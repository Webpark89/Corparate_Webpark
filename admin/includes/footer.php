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
<script>
// =========================================================================
// Global Admin Session Heartbeat & Draft Cleanup Handler
// =========================================================================
(function initGlobalAdminHeartbeat() {
    // 1. Clean up submitted article draft upon successful redirect to list/dashboard
    try {
        const pendingKey = sessionStorage.getItem('pending_clear_draft');
        if (pendingKey && !window.location.pathname.includes('/create.php') && !window.location.pathname.includes('/edit.php')) {
            localStorage.removeItem(pendingKey);
            sessionStorage.removeItem('pending_clear_draft');
        }
    } catch (e) {}

    // 2. Avoid duplicate heartbeat if current page already has a specialized handler (like article/_form.php)
    if (window._hasAdminHeartbeat) return;
    window._hasAdminHeartbeat = true;

    const HEARTBEAT_INTERVAL = 3 * 60 * 1000; // Ping every 3 minutes
    const HEARTBEAT_URL = '<?= ADMIN_URL ?>/ajax_heartbeat.php';
    let lastPingTime = Date.now();
    let isPinging = false;

    async function doHeartbeat() {
        if (isPinging) return;
        isPinging = true;
        try {
            const res = await fetch(HEARTBEAT_URL, {
                method: 'GET',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                cache: 'no-store'
            });
            lastPingTime = Date.now();
            if (res.ok) {
                const data = await res.json();
                if (data && data.csrf_token) {
                    const csrfInputs = document.querySelectorAll('input[name="_csrf"], input[name="csrf_token"]');
                    csrfInputs.forEach(input => {
                        input.value = data.csrf_token;
                    });
                }
            }
        } catch (err) {
            // Network offline or paused
        } finally {
            isPinging = false;
        }
    }

    // Ping periodically every 3 minutes
    setInterval(doHeartbeat, HEARTBEAT_INTERVAL);

    // Ping when window gains focus back from other tabs or desktop applications
    window.addEventListener('focus', () => {
        if (Date.now() - lastPingTime > 30000) {
            doHeartbeat();
        }
    });
    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'visible' && (Date.now() - lastPingTime > 30000)) {
            doHeartbeat();
        }
    });
})();
</script>
</body>
</html>
