<?php
include 'koneksi.php';

$input = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $input['username'];
    $password = $input['password'];

    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            // Simpan data user ke Session PHP
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            echo json_encode(["status" => "success", "username" => $row['username']]);
        } else {
            echo json_encode(["status" => "error", "message" => "Password Salah"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Username tidak ditemukan"]);
    }
}
$conn->close();
?>