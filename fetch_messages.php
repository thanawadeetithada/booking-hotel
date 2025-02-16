<?php
session_start();
include 'db.php';

if (!isset($_SESSION['userrole']) || $_SESSION['userrole'] !== 'admin') {
    echo json_encode([]);
    exit();
}

$sql = "SELECT m.id, m.user_id, u.first_name, u.last_name, u.email, m.created_at, m.notification
        FROM messages m
        LEFT JOIN users u ON m.user_id = u.user_id
        GROUP BY m.user_id
        ORDER BY m.created_at DESC";

$result = $conn->query($sql);

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

echo json_encode($messages);
?>
