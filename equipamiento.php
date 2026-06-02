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
    <title>SwellTracker Global – Tablas &amp; Material</title>
    <meta name="description"
        content="Guías y artículos sobre equipamiento de surf: tablas, quillas, neoprenos y todo lo que necesitas saber para surfear mejor.">

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

    <div id="modal-container" class="fixed inset-0 z-50 flex items-center justify-center px-4 hidden opacity-0 transition-opacity duration-300 bg-black/80 backdrop-blur-sm">
        <div class="bg-slate-900 border border-slate-700 rounded-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col shadow-2xl transform scale-95 transition-transform duration-300" id="modal-content-wrapper">
            <!-- HEADER MODAL -->
            <div class="relative h-48 md:h-64 shrink-0 bg-slate-800">
                <img id="modal-img" src="" alt="" class="w-full h-full object-cover opacity-60">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900 to-transparent"></div>
                <button onclick="closeModal()" class="absolute top-4 right-4 bg-black/50 hover:bg-black text-white p-2 rounded-full transition-colors z-10">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
                <div class="absolute bottom-6 left-6 right-6">
                    <div id="modal-icon-wrap" class="w-12 h-12 bg-blue-600/80 rounded-xl flex items-center justify-center mb-3 backdrop-blur-md"></div>
                    <h2 id="modal-title" class="text-3xl font-bold text-white">Título</h2>
                </div>
            </div>
            
            <!-- BODY MODAL -->
            <div class="p-6 md:p-8 overflow-y-auto custom-scrollbar" id="modal-body">
                <!-- Se inyecta contenido HTML aquí -->
            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 8px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #0f172a; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #475569; }
    </style>

    <script>
        const GEAR_DB = [
            {
                id: 'tablas',
                icon: 'info',
                title: 'Shortboard vs Longboard',
                desc: 'Descubre qué tabla se adapta mejor a tu estilo y al tipo de ola.',
                image: 'https://lushpalm.com/wp-content/uploads/2018/07/beginner-surfboards-13.jpg',
                content: `
                    <h3 class="text-2xl font-bold mb-4 text-white">¿Cuál elegir?</h3>
                    <p class="mb-4 text-gray-300">Las tablas cortas y largas responden a necesidades distintas en el agua. Las <strong>shortboards</strong> (c. 5’6″–6’6″) son ágiles y veloces, diseñadas para maniobras rápidas en olas medianas a grandes. En cambio los <strong>longboards</strong> (8′–10′+) ofrecen máxima flotabilidad y estabilidad, ideales para olas pequeñas y remadas fáciles.</p>
                    <ul class="list-disc list-inside mb-6 text-gray-300 space-y-2">
                        <li><strong>Shortboards:</strong> Tienen punta afilada y rocker alto. Son muy reactivas pero requieren experiencia para generar velocidad en la remada y mantener la estabilidad.</li>
                        <li><strong>Longboards:</strong> Suelen ser rígidos o flexibles, con nariz ancha y mucho volumen. Ofrecen planing automático incluso con olas débiles. La desventaja es la menor maniobrabilidad.</li>
                        <li><strong>Mini Malibú / Funboard:</strong> (≈7–8 pies) combina ambos mundos. Tiene algo de volumen extra y más rocker que un longboard. Ideal para intermedios que buscan estabilidad pero quieren girar con facilidad.</li>
                    </ul>
                    <h4 class="text-xl font-bold mb-3 text-white">Comparativa</h4>
                    <div class="overflow-x-auto mb-6">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="bg-gray-800 text-gray-200">
                                    <th class="p-3 border border-gray-700">Aspecto</th>
                                    <th class="p-3 border border-gray-700">Shortboard</th>
                                    <th class="p-3 border border-gray-700">Longboard</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-300">
                                <tr>
                                    <td class="p-3 border border-gray-700 font-semibold">Tamaño típico</td>
                                    <td class="p-3 border border-gray-700">5’6″ – 6’6″ (corto y puntiagudo)</td>
                                    <td class="p-3 border border-gray-700">8′ – 10′+ (largo y ancho)</td>
                                </tr>
                                <tr>
                                    <td class="p-3 border border-gray-700 font-semibold">Nivel</td>
                                    <td class="p-3 border border-gray-700">Avanzado/experto</td>
                                    <td class="p-3 border border-gray-700">Principiantes/intermedio</td>
                                </tr>
                                <tr>
                                    <td class="p-3 border border-gray-700 font-semibold">Ventajas</td>
                                    <td class="p-3 border border-gray-700">Alta maniobrabilidad; radicalidad en olas</td>
                                    <td class="p-3 border border-gray-700">Gran flotación; facilidad en olas pequeñas</td>
                                </tr>
                                <tr>
                                    <td class="p-3 border border-gray-700 font-semibold">Desventajas</td>
                                    <td class="p-3 border border-gray-700">Requiere buen remado; menos estable</td>
                                    <td class="p-3 border border-gray-700">Menos ágil; difícil de dominar en olas grandes</td>
                                </tr>
                                <tr>
                                    <td class="p-3 border border-gray-700 font-semibold">Uso recomendado</td>
                                    <td class="p-3 border border-gray-700">Olas medias a grandes, surf radical</td>
                                    <td class="p-3 border border-gray-700">Olas pequeñas a medianas, estilo clásico</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="bg-yellow-900/30 border border-yellow-700/50 p-4 rounded-xl text-yellow-200 text-sm">
                        <strong>Consejo de nivel:</strong> Los principiantes suelen comenzar en longboard. Tras ganar experiencia, pueden probar funboards intermedios. Los surfers avanzados optan por shortboards para máxima performance.
                    </div>
                `
            },
            {
                id: 'neoprenos',
                icon: 'droplet',
                title: 'Guía de Neoprenos',
                desc: 'Grosores y materiales según la temperatura del agua de tu spot.',
                image: 'https://www.nootica.es/media/catalog/category/promo_soldes.jpg',
                content: `
                    <h3 class="text-2xl font-bold mb-4 text-white">Encuentra tu traje ideal</h3>
                    <p class="mb-4 text-gray-300">Elegir el traje de neopreno adecuado es clave para surfear cómodo. El grosor del neopreno determina el calor: a mayor grosor en mm, más abrigo. Los trajes van desde chaquetillas (0.5–2 mm) para aguas cálidas hasta trajes completos con capucha y 5/4 mm o más en fríos extremos.</p>
                    <ul class="list-disc list-inside mb-6 text-gray-300 space-y-2">
                        <li><strong>Fullsuits (traje integral):</strong> Cubren brazos y piernas por completo. Vienen en grosores típicos como 3/2 mm, 4/3 mm, 5/4 mm. Ventajas: máximo abrigo y protección completa. Desventajas: restringen algo la movilidad.</li>
                        <li><strong>Spring suits:</strong> Trajes cortos (manga corta y pernera hasta la rodilla). Su grosor suele ser 2 mm o 3 mm. Son muy versátiles en aguas templadas (20–24 °C).</li>
                        <li><strong>Shorties y tops:</strong> Para protección solar/raspaduras en aguas cálidas.</li>
                        <li><strong>Hoodies y botines:</strong> La capucha cubre cuello, oídos y parte de la cabeza (esencial en invierno atlántico). Los botines protegen pies de cortes y frío.</li>
                    </ul>
                    <h4 class="text-xl font-bold mb-3 text-white">Guía de grosores</h4>
                    <div class="overflow-x-auto mb-6">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="bg-gray-800 text-gray-200">
                                    <th class="p-3 border border-gray-700">Grosor (mm)</th>
                                    <th class="p-3 border border-gray-700">Temp. agua</th>
                                    <th class="p-3 border border-gray-700">Ventajas</th>
                                    <th class="p-3 border border-gray-700">Desventajas</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-300">
                                <tr>
                                    <td class="p-3 border border-gray-700 font-semibold">0.5 – 2 mm</td>
                                    <td class="p-3 border border-gray-700">> 22 °C (caliente)</td>
                                    <td class="p-3 border border-gray-700">Muy ligero; protege del sol y viento</td>
                                    <td class="p-3 border border-gray-700">Poco abrigo; no sirve en agua fresca</td>
                                </tr>
                                <tr>
                                    <td class="p-3 border border-gray-700 font-semibold">3/2 mm</td>
                                    <td class="p-3 border border-gray-700">18 – 22 °C (templado)</td>
                                    <td class="p-3 border border-gray-700">Buen equilibrio calidez/flexibilidad</td>
                                    <td class="p-3 border border-gray-700">Menos térmico que 4/3</td>
                                </tr>
                                <tr>
                                    <td class="p-3 border border-gray-700 font-semibold">4/3 mm</td>
                                    <td class="p-3 border border-gray-700">12 – 18 °C (templado-frío)</td>
                                    <td class="p-3 border border-gray-700">Más abrigo en torso; útil en aguas frescas</td>
                                    <td class="p-3 border border-gray-700">Algo menos flexible; añade peso</td>
                                </tr>
                                <tr>
                                    <td class="p-3 border border-gray-700 font-semibold">5/4 – 6/5 mm</td>
                                    <td class="p-3 border border-gray-700">< 12 °C (frío)</td>
                                    <td class="p-3 border border-gray-700">Máximo calor en aguas muy frías</td>
                                    <td class="p-3 border border-gray-700">Más pesado; restringe movilidad</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="bg-blue-900/30 border border-blue-700/50 p-4 rounded-xl text-blue-200 text-sm">
                        <strong>Mantenimiento:</strong> Enjuaga siempre el neopreno con agua dulce tras el surf y déjalo secar a la sombra (evita luz solar directa). Guárdalo colgado para evitar arrugas.
                    </div>
                `
            },
            {
                id: 'quillas',
                icon: 'anchor',
                title: 'Sistemas de Quillas',
                desc: 'FCS, Futures, Thruster, Quad... Todo lo que necesitas saber.',
                image: 'https://www.artsurfcamp.com/wp-content/uploads/2021/10/tikti.jpg',
                content: `
                    <h3 class="text-2xl font-bold mb-4 text-white">Sistemas y Configuraciones</h3>
                    <p class="mb-4 text-gray-300">Existen dos sistemas predominantes para fijar quillas desmontables en la tabla: FCS (Fin Control System) y Futures.</p>
                    <ul class="list-disc list-inside mb-6 text-gray-300 space-y-2">
                        <li><strong>FCS (FCS II):</strong> Es el estándar más usado en tablas de shortboard en Europa. El sistema click (FCS II) elimina los tornillos. Ventaja: extenso catálogo de quillas. Desventaja: la base es más pequeña por lo que ofrece menos "feeling" duro con la tabla.</li>
                        <li><strong>Futures:</strong> Basado en una única caja larga (US box). Las quillas se insertan con un solo tornillo en la parte delantera. Ventaja: unión muy rígida, casi como quilla fija, con transmisión de potencia excepcional. Desventaja: en impactos fuertes la base al completo puede dañar más la tabla.</li>
                    </ul>
                    <h4 class="text-xl font-bold mb-3 text-white">Configuraciones comunes</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="bg-gray-800 p-4 rounded-xl">
                            <strong class="text-white block mb-1">Single (1 quilla)</strong>
                            <span class="text-sm text-gray-400">Estabilidad máxima en línea recta; clásico en longboards. Proporciona un ride suave pero es difícil de girar radicalmente.</span>
                        </div>
                        <div class="bg-gray-800 p-4 rounded-xl">
                            <strong class="text-white block mb-1">Twin fin (2 quillas)</strong>
                            <span class="text-sm text-gray-400">Velocidad y soltura. Generan mucha aceleración en olas pequeñas. Sensación retro y mucha libertad, típico de las tablas Fish.</span>
                        </div>
                        <div class="bg-gray-800 p-4 rounded-xl">
                            <strong class="text-white block mb-1">Thruster (3 quillas)</strong>
                            <span class="text-sm text-gray-400">Equilibrio perfecto entre control y maniobra. Es el set estándar en shortboards, versátil en todo tipo de olas.</span>
                        </div>
                        <div class="bg-gray-800 p-4 rounded-xl">
                            <strong class="text-white block mb-1">Quad (4 quillas)</strong>
                            <span class="text-sm text-gray-400">Máxima velocidad en línea y agarre al girar. Mezcla la soltura del twin con el control del thruster. Gran desempeño en tubos.</span>
                        </div>
                    </div>
                `
            },
            {
                id: 'inventos',
                icon: 'link',
                title: 'Inventos y Accesorios',
                desc: 'Leashes, pads antideslizantes, adaptadores y componentes para foil.',
                image: 'https://images.unsplash.com/photo-1502680390469-be75c86b636f?auto=format&fit=crop&w=800&q=80',
                content: `
                    <h3 class="text-2xl font-bold mb-4 text-white">Inventos, Pads y Accesorios</h3>
                    <p class="mb-4 text-gray-300">Elementos indispensables que completan tu tabla y te mantienen seguro en el agua.</p>
                    <ul class="list-disc list-inside mb-6 text-gray-300 space-y-4">
                        <li><strong>Leash (tobillera de seguridad):</strong> Correa elástica que une el pie con la tabla. Evita perder la tabla tras caídas. Vienen en varios largos (p.ej. 6 ft para tabla 6 ft). El grosor depende de la potencia de las olas.</li>
                        <li><strong>Pad / Traction pad:</strong> Almohadilla antideslizante adhesiva en la cola de la tabla (zona del pie trasero). Aporta agarre sin usar cera. Suelen constar de tres secciones con un taco elevador ("kick") al final. Muy útil en tablas cortas para maniobras aéreas.</li>
                        <li><strong>Adaptadores y plugs:</strong> Pequeños accesorios para compatibilidad. Por ejemplo, adaptadores para instalar quillas FCS en cajas Futures. Los "plugs" son los insertos roscados integrados en la tabla para atornillar quillas o el leash.</li>
                        <li><strong>Máster de foil y straps:</strong> Para la modalidad de foiling. El mástil (aluminio o carbono) conecta la tabla con el ala (foil) y los straps (correas) sujetan los pies para control a alta velocidad.</li>
                    </ul>
                `
            },
            {
                id: 'ceras',
                icon: 'sun',
                title: 'Ceras y Lubricantes',
                desc: 'Tipos de parafina según la temperatura del agua y herramientas de limpieza.',
                image: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRRIPBhRpBCIxk9Uc7B9HygFoqOIwWRqz4_pg&s',
                content: `
                    <h3 class="text-2xl font-bold mb-4 text-white">Ceras y Lubricantes</h3>
                    <p class="mb-4 text-gray-300">La tracción adecuada sobre la tabla es fundamental para no resbalar al ponerte de pie o maniobrar.</p>
                    <ul class="list-disc list-inside mb-6 text-gray-300 space-y-4">
                        <li><strong>Cera de surf ("wax"):</strong> Barra de parafina que crea agarre en el deck. Se aplica frotando en círculos para crear "bolitas" de tracción.</li>
                        <li><strong>Tipos según temperatura:</strong>
                            <ul class="list-[circle] pl-8 mt-2 space-y-1 text-sm">
                                <li><em>Cold</em> (Agua fría)</li>
                                <li><em>Cool</em> (Agua templada)</li>
                                <li><em>Warm</em> (Agua caliente)</li>
                                <li><em>Tropical</em> (Aguas muy cálidas)</li>
                            </ul>
                        </li>
                        <li><strong>Base coat:</strong> Se aplica una capa base dura primero sobre la tabla limpia, y luego se echa la cera del rango de temperatura correspondiente encima.</li>
                        <li><strong>Herramientas (Peine de cera):</strong> El "wax comb" es una herramienta con un lado liso para raspar la cera vieja y otro dentado para texturizar la cera fresca y mejorar el agarre antes de surfear.</li>
                        <li><strong>Lubricantes y limpiadores:</strong> Líquidos o sprays que ayudan a proteger el epoxy y facilitan la limpieza completa de la cera antigua.</li>
                    </ul>
                `
            },
            {
                id: 'seguridad',
                icon: 'shield',
                title: 'Seguridad y Rescate',
                desc: 'Chalecos de impacto, flotadores y accesorios vitales para olas grandes.',
                image: 'https://es.kampaoh.com/wp-content/uploads/2023/12/imagenes-2023-12-22T111338.035.jpg',
                content: `
                    <h3 class="text-2xl font-bold mb-4 text-white">Seguridad y Rescate</h3>
                    <p class="mb-4 text-gray-300">En condiciones extremas o situaciones comprometidas, el equipamiento de seguridad salva vidas.</p>
                    <div class="space-y-4 text-gray-300 mb-6">
                        <div class="bg-gray-800 p-4 rounded-xl border border-gray-700">
                            <h4 class="font-bold text-white mb-1">Chalecos salvavidas / Impact vest</h4>
                            <p class="text-sm">Flotadores personales para surfistas en olas grandes. Diseñados para surf (baja flotabilidad pero con gran libertad de movimiento). No sustituyen un leash, pero brindan flotación extra en wipeouts violentos. Exigidos en competiciones de olas gigantes.</p>
                        </div>
                        <div class="bg-gray-800 p-4 rounded-xl border border-gray-700">
                            <h4 class="font-bold text-white mb-1">Flotadores de rescate</h4>
                            <p class="text-sm">Boyas inflables usadas en rescates. Equipos que el socorrista sostiene; se lanzan al surfista o se le engancha para remolcarlo hacia la orilla. En spots de alto riesgo, los propios surfistas llevan una pequeña boya en bandolera.</p>
                        </div>
                        <div class="bg-gray-800 p-4 rounded-xl border border-gray-700">
                            <h4 class="font-bold text-white mb-1">Silbatos</h4>
                            <p class="text-sm">Dispositivo sonoro para llamar la atención en situaciones de emergencia. Muy útiles en playas remotas o en olas grandes donde el ruido del mar ahoga los gritos.</p>
                        </div>
                    </div>
                `
            },
            {
                id: 'transporte',
                icon: 'truck',
                title: 'Transporte y Almacenaje',
                desc: 'Fundas de viaje, soportes de pared y bacas (racks) para el coche.',
                image: 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80',
                content: `
                    <h3 class="text-2xl font-bold mb-4 text-white">Transporte y Almacenamiento</h3>
                    <p class="mb-4 text-gray-300">Cuidar tu tabla fuera del agua es tan importante como dentro de ella.</p>
                    <ul class="list-disc list-inside mb-6 text-gray-300 space-y-4">
                        <li><strong>Fundas (board bags):</strong> Fundas protectoras para viaje y almacenaje.
                            <ul class="list-[circle] pl-8 mt-2 space-y-1 text-sm text-gray-400">
                                <li><em>Calcetines de tela:</em> Protegen del polvo y del sol a nivel básico.</li>
                                <li><em>Acolchadas:</em> Tienen espuma (3-10 mm) y protegen de golpes leves (ideales para ir en el coche).</li>
                                <li><em>Rígidas de viaje (coffin):</em> Estructura reforzada máxima para vuelos y grandes impactos.</li>
                            </ul>
                        </li>
                        <li><strong>Portaequipajes de coche (roof racks):</strong> Barras para el techo del coche con almohadillas (pads) y correas de sujeción (cinchas). Imprescindibles para transportar longboards y evitar daños en la carrocería o multas de tráfico.</li>
                        <li><strong>Soportes de pared o garaje (racks):</strong> Ganchos horizontales o verticales acolchados para guardar las tablas en casa. Guardar la tabla correctamente (sin apoyarla directamente sobre la cola de forma agresiva) evita que se deforme o se abolle.</li>
                    </ul>
                `
            },
            {
                id: 'electronica',
                icon: 'watch',
                title: 'Electrónica y Gadgets',
                desc: 'Relojes GPS, cámaras de acción (GoPro) y sensores de olas.',
                image: 'https://images.unsplash.com/photo-1522273400909-fd1a8f77637e?auto=format&fit=crop&w=800&q=80',
                content: `
                    <h3 class="text-2xl font-bold mb-4 text-white">Electrónica y Gadgets</h3>
                    <p class="mb-4 text-gray-300">La tecnología ha llegado al surf para medir el rendimiento y capturar los mejores momentos.</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="bg-gray-800 p-4 rounded-xl">
                            <div class="w-10 h-10 bg-indigo-600/30 rounded-lg flex items-center justify-center mb-3">
                                <i data-lucide="watch" class="text-indigo-400 w-5 h-5"></i>
                            </div>
                            <strong class="text-white block mb-2">Relojes GPS de Surf</strong>
                            <p class="text-sm text-gray-400">Dispositivos como el Rip Curl Search GPS que miden estadísticas en tiempo real: olas cogidas, velocidad máxima, distancia remada y tiempo en el agua. Se sincronizan con el móvil para ver el track de tu sesión en un mapa.</p>
                        </div>
                        <div class="bg-gray-800 p-4 rounded-xl">
                            <div class="w-10 h-10 bg-indigo-600/30 rounded-lg flex items-center justify-center mb-3">
                                <i data-lucide="camera" class="text-indigo-400 w-5 h-5"></i>
                            </div>
                            <strong class="text-white block mb-2">Cámaras de acción</strong>
                            <p class="text-sm text-gray-400">Cámaras sumergibles (GoPro, Insta360) que se montan en el nose de la tabla o en la boca/casco. Capturan momentos POV (punto de vista) en alta definición, permitiendo analizar tu postura o compartir tubos increíbles.</p>
                        </div>
                        <div class="bg-gray-800 p-4 rounded-xl md:col-span-2">
                            <div class="w-10 h-10 bg-indigo-600/30 rounded-lg flex items-center justify-center mb-3">
                                <i data-lucide="activity" class="text-indigo-400 w-5 h-5"></i>
                            </div>
                            <strong class="text-white block mb-2">Sensores de olas</strong>
                            <p class="text-sm text-gray-400">Dispositivos piezoeléctricos o acelerómetros montados en la tabla para evaluar la técnica del surfista y las fuerzas G. Ayudan en el entrenamiento de alto rendimiento.</p>
                        </div>
                    </div>
                `
            }
        ];

        const GlassCard = (item) => `
            <div onclick="openModal('${item.id}')" class="bg-black/40 backdrop-blur-md border border-white/10 rounded-2xl overflow-hidden shadow-[0_8px_32px_0_rgba(0,0,0,0.5)] hover:bg-white/[0.05] transition-all duration-300 cursor-pointer border-t-2 border-t-transparent hover:border-t-yellow-400 hover:-translate-y-1 group flex flex-col h-full">
                <div class="h-48 w-full relative overflow-hidden shrink-0">
                    <img src="${item.image}" alt="${item.title}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 opacity-80 group-hover:opacity-100">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 to-transparent opacity-80"></div>
                </div>
                <div class="p-6 pt-4 flex flex-col flex-grow relative bg-slate-900/80 -mt-6 rounded-t-2xl z-10 border-t border-white/5">
                    <div class="w-12 h-12 bg-blue-600/20 rounded-xl flex items-center justify-center mb-4 border border-blue-500/30 shadow-[0_0_15px_rgba(59,130,246,0.1)] transition-transform group-hover:scale-110">
                        <i data-lucide="${item.icon}" class="w-6 h-6 text-yellow-400"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-white">${item.title}</h3>
                    <p class="text-gray-400 leading-relaxed text-sm flex-grow">${item.desc}</p>
                    <button class="mt-6 text-blue-400 font-bold text-sm group-hover:text-yellow-400 flex items-center gap-2 transition-colors">
                        Leer artículo <span class="text-lg group-hover:translate-x-1 transition-transform">→</span>
                    </button>
                </div>
            </div>
        `;

        function openModal(id) {
            const item = GEAR_DB.find(i => i.id === id);
            if (!item) return;

            document.getElementById('modal-img').src = item.image;
            document.getElementById('modal-title').textContent = item.title;
            document.getElementById('modal-icon-wrap').innerHTML = `<i data-lucide="${item.icon}" class="w-6 h-6 text-yellow-400"></i>`;
            document.getElementById('modal-body').innerHTML = item.content;
            
            lucide.createIcons();
            
            const modal = document.getElementById('modal-container');
            const wrapper = document.getElementById('modal-content-wrapper');
            
            modal.classList.remove('hidden');
            // Trigger reflow
            void modal.offsetWidth;
            
            modal.classList.remove('opacity-0');
            modal.classList.add('opacity-100');
            wrapper.classList.remove('scale-95');
            wrapper.classList.add('scale-100');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            const modal = document.getElementById('modal-container');
            const wrapper = document.getElementById('modal-content-wrapper');
            
            modal.classList.remove('opacity-100');
            modal.classList.add('opacity-0');
            wrapper.classList.remove('scale-100');
            wrapper.classList.add('scale-95');
            document.body.style.overflow = '';
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        window.addEventListener('DOMContentLoaded', () => {
            const main = document.getElementById('main-content');
            main.innerHTML = `
                <div class="space-y-8 animate-fade-in">
                    <header class="mb-8">
                        <h1 class="text-4xl md:text-5xl font-bold mb-4 bg-clip-text text-transparent bg-gradient-to-r from-yellow-400 to-white">Equipamiento</h1>
                        <p class="text-gray-300 text-lg">Información sobre tablas, quillas, neoprenos y accesorios.</p>
                    </header>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        ${GEAR_DB.map(item => GlassCard(item)).join('')}
                    </div>
                </div>
            `;
            lucide.createIcons();

            // Cerrar modal clickando fuera
            document.getElementById('modal-container').addEventListener('click', (e) => {
                if(e.target.id === 'modal-container') closeModal();
            });
        });
    </script>

</body>
</html>