<?php
session_start();
include 'db.php';

header('Content-Type: application/json');

$sql = "SELECT COUNT(*) AS unread_count FROM messages WHERE notification = 1";
$result = $conn->query($sql);

if ($result) {
    $row = $result->fetch_assoc();
    echo json_encode(['unread_count' => $row['unread_count']]);
} else {
    echo json_encode(['unread_count' => 0]);
}
?>
