<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="space-y-6">

    <div class="relative overflow-hidden rounded-[1.6rem] bg-white border border-[#FED7AA] backdrop-blur shadow-[0_8px_28px_rgba(62,35,20,.06)]">
        <div class="absolute -top-24 -right-24 w-72 h-72 rounded-full bg-purple-500/20 blur-3xl"></div>
        <div class="absolute -bottom-28 -left-24 w-72 h-72 rounded-full bg-sky-600/20 blur-3xl"></div>

        <div class="relative p-5 lg:p-7">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-purple-500/10 border border-purple-200 text-purple-800 text-xs font-semibold mb-4">
                        <i class="fa-solid fa-qrcode"></i>
                        QR Code Meja
                    </div>

                    <h1 class="text-2xl lg:text-4xl font-extrabold tracking-tight text-[#000000]">
                        Meja & QR Code
                    </h1>

                    <p class="text-sm lg:text-base text-black mt-2 max-w-2xl">
                        Kelola meja dan QR Code untuk halaman pemesanan pelanggan.
                    </p>
                </div>

                <div class="rounded-3xl bg-[#FFF7ED] border border-[#FED7AA] p-4 min-w-full sm:min-w-[280px] lg:min-w-[320px]">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-purple-500/10 border border-purple-200 flex items-center justify-center text-purple-800">
                            <i class="fa-solid fa-chair"></i>
                        </div>

                        <div>
                            <p class="text-sm font-bold text-[#000000]">Total Meja</p>
                            <p class="text-2xl font-extrabold text-[#000000] mt-1">
                                <?= number_format(count($mejas ?? []), 0, ',', '.') ?>
                            </p>
                        </div>
                    </div>
                </div>
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

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <div class="xl:col-span-1">
            <div class="rounded-[1.7rem] border border-[#FED7AA] bg-white backdrop-blur shadow-[0_8px_28px_rgba(62,35,20,.06)] p-5 lg:p-6 xl:sticky xl:top-24">
                <div class="mb-5">
                    <h2 class="text-lg font-extrabold text-[#000000]">Tambah Meja Baru</h2>
                    <p class="text-xs text-black mt-1">
                        Nomor meja akan dibuatkan kode unik otomatis.
                    </p>
                </div>

                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="mb-4 rounded-2xl border border-red-400/20 bg-red-500/10 px-4 py-3 text-xs text-red-300">
                        <?php foreach ((array) session()->getFlashdata('errors') as $err): ?>
                            <p><?= esc($err) ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('admin/meja/store') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="mb-5">
                        <label class="block text-xs font-semibold text-black uppercase tracking-[0.16em] mb-2">
                            Nomor Meja <span class="text-red-700">*</span>
                        </label>

                        <input type="text"
                               name="nomor_meja"
                               value="<?= old('nomor_meja') ?>"
                               maxlength="10"
                               placeholder="Meja VIP1"
                               class="w-full uppercase rounded-2xl bg-white border border-[#FED7AA] px-4 py-3 text-sm text-[#000000] placeholder:text-black focus:outline-none focus:ring-2 focus:ring-sky-500/50 focus:border-sky-400">

                        <p class="text-xs text-black mt-2">
                            Maksimal 10 karakter. Contoh: Meja A1, Meja B2, Meja VIP1.
                        </p>
                    </div>

                    <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-[#EA580C] px-5 py-3 text-sm font-bold text-[#000000] shadow-sm hover:opacity-95 transition">
                        <i class="fa-solid fa-plus"></i>
                        Tambah Meja
                    </button>
                </form>
            </div>
        </div>

        <div class="xl:col-span-2">
            <div class="rounded-[1.7rem] border border-[#FED7AA] bg-white backdrop-blur shadow-[0_8px_28px_rgba(62,35,20,.06)] overflow-hidden">
                <div class="p-5 lg:p-6 border-b border-[#FED7AA]">
                    <h2 class="text-lg font-extrabold text-[#000000]">Daftar Meja</h2>
                    <p class="text-xs text-black mt-1">
                        Klik tombol QR untuk melihat atau mencetak QR Code meja.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-[#FED7AA] bg-[#FED7AA] text-xs font-bold text-[#C2410C] uppercase tracking-[0.16em]">
                                <th class="px-5 py-4 whitespace-nowrap">Meja</th>
                                <th class="px-5 py-4 whitespace-nowrap">URL QR</th>
                                <th class="px-5 py-4 text-center whitespace-nowrap">Status</th>
                                <th class="px-5 py-4 text-right whitespace-nowrap">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-stone-100">
                            <?php if (! empty($mejas)): ?>
                                <?php foreach ($mejas as $m): ?>
                                    <?php $url = base_url('order/' . $m['kode_unik']); ?>

                                    <tr class="hover:bg-[#FED7AA] transition">
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="w-11 h-11 rounded-2xl bg-purple-500/10 border border-purple-200 text-purple-800 flex items-center justify-center">
                                                    <i class="fa-solid fa-chair"></i>
                                                </div>

                                                <div>
                                                    <p class="text-base font-extrabold text-[#000000]">
                                                        <?= esc($m['nomor_meja'] ?? '-') ?>
                                                    </p>
                                                    <p class="text-xs text-black">
                                                        ID: <?= esc($m['id'] ?? '-') ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="px-5 py-4 whitespace-nowrap">
                                            <p class="text-xs font-mono text-black truncate max-w-[260px]">
                                                <?= esc($url) ?>
                                            </p>
                                        </td>

                                        <td class="px-5 py-4 text-center whitespace-nowrap">
                                            <form action="<?= base_url('admin/meja/toggle/' . $m['id']) ?>" method="post" class="inline">
                                                <?= csrf_field() ?>

                                                <button type="submit"
                                                        class="inline-flex items-center gap-2 text-[10px] font-bold px-3 py-1.5 rounded-full border uppercase tracking-wider transition
                                                        <?= ! empty($m['is_active'])
                                                            ? 'bg-emerald-50 text-emerald-800 border-emerald-200 hover:bg-emerald-500/15'
                                                            : 'bg-slate-500/10 text-black border-slate-400/20 hover:bg-slate-500/15' ?>">
                                                    <i class="fa-solid fa-circle text-[7px] <?= ! empty($m['is_active']) ? 'text-emerald-700' : 'text-black' ?>"></i>
                                                    <?= ! empty($m['is_active']) ? 'Aktif' : 'Nonaktif' ?>
                                                </button>
                                            </form>
                                        </td>

                                        <td class="px-5 py-4 text-right whitespace-nowrap">
                                            <div class="flex items-center justify-end gap-2">
                                                <button type="button"
                                                        onclick="showQR('<?= esc($url) ?>', '<?= esc($m['nomor_meja'] ?? '-') ?>')"
                                                        class="inline-flex items-center gap-2 text-xs font-bold text-purple-800 hover:text-[#000000] bg-purple-500/10 hover:bg-purple-500/20 border border-purple-200 px-3 py-2 rounded-xl transition">
                                                    <i class="fa-solid fa-qrcode"></i>
                                                    QR
                                                </button>

                                                <form action="<?= base_url('admin/meja/delete/' . $m['id']) ?>"
                                                      method="post"
                                                      onsubmit="return confirm('Hapus meja ini?')">
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
                                    <td colspan="4" class="px-5 py-20 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="w-16 h-16 rounded-3xl bg-[#FFF7ED] border border-[#FED7AA] flex items-center justify-center">
                                                <i class="fa-solid fa-table-cells text-black text-2xl"></i>
                                            </div>

                                            <div>
                                                <p class="text-sm font-bold text-[#000000]">Belum ada meja</p>
                                                <p class="text-xs text-black mt-1">
                                                    Tambahkan meja melalui form di samping.
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

</div>

<div id="modal-qr" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-[#000000]/50 backdrop-blur-sm p-4">
    <div class="relative w-full max-w-sm overflow-hidden rounded-[1.7rem] bg-white border border-[#FED7AA] shadow-[0_20px_60px_rgba(62,35,20,.18)]">
        <button onclick="closeQR()" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-stone-100 hover:bg-stone-200 flex items-center justify-center text-stone-600 transition"><i class="fa-solid fa-xmark text-xs"></i></button>
        <div class="p-6 pt-7 text-center">
            <div class="w-12 h-12 rounded-2xl bg-[#FFF7ED] border border-[#FED7AA] flex items-center justify-center mx-auto mb-3 text-[#EA580C]"><i class="fa-solid fa-qrcode text-lg"></i></div>
            <h3 class="font-extrabold text-[#000000] text-[18px] tracking-tight">QR Meja <span id="modal-nomor" class="text-[#EA580C]"></span></h3>
            <p class="text-[13px] font-medium text-black mt-1">Arahkan kamera HP ke QR — menu Nasi Padang langsung terbuka</p>
            <div class="mt-5 bg-[#FFF7ED] rounded-2xl border border-[#FED7AA] p-4 flex justify-center">
                <div id="qr-container" class="bg-white rounded-xl p-3 shadow-sm border border-stone-100"></div>
            </div>
            <div class="mt-4 rounded-xl bg-stone-50 border border-stone-200 px-3 py-2.5">
                <p class="text-[10px] font-bold uppercase tracking-widest text-black mb-1">Link Pesan</p>
                <p id="modal-url" class="text-xs font-mono font-semibold text-[#000000] break-all"></p>
                <button type="button" id="btn-copy-link" onclick="copyLink(this)" class="mt-2 text-xs font-bold text-[#EA580C] hover:underline">Salin Link</button>
            </div>
            <div class="flex gap-3 mt-5">
                <button onclick="printQR()" class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl bg-[#EA580C] hover:bg-[#C2410C] px-4 py-3 text-sm font-bold text-[#000000] shadow-sm transition"><i class="fa-solid fa-print"></i> Cetak QR</button>
                <button onclick="closeQR()" class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl bg-white border border-stone-300 px-4 py-3 text-sm font-bold text-black hover:bg-stone-50">Tutup</button>
            </div>
            <p class="text-[11px] text-black mt-3">Cetak & tempel di meja — pelanggan scan tanpa login</p>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    function showQR(url, nomor) {
        document.getElementById('modal-nomor').textContent = nomor;
        document.getElementById('modal-url').textContent = url;

        const container = document.getElementById('qr-container');
        container.innerHTML = '';

        new QRCode(container, {
            text: url,
            width: 180,
            height: 180,
            correctLevel: QRCode.CorrectLevel.H
        });

        document.getElementById('modal-qr').classList.remove('hidden');
    }

    function closeQR() {
        document.getElementById('modal-qr').classList.add('hidden');
    }

    function copyLink(btn) {
        const text = document.getElementById('modal-url').textContent.trim();

        if (!text) {
            return;
        }

        const done = function (ok) {
            btn.textContent = ok ? 'Tersalin!' : 'Gagal menyalin';
            setTimeout(function () { btn.textContent = 'Salin Link'; }, 1500);
        };

        // Clipboard API hanya tersedia di konteks aman (HTTPS/localhost),
        // sehingga butuh fallback untuk akses via HTTP IP lokal.
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text).then(
                function () { done(true); },
                function () { fallbackCopy(text, done); }
            );
            return;
        }

        fallbackCopy(text, done);
    }

    function fallbackCopy(text, done) {
        try {
            const ta = document.createElement('textarea');
            ta.value = text;
            ta.setAttribute('readonly', '');
            ta.style.position = 'fixed';
            ta.style.opacity = '0';
            document.body.appendChild(ta);
            ta.select();
            ta.setSelectionRange(0, ta.value.length);
            const ok = document.execCommand('copy');
            document.body.removeChild(ta);
            done(ok);
        } catch (e) {
            done(false);
        }
    }

    function printQR() {
        const canvas = document.querySelector('#qr-container canvas');

        if (!canvas) {
            return;
        }

        const nomor = document.getElementById('modal-nomor').textContent;
        const url = document.getElementById('modal-url').textContent;
        const win = window.open('');

        win.document.write(`
            <html>
            <head>
                <title>QR Code ${nomor}</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        text-align: center;
                        padding: 30px;
                    }
                    .box {
                        border: 1px solid #ddd;
                        border-radius: 16px;
                        padding: 24px;
                        display: inline-block;
                    }
                    img {
                        width: 220px;
                        height: 220px;
                    }
                    h2 {
                        margin-bottom: 8px;
                    }
                    p {
                        font-size: 12px;
                        color: #555;
                        word-break: break-all;
                    }
                </style>
            </head>
            <body>
                <div class="box">
                    <h2>Meja ${nomor}</h2>
                    <img src="${canvas.toDataURL()}">
                    <p>${url}</p>
                </div>
            </body>
            </html>
        `);

        win.document.close();
        win.focus();
        win.print();
        win.close();
    }

    document.getElementById('modal-qr').addEventListener('click', function(e) {
        if (e.target === this) {
            closeQR();
        }
    });
</script>

<?= $this->endSection() ?>