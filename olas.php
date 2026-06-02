<?php
require_once 'inc/auth.php';
require_once 'inc/config.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="favicon.png" type="favicon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwellTracker Global – Olas & Spots</title>
    <meta name="description"
        content="Consulta las condiciones de surf en tiempo real en cualquier playa del mundo. Radar global de olas, viento y swell.">

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body {
            background-image: linear-gradient(to bottom, rgba(5, 10, 20, 0.7), rgba(0, 0, 0, 0.95)), url("https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1920&q=80");
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }
    </style>
</head>

<body class="min-h-screen text-gray-100 bg-slate-900 font-sans selection:bg-yellow-500/30 antialiased">

    <!-- NAV COMPARTIDA -->
    <?php include 'inc/navbar.php'; ?>

    <div id="app-root"></div>

    <script>
        // --- CONSTANTES Y MOCKS ---
        const GLOBAL_SURF_SPOTS = [
            { name: 'La Malvarrosa, Valencia', lat: 39.4795, lng: -0.3228 },
            { name: 'La Patacona, Valencia', lat: 39.4930, lng: -0.3235 },
            { name: 'El Saler, Valencia', lat: 39.3831, lng: -0.3245 },
            { name: 'Pipeline, Hawaii', lat: 21.6640, lng: -158.0539 },
            { name: 'Teahupo\'o, Tahiti', lat: -17.8471, lng: -149.2667 },
            { name: 'Uluwatu, Bali', lat: -8.8149, lng: 115.0884 },
            { name: 'Zarautz, País Vasco', lat: 43.2874, lng: -2.1699 },
            { name: 'Mundaka, País Vasco', lat: 43.4072, lng: -2.6965 },
            { name: 'Supertubos, Portugal', lat: 39.3308, lng: -9.3644 },
            { name: 'Nazaré, Portugal', lat: 39.6011, lng: -9.0830 },
            { name: 'Jeffreys Bay, Sudáfrica', lat: -34.0333, lng: 24.9167 },
            { name: 'Snapper Rocks, Australia', lat: -28.1625, lng: 153.5489 },
            { name: 'Bells Beach, Australia', lat: -38.3667, lng: 144.2833 },
            { name: 'Mavericks, California', lat: 37.4925, lng: -122.4981 },
            { name: 'Trestles, California', lat: 33.3853, lng: -117.5939 },
            { name: 'Puerto Escondido, México', lat: 15.8653, lng: -97.0681 },
            { name: 'Somo, Cantabria', lat: 43.4542, lng: -3.7381 },
            { name: 'El Palmar, Cádiz', lat: 36.2366, lng: -6.0682 },
            { name: 'Famara, Lanzarote', lat: 29.1172, lng: -13.5599 },
            { name: 'Razo, Galicia', lat: 43.2882, lng: -8.6946 },
            { name: 'G-Land, Indonesia', lat: -8.7243, lng: 114.3617 },
            { name: 'Cloud9, Filipinas', lat: 9.8055, lng: 126.1645 },
            { name: 'Hossegor, Francia', lat: 43.6625, lng: -1.4385 },
            { name: 'Biarritz, Francia', lat: 43.4832, lng: -1.5586 },
            { name: 'Waikiki, Hawaii', lat: 21.2769, lng: -157.8271 },
            { name: 'Río de Janeiro, Brasil', lat: -22.9836, lng: -43.2045 },
            { name: 'Pichilemu, Chile', lat: -34.3857, lng: -72.0048 },
            { name: 'Arugam Bay, Sri Lanka', lat: 6.8407, lng: 81.8267 }
        ];

        const navItems = [
            { id: 'index', label: 'Inicio', icon: 'sun', href: 'index.php' },
            { id: 'forecast', label: 'Olas &amp; Spots', icon: 'waves', href: 'olas.php' },
            { id: 'tutorials', label: 'Tutoriales', icon: 'play-circle', href: 'tutoriales.php' },
            { id: 'gear', label: 'Material', icon: 'layout-template', href: 'equipamiento.php' },
            { id: 'game', label: 'WavePilot 🎮', icon: 'gamepad-2', href: 'Juego.php' },
        ];

        const activeTab = 'forecast';

        // --- LÓGICA Y UTILIDADES ---
        const getDirection = (degree) => {
            if (degree === undefined || degree === null) return 'N/A';
            const val = Math.floor((degree / 22.5) + 0.5);
            const arr = ["N", "NNE", "NE", "ENE", "E", "ESE", "SE", "SSE", "S", "SSW", "SW", "WSW", "W", "WNW", "NW", "NNW"];
            return arr[(val % 16)];
        };

        const calculateQuality = (waveHeight, windSpeed) => {
            if (!waveHeight) return 'poor';
            if (waveHeight >= 1.5 && windSpeed < 15) return 'epic';
            if (waveHeight >= 1.0 && windSpeed < 20) return 'good';
            if (waveHeight > 0.5) return 'fair';
            return 'poor';
        };

        const fetchLiveSpotData = async (lat, lng, spotName) => {
            try {
                const marineRes = await fetch(`https://marine-api.open-meteo.com/v1/marine?latitude=${lat}&longitude=${lng}&current=wave_height,wave_direction,wave_period`);
                const marineData = await marineRes.json();

                const weatherRes = await fetch(`https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lng}&current=temperature_2m,wind_speed_10m,wind_direction_10m`);
                const weatherData = await weatherRes.json();

                const currentM = marineData.current || {};
                const currentW = weatherData.current || {};

                const waveH = currentM.wave_height || 0;
                const windS = currentW.wind_speed_10m || 0;

                return {
                    id: `${lat}-${lng}`,
                    name: spotName || 'Spot Personalizado',
                    lat,
                    lng,
                    waveHeight: waveH ? `${waveH.toFixed(1)}m` : 'Flat',
                    windSpeed: windS ? `${windS.toFixed(1)} km/h` : '0 km/h',
                    wind: getDirection(currentW.wind_direction_10m),
                    swell: currentM.wave_height ? `${currentM.wave_height.toFixed(1)}m a ${Math.round(currentM.wave_period || 0)}s` : 'N/A',
                    swellDir: getDirection(currentM.wave_direction),
                    temp: currentW.temperature_2m ? `${Math.round(currentW.temperature_2m)}°C` : 'N/A',
                    quality: calculateQuality(waveH, windS),
                    tide: 'Media'
                };
            } catch (error) {
                console.error("Error fetching live data:", error);
                return null;
            }
        };

        // --- ESTADO GLOBAL ---
        let state = {
            isMobileMenuOpen: false,
            mySpots: [],
            viewMode: 'list',
            searchQuery: '',
            searchResults: [],
            selectedMapSpot: null,
            isFetchingSpot: true
        };

        let mapInstance = null;
        let tempMarkerRef = null;
        let searchDebounceTimeout = null;

        // Cargar desde BD
        async function loadSpotsFromDB() {
            try {
                const res = await fetch('php/api_spots.php');
                if(!res.ok) throw new Error("API error");
                const dbSpots = await res.json();
                if (dbSpots && !dbSpots.error && dbSpots.length > 0) {
                    const loaded = [];
                    for (const s of dbSpots) {
                        const data = await fetchLiveSpotData(parseFloat(s.latitud), parseFloat(s.longitud), s.nombre);
                        if (data) loaded.push(data);
                    }
                    setState({ mySpots: loaded, isFetchingSpot: false });
                } else {
                    setState({ isFetchingSpot: false });
                }
            } catch (err) {
                console.error(err);
                setState({ isFetchingSpot: false });
            }
        }

        // --- COMPONENTES UI AUXILIARES ---
        const LogoIcon = `
            <div style="width:48px;height:48px;border-radius:50%;overflow:hidden;border:1px solid rgba(250,204,21,0.35);box-shadow:0 0 14px rgba(250,204,21,0.08);flex-shrink:0;">
                <img src="Logo_Surf.png" alt="SwellTracker Global" style="width:100%;height:100%;object-fit:cover;display:block;">
            </div>
        `;

        const TurtleIcon = `
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-12 h-12 text-yellow-400">
                <rect x="6" y="6" width="12" height="13" rx="6" fill="currentColor" fill-opacity="0.2"/>
                <circle cx="12" cy="3" r="2.5" fill="currentColor" fill-opacity="0.5"/>
                <path d="M7 8l-4-2M17 8l4-2M8 17l-3 3M16 17l3 3" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M12 6v13M6 12.5h12" stroke-linecap="round" stroke-dasharray="2 2"/>
            </svg>
        `;

        const Badge = (text, type) => {
            const colors = {
                epic: 'bg-yellow-500 text-black shadow-[0_0_10px_rgba(250,204,21,0.5)]',
                good: 'bg-blue-500 text-white shadow-[0_0_10px_rgba(59,130,246,0.5)]',
                fair: 'bg-gray-500 text-white',
                poor: 'bg-red-900/80 text-white',
            };
            const labels = { epic: 'Épico', good: 'Bueno', fair: 'Normal', poor: 'Malo' };
            return `<span class="text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider ${colors[type] || colors.fair}">${labels[type] || text}</span>`;
        };

        const GlassCard = (content, className = '') => `
            <div class="bg-black/40 backdrop-blur-md border border-white/10 rounded-2xl p-6 shadow-[0_8px_32px_0_rgba(0,0,0,0.5)] ${className}">
                ${content}
            </div>
        `;

        // --- MÉTODOS DE ESTADO ---
        function setState(newState) {
            state = { ...state, ...newState };
            render();
        }

        window.toggleMobileMenu = () => setState({ isMobileMenuOpen: !state.isMobileMenuOpen });
        window.setViewMode = (mode) => setState({ viewMode: mode, selectedMapSpot: null });
        
        window.removeSpot = async (id) => {
            const spotToRemove = state.mySpots.find(s => s.id === id);
            if(spotToRemove) {
                // Eliminar optimista visual
                setState({ mySpots: state.mySpots.filter(s => s.id !== id) });
                // Enviar a la BD
                await fetch('php/api_spots.php', {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ lat: spotToRemove.lat, lng: spotToRemove.lng })
                });
            }
        };
        
        window.setSelectedMapSpot = (spot) => setState({ selectedMapSpot: spot });

        window.handleAddMapSpot = async () => {
            const spot = state.selectedMapSpot;
            if (spot && !state.mySpots.find(s => s.id === spot.id)) {
                // Actualizar local
                state.mySpots.unshift(spot);
                setState({ selectedMapSpot: null, viewMode: 'list' });
                // Guardar en la BD
                await fetch('php/api_spots.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ lat: spot.lat, lng: spot.lng, name: spot.name })
                });
            } else {
                setState({ selectedMapSpot: null, viewMode: 'list' });
            }
        };

        // --- BÚSQUEDA ASÍNCRONA ---
        window.handleSearchInput = (value) => {
            state.searchQuery = value;
            clearTimeout(searchDebounceTimeout);

            const iconContainer = document.getElementById('search-icon-container');
            if (value.length > 2) {
                if (iconContainer) {
                    iconContainer.innerHTML = `<i data-lucide="loader-2" class="w-4 h-4 text-yellow-400 animate-spin"></i>`;
                    lucide.createIcons();
                }

                searchDebounceTimeout = setTimeout(async () => {
                    try {
                        const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(value)}&limit=5`);
                        state.searchResults = await response.json();
                    } catch (error) {
                        state.searchResults = [];
                    }
                    if (iconContainer) {
                        iconContainer.innerHTML = `<i data-lucide="search" class="w-4 h-4 text-gray-400"></i>`;
                        lucide.createIcons();
                    }
                    renderSearchResults();
                }, 800);
            } else {
                state.searchResults = [];
                if (iconContainer) {
                    iconContainer.innerHTML = `<i data-lucide="search" class="w-4 h-4 text-gray-400"></i>`;
                    lucide.createIcons();
                }
                renderSearchResults();
            }
        };

        function renderSearchResults() {
            const dropdown = document.getElementById('search-results-dropdown');
            if (!dropdown) return;

            if (state.searchResults.length === 0) {
                dropdown.innerHTML = '';
                dropdown.classList.add('hidden');
                return;
            }

            dropdown.classList.remove('hidden');
            dropdown.innerHTML = state.searchResults.map((loc, idx) => `
                <div class="p-3 border-b border-white/5 hover:bg-blue-900/40 transition-colors flex justify-between items-center group cursor-pointer" 
                     onclick="window.handleSelectSearchResult(${loc.lat}, ${loc.lon}, '${loc.display_name.replace(/'/g, "\\'")}')">
                    <div class="pr-4">
                        <p class="text-sm font-bold text-white line-clamp-1">${loc.display_name.split(',')[0]}</p>
                        <p class="text-xs text-gray-400 line-clamp-1">${loc.display_name.split(',').slice(1).join(',')}</p>
                    </div>
                    <i data-lucide="plus" class="w-5 h-5 text-gray-400 group-hover:text-yellow-400 flex-shrink-0"></i>
                </div>
            `).join('');
            lucide.createIcons();
        }

        window.handleSelectSearchResult = async (lat, lon, displayName) => {
            state.searchQuery = '';
            state.searchResults = [];

            const input = document.getElementById('search-input');
            if (input) input.value = '';
            renderSearchResults();

            setState({ isFetchingSpot: true });

            const cleanName = displayName.split(',').slice(0, 2).join(', ');
            const liveData = await fetchLiveSpotData(parseFloat(lat), parseFloat(lon), cleanName);

            if (liveData && !state.mySpots.find(s => s.id === liveData.id)) {
                // Optimista visual
                state.mySpots.unshift(liveData);
                // Enviar a la BD
                await fetch('php/api_spots.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ lat: liveData.lat, lng: liveData.lng, name: liveData.name })
                });
            }
            setState({ isFetchingSpot: false });
        };

        // --- MAPA ---
        function initMap() {
            const mapContainer = document.getElementById('map-container');
            if (!mapContainer) return;

            if (mapInstance) {
                mapInstance.remove();
                mapInstance = null;
            }

            const bounds = L.latLngBounds(L.latLng(-89.98, -180), L.latLng(89.98, 180));

            mapInstance = L.map(mapContainer, {
                center: [20.0, -10.0],
                zoom: 2,
                maxBounds: bounds,
                maxBoundsViscosity: 1.0,
                minZoom: 2
            });

            L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; OpenStreetMap',
                subdomains: 'abcd',
                maxZoom: 19,
                noWrap: true,
                bounds: bounds
            }).addTo(mapInstance);

            mapInstance.on('click', async (e) => {
                const { lat, lng } = e.latlng;

                if (tempMarkerRef) mapInstance.removeLayer(tempMarkerRef);

                const loadingIcon = L.divIcon({
                    html: `<div class="animate-spin w-5 h-5 border-2 border-yellow-400 border-t-transparent rounded-full shadow-[0_0_10px_rgba(250,204,21,0.8)]"></div>`,
                    className: '', iconSize: [20, 20], iconAnchor: [10, 10]
                });

                tempMarkerRef = L.marker([lat, lng], { icon: loadingIcon }).addTo(mapInstance);

                try {
                    const geoRes = await fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`);
                    const geoData = await geoRes.json();

                    let spotName = "Océano Desconocido";
                    if (geoData && geoData.address) {
                        spotName = geoData.address.beach || geoData.address.village || geoData.address.town || geoData.address.city || geoData.name || "Punto Costero";
                    }

                    const spotData = await fetchLiveSpotData(lat, lng, spotName);

                    if (spotData) {
                        window.setSelectedMapSpot(spotData);
                        const selectedIcon = L.divIcon({
                            html: `<div style="background-color: #FACC15; width: 14px; height: 14px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 10px rgba(250,204,21,0.8);"></div>`,
                            className: '', iconSize: [14, 14], iconAnchor: [7, 7]
                        });
                        tempMarkerRef.setIcon(selectedIcon);
                    } else {
                        mapInstance.removeLayer(tempMarkerRef);
                    }
                } catch (err) {
                    mapInstance.removeLayer(tempMarkerRef);
                }
            });

            GLOBAL_SURF_SPOTS.forEach(spot => {
                const spotIcon = L.divIcon({
                    html: `<div style="background-color: #3B82F6; width: 14px; height: 14px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 8px rgba(59,130,246,0.8); cursor: pointer; transition: all 0.3s ease;"></div>`,
                    className: '',
                    iconSize: [14, 14],
                    iconAnchor: [7, 7]
                });

                const marker = L.marker([spot.lat, spot.lng], { icon: spotIcon }).addTo(mapInstance);

                marker.on('click', async () => {
                    const loadingIcon = L.divIcon({
                        html: `<div class="animate-spin w-5 h-5 border-2 border-yellow-400 border-t-transparent rounded-full"></div>`,
                        className: '', iconSize: [20, 20], iconAnchor: [10, 10]
                    });
                    marker.setIcon(loadingIcon);

                    const spotData = await fetchLiveSpotData(spot.lat, spot.lng, spot.name);

                    marker.setIcon(spotIcon);
                    if (spotData) {
                        window.setSelectedMapSpot(spotData);
                    }
                });
            });
        }

        // --- RENDERIZADO PRINCIPAL ---
        function render() {
            const root = document.getElementById('app-root');

            const navMenu = navItems.map(item => `
                <a href="${item.href}"
                    class="flex items-center gap-2 px-4 py-2 rounded-full transition-all duration-300 ${activeTab === item.id ? 'bg-blue-600/20 text-yellow-400 shadow-[inset_0_0_20px_rgba(59,130,246,0.1)] border border-blue-500/30' : 'text-gray-300 hover:text-white hover:bg-white/5'}">
                    <i data-lucide="${item.icon}" class="w-4 h-4"></i> ${item.label}
                </a>
            `).join('');

            const mobileNavMenu = navItems.map(item => `
                <a href="${item.href}"
                    class="flex items-center gap-3 w-full px-4 py-3 rounded-xl transition-colors ${activeTab === item.id ? 'bg-blue-900/40 text-yellow-400' : 'text-gray-300 hover:bg-white/5'}">
                    <i data-lucide="${item.icon}" class="w-5 h-5"></i> ${item.label}
                </a>
            `).join('');

            const isSaved = state.selectedMapSpot && state.mySpots.find(s => s.id === state.selectedMapSpot.id);

            const mainContent = `
                <div class="space-y-6 animate-fade-in">
                    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
                        <div>
                            <h1 class="text-4xl md:text-5xl font-bold mb-2 bg-clip-text text-transparent bg-gradient-to-r from-yellow-400 to-yellow-200">
                                Condiciones Globales
                            </h1>
                            <p class="text-gray-300 text-lg flex items-center gap-2">
                                <i data-lucide="palmtree" class="w-5 h-5 text-blue-400"></i>
                                Conectado a boyas oceánicas globales en tiempo real.
                            </p>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-3">
                            <div class="relative flex items-center w-full sm:w-80 z-[1000]">
                                <div id="search-icon-container" class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
                                </div>
                                <input type="text" id="search-input" placeholder="Buscar cualquier playa del mundo..." 
                                    class="block w-full bg-black/60 backdrop-blur-md border border-white/20 rounded-full py-2 pl-10 pr-4 text-sm text-white placeholder-gray-400 focus:outline-none focus:border-yellow-400 focus:ring-1 focus:ring-yellow-400 transition-all"
                                    value="${state.searchQuery}"
                                    oninput="window.handleSearchInput(this.value)"
                                />
                                <div id="search-results-dropdown" class="hidden absolute top-full mt-2 left-0 w-full bg-slate-900/95 backdrop-blur-xl border border-white/20 rounded-xl shadow-[0_20px_50px_rgba(0,0,0,0.7)] max-h-60 overflow-y-auto"></div>
                            </div>

                            <div class="flex bg-black/60 border border-white/20 rounded-full p-1 backdrop-blur-md">
                                <button onclick="window.setViewMode('list')" class="flex items-center gap-2 px-4 py-1.5 rounded-full text-sm font-bold transition-all ${state.viewMode === 'list' ? 'bg-yellow-500 text-black shadow-md' : 'text-gray-400 hover:text-white'}">
                                    <i data-lucide="list" class="w-4 h-4"></i> Spots
                                </button>
                                <button onclick="window.setViewMode('map')" class="flex items-center gap-2 px-4 py-1.5 rounded-full text-sm font-bold transition-all ${state.viewMode === 'map' ? 'bg-yellow-500 text-black shadow-md' : 'text-gray-400 hover:text-white'}">
                                    <i data-lucide="map" class="w-4 h-4"></i> Radar Libre
                                </button>
                            </div>
                        </div>
                    </div>

                    ${state.isFetchingSpot ? `
                        <div class="w-full flex items-center justify-center p-8">
                            <div class="bg-black/60 backdrop-blur-xl rounded-full px-6 py-3 flex items-center gap-3 border border-yellow-400/30">
                                <i data-lucide="loader-2" class="w-5 h-5 text-yellow-400 animate-spin"></i>
                                <span class="text-yellow-400 font-semibold tracking-wide">Analizando boyas oceánicas...</span>
                            </div>
                        </div>
                    ` : ''}

                    ${state.viewMode === 'list' ? (
                    state.mySpots.length === 0 ? `
                            ${GlassCard(`
                                <div class="w-24 h-24 bg-blue-900/30 rounded-full flex items-center justify-center mb-6 border border-blue-500/20 relative z-10">
                                    ${TurtleIcon}
                                </div>
                                <h2 class="text-2xl font-bold mb-2 text-white z-10">Tu panel está vacío</h2>
                                <p class="text-gray-400 max-w-md mx-auto mb-6 z-10">
                                    Busca <b>cualquier</b> playa en el buscador de arriba o abre el Radar Libre para explorar los mejores spots del mundo.
                                </p>
                                <button onclick="window.setViewMode('map')" class="bg-yellow-500 text-black px-6 py-2.5 rounded-full font-bold hover:bg-yellow-400 transition-colors flex items-center gap-2 shadow-[0_0_20px_rgba(250,204,21,0.3)] z-10">
                                    <i data-lucide="compass" class="w-5 h-5"></i> Abrir Radar Global
                                </button>
                            `, 'flex flex-col items-center justify-center py-20 text-center border-dashed border-2 border-white/20 relative overflow-hidden')}
                        ` : `
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                ${state.mySpots.map(spot => GlassCard(`
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h2 class="text-2xl font-bold flex items-center gap-2 text-white mb-1">
                                                <i data-lucide="map-pin" class="w-5 h-5 text-yellow-400"></i> ${spot.name}
                                            </h2>
                                            <p class="text-xs text-gray-400">Lat: ${spot.lat.toFixed(3)}, Lng: ${spot.lng.toFixed(3)}</p>
                                        </div>
                                        ${Badge(spot.quality, spot.quality)}
                                    </div>

                                    <div class="grid grid-cols-2 gap-3 mb-4">
                                        <div class="bg-black/50 rounded-xl p-3 flex flex-col items-center justify-center border border-white/10">
                                            <span class="text-gray-400 text-xs uppercase tracking-wider mb-1">Altura Ola</span>
                                            <span class="text-3xl font-black text-blue-400 drop-shadow-[0_0_10px_rgba(59,130,246,0.3)]">${spot.waveHeight}</span>
                                        </div>
                                        <div class="bg-black/50 rounded-xl p-3 flex flex-col items-center justify-center border border-white/10">
                                            <span class="text-gray-400 text-xs uppercase tracking-wider mb-1">Viento</span>
                                            <div class="flex items-center gap-1">
                                                <span class="text-2xl font-black text-white">${spot.windSpeed}</span>
                                            </div>
                                            <span class="text-xs text-yellow-400 font-bold mt-0.5">${spot.wind}</span>
                                        </div>
                                    </div>

                                    <div class="bg-white/5 rounded-xl p-4 mb-4 flex justify-between items-center border border-white/10">
                                        <div class="flex flex-col">
                                            <span class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Swell Primario</span>
                                            <span class="text-base font-bold text-white flex items-center gap-1 mt-1">
                                                <i data-lucide="navigation" class="w-4 h-4 text-blue-400"></i> ${spot.swell}
                                            </span>
                                        </div>
                                        <div class="h-10 w-px bg-white/20"></div>
                                        <div class="flex flex-col items-center">
                                            <span class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Dirección</span>
                                            <span class="text-base font-bold text-white mt-1">${spot.swellDir}</span>
                                        </div>
                                        <div class="h-10 w-px bg-white/20"></div>
                                        <div class="flex flex-col items-end">
                                            <span class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Agua</span>
                                            <span class="text-base font-bold text-white flex items-center gap-1 mt-1">
                                                <i data-lucide="droplet" class="w-4 h-4 text-cyan-400"></i> ${spot.temp}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-end border-t border-white/10 pt-3">
                                        <button onclick="window.removeSpot('${spot.id}')" class="text-xs font-bold text-red-400 hover:text-red-300 transition-colors flex items-center gap-1 bg-red-400/10 px-3 py-1.5 rounded-md">
                                            <i data-lucide="x" class="w-3 h-3"></i> Eliminar
                                        </button>
                                    </div>
                                `, 'group hover:bg-white/[0.05] transition-all duration-300 relative overflow-hidden')).join('')}
                            </div>
                        `
                ) : `
                        <div class="relative">
                            <div class="absolute top-4 left-1/2 -translate-x-1/2 z-[2000] bg-black/80 backdrop-blur-md border border-yellow-500/50 text-yellow-400 text-sm font-bold px-6 py-2 rounded-full shadow-[0_0_15px_rgba(250,204,21,0.3)] flex flex-col items-center text-center animate-bounce w-[90%] md:w-auto">
                                <span class="flex items-center gap-2"><i data-lucide="map-pin" class="w-4 h-4"></i> Haz clic en CUALQUIER costa del mapa</span>
                                <span class="text-[10px] text-gray-300 font-normal mt-0.5">La app detectará y escaneará el lugar automáticamente</span>
                            </div>
                            ${GlassCard(`
                                <div id="map-container" class="w-full h-full rounded-xl z-0"></div>
                                
                                ${state.selectedMapSpot ? `
                                    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 w-11/12 max-w-md animate-fade-in z-[2000]">
                                        ${GlassCard(`
                                            <button onclick="window.setSelectedMapSpot(null)" class="absolute top-4 right-4 text-gray-400 hover:text-white bg-white/10 rounded-full p-1">
                                                <i data-lucide="x" class="w-5 h-5"></i>
                                            </button>
                                            <h3 class="text-xl font-bold mb-1 pr-8 text-white">${state.selectedMapSpot.name}</h3>
                                            
                                            <div class="flex items-center gap-3 mb-5">
                                                ${Badge(state.selectedMapSpot.quality, state.selectedMapSpot.quality)}
                                                <span class="text-lg font-black text-blue-400">${state.selectedMapSpot.waveHeight}</span>
                                            </div>

                                            <div class="grid grid-cols-2 gap-3 mb-5">
                                                <div class="bg-white/5 p-2 rounded-lg border border-white/10 flex flex-col justify-center">
                                                    <span class="text-[10px] text-gray-400 uppercase">Viento</span>
                                                    <span class="text-sm font-bold text-white flex items-center gap-1"><i data-lucide="wind" class="w-3 h-3 text-yellow-400"></i> ${state.selectedMapSpot.windSpeed} (${state.selectedMapSpot.wind})</span>
                                                </div>
                                                <div class="bg-white/5 p-2 rounded-lg border border-white/10 flex flex-col justify-center">
                                                    <span class="text-[10px] text-gray-400 uppercase">Swell</span>
                                                    <span class="text-sm font-bold text-white flex items-center gap-1"><i data-lucide="navigation" class="w-3 h-3 text-blue-400"></i> ${state.selectedMapSpot.swell}</span>
                                                </div>
                                            </div>
                                            
                                            <button 
                                                onclick="${isSaved ? '' : `window.handleAddMapSpot()`}"
                                                class="w-full py-3 rounded-xl font-bold text-sm flex items-center justify-center gap-2 transition-all ${isSaved ? 'bg-white/10 text-gray-400 cursor-not-allowed' : 'bg-yellow-500 text-black hover:bg-yellow-400 shadow-[0_0_20px_rgba(250,204,21,0.4)]'}"
                                            >
                                                ${isSaved ? `<i data-lucide="check" class="w-5 h-5"></i> Añadido a tu panel` : `<i data-lucide="plus" class="w-5 h-5"></i> Guardar en Mi Panel`}
                                            </button>
                                        `, '!bg-black/95 backdrop-blur-2xl !p-6 relative border border-blue-500/30 shadow-[0_20px_50px_rgba(0,0,0,0.8)]')}
                                    </div>
                                ` : ''}
                            `, 'p-1 h-[600px] relative overflow-hidden flex flex-col border-2 border-white/10 z-0')}
                        </div>
                    `}
                </div>
            `;

            root.innerHTML = `
                <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 relative z-10">
                    ${mainContent}
                </main>
            `;

            lucide.createIcons();

            if (state.viewMode === 'list' && state.searchQuery.length > 2) {
                renderSearchResults();
            } else if (state.viewMode === 'map') {
                setTimeout(initMap, 50);
            }
        }

        // Arrancar la app
        loadSpotsFromDB();
        // El render final se dispara desde setState al terminar de cargar
    </script>
</body>

</html>