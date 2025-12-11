<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History Book - Modular PHP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style> body { background: #f8fafc; font-family: 'Segoe UI', sans-serif; } </style>
</head>
<body>

<div id="app" class="min-h-screen flex flex-col items-center justify-center p-4">

    <div v-if="!isLoggedIn" class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md border-t-4 border-blue-600">
        <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">{{ isRegisterMode ? 'Daftar Akun' : 'Login Aplikasi' }}</h1>
        
        <form @submit.prevent="authSubmit">
            <div class="mb-4">
                <label class="block text-gray-600 font-bold mb-2">Username</label>
                <input v-model="authForm.username" type="text" required class="w-full p-3 border rounded focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div class="mb-6">
                <label class="block text-gray-600 font-bold mb-2">Password</label>
                <input v-model="authForm.password" type="password" required class="w-full p-3 border rounded focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded hover:bg-blue-700 transition">
                {{ isRegisterMode ? 'Daftar' : 'Masuk' }}
            </button>
        </form>

        <p class="mt-4 text-center text-sm text-gray-600">
            {{ isRegisterMode ? 'Sudah punya akun?' : 'Belum punya akun?' }}
            <a href="#" @click.prevent="isRegisterMode = !isRegisterMode" class="text-blue-600 font-bold hover:underline">
                {{ isRegisterMode ? 'Login' : 'Daftar' }}
            </a>
        </p>
    </div>

    <div v-else class="w-full max-w-6xl animate-fade-in">
        <nav class="flex justify-between items-center bg-white p-4 rounded-lg shadow mb-6 border-l-4 border-indigo-500">
            <h2 class="text-xl font-bold text-gray-700">📚 Library: {{ currentUsername }}</h2>
            <button @click="logout" class="text-red-500 hover:text-red-700 font-semibold"><i class="fas fa-sign-out-alt"></i> Logout</button>
        </nav>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
            <div class="md:col-span-4 space-y-6">
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="font-bold mb-4 text-gray-700">Tambah Buku</h3>
                    <form @submit.prevent="addBook">
                        <input v-model="form.title" class="w-full border p-2 mb-2 rounded" placeholder="Judul Buku" required>
                        <input v-model="form.author" class="w-full border p-2 mb-2 rounded" placeholder="Penulis" required>
                        <select v-model="form.status" class="w-full border p-2 mb-4 rounded">
                            <option value="ingin_dibaca">Ingin Dibaca</option>
                            <option value="sedang_dibaca">Sedang Dibaca</option>
                            <option value="sudah_dibaca">Sudah Dibaca</option>
                        </select>
                        <button class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700">Simpan</button>
                    </form>
                </div>
                <div class="bg-white p-4 rounded-lg shadow h-64 relative">
                    <canvas id="bookChart"></canvas>
                </div>
            </div>

            <div class="md:col-span-8 space-y-4">
                <div v-for="(cat, key) in categories" :key="key" :class="`bg-white p-4 rounded-lg shadow border-t-4 ${cat.border}`">
                    <h3 class="font-bold text-gray-700 mb-2 flex items-center gap-2">
                        <i :class="cat.icon"></i> {{ cat.text }} <span class="bg-gray-100 px-2 rounded-full text-xs">{{ getBooks(key).length }}</span>
                    </h3>
                    <ul>
                        <li v-for="book in getBooks(key)" :key="book.id" class="flex justify-between items-center bg-gray-50 p-2 mb-2 rounded border">
                            <div>
                                <p class="font-bold text-gray-800">{{ book.title }}</p>
                                <p class="text-xs text-gray-500">{{ book.author }}</p>
                            </div>
                            <div class="flex gap-1">
                                <button v-if="key === 'ingin_dibaca'" @click="updateStatus(book, 'sedang_dibaca')" class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs rounded">Baca ➡️</button>
                                <button v-if="key === 'sedang_dibaca'" @click="updateStatus(book, 'sudah_dibaca')" class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded">Selesai ✅</button>
                                <button @click="deleteBook(book.id)" class="px-2 py-1 text-red-400 hover:text-red-600"><i class="fas fa-trash"></i></button>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const { createApp } = Vue;

    createApp({
        data() {
            return {
                isLoggedIn: false,
                isRegisterMode: false,
                currentUsername: '',
                authForm: { username: '', password: '' },
                form: { title: '', author: '', status: 'ingin_dibaca' },
                books: [],
                chartInstance: null,
                categories: {
                    'ingin_dibaca': { text: 'Ingin Dibaca', border: 'border-gray-400', icon: 'fas fa-clock' },
                    'sedang_dibaca': { text: 'Sedang Dibaca', border: 'border-yellow-400', icon: 'fas fa-book-open' },
                    'sudah_dibaca': { text: 'Sudah Dibaca', border: 'border-green-500', icon: 'fas fa-check-circle' }
                }
            }
        },
        mounted() {
            this.checkSession();
        },
        methods: {
            // Helper Function untuk Fetch API
            async fetchData(url, method = 'GET', body = null) {
                const options = { method, headers: { 'Content-Type': 'application/json' } };
                if (body) options.body = JSON.stringify(body);
                const res = await fetch(url, options);
                return await res.json();
            },

            // --- AUTH LOGIC ---
            checkSession() {
                // Panggil file check_session.php
                this.fetchData('check_session.php').then(data => {
                    if (data.status === 'logged_in') {
                        this.isLoggedIn = true;
                        this.currentUsername = data.username;
                        this.fetchBooks();
                    }
                });
            },
            authSubmit() {
                // Tentukan mau ke login.php atau register.php
                const endpoint = this.isRegisterMode ? 'register.php' : 'login.php';
                
                this.fetchData(endpoint, 'POST', this.authForm).then(data => {
                    if (data.status === 'success') {
                        if (this.isRegisterMode) {
                            alert('Registrasi berhasil, silakan login');
                            this.isRegisterMode = false;
                        } else {
                            this.isLoggedIn = true;
                            this.currentUsername = data.username || this.authForm.username;
                            this.fetchBooks();
                        }
                    } else {
                        alert(data.message);
                    }
                });
            },
            logout() {
                // Panggil logout.php
                this.fetchData('logout.php').then(() => {
                    this.isLoggedIn = false;
                    this.books = [];
                    this.authForm.username = '';
                    this.authForm.password = '';
                });
            },

            // --- BOOK LOGIC (Panggil api.php) ---
            fetchBooks() {
                this.fetchData('api.php').then(data => {
                    this.books = Array.isArray(data) ? data : [];
                    this.renderChart();
                });
            },
            getBooks(status) { return this.books.filter(b => b.status === status); },
            addBook() {
                this.fetchData('api.php', 'POST', this.form).then(() => {
                    this.form.title = ''; this.form.author = ''; 
                    this.fetchBooks();
                });
            },
            updateStatus(book, status) {
                this.fetchData('api.php', 'PUT', { id: book.id, status }).then(() => this.fetchBooks());
            },
            deleteBook(id) {
                if(confirm('Hapus?')) fetch(`api.php?id=${id}`, { method: 'DELETE' }).then(() => this.fetchBooks());
            },
            
            // --- CHART LOGIC ---
            renderChart() {
                const ctx = document.getElementById('bookChart');
                if(!ctx) return;
                if(this.chartInstance) this.chartInstance.destroy();
                
                const counts = [this.getBooks('ingin_dibaca').length, this.getBooks('sedang_dibaca').length, this.getBooks('sudah_dibaca').length];
                this.chartInstance = new Chart(ctx, {
                    type: 'doughnut',
                    data: { labels: ['Ingin', 'Sedang', 'Selesai'], datasets: [{ data: counts, backgroundColor: ['#9CA3AF', '#FBBF24', '#10B981'] }] },
                    options: { responsive: true, maintainAspectRatio: false }
                });
            }
        }
    }).mount('#app');
</script>
</body>
</html>