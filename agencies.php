<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Primary Meta Tags -->
    <title>Real Estate Agencies & Cleaning Companies in Kenya | HAVENTRA Agencies</title>
    <meta name="title" content="Real Estate Agencies & Cleaning Companies in Kenya | HAVENTRA Agencies">
    <meta name="description" content="Discover trusted real estate agencies, property managers, and professional cleaning companies across Kenya. Browse verified companies, compare services, and connect with the right partner on HAVENTRA.">
    <meta name="keywords" content="real estate agencies Kenya, property management companies Nairobi, cleaning companies Kenya, real estate agents, property managers, professional cleaners, agency directory, HAVENTRA agencies">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://haventra.com/agencies/">
    
    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://haventra.com/agencies/">
    <meta property="og:title" content="Real Estate Agencies & Cleaning Companies in Kenya | HAVENTRA">
    <meta property="og:description" content="Discover trusted real estate agencies, property managers, and professional cleaning companies across Kenya.">
    <meta property="og:image" content="https://haventra.com/logo.png">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://haventra.com/agencies/">
    <meta property="twitter:title" content="Real Estate Agencies & Cleaning Companies in Kenya | HAVENTRA">
    <meta property="twitter:description" content="Discover trusted real estate agencies, property managers, and professional cleaning companies across Kenya.">
    <meta property="twitter:image" content="https://haventra.com/logo.png">
    
    <!-- Schema.org -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebPage",
        "name": "Real Estate Agencies & Cleaning Companies Directory",
        "description": "Browse verified real estate agencies, property managers, and cleaning companies across Kenya.",
        "url": "https://haventra.com/agencies/",
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
                        agencyPurple: '#6D28D9',
                        cleaningTeal: '#0D9488',
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
        .agency-hero {
            background: linear-gradient(135deg, rgba(0, 21, 64, 0.93), rgba(109, 40, 217, 0.78)),
                        url('https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1600&q=80');
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
        .search-field.active { background: #F3F4F6; box-shadow: inset 0 0 0 2px #6D28D9; }

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
        .chip:hover { border-color: #6D28D9; color: #6D28D9; }
        .chip.active {
            background: #6D28D9; border-color: #6D28D9; color: white;
            box-shadow: 0 4px 12px rgba(109, 40, 217, 0.3);
        }

        /* ===== AGENCY CARD ===== */
        .agency-card {
            background: white; border-radius: 18px; overflow: hidden;
            cursor: pointer; transition: all 0.35s cubic-bezier(0.25, 0.8, 0.25, 1);
            border: 1px solid #F1F5F9;
            box-shadow: 0 4px 10px -2px rgba(0, 0, 0, 0.04);
            display: flex; flex-direction: column;
        }
        .agency-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 24px 40px -12px rgba(0, 0, 0, 0.18);
            border-color: rgba(109, 40, 217, 0.25);
        }
        .agency-card .agency-logo {
            transition: transform 0.4s ease;
        }
        .agency-card:hover .agency-logo { transform: rotate(-5deg) scale(1.08); }

        .agency-logo {
            width: 56px; height: 56px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
            overflow: hidden;
        }
        .agency-logo img {
            width: 100%; height: 100%; object-fit: cover; display: block;
        }

        .verified-badge {
            display: inline-flex; align-items: center; gap: 3px;
            padding: 2px 7px;
            border-radius: 6px;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .agency-tag {
            font-size: 9px;
            padding: 2px 8px;
            border-radius: 9999px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            white-space: nowrap;
        }

        .favorite-btn {
            position: absolute; top: 12px; right: 12px;
            width: 34px; height: 34px; border-radius: 9999px;
            background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(4px);
            display: flex; align-items: center; justify-content: center;
            color: #374151; font-size: 0.8rem; transition: all 0.25s ease; z-index: 5;
        }
        .favorite-btn:hover { background: white; transform: scale(1.1); }
        .favorite-btn.liked { color: #EF4444; }
        .favorite-btn.liked i { animation: heartPop 0.35s ease; }
        @keyframes heartPop { 0%{transform:scale(1);} 50%{transform:scale(1.4);} 100%{transform:scale(1);} }

        /* ===== MODAL ===== */
        #agencyModal { opacity: 0; transition: opacity 0.3s ease; }
        #agencyModal.show { opacity: 1; }
        #agencyModal .modal-content {
            transform: scale(0.95) translateY(10px);
            transition: transform 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        #agencyModal.show .modal-content { transform: scale(1) translateY(0); }

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

        /* Star rating */
        .star-filled { color: #F59E0B; }
        .star-empty { color: #D1D5DB; }
    </style>
</head>
<body class="bg-[#FAFAFA] text-gray-800 antialiased">


<!-- ================= HERO WITH SEARCH ================= -->
<section class="agency-hero relative pt-8 pb-16 md:pt-12 md:pb-24 px-4">
    
    <div class="max-w-4xl mx-auto text-center relative z-10 mb-6 md:mb-10">
        <span class="inline-block bg-white/15 backdrop-blur text-white text-[10px] md:text-xs font-bold uppercase tracking-[0.2em] px-4 py-1.5 rounded-full mb-4 border border-white/20">
            <i class="fas fa-handshake mr-1.5 text-accentOrange"></i> Verified Agency Directory
        </span>
        <h1 class="font-serif text-3xl md:text-6xl text-white font-semibold leading-tight mb-3 md:mb-4 drop-shadow-lg">
            Find trusted <span class="italic text-accentOrange">partners</span>
        </h1>
        <p class="text-white/80 text-sm md:text-base max-w-2xl mx-auto">
            Browse real estate agencies, property managers, and professional cleaning companies across Kenya — all verified, all reviewed.
        </p>
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
                        <button onclick="toggleFilters()" class="lg:hidden w-full flex items-center justify-between p-4">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-filter text-agencyPurple"></i>
                                <h3 class="font-serif text-base text-navy font-semibold">Filters</h3>
                            </div>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform" id="filterChevron"></i>
                        </button>
                        <div id="filterContent" class="hidden lg:block px-5 pb-5 pt-0 lg:pt-5">
                            <div class="hidden lg:flex items-center justify-between mb-4">
                                <h3 class="font-serif text-lg text-navy font-semibold">Filters</h3>
                                <button onclick="resetAllFilters()" class="text-[10px] text-agencyPurple hover:text-violet-800 font-semibold underline transition-colors">
                                    Reset all
                                </button>
                            </div>

                        <!-- Category -->
                        <div class="mb-5">
                            <h4 class="text-[11px] uppercase tracking-wider font-bold text-gray-500 mb-2.5">Category</h4>
                            <div class="space-y-2" id="categoryFilters">
                                <label class="flex items-center gap-2.5 cursor-pointer group">
                                    <input type="radio" name="category" value="all" checked class="w-4 h-4 border-gray-300 text-agencyPurple focus:ring-agencyPurple">
                                    <span class="text-xs text-gray-600 group-hover:text-agencyPurple transition-colors">All Categories</span>
                                </label>
                                <label class="flex items-center gap-2.5 cursor-pointer group">
                                    <input type="radio" name="category" value="agency" class="w-4 h-4 border-gray-300 text-agencyPurple focus:ring-agencyPurple">
                                    <span class="text-xs text-gray-600 group-hover:text-agencyPurple transition-colors"><i class="fas fa-building text-[10px] text-agencyPurple mr-1"></i> Real Estate Agency</span>
                                </label>
                                <label class="flex items-center gap-2.5 cursor-pointer group">
                                    <input type="radio" name="category" value="management" class="w-4 h-4 border-gray-300 text-agencyPurple focus:ring-agencyPurple">
                                    <span class="text-xs text-gray-600 group-hover:text-agencyPurple transition-colors"><i class="fas fa-tasks text-[10px] text-accentBlue mr-1"></i> Property Management</span>
                                </label>
                                <label class="flex items-center gap-2.5 cursor-pointer group">
                                    <input type="radio" name="category" value="cleaning" class="w-4 h-4 border-gray-300 text-agencyPurple focus:ring-agencyPurple">
                                    <span class="text-xs text-gray-600 group-hover:text-agencyPurple transition-colors"><i class="fas fa-spray-can text-[10px] text-cleaningTeal mr-1"></i> Cleaning Company</span>
                                </label>
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="mb-5">
                            <h4 class="text-[11px] uppercase tracking-wider font-bold text-gray-500 mb-2.5">Location</h4>
                            <div class="space-y-2" id="locationFilters">
                                <label class="flex items-center gap-2.5 cursor-pointer group">
                                    <input type="checkbox" value="Nairobi" class="agency-filter-checkbox w-4 h-4 rounded border-gray-300 text-agencyPurple focus:ring-agencyPurple">
                                    <span class="text-xs text-gray-600 group-hover:text-agencyPurple transition-colors">Nairobi</span>
                                </label>
                                <label class="flex items-center gap-2.5 cursor-pointer group">
                                    <input type="checkbox" value="Mombasa" class="agency-filter-checkbox w-4 h-4 rounded border-gray-300 text-agencyPurple focus:ring-agencyPurple">
                                    <span class="text-xs text-gray-600 group-hover:text-agencyPurple transition-colors">Mombasa</span>
                                </label>
                                <label class="flex items-center gap-2.5 cursor-pointer group">
                                    <input type="checkbox" value="Kisumu" class="agency-filter-checkbox w-4 h-4 rounded border-gray-300 text-agencyPurple focus:ring-agencyPurple">
                                    <span class="text-xs text-gray-600 group-hover:text-agencyPurple transition-colors">Kisumu</span>
                                </label>
                                <label class="flex items-center gap-2.5 cursor-pointer group">
                                    <input type="checkbox" value="Nakuru" class="agency-filter-checkbox w-4 h-4 rounded border-gray-300 text-agencyPurple focus:ring-agencyPurple">
                                    <span class="text-xs text-gray-600 group-hover:text-agencyPurple transition-colors">Nakuru</span>
                                </label>
                            </div>
                        </div>

                        <!-- Rating -->
                        <div class="mb-5">
                            <h4 class="text-[11px] uppercase tracking-wider font-bold text-gray-500 mb-2.5">Minimum Rating</h4>
                            <div class="flex flex-wrap gap-1.5" id="ratingFilters">
                                <button onclick="toggleRatingFilter(this, 4)" class="chip !text-[10px] !py-1 !px-3">4★+</button>
                                <button onclick="toggleRatingFilter(this, 4.5)" class="chip !text-[10px] !py-1 !px-3">4.5★+</button>
                                <button onclick="toggleRatingFilter(this, 5)" class="chip !text-[10px] !py-1 !px-3">5★</button>
                            </div>
                        </div>

                        <!-- Verified toggle -->
                        <div class="pt-3 border-t border-gray-100">
                            <label class="flex items-center justify-between cursor-pointer">
                                <span class="text-xs text-gray-600 font-medium">
                                    <i class="fas fa-certificate text-accentOrange mr-1"></i> Verified only
                                </span>
                                <div class="relative">
                                    <input type="checkbox" id="verifiedToggle" class="sr-only peer">
                                    <div class="w-9 h-5 bg-gray-200 rounded-full peer-checked:bg-agencyPurple transition-colors"></div>
                                    <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow peer-checked:translate-x-4 transition-transform"></div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                    <!-- Stats card -->
                    <div class="bg-gradient-to-br from-agencyPurple to-violet-700 rounded-2xl p-5 text-white shadow-xl shadow-agencyPurple/30">
                        <h3 class="font-serif text-lg font-semibold mb-3">Why HAVENTRA?</h3>
                        <div class="space-y-2.5 text-xs">
                            <div class="flex items-start gap-2">
                                <i class="fas fa-check-circle text-accentOrange mt-0.5 text-[10px]"></i>
                                <span class="text-white/90">Every company is vetted and verified before listing</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <i class="fas fa-check-circle text-accentOrange mt-0.5 text-[10px]"></i>
                                <span class="text-white/90">Real reviews from real clients</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <i class="fas fa-check-circle text-accentOrange mt-0.5 text-[10px]"></i>
                                <span class="text-white/90">Direct contact — no middlemen, no hidden fees</span>
                            </div>
                        </div>
                    </div>

                    <!-- Join card -->
                    <div class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 p-5">
                        <h4 class="font-serif text-sm text-navy font-semibold mb-1.5">Are you a company?</h4>
                        <p class="text-[11px] text-gray-500 leading-relaxed mb-3">List your agency on HAVENTRA and reach thousands of clients looking for your services.</p>
                        <a href="/contact.html" class="inline-flex items-center gap-1.5 bg-agencyPurple text-white text-[10px] font-bold px-3 py-1.5 rounded-lg hover:bg-violet-800 transition-colors">
                            <i class="fas fa-plus-circle"></i> List Your Agency
                        </a>
                    </div>

                </div>
            </aside>

            <!-- ===== AGENCY LISTINGS ===== -->
            <div class="lg:w-3/4 w-full">

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
                    <div>
                        <h2 class="font-serif text-2xl md:text-3xl text-navy font-semibold">
                            <span id="resultsCount">12</span> companies found
                        </h2>
                        <p class="text-xs text-gray-500 mt-0.5" id="filterSummary">Showing all agencies and companies</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="relative">
                            <select id="sortSelect" onchange="applySort()" class="appearance-none bg-white border border-gray-200 rounded-lg pl-3 pr-8 py-2 text-xs font-medium text-gray-700 cursor-pointer focus:outline-none focus:ring-2 focus:ring-agencyPurple/30">
                                <option value="recommended">Recommended</option>
                                <option value="rating">Top Rated</option>
                                <option value="name">Name (A-Z)</option>
                                <option value="experience">Most Experienced</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] pointer-events-none"></i>
                        </div>
                    </div>
                </div>

                <div id="activeFilters" class="flex flex-wrap gap-1.5 mb-4 hidden"></div>

                <div id="agenciesGrid" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 md:gap-5"></div>

                <div id="emptyState" class="hidden text-center py-20">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-search text-3xl text-gray-300"></i>
                    </div>
                    <h3 class="font-serif text-xl text-navy font-semibold mb-2">No companies found</h3>
                    <p class="text-sm text-gray-500 mb-4">Try adjusting your filters or search criteria.</p>
                    <button onclick="resetAllFilters()" class="px-5 py-2.5 bg-agencyPurple text-white text-sm font-semibold rounded-lg hover:bg-violet-800 transition-colors">
                        Clear All Filters
                    </button>
                </div>

                <div class="flex items-center justify-center gap-1.5 mt-8" id="pagination"></div>

            </div>
        </div>
    </div>
</section>


<!-- ================= AGENCY PROFILE MODAL ================= -->
<div id="agencyModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-[3000] hidden items-center justify-center p-2 md:p-4">
    <div class="modal-content bg-white max-w-3xl w-full max-h-[95vh] md:max-h-[90vh] overflow-y-auto thin-scroll rounded-2xl md:rounded-3xl shadow-2xl relative">
        
        <button onclick="closeAgencyModal()" class="sticky top-3 float-right mr-3 ml-auto w-9 h-9 bg-white/90 backdrop-blur rounded-full shadow-lg flex items-center justify-center hover:bg-agencyPurple hover:text-white transition-all z-50 border border-gray-200">
            <i class="fas fa-times text-sm"></i>
        </button>

        <div class="p-4 md:p-8">

            <!-- Header -->
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 mb-6 border-b border-gray-100 pb-6">
                <div id="modalLogo" class="w-20 h-20 md:w-24 md:h-24 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-inner">
                    <i class="fas fa-building text-3xl md:text-4xl"></i>
                </div>
                <div class="text-center sm:text-left flex-1">
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 mb-2">
                        <span id="modalCategory" class="agency-tag bg-agencyPurple/10 text-agencyPurple border border-agencyPurple/20">Real Estate Agency</span>
                        <span id="modalVerified" class="verified-badge bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <i class="fas fa-certificate text-[8px]"></i> Verified
                        </span>
                    </div>
                    <h2 id="modalName" class="font-serif text-2xl md:text-3xl text-navy font-semibold">Company Name</h2>
                    <p id="modalLocation" class="text-gray-500 text-xs md:text-sm mt-1">
                        <i class="fas fa-map-marker-alt text-agencyPurple mr-1"></i> Location
                    </p>
                    <div class="flex items-center justify-center sm:justify-start gap-1.5 mt-2" id="modalRating">
                        <!-- stars -->
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-3 gap-2 md:gap-4 mb-6">
                <div class="bg-gray-50 rounded-xl p-3 text-center border border-gray-100">
                    <p id="modalYears" class="text-navy font-bold text-lg md:text-2xl">15+</p>
                    <p class="text-gray-500 text-[9px] md:text-[10px] uppercase tracking-wide font-semibold">Years Exp.</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-3 text-center border border-gray-100">
                    <p id="modalClients" class="text-navy font-bold text-lg md:text-2xl">500+</p>
                    <p class="text-gray-500 text-[9px] md:text-[10px] uppercase tracking-wide font-semibold">Clients Served</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-3 text-center border border-gray-100">
                    <p id="modalSatisfaction" class="text-navy font-bold text-lg md:text-2xl">98%</p>
                    <p class="text-gray-500 text-[9px] md:text-[10px] uppercase tracking-wide font-semibold">Satisfaction</p>
                </div>
            </div>

            <!-- About -->
            <div class="mb-6">
                <h3 class="font-serif text-xl text-navy font-semibold mb-2">
                    <i class="fas fa-info-circle text-agencyPurple mr-2 text-sm"></i> About the Company
                </h3>
                <p id="modalAbout" class="text-gray-600 text-xs md:text-sm leading-relaxed"></p>
            </div>

            <!-- Achievements -->
            <div class="mb-6">
                <h3 class="font-serif text-xl text-navy font-semibold mb-2.5">
                    <i class="fas fa-trophy text-accentOrange mr-2 text-sm"></i> Key Achievements
                </h3>
                <ul id="modalAchievements" class="space-y-2"></ul>
            </div>

            <!-- Services -->
            <div class="mb-6">
                <h3 class="font-serif text-xl text-navy font-semibold mb-2.5">
                    <i class="fas fa-concierge-bell text-agencyPurple mr-2 text-sm"></i> Services Offered
                </h3>
                <div id="modalServices" class="flex flex-wrap gap-2"></div>
            </div>

            <!-- Contact info -->
            <div class="mb-6 p-4 bg-gray-50 rounded-xl">
                <h3 class="font-serif text-base text-navy font-semibold mb-2.5">
                    <i class="fas fa-address-card text-agencyPurple mr-1.5 text-xs"></i> Contact Details
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 text-xs">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-phone text-agencyPurple text-[10px] w-4"></i>
                        <span id="modalPhone" class="text-gray-600">+254 700 000 000</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-envelope text-agencyPurple text-[10px] w-4"></i>
                        <span id="modalEmail" class="text-gray-600">info@company.co.ke</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fab fa-whatsapp text-[#25D366] text-[11px] w-4"></i>
                        <span id="modalWhatsapp" class="text-gray-600">+254 700 000 000</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-globe text-agencyPurple text-[10px] w-4"></i>
                        <span id="modalWebsite" class="text-gray-600">www.company.co.ke</span>
                    </div>
                </div>
            </div>

            <!-- CTA -->
            <div class="flex gap-2 md:gap-3 pt-4 border-t border-gray-100">
                <button class="flex-1 py-2.5 md:py-3 bg-gradient-to-r from-agencyPurple to-violet-700 text-white text-xs md:text-sm rounded-lg font-bold hover:shadow-lg transition-all">
                    <i class="fas fa-phone-alt mr-2"></i> Call Now
                </button>
                <button class="flex-1 py-2.5 md:py-3 border border-agencyPurple text-agencyPurple text-xs md:text-sm rounded-lg font-bold hover:bg-agencyPurple hover:text-white transition-colors">
                    <i class="fas fa-envelope mr-2"></i> Send Message
                </button>
                <button onclick="toggleFavorite(event, this)" class="w-10 h-10 md:w-12 md:h-12 flex items-center justify-center border border-gray-200 rounded-lg text-gray-500 hover:bg-red-50 hover:text-red-500 hover:border-red-200 transition-colors">
                    <i class="far fa-heart"></i>
                </button>
            </div>

        </div>
    </div>
</div>


<!-- ================= SCRIPT ================= -->
<script>
/* ============================================================
   DATA — 12 companies (4 agencies, 4 management, 4 cleaning)
   ============================================================ */
const agenciesData = [
    // ===== REAL ESTATE AGENCIES =====
    {
        id: 'agency-1',
        name: 'Prestige Realty Group',
        category: 'agency',
        categoryLabel: 'Real Estate Agency',
        location: 'Westlands, Nairobi',
        city: 'Nairobi',
        rating: 5,
        reviews: 128,
        verified: true,
        years: 15,
        yearsLabel: '15+',
        clients: '500+',
        satisfaction: '98%',
        icon: 'fa-handshake',
        logo: 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=200&q=80',
        color: 'purple',
        shortDesc: 'Luxury residential & commercial sales.',
        tags: ['luxury', 'sales', 'commercial'],
        about: 'Prestige Realty Group is a leading real estate agency specializing in high-end residential and commercial properties across Nairobi. With over 15 years of experience, we have built a reputation for integrity, professionalism, and exceptional client service. Our team of seasoned agents is dedicated to helping you find your perfect property or achieve the best price for your sale.',
        achievements: [
            'Top Real Estate Agency 2023 — Kenya Property Awards',
            'Over 500 successful property transactions',
            'Exclusive listing partner for 3 major developers',
            'Featured in Business Daily & Property Magazine'
        ],
        services: ['Property Sales', 'Luxury Rentals', 'Investment Advisory', 'Valuation', 'Property Marketing'],
        phone: '+254 700 111 222',
        email: 'info@prestigerealty.co.ke',
        whatsapp: '+254 700 111 222',
        website: 'www.prestigerealty.co.ke'
    },
    {
        id: 'agency-2',
        name: 'Urban Nest Properties',
        category: 'agency',
        categoryLabel: 'Real Estate Agency',
        location: 'Kilimani, Nairobi',
        city: 'Nairobi',
        rating: 4,
        reviews: 86,
        verified: true,
        years: 8,
        yearsLabel: '8+',
        clients: '320+',
        satisfaction: '95%',
        icon: 'fa-city',
        logo: 'https://images.unsplash.com/photo-1560518889-ce09f59e3a69?auto=format&fit=crop&w=200&q=80',
        color: 'purple',
        shortDesc: 'Modern apartments & townhouses.',
        tags: ['city', 'apartments', 'investment'],
        about: 'Urban Nest Properties focuses on the growing demand for modern, affordable urban housing. We connect young professionals and families with quality homes in Nairobi\'s most vibrant neighborhoods. Our deep knowledge of the urban market and commitment to transparent dealings make us the go-to agency for city living.',
        achievements: [
            'Rising Star Agency 2022 — East Africa Real Estate Summit',
            '320+ satisfied clients and counting',
            'Specialized in off-plan property investments',
            'Official partner of 5 leading property developers'
        ],
        services: ['Apartment Rentals', 'Townhouse Sales', 'Off-Plan Investments', 'Relocation Support', 'Tenant Screening'],
        phone: '+254 700 222 333',
        email: 'hello@urbannest.co.ke',
        whatsapp: '+254 700 222 333',
        website: 'www.urbannest.co.ke'
    },
    {
        id: 'agency-3',
        name: 'Coastal Homes Ltd',
        category: 'agency',
        categoryLabel: 'Real Estate Agency',
        location: 'Diani, Mombasa',
        city: 'Mombasa',
        rating: 4,
        reviews: 94,
        verified: true,
        years: 10,
        yearsLabel: '10+',
        clients: '180+',
        satisfaction: '96%',
        icon: 'fa-water',
        logo: 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=200&q=80',
        color: 'purple',
        shortDesc: 'Beachfront & holiday property experts.',
        tags: ['beachfront', 'luxury', 'holiday'],
        about: 'Coastal Homes Ltd is the premier agency for beachfront properties, holiday villas, and vacation rentals along Kenya\'s stunning coastline. We help clients find their perfect seaside retreat or investment property. From Diani to Watamu, our local expertise ensures you get the best coastal property deals.',
        achievements: [
            'Best Coastal Agency 2023 — Mombasa Property Expo',
            '180+ beachfront properties sold',
            'Exclusive partner with 5 luxury resorts',
            'Specialists in holiday rental management'
        ],
        services: ['Beachfront Sales', 'Holiday Rentals', 'Property Management', 'Investment Tours', 'Valuation'],
        phone: '+254 700 333 444',
        email: 'sales@coastalhomes.co.ke',
        whatsapp: '+254 700 333 444',
        website: 'www.coastalhomes.co.ke'
    },
    {
        id: 'agency-4',
        name: 'Summit & Valley Realty',
        category: 'agency',
        categoryLabel: 'Real Estate Agency',
        location: 'Karen, Nairobi',
        city: 'Nairobi',
        rating: 5,
        reviews: 156,
        verified: true,
        years: 20,
        yearsLabel: '20+',
        clients: '750+',
        satisfaction: '99%',
        icon: 'fa-mountain',
        logo: 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=200&q=80',
        color: 'purple',
        shortDesc: 'Premium estates & land sales.',
        tags: ['luxury', 'land', 'estates'],
        about: 'Summit & Valley Realty has been the trusted name in premium estate and land sales for over two decades. Our deep understanding of the local market and commitment to excellence set us apart. We specialize in high-value properties in Karen, Runda, and other prestigious neighborhoods, serving both local and international clients.',
        achievements: [
            'Lifetime Achievement Award 2022 — Kenya Real Estate Federation',
            '750+ premium properties sold',
            'Trusted by 3 diplomatic missions for housing',
            'Top land sales agency in Nairobi for 5 consecutive years'
        ],
        services: ['Estate Sales', 'Land Acquisition', 'Property Development', 'Concierge Services', 'Legal Support'],
        phone: '+254 700 444 555',
        email: 'info@summitvalley.co.ke',
        whatsapp: '+254 700 444 555',
        website: 'www.summitvalley.co.ke'
    },

    // ===== PROPERTY MANAGEMENT =====
    {
        id: 'management-1',
        name: 'Guardian Property Managers',
        category: 'management',
        categoryLabel: 'Property Management',
        location: 'CBD, Nairobi',
        city: 'Nairobi',
        rating: 5,
        reviews: 112,
        verified: true,
        years: 12,
        yearsLabel: '12+',
        clients: '200+',
        satisfaction: '97%',
        icon: 'fa-shield-alt',
        logo: 'https://images.unsplash.com/photo-1556911212-b92d76c4e7ee?auto=format&fit=crop&w=200&q=80',
        color: 'blue',
        shortDesc: 'Full-service residential management.',
        tags: ['management', 'residential'],
        about: 'Guardian Property Managers offers comprehensive residential and commercial property management services. From tenant screening to maintenance coordination, we handle everything so you can enjoy passive income. Our dedicated team ensures your property is well-maintained and your tenants are happy.',
        achievements: [
            'Property Management Firm of the Year 2023',
            '200+ properties under active management',
            'Average tenant occupancy rate of 94%',
            'ISO 9001:2015 certified management processes'
        ],
        services: ['Tenant Screening', 'Rent Collection', 'Maintenance', 'Financial Reporting', 'Legal Compliance'],
        phone: '+254 700 555 666',
        email: 'info@guardianpm.co.ke',
        whatsapp: '+254 700 555 666',
        website: 'www.guardianpm.co.ke'
    },
    {
        id: 'management-2',
        name: 'RentWise Solutions',
        category: 'management',
        categoryLabel: 'Property Management',
        location: 'Westlands, Nairobi',
        city: 'Nairobi',
        rating: 4,
        reviews: 78,
        verified: true,
        years: 6,
        yearsLabel: '6+',
        clients: '150+',
        satisfaction: '93%',
        icon: 'fa-laptop-house',
        logo: 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=200&q=80',
        color: 'blue',
        shortDesc: 'Tech-driven rental management.',
        tags: ['management', 'tech', 'rentals'],
        about: 'RentWise Solutions leverages technology to streamline rental management. Our digital platform gives landlords real-time insights into their properties, while tenants enjoy a seamless renting experience. From automated rent collection to smart maintenance scheduling, we make property management effortless.',
        achievements: [
            'Best PropTech Startup 2022 — Nairobi Innovation Week',
            '150+ properties managed digitally',
            'Reduced average vacancy period by 40%',
            'Over 10,000 rent transactions processed'
        ],
        services: ['Digital Rent Collection', 'Smart Maintenance', 'Tenant Portals', 'Market Analytics', 'Automated Reports'],
        phone: '+254 700 666 777',
        email: 'hello@rentwise.co.ke',
        whatsapp: '+254 700 666 777',
        website: 'www.rentwise.co.ke'
    },
    {
        id: 'management-3',
        name: 'Sterling Facilities Co.',
        category: 'management',
        categoryLabel: 'Property Management',
        location: 'Upper Hill, Nairobi',
        city: 'Nairobi',
        rating: 4,
        reviews: 63,
        verified: true,
        years: 9,
        yearsLabel: '9+',
        clients: '120+',
        satisfaction: '94%',
        icon: 'fa-building',
        logo: 'https://images.unsplash.com/photo-1486325212027-8081e485255e?auto=format&fit=crop&w=200&q=80',
        color: 'blue',
        shortDesc: 'Commercial & office facility management.',
        tags: ['management', 'commercial', 'office'],
        about: 'Sterling Facilities Co. specializes in the management of commercial office spaces and mixed-use developments. We ensure your facilities run smoothly, creating productive environments for your tenants. Our services cover everything from cleaning and security to energy management and compliance.',
        achievements: [
            'Excellence in Commercial Management 2023',
            '120+ commercial properties serviced',
            'Serving 5 multinational corporate clients',
            'Zero compliance violations in 3 years'
        ],
        services: ['Office Management', 'Facility Maintenance', 'Security Coordination', 'Energy Management', 'Compliance'],
        phone: '+254 700 777 888',
        email: 'info@sterlingfacilities.co.ke',
        whatsapp: '+254 700 777 888',
        website: 'www.sterlingfacilities.co.ke'
    },
    {
        id: 'management-4',
        name: 'HomeCare Custodians',
        category: 'management',
        categoryLabel: 'Property Management',
        location: 'Lavington, Nairobi',
        city: 'Nairobi',
        rating: 5,
        reviews: 89,
        verified: true,
        years: 7,
        yearsLabel: '7+',
        clients: '90+',
        satisfaction: '98%',
        icon: 'fa-home',
        logo: 'https://images.unsplash.com/photo-1580587771525-78b9dba3b91d?auto=format&fit=crop&w=200&q=80',
        color: 'blue',
        shortDesc: 'Boutique residential property care.',
        tags: ['management', 'luxury', 'residential'],
        about: 'HomeCare Custodians provides boutique property management services for high-end residential properties. Our personalized approach ensures every home is maintained to the highest standard. We work with a select number of clients, giving each property the attention and care it deserves.',
        achievements: [
            'Client Choice Award 2023 — Residential Management',
            '90+ luxury homes under management',
            '100% landlord retention rate for 3 consecutive years',
            'Featured in Kenya Homes Magazine'
        ],
        services: ['Luxury Home Management', 'Landlord Services', 'Tenant Relations', 'Emergency Response', 'Property Upgrades'],
        phone: '+254 700 888 999',
        email: 'care@homecare.co.ke',
        whatsapp: '+254 700 888 999',
        website: 'www.homecare.co.ke'
    },

    // ===== CLEANING COMPANIES =====
    {
        id: 'cleaning-1',
        name: 'Sparkle & Shine Pro',
        category: 'cleaning',
        categoryLabel: 'Cleaning Company',
        location: 'Industrial Area, Nairobi',
        city: 'Nairobi',
        rating: 5,
        reviews: 203,
        verified: true,
        years: 10,
        yearsLabel: '10+',
        clients: '300+',
        satisfaction: '99%',
        icon: 'fa-spray-can',
        logo: 'https://images.unsplash.com/photo-1556761175-5973dc0f32e7?auto=format&fit=crop&w=200&q=80',
        color: 'teal',
        shortDesc: 'Commercial & residential deep cleaning.',
        tags: ['cleaning', 'commercial', 'residential'],
        about: 'Sparkle & Shine Pro is Nairobi\'s most trusted cleaning company, offering a full range of commercial and residential cleaning services. Our trained professionals use eco-friendly products and state-of-the-art equipment to deliver spotless results every time. From offices to homes, we make spaces shine.',
        achievements: [
            'Best Cleaning Company 2023 — Nairobi Business Awards',
            '300+ regular commercial clients',
            'Eco-friendly certified by Green Kenya Initiative',
            'Over 50,000 cleaning jobs completed'
        ],
        services: ['Office Cleaning', 'Deep Cleaning', 'Move-In/Move-Out', 'Post-Construction Cleaning', 'Carpet Cleaning'],
        phone: '+254 700 999 000',
        email: 'info@sparkleshine.co.ke',
        whatsapp: '+254 700 999 000',
        website: 'www.sparkleshine.co.ke'
    },
    {
        id: 'cleaning-2',
        name: 'PureSpace Cleaning',
        category: 'cleaning',
        categoryLabel: 'Cleaning Company',
        location: 'Karen, Nairobi',
        city: 'Nairobi',
        rating: 4,
        reviews: 147,
        verified: true,
        years: 5,
        yearsLabel: '5+',
        clients: '200+',
        satisfaction: '96%',
        icon: 'fa-broom',
        logo: 'https://images.unsplash.com/photo-1541888946425-d81bb19940f4?auto=format&fit=crop&w=200&q=80',
        color: 'teal',
        shortDesc: 'Specialized residential & villa cleaning.',
        tags: ['cleaning', 'residential', 'luxury'],
        about: 'PureSpace Cleaning specializes in high-end residential cleaning, including villas, apartments, and holiday homes. We treat every home with the care and attention it deserves, ensuring a spotless and healthy living environment. Our teams are trained, vetted, and equipped with premium cleaning products.',
        achievements: [
            'Top Rated Cleaning Service 2023 — Customer Choice',
            '200+ luxury homes cleaned monthly',
            '100% satisfaction guarantee on all services',
            'Trusted by 15 diplomatic residences'
        ],
        services: ['Villa Cleaning', 'Apartment Cleaning', 'Holiday Home Turnover', 'Carpet & Upholstery', 'Window Cleaning'],
        phone: '+254 701 111 222',
        email: 'hello@purespace.co.ke',
        whatsapp: '+254 701 111 222',
        website: 'www.purespace.co.ke'
    },
    {
        id: 'cleaning-3',
        name: 'FreshNest Services',
        category: 'cleaning',
        categoryLabel: 'Cleaning Company',
        location: 'Kilimani, Nairobi',
        city: 'Nairobi',
        rating: 4,
        reviews: 168,
        verified: true,
        years: 8,
        yearsLabel: '8+',
        clients: '250+',
        satisfaction: '94%',
        icon: 'fa-leaf',
        logo: 'https://images.unsplash.com/photo-1503387762-592deb59ef4e?auto=format&fit=crop&w=200&q=80',
        color: 'teal',
        shortDesc: 'Eco-friendly home & office cleaning.',
        tags: ['cleaning', 'eco', 'residential'],
        about: 'FreshNest Services is committed to providing environmentally responsible cleaning solutions. We use only non-toxic, biodegradable products that are safe for your family, pets, and the planet. Our green cleaning approach doesn\'t compromise on quality — we deliver a deep clean every time.',
        achievements: [
            'Green Cleaning Award 2022 — Kenya Eco Forum',
            '250+ eco-conscious clients',
            'Reduced water usage by 30% through smart cleaning methods',
            'Partnered with 10 green building complexes'
        ],
        services: ['Eco Home Cleaning', 'Green Office Cleaning', 'Sanitization', 'Window Cleaning', 'Floor Care'],
        phone: '+254 701 222 333',
        email: 'eco@freshnest.co.ke',
        whatsapp: '+254 701 222 333',
        website: 'www.freshnest.co.ke'
    },
    {
        id: 'cleaning-4',
        name: 'Crystal Clear Facilities',
        category: 'cleaning',
        categoryLabel: 'Cleaning Company',
        location: 'Mombasa Road, Nairobi',
        city: 'Nairobi',
        rating: 5,
        reviews: 234,
        verified: true,
        years: 12,
        yearsLabel: '12+',
        clients: '400+',
        satisfaction: '97%',
        icon: 'fa-industry',
        logo: 'https://images.unsplash.com/photo-1493937672613-523e9f474c7a?auto=format&fit=crop&w=200&q=80',
        color: 'teal',
        shortDesc: 'Large-scale facility & industrial cleaning.',
        tags: ['cleaning', 'industrial', 'commercial'],
        about: 'Crystal Clear Facilities handles large-scale cleaning contracts for factories, warehouses, hospitals, and shopping malls. Our team is equipped to manage the most demanding cleaning challenges with precision and safety. We use industrial-grade equipment and follow strict safety protocols.',
        achievements: [
            'Excellence in Facility Cleaning 2023 — Industrial Awards',
            '400+ facilities serviced across Kenya',
            'ISO 9001:2015 certified cleaning processes',
            'Serving 8 hospitals and 12 shopping malls'
        ],
        services: ['Industrial Cleaning', 'Warehouse Cleaning', 'Hospital Sanitization', 'Mall Cleaning', 'Fleet Cleaning'],
        phone: '+254 701 333 444',
        email: 'info@crystalclear.co.ke',
        whatsapp: '+254 701 333 444',
        website: 'www.crystalclear.co.ke'
    }
];

/* ============================================================
   STATE
   ============================================================ */
let currentFilters = {
    category: 'all',
    locations: [],
    minRating: null,
    verified: false,
    quickTag: null
};
let currentSort = 'recommended';
let currentPage = 1;
const PER_PAGE = 6;

/* ============================================================
   FILTERING
   ============================================================ */
function getFilteredAgencies() {
    let results = [...agenciesData];

    if (currentFilters.category !== 'all')
        results = results.filter(a => a.category === currentFilters.category);

    if (currentFilters.locations.length > 0)
        results = results.filter(a => currentFilters.locations.includes(a.city));

    if (currentFilters.minRating !== null)
        results = results.filter(a => a.rating >= currentFilters.minRating);

    if (currentFilters.verified)
        results = results.filter(a => a.verified);

    if (currentFilters.quickTag) {
        results = results.filter(a => a.category === currentFilters.quickTag);
    }

    if (currentSort === 'rating') results.sort((a, b) => b.rating - a.rating);
    else if (currentSort === 'name') results.sort((a, b) => a.name.localeCompare(b.name));
    else if (currentSort === 'experience') results.sort((a, b) => b.years - a.years);

    return results;
}

/* ============================================================
   RENDER
   ============================================================ */
function renderAgencies() {
    const grid = document.getElementById('agenciesGrid');
    const empty = document.getElementById('emptyState');
    const countEl = document.getElementById('resultsCount');
    const summaryEl = document.getElementById('filterSummary');
    const pagination = document.getElementById('pagination');
    const activeFiltersEl = document.getElementById('activeFilters');

    const filtered = getFilteredAgencies();
    countEl.textContent = filtered.length;

    let summaryParts = [];
    if (currentFilters.quickTag) {
        const map = {agency: 'Real Estate Agencies', management: 'Property Management', cleaning: 'Cleaning Companies'};
        summaryParts.push(map[currentFilters.quickTag]);
    }
    if (currentFilters.category !== 'all') {
        const map = {agency: 'Real Estate Agencies', management: 'Property Management', cleaning: 'Cleaning Companies'};
        summaryParts.push(map[currentFilters.category]);
    }
    if (currentFilters.locations.length) summaryParts.push(currentFilters.locations.join(', '));
    if (currentFilters.minRating) summaryParts.push(currentFilters.minRating + '★+');
    if (currentFilters.verified) summaryParts.push('verified only');
    summaryEl.textContent = summaryParts.length > 0
        ? 'Filtered by: ' + summaryParts.join(' · ')
        : 'Showing all agencies and companies';

    // Active chips
    let chips = [];
    if (currentFilters.quickTag) {
        const map = {agency: '🏢 Real Estate', management: '🏘️ Property Mgmt', cleaning: '🧹 Cleaning'};
        chips.push({label: map[currentFilters.quickTag], fn: 'removeQuickTag()'});
    }
    if (currentFilters.category !== 'all') {
        const map = {agency: 'Real Estate', management: 'Property Mgmt', cleaning: 'Cleaning'};
        chips.push({label: map[currentFilters.category], fn: 'removeCategory()'});
    }
    currentFilters.locations.forEach(l => chips.push({label: l, fn: `removeLocation('${l}')`}));
    if (currentFilters.minRating) chips.push({label: currentFilters.minRating + '★+', fn: 'removeRating()'});
    if (currentFilters.verified) chips.push({label: 'Verified', fn: 'removeVerified()'});

    if (chips.length > 0) {
        activeFiltersEl.classList.remove('hidden');
        activeFiltersEl.innerHTML = chips.map(c =>
            `<button onclick="${c.fn}" class="inline-flex items-center gap-1 bg-agencyPurple/10 text-agencyPurple text-[10px] font-semibold px-2.5 py-1 rounded-full border border-agencyPurple/20 hover:bg-agencyPurple hover:text-white transition-colors">
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

    const catColors = {
        agency: {bg: 'bg-agencyPurple/10', text: 'text-agencyPurple', border: 'border-agencyPurple/20', iconBg: 'bg-agencyPurple'},
        management: {bg: 'bg-accentBlue/10', text: 'text-accentBlue', border: 'border-accentBlue/20', iconBg: 'bg-accentBlue'},
        cleaning: {bg: 'bg-cleaningTeal/10', text: 'text-cleaningTeal', border: 'border-cleaningTeal/20', iconBg: 'bg-cleaningTeal'}
    };

    grid.innerHTML = paged.map(a => {
        const colors = catColors[a.category] || catColors.agency;
        const stars = '<i class="fas fa-star star-filled text-[9px]"></i>'.repeat(Math.floor(a.rating)) +
                      (a.rating % 1 >= 0.5 ? '<i class="fas fa-star-half-alt star-filled text-[9px]"></i>' : '') +
                      '<i class="far fa-star star-empty text-[9px]"></i>'.repeat(5 - Math.ceil(a.rating));

        return `
            <article class="agency-card anim-fade-up relative" onclick="openAgencyModal('${a.id}')">
                <button onclick="toggleFavorite(event, this)" class="favorite-btn" aria-label="Save">
                    <i class="far fa-heart"></i>
                </button>
                <div class="p-4 md:p-5">
                    <!-- Top row: logo + name -->
                    <div class="flex items-start gap-3 mb-3">
                        <div class="agency-logo ${colors.iconBg} text-white overflow-hidden">
                            ${a.logo ? `<img src="${a.logo}" alt="${a.name} logo" class="w-full h-full object-cover">` : `<i class="fas ${a.icon}"></i>`}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-serif text-sm md:text-base text-navy font-semibold leading-tight line-clamp-1">${a.name}</h3>
                            <p class="text-[10px] text-gray-500 mt-0.5 line-clamp-1">
                                <i class="fas fa-map-marker-alt ${colors.text} mr-1 text-[8px]"></i>${a.location}
                            </p>
                        </div>
                    </div>

                    <!-- Rating -->
                    <div class="flex items-center gap-1.5 mb-2.5">
                        <div class="flex gap-0.5">${stars}</div>
                        <span class="text-[10px] font-semibold text-navy">${a.rating}</span>
                        <span class="text-[9px] text-gray-400">(${a.reviews})</span>
                    </div>

                    <!-- Description -->
                    <p class="text-[11px] text-gray-500 leading-relaxed mb-3 line-clamp-2">${a.shortDesc}</p>

                    <!-- Tags -->
                    <div class="flex flex-wrap gap-1 mb-3">
                        <span class="agency-tag ${colors.bg} ${colors.text} border ${colors.border}">${a.categoryLabel}</span>
                        ${a.verified ? '<span class="agency-tag bg-emerald-50 text-emerald-700 border border-emerald-200"><i class="fas fa-certificate text-[7px] mr-0.5"></i>Verified</span>' : ''}
                    </div>

                    <!-- Bottom stats -->
                    <div class="flex items-center justify-between pt-3 border-t border-gray-100 text-[10px]">
                        <div class="flex items-center gap-3 text-gray-500">
                            <span><i class="fas fa-briefcase ${colors.text} mr-0.5"></i>${a.yearsLabel} yrs</span>
                            <span><i class="fas fa-users ${colors.text} mr-0.5"></i>${a.clients}</span>
                        </div>
                        <span class="text-emerald-600 font-semibold">${a.satisfaction} <i class="fas fa-thumbs-up text-[8px]"></i></span>
                    </div>
                </div>
            </article>
        `;
    }).join('');

    if (totalPages > 1) {
        let pag = `<button onclick="goToPage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''} class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:border-agencyPurple hover:text-agencyPurple transition-colors disabled:opacity-40 disabled:cursor-not-allowed"><i class="fas fa-chevron-left text-[10px]"></i></button>`;
        for (let i = 1; i <= totalPages; i++) {
            pag += `<button onclick="goToPage(${i})" class="w-9 h-9 flex items-center justify-center rounded-lg text-xs font-semibold transition-colors ${i === currentPage ? 'bg-agencyPurple text-white shadow-md shadow-agencyPurple/30' : 'border border-gray-200 text-gray-600 hover:border-agencyPurple hover:text-agencyPurple'}">${i}</button>`;
        }
        pag += `<button onclick="goToPage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''} class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:border-agencyPurple hover:text-agencyPurple transition-colors disabled:opacity-40 disabled:cursor-not-allowed"><i class="fas fa-chevron-right text-[10px]"></i></button>`;
        pagination.innerHTML = pag;
    } else {
        pagination.innerHTML = '';
    }
}

function goToPage(page) {
    const filtered = getFilteredAgencies();
    const totalPages = Math.ceil(filtered.length / PER_PAGE) || 1;
    if (page < 1 || page > totalPages) return;
    currentPage = page;
    renderAgencies();
    document.getElementById('agenciesGrid').scrollIntoView({behavior: 'smooth', block: 'start'});
}

/* ============================================================
   FILTER HANDLERS
   ============================================================ */
document.querySelectorAll('input[name="category"]').forEach(radio => {
    radio.addEventListener('change', function() {
        currentFilters.category = this.value;
        currentFilters.quickTag = null;
        currentPage = 1;
        renderAgencies();
    });
});

document.querySelectorAll('#locationFilters input[type="checkbox"]').forEach(cb => {
    cb.addEventListener('change', function() {
        const val = this.value;
        if (this.checked) currentFilters.locations.push(val);
        else currentFilters.locations = currentFilters.locations.filter(l => l !== val);
        currentPage = 1;
        renderAgencies();
    });
});

document.getElementById('verifiedToggle').addEventListener('change', function() {
    currentFilters.verified = this.checked;
    currentPage = 1;
    renderAgencies();
});

function toggleRatingFilter(btn, val) {
    if (btn.classList.contains('active')) {
        btn.classList.remove('active');
        currentFilters.minRating = null;
    } else {
        document.querySelectorAll('#ratingFilters .chip').forEach(c => c.classList.remove('active'));
        btn.classList.add('active');
        currentFilters.minRating = val;
    }
    currentPage = 1;
    renderAgencies();
}

function quickFilter(tag) {
    currentFilters.quickTag = tag;
    currentFilters.category = 'all';
    // Reset radio
    document.querySelectorAll('input[name="category"]').forEach(r => r.checked = false);
    document.querySelector('input[name="category"][value="all"]').checked = true;
    currentPage = 1;
    renderAgencies();
}

function resetAllFilters() {
    currentFilters = {category: 'all', locations: [], minRating: null, verified: false, quickTag: null};
    document.querySelectorAll('input[name="category"]').forEach(r => r.checked = false);
    document.querySelector('input[name="category"][value="all"]').checked = true;
    document.querySelectorAll('#locationFilters input[type="checkbox"]').forEach(cb => cb.checked = false);
    document.querySelectorAll('#ratingFilters .chip').forEach(c => c.classList.remove('active'));
    document.getElementById('verifiedToggle').checked = false;
    currentPage = 1;
    renderAgencies();
}

function removeQuickTag() {
    currentFilters.quickTag = null;
    currentPage = 1;
    renderAgencies();
}
function removeCategory() {
    currentFilters.category = 'all';
    document.querySelectorAll('input[name="category"]').forEach(r => r.checked = false);
    document.querySelector('input[name="category"][value="all"]').checked = true;
    currentPage = 1;
    renderAgencies();
}
function removeLocation(l) {
    currentFilters.locations = currentFilters.locations.filter(x => x !== l);
    document.querySelectorAll('#locationFilters input').forEach(cb => { if (cb.value === l) cb.checked = false; });
    currentPage = 1;
    renderAgencies();
}
function removeRating() {
    currentFilters.minRating = null;
    document.querySelectorAll('#ratingFilters .chip').forEach(c => c.classList.remove('active'));
    currentPage = 1;
    renderAgencies();
}
function removeVerified() {
    currentFilters.verified = false;
    document.getElementById('verifiedToggle').checked = false;
    currentPage = 1;
    renderAgencies();
}

/* ============================================================
    SORT
    ============================================================ */
    function applySort() {
        currentSort = document.getElementById('sortSelect').value;
        currentPage = 1;
        renderAgencies();
    }

    /* ============================================================
    FILTER TOGGLE (mobile collapsible)
    ============================================================ */
    function toggleFilters() {
        const content = document.getElementById('filterContent');
        const chevron = document.getElementById('filterChevron');
        if (!content) return;
        content.classList.toggle('hidden');
        if (chevron) {
            chevron.style.transform = content.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
        }
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
   MODAL
   ============================================================ */
function openAgencyModal(id) {
    const a = agenciesData.find(x => x.id === id);
    if (!a) return;

    const catColors = {
        agency: {bg: 'bg-agencyPurple/10', text: 'text-agencyPurple', border: 'border-agencyPurple/20', iconBg: 'bg-agencyPurple'},
        management: {bg: 'bg-accentBlue/10', text: 'text-accentBlue', border: 'border-accentBlue/20', iconBg: 'bg-accentBlue'},
        cleaning: {bg: 'bg-cleaningTeal/10', text: 'text-cleaningTeal', border: 'border-cleaningTeal/20', iconBg: 'bg-cleaningTeal'}
    };
    const colors = catColors[a.category] || catColors.agency;

    // Header
    const logoEl = document.getElementById('modalLogo');
    logoEl.className = `w-20 h-20 md:w-24 md:h-24 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-inner overflow-hidden ${colors.iconBg} text-white`;
    logoEl.innerHTML = a.logo
        ? `<img src="${a.logo}" alt="${a.name} logo" class="w-full h-full object-cover">`
        : `<i class="fas ${a.icon} text-3xl md:text-4xl"></i>`;

    document.getElementById('modalName').textContent = a.name;
    document.getElementById('modalLocation').innerHTML = `<i class="fas fa-map-marker-alt text-agencyPurple mr-1"></i> ${a.location}`;
    document.getElementById('modalCategory').textContent = a.categoryLabel;
    document.getElementById('modalCategory').className = `agency-tag ${colors.bg} ${colors.text} border ${colors.border}`;
    document.getElementById('modalVerified').style.display = a.verified ? 'inline-flex' : 'none';

    // Rating
    const stars = '<i class="fas fa-star star-filled text-xs"></i>'.repeat(Math.floor(a.rating)) +
                  (a.rating % 1 >= 0.5 ? '<i class="fas fa-star-half-alt star-filled text-xs"></i>' : '') +
                  '<i class="far fa-star star-empty text-xs"></i>'.repeat(5 - Math.ceil(a.rating));
    document.getElementById('modalRating').innerHTML = `${stars}<span class="text-xs font-semibold text-gray-700 ml-1">${a.rating}.0</span><span class="text-[10px] text-gray-400 ml-1">(${a.reviews} reviews)</span>`;

    // Stats
    document.getElementById('modalYears').textContent = a.yearsLabel;
    document.getElementById('modalClients').textContent = a.clients;
    document.getElementById('modalSatisfaction').textContent = a.satisfaction;
    document.getElementById('modalAbout').textContent = a.about;

    // Achievements
    document.getElementById('modalAchievements').innerHTML = a.achievements.map(ach => `
        <li class="flex items-start gap-2 text-gray-600 text-xs md:text-sm">
            <i class="fas fa-check-circle text-emerald-500 mt-0.5 text-[10px]"></i>
            <span>${ach}</span>
        </li>
    `).join('');

    // Services
    document.getElementById('modalServices').innerHTML = a.services.map(s => `
        <span class="px-2.5 py-1 bg-gray-100 text-navy text-[10px] md:text-xs rounded-full border border-gray-200 font-medium">
            ${s}
        </span>
    `).join('');

    // Contact
    document.getElementById('modalPhone').textContent = a.phone;
    document.getElementById('modalEmail').textContent = a.email;
    document.getElementById('modalWhatsapp').textContent = a.whatsapp;
    document.getElementById('modalWebsite').textContent = a.website;

    // Show
    const modal = document.getElementById('agencyModal');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    requestAnimationFrame(() => modal.classList.add('show'));
}

function closeAgencyModal() {
    const modal = document.getElementById('agencyModal');
    modal.classList.remove('show');
    setTimeout(() => modal.classList.add('hidden'), 300);
    document.body.style.overflow = '';
}

document.getElementById('agencyModal').addEventListener('click', function(e) {
    if (e.target === this) closeAgencyModal();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeAgencyModal();
});

/* ============================================================
   INIT
   ============================================================ */
document.addEventListener('DOMContentLoaded', function() {
    renderAgencies();
});
</script>

</body>
</html>