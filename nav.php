<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    
    <!-- Primary Meta Tags -->
    <title><?= isset($page_title) ? htmlspecialchars($page_title) : 'HAVENTRA - Global Property & Accommodation Marketplace' ?></title>
    <meta name="title" content="<?= isset($page_title) ? htmlspecialchars($page_title) : 'HAVENTRA - Global Property & Accommodation Marketplace' ?>">
    <meta name="description" content="<?= isset($page_description) ? htmlspecialchars($page_description) : 'HAVENTRA is an international real estate and accommodation marketplace connecting property owners, landlords, real estate agencies, property managers, Airbnb hosts and prospective clients. Find, rent, buy, sell and book properties worldwide.' ?>">
    <meta name="keywords" content="real estate, property marketplace, houses for rent, apartments for rent, buy house, airbnb, short stay, property management, real estate agencies, HAVENTRA">
    <meta name="robots" content="index, follow">
    <meta name="language" content="English">
    <meta name="author" content="HAVENTRA">
    <link rel="canonical" href="<?= isset($page_canonical) ? htmlspecialchars($page_canonical) : 'https://haventra.com/' ?>">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= isset($page_canonical) ? htmlspecialchars($page_canonical) : 'https://haventra.com/' ?>">
    <meta property="og:title" content="<?= isset($page_title) ? htmlspecialchars($page_title) : 'HAVENTRA - Global Property & Accommodation Marketplace' ?>">
    <meta property="og:description" content="<?= isset($page_description) ? htmlspecialchars($page_description) : 'Your Global Property Partner. Find, rent, buy, book properties and connect with trusted professionals worldwide.' ?>">
    <meta property="og:image" content="logo.png">
    <meta property="og:site_name" content="HAVENTRA">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?= isset($page_canonical) ? htmlspecialchars($page_canonical) : 'https://haventra.com/' ?>">
    <meta property="twitter:title" content="<?= isset($page_title) ? htmlspecialchars($page_title) : 'HAVENTRA - Global Property & Accommodation Marketplace' ?>">
    <meta property="twitter:description" content="<?= isset($page_description) ? htmlspecialchars($page_description) : 'Your Global Property Partner. Find, rent, buy, book properties and connect with trusted professionals worldwide.' ?>">
    <meta property="twitter:image" content="logo.png">

    <!-- Schema.org Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "RealEstateAgent",
        "name": "HAVENTRA",
        "url": "https://haventra.com/",
        "logo": "https://haventra.com/logo.png",
        "image": "https://haventra.com/logo.png",
        "description": "HAVENTRA is an international real estate and accommodation marketplace connecting property owners, landlords, real estate agencies, property managers, Airbnb hosts and prospective clients. Find, rent, buy, sell and book properties worldwide.",
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "Nairobi",
            "addressRegion": "Nairobi County",
            "addressCountry": "KE"
        },
        "areaServed": [
            {"@type": "Country", "name": "Kenya"},
            {"@type": "Country", "name": "United Kingdom"},
            {"@type": "Country", "name": "United Arab Emirates"}
        ],
        "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "+254 700 111 222",
            "contactType": "customer service",
            "availableLanguage": ["English", "Swahili"]
        },
        "sameAs": [
            "https://haventra.com/",
            "https://haventra.com/?page=agencies",
            "https://haventra.com/?page=houses",
            "https://haventra.com/?page=airbnb"
        ],
        "foundingDate": "2024",
        "priceRange": "$$",
        "serviceType": [
            "Property Sales",
            "Property Rentals",
            "Short Stay Accommodation",
            "Property Management",
            "Real Estate Agency Services",
            "Cleaning Services"
        ]
    }
    </script>

    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": "HAVENTRA",
        "url": "https://haventra.com/",
        "potentialAction": {
            "@type": "SearchAction",
            "target": "https://haventra.com/?page=search&q={search_term_string}",
            "query-input": "required name=search_term_string"
        },
        "inLanguage": "en",
        "isPartOf": {
            "@type": "WebSite",
            "name": "HAVENTRA",
            "url": "https://haventra.com/"
        }
    }
    </script>

    <!-- Breadcrumb Schema -->
    <?php
    $breadcrumbs = isset($page_breadcrumbs) ? $page_breadcrumbs : [
        ['name' => 'Home', 'url' => 'https://haventra.com/']
    ];
    ?>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BreadcrumbList",
        "itemListElement": [
            <?php
            $bcItems = [];
            foreach ($breadcrumbs as $index => $bc) {
                $pos = $index + 1;
                $item = '{"@type": "ListItem", "position": ' . $pos . ', "name": "' . htmlspecialchars($bc['name'], ENT_QUOTES) . '"';
                if (isset($bc['url'])) {
                    $item .= ', "item": "' . htmlspecialchars($bc['url'], ENT_QUOTES) . '"';
                }
                $item .= '}';
                $bcItems[] = $item;
            }
            echo implode(",\n", $bcItems);
            ?>
        ]
    }
    </script>

    <!-- hreflang -->
    <link rel="alternate" hreflang="en" href="<?= isset($page_canonical) ? htmlspecialchars($page_canonical) : 'https://haventra.com/' ?>">
    <link rel="alternate" hreflang="en-KE" href="<?= isset($page_canonical) ? htmlspecialchars($page_canonical) : 'https://haventra.com/' ?>">
    <link rel="alternate" hreflang="x-default" href="<?= isset($page_canonical) ? htmlspecialchars($page_canonical) : 'https://haventra.com/' ?>">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;1,400&family=Montserrat:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: '#00215E',
                        navyLight: '#001540',
                        accentOrange: '#F59E0B',
                        accentBlue: '#005BB5',
                        accentTeal: '#059669',
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'Montserrat', 'sans-serif'],
                        serif: ['Cormorant Garamond', 'serif'],
                    }
                }
            }
        }
    </script>

    <style>
        /* =========================================
           CSS VARIABLES (Brand Colors)
           ========================================= */
        :root {
            --primary-navy: #00215E;
            --primary-navy-dark: #001540;
            --accent-orange: #F59E0B;
            --accent-blue: #005BB5;
            --text-main: #111827;
            --text-muted: #6B7280;
            --bg-body: #F3F4F6;
            --bg-glass: rgba(255, 255, 255, 0.85);
            --bg-white: #FFFFFF;
            --border-light: #E5E7EB;
            
            --shadow-sm: 0 2px 4px rgba(0,0,0,0.02);
            --shadow-md: 0 10px 30px -5px rgba(0,0,0,0.08);
            --shadow-lg: 0 20px 40px -10px rgba(0,33,94,0.15);
            
            --nav-height: 88px;
            --transition: 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            --radius-sm: 8px;
            --radius-md: 16px;
            --radius-lg: 24px;
        }

        /* =========================================
           RESET & BASE
           ========================================= */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Cormorant Garamond', sans-serif;
            color: var(--text-main);
            background-color: var(--bg-body);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        a { text-decoration: none; color: inherit; }
        ul { list-style: none; }

        /* =========================================
           NAVIGATION BAR (Glassmorphism)
           ========================================= */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: var(--nav-height);
            background: var(--bg-glass);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: var(--shadow-sm);
            z-index: 1000;
            transition: all var(--transition);
        }

        .navbar.scrolled {
            height: 72px;
            box-shadow: var(--shadow-md);
            background: rgba(255, 255, 255, 0.95);
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 clamp(16px, 4vw, 40px);
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 32px;
        }

        /* Logo */
        .nav-logo {
            display: flex;
            align-items: center;
            flex-shrink: 0;
            z-index: 1001;
            transition: transform var(--transition);
        }

        /* Mobile Center Text */
        .mobile-nav-center {
            display: none;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--primary-navy);
            white-space: nowrap;
            align-items: center;
            gap: 4px;
        }

        .mobile-nav-center .typing-text {
            color: var(--accent-orange);
            font-weight: 800;
        }

        .mobile-nav-center .cursor {
            display: inline-block;
            width: 2px;
            height: 16px;
            background-color: var(--primary-navy);
            margin-left: 2px;
            animation: blink 1s infinite;
        }

        @keyframes blink {
            0%, 50% { opacity: 1; }
            51%, 100% { opacity: 0; }
        }

        .nav-logo:hover {
            transform: scale(1.02);
        }

        .nav-logo img {
            height: 52px;
            width: auto;
            object-fit: contain;
            transition: height var(--transition);
        }

        .navbar.scrolled .nav-logo img {
            height: 44px;
        }

        /* =========================================
           DESKTOP MENU
           ========================================= */
        .nav-menu {
            display: flex;
            align-items: center;
            gap: 8px;
            height: 100%;
        }

        .nav-item {
            position: relative;
            height: 100%;
            display: flex;
            align-items: center;
        }

        .nav-link {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--primary-navy);
            padding: 10px 16px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all var(--transition);
        }

        .nav-link svg {
            width: 14px;
            height: 14px;
            transition: transform var(--transition);
        }

        /* Creative Hover Animation */
        .nav-item:hover > .nav-link {
            background-color: rgba(0, 33, 94, 0.04);
            color: var(--accent-blue);
        }

        .nav-item:hover > .nav-link svg {
            transform: rotate(180deg);
            color: var(--accent-orange);
        }

        /* =========================================
           DROPDOWNS & MEGA MENUS
           ========================================= */
        .dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            transform: translateY(12px);
            background: var(--bg-white);
            border: 1px solid var(--border-light);
            border-radius: var(--radius-md);
            padding: 16px;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: all var(--transition);
            box-shadow: var(--shadow-lg);
            min-width: 240px;
        }

        .nav-item:hover .dropdown {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
            pointer-events: auto;
        }

        /* Mega Menu Specifics */
        .dropdown.mega-menu {
            width: max-content;
            left: 0;
            transform: translateY(12px);
            padding: 32px;
            max-width: calc(100vw - 40px);
        }

        .nav-item:hover .dropdown.mega-menu {
            transform: translateY(0);
        }

        .mega-grid {
            display: grid;
            grid-template-columns: repeat(3, 240px);
            gap: 32px;
        }

        .mega-column h4 {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--accent-blue);
            margin-bottom: 16px;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .mega-column h4::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border-light);
        }

        /* Dropdown Links */
        .dropdown-list {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .dropdown-list a {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-muted);
            padding: 10px 12px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.2s ease;
        }

        .dropdown-list a::before {
            content: '';
            display: inline-block;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: var(--border-light);
            transition: all 0.2s ease;
        }

        .dropdown-list a:hover {
            background-color: rgba(245, 158, 11, 0.08);
            color: var(--primary-navy);
            padding-left: 16px;
        }

        .dropdown-list a:hover::before {
            background-color: var(--accent-orange);
            transform: scale(1.3);
        }

        /* =========================================
           ACTION BUTTONS
           ========================================= */
        .nav-actions {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-shrink: 0;
        }

        .btn-login {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--primary-navy);
            padding: 10px 20px;
            border-radius: var(--radius-sm);
            transition: all var(--transition);
        }

        .btn-login:hover {
            background-color: rgba(0, 33, 94, 0.05);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-navy), var(--accent-blue));
            color: var(--bg-white);
            padding: 12px 24px;
            border-radius: var(--radius-sm);
            font-size: 0.95rem;
            font-weight: 700;
            transition: all var(--transition);
            box-shadow: 0 4px 15px rgba(0, 91, 181, 0.3);
            position: relative;
            overflow: hidden;
        }

        .btn-primary::after {
            content: '';
            position: absolute;
            top: 0; left: -100%; width: 50%; height: 100%;
            background: linear-gradient(to right, transparent, rgba(255,255,255,0.2), transparent);
            transform: skewX(-25deg);
            transition: all 0.5s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 91, 181, 0.4);
        }

        .btn-primary:hover::after {
            left: 150%;
        }

        /* =========================================
           MOBILE TOGGLE & MENU
           ========================================= */
        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
            z-index: 1001;
        }

        .mobile-toggle span {
            display: block;
            width: 28px;
            height: 2.5px;
            background-color: var(--primary-navy);
            margin-bottom: 6px;
            border-radius: 2px;
            transition: all var(--transition);
        }

        .mobile-toggle span:last-child { margin-bottom: 0; }

        .mobile-menu-wrapper {
            display: none;
        }

        /* =========================================
           RESPONSIVE
           ========================================= */
        @media (max-width: 1100px) {
            .nav-menu, .nav-actions {
                display: none !important;
            }

            .mobile-toggle {
                display: block;
            }

            .mobile-nav-center {
                display: flex;
            }

            .mobile-menu-wrapper {
                display: block;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100vh;
                background: var(--bg-white);
                padding: calc(var(--nav-height) + 20px) 24px 40px;
                overflow-y: auto;
                transform: translateX(100%);
                transition: transform var(--transition);
                z-index: 999;
                box-shadow: -10px 0 30px rgba(0,0,0,0.1);
            }

            .mobile-menu-wrapper.active {
                transform: translateX(0);
            }

            .mobile-nav-item {
                border-bottom: 1px solid var(--border-light);
            }

            .mobile-nav-link {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 20px 0;
                font-size: 1.1rem;
                font-weight: 700;
                color: var(--primary-navy);
            }

            .mobile-nav-link svg {
                width: 20px;
                height: 20px;
                transition: transform var(--transition);
            }

            .mobile-dropdown {
                display: none;
                padding-bottom: 16px;
            }

            .mobile-dropdown.active {
                display: block;
                animation: slideDown 0.3s ease forwards;
            }

            @keyframes slideDown {
                from { opacity: 0; transform: translateY(-10px); }
                to { opacity: 1; transform: translateY(0); }
            }

            .mobile-dropdown a {
                display: block;
                padding: 12px 16px;
                font-size: 1rem;
                font-weight: 600;
                color: var(--text-muted);
                border-radius: var(--radius-sm);
            }

            .mobile-dropdown a:hover {
                background: rgba(0, 33, 94, 0.05);
                color: var(--accent-blue);
            }

            .mobile-actions {
                margin-top: 32px;
                display: flex;
                flex-direction: column;
                gap: 16px;
            }

            .mobile-actions .btn-login {
                text-align: center;
                border: 2px solid var(--border-light);
            }

            .mobile-actions .btn-primary {
                text-align: center;
            }

            .mobile-toggle.active span:nth-child(1) {
                transform: translateY(8.5px) rotate(45deg);
            }
            .mobile-toggle.active span:nth-child(2) {
                opacity: 0;
            }
            .mobile-toggle.active span:nth-child(3) {
                transform: translateY(-8.5px) rotate(-45deg);
            }
        }

        /* ============================================================
           HERO SECTION — uniform showroom camera sequence on ALL images.
           5 frames × 20s = 100s cycle.
           ============================================================ */
        .hero-section {
            position: relative;
            width: 100%;
            height: 100vh;
            min-height: 600px;
            overflow: hidden;
            background: #0b1a1f;
            display: flex;
            align-items: center;
            justify-content: center;
            -webkit-transform: translateZ(0);
            transform: translateZ(0);
        }

        .cinema-showcase { position: absolute; inset: 0; width: 100%; height: 100%; }

        .cinema-frame {
            position: absolute; inset: 0; opacity: 0;
            animation: frameFade 100s linear infinite;
            will-change: opacity;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
        }

        .cinema-frame:nth-child(1) { z-index: 5; animation-delay: 0s; }
        .cinema-frame:nth-child(2) { z-index: 4; animation-delay: 20s; }
        .cinema-frame:nth-child(3) { z-index: 3; animation-delay: 40s; }
        .cinema-frame:nth-child(4) { z-index: 2; animation-delay: 60s; }
        .cinema-frame:nth-child(5) { z-index: 1; animation-delay: 80s; }

        @keyframes frameFade {
            0%    { opacity: 0; }
            1.5%  { opacity: 1; }
            18.5% { opacity: 1; }
            20%   { opacity: 0; }
            100%  { opacity: 0; }
        }

        .cinema-image {
            width: 100%; height: 100%; object-fit: cover; display: block;
            filter: brightness(0.88) saturate(0.85);
            will-change: transform;
            transform-origin: center center;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            -webkit-transform: translate3d(0, 0, 0) scale(1.22);
            transform: translate3d(0, 0, 0) scale(1.22);
            animation: cameraShowroom 100s linear infinite;
        }

        .cinema-frame:nth-child(1) .cinema-image { animation-delay: 0s; }
        .cinema-frame:nth-child(2) .cinema-image { animation-delay: 20s; }
        .cinema-frame:nth-child(3) .cinema-image { animation-delay: 40s; }
        .cinema-frame:nth-child(4) .cinema-image { animation-delay: 60s; }
        .cinema-frame:nth-child(5) .cinema-image { animation-delay: 80s; }

        @keyframes cameraShowroom {
            0%     { transform: translate3d(0%, 0%, 0) scale(1.22); }
            2.5%   { transform: translate3d(-2.5%, 0%, 0) scale(1.20); }
            5%     { transform: translate3d(-4%, -1.5%, 0) scale(1.18); }
            7.5%   { transform: translate3d(-2%, -3%, 0) scale(1.16); }
            10%    { transform: translate3d(2%, -3%, 0) scale(1.15); }
            12.5%  { transform: translate3d(4%, -1%, 0) scale(1.16); }
            15%    { transform: translate3d(2%, 2.5%, 0) scale(1.18); }
            17%    { transform: translate3d(0%, 1%, 0) scale(1.14); }
            18.5%  { transform: translate3d(0%, 0%, 0) scale(1.16); }
            20%    { transform: translate3d(0%, 0%, 0) scale(1.22); }
            100%   { transform: translate3d(0%, 0%, 0) scale(1.22); }
        }

        .hero-overlay { position: absolute; inset: 0; background: rgba(100, 180, 200, 0.12); pointer-events: none; z-index: 2; }
        .hero-gradient { position: absolute; inset: 0; background: linear-gradient(to bottom, transparent 0%, transparent 50%, rgba(0,0,0,0.35) 85%, rgba(0,0,0,0.65) 100%); pointer-events: none; z-index: 3; }

        .hero-content { position: relative; z-index: 10; width: 100%; display: flex; align-items: center; justify-content: center; padding: 2rem; box-sizing: border-box; overflow: visible; }
        .experience-card { width: 100%; max-width: 900px; margin: 0 auto; padding: 1.5rem; box-sizing: border-box; }

        .card-arrows {
            display: grid; grid-template-columns: auto minmax(0, 1fr) auto; grid-template-rows: auto auto auto;
            align-items: center; justify-items: center; gap: 0.5rem 1rem;
            width: 100%; box-sizing: border-box;
        }

        .progress-circle { position: relative; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; }
        .progress-circle svg { position: absolute; width: 60px; height: 60px; transform: rotate(-90deg); }
        .progress-circle circle { fill: none; stroke-width: 2; cx: 30; cy: 30; r: 25; }
        .progress-circle .bg { stroke: rgba(255, 255, 255, 0.2); }
        .progress-circle .progress { stroke: #ffffff; stroke-linecap: round; stroke-dasharray: 157; stroke-dashoffset: 157; animation: circleProgress 100s linear infinite; }

        .progress-circle.up { grid-column: 2 / 3; grid-row: 1 / 2; align-self: end; margin-bottom: 0.3rem; }
        .progress-circle.down { grid-column: 2 / 3; grid-row: 3 / 4; align-self: start; margin-top: 0.3rem; }
        .progress-circle.left { grid-column: 1 / 2; grid-row: 2 / 3; justify-self: end; margin-right: 0.3rem; }
        .progress-circle.right { grid-column: 3 / 4; grid-row: 2 / 3; justify-self: start; margin-left: 0.3rem; }

        .progress-text { position: relative; color: white; font-size: 0.7rem; font-family: 'Montserrat', sans-serif; font-weight: 400; }

        @keyframes circleProgress { 0% { stroke-dashoffset: 157; } 1% { stroke-dashoffset: 155.43; } 50% { stroke-dashoffset: 78.5; } 100% { stroke-dashoffset: 0; } }

        .arrow-center-text { grid-column: 2 / 3; grid-row: 2 / 3; text-align: center; color: white; padding: 0.25rem 0.5rem; }
        .center-title { font-family: 'Cormorant Garamond', serif; font-size: clamp(2.2rem, 6vw, 4rem); font-weight: 400; line-height: 1.2; letter-spacing: 0.08em; text-shadow: 0 4px 15px rgba(0, 0, 0, 0.5); display: flex; flex-direction: column; align-items: center; gap: 0.15rem; }
        .brand { display: block; font-family: 'Montserrat', sans-serif; font-size: 0.5em; letter-spacing: 0.4em; font-weight: 300; color: #c4e2ed; text-transform: uppercase; margin-top: 0.1rem; text-shadow: 0 2px 8px rgba(0, 0, 0, 0.4); }
        .center-subtitle { font-family: 'Montserrat', sans-serif; font-size: clamp(0.6rem, 1.5vw, 0.9rem); letter-spacing: 0.3em; text-transform: uppercase; font-weight: 300; color: rgba(255, 255, 255, 0.8); margin-top: 0.5rem; border-top: 1px solid rgba(255, 255, 255, 0.25); padding-top: 0.6rem; width: 80%; margin-left: auto; margin-right: auto; }

        @media (max-width: 600px) {
            .hero-content { padding: 1rem 0.75rem; }
            .experience-card { padding: 0.75rem 0.25rem; width: 100%; max-width: 100%; }
            .progress-circle, .progress-circle svg { width: 46px; height: 46px; flex-shrink: 0; }
            .progress-circle circle { cx: 23; cy: 23; r: 19; stroke-width: 1.75; }
            .progress-text { font-size: 0.58rem; font-weight: 500; }
            .card-arrows {
                grid-template-columns: auto minmax(0, 1fr) auto;
                gap: 0.15rem 0.35rem;
                width: 100%;
            }
            .progress-circle.left { margin-right: 0; justify-self: center; }
            .progress-circle.right { margin-left: 0; justify-self: center; }
            .progress-circle.up { margin-bottom: 0; }
            .progress-circle.down { margin-top: 0; }
            .arrow-center-text { padding: 0.35rem 0.4rem; min-width: 0; }
            .center-subtitle { letter-spacing: 0.16em; font-size: 0.62rem; width: 100%; padding-top: 0.4rem; margin-top: 0.35rem; }
            .center-title { font-size: clamp(1.9rem, 9.5vw, 2.8rem); letter-spacing: 0.05em; gap: 0.1rem; }
            .brand { font-size: 0.42em; letter-spacing: 0.22em; }
        }

        /* ============================================================
           EXPLORE BY CATEGORY SECTION
           ============================================================ */
        .category-section {
            background: #F8FAFC;
            padding: 5rem 1rem;
        }

        .category-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .category-row {
            display: flex;
            gap: 1rem;
            justify-content: center;
            align-items: stretch;
            flex-wrap: nowrap;
            width: 100%;
        }

        .category-card {
            flex: 1 1 0;
            min-width: 0;
            background: #ffffff;
            border: 1px solid #E2E8F0;
            border-radius: 16px;
            padding: 2rem 1rem;
            text-align: center;
            position: relative;
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
        }

        .category-card:hover {
            transform: translateY(-8px);
            border-color: #005BB5;
            box-shadow: 0 20px 25px -5px rgba(0, 91, 181, 0.1), 0 10px 10px -5px rgba(0, 91, 181, 0.04);
        }

        .category-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 4px;
            background: linear-gradient(90deg, #F59E0B, #005BB5);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.4s ease;
        }

        .category-card:hover::before { transform: scaleX(1); }

        .cat-icon-wrapper {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: #F1F5F9;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.25rem;
            transition: all 0.4s ease;
            color: #00215E;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .category-card:hover .cat-icon-wrapper {
            background: #00215E;
            color: #ffffff;
            transform: scale(1.1) rotate(5deg);
        }

        .cat-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            color: #00215E;
            font-size: 1rem;
            line-height: 1.3;
            margin-bottom: 0.5rem;
        }

        .cat-desc {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #64748B;
            font-size: 0.8rem;
            line-height: 1.4;
        }

        @media (max-width: 768px) {
            .category-section { padding: 3rem 0.5rem; }
            .category-row { gap: 0.5rem; }
            
            .category-card { 
                padding: 1rem 0.25rem; 
                border-radius: 10px;
            }

            .cat-icon-wrapper { 
                width: 40px; 
                height: 40px; 
                font-size: 1rem; 
                margin-bottom: 0.75rem; 
            }

            .cat-title { 
                font-size: 0.65rem; 
                line-height: 1.2;
                margin-bottom: 0.2rem;
                word-wrap: break-word;
            }

            .cat-desc { 
                font-size: 0.55rem; 
                line-height: 1.2;
            }
        }

        /* ================= CONTACT & HELP CUSTOM STYLES ================= */
        * { -webkit-tap-highlight-color: transparent; }

        /* ===== Floating buttons ===== */
        .floating-btn {
            width: 56px; height: 56px;
            border-radius: 9999px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            color: white; font-size: 1.35rem;
            position: relative;
        }
        .floating-btn:hover { transform: translateY(-3px) scale(1.05); }
        .floating-btn:active { transform: scale(0.95); }

        .floating-btn.whatsapp { background: #25D366; }
        .floating-btn.whatsapp:hover { box-shadow: 0 15px 30px -5px rgba(37, 211, 102, 0.5); }

        .floating-btn.help { background: linear-gradient(135deg, #00215E, #005BB5); }
        .floating-btn.help:hover { box-shadow: 0 15px 30px -5px rgba(0, 91, 181, 0.5); }

        /* Pulse ring on help button */
        .pulse-ring::before {
            content: '';
            position: absolute; inset: -4px;
            border-radius: 9999px;
            border: 2px solid rgba(245, 158, 11, 0.8);
            animation: pulseRing 2s ease-out infinite;
        }
        @keyframes pulseRing {
            0% { transform: scale(1); opacity: 0.8; }
            100% { transform: scale(1.5); opacity: 0; }
        }

        /* Notification dot */
        .notif-dot {
            position: absolute; top: 2px; right: 2px;
            width: 14px; height: 14px;
            background: #EF4444;
            border-radius: 9999px;
            border: 2px solid white;
            animation: bounceDot 2s infinite;
        }
        @keyframes bounceDot {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.2); }
        }

        /* ===== Chat panel ===== */
        .chat-panel {
            position: fixed;
            bottom: 0; right: 0;
            width: 100%; height: 100%;
            max-width: 400px;
            max-height: calc(100vh - 40px);
            margin-top: 20px;
            z-index: 9999;
            display: flex; flex-direction: column;
            border-radius: 20px 20px 0 0;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
            transform: translateY(100%);
            opacity: 0;
            pointer-events: none;
            transition: transform 0.45s cubic-bezier(0.25, 0.8, 0.25, 1), opacity 0.3s ease;
        }
        .chat-panel.open {
            transform: translateY(0);
            opacity: 1;
            pointer-events: auto;
        }
        @media (min-width: 640px) {
            .chat-panel { bottom: 24px; right: 24px; border-radius: 20px; max-height: calc(100vh - 100px); margin-top: 0; }
        }
        @media (max-width: 639px) {
            .chat-panel { border-radius: 20px 20px 0 0; max-height: calc(100vh - 20px); margin-top: 10px; }
        }

        /* Chat messages */
        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 16px;
            background: #F8FAFC;
            display: flex;
            flex-direction: column;
            gap: 10px;
            scroll-behavior: smooth;
        }
        .chat-messages::-webkit-scrollbar { width: 5px; }
        .chat-messages::-webkit-scrollbar-track { background: transparent; }
        .chat-messages::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 3px; }

        .msg-row { display: flex; }
        .msg-row.sender { justify-content: flex-end; }
        .msg-row.receiver { justify-content: flex-start; }

        .msg-bubble {
            max-width: 78%;
            padding: 10px 14px;
            border-radius: 18px;
            font-size: 0.82rem;
            line-height: 1.45;
            word-wrap: break-word;
            animation: msgIn 0.3s ease;
            position: relative;
        }
        @keyframes msgIn {
            from { opacity: 0; transform: translateY(8px) scale(0.97); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Sender (right) — our user */
        .msg-row.sender .msg-bubble {
            background: linear-gradient(135deg, #00215E, #005BB5);
            color: white;
            border-bottom-right-radius: 6px;
            box-shadow: 0 4px 10px -2px rgba(0, 91, 181, 0.35);
        }

        /* Receiver (left) — support agent */
        .msg-row.receiver .msg-bubble {
            background: white;
            color: #1E293B;
            border-bottom-left-radius: 6px;
            border: 1px solid #E2E8F0;
            box-shadow: 0 2px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .msg-time {
            font-size: 0.6rem;
            margin-top: 3px;
            opacity: 0.65;
            display: block;
        }
        .msg-row.sender .msg-time { color: rgba(255,255,255,0.8); text-align: right; }
        .msg-row.receiver .msg-time { color: #94A3B8; }

        /* Typing indicator */
        .typing-indicator {
            display: flex; gap: 4px; padding: 12px 16px;
            background: white; border-radius: 18px; border-bottom-left-radius: 6px;
            border: 1px solid #E2E8F0;
            width: fit-content;
        }
        .typing-indicator span {
            width: 7px; height: 7px; border-radius: 9999px;
            background: #94A3B8;
            animation: typingBounce 1.2s infinite;
        }
        .typing-indicator span:nth-child(2) { animation-delay: 0.15s; }
        .typing-indicator span:nth-child(3) { animation-delay: 0.3s; }
        @keyframes typingBounce {
            0%, 60%, 100% { transform: translateY(0); opacity: 0.5; }
            30% { transform: translateY(-6px); opacity: 1; }
        }

        /* Avatar */
        .chat-avatar {
            width: 34px; height: 34px;
            border-radius: 9999px;
            background: linear-gradient(135deg, #F59E0B, #F97316);
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 0.8rem;
            flex-shrink: 0;
        }

        /* Form focus ring */
        .form-input {
            transition: all 0.25s ease;
        }
        .form-input:focus {
            outline: none;
            border-color: #005BB5;
            box-shadow: 0 0 0 4px rgba(0, 91, 181, 0.1);
        }

        /* Background decorative */
        .contact-bg::before {
            content: '';
            position: absolute; inset: 0;
            background: radial-gradient(circle at 20% 30%, rgba(245, 158, 11, 0.08), transparent 50%),
                        radial-gradient(circle at 80% 70%, rgba(0, 91, 181, 0.08), transparent 50%);
            pointer-events: none;
        }
    </style>
</head>
<body>

    <!-- Navigation -->
    <nav class="navbar" id="navbar">
        <div class="nav-container">
            
            <!-- Logo -->
            <a href="<?= BASE_PATH ?>" class="nav-logo">
                <img src="<?= BASE_PATH ?>logo.png" alt="HAVENTRA Logo">
            </a>

            <!-- Mobile Center Typing Text -->
            <div class="mobile-nav-center">
                <span class="prefix">Find</span>
                <span class="typing-text"></span>
                <span class="cursor"></span>
            </div>

            <!-- DESKTOP MENU -->
            <ul class="nav-menu">
                <!-- Menu 1: Home -->
                <li class="nav-item">
                    <a href="<?= BASE_PATH ?>" class="nav-link">Home</a>
                </li>

                <!-- Menu 2: Book Stays -->
                <li class="nav-item">
                    <a href="/haventra/airbnb/" class="nav-link">
                        Book Stays
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </a>
                    <div class="dropdown">
                        <ul class="dropdown-list">
                            <li><a href="/haventra/marketplace/?kind=property&property_type=booking">All Short Stays</a></li>
                            <li><a href="/haventra/airbnb/">Airbnb Accommodation</a></li>
                            <li><a href="/haventra/airbnb/">BnB Accommodation</a></li>
                            <li><a href="/haventra/airbnb/">Serviced Apartments</a></li>
                            <li><a href="/haventra/airbnb/">Holiday Homes</a></li>
                            <li><a href="/haventra/airbnb/">Luxury Stays</a></li>
                        </ul>
                    </div>
                </li>

                <!-- Menu 3: Professionals (Now includes Cleaning) -->
                <li class="nav-item">
                    <a href="/haventra/agencies/" class="nav-link">
                        Professionals
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </a>
                    <div class="dropdown">
                        <ul class="dropdown-list">
                            <li><a href="/haventra/marketplace/?kind=agency">All Agencies & Companies</a></li>
                            <li><a href="/haventra/agencies/?category=agency">Real Estate Agencies</a></li>
                            <li><a href="/haventra/agencies/?category=management">Property Managers</a></li>
                            <li><a href="/haventra/agencies/">Find an Agent</a></li>
                            <li><a href="/haventra/agencies/?category=cleaning">Cleaning Agencies</a></li>
                        </ul>
                    </div>
                </li>

                <!-- Menu 4: Home (swapped from Find Properties → Blog → Home) -->
                <li class="nav-item">
                    <a href="<?= BASE_PATH ?>" class="nav-link">
                        Find Properties
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </a>
                    <div class="dropdown mega-menu">
                        <div class="mega-grid">
                            <div class="mega-column">
                                <h4>Residential Rentals</h4>
                                <ul class="dropdown-list">
                                    <li><a href="/haventra/marketplace/">Real Estate Marketplace</a></li>
                                    <li><a href="/haventra/houses-for-rent/">Houses for Rent</a></li>
                                    <li><a href="/haventra/apartments-for-rent/">Apartments for Rent</a></li>
                                    <li><a href="/haventra/bedsitters-for-rent/">Bedsitters</a></li>
                                    <li><a href="/haventra/single-rooms-for-rent/">Single Rooms</a></li>
                                    <li><a href="/haventra/student-accommodation/">Student Accommodation</a></li>
                                </ul>
                            </div>
                            <div class="mega-column">
                                <h4>For Sale & Commercial</h4>
                                <ul class="dropdown-list">
                                    <li><a href="/haventra/houses-for-sale/">Houses for Sale</a></li>
                                    <li><a href="/haventra/apartments-for-sale/">Apartments for Sale</a></li>
                                    <li><a href="/haventra/land-for-sale/">Land for Sale</a></li>
                                    <li><a href="/haventra/commercial-property-for-rent/">Commercial Rentals</a></li>
                                    <li><a href="/haventra/office-space-for-rent/">Office Space</a></li>
                                </ul>
                            </div>
                            <div class="mega-column">
                                <h4>Top Locations</h4>
                                <ul class="dropdown-list">
                                    <li><a href="/haventra/locations/nairobi/">Nairobi, Kenya</a></li>
                                    <li><a href="/haventra/locations/mombasa/">Mombasa, Kenya</a></li>
                                    <li><a href="/haventra/locations/london/">London, UK</a></li>
                                    <li><a href="/haventra/locations/dubai/">Dubai, UAE</a></li>
                                    <li><a href="/haventra/locations/" style="color: var(--accent-orange);">View All Locations &rarr;</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>

                <!-- Menu 5: Contact Us -->
                <li class="nav-item">
                    <a href="<?= BASE_PATH ?>?page=contact" class="nav-link text-[#F59E0B] font-bold">Contact Us</a>
                </li>
            </ul>

            <!-- DESKTOP ACTIONS -->
            <div class="nav-actions">
                <a href="<?= BASE_PATH ?>?page=login" class="btn-login">Sign In</a>
                <a href="<?= BASE_PATH ?>?page=register" class="btn-primary">Get Started</a>
            </div>

            <!-- MOBILE TOGGLE BUTTON -->
            <button class="mobile-toggle" id="mobile-toggle" aria-label="Toggle Menu" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>

        <!-- MOBILE MENU OVERLAY -->
        <div class="mobile-menu-wrapper" id="mobile-menu">
            
            <div class="mobile-nav-item">
                <a href="<?= BASE_PATH ?>" class="mobile-nav-link">Home</a>
            </div>

            <div class="mobile-nav-item">
                <a href="#" class="mobile-nav-link mobile-dropdown-toggle">
                    Book Stays
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </a>
                <div class="mobile-dropdown">
                    <a href="/haventra/marketplace/?kind=property&property_type=booking">All Short Stays</a>
                    <a href="/haventra/airbnb/">Airbnb</a>
                    <a href="/haventra/airbnb/">BnB</a>
                    <a href="/haventra/airbnb/">Holiday Homes</a>
                </div>
            </div>

            <div class="mobile-nav-item">
                <a href="#" class="mobile-nav-link mobile-dropdown-toggle">
                    Professionals
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </a>
                <div class="mobile-dropdown">
                    <a href="/haventra/marketplace/?kind=agency">All Agencies & Companies</a>
                    <a href="/haventra/agencies/?category=agency">Real Estate Agencies</a>
                    <a href="/haventra/agencies/?category=management">Property Managers</a>
                    <a href="/haventra/agencies/">Find an Agent</a>
                    <a href="/haventra/agencies/?category=cleaning">Cleaning Agencies</a>
                </div>
            </div>

            <div class="mobile-nav-item">
                <a href="<?= BASE_PATH ?>" class="mobile-nav-link mobile-dropdown-toggle">
                    Find Properties
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </a>
                <div class="mobile-dropdown">
                    <a href="/haventra/marketplace/">Real Estate Marketplace</a>
                    <a href="/haventra/houses-for-rent/">Houses for Rent</a>
                    <a href="/haventra/houses-for-sale/">Houses for Sale</a>
                    <a href="/haventra/airbnb/">Short Stays</a>
                    <a href="/haventra/locations/">Locations</a>
                </div>
            </div>

            <div class="mobile-nav-item">
                <a href="<?= BASE_PATH ?>?page=contact" class="mobile-nav-link text-[#F59E0B] font-bold">Contact Us</a>
            </div>

            <div class="mobile-actions">
                <a href="<?= BASE_PATH ?>?page=login" class="btn-login">Sign In</a>
                <a href="<?= BASE_PATH ?>?page=register" class="btn-primary">Get Started</a>
            </div>
        </div>
    </nav>

    <!-- Spacer for fixed nav -->
    <div style="height: var(--nav-height);"></div>

    <!-- Page content injected here by index.php -->