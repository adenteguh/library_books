<?php
// Ambil buku HANYA milik user yang sedang login
$stmt = $conn->prepare("SELECT * FROM books WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $books = [];
    while ($row = $result->fetch_assoc()) { 
        $books[] = $row; 
    }
    echo json_encode($books);
} else {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Gagal mengambil data"]);
}
?>