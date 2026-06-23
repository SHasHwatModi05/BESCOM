<?php
$pathToRoot = '../';
$activePage = 'meter-readers';
include $pathToRoot . 'includes/header.php';
?>

<!-- Meter Readers Report Content -->
<div class="space-y-6">

    <!-- Search/Filters Box (matches screenshot) -->
    <div id="search-box-container" class="bg-slate-100/90 border border-slate-200 rounded-xl p-5 shadow-sm space-y-4 relative">
        <div class="flex items-center justify-between border-b border-slate-200 pb-2">
            <span class="font-bold text-slate-800 text-sm">Search</span>
            <button onclick="toggleSearch()" id="toggle-search-btn" class="bg-primary hover:bg-primaryHover text-white px-3 py-1 rounded text-xs font-semibold shadow-sm transition">
                Hide Search
            </button>
        </div>

        <form id="search-form" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">From Date:</label>
                    <input type="date" id="from-date" value="2026-06-22" class="w-full border border-slate-300 rounded-lg px-3 py-1.5 text-xs focus:ring-1 focus:ring-primary focus:border-primary focus:outline-none bg-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">To Date:</label>
                    <input type="date" id="to-date" value="2026-06-22" class="w-full border border-slate-300 rounded-lg px-3 py-1.5 text-xs focus:ring-1 focus:ring-primary focus:border-primary focus:outline-none bg-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Meter Reader ID:</label>
                    <input type="text" id="mr-id-input" placeholder="Enter Meter Reader ID" class="w-full border border-slate-300 rounded-lg px-3 py-1.5 text-xs focus:ring-1 focus:ring-primary focus:border-primary focus:outline-none bg-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Subdivision:</label>
                    <input type="text" id="subdiv-input" placeholder="Enter Subdivision" class="w-full border border-slate-300 rounded-lg px-3 py-1.5 text-xs focus:ring-1 focus:ring-primary focus:border-primary focus:outline-none bg-white">
                </div>
            </div>

            <!-- Buttons inside Search box -->
            <div class="flex justify-end gap-2.5 pt-2 border-t border-slate-200/50">
                <button type="button" onclick="resetSearch()" class="bg-slate-400 hover:bg-slate-500 text-white px-4 py-1.5 rounded-lg text-xs font-semibold transition shadow-sm">
                    Reset
                </button>
                <button type="button" onclick="applySearch()" class="bg-primary hover:bg-primaryHover text-white px-4 py-1.5 rounded-lg text-xs font-semibold transition shadow-sm">
                    Apply Search
                </button>
            </div>
        </form>
    </div>

    <!-- Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <!-- Left Buttons & Badges -->
        <div class="flex flex-wrap items-center gap-3">
            <button onclick="alert('Exported to Excel')" class="bg-primary hover:bg-primaryHover text-white px-4 py-2 rounded text-xs font-bold shadow-sm transition">
                Download Excel
            </button>
            <span class="bg-slate-200 text-slate-700 text-xs font-bold px-3 py-2 rounded shadow-sm">
                Total Counts (<strong id="total-counts-badge">0</strong>)
            </span>
            <span class="bg-slate-200 text-slate-700 text-xs font-bold px-3 py-2 rounded shadow-sm">
                Total Meter Readers (<strong id="total-readers-badge">0</strong>)
            </span>
        </div>

        <!-- Center Page Title -->
        <h2 class="text-base font-bold text-slate-800 tracking-wide text-center">
            Meter Readers Report - Kalaburagi
        </h2>

        <!-- Right Action Button -->
        <button onclick="refreshData()" class="bg-primary hover:bg-primaryHover text-white px-4 py-2 rounded text-xs font-bold shadow-sm transition">
            Refresh Data
        </button>
    </div>

    <!-- Table Container (matches screenshot headers) -->
    <div class="bg-white rounded-xl shadow-md border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto w-full">
            <table class="w-full min-w-[700px] text-center border-collapse border border-slate-300">
                <thead>
                    <tr class="bg-slate-100 text-slate-700 text-xs font-bold border-b border-slate-300">
                        <th class="py-3 px-3 border border-slate-300 text-rose-800">Sno.</th>
                        <th class="py-3 px-3 border border-slate-300 text-rose-800">Date</th>
                        <th class="py-3 px-3 border border-slate-300 text-rose-800">Meter Reader ID</th>
                        <th class="py-3 px-3 border border-slate-300 text-rose-800">Subdivision</th>
                        <th class="py-3 px-3 border border-slate-300 text-rose-800">
                            <div class="flex items-center justify-center gap-1">
                                Total Count <i class="fas fa-sort text-[10px] text-slate-400"></i>
                            </div>
                        </th>
                        <th class="py-3 px-3 border border-slate-300 text-rose-800">
                            <div class="flex items-center justify-center gap-1">
                                Online Count <i class="fas fa-sort text-[10px] text-slate-400"></i>
                            </div>
                        </th>
                        <th class="py-3 px-3 border border-slate-300 text-rose-800">
                            <div class="flex items-center justify-center gap-1">
                                Offline Count <i class="fas fa-sort text-[10px] text-slate-400"></i>
                            </div>
                        </th>
                        <th class="py-3 px-3 border border-slate-300 text-rose-800">Tracking</th>
                    </tr>
                </thead>
                <tbody id="table-data-body" class="divide-y divide-slate-200 text-xs font-semibold text-slate-700 bg-white">
                    <!-- Empty State row -->
                    <tr id="empty-state-row">
                        <td colspan="8" class="py-12 text-center text-slate-400 font-bold text-sm bg-slate-50">
                            No records found for the selected date range
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function toggleSearch() {
        const form = document.getElementById('search-form');
        const btn = document.getElementById('toggle-search-btn');
        if (form.classList.contains('hidden')) {
            form.classList.remove('hidden');
            btn.textContent = 'Hide Search';
        } else {
            form.classList.add('hidden');
            btn.textContent = 'Show Search';
        }
    }

    function applySearch() {
        const mrFilter = document.getElementById('mr-id-input').value.trim().toUpperCase();
        const subdivFilter = document.getElementById('subdiv-input').value.trim().toUpperCase();

        const rows = document.querySelectorAll('.data-row');
        let visibleCount = 0;
        let totalCountSum = 0;

        rows.forEach(row => {
            const mr = row.getAttribute('data-mr').toUpperCase();
            const subdiv = row.getAttribute('data-subdiv').toUpperCase();
            const count = parseInt(row.children[4].textContent);

            let matches = true;

            if (mrFilter !== '' && !mr.includes(mrFilter)) matches = false;
            if (subdivFilter !== '' && !subdiv.includes(subdivFilter)) matches = false;

            if (matches) {
                row.classList.remove('hidden');
                visibleCount++;
                totalCountSum += count;
            } else {
                row.classList.add('hidden');
            }
        });

        const emptyRow = document.getElementById('empty-state-row');
        if (visibleCount === 0) {
            emptyRow.classList.remove('hidden');
        } else {
            emptyRow.classList.add('hidden');
        }

        // Update badges
        document.getElementById('total-counts-badge').textContent = totalCountSum;
        document.getElementById('total-readers-badge').textContent = visibleCount;
    }

    function resetSearch() {
        document.getElementById('search-form').reset();
        const rows = document.querySelectorAll('.data-row');
        rows.forEach(row => row.classList.remove('hidden'));
        document.getElementById('empty-state-row').classList.add('hidden');

        document.getElementById('total-counts-badge').textContent = '0';
        document.getElementById('total-readers-badge').textContent = '0';
    }

    function refreshData() {
        resetSearch();
        alert("Data refreshed!");
    }
</script>

<?php include $pathToRoot . 'includes/footer.php'; ?>
