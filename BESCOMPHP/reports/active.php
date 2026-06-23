<?php
$pathToRoot = '../';
$activePage = 'active-inactive-status';
include $pathToRoot . 'includes/header.php';
?>

<!-- Active/Inactive Status View -->
<div class="space-y-6">

    <!-- Sub-header banner -->
    <div class="bg-primary/95 text-white font-bold text-center py-3 rounded-xl uppercase tracking-wider text-sm shadow">
        Meter Readers – Active / Inactive Status
    </div>

    <!-- Filters (matches screenshot) -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100">
        <form id="status-filter-form" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
            <div class="w-full">
                <label class="block text-xs font-semibold text-slate-500 mb-1">Select Date</label>
                <input type="date" id="select-date" value="2026-06-22" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs focus:ring-1 focus:ring-primary focus:border-primary focus:outline-none">
            </div>
            <div class="w-full">
                <label class="block text-xs font-semibold text-slate-500 mb-1">Status</label>
                <select id="status-select" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs focus:ring-1 focus:ring-primary focus:border-primary focus:outline-none bg-white">
                    <option value="All">All</option>
                    <option value="Active">Active Only</option>
                    <option value="Inactive">Inactive Only</option>
                </select>
            </div>
            <div class="w-full">
                <label class="block text-xs font-semibold text-slate-500 mb-1">MR Code</label>
                <input type="text" id="mr-code-input" placeholder="Enter MR Code" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs focus:ring-1 focus:ring-primary focus:border-primary focus:outline-none">
            </div>
            <div class="w-full">
                <label class="block text-xs font-semibold text-slate-500 mb-1">Mobile Number</label>
                <input type="text" id="mobile-input" placeholder="Enter Mobile Number" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs focus:ring-1 focus:ring-primary focus:border-primary focus:outline-none">
            </div>
            <div class="flex items-center gap-2 sm:col-span-2 lg:col-span-1">
                <button type="button" onclick="applyFilters()" class="flex-1 bg-primary hover:bg-primaryHover text-white px-5 py-2 rounded-xl text-xs font-semibold shadow-sm transition">Apply</button>
                <button type="button" onclick="resetFilters()" class="flex-1 bg-slate-400 hover:bg-slate-500 text-white px-5 py-2 rounded-xl text-xs font-semibold shadow-sm transition">Reset</button>
            </div>
        </form>
    </div>

    <!-- Status Badges Bar -->
    <div class="flex flex-wrap gap-3">
        <span class="bg-emerald-50 text-emerald-700 border border-emerald-100 text-xs font-bold px-4 py-2 rounded-xl shadow-sm">
            Active MRs: <strong id="active-count-badge">0</strong>
        </span>
        <span class="bg-rose-50 text-rose-700 border border-rose-100 text-xs font-bold px-4 py-2 rounded-xl shadow-sm">
            Inactive MRs: <strong id="inactive-count-badge">0</strong>
        </span>
        <span class="bg-slate-100 text-slate-700 border border-slate-200 text-xs font-bold px-4 py-2 rounded-xl shadow-sm">
            Total MRs: <strong id="total-count-badge">0</strong>
        </span>
    </div>

    <!-- Table Container (matches screenshot) -->
    <div class="bg-white rounded-xl shadow-md border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto w-full">
            <table class="w-full min-w-[600px] text-center border-collapse border border-slate-300">
                <thead>
                    <tr class="bg-slate-100 text-slate-700 text-xs font-bold border-b border-slate-300">
                        <th class="py-3 px-4 border border-slate-300 text-rose-800 w-20">SI No</th>
                        <th class="py-3 px-4 border border-slate-300 text-rose-800">CSD</th>
                        <th class="py-3 px-4 border border-slate-300 text-rose-800">MR Code</th>
                        <th class="py-3 px-4 border border-slate-300 text-rose-800">Name</th>
                        <th class="py-3 px-4 border border-slate-300 text-rose-800">Mobile</th>
                        <th class="py-3 px-4 border border-slate-300 text-rose-800">Status (<span id="header-date">22/06/2026</span>)</th>
                    </tr>
                </thead>
                <tbody id="table-data-body" class="divide-y divide-slate-200 text-xs font-semibold text-slate-700 bg-white">
                    <!-- Empty state -->
                    <tr id="empty-state-row">
                        <td colspan="6" class="py-12 text-center text-slate-400 font-bold text-sm bg-slate-50 border border-slate-300">
                            No records found
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function formatDate(dateStr) {
        if (!dateStr) return '';
        const parts = dateStr.split('-');
        if (parts.length === 3) {
            return parts[2] + '/' + parts[1] + '/' + parts[0];
        }
        return dateStr;
    }

    function applyFilters() {
        const selectDate = document.getElementById('select-date').value;
        const statusFilter = document.getElementById('status-select').value;
        const mrCode = document.getElementById('mr-code-input').value.trim();
        const mobile = document.getElementById('mobile-input').value.trim();

        // Update header date
        document.getElementById('header-date').textContent = formatDate(selectDate);

        const rows = document.querySelectorAll('.data-row');
        let visibleCount = 0;
        let activeCount = 0;
        let inactiveCount = 0;

        rows.forEach(row => {
            const status = row.getAttribute('data-status');
            const code = row.getAttribute('data-code');
            const rowMobile = row.getAttribute('data-mobile');

            let matches = true;

            if (statusFilter !== 'All' && status !== statusFilter) matches = false;
            if (mrCode !== '' && !code.includes(mrCode)) matches = false;
            if (mobile !== '' && !rowMobile.includes(mobile)) matches = false;

            if (matches) {
                row.classList.remove('hidden');
                visibleCount++;
                if (status === 'Active') {
                    activeCount++;
                } else {
                    inactiveCount++;
                }
            } else {
                row.classList.add('hidden');
            }
        });

        const empty = document.getElementById('empty-state-row');
        if (visibleCount === 0) {
            empty.classList.remove('hidden');
        } else {
            empty.classList.add('hidden');
        }

        // Update badges
        document.getElementById('active-count-badge').textContent = activeCount;
        document.getElementById('inactive-count-badge').textContent = inactiveCount;
        document.getElementById('total-count-badge').textContent = visibleCount;
    }

    function resetFilters() {
        document.getElementById('status-filter-form').reset();
        document.getElementById('header-date').textContent = '22/06/2026';
        const rows = document.querySelectorAll('.data-row');
        rows.forEach(row => row.classList.remove('hidden'));
        document.getElementById('empty-state-row').classList.add('hidden');

        document.getElementById('active-count-badge').textContent = '0';
        document.getElementById('inactive-count-badge').textContent = '0';
        document.getElementById('total-count-badge').textContent = '0';
    }
</script>

<?php include $pathToRoot . 'includes/footer.php'; ?>
