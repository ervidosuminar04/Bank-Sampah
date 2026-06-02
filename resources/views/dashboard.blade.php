<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Realive Bank Sampah</title>
    <!-- Leaflet CSS & JS for Interactive Map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <!-- Google Fonts: Nunito, Nunito Sans, JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@700;800;900&family=Nunito+Sans:wght@400;600;700&family=JetBrains+Mono:wght@400&display=swap" rel="stylesheet">
    
    <style>
        /* Modern Premium Style System - REALIVE */
        :root {
            /* Warm Spectrum (Energy / CTA) */
            --color-solar:      #FFD700;
            --color-sunburst:   #FFA500;
            --color-ember:      #F5511E;
            --color-flame:      #E63946;

            /* Green Spectrum (Brand / Growth) */
            --color-lime:       #C8E000;
            --color-sprout:     #7DB825;
            --color-forest:     #2D6A2D;
            --color-canopy:     #1A3A1A;

            /* Neutrals */
            --color-black:      #0A0A0A;
            --color-white:      #FFFFFF;
            --color-mist:       #F4F7F0;
            --color-fog:        #8A9E8A;
            --color-smoke:      #D4DDD4;

            /* Gradients */
            --gradient-brand: linear-gradient(135deg, #FFD700 0%, #7DB825 50%, #2D6A2D 100%);
            --gradient-warm:  linear-gradient(90deg, #FFD700 0%, #F5511E 60%, #E63946 100%);

            /* Semantics */
            --bg-page:          var(--color-mist);
            --bg-surface:       var(--color-white);
            --bg-dark:          var(--color-black);
            --text-primary:     var(--color-canopy);
            --text-secondary:   var(--color-forest);
            --text-muted:       var(--color-fog);
            --text-on-dark:     var(--color-white);
            --text-on-gradient: var(--color-white);
            --accent-cta:       var(--color-solar);
            --accent-success:   var(--color-forest);
            --accent-alert:     var(--color-flame);
            --border-default:   var(--color-smoke);
            --border-focus:     var(--color-sprout);

            /* Radius */
            --radius-sm:   8px;
            --radius-md:   16px;
            --radius-lg:   24px;
            --radius-xl:   36px;
            --radius-full: 9999px;

            /* Shadow */
            --shadow-sm:    0 1px 4px rgba(26, 58, 26, 0.08);
            --shadow-md:    0 4px 16px rgba(26, 58, 26, 0.12);
            --shadow-lg:    0 8px 32px rgba(26, 58, 26, 0.18);
            --shadow-xl:    0 16px 56px rgba(26, 58, 26, 0.24);
            --shadow-glow:  0 0 24px rgba(255, 215, 0, 0.35);
            --shadow-focus: 0 0 0 3px rgba(125, 184, 37, 0.45);

            --transition: all 0.26s cubic-bezier(0.4, 0, 0.2, 1);
            --ease-spring: cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Nunito Sans', sans-serif;
            background-color: var(--bg-page);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            overflow: hidden; 
            line-height: 1.55;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Nunito', sans-serif;
            line-height: 1.15;
            letter-spacing: -0.01em;
        }

        /* App Layout Grid */
        .app-layout {
            display: flex;
            width: 100vw;
            height: 100vh;
        }

        /* Sidebar Style */
        .sidebar {
            width: 256px;
            background-color: var(--color-canopy);
            color: var(--text-on-dark);
            display: flex;
            flex-direction: column;
            height: 100%;
            z-index: 100;
            box-shadow: var(--shadow-lg);
            transition: var(--transition);
        }

        .sidebar-brand {
            padding: 24px;
            font-size: 24px;
            font-family: 'Nunito', sans-serif;
            font-weight: 800;
            letter-spacing: 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            align-items: center;
            background: var(--gradient-brand);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            color: transparent; /* Fallback */
        }
        .sidebar-brand-icon {
            -webkit-text-fill-color: initial; 
            margin-right: 12px;
            font-size: 28px;
        }

        /* Profile Card inside Sidebar */
        .sidebar-profile {
            padding: 20px 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .profile-avatar {
            width: 44px;
            height: 44px;
            border-radius: var(--radius-full);
            background: var(--gradient-brand);
            color: var(--color-white);
            font-family: 'Nunito', sans-serif;
            font-size: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            box-shadow: var(--shadow-sm);
        }

        .profile-info {
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .profile-name {
            font-size: 14px;
            font-family: 'Nunito Sans', sans-serif;
            font-weight: 700;
            white-space: nowrap;
            text-overflow: ellipsis;
            overflow: hidden;
            color: var(--color-white);
        }

        .profile-role-badge {
            font-size: 10px;
            background-color: var(--color-solar);
            color: var(--color-canopy);
            padding: 2px 10px;
            border-radius: var(--radius-full);
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            align-self: flex-start;
            margin-top: 4px;
        }

        /* Sidebar Navigation Menu */
        .sidebar-menu {
            list-style: none;
            padding: 20px 0;
            flex: 1;
            overflow-y: auto;
        }

        .sidebar-menu li {
            margin-bottom: 4px;
            padding: 0 12px;
        }

        .menu-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: var(--color-fog);
            text-decoration: none;
            font-size: 14px;
            font-family: 'Nunito Sans', sans-serif;
            font-weight: 600;
            border-radius: var(--radius-sm);
            transition: var(--transition);
            cursor: pointer;
            border-left: 4px solid transparent;
        }

        .menu-link:hover {
            color: var(--color-white);
            background-color: rgba(255, 255, 255, 0.05);
        }

        .menu-link.active {
            color: var(--color-white);
            background-color: rgba(45, 106, 45, 0.4); /* forest/40 */
            border-left: 4px solid var(--color-solar); /* Simulating gradient border */
            border-image: var(--gradient-brand) 1;
            border-image-slice: 0 0 0 1;
            border-radius: 0 var(--radius-sm) var(--radius-sm) 0;
        }

        .sidebar-footer {
            padding: 20px 24px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        .btn-logout-sidebar {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background-color: rgba(230, 57, 70, 0.1);
            border: 1px solid var(--color-flame);
            color: var(--color-flame);
            width: 100%;
            padding: 12px;
            border-radius: var(--radius-full);
            font-size: 14px;
            font-family: 'Nunito Sans', sans-serif;
            font-weight: 700;
            text-decoration: none;
            transition: var(--transition);
        }

        .btn-logout-sidebar:hover {
            background-color: var(--color-flame);
            color: var(--color-white);
        }

        /* Main Viewport Panel */
        .main-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            height: 100%;
            overflow: hidden;
            background-color: var(--bg-page);
        }

        /* Panel Header */
        .panel-header {
            background-color: var(--bg-surface);
            border-bottom: 1px solid var(--color-smoke);
            padding: 16px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 10;
        }

        .header-title {
            font-size: 20px;
            font-family: 'Nunito', sans-serif;
            font-weight: 800;
            color: var(--text-primary);
        }

        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            color: var(--color-forest);
            cursor: pointer;
            margin-right: 16px;
        }
        
        .role-badge {
            background-color: var(--color-canopy);
            color: var(--color-solar);
            border: none;
            padding: 6px 16px;
            border-radius: var(--radius-full);
            font-weight: 700;
            font-size: 12px;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        /* Panel Content Area */
        .panel-content {
            flex: 1;
            padding: 32px;
            overflow-y: auto;
            position: relative;
        }

        /* Tab Content Section Container */
        .tab-content {
            display: none;
            animation: fadeSlideUp 0.35s var(--ease-spring);
        }

        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ── Map Illustration Animations ── */
        @keyframes pinDrop {
            0%   { opacity: 0; transform: translate(-50%, -100%) translateY(-60px) scale(0.3); }
            60%  { opacity: 1; transform: translate(-50%, -100%) translateY(8px) scale(1.1); }
            80%  { transform: translate(-50%, -100%) translateY(-4px) scale(0.95); }
            100% { opacity: 1; transform: translate(-50%, -100%) translateY(0) scale(1); }
        }

        @keyframes radarPulse {
            0%   { transform: translate(-50%, -50%) scale(0); opacity: 0.7; }
            100% { transform: translate(-50%, -50%) scale(4); opacity: 0; }
        }

        @keyframes pinBounce {
            0%, 100% { transform: translate(-50%, -100%) translateY(0); }
            40%      { transform: translate(-50%, -100%) translateY(-14px); }
            60%      { transform: translate(-50%, -100%) translateY(-6px); }
        }

        @keyframes popupReveal {
            0%   { opacity: 0; transform: translateX(-50%) translateY(4px) scale(0.9); }
            100% { opacity: 1; transform: translateX(-50%) translateY(-10px) scale(1); }
        }

        @keyframes mapIdlePan {
            0%   { background-position: center center; }
            25%  { background-position: 48% 52%; }
            50%  { background-position: 52% 48%; }
            75%  { background-position: 50% 53%; }
            100% { background-position: center center; }
        }

        @keyframes pinGlow {
            0%, 100% { filter: drop-shadow(0 4px 6px rgba(0,0,0,0.3)); }
            50%      { filter: drop-shadow(0 0 16px rgba(125, 184, 37, 0.6)); }
        }

        @keyframes pinShadowPulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(125, 184, 37, 0.4); }
            50%      { box-shadow: 0 0 0 12px rgba(125, 184, 37, 0); }
        }

        .map-idle-pan {
            animation: mapIdlePan 20s ease-in-out infinite;
        }

        .map-pin-animated {
            position: absolute;
            cursor: pointer;
            transform: translate(-50%, -100%);
            transition: all 0.26s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 2;
        }

        .map-pin-animated.drop-in {
            animation: pinDrop 0.6s var(--ease-spring) both;
        }

        .map-pin-animated:hover,
        .map-pin-animated.is-highlighted {
            animation: pinBounce 0.8s ease infinite;
            z-index: 20 !important;
        }

        .map-pin-animated:hover .pin-glow-ring,
        .map-pin-animated.is-highlighted .pin-glow-ring {
            animation: pinShadowPulse 1.4s ease infinite;
        }

        .map-pin-animated:hover .pin-icon-svg,
        .map-pin-animated.is-highlighted .pin-icon-svg {
            animation: pinGlow 1.6s ease infinite;
        }

        .map-popup-glass {
            display: none;
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%) translateY(-10px) scale(1);
            background: rgba(255,255,255,0.78);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            padding: 14px 18px;
            border-radius: var(--radius-sm);
            box-shadow: var(--shadow-md);
            min-width: 180px;
            text-align: center;
            border: 1px solid rgba(255,255,255,0.5);
            z-index: 30;
            pointer-events: none;
        }

        .map-popup-glass.is-visible {
            display: block;
            animation: popupReveal 0.35s var(--ease-spring) both;
        }

        .map-radar-pulse {
            position: absolute;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: rgba(125, 184, 37, 0.35);
            border: 2px solid var(--color-sprout);
            z-index: 3;
            pointer-events: none;
        }

        .map-radar-pulse::before,
        .map-radar-pulse::after {
            content: '';
            position: absolute;
            top: 50%; left: 50%;
            width: 100%; height: 100%;
            border-radius: 50%;
            border: 2px solid var(--color-sprout);
            animation: radarPulse 2.5s ease-out infinite;
        }

        .map-radar-pulse::after {
            animation-delay: 1.25s;
        }

        .pin-glow-ring {
            position: absolute;
            bottom: -2px;
            left: 50%;
            transform: translateX(-50%);
            width: 16px;
            height: 6px;
            border-radius: 50%;
            background: rgba(125, 184, 37, 0.25);
        }

        /* Card Elements */
        .welcome-section {
            background: var(--gradient-brand);
            border-radius: var(--radius-xl);
            padding: 32px 40px;
            margin-bottom: 32px;
            box-shadow: var(--shadow-lg);
            position: relative;
            overflow: hidden;
            color: var(--color-white);
        }

        /* Adding a light pattern overlay using CSS */
        .welcome-section::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: url('{{ asset("images/Pattern_2_3x.png") }}');
            background-size: cover;
            background-position: center;
            opacity: 0.7;
            pointer-events: none;
        }

        .welcome-text {
            position: relative;
            z-index: 2;
        }

        .welcome-text h1 {
            font-size: 30px;
            font-weight: 900;
            color: var(--color-white);
            margin-bottom: 8px;
        }

        .welcome-text p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 16px;
            font-weight: 600;
            max-width: 600px;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }

        .card-stat {
            background-color: #FFFFFF;
            border-radius: 24px 24px 16px 16px;
            padding: 24px;
            box-shadow: var(--shadow-md);
            display: flex;
            align-items: center;
            gap: 20px;
            transition: var(--transition);
            border: none;
            border-left: 3px solid var(--color-sprout);
        }

        .card-stat:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: var(--radius-full);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
        }

        .stat-content h3 {
            font-size: 12px;
            color: var(--color-fog);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 6px;
            font-weight: 700;
            font-family: 'Nunito Sans', sans-serif;
        }

        .stat-content p {
            font-size: 24px;
            font-weight: 800;
            color: var(--text-primary);
            font-family: 'Nunito', sans-serif;
        }
        
        .stat-content .data-mono {
            font-family: 'JetBrains Mono', monospace;
        }

        .icon-accent-1 { background-color: rgba(125, 184, 37, 0.15); color: var(--color-forest); }
        .icon-accent-2 { background-color: rgba(255, 215, 0, 0.15); color: var(--color-sunburst); }
        .icon-accent-3 { background-color: rgba(26, 58, 26, 0.1); color: var(--color-canopy); }
        .icon-accent-4 { background-color: rgba(230, 57, 70, 0.1); color: var(--color-flame); }

        /* UI Blocks */
        .ui-block {
            background-color: #FFFFFF;
            border-radius: var(--radius-md);
            padding: 32px;
            box-shadow: var(--shadow-md);
            border: none;
            margin-bottom: 32px;
        }

        .block-title {
            font-size: 20px;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 2px solid var(--color-mist);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* E-Gamifikasi Center */
        .gamifikasi-card {
            background: var(--color-solar);
            color: var(--color-canopy);
            border-radius: var(--radius-lg);
            padding: 32px;
            box-shadow: var(--shadow-glow);
            margin-bottom: 32px;
            position: relative;
            overflow: hidden;
        }
        
        /* Dark pattern approximation */
        .gamifikasi-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: radial-gradient(rgba(26, 58, 26, 0.05) 2px, transparent 2px);
            background-size: 20px 20px;
            pointer-events: none;
            opacity: 0.8;
        }

        .gamifikasi-card > * {
            position: relative;
            z-index: 2;
        }

        .gamifikasi-title {
            font-size: 14px;
            font-family: 'Nunito Sans', sans-serif;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-weight: 700;
            margin-bottom: 16px;
            color: var(--color-sprout);
        }

        .gamifikasi-points {
            font-family: 'Nunito', sans-serif;
            font-size: 48px;
            font-weight: 900;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--color-canopy);
            text-shadow: 0 0 20px rgba(26, 58, 26, 0.1);
        }

        .gamifikasi-points span { font-size: 20px; font-weight: 700; color: var(--color-canopy); opacity: 0.9; }

        @keyframes pointRise {
            0% { transform: scale(1) translateY(0); opacity: 0.5; }
            50% { transform: scale(1.2) translateY(-10px); opacity: 1; }
            100% { transform: scale(1) translateY(0); opacity: 1; }
        }

        .animate-pointRise {
            animation: pointRise 0.45s var(--ease-spring);
        }

        .level-progress {
            margin-top: 20px;
            margin-bottom: 24px;
        }

        .level-meta {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
            color: rgba(255, 255, 255, 0.8);
        }

        .level-bar {
            background-color: rgba(255, 255, 255, 0.1);
            height: 12px;
            border-radius: var(--radius-full);
            overflow: hidden;
        }

        .level-fill {
            background: var(--gradient-brand);
            height: 100%;
            border-radius: var(--radius-full);
            transition: width 0.8s var(--ease-spring);
        }

        /* Badges Collection */
        .badges-title {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 12px;
            font-weight: 700;
            color: var(--color-mist);
        }

        .badges-row {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
        }

        .badge-circle {
            width: 56px;
            height: 56px;
            border-radius: var(--radius-full);
            background-color: rgba(255, 255, 255, 0.05);
            border: 2px dashed rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            position: relative;
            cursor: pointer;
            transition: var(--transition);
        }

        .badge-circle.active {
            background-color: var(--color-solar);
            border: 2px solid var(--color-solar);
            box-shadow: var(--shadow-glow);
        }

        .badge-circle:hover .tooltip {
            visibility: visible;
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }

        .tooltip {
            visibility: hidden;
            background-color: var(--color-canopy);
            color: var(--color-white);
            text-align: center;
            padding: 8px 12px;
            border-radius: var(--radius-sm);
            position: absolute;
            z-index: 10;
            bottom: calc(100% + 12px);
            left: 50%;
            transform: translateX(-50%) translateY(8px);
            opacity: 0;
            transition: var(--transition);
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
            box-shadow: var(--shadow-md);
        }

        /* Form Controls */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--border-default);
            border-radius: var(--radius-sm);
            font-size: 16px;
            color: var(--text-primary);
            outline: none;
            font-family: inherit;
            transition: var(--transition);
            background-color: var(--color-white);
        }

        .form-control:focus {
            border-color: var(--border-focus);
            box-shadow: var(--shadow-focus);
        }

        /* Buttons */
        .btn-submit {
            background-color: var(--accent-cta);
            color: var(--color-canopy);
            border: none;
            padding: 14px 24px;
            border-radius: var(--radius-full);
            font-size: 16px;
            font-weight: 700;
            font-family: 'Nunito Sans', sans-serif;
            cursor: pointer;
            width: 100%;
            transition: var(--transition);
            min-height: 48px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-submit:hover {
            background-color: var(--color-sunburst);
            transform: scale(1.02);
            box-shadow: var(--shadow-glow);
        }

        .btn-action-sm {
            padding: 8px 16px;
            border-radius: var(--radius-full);
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: var(--transition);
            font-family: 'Nunito Sans', sans-serif;
            min-height: 40px;
        }

        .btn-success { background-color: var(--color-solar); color: var(--color-canopy); box-shadow: var(--shadow-sm); }
        .btn-success:hover { background-color: var(--color-sunburst); box-shadow: var(--shadow-glow); transform: scale(1.02); }
        
        .btn-danger { background-color: rgba(230, 57, 70, 0.1); color: var(--color-flame); border: 1px solid var(--color-flame); }
        .btn-danger:hover { background-color: var(--color-flame); color: var(--color-white); }

        .btn-secondary { background-color: var(--color-white); color: var(--color-forest); border: 2px solid var(--color-forest); }
        .btn-secondary:hover { background-color: var(--color-mist); border-color: var(--color-sprout); }

        .btn-gradient { background: var(--gradient-brand); color: var(--color-white); box-shadow: var(--shadow-md); }
        .btn-gradient:hover { transform: scale(1.02); box-shadow: var(--shadow-lg); }

        /* Table Style */
        .table-wrap {
            width: 100%;
            overflow-x: auto;
            border-radius: var(--radius-md);
            border: 1px solid var(--color-smoke);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            text-align: left;
        }

        th {
            background-color: var(--color-mist);
            padding: 16px;
            font-weight: 700;
            color: var(--color-canopy);
            border-bottom: 2px solid var(--color-smoke);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-size: 12px;
        }

        td {
            padding: 16px;
            border-bottom: 1px solid var(--color-smoke);
            color: var(--text-primary);
        }
        
        td code {
            font-family: 'JetBrains Mono', monospace;
            background-color: var(--color-mist);
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 13px;
        }

        tr:hover td {
            background-color: rgba(244, 247, 240, 0.5); /* mist with opacity */
        }

        /* Status & Distance Badges */
        .status-badge {
            font-size: 10px;
            padding: 4px 12px;
            border-radius: var(--radius-full);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .status-badge.open { background-color: rgba(125, 184, 37, 0.15); color: var(--color-forest); }
        .status-badge.closed { background-color: rgba(230, 57, 70, 0.1); color: var(--color-flame); }

        .distance-badge {
            font-size: 13px;
            font-weight: 700;
            background-color: var(--color-solar);
            color: var(--color-canopy);
            padding: 6px 14px;
            border-radius: var(--radius-full);
            box-shadow: var(--shadow-sm);
        }

        /* GPS Geolokasi Module */
        .gps-section {
            background-color: var(--color-white);
            border: 2px solid var(--color-smoke);
            border-radius: var(--radius-lg);
            padding: 24px;
        }

        .gps-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .gps-btn {
            background-color: var(--color-forest);
            color: var(--color-white);
            border: none;
            padding: 10px 20px;
            border-radius: var(--radius-full);
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: var(--transition);
        }

        .gps-btn:hover {
            background-color: var(--color-canopy);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .gps-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
            margin-top: 20px;
        }

        .gps-item {
            background-color: var(--color-white);
            border: 1px solid var(--color-smoke);
            padding: 16px 20px;
            border-radius: var(--radius-md);
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--shadow-sm);
            flex-wrap: wrap;
            gap: 16px;
            transition: var(--transition);
        }
        
        .gps-item:hover {
            border-color: var(--color-sprout);
            box-shadow: var(--shadow-md);
        }

        .gps-meta h4 {
            font-size: 16px;
            font-weight: 800;
            color: var(--color-forest);
            margin-bottom: 4px;
        }

        .gps-meta p {
            font-size: 14px;
            color: var(--color-fog);
        }

        /* Alert styling */
        .alert {
            padding: 16px 24px;
            border-radius: var(--radius-md);
            margin-bottom: 32px;
            font-size: 16px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: var(--shadow-sm);
            animation: fadeSlideUp 0.4s var(--ease-spring);
        }

        .alert-success { background-color: var(--color-mist); border-left: 6px solid var(--color-sprout); color: var(--color-forest); }
        .alert-error { background-color: rgba(230, 57, 70, 0.05); border-left: 6px solid var(--color-flame); color: var(--color-flame); }

        /* Responsive Mobile Layout adjustments */
        @media (max-width: 1024px) {
            .sidebar {
                position: fixed;
                left: -280px;
                top: 0;
                bottom: 0;
                height: 100%;
            }

            .sidebar.mobile-active {
                left: 0;
            }

            .mobile-toggle {
                display: block;
            }
        }
    </style>
</head>
<body>

    <div class="app-layout">
        
        <!-- ======================================================= -->
        <!--                   🌿 LEFT SIDEBAR                        -->
        <!-- ======================================================= -->
        <aside class="sidebar">
            <div class="sidebar-brand">
                <img src="{{ asset('images/white logo@4x.png') }}" alt="Realive Logo" style="height: 32px; margin: 12px 0;">
            </div>
            
            <div class="sidebar-profile">
                <div class="profile-avatar">
                    {{ substr($userType === 'admin' ? $user->admin_nama : $user->nasabah_nama, 0, 1) }}
                </div>
                <div class="profile-info">
                    <span class="profile-name">{{ $userType === 'admin' ? $user->admin_nama : $user->nasabah_nama }}</span>
                    <span class="profile-role-badge">{{ $userType }}</span>
                </div>
            </div>

            <ul class="sidebar-menu">
                @if($userType === 'admin')
                    <!-- Admin Options -->
                    <li>
                        <a class="menu-link active" data-tab="tab-overview" onclick="switchTab(event, 'tab-overview')">
                            🏠 Ringkasan
                        </a>
                    </li>
                    <li>
                        <a class="menu-link" data-tab="tab-persetujuan" onclick="switchTab(event, 'tab-persetujuan')">
                            🔔 Persetujuan
                        </a>
                    </li>
                    <li>
                        <a class="menu-link" data-tab="tab-pengepul" onclick="switchTab(event, 'tab-pengepul')" style="display:flex;justify-content:space-between;align-items:center;">
                            🚛 Kelola Pengepul
                            @if(($pendingPengepul ?? collect())->count() > 0)
                                <span style="background:var(--color-flame);color:var(--color-white);border-radius:10px;padding:2px 8px;font-size:11px;font-weight:bold;">
                                    {{ $pendingPengepul->count() }}
                                </span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a class="menu-link" data-tab="tab-sampah" onclick="switchTab(event, 'tab-sampah')">
                            🏷️ Master Sampah
                        </a>
                    </li>
                    <li>
                        <a class="menu-link" data-tab="tab-geolokasi" onclick="switchTab(event, 'tab-geolokasi')">
                            📍 Master Geolokasi
                        </a>
                    </li>
                    <li>
                        <a class="menu-link" data-tab="tab-laporan" onclick="switchTab(event, 'tab-laporan')">
                            📊 Cetak Laporan
                        </a>
                    </li>
                    <li>
                        <a class="menu-link" href="{{ route('admin.setoran.index') }}" style="display:flex;justify-content:space-between;align-items:center;">
                            💸 Setoran Pengepul
                            @if(($setoranMenunggu ?? 0) > 0)
                                <span style="background:var(--color-flame);color:var(--color-white);border-radius:10px;padding:2px 8px;font-size:11px;font-weight:bold;">
                                    {{ $setoranMenunggu }}
                                </span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a class="menu-link" href="{{ route('admin.pencairan.index') }}">
                            💳 Pencairan Nasabah
                        </a>
                    </li>
                    <li>
                        <a class="menu-link" href="{{ route('admin.penukaran.index') }}" style="display:flex;justify-content:space-between;align-items:center;">
                            🎁 Penukaran & Hadiah
                            @if(($rewardsMenunggu ?? 0) > 0)
                                <span style="background:var(--color-solar);color:var(--color-canopy);border-radius:10px;padding:2px 8px;font-size:11px;font-weight:700;box-shadow:var(--shadow-glow);">
                                    {{ $rewardsMenunggu }}
                                </span>
                            @endif
                        </a>
                    </li>
                @else
                    <!-- Nasabah Options -->
                    <li>
                        <a class="menu-link active" data-tab="tab-overview" onclick="switchTab(event, 'tab-overview')">
                            🏠 Ringkasan Akun
                        </a>
                    </li>
                    <li>
                        <a class="menu-link" data-tab="tab-gps" onclick="switchTab(event, 'tab-gps')">
                            📍 Cari Pengepul (GPS)
                        </a>
                    </li>
                    <li>
                        <a class="menu-link" data-tab="tab-harga-sampah" onclick="switchTab(event, 'tab-harga-sampah')">
                            🏷️ Katalog Harga Sampah
                        </a>
                    </li>
                    <li>
                        <a class="menu-link" data-tab="tab-tarik" onclick="switchTab(event, 'tab-tarik')">
                            💸 Tarik Tabungan
                        </a>
                    </li>
                    <li>
                        <a class="menu-link" data-tab="tab-riwayat" onclick="switchTab(event, 'tab-riwayat')">
                            📈 Riwayat Transaksi
                        </a>
                    </li>
                    <li>
                        <a class="menu-link" data-tab="tab-rewards" onclick="switchTab(event, 'tab-rewards')">
                            🎁 Tukar Eco Poin
                        </a>
                    </li>
                @endif
            </ul>

            <div class="sidebar-footer">
                <a href="{{ url('/logout') }}" class="btn-logout-sidebar">🚪 Logout</a>
            </div>
        </aside>

        <!-- ======================================================= -->
        <!--                 📁 RIGHT PANEL CONTENT                  -->
        <!-- ======================================================= -->
        <main class="main-panel">
            
            <header class="panel-header">
                <div style="display:flex; align-items:center;">
                    <button class="mobile-toggle" onclick="toggleSidebar()">☰</button>
                    <div class="header-title">Dashboard Realive</div>
                </div>
                <div class="role-badge">
                    Otoritas: {{ ucfirst($userType) }}
                </div>
            </header>

            <div class="panel-content">
                
                <!-- Alerts (Flash Messages) -->
                @if(session('success'))
                    <div class="alert alert-success">
                        <span>✅</span> {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-error">
                        <span>⚠️</span> {{ session('error') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert alert-error">
                        <span>⚠️</span>
                        <ul style="padding-left: 15px; margin: 0;">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if($userType === 'admin')
                    <!-- ======================================================= -->
                    <!--                   👨💼 ADMIN SECTIONS                    -->
                    <!-- ======================================================= -->

                    <!-- 1. OVERVIEW TAB -->
                    <div id="tab-overview" class="tab-content" style="display:block;">
                        <div class="welcome-section">
                            <div class="welcome-text">
                                <h1>Selamat Datang Kembali, Admin!</h1>
                                <p>Gunakan sidebar di sebelah kiri untuk mengakses pengelolaan pengepul, verifikasi nasabah, pengaturan master data, dan cetak laporan.</p>
                            </div>
                        </div>

                        <!-- Statistics -->
                        <div class="stats-grid">
                            <div class="card-stat">
                                <div class="stat-icon icon-accent-1">👥</div>
                                <div class="stat-content">
                                    <h3>Nasabah Aktif</h3>
                                    <p>{{ $totalNasabah }} Orang</p>
                                </div>
                            </div>
                            <div class="card-stat">
                                <div class="stat-icon icon-accent-2">📦</div>
                                <div class="stat-content">
                                    <h3>Sampah Terkumpul</h3>
                                    <p>{{ number_format($totalBeratSampahBulanIni, 2, ',', '.') }} kg</p>
                                </div>
                            </div>
                            <div class="card-stat">
                                <div class="stat-icon icon-accent-3">📥</div>
                                <div class="stat-content">
                                    <h3>Total Kas Masuk</h3>
                                    <p>Rp {{ number_format($totalKasMasuk, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            <div class="card-stat">
                                <div class="stat-icon icon-accent-4">📤</div>
                                <div class="stat-content">
                                    <h3>Total Kas Keluar</h3>
                                    <p>Rp {{ number_format($totalKasKeluar, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="ui-block">
                            <h2 class="block-title">Nasabah Baru Bergabung</h2>
                            <div class="table-wrap">
                                @if($nasabahs->isEmpty())
                                    <p style="color: #888; text-align: center; padding: 20px 0;">Belum ada nasabah terdaftar.</p>
                                @else
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Nama Nasabah</th>
                                                <th>Username</th>
                                                <th>Nomor Telepon</th>
                                                <th>Email</th>
                                                <th>Tanggal Daftar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($nasabahs as $n)
                                                <tr>
                                                    <td><strong>{{ $n->nasabah_nama }}</strong></td>
                                                    <td><code>{{ $n->nasabah_username }}</code></td>
                                                    <td>{{ $n->nasabah_telepon }}</td>
                                                    <td>{{ $n->nasabah_email }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($n->nasabah_tgl_daftar)->format('d M Y') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>


                    <!-- 2. KELOLA PENGEPUL TAB -->
                    <div id="tab-pengepul" class="tab-content">

                        <!-- Verifikasi Pendaftaran Pengepul -->
                        <div class="ui-block" style="margin-bottom:24px;">
                            <h2 class="block-title" style="display:flex;justify-content:space-between;align-items:center;">
                                <span>🔔 Verifikasi Pendaftaran Pengepul</span>
                                @if(($pendingPengepul ?? collect())->count() > 0)
                                    <span style="background:var(--color-flame);color:var(--color-white);border-radius:var(--radius-full);padding:4px 14px;font-size:12px;font-weight:700;">
                                        {{ $pendingPengepul->count() }} Menunggu
                                    </span>
                                @endif
                            </h2>
                            <p style="font-size:13px; color:#666; margin-bottom:16px;">Pengepul yang mendaftar secara mandiri akan muncul di sini. Verifikasi untuk mengaktifkan akun mereka.</p>
                            
                            @if(($pendingPengepul ?? collect())->isEmpty())
                                <div style="text-align:center;padding:32px 20px;background:var(--color-mist);border-radius:var(--radius-md);border:1px dashed var(--color-smoke);">
                                    <span style="font-size:40px;display:block;margin-bottom:12px;">✅</span>
                                    <p style="color:var(--color-fog);font-size:14px;font-weight:600;">Tidak ada pendaftaran pengepul yang menunggu verifikasi.</p>
                                </div>
                            @else
                                <div class="table-wrap">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Nama</th>
                                                <th>Username</th>
                                                <th>Alamat</th>
                                                <th>Telepon</th>
                                                <th>Tanggal Daftar</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pendingPengepul as $pp)
                                            <tr>
                                                <td><strong>{{ $pp->nama }}</strong></td>
                                                <td><code>{{ $pp->username }}</code></td>
                                                <td>{{ $pp->alamat }}</td>
                                                <td>{{ $pp->telepon ?? '-' }}</td>
                                                <td>{{ $pp->created_at ? $pp->created_at->format('d M Y H:i') : '-' }}</td>
                                                <td style="display:flex;gap:6px;flex-wrap:wrap;">
                                                    <form method="POST" action="{{ route('admin.pengepul.verify', $pp->id) }}" style="display:inline;">
                                                        @csrf
                                                        <button type="submit" class="btn-action-sm btn-success">✅ Verifikasi</button>
                                                    </form>
                                                    <form method="POST" action="{{ route('admin.pengepul.reject', $pp->id) }}" 
                                                          onsubmit="return confirm('Tolak dan hapus pendaftaran {{ $pp->nama }}?')" style="display:inline;">
                                                        @csrf
                                                        <button type="submit" class="btn-action-sm btn-danger">❌ Tolak</button>
                                                    </form>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>

                        <!-- Daftar Pengepul -->
                        <div class="ui-block">
                            <h2 class="block-title">📋 Daftar Pengepul Terdaftar</h2>
                            <div class="table-wrap">
                                @if($allPengepul->isEmpty())
                                    <p style="color:#888;text-align:center;padding:20px 0;">Belum ada pengepul terdaftar.</p>
                                @else
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Nama</th>
                                                <th>Username</th>
                                                <th>Alamat</th>
                                                <th>Telepon</th>
                                                <th>Koordinat GPS</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($allPengepul as $pg)
                                            <tr>
                                                <td><strong>{{ $pg->nama }}</strong></td>
                                                <td><code>{{ $pg->username }}</code></td>
                                                <td>{{ $pg->alamat }}</td>
                                                <td>{{ $pg->telepon ?? '-' }}</td>
                                                <td>
                                                    @if($pg->latitude && $pg->longitude)
                                                        <code style="font-size:11px;">{{ $pg->latitude }}, {{ $pg->longitude }}</code>
                                                    @else
                                                        <span style="color:#aaa; font-style:italic; font-size:11.5px;">Belum aktif (GPS)</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="status-badge {{ $pg->status_aktif ? 'open' : 'closed' }}">
                                                        {{ $pg->status_aktif ? 'Aktif' : 'Nonaktif' }}
                                                    </span>
                                                </td>
                                                <td style="display:flex;gap:6px;flex-wrap:wrap;">
                                                    <!-- Tombol Edit (toggle form inline) -->
                                                    <button class="btn-action-sm btn-success"
                                                        onclick="toggleEditPengepul({{ $pg->id }})">✏️ Edit</button>
                                                    <!-- Tombol Hapus -->
                                                    <form method="POST" action="{{ route('admin.pengepul.delete', $pg->id) }}"
                                                        onsubmit="return confirm('Hapus pengepul {{ $pg->nama }}?')">
                                                        @csrf
                                                        <button type="submit" class="btn-action-sm btn-danger">🗑️ Hapus</button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <!-- Form Edit Inline (tersembunyi) -->
                                            <tr id="edit-pengepul-{{ $pg->id }}" style="display:none;background:#f9fdf9;">
                                                <td colspan="7" style="padding:16px;">
                                                    <form method="POST" action="{{ route('admin.pengepul.update', $pg->id) }}">
                                                        @csrf
                                                        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;">
                                                            <div class="form-group" style="margin:0;">
                                                                <label style="font-size:12px;">Nama</label>
                                                                <input type="text" name="nama" class="form-control" value="{{ $pg->nama }}" required>
                                                            </div>
                                                            <div class="form-group" style="margin:0;">
                                                                <label style="font-size:12px;">Telepon</label>
                                                                <input type="text" name="telepon" class="form-control" value="{{ $pg->telepon }}">
                                                            </div>
                                                            <div class="form-group" style="margin:0;">
                                                                <label style="font-size:12px;">Username</label>
                                                                <input type="text" name="username" class="form-control" value="{{ $pg->username }}" required>
                                                            </div>
                                                        </div>
                                                        <div style="display:grid;grid-template-columns:2fr 1fr 1fr;gap:12px;margin-top:8px;">
                                                            <div class="form-group" style="margin:0;">
                                                                <label style="font-size:12px;">Alamat</label>
                                                                <input type="text" name="alamat" class="form-control" value="{{ $pg->alamat }}" required>
                                                            </div>
                                                            <div class="form-group" style="margin:0;">
                                                                <label style="font-size:12px;">Password baru (kosongkan jika tidak diubah)</label>
                                                                <input type="password" name="password" class="form-control" placeholder="Opsional">
                                                            </div>
                                                            <div class="form-group" style="margin:0;">
                                                                <label style="font-size:12px;">Status</label>
                                                                <select name="status_aktif" class="form-control">
                                                                    <option value="1" {{ $pg->status_aktif ? 'selected' : '' }}>Aktif</option>
                                                                    <option value="0" {{ !$pg->status_aktif ? 'selected' : '' }}>Nonaktif</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div style="margin-top:10px;display:flex;gap:8px;">
                                                            <button type="submit" class="btn-action-sm btn-success">💾 Simpan</button>
                                                            <button type="button" class="btn-action-sm btn-secondary"
                                                                onclick="toggleEditPengepul({{ $pg->id }})">Batal</button>
                                                        </div>
                                                    </form>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- 3. PERSETUJUAN & VERIFIKASI TAB -->
                    <div id="tab-persetujuan" class="tab-content">
                        <div class="ui-block">
                            <h2 class="block-title">🔔 Persetujuan Pendaftaran Nasabah Baru (Pending)</h2>
                            <div class="table-wrap" style="margin-bottom: 30px;">
                                @if($pendingNasabahs->isEmpty())
                                    <p style="font-size: 13.5px; color: #888; text-align: center; padding: 15px 0;">Tidak ada pendaftaran baru yang tertunda.</p>
                                @else
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Nama Lengkap</th>
                                                <th>NIK Identitas</th>
                                                <th>Alamat Domisili</th>
                                                <th>Telepon</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pendingNasabahs as $pn)
                                                <tr>
                                                    <td><strong>{{ $pn->nasabah_nama }}</strong></td>
                                                    <td><code>{{ $pn->nasabah_nik }}</code></td>
                                                    <td>{{ $pn->nasabah_alamat }}</td>
                                                    <td>{{ $pn->nasabah_telepon }}</td>
                                                    <td>
                                                        <form method="POST" action="{{ route('admin.verifikasi', $pn->id_nasabah) }}" style="display:inline;">
                                                            @csrf
                                                            <button type="submit" class="btn-action-sm btn-success">✅ Setujui</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>

                            <h2 class="block-title">🔔 Persetujuan Pengajuan Tarik Uang</h2>
                            <div class="table-wrap">
                                @if($pendingTarikRequests->isEmpty())
                                    <p style="font-size: 13.5px; color: #888; text-align: center; padding: 15px 0;">Tidak ada pengajuan penarikan dana tertunda.</p>
                                @else
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Nama Nasabah</th>
                                                <th>Tanggal Pengajuan</th>
                                                <th>Jumlah Pengambilan</th>
                                                <th>Aksi Tindakan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pendingTarikRequests as $tr)
                                                <tr>
                                                    <td><strong>{{ $tr->nasabah->nasabah_nama }}</strong></td>
                                                    <td>{{ \Carbon\Carbon::parse($tr->tarik_tanggal)->format('d M Y H:i') }}</td>
                                                    <td><strong style="color:var(--color-flame)">Rp {{ number_format($tr->tarik_jumlah, 0, ',', '.') }}</strong></td>
                                                    <td>
                                                        <form method="POST" action="{{ route('admin.persetujuan_tarik', [$tr->id_tarik, 'setuju']) }}" style="display:inline;">
                                                            @csrf
                                                            <button type="submit" class="btn-action-sm btn-success">✅ Setujui</button>
                                                        </form>
                                                        <form method="POST" action="{{ route('admin.persetujuan_tarik', [$tr->id_tarik, 'tolak']) }}" style="display:inline; margin-left: 5px;">
                                                            @csrf
                                                            <button type="submit" class="btn-action-sm btn-danger">❌ Tolak</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- 4. MASTER SAMPAH TAB -->
                    <div id="tab-sampah" class="tab-content">
                        <div class="ui-block">
                            <h2 class="block-title">
                                <span>🏷️ Manajemen Master Harga Sampah</span>
                                <button onclick="openAddSampahForm()" class="btn-action-sm btn-success">➕ Tambah Sampah Baru</button>
                            </h2>
                            <p style="font-size:13px; color:#666; margin-bottom:20px;">Ubah nilai Rupiah per kg jika harga pasar sampah naik/turun, atau tambahkan jenis sampah baru.</p>
                            
                            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
                                <div class="table-wrap">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Nama Bahan</th>
                                                <th>Kategori Jenis</th>
                                                <th>Harga Per kg</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($allSampah as $sam)
                                                <tr>
                                                    <td><strong>{{ $sam->sampah_name }}</strong></td>
                                                    <td>{{ $sam->sampah_jenis }}</td>
                                                    <td><strong style="color:var(--color-canopy)">Rp {{ number_format($sam->sampah_harga_kg, 0, ',', '.') }}</strong></td>
                                                    <td>
                                                        <button onclick="openEditPriceForm({{ $sam->id_sampah }}, '{{ $sam->sampah_name }}', {{ $sam->sampah_harga_kg }})" class="btn-action-sm btn-success">✏️ Edit Harga</button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Form update price & add new -->
                                <div>
                                    <!-- Edit Harga Form -->
                                    <div id="price_edit_section" style="display:none; background-color: rgba(125, 184, 37, 0.1); padding: 20px; border-radius: var(--radius-md); border: 1px solid var(--color-sprout);">
                                        <h4 style="font-size:14px; color:var(--color-forest); margin-bottom:12px; font-weight:700;">Ubah Harga: <span id="price_edit_title"></span></h4>
                                        <form method="POST" action="{{ route('admin.master_sampah.update') }}">
                                            @csrf
                                            <input type="hidden" name="id_sampah" id="price_edit_id">
                                            <div class="form-group">
                                                <label for="price_edit_val">Harga Baru (Rp /kg)</label>
                                                <input type="number" name="sampah_harga_kg" id="price_edit_val" class="form-control" required min="0">
                                            </div>
                                            <div style="display:flex; gap:10px;">
                                                <button type="submit" class="btn-action-sm btn-success" style="padding: 8px 16px;">💾 Simpan</button>
                                                <button type="button" onclick="document.getElementById('price_edit_section').style.display='none'; document.getElementById('price_edit_placeholder').style.display='block';" class="btn-action-sm btn-secondary" style="padding: 8px 16px;">Batal</button>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Tambah Sampah Baru Form -->
                                    <div id="sampah_add_section" style="display:none; background-color: rgba(125, 184, 37, 0.1); padding: 20px; border-radius: var(--radius-md); border: 1px solid var(--color-sprout);">
                                        <h4 style="font-size:14px; color:var(--color-forest); margin-bottom:12px; font-weight:700;">Tambah Sampah Baru</h4>
                                        <form method="POST" action="{{ route('admin.master_sampah.store') }}">
                                            @csrf
                                            <div class="form-group">
                                                <label for="add_sampah_name">Nama Bahan</label>
                                                <input type="text" name="sampah_name" id="add_sampah_name" class="form-control" placeholder="Contoh: Plastik Kresek" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="add_sampah_jenis">Kategori Jenis</label>
                                                <select name="sampah_jenis" id="add_sampah_jenis" class="form-control" required>
                                                    <option value="Plastik">Plastik</option>
                                                    <option value="Kertas">Kertas</option>
                                                    <option value="Logam">Logam</option>
                                                    <option value="Kaca">Kaca</option>
                                                    <option value="Organik">Organik</option>
                                                    <option value="Lainnya">Lainnya</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="add_sampah_satuan">Satuan</label>
                                                <input type="text" name="sampah_satuan" id="add_sampah_satuan" class="form-control" value="kg" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="add_sampah_harga">Harga Per kg (Rp)</label>
                                                <input type="number" name="sampah_harga_kg" id="add_sampah_harga" class="form-control" required min="0" placeholder="Contoh: 1500">
                                            </div>
                                            <div class="form-group">
                                                <label for="add_sampah_keterangan">Keterangan</label>
                                                <textarea name="sampah_keterangan" id="add_sampah_keterangan" class="form-control" rows="3" placeholder="Opsional..."></textarea>
                                            </div>
                                            <div style="display:flex; gap:10px;">
                                                <button type="submit" class="btn-action-sm btn-success" style="padding: 8px 16px;">💾 Simpan</button>
                                                <button type="button" onclick="document.getElementById('sampah_add_section').style.display='none'; document.getElementById('price_edit_placeholder').style.display='block';" class="btn-action-sm btn-secondary" style="padding: 8px 16px;">Batal</button>
                                            </div>
                                        </form>
                                    </div>

                                    <div id="price_edit_placeholder" style="background-color: #f5f6f5; padding:20px; border-radius:var(--radius-md); text-align:center; color:#888; font-size:13px; border: 1px dashed #d1d5d1;">
                                        *Klik tombol Edit Harga di tabel untuk mengubah harga bahan, atau klik tombol Tambah Sampah Baru untuk menambah jenis sampah baru.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 5. MASTER GEOLOKASI TAB -->
                    <div id="tab-geolokasi" class="tab-content">
                        <div class="ui-block">
                            <h2 class="block-title">
                                <span>📍 Manajemen Master Geolokasi (Mitra Pengepul)</span>
                                <button onclick="toggleForm('form_tambah_lokasi')" class="btn-action-sm btn-success">➕ Tambah Lokasi Baru</button>
                            </h2>

                            <!-- Add Geolocation form -->
                            <div id="form_tambah_lokasi" style="display:none; background-color: #f5f6f5; padding: 24px; border-radius: var(--radius-md); margin-bottom: 25px; border: 1px solid #d1d5d1;">
                                <h3 style="font-size:15px; font-weight:700; margin-bottom:15px; color: var(--color-forest);">Tambah Lokasi Baru</h3>
                                <form method="POST" action="{{ route('admin.master_geolokasi.store') }}">
                                    @csrf
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                                        <div class="form-group">
                                            <label for="nama_lokasi">Nama Pengepul</label>
                                            <input type="text" name="nama_lokasi" id="nama_lokasi" class="form-control" required placeholder="Contoh: Pengepul Maju Sejahtera">
                                        </div>
                                        <div class="form-group">
                                            <label for="alamat">Alamat Lengkap</label>
                                            <input type="text" name="alamat" id="alamat" class="form-control" required placeholder="Jl. Sudirman No 25">
                                        </div>
                                    </div>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                                        <div class="form-group">
                                            <label for="latitude">Latitude Koordinat</label>
                                            <input type="number" step="0.000001" name="latitude" id="latitude" class="form-control" required placeholder="-6.182400">
                                        </div>
                                        <div class="form-group">
                                            <label for="longitude">Longitude Koordinat</label>
                                            <input type="number" step="0.000001" name="longitude" id="longitude" class="form-control" required placeholder="106.829400">
                                        </div>
                                    </div>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                                        <div class="form-group">
                                            <label for="jam_operasional">Jam Kerja Operasional</label>
                                            <input type="text" name="jam_operasional" id="jam_operasional" class="form-control" required placeholder="08:00 - 17:00">
                                        </div>
                                        <div class="form-group">
                                            <label for="status_aktif">Status Kemitraan</label>
                                            <select name="status_aktif" id="status_aktif" class="form-control" required>
                                                <option value="aktif">Aktif (Mitra Aktif)</option>
                                                <option value="nonaktif">Nonaktif (Tutup Sementara)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div style="display: flex; gap: 10px; margin-top: 10px;">
                                        <button type="submit" class="btn-action-sm btn-success" style="padding: 9px 18px;">💾 Simpan Lokasi</button>
                                        <button type="button" onclick="toggleForm('form_tambah_lokasi')" class="btn-action-sm btn-secondary" style="padding: 9px 18px;">Batal</button>
                                    </div>
                                </form>
                            </div>

                            <!-- Table list geolokasi -->
                            <div class="table-wrap">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Nama Tempat Pengepul</th>
                                            <th>Alamat</th>
                                            <th>Koordinat (Lat, Lng)</th>
                                            <th>Jam Operasional</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($allGeolokasi as $geo)
                                            <tr>
                                                <td><strong>{{ $geo->nama_lokasi }}</strong></td>
                                                <td>{{ $geo->alamat }}</td>
                                                <td><code style="font-size:11.5px;">{{ $geo->latitude }}, {{ $geo->longitude }}</code></td>
                                                <td>{{ $geo->jam_operasional }}</td>
                                                <td>
                                                    <span class="status-badge {{ $geo->status_aktif === 'aktif' ? 'open' : 'closed' }}">{{ $geo->status_aktif }}</span>
                                                </td>
                                                <td>
                                                    <button onclick="openEditGeoModal({{ $geo }})" class="btn-action-sm btn-secondary">✏️ Edit</button>
                                                    <form method="POST" action="{{ route('admin.master_geolokasi.delete', $geo->id_lokasi) }}" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus lokasi pengepul ini?')">
                                                        @csrf
                                                        <button type="submit" class="btn-action-sm btn-danger" style="margin-left:4px;">🗑️ Hapus</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- 6. CETAK LAPORAN TAB -->
                    <div id="tab-laporan" class="tab-content">
                        <div class="ui-block" style="max-width: 600px;">
                            <h2 class="block-title">📊 Cetak & Export Laporan Bulanan (PDF)</h2>
                            <p style="font-size: 13.5px; color:#555; margin-bottom: 20px; line-height: 1.5;">Pilih periode bulan dan tahun laporan operasional bank sampah. Hasil dokumen terformat rapi A4 dan siap untuk diprint fisik maupun diekspor langsung sebagai PDF.</p>
                            
                            <form method="GET" action="{{ route('admin.cetak_laporan') }}" target="_blank">
                                <div class="form-group">
                                    <label for="report_bulan">Pilih Periode Bulan</label>
                                    <select name="bulan" id="report_bulan" class="form-control" required>
                                        @for($m = 1; $m <= 12; $m++)
                                            <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}" {{ date('m') == $m ? 'selected' : '' }}>
                                                {{ date('F', mktime(0, 0, 0, $m, 10)) }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="report_tahun">Pilih Periode Tahun</label>
                                    <select name="tahun" id="report_tahun" class="form-control" required>
                                        @for($y = date('Y') - 2; $y <= date('Y') + 1; $y++)
                                            <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <button type="submit" class="btn-submit" style="background-color: var(--color-canopy); display:flex; align-items:center; justify-content:center; gap:10px;">
                                    🖨️ Generate & Buka Laporan Cetak
                                </button>
                            </form>
                        </div>
                    </div>

                @else
                    <!-- ======================================================= -->
                    <!--                 👤 NASABAH SECTIONS                     -->
                    <!-- ======================================================= -->

                    <!-- 1. OVERVIEW TAB -->
                    <div id="tab-overview" class="tab-content" style="display:block;">
                        <div class="welcome-section">
                            <div class="welcome-text">
                                <h1>Selamat Datang Pejuang Ekologis!</h1>
                                <p>Selamat berkontribusi menjaga kelestarian bumi. Tabungan sampah Anda dikonversi menjadi saldo keuangan dan poin level gamifikasi.</p>
                            </div>
                        </div>

                        <!-- Statistics -->
                        <div class="stats-grid">
                            <div class="card-stat">
                                <div class="stat-icon icon-accent-1">🎁</div>
                                <div class="stat-content">
                                    <h3>Eco Poin</h3>
                                    <p>{{ number_format($user->gamifikasi->poin_diperoleh ?? 0, 0, ',', '.') }} Poin</p>
                                </div>
                            </div>
                            <div class="card-stat">
                                <div class="stat-icon icon-accent-2">💰</div>
                                <div class="stat-content">
                                    <h3>Sisa Uang Saldo</h3>
                                    <p>Rp {{ number_format($user->tabungan->tabungan_saldo_akhir ?? 0, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            <div class="card-stat">
                                <div class="stat-icon icon-accent-3">⚖️</div>
                                <div class="stat-content">
                                    <h3>Sampah Disetor (Total)</h3>
                                    <p>{{ number_format($totalSampahDisetorKg, 2, ',', '.') }} kg</p>
                                </div>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 25px;">
                            <!-- E-Gamifikasi Center -->
                            <div class="gamifikasi-card">
                                <div class="gamifikasi-title">🏆 Status E-Gamifikasi Nasabah</div>
                                <div class="gamifikasi-points">
                                    {{ $user->gamifikasi->poin_diperoleh ?? 0 }} <span>Eco Poin</span>
                                </div>
                                <p style="font-size: 13px; opacity:0.95; margin-bottom: 15px;">
                                    Tingkat Level Anda: <strong>{{ $user->gamifikasi->level_nasabah ?? 'Pemula' }}</strong>
                                </p>

                                <div class="level-progress">
                                    <div class="level-meta">
                                        <span>Poin saat ini: {{ $user->gamifikasi->poin_diperoleh ?? 0 }} Poin</span>
                                        <span>Target Level Bintang: 500 Poin</span>
                                    </div>
                                    <div class="level-bar">
                                        @php
                                            $curPoin = $user->gamifikasi->poin_diperoleh ?? 0;
                                            $pct = min(100, ($curPoin / 500) * 100);
                                        @endphp
                                        <div class="level-fill" style="width: {{ $pct }}%"></div>
                                    </div>
                                </div>

                                <div class="badges-title">🎖️ Koleksi Lencana Digital:</div>
                                <div class="badges-row">
                                    <!-- Badge 1: Eco Starter (Setiap nasabah punya) -->
                                    <div class="badge-circle active">
                                        🌿
                                        <span class="tooltip">Eco Starter - Anggota Pejuang Kebersihan</span>
                                    </div>

                                    <!-- Badge 2: Penyetor Konsisten -->
                                    <div class="badge-circle {{ $totalSampahDisetorKg > 0 ? 'active' : '' }}">
                                        📦
                                        <span class="tooltip">Penyetor Konsisten - Melakukan setoran perdana</span>
                                    </div>

                                    <!-- Badge 3: Penyelamat Bumi -->
                                    <div class="badge-circle {{ $totalSampahDisetorKg >= 10 ? 'active' : '' }}">
                                        🌎
                                        <span class="tooltip">Penyelamat Bumi - Menabung lebih dari 10kg sampah</span>
                                    </div>
                                </div>
                            </div>

                            <!-- User Info Box -->
                            <div class="ui-block">
                                <h3 style="font-size: 14px; font-weight:700; color:var(--color-forest); margin-bottom: 12px;">ℹ️ Data Akun</h3>
                                <div style="font-size:12.5px; line-height: 1.8; color: #555;">
                                    <p>Nama: <strong>{{ $user->nasabah_nama }}</strong></p>
                                    <p>NIK: {{ $user->nasabah_nik }}</p>
                                    <p>Username: <code>{{ $user->nasabah_username }}</code></p>
                                    <p>Telepon: {{ $user->nasabah_telepon }}</p>
                                    <p>Alamat: {{ $user->nasabah_alamat }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. CARI PENGEPUL (GPS) TAB -->
                    <div id="tab-gps" class="tab-content">
                        <div style="display: flex; flex-wrap: wrap; gap: 24px;">
                            <!-- Panel Daftar Pengepul Kiri -->
                            <div style="flex: 1 1 35%; max-width: 40%; max-height: calc(100vh - 140px); overflow-y: auto; padding-right: 10px;">
                                <div class="ui-block" style="padding: 24px;">
                                    <div class="gps-section" style="border: none; padding: 0;">
                                        <div class="gps-header" style="flex-direction: column; align-items: flex-start; gap: 12px;">
                                            <div>
                                                <h3 style="font-size:15.5px; font-weight:700; color:var(--color-forest); display:flex; align-items:center; gap:8px;">📍 Cari Pengepul Mitra Terdekat</h3>
                                                <p style="font-size:12px; color:#555; margin-top:3px;">Aktifkan koordinat GPS perangkat Anda untuk menghitung radius jarak pengepul aktif secara real-time.</p>
                                            </div>
                                            <button onclick="aktifkanGPS()" class="gps-btn" style="width: 100%; justify-content: center;">📡 Aktifkan GPS</button>
                                        </div>

                                        <div id="gps_status_msg" style="font-size:12.5px; color:#666; font-style:italic; margin-top: 12px; margin-bottom: 12px;">
                                            *Tekan tombol "Aktifkan GPS" untuk melakukan pencarian berbasis lokasi.
                                        </div>

                                        <!-- GPS Sorted Output List -->
                                        <div id="gps_render_list" class="gps-list" style="display:none; margin-top: 0;"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Kontainer Peta Kanan -->
                            <div style="flex: 1 1 58%; position: sticky; top: 0;">
                                <div id="mapContainer" class="map-idle-pan" style="position: relative; width: 100%; height: calc(100vh - 140px); border-radius: var(--radius-lg); box-shadow: var(--shadow-md); background: url('{{ asset('images/map 1@4x-100.jpg') }}') center/cover no-repeat; overflow: hidden;">
                                    <!-- Map overlay idle message -->
                                    <div id="mapIdleOverlay" style="position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; background: rgba(244,247,240,0.45); backdrop-filter: blur(1px); z-index: 5; transition: all 0.5s ease;">
                                        <div style="font-size: 48px; margin-bottom: 12px; animation: pinBounce 2s ease infinite;">📍</div>
                                        <p style="font-family: 'Nunito', sans-serif; font-weight: 700; font-size: 15px; color: var(--color-canopy); text-align: center;">Aktifkan GPS untuk<br>menampilkan pengepul terdekat</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2B. KATALOG HARGA SAMPAH -->
                    <div id="tab-harga-sampah" class="tab-content">
                        <div class="ui-block">
                            <div class="block-title" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
                                <h2 style="font-size: 16px; font-weight: 800; color: var(--color-forest); display:flex; align-items:center; gap:8px;">
                                    🏷️ Daftar Harga Beli Sampah Terkini
                                </h2>
                            </div>
                            <p style="font-size: 13.5px; color: #555; margin-bottom: 20px; line-height: 1.5;">Katalog panduan jenis sampah yang dapat Anda setorkan ke bank sampah beserta harga estimasinya per satuan kilogram.</p>

                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 16px;">
                                @foreach($allSampah as $sampah)
                                    <div class="sampah-card" style="border: 1px solid var(--color-smoke); border-radius: var(--radius-md); padding: 16px; transition: var(--transition); background: var(--color-white); box-shadow: var(--shadow-sm);">
                                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                                            <div>
                                                <h4 style="font-size: 15px; font-weight: 800; color: var(--text-primary); margin-bottom: 4px;">{{ $sampah->sampah_name }}</h4>
                                                <span class="status-badge" style="background-color: rgba(125, 184, 37, 0.1); color: var(--color-forest); font-size: 10px;">{{ $sampah->sampah_jenis }}</span>
                                            </div>
                                            <div style="font-size: 20px; color: var(--color-fog);">📦</div>
                                        </div>
                                        <div style="margin-bottom: 12px;">
                                            <div style="font-size: 18px; font-weight: 800; color: var(--color-canopy);">Rp {{ number_format($sampah->sampah_harga_kg, 0, ',', '.') }}</div>
                                            <div style="font-size: 11px; color: var(--color-fog); text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700;">Harga per {{ $sampah->sampah_satuan }}</div>
                                        </div>
                                        <p style="font-size: 12px; color: #666; line-height: 1.4;">{{ $sampah->sampah_keterangan ?: 'Tidak ada keterangan.' }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- 3. TARIK TABUNGAN TAB -->
                    <div id="tab-tarik" class="tab-content">
                        <div class="ui-block" style="max-width: 550px;">
                            <h2 class="block-title">💸 Ajukan Pencairan Saldo</h2>
                            <p style="font-size: 13px; color: #666; margin-bottom: 16px; line-height: 1.5;">Masukkan nominal uang yang ingin dicairkan. Minimal pencairan <strong>Rp 100.000</strong>. Pengajuan akan diproses oleh admin.</p>

                            {{-- Info saldo saat ini --}}
                            <div style="background: rgba(125, 184, 37, 0.1); border-radius: 10px; padding: 14px 18px; margin-bottom: 20px; display:flex; justify-content:space-between; align-items:center;">
                                <div>
                                    <div style="font-size:12px;color:#555;font-weight:600;">Saldo Anda Saat Ini</div>
                                    <div style="font-size:22px;font-weight:800;color:var(--color-forest);">Rp {{ number_format($saldoNasabah ?? 0, 0, ',', '.') }}</div>
                                </div>
                                <div style="font-size:28px;">💰</div>
                            </div>

                            @if(($saldoNasabah ?? 0) < $minimalPencairan)
                                <div style="background:rgba(255, 183, 15, 0.1);border:1px solid var(--color-solar);border-radius:10px;padding:12px 16px;margin-bottom:16px;font-size:13px;color:var(--color-flame);">
                                    ⚠️ Saldo Anda belum mencukupi untuk melakukan pencairan. Minimal saldo <strong>Rp {{ number_format($minimalPencairan,0,',','.') }}</strong>.
                                </div>
                            @endif

                            <form method="POST" action="{{ route('nasabah.pencairan.store') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="tarik_jumlah">Nominal Pencairan (Rp)</label>
                                    <input type="number" name="tarik_jumlah" id="tarik_jumlah" class="form-control"
                                        min="{{ $minimalPencairan }}"
                                        max="{{ $saldoNasabah ?? 0 }}"
                                        step="1000"
                                        placeholder="Contoh: 150000" required
                                        {{ ($saldoNasabah ?? 0) < $minimalPencairan ? 'disabled' : '' }}>
                                    <span style="font-size: 11px; color:#888; display:block; margin-top:4px;">*Minimal pencairan Rp {{ number_format($minimalPencairan,0,',','.') }}</span>
                                </div>
                                <div class="form-group" style="margin-top: 15px;">
                                    <label for="tarik_bank_tujuan">Bank / E-Wallet Tujuan Transfer</label>
                                    <select name="tarik_bank_tujuan" id="tarik_bank_tujuan" class="form-control" required
                                        {{ ($saldoNasabah ?? 0) < $minimalPencairan ? 'disabled' : '' }}>
                                        <option value="" disabled selected>-- Pilih Bank / E-Wallet --</option>
                                        <option value="BCA">Bank BCA</option>
                                        <option value="Mandiri">Bank Mandiri</option>
                                        <option value="BRI">Bank BRI</option>
                                        <option value="BNI">Bank BNI</option>
                                        <option value="BSI">Bank Syariah Indonesia (BSI)</option>
                                        <option value="GoPay">GoPay</option>
                                        <option value="OVO">OVO</option>
                                        <option value="Dana">Dana</option>
                                        <option value="LinkAja">LinkAja</option>
                                        <option value="ShopeePay">ShopeePay</option>
                                    </select>
                                </div>
                                <div class="form-group" style="margin-top: 15px;">
                                    <label for="tarik_nomor_rekening">Nomor Rekening / No. HP E-Wallet</label>
                                    <input type="text" name="tarik_nomor_rekening" id="tarik_nomor_rekening" class="form-control"
                                        placeholder="Contoh: 1234567890 atau 08123456789" required
                                        {{ ($saldoNasabah ?? 0) < $minimalPencairan ? 'disabled' : '' }}>
                                </div>
                                <div class="form-group" style="margin-top: 15px; margin-bottom: 20px;">
                                    <label for="tarik_atas_nama">Nama Pemilik Rekening / Akun</label>
                                    <input type="text" name="tarik_atas_nama" id="tarik_atas_nama" class="form-control"
                                        placeholder="Contoh: Budi Susanto" required
                                        {{ ($saldoNasabah ?? 0) < $minimalPencairan ? 'disabled' : '' }}>
                                </div>
                                <button type="submit" class="btn-submit" {{ ($saldoNasabah ?? 0) < $minimalPencairan ? 'disabled style=opacity:0.5;cursor:not-allowed;' : '' }}>
                                    💸 Kirim Pengajuan Pencairan
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- 4. RIWAYAT TRANSAKSI TAB -->
                    <div id="tab-riwayat" class="tab-content">
                        <!-- Deposit History -->
                        <div class="ui-block" style="margin-bottom: 30px;">
                            <h2 class="block-title">📥 Histori Penyetoran Sampah</h2>
                            <div class="table-wrap">
                                @if($recentSetorans->isEmpty())
                                    <p style="font-size: 13.5px; color: #888; text-align: center; padding: 15px 0;">Belum pernah melakukan penyetoran sampah.</p>
                                @else
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Tanggal Setor</th>
                                                <th>Jenis Sampah</th>
                                                <th>Berat Timbangan</th>
                                                <th>Rupiah Didapat</th>
                                                <th>Catatan Petugas</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentSetorans as $rs)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($rs->setor_tanggal)->format('d M Y H:i') }}</td>
                                                    <td><strong>{{ $rs->sampah->sampah_name }}</strong></td>
                                                    <td>{{ number_format($rs->setor_berat_kg, 2, ',', '.') }} kg</td>
                                                    <td><strong style="color:var(--color-canopy)">+ Rp {{ number_format($rs->setor_harga_total, 0, ',', '.') }}</strong></td>
                                                    <td><span style="font-size:12px; color:#666;">{{ $rs->setor_keterangan }}</span></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>

                        <!-- Withdrawal History -->
                        <div class="ui-block">
                            <h2 class="block-title">📤 Histori Penarikan Uang Tabungan</h2>
                            <div class="table-wrap">
                                @if($recentPenarikans->isEmpty())
                                    <p style="font-size: 13.5px; color: #888; text-align: center; padding: 15px 0;">Belum pernah mengajukan penarikan uang.</p>
                                @else
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Tanggal Pengajuan</th>
                                                <th>Jumlah Pengambilan</th>
                                                <th>Sisa Saldo Rekening</th>
                                                <th>Rekening Tujuan</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentPenarikans as $rp)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($rp->tarik_tanggal)->format('d M Y') }}</td>
                                                    <td><strong style="color:var(--color-flame)">Rp {{ number_format($rp->tarik_jumlah, 0, ',', '.') }}</strong></td>
                                                    <td>Rp {{ number_format($rp->tarik_sisa_saldo, 0, ',', '.') }}</td>
                                                    <td>
                                                        @if($rp->tarik_bank_tujuan)
                                                            <div style="font-size: 13px; font-weight: 700; color: #333;">
                                                                {{ $rp->tarik_bank_tujuan }} - {{ $rp->tarik_nomor_rekening }}
                                                            </div>
                                                            <div style="font-size: 11.5px; color: #666; margin-top: 2px;">
                                                                a/n {{ $rp->tarik_atas_nama }}
                                                            </div>
                                                        @else
                                                            <span style="color:#aaa; font-style:italic;">Manual / Tunai</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($rp->status === 'menunggu')
                                                            <span class="status-badge" style="background-color:rgba(255, 183, 15, 0.1); color:var(--color-flame);">⏳ Menunggu</span>
                                                        @elseif($rp->status === 'disetujui')
                                                            <span class="status-badge" style="background-color:rgba(125, 184, 37, 0.1); color:var(--color-forest);">✅ Disetujui</span>
                                                        @else
                                                            <span class="status-badge" style="background-color:rgba(255, 87, 34, 0.1); color:var(--color-flame);">❌ Ditolak</span>
                                                        @endif
                                                    </td>
                                                    @if($rp->catatan)
                                                    <td style="font-size:12px;color:#888;">{{ $rp->catatan }}</td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- 5. TUKAR ECO POIN TAB -->
                    <div id="tab-rewards" class="tab-content">
                        <!-- Eco Poin Stats Card -->
                        <div style="background: var(--gradient-warm); color:#fff; border-radius: var(--radius-md); padding:20px; box-shadow: var(--shadow-glow); margin-bottom: 25px; display:flex; justify-content:space-between; align-items:center;">
                            <div>
                                <div style="font-size:12.5px; opacity:0.8; text-transform:uppercase; font-weight:700; letter-spacing:0.5px;">Saldo Poin Anda Saat Ini</div>
                                <div style="font-size:28px; font-weight:800; margin-top:5px;">{{ $user->gamifikasi->poin_diperoleh ?? 0 }} <span style="font-size:16px; font-weight:500; opacity:0.8;">Eco Poin</span></div>
                                <div style="font-size:12px; opacity:0.9; margin-top:8px;">*Poin dapat ditukarkan dengan sembako atau barang ramah lingkungan di bawah ini.</div>
                            </div>
                            <div style="font-size:42px;">🎁</div>
                        </div>

                        <!-- Catalog Grid -->
                        <h2 class="block-title" style="margin-bottom:15px; display:flex; align-items:center; gap:8px;">🛍️ Katalog Eco Rewards</h2>
                        <p style="font-size:13px; color:#666; margin-bottom:20px;">Silakan pilih barang yang ingin ditukar. Setelah menukar, Anda dapat mengambil barang langsung di kantor Bank Sampah.</p>

                        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); gap:20px; margin-bottom:30px;">
                            @forelse($allHadiahs as $h)
                                <div class="ui-block" style="display:flex; flex-direction:column; padding:18px; position:relative; overflow:hidden; border-top: 4px solid var(--color-forest); background:#fff;">
                                    <!-- Reward Title -->
                                    <h3 style="font-size:15.5px; font-weight:700; color:#333; margin-bottom:8px;">{{ $h->nama_hadiah }}</h3>
                                    <!-- Description -->
                                    <p style="font-size:12.5px; color:#666; flex-grow:1; margin-bottom:15px; line-height:1.4;">{{ $h->keterangan ?? 'Tidak ada keterangan.' }}</p>
                                    <!-- Meta Row -->
                                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px; background:#f9f9f9; padding:8px; border-radius:6px; font-size:12.5px;">
                                        <div>Biaya: <strong style="color:var(--color-forest); font-weight:700;">{{ $h->poin_butuh }} Poin</strong></div>
                                        <div>Stok: <strong style="{{ $h->stok > 0 ? 'color:var(--color-forest);' : 'color:var(--color-flame);' }}">{{ $h->stok }} pcs</strong></div>
                                    </div>
                                    <!-- Action Form -->
                                    @if($h->stok > 0)
                                        @if(($user->gamifikasi->poin_diperoleh ?? 0) >= $h->poin_butuh)
                                            <form method="POST" action="{{ route('nasabah.tukar_poin') }}" style="margin:0;">
                                                @csrf
                                                <input type="hidden" name="id_hadiah" value="{{ $h->id_hadiah }}">
                                                <div style="display:flex; gap:8px;">
                                                    <input type="number" name="jumlah" value="1" min="1" max="{{ min($h->stok, floor(($user->gamifikasi->poin_diperoleh ?? 0) / $h->poin_butuh)) }}" class="form-control" style="width:70px; padding:6px 8px; text-align:center;" required>
                                                    <button type="submit" class="btn-submit" style="margin:0; font-size:12.5px; padding:8px 12px; background:var(--color-forest); color:#fff; border:none; border-radius:6px; cursor:pointer; font-weight:700;">Tukar</button>
                                                </div>
                                            </form>
                                        @else
                                            <button disabled style="width:100%; border:none; background:#e0e0e0; color:#888; font-size:12px; padding:10px; border-radius:8px; cursor:not-allowed; font-weight:700;">Poin Tidak Cukup</button>
                                        @endif
                                    @else
                                        <button disabled style="width:100%; border:none; background:rgba(255, 87, 34, 0.1); color:var(--color-flame); font-size:12px; padding:10px; border-radius:8px; cursor:not-allowed; font-weight:700;">Stok Habis</button>
                                    @endif
                                </div>
                            @empty
                                <div style="grid-column: 1/-1; text-align:center; padding:30px; color:#aaa;">Belum ada hadiah yang ditambahkan ke katalog.</div>
                            @endforelse
                        </div>

                        <!-- Redemptions History -->
                        <div class="ui-block" style="background:#fff;">
                            <h2 class="block-title">🕒 Histori Penukaran Eco Rewards</h2>
                            <div class="table-wrap">
                                @if($recentPenukaranRewards->isEmpty())
                                    <p style="font-size: 13.5px; color: #888; text-align: center; padding: 15px 0;">Belum pernah mengajukan penukaran hadiah.</p>
                                @else
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Tanggal Tukar</th>
                                                <th>Barang Hadiah</th>
                                                <th>Jumlah</th>
                                                <th>Total Eco Poin</th>
                                                <th>Status</th>
                                                <th>Keterangan Admin</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentPenukaranRewards as $pr)
                                                <tr>
                                                     <td>{{ \Carbon\Carbon::parse($pr->tanggal_tukar)->format('d M Y') }}</td>
                                                     <td><strong>{{ $pr->hadiah->nama_hadiah ?? 'Hadiah Dihapus' }}</strong></td>
                                                     <td>{{ $pr->jumlah }} pcs</td>
                                                     <td><strong style="color:var(--color-canopy)">- {{ $pr->total_poin_ditukar }} Poin</strong></td>
                                                     <td>
                                                         @if($pr->status === 'menunggu')
                                                             <span class="status-badge" style="background-color:rgba(255, 183, 15, 0.1); color:var(--color-flame);">⏳ Menunggu</span>
                                                         @elseif($pr->status === 'diambil')
                                                             <span class="status-badge" style="background-color:rgba(125, 184, 37, 0.1); color:var(--color-forest);">✅ Sudah Diambil</span>
                                                         @else
                                                             <span class="status-badge" style="background-color:rgba(255, 87, 34, 0.1); color:var(--color-flame);">❌ Ditolak</span>
                                                         @endif
                                                     </td>
                                                     <td><span style="font-size:12px; color:#666;">{{ $pr->catatan ?? '-' }}</span></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>

                @endif

            </div>

            <footer style="margin-top:auto; padding:20px 30px; text-align:center; font-size:12px; color:#888; background:#fff; border-top: 1px solid #d1d5d1;">
                &copy; 2026 Bank Sampah Digital. Bersama Kita Selamatkan Ekosistem Hijau & Finansial Masa Depan.
            </footer>

        </main>

    </div>

    <!-- Edit Geolokasi Modal (Admin Only, triggered by JS) -->
    @if($userType === 'admin')
        <div id="edit_geo_modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
            <div class="ui-block" style="width:100%; max-width:550px; margin: 0 15px; border-top: 5px solid var(--color-canopy);">
                <h3 style="font-size:16px; font-weight:700; margin-bottom:15px; color:var(--color-forest);">Ubah Data Geolokasi</h3>
                <form id="edit_geo_form" method="POST" action="">
                    @csrf
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label for="edit_nama_lokasi">Nama Pengepul</label>
                            <input type="text" name="nama_lokasi" id="edit_nama_lokasi" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_alamat">Alamat Lengkap</label>
                            <input type="text" name="alamat" id="edit_alamat" class="form-control" required>
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label for="edit_latitude">Latitude</label>
                            <input type="number" step="0.000001" name="latitude" id="edit_latitude" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_longitude">Longitude</label>
                            <input type="number" step="0.000001" name="longitude" id="edit_longitude" class="form-control" required>
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label for="edit_jam_operasional">Jam Operasional</label>
                            <input type="text" name="jam_operasional" id="edit_jam_operasional" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_status_aktif">Status Aktif</label>
                            <select name="status_aktif" id="edit_status_aktif" class="form-control" required>
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    <div style="display:flex; gap:10px; margin-top:15px; justify-content: flex-end;">
                        <button type="submit" class="btn-action-sm btn-success" style="padding:8px 16px;">💾 Perbarui</button>
                        <button type="button" onclick="document.getElementById('edit_geo_modal').style.display='none'" class="btn-action-sm btn-secondary" style="padding:8px 16px;">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- INTERACTIVE SIDEBAR & TAB LOGICS -->
    <script>
        // --- 1. GLOBAL LAYOUT SWITCHING (TABS) ---
        document.addEventListener('DOMContentLoaded', () => {
            const savedTab = localStorage.getItem('BS_active_tab') || 'tab-overview';
            const link = document.querySelector(`.menu-link[data-tab="${savedTab}"]`);
            
            // Hide all tabs
            const contents = document.querySelectorAll('.tab-content');
            contents.forEach(c => c.style.display = 'none');
            
            // Show saved tab
            const activeSection = document.getElementById(savedTab);
            if (activeSection) {
                activeSection.style.display = 'block';
            }
            
            // Highlight active menu item
            if (link) {
                const links = document.querySelectorAll('.menu-link');
                links.forEach(l => l.classList.remove('active'));
                link.classList.add('active');
            }

        });

        // Tab Switching Engine
        function switchTab(event, tabId) {
            if (event) event.preventDefault();
            
            // Hide all tab contents
            const contents = document.querySelectorAll('.tab-content');
            contents.forEach(c => c.style.display = 'none');
            
            // Show target tab
            const target = document.getElementById(tabId);
            if (target) {
                target.style.display = 'block';
            }
            
            // Save tab state to localStorage
            localStorage.setItem('BS_active_tab', tabId);
            
            // Update active styling
            const links = document.querySelectorAll('.menu-link');
            links.forEach(l => l.classList.remove('active'));
            
            if (event) {
                event.currentTarget.classList.add('active');
            }
            
            // Close mobile sidebar if open
            const sidebar = document.querySelector('.sidebar');
            if (sidebar.classList.contains('mobile-active')) {
                sidebar.classList.remove('mobile-active');
            }

        }        // Toggle mobile sidebar
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('mobile-active');
        }

        // --- 2. ADMIN INTERACTIVE SCRIPTS ---
        @if($userType === 'admin')
            // Live Price Calculator in Deposit Timbang form
            const selectSampah = document.getElementById('id_sampah');
            const inputBerat = document.getElementById('setor_berat_kg');
            const displayPrice = document.getElementById('live_price_display');

            function updateLivePrice() {
                const opt = selectSampah.options[selectSampah.selectedIndex];
                const weight = parseFloat(inputBerat.value) || 0;
                
                if (opt && opt.dataset.price) {
                    const priceKg = parseFloat(opt.dataset.price);
                    const grandTotal = priceKg * weight;
                    displayPrice.textContent = 'Rp ' + Math.round(grandTotal).toLocaleString('id-ID');
                } else {
                    displayPrice.textContent = 'Rp 0';
                }
            }

            if (selectSampah && inputBerat) {
                selectSampah.addEventListener('change', updateLivePrice);
                inputBerat.addEventListener('input', updateLivePrice);
            }

            // Edit price inline section
            function openEditPriceForm(id, name, currentPrice) {
                const sect = document.getElementById('price_edit_section');
                const addSect = document.getElementById('sampah_add_section');
                const ph = document.getElementById('price_edit_placeholder');
                
                if (addSect) addSect.style.display = 'none';
                ph.style.display = 'none';
                sect.style.display = 'block';
                
                document.getElementById('price_edit_id').value = id;
                document.getElementById('price_edit_title').textContent = name;
                document.getElementById('price_edit_val').value = currentPrice;
                document.getElementById('price_edit_val').focus();
            }

            function openAddSampahForm() {
                const sect = document.getElementById('price_edit_section');
                const addSect = document.getElementById('sampah_add_section');
                const ph = document.getElementById('price_edit_placeholder');
                
                if (sect) sect.style.display = 'none';
                ph.style.display = 'none';
                addSect.style.display = 'block';
                
                document.getElementById('add_sampah_name').value = '';
                document.getElementById('add_sampah_harga').value = '';
                document.getElementById('add_sampah_keterangan').value = '';
                document.getElementById('add_sampah_name').focus();
            }

            // Toggle layout visibility helper
            function toggleForm(id) {
                const el = document.getElementById(id);
                el.style.display = el.style.display === 'none' ? 'block' : 'none';
            }

            // Edit geolokasi form modal popup
            function openEditGeoModal(geo) {
                const modal = document.getElementById('edit_geo_modal');
                const form = document.getElementById('edit_geo_form');
                
                // Set route action
                form.action = `/admin/master-geolokasi/update/${geo.id_lokasi}`;
                
                // Populate inputs
                document.getElementById('edit_nama_lokasi').value = geo.nama_lokasi;
                document.getElementById('edit_alamat').value = geo.alamat;
                document.getElementById('edit_latitude').value = geo.latitude;
                document.getElementById('edit_longitude').value = geo.longitude;
                document.getElementById('edit_jam_operasional').value = geo.jam_operasional;
                document.getElementById('edit_status_aktif').value = geo.status_aktif;
                
                modal.style.display = 'flex';
            }

            // Map initialization removed since dynamic GPS coordinates are self-updated by Pengepul.
        @else
            // --- 3. NASABAH INTERACTIVE SCRIPTS ---
            // GPS Geolocator Mitra Finder (Haversine Distance sorting)
            const geolokasiData = @json($activeGeolokasi);

            function calculateDistance(lat1, lon1, lat2, lon2) {
                const R = 6371; // Earth radius in km
                const dLat = (lat2 - lat1) * Math.PI / 180;
                const dLon = (lon2 - lon1) * Math.PI / 180;
                
                const a = 
                    Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                    Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * 
                    Math.sin(dLon / 2) * Math.sin(dLon / 2);
                
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                return R * c; // Distance in km
            }

            function aktifkanGPS() {
                const statusMsg = document.getElementById('gps_status_msg');
                const renderList = document.getElementById('gps_render_list');
                
                if (!navigator.geolocation) {
                    statusMsg.innerHTML = '<span style="color:var(--color-flame);">⚠️ Browser Anda tidak mendukung koordinat GPS.</span>';
                    return;
                }
                
                statusMsg.innerHTML = '🔄 Mengambil koordinat satelit GPS perangkat Anda...';
                
                navigator.geolocation.getCurrentPosition(
                    (pos) => {
                        const userLat = pos.coords.latitude;
                        const userLng = pos.coords.longitude;
                        statusMsg.innerHTML = `📍 GPS Aktif! Koordinat perangkat: <code>${userLat.toFixed(5)}, ${userLng.toFixed(5)}</code>. Diurutkan berdasarkan jarak terdekat:`;

                        // Map distance
                        const scoredList = geolokasiData.map(loc => {
                            const dist = calculateDistance(userLat, userLng, parseFloat(loc.latitude), parseFloat(loc.longitude));
                            return { ...loc, distance: dist };
                        });

                        // Sort by distance ascending
                        scoredList.sort((a, b) => a.distance - b.distance);

                        // Render output html
                        renderList.innerHTML = '';
                        scoredList.forEach(loc => {
                            const distText = loc.distance < 1 
                                ? Math.round(loc.distance * 1000) + ' meter' 
                                : loc.distance.toFixed(2) + ' km';

                            const tipeBadge = loc.tipe === 'Cabang Utama' 
                                ? '<span class="status-badge open" style="background-color:rgba(125, 184, 37, 0.1); color:var(--color-forest); margin-left: 8px;">🏛️ Cabang Utama</span>'
                                : '<span class="status-badge open" style="background-color:#e3f2fd; color:#1565c0; margin-left: 8px;">🚛 Mitra Pengepul</span>';

                            const itemHtml = `
                                <div class="gps-item" onmouseenter="highlightMapPin(${loc.id_lokasi})" onmouseleave="resetMapPin(${loc.id_lokasi})">
                                    <div class="gps-meta">
                                        <h4 style="display:flex; align-items:center;">${loc.nama_lokasi} ${tipeBadge}</h4>
                                        <p>📍 Alamat: ${loc.alamat}</p>
                                        <p style="font-size:11px; margin-top:3px; color:#555;">⏱️ Operasional: ${loc.jam_operasional} | <span class="status-badge open">Aktif</span></p>
                                    </div>
                                    <div class="distance-badge">${distText}</div>
                                </div>
                            `;
                            renderList.innerHTML += itemHtml;
                        });

                        renderList.style.display = 'flex';
                        renderMapPins(scoredList);
                    },
                    (err) => {
                        statusMsg.innerHTML = '<span style="color:var(--color-flame);">⚠️ Gagal melacak lokasi. Mohon berikan izin akses GPS/Lokasi di browser Anda.</span>';
                    }
                );
            }

            function renderMapPins(scoredList) {
                const mapContainer = document.getElementById('mapContainer');

                // Hapus overlay idle & hentikan animasi pan
                const idleOverlay = document.getElementById('mapIdleOverlay');
                if (idleOverlay) {
                    idleOverlay.style.opacity = '0';
                    setTimeout(() => idleOverlay.remove(), 500);
                }
                mapContainer.classList.remove('map-idle-pan');

                // Bersihkan pin sebelumnya
                mapContainer.innerHTML = '';

                // Tambahkan radar pulse di tengah peta (posisi user)
                const radarHtml = `<div class="map-radar-pulse" style="left: 50%; top: 50%; transform: translate(-50%, -50%);"></div>`;
                mapContainer.innerHTML += radarHtml;

                // Tambahkan label "Lokasi Anda" di dekat radar
                const youLabel = `
                    <div style="position: absolute; left: 50%; top: 50%; transform: translate(-50%, 20px); z-index: 4;
                        font-family: 'Nunito Sans', sans-serif; font-size: 11px; font-weight: 700; color: var(--color-forest);
                        background: rgba(255,255,255,0.85); backdrop-filter: blur(6px); padding: 3px 10px; border-radius: var(--radius-full); box-shadow: var(--shadow-sm);">
                        📍 Lokasi Anda
                    </div>`;
                mapContainer.innerHTML += youLabel;

                // Spread pin-pin dari pusat secara radial berdasarkan jarak relatif
                const maxDist = Math.max(...scoredList.map(l => l.distance), 1);

                scoredList.forEach((loc, index) => {
                    // Hitung posisi radial dari tengah peta berdasarkan jarak & sudut
                    const angle = (index / scoredList.length) * 2 * Math.PI - Math.PI / 2;
                    const radiusFraction = Math.min(loc.distance / maxDist, 1);
                    const spread = 32 + radiusFraction * 15; // 32-47% radius dari pusat

                    const leftPct = 50 + Math.cos(angle) * spread;
                    const topPct  = 50 + Math.sin(angle) * spread;

                    const isCabang = loc.tipe === 'Cabang Utama';
                    const pinColor = isCabang ? 'var(--color-forest)' : 'var(--color-sprout)';
                    const pinSize  = isCabang ? '42px' : '34px';
                    const delay    = 0.15 + index * 0.12; // stagger masuk

                    const pinHtml = `
                        <div id="pin-${loc.id_lokasi}" class="map-pin-animated drop-in"
                             style="left: ${leftPct.toFixed(1)}%; top: ${topPct.toFixed(1)}%; animation-delay: ${delay}s;"
                             onclick="toggleMapPopup(${loc.id_lokasi})">
                            <div class="pin-icon-svg" style="color: ${pinColor}; font-size: ${pinSize}; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.3)); transition: filter 0.3s ease;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 256 256"><path d="M128,16a88.1,88.1,0,0,0-88,88c0,75.3,80,132.17,83.41,134.55a8,8,0,0,0,9.18,0C136,236.17,216,179.3,216,104A88.1,88.1,0,0,0,128,16Zm0,112a24,24,0,1,1,24-24A24,24,0,0,1,128,128Z"></path></svg>
                            </div>
                            <div class="pin-glow-ring"></div>

                            <!-- Glassmorphism Pop-up -->
                            <div id="popup-${loc.id_lokasi}" class="map-popup-glass">
                                <h5 style="font-size:12px; font-weight:800; color:var(--color-canopy); margin-bottom:4px;">${loc.nama_lokasi}</h5>
                                <p style="font-size:10px; font-family:'JetBrains Mono', monospace; color:var(--color-forest); margin-bottom:2px;">${loc.distance.toFixed(2)} km</p>
                                <p style="font-size:9px; color:var(--color-fog);">${isCabang ? '🏛️ Cabang Utama' : '🚛 Mitra Pengepul'}</p>
                            </div>
                        </div>
                    `;
                    mapContainer.innerHTML += pinHtml;
                });
            }

            // Toggle popup saat klik pin di peta
            function toggleMapPopup(id) {
                // Tutup semua popup yang terbuka
                document.querySelectorAll('.map-popup-glass.is-visible').forEach(p => {
                    if (p.id !== 'popup-' + id) p.classList.remove('is-visible');
                });
                const popup = document.getElementById('popup-' + id);
                if (popup) popup.classList.toggle('is-visible');
            }

            // Hover interactions dari daftar kiri
            function highlightMapPin(id) {
                const pin = document.getElementById('pin-' + id);
                const popup = document.getElementById('popup-' + id);
                if (pin) {
                    pin.classList.add('is-highlighted');
                }
                if (popup) {
                    popup.classList.add('is-visible');
                }
            }

            function resetMapPin(id) {
                const pin = document.getElementById('pin-' + id);
                const popup = document.getElementById('popup-' + id);
                if (pin) {
                    pin.classList.remove('is-highlighted');
                }
                if (popup) {
                    popup.classList.remove('is-visible');
                }
            }
        @endif

        // ── Toggle form edit pengepul inline ─────────────────────
        function toggleEditPengepul(id) {
            const row = document.getElementById('edit-pengepul-' + id);
            if (row) {
                const isShowing = (row.style.display === 'none' || row.style.display === '');
                row.style.display = isShowing ? 'table-row' : 'none';
            }
        }
    </script>

</body>
</html>
