<?php
$pathToRoot = '../';
$activePage = 'live-tracking';
include $pathToRoot . 'includes/header.php';
?>

<!-- Live GPS Tracking Map Panel -->
<div class="h-[calc(100vh-88px)] relative -m-4 md:-m-6 overflow-hidden">
    <!-- Map Canvas -->
    <div id="live-map" class="w-full h-full"></div>

    <!-- Floating Sidebar Control -->
    <div class="absolute bottom-4 left-4 right-4 sm:top-4 sm:bottom-auto sm:left-auto sm:right-4 z-[1000] sm:w-80 bg-white/95 backdrop-blur-md shadow-2xl rounded-2xl border border-slate-100 max-h-[45vh] sm:max-h-[calc(100vh-140px)] overflow-y-auto p-4 flex flex-col gap-4">
        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
            <span class="font-bold text-slate-800">Meter Reader Live List</span>
            <span class="bg-emerald-100 text-emerald-800 text-xs px-2.5 py-0.5 rounded-full font-bold">0 Active</span>
        </div>

        <!-- Filters inside panel -->
        <div class="space-y-2">
            <label class="text-xs font-semibold text-slate-500">Select Date</label>
            <input type="date" value="2026-06-22" class="w-full border border-slate-200 rounded-xl px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-accent/20 focus:border-accent">
        </div>

        <div class="space-y-1.5 flex-1 overflow-y-auto">
            <label class="text-xs font-semibold text-slate-500 block mb-1">Select Reader to Locate</label>
            <div class="text-center py-6 text-slate-400 font-bold text-xs bg-slate-50 rounded-xl border border-dashed border-slate-200">
                No active readers found
            </div>
        </div>
    </div>
</div>

<script>
    let liveMapInstance;
    let mapMarkers = {};
    const kalaburagiLatLng = [17.3297, 76.8376];

    function initLiveMap() {
        if (liveMapInstance) return;

        liveMapInstance = L.map('live-map', {
            center: kalaburagiLatLng,
            zoom: 13,
            zoomControl: true,
            attributionControl: false
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(liveMapInstance);

        mapMarkers = {};
    }

    function centerMapOn(lat, lng, name) {
        if (liveMapInstance) {
            liveMapInstance.setView([lat, lng], 15, { animate: true, duration: 1 });
            const marker = mapMarkers[name];
            if (marker) {
                setTimeout(() => {
                    marker.openPopup();
                }, 800);
            }
        }
    }

    window.addEventListener('load', () => {
        initLiveMap();
    });
</script>

<?php include $pathToRoot . 'includes/footer.php'; ?>
