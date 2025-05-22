<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "User not authenticated"]);
    exit;
}
$user_id = (int)$_SESSION['user_id'];

require_once 'connection.php';  

$sql = "
    SELECT
        t.id,
        t.title,
        t.start_time,
        t.end_time,
        t.scheduled_date,
        s.status_id,
        s.title       AS status_title,
        p.priority_id,
        p.name        AS priority_name,
        u.user_id,
        u.username
    FROM todo t
    JOIN status   s ON t.status_id   = s.status_id
    JOIN priority p ON t.priority_id = p.priority_id
    JOIN users    u ON t.user_id     = u.user_id
    WHERE u.user_id = ?
      AND t.scheduled_date = CURDATE()
    ORDER BY t.scheduled_date, t.start_time
";

if ($stmt = $conn->prepare($sql)) {

    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $todos  = [];
    while ($row = $result->fetch_assoc()) {
        $todos[] = $row;
    }

    echo json_encode(["status" => "success", "todos" => $todos]);

    $stmt->close();

} else {
    http_response_code(500);
    echo json_encode([
        "status"  => "error",
        "message" => "Database error: " . $conn->error
    ]);
}

$conn->close();
?>
