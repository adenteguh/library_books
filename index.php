<?php
require 'db.php';

// Simulasi Login (Hardcode untuk demo)
if (!isset($_SESSION['username'])) {
    $_SESSION['username'] = 'UserDemo';
    $_SESSION['user_id'] = 1;
}

// Ambil semua data buku dari Database
$stmt = $conn->prepare("SELECT * FROM books WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Hitung data untuk Chart
$count_ingin = count(array_filter($books, fn($b) => $b['status'] == 'ingin_dibaca'));
$count_sedang = count(array_filter($books, fn($b) => $b['status'] == 'sedang_dibaca'));
$count_sudah = count(array_filter($books, fn($b) => $b['status'] == 'sudah_dibaca'));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Modular Library</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 font-sans">

<div class="min-h-screen p-4 flex justify-center">
    <div class="w-full max-w-6xl">
        
        <nav class="flex justify-between items-center bg-white p-4 rounded-lg shadow mb-6 border-l-4 border-indigo-500">
            <h2 class="text-xl font-bold text-gray-700">📚 Library: <?php echo $_SESSION['username']; ?></h2>
            <a href="logout.php" class="text-red-500 hover:text-red-700 font-semibold"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </nav>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
            
            <div class="md:col-span-4 space-y-6">
                <?php include 'components/form_input.php'; ?>

                <div class="bg-white p-4 rounded-lg shadow h-64 relative">
                    <canvas id="bookChart"></canvas>
                </div>
            </div>

            <div class="md:col-span-8 space-y-4">
                
                <?php 
                    $category_key = 'ingin_dibaca';
                    $category_label = 'Ingin Dibaca';
                    $category_icon = 'fas fa-clock';
                    $border_color = 'border-gray-400';
                    include 'components/book_list.php'; 
                ?>

                <?php 
                    $category_key = 'sedang_dibaca';
                    $category_label = 'Sedang Dibaca';
                    $category_icon = 'fas fa-book-open';
                    $border_color = 'border-yellow-400';
                    include 'components/book_list.php'; 
                ?>

                <?php 
                    $category_key = 'sudah_dibaca';
                    $category_label = 'Sudah Dibaca';
                    $category_icon = 'fas fa-check-circle';
                    $border_color = 'border-green-500';
                    include 'components/book_list.php'; 
                ?>
                
            </div>
        </div>
    </div>
</div>

<script>
    const ctx = document.getElementById('bookChart');
    new Chart(ctx, {
        type: 'doughnut',
        data: { 
            labels: ['Ingin', 'Sedang', 'Selesai'], 
            datasets: [{ 
                data: [<?= $count_ingin ?>, <?= $count_sedang ?>, <?= $count_sudah ?>], 
                backgroundColor: ['#9CA3AF', '#FBBF24', '#10B981'] 
            }] 
        },
        options: { responsive: true, maintainAspectRatio: false }
    });
</script>

</body>
</html>