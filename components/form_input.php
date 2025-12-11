<div class="bg-white p-6 rounded-lg shadow">
    <h3 class="font-bold mb-4 text-gray-700">Tambah Buku</h3>
    <form action="process.php?action=add" method="POST">
        <input name="title" class="w-full border p-2 mb-2 rounded" placeholder="Judul Buku" required>
        <input name="author" class="w-full border p-2 mb-2 rounded" placeholder="Penulis" required>
        <select name="status" class="w-full border p-2 mb-4 rounded">
            <option value="ingin_dibaca">Ingin Dibaca</option>
            <option value="sedang_dibaca">Sedang Dibaca</option>
            <option value="sudah_dibaca">Sudah Dibaca</option>
        </select>
        <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700">
            Simpan
        </button>
    </form>
</div>