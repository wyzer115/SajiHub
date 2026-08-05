// SajiHUB - Main Application JavaScript

// Sidebar Toggle
document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');
    const sidebarOverlay = document.getElementById('sidebar-overlay');

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('-translate-x-full');
            sidebar.classList.toggle('translate-x-0');
            if (sidebarOverlay) {
                sidebarOverlay.classList.toggle('hidden');
            }
        });
    }

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.add('-translate-x-full');
            sidebar.classList.remove('translate-x-0');
            sidebarOverlay.classList.add('hidden');
        });
    }

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

    // Delete confirmation
    document.querySelectorAll('[data-confirm-delete]').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            if (!confirm('Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.')) {
                e.preventDefault();
            }
        });
    });
});
