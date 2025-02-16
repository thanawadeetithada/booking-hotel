<?php
session_start();
include 'db.php';

header('Content-Type: application/json');

$sql = "SELECT user_id, MAX(notification) AS notification FROM messages GROUP BY user_id";
$result = $conn->query($sql);

$rows = [];
while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
}

echo json_encode($rows);
?>
