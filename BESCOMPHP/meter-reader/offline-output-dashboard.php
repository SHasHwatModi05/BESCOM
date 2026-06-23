<?php
$pathToRoot = '../';
$activePage = 'offline-output';
include $pathToRoot . 'includes/header.php';
?>

<!-- Offline Field Output Content -->
<div class="space-y-6">

    <!-- Filters container (matches gray box in screenshot) -->
    <div class="bg-slate-100/90 border border-slate-200 rounded-xl p-5 shadow-sm space-y-4">
        <form id="offline-filter-form" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">From Date - To Date</label>
                    <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                        <div class="relative flex-1">
                            <input type="date" id="from-date" value="2026-06-22" class="w-full border border-slate-300 rounded-lg px-3 py-1.5 text-xs focus:ring-1 focus:ring-primary focus:border-primary focus:outline-none bg-white">
                        </div>
                        <span class="text-slate-400 text-xs text-center">to</span>
                        <div class="relative flex-1">
                            <input type="date" id="to-date" value="2026-06-22" class="w-full border border-slate-300 rounded-lg px-3 py-1.5 text-xs focus:ring-1 focus:ring-primary focus:border-primary focus:outline-none bg-white">
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Meter Reader Id</label>
                    <select id="mr-id-select" class="w-full border border-slate-300 rounded-lg px-3 py-1.5 text-xs focus:ring-1 focus:ring-primary focus:border-primary focus:outline-none bg-white">
                        <option value="All">All</option>
                        <option value="MR098">MR098</option>
                        <option value="MR104">MR104</option>
                        <option value="MR012">MR012</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Account ID</label>
                    <input type="text" id="account-id-input" placeholder="Enter Account ID" class="w-full border border-slate-300 rounded-lg px-3 py-1.5 text-xs focus:ring-1 focus:ring-primary focus:border-primary focus:outline-none bg-white">
                </div>
            </div>

            <!-- Action Buttons inside filter box -->
            <div class="flex justify-end gap-2.5 pt-2 border-t border-slate-200/50">
                <button type="button" onclick="resetFilters()" class="bg-slate-400 hover:bg-slate-500 text-white px-4 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1.5 transition shadow-sm">
                    Reset
                </button>
                <button type="button" onclick="applyFilters()" class="bg-primary hover:bg-primaryHover text-white px-4 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1.5 transition shadow-sm">
                    Apply
                </button>
            </div>
        </form>
    </div>

    <!-- Action buttons & counters bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <!-- Left Buttons & Badges -->
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

            <!-- Badges -->
            <span class="bg-white border border-slate-200 text-slate-800 text-[10px] font-bold px-2.5 py-1.5 rounded shadow-sm">
                Total Count: <strong id="total-count-badge" class="text-primary">0</strong>
            </span>
            <span class="bg-white border border-slate-200 text-slate-800 text-[10px] font-bold px-2.5 py-1.5 rounded shadow-sm">
                Total OCR Count: <strong id="ocr-count-badge" class="text-sky-600">0</strong>
            </span>
            <span class="bg-white border border-slate-200 text-slate-800 text-[10px] font-bold px-2.5 py-1.5 rounded shadow-sm">
                OCR Corrected Count: <strong class="text-rose-600">0</strong>
            </span>
        </div>

        <!-- Right rows per page selection -->
        <div class="flex items-center gap-2">
            <span class="text-xs font-bold text-slate-600">Rows per page:</span>
            <select class="border border-slate-300 rounded px-2.5 py-1 text-xs focus:outline-none bg-white">
                <option>10</option>
                <option>20</option>
                <option>50</option>
            </select>
        </div>
    </div>

    <!-- Table Container (matches 20 columns layout) -->
    <div class="bg-white rounded-xl shadow-md border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-center border-collapse border border-slate-300 min-w-[2000px]">
                <thead>
                    <tr class="bg-slate-100 text-slate-700 text-xs font-bold border-b border-slate-300">
                        <th class="py-3 px-2 border border-slate-300 text-rose-800">Sno.</th>
                        <th class="py-3 px-2 border border-slate-300 text-rose-800">Date</th>
                        <th class="py-3 px-2 border border-slate-300 text-rose-800">Time</th>
                        <th class="py-3 px-2 border border-slate-300 text-rose-800">Request ID</th>
                        <th class="py-3 px-2 border border-slate-300 text-rose-800">Account ID</th>
                        <th class="py-3 px-2 border border-slate-300 text-rose-800">Meter No</th>
                        <th class="py-3 px-2 border border-slate-300 text-rose-800">Meter Model</th>
                        <th class="py-3 px-2 border border-slate-300 text-rose-800">KWH</th>
                        <th class="py-3 px-2 border border-slate-300 text-rose-800">KW</th>
                        <th class="py-3 px-2 border border-slate-300 text-rose-800">KWH Img</th>
                        <th class="py-3 px-2 border border-slate-300 text-rose-800">KWH Path</th>
                        <th class="py-3 px-2 border border-slate-300 text-rose-800">KW Img</th>
                        <th class="py-3 px-2 border border-slate-300 text-rose-800">KW Path</th>
                        <th class="py-3 px-2 border border-slate-300 text-rose-800">Lat Long</th>
                        <th class="py-3 px-2 border border-slate-300 text-rose-800">PF</th>
                        <th class="py-3 px-2 border border-slate-300 text-rose-800">PF Img</th>
                        <th class="py-3 px-2 border border-slate-300 text-rose-800">PF Path</th>
                        <th class="py-3 px-2 border border-slate-300 text-rose-800">Address</th>
                        <th class="py-3 px-2 border border-slate-300 text-rose-800">OCR/2</th>
                        <th class="py-3 px-2 border border-slate-300 text-rose-800">MR ID</th>
                    </tr>
                </thead>
                <tbody id="table-data-body" class="divide-y divide-slate-200 text-xs font-semibold text-slate-700 bg-white">
                    <!-- Empty State placeholder -->
                    <tr id="empty-state-row">
                        <td colspan="20" class="py-12 text-center text-slate-400 font-bold text-sm bg-slate-50">
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
                    <span>BESCOM NETWORK (OFFLINE)</span>
                </div>
                <div class="bg-emerald-950 border border-emerald-900/50 rounded p-3 text-emerald-400 flex flex-col justify-center items-end relative shadow-inner">
                    <div id="modal-label" class="text-[9px] text-emerald-600/80 absolute top-1 left-2">ACTIVE KWH</div>
                    <div id="modal-reading-val" class="text-3xl font-bold tracking-widest">00398.0</div>
                    <div id="modal-unit" class="text-[9px] text-emerald-600/80">kWh</div>
                </div>
                <div class="flex justify-between items-center text-[9px] text-slate-600">
                    <span id="modal-serial">No. HPL9803102</span>
                    <span>50Hz 240V</span>
                </div>
            </div>
            <p class="text-xs text-slate-400 text-center font-sans">Snapshot simulated extraction records.</p>
        </div>
    </div>
</div>

<script>
    function openMeterImageModal(val, serial, type = 'KWH') {
        const modal = document.getElementById('image-modal');
        const titleText = document.getElementById('modal-title-text');
        const label = document.getElementById('modal-label');
        const readingVal = document.getElementById('modal-reading-val');
        const unit = document.getElementById('modal-unit');
        const serialNo = document.getElementById('modal-serial');

        serialNo.textContent = "No. " + serial;
        readingVal.textContent = val;

        if (type === 'KWH') {
            titleText.textContent = "KWH Meter Display";
            label.textContent = "ACTIVE KWH";
            unit.textContent = "kWh";
        } else if (type === 'KW') {
            titleText.textContent = "MD Meter Display";
            label.textContent = "MAX DEMAND (KW)";
            unit.textContent = "kW";
        } else if (type === 'PF') {
            titleText.textContent = "Power Factor Display";
            label.textContent = "POWER FACTOR";
            unit.textContent = "PF";
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeMeterImageModal() {
        const modal = document.getElementById('image-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function applyFilters() {
        const mrFilter = document.getElementById('mr-id-select').value;
        const accFilter = document.getElementById('account-id-input').value.trim();

        const rows = document.querySelectorAll('.data-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const mr = row.getAttribute('data-mr');
            const acc = row.getAttribute('data-acc');

            let matches = true;

            if (mrFilter !== 'All' && mr !== mrFilter) matches = false;
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

        document.getElementById('total-count-badge').textContent = visibleCount;
    }

    function resetFilters() {
        document.getElementById('offline-filter-form').reset();
        const rows = document.querySelectorAll('.data-row');
        rows.forEach(row => row.classList.remove('hidden'));
        document.getElementById('empty-state-row').classList.add('hidden');
        document.getElementById('total-count-badge').textContent = '0';
    }

    function refreshData() {
        resetFilters();
        alert("Data refreshed!");
    }
</script>

<?php include $pathToRoot . 'includes/footer.php'; ?>
