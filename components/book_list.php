<?php

$filtered_books = array_filter($books, function($b) use ($category_key) {
    return $b['status'] === $category_key;
});
?>

<div class="bg-white p-4 rounded-lg shadow border-t-4 <?php echo $border_color; ?>">
    <h3 class="font-bold text-gray-700 mb-2 flex items-center gap-2">
        <i class="<?php echo $category_icon; ?>"></i> 
        <?php echo $category_label; ?>
        <span class="bg-gray-100 px-2 rounded-full text-xs"><?php echo count($filtered_books); ?></span>
    </h3>

    <ul>
        <?php if (empty($filtered_books)): ?>
            <li class="text-gray-400 text-sm py-4 text-center">Tidak ada buku</li>
        <?php else: ?>
            <?php foreach ($filtered_books as $book): ?>
                <li class="flex justify-between items-center bg-gray-50 p-2 mb-2 rounded border">
                    <div>
                        <p class="font-bold text-gray-800"><?php echo htmlspecialchars($book['title']); ?></p>
                        <p class="text-xs text-gray-500"><?php echo htmlspecialchars($book['author']); ?></p>
                    </div>

                    <div class="flex gap-1">
                        
                        <?php if ($category_key === 'ingin_dibaca'): ?>
                            <a href="process.php?action=update&id=<?= $book['id'] ?>&status=sedang_dibaca" 
                               class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs rounded hover:bg-yellow-200">
                               Baca ➡️
                            </a>
                        <?php endif; ?>

                        <?php if ($category_key === 'sedang_dibaca'): ?>
                            <a href="process.php?action=update&id=<?= $book['id'] ?>&status=sudah_dibaca" 
                               class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded hover:bg-green-200">
                               Selesai ✅
                            </a>
                        <?php endif; ?>

                        <a href="process.php?action=delete&id=<?= $book['id'] ?>" 
                           onclick="return confirm('Hapus buku ini?')"
                           class="px-2 py-1 text-red-400 hover:text-red-600">
                           <i class="fas fa-trash"></i>
                        </a>

                    </div>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</div>