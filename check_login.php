<?php
session_start();

header('Content-Type: application/json');

$response = [];

if (!isset($_SESSION['user_id'])) {
    $response['status'] = 'not_logged_in';
} else {
    $response['status'] = 'logged_in';
    $response['first_name'] = isset($_SESSION['first_name']) ? $_SESSION['first_name'] : 'Guest';
    $response['last_name'] = isset($_SESSION['last_name']) ? $_SESSION['last_name'] : 'User';
}

echo json_encode($response);
?>
