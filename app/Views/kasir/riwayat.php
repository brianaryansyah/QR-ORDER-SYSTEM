<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<?php
helper('app_setting');
$storeNameForReceipt = app_setting('store_name', 'NasPad Zendry');
$formatMetode = static function ($m){ return match($m){'tunai'=>'Tunai','qris'=>'QRIS / E-Wallet','transfer'=>'Transfer Bank', default=>ucfirst((string)$m)}; };
$statusBadgeClass = static function ($s){ return match($s){'selesai'=>'bg-emerald-50 text-emerald-900 border-emerald-300','pending'=>'bg-amber-50 text-amber-900 border-amber-300', default=>'bg-stone-100 text-black border-stone-300'}; };
$statusLabel = static function ($s){ return match($s){'selesai'=>'Selesai','pending'=>'Pending', default=>ucfirst((string)$s)}; };
$formatRupiah = static function ($v){ return 'Rp '.number_format((int)$v,0,',','.'); };
$receiptData = static function (array $p) use ($formatMetode): array {
    $items=[]; $sub=0; foreach(($p['items']??[]) as $it){ $h=(int)($it['harga']??0); $q=(int)($it['qty']??0); $s=$h*$q; $sub+=$s; $items[]=['nama_menu'=>$it['nama_menu']??'-','harga'=>$h,'qty'=>$q,'subtotal'=>$s,'catatan'=>$it['catatan']??'']; }
    $pot=(int)($p['potongan']??0); $ppn=(int)($p['ppn']??0); $tot=(int)($p['total_harga']??0); if($sub<=0 && $tot>0) $sub=max(0,$tot-$ppn+$pot);
    return ['id'=>(int)($p['id']??0),'kode_pesanan'=>$p['kode_pesanan']??'-','nomor_meja'=>$p['nomor_meja']??'-','nama_pemesan'=>$p['nama_pemesan']??'-','metode_pembayaran'=>$p['metode_pembayaran']??'-','metode_label'=>$formatMetode($p['metode_pembayaran']??'-'),'status'=>$p['status']??'selesai','catatan'=>$p['catatan']??'','created_at'=>$p['created_at']??date('Y-m-d H:i:s'),'updated_at'=>$p['updated_at']??($p['created_at']??date('Y-m-d H:i:s')),'waktu'=>!empty($p['updated_at'])?date('d M Y, H:i',strtotime($p['updated_at'])):date('d M Y, H:i'),'subtotal'=>$sub,'potongan'=>$pot,'ppn'=>$ppn,'total_harga'=>$tot,'items'=>$items];
};
$jsonFlags = JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP;
?>
<div class="space-y-6">
    <div class="rounded-[1.6rem] bg-white border border-[#FED7AA] shadow-[0_8px_28px_rgba(62,35,20,.06)] p-5 lg:p-7">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
            <div>
                <p class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[#FFF7ED] border border-[#FED7AA] text-[#EA580C] text-xs font-bold mb-3"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Kasir</p>
                <h1 class="text-2xl lg:text-3xl font-extrabold tracking-tight text-[#000000]">Riwayat Transaksi</h1>
                <p class="text-sm font-medium text-black mt-2 max-w-2xl">Daftar pesanan yang sudah selesai diproses, lengkap dengan detail transaksi dan cetak ulang struk.</p>
            </div>
            <div class="rounded-2xl bg-[#FFF7ED] border border-[#FDE68A] p-4 min-w-full sm:min-w-[280px] lg:min-w-[320px]">
                <div class="flex items-center gap-3"><div class="w-12 h-12 rounded-xl bg-white border border-[#FED7AA] flex items-center justify-center text-[#EA580C]"><i class="fa-solid fa-receipt"></i></div><div><p class="text-sm font-bold text-[#000000]">Total Transaksi</p><p class="text-2xl font-extrabold text-[#000000] mt-1"><?= number_format((int)$total_pesanan,0,',','.') ?></p></div></div>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="rounded-2xl bg-white border border-[#FED7AA] p-5 shadow-[0_4px_14px_rgba(62,35,20,.05)]">
            <div class="flex items-center gap-4"><div class="w-12 h-12 rounded-xl bg-[#F0FFF4] border border-green-200 flex items-center justify-center text-green-700"><i class="fa-solid fa-receipt"></i></div><div><p class="text-xs font-bold uppercase tracking-[0.08em] text-black">Total Pesanan</p><p class="text-2xl font-extrabold text-[#000000] mt-1"><?= number_format((int)$total_pesanan,0,',','.') ?></p></div></div>
        </div>
        <div class="rounded-2xl bg-white border border-[#FED7AA] p-5 shadow-[0_4px_14px_rgba(62,35,20,.05)]">
            <div class="flex items-center gap-4"><div class="w-12 h-12 rounded-xl bg-[#EFF6FF] border border-sky-200 flex items-center justify-center text-sky-700"><i class="fa-solid fa-sack-dollar"></i></div><div><p class="text-xs font-bold uppercase tracking-[0.08em] text-black">Total Pendapatan</p><p class="text-2xl font-extrabold text-[#000000] mt-1"><?= $formatRupiah($total_pendapatan) ?></p></div></div>
        </div>
    </div>
    <div class="rounded-2xl border border-[#FED7AA] bg-white shadow-[0_8px_28px_rgba(62,35,20,.06)] p-5 lg:p-6">
        <form action="<?= base_url('kasir/riwayat') ?>" method="get" class="grid grid-cols-1 lg:grid-cols-[1fr_1fr_auto] gap-4 items-end">
            <div><label class="block text-xs font-bold text-black uppercase tracking-[0.08em] mb-2">Dari Tanggal</label><input type="date" name="start_date" value="<?= esc($start_date??'') ?>" class="w-full rounded-xl bg-white border border-stone-300 px-4 py-3 text-sm font-semibold text-[#000000] focus:outline-none focus:ring-2 focus:ring-[#EA580C]/20 focus:border-[#EA580C]"></div>
            <div><label class="block text-xs font-bold text-black uppercase tracking-[0.08em] mb-2">Sampai Tanggal</label><input type="date" name="end_date" value="<?= esc($end_date??'') ?>" class="w-full rounded-xl bg-white border border-stone-300 px-4 py-3 text-sm font-semibold text-[#000000] focus:outline-none focus:ring-2 focus:ring-[#EA580C]/20 focus:border-[#EA580C]"></div>
            <div class="flex gap-2"><button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#EA580C] hover:bg-[#C2410C] px-5 py-3 text-sm font-bold text-[#000000] shadow-sm"><i class="fa-solid fa-filter"></i> Terapkan</button><a href="<?= base_url('kasir/riwayat') ?>" class="inline-flex items-center justify-center gap-2 rounded-xl bg-white border border-stone-300 px-5 py-3 text-sm font-bold text-black hover:bg-stone-50"><i class="fa-solid fa-rotate-left"></i> Reset</a></div>
        </form>
    </div>
    <div class="rounded-2xl border border-[#FED7AA] bg-white shadow-[0_8px_28px_rgba(62,35,20,.06)] overflow-hidden">
        <div class="p-5 lg:p-6 border-b border-[#FED7AA] flex flex-col md:flex-row md:items-center md:justify-between gap-3 bg-white">
            <div><h2 class="text-lg font-extrabold text-[#000000]">Daftar Transaksi Selesai</h2><p class="text-xs font-medium text-black mt-1">Klik detail untuk melihat item pesanan. Klik cetak untuk mencetak ulang struk transaksi.</p></div>
            <span class="inline-flex items-center gap-2 text-xs font-semibold bg-[#FFF7ED] border border-[#FDE68A] text-black px-3 py-2 rounded-full"><i class="fa-solid fa-calendar-day text-[#EA580C]"></i><?= esc(($start_date??'')?:'Semua') ?> <?= !empty($end_date)?'s/d '.esc($end_date):'' ?></span>
        </div>
        <?php if (empty($riwayat)): ?>
            <div class="flex flex-col items-center justify-center py-20 text-center"><div class="w-16 h-16 rounded-2xl bg-[#FFF7ED] border border-[#FED7AA] flex items-center justify-center mb-4"><i class="fa-solid fa-clock-rotate-left text-black text-2xl"></i></div><p class="text-sm font-bold text-[#000000]">Belum ada riwayat</p><p class="text-xs font-medium text-black mt-1">Transaksi yang sesuai pencarian tidak ditemukan.</p></div>
        <?php else: ?>
            <div class="overflow-x-auto"><table class="w-full text-left border-collapse">
                <thead><tr class="bg-[#FED7AA] border-b border-[#FED7AA] text-xs font-bold text-[#C2410C] uppercase tracking-[0.08em]"><th class="px-5 py-4 whitespace-nowrap">Waktu Selesai</th><th class="px-5 py-4 whitespace-nowrap">ID Pesanan</th><th class="px-5 py-4 whitespace-nowrap">Pelanggan</th><th class="px-5 py-4 whitespace-nowrap">Meja</th><th class="px-5 py-4 whitespace-nowrap">Metode</th><th class="px-5 py-4 whitespace-nowrap">Total</th><th class="px-5 py-4 text-right whitespace-nowrap">Aksi</th></tr></thead>
                <tbody class="divide-y divide-stone-100">
                    <?php foreach ($riwayat as $p): $dataStruk=$receiptData($p); $jsonStruk=json_encode($dataStruk,$jsonFlags); $subtotal=(int)($dataStruk['subtotal']??0); $potongan=(int)($dataStruk['potongan']??0); $ppn=(int)($dataStruk['ppn']??0); ?>
                        <tr class="hover:bg-[#FFF7ED] transition">
                            <td class="px-5 py-4 text-sm font-medium text-black whitespace-nowrap"><?= !empty($p['updated_at'])?date('d M Y, H:i',strtotime($p['updated_at'])):'-' ?></td>
                            <td class="px-5 py-4 whitespace-nowrap"><span class="inline-flex items-center gap-2 text-xs font-bold bg-sky-50 text-sky-900 border border-sky-200 px-3 py-1.5 rounded-full"><i class="fa-solid fa-hashtag"></i><?= esc($p['kode_pesanan']) ?></span></td>
                            <td class="px-5 py-4 text-sm font-bold text-[#000000] whitespace-nowrap"><?= esc($p['nama_pemesan']) ?></td>
                            <td class="px-5 py-4 text-sm font-medium text-black whitespace-nowrap">Meja <?= esc($p['nomor_meja']) ?></td>
                            <td class="px-5 py-4 whitespace-nowrap"><span class="inline-flex items-center text-[11px] font-bold px-2.5 py-1 rounded-full border bg-white text-black border-stone-300"><i class="fa-solid fa-wallet mr-1.5 text-[#EA580C]"></i><?= esc($formatMetode($p['metode_pembayaran']??'-')) ?></span></td>
                            <td class="px-5 py-4 text-sm font-extrabold text-[#000000] whitespace-nowrap"><?= $formatRupiah($p['total_harga']) ?></td>
                            <td class="px-5 py-4 text-right whitespace-nowrap"><div class="flex items-center justify-end gap-2"><button type="button" class="inline-flex items-center justify-center gap-2 rounded-xl bg-white border border-stone-300 px-3 py-2 text-xs font-bold text-black hover:bg-stone-50" onclick="toggleDetail('detail-<?= esc($p['id']) ?>')"><i class="fa-solid fa-eye"></i> Detail</button><button type="button" onclick='cetakStruk(<?= $jsonStruk ?>)' class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#EA580C] hover:bg-[#C2410C] px-3 py-2 text-xs font-bold text-[#000000]"><i class="fa-solid fa-print"></i> Cetak</button></div></td>
                        </tr>
                        <tr id="detail-<?= esc($p['id']) ?>" class="hidden bg-[#FFF7ED]"><td colspan="7" class="px-5 py-5"><div class="rounded-xl border border-[#FED7AA] bg-white p-5"><div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-5"><div class="flex-1"><h4 class="text-xs font-bold text-black uppercase tracking-[0.08em] mb-3">Item Pesanan</h4><ul class="space-y-2.5"><?php foreach(($p['items']??[]) as $item): ?><li class="flex items-start justify-between gap-4 text-sm"><span class="flex items-start gap-2 text-black"><i class="fa-solid fa-circle-dot text-black text-[9px] mt-1.5"></i><span><span class="font-bold text-[#000000]"><?= esc($item['nama_menu']) ?></span> <span class="text-[#EA580C] font-bold">x<?= esc($item['qty']) ?></span><?php if(!empty($item['catatan'])): ?><span class="block text-xs font-medium text-amber-900 italic mt-1">Catatan: <?= esc($item['catatan']) ?></span><?php endif; ?></span></span><span class="text-xs font-bold text-black whitespace-nowrap"><?= $formatRupiah(($item['harga']??0)*($item['qty']??0)) ?></span></li><?php endforeach; ?></ul><?php if(!empty($p['catatan'])): ?><div class="mt-4 text-xs font-medium text-amber-900 bg-amber-50 border border-amber-200 rounded-xl px-4 py-3"><i class="fa-solid fa-note-sticky mr-1"></i> Catatan: <?= esc($p['catatan']) ?></div><?php endif; ?></div><div class="w-full lg:w-[280px] rounded-xl border border-stone-200 bg-[#FFF7ED] p-4"><h4 class="text-xs font-bold text-black uppercase tracking-[0.08em] mb-3">Ringkasan</h4><div class="space-y-2 text-sm"><div class="flex justify-between gap-4 text-black"><span>Subtotal</span><span class="font-bold text-[#000000]"><?= $formatRupiah($subtotal) ?></span></div><?php if($potongan>0): ?><div class="flex justify-between gap-4 text-emerald-800"><span>Promo</span><span class="font-bold">-<?= $formatRupiah($potongan) ?></span></div><?php endif; ?><div class="flex justify-between gap-4 text-black"><span>PPN 10%</span><span class="font-bold text-[#000000]"><?= $formatRupiah($ppn) ?></span></div><div class="border-t border-stone-200 pt-3 mt-3 flex justify-between gap-4"><span class="font-extrabold text-[#000000]">Total</span><span class="font-extrabold text-[#000000]"><?= $formatRupiah($p['total_harga']) ?></span></div></div><button type="button" onclick='cetakStruk(<?= $jsonStruk ?>)' class="mt-4 w-full inline-flex items-center justify-center gap-2 rounded-xl bg-[#EA580C] hover:bg-[#C2410C] px-4 py-3 text-xs font-bold text-[#000000]"><i class="fa-solid fa-print"></i> Cetak Struk</button></div></div></div></td></tr>
                    <?php endforeach; ?>
                </tbody>
            </table></div>
        <?php endif; ?>
    </div>
</div>
<script>
    const STORE_NAME = <?= json_encode($storeNameForReceipt) ?>;
    function toggleDetail(id){ const el=document.getElementById(id); if(el) el.classList.toggle('hidden'); }
    function escapeHTML(v){ return String(v??'').replaceAll('&','&amp;').replaceAll('<','&lt;').replaceAll('>','&gt;').replaceAll('"','&quot;').replaceAll("'","&#039;"); }
    function formatRupiah(n){ return String(parseInt(n||0)).replace(/\B(?=(\d{3})+(?!\d))/g,'.'); }
    // cetakStruk sama seperti dashboard
    function cetakStruk(d){ const w=window.open('','_blank','width=380,height=650'); if(!w) return; w.document.write('<pre>'+JSON.stringify(d,null,2)+'</pre>'); }
</script>
<?= $this->endSection() ?>
