<?php
require_once 'inc/auth.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwellTracker Global – Inicio</title>
    <link rel="icon" href="favicon.png" type="favicon">
    <meta name="description"
        content="SwellTracker Global: condiciones de surf en tiempo real, tutoriales y guías de equipamiento para surfistas de todo el mundo.">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap');

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #050a14;
            min-height: 100vh;
            color: #f1f5f9;
        }

        /* ── NAV — usamos el include compartido ── */

        /* ── COLLAGE ── */
        .collage-section {
            max-width: 1280px;
            margin: 48px auto;
            padding: 0 2rem;
        }

        /*
         * Layout tetris — CSS Grid con 6 columnas × 6 filas de 120px
         * Hueco central: columnas 3-4, filas 2-5
         */
        .collage-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            grid-template-rows: repeat(6, 120px);
            gap: 10px;
        }

        .collage-cell {
            border-radius: 16px;
            overflow: hidden;
            position: relative;
        }

        .collage-cell img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: opacity 0.9s ease-in-out;
        }

        /* Posiciones tetris */
        .c1 {
            grid-column: 1 / 3;
            grid-row: 1 / 3;
        }

        /* 2×2 grande */
        .c2 {
            grid-column: 3 / 4;
            grid-row: 1 / 2;
        }

        /* 1×1 */
        .c3 {
            grid-column: 4 / 5;
            grid-row: 1 / 2;
        }

        /* 1×1 */
        .c4 {
            grid-column: 5 / 7;
            grid-row: 1 / 3;
        }

        /* 2×2 grande */
        .c5 {
            grid-column: 1 / 2;
            grid-row: 3 / 5;
        }

        /* 1×2 vertical */
        .c6 {
            grid-column: 2 / 3;
            grid-row: 3 / 4;
        }

        /* 1×1 */
        /* HUECO CENTRAL */
        .c7 {
            grid-column: 5 / 6;
            grid-row: 3 / 4;
        }

        /* 1×1 */
        .c8 {
            grid-column: 6 / 7;
            grid-row: 3 / 5;
        }

        /* 1×2 vertical */
        .c9 {
            grid-column: 2 / 3;
            grid-row: 4 / 5;
        }

        /* 1×1 */
        .c10 {
            grid-column: 5 / 6;
            grid-row: 4 / 5;
        }

        /* 1×1 */
        .c11 {
            grid-column: 1 / 2;
            grid-row: 5 / 7;
        }

        /* 1×2 vertical */
        .c12 {
            grid-column: 2 / 4;
            grid-row: 5 / 6;
        }

        /* 2×1 */
        .c13 {
            grid-column: 4 / 5;
            grid-row: 5 / 6;
        }

        /* 1×1 */
        .c14 {
            grid-column: 5 / 7;
            grid-row: 5 / 7;
        }

        /* 2×2 grande */
        .c15 {
            grid-column: 2 / 3;
            grid-row: 6 / 7;
        }

        /* 1×1 */
        .c16 {
            grid-column: 3 / 5;
            grid-row: 6 / 7;
        }

        /* 2×1 */

        /* Hueco con la frase */
        .collage-quote {
            grid-column: 3 / 5;
            grid-row: 2 / 5;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 24px;
            background: rgba(5, 10, 20, 0.55);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(250, 204, 21, 0.15);
            border-radius: 20px;
            box-shadow: 0 0 60px rgba(250, 204, 21, 0.06), inset 0 0 40px rgba(0, 0, 0, 0.4);
        }

        .collage-quote .wave-icon {
            width: 40px;
            height: 40px;
            color: #3b82f6;
            margin-bottom: 16px;
            opacity: 0.85;
        }

        .collage-quote blockquote {
            font-size: clamp(0.95rem, 1.6vw, 1.2rem);
            font-weight: 700;
            line-height: 1.45;
            color: #fff;
            letter-spacing: -0.01em;
        }

        .collage-quote blockquote em {
            color: #facc15;
            font-style: normal;
        }

        .collage-quote .quote-line {
            width: 40px;
            height: 2px;
            background: linear-gradient(to right, #facc15, #3b82f6);
            border-radius: 2px;
            margin: 14px auto 0;
        }

        /* Overlay sutil en cada celda */
        .collage-cell::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(5, 10, 20, 0.1), rgba(5, 10, 20, 0.35));
            pointer-events: none;
        }

        /* ── HERO TEXT ── */
        .hero-text {
            text-align: center;
            margin-bottom: 40px;
        }

        .hero-text h1 {
            font-size: clamp(1.8rem, 4vw, 3rem);
            font-weight: 900;
            letter-spacing: -0.03em;
            background: linear-gradient(to right, #facc15, #fef08a, #fff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 12px;
        }

        .hero-text p {
            color: #94a3b8;
            font-size: 1.05rem;
            max-width: 520px;
            margin: 0 auto;
        }

        /* ── CTA BUTTONS ── */
        .cta-row {
            display: flex;
            gap: 14px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 44px;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #facc15;
            color: #000;
            font-weight: 700;
            font-size: 0.9rem;
            padding: 12px 28px;
            border-radius: 999px;
            text-decoration: none;
            box-shadow: 0 0 24px rgba(250, 204, 21, 0.35);
            transition: all 0.25s;
        }

        .btn-primary:hover {
            background: #fde047;
            box-shadow: 0 0 40px rgba(250, 204, 21, 0.5);
            transform: translateY(-2px);
        }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #e2e8f0;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 12px 28px;
            border-radius: 999px;
            text-decoration: none;
            backdrop-filter: blur(8px);
            transition: all 0.25s;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            transform: translateY(-2px);
        }

        .btn-primary svg,
        .btn-secondary svg {
            width: 17px;
            height: 17px;
        }

        /* ── ANIMACIÓN FADE-IN IMÁGENES ── */
        @keyframes imgFadeIn {
            from {
                opacity: 0;
                transform: scale(1.04);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .collage-cell img.entering {
            animation: imgFadeIn 0.9s ease forwards;
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            .collage-grid {
                grid-template-columns: repeat(4, 1fr);
                grid-template-rows: repeat(5, 100px);
            }

            .c1 {
                grid-column: 1/3;
                grid-row: 1/3;
            }

            .c2 {
                grid-column: 3/4;
                grid-row: 1/2;
            }

            .c3 {
                grid-column: 4/5;
                grid-row: 1/2;
            }

            .c4 {
                grid-column: 3/5;
                grid-row: 2/3;
            }

            .c5 {
                grid-column: 1/2;
                grid-row: 3/4;
            }

            .collage-quote {
                grid-column: 2/4;
                grid-row: 3/5;
                font-size: 0.85rem;
            }

            .c6 {
                grid-column: 4/5;
                grid-row: 3/4;
            }

            .c7 {
                grid-column: 1/2;
                grid-row: 4/5;
            }

            .c8 {
                grid-column: 4/5;
                grid-row: 4/5;
            }

            .c9 {
                grid-column: 1/3;
                grid-row: 5/6;
            }

            .c10 {
                grid-column: 3/5;
                grid-row: 5/6;
            }

            /* Ocultar celdas sobrantes en móvil */
            .c11,
            .c12,
            .c13,
            .c14,
            .c15,
            .c16 {
                display: none;
            }
        }
    </style>
</head>

<body>

    <!-- Decoración fija de fondo -->
    <div style="position:fixed;top:80px;left:40px;opacity:0.06;pointer-events:none;">
        <svg width="128" height="128" viewBox="0 0 24 24" fill="#facc15">
            <circle cx="12" cy="12" r="5" />
            <line x1="12" y1="1" x2="12" y2="3" stroke="#facc15" stroke-width="2" />
            <line x1="12" y1="21" x2="12" y2="23" stroke="#facc15" stroke-width="2" />
            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64" stroke="#facc15" stroke-width="2" />
            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78" stroke="#facc15" stroke-width="2" />
            <line x1="1" y1="12" x2="3" y2="12" stroke="#facc15" stroke-width="2" />
            <line x1="21" y1="12" x2="23" y2="12" stroke="#facc15" stroke-width="2" />
            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36" stroke="#facc15" stroke-width="2" />
            <line x1="18.36" y1="5.64" x2="19.78" y2="4.22" stroke="#facc15" stroke-width="2" />
        </svg>
    </div>
    <div style="position:fixed;bottom:80px;right:40px;opacity:0.06;pointer-events:none;">
        <svg width="160" height="160" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="1.5">
            <path
                d="M12 12c-2-2-4-6-1-8 2-1.5 5 1 5 4 0 2-2 4-4 4zm0 0c2-2 6-4 8-1 1.5 2-1 5-4 5-2 0-4-2-4-4zm0 0c2 2 4 6 1 8-2 1.5-5-1-5-4 0-2 2-4 4-4zm0 0c-2 2-6 4-8 1-1.5-2 1-5 4-5 2 0 4 2 4 4z"
                fill="#3b82f6" fill-opacity="0.2" />
            <circle cx="12" cy="12" r="2" fill="#3b82f6" />
        </svg>
    </div>

    <!-- NAV COMPARTIDA -->
    <?php
    include 'inc/navbar.php';
    ?>

    <!-- MAIN -->
    <main class="collage-section">

        <!-- Texto hero -->
        <div class="hero-text">
            <h1>El océano te espera.</h1>
            <p>Condiciones en tiempo real, tutoriales y equipamiento para surfistas de todo el mundo.</p>
        </div>

        <!-- COLLAGE TETRIS -->
        <div class="collage-grid" id="collage-grid">

            <!-- Celdas de imagen -->
            <div class="collage-cell c1"><img src="" alt="surf spot" data-slot="0"></div>
            <div class="collage-cell c2"><img src="" alt="surf spot" data-slot="1"></div>
            <div class="collage-cell c3"><img src="" alt="surf spot" data-slot="2"></div>
            <div class="collage-cell c4"><img src="" alt="surf spot" data-slot="3"></div>
            <div class="collage-cell c5"><img src="" alt="surf spot" data-slot="4"></div>
            <div class="collage-cell c6"><img src="" alt="surf spot" data-slot="5"></div>

            <!-- HUECO CENTRAL CON FRASE -->
            <div class="collage-quote">
                <svg class="wave-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M2 6c.6.5 1.2 1 2.5 1C7 7 7 5 9.5 5s2.5 2 5 2 2.5-2 5-2" />
                    <path d="M2 12c.6.5 1.2 1 2.5 1C7 13 7 11 9.5 11s2.5 2 5 2 2.5-2 5-2" />
                    <path d="M2 18c.6.5 1.2 1 2.5 1C7 19 7 17 9.5 17s2.5 2 5 2 2.5-2 5-2" />
                </svg>
                <blockquote>
                    Si no puedes parar las olas,<br>
                    <em>aprende a surfearlas.</em>
                </blockquote>
                <div class="quote-line"></div>
            </div>

            <div class="collage-cell c7"><img src="" alt="surf spot" data-slot="6"></div>
            <div class="collage-cell c8"><img src="" alt="surf spot" data-slot="7"></div>
            <div class="collage-cell c9"><img src="" alt="surf spot" data-slot="8"></div>
            <div class="collage-cell c10"><img src="" alt="surf spot" data-slot="9"></div>
            <div class="collage-cell c11"><img src="" alt="surf spot" data-slot="10"></div>
            <div class="collage-cell c12"><img src="" alt="surf spot" data-slot="11"></div>
            <div class="collage-cell c13"><img src="" alt="surf spot" data-slot="12"></div>
            <div class="collage-cell c14"><img src="" alt="surf spot" data-slot="13"></div>
            <div class="collage-cell c15"><img src="" alt="surf spot" data-slot="14"></div>
            <div class="collage-cell c16"><img src="" alt="surf spot" data-slot="15"></div>
        </div>

        <!-- CTAs -->
        <div class="cta-row">
            <a href="olas.php" class="btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M2 6c.6.5 1.2 1 2.5 1C7 7 7 5 9.5 5s2.5 2 5 2 2.5-2 5-2" />
                    <path d="M2 12c.6.5 1.2 1 2.5 1C7 13 7 11 9.5 11s2.5 2 5 2 2.5-2 5-2" />
                    <path d="M2 18c.6.5 1.2 1 2.5 1C7 19 7 17 9.5 17s2.5 2 5 2 2.5-2 5-2" />
                </svg>
                Ver condiciones globales
            </a>
            <a href="tutoriales.php" class="btn-secondary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10" />
                    <polygon points="10,8 16,12 10,16" />
                </svg>
                Explorar tutoriales
            </a>
        </div>

    </main>

    <script>
        // Imágenes locales del tablero de Pinterest (carpeta img/)
        const SURF_IMAGES = [
            'img/surf1.jpeg',
            'img/surf2.jpeg',
            'img/surf3.jpeg',
            'img/surf4.jpeg',
            'img/surf5.jpeg',
            'img/surf6.jpeg',
            'img/surf7.jpeg',
            'img/surf8.jpeg',
            'img/surf9.jpeg',
            'img/surf10.jpeg',
            'img/surf11.jpeg',
            'img/surf12.jpeg',
            'img/surf13.jpeg',
            'img/surf14.jpeg',
            'img/surf15.jpeg',
            'img/surf16.jpeg',
            'img/surf17.jpeg',
            'img/surf18.jpeg',
            'img/surf19.jpeg',
            'img/surf20.jpeg',
            'img/surf21.jpeg',
            'img/surf22.jpeg',
            'img/surf23.jpeg',
            'img/oceano.jpg'
        ];

        const NUM_SLOTS = 16;

        // Estado: qué imagen hay en cada slot
        let slotImages = [];

        // Mezclar array
        function shuffle(arr) {
            const a = [...arr];
            for (let i = a.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [a[i], a[j]] = [a[j], a[i]];
            }
            return a;
        }

        // Cargar imágenes iniciales (distintas entre sí)
        function initCollage() {
            const shuffled = shuffle(SURF_IMAGES);
            slotImages = shuffled.slice(0, NUM_SLOTS);

            const imgs = document.querySelectorAll('.collage-cell img');
            imgs.forEach((img, i) => {
                img.src = slotImages[i] || '';
                img.classList.add('entering');
                img.addEventListener('animationend', () => img.classList.remove('entering'), { once: true });
            });
        }

        // Cambiar UNA imagen aleatoria cada N segundos con cross-fade
        function rotateSingleImage() {
            const imgs = document.querySelectorAll('.collage-cell img');
            const slotIdx = Math.floor(Math.random() * NUM_SLOTS);
            const img = imgs[slotIdx];
            if (!img) return;

            // Elegir nueva imagen que no esté ya en uso
            const inUse = new Set(slotImages);
            const pool = SURF_IMAGES.filter(u => !inUse.has(u));
            const newUrl = pool.length > 0
                ? pool[Math.floor(Math.random() * pool.length)]
                : SURF_IMAGES[Math.floor(Math.random() * SURF_IMAGES.length)];

            // Fade out → swap → fade in
            img.style.opacity = '0';
            setTimeout(() => {
                img.src = newUrl;
                slotImages[slotIdx] = newUrl;
                img.style.opacity = '1';
                img.classList.add('entering');
                img.addEventListener('animationend', () => img.classList.remove('entering'), { once: true });
            }, 500);
        }

        // Inicializar y arrancar el ciclo
        initCollage();

        // Rotación escalonada: cada 2.8s cambia una imagen
        setInterval(rotateSingleImage, 2800);
    </script>

</body>

</html>