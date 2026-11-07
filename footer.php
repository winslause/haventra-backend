<!-- ===== FOOTER ===== -->
<footer class="bg-[#0b1a1f] text-white pt-16 pb-6 relative overflow-hidden">
    
    <!-- Decorative Top Border -->
    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-[#4a90a0] via-[#F59E0B] to-[#005BB5]"></div>

    <!-- Background Decorative Elements -->
    <div class="absolute top-0 left-0 w-64 h-64 bg-[#4a90a0] rounded-full mix-blend-multiply filter blur-3xl opacity-10 pointer-events-none"></div>
    <div class="absolute bottom-0 right-0 w-64 h-64 bg-[#F59E0B] rounded-full mix-blend-multiply filter blur-3xl opacity-10 pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-6 relative z-10">
        
        <!-- Top Section: Newsletter & Main Links -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-8 pb-12 border-b border-white/10">
            
            <!-- Brand & Newsletter (Takes up 4 columns on large screens) -->
            <div class="lg:col-span-4 flex flex-col gap-6">
                <!-- Logo/Brand Name -->
                <div>
                    <h3 class="font-['Cormorant_Garamond'] text-3xl font-bold tracking-widest text-white mb-2 uppercase">HAVENTRA</h3>
                    <p class="text-gray-400 text-sm leading-relaxed mb-4">
                        Your Global Property Partner. Find, rent, buy, and book premium properties and professional services worldwide.
                    </p>
                </div>

                <!-- Newsletter Form -->
                <div>
                    <h4 class="text-sm font-semibold text-gray-200 uppercase tracking-wider mb-3">Subscribe to our Newsletter</h4>
                    <form class="flex flex-col sm:flex-row gap-2" onsubmit="event.preventDefault(); alert('Subscribed successfully!');">
                        <input type="email" placeholder="Enter your email" required
                               class="flex-1 px-4 py-2.5 bg-white/5 border border-white/10 rounded-lg text-sm text-white placeholder-gray-500 focus:outline-none focus:border-[#4a90a0] focus:bg-white/10 transition-all">
                        <button type="submit" 
                                class="px-5 py-2.5 bg-[#4a90a0] hover:bg-[#387585] text-white text-sm font-semibold rounded-lg transition-colors shadow-lg shadow-[#4a90a0]/20">
                            Subscribe
                        </button>
                    </form>
                    <p class="text-[10px] text-gray-500 mt-2">We respect your privacy. Unsubscribe at any time.</p>
                </div>
            </div>

            <!-- Spacer -->
            <div class="hidden lg:block lg:col-span-1"></div>

            <!-- Quick Links (Takes up 7 columns on large screens) -->
            <div class="lg:col-span-7 grid grid-cols-2 sm:grid-cols-3 gap-8">
                
                <!-- Column 1: Discover -->
                <div>
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4 flex items-center gap-2">
                        <span class="w-1 h-4 bg-[#F59E0B] rounded-full"></span> Discover
                    </h4>
                    <ul class="space-y-2.5">
                        <li><a href="<?= BASE_PATH ?>?page=houses" class="text-gray-400 text-sm hover:text-[#4a90a0] transition-colors">Houses for Rent</a></li>
                        <li><a href="#" class="text-gray-400 text-sm hover:text-[#4a90a0] transition-colors">Apartments for Sale</a></li>
                        <li><a href="<?= BASE_PATH ?>?page=airbnb" class="text-gray-400 text-sm hover:text-[#4a90a0] transition-colors">Short-Stay Stays</a></li>
                        <li><a href="#" class="text-gray-400 text-sm hover:text-[#4a90a0] transition-colors">Commercial Spaces</a></li>
                        <li><a href="#" class="text-gray-400 text-sm hover:text-[#4a90a0] transition-colors">Top Locations</a></li>
                    </ul>
                </div>

                <!-- Column 2: Professionals -->
                <div>
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4 flex items-center gap-2">
                        <span class="w-1 h-4 bg-[#4a90a0] rounded-full"></span> Professionals
                    </h4>
                    <ul class="space-y-2.5">
                        <li><a href="#" class="text-gray-400 text-sm hover:text-[#4a90a0] transition-colors">Real Estate Agencies</a></li>
                        <li><a href="#" class="text-gray-400 text-sm hover:text-[#4a90a0] transition-colors">Property Management</a></li>
                        <li><a href="#" class="text-gray-400 text-sm hover:text-[#4a90a0] transition-colors">Cleaning Services</a></li>
                        <li><a href="#" class="text-gray-400 text-sm hover:text-[#4a90a0] transition-colors">Find an Agent</a></li>
                        <li><a href="#" class="text-gray-400 text-sm hover:text-[#4a90a0] transition-colors">List Your Property</a></li>
                    </ul>
                </div>

                <!-- Column 3: Support & Company -->
                <div class="col-span-2 sm:col-span-1">
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4 flex items-center gap-2">
                        <span class="w-1 h-4 bg-[#005BB5] rounded-full"></span> Company
                    </h4>
                    <ul class="space-y-2.5">
                        <li><a href="<?= BASE_PATH ?>?page=about" class="text-gray-400 text-sm hover:text-[#4a90a0] transition-colors">About Us</a></li>
                        <li><a href="<?= BASE_PATH ?>?page=contact" class="text-gray-400 text-sm hover:text-[#4a90a0] transition-colors">Contact Us</a></li>
                        <li><a href="#" class="text-gray-400 text-sm hover:text-[#4a90a0] transition-colors">Blog & News</a></li>
                        <li><a href="#" class="text-gray-400 text-sm hover:text-[#4a90a0] transition-colors">Help Centre / FAQs</a></li>
                        <li><a href="#" class="text-gray-400 text-sm hover:text-[#4a90a0] transition-colors">Pricing & Plans</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Middle Section: Contact Info & Socials -->
        <div class="flex flex-col md:flex-row justify-between items-center py-8 border-b border-white/10 gap-6">
            
            <!-- Contact Info -->
            <div class="flex flex-col sm:flex-row items-center gap-6 text-sm text-gray-400">
                <a href="mailto:hello@haventra.com" class="flex items-center gap-2 hover:text-white transition-colors">
                    <div class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center text-[#4a90a0]">
                        <i class="fas fa-envelope text-xs"></i>
                    </div>
                    hello@haventra.com
                </a>
                <a href="tel:+254700000000" class="flex items-center gap-2 hover:text-white transition-colors">
                    <div class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center text-[#4a90a0]">
                        <i class="fas fa-phone-alt text-xs"></i>
                    </div>
                    +254 700 000 000
                </a>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center text-[#4a90a0]">
                        <i class="fas fa-map-marker-alt text-xs"></i>
                    </div>
                    Nairobi, Kenya
                </div>
            </div>

            <!-- Social Media Icons -->
            <div class="flex gap-3">
                <a href="#" aria-label="Facebook" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 hover:bg-[#4a90a0] hover:text-white hover:border-[#4a90a0] transition-all transform hover:-translate-y-1">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#" aria-label="Twitter" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 hover:bg-[#4a90a0] hover:text-white hover:border-[#4a90a0] transition-all transform hover:-translate-y-1">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="#" aria-label="Instagram" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 hover:bg-[#4a90a0] hover:text-white hover:border-[#4a90a0] transition-all transform hover:-translate-y-1">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="#" aria-label="LinkedIn" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 hover:bg-[#4a90a0] hover:text-white hover:border-[#4a90a0] transition-all transform hover:-translate-y-1">
                    <i class="fab fa-linkedin-in"></i>
                </a>
            </div>
        </div>

        <!-- Bottom Section: Copyright & Legal -->
        <div class="flex flex-col md:flex-row justify-between items-center pt-6 gap-4">
            <p class="text-gray-500 text-xs text-center md:text-left">
                &copy; <span id="year"></span> HAVENTRA. All rights reserved. 
                <span class="hidden sm:inline">|</span> Designed for the Global Property Market.
            </p>
            
            <div class="flex flex-wrap justify-center gap-x-4 gap-y-2 text-xs text-gray-500">
                <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
                <a href="#" class="hover:text-white transition-colors">Cookie Policy</a>
                <a href="#" class="hover:text-white transition-colors">Sitemap</a>
            </div>
        </div>

    </div>
</footer>

<script>
    document.getElementById('year').textContent = new Date().getFullYear();
</script>
</body>
</html>
