<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="space-y-6">

    <div class="relative overflow-hidden rounded-[1.6rem] bg-white border border-[#FED7AA] backdrop-blur shadow-[0_8px_28px_rgba(62,35,20,.06)]">
        <div class="absolute -top-24 -right-24 w-72 h-72 rounded-full bg-amber-500/20 blur-3xl"></div>
        <div class="absolute -bottom-28 -left-24 w-72 h-72 rounded-full bg-orange-500/10 blur-3xl"></div>

        <div class="relative p-5 lg:p-7">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-amber-50 border border-amber-200 text-amber-800 text-xs font-semibold mb-4">
                        <i class="fa-solid fa-bowl-food"></i>
                        Menu Warung
                    </div>

                    <h1 class="text-2xl lg:text-4xl font-extrabold tracking-tight text-[#000000]">
                        Kelola Menu
                    </h1>

                    <p class="text-sm lg:text-base text-black mt-2 max-w-2xl">
                        Daftar semua item menu yang tersedia untuk pelanggan.
                    </p>
                </div>

                <a href="<?= base_url('admin/menu/create') ?>"
                   class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#EA580C] px-5 py-3 text-sm font-bold text-white shadow-sm hover:bg-[#C2410C] transition">
                    <i class="fa-solid fa-plus"></i>
                    Tambah Menu
                </a>
            </div>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div role="status" class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">
            <i class="fa-solid fa-circle-check"></i>
            <span><?= esc(session()->getFlashdata('success')) ?></span>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div role="alert" class="flex items-center gap-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-800">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span><?= esc(session()->getFlashdata('error')) ?></span>
        </div>
    <?php endif; ?>

    <div class="rounded-[1.7rem] border border-[#FED7AA] bg-white backdrop-blur shadow-[0_8px_28px_rgba(62,35,20,.06)] overflow-hidden">
        <div class="p-5 lg:p-6 border-b border-[#FED7AA] flex items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-extrabold text-[#000000]">Data Menu</h2>
                <p class="text-xs text-black mt-1">
                    Atur menu, stok, status aktif, harga, dan kategori.
                </p>
            </div>

            <div class="hidden sm:flex items-center gap-2 rounded-2xl bg-[#FFF7ED] border border-[#FED7AA] px-4 py-2">
                <i class="fa-solid fa-database text-black text-xs"></i>
                <span class="text-xs font-semibold text-black">
                    <?= number_format(count($menus ?? []), 0, ',', '.') ?> Data
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-[#FED7AA] bg-[#FED7AA] text-xs font-bold text-[#C2410C] uppercase tracking-[0.16em]">
                        <th class="px-5 py-4 whitespace-nowrap">No</th>
                        <th class="px-5 py-4 whitespace-nowrap">Menu</th>
                        <th class="px-5 py-4 whitespace-nowrap">Kategori</th>
                        <th class="px-5 py-4 whitespace-nowrap">Harga</th>
                        <th class="px-5 py-4 text-center whitespace-nowrap">Stok</th>
                        <th class="px-5 py-4 text-center whitespace-nowrap">Status</th>
                        <th class="px-5 py-4 text-right whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-stone-100">
                    <?php if (! empty($menus)): ?>
                        <?php foreach ($menus as $m): ?>
                            <tr class="hover:bg-[#FED7AA] transition">
                                <td class="px-5 py-4 text-black whitespace-nowrap">
                                    <?= esc($m['id'] ?? '-') ?>
                                </td>

                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <?php
                                            $gambar = (string) ($m['gambar'] ?? '');
                                            $hasGambar = $gambar !== '' && is_file(FCPATH . 'uploads/menu/' . $gambar);
                                            $initial = strtoupper(mb_substr((string) ($m['nama_menu'] ?? 'M'), 0, 1));
                                        ?>
                                        <?php if ($hasGambar): ?>
                                            <img src="<?= base_url('uploads/menu/' . $gambar) ?>"
                                                 alt="<?= esc($m['nama_menu'] ?? 'Menu') ?>"
                                                 loading="lazy" decoding="async"
                                                 class="w-12 h-12 rounded-2xl object-cover border border-[#FED7AA]">
                                        <?php else: ?>
                                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#FFF4EA] to-[#FFE6D2] border border-[#F6D9C8] flex items-center justify-center" aria-hidden="true">
                                                <span class="text-base font-extrabold text-[#C2410C]"><?= esc($initial) ?></span>
                                            </div>
                                        <?php endif; ?>

                                        <div class="min-w-0">
                                            <p class="text-sm font-bold text-[#000000] truncate max-w-[220px]">
                                                <?= esc($m['nama_menu'] ?? '-') ?>
                                            </p>

                                            <?php if (! empty($m['deskripsi'])): ?>
                                                <p class="text-xs text-black truncate max-w-[240px] mt-0.5">
                                                    <?= esc($m['deskripsi']) ?>
                                                </p>
                                            <?php else: ?>
                                                <p class="text-xs text-black mt-0.5">
                                                    Tidak ada deskripsi
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4 whitespace-nowrap">
                                    <?php if (! empty($m['nama_kategori'])): ?>
                                        <span class="text-xs font-semibold bg-[#FFF7ED] text-black border border-[#FED7AA] px-2.5 py-1 rounded-full">
                                            <?= esc($m['nama_kategori']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-black">—</span>
                                    <?php endif; ?>
                                </td>

                                <td class="px-5 py-4 text-sm font-extrabold text-[#000000] whitespace-nowrap">
                                    Rp <?= number_format($m['harga'] ?? 0, 0, ',', '.') ?>
                                </td>

                                <td class="px-5 py-4 text-center whitespace-nowrap">
                                    <form action="<?= base_url('admin/menu/toggle-stok/' . $m['id']) ?>" method="post" class="inline">
                                        <?= csrf_field() ?>

                                        <button type="submit"
                                                class="inline-flex items-center gap-2 text-[10px] font-bold px-3 py-1.5 rounded-full border uppercase tracking-wider transition
                                                <?= ! empty($m['stok'])
                                                    ? 'bg-emerald-50 text-emerald-800 border-emerald-200 hover:bg-emerald-100'
                                                    : 'bg-red-50 text-red-800 border-red-200 hover:bg-red-100' ?>">
                                            <i class="fa-solid fa-circle text-[7px] <?= ! empty($m['stok']) ? 'text-emerald-600' : 'text-red-600' ?>"></i>
                                            <?= ! empty($m['stok']) ? 'Tersedia' : 'Habis' ?>
                                        </button>
                                    </form>
                                </td>

                                <td class="px-5 py-4 text-center whitespace-nowrap">
                                    <form action="<?= base_url('admin/menu/toggle/' . $m['id']) ?>" method="post" class="inline">
                                        <?= csrf_field() ?>

                                        <button type="submit"
                                                class="inline-flex items-center gap-2 text-[10px] font-bold px-3 py-1.5 rounded-full border uppercase tracking-wider transition
                                                <?= ! empty($m['is_active'])
                                                    ? 'bg-emerald-50 text-emerald-800 border-emerald-200 hover:bg-emerald-100'
                                                    : 'bg-stone-100 text-stone-600 border-stone-300 hover:bg-stone-200' ?>">
                                            <i class="fa-solid fa-circle text-[7px] <?= ! empty($m['is_active']) ? 'text-emerald-600' : 'text-stone-500' ?>"></i>
                                            <?= ! empty($m['is_active']) ? 'Aktif' : 'Nonaktif' ?>
                                        </button>
                                    </form>
                                </td>

                                <td class="px-5 py-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="<?= base_url('admin/menu/edit/' . $m['id']) ?>"
                                           class="inline-flex items-center gap-2 text-xs font-bold text-sky-800 hover:text-[#1C1917] bg-sky-50 hover:bg-sky-500/20 border border-sky-200 px-3 py-2 rounded-xl transition">
                                            <i class="fa-solid fa-pen"></i>
                                            Edit
                                        </a>

                                        <form action="<?= base_url('admin/menu/delete/' . $m['id']) ?>"
                                              method="post"
                                              onsubmit="return confirm('Hapus menu ini?')">
                                            <?= csrf_field() ?>

                                            <button type="submit"
                                                    class="inline-flex items-center gap-2 text-xs font-bold text-red-800 hover:text-[#1C1917] bg-red-50 hover:bg-rose-500/20 border border-red-200 px-3 py-2 rounded-xl transition">
                                                <i class="fa-solid fa-trash"></i>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="px-5 py-20 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-16 h-16 rounded-3xl bg-[#FFF7ED] border border-[#FED7AA] flex items-center justify-center">
                                        <i class="fa-solid fa-utensils text-black text-2xl"></i>
                                    </div>

                                    <div>
                                        <p class="text-sm font-bold text-[#000000]">Belum ada menu</p>
                                        <p class="text-xs text-black mt-1">
                                            Klik tombol tambah untuk membuat menu pertama.
                                        </p>
                                    </div>

                                    <a href="<?= base_url('admin/menu/create') ?>"
                                       class="mt-2 inline-flex items-center gap-2 rounded-2xl bg-[#EA580C] px-5 py-3 text-sm font-bold text-white shadow-sm hover:bg-[#C2410C] transition">
                                        <i class="fa-solid fa-plus"></i>
                                        Tambah Menu
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?= $this->endSection() ?>