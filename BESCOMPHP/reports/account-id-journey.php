<?php
$pathToRoot = '../';
$activePage = 'account-journey';
include $pathToRoot . 'includes/header.php';
?>

<!-- Account ID Journey Content -->
<div class="space-y-6">

    <!-- Sub-header banner -->
    <div class="bg-primary/95 text-white font-bold text-center py-4 rounded-xl shadow uppercase tracking-wide">
        <div class="text-sm md:text-base">Bangalore Electricity Supply Company Limited (BESCOM)</div>
        <div class="text-xs md:text-sm mt-1 font-semibold text-white/90">Meter Reading Dashboard (ACCOUNT ID JOURNEY)</div>
    </div>

    <!-- Filters container (matches gray box in screenshot) -->
    <div class="bg-slate-100/90 border border-slate-200 rounded-xl p-5 shadow-sm space-y-4">
        <form id="journey-filter-form" class="space-y-4">
            <!-- Row 1 -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">From Date</label>
                    <input type="date" id="from-date" value="2026-06-22" class="w-full border border-slate-300 rounded-lg px-3 py-1.5 text-xs focus:ring-1 focus:ring-primary focus:border-primary focus:outline-none bg-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">To Date</label>
                    <input type="date" id="to-date" value="2026-06-22" class="w-full border border-slate-300 rounded-lg px-3 py-1.5 text-xs focus:ring-1 focus:ring-primary focus:border-primary focus:outline-none bg-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">OCR Status</label>
                    <select id="ocr-status-select" class="w-full border border-slate-300 rounded-lg px-3 py-1.5 text-xs focus:ring-1 focus:ring-primary focus:border-primary focus:outline-none bg-white">
                        <option value="All">All</option>
                        <option value="SUCCESS">SUCCESS</option>
                        <option value="FAILED">FAILED</option>
                    </select>
                </div>
            </div>

            <!-- Row 2 -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Account ID</label>
                    <input type="text" id="account-id-input" placeholder="Enter Account ID" class="w-full border border-slate-300 rounded-lg px-3 py-1.5 text-xs focus:ring-1 focus:ring-primary focus:border-primary focus:outline-none bg-white">
                </div>
            </div>

            <!-- Row 3: Action Buttons inside filter box -->
            <div class="flex justify-end gap-2.5 pt-2 border-t border-slate-200/50">
                <button type="button" onclick="resetFilters()" class="bg-slate-400 hover:bg-slate-500 text-white px-4 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1.5 transition shadow-sm">
                    <i class="fas fa-sync-alt"></i> Reset Filters
                </button>
                <button type="button" onclick="applyFilters()" class="bg-primary hover:bg-primaryHover text-white px-4 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1.5 transition shadow-sm">
                    <i class="fas fa-filter"></i> Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Action buttons bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <!-- Left Buttons -->
        <div class="flex flex-wrap items-center gap-2">
            <button onclick="alert('Exported to Excel')" class="bg-primary hover:bg-primaryHover text-white px-3.5 py-1.5 rounded text-xs font-bold flex items-center gap-1.5 shadow-sm transition">
                <i class="fas fa-file-excel"></i> Excel
            </button>
            <button onclick="alert('Exported to PDF')" class="bg-primary hover:bg-primaryHover text-white px-3.5 py-1.5 rounded text-xs font-bold flex items-center gap-1.5 shadow-sm transition">
                <i class="fas fa-file-pdf"></i> PDF
            </button>
            <button onclick="alert('Exported to CSV')" class="bg-primary hover:bg-primaryHover text-white px-3.5 py-1.5 rounded text-xs font-bold flex items-center gap-1.5 shadow-sm transition">
                <i class="fas fa-file-csv"></i> CSV
            </button>
            <button onclick="refreshData()" class="bg-primary hover:bg-primaryHover text-white px-3.5 py-1.5 rounded text-xs font-bold flex items-center gap-1.5 shadow-sm transition">
                <i class="fas fa-redo"></i> Refresh
            </button>
            <button onclick="window.history.back()" class="bg-primary hover:bg-primaryHover text-white px-3.5 py-1.5 rounded text-xs font-bold flex items-center gap-1.5 shadow-sm transition">
                <i class="fas fa-arrow-left"></i> Back
            </button>
        </div>

        <!-- Right Search In Table -->
        <div class="relative w-full sm:w-64">
            <input type="text" id="table-search-input" onkeyup="searchTable()" placeholder="Search in table..." class="w-full border border-slate-300 rounded-lg pl-3 pr-8 py-1.5 text-xs focus:ring-1 focus:ring-primary focus:border-primary focus:outline-none bg-white">
            <i class="fas fa-search absolute right-2.5 top-2.5 text-slate-400 text-xs"></i>
        </div>
    </div>

    <!-- Table Container (matches 13 columns with grid layout) -->
    <div class="bg-white rounded-xl shadow-md border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-center border-collapse border border-slate-300 min-w-[1500px]">
                <thead>
                    <tr class="bg-slate-100 text-slate-700 text-xs font-bold border-b border-slate-300">
                        <th class="py-3 px-2 border border-slate-300 text-rose-800">Sno.</th>
                        <th class="py-3 px-2 border border-slate-300 text-rose-800">Date</th>
                        <th class="py-3 px-2 border border-slate-300 text-rose-800">Time</th>
                        <th class="py-3 px-2 border border-slate-300 text-rose-800">Account ID</th>
                        <th class="py-3 px-2 border border-slate-300 text-rose-800">Meter Image</th>
                        <th class="py-3 px-2 border border-slate-300 text-rose-800">Image Path</th>
                        <th class="py-3 px-2 border border-slate-300 text-rose-800">Meter Number</th>
                        <th class="py-3 px-2 border border-slate-300 text-rose-800">Meter Reading</th>
                        <th class="py-3 px-2 border border-slate-300 text-rose-800">Units</th>
                        <th class="py-3 px-2 border border-slate-300 text-rose-800">Meter Model</th>
                        <th class="py-3 px-2 border border-slate-300 text-rose-800">Latitude Longitude</th>
                        <th class="py-3 px-2 border border-slate-300 text-rose-800">Address</th>
                        <th class="py-3 px-2 border border-slate-300 text-rose-800">OCR Status</th>
                    </tr>
                </thead>
                <tbody id="table-data-body" class="divide-y divide-slate-200 text-xs font-semibold text-slate-700 bg-white">
                    <!-- Empty State placeholder -->
                    <tr id="empty-state-row">
                        <td colspan="13" class="py-12 text-center text-slate-400 font-bold text-sm bg-slate-50 border border-slate-300">
                            No data available
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- DIGITAL METER SNAPSHOT SIMULATOR MODAL -->
<div id="image-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[2000] hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden border border-slate-100 relative">
        <div class="p-4 bg-primary text-white font-bold flex justify-between items-center">
            <span id="modal-title-text">Meter Display Capture</span>
            <button onclick="closeMeterImageModal()" class="text-white hover:text-slate-200 text-xl"><i class="fas fa-times"></i></button>
        </div>
        <div class="p-6 flex flex-col items-center justify-center bg-slate-950 text-white space-y-4">
            <div class="w-72 h-48 bg-slate-900 border-4 border-slate-700 rounded-xl shadow-inner relative p-4 flex flex-col justify-between font-mono">
                <div class="flex justify-between items-center text-[10px] text-slate-500">
                    <span>METER CAPTURE</span>
                    <span>BESCOM NETWORK</span>
                </div>
                <div class="bg-emerald-950 border border-emerald-900/50 rounded p-3 text-emerald-400 flex flex-col justify-center items-end relative shadow-inner">
                    <div id="modal-label" class="text-[9px] text-emerald-600/80 absolute top-1 left-2">ACTIVE KWH</div>
                    <div id="modal-reading-val" class="text-3xl font-bold tracking-widest">04150.2</div>
                    <div id="modal-unit" class="text-[9px] text-emerald-600/80">kWh</div>
                </div>
                <div class="flex justify-between items-center text-[9px] text-slate-600">
                    <span id="modal-serial">No. GEN7829103</span>
                    <span>50Hz 240V</span>
                </div>
            </div>
            <p class="text-xs text-slate-400 text-center font-sans">Snapshot simulated extraction records.</p>
        </div>
    </div>
</div>

<script>
    function openMeterImageModal(val, serial) {
        const modal = document.getElementById('image-modal');
        const readingVal = document.getElementById('modal-reading-val');
        const serialNo = document.getElementById('modal-serial');

        serialNo.textContent = "No. " + serial;
        readingVal.textContent = val;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeMeterImageModal() {
        const modal = document.getElementById('image-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function applyFilters() {
        const ocrFilter = document.getElementById('ocr-status-select').value;
        const accFilter = document.getElementById('account-id-input').value.trim();

        const rows = document.querySelectorAll('.data-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const ocr = row.getAttribute('data-ocr');
            const acc = row.getAttribute('data-acc');

            let matches = true;

            if (ocrFilter !== 'All' && ocr !== ocrFilter) matches = false;
            if (accFilter !== '' && !acc.includes(accFilter)) matches = false;

            if (matches) {
                row.classList.remove('hidden');
                visibleCount++;
            } else {
                row.classList.add('hidden');
            }
        });

        const emptyState = document.getElementById('empty-state-row');
        if (visibleCount === 0) {
            emptyState.classList.remove('hidden');
        } else {
            emptyState.classList.add('hidden');
        }
    }

    function resetFilters() {
        document.getElementById('journey-filter-form').reset();
        const rows = document.querySelectorAll('.data-row');
        rows.forEach(row => row.classList.remove('hidden'));
        document.getElementById('empty-state-row').classList.add('hidden');
    }

    function refreshData() {
        resetFilters();
        alert("Data refreshed!");
    }

    function searchTable() {
        const query = document.getElementById('table-search-input').value.toLowerCase();
        const rows = document.querySelectorAll('.data-row');
        
        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            if (text.includes(query)) {
                row.classList.remove('hidden');
            } else {
                row.classList.add('hidden');
            }
        });
    }
</script>

<?php include $pathToRoot . 'includes/footer.php'; ?>
