<?php
// agencies.php - Agencies directory (DB-backed, fast/searchable)
// $pdo is already available from index.php
$page_title = 'Real Estate Agencies & Cleaning Companies in Kenya | HAVENTRA Agencies';
$page_description = 'Discover trusted real estate agencies, property managers, and professional cleaning companies across Kenya. Browse verified companies, compare services, and connect with the right partner on HAVENTRA.';
$page_canonical = 'https://haventra.com/agencies/';

$q = trim((string)($_GET['q'] ?? $_GET['search'] ?? ''));
$catParam = strtolower(trim((string)($_GET['category'] ?? $_GET['cat'] ?? '')));
$catMap = ['agency'=>'agency','real-estate'=>'agency','real-estate-agencies'=>'agency','management'=>'management','property-management'=>'management','property-management-companies'=>'management','cleaning'=>'cleaning','cleaning-agencies'=>'cleaning','cleaning-companies'=>'cleaning'];
$activeCat = $catMap[$catParam] ?? 'all';

$agencies = [];
$dbCities = [];
try {
    $rows = $pdo->query("SELECT id,name,category,category_label,location,city,rating,reviews,verified,years,years_label,clients,satisfaction,icon,logo,color,short_desc,tags,about,achievements,services,phone,email,whatsapp,website,lat,lng FROM agencies WHERE is_active=1 ORDER BY rating DESC, years DESC, name ASC")->fetchAll();
    foreach ($rows as $r) {
        $tags = json_decode($r['tags'] ?? '[]', true); if (!is_array($tags)) $tags = [];
        $ach = json_decode($r['achievements'] ?? '[]', true); if (!is_array($ach)) $ach = [];
        $svc = json_decode($r['services'] ?? '[]', true); if (!is_array($svc)) $svc = [];
        $city = trim((string)$r['city']); if ($city !== '') $dbCities[$city] = ($dbCities[$city] ?? 0) + 1;
        $agencies[] = [
            'id' => 'agency-' . $r['id'],
            'dbId' => (int)$r['id'],
            'name' => (string)$r['name'],
            'category' => (string)$r['category'],
            'categoryLabel' => (string)$r['category_label'],
            'location' => (string)$r['location'],
            'city' => $city,
            'rating' => (float)$r['rating'],
            'reviews' => (int)$r['reviews'],
            'verified' => (bool)$r['verified'],
            'years' => (int)$r['years'],
            'yearsLabel' => (string)$r['years_label'],
            'clients' => (string)$r['clients'],
            'satisfaction' => (string)$r['satisfaction'],
            'icon' => (string)($r['icon'] ?: 'fa-building'),
            'logo' => (string)($r['logo'] ?? ''),
            'color' => (string)($r['color'] ?: 'purple'),
            'shortDesc' => (string)$r['short_desc'],
            'tags' => $tags,
            'about' => (string)$r['about'],
            'achievements' => $ach,
            'services' => $svc,
            'phone' => (string)$r['phone'],
            'email' => (string)$r['email'],
            'whatsapp' => (string)$r['whatsapp'],
            'website' => (string)$r['website'],
            'lat' => $r['lat'] !== null ? (float)$r['lat'] : null,
            'lng' => $r['lng'] !== null ? (float)$r['lng'] : null,
        ];
    }
} catch (Throwable $e) { $agencies = []; }
ksort($dbCities);
?>
<!-- SEO -->
<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large">
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

    <!-- Search bar -->
    <div class="max-w-2xl mx-auto relative z-20 px-4">
        <form id="agencySearchForm" onsubmit="handleAgencySearch(event)" class="search-shell p-1.5 md:p-2 flex items-center gap-1">
            <div class="search-field flex-1 px-3 py-2 md:py-2.5 cursor-pointer" onclick="activateField(this)">
                <div class="flex items-center gap-2">
                    <i class="fas fa-search text-agencyPurple text-xs"></i>
                    <input type="text" id="searchAgency" value="<?php echo htmlspecialchars($q, ENT_QUOTES); ?>" placeholder="Search agencies, services, or locations" class="search-input font-medium" autocomplete="off">
                </div>
            </div>
            <button type="submit" class="bg-gradient-to-r from-agencyPurple to-violet-700 text-white rounded-full px-5 py-2.5 md:px-6 md:py-3 flex items-center justify-center gap-2 font-semibold text-sm shadow-lg hover:shadow-xl transition-all duration-300">
                <i class="fas fa-search text-xs"></i><span class="hidden sm:inline">Search</span>
            </button>
        </form>
        <div class="flex flex-wrap items-center justify-center gap-2 mt-4 md:mt-5">
            <span class="text-white/60 text-[10px] md:text-xs font-semibold uppercase tracking-wide mr-1">Quick:</span>
            <button onclick="quickFilter('agency')" class="chip !bg-white/15 !border-white/20 !text-white hover:!bg-white/25">🏢 Real Estate</button>
            <button onclick="quickFilter('management')" class="chip !bg-white/15 !border-white/20 !text-white hover:!bg-white/25">🏘️ Property Mgmt</button>
            <button onclick="quickFilter('cleaning')" class="chip !bg-white/15 !border-white/20 !text-white hover:!bg-white/25">🧹 Cleaning</button>
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
                                                                <?php if (!empty($dbCities)): foreach (array_slice(array_keys($dbCities),0,8) as $city): ?>
                                <label class="flex items-center gap-2.5 cursor-pointer group">
                                    <input type="checkbox" value="<?php echo htmlspecialchars($city,ENT_QUOTES); ?>" class="agency-filter-checkbox w-4 h-4 rounded border-gray-300 text-agencyPurple focus:ring-agencyPurple">
                                    <span class="text-xs text-gray-600 group-hover:text-agencyPurple transition-colors"><?php echo htmlspecialchars($city); ?></span>
                                </label>
                                <?php endforeach; else: ?>
                                <label class="flex items-center gap-2.5 cursor-pointer group"><input type="checkbox" value="Nairobi" class="agency-filter-checkbox w-4 h-4 rounded border-gray-300 text-agencyPurple focus:ring-agencyPurple"><span class="text-xs text-gray-600 group-hover:text-agencyPurple transition-colors">Nairobi</span></label>
                                <label class="flex items-center gap-2.5 cursor-pointer group"><input type="checkbox" value="Mombasa" class="agency-filter-checkbox w-4 h-4 rounded border-gray-300 text-agencyPurple focus:ring-agencyPurple"><span class="text-xs text-gray-600 group-hover:text-agencyPurple transition-colors">Mombasa</span></label>
                                <?php endif; ?>
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
                    <!-- <div class="bg-gradient-to-br from-agencyPurple to-violet-700 rounded-2xl p-5 text-white shadow-xl shadow-agencyPurple/30">
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
                    </div> -->

                    <!-- Join card -->
                    <!-- <div class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 p-5">
                        <h4 class="font-serif text-sm text-navy font-semibold mb-1.5">Are you a company?</h4>
                        <p class="text-[11px] text-gray-500 leading-relaxed mb-3">List your agency on HAVENTRA and reach thousands of clients looking for your services.</p>
                        <a href="/contact.html" class="inline-flex items-center gap-1.5 bg-agencyPurple text-white text-[10px] font-bold px-3 py-1.5 rounded-lg hover:bg-violet-800 transition-colors">
                            <i class="fas fa-plus-circle"></i> List Your Agency
                        </a>
                    </div> -->

                </div>
            </aside>

            <!-- ===== AGENCY LISTINGS ===== -->
            <div class="lg:w-3/4 w-full">

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
                    <div>
                        <h2 class="font-serif text-2xl md:text-3xl text-navy font-semibold">
                            <span id="resultsCount">12</span> companies found
                        </h2>
                        <p class="text-xs text-gray-500 mt-0.5" id="filterSummary"></p>
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
const agenciesData = <?php echo json_encode($agencies, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT); ?>;
const INITIAL_Q = <?php echo json_encode($q, JSON_UNESCAPED_UNICODE|JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT); ?>;
const INITIAL_CAT = <?php echo json_encode($activeCat, JSON_UNESCAPED_UNICODE|JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT); ?>;

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
    if (currentFilters.search && currentFilters.search.trim()) {
        const q = currentFilters.search.toLowerCase();
        results = results.filter(a => ((a.name||'')+' '+(a.location||'')+' '+(a.city||'')+' '+(a.categoryLabel||'')+' '+(a.shortDesc||'')+' '+(a.about||'')+' '+(a.services||[]).join(' ')+' '+(a.tags||[]).join(' ')).toLowerCase().includes(q));
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
        : '';

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
                            ${a.logo ? `<img src="${a.logo}" alt="${a.name} logo" loading="lazy" decoding="async" class="w-full h-full object-cover">` : `<i class="fas ${a.icon}"></i>`}
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
    currentFilters = {category: 'all', locations: [], minRating: null, verified: false, quickTag: null, search: ''};
    document.querySelectorAll('input[name="category"]').forEach(r => r.checked = false);
    document.querySelector('input[name="category"][value="all"]').checked = true;
    document.querySelectorAll('#locationFilters input[type="checkbox"]').forEach(cb => cb.checked = false);
    document.querySelectorAll('#ratingFilters .chip').forEach(c => c.classList.remove('active'));
    document.getElementById('verifiedToggle').checked = false;
    const si = document.getElementById('searchAgency'); if (si) si.value = '';
    currentPage = 1;
    renderAgencies();
    updateAgencyUrl();
}

function removeQuickTag() {
    currentFilters.quickTag = null;
    currentPage = 1;
    renderAgencies();
    updateAgencyUrl();
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
        ? `<img src="${a.logo}" alt="${a.name} logo" loading="lazy" decoding="async" class="w-full h-full object-cover">`
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
function handleAgencySearch(e){ e.preventDefault(); currentFilters.search = (document.getElementById('searchAgency').value||'').toLowerCase(); currentPage=1; renderAgencies(); const g=document.getElementById('agenciesGrid'); if(g) g.scrollIntoView({behavior:'smooth',block:'start'}); updateAgencyUrl(); }
function activateField(el){ el.classList.add('active'); setTimeout(()=>{const i=el.querySelector('input'); if(i) i.focus();},50); setTimeout(()=>el.classList.remove('active'),2000); }
function updateAgencyUrl(){ try{ const p=new URLSearchParams(window.location.search); if(currentFilters.search) p.set('q', currentFilters.search); else p.delete('q'); const cat = currentFilters.quickTag || (currentFilters.category!=='all'?currentFilters.category:''); if(cat) p.set('category',cat); else p.delete('category'); const s=p.toString(); history.replaceState(null,'', window.location.pathname + (s?('?'+s):'')); }catch(e){} }
document.addEventListener('DOMContentLoaded', function() {
    const params = new URLSearchParams(window.location.search);
    const q = (typeof INITIAL_Q!=='undefined' && INITIAL_Q) ? INITIAL_Q : (params.get('q')||params.get('search')||'');
    const cat = (typeof INITIAL_CAT!=='undefined' && INITIAL_CAT && INITIAL_CAT!=='all') ? INITIAL_CAT : (params.get('category')||params.get('cat')||'all');
    if(q){ currentFilters.search=String(q).toLowerCase(); const si=document.getElementById('searchAgency'); if(si) si.value=q; }
    if(cat && cat!=='all' && ['agency','management','cleaning'].includes(cat)){ currentFilters.quickTag=cat; const rad=document.querySelector('input[name="category"][value="'+cat+'"]'); if(rad) rad.checked=true; }
    renderAgencies();
});
</script>

