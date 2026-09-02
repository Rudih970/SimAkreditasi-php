<?php
/**
 * ============================================================================
 * SIM Akreditasi — Sidebar Navigation Template
 * ============================================================================
 * Sidebar navigasi responsif dengan RBAC-based menu visibility.
 * Menu ditampilkan berdasarkan role pengguna yang sedang login.
 * 
 * Variabel yang tersedia:
 * - $currentUser : Data user yang login
 * - $activePage  : Halaman aktif saat ini
 * - $baseUrl     : Base URL aplikasi
 * ============================================================================
 */

// Definisi menu dengan icon Lucide
$menuItems = [
    [
        'key'   => 'dashboard',
        'label' => 'Dashboard',
        'icon'  => 'layout-dashboard',
        'url'   => url('dashboard'),
    ],
    // -----------------------------------------------------------------------
    // Kategori 1: Master Data
    // -----------------------------------------------------------------------
    [
        'type'  => 'divider',
        'label' => 'Master Data',
        'roles' => ['admin_universitas', 'kepala_kpma', 'kabid_kpma', 'admin_prodi'],
    ],
    [
        'key'   => 'program_studi',
        'label' => 'Program Studi',
        'icon'  => 'graduation-cap',
        'url'   => url('program-studi'),
    ],
    [
        'key'   => 'users',
        'label' => 'Manajemen Akun',
        'icon'  => 'users',
        'url'   => url('users'),
    ],
    [
        'key'   => 'instrumen',
        'label' => 'Instrumen Akreditasi',
        'icon'  => 'clipboard-list',
        'url'   => url('instrumen'),
    ],
    // -----------------------------------------------------------------------
    // Kategori 2: Akreditasi
    // -----------------------------------------------------------------------
    [
        'type'  => 'divider',
        'label' => 'Akreditasi',
        'roles' => ['admin_universitas', 'kepala_kpma', 'kabid_kpma', 'reviewer_internal', 'asesor_internal', 'admin_prodi', 'team_task_force'],
    ],
    [
        'key'   => 'pengajuan',
        'label' => 'Pengajuan Akreditasi',
        'icon'  => 'file-check',
        'url'   => url('pengajuan'),
    ],
    [
        'key'   => 'borang',
        'label' => 'Upload Borang',
        'icon'  => 'folder-open',
        'url'   => url('borang'),
    ],
    [
        'key'   => 'review',
        'label' => 'Review Borang',
        'icon'  => 'scan-search',
        'url'   => url('review'),
    ],
    [
        'key'   => 'jadwal',
        'label' => 'Jadwal Pendampingan',
        'icon'  => 'calendar-days',
        'url'   => url('jadwal'),
    ],
    [
        'key'   => 'riwayat',
        'label' => 'Riwayat Akreditasi',
        'icon'  => 'history',
        'url'   => url('riwayat'),
    ],
    // -----------------------------------------------------------------------
    // Kategori 3: Laporan
    // -----------------------------------------------------------------------
    [
        'type'  => 'divider',
        'label' => 'Laporan',
        'roles' => ['admin_universitas', 'kepala_kpma', 'kabid_kpma', 'asesor_internal', 'admin_prodi', 'team_task_force'],
    ],
    [
        'key'   => 'laporan',
        'label' => 'Laporan Pendampingan',
        'icon'  => 'file-bar-chart',
        'url'   => url('laporan'),
    ],
    [
        'key'   => 'statistik',
        'label' => 'Statistik & Evaluasi Mutu',
        'icon'  => 'pie-chart',
        'url'   => url('statistik'),
    ],
    // -----------------------------------------------------------------------
    // Kategori Tambahan: Sistem & Pengaturan
    // -----------------------------------------------------------------------
    [
        'type'  => 'divider',
        'label' => 'Sistem',
        'roles' => ['admin_universitas', 'kepala_kpma', 'kabid_kpma', 'reviewer_internal', 'asesor_internal', 'admin_prodi', 'team_task_force'],
    ],
    [
        'key'   => 'activity_log',
        'label' => 'Log Aktivitas',
        'icon'  => 'scroll-text',
        'url'   => url('activity-log'),
    ],
    [
        'key'   => 'notifikasi',
        'label' => 'Notifikasi',
        'icon'  => 'bell-ring',
        'url'   => url('notifikasi'),
    ],
    [
        'key'   => 'settings',
        'label' => 'Pengaturan',
        'icon'  => 'settings',
        'url'   => url('settings'),
    ],
];

$userRole = $currentUser['role'] ?? '';
$allowedMenus = ROLE_MENUS[$userRole] ?? [];
$activePage = $activePage ?? ''; // Fallback for linting and safety
?>

<!-- Mobile Sidebar Overlay -->
<div id="sidebar-overlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 lg:hidden hidden transition-opacity"></div>

<!-- Sidebar -->
<aside id="sidebar" class="fixed top-0 left-0 z-50 h-screen w-72 bg-white border-r border-surface-200 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col">

    <!-- Logo & Brand -->
    <div class="flex items-center gap-3 px-6 h-16 border-b border-surface-200 flex-shrink-0">
        <div class="w-10 h-10 flex items-center justify-center shrink-0">
            <img src="<?= asset('images/logo-uika.png') ?>" alt="Logo UIKA" class="w-full h-full object-contain drop-shadow-sm">
        </div>
        <div>
            <h1 class="text-base font-bold text-surface-900 leading-tight tracking-tight"><?= e(APP_NAME) ?></h1>
            <p class="text-[10px] text-surface-500 font-medium tracking-wider uppercase">SIM Akreditasi</p>
        </div>
        <!-- Mobile Close Button -->
        <button id="btn-close-sidebar" class="lg:hidden ml-auto p-1.5 text-surface-500 hover:text-surface-900 hover:bg-surface-100 rounded-lg transition-all">
            <i data-lucide="x" class="w-4 h-4"></i>
        </button>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1 scrollbar-thin">
        <?php foreach ($menuItems as $item): ?>
            <?php
            // Divider / Section Header
            if (isset($item['type']) && $item['type'] === 'divider'):
                // Cek apakah user punya akses ke section ini
                $showSection = isset($item['roles']) && in_array($userRole, $item['roles']);
                if (!$showSection) continue;
            ?>
                <div class="pt-5 pb-2 px-3">
                    <p class="text-[10px] font-bold text-surface-500 tracking-[0.15em] uppercase"><?= e($item['label']) ?></p>
                </div>
            <?php
            // Menu Item
            else:
                $menuKey = $item['key'] ?? '';
                if (!in_array($menuKey, $allowedMenus)) continue;

                $isActive = ($activePage === $menuKey);
                $activeClass = $isActive
                    ? 'bg-primary-50 text-primary-600 border-primary-100 shadow-sm'
                    : 'text-surface-600 border-transparent hover:bg-surface-50 hover:text-surface-900';
            ?>
                <a href="<?= $item['url'] ?>"
                   class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium border transition-all duration-200 <?= $activeClass ?>">
                    <i data-lucide="<?= $item['icon'] ?>"
                       class="w-[18px] h-[18px] flex-shrink-0 <?= $isActive ? 'text-primary-600' : 'text-surface-400 group-hover:text-surface-600' ?> transition-colors"></i>
                    <span><?= e($item['label']) ?></span>
                    <?php if ($isActive): ?>
                        <div class="ml-auto w-1.5 h-1.5 rounded-full bg-primary-500 animate-pulse-slow"></div>
                    <?php endif; ?>
                </a>
            <?php endif; ?>
        <?php endforeach; ?>
    </nav>

    <!-- Sidebar Footer: User Info -->
    <div class="flex-shrink-0 border-t border-surface-200 p-4">
        <div class="flex items-center gap-3 p-2 rounded-xl bg-surface-50 border border-surface-100">
            <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-primary-500/80 to-primary-600/80 flex items-center justify-center text-white text-xs font-bold">
                <?= get_initials($currentUser['nama_lengkap'] ?? 'User') ?>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-surface-900 truncate"><?= e($currentUser['nama_lengkap'] ?? '') ?></p>
                <p class="text-[11px] text-surface-500 truncate"><?= e(role_label($currentUser['role'] ?? '')) ?></p>
            </div>
            <a href="<?= url('auth/logout') ?>" class="p-1.5 text-surface-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all" title="Keluar">
                <i data-lucide="log-out" class="w-4 h-4"></i>
            </a>
        </div>
    </div>
</aside>
