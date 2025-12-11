<?php
if (isset($input['id']) && isset($input['status'])) {
    
    $id     = $input['id'];
    $status = $input['status'];

    // Pastikan user hanya bisa update bukunya sendiri (Security Check)
    $stmt = $conn->prepare("UPDATE books SET status = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("sii", $status, $id, $user_id);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(["status" => "success", "message" => "Status berhasil diupdate"]);
        } else {
            echo json_encode(["status" => "warning", "message" => "Tidak ada perubahan atau ID salah"]);
        }
    } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Gagal update data"]);
    }

} else {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "ID atau Status tidak ditemukan"]);
}
?>