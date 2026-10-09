<?php
helper('app_setting');

$storeName        = app_setting('store_name', 'NasPad Zendry');
$storeLogo        = app_setting('store_logo', '');
$storeFavicon     = app_setting('store_favicon', '');
$storeWhatsapp    = app_setting('store_whatsapp', '');

$logoSrc = ! empty($storeLogo)
    ? base_url($storeLogo)
    : base_url('assets/img/logo_k.png');

$faviconPath = ! empty($storeFavicon)
    ? $storeFavicon
    : (! empty($storeLogo) ? $storeLogo : 'assets/img/logo_k.png');

$faviconSrc = base_url($faviconPath);

$whatsappUrl = '';
if (! empty($storeWhatsapp)) {
    $waNumber = preg_replace('/[^0-9]/', '', $storeWhatsapp);
    $waText   = 'Halo, saya mengalami kendala QR Code tidak valid.';
    $whatsappUrl = 'https://wa.me/' . $waNumber . '?text=' . rawurlencode($waText);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Tidak Valid — <?= esc($storeName) ?></title>

    <link rel="icon" type="image/png" href="<?= esc($faviconSrc) ?>">

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root {
            --bg: #FFF7ED;
            --card: #FFFFFF;
            --border: #FED7AA;
            --muted: #57534E;
            --rose: #f43f5e;
            --sky: #0ea5e9;
            --purple: #7c3aed;
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
            color: #000000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
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

        .wrap {
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 1;
        }

        .card {
            position: relative;
            overflow: hidden;
            border-radius: 32px;
            background: #FFFFFF;
            border: 1px solid #FED7AA;
            box-shadow: var(--shadow);
            padding: 28px 22px;
            text-align: center;
        }

        .card::before {
            content: '';
            position: absolute;
            right: -70px;
            top: -80px;
            width: 190px;
            height: 190px;
            background: rgba(244,63,94,.10);
            filter: blur(42px);
            border-radius: 50%;
        }

        .card::after {
            content: '';
            position: absolute;
            left: -90px;
            bottom: -90px;
            width: 220px;
            height: 220px;
            background: rgba(234,88,12,.08);
            filter: blur(44px);
            border-radius: 50%;
        }

        .content {
            position: relative;
            z-index: 1;
        }

        .logo {
            width: 64px;
            height: 64px;
            border-radius: 24px;
            background: #FFF7ED;
            border: 1px solid #FED7AA;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin: 0 auto 18px;
        }

        .logo img {
            width: 52px;
            height: 52px;
            object-fit: contain;
        }

        .logo-fallback {
            display: none;
            width: 52px;
            height: 52px;
            border-radius: 20px;
            background: #EA580C;
            align-items: center;
            justify-content: center;
            color: #000000;
        }

        .icon {
            width: 74px;
            height: 74px;
            border-radius: 28px;
            background: #fff1f2;
            border: 1px solid #ffe4e6;
            color: #e11d48;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 18px;
            font-size: 30px;
            box-shadow: 0 8px 16px rgba(225,29,72,.10);
        }

        h1 {
            font-size: 24px;
            line-height: 1.2;
            font-weight: 900;
            letter-spacing: -.04em;
            margin-bottom: 9px;
            color: #000000;
        }

        p {
            font-size: 13px;
            color: var(--muted);
            line-height: 1.65;
            margin-bottom: 22px;
        }

        .actions {
            display: grid;
            gap: 10px;
        }

        .btn {
            text-decoration: none;
            width: 100%;
            border-radius: 20px;
            padding: 14px 16px;
            font-size: 13px;
            font-weight: 900;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            transition: .2s ease;
        }

        .btn-primary {
            background: #EA580C;
            color: #FFFFFF;
            box-shadow: 0 12px 24px rgba(234, 88, 12, .25);
        }

        .btn-secondary {
            background: #FFFFFF;
            color: #EA580C;
            border: 1px solid #FED7AA;
        }

        .btn:hover {
            opacity: .94;
        }
    </style>
</head>

<body>

<div class="wrap">
    <div class="card">
        <div class="content">
            <div class="logo">
                <img src="<?= esc($logoSrc) ?>"
                     alt="Logo <?= esc($storeName) ?>"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                <div class="logo-fallback">
                    <i class="fa-solid fa-store"></i>
                </div>
            </div>

            <div class="icon">
                <i class="fa-solid fa-qrcode"></i>
            </div>

            <h1>QR Code Tidak Valid</h1>

            <p>
                Meja tidak ditemukan, QR sudah tidak aktif, atau link yang kamu buka tidak sesuai.
                Silakan hubungi kasir untuk mendapatkan QR Code yang benar.
            </p>

            <div class="actions">
                <?php if (! empty($whatsappUrl)): ?>
                    <a href="<?= esc($whatsappUrl) ?>" target="_blank" rel="noopener" class="btn btn-primary">
                        <i class="fa-brands fa-whatsapp"></i>
                        Hubungi Admin
                    </a>
                <?php endif; ?>

                <a href="<?= base_url() ?>" class="btn btn-secondary">
                    <i class="fa-solid fa-house"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>
</div>

</body>
</html>