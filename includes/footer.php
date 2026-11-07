    <!-- ===== FOOTER ===== -->
    <footer class="bg-[#001540] border-t border-white/10 pt-12 pb-8">
        <div class="max-w-7xl mx-auto px-4 md:px-6">
            
            <!-- Top: Logo + Newsletter -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 mb-12">
                <div class="md:col-span-4">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 bg-[#F59E0B] rounded-lg flex items-center justify-center">
                            <i class="fas fa-home text-white text-xs"></i>
                        </div>
                        <span class="font-['Cormorant_Garamond'] text-2xl text-white font-semibold tracking-wider">HAVENTRA</span>
                    </div>
                    <p class="text-[#c4e2ed]/80 text-sm md:text-base leading-relaxed">
                        Global real estate marketplace connecting property owners, landlords, agencies, and clients worldwide. Find, rent, buy, sell, and book properties with confidence.
                    </p>
                    <div class="mt-6 flex gap-4">
                        <a href="#" class="text-[#c4e2ed]/70 hover:text-white transition-colors"><i class="fab fa-facebook text-xl"></i></a>
                        <a href="#" class="text-[#c4e2ed]/70 hover:text-white transition-colors"><i class="fab fa-twitter text-xl"></i></a>
                        <a href="#" class="text-[#c4e2ed]/70 hover:text-white transition-colors"><i class="fab fa-instagram text-xl"></i></a>
                        <a href="#" class="text-[#c4e2ed]/70 hover:text-white transition-colors"><i class="fab fa-linkedin text-xl"></i></a>
                    </div>
                </div>

                <div class="md:col-span-8 grid grid-cols-2 md:grid-cols-4 gap-8">
                    <div>
                        <h4 class="text-white font-semibold mb-6">For Buyers</h4>
                        <ul class="space-y-3 text-[#c4e2ed]/70 text-sm">
                            <li><a href="<?= BASE_PATH ?>?page=houses" class="hover:text-white transition-colors">Houses for Sale</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Apartments for Sale</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Commercial Properties</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Land for Sale</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-white font-semibold mb-6">For Renters</h4>
                        <ul class="space-y-3 text-[#c4e2ed]/70 text-sm">
                            <li><a href="<?= BASE_PATH ?>?page=houses" class="hover:text-white transition-colors">Houses for Rent</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Apartments for Rent</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Short-Stay Stays</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Rooms</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-white font-semibold mb-6">Services</h4>
                        <ul class="space-y-3 text-[#c4e2ed]/70 text-sm">
                            <li><a href="#" class="hover:text-white transition-colors">Property Management</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Cleaning Services</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Restaurant</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Amenities</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-white font-semibold mb-6">Company</h4>
                        <ul class="space-y-3 text-[#c4e2ed]/70 text-sm">
                            <li><a href="<?= BASE_PATH ?>?page=about" class="hover:text-white transition-colors">About Us</a></li>
                            <li><a href="<?= BASE_PATH ?>?page=contact" class="hover:text-white transition-colors">Contact Us</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Terms of Service</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Privacy Policy</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Newsletter -->
            <div class="bg-[#00215E]/50 border border-white/10 rounded-2xl p-6 md:p-8 mb-12">
                <h3 class="text-white text-lg md:text-xl font-semibold mb-4">Subscribe to our newsletter</h3>
                <p class="text-[#c4e2ed]/70 text-sm mb-6">Get the latest property listings, market insights, and exclusive deals delivered to your inbox.</p>
                <form class="flex flex-col sm:flex-row gap-3 max-w-md">
                    <input type="email" placeholder="Enter your email" class="flex-1 px-4 py-2 rounded-lg bg-white/10 border border-white/20 text-white placeholder-[#c4e2ed]/50 focus:outline-none focus:border-[#F59E0B] transition-colors">
                    <button type="submit" class="bg-[#F59E0B] text-[#00215E] font-bold px-6 py-2 rounded-lg hover:bg-[#d97706] transition-colors">Subscribe</button>
                </form>
            </div>

            <!-- Bottom: Copyright -->
            <div class="border-t border-white/10 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-[#c4e2ed]/60 text-xs md:text-sm">
                    <p>&copy; <?= date('Y') ?> HAVENTRA. All rights reserved.</p>
                    <div class="flex gap-6">
                        <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
                        <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                        <a href="#" class="hover:text-white transition-colors">Cookie Policy</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Close mobile menu when clicking a link
            document.querySelectorAll('#mobile-menu a').forEach(link => {
                link.addEventListener('click', () => {
                    document.getElementById('mobile-menu').classList.remove('active');
                    document.getElementById('mobile-toggle').classList.remove('active');
                    document.body.style.overflow = '';
                });
            });
        });
    </script>
</body>
</html>
