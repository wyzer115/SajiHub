// SajiHUB - Main Application JavaScript

// Sidebar Navigation Drawer (Mobile)
document.addEventListener('DOMContentLoaded', function() {
    if (window.__mobileSidebarInitialized) return;
    window.__mobileSidebarInitialized = true;

    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebar = document.getElementById('sidebar');
    const backdrop = document.getElementById('mobile-sidebar-backdrop') || document.getElementById('sidebar-overlay');

    function openMobileDrawer() {
        if (!sidebar) return;
        sidebar.classList.remove('-translate-x-full');
        sidebar.classList.add('translate-x-0');
        if (backdrop) backdrop.classList.remove('hidden');
        document.body.classList.add('overflow-hidden', 'lg:overflow-auto');
    }

    function closeMobileDrawer() {
        if (!sidebar) return;
        sidebar.classList.add('-translate-x-full');
        sidebar.classList.remove('translate-x-0');
        if (backdrop) backdrop.classList.add('hidden');
        document.body.classList.remove('overflow-hidden', 'lg:overflow-auto');
    }

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            const isOpen = !sidebar.classList.contains('-translate-x-full');
            if (isOpen) {
                closeMobileDrawer();
            } else {
                openMobileDrawer();
            }
        });
    }

    if (backdrop) {
        backdrop.addEventListener('click', function(e) {
            e.stopPropagation();
            closeMobileDrawer();
        });
        backdrop.addEventListener('touchstart', function() {
            closeMobileDrawer();
        }, { passive: true });
    }

    // Click outside handler for mobile
    document.addEventListener('click', function(e) {
        if (window.innerWidth >= 1024 || !sidebar) return;
        const isOpen = !sidebar.classList.contains('-translate-x-full');
        if (isOpen && !sidebar.contains(e.target) && (!sidebarToggle || !sidebarToggle.contains(e.target))) {
            closeMobileDrawer();
        }
    });

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' || e.key === 'Esc') {
            closeMobileDrawer();
        }
    });

    // Reset when resizing to desktop
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 1024) {
            closeMobileDrawer();
        }
    });

    // Dropdown toggle
    document.querySelectorAll('[data-dropdown-toggle]').forEach(function(toggle) {
        toggle.addEventListener('click', function(e) {
            e.stopPropagation();
            const targetId = this.getAttribute('data-dropdown-toggle');
            const dropdown = document.getElementById(targetId);
            if (dropdown) {
                dropdown.classList.toggle('hidden');
            }
        });
    });

    // Close dropdowns on outside click
    document.addEventListener('click', function() {
        document.querySelectorAll('[id$="-dropdown"]').forEach(function(dropdown) {
            dropdown.classList.add('hidden');
        });
    });

    // Auto-dismiss alerts after 5 seconds
    document.querySelectorAll('[data-auto-dismiss]').forEach(function(alert) {
        setTimeout(function() {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });

    // Delete confirmation using Custom Modal instead of native confirm
    document.querySelectorAll('[data-confirm-delete]').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (window.showConfirm) {
                window.showConfirm(e, 'Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.', 'Konfirmasi Hapus', 'Ya, Hapus');
            } else {
                form.submit();
            }
        });
    });
});
