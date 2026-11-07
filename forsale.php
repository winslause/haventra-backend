<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Primary Meta Tags -->
    <title>Houses, Land & Property for Sale in Kenya | HAVENTRA For Sale</title>
    <meta name="title" content="Houses, Land & Property for Sale in Kenya | HAVENTRA For Sale">
    <meta name="description" content="Buy houses, apartments, land, townhouses, and commercial property for sale in Kenya. Browse verified listings in Nairobi, Mombasa, Kisumu and beyond. Freehold and leasehold titles available.">
    <meta name="keywords" content="property for sale Kenya, houses for sale Nairobi, land for sale, apartments for sale, townhouses for sale, buy house Kenya, commercial property for sale, freehold title, HAVENTRA for sale">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://haventra.com/forsale/">
    
    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://haventra.com/forsale/">
    <meta property="og:title" content="Houses, Land & Property for Sale in Kenya | HAVENTRA">
    <meta property="og:description" content="Buy houses, apartments, land, townhouses, and commercial property for sale in Kenya.">
    <meta property="og:image" content="https://haventra.com/logo.png">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://haventra.com/forsale/">
    <meta property="twitter:title" content="Houses, Land & Property for Sale in Kenya | HAVENTRA">
    <meta property="twitter:description" content="Buy houses, apartments, land, townhouses, and commercial property for sale in Kenya.">
    <meta property="twitter:image" content="https://haventra.com/logo.png">
    
    <!-- Schema.org for SEO -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebPage",
        "name": "Property for Sale in Kenya",
        "description": "Browse verified property listings for sale across Kenya â houses, land, apartments, and commercial.",
        "url": "https://haventra.com/forsale/",
        "publisher": {
            "@type": "Organization",
            "name": "HAVENTRA",
            "logo": "https://haventra.com/logo.png"
        }
    }
    </script>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=Montserrat:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

<script>
tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: '#00215E',
                        navyLight: '#001540',
                        accentOrange: '#F59E0B',
                        accentBlue: '#005BB5',
                        saleGold: '#B45309',
                        saleBrown: '#78350F',
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
        * { -webkit-tap-highlight-color: transparent; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* ===== HERO ===== */
        .sale-hero {
            background: linear-gradient(135deg, rgba(0, 21, 64, 0.93), rgba(120, 53, 15, 0.82)),
                        url('https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1600&q=80');
            background-size: cover;
            background-position: center;
        }

        .search-shell {
            background: white;
            border-radius: 9999px;
            box-shadow: 0 20px 50px -12px rgba(0, 0, 0, 0.4);
            transition: all 0.3s ease;
        }
        @media (max-width: 767px) { .search-shell { border-radius: 24px; } }

        .search-field {
            transition: background 0.25s ease;
            border-radius: 9999px;
        }
        .search-field:hover { background: #F3F4F6; }
        .search-field.active { background: #F3F4F6; box-shadow: inset 0 0 0 2px #B45309; }

        .search-input {
            background: transparent; border: none; outline: none;
            width: 100%; font-size: 0.85rem; color: #1F2937;
        }
        .search-input::placeholder { color: #9CA3AF; }

        /* ===== CHIPS ===== */
        .chip {
            padding: 6px 14px; border-radius: 9999px;
            font-size: 0.75rem; font-weight: 500;
            border: 1px solid #E5E7EB; background: white; color: #4B5563;
            transition: all 0.25s ease; cursor: pointer; white-space: nowrap;
        }
        .chip:hover { border-color: #B45309; color: #B45309; }
        .chip.active {
            background: #B45309; border-color: #B45309; color: white;
            box-shadow: 0 4px 12px rgba(180, 83, 9, 0.3);
        }

        /* ===== SALE CARD ===== */
        .sale-card {
            background: white; border-radius: 18px; overflow: hidden;
            cursor: pointer; transition: all 0.35s cubic-bezier(0.25, 0.8, 0.25, 1);
            border: 1px solid #F1F5F9;
            box-shadow: 0 4px 10px -2px rgba(0, 0, 0, 0.04);
        }
        .sale-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 24px 40px -12px rgba(0, 0, 0, 0.18);
            border-color: rgba(180, 83, 9, 0.25);
        }
        .sale-card .sale-img { transition: transform 0.7s cubic-bezier(0.25, 0.8, 0.25, 1); }
        .sale-card:hover .sale-img { transform: scale(1.08); }

        .favorite-btn {
            position: absolute; top: 12px; right: 12px;
            width: 34px; height: 34px; border-radius: 9999px;
            background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(4px);
            display: flex; align-items: center; justify-content: center;
            color: #374151; font-size: 0.8rem; transition: all 0.25s ease; z-index: 5;
        }
        .favorite-btn:hover { background: white; transform: scale(1.1); }
        .favorite-btn.liked { color: #EF4444; }
        .favorite-btn.liked i { animation: heartPop 0.35s ease; }
        @keyframes heartPop { 0%{transform:scale(1);} 50%{transform:scale(1.4);} 100%{transform:scale(1);} }

        .badge-sale {
            position: absolute; top: 12px; left: 12px;
            padding: 4px 12px; border-radius: 9999px;
            font-size: 0.65rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.03em; color: white; z-index: 5;
        }

        /* ===== MODAL ===== */
        #saleModal { opacity: 0; transition: opacity 0.3s ease; }
        #saleModal.show { opacity: 1; }
        #saleModal .modal-content {
            transform: scale(0.95) translateY(10px);
            transition: transform 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        #saleModal.show .modal-content { transform: scale(1) translateY(0); }

        /* ===== SCROLLBARS ===== */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .thin-scroll::-webkit-scrollbar { width: 6px; }
        .thin-scroll::-webkit-scrollbar-track { background: transparent; }
        .thin-scroll::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 3px; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .anim-fade-up { animation: fadeUp 0.5s ease both; }

        .map-placeholder {
            background: linear-gradient(135deg, #FEF3C7, #FEF9EE);
            position: relative; overflow: hidden;
        }
        .map-placeholder::after {
            content: ''; position: absolute; inset: 0;
            background-image: 
                linear-gradient(rgba(180, 83, 9, 0.08) 1px, transparent 1px),
                linear-gradient(90deg, rgba(180, 83, 9, 0.08) 1px, transparent 1px);
            background-size: 30px 30px;
        }

        /* Title deed badge */
        .title-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }
    </style>
</head>
<body class="bg-[#FAFAFA] text-gray-800 antialiased">


<!-- ================= HERO WITH SEARCH ================= -->
<section class="sale-hero relative pt-10 pb-16 md:pt-12 md:pb-24 px-4">
    
    <div class="max-w-4xl mx-auto text-center relative z-10 mb-6 md:mb-10">
        <span class="inline-block bg-white/15 backdrop-blur text-white text-[10px] md:text-xs font-bold uppercase tracking-[0.2em] px-4 py-1.5 rounded-full mb-4 border border-white/20">
            <i class="fas fa-key mr-1.5 text-accentOrange"></i> Property For Sale
        </span>
        <h1 class="font-serif text-3xl md:text-6xl text-white font-semibold leading-tight mb-3 md:mb-4 drop-shadow-lg">
            Own your <span class="italic text-accentOrange">dream</span> property
        </h1>
        <p class="text-white/80 text-sm md:text-base max-w-2xl mx-auto">
            Verified houses, land, apartments, and commercial property for sale across Kenya â freehold & leasehold titles, verified agents.
        </p>
    </div>

    <!-- SEARCH BAR -->
    <div class="max-w-2xl mx-auto relative z-20">
        <form onsubmit="handleSearch(event)" class="search-shell p-1.5 md:p-2 flex items-center gap-1">
            
            <div class="search-field flex-1 px-3 py-2 md:py-2.5 cursor-pointer" onclick="activateField(this)">
                <div class="flex items-center gap-2">
                    <i class="fas fa-map-marker-alt text-saleGold text-xs"></i>
                    <input type="text" id="searchLocation" placeholder="Search by location" class="search-input font-medium">
                </div>
            </div>

            <button type="submit" class="bg-gradient-to-r from-saleGold to-amber-600 text-white rounded-full px-5 py-2.5 md:px-6 md:py-3 flex items-center justify-center gap-2 font-semibold text-sm shadow-lg shadow-saleGold/30 hover:shadow-xl hover:shadow-saleGold/40 transition-all duration-300">
                <i class="fas fa-search text-xs"></i>
                <span class="hidden sm:inline">Search</span>
            </button>
        </form>

                <div class="flex flex-wrap items-center justify-center gap-2 mt-4 md:mt-5">
                    <span class="text-white/60 text-[10px] md:text-xs font-semibold uppercase tracking-wide mr-1">Popular:</span>
                    <button onclick="quickFilter('Freehold')" class="chip !bg-white/15 !border-white/20 !text-white hover:!bg-white/25">Freehold</button>
                    <button onclick="quickFilter('Land')" class="chip !bg-white/15 !border-white/20 !text-white hover:!bg-white/25">Land</button>
                    <button onclick="quickFilter('Luxury')" class="chip !bg-white/15 !border-white/20 !text-white hover:!bg-white/25">Luxury</button>
                    <button onclick="quickFilter('Investment')" class="chip !bg-white/15 !border-white/20 !text-white hover:!bg-white/25">Investment</button>
                    <button onclick="quickFilter('Family')" class="chip !bg-white/15 !border-white/20 !text-white hover:!bg-white/25">Family</button>
                </div>
    </div>
</section>


<!-- ================= MAIN CONTENT ================= -->
<section class="relative -mt-8 md:-mt-12 pb-16 px-4 md:px-6">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col lg:flex-row gap-6">

            <!-- ===== FILTER SIDEBAR ===== -->
            <aside class="lg:w-1/4 w-full">
                <div class="lg:sticky lg:top-6 space-y-4">

                    <div class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100">
                        <button onclick="toggleMobileFilters()" class="lg:hidden w-full flex items-center justify-between p-4">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-filter text-saleGold"></i>
                                <h3 class="font-serif text-base text-navy font-semibold">Filters</h3>
                            </div>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform" id="mobileFilterChevron"></i>
                        </button>
                        <div id="filterContent" class="hidden lg:block px-5 pb-5 pt-0 lg:pt-5">
                            <div class="hidden lg:flex items-center justify-between mb-4">
                                <h3 class="font-serif text-lg text-navy font-semibold">Filters</h3>
                                <button onclick="resetAllFilters()" class="text-[10px] text-saleGold hover:text-amber-800 font-semibold underline transition-colors">
                                    Reset all
                                </button>
                            </div>

                        <!-- Property Type -->
                        <div class="mb-5">
                            <h4 class="text-[11px] uppercase tracking-wider font-bold text-gray-500 mb-2.5">Property Type</h4>
                            <div class="space-y-2" id="typeFilters">
                                <label class="flex items-center gap-2.5 cursor-pointer group">
                                    <input type="checkbox" value="house" class="sale-filter-checkbox w-4 h-4 rounded border-gray-300 text-saleGold focus:ring-saleGold">
                                    <span class="text-xs text-gray-600 group-hover:text-saleGold transition-colors">House</span>
                                </label>
                                <label class="flex items-center gap-2.5 cursor-pointer group">
                                    <input type="checkbox" value="apartment" class="sale-filter-checkbox w-4 h-4 rounded border-gray-300 text-saleGold focus:ring-saleGold">
                                    <span class="text-xs text-gray-600 group-hover:text-saleGold transition-colors">Apartment</span>
                                </label>
                                <label class="flex items-center gap-2.5 cursor-pointer group">
                                    <input type="checkbox" value="land" class="sale-filter-checkbox w-4 h-4 rounded border-gray-300 text-saleGold focus:ring-saleGold">
                                    <span class="text-xs text-gray-600 group-hover:text-saleGold transition-colors">Land</span>
                                </label>
                                <label class="flex items-center gap-2.5 cursor-pointer group">
                                    <input type="checkbox" value="townhouse" class="sale-filter-checkbox w-4 h-4 rounded border-gray-300 text-saleGold focus:ring-saleGold">
                                    <span class="text-xs text-gray-600 group-hover:text-saleGold transition-colors">Townhouse</span>
                                </label>
                                <label class="flex items-center gap-2.5 cursor-pointer group">
                                    <input type="checkbox" value="commercial" class="sale-filter-checkbox w-4 h-4 rounded border-gray-300 text-saleGold focus:ring-saleGold">
                                    <span class="text-xs text-gray-600 group-hover:text-saleGold transition-colors">Commercial</span>
                                </label>
                            </div>
                        </div>

                        <!-- Bedrooms -->
                        <div class="mb-5">
                            <h4 class="text-[11px] uppercase tracking-wider font-bold text-gray-500 mb-2.5">Bedrooms</h4>
                            <div class="flex flex-wrap gap-1.5" id="bedFilters">
                                <button onclick="toggleBedFilter(this, '1')" class="chip !text-[10px] !py-1 !px-3">1</button>
                                <button onclick="toggleBedFilter(this, '2')" class="chip !text-[10px] !py-1 !px-3">2</button>
                                <button onclick="toggleBedFilter(this, '3')" class="chip !text-[10px] !py-1 !px-3">3</button>
                                <button onclick="toggleBedFilter(this, '4')" class="chip !text-[10px] !py-1 !px-3">4</button>
                                <button onclick="toggleBedFilter(this, '5+')" class="chip !text-[10px] !py-1 !px-3">5+</button>
                            </div>
                        </div>

                        <!-- Price Range -->
                        <div class="mb-5">
                            <h4 class="text-[11px] uppercase tracking-wider font-bold text-gray-500 mb-2.5">Sale Price</h4>
                            <div class="flex items-center gap-2">
                                <input type="number" id="minPrice" placeholder="Min" class="w-full px-2.5 py-1.5 border border-gray-200 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-saleGold/30">
                                <span class="text-gray-400 text-xs">â</span>
                                <input type="number" id="maxPrice" placeholder="Max" class="w-full px-2.5 py-1.5 border border-gray-200 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-saleGold/30">
                            </div>
                            <p class="text-[10px] text-gray-400 mt-1.5">KSh total price</p>
                        </div>

                        <!-- Title Type -->
                        <div class="mb-5">
                            <h4 class="text-[11px] uppercase tracking-wider font-bold text-gray-500 mb-2.5">Title Type</h4>
                            <div class="flex flex-wrap gap-1.5" id="titleFilters">
                                <button onclick="toggleTitleFilter(this, 'freehold')" class="chip !text-[10px] !py-1 !px-3">Freehold</button>
                                <button onclick="toggleTitleFilter(this, 'leasehold')" class="chip !text-[10px] !py-1 !px-3">Leasehold</button>
                            </div>
                        </div>

                        <!-- Amenities -->
                        <div class="mb-5">
                            <h4 class="text-[11px] uppercase tracking-wider font-bold text-gray-500 mb-2.5">Features</h4>
                            <div class="space-y-2">
                                <label class="flex items-center gap-2.5 cursor-pointer group">
                                    <input type="checkbox" value="wifi" class="sale-filter-checkbox amenity-checkbox w-4 h-4 rounded border-gray-300 text-saleGold focus:ring-saleGold">
                                    <span class="text-xs text-gray-600 group-hover:text-saleGold transition-colors"><i class="fas fa-wifi text-[10px] text-gray-400 mr-1"></i> WiFi Ready</span>
                                </label>
                                <label class="flex items-center gap-2.5 cursor-pointer group">
                                    <input type="checkbox" value="pool" class="sale-filter-checkbox amenity-checkbox w-4 h-4 rounded border-gray-300 text-saleGold focus:ring-saleGold">
                                    <span class="text-xs text-gray-600 group-hover:text-saleGold transition-colors"><i class="fas fa-swimming-pool text-[10px] text-gray-400 mr-1"></i> Swimming Pool</span>
                                </label>
                                <label class="flex items-center gap-2.5 cursor-pointer group">
                                    <input type="checkbox" value="parking" class="sale-filter-checkbox amenity-checkbox w-4 h-4 rounded border-gray-300 text-saleGold focus:ring-saleGold">
                                    <span class="text-xs text-gray-600 group-hover:text-saleGold transition-colors"><i class="fas fa-parking text-[10px] text-gray-400 mr-1"></i> Parking</span>
                                </label>
                                <label class="flex items-center gap-2.5 cursor-pointer group">
                                    <input type="checkbox" value="security" class="sale-filter-checkbox amenity-checkbox w-4 h-4 rounded border-gray-300 text-saleGold focus:ring-saleGold">
                                    <span class="text-xs text-gray-600 group-hover:text-saleGold transition-colors"><i class="fas fa-shield-alt text-[10px] text-gray-400 mr-1"></i> 24/7 Security</span>
                                </label>
                                <label class="flex items-center gap-2.5 cursor-pointer group">
                                    <input type="checkbox" value="garden" class="sale-filter-checkbox amenity-checkbox w-4 h-4 rounded border-gray-300 text-saleGold focus:ring-saleGold">
                                    <span class="text-xs text-gray-600 group-hover:text-saleGold transition-colors"><i class="fas fa-tree text-[10px] text-gray-400 mr-1"></i> Garden</span>
                                </label>
                                <label class="flex items-center gap-2.5 cursor-pointer group">
                                    <input type="checkbox" value="borehole" class="sale-filter-checkbox amenity-checkbox w-4 h-4 rounded border-gray-300 text-saleGold focus:ring-saleGold">
                                    <span class="text-xs text-gray-600 group-hover:text-saleGold transition-colors"><i class="fas fa-water text-[10px] text-gray-400 mr-1"></i> Borehole Water</span>
                                </label>
                            </div>
                        </div>

                        <!-- Verified title toggle -->
                        <div class="pt-3 border-t border-gray-100">
                            <label class="flex items-center justify-between cursor-pointer">
                                <span class="text-xs text-gray-600 font-medium">
                                    <i class="fas fa-certificate text-accentOrange mr-1"></i> Verified title only
                                </span>
                                <div class="relative">
                                    <input type="checkbox" id="verifiedToggle" class="sr-only peer">
                                    <div class="w-9 h-5 bg-gray-200 rounded-full peer-checked:bg-saleGold transition-colors"></div>
                                    <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow peer-checked:translate-x-4 transition-transform"></div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Map teaser -->
                    <div class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 p-4">
                        <h3 class="font-serif text-sm text-navy font-semibold mb-2.5 flex items-center gap-1.5">
                            <i class="fas fa-map-marked-alt text-saleGold text-xs"></i> Explore on Map
                        </h3>
                        <div class="map-placeholder rounded-xl h-32 flex items-center justify-center">
                            <div class="relative z-10 text-center">
                                <i class="fas fa-map-marker-alt text-saleGold text-2xl mb-1 animate-bounce"></i>
                                <p class="text-[10px] text-saleGold font-semibold">View properties on map</p>
                            </div>
                        </div>
                    </div>

                </div>
            </aside>

            <!-- ===== LISTINGS ===== -->
            <div class="lg:w-3/4 w-full">

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
                    <div>
                        <h2 class="font-serif text-2xl md:text-3xl text-navy font-semibold">
                            <span id="resultsCount">8</span> properties for sale
                        </h2>
                        <p class="text-xs text-gray-500 mt-0.5" id="filterSummary">Showing all properties for sale</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="relative">
                            <select id="sortSelect" onchange="applySort()" class="appearance-none bg-white border border-gray-200 rounded-lg pl-3 pr-8 py-2 text-xs font-medium text-gray-700 cursor-pointer focus:outline-none focus:ring-2 focus:ring-saleGold/30">
                                <option value="recommended">Recommended</option>
                                <option value="price-low">Price: Low to High</option>
                                <option value="price-high">Price: High to Low</option>
                                <option value="rating">Top Rated</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] pointer-events-none"></i>
                        </div>
                        <div class="hidden sm:flex bg-white border border-gray-200 rounded-lg p-0.5">
                            <button onclick="setView('grid', this)" class="view-btn active px-2.5 py-1.5 rounded-md text-xs transition-colors" aria-label="Grid view">
                                <i class="fas fa-th-large"></i>
                            </button>
                            <button onclick="setView('list', this)" class="view-btn px-2.5 py-1.5 rounded-md text-xs text-gray-400 transition-colors" aria-label="List view">
                                <i class="fas fa-list"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div id="activeFilters" class="flex flex-wrap gap-1.5 mb-4 hidden"></div>

                <div id="salesGrid" class="grid grid-cols-2 gap-0.5 min-[375px]:gap-1 sm:gap-2 md:gap-5"></div>

                <div id="emptyState" class="hidden text-center py-20">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-search text-3xl text-gray-300"></i>
                    </div>
                    <h3 class="font-serif text-xl text-navy font-semibold mb-2">No properties found</h3>
                    <p class="text-sm text-gray-500 mb-4">Try adjusting your filters or search criteria.</p>
                    <button onclick="resetAllFilters()" class="px-5 py-2.5 bg-saleGold text-white text-sm font-semibold rounded-lg hover:bg-amber-800 transition-colors">
                        Clear All Filters
                    </button>
                </div>

                <div class="flex items-center justify-center gap-1.5 mt-8" id="pagination"></div>

            </div>
        </div>
    </div>
</section>


<!-- ================= PROPERTY DETAIL MODAL ================= -->
<div id="saleModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-[3000] hidden items-center justify-center p-2 md:p-4">
    <div class="modal-content bg-white max-w-5xl w-full max-h-[95vh] md:max-h-[90vh] overflow-y-auto thin-scroll rounded-2xl md:rounded-3xl shadow-2xl relative">
        
        <button onclick="closeSaleModal()" class="sticky top-3 float-right mr-3 ml-auto w-9 h-9 bg-white/90 backdrop-blur rounded-full shadow-lg flex items-center justify-center hover:bg-saleGold hover:text-white transition-all z-50 border border-gray-200">
            <i class="fas fa-times text-sm"></i>
        </button>

        <!-- Gallery -->
        <div class="relative">
            <div class="h-[240px] md:h-[420px] overflow-hidden rounded-t-2xl md:rounded-t-3xl">
                <img id="modalMainImg" src="" alt="Property" class="w-full h-full object-cover transition-opacity duration-500">
            </div>
            <div class="absolute bottom-3 right-3 bg-black/60 backdrop-blur text-white text-[10px] md:text-xs font-semibold px-3 py-1.5 rounded-full flex items-center gap-1.5">
                <i class="fas fa-camera"></i> <span id="modalPhotoCount">6</span> photos
            </div>
            <div class="absolute bottom-3 left-3 flex gap-1.5 md:gap-2">
                <div id="modalThumbs" class="flex gap-1.5 md:gap-2"></div>
            </div>
        </div>

        <div class="p-4 md:p-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">
                
                <!-- Left content -->
                <div class="lg:col-span-2">
                    <div class="flex items-center gap-2 mb-2">
                        <span id="modalBadge" class="bg-saleGold/10 text-saleGold px-3 py-1 rounded-full text-[10px] md:text-xs font-bold uppercase">For Sale</span>
                        <span id="modalTitleBadge" class="title-badge bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <i class="fas fa-check-circle text-[8px]"></i> Freehold
                        </span>
                        <span id="modalVerified" class="hidden title-badge bg-blue-50 text-blue-700 border border-blue-200">
                            <i class="fas fa-certificate text-[8px]"></i> Verified
                        </span>
                    </div>

                    <h2 id="modalTitle" class="font-serif text-2xl md:text-4xl text-navy font-semibold mb-2">Property Title</h2>
                    
                    <div class="flex flex-wrap items-center gap-3 text-gray-500 text-xs md:text-sm mb-4">
                        <span class="flex items-center gap-1.5">
                            <i class="fas fa-map-marker-alt text-saleGold"></i>
                            <span id="modalLocation">Location</span>
                        </span>
                        <span class="flex items-center gap-1">
                            <i class="fas fa-star text-accentOrange text-[10px]"></i>
                            <span id="modalRating" class="font-semibold text-gray-700">4.8</span>
                            <span id="modalReviews" class="text-gray-400">(64 reviews)</span>
                        </span>
                    </div>

                    <!-- Specs -->
                    <div class="grid grid-cols-4 gap-2 md:gap-3 mb-5 p-3 md:p-4 bg-gray-50 rounded-xl">
                        <div class="text-center">
                            <i class="fas fa-bed text-saleGold text-sm md:text-lg mb-1"></i>
                            <p class="text-[9px] md:text-[10px] uppercase text-gray-400 font-bold">Bedrooms</p>
                            <p id="modalBeds" class="text-xs md:text-sm font-bold text-navy">3</p>
                        </div>
                        <div class="text-center border-l border-gray-200">
                            <i class="fas fa-bath text-saleGold text-sm md:text-lg mb-1"></i>
                            <p class="text-[9px] md:text-[10px] uppercase text-gray-400 font-bold">Bathrooms</p>
                            <p id="modalBaths" class="text-xs md:text-sm font-bold text-navy">2</p>
                        </div>
                        <div class="text-center border-l border-gray-200">
                            <i class="fas fa-car text-saleGold text-sm md:text-lg mb-1"></i>
                            <p class="text-[9px] md:text-[10px] uppercase text-gray-400 font-bold">Parking</p>
                            <p id="modalParking" class="text-xs md:text-sm font-bold text-navy">2</p>
                        </div>
                        <div class="text-center border-l border-gray-200">
                            <i class="fas fa-ruler-combined text-saleGold text-sm md:text-lg mb-1"></i>
                            <p class="text-[9px] md:text-[10px] uppercase text-gray-400 font-bold">Size</p>
                            <p class="text-xs md:text-sm font-bold text-navy"><span id="modalSize">1,800</span> sqft</p>
                        </div>
                    </div>

                    <!-- Agent -->
                    <div class="flex items-center gap-3 mb-5 p-3 bg-gray-50 rounded-xl">
                        <div class="w-11 h-11 rounded-full bg-gradient-to-br from-saleGold to-amber-700 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                            <span id="modalAgentInitial">J</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[10px] uppercase text-gray-400 font-bold">Listed by</p>
                            <p id="modalAgentName" class="text-sm font-semibold text-navy truncate">Jane M.</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] uppercase text-gray-400 font-bold">Verified</p>
                            <p class="text-xs font-semibold text-emerald-600"><i class="fas fa-check-circle mr-0.5"></i> Agent</p>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-5">
                        <h3 class="font-serif text-lg md:text-xl text-navy font-semibold mb-2">
                            <i class="fas fa-info-circle text-saleGold mr-1.5 text-sm"></i> About this property
                        </h3>
                        <p id="modalDesc" class="text-gray-600 text-xs md:text-sm leading-relaxed"></p>
                    </div>

                    <!-- Amenities -->
                    <div class="mb-5">
                        <h3 class="font-serif text-lg md:text-xl text-navy font-semibold mb-2.5">
                            <i class="fas fa-concierge-bell text-saleGold mr-1.5 text-sm"></i> Features & Amenities
                        </h3>
                        <div id="modalAmenities" class="grid grid-cols-2 gap-2"></div>
                    </div>

                    <!-- Title deed details -->
                    <div class="mb-5">
                        <h3 class="font-serif text-lg md:text-xl text-navy font-semibold mb-2">
                            <i class="fas fa-file-contract text-saleGold mr-1.5 text-sm"></i> Title & Legal
                        </h3>
                        <div class="grid grid-cols-2 gap-2 text-xs text-gray-600">
                            <div class="flex items-center gap-1.5"><i class="fas fa-check text-emerald-500 text-[10px]"></i> <span id="modalTitleType">Freehold</span></div>
                            <div class="flex items-center gap-1.5"><i class="fas fa-check text-emerald-500 text-[10px]"></i> Ready title deed</div>
                            <div class="flex items-center gap-1.5"><i class="fas fa-check text-emerald-500 text-[10px]"></i> Surveyed & beaconed</div>
                            <div class="flex items-center gap-1.5"><i class="fas fa-check text-emerald-500 text-[10px]"></i> Rates paid up to date</div>
                        </div>
                    </div>
                </div>

                <!-- Right: Purchase Card -->
                <div class="lg:col-span-1">
                    <div class="lg:sticky lg:top-4 bg-white border border-gray-200 rounded-2xl p-4 md:p-5 shadow-xl">
                        <div class="mb-4">
                            <span id="modalPrice" class="text-2xl md:text-3xl font-bold text-navy">KSh 25,000,000</span>
                        </div>

                        <div class="flex items-center gap-2 mb-4 p-2 bg-gray-50 rounded-lg">
                            <i class="fas fa-star text-accentOrange text-xs"></i>
                            <span class="text-sm font-semibold text-navy" id="modalRating2">4.8</span>
                            <span class="text-[10px] text-gray-400">â¢</span>
                            <span class="text-[10px] text-gray-500" id="modalReviews2">64 reviews</span>
                        </div>

                        <!-- Mortgage estimate -->
                        <div class="bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-200 rounded-xl p-3 mb-4">
                            <div class="flex items-center gap-1.5 mb-1.5">
                                <i class="fas fa-calculator text-saleGold text-xs"></i>
                                <span class="text-[10px] uppercase font-bold text-saleBrown tracking-wide">Estimated Mortgage</span>
                            </div>
                            <p class="text-lg font-bold text-navy mb-0.5">
                                <span id="modalMortgage">KSh 180,000</span><span class="text-[10px] text-gray-500 font-normal">/month</span>
                            </p>
                            <p class="text-[9px] text-gray-500">20% down Â· 20 years Â· 12% p.a.</p>
                        </div>

                        <!-- Enquiry fields -->
                        <div class="space-y-2.5 mb-4">
                            <div class="border border-gray-200 rounded-lg px-2.5 py-2">
                                <label class="block text-[9px] uppercase text-gray-400 font-bold mb-0.5">I'm interested in</label>
                                <select class="w-full text-xs focus:outline-none bg-transparent">
                                    <option>Buying this property</option>
                                    <option>Scheduling a viewing</option>
                                    <option>Requesting more info</option>
                                    <option>Making an offer</option>
                                </select>
                            </div>
                            <div class="border border-gray-200 rounded-lg px-2.5 py-2">
                                <label class="block text-[9px] uppercase text-gray-400 font-bold mb-0.5">Preferred viewing date</label>
                                <input type="date" class="w-full text-xs focus:outline-none bg-transparent">
                            </div>
                        </div>

                        <!-- Price breakdown -->
                        <div class="space-y-1.5 py-3 border-t border-gray-100 text-xs">
                            <div class="flex justify-between text-gray-600">
                                <span>Asking price</span>
                                <span id="modalPriceLine">KSh 25,000,000</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Est. legal fees (4%)</span>
                                <span id="modalLegal">KSh 1,000,000</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Est. stamp duty (4%)</span>
                                <span id="modalStamp">KSh 1,000,000</span>
                            </div>
                            <div class="flex justify-between font-bold text-navy pt-2 border-t border-gray-100 text-sm">
                                <span>Est. total cost</span>
                                <span id="modalTotal">KSh 27,000,000</span>
                            </div>
                        </div>

                        <button class="w-full py-3 bg-gradient-to-r from-saleGold to-amber-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-saleGold/30 hover:shadow-xl hover:shadow-saleGold/40 transition-all mt-3">
                            <i class="fas fa-calendar-check mr-2"></i> Schedule Viewing
                        </button>
                        <button class="w-full py-2.5 mt-2 border border-gray-200 text-gray-600 text-xs font-semibold rounded-xl hover:bg-gray-50 transition-colors">
                            <i class="fas fa-envelope mr-1.5"></i> Contact Agent
                        </button>

                        <p class="text-center text-[10px] text-gray-400 mt-3">
                            <i class="fas fa-shield-alt mr-1"></i> Verified listing Â· Title on file
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<script>/* ============================================================
   DATA - 8 sale properties
   ============================================================ */
const salesData = [
    {
        id: 'sale-1',
        title: 'Contemporary 4 Bed Townhouse',
        location: 'Kileleshwa, Nairobi',
        price: 25000000,
        rating: 4.8,
        reviews: 64,
        beds: 4, baths: 4, parking: 2, size: '3,800',
        type: 'townhouse',
        badge: 'For Sale',
        titleType: 'freehold',
        verified: true,
        tags: ['family', 'luxury', 'investment'],
        agentName: 'Jane M.',
        agentInitial: 'J',
        desc: 'A stunning contemporary townhouse in a secure gated community in Kileleshwa. Boasting high-end finishes, spacious rooms, a rooftop terrace, and a private garden. The property is ideal for a growing family or as a premium investment with excellent rental yield potential. Ready title deed available.',
        amenities: [
            {icon: 'fa-wifi', label: 'WiFi Ready'},
            {icon: 'fa-swimming-pool', label: 'Swimming Pool'},
            {icon: 'fa-shield-alt', label: '24/7 Security'},
            {icon: 'fa-parking', label: '2 Parking Spaces'},
            {icon: 'fa-tree', label: 'Private Garden'},
            {icon: 'fa-dumbbell', label: 'Gym Access'},
            {icon: 'fa-solar-panel', label: 'Solar Heating'},
            {icon: 'fa-video', label: 'CCTV'}
        ],
        images: [
            'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80'
        ]
    },
    {
        id: 'sale-2',
        title: 'Luxury 5 Bed Garden Villa',
        location: 'Runda, Nairobi',
        price: 85000000,
        rating: 4.9,
        reviews: 42,
        beds: 5, baths: 6, parking: 4, size: '6,500',
        type: 'house',
        badge: 'For Sale',
        titleType: 'freehold',
        verified: true,
        tags: ['luxury', 'family'],
        agentName: 'Michael A.',
        agentInitial: 'M',
        desc: 'An exquisite 5-bedroom villa set on a sprawling 1-acre garden in the prestigious Runda estate. The property features a grand entrance, a spacious living room with high ceilings, a home cinema, a private gym, a heated swimming pool, and staff quarters. Absolute privacy and luxury in one of Nairobi\'s most sought-after neighborhoods.',
        amenities: [
            {icon: 'fa-wifi', label: 'Fibre Internet'},
            {icon: 'fa-swimming-pool', label: 'Heated Pool'},
            {icon: 'fa-shield-alt', label: '24/7 Security'},
            {icon: 'fa-parking', label: '4 Car Garage'},
            {icon: 'fa-tree', label: '1-Acre Garden'},
            {icon: 'fa-dumbbell', label: 'Private Gym'},
            {icon: 'fa-film', label: 'Home Cinema'},
            {icon: 'fa-water', label: 'Borehole Water'}
        ],
        images: [
            'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80'
        ]
    },
    {
        id: 'sale-3',
        title: 'Modern 3 Bed Penthouse with City View',
        location: 'Upper Hill, Nairobi',
        price: 40000000,
        rating: 4.7,
        reviews: 38,
        beds: 3, baths: 3, parking: 2, size: '2,800',
        type: 'apartment',
        badge: 'For Sale',
        titleType: 'leasehold',
        verified: true,
        tags: ['luxury', 'investment', 'city'],
        agentName: 'Sarah N.',
        agentInitial: 'S',
        desc: 'A spectacular penthouse in Upper Hill with panoramic views of the Nairobi skyline. This 3-bedroom unit features floor-to-ceiling windows, a private rooftop terrace, premium finishes, and access to a rooftop infinity pool and gym. The building is secure, with 24/7 concierge and 2 dedicated parking spaces. Ideal for the discerning urban professional.',
        amenities: [
            {icon: 'fa-wifi', label: 'Fibre Internet'},
            {icon: 'fa-swimming-pool', label: 'Rooftop Pool'},
            {icon: 'fa-shield-alt', label: '24/7 Concierge'},
            {icon: 'fa-parking', label: '2 Parking Spaces'},
            {icon: 'fa-dumbbell', label: 'Gym Access'},
            {icon: 'fa-building', label: 'Rooftop Terrace'},
            {icon: 'fa-snowflake', label: 'Air Conditioning'},
            {icon: 'fa-fire-extinguisher', label: 'Fire Safety'}
        ],
        images: [
            'https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80'
        ]
    },
    {
        id: 'sale-4',
        title: 'Modern 3 Bed Bungalow on 1/8 Acre',
        location: 'Syokimau, Machakos',
        price: 12500000,
        rating: 4.5,
        reviews: 56,
        beds: 3, baths: 2, parking: 2, size: '1,600',
        type: 'house',
        badge: 'For Sale',
        titleType: 'freehold',
        verified: false,
        tags: ['family', 'investment'],
        agentName: 'David O.',
        agentInitial: 'D',
        desc: 'A modern, well-maintained 3-bedroom bungalow on a 1/8 acre plot in Syokimau. Features a spacious living area, a modern kitchen, a master en-suite, and a well-kept garden. The property has a ready title deed, is surveyed and beaconed, and is ideal for a first-time buyer or as a rental investment. Close to SGR station and Mombasa Road.',
        amenities: [
            {icon: 'fa-wifi', label: 'WiFi Ready'},
            {icon: 'fa-shield-alt', label: 'Gated Community'},
            {icon: 'fa-parking', label: '2 Parking Spaces'},
            {icon: 'fa-tree', label: 'Garden'},
            {icon: 'fa-water', label: 'Borehole Water'},
            {icon: 'fa-child', label: 'Playground'}
        ],
        images: [
            'https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1200&q=80'
        ]
    },
    {
        id: 'sale-5',
        title: 'Prime 1/4 Acre Residential Plot',
        location: 'Ngong, Kajiado',
        price: 4500000,
        rating: 4.4,
        reviews: 31,
        beds: 0, baths: 0, parking: 0, size: '0.25 acres',
        type: 'land',
        badge: 'For Sale',
        titleType: 'freehold',
        verified: true,
        tags: ['land', 'investment'],
        agentName: 'Grace W.',
        agentInitial: 'G',
        desc: 'A prime 1/4 acre residential plot in Ngong, Kajiado. The land is flat, well-drained, and ready for immediate development. It has a ready freehold title deed, is fully surveyed, and is located in a fast-growing neighborhood with easy access to Ngong town and the Nairobi CBD. Ideal for building your dream home or as a long-term investment.',
        amenities: [
            {icon: 'fa-road', label: 'Tarmac Road Access'},
            {icon: 'fa-bolt', label: 'Electricity On-Site'},
            {icon: 'fa-water', label: 'Water Nearby'},
            {icon: 'fa-certificate', label: 'Ready Title Deed'},
            {icon: 'fa-ruler-combined', label: 'Surveyed & Beaconed'},
            {icon: 'fa-tree', label: 'Scenic Views'}
        ],
        images: [
            'https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1200&q=80'
        ]
    },
    {
        id: 'sale-6',
        title: 'Commercial Building in CBD',
        location: 'Nairobi CBD, Nairobi',
        price: 120000000,
        rating: 4.8,
        reviews: 27,
        beds: 0, baths: 8, parking: 10, size: '8,000',
        type: 'commercial',
        badge: 'For Sale',
        titleType: 'leasehold',
        verified: true,
        tags: ['investment', 'commercial'],
        agentName: 'Peter K.',
        agentInitial: 'P',
        desc: 'A prime commercial building in the heart of Nairobi CBD, ideal for office, retail, or mixed-use development. The building has 8 floors of lettable space, currently leased to 5 corporate tenants with long-term leases. The property offers a steady rental income of over KSh 2.4M per month with significant upside potential. Secure parking for 10 vehicles and 24/7 security.',
        amenities: [
            {icon: 'fa-building', label: '8 Floors'},
            {icon: 'fa-elevator', label: '2 Elevators'},
            {icon: 'fa-shield-alt', label: '24/7 Security'},
            {icon: 'fa-parking', label: '10 Parking Spaces'},
            {icon: 'fa-fire-extinguisher', label: 'Fire Safety'},
            {icon: 'fa-solar-panel', label: 'Backup Generator'},
            {icon: 'fa-wifi', label: 'Fibre Internet'},
            {icon: 'fa-video', label: 'CCTV'}
        ],
        images: [
            'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80'
        ]
    },
    {
        id: 'sale-7',
        title: 'Beachfront 3 Bed Villa in Diani',
        location: 'Diani, Mombasa',
        price: 65000000,
        rating: 4.9,
        reviews: 44,
        beds: 3, baths: 3, parking: 2, size: '3,200',
        type: 'house',
        badge: 'For Sale',
        titleType: 'freehold',
        verified: true,
        tags: ['luxury', 'investment', 'beachfront'],
        agentName: 'Fatuma H.',
        agentInitial: 'F',
        desc: 'A stunning beachfront villa in Diani with direct access to the white sands of the Indian Ocean. This 3-bedroom property features a private swimming pool, a large veranda overlooking the ocean, and a lush tropical garden. It comes with a ready title deed and is currently a successful holiday rental with high occupancy. A perfect blend of luxury living and investment.',
        amenities: [
            {icon: 'fa-umbrella-beach', label: 'Beach Front'},
            {icon: 'fa-swimming-pool', label: 'Private Pool'},
            {icon: 'fa-shield-alt', label: '24/7 Security'},
            {icon: 'fa-parking', label: '2 Parking Spaces'},
            {icon: 'fa-tree', label: 'Tropical Garden'},
            {icon: 'fa-water', label: 'Ocean Views'},
            {icon: 'fa-snowflake', label: 'Air Conditioning'},
            {icon: 'fa-wifi', label: 'Fibre Internet'}
        ],
        images: [
            'https://images.unsplash.com/photo-1499793983690-e29da59ef1c2?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1571896349842-33c89424de2d?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1200&q=80'
        ]
    },
    {
        id: 'sale-8',
        title: 'Modern 2 Bed Apartment in Kilimani',
        location: 'Kilimani, Nairobi',
        price: 8500000,
        rating: 4.6,
        reviews: 73,
        beds: 2, baths: 2, parking: 1, size: '1,100',
        type: 'apartment',
        badge: 'For Sale',
        titleType: 'leasehold',
        verified: true,
        tags: ['investment', 'city'],
        agentName: 'Kevin R.',
        agentInitial: 'K',
        desc: 'A modern 2-bedroom apartment in the vibrant Kilimani area, ideal for first-time buyers or investors. The unit features an open-plan living area, a modern kitchen, a master en-suite, and a balcony. The building offers a swimming pool, gym, and 24/7 security. Excellent rental demand and high capital appreciation potential. Ready title deed available.',
        amenities: [
            {icon: 'fa-wifi', label: 'Fibre Internet'},
            {icon: 'fa-swimming-pool', label: 'Swimming Pool'},
            {icon: 'fa-shield-alt', label: '24/7 Security'},
            {icon: 'fa-parking', label: '1 Parking Space'},
            {icon: 'fa-dumbbell', label: 'Gym Access'},
            {icon: 'fa-building', label: 'Balcony'}
        ],
        images: [
            'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea?auto=format&fit=crop&w=1200&q=80'
        ]
    }
];

/* ============================================================
   STATE
   ============================================================ */
let currentFilters = {
    types: [],
    amenities: [],
    minPrice: null,
    maxPrice: null,
    beds: null,
    titleType: null,
    verified: false,
    search: '',
    quickTag: null
};
let currentSort = 'recommended';
let currentView = 'grid';
let currentPage = 1;
const PER_PAGE = 6;
let modalSlideInterval = null;
let modalSlideIndex = 0;

/* ============================================================
   FILTERING
   ============================================================ */
function getFilteredSales() {
    let results = [...salesData];

    if (currentFilters.types.length > 0)
        results = results.filter(s => currentFilters.types.includes(s.type));

    if (currentFilters.amenities.length > 0) {
        results = results.filter(s => {
            return currentFilters.amenities.every(amen => {
                const map = {
                    wifi: 'fa-wifi', pool: 'fa-swimming-pool', parking: 'fa-parking',
                    security: 'fa-shield-alt', garden: 'fa-tree', borehole: 'fa-water'
                };
                return s.amenities.some(a => a.icon === map[amen]);
            });
        });
    }

    if (currentFilters.minPrice !== null)
        results = results.filter(s => s.price >= currentFilters.minPrice);
    if (currentFilters.maxPrice !== null)
        results = results.filter(s => s.price <= currentFilters.maxPrice);

    if (currentFilters.beds) {
        const b = currentFilters.beds;
        if (b === '5+') results = results.filter(s => s.beds >= 5);
        else results = results.filter(s => s.beds >= parseInt(b));
    }

    if (currentFilters.titleType)
        results = results.filter(s => s.titleType === currentFilters.titleType);

    if (currentFilters.verified)
        results = results.filter(s => s.verified);

    if (currentFilters.quickTag) {
        const tag = currentFilters.quickTag.toLowerCase();
        results = results.filter(s => s.tags.some(t => t.includes(tag) || tag.includes(t)));
    }

    if (currentFilters.search.trim()) {
        const q = currentFilters.search.toLowerCase();
        results = results.filter(s =>
            s.title.toLowerCase().includes(q) ||
            s.location.toLowerCase().includes(q) ||
            s.type.toLowerCase().includes(q)
        );
    }

    if (currentSort === 'price-low') results.sort((a, b) => a.price - b.price);
    else if (currentSort === 'price-high') results.sort((a, b) => b.price - a.price);
    else if (currentSort === 'rating') results.sort((a, b) => b.rating - a.rating);

    return results;
}

/* ============================================================
   RENDER
   ============================================================ */
function renderSales() {
    const grid = document.getElementById('salesGrid');
    const empty = document.getElementById('emptyState');
    const countEl = document.getElementById('resultsCount');
    const summaryEl = document.getElementById('filterSummary');
    const pagination = document.getElementById('pagination');
    const activeFiltersEl = document.getElementById('activeFilters');

    const filtered = getFilteredSales();
    countEl.textContent = filtered.length;

    let summaryParts = [];
    if (currentFilters.quickTag) summaryParts.push(currentFilters.quickTag);
    if (currentFilters.types.length) summaryParts.push(currentFilters.types.join(', '));
    if (currentFilters.amenities.length) summaryParts.push(currentFilters.amenities.length + ' features');
    if (currentFilters.minPrice || currentFilters.maxPrice) summaryParts.push('price filtered');
    if (currentFilters.beds) summaryParts.push(currentFilters.beds + ' beds');
    if (currentFilters.titleType) summaryParts.push(currentFilters.titleType);
    if (currentFilters.verified) summaryParts.push('verified title');
    summaryEl.textContent = summaryParts.length > 0
        ? 'Filtered by: ' + summaryParts.join(' Â· ')
        : 'Showing all properties for sale';

    let chips = [];
    if (currentFilters.quickTag) chips.push({label: 'ð·ï¸ ' + currentFilters.quickTag, fn: 'removeQuickTag()'});
    currentFilters.types.forEach(t => chips.push({label: t, fn: `removeType('${t}')`}));
    currentFilters.amenities.forEach(a => chips.push({label: a, fn: `removeAmenity('${a}')`}));
    if (currentFilters.beds) chips.push({label: currentFilters.beds + ' beds', fn: 'removeBeds()'});
    if (currentFilters.titleType) chips.push({label: currentFilters.titleType, fn: 'removeTitleType()'});
    if (currentFilters.verified) chips.push({label: 'Verified', fn: 'removeVerified()'});
    if (currentFilters.minPrice || currentFilters.maxPrice) chips.push({label: 'Price', fn: 'removePrice()'});

    if (chips.length > 0) {
        activeFiltersEl.classList.remove('hidden');
        activeFiltersEl.innerHTML = chips.map(c =>
            `<button onclick="${c.fn}" class="inline-flex items-center gap-1 bg-saleGold/10 text-saleGold text-[10px] font-semibold px-2.5 py-1 rounded-full border border-saleGold/20 hover:bg-saleGold hover:text-white transition-colors">
                ${c.label} <i class="fas fa-times text-[8px]"></i>
            </button>`
        ).join('');
    } else {
        activeFiltersEl.classList.add('hidden');
        activeFiltersEl.innerHTML = '';
    }

    const totalPages = Math.ceil(filtered.length / PER_PAGE) || 1;
    if (currentPage > totalPages) currentPage = totalPages;
    const start = (currentPage - 1) * PER_PAGE;
    const paged = filtered.slice(start, start + PER_PAGE);

    if (filtered.length === 0) {
        grid.innerHTML = '';
        grid.classList.add('hidden');
        empty.classList.remove('hidden');
        pagination.innerHTML = '';
        return;
    }
    grid.classList.remove('hidden');
    empty.classList.add('hidden');

    grid.className = currentView === 'grid'
        ? 'grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 md:gap-5'
        : 'flex flex-col gap-3';

    grid.innerHTML = paged.map(r => {
        const badgeColor = r.type === 'house' ? 'bg-saleBrown'
                         : r.type === 'land' ? 'bg-emerald-700'
                         : r.type === 'townhouse' ? 'bg-indigo-700'
                         : r.type === 'commercial' ? 'bg-navy'
                         : 'bg-saleGold';

        const priceDisplay = r.price >= 1000000
            ? `KSh ${(r.price / 1000000).toFixed(r.price % 1000000 === 0 ? 0 : 1)}M`
            : `KSh ${r.price.toLocaleString()}`;

        if (currentView === 'grid') {
            return `
                <article class="sale-card anim-fade-up group" onclick="openSaleModal('${r.id}')">
                    <div class="relative h-52 overflow-hidden">
                        <img src="${r.images[0]}" alt="${r.title}" class="sale-img w-full h-full object-cover">
                        <span class="badge-sale ${badgeColor}">${r.badge}</span>
                        ${r.verified ? `<span class="absolute top-3 left-3 mt-7 bg-emerald-600 text-white text-[9px] font-bold px-2 py-0.5 rounded-full"><i class="fas fa-certificate mr-0.5"></i>Verified</span>` : ''}
                        <button onclick="toggleFavorite(event, this)" class="favorite-btn" aria-label="Save">
                            <i class="far fa-heart"></i>
                        </button>
                    </div>
                    <div class="p-3.5">
                        <div class="flex items-start justify-between gap-2 mb-1">
                            <h3 class="font-serif text-base md:text-lg text-navy font-semibold leading-tight line-clamp-1">${r.title}</h3>
                            <div class="flex items-center gap-1 flex-shrink-0">
                                <i class="fas fa-star text-accentOrange text-[10px]"></i>
                                <span class="text-xs font-semibold text-navy">${r.rating}</span>
                            </div>
                        </div>
                        <p class="text-[11px] text-gray-500 mb-2 line-clamp-1">
                            <i class="fas fa-map-marker-alt text-saleGold text-[9px] mr-1"></i>${r.location}
                        </p>
                        <div class="flex items-center gap-2 text-[10px] text-gray-400 mb-2.5">
                            ${r.beds > 0 ? `<span><i class="fas fa-bed mr-0.5"></i>${r.beds}</span><span>Â·</span>` : ''}
                            ${r.baths > 0 ? `<span><i class="fas fa-bath mr-0.5"></i>${r.baths}</span><span>Â·</span>` : ''}
                            <span><i class="fas fa-ruler-combined mr-0.5"></i>${r.size}</span>
                            <span class="ml-auto uppercase font-bold text-[9px] ${r.titleType === 'freehold' ? 'text-emerald-600' : 'text-blue-600'}">${r.titleType}</span>
                        </div>
                        <div class="flex items-end justify-between pt-2.5 border-t border-gray-100">
                            <div>
                                <span class="text-base md:text-lg font-bold text-navy">${priceDisplay}</span>
                            </div>
                            <span class="text-[10px] text-gray-400">${r.reviews} views</span>
                        </div>
                    </div>
                </article>
            `;
        } else {
            return `
                <article class="bg-white rounded-xl overflow-hidden border border-gray-100 hover:shadow-lg transition-all cursor-pointer flex" onclick="openSaleModal('${r.id}')">
                    <div class="relative w-40 sm:w-56 flex-shrink-0 overflow-hidden">
                        <img src="${r.images[0]}" alt="${r.title}" class="w-full h-full object-cover">
                        <span class="badge-sale ${badgeColor}">${r.badge}</span>
                    </div>
                    <div class="p-3 md:p-4 flex-1 min-w-0 flex flex-col justify-between">
                        <div>
                            <div class="flex items-start justify-between gap-2 mb-1">
                                <h3 class="font-serif text-sm md:text-lg text-navy font-semibold line-clamp-1">${r.title}</h3>
                                <div class="flex items-center gap-1 flex-shrink-0">
                                    <i class="fas fa-star text-accentOrange text-[10px]"></i>
                                    <span class="text-xs font-semibold text-navy">${r.rating}</span>
                                </div>
                            </div>
                            <p class="text-[11px] text-gray-500 mb-1"><i class="fas fa-map-marker-alt text-saleGold text-[9px] mr-1"></i>${r.location}</p>
                            <p class="text-[11px] text-gray-400 line-clamp-2 hidden sm:block">${r.desc}</p>
                        </div>
                        <div class="flex items-center justify-between pt-2 border-t border-gray-100 mt-2">
                            <div class="flex items-center gap-2 text-[10px] text-gray-400">
                                ${r.beds > 0 ? `<span><i class="fas fa-bed mr-0.5"></i>${r.beds}</span>` : ''}
                                ${r.baths > 0 ? `<span><i class="fas fa-bath mr-0.5"></i>${r.baths}</span>` : ''}
                                <span class="uppercase font-bold text-[9px] ${r.titleType === 'freehold' ? 'text-emerald-600' : 'text-blue-600'}">${r.titleType}</span>
                            </div>
                            <div>
                                <span class="text-sm md:text-base font-bold text-navy">${priceDisplay}</span>
                            </div>
                        </div>
                    </div>
                </article>
            `;
        }
    }).join('');

    if (totalPages > 1) {
        let pag = `<button onclick="goToPage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''} class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:border-saleGold hover:text-saleGold transition-colors disabled:opacity-40 disabled:cursor-not-allowed"><i class="fas fa-chevron-left text-[10px]"></i></button>`;
        for (let i = 1; i <= totalPages; i++) {
            pag += `<button onclick="goToPage(${i})" class="w-9 h-9 flex items-center justify-center rounded-lg text-xs font-semibold transition-colors ${i === currentPage ? 'bg-saleGold text-white shadow-md shadow-saleGold/30' : 'border border-gray-200 text-gray-600 hover:border-saleGold hover:text-saleGold'}">${i}</button>`;
        }
        pag += `<button onclick="goToPage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''} class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:border-saleGold hover:text-saleGold transition-colors disabled:opacity-40 disabled:cursor-not-allowed"><i class="fas fa-chevron-right text-[10px]"></i></button>`;
        pagination.innerHTML = pag;
    } else {
        pagination.innerHTML = '';
    }
}

function goToPage(page) {
    const filtered = getFilteredSales();
    const totalPages = Math.ceil(filtered.length / PER_PAGE) || 1;
    if (page < 1 || page > totalPages) return;
    currentPage = page;
    renderSales();
    document.getElementById('salesGrid').scrollIntoView({behavior: 'smooth', block: 'start'});
}

/* ============================================================
   FILTER HANDLERS
   ============================================================ */
document.querySelectorAll('#typeFilters input[type="checkbox"]').forEach(cb => {
    cb.addEventListener('change', function() {
        const val = this.value;
        if (this.checked) currentFilters.types.push(val);
        else currentFilters.types = currentFilters.types.filter(t => t !== val);
        currentPage = 1;
        renderSales();
    });
});

document.querySelectorAll('.amenity-checkbox').forEach(cb => {
    cb.addEventListener('change', function() {
        const val = this.value;
        if (this.checked) currentFilters.amenities.push(val);
        else currentFilters.amenities = currentFilters.amenities.filter(a => a !== val);
        currentPage = 1;
        renderSales();
    });
});

document.getElementById('minPrice').addEventListener('input', function() {
    currentFilters.minPrice = this.value ? parseInt(this.value) : null;
    currentPage = 1;
    renderSales();
});
document.getElementById('maxPrice').addEventListener('input', function() {
    currentFilters.maxPrice = this.value ? parseInt(this.value) : null;
    currentPage = 1;
    renderSales();
});

document.getElementById('verifiedToggle').addEventListener('change', function() {
    currentFilters.verified = this.checked;
    currentPage = 1;
    renderSales();
});

function toggleMobileFilters() {
    const content = document.getElementById('filterContent');
    const chevron = document.getElementById('mobileFilterChevron');
    if (!content) return;
    content.classList.toggle('hidden');
    if (content.classList.contains('hidden')) {
        if (chevron) chevron.style.transform = 'rotate(0deg)';
    } else {
        if (chevron) chevron.style.transform = 'rotate(180deg)';
    }
}

function toggleBedFilter(btn, val) {
    if (btn.classList.contains('active')) {
        btn.classList.remove('active');
        currentFilters.beds = null;
    } else {
        document.querySelectorAll('#bedFilters .chip').forEach(c => c.classList.remove('active'));
        btn.classList.add('active');
        currentFilters.beds = val;
    }
    currentPage = 1;
    renderSales();
}

function toggleTitleFilter(btn, val) {
    if (btn.classList.contains('active')) {
        btn.classList.remove('active');
        currentFilters.titleType = null;
    } else {
        document.querySelectorAll('#titleFilters .chip').forEach(c => c.classList.remove('active'));
        btn.classList.add('active');
        currentFilters.titleType = val;
    }
    currentPage = 1;
    renderSales();
}

function quickFilter(tag) {
    currentFilters.quickTag = tag;
    currentPage = 1;
    renderSales();
}

function resetAllFilters() {
    currentFilters = {types: [], amenities: [], minPrice: null, maxPrice: null, beds: null, titleType: null, verified: false, search: '', quickTag: null};
    document.querySelectorAll('#typeFilters input[type="checkbox"]').forEach(cb => cb.checked = false);
    document.querySelectorAll('.amenity-checkbox').forEach(cb => cb.checked = false);
    document.querySelectorAll('#bedFilters .chip').forEach(c => c.classList.remove('active'));
    document.querySelectorAll('#titleFilters .chip').forEach(c => c.classList.remove('active'));
    document.getElementById('minPrice').value = '';
    document.getElementById('maxPrice').value = '';
    document.getElementById('verifiedToggle').checked = false;
    currentPage = 1;
    renderSales();
}

function removeQuickTag() { currentFilters.quickTag = null; renderSales(); }
function removeType(t) {
    currentFilters.types = currentFilters.types.filter(x => x !== t);
    document.querySelectorAll('#typeFilters input').forEach(cb => { if (cb.value === t) cb.checked = false; });
    renderSales();
}
function removeAmenity(a) {
    currentFilters.amenities = currentFilters.amenities.filter(x => x !== a);
    document.querySelectorAll('.amenity-checkbox').forEach(cb => { if (cb.value === a) cb.checked = false; });
    renderSales();
}
function removeBeds() {
    currentFilters.beds = null;
    document.querySelectorAll('#bedFilters .chip').forEach(c => c.classList.remove('active'));
    renderSales();
}
function removeTitleType() {
    currentFilters.titleType = null;
    document.querySelectorAll('#titleFilters .chip').forEach(c => c.classList.remove('active'));
    renderSales();
}
function removeVerified() {
    currentFilters.verified = false;
    document.getElementById('verifiedToggle').checked = false;
    renderSales();
}
function removePrice() {
    currentFilters.minPrice = null;
    currentFilters.maxPrice = null;
    document.getElementById('minPrice').value = '';
    document.getElementById('maxPrice').value = '';
    renderSales();
}

/* ============================================================
   SORT & VIEW
   ============================================================ */
function applySort() {
    currentSort = document.getElementById('sortSelect').value;
    currentPage = 1;
    renderSales();
}

function setView(view, btn) {
    currentView = view;
    document.querySelectorAll('.view-btn').forEach(b => {
        b.classList.remove('active', 'bg-saleGold', 'text-white');
        b.classList.add('text-gray-400');
    });
    btn.classList.add('active', 'bg-saleGold', 'text-white');
    btn.classList.remove('text-gray-400');
    renderSales();
}

/* ============================================================
   SEARCH
   ============================================================ */
function handleSearch(e) {
    e.preventDefault();
    currentFilters.search = document.getElementById('searchLocation').value.toLowerCase();
    currentPage = 1;
    renderSales();
    document.getElementById('salesGrid').scrollIntoView({behavior: 'smooth', block: 'start'});
}

function activateField(el) {
    el.classList.add('active');
    setTimeout(() => {
        const input = el.querySelector('input, select');
        if (input) input.focus();
    }, 50);
    setTimeout(() => el.classList.remove('active'), 2000);
}

/* ============================================================
   FAVORITE
   ============================================================ */
function toggleFavorite(e, btn) {
    e.stopPropagation();
    btn.classList.toggle('liked');
    const icon = btn.querySelector('i');
    if (btn.classList.contains('liked')) {
        icon.classList.remove('far');
        icon.classList.add('fas');
    } else {
        icon.classList.remove('fas');
        icon.classList.add('far');
    }
}

/* ============================================================
   MORTGAGE CALCULATOR
   ============================================================ */
function calcMortgage(price) {
    const principal = price * 0.8; // 80% loan (20% down)
    const monthlyRate = 0.12 / 12;
    const n = 20 * 12; // 20 years
    const monthly = principal * monthlyRate * Math.pow(1 + monthlyRate, n) / (Math.pow(1 + monthlyRate, n) - 1);
    return Math.round(monthly);
}

function formatMoney(num) {
    if (num >= 1000000) return `KSh ${(num / 1000000).toFixed(2)}M`;
    return `KSh ${num.toLocaleString()}`;
}

/* ============================================================
   MODAL
   ============================================================ */
function openSaleModal(id) {
    const r = salesData.find(s => s.id === id);
    if (!r) return;
    modalSlideIndex = 0;

    document.getElementById('modalMainImg').src = r.images[0];
    document.getElementById('modalTitle').textContent = r.title;
    document.getElementById('modalLocation').textContent = r.location;
    document.getElementById('modalRating').textContent = r.rating;
    document.getElementById('modalReviews').textContent = `(${r.reviews} reviews)`;
    document.getElementById('modalRating2').textContent = r.rating;
    document.getElementById('modalReviews2').textContent = `${r.reviews} reviews`;
    document.getElementById('modalBeds').textContent = r.beds;
    document.getElementById('modalBaths').textContent = r.baths;
    document.getElementById('modalParking').textContent = r.parking;
    document.getElementById('modalSize').textContent = r.size;
    document.getElementById('modalDesc').textContent = r.desc;
    document.getElementById('modalPrice').textContent = `KSh ${r.price.toLocaleString()}`;
    document.getElementById('modalAgentName').textContent = r.agentName;
    document.getElementById('modalAgentInitial').textContent = r.agentInitial;
    document.getElementById('modalPhotoCount').textContent = r.images.length;
    document.getElementById('modalBadge').textContent = r.badge;
    document.getElementById('modalTitleBadge').innerHTML = `<i class="fas fa-check-circle text-[8px]"></i> ${r.titleType === 'freehold' ? 'Freehold' : 'Leasehold'}`;
    document.getElementById('modalVerified').classList.toggle('hidden', !r.verified);
    document.getElementById('modalTitleType').textContent = r.titleType === 'freehold' ? 'Freehold Title' : 'Leasehold Title';

    // Price lines
    document.getElementById('modalPriceLine').textContent = `KSh ${r.price.toLocaleString()}`;
    const legal = Math.round(r.price * 0.04);
    const stamp = Math.round(r.price * 0.04);
    document.getElementById('modalLegal').textContent = `KSh ${legal.toLocaleString()}`;
    document.getElementById('modalStamp').textContent = `KSh ${stamp.toLocaleString()}`;
    document.getElementById('modalTotal').textContent = `KSh ${(r.price + legal + stamp).toLocaleString()}`;

    // Mortgage
    const mortgage = calcMortgage(r.price);
    document.getElementById('modalMortgage').textContent = formatMoney(mortgage);

    // Amenities
    const amenContainer = document.getElementById('modalAmenities');
    amenContainer.innerHTML = r.amenities.map(a => `
        <div class="flex items-center gap-2 text-xs text-gray-600">
            <i class="fas ${a.icon} text-saleGold text-[11px] w-4 text-center"></i>
            <span>${a.label}</span>
        </div>
    `).join('');

    // Thumbnails
    const thumbContainer = document.getElementById('modalThumbs');
    thumbContainer.innerHTML = r.images.map((img, i) => `
        <button onclick="changeModalImage(${i}, '${img}')" class="modal-thumb ${i === 0 ? 'ring-2 ring-white' : 'ring-1 ring-white/40'} w-10 h-10 md:w-12 md:h-12 rounded-lg overflow-hidden flex-shrink-0 transition-all hover:ring-2 hover:ring-white">
            <img src="${img}" alt="thumb" class="w-full h-full object-cover">
        </button>
    `).join('');

    const modal = document.getElementById('saleModal');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    requestAnimationFrame(() => modal.classList.add('show'));

    startModalSlideshow(r.images);
}

function changeModalImage(index, src) {
    modalSlideIndex = index;
    const main = document.getElementById('modalMainImg');
    main.style.opacity = '0';
    setTimeout(() => {
        main.src = src;
        main.style.opacity = '1';
    }, 200);
    document.querySelectorAll('.modal-thumb').forEach((t, i) => {
        t.className = i === index
            ? 'modal-thumb ring-2 ring-white w-10 h-10 md:w-12 md:h-12 rounded-lg overflow-hidden flex-shrink-0 transition-all'
            : 'modal-thumb ring-1 ring-white/40 w-10 h-10 md:w-12 md:h-12 rounded-lg overflow-hidden flex-shrink-0 transition-all hover:ring-2 hover:ring-white';
    });
}

function startModalSlideshow(images) {
    if (modalSlideInterval) clearInterval(modalSlideInterval);
    modalSlideInterval = setInterval(() => {
        if (document.getElementById('saleModal').classList.contains('hidden')) return;
        modalSlideIndex = (modalSlideIndex + 1) % images.length;
        const main = document.getElementById('modalMainImg');
        main.style.opacity = '0';
        setTimeout(() => {
            main.src = images[modalSlideIndex];
            main.style.opacity = '1';
        }, 200);
        document.querySelectorAll('.modal-thumb').forEach((t, i) => {
            t.className = i === modalSlideIndex
                ? 'modal-thumb ring-2 ring-white w-10 h-10 md:w-12 md:h-12 rounded-lg overflow-hidden flex-shrink-0 transition-all'
                : 'modal-thumb ring-1 ring-white/40 w-10 h-10 md:w-12 md:h-12 rounded-lg overflow-hidden flex-shrink-0 transition-all hover:ring-2 hover:ring-white';
        });
    }, 4500);
}

function closeSaleModal() {
    const modal = document.getElementById('saleModal');
    modal.classList.remove('show');
    setTimeout(() => modal.classList.add('hidden'), 300);
    document.body.style.overflow = '';
    if (modalSlideInterval) {
        clearInterval(modalSlideInterval);
        modalSlideInterval = null;
    }
}

document.getElementById('saleModal').addEventListener('click', function(e) {
    if (e.target === this) closeSaleModal();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeSaleModal();
});

/* ============================================================
   INIT
   ============================================================ */
document.addEventListener('DOMContentLoaded', function() {
    renderSales();
});

</script>

</body>
</html>