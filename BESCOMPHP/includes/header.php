<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Default root path helper
if (!isset($pathToRoot)) {
    $pathToRoot = '../';
}

// Redirect unauthorized users
if (!isset($_SESSION['user'])) {
    header("Location: " . $pathToRoot . "frontend/login.php");
    exit();
}

if (!isset($activePage)) {
    $activePage = 'dashboard';
}
?>
<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BESCOM Meter Reader - <?php echo ucfirst($activePage); ?></title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <!-- Google Fonts: Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Leaflet Map CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        primary: '#0284c7',
                        primaryHover: '#0369a1',
                        accent: '#0ea5e9',
                        accentHover: '#0284c7',
                    }
                }
            }
        }
    </script>
    <style>
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        .active-nav {
            background-color: rgba(255, 255, 255, 0.15) !important;
            border-color: rgba(255, 255, 255, 0.25);
        }
    </style>
</head>
<body class="h-full bg-slate-50 text-slate-800 antialiased overflow-x-hidden flex flex-col">

    <!-- HEADER -->
    <header class="bg-primary text-white shadow-lg fixed top-0 left-0 w-full z-40 h-16 flex items-center">
        <div class="w-full px-4 flex items-center justify-between">
            
            <!-- Left Branding: Downloaded Assetplus Logo -->
            <div class="flex items-center gap-4">
                <button id="menu-btn" class="lg:hidden p-2 text-2xl hover:bg-primaryHover rounded-lg transition">
                    <i class="fas fa-bars"></i>
                </button>
                <div onclick="window.location.href='<?php echo $pathToRoot; ?>reports/supervisor.php'" class="bg-white px-2 py-1 rounded border border-slate-200 cursor-pointer shadow-sm hover:scale-[1.01] transition w-[160px] h-[48px] flex items-center justify-center">
                    <img src="<?php echo $pathToRoot; ?>assets/images/assetplus.png" alt="Assetplus Consulting" class="max-h-full max-w-full object-contain">
                </div>
            </div>

            <!-- Desktop Navigation Links -->
            <nav class="hidden lg:flex items-center gap-1 font-semibold text-xs tracking-wider">
                <a href="<?php echo $pathToRoot; ?>reports/supervisor.php" class="nav-link px-3 py-2.5 rounded-lg hover:bg-white/10 transition <?php echo $activePage === 'dashboard' ? 'active-nav' : ''; ?>">DASHBOARD</a>
                <a href="<?php echo $pathToRoot; ?>frontend/live-tracking.php" class="nav-link px-3 py-2.5 rounded-lg hover:bg-white/10 transition <?php echo $activePage === 'live-tracking' ? 'active-nav' : ''; ?>">MR LIVE TRACKING</a>
                <a href="<?php echo $pathToRoot; ?>meter-reader/feild-output.php" class="nav-link px-3 py-2.5 rounded-lg hover:bg-white/10 transition <?php echo $activePage === 'field-output' ? 'active-nav' : ''; ?>">FIELD OUTPUT</a>
                <a href="<?php echo $pathToRoot; ?>meter-reader/meter-readers.php" class="nav-link px-3 py-2.5 rounded-lg hover:bg-white/10 transition <?php echo $activePage === 'meter-readers' ? 'active-nav' : ''; ?>">METER READERS</a>
                <a href="<?php echo $pathToRoot; ?>meter-reader/offline-output-dashboard.php" class="nav-link px-3 py-2.5 rounded-lg hover:bg-white/10 transition <?php echo $activePage === 'offline-output' ? 'active-nav' : ''; ?>">OFFLINE FIELD OUTPUT</a>
                
                <!-- Masters Dropdown -->
                <div class="relative dropdown-container">
                    <button class="dropdown-trigger px-3 py-2.5 rounded-lg hover:bg-white/10 transition flex items-center gap-1 <?php echo in_array($activePage, ['master-reader', 'master-consumer']) ? 'active-nav' : ''; ?>">
                        MASTERS <i class="fas fa-chevron-down text-[10px] transition-transform duration-200"></i>
                    </button>
                    <div class="dropdown-menu absolute hidden right-0 mt-2 w-52 bg-white text-slate-800 rounded-xl shadow-xl border border-slate-100 py-1.5 z-50">
                        <a href="<?php echo $pathToRoot; ?>masters/meterreader.php" class="block w-full text-left px-4 py-2.5 text-xs font-semibold hover:bg-accent hover:text-white transition">Meter Reader Master</a>
                        <a href="<?php echo $pathToRoot; ?>masters/consumer.php" class="block w-full text-left px-4 py-2.5 text-xs font-semibold hover:bg-accent hover:text-white transition">Consumer Master</a>
                    </div>
                </div>

                <!-- Reports Dropdown -->
                <div class="relative dropdown-container">
                    <button class="dropdown-trigger px-3 py-2.5 rounded-lg hover:bg-white/10 transition flex items-center gap-1 <?php echo in_array($activePage, ['account-journey', 'active-inactive-status']) ? 'active-nav' : ''; ?>">
                        REPORTS <i class="fas fa-chevron-down text-[10px] transition-transform duration-200"></i>
                    </button>
                    <div class="dropdown-menu absolute hidden right-0 mt-2 w-52 bg-white text-slate-800 rounded-xl shadow-xl border border-slate-100 py-1.5 z-50">
                        <a href="<?php echo $pathToRoot; ?>reports/account-id-journey.php" class="block w-full text-left px-4 py-2.5 text-xs font-semibold hover:bg-accent hover:text-white transition">Account Id Journey</a>
                        <a href="<?php echo $pathToRoot; ?>reports/active.php" class="block w-full text-left px-4 py-2.5 text-xs font-semibold hover:bg-accent hover:text-white transition">Active/Inactive MR List</a>
                    </div>
                </div>
            </nav>

            <!-- Right side details -->
            <div class="flex items-center gap-4">
                <!-- Download APK button -->
                <a href="https://api.vidyut-suvidha.in/images/apc_bulk_ocr_09_10_v1.apk" download class="hidden sm:flex items-center gap-1.5 bg-white/10 hover:bg-white/20 px-3 py-1.5 rounded-lg border border-white/10 text-xs font-bold transition">
                    <i class="fas fa-cloud-download-alt text-sm"></i> Download APK
                </a>

                <!-- Supervisor Profile Dropdown -->
                <div class="relative dropdown-container">
                    <button class="dropdown-trigger bg-white/10 px-3 py-1.5 rounded-lg border border-white/10 hover:bg-white/20 transition flex items-center gap-1.5 text-xs font-bold">
                        <i class="fas fa-user-circle text-sm"></i>
                        <span class="uppercase">Supervisor</span>
                        <i class="fas fa-chevron-down text-[10px]"></i>
                    </button>
                    <div class="dropdown-menu absolute hidden right-0 mt-2 w-48 bg-white text-slate-800 rounded-xl shadow-xl border border-slate-100 py-1.5 z-50">
                        <a href="<?php echo $pathToRoot; ?>frontend/logout.php" class="block w-full text-left px-4 py-2.5 text-xs font-semibold hover:bg-rose-50 text-rose-600 transition flex items-center gap-2">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                </div>

                <!-- Circular BESCOM Logo Image -->
                <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center p-0.5 border border-white/20 shadow-inner overflow-hidden select-none">
                    <img src="<?php echo $pathToRoot; ?>assets/images/bescom.png" alt="BESCOM" class="w-full h-full object-contain">
                </div>
            </div>

        </div>
    </header>

    <!-- MOBILE SIDE NAVIGATION SIDEBAR -->
    <div id="mobile-sidebar" class="fixed inset-0 z-[5000] pointer-events-none transition-all duration-300 opacity-0">
        <!-- Backdrop -->
        <div id="sidebar-backdrop" class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm cursor-pointer"></div>
        <!-- Menu container -->
        <div id="sidebar-menu" class="absolute top-0 left-0 bottom-0 w-72 bg-white shadow-2xl flex flex-col p-6 space-y-4 transform -translate-x-full transition-transform duration-300 ease-in-out pointer-events-auto">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center gap-2">
                    <div class="bg-primary rounded-xl p-2 text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <span class="font-bold text-primary text-lg">BESCOM Suite</span>
                </div>
                <button id="close-sidebar" class="p-1.5 text-slate-400 hover:text-slate-600 text-lg"><i class="fas fa-times"></i></button>
            </div>

            <div class="flex-1 overflow-y-auto space-y-2 py-2">
                <a href="<?php echo $pathToRoot; ?>reports/supervisor.php" class="block w-full text-left px-4 py-3 rounded-xl font-semibold text-slate-600 hover:bg-slate-50 transition <?php echo $activePage === 'dashboard' ? 'bg-primary/10 text-primary' : ''; ?>">DASHBOARD</a>
                <a href="<?php echo $pathToRoot; ?>frontend/live-tracking.php" class="block w-full text-left px-4 py-3 rounded-xl font-semibold text-slate-600 hover:bg-slate-50 transition <?php echo $activePage === 'live-tracking' ? 'bg-primary/10 text-primary' : ''; ?>">MR LIVE TRACKING</a>
                <a href="<?php echo $pathToRoot; ?>meter-reader/feild-output.php" class="block w-full text-left px-4 py-3 rounded-xl font-semibold text-slate-600 hover:bg-slate-50 transition <?php echo $activePage === 'field-output' ? 'bg-primary/10 text-primary' : ''; ?>">FIELD OUTPUT</a>
                <a href="<?php echo $pathToRoot; ?>meter-reader/meter-readers.php" class="block w-full text-left px-4 py-3 rounded-xl font-semibold text-slate-600 hover:bg-slate-50 transition <?php echo $activePage === 'meter-readers' ? 'bg-primary/10 text-primary' : ''; ?>">METER READERS</a>
                <a href="<?php echo $pathToRoot; ?>meter-reader/offline-output-dashboard.php" class="block w-full text-left px-4 py-3 rounded-xl font-semibold text-slate-600 hover:bg-slate-50 transition <?php echo $activePage === 'offline-output' ? 'bg-primary/10 text-primary' : ''; ?>">OFFLINE FIELD OUTPUT</a>
                
                <div class="border-t border-slate-100 my-2 pt-2"></div>
                <p class="text-xs font-bold text-slate-400 px-4 uppercase tracking-wider mb-1">Masters</p>
                <a href="<?php echo $pathToRoot; ?>masters/meterreader.php" class="block w-full text-left px-4 py-3 rounded-xl font-semibold text-slate-600 hover:bg-slate-50 transition <?php echo $activePage === 'master-reader' ? 'bg-primary/10 text-primary' : ''; ?>">Meter Reader Master</a>
                <a href="<?php echo $pathToRoot; ?>masters/consumer.php" class="block w-full text-left px-4 py-3 rounded-xl font-semibold text-slate-600 hover:bg-slate-50 transition <?php echo $activePage === 'master-consumer' ? 'bg-primary/10 text-primary' : ''; ?>">Consumer Master</a>
                
                <div class="border-t border-slate-100 my-2 pt-2"></div>
                <p class="text-xs font-bold text-slate-400 px-4 uppercase tracking-wider mb-1">Reports</p>
                <a href="<?php echo $pathToRoot; ?>reports/account-id-journey.php" class="block w-full text-left px-4 py-3 rounded-xl font-semibold text-slate-600 hover:bg-slate-50 transition <?php echo $activePage === 'account-journey' ? 'bg-primary/10 text-primary' : ''; ?>">Account Id Journey</a>
                <a href="<?php echo $pathToRoot; ?>reports/active.php" class="block w-full text-left px-4 py-3 rounded-xl font-semibold text-slate-600 hover:bg-slate-50 transition <?php echo $activePage === 'active-inactive-status' ? 'bg-primary/10 text-primary' : ''; ?>">Active/Inactive MR List</a>
            </div>

            <div class="border-t border-slate-100 pt-4 space-y-2">
                <a href="<?php echo $pathToRoot; ?>frontend/logout.php" class="block w-full text-left px-4 py-2.5 rounded-xl font-semibold text-rose-600 hover:bg-rose-50 transition flex items-center gap-2">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </div>

    <!-- MAIN VIEWPORT CONTAINER -->
    <main class="flex-1 mt-16 p-4 md:p-6 overflow-y-auto">
