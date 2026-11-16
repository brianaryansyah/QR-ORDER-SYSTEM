<?php
helper('app_setting');

$storeName        = app_setting('store_name', 'NasPad Zendry');
$storeLogo        = app_setting('store_logo', '');
$storeFavicon     = app_setting('store_favicon', '');
$storeDescription = app_setting('store_description', 'Pesan makanan lebih mudah dari meja Anda.');
$storeWhatsapp    = app_setting('store_whatsapp', '');

$logoSrc = ! empty($storeLogo)
    ? base_url($storeLogo)
    : '';

$faviconPath = ! empty($storeFavicon)
    ? $storeFavicon
    : (! empty($storeLogo) ? $storeLogo : '');

$faviconSrc = ! empty($faviconPath) ? base_url($faviconPath) : '';

$nomorMeja = $meja['nomor_meja'] ?? '-';
$kodeMeja  = $meja['kode_unik'] ?? '';

$whatsappUrl = '';
if (! empty($storeWhatsapp)) {
    $waNumber = preg_replace('/[^0-9]/', '', $storeWhatsapp);
    $waText   = 'Halo, saya pelanggan meja ' . $nomorMeja . ' ingin bertanya.';
    $whatsappUrl = 'https://wa.me/' . $waNumber . '?text=' . rawurlencode($waText);
}

$promos = $promos ?? [];

$formatRupiah = static function ($value): string {
    return 'Rp ' . number_format((int) $value, 0, ',', '.');
};

$promoDiskonLabel = static function (array $promo) use ($formatRupiah): string {
    if (($promo['tipe_diskon'] ?? '') === 'persen') {
        $label = 'Diskon ' . (int) ($promo['nilai_diskon'] ?? 0) . '%';

        if ((int) ($promo['maksimal_diskon'] ?? 0) > 0) {
            $label .= ' maks. ' . $formatRupiah($promo['maksimal_diskon']);
        }

        return $label;
    }

    return 'Potongan ' . $formatRupiah($promo['nilai_diskon'] ?? 0);
};

$promoSyaratLabel = static function (array $promo) use ($formatRupiah): string {
    $minimal = (int) ($promo['minimal_belanja'] ?? 0);

    if ($minimal > 0) {
        return 'Min. belanja ' . $formatRupiah($minimal);
    }

    return 'Tanpa minimal belanja';
};
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Meja <?= esc($nomorMeja) ?> — <?= esc($storeName) ?></title>

    <?php if (! empty($faviconSrc)): ?>
    <link rel="icon" type="image/png" href="<?= esc($faviconSrc) ?>">
    <?php endif; ?>

    <link
href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root {
            --bg: #FFF8F5;
            --bg-soft: #FFF8F5;
            --card: #FFFFFF;
            --card-strong: #FFFFFF;
            --border: #FFDCCF;
            --text: #1C1C1C;
            --muted: #6B7280;
            --muted-2: #9CA3AF;
            --primary: #FF5722;
            --primary-dark: #E64A19;
            --primary-light: #FF8A65;
            --gradient: linear-gradient(135deg, #FF5722, #E64A19);
            --shadow: 0 16px 46px rgba(255, 87, 34, .12);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background-color: #E5E7EB;
            color: var(--text);
            -webkit-font-smoothing: antialiased;
            display: flex;
            justify-content: center;
        }

        body::before {
            display: none;
        }

        button,
        a {
            -webkit-tap-highlight-color: transparent;
        }

        .app {
            width: 100%;
            max-width: 480px;
            min-height: 100vh;
            background:
                radial-gradient(circle at top left, rgba(255, 87, 34, 0.08), transparent 30rem),
                radial-gradient(circle at top right, rgba(251, 191, 36, 0.08), transparent 30rem),
                var(--bg);
            margin: 0 auto;
            position: relative;
            z-index: 1;
            box-shadow: 0 0 50px rgba(0, 0, 0, 0.1);
            padding-bottom: calc(120px + env(safe-area-inset-bottom));
        }

        .header {
            position: sticky;
            top: 0;
            z-index: 30;
            padding: 14px 14px 11px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border-bottom: 1px solid var(--border);
        }

        .header-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 11px;
            min-width: 0;
        }

        .logo-box {
            width: 43px;
            height: 43px;
            border-radius: 17px;
            background: var(--primary);
            border: 1px solid var(--primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
            box-shadow: 0 0 20px rgba(255, 87, 34, 0.2);
        }

        .logo-box img {
            width: 35px;
            height: 35px;
            object-fit: contain;
        }

        .logo-fallback {
            display: none;
            width: 35px;
            height: 35px;
            border-radius: 13px;
            background: var(--primary);
            align-items: center;
            justify-content: center;
            color: white;
        }

        .brand-info {
            min-width: 0;
        }

        .brand-info h1 {
            font-size: 15.5px;
            font-weight: 900;
            line-height: 1.15;
            color: var(--text);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 230px;
        }

        .brand-info p {
            font-size: 11px;
            color: var(--muted);
            margin-top: 3px;
        }

        .brand-info p span {
            color: var(--primary);
            font-weight: 900;
        }

        .header-action {
            width: 39px;
            height: 39px;
            border-radius: 15px;
            background: #FFFFFF;
            border: 1px solid var(--border);
            color: var(--muted);
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            flex-shrink: 0;
            transition: .2s ease;
        }

        .header-action:hover {
            background: rgba(255, 87, 34, 0.05);
            color: var(--primary);
            border-color: var(--primary-light);
        }

        .hero {
            padding: 14px 10px 12px;
        }

        .hero-card {
            position: relative;
            overflow: hidden;
            border-radius: 24px;
            background: #FFFFFF;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            padding: 14px;
        }

        .hero-card::before {
            content: '';
            position: absolute;
            right: -60px;
            top: -70px;
            width: 160px;
            height: 160px;
            background: rgba(255, 87, 34, 0.15);
            filter: blur(34px);
            border-radius: 50%;
        }

        .hero-card::after {
            content: '';
            position: absolute;
            left: -80px;
            bottom: -80px;
            width: 180px;
            height: 180px;
            background: rgba(251, 191, 36, 0.15);
            filter: blur(38px);
            border-radius: 50%;
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 11px;
            border-radius: 999px;
            background: #dcfce7;
            border: 1px solid #bbf7d0;
            color: #166534;
            font-size: 11px;
            font-weight: 800;
            margin-bottom: 9px;
        }

        .badge-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #16a34a;
            box-shadow: 0 0 0 4px rgba(22, 163, 74, 0.2);
        }

        .hero-title {
            font-size: 20px;
            line-height: 1.16;
            font-weight: 900;
            letter-spacing: -.045em;
            color: var(--text);
            max-width: 95%;
        }

        .hero-desc {
            font-size: 12px;
            line-height: 1.5;
            color: var(--muted);
            margin-top: 7px;
        }

        .promo-slider {
            margin-top: 12px;
            overflow: hidden;
            border-radius: 19px;
            border: 1px solid var(--border);
            background: #FFFFFF;
            position: relative;
            min-height: 78px;
        }

        .promo-slider::before,
        .promo-slider::after {
            content: '';
            position: absolute;
            top: 0;
            width: 34px;
            height: 100%;
            z-index: 2;
            pointer-events: none;
        }

        .promo-slider::before {
            left: 0;
            background: linear-gradient(to right, #FFFFFF, transparent);
        }

        .promo-slider::after {
            right: 0;
            background: linear-gradient(to left, #FFFFFF, transparent);
        }

        .promo-track {
            display: flex;
            width: max-content;
            animation: promoMarquee 18s linear infinite;
            will-change: transform;
        }

        .promo-slider:hover .promo-track {
            animation-play-state: paused;
        }

        .promo-group {
            display: flex;
            gap: 8px;
            flex-shrink: 0;
            padding: 8px 8px 8px 0;
        }

        .promo-group:first-child {
            padding-left: 8px;
        }

        .promo-card {
            width: 190px;
            min-height: 62px;
            flex-shrink: 0;
            border-radius: 16px;
            padding: 9px;
            background: #FFF8F5;
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .promo-icon {
            width: 32px;
            height: 32px;
            border-radius: 13px;
            background: var(--primary);
            border: 1px solid var(--primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #FFFFFF;
            flex-shrink: 0;
            box-shadow: 0 0 12px rgba(255, 87, 34, 0.2);
        }

        .promo-icon i {
            font-size: 13px;
        }

        .promo-info {
            min-width: 0;
        }

        .promo-code {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            max-width: 118px;
            font-size: 9px;
            font-weight: 900;
            color: var(--primary-dark);
            background: #FFDCCF;
            border: 1px solid var(--border);
            padding: 3px 7px;
            border-radius: 999px;
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .promo-info h3 {
            font-size: 11px;
            font-weight: 900;
            line-height: 1.2;
            color: var(--text);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 128px;
        }

        .promo-info p {
            font-size: 9.5px;
            color: var(--muted);
            line-height: 1.3;
            margin-top: 3px;
        }

        .promo-info p span {
            color: var(--primary);
            font-weight: 900;
        }

        .promo-empty-card {
            width: 100%;
            min-height: 66px;
            padding: 10px;
            border-radius: 16px;
            background: #FFF8F5;
            border: 1px dashed var(--border);
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--muted);
        }

        .promo-empty-card i {
            color: var(--primary);
            font-size: 14px;
        }

        .promo-empty-card p {
            font-size: 11px;
            line-height: 1.4;
        }

        @keyframes promoMarquee {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        .hero-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 9px;
            margin-top: 12px;
        }

        .meta-card {
            border-radius: 18px;
            background: #FFFFFF;
            border: 1px solid var(--border);
            padding: 10px;
        }

        .meta-card span {
            display: block;
            font-size: 10.5px;
            color: var(--muted-2);
            margin-bottom: 4px;
        }

        .meta-card strong {
            font-size: 12.5px;
            color: var(--text);
        }

        /* Search Bar */
        .search-section {
            padding: 4px 10px 14px;
        }

        .search-box {
            position: relative;
            display: flex;
            align-items: center;
            background: #FFFFFF;
            border: 1.5px solid var(--border);
            border-radius: 18px;
            padding: 0 14px;
            box-shadow: 0 4px 14px rgba(234, 88, 12, 0.05);
            transition: border-color .2s ease, box-shadow .2s ease;
        }

        .search-box:focus-within {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(255, 87, 34, 0.12);
        }

        .search-icon {
            color: #78716C;
            font-size: 14px;
            margin-right: 10px;
            flex-shrink: 0;
        }

        .search-box input {
            width: 100%;
            height: 44px;
            border: none;
            outline: none;
            background: transparent;
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
        }

        .search-box input::placeholder {
            color: #A8A29E;
            font-weight: 500;
        }

        .search-clear {
            display: none;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border: none;
            background: #F5F5F4;
            color: #78716C;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 11px;
            flex-shrink: 0;
            transition: .2s;
        }

        .search-clear:hover {
            background: #E7E5E4;
            color: #1C1917;
        }

        /* High-Conversion Floating Cart Bar */
        .floating-cart-bar {
            position: fixed;
            bottom: calc(20px + env(safe-area-inset-bottom));
            left: 50%;
            transform: translateX(-50%) translateY(160%);
            width: calc(100% - 28px);
            max-width: 440px;
            background: linear-gradient(135deg, #EA580C, #C2410C);
            border-radius: 24px;
            padding: 10px 14px 10px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 16px 36px rgba(234, 88, 12, 0.42);
            border: 1px solid rgba(255, 255, 255, 0.25);
            z-index: 55;
            cursor: pointer;
            transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.2s ease;
            opacity: 0;
            pointer-events: none;
        }

        .floating-cart-bar.active {
            transform: translateX(-50%) translateY(0);
            opacity: 1;
            pointer-events: auto;
        }

        .floating-cart-bar:active {
            transform: translateX(-50%) scale(0.98);
        }

        .floating-cart-left {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #FFFFFF;
        }

        .floating-cart-icon-box {
            position: relative;
            width: 42px;
            height: 42px;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.20);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            flex-shrink: 0;
        }

        .floating-cart-badge {
            position: absolute;
            top: -6px;
            right: -6px;
            background: #FFFFFF;
            color: #EA580C;
            font-size: 11px;
            font-weight: 900;
            min-width: 20px;
            height: 20px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 4px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }

        .floating-cart-info {
            display: flex;
            flex-direction: column;
        }

        .floating-cart-count {
            font-size: 11px;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.88);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .floating-cart-total {
            font-size: 16px;
            font-weight: 900;
            color: #FFFFFF;
            line-height: 1.15;
            letter-spacing: -0.02em;
        }

        .floating-cart-btn {
            background: #FFFFFF;
            color: #EA580C;
            border: none;
            padding: 11px 18px;
            border-radius: 18px;
            font-size: 13px;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12);
            cursor: pointer;
            transition: background 0.15s, transform 0.15s;
            flex-shrink: 0;
        }

        .floating-cart-btn:hover {
            background: #FFF7ED;
        }

        .section-head {
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 12px;
            padding: 3px 10px 10px;
        }

        .section-head h2 {
            font-size: 17px;
            font-weight: 900;
            letter-spacing: -.02em;
        }

        .section-head p {
            font-size: 11.5px;
            color: var(--muted-2);
            margin-top: 1px;
        }

        .cat-list {
            display: flex;
            flex-wrap: wrap;
            gap: 9px;
            overflow: visible;
            padding: 0 16px 16px;
        }

        .cat-list::-webkit-scrollbar {
            display: none;
        }

        .category-pill {
            border: 1px solid var(--border);
            background: #FFFFFF;
            color: var(--muted);
            padding: 10px 18px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 900;
            white-space: nowrap;
            cursor: pointer;
            transition: .2s ease;
            scroll-snap-align: start;
            flex-shrink: 0;
        }

        .category-pill:focus-visible {
            outline: 3px solid rgba(234, 88, 12, .35);
            outline-offset: 2px;
        }

        .category-pill.active {
            background: var(--primary);
            color: #FFFFFF;
            border-color: var(--primary);
            box-shadow: 0 8px 20px rgba(255, 87, 34, 0.25);
        }

        .category-pill:not(.active):hover {
            background: rgba(255, 87, 34, 0.05);
            color: var(--primary);
            border-color: var(--primary-light);
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
            padding: 0 10px;
        }

        .menu-card {
            position: relative;
            overflow: hidden;
            background: #FFFFFF;
            border: 1px solid var(--border);
            border-radius: 20px;
            min-height: 100%;
            display: flex;
            flex-direction: column;
            box-shadow: 0 6px 16px rgba(0,0,0,.03);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .menu-card:hover {
            box-shadow: 0 12px 24px rgba(255, 87, 34, 0.08);
            border-color: var(--primary-light);
        }

        .menu-card:active {
            transform: scale(0.96);
        }

        .img-wrap {
            position: relative;
            width: 100%;
            aspect-ratio: 1 / .92;
            background: rgba(255,255,255,.04);
            overflow: hidden;
        }

        .img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .img-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, transparent 40%, rgba(2,6,23,.82));
            pointer-events: none;
        }

        .no-img {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 2px;
            background:
                radial-gradient(circle at 30% 20%, rgba(255,255,255,.9), transparent 42%),
                linear-gradient(145deg, #FFF4EA, #FFE6D2);
        }

        .no-img-letter {
            font-family: 'Instrument Serif', serif;
            font-size: clamp(30px, 8vw, 44px);
            line-height: 1;
            color: #C2410C;
        }

        .no-img-label {
            font-size: 9px;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #A16207;
        }

        .stock-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 2;
            font-size: 10px;
            font-weight: 900;
            padding: 5px 9px;
            border-radius: 999px;
            border: 1px solid rgba(255,255,255,.12);
            backdrop-filter: blur(10px);
        }

        .stock-ready {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
            box-shadow: 0 2px 6px rgba(22, 101, 52, 0.12);
        }

        .stock-empty {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
            box-shadow: 0 2px 6px rgba(153, 27, 27, 0.12);
        }

        .menu-content {
            padding: 12px;
            display: flex;
            flex-direction: column;
            gap: 9px;
            flex: 1;
        }

        .item-title {
            font-size: 13px;
            line-height: 1.35;
            font-weight: 800;
            color: var(--text);
            min-height: 34px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .item-desc {
            font-size: 11px;
            line-height: 1.45;
            color: #57534E;
            min-height: 32px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .item-footer {
            margin-top: auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }

        .item-price {
            font-size: 13px;
            font-weight: 900;
            color: var(--primary);
            white-space: nowrap;
        }

        .action-container {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 7px;
            min-width: 34px;
        }

        .icon-btn {
            width: 30px;
            height: 30px;
            border: 0;
            border-radius: 999px;
            font-size: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: transform .15s ease, opacity .15s ease;
        }

        .icon-btn:active {
            transform: scale(.92);
        }

        .btn-plus {
            background: var(--primary);
            color: #FFFFFF;
            box-shadow: 0 10px 24px rgba(255, 87, 34, 0.25);
        }

        .btn-minus {
            background: #FFF0EB;
            color: var(--primary);
            box-shadow: none;
        }

        .item-qty {
            min-width: 18px;
            text-align: center;
            font-size: 13px;
            font-weight: 900;
            color: var(--text);
        }

        .btn-habis {
            border: 1px solid var(--border);
            background: #F3F4F6;
            color: var(--muted);
            padding: 7px 10px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 900;
        }

        .empty-state {
            grid-column: 1 / -1;
            padding: 38px 18px;
            text-align: center;
            border-radius: 24px;
            background: #FFFFFF;
            border: 1px solid var(--border);
        }

        .empty-state i {
            font-size: 28px;
            color: #475569;
            margin-bottom: 10px;
        }

        .empty-state p {
            font-size: 13px;
            font-weight: 800;
            color: var(--text);
        }

        .empty-state span {
            display: block;
            font-size: 12px;
            color: var(--muted-2);
            margin-top: 4px;
        }

        .bottom-bar {
            position: fixed;
            left: 50%;
            bottom: 20px;
            transform: translateX(-50%) translateY(150%);
            width: calc(100% - 32px);
            max-width: 448px;
            border-radius: 999px;
            background: var(--primary);
            box-shadow: 0 10px 32px rgba(255, 87, 34, 0.35);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 12px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            cursor: pointer;
            z-index: 40;
            transition: transform .3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .bottom-bar:active {
            transform: translateX(-50%) scale(0.97);
        }

        .bottom-bar.show {
            transform: translateX(-50%) translateY(0);
        }

        .cart-left {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .cart-bubble {
            width: 43px;
            height: 43px;
            border-radius: 18px;
            background: rgba(255,255,255,.16);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            flex-shrink: 0;
        }

        .cart-bubble i {
            color: #FFFFFF;
            font-size: 18px;
        }

        .cart-badge {
            position: absolute;
            top: -7px;
            right: -7px;
            min-width: 20px;
            height: 20px;
            border-radius: 999px;
            background: #FFFFFF;
            color: var(--primary);
            border: 2px solid var(--primary-light);
            font-size: 10px;
            font-weight: 900;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 5px;
        }

        .cart-text h4 {
            font-size: 13px;
            font-weight: 900;
            color: #fff;
            white-space: nowrap;
        }
        .cart-text p {
            font-size: 12px;
            font-weight: 700;
            color: rgba(255,255,255,.86);
            margin-top: 3px;
        }
        .cart-cta {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            font-weight: 900;
            color: #fff;
            white-space: nowrap;
        }

        .toast {
            position: fixed;
            left: 50%;
            bottom: 110px;
            transform: translateX(-50%) translateY(20px);
            opacity: 0;
            pointer-events: none;
            z-index: 60;
            width: calc(100% - 48px);
            max-width: 400px;
            border-radius: 18px;
            background: #FFFFFF;
            border: 1px solid #FED7AA;
            box-shadow: 0 10px 30px rgba(234, 88, 12, 0.2);
            padding: 12px 18px;
            color: #000000;
            font-size: 13px;
            font-weight: 800;
            text-align: center;
            transition: transform .22s cubic-bezier(0.34, 1.56, 0.64, 1), opacity .22s ease;
        }

        .toast.show {
            transform: translateX(-50%) translateY(0);
            opacity: 1;
        }

        @media (max-width: 380px) {
            .hero {
                padding: 12px 8px 10px;
            }

            .hero-card {
                padding: 12px;
                border-radius: 22px;
            }

            .hero-title {
                font-size: 18.5px;
            }

            .hero-desc {
                font-size: 11.5px;
            }

            .promo-slider {
                margin-top: 10px;
                min-height: 72px;
                border-radius: 17px;
            }

            .promo-card {
                width: 172px;
                min-height: 58px;
                padding: 8px;
                gap: 8px;
                border-radius: 15px;
            }

            .promo-icon {
                width: 29px;
                height: 29px;
                border-radius: 12px;
            }

            .promo-icon i {
                font-size: 12px;
            }

            .promo-code {
                max-width: 106px;
                font-size: 8.5px;
                padding: 3px 6px;
                margin-bottom: 3px;
            }

            .promo-info h3 {
                font-size: 10.5px;
                max-width: 116px;
            }

            .promo-info p {
                font-size: 9px;
            }

            .promo-track {
                animation-duration: 15s;
            }

            .hero-meta {
                margin-top: 10px;
                gap: 8px;
            }

            .meta-card {
                padding: 9px;
                border-radius: 16px;
            }

            .meta-card span {
                font-size: 10px;
            }

            .meta-card strong {
                font-size: 12px;
            }

            .menu-grid {
                gap: 12px;
                padding-inline: 8px;
            }

            .header,
            .section-head,
            .cat-list {
                padding-left: 8px;
                padding-right: 8px;
            }

            .brand-info h1 {
                max-width: 190px;
            }

            .item-title {
                font-size: 12.5px;
            }

            .item-desc {
                display: -webkit-box;
                -webkit-line-clamp: 1;
                -webkit-box-orient: vertical;
                overflow: hidden;
                font-size: 10px;
                min-height: auto;
            }

            .item-price {
                font-size: 12px;
                font-weight: 800;
            }

            .icon-btn {
                width: 29px;
                height: 29px;
            }
        }
        .app::after {
            content: '';
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 480px;
            height: 120px;
            background: linear-gradient(to top, #FFF8F5 35%, rgba(255, 248, 245, 0));
            pointer-events: none;
            z-index: 45;
        }

        .bottom-nav {
            position: fixed;
            bottom: calc(24px + env(safe-area-inset-bottom));
            left: 50%;
            transform: translateX(-50%);
            width: calc(100% - 40px);
            max-width: 400px;
            background: #FFFFFF;
            border: 1px solid #FED7AA;
            box-shadow: 0 20px 40px rgba(234, 88, 12, 0.15);
            border-radius: 40px;
            padding: 12px 24px;
            display: flex;
            justify-content: space-around;
            align-items: center;
            z-index: 50;
        }

        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            text-decoration: none;
            color: var(--muted-2);
            transition: color 0.2s ease;
            position: relative;
            background: transparent;
            border: none;
            cursor: pointer;
        }

        .nav-item.active {
            color: #EA580C;
        }

        .nav-icon {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            transition: all 0.2s ease;
        }

        .nav-item:hover {
            color: #EA580C;
        }

        .nav-item.active .nav-icon {
            background: #EA580C;
            color: #FFFFFF;
            box-shadow: 0 6px 16px rgba(234, 88, 12, 0.35);
        }

        .nav-item:active {
            transform: scale(0.95);
        }

        .nav-label {
            font-size: 11px;
            font-weight: 800;
        }

        .nav-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            background: #ef4444;
            color: #FFFFFF;
            font-size: 10px;
            font-weight: 900;
            height: 20px;
            min-width: 20px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 6px;
            border: 2px solid #FFFFFF;
            transform: scale(0);
            transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .nav-badge.show {
            transform: scale(1);
        }
    </style>
</head>

<body>

<div class="app">

    <header class="header">
        <div class="header-top">
            <div class="brand">


                <div class="brand-info">
                    <h1><?= esc($storeName) ?></h1>
                    <p>Meja <span><?= esc($nomorMeja) ?></span></p>
                </div>
            </div>

            <?php if (! empty($whatsappUrl)): ?>
                <a href="<?= esc($whatsappUrl) ?>"
                   target="_blank"
                   rel="noopener"
                   class="header-action"
                   title="Hubungi admin">
                    <i class="fa-brands fa-whatsapp"></i>
                </a>
            <?php else: ?>
                <button type="button" class="header-action" title="Bantuan">
                    <i class="fa-solid fa-headset"></i>
                </button>
            <?php endif; ?>
        </div>
    </header>

    <section class="hero">
        <div class="hero-card">
            <div class="hero-content">
                <div class="badge">
                    <span class="badge-dot"></span>
                    Scan QR berhasil
                </div>

                <h2 class="hero-title">
                    Pilih menu favoritmu dari meja <?= esc($nomorMeja) ?>
                </h2>

                <p class="hero-desc">
                    <?= esc($storeDescription ?: 'Tambahkan menu ke keranjang, lalu lanjut checkout untuk mengirim pesanan ke kasir.') ?>
                </p>

                <?php if (! empty($promos)): ?>
                    <div class="promo-slider" aria-label="Promo tersedia">
                        <div class="promo-track">
                            <div class="promo-group">
                                <?php foreach ($promos as $promo): ?>
                                    <div class="promo-card">
                                        <div class="promo-icon">
                                            <i class="fa-solid fa-gift"></i>
                                        </div>

                                        <div class="promo-info">
                                            <div class="promo-code">
                                                <i class="fa-solid fa-ticket"></i>
                                                <?= esc($promo['kode'] ?? '-') ?>
                                            </div>

                                            <h3><?= esc($promo['nama_promo'] ?? 'Promo Spesial') ?></h3>

                                            <p>
                                                <span><?= esc($promoDiskonLabel($promo)) ?></span><br>
                                                <?= esc($promoSyaratLabel($promo)) ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <div class="promo-group" aria-hidden="true">
                                <?php foreach ($promos as $promo): ?>
                                    <div class="promo-card">
                                        <div class="promo-icon">
                                            <i class="fa-solid fa-gift"></i>
                                        </div>

                                        <div class="promo-info">
                                            <div class="promo-code">
                                                <i class="fa-solid fa-ticket"></i>
                                                <?= esc($promo['kode'] ?? '-') ?>
                                            </div>

                                            <h3><?= esc($promo['nama_promo'] ?? 'Promo Spesial') ?></h3>

                                            <p>
                                                <span><?= esc($promoDiskonLabel($promo)) ?></span><br>
                                                <?= esc($promoSyaratLabel($promo)) ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="promo-slider">
                        <div class="promo-empty-card">
                            <i class="fa-solid fa-bullhorn"></i>
                            <p>
                                Belum ada promo aktif saat ini. Cek kembali nanti untuk voucher terbaru.
                            </p>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="hero-meta">
                    <div class="meta-card">
                        <span>Meja</span>
                        <strong><?= esc($nomorMeja) ?></strong>
                    </div>

                    <div class="meta-card">
                        <span>Status</span>
                        <strong>Siap pesan</strong>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Instant Search Bar -->
    <div class="search-section">
        <div class="search-box">
            <i class="fa-solid fa-magnifying-glass search-icon"></i>
            <input type="text" id="menu-search" placeholder="Cari rendang, ayam pop, lauk favorit..." autocomplete="off">
            <button type="button" id="search-clear" class="search-clear" aria-label="Hapus pencarian">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    </div>

    <div class="section-head">
        <div>
            <h2>Kategori</h2>
            <p>Pilih kategori menu</p>
        </div>
    </div>

    <div class="cat-list" id="cat-list">
        <button type="button"
                data-filter="semua"
                id="btn-kat-semua"
                class="category-pill active">
            Semua
        </button>

        <?php foreach ($menuByKategori as $kid => $kat): ?>
            <?php if (! empty($kat['items'])): ?>
                <button type="button"
                        data-filter="<?= esc($kid) ?>"
                        id="btn-kat-<?= esc($kid) ?>"
                        class="category-pill">
                    <?= esc($kat['nama']) ?>
                </button>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <main class="menu-grid" id="menu-grid">
        <?php $hasMenu = false; ?>

        <?php foreach ($menuByKategori as $kid => $kat): ?>
            <?php foreach (($kat['items'] ?? []) as $item): ?>
                <?php
                    $hasMenu = true;
                    $itemId = (int) ($item['id'] ?? 0);
                    $stok   = (int) ($item['stok'] ?? 0);
                    $gambar = (string) ($item['gambar'] ?? '');
                    $hasGambar = $gambar !== '' && is_file(FCPATH . 'uploads/menu/' . $gambar);
                    $initial = strtoupper(mb_substr((string) ($item['nama_menu'] ?? 'M'), 0, 1));
                ?>

                <div class="menu-card menu-item" data-kategori="<?= esc($kid) ?>">
                    <div class="img-wrap">
                        <?php if ($hasGambar): ?>
                            <img src="<?= esc(base_url('uploads/menu/' . $gambar)) ?>" alt="<?= esc($item['nama_menu'] ?? 'Menu') ?>" loading="lazy" decoding="async">
                        <?php else: ?>
                            <div class="no-img" aria-hidden="true">
                                <span class="no-img-letter"><?= esc($initial) ?></span>
                                <span class="no-img-label">Foto menyusul</span>
                            </div>
                        <?php endif; ?>

                        <?php if ($hasGambar): ?>
                            <div class="img-overlay"></div>
                        <?php endif; ?>

                        <div class="stock-badge <?= $stok ? 'stock-ready' : 'stock-empty' ?>">
                            <?= $stok ? 'Tersedia' : 'Habis' ?>
                        </div>
                    </div>

                    <div class="menu-content">
                        <div>
                            <h3 class="item-title"><?= esc($item['nama_menu'] ?? '-') ?></h3>

                            <?php if (! empty($item['deskripsi'])): ?>
                                <p class="item-desc"><?= esc($item['deskripsi']) ?></p>
                            <?php else: ?>
                                <p class="item-desc">Menu pilihan untuk meja Anda.</p>
                            <?php endif; ?>
                        </div>

                        <div class="item-footer">
                            <p class="item-price">
                                Rp <?= number_format($item['harga'] ?? 0, 0, ',', '.') ?>
                            </p>

                            <div class="action-container"
                                 data-id="<?= esc($itemId) ?>"
                                 data-nama="<?= esc($item['nama_menu'] ?? '-') ?>"
                                 data-harga="<?= esc((int) ($item['harga'] ?? 0)) ?>"
                                 data-stok="<?= esc($stok) ?>">
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endforeach; ?>

        <?php if (! $hasMenu): ?>
            <div class="empty-state">
                <i class="fa-solid fa-bowl-food"></i>
                <p>Belum ada menu tersedia</p>
                <span>Silakan hubungi kasir atau coba beberapa saat lagi.</span>
            </div>
        <?php endif; ?>

        <div id="search-empty" class="empty-state" style="display: none;">
            <i class="fa-solid fa-magnifying-glass"></i>
            <p>Menu tidak ditemukan</p>
            <span>Coba cari dengan kata kunci lain (contoh: ayam, rendang, sambal).</span>
        </div>
    </main>

</div>

<!-- High-Conversion Floating Cart Bar -->
<div id="floating-cart-bar" class="floating-cart-bar" onclick="goCheckout()">
    <div class="floating-cart-left">
        <div class="floating-cart-icon-box">
            <i class="fa-solid fa-bag-shopping"></i>
            <span id="floating-cart-badge" class="floating-cart-badge">0</span>
        </div>
        <div class="floating-cart-info">
            <span id="floating-cart-count" class="floating-cart-count">0 Porsi</span>
            <strong id="floating-cart-total" class="floating-cart-total">Rp 0</strong>
        </div>
    </div>
    <button type="button" class="floating-cart-btn">
        <span>Lanjut Pesan</span>
        <i class="fa-solid fa-arrow-right"></i>
    </button>
</div>

<div id="bottom-nav" class="bottom-nav">
    <button class="nav-item active">
        <div class="nav-icon">
            <i class="fa-solid fa-border-all"></i>
        </div>
        <span class="nav-label">Menu</span>
    </button>
    <button class="nav-item" onclick="goCheckout()">
        <div class="nav-icon relative">
            <i class="fa-solid fa-cart-shopping"></i>
            <div id="nav-badge" class="nav-badge">0</div>
        </div>
        <span class="nav-label">Keranjang</span>
    </button>
</div>

<div id="toast" class="toast"></div>

<script>
    const KODE_MEJA = <?= json_encode($kodeMeja) ?>;
    const CHECKOUT_URL = <?= json_encode(base_url('order/checkout/' . $kodeMeja)) ?>;

    let cart = JSON.parse(localStorage.getItem('arzana_cart_' + KODE_MEJA) || '[]');

    function rupiah(value) {
        return 'Rp ' + Number(value || 0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function showToast(message) {
        const toast = document.getElementById('toast');

        if (!toast) {
            return;
        }

        toast.textContent = message;
        toast.classList.add('show');

        clearTimeout(window.__toastTimer);
        window.__toastTimer = setTimeout(() => {
            toast.classList.remove('show');
        }, 1600);
    }

    function saveCart() {
        localStorage.setItem('arzana_cart_' + KODE_MEJA, JSON.stringify(cart));
        if (navigator.vibrate) {
            try { navigator.vibrate(15); } catch(e) {}
        }
        updateAllUI();
    }

    function getMenuData(id) {
        const container = document.querySelector(`.action-container[data-id="${id}"]`);

        if (!container) {
            return null;
        }

        return {
            id: parseInt(container.dataset.id),
            nama_menu: container.dataset.nama,
            harga: parseInt(container.dataset.harga || '0'),
            stok: parseInt(container.dataset.stok || '0')
        };
    }

    function addToCart(id) {
        const data = getMenuData(id);

        if (!data || !data.stok) {
            return;
        }

        const existing = cart.find(i => i.id === data.id);

        if (existing) {
            existing.qty++;
        } else {
            cart.push({
                id: data.id,
                nama_menu: data.nama_menu,
                harga: data.harga,
                qty: 1,
                catatan: ''
            });
        }

        saveCart();
        showToast(data.nama_menu + ' ditambahkan ke keranjang.');
    }

    function increaseQty(id) {
        const item = cart.find(i => i.id === parseInt(id));

        if (item) {
            item.qty++;
            saveCart();
        }
    }

    function decreaseQty(id) {
        const idx = cart.findIndex(i => i.id === parseInt(id));

        if (idx > -1) {
            if (cart[idx].qty > 1) {
                cart[idx].qty--;
            } else {
                cart.splice(idx, 1);
            }

            saveCart();
        }
    }

    function updateAllUI() {
        document.querySelectorAll('.action-container').forEach(container => {
            const id = parseInt(container.dataset.id);
            const stok = parseInt(container.dataset.stok || '0');
            const item = cart.find(i => i.id === id);

            if (!stok) {
                container.innerHTML = `<button disabled class="btn-habis">Habis</button>`;
                return;
            }

            if (item) {
                container.innerHTML = `
                    <button type="button" onclick="event.stopPropagation(); decreaseQty(${id})" class="icon-btn btn-minus" aria-label="Kurangi">
                        <i class="fa-solid fa-minus"></i>
                    </button>
                    <span class="item-qty">${item.qty}</span>
                    <button type="button" onclick="event.stopPropagation(); increaseQty(${id})" class="icon-btn btn-plus" aria-label="Tambah">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                `;
            } else {
                container.innerHTML = `
                    <button type="button" onclick="event.stopPropagation(); addToCart(${id})" class="icon-btn btn-plus" aria-label="Tambah ke keranjang">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                `;
            }
        });

        updateBottomBar();
    }

    function updateBottomBar() {
        const totalQty = cart.reduce((sum, item) => sum + Number(item.qty || 0), 0);
        const subtotal = cart.reduce((sum, item) => sum + (Number(item.harga || 0) * Number(item.qty || 0)), 0);

        const navBadge      = document.getElementById('nav-badge');
        const floatingCart  = document.getElementById('floating-cart-bar');
        const floatingBadge = document.getElementById('floating-cart-badge');
        const floatingCount = document.getElementById('floating-cart-count');
        const floatingTotal = document.getElementById('floating-cart-total');
        const bottomNav     = document.getElementById('bottom-nav');

        if (totalQty > 0) {
            if (navBadge) {
                navBadge.textContent = totalQty;
                navBadge.classList.add('show');
            }
            if (floatingBadge) floatingBadge.textContent = totalQty;
            if (floatingCount) floatingCount.textContent = totalQty + ' Porsi Dipilih';
            if (floatingTotal) floatingTotal.textContent = rupiah(subtotal);

            if (floatingCart) floatingCart.classList.add('active');
            if (bottomNav) bottomNav.style.transform = 'translateX(-50%) translateY(160%)';
        } else {
            if (navBadge) navBadge.classList.remove('show');
            if (floatingCart) floatingCart.classList.remove('active');
            if (bottomNav) bottomNav.style.transform = 'translateX(-50%) translateY(0)';
        }
    }

    // Real-Time Menu Search & Category Filter
    const searchInput = document.getElementById('menu-search');
    const searchClear = document.getElementById('search-clear');
    const searchEmpty = document.getElementById('search-empty');
    let currentKategori = 'semua';

    function applyFilters() {
        const query = (searchInput ? searchInput.value : '').trim().toLowerCase();
        let visibleCount = 0;

        if (searchClear) {
            searchClear.style.display = query.length > 0 ? 'inline-flex' : 'none';
        }

        document.querySelectorAll('.menu-item').forEach(item => {
            const kategoriId = item.dataset.kategori;
            const matchesKategori = (currentKategori === 'semua' || kategoriId === currentKategori);

            const title = (item.querySelector('.item-title')?.textContent || '').toLowerCase();
            const desc  = (item.querySelector('.item-desc')?.textContent || '').toLowerCase();
            const matchesSearch = query === '' || title.includes(query) || desc.includes(query);

            if (matchesKategori && matchesSearch) {
                item.style.display = 'flex';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        if (searchEmpty) {
            searchEmpty.style.display = visibleCount === 0 ? 'block' : 'none';
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', applyFilters);
    }

    if (searchClear) {
        searchClear.addEventListener('click', () => {
            searchInput.value = '';
            applyFilters();
            searchInput.focus();
        });
    }

    function filterKategori(id) {
        currentKategori = id;
        document.querySelectorAll('.category-pill').forEach(el => {
            el.classList.remove('active');
        });

        const btnId = id === 'semua' ? 'btn-kat-semua' : 'btn-kat-' + id;
        const activeBtn = document.getElementById(btnId);

        if (activeBtn) {
            activeBtn.classList.add('active');
        }

        applyFilters();
    }

    function goCheckout() {
        if (cart.length === 0) {
            return;
        }

        window.location.href = CHECKOUT_URL;
    }

    document.querySelectorAll('.category-pill').forEach(button => {
        button.addEventListener('click', () => {
            filterKategori(button.dataset.filter);
        });
    });

    updateAllUI();
</script>

</body>
</html>