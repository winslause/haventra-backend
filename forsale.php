<?php
// forsale.php - Houses & Property for Sale page
// $pdo is already available from index.php

$page_title = 'Houses & Property for Sale | HAVENTRA Real Estate';
$page_description = 'Buy houses, apartments, land, townhouses, and commercial property for sale. Browse verified listings with freehold and leasehold titles on HAVENTRA.';
$page_canonical = 'https://haventra.com/forsale/';

// Fetch sale properties from database
$sales = [];
try {
    $stmt = $pdo->query("SELECT * FROM properties WHERE is_active = 1 AND type = 'sale' ORDER BY created_at DESC");
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
        elseif (strpos($titleLower, 'land') !== false) $propType = 'land';
        elseif (strpos($titleLower, 'apartment') !== false || strpos($titleLower, 'studio') !== false || strpos($titleLower, 'loft') !== false) $propType = 'apartment';
        else $propType = 'house';

        $sales[] = [
            'id' => 'sale-' . $row['id'],
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
            'badge' => htmlspecialchars($row['badge'] ?? 'For Sale'),
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
                    'study room' => 'fa-book',
                    'freehold' => 'fa-file-contract', 'leasehold' => 'fa-file-contract',
                    'title deed' => 'fa-certificate', 'title_deed' => 'fa-certificate'
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
    $sales = [];
}
?>

<!-- ===== SEO META TAGS ===== -->
<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
<meta name="googlebot" content="index, follow, max-snippet:-1, max-image-preview:large">
<meta name="bingbot" content="index, follow, max-snippet:-1, max-image-preview:large">
<meta name="language" content="English">
<meta name="geo.region" content="World">
<meta name="geo.placename" content="Worldwide">
<meta name="geo.position" content="0;0">
<meta name="ICBM" content="0, 0">
<link rel="sitemap" href="/haventra/sitemap.xml">
<link rel="alternate" href="https://haventra.com/forsale/" hreflang="en">
<link rel="alternate" href="https://haventra.com/forsale/" hreflang="en-ke">
<link rel="author" href="https://haventra.com/about/">

<!-- Schema.org: RealEstateAggregator for Sale -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "RealEstateAggregator",
    "name": "HAVENTRA For Sale",
    "url": "https://haventra.com/forsale/",
    "description": "Buy houses, apartments, land, townhouses, and commercial property for sale.",
    "address": {
        "@type": "PostalAddress",
        "addressLocality": "Nairobi",
        "addressRegion": "Nairobi County",
        "addressCountry": "KE"
    },
    "areaServed": [
        <?php
        $dbLocations = [];
        foreach ($sales as $s) {
            $loc = $s['location'];
            if (!isset($dbLocations[$loc])) $dbLocations[$loc] = 0;
            $dbLocations[$loc]++;
        }
        arsort($dbLocations);
        $areaItems = [];
        foreach ($dbLocations as $loc => $count) {
            $areaItems[] = '{"@type": "City", "name": "' . addslashes($loc) . '"}';
        }
        echo implode(',', $areaItems);
        ?>
    ],
    "hasOfferCatalog": {
        "@type": "OfferCatalog",
        "name": "Properties for Sale",
        "itemListElement": [
            <?php
            $schemaItems = [];
            foreach ($sales as $idx => $s) {
                $schemaItems[] = '{
                    "@type": "RealEstateListing",
                    "position": ' . ($idx + 1) . ',
                    "itemOffered": {
                        "@type": "House",
                        "name": "' . addslashes($s['title']) . '",
                        "description": "' . addslashes($s['desc']) . '",
                        "address": {
                            "@type": "PostalAddress",
                            "addressLocality": "' . addslashes($s['location']) . '",
                            "addressCountry": "KE"
                        },
                        "numberOfRooms": ' . $s['beds'] . ',
                        "floorSize": {
                            "@type": "QuantitativeValue",
                            "value": "' . $s['size'] . '",
                            "unitText": "sqft"
                        },
                        "amenityFeature": [
                            ' . implode(',', array_map(fn($a) => '{"@type":"AmenityFeature","name":"' . addslashes($a['label']) . '"}', $s['amenities'])) . ']
                    },
                    "priceSpecification": {
                        "@type": "UnitPriceSpecification",
                        "price": ' . $s['price'] . ',
                        "priceCurrency": "KES",
                        "pricingModel": "http://schema.org/MonthlyRate"
                    }
                }';
            }
            echo implode(',', $schemaItems);
            ?>
        ]
    }
}
</script>

<!-- Schema.org: Breadcrumb -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        {
            "@type": "ListItem",
            "position": 1,
            "name": "Home",
            "item": "https://haventra.com/"
        },
        {
            "@type": "ListItem",
            "position": 2,
            "name": "For Sale",
            "item": "https://haventra.com/forsale/"
        }
    ]
}
</script>

<?php
// Build dynamic location and type lists for SEO content
$dbLocations = [];
foreach ($sales as $s) {
    $loc = $s['location'];
    if (!isset($dbLocations[$loc])) $dbLocations[$loc] = 0;
    $dbLocations[$loc]++;
}
arsort($dbLocations);
$topLocations = array_slice($dbLocations, 0, 12, true);

$dbTypes = [];
foreach ($sales as $s) {
    $t = $s['type'];
    if (!isset($dbTypes[$t])) $dbTypes[$t] = 0;
    $dbTypes[$t]++;
}
?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
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
    #saleMap { width: 100%; height: 200px; border-radius: 12px; background: #fff7e6; }

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

<!-- ================= HERO WITH SEARCH ================= -->
<section class="sale-hero relative pt-10 pb-16 md:pt-12 md:pb-24 px-4">
    
    <!-- Hero heading -->
    <div class="max-w-4xl mx-auto text-center relative z-10 mb-6 md:mb-10">
        <span class="inline-block bg-white/15 backdrop-blur text-white text-[10px] md:text-xs font-bold uppercase tracking-[0.2em] px-4 py-1.5 rounded-full mb-4 border border-white/20">
            <i class="fas fa-key mr-1.5 text-accentOrange"></i> Property For Sale
        </span>
        <h1 class="font-serif text-3xl md:text-6xl text-white font-semibold leading-tight mb-3 md:mb-4 drop-shadow-lg">
            Own your <span class="italic text-accentOrange">dream</span> property
        </h1>
        <p class="text-white/80 text-sm md:text-base max-w-2xl mx-auto">
            Verified houses, land, apartments, and commercial property for sale — freehold & leasehold titles, verified agents.
        </p>
    </div>

    <!-- ===== SEARCH BAR ===== -->
    <div class="max-w-2xl mx-auto relative z-20">
        <form onsubmit="handleSearch(event)" class="search-shell p-1.5 md:p-2 flex items-center gap-1">
            
            <!-- Location -->
            <div class="search-field flex-1 px-3 py-2 md:py-2.5 cursor-pointer" onclick="activateField(this)">
                <div class="flex items-center gap-2">
                    <i class="fas fa-map-marker-alt text-saleGold text-xs"></i>
                    <input type="text" id="searchLocation" placeholder="Search by location" class="search-input font-medium">
                </div>
            </div>

            <!-- Search button -->
            <button type="submit" class="bg-gradient-to-r from-saleGold to-amber-600 text-white rounded-full px-5 py-2.5 md:px-6 md:py-3 flex items-center justify-center gap-2 font-semibold text-sm shadow-lg shadow-saleGold/30 hover:shadow-xl hover:shadow-saleGold/40 transition-all duration-300">
                <i class="fas fa-search text-xs"></i>
                <span class="hidden sm:inline">Search</span>
            </button>
        </form>

        <!-- Quick chips -->
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


<!-- ===== SEO CONTENT ===== -->
<section class="py-8 md:py-12 bg-white border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 md:px-6">
        <div class="mb-8 md:mb-10">
            <div>
                <h2 class="font-serif text-xl md:text-2xl text-navy font-semibold mb-3">Houses & Property for Sale</h2>
                <p class="text-gray-600 text-xs md:text-sm leading-relaxed mb-3">
                    Searching for <strong>houses for sale</strong>? HAVENTRA is your trusted real estate marketplace for verified properties across <?php echo implode(', ', array_keys(array_slice($dbLocations, 0, 6))); ?> and more. Find your dream home today.
                </p>
                <p class="text-gray-600 text-xs md:text-sm leading-relaxed mb-3">
                    Our property listings include detailed information, high-quality photos, amenities, title deed verification, and exact location coordinates. Filter by <strong>sale price</strong>, <strong>bedrooms</strong>, <strong>property type</strong>, and <strong>amenities</strong> to find your perfect property. All listings are verified by our team of real estate agents to ensure transparency and security.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 md:gap-6">
            <div>
                <h3 class="font-serif text-base md:text-lg text-navy font-semibold mb-2">Popular Property Locations</h3>
                <ul class="space-y-1 text-xs md:text-sm text-gray-600">
                    <?php foreach ($topLocations as $loc => $count): ?>
                    <li><a href="/haventra/houses-for-sale/?search=<?php echo urlencode($loc); ?>" class="text-saleGold hover:underline transition-colors"><?php echo htmlspecialchars($loc); ?> (<?php echo $count; ?> properties)</a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div>
                <h3 class="font-serif text-base md:text-lg text-navy font-semibold mb-2">Property Types for Sale</h3>
                <ul class="space-y-1 text-xs md:text-sm text-gray-600">
                    <?php foreach ($dbTypes as $type => $count): ?>
                    <li><a href="/haventra/houses-for-sale/?type=<?php echo urlencode($type); ?>" class="text-saleGold hover:underline transition-colors"><?php echo ucfirst(htmlspecialchars($type)); ?> for Sale (<?php echo $count; ?>)</a></li>
                    <?php endforeach; ?>
                    <li><a href="/haventra/houses-for-sale/" class="text-saleGold hover:underline transition-colors">Freehold Properties</a></li>
                    <li><a href="/haventra/houses-for-sale/" class="text-saleGold hover:underline transition-colors">Land for Sale</a></li>
                </ul>
            </div>
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
                        <button onclick="resetAllFilters()" class="text-[10px] text-saleGold hover:text-amber-700 font-semibold underline transition-colors">
                            Reset all
                        </button>
                    </div>

                    <!-- Property Type -->
                    <div class="mb-5">
                        <h4 class="text-[11px] uppercase tracking-wider font-bold text-gray-500 mb-2.5">Property Type</h4>
                        <div class="space-y-2" id="typeFilters">
                            <label class="flex items-center gap-2.5 cursor-pointer group">
                                <input type="checkbox" value="house" class="sale-filter-checkbox w-4 h-4 rounded border-gray-300 text-saleGold focus:ring-saleGold">
                                <span class="text-xs text-gray-600 group-hover:text-saleGreen transition-colors">House</span>
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
                                <input type="checkbox" value="villa" class="sale-filter-checkbox w-4 h-4 rounded border-gray-300 text-saleGold focus:ring-saleGold">
                                <span class="text-xs text-gray-600 group-hover:text-saleGold transition-colors">Villa</span>
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
                            <span class="text-gray-400 text-xs">—</span>
                            <input type="number" id="maxPrice" placeholder="Max" class="w-full px-2.5 py-1.5 border border-gray-200 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-saleGold/30">
                        </div>
                        <p class="text-[10px] text-gray-400 mt-1.5">KSh</p>
                    </div>

                    <!-- Amenities -->
                    <div class="mb-5">
                        <h4 class="text-[11px] uppercase tracking-wider font-bold text-gray-500 mb-2.5">Amenities</h4>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2.5 cursor-pointer group">
                                <input type="checkbox" value="wifi" class="sale-filter-checkbox amenity-checkbox w-4 h-4 rounded border-gray-300 text-saleGold focus:ring-saleGold">
                                <span class="text-xs text-gray-600 group-hover:text-saleGold transition-colors"><i class="fas fa-wifi text-[10px] text-gray-400 mr-1"></i> WiFi</span>
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
                                <input type="checkbox" value="freehold" class="sale-filter-checkbox amenity-checkbox w-4 h-4 rounded border-gray-300 text-saleGold focus:ring-saleGold">
                                <span class="text-xs text-gray-600 group-hover:text-saleGold transition-colors"><i class="fas fa-file-contract text-[10px] text-gray-400 mr-1"></i> Freehold Title</span>
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
                                <div class="w-9 h-5 bg-gray-200 rounded-full peer-checked:bg-saleGold transition-colors"></div>
                                <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow peer-checked:translate-x-4 transition-transform"></div>
                            </div>
                        </label>
                    </div>
                </div>

                    <!-- Map teaser -->
                    <!-- <div class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 p-4">
                        <h3 class="font-serif text-sm text-navy font-semibold mb-2.5 flex items-center gap-1.5">
                            <i class="fas fa-map-marked-alt text-saleGold text-xs"></i> Explore on Map
                        </h3>
                        <div class="map-placeholder rounded-xl h-32 flex items-center justify-center">
                            <div class="relative z-10 text-center">
                                <i class="fas fa-map-marker-alt text-saleGold text-2xl mb-1 animate-bounce"></i>
                                <p class="text-[10px] text-saleGold font-semibold">View properties on map</p>
                            </div>
                        </div>
                    </div> -->

                </div>
            </aside>

            <!-- ===== LISTINGS ===== -->
            <div class="lg:w-3/4 w-full">

                <div class="flex items-center justify-between gap-3 mb-5">
                    <div>
                        <h2 class="font-serif text-2xl md:text-3xl text-navy font-semibold">
                            <span id="resultsCount"><?php echo count($sales); ?></span> properties for sale
                        </h2>
                        <p class="text-xs text-gray-500 mt-0.5" id="filterSummary"></p>
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
                    </div>
                </div>

                <div id="activeFilters" class="flex flex-wrap gap-1.5 mb-4 hidden"></div>

                <div id="salesGrid" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 md:gap-5">
                    <?php foreach ($sales as $s): 
                        $badgeColor = ($s['type'] === 'villa') ? 'bg-saleGold'
                            : (($s['type'] === 'house') ? 'bg-navy'
                            : (($s['type'] === 'townhouse') ? 'bg-indigo-600'
                            : (($s['type'] === 'land') ? 'bg-emerald-600'
                            : 'bg-saleGold')));
                    ?>
                    <article class="sale-card anim-fade-up group" onclick="openSaleModal('<?php echo $s['id']; ?>')">
                        <div class="relative h-52 overflow-hidden">
                            <img src="<?php echo $s['images'][0]; ?>" alt="<?php echo $s['title']; ?>" class="sale-img w-full h-full object-cover">
                            <span class="badge-sale <?php echo $badgeColor; ?>"><?php echo $s['badge']; ?></span>
                            <?php if ($s['furnished']): ?>
                            <span class="absolute top-3 left-3 mt-7 bg-accentOrange text-white text-[9px] font-bold px-2 py-0.5 rounded-full"><i class="fas fa-couch mr-0.5"></i>Furnished</span>
                            <?php endif; ?>
                            <button onclick="toggleFavorite(event, this)" class="favorite-btn" aria-label="Save">
                                <i class="far fa-heart"></i>
                            </button>
                        </div>
                        <div class="p-3.5">
                            <div class="flex items-start justify-between gap-2 mb-1">
                                <h3 class="font-serif text-base md:text-lg text-navy font-semibold leading-tight line-clamp-1"><?php echo $s['title']; ?></h3>
                                <div class="flex items-center gap-1 flex-shrink-0">
                                    <i class="fas fa-star text-accentOrange text-[10px]"></i>
                                    <span class="text-xs font-semibold text-navy"><?php echo $s['rating']; ?></span>
                                </div>
                            </div>
                            <p class="text-[11px] text-gray-500 mb-2 line-clamp-1">
                                <i class="fas fa-map-marker-alt text-saleGold text-[9px] mr-1"></i><?php echo $s['location']; ?>
                            </p>
                            <div class="flex items-center gap-2 text-[10px] text-gray-400 mb-2.5">
                                <span><i class="fas fa-bed mr-0.5"></i><?php echo $s['beds']; ?> Beds</span>
                                <span>·</span>
                                <span><i class="fas fa-bath mr-0.5"></i><?php echo $s['baths']; ?> Baths</span>
                                <span>·</span>
                                <span><i class="fas fa-car mr-0.5"></i><?php echo $s['parking']; ?></span>
                            </div>
                            <div class="flex items-end justify-between pt-2.5 border-t border-gray-100">
                                <div>
                                    <span class="text-base md:text-lg font-bold text-navy">KSh <?php echo number_format($s['price']); ?></span>
                                    <span class="text-[10px] text-gray-400">/ sale</span>
                                </div>
                                <span class="text-[10px] text-gray-400"><?php echo $s['reviews']; ?> reviews</span>
                            </div>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>

                <div id="emptyState" class="hidden text-center py-20">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-search text-3xl text-gray-300"></i>
                    </div>
                    <h3 class="font-serif text-xl text-navy font-semibold mb-2">No properties found</h3>
                    <p class="text-sm text-gray-500 mb-4">Try adjusting your filters or search criteria.</p>
                    <button onclick="resetAllFilters()" class="px-5 py-2.5 bg-saleGold text-white text-sm font-semibold rounded-lg hover:bg-amber-700 transition-colors">
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
                <img id="saleMainImg" src="" alt="Property for Sale" class="w-full h-full object-cover transition-opacity duration-500">
            </div>
            <div class="absolute bottom-3 right-3 bg-black/60 backdrop-blur text-white text-[10px] md:text-xs font-semibold px-3 py-1.5 rounded-full flex items-center gap-1.5">
                <i class="fas fa-camera"></i> <span id="salePhotoCount">6</span> photos
            </div>
            <div class="absolute bottom-3 left-3 flex gap-1.5 md:gap-2">
                <div id="saleThumbs" class="flex gap-1.5 md:gap-2"></div>
            </div>
        </div>

        <div class="p-4 md:p-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">
                
                <!-- Left content -->
                <div class="lg:col-span-2">
                    <div class="flex items-center gap-2 mb-2">
                        <span id="saleBadge" class="bg-saleGold/10 text-saleGold px-3 py-1 rounded-full text-[10px] md:text-xs font-bold uppercase">For Sale</span>
                        <span id="saleFurnished" class="hidden bg-accentOrange/10 text-accentOrange px-3 py-1 rounded-full text-[10px] md:text-xs font-bold uppercase">
                            <i class="fas fa-couch mr-1"></i>Furnished
                        </span>
                    </div>

                    <h2 id="saleTitle" class="font-serif text-2xl md:text-4xl text-navy font-semibold mb-2">Property Title</h2>
                    
                    <div class="flex flex-wrap items-center gap-3 text-gray-500 text-xs md:text-sm mb-4">
                        <span class="flex items-center gap-1.5">
                            <i class="fas fa-map-marker-alt text-saleGold"></i>
                            <span id="saleLocation">Location</span>
                        </span>
                        <span class="flex items-center gap-1">
                            <i class="fas fa-star text-accentOrange text-[10px]"></i>
                            <span id="saleRating" class="font-semibold text-gray-700">4.8</span>
                            <span id="saleReviews" class="text-gray-400">(64 reviews)</span>
                        </span>
                    </div>

                    <!-- Specs -->
                    <div class="grid grid-cols-4 gap-2 md:gap-3 mb-5 p-3 md:p-4 bg-gray-50 rounded-xl">
                        <div class="text-center">
                            <i class="fas fa-bed text-saleGold text-sm md:text-lg mb-1"></i>
                            <p class="text-[9px] md:text-[10px] uppercase text-gray-400 font-bold">Bedrooms</p>
                            <p id="saleBeds" class="text-xs md:text-sm font-bold text-navy">3</p>
                        </div>
                        <div class="text-center border-l border-gray-200">
                            <i class="fas fa-bath text-saleGold text-sm md:text-lg mb-1"></i>
                            <p class="text-[9px] md:text-[10px] uppercase text-gray-400 font-bold">Bathrooms</p>
                            <p id="saleBaths" class="text-xs md:text-sm font-bold text-navy">2</p>
                        </div>
                        <div class="text-center border-l border-gray-200">
                            <i class="fas fa-car text-saleGold text-sm md:text-lg mb-1"></i>
                            <p class="text-[9px] md:text-[10px] uppercase text-gray-400 font-bold">Parking</p>
                            <p id="saleParking" class="text-xs md:text-sm font-bold text-navy">2</p>
                        </div>
                        <div class="text-center border-l border-gray-200">
                            <i class="fas fa-ruler-combined text-saleGold text-sm md:text-lg mb-1"></i>
                            <p class="text-[9px] md:text-[10px] uppercase text-gray-400 font-bold">Size</p>
                            <p class="text-xs md:text-sm font-bold text-navy"><span id="saleSize">1,800</span> sqft</p>
                        </div>
                    </div>

                    <!-- Agent -->
                    <div class="flex items-center gap-3 mb-5 p-3 bg-gray-50 rounded-xl">
                        <div class="w-11 h-11 rounded-full bg-gradient-to-br from-saleGold to-amber-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                            <span id="saleAgentInitial">J</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[10px] uppercase text-gray-400 font-bold">Listed by</p>
                            <p id="saleAgentName" class="text-sm font-semibold text-navy truncate">Jane M.</p>
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
                        <p id="saleDesc" class="text-gray-600 text-xs md:text-sm leading-relaxed"></p>
                    </div>

                    <!-- Amenities -->
                    <div class="mb-5">
                        <h3 class="font-serif text-lg md:text-xl text-navy font-semibold mb-2.5">
                            <i class="fas fa-concierge-bell text-saleGold mr-1.5 text-sm"></i> Amenities & Features
                        </h3>
                        <div id="saleAmenities" class="grid grid-cols-2 gap-2"></div>
                    </div>

                    <!-- Title deed -->
                    <div class="mb-5">
                        <h3 class="font-serif text-lg md:text-xl text-navy font-semibold mb-2">
                            <i class="fas fa-file-contract text-saleGold mr-1.5 text-sm"></i> Title & Ownership
                        </h3>
                        <div class="grid grid-cols-2 gap-2 text-xs text-gray-600">
                            <div class="flex items-center gap-1.5"><i class="fas fa-check text-emerald-500 text-[10px]"></i> Title Deed Verified</div>
                            <div class="flex items-center gap-1.5"><i class="fas fa-check text-emerald-500 text-[10px]"></i> Freehold Title</div>
                            <div class="flex items-center gap-1.5"><i class="fas fa-check text-emerald-500 text-[10px]"></i> Agent Verified</div>
                            <div class="flex items-center gap-1.5"><i class="fas fa-check text-emerald-500 text-[10px]"></i> Ready for Transfer</div>
                        </div>
                    </div>

                    <!-- Google Map -->
                    <div class="mb-5">
                        <h3 class="font-serif text-lg md:text-xl text-navy font-semibold mb-2.5">
                            <i class="fas fa-map-marker-alt text-saleGold mr-1.5 text-sm"></i> Property Location
                        </h3>
                        <div id="saleMap" class="w-full h-64 rounded-xl overflow-hidden"></div>
                    </div>
                </div>

                <!-- Right: Enquiry Card -->
                <div class="lg:col-span-1">
                    <div class="lg:sticky lg:top-4 bg-white border border-gray-200 rounded-2xl p-4 md:p-5 shadow-xl">
                        <div class="mb-4">
                            <span id="salePrice" class="text-2xl md:text-3xl font-bold text-navy">KSh 15,000,000</span>
                        </div>

                        <div class="flex items-center gap-2 mb-4 p-2 bg-gray-50 rounded-lg">
                            <i class="fas fa-star text-accentOrange text-xs"></i>
                            <span class="text-sm font-semibold text-navy" id="saleRating2">4.8</span>
                            <span class="text-[10px] text-gray-400">·</span>
                            <span class="text-[10px] text-gray-500" id="saleReviews2">64 reviews</span>
                        </div>

                        <div class="space-y-2.5 mb-4">
                            <div class="border border-gray-200 rounded-lg px-2.5 py-2">
                                <label class="block text-[9px] uppercase text-gray-400 font-bold mb-0.5">Viewing Date</label>
                                <input type="date" class="w-full text-xs focus:outline-none bg-transparent">
                            </div>
                            <div class="border border-gray-200 rounded-lg px-2.5 py-2">
                                <label class="block text-[9px] uppercase text-gray-400 font-bold mb-0.5">Title Type</label>
                                <select class="w-full text-xs focus:outline-none bg-transparent">
                                    <option>Freehold</option>
                                    <option>Leasehold</option>
                                </select>
                            </div>
                        </div>

                        <div class="space-y-1.5 py-3 border-t border-gray-100 text-xs">
                            <div class="flex justify-between text-gray-600">
                                <span>Sale price</span>
                                <span id="saleRentLine">KSh 15,000,000</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Agent fee</span>
                                <span>KSh 0</span>
                            </div>
                        </div>

                        <button class="w-full py-3 bg-gradient-to-r from-saleGold to-amber-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-saleGold/30 hover:shadow-xl hover:shadow-saleGold/40 transition-all mt-3">
                            <i class="fas fa-calendar-check mr-2"></i> Schedule Viewing
                        </button>
                        <button onclick="getSaleDirections()" id="saleDirectionsBtn" class="w-full py-2.5 border border-saleGold text-saleGold text-sm font-semibold rounded-xl hover:bg-saleGold hover:text-white transition-colors mt-2 hidden">
                            <i class="fas fa-route mr-1.5"></i> Get Directions
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

<!-- ================= SCRIPT ================= -->
<script>
/* ============================================================
   DATA — sale properties from database
   ============================================================ */
const salesData = <?php echo json_encode($sales); ?>;

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

/* Map state */
let saleMap = null;
let saleMarker = null;
let saleUserMarker = null;
let watchIdSale = null;
let currentSaleModalLat = null;
let currentSaleModalLng = null;

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
                    security: 'fa-shield-alt', garden: 'fa-tree', freehold: 'fa-file-contract'
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

    grid.innerHTML = paged.map(s => {
        const badgeColor = s.type === 'villa' ? 'bg-saleGold'
                         : s.type === 'house' ? 'bg-navy'
                         : s.type === 'townhouse' ? 'bg-indigo-600'
                         : s.type === 'land' ? 'bg-emerald-600'
                         : 'bg-saleGold';

        return `
            <article class="sale-card anim-fade-up group" onclick="openSaleModal('${s.id}')">
                <div class="relative h-52 overflow-hidden">
                    <img src="${s.images[0]}" alt="${s.title}" class="sale-img w-full h-full object-cover">
                    <span class="badge-sale ${badgeColor}">${s.badge}</span>
                    ${s.furnished ? `<span class="absolute top-3 left-3 mt-7 bg-accentOrange text-white text-[9px] font-bold px-2 py-0.5 rounded-full"><i class="fas fa-couch mr-0.5"></i>Furnished</span>` : ''}
                    <button onclick="toggleFavorite(event, this)" class="favorite-btn" aria-label="Save">
                        <i class="far fa-heart"></i>
                    </button>
                </div>
                <div class="p-3.5">
                    <div class="flex items-start justify-between gap-2 mb-1">
                        <h3 class="font-serif text-base md:text-lg text-navy font-semibold leading-tight line-clamp-1">${s.title}</h3>
                        <div class="flex items-center gap-1 flex-shrink-0">
                            <i class="fas fa-star text-accentOrange text-[10px]"></i>
                            <span class="text-xs font-semibold text-navy">${s.rating}</span>
                        </div>
                    </div>
                    <p class="text-[11px] text-gray-500 mb-2 line-clamp-1">
                        <i class="fas fa-map-marker-alt text-saleGold text-[9px] mr-1"></i>${s.location}
                    </p>
                    <div class="flex items-center gap-2 text-[10px] text-gray-400 mb-2.5">
                        <span><i class="fas fa-bed mr-0.5"></i>${s.beds} Beds</span>
                        <span>·</span>
                        <span><i class="fas fa-bath mr-0.5"></i>${s.baths} Baths</span>
                        <span>·</span>
                        <span><i class="fas fa-car mr-0.5"></i>${s.parking}</span>
                    </div>
                    <div class="flex items-end justify-between pt-2.5 border-t border-gray-100">
                        <div>
                            <span class="text-base md:text-lg font-bold text-navy">KSh ${s.price.toLocaleString()}</span>
                            <span class="text-[10px] text-gray-400">/ sale</span>
                        </div>
                        <span class="text-[10px] text-gray-400">${s.reviews} reviews</span>
                    </div>
                </div>
            </article>
        `;
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

document.getElementById('furnishedToggle').addEventListener('change', function() {
    currentFilters.furnished = this.checked;
    currentPage = 1;
    renderSales();
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
    renderSales();
}

function quickFilter(tag) {
    currentFilters.quickTag = tag;
    currentPage = 1;
    renderSales();
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
    renderSales();
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
function removeFurnished() {
    currentFilters.furnished = false;
    document.getElementById('furnishedToggle').checked = false;
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
   LEAFLET + OSRM MAPS (free, no API key)
   ============================================================ */
function initSaleMap(lat, lng) {
    const mapEl = document.getElementById('saleMap');
    if (!lat || !lng) {
        mapEl.innerHTML = '<div class="w-full h-full flex flex-col items-center justify-center text-gray-400"><i class="fas fa-map-marker-alt text-2xl mb-2"></i><p class="text-xs">Location not available</p></div>';
        return;
    }
    mapEl.innerHTML = '';
    saleMap = L.map(mapEl, {
        center: [lat, lng],
        zoom: 15,
        zoomControl: true,
        attributionControl: false
    });
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(saleMap);
    saleMarker = L.marker([lat, lng]).addTo(saleMap).bindPopup('Property Location').openPopup();
}

function destroySaleMap() {
    if (watchIdSale !== null) {
        navigator.geolocation.clearWatch(watchIdSale);
        watchIdSale = null;
    }
    if (saleMap) {
        try { saleMap.remove(); } catch (e) {}
        saleMap = null;
    }
    saleMarker = null;
    saleUserMarker = null;
    const mapEl = document.getElementById('saleMap');
    mapEl.innerHTML = '';
}

/* ============================================================
   MODAL
   ============================================================ */
function openSaleModal(id) {
    const s = salesData.find(x => x.id === id);
    if (!s) return;
    modalSlideIndex = 0;

    document.getElementById('saleMainImg').src = s.images[0];
    document.getElementById('saleTitle').textContent = s.title;
    document.getElementById('saleLocation').textContent = s.location;
    document.getElementById('saleRating').textContent = s.rating;
    document.getElementById('saleReviews').textContent = `(${s.reviews} reviews)`;
    document.getElementById('saleRating2').textContent = s.rating;
    document.getElementById('saleReviews2').textContent = `${s.reviews} reviews`;
    document.getElementById('saleBeds').textContent = s.beds;
    document.getElementById('saleBaths').textContent = s.baths;
    document.getElementById('saleParking').textContent = s.parking;
    document.getElementById('saleSize').textContent = s.size;
    document.getElementById('saleDesc').textContent = s.desc;
    document.getElementById('salePrice').textContent = `KSh ${s.price.toLocaleString()}`;
    document.getElementById('saleAgentName').textContent = s.agentName;
    document.getElementById('saleAgentInitial').textContent = s.agentInitial;
    document.getElementById('salePhotoCount').textContent = s.images.length;
    document.getElementById('saleBadge').textContent = s.badge;
    document.getElementById('saleFurnished').classList.toggle('hidden', !s.furnished);

    // Price breakdown
    document.getElementById('saleRentLine').textContent = `KSh ${s.price.toLocaleString()}`;

    currentSaleModalLat = s.lat;
    currentSaleModalLng = s.lng;
    document.getElementById('saleDirectionsBtn').classList.toggle('hidden', !s.lat || !s.lng);

    // Amenities
    const amenContainer = document.getElementById('saleAmenities');
    amenContainer.innerHTML = s.amenities.map(a => `
        <div class="flex items-center gap-2 text-xs text-gray-600">
            <i class="fas ${a.icon} text-saleGold text-[11px] w-4 text-center"></i>
            <span>${a.label}</span>
        </div>
    `).join('');

    // Thumbnails
    const thumbContainer = document.getElementById('saleThumbs');
    thumbContainer.innerHTML = s.images.map((img, i) => `
        <button onclick="changeSaleImage(${i}, '${img}')" class="modal-thumb ${i === 0 ? 'ring-2 ring-white' : 'ring-1 ring-white/40'} w-10 h-10 md:w-12 md:h-12 rounded-lg overflow-hidden flex-shrink-0 transition-all hover:ring-2 hover:ring-white">
            <img src="${img}" alt="thumb" class="w-full h-full object-cover">
        </button>
    `).join('');

    const modal = document.getElementById('saleModal');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    requestAnimationFrame(() => {
        modal.classList.add('show');
        initSaleMap(s.lat, s.lng);
    });

    startSaleSlideshow(s.images);
}

function changeSaleImage(index, src) {
    modalSlideIndex = index;
    const main = document.getElementById('saleMainImg');
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

function startSaleSlideshow(images) {
    if (modalSlideInterval) clearInterval(modalSlideInterval);
    modalSlideInterval = setInterval(() => {
        if (document.getElementById('saleModal').classList.contains('hidden')) return;
        modalSlideIndex = (modalSlideIndex + 1) % images.length;
        const main = document.getElementById('saleMainImg');
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
    setTimeout(() => {
        modal.classList.add('hidden');
        destroySaleMap();
    }, 300);
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
   GET DIRECTIONS
   ============================================================ */
function getSaleDirections(lat, lng) {
    lat = lat !== undefined ? lat : currentSaleModalLat;
    lng = lng !== undefined ? lng : currentSaleModalLng;
    if (!lat || !lng) return;
    if (!navigator.geolocation) {
        const mapEl = document.getElementById('saleMap');
        mapEl.innerHTML = '<div class="w-full h-full flex flex-col items-center justify-center text-gray-400"><i class="fas fa-exclamation-triangle text-2xl mb-2"></i><p class="text-xs">Geolocation not supported</p></div>';
        return;
    }
    if (watchIdSale !== null) {
        navigator.geolocation.clearWatch(watchIdSale);
        watchIdSale = null;
    }

    navigator.geolocation.getCurrentPosition(
        function(position) {
            const userLat = position.coords.latitude;
            const userLng = position.coords.longitude;
            const url = `https://router.project-osrm.org/route/v1/driving/${userLng},${userLat};${lng},${lat}?overview=full&geometries=geojson`;
            fetch(url)
                .then(res => res.json())
                .then(data => {
                    if (data.code === 'Ok' && data.routes[0]) {
                        drawSaleRoute(userLat, userLng, lat, lng, data.routes[0].geometry.coordinates);
                    }
                });
        },
        function(error) {
            const mapEl = document.getElementById('saleMap');
            if (error.code === error.PERMISSION_DENIED) {
                mapEl.innerHTML = '<div class="w-full h-full flex flex-col items-center justify-center text-gray-400"><i class="fas fa-location-slash text-2xl mb-2"></i><p class="text-xs">Location permission denied</p></div>';
            } else {
                mapEl.innerHTML = '<div class="w-full h-full flex flex-col items-center justify-center text-gray-400"><i class="fas fa-exclamation-triangle text-2xl mb-2"></i><p class="text-xs">Unable to get your location</p></div>';
            }
        },
        { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
    );

    watchIdSale = navigator.geolocation.watchPosition(
        function(position) {
            const userLat = position.coords.latitude;
            const userLng = position.coords.longitude;
            if (saleUserMarker) {
                saleUserMarker.setLatLng([userLat, userLng]).update();
                saleMap.panTo([userLat, userLng]);
            } else {
                saleUserMarker = L.marker([userLat, userLng]).addTo(saleMap).bindPopup('Your Location');
            }
        },
        function(error) {
            // Silent on watch errors
        },
        { enableHighAccuracy: true, timeout: 10000, maximumAge: 5000 }
    );
}

function drawSaleRoute(userLat, userLng, propLat, propLng, coordinates) {
    const mapEl = document.getElementById('saleMap');

    if (saleMap) {
        saleMap.eachLayer(function(layer) {
            if (layer instanceof L.Marker || layer instanceof L.Polyline) {
                saleMap.removeLayer(layer);
            }
        });
    } else {
        mapEl.innerHTML = '';
        saleMap = L.map(mapEl, {
            center: [propLat, propLng],
            zoom: 13,
            zoomControl: false,
            attributionControl: false
        });
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(saleMap);
    }

    saleMarker = L.marker([propLat, propLng]).addTo(saleMap).bindPopup('Property Location').openPopup();
    saleUserMarker = L.marker([userLat, userLng]).addTo(saleMap).bindPopup('Your Location');

    const routeCoords = coordinates.map(c => [c[1], c[0]]);
    L.polyline(routeCoords, { color: '#B45309', weight: 4, opacity: 0.8 }).addTo(saleMap);

    const bounds = L.latLngBounds(routeCoords.concat([[userLat, userLng], [propLat, propLng]]));
    saleMap.fitBounds(bounds, { padding: [40, 40] });
}

/* ============================================================
   INIT
   ============================================================ */
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', renderSales);
} else {
    renderSales();
}
</script>