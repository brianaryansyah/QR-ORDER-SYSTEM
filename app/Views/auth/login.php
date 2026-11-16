<?php
helper('app_setting');
$storeName        = app_setting('store_name', 'NasPad Zendry');
$storeLogo        = app_setting('store_logo', '');
$storeFavicon     = app_setting('store_favicon', '');
$storeDescription = app_setting('store_description', 'Warung Nasi Padang Asli — Pesan via QR');
$footerText       = app_setting('footer_text', '© 2026 NasPad Zendry — Dimasak dengan hati');
$logoSrc = ! empty($storeLogo) ? base_url($storeLogo) : '';
$faviconPath = ! empty($storeFavicon) ? $storeFavicon : (! empty($storeLogo) ? $storeLogo : '');
$faviconSrc = ! empty($faviconPath) ? base_url($faviconPath) : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — <?= esc($storeName) ?></title>
    <?php if (! empty($faviconSrc)): ?>
    <link rel="icon" type="image/png" href="<?= esc($faviconSrc) ?>">
    <?php endif; ?>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Instrument+Sans:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *{margin:0;padding:0;box-sizing:border-box;font-family:'Plus Jakarta Sans',sans-serif}
        body{min-height:100vh;background:#FFF7ED;color:#000000;display:flex;align-items:center;justify-content:center;padding:24px 16px}
        body::before{content:'';position:fixed;inset:0;pointer-events:none;opacity:.4;background-image:radial-gradient(rgba(124,45,18,.06) 1px, transparent 1px);background-size:22px 22px}
        .login-wrapper{width:100%;max-width:430px;position:relative;z-index:1}
        .brand{display:flex;align-items:center;justify-content:center;gap:13px;margin-bottom:24px}
        .brand-logo{width:58px;height:58px;border-radius:22px;background:#EA580C;display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0;box-shadow:0 8px 24px rgba(138,37,18,.18)}
        .brand-logo img{width:48px;height:48px;object-fit:contain}
        .logo-fallback{display:none;width:48px;height:48px;border-radius:18px;background:#EA580C;align-items:center;justify-content:center;color:white}
        .brand-info h1{font-size:22px;font-weight:900;color:#000000;letter-spacing:-.03em;line-height:1.15;font-family:'Instrument Sans',sans-serif}
        .brand-info p{font-size:12px;color:#57534E;font-weight:600;margin-top:4px}
        .card{position:relative;overflow:hidden;background:#FFFFFF;border:1px solid #FED7AA;border-radius:28px;padding:28px;box-shadow:0 12px 40px rgba(62,35,20,.08)}
        .badge{display:inline-flex;align-items:center;gap:7px;padding:7px 11px;border-radius:999px;background:#FFF7ED;border:1px solid #FED7AA;color:#9A3412;font-size:11px;font-weight:800;margin-bottom:16px}
        .badge-dot{width:7px;height:7px;border-radius:999px;background:#16a34a;box-shadow:0 0 0 5px rgba(34,197,94,.15)}
        .card-header-text h2{font-size:25px;line-height:1.2;font-weight:900;letter-spacing:-.04em;color:#000000}
        .card-header-text p{font-size:13px;color:#44403C;font-weight:500;margin-top:8px;line-height:1.55}
        .alert{display:flex;align-items:flex-start;gap:10px;background:#FEF2F2;border:1px solid #FECACA;color:#991B1B;border-radius:16px;padding:13px 14px;font-size:13px;font-weight:700;line-height:1.5;margin-bottom:20px}
        .card form{margin-top:20px}
        .form-group{margin-bottom:18px}
        .form-group label{display:block;font-size:11px;font-weight:800;color:#1C1917;text-transform:uppercase;letter-spacing:.1em;margin-bottom:9px}
        .input-wrap{position:relative}
        .input-icon{position:absolute;left:15px;top:50%;transform:translateY(-50%);font-size:14px;color:#78716C;pointer-events:none}
        .input-wrap input{width:100%;background:#FFFFFF;border:1.5px solid #E7E5E4;color:#000000;border-radius:16px;padding:14px 45px 14px 43px;font-size:14px;font-weight:600;outline:none;transition:.2s}
        .input-wrap input::placeholder{color:#A8A29E}
        .input-wrap input:focus{border-color:#EA580C;box-shadow:0 0 0 4px rgba(138,37,18,.1)}
        .toggle-pw{position:absolute;right:9px;top:50%;transform:translateY(-50%);width:34px;height:34px;display:inline-flex;align-items:center;justify-content:center;border:0;border-radius:10px;background:transparent;font-size:14px;color:#78716C;cursor:pointer}
        .toggle-pw:hover{color:#000000;background:#FFF7ED}
        .btn-submit{width:100%;border:0;border-radius:16px;background:#EA580C;color:#fff;padding:15px 16px;font-size:14px;font-weight:800;display:flex;justify-content:center;align-items:center;gap:10px;cursor:pointer;box-shadow:0 10px 28px rgba(138,37,18,.22);transition:transform .15s}
        .btn-submit:hover{background:#C2410C}
        .btn-submit:active{transform:scale(.98)}
        .security-note{margin-top:18px;display:flex;align-items:flex-start;gap:10px;padding:13px 14px;border-radius:16px;background:#FFF7ED;border:1px solid #FDE68A}
        .security-note i{color:#D97706;margin-top:2px}
        .security-note p{font-size:12px;line-height:1.55;color:#44403C;font-weight:500}
        .login-footer{text-align:center;margin-top:22px;font-size:12px;color:#57534E;font-weight:500;line-height:1.6}
        .login-footer strong{color:#000000}
        :where(button,a,input):focus-visible{outline:3px solid rgba(234,88,12,.35);outline-offset:2px}
        @media(max-width:420px){.card{padding:24px 20px;border-radius:24px}}
        @media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.01ms !important;animation-iteration-count:1 !important;transition-duration:.01ms !important;scroll-behavior:auto !important}}
    </style>
</head>
<body>
<div class="login-wrapper">
    <div class="brand">
        <div class="brand-info" style="text-align:center">
            <h1><?= esc($storeName) ?></h1>
            <p><?= esc($storeDescription ?: 'Warung Nasi Padang Asli') ?></p>
        </div>
    </div>
    <div class="card">
        <div class="badge"><span class="badge-dot"></span> Area Staf</div>
        <div class="card-header-text">
            <h2>Masuk ke Akun</h2>
            <p>Silakan login menggunakan username dan password yang sudah terdaftar.</p>
        </div>
        <?php if (session()->getFlashdata('msg')): ?>
            <div class="alert" role="alert"><i class="fa-solid fa-circle-exclamation"></i><span><?= esc(session()->getFlashdata('msg')) ?></span></div>
        <?php endif; ?>
        <form action="<?= base_url('auth/login') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="form-group">
                <label for="username">Username</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-user input-icon"></i>
                    <input type="text" id="username" name="username" value="<?= esc(old('username')) ?>" placeholder="Masukkan username" required autofocus autocomplete="username">
                </div>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-lock input-icon"></i>
                    <input type="password" id="password" name="password" placeholder="Masukkan password" required autocomplete="current-password">
                    <button type="button" class="toggle-pw" id="togglePw" aria-label="Tampilkan password" aria-pressed="false">
                        <i class="fa-solid fa-eye" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
            <button type="submit" class="btn-submit"><i class="fa-solid fa-arrow-right-to-bracket"></i> Masuk ke Dashboard</button>
        </form>
        <div class="security-note"><i class="fa-solid fa-shield-halved"></i><p>Halaman ini hanya untuk admin dan kasir yang memiliki akses resmi.</p></div>
    </div>
    <p class="login-footer">&copy; <?= date('Y') ?> <strong><?= esc($storeName) ?></strong>. Hak cipta dilindungi.<br><?= esc($footerText) ?></p>
</div>
<script>
    const togglePw=document.getElementById('togglePw'), pwInput=document.getElementById('password');
    if(togglePw&&pwInput){
        togglePw.addEventListener('click',()=>{
            const show=pwInput.type==='password';
            pwInput.type=show?'text':'password';
            togglePw.setAttribute('aria-pressed',show?'true':'false');
            togglePw.setAttribute('aria-label',show?'Sembunyikan password':'Tampilkan password');
            const icon=togglePw.querySelector('i');
            if(icon){ icon.classList.toggle('fa-eye',!show); icon.classList.toggle('fa-eye-slash',show); }
        });
    }
</script>
</body>
</html>
