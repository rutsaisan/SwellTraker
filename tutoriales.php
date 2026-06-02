<?php
require_once 'inc/auth.php';
require_once 'inc/config.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="favicon.png" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwellTracker Global – Tutoriales</title>
    <meta name="description"
        content="Tutoriales, técnicas y trucos de surf para todos los niveles. Aprende el take off perfecto, cutbacks, maniobras y mucho más.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-image: linear-gradient(to bottom, rgba(5, 10, 20, 0.7), rgba(0, 0, 0, 0.95)),
                              url("https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1920&q=80");
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeIn 0.5s ease-out forwards; }
    </style>
</head>

<body class="min-h-screen text-gray-100 bg-slate-900 font-sans selection:bg-yellow-500/30 antialiased">

    <!-- Decoración de fondo -->
    <div class="fixed top-20 left-10 opacity-10 pointer-events-none">
        <i data-lucide="sun" class="w-32 h-32 text-yellow-500"></i>
    </div>

    <!-- NAVBAR COMPARTIDA -->
    <?php include 'inc/navbar.php'; ?>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 relative z-10">
        <div id="main-content"></div>
    </main>
       <script>
        const TUTORIALS_DB = [
            { id: 1, title: 'El Take Off perfecto', level: 'Principiante', category: 'Técnica', image: 'https://images.unsplash.com/photo-1502680390469-be75c86b636f?auto=format&fit=crop&w=400&q=80', video: 'https://www.youtube.com/watch?v=e7ybWInObMQ',
              content: 'Es la maniobra inicial más básica. Consiste en ponerse de pie de forma rápida y equilibrada al sentir el empuje de la ola. Dominar el take off es fundamental para comenzar la ola con seguridad y soltura. Aprender a levantarse en el momento adecuado convierte esta maniobra en un gesto automático en tu rutina surfista.' },
            
            { id: 2, title: 'Cómo hacer un Cutback', level: 'Intermedio', category: 'Maniobras', image: 'https://images.unsplash.com/photo-1515404929826-76fff9fef6fe?auto=format&fit=crop&w=400&q=80', video: 'https://www.youtube.com/watch?v=PxFHl-4r3p0',
              content: 'Giro de 180º para volver a la espuma tras surfear alejándose de la rompiente. El cutback es básico para mantener la velocidad cuando la ola pierde fuerza: el surfista corta hacia el hombro de la ola más débil y regresa a la zona con más impulso. Permite recuperar velocidad al rebotar en la espuma.' },
            
            { id: 3, title: 'Entender las mareas y el viento', level: 'Todos', category: 'Teoría', image: 'https://surfcanarias.com/wp-content/uploads/2025/06/Las-Mareas-%C2%BFQue-son-y-como-se-Producen-2.webp', video: 'https://www.youtube.com/results?search_query=surf+tides+and+wind+explained',
              content: 'El nivel de la marea afecta la forma de las olas. En marea baja las olas suelen ser más rápidas y huecas; en marea alta las olas son más suaves y fáciles para principiantes. El viento moldea las olas. El offshore (desde tierra hacia el mar) limpia la ola, dejándola más ordenada para surfear. En cambio, el onshore (del mar hacia la costa) produce olas más desordenadas y difíciles.' },
            
            { id: 4, title: 'Generar velocidad en la pared', level: 'Avanzado', category: 'Técnica', image: 'https://images.unsplash.com/photo-1414490929659-9a12b7e31907?auto=format&fit=crop&w=400&q=80', video: 'https://www.youtube.com/results?search_query=ombe+speed+generation+surfing',
              content: 'Técnica para aumentar la velocidad en la ola, muy útil antes de realizar giros o aéreos. El pumping consiste en desplazarse repetidamente desde la base hacia la parte alta de la ola y luego descender con energía. Esta acción incrementa la velocidad, aprovecha mejor la pendiente y prepara al surfista para maniobras con más fuerza y control. Para generar velocidad se recomienda flexionar bien las rodillas (bajar el centro de gravedad) en la base de la ola y luego proyectarse hacia arriba usando los cantos de la tabla.' },

            { id: 5, title: 'Bottom Turn', level: 'Principiante/Intermedio', category: 'Maniobras', image: 'https://barefootsurf.s3.us-east-2.amazonaws.com/wp-content/uploads/2021/06/28223635/606ef56a0b3750ddfefab9ef_Major.jpeg', video: 'https://www.youtube.com/results?search_query=ombe+bottom+turn+surfing',
              content: 'Giro en la base de la ola justo después del take off. Permite dirigir la tabla de regreso hacia la pared activa de la ola, aprovechando al máximo su impulso en la bajada. Esta técnica es clave para mantener velocidad y preparar la realización de maniobras posteriores; es recomendable dominar el bottom turn antes de intentar giros más complejos.' },

            { id: 6, title: 'Duck Dive', level: 'Intermedio', category: 'Técnica', image: 'https://www.latassurf.com/cms/wp-content/uploads/2020/04/tecnica-pato-1080x672-1.webp', video: 'https://www.youtube.com/results?search_query=how+to+duck+dive+surfing',
              content: 'Técnica para pasar por debajo de las olas rompientes mientras remas. Se practica hundiendo la punta de la tabla bajo el agua con ayuda de la rodilla o el pie trasero, de modo que el surfista pueda avanzar sin ser arrastrado hacia la orilla. Es esencial saber hacer duck dive para colocar la tabla al pico sin perder la ola que quieres surfear.' },

            { id: 7, title: 'Floater', level: 'Intermedio', category: 'Maniobras', image: 'https://www.gosurferos.com/img/cms/Floater.jpg', video: 'https://www.youtube.com/results?search_query=how+to+do+a+floater+surfing',
              content: 'Maniobra en la que se cabalga sobre la espuma de una ola rota. Surfear sobre la parte rota ("espuma") da sensación de ingravidez. El floater sirve para sortear secciones complicadas: cuando la pared de la ola cierra, en lugar de caer se desliza uno brevemente sobre la espuma para continuar el recorrido.' },

            { id: 8, title: 'Reentry', level: 'Avanzado', category: 'Maniobras', image: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTvPUFEP2w0L5630aLV-ZGdRDWB1m-iuo22fA&s', video: 'https://www.youtube.com/results?search_query=surfing+reentry+tutorial',
              content: 'Giro brusco de 180º en la cresta de la ola. El surfista sube hasta el lip (labio) de la ola y realiza un giro vertical para volver a bajar. Es una maniobra exigente que requiere un bottom turn potente y buen control; sirve para volver a la pared de la ola desde arriba, manteniendo el impulso.' },

            { id: 9, title: 'Tubo o Tube Ride', level: 'Avanzado', category: 'Maniobras', image: 'https://www.berriasurfschool.com/wp-content/uploads/sites/893/2025/04/tubo-en-ola.jpg', video: 'https://www.youtube.com/results?search_query=how+to+barrel+ride+surfing',
              content: 'Surfear dentro del hueco o tubo de la ola. Consiste en quedarse bajo el lip mientras la ola rompe alrededor, con el brazo trasero pegado a la pared. Es considerada la maniobra más espectacular (y difícil) del surf, ya que permite avanzar protegido dentro de la curva de la ola.' },

            { id: 10, title: 'Aéreo', level: 'Avanzado', category: 'Maniobras', image: 'https://img.freepik.com/foto-gratis/fascinante-vista-silueta-surfista-oceano-puesta-sol-indonesia_181624-44971.jpg?semt=ais_hybrid&w=740&q=80', video: 'https://www.youtube.com/results?search_query=surf+aerial+tutorial',
              content: 'Saltar sobre la ola (realizar un salto con tabla) aprovechando la velocidad. El surfista se desliza por la pared y sale al aire "como en una rampa de skate". Un aerial requiere velocidad y equilibrio, y sirve para avanzar por la ola aprovechando la energía de esta.' },

            { id: 11, title: 'Reverso (Aerial 180)', level: 'Avanzado', category: 'Maniobras', image: 'https://magazine.todosurf.com/wp-content/uploads/2016/11/75247.jpg', video: 'https://www.youtube.com/results?search_query=surf+aerial+reverse+tutorial',
              content: 'Maniobra aérea en la que, tras despegar de la ola, se da un giro de 180º en el aire. Es una variante del aéreo que exige aún más velocidad de entrada, pues se tiene que girar por completo mientras se está en el aire.' },

            { id: 12, title: 'Off the lip', level: 'Intermedio', category: 'Maniobras', image: 'https://www.artsurfcamp.com/wp-content/uploads/2014/07/Off-the-lip.jpg', video: 'https://www.youtube.com/results?search_query=off+the+lip+surf+tutorial',
              content: 'Consiste en encarar la pared de la ola justo antes de que rompa, chocar ligeramente con ella y sacar parte de la cola de la tabla fuera del agua. Sirve para redirigir la dirección o tomar altura momentánea usando el labio de la ola.' },

            { id: 13, title: 'Nose Riding (Hang Ten)', level: 'Avanzado - longboard', category: 'Maniobras', image: 'https://www.astadventures.com/cdn/shop/articles/fullsizeoutput_3f1d_db35f54b-d263-4fb4-9065-78693a2c1379.jpeg?v=1516746704', video: 'https://www.youtube.com/results?search_query=hang+ten+longboard+tutorial',
              content: 'Maniobra heredada del longboard. El surfista camina hacia la punta de una tabla larga y sitúa los dedos sobre el nose (punta). Permite estabilidad extra y estilo, avanzando casi al frente de la ola.' },

            { id: 14, title: 'Snap', level: 'Avanzado', category: 'Maniobras', image: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRPt2LcQ5KulS6vHMFPqnKc8OtEJiVLFrHvRA&s', video: 'https://www.youtube.com/results?search_query=surf+snap+tutorial',
              content: 'Giro similar al cutback, pero muy brusco en la sección crítica de la ola. En vez de un giro amplio, el surfista cierra más la curva ("más cerrado en la pared"). Se usa para cambiar de dirección de forma explosiva cuando la ola aún tiene fuerza.' },

            { id: 15, title: 'Backside Tail Slide', level: 'Avanzado', category: 'Maniobras', image: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTBXRcdq6aZd2cyGW7_4jVfYUbhqsBN3-xijw&s', video: 'https://www.youtube.com/results?search_query=backside+tail+slide+surfing',
              content: 'Deslizar la cola de la tabla sobre la cresta de la ola. Se realiza girando el tail hacia la parte superior y deslizando (skate). Es un truco espectacular, usado para mostrar control y estilo.' },

            { id: 16, title: 'Tailslide', level: 'Avanzado', category: 'Maniobras', image: 'https://www.artsurfcamp.com/wp-content/uploads/2014/07/tailslide.jpg', video: 'https://www.youtube.com/results?search_query=tailslide+surf+tutorial',
              content: 'Variante del tail slide: el surfista rota los hombros y transfiere peso al pie delantero para elevar la cola de la tabla y deslizarla sobre la superficie de la ola.' },

            { id: 17, title: 'Wipe Out (¡Caída!)', level: 'Cualquier nivel', category: 'Técnica', image: 'https://www.berriasurfschool.com/wp-content/uploads/sites/893/2025/01/wipeout-surf.jpg', video: 'https://www.youtube.com/results?search_query=surf+wipeout+safety',
              content: 'No es una maniobra buscada, sino una caída involuntaria. Sucede cuando el surfista pierde el control y la tabla lo lanza al agua. Aunque no se busca intencionalmente, es útil reconocerlo como parte del surf e intentar minimizar la lesión (por ejemplo, cubrir cabeza al caer).' },

            { id: 18, title: 'Corrientes', level: 'Todos', category: 'Teoría', image: 'https://concepto.de/wp-content/uploads/2018/09/corrientes-marinas2-e1536755180913.jpg', video: 'https://www.youtube.com/results?search_query=rip+currents+for+surfers',
              content: 'Hay corrientes que pueden ayudar o entorpecer. La corriente de resaca (rip) arrastra hacia mar abierto; nunca hay que luchar contra ella, sino salir en diagonal hacia la costa. También existen corrientes laterales que desplazan por la playa, donde hay que mantener la posición con atención. Saber identificarlas ayuda a entrar y salir del agua con seguridad.' },

            { id: 19, title: 'Planificación y seguridad', level: 'Todos', category: 'Teoría', image: 'https://images.unsplash.com/photo-1439405326854-014607f694d7?auto=format&fit=crop&w=400&q=80', video: 'https://www.youtube.com/results?search_query=surf+safety+beginners',
              content: 'Entender el mar en conjunto (mareas, corrientes, viento, tipo de fondo) te permitirá surfear con seguridad y eficiencia. Como resumen, controlar estos factores "te permitirá surfear con seguridad y éxito". Siempre revisa reportes de olas y pregunta a locales o instructores cómo están las condiciones antes de entrar.' }
        ];

        const GlassCard = (content, cls = '') =>
            `<div class="bg-black/40 backdrop-blur-md border border-white/10 rounded-2xl shadow-[0_8px_32px_0_rgba(0,0,0,0.5)] ${cls}">${content}</div>`;

        window.openTutorial = (id) => {
            const t = TUTORIALS_DB.find(x => x.id === id);
            if (!t) return;
            
            let videoHTML = '';
            if (t.video && t.video.includes('watch?v=')) {
                const videoId = t.video.split('watch?v=')[1].split('&')[0];
                videoHTML = `<iframe class="absolute inset-0 w-full h-full z-20" src="https://www.youtube.com/embed/${videoId}?autoplay=1" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
            } else if (t.video && t.video.includes('search_query=')) {
                videoHTML = `
                    <div class="z-20 flex flex-col items-center justify-center text-center">
                        <i data-lucide="youtube" class="w-16 h-16 text-red-500 mb-4 drop-shadow-[0_0_15px_rgba(239,68,68,0.5)]"></i>
                        <h3 class="text-white font-bold text-xl mb-2">Buscar Tutorial</h3>
                        <p class="text-gray-300 text-sm mb-4 max-w-sm px-4">YouTube no permite reproducir búsquedas aquí. Haz clic abajo para ver los vídeos en YouTube.</p>
                        <a href="${t.video}" target="_blank" class="bg-red-600 hover:bg-red-500 text-white font-bold py-2 px-6 rounded-full transition-colors shadow-[0_0_20px_rgba(220,38,38,0.4)] flex items-center gap-2">
                            <i data-lucide="search" class="w-4 h-4"></i> Buscar en YouTube
                        </a>
                    </div>
                `;
            } else {
                videoHTML = `
                    <div class="z-10 flex flex-col items-center justify-center">
                        <div class="w-20 h-14 bg-red-600 rounded-2xl flex items-center justify-center shadow-[0_0_30px_rgba(220,38,38,0.6)] mb-3 border border-white/20">
                            <div class="w-0 h-0 border-t-8 border-t-transparent border-l-[14px] border-l-white border-b-8 border-b-transparent ml-1"></div>
                        </div>
                        <span class="text-white font-bold tracking-widest text-lg drop-shadow-md">TUTORIAL AQUÍ</span>
                    </div>
                `;
            }

            const modalHTML = `
                <div id="tutorial-modal-container" class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-6 opacity-0 transition-opacity duration-300" style="background: rgba(0, 0, 0, 0.7); backdrop-filter: blur(8px);">
                    <div class="bg-slate-900 border border-white/10 rounded-3xl w-full max-w-4xl shadow-2xl overflow-hidden transform scale-95 transition-transform duration-300 relative flex flex-col max-h-full">
                        
                        <button onclick="window.closeTutorial()" class="absolute top-4 right-4 z-[100] bg-black/50 hover:bg-red-500/80 text-white p-2 rounded-full backdrop-blur-md transition-colors border border-white/20 shadow-lg">
                            <i data-lucide="x" class="w-6 h-6"></i>
                        </button>
                        
                        <div class="w-full bg-black flex flex-col items-center justify-center relative aspect-video border-b border-white/10 group overflow-hidden">
                            <!-- Imagen de fondo difuminada para el placeholder -->
                            <img src="${t.image}" class="absolute inset-0 w-full h-full object-cover opacity-30 blur-sm scale-110" />
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900 to-transparent z-10"></div>
                            
                            ${videoHTML}
                        </div>

                        <div class="p-6 md:p-8 overflow-y-auto custom-scrollbar">
                            <div class="flex flex-wrap gap-3 mb-4">
                                <span class="bg-blue-500/20 text-blue-400 text-xs font-bold px-3 py-1 rounded-md border border-blue-500/30 uppercase tracking-wider flex items-center gap-1">
                                    <i data-lucide="book-open" class="w-3 h-3"></i> Nivel: ${t.level}
                                </span>
                                <span class="bg-yellow-500/20 text-yellow-400 text-xs font-bold px-3 py-1 rounded-md border border-yellow-500/30 uppercase tracking-wider">
                                    ${t.category}
                                </span>
                            </div>
                            
                            <h2 class="text-3xl md:text-4xl font-bold mb-6 text-white">${t.title}</h2>
                            
                            <div class="prose prose-invert prose-lg max-w-none text-gray-300 leading-relaxed">
                                <p>${t.content}</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.insertAdjacentHTML('beforeend', modalHTML);
            lucide.createIcons();
            
            // Forzar reflow para la animación
            const container = document.getElementById('tutorial-modal-container');
            const inner = container.querySelector('div');
            
            requestAnimationFrame(() => {
                container.classList.remove('opacity-0');
                inner.classList.remove('scale-95');
                inner.classList.add('scale-100');
            });
            
            // Cerrar al clickear fuera
            container.addEventListener('click', (e) => {
                if(e.target === container) window.closeTutorial();
            });
        };

        window.closeTutorial = () => {
            const container = document.getElementById('tutorial-modal-container');
            if (container) {
                const inner = container.querySelector('div');
                container.classList.add('opacity-0');
                inner.classList.remove('scale-100');
                inner.classList.add('scale-95');
                
                setTimeout(() => {
                    container.remove();
                }, 300);
            }
        };

        window.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();

            // Insertar estilos custom scrollbar temporalmente
            const style = document.createElement('style');
            style.innerHTML = `
                .custom-scrollbar::-webkit-scrollbar { width: 8px; }
                .custom-scrollbar::-webkit-scrollbar-track { background: rgba(0,0,0,0.2); border-radius: 10px; }
                .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(250,204,21,0.3); border-radius: 10px; }
                .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(250,204,21,0.5); }
            `;
            document.head.appendChild(style);

            const main = document.getElementById('main-content');
            main.innerHTML = `
                <div class="space-y-8 animate-fade-in">
                    <header class="mb-10">
                        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6">
                            <div>
                                <h1 class="text-4xl md:text-5xl font-bold mb-4 bg-clip-text text-transparent bg-gradient-to-r from-yellow-400 to-white">Aprende y Mejora</h1>
                                <p class="text-gray-300 text-lg">Tutoriales, técnicas y trucos para todos los niveles.</p>
                            </div>
                        </div>
                        
                        <div class="bg-white/5 border border-white/10 rounded-2xl p-4 backdrop-blur-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="bg-yellow-500/20 p-2 rounded-lg border border-yellow-500/30">
                                    <i data-lucide="library" class="w-6 h-6 text-yellow-400"></i>
                                </div>
                                <div>
                                    <h3 class="text-white font-bold text-sm">Biblioteca Oficial PDF</h3>
                                    <p class="text-gray-400 text-xs">Descarga todo el temario teórico y práctico</p>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-2 w-full md:w-auto">
                                <a href="PDFs/T%C3%A9cnicas,%20maniobras%20y%20fundamentos%20del%20surf.pdf" download target="_blank" class="flex items-center gap-2 bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-xl font-bold transition-all shadow-[0_0_15px_rgba(37,99,235,0.4)] text-xs border border-blue-400/50 flex-1 md:flex-initial justify-center">
                                    <i data-lucide="download" class="w-4 h-4"></i> Guía: Técnicas
                                </a>
                                <a href="PDFs/Equipamiento%20de%20surf%20(1).pdf" download target="_blank" class="flex items-center gap-2 bg-white/10 hover:bg-white/20 text-gray-200 hover:text-white px-4 py-2 rounded-xl transition-all border border-white/20 text-xs flex-1 md:flex-initial justify-center">
                                    <i data-lucide="download" class="w-4 h-4"></i> Básicos de Equipo
                                </a>
                                <a href="PDFs/Equipamiento%20de%20Surf_%20Shortboard%20vs%20Longboard,%20Neoprenos%20y%20Sistemas%20de%20Quillas.pdf" download target="_blank" class="flex items-center gap-2 bg-white/10 hover:bg-white/20 text-gray-200 hover:text-white px-4 py-2 rounded-xl transition-all border border-white/20 text-xs flex-1 md:flex-initial justify-center">
                                    <i data-lucide="download" class="w-4 h-4"></i> Guía Avanzada de Tablas
                                </a>
                            </div>
                        </div>
                    </header>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        ${TUTORIALS_DB.map(t => GlassCard(`
                            <div class="h-48 relative group cursor-pointer" onclick="window.openTutorial(${t.id})">
                                <div class="absolute inset-0 bg-blue-900/40 mix-blend-multiply group-hover:bg-transparent transition-colors z-10"></div>
                                <img src="${t.image}" alt="${t.title}" class="w-full h-full object-cover grayscale-[30%] group-hover:grayscale-0 transition-all" />
                                <div class="absolute top-4 left-4 z-20">
                                    <span class="bg-black/80 backdrop-blur-md text-xs font-bold px-3 py-1 rounded-md text-yellow-400 border border-white/10 uppercase tracking-wider">${t.category}</span>
                                </div>
                                <div class="absolute inset-0 flex items-center justify-center z-20 opacity-0 group-hover:opacity-100 transition-opacity bg-black/40 backdrop-blur-sm">
                                    <i data-lucide="play-circle" class="w-16 h-16 text-yellow-400 drop-shadow-[0_0_15px_rgba(250,204,21,0.5)] transform scale-90 group-hover:scale-100 transition-transform"></i>
                                </div>
                            </div>
                            <div class="p-5 flex-1 flex flex-col cursor-pointer" onclick="window.openTutorial(${t.id})">
                                <h3 class="font-bold text-lg mb-2 leading-tight text-white group-hover:text-yellow-400 transition-colors">${t.title}</h3>
                                <div class="mt-auto flex items-center gap-2 text-sm font-semibold text-blue-400 bg-blue-500/10 px-3 py-1.5 rounded-lg w-max border border-blue-500/20">
                                    <i data-lucide="book-open" class="w-4 h-4"></i> Nivel: ${t.level}
                                </div>
                            </div>
                        `, 'p-0 overflow-hidden flex flex-col hover:-translate-y-1 hover:shadow-[0_10px_30px_rgba(59,130,246,0.2)] transition-all duration-300 border border-white/10 group')).join('')}
                    </div>
                </div>
            `;
            lucide.createIcons();
        });
    </script>
</body>
</html>