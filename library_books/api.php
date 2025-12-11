<?php
include 'koneksi.php';

// Cek apakah user sudah login? Jika belum, tolak akses.
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Harap Login Terlebih Dahulu"]);
    exit;
}

$user_id = $_SESSION['user_id'];
$method  = $_SERVER['REQUEST_METHOD'];
$input   = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        // Ambil buku HANYA milik user yang sedang login
        $stmt = $conn->prepare("SELECT * FROM books WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $books = [];
        while ($row = $result->fetch_assoc()) { $books[] = $row; }
        echo json_encode($books);
        break;

    case 'POST':
        // Tambah Buku
        if (isset($input['title'])) {
            $stmt = $conn->prepare("INSERT INTO books (user_id, title, author, status) VALUES (?, ?, ?, ?)");
            $status = $input['status'] ?? 'ingin_dibaca';
            $stmt->bind_param("isss", $user_id, $input['title'], $input['author'], $status);
            $stmt->execute();
            echo json_encode(["status" => "success"]);
        }
        break;

    case 'PUT':
        // Update Status
        if (isset($input['id'])) {
            $stmt = $conn->prepare("UPDATE books SET status = ? WHERE id = ? AND user_id = ?");
            $stmt->bind_param("sii", $input['status'], $input['id'], $user_id);
            $stmt->execute();
            echo json_encode(["status" => "success"]);
        }
        break;

    case 'DELETE':
        // Hapus Buku
        if (isset($_GET['id'])) {
            $stmt = $conn->prepare("DELETE FROM books WHERE id = ? AND user_id = ?");
            $bid = $_GET['id'];
            $stmt->bind_param("ii", $bid, $user_id);
            $stmt->execute();
            echo json_encode(["status" => "success"]);
        }
        break;
}
$conn->close();
?>