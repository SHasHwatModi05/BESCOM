    </main>

    <!-- Footer triggers Javascript -->
    <script>
        // Dropdown triggers controller
        document.querySelectorAll('.dropdown-trigger').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const menu = this.nextElementSibling;
                const isOpen = !menu.classList.contains('hidden');
                
                // Close all dropdowns
                document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.add('hidden'));
                
                if (!isOpen) {
                    menu.classList.remove('hidden');
                }
            });
        });

        // Close dropdowns on body clicks
        document.addEventListener('click', function() {
            document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.add('hidden'));
        });

        // Mobile drawer navigation controller
        const menuBtn = document.getElementById('menu-btn');
        const mobileSidebar = document.getElementById('mobile-sidebar');
        const sidebarMenu = document.getElementById('sidebar-menu');
        const closeSidebar = document.getElementById('close-sidebar');
        const sidebarBackdrop = document.getElementById('sidebar-backdrop');

        function openDrawer() {
            if (mobileSidebar && sidebarMenu) {
                mobileSidebar.classList.remove('opacity-0', 'pointer-events-none');
                mobileSidebar.classList.add('opacity-100', 'pointer-events-auto');
                sidebarMenu.classList.remove('-translate-x-full');
                sidebarMenu.classList.add('translate-x-0');
            }
        }

        function closeDrawer() {
            if (mobileSidebar && sidebarMenu) {
                mobileSidebar.classList.remove('opacity-100', 'pointer-events-auto');
                mobileSidebar.classList.add('opacity-0', 'pointer-events-none');
                sidebarMenu.classList.remove('translate-x-0');
                sidebarMenu.classList.add('-translate-x-full');
            }
        }

        if (menuBtn) menuBtn.addEventListener('click', openDrawer);
        if (closeSidebar) closeSidebar.addEventListener('click', closeDrawer);
        if (sidebarBackdrop) sidebarBackdrop.addEventListener('click', closeDrawer);
    </script>
</body>
</html>
