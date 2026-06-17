</section>
</main>
</div>
<script src="<?= ADMIN_URL ?>/assets/js/admin.js"></script>
<script>
// 💡 เพิ่มปีกกาเปิดตรงนี้
{
    const sidebar = document.getElementById('adminSidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const toggle = document.getElementById('sidebarToggle');

    function openSidebar() {
        sidebar.classList.remove('translate-x-[-100%]');
        overlay.classList.remove('hidden');
    }

    function closeSidebar() {
        sidebar.classList.add('translate-x-[-100%]');
        overlay.classList.add('hidden');
    }

    if (toggle) {
        toggle.addEventListener('click', () => {
            if (sidebar.classList.contains('translate-x-[-100%]')) {
                openSidebar();
            } else {
                closeSidebar();
            }
        });
    }

    if (overlay) {
        overlay.addEventListener('click', closeSidebar);
    }

    window.addEventListener('resize', () => {
        if (window.innerWidth >= 768 && overlay) {
            overlay.classList.add('hidden');
        }
    });
}
</script>
</body>
</html>