<?php
helper('app_setting');
$role = session()->get('role');
$nama = session()->get('nama_lengkap') ?? 'User';
$storeName    = app_setting('store_name', 'NasPad Zendry');
$storeLogo    = app_setting('store_logo', '');
$storeFavicon = app_setting('store_favicon', '');
$footerText   = app_setting('footer_text', '© 2026 NasPad Zendry — Warung Nasi Padang Asli');
$logoSrc = ! empty($storeLogo) ? base_url($storeLogo) : '';
$faviconPath = ! empty($storeFavicon) ? $storeFavicon : (! empty($storeLogo) ? $storeLogo : '');
$faviconSrc = ! empty($faviconPath) ? base_url($faviconPath) : '';
$isAdmin = $role === 'admin';
$isActive = static function (string $path) {
    $c = trim(uri_string(), '/'); $t = trim($path, '/');
    if ($t === '') return $c === '';
    return str_starts_with($c, $t);
};
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? ($storeName . ' — Kasir')) ?></title>
    <?php if (! empty($faviconSrc)): ?>
    <link rel="icon" type="image/png" href="<?= esc($faviconSrc) ?>">
    <?php endif; ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Instrument+Serif:ital@0;1&family=Instrument+Sans:wght@600;700&display=swap" rel="stylesheet">
    <style>
        *{scrollbar-width:thin; scrollbar-color:rgba(161,98,7,.25) transparent}
        body{font-family:'Plus Jakarta Sans',sans-serif; background:#FFF7ED; color:#000000; min-height:100vh}
        /* subtle paper texture */
        body::before{
            content:''; position:fixed; inset:0; pointer-events:none; opacity:.55;
            background-image: radial-gradient(rgba(124,45,18,.06) 1px, transparent 1px);
            background-size:22px 22px; mask-image: linear-gradient(to bottom, rgba(0,0,0,.18), transparent 85%);
        }
        .sidebar-scrollbar::-webkit-scrollbar{width:4px}
        .sidebar-scrollbar::-webkit-scrollbar-thumb{background:rgba(124,45,18,.18); border-radius:999px}
        #sidebar{transition:transform .25s cubic-bezier(.4,0,.2,1)}
        #overlay{transition:opacity .2s ease}
        .nav-link{transition: all .16s ease}
        .nav-link{color:#000000}
        .nav-link:hover{background:#FFF7ED}
        .nav-link.active{background:#FFFFFF; color:#EA580C; box-shadow:0 6px 18px rgba(0,0,0,.12)}
        .nav-link.active .nav-icon{background:#EA580C; color:#fff}
    </style>
</head>
<body class="antialiased">
<div id="overlay" class="fixed inset-0 bg-[#000000]/20 backdrop-blur-[2px] z-30 hidden opacity-0 lg:hidden" onclick="closeSidebar()"></div>

<!-- Sidebar terang human - white with warm border, not gelap -->
<aside id="sidebar" class="fixed top-0 left-0 h-screen w-72 bg-[#EA580C] border-r border-[#C2410C] z-40 flex flex-col sidebar-scrollbar overflow-y-auto -translate-x-full lg:translate-x-0 shadow-[6px_0_32px_rgba(62,35,20,.18)]">
    <div class="px-5 py-6">
        <div class="flex items-center gap-3">
            <div>
                <h1 class="text-[17px] font-extrabold tracking-tight text-[#000000]" style="font-family:'Instrument Sans',sans-serif">NasPad Zendry</h1>
                <p class="text-[11px] font-bold tracking-[0.12em] uppercase text-black">Warung Nasi Padang • Sejak 2018</p>
            </div>
            <button onclick="closeSidebar()" class="ml-auto lg:hidden w-9 h-9 rounded-xl bg-white border border-[#FED7AA] text-[#EA580C]"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <!-- human touch badge -->
        <div class="mt-4 flex items-center gap-2 rounded-full bg-[#FFF7ED] border border-[#FED7AA] px-3 py-1.5">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            <span class="text-[11px] font-bold text-[#000000]">Buka • 10:00 - 22:00</span>
            <span class="ml-auto text-[11px] font-medium text-black">Padang asli</span>
        </div>
    </div>
    <nav class="flex-1 px-3 py-2 space-y-6">
        <?php if ($isAdmin): ?>
            <div><p class="px-3 mb-2 text-[10px] font-bold uppercase tracking-[0.18em] text-black">Menu Utama</p>
                <div class="space-y-1">
                    <a href="<?= base_url('admin/dashboard') ?>" class="nav-link <?= $isActive('admin/dashboard') ? 'active' : '' ?> flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold <?= $isActive('admin/dashboard') ? 'text-[#000000]' : 'text-[#000000]' ?>">
                        <span class="nav-icon w-8 h-8 rounded-lg bg-[#FFF7ED] border border-[#FED7AA] flex items-center justify-center text-[#EA580C]"><i class="fa-solid fa-table-columns text-[12px]"></i></span> Dasbor Utama</a>
                    <a href="<?= base_url('kasir/dashboard') ?>" class="nav-link <?= $isActive('kasir/dashboard') ? 'active' : '' ?> flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-[#000000]"><span class="nav-icon w-8 h-8 rounded-lg bg-[#FFF7ED] border border-[#FED7AA] flex items-center justify-center text-[#EA580C]"><i class="fa-solid fa-bell-concierge text-[12px]"></i></span> Antrean Pesanan</a>
                    <a href="<?= base_url('admin/menu') ?>" class="nav-link <?= $isActive('admin/menu') ? 'active' : '' ?> flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium <?= $isActive('admin/menu') ? 'text-[#000000]' : 'text-[#000000]' ?>"><span class="nav-icon w-8 h-8 rounded-lg bg-[#FFF7ED] border border-[#FED7AA] flex items-center justify-center text-[#EA580C]"><i class="fa-solid fa-bowl-food text-[12px]"></i></span> Daftar Lauk & Menu</a>
                    <a href="<?= base_url('admin/kategori') ?>" class="nav-link <?= $isActive('admin/kategori') ? 'active' : '' ?> flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-[#000000]"><span class="nav-icon w-8 h-8 rounded-lg bg-white border border-stone-200 flex items-center justify-center text-black"><i class="fa-solid fa-layer-group text-[12px]"></i></span> Kategori Lauk</a>
                    <a href="<?= base_url('admin/riwayat') ?>" class="nav-link <?= $isActive('admin/riwayat') ? 'active' : '' ?> flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-[#000000]"><span class="nav-icon w-8 h-8 rounded-lg bg-white border border-stone-200 flex items-center justify-center text-black"><i class="fa-solid fa-clock-rotate-left text-[12px]"></i></span> Riwayat Transaksi</a>
                    <a href="<?= base_url('admin/meja') ?>" class="nav-link <?= $isActive('admin/meja') ? 'active' : '' ?> flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-[#000000]"><span class="nav-icon w-8 h-8 rounded-lg bg-white border border-stone-200 flex items-center justify-center text-black"><i class="fa-solid fa-qrcode text-[12px]"></i></span> Meja & QR Code</a>
                    <a href="<?= base_url('admin/pengaturan') ?>" class="nav-link <?= $isActive('admin/pengaturan') ? 'active' : '' ?> flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-[#000000]"><span class="nav-icon w-8 h-8 rounded-lg bg-white border border-stone-200 flex items-center justify-center text-black"><i class="fa-solid fa-store text-[12px]"></i></span> Pengaturan Warung</a>
                </div></div>
            <div><p class="px-3 mb-2 text-[10px] font-bold uppercase tracking-[0.18em] text-black">Operasional</p>
                <div class="space-y-1">
                    <a href="<?= base_url('admin/users') ?>" class="nav-link <?= $isActive('admin/users') ? 'active' : '' ?> flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-[#000000]"><span class="nav-icon w-8 h-8 rounded-lg bg-white border border-stone-200 flex items-center justify-center text-black"><i class="fa-solid fa-users-gear text-[12px]"></i></span> User Kasir</a>
                    <a href="<?= base_url('admin/promo') ?>" class="nav-link <?= $isActive('admin/promo') ? 'active' : '' ?> flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-[#000000]"><span class="nav-icon w-8 h-8 rounded-lg bg-white border border-stone-200 flex items-center justify-center text-black"><i class="fa-solid fa-gift text-[12px]"></i></span> Promo</a>
                </div></div>
        <?php else: ?>
            <div><p class="px-3 mb-2 text-[10px] font-bold uppercase tracking-[0.18em] text-black">Kasir</p>
                <div class="space-y-1">
                    <a href="<?= base_url('kasir/dashboard') ?>" class="nav-link <?= $isActive('kasir/dashboard') ? 'active' : '' ?> flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-[#000000]"><span class="nav-icon w-8 h-8 rounded-lg flex items-center justify-center"><i class="fa-solid fa-bell-concierge text-[12px]"></i></span> Antrean Pesanan</a>
                    <a href="<?= base_url('kasir/riwayat') ?>" class="nav-link <?= $isActive('kasir/riwayat') ? 'active' : '' ?> flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium <?= $isActive('kasir/riwayat') ? 'text-[#000000]' : 'text-[#000000]' ?>"><span class="nav-icon w-8 h-8 rounded-lg bg-white border border-stone-200 flex items-center justify-center text-black"><i class="fa-solid fa-clock-rotate-left text-[12px]"></i></span> Riwayat Transaksi</a>
                </div></div>
        <?php endif; ?>
    </nav>
    <div class="px-4 py-4 border-t border-[#FED7AA] bg-white">
        <p class="text-[11px] leading-relaxed text-black text-center">© 2026 NasPad Zendry.<br><span class="text-[#EA580C] font-semibold">Dimasak dengan hati — Padang asli.</span></p>
    </div>
</aside>

<div class="relative z-10 lg:ml-72 min-h-screen flex flex-col">
    <header class="sticky top-0 z-20 bg-white/85 backdrop-blur-xl border-b border-[#FED7AA]">
        <div class="px-4 lg:px-7 py-4 flex items-center justify-between gap-4">
            <div class="flex items-center gap-4 min-w-0">
                <button onclick="openSidebar()" class="lg:hidden w-10 h-10 rounded-xl bg-white border border-[#FED7AA] flex items-center justify-center text-[#EA580C] shadow-sm"><i class="fa-solid fa-bars"></i></button>
                <div>
                    <h2 class="text-[18px] lg:text-[22px] font-extrabold tracking-tight text-[#000000] leading-none" style="font-family:'Instrument Sans',sans-serif">Halo, <?= esc($nama) ?> (Uda/Uni) 👋</h2>
                    <p class="text-[13px] text-black mt-1 hidden sm:block">Selamat datang di dashboard Kasir <span class="font-semibold text-[#EA580C]">NasPad Zendry</span>.</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="hidden md:flex items-center gap-3 px-4 py-2.5 rounded-2xl bg-white border border-[#FED7AA] shadow-sm">
                    <div class="w-8 h-8 rounded-xl bg-[#FFF7ED] border border-[#FED7AA] flex items-center justify-center text-[#EA580C]"><i class="fa-regular fa-calendar text-sm"></i></div>
                    <div><p class="text-xs font-bold text-[#000000]"><?= date('d M Y') ?></p><p class="text-[11px] font-medium text-black">Hari ini</p></div>
                </div>
                <div class="relative group">
                    <button type="button" class="flex items-center gap-2.5 pl-1.5 pr-3 py-1.5 rounded-full bg-white border border-[#FED7AA] shadow-sm">
                        <div class="w-9 h-9 rounded-full bg-[#EA580C] flex items-center justify-center text-[#000000] font-bold text-sm">NZ</div>
                        <div class="hidden sm:block text-left"><p class="text-sm font-bold leading-none text-[#000000]"><?= esc($nama) ?></p><p class="text-[11px] font-medium text-black capitalize"><?= esc($role ?? 'Kasir') ?></p></div>
                        <i class="fa-solid fa-chevron-down text-[10px] text-black hidden sm:block"></i>
                    </button>
                    <div class="absolute right-0 mt-2 w-56 rounded-2xl bg-white border border-[#FED7AA] shadow-[0_12px_32px_rgba(62,35,20,.12)] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all overflow-hidden">
                        <div class="px-4 py-3 border-b border-[#FED7AA] bg-white"><p class="text-sm font-bold text-[#000000]"><?= esc($nama) ?></p><p class="text-xs font-medium text-black capitalize"><?= esc($role ?? 'User') ?></p></div>
                        <a href="<?= base_url('logout') ?>" class="flex items-center gap-3 px-4 py-3 text-sm text-[#EA580C] hover:bg-[#FFF7ED]"><i class="fa-solid fa-right-from-bracket w-4 text-center"></i> Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <main class="flex-1 px-4 lg:px-7 py-6 bg-white relative">
        <?= $this->renderSection('content') ?>
    </main>
    <footer class="px-4 lg:px-7 py-5 border-t border-[#FED7AA] bg-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 text-xs font-medium text-black">
            <p>© <?= date('Y') ?> <span class="font-bold text-[#EA580C]">NasPad Zendry</span> — Warung Nasi Padang</p>
            <p class="font-medium"><?= esc($footerText) ?></p>
        </div>
    </footer>
</div>
<script>
    function openSidebar(){ const s=document.getElementById('sidebar'), o=document.getElementById('overlay'); s.classList.remove('-translate-x-full'); o.classList.remove('hidden'); setTimeout(()=>o.classList.remove('opacity-0'),10); }
    function closeSidebar(){ const s=document.getElementById('sidebar'), o=document.getElementById('overlay'); s.classList.add('-translate-x-full'); o.classList.add('opacity-0'); setTimeout(()=>o.classList.add('hidden'),200); }
    document.addEventListener('keydown',e=>{ if(e.key==='Escape') closeSidebar(); });
</script>
</body>
</html>
