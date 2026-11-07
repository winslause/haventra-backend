<?php
// rentals.php - Houses & Apartments for Rent page
// $pdo is already available from index.php
// $page_title, $page_description, $page_canonical set below for SEO (used by any code after nav.php)

$page_title = 'Houses & Apartments for Rent in Kenya | HAVENTRA Rentals';
$page_description = 'Find houses, apartments, bedsitters, and family homes for rent in Kenya. Browse verified long-term rental listings in Nairobi, Mombasa, Kisumu, and beyond on HAVENTRA.';
$page_canonical = 'https://haventra.com/rentals/';

// Fetch rental properties from database
$rentals = [];
try {
    $stmt = $pdo->query("SELECT * FROM properties WHERE is_active = 1 AND type = 'rent' ORDER BY created_at DESC");
    $dbRows = $stmt->fetchAll();
    foreach ($dbRows as $row) {
        $priceNum = (int) str_replace(',', '', $row['price']);
        $images = json_decode($row['image_urls'] ?? '[]', true);
        if (!is_array($images) || empty($images)) {
            $images = [$row['image_url']];
        }
        $amenities = json_decode($row['amenities'] ?? '[]', true);
        if (!is_array($amenities)) {
            $amenities = [];
        }
        $titleLower = strtolower($row['title']);
        if (strpos($titleLower, 'villa') !== false) $propType = 'villa';
        elseif (strpos($titleLower, 'townhouse') !== false) $propType = 'townhouse';
        elseif (strpos($titleLower, 'bedsitter') !== false) $propType = 'bedsitter';
        elseif (strpos($titleLower, 'apartment') !== false || strpos($titleLower, 'studio') !== false || strpos($titleLower, 'loft') !== false) $propType = 'apartment';
        else $propType = 'house';

        $rentals[] = [
            'id' => 'rent-' . $row['id'],
            'title' => htmlspecialchars($row['title']),
            'location' => htmlspecialchars($row['location']),
            'price' => $priceNum,
            'rating' => 4.5,
            'reviews' => 0,
            'beds' => (int) $row['beds'],
            'baths' => (int) $row['baths'],
            'parking' => 1,
            'size' => htmlspecialchars($row['size'] ?? ''),
            'type' => $propType,
            'badge' => htmlspecialchars($row['badge'] ?? 'For Rent'),
            'furnished' => false,
            'tags' => [],
            'agentName' => 'HAVENTRA Agent',
            'agentInitial' => 'H',
            'desc' => htmlspecialchars($row['description'] ?? ''),
            'amenities' => array_map(function($a) {
                $iconMap = [
                    'wifi' => 'fa-wifi', 'wi-fi' => 'fa-wifi', 'free wifi' => 'fa-wifi',
                    'pool' => 'fa-swimming-pool', 'swimming pool' => 'fa-swimming-pool',
                    'parking' => 'fa-parking', 'security' => 'fa-shield-alt', '24/7 security' => 'fa-shield-alt',
                    'garden' => 'fa-tree', 'private garden' => 'fa-tree',
                    'borehole' => 'fa-water', 'borehole water' => 'fa-water',
                    'air conditioning' => 'fa-snowflake', 'ac' => 'fa-snowflake',
                    'gym' => 'fa-dumbbell', 'fitness' => 'fa-dumbbell',
                    'furnished' => 'fa-couch', 'fully furnished' => 'fa-couch',
                    'balcony' => 'fa-balcony', 'playground' => 'fa-child',
                    'water' => 'fa-tint', '24/7 water' => 'fa-tint',
                    'caretaker' => 'fa-user-shield', 'electricity' => 'fa-bolt',
                    'fireplace' => 'fa-fire', 'study' => 'fa-book',
                    'beach' => 'fa-umbrella-beach', 'beach access' => 'fa-umbrella-beach',
                    'terrace' => 'fa-building', 'rooftop' => 'fa-building',
                    'study room' => 'fa-book'
                ];
                $key = strtolower(trim($a));
                $icon = $iconMap[$key] ?? 'fa-check';
                return ['icon' => $icon, 'label' => htmlspecialchars($a)];
            }, $amenities),
            'images' => array_map(function($img) { return htmlspecialchars($img); }, $images),
            'lat' => $row['lat'] ? (float) $row['lat'] : null,
            'lng' => $row['lng'] ? (float) $row['lng'] : null,
        ];
    }
} catch (PDOException $e) {
    $rentals = [];
}
?>

<!-- ================= HERO WITH SEARCH ================= -->
<section class="rent-hero relative pt-10 pb-16 md:pt-12 md:pb-24 px-4">
    
    <!-- Hero heading -->
    <div class="max-w-4xl mx-auto text-center relative z-10 mb-6 md:mb-10">
        <span class="inline-block bg-white/15 backdrop-blur text-white text-[10px] md:text-xs font-bold uppercase tracking-[0.2em] px-4 py-1.5 rounded-full mb-4 border border-white/20">
            <i class="fas fa-key mr-1.5 text-accentOrange"></i> Long-Term Rentals
        </span>
        <h1 class="font-serif text-3xl md:text-6xl text-white font-semibold leading-tight mb-3 md:mb-4 drop-shadow-lg">
            Find your next <span class="italic text-accentOrange">home</span>
        </h1>
        <p class="text-white/80 text-sm md:text-base max-w-2xl mx-auto">
            Verified houses, apartments, bedsitters, and family homes for rent across Kenya — monthly leases, no hidden fees.
        </p>
    </div>

    <!-- ===== SEARCH BAR ===== -->
    <div class="max-w-2xl mx-auto relative z-20">
        <form onsubmit="handleSearch(event)" class="search-shell p-1.5 md:p-2 flex items-center gap-1">
            
            <!-- Location -->
            <div class="search-field flex-1 px-3 py-2 md:py-2.5 cursor-pointer" onclick="activateField(this)">
                <div class="flex items-center gap-2">
                    <i class="fas fa-map-marker-alt text-rentGreen text-xs"></i>
                    <input type="text" id="searchLocation" placeholder="Search by location" class="search-input font-medium">
                </div>
            </div>

            <!-- Search button -->
            <button type="submit" class="bg-gradient-to-r from-rentGreen to-emerald-600 text-white rounded-full px-5 py-2.5 md:px-6 md:py-3 flex items-center justify-center gap-2 font-semibold text-sm shadow-lg shadow-rentGreen/30 hover:shadow-xl hover:shadow-rentGreen/40 transition-all duration-300">
                <i class="fas fa-search text-xs"></i>
                <span class="hidden sm:inline">Search</span>
            </button>
        </form>

        <!-- Quick chips -->
        <div class="flex flex-wrap items-center justify-center gap-2 mt-4 md:mt-5">
            <span class="text-white/60 text-[10px] md:text-xs font-semibold uppercase tracking-wide mr-1">Popular:</span>
            <button onclick="quickFilter('Family Homes')" class="chip !bg-white/15 !border-white/20 !text-white hover:!bg-white/25">👨‍👩‍👧 Family Homes</button>
            <button onclick="quickFilter('Bedsitters')" class="chip !bg-white/15 !border-white/20 !text-white hover:!bg-white/25">🛏️ Bedsitters</button>
            <button onclick="quickFilter('Luxury')" class="chip !bg-white/15 !border-white/20 !text-white hover:!bg-white/25">✨ Luxury</button>
            <button onclick="quickFilter('Townhouses')" class="chip !bg-white/15 !border-white/20 !text-white hover:!bg-white/25">🏘️ Townhouses</button>
            <button onclick="quickFilter('Near Schools')" class="chip !bg-white/15 !border-white/20 !text-white hover:!bg-white/25">🎓 Near Schools</button>
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
                                <i class="fas fa-filter text-rentGreen"></i>
                                <h3 class="font-serif text-base text-navy font-semibold">Filters</h3>
                            </div>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform" id="mobileFilterChevron"></i>
                        </button>
                <div id="filterContent" class="hidden lg:block px-5 pb-5 pt-0 lg:pt-5">
                    <div class="hidden lg:flex items-center justify-between mb-4">
                        <h3 class="font-serif text-lg text-navy font-semibold">Filters</h3>
                        <button onclick="resetAllFilters()" class="text-[10px] text-rentGreen hover:text-emerald-700 font-semibold underline transition-colors">
                            Reset all
                        </button>
                    </div>

                    <!-- Property Type -->
                    <div class="mb-5">
                        <h4 class="text-[11px] uppercase tracking-wider font-bold text-gray-500 mb-2.5">Property Type</h4>
                        <div class="space-y-2" id="typeFilters">
                            <label class="flex items-center gap-2.5 cursor-pointer group">
                                <input type="checkbox" value="house" class="rent-filter-checkbox w-4 h-4 rounded border-gray-300 text-rentGreen focus:ring-rentGreen">
                                <span class="text-xs text-gray-600 group-hover:text-rentGreen transition-colors">House</span>
                            </label>
                            <label class="flex items-center gap-2.5 cursor-pointer group">
                                <input type="checkbox" value="apartment" class="rent-filter-checkbox w-4 h-4 rounded border-gray-300 text-rentGreen focus:ring-rentGreen">
                                <span class="text-xs text-gray-600 group-hover:text-rentGreen transition-colors">Apartment</span>
                            </label>
                            <label class="flex items-center gap-2.5 cursor-pointer group">
                                <input type="checkbox" value="bedsitter" class="rent-filter-checkbox w-4 h-4 rounded border-gray-300 text-rentGreen focus:ring-rentGreen">
                                <span class="text-xs text-gray-600 group-hover:text-rentGreen transition-colors">Bedsitter</span>
                            </label>
                            <label class="flex items-center gap-2.5 cursor-pointer group">
                                <input type="checkbox" value="townhouse" class="rent-filter-checkbox w-4 h-4 rounded border-gray-300 text-rentGreen focus:ring-rentGreen">
                                <span class="text-xs text-gray-600 group-hover:text-rentGreen transition-colors">Townhouse</span>
                            </label>
                            <label class="flex items-center gap-2.5 cursor-pointer group">
                                <input type="checkbox" value="villa" class="rent-filter-checkbox w-4 h-4 rounded border-gray-300 text-rentGreen focus:ring-rentGreen">
                                <span class="text-xs text-gray-600 group-hover:text-rentGreen transition-colors">Villa</span>
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
                        <h4 class="text-[11px] uppercase tracking-wider font-bold text-gray-500 mb-2.5">Monthly Rent</h4>
                        <div class="flex items-center gap-2">
                            <input type="number" id="minPrice" placeholder="Min" class="w-full px-2.5 py-1.5 border border-gray-200 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-rentGreen/30">
                            <span class="text-gray-400 text-xs">—</span>
                            <input type="number" id="maxPrice" placeholder="Max" class="w-full px-2.5 py-1.5 border border-gray-200 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-rentGreen/30">
                        </div>
                        <p class="text-[10px] text-gray-400 mt-1.5">KSh per month</p>
                    </div>

                    <!-- Amenities -->
                    <div class="mb-5">
                        <h4 class="text-[11px] uppercase tracking-wider font-bold text-gray-500 mb-2.5">Amenities</h4>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2.5 cursor-pointer group">
                                <input type="checkbox" value="wifi" class="rent-filter-checkbox amenity-checkbox w-4 h-4 rounded border-gray-300 text-rentGreen focus:ring-rentGreen">
                                <span class="text-xs text-gray-600 group-hover:text-rentGreen transition-colors"><i class="fas fa-wifi text-[10px] text-gray-400 mr-1"></i> WiFi</span>
                            </label>
                            <label class="flex items-center gap-2.5 cursor-pointer group">
                                <input type="checkbox" value="pool" class="rent-filter-checkbox amenity-checkbox w-4 h-4 rounded border-gray-300 text-rentGreen focus:ring-rentGreen">
                                <span class="text-xs text-gray-600 group-hover:text-rentGreen transition-colors"><i class="fas fa-swimming-pool text-[10px] text-gray-400 mr-1"></i> Swimming Pool</span>
                            </label>
                            <label class="flex items-center gap-2.5 cursor-pointer group">
                                <input type="checkbox" value="parking" class="rent-filter-checkbox amenity-checkbox w-4 h-4 rounded border-gray-300 text-rentGreen focus:ring-rentGreen">
                                <span class="text-xs text-gray-600 group-hover:text-rentGreen transition-colors"><i class="fas fa-parking text-[10px] text-gray-400 mr-1"></i> Parking</span>
                            </label>
                            <label class="flex items-center gap-2.5 cursor-pointer group">
                                <input type="checkbox" value="security" class="rent-filter-checkbox amenity-checkbox w-4 h-4 rounded border-gray-300 text-rentGreen focus:ring-rentGreen">
                                <span class="text-xs text-gray-600 group-hover:text-rentGreen transition-colors"><i class="fas fa-shield-alt text-[10px] text-gray-400 mr-1"></i> 24/7 Security</span>
                            </label>
                            <label class="flex items-center gap-2.5 cursor-pointer group">
                                <input type="checkbox" value="garden" class="rent-filter-checkbox amenity-checkbox w-4 h-4 rounded border-gray-300 text-rentGreen focus:ring-rentGreen">
                                <span class="text-xs text-gray-600 group-hover:text-rentGreen transition-colors"><i class="fas fa-tree text-[10px] text-gray-400 mr-1"></i> Garden</span>
                            </label>
                            <label class="flex items-center gap-2.5 cursor-pointer group">
                                <input type="checkbox" value="borehole" class="rent-filter-checkbox amenity-checkbox w-4 h-4 rounded border-gray-300 text-rentGreen focus:ring-rentGreen">
                                <span class="text-xs text-gray-600 group-hover:text-rentGreen transition-colors"><i class="fas fa-water text-[10px] text-gray-400 mr-1"></i> Borehole Water</span>
                            </label>
                        </div>
                    </div>

                    <!-- Furnished toggle -->
                    <div class="pt-3 border-t border-gray-100">
                        <label class="flex items-center justify-between cursor-pointer">
                            <span class="text-xs text-gray-600 font-medium">
                                <i class="fas fa-couch text-accentOrange mr-1"></i> Furnished only
                            </span>
                            <div class="relative">
                                <input type="checkbox" id="furnishedToggle" class="sr-only peer">
                                <div class="w-9 h-5 bg-gray-200 rounded-full peer-checked:bg-rentGreen transition-colors"></div>
                                <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow peer-checked:translate-x-4 transition-transform"></div>
                            </div>
                        </label>
                    </div>
                </div>

                    <!-- Map teaser -->
                    <div class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 p-4">
                        <h3 class="font-serif text-sm text-navy font-semibold mb-2.5 flex items-center gap-1.5">
                            <i class="fas fa-map-marked-alt text-rentGreen text-xs"></i> Explore on Map
                        </h3>
                        <div class="map-placeholder rounded-xl h-32 flex items-center justify-center">
                            <div class="relative z-10 text-center">
                                <i class="fas fa-map-marker-alt text-rentGreen text-2xl mb-1 animate-bounce"></i>
                                <p class="text-[10px] text-rentGreen font-semibold">View rentals on map</p>
                            </div>
                        </div>
                    </div>

                </div>
            </aside>

            <!-- ===== LISTINGS ===== -->
            <div class="lg:w-3/4 w-full">

                <div class="flex items-center justify-between gap-3 mb-5">
                    <div>
                        <h2 class="font-serif text-2xl md:text-3xl text-navy font-semibold">
                            <span id="resultsCount">8</span> rentals available
                        </h2>
                        <p class="text-xs text-gray-500 mt-0.5" id="filterSummary"></p>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="relative">
                            <select id="sortSelect" onchange="applySort()" class="appearance-none bg-white border border-gray-200 rounded-lg pl-3 pr-8 py-2 text-xs font-medium text-gray-700 cursor-pointer focus:outline-none focus:ring-2 focus:ring-rentGreen/30">
                                <option value="recommended">Recommended</option>
                                <option value="price-low">Price: Low to High</option>
                                <option value="price-high">Price: High to Low</option>
                                <option value="rating">Top Rated</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] pointer-events-none"></i>
                        </div>
                    </div>
                </div>

                <div id="activeFilters" class="flex flex-wrap gap-1.5 mb-4 hidden"></div>

                <div id="rentalsGrid" class="grid grid-cols-2 gap-0.5 min-[375px]:gap-1 sm:gap-2 md:gap-5">
                    <!-- Cards injected -->
                </div>

                <div id="emptyState" class="hidden text-center py-20">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-search text-3xl text-gray-300"></i>
                    </div>
                    <h3 class="font-serif text-xl text-navy font-semibold mb-2">No rentals found</h3>
                    <p class="text-sm text-gray-500 mb-4">Try adjusting your filters or search criteria.</p>
                    <button onclick="resetAllFilters()" class="px-5 py-2.5 bg-rentGreen text-white text-sm font-semibold rounded-lg hover:bg-emerald-700 transition-colors">
                        Clear All Filters
                    </button>
                </div>

                <div class="flex items-center justify-center gap-1.5 mt-8" id="pagination"></div>

            </div>
        </div>
    </div>
</section>


<!-- ================= PROPERTY DETAIL MODAL ================= -->
<div id="rentModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-[3000] hidden items-center justify-center p-2 md:p-4">
    <div class="modal-content bg-white max-w-5xl w-full max-h-[95vh] md:max-h-[90vh] overflow-y-auto thin-scroll rounded-2xl md:rounded-3xl shadow-2xl relative">
        
        <button onclick="closeRentModal()" class="sticky top-3 float-right mr-3 ml-auto w-9 h-9 bg-white/90 backdrop-blur rounded-full shadow-lg flex items-center justify-center hover:bg-rentGreen hover:text-white transition-all z-50 border border-gray-200">
            <i class="fas fa-times text-sm"></i>
        </button>

        <!-- Gallery -->
        <div class="relative">
            <div class="h-[240px] md:h-[420px] overflow-hidden rounded-t-2xl md:rounded-t-3xl">
                <img id="modalMainImg" src="" alt="Rental" class="w-full h-full object-cover transition-opacity duration-500">
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
                        <span id="modalBadge" class="bg-rentGreen/10 text-rentGreen px-3 py-1 rounded-full text-[10px] md:text-xs font-bold uppercase">For Rent</span>
                        <span id="modalFurnished" class="hidden bg-accentOrange/10 text-accentOrange px-3 py-1 rounded-full text-[10px] md:text-xs font-bold uppercase">
                            <i class="fas fa-couch mr-1"></i>Furnished
                        </span>
                    </div>

                    <h2 id="modalTitle" class="font-serif text-2xl md:text-4xl text-navy font-semibold mb-2">Property Title</h2>
                    
                    <div class="flex flex-wrap items-center gap-3 text-gray-500 text-xs md:text-sm mb-4">
                        <span class="flex items-center gap-1.5">
                            <i class="fas fa-map-marker-alt text-rentGreen"></i>
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
                            <i class="fas fa-bed text-rentGreen text-sm md:text-lg mb-1"></i>
                            <p class="text-[9px] md:text-[10px] uppercase text-gray-400 font-bold">Bedrooms</p>
                            <p id="modalBeds" class="text-xs md:text-sm font-bold text-navy">3</p>
                        </div>
                        <div class="text-center border-l border-gray-200">
                            <i class="fas fa-bath text-rentGreen text-sm md:text-lg mb-1"></i>
                            <p class="text-[9px] md:text-[10px] uppercase text-gray-400 font-bold">Bathrooms</p>
                            <p id="modalBaths" class="text-xs md:text-sm font-bold text-navy">2</p>
                        </div>
                        <div class="text-center border-l border-gray-200">
                            <i class="fas fa-car text-rentGreen text-sm md:text-lg mb-1"></i>
                            <p class="text-[9px] md:text-[10px] uppercase text-gray-400 font-bold">Parking</p>
                            <p id="modalParking" class="text-xs md:text-sm font-bold text-navy">2</p>
                        </div>
                        <div class="text-center border-l border-gray-200">
                            <i class="fas fa-ruler-combined text-rentGreen text-sm md:text-lg mb-1"></i>
                            <p class="text-[9px] md:text-[10px] uppercase text-gray-400 font-bold">Size</p>
                            <p class="text-xs md:text-sm font-bold text-navy"><span id="modalSize">1,800</span> sqft</p>
                        </div>
                    </div>

                    <!-- Agent -->
                    <div class="flex items-center gap-3 mb-5 p-3 bg-gray-50 rounded-xl">
                        <div class="w-11 h-11 rounded-full bg-gradient-to-br from-rentGreen to-emerald-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
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
                            <i class="fas fa-info-circle text-rentGreen mr-1.5 text-sm"></i> About this property
                        </h3>
                        <p id="modalDesc" class="text-gray-600 text-xs md:text-sm leading-relaxed"></p>
                    </div>

                    <!-- Amenities -->
                    <div class="mb-5">
                        <h3 class="font-serif text-lg md:text-xl text-navy font-semibold mb-2.5">
                            <i class="fas fa-concierge-bell text-rentGreen mr-1.5 text-sm"></i> Amenities & Features
                        </h3>
                        <div id="modalAmenities" class="grid grid-cols-2 gap-2"></div>
                    </div>

                    <!-- Lease terms -->
                    <div class="mb-5">
                        <h3 class="font-serif text-lg md:text-xl text-navy font-semibold mb-2">
                            <i class="fas fa-file-contract text-rentGreen mr-1.5 text-sm"></i> Lease Terms
                        </h3>
                        <div class="grid grid-cols-2 gap-2 text-xs text-gray-600">
                            <div class="flex items-center gap-1.5"><i class="fas fa-check text-emerald-500 text-[10px]"></i> Min. 12-month lease</div>
                            <div class="flex items-center gap-1.5"><i class="fas fa-check text-emerald-500 text-[10px]"></i> 2 months deposit</div>
                            <div class="flex items-center gap-1.5"><i class="fas fa-check text-emerald-500 text-[10px]"></i> Rent paid monthly</div>
                            <div class="flex items-center gap-1.5"><i class="fas fa-check text-emerald-500 text-[10px]"></i> Utilities extra</div>
                        </div>
                    </div>

                    <!-- Google Map -->
                    <div class="mb-5">
                        <h3 class="font-serif text-lg md:text-xl text-navy font-semibold mb-2.5">
                            <i class="fas fa-map-marker-alt text-rentGreen mr-1.5 text-sm"></i> Property Location
                        </h3>
                        <div id="rentalMap" class="w-full h-64 rounded-xl overflow-hidden"></div>
                    </div>
                </div>

                <!-- Right: Enquiry Card -->
                <div class="lg:col-span-1">
                    <div class="lg:sticky lg:top-4 bg-white border border-gray-200 rounded-2xl p-4 md:p-5 shadow-xl">
                        <div class="mb-4">
                            <span id="modalPrice" class="text-2xl md:text-3xl font-bold text-navy">KSh 150,000</span>
                            <span class="text-gray-500 text-xs md:text-sm">/ month</span>
                        </div>

                        <div class="flex items-center gap-2 mb-4 p-2 bg-gray-50 rounded-lg">
                            <i class="fas fa-star text-accentOrange text-xs"></i>
                            <span class="text-sm font-semibold text-navy" id="modalRating2">4.8</span>
                            <span class="text-[10px] text-gray-400">·</span>
                            <span class="text-[10px] text-gray-500" id="modalReviews2">64 reviews</span>
                        </div>

                        <div class="space-y-2.5 mb-4">
                            <div class="border border-gray-200 rounded-lg px-2.5 py-2">
                                <label class="block text-[9px] uppercase text-gray-400 font-bold mb-0.5">Move-in Date</label>
                                <input type="date" class="w-full text-xs focus:outline-none bg-transparent">
                            </div>
                            <div class="border border-gray-200 rounded-lg px-2.5 py-2">
                                <label class="block text-[9px] uppercase text-gray-400 font-bold mb-0.5">Lease Duration</label>
                                <select class="w-full text-xs focus:outline-none bg-transparent">
                                    <option>12 months</option>
                                    <option>24 months</option>
                                    <option>6 months</option>
                                    <option>Short let</option>
                                </select>
                            </div>
                        </div>

                        <div class="space-y-1.5 py-3 border-t border-gray-100 text-xs">
                            <div class="flex justify-between text-gray-600">
                                <span>Monthly rent</span>
                                <span id="modalRentLine">KSh 150,000</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Deposit (2 months)</span>
                                <span id="modalDeposit">KSh 300,000</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Agent fee</span>
                                <span>KSh 0</span>
                            </div>
                            <div class="flex justify-between font-bold text-navy pt-2 border-t border-gray-100 text-sm">
                                <span>Move-in cost</span>
                                <span id="modalMoveIn">KSh 450,000</span>
                            </div>
                        </div>

                        <button class="w-full py-3 bg-gradient-to-r from-rentGreen to-emerald-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-rentGreen/30 hover:shadow-xl hover:shadow-rentGreen/40 transition-all mt-3">
                            <i class="fas fa-calendar-check mr-2"></i> Schedule Viewing
                        </button>
                        <button class="w-full py-2.5 mt-2 border border-gray-200 text-gray-600 text-xs font-semibold rounded-xl hover:bg-gray-50 transition-colors">
                            <i class="fas fa-envelope mr-1.5"></i> Contact Agent
                        </button>

                        <p class="text-center text-[10px] text-gray-400 mt-3">
                            <i class="fas fa-shield-alt mr-1"></i> Verified listing · No hidden fees
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- ================= PAGE-SPECIFIC CSS ================= -->
<style>
    * { -webkit-tap-highlight-color: transparent; }

    /* ===== HERO ===== */
    .rent-hero {
        background: linear-gradient(135deg, rgba(0, 21, 64, 0.93), rgba(15, 118, 110, 0.82)),
                    url('https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1600&q=80');
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
    .search-field.active { background: #F3F4F6; box-shadow: inset 0 0 0 2px #0F766E; }

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
    .chip:hover { border-color: #0F766E; color: #0F766E; }
    .chip.active {
        background: #0F766E; border-color: #0F766E; color: white;
        box-shadow: 0 4px 12px rgba(15, 118, 110, 0.3);
    }

    /* ===== RENTAL CARD ===== */
    .rent-card {
        background: white; border-radius: 18px; overflow: hidden;
        cursor: pointer; transition: all 0.35s cubic-bezier(0.25, 0.8, 0.25, 1);
        border: 1px solid #F1F5F9;
        box-shadow: 0 4px 10px -2px rgba(0, 0, 0, 0.04);
    }
    .rent-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 24px 40px -12px rgba(0, 0, 0, 0.18);
        border-color: rgba(15, 118, 110, 0.25);
    }
    .rent-card .rent-img { transition: transform 0.7s cubic-bezier(0.25, 0.8, 0.25, 1); }
    .rent-card:hover .rent-img { transform: scale(1.08); }

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

    .badge-rent {
        position: absolute; top: 12px; left: 12px;
        padding: 4px 12px; border-radius: 9999px;
        font-size: 0.65rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.03em; color: white; z-index: 5;
    }

    /* ===== MODAL ===== */
    #rentModal { opacity: 0; transition: opacity 0.3s ease; }
    #rentModal.show { opacity: 1; }
    #rentModal .modal-content {
        transform: scale(0.95) translateY(10px);
        transition: transform 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }
    #rentModal.show .modal-content { transform: scale(1) translateY(0); }

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
        background: linear-gradient(135deg, #E0F2FE, #F0FDFA);
        position: relative; overflow: hidden;
    }
    .map-placeholder::after {
        content: ''; position: absolute; inset: 0;
        background-image: 
            linear-gradient(rgba(15, 118, 110, 0.08) 1px, transparent 1px),
            linear-gradient(90deg, rgba(15, 118, 110, 0.08) 1px, transparent 1px);
        background-size: 30px 30px;
    }
    #rentalMap { width: 100%; height: 200px; border-radius: 12px; background: #eef2f2; }
</style>

<!-- ================= SCRIPT ================= -->
<script>
/* ============================================================
   DATA — rental properties from database
   ============================================================ */
const rentalsData = <?php echo json_encode($rentals); ?>;

/* ============================================================
   STATE
   ============================================================ */
let currentFilters = {
    types: [],
    amenities: [],
    minPrice: null,
    maxPrice: null,
    beds: null,
    furnished: false,
    search: '',
    quickTag: null
};
let currentSort = 'recommended';
let currentView = 'grid';
let currentPage = 1;
const PER_PAGE = 6;
let modalSlideInterval = null;
let modalSlideIndex = 0;
let map = null;
let marker = null;

/* ============================================================
   FILTERING
   ============================================================ */
function getFilteredRentals() {
    let results = [...rentalsData];

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

    if (currentFilters.furnished)
        results = results.filter(s => s.furnished);

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
    else if (currentSort === 'price-high') results = results.sort((a, b) => b.price - a.price);
    else if (currentSort === 'rating') results.sort((a, b) => b.rating - a.rating);

    return results;
}

/* ============================================================
   RENDER
   ============================================================ */
function renderRentals() {
    const grid = document.getElementById('rentalsGrid');
    const empty = document.getElementById('emptyState');
    const countEl = document.getElementById('resultsCount');
    const summaryEl = document.getElementById('filterSummary');
    const pagination = document.getElementById('pagination');
    const activeFiltersEl = document.getElementById('activeFilters');

    const filtered = getFilteredRentals();
    countEl.textContent = filtered.length;

    let summaryParts = [];
    if (currentFilters.quickTag) summaryParts.push(currentFilters.quickTag);
    if (currentFilters.types.length) summaryParts.push(currentFilters.types.join(', '));
    if (currentFilters.amenities.length) summaryParts.push(currentFilters.amenities.length + ' amenities');
    if (currentFilters.minPrice || currentFilters.maxPrice) summaryParts.push('price filtered');
    if (currentFilters.beds) summaryParts.push(currentFilters.beds + ' beds');
    if (currentFilters.furnished) summaryParts.push('furnished');
    summaryEl.textContent = summaryParts.length > 0
        ? 'Filtered by: ' + summaryParts.join(' · ')
        : '';

    let chips = [];
    if (currentFilters.quickTag) chips.push({label: '🏷️ ' + currentFilters.quickTag, fn: 'removeQuickTag()'});
    currentFilters.types.forEach(t => chips.push({label: t, fn: `removeType('${t}')`}));
    currentFilters.amenities.forEach(a => chips.push({label: a, fn: `removeAmenity('${a}')`}));
    if (currentFilters.beds) chips.push({label: currentFilters.beds + ' beds', fn: 'removeBeds()'});
    if (currentFilters.furnished) chips.push({label: 'Furnished', fn: 'removeFurnished()'});
    if (currentFilters.minPrice || currentFilters.maxPrice) chips.push({label: 'Price', fn: 'removePrice()'});

    if (chips.length > 0) {
        activeFiltersEl.classList.remove('hidden');
        activeFiltersEl.innerHTML = chips.map(c =>
            `<button onclick="${c.fn}" class="inline-flex items-center gap-1 bg-rentGreen/10 text-rentGreen text-[10px] font-semibold px-2.5 py-1 rounded-full border border-rentGreen/20 hover:bg-rentGreen hover:text-white transition-colors">
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
        const badgeColor = r.type === 'villa' ? 'bg-rentGold'
                         : r.type === 'house' ? 'bg-navy'
                         : r.type === 'townhouse' ? 'bg-indigo-600'
                         : r.type === 'bedsitter' ? 'bg-emerald-600'
                         : 'bg-rentGreen';

        if (currentView === 'grid') {
            return `
                <article class="rent-card anim-fade-up group" onclick="openRentModal('${r.id}')">
                    <div class="relative h-52 overflow-hidden">
                        <img src="${r.images[0]}" alt="${r.title}" class="rent-img w-full h-full object-cover">
                        <span class="badge-rent ${badgeColor}">${r.badge}</span>
                        ${r.furnished ? `<span class="absolute top-3 left-3 mt-7 bg-accentOrange text-white text-[9px] font-bold px-2 py-0.5 rounded-full"><i class="fas fa-couch mr-0.5"></i>Furnished</span>` : ''}
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
                            <i class="fas fa-map-marker-alt text-rentGreen text-[9px] mr-1"></i>${r.location}
                        </p>
                        <div class="flex items-center gap-2 text-[10px] text-gray-400 mb-2.5">
                            <span><i class="fas fa-bed mr-0.5"></i>${r.beds} Beds</span>
                            <span>·</span>
                            <span><i class="fas fa-bath mr-0.5"></i>${r.baths} Baths</span>
                            <span>·</span>
                            <span><i class="fas fa-car mr-0.5"></i>${r.parking}</span>
                        </div>
                        <div class="flex items-end justify-between pt-2.5 border-t border-gray-100">
                            <div>
                                <span class="text-base md:text-lg font-bold text-navy">KSh ${r.price.toLocaleString()}</span>
                                <span class="text-[10px] text-gray-400">/ month</span>
                            </div>
                            <span class="text-[10px] text-gray-400">${r.reviews} reviews</span>
                        </div>
                    </div>
                </article>
            `;
        } else {
            return `
                <article class="bg-white rounded-xl overflow-hidden border border-gray-100 hover:shadow-lg transition-all cursor-pointer flex" onclick="openRentModal('${r.id}')">
                    <div class="relative w-40 sm:w-56 flex-shrink-0 overflow-hidden">
                        <img src="${r.images[0]}" alt="${r.title}" class="w-full h-full object-cover">
                        <span class="badge-rent ${badgeColor}">${r.badge}</span>
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
                            <p class="text-[11px] text-gray-500 mb-1"><i class="fas fa-map-marker-alt text-rentGreen text-[9px] mr-1"></i>${r.location}</p>
                            <p class="text-[11px] text-gray-400 line-clamp-2 hidden sm:block">${r.desc}</p>
                        </div>
                        <div class="flex items-center justify-between pt-2 border-t border-gray-100 mt-2">
                            <div class="flex items-center gap-2 text-[10px] text-gray-400">
                                <span><i class="fas fa-bed mr-0.5"></i>${r.beds}</span>
                                <span><i class="fas fa-bath mr-0.5"></i>${r.baths}</span>
                                <span><i class="fas fa-car mr-0.5"></i>${r.parking}</span>
                            </div>
                            <div>
                                <span class="text-sm md:text-base font-bold text-navy">KSh ${r.price.toLocaleString()}</span>
                                <span class="text-[10px] text-gray-400">/mo</span>
                            </div>
                        </div>
                    </div>
                </article>
            `;
        }
    }).join('');

    if (totalPages > 1) {
        let pag = `<button onclick="goToPage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''} class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:border-rentGreen hover:text-rentGreen transition-colors disabled:opacity-40 disabled:cursor-not-allowed"><i class="fas fa-chevron-left text-[10px]"></i></button>`;
        for (let i = 1; i <= totalPages; i++) {
            pag += `<button onclick="goToPage(${i})" class="w-9 h-9 flex items-center justify-center rounded-lg text-xs font-semibold transition-colors ${i === currentPage ? 'bg-rentGreen text-white shadow-md shadow-rentGreen/30' : 'border border-gray-200 text-gray-600 hover:border-rentGreen hover:text-rentGreen'}">${i}</button>`;
        }
        pag += `<button onclick="goToPage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''} class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:border-rentGreen hover:text-rentGreen transition-colors disabled:opacity-40 disabled:cursor-not-allowed"><i class="fas fa-chevron-right text-[10px]"></i></button>`;
        pagination.innerHTML = pag;
    } else {
        pagination.innerHTML = '';
    }
}

function goToPage(page) {
    const filtered = getFilteredRentals();
    const totalPages = Math.ceil(filtered.length / PER_PAGE) || 1;
    if (page < 1 || page > totalPages) return;
    currentPage = page;
    renderRentals();
    document.getElementById('rentalsGrid').scrollIntoView({behavior: 'smooth', block: 'start'});
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
        renderRentals();
    });
});

document.querySelectorAll('.amenity-checkbox').forEach(cb => {
    cb.addEventListener('change', function() {
        const val = this.value;
        if (this.checked) currentFilters.amenities.push(val);
        else currentFilters.amenities = currentFilters.amenities.filter(a => a !== val);
        currentPage = 1;
        renderRentals();
    });
});

document.getElementById('minPrice').addEventListener('input', function() {
    currentFilters.minPrice = this.value ? parseInt(this.value) : null;
    currentPage = 1;
    renderRentals();
});
document.getElementById('maxPrice').addEventListener('input', function() {
    currentFilters.maxPrice = this.value ? parseInt(this.value) : null;
    currentPage = 1;
    renderRentals();
});

document.getElementById('furnishedToggle').addEventListener('change', function() {
    currentFilters.furnished = this.checked;
    currentPage = 1;
    renderRentals();
});

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
    renderRentals();
}

function quickFilter(tag) {
    currentFilters.quickTag = tag;
    currentPage = 1;
    renderRentals();
}

function resetAllFilters() {
    currentFilters = {types: [], amenities: [], minPrice: null, maxPrice: null, beds: null, furnished: false, search: '', quickTag: null};
    document.querySelectorAll('#typeFilters input[type="checkbox"]').forEach(cb => cb.checked = false);
    document.querySelectorAll('.amenity-checkbox').forEach(cb => cb.checked = false);
    document.querySelectorAll('#bedFilters .chip').forEach(c => c.classList.remove('active'));
    document.getElementById('minPrice').value = '';
    document.getElementById('maxPrice').value = '';
    document.getElementById('furnishedToggle').checked = false;
    currentPage = 1;
    renderRentals();
}

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

function removeQuickTag() { currentFilters.quickTag = null; renderRentals(); }
function removeType(t) {
    currentFilters.types = currentFilters.types.filter(x => x !== t);
    document.querySelectorAll('#typeFilters input').forEach(cb => { if (cb.value === t) cb.checked = false; });
    renderRentals();
}
function removeAmenity(a) {
    currentFilters.amenities = currentFilters.amenities.filter(x => x !== a);
    document.querySelectorAll('.amenity-checkbox').forEach(cb => { if (cb.value === a) cb.checked = false; });
    renderRentals();
}
function removeBeds() {
    currentFilters.beds = null;
    document.querySelectorAll('#bedFilters .chip').forEach(c => c.classList.remove('active'));
    renderRentals();
}
function removeFurnished() {
    currentFilters.furnished = false;
    document.getElementById('furnishedToggle').checked = false;
    renderRentals();
}
function removePrice() {
    currentFilters.minPrice = null;
    currentFilters.maxPrice = null;
    document.getElementById('minPrice').value = '';
    document.getElementById('maxPrice').value = '';
    renderRentals();
}

/* ============================================================
   SORT & VIEW
   ============================================================ */
function applySort() {
    currentSort = document.getElementById('sortSelect').value;
    currentPage = 1;
    renderRentals();
}

function setView(view, btn) {
    currentView = view;
    document.querySelectorAll('.view-btn').forEach(b => {
        b.classList.remove('active', 'bg-rentGreen', 'text-white');
        b.classList.add('text-gray-400');
    });
    btn.classList.add('active', 'bg-rentGreen', 'text-white');
    btn.classList.remove('text-gray-400');
    renderRentals();
}

/* ============================================================
   SEARCH
   ============================================================ */
function handleSearch(e) {
    e.preventDefault();
    currentFilters.search = document.getElementById('searchLocation').value.toLowerCase();
    currentPage = 1;
    renderRentals();
    document.getElementById('rentalsGrid').scrollIntoView({behavior: 'smooth', block: 'start'});
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
   GOOGLE MAPS
   ============================================================ */
function initPropertyMap(lat, lng) {
    if (typeof google === 'undefined' || !google.maps) {
        const mapEl = document.getElementById('rentalMap');
        mapEl.innerHTML = '<div class="w-full h-full flex flex-col items-center justify-center text-gray-400 bg-gray-100"><i class="fas fa-map-marker-alt text-2xl mb-2"></i><p class="text-xs">Map unavailable</p></div>';
        return;
    }
    const mapEl = document.getElementById('rentalMap');
    if (!lat || !lng) {
        mapEl.innerHTML = '<div class="w-full h-full flex flex-col items-center justify-center text-gray-400"><i class="fas fa-map-marker-alt text-2xl mb-2"></i><p class="text-xs">Location not available</p></div>';
        return;
    }
    const position = { lat: lat, lng: lng };
    map = new google.maps.Map(mapEl, {
        center: position,
        zoom: 15,
        disableDefaultUI: true,
        zoomControl: true,
        styles: [
            { featureType: 'poi', elementType: 'labels', stylers: [{ visibility: 'off' }] }
        ]
    });
    marker = new google.maps.Marker({
        position: position,
        map: map,
        title: 'Property Location'
    });
}

function destroyMap() {
    if (map) {
        map = null;
    }
    if (marker) {
        marker = null;
    }
    const mapEl = document.getElementById('rentalMap');
    mapEl.innerHTML = '';
}

/* ============================================================
   MODAL
   ============================================================ */
function openRentModal(id) {
    const r = rentalsData.find(s => s.id === id);
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
    document.getElementById('modalFurnished').classList.toggle('hidden', !r.furnished);

    // Price breakdown
    document.getElementById('modalRentLine').textContent = `KSh ${r.price.toLocaleString()}`;
    document.getElementById('modalDeposit').textContent = `KSh ${(r.price * 2).toLocaleString()}`;
    document.getElementById('modalMoveIn').textContent = `KSh ${(r.price * 3).toLocaleString()}`;

    // Amenities
    const amenContainer = document.getElementById('modalAmenities');
    amenContainer.innerHTML = r.amenities.map(a => `
        <div class="flex items-center gap-2 text-xs text-gray-600">
            <i class="fas ${a.icon} text-rentGreen text-[11px] w-4 text-center"></i>
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

    // Init map after modal is shown
    const modal = document.getElementById('rentModal');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    requestAnimationFrame(() => {
        modal.classList.add('show');
        initPropertyMap(r.lat, r.lng);
    });

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
        if (document.getElementById('rentModal').classList.contains('hidden')) return;
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

function closeRentModal() {
    const modal = document.getElementById('rentModal');
    modal.classList.remove('show');
    setTimeout(() => {
        modal.classList.add('hidden');
        destroyMap();
    }, 300);
    document.body.style.overflow = '';
    if (modalSlideInterval) {
        clearInterval(modalSlideInterval);
        modalSlideInterval = null;
    }
}

document.getElementById('rentModal').addEventListener('click', function(e) {
    if (e.target === this) closeRentModal();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeRentModal();
});

/* ============================================================
   INIT
   ============================================================ */
document.addEventListener('DOMContentLoaded', function() {
    renderRentals();
});
</script>