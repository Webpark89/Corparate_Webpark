</section>
</main>
</div>
<script src="<?= ADMIN_URL ?>/assets/js/admin.js"></script>
<script>
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

	toggle.addEventListener('click', () => {
		if (sidebar.classList.contains('translate-x-[-100%]')) {
			openSidebar();
		} else {
			closeSidebar();
		}
	});

	overlay.addEventListener('click', closeSidebar);

	window.addEventListener('resize', () => {
		if (window.innerWidth >= 768) {
			overlay.classList.add('hidden');
		}
	});
</script>
</body>

</html>