<?php
require_once 'connection.php';

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id'])) {
  echo json_encode(["status" => "error", "message" => "Missing task ID"]);
  exit;
}

$id = intval($data['id']);

$stmt = $conn->prepare("DELETE FROM todo WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
  echo json_encode(["status" => "success"]);
} else {
  echo json_encode(["status" => "error", "message" => "Failed to delete"]);
}

$stmt->close();
$conn->close();
?>
