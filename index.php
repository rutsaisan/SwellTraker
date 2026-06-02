<?php
session_start();
if (!empty($_SESSION['loggedin'])) {
    header("Location: inicio.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SwellTracker Global — Iniciar sesión</title>
    <meta name="description" content="Accede a SwellTracker Global: condiciones de olas en tiempo real, tutoriales y gamificación." />
    <link rel="icon" href="favicon.png" type="image/png" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet" />
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background-color: #050a14;
            background-image:
                linear-gradient(rgba(5, 10, 20, 0.82), rgba(5, 10, 20, 0.82)),
                radial-gradient(ellipse 80% 50% at 20% 80%, rgba(59,130,246,0.18) 0%, transparent 70%),
                radial-gradient(ellipse 60% 40% at 80% 20%, rgba(250,204,21,0.07) 0%, transparent 50%),
                url('img/oceano.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 24px 16px; color: #f1f5f9; overflow: hidden; position: relative;
        }
        body::before {
            content: ''; position: fixed;
            width: 600px; height: 600px; border-radius: 50%;
            background: radial-gradient(circle, rgba(59,130,246,0.08) 0%, transparent 70%);
            top: -200px; left: -150px;
            animation: drift 18s ease-in-out infinite alternate; pointer-events: none;
        }
        body::after {
            content: ''; position: fixed;
            width: 500px; height: 500px; border-radius: 50%;
            background: radial-gradient(circle, rgba(250,204,21,0.06) 0%, transparent 70%);
            bottom: -150px; right: -100px;
            animation: drift 22s ease-in-out infinite alternate-reverse; pointer-events: none;
        }
        @keyframes drift {
            0%   { transform: translate(0,0) scale(1); }
            100% { transform: translate(60px,40px) scale(1.1); }
        }
        .brand { display:flex; align-items:center; gap:14px; margin-bottom:40px; text-decoration:none; z-index:1; }
        .brand-icon { width:52px; height:52px; border-radius:50%; overflow:hidden; border:1px solid rgba(250,204,21,0.35); box-shadow:0 0 24px rgba(250,204,21,0.08); flex-shrink:0; }
        .brand-icon img { width:100%; height:100%; object-fit:cover; display:block; }
        .brand-name { font-size:1.6rem; font-weight:700; color:#fff; letter-spacing:-0.03em; }
        .brand-name span { color:#facc15; }
        .card { position:relative; z-index:1; width:100%; max-width:420px; background:rgba(10,20,50,0.75); backdrop-filter:blur(20px); -webkit-backdrop-filter:blur(20px); border:1px solid rgba(59,130,246,0.18); border-radius:24px; padding:40px 36px 36px; box-shadow:0 32px 80px rgba(0,0,0,0.55),inset 0 1px 0 rgba(255,255,255,0.05); }
        .card-title { font-size:1.3rem; font-weight:700; color:#fff; margin-bottom:4px; }
        .card-subtitle { font-size:13px; color:rgba(255,255,255,0.42); margin-bottom:28px; }
        .field { margin-bottom:16px; }
        .field label { display:block; font-size:12px; font-weight:600; color:rgba(255,255,255,0.55); letter-spacing:0.6px; text-transform:uppercase; margin-bottom:7px; }
        .input-wrap { position:relative; display:flex; align-items:center; }
        .input-icon { position:absolute; left:13px; font-size:16px; pointer-events:none; opacity:0.55; }
        .field input { width:100%; background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); border-radius:12px; padding:12px 14px 12px 40px; color:#f1f5f9; font-size:14px; font-family:inherit; outline:none; transition:border-color 0.2s,box-shadow 0.2s; }
        .field input::placeholder { color:rgba(255,255,255,0.22); }
        .field input:focus { border-color:rgba(59,130,246,0.55); box-shadow:0 0 0 3px rgba(59,130,246,0.12); background:rgba(255,255,255,0.07); }
        .pwd-toggle { position:absolute; right:13px; background:none; border:none; color:rgba(255,255,255,0.35); cursor:pointer; font-size:14px; padding:4px; transition:color 0.2s; }
        .pwd-toggle:hover { color:rgba(255,255,255,0.7); }
        .btn-primary { width:100%; padding:13px; border:none; border-radius:12px; background:linear-gradient(135deg,#3b82f6,#1d4ed8); color:#fff; font-size:15px; font-weight:700; font-family:inherit; cursor:pointer; transition:all 0.25s; box-shadow:0 4px 20px rgba(59,130,246,0.35); margin-top:8px; }
        .btn-primary:hover { transform:translateY(-1px); box-shadow:0 8px 28px rgba(59,130,246,0.45); }
        .btn-primary:active { transform:translateY(0); }
        .error-msg { font-size:13px; border-radius:10px; padding:11px 14px; margin-bottom:16px; background:rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.3); color:#fca5a5; }
        .register-link { margin-top:24px; text-align:center; font-size:13px; color:rgba(255,255,255,0.4); z-index:1; }
        .register-link a { color:#facc15; text-decoration:none; font-weight:600; }
        .register-link a:hover { color:#fde047; }
        .footer-note { z-index:1; margin-top:20px; font-size:12px; color:rgba(255,255,255,0.2); text-align:center; }
        @media (max-width:480px) { .card { padding:28px 20px 24px; border-radius:20px; } }
    </style>
</head>
<body>

    <a class="brand" href="index.php">
        <div class="brand-icon">
            <img src="Logo_Surf.png" alt="SwellTracker Logo">
        </div>
        <div class="brand-name">Swell<span>Tracker</span></div>
    </a>

    <?php if (isset($_GET['error'])): ?>
        <div class="error-msg" style="position:relative;z-index:1;width:100%;max-width:420px;margin-bottom:16px;">
            <?php
                if ($_GET['error'] == '1') echo '✖ Contraseña incorrecta.';
                else if ($_GET['error'] == '2') echo '✖ El correo no existe o faltan datos.';
            ?>
        </div>
    <?php endif; ?>

    <div class="card" role="main">
        <div class="card-title">Bienvenido de vuelta 🤙</div>
        <div class="card-subtitle">Entra para ver tus spots, progreso y equipo</div>

        <form action="php/login.php" method="POST" id="login-form">
            <div class="field">
                <label for="login-email">Email</label>
                <div class="input-wrap">
                    <span class="input-icon">📧</span>
                    <input type="email" id="login-email" name="email" placeholder="tu@email.com" autocomplete="email" required />
                </div>
            </div>
            <div class="field">
                <label for="login-password">Contraseña</label>
                <div class="input-wrap">
                    <span class="input-icon">🔒</span>
                    <input type="password" id="login-password" name="password" placeholder="••••••••" autocomplete="current-password" required />
                    <button type="button" class="pwd-toggle" onclick="togglePwd('login-password',this)" aria-label="Ver contraseña">👁</button>
                </div>
            </div>
            <button type="submit" class="btn-primary" id="login-btn">Entrar</button>
        </form>
    </div>

    <p class="register-link">¿No tienes cuenta? <a href="registro.php">Crear cuenta</a></p>
    <p class="footer-note">SwellTracker Global &copy; <?= date('Y') ?></p>

    <script>
        function togglePwd(id, btn) {
            const input = document.getElementById(id);
            input.type = input.type === 'text' ? 'password' : 'text';
            btn.textContent = input.type === 'text' ? '🙈' : '👁';
        }
    </script>
</body>
</html>