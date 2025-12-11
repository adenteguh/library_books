<?php
include 'koneksi.php';

if (isset($_SESSION['user_id'])) {
    echo json_encode([
        "status" => "logged_in", 
        "user_id" => $_SESSION['user_id'], 
        "username" => $_SESSION['username']
    ]);
} else {
    echo json_encode(["status" => "guest"]);
}
?>