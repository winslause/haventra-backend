<?php
// airbnb.php - Airbnb & Short-Stay Accommodation page
// $pdo is already available from index.php

$page_title = 'Airbnb & Short-Stay Accommodation in Kenya | HAVENTRA';
$page_description = 'Book the best Airbnbs, holiday homes, serviced apartments, and short-stay rentals in Kenya. Discover beachfront villas, safari lodges, city suites, and more on HAVENTRA.';
$page_canonical = 'https://haventra.com/airbnb/';

// Fetch short-stay properties from database
$stays = [];
try {
    $stmt = $pdo->query("SELECT id,title,location,price,beds,baths,badge,description,amenities,image_url,image_urls,lat,lng FROM properties WHERE is_active = 1 AND type = 'booking' ORDER BY created_at DESC");
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
        elseif (strpos($titleLower, 'lodge') !== false) $propType = 'lodge';
        elseif (strpos($titleLower, 'cottage') !== false) $propType = 'cottage';
        elseif (strpos($titleLower, 'penthouse') !== false) $propType = 'penthouse';
        elseif (strpos($titleLower, 'apartment') !== false || strpos($titleLower, 'studio') !== false || strpos($titleLower, 'loft') !== false) $propType = 'apartment';
        else $propType = 'house';

        $stays[] = [
            'id' => 'stay-' . $row['id'],
            'title' => htmlspecialchars($row['title']),
            'location' => htmlspecialchars($row['location']),
            'price' => $priceNum,
            'rating' => 4.5,
            'reviews' => 0,
            'guests' => (int) max(2, $row['beds'] * 2),
            'beds' => (int) $row['beds'],
            'baths' => (int) $row['baths'],
            'rooms' => (int) max(1, $row['beds']),
            'type' => $propType,
            'badge' => htmlspecialchars($row['badge'] ?? 'Short Stay'),
            'superhost' => false,
            'tags' => [],
            'hostName' => 'HAVENTRA Host',
            'hostInitial' => 'H',
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
                    'full board' => 'fa-utensils', 'kitchenette' => 'fa-utensils',
                    'game drives' => 'fa-binoculars', 'spa services' => 'fa-spa',
                    'bar & lounge' => 'fa-wine-glass', 'smart tv' => 'fa-tv',
                    'ceiling fans' => 'fa-fan', 'mini bar' => 'fa-wine-glass'
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
    $stays = [];
}

// Build dynamic locations from database
$dbLocations = [];
foreach ($stays as $s) {
    $loc = $s['location'];
    if (!isset($dbLocations[$loc])) $dbLocations[$loc] = 0;
    $dbLocations[$loc]++;
}
arsort($dbLocations);
$topLocations = array_slice($dbLocations, 0, 12, true);

// Build dynamic property types from database
$dbTypes = [];
foreach ($stays as $s) {
    $t = $s['type'];
    if (!isset($dbTypes[$t])) {
        $dbTypes[$t] = 0;
    }
    $dbTypes[$t]++;
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
<link rel="alternate" href="https://haventra.com/airbnb/" hreflang="en">
<link rel="alternate" href="https://haventra.com/airbnb/" hreflang="en-ke">
<link rel="author" href="https://haventra.com/about/">

<!-- Schema.org: RealEstateAggregator for Short Stay -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "RealEstateAggregator",
    "name": "HAVENTRA Short Stay",
    "url": "https://haventra.com/airbnb/",
    "description": "Book the best Airbnbs, holiday homes, serviced apartments, and short-stay rentals in Kenya.",
    "address": {
        "@type": "PostalAddress",
        "addressLocality": "Nairobi",
        "addressRegion": "Nairobi County",
        "addressCountry": "KE"
    },
    "areaServed": [
        <?php
        $areaItems = [];
        foreach ($dbLocations as $loc => $count) {
            $areaItems[] = '{"@type": "City", "name": "' . addslashes($loc) . '"}';
        }
        echo implode(',', $areaItems);
        ?>
    ],
    "hasOfferCatalog": {
        "@type": "OfferCatalog",
        "name": "Short-Stay Properties",
        "itemListElement": [
            <?php
            $schemaItems = [];
            foreach ($stays as $idx => $s) {
                $schemaItems[] = '{
                    "@type": "RealEstateListing",
                    "position": ' . ($idx + 1) . ',
                    "itemOffered": {
                        "@type": "Apartment",
                        "name": "' . addslashes($s['title']) . '",
                        "description": "' . addslashes($s['desc']) . '",
                        "address": {
                            "@type": "PostalAddress",
                            "addressLocality": "' . addslashes($s['location']) . '",
                            "addressCountry": "KE"
                        },
                        "numberOfRooms": ' . $s['rooms'] . ',
                        "floorSize": {
                            "@type": "QuantitativeValue",
                            "value": "' . $s['price'] . '",
                            "unitText": "KES per night"
                        },
                        "amenityFeature": [
                            ' . implode(',', array_map(fn($a) => '{"@type":"AmenityFeature","name":"' . addslashes($a['label']) . '"}', $s['amenities'])) . ']
                    },
                    "priceSpecification": {
                        "@type": "UnitPriceSpecification",
                        "price": ' . $s['price'] . ',
                        "priceCurrency": "KES",
                        "pricingModel": "http://schema.org/NightlyRate"
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
            "name": "Airbnb & Short Stay",
            "item": "https://haventra.com/airbnb/"
        }
    ]
}
</script>

<!-- Schema.org: FAQ -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [
        {
            "@type": "Question",
            "name": "What types of short-stay properties are available?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "HAVENTRA offers villas, safari lodges, apartments, cottages, penthouses, and houses for short-stay booking across all locations in Kenya."
            }
        },
        {
            "@type": "Question",
            "name": "How do I book an Airbnb or short-stay property?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Select your check-in and check-out dates, choose the number of guests, and click Reserve Now to book your short-stay accommodation."
            }
        },
        {
            "@type": "Question",
            "name": "Can I filter short-stay properties by price and location?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Yes, you can filter short-stay properties by location, price per night, property type, amenities, guests, and Superhost status using the filter sidebar."
            }
        }
    ]
}
</script>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
tailwind.config = tailwind.config || {};
tailwind.config.theme = tailwind.config.theme || {};
tailwind.config.theme.extend = tailwind.config.theme.extend || {};
tailwind.config.theme.extend.colors = Object.assign({
    stayCoral: '#FF5A5F',
    stayTeal: '#0E7490',
    accentOrange: '#F59E0B',
    navy: '#00215E',
}, tailwind.config.theme.extend.colors || {});
</script>
<style>
    .text-stayCoral{color:#FF5A5F!important}.bg-stayCoral{background-color:#FF5A5F!important}.border-stayCoral{border-color:#FF5A5F!important}
    .text-stayTeal{color:#0E7490!important}.bg-stayTeal{background-color:#0E7490!important}
    .from-stayCoral{--tw-gradient-from:#FF5A5F var(--tw-gradient-from-position);--tw-gradient-to:rgb(255 90 95 / 0) var(--tw-gradient-to-position);--tw-gradient-stops:var(--tw-gradient-from),var(--tw-gradient-to)}
    .to-stayCoral{--tw-gradient-to:#FF5A5F var(--tw-gradient-to-position)}
    .ring-stayCoral{--tw-ring-color:#FF5A5F!important}
    .focus\:ring-stayCoral:focus{--tw-ring-color:#FF5A5F!important}
    .focus\:ring-stayCoral\/30:focus{--tw-ring-color:rgb(255 90 95 / 0.3)!important}
    .hover\:text-stayCoral:hover{color:#FF5A5F!important}
    .bg-stayCoral\/10{background-color:rgb(255 90 95 / 0.1)!important}
    .shadow-stayCoral\/30{--tw-shadow-color:rgb(255 90 95 / 0.3);--tw-shadow:var(--tw-shadow-colored)}
</style>

<style>
    * { -webkit-tap-highlight-color: transparent; }
    body { font-family: 'Plus Jakarta Sans', sans-serif; }

    /* ===== HERO SEARCH ===== */
    .stay-hero {
        background: linear-gradient(135deg, rgba(0, 21, 64, 0.92), rgba(14, 116, 144, 0.85)),
                    url('https://images.unsplash.com/photo-1499793983690-e29da59ef1c2?auto=format&fit=crop&w=1600&q=80');
        background-size: cover;
        background-position: center;
    }

    .search-shell {
        background: white;
        border-radius: 9999px;
        box-shadow: 0 20px 50px -12px rgba(0, 0, 0, 0.4);
        transition: all 0.3s ease;
    }
    @media (max-width: 767px) {
        .search-shell { border-radius: 24px; }
    }

    .search-field {
        transition: background 0.25s ease;
        border-radius: 9999px;
    }
    .search-field:hover { background: #F3F4F6; }
    .search-field.active { background: #F3F4F6; box-shadow: inset 0 0 0 2px #FF5A5F; }

    .search-input {
        background: transparent;
        border: none;
        outline: none;
        width: 100%;
        font-size: 0.85rem;
        color: #1F2937;
    }
    .search-input::placeholder { color: #9CA3AF; }

    /* ===== FILTER CHIPS ===== */
    .chip {
        padding: 6px 14px;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
        border: 1px solid #E5E7EB;
        background: white;
        color: #4B5563;
        transition: all 0.25s ease;
        cursor: pointer;
        white-space: nowrap;
    }
    .chip:hover { border-color: #FF5A5F; color: #FF5A5F; }
    .chip.active {
        background: #FF5A5F;
        border-color: #FF5A5F;
        color: white;
        box-shadow: 0 4px 12px rgba(255, 90, 95, 0.3);
    }

    /* ===== STAY CARD ===== */
    .stay-card {
        background: white;
        border-radius: 14px;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.35s cubic-bezier(0.25, 0.8, 0.25, 1);
        border: 1px solid #F1F5F9;
        box-shadow: 0 4px 10px -2px rgba(0, 0, 0, 0.04);
    }
    .stay-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 24px 40px -12px rgba(0, 0, 0, 0.18);
        border-color: rgba(255, 90, 95, 0.25);
    }
    .stay-card .stay-img {
        transition: transform 0.7s cubic-bezier(0.25, 0.8, 0.25, 1);
    }
    .stay-card:hover .stay-img { transform: scale(1.08); }

    .favorite-btn {
        position: absolute;
        top: 12px;
        right: 12px;
        width: 34px;
        height: 34px;
        border-radius: 9999px;
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #374151;
        font-size: 0.8rem;
        transition: all 0.25s ease;
        z-index: 5;
    }
    .favorite-btn:hover { background: white; transform: scale(1.1); }
    .favorite-btn.liked { color: #FF5A5F; }
    .favorite-btn.liked i { animation: heartPop 0.35s ease; }
    @keyframes heartPop {
        0% { transform: scale(1); }
        50% { transform: scale(1.4); }
        100% { transform: scale(1); }
    }

    .badge-stay {
        position: absolute;
        top: 12px;
        left: 12px;
        padding: 4px 12px;
        border-radius: 9999px;
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        color: white;
        z-index: 5;
    }

    /* ===== MODAL ===== */
    #stayModal {
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    #stayModal.show { opacity: 1; }
    #stayModal .modal-content {
        transform: scale(0.95) translateY(10px);
        transition: transform 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }
    #stayModal.show .modal-content { transform: scale(1) translateY(0); }

    /* ===== SCROLLBARS ===== */
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

    .thin-scroll::-webkit-scrollbar { width: 6px; }
    .thin-scroll::-webkit-scrollbar-track { background: transparent; }
    .thin-scroll::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 3px; }

    /* ===== ANIMATIONS ===== */
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(16px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .anim-fade-up { animation: fadeUp 0.5s ease both; }

    /* ===== MAP PLACEHOLDER ===== */
    .map-placeholder {
        background: linear-gradient(135deg, #E0F2FE, #F0FDFA);
        position: relative;
        overflow: hidden;
    }
    .map-placeholder::after {
        content: '';
        position: absolute;
        inset: 0;
        background-image: 
            linear-gradient(rgba(14, 116, 144, 0.08) 1px, transparent 1px),
            linear-gradient(90deg, rgba(14, 116, 144, 0.08) 1px, transparent 1px);
        background-size: 30px 30px;
    }

    /* ===== SEO BODY SECTION ===== */
    .seo-body-section {
        background: #FAFAFA;
        border-top: 1px solid #F1F5F9;
    }
    .seo-location-list a, .seo-type-list a {
        color: #FF5A5F;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .seo-location-list a:hover, .seo-type-list a:hover {
        color: #FF5A5F;
        text-decoration: underline;
    }
</style>

<!-- ================= SEO BODY SECTION ================= -->
<section class="seo-body-section py-8 md:py-12">
    <div class="max-w-7xl mx-auto px-4 md:px-6">
        <div class="mb-8 md:mb-10">
            <div>
                <h2 class="font-serif text-xl md:text-2xl text-navy font-semibold mb-3">Airbnb & Short-Stay Accommodation</h2>
                <p class="text-gray-600 text-xs md:text-sm leading-relaxed mb-3">
                    Searching for <strong>Airbnb Kenya</strong> or <strong>short stay rentals</strong>? HAVENTRA is your trusted marketplace for verified short-stay properties across <?php echo implode(', ', array_keys(array_slice($dbLocations, 0, 6))); ?> and more. Book your perfect stay today.
                </p>
                <p class="text-gray-600 text-xs md:text-sm leading-relaxed mb-3">
                    Our short-stay listings include beachfront villas, safari lodges, city suites, and cozy cottages with detailed information, high-quality photos, amenities, and exact location coordinates. Filter by <strong>price per night</strong>, <strong>guests</strong>, <strong>property type</strong>, and <strong>amenities</strong> to find your perfect stay.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 md:gap-6">
            <div>
                <h3 class="font-serif text-base md:text-lg text-navy font-semibold mb-2">Popular Airbnb Locations</h3>
                <ul class="space-y-1 text-xs md:text-sm text-gray-600 seo-location-list">
                    <?php foreach ($topLocations as $loc => $count): ?>
                    <li><a href="/haventra/airbnb/?search=<?php echo urlencode($loc); ?>" class="text-stayCoral hover:underline transition-colors"><?php echo htmlspecialchars($loc); ?> (<?php echo $count; ?> stays)</a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div>
                <h3 class="font-serif text-base md:text-lg text-navy font-semibold mb-2">Property Types for Stay</h3>
                <ul class="space-y-1 text-xs md:text-sm text-gray-600 seo-type-list">
                    <?php foreach ($dbTypes as $type => $count): ?>
                    <li><a href="/haventra/airbnb/?type=<?php echo urlencode($type); ?>" class="text-stayCoral hover:underline transition-colors"><?php echo ucfirst(htmlspecialchars($type)); ?> Stays (<?php echo $count; ?>)</a></li>
                    <?php endforeach; ?>
                    <li><a href="/haventra/airbnb/" class="text-stayCoral hover:underline transition-colors">Beachfront Stays</a></li>
                    <li><a href="/haventra/airbnb/" class="text-stayCoral hover:underline transition-colors">Safari Lodges</a></li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- ================= HERO WITH SEARCH ================= -->
<section class="stay-hero relative pt-10 pb-16 md:pt-12 md:pb-24 px-4">

    <!-- Hero heading -->
    <div class="max-w-4xl mx-auto text-center relative z-10 mb-6 md:mb-10">
        <span class="inline-block bg-white/15 backdrop-blur text-white text-[10px] md:text-xs font-bold uppercase tracking-[0.2em] px-4 py-1.5 rounded-full mb-4 border border-white/20">
            <i class="fas fa-umbrella-beach mr-1.5 text-accentOrange"></i> Short-Stay & Airbnb Collection
        </span>
        <h1 class="font-serif text-3xl md:text-6xl text-white font-semibold leading-tight mb-3 md:mb-4 drop-shadow-lg">
            Find your perfect <span class="italic text-accentOrange">escape</span>
        </h1>
        <p class="text-white/80 text-sm md:text-base max-w-2xl mx-auto">
            Beachfront villas, safari lodges, city suites, and cozy stays — book unique homes across Kenya and beyond.
        </p>
    </div>

    <!-- ===== SEARCH BAR ===== -->
    <div class="max-w-2xl mx-auto relative z-20">
        <form onsubmit="handleSearch(event)" class="search-shell p-1.5 md:p-2 flex items-center gap-1">
            
            <!-- Location -->
            <div class="search-field flex-1 px-3 py-2 md:py-2.5 cursor-pointer" onclick="activateField(this)">
                <div class="flex items-center gap-2">
                    <i class="fas fa-map-marker-alt text-stayCoral text-xs"></i>
                    <input type="text" id="searchLocation" placeholder="Search by location" class="search-input font-medium">
                </div>
            </div>

            <!-- Search button -->
            <button type="submit" class="bg-gradient-to-r from-stayCoral to-orange-500 text-white rounded-full px-5 py-2.5 md:px-6 md:py-3 flex items-center justify-center gap-2 font-semibold text-sm shadow-lg shadow-stayCoral/30 hover:shadow-xl hover:shadow-stayCoral/40 transition-all duration-300">
                <i class="fas fa-search text-xs"></i>
                <span class="hidden sm:inline">Search</span>
            </button>
        </form>

        <!-- Quick filter chips -->
        <div class="flex flex-wrap items-center justify-center gap-2 mt-4 md:mt-5">
            <span class="text-white/60 text-[10px] md:text-xs font-semibold uppercase tracking-wide mr-1">Popular:</span>
            <button onclick="quickFilter('Beachfront')" class="chip !bg-white/15 !border-white/20 !text-white hover:!bg-white/25">🏖️ Beachfront</button>
            <button onclick="quickFilter('Safari')" class="chip !bg-white/15 !border-white/20 !text-white hover:!bg-white/25">🦁 Safari</button>
            <button onclick="quickFilter('City')" class="chip !bg-white/15 !border-white/20 !text-white hover:!bg-white/25">🏙️ City</button>
            <button onclick="quickFilter('Luxury')" class="chip !bg-white/15 !border-white/20 !text-white hover:!bg-white/25">✨ Luxury</button>
            <button onclick="quickFilter('Family')" class="chip !bg-white/15 !border-white/20 !text-white hover:!bg-white/25">👨‍👩‍👧 Family</button>
        </div>
    </div>
</section>


<!-- ================= MAIN CONTENT: FILTERS + LISTINGS ================= -->
<section class="relative -mt-8 md:-mt-12 pb-16 px-4 md:px-6">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col lg:flex-row gap-6">

                <!-- ===== LEFT: FILTER SIDEBAR (25%) ===== -->
                <aside class="lg:w-1/4 w-full">
                    <div class="lg:sticky lg:top-6 space-y-4">

                        <!-- Filter Card -->
                    <div class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100">
                        <button onclick="toggleMobileFilters()" class="lg:hidden w-full flex items-center justify-between p-4">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-filter text-stayCoral"></i>
                                <h3 class="font-serif text-base text-navy font-semibold">Filters</h3>
                            </div>
                            <i class="fas fa-chevron-down text-gray-400 transition-transform" id="mobileFilterChevron"></i>
                        </button>
                        <div id="filterContent" class="hidden lg:block px-5 pb-5 pt-0 lg:pt-5">
                            <div class="hidden lg:flex items-center justify-between mb-4">
                                <h3 class="font-serif text-lg text-navy font-semibold">Filters</h3>
                                <button onclick="resetAllFilters()" class="text-[10px] text-stayCoral hover:text-orange-600 font-semibold underline transition-colors">
                                    Reset all
                                </button>
                            </div>

                            <!-- Property Type -->
                            <div class="mb-5">
                                <h4 class="text-[11px] uppercase tracking-wider font-bold text-gray-500 mb-2.5">Stay Type</h4>
                                <div class="space-y-2" id="typeFilters">
                                    <?php
                                    $typeOptions = [
                                        'villa' => 'Villa',
                                        'apartment' => 'Apartment',
                                        'lodge' => 'Safari Lodge',
                                        'cottage' => 'Cottage',
                                        'penthouse' => 'Penthouse',
                                        'house' => 'House'
                                    ];
                                    foreach ($typeOptions as $val => $label):
                                    ?>
                                    <label class="flex items-center gap-2.5 cursor-pointer group">
                                        <input type="checkbox" value="<?php echo $val; ?>" class="stay-filter-checkbox w-4 h-4 rounded border-gray-300 text-stayCoral focus:ring-stayCoral">
                                        <span class="text-xs text-gray-600 group-hover:text-stayCoral transition-colors"><?php echo $label; ?></span>
                                    </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Price Range -->
                            <div class="mb-5">
                                <h4 class="text-[11px] uppercase tracking-wider font-bold text-gray-500 mb-2.5">Price per Night</h4>
                                <div class="flex items-center gap-2">
                                    <input type="number" id="minPrice" placeholder="Min" class="w-full px-2.5 py-1.5 border border-gray-200 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-stayCoral/30">
                                    <span class="text-gray-400 text-xs">—</span>
                                    <input type="number" id="maxPrice" placeholder="Max" class="w-full px-2.5 py-1.5 border border-gray-200 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-stayCoral/30">
                                </div>
                                <p class="text-[10px] text-gray-400 mt-1.5">KSh per night</p>
                            </div>

                            <!-- Guests -->
                            <div class="mb-5">
                                <h4 class="text-[11px] uppercase tracking-wider font-bold text-gray-500 mb-2.5">Guests</h4>
                                <div class="flex flex-wrap gap-1.5" id="guestFilters">
                                    <button onclick="toggleGuestFilter(this, '1')" class="chip !text-[10px] !py-1 !px-3">1</button>
                                    <button onclick="toggleGuestFilter(this, '2')" class="chip !text-[10px] !py-1 !px-3">2</button>
                                    <button onclick="toggleGuestFilter(this, '3')" class="chip !text-[10px] !py-1 !px-3">3</button>
                                    <button onclick="toggleGuestFilter(this, '4')" class="chip !text-[10px] !py-1 !px-3">4</button>
                                    <button onclick="toggleGuestFilter(this, '5+')" class="chip !text-[10px] !py-1 !px-3">5+</button>
                                </div>
                            </div>

                            <!-- Amenities -->
                            <div class="mb-5">
                                <h4 class="text-[11px] uppercase tracking-wider font-bold text-gray-500 mb-2.5">Amenities</h4>
                                <div class="space-y-2">
                                    <label class="flex items-center gap-2.5 cursor-pointer group">
                                        <input type="checkbox" value="wifi" class="stay-filter-checkbox amenity-checkbox w-4 h-4 rounded border-gray-300 text-stayCoral focus:ring-stayCoral">
                                        <span class="text-xs text-gray-600 group-hover:text-stayCoral transition-colors"><i class="fas fa-wifi text-[10px] text-gray-400 mr-1"></i> WiFi</span>
                                    </label>
                                    <label class="flex items-center gap-2.5 cursor-pointer group">
                                        <input type="checkbox" value="pool" class="stay-filter-checkbox amenity-checkbox w-4 h-4 rounded border-gray-300 text-stayCoral focus:ring-stayCoral">
                                        <span class="text-xs text-gray-600 group-hover:text-stayCoral transition-colors"><i class="fas fa-swimming-pool text-[10px] text-gray-400 mr-1"></i> Pool</span>
                                    </label>
                                    <label class="flex items-center gap-2.5 cursor-pointer group">
                                        <input type="checkbox" value="ac" class="stay-filter-checkbox amenity-checkbox w-4 h-4 rounded border-gray-300 text-stayCoral focus:ring-stayCoral">
                                        <span class="text-xs text-gray-600 group-hover:text-stayCoral transition-colors"><i class="fas fa-snowflake text-[10px] text-gray-400 mr-1"></i> Air Conditioning</span>
                                    </label>
                                    <label class="flex items-center gap-2.5 cursor-pointer group">
                                        <input type="checkbox" value="kitchen" class="stay-filter-checkbox amenity-checkbox w-4 h-4 rounded border-gray-300 text-stayCoral focus:ring-stayCoral">
                                        <span class="text-xs text-gray-600 group-hover:text-stayCoral transition-colors"><i class="fas fa-utensils text-[10px] text-gray-400 mr-1"></i> Kitchen</span>
                                    </label>
                                    <label class="flex items-center gap-2.5 cursor-pointer group">
                                        <input type="checkbox" value="parking" class="stay-filter-checkbox amenity-checkbox w-4 h-4 rounded border-gray-300 text-stayCoral focus:ring-stayCoral">
                                        <span class="text-xs text-gray-600 group-hover:text-stayCoral transition-colors"><i class="fas fa-parking text-[10px] text-gray-400 mr-1"></i> Free Parking</span>
                                    </label>
                                    <label class="flex items-center gap-2.5 cursor-pointer group">
                                        <input type="checkbox" value="beach" class="stay-filter-checkbox amenity-checkbox w-4 h-4 rounded border-gray-300 text-stayCoral focus:ring-stayCoral">
                                        <span class="text-xs text-gray-600 group-hover:text-stayCoral transition-colors"><i class="fas fa-umbrella-beach text-[10px] text-gray-400 mr-1"></i> Beach Access</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Superhost toggle -->
                            <div class="pt-3 border-t border-gray-100">
                                <label class="flex items-center justify-between cursor-pointer">
                                    <span class="text-xs text-gray-600 font-medium">
                                        <i class="fas fa-award text-accentOrange mr-1"></i> Superhost only
                                    </span>
                                    <div class="relative">
                                        <input type="checkbox" id="superhostToggle" class="sr-only peer">
                                        <div class="w-9 h-5 bg-gray-200 rounded-full peer-checked:bg-stayCoral transition-colors"></div>
                                        <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow peer-checked:translate-x-4 transition-transform"></div>
                                    </div>
                                </label>
                            </div>
                            </div>

                        <!-- Map teaser -->
                        <div class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 p-4 hidden lg:block">
                            <h3 class="font-serif text-sm text-navy font-semibold mb-2.5 flex items-center gap-1.5">
                                <i class="fas fa-map-marked-alt text-stayTeal text-xs"></i> Explore on Map
                            </h3>
                            <div class="map-placeholder rounded-xl h-32 flex items-center justify-center">
                                <div class="relative z-10 text-center">
                                    <i class="fas fa-map-marker-alt text-stayCoral text-2xl mb-1 animate-bounce"></i>
                                    <p class="text-[10px] text-stayTeal font-semibold">View stays on map</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </aside>

                <!-- ===== RIGHT: LISTINGS (75%) ===== -->
                <div class="lg:w-3/4 w-full">

                    <!-- Results header -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
                        <div>
                            <h2 class="font-serif text-2xl md:text-3xl text-navy font-semibold">
                                <span id="resultsCount"><?php echo count($stays); ?></span> stays available
                            </h2>
                            <p class="text-xs text-gray-500 mt-0.5" id="filterSummary"></p>
                        </div>
                        <div class="flex items-center gap-2">
                            <!-- Sort -->
                            <div class="relative">
                                <select id="sortSelect" onchange="applySort()" class="appearance-none bg-white border border-gray-200 rounded-lg pl-3 pr-8 py-2 text-xs font-medium text-gray-700 cursor-pointer focus:outline-none focus:ring-2 focus:ring-stayCoral/30">
                                    <option value="recommended">Recommended</option>
                                    <option value="price-low">Price: Low to High</option>
                                    <option value="price-high">Price: High to Low</option>
                                    <option value="rating">Top Rated</option>
                                </select>
                                <i class="fas fa-chevron-down absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] pointer-events-none"></i>
                            </div>
                        </div>
                    </div>

                <!-- Active filters display -->
                <div id="activeFilters" class="flex flex-wrap gap-1.5 mb-4 hidden"></div>

                <!-- Listings Grid -->
                <div id="staysGrid" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 md:gap-5">
                    <?php foreach ($stays as $s): 
                        if ($s['badge'] === 'Safari') $badgeColor = 'bg-amber-600';
elseif ($s['badge'] === 'City') $badgeColor = 'bg-navy';
elseif ($s['badge'] === 'Beach') $badgeColor = 'bg-cyan-600';
elseif ($s['badge'] === 'Budget') $badgeColor = 'bg-emerald-600';
else $badgeColor = 'bg-stayCoral';
                    ?>
                    <article class="stay-card anim-fade-up group" onclick="openStayModal('<?php echo $s['id']; ?>')">
                        <div class="relative h-40 sm:h-52 overflow-hidden">
                            <img src="<?php echo $s['images'][0]; ?>" alt="<?php echo $s['title']; ?>" loading="lazy" decoding="async" class="stay-img w-full h-full object-cover">
                            <span class="badge-stay <?php echo $badgeColor; ?>"><?php echo $s['badge']; ?></span>
                            <?php if ($s['superhost']): ?>
                            <span class="absolute top-3 left-3 mt-7 bg-accentOrange text-white text-[9px] font-bold px-2 py-0.5 rounded-full"><i class="fas fa-award mr-0.5"></i>Superhost</span>
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
                                <i class="fas fa-map-marker-alt text-stayCoral text-[9px] mr-1"></i><?php echo $s['location']; ?>
                            </p>
                            <div class="flex items-center gap-2 text-[10px] text-gray-400 mb-2.5">
                                <span><i class="fas fa-user-friends mr-0.5"></i><?php echo $s['guests']; ?></span>
                                <span>·</span>
                                <span><i class="fas fa-bed mr-0.5"></i><?php echo $s['beds']; ?></span>
                                <span>·</span>
                                <span><i class="fas fa-bath mr-0.5"></i><?php echo $s['baths']; ?></span>
                            </div>
                            <div class="flex items-end justify-between pt-2.5 border-t border-gray-100">
                                <div>
                                    <span class="text-base md:text-lg font-bold text-navy">KSh <?php echo number_format($s['price']); ?></span>
                                    <span class="text-[10px] text-gray-400">/ night</span>
                                </div>
                                <span class="text-[10px] text-gray-400"><?php echo $s['reviews']; ?> reviews</span>
                            </div>
                        </div>
                    </article>
                    <?php endforeach; ?>
                    <?php if (empty($stays)): ?>
                    <div class="col-span-full text-center py-20">
                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-search text-3xl text-gray-300"></i>
                        </div>
                        <h3 class="font-serif text-xl text-navy font-semibold mb-2">No stays found</h3>
                        <p class="text-sm text-gray-500 mb-4">Try adjusting your filters or search criteria.</p>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Pagination -->
                <div class="flex items-center justify-center gap-1.5 mt-8" id="pagination">
                    <!-- injected via JS -->
                </div>

            </div>
        </div>
    </div>
</section>


<!-- ================= PROPERTY DETAIL MODAL ================= -->
<div id="stayModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-[3000] hidden items-center justify-center p-2 md:p-4">
    <div class="modal-content bg-white max-w-5xl w-full max-h-[95vh] md:max-h-[90vh] overflow-y-auto thin-scroll rounded-2xl md:rounded-3xl shadow-2xl relative">
        
        <!-- Close -->
        <button onclick="closeStayModal()" class="sticky top-3 float-right mr-3 ml-auto w-9 h-9 bg-white/90 backdrop-blur rounded-full shadow-lg flex items-center justify-center hover:bg-stayCoral hover:text-white transition-all z-50 border border-gray-200">
            <i class="fas fa-times text-sm"></i>
        </button>

        <!-- Gallery -->
        <div class="relative">
            <div class="h-[240px] md:h-[420px] overflow-hidden rounded-t-2xl md:rounded-t-3xl">
                <img id="modalMainImg" src="" alt="Stay" class="w-full h-full object-cover transition-opacity duration-500">
            </div>
            <div class="absolute bottom-3 right-3 bg-black/60 backdrop-blur text-white text-[10px] md:text-xs font-semibold px-3 py-1.5 rounded-full flex items-center gap-1.5">
                <i class="fas fa-camera"></i> <span id="modalPhotoCount">6</span> photos
            </div>
            <!-- Thumbnails -->
            <div class="absolute bottom-3 left-3 flex gap-1.5 md:gap-2">
                <div id="modalThumbs" class="flex gap-1.5 md:gap-2"></div>
            </div>
        </div>

        <div class="p-4 md:p-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">
                
                <!-- Left content -->
                <div class="lg:col-span-2">
                    <div class="flex items-center gap-2 mb-2">
                        <span id="modalBadge" class="bg-stayCoral/10 text-stayCoral px-3 py-1 rounded-full text-[10px] md:text-xs font-bold uppercase">Short Stay</span>
                        <span id="modalSuperhost" class="hidden bg-accentOrange/10 text-accentOrange px-3 py-1 rounded-full text-[10px] md:text-xs font-bold uppercase">
                            <i class="fas fa-award mr-1"></i>Superhost
                        </span>
                    </div>

                    <h2 id="modalTitle" class="font-serif text-2xl md:text-4xl text-navy font-semibold mb-2">Stay Title</h2>
                    
                    <div class="flex flex-wrap items-center gap-3 text-gray-500 text-xs md:text-sm mb-4">
                        <span class="flex items-center gap-1.5">
                            <i class="fas fa-map-marker-alt text-stayCoral"></i>
                            <span id="modalLocation">Location</span>
                        </span>
                        <span class="flex items-center gap-1">
                            <i class="fas fa-star text-accentOrange text-[10px]"></i>
                            <span id="modalRating" class="font-semibold text-gray-700">4.9</span>
                            <span id="modalReviews" class="text-gray-400">(128 reviews)</span>
                        </span>
                    </div>

                    <!-- Specs -->
                    <div class="grid grid-cols-4 gap-2 md:gap-3 mb-5 p-3 md:p-4 bg-gray-50 rounded-xl">
                        <div class="text-center">
                            <i class="fas fa-user-friends text-stayTeal text-sm md:text-lg mb-1"></i>
                            <p class="text-[9px] md:text-[10px] uppercase text-gray-400 font-bold">Guests</p>
                            <p id="modalGuests" class="text-xs md:text-sm font-bold text-navy">4</p>
                        </div>
                        <div class="text-center border-l border-gray-200">
                            <i class="fas fa-bed text-stayTeal text-sm md:text-lg mb-1"></i>
                            <p class="text-[9px] md:text-[10px] uppercase text-gray-400 font-bold">Beds</p>
                            <p id="modalBeds" class="text-xs md:text-sm font-bold text-navy">2</p>
                        </div>
                        <div class="text-center border-l border-gray-200">
                            <i class="fas fa-bath text-stayTeal text-sm md:text-lg mb-1"></i>
                            <p class="text-[9px] md:text-[10px] uppercase text-gray-400 font-bold">Baths</p>
                            <p id="modalBaths" class="text-xs md:text-sm font-bold text-navy">2</p>
                        </div>
                        <div class="text-center border-l border-gray-200">
                            <i class="fas fa-door-open text-stayTeal text-sm md:text-lg mb-1"></i>
                            <p class="text-[9px] md:text-[10px] uppercase text-gray-400 font-bold">Rooms</p>
                            <p id="modalRooms" class="text-xs md:text-sm font-bold text-navy">2</p>
                        </div>
                    </div>

                    <!-- Property Location Map -->
                    <div class="mb-5">
                        <h3 class="font-serif text-lg md:text-xl text-navy font-semibold mb-2">
                            <i class="fas fa-map-marker-alt text-stayCoral mr-1.5 text-sm"></i> Property Location
                        </h3>
                        <div id="stayMap" class="w-full h-48 rounded-xl border border-gray-200 bg-gray-50 flex items-center justify-center">
                            <div class="text-gray-400 text-xs"><i class="fas fa-map-marker-alt text-2xl mb-2"></i><p>Loading map...</p></div>
                        </div>
                    </div>

                    <!-- Directions -->
                    <div class="mb-5">
                        <button onclick="getStayDirections()" class="w-full py-2.5 bg-gray-50 border border-gray-200 text-stayCoral text-sm font-semibold rounded-xl hover:bg-stayCoral/50 transition-colors flex items-center justify-center gap-2">
                            <i class="fas fa-route"></i> Get Directions to Property
                        </button>
                    </div>

                    <!-- Host -->
                    <div class="flex items-center gap-3 mb-5 p-3 bg-gray-50 rounded-xl">
                        <div class="w-11 h-11 rounded-full bg-gradient-to-br from-stayCoral to-orange-500 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                            <span id="modalHostInitial">J</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[10px] uppercase text-gray-400 font-bold">Hosted by</p>
                            <p id="modalHostName" class="text-sm font-semibold text-navy truncate">Jane M.</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] uppercase text-gray-400 font-bold">Response</p>
                            <p class="text-xs font-semibold text-emerald-600">Within 1 hour</p>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-5">
                        <h3 class="font-serif text-lg md:text-xl text-navy font-semibold mb-2">
                            <i class="fas fa-info-circle text-stayCoral mr-1.5 text-sm"></i> About this stay
                        </h3>
                        <p id="modalDesc" class="text-gray-600 text-xs md:text-sm leading-relaxed">
                            Description...
                        </p>
                    </div>

                    <!-- Amenities -->
                    <div class="mb-5">
                        <h3 class="font-serif text-lg md:text-xl text-navy font-semibold mb-2.5">
                            <i class="fas fa-concierge-bell text-stayCoral mr-1.5 text-sm"></i> What this place offers
                        </h3>
                        <div id="modalAmenities" class="grid grid-cols-2 gap-2">
                            <!-- injected -->
                        </div>
                    </div>

                    <!-- House rules -->
                    <div class="mb-5">
                        <h3 class="font-serif text-lg md:text-xl text-navy font-semibold mb-2">
                            <i class="fas fa-clipboard-list text-stayCoral mr-1.5 text-sm"></i> House Rules
                        </h3>
                        <div class="grid grid-cols-2 gap-2 text-xs text-gray-600">
                            <div class="flex items-center gap-1.5"><i class="fas fa-check text-emerald-500 text-[10px]"></i> Check-in: 2PM</div>
                            <div class="flex items-center gap-1.5"><i class="fas fa-check text-emerald-500 text-[10px]"></i> Check-out: 11AM</div>
                            <div class="flex items-center gap-1.5"><i class="fas fa-ban text-red-400 text-[10px]"></i> No smoking</div>
                            <div class="flex items-center gap-1.5"><i class="fas fa-ban text-red-400 text-[10px]"></i> No parties</div>
                        </div>
                    </div>
                </div>

                <!-- Right: Booking Card -->
                <div class="lg:col-span-1">
                    <div class="lg:sticky lg:top-4 bg-white border border-gray-200 rounded-2xl p-4 md:p-5 shadow-xl">
                        <div class="mb-4">
                            <span id="modalPrice" class="text-2xl md:text-3xl font-bold text-navy">KSh 25,000</span>
                            <span class="text-gray-500 text-xs md:text-sm">/ night</span>
                        </div>

                        <!-- Rating badge -->
                        <div class="flex items-center gap-2 mb-4 p-2 bg-gray-50 rounded-lg">
                            <i class="fas fa-star text-accentOrange text-xs"></i>
                            <span class="text-sm font-semibold text-navy" id="modalRating2">4.9</span>
                            <span class="text-[10px] text-gray-400">·</span>
                            <span class="text-[10px] text-gray-500" id="modalReviews2">128 reviews</span>
                        </div>

                        <!-- Booking fields -->
                        <div class="space-y-2.5 mb-4">
                            <div class="grid grid-cols-2 gap-2">
                                <div class="border border-gray-200 rounded-lg px-2.5 py-2">
                                    <label class="block text-[9px] uppercase text-gray-400 font-bold mb-0.5">Check-in</label>
                                    <input type="date" id="modalCheckIn" class="w-full text-xs focus:outline-none bg-transparent">
                                </div>
                                <div class="border border-gray-200 rounded-lg px-2.5 py-2">
                                    <label class="block text-[9px] uppercase text-gray-400 font-bold mb-0.5">Check-out</label>
                                    <input type="date" id="modalCheckOut" class="w-full text-xs focus:outline-none bg-transparent">
                                </div>
                            </div>
                            <div class="border border-gray-200 rounded-lg px-2.5 py-2">
                                <label class="block text-[9px] uppercase text-gray-400 font-bold mb-0.5">Guests</label>
                                <select id="modalGuestsSelect" class="w-full text-xs focus:outline-none bg-transparent">
                                    <option>1 guest</option>
                                    <option selected>2 guests</option>
                                    <option>3 guests</option>
                                    <option>4 guests</option>
                                    <option>5 guests</option>
                                    <option>6+ guests</option>
                                </select>
                            </div>
                        </div>

                        <!-- Price breakdown -->
                        <div class="space-y-1.5 py-3 border-t border-gray-100 text-xs">
                            <div class="flex justify-between text-gray-600">
                                <span id="modalPriceLabel">KSh 25,000 × 2 nights</span>
                                <span id="modalSubtotal">KSh 50,000</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Service fee</span>
                                <span>KSh 3,500</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Cleaning fee</span>
                                <span>KSh 2,000</span>
                            </div>
                            <div class="flex justify-between font-bold text-navy pt-2 border-t border-gray-100 text-sm">
                                <span>Total</span>
                                <span id="modalTotal">KSh 55,500</span>
                            </div>
                        </div>

                        <!-- Book button -->
                        <button class="w-full py-3 bg-gradient-to-r from-stayCoral to-orange-500 text-white text-sm font-bold rounded-xl shadow-lg shadow-stayCoral/30 hover:shadow-xl hover:shadow-stayCoral/40 transition-all mt-3">
                            <i class="fas fa-calendar-check mr-2"></i> Reserve Now
                        </button>
                        <button class="w-full py-2.5 mt-2 border border-gray-200 text-gray-600 text-xs font-semibold rounded-xl hover:bg-gray-50 transition-colors">
                            <i class="fas fa-envelope mr-1.5"></i> Contact Host
                        </button>

                        <p class="text-center text-[10px] text-gray-400 mt-3">
                            <i class="fas fa-shield-alt mr-1"></i> You won't be charged yet
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
   STATE
   ============================================================ */
let staysData = <?php echo json_encode($stays); ?>;
let currentFilters = {
    types: [],
    amenities: [],
    minPrice: null,
    maxPrice: null,
    guests: null,
    superhost: false,
    search: '',
    quickTag: null
};
let currentSort = 'recommended';
let currentView = 'grid';
let currentPage = 1;
const PER_PAGE = 6;
let activeModalStayId = null;
let modalSlideInterval = null;
let modalSlideIndex = 0;
let stayMap = null;
let stayMarker = null;
let currentStayLat = null;
let currentStayLng = null;

/* ============================================================
   RENDER
   ============================================================ */
function getFilteredStays() {
    let results = [...staysData];

    // Type filter
    if (currentFilters.types.length > 0) {
        results = results.filter(s => currentFilters.types.includes(s.type));
    }

    // Amenities
    if (currentFilters.amenities.length > 0) {
        results = results.filter(s => {
            return currentFilters.amenities.every(amen => {
                const map = {
                    wifi: 'fa-wifi', pool: 'fa-swimming-pool', ac: 'fa-snowflake',
                    kitchen: 'fa-utensils', parking: 'fa-parking', beach: 'fa-umbrella-beach'
                };
                return s.amenities.some(a => a.icon === map[amen]);
            });
        });
    }

    // Price
    if (currentFilters.minPrice !== null) {
        results = results.filter(s => s.price >= currentFilters.minPrice);
    }
    if (currentFilters.maxPrice !== null) {
        results = results.filter(s => s.price <= currentFilters.maxPrice);
    }

    // Guests
    if (currentFilters.guests) {
        const g = currentFilters.guests;
        if (g === '5+') results = results.filter(s => s.guests >= 5);
        else results = results.filter(s => s.guests >= parseInt(g));
    }

    // Superhost
    if (currentFilters.superhost) {
        results = results.filter(s => s.superhost);
    }

    // Quick tag
    if (currentFilters.quickTag) {
        results = results.filter(s => s.tags.includes(currentFilters.quickTag.toLowerCase()));
    }

    // Search
    if (currentFilters.search.trim()) {
        const q = currentFilters.search.toLowerCase();
        results = results.filter(s =>
            s.title.toLowerCase().includes(q) ||
            s.location.toLowerCase().includes(q)
        );
    }

    // Sort
    if (currentSort === 'price-low') results.sort((a, b) => a.price - b.price);
    else if (currentSort === 'price-high') results.sort((a, b) => b.price - a.price);
    else if (currentSort === 'rating') results.sort((a, b) => b.rating - a.rating);

    return results;
}

function renderStays() {
    const grid = document.getElementById('staysGrid');
    const empty = document.getElementById('emptyState');
    const countEl = document.getElementById('resultsCount');
    const summaryEl = document.getElementById('filterSummary');
    const pagination = document.getElementById('pagination');
    const activeFiltersEl = document.getElementById('activeFilters');

    const filtered = getFilteredStays();
    countEl.textContent = filtered.length;

    let summaryParts = [];
    if (currentFilters.quickTag) summaryParts.push(currentFilters.quickTag);
    if (currentFilters.types.length) summaryParts.push(currentFilters.types.join(', '));
    if (currentFilters.amenities.length) summaryParts.push(currentFilters.amenities.length + ' amenities');
    if (currentFilters.minPrice || currentFilters.maxPrice) summaryParts.push('price filtered');
    if (currentFilters.guests) summaryParts.push(currentFilters.guests + ' guests');
    if (currentFilters.superhost) summaryParts.push('superhost');
    summaryEl.textContent = summaryParts.length > 0 ? 'Filtered by: ' + summaryParts.join(' · ') : '';

    let chips = [];
    if (currentFilters.quickTag) chips.push({label: '🏷️ ' + currentFilters.quickTag, fn: 'removeQuickTag()'});
    currentFilters.types.forEach(t => chips.push({label: t, fn: `removeType('${t}')`}));
    currentFilters.amenities.forEach(a => chips.push({label: a, fn: `removeAmenity('${a}')`}));
    if (currentFilters.guests) chips.push({label: currentFilters.guests + ' guests', fn: 'removeGuest()'});
    if (currentFilters.superhost) chips.push({label: 'Superhost', fn: 'removeSuperhost()'});
    if (currentFilters.minPrice || currentFilters.maxPrice) chips.push({label: 'Price', fn: 'removePrice()'});

    if (chips.length > 0) {
        activeFiltersEl.classList.remove('hidden');
        activeFiltersEl.innerHTML = chips.map(c =>
            `<button onclick="${c.fn}" class="inline-flex items-center gap-1 bg-stayCoral/10 text-stayCoral text-[10px] font-semibold px-2.5 py-1 rounded-full border border-stayCoral/20 hover:bg-stayCoral hover:text-white transition-colors">
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
        if (empty) empty.classList.remove('hidden');
        pagination.innerHTML = '';
        return;
    }
    grid.classList.remove('hidden');
    if (empty) empty.classList.add('hidden');

    grid.className = currentView === 'grid'
        ? 'grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 md:gap-5'
        : 'flex flex-col gap-3';

    grid.innerHTML = paged.map(stay => {
        const badgeColor = stay.badge === 'Safari' ? 'bg-amber-600'
                         : stay.badge === 'City' ? 'bg-navy'
                         : stay.badge === 'Beach' ? 'bg-cyan-600'
                         : stay.badge === 'Budget' ? 'bg-emerald-600'
                         : 'bg-stayCoral';
        const img = (stay.images && stay.images[0]) ? stay.images[0] : '';
        if (currentView === 'grid') {
            return `
                <article class="stay-card anim-fade-up group" onclick="openStayModal('${stay.id}')">
                    <div class="relative h-40 sm:h-52 overflow-hidden">
                        <img src="${img}" alt="${stay.title}" loading="lazy" decoding="async" class="stay-img w-full h-full object-cover">
                        <span class="badge-stay ${badgeColor}">${stay.badge || 'Short Stay'}</span>
                        ${stay.superhost ? `<span class="absolute top-3 left-3 mt-7 bg-accentOrange text-white text-[9px] font-bold px-2 py-0.5 rounded-full"><i class="fas fa-award mr-0.5"></i>Superhost</span>` : ''}
                        <button onclick="toggleFavorite(event, this)" class="favorite-btn" aria-label="Save"><i class="far fa-heart"></i></button>
                    </div>
                    <div class="p-2.5 sm:p-3.5">
                        <div class="flex items-start justify-between gap-1 sm:gap-2 mb-1">
                            <h3 class="font-serif text-sm sm:text-base md:text-lg text-navy font-semibold leading-tight line-clamp-1">${stay.title}</h3>
                            <div class="flex items-center gap-1 flex-shrink-0"><i class="fas fa-star text-accentOrange text-[10px]"></i><span class="text-[10px] sm:text-xs font-semibold text-navy">${stay.rating}</span></div>
                        </div>
                        <p class="text-[10px] sm:text-[11px] text-gray-500 mb-1.5 sm:mb-2 line-clamp-1"><i class="fas fa-map-marker-alt text-stayCoral text-[9px] mr-1"></i>${stay.location}</p>
                        <div class="flex items-center gap-1.5 sm:gap-2 text-[9px] sm:text-[10px] text-gray-400 mb-2"><span><i class="fas fa-user-friends mr-0.5"></i>${stay.guests}</span><span>·</span><span><i class="fas fa-bed mr-0.5"></i>${stay.beds}</span><span>·</span><span><i class="fas fa-bath mr-0.5"></i>${stay.baths}</span></div>
                        <div class="flex items-end justify-between pt-2 border-t border-gray-100">
                            <div><span class="text-sm sm:text-base md:text-lg font-bold text-navy">KSh ${Number(stay.price).toLocaleString()}</span><span class="text-[9px] sm:text-[10px] text-gray-400">/ night</span></div>
                            <span class="text-[9px] sm:text-[10px] text-gray-400">${stay.reviews || 0}</span>
                        </div>
                    </div>
                </article>`;
        } else {
            return `
                <article class="bg-white rounded-xl overflow-hidden border border-gray-100 hover:shadow-lg transition-all cursor-pointer flex" onclick="openStayModal('${stay.id}')">
                    <div class="relative w-36 sm:w-56 flex-shrink-0 overflow-hidden"><img src="${img}" alt="${stay.title}" loading="lazy" decoding="async" class="w-full h-full object-cover"><span class="badge-stay ${badgeColor}">${stay.badge || 'Short Stay'}</span></div>
                    <div class="p-3 md:p-4 flex-1 min-w-0 flex flex-col justify-between">
                        <div><div class="flex items-start justify-between gap-2 mb-1"><h3 class="font-serif text-sm md:text-lg text-navy font-semibold line-clamp-1">${stay.title}</h3><div class="flex items-center gap-1 flex-shrink-0"><i class="fas fa-star text-accentOrange text-[10px]"></i><span class="text-xs font-semibold text-navy">${stay.rating}</span></div></div>
                        <p class="text-[11px] text-gray-500 mb-1"><i class="fas fa-map-marker-alt text-stayCoral text-[9px] mr-1"></i>${stay.location}</p>
                        <p class="text-[11px] text-gray-400 line-clamp-2 hidden sm:block">${stay.desc || ''}</p></div>
                        <div class="flex items-center justify-between pt-2 border-t border-gray-100 mt-2"><div class="flex items-center gap-2 text-[10px] text-gray-400"><span><i class="fas fa-user-friends mr-0.5"></i>${stay.guests}</span><span><i class="fas fa-bed mr-0.5"></i>${stay.beds}</span><span><i class="fas fa-bath mr-0.5"></i>${stay.baths}</span></div><div><span class="text-sm md:text-base font-bold text-navy">KSh ${Number(stay.price).toLocaleString()}</span><span class="text-[10px] text-gray-400">/night</span></div></div>
                    </div>
                </article>`;
        }
    }).join('');

    if (totalPages > 1) {
        let pag = `<button onclick="goToPage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''} class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:border-stayCoral hover:text-stayCoral transition-colors disabled:opacity-40 disabled:cursor-not-allowed"><i class="fas fa-chevron-left text-[10px]"></i></button>`;
        for (let i = 1; i <= totalPages; i++) {
            pag += `<button onclick="goToPage(${i})" class="w-9 h-9 flex items-center justify-center rounded-lg text-xs font-semibold transition-colors ${i === currentPage ? 'bg-stayCoral text-white shadow-md' : 'border border-gray-200 text-gray-600 hover:border-stayCoral hover:text-stayCoral'}">${i}</button>`;
        }
        pag += `<button onclick="goToPage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''} class="w-9 h-9 flex items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:border-stayCoral hover:text-stayCoral transition-colors disabled:opacity-40 disabled:cursor-not-allowed"><i class="fas fa-chevron-right text-[10px]"></i></button>`;
        pagination.innerHTML = pag;
    } else {
        pagination.innerHTML = '';
    }
}
function goToPage(page) {
    const filtered = getFilteredStays();
    const totalPages = Math.ceil(filtered.length / PER_PAGE) || 1;
    if (page < 1 || page > totalPages) return;
    currentPage = page;
    renderStays();
    document.getElementById('staysGrid').scrollIntoView({behavior: 'smooth', block: 'start'});
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
        renderStays();
    });
});

document.querySelectorAll('.amenity-checkbox').forEach(cb => {
    cb.addEventListener('change', function() {
        const val = this.value;
        if (this.checked) currentFilters.amenities.push(val);
        else currentFilters.amenities = currentFilters.amenities.filter(a => a !== val);
        currentPage = 1;
        renderStays();
    });
});

document.getElementById('minPrice').addEventListener('input', function() {
    currentFilters.minPrice = this.value ? parseInt(this.value) : null;
    currentPage = 1;
    renderStays();
});
document.getElementById('maxPrice').addEventListener('input', function() {
    currentFilters.maxPrice = this.value ? parseInt(this.value) : null;
    currentPage = 1;
    renderStays();
});

document.getElementById('superhostToggle').addEventListener('change', function() {
    currentFilters.superhost = this.checked;
    currentPage = 1;
    renderStays();
});

function toggleMobileFilters() {
    const content = document.getElementById('filterContent');
    const chevron = document.getElementById('mobileFilterChevron');
    content.classList.toggle('hidden');
    if (content.classList.contains('hidden')) {
        chevron.style.transform = 'rotate(0deg)';
    } else {
        chevron.style.transform = 'rotate(180deg)';
    }
}

function toggleGuestFilter(btn, val) {
    if (btn.classList.contains('active')) {
        btn.classList.remove('active');
        currentFilters.guests = null;
    } else {
        document.querySelectorAll('#guestFilters .chip').forEach(c => c.classList.remove('active'));
        btn.classList.add('active');
        currentFilters.guests = val;
    }
    currentPage = 1;
    renderStays();
}

function quickFilter(tag) {
    currentFilters.quickTag = tag;
    currentPage = 1;
    renderStays();
}

function resetAllFilters() {
    currentFilters = {types: [], amenities: [], minPrice: null, maxPrice: null, guests: null, superhost: false, search: '', quickTag: null};
    document.querySelectorAll('#typeFilters input[type="checkbox"]').forEach(cb => cb.checked = false);
    document.querySelectorAll('.amenity-checkbox').forEach(cb => cb.checked = false);
    document.querySelectorAll('#guestFilters .chip').forEach(c => c.classList.remove('active'));
    document.getElementById('minPrice').value = '';
    document.getElementById('maxPrice').value = '';
    document.getElementById('superhostToggle').checked = false;
    currentPage = 1;
    renderStays();
}

// Remove individual filter chips
function removeQuickTag() { currentFilters.quickTag = null; renderStays(); }
function removeType(t) {
    currentFilters.types = currentFilters.types.filter(x => x !== t);
    document.querySelectorAll('#typeFilters input').forEach(cb => { if (cb.value === t) cb.checked = false; });
    renderStays();
}
function removeAmenity(a) {
    currentFilters.amenities = currentFilters.amenities.filter(x => x !== a);
    document.querySelectorAll('.amenity-checkbox').forEach(cb => { if (cb.value === a) cb.checked = false; });
    renderStays();
}
function removeGuest() {
    currentFilters.guests = null;
    document.querySelectorAll('#guestFilters .chip').forEach(c => c.classList.remove('active'));
    renderStays();
}
function removeSuperhost() {
    currentFilters.superhost = false;
    document.getElementById('superhostToggle').checked = false;
    renderStays();
}
function removePrice() {
    currentFilters.minPrice = null;
    currentFilters.maxPrice = null;
    document.getElementById('minPrice').value = '';
    document.getElementById('maxPrice').value = '';
    renderStays();
}

/* ============================================================
   SORT & VIEW
   ============================================================ */
function applySort() {
    currentSort = document.getElementById('sortSelect').value;
    currentPage = 1;
    renderStays();
}

function setView(view, btn) {
    currentView = view;
    document.querySelectorAll('.view-btn').forEach(b => {
        b.classList.remove('active', 'bg-stayCoral', 'text-white');
        b.classList.add('text-gray-400');
    });
    btn.classList.add('active', 'bg-stayCoral', 'text-white');
    btn.classList.remove('text-gray-400');
    renderStays();
}

/* ============================================================
   SEARCH
   ============================================================ */
function handleSearch(e) {
    e.preventDefault();
    currentFilters.search = document.getElementById('searchLocation').value.toLowerCase();
    currentPage = 1;
    renderStays();
    document.getElementById('staysGrid').scrollIntoView({behavior: 'smooth', block: 'start'});
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
   MODAL
   ============================================================ */
function openStayModal(id, stay) {
    if (!stay) {
        const found = staysData.find(s => s.id === id);
        if (!found) return;
        stay = found;
    }
    activeModalStayId = id;
    modalSlideIndex = 0;
    currentStayLat = stay.lat;
    currentStayLng = stay.lng;

    // Populate
    document.getElementById('modalMainImg').src = stay.images[0];
    document.getElementById('modalTitle').textContent = stay.title;
    document.getElementById('modalLocation').textContent = stay.location;
    document.getElementById('modalRating').textContent = stay.rating;
    document.getElementById('modalReviews').textContent = `(${stay.reviews} reviews)`;
    document.getElementById('modalRating2').textContent = stay.rating;
    document.getElementById('modalReviews2').textContent = `${stay.reviews} reviews`;
    document.getElementById('modalGuests').textContent = stay.guests;
    document.getElementById('modalBeds').textContent = stay.beds;
    document.getElementById('modalBaths').textContent = stay.baths;
    document.getElementById('modalRooms').textContent = stay.rooms;
    document.getElementById('modalDesc').textContent = stay.desc;
    document.getElementById('modalPrice').textContent = `KSh ${stay.price.toLocaleString()}`;
    document.getElementById('modalHostName').textContent = stay.hostName;
    document.getElementById('modalHostInitial').textContent = stay.hostInitial;
    document.getElementById('modalPhotoCount').textContent = stay.images.length;
    document.getElementById('modalBadge').textContent = stay.badge;
    document.getElementById('modalBadge').className = `px-3 py-1 rounded-full text-[10px] md:text-xs font-bold uppercase bg-stayCoral/10 text-stayCoral`;
    document.getElementById('modalSuperhost').classList.toggle('hidden', !stay.superhost);

    // Price breakdown
    document.getElementById('modalPriceLabel').textContent = `KSh ${stay.price.toLocaleString()} × 2 nights`;
    document.getElementById('modalSubtotal').textContent = `KSh ${(stay.price * 2).toLocaleString()}`;
    const total = stay.price * 2 + 3500 + 2000;
    document.getElementById('modalTotal').textContent = `KSh ${total.toLocaleString()}`;

    // Amenities
    const amenContainer = document.getElementById('modalAmenities');
    amenContainer.innerHTML = stay.amenities.map(a => `
        <div class="flex items-center gap-2 text-xs text-gray-600">
            <i class="fas ${a.icon} text-stayTeal text-[11px] w-4 text-center"></i>
            <span>${a.label}</span>
        </div>
    `).join('');

    // Thumbnails
    const thumbContainer = document.getElementById('modalThumbs');
    thumbContainer.innerHTML = '';
    stay.images.forEach((img, i) => {
        const btn = document.createElement('button');
        btn.className = 'modal-thumb ' + (i === 0 ? 'ring-2 ring-white' : 'ring-1 ring-white/40') + ' w-10 h-10 md:w-12 md:h-12 rounded-lg overflow-hidden flex-shrink-0 transition-all hover:ring-2 hover:ring-white';
        btn.addEventListener('click', () => changeModalImage(i, img));
        btn.innerHTML = '<img src="' + img + '" alt="thumb" class="w-full h-full object-cover">';
        thumbContainer.appendChild(btn);
    });

    // Show (init map after modal is visible so Leaflet sizes/loads fast like rentals)
    const modal = document.getElementById('stayModal');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    requestAnimationFrame(() => {
        modal.classList.add('show');
        modal.style.opacity = '1';
        initStayMap(stay.lat, stay.lng);
        setTimeout(() => { if (stayMap) try { stayMap.invalidateSize(); } catch (e) {} }, 320);
    });

    // Start slideshow
    startModalSlideshow(stay.images);
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

/* ============================================================
   LEAFLET MAP (free, no API key)
   ============================================================ */

function initStayMap(lat, lng) {
    const mapEl = document.getElementById('stayMap');
    if (!mapEl) return;
    if (!lat || !lng) {
        mapEl.innerHTML = '<div class="w-full h-full flex flex-col items-center justify-center text-gray-400"><i class="fas fa-map-marker-alt text-2xl mb-2"></i><p class="text-xs">Location not available</p></div>';
        return;
    }
    if (stayMap) destroyStayMap();
    mapEl.innerHTML = '';
    stayMap = L.map(mapEl, { center: [lat, lng], zoom: 15, zoomControl: true, attributionControl: false });
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '© OpenStreetMap' }).addTo(stayMap);
    stayMarker = L.marker([lat, lng]).addTo(stayMap).bindPopup('Property Location').openPopup();
    setTimeout(() => { if (stayMap) try { stayMap.invalidateSize(); } catch (e) {} }, 120);
}

function destroyStayMap() {
    if (stayMap) {
        try { stayMap.remove(); } catch (e) {}
        stayMap = null;
    }
    stayMarker = null;
    const mapEl = document.getElementById('stayMap');
    if (mapEl) mapEl.innerHTML = '';
}

function getStayDirections() {
    const lat = currentStayLat;
    const lng = currentStayLng;
    if (!lat || !lng) return;
    if (!navigator.geolocation) {
        const mapEl = document.getElementById('stayMap');
        mapEl.innerHTML = '<div class="w-full h-full flex flex-col items-center justify-center text-gray-400"><i class="fas fa-exclamation-triangle text-2xl mb-2"></i><p class="text-xs">Geolocation not supported</p></div>';
        return;
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
                        drawStayRoute(userLat, userLng, lat, lng, data.routes[0].geometry.coordinates);
                    }
                });
        },
        function(error) {
            const mapEl = document.getElementById('stayMap');
            if (error.code === error.PERMISSION_DENIED) {
                mapEl.innerHTML = '<div class="w-full h-full flex flex-col items-center justify-center text-gray-400"><i class="fas fa-location-slash text-2xl mb-2"></i><p class="text-xs">Location permission denied</p></div>';
            } else {
                mapEl.innerHTML = '<div class="w-full h-full flex flex-col items-center justify-center text-gray-400"><i class="fas fa-exclamation-triangle text-2xl mb-2"></i><p class="text-xs">Unable to get your location</p></div>';
            }
        },
        { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
    );
}

function drawStayRoute(userLat, userLng, propLat, propLng, coordinates) {
    if (stayMap) {
        stayMap.eachLayer(function(layer) {
            if (layer !== stayMarker) stayMap.removeLayer(layer);
        });
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(stayMap);
        const routeCoords = coordinates.map(c => [c[1], c[0]]);
        L.polyline(routeCoords, { color: '#FF5A5F', weight: 4, opacity: 0.8 }).addTo(stayMap);
        L.marker([userLat, userLng]).addTo(stayMap).bindPopup('Your Location');
        stayMarker.bindPopup('Property Location').openPopup();
        stayMap.fitBounds([
            [userLat, userLng],
            [propLat, propLng]
        ], { padding: [40, 40] });
    }
}

function startModalSlideshow(images) {
    if (modalSlideInterval) clearInterval(modalSlideInterval);
    modalSlideInterval = setInterval(() => {
        if (document.getElementById('stayModal').classList.contains('hidden')) return;
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

function closeStayModal() {
    const modal = document.getElementById('stayModal');
    modal.classList.remove('show');
    setTimeout(() => modal.classList.add('hidden'), 300);
    document.body.style.overflow = '';
    if (modalSlideInterval) {
        clearInterval(modalSlideInterval);
        modalSlideInterval = null;
    }
    destroyStayMap();
}

// Backdrop click
document.getElementById('stayModal').addEventListener('click', function(e) {
    if (e.target === this) closeStayModal();
});

// Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeStayModal();
});

/* ============================================================
   INIT
   ============================================================ */
window.openStayModal = openStayModal;
document.addEventListener('DOMContentLoaded', function() {
    renderStays();
});
</script>
