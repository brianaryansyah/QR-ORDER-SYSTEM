<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<?php $isEdit = isset($kategori); ?>

<div class="space-y-6">

    <div class="relative overflow-hidden rounded-[1.6rem] bg-white border border-[#FED7AA] backdrop-blur shadow-[0_8px_28px_rgba(62,35,20,.06)]">
        <div class="absolute -top-24 -right-24 w-72 h-72 rounded-full bg-amber-500/20 blur-3xl"></div>
        <div class="absolute -bottom-28 -left-24 w-72 h-72 rounded-full bg-sky-600/20 blur-3xl"></div>

        <div class="relative p-5 lg:p-7">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
                <div class="flex items-start gap-4">
                    <a href="<?= base_url('admin/kategori') ?>"
                       class="w-11 h-11 rounded-2xl bg-[#FFF7ED] hover:bg-[#FFF7ED] border border-[#FED7AA] text-black hover:text-[#000000] flex items-center justify-center transition flex-shrink-0">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>

                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-amber-50 border border-amber-200 text-amber-800 text-xs font-semibold mb-4">
                            <i class="fa-solid fa-layer-group"></i>
                            Kategori Menu
                        </div>

                        <h1 class="text-2xl lg:text-4xl font-extrabold tracking-tight text-[#000000]">
                            <?= $isEdit ? 'Edit Kategori' : 'Tambah Kategori' ?>
                        </h1>

                        <p class="text-sm lg:text-base text-black mt-2 max-w-2xl">
                            <?= $isEdit ? 'Perbarui data kategori yang sudah ada.' : 'Buat kategori baru untuk mengelompokkan menu.' ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="rounded-2xl border border-red-400/20 bg-red-500/10 px-4 py-3 text-sm text-red-300">
            <p class="font-bold mb-2 flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation"></i>
                Terdapat kesalahan:
            </p>

            <ul class="list-disc list-inside space-y-1 text-xs">
                <?php foreach ((array) session()->getFlashdata('errors') as $err): ?>
                    <li><?= esc($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="max-w-2xl">
        <div class="rounded-[1.7rem] border border-[#FED7AA] bg-white backdrop-blur shadow-[0_8px_28px_rgba(62,35,20,.06)] overflow-hidden">
            <div class="p-5 lg:p-6 border-b border-[#FED7AA]">
                <h2 class="text-lg font-extrabold text-[#000000]">
                    Informasi Kategori
                </h2>
                <p class="text-xs text-black mt-1">
                    Isi nama, deskripsi, dan status kategori.
                </p>
            </div>

            <form action="<?= $isEdit ? base_url('admin/kategori/update/' . $kategori['id']) : base_url('admin/kategori/store') ?>" method="post" class="p-5 lg:p-6">
                <?= csrf_field() ?>

                <div class="space-y-5">
                    <div>
                        <label class="block text-xs font-semibold text-black uppercase tracking-[0.16em] mb-2">
                            Nama Kategori <span class="text-red-700">*</span>
                        </label>
                        <input type="text"
                               name="nama_kategori"
                               value="<?= old('nama_kategori', $kategori['nama_kategori'] ?? '') ?>"
                               placeholder="Contoh: Minuman, Makanan Utama"
                               class="w-full rounded-2xl bg-white border border-[#FED7AA] px-4 py-3 text-sm text-[#000000] placeholder:text-black focus:outline-none focus:ring-2 focus:ring-sky-500/50 focus:border-sky-400">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-black uppercase tracking-[0.16em] mb-2">
                            Deskripsi
                        </label>
                        <textarea name="deskripsi"
                                  rows="4"
                                  placeholder="Deskripsi singkat kategori (opsional)"
                                  class="w-full rounded-2xl bg-white border border-[#FED7AA] px-4 py-3 text-sm text-[#000000] placeholder:text-black focus:outline-none focus:ring-2 focus:ring-sky-500/50 focus:border-sky-400 resize-none"><?= old('deskripsi', $kategori['deskripsi'] ?? '') ?></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-black uppercase tracking-[0.16em] mb-2">
                            Status <span class="text-red-700">*</span>
                        </label>
                        <select name="is_active"
                                class="w-full rounded-2xl bg-white border border-[#FED7AA] px-4 py-3 text-sm text-[#000000] focus:outline-none focus:ring-2 focus:ring-sky-500/50 focus:border-sky-400">
                            <option class="bg-white text-[#000000]" value="1" <?= old('is_active', $kategori['is_active'] ?? 1) == 1 ? 'selected' : '' ?>>
                                Aktif
                            </option>
                            <option class="bg-white text-[#000000]" value="0" <?= old('is_active', $kategori['is_active'] ?? 1) == 0 ? 'selected' : '' ?>>
                                Nonaktif
                            </option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center gap-3 mt-7 pt-6 border-t border-[#FED7AA]">
                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#EA580C] px-5 py-3 text-sm font-bold text-white shadow-sm hover:bg-[#C2410C] transition">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <?= $isEdit ? 'Simpan Perubahan' : 'Tambah Kategori' ?>
                    </button>

                    <a href="<?= base_url('admin/kategori') ?>"
                       class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#FFF7ED] hover:bg-[#FFF7ED] border border-[#FED7AA] px-5 py-3 text-sm font-semibold text-black hover:text-[#000000] transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

</div>

<?= $this->endSection() ?>