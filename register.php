<?php
header('Content-Type: application/json');

require_once 'connection.php'; 

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
    exit;
}

$username = trim($_POST['username'] ?? '');
$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $email === '' || $password === '') {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Missing fields"]);
    exit;
}

$hashedPassword = $password;

$stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $email, $hashedPassword);

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    http_response_code(409);
    echo json_encode(["status" => "error", "message" => "Username or email already exists"]);
}

$stmt->close();
$conn->close();
