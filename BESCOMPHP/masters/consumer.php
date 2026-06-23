<?php
$pathToRoot = '../';
$activePage = 'master-consumer';
include $pathToRoot . 'includes/header.php';
?>

<!-- Consumer Master Content -->
<div class="space-y-6">

    <!-- Sub-header banner -->
    <div class="bg-primary text-white p-6 rounded-xl shadow-sm">
        <h1 class="text-2xl font-bold">Consumer Master</h1>
        <p class="text-xs text-white/80 mt-1">Manage your consumers</p>
    </div>

    <!-- Tabs Layout -->
    <div class="border-b border-slate-200">
        <nav class="flex gap-6">
            <a href="#" class="border-b-2 border-primary text-primary pb-3 px-1 text-sm font-bold">List View</a>
        </nav>
    </div>

    <!-- Filter box container -->
    <div class="bg-slate-100/90 border border-slate-200 rounded-xl p-5 shadow-sm">
        <form id="consumer-filter-form" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
            <div class="w-full">
                <label class="block text-xs font-bold text-slate-700 mb-1">CSD</label>
                <select id="csd-select" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-xs focus:ring-1 focus:ring-primary focus:border-primary focus:outline-none bg-white">
                    <option value="All">All</option>
                    <option value="CSD-1">CSD-1</option>
                </select>
            </div>
            <div class="w-full">
                <label class="block text-xs font-bold text-slate-700 mb-1">Category</label>
                <select id="cat-select" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-xs focus:ring-1 focus:ring-primary focus:border-primary focus:outline-none bg-white">
                    <option value="All">All</option>
                    <option value="4LT2A1-N">4LT2A1-N</option>
                    <option value="4LT3IN">4LT3IN</option>
                </select>
            </div>
            <div class="w-full md:col-span-2">
                <label class="block text-xs font-bold text-slate-700 mb-1">Account ID</label>
                <input type="text" id="account-id-input" placeholder="Enter Account ID" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-xs focus:ring-1 focus:ring-primary focus:border-primary focus:outline-none bg-white">
            </div>
            <div class="flex items-center gap-2 sm:col-span-2 md:col-span-4 justify-end">
                <button type="button" onclick="applyFilters()" class="bg-primary hover:bg-primaryHover text-white px-5 py-2 rounded-lg text-xs font-semibold shadow-sm transition">
                    Apply
                </button>
                <button type="button" onclick="resetFilters()" class="bg-slate-400 hover:bg-slate-500 text-white px-5 py-2 rounded-lg text-xs font-semibold shadow-sm transition">
                    Reset
                </button>
            </div>
        </form>
    </div>

    <!-- Table Container (matches screenshot) -->
    <div class="bg-white rounded-xl shadow-md border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto w-full">
            <table class="w-full min-w-[900px] text-left border-collapse border border-slate-300">
                <thead>
                    <tr class="bg-primary text-white text-xs font-bold border-b border-slate-300">
                        <th class="py-3.5 px-4 border border-slate-300">
                            <div class="flex items-center gap-1 cursor-pointer">
                                CSD <i class="fas fa-sort text-[10px] opacity-75"></i>
                            </div>
                        </th>
                        <th class="py-3.5 px-4 border border-slate-300">
                            <div class="flex items-center gap-1 cursor-pointer">
                                Account ID <i class="fas fa-sort text-[10px] opacity-75"></i>
                            </div>
                        </th>
                        <th class="py-3.5 px-4 border border-slate-300">
                            <div class="flex items-center gap-1 cursor-pointer">
                                Name <i class="fas fa-sort text-[10px] opacity-75"></i>
                            </div>
                        </th>
                        <th class="py-3.5 px-4 border border-slate-300">
                            <div class="flex items-center gap-1 cursor-pointer">
                                Mobile <i class="fas fa-sort text-[10px] opacity-75"></i>
                            </div>
                        </th>
                        <th class="py-3.5 px-4 border border-slate-300">
                            <div class="flex items-center gap-1 cursor-pointer">
                                Category <i class="fas fa-sort text-[10px] opacity-75"></i>
                            </div>
                        </th>
                        <th class="py-3.5 px-4 border border-slate-300">
                            <div class="flex items-center gap-1 cursor-pointer">
                                Sanction Load <i class="fas fa-sort text-[10px] opacity-75"></i>
                            </div>
                        </th>
                        <th class="py-3.5 px-4 border border-slate-300">
                            <div class="flex items-center gap-1 cursor-pointer">
                                Meter No <i class="fas fa-sort text-[10px] opacity-75"></i>
                            </div>
                        </th>
                        <th class="py-3.5 px-4 border border-slate-300">
                            <div class="flex items-center gap-1 cursor-pointer">
                                MR Code <i class="fas fa-sort text-[10px] opacity-75"></i>
                            </div>
                        </th>
                        <th class="py-3.5 px-4 border border-slate-300">
                            <div class="flex items-center gap-1 cursor-pointer">
                                Status <i class="fas fa-sort text-[10px] opacity-75"></i>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody id="table-data-body" class="divide-y divide-slate-200 text-xs font-semibold text-slate-700 bg-white">
                    <!-- Empty state -->
                    <tr id="empty-state-row">
                        <td colspan="9" class="py-12 text-center text-slate-400 font-bold text-sm bg-slate-50 border border-slate-300">
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
        const csd = document.getElementById('csd-select').value;
        const cat = document.getElementById('cat-select').value;
        const acc = document.getElementById('account-id-input').value.trim();

        const rows = document.querySelectorAll('.data-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const rowCsd = row.getAttribute('data-csd');
            const rowCat = row.getAttribute('data-cat');
            const rowAcc = row.getAttribute('data-acc');

            let matches = true;

            if (csd !== 'All' && rowCsd !== csd) matches = false;
            if (cat !== 'All' && rowCat !== cat) matches = false;
            if (acc !== '' && !rowAcc.includes(acc)) matches = false;

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
        document.getElementById('consumer-filter-form').reset();
        const rows = document.querySelectorAll('.data-row');
        rows.forEach(row => row.classList.remove('hidden'));
        document.getElementById('empty-state-row').classList.add('hidden');
    }
</script>

<?php include $pathToRoot . 'includes/footer.php'; ?>
