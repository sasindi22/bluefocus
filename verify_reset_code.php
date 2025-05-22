<?php
header('Content-Type: application/json');

require_once 'connection.php';

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'DB connection failed']);
    exit;
}

$email    = trim($_POST['email']             ?? '');
$code     = trim($_POST['verification_code'] ?? '');
$password = trim($_POST['new_password']      ?? '');

file_put_contents(
    __DIR__ . '/debug_reset.log',
    date('c') . '  ' . json_encode($_POST) . PHP_EOL,
    FILE_APPEND
);

$errs = [];
if (!filter_var($email, FILTER_VALIDATE_EMAIL))    $errs[] = 'email';
if (!preg_match('/^\d{6}$/', $code))                $errs[] = 'verification_code';
if (strlen($password) < 6)                          $errs[] = 'new_password';

if ($errs) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Invalid input: ' . implode(', ', $errs)
    ]);
    exit;
}

$stmt = $conn->prepare(
    "SELECT 1 FROM password_resets
     WHERE email = ? AND code = ? AND expires_at > NOW()"
);
$stmt->bind_param("ss", $email, $code);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid or expired code']);
    exit;
}

$conn->begin_transaction();

try {
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
    $stmt->bind_param("ss", $password, $email);
    if (!$stmt->execute()) {
        throw new Exception("Failed to update password");
    }

    $stmt = $conn->prepare("DELETE FROM password_resets WHERE email = ?");
    $stmt->bind_param("s", $email);
    if (!$stmt->execute()) {
        throw new Exception("Failed to delete reset code");
    }

    $conn->commit();

    echo json_encode(['status' => 'success', 'message' => 'Password updated']);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
