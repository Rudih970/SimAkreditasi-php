/**
 * ============================================================================
 * SIM Akreditasi — Custom JavaScript
 * ============================================================================
 * Menangani interaksi UI: sidebar mobile, dropdown, search modal,
 * dan utilitas umum.
 * ============================================================================
 */

document.addEventListener('DOMContentLoaded', function () {

    // -----------------------------------------------------------------------
    // 1. Mobile Sidebar Toggle
    // -----------------------------------------------------------------------
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebar-overlay');
    const btnMobileMenu = document.getElementById('btn-mobile-menu');
    const btnCloseSidebar = document.getElementById('btn-close-sidebar');

    function openSidebar() {
        if (!sidebar) return;
        sidebar.classList.remove('-translate-x-full');
        sidebarOverlay?.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        if (!sidebar) return;
        sidebar.classList.add('-translate-x-full');
        sidebarOverlay?.classList.add('hidden');
        document.body.style.overflow = '';
    }

    btnMobileMenu?.addEventListener('click', openSidebar);
    btnCloseSidebar?.addEventListener('click', closeSidebar);
    sidebarOverlay?.addEventListener('click', closeSidebar);

    // -----------------------------------------------------------------------
    // 2. Profile Dropdown
    // -----------------------------------------------------------------------
    const btnProfile = document.getElementById('btn-profile');
    const profileMenu = document.getElementById('profile-menu');

    btnProfile?.addEventListener('click', function (e) {
        e.stopPropagation();
        profileMenu?.classList.toggle('hidden');
    });

    // -----------------------------------------------------------------------
    // 3. Search Modal (Ctrl+K)
    // -----------------------------------------------------------------------
    const searchModal = document.getElementById('search-modal');
    const searchOverlay = document.getElementById('search-overlay');
    const searchInput = document.getElementById('search-input');
    const searchResults = document.getElementById('search-results');
    const btnSearch = document.getElementById('btn-search');

    function openSearch() {
        if (!searchModal) return;
        searchModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        setTimeout(() => searchInput?.focus(), 100);
    }

    function closeSearch() {
        if (!searchModal) return;
        searchModal.classList.add('hidden');
        document.body.style.overflow = '';
        if (searchInput) searchInput.value = '';
        if (searchResults) {
            searchResults.innerHTML = '<p class="text-center text-gray-600 text-sm py-8">Ketik untuk mulai mencari...</p>';
        }
    }

    btnSearch?.addEventListener('click', openSearch);
    searchOverlay?.addEventListener('click', closeSearch);

    // Keyboard shortcuts
    document.addEventListener('keydown', function (e) {
        // Ctrl+K or Cmd+K → Open search
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            if (searchModal?.classList.contains('hidden')) {
                openSearch();
            } else {
                closeSearch();
            }
        }
        // Escape → Close modals
        if (e.key === 'Escape') {
            closeSearch();
            profileMenu?.classList.add('hidden');
        }
    });

    // Search menu items
    const menuSearchData = [
        { label: 'Dashboard', url: 'dashboard', icon: 'layout-dashboard', desc: 'Halaman utama' },
        { label: 'Kelola Pengguna', url: 'users', icon: 'users', desc: 'Manajemen user & role' },
        { label: 'Program Studi', url: 'program-studi', icon: 'graduation-cap', desc: 'Data program studi' },
        { label: 'Instrumen Akreditasi', url: 'instrumen', icon: 'clipboard-list', desc: 'Standar akreditasi' },
        { label: 'Pengajuan Akreditasi', url: 'pengajuan', icon: 'file-check', desc: 'Pengajuan proses akreditasi' },
        { label: 'Dokumen Borang', url: 'borang', icon: 'folder-open', desc: 'Upload & kelola dokumen' },
        { label: 'Review & Penilaian', url: 'review', icon: 'scan-search', desc: 'Review dokumen borang' },
        { label: 'Jadwal Pendampingan', url: 'jadwal', icon: 'calendar-days', desc: 'Jadwal kegiatan' },
        { label: 'Laporan Kegiatan', url: 'laporan', icon: 'file-bar-chart', desc: 'Laporan pendampingan' },
        { label: 'Riwayat Akreditasi', url: 'riwayat', icon: 'history', desc: 'Histori akreditasi' },
        { label: 'Notifikasi', url: 'notifikasi', icon: 'bell-ring', desc: 'Pemberitahuan sistem' },
        { label: 'Pengaturan', url: 'settings', icon: 'settings', desc: 'Konfigurasi sistem' },
        { label: 'Profil Saya', url: 'profile', icon: 'user', desc: 'Edit profil pengguna' },
    ];

    searchInput?.addEventListener('input', function () {
        const query = this.value.toLowerCase().trim();

        if (!query) {
            searchResults.innerHTML = '<p class="text-center text-gray-600 text-sm py-8">Ketik untuk mulai mencari...</p>';
            return;
        }

        const filtered = menuSearchData.filter(item =>
            item.label.toLowerCase().includes(query) ||
            item.desc.toLowerCase().includes(query)
        );

        if (filtered.length === 0) {
            searchResults.innerHTML = `
                <div class="text-center py-8">
                    <div class="w-12 h-12 bg-white/[0.04] rounded-xl flex items-center justify-center mx-auto mb-3">
                        <i data-lucide="search-x" class="w-5 h-5 text-gray-600"></i>
                    </div>
                    <p class="text-gray-500 text-sm">Tidak ditemukan hasil untuk "<span class="text-gray-300">${query}</span>"</p>
                </div>`;
            if (typeof lucide !== 'undefined') lucide.createIcons();
            return;
        }

        const baseUrl = document.querySelector('meta[name="base-url"]')?.content || '';

        searchResults.innerHTML = filtered.map(item => `
            <a href="${baseUrl}/${item.url}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm hover:bg-white/[0.04] transition-colors group">
                <div class="w-8 h-8 bg-white/[0.04] rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-primary-500/10">
                    <i data-lucide="${item.icon}" class="w-4 h-4 text-gray-500 group-hover:text-primary-400"></i>
                </div>
                <div>
                    <p class="text-gray-200 font-medium">${item.label}</p>
                    <p class="text-gray-600 text-xs">${item.desc}</p>
                </div>
                <i data-lucide="arrow-right" class="w-4 h-4 text-gray-700 ml-auto opacity-0 group-hover:opacity-100 transition-opacity"></i>
            </a>
        `).join('');

        // Re-initialize Lucide icons for dynamically added elements
        if (typeof lucide !== 'undefined') lucide.createIcons();
    });

    // -----------------------------------------------------------------------
    // 4. Close Dropdowns on Outside Click
    // -----------------------------------------------------------------------
    document.addEventListener('click', function (e) {
        // Close profile dropdown
        if (profileMenu && !profileMenu.contains(e.target) && !btnProfile?.contains(e.target)) {
            profileMenu.classList.add('hidden');
        }
    });

    // -----------------------------------------------------------------------
    // 5. Auto-dismiss Flash Messages
    // -----------------------------------------------------------------------
    const flashAlerts = document.querySelectorAll('.flash-alert');
    flashAlerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });

    // -----------------------------------------------------------------------
    // 6. Confirm Delete Dialog
    // -----------------------------------------------------------------------
    window.confirmDelete = function (formId, itemName) {
        const confirmed = confirm(`Yakin ingin menghapus "${itemName}"?\n\nTindakan ini tidak dapat dibatalkan.`);
        if (confirmed) {
            document.getElementById(formId)?.submit();
        }
    };

    // -----------------------------------------------------------------------
    // 7. File Upload Preview
    // -----------------------------------------------------------------------
    window.handleFileSelect = function (input, previewId) {
        const preview = document.getElementById(previewId);
        if (!preview || !input.files.length) return;

        const file = input.files[0];
        const maxSize = 25 * 1024 * 1024; // 25MB

        if (file.size > maxSize) {
            alert('Ukuran file melebihi batas maksimal (25 MB).');
            input.value = '';
            return;
        }

        const ext = file.name.split('.').pop().toLowerCase();
        const allowedExts = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'jpg', 'jpeg', 'png'];
        if (!allowedExts.includes(ext)) {
            alert('Format file tidak diizinkan. Gunakan: ' + allowedExts.join(', '));
            input.value = '';
            return;
        }

        const fileSize = file.size < 1024 * 1024
            ? (file.size / 1024).toFixed(1) + ' KB'
            : (file.size / (1024 * 1024)).toFixed(2) + ' MB';

        preview.innerHTML = `
            <div class="flex items-center gap-3 p-3 bg-white/[0.04] rounded-xl border border-white/[0.08]">
                <div class="w-10 h-10 bg-primary-500/10 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i data-lucide="file-text" class="w-5 h-5 text-primary-400"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-gray-200 font-medium truncate">${file.name}</p>
                    <p class="text-xs text-gray-500">${fileSize}</p>
                </div>
                <button type="button" onclick="clearFile('${input.id}', '${previewId}')" class="p-1 text-gray-500 hover:text-red-400 transition-colors">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>`;

        if (typeof lucide !== 'undefined') lucide.createIcons();
    };

    window.clearFile = function (inputId, previewId) {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);
        if (input) input.value = '';
        if (preview) preview.innerHTML = '';
    };

    // -----------------------------------------------------------------------
    // 8. Re-initialize Lucide Icons (for dynamic content)
    // -----------------------------------------------------------------------
    window.refreshIcons = function () {
        if (typeof lucide !== 'undefined') lucide.createIcons();
    };

});
