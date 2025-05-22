<?php
session_start();
header('Content-Type: application/json');

require_once 'connection.php';  

$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized. Please log in."]);
    exit;
}

$year  = isset($_GET['year'])  ? (int)$_GET['year']  : 0;
$month = isset($_GET['month']) ? (int)$_GET['month'] : 0;

if ($year < 2000 || $year > 2100 || $month < 1 || $month > 12) {
    echo json_encode(["error" => "Invalid year or month"]);
    exit;
}

$startDate = sprintf("%04d-%02d-01", $year, $month);
$dt        = new DateTime($startDate);
$endDate   = $dt->format('Y-m-t');

$stmt = $conn->prepare(
    "SELECT task_id, task_date, title, category
     FROM tasks
     WHERE task_date BETWEEN ? AND ?
       AND users_user_id = ?"
);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(["error" => "Prepare failed: " . $conn->error]);
    exit;
}

$stmt->bind_param("ssi", $startDate, $endDate, $userId);

if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(["error" => "Query failed: " . $stmt->error]);
    exit;
}

$result = $stmt->get_result();
$tasks  = [];

while ($row = $result->fetch_assoc()) {
    $tasks[] = [
        "id"       => $row['task_id'],
        "date"     => $row['task_date'],
        "title"    => $row['title'],
        "category" => $row['category']
    ];
}

echo json_encode($tasks);

$stmt->close();
$conn->close();
