<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'Exception.php';
require 'PHPMailer.php';
require 'SMTP.php';
require 'OAuth.php';
require_once 'connection.php';

date_default_timezone_set('Asia/Colombo');

if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'DB connection failed']));
}

$email = $_POST['email'] ?? '';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid email']);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Email not registered']);
    exit;
}

$code = rand(100000, 999999);
$expires_at = date("Y-m-d H:i:s", time() + 600);

$stmt = $conn->prepare("INSERT INTO password_resets (email, code, expires_at) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $email, $code, $expires_at);
$stmt->execute();

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = ''; // replace with your email
    $mail->Password   = '';      // replace with your app password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $mail->setFrom('example@gmail.com', 'BlueFocus');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Password Reset Code - BlueFocus';
    $mail->Body    = "<p>Your password reset code is: <strong>$code</strong></p><p>This code will expire in 10 minutes.</p>";

    $mail->send();
    echo json_encode(['status' => 'success', 'message' => 'Verification code sent']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Mailer Error: ' . $mail->ErrorInfo]);
}
