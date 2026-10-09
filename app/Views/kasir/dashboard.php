<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<?php
helper('app_setting');
$storeNameForReceipt = app_setting('store_name', 'NasPad Zendry');
$formatMetode = static function ($m){ return match($m){'tunai'=>'Tunai / COD','qris'=>'QRIS / E-Wallet','transfer'=>'Transfer Bank / VA', default=>ucfirst((string)$m)}; };
$statusBadgeClass = static function ($s){ return match($s){'selesai'=>'bg-emerald-50 text-emerald-900 border-emerald-300','pending'=>'bg-amber-50 text-amber-900 border-amber-300', default=>'bg-stone-100 text-black border-stone-300'}; };
$statusLabel = static function ($s){ return match($s){'selesai'=>'Pembayaran Selesai','pending'=>'Menunggu Bayar', default=>ucfirst((string)$s)}; };
$statusPesananBadgeClass = static function ($s){ return match($s){'siap'=>'bg-emerald-50 text-emerald-900 border-emerald-300','selesai'=>'bg-sky-50 text-sky-900 border-sky-300','menunggu'=>'bg-amber-50 text-amber-900 border-amber-300', default=>'bg-stone-100 text-black border-stone-300'}; };
$statusPesananLabel = static function ($s){ return match($s){'siap'=>'Siap Diantarkan','selesai'=>'Selesai','menunggu'=>'Sedang Disiapkan', default=>ucfirst((string)$s)}; };
$formatRupiah = static function ($v){ return 'Rp '.number_format((int)$v,0,',','.'); };
$receiptData = static function (array $p) use ($formatMetode): array {
    $items=[]; $sub=0; foreach(($p['items']??[]) as $it){ $h=(int)($it['harga']??0); $q=(int)($it['qty']??0); $s=$h*$q; $sub+=$s; $items[]=['nama_menu'=>$it['nama_menu']??'-','harga'=>$h,'qty'=>$q,'subtotal'=>$s,'catatan'=>$it['catatan']??'']; }
    $pot=(int)($p['potongan']??0); $ppn=(int)($p['ppn']??0); $tot=(int)($p['total_harga']??0); if($sub<=0 && $tot>0) $sub=max(0,$tot-$ppn+$pot);
    return ['id'=>(int)($p['id']??0),'kode_pesanan'=>$p['kode_pesanan']??'-','nomor_antrian'=>$p['nomor_antrian']??'-','nomor_meja'=>$p['nomor_meja']??'-','nama_pemesan'=>$p['nama_pemesan']??'-','metode_pembayaran'=>$p['metode_pembayaran']??'-','metode_label'=>$formatMetode($p['metode_pembayaran']??'-'),'status'=>$p['status']??'pending','status_pesanan'=>$p['status_pesanan']??'menunggu','catatan'=>$p['catatan']??'','created_at'=>$p['created_at']??date('Y-m-d H:i:s'),'waktu'=>!empty($p['created_at'])?date('H:i',strtotime($p['created_at'])):date('H:i'),'subtotal'=>$sub,'potongan'=>$pot,'ppn'=>$ppn,'total_harga'=>$tot,'items'=>$items];
};
$riwayatHariIni = $riwayat_hari_ini ?? []; $jsonFlags = JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP;
?>
<div class="space-y-6">
    <!-- Hero terang - human -->
    <div class="rounded-[1.6rem] bg-white border border-[#FED7AA] shadow-[0_8px_28px_rgba(62,35,20,.06)] p-5 lg:p-6">
        <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-5">
            <div>
                <p class="inline-flex items-center gap-2 rounded-full bg-[#FFF7ED] border border-[#FED7AA] px-3 py-1.5 text-[11px] font-bold tracking-widest uppercase text-[#EA580C]"><span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Kasir Live • Siap saji</p>
                <h1 class="mt-3 text-[22px] lg:text-[26px] font-extrabold tracking-tight text-[#000000]" style="font-family:'Instrument Sans',sans-serif">Antrean Pesanan</h1>
                <p class="text-sm font-medium text-black mt-1">Konfirmasi tunai, cetak struk, tandai siap — semua terang dan jelas.</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="<?= base_url('monitor') ?>" target="_blank" class="inline-flex items-center justify-center gap-2 rounded-xl border border-[#EA580C] text-[#EA580C] hover:bg-[#FFF7ED] px-5 py-3 text-sm font-bold transition"><i class="fa-solid fa-display text-[13px]"></i> Buka Monitor</a>
                <button type="button" id="btn-enable-sound" onclick="aktifkanSuara()" class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#DE3C1F] hover:bg-[#c7351b] text-[#000000] px-5 py-3 text-sm font-bold shadow-sm"><i class="fa-solid fa-volume-high"></i> Aktifkan Suara</button>
                <div class="hidden sm:inline-flex items-center gap-2 rounded-xl bg-white border border-[#FED7AA] px-4 py-3 text-sm text-black"><i class="fa-regular fa-clock text-[#EA580C]"></i><span id="waktu-sekarang" class="font-bold text-[#000000]"></span></div>
            </div>
        </div>
    </div>
    <?php if (session()->getFlashdata('success')): ?><div class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900"><i class="fa-solid fa-circle-check"></i><span><?= esc(session()->getFlashdata('success')) ?></span></div><?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?><div class="flex items-center gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"><i class="fa-solid fa-circle-exclamation"></i><span><?= esc(session()->getFlashdata('error')) ?></span></div><?php endif; ?>

    <!-- 3 Stat cards terang -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="rounded-2xl bg-amber-50 border border-amber-100 p-5 shadow-[0_4px_14px_rgba(124,45,18,.05)]">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-white border border-amber-200 flex items-center justify-center text-amber-600 shadow-sm"><i class="fa-solid fa-hourglass-half"></i></div>
                <div><p class="text-[11px] font-bold uppercase tracking-[0.14em] text-amber-900">Menunggu</p><p class="text-3xl font-extrabold text-[#000000] mt-1" id="count-pending"><?= (int)$total_pending ?></p></div>
            </div>
        </div>
        <div class="rounded-2xl bg-green-50 border border-green-100 p-5 shadow-[0_4px_14px_rgba(124,45,18,.05)]">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-white border border-green-200 flex items-center justify-center text-green-600 shadow-sm">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><ellipse cx="12" cy="7" rx="7" ry="3.5"/><path d="M5 7v4c0 1.9 3.1 3.5 7 3.5s7-1.6 7-3.5V7"/><path d="M5 11.5v3.5c0 1.9 3.1 3.5 7 3.5s7-1.6 7-3.5v-3.5"/></svg>
                </div>
                <div><p class="text-[11px] font-bold uppercase tracking-[0.14em] text-green-900">Siap Hari Ini</p><p class="text-3xl font-extrabold text-[#000000] mt-1" id="count-selesai"><?= (int)$total_selesai ?></p></div>
            </div>
        </div>
        <div class="rounded-2xl bg-orange-50 border border-orange-100 p-5 shadow-[0_4px_14px_rgba(124,45,18,.05)]">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-white border border-orange-200 flex items-center justify-center text-[#DE3C1F] shadow-sm"><i class="fa-solid fa-wallet"></i></div>
                <div><p class="text-[11px] font-bold uppercase tracking-[0.14em] text-orange-900">Pendapatan Hari Ini</p><p class="text-2xl font-extrabold text-[#000000] mt-1" id="pendapatan-hari-ini" data-amount="<?= (int)$pendapatan ?>"><?= $formatRupiah($pendapatan) ?></p></div>
            </div>
        </div>
    </div>

    <!-- Pesanan Menunggu terang -->
    <div class="rounded-2xl bg-white border border-[#FED7AA] shadow-[0_8px_28px_rgba(62,35,20,.06)] overflow-hidden">
        <div class="p-5 lg:p-6 border-b border-[#FED7AA] flex flex-col md:flex-row md:items-center md:justify-between gap-3 bg-white">
            <div><h2 class="text-[16px] font-extrabold text-[#000000]">Pesanan Menunggu</h2><p class="text-xs font-medium text-black mt-1">Untuk tunai, klik <b class="text-[#000000]">Telah Bayar</b> setelah uang diterima.</p></div>
            <span class="inline-flex items-center gap-2 text-xs font-semibold bg-stone-100 text-black border border-stone-300 px-3 py-1.5 rounded-full"><i class="fa-solid fa-rotate text-black text-[11px]"></i> Auto-refresh setiap 3 detik</span>
        </div>
        <div class="p-4 lg:p-5 bg-white">
            <div id="order-container" class="space-y-4 <?= empty($pesanan_pending) ? 'hidden' : '' ?>">
                <?php foreach (($pesanan_pending ?? []) as $p): ?>
                <?php $orderJson=json_encode($receiptData($p),$jsonFlags); $met=strtolower((string)($p['metode_pembayaran']??'')); $sb=$p['status']??'pending'; $sp=$p['status_pesanan']??'menunggu'; $tun=$met==='tunai'; $lunas=$sb==='selesai'; $boleh=$lunas && $sp==='menunggu'; ?>
                <div class="rounded-xl border border-stone-200 bg-white overflow-hidden" data-order-card="<?= esc($p['id']) ?>">
                    <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4 px-5 py-4 border-b border-stone-100 bg-[#FFF7ED]">
                        <div class="flex items-start gap-4">
                            <div class="w-14 h-14 rounded-xl bg-[#EA580C] flex items-center justify-center text-[#000000] flex-shrink-0"><div class="text-center leading-none"><p class="text-[8px] font-bold uppercase tracking-widest text-[#000000]/80">Antri</p><p class="text-lg font-black"><?= esc($p['nomor_antrian']??'-') ?></p></div></div>
                            <div><p class="text-[15px] font-extrabold text-[#000000]">Meja <?= esc($p['nomor_meja']) ?> <span class="ml-2 text-xs font-semibold text-black"><?= esc($p['kode_pesanan']) ?></span></p><p class="text-xs font-medium text-black mt-1"><i class="fa-solid fa-user mr-1 text-black"></i><?= esc($p['nama_pemesan']) ?> <span class="mx-1">·</span><?= date('H:i',strtotime($p['created_at'])) ?></p><div class="mt-2 flex flex-wrap gap-2"><span class="inline-flex text-[11px] font-semibold px-2.5 py-1 rounded-full border bg-white text-black border-stone-300"><?= esc($formatMetode($met)) ?></span><span class="inline-flex text-[11px] font-semibold px-2.5 py-1 rounded-full border <?= $statusBadgeClass($sb) ?>"><?= esc($statusLabel($sb)) ?></span><span class="inline-flex text-[11px] font-semibold px-2.5 py-1 rounded-full border <?= $statusPesananBadgeClass($sp) ?>"><?= esc($statusPesananLabel($sp)) ?></span></div></div>
                        </div>
                        <div class="flex flex-wrap items-center gap-2"><span class="text-lg font-extrabold text-[#000000]"><?= $formatRupiah($p['total_harga']) ?></span>
                        <?php if($tun && !$lunas): ?><form action="<?= base_url('kasir/telah-bayar/'.$p['id']) ?>" method="post" onsubmit="return confirm('Sudah terima uang?')"><?= csrf_field() ?><button class="inline-flex gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 px-4 py-2.5 text-xs font-bold text-[#000000]"><i class="fa-solid fa-money-bill-wave"></i> Telah Bayar</button></form>
                        <?php elseif(!$lunas): ?><span class="inline-flex gap-2 rounded-xl bg-amber-100 text-amber-900 border border-amber-200 px-4 py-2.5 text-xs font-bold"><i class="fa-solid fa-clock"></i> Menunggu Bayar</span>
                        <?php else: ?><button onclick='cetakStruk(<?= $orderJson ?>)' class="inline-flex gap-2 rounded-xl bg-white border border-stone-200 px-4 py-2.5 text-xs font-bold text-black"><i class="fa-solid fa-print"></i> Cetak</button><?php if($boleh): ?><form action="<?= base_url('kasir/siap/'.$p['id']) ?>" method="post"><?= csrf_field() ?><button class="inline-flex gap-2 rounded-xl bg-[#DE3C1F] hover:bg-[#c7351b] px-4 py-2.5 text-xs font-bold text-[#000000]"><i class="fa-solid fa-bell-concierge"></i> Pesanan Siap</button></form><?php endif; endif; ?>
                        </div>
                    </div>
                    <div class="px-5 py-4"><ul class="space-y-2"><?php foreach(($p['items']??[]) as $it): ?><li class="flex justify-between text-sm"><span><span class="font-bold text-[#000000]"><?= esc($it['nama_menu']) ?></span> <span class="text-[#9A1B0A] font-bold">x<?= esc($it['qty']) ?></span></span><span class="text-xs font-medium text-black">Rp <?= number_format((int)$it['harga']*(int)$it['qty'],0,',','.') ?></span></li><?php endforeach; ?></ul></div>
                </div>
                <?php endforeach; ?>
            </div>
            <div id="empty-state" class="flex flex-col items-center justify-center py-14 text-center <?= !empty($pesanan_pending)?'hidden':'' ?>">
                <div class="w-24 h-14 rounded-[18px] bg-gradient-to-b from-[#fefce8] to-[#e7e5c8] border border-[#d6a86a] flex items-center justify-center mb-4 shadow-sm">
                    <svg width="72" height="42" viewBox="0 0 72 42" fill="none"><ellipse cx="36" cy="32" rx="26" ry="6" fill="#86efac" stroke="#22c55e" stroke-width="1.2"/><path d="M14 32 Q36 26 58 32" stroke="#16a34a" stroke-width=".7" stroke-dasharray="3 3" fill="none"/><ellipse cx="36" cy="22" rx="18" ry="5" fill="white" stroke="#EA580C" stroke-width="1.2"/><ellipse cx="36" cy="16.5" rx="18" ry="5" fill="white" stroke="#EA580C" stroke-width="1.2"/><ellipse cx="36" cy="11" rx="18" ry="5" fill="white" stroke="#EA580C" stroke-width="1.3"/><ellipse cx="36" cy="9" rx="14" ry="3.2" fill="#fffbeb" stroke="#fde68a" stroke-width=".9"/><path d="M26 9 Q36 2 46 9" fill="white" stroke="#e7cc8a" stroke-width=".9"/></svg>
                </div>
                <p class="text-[15px] font-extrabold text-[#000000]">Belum ada pesanan masuk</p>
                <p class="text-sm font-medium text-black mt-1 max-w-[32ch]">Pesanan lauk (Rendang, Gulai, Ayam Pop) yang siap akan muncul di sini.</p>
            </div>
        </div>
    </div>

    <!-- Riwayat -->
    <div class="rounded-2xl bg-white border border-[#FED7AA] shadow-[0_8px_28px_rgba(62,35,20,.06)] overflow-hidden">
        <div class="p-5 lg:p-6 border-b border-[#FED7AA] flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div><h2 class="text-[16px] font-extrabold text-[#000000]">Riwayat Pesanan Hari Ini</h2><p class="text-xs font-medium text-black mt-1">Struk dapat dicetak ulang dari sini.</p></div>
            <span class="inline-flex items-center gap-2 text-xs font-semibold bg-[#FFF7ED] border border-[#FED7AA] text-black px-3 py-2 rounded-full"><i class="fa-solid fa-calendar-day text-[#EA580C]"></i><?= date('d M Y') ?></span>
        </div>
        <div class="overflow-x-auto"><table class="w-full text-sm">
            <thead class="bg-[#FED7AA] border-b border-[#FED7AA]"><tr>
                <th class="text-left text-[11px] font-extrabold text-[#C2410C] uppercase tracking-[0.14em] px-5 py-3">Waktu</th>
                <th class="text-left text-[11px] font-extrabold text-[#C2410C] uppercase tracking-[0.14em] px-5 py-3">Antrean</th>
                <th class="text-left text-[11px] font-extrabold text-[#C2410C] uppercase tracking-[0.14em] px-5 py-3">Kode</th>
                <th class="text-left text-[11px] font-extrabold text-[#C2410C] uppercase tracking-[0.14em] px-5 py-3">Meja</th>
                <th class="text-left text-[11px] font-extrabold text-[#C2410C] uppercase tracking-[0.14em] px-5 py-3">Pemesan</th>
                <th class="text-left text-[11px] font-extrabold text-[#C2410C] uppercase tracking-[0.14em] px-5 py-3">Status</th>
                <th class="text-right text-[11px] font-extrabold text-[#C2410C] uppercase tracking-[0.14em] px-5 py-3">Total</th>
                <th class="text-right text-[11px] font-extrabold text-[#C2410C] uppercase tracking-[0.14em] px-5 py-3">Aksi</th>
            </tr></thead>
            <tbody id="riwayat-body" class="divide-y divide-stone-100">
                <?php if(empty($riwayatHariIni)): ?><tr id="riwayat-empty"><td colspan="8" class="px-5 py-12 text-center text-sm font-medium text-black">Belum ada riwayat pesanan hari ini.</td></tr>
                <?php else: foreach($riwayatHariIni as $r): $js=json_encode($receiptData($r),$jsonFlags); $sp=$r['status_pesanan']??'menunggu'; $sb=$r['status']??'pending'; ?>
                <tr id="riwayat-order-<?= esc($r['id']) ?>" class="hover:bg-[#FFF7ED]"><td class="px-5 py-4 font-medium text-black"><?= date('H:i',strtotime($r['created_at'])) ?></td><td class="px-5 py-4 font-black text-[#EA580C]"><?= esc($r['nomor_antrian']??'-') ?></td><td class="px-5 py-4 font-bold text-[#000000]"><?= esc($r['kode_pesanan']) ?></td><td class="px-5 py-4 font-medium text-black">Meja <?= esc($r['nomor_meja']) ?></td><td class="px-5 py-4 font-medium text-black"><?= esc($r['nama_pemesan']) ?></td><td class="px-5 py-4"><span class="inline-flex text-[11px] font-bold px-2.5 py-1 rounded-full border <?= $statusPesananBadgeClass($sp) ?>"><?= esc($statusPesananLabel($sp)) ?></span></td><td class="px-5 py-4 text-right font-extrabold text-[#000000]"><?= $formatRupiah($r['total_harga']) ?></td><td class="px-5 py-4 text-right"><?php if($sb==='selesai'): ?><button onclick='cetakStruk(<?= $js ?>)' class="inline-flex gap-2 rounded-xl bg-white border border-stone-300 px-3 py-2 text-xs font-bold text-[#EA580C] hover:bg-[#FFF7ED]"><i class="fa-solid fa-print"></i> Cetak</button><?php else: ?><span class="text-xs font-bold text-black">-</span><?php endif; ?></td></tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table></div>
    </div>
</div>

<div id="modal-notif" class="fixed inset-0 z-50 hidden items-center justify-center bg-[#000000]/50 backdrop-blur-sm">
    <div class="bg-white border border-[#FED7AA] rounded-[1.5rem] p-6 w-full max-w-sm mx-4 shadow-xl" id="modal-box">
        <div class="w-16 h-16 rounded-full bg-amber-50 border border-amber-200 flex items-center justify-center mx-auto mb-4" id="modal-icon-wrap"><i class="fa-solid fa-bell text-[#EA580C] text-2xl" id="modal-icon"></i></div>
        <h3 class="text-lg font-extrabold text-[#000000] text-center" id="modal-title">Pesanan Baru!</h3>
        <p class="text-black font-medium text-center text-sm mt-1" id="modal-message">Ada pesanan baru masuk.</p>
    </div>
</div>
<script src="https://code.responsivevoice.org/responsivevoice.js"></script>
<script>
const STORE_NAME = <?= json_encode($storeNameForReceipt) ?>;
let lastId = <?= (int)($last_id ?? 0) ?>, lastPaymentTime='<?= esc($last_payment_time ?? date('Y-m-d H:i:s')) ?>', isCheckingOrder=false, isCheckingPayment=false, csrfHash='<?= csrf_hash() ?>', antreanTTS=[], sedangBicara=false, soundEnabled=localStorage.getItem('naspad-zendry_kasir_sound_enabled')==='1';
function updateWaktu(){ const el=document.getElementById('waktu-sekarang'); if(el) el.textContent=new Date().toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit',second:'2-digit'}); }
updateWaktu(); setInterval(updateWaktu,1000); updateSoundButton();
function updateSoundButton(){ const b=document.getElementById('btn-enable-sound'); if(!b) return; if(soundEnabled){ b.innerHTML='<i class="fa-solid fa-volume-high"></i> Suara Aktif'; b.classList.remove('bg-[#DE3C1F]'); b.classList.add('bg-emerald-600'); } else { b.innerHTML='<i class="fa-solid fa-volume-high"></i> Aktifkan Suara'; b.classList.add('bg-[#DE3C1F]'); b.classList.remove('bg-emerald-600'); } }
function aktifkanSuara(){ soundEnabled=true; localStorage.setItem('naspad-zendry_kasir_sound_enabled','1'); updateSoundButton(); try{ if(typeof responsiveVoice!=='undefined') responsiveVoice.speak('Suara kasir aktif','Indonesian Female',{pitch:1.05,rate:.95}); }catch(e){} }
function escapeHTML(v){ return String(v??'').replaceAll('&','&amp;').replaceAll('<','&lt;').replaceAll('>','&gt;').replaceAll('"','&quot;').replaceAll("'","&#039;"); }
function formatRupiah(n){ return String(parseInt(n||0)).replace(/\B(?=(\d{3})+(?!\d))/g,'.'); }
function formatMetodeLabel(m){ if(m==='tunai') return 'Tunai / COD'; if(m==='qris') return 'QRIS / E-Wallet'; if(m==='transfer') return 'Transfer Bank / VA'; return m||'-'; }
function formatStatusLabel(s){ if(s==='selesai') return 'Pembayaran Selesai'; if(s==='pending') return 'Menunggu Bayar'; return s? s.charAt(0).toUpperCase()+s.slice(1):'-'; }
function statusBadgeClass(s){ if(s==='selesai') return 'bg-emerald-50 text-emerald-900 border-emerald-200'; if(s==='pending') return 'bg-amber-50 text-amber-900 border-amber-200'; return 'bg-stone-100 text-black border-stone-200'; }
function formatStatusPesananLabel(s){ if(s==='siap') return 'Siap Diantarkan'; if(s==='selesai') return 'Selesai'; if(s==='menunggu') return 'Sedang Disiapkan'; return s? s.charAt(0).toUpperCase()+s.slice(1):'-'; }
function statusPesananBadgeClass(s){ if(s==='siap') return 'bg-emerald-50 text-emerald-900 border-emerald-200'; if(s==='selesai') return 'bg-sky-50 text-sky-900 border-sky-200'; if(s==='menunggu') return 'bg-amber-50 text-amber-900 border-amber-200'; return 'bg-stone-100 text-black border-stone-200'; }
function cetakStruk(d){ alert('Cetak '+d.kode_pesanan); }
// polling tetap sama
function cekPesananBaru(){ if(isCheckingOrder) return; isCheckingOrder=true; fetch('<?= base_url('kasir/cek_baru/') ?>'+lastId,{headers:{'X-Requested-With':'XMLHttpRequest'}}).then(r=>r.json()).then(data=>{ if(data.csrfHash) csrfHash=data.csrfHash; if(data.pesanan && data.pesanan.length>0){ lastId=data.last_id||lastId; location.reload(); } }).finally(()=>isCheckingOrder=false); }
function cekPembayaranBerhasil(){ if(isCheckingPayment) return; isCheckingPayment=true; fetch('<?= base_url('kasir/cek_pembayaran_berhasil') ?>?last_time='+encodeURIComponent(lastPaymentTime),{headers:{'X-Requested-With':'XMLHttpRequest'}}).then(r=>r.json()).then(data=>{ if(data.csrfHash) csrfHash=data.csrfHash; if(data.pesanan && data.pesanan.length>0){ lastPaymentTime=data.last_payment_time||lastPaymentTime; location.reload(); } }).finally(()=>isCheckingPayment=false); }
setInterval(cekPesananBaru,3000); setInterval(cekPembayaranBerhasil,3000);
</script>
<?= $this->endSection() ?>
