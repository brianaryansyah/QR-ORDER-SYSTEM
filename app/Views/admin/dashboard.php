<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<?php
helper('app_setting');
$storeName        = app_setting('store_name', 'NasPad Zendry');
$storeDescription = app_setting('store_description', 'Ringkasan operasional Nasi Padang hari ini.');
$statusLabel = static function (?string $s): string { return match($s){'selesai'=>'Selesai','pending'=>'Menunggu', default=>ucfirst((string)$s)}; };
$statusClass = static function (?string $s): string { return match($s){'selesai'=>'bg-emerald-50 text-emerald-900 border-emerald-300','pending'=>'bg-amber-50 text-amber-900 border-amber-300', default=>'bg-stone-100 text-black border-stone-300'}; };
$paymentLabel = static function (?string $m): string { return match($m){'tunai'=>'Tunai','qris'=>'QRIS / E-Wallet','transfer'=>'Transfer Bank', default=>!empty($m)?ucfirst((string)$m):'-'}; };
$paymentIcon = static function (?string $m): string { return match($m){'tunai'=>'fa-money-bill-wave','qris'=>'fa-qrcode','transfer'=>'fa-building-columns', default=>'fa-credit-card'}; };
?>
<div class="space-y-6">
    <div class="rounded-[1.6rem] bg-white border border-[#FED7AA] shadow-[0_8px_28px_rgba(62,35,20,.06)] p-5 lg:p-7">
        <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-5">
            <div>
                <p class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[#FFF7ED] border border-[#FED7AA] text-[#EA580C] text-xs font-bold mb-3"><span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Admin Dashboard</p>
                <h1 class="text-2xl lg:text-3xl font-extrabold tracking-tight text-[#000000]">Dashboard <?= esc($storeName) ?></h1>
                <p class="text-sm font-medium text-black mt-2 max-w-2xl"><?= esc($storeDescription ?: 'Pantau pesanan, pendapatan, menu, dan aktivitas operasional warung dari satu halaman.') ?></p>
            </div>
            <div class="grid grid-cols-2 gap-3 min-w-full sm:min-w-[360px] xl:min-w-[420px]">
                <a href="<?= base_url('admin/menu') ?>" class="group rounded-2xl bg-[#FFF7ED] hover:bg-[#FFF7ED] border border-[#FED7AA] p-4 transition">
                    <div class="w-10 h-10 rounded-xl bg-white border border-[#FED7AA] text-[#EA580C] flex items-center justify-center mb-3"><i class="fa-solid fa-plus"></i></div>
                    <p class="text-sm font-bold text-[#000000]">Tambah Lauk</p><p class="text-xs font-medium text-black mt-1">Kelola Rendang, Gulai</p>
                </a>
                <a href="<?= base_url('admin/meja') ?>" class="group rounded-2xl bg-[#FFF7ED] hover:bg-white border border-[#FED7AA] p-4 transition">
                    <div class="w-10 h-10 rounded-xl bg-white border border-[#FED7AA] text-[#EA580C] flex items-center justify-center mb-3"><i class="fa-solid fa-qrcode"></i></div>
                    <p class="text-sm font-bold text-[#000000]">QR Meja</p><p class="text-xs font-medium text-black mt-1">Cetak QR Code</p>
                </a>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="rounded-2xl bg-white border border-[#FED7AA] p-5 shadow-[0_4px_14px_rgba(62,35,20,.05)]">
            <div class="flex items-start justify-between gap-4">
                <div><p class="text-xs font-bold text-black uppercase tracking-[0.08em]">Pesanan Hari Ini</p><p class="text-3xl font-extrabold text-[#000000] mt-3"><?= number_format($total_pesanan ?? 0,0,',','.') ?></p><p class="text-xs font-medium text-black mt-2">Total transaksi masuk</p></div>
                <div class="w-12 h-12 rounded-xl bg-sky-50 border border-sky-200 flex items-center justify-center text-sky-800"><i class="fa-solid fa-receipt"></i></div>
            </div>
        </div>
        <div class="rounded-2xl bg-white border border-[#FED7AA] p-5 shadow-[0_4px_14px_rgba(62,35,20,.05)]">
            <div class="flex items-start justify-between gap-4">
                <div><p class="text-xs font-bold text-black uppercase tracking-[0.08em]">Pendapatan Hari Ini</p><p class="text-3xl font-extrabold text-[#000000] mt-3">Rp <?= number_format($pendapatan ?? 0,0,',','.') ?></p><p class="text-xs font-medium text-black mt-2">Hanya transaksi selesai</p></div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-800"><i class="fa-solid fa-sack-dollar"></i></div>
            </div>
        </div>
        <div class="rounded-2xl bg-white border border-[#FED7AA] p-5 shadow-[0_4px_14px_rgba(62,35,20,.05)]">
            <div class="flex items-start justify-between gap-4">
                <div><p class="text-xs font-bold text-black uppercase tracking-[0.08em]">Lauk Aktif</p><p class="text-3xl font-extrabold text-[#000000] mt-3"><?= number_format($total_menu ?? 0,0,',','.') ?></p><p class="text-xs font-medium text-black mt-2">Menu Rendang, Gulai tersedia</p></div>
                <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-200 flex items-center justify-center text-amber-800"><i class="fa-solid fa-bowl-food"></i></div>
            </div>
        </div>
        <div class="rounded-2xl bg-white border border-[#FED7AA] p-5 shadow-[0_4px_14px_rgba(62,35,20,.05)]">
            <div class="flex items-start justify-between gap-4">
                <div><p class="text-xs font-bold text-black uppercase tracking-[0.08em]">Total Meja</p><p class="text-3xl font-extrabold text-[#000000] mt-3"><?= number_format($total_meja ?? 0,0,',','.') ?></p><p class="text-xs font-medium text-black mt-2">Meja terdaftar</p></div>
                <div class="w-12 h-12 rounded-xl bg-purple-50 border border-purple-200 flex items-center justify-center text-purple-800"><i class="fa-solid fa-chair"></i></div>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">
        <div class="xl:col-span-2 rounded-2xl border border-[#FED7AA] bg-white shadow-[0_8px_28px_rgba(62,35,20,.06)] overflow-hidden">
            <div class="p-5 lg:p-6 border-b border-[#FED7AA] flex items-center justify-between gap-4 bg-white">
                <div><h2 class="text-lg font-extrabold text-[#000000]">Pesanan Terbaru</h2><p class="text-xs font-medium text-black mt-1">Daftar transaksi terbaru yang masuk ke sistem.</p></div>
                <a href="<?= base_url('admin/riwayat') ?>" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#FFF7ED] hover:bg-[#FFF7ED] border border-[#FED7AA] text-[#EA580C] text-xs font-bold">Lihat Semua <i class="fa-solid fa-arrow-right"></i></a>
            </div>
            <?php if (empty($pesanan_terbaru)): ?>
                <div class="flex flex-col items-center justify-center py-16 px-5 text-center bg-[#FFF7ED]/30">
                    <div class="w-16 h-16 rounded-2xl bg-white border border-[#FED7AA] flex items-center justify-center mb-4"><i class="fa-solid fa-clipboard-list text-black text-2xl"></i></div>
                    <p class="text-sm font-bold text-[#000000]">Belum ada pesanan</p><p class="text-xs font-medium text-black mt-1">Pesanan Rendang, Gulai masuk akan ditampilkan di sini.</p>
                </div>
            <?php else: ?>
                <div class="divide-y divide-stone-100">
                    <?php foreach ($pesanan_terbaru as $p): $status=$p['status']??'pending'; $metode=$p['metode_pembayaran']??''; ?>
                        <div class="p-5 lg:p-6 hover:bg-[#FFF7ED] transition">
                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                                <div class="flex items-start gap-4 min-w-0">
                                    <div class="w-12 h-12 rounded-xl bg-[#FFF7ED] border border-[#FED7AA] flex items-center justify-center flex-shrink-0 text-[#EA580C]"><i class="fa-solid fa-receipt"></i></div>
                                    <div class="min-w-0">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <p class="text-sm font-extrabold text-[#000000]">Meja <?= esc($p['nomor_meja']??'-') ?></p>
                                            <span class="text-[11px] px-2 py-1 rounded-full bg-white border border-stone-200 text-black font-semibold">#<?= esc($p['kode_pesanan']??'-') ?></span>
                                            <span class="text-[10px] font-bold px-2 py-1 rounded-full border uppercase tracking-wider <?= $statusClass($status) ?>"><?= esc($statusLabel($status)) ?></span>
                                        </div>
                                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-2 text-xs font-medium text-black">
                                            <span><i class="fa-solid fa-user mr-1 text-black"></i><?= esc($p['nama_pemesan']??'-') ?></span>
                                            <span><i class="fa-regular fa-clock mr-1 text-black"></i><?= !empty($p['created_at'])?date('H:i',strtotime($p['created_at'])):'-' ?></span>
                                            <span><i class="fa-solid <?= esc($paymentIcon($metode)) ?> mr-1 text-black"></i><?= esc($paymentLabel($metode)) ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="lg:text-right"><p class="text-lg font-extrabold text-[#000000]">Rp <?= number_format($p['total_harga']??0,0,',','.') ?></p><p class="text-xs font-medium text-black mt-1"><?= !empty($p['created_at'])?date('d M Y',strtotime($p['created_at'])):'-' ?></p></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="space-y-5">
            <div class="rounded-2xl border border-[#FED7AA] bg-white shadow-[0_8px_28px_rgba(62,35,20,.06)] overflow-hidden">
                <div class="p-5 border-b border-[#FED7AA] bg-white"><h2 class="text-lg font-extrabold text-[#000000]">Akses Cepat</h2><p class="text-xs font-medium text-black mt-1">Shortcut untuk mengelola warung.</p></div>
                <div class="p-4 space-y-2">
                    <a href="<?= base_url('admin/menu') ?>" class="group flex items-center gap-3 p-3 rounded-xl hover:bg-[#FFF7ED] border border-stone-200 text-sm text-black transition"><div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-200 flex items-center justify-center flex-shrink-0 text-amber-800"><i class="fa-solid fa-utensils"></i></div><div class="min-w-0"><p class="font-bold text-[#000000]">Tambah Lauk Baru</p><p class="text-xs font-medium text-black mt-0.5">Rendang, Gulai, Ayam Pop</p></div><i class="fa-solid fa-chevron-right text-xs text-black ml-auto group-hover:text-[#EA580C]"></i></a>
                    <a href="<?= base_url('admin/meja') ?>" class="group flex items-center gap-3 p-3 rounded-xl hover:bg-[#FFF7ED] border border-stone-200 text-sm text-black transition"><div class="w-10 h-10 rounded-xl bg-sky-50 border border-sky-200 flex items-center justify-center flex-shrink-0 text-sky-800"><i class="fa-solid fa-qrcode"></i></div><div class="min-w-0"><p class="font-bold text-[#000000]">Kelola QR Meja</p><p class="text-xs font-medium text-black mt-0.5">Generate dan cetak QR</p></div><i class="fa-solid fa-chevron-right text-xs text-black ml-auto group-hover:text-[#EA580C]"></i></a>
                    <a href="<?= base_url('admin/users') ?>" class="group flex items-center gap-3 p-3 rounded-xl hover:bg-[#FFF7ED] border border-stone-200 text-sm text-black transition"><div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-center flex-shrink-0 text-emerald-800"><i class="fa-solid fa-user-plus"></i></div><div class="min-w-0"><p class="font-bold text-[#000000]">Tambah Akun Kasir</p><p class="text-xs font-medium text-black mt-0.5">Atur Uda/Uni kasir</p></div><i class="fa-solid fa-chevron-right text-xs text-black ml-auto group-hover:text-[#EA580C]"></i></a>
                    <a href="<?= base_url('admin/riwayat') ?>" class="group flex items-center gap-3 p-3 rounded-xl hover:bg-[#FFF7ED] border border-stone-200 text-sm text-black transition"><div class="w-10 h-10 rounded-xl bg-purple-50 border border-purple-200 flex items-center justify-center flex-shrink-0 text-purple-800"><i class="fa-solid fa-file-invoice"></i></div><div class="min-w-0"><p class="font-bold text-[#000000]">Riwayat Transaksi</p><p class="text-xs font-medium text-black mt-0.5">Lihat rekap pesanan</p></div><i class="fa-solid fa-chevron-right text-xs text-black ml-auto group-hover:text-[#EA580C]"></i></a>
                    <a href="<?= base_url('admin/pengaturan') ?>" class="group flex items-center gap-3 p-3 rounded-xl hover:bg-[#FFF7ED] border border-stone-200 text-sm text-black transition"><div class="w-10 h-10 rounded-xl bg-[#FFF7ED] border border-[#FED7AA] flex items-center justify-center flex-shrink-0 text-[#EA580C]"><i class="fa-solid fa-store"></i></div><div class="min-w-0"><p class="font-bold text-[#000000]">Pengaturan Warung</p><p class="text-xs font-medium text-black mt-0.5">Nama, alamat, footer</p></div><i class="fa-solid fa-chevron-right text-xs text-black ml-auto group-hover:text-[#EA580C]"></i></a>
                </div>
            </div>
            <div class="rounded-2xl border border-[#FED7AA] bg-[#FFF7ED] p-5 shadow-[0_4px_14px_rgba(62,35,20,.05)]">
                <div class="w-12 h-12 rounded-xl bg-white border border-[#FED7AA] flex items-center justify-center text-[#EA580C] mb-4"><i class="fa-solid fa-credit-card"></i></div>
                <h3 class="text-base font-extrabold text-[#000000]">Payment Gateway Aktif</h3>
                <p class="text-xs font-medium text-black mt-2 leading-relaxed">Warung mendukung Tunai, QRIS, E-Wallet, dan Transfer Bank via Midtrans.</p>
                <div class="mt-4 flex flex-wrap gap-2"><span class="text-[10px] font-bold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-900 border border-emerald-200">Tunai</span><span class="text-[10px] font-bold px-2.5 py-1 rounded-full bg-sky-50 text-sky-900 border border-sky-200">QRIS</span><span class="text-[10px] font-bold px-2.5 py-1 rounded-full bg-purple-50 text-purple-900 border border-purple-200">VA Bank</span></div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
