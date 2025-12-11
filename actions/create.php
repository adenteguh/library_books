<?php
if (isset($input['title']) && isset($input['author'])) {
    
    $title  = $input['title'];
    $author = $input['author'];
    $status = $input['status'] ?? 'ingin_dibaca'; // Default value

    $stmt = $conn->prepare("INSERT INTO books (user_id, title, author, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $title, $author, $status);
    
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Buku berhasil ditambahkan"]);
    } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Gagal menyimpan buku"]);
    }

} else {
    http_response_code(400); // Bad Request
    echo json_encode(["status" => "error", "message" => "Data tidak lengkap"]);
}
?>