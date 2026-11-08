<?php
// marketplace.php - Real Estate Marketplace (all properties + agencies, DB-backed)
$page_title = 'Real Estate Marketplace | HAVENTRA';
$page_description = 'Explore HAVENTRA’s real estate marketplace: houses for rent, short-stay bookings, properties for sale, and verified agencies/companies — all in one place with fast filters.';
$page_canonical = 'https://haventra.com/marketplace/';

$q = trim((string)($_GET['q'] ?? $_GET['search'] ?? ''));
$kind = strtolower(trim((string)($_GET['kind'] ?? 'all'))); // all|property|agency
$pt = strtolower(trim((string)($_GET['property_type'] ?? $_GET['type'] ?? 'all'))); // all|rent|booking|sale
$ac = strtolower(trim((string)($_GET['agency_category'] ?? $_GET['category'] ?? 'all'))); // all|agency|management|cleaning
$acMap = ['agency'=>'agency','real-estate'=>'agency','real-estate-agencies'=>'agency','management'=>'management','property-management'=>'management','property-management-companies'=>'management','cleaning'=>'cleaning','cleaning-agencies'=>'cleaning','cleaning-companies'=>'cleaning'];
if (isset($acMap[$ac])) $ac = $acMap[$ac];
if (!in_array($kind,['all','property','agency'],true)) $kind='all';
if (!in_array($pt,['all','rent','booking','sale'],true)) $pt='all';
if (!in_array($ac,['all','agency','management','cleaning'],true)) $ac='all';

$iconMap = [
  'wifi'=>'fa-wifi','wi-fi'=>'fa-wifi','free wifi'=>'fa-wifi','pool'=>'fa-swimming-pool','swimming pool'=>'fa-swimming-pool',
  'parking'=>'fa-parking','security'=>'fa-shield-alt','24/7 security'=>'fa-shield-alt','garden'=>'fa-tree','borehole'=>'fa-water',
  'air conditioning'=>'fa-snowflake','ac'=>'fa-snowflake','gym'=>'fa-dumbbell','furnished'=>'fa-couch','water'=>'fa-tint',
  'beach'=>'fa-umbrella-beach','beach access'=>'fa-umbrella-beach','kitchen'=>'fa-utensils','kitchenette'=>'fa-utensils','full board'=>'fa-utensils'
];

$items = [];
$stats = ['all'=>0,'property'=>0,'agency'=>0,'rent'=>0,'booking'=>0,'sale'=>0,'agency_co'=>0,'management'=>0,'cleaning'=>0];
$cities = [];
try {
    $rows = $pdo->query("SELECT id,title,location,price,price_suffix,badge,beds,baths,size,type,image_url,image_urls,amenities,description,lat,lng,created_at FROM properties WHERE is_active=1 ORDER BY created_at DESC")->fetchAll();
    foreach ($rows as $r) {
        $images = json_decode($r['image_urls'] ?? '[]', true);
        if (!is_array($images) || !$images) $images = array_filter([$r['image_url'] ?? '']);
        $amen = json_decode($r['amenities'] ?? '[]', true); if (!is_array($amen)) $amen = [];
        $amenOut=[]; foreach($amen as $a){ $k=strtolower(trim((string)$a)); $amenOut[]=['icon'=>$iconMap[$k]??'fa-check','label'=>(string)$a]; }
        $ptype = (string)$r['type']; // rent|booking|sale
        $ptypeLabel = $ptype==='booking'?'Short Stay':($ptype==='sale'?'For Sale':'For Rent');
        $url = $ptype==='booking'?'/haventra/airbnb/':($ptype==='sale'?'/haventra/houses-for-sale/':'/haventra/houses-for-rent/');
        $city = trim(explode(',', (string)$r['location'])[0] ?? ''); if($city!=='') $cities[$city]=($cities[$city]??0)+1;
        $stats['property']++; $stats['all']++; $stats[$ptype] = ($stats[$ptype]??0)+1;
        $items[] = [
          'kind'=>'property','id'=>'prop-'.$r['id'],'dbId'=>(int)$r['id'],'title'=>(string)$r['title'],'location'=>(string)$r['location'],'city'=>$city,
          'price'=>(int)str_replace([',',' '],'',(string)$r['price']),'priceText'=>'KSh '.(string)$r['price'].($r['price_suffix']?' '.(string)$r['price_suffix']:''),
          'badge'=>(string)($r['badge']?:$ptypeLabel),'propertyType'=>$ptype,'propertyTypeLabel'=>$ptypeLabel,'beds'=>(int)$r['beds'],'baths'=>(int)$r['baths'],
          'size'=>(string)($r['size']??''),'image'=>(string)($images[0]??''),'images'=>array_values($images),'amenities'=>$amenOut,
          'desc'=>(string)($r['description']??''),'lat'=>$r['lat']!==null?(float)$r['lat']:null,'lng'=>$r['lng']!==null?(float)$r['lng']:null,'url'=>$url
        ];
    }
} catch (Throwable $e) {}
try {
    $rows = $pdo->query("SELECT id,name,category,category_label,location,city,rating,reviews,verified,years,years_label,clients,satisfaction,icon,logo,short_desc,about,services,phone,email,whatsapp,website FROM agencies WHERE is_active=1 ORDER BY rating DESC, years DESC, name ASC")->fetchAll();
    foreach ($rows as $r) {
        $svc = json_decode($r['services'] ?? '[]', true); if (!is_array($svc)) $svc=[];
        $city = trim((string)$r['city']); if($city!=='') $cities[$city]=($cities[$city]??0)+1;
        $cat=(string)$r['category']; $stats['agency']++; $stats['all']++; if($cat==='agency') $stats['agency_co']++; elseif(isset($stats[$cat])) $stats[$cat]++;
        $items[] = [
          'kind'=>'agency','id'=>'agency-'.$r['id'],'dbId'=>(int)$r['id'],'title'=>(string)$r['name'],'location'=>(string)$r['location'],'city'=>$city,
          'agencyCategory'=>$cat,'agencyCategoryLabel'=>(string)$r['category_label'],'rating'=>(float)$r['rating'],'reviews'=>(int)$r['reviews'],
          'verified'=>(bool)$r['verified'],'years'=>(int)$r['years'],'yearsLabel'=>(string)$r['years_label'],'clients'=>(string)$r['clients'],
          'satisfaction'=>(string)$r['satisfaction'],'icon'=>(string)($r['icon']?:'fa-building'),'image'=>(string)($r['logo']??''),
          'desc'=>(string)($r['short_desc']??''),'about'=>(string)($r['about']??''),'services'=>$svc,'phone'=>(string)$r['phone'],'email'=>(string)$r['email'],
          'whatsapp'=>(string)$r['whatsapp'],'website'=>(string)$r['website'],'url'=>'/haventra/agencies/?q='.rawurlencode((string)$r['name']).'&category='.rawurlencode($cat)
        ];
    }
} catch (Throwable $e) {}
ksort($cities);
$initial = ['q'=>$q,'kind'=>$kind,'propertyType'=>$pt,'agencyCategory'=>$ac];
?>
<?php
$base = (defined('APP_URL') ? rtrim(APP_URL,'/') : 'https://haventra.com');
$canon = $base . '/marketplace/';
$seoTitle = 'Real Estate Marketplace | Houses for Rent, Sale, Short Stay & Agencies | HAVENTRA';
$seoDesc = 'Browse HAVENTRA’s real estate marketplace: houses and apartments for rent, short-stay bookings, properties for sale, and verified real estate agencies, property management, and cleaning companies. Filter by location, type, and price.';
$page_title = $page_title ?: $seoTitle; $page_description = $page_description ?: $seoDesc; $page_canonical = $canon;
$ldItems = [];
$pos = 0;
foreach ($items as $it) {
  $pos++; $u = $it['url']; if ($u && strpos($u,'http')!==0) $u = $base . $u;
  if ($it['kind']==='property') {
    $ld = ['@type'=>'ListItem','position'=>$pos,'url'=>$u,'item'=>['@type'=>'RealEstateListing','name'=>$it['title'],'url'=>$u,'description'=>$it['desc'] ?: ($it['propertyTypeLabel'].' in '.$it['location']),'address'=>['@type'=>'PostalAddress','addressLocality'=>$it['city']?:$it['location'],'addressCountry'=>'KE']]];
    if (!empty($it['image'])) $ld['item']['image']=$it['image'];
    if (!empty($it['price'])) $ld['item']['offers']=['@type'=>'Offer','price'=>$it['price'],'priceCurrency'=>'KES'];
    $ldItems[] = $ld;
  } else {
    $ld = ['@type'=>'ListItem','position'=>$pos,'url'=>$u,'item'=>['@type'=>($it['agencyCategory']==='cleaning'?'LocalBusiness':'RealEstateAgent'),'name'=>$it['title'],'url'=>$u,'description'=>$it['desc'] ?: $it['agencyCategoryLabel'],'address'=>['@type'=>'PostalAddress','addressLocality'=>$it['city']?:$it['location'],'addressCountry'=>'KE']]];
    if (!empty($it['phone'])) $ld['item']['telephone']=$it['phone'];
    if (!empty($it['image'])) $ld['item']['image']=$it['image'];
    if (!empty($it['rating'])) $ld['item']['aggregateRating']=['@type'=>'AggregateRating','ratingValue'=>$it['rating'],'reviewCount'=>max(1,(int)$it['reviews'])];
    $ldItems[] = $ld;
  }
}
$graph = [
  ['@context'=>'https://schema.org','@type'=>'BreadcrumbList','itemListElement'=>[
    ['@type'=>'ListItem','position'=>1,'name'=>'Home','item'=>$base.'/'],
    ['@type'=>'ListItem','position'=>2,'name'=>'Real Estate Marketplace','item'=>$canon],
  ]],
  ['@context'=>'https://schema.org','@type'=>'CollectionPage','name'=>$seoTitle,'description'=>$seoDesc,'url'=>$canon,'isPartOf'=>['@type'=>'WebSite','name'=>'HAVENTRA','url'=>$base.'/'],'mainEntity'=>['@type'=>'ItemList','name'=>'HAVENTRA Real Estate Marketplace Listings','numberOfItems'=>count($items),'itemListElement'=>$ldItems]],
  ['@context'=>'https://schema.org','@type'=>'WebSite','name'=>'HAVENTRA','url'=>$base.'/','potentialAction'=>['@type'=>'SearchAction','target'=>$base.'/marketplace/?q={search_term_string}','query-input'=>'required name=search_term_string']],
];
?>
<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
<meta name="keywords" content="real estate marketplace, houses for rent, apartments for rent, houses for sale, short stay, Airbnb, booking, property for sale, real estate agencies, property management companies, cleaning companies, Kenya property, HAVENTRA">
<script type="application/ld+json"><?php echo json_encode($graph, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_HEX_TAG); ?></script>
<style>
  .mkt-hero{background:radial-gradient(1200px 400px at 10% -10%,rgba(245,158,11,.25),transparent),linear-gradient(135deg,#001540 0%,#00215E 45%,#0f766e 100%)}
  .glass{background:rgba(255,255,255,.08);backdrop-filter:blur(10px);-webkit-backdrop-filter:blur(10px);border:1px solid rgba(255,255,255,.18)}
  .mkt-chip{padding:8px 14px;border-radius:9999px;font-size:.75rem;font-weight:700;border:1px solid #e5e7eb;background:#fff;color:#374151 !important;transition:.2s;cursor:pointer;white-space:nowrap}
  .mkt-chip:hover{border-color:#005BB5;color:#005BB5 !important}
  .mkt-chip.active{background:linear-gradient(135deg,#00215E,#005BB5);border-color:transparent;color:#fff !important;box-shadow:0 8px 20px -8px rgba(0,91,181,.7)}
  .mkt-hero .mkt-chip{background:rgba(255,255,255,.14);border-color:rgba(255,255,255,.24);color:#fff !important}
  .mkt-hero .mkt-chip:hover{background:rgba(255,255,255,.24);color:#fff !important}
  .mkt-hero .mkt-chip.active{background:#fff;border-color:#fff;color:#00215E !important}
  .mkt-card{background:#fff;border:1px solid #eef2f7;border-radius:18px;overflow:hidden;transition:.28s;box-shadow:0 8px 24px -18px rgba(0,33,94,.35)}
  .mkt-card:hover{transform:translateY(-4px);box-shadow:0 22px 40px -22px rgba(0,33,94,.45);border-color:#dbeafe}
  .mkt-img{transition:transform .6s ease}.mkt-card:hover .mkt-img{transform:scale(1.06)}
  .line-clamp-1{display:-webkit-box;-webkit-line-clamp:1;-webkit-box-orient:vertical;overflow:hidden}
  .line-clamp-2{display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
  .skeleton{background:linear-gradient(90deg,#f1f5f9 25%,#e2e8f0 37%,#f1f5f9 63%);background-size:400% 100%;animation:sk 1.2s ease infinite}
  @keyframes sk{0%{background-position:100% 50%}100%{background-position:0 50%}}
</style>

<section class="mkt-hero relative px-4 md:px-6 pt-8 pb-14 md:pt-12 md:pb-20 overflow-hidden">
  <div class="max-w-7xl mx-auto relative z-10">
    <div class="text-center mb-6 md:mb-8">
      <span class="inline-flex items-center gap-2 glass text-white/95 text-[10px] md:text-xs font-bold uppercase tracking-[.18em] px-4 py-2 rounded-full"><i class="fas fa-store text-amber-300"></i> Haventra Marketplace</span>
      <h1 class="font-serif text-3xl md:text-6xl text-white font-semibold leading-tight mt-4">All real estate, <span class="italic text-amber-300">one marketplace</span></h1>
      <p class="text-white/80 text-sm md:text-base max-w-3xl mx-auto mt-3">Browse houses for rent, short-stay bookings, properties for sale, and verified agencies/companies — with fast filters and instant results.</p>
    </div>

    <div class="max-w-4xl mx-auto">
      <form id="mktSearchForm" class="bg-white rounded-2xl md:rounded-full shadow-2xl shadow-black/20 p-2 md:p-2.5 flex flex-col md:flex-row gap-2">
        <div class="flex-1 flex items-center gap-2 px-3 md:px-4 py-2 rounded-xl md:rounded-full hover:bg-gray-50 transition">
          <i class="fas fa-search text-[#005BB5]"></i>
          <input id="mktSearch" type="text" value="<?php echo htmlspecialchars($q,ENT_QUOTES); ?>" placeholder="Search properties, locations, agencies, services..." class="w-full bg-transparent outline-none text-sm text-gray-800 placeholder:text-gray-400" autocomplete="off">
        </div>
        <div class="flex gap-2">
          <select id="mktKind" class="px-3 py-2.5 rounded-xl md:rounded-full border border-gray-200 text-sm font-semibold text-gray-700 bg-gray-50 md:bg-white">
            <option value="all">Everything</option>
            <option value="property">Properties</option>
            <option value="agency">Agencies & Companies</option>
          </select>
          <button type="submit" class="px-5 md:px-7 py-2.5 rounded-xl md:rounded-full text-sm font-bold text-white bg-gradient-to-r from-[#00215E] to-[#005BB5] hover:opacity-95 shadow-lg shadow-blue-900/20">Search</button>
        </div>
      </form>
      <div class="flex flex-wrap justify-center gap-2 mt-4">
        <button class="mkt-chip !bg-white/12 !border-white/20 !text-white hover:!bg-white/20" data-quick="all">All (<?php echo (int)$stats['all']; ?>)</button>
        <button class="mkt-chip !bg-white/12 !border-white/20 !text-white hover:!bg-white/20" data-quick="rent">For Rent (<?php echo (int)$stats['rent']; ?>)</button>
        <button class="mkt-chip !bg-white/12 !border-white/20 !text-white hover:!bg-white/20" data-quick="booking">Short Stay (<?php echo (int)$stats['booking']; ?>)</button>
        <button class="mkt-chip !bg-white/12 !border-white/20 !text-white hover:!bg-white/20" data-quick="sale">For Sale (<?php echo (int)$stats['sale']; ?>)</button>
        <button class="mkt-chip !bg-white/12 !border-white/20 !text-white hover:!bg-white/20" data-quick="agency">Agencies (<?php echo (int)$stats['agency']; ?>)</button>
      </div>
    </div>
  </div>
</section>

<section class="relative -mt-7 md:-mt-10 pb-16 px-4 md:px-6">
  <div class="max-w-7xl mx-auto">
    <div class="flex flex-col lg:flex-row gap-5 md:gap-6">
      <aside class="lg:w-1/4 w-full">
        <div class="lg:sticky lg:top-6 space-y-4">
          <div class="bg-white rounded-2xl border border-gray-100 shadow-lg shadow-gray-200/40 p-4 md:p-5">
            <div class="flex items-center justify-between gap-2 mb-3 lg:mb-4">
              <button id="mktFilterToggle" type="button" aria-controls="mktFilterBody" aria-expanded="false" class="flex-1 flex items-center justify-between gap-2 text-left lg:pointer-events-none">
                <h3 class="font-serif text-lg text-navy font-semibold"><i class="fas fa-sliders-h text-[#005BB5] mr-1.5"></i>Filters <span id="mktFilterCount" class="hidden ml-1 text-[10px] font-bold px-1.5 py-0.5 rounded-full bg-[#005BB5] text-white align-middle">0</span></h3>
                <i id="mktFilterChevron" class="fas fa-chevron-down text-xs text-gray-400 transition-transform lg:hidden"></i>
              </button>
              <button id="mktReset" class="text-[10px] font-bold uppercase tracking-wide text-[#005BB5] hover:underline shrink-0">Reset</button>
            </div>
            <div id="mktFilterBody" class="hidden lg:block">
            <div class="mb-4">
              <h4 class="text-[11px] uppercase tracking-wider font-bold text-gray-500 mb-2">Show</h4>
              <div class="grid grid-cols-3 gap-1.5">
                <button class="mkt-chip !px-2 !py-2 !text-[10px]" data-kind="all">All</button>
                <button class="mkt-chip !px-2 !py-2 !text-[10px]" data-kind="property">Homes</button>
                <button class="mkt-chip !px-2 !py-2 !text-[10px]" data-kind="agency">Agencies</button>
              </div>
            </div>
            <div class="mb-4" id="propertyTypeWrap">
              <h4 class="text-[11px] uppercase tracking-wider font-bold text-gray-500 mb-2">Property Type</h4>
              <div class="flex flex-wrap gap-1.5">
                <button class="mkt-chip !py-1.5 !px-3 !text-[10px]" data-ptype="all">All</button>
                <button class="mkt-chip !py-1.5 !px-3 !text-[10px]" data-ptype="rent">Rent</button>
                <button class="mkt-chip !py-1.5 !px-3 !text-[10px]" data-ptype="booking">Short Stay</button>
                <button class="mkt-chip !py-1.5 !px-3 !text-[10px]" data-ptype="sale">Sale</button>
              </div>
            </div>
            <div class="mb-4" id="agencyCatWrap">
              <h4 class="text-[11px] uppercase tracking-wider font-bold text-gray-500 mb-2">Company Type</h4>
              <div class="flex flex-wrap gap-1.5">
                <button class="mkt-chip !py-1.5 !px-3 !text-[10px]" data-acat="all">All</button>
                <button class="mkt-chip !py-1.5 !px-3 !text-[10px]" data-acat="agency">Real Estate</button>
                <button class="mkt-chip !py-1.5 !px-3 !text-[10px]" data-acat="management">Management</button>
                <button class="mkt-chip !py-1.5 !px-3 !text-[10px]" data-acat="cleaning">Cleaning</button>
              </div>
            </div>
            <div class="mb-4">
              <h4 class="text-[11px] uppercase tracking-wider font-bold text-gray-500 mb-2">Location</h4>
              <select id="mktCity" class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm bg-white"><option value="">All locations</option><?php foreach(array_slice(array_keys($cities),0,40) as $c): ?><option value="<?php echo htmlspecialchars($c,ENT_QUOTES); ?>"><?php echo htmlspecialchars($c); ?></option><?php endforeach; ?></select>
            </div>
            <div class="mb-4" id="priceWrap">
              <h4 class="text-[11px] uppercase tracking-wider font-bold text-gray-500 mb-2">Max Price (properties)</h4>
              <input id="mktMaxPrice" type="number" min="0" placeholder="e.g. 5000000" class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm">
            </div>
            <div id="verifiedWrap">
              <label class="flex items-center justify-between cursor-pointer"><span class="text-xs font-semibold text-gray-600"><i class="fas fa-certificate text-amber-500 mr-1"></i>Verified agencies only</span><input id="mktVerified" type="checkbox" class="w-4 h-4 rounded border-gray-300 text-[#005BB5]"></label>
            </div>
            </div>
          </div>
          <div class="hidden lg:block bg-gradient-to-br from-[#00215E] to-[#0f766e] rounded-2xl p-4 text-white shadow-xl"><h4 class="font-serif text-base font-semibold mb-1">Everything in one place</h4><p class="text-xs text-white/80">Compare rentals, short stays, homes for sale, and trusted companies without switching pages.</p></div>
        </div>
      </aside>

      <main class="lg:w-3/4 w-full">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
          <div>
            <h2 class="font-serif text-2xl md:text-3xl text-navy font-semibold"><span id="mktCount">0</span> results</h2>
            <p id="mktSummary" class="text-xs text-gray-500 mt-0.5">Showing marketplace listings</p>
          </div>
          <div class="flex items-center gap-2">
            <label class="text-xs text-gray-500">Sort</label>
            <select id="mktSort" class="px-3 py-2 rounded-xl border border-gray-200 text-xs font-semibold bg-white">
              <option value="recommended">Recommended</option>
              <option value="price-low">Price: Low to High</option>
              <option value="price-high">Price: High to Low</option>
              <option value="rating">Top Rated (agencies)</option>
              <option value="name">Name (A–Z)</option>
            </select>
          </div>
        </div>
        <div id="mktGrid" class="grid grid-cols-2 xl:grid-cols-3 gap-2.5 md:gap-5" aria-live="polite">
<?php
$e = fn($v)=>htmlspecialchars((string)($v??''),ENT_QUOTES,'UTF-8');
foreach ($items as $it) {
  $kind=$it['kind']; $img=(string)$it['image']; $href=(string)$it['url'];
  $data = 'data-mkt="1" data-id="'.$e($it['id']).'" data-kind="'.$e($kind).'" data-ptype="'.$e($it['propertyType']??'').'" data-acat="'.$e($it['agencyCategory']??'').'" data-city="'.$e($it['city']).'" data-price="'.(int)($it['price']??0).'" data-rating="'.(float)($it['rating']??0).'" data-verified="'.(!empty($it['verified'])?'1':'0').'" data-search="'. $e(strtolower(($it['title']??'').' '.($it['location']??'').' '.($it['city']??'').' '.($it['desc']??'').' '.($it['propertyTypeLabel']??'').' '.($it['agencyCategoryLabel']??'').' '.implode(' ', array_map(fn($a)=>is_array($a)?($a['label']??''):$a, $it['amenities']??$it['services']??[])))).'" data-title="'.$e($it['title']).'"';
  if ($kind==='agency') {
    $logo = $img ? '<img src="'.$e($img).'" alt="'.$e($it['title']).'" loading="lazy" decoding="async" width="400" height="220" class="w-full h-full object-cover">' : '<div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-[#00215E] to-[#005BB5] text-white"><i class="fas '.$e($it['icon']?:'fa-building').' text-2xl md:text-3xl" aria-hidden="true"></i></div>';
    $bmap=['agency'=>'bg-violet-100 text-violet-700','management'=>'bg-blue-100 text-blue-700','cleaning'=>'bg-teal-100 text-teal-700']; $bc=$bmap[$it['agencyCategory']]??$bmap['agency'];
    $ver = !empty($it['verified']) ? '<span class="absolute top-1.5 right-1.5 md:top-3 md:right-3 bg-white/95 text-emerald-700 text-[8px] md:text-[10px] font-bold px-1.5 md:px-2 py-0.5 md:py-1 rounded-full shadow"><i class="fas fa-certificate text-amber-500 mr-0.5" aria-hidden="true"></i>Verified</span>' : '';
    $rating = !empty($it['rating']) ? '<div class="hidden sm:flex items-center gap-1 text-[11px]"><span class="text-amber-500"><i class="fas fa-star" aria-hidden="true"></i></span><span class="font-bold text-navy">'.number_format((float)$it['rating'],1).'</span><span class="text-gray-400">('.(int)$it['reviews'].')</span></div>' : '';
    $phone = !empty($it['phone']) ? '<a href="tel:'.$e(preg_replace('/\s+/','',(string)$it['phone'])).'" class="w-8 md:w-10 h-8 md:h-9 rounded-lg border border-gray-200 flex items-center justify-center text-gray-600 hover:border-[#005BB5] hover:text-[#005BB5]" aria-label="Call '.$e($it['title']).'"><i class="fas fa-phone text-[10px] md:text-xs" aria-hidden="true"></i></a>' : '';
    echo '<article class="mkt-card flex flex-col" '.$data.' itemscope itemtype="https://schema.org/'.($it['agencyCategory']==='cleaning'?'LocalBusiness':'RealEstateAgent').'" data-open="'.$e($it['id']).'"><a href="'.$e($href).'" class="block mkt-open" data-open="'.$e($it['id']).'" itemprop="url"><div class="relative h-28 sm:h-36 md:h-44 overflow-hidden bg-gray-100">'.$logo.$ver.'</div></a><div class="p-2.5 md:p-4 flex flex-col flex-1"><div class="flex items-center justify-between gap-1 mb-1 md:mb-2"><span class="px-2 py-1 rounded-full text-[8px] md:text-[10px] font-bold '.$bc.'">'.$e($it['agencyCategoryLabel']).'</span>'.$rating.'</div><h3 class="font-serif text-[13px] md:text-lg text-navy font-semibold leading-tight line-clamp-1" itemprop="name"><a href="'.$e($href).'" class="hover:text-[#005BB5] mkt-open" data-open="'.$e($it['id']).'">'.$e($it['title']).'</a></h3><p class="text-[9px] md:text-[11px] text-gray-500 mt-0.5 md:mt-1 line-clamp-1" itemprop="address"><i class="fas fa-map-marker-alt text-[#005BB5] mr-0.5" aria-hidden="true"></i>'.$e($it['location']).'</p><p class="hidden md:block text-xs text-gray-600 mt-2 line-clamp-2" itemprop="description">'.$e($it['desc']).'</p><div class="mt-2 md:mt-3 pt-2 md:pt-3 border-t border-gray-100 flex items-center justify-between gap-1 text-[8px] md:text-[10px] text-gray-500"><span class="truncate"><i class="fas fa-briefcase text-[#005BB5] mr-0.5" aria-hidden="true"></i>'.$e($it['yearsLabel']?:((int)$it['years'].'+')).'</span><span class="hidden sm:inline"><i class="fas fa-users text-[#005BB5] mr-0.5" aria-hidden="true"></i>'.$e($it['clients']).'</span><span class="text-emerald-600 font-bold">'.$e($it['satisfaction']).'</span></div><div class="mt-2 md:mt-3 flex gap-1.5 md:gap-2"><a href="'.$e($href).'" class="flex-1 text-center py-1.5 md:py-2 rounded-lg bg-[#00215E] text-white text-[10px] md:text-xs font-bold hover:bg-[#005BB5] transition mkt-open" data-open="'.$e($it['id']).'">View</a>'.$phone.'</div></div></article>';
  } else {
    $imgHtml = $img ? '<img src="'.$e($img).'" alt="'.$e($it['title'].' - '.$it['propertyTypeLabel'].' in '.$it['location']).'" loading="lazy" decoding="async" width="400" height="220" class="mkt-img w-full h-full object-cover" itemprop="image">' : '<div class="w-full h-full skeleton"></div>';
    $bmap=['rent'=>'bg-emerald-100 text-emerald-700','booking'=>'bg-orange-100 text-orange-700','sale'=>'bg-indigo-100 text-indigo-700']; $bc=$bmap[$it['propertyType']]??$bmap['rent'];
    $sz = !empty($it['size']) ? '<span class="hidden sm:inline"><i class="fas fa-ruler-combined mr-0.5 text-[#005BB5]" aria-hidden="true"></i>'.$e($it['size']).'</span>' : '';
    echo '<article class="mkt-card flex flex-col" '.$data.' itemscope itemtype="https://schema.org/RealEstateListing" data-open="'.$e($it['id']).'"><a href="'.$e($href).'" class="block mkt-open" data-open="'.$e($it['id']).'" itemprop="url"><div class="relative h-28 sm:h-36 md:h-44 overflow-hidden bg-gray-100">'.$imgHtml.'<div class="absolute top-1.5 left-1.5 md:top-3 md:left-3"><span class="px-2 py-1 rounded-full text-[8px] md:text-[10px] font-bold '.$bc.'">'.$e($it['propertyTypeLabel']?:$it['badge']).'</span></div></div></a><div class="p-2.5 md:p-4 flex flex-col flex-1"><h3 class="font-serif text-[13px] md:text-lg text-navy font-semibold leading-tight line-clamp-1" itemprop="name"><a href="'.$e($href).'" class="hover:text-[#005BB5] mkt-open" data-open="'.$e($it['id']).'">'.$e($it['title']).'</a></h3><p class="text-[9px] md:text-[11px] text-gray-500 mt-0.5 md:mt-1 line-clamp-1" itemprop="address"><i class="fas fa-map-marker-alt text-[#005BB5] mr-0.5" aria-hidden="true"></i>'.$e($it['location']).'</p><div class="flex items-center gap-2 md:gap-3 text-[9px] md:text-[10px] text-gray-500 mt-1.5 md:mt-2"><span><i class="fas fa-bed mr-0.5 text-[#005BB5]" aria-hidden="true"></i>'.(int)$it['beds'].'</span><span><i class="fas fa-bath mr-0.5 text-[#005BB5]" aria-hidden="true"></i>'.(int)$it['baths'].'</span>'.$sz.'</div><div class="mt-2 md:mt-3 pt-2 md:pt-3 border-t border-gray-100 flex items-end justify-between gap-1.5 md:gap-2" itemprop="offers" itemscope itemtype="https://schema.org/Offer"><meta itemprop="priceCurrency" content="KES"><meta itemprop="price" content="'.(int)$it['price'].'"><div class="min-w-0"><div class="text-[12px] sm:text-sm md:text-lg font-extrabold text-navy truncate">'.$e($it['priceText']).'</div></div><a href="'.$e($href).'" class="px-2 md:px-3 py-1.5 md:py-2 rounded-lg bg-gray-900 text-white text-[10px] md:text-[11px] font-bold hover:bg-[#005BB5] transition shrink-0 mkt-open" data-open="'.$e($it['id']).'">View</a></div></div></article>';
  }
}
?>
        </div>
        <noscript><p class="text-sm text-gray-600 mt-4">Showing all <?php echo count($items); ?> marketplace listings. Enable JavaScript for interactive filters.</p></noscript>
        <div id="mktEmpty" class="hidden text-center py-16"><div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-3"><i class="fas fa-search text-gray-300 text-xl"></i></div><h3 class="font-serif text-xl text-navy font-semibold">No results found</h3><p class="text-sm text-gray-500 mt-1">Try different keywords or clear filters.</p><button id="mktEmptyReset" class="mt-4 px-4 py-2 rounded-lg bg-[#005BB5] text-white text-sm font-semibold">Clear filters</button></div>
        <div id="mktPager" class="flex items-center justify-center gap-1.5 mt-8"></div>
      </main>
    </div>
  </div>

<!-- Marketplace Modals -->
<div id="mktPropModal" class="fixed inset-0 z-[6000] hidden" aria-hidden="true">
  <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" data-mkt-close="prop"></div>
  <div class="relative h-full w-full overflow-y-auto p-3 md:p-6">
    <div class="mx-auto max-w-5xl bg-white rounded-2xl shadow-2xl overflow-hidden my-2 md:my-6">
      <div class="flex items-center justify-between px-4 md:px-6 py-3 border-b border-gray-100">
        <div class="min-w-0"><div id="mktPropBadge" class="inline-flex mb-1"></div><h3 id="mktPropTitle" class="font-serif text-lg md:text-2xl text-navy font-semibold truncate"></h3><p id="mktPropLoc" class="text-xs text-gray-500 truncate"></p></div>
        <button type="button" data-mkt-close="prop" class="w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 shrink-0"><i class="fas fa-times"></i></button>
      </div>
      <div class="grid grid-cols-1 lg:grid-cols-5">
        <div class="lg:col-span-3 bg-gray-100">
          <div class="relative h-56 sm:h-72 md:h-[380px]"><img id="mktPropImg" src="" alt="" class="w-full h-full object-cover"><button id="mktPropPrev" type="button" class="absolute left-2 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-white/90 text-navy shadow"><i class="fas fa-chevron-left"></i></button><button id="mktPropNext" type="button" class="absolute right-2 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-white/90 text-navy shadow"><i class="fas fa-chevron-right"></i></button><div id="mktPropCount" class="absolute bottom-2 right-2 text-[10px] font-bold bg-black/55 text-white px-2 py-1 rounded-full"></div></div>
          <div id="mktPropThumbs" class="flex gap-2 p-2 overflow-x-auto bg-white border-t border-gray-100"></div>
        </div>
        <div class="lg:col-span-2 p-4 md:p-5 max-h-[70vh] overflow-y-auto">
          <div id="mktPropPrice" class="text-xl md:text-2xl font-extrabold text-navy"></div>
          <div id="mktPropMeta" class="flex flex-wrap gap-3 text-xs text-gray-600 mt-2"></div>
          <p id="mktPropDesc" class="text-sm text-gray-600 mt-3 leading-relaxed"></p>
          <div id="mktPropAmen" class="flex flex-wrap gap-1.5 mt-3"></div>
          <div id="mktPropMapWrap" class="mt-4 hidden"><h4 class="text-xs font-bold uppercase tracking-wide text-gray-500 mb-2"><i class="fas fa-map-marked-alt text-[#005BB5] mr-1"></i>Location</h4><div id="mktPropMap" class="h-40 rounded-xl border border-gray-200 overflow-hidden"></div></div>
          <div class="mt-4 flex gap-2"><a id="mktPropView" href="#" target="_blank" rel="noopener" class="flex-1 text-center py-2.5 rounded-xl bg-gradient-to-r from-[#00215E] to-[#005BB5] text-white text-sm font-bold">Open Full Page <i class="fas fa-external-link-alt text-[10px] ml-1"></i></a><button type="button" data-mkt-close="prop" class="px-4 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-700">Close</button></div>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="mktAgencyModal" class="fixed inset-0 z-[6000] hidden" aria-hidden="true">
  <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" data-mkt-close="agency"></div>
  <div class="relative h-full w-full overflow-y-auto p-3 md:p-6">
    <div class="mx-auto max-w-3xl bg-white rounded-2xl shadow-2xl overflow-hidden my-2 md:my-6">
      <div class="flex items-center justify-between px-4 md:px-6 py-3 border-b border-gray-100">
        <div class="flex items-center gap-3 min-w-0"><div id="mktAgLogo" class="w-12 h-12 rounded-xl bg-gray-100 overflow-hidden shrink-0"></div><div class="min-w-0"><div id="mktAgBadge" class="inline-flex mb-1"></div><h3 id="mktAgTitle" class="font-serif text-lg md:text-2xl text-navy font-semibold truncate"></h3><p id="mktAgLoc" class="text-xs text-gray-500 truncate"></p></div></div>
        <button type="button" data-mkt-close="agency" class="w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 shrink-0"><i class="fas fa-times"></i></button>
      </div>
      <div class="p-4 md:p-6">
        <div id="mktAgStats" class="grid grid-cols-2 sm:grid-cols-4 gap-2 mb-4"></div>
        <p id="mktAgDesc" class="text-sm text-gray-600 leading-relaxed"></p>
        <p id="mktAgAbout" class="text-sm text-gray-500 leading-relaxed mt-2"></p>
        <div id="mktAgServices" class="flex flex-wrap gap-1.5 mt-4"></div>
        <div id="mktAgContacts" class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-2"></div>
        <div class="mt-5 flex gap-2"><a id="mktAgView" href="#" target="_blank" rel="noopener" class="flex-1 text-center py-2.5 rounded-xl bg-gradient-to-r from-[#00215E] to-[#005BB5] text-white text-sm font-bold">Open Company Page <i class="fas fa-external-link-alt text-[10px] ml-1"></i></a><button type="button" data-mkt-close="agency" class="px-4 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-700">Close</button></div>
      </div>
    </div>
  </div>
</div>
</section>

<script>
const MKT_ITEMS = <?php echo json_encode($items, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT); ?>;
const MKT_INITIAL = <?php echo json_encode($initial, JSON_UNESCAPED_UNICODE|JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT); ?>;
(function(){
  const state = {q:(MKT_INITIAL.q||'').toLowerCase(), kind:MKT_INITIAL.kind||'all', propertyType:MKT_INITIAL.propertyType||'all', agencyCategory:MKT_INITIAL.agencyCategory||'all', city:'', maxPrice:null, verified:false, sort:'recommended', page:1};
  const PER=12;
  const $=id=>document.getElementById(id);
  const esc=s=>String(s??'').replace(/[&<>"']/g,m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
  const grid=$('mktGrid'), empty=$('mktEmpty'), count=$('mktCount'), summary=$('mktSummary'), pager=$('mktPager');
  const search=$('mktSearch'), kindSel=$('mktKind'), citySel=$('mktCity'), maxPrice=$('mktMaxPrice'), verified=$('mktVerified'), sortSel=$('mktSort');

  function syncChips(){
    document.querySelectorAll('[data-kind]').forEach(b=>b.classList.toggle('active', b.dataset.kind===state.kind));
    document.querySelectorAll('[data-ptype]').forEach(b=>b.classList.toggle('active', b.dataset.ptype===state.propertyType));
    document.querySelectorAll('[data-acat]').forEach(b=>b.classList.toggle('active', b.dataset.acat===state.agencyCategory));
    kindSel.value=state.kind;
    $('propertyTypeWrap').style.opacity = state.kind==='agency'?'.45':'1';
    $('priceWrap').style.opacity = state.kind==='agency'?'.45':'1';
    $('agencyCatWrap').style.opacity = state.kind==='property'?'.45':'1';
    $('verifiedWrap').style.opacity = state.kind==='property'?'.45':'1';
    const active = [state.kind!=='all', state.propertyType!=='all', state.agencyCategory!=='all', !!state.city, !!state.maxPrice, !!state.verified].filter(Boolean).length;
    const badge=$('mktFilterCount'); if(badge){ badge.textContent=String(active); badge.classList.toggle('hidden', active===0); }
  }
  function match(it){
    if(state.kind!=='all' && it.kind!==state.kind) return false;
    if(it.kind==='property'){
      if(state.propertyType!=='all' && it.propertyType!==state.propertyType) return false;
      if(state.maxPrice && Number(it.price||0)>state.maxPrice) return false;
    } else {
      if(state.agencyCategory!=='all' && it.agencyCategory!==state.agencyCategory) return false;
      if(state.verified && !it.verified) return false;
    }
    if(state.city && (it.city||'')!==state.city) return false;
    if(state.q && !(it.search||'').includes(state.q)) return false;
    return true;
  }
  function sorted(arr){
    const a=[...arr];
    if(state.sort==='price-low') a.sort((x,y)=>(x.price||9e15)-(y.price||9e15));
    else if(state.sort==='price-high') a.sort((x,y)=>(y.price||-1)-(x.price||-1));
    else if(state.sort==='rating') a.sort((x,y)=>(y.rating||-1)-(x.rating||-1));
    else if(state.sort==='name') a.sort((x,y)=>String(x.title).localeCompare(String(y.title)));
    else a.sort((x,y)=>(x.kind===y.kind?0:(x.kind==='property'?-1:1)));
    return a;
  }
  // Items are server-rendered for crawlability/SEO. JS progressively enhances with instant filtering & pagination.
  const cards = Array.from(grid.querySelectorAll('[data-mkt="1"]'));
  function getKey(el){ return el.querySelector('a[href]')?.getAttribute('href')||el.querySelector('h3')?.textContent||Math.random(); }
  function cardData(el){
    return {el, kind:el.dataset.kind||'', propertyType:el.dataset.ptype||'', agencyCategory:el.dataset.acat||'', city:el.dataset.city||'', price:Number(el.dataset.price||0), rating:Number(el.dataset.rating||0), verified:el.dataset.verified==='1', search:(el.dataset.search||''), title:(el.dataset.title||'')};
  }
  const cardItems = cards.map(cardData);
  function pushUrl(){
    const p=new URLSearchParams();
    if(state.q) p.set('q',state.q);
    if(state.kind!=='all') p.set('kind',state.kind);
    if(state.propertyType!=='all') p.set('property_type',state.propertyType);
    if(state.agencyCategory!=='all') p.set('agency_category',state.agencyCategory);
    if(state.city) p.set('city',state.city);
    if(state.maxPrice) p.set('max_price',String(state.maxPrice));
    if(state.verified) p.set('verified','1');
    const s=p.toString(); history.replaceState(null,'', window.location.pathname + (s?('?'+s):''));
  }
  function render(){
    syncChips();
    const all=sorted(cardItems.filter(match));
    const total=all.length, pages=Math.max(1,Math.ceil(total/PER));
    if(state.page>pages) state.page=pages;
    const start=(state.page-1)*PER, pageItems=all.slice(start,start+PER);
    count.textContent=String(total);
    const bits=[];
    if(state.q) bits.push('"'+esc(state.q)+'"');
    if(state.kind!=='all') bits.push(state.kind==='property'?'properties':'agencies');
    if(state.kind!=='property' && state.propertyType!=='all') bits.push(state.propertyType);
    if(state.kind!=='agency' && state.agencyCategory!=='all') bits.push(state.agencyCategory);
    if(state.city) bits.push(state.city);
    summary.textContent = bits.length?('Filtered by '+bits.join(' · ')): 'Showing all '+total+' marketplace listings';
    const show = new Set(pageItems.map(i=>i.el));
    cardItems.forEach(i=>{ i.el.style.display = show.has(i.el) ? '' : 'none'; });
    pageItems.forEach(i=>grid.appendChild(i.el)); // keep DOM order by current sort/page
    empty.classList.toggle('hidden', total!==0);
    grid.classList.toggle('hidden', total===0);
    let pag=''; if(pages>1){
      pag+=`<button ${state.page===1?'disabled':''} data-pg="${state.page-1}" class="w-9 h-9 rounded-lg border border-gray-200 disabled:opacity-40" aria-label="Previous page"><i class="fas fa-chevron-left text-[10px]"></i></button>`;
      for(let i=1;i<=pages;i++){ if(i===1||i===pages||Math.abs(i-state.page)<=1) pag+=`<button data-pg="${i}" class="w-9 h-9 rounded-lg text-xs font-bold ${i===state.page?'bg-[#00215E] text-white':'border border-gray-200 text-gray-600'}" aria-label="Page ${i}">${i}</button>`; else if(Math.abs(i-state.page)===2) pag+='<span class="px-1 text-gray-400">…</span>'; }
      pag+=`<button ${state.page===pages?'disabled':''} data-pg="${state.page+1}" class="w-9 h-9 rounded-lg border border-gray-200 disabled:opacity-40" aria-label="Next page"><i class="fas fa-chevron-right text-[10px]"></i></button>`;
    }
    pager.innerHTML=pag;
    pager.querySelectorAll('[data-pg]').forEach(b=>b.addEventListener('click',()=>{state.page=Number(b.dataset.pg)||1; render(); grid.scrollIntoView({behavior:'smooth',block:'start'});}));
    pushUrl();
  }
  function reset(full){
    state.kind='all'; state.propertyType='all'; state.agencyCategory='all'; state.city=''; state.maxPrice=null; state.verified=false; state.sort='recommended'; state.page=1;
    if(full){ state.q=''; search.value=''; }
    kindSel.value='all'; citySel.value=''; maxPrice.value=''; verified.checked=false; sortSel.value='recommended'; render();
  }
  $('mktSearchForm').addEventListener('submit',e=>{e.preventDefault(); state.q=(search.value||'').trim().toLowerCase(); state.kind=kindSel.value; state.page=1; render();});
  search.addEventListener('input',()=>{ clearTimeout(search._t); search._t=setTimeout(()=>{state.q=(search.value||'').trim().toLowerCase(); state.page=1; render();},180); });
  kindSel.addEventListener('change',()=>{state.kind=kindSel.value; state.page=1; render();});
  citySel.addEventListener('change',()=>{state.city=citySel.value; state.page=1; render();});
  maxPrice.addEventListener('input',()=>{state.maxPrice=maxPrice.value?Number(maxPrice.value):null; state.page=1; render();});
  verified.addEventListener('change',()=>{state.verified=verified.checked; state.page=1; render();});
  sortSel.addEventListener('change',()=>{state.sort=sortSel.value; state.page=1; render();});
  document.querySelectorAll('[data-kind]').forEach(b=>b.addEventListener('click',()=>{state.kind=b.dataset.kind; state.page=1; render();}));
  document.querySelectorAll('[data-ptype]').forEach(b=>b.addEventListener('click',()=>{state.propertyType=b.dataset.ptype; if(state.propertyType!=='all') state.kind='property'; state.page=1; render();}));
  document.querySelectorAll('[data-acat]').forEach(b=>b.addEventListener('click',()=>{state.agencyCategory=b.dataset.acat; if(state.agencyCategory!=='all') state.kind='agency'; state.page=1; render();}));
  document.querySelectorAll('[data-quick]').forEach(b=>b.addEventListener('click',()=>{const v=b.dataset.quick; state.q=''; search.value=''; state.city=''; citySel.value=''; state.maxPrice=null; maxPrice.value=''; state.verified=false; verified.checked=false; state.page=1; if(v==='all'){state.kind='all';state.propertyType='all';state.agencyCategory='all';} else if(['rent','booking','sale'].includes(v)){state.kind='property';state.propertyType=v;state.agencyCategory='all';} else if(v==='agency'){state.kind='agency';state.agencyCategory='all';state.propertyType='all';} render();}));
  $('mktReset').addEventListener('click',()=>reset(true));
  $('mktEmptyReset').addEventListener('click',()=>reset(true));
  const ft=$('mktFilterToggle'), fb=$('mktFilterBody'), fc=$('mktFilterChevron');
  function setFiltersOpen(open){ if(!fb||!ft) return; fb.classList.toggle('hidden', !open); ft.setAttribute('aria-expanded', open?'true':'false'); if(fc) fc.style.transform = open?'rotate(180deg)':''; }
  if(ft) ft.addEventListener('click',()=>{ if(window.matchMedia('(min-width: 1024px)').matches) return; setFiltersOpen(fb.classList.contains('hidden')); });
  window.addEventListener('resize',()=>{ if(window.matchMedia('(min-width: 1024px)').matches){ fb && fb.classList.remove('hidden'); ft && ft.setAttribute('aria-expanded','true'); if(fc) fc.style.transform=''; } else if(ft && ft.getAttribute('aria-expanded')!=='true'){ fb && fb.classList.add('hidden'); } });
  // init from URL extras
  const up=new URLSearchParams(location.search);
  state.city=up.get('city')||''; state.maxPrice=up.get('max_price')?Number(up.get('max_price')):null; state.verified=up.get('verified')==='1';
  citySel.value=state.city; if(state.maxPrice) maxPrice.value=state.maxPrice; verified.checked=state.verified;
  
  // Modal logic (keeps SEO links intact; JS enhances clicks to modals)
  const byId = Object.fromEntries(MKT_ITEMS.map(i=>[i.id,i]));
  let mktMap=null, mktMarker=null, mktSlide=0, mktImgs=[], mktCurProp=null;
  function ensureLeaflet(cb){
    if(window.L) return cb();
    if(!document.getElementById('mktLeafletCss')){ const l=document.createElement('link'); l.id='mktLeafletCss'; l.rel='stylesheet'; l.href='https://unpkg.com/leaflet@1.9.4/dist/leaflet.css'; document.head.appendChild(l); }
    if(!document.getElementById('mktLeafletJs')){ const s=document.createElement('script'); s.id='mktLeafletJs'; s.src='https://unpkg.com/leaflet@1.9.4/dist/leaflet.js'; s.onload=cb; document.head.appendChild(s); } else document.getElementById('mktLeafletJs').addEventListener('load',cb,{once:true});
  }
  function openModal(kind){
    const m=$(kind==='prop'?'mktPropModal':'mktAgencyModal'); if(!m) return; m.classList.remove('hidden'); m.setAttribute('aria-hidden','false'); document.body.style.overflow='hidden';
  }
  function closeModal(kind){
    const m=$(kind==='prop'?'mktPropModal':'mktAgencyModal'); if(!m) return; m.classList.add('hidden'); m.setAttribute('aria-hidden','true');
    if(!$('mktPropModal')?.classList.contains('hidden')===false && !$('mktAgencyModal')?.classList.contains('hidden')===false){} 
    if(document.querySelectorAll('#mktPropModal:not(.hidden),#mktAgencyModal:not(.hidden)').length===0) document.body.style.overflow='';
    if(kind==='prop' && mktMap){ try{mktMap.remove()}catch(e){} mktMap=null; mktMarker=null; }
  }
  document.querySelectorAll('[data-mkt-close]').forEach(el=>el.addEventListener('click',()=>closeModal(el.dataset.mktClose)));
  document.addEventListener('keydown',e=>{ if(e.key==='Escape'){ closeModal('prop'); closeModal('agency'); }});

  function openProperty(id){
    const it=byId[id]; if(!it) return; mktCurProp=it; mktImgs=Array.isArray(it.images)&&it.images.length?it.images:(it.image?[it.image]:[]); mktSlide=0;
    $('mktPropTitle').textContent=it.title||''; $('mktPropLoc').innerHTML='<i class="fas fa-map-marker-alt text-[#005BB5] mr-1"></i>'+esc(it.location||'');
    $('mktPropBadge').innerHTML=it.propertyTypeLabel?`<span class="px-2 py-1 rounded-full text-[10px] font-bold ${it.propertyType==='booking'?'bg-orange-100 text-orange-700':it.propertyType==='sale'?'bg-indigo-100 text-indigo-700':'bg-emerald-100 text-emerald-700'}">${esc(it.propertyTypeLabel)}</span>`:'';
    $('mktPropPrice').textContent=it.priceText||''; $('mktPropMeta').innerHTML=`<span><i class="fas fa-bed mr-1 text-[#005BB5]"></i>${it.beds||0} beds</span><span><i class="fas fa-bath mr-1 text-[#005BB5]"></i>${it.baths||0} baths</span>${it.size?`<span><i class="fas fa-ruler-combined mr-1 text-[#005BB5]"></i>${esc(it.size)}</span>`:''}`;
    $('mktPropDesc').textContent=it.desc||''; $('mktPropAmen').innerHTML=(it.amenities||[]).map(a=>`<span class="inline-flex items-center gap-1 text-[10px] font-semibold bg-gray-100 text-gray-700 px-2 py-1 rounded-full"><i class="fas ${esc(a.icon||'fa-check')} text-[#005BB5]"></i>${esc(a.label||a)}</span>`).join('');
    $('mktPropView').href=it.url||'#'; renderPropSlide();
    const hasMap = it.lat!=null && it.lng!=null && !isNaN(Number(it.lat)) && !isNaN(Number(it.lng));
    $('mktPropMapWrap').classList.toggle('hidden', !hasMap);
    openModal('prop');
    if(hasMap){ ensureLeaflet(()=>requestAnimationFrame(()=>{ try{ if(mktMap){mktMap.remove();} mktMap=L.map('mktPropMap',{scrollWheelZoom:false}).setView([Number(it.lat),Number(it.lng)],14); L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{maxZoom:19,attribution:'&copy; OpenStreetMap'}).addTo(mktMap); mktMarker=L.marker([Number(it.lat),Number(it.lng)]).addTo(mktMap).bindPopup(esc(it.title||'Property')); setTimeout(()=>mktMap&&mktMap.invalidateSize(),120);}catch(e){} })); }
  }
  function renderPropSlide(){
    $('mktPropImg').src=mktImgs[mktSlide]||''; $('mktPropImg').alt=(mktCurProp?.title||'Property')+' image';
    $('mktPropCount').textContent=mktImgs.length?(mktSlide+1)+' / '+mktImgs.length:'';
    $('mktPropThumbs').innerHTML=mktImgs.map((src,i)=>`<button type="button" data-ms="${i}" class="w-14 h-10 rounded-lg overflow-hidden border-2 ${i===mktSlide?'border-[#005BB5]':'border-transparent'} shrink-0"><img src="${esc(src)}" alt="" class="w-full h-full object-cover" loading="lazy"></button>`).join('');
    $('mktPropThumbs').querySelectorAll('[data-ms]').forEach(b=>b.addEventListener('click',()=>{mktSlide=Number(b.dataset.ms)||0; renderPropSlide();}));
  }
  $('mktPropPrev').addEventListener('click',()=>{ if(!mktImgs.length) return; mktSlide=(mktSlide-1+mktImgs.length)%mktImgs.length; renderPropSlide(); });
  $('mktPropNext').addEventListener('click',()=>{ if(!mktImgs.length) return; mktSlide=(mktSlide+1)%mktImgs.length; renderPropSlide(); });

  function openAgency(id){
    const it=byId[id]; if(!it) return;
    $('mktAgTitle').textContent=it.title||''; $('mktAgLoc').innerHTML='<i class="fas fa-map-marker-alt text-[#005BB5] mr-1"></i>'+esc(it.location||'');
    const bmap={agency:['bg-violet-100 text-violet-700'],management:['bg-blue-100 text-blue-700'],cleaning:['bg-teal-100 text-teal-700']};
    $('mktAgBadge').innerHTML=`<span class="px-2 py-1 rounded-full text-[10px] font-bold ${(bmap[it.agencyCategory]||bmap.agency)[0]}">${esc(it.agencyCategoryLabel||'Company')}</span>`;
    $('mktAgLogo').innerHTML=it.image?`<img src="${esc(it.image)}" alt="${esc(it.title)}" class="w-full h-full object-cover">`:`<div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-[#00215E] to-[#005BB5] text-white"><i class="fas ${esc(it.icon||'fa-building')}"></i></div>`;
    $('mktAgDesc').textContent=it.desc||''; $('mktAgAbout').textContent=it.about||'';
    $('mktAgStats').innerHTML=[['Rating', it.rating?Number(it.rating).toFixed(1)+' ★':'—'],['Reviews', String(it.reviews||0)],['Experience', esc(it.yearsLabel||((it.years||0)+'+ yrs'))],['Satisfaction', esc(it.satisfaction||'—')]].map(([k,v])=>`<div class="rounded-xl bg-gray-50 border border-gray-100 p-3 text-center"><div class="text-[10px] uppercase tracking-wide text-gray-500 font-bold">${k}</div><div class="text-sm font-extrabold text-navy mt-0.5">${v}</div></div>`).join('');
    $('mktAgServices').innerHTML=(it.services||[]).map(s=>`<span class="text-[10px] font-semibold bg-blue-50 text-[#005BB5] px-2 py-1 rounded-full">${esc(s)}</span>`).join('');
    const contacts=[]; if(it.phone) contacts.push(['fa-phone','Call','tel:'+String(it.phone).replace(/\s+/g,''),it.phone]); if(it.whatsapp) contacts.push(['fa-whatsapp fab','WhatsApp','https://wa.me/'+String(it.whatsapp).replace(/[^\d]/g,''),'Chat']); if(it.email) contacts.push(['fa-envelope','Email','mailto:'+it.email,it.email]); if(it.website) contacts.push(['fa-globe','Website',/^https?:/i.test(it.website)?it.website:'https://'+it.website,'Visit']);
    $('mktAgContacts').innerHTML=contacts.map(c=>`<a href="${esc(c[2])}" target="${c[2].startsWith('http')?'_blank':'_self'}" rel="noopener" class="flex items-center gap-2 p-3 rounded-xl border border-gray-200 hover:border-[#005BB5] hover:bg-blue-50/40 transition"><i class="fas ${c[0]} text-[#005BB5]"></i><div class="min-w-0"><div class="text-[10px] uppercase tracking-wide text-gray-400 font-bold">${c[1]}</div><div class="text-xs font-semibold text-gray-700 truncate">${esc(c[3])}</div></div></a>`).join('');
    $('mktAgView').href=it.url||'#';
    openModal('agency');
  }

  function bindModalOpener(){
    const handler = function(e){
      const t = e.target && e.target.closest ? e.target.closest('.mkt-open,[data-open]') : null;
      if(!t) return;
      const id = t.getAttribute('data-open') || (t.closest('[data-id]') && t.closest('[data-id]').getAttribute('data-id'));
      if(!id || !byId[id]) return;
      e.preventDefault();
      e.stopPropagation();
      if(typeof e.stopImmediatePropagation==='function') e.stopImmediatePropagation();
      const it = byId[id];
      if(it.kind==='agency') openAgency(id); else openProperty(id);
    };
    // Capture phase so it wins before any other navigation handlers
    document.addEventListener('click', handler, true);
    grid && grid.addEventListener('click', handler, false);
  }
  bindModalOpener();
  render();
})();
</script>