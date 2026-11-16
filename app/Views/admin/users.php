<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<div class="space-y-6">
    <div class="rounded-[1.6rem] bg-white border border-[#FED7AA] shadow-[0_8px_28px_rgba(62,35,20,.06)] p-5 lg:p-7">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
            <div>
                <p class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[#FFF7ED] border border-[#FED7AA] text-[#EA580C] text-xs font-bold mb-3"><i class="fa-solid fa-users-gear"></i> Manajemen Pengguna</p>
                <h1 class="text-2xl lg:text-3xl font-extrabold tracking-tight text-[#000000]">Manajemen Akun</h1>
                <p class="text-sm font-medium text-black mt-2 max-w-2xl">Kelola akun akses untuk Admin dan Kasir yang dapat masuk ke sistem.</p>
            </div>
            <div class="rounded-2xl bg-[#FFF7ED] border border-[#FDE68A] p-4 min-w-full sm:min-w-[280px] lg:min-w-[320px]">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-white border border-[#FED7AA] flex items-center justify-center text-[#EA580C]"><i class="fa-solid fa-shield-halved"></i></div>
                    <div><p class="text-sm font-bold text-[#000000]">Total Akun</p><p class="text-2xl font-extrabold text-[#000000] mt-1"><?= number_format(count($users ?? []),0,',','.') ?></p></div>
                </div>
            </div>
        </div>
    </div>
    <?php if (session()->getFlashdata('success')): ?><div role="status" class="flex items-center gap-3 rounded-xl border border-emerald-300 bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-900"><i class="fa-solid fa-circle-check" aria-hidden="true"></i><span><?= esc(session()->getFlashdata('success')) ?></span></div><?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?><div role="alert" class="flex items-center gap-3 rounded-xl border border-red-300 bg-red-50 px-4 py-3 text-sm font-bold text-red-900"><i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i><span><?= esc(session()->getFlashdata('error')) ?></span></div><?php endif; ?>
    <?php if (session()->getFlashdata('errors')): ?>
        <div role="alert" class="rounded-xl border border-red-300 bg-red-50 px-4 py-3 text-sm font-bold text-red-900 space-y-1">
            <?php foreach ((array) session()->getFlashdata('errors') as $err): ?>
                <p><i class="fa-solid fa-circle-exclamation mr-1" aria-hidden="true"></i><?= esc($err) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-1">
            <div class="rounded-2xl border border-[#FED7AA] bg-white shadow-[0_8px_28px_rgba(62,35,20,.06)] p-5 lg:p-6 xl:sticky xl:top-24">
                <div class="mb-5"><h2 class="text-lg font-extrabold text-[#000000]">Tambah Akun Baru</h2><p class="text-xs font-medium text-black mt-1">Buat akses baru untuk admin atau kasir.</p></div>
                <form action="<?= base_url('admin/users/store') ?>" method="post" class="space-y-4">
                    <?= csrf_field() ?>
                    <div><label for="nama_lengkap" class="block text-xs font-bold text-[#57534E] uppercase tracking-[0.08em] mb-2">Nama Lengkap <span class="text-red-600" aria-hidden="true">*</span></label><input type="text" id="nama_lengkap" name="nama_lengkap" required autocomplete="name" placeholder="Contoh: Kasir Utama" class="w-full rounded-xl bg-white border border-stone-300 px-4 py-3 text-sm font-semibold text-[#1C1917] placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-[#EA580C]/20 focus:border-[#EA580C]"></div>
                    <div><label for="username" class="block text-xs font-bold text-[#57534E] uppercase tracking-[0.08em] mb-2">Username <span class="text-red-600" aria-hidden="true">*</span></label><input type="text" id="username" name="username" required autocomplete="username" autocapitalize="none" placeholder="Contoh: kasir01" class="w-full rounded-xl bg-white border border-stone-300 px-4 py-3 text-sm font-semibold text-[#1C1917] placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-[#EA580C]/20 focus:border-[#EA580C]"></div>
                    <div><label for="password" class="block text-xs font-bold text-[#57534E] uppercase tracking-[0.08em] mb-2">Password <span class="text-red-600" aria-hidden="true">*</span></label><input type="password" id="password" name="password" required minlength="6" autocomplete="new-password" placeholder="Minimal 6 karakter" class="w-full rounded-xl bg-white border border-stone-300 px-4 py-3 text-sm font-semibold text-[#1C1917] placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-[#EA580C]/20 focus:border-[#EA580C]"></div>
                    <div><label for="role" class="block text-xs font-bold text-[#57534E] uppercase tracking-[0.08em] mb-2">Role Akses</label><select id="role" name="role" required class="w-full rounded-xl bg-white border border-stone-300 px-4 py-3 text-sm font-bold text-[#1C1917] focus:outline-none focus:ring-2 focus:ring-[#EA580C]/20"><option value="kasir">Kasir</option><option value="admin">Admin</option></select></div>
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-[#EA580C] hover:bg-[#C2410C] px-5 py-3 text-sm font-bold text-white shadow-sm transition"><i class="fa-solid fa-floppy-disk"></i> Simpan Akun</button>
                </form>
            </div>
        </div>
        <div class="xl:col-span-2">
            <div class="rounded-2xl border border-[#FED7AA] bg-white shadow-[0_8px_28px_rgba(62,35,20,.06)] overflow-hidden">
                <div class="p-5 lg:p-6 border-b border-[#FED7AA] flex items-center justify-between gap-4 bg-white">
                    <div><h2 class="text-lg font-extrabold text-[#000000]">Daftar Akun</h2><p class="text-xs font-medium text-black mt-1">Akun admin dan kasir yang terdaftar.</p></div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead><tr class="border-b border-[#FED7AA] bg-[#FED7AA] text-xs font-bold text-[#C2410C] uppercase tracking-[0.08em]"><th class="px-5 py-4 whitespace-nowrap">Pengguna</th><th class="px-5 py-4 whitespace-nowrap">Username</th><th class="px-5 py-4 whitespace-nowrap">Role</th><th class="px-5 py-4 text-center whitespace-nowrap">Aksi</th></tr></thead>
                        <tbody class="divide-y divide-stone-100">
                            <?php if (empty($users)): ?>
                                <tr><td colspan="4" class="px-5 py-16 text-center"><div class="flex flex-col items-center gap-3"><div class="w-16 h-16 rounded-2xl bg-[#FFF7ED] border border-[#FED7AA] flex items-center justify-center"><i class="fa-solid fa-users text-black text-2xl"></i></div><div><p class="text-sm font-bold text-[#000000]">Belum ada data akun</p><p class="text-xs font-medium text-black mt-1">Tambahkan akun baru dari form sebelah kiri.</p></div></div></td></tr>
                            <?php else: foreach ($users as $u): ?>
                                <tr class="hover:bg-[#FFF7ED] transition">
                                    <td class="px-5 py-4 whitespace-nowrap"><div class="flex items-center gap-3"><div class="w-10 h-10 rounded-full bg-[#EA580C] flex items-center justify-center text-white" aria-hidden="true"><i class="fa-solid fa-user text-xs"></i></div><div><p class="text-sm font-bold text-[#1C1917]"><?= esc($u['nama_lengkap']??'-') ?></p><p class="text-xs font-medium text-stone-500">ID: <?= esc($u['id']??'-') ?></p></div></div></td>
                                    <td class="px-5 py-4 text-sm font-semibold text-black whitespace-nowrap"><?= esc($u['username']??'-') ?></td>
                                    <td class="px-5 py-4 whitespace-nowrap"><?php if(($u['role']??'')==='admin'): ?><span class="text-[11px] font-bold text-purple-900 bg-purple-50 border border-purple-200 px-2.5 py-1 rounded-full uppercase tracking-wider">Admin</span><?php else: ?><span class="text-[11px] font-bold text-sky-900 bg-sky-50 border border-sky-200 px-2.5 py-1 rounded-full uppercase tracking-wider">Kasir</span><?php endif; ?></td>
                                    <td class="px-5 py-4 text-center whitespace-nowrap"><form action="<?= base_url('admin/users/delete/'.$u['id']) ?>" method="post" onsubmit="return confirm('Hapus akun ini? Tindakan ini tidak dapat dibatalkan.');"><?= csrf_field() ?><button type="submit" aria-label="Hapus akun <?= esc($u['nama_lengkap'] ?? '-', 'attr') ?>" title="<?= (session()->get('id')==($u['id']??null)) ? 'Tidak dapat menghapus akun sendiri' : 'Hapus akun' ?>" class="w-9 h-9 rounded-xl bg-white border border-red-200 text-red-700 hover:bg-red-50 inline-flex items-center justify-center disabled:opacity-40 disabled:cursor-not-allowed" <?= (session()->get('id')==($u['id']??null))?'disabled':'' ?>><i class="fa-solid fa-trash-can text-xs" aria-hidden="true"></i></button></form></td>
                                </tr>
                            <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
