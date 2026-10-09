<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Pesanan - NasPad Zendry</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #FFF7ED !important; 
        }
        /* subtle paper texture */
        body::before {
            content:''; position:fixed; inset:0; pointer-events:none; opacity:.55;
            background-image: radial-gradient(rgba(124,45,18,.06) 1px, transparent 1px);
            background-size:22px 22px; mask-image: linear-gradient(to bottom, rgba(0,0,0,.18), transparent 85%);
            z-index: -1;
        }

        /* Override dark mode classes with light mode equivalent */
        .bg-gacoan-black { background-color: #FFF7ED !important; }
        .bg-gacoan-gray { background-color: #FFFFFF !important; }
        .border-gacoan-gray { border-color: #FED7AA !important; }
        .text-gacoan-gold { color: #EA580C !important; }
        .bg-gacoan-gold { background-color: #EA580C !important; color: #FFFFFF !important; }
        
        /* Typography overrides */
        .text-gray-300, .text-gray-400, .text-gray-500 { color: #57534E !important; }
        .text-gray-100 { color: #000000 !important; }
        
        /* Interactive elements */
        .bg-gray-800, .bg-gray-700 { background-color: #FFF7ED !important; border: 1px solid #FED7AA; }
        .hover\:bg-gray-800:hover { background-color: #FFEDD5 !important; }
        .hover\:bg-gray-700:hover { background-color: #FFEDD5 !important; }
        .hover\:border-gacoan-gold:hover { border-color: #EA580C !important; }
        
        /* Header blur */
        header .bg-gacoan-black\/80 { background-color: rgba(255, 247, 237, 0.9) !important; }
        
        /* Fix cart badge text */
        .bg-gacoan-gold svg { color: #FFFFFF !important; }
        .bg-gacoan-gold.text-black { color: #FFFFFF !important; }

        /* Floating Total Indicator */
        #floating-total-container {
            background-color: #FFFFFF !important;
            border-color: #FED7AA !important;
            box-shadow: 0 10px 30px rgba(234, 88, 12, 0.1) !important;
        }
        #floating-total-container .text-white { color: #000000 !important; }
    </style>
</head>
<body class="bg-gacoan-black text-[#000000] pb-32">

    <header class="fixed top-0 left-0 w-full bg-gacoan-black z-50 border-b border-gacoan-gray shadow-md">
        <div class="flex justify-between items-center p-4">
            <h1 class="text-3xl font-extrabold text-gacoan-gold tracking-tight">NasPad Zendry</h1>
            <div class="flex items-center gap-4">
                <button class="text-gray-400 hover:text-[#000000] transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31a8.967 8.967 0 0 1-2.312-6.022c0-3.121-1.692-5.747-4.11-6.707A3.332 3.332 0 0 0 12 1.5c-1.28 0-2.4.717-2.986 1.777a8.967 8.967 0 0 1-4.11 6.707 8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                    </svg>
                </button>
                <button class="text-gray-400 hover:text-[#000000] transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="flex items-center gap-3 p-4 pt-1 overflow-x-auto border-b border-gacoan-gray bg-gacoan-black/80 backdrop-blur-sm">
            <button onclick="filterKategori('all', this)" class="tab-btn bg-gacoan-gold text-black font-semibold px-6 py-2.5 rounded-xl whitespace-nowrap transition">Semua</button>
            
            <?php foreach($categories as $c): ?>
                <button onclick="filterKategori(<?= $c['id'] ?>, this)" class="tab-btn bg-gacoan-gray text-gray-300 font-medium px-6 py-2.5 rounded-xl whitespace-nowrap hover:bg-gray-800 transition">
                    <?= esc($c['name']) ?>
                </button>
            <?php endforeach; ?>
        </div>
    </header>

    <div class="p-4 pt-44">
        <h2 class="text-xl font-semibold mb-6 text-gray-300">Daftar Menu (Meja <?= esc($table_id); ?>)</h2>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4" id="product-container">
            <?php foreach($products as $p): ?>
            <div class="product-card bg-gacoan-gray rounded-3xl p-3 border border-gacoan-gray shadow-xl group hover:border-gacoan-gold transition-colors flex flex-col justify-between" data-kategori="<?= $p['category_id'] ?>">
                <div>
                    <div class="bg-gray-700 h-36 rounded-2xl mb-4 flex items-center justify-center text-gray-500 text-sm overflow-hidden border border-gacoan-gray">
                        <?php if(!empty($p['image'])): ?>
                            <img src="<?= base_url('uploads/' . esc($p['image'])); ?>" alt="<?= esc($p['name']) ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <span>No Image</span>
                        <?php endif; ?>
                    </div>
                    <h3 class="font-bold text-gray-100 text-lg mb-1 group-hover:text-[#000000] transition"><?= esc($p['name']) ?></h3>
                    <p class="text-gacoan-gold font-bold text-xl mb-4">Rp <?= number_format($p['price'], 0, ',', '.') ?></p>
                </div>
                
                <div id="controls-<?= $p['id'] ?>" class="w-full mt-auto">
                    <button onclick="handleTambahProduk(<?= $p['id'] ?>, '<?= addslashes($p['name']) ?>', <?= $p['price'] ?>)" 
                            class="add-btn bg-gacoan-gold text-black w-full h-14 rounded-xl flex items-center justify-center hover:opacity-90 transition active:scale-95 shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </button>

                    <div class="qty-controls hidden flex items-center justify-between gap-3 bg-gacoan-gray h-14 rounded-xl border border-gacoan-gold p-1 shadow-lg">
                        <button onclick="handleKurangiKuantitas(<?= $p['id'] ?>)" class="w-12 h-12 bg-gray-800 rounded-lg flex items-center justify-center text-gacoan-gold hover:bg-gray-700 transition active:scale-95">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14" />
                            </svg>
                        </button>
                        <span class="text-3xl font-extrabold text-[#000000] qty-text" id="qty-text-<?= $p['id'] ?>">1</span>
                        <button onclick="handleTambahKuantitas(<?= $p['id'] ?>)" class="w-12 h-12 bg-gacoan-gold rounded-lg flex items-center justify-center text-black hover:opacity-90 transition active:scale-95">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Floating Total Indicator -->
    <div id="floating-total-container" class="fixed bottom-[110px] left-1/2 -translate-x-1/2 bg-[#1E1E1E] border border-[#2D2D2D] text-white px-5 py-2.5 rounded-full shadow-[0_8px_20px_rgba(0,0,0,0.5)] flex items-center gap-3 z-40 transition-all duration-300 opacity-0 pointer-events-none translate-y-4">
        <span class="text-xs font-medium text-gray-400">Total</span>
        <span class="text-sm font-bold text-gacoan-gold" id="totalHarga">Rp 0</span>
    </div>

    <!-- Bottom Navbar (Mobile App Style) -->
    <div class="fixed bottom-6 left-1/2 -translate-x-1/2 w-[calc(100%-2.5rem)] max-w-[400px] bg-[#FDF8F3] backdrop-blur-xl border border-white/40 shadow-[0_20px_40px_rgba(0,0,0,0.2)] rounded-[2.5rem] px-5 py-3 flex justify-between items-center z-50">
        <!-- Beranda -->
        <a href="#" class="flex flex-col items-center gap-1 w-14 group">
            <div class="w-10 h-10 rounded-full flex items-center justify-center text-[#9CA3AF] group-hover:text-[#EA580C] transition-colors">
                <i class="fa-solid fa-house text-xl"></i>
            </div>
            <span class="text-[10px] font-semibold text-[#9CA3AF] group-hover:text-[#EA580C]">Beranda</span>
        </a>

        <!-- Menu (Active) -->
        <a href="#" class="flex flex-col items-center gap-1 w-14">
            <div class="w-11 h-11 rounded-full bg-[#EA580C] flex items-center justify-center text-white shadow-[0_4px_12px_rgba(234,88,12,0.3)]">
                <i class="fa-solid fa-border-all text-xl"></i>
            </div>
            <span class="text-[10px] font-bold text-[#EA580C]">Menu</span>
        </a>

        <!-- Cart (Center Action) -->
        <div class="flex justify-center w-14">
            <button onclick="bukaCheckout()" class="relative w-12 h-12 rounded-full bg-[#EA580C] flex items-center justify-center text-white shadow-[0_8px_16px_rgba(234,88,12,0.4)] transition-transform active:scale-95">
                <i class="fa-solid fa-cart-shopping text-xl"></i>
                <div id="badge-keranjang" class="absolute -top-1.5 -right-1.5 bg-red-600 text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center hidden border-2 border-[#FDF8F3]">0</div>
            </button>
        </div>

        <!-- Pesanan -->
        <a href="#" class="flex flex-col items-center gap-1 w-14 group">
            <div class="w-10 h-10 rounded-full flex items-center justify-center text-[#9CA3AF] group-hover:text-[#EA580C] transition-colors">
                <i class="fa-solid fa-receipt text-xl"></i>
            </div>
            <span class="text-[10px] font-semibold text-[#9CA3AF] group-hover:text-[#EA580C]">Pesanan</span>
        </a>

        <!-- Profil -->
        <a href="#" class="flex flex-col items-center gap-1 w-14 group">
            <div class="w-10 h-10 rounded-full flex items-center justify-center text-[#9CA3AF] group-hover:text-[#EA580C] transition-colors">
                <i class="fa-regular fa-user text-xl"></i>
            </div>
            <span class="text-[10px] font-semibold text-[#9CA3AF] group-hover:text-[#EA580C]">Profil</span>
        </a>
    </div>

    <script>
        let keranjang = [];

        // --- Logika Tombol & Keranjang ---
        function handleTambahProduk(id, nama, harga) {
            tambahKeKeranjang(id, nama, harga);
            toggleTombol(id, true);
        }

        function handleTambahKuantitas(id) {
            let item = keranjang.find(item => item.id === id);
            if (item) {
                item.qty += 1;
                updateKuantitasDiKartu(id, item.qty);
                updateTampilanKeranjang();
            }
        }

        function handleKurangiKuantitas(id) {
            let itemIndex = keranjang.findIndex(item => item.id === id);
            if (itemIndex !== -1) {
                keranjang[itemIndex].qty -= 1;
                
                if (keranjang[itemIndex].qty <= 0) {
                    keranjang.splice(itemIndex, 1);
                    toggleTombol(id, false);
                } else {
                    updateKuantitasDiKartu(id, keranjang[itemIndex].qty);
                }
                updateTampilanKeranjang();
            }
        }

        function tambahKeKeranjang(id, nama, harga) {
            let itemAda = keranjang.find(item => item.id === id);
            if (itemAda) {
                itemAda.qty += 1;
            } else {
                keranjang.push({ id: id, nama: nama, harga: harga, qty: 1 });
            }
            updateTampilanKeranjang();
        }

        function updateTampilanKeranjang() {
            let totalItem = 0;
            let totalHarga = 0;

            keranjang.forEach(item => {
                totalItem += item.qty;
                totalHarga += (item.harga * item.qty);
            });

            document.getElementById('totalHarga').innerText = 'Rp ' + totalHarga.toLocaleString('id-ID');
            
            let badge = document.getElementById('badge-keranjang');
            let floatingTotal = document.getElementById('floating-total-container');

            if (totalItem > 0) {
                badge.innerText = totalItem;
                badge.classList.remove('hidden');
                
                if (floatingTotal) {
                    floatingTotal.classList.remove('opacity-0', 'translate-y-4', 'pointer-events-none');
                    floatingTotal.classList.add('opacity-100', 'translate-y-0');
                }
            } else {
                badge.classList.add('hidden');
                
                if (floatingTotal) {
                    floatingTotal.classList.add('opacity-0', 'translate-y-4', 'pointer-events-none');
                    floatingTotal.classList.remove('opacity-100', 'translate-y-0');
                }
            }
        }

        function toggleTombol(id, showQtyControls) {
            let controlsContainer = document.getElementById('controls-' + id);
            let addBtn = controlsContainer.querySelector('.add-btn');
            let qtyControls = controlsContainer.querySelector('.qty-controls');
            let qtyText = controlsContainer.querySelector('.qty-text');

            if (showQtyControls) {
                addBtn.classList.add('hidden');
                qtyControls.classList.remove('hidden');
                qtyText.innerText = 1;
            } else {
                addBtn.classList.remove('hidden');
                qtyControls.classList.add('hidden');
            }
        }

        function updateKuantitasDiKartu(id, qty) {
            document.getElementById('qty-text-' + id).innerText = qty;
        }

        function bukaCheckout() {
            if (keranjang.length === 0) {
                alert('Keranjang masih kosong! Silakan pilih menu dulu.');
                return;
            }
            localStorage.setItem('keranjang', JSON.stringify(keranjang));
            alert('Data keranjang berhasil disimpan di LocalStorage. Siap diarahkan ke halaman Checkout.');
        }

        // --- Logika Filter Kategori ---
        function filterKategori(idKategori, elemenTombol) {
            // 1. Reset semua warna tombol tab kembali ke warna abu-abu (tidak aktif)
            let tabs = document.querySelectorAll('.tab-btn');
            tabs.forEach(tab => {
                tab.classList.remove('bg-gacoan-gold', 'text-black', 'font-semibold');
                tab.classList.add('bg-gacoan-gray', 'text-gray-300', 'font-medium');
            });
            
            // 2. Beri warna emas (aktif) pada tombol yang baru saja diklik
            elemenTombol.classList.remove('bg-gacoan-gray', 'text-gray-300', 'font-medium');
            elemenTombol.classList.add('bg-gacoan-gold', 'text-black', 'font-semibold');

            // 3. Filter produk berdasarkan kategori
            let products = document.querySelectorAll('.product-card');
            products.forEach(card => {
                // Ambil data-kategori dari setiap kartu produk
                let kategoriProduk = card.getAttribute('data-kategori');
                
                // Jika yang diklik 'all' ATAU id kategori cocok, tampilkan kartu (flex)
                if (idKategori === 'all' || kategoriProduk == idKategori) {
                    card.style.display = 'flex'; 
                } else {
                    // Jika tidak cocok, sembunyikan kartu
                    card.style.display = 'none'; 
                }
            });
        }
    </script>
</body>
</html>