<?php if (!defined('BASE_PATH')) { $base_path = (strpos($_SERVER['HTTP_HOST'] ?? '', 'localhost') !== false) ? '/haventra/' : '/'; define('BASE_PATH', $base_path); } ?>
<!-- ================= AUTH PAGE STYLES ================= -->
<style>
    /* ===== SPLIT HERO ===== */
    .auth-hero {
        background: linear-gradient(135deg, rgba(0, 21, 64, 0.95), rgba(0, 91, 181, 0.85)),
                    url('https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1600&q=80');
        background-size: cover;
        background-position: center;
    }

    /* ===== MODAL BASE ===== */
    .auth-modal {
        position: fixed;
        inset: 0;
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
    }
    .auth-modal.active {
        opacity: 1;
        pointer-events: auto;
    }
    .auth-modal-backdrop {
        position: absolute;
        inset: 0;
        background: rgba(0, 15, 40, 0.75);
        backdrop-filter: blur(8px);
    }
    .auth-modal-panel {
        position: relative;
        background: white;
        border-radius: 24px;
        width: 100%;
        max-width: 460px;
        max-height: calc(100vh - 32px);
        overflow-y: auto;
        box-shadow: 0 30px 80px -20px rgba(0, 0, 0, 0.5);
        transform: scale(0.94) translateY(16px);
        transition: transform 0.35s cubic-bezier(0.25, 0.8, 0.25, 1);
    }
    .auth-modal.active .auth-modal-panel {
        transform: scale(1) translateY(0);
    }
    .auth-modal-panel::-webkit-scrollbar { width: 6px; }
    .auth-modal-panel::-webkit-scrollbar-track { background: transparent; }
    .auth-modal-panel::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 3px; }

    /* ===== INPUTS ===== */
    .auth-input {
        width: 100%;
        padding: 13px 14px 13px 42px;
        background: #F8FAFC;
        border: 1.5px solid #E2E8F0;
        border-radius: 12px;
        font-size: 14px;
        color: #1E293B;
        transition: all 0.25s ease;
        outline: none;
    }
    .auth-input::placeholder { color: #94A3B8; font-size: 13px; }
    .auth-input:focus {
        background: white;
        border-color: #005BB5;
        box-shadow: 0 0 0 4px rgba(0, 91, 181, 0.12);
    }
    .auth-input.error {
        border-color: #EF4444;
        background: #FEF2F2;
    }
    .input-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94A3B8;
        font-size: 13px;
        transition: color 0.25s ease;
    }
    .auth-input:focus ~ .input-icon {
        color: #005BB5;
    }

    /* ===== BUTTONS ===== */
    .btn-primary {
        width: 100%;
        padding: 14px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 700;
        color: white;
        background: linear-gradient(135deg, #00215E, #005BB5);
        box-shadow: 0 8px 20px -4px rgba(0, 91, 181, 0.4);
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        border: none;
    }
    .btn-primary:hover {
        box-shadow: 0 12px 28px -4px rgba(0, 91, 181, 0.55);
        transform: translateY(-1px);
    }
    .btn-primary:active { transform: translateY(0) scale(0.99); }
    .btn-primary:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

    .btn-social {
        width: 100%;
        padding: 12px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        background: white;
        border: 1.5px solid #E2E8F0;
        transition: all 0.25s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        cursor: pointer;
    }
    .btn-social:hover {
        background: #F8FAFC;
        border-color: #CBD5E1;
    }

    /* ===== ROLE SELECTOR ===== */
    .role-card {
        flex: 1;
        padding: 14px 8px;
        border-radius: 14px;
        border: 2px solid #E2E8F0;
        background: white;
        cursor: pointer;
        transition: all 0.25s cubic-bezier(0.25, 0.8, 0.25, 1);
        text-align: center;
    }
    .role-card:hover { border-color: #CBD5E1; transform: translateY(-2px); }
    .role-card.selected {
        border-color: #005BB5;
        background: linear-gradient(135deg, rgba(0, 91, 181, 0.06), rgba(0, 33, 94, 0.04));
        box-shadow: 0 4px 16px -4px rgba(0, 91, 181, 0.25);
    }
    .role-card.selected .role-icon {
        background: linear-gradient(135deg, #00215E, #005BB5);
        color: white;
        transform: scale(1.08);
    }
    .role-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: #F1F5F9;
        color: #64748B;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 8px;
        font-size: 17px;
        transition: all 0.25s ease;
    }

    /* ===== PASSWORD STRENGTH ===== */
    .strength-bar {
        height: 4px;
        border-radius: 9999px;
        background: #E2E8F0;
        overflow: hidden;
        flex: 1;
    }
    .strength-fill {
        height: 100%;
        border-radius: 9999px;
        width: 0;
        transition: width 0.4s ease, background 0.3s ease;
    }

    /* ===== TOGGLE ===== */
    .password-toggle {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94A3B8;
        font-size: 13px;
        cursor: pointer;
        transition: color 0.2s;
        background: none;
        border: none;
    }
    .password-toggle:hover { color: #005BB5; }

    /* ===== CHECKBOX ===== */
    .auth-checkbox {
        width: 18px;
        height: 18px;
        border-radius: 5px;
        border: 1.5px solid #CBD5E1;
        cursor: pointer;
        transition: all 0.2s;
        accent-color: #005BB5;
    }

    /* ===== FEATURE LIST ===== */
    .feature-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 10px 0;
    }
    .feature-icon {
        width: 32px;
        height: 32px;
        border-radius: 9px;
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #F59E0B;
        font-size: 12px;
        flex-shrink: 0;
    }

    /* ===== PULSE ===== */
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    .float-anim { animation: float 6s ease-in-out infinite; }

    @keyframes spin { to { transform: rotate(360deg); } }
    .spin { animation: spin 0.9s linear infinite; }

    /* ===== OTP INPUTS ===== */
    .otp-input {
        width: 48px;
        height: 56px;
        text-align: center;
        font-size: 20px;
        font-weight: 700;
        color: #00215E;
        background: #F8FAFC;
        border: 1.5px solid #E2E8F0;
        border-radius: 12px;
        outline: none;
        transition: all 0.2s ease;
    }
    .otp-input:focus {
        background: white;
        border-color: #005BB5;
        box-shadow: 0 0 0 4px rgba(0, 91, 181, 0.12);
    }
    @media (max-width: 400px) {
        .otp-input { width: 40px; height: 48px; font-size: 17px; }
    }

    /* ===== SUCCESS CHECK ===== */
    @keyframes checkPop {
        0% { transform: scale(0); opacity: 0; }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); opacity: 1; }
    }
    .check-pop { animation: checkPop 0.5s cubic-bezier(0.25, 0.8, 0.25, 1) both; }
</style>

<!-- ================= SPLIT LAYOUT ================= -->
<div class="min-h-screen flex">

    <!-- ===== LEFT: BRAND / HERO PANEL (desktop only) ===== -->
    <div class="hidden lg:flex lg:w-[55%] auth-hero relative overflow-hidden">
        
        <!-- Decorative circles -->
        <div class="absolute top-20 right-20 w-64 h-64 bg-accentOrange/10 rounded-full blur-3xl float-anim"></div>
        <div class="absolute bottom-20 left-10 w-80 h-80 bg-accentBlue/20 rounded-full blur-3xl"></div>

        <div class="relative z-10 flex flex-col justify-between p-12 xl:p-16 w-full">
            
            <!-- Logo -->
            <a href="<?= BASE_PATH ?>" class="flex items-center gap-3 group w-fit">
                <div class="w-11 h-11 rounded-2xl bg-white/15 backdrop-blur-md border border-white/20 flex items-center justify-center group-hover:bg-white/25 transition-colors">
                    <i class="fas fa-home text-white text-lg"></i>
                </div>
                <div>
                    <p class="font-serif text-2xl text-white font-semibold leading-none tracking-wide">HAVENTRA</p>
                    <p class="text-[9px] text-white/50 uppercase tracking-[0.3em] font-semibold mt-0.5">Real Estate Marketplace</p>
                </div>
            </a>

            <!-- Main message -->
            <div class="max-w-md">
                <h1 class="font-serif text-5xl xl:text-6xl text-white font-semibold leading-[1.1] mb-5">
                    Your property<br>
                    <span class="italic text-accentOrange">journey</span> starts here.
                </h1>
                <p class="text-white/70 text-base leading-relaxed mb-8">
                    Join thousands of clients, homeowners, and agencies finding, booking, and managing properties across Kenya and beyond.
                </p>

                <div class="space-y-1">
                    <div class="feature-item">
                        <div class="feature-icon"><i class="fas fa-search"></i></div>
                        <div>
                            <p class="text-white text-sm font-semibold">Discover verified properties</p>
                            <p class="text-white/50 text-xs mt-0.5">Thousands of listings, all vetted</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon"><i class="fas fa-calendar-check"></i></div>
                        <div>
                            <p class="text-white text-sm font-semibold">Book stays & schedule viewings</p>
                            <p class="text-white/50 text-xs mt-0.5">Instant booking for short stays</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon"><i class="fas fa-chart-line"></i></div>
                        <div>
                            <p class="text-white text-sm font-semibold">Manage your listings & earnings</p>
                            <p class="text-white/50 text-xs mt-0.5">Powerful dashboard for owners</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Social proof -->
            <div class="flex items-center gap-5">
                <div class="flex -space-x-3">
                    <div class="w-9 h-9 rounded-full bg-accentOrange border-2 border-white/30 flex items-center justify-center text-white font-bold text-[10px]">JM</div>
                    <div class="w-9 h-9 rounded-full bg-accentBlue border-2 border-white/30 flex items-center justify-center text-white font-bold text-[10px]">DK</div>
                    <div class="w-9 h-9 rounded-full bg-emerald-500 border-2 border-white/30 flex items-center justify-center text-white font-bold text-[10px]">SW</div>
                    <div class="w-9 h-9 rounded-full bg-adminPurple border-2 border-white/30 flex items-center justify-center text-white font-bold text-[10px]">+</div>
                </div>
                <div>
                    <div class="flex items-center gap-1 text-accentOrange text-xs mb-0.5">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                    <p class="text-white/60 text-xs">Trusted by <span class="text-white font-semibold">12,000+</span> users</p>
                </div>
            </div>
        </div>
    </div>


    <!-- ===== RIGHT: AUTH FORM PANEL ===== -->
    <div class="w-full lg:w-[45%] flex items-center justify-center p-5 sm:p-8 relative bg-white">
        
        <!-- Mobile logo -->
        <div class="lg:hidden absolute top-5 left-5">
            <a href="<?= BASE_PATH ?>" class="flex items-center gap-2">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-navy to-accentBlue flex items-center justify-center">
                    <i class="fas fa-home text-white text-sm"></i>
                </div>
                <p class="font-serif text-lg text-navy font-semibold">HAVENTRA</p>
            </a>
        </div>

        <div class="w-full max-w-[400px]">
            
            <!-- Welcome -->
            <div class="text-center mb-8">
                <h2 class="font-serif text-3xl sm:text-4xl text-navy font-semibold mb-2">Welcome</h2>
                <p class="text-slate-400 text-sm">Choose how you'd like to continue</p>
            </div>

            <!-- Primary action buttons -->
            <div class="space-y-3">
                <button onclick="openModal('login')" class="btn-primary">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Sign In to Your Account</span>
                </button>
                <button onclick="openModal('register')" class="w-full py-3.5 rounded-xl font-bold text-sm text-navy border-2 border-navy hover:bg-navy hover:text-white transition-all duration-300 flex items-center justify-center gap-2">
                    <i class="fas fa-user-plus"></i>
                    <span>Create a New Account</span>
                </button>
            </div>

            <!-- Divider -->
            <div class="flex items-center gap-3 my-7">
                <div class="flex-1 h-px bg-slate-200"></div>
                <span class="text-[10px] uppercase tracking-widest font-bold text-slate-400">or continue with</span>
                <div class="flex-1 h-px bg-slate-200"></div>
            </div>

            <!-- Social buttons -->
            <div class="space-y-2.5">
                <button onclick="socialLogin('Google')" class="btn-social">
                    <svg class="w-5 h-5" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    <span>Continue with Google</span>
                </button>
                <button onclick="socialLogin('Apple')" class="btn-social">
                    <i class="fab fa-apple text-lg"></i>
                    <span>Continue with Apple</span>
                </button>
            </div>

            <!-- Footer links -->
            <div class="mt-8 text-center space-y-2">
                <button onclick="openModal('forgot')" class="text-xs text-slate-400 hover:text-accentBlue transition-colors font-medium block mx-auto">
                    Forgot your password?
                </button>
                <p class="text-[10px] text-slate-400 leading-relaxed px-4">
                    By continuing, you agree to HAVENTRA's 
                    <a href="#" class="text-accentBlue hover:underline">Terms of Service</a> and 
                    <a href="#" class="text-accentBlue hover:underline">Privacy Policy</a>.
                </p>
            </div>
        </div>
    </div>
</div>


<!-- ============================================================
     MODAL: LOGIN
     ============================================================ -->
<div id="modal-login" class="auth-modal" aria-hidden="true">
    <div class="auth-modal-backdrop" onclick="closeModal('login')"></div>
    <div class="auth-modal-panel">
        
        <!-- Header -->
        <div class="relative px-6 pt-6 pb-5 border-b border-slate-100">
            <button onclick="closeModal('login')" class="absolute top-5 right-5 w-8 h-8 flex items-center justify-center rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors">
                <i class="fas fa-times text-sm"></i>
            </button>
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-navy to-accentBlue flex items-center justify-center mb-3">
                <i class="fas fa-sign-in-alt text-white text-lg"></i>
            </div>
            <h3 class="font-serif text-2xl text-navy font-semibold">Welcome back</h3>
            <p class="text-slate-400 text-xs mt-1">Sign in to access your dashboard</p>
        </div>

        <!-- Body -->
        <form onsubmit="handleLogin(event)" class="px-6 py-5 space-y-4">
            
            <div class="relative">
                <input type="email" id="loginEmail" class="auth-input" placeholder="Email address" required autocomplete="email">
                <i class="fas fa-envelope input-icon"></i>
            </div>

            <div class="relative">
                <input type="password" id="loginPassword" class="auth-input" placeholder="Password" required autocomplete="current-password" style="padding-right: 42px;">
                <i class="fas fa-lock input-icon"></i>
                <button type="button" onclick="togglePassword('loginPassword', this)" class="password-toggle" aria-label="Show password">
                    <i class="fas fa-eye"></i>
                </button>
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" class="auth-checkbox" checked>
                    <span class="text-xs text-slate-500">Remember me</span>
                </label>
                <button type="button" onclick="closeModal('login'); openModal('forgot')" class="text-xs text-accentBlue hover:underline font-semibold">
                    Forgot password?
                </button>
            </div>

            <button type="submit" id="loginBtn" class="btn-primary">
                <span>Sign In</span>
                <i class="fas fa-arrow-right text-xs"></i>
            </button>

            <!-- Success message -->
            <div id="loginSuccess" class="hidden items-center gap-2 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs px-3 py-2.5 rounded-lg">
                <i class="fas fa-check-circle"></i>
                <span>Signed in successfully! Redirecting&hellip;</span>
            </div>
        </form>

        <!-- Footer -->
        <div class="px-6 pb-6 text-center border-t border-slate-100 pt-4">
            <p class="text-xs text-slate-500">
                Don't have an account?
                <button onclick="closeModal('login'); openModal('register')" class="text-accentBlue font-bold hover:underline ml-1">Sign up</button>
            </p>
        </div>
    </div>
</div>


<!-- ============================================================
     MODAL: REGISTER
     ============================================================ -->
<div id="modal-register" class="auth-modal" aria-hidden="true">
    <div class="auth-modal-backdrop" onclick="closeModal('register')"></div>
    <div class="auth-modal-panel" style="max-width: 500px;">
        
        <!-- Header -->
        <div class="relative px-6 pt-6 pb-5 border-b border-slate-100">
            <button onclick="closeModal('register')" class="absolute top-5 right-5 w-8 h-8 flex items-center justify-center rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors">
                <i class="fas fa-times text-sm"></i>
            </button>
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-accentOrange to-orange-600 flex items-center justify-center mb-3">
                <i class="fas fa-user-plus text-white text-lg"></i>
            </div>
            <h3 class="font-serif text-2xl text-navy font-semibold">Create your account</h3>
            <p class="text-slate-400 text-xs mt-1">Join HAVENTRA in just a few seconds</p>
        </div>

        <!-- Body -->
        <form onsubmit="handleRegister(event)" class="px-6 py-5">
            <!-- Role selector -->
            <div class="mb-5">
                <p class="text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-2.5">I want to join as:</p>
                <div class="flex gap-2">
                    <div onclick="selectRole(this, 'client')" class="role-card selected" data-role="client">
                        <div class="role-icon"><i class="fas fa-user"></i></div>
                        <p class="text-[11px] font-bold text-navy">Client</p>
                        <p class="text-[9px] text-slate-400 mt-0.5">Find & book</p>
                    </div>
                    <div onclick="selectRole(this, 'owner')" class="role-card" data-role="owner">
                        <div class="role-icon"><i class="fas fa-home"></i></div>
                        <p class="text-[11px] font-bold text-navy">Owner</p>
                        <p class="text-[9px] text-slate-400 mt-0.5">List property</p>
                    </div>
                    <div onclick="selectRole(this, 'company')" class="role-card" data-role="company">
                        <div class="role-icon"><i class="fas fa-building"></i></div>
                        <p class="text-[11px] font-bold text-navy">Company</p>
                        <p class="text-[9px] text-slate-400 mt-0.5">Agency</p>
                    </div>
                </div>
                <input type="hidden" id="registerRole" value="client">
            </div>

            <!-- Name row -->
            <div class="grid grid-cols-2 gap-3 mb-3">
                <div class="relative">
                    <input type="text" id="registerFirstName" class="auth-input" placeholder="First name" required autocomplete="given-name">
                    <i class="fas fa-user input-icon"></i>
                </div>
                <div class="relative">
                    <input type="text" id="registerLastName" class="auth-input" placeholder="Last name" required autocomplete="family-name">
                    <i class="fas fa-user input-icon"></i>
                </div>
            </div>

            <div class="relative mb-3">
                <input type="email" id="registerEmail" class="auth-input" placeholder="Email address" required autocomplete="email">
                <i class="fas fa-envelope input-icon"></i>
            </div>

            <div class="relative mb-3">
                <input type="tel" id="registerPhone" class="auth-input" placeholder="Phone number (+254...)" required autocomplete="tel">
                <i class="fas fa-phone input-icon"></i>
            </div>

            <!-- Company name field (shown for company/agency roles) -->
            <div class="relative mb-3" id="companyField" style="display: none;">
                <input type="text" id="registerCompany" class="auth-input" placeholder="Company/Agency name" autocomplete="organization">
                <i class="fas fa-building input-icon"></i>
            </div>

            <div class="relative mb-2">
                <input type="password" id="registerPassword" class="auth-input" placeholder="Create a password" required autocomplete="new-password" style="padding-right: 42px;" oninput="checkPasswordStrength(this.value)">
                <i class="fas fa-lock input-icon"></i>
                <button type="button" onclick="togglePassword('registerPassword', this)" class="password-toggle" aria-label="Show password">
                    <i class="fas fa-eye"></i>
                </button>
            </div>

            <!-- Password strength -->
            <div class="mb-4">
                <div class="flex items-center gap-1.5 mb-1.5">
                    <div class="strength-bar"><div id="strengthFill" class="strength-fill"></div></div>
                    <span id="strengthLabel" class="text-[9px] font-bold text-slate-400 w-14 text-right">Weak</span>
                </div>
                <ul class="text-[9px] text-slate-400 space-y-0.5 pl-1">
                    <li id="reqLength" class="flex items-center gap-1"><i class="fas fa-circle text-[5px]"></i> At least 8 characters</li>
                    <li id="reqUpper" class="flex items-center gap-1"><i class="fas fa-circle text-[5px]"></i> One uppercase letter</li>
                    <li id="reqNumber" class="flex items-center gap-1"><i class="fas fa-circle text-[5px]"></i> One number</li>
                </ul>
            </div>

            <div class="relative mb-4">
                <input type="password" id="registerConfirm" class="auth-input" placeholder="Confirm password" required autocomplete="new-password" style="padding-right: 42px;">
                <i class="fas fa-lock input-icon"></i>
                <button type="button" onclick="togglePassword('registerConfirm', this)" class="password-toggle" aria-label="Show password">
                    <i class="fas fa-eye"></i>
                </button>
            </div>

            <label class="flex items-start gap-2.5 cursor-pointer mb-4">
                <input type="checkbox" class="auth-checkbox mt-0.5" required>
                <span class="text-[11px] text-slate-500 leading-relaxed">
                    I agree to the <a href="#" class="text-accentBlue hover:underline font-semibold">Terms of Service</a> and <a href="#" class="text-accentBlue hover:underline font-semibold">Privacy Policy</a>
                </span>
            </label>

            <button type="submit" id="registerBtn" class="btn-primary">
                <span>Create Account</span>
                <i class="fas fa-arrow-right text-xs"></i>
            </button>
        </form>

        <!-- Footer -->
        <div class="px-6 pb-6 text-center border-t border-slate-100 pt-4">
            <p class="text-xs text-slate-500">
                Already have an account?
                <button onclick="closeModal('register'); openModal('login')" class="text-accentBlue font-bold hover:underline ml-1">Sign in</button>
            </p>
        </div>
    </div>
</div>


<!-- ============================================================
     MODAL: FORGOT PASSWORD
     ============================================================ -->
<div id="modal-forgot" class="auth-modal" aria-hidden="true">
    <div class="auth-modal-backdrop" onclick="closeModal('forgot')"></div>
    <div class="auth-modal-panel">
        
        <!-- Header -->
        <div class="relative px-6 pt-6 pb-5 border-b border-slate-100">
            <button onclick="closeModal('forgot')" class="absolute top-5 right-5 w-8 h-8 flex items-center justify-center rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors">
                <i class="fas fa-times text-sm"></i>
            </button>
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center mb-3">
                <i class="fas fa-key text-white text-lg"></i>
            </div>
            <h3 class="font-serif text-2xl text-navy font-semibold">Forgot password?</h3>
            <p class="text-slate-400 text-xs mt-1">No worries &mdash; we'll send you a reset code.</p>
        </div>

        <!-- Body -->
        <form onsubmit="handleForgot(event)" class="px-6 py-5 space-y-4">
            <div class="relative">
                <input type="email" id="forgotEmail" class="auth-input" placeholder="Enter your email address" required autocomplete="email">
                <i class="fas fa-envelope input-icon"></i>
            </div>

            <button type="submit" id="forgotBtn" class="btn-primary">
                <span>Send Reset Code</span>
                <i class="fas fa-paper-plane text-xs"></i>
            </button>

            <div class="flex items-start gap-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                <i class="fas fa-info-circle text-blue-500 text-xs mt-0.5"></i>
                <p class="text-[10px] text-blue-700 leading-relaxed">
                    If an account exists with this email, you'll receive a 6-digit verification code valid for 15 minutes.
                </p>
            </div>

            <button type="button" onclick="closeModal('forgot'); openModal('login')" class="w-full text-center text-xs text-slate-500 hover:text-accentBlue transition-colors">
                <i class="fas fa-arrow-left mr-1"></i> Back to sign in
            </button>
        </form>
    </div>
</div>


<!-- ============================================================
     MODAL: RESET PASSWORD (with OTP)
     ============================================================ -->
<div id="modal-reset" class="auth-modal" aria-hidden="true">
    <div class="auth-modal-backdrop" onclick="closeModal('reset')"></div>
    <div class="auth-modal-panel">
        
        <!-- Header -->
        <div class="relative px-6 pt-6 pb-5 border-b border-slate-100">
            <button onclick="closeModal('reset')" class="absolute top-5 right-5 w-8 h-8 flex items-center justify-center rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors">
                <i class="fas fa-times text-sm"></i>
            </button>
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-700 flex items-center justify-center mb-3">
                <i class="fas fa-shield-alt text-white text-lg"></i>
            </div>
            <h3 class="font-serif text-2xl text-navy font-semibold">Reset your password</h3>
            <p class="text-slate-400 text-xs mt-1">Enter the code sent to <span id="resetEmailDisplay" class="font-semibold text-navy">your email</span></p>
        </div>

        <!-- Body -->
        <form onsubmit="handleReset(event)" class="px-6 py-5 space-y-4">
            <!-- OTP inputs -->
            <div>
                <p class="text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-2 text-center">Verification Code</p>
                <div class="flex justify-center gap-2" id="otpContainer">
                    <input type="text" class="otp-input" maxlength="1" inputmode="numeric" autocomplete="one-time-code" data-index="0">
                    <input type="text" class="otp-input" maxlength="1" inputmode="numeric" data-index="1">
                    <input type="text" class="otp-input" maxlength="1" inputmode="numeric" data-index="2">
                    <input type="text" class="otp-input" maxlength="1" inputmode="numeric" data-index="3">
                    <input type="text" class="otp-input" maxlength="1" inputmode="numeric" data-index="4">
                    <input type="text" class="otp-input" maxlength="1" inputmode="numeric" data-index="5">
                </div>
                <p class="text-center text-[10px] text-slate-400 mt-2">
                    Didn't receive it?
                    <button type="button" onclick="resendCode()" class="text-accentBlue font-bold hover:underline">Resend code</button>
                    <span id="resendTimer" class="hidden text-slate-400">(60s)</span>
                </p>
            </div>

            <div class="relative">
                <input type="password" id="resetPassword" class="auth-input" placeholder="New password" required autocomplete="new-password" style="padding-right: 42px;">
                <i class="fas fa-lock input-icon"></i>
                <button type="button" onclick="togglePassword('resetPassword', this)" class="password-toggle" aria-label="Show password">
                    <i class="fas fa-eye"></i>
                </button>
            </div>

            <div class="relative">
                <input type="password" id="resetConfirm" class="auth-input" placeholder="Confirm new password" required autocomplete="new-password" style="padding-right: 42px;">
                <i class="fas fa-lock input-icon"></i>
                <button type="button" onclick="togglePassword('resetConfirm', this)" class="password-toggle" aria-label="Show password">
                    <i class="fas fa-eye"></i>
                </button>
            </div>

            <button type="submit" id="resetBtn" class="btn-primary">
                <span>Reset Password</span>
                <i class="fas fa-check text-xs"></i>
            </button>
        </form>
    </div>
</div>


<!-- ============================================================
     MODAL: SUCCESS
     ============================================================ -->
<div id="modal-success" class="auth-modal" aria-hidden="true">
    <div class="auth-modal-backdrop" onclick="closeModal('success')"></div>
    <div class="auth-modal-panel" style="max-width: 420px;">
        <div class="px-6 py-10 text-center">
            <div class="w-20 h-20 rounded-full bg-emerald-100 flex items-center justify-center mx-auto mb-5 check-pop">
                <i class="fas fa-check-circle text-emerald-500 text-4xl"></i>
            </div>
            <h3 id="successTitle" class="font-serif text-2xl text-navy font-semibold mb-2">Success!</h3>
            <p id="successMessage" class="text-slate-500 text-sm leading-relaxed mb-6">Your action was completed successfully.</p>
            <button onclick="closeModal('success')" class="btn-primary">
                <i class="fas fa-arrow-right text-xs"></i>
                <span>Continue</span>
            </button>
        </div>
    </div>
</div>


<!-- ================= AUTH PAGE SCRIPTS ================= -->
<script>
/* ============================================================
   MODAL CONTROLLER
   ============================================================ */
function openModal(modalId) {
    // Close all modals first
    document.querySelectorAll('.auth-modal').forEach(m => {
        m.classList.remove('active');
        m.setAttribute('aria-hidden', 'true');
    });
    
    const modal = document.getElementById('modal-' + modalId);
    if (modal) {
        modal.classList.add('active');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        
        // Autofocus first input
        setTimeout(() => {
            const firstInput = modal.querySelector('input:not([type="checkbox"]):not([type="hidden"])');
            if (firstInput && firstInput.offsetParent !== null) firstInput.focus();
        }, 300);
    }
}

function closeModal(modalId) {
    const modal = document.getElementById('modal-' + modalId);
    if (modal) {
        modal.classList.remove('active');
        modal.setAttribute('aria-hidden', 'true');
    }
    
    // If all modals closed, restore body scroll
    const anyActive = document.querySelector('.auth-modal.active');
    if (!anyActive) {
        document.body.style.overflow = '';
    }
    
    // Reset forms on close
    if (modalId === 'login' || modalId === 'register' || modalId === 'forgot' || modalId === 'reset') {
        const form = modal?.querySelector('form');
        if (form) form.reset();
        
        // Reset register role selector
        if (modalId === 'register') {
            document.querySelectorAll('.role-card').forEach(c => c.classList.remove('selected'));
            document.querySelector('[data-role="client"]').classList.add('selected');
            document.getElementById('registerRole').value = 'client';
            document.getElementById('companyField').style.display = 'none';
            document.getElementById('registerCompany').required = false;
            
            // Reset password strength
            const fill = document.getElementById('strengthFill');
            const label = document.getElementById('strengthLabel');
            if (fill) { fill.style.width = '0%'; label.textContent = 'Weak'; label.className = 'text-[9px] font-bold text-slate-400 w-14 text-right'; }
            ['reqLength','reqUpper','reqNumber'].forEach(id => {
                const el = document.getElementById(id);
                if (el) { el.innerHTML = '<i class="fas fa-circle text-[5px]"></i> ' + el.textContent.trim().replace(/^./, ''); el.classList.remove('text-emerald-600'); }
            });
        }
        
        // Reset OTP inputs
        if (modalId === 'reset') {
            document.querySelectorAll('#otpContainer .otp-input').forEach(inp => inp.value = '');
            document.getElementById('resendTimer').classList.add('hidden');
            const btn = document.querySelector('#modal-reset button[onclick="resendCode()"]');
            if (btn) btn.style.display = 'inline';
        }
    }
}

/* ============================================================
   ROLE SELECTOR
   ============================================================ */
function selectRole(el, role) {
    document.querySelectorAll('.role-card').forEach(c => c.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('registerRole').value = role;

    const companyField = document.getElementById('companyField');
    if (role === 'company') {
        companyField.style.display = 'block';
        document.getElementById('registerCompany').required = true;
    } else {
        companyField.style.display = 'none';
        document.getElementById('registerCompany').required = false;
    }
}

/* ============================================================
   PASSWORD TOGGLE
   ============================================================ */
function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

/* ============================================================
   PASSWORD STRENGTH
   ============================================================ */
function checkPasswordStrength(password) {
    const fill = document.getElementById('strengthFill');
    const label = document.getElementById('strengthLabel');
    const reqLength = document.getElementById('reqLength');
    const reqUpper = document.getElementById('reqUpper');
    const reqNumber = document.getElementById('reqNumber');

    let score = 0;

    if (password.length >= 8) {
        score++;
        reqLength.innerHTML = '<i class="fas fa-check-circle text-emerald-500 text-[8px]"></i> At least 8 characters';
        reqLength.classList.add('text-emerald-600');
    } else {
        reqLength.innerHTML = '<i class="fas fa-circle text-[5px]"></i> At least 8 characters';
        reqLength.classList.remove('text-emerald-600');
    }

    if (/[A-Z]/.test(password)) {
        score++;
        reqUpper.innerHTML = '<i class="fas fa-check-circle text-emerald-500 text-[8px]"></i> One uppercase letter';
        reqUpper.classList.add('text-emerald-600');
    } else {
        reqUpper.innerHTML = '<i class="fas fa-circle text-[5px]"></i> One uppercase letter';
        reqUpper.classList.remove('text-emerald-600');
    }

    if (/[0-9]/.test(password)) {
        score++;
        reqNumber.innerHTML = '<i class="fas fa-check-circle text-emerald-500 text-[8px]"></i> One number';
        reqNumber.classList.add('text-emerald-600');
    } else {
        reqNumber.innerHTML = '<i class="fas fa-circle text-[5px]"></i> One number';
        reqNumber.classList.remove('text-emerald-600');
    }

    if (password.length === 0) {
        fill.style.width = '0%';
        label.textContent = 'Weak';
        label.className = 'text-[9px] font-bold text-slate-400 w-14 text-right';
    } else if (score <= 1) {
        fill.style.width = '33%';
        fill.style.background = '#EF4444';
        label.textContent = 'Weak';
        label.className = 'text-[9px] font-bold text-red-500 w-14 text-right';
    } else if (score === 2) {
        fill.style.width = '66%';
        fill.style.background = '#F59E0B';
        label.textContent = 'Medium';
        label.className = 'text-[9px] font-bold text-amber-500 w-14 text-right';
    } else {
        fill.style.width = '100%';
        fill.style.background = '#10B981';
        label.textContent = 'Strong';
        label.className = 'text-[9px] font-bold text-emerald-600 w-14 text-right';
    }
}

/* ============================================================
   AUTH HANDLERS
   ============================================================ */
async function handleLogin(e) {
    e.preventDefault();
    const btn = document.getElementById('loginBtn');
    const original = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-circle-notch spin"></i><span>Signing in&hellip;</span>';
    btn.disabled = true;

    const formData = new FormData();
    formData.append('action', 'login');
    formData.append('email', document.getElementById('loginEmail').value);
    formData.append('password', document.getElementById('loginPassword').value);
    formData.append('remember', (document.querySelector('#modal-login .auth-checkbox')?.checked) ? '1' : '0');

    try {
        const response = await fetch('<?= BASE_PATH ?>api/auth.php', {
            method: 'POST',
            credentials: 'include',
            body: formData
        });
        const data = await response.json();

        btn.innerHTML = original;
        btn.disabled = false;

        if (data.success) {
            document.getElementById('loginSuccess').classList.remove('hidden');
            document.getElementById('loginSuccess').classList.add('flex');
            
            setTimeout(() => {
                closeModal('login');
                if (data.user && data.user.role === 'admin') {
                    // Admin user - redirect to admin page
                    window.location.href = '<?= BASE_PATH ?>?page=admin';
                } else if (data.user && data.user.first_login) {
                    // First login ever - redirect to profile to complete profile
                    window.location.href = '<?= BASE_PATH ?>?page=profiles&welcome=1';
                } else {
                    // Returning user - redirect to home page
                    window.location.href = '<?= BASE_PATH ?>';
                }
            }, 1500);
        } else {
            showToast(data.message || 'Login failed', 'error');
        }
    } catch (err) {
        btn.innerHTML = original;
        btn.disabled = false;
        showToast('Network error. Please try again.', 'error');
    }
}

async function handleRegister(e) {
    e.preventDefault();
    const pass = document.getElementById('registerPassword').value;
    const confirm = document.getElementById('registerConfirm').value;

    if (pass !== confirm) {
        showToast('Passwords do not match', 'error');
        return;
    }
    if (pass.length < 8) {
        showToast('Password must be at least 8 characters', 'error');
        return;
    }

    const btn = document.getElementById('registerBtn');
    const original = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-circle-notch spin"></i><span>Creating account&hellip;</span>';
    btn.disabled = true;

    const formData = new FormData();
    formData.append('action', 'register');
    formData.append('first_name', document.getElementById('registerFirstName').value);
    formData.append('last_name', document.getElementById('registerLastName').value);
    formData.append('email', document.getElementById('registerEmail').value);
    formData.append('phone', document.getElementById('registerPhone').value);
    formData.append('password', pass);
    formData.append('confirm_password', confirm);
    formData.append('role', document.getElementById('registerRole').value);
    formData.append('company_name', document.getElementById('registerCompany').value);

    try {
        const response = await fetch('<?= BASE_PATH ?>api/auth.php', {
            method: 'POST',
            credentials: 'include',
            body: formData
        });
        const data = await response.json();

        btn.innerHTML = original;
        btn.disabled = false;

        if (data.success) {
            closeModal('register');
            showSuccess('Account created!', 'Welcome to HAVENTRA. Redirecting to your dashboard&hellip;');
            
            setTimeout(() => {
                closeModal('success');
                // New account = first login, redirect to profile
                window.location.href = '<?= BASE_PATH ?>?page=profiles&welcome=1';
            }, 2000);
        } else {
            showToast(data.message || 'Registration failed', 'error');
        }
    } catch (err) {
        btn.innerHTML = original;
        btn.disabled = false;
        showToast('Network error. Please try again.', 'error');
    }
}

async function handleForgot(e) {
    e.preventDefault();
    const email = document.getElementById('forgotEmail').value;
    const btn = document.getElementById('forgotBtn');
    const original = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-circle-notch spin"></i><span>Sending&hellip;</span>';
    btn.disabled = true;

    const formData = new FormData();
    formData.append('action', 'forgot');
    formData.append('email', email);

    try {
        const response = await fetch('<?= BASE_PATH ?>api/auth.php', {
            method: 'POST',
            credentials: 'include',
            body: formData
        });
        const data = await response.json();

        btn.innerHTML = original;
        btn.disabled = false;

        if (data.success) {
            document.getElementById('resetEmailDisplay').textContent = email;
            closeModal('forgot');
            openModal('reset');
        } else {
            showToast(data.message || 'Failed to send reset code', 'error');
        }
    } catch (err) {
        btn.innerHTML = original;
        btn.disabled = false;
        showToast('Network error. Please try again.', 'error');
    }
}

async function handleReset(e) {
    e.preventDefault();
    const pass = document.getElementById('resetPassword').value;
    const confirm = document.getElementById('resetConfirm').value;

    if (pass !== confirm) {
        showToast('Passwords do not match', 'error');
        return;
    }
    if (pass.length < 8) {
        showToast('Password must be at least 8 characters', 'error');
        return;
    }

    // Check OTP filled
    const otpInputs = document.querySelectorAll('#otpContainer .otp-input');
    let otp = '';
    otpInputs.forEach(inp => otp += inp.value);
    if (otp.length !== 6) {
        showToast('Please enter the 6-digit verification code', 'error');
        return;
    }

    const btn = document.getElementById('resetBtn');
    const original = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-circle-notch spin"></i><span>Resetting&hellip;</span>';
    btn.disabled = true;

    const formData = new FormData();
    formData.append('action', 'reset');
    formData.append('otp', otp);
    formData.append('password', pass);
    formData.append('confirm_password', confirm);
    formData.append('email', document.getElementById('resetEmailDisplay').textContent);

    try {
        const response = await fetch('<?= BASE_PATH ?>api/auth.php', {
            method: 'POST',
            credentials: 'include',
            body: formData
        });
        const data = await response.json();

        btn.innerHTML = original;
        btn.disabled = false;

        if (data.success) {
            closeModal('reset');
            showSuccess('Password reset!', 'Your password has been reset successfully. You can now sign in with your new password.');
        } else {
            showToast(data.message || 'Password reset failed', 'error');
        }
    } catch (err) {
        btn.innerHTML = original;
        btn.disabled = false;
        showToast('Network error. Please try again.', 'error');
    }
}

/* ============================================================
   SUCCESS MODAL
   ============================================================ */
function showSuccess(title, message) {
    document.getElementById('successTitle').textContent = title;
    document.getElementById('successMessage').textContent = message;
    openModal('success');
}

/* ============================================================
   TOAST NOTIFICATIONS
   ============================================================ */
function showToast(message, type = 'error') {
    const bgColor = type === 'error' ? 'bg-red-500' : 'bg-emerald-500';
    const icon = type === 'error' ? 'fa-exclamation-circle' : 'fa-check-circle';
    
    const toast = document.createElement('div');
    toast.className = 'fixed top-5 left-1/2 -translate-x-1/2 ' + bgColor + ' text-white text-xs font-semibold px-5 py-3 rounded-xl shadow-2xl z-[2000] flex items-center gap-2';
    toast.innerHTML = '<i class="fas ' + icon + '"></i> ' + message;
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transition = 'opacity 0.3s';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

/* ============================================================
   OTP INPUT HANDLING
   ============================================================ */
const otpInputs = document.querySelectorAll('#otpContainer .otp-input');

otpInputs.forEach((input, index) => {
    input.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
        if (this.value && index < otpInputs.length - 1) {
            otpInputs[index + 1].focus();
        }
    });

    input.addEventListener('keydown', function(e) {
        if (e.key === 'Backspace' && !this.value && index > 0) {
            otpInputs[index - 1].focus();
        }
        if (e.key === 'ArrowLeft' && index > 0) otpInputs[index - 1].focus();
        if (e.key === 'ArrowRight' && index < otpInputs.length - 1) otpInputs[index + 1].focus();
    });

    input.addEventListener('paste', function(e) {
        e.preventDefault();
        const paste = (e.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '');
        for (let i = 0; i < otpInputs.length && i < paste.length; i++) {
            otpInputs[i].value = paste[i];
        }
        const nextEmpty = Array.from(otpInputs).findIndex(inp => !inp.value);
        if (nextEmpty !== -1) otpInputs[nextEmpty].focus();
        else otpInputs[otpInputs.length - 1].focus();
    });
});

/* ============================================================
   RESEND CODE TIMER
   ============================================================ */
let resendInterval;
function resendCode() {
    const timer = document.getElementById('resendTimer');
    const btn = event.target;
    
    btn.style.display = 'none';
    timer.classList.remove('hidden');
    
    let seconds = 60;
    timer.textContent = `(${seconds}s)`;
    
    clearInterval(resendInterval);
    resendInterval = setInterval(() => {
        seconds--;
        timer.textContent = `(${seconds}s)`;
        if (seconds <= 0) {
            clearInterval(resendInterval);
            timer.classList.add('hidden');
            btn.style.display = 'inline';
        }
    }, 1000);
    
    // Show toast
    const toast = document.createElement('div');
    toast.className = 'fixed top-5 left-1/2 -translate-x-1/2 bg-emerald-500 text-white text-xs font-semibold px-5 py-3 rounded-xl shadow-2xl z-[2000] flex items-center gap-2';
    toast.innerHTML = '<i class="fas fa-check-circle"></i> New code sent to your email';
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transition = 'opacity 0.3s';
        setTimeout(() => toast.remove(), 300);
    }, 2500);
}

/* ============================================================
   SOCIAL LOGIN (placeholder)
   ============================================================ */
function socialLogin(provider) {
    showToast('Connecting to ' + provider + '&hellip; In production, this would open the OAuth flow.', 'info');
    setTimeout(() => closeModal('success'), 2500);
}

/* ============================================================
   AUTO-OPEN MODAL BASED ON URL PARAMETER
   ============================================================ */
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const page = urlParams.get('page');
    if (page === 'login') {
        openModal('login');
    } else if (page === 'register') {
        openModal('register');
    }
    
    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.auth-modal.active').forEach(m => {
                const id = m.id.replace('modal-', '');
                closeModal(id);
            });
        }
    });
});
</script>