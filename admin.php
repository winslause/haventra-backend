<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel — HAVENTRA</title>
    <meta name="description" content="HAVENTRA administration panel — manage users, properties, bookings, agencies, and platform settings.">
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
                        adminRed: '#DC2626',
                        adminGreen: '#059669',
                        adminPurple: '#6D28D9',
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

        /* ===== SIDEBAR ===== */
        #adminSidebar {
            transition: transform 0.3s ease;
        }
        @media (max-width: 1023px) {
            #adminSidebar { transform: translateX(-100%); }
            #adminSidebar.open { transform: translateX(0); }
        }
        #sidebarOverlay { display: none; }
        #sidebarOverlay.active { display: block; }

        .admin-nav {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 9px 14px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 500;
            color: #94A3B8;
            transition: all 0.25s ease;
            cursor: pointer;
        }
        .admin-nav:hover { background: rgba(255, 255, 255, 0.05); color: white; }
        .admin-nav.active {
            background: linear-gradient(135deg, #005BB5, #00215E);
            color: white;
            box-shadow: 0 6px 16px -4px rgba(0, 91, 181, 0.5);
        }
        .admin-nav.active .nav-icon { color: #F59E0B; }
        .nav-icon { font-size: 14px; width: 18px; text-align: center; }

        /* ===== CARDS ===== */
        .stat-card { transition: all 0.35s cubic-bezier(0.25, 0.8, 0.25, 1); }
        .stat-card:hover { transform: translateY(-4px); box-shadow: 0 20px 30px -10px rgba(0, 0, 0, 0.1); }

        /* ===== TAB PILLS ===== */
        .admin-tab {
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
        .admin-tab:hover { border-color: #005BB5; color: #005BB5; }
        .admin-tab.active {
            background: #00215E;
            color: white;
            border-color: #00215E;
            box-shadow: 0 4px 12px rgba(0, 33, 94, 0.25);
        }

        /* ===== TABLE ===== */
        .admin-table th {
            text-align: left;
            padding: 10px 14px;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 700;
            color: #64748B;
            border-bottom: 1px solid #E2E8F0;
            background: #F8FAFC;
            white-space: nowrap;
        }
        .admin-table td {
            padding: 12px 14px;
            font-size: 12px;
            color: #1E293B;
            border-bottom: 1px solid #F1F5F9;
            vertical-align: middle;
        }
        .admin-table tbody tr:hover { background: #F8FAFC; }

        /* ===== STATUS BADGES ===== */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 10px;
            border-radius: 9999px;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            white-space: nowrap;
        }
        .status-active { background: #DCFCE7; color: #166534; }
        .status-pending { background: #FEF3C7; color: #92400E; }
        .status-suspended { background: #FEE2E2; color: #991B1B; }
        .status-draft { background: #E2E8F0; color: #475569; }
        .status-confirmed { background: #DBEAFE; color: #1E40AF; }
        .status-completed { background: #D1FAE5; color: #065F46; }
        .status-cancelled { background: #FEE2E2; color: #991B1B; }

        /* ===== MINI CHART BARS ===== */
        .mini-chart { display: flex; align-items: flex-end; gap: 3px; height: 32px; }
        .mini-bar { width: 6px; border-radius: 3px 3px 0 0; background: #CBD5E1; transition: background 0.2s; }
        .mini-bar.blue { background: #005BB5; }
        .mini-bar.orange { background: #F59E0B; }
        .mini-bar.green { background: #059669; }
        .mini-bar.purple { background: #6D28D9; }

        /* ===== SCROLLBARS ===== */
        .thin-scroll::-webkit-scrollbar { width: 5px; height: 5px; }
        .thin-scroll::-webkit-scrollbar-track { background: transparent; }
        .thin-scroll::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 3px; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* ===== MODAL ===== */
        .admin-modal { opacity: 0; transition: opacity 0.25s ease; }
        .admin-modal.show { opacity: 1; }
        .admin-modal .modal-inner {
            transform: scale(0.95) translateY(10px);
            transition: transform 0.25s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        .admin-modal.show .modal-inner { transform: scale(1) translateY(0); }

        /* ===== FADE IN ===== */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .anim-fade-in { animation: fadeIn 0.35s ease both; }

        /* ===== PULSE ===== */
        @keyframes pulseDot {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }
        .pulse-dot { animation: pulseDot 2s infinite; }

        /* ===== SECTIONS ===== */
        .admin-section { display: none; }
        .admin-section.active { display: block; animation: fadeIn 0.35s ease both; }

        /* ===== CHECKBOX ===== */
        .admin-checkbox {
            width: 16px;
            height: 16px;
            border-radius: 4px;
            border: 1.5px solid #CBD5E1;
            cursor: pointer;
            transition: all 0.2s;
        }
        .admin-checkbox:checked {
            background: #005BB5;
            border-color: #005BB5;
        }

        /* ===== SEARCH INPUT ===== */
        .admin-search {
            background: #F1F5F9;
            border-radius: 10px;
            transition: all 0.2s;
        }
        .admin-search:focus-within {
            background: white;
            box-shadow: 0 0 0 2px rgba(0, 91, 181, 0.3);
        }
    </style>
</head>
<body class="bg-[#F1F5F9] text-gray-800 antialiased">


<!-- ================= MOBILE OVERLAY ================= -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 lg:hidden" onclick="closeSidebar()"></div>


<!-- ================= SIDEBAR ================= -->
<aside id="adminSidebar" class="fixed top-0 left-0 h-full w-[260px] bg-navyLight z-50 flex flex-col">

    <!-- Brand -->
    <div class="px-5 py-5 border-b border-white/10 flex items-center justify-between">
        <a href="/" class="flex items-center gap-2">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-accentBlue to-accentOrange flex items-center justify-center">
                <i class="fas fa-shield-alt text-white text-sm"></i>
            </div>
            <div>
                <p class="font-serif text-lg text-white font-semibold leading-none">HAVENTRA</p>
                <p class="text-[9px] text-accentOrange uppercase tracking-widest font-bold">Admin Panel</p>
            </div>
        </a>
        <button onclick="closeSidebar()" class="lg:hidden w-8 h-8 flex items-center justify-center rounded-lg text-white/60 hover:bg-white/10">
            <i class="fas fa-times text-sm"></i>
        </button>
    </div>

    <!-- Admin info -->
    <div class="px-4 py-4 border-b border-white/10">
        <div class="flex items-center gap-3">
            <div class="relative">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-accentOrange to-red-600 flex items-center justify-center text-white font-bold text-xs">
                    AD
                </div>
                <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-emerald-500 border-2 border-navyLight rounded-full"></span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-bold text-white truncate">Admin User</p>
                <p class="text-[9px] text-white/50 truncate">admin@haventra.com</p>
            </div>
            <span class="text-[8px] font-bold text-accentOrange bg-accentOrange/15 px-1.5 py-0.5 rounded-full">SUPER</span>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto thin-scroll px-3 py-4">
        <p class="text-[9px] uppercase tracking-widest font-bold text-white/30 px-3 mb-2">Main</p>
        <div class="space-y-1">
            <a onclick="showAdminSection('dashboard', this)" class="admin-nav active">
                <i class="fas fa-th-large nav-icon"></i>
                <span>Dashboard</span>
            </a>
            <a onclick="showAdminSection('users', this)" class="admin-nav">
                <i class="fas fa-users nav-icon"></i>
                <span>Users</span>
                <span class="ml-auto text-[9px] bg-accentBlue/30 text-white font-bold px-1.5 py-0.5 rounded-full">1,248</span>
            </a>
            <a onclick="showAdminSection('properties', this)" class="admin-nav">
                <i class="fas fa-building nav-icon"></i>
                <span>Properties</span>
                <span class="ml-auto text-[9px] bg-accentOrange/30 text-white font-bold px-1.5 py-0.5 rounded-full">2,847</span>
            </a>
            <a onclick="showAdminSection('agencies', this)" class="admin-nav">
                <i class="fas fa-handshake nav-icon"></i>
                <span>Agencies</span>
                <span class="ml-auto text-[9px] bg-adminPurple/40 text-white font-bold px-1.5 py-0.5 rounded-full">156</span>
            </a>
            <a onclick="showAdminSection('bookings', this)" class="admin-nav">
                <i class="fas fa-calendar-check nav-icon"></i>
                <span>Bookings</span>
            </a>
            <a onclick="showAdminSection('messages', this)" class="admin-nav">
                <i class="fas fa-comments nav-icon"></i>
                <span>Support</span>
                <span class="ml-auto text-[9px] bg-red-500 text-white font-bold px-1.5 py-0.5 rounded-full">12</span>
            </a>
        </div>

        <p class="text-[9px] uppercase tracking-widest font-bold text-white/30 px-3 mt-5 mb-2">Finance</p>
        <div class="space-y-1">
            <a onclick="showAdminSection('transactions', this)" class="admin-nav">
                <i class="fas fa-wallet nav-icon"></i>
                <span>Transactions</span>
            </a>
            <a onclick="showAdminSection('payouts', this)" class="admin-nav">
                <i class="fas fa-money-bill-wave nav-icon"></i>
                <span>Payouts</span>
                <span class="ml-auto text-[9px] bg-amber-500/30 text-white font-bold px-1.5 py-0.5 rounded-full">5</span>
            </a>
        </div>

        <p class="text-[9px] uppercase tracking-widest font-bold text-white/30 px-3 mt-5 mb-2">Content</p>
        <div class="space-y-1">
            <a onclick="showAdminSection('reviews', this)" class="admin-nav">
                <i class="fas fa-star nav-icon"></i>
                <span>Reviews</span>
            </a>
            <a onclick="showAdminSection('categories', this)" class="admin-nav">
                <i class="fas fa-tags nav-icon"></i>
                <span>Categories</span>
            </a>
            <a onclick="showAdminSection('locations', this)" class="admin-nav">
                <i class="fas fa-map-marker-alt nav-icon"></i>
                <span>Locations</span>
            </a>
        </div>

        <p class="text-[9px] uppercase tracking-widest font-bold text-white/30 px-3 mt-5 mb-2">System</p>
        <div class="space-y-1">
            <a onclick="showAdminSection('reports', this)" class="admin-nav">
                <i class="fas fa-chart-bar nav-icon"></i>
                <span>Reports</span>
            </a>
            <a onclick="showAdminSection('settings', this)" class="admin-nav">
                <i class="fas fa-cog nav-icon"></i>
                <span>Settings</span>
            </a>
            <a onclick="showAdminSection('activity', this)" class="admin-nav">
                <i class="fas fa-history nav-icon"></i>
                <span>Activity Log</span>
            </a>
        </div>
    </nav>

    <!-- Logout -->
    <div class="px-4 py-3 border-t border-white/10">
        <button class="w-full flex items-center gap-2 text-xs text-white/60 hover:text-red-400 font-medium transition-colors">
            <i class="fas fa-sign-out-alt"></i>
            <span>Sign Out</span>
        </button>
    </div>
</aside>


<!-- ================= MAIN CONTENT ================= -->
<div class="lg:ml-[260px] min-h-screen">

    <!-- Top bar -->
    <header class="sticky top-0 z-20 bg-white/90 backdrop-blur-md border-b border-slate-200">
        <div class="px-4 md:px-6 py-3 flex items-center justify-between gap-3">
            
            <div class="flex items-center gap-3 flex-1">
                <button onclick="openSidebar()" class="lg:hidden w-9 h-9 flex items-center justify-center rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors">
                    <i class="fas fa-bars text-sm"></i>
                </button>
                <div>
                    <h1 id="adminPageTitle" class="font-serif text-lg md:text-xl text-navy font-semibold leading-tight">Dashboard</h1>
                    <p id="adminPageSubtitle" class="text-[10px] md:text-xs text-slate-400">Platform overview & key metrics</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <!-- Global search -->
                <div class="hidden md:flex admin-search items-center gap-2 px-3 py-2 w-64">
                    <i class="fas fa-search text-slate-400 text-xs"></i>
                    <input type="text" placeholder="Search users, properties, bookings…" class="bg-transparent text-xs outline-none w-full text-slate-600 placeholder-slate-400">
                    <kbd class="text-[8px] font-bold text-slate-400 bg-white px-1.5 py-0.5 rounded border border-slate-200">⌘K</kbd>
                </div>

                <!-- Quick actions -->
                <button onclick="showToast('Quick actions panel')" class="w-9 h-9 flex items-center justify-center rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors">
                    <i class="fas fa-plus text-sm"></i>
                </button>

                <!-- Notifications -->
                <button onclick="showToast('You have 12 new notifications')" class="relative w-9 h-9 flex items-center justify-center rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors">
                    <i class="fas fa-bell text-sm"></i>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full pulse-dot"></span>
                </button>

                <!-- Messages -->
                <button onclick="showAdminSection('messages', document.querySelectorAll('.admin-nav')[5])" class="relative w-9 h-9 flex items-center justify-center rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors">
                    <i class="fas fa-envelope text-sm"></i>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-accentBlue rounded-full"></span>
                </button>

                <!-- Avatar -->
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-accentOrange to-red-600 flex items-center justify-center text-white font-bold text-[10px] cursor-pointer">
                    AD
                </div>
            </div>
        </div>
    </header>

    <!-- Content area -->
    <main class="p-4 md:p-6 pb-20">

        <!-- ================= DASHBOARD ================= -->
        <section id="admin-section-dashboard" class="admin-section active">

            <!-- Welcome banner -->
            <div class="bg-gradient-to-br from-navy via-navyLight to-accentBlue rounded-2xl p-5 md:p-7 text-white mb-5 relative overflow-hidden">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/5 rounded-full"></div>
                <div class="absolute -bottom-8 -left-8 w-32 h-32 bg-accentOrange/10 rounded-full"></div>
                <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <p class="text-[10px] uppercase tracking-widest text-white/60 font-bold mb-1">Admin Dashboard</p>
                        <h2 class="font-serif text-2xl md:text-3xl font-semibold mb-1">Welcome back, Admin 👋</h2>
                        <p class="text-white/70 text-xs md:text-sm">Platform is healthy. <span class="text-accentOrange font-bold">2,847 properties</span>, <span class="text-accentOrange font-bold">1,248 users</span>, <span class="text-accentOrange font-bold">156 agencies</span>.</p>
                    </div>
                    <div class="flex gap-2 self-start md:self-center">
                        <button onclick="showAdminSection('reports', document.querySelectorAll('.admin-nav')[14])" class="bg-accentOrange text-navy text-xs font-bold px-4 py-2.5 rounded-xl hover:bg-white transition-colors flex items-center gap-2">
                            <i class="fas fa-chart-bar"></i> View Reports
                        </button>
                    </div>
                </div>
            </div>

            <!-- Stats grid -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 mb-5">
                <div class="stat-card bg-white rounded-2xl p-4 border border-slate-100">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-lg bg-accentBlue/10 flex items-center justify-center">
                            <i class="fas fa-users text-accentBlue text-sm"></i>
                        </div>
                        <span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded-full">+8.2%</span>
                    </div>
                    <p class="text-2xl font-bold text-navy">1,248</p>
                    <p class="text-[10px] text-slate-400 font-medium">Total Users</p>
                    <div class="mini-chart mt-2">
                        <div class="mini-bar blue" style="height: 40%"></div>
                        <div class="mini-bar blue" style="height: 55%"></div>
                        <div class="mini-bar blue" style="height: 45%"></div>
                        <div class="mini-bar blue" style="height: 70%"></div>
                        <div class="mini-bar blue" style="height: 60%"></div>
                        <div class="mini-bar blue" style="height: 85%"></div>
                        <div class="mini-bar blue" style="height: 100%"></div>
                    </div>
                </div>

                <div class="stat-card bg-white rounded-2xl p-4 border border-slate-100">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-lg bg-accentOrange/10 flex items-center justify-center">
                            <i class="fas fa-building text-accentOrange text-sm"></i>
                        </div>
                        <span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded-full">+12.5%</span>
                    </div>
                    <p class="text-2xl font-bold text-navy">2,847</p>
                    <p class="text-[10px] text-slate-400 font-medium">Properties</p>
                    <div class="mini-chart mt-2">
                        <div class="mini-bar orange" style="height: 50%"></div>
                        <div class="mini-bar orange" style="height: 65%"></div>
                        <div class="mini-bar orange" style="height: 55%"></div>
                        <div class="mini-bar orange" style="height: 80%"></div>
                        <div class="mini-bar orange" style="height: 70%"></div>
                        <div class="mini-bar orange" style="height: 90%"></div>
                        <div class="mini-bar orange" style="height: 100%"></div>
                    </div>
                </div>

                <div class="stat-card bg-white rounded-2xl p-4 border border-slate-100">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-lg bg-adminGreen/10 flex items-center justify-center">
                            <i class="fas fa-calendar-check text-adminGreen text-sm"></i>
                        </div>
                        <span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded-full">+5.7%</span>
                    </div>
                    <p class="text-2xl font-bold text-navy">4,521</p>
                    <p class="text-[10px] text-slate-400 font-medium">Bookings</p>
                    <div class="mini-chart mt-2">
                        <div class="mini-bar green" style="height: 60%"></div>
                        <div class="mini-bar green" style="height: 45%"></div>
                        <div class="mini-bar green" style="height: 75%"></div>
                        <div class="mini-bar green" style="height: 65%"></div>
                        <div class="mini-bar green" style="height: 85%"></div>
                        <div class="mini-bar green" style="height: 95%"></div>
                        <div class="mini-bar green" style="height: 100%"></div>
                    </div>
                </div>

                <div class="stat-card bg-white rounded-2xl p-4 border border-slate-100">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-9 h-9 rounded-lg bg-adminPurple/10 flex items-center justify-center">
                            <i class="fas fa-wallet text-adminPurple text-sm"></i>
                        </div>
                        <span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded-full">+15.3%</span>
                    </div>
                    <p class="text-2xl font-bold text-navy">KSh 8.4M</p>
                    <p class="text-[10px] text-slate-400 font-medium">Revenue (MTD)</p>
                    <div class="mini-chart mt-2">
                        <div class="mini-bar purple" style="height: 35%"></div>
                        <div class="mini-bar purple" style="height: 55%"></div>
                        <div class="mini-bar purple" style="height: 70%"></div>
                        <div class="mini-bar purple" style="height: 60%"></div>
                        <div class="mini-bar purple" style="height: 85%"></div>
                        <div class="mini-bar purple" style="height: 90%"></div>
                        <div class="mini-bar purple" style="height: 100%"></div>
                    </div>
                </div>
            </div>

            <!-- Charts row -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-5 mb-5">
                
                <!-- Revenue chart -->
                <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 p-4 md:p-5">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="font-serif text-lg text-navy font-semibold">Revenue Trend</h3>
                            <p class="text-[10px] text-slate-400">Last 12 months</p>
                        </div>
                        <div class="flex gap-1">
                            <button class="text-[9px] font-bold text-accentBlue bg-accentBlue/10 px-2 py-1 rounded-md">Monthly</button>
                            <button class="text-[9px] font-bold text-slate-400 px-2 py-1 rounded-md hover:bg-slate-50">Yearly</button>
                        </div>
                    </div>
                    <div class="flex items-end gap-2 h-40">
                        <div class="flex-1 bg-accentBlue/20 rounded-t hover:bg-accentBlue/40 transition-colors" style="height: 40%"></div>
                        <div class="flex-1 bg-accentBlue/20 rounded-t hover:bg-accentBlue/40 transition-colors" style="height: 55%"></div>
                        <div class="flex-1 bg-accentBlue/20 rounded-t hover:bg-accentBlue/40 transition-colors" style="height: 45%"></div>
                        <div class="flex-1 bg-accentBlue/20 rounded-t hover:bg-accentBlue/40 transition-colors" style="height: 70%"></div>
                        <div class="flex-1 bg-accentBlue/20 rounded-t hover:bg-accentBlue/40 transition-colors" style="height: 60%"></div>
                        <div class="flex-1 bg-accentBlue/20 rounded-t hover:bg-accentBlue/40 transition-colors" style="height: 85%"></div>
                        <div class="flex-1 bg-accentBlue/20 rounded-t hover:bg-accentBlue/40 transition-colors" style="height: 75%"></div>
                        <div class="flex-1 bg-accentBlue/20 rounded-t hover:bg-accentBlue/40 transition-colors" style="height: 65%"></div>
                        <div class="flex-1 bg-accentBlue/20 rounded-t hover:bg-accentBlue/40 transition-colors" style="height: 90%"></div>
                        <div class="flex-1 bg-accentBlue/20 rounded-t hover:bg-accentBlue/40 transition-colors" style="height: 80%"></div>
                        <div class="flex-1 bg-accentBlue/20 rounded-t hover:bg-accentBlue/40 transition-colors" style="height: 95%"></div>
                        <div class="flex-1 bg-accentBlue rounded-t" style="height: 100%"></div>
                    </div>
                    <div class="flex justify-between mt-2 text-[8px] text-slate-400">
                        <span>Jan</span><span>Mar</span><span>May</span><span>Jul</span><span>Sep</span><span>Nov</span><span>Dec</span>
                    </div>
                </div>

                <!-- Top categories -->
                <div class="bg-white rounded-2xl border border-slate-100 p-4 md:p-5">
                    <h3 class="font-serif text-lg text-navy font-semibold mb-3">Property Distribution</h3>
                    <div class="space-y-3">
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[11px] font-semibold text-navy">Airbnb / Short Stay</span>
                                <span class="text-[10px] font-bold text-navy">1,145</span>
                            </div>
                            <div class="h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-accentBlue rounded-full" style="width: 80%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[11px] font-semibold text-navy">For Rent</span>
                                <span class="text-[10px] font-bold text-navy">986</span>
                            </div>
                            <div class="h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-adminGreen rounded-full" style="width: 69%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[11px] font-semibold text-navy">For Sale</span>
                                <span class="text-[10px] font-bold text-navy">716</span>
                            </div>
                            <div class="h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-accentOrange rounded-full" style="width: 50%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[11px] font-semibold text-navy">Land</span>
                                <span class="text-[10px] font-bold text-navy">389</span>
                            </div>
                            <div class="h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-adminPurple rounded-full" style="width: 27%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[11px] font-semibold text-navy">Commercial</span>
                                <span class="text-[10px] font-bold text-navy">245</span>
                            </div>
                            <div class="h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-slate-400 rounded-full" style="width: 17%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending actions + Recent activity -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-5">
                
                <!-- Pending actions -->
                <div class="bg-white rounded-2xl border border-slate-100 p-4 md:p-5">
                    <h3 class="font-serif text-lg text-navy font-semibold mb-3">Pending Actions</h3>
                    <div class="space-y-2">
                        <button onclick="showAdminSection('properties', document.querySelectorAll('.admin-nav')[2])" class="w-full flex items-center gap-3 p-3 rounded-xl bg-amber-50 hover:bg-amber-100 transition-colors text-left">
                            <div class="w-8 h-8 rounded-lg bg-amber-500 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-clock text-white text-xs"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[11px] font-bold text-navy">12 pending properties</p>
                                <p class="text-[9px] text-slate-500">Awaiting approval</p>
                            </div>
                            <i class="fas fa-chevron-right text-slate-400 text-[10px]"></i>
                        </button>
                        <button onclick="showAdminSection('payouts', document.querySelectorAll('.admin-nav')[9])" class="w-full flex items-center gap-3 p-3 rounded-xl bg-blue-50 hover:bg-blue-100 transition-colors text-left">
                            <div class="w-8 h-8 rounded-lg bg-accentBlue flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-money-bill-wave text-white text-xs"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[11px] font-bold text-navy">5 payout requests</p>
                                <p class="text-[9px] text-slate-500">Total: KSh 425,000</p>
                            </div>
                            <i class="fas fa-chevron-right text-slate-400 text-[10px]"></i>
                        </button>
                        <button onclick="showAdminSection('users', document.querySelectorAll('.admin-nav')[1])" class="w-full flex items-center gap-3 p-3 rounded-xl bg-emerald-50 hover:bg-emerald-100 transition-colors text-left">
                            <div class="w-8 h-8 rounded-lg bg-emerald-500 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-user-check text-white text-xs"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[11px] font-bold text-navy">8 KYC verifications</p>
                                <p class="text-[9px] text-slate-500">Identity documents</p>
                            </div>
                            <i class="fas fa-chevron-right text-slate-400 text-[10px]"></i>
                        </button>
                        <button onclick="showAdminSection('messages', document.querySelectorAll('.admin-nav')[5])" class="w-full flex items-center gap-3 p-3 rounded-xl bg-red-50 hover:bg-red-100 transition-colors text-left">
                            <div class="w-8 h-8 rounded-lg bg-red-500 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-flag text-white text-xs"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[11px] font-bold text-navy">3 reported listings</p>
                                <p class="text-[9px] text-slate-500">Flagged by users</p>
                            </div>
                            <i class="fas fa-chevron-right text-slate-400 text-[10px]"></i>
                        </button>
                    </div>
                </div>

                <!-- Recent activity -->
                <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 p-4 md:p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-serif text-lg text-navy font-semibold">Recent Activity</h3>
                        <button onclick="showAdminSection('activity', document.querySelectorAll('.admin-nav')[16])" class="text-[10px] text-accentBlue font-semibold hover:underline">View All</button>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-start gap-3 pb-3 border-b border-slate-50">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-user-plus text-emerald-600 text-xs"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-navy">New user registered</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">David Kariuki · david.k@email.com · Client</p>
                            </div>
                            <span class="text-[9px] text-slate-400 whitespace-nowrap">2m ago</span>
                        </div>
                        <div class="flex items-start gap-3 pb-3 border-b border-slate-50">
                            <div class="w-8 h-8 rounded-lg bg-accentBlue/10 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-building text-accentBlue text-xs"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-navy">Property submitted for review</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">Modern 3 Bed Villa · Kilimani · by Prestige Realty</p>
                            </div>
                            <span class="text-[9px] text-slate-400 whitespace-nowrap">8m ago</span>
                        </div>
                        <div class="flex items-start gap-3 pb-3 border-b border-slate-50">
                            <div class="w-8 h-8 rounded-lg bg-accentOrange/10 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-wallet text-accentOrange text-xs"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-navy">Payout processed</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">KSh 125,000 to Guardian Property Managers</p>
                            </div>
                            <span class="text-[9px] text-slate-400 whitespace-nowrap">15m ago</span>
                        </div>
                        <div class="flex items-start gap-3 pb-3 border-b border-slate-50">
                            <div class="w-8 h-8 rounded-lg bg-adminPurple/10 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-handshake text-adminPurple text-xs"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-navy">New agency verified</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">Coastal Homes Ltd · Mombasa · Real Estate Agency</p>
                            </div>
                            <span class="text-[9px] text-slate-400 whitespace-nowrap">1h ago</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-flag text-red-500 text-xs"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-navy">Listing reported</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">Beachfront Villa · reported by 2 users · under review</p>
                            </div>
                            <span class="text-[9px] text-slate-400 whitespace-nowrap">2h ago</span>
                        </div>
                    </div>
                </div>
            </div>

        </section>


        <!-- ================= USERS ================= -->
        <section id="admin-section-users" class="admin-section">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
                <div>
                    <h2 class="font-serif text-2xl text-navy font-semibold">User Management</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Manage all platform users — clients, owners, and companies</p>
                </div>
                <button onclick="showToast('Add user form opened')" class="bg-navy text-white text-xs font-bold px-4 py-2.5 rounded-lg hover:bg-accentBlue transition-colors flex items-center gap-2 self-start sm:self-center">
                    <i class="fas fa-plus"></i> Add User
                </button>
            </div>

            <!-- Filters -->
            <div class="flex flex-wrap gap-2 mb-4">
                <button class="admin-tab active">All (1,248)</button>
                <button class="admin-tab">Clients (892)</button>
                <button class="admin-tab">Owners (241)</button>
                <button class="admin-tab">Companies (115)</button>
                <button class="admin-tab">Suspended (18)</button>
            </div>

            <!-- Search & bulk actions -->
            <div class="bg-white rounded-2xl border border-slate-100 p-4 mb-4">
                <div class="flex flex-col sm:flex-row gap-2">
                    <div class="admin-search flex items-center gap-2 px-3 py-2.5 flex-1">
                        <i class="fas fa-search text-slate-400 text-xs"></i>
                        <input type="text" placeholder="Search by name, email, or phone…" class="bg-transparent text-xs outline-none w-full text-slate-600 placeholder-slate-400">
                    </div>
                    <select class="px-3 py-2.5 bg-white border border-slate-200 rounded-lg text-xs font-medium text-slate-600 focus:outline-none focus:ring-2 focus:ring-accentBlue/30">
                        <option>All Roles</option>
                        <option>Client</option>
                        <option>Owner</option>
                        <option>Company</option>
                    </select>
                    <select class="px-3 py-2.5 bg-white border border-slate-200 rounded-lg text-xs font-medium text-slate-600 focus:outline-none focus:ring-2 focus:ring-accentBlue/30">
                        <option>All Status</option>
                        <option>Active</option>
                        <option>Pending</option>
                        <option>Suspended</option>
                    </select>
                </div>
            </div>

            <!-- Users table -->
            <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto thin-scroll">
                    <table class="admin-table w-full">
                        <thead>
                            <tr>
                                <th class="w-10"><input type="checkbox" class="admin-checkbox"></th>
                                <th>User</th>
                                <th>Role</th>
                                <th>Location</th>
                                <th>Joined</th>
                                <th>Status</th>
                                <th>Listings</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="checkbox" class="admin-checkbox"></td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-accentOrange to-orange-600 flex items-center justify-center text-white font-bold text-[10px] flex-shrink-0">JM</div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-navy truncate">Jane Mwangi</p>
                                            <p class="text-[9px] text-slate-400 truncate">jane.mwangi@email.com</p>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="text-[10px] font-semibold text-accentBlue bg-accentBlue/10 px-2 py-0.5 rounded-full">Owner</span></td>
                                <td class="text-slate-500">Nairobi</td>
                                <td class="text-slate-500">Jan 12, 2024</td>
                                <td><span class="status-badge status-active"><i class="fas fa-check-circle text-[7px]"></i>Active</span></td>
                                <td class="font-bold">8</td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button onclick="showToast('Viewing Jane Mwangi')" class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-navy transition-colors" title="View">
                                            <i class="fas fa-eye text-[10px]"></i>
                                        </button>
                                        <button onclick="showToast('Edit Jane Mwangi')" class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-accentBlue transition-colors" title="Edit">
                                            <i class="fas fa-pen text-[10px]"></i>
                                        </button>
                                        <button onclick="showToast('Suspend Jane Mwangi')" class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-red-50 hover:text-red-500 transition-colors" title="Suspend">
                                            <i class="fas fa-ban text-[10px]"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" class="admin-checkbox"></td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-accentBlue to-blue-700 flex items-center justify-center text-white font-bold text-[10px] flex-shrink-0">DK</div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-navy truncate">David Kariuki</p>
                                            <p class="text-[9px] text-slate-400 truncate">david.k@email.com</p>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="text-[10px] font-semibold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-full">Client</span></td>
                                <td class="text-slate-500">Nairobi</td>
                                <td class="text-slate-500">Feb 3, 2024</td>
                                <td><span class="status-badge status-active"><i class="fas fa-check-circle text-[7px]"></i>Active</span></td>
                                <td class="font-bold">0</td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-navy transition-colors"><i class="fas fa-eye text-[10px]"></i></button>
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-accentBlue transition-colors"><i class="fas fa-pen text-[10px]"></i></button>
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-red-50 hover:text-red-500 transition-colors"><i class="fas fa-ban text-[10px]"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" class="admin-checkbox"></td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-adminPurple to-violet-700 flex items-center justify-center text-white font-bold text-[10px] flex-shrink-0">PR</div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-navy truncate">Prestige Realty Group</p>
                                            <p class="text-[9px] text-slate-400 truncate">info@prestigerealty.co.ke</p>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="text-[10px] font-semibold text-adminPurple bg-adminPurple/10 px-2 py-0.5 rounded-full">Company</span></td>
                                <td class="text-slate-500">Nairobi</td>
                                <td class="text-slate-500">Nov 8, 2023</td>
                                <td><span class="status-badge status-active"><i class="fas fa-check-circle text-[7px]"></i>Active</span></td>
                                <td class="font-bold">45</td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-navy transition-colors"><i class="fas fa-eye text-[10px]"></i></button>
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-accentBlue transition-colors"><i class="fas fa-pen text-[10px]"></i></button>
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-red-50 hover:text-red-500 transition-colors"><i class="fas fa-ban text-[10px]"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" class="admin-checkbox"></td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-amber-500 to-amber-700 flex items-center justify-center text-white font-bold text-[10px] flex-shrink-0">SW</div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-navy truncate">Sarah Wanjiku</p>
                                            <p class="text-[9px] text-slate-400 truncate">sarah.w@email.com</p>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="text-[10px] font-semibold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-full">Owner</span></td>
                                <td class="text-slate-500">Kiambu</td>
                                <td class="text-slate-500">Mar 15, 2024</td>
                                <td><span class="status-badge status-pending"><i class="fas fa-clock text-[7px]"></i>Pending</span></td>
                                <td class="font-bold">2</td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-navy transition-colors"><i class="fas fa-eye text-[10px]"></i></button>
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-accentBlue transition-colors"><i class="fas fa-pen text-[10px]"></i></button>
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-red-50 hover:text-red-500 transition-colors"><i class="fas fa-ban text-[10px]"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" class="admin-checkbox"></td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center text-white font-bold text-[10px] flex-shrink-0">FK</div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-navy truncate">Fred Kimani</p>
                                            <p class="text-[9px] text-slate-400 truncate">fred.k@email.com</p>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="text-[10px] font-semibold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-full">Client</span></td>
                                <td class="text-slate-500">Mombasa</td>
                                <td class="text-slate-500">Apr 22, 2024</td>
                                <td><span class="status-badge status-suspended"><i class="fas fa-ban text-[7px]"></i>Suspended</span></td>
                                <td class="font-bold">0</td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-navy transition-colors"><i class="fas fa-eye text-[10px]"></i></button>
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-accentBlue transition-colors"><i class="fas fa-pen text-[10px]"></i></button>
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-emerald-50 hover:text-emerald-600 transition-colors" title="Reactivate"><i class="fas fa-check text-[10px]"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div class="flex items-center justify-between px-4 py-3 border-t border-slate-100">
                    <p class="text-[10px] text-slate-400">Showing <span class="font-bold text-navy">1-5</span> of <span class="font-bold text-navy">1,248</span> users</p>
                    <div class="flex gap-1">
                        <button class="w-7 h-7 flex items-center justify-center rounded-md border border-slate-200 text-slate-400 hover:border-accentBlue hover:text-accentBlue transition-colors"><i class="fas fa-chevron-left text-[9px]"></i></button>
                        <button class="w-7 h-7 flex items-center justify-center rounded-md bg-navy text-white text-[10px] font-bold">1</button>
                        <button class="w-7 h-7 flex items-center justify-center rounded-md border border-slate-200 text-slate-600 text-[10px] font-bold hover:border-accentBlue hover:text-accentBlue transition-colors">2</button>
                        <button class="w-7 h-7 flex items-center justify-center rounded-md border border-slate-200 text-slate-600 text-[10px] font-bold hover:border-accentBlue hover:text-accentBlue transition-colors">3</button>
                        <button class="w-7 h-7 flex items-center justify-center rounded-md border border-slate-200 text-slate-400 hover:border-accentBlue hover:text-accentBlue transition-colors"><i class="fas fa-chevron-right text-[9px]"></i></button>
                    </div>
                </div>
            </div>
        </section>


        <!-- ================= PROPERTIES ================= -->
        <section id="admin-section-properties" class="admin-section">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
                <div>
                    <h2 class="font-serif text-2xl text-navy font-semibold">Property Management</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Review, approve, and manage all listings</p>
                </div>
                <div class="flex gap-2">
                    <button onclick="showToast('Bulk actions opened')" class="border border-slate-200 text-slate-600 text-xs font-semibold px-4 py-2.5 rounded-lg hover:bg-slate-50 transition-colors flex items-center gap-2">
                        <i class="fas fa-tasks"></i> Bulk Actions
                    </button>
                    <button onclick="showToast('Add property form opened')" class="bg-navy text-white text-xs font-bold px-4 py-2.5 rounded-lg hover:bg-accentBlue transition-colors flex items-center gap-2">
                        <i class="fas fa-plus"></i> Add Property
                    </button>
                </div>
            </div>

            <div class="flex flex-wrap gap-2 mb-4">
                <button class="admin-tab active">All (2,847)</button>
                <button class="admin-tab">Pending (12)</button>
                <button class="admin-tab">Active (2,745)</button>
                <button class="admin-tab">Draft (58)</button>
                <button class="admin-tab">Suspended (20)</button>
                <button class="admin-tab">Reported (3)</button>
            </div>

            <!-- Pending approval banner -->
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 mb-4 flex items-start gap-3">
                <div class="w-9 h-9 rounded-lg bg-amber-500 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-white text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="text-xs font-bold text-navy">12 properties are awaiting your approval</p>
                    <p class="text-[10px] text-slate-500 mt-0.5">New listings submitted by owners and agencies. Review them to publish on the platform.</p>
                </div>
                <button onclick="showToast('Reviewing pending properties')" class="bg-amber-500 text-white text-[10px] font-bold px-3 py-1.5 rounded-lg hover:bg-amber-600 transition-colors whitespace-nowrap">
                    Review Now
                </button>
            </div>

            <!-- Properties table -->
            <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto thin-scroll">
                    <table class="admin-table w-full">
                        <thead>
                            <tr>
                                <th class="w-10"><input type="checkbox" class="admin-checkbox"></th>
                                <th>Property</th>
                                <th>Category</th>
                                <th>Owner/Agency</th>
                                <th>Price</th>
                                <th>Submitted</th>
                                <th>Status</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="checkbox" class="admin-checkbox"></td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <img src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=100&q=80" alt="Property" class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-navy truncate">Modern 3 Bed Villa</p>
                                            <p class="text-[9px] text-slate-400 truncate">Kilimani, Nairobi</p>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="text-[10px] font-semibold text-accentBlue bg-accentBlue/10 px-2 py-0.5 rounded-full">For Rent</span></td>
                                <td class="text-slate-500 text-[10px]">Prestige Realty</td>
                                <td class="font-bold">KSh 150K</td>
                                <td class="text-slate-500 text-[10px]">2h ago</td>
                                <td><span class="status-badge status-pending"><i class="fas fa-clock text-[7px]"></i>Pending</span></td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button onclick="showToast('Approving property')" class="w-7 h-7 flex items-center justify-center rounded-md text-emerald-500 hover:bg-emerald-50 transition-colors" title="Approve">
                                            <i class="fas fa-check text-[10px]"></i>
                                        </button>
                                        <button onclick="showToast('Rejecting property')" class="w-7 h-7 flex items-center justify-center rounded-md text-red-500 hover:bg-red-50 transition-colors" title="Reject">
                                            <i class="fas fa-times text-[10px]"></i>
                                        </button>
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-navy transition-colors" title="View">
                                            <i class="fas fa-eye text-[10px]"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" class="admin-checkbox"></td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=100&q=80" alt="Property" class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-navy truncate">Spacious Family Home</p>
                                            <p class="text-[9px] text-slate-400 truncate">Karen, Nairobi</p>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="text-[10px] font-semibold text-adminGreen bg-adminGreen/10 px-2 py-0.5 rounded-full">For Rent</span></td>
                                <td class="text-slate-500 text-[10px]">Jane Mwangi</td>
                                <td class="font-bold">KSh 200K</td>
                                <td class="text-slate-500 text-[10px]">5h ago</td>
                                <td><span class="status-badge status-active"><i class="fas fa-check-circle text-[7px]"></i>Active</span></td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-navy transition-colors"><i class="fas fa-eye text-[10px]"></i></button>
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-accentBlue transition-colors"><i class="fas fa-pen text-[10px]"></i></button>
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-red-50 hover:text-red-500 transition-colors"><i class="fas fa-ban text-[10px]"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" class="admin-checkbox"></td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <img src="https://images.unsplash.com/photo-1499793983690-e29da59ef1c2?auto=format&fit=crop&w=100&q=80" alt="Property" class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-navy truncate">Beachfront Villa</p>
                                            <p class="text-[9px] text-slate-400 truncate">Diani, Mombasa</p>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="text-[10px] font-semibold text-accentOrange bg-accentOrange/10 px-2 py-0.5 rounded-full">Short Stay</span></td>
                                <td class="text-slate-500 text-[10px]">Coastal Homes</td>
                                <td class="font-bold">KSh 25K</td>
                                <td class="text-slate-500 text-[10px]">1d ago</td>
                                <td><span class="status-badge status-active"><i class="fas fa-check-circle text-[7px]"></i>Active</span></td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-navy transition-colors"><i class="fas fa-eye text-[10px]"></i></button>
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-accentBlue transition-colors"><i class="fas fa-pen text-[10px]"></i></button>
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-red-50 hover:text-red-500 transition-colors"><i class="fas fa-ban text-[10px]"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" class="admin-checkbox"></td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <img src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=100&q=80" alt="Property" class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-navy truncate">Contemporary Townhouse</p>
                                            <p class="text-[9px] text-slate-400 truncate">Kileleshwa, Nairobi</p>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="text-[10px] font-semibold text-adminPurple bg-adminPurple/10 px-2 py-0.5 rounded-full">For Sale</span></td>
                                <td class="text-slate-500 text-[10px]">Jane Mwangi</td>
                                <td class="font-bold">KSh 25M</td>
                                <td class="text-slate-500 text-[10px]">2d ago</td>
                                <td><span class="status-badge status-pending"><i class="fas fa-clock text-[7px]"></i>Pending</span></td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-emerald-500 hover:bg-emerald-50 transition-colors"><i class="fas fa-check text-[10px]"></i></button>
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-red-500 hover:bg-red-50 transition-colors"><i class="fas fa-times text-[10px]"></i></button>
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-navy transition-colors"><i class="fas fa-eye text-[10px]"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" class="admin-checkbox"></td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <img src="https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=100&q=80" alt="Property" class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-navy truncate">Prime 1/4 Acre Plot</p>
                                            <p class="text-[9px] text-slate-400 truncate">Ngong, Kajiado</p>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="text-[10px] font-semibold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-full">Land</span></td>
                                <td class="text-slate-500 text-[10px]">Summit & Valley</td>
                                <td class="font-bold">KSh 4.5M</td>
                                <td class="text-slate-500 text-[10px]">3d ago</td>
                                <td><span class="status-badge status-suspended"><i class="fas fa-flag text-[7px]"></i>Reported</span></td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-navy transition-colors"><i class="fas fa-eye text-[10px]"></i></button>
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-accentBlue transition-colors"><i class="fas fa-pen text-[10px]"></i></button>
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-red-500 hover:bg-red-50 transition-colors"><i class="fas fa-trash text-[10px]"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="flex items-center justify-between px-4 py-3 border-t border-slate-100">
                    <p class="text-[10px] text-slate-400">Showing <span class="font-bold text-navy">1-5</span> of <span class="font-bold text-navy">2,847</span> properties</p>
                    <div class="flex gap-1">
                        <button class="w-7 h-7 flex items-center justify-center rounded-md border border-slate-200 text-slate-400"><i class="fas fa-chevron-left text-[9px]"></i></button>
                        <button class="w-7 h-7 flex items-center justify-center rounded-md bg-navy text-white text-[10px] font-bold">1</button>
                        <button class="w-7 h-7 flex items-center justify-center rounded-md border border-slate-200 text-slate-600 text-[10px] font-bold">2</button>
                        <button class="w-7 h-7 flex items-center justify-center rounded-md border border-slate-200 text-slate-600 text-[10px] font-bold">3</button>
                        <button class="w-7 h-7 flex items-center justify-center rounded-md border border-slate-200 text-slate-400"><i class="fas fa-chevron-right text-[9px]"></i></button>
                    </div>
                </div>
            </div>
        </section>


        <!-- ================= AGENCIES ================= -->
        <section id="admin-section-agencies" class="admin-section">
            <div class="mb-5">
                <h2 class="font-serif text-2xl text-navy font-semibold">Agency Management</h2>
                <p class="text-xs text-slate-400 mt-0.5">Manage real estate agencies and cleaning companies</p>
            </div>

            <div class="flex flex-wrap gap-2 mb-4">
                <button class="admin-tab active">All (156)</button>
                <button class="admin-tab">Real Estate (72)</button>
                <button class="admin-tab">Property Mgmt (48)</button>
                <button class="admin-tab">Cleaning (36)</button>
                <button class="admin-tab">Pending (8)</button>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto thin-scroll">
                    <table class="admin-table w-full">
                        <thead>
                            <tr>
                                <th class="w-10"><input type="checkbox" class="admin-checkbox"></th>
                                <th>Agency</th>
                                <th>Category</th>
                                <th>Location</th>
                                <th>Rating</th>
                                <th>Verified</th>
                                <th>Status</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="checkbox" class="admin-checkbox"></td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-lg bg-adminPurple/10 flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-handshake text-adminPurple text-xs"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-navy truncate">Prestige Realty Group</p>
                                            <p class="text-[9px] text-slate-400 truncate">info@prestigerealty.co.ke</p>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="text-[10px] font-semibold text-adminPurple bg-adminPurple/10 px-2 py-0.5 rounded-full">Real Estate</span></td>
                                <td class="text-slate-500 text-[10px]">Nairobi</td>
                                <td><span class="text-[10px] font-bold text-accentOrange"><i class="fas fa-star mr-0.5"></i>5.0</span></td>
                                <td><span class="status-badge status-active"><i class="fas fa-certificate text-[7px]"></i>Verified</span></td>
                                <td><span class="status-badge status-active">Active</span></td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-navy transition-colors"><i class="fas fa-eye text-[10px]"></i></button>
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-accentBlue transition-colors"><i class="fas fa-pen text-[10px]"></i></button>
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-red-50 hover:text-red-500 transition-colors"><i class="fas fa-ban text-[10px]"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" class="admin-checkbox"></td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-lg bg-accentBlue/10 flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-shield-alt text-accentBlue text-xs"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-navy truncate">Guardian Property Managers</p>
                                            <p class="text-[9px] text-slate-400 truncate">info@guardianpm.co.ke</p>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="text-[10px] font-semibold text-accentBlue bg-accentBlue/10 px-2 py-0.5 rounded-full">Property Mgmt</span></td>
                                <td class="text-slate-500 text-[10px]">Nairobi</td>
                                <td><span class="text-[10px] font-bold text-accentOrange"><i class="fas fa-star mr-0.5"></i>5.0</span></td>
                                <td><span class="status-badge status-active"><i class="fas fa-certificate text-[7px]"></i>Verified</span></td>
                                <td><span class="status-badge status-active">Active</span></td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-navy transition-colors"><i class="fas fa-eye text-[10px]"></i></button>
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-accentBlue transition-colors"><i class="fas fa-pen text-[10px]"></i></button>
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-red-50 hover:text-red-500 transition-colors"><i class="fas fa-ban text-[10px]"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" class="admin-checkbox"></td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-lg bg-adminGreen/10 flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-spray-can text-adminGreen text-xs"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-navy truncate">Sparkle & Shine Pro</p>
                                            <p class="text-[9px] text-slate-400 truncate">info@sparkleshine.co.ke</p>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="text-[10px] font-semibold text-adminGreen bg-adminGreen/10 px-2 py-0.5 rounded-full">Cleaning</span></td>
                                <td class="text-slate-500 text-[10px]">Nairobi</td>
                                <td><span class="text-[10px] font-bold text-accentOrange"><i class="fas fa-star mr-0.5"></i>5.0</span></td>
                                <td><span class="status-badge status-active"><i class="fas fa-certificate text-[7px]"></i>Verified</span></td>
                                <td><span class="status-badge status-active">Active</span></td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-navy transition-colors"><i class="fas fa-eye text-[10px]"></i></button>
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-accentBlue transition-colors"><i class="fas fa-pen text-[10px]"></i></button>
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-red-50 hover:text-red-500 transition-colors"><i class="fas fa-ban text-[10px]"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" class="admin-checkbox"></td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-lg bg-amber-500/10 flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-water text-amber-600 text-xs"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-navy truncate">Coastal Homes Ltd</p>
                                            <p class="text-[9px] text-slate-400 truncate">sales@coastalhomes.co.ke</p>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="text-[10px] font-semibold text-adminPurple bg-adminPurple/10 px-2 py-0.5 rounded-full">Real Estate</span></td>
                                <td class="text-slate-500 text-[10px]">Mombasa</td>
                                <td><span class="text-[10px] font-bold text-accentOrange"><i class="fas fa-star mr-0.5"></i>4.0</span></td>
                                <td><span class="status-badge status-pending"><i class="fas fa-clock text-[7px]"></i>Pending</span></td>
                                <td><span class="status-badge status-pending">Pending</span></td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-emerald-500 hover:bg-emerald-50 transition-colors" title="Verify"><i class="fas fa-check text-[10px]"></i></button>
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-red-500 hover:bg-red-50 transition-colors" title="Reject"><i class="fas fa-times text-[10px]"></i></button>
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-navy transition-colors"><i class="fas fa-eye text-[10px]"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>


        <!-- ================= BOOKINGS ================= -->
        <section id="admin-section-bookings" class="admin-section">
            <div class="mb-5">
                <h2 class="font-serif text-2xl text-navy font-semibold">Bookings Management</h2>
                <p class="text-xs text-slate-400 mt-0.5">Manage all reservations, viewings, and short-stay bookings</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-5">
                <div class="bg-white rounded-xl p-3 border border-slate-100 text-center">
                    <p class="text-[10px] text-slate-400 font-medium">Total</p>
                    <p class="text-xl font-bold text-navy">4,521</p>
                </div>
                <div class="bg-white rounded-xl p-3 border border-slate-100 text-center">
                    <p class="text-[10px] text-slate-400 font-medium">Confirmed</p>
                    <p class="text-xl font-bold text-adminGreen">3,842</p>
                </div>
                <div class="bg-white rounded-xl p-3 border border-slate-100 text-center">
                    <p class="text-[10px] text-slate-400 font-medium">Pending</p>
                    <p class="text-xl font-bold text-accentOrange">342</p>
                </div>
                <div class="bg-white rounded-xl p-3 border border-slate-100 text-center">
                    <p class="text-[10px] text-slate-400 font-medium">Cancelled</p>
                    <p class="text-xl font-bold text-red-500">337</p>
                </div>
            </div>

            <div class="flex flex-wrap gap-2 mb-4">
                <button class="admin-tab active">All (4,521)</button>
                <button class="admin-tab">Upcoming (124)</button>
                <button class="admin-tab">Active (86)</button>
                <button class="admin-tab">Completed (3,842)</button>
                <button class="admin-tab">Cancelled (337)</button>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto thin-scroll">
                    <table class="admin-table w-full">
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Property</th>
                                <th>Guest</th>
                                <th>Dates</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-mono text-[10px] font-bold text-accentBlue">#HV-2451</td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <img src="https://images.unsplash.com/photo-1499793983690-e29da59ef1c2?auto=format&fit=crop&w=80&q=80" alt="Property" class="w-8 h-8 rounded-md object-cover flex-shrink-0">
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-navy truncate">Beachfront Villa</p>
                                            <p class="text-[9px] text-slate-400 truncate">Diani, Mombasa</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-accentOrange flex items-center justify-center text-white font-bold text-[8px] flex-shrink-0">DK</div>
                                        <span class="text-[10px] text-slate-600">David K.</span>
                                    </div>
                                </td>
                                <td class="text-[10px] text-slate-500">Dec 15 – Dec 18</td>
                                <td class="font-bold">KSh 55,500</td>
                                <td><span class="status-badge status-confirmed"><i class="fas fa-check-circle text-[7px]"></i>Confirmed</span></td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-navy transition-colors"><i class="fas fa-eye text-[10px]"></i></button>
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-accentBlue transition-colors"><i class="fas fa-pen text-[10px]"></i></button>
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-red-50 hover:text-red-500 transition-colors"><i class="fas fa-times text-[10px]"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-mono text-[10px] font-bold text-accentBlue">#HV-2450</td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <img src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=80&q=80" alt="Property" class="w-8 h-8 rounded-md object-cover flex-shrink-0">
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-navy truncate">Modern 3 Bed Villa</p>
                                            <p class="text-[9px] text-slate-400 truncate">Kilimani, Nairobi</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-accentBlue flex items-center justify-center text-white font-bold text-[8px] flex-shrink-0">SW</div>
                                        <span class="text-[10px] text-slate-600">Sarah W.</span>
                                    </div>
                                </td>
                                <td class="text-[10px] text-slate-500">Jan 5, 2025</td>
                                <td class="font-bold">Free</td>
                                <td><span class="status-badge status-pending"><i class="fas fa-clock text-[7px]"></i>Viewing</span></td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-emerald-500 hover:bg-emerald-50 transition-colors"><i class="fas fa-check text-[10px]"></i></button>
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-red-500 hover:bg-red-50 transition-colors"><i class="fas fa-times text-[10px]"></i></button>
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-navy transition-colors"><i class="fas fa-eye text-[10px]"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-mono text-[10px] font-bold text-accentBlue">#HV-2449</td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <img src="https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?auto=format&fit=crop&w=80&q=80" alt="Property" class="w-8 h-8 rounded-md object-cover flex-shrink-0">
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-navy truncate">Luxury Safari Lodge</p>
                                            <p class="text-[9px] text-slate-400 truncate">Maasai Mara</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-adminGreen flex items-center justify-center text-white font-bold text-[8px] flex-shrink-0">JK</div>
                                        <span class="text-[10px] text-slate-600">James K.</span>
                                    </div>
                                </td>
                                <td class="text-[10px] text-slate-500">Feb 10 – Feb 12</td>
                                <td class="font-bold">KSh 90,000</td>
                                <td><span class="status-badge status-pending"><i class="fas fa-clock text-[7px]"></i>Pending</span></td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-emerald-500 hover:bg-emerald-50 transition-colors"><i class="fas fa-check text-[10px]"></i></button>
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-red-500 hover:bg-red-50 transition-colors"><i class="fas fa-times text-[10px]"></i></button>
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-navy transition-colors"><i class="fas fa-eye text-[10px]"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-mono text-[10px] font-bold text-accentBlue">#HV-2448</td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <img src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=80&q=80" alt="Property" class="w-8 h-8 rounded-md object-cover flex-shrink-0">
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-navy truncate">Executive City Suite</p>
                                            <p class="text-[9px] text-slate-400 truncate">Westlands, Nairobi</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-adminPurple flex items-center justify-center text-white font-bold text-[8px] flex-shrink-0">AM</div>
                                        <span class="text-[10px] text-slate-600">Alice M.</span>
                                    </div>
                                </td>
                                <td class="text-[10px] text-slate-500">Nov 1 – Nov 3</td>
                                <td class="font-bold">KSh 30,000</td>
                                <td><span class="status-badge status-completed"><i class="fas fa-check text-[7px]"></i>Completed</span></td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-navy transition-colors"><i class="fas fa-eye text-[10px]"></i></button>
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-accentBlue transition-colors"><i class="fas fa-receipt text-[10px]"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>


        <!-- ================= SUPPORT MESSAGES ================= -->
        <section id="admin-section-messages" class="admin-section">
            <div class="mb-5">
                <h2 class="font-serif text-2xl text-navy font-semibold">Support Messages</h2>
                <p class="text-xs text-slate-400 mt-0.5">Manage user inquiries, complaints, and support tickets</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <!-- Ticket list -->
                <div class="bg-white rounded-2xl border border-slate-100 p-3">
                    <div class="flex items-center gap-2 mb-3 px-1">
                        <div class="admin-search flex items-center gap-2 px-3 py-2 flex-1">
                            <i class="fas fa-search text-slate-400 text-[10px]"></i>
                            <input type="text" placeholder="Search tickets…" class="bg-transparent text-[11px] outline-none w-full text-slate-600 placeholder-slate-400">
                        </div>
                    </div>
                    <div class="space-y-1">
                        <div class="p-2.5 rounded-lg bg-accentBlue/5 border border-accentBlue/20 cursor-pointer">
                            <div class="flex items-center justify-between gap-1">
                                <span class="text-[9px] font-bold text-accentBlue bg-accentBlue/10 px-1.5 py-0.5 rounded">#TKT-1024</span>
                                <span class="text-[9px] text-red-500 font-bold">High</span>
                            </div>
                            <p class="text-[11px] font-bold text-navy mt-1 truncate">Booking not confirmed</p>
                            <p class="text-[10px] text-slate-500 truncate mt-0.5">"I paid for a booking but haven't received confirmation…"</p>
                            <div class="flex items-center justify-between mt-1.5">
                                <span class="text-[9px] text-slate-400">David K.</span>
                                <span class="text-[9px] text-slate-400">2h ago</span>
                            </div>
                        </div>
                        <div class="p-2.5 rounded-lg hover:bg-slate-50 cursor-pointer">
                            <div class="flex items-center justify-between gap-1">
                                <span class="text-[9px] font-bold text-slate-500 bg-slate-100 px-1.5 py-0.5 rounded">#TKT-1023</span>
                                <span class="text-[9px] text-amber-500 font-bold">Medium</span>
                            </div>
                            <p class="text-[11px] font-bold text-navy mt-1 truncate">Cannot upload photos</p>
                            <p class="text-[10px] text-slate-500 truncate mt-0.5">"The upload button doesn't work on my phone…"</p>
                            <div class="flex items-center justify-between mt-1.5">
                                <span class="text-[9px] text-slate-400">Sarah W.</span>
                                <span class="text-[9px] text-slate-400">5h ago</span>
                            </div>
                        </div>
                        <div class="p-2.5 rounded-lg hover:bg-slate-50 cursor-pointer">
                            <div class="flex items-center justify-between gap-1">
                                <span class="text-[9px] font-bold text-slate-500 bg-slate-100 px-1.5 py-0.5 rounded">#TKT-1022</span>
                                <span class="text-[9px] text-slate-400 font-bold">Low</span>
                            </div>
                            <p class="text-[11px] font-bold text-navy mt-1 truncate">Question about fees</p>
                            <p class="text-[10px] text-slate-500 truncate mt-0.5">"What are the platform fees for agencies…"</p>
                            <div class="flex items-center justify-between mt-1.5">
                                <span class="text-[9px] text-slate-400">Prestige Realty</span>
                                <span class="text-[9px] text-slate-400">1d ago</span>
                            </div>
                        </div>
                        <div class="p-2.5 rounded-lg hover:bg-slate-50 cursor-pointer">
                            <div class="flex items-center justify-between gap-1">
                                <span class="text-[9px] font-bold text-slate-500 bg-slate-100 px-1.5 py-0.5 rounded">#TKT-1021</span>
                                <span class="text-[9px] text-amber-500 font-bold">Medium</span>
                            </div>
                            <p class="text-[11px] font-bold text-navy mt-1 truncate">Report a listing</p>
                            <p class="text-[10px] text-slate-500 truncate mt-0.5">"This property has incorrect information…"</p>
                            <div class="flex items-center justify-between mt-1.5">
                                <span class="text-[9px] text-slate-400">James K.</span>
                                <span class="text-[9px] text-slate-400">1d ago</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chat view -->
                <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 flex flex-col h-[500px]">
                    <div class="p-3 border-b border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-accentOrange flex items-center justify-center text-white font-bold text-[10px]">DK</div>
                            <div>
                                <p class="text-xs font-bold text-navy">David K. — #TKT-1024</p>
                                <p class="text-[9px] text-red-500 font-semibold flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span> High Priority
                                </p>
                            </div>
                        </div>
                        <div class="flex gap-1">
                            <button class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 transition-colors" title="Resolve"><i class="fas fa-check text-xs"></i></button>
                            <button class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 transition-colors" title="Close"><i class="fas fa-times text-xs"></i></button>
                        </div>
                    </div>

                    <div class="flex-1 overflow-y-auto thin-scroll p-4 space-y-3 bg-slate-50/50">
                        <div class="flex justify-start">
                            <div class="max-w-[80%] bg-white rounded-2xl rounded-bl-sm px-3.5 py-2.5 shadow-sm border border-slate-100">
                                <p class="text-[11px] text-slate-700">Hello, I paid for a booking (Beachfront Villa, Dec 15-18) about 2 hours ago but I haven't received any confirmation email or message. Can you help?</p>
                                <span class="text-[8px] text-slate-400 mt-1 block">2:15 PM</span>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <div class="max-w-[80%] bg-gradient-to-br from-navy to-accentBlue text-white rounded-2xl rounded-br-sm px-3.5 py-2.5 shadow-sm">
                                <p class="text-[11px]">Hi David! I'm sorry for the inconvenience. Let me check your booking status right away. Could you please share your booking reference number?</p>
                                <span class="text-[8px] text-white/60 mt-1 block text-right">2:18 PM</span>
                            </div>
                        </div>
                        <div class="flex justify-start">
                            <div class="max-w-[80%] bg-white rounded-2xl rounded-bl-sm px-3.5 py-2.5 shadow-sm border border-slate-100">
                                <p class="text-[11px] text-slate-700">The reference is #HV-2451. I have the M-Pesa transaction receipt if that helps.</p>
                                <span class="text-[8px] text-slate-400 mt-1 block">2:20 PM</span>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <div class="max-w-[80%] bg-gradient-to-br from-navy to-accentBlue text-white rounded-2xl rounded-br-sm px-3.5 py-2.5 shadow-sm">
                                <p class="text-[11px]">Thank you! I've found your booking — it was confirmed. The confirmation email may have gone to your spam folder. I'm re-sending it now. Please check in a few minutes.</p>
                                <span class="text-[8px] text-white/60 mt-1 block text-right">2:24 PM</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-3 border-t border-slate-100">
                        <div class="flex items-center gap-2">
                            <button class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 transition-colors flex-shrink-0">
                                <i class="fas fa-paperclip text-xs"></i>
                            </button>
                            <input type="text" placeholder="Type your reply…" class="flex-1 bg-slate-100 rounded-lg px-3 py-2 text-[11px] outline-none text-slate-600 placeholder-slate-400">
                            <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-navy text-white hover:bg-accentBlue transition-colors flex-shrink-0">
                                <i class="fas fa-paper-plane text-[10px]"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <!-- ================= TRANSACTIONS ================= -->
        <section id="admin-section-transactions" class="admin-section">
            <div class="mb-5">
                <h2 class="font-serif text-2xl text-navy font-semibold">Transactions</h2>
                <p class="text-xs text-slate-400 mt-0.5">All financial transactions on the platform</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-5">
                <div class="bg-white rounded-xl p-3 border border-slate-100">
                    <p class="text-[10px] text-slate-400 font-medium">Total Revenue</p>
                    <p class="text-xl font-bold text-navy mt-1">KSh 8.4M</p>
                </div>
                <div class="bg-white rounded-xl p-3 border border-slate-100">
                    <p class="text-[10px] text-slate-400 font-medium">This Month</p>
                    <p class="text-xl font-bold text-adminGreen mt-1">KSh 1.2M</p>
                </div>
                <div class="bg-white rounded-xl p-3 border border-slate-100">
                    <p class="text-[10px] text-slate-400 font-medium">Pending Payouts</p>
                    <p class="text-xl font-bold text-accentOrange mt-1">KSh 425K</p>
                </div>
                <div class="bg-white rounded-xl p-3 border border-slate-100">
                    <p class="text-[10px] text-slate-400 font-medium">Transactions</p>
                    <p class="text-xl font-bold text-navy mt-1">4,521</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto thin-scroll">
                    <table class="admin-table w-full">
                        <thead>
                            <tr>
                                <th>Transaction ID</th>
                                <th>Type</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th class="text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-mono text-[10px] font-bold text-accentBlue">#TXN-84521</td>
                                <td><span class="text-[10px] font-semibold text-adminGreen bg-adminGreen/10 px-2 py-0.5 rounded-full">Booking</span></td>
                                <td class="text-slate-500 text-[10px]">David Kariuki</td>
                                <td class="text-slate-500 text-[10px]">Jane Mwangi</td>
                                <td class="text-slate-500 text-[10px]">Dec 10, 2024</td>
                                <td><span class="status-badge status-completed">Completed</span></td>
                                <td class="text-right font-bold text-adminGreen">+ KSh 55,500</td>
                            </tr>
                            <tr>
                                <td class="font-mono text-[10px] font-bold text-accentBlue">#TXN-84520</td>
                                <td><span class="text-[10px] font-semibold text-accentOrange bg-accentOrange/10 px-2 py-0.5 rounded-full">Payout</span></td>
                                <td class="text-slate-500 text-[10px]">HAVENTRA</td>
                                <td class="text-slate-500 text-[10px]">Guardian PM</td>
                                <td class="text-slate-500 text-[10px]">Dec 10, 2024</td>
                                <td><span class="status-badge status-completed">Processed</span></td>
                                <td class="text-right font-bold text-red-500">− KSh 125,000</td>
                            </tr>
                            <tr>
                                <td class="font-mono text-[10px] font-bold text-accentBlue">#TXN-84519</td>
                                <td><span class="text-[10px] font-semibold text-adminGreen bg-adminGreen/10 px-2 py-0.5 rounded-full">Booking</span></td>
                                <td class="text-slate-500 text-[10px]">Sarah Wanjiku</td>
                                <td class="text-slate-500 text-[10px]">Prestige Realty</td>
                                <td class="text-slate-500 text-[10px]">Dec 9, 2024</td>
                                <td><span class="status-badge status-pending">Pending</span></td>
                                <td class="text-right font-bold text-adminGreen">+ KSh 45,000</td>
                            </tr>
                            <tr>
                                <td class="font-mono text-[10px] font-bold text-accentBlue">#TXN-84518</td>
                                <td><span class="text-[10px] font-semibold text-adminPurple bg-adminPurple/10 px-2 py-0.5 rounded-full">Fee</span></td>
                                <td class="text-slate-500 text-[10px]">Coastal Homes</td>
                                <td class="text-slate-500 text-[10px]">HAVENTRA</td>
                                <td class="text-slate-500 text-[10px]">Dec 8, 2024</td>
                                <td><span class="status-badge status-completed">Completed</span></td>
                                <td class="text-right font-bold text-adminGreen">+ KSh 8,500</td>
                            </tr>
                            <tr>
                                <td class="font-mono text-[10px] font-bold text-accentBlue">#TXN-84517</td>
                                <td><span class="text-[10px] font-semibold text-adminGreen bg-adminGreen/10 px-2 py-0.5 rounded-full">Booking</span></td>
                                <td class="text-slate-500 text-[10px]">James K.</td>
                                <td class="text-slate-500 text-[10px]">Jane Mwangi</td>
                                <td class="text-slate-500 text-[10px]">Dec 7, 2024</td>
                                <td><span class="status-badge status-completed">Completed</span></td>
                                <td class="text-right font-bold text-adminGreen">+ KSh 90,000</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>


        <!-- ================= PAYOUTS ================= -->
        <section id="admin-section-payouts" class="admin-section">
            <div class="mb-5">
                <h2 class="font-serif text-2xl text-navy font-semibold">Payouts</h2>
                <p class="text-xs text-slate-400 mt-0.5">Manage withdrawal requests from owners and agencies</p>
            </div>

            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 mb-4 flex items-start gap-3">
                <div class="w-9 h-9 rounded-lg bg-amber-500 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-money-bill-wave text-white text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="text-xs font-bold text-navy">5 payout requests pending</p>
                    <p class="text-[10px] text-slate-500 mt-0.5">Total amount: KSh 425,000. Review and process these requests.</p>
                </div>
                <button onclick="showToast('Processing all payouts')" class="bg-amber-500 text-white text-[10px] font-bold px-3 py-1.5 rounded-lg hover:bg-amber-600 transition-colors whitespace-nowrap">
                    Process All
                </button>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto thin-scroll">
                    <table class="admin-table w-full">
                        <thead>
                            <tr>
                                <th>Request ID</th>
                                <th>Recipient</th>
                                <th>Method</th>
                                <th>Requested</th>
                                <th>Status</th>
                                <th class="text-right">Amount</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-mono text-[10px] font-bold text-accentBlue">#PAY-1024</td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-adminPurple flex items-center justify-center text-white font-bold text-[8px] flex-shrink-0">PR</div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-navy truncate">Prestige Realty</p>
                                            <p class="text-[9px] text-slate-400 truncate">Bank Transfer</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-slate-500 text-[10px]">M-Pesa</td>
                                <td class="text-slate-500 text-[10px]">2h ago</td>
                                <td><span class="status-badge status-pending">Pending</span></td>
                                <td class="text-right font-bold">KSh 125,000</td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button onclick="showToast('Approving payout')" class="w-7 h-7 flex items-center justify-center rounded-md text-emerald-500 hover:bg-emerald-50 transition-colors" title="Approve"><i class="fas fa-check text-[10px]"></i></button>
                                        <button onclick="showToast('Rejecting payout')" class="w-7 h-7 flex items-center justify-center rounded-md text-red-500 hover:bg-red-50 transition-colors" title="Reject"><i class="fas fa-times text-[10px]"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-mono text-[10px] font-bold text-accentBlue">#PAY-1023</td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-accentBlue flex items-center justify-center text-white font-bold text-[8px] flex-shrink-0">GP</div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-navy truncate">Guardian PM</p>
                                            <p class="text-[9px] text-slate-400 truncate">Bank Transfer</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-slate-500 text-[10px]">Bank</td>
                                <td class="text-slate-500 text-[10px]">5h ago</td>
                                <td><span class="status-badge status-pending">Pending</span></td>
                                <td class="text-right font-bold">KSh 185,000</td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-emerald-500 hover:bg-emerald-50 transition-colors"><i class="fas fa-check text-[10px]"></i></button>
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-red-500 hover:bg-red-50 transition-colors"><i class="fas fa-times text-[10px]"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-mono text-[10px] font-bold text-accentBlue">#PAY-1022</td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-accentOrange flex items-center justify-center text-white font-bold text-[8px] flex-shrink-0">JM</div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-navy truncate">Jane Mwangi</p>
                                            <p class="text-[9px] text-slate-400 truncate">M-Pesa</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-slate-500 text-[10px]">M-Pesa</td>
                                <td class="text-slate-500 text-[10px]">1d ago</td>
                                <td><span class="status-badge status-pending">Pending</span></td>
                                <td class="text-right font-bold">KSh 115,000</td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-emerald-500 hover:bg-emerald-50 transition-colors"><i class="fas fa-check text-[10px]"></i></button>
                                        <button class="w-7 h-7 flex items-center justify-center rounded-md text-red-500 hover:bg-red-50 transition-colors"><i class="fas fa-times text-[10px]"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>


        <!-- ================= REVIEWS ================= -->
        <section id="admin-section-reviews" class="admin-section">
            <div class="mb-5">
                <h2 class="font-serif text-2xl text-navy font-semibold">Reviews Moderation</h2>
                <p class="text-xs text-slate-400 mt-0.5">Review and moderate user reviews</p>
            </div>

            <div class="flex flex-wrap gap-2 mb-4">
                <button class="admin-tab active">All (1,842)</button>
                <button class="admin-tab">Published (1,798)</button>
                <button class="admin-tab">Flagged (24)</button>
                <button class="admin-tab">Pending (20)</button>
            </div>

            <div class="space-y-3">
                <div class="bg-white rounded-2xl border border-slate-100 p-4">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-full bg-accentOrange flex items-center justify-center text-white font-bold text-[10px] flex-shrink-0">DK</div>
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                <span class="text-xs font-bold text-navy">David Kariuki</span>
                                <span class="text-[9px] text-accentOrange"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></span>
                                <span class="text-[9px] text-slate-400">on Beachfront Villa</span>
                            </div>
                            <p class="text-[11px] text-slate-600 leading-relaxed">"Absolutely stunning property! The beach access was perfect and the host was incredibly responsive. Will definitely book again."</p>
                            <div class="flex items-center gap-3 mt-2">
                                <span class="text-[9px] text-slate-400">Dec 10, 2024</span>
                                <span class="status-badge status-active">Published</span>
                            </div>
                        </div>
                        <div class="flex gap-1 flex-shrink-0">
                            <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-accentBlue transition-colors" title="Edit"><i class="fas fa-pen text-[10px]"></i></button>
                            <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-red-50 hover:text-red-500 transition-colors" title="Delete"><i class="fas fa-trash text-[10px]"></i></button>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-red-200 p-4">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-full bg-red-500 flex items-center justify-center text-white font-bold text-[10px] flex-shrink-0">FK</div>
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                <span class="text-xs font-bold text-navy">Fred Kimani</span>
                                <span class="text-[9px] text-accentOrange"><i class="fas fa-star"></i><i class="fas fa-star"></i></span>
                                <span class="text-[9px] text-slate-400">on Luxury Safari Lodge</span>
                                <span class="text-[9px] text-red-500 font-bold bg-red-50 px-1.5 py-0.5 rounded"><i class="fas fa-flag mr-0.5"></i>Flagged</span>
                            </div>
                            <p class="text-[11px] text-slate-600 leading-relaxed">"This place was terrible. The photos are misleading and the staff was rude. I would NOT recommend this to anyone..."</p>
                            <div class="flex items-center gap-3 mt-2">
                                <span class="text-[9px] text-slate-400">Dec 9, 2024</span>
                                <span class="text-[9px] text-red-500">Reported 3 times</span>
                            </div>
                        </div>
                        <div class="flex gap-1 flex-shrink-0">
                            <button class="w-7 h-7 flex items-center justify-center rounded-md text-emerald-500 hover:bg-emerald-50 transition-colors" title="Approve"><i class="fas fa-check text-[10px]"></i></button>
                            <button class="w-7 h-7 flex items-center justify-center rounded-md text-red-500 hover:bg-red-50 transition-colors" title="Remove"><i class="fas fa-trash text-[10px]"></i></button>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-100 p-4">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-full bg-adminGreen flex items-center justify-center text-white font-bold text-[10px] flex-shrink-0">SW</div>
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                <span class="text-xs font-bold text-navy">Sarah Wanjiku</span>
                                <span class="text-[9px] text-accentOrange"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></span>
                                <span class="text-[9px] text-slate-400">on Prestige Realty</span>
                            </div>
                            <p class="text-[11px] text-slate-600 leading-relaxed">"Excellent service from start to finish. The agent was professional and found exactly what we were looking for."</p>
                            <div class="flex items-center gap-3 mt-2">
                                <span class="text-[9px] text-slate-400">Dec 8, 2024</span>
                                <span class="status-badge status-active">Published</span>
                            </div>
                        </div>
                        <div class="flex gap-1 flex-shrink-0">
                            <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-accentBlue transition-colors"><i class="fas fa-pen text-[10px]"></i></button>
                            <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-red-50 hover:text-red-500 transition-colors"><i class="fas fa-trash text-[10px]"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <!-- ================= CATEGORIES ================= -->
        <section id="admin-section-categories" class="admin-section">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
                <div>
                    <h2 class="font-serif text-2xl text-navy font-semibold">Categories</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Manage property categories and types</p>
                </div>
                <button onclick="showToast('Add category form opened')" class="bg-navy text-white text-xs font-bold px-4 py-2.5 rounded-lg hover:bg-accentBlue transition-colors flex items-center gap-2 self-start sm:self-center">
                    <i class="fas fa-plus"></i> Add Category
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white rounded-2xl border border-slate-100 p-4">
                    <h3 class="text-xs font-bold text-navy uppercase tracking-wide mb-3">For Rent</h3>
                    <div class="space-y-1">
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50">
                            <span class="text-xs text-slate-600">Houses</span>
                            <span class="text-[10px] font-bold text-navy">245</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50">
                            <span class="text-xs text-slate-600">Apartments</span>
                            <span class="text-[10px] font-bold text-navy">386</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50">
                            <span class="text-xs text-slate-600">Bedsitters</span>
                            <span class="text-[10px] font-bold text-navy">178</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50">
                            <span class="text-xs text-slate-600">Townhouses</span>
                            <span class="text-[10px] font-bold text-navy">112</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50">
                            <span class="text-xs text-slate-600">Villas</span>
                            <span class="text-[10px] font-bold text-navy">65</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-100 p-4">
                    <h3 class="text-xs font-bold text-navy uppercase tracking-wide mb-3">For Sale</h3>
                    <div class="space-y-1">
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50">
                            <span class="text-xs text-slate-600">Houses</span>
                            <span class="text-[10px] font-bold text-navy">198</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50">
                            <span class="text-xs text-slate-600">Apartments</span>
                            <span class="text-[10px] font-bold text-navy">156</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50">
                            <span class="text-xs text-slate-600">Land</span>
                            <span class="text-[10px] font-bold text-navy">389</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50">
                            <span class="text-xs text-slate-600">Townhouses</span>
                            <span class="text-[10px] font-bold text-navy">87</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50">
                            <span class="text-xs text-slate-600">Commercial</span>
                            <span class="text-[10px] font-bold text-navy">245</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-100 p-4">
                    <h3 class="text-xs font-bold text-navy uppercase tracking-wide mb-3">Short Stay / Airbnb</h3>
                    <div class="space-y-1">
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50">
                            <span class="text-xs text-slate-600">Beachfront Villas</span>
                            <span class="text-[10px] font-bold text-navy">234</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50">
                            <span class="text-xs text-slate-600">City Apartments</span>
                            <span class="text-[10px] font-bold text-navy">412</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50">
                            <span class="text-xs text-slate-600">Safari Lodges</span>
                            <span class="text-[10px] font-bold text-navy">87</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50">
                            <span class="text-xs text-slate-600">Holiday Homes</span>
                            <span class="text-[10px] font-bold text-navy">298</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50">
                            <span class="text-xs text-slate-600">Serviced Apartments</span>
                            <span class="text-[10px] font-bold text-navy">114</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-100 p-4">
                    <h3 class="text-xs font-bold text-navy uppercase tracking-wide mb-3">Commercial</h3>
                    <div class="space-y-1">
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50">
                            <span class="text-xs text-slate-600">Offices</span>
                            <span class="text-[10px] font-bold text-navy">89</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50">
                            <span class="text-xs text-slate-600">Shops</span>
                            <span class="text-[10px] font-bold text-navy">76</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50">
                            <span class="text-xs text-slate-600">Warehouses</span>
                            <span class="text-[10px] font-bold text-navy">45</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50">
                            <span class="text-xs text-slate-600">Industrial</span>
                            <span class="text-[10px] font-bold text-navy">35</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <!-- ================= LOCATIONS ================= -->
        <section id="admin-section-locations" class="admin-section">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
                <div>
                    <h2 class="font-serif text-2xl text-navy font-semibold">Locations</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Manage cities, neighborhoods, and regions</p>
                </div>
                <button onclick="showToast('Add location form opened')" class="bg-navy text-white text-xs font-bold px-4 py-2.5 rounded-lg hover:bg-accentBlue transition-colors flex items-center gap-2 self-start sm:self-center">
                    <i class="fas fa-plus"></i> Add Location
                </button>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
                <table class="admin-table w-full">
                    <thead>
                        <tr>
                            <th>City</th>
                            <th>Neighborhoods</th>
                            <th>Properties</th>
                            <th>Agencies</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="font-bold">Nairobi</td>
                            <td class="text-slate-500 text-[10px]">Westlands, Kilimani, Karen, Kileleshwa, Lavington, Runda…</td>
                            <td class="font-bold">1,245</td>
                            <td class="font-bold">72</td>
                            <td><span class="status-badge status-active">Active</span></td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-accentBlue transition-colors"><i class="fas fa-pen text-[10px]"></i></button>
                                    <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-red-50 hover:text-red-500 transition-colors"><i class="fas fa-trash text-[10px]"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-bold">Mombasa</td>
                            <td class="text-slate-500 text-[10px]">Diani, Nyali, Bamburi, Mtwapa, Watamu…</td>
                            <td class="font-bold">567</td>
                            <td class="font-bold">28</td>
                            <td><span class="status-badge status-active">Active</span></td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-accentBlue transition-colors"><i class="fas fa-pen text-[10px]"></i></button>
                                    <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-red-50 hover:text-red-500 transition-colors"><i class="fas fa-trash text-[10px]"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-bold">Kisumu</td>
                            <td class="text-slate-500 text-[10px]">Milimani, Nyalenda, Riat Hills, Mamboleo…</td>
                            <td class="font-bold">234</td>
                            <td class="font-bold">12</td>
                            <td><span class="status-badge status-active">Active</span></td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-accentBlue transition-colors"><i class="fas fa-pen text-[10px]"></i></button>
                                    <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-red-50 hover:text-red-500 transition-colors"><i class="fas fa-trash text-[10px]"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-bold">Nakuru</td>
                            <td class="text-slate-500 text-[10px]">Milimani, Section 58, Kiamunyi, Lanet…</td>
                            <td class="font-bold">189</td>
                            <td class="font-bold">8</td>
                            <td><span class="status-badge status-active">Active</span></td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-slate-100 hover:text-accentBlue transition-colors"><i class="fas fa-pen text-[10px]"></i></button>
                                    <button class="w-7 h-7 flex items-center justify-center rounded-md text-slate-400 hover:bg-red-50 hover:text-red-500 transition-colors"><i class="fas fa-trash text-[10px]"></i></button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>


        <!-- ================= REPORTS ================= -->
        <section id="admin-section-reports" class="admin-section">
            <div class="mb-5">
                <h2 class="font-serif text-2xl text-navy font-semibold">Reports & Analytics</h2>
                <p class="text-xs text-slate-400 mt-0.5">Platform performance and insights</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-5">
                <div class="bg-white rounded-2xl border border-slate-100 p-5">
                    <h3 class="font-serif text-lg text-navy font-semibold mb-4">User Growth</h3>
                    <div class="flex items-end gap-2 h-32">
                        <div class="flex-1 bg-accentBlue/20 rounded-t" style="height: 30%"></div>
                        <div class="flex-1 bg-accentBlue/20 rounded-t" style="height: 45%"></div>
                        <div class="flex-1 bg-accentBlue/20 rounded-t" style="height: 55%"></div>
                        <div class="flex-1 bg-accentBlue/20 rounded-t" style="height: 65%"></div>
                        <div class="flex-1 bg-accentBlue/20 rounded-t" style="height: 75%"></div>
                        <div class="flex-1 bg-accentBlue/20 rounded-t" style="height: 85%"></div>
                        <div class="flex-1 bg-accentBlue rounded-t" style="height: 100%"></div>
                    </div>
                    <div class="flex justify-between mt-2 text-[8px] text-slate-400">
                        <span>Jan</span><span>Feb</span><span>Mar</span><span>Apr</span><span>May</span><span>Jun</span><span>Jul</span>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-100 p-5">
                    <h3 class="font-serif text-lg text-navy font-semibold mb-4">Revenue by Category</h3>
                    <div class="space-y-3">
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[11px] font-semibold text-navy">Short Stay</span>
                                <span class="text-[10px] font-bold text-navy">KSh 3.8M</span>
                            </div>
                            <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-accentBlue rounded-full" style="width: 85%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[11px] font-semibold text-navy">Rentals</span>
                                <span class="text-[10px] font-bold text-navy">KSh 2.4M</span>
                            </div>
                            <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-adminGreen rounded-full" style="width: 55%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[11px] font-semibold text-navy">Sales</span>
                                <span class="text-[10px] font-bold text-navy">KSh 1.6M</span>
                            </div>
                            <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-accentOrange rounded-full" style="width: 35%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[11px] font-semibold text-navy">Agency Fees</span>
                                <span class="text-[10px] font-bold text-navy">KSh 0.6M</span>
                            </div>
                            <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-adminPurple rounded-full" style="width: 15%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-serif text-lg text-navy font-semibold">Export Reports</h3>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    <button onclick="showToast('Exporting users report')" class="p-3 rounded-xl border border-slate-200 hover:border-accentBlue hover:bg-accentBlue/5 transition-colors text-left group">
                        <i class="fas fa-users text-accentBlue text-lg mb-2"></i>
                        <p class="text-xs font-bold text-navy">Users Report</p>
                        <p class="text-[9px] text-slate-400">CSV · Last 30 days</p>
                    </button>
                    <button onclick="showToast('Exporting properties report')" class="p-3 rounded-xl border border-slate-200 hover:border-accentOrange hover:bg-accentOrange/5 transition-colors text-left group">
                        <i class="fas fa-building text-accentOrange text-lg mb-2"></i>
                        <p class="text-xs font-bold text-navy">Properties Report</p>
                        <p class="text-[9px] text-slate-400">CSV · All listings</p>
                    </button>
                    <button onclick="showToast('Exporting revenue report')" class="p-3 rounded-xl border border-slate-200 hover:border-adminGreen hover:bg-adminGreen/5 transition-colors text-left group">
                        <i class="fas fa-chart-line text-adminGreen text-lg mb-2"></i>
                        <p class="text-xs font-bold text-navy">Revenue Report</p>
                        <p class="text-[9px] text-slate-400">CSV · This quarter</p>
                    </button>
                    <button onclick="showToast('Exporting bookings report')" class="p-3 rounded-xl border border-slate-200 hover:border-adminPurple hover:bg-adminPurple/5 transition-colors text-left group">
                        <i class="fas fa-calendar-check text-adminPurple text-lg mb-2"></i>
                        <p class="text-xs font-bold text-navy">Bookings Report</p>
                        <p class="text-[9px] text-slate-400">CSV · All time</p>
                    </button>
                </div>
            </div>
        </section>


        <!-- ================= SETTINGS ================= -->
        <section id="admin-section-settings" class="admin-section">
            <div class="mb-5">
                <h2 class="font-serif text-2xl text-navy font-semibold">Platform Settings</h2>
                <p class="text-xs text-slate-400 mt-0.5">Configure platform-wide settings</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <!-- General settings -->
                <div class="bg-white rounded-2xl border border-slate-100 p-5">
                    <h3 class="font-serif text-lg text-navy font-semibold mb-4">General</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Platform Name</label>
                            <input type="text" value="HAVENTRA" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-accentBlue/30">
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Support Email</label>
                            <input type="email" value="support@haventra.com" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-accentBlue/30">
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Default Currency</label>
                            <select class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-accentBlue/30">
                                <option>KES — Kenyan Shilling</option>
                                <option>USD — US Dollar</option>
                                <option>EUR — Euro</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase tracking-wide font-bold text-slate-500 mb-1.5">Platform Fee (%)</label>
                            <input type="number" value="5" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-accentBlue/30">
                        </div>
                    </div>
                </div>

                <!-- Feature toggles -->
                <div class="bg-white rounded-2xl border border-slate-100 p-5">
                    <h3 class="font-serif text-lg text-navy font-semibold mb-4">Features</h3>
                    <div class="space-y-1">
                        <div class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-accentBlue/10 flex items-center justify-center">
                                    <i class="fas fa-building text-accentBlue text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-navy">Property Listings</p>
                                    <p class="text-[10px] text-slate-400">Allow new property submissions</p>
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
                                <div class="w-9 h-9 rounded-lg bg-adminGreen/10 flex items-center justify-center">
                                    <i class="fas fa-handshake text-adminGreen text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-navy">Agency Registration</p>
                                    <p class="text-[10px] text-slate-400">Allow new agencies to sign up</p>
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
                                <div class="w-9 h-9 rounded-lg bg-accentOrange/10 flex items-center justify-center">
                                    <i class="fas fa-star text-accentOrange text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-navy">Reviews</p>
                                    <p class="text-[10px] text-slate-400">Allow users to leave reviews</p>
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
                                <div class="w-9 h-9 rounded-lg bg-adminPurple/10 flex items-center justify-center">
                                    <i class="fas fa-shield-alt text-adminPurple text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-navy">Auto-approve Verified</p>
                                    <p class="text-[10px] text-slate-400">Auto-approve verified agency listings</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer">
                                <div class="w-10 h-5 bg-slate-200 rounded-full peer-checked:bg-accentBlue transition-colors"></div>
                                <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow peer-checked:translate-x-5 transition-transform"></div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Maintenance mode -->
                <div class="bg-white rounded-2xl border border-slate-100 p-5">
                    <h3 class="font-serif text-lg text-navy font-semibold mb-4">Maintenance</h3>
                    <div class="flex items-center justify-between p-3 rounded-xl bg-red-50 border border-red-200">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-red-500 flex items-center justify-center">
                                <i class="fas fa-wrench text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-navy">Maintenance Mode</p>
                                <p class="text-[10px] text-slate-500">Temporarily disable the platform</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer">
                            <div class="w-10 h-5 bg-slate-200 rounded-full peer-checked:bg-red-500 transition-colors"></div>
                            <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow peer-checked:translate-x-5 transition-transform"></div>
                        </label>
                    </div>
                    <p class="text-[10px] text-slate-400 mt-2">When enabled, users will see a maintenance page.</p>
                </div>

                <!-- Security -->
                <div class="bg-white rounded-2xl border border-slate-100 p-5">
                    <h3 class="font-serif text-lg text-navy font-semibold mb-4">Security</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 transition-colors">
                            <div>
                                <p class="text-xs font-semibold text-navy">Two-Factor Auth</p>
                                <p class="text-[10px] text-slate-400">Require 2FA for admin accounts</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" checked class="sr-only peer">
                                <div class="w-10 h-5 bg-slate-200 rounded-full peer-checked:bg-accentBlue transition-colors"></div>
                                <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow peer-checked:translate-x-5 transition-transform"></div>
                            </label>
                        </div>
                        <div class="flex items-center justify-between p-3 rounded-xl hover:bg-slate-50 transition-colors">
                            <div>
                                <p class="text-xs font-semibold text-navy">Email Verification</p>
                                <p class="text-[10px] text-slate-400">Require email verification for users</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" checked class="sr-only peer">
                                <div class="w-10 h-5 bg-slate-200 rounded-full peer-checked:bg-accentBlue transition-colors"></div>
                                <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow peer-checked:translate-x-5 transition-transform"></div>
                            </label>
                        </div>
                        <button class="w-full mt-2 px-4 py-2.5 border border-slate-200 text-slate-600 text-xs font-semibold rounded-lg hover:bg-slate-50 transition-colors">
                            <i class="fas fa-key mr-1.5"></i> Change Admin Password
                        </button>
                    </div>
                </div>
            </div>

            <div class="mt-4 flex justify-end">
                <button onclick="showToast('Settings saved successfully')" class="bg-navy text-white text-xs font-bold px-6 py-2.5 rounded-lg hover:bg-accentBlue transition-colors">
                    <i class="fas fa-save mr-1.5"></i> Save All Settings
                </button>
            </div>
        </section>


        <!-- ================= ACTIVITY LOG ================= -->
        <section id="admin-section-activity" class="admin-section">
            <div class="mb-5">
                <h2 class="font-serif text-2xl text-navy font-semibold">Activity Log</h2>
                <p class="text-xs text-slate-400 mt-0.5">Complete history of platform events</p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 p-4 md:p-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="admin-search flex items-center gap-2 px-3 py-2 w-full max-w-md">
                        <i class="fas fa-search text-slate-400 text-xs"></i>
                        <input type="text" placeholder="Search activity…" class="bg-transparent text-xs outline-none w-full text-slate-600 placeholder-slate-400">
                    </div>
                    <button onclick="showToast('Exporting activity log')" class="text-[10px] text-accentBlue font-semibold hover:underline hidden sm:block">Export Log</button>
                </div>

                <div class="space-y-3">
                    <div class="flex items-start gap-3 pb-3 border-b border-slate-50">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-user-plus text-emerald-600 text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-navy">User registered</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">David Kariuki (david.k@email.com) created a new client account</p>
                        </div>
                        <span class="text-[9px] text-slate-400 whitespace-nowrap">2m ago</span>
                    </div>
                    <div class="flex items-start gap-3 pb-3 border-b border-slate-50">
                        <div class="w-8 h-8 rounded-lg bg-accentBlue/10 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-check text-accentBlue text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-navy">Property approved</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">"Modern 3 Bed Villa" in Kilimani approved by admin</p>
                        </div>
                        <span class="text-[9px] text-slate-400 whitespace-nowrap">15m ago</span>
                    </div>
                    <div class="flex items-start gap-3 pb-3 border-b border-slate-50">
                        <div class="w-8 h-8 rounded-lg bg-accentOrange/10 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-wallet text-accentOrange text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-navy">Payout processed</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">KSh 125,000 paid to Prestige Realty Group via M-Pesa</p>
                        </div>
                        <span class="text-[9px] text-slate-400 whitespace-nowrap">1h ago</span>
                    </div>
                    <div class="flex items-start gap-3 pb-3 border-b border-slate-50">
                        <div class="w-8 h-8 rounded-lg bg-adminPurple/10 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-certificate text-adminPurple text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-navy">Agency verified</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">Coastal Homes Ltd verified and approved as a real estate agency</p>
                        </div>
                        <span class="text-[9px] text-slate-400 whitespace-nowrap">2h ago</span>
                    </div>
                    <div class="flex items-start gap-3 pb-3 border-b border-slate-50">
                        <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-ban text-red-500 text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-navy">User suspended</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">Fred Kimani suspended for violating platform terms</p>
                        </div>
                        <span class="text-[9px] text-slate-400 whitespace-nowrap">3h ago</span>
                    </div>
                    <div class="flex items-start gap-3 pb-3 border-b border-slate-50">
                        <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-flag text-amber-600 text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-navy">Listing reported</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">"Prime 1/4 Acre Plot" reported by 2 users for incorrect information</p>
                        </div>
                        <span class="text-[9px] text-slate-400 whitespace-nowrap">5h ago</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-cog text-slate-500 text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-navy">Settings updated</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">Platform fee changed from 4% to 5% by admin</p>
                        </div>
                        <span class="text-[9px] text-slate-400 whitespace-nowrap">1d ago</span>
                    </div>
                </div>
            </div>
        </section>

    </main>
</div>


<!-- ================= TOAST ================= -->
<div id="adminToast" class="fixed bottom-6 left-1/2 -translate-x-1/2 bg-navy text-white text-xs font-semibold px-5 py-3 rounded-xl shadow-2xl z-[5000] hidden items-center gap-2">
    <i class="fas fa-check-circle text-accentOrange"></i>
    <span id="adminToastMsg">Action completed</span>
</div>


<script>
/* ============================================================
   SIDEBAR
   ============================================================ */
function openSidebar() {
    document.getElementById('adminSidebar').classList.add('open');
    document.getElementById('sidebarOverlay').classList.add('active');
    document.body.style.overflow = 'hidden';
}
function closeSidebar() {
    document.getElementById('adminSidebar').classList.remove('open');
    document.getElementById('sidebarOverlay').classList.remove('active');
    document.body.style.overflow = '';
}

/* ============================================================
   SECTION SWITCHING
   ============================================================ */
const adminSections = {
    dashboard: { title: 'Dashboard', subtitle: 'Platform overview & key metrics' },
    users: { title: 'User Management', subtitle: 'Manage all platform users' },
    properties: { title: 'Property Management', subtitle: 'Review, approve, and manage listings' },
    agencies: { title: 'Agency Management', subtitle: 'Manage agencies and companies' },
    bookings: { title: 'Bookings Management', subtitle: 'Manage all reservations' },
    messages: { title: 'Support Messages', subtitle: 'User inquiries and complaints' },
    transactions: { title: 'Transactions', subtitle: 'All financial transactions' },
    payouts: { title: 'Payouts', subtitle: 'Withdrawal requests' },
    reviews: { title: 'Reviews Moderation', subtitle: 'Review and moderate user reviews' },
    categories: { title: 'Categories', subtitle: 'Property categories and types' },
    locations: { title: 'Locations', subtitle: 'Cities, neighborhoods, and regions' },
    reports: { title: 'Reports & Analytics', subtitle: 'Platform performance and insights' },
    settings: { title: 'Platform Settings', subtitle: 'Configure platform-wide settings' },
    activity: { title: 'Activity Log', subtitle: 'Complete history of platform events' },
};

function showAdminSection(name, navEl) {
    // Hide all sections
    document.querySelectorAll('.admin-section').forEach(s => s.classList.remove('active'));
    
    // Show target
    const target = document.getElementById('admin-section-' + name);
    if (target) target.classList.add('active');

    // Update nav active states
    document.querySelectorAll('.admin-nav').forEach(n => n.classList.remove('active'));
    if (navEl) navEl.classList.add('active');

    // Update page title
    const info = adminSections[name] || { title: name, subtitle: '' };
    document.getElementById('adminPageTitle').textContent = info.title;
    document.getElementById('adminPageSubtitle').textContent = info.subtitle;

    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });

    // Close sidebar on mobile
    if (window.innerWidth < 1024) closeSidebar();
}

/* ============================================================
   TOAST
   ============================================================ */
let toastTimeout;
function showToast(message) {
    const toast = document.getElementById('adminToast');
    const msg = document.getElementById('adminToastMsg');
    msg.textContent = message;
    toast.classList.remove('hidden');
    toast.classList.add('flex');
    
    clearTimeout(toastTimeout);
    toastTimeout = setTimeout(() => {
        toast.classList.add('hidden');
        toast.classList.remove('flex');
    }, 2500);
}

/* ============================================================
   TABLE SELECT ALL
   ============================================================ */
document.querySelectorAll('.admin-checkbox').forEach(checkbox => {
    // Individual checkboxes handled by browser default
});

/* ============================================================
   KEYBOARD SHORTCUT
   ============================================================ */
document.addEventListener('keydown', function(e) {
    // Cmd/Ctrl + K to focus search
    if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
        e.preventDefault();
        const searchInput = document.querySelector('.admin-search input');
        if (searchInput) searchInput.focus();
    }
});

/* ============================================================
   RESPONSIVE FIX
   ============================================================ */
window.addEventListener('resize', () => {
    if (window.innerWidth >= 1024) {
        document.getElementById('adminSidebar').classList.remove('open');
        document.getElementById('sidebarOverlay').classList.remove('active');
        document.body.style.overflow = '';
    }
});
</script>

</body>
</html>