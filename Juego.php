<?php
require_once 'inc/auth.php';
require_once 'inc/config.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="favicon.png" type="favicon">
    <title>WavePilot 🌊 — Surf App</title>
    <meta name="description"
        content="WavePilot: tu compañero de surf gamificado. Sigue misiones, gestiona tu equipo y evoluciona con cada ola." />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        /* ── NAVBAR GLOBAL ─────────────────────────────────── */
        .global-nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
            background: rgba(0, 0, 0, 0.65);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }
        .global-nav-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 1.5rem;
            height: 68px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }
        .global-nav-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            flex-shrink: 0;
        }
        .global-nav-logo-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            border: 1px solid rgba(250,204,21,0.35);
            box-shadow: 0 0 14px rgba(250,204,21,0.08);
        }
        .global-nav-logo-img img {
            width: 100%; height: 100%;
            object-fit: cover; display: block;
        }
        .global-nav-brand {
            font-size: 1.2rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: -0.03em;
        }
        .global-nav-brand span { color: #facc15; }
        .global-nav-links {
            display: flex;
            align-items: center;
            gap: 4px;
            overflow-x: auto;
            scrollbar-width: none;
        }
        .global-nav-links::-webkit-scrollbar { display: none; }
        .global-nav-link {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 7px 13px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.22s;
            color: rgba(255,255,255,0.55);
            white-space: nowrap;
            border: 1px solid transparent;
        }
        .global-nav-link:hover {
            color: #fff;
            background: rgba(255,255,255,0.07);
        }
        .global-nav-link.active {
            background: rgba(99,252,255,0.12);
            color: #63FCFF;
            border-color: rgba(99,252,255,0.28);
            font-weight: 600;
        }
        .global-nav-link svg {
            width: 15px; height: 15px;
            flex-shrink: 0;
        }
        @media (max-width: 600px) {
            .global-nav-brand { display: none; }
            .global-nav-link { padding: 6px 9px; font-size: 12px; }
        }

        /* ── FONDO ────────────────────────────────────────── */

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 80px 16px 40px;
            background-image: url('img/PlayaFondo.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: linear-gradient(160deg,
                    rgba(5, 15, 40, 0.72) 0%,
                    rgba(10, 30, 70, 0.60) 50%,
                    rgba(2, 12, 35, 0.75) 100%);
            pointer-events: none;
            z-index: 0;
        }

        /* ── TARJETA PRINCIPAL ────────────────────────────── */
        .game-wrap {
            position: relative;
            z-index: 1;
            background: rgba(8, 18, 48, 0.82);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            width: 100%;
            max-width: 480px;
            border-radius: 24px;
            overflow: hidden;
            color: white;
            border: 1px solid rgba(99, 252, 255, 0.12);
            box-shadow:
                0 32px 80px rgba(0, 0, 0, 0.6),
                0 0 0 1px rgba(255, 255, 255, 0.04);
        }

        /* ── TOPBAR ───────────────────────────────────────── */
        .topbar {
            background: rgba(0, 0, 0, 0.35);
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            gap: 12px;
        }

        .logo {
            font-size: 18px;
            font-weight: 700;
            color: #63FCFF;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }

        .xp-bar-wrap {
            flex: 1;
            min-width: 0;
        }

        .xp-label {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.55);
            margin-bottom: 5px;
            display: flex;
            justify-content: space-between;
        }

        .xp-bar {
            height: 6px;
            background: rgba(255, 255, 255, 0.12);
            border-radius: 3px;
            overflow: hidden;
        }

        .xp-fill {
            height: 100%;
            background: linear-gradient(90deg, #63FCFF, #FFFF63);
            border-radius: 3px;
            transition: width 0.6s ease;
        }

        .level-badge {
            background: linear-gradient(135deg, #FFFF63, #FFD700);
            color: #0a1628;
            border-radius: 20px;
            padding: 6px 14px;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
            box-shadow: 0 2px 8px rgba(255, 255, 99, 0.3);
        }

        /* ── NAV ──────────────────────────────────────────── */
        .nav {
            display: flex;
            gap: 4px;
            padding: 10px 14px;
            background: rgba(0, 0, 0, 0.2);
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            overflow-x: auto;
            scrollbar-width: none;
        }

        .nav::-webkit-scrollbar {
            display: none;
        }

        .nav-btn {
            padding: 7px 14px;
            border-radius: 20px;
            border: none;
            background: transparent;
            color: rgba(255, 255, 255, 0.45);
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s;
            white-space: nowrap;
            font-family: inherit;
        }

        .nav-btn.active {
            background: #63FCFF;
            color: #0a1628;
            font-weight: 600;
        }

        .nav-btn:hover:not(.active) {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        /* ── SCREENS ──────────────────────────────────────── */
        .screen {
            display: none;
            padding: 18px 16px;
        }

        .screen.active {
            display: block;
        }

        /* ── SECTION TITLE ────────────────────────────────── */
        .section-title {
            font-size: 11px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.38);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
        }

        /* ── STATS ROW ────────────────────────────────────── */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            margin-bottom: 18px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.07);
            border-radius: 12px;
            padding: 14px 8px;
            text-align: center;
        }

        .stat-val {
            font-size: 24px;
            font-weight: 700;
            color: #63FCFF;
        }

        .stat-lbl {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.4);
            margin-top: 3px;
        }

        /* ── PERSONAJES ───────────────────────────────────── */
        .chars-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 16px;
        }

        .char-card {
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid transparent;
            border-radius: 16px;
            padding: 16px 12px;
            text-align: center;
            cursor: pointer;
            transition: all 0.22s;
        }

        .char-card:hover {
            background: rgba(99, 252, 255, 0.08);
            border-color: rgba(99, 252, 255, 0.25);
            transform: translateY(-2px);
        }

        .char-card.selected {
            border-color: #63FCFF;
            background: rgba(99, 252, 255, 0.13);
            box-shadow: 0 4px 20px rgba(99, 252, 255, 0.15);
        }

        .char-card.locked {
            opacity: 0.3;
            cursor: default;
        }

        .char-img {
            width: 80px;
            height: 110px;
            object-fit: contain;
            image-rendering: pixelated;
            margin: 0 auto 8px;
            display: block;
        }

        .char-placeholder {
            width: 80px;
            height: 110px;
            background: rgba(255, 255, 255, 0.07);
            border-radius: 12px;
            margin: 0 auto 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
        }

        .char-name {
            font-size: 14px;
            font-weight: 600;
            color: white;
        }

        .char-desc {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.4);
            margin-top: 3px;
        }

        /* ── MISIONES ─────────────────────────────────────── */
        .mission-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 4px;
        }

        .missions-refresh-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            background: rgba(99, 252, 255, 0.08);
            border: 1px solid rgba(99, 252, 255, 0.2);
            color: #63FCFF;
            border-radius: 20px;
            padding: 6px 14px;
            font-size: 12px;
            font-family: inherit;
            cursor: pointer;
            margin-bottom: 14px;
            transition: all 0.2s;
        }

        .missions-refresh-btn:hover {
            background: rgba(99, 252, 255, 0.16);
        }

        .mission {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 12px 14px;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid transparent;
        }

        .mission:hover {
            background: rgba(255, 255, 255, 0.09);
        }

        .mission.done {
            background: rgba(99, 252, 255, 0.07);
            border-color: rgba(99, 252, 255, 0.18);
        }

        .mission.epic {
            background: linear-gradient(45deg, rgba(229, 46, 113, 0.15), rgba(255, 138, 0, 0.15));
            border: 1px solid rgba(255, 138, 0, 0.3);
        }

        .mission-icon {
            font-size: 18px;
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.07);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .mission-info {
            flex: 1;
            min-width: 0;
        }

        .mission-name {
            font-size: 13px;
            font-weight: 500;
            color: white;
        }

        .mission.done .mission-name {
            text-decoration: line-through;
            color: rgba(255, 255, 255, 0.3);
        }

        .mission-xp {
            font-size: 11px;
            color: #FFFF63;
            margin-top: 2px;
            display: flex;
            gap: 6px;
        }
        
        .mission-reward {
            color: #63FCFF;
        }

        .mission-check {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.18);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all 0.2s;
            font-size: 13px;
        }

        .mission.done .mission-check {
            background: #63FCFF;
            border-color: #63FCFF;
            color: #0a1628;
        }

        /* ── RACHAS (STREAKS) ─────────────────────────────── */
        .streak-banner {
            background: linear-gradient(90deg, rgba(255, 138, 0, 0.2), rgba(229, 46, 113, 0.2));
            border: 1px solid rgba(255, 138, 0, 0.3);
            border-radius: 12px;
            padding: 12px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .streak-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .streak-icon {
            font-size: 24px;
            animation: pulseFire 2s infinite;
        }

        @keyframes pulseFire {
            0% { transform: scale(1); opacity: 0.8; }
            50% { transform: scale(1.1); opacity: 1; }
            100% { transform: scale(1); opacity: 0.8; }
        }

        .streak-text {
            font-size: 14px;
            font-weight: 700;
            color: white;
        }
        
        .streak-sub {
            font-size: 11px;
            color: #ffb84d;
        }

        /* ── EQUIPO ───────────────────────────────────────── */
        .equipo-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .equipo-item {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.07);
            border-radius: 12px;
            padding: 12px 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .equipo-left {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .equipo-icon {
            font-size: 18px;
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.07);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .equipo-name {
            font-size: 13px;
            color: white;
            font-weight: 500;
        }

        .equipo-val {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.38);
            margin-top: 2px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 140px;
        }

        .equipo-edit {
            font-size: 12px;
            color: #63FCFF;
            background: rgba(99, 252, 255, 0.08);
            border: 1px solid rgba(99, 252, 255, 0.2);
            border-radius: 8px;
            padding: 6px 12px;
            cursor: pointer;
            font-family: inherit;
            transition: all 0.2s;
            flex-shrink: 0;
        }

        .equipo-edit:hover {
            background: rgba(99, 252, 255, 0.18);
        }

        /* ── LOGROS ───────────────────────────────────────── */
        .logros-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .logro-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 14px 12px;
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1px solid transparent;
        }

        .logro-card.locked {
            opacity: 0.28;
        }

        .logro-card:not(.locked) {
            border-color: rgba(255, 255, 63, 0.2);
        }

        .logro-icon {
            font-size: 22px;
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.07);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .logro-card:not(.locked) .logro-icon {
            background: rgba(255, 255, 63, 0.1);
        }

        .logro-name {
            font-size: 12px;
            font-weight: 600;
            color: white;
        }

        .logro-desc {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.38);
            margin-top: 2px;
            line-height: 1.4;
        }

        /* ── MASCOTA (TAMAGOTCHI) ─────────────────────────── */
        .tortuga-wrap {
            text-align: center;
            padding: 24px 0 16px;
        }

        .tortuga-big {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto;
            animation: bob 2s ease-in-out infinite;
            filter: drop-shadow(0 8px 20px rgba(99, 252, 255, 0.3));
            transition: all 0.5s ease;
            width: 300px;
            height: 200px; /* Altura fija para que no salte la interfaz al crecer */
        }
        
        /* Efecto al arrastrarle comida */
        .tortuga-big.dragover {
            transform: scale(1.1);
            filter: drop-shadow(0 0 30px #FFFF63) brightness(1.2);
        }

        .tortuga-big img {
            width: 100%;
            height: auto;
            display: block;
            transition: transform 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        /* Evolución Visual basada en clases */
        .tortuga-cria img { transform: scale(0.6); }
        .tortuga-juvenil img { transform: scale(0.85); }
        .tortuga-adulta img { transform: scale(1); }

        @keyframes bob {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .tortuga-name {
            font-size: 22px;
            font-weight: 700;
            color: #63FCFF;
            margin-top: 12px;
        }

        .tortuga-lvl {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.4);
            margin-top: 4px;
        }

        .tortuga-bars-container {
            max-width: 280px;
            margin: 20px auto 0;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .tortuga-lbl {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 4px;
            display: flex;
            justify-content: space-between;
            font-weight: 600;
        }

        .tortuga-bar {
            height: 8px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
            overflow: hidden;
        }

        /* Barra de Energía (Hambre) */
        .tortuga-fill-energy {
            height: 100%;
            background: linear-gradient(90deg, #ff4b4b, #ffb84d, #4CAF50);
            background-size: 200% 100%;
            border-radius: 4px;
            transition: width 0.6s ease, background-position 0.6s ease;
        }

        /* Barra de Crecimiento */
        .tortuga-fill-growth {
            height: 100%;
            background: linear-gradient(90deg, #4CAF50, #63FCFF);
            border-radius: 4px;
            transition: width 0.6s ease;
        }

        /* Inventario de Comida */
        .food-inventory {
            margin-top: 20px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px dashed rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            padding: 16px;
        }
        
        .food-inventory-title {
            font-size: 12px;
            color: white;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .food-items-wrap {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .food-item {
            font-size: 32px;
            cursor: grab;
            background: rgba(0, 0, 0, 0.3);
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            position: relative;
            transition: transform 0.2s;
            border: 1px solid rgba(255,255,255,0.1);
        }

        .food-item:hover {
            transform: scale(1.1);
            background: rgba(99, 252, 255, 0.1);
            border-color: rgba(99, 252, 255, 0.3);
        }

        .food-item:active {
            cursor: grabbing;
        }

        .food-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ef4444;
            color: white;
            border-radius: 50%;
            font-size: 11px;
            font-weight: bold;
            width: 22px;
            height: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.5);
        }
        
        .food-item.empty {
            opacity: 0.3;
            pointer-events: none;
            filter: grayscale(1);
        }

        .tortuga-msg {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.5);
            margin: 20px auto 0;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.07);
            border-radius: 12px;
            padding: 14px;
            max-width: 300px;
            line-height: 1.6;
        }

        /* ── XP POPUP ─────────────────────────────────────── */
        .xp-popup {
            position: fixed;
            font-size: 16px;
            font-weight: 700;
            color: #FFFF63;
            pointer-events: none;
            z-index: 9999;
            transition: all 1s ease;
            opacity: 0;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
        }
        
        .food-popup {
            color: #4ade80; /* Verde para la comida */
        }

        /* ── SEPARADOR SECCIÓN ────────────────────────────── */
        .section-gap {
            margin-top: 20px;
        }

        /* ══════════════════════════════════════════════════
           OVERLAY: PERSONAJE EN LA PLAYA
        ══════════════════════════════════════════════════ */
        #beach-overlay {
            position: fixed;
            bottom: 0;
            right: 0;
            z-index: 10;
            display: flex;
            align-items: flex-end;
            gap: 0;
            pointer-events: none;
            transform: translateY(20px);
            opacity: 0;
            transition: transform 0.5s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.4s ease;
        }

        #beach-overlay.visible {
            transform: translateY(0);
            opacity: 1;
        }

        .beach-gear {
            display: grid;
            grid-template-columns: 1fr 1fr;
            align-items: flex-end;
            justify-items: center;
            gap: 6px;
            padding-bottom: 10px;
            padding-right: 4px;
        }

        .beach-gear img {
            height: 80px;
            width: auto;
            max-width: 70px;
            object-fit: contain;
            image-rendering: pixelated;
            filter: drop-shadow(0 4px 10px rgba(0, 0, 0, 0.55));
            animation: gearFloat 3s ease-in-out infinite;
        }

        #beach-gear-extra {
            position: fixed;
            bottom: 0;
            left: 0;
            z-index: 10;
            display: grid;
            grid-template-columns: 1fr 1fr;
            align-items: flex-end;
            justify-items: center;
            gap: 6px;
            padding: 0 6px 8px;
            pointer-events: none;
            opacity: 0;
            transform: translateY(20px);
            transition: transform 0.5s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.4s ease;
        }

        #beach-gear-extra.visible {
            opacity: 1;
            transform: translateY(0);
        }

        #beach-gear-extra img {
            height: 80px;
            width: auto;
            max-width: 70px;
            object-fit: contain;
            image-rendering: pixelated;
            filter: drop-shadow(0 4px 10px rgba(0, 0, 0, 0.55));
            animation: gearFloat 3s ease-in-out infinite;
        }

        @keyframes gearFloat {
            0%, 100% { transform: translateY(0) rotate(-1deg); }
            50% { transform: translateY(-6px) rotate(1deg); }
        }

        #beach-char-img {
            height: 220px;
            width: auto;
            object-fit: contain;
            image-rendering: pixelated;
            filter: drop-shadow(-6px 0 24px rgba(99, 252, 255, 0.35)) drop-shadow(0 16px 32px rgba(0, 0, 0, 0.6));
            animation: charBreath 4s ease-in-out infinite;
            display: block;
        }

        @keyframes charBreath {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-8px) scale(1.015); }
        }

        #beach-char-name {
            position: absolute;
            bottom: 8px;
            right: 8px;
            font-size: 11px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.7);
            letter-spacing: 0.8px;
            text-shadow: 0 1px 6px rgba(0, 0, 0, 0.8);
            pointer-events: none;
        }

        #beach-char-wrap { position: relative; }

        @media (max-width: 600px) {
            #beach-char-img { height: 140px; }
            .beach-gear img, #beach-gear-extra img { height: 60px; max-width: 55px; }
        }

        @media (max-width: 400px) {
            #beach-overlay { display: none; }
        }

        /* ══════════════════════════════════════════════════
           RESPONSIVE
        ══════════════════════════════════════════════════ */
        @media (min-width: 768px) {
            body { align-items: center; padding: 80px 20px 40px; }
            .game-wrap { max-width: 560px; }
            .topbar { padding: 18px 28px; }
            .nav { padding: 12px 20px; }
            .nav-btn { font-size: 14px; padding: 8px 16px; }
            .screen { padding: 22px 24px; }
            .stat-val { font-size: 26px; }
            .chars-grid { grid-template-columns: repeat(4, 1fr); }
            .char-img, .char-placeholder { width: 70px; height: 100px; }
            .char-name { font-size: 12px; }
            .char-desc { font-size: 10px; }
            .logros-grid { grid-template-columns: repeat(3, 1fr); }
            #beach-char-img { height: 240px; }
            .beach-gear img, #beach-gear-extra img { height: 90px; max-width: 80px; }
        }

        @media (min-width: 1100px) {
            body { padding: 80px 40px 48px; }
            .game-wrap { max-width: 680px; }
            .chars-grid { grid-template-columns: repeat(5, 1fr); }
            .char-img, .char-placeholder { width: 64px; height: 90px; }
            #beach-char-img { height: 280px; }
            .beach-gear img, #beach-gear-extra img { height: 100px; max-width: 90px; }
        }

        @media (max-width: 360px) {
            .topbar { padding: 12px 14px; }
            .logo { font-size: 15px; }
            .level-badge { padding: 5px 10px; font-size: 11px; }
            .screen { padding: 14px 12px; }
            .chars-grid { grid-template-columns: 1fr 1fr; gap: 8px; }
            .stat-val { font-size: 20px; }
        }
    </style>
</head>

<body>

    <?php include 'inc/navbar.php'; ?>

    <div id="beach-overlay">
        <div class="beach-gear" id="beach-gear-left"></div>
        <div id="beach-char-wrap">
            <img id="beach-char-img" src="img/Personaje 1.png" alt="Personaje" />
            <span id="beach-char-name">Luna</span>
        </div>
    </div>
    <div id="beach-gear-extra"></div>

    <div class="game-wrap">

        <div class="topbar">
            <div class="logo">
                🌊 WavePilot
                <button onclick="resetGame()" style="background:none;border:none;color:rgba(255,255,255,0.4);cursor:pointer;margin-left:8px;font-size:14px;transition:color 0.2s;" title="Reiniciar todo el progreso" onmouseover="this.style.color='#ef4444'" onmouseout="this.style.color='rgba(255,255,255,0.4)'">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                </button>
            </div>
            <div class="xp-bar-wrap">
                <div class="xp-label">
                    <span id="lvl-text">Nivel 3</span>
                    <span id="xp-num">340/500 XP</span>
                </div>
                <div class="xp-bar">
                    <div class="xp-fill" id="xp-fill" style="width:68%"></div>
                </div>
            </div>
            <div class="level-badge" id="lvl-badge">Lv 3</div>
        </div>

        <div class="nav">
            <button class="nav-btn active" id="nav-personaje" onclick="showScreen('personaje', this)">Personaje</button>
            <button class="nav-btn" id="nav-misiones" onclick="showScreen('misiones', this)">Misiones</button>
            <button class="nav-btn" id="nav-equipo" onclick="showScreen('equipo', this)">Equipo</button>
            <button class="nav-btn" id="nav-logros" onclick="showScreen('logros', this)">Logros</button>
            <button class="nav-btn" id="nav-mascota" onclick="showScreen('mascota', this)">Mascota</button>
        </div>

        <div class="screen active" id="screen-personaje">
            <div class="stats-row">
                <div class="stat-card">
                    <div class="stat-val" id="stat-sesiones">12</div>
                    <div class="stat-lbl">Sesiones</div>
                </div>
                <div class="stat-card">
                    <div class="stat-val" id="stat-xp">340</div>
                    <div class="stat-lbl">XP total</div>
                </div>
                <div class="stat-card">
                    <div class="stat-val" id="stat-misiones">0</div>
                    <div class="stat-lbl">Misiones</div>
                </div>
            </div>

            <div class="section-title">Elige tu rider</div>
            <div class="chars-grid">
                <div class="char-card selected" onclick="selectChar(0, this)">
                    <img class="char-img" src="img/Personaje 1.png" alt="Luna" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'" />
                    <div class="char-placeholder" style="display:none">🏄‍♀️</div>
                    <div class="char-name">Luna</div>
                    <div class="char-desc">Reina del longboard</div>
                </div>
                <div class="char-card" onclick="selectChar(1, this)">
                    <img class="char-img" src="img/Personaje 2.png" alt="Kai" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'" />
                    <div class="char-placeholder" style="display:none">🏄‍♂️</div>
                    <div class="char-name">Kai</div>
                    <div class="char-desc">Maestro del shortboard</div>
                </div>
                <div class="char-card" onclick="selectChar(2, this)">
                    <img class="char-img" src="img/Personaje 3.png" alt="Jake" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'" />
                    <div class="char-placeholder" style="display:none">🏄‍♂️</div>
                    <div class="char-name">Jake</div>
                    <div class="char-desc">Maestro del skateboard</div>
                </div>
                <div class="char-card" onclick="selectChar(3, this)">
                    <img class="char-img" src="img/Personaje_4.png" alt="Leo" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'" />
                    <div class="char-placeholder" style="display:none">🏄‍♂️</div>
                    <div class="char-name">Leo</div>
                    <div class="char-desc">Maestro del longboard</div>
                </div>
                <div class="char-card" onclick="selectChar(4, this)">
                    <img class="char-img" src="img/Personaje_5.png" alt="Melody" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'" />
                    <div class="char-placeholder" style="display:none">🏄</div>
                    <div class="char-name">Melody</div>
                    <div class="char-desc">Maestra de las Olas</div>
                </div>
            </div>
        </div>

        <div class="screen" id="screen-misiones">
            
            <div class="streak-banner">
                <div class="streak-info">
                    <div class="streak-icon">🔥</div>
                    <div>
                        <div class="streak-text">Racha activa: <span id="streak-count">1</span> días</div>
                        <div class="streak-sub" id="streak-bonus">Multiplicador XP: x1.0</div>
                    </div>
                </div>
            </div>

            <button class="missions-refresh-btn" onclick="forceNewMissions()">
                🎲 Nuevas misiones aleatorias
            </button>

            <div class="section-title">🌟 Especiales</div>
            <div class="mission-list" id="missions-special"></div>

            <div class="section-title section-gap">🌊 Surf — esta semana</div>
            <div class="mission-list" id="missions-surf"></div>

            <div class="section-title section-gap">🤣 Divertidas</div>
            <div class="mission-list" id="missions-fun"></div>

            <div class="section-title section-gap">🛹 Trucos</div>
            <div class="mission-list" id="missions-tricks"></div>
        </div>

        <div class="screen" id="screen-equipo">
            <div class="section-title">Tu equipamiento real</div>
            <div class="equipo-list" id="equipo-list"></div>
        </div>

        <div class="screen" id="screen-logros">
            <div class="section-title">Trofeos</div>
            <div class="logros-grid" id="logros-grid"></div>
        </div>

        <div class="screen" id="screen-mascota">
            <div class="tortuga-wrap">
                <div class="tortuga-big tortuga-juvenil" id="tortuga-pet" ondrop="dropFood(event)" ondragover="allowDrop(event)" ondragenter="dragEnter(event)" ondragleave="dragLeave(event)">
                    <img src="img/Tarta.png" alt="Tarta">
                </div>
                <div class="tortuga-name">Tarta</div>
                <div class="tortuga-lvl" id="tortuga-stage">Tortuga nivel 2 · Juvenil</div>
                
                <div class="tortuga-bars-container">
                    <div>
                        <div class="tortuga-lbl">
                            <span>Energía (Hambre)</span>
                            <span id="tortuga-energy-txt">50%</span>
                        </div>
                        <div class="tortuga-bar">
                            <div class="tortuga-fill-energy" id="tortuga-energy-fill" style="width: 50%;"></div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="tortuga-lbl">
                            <span>Crecimiento</span>
                            <span id="tortuga-growth-txt">62%</span>
                        </div>
                        <div class="tortuga-bar">
                            <div class="tortuga-fill-growth" id="tortuga-growth-fill" style="width: 62%;"></div>
                        </div>
                    </div>
                </div>

                <div class="food-inventory">
                    <div class="food-inventory-title">Despensa (Arrastra o haz clic)</div>
                    <div class="food-items-wrap">
                        <div class="food-item" id="food-alga" draggable="true" ondragstart="dragStart(event, 'alga')" onclick="feedClick('alga')">
                            🌿
                            <div class="food-badge" id="badge-alga">0</div>
                        </div>
                        <div class="food-item" id="food-pez" draggable="true" ondragstart="dragStart(event, 'pez')" onclick="feedClick('pez')">
                            🐟
                            <div class="food-badge" id="badge-pez">0</div>
                        </div>
                    </div>
                </div>

                <div class="tortuga-msg">
                    Las misiones te dan comida.<br>¡Si mi energía está alta, ganarás un 15% más de XP! 🌊
                </div>
            </div>
        </div>

    </div><script>
        /* ============================================================
           ESTADO DEL JUEGO (Con nuevas variables de Tamagotchi/Rachas)
        ============================================================ */
        const state = {
            xp: 340,
            level: 3,
            xpToNextLevel: 500,
            sessions: 12,
            completedMissions: new Set(),
            selectedCharIdx: 0,
            equipo: { tabla1: '', tabla2: '', quillas: '', neopreno: '', wax: '', accesorios: '' },
            
            // Nuevas variables (Se guardarán en localStorage de momento)
            lastPlayed: new Date().toDateString(),
            streakDays: 1,
            petGrowth: 20, // 0-100 (Evolución)
            petEnergy: 60, // 0-100 (Hambre)
            inventory: { alga: 2, pez: 1 } // Tokens de comida
        };

        const CHARACTERS = [
            { src: 'img/Personaje 1.png', name: 'Luna', emoji: '🏄‍♀️' },
            { src: 'img/Personaje 2.png', name: 'Kai', emoji: '🏄‍♂️' },
            { src: 'img/Personaje 3.png', name: 'Jake', emoji: '🏄‍♂️' },
            { src: 'img/Personaje_4.png', name: 'Leo', emoji: '🏄‍♂️' },
            { src: 'img/Personaje_5.png', name: 'Melody', emoji: '🏄' },
        ];

        const EQUIPO_ITEMS = [
            { key: 'tabla1', icon: '🏄', name: 'Tabla principal', img: 'img/tabla1.png' },
            { key: 'tabla2', icon: '🏄', name: 'Tabla secundaria', img: 'img/tabla2.png' },
            { key: 'quillas', icon: '🔱', name: 'Quillas', img: 'img/quillas.png' },
            { key: 'neopreno', icon: '🥷', name: 'Neopreno', img: 'img/neopreno.png' },
            { key: 'wax', icon: '🕯️', name: 'Wax', img: 'img/wax.png' },
            { key: 'accesorios', icon: '🎒', name: 'Accesorios', img: 'img/invento.png' },
        ];

        /* POOL DE MISIONES (Añadido "food" reward) */
        const POOL_SURF = [
            { id: 's1', icon: '🌊', name: 'Surfea 2 veces esta semana', xp: 50, food: 'alga' },
            { id: 's2', icon: '⏱️', name: 'Haz una sesión de más de 1h', xp: 40, food: 'alga' },
            { id: 's3', icon: '🌅', name: 'Surfea al amanecer', xp: 60, food: 'pez' },
            { id: 's4', icon: '📍', name: 'Ve a una playa nueva', xp: 80, food: 'pez' },
            { id: 's5', icon: '🌊', name: 'Entra al agua antes de las 8h', xp: 55, food: 'alga' }
        ];

        const POOL_FUN = [
            { id: 'f1', icon: '😂', name: 'Cae 3 veces de la tabla', xp: 30, food: 'alga' },
            { id: 'f2', icon: '🏖️', name: 'Come arena 1 vez', xp: 20, food: 'alga' },
            { id: 'f3', icon: '💥', name: 'Sobrevive a un wipeout épico', xp: 50, food: 'pez' },
            { id: 'f4', icon: '🦈', name: 'Salta una ola grande', xp: 60, food: 'pez' },
            { id: 'f5', icon: '🦀', name: 'Encuentra un cangrejo', xp: 25, food: 'alga' }
        ];

        const POOL_TRICKS = [
            { id: 't1', icon: '🔄', name: 'Haz un cutback', xp: 70, food: 'pez' },
            { id: 't2', icon: '🚀', name: 'Practica takeoff 10 veces', xp: 40, food: 'alga' },
            { id: 't3', icon: '🏄', name: 'Completa un floater', xp: 90, food: 'pez' },
            { id: 't4', icon: '⚡', name: 'Practica el duck dive 5 veces', xp: 50, food: 'alga' }
        ];
        
        const SPECIAL_MISSIONS = [
            { id: 'sp1', icon: '🌟', name: 'ÉPICA: Surfea 3 días diferentes', xp: 150, food: 'pez', epic: true },
            { id: 'sp2', icon: '🌤️', name: 'CLIMA: Hay olas > 1m. ¡Ve al agua!', xp: 80, food: 'alga' }
        ];

        let activeSurf = [];
        let activeFun = [];
        let activeTricks = [];

        function pickRandom(arr, n) {
            const shuffled = [...arr].sort(() => Math.random() - 0.5);
            return shuffled.slice(0, n);
        }

        /* ============================================================
           LÓGICA — RACHAS Y GUARDADO LOCAL (TAMAGOTCHI)
        ============================================================ */
        function loadLocalState() {
            const stored = localStorage.getItem('wavepilot_extended_state');
            if(stored) {
                try {
                    const data = JSON.parse(stored);
                    state.lastPlayed = data.lastPlayed || state.lastPlayed;
                    state.streakDays = data.streakDays || 1;
                    state.petGrowth = data.petGrowth !== undefined ? data.petGrowth : 20;
                    state.petEnergy = data.petEnergy !== undefined ? data.petEnergy : 60;
                    state.inventory = data.inventory || { alga: 0, pez: 0 };
                } catch(e){}
            }
            
            // Lógica de paso del tiempo
            const today = new Date().toDateString();
            if(state.lastPlayed !== today) {
                const lastDate = new Date(state.lastPlayed);
                const nowDate = new Date(today);
                const diffTime = Math.abs(nowDate - lastDate);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
                
                if(diffDays === 1) {
                    state.streakDays += 1; // Un día consecutivo
                    state.petEnergy = Math.max(0, state.petEnergy - 15); // Baja hambre poco
                } else {
                    state.streakDays = 1; // Racha rota
                    state.petEnergy = Math.max(0, state.petEnergy - (25 * diffDays)); // Pasa hambre
                }
                state.lastPlayed = today;
                saveLocalState();
            }
            
            updateStreakUI();
            updatePetUI();
        }

        function saveLocalState() {
            localStorage.setItem('wavepilot_extended_state', JSON.stringify({
                lastPlayed: state.lastPlayed,
                streakDays: state.streakDays,
                petGrowth: state.petGrowth,
                petEnergy: state.petEnergy,
                inventory: state.inventory
            }));
        }

        function getXPMultiplier() {
            let mult = 1.0 + (state.streakDays * 0.05); // +5% por día de racha
            if (state.petEnergy >= 80) mult += 0.15; // Buff de mascota feliz
            return Math.min(mult, 2.0); // Máximo x2.0
        }

        function updateStreakUI() {
            document.getElementById('streak-count').textContent = state.streakDays;
            const mult = getXPMultiplier().toFixed(2);
            let buffText = state.petEnergy >= 80 ? ' (+ Buff Mascota)' : '';
            document.getElementById('streak-bonus').textContent = `Multiplicador XP: x${mult} ${buffText}`;
        }

        /* ============================================================
           LÓGICA — TAMAGOTCHI (DRAG & DROP Y ALIMENTACIÓN)
        ============================================================ */
        function dragStart(event, foodType) {
            if(state.inventory[foodType] <= 0) {
                event.preventDefault();
                return;
            }
            event.dataTransfer.setData("foodType", foodType);
        }

        function allowDrop(event) {
            event.preventDefault();
        }

        function dragEnter(event) {
            event.preventDefault();
            document.getElementById('tortuga-pet').classList.add('dragover');
        }

        function dragLeave(event) {
            document.getElementById('tortuga-pet').classList.remove('dragover');
        }

        function dropFood(event) {
            event.preventDefault();
            document.getElementById('tortuga-pet').classList.remove('dragover');
            const foodType = event.dataTransfer.getData("foodType");
            feedTortuga(foodType);
        }

        // Para móviles o clics
        function feedClick(foodType) {
            feedTortuga(foodType);
        }

        function feedTortuga(foodType) {
            if(!foodType || state.inventory[foodType] <= 0) return;
            if(state.petEnergy >= 100) {
                alert("¡Tarta ya no tiene más hambre por hoy!");
                return;
            }

            // Consumir comida
            state.inventory[foodType] -= 1;
            
            // Beneficios
            const energyGain = (foodType === 'pez') ? 30 : 15;
            const growthGain = (foodType === 'pez') ? 4 : 2;
            
            state.petEnergy = Math.min(100, state.petEnergy + energyGain);
            state.petGrowth = Math.min(100, state.petGrowth + growthGain);
            
            saveLocalState();
            updatePetUI();
            updateStreakUI(); // Actualizar el multiplicador por si se activa el buff

            // Animación visual de comer
            const img = document.querySelector('#tortuga-pet img');
            img.style.transform = 'scale(1.2) rotate(5deg)';
            setTimeout(() => img.style.transform = '', 300);
        }

        function updatePetUI() {
            // Actualizar barras
            document.getElementById('tortuga-energy-fill').style.width = state.petEnergy + '%';
            document.getElementById('tortuga-energy-txt').textContent = Math.round(state.petEnergy) + '%';
            
            // Colores de la barra de energía según nivel
            const eFill = document.getElementById('tortuga-energy-fill');
            if(state.petEnergy < 30) eFill.style.backgroundPosition = '0% 0';
            else if(state.petEnergy < 70) eFill.style.backgroundPosition = '50% 0';
            else eFill.style.backgroundPosition = '100% 0';

            document.getElementById('tortuga-growth-fill').style.width = state.petGrowth + '%';
            document.getElementById('tortuga-growth-txt').textContent = Math.round(state.petGrowth) + '%';

            // Actualizar inventario
            const iAlga = document.getElementById('food-alga');
            const iPez = document.getElementById('food-pez');
            document.getElementById('badge-alga').textContent = state.inventory.alga;
            document.getElementById('badge-pez').textContent = state.inventory.pez;
            
            if(state.inventory.alga <= 0) iAlga.classList.add('empty'); else iAlga.classList.remove('empty');
            if(state.inventory.pez <= 0) iPez.classList.add('empty'); else iPez.classList.remove('empty');

            // Evolución Visual
            const petDiv = document.getElementById('tortuga-pet');
            const stageTxt = document.getElementById('tortuga-stage');
            
            petDiv.className = 'tortuga-big'; // reset
            if(state.petGrowth < 33) {
                petDiv.classList.add('tortuga-cria');
                stageTxt.textContent = "Nivel 1 · Cría";
            } else if (state.petGrowth < 80) {
                petDiv.classList.add('tortuga-juvenil');
                stageTxt.textContent = "Nivel 2 · Juvenil";
            } else {
                petDiv.classList.add('tortuga-adulta');
                stageTxt.textContent = "Nivel 3 · Adulta";
            }
        }

        /* ============================================================
           LÓGICA — GENERACIÓN MISIONES (Añadido Food Rewards)
        ============================================================ */
        function getDailyMissions() {
            const today = new Date().toDateString();
            const stored = localStorage.getItem('wavepilot_missions');
            if (stored) {
                try {
                    const data = JSON.parse(stored);
                    if (data.date === today && data.surf) return data;
                } catch(e){}
            }
            return generateNewMissionsList(today);
        }

        function generateNewMissionsList(dateString) {
            const data = {
                date: dateString || new Date().toDateString(),
                surf: pickRandom(POOL_SURF, 3),
                fun: pickRandom(POOL_FUN, 3),
                tricks: pickRandom(POOL_TRICKS, 3)
            };
            localStorage.setItem('wavepilot_missions', JSON.stringify(data));
            return data;
        }

        function refreshAllMissions() {
            const data = getDailyMissions();
            activeSurf = data.surf;
            activeFun = data.fun;
            activeTricks = data.tricks;
            
            renderMissions(SPECIAL_MISSIONS, 'missions-special'); // Las especiales son fijas
            renderMissions(activeSurf, 'missions-surf');
            renderMissions(activeFun, 'missions-fun');
            renderMissions(activeTricks, 'missions-tricks');
        }

        function forceNewMissions() {
            const data = generateNewMissionsList(new Date().toDateString());
            activeSurf = data.surf; activeFun = data.fun; activeTricks = data.tricks;
            renderMissions(activeSurf, 'missions-surf');
            renderMissions(activeFun, 'missions-fun');
            renderMissions(activeTricks, 'missions-tricks');
            
            const btn = document.querySelector('.missions-refresh-btn');
            if(btn) {
                const oldText = btn.textContent;
                btn.textContent = '¡Hecho!';
                setTimeout(() => btn.textContent = oldText, 1000);
            }
        }

        function renderMissions(list, containerId) {
            const el = document.getElementById(containerId);
            el.innerHTML = list.map(m => {
                const done = state.completedMissions.has(m.id);
                const isEpic = m.epic ? 'epic' : '';
                
                // Parche para misiones cacheadas antiguas
                const actualFood = m.food || 'alga'; 
                const foodIcon = actualFood === 'pez' ? '🐟' : '🌿';
                
                return `
        <div class="mission ${done ? 'done' : ''} ${isEpic}" id="mission-${m.id}"
             onclick="completeMission('${m.id}', ${m.xp}, '${actualFood}', event)">
          <div class="mission-icon">${m.icon}</div>
          <div class="mission-info">
            <div class="mission-name">${m.name}</div>
            <div class="mission-xp">
                <span>+${m.xp} XP</span>
                <span class="mission-reward">+1 ${foodIcon}</span>
            </div>
          </div>
          <div class="mission-check">${done ? '✓' : ''}</div>
        </div>`;
            }).join('');
        }

        function completeMission(id, baseGainXp, foodReward, event) {
            if (state.completedMissions.has(id)) return;
            state.completedMissions.add(id);

            const el = document.getElementById('mission-' + id);
            el.classList.add('done');
            const check = el.querySelector('.mission-check');
            check.textContent = '✓';
            check.style.background = '#63FCFF';
            check.style.borderColor = '#63FCFF';
            check.style.color = '#0a1628';

            // Aplicar multiplicador a la XP
            const finalXP = Math.round(baseGainXp * getXPMultiplier());
            addXP(finalXP, event);

            // Añadir comida al inventario
            if(foodReward && foodReward !== 'undefined' && foodReward !== 'null') {
                state.inventory[foodReward] = (state.inventory[foodReward] || 0) + 1;
                saveLocalState();
                updatePetUI();
                
                // Efecto visual de comida en el ratón
                const foodPopup = document.createElement('div');
                foodPopup.className = 'xp-popup food-popup';
                foodPopup.textContent = '+1 ' + (foodReward==='pez'?'🐟':'🌿');
                foodPopup.style.left = (event.clientX + 30) + 'px';
                foodPopup.style.top = (event.clientY - 10) + 'px';
                document.body.appendChild(foodPopup);
                setTimeout(() => { foodPopup.style.opacity = '1'; foodPopup.style.transform = 'translateY(-20px)'; }, 10);
                setTimeout(() => { foodPopup.style.opacity = '0'; }, 700);
                setTimeout(() => foodPopup.remove(), 1200);
            }

            // Guardar misión en BD
            fetch('api/wavepilot.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'complete_mision', mision_id: id })
            }).catch(() => {});
        }

        /* ============================================================
           LÓGICA — XP y niveles (Sin cambios excepto llamadas extra)
        ============================================================ */
        function addXP(amount, event) {
            state.xp += amount;

            if (event) {
                const popup = document.createElement('div');
                popup.className = 'xp-popup';
                popup.textContent = '+' + amount + ' XP';
                popup.style.left = (event.clientX - 20) + 'px';
                popup.style.top = (event.clientY - 10) + 'px';
                document.body.appendChild(popup);
                setTimeout(() => { popup.style.opacity = '1'; popup.style.transform = 'translateY(-30px)'; }, 10);
                setTimeout(() => { popup.style.opacity = '0'; }, 700);
                setTimeout(() => popup.remove(), 1200);
            }

            while (state.xp >= state.xpToNextLevel) {
                state.xp -= state.xpToNextLevel;
                state.level++;
                state.xpToNextLevel = state.level * 200 - 100;
            }

            updateXPBar();
            updateStats();
            checkAchievements();
            savePerfilDB();
        }

        function updateXPBar() {
            const pct = Math.round((state.xp / state.xpToNextLevel) * 100);
            document.getElementById('xp-fill').style.width = pct + '%';
            document.getElementById('xp-num').textContent = state.xp + '/' + state.xpToNextLevel + ' XP';
            document.getElementById('lvl-text').textContent = 'Nivel ' + state.level;
            document.getElementById('lvl-badge').textContent = 'Lv ' + state.level;
        }

        function updateStats() {
            document.getElementById('stat-sesiones').textContent = state.sessions;
            document.getElementById('stat-xp').textContent = state.xp;
            document.getElementById('stat-misiones').textContent = state.completedMissions.size;
        }

        /* ============================================================
           LÓGICA — OVERLAY DE PLAYA Y RESTO DEL JUEGO
        ============================================================ */
        function updateBeachOverlay() {
            const char = CHARACTERS[state.selectedCharIdx];
            const overlay = document.getElementById('beach-overlay');
            const img = document.getElementById('beach-char-img');
            const nameEl = document.getElementById('beach-char-name');
            const gearNear = document.getElementById('beach-gear-left');
            const gearFar = document.getElementById('beach-gear-extra');

            overlay.classList.remove('visible');
            gearFar.classList.remove('visible');

            setTimeout(() => {
                img.src = char.src; img.alt = char.name; nameEl.textContent = char.name;
                const activeGear = EQUIPO_ITEMS.filter(item => state.equipo[item.key] && item.img);
                const nearItems = activeGear.slice(0, 2);
                const farItems = activeGear.slice(2);

                function buildImg(item) {
                    const el = document.createElement('img');
                    el.src = item.img; el.alt = item.name; el.title = item.name + ': ' + state.equipo[item.key];
                    el.onerror = function () { this.style.display = 'none'; };
                    el.style.animationDelay = (Math.random() * 1.5).toFixed(1) + 's';
                    return el;
                }

                gearNear.innerHTML = ''; nearItems.forEach(item => gearNear.appendChild(buildImg(item)));
                gearFar.innerHTML = ''; farItems.forEach(item => gearFar.appendChild(buildImg(item)));

                overlay.classList.add('visible');
                if (farItems.length > 0) gearFar.classList.add('visible');
            }, 300);
        }

        function selectChar(idx, el) {
            document.querySelectorAll('.char-card:not(.locked)').forEach(c => c.classList.remove('selected'));
            el.classList.add('selected');
            state.selectedCharIdx = idx;
            updateBeachOverlay();
            savePerfilDB();
        }

        function renderEquipo() {
            document.getElementById('equipo-list').innerHTML = EQUIPO_ITEMS.map(item => `
      <div class="equipo-item">
        <div class="equipo-left">
          <div class="equipo-icon">${item.icon}</div>
          <div>
            <div class="equipo-name">${item.name}</div>
            <div class="equipo-val" id="eq-${item.key}">${state.equipo[item.key] || 'Sin configurar'}</div>
          </div>
        </div>
        <button class="equipo-edit" onclick="editEquipo('${item.key}', '${item.name}')">Editar</button>
      </div>`).join('');
        }

        function editEquipo(key, name) {
            const current = state.equipo[key] || '';
            const newVal = prompt('¿Qué ' + name.toLowerCase() + ' tienes?\n(Escribe cualquier cosa para mostrar la imagen. Deja vacío para quitarla.)', current);
            if (newVal === null) return;
            state.equipo[key] = newVal.trim();
            document.getElementById('eq-' + key).textContent = newVal.trim() || 'Sin configurar';
            updateBeachOverlay();

            fetch('api/wavepilot.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'save_equipo', clave: key, valor: newVal.trim() })
            }).catch(() => {});
        }

        /* LOGROS Y NAVEGACIÓN (Sin cambios) */
        const LOGROS = [
            { icon: '⚡', name: 'Primera ola', desc: 'Completa tu 1ª misión', unlocked: false },
            { icon: '🎯', name: 'En racha', desc: '5 misiones completadas', unlocked: false },
            { icon: '🐢', name: 'Tortuga Marina', desc: '50 sesiones en el agua', unlocked: false },
            { icon: '👑', name: 'Rey de las olas', desc: 'Alcanza nivel 25', unlocked: false },
            { icon: '🌺', name: 'Hibiscus Rider', desc: 'Tabla rosa 10 veces', unlocked: false },
            { icon: '🌇', name: 'Sunset Hunter', desc: 'Surfea 5 atardeceres', unlocked: false },
        ];

        function checkAchievements() {
            if (state.completedMissions.size >= 1 && !LOGROS[0].unlocked) unlock(0);
            if (state.completedMissions.size >= 5 && !LOGROS[1].unlocked) unlock(1);
            if (state.sessions >= 50 && !LOGROS[2].unlocked) unlock(2);
            if (state.level >= 25 && !LOGROS[3].unlocked) unlock(3);
            renderLogros();
        }
        function unlock(idx) {
            LOGROS[idx].unlocked = true;
            fetch('api/wavepilot.php', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({action:'unlock_logro', logro_idx:idx}) }).catch(()=>{});
        }
        function renderLogros() {
            document.getElementById('logros-grid').innerHTML = LOGROS.map(l => `
      <div class="logro-card ${l.unlocked ? '' : 'locked'}">
        <div class="logro-icon">${l.icon}</div>
        <div>
          <div class="logro-name">${l.name}</div>
          <div class="logro-desc">${l.desc}</div>
        </div>
      </div>`).join('');
        }

        function showScreen(name, btn) {
            document.querySelectorAll('.screen').forEach(s => s.classList.remove('active'));
            document.querySelectorAll('.nav-btn').forEach(b => b.classList.remove('active'));
            document.getElementById('screen-' + name).classList.add('active');
            if (btn) btn.classList.add('active');
        }

        /* INIT */
        async function init() {
            loadLocalState(); // Carga el Tamagotchi y las Rachas
            try {
                const res = await fetch('api/wavepilot.php?action=load');
                const data = await res.json();
                if (data.ok && data.perfil) {
                    const p = data.perfil;
                    state.xp = parseInt(p.xp) || 0;
                    state.level = parseInt(p.nivel) || 1;
                    state.xpToNextLevel = parseInt(p.xp_para_siguiente) || 500;
                    state.sessions = parseInt(p.sesiones_surf) || 0;
                    state.selectedCharIdx = parseInt(p.personaje_idx) || 0;

                    if (Array.isArray(data.misiones)) data.misiones.forEach(id => state.completedMissions.add(id));
                    if (data.equipo) Object.keys(state.equipo).forEach(k => { if (data.equipo[k] !== undefined) state.equipo[k] = data.equipo[k]; });
                    if (Array.isArray(data.logros)) data.logros.forEach(idx => { if (LOGROS[idx]) LOGROS[idx].unlocked = true; });

                    document.querySelectorAll('.char-card').forEach((c, i) => { c.classList.toggle('selected', i === state.selectedCharIdx); });
                }
                checkAchievements();
                updateBeachOverlay();
                updateXPBar();
                updateStats();
                renderLogros();
            } catch (e) { console.warn('Error backend:', e); }

            refreshAllMissions();
            renderEquipo();
            renderLogros();
            updateXPBar();
            updateStats();
            updateBeachOverlay();
        }

        async function savePerfilDB() {
            try {
                await fetch('api/wavepilot.php', {
                    method: 'POST', headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        action: 'save_perfil', personaje: state.selectedCharIdx, xp: state.xp,
                        nivel: state.level, xp_para_siguiente: state.xpToNextLevel, sesiones_surf: state.sessions
                    })
                });
            } catch (e) {}
        }

        init();

        async function resetGame() {
            if (!confirm('¿Reiniciar TODO tu progreso? Perderás tu XP, nivel, equipo, logros y a Tarta.')) return;
            localStorage.removeItem('wavepilot_extended_state');
            localStorage.removeItem('wavepilot_missions');
            try {
                const res = await fetch('api/wavepilot.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ action: 'reset' }) });
                if (res.ok) { alert('Progreso reiniciado.'); location.reload(); } else alert('Error.');
            } catch (err) { alert('Error de conexión.'); }
        }
    </script>
</body>

</html>