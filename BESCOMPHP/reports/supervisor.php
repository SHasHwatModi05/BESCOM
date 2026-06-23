<?php
$pathToRoot = '../';
$activePage = 'dashboard';
include $pathToRoot . 'includes/header.php';
?>

<!-- Supervisor Dashboard View -->
<div class="space-y-6">
    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-primary to-primaryHover p-6 rounded-2xl shadow-xl text-white flex flex-col md:flex-row items-start md:items-center justify-between gap-4 border border-primary/20">
        <div class="space-y-1">
            <h1 class="text-2xl md:text-3xl font-bold tracking-tight">Supervisor Dashboard</h1>
            <p class="text-white/80 text-sm">Real-time meter reading progress and status overview.</p>
        </div>
        <div class="flex items-center gap-2 bg-white/10 px-4 py-2 rounded-xl border border-white/10 backdrop-blur-sm">
            <i class="fas fa-calendar-alt text-white/80"></i>
            <input type="date" value="2026-06-22" class="bg-transparent border-none text-white focus:outline-none font-semibold text-sm">
        </div>
    </div>

    <!-- Map Panel -->
    <div class="bg-white rounded-2xl shadow-md border border-slate-100 overflow-hidden">
        <div class="bg-primary/5 px-6 py-4 border-b border-slate-100 flex justify-between items-center">
            <div class="flex items-center gap-2.5">
                <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></div>
                <h3 class="font-bold text-slate-800 text-lg">Active Reading Areas</h3>
            </div>
            <a href="../frontend/live-tracking.php" class="text-primary hover:text-primaryHover font-semibold text-sm flex items-center gap-1.5 transition">
                <i class="fas fa-expand-arrows-alt"></i> Enter Map View
            </a>
        </div>
        <div class="p-4">
            <div id="dashboard-map" class="h-[300px] w-full rounded-xl border border-slate-200"></div>
        </div>
    </div>

    <!-- Stats Columns -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Reader Status Panel -->
        <div class="bg-white rounded-2xl shadow-md border border-slate-100 flex flex-col overflow-hidden">
            <div class="bg-primary/5 px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                <h3 class="font-bold text-slate-800 text-base">Reader Status</h3>
                <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-2.5 py-1 rounded-full">Today</span>
            </div>
            <div class="flex-1 p-6 flex items-center justify-center">
                <div class="grid grid-cols-2 gap-12 text-center w-full max-w-sm">
                    <div class="space-y-1">
                        <div class="text-5xl font-black text-emerald-500">0</div>
                        <div class="text-sm font-semibold text-slate-500">Active Readers</div>
                    </div>
                    <div class="space-y-1 border-l border-slate-100">
                        <div class="text-5xl font-black text-rose-500">0</div>
                        <div class="text-sm font-semibold text-slate-500">Inactive Readers</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Meter Reads Today Line Chart -->
        <div class="bg-white rounded-2xl shadow-md border border-slate-100 flex flex-col overflow-hidden">
            <div class="bg-primary/5 px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                <h3 class="font-bold text-slate-800 text-base">Meter Reads Done Today</h3>
                <button onclick="openChartModal()" class="text-primary hover:text-primaryHover hover:scale-105 transition duration-150">
                    <i class="fas fa-expand text-sm"></i>
                </button>
            </div>
            <div class="p-6 h-64 flex-1">
                <canvas id="meterReadsChart"></canvas>
            </div>
        </div>

    </div>
</div>

<!-- CHART FULLSCREEN OVERLAY MODAL -->
<div id="chart-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[2000] hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] md:h-[500px] overflow-hidden border border-slate-100 flex flex-col">
        <div class="p-4 bg-primary text-white font-bold flex justify-between items-center">
            <span>Meter Reads Done on 22 Jun 2026</span>
            <button onclick="closeChartModal()" class="text-white hover:text-slate-200 text-xl"><i class="fas fa-times"></i></button>
        </div>
        <div class="p-6 flex-1 bg-white relative min-h-[250px]">
            <canvas id="fullscreenChart"></canvas>
        </div>
    </div>
</div>

<!-- JS initialization for dashboard maps and charts -->
<script>
    let dashboardMapInstance;
    let dashboardChartInstance;
    let fullscreenChartInstance;

    const kalaburagiLatLng = [17.3297, 76.8376];

    function initDashboardMap() {
        if (dashboardMapInstance) return;
        
        dashboardMapInstance = L.map('dashboard-map', {
            center: kalaburagiLatLng,
            zoom: 12,
            zoomControl: true,
            attributionControl: false
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(dashboardMapInstance);
    }

    function initDashboardChart() {
        const ctx = document.getElementById('meterReadsChart').getContext('2d');
        const data = {
            labels: ['6-9 AM', '9-12 PM', '12-3 PM', '3-6 PM', '6+ PM'],
            datasets: [{
                label: 'Meter Reads Captured',
                data: [0, 0, 0, 0, 0],
                backgroundColor: '#14b8a6',
                borderColor: '#14b8a6',
                borderWidth: 2,
                tension: 0.4,
                fill: false
            }]
        };
        dashboardChartInstance = new Chart(ctx, {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    function openChartModal() {
        const modal = document.getElementById('chart-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        setTimeout(() => {
            const ctx = document.getElementById('fullscreenChart').getContext('2d');
            const data = {
                labels: ['6-9 AM', '9-12 PM', '12-3 PM', '3-6 PM', '6+ PM'],
                datasets: [{
                    label: 'Reads Frequency',
                    data: [0, 0, 0, 0, 0],
                    backgroundColor: 'rgba(2, 132, 199, 0.8)',
                    borderColor: '#0284c7',
                    borderWidth: 2
                }]
            };
            if (fullscreenChartInstance) { fullscreenChartInstance.destroy(); }
            fullscreenChartInstance = new Chart(ctx, {
                type: 'bar',
                data: data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        }, 100);
    }

    function closeChartModal() {
        const modal = document.getElementById('chart-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Run resource inits on load
    window.addEventListener('load', () => {
        initDashboardMap();
        initDashboardChart();
    });
</script>

<?php include $pathToRoot . 'includes/footer.php'; ?>
