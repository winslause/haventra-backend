<?php
// Property detail page - loads a single property from database
$property_id = $_GET['id'] ?? '';
$property_id = preg_replace('/[^0-9]/', '', $property_id);

$page_title = 'Property Details | HAVENTRA';
$page_description = 'View property details on HAVENTRA. Find your next home, rental, or investment property.';
$page_canonical = 'https://haventra.com/house/' . $property_id . '/';
$page_breadcrumbs = [
    ['name' => 'Home', 'url' => 'https://haventra.com/'],
    ['name' => 'Properties', 'url' => 'https://haventra.com/?page=houses'],
    ['name' => 'Property #' . $property_id, 'url' => 'https://haventra.com/house/' . $property_id . '/'],
];

$property = null;
if ($property_id) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM properties WHERE id = ? AND is_active = 1 LIMIT 1");
        $stmt->execute([$property_id]);
        $property = $stmt->fetch();
        if ($property) {
            $property['image_urls'] = json_decode($property['image_urls'], true) ?: [$property['image_url']];
            $property['amenities'] = json_decode($property['amenities'], true) ?: [];
            $page_title = htmlspecialchars($property['title']) . ' | HAVENTRA';
            $page_description = htmlspecialchars(substr($property['description'], 0, 160)) . '...';
            $page_canonical = 'https://haventra.com/house/' . $property['id'] . '/';
            $page_breadcrumbs = [
                ['name' => 'Home', 'url' => 'https://haventra.com/'],
                ['name' => 'Properties', 'url' => 'https://haventra.com/?page=houses'],
                ['name' => htmlspecialchars($property['title']), 'url' => 'https://haventra.com/house/' . $property['id'] . '/'],
            ];
        }
    } catch (PDOException $e) {
        $property = null;
    }
}
?>
<?php if (!$property): ?>
<!-- Property Not Found -->
<section class="py-20 px-4 md:px-6 text-center bg-[#f5efe8]">
    <div class="max-w-2xl mx-auto">
        <i class="fas fa-exclamation-circle text-6xl text-[#4a90a0] mb-6"></i>
        <h1 class="font-['Cormorant_Garamond'] text-4xl md:text-5xl text-[#3f352e] mb-4">Property Not Found</h1>
        <p class="text-[#1e4d40] text-lg mb-8">The property you are looking for does not exist or has been removed.</p>
        <a href="/" class="px-6 py-3 bg-[#4a90a0] text-white rounded-xl font-medium hover:bg-[#2d5a4a] transition-colors">Browse All Properties</a>
    </div>
</section>
<?php else: ?>
<!-- Property Detail -->
<section class="py-16 px-4 md:px-6 bg-[#f5efe8]">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Gallery -->
            <div class="lg:col-span-2">
                <div class="relative h-[500px] rounded-2xl overflow-hidden shadow-xl mb-6">
                    <img src="<?php echo htmlspecialchars($property['image_url']); ?>" alt="<?php echo htmlspecialchars($property['title']); ?>" class="w-full h-full object-cover">
                    <div class="absolute top-4 left-4 bg-[#4a90a0] text-white px-4 py-2 rounded-full text-sm font-bold uppercase shadow-lg">
                        <?php echo htmlspecialchars($property['badge']); ?>
                    </div>
                    <div class="absolute bottom-4 left-4 bg-white/90 backdrop-blur px-4 py-3 rounded-xl shadow-lg">
                        <h1 class="font-['Cormorant_Garamond'] text-3xl md:text-4xl text-[#3f352e] mb-2"><?php echo htmlspecialchars($property['title']); ?></h1>
                        <p class="text-[#1e4d40] text-sm"><i class="fas fa-map-marker-alt text-[#4a90a0] mr-2"></i> <?php echo htmlspecialchars($property['location']); ?></p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-8" id="propertyThumbnails">
                    <?php foreach ($property['image_urls'] as $i => $img): ?>
                    <img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($property['title']); ?> image <?php echo $i + 1; ?>" class="w-full h-48 object-cover rounded-xl cursor-pointer hover:scale-105 transition-transform border-2 border-transparent hover:border-[#4a90a0] <?php echo $i === 0 ? 'border-[#4a90a0]' : ''; ?>">
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Details -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-gray-100 sticky top-24">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="bg-[#4a90a0]/10 text-[#4a90a0] px-3 py-1 rounded-full text-[10px] md:text-xs font-bold uppercase"><?php echo htmlspecialchars($property['badge']); ?></span>
                        <span class="text-gray-500 text-[10px] md:text-xs"><?php echo htmlspecialchars($property['date_added']); ?></span>
                    </div>

                    <h2 class="font-['Cormorant_Garamond'] text-2xl md:text-3xl text-[#3f352e] mb-4"><?php echo htmlspecialchars($property['title']); ?></h2>

                    <div class="flex items-center gap-2 text-[#1e4d40] text-sm mb-6">
                        <i class="fas fa-map-marker-alt text-[#4a90a0]"></i>
                        <span><?php echo htmlspecialchars($property['location']); ?></span>
                    </div>

                    <div class="text-3xl md:text-4xl font-['DM_Serif_Display'] text-[#4a90a0] mb-6">
                        KSh <?php echo htmlspecialchars($property['price']); ?>
                        <span class="text-lg md:text-xl text-gray-500 font-sans ml-2"><?php echo htmlspecialchars($property['price_suffix']); ?></span>
                    </div>

                    <!-- Specs -->
                    <div class="grid grid-cols-3 gap-4 mb-6 p-4 bg-gray-50 rounded-xl">
                        <div class="text-center">
                            <p class="text-[#1e4d40] text-[10px] uppercase mb-1">Beds</p>
                            <p class="text-[#3f352e] font-bold text-lg"><i class="fas fa-bed text-[#4a90a0] mr-1"></i> <?php echo $property['beds']; ?></p>
                        </div>
                        <div class="text-center">
                            <p class="text-[#1e4d40] text-[10px] uppercase mb-1">Baths</p>
                            <p class="text-[#3f352e] font-bold text-lg"><i class="fas fa-bath text-[#4a90a0] mr-1"></i> <?php echo $property['baths']; ?></p>
                        </div>
                        <div class="text-center">
                            <p class="text-[#1e4d40] text-[10px] uppercase mb-1">Size</p>
                            <p class="text-[#3f352e] font-bold text-lg"><i class="fas fa-ruler-combined text-[#4a90a0] mr-1"></i> <?php echo htmlspecialchars($property['size']); ?></p>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <h3 class="font-['Cormorant_Garamond'] text-xl text-[#3f352e] mb-3">Description</h3>
                        <p class="text-[#5c524a] text-sm md:text-base leading-relaxed"><?php echo htmlspecialchars($property['description']); ?></p>
                    </div>

                    <!-- Amenities -->
                    <div class="mb-6">
                        <h3 class="font-['Cormorant_Garamond'] text-xl text-[#3f352e] mb-3">Amenities</h3>
                        <div class="flex flex-wrap gap-2">
                            <?php foreach ($property['amenities'] as $amenity): ?>
                            <span class="px-2.5 py-1 bg-white text-[#1e4d40] text-[10px] md:text-xs rounded-full border border-[#4a90a0]/20">
                                <i class="fas fa-check text-[#4a90a0] mr-1 text-[9px]"></i> <?php echo htmlspecialchars($amenity); ?>
                            </span>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Map -->
                    <div class="mb-6">
                        <h3 class="font-['Cormorant_Garamond'] text-xl text-[#3f352e] mb-3">Location</h3>
                        <div id="propertyMap" class="h-48 rounded-xl bg-[#e8f5e9]"></div>
                        <button onclick="getPropertyDirections()" class="w-full py-3 mt-3 bg-[#4a90a0] text-white rounded-xl font-medium hover:bg-[#2d5a4a] transition-colors flex items-center justify-center gap-2">
                            <i class="fas fa-route"></i> Get Directions
                        </button>
                    </div>

                    <!-- Contact -->
                    <div class="pt-6 border-t border-gray-100">
                        <button class="w-full py-4 bg-[#00215E] text-white text-lg font-bold rounded-xl hover:bg-[#003d7a] transition-colors shadow-lg">
                            <i class="fas fa-phone-alt mr-2"></i> Contact Agent
                        </button>
                        <button class="w-full py-3 mt-3 border border-[#00215E] text-[#00215E] font-medium rounded-xl hover:bg-[#00215E] hover:text-white transition-colors">
                            <i class="fas fa-envelope mr-2"></i> Send Message
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Leaflet Map -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<style>#propertyMap { width: 100%; border-radius: 8px; }</style>

<script>
    let propertyMap = null;
    let propertyMarker = null;
    let propertyLat = <?php echo (float)$property['lat']; ?>;
    let propertyLng = <?php echo (float)$property['lng']; ?>;

    function initPropertyMap() {
        const mapDiv = document.getElementById('propertyMap');
        if (!mapDiv || !propertyLat || !propertyLng) return;
        propertyMap = L.map('propertyMap', {zoomControl: false, attributionControl: false}).setView([propertyLat, propertyLng], 14);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {maxZoom: 19}).addTo(propertyMap);
        propertyMarker = L.marker([propertyLat, propertyLng]).addTo(propertyMap);
        propertyMarker.bindPopup('<?php echo htmlspecialchars($property['title']); ?>').openPopup();
    }

    function getPropertyDirections() {
        if (!propertyLat || !propertyLng) return;
        window.open('https://www.google.com/maps/dir/?api=1&destination=' + propertyLat + ',' + propertyLng + '&travelmode=driving', '_blank');
    }

    if (propertyLat && propertyLng) {
        initPropertyMap();
    }
</script>

<?php endif; ?>

<!-- Schema.org Property Structured Data -->
<?php if ($property): ?>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "SingleFamilyResidence",
    "name": "<?php echo htmlspecialchars($property['title']); ?>",
    "description": "<?php echo htmlspecialchars($property['description']); ?>",
    "address": {
        "@type": "PostalAddress",
        "streetAddress": "<?php echo htmlspecialchars($property['location']); ?>",
        "addressLocality": "Nairobi",
        "addressRegion": "Nairobi County",
        "addressCountry": "KE"
    },
    "geo": {
        "@type": "GeoCoordinates",
        "latitude": <?php echo (float)$property['lat']; ?>,
        "longitude": <?php echo (float)$property['lng']; ?>
    },
    "offers": {
        "@type": "Offer",
        "price": "<?php echo htmlspecialchars($property['price']); ?>",
        "priceCurrency": "KES",
        "priceSpecification": {
            "@type": "UnitPriceSpecification",
            "price": "<?php echo htmlspecialchars($property['price']); ?>",
            "priceCurrency": "KES",
            "referenceQuantity": {
                "@type": "QuantitativeValue",
                "value": 1,
                "unitCode": "<?php echo strpos($property['price_suffix'], 'night') !== false ? 'NIGHT' : 'MONTH'; ?>"
            }
        },
        "availability": "https://schema.org/InStock"
    },
    "numberOfRooms": <?php echo $property['beds']; ?>,
    "floorSize": {
        "@type": "QuantitativeValue",
        "value": "<?php echo str_replace(',', '', $property['size']); ?>",
        "unitCode": "FT2"
    },
    "image": [
        <?php echo implode(",\n", array_map(function($img) { return '"' . htmlspecialchars($img, ENT_QUOTES) . '"'; }, $property['image_urls'])); ?>
    ],
    "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "https://haventra.com/house/<?php echo $property['id']; ?>/"
    }
}
</script>
<?php endif; ?>
