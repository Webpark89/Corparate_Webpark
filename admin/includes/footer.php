<?php

/**
 * Admin layout footer — closes main content, loads admin.js, and sidebar resize handler.
 */
?>
</section>
</main>
</div>
<script src="<?= ADMIN_URL ?>/assets/js/admin.js"></script>
<script>
{
    const sidebar = document.getElementById('adminSidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const toggle = document.getElementById('sidebarToggle');

    function openSidebar() {
        if (!sidebar) return;
        sidebar.classList.remove('-translate-x-full');
        sidebar.classList.add('translate-x-0');
        if (overlay) {
            overlay.classList.remove('hidden');
            overlay.style.display = 'block';
        }
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        if (!sidebar) return;
        sidebar.classList.add('-translate-x-full');
        sidebar.classList.remove('translate-x-0');
        if (overlay) {
            overlay.classList.add('hidden');
            overlay.style.display = 'none';
        }
        document.body.style.overflow = '';
    }

    if (toggle) {
        toggle.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            if (sidebar.classList.contains('translate-x-0')) {
                closeSidebar();
            } else {
                openSidebar();
            }
        });
    }

    if (overlay) {
        overlay.addEventListener('click', (e) => {
            e.preventDefault();
            closeSidebar();
        });
    }

    // Auto-close drawer on mobile when clicking any link in the sidebar
    if (sidebar) {
        sidebar.querySelectorAll('a').forEach((link) => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 768) {
                    closeSidebar();
                }
            });
        });
    }

    window.addEventListener('resize', () => {
        if (window.innerWidth >= 768) {
            closeSidebar();
        }
    });
}
</script>
</body>
</html>
