<?php
helper('app_setting');

$storeName        = app_setting('store_name', 'NasPad Zendry');
$storeLogo        = app_setting('store_logo', '');
$storeFavicon     = app_setting('store_favicon', '');
$storeDescription = app_setting('store_description', 'Konfirmasi pesanan Anda sebelum dikirim ke kasir.');

$logoSrc = ! empty($storeLogo)
    ? base_url($storeLogo)
    : base_url('assets/img/logo_k.png');

$faviconPath = ! empty($storeFavicon)
    ? $storeFavicon
    : (! empty($storeLogo) ? $storeLogo : 'assets/img/logo_k.png');

$faviconSrc = base_url($faviconPath);

$nomorMeja = $meja['nomor_meja'] ?? '-';
$kodeMeja  = $meja['kode_unik'] ?? '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Meja <?= esc($nomorMeja) ?> — <?= esc($storeName) ?></title>

    <link rel="icon" type="image/png" href="<?= esc($faviconSrc) ?>">

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root {
            --bg: #FFF7ED;
            --bg-soft: #FFF7ED;
            --card: #FFFFFF;
            --card-strong: #FFFFFF;
            --border: #FED7AA;
            --text: #000000;
            --muted: #57534E;
            --muted-2: #78716C;
            --sky: #0ea5e9;
            --purple: #7c3aed;
            --rose: #f43f5e;
            --emerald: #22c55e;
            --amber: #f59e0b;
            --gradient: linear-gradient(135deg, #EA580C, #C2410C);
            --shadow: 0 12px 40px rgba(234, 88, 12, 0.08);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            min-height: 100vh;
            background: #FFF7ED;
            color: var(--text);
            -webkit-font-smoothing: antialiased;
            padding-bottom: 112px;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            pointer-events: none;
            opacity: .55;
            background-image: radial-gradient(rgba(124,45,18,.06) 1px, transparent 1px);
            background-size: 22px 22px;
            mask-image: linear-gradient(to bottom, rgba(0,0,0,.18), transparent 85%);
            z-index: 0;
        }

        button,
        a,
        input,
        textarea,
        label {
            -webkit-tap-highlight-color: transparent;
        }

        .app {
            width: 100%;
            max-width: 520px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .header {
            position: sticky;
            top: 0;
            z-index: 30;
            padding: 16px;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border-bottom: 1px solid #FED7AA;
            display: flex;
            align-items: center;
            gap: 13px;
        }

        .btn-back {
            width: 43px;
            height: 43px;
            border-radius: 17px;
            background: #FFFFFF;
            border: 1px solid #FED7AA;
            color: var(--muted);
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            flex-shrink: 0;
            transition: .2s ease;
        }

        .btn-back:hover {
            background: #FFF7ED;
            color: #EA580C;
        }

        .brand-mini {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }

        .logo-box {
            width: 42px;
            height: 42px;
            border-radius: 16px;
            background: #FFF7ED;
            border: 1px solid #FED7AA;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
        }

        .logo-box img {
            width: 34px;
            height: 34px;
            object-fit: contain;
        }

        .logo-fallback {
            display: none;
            width: 34px;
            height: 34px;
            border-radius: 13px;
            background: #EA580C;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .header-title {
            min-width: 0;
        }

        .header-title h1 {
            font-size: 16px;
            font-weight: 900;
            color: #000000;
            line-height: 1.2;
        }

        .header-title p {
            font-size: 12px;
            color: var(--muted);
            margin-top: 3px;
        }

        .header-title p span {
            color: #EA580C;
            font-weight: 900;
        }

        .main {
            padding: 18px 16px 0;
        }

        .hero-card {
            position: relative;
            overflow: hidden;
            border-radius: 28px;
            background: #FFFFFF;
            border: 1px solid #FED7AA;
            box-shadow: var(--shadow);
            padding: 18px;
            margin-bottom: 16px;
        }

        .hero-card::before {
            content: '';
            position: absolute;
            right: -60px;
            top: -70px;
            width: 180px;
            height: 180px;
            background: rgba(234, 88, 12, .1);
            filter: blur(38px);
            border-radius: 50%;
        }

        .hero-card::after {
            content: '';
            position: absolute;
            left: -80px;
            bottom: -80px;
            width: 200px;
            height: 200px;
            background: rgba(234, 88, 12, .08);
            filter: blur(42px);
            border-radius: 50%;
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 11px;
            border-radius: 999px;
            background: #FFF7ED;
            border: 1px solid #FED7AA;
            color: #EA580C;
            font-size: 11px;
            font-weight: 900;
            margin-bottom: 12px;
        }

        .hero-title {
            font-size: 23px;
            line-height: 1.18;
            font-weight: 900;
            letter-spacing: -.04em;
            color: #000000;
        }

        .hero-desc {
            font-size: 13px;
            line-height: 1.6;
            color: var(--muted);
            margin-top: 8px;
        }

        .section-card {
            background: #FFFFFF;
            border: 1px solid #FED7AA;
            border-radius: 26px;
            padding: 18px;
            margin-bottom: 16px;
            box-shadow: var(--shadow);
        }

        .section-title {
            font-size: 14px;
            font-weight: 900;
            margin-bottom: 16px;
            color: #000000;
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .section-title i {
            width: 34px;
            height: 34px;
            border-radius: 14px;
            background: #FFF7ED;
            border: 1px solid #FED7AA;
            color: #EA580C;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 14px;
            padding: 13px 0;
            border-bottom: 1px dashed #FED7AA;
        }

        .order-item:last-child {
            border-bottom: 0;
        }

        .item-info h4 {
            font-size: 13px;
            font-weight: 800;
            color: #000000;
            line-height: 1.35;
        }

        .item-info p {
            font-size: 12px;
            color: #EA580C;
            font-weight: 900;
            margin-top: 4px;
        }

        .item-price {
            font-size: 13px;
            font-weight: 900;
            color: #000000;
            white-space: nowrap;
        }

        .order-summary {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid #FED7AA;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 9px;
            font-size: 13px;
            color: var(--muted);
        }

        .summary-row.promo {
            color: var(--emerald);
            display: none;
        }

        .summary-row.promo.show {
            display: flex;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 12px;
            padding-top: 14px;
            border-top: 1px solid #FED7AA;
        }

        .summary-total span {
            font-size: 13px;
            font-weight: 800;
            color: var(--muted);
        }

        .summary-total strong {
            font-size: 20px;
            font-weight: 900;
            color: #000000;
        }

        .payment-options {
            display: grid;
            gap: 11px;
        }

        .pay-option {
            position: relative;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px;
            border-radius: 20px;
            background: #FFFFFF;
            border: 1px solid #FED7AA;
            cursor: pointer;
            transition: .2s ease;
        }

        .pay-option:hover,
        .pay-option:has(input:checked) {
            background: #FFF7ED;
            border-color: #EA580C;
            box-shadow: 0 0 0 1px #EA580C;
        }

        .pay-option input[type="radio"] {
            display: none;
        }

        .pay-radio {
            width: 18px;
            height: 18px;
            border-radius: 999px;
            border: 2px solid #FED7AA;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .pay-radio::after {
            content: '';
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: transparent;
        }

        .pay-option input[type="radio"]:checked + .pay-radio {
            border-color: #EA580C;
        }

        .pay-option input[type="radio"]:checked + .pay-radio::after {
            background: #EA580C;
        }

        .pay-icon {
            width: 40px;
            height: 40px;
            border-radius: 16px;
            background: #FFF7ED;
            border: 1px solid #FED7AA;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #EA580C;
            flex-shrink: 0;
        }

        .pay-option input[type="radio"]:checked ~ .pay-icon {
            color: #FFFFFF;
            background: #EA580C;
            border-color: #EA580C;
        }

        .pay-info span {
            display: block;
            font-size: 13px;
            font-weight: 900;
            color: #000000;
        }

        .pay-info small {
            display: block;
            font-size: 11px;
            color: var(--muted-2);
            margin-top: 3px;
            line-height: 1.35;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group:last-child {
            margin-bottom: 0;
        }

        .form-group label {
            display: block;
            font-size: 11px;
            font-weight: 900;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .12em;
            margin-bottom: 9px;
        }

        .form-group label span {
            color: var(--rose);
        }

        .form-control {
            width: 100%;
            background: #FFFFFF;
            border: 1px solid #FED7AA;
            color: #000000;
            border-radius: 18px;
            padding: 14px 15px;
            font-size: 13px;
            outline: none;
            transition: .2s ease;
        }

        .form-control::placeholder {
            color: #9CA3AF;
        }

        .form-control:focus {
            border-color: #EA580C;
            box-shadow: 0 0 0 4px rgba(234, 88, 12, .1);
        }

        textarea.form-control {
            resize: none;
        }

        .promo-wrap {
            display: flex;
            gap: 10px;
        }

        .promo-wrap .form-control {
            text-transform: uppercase;
        }

        .btn-promo {
            border: 1px solid #FED7AA;
            background: #FFF7ED;
            color: #EA580C;
            border-radius: 18px;
            padding: 0 18px;
            font-size: 12px;
            font-weight: 900;
            cursor: pointer;
            transition: .2s ease;
            white-space: nowrap;
        }

        .btn-promo:hover {
            background: #EA580C;
            color: #FFFFFF;
        }

        .promo-msg {
            font-size: 11px;
            font-weight: 800;
            margin-top: 8px;
            display: none;
        }

        .bottom-action {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 40;
            display: flex;
            justify-content: center;
            padding: 14px 16px 18px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border-top: 1px solid #FED7AA;
        }

        .bottom-action-inner {
            width: 100%;
            max-width: 488px;
        }

        .btn-submit {
            width: 100%;
            border: 0;
            border-radius: 22px;
            background: #EA580C;
            color: #FFFFFF;
            padding: 16px;
            font-size: 14px;
            font-weight: 900;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            box-shadow: 0 12px 24px rgba(234, 88, 12, .25);
            transition: transform .15s ease, opacity .15s ease;
        }

        .btn-submit:active {
            transform: scale(.98);
        }

        .btn-submit:disabled {
            background: #F3F4F6;
            color: #9CA3AF;
            box-shadow: none;
            cursor: not-allowed;
        }

        .toast {
            position: fixed;
            left: 50%;
            top: 18px;
            transform: translateX(-50%) translateY(-20px);
            opacity: 0;
            pointer-events: none;
            z-index: 100;
            width: calc(100% - 32px);
            max-width: 488px;
            border-radius: 18px;
            background: #FFFFFF;
            border: 1px solid #FED7AA;
            box-shadow: var(--shadow);
            padding: 12px 14px;
            color: #000000;
            font-size: 12px;
            font-weight: 800;
            transition: .22s ease;
        }

        .toast.show {
            transform: translateX(-50%) translateY(0);
            opacity: 1;
        }

        .empty-state {
            display: none;
            text-align: center;
            padding: 54px 18px;
            border-radius: 28px;
            background: #FFFFFF;
            border: 1px solid #FED7AA;
            box-shadow: var(--shadow);
        }

        .empty-state i {
            font-size: 42px;
            color: #EA580C;
            margin-bottom: 14px;
        }

        .empty-state h2 {
            font-size: 18px;
            font-weight: 900;
            color: #000000;
            margin-bottom: 6px;
        }

        .empty-state p {
            font-size: 13px;
            color: var(--muted);
            line-height: 1.5;
            margin-bottom: 18px;
        }

        .btn-outline {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            border: 1px solid #FED7AA;
            color: #EA580C;
            background: #FFF7ED;
            padding: 12px 18px;
            border-radius: 18px;
            font-size: 13px;
            font-weight: 900;
        }
        
        .btn-outline:hover {
            background: #EA580C;
            color: #FFFFFF;
        }

        @media (max-width: 380px) {
            .main {
                padding-inline: 12px;
            }

            .header {
                padding-inline: 12px;
            }

            .hero-title {
                font-size: 21px;
            }

            .section-card {
                padding: 15px;
            }

            .pay-option {
                padding: 12px;
            }

            .pay-icon {
                width: 36px;
                height: 36px;
                border-radius: 14px;
            }
        }
    </style>
</head>

<body>

<div class="app">

    <div id="error-msg" class="toast">Pesan error</div>

    <header class="header">
        <a href="javascript:history.back()" class="btn-back" aria-label="Kembali">
            <i class="fa-solid fa-arrow-left"></i>
        </a>

        <div class="brand-mini">
            <div class="header-title">
                <h1>Konfirmasi Pesanan</h1>
                <p>Meja <span><?= esc($nomorMeja) ?></span> — <?= esc($storeName) ?></p>
            </div>
        </div>
    </header>

    <main class="main">

        <div id="empty-state" class="empty-state">
            <i class="fa-solid fa-cart-arrow-down"></i>
            <h2>Keranjang kosong</h2>
            <p>Belum ada menu yang dipilih. Silakan kembali ke halaman menu dan pilih pesanan dulu.</p>
            <a href="<?= base_url('order/' . $kodeMeja) ?>" class="btn-outline">
                <i class="fa-solid fa-arrow-left"></i>
                Kembali ke Menu
            </a>
        </div>

        <div id="checkout-content">
            <section class="hero-card">
                <div class="hero-content">
                    <div class="badge">
                        <i class="fa-solid fa-clipboard-check"></i>
                        Checkout
                    </div>

                    <h2 class="hero-title">
                        Cek pesananmu sebelum dikirim
                    </h2>

                    <p class="hero-desc">
                        <?= esc($storeDescription ?: 'Pastikan menu, nama pemesan, dan metode pembayaran sudah benar sebelum pesanan dikirim ke kasir.') ?>
                    </p>
                </div>
            </section>

            <section class="section-card">
                <h3 class="section-title">
                    <i class="fa-solid fa-clipboard-list"></i>
                    Pesanan Kamu
                </h3>

                <div id="order-list"></div>

                <div class="order-summary">
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span id="subtotal-val">Rp 0</span>
                    </div>

                    <div id="row-promo" class="summary-row promo">
                        <span>Potongan Promo</span>
                        <span id="promo-val">-Rp 0</span>
                    </div>

                    <div class="summary-row">
                        <span>PPN 10%</span>
                        <span id="ppn-val">Rp 0</span>
                    </div>

                    <div class="summary-total">
                        <span>Total Pembayaran</span>
                        <strong id="order-total">Rp 0</strong>
                    </div>
                </div>
            </section>

            <section class="section-card">
                <h3 class="section-title">
                    <i class="fa-solid fa-wallet"></i>
                    Metode Pembayaran
                </h3>

                <div class="payment-options">
                    <label class="pay-option">
                        <input type="radio" name="payment_method" value="tunai" checked>
                        <div class="pay-radio"></div>
                        <div class="pay-icon">
                            <i class="fa-solid fa-money-bill-wave"></i>
                        </div>
                        <div class="pay-info">
                            <span>Tunai / COD</span>
                            <small>Bayar langsung ke kasir. Pesanan diproses setelah pembayaran diterima.</small>
                        </div>
                    </label>

                    <label class="pay-option">
                        <input type="radio" name="payment_method" value="qris">
                        <div class="pay-radio"></div>
                        <div class="pay-icon">
                            <i class="fa-solid fa-qrcode"></i>
                        </div>
                        <div class="pay-info">
                            <span>QRIS / E-Wallet</span>
                            <small>Bayar online melalui Midtrans Snap.</small>
                        </div>
                    </label>

                    <label class="pay-option">
                        <input type="radio" name="payment_method" value="transfer">
                        <div class="pay-radio"></div>
                        <div class="pay-icon">
                            <i class="fa-solid fa-building-columns"></i>
                        </div>
                        <div class="pay-info">
                            <span>Transfer Bank / VA</span>
                            <small>Virtual Account BCA, BNI, BRI, Permata, dan bank lain.</small>
                        </div>
                    </label>
                </div>
            </section>

            <section class="section-card">
                <h3 class="section-title">
                    <i class="fa-solid fa-user-pen"></i>
                    Data Pemesan
                </h3>

                <div class="form-group">
                    <label>Nama Kamu <span>*</span></label>
                    <input type="text" id="nama_pemesan" class="form-control" placeholder="Masukkan nama kamu">
                </div>

                <div class="form-group">
                    <label>Catatan</label>
                    <textarea id="catatan" rows="3" class="form-control" placeholder="Misal: tidak pakai pedas, es dipisah"></textarea>
                </div>

                <div class="form-group">
                    <label>Kode Promo</label>
                    <div class="promo-wrap">
                        <input type="text" id="kode_promo" class="form-control" placeholder="Masukkan kode">
                        <button type="button" onclick="cekPromo()" class="btn-promo">Pakai</button>
                    </div>
                    <p id="promo-info" class="promo-msg"></p>
                </div>
            </section>
        </div>
    </main>

</div>

<div class="bottom-action">
    <div class="bottom-action-inner">
        <button id="btn-pesan" onclick="submitOrder()" class="btn-submit">
            <i class="fa-solid fa-paper-plane"></i>
            Kirim Pesanan Sekarang
        </button>
    </div>
</div>

<script>
    const KODE_MEJA  = <?= json_encode($kodeMeja) ?>;
    const SUBMIT_URL = <?= json_encode(base_url('order/submit/' . $kodeMeja)) ?>;
    const MENU_URL   = <?= json_encode(base_url('order/' . $kodeMeja)) ?>;
    const PROMO_URL  = <?= json_encode(base_url('order/cek-promo/' . $kodeMeja)) ?>;

    const cart = JSON.parse(localStorage.getItem('arzana_cart_' + KODE_MEJA) || '[]');

    let potongan  = 0;
    let kodeValid = '';

    function formatRupiah(number) {
        return Number(number || 0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function escapeHTML(value) {
        return String(value ?? '').replace(/[&<>"']/g, function(match) {
            return ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            })[match];
        });
    }

    function getSubtotal() {
        return cart.reduce((sum, item) => {
            return sum + (Number(item.harga || 0) * Number(item.qty || 0));
        }, 0);
    }

    function hitungPPN(totalSetelahDiskon) {
        return Math.floor(Number(totalSetelahDiskon || 0) * 0.10);
    }

    function resetPromo(message = '') {
        potongan  = 0;
        kodeValid = '';

        const info     = document.getElementById('promo-info');
        const rowPromo = document.getElementById('row-promo');

        rowPromo.classList.remove('show');

        if (message !== '') {
            info.style.display = 'block';
            info.textContent = message;
            info.style.color = 'var(--rose)';
        }

        renderOrder();
    }

    function showError(msg) {
        const el = document.getElementById('error-msg');

        el.textContent = msg;
        el.classList.add('show');

        clearTimeout(window.__errorTimer);
        window.__errorTimer = setTimeout(() => {
            el.classList.remove('show');
        }, 3500);
    }

    async function cekPromo() {
        const input    = document.getElementById('kode_promo').value.trim().toUpperCase();
        const info     = document.getElementById('promo-info');
        const rowPromo = document.getElementById('row-promo');

        info.style.display = 'block';

        if (input === '') {
            resetPromo('Masukkan kode promo terlebih dahulu.');
            return;
        }

        const subtotal = getSubtotal();

        if (subtotal <= 0) {
            resetPromo('Keranjang masih kosong.');
            return;
        }

        const formData = new FormData();
        formData.append('kode_promo', input);
        formData.append('subtotal', subtotal);

        try {
            const res = await fetch(PROMO_URL, {
                method: 'POST',
                body: formData
            });

            const text = await res.text();

            let data;

            try {
                data = JSON.parse(text);
            } catch (e) {
                resetPromo('Response promo tidak valid dari server.');
                console.error('Raw promo response:', text);
                return;
            }

            if (!data.success) {
                resetPromo(data.message || 'Kode promo tidak valid.');
                return;
            }

            potongan  = Number(data.potongan || 0);
            kodeValid = input;

            info.innerHTML = '<i class="fa-solid fa-circle-check"></i> ' + (data.message || 'Kode promo berhasil digunakan.');
            info.style.color = 'var(--emerald)';
            rowPromo.classList.add('show');

            document.getElementById('promo-val').textContent = '-Rp ' + formatRupiah(potongan);

            renderOrder();
        } catch (err) {
            resetPromo('Gagal mengecek promo.');
            console.error(err);
        }
    }

    function renderOrder() {
        const list = document.getElementById('order-list');

        if (cart.length === 0) {
            document.getElementById('empty-state').style.display = 'block';
            document.getElementById('checkout-content').style.display = 'none';
            document.querySelector('.bottom-action').style.display = 'none';
            return;
        }

        let subtotal = 0;

        list.innerHTML = cart.map(item => {
            const nama = escapeHTML(item.nama_menu || '-');
            const qty  = Number(item.qty || 0);
            const sub  = Number(item.harga || 0) * qty;

            subtotal += sub;

            return `
                <div class="order-item">
                    <div class="item-info">
                        <h4>${nama}</h4>
                        <p>x${qty}</p>
                    </div>
                    <div class="item-price">Rp ${formatRupiah(sub)}</div>
                </div>
            `;
        }).join('');

        if (potongan > subtotal) {
            potongan = subtotal;
        }

        const totalSetelahDiskon = Math.max(0, subtotal - potongan);
        const ppn                = hitungPPN(totalSetelahDiskon);
        const finalTotal         = totalSetelahDiskon + ppn;

        document.getElementById('subtotal-val').textContent = 'Rp ' + formatRupiah(subtotal);
        document.getElementById('promo-val').textContent    = '-Rp ' + formatRupiah(potongan);
        document.getElementById('ppn-val').textContent      = 'Rp ' + formatRupiah(ppn);
        document.getElementById('order-total').textContent  = 'Rp ' + formatRupiah(finalTotal);
    }

    async function submitOrder() {
        const nama          = document.getElementById('nama_pemesan').value.trim();
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;

        if (!nama) {
            showError('Nama pemesan wajib diisi.');
            return;
        }

        if (cart.length === 0) {
            showError('Keranjang masih kosong.');
            return;
        }

        const btn = document.getElementById('btn-pesan');

        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mengirim...';

        const formData = new FormData();
        formData.append('nama_pemesan', nama);
        formData.append('catatan', document.getElementById('catatan').value);
        formData.append('payment_method', paymentMethod);
        formData.append('items', JSON.stringify(cart));

        /*
         * Yang dikirim cuma kode promo.
         * Potongan dan PPN dihitung ulang di server supaya aman.
         */
        formData.append('kode_promo', kodeValid);

        try {
            const res  = await fetch(SUBMIT_URL, {
                method: 'POST',
                body: formData
            });

            const text = await res.text();

            let data;

            try {
                data = JSON.parse(text);
            } catch (e) {
                showError('Response tidak valid dari server.');
                console.error('Raw response:', text);

                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Kirim Pesanan Sekarang';
                return;
            }

            if (data.success) {
                localStorage.removeItem('arzana_cart_' + KODE_MEJA);

                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    window.location.href = MENU_URL;
                }

                return;
            }

            if (data.debug || data.debug_error) {
                showError('Debug mode aktif. Cek console browser.');
                console.log('DEBUG RESPONSE:', data);
            } else {
                showError(data.message || 'Terjadi kesalahan saat mengirim pesanan.');
            }

            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Kirim Pesanan Sekarang';
        } catch (err) {
            showError('Gagal menghubungi server: ' + err.message);
            console.error(err);

            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Kirim Pesanan Sekarang';
        }
    }

    const promoInput = document.getElementById('kode_promo');

    if (promoInput) {
        promoInput.addEventListener('input', function () {
            const currentValue = this.value.trim().toUpperCase();

            if (kodeValid !== '' && currentValue !== kodeValid) {
                resetPromo('');
                document.getElementById('promo-info').style.display = 'none';
            }
        });
    }

    renderOrder();
</script>

</body>
</html>