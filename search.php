<?php
// Page-specific SEO variables for search page
$page_title = 'Search Results | HAVENTRA';
$page_description = 'Search properties, real estate agencies, property management, and cleaning companies on HAVENTRA.';
$page_canonical = 'https://haventra.com/search/';
?>
<?php
$query = $_GET['q'] ?? '';
$query = trim($query);
?>

<!-- ===== SEARCH HERO SECTION ===== -->
<section class="py-16 px-4 md:px-6 bg-[#f5efe8]">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-10 md:mb-14">
            <h1 class="font-['Cormorant_Garamond'] text-4xl md:text-6xl text-[#3f352e] mb-4">
                Search Results
            </h1>
            <p class="text-[#1e4d40] text-lg md:text-xl max-w-2xl mx-auto">
                <?php echo htmlspecialchars($query); ?>
                <?php echo $query ? '— properties, agencies & services found' : '— enter what you are looking for'; ?>
            </p>
        </div>

        <!-- Search Bar -->
        <div class="max-w-2xl mx-auto mb-12">
            <form id="searchForm" class="flex flex-col md:flex-row gap-3 bg-white rounded-2xl shadow-lg p-1 border border-[#4a90a0]/20">
                <input type="text" name="q" id="searchInput" value="<?php echo htmlspecialchars($query); ?>"
                    placeholder="Search properties, agencies, locations..."
                    class="flex-1 px-5 py-3 text-[#3f352e] placeholder-[#6b7280] focus:outline-none focus:ring-2 focus:ring-[#4a90a0] rounded-xl border-none">
                <button type="submit" class="px-6 py-3 bg-[#4a90a0] text-white font-medium rounded-xl hover:bg-[#2d5a4a] transition-colors">
                    Search
                </button>
            </form>
            <?php if ($query): ?>
            <div class="mt-3 flex gap-2 text-xs text-[#6b7280]">
                <span>Showing combined results for properties and agencies</span>
            </div>
            <?php endif; ?>
        </div>

        <!-- Results Summary -->
        <div id="resultsSummary" class="mb-8">
            <?php if ($query): ?>
            <div class="flex items-center justify-between mb-4">
                <span id="resultCount" class="text-[#1e4d40] font-semibold">Loading results...</span>
                <div class="flex gap-2">
                    <a href="?q=<?php echo urlencode($query); ?>&t=property" class="px-3 py-1 bg-white text-[#4a90a0] text-xs rounded-lg border border-[#4a90a0]/20 hover:bg-[#4a90a0]/5">Properties</a>
                    <a href="?q=<?php echo urlencode($query); ?>&t=agency" class="px-3 py-1 bg-white text-[#4a90a0] text-xs rounded-lg border border-[#4a90a0]/20 hover:bg-[#4a90a0]/5">Agencies</a>
                </div>
            </div>
            <?php else: ?>
            <div class="text-center py-20 text-[#6b7280]">
                <i class="fas fa-search text-4xl mb-4 opacity-30"></i>
                <p class="text-lg">Start searching for properties, agencies, or locations</p>
            </div>
            <?php endif; ?>
        </div>

        <!-- Results Grid -->
        <div id="searchResults" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Results injected via JS -->
        </div>

        <!-- Empty State -->
        <div id="searchEmpty" class="hidden text-center py-20">
            <i class="fas fa-search text-4xl text-gray-300 mb-4"></i>
            <h3 class="text-[#3f352e] text-xl font-semibold mb-2">No results found</h3>
            <p class="text-[#6b7280]">Try adjusting your search or browse our featured listings below.</p>
            <a href="/" class="mt-4 inline-block text-[#4a90a0] font-medium hover:underline">Browse Featured Listings</a>
        </div>
    </div>
</section>

<!-- ===== QUICK LINKS TO CATEGORIES ===== -->
<section class="py-12 px-4 md:px-6 bg-white">
    <div class="max-w-7xl mx-auto">
        <h2 class="font-['Cormorant_Garamond'] text-3xl md:text-4xl text-[#3f352e] text-center mb-10">Browse Categories</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <a href="/houses-for-rent/" class="group block p-6 bg-[#f5efe8] rounded-2xl border border-[#4a90a0]/10 hover:border-[#4a90a0]/30 hover:shadow-lg transition-all text-center">
                <i class="fas fa-house text-3xl text-[#4a90a0] mb-3 group-hover:text-[#2d5a4a] transition-colors"></i>
                <h3 class="font-['Cormorant_Garamond'] text-xl text-[#3f352e]">For Rent</h3>
            </a>
            <a href="/apartments-for-sale/" class="group block p-6 bg-[#f5efe8] rounded-2xl border border-[#4a90a0]/10 hover:border-[#4a90a0]/30 hover:shadow-lg transition-all text-center">
                <i class="fas fa-tag text-3xl text-[#F59E0B] mb-3 group-hover:text-[#b45309] transition-colors"></i>
                <h3 class="font-['Cormorant_Garamond'] text-xl text-[#3f352e]">For Sale</h3>
            </a>
            <a href="/moving_services/" class="group block p-6 bg-[#f5efe8] rounded-2xl border border-[#4a90a0]/10 hover:border-[#4a90a0]/30 hover:shadow-lg transition-all text-center">
                <i class="fas fa-plane text-3xl text-[#005BB5] mb-3 group-hover:text-[#003d7a] transition-colors"></i>
                <h3 class="font-['Cormorant_Garamond'] text-xl text-[#3f352e]">Short Stay</h3>
            </a>
            <a href="/agencies/" class="group block p-6 bg-[#f5efe8] rounded-2xl border border-[#4a90a0]/10 hover:border-[#4a90a0]/30 hover:shadow-lg transition-all text-center">
                <i class="fas fa-building text-3xl text-[#059669] mb-3 group-hover:text-[#047857] transition-colors"></i>
                <h3 class="font-['Cormorant_Garamond'] text-xl text-[#3f352e]">Agencies</h3>
            </a>
        </div>
    </div>
</section>

<script>
// Fetch search results from API
async function fetchSearchResults(q, typeFilter = '') {
    if (!q) {
        document.getElementById('resultsSummary').innerHTML = '';
        document.getElementById('searchResults').innerHTML = '';
        document.getElementById('searchEmpty').classList.remove('hidden');
        return;
    }

    const summary = document.getElementById('resultsSummary');
    const resultsGrid = document.getElementById('searchResults');
    const emptyState = document.getElementById('searchEmpty');

    summary.innerHTML = `
        <div class="flex items-center justify-between mb-4">
            <span id="resultCount" class="text-[#1e4d40] font-semibold">Searching...</span>
            <div class="flex gap-2">
                <a href="?q=${encodeURIComponent(q)}&t=property" class="px-3 py-1 bg-white text-[#4a90a0] text-xs rounded-lg border border-[#4a90a0]/20 hover:bg-[#4a90a0]/5">Properties</a>
                <a href="?q=${encodeURIComponent(q)}&t=agency" class="px-3 py-1 bg-white text-[#4a90a0] text-xs rounded-lg border border-[#4a90a0]/20 hover:bg-[#4a90a0]/5">Agencies</a>
            </div>
        </div>
    `;
    resultsGrid.innerHTML = '';
    emptyState.classList.add('hidden');

    try {
        const res = await fetch(`/api/search.php?q=${encodeURIComponent(q)}`);
        const data = await res.json();
        const results = data.results.filter(r => typeFilter ? r.type === typeFilter : true);

        document.getElementById('resultCount').textContent = results.length + ' result' + (results.length !== 1 ? 's' : '');

        if (results.length === 0) {
            emptyState.classList.remove('hidden');
            return;
        }

        resultsGrid.innerHTML = results.map(item => {
            if (item.type === 'property') {
                return `
                    <div class="bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-md transition-shadow group">
                        <div class="relative h-48 overflow-hidden">
                            <img src="${item.image}" alt="${item.title}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute top-3 left-3 bg-white/90 backdrop-blur px-3 py-1 rounded-full text-xs font-semibold text-[#3f352e]">${item.badge}</div>
                        </div>
                        <div class="p-5">
                            <h3 class="font-['Cormorant_Garamond'] text-xl text-[#3f352e] mb-1">${item.title}</h3>
                            <p class="text-[#1e4d40] text-xs mb-3"><i class="fas fa-map-marker-alt text-[#4a90a0]"></i> ${item.location}</p>
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-[#4a90a0] font-bold text-lg">${item.price}</span>
                                <span class="text-xs text-gray-500">${item.category}</span>
                            </div>
                            <a href="/house/?id=${item.id.replace('prop-', '')}" class="w-full py-2.5 bg-[#4a90a0] text-white text-center rounded-xl text-sm font-medium hover:bg-[#2d5a4a]">View Property</a>
                        </div>
                    </div>
                `;
            } else {
                const stars = '<i class="fas fa-star text-[#F59E0B]"></i>'.repeat(item.rating || 5) + '<i class="far fa-star text-[#F59E0B]"></i>'.repeat(5 - (item.rating || 5));
                return `
                    <div class="bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-md transition-shadow group">
                        <div class="p-5">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center">
                                    <i class="fas ${item.icon || 'fa-building'} text-[#00215E]"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-['Cormorant_Garamond'] text-xl text-[#3f352e] mb-1">${item.title}</h3>
                                    <p class="text-xs text-[#4a90a0] font-semibold uppercase">${item.category}</p>
                                    <div class="flex items-center gap-1 mt-1">
                                        ${stars}
                                        <span class="text-xs text-gray-500 ml-1">${item.rating || 5}.0</span>
                                    </div>
                                </div>
                            </div>
                            <p class="text-[#1e4d40] text-xs mb-3"><i class="fas fa-map-marker-alt text-[#4a90a0]"></i> ${item.location}</p>
                            <p class="text-[#6b7280] text-[11px] mb-4 line-clamp-2">${item.description}</p>
                            <div class="flex gap-2">
                                <a href="/agencies/" class="flex-1 py-2.5 bg-[#4a90a0] text-white text-center rounded-xl text-sm font-medium hover:bg-[#2d5a4a]">Contact</a>
                                <button class="w-10 h-10 bg-gray-100 text-[#3f352e] rounded-xl flex items-center justify-center hover:bg-[#4a90a0] hover:text-white transition-colors" onclick="window.open('https://www.google.com/maps/dir/?api=1&destination=${item.lat},${item.lng}', '_blank')">
                                    <i class="fas fa-route text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            }
        }).join('');
    } catch (e) {
        document.getElementById('resultCount').textContent = 'Error loading results';
        emptyState.classList.remove('hidden');
    }
}

// Initialize
const urlParams = new URLSearchParams(window.location.search);
const q = urlParams.get('q') || '';
const typeFilter = urlParams.get('t') || '';

document.getElementById('searchInput').value = q;
if (q) {
    fetchSearchResults(q, typeFilter);
}

// Handle form submit
document.getElementById('searchForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const input = document.getElementById('searchInput').value.trim();
    const type = document.querySelector('.border-[#4a90a0]/20') ? '' : '';
    window.location.href = '/search/?q=' + encodeURIComponent(input);
});
</script>

<style>
    #searchResults .group:hover img {
        transform: scale(1.03);
    }
</style>
