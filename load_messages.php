<?php
session_start();
include "db.php";

$user_id = isset($_GET["user_id"]) ? intval($_GET["user_id"]) : NULL;

if ($_SESSION["userrole"] === "admin" && $user_id) {
    $stmt = $conn->prepare("SELECT * FROM messages WHERE user_id = ? ORDER BY created_at ASC");
    $stmt->bind_param("i", $user_id);
} 
elseif ($_SESSION["userrole"] === "user") {
    $user_id = intval($_SESSION["user_id"]);
    $stmt = $conn->prepare("SELECT * FROM messages WHERE user_id = ? ORDER BY created_at ASC");
    $stmt->bind_param("i", $user_id);
} 
else {
    $stmt = $conn->prepare("SELECT * FROM messages ORDER BY created_at ASC");
}

$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    if ($row['sender'] == "user") {
        echo "<div class='message-wrapper user-message'>
                <div class='message user'>" . htmlspecialchars($row['message']) . "</div>
              </div>";
    } else {
        echo "<div class='message-wrapper admin-message'>
                <div class='message admin'>" . htmlspecialchars($row['message']) . "</div>
              </div>";
    }
}

$stmt->close();
?>
