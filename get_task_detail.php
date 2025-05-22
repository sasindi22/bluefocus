<?php
header('Content-Type: application/json');
require_once 'connection.php'; // provides $conn

if (!isset($_GET['task_id']) || !is_numeric($_GET['task_id'])) {
    echo json_encode(["error" => "Invalid task id"]);
    exit;
}

$task_id = (int)$_GET['task_id'];

$stmt = $conn->prepare("SELECT task_id, task_date, title, description, start_time, end_time, category FROM tasks WHERE task_id = ?");
$stmt->bind_param("i", $task_id);
$stmt->execute();

$result = $stmt->get_result();
$task = $result->fetch_assoc();

if ($task) {
    echo json_encode($task);
} else {
    echo json_encode(["error" => "Task not found"]);
}

$stmt->close();
$conn->close();
?>
