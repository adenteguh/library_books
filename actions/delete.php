<?php
if (isset($_GET['id'])) {
    
    $bid = $_GET['id'];

    // Pastikan user hanya bisa hapus bukunya sendiri
    $stmt = $conn->prepare("DELETE FROM books WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $bid, $user_id);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(["status" => "success", "message" => "Buku berhasil dihapus"]);
        } else {
            http_response_code(404);
            echo json_encode(["status" => "error", "message" => "Buku tidak ditemukan atau bukan milik Anda"]);
        }
    } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Gagal menghapus data"]);
    }

} else {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "ID buku diperlukan"]);
}
?>