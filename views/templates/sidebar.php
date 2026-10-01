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
<aside id="sidebar" class="fixed top-0 left-0 z-50 h-screen w-72 bg-gradient-to-b from-[#38bdf8] via-[#0ea5e9] to-[#0369a1] border-r border-[#0369a1]/30 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col text-white">

    <!-- Subtle overlay for sidebar -->
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_30%_20%,rgba(255,255,255,0.10)_0%,transparent_60%)] pointer-events-none"></div>

    <!-- Logo & Brand -->
    <div class="relative z-10 flex items-center px-6 h-16 border-b border-white/10 flex-shrink-0">
        <a href="<?= url('dashboard') ?>" class="flex items-center gap-3 flex-1 group" title="Ke Halaman Dashboard">
            <div class="w-10 h-10 flex items-center justify-center shrink-0 bg-white/20 backdrop-blur-sm rounded-lg p-1 border border-white/30 group-hover:bg-white/30 transition-colors">
                <img src="<?= asset('images/logo-uika.png') ?>" alt="Logo UIKA" class="w-full h-full object-contain drop-shadow-sm">
            </div>
            <div>
                <h1 class="text-base font-bold text-white leading-tight tracking-tight group-hover:text-white/90 transition-colors"><?= e(APP_NAME) ?></h1>
                <p class="text-[10px] text-white/70 font-medium tracking-wider uppercase group-hover:text-white/90 transition-colors">SIM Akreditasi</p>
            </div>
        </a>
        <!-- Mobile Close Button -->
        <button id="btn-close-sidebar" class="lg:hidden p-1.5 text-white/70 hover:text-white hover:bg-white/10 rounded-lg transition-all ml-2">
            <i data-lucide="x" class="w-4 h-4"></i>
        </button>
    </div>

    <!-- Navigation Menu -->
    <nav class="relative z-10 flex-1 overflow-y-auto px-3 py-4 space-y-1 scrollbar-thin scrollbar-thumb-white/20 hover:scrollbar-thumb-white/30">
        <?php foreach ($menuItems as $item): ?>
            <?php
            // Divider / Section Header
            if (isset($item['type']) && $item['type'] === 'divider'):
                // Cek apakah user punya akses ke section ini
                $showSection = isset($item['roles']) && in_array($userRole, $item['roles']);
                if (!$showSection) continue;
            ?>
                <div class="pt-5 pb-2 px-3">
                    <p class="text-[10px] font-bold text-white/50 tracking-[0.15em] uppercase"><?= e($item['label']) ?></p>
                </div>
            <?php
            // Menu Item
            else:
                $menuKey = $item['key'] ?? '';
                if (!in_array($menuKey, $allowedMenus)) continue;

                $isActive = ($activePage === $menuKey);
                $activeClass = $isActive
                    ? 'bg-white/20 text-white shadow-sm border-white/20'
                    : 'text-white/80 border-transparent hover:bg-white/10 hover:text-white';
            ?>
                <a href="<?= $item['url'] ?>"
                   class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium border transition-all duration-200 <?= $activeClass ?>">
                    <i data-lucide="<?= $item['icon'] ?>"
                       class="w-[18px] h-[18px] flex-shrink-0 <?= $isActive ? 'text-white' : 'text-white/70 group-hover:text-white' ?> transition-colors"></i>
                    <span><?= e($item['label']) ?></span>
                    <?php if ($isActive): ?>
                        <div class="ml-auto w-1.5 h-1.5 rounded-full bg-white animate-pulse-slow shadow-[0_0_8px_rgba(255,255,255,0.8)]"></div>
                    <?php endif; ?>
                </a>
            <?php endif; ?>
        <?php endforeach; ?>
    </nav>

</aside>
