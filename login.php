<?php
session_start();
header('Content-Type: application/json');

require_once 'connection.php'; 

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Use POST"]);
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Missing email or password"]);
    exit;
}

$stmt = $conn->prepare("SELECT user_id, password FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Invalid credentials"]);
    exit;
}

$row = $result->fetch_assoc();

if ($row['password'] === $password) {
    $_SESSION['user_id'] = $row['user_id'];
    echo json_encode(["status" => "success", "message" => "You are logged in!", "user_id" => $row['user_id']]);
} else {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Wrong password"]);
}

$stmt->close();
$conn->close();
?>
