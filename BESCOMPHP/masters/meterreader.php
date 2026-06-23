<?php
$pathToRoot = '../';
$activePage = 'master-reader';
include $pathToRoot . 'includes/header.php';
?>

<!-- Meter Reader Master Content -->
<div class="space-y-6">

    <!-- Sub-header banner -->
    <div class="bg-primary text-white p-6 rounded-xl shadow-sm">
        <h1 class="text-2xl font-bold">Meter Reader Master</h1>
        <p class="text-xs text-white/80 mt-1">Manage your meter reader records</p>
    </div>

    <!-- Tabs Layout -->
    <div class="border-b border-slate-200">
        <nav class="flex gap-6">
            <a href="#" class="border-b-2 border-primary text-primary pb-3 px-1 text-sm font-bold">List View</a>
        </nav>
    </div>

    <!-- Filter box container -->
    <div class="bg-slate-100/90 border border-slate-200 rounded-xl p-5 shadow-sm">
        <form id="mr-filter-form" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
            <div class="w-full">
                <label class="block text-xs font-bold text-slate-700 mb-1">Sub Division</label>
                <select id="subdiv-select" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-xs focus:ring-1 focus:ring-primary focus:border-primary focus:outline-none bg-white">
                    <option value="All">All</option>
                    <option value="CSD 1">CSD 1</option>
                    <option value="CSD 2">CSD 2</option>
                </select>
            </div>
            <div class="w-full">
                <label class="block text-xs font-bold text-slate-700 mb-1">MR Code</label>
                <input type="text" id="mr-code-input" placeholder="Enter MR Code" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-xs focus:ring-1 focus:ring-primary focus:border-primary focus:outline-none bg-white">
            </div>
            <div class="w-full">
                <label class="block text-xs font-bold text-slate-700 mb-1">Mobile Number</label>
                <input type="text" id="mobile-input" placeholder="Enter Mobile" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-xs focus:ring-1 focus:ring-primary focus:border-primary focus:outline-none bg-white">
            </div>
            <div class="flex items-center gap-2 sm:col-span-3 justify-end">
                <button type="button" onclick="applyFilters()" class="bg-primary hover:bg-primaryHover text-white px-5 py-2 rounded-lg text-xs font-semibold shadow-sm transition">
                    Apply
                </button>
                <button type="button" onclick="resetFilters()" class="bg-slate-300 hover:bg-slate-400 text-slate-700 px-5 py-2 rounded-lg text-xs font-semibold shadow-sm transition">
                    Reset
                </button>
            </div>
        </form>
    </div>

    <!-- Table Container (matches screenshot) -->
    <div class="bg-white rounded-xl shadow-md border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto w-full">
            <table class="w-full min-w-[800px] text-left border-collapse border border-slate-300">
                <thead>
                    <tr class="bg-primary text-white text-xs font-bold uppercase border-b border-slate-300">
                        <th class="py-3.5 px-4 border border-slate-300 w-16">S.No</th>
                        <th class="py-3.5 px-4 border border-slate-300">Sub Division</th>
                        <th class="py-3.5 px-4 border border-slate-300">MR Code</th>
                        <th class="py-3.5 px-4 border border-slate-300">Name</th>
                        <th class="py-3.5 px-4 border border-slate-300">Mobile</th>
                        <th class="py-3.5 px-4 border border-slate-300">Agency</th>
                        <th class="py-3.5 px-4 border border-slate-300">Status</th>
                    </tr>
                </thead>
                <tbody id="table-data-body" class="divide-y divide-slate-200 text-xs font-semibold text-slate-700 bg-white">
                    <!-- Empty state -->
                    <tr id="empty-state-row">
                        <td colspan="7" class="py-12 text-center text-slate-400 font-bold text-sm bg-slate-50 border border-slate-300">
                            No records found
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function applyFilters() {
        const subdiv = document.getElementById('subdiv-select').value;
        const mrCode = document.getElementById('mr-code-input').value.trim();
        const mobile = document.getElementById('mobile-input').value.trim();

        const rows = document.querySelectorAll('.data-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const rowSubdiv = row.getAttribute('data-subdiv');
            const rowCode = row.getAttribute('data-code');
            const rowMobile = row.getAttribute('data-mobile');

            let matches = true;

            if (subdiv !== 'All' && rowSubdiv !== subdiv) matches = false;
            if (mrCode !== '' && !rowCode.includes(mrCode)) matches = false;
            if (mobile !== '' && !rowMobile.includes(mobile)) matches = false;

            if (matches) {
                row.classList.remove('hidden');
                visibleCount++;
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
    }

    function resetFilters() {
        document.getElementById('mr-filter-form').reset();
        const rows = document.querySelectorAll('.data-row');
        rows.forEach(row => row.classList.remove('hidden'));
        document.getElementById('empty-state-row').classList.add('hidden');
    }
</script>

<?php include $pathToRoot . 'includes/footer.php'; ?>
