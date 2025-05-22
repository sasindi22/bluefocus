<?php
session_start();
header('Content-Type: application/json');

require_once 'connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Use POST"]);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    echo json_encode(["status" => "error", "message" => "Invalid JSON"]);
    exit;
}

$title       = trim($input['title']       ?? '');
$description = trim($input['description'] ?? '');
$startTime   = trim($input['startTime']   ?? '');
$endTime     = trim($input['endTime']     ?? '');
$category    = trim($input['category']    ?? '');
$priority_id = (int)($input['priority']   ?? 0);
$reminder    = isset($input['reminder']) ? (int)$input['reminder'] : 0;
$date        = trim($input['date']        ?? '');

if (!$title || !$date) {
    echo json_encode(["status" => "error", "message" => "Title and date are required"]);
    exit;
}

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo json_encode(["status" => "error", "message" => "User not authenticated"]);
    exit;
}

$stmt = $conn->prepare(
    "INSERT INTO tasks
     (title, description, start_time, end_time, category,
      priority_priority_id, reminder, task_date, users_user_id)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
);

$formatted_date = date('Y-m-d', strtotime($date));

$stmt->bind_param(
    "sssssiisi",
    $title,
    $description,
    $startTime,
    $endTime,
    $category,
    $priority_id,
    $reminder,
    $formatted_date,
    $user_id
);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Task saved"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to save task: " . $stmt->error]);
}

$stmt->close();
$conn->close();
