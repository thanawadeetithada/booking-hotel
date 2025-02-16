<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST["user_id"])) {
        echo "error: no user_id provided";
        exit();
    }

    $user_id = intval($_POST["user_id"]);

    $sql = "DELETE FROM messages WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
