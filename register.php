<?php
include 'koneksi.php';

$input = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $input['username'];
    // Enkripsi password agar aman
    $password = password_hash($input['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Registrasi Berhasil"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Username sudah ada"]);
    }
}
$conn->close();
?>