<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="space-y-6">

    <div class="relative overflow-hidden rounded-[1.6rem] bg-white border border-[#FED7AA] backdrop-blur shadow-[0_8px_28px_rgba(62,35,20,.06)]">
        <div class="absolute -top-24 -right-24 w-72 h-72 rounded-full bg-amber-500/20 blur-3xl"></div>
        <div class="absolute -bottom-28 -left-24 w-72 h-72 rounded-full bg-purple-600/20 blur-3xl"></div>

        <div class="relative p-5 lg:p-7">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-amber-50 border border-amber-200 text-amber-800 text-xs font-semibold mb-4">
                        <i class="fa-solid fa-layer-group"></i>
                        Kategori Menu
                    </div>

                    <h1 class="text-2xl lg:text-4xl font-extrabold tracking-tight text-[#000000]">
                        Kelola Kategori
                    </h1>

                    <p class="text-sm lg:text-base text-black mt-2 max-w-2xl">
                        Atur kategori untuk pengelompokan menu makanan, minuman, dan produk lainnya.
                    </p>
                </div>

                <a href="<?= base_url('admin/kategori/create') ?>"
                   class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#EA580C] px-5 py-3 text-sm font-bold text-[#000000] shadow-sm hover:opacity-95 transition">
                    <i class="fa-solid fa-plus"></i>
                    Tambah Kategori
                </a>
            </div>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            <i class="fa-solid fa-circle-check"></i>
            <span><?= esc(session()->getFlashdata('success')) ?></span>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="flex items-center gap-3 rounded-2xl border border-red-400/20 bg-red-500/10 px-4 py-3 text-sm text-red-300">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span><?= esc(session()->getFlashdata('error')) ?></span>
        </div>
    <?php endif; ?>

    <div class="rounded-[1.7rem] border border-[#FED7AA] bg-white backdrop-blur shadow-[0_8px_28px_rgba(62,35,20,.06)] overflow-hidden">
        <div class="p-5 lg:p-6 border-b border-[#FED7AA] flex items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-extrabold text-[#000000]">
                    Data Kategori
                </h2>
                <p class="text-xs text-black mt-1">
                    Daftar kategori yang tersedia di sistem.
                </p>
            </div>

            <div class="hidden sm:flex items-center gap-2 rounded-2xl bg-[#FFF7ED] border border-[#FED7AA] px-4 py-2">
                <i class="fa-solid fa-database text-black text-xs"></i>
                <span class="text-xs font-semibold text-black">
                    <?= number_format(count($kategoris ?? []), 0, ',', '.') ?> Data
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-[#FED7AA] bg-[#FED7AA] text-xs font-bold text-[#C2410C] uppercase tracking-[0.16em]">
                        <th class="px-5 py-4 whitespace-nowrap w-8">No</th>
                        <th class="px-5 py-4 whitespace-nowrap">Nama Kategori</th>
                        <th class="px-5 py-4 whitespace-nowrap">Slug</th>
                        <th class="px-5 py-4 whitespace-nowrap">Deskripsi</th>
                        <th class="px-5 py-4 text-center whitespace-nowrap">Status</th>
                        <th class="px-5 py-4 text-right whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-stone-100">
                    <?php if (! empty($kategoris)): ?>
                        <?php foreach ($kategoris as $i => $k): ?>
                            <tr class="hover:bg-[#FED7AA] transition">
                                <td class="px-5 py-4 text-black whitespace-nowrap">
                                    <?= $i + 1 ?>
                                </td>

                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-2xl bg-amber-50 border border-amber-200 flex items-center justify-center text-amber-800">
                                            <i class="fa-solid fa-layer-group text-sm"></i>
                                        </div>

                                        <div>
                                            <p class="text-sm font-bold text-[#000000]">
                                                <?= esc($k['nama_kategori'] ?? '-') ?>
                                            </p>
                                            <p class="text-xs text-black">
                                                Kategori menu
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="text-xs font-mono bg-[#FFF7ED] text-black border border-[#FED7AA] px-2.5 py-1 rounded-full">
                                        <?= esc($k['slug'] ?? '-') ?>
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-sm text-black max-w-xs truncate">
                                    <?= esc($k['deskripsi'] ?? '') ?: '—' ?>
                                </td>

                                <td class="px-5 py-4 text-center whitespace-nowrap">
                                    <form action="<?= base_url('admin/kategori/toggle/' . $k['id']) ?>" method="post" class="inline">
                                        <?= csrf_field() ?>

                                        <button type="submit"
                                                class="inline-flex items-center gap-2 text-[10px] font-bold px-3 py-1.5 rounded-full border uppercase tracking-wider transition
                                                <?= ! empty($k['is_active'])
                                                    ? 'bg-emerald-50 text-emerald-800 border-emerald-200 hover:bg-emerald-500/15'
                                                    : 'bg-slate-500/10 text-black border-slate-400/20 hover:bg-slate-500/15' ?>">
                                            <i class="fa-solid fa-circle text-[7px] <?= ! empty($k['is_active']) ? 'text-emerald-700' : 'text-black' ?>"></i>
                                            <?= ! empty($k['is_active']) ? 'Aktif' : 'Nonaktif' ?>
                                        </button>
                                    </form>
                                </td>

                                <td class="px-5 py-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="<?= base_url('admin/kategori/edit/' . $k['id']) ?>"
                                           class="inline-flex items-center gap-2 text-xs font-bold text-sky-800 hover:text-[#000000] bg-sky-50 hover:bg-sky-500/20 border border-sky-200 px-3 py-2 rounded-xl transition">
                                            <i class="fa-solid fa-pen"></i>
                                            Edit
                                        </a>

                                        <form action="<?= base_url('admin/kategori/delete/' . $k['id']) ?>"
                                              method="post"
                                              onsubmit="return confirm('Hapus kategori ini?')">
                                            <?= csrf_field() ?>

                                            <button type="submit"
                                                    class="inline-flex items-center gap-2 text-xs font-bold text-red-800 hover:text-[#000000] bg-red-50 hover:bg-rose-500/20 border border-red-200 px-3 py-2 rounded-xl transition">
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
                            <td colspan="6" class="px-5 py-20 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-16 h-16 rounded-3xl bg-[#FFF7ED] border border-[#FED7AA] flex items-center justify-center">
                                        <i class="fa-solid fa-tags text-black text-2xl"></i>
                                    </div>

                                    <div>
                                        <p class="text-sm font-bold text-[#000000]">Belum ada kategori</p>
                                        <p class="text-xs text-black mt-1">
                                            Klik tombol tambah untuk membuat kategori pertama.
                                        </p>
                                    </div>

                                    <a href="<?= base_url('admin/kategori/create') ?>"
                                       class="mt-2 inline-flex items-center gap-2 rounded-2xl bg-[#EA580C] px-5 py-3 text-sm font-bold text-[#000000] shadow-sm hover:opacity-95 transition">
                                        <i class="fa-solid fa-plus"></i>
                                        Tambah Kategori
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