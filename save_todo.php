<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Use POST"]);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    echo json_encode(["status" => "error", "message" => "Invalid JSON"]);
    exit;
}

$title       = trim($input['title']        ?? '');
$priority_id = (int)($input['priority']    ?? 0);
$status_id   = (int)($input['status']      ?? 0);
$startTime   = trim($input['startTime']    ?? '');
$endTime     = trim($input['endTime']      ?? '');
$scheduled   = trim($input['date']         ?? '');

if (!$title || !$scheduled || !$startTime || !$endTime || !$priority_id || !$status_id) {
    echo json_encode([
        "status" => "error",
        "message" => "title, date, startTime, endTime, priority, status are required"
    ]);
    exit;
}

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo json_encode(["status" => "error", "message" => "User not authenticated"]);
    exit;
}

require_once 'connection.php';

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed"]);
    exit;
}

$stmt = $conn->prepare(
    "INSERT INTO todo
     (title, priority_id, status_id, scheduled_date, start_time, end_time, user_id)
     VALUES (?, ?, ?, ?, ?, ?, ?)"
);

if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "SQL error: " . $conn->error]);
    exit;
}

$scheduled_date = date('Y-m-d', strtotime($scheduled));
$start_time     = date('H:i:s', strtotime($startTime));
$end_time       = date('H:i:s', strtotime($endTime));

$stmt->bind_param(
    "siisssi",
    $title,
    $priority_id,
    $status_id,
    $scheduled_date,
    $start_time,
    $end_time,
    $user_id
);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Todo saved"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to save todo: " . $stmt->error]);
}

$stmt->close();
$conn->close();
