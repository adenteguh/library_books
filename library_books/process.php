<?php
require 'db.php';

// Cek Login sederhana (bisa dikembangkan)
if (!isset($_SESSION['username'])) {
    header("Location: index.php?error=belum_login");
    exit;
}

$user_id = $_SESSION['user_id'] ?? 1; // Default user ID 1 untuk demo
$action  = $_GET['action'] ?? '';

// --- LOGIK TAMBAH (INPUT) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'add') {
    $title  = $_POST['title'];
    $author = $_POST['author'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO books (user_id, title, author, status) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $title, $author, $status]);
    
    header("Location: index.php");
    exit;
}

// --- LOGIK UPDATE STATUS ---
if ($action === 'update' && isset($_GET['id']) && isset($_GET['status'])) {
    $id = $_GET['id'];
    $status = $_GET['status'];

    $stmt = $conn->prepare("UPDATE books SET status = ? WHERE id = ?");
    $stmt->execute([$status, $id]);

    header("Location: index.php");
    exit;
}

// --- LOGIK DELETE ---
if ($action === 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $stmt = $conn->prepare("DELETE FROM books WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: index.php");
    exit;
}
?>