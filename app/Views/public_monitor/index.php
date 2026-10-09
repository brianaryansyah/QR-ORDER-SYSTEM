<?php
helper('app_setting');

$storeName = app_setting('store_name', 'NasPad Zendry');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Monitor Pesanan — <?= esc($storeName) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root {
            --bg: #FFF8F5;
            --panel: #FFFFFF;
            --panel-soft: #FFF8F5;
            --white: #ffffff;
            --text: #1C1C1C;
            --muted: #6B7280;
            --primary: #FF5722;
            --primary-dark: #E64A19;
            --primary-light: #FF8A65;
            --yellow: #FBBF24;
            --green: #10b981;
            --red: #f43f5e;
            --border: #FFDCCF;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        html, body {
            width: 100%;
            min-height: 100%;
            background: var(--bg);
            color: var(--text);
        }

        body {
            min-height: 100vh;
            overflow-x: hidden;
            background:
                radial-gradient(circle at top right, rgba(255, 87, 34, 0.08), transparent 32rem),
                radial-gradient(circle at bottom left, rgba(251, 191, 36, 0.08), transparent 30rem),
                var(--bg);
        }

        button {
            font: inherit;
        }

        /* =====================================================
           PAGE
        ===================================================== */

        .monitor-page {
            width: 100%;
            min-height: 100vh;
            padding: 16px;
        }

        .monitor-shell {
            width: min(1500px, 100%);
            margin: 0 auto;
        }

        /* =====================================================
           HEADER
        ===================================================== */

        .monitor-header {
            min-height: 54px;
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
            gap: 16px;
            padding: 0 4px 12px;
        }

        .brand {
            display: flex;
            align-items: center;
        }

        .brand-name {
            font-size: 14px;
            font-weight: 900;
            letter-spacing: -.02em;
        }

        .brand-subtitle {
            margin-top: 2px;
            color: var(--muted);
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .13em;
        }

        .monitor-title {
            text-align: center;
        }

        .monitor-title h1 {
            font-size: clamp(14px, 1.5vw, 20px);
            font-weight: 900;
            letter-spacing: -.03em;
        }

        .monitor-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
        }

        .clock {
            min-width: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 9px 12px;
            border-radius: 9px;
            border: 1px solid var(--border);
            background: var(--panel);
            color: var(--text);
            font-size: 12px;
            font-weight: 800;
        }

        .fullscreen-button {
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            border: 1px solid var(--border);
            border-radius: 9px;
            background: var(--panel);
            color: var(--text);
            cursor: pointer;
            transition: .2s ease;
        }

        .fullscreen-button:hover {
            background: rgba(255, 87, 34, 0.1);
            color: var(--primary);
            border-color: rgba(255, 87, 34, 0.3);
            transform: translateY(-1px);
        }

        /* =====================================================
           TOP MONITOR
        ===================================================== */

        .top-monitor {
            display: grid;
            grid-template-columns: 250px minmax(0, 1fr);
            gap: 12px;
            height: 260px;
        }

        .left-monitor-column {
            display: grid;
            grid-template-rows: 58px 1fr;
            gap: 10px;
        }

        .processing-count {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--panel);
        }

        .processing-icon {
            color: var(--primary);
            font-size: 24px;
        }

        .processing-number {
            font-size: 30px;
            line-height: 1;
            font-weight: 900;
            color: var(--text);
        }

        .processing-text {
            color: var(--muted);
            font-size: 10px;
            line-height: 1.3;
            font-weight: 900;
            text-transform: uppercase;
        }

        /* =====================================================
           MAIN CALL CARD
        ===================================================== */

        .main-call-card {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border-radius: 8px;
            border: 2px solid rgba(255, 87, 34, 0.45);
            background: linear-gradient(145deg, var(--primary), var(--primary-dark));
            color: #FFFFFF;
            box-shadow:
                inset 0 1px 0 rgba(255, 255, 255, .5),
                0 10px 30px rgba(255, 87, 34, .14);
        }

        .main-call-card:not(.empty-call) {
            animation: primaryBlink 1.3s ease-in-out infinite;
        }

        .main-call-card::before {
            content: '';
            position: absolute;
            top: -80px;
            right: -60px;
            width: 170px;
            height: 170px;
            border-radius: 999px;
            background: rgba(255, 255, 255, .24);
            filter: blur(20px);
        }

        .main-call-card.empty-call {
            background: #FFF8F5;
            border: 1px dashed var(--border);
            color: var(--muted);
            animation: none;
            box-shadow: none;
        }

        .main-call-label {
            position: relative;
            z-index: 1;
            margin-bottom: 8px;
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .15em;
        }

        .main-call-number {
            position: relative;
            z-index: 1;
            font-size: clamp(48px, 7vw, 86px);
            line-height: .9;
            font-weight: 900;
            letter-spacing: -.07em;
        }

        .main-call-status {
            position: relative;
            z-index: 1;
            margin-top: 13px;
            font-size: 14px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .06em;
        }

        .main-call-meja {
            position: relative;
            z-index: 1;
            margin-top: 7px;
            font-size: 10px;
            font-weight: 800;
            opacity: .7;
        }

        /* =====================================================
           BANNER
        ===================================================== */

        .banner {
            position: relative;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        }

        .banner-grid {
            position: absolute;
            inset: 0;
            opacity: .12;
            background-image:
                linear-gradient(rgba(255, 255, 255, .5) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, .5) 1px, transparent 1px);
            background-size: 42px 42px;
        }

        .banner-glow {
            position: absolute;
            width: 350px;
            height: 350px;
            border-radius: 999px;
            background: rgba(255, 255, 255, .25);
            filter: blur(90px);
        }

        .banner-content {
            position: relative;
            z-index: 2;
            max-width: 850px;
            padding: 30px;
            text-align: center;
        }

        .banner-store {
            color: #FFFFFF;
            font-size: clamp(36px, 6vw, 86px);
            line-height: .95;
            font-weight: 900;
            letter-spacing: -.07em;
            text-transform: uppercase;
            text-shadow: 0 4px 0 rgba(0, 0, 0, .15);
        }

        .banner-text {
            margin-top: 15px;
            color: rgba(255, 255, 255, .9);
            font-size: clamp(12px, 1.6vw, 20px);
            font-weight: 800;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        /* =====================================================
           READY HEADER
        ===================================================== */

        .ready-section {
            margin-top: 12px;
        }

        .ready-header {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 10px;
            padding: 2px 2px 9px;
        }

        .ready-header-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .ready-title {
            font-size: clamp(18px, 2vw, 28px);
            line-height: 1;
            font-weight: 900;
            letter-spacing: -.045em;
            text-transform: uppercase;
            color: var(--text);
        }

        .ready-count {
            min-width: 31px;
            height: 31px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 9px;
            border-radius: 999px;
            background: var(--green);
            color: white;
            font-size: 12px;
            font-weight: 900;
        }

        /* =====================================================
           READY GRID
        ===================================================== */

        .ready-grid {
            display: grid;
            grid-template-columns: repeat(6, minmax(0, 1fr));
            gap: 7px;
        }

        .ready-card {
            position: relative;
            min-height: 96px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: 7px;
            background: var(--panel);
            color: var(--text);
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        }

        .ready-card.latest {
            border-color: rgba(255, 87, 34, 0.45);
            background: linear-gradient(145deg, var(--primary), var(--primary-dark));
            color: #FFFFFF;
            box-shadow:
                inset 0 1px 0 rgba(255, 255, 255, .4),
                0 0 24px rgba(255, 87, 34, .25);
            animation: primaryBlink 1.3s ease-in-out infinite;
        }

        .ready-number {
            font-size: clamp(25px, 3.2vw, 44px);
            line-height: 1;
            font-weight: 900;
            letter-spacing: -.06em;
        }

        .ready-meja {
            margin-top: 6px;
            font-size: 9px;
            font-weight: 800;
            opacity: .72;
            text-transform: uppercase;
        }

        .ready-empty {
            grid-column: 1 / -1;
            min-height: 190px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 1px dashed var(--border);
            border-radius: 8px;
            background: #FFF8F5;
            color: var(--muted);
            text-align: center;
        }

        .ready-empty i {
            margin-bottom: 10px;
            font-size: 28px;
            color: var(--primary-light);
        }

        .ready-empty strong {
            color: #94a3b8;
            font-size: 13px;
        }

        .ready-empty span {
            margin-top: 4px;
            font-size: 10px;
        }

        /* =====================================================
           CALLING OVERLAY
        ===================================================== */

        .calling-overlay {
            position: fixed;
            inset: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            visibility: hidden;
            opacity: 0;
            pointer-events: none;
            background: rgba(0, 0, 0, .88);
            backdrop-filter: blur(14px);
            transition: visibility .3s ease, opacity .3s ease;
        }

        .calling-overlay.show {
            visibility: visible;
            opacity: 1;
        }

        .calling-box {
            width: min(620px, 100%);
            padding: 36px;
            overflow: hidden;
            border: 2px solid rgba(255, 87, 34, 0.45);
            border-radius: 22px;
            background:
                radial-gradient(circle at top right, rgba(255, 87, 34, 0.15), transparent 17rem),
                #FFFFFF;
            text-align: center;
            transform: scale(.92);
            transition: transform .3s ease;
        }

        .calling-overlay.show .calling-box {
            transform: scale(1);
        }

        .calling-speaker {
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 18px;
            border-radius: 999px;
            background: rgba(255, 87, 34, 0.15);
            color: var(--primary);
            font-size: 28px;
            animation: speakerPulsePrimary 1s infinite;
        }

        .calling-label {
            color: #94a3b8;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .16em;
        }

        .calling-number {
            margin-top: 9px;
            color: var(--primary);
            font-size: clamp(70px, 12vw, 130px);
            line-height: .9;
            font-weight: 900;
            letter-spacing: -.08em;
        }

        .calling-status {
            margin-top: 18px;
            font-size: clamp(18px, 3vw, 30px);
            font-weight: 900;
        }

        .calling-meja {
            margin-top: 9px;
            color: #94a3b8;
            font-size: 13px;
            font-weight: 700;
        }

        /* =====================================================
           CONNECTION
        ===================================================== */

        .connection-status {
            position: fixed;
            right: 14px;
            bottom: 14px;
            z-index: 20;
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 8px 11px;
            border: 1px solid var(--border);
            border-radius: 999px;
            background: rgba(3, 7, 18, .85);
            backdrop-filter: blur(10px);
            color: #94a3b8;
            font-size: 9px;
            font-weight: 800;
        }

        .connection-dot {
            width: 7px;
            height: 7px;
            border-radius: 999px;
            background: #22c55e;
            box-shadow: 0 0 0 4px rgba(34, 197, 94, .1);
        }

        .connection-status.error .connection-dot {
            background: var(--red);
            box-shadow: 0 0 0 4px rgba(244, 63, 94, .1);
        }

        /* =====================================================
           ANIMATIONS
        ===================================================== */

        @keyframes primaryBlink {
            0%, 100% {
                filter: brightness(1);
                box-shadow:
                    inset 0 1px 0 rgba(255, 255, 255, .4),
                    0 0 12px rgba(255, 87, 34, .15);
            }

            50% {
                filter: brightness(1.2);
                box-shadow:
                    inset 0 1px 0 rgba(255, 255, 255, .5),
                    0 0 35px rgba(255, 87, 34, .45);
            }
        }

        @keyframes speakerPulsePrimary {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(255, 87, 34, .25);
            }

            50% {
                transform: scale(1.08);
                box-shadow: 0 0 0 18px rgba(255, 87, 34, 0);
            }
        }

        /* =====================================================
           RESPONSIVE
        ===================================================== */

        @media (min-width: 1500px) {
            .monitor-page {
                padding: 20px;
            }

            .top-monitor {
                grid-template-columns: 300px minmax(0, 1fr);
                height: 320px;
            }

            .ready-card {
                min-height: 120px;
            }
        }

        @media (max-width: 1000px) {
            .top-monitor {
                grid-template-columns: 210px minmax(0, 1fr);
                height: 230px;
            }

            .ready-grid {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }

        @media (max-width: 720px) {
            .monitor-page {
                padding: 10px;
            }

            .monitor-header {
                grid-template-columns: 1fr auto;
            }

            .monitor-title {
                display: none;
            }

            .clock {
                min-width: auto;
                padding: 9px;
            }

            .top-monitor {
                height: auto;
                grid-template-columns: 1fr;
            }

            .left-monitor-column {
                grid-template-columns: 130px 1fr;
                grid-template-rows: 155px;
            }

            .processing-count {
                flex-direction: column;
                justify-content: center;
                text-align: center;
            }

            .processing-number {
                font-size: 36px;
            }

            .main-call-card {
                min-height: 155px;
            }

            .banner {
                min-height: 180px;
            }

            .ready-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }

            .ready-card {
                min-height: 78px;
            }
        }

        @media (max-width: 430px) {
            .brand-subtitle {
                display: none;
            }

            .brand-name {
                font-size: 12px;
            }

            .ready-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .ready-title {
                font-size: 16px;
            }
        }
    </style>
</head>

<body>

<div class="monitor-page">
    <div class="monitor-shell">

        <header class="monitor-header">
            <div class="brand">
                <div>
                    <div class="brand-name"><?= esc($storeName) ?></div>
                    <div class="brand-subtitle">Live Order Monitor</div>
                </div>
            </div>

            <div class="monitor-title">
                <h1>Monitor Pesanan</h1>
            </div>

            <div class="monitor-actions">
                <div class="clock">
                    <i class="fa-regular fa-clock"></i>
                    <span id="clock">--:--:--</span>
                </div>

                <button type="button" class="fullscreen-button" onclick="toggleFullscreen()" title="Layar Penuh">
                    <i class="fa-solid fa-expand"></i>
                </button>
            </div>
        </header>

        <section class="top-monitor">
            <div class="left-monitor-column">

                <div class="processing-count">
                    <i class="fa-solid fa-kitchen-set processing-icon"></i>
                    <div class="processing-number" id="processing-count">0</div>

                    <div class="processing-text">
                        Pesanan<br>Lagi Diproses
                    </div>
                </div>

                <div class="main-call-card empty-call" id="main-call-card">
                    <div class="main-call-label">Nomor Antrian</div>
                    <div class="main-call-number" id="main-call-number">---</div>
                    <div class="main-call-status" id="main-call-status">Belum Ada</div>
                    <div class="main-call-meja" id="main-call-meja"></div>
                </div>

            </div>

            <div class="banner">
                <div class="banner-grid"></div>
                <div class="banner-glow"></div>

                <div class="banner-content">
                    <div class="banner-store"><?= esc($storeName) ?></div>
                    <div class="banner-text">Pesan • Tunggu • Nikmati</div>
                </div>
            </div>
        </section>

        <section class="ready-section">
            <div class="ready-header">
                <div class="ready-header-left">
                    <h2 class="ready-title">Sudah Siap</h2>
                    <div class="ready-count" id="ready-count">0</div>
                </div>
            </div>

            <div class="ready-grid" id="ready-grid">
                <div class="ready-empty">
                    <i class="fa-solid fa-bell-concierge"></i>
                    <strong>Belum ada pesanan yang siap</strong>
                    <span>Nomor antrian akan muncul otomatis</span>
                </div>
            </div>
        </section>

    </div>
</div>

<div class="calling-overlay" id="calling-overlay">
    <div class="calling-box">
        <div class="calling-speaker">
            <i class="fa-solid fa-volume-high"></i>
        </div>

        <div class="calling-label">Pesanan Sudah Siap</div>
        <div class="calling-number" id="calling-number">A001</div>
        <div class="calling-status">Siap Diantarkan</div>
        <div class="calling-meja" id="calling-meja"></div>
    </div>
</div>

<div class="connection-status" id="connection-status">
    <span class="connection-dot"></span>
    <span id="connection-text">Terhubung</span>
</div>

<script>
    const DATA_URL = <?= json_encode(base_url('monitor/data')) ?>;
    const POLLING_INTERVAL = 3000;

    let hasInitialLoad = false;
    let knownReadyIds = [];
    let speechQueue = [];
    let queuedSpeechIds = [];
    let isSpeaking = false;
    let isLoadingMonitor = false;
    let latestReadyId = null;

    function updateClock() {
        const clock = document.getElementById('clock');

        if (!clock) {
            return;
        }

        clock.textContent = new Date().toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
    }

    updateClock();
    setInterval(updateClock, 1000);

    function escapeHTML(value) {
        return String(value ?? '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    function formatNomorAntrian(order) {
        const nomor = String(order?.nomor_antrian ?? '').trim();

        if (nomor !== '') {
            return nomor;
        }

        return '-';
    }

    function formatNomorMeja(order) {
        const meja = String(order?.nomor_meja ?? '').trim();

        if (meja !== '') {
            return meja;
        }

        return '-';
    }

    function toggleFullscreen() {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen().catch(function (error) {
                console.error('Fullscreen gagal:', error);
            });

            return;
        }

        document.exitFullscreen().catch(function (error) {
            console.error('Keluar fullscreen gagal:', error);
        });
    }

    function getIndonesianVoice() {
        if (!('speechSynthesis' in window)) {
            return null;
        }

        const voices = window.speechSynthesis.getVoices();

        return voices.find(function (voice) {
            return String(voice.lang || '').toLowerCase().startsWith('id');
        }) || null;
    }

    function speakDirect(text, onEnd = null) {
        if (!('speechSynthesis' in window)) {
            console.error('Browser tidak mendukung Speech Synthesis.');

            if (typeof onEnd === 'function') {
                onEnd();
            }

            return;
        }

        try {
            const utterance = new SpeechSynthesisUtterance(text);
            const voice = getIndonesianVoice();

            utterance.lang = 'id-ID';
            utterance.rate = 0.9;
            utterance.pitch = 1;
            utterance.volume = 1;

            if (voice) {
                utterance.voice = voice;
            }

            utterance.onend = function () {
                if (typeof onEnd === 'function') {
                    onEnd();
                }
            };

            utterance.onerror = function (event) {
                console.error('TTS error:', event);

                if (typeof onEnd === 'function') {
                    onEnd();
                }
            };

            window.speechSynthesis.resume();
            window.speechSynthesis.speak(utterance);

        } catch (error) {
            console.error('TTS gagal:', error);

            if (typeof onEnd === 'function') {
                onEnd();
            }
        }
    }

    function queueSpeech(order) {
        if (!order || !order.id) {
            return;
        }

        const orderId = Number(order.id);

        if (queuedSpeechIds.includes(orderId)) {
            return;
        }

        queuedSpeechIds.push(orderId);
        speechQueue.push(order);

        processSpeechQueue();
    }

    function processSpeechQueue() {
        if (isSpeaking || speechQueue.length === 0) {
            return;
        }

        isSpeaking = true;

        const order = speechQueue.shift();
        const orderId = Number(order.id);
        const nomorAntrian = formatNomorAntrian(order);

        showCallingOverlay(order);

        const sentence = `Pesanan ${nomorAntrian} siap diantarkan.`;

        speakDirect(sentence, function () {
            setTimeout(function () {
                hideCallingOverlay();

                queuedSpeechIds = queuedSpeechIds.filter(function (id) {
                    return id !== orderId;
                });

                isSpeaking = false;

                setTimeout(processSpeechQueue, 500);
            }, 1000);
        });
    }

    function showCallingOverlay(order) {
        const overlay = document.getElementById('calling-overlay');
        const number = document.getElementById('calling-number');
        const meja = document.getElementById('calling-meja');

        if (!overlay || !number || !meja) {
            return;
        }

        number.textContent = formatNomorAntrian(order);
        meja.textContent = `Meja ${formatNomorMeja(order)}`;

        overlay.classList.add('show');
    }

    function hideCallingOverlay() {
        const overlay = document.getElementById('calling-overlay');

        if (overlay) {
            overlay.classList.remove('show');
        }
    }

    function renderMainCall(order) {
        const card = document.getElementById('main-call-card');
        const number = document.getElementById('main-call-number');
        const status = document.getElementById('main-call-status');
        const meja = document.getElementById('main-call-meja');

        if (!card || !number || !status || !meja) {
            return;
        }

        if (!order) {
            card.classList.add('empty-call');
            number.textContent = '---';
            status.textContent = 'Belum Ada';
            meja.textContent = '';
            return;
        }

        card.classList.remove('empty-call');
        number.textContent = formatNomorAntrian(order);
        status.textContent = 'Sudah Siap';
        meja.textContent = `Meja ${formatNomorMeja(order)}`;
    }

    function renderReadyGrid(orders) {
        const grid = document.getElementById('ready-grid');
        const counter = document.getElementById('ready-count');

        if (!grid || !counter) {
            return;
        }

        counter.textContent = orders.length;

        if (orders.length === 0) {
            grid.innerHTML = `
                <div class="ready-empty">
                    <i class="fa-solid fa-bell-concierge"></i>
                    <strong>Belum ada pesanan yang siap</strong>
                    <span>Nomor antrian akan muncul otomatis</span>
                </div>
            `;

            return;
        }

        grid.innerHTML = orders.map(function (order) {
            const isLatest = Number(order.id) === Number(latestReadyId);
            const nomorAntrian = formatNomorAntrian(order);
            const nomorMeja = formatNomorMeja(order);

            return `
                <div class="ready-card ${isLatest ? 'latest' : ''}" data-order-id="${escapeHTML(order.id)}">
                    <div class="ready-number">${escapeHTML(nomorAntrian)}</div>
                    <div class="ready-meja">Meja ${escapeHTML(nomorMeja)}</div>
                </div>
            `;
        }).join('');
    }

    function setConnectionState(connected, text = null) {
        const element = document.getElementById('connection-status');
        const label = document.getElementById('connection-text');

        if (!element || !label) {
            return;
        }

        if (connected) {
            element.classList.remove('error');
            label.textContent = text || 'Terhubung';
            return;
        }

        element.classList.add('error');
        label.textContent = text || 'Koneksi Terputus';
    }

    function handleNewReadyOrders(readyOrders) {
        const currentIds = readyOrders.map(function (order) {
            return Number(order.id);
        });

        /*
         * Saat monitor pertama kali dibuka atau direfresh,
         * semua order siap yang sudah ada hanya dijadikan baseline.
         * Jadi tidak langsung dipanggil ulang.
         */
        if (!hasInitialLoad) {
            knownReadyIds = currentIds;
            hasInitialLoad = true;
            return;
        }

        const newOrders = readyOrders.filter(function (order) {
            return !knownReadyIds.includes(Number(order.id));
        });

        newOrders.sort(function (a, b) {
            return Number(a.id) - Number(b.id);
        });

        newOrders.forEach(function (order) {
            latestReadyId = Number(order.id);
            queueSpeech(order);
        });

        knownReadyIds = currentIds;
    }

    async function loadMonitor() {
        if (isLoadingMonitor) {
            return;
        }

        isLoadingMonitor = true;

        try {
            const response = await fetch(DATA_URL + '?t=' + Date.now(), {
                method: 'GET',
                cache: 'no-store',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error(`Server merespons dengan status ${response.status}`);
            }

            const json = await response.json();

            if (!json.success) {
                throw new Error('Data monitor tidak valid');
            }

            const data = Array.isArray(json.data) ? json.data : [];

            const waitingOrders = data.filter(function (item) {
                return item.tone !== 'ready';
            });

            let readyOrders = data.filter(function (item) {
                return item.tone === 'ready';
            });

            readyOrders.sort(function (a, b) {
                return Number(b.id) - Number(a.id);
            });

            const newestReady = readyOrders.length > 0 ? readyOrders[0] : null;

            if (latestReadyId === null && newestReady) {
                latestReadyId = Number(newestReady.id);
            }

            const processingCount = document.getElementById('processing-count');

            if (processingCount) {
                processingCount.textContent = waitingOrders.length;
            }

            renderMainCall(newestReady);
            renderReadyGrid(readyOrders.slice(0, 18));
            handleNewReadyOrders(readyOrders);

            setConnectionState(true, 'Terhubung');

        } catch (error) {
            console.error('Gagal memuat monitor:', error);
            setConnectionState(false, 'Koneksi Bermasalah');

        } finally {
            isLoadingMonitor = false;
        }
    }

    /*
     * Persiapkan voice browser sejak awal.
     */
    if ('speechSynthesis' in window) {
        window.speechSynthesis.getVoices();

        window.speechSynthesis.onvoiceschanged = function () {
            window.speechSynthesis.getVoices();
        };
    }

    /*
     * Saat halaman kembali aktif, pastikan engine TTS dilanjutkan.
     */
    document.addEventListener('visibilitychange', function () {
        if (document.visibilityState === 'visible') {
            if ('speechSynthesis' in window) {
                window.speechSynthesis.resume();
            }

            loadMonitor();
        }
    });

    /*
     * Beberapa browser memerlukan interaksi pertama.
     * Tidak ada tombol khusus; klik, sentuh, atau tekan tombol apa pun
     * akan otomatis membuka engine TTS.
     */
    function unlockSpeech() {
        if ('speechSynthesis' in window) {
            window.speechSynthesis.resume();
        }
    }

    document.addEventListener('click', unlockSpeech, { once: true });
    document.addEventListener('touchstart', unlockSpeech, { once: true });
    document.addEventListener('keydown', unlockSpeech, { once: true });

    loadMonitor();
    setInterval(loadMonitor, POLLING_INTERVAL);
</script>

</body>
</html>