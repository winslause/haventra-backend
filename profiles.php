<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard — HAVENTRA</title>
    <meta name="description" content="Manage your HAVENTRA account — properties, bookings, messages, and settings.">
    <meta name="robots" content="noindex, nofollow">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=Montserrat:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: '#00215E',
                        navyLight: '#001540',
                        accentOrange: '#F59E0B',
                        accentBlue: '#005BB5',
                        dashPurple: '#6D28D9',
                        dashTeal: '#0D9488',
                        dashGreen: '#059669',
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'Montserrat', 'sans-serif'],
                        serif: ['Cormorant Garamond', 'serif'],
                    }
                }
            }
        }
    </script>

    <style>
        * { -webkit-tap-highlight-color: transparent; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* Sidebar nav link */
        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 500;
            color: #64748B;
            transition: all 0.25s ease;
            cursor: pointer;
        }
        .nav-link:hover { background: #F1F5F9; color: #00215E; }
        .nav-link.active {
            background: linear-gradient(135deg, #00215E, #005BB5);
            color: white;
            box-shadow: 0 6px 16px -4px rgba(0, 91, 181, 0.4);
        }
        .nav-link.active .nav-icon { color: #F59E0B; }
        .nav-icon { font-size: 14px; width: 18px; text-align: center; transition: color 0.25s ease; }

        /* Card hover */
        .stat-card { transition: all 0.35s cubic-bezier(0.25, 0.8, 0.25, 1); }
        .stat-card:hover { transform: translateY(-4px); box-shadow: 0 20px 30px -10px rgba(0, 0, 0, 0.1); }

        /* Tab pill */
        .dash-tab {
            padding: 8px 16px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 600;
            color: #64748B;
            background: white;
            border: 1px solid #E2E8F0;
            transition: all 0.25s ease;
            cursor: pointer;
            white-space: nowrap;
        }
        .dash-tab:hover { border-color: #005BB5; color: #005BB5; }
        .dash-tab.active {
            background: #00215E;
            color: white;
            border-color: #00215E;
            box-shadow: 0 4px 12px rgba(0, 33, 94, 0.25);
        }

        /* Progress bar */
        .progress-bar {
            height: 6px;
            border-radius: 9999px;
            background: #E2E8F0;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            border-radius: 9999px;
            transition: width 0.8s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        /* Scrollbars */
        .thin-scroll::-webkit-scrollbar { width: 5px; }
        .thin-scroll::-webkit-scrollbar-track { background: transparent; }
        .thin-scroll::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 3px; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* Fade in */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .anim-fade-in { animation: fadeIn 0.4s ease both; }

        /* Pulse dot */
        @keyframes pulseDot {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }
        .pulse-dot { animation: pulseDot 2s infinite; }

        /* Sidebar overlay */
        #sidebarOverlay { display: none; }
        #sidebarOverlay.active { display: block; }

        @media (max-width: 1023px) {
            #sidebar { transform: translateX(-100%); transition: transform 0.3s ease; }
            #sidebar.open { transform: translateX(0); }
        }

        /* Role switcher */
        .role-btn {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            transition: all 0.25s ease;
            cursor: pointer;
        }
        .role-btn.active {
            background: white;
            color: #00215E;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }
        .role-btn:not(.active) { color: rgba(255, 255, 255, 0.7); }
        .role-btn:not(.active):hover { color: white; }

        /* Property item */
        .listing-item {
            transition: all 0.25s ease;
            border-radius: 12px;
            border: 1px solid #F1F5F9;
        }
        .listing-item:hover {
            border-color: #CBD5E1;
            box-shadow: 0 4px 12px -2px rgba(0, 0, 0, 0.06);
        }

        /* Message row */
        .message-row {
            transition: background 0.2s ease;
            border-radius: 10px;
        }
        .message-row:hover { background: #F8FAFC; }
        .message-row.unread { background: #EFF6FF; }

        /* Hidden sections */
        .dash-section { display: none; }
        .dash-section.active { display: block; animation: fadeIn 0.35s ease both; }
    </style>
</head>
<body class="bg-[#F1F5F9] text-gray-800 antialiased">


<!-- ================= MOBILE OVERLAY ================= -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 lg:hidden" onclick="closeSidebar()"></div>


<!-- ================= SIDEBAR ================= -->
<aside id="sidebar" class="fixed top-0 left-0 h-full w-[260px] bg-white z-50 lg:z-30 shadow-xl lg:shadow-none lg:border-r lg:border-slate-200 flex flex-col">

    <!-- Brand -->
    <div class="px-5 py-5 border-b border-slate-100 flex items-center justify-between">
        <a href="/" class="flex items-center gap-2">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-navy to-accentBlue flex items-center justify-center">
                <i class="fas fa-home text-white text-sm"></i>
            </div>
            <div>
                <p class="font-serif text-lg text-navy font-semibold leading-none">HAVENTRA</p>
                <p class="text-[9px] text-slate-400 uppercase tracking-widest font-semibold">Dashboard</p>
            </div>
        </a>
        <button onclick="closeSidebar()" class="lg:hidden w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100">
            <i class="fas fa-times text-sm"></i>
        </button>
    </div>

    <!-- User profile card -->
    <div class="px-4 py-4 border-b border-slate-100">
        <div class="flex items-center gap-3 mb-3">
            <div class="relative">
                <div class="w-11 h-11 rounded-full bg-gradient-to-br from-accentOrange to-orange-600 flex items-center justify-center text-white font-bold text-sm">
                    JM
                </div>
                <span class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 bg-emerald-500 border-2 border-white rounded-full"></span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-navy truncate">Jane Mwangi</p>
                <p class="text-[10px] text-slate-400 truncate">jane.mwangi@email.com</p>
            </div>
        </div>

        <!-- Role switcher -->
        <div class="bg-navy rounded-xl p-1 flex gap-1">
            <button onclick="switchRole('client')" class="role-btn active flex-1" data-role="client">Client</button>
            <button onclick="switchRole('owner')" class="role-btn flex-1" data-role="owner">Owner</button>
            <button onclick="switchRole('company')" class="role-btn flex-1" data-role="company">Company</button>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto thin-scroll px-3 py-4">
        <p class="text-[9px] uppercase tracking-widest font-bold text-slate-400 px-3 mb-2">Main</p>
        <div class="space-y-1">
            <a onclick="showSection('overview', this)" class="nav-link active">
                <i class="fas fa-th-large nav-icon"></i>
                <span>Overview</span>
            </a>
            <a onclick="showSection('properties', this)" class="nav-link">
                <i class="fas fa-building nav-icon"></i>
                <span>My Properties</span>
                <span class="ml-auto text-[9px] bg-accentOrange/15 text-accentOrange font-bold px-1.5 py-0.5 rounded-full">8</span>
            </a>
            <a onclick="showSection('bookings', this)" class="nav-link">
                <i class="fas fa-calendar-check nav-icon"></i>
                <span>Bookings</span>
                <span class="ml-auto text-[9px] bg-accentBlue/15 text-accentBlue font-bold px-1.5 py-0.5 rounded-full">3</span>
            </a>
            <a onclick="showSection('messages', this)" class="nav-link">
                <i class="fas fa-comments nav-icon"></i>
                <span>Messages</span>
                <span class="ml-auto text-[9px] bg-red-500 text-white font-bold px-1.5 py-0.5 rounded-full">5</span>
            </a>
            <a onclick="showSection('favorites', this)" class="nav-link">
                <i class="fas fa-heart nav-icon"></i>
                <span>Favorites</span>
            </a>
        </div>

        <p class="text-[9px] uppercase tracking-widest font-bold text-slate-400 px-3 mt-5 mb-2">Finance</p>
        <div class="space-y-1">
            <a onclick="showSection('payments', this)" class="nav-link">
                <i class="fas fa-wallet nav-icon"></i>
                <span>Payments</span>
            </a>
            <a onclick="showSection('analytics', this)" class="nav-link">
                <i class="fas fa-chart-line nav-icon"></i>
                <span>Analytics</span>
            </a>
        </div>

        <p class="text-[9px] uppercase tracking-widest font-bold text-slate-400 px-3 mt-5 mb-2">Account</p>
        <div class="space-y-1">
            <a onclick="showSection('profile', this)" class="nav-link">
                <i class="fas fa-user-circle nav-icon"></i>
                <span>Profile</span>
            </a>
            <a onclick="showSection('settings', this)" class="nav-link">
                <i class="fas fa-cog nav-icon"></i>
                <span>Settings</span>
            </a>
            <a onclick="showSection('help', this)" class="nav-link">
                <i class="fas fa-question-circle nav-icon"></i>
                <span>Help & Support</span>
            </a>
        </div>
    </nav>

    <!-- Upgrade card -->
    <div id="upgradeCard" class="px-3 pb-3">
        <div class="bg-gradient-to-br from-navy to-accentBlue rounded-2xl p-4 text-white relative overflow-hidden">
            <button onclick="closeUpgradeCard()" class="absolute top-2 right-2 w-6 h-6 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 text-white/80 hover:text-white transition-colors z-10" aria-label="Close upgrade card">
                <i class="fas fa-times text-[10px]"></i>
            </button>
            <div class="absolute -top-6 -right-6 w-20 h-20 bg-white/10 rounded-full"></div>
            <div class="relative">
                <i class="fas fa-crown text-accentOrange text-lg mb-2"></i>
                <p class="text-xs font-bold mb-1">Upgrade to Pro</p>
                <p class="text-[10px] text-white/70 leading-relaxed mb-2.5">Unlock premium features and unlimited listings.</p>
                <button class="w-full bg-accentOrange text-navy text-[10px] font-bold py-2 rounded-lg hover:bg-white transition-colors">
                    Upgrade Now
                </button>
            </div>
        </div>
    </div>

    <!-- Logout -->
    <div class="px-4 py-3 border-t border-slate-100">
        <button class="w-full flex items-center gap-2 text-xs text-slate-500 hover:text-red-500 font-medium transition-colors">
            <i class="fas fa-sign-out-alt"></i>
            <span>Sign Out</span>
        </button>
    </div>
</aside>


<!-- ================= MAIN CONTENT ================= -->
<div class="lg:ml-[260px] min-h-screen">

    <!-- Top bar -->
    <header class="sticky top-0 z-20 bg-white/80 backdrop-blur-md border-b border-slate-200">
        <div class="px-4 md:px-6 py-3 flex items-center justify-between gap-3">
            
            <div class="flex items-center gap-3">
                <button onclick="openSidebar()" class="lg:hidden w-9 h-9 flex items-center justify-center rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors">
                    <i class="fas fa-bars text-sm"></i>
                </button>
                <div>
                    <h1 id="pageTitle" class="font-serif text-lg md:text-xl text-navy font-semibold leading-tight">Overview</h1>
                    <p id="pageSubtitle" class="text-[10px] md:text-xs text-slate-400">Welcome back, Jane!</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <!-- Search -->
                <div class="hidden md:flex items-center gap-2 bg-slate-100 rounded-lg px-3 py-2 w-56">
                    <i class="fas fa-search text-slate-400 text-xs"></i>
                    <input type="text" placeholder="Search…" class="bg-transparent text-xs outline-none w-full text-slate-600 placeholder-slate-400">
                </div>

                <!-- Notifications -->
                <button class="relative w-9 h-9 flex items-center justify-center rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors">
                    <i class="fas fa-bell text-sm"></i>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full pulse-dot"></span>
                </button>

                <!-- Messages -->
                <button onclick="showSection('messages', document.querySelectorAll('.nav-link')[3])" class="relative w-9 h-9 flex items-center justify-center rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors">
                    <i class="fas fa-envelope text-sm"></i>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-accentBlue rounded-full"></span>
                </button>

                <!-- Avatar -->
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-accentOrange to-orange-600 flex items-center justify-center text-white font-bold text-[10px] cursor-pointer">
                    JM
                </div>
            </div>
        </div>
    </header>

    <!-- Content area -->
    <main class="p-4 md:p-6 pb-20">

        <!-- ================= OVERVIEW ================= -->
        <section id="section-overview" class="dash-section active">

            <!-- Welcome banner -->
            <div class="bg-gradient-to-br from-navy via-navyLight to-accentBlue rounded-2xl p-5 md:p-7 text-white mb-5 relative overflow-hidden">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/5 rounded-full"></div>
                <div class="absolute -bottom-8 -left-8 w-32 h-32 bg-accentOrange/10 rounded-full"></div>
                <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <p class="text-[10px] uppercase tracking-widest text-white/60 font-bold mb-1">Welcome back</p>
                        <h2 class="font-serif text-2xl md:text-3xl font-semibold mb-1">Hello, Jane 👋</h2>
                        <p class="text-white/70 text-xs md:text-sm">You have <span class="text-accentOrange font-bold">3 new bookings</span> and <span class="text-accentOrange font-bold">5 unread messages</span>.</p>
                    </div>
                    <button onclick="showSection('properties', document.querySelectorAll('.nav-link')[1])" class="bg-accentOrange text-navy text-xs md:text-sm font-bold px-5 py-2.5 rounded-xl hover:bg-white transition-colors self-start md:self-center flex items-center gap-2">
                        <i class="fas fa-plus-circle"></i> Add New Listing
                    </button>
                </div>
            </div>

            <!-- Stats grid -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 mb-5">
                <div class="stat-card bg-white rounded-2xl p-4 border border-slate-100">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-lg bg-accentBlue/10 flex items-center justify-center">
                            <i class="fas fa-building text-accentBlue text-sm"></i>
                        </div>
                        <span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded-full">+2</span>
                    </div>
                    <p class="text-2xl font-bold text-navy">8</p>
                    <p class="text-[10px] text-slate-400 font-medium">Active Listings</p>
                </div>

                <div class="stat-card bg-white rounded-2xl p-4 border border-slate-100">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-lg bg-accentOrange/10 flex items-center justify-center">
                            <i class="fas fa-calendar-check text-accentOrange text-sm"></i>
                        </div>
                        <span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded-full">+3</span>
                    </div>
                    <p class="text-2xl font-bold text-navy">12</p>
                    <p class="text-[10px] text-slate-400 font-medium">Total Bookings</p>
                </div>

                <div class="stat-card bg-white rounded-2xl p-4 border border-slate-100">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-lg bg-dashGreen/10 flex items-center justify-center">
                            <i class="fas fa-eye text-dashGreen text-sm"></i>
                        </div>
                        <span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded-full">+18%</span>
                    </div>
                    <p class="text-2xl font-bold text-navy">2.4K</p>
                    <p class="text-[10px] text-slate-400 font-medium">Profile Views</p>
                </div>

                <div class="stat-card bg-white rounded-2xl p-4 border border-slate-100">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-lg bg-dashPurple/10 flex items-center justify-center">
                            <i class="fas fa-wallet text-dashPurple text-sm"></i>
                        </div>
                        <span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded-full">+12%</span>
                    </div>
                    <p class="text-2xl font-bold text-navy">KSh 245K</p>
                    <p class="text-[10px] text-slate-400 font-medium">Total Earnings</p>
                </div>
            </div>

            <!-- Two-column: Recent activity + Quick actions -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-5 mb-5">
                
                <!-- Recent activity -->
                <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 p-4 md:p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-serif text-lg text-navy font-semibold">Recent Activity</h3>
                        <button class="text-[10px] text-accentBlue font-semibold hover:underline">View All</button>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-start gap-3 pb-3 border-b border-slate-50">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-calendar-check text-emerald-600 text-xs"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-navy">New booking confirmed</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">Beachfront Villa · 2 guests · 3 nights</p>
                            </div>
                            <span class="text-[9px] text-slate-400 whitespace-nowrap">2h ago</span>
                        </div>
                        <div class="flex items-start gap-3 pb-3 border-b border-slate-50">
                            <div class="w-8 h-8 rounded-lg bg-accentBlue/10 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-comment text-accentBlue text-xs"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-navy">New message from David K.</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">"Is the property still available for viewing?"</p>
                            </div>
                            <span class="text-[9px] text-slate-400 whitespace-nowrap">5h ago</span>
                        </div>
                        <div class="flex items-start gap-3 pb-3 border-b border-slate-50">
                            <div class="w-8 h-8 rounded-lg bg-accentOrange/10 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-star text-accentOrange text-xs"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-navy">New 5-star review received</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">Luxury Safari Lodge · "Amazing experience!"</p>
                            </div>
                            <span class="text-[9px] text-slate-400 whitespace-nowrap">1d ago</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-dashPurple/10 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-wallet text-dashPurple text-xs"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-navy">Payment received</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">KSh 45,000 · Booking #HV-2451</p>
                            </div>
                            <span class="text-[9px] text-slate-400 whitespace-nowrap">2d ago</span>
                        </div>
                    </div>
                </div>

                <!-- Quick actions -->
                <div class="bg-white rounded-2xl border border-slate-100 p-4 md:p-5">
                    <h3 class="font-serif text-lg text-navy font-semibold mb-4">Quick Actions</h3>
                    <div class="space-y-2">
                        <button onclick="showSection('properties', document.querySelectorAll('.nav-link')[1])" class="w-full flex items-center gap-3 p-3 rounded-xl bg-slate-50 hover:bg-accentBlue/10 hover:text-accentBlue transition-colors group">
                            <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center shadow-sm group-hover:bg-accentBlue group-hover:text-white transition-colors">
                                <i class="fas fa-plus text-xs"></i>
                            </div>
                            <span class="text-xs font-semibold text-slate-700 group-hover:text-accentBlue">Add New Property</span>
                        </button>
                        <button onclick="showSection('bookings', document.querySelectorAll('.nav-link')[2])" class="w-full flex items-center gap-3 p-3 rounded-xl bg-slate-50 hover:bg-accentOrange/10 hover:text-accentOrange transition-colors group">
                            <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center shadow-sm group-hover:bg-accentOrange group-hover:text-white transition-colors">
                                <i class="fas fa-calendar text-xs"></i>
                            </div>
                            <span class="text-xs font-semibold text-slate-700 group-hover:text-accentOrange">View Bookings</span>
                        </button>
                        <button onclick="showSection('messages', document.querySelectorAll('.nav-link')[3])" class="w-full flex items-center gap-3 p-3 rounded-xl bg-slate-50 hover:bg-dashGreen/10 hover:text-dashGreen transition-colors group">
                            <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center shadow-sm group-hover:bg-dashGreen group-hover:text-white transition-colors">
                                <i class="fas fa-envelope text-xs"></i>
                            </div>
                            <span class="text-xs font-semibold text-slate-700 group-hover:text-dashGreen">Check Messages</span>
                        </button>
                        <button onclick="showSection('analytics', document.querySelectorAll('.nav-link')[5])" class="w-full flex items-center gap-3 p-3 rounded-xl bg-slate-50 hover:bg-dashPurple/10 hover:text-dashPurple transition-colors group">
                            <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center shadow-sm group-hover:bg-dashPurple group-hover:text-white transition-colors">
                                <i class="fas fa-chart-line text-xs"></i>
                            </div>
                            <span class="text-xs font-semibold text-slate-700 group-hover:text-dashPurple">View Analytics</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Profile completeness + Recent listings -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-5">
                
                <!-- Profile completeness -->
                <div class="bg-white rounded-2xl border border-slate-100 p-4 md:p-5">
                    <h3 class="font-serif text-lg text-navy font-semibold mb-3">Profile Completeness</h3>
                    <div class="flex items-center gap-4 mb-4">
                        <div class="relative w-20 h-20 flex-shrink-0">
                            <svg class="transform -rotate-90" width="80" height="80">
                                <circle cx="40" cy="40" r="34" stroke="#E2E8F0" stroke-width="6" fill="none"/>
                                <circle cx="40" cy="40" r="34" stroke="#005BB5" stroke-width="6" fill="none" 
                                        stroke-dasharray="213.6" stroke-dashoffset="51.3" stroke-linecap="round"/>
                            </svg>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="text-lg font-bold text-navy">76%</span>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-semibold text-navy mb-1">Almost there!</p>
                            <p class="text-[10px] text-slate-400 leading-relaxed">Complete your profile to build trust and get more bookings.</p>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex items-center gap-2 text-[10px]">
                            <i class="fas fa-check-circle text-emerald-500"></i>
                            <span class="text-slate-600 line-through">Profile photo</span>
                        </div>
                        <div class="flex items-center gap-2 text-[10px]">
                            <i class="fas fa-check-circle text-emerald-500"></i>
                            <span class="text-slate-600 line-through">Contact details</span>
                        </div>
                        <div class="flex items-center gap-2 text-[10px]">
                            <i class="fas fa-circle text-slate-300"></i>
                            <span class="text-slate-600">Verify your ID</span>
                        </div>
                        <div class="flex items-center gap-2 text-[10px]">
                            <i class="fas fa-circle text-slate-300"></i>
                            <span class="text-slate-600">Add payment method</span>
                        </div>
                    </div>
                </div>

                <!-- Recent listings -->
                <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 p-4 md:p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-serif text-lg text-navy font-semibold">My Recent Listings</h3>
                        <button onclick="showSection('properties', document.querySelectorAll('.nav-link')[1])" class="text-[10px] text-accentBlue font-semibold hover:underline">View All</button>
                    </div>
                    <div class="space-y-2">
                        <div class="listing-item p-3 flex items-center gap-3">
                            <img src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=200&q=80" alt="Property" class="w-14 h-14 rounded-lg object-cover flex-shrink-0">
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-navy truncate">Modern 3 Bed Villa</p>
                                <p class="text-[10px] text-slate-400 truncate"><i class="fas fa-map-marker-alt text-[8px] mr-1"></i>Kilimani, Nairobi</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-[9px] font-semibold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded">Active</span>
                                    <span class="text-[9px] text-slate-400">KSh 150,000/mo</span>
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <p class="text-[10px] font-bold text-navy">245</p>
                                <p class="text-[9px] text-slate-400">views</p>
                            </div>
                        </div>
                        <div class="listing-item p-3 flex items-center gap-3">
                            <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=200&q=80" alt="Property" class="w-14 h-14 rounded-lg object-cover flex-shrink-0">
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-navy truncate">Spacious Family Home</p>
                                <p class="text-[10px] text-slate-400 truncate"><i class="fas fa-map-marker-alt text-[8px] mr-1"></i>Karen, Nairobi</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-[9px] font-semibold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded">Active</span>
                                    <span class="text-[9px] text-slate-400">KSh 200,000/mo</span>
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <p class="text-[10px] font-bold text-navy">189</p>
                                <p class="text-[9px] text-slate-400">views</p>
                            </div>
                        </div>
                        <div class="listing-item p-3 flex items-center gap-3">
                            <img src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=200&q=80" alt="Property" class="w-14 h-14 rounded-lg object-cover flex-shrink-0">
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-navy truncate">Contemporary Townhouse</p>
                                <p class="text-[10px] text-slate-400 truncate"><i class="fas fa-map-marker-alt text-[8px] mr-1"></i>Kileleshwa, Nairobi</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-[9px] font-semibold text-amber-600 bg-amber-50 px-1.5 py-0.5 rounded">Pending</span>
                                    <span class="text-[9px] text-slate-400">KSh 25,000,000</span>
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <p class="text-[10px] font-bold text-navy">112</p>
                                <p class="text-[9px] text-slate-400">views</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>


        <!-- ================= PROPERTIES ================= -->
        <section id="section-properties" class="dash-section">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
                <div>
                    <h2 class="font-serif text-2xl text-navy font-semibold">My Properties</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Manage your listings — 8 total</p>
                </div>
                <button class="bg-navy text-white text-xs font-bold px-4 py-2.5 rounded-lg hover:bg-accentBlue transition-colors flex items-center gap-2 self-start sm:self-center">
                    <i class="fas fa-plus"></i> Add New Property
                </button>
            </div>

            <div class="flex gap-2 mb-4 overflow-x-auto no-scrollbar pb-1">
                <button class="dash-tab active">All (8)</button>
                <button class="dash-tab">Active (6)</button>
                <button class="dash-tab">Pending (1)</button>
                <button class="dash-tab">Draft (1)</button>
                <button class="dash-tab">Sold (0)</button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                <!-- Property card -->
                <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden hover:shadow-lg transition-all group">
                    <div class="relative h-40 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=600&q=80" alt="Property" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <span class="absolute top-2 left-2 bg-emerald-600 text-white text-[9px] font-bold px-2 py-0.5 rounded-full">Active</span>
                        <button class="absolute top-2 right-2 w-7 h-7 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-slate-600 hover:text-navy">
                            <i class="fas fa-ellipsis-h text-[10px]"></i>
                        </button>
                    </div>
                    <div class="p-3">
                        <h3 class="font-serif text-sm text-navy font-semibold truncate">Modern 3 Bed Villa</h3>
                        <p class="text-[10px] text-slate-400 mt-0.5"><i class="fas fa-map-marker-alt text-[8px] mr-1"></i>Kilimani, Nairobi</p>
                        <div class="flex items-center justify-between mt-2.5 pt-2.5 border-t border-slate-100">
                            <span class="text-sm font-bold text-navy">KSh 150,000<span class="text-[9px] text-slate-400 font-normal">/mo</span></span>
                            <span class="text-[9px] text-slate-400"><i class="fas fa-eye mr-0.5"></i>245</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden hover:shadow-lg transition-all group">
                    <div class="relative h-40 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=600&q=80" alt="Property" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <span class="absolute top-2 left-2 bg-emerald-600 text-white text-[9px] font-bold px-2 py-0.5 rounded-full">Active</span>
                        <button class="absolute top-2 right-2 w-7 h-7 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-slate-600 hover:text-navy">
                            <i class="fas fa-ellipsis-h text-[10px]"></i>
                        </button>
                    </div>
                    <div class="p-3">
                        <h3 class="font-serif text-sm text-navy font-semibold truncate">Spacious Family Home</h3>
                        <p class="text-[10px] text-slate-400 mt-0.5"><i class="fas fa-map-marker-alt text-[8px] mr-1"></i>Karen, Nairobi</p>
                        <div class="flex items-center justify-between mt-2.5 pt-2.5 border-t border-slate-100">
                            <span class="text-sm font-bold text-navy">KSh 200,000<span class="text-[9px] text-slate-400 font-normal">/mo</span></span>
                            <span class="text-[9px] text-slate-400"><i class="fas fa-eye mr-0.5"></i>189</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden hover:shadow-lg transition-all group">
                    <div class="relative h-40 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=600&q=80" alt="Property" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <span class="absolute top-2 left-2 bg-amber-600 text-white text-[9px] font-bold px-2 py-0.5 rounded-full">Pending</span>
                        <button class="absolute top-2 right-2 w-7 h-7 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-slate-600 hover:text-navy">
                            <i class="fas fa-ellipsis-h text-[10px]"></i>
                        </button>
                    </div>
                    <div class="p-3">
                        <h3 class="font-serif text-sm text-navy font-semibold truncate">Contemporary Townhouse</h3>
                        <p class="text-[10px] text-slate-400 mt-0.5"><i class="fas fa-map-marker-alt text-[8px] mr-1"></i>Kileleshwa, Nairobi</p>
                        <div class="flex items-center justify-between mt-2.5 pt-2.5 border-t border-slate-100">
                            <span class="text-sm font-bold text-navy">KSh 25M</span>
                            <span class="text-[9px] text-slate-400"><i class="fas fa-eye mr-0.5"></i>112</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden hover:shadow-lg transition-all group">
                    <div class="relative h-40 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=600&q=80" alt="Property" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <span class="absolute top-2 left-2 bg-emerald-600 text-white text-[9px] font-bold px-2 py-0.5 rounded-full">Active</span>
                        <button class="absolute top-2 right-2 w-7 h-7 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-slate-600 hover:text-navy">
                            <i class="fas fa-ellipsis-h text-[10px]"></i>
                        </button>
                    </div>
                    <div class="p-3">
                        <h3 class="font-serif text-sm text-navy font-semibold truncate">Cozy 2 Bed Apartment</h3>
                        <p class="text-[10px] text-slate-400 mt-0.5"><i class="fas fa-map-marker-alt text-[8px] mr-1"></i>Westlands, Nairobi</p>
                        <div class="flex items-center justify-between mt-2.5 pt-2.5 border-t border-slate-100">
                            <span class="text-sm font-bold text-navy">KSh 65,000<span class="text-[9px] text-slate-400 font-normal">/mo</span></span>
                            <span class="text-[9px] text-slate-400"><i class="fas fa-eye mr-0.5"></i>156</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden hover:shadow-lg transition-all group">
                    <div class="relative h-40 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1499793983690-e29da59ef1c2?auto=format&fit=crop&w=600&q=80" alt="Property" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <span class="absolute top-2 left-2 bg-emerald-600 text-white text-[9px] font-bold px-2 py-0.5 rounded-full">Active</span>
                        <button class="absolute top-2 right-2 w-7 h-7 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-slate-600 hover:text-navy">
                            <i class="fas fa-ellipsis-h text-[10px]"></i>
                        </button>
                    </div>
                    <div class="p-3">
                        <h3 class="font-serif text-sm text-navy font-semibold truncate">Beachfront Villa</h3>
                        <p class="text-[10px] text-slate-400 mt-0.5"><i class="fas fa-map-marker-alt text-[8px] mr-1"></i>Diani, Mombasa</p>
                        <div class="flex items-center justify-between mt-2.5 pt-2.5 border-t border-slate-100">
                            <span class="text-sm font-bold text-navy">KSh 25,000<span class="text-[9px] text-slate-400 font-normal">/night</span></span>
                            <span class="text-[9px] text-slate-400"><i class="fas fa-eye mr-0.5"></i>312</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden hover:shadow-lg transition-all group border-dashed">
                    <div class="h-40 flex flex-col items-center justify-center bg-slate-50 cursor-pointer hover:bg-slate-100 transition-colors">
                        <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center shadow-sm mb-2">
                            <i class="fas fa-plus text-slate-400"></i>
                        </div>
                        <p class="text-xs font-semibold text-slate-500">Add New Listing</p>
                        <p class="text-[10px] text-slate-400">List a property for rent or sale</p>
                    </div>
                </div>
            </div>
        </section>


        <!-- ================= BOOKINGS ================= -->
        <section id="section-bookings" class="dash-section">
            <div class="mb-5">
                <h2 class="font-serif text-2xl text-navy font-semibold">Bookings</h2>
                <p class="text-xs text-slate-400 mt-0.5">Manage your reservations and viewing requests</p>
            </div>

            <div class="flex gap-2 mb-4 overflow-x-auto no-scrollbar pb-1">
                <button class="dash-tab active">All (12)</button>
                <button class="dash-tab">Upcoming (3)</button>
                <button class="dash-tab">Active (2)</button>
                <button class="dash-tab">Completed (7)</button>
                <button class="dash-tab">Cancelled (0)</button>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 p-4 md:p-5">
                <div class="space-y-3">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 p-3 rounded-xl border border-slate-100 hover:border-slate-200 transition-colors">
                        <img src="https://images.unsplash.com/photo-1499793983690-e29da59ef1c2?auto=format&fit=crop&w=200&q=80" alt="Property" class="w-full sm:w-16 h-32 sm:h-16 rounded-lg object-cover flex-shrink-0">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-navy">Beachfront Villa</p>
                            <p class="text-[10px] text-slate-400 mt-0.5"><i class="fas fa-map-marker-alt text-[8px] mr-1"></i>Diani, Mombasa</p>
                            <div class="flex flex-wrap items-center gap-2 mt-1.5 text-[10px] text-slate-500">
                                <span><i class="fas fa-calendar text-[9px] mr-1 text-accentBlue"></i>Dec 15 – Dec 18, 2024</span>
                                <span>·</span>
                                <span><i class="fas fa-user-friends text-[9px] mr-1 text-accentBlue"></i>2 guests</span>
                            </div>
                        </div>
                        <div class="flex sm:flex-col items-center sm:items-end gap-2 sm:gap-1">
                            <span class="bg-emerald-50 text-emerald-700 text-[9px] font-bold px-2 py-0.5 rounded-full">Confirmed</span>
                            <span class="text-sm font-bold text-navy">KSh 55,500</span>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 p-3 rounded-xl border border-slate-100 hover:border-slate-200 transition-colors">
                        <img src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=200&q=80" alt="Property" class="w-full sm:w-16 h-32 sm:h-16 rounded-lg object-cover flex-shrink-0">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-navy">Modern 3 Bed Villa</p>
                            <p class="text-[10px] text-slate-400 mt-0.5"><i class="fas fa-map-marker-alt text-[8px] mr-1"></i>Kilimani, Nairobi</p>
                            <div class="flex flex-wrap items-center gap-2 mt-1.5 text-[10px] text-slate-500">
                                <span><i class="fas fa-calendar text-[9px] mr-1 text-accentBlue"></i>Jan 5, 2025</span>
                                <span>·</span>
                                <span><i class="fas fa-clock text-[9px] mr-1 text-accentBlue"></i>10:00 AM</span>
                            </div>
                        </div>
                        <div class="flex sm:flex-col items-center sm:items-end gap-2 sm:gap-1">
                            <span class="bg-accentBlue/10 text-accentBlue text-[9px] font-bold px-2 py-0.5 rounded-full">Viewing</span>
                            <span class="text-sm font-bold text-navy">Free</span>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 p-3 rounded-xl border border-slate-100 hover:border-slate-200 transition-colors">
                        <img src="https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?auto=format&fit=crop&w=200&q=80" alt="Property" class="w-full sm:w-16 h-32 sm:h-16 rounded-lg object-cover flex-shrink-0">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-navy">Luxury Safari Lodge</p>
                            <p class="text-[10px] text-slate-400 mt-0.5"><i class="fas fa-map-marker-alt text-[8px] mr-1"></i>Maasai Mara</p>
                            <div class="flex flex-wrap items-center gap-2 mt-1.5 text-[10px] text-slate-500">
                                <span><i class="fas fa-calendar text-[9px] mr-1 text-accentBlue"></i>Feb 10 – Feb 12, 2025</span>
                                <span>·</span>
                                <span><i class="fas fa-user-friends text-[9px] mr-1 text-accentBlue"></i>2 guests</span>
                            </div>
                        </div>
                        <div class="flex sm:flex-col items-center sm:items-end gap-2 sm:gap-1">
                            <span class="bg-amber-50 text-amber-700 text-[9px] font-bold px-2 py-0.5 rounded-full">Pending</span>
                            <span class="text-sm font-bold text-navy">KSh 90,000</span>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 p-3 rounded-xl border border-slate-100 hover:border-slate-200 transition-colors opacity-75">
                        <img src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=200&q=80" alt="Property" class="w-full sm:w-16 h-32 sm:h-16 rounded-lg object-cover flex-shrink-0">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-navy">Executive City Suite</p>
                            <p class="text-[10px] text-slate-400 mt-0.5"><i class="fas fa-map-marker-alt text-[8px] mr-1"></i>Westlands, Nairobi</p>
                            <div class="flex flex-wrap items-center gap-2 mt-1.5 text-[10px] text-slate-500">
                                <span><i class="fas fa-calendar text-[9px] mr-1 text-slate-400"></i>Nov 1 – Nov 3, 2024</span>
                                <span>·</span>
                                <span><i class="fas fa-user-friends text-[9px] mr-1 text-slate-400"></i>2 guests</span>
                            </div>
                        </div>
                        <div class="flex sm:flex-col items-center sm:items-end gap-2 sm:gap-1">
                            <span class="bg-slate-100 text-slate-600 text-[9px] font-bold px-2 py-0.5 rounded-full">Completed</span>
                            <span class="text-sm font-bold text-slate-500">KSh 30,000</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <!-- ================= MESSAGES ================= -->
        <section id="section-messages" class="dash-section">
            <div class="mb-5">
                <h2 class="font-serif text-2xl text-navy font-semibold">Messages</h2>
                <p class="text-xs text-slate-400 mt-0.5">5 unread conversations</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <!-- Conversation list -->
                <div class="bg-white rounded-2xl border border-slate-100 p-3">
                    <div class="flex items-center gap-2 mb-3 px-1">
                        <div class="flex items-center gap-2 bg-slate-100 rounded-lg px-3 py-2 flex-1">
                            <i class="fas fa-search text-slate-400 text-[10px]"></i>
                            <input type="text" placeholder="Search messages…" class="bg-transparent text-[11px] outline-none w-full text-slate-600 placeholder-slate-400">
                        </div>
                    </div>
                    <div class="space-y-1">
                        <div class="message-row unread p-2.5 flex items-start gap-2.5 cursor-pointer">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-accentOrange to-orange-600 flex items-center justify-center text-white font-bold text-[10px] flex-shrink-0">DK</div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-1">
                                    <p class="text-[11px] font-bold text-navy truncate">David K.</p>
                                    <span class="text-[9px] text-slate-400 whitespace-nowrap">2h</span>
                                </div>
                                <p class="text-[10px] text-slate-500 truncate mt-0.5">Is the property still available for viewing?</p>
                            </div>
                        </div>
                        <div class="message-row unread p-2.5 flex items-start gap-2.5 cursor-pointer">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-accentBlue to-blue-700 flex items-center justify-center text-white font-bold text-[10px] flex-shrink-0">SW</div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-1">
                                    <p class="text-[11px] font-bold text-navy truncate">Sarah W.</p>
                                    <span class="text-[9px] text-slate-400 whitespace-nowrap">5h</span>
                                </div>
                                <p class="text-[10px] text-slate-500 truncate mt-0.5">Thank you for the quick response! I'll book…</p>
                            </div>
                        </div>
                        <div class="message-row unread p-2.5 flex items-start gap-2.5 cursor-pointer">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-dashTeal to-emerald-700 flex items-center justify-center text-white font-bold text-[10px] flex-shrink-0">PM</div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-1">
                                    <p class="text-[11px] font-bold text-navy truncate">Prestige Realty</p>
                                    <span class="text-[9px] text-slate-400 whitespace-nowrap">1d</span>
                                </div>
                                <p class="text-[10px] text-slate-500 truncate mt-0.5">We have a new listing you might be interested in…</p>
                            </div>
                        </div>
                        <div class="message-row p-2.5 flex items-start gap-2.5 cursor-pointer">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-dashPurple to-violet-700 flex items-center justify-center text-white font-bold text-[10px] flex-shrink-0">JK</div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-1">
                                    <p class="text-[11px] font-bold text-navy truncate">James K.</p>
                                    <span class="text-[9px] text-slate-400 whitespace-nowrap">2d</span>
                                </div>
                                <p class="text-[10px] text-slate-500 truncate mt-0.5">Could you send me more photos of the villa?</p>
                            </div>
                        </div>
                        <div class="message-row p-2.5 flex items-start gap-2.5 cursor-pointer">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-slate-500 to-slate-700 flex items-center justify-center text-white font-bold text-[10px] flex-shrink-0">AM</div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-1">
                                    <p class="text-[11px] font-bold text-navy truncate">Alice M.</p>
                                    <span class="text-[9px] text-slate-400 whitespace-nowrap">3d</span>
                                </div>
                                <p class="text-[10px] text-slate-500 truncate mt-0.5">The booking was perfect, thank you!</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chat view -->
                <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 flex flex-col h-[500px]">
                    <!-- Chat header -->
                    <div class="p-3 border-b border-slate-100 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-accentOrange to-orange-600 flex items-center justify-center text-white font-bold text-[10px]">DK</div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-navy">David K.</p>
                            <p class="text-[9px] text-emerald-600 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Online
                            </p>
                        </div>
                        <button class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 transition-colors">
                            <i class="fas fa-ellipsis-v text-xs"></i>
                        </button>
                    </div>

                    <!-- Messages -->
                    <div class="flex-1 overflow-y-auto thin-scroll p-4 space-y-3 bg-slate-50/50">
                        <div class="flex justify-start">
                            <div class="max-w-[75%] bg-white rounded-2xl rounded-bl-sm px-3.5 py-2.5 shadow-sm border border-slate-100">
                                <p class="text-[11px] text-slate-700">Hi Jane! I'm interested in the Modern 3 Bed Villa in Kilimani. Is it still available?</p>
                                <span class="text-[8px] text-slate-400 mt-1 block">10:30 AM</span>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <div class="max-w-[75%] bg-gradient-to-br from-navy to-accentBlue text-white rounded-2xl rounded-br-sm px-3.5 py-2.5 shadow-sm">
                                <p class="text-[11px]">Hi David! Yes, it's still available. Would you like to schedule a viewing?</p>
                                <span class="text-[8px] text-white/60 mt-1 block text-right">10:35 AM</span>
                            </div>
                        </div>
                        <div class="flex justify-start">
                            <div class="max-w-[75%] bg-white rounded-2xl rounded-bl-sm px-3.5 py-2.5 shadow-sm border border-slate-100">
                                <p class="text-[11px] text-slate-700">That would be great! Is this Saturday at 10 AM okay?</p>
                                <span class="text-[8px] text-slate-400 mt-1 block">10:38 AM</span>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <div class="max-w-[75%] bg-gradient-to-br from-navy to-accentBlue text-white rounded-2xl rounded-br-sm px-3.5 py-2.5 shadow-sm">
                                <p class="text-[11px]">Saturday at 10 AM works perfectly. I'll send you the location pin. See you then! 😊</p>
                                <span class="text-[8px] text-white/60 mt-1 block text-right">10:40 AM</span>
                            </div>
                        </div>
                    </div>

                    <!-- Input -->
                    <div class="p-3 border-t border-slate-100">
                        <div class="flex items-center gap-2">
                            <button class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 transition-colors flex-shrink-0">
                                <i class="fas fa-paperclip text-xs"></i>
                            </button>
                            <input type="text" placeholder="Type a message…" class="flex-1 bg-slate-100 rounded-lg px-3 py-2 text-[11px] outline-none text-slate-600 placeholder-slate-400">
                            <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-navy text-white hover:bg-accentBlue transition-colors flex-shrink-0">
                                <i class="fas fa-paper-plane text-[10px]"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <!-- ================= FAVORITES ================= -->
        <section id="section-favorites" class="dash-section">
            <div class="mb-5">
                <h2 class="font-serif text-2xl text-navy font-semibold">Saved Favorites</h2>
                <p class="text-xs text-slate-400 mt-0.5">Properties you've saved for later</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden hover:shadow-lg transition-all group">
                    <div class="relative h-40 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=600&q=80" alt="Property" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <button class="absolute top-2 right-2 w-7 h-7 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-red-500">
                            <i class="fas fa-heart text-[10px]"></i>
                        </button>
                    </div>
                    <div class="p-3">
                        <h3 class="font-serif text-sm text-navy font-semibold truncate">Luxury Garden Villa</h3>
                        <p class="text-[10px] text-slate-400 mt-0.5"><i class="fas fa-map-marker-alt text-[8px] mr-1"></i>Runda, Nairobi</p>
                        <div class="flex items-center justify-between mt-2.5 pt-2.5 border-t border-slate-100">
                            <span class="text-sm font-bold text-navy">KSh 85M</span>
                            <span class="text-[9px] font-semibold text-accentOrange"><i class="fas fa-star mr-0.5"></i>4.9</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden hover:shadow-lg transition-all group">
                    <div class="relative h-40 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=600&q=80" alt="Property" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <button class="absolute top-2 right-2 w-7 h-7 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-red-500">
                            <i class="fas fa-heart text-[10px]"></i>
                        </button>
                    </div>
                    <div class="p-3">
                        <h3 class="font-serif text-sm text-navy font-semibold truncate">Executive City Suite</h3>
                        <p class="text-[10px] text-slate-400 mt-0.5"><i class="fas fa-map-marker-alt text-[8px] mr-1"></i>Westlands, Nairobi</p>
                        <div class="flex items-center justify-between mt-2.5 pt-2.5 border-t border-slate-100">
                            <span class="text-sm font-bold text-navy">KSh 15,000<span class="text-[9px] text-slate-400 font-normal">/night</span></span>
                            <span class="text-[9px] font-semibold text-accentOrange"><i class="fas fa-star mr-0.5"></i>4.8</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden hover:shadow-lg transition-all group">
                    <div class="relative h-40 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?auto=format&fit=crop&w=600&q=80" alt="Property" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <button class="absolute top-2 right-2 w-7 h-7 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-red-500">
                            <i class="fas fa-heart text-[10px]"></i>
                        </button>
                    </div>
                    <div class="p-3">
                        <h3 class="font-serif text-sm text-navy font-semibold truncate">Luxury Safari Lodge</h3>
                        <p class="text-[10px] text-slate-400 mt-0.5"><i class="fas fa-map-marker-alt text-[8px] mr-1"></i>Maasai Mara</p>
                        <div class="flex items-center justify-between mt-2.5 pt-2.5 border-t border-slate-100">
                            <span class="text-sm font-bold text-navy">KSh 45,000<span class="text-[9px] text-slate-400 font-normal">/night</span></span>
                            <span class="text-[9px] font-semibold text-accentOrange"><i class="fas fa-star mr-0.5"></i>5.0</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <!-- ================= PAYMENTS ================= -->
        <section id="section-payments" class="dash-section">
            <div class="mb-5">
                <h2 class="font-serif text-2xl text-navy font-semibold">Payments</h2>
                <p class="text-xs text-slate-400 mt-0.5">Track your earnings and transactions</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5">
                <div class="bg-white rounded-2xl border border-slate-100 p-4">
                    <div class="w-9 h-9 rounded-lg bg-emerald-50 flex items-center justify-center mb-2">
                        <i class="fas fa-arrow-down text-emerald-600 text-sm"></i>
                    </div>
                    <p class="text-[10px] text-slate-400 font-medium">Total Earnings</p>
                    <p class="text-2xl font-bold text-navy mt-0.5">KSh 245,000</p>
                    <p class="text-[9px] text-emerald-600 font-semibold mt-1"><i class="fas fa-arrow-up mr-0.5"></i>12% vs last month</p>
                </div>
                <div class="bg-white rounded-2xl border border-slate-100 p-4">
                    <div class="w-9 h-9 rounded-lg bg-amber-50 flex items-center justify-center mb-2">
                        <i class="fas fa-clock text-amber-600 text-sm"></i>
                    </div>
                    <p class="text-[10px] text-slate-400 font-medium">Pending</p>
                    <p class="text-2xl font-bold text-navy mt-0.5">KSh 45,000</p>
                    <p class="text-[9px] text-slate-400 mt-1">2 payments processing</p>
                </div>
                <div class="bg-white rounded-2xl border border-slate-100 p-4">
                    <div class="w-9 h-9 rounded-lg bg-accentBlue/10 flex items-center justify-center mb-2">
                        <i class="fas fa-receipt text-accentBlue text-sm"></i>
                    </div>
                    <p class="text-[10px] text-slate-400 font-medium">Transactions</p>
                    <p class="text-2xl font-bold text-navy mt-0.5">34</p>
                    <p class="text-[9px] text-slate-400 mt-1">All time</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 p-4 md:p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-serif text-lg text-navy font-semibold">Recent Transactions</h3>
                    <button class="text-[10px] text-accentBlue font-semibold hover:underline">Export CSV</button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-slate-100">
                                <th class="py-2 text-[9px] uppercase tracking-wider text-slate-400 font-bold">Transaction</th>
                                <th class="py-2 text-[9px] uppercase tracking-wider text-slate-400 font-bold">Date</th>
                                <th class="py-2 text-[9px] uppercase tracking-wider text-slate-400 font-bold">Status</th>
                                <th class="py-2 text-[9px] uppercase tracking-wider text-slate-400 font-bold text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-slate-50 hover:bg-slate-50 transition-colors">
                                <td class="py-2.5 text-xs font-medium text-navy">Booking #HV-2451</td>
                                <td class="py-2.5 text-[10px] text-slate-500">Dec 10, 2024</td>
                                <td class="py-2.5"><span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded-full">Completed</span></td>
                                <td class="py-2.5 text-xs font-bold text-navy text-right">+ KSh 45,000</td>
                            </tr>
                            <tr class="border-b border-slate-50 hover:bg-slate-50 transition-colors">
                                <td class="py-2.5 text-xs font-medium text-navy">Booking #HV-2438</td>
                                <td class="py-2.5 text-[10px] text-slate-500">Dec 5, 2024</td>
                                <td class="py-2.5"><span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded-full">Completed</span></td>
                                <td class="py-2.5 text-xs font-bold text-navy text-right">+ KSh 30,000</td>
                            </tr>
                            <tr class="border-b border-slate-50 hover:bg-slate-50 transition-colors">
                                <td class="py-2.5 text-xs font-medium text-navy">Booking #HV-2425</td>
                                <td class="py-2.5 text-[10px] text-slate-500">Nov 28, 2024</td>
                                <td class="py-2.5"><span class="text-[9px] font-bold text-amber-600 bg-amber-50 px-1.5 py-0.5 rounded-full">Pending</span></td>
                                <td class="py-2.5 text-xs font-bold text-navy text-right">+ KSh 90,000</td>
                            </tr>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-2.5 text-xs font-medium text-navy">Withdrawal to Bank</td>
                                <td class="py-2.5 text-[10px] text-slate-500">Nov 20, 2024</td>
                                <td class="py-2.5"><span class="text-[9px] font-bold text-slate-600 bg-slate-100 px-1.5 py-0.5 rounded-full">Processed</span></td>
                                <td class="py-2.5 text-xs font-bold text-red-500 text-right">− KSh 100,000</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>


        <!-- ================= ANALYTICS ================= -->
        <section id="section-analytics" class="dash-section">
            <div class="mb-5">
                <h2 class="font-serif text-2xl text-navy font-semibold">Analytics</h2>
                <p class="text-xs text-slate-400 mt-0.5">Track your performance</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-5">
                <!-- Views chart -->
                <div class="bg-white rounded-2xl border border-slate-100 p-4 md:p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-serif text-lg text-navy font-semibold">Profile Views</h3>
                        <span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">+18%</span>
                    </div>
                    <div class="flex items-end gap-1.5 h-32">
                        <div class="flex-1 bg-accentBlue/20 rounded-t hover:bg-accentBlue/40 transition-colors" style="height: 40%"></div>
                        <div class="flex-1 bg-accentBlue/20 rounded-t hover:bg-accentBlue/40 transition-colors" style="height: 55%"></div>
                        <div class="flex-1 bg-accentBlue/20 rounded-t hover:bg-accentBlue/40 transition-colors" style="height: 35%"></div>
                        <div class="flex-1 bg-accentBlue/20 rounded-t hover:bg-accentBlue/40 transition-colors" style="height: 70%"></div>
                        <div class="flex-1 bg-accentBlue/20 rounded-t hover:bg-accentBlue/40 transition-colors" style="height: 60%"></div>
                        <div class="flex-1 bg-accentBlue/20 rounded-t hover:bg-accentBlue/40 transition-colors" style="height: 85%"></div>
                        <div class="flex-1 bg-accentBlue rounded-t" style="height: 100%"></div>
                        <div class="flex-1 bg-accentBlue/20 rounded-t hover:bg-accentBlue/40 transition-colors" style="height: 75%"></div>
                        <div class="flex-1 bg-accentBlue/20 rounded-t hover:bg-accentBlue/40 transition-colors" style="height: 65%"></div>
                        <div class="flex-1 bg-accentBlue/20 rounded-t hover:bg-accentBlue/40 transition-colors" style="height: 50%"></div>
                        <div class="flex-1 bg-accentBlue/20 rounded-t hover:bg-accentBlue/40 transition-colors" style="height: 80%"></div>
                        <div class="flex-1 bg-accentBlue/20 rounded-t hover:bg-accentBlue/40 transition-colors" style="height: 90%"></div>
                    </div>
                    <div class="flex justify-between mt-2 text-[8px] text-slate-400">
                        <span>Jan</span><span>Mar</span><span>May</span><span>Jul</span><span>Sep</span><span>Nov</span><span>Dec</span>
                    </div>
                </div>

                <!-- Top properties -->
                <div class="bg-white rounded-2xl border border-slate-100 p-4 md:p-5">
                    <h3 class="font-serif text-lg text-navy font-semibold mb-4">Top Performing Properties</h3>
                    <div class="space-y-3">
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[11px] font-semibold text-navy truncate">Beachfront Villa</span>
                                <span class="text-[10px] font-bold text-navy">312 views</span>
                            </div>
                            <div class="progress-bar"><div class="progress-fill bg-gradient-to-r from-accentBlue to-dashTeal" style="width: 100%"></div></div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[11px] font-semibold text-navy truncate">Modern 3 Bed Villa</span>
                                <span class="text-[10px] font-bold text-navy">245 views</span>
                            </div>
                            <div class="progress-bar"><div class="progress-fill bg-gradient-to-r from-accentBlue to-dashTeal" style="width: 78%"></div></div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[11px] font-semibold text-navy truncate">Spacious Family Home</span>
                                <span class="text-[10px] font-bold text-navy">189 views</span>
                            </div>
                            <div class="progress-bar"><div class="progress-fill bg-gradient-to-r from-accentBlue to-dashTeal" style="width: 60%"></div></div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[11px] font-semibold text-navy truncate">Cozy 2 Bed Apartment</span>
                                <span class="text-[10px] font-bold text-navy">156 views</span>
                            </div>
                            <div class="progress-bar"><div class="progress-fill bg-gradient-to-r from-accentBlue to-dashTeal" style="width: 50%"></div></div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[11px] font-semibold text-navy truncate">Contemporary Townhouse</span>
                                <span class="text-[10px] font-bold text-navy">112 views</span>
                            </div>
                            <div class="progress-bar"><div class="progress-fill bg-gradient-to-r from-accentBlue to-dashTeal" style="width: 36%"></div></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Conversion stats -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
                <div class="bg-white rounded-2xl border border-slate-100 p-4 text-center">
                    <p class="text-[10px] text-slate-400 font-medium mb-1">View-to-Booking</p>
                    <p class="text-xl font-bold text-navy">4.8%</p>
                </div>
                <div class="bg-white rounded-2xl border border-slate-100 p-4 text-center">
                    <p class="text-[10px] text-slate-400 font-medium mb-1">Avg. Response Time</p>
                    <p class="text-xl font-bold text-navy">1.2h</p>
                </div>
                <div class="bg-white rounded-2xl border border-slate-100 p-4 text-center">
                    <p class="text-[10px] text-slate-400 font-medium mb-1">Repeat Clients</p>
                    <p class="text-xl font-bold text-navy">38%</p>
                </div>
                <div class="bg-white rounded-2xl border border-slate-100 p-4 text-center">
                    <p class="text-[10px] text-slate-400 font-medium mb-1">Avg. Rating</p>
                    <p class="text-xl font-bold text-navy">4.8★</p>
                </div>
            </div>
        </section>


        <!-- ================= PROFILE ================= -->
        <section id="section-profile" class="dash-section">
            <div class="mb-5">
                <h2 class="font-serif text-2xl text-navy font-semibold">My Profile</h2>
                <p class="text-xs text-slate-400 mt-0.5">Manage your personal information</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-5">
                <!-- Profile card -->
                <div class="bg-white rounded-2xl border border-slate-100 p-5 text-center">
                    <div class="relative inline-block mb-3">
                        <div class="w-24 h-24 rounded-full bg-gradient-to-br from-accentOrange to-orange-600 flex items-center justify-center text-white font-bold text-2xl mx-auto">
                            JM
                        </div>
                        <button class="absolute bottom-0 right-0 w-8 h-8 bg-white rounded-full shadow-md flex items-center justify-center text-slate-600 hover:text-accentBlue transition-colors border border-slate-100">
                            <i class="fas fa-camera text-[10px]"></i>
                        </button>
                    </div>
                    <h3 class="font-serif text-xl text-navy font-semibold">Jane Mwangi</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">jane.mwangi@email.com</p>
                    <div class="flex items-center justify-center gap-2 mt-2">
                        <span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full"><i class="fas fa-check-circle mr-0.5"></i>Verified</span>
                        <span class="text-[9px] font-bold text-accentOrange bg-accentOrange/10 px-2 py-0.5 rounded-full"><i class="fas fa-crown mr-0.5"></i>Pro</span>
                    </div>
                    <div class="grid grid-cols-3 gap-2 mt-5 pt-4 border-t border-slate-100">
                        <div>
                            <p class="text-lg font-bold text-navy">8</p>
                            <p class="text-[9px] text-slate-400">Listings</p>
                        </div>
                        <div>
                            <p class="text-lg font-bold text-navy">12</p>
                            <p class="text-[9px] text-slate-400">Bookings</p>
                        </div>
                        <div>
                            <p class="text-lg font-bold text-navy">4.9</p>
                            <p class="text-[9px] text-slate-400">Rating</p>
                        </div>
                    </div>
                </div>

                <!-- Edit form -->
                <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 p-5">
                    <h3 class="font-serif text-lg text-navy font-semibold mb-4">Edit Information</h3>
                    <form class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Full Name</label>
                                <input type="text" value="Jane Mwangi" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-accentBlue/30 focus:border-accentBlue">
                            </div>
                            <div>
                                <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Email</label>
                                <input type="email" value="jane.mwangi@email.com" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-accentBlue/30 focus:border-accentBlue">
                            </div>
                            <div>
                                <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Phone</label>
                                <input type="tel" value="+254 700 123 456" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-accentBlue/30 focus:border-accentBlue">
                            </div>
                            <div>
                                <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Location</label>
                                <input type="text" value="Nairobi, Kenya" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-accentBlue/30 focus:border-accentBlue">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Bio</label>
                            <textarea rows="3" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-accentBlue/30 focus:border-accentBlue resize-none">Property owner and short-stay host based in Nairobi. Passionate about providing exceptional hospitality experiences.</textarea>
                        </div>
                        <div class="flex gap-2 pt-2">
                            <button type="button" class="px-5 py-2.5 bg-navy text-white text-xs font-bold rounded-lg hover:bg-accentBlue transition-colors">Save Changes</button>
                            <button type="button" class="px-5 py-2.5 border border-slate-200 text-slate-600 text-xs font-semibold rounded-lg hover:bg-slate-50 transition-colors">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>


        <!-- ================= SETTINGS ================= -->
        <section id="section-settings" class="dash-section">
            <div class="mb-5">
                <h2 class="font-serif text-2xl text-navy font-semibold">Settings</h2>
                <p class="text-xs text-slate-400 mt-0.5">Manage your preferences</p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 p-5">
                <div class="space-y-1">
                    <div class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-accentBlue/10 flex items-center justify-center">
                                <i class="fas fa-bell text-accentBlue text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-navy">Email Notifications</p>
                                <p class="text-[10px] text-slate-400">Receive updates about bookings and messages</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" checked class="sr-only peer">
                            <div class="w-10 h-5 bg-slate-200 rounded-full peer-checked:bg-accentBlue transition-colors"></div>
                            <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow peer-checked:translate-x-5 transition-transform"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-emerald-50 flex items-center justify-center">
                                <i class="fab fa-whatsapp text-emerald-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-navy">WhatsApp Notifications</p>
                                <p class="text-[10px] text-slate-400">Get instant alerts on WhatsApp</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer">
                            <div class="w-10 h-5 bg-slate-200 rounded-full peer-checked:bg-accentBlue transition-colors"></div>
                            <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow peer-checked:translate-x-5 transition-transform"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-amber-50 flex items-center justify-center">
                                <i class="fas fa-eye text-amber-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-navy">Public Profile</p>
                                <p class="text-[10px] text-slate-400">Make your profile visible to other users</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" checked class="sr-only peer">
                            <div class="w-10 h-5 bg-slate-200 rounded-full peer-checked:bg-accentBlue transition-colors"></div>
                            <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow peer-checked:translate-x-5 transition-transform"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-dashPurple/10 flex items-center justify-center">
                                <i class="fas fa-moon text-dashPurple text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-navy">Dark Mode</p>
                                <p class="text-[10px] text-slate-400">Switch to a darker theme</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer">
                            <div class="w-10 h-5 bg-slate-200 rounded-full peer-checked:bg-accentBlue transition-colors"></div>
                            <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow peer-checked:translate-x-5 transition-transform"></div>
                        </label>
                    </div>
                </div>

                <div class="mt-5 pt-5 border-t border-slate-100">
                    <h3 class="text-xs font-bold text-red-500 uppercase tracking-wide mb-3">Danger Zone</h3>
                    <div class="flex flex-col sm:flex-row gap-2">
                        <button class="px-4 py-2.5 border border-slate-200 text-slate-600 text-xs font-semibold rounded-lg hover:bg-slate-50 transition-colors">
                            <i class="fas fa-download mr-1.5"></i> Download My Data
                        </button>
                        <button class="px-4 py-2.5 border border-red-200 text-red-500 text-xs font-semibold rounded-lg hover:bg-red-50 transition-colors">
                            <i class="fas fa-trash mr-1.5"></i> Delete Account
                        </button>
                    </div>
                </div>
            </div>
        </section>


        <!-- ================= HELP ================= -->
        <section id="section-help" class="dash-section">
            <div class="mb-5">
                <h2 class="font-serif text-2xl text-navy font-semibold">Help & Support</h2>
                <p class="text-xs text-slate-400 mt-0.5">We're here to help you</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
                <div class="bg-gradient-to-br from-navy to-accentBlue rounded-2xl p-5 text-white">
                    <i class="fas fa-headset text-accentOrange text-2xl mb-3"></i>
                    <h3 class="font-serif text-lg font-semibold mb-1">Live Chat Support</h3>
                    <p class="text-white/70 text-xs leading-relaxed mb-3">Our team is available 24/7 to assist you with any questions.</p>
                    <button class="bg-accentOrange text-navy text-[10px] font-bold px-4 py-2 rounded-lg hover:bg-white transition-colors">
                        Start Chat
                    </button>
                </div>
                <div class="bg-white rounded-2xl border border-slate-100 p-5">
                    <i class="fas fa-envelope text-accentBlue text-2xl mb-3"></i>
                    <h3 class="font-serif text-lg text-navy font-semibold mb-1">Email Support</h3>
                    <p class="text-slate-400 text-xs leading-relaxed mb-3">Send us an email and we'll respond within 24 hours.</p>
                    <a href="mailto:support@haventra.com" class="text-[10px] font-bold text-accentBlue hover:underline">support@haventra.com →</a>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 p-5">
                <h3 class="font-serif text-lg text-navy font-semibold mb-3">Frequently Asked Questions</h3>
                <div class="space-y-1">
                    <details class="group p-3 rounded-xl hover:bg-slate-50 transition-colors">
                        <summary class="flex items-center justify-between cursor-pointer list-none">
                            <span class="text-xs font-semibold text-navy">How do I add a new property listing?</span>
                            <i class="fas fa-chevron-down text-slate-400 text-[10px] group-open:rotate-180 transition-transform"></i>
                        </summary>
                        <p class="text-[11px] text-slate-500 leading-relaxed mt-2 pl-0">Navigate to "My Properties" in the sidebar, then click the "Add New Property" button. Fill in the property details, upload photos, and publish your listing.</p>
                    </details>
                    <details class="group p-3 rounded-xl hover:bg-slate-50 transition-colors">
                        <summary class="flex items-center justify-between cursor-pointer list-none">
                            <span class="text-xs font-semibold text-navy">How do I get paid for bookings?</span>
                            <i class="fas fa-chevron-down text-slate-400 text-[10px] group-open:rotate-180 transition-transform"></i>
                        </summary>
                        <p class="text-[11px] text-slate-500 leading-relaxed mt-2 pl-0">Payments are processed automatically after each successful booking. You can withdraw your earnings to your bank account from the Payments section. Withdrawals take 1-3 business days.</p>
                    </details>
                    <details class="group p-3 rounded-xl hover:bg-slate-50 transition-colors">
                        <summary class="flex items-center justify-between cursor-pointer list-none">
                            <span class="text-xs font-semibold text-navy">How do I switch between Client, Owner, and Company roles?</span>
                            <i class="fas fa-chevron-down text-slate-400 text-[10px] group-open:rotate-180 transition-transform"></i>
                        </summary>
                        <p class="text-[11px] text-slate-500 leading-relaxed mt-2 pl-0">Use the role switcher in the sidebar under your profile card. Each role has its own dashboard, navigation menu, and features tailored to your needs.</p>
                    </details>
                    <details class="group p-3 rounded-xl hover:bg-slate-50 transition-colors">
                        <summary class="flex items-center justify-between cursor-pointer list-none">
                            <span class="text-xs font-semibold text-navy">How do I verify my account?</span>
                            <i class="fas fa-chevron-down text-slate-400 text-[10px] group-open:rotate-180 transition-transform"></i>
                        </summary>
                        <p class="text-[11px] text-slate-500 leading-relaxed mt-2 pl-0">Go to Profile → Edit Information and complete all required fields. Then upload a government-issued ID from the verification section. Verification usually takes 24-48 hours.</p>
                    </details>
                </div>
            </div>
        </section>

    </main>
</div>


<!-- ================= MOBILE BOTTOM NAV ================= -->
<div class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 z-30">
    <div class="grid grid-cols-5 py-1.5">
        <button onclick="showSection('overview', document.querySelectorAll('.nav-link')[0])" class="flex flex-col items-center gap-0.5 py-1.5 text-navy">
            <i class="fas fa-th-large text-sm"></i>
            <span class="text-[8px] font-semibold">Home</span>
        </button>
        <button onclick="showSection('properties', document.querySelectorAll('.nav-link')[1])" class="flex flex-col items-center gap-0.5 py-1.5 text-slate-400 hover:text-navy transition-colors">
            <i class="fas fa-building text-sm"></i>
            <span class="text-[8px] font-semibold">Listings</span>
        </button>
        <button onclick="showSection('bookings', document.querySelectorAll('.nav-link')[2])" class="flex flex-col items-center gap-0.5 py-1.5 text-slate-400 hover:text-navy transition-colors">
            <i class="fas fa-calendar-check text-sm"></i>
            <span class="text-[8px] font-semibold">Bookings</span>
        </button>
        <button onclick="showSection('messages', document.querySelectorAll('.nav-link')[3])" class="flex flex-col items-center gap-0.5 py-1.5 text-slate-400 hover:text-navy transition-colors relative">
            <i class="fas fa-comments text-sm"></i>
            <span class="text-[8px] font-semibold">Messages</span>
            <span class="absolute top-0.5 right-3 w-1.5 h-1.5 bg-red-500 rounded-full"></span>
        </button>
        <button onclick="showSection('profile', document.querySelectorAll('.nav-link')[6])" class="flex flex-col items-center gap-0.5 py-1.5 text-slate-400 hover:text-navy transition-colors">
            <i class="fas fa-user-circle text-sm"></i>
            <span class="text-[8px] font-semibold">Profile</span>
        </button>
    </div>
</div>


<script>
/* ============================================================
   UPGRADE CARD
   ============================================================ */
function closeUpgradeCard() {
    const card = document.getElementById('upgradeCard');
    if (card) {
        card.style.display = 'none';
        try {
            localStorage.setItem('haventra_upgrade_card_closed', 'true');
        } catch (e) {}
    }
}

function initUpgradeCard() {
    try {
        if (localStorage.getItem('haventra_upgrade_card_closed') === 'true') {
            const card = document.getElementById('upgradeCard');
            if (card) card.style.display = 'none';
        }
    } catch (e) {}
}

/* ============================================================
   SIDEBAR
   ============================================================ */
function openSidebar() {
    document.getElementById('sidebar').classList.add('open');
    document.getElementById('sidebarOverlay').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('sidebarOverlay').classList.remove('active');
    document.body.style.overflow = '';
}

/* ============================================================
   SECTION SWITCHING
   ============================================================ */
const sectionTitles = {
    overview: { title: 'Overview', subtitle: 'Welcome back, Jane!' },
    properties: { title: 'My Properties', subtitle: 'Manage your listings' },
    bookings: { title: 'Bookings', subtitle: 'Manage your reservations' },
    messages: { title: 'Messages', subtitle: 'Your conversations' },
    favorites: { title: 'Favorites', subtitle: 'Saved properties' },
    payments: { title: 'Payments', subtitle: 'Track your earnings' },
    analytics: { title: 'Analytics', subtitle: 'Your performance insights' },
    profile: { title: 'My Profile', subtitle: 'Manage your personal info' },
    settings: { title: 'Settings', subtitle: 'Manage your preferences' },
    help: { title: 'Help & Support', subtitle: 'We are here to help' },
};

function showSection(name, navEl) {
    // Hide all sections
    document.querySelectorAll('.dash-section').forEach(s => s.classList.remove('active'));
    
    // Show target section
    const target = document.getElementById('section-' + name);
    if (target) target.classList.add('active');

    // Update nav active states (desktop)
    document.querySelectorAll('.nav-link').forEach(n => n.classList.remove('active'));
    if (navEl) navEl.classList.add('active');

    // Update page title
    const info = sectionTitles[name] || { title: name, subtitle: '' };
    document.getElementById('pageTitle').textContent = info.title;
    document.getElementById('pageSubtitle').textContent = info.subtitle;

    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });

    // Close sidebar on mobile
    if (window.innerWidth < 1024) closeSidebar();
}

/* ============================================================
   ROLE SWITCHER
   ============================================================ */
function switchRole(role) {
    // Update active state
    document.querySelectorAll('.role-btn').forEach(b => b.classList.remove('active'));
    document.querySelector(`.role-btn[data-role="${role}"]`).classList.add('active');

    // In a real app, this would fetch role-specific data.
    // For demo, we just show a subtle notification.
    const roleNames = { client: 'Client', owner: 'Owner', company: 'Company' };
    
    // Update subtitle
    const subtitle = document.getElementById('pageSubtitle');
    if (subtitle) {
        const base = sectionTitles[document.querySelector('.dash-section.active')?.id?.replace('section-', '')] || {};
        subtitle.textContent = `Viewing as ${roleNames[role]} · ${base.subtitle || ''}`;
    }

    // Optional: show toast
    showToast(`Switched to ${roleNames[role]} view`);
}

/* ============================================================
   TOAST (lightweight notification)
   ============================================================ */
function showToast(message) {
    const existing = document.getElementById('dashToast');
    if (existing) existing.remove();

    const toast = document.createElement('div');
    toast.id = 'dashToast';
    toast.className = 'fixed bottom-20 lg:bottom-6 left-1/2 -translate-x-1/2 bg-navy text-white text-xs font-semibold px-5 py-3 rounded-xl shadow-xl z-[5000] flex items-center gap-2';
    toast.innerHTML = `<i class="fas fa-check-circle text-accentOrange"></i> ${message}`;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translate(-50%, 10px)';
        toast.style.transition = 'all 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, 2000);
}

/* ============================================================
   INIT — responsive fix
   ============================================================ */
document.addEventListener('DOMContentLoaded', initUpgradeCard);

window.addEventListener('resize', () => {
    if (window.innerWidth >= 1024) {
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('sidebarOverlay').classList.remove('active');
        document.body.style.overflow = '';
    }
});
</script>

</body>
</html>