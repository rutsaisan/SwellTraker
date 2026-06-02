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
    <title>SwellTracker Global — Crear cuenta</title>
    <meta name="description" content="Crea tu cuenta en SwellTracker Global." />
    <link rel="icon" href="favicon.png" type="image/png" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet" />
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family:'Inter',sans-serif; min-height:100vh; background-color:#050a14; background-image:linear-gradient(rgba(5,10,20,0.82), rgba(5,10,20,0.82)), radial-gradient(ellipse 80% 50% at 20% 80%,rgba(59,130,246,0.18) 0%,transparent 60%), radial-gradient(ellipse 60% 40% at 80% 20%,rgba(250,204,21,0.07) 0%,transparent 55%), url('img/oceano.jpg'); background-size: cover; background-position: center; background-attachment: fixed; display:flex; flex-direction:column; align-items:center; justify-content:center; padding:24px 16px; color:#f1f5f9; overflow:hidden; position:relative; }
        body::before { content:''; position:fixed; width:600px; height:600px; border-radius:50%; background:radial-gradient(circle,rgba(59,130,246,0.08) 0%,transparent 70%); top:-200px; left:-150px; animation:drift 18s ease-in-out infinite alternate; pointer-events:none; }
        body::after { content:''; position:fixed; width:500px; height:500px; border-radius:50%; background:radial-gradient(circle,rgba(250,204,21,0.06) 0%,transparent 70%); bottom:-150px; right:-100px; animation:drift 22s ease-in-out infinite alternate-reverse; pointer-events:none; }
        @keyframes drift { 0%{transform:translate(0,0) scale(1)} 100%{transform:translate(60px,40px) scale(1.1)} }
        .brand { display:flex; align-items:center; gap:14px; margin-bottom:40px; text-decoration:none; z-index:1; }
        .brand-icon { width:52px; height:52px; border-radius:50%; overflow:hidden; border:1px solid rgba(250,204,21,0.35); box-shadow:0 0 24px rgba(250,204,21,0.08); flex-shrink:0; }
        .brand-icon img { width:100%; height:100%; object-fit:cover; display:block; }
        .brand-name { font-size:1.6rem; font-weight:700; color:#fff; letter-spacing:-0.03em; }
        .brand-name span { color:#facc15; }
        .card { position:relative; z-index:1; width:100%; max-width:420px; background:rgba(10,20,50,0.75); backdrop-filter:blur(20px); border:1px solid rgba(59,130,246,0.18); border-radius:24px; padding:40px 36px 36px; box-shadow:0 32px 80px rgba(0,0,0,0.55); }
        .card-title { font-size:1.3rem; font-weight:700; color:#fff; margin-bottom:4px; }
        .card-subtitle { font-size:13px; color:rgba(255,255,255,0.42); margin-bottom:28px; }
        .field { margin-bottom:16px; }
        .field label { display:block; font-size:12px; font-weight:600; color:rgba(255,255,255,0.55); letter-spacing:0.6px; text-transform:uppercase; margin-bottom:7px; }
        .input-wrap { position:relative; display:flex; align-items:center; }
        .input-icon { position:absolute; left:13px; font-size:16px; pointer-events:none; opacity:0.55; }
        .field input { width:100%; background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); border-radius:12px; padding:12px 14px 12px 40px; color:#f1f5f9; font-size:14px; font-family:inherit; outline:none; transition:border-color 0.2s,box-shadow 0.2s; }
        .field input::placeholder { color:rgba(255,255,255,0.22); }
        .field input:focus { border-color:rgba(59,130,246,0.55); box-shadow:0 0 0 3px rgba(59,130,246,0.12); background:rgba(255,255,255,0.07); }
        .pwd-toggle { position:absolute; right:13px; background:none; border:none; color:rgba(255,255,255,0.35); cursor:pointer; font-size:14px; padding:4px; }
        .pwd-strength { display:flex; gap:4px; margin-top:8px; }
        .pwd-bar { flex:1; height:3px; border-radius:2px; background:rgba(255,255,255,0.1); transition:background 0.3s; }
        .pwd-bar.weak { background:#ef4444; } .pwd-bar.medium { background:#f59e0b; } .pwd-bar.strong { background:#10b981; }
        .pwd-hint { font-size:11px; color:rgba(255,255,255,0.35); margin-top:5px; }
        .btn-primary { width:100%; padding:13px; border:none; border-radius:12px; background:linear-gradient(135deg,#3b82f6,#1d4ed8); color:#fff; font-size:15px; font-weight:700; font-family:inherit; cursor:pointer; transition:all 0.25s; box-shadow:0 4px 20px rgba(59,130,246,0.35); margin-top:8px; }
        .btn-primary:hover { transform:translateY(-1px); }
        .login-link { margin-top:24px; text-align:center; font-size:13px; color:rgba(255,255,255,0.4); z-index:1; }
        .login-link a { color:#facc15; text-decoration:none; font-weight:600; }
        .footer-note { z-index:1; margin-top:20px; font-size:12px; color:rgba(255,255,255,0.2); text-align:center; }
    </style>
</head>
<body>

    <a class="brand" href="index.php">
        <div class="brand-icon">
            <img src="Logo_Surf.png" alt="SwellTracker Logo">
        </div>
        <div class="brand-name">Swell<span>Tracker</span></div>
    </a>

    <div class="card" role="main">
        <div class="card-title">Únete a la crew 🏄</div>
        <div class="card-subtitle">Crea tu cuenta y empieza a surfear datos</div>

        <form action="php/registro.php" method="POST" id="register-form">
            <div class="field">
                <label for="reg-name">Nombre de surfer</label>
                <div class="input-wrap">
                    <span class="input-icon">🤙</span>
                    <input type="text" id="reg-name" name="nombre" placeholder="Tu nombre o apodo" minlength="2" maxlength="80" required />
                </div>
            </div>
            <div class="field">
                <label for="reg-email">Email</label>
                <div class="input-wrap">
                    <span class="input-icon">📧</span>
                    <input type="email" id="reg-email" name="email" placeholder="tu@email.com" autocomplete="email" required />
                </div>
            </div>
            <div class="field">
                <label for="reg-password">Contraseña</label>
                <div class="input-wrap">
                    <span class="input-icon">🔒</span>
                    <input type="password" id="reg-password" name="password" placeholder="Mín. 8 caracteres" minlength="8" oninput="checkStrength(this.value)" required />
                    <button type="button" class="pwd-toggle" onclick="togglePwd('reg-password',this)">👁</button>
                </div>
                <div class="pwd-strength">
                    <div class="pwd-bar" id="bar1"></div><div class="pwd-bar" id="bar2"></div>
                    <div class="pwd-bar" id="bar3"></div><div class="pwd-bar" id="bar4"></div>
                </div>
                <div class="pwd-hint" id="pwd-hint">Usa letras, números y símbolos</div>
            </div>
            <div class="field">
                <label for="reg-password2">Confirmar contraseña</label>
                <div class="input-wrap">
                    <span class="input-icon">🔐</span>
                    <input type="password" id="reg-password2" name="password2" placeholder="Repite tu contraseña" required />
                    <button type="button" class="pwd-toggle" onclick="togglePwd('reg-password2',this)">👁</button>
                </div>
            </div>
            <button type="submit" class="btn-primary" id="register-btn">Crear cuenta</button>
        </form>
    </div>

    <p class="login-link">¿Ya tienes cuenta? <a href="index.php">Iniciar sesión</a></p>
    <p class="footer-note">SwellTracker Global &copy; <?= date('Y') ?></p>

    <script>
        function togglePwd(id, btn) {
            const input = document.getElementById(id);
            input.type = input.type === 'text' ? 'password' : 'text';
            btn.textContent = input.type === 'text' ? '🙈' : '👁';
        }
        function checkStrength(pwd) {
            const bars = [1,2,3,4].map(i => document.getElementById('bar'+i));
            const hint = document.getElementById('pwd-hint');
            let score = 0;
            if (pwd.length >= 8) score++;
            if (/[A-Z]/.test(pwd)) score++;
            if (/[0-9]/.test(pwd)) score++;
            if (/[^A-Za-z0-9]/.test(pwd)) score++;
            const levels = ['','weak','medium','medium','strong'];
            bars.forEach((b,i) => b.className = 'pwd-bar '+(i<score?levels[score]:''));
            hint.textContent = pwd.length ? ['','⚠ Muy débil','⚠ Débil','✔ Aceptable','✔ Segura'][score] : 'Usa letras, números y símbolos';
        }
    </script>
</body>
</html>
