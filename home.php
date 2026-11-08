<?php
// Page-specific SEO variables for home page
$page_title = 'HAVENTRA - Global Real Estate & Property Marketplace | Find, Rent, Buy, Sell & Book';
$page_description = 'HAVENTRA is an international real estate and accommodation marketplace connecting property owners, landlords, agencies, Airbnb hosts and clients. Find houses, apartments, land, commercial properties for rent or sale, and book short-stay accommodation worldwide.';
$page_canonical = 'https://haventra.com/';
?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #featuredMap { height: 200px; width: 100%; border-radius: 8px; margin: 1rem 0; background: #e8f5e9; }
        .leaflet-container { font-size: 12px; }
    </style>
    <!-- ====== HERO SECTION ====== -->
    <section class="hero-section">
        <div class="cinema-showcase">
            <div class="cinema-frame">
                <img src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1600&q=80" alt="Modern house exterior" class="cinema-image">
            </div>
            <div class="cinema-frame">
                <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1600&q=80" alt="Luxury villa with pool" class="cinema-image">
            </div>
            <div class="cinema-frame">
                <img src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1600&q=80" alt="Cozy home interior" class="cinema-image">
            </div>
            <div class="cinema-frame">
                <img src="https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea?auto=format&fit=crop&w=1600&q=80" alt="Contemporary house at dusk" class="cinema-image">
            </div>
            <div class="cinema-frame">
                <img src="https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=1600&q=80" alt="Architectural home with garden" class="cinema-image">
            </div>
        </div>

        <div class="hero-overlay"></div>
        <div class="hero-gradient"></div>

        <div class="hero-content">
            <div class="experience-card">
                <div class="card-arrows">
                    <div class="progress-circle up">
                        <svg><circle class="bg" cx="30" cy="30" r="25"></circle><circle class="progress" cx="30" cy="30" r="25"></circle></svg>
                        <span class="progress-text">0%</span>
                    </div>

                    <div class="arrow-center-text">
                        <div class="center-title">
                            HAVENTRA
                            <span class="brand">Real Estate Marketplace</span>
                        </div>
                        <div class="center-subtitle">Discover • Rent • Buy • Sell • Book</div>
                    </div>

                    <div class="progress-circle left">
                        <svg><circle class="bg" cx="30" cy="30" r="25"></circle><circle class="progress" cx="30" cy="30" r="25"></circle></svg>
                        <span class="progress-text">0%</span>
                    </div>

                    <div class="progress-circle right">
                        <svg><circle class="bg" cx="30" cy="30" r="25"></circle><circle class="progress" cx="30" cy="30" r="25"></circle></svg>
                        <span class="progress-text">0%</span>
                    </div>

                    <div class="progress-circle down">
                        <svg><circle class="bg" cx="30" cy="30" r="25"></circle><circle class="progress" cx="30" cy="30" r="25"></circle></svg>
                        <span class="progress-text">0%</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ====== END HERO SECTION ====== -->

    <!-- ====== EXPLORE BY CATEGORY SECTION ====== -->
    <section class="category-section">
        <div class="category-container">
            
            <!-- Section Heading -->
            <div class="text-center mb-10 md:mb-14">
                <h2 class="font-serif text-3xl md:text-4xl text-navy font-semibold mb-3">Explore by Category</h2>
                <p class="text-gray-500 text-sm md:text-base max-w-lg mx-auto">
                    Find exactly what you are looking for. Browse our top property categories and discover your next opportunity.
                </p>
            </div>

            <!-- Cards Row -->
            <div class="category-row">
                
                <!-- 1. Houses for Rent -->
                <a href="/haventra/houses-for-rent/" class="category-card">
                    <div class="cat-icon-wrapper">
                        <i class="fas fa-home"></i>
                    </div>
                    <h3 class="cat-title">Houses for Rent</h3>
                    <p class="cat-desc">Family homes & spaces</p>
                </a>

                <!-- 2. Apartments for Sale -->
                <a href="/haventra/apartments-for-sale/" class="category-card">
                    <div class="cat-icon-wrapper">
                        <i class="fas fa-building"></i>
                    </div>
                    <h3 class="cat-title">Apartments for Sale</h3>
                    <p class="cat-desc">Invest in modern living</p>
                </a>

                <!-- 3. Short-Stay Stays -->
                <a href="/haventra/airbnb/" class="category-card">
                    <div class="cat-icon-wrapper">
                        <i class="fas fa-umbrella-beach"></i>
                    </div>
                    <h3 class="cat-title">Short-Stay Stays</h3>
                    <p class="cat-desc">Book your next getaway</p>
                </a>

                <!-- 4. Commercial Spaces -->
                <a href="/haventra/commercial-property-for-rent/" class="category-card">
                    <div class="cat-icon-wrapper">
                        <i class="fas fa-store"></i>
                    </div>
                    <h3 class="cat-title">Commercial Spaces</h3>
                    <p class="cat-desc">Grow your business</p>
                </a>

            </div>
        </div>
    </section>
    <!-- ====== END EXPLORE BY CATEGORY SECTION ====== -->
    
    <script>
        function updateProgress() {
            const textElements = document.querySelectorAll('.progress-text');
            const startTime = Date.now();
            
            setInterval(() => {
                const elapsed = (Date.now() - startTime) % 100000;
                const progress = Math.floor((elapsed / 100000) * 100) + 1;
                const displayProgress = progress > 100 ? 100 : progress;
                
                textElements.forEach(el => {
                    el.textContent = displayProgress + '%';
                });
            }, 50);
        }
        
        updateProgress();
    </script>





<!-- ===== FEATURED LISTINGS SECTION ===== -->
<section class="py-16 px-4 md:px-6 bg-[#f5efe8] relative overflow-hidden">
    <div class="max-w-7xl mx-auto">
        
        <!-- Section Heading -->
        <div class="text-center mb-8 md:mb-12 reveal">
            <h2 class="font-['Cormorant_Garamond'] text-4xl md:text-5xl text-[#3f352e] mb-3">Featured Listings</h2>
            <p class="text-[#1e4d40] max-w-2xl mx-auto text-sm md:text-base">
                Discover our latest premium properties, from cozy rentals to luxury homes for sale and short-stay getaways.
            </p>
        </div>

        <!-- Category Tabs -->
        <div class="flex justify-center flex-wrap gap-2 md:gap-4 mb-8 md:mb-10 reveal">
            <button class="featured-tab active" data-target="for-rent">For Rent</button>
            <button class="featured-tab" data-target="for-sale">For Sale</button>
            <button class="featured-tab" data-target="for-booking">For Booking</button>
        </div>

        <!-- Listings Grids -->
        <div class="relative w-full overflow-hidden">
            
<?php
// Fetch featured properties from database, grouped by type
$featuredByType = [];
try {
    $stmt = $pdo->query("SELECT * FROM properties WHERE is_active = 1 ORDER BY type, created_at DESC");
    while ($row = $stmt->fetch()) {
        $featuredByType[$row['type']][] = $row;
    }
} catch (PDOException $e) {
    $featuredByType = [];
}

$typeLabels = [
    'rent' => 'For Rent',
    'sale' => 'For Sale',
    'booking' => 'For Booking',
];

$typeBadges = [
    'rent' => 'For Rent',
    'sale' => 'For Sale',
    'booking' => 'Short Stay',
];

$typeColors = [
    'rent' => '#4a90a0',
    'sale' => '#F59E0B',
    'booking' => '#005BB5',
];

// Limit to 4 per type
foreach ($featuredByType as $type => &$props) {
    $props = array_slice($props, 0, 4);
}
unset($props);
?>
            <!-- 1. FOR RENT -->
            <div id="for-rent" class="featured-content featured-carousel">
                <?php foreach (($featuredByType['rent'] ?? []) as $prop): ?>
                <div class="featured-card group" onclick="openFeaturedModal('prop-<?php echo $prop['id']; ?>')">
                    <div class="relative h-56 overflow-hidden rounded-t-2xl">
                        <img src="<?php echo htmlspecialchars($prop['image_url']); ?>" alt="<?php echo htmlspecialchars($prop['title']); ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        <div class="absolute top-3 left-3 bg-white/90 backdrop-blur px-3 py-1 rounded-full text-xs font-semibold text-[#3f352e]"><?php echo htmlspecialchars($prop['badge']); ?></div>
                    </div>
                    <div class="p-4 bg-white rounded-b-2xl border border-t-0 border-[#4a90a0]/10">
                        <h3 class="font-['Cormorant_Garamond'] text-xl text-[#3f352e] truncate"><?php echo htmlspecialchars($prop['title']); ?></h3>
                        <p class="text-[#1e4d40] text-xs mb-2"><i class="fas fa-map-marker-alt text-[#4a90a0]"></i> <?php echo htmlspecialchars($prop['location']); ?></p>
                        <div class="flex justify-between items-center border-t border-gray-100 pt-3">
                            <span class="text-[#4a90a0] font-bold text-sm md:text-base">KSh <?php echo htmlspecialchars($prop['price']); ?><span class="text-xs text-gray-400 font-normal"><?php echo htmlspecialchars($prop['price_suffix']); ?></span></span>
                            <span class="text-xs text-gray-500"><i class="fas fa-bed"></i> <?php echo $prop['beds']; ?> &nbsp; <i class="fas fa-bath"></i> <?php echo $prop['baths']; ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <a href="/haventra/houses-for-rent/" class="view-more-link absolute bottom-2 right-2 text-[10px] md:text-xs font-bold text-[#4a90a0] hover:text-[#2d5a4a] underline hover:underline px-3 py-1.5 bg-white/80 backdrop-blur rounded-lg border border-[#4a90a0]/20 shadow-sm transition-all">
                    View more <i class="fas fa-chevron-right ml-1 text-[9px]"></i>
                </a>
            </div>

            <!-- 2. FOR SALE (Latest 4) - Hidden by default -->
            <div id="for-sale" class="featured-content featured-carousel hidden">
                <?php foreach (($featuredByType['sale'] ?? []) as $prop): ?>
                <div class="featured-card group" onclick="openFeaturedModal('prop-<?php echo $prop['id']; ?>')">
                    <div class="relative h-56 overflow-hidden rounded-t-2xl">
                        <img src="<?php echo htmlspecialchars($prop['image_url']); ?>" alt="<?php echo htmlspecialchars($prop['title']); ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        <div class="absolute top-3 left-3 bg-[#F59E0B] text-white backdrop-blur px-3 py-1 rounded-full text-xs font-semibold"><?php echo htmlspecialchars($prop['badge']); ?></div>
                    </div>
                    <div class="p-4 bg-white rounded-b-2xl border border-t-0 border-[#4a90a0]/10">
                        <h3 class="font-['Cormorant_Garamond'] text-xl text-[#3f352e] truncate"><?php echo htmlspecialchars($prop['title']); ?></h3>
                        <p class="text-[#1e4d40] text-xs mb-2"><i class="fas fa-map-marker-alt text-[#4a90a0]"></i> <?php echo htmlspecialchars($prop['location']); ?></p>
                        <div class="flex justify-between items-center border-t border-gray-100 pt-3">
                            <span class="text-[#4a90a0] font-bold text-sm md:text-base">KSh <?php echo htmlspecialchars($prop['price']); ?></span>
                            <span class="text-xs text-gray-500"><i class="fas fa-bed"></i> <?php echo $prop['beds']; ?> &nbsp; <i class="fas fa-bath"></i> <?php echo $prop['baths']; ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <a href="/haventra/apartments-for-sale/" class="view-more-link absolute bottom-2 right-2 text-[10px] md:text-xs font-bold text-[#F59E0B] hover:text-[#b45309] underline hover:underline px-3 py-1.5 bg-white/80 backdrop-blur rounded-lg border border-[#F59E0B]/20 shadow-sm transition-all">
                    View more <i class="fas fa-chevron-right ml-1 text-[9px]"></i>
                </a>
            </div>

            <!-- 3. FOR BOOKING (Latest 4) - Hidden by default -->
            <div id="for-booking" class="featured-content featured-carousel hidden">
                <?php foreach (($featuredByType['booking'] ?? []) as $prop): ?>
                <div class="featured-card group" onclick="openFeaturedModal('prop-<?php echo $prop['id']; ?>')">
                    <div class="relative h-56 overflow-hidden rounded-t-2xl">
                        <img src="<?php echo htmlspecialchars($prop['image_url']); ?>" alt="<?php echo htmlspecialchars($prop['title']); ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        <div class="absolute top-3 left-3 bg-[#005BB5] text-white backdrop-blur px-3 py-1 rounded-full text-xs font-semibold"><?php echo htmlspecialchars($prop['badge']); ?></div>
                    </div>
                    <div class="p-4 bg-white rounded-b-2xl border border-t-0 border-[#4a90a0]/10">
                        <h3 class="font-['Cormorant_Garamond'] text-xl text-[#3f352e] truncate"><?php echo htmlspecialchars($prop['title']); ?></h3>
                        <p class="text-[#1e4d40] text-xs mb-2"><i class="fas fa-map-marker-alt text-[#4a90a0]"></i> <?php echo htmlspecialchars($prop['location']); ?></p>
                        <div class="flex justify-between items-center border-t border-gray-100 pt-3">
                            <span class="text-[#4a90a0] font-bold text-sm md:text-base">KSh <?php echo htmlspecialchars($prop['price']); ?><span class="text-xs text-gray-400 font-normal"><?php echo htmlspecialchars($prop['price_suffix']); ?></span></span>
                            <span class="text-xs text-gray-500"><i class="fas fa-user"></i> <?php echo $prop['beds'] + $prop['baths']; ?> Guests</span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <a href="/haventra/airbnb/" class="view-more-link absolute bottom-2 right-2 text-[10px] md:text-xs font-bold text-[#005BB5] hover:text-[#003d7a] underline hover:underline px-3 py-1.5 bg-white/80 backdrop-blur rounded-lg border border-[#005BB5]/20 shadow-sm transition-all">
                    View more <i class="fas fa-chevron-right ml-1 text-[9px]"></i>
                </a>
            </div>

        </div>
    </div>
</section>

<!-- ===== FEATURED PROPERTY MODAL (MOBILE OPTIMIZED) ===== -->
<div id="featuredModal" class="fixed inset-0 bg-black/80 backdrop-blur-md z-[2000] hidden items-center justify-center p-2 md:p-4 opacity-0 transition-opacity duration-300">
    <div class="bg-[#f5efe8] max-w-5xl w-full max-h-[95vh] md:max-h-[90vh] overflow-y-auto rounded-2xl md:rounded-3xl shadow-2xl relative transform scale-95 transition-transform duration-300" id="featuredModalContent">
        
        <!-- Close Button -->
        <button onclick="closeFeaturedModal()" class="fixed md:absolute top-4 right-4 w-10 h-10 bg-white rounded-full shadow-lg flex items-center justify-center hover:bg-[#4a90a0] hover:text-white transition-all z-50">
            <i class="fas fa-times"></i>
        </button>

        <div class="p-4 md:p-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-8">
                
                <!-- Left Column - Gallery -->
                <div>
                    <div class="relative rounded-xl md:rounded-2xl overflow-hidden h-[250px] md:h-[400px]">
                        <img id="featuredMainImage" src="" alt="Property Image" class="w-full h-full object-cover transition-opacity duration-500">
                    </div>
                    
                    <!-- Thumbnails -->
                    <div class="flex gap-2 md:gap-3 overflow-x-auto py-3 md:py-4 scrollbar-hide" id="featuredThumbnails">
                        <!-- Thumbnails injected via JS -->
                    </div>
                </div>

                <!-- Right Column - Details -->
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="bg-[#4a90a0]/10 text-[#4a90a0] px-3 py-1 rounded-full text-[10px] md:text-xs font-bold uppercase" id="featuredBadge">For Rent</span>
                        <span class="text-gray-500 text-[10px] md:text-xs" id="featuredDate">Added 2 days ago</span>
                    </div>
                    
                    <h2 class="font-['Cormorant_Garamond'] text-2xl md:text-4xl text-[#3f352e] mb-2" id="featuredTitle">Property Title</h2>
                    
                    <div class="flex items-center gap-2 text-[#1e4d40] text-xs md:text-sm mb-4 md:mb-6">
                        <i class="fas fa-map-marker-alt text-[#4a90a0]"></i>
                        <span id="featuredLocation">Location</span>
                    </div>

                    <!-- Property Location Map -->
                    <div id="featuredMap"></div>

                    <button onclick="getFeaturedDirections()" class="w-full py-2 md:py-2.5 bg-[#4a90a0] text-white text-sm md:text-base rounded-lg font-medium hover:bg-[#2d5a4a] transition-colors shadow-lg flex items-center justify-center gap-2">
                        <i class="fas fa-route"></i> Get Directions
                    </button>

                    <div class="text-2xl md:text-3xl font-['DM_Serif_Display'] text-[#4a90a0] mb-4 md:mb-6">
                        <span id="featuredPrice">KSh 0</span>
                        <span class="text-xs md:text-sm text-gray-500 font-sans" id="featuredPriceSuffix">/month</span>
                    </div>

                    <!-- Specs Grid -->
                    <div class="grid grid-cols-3 gap-2 md:gap-4 mb-4 md:mb-6 p-3 md:p-4 bg-white/60 rounded-xl">
                        <div class="text-center border-r border-gray-200">
                            <p class="text-[#1e4d40] text-[9px] md:text-[10px] uppercase mb-1">Beds</p>
                            <p class="text-[#3f352e] font-bold text-sm md:text-lg"><i class="fas fa-bed text-[#4a90a0] text-xs md:text-sm mr-1"></i> <span id="featuredBeds">3</span></p>
                        </div>
                        <div class="text-center border-r border-gray-200">
                            <p class="text-[#1e4d40] text-[9px] md:text-[10px] uppercase mb-1">Baths</p>
                            <p class="text-[#3f352e] font-bold text-sm md:text-lg"><i class="fas fa-bath text-[#4a90a0] text-xs md:text-sm mr-1"></i> <span id="featuredBaths">2</span></p>
                        </div>
                        <div class="text-center">
                            <p class="text-[#1e4d40] text-[9px] md:text-[10px] uppercase mb-1">Size</p>
                            <p class="text-[#3f352e] font-bold text-sm md:text-lg"><i class="fas fa-ruler-combined text-[#4a90a0] text-xs md:text-sm mr-1"></i> <span id="featuredSize">1,200</span> <span class="hidden md:inline">sqft</span></p>
                        </div>
                    </div>

                    <p class="text-[#5c524a] text-xs md:text-sm leading-relaxed mb-4 md:mb-6 line-clamp-4" id="featuredDesc">
                        Property description goes here...
                    </p>

                    <h3 class="font-['Cormorant_Garamond'] text-lg md:text-xl text-[#3f352e] mb-2 md:mb-3">Amenities</h3>
                    <div class="flex flex-wrap gap-2 mb-6 md:mb-8" id="featuredAmenities">
                        <!-- Amenities injected via JS -->
                    </div>

                    <div class="flex gap-2 md:gap-3">
                        <button class="flex-1 py-2.5 md:py-3 bg-[#4a90a0] text-white text-sm md:text-base rounded-lg font-medium hover:bg-[#2d5a4a] transition-colors shadow-lg">
                            <i class="fas fa-calendar-check mr-2"></i> Book / Enquire
                        </button>
                        <button class="w-10 h-10 md:w-12 md:h-12 flex items-center justify-center border border-[#4a90a0]/30 rounded-lg text-[#4a90a0] hover:bg-[#4a90a0] hover:text-white transition-colors">
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===== REQUIRED CSS FOR FEATURED SECTION ===== -->
<style>
    /* Tab Styles */
    .featured-tab {
        padding: 0.5rem 1rem;
        border-radius: 999px;
        font-family: 'Montserrat', sans-serif;
        font-weight: 500;
        font-size: 0.8rem;
        color: #1e4d40;
        background: transparent;
        border: 1px solid rgba(74, 144, 160, 0.3);
        transition: all 0.3s ease;
        cursor: pointer;
    }
    @media (min-width: 768px) {
        .featured-tab { padding: 0.75rem 2rem; font-size: 0.9rem; }
    }
    .featured-tab:hover {
        background: rgba(74, 144, 160, 0.1);
    }
    .featured-tab.active {
        background: #4a90a0;
        color: white;
        border-color: #4a90a0;
        box-shadow: 0 4px 15px rgba(74, 144, 160, 0.3);
    }

    /* Card Styles */
    .featured-card {
        background: white;
        border-radius: 1rem;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.4s ease;
        border: 1px solid rgba(74, 144, 160, 0.1);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
    .featured-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        border-color: rgba(74, 144, 160, 0.3);
    }

    /* =========================================
       CAROUSEL & GRID LOGIC (FIXED)
       ========================================= */
       
    /* Force hidden to actually hide */
    .featured-content.hidden {
        display: none !important;
    }

    /* Default (Desktop) Grid View */
    .featured-carousel {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: 1.5rem;
    }

    @media (min-width: 768px) {
        .featured-carousel {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (min-width: 1024px) {
        .featured-carousel {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    /* Mobile Carousel Style */
    @media (max-width: 767px) {
        .featured-carousel {
            display: flex !important; /* Overrides grid */
            flex-direction: row;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            -webkit-overflow-scrolling: touch;
            gap: 1rem;
            padding-bottom: 1rem;
            scrollbar-width: none; /* Firefox */
            -ms-overflow-style: none; /* IE/Edge */
        }
        .featured-carousel::-webkit-scrollbar {
            display: none; /* Chrome/Safari */
        }
        .featured-carousel .featured-card {
            flex: 0 0 85%; /* Shows a bit of the next card */
            scroll-snap-align: center;
        }
    }

    /* View more link */
    .featured-carousel {
        position: relative;
    }
    .view-more-link {
        display: inline-flex;
        align-items: center;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        transition: all 0.2s ease;
    }
    .view-more-link i {
        font-size: 8px;
    }
    .view-more-link:hover {
        transform: translateX(3px);
    }

    /* Modal Visibility */
    #featuredModal.show {
        display: flex;
        opacity: 1;
    }
    #featuredModal.show #featuredModalContent {
        transform: scale(1);
    }

    /* Hide scrollbar for thumbnails */
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    /* Thumbnail specific styles */
        width: 60px;
        height: 45px;
        object-fit: cover;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        flex-shrink: 0;
    }
    @media (min-width: 768px) {
        .featured-thumb { width: 80px; height: 60px; border-radius: 8px; }
    }
    /* Modal logo image */
    #agencyModalLogo img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .featured-thumb:hover {
        transform: scale(1.05);
    }
    .featured-thumb.active {
        border-color: #4a90a0;
        transform: scale(1.05);
    }
</style>

<!-- ===== REQUIRED JAVASCRIPT FOR FEATURED SECTION ===== -->
<script>
    // 1. Tab Switching Logic
    document.querySelectorAll('.featured-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            // Tabs state
            document.querySelectorAll('.featured-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            // Hide all contents
            document.querySelectorAll('.featured-content').forEach(c => {
                c.classList.add('hidden');
                c.style.display = 'none'; // Ensure it's hidden
            });
            
            // Show target content
            const targetId = this.dataset.target;
            const targetContent = document.getElementById(targetId);
            targetContent.classList.remove('hidden');
            
            // Reset display so CSS grid/flex takes over
            targetContent.style.display = ''; 
            
            // Start auto-slide for the newly active tab on mobile
            startAutoSlide();
        });
    });

    // 2. Data from Database
    const featuredData = <?php
    try {
        $stmt = $pdo->query("SELECT * FROM properties WHERE is_active = 1 ORDER BY id");
        $jsData = [];
        while ($row = $stmt->fetch()) {
            $images = json_decode($row['image_urls'], true) ?: [$row['image_url']];
            $amenities = json_decode($row['amenities'], true) ?: [];
            $jsData['prop-' . $row['id']] = [
                'title' => $row['title'],
                'location' => $row['location'],
                'price' => $row['price'],
                'priceSuffix' => $row['price_suffix'],
                'badge' => $row['badge'],
                'date' => $row['date_added'],
                'beds' => (int)$row['beds'],
                'baths' => (int)$row['baths'],
                'size' => $row['size'],
                'desc' => $row['description'],
                'amenities' => $amenities,
                'images' => $images,
                'lat' => (float)$row['lat'],
                'lng' => (float)$row['lng'],
            ];
        }
        echo json_encode($jsData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_SLASHES);
    } catch (PDOException $e) {
        echo '{}';
    }
    ?>;

    let featuredSlideInterval;
    let autoScrollInterval;
    let isUserScrolling = false;

    // 3. Auto-Slide for Mobile Carousels
    function startAutoSlide() {
        if (window.innerWidth >= 768) return; // Disable on desktop

        clearInterval(autoScrollInterval);
        
        // Only auto-slide if there's a visible carousel
        const activeCarousel = document.querySelector('.featured-content:not(.hidden)');
        if (!activeCarousel) return;

        autoScrollInterval = setInterval(() => {
            if (isUserScrolling) return; // Pause if user is interacting

            const card = activeCarousel.querySelector('.featured-card');
            if (!card) return;
            
            // Calculate how much to scroll (width of card + gap)
            const scrollAmount = card.offsetWidth + 16; 
            const maxScroll = activeCarousel.scrollWidth - activeCarousel.clientWidth;

            if (activeCarousel.scrollLeft >= maxScroll - 10) {
                // Reset to start if at the end
                activeCarousel.scrollTo({ left: 0, behavior: 'smooth' });
            } else {
                activeCarousel.scrollBy({ left: scrollAmount, behavior: 'smooth' });
            }
        }, 3000); // Slide every 3 seconds
    }

    // Pause auto-scroll on user interaction
    document.querySelectorAll('.featured-carousel').forEach(carousel => {
        carousel.addEventListener('touchstart', () => { isUserScrolling = true; }, { passive: true });
        carousel.addEventListener('touchend', () => { 
            setTimeout(() => { isUserScrolling = false; }, 2000); // Resume after 2s of no interaction
        }, { passive: true });
    });

    // Initialize auto-slide on load
    window.addEventListener('load', startAutoSlide);
    window.addEventListener('resize', startAutoSlide);

    // 4. Open Modal Function
    function openFeaturedModal(dataKey) {
        const data = featuredData[dataKey];
        if (!data) return;

        document.getElementById('featuredTitle').textContent = data.title;
        document.getElementById('featuredLocation').textContent = data.location;
        document.getElementById('featuredPrice').textContent = `KSh ${data.price}`;
        document.getElementById('featuredPriceSuffix').textContent = data.priceSuffix;
        document.getElementById('featuredBadge').textContent = data.badge;
        document.getElementById('featuredDate').textContent = data.date;
        document.getElementById('featuredBeds').textContent = data.beds;
        document.getElementById('featuredBaths').textContent = data.baths;
        document.getElementById('featuredSize').textContent = data.size;
        document.getElementById('featuredDesc').textContent = data.desc;

        const amenitiesContainer = document.getElementById('featuredAmenities');
        amenitiesContainer.innerHTML = data.amenities.map(amenity => `
            <span class="px-2 py-1 md:px-3 md:py-1 bg-white text-[#1e4d40] text-[10px] md:text-xs rounded-full border border-[#4a90a0]/20">
                <i class="fas fa-check text-[#4a90a0] mr-1 text-[9px] md:text-[10px]"></i> ${amenity}
            </span>
        `).join('');

        document.getElementById('featuredMainImage').src = data.images[0];

        const thumbContainer = document.getElementById('featuredThumbnails');
        thumbContainer.innerHTML = data.images.map((img, index) => `
            <img src="${img}" class="featured-thumb ${index === 0 ? 'active' : ''}" onclick="changeFeaturedImage(this, '${img}', ${index})">
        `).join('');

        const modal = document.getElementById('featuredModal');
        modal.classList.remove('hidden');
        setTimeout(() => { modal.classList.add('show'); }, 10);
        document.body.style.overflow = 'hidden';

        startFeaturedSlideshow(data.images);

        // Initialize map with property location
        if (data.lat && data.lng) {
            initFeaturedMap(data.lat, data.lng);
        } else {
            closeFeaturedMap();
        }
    }

    // 5. Close Modal
    function closeFeaturedModal() {
        const modal = document.getElementById('featuredModal');
        modal.classList.remove('show');
        setTimeout(() => { modal.classList.add('hidden'); }, 300);
        document.body.style.overflow = 'auto';
        if (featuredSlideInterval) clearInterval(featuredSlideInterval);
        closeFeaturedMap();
    }

    // 6. Change Image
    function changeFeaturedImage(thumbnail, src, index) {
        const mainImage = document.getElementById('featuredMainImage');
        mainImage.style.opacity = '0';
        setTimeout(() => {
            mainImage.src = src;
            mainImage.style.opacity = '1';
        }, 200);

        document.querySelectorAll('.featured-thumb').forEach(t => t.classList.remove('active'));
        thumbnail.classList.add('active');
    }

    // 7. Auto Slideshow inside Modal
    function startFeaturedSlideshow(images) {
        if (featuredSlideInterval) clearInterval(featuredSlideInterval);
        
        let currentIndex = 0;
        const activeThumb = document.querySelector('.featured-thumb.active');
        if (activeThumb) {
            const thumbs = Array.from(document.querySelectorAll('.featured-thumb'));
            currentIndex = thumbs.indexOf(activeThumb);
        }

        featuredSlideInterval = setInterval(() => {
            currentIndex = (currentIndex + 1) % images.length;
            const mainImage = document.getElementById('featuredMainImage');
            const thumbs = document.querySelectorAll('.featured-thumb');
            
            if (mainImage) {
                mainImage.style.opacity = '0';
                setTimeout(() => {
                    mainImage.src = images[currentIndex];
                    mainImage.style.opacity = '1';
                }, 200);

                thumbs.forEach((t, i) => {
                    if (i === currentIndex) t.classList.add('active');
                    else t.classList.remove('active');
                });
            }
        }, 4000);
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeFeaturedModal();
    });

    document.getElementById('featuredModal').addEventListener('click', function(e) {
        if (e.target === this) closeFeaturedModal();
    });
</script>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
// Leaflet Map for Featured Modal
    let featuredMap = null;
    let featuredMapMarker = null;
    let featuredUserMarker = null;
    let watchIdFeatured = null;
    let currentFeaturedLat = null;
    let currentFeaturedLng = null;

    function initFeaturedMap(lat, lng) {
        currentFeaturedLat = lat;
        currentFeaturedLng = lng;
        const mapDiv = document.getElementById('featuredMap');
        if (!mapDiv) return;

        if (!featuredMap) {
            featuredMap = L.map('featuredMap', {
                zoomControl: false,
                attributionControl: false,
            }).setView([lat, lng], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
            }).addTo(featuredMap);

            featuredMapMarker = L.marker([lat, lng]).addTo(featuredMap);
        } else {
            featuredMap.setView([lat, lng], 13);
            if (featuredMapMarker) {
                featuredMapMarker.setLatLng([lat, lng]);
            }
            featuredMap.invalidateSize();
        }
    }

    function closeFeaturedMap() {
        if (watchIdFeatured !== null) {
            navigator.geolocation.clearWatch(watchIdFeatured);
            watchIdFeatured = null;
        }
        if (featuredMap) {
            featuredMap.remove();
            featuredMap = null;
            featuredMapMarker = null;
            featuredUserMarker = null;
        }
        currentFeaturedLat = null;
        currentFeaturedLng = null;
    }

    function getFeaturedDirections(lat, lng) {
        lat = lat !== undefined ? lat : currentFeaturedLat;
        lng = lng !== undefined ? lng : currentFeaturedLng;
        if (!lat || !lng) return;
        if (!navigator.geolocation) {
            const mapDiv = document.getElementById('featuredMap');
            mapDiv.innerHTML = '<div class="w-full h-full flex flex-col items-center justify-center text-gray-400"><i class="fas fa-exclamation-triangle text-2xl mb-2"></i><p class="text-xs">Geolocation not supported</p></div>';
            return;
        }
        if (watchIdFeatured !== null) {
            navigator.geolocation.clearWatch(watchIdFeatured);
            watchIdFeatured = null;
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
                            drawFeaturedRoute(userLat, userLng, lat, lng, data.routes[0].geometry.coordinates);
                        }
                    });
            },
            function(error) {
                const mapDiv = document.getElementById('featuredMap');
                if (error.code === error.PERMISSION_DENIED) {
                    mapDiv.innerHTML = '<div class="w-full h-full flex flex-col items-center justify-center text-gray-400"><i class="fas fa-location-slash text-2xl mb-2"></i><p class="text-xs">Location permission denied</p></div>';
                } else {
                    mapDiv.innerHTML = '<div class="w-full h-full flex flex-col items-center justify-center text-gray-400"><i class="fas fa-exclamation-triangle text-2xl mb-2"></i><p class="text-xs">Unable to get your location</p></div>';
                }
            },
            { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
        );

        watchIdFeatured = navigator.geolocation.watchPosition(
            function(position) {
                const userLat = position.coords.latitude;
                const userLng = position.coords.longitude;
                if (featuredUserMarker) {
                    featuredUserMarker.setLatLng([userLat, userLng]).update();
                    featuredMap.panTo([userLat, userLng]);
                } else {
                    featuredUserMarker = L.marker([userLat, userLng]).addTo(featuredMap).bindPopup('Your Location');
                }
            },
            function(error) {
                // Silent on watch errors
            },
            { enableHighAccuracy: true, timeout: 10000, maximumAge: 5000 }
        );
    }

    function drawFeaturedRoute(userLat, userLng, propLat, propLng, coordinates) {
        const mapDiv = document.getElementById('featuredMap');

        if (featuredMap) {
            featuredMap.eachLayer(function(layer) {
                if (layer instanceof L.Marker || layer instanceof L.Polyline) {
                    featuredMap.removeLayer(layer);
                }
            });
        } else {
            mapDiv.innerHTML = '';
            featuredMap = L.map('featuredMap', {
                center: [propLat, propLng],
                zoom: 13,
                zoomControl: false,
                attributionControl: false
            });
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(featuredMap);
        }

        featuredMapMarker = L.marker([propLat, propLng]).addTo(featuredMap).bindPopup('Property Location').openPopup();
        featuredUserMarker = L.marker([userLat, userLng]).addTo(featuredMap).bindPopup('Your Location');

        const routeCoords = coordinates.map(c => [c[1], c[0]]);
        L.polyline(routeCoords, { color: '#4a90a0', weight: 4, opacity: 0.8 }).addTo(featuredMap);

        const bounds = L.latLngBounds(routeCoords.concat([[userLat, userLng], [propLat, propLng]]));
        featuredMap.fitBounds(bounds, { padding: [40, 40] });
    }
</script>







<!-- ===== AGENCIES & PROPERTY MANAGEMENT SECTION ===== -->
<?php
// Fetch agencies from database
$agenciesByCategory = [];
try {
    $stmt = $pdo->query("SELECT * FROM agencies WHERE is_active = 1 ORDER BY category, name");
    while ($row = $stmt->fetch()) {
        $row['tags'] = json_decode($row['tags'], true) ?: [];
        $row['achievements'] = json_decode($row['achievements'], true) ?: [];
        $row['services'] = json_decode($row['services'], true) ?: [];
        $agenciesByCategory[$row['category']][] = $row;
    }
} catch (PDOException $e) {
    $agenciesByCategory = [];
}
$agencyCountAll = count($agenciesByCategory['agency'] ?? []) + count($agenciesByCategory['management'] ?? []) + count($agenciesByCategory['cleaning'] ?? []);
$agencyCountAgency = count($agenciesByCategory['agency'] ?? []);
$agencyCountManagement = count($agenciesByCategory['management'] ?? []);
$agencyCountCleaning = count($agenciesByCategory['cleaning'] ?? []);
?>
<section class="relative py-16 px-3 md:px-6 overflow-hidden" 
         style="background-image: url('https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1600&q=80'); background-size: cover; background-position: center; background-attachment: fixed;">
    
    <!-- Dark overlay for readability -->
    <div class="absolute inset-0 bg-[#00215E]/85 backdrop-blur-sm"></div>

    <div class="relative z-10 max-w-7xl mx-auto">
        
        <!-- Section Heading -->
        <div class="text-center mb-8 md:mb-12">
            <h2 class="font-['Cormorant_Garamond'] text-3xl md:text-5xl text-white font-semibold mb-3 drop-shadow-lg">
                Agencies & Property Management
            </h2>
            <p class="text-[#c4e2ed] text-xs md:text-base max-w-2xl mx-auto drop-shadow">
                Discover trusted real estate agencies, property management firms, and professional cleaning companies. Filter by category to find the right partner.
            </p>
        </div>

        <!-- Main Split Layout: 70% Left / 30% Right -->
        <div class="flex flex-col lg:flex-row gap-4 md:gap-6">

            <!-- ===== RIGHT SIDEBAR (30%) — FILTERS (shown first on mobile) ===== -->
            <div class="lg:w-[30%] w-full order-1 lg:order-2">
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-3 md:p-5 border border-white/20 shadow-2xl lg:sticky lg:top-6">
                    
                    <h3 class="text-white text-sm md:text-lg font-semibold mb-3 md:mb-4 border-b border-white/20 pb-2">
                        <i class="fas fa-filter text-[#F59E0B] mr-2"></i> Filter Companies
                    </h3>

                    <!-- Filter List -->
                    <div class="space-y-2 md:space-y-3">

                        <!-- All -->
                        <button onclick="filterAgencies('all', this)" 
                                class="agency-filter-btn active-filter w-full text-left flex items-center justify-between px-3 py-2 rounded-lg text-[11px] md:text-sm transition-all duration-300">
                            <span><i class="fas fa-layer-group mr-2"></i> All Companies</span>
                            <span class="filter-count"><?php echo $agencyCountAll; ?></span>
                        </button>

                        <!-- Real Estate Agencies -->
                        <button onclick="filterAgencies('agency', this)" 
                                class="agency-filter-btn w-full text-left flex items-center justify-between px-3 py-2 rounded-lg text-[11px] md:text-sm transition-all duration-300">
                            <span><i class="fas fa-handshake mr-2"></i> Real Estate Agencies</span>
                            <span class="filter-count"><?php echo $agencyCountAgency; ?></span>
                        </button>

                        <!-- Property Management -->
                        <button onclick="filterAgencies('management', this)" 
                                class="agency-filter-btn w-full text-left flex items-center justify-between px-3 py-2 rounded-lg text-[11px] md:text-sm transition-all duration-300">
                            <span><i class="fas fa-tasks mr-2"></i> Property Management</span>
                            <span class="filter-count"><?php echo $agencyCountManagement; ?></span>
                        </button>

                        <!-- Cleaning Companies -->
                        <button onclick="filterAgencies('cleaning', this)" 
                                class="agency-filter-btn w-full text-left flex items-center justify-between px-3 py-2 rounded-lg text-[11px] md:text-sm transition-all duration-300">
                            <span><i class="fas fa-spray-can mr-2"></i> Cleaning Companies</span>
                            <span class="filter-count"><?php echo $agencyCountCleaning; ?></span>
                        </button>

                    </div>

                    <!-- Reset Button -->
                    <button onclick="resetAgencyFilter()" 
                            class="mt-4 w-full text-center text-[10px] md:text-xs text-[#c4e2ed] hover:text-white underline transition-colors">
                        Reset Filter
                    </button>

                    <!-- Sidebar Note -->
                    <div class="mt-4 pt-3 border-t border-white/20">
                        
                    </div>
                </div>
            </div>

            <!-- ===== LEFT PANEL (70%) — COMPANY CARDS ===== -->
            <div class="lg:w-[70%] w-full order-2 lg:order-1">
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-3 md:p-5 border border-white/20 shadow-2xl">
                    
                    <!-- Results count / active filter badge -->
                    <div class="flex items-center justify-between mb-3 md:mb-4">
                        <h3 class="text-white text-sm md:text-lg font-semibold">
                            <i class="fas fa-building text-[#F59E0B] mr-2"></i>
                            <span id="agencyResultLabel">All Companies</span>
                        </h3>
                        <span id="agencyCountBadge" class="bg-[#F59E0B] text-[#00215E] text-[10px] md:text-xs font-bold px-2 md:px-3 py-0.5 md:py-1 rounded-full">
                            12 Listed
                        </span>
                    </div>

                    <!-- Company Cards Grid — always 2 columns -->
                    <div id="agencyCardsGrid" class="grid grid-cols-2 gap-2 md:gap-4">
                        <!-- Cards injected via JS -->
                    </div>

                    <a href="/haventra/agencies/" class="view-more-link mt-4 text-[10px] md:text-xs font-bold text-[#059669] hover:text-[#047857] underline hover:underline px-3 py-1.5 bg-white/80 backdrop-blur rounded-lg border border-[#059669]/20 shadow-sm inline-block transition-all w-fit">
                        View more <i class="fas fa-chevron-right ml-1 text-[9px]"></i>
                    </a>

                    <!-- Empty state (hidden by default) -->
                    <div id="agencyEmptyState" class="hidden text-center py-10">
                        <i class="fas fa-search text-4xl text-white/40 mb-3"></i>
                        <p class="text-white/70 text-sm">No companies found for this filter.</p>
                        <button onclick="resetAgencyFilter()" class="mt-3 text-[#F59E0B] text-xs underline">Show all companies</button>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>


<!-- ===== AGENCY PROFILE MODAL ===== -->
<div id="agencyModal" class="fixed inset-0 bg-black/80 backdrop-blur-md z-[3000] hidden items-center justify-center p-2 md:p-4 opacity-0 transition-opacity duration-300">
    <div class="bg-white max-w-3xl w-full max-h-[95vh] md:max-h-[90vh] overflow-y-auto rounded-2xl md:rounded-3xl shadow-2xl relative transform scale-95 transition-transform duration-300" id="agencyModalContent">
        
        <!-- Close -->
        <button onclick="closeAgencyModal()" class="sticky top-2 float-right mr-2 ml-auto w-9 h-9 bg-white rounded-full shadow-lg flex items-center justify-center hover:bg-[#00215E] hover:text-white transition-all z-50 border border-gray-200">
            <i class="fas fa-times text-sm"></i>
        </button>

        <div class="p-4 md:p-8">
            
            <!-- Header: Logo, Name, Category -->
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 mb-6 border-b border-gray-100 pb-6">
                <div class="w-20 h-20 md:w-24 md:h-24 rounded-2xl bg-[#F1F5F9] flex items-center justify-center flex-shrink-0 shadow-inner overflow-hidden" id="agencyModalLogo">
                    <i class="fas fa-building text-3xl md:text-4xl text-[#00215E]"></i>
                </div>
                <div class="text-center sm:text-left flex-1">
                    <span id="agencyModalCategory" class="inline-block bg-[#F59E0B]/10 text-[#F59E0B] text-[10px] md:text-xs font-bold uppercase px-3 py-1 rounded-full mb-2">
                        Real Estate Agency
                    </span>
                    <h2 id="agencyModalName" class="font-['Cormorant_Garamond'] text-2xl md:text-3xl text-[#00215E] font-semibold">
                        Company Name
                    </h2>
                    <p id="agencyModalLocation" class="text-gray-500 text-xs md:text-sm mt-1">
                        <i class="fas fa-map-marker-alt text-[#005BB5] mr-1"></i> Location
                    </p>
                    <div class="flex items-center justify-center sm:justify-start gap-1 mt-2" id="agencyModalRating">
                        <!-- stars -->
                    </div>
                </div>
            </div>

            <!-- Stats / Achievements Row -->
            <div class="grid grid-cols-3 gap-2 md:gap-4 mb-6">
                <div class="bg-[#F8FAFC] rounded-xl p-3 text-center border border-gray-100">
                    <p id="agencyStat1Value" class="text-[#00215E] font-bold text-lg md:text-2xl">15+</p>
                    <p id="agencyStat1Label" class="text-gray-500 text-[9px] md:text-[10px] uppercase tracking-wide">Years Exp.</p>
                </div>
                <div class="bg-[#F8FAFC] rounded-xl p-3 text-center border border-gray-100">
                    <p id="agencyStat2Value" class="text-[#00215E] font-bold text-lg md:text-2xl">500+</p>
                    <p id="agencyStat2Label" class="text-gray-500 text-[9px] md:text-[10px] uppercase tracking-wide">Properties</p>
                </div>
                <div class="bg-[#F8FAFC] rounded-xl p-3 text-center border border-gray-100">
                    <p id="agencyStat3Value" class="text-[#00215E] font-bold text-lg md:text-2xl">98%</p>
                    <p id="agencyStat3Label" class="text-gray-500 text-[9px] md:text-[10px] uppercase tracking-wide">Satisfaction</p>
                </div>
            </div>

            <!-- About -->
            <div class="mb-6">
                <h3 class="font-['Cormorant_Garamond'] text-xl text-[#00215E] font-semibold mb-2">
                    <i class="fas fa-info-circle text-[#F59E0B] mr-2"></i> About
                </h3>
                <p id="agencyModalAbout" class="text-gray-600 text-xs md:text-sm leading-relaxed">
                    Company description...
                </p>
            </div>

            <!-- Achievements List -->
            <div class="mb-6">
                <h3 class="font-['Cormorant_Garamond'] text-xl text-[#00215E] font-semibold mb-2">
                    <i class="fas fa-trophy text-[#F59E0B] mr-2"></i> Key Achievements
                </h3>
                <ul id="agencyModalAchievements" class="space-y-2">
                    <!-- injected -->
                </ul>
            </div>

            <!-- Services Tags -->
            <div class="mb-6">
                <h3 class="font-['Cormorant_Garamond'] text-xl text-[#00215E] font-semibold mb-2">
                    <i class="fas fa-concierge-bell text-[#F59E0B] mr-2"></i> Services Offered
                </h3>
                <div id="agencyModalServices" class="flex flex-wrap gap-2">
                    <!-- injected -->
                </div>
            </div>

            <!-- Contact Buttons -->
            <div class="flex gap-2 md:gap-3 pt-4 border-t border-gray-100">
                <button class="flex-1 py-2.5 md:py-3 bg-[#00215E] text-white text-xs md:text-sm rounded-lg font-medium hover:bg-[#005BB5] transition-colors shadow-lg">
                    <i class="fas fa-phone-alt mr-2"></i> Contact
                </button>
                <button class="flex-1 py-2.5 md:py-3 border border-[#00215E] text-[#00215E] text-xs md:text-sm rounded-lg font-medium hover:bg-[#00215E] hover:text-white transition-colors">
                    <i class="fas fa-envelope mr-2"></i> Message
                </button>
                <button class="w-10 h-10 md:w-12 md:h-12 flex items-center justify-center border border-[#00215E]/30 rounded-lg text-[#00215E] hover:bg-[#00215E] hover:text-white transition-colors">
                    <i class="fas fa-bookmark"></i>
                </button>
            </div>

        </div>
    </div>
</div>


<!-- ===== STYLES FOR AGENCY SECTION ===== -->
<style>
    /* Filter buttons */
    .agency-filter-btn {
        color: rgba(255, 255, 255, 0.75);
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .agency-filter-btn:hover {
        background: rgba(255, 255, 255, 0.15);
        color: #ffffff;
    }
    .agency-filter-btn.active-filter {
        background: #F59E0B;
        color: #00215E;
        border-color: #F59E0B;
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
    }
    .agency-filter-btn.active-filter .filter-count {
        background: #00215E;
        color: #F59E0B;
    }
    .filter-count {
        background: rgba(255, 255, 255, 0.15);
        color: #ffffff;
        font-size: 9px;
        padding: 1px 7px;
        border-radius: 999px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    @media (min-width: 768px) {
        .filter-count { font-size: 11px; padding: 2px 9px; }
    }

    /* Company card */
    .agency-card {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 12px;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.35s cubic-bezier(0.25, 0.8, 0.25, 1);
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.08);
    }
    @media (min-width: 768px) {
        .agency-card { border-radius: 16px; }
    }
    .agency-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 30px -5px rgba(0, 0, 0, 0.3);
        border-color: #F59E0B;
    }
    .agency-card-logo {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: #F1F5F9;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #00215E;
        font-size: 1rem;
        flex-shrink: 0;
        transition: all 0.3s ease;
        overflow: hidden;
    }
    .agency-card-logo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    @media (min-width: 768px) {
        .agency-card-logo { width: 52px; height: 52px; font-size: 1.3rem; border-radius: 12px; }
    }
    .agency-card:hover .agency-card-logo {
        background: #00215E;
        color: #F59E0B;
        transform: rotate(-5deg) scale(1.05);
    }
    .agency-card-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 700;
        color: #00215E;
        font-size: 0.7rem;
        line-height: 1.3;
    }
    @media (min-width: 768px) {
        .agency-card-title { font-size: 0.95rem; }
    }
    .agency-card-desc {
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: #64748B;
        font-size: 0.55rem;
        line-height: 1.4;
    }
    @media (min-width: 768px) {
        .agency-card-desc { font-size: 0.75rem; }
    }
    .agency-tag {
        font-size: 0.5rem;
        padding: 1px 6px;
        border-radius: 999px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }
    @media (min-width: 768px) {
        .agency-tag { font-size: 0.6rem; padding: 2px 8px; }
    }

    /* Modal visibility */
    #agencyModal.show {
        display: flex;
        opacity: 1;
    }
    #agencyModal.show #agencyModalContent {
        transform: scale(1);
    }

    /* Star rating */
    .star-filled { color: #F59E0B; }
    .star-empty { color: #D1D5DB; }

    /* Hide scrollbar for modal */
    #agencyModalContent::-webkit-scrollbar { width: 6px; }
    #agencyModalContent::-webkit-scrollbar-track { background: transparent; }
    #agencyModalContent::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
</style>


<!-- ===== JAVASCRIPT FOR AGENCY SECTION ===== -->
<script>
(function() {
    // ------------------------------------------------------------
    // MOCK DATA — 12 companies (4 per category)
    // ------------------------------------------------------------
    // Agency Data from Database
    // ------------------------------------------------------------
    const agencyData = <?php
    $jsAgencies = [];
    foreach ($agenciesByCategory as $category => $cats) {
        foreach ($cats as $row) {
            $jsAgencies[] = [
                'id' => 'agency-' . $row['id'],
                'category' => $row['category'],
                'categoryLabel' => $row['category_label'],
                'name' => $row['name'],
                'location' => $row['location'],
                'rating' => (int)$row['rating'],
                'yearsExp' => $row['years_label'],
                'properties' => $row['clients'],
                'satisfaction' => $row['satisfaction'],
                'shortDesc' => $row['short_desc'],
                'about' => $row['about'],
                'achievements' => $row['achievements'],
                'services' => $row['services'],
                'icon' => $row['icon'],
                'logo' => $row['logo'],
                'phone' => $row['phone'],
                'email' => $row['email'],
                'whatsapp' => $row['whatsapp'],
                'website' => $row['website'],
                'lat' => (float)$row['lat'],
                'lng' => (float)$row['lng'],
            ];
        }
    }
    echo json_encode($jsAgencies, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_SLASHES);
    ?>;

    // ------------------------------------------------------------
    // STATE
    // ------------------------------------------------------------
    let activeAgencyFilter = 'all';

    // ------------------------------------------------------------
    // RENDER CARDS
    // ------------------------------------------------------------
    function renderAgencyCards(filter) {
        const grid = document.getElementById('agencyCardsGrid');
        const emptyState = document.getElementById('agencyEmptyState');
        const label = document.getElementById('agencyResultLabel');
        const countBadge = document.getElementById('agencyCountBadge');

        let filtered = filter === 'all' 
            ? agencyData 
            : agencyData.filter(c => c.category === filter);

        // Update label and count
        const labelMap = {
            all: 'All Companies',
            agency: 'Real Estate Agencies',
            management: 'Property Management',
            cleaning: 'Cleaning Companies'
        };
        label.textContent = labelMap[filter] || 'All Companies';
        countBadge.textContent = filtered.length + ' Listed';

        if (filtered.length === 0) {
            grid.innerHTML = '';
            emptyState.classList.remove('hidden');
            return;
        }
        emptyState.classList.add('hidden');

        // Category color mapping
        const catColors = {
            agency: { bg: 'bg-[#F59E0B]/10', text: 'text-[#F59E0B]', border: 'border-[#F59E0B]/20' },
            management: { bg: 'bg-[#005BB5]/10', text: 'text-[#005BB5]', border: 'border-[#005BB5]/20' },
            cleaning: { bg: 'bg-[#059669]/10', text: 'text-[#059669]', border: 'border-[#059669]/20' }
        };

        grid.innerHTML = filtered.map(company => {
            const colors = catColors[company.category] || catColors.agency;
            return `
                <div class="agency-card group" onclick="openAgencyModal('${company.id}')">
                    <div class="p-2 md:p-4">
                        <!-- Top row: logo + name -->
                        <div class="flex items-start gap-2 md:gap-3 mb-2">
                            <div class="agency-card-logo">
                                ${company.logo ? `<img src="${company.logo}" alt="${company.name} logo" class="w-full h-full object-cover rounded-lg">` : `<i class="fas ${company.icon}"></i>`}
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="agency-card-title truncate">${company.name}</h4>
                                <p class="text-[9px] md:text-xs text-gray-400 truncate">
                                    <i class="fas fa-map-marker-alt text-[#005BB5] mr-0.5"></i>${company.location}
                                </p>
                            </div>
                        </div>

                        <!-- Description -->
                        <p class="agency-card-desc mb-2 md:mb-3 line-clamp-2">${company.shortDesc}</p>

                        <!-- Bottom row: category tag + rating -->
                        <div class="flex items-center justify-between">
                            <span class="agency-tag ${colors.bg} ${colors.text} border ${colors.border}">${company.categoryLabel}</span>
                            <span class="text-[9px] md:text-xs text-[#F59E0B]">
                                ${'<i class="fas fa-star"></i>'.repeat(company.rating)}${'<i class="far fa-star"></i>'.repeat(5 - company.rating)}
                            </span>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }

    // ------------------------------------------------------------
    // FILTER
    // ------------------------------------------------------------
    window.filterAgencies = function(filter, btn) {
        activeAgencyFilter = filter;

        // Update active button styling
        document.querySelectorAll('.agency-filter-btn').forEach(b => b.classList.remove('active-filter'));
        if (btn) btn.classList.add('active-filter');

        renderAgencyCards(filter);
    };

    window.resetAgencyFilter = function() {
        activeAgencyFilter = 'all';
        document.querySelectorAll('.agency-filter-btn').forEach(b => b.classList.remove('active-filter'));
        // Activate "All" button (first one)
        const allBtn = document.querySelector('.agency-filter-btn');
        if (allBtn) allBtn.classList.add('active-filter');
        renderAgencyCards('all');
    };

    // ------------------------------------------------------------
    // MODAL
    // ------------------------------------------------------------
    window.openAgencyModal = function(id) {
        const company = agencyData.find(c => c.id === id);
        if (!company) return;

        // Populate modal
        document.getElementById('agencyModalName').textContent = company.name;
        document.getElementById('agencyModalLocation').innerHTML = `<i class="fas fa-map-marker-alt text-[#005BB5] mr-1"></i> ${company.location}`;
        document.getElementById('agencyModalCategory').textContent = company.categoryLabel;
        document.getElementById('agencyModalAbout').textContent = company.about;

        // Logo icon
        document.getElementById('agencyModalLogo').innerHTML = company.logo
            ? `<img src="${company.logo}" alt="${company.name} logo" class="w-full h-full object-cover rounded-lg">`
            : `<i class="fas ${company.icon} text-3xl md:text-4xl text-[#00215E]"></i>`;

        // Stats
        document.getElementById('agencyStat1Value').textContent = company.yearsExp;
        document.getElementById('agencyStat1Label').textContent = 'Years Experience';
        document.getElementById('agencyStat2Value').textContent = company.properties;
        document.getElementById('agencyStat2Label').textContent = 'Properties Handled';
        document.getElementById('agencyStat3Value').textContent = company.satisfaction;
        document.getElementById('agencyStat3Label').textContent = 'Satisfaction Rate';

        // Rating stars
        const ratingContainer = document.getElementById('agencyModalRating');
        ratingContainer.innerHTML = 
            `<span class="text-[#F59E0B] text-sm md:text-base">${'<i class="fas fa-star"></i>'.repeat(company.rating)}${'<i class="far fa-star"></i>'.repeat(5 - company.rating)}</span>
             <span class="text-gray-400 text-[10px] md:text-xs ml-1">(${company.rating}.0)</span>`;

        // Achievements
        const achList = document.getElementById('agencyModalAchievements');
        achList.innerHTML = company.achievements.map(a => `
            <li class="flex items-start gap-2 text-gray-600 text-xs md:text-sm">
                <i class="fas fa-check-circle text-[#059669] mt-0.5 text-[10px] md:text-xs"></i>
                <span>${a}</span>
            </li>
        `).join('');

        // Services
        const servicesContainer = document.getElementById('agencyModalServices');
        servicesContainer.innerHTML = company.services.map(s => `
            <span class="px-2 py-1 md:px-3 md:py-1.5 bg-[#F8FAFC] text-[#00215E] text-[10px] md:text-xs rounded-full border border-gray-200 font-medium">
                ${s}
            </span>
        `).join('');

        // Show modal
        const modal = document.getElementById('agencyModal');
        modal.classList.remove('hidden');
        setTimeout(() => { modal.classList.add('show'); }, 10);
        document.body.style.overflow = 'hidden';
    };

    window.closeAgencyModal = function() {
        const modal = document.getElementById('agencyModal');
        modal.classList.remove('show');
        setTimeout(() => { modal.classList.add('hidden'); }, 300);
        document.body.style.overflow = 'auto';
    };

    // Close modal on backdrop click
    document.getElementById('agencyModal').addEventListener('click', function(e) {
        if (e.target === this) closeAgencyModal();
    });

    // Close modal on Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeAgencyModal();
    });

    // ------------------------------------------------------------
    // INIT
    // ------------------------------------------------------------
    document.addEventListener('DOMContentLoaded', function() {
        renderAgencyCards('all');
        // Activate "All" button by default
        const allBtn = document.querySelector('.agency-filter-btn');
        if (allBtn) allBtn.classList.add('active-filter');
    });

})();
</script>
