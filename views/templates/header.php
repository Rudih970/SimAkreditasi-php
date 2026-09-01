<?php
/**
 * ============================================================================
 * SIM Akreditasi — Global Header Template
 * ============================================================================
 * Template header yang digunakan di seluruh halaman aplikasi.
 * Memuat: Tailwind CSS CDN, Lucide Icons, Google Fonts, dan custom CSS.
 * 
 * Variabel yang tersedia:
 * - $pageTitle   : Judul halaman (opsional)
 * - $currentUser : Data user yang login
 * - $baseUrl     : Base URL aplikasi
 * - $appName     : Nama aplikasi
 * ============================================================================
 */

$pageTitle = ($pageTitle ?? 'Dashboard') . ' — ' . APP_NAME;
$activePage = $activePage ?? '';
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <!-- SEO Meta Tags -->
    <title><?= e($pageTitle) ?></title>
    <meta name="description" content="<?= e(APP_DESCRIPTION) ?>">
    <meta name="author" content="<?= e(APP_NAME) ?>">
    <meta name="robots" content="noindex, nofollow">
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎓</text></svg>">
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50:  '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                            950: '#1e1b4b',
                        },
                        surface: {
                            50:  '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            700: '#1a1d2e',
                            800: '#141625',
                            850: '#10121f',
                            900: '#0c0e1a',
                            950: '#080a12',
                        }
                    },
                    animation: {
                        'slide-down': 'slideDown 0.3s ease-out',
                        'slide-up': 'slideUp 0.3s ease-out',
                        'fade-in': 'fadeIn 0.3s ease-out',
                        'scale-in': 'scaleIn 0.2s ease-out',
                        'shimmer': 'shimmer 2s linear infinite',
                        'pulse-slow': 'pulse 3s ease-in-out infinite',
                    },
                    keyframes: {
                        slideDown: {
                            '0%': { opacity: '0', transform: 'translateY(-10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        slideUp: {
                            '0%': { opacity: '0', transform: 'translateY(10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        scaleIn: {
                            '0%': { opacity: '0', transform: 'scale(0.95)' },
                            '100%': { opacity: '1', transform: 'scale(1)' },
                        },
                        shimmer: {
                            '0%': { backgroundPosition: '-200% 0' },
                            '100%': { backgroundPosition: '200% 0' },
                        }
                    }
                },
            },
        }
    </script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>
<body class="bg-surface-950 text-gray-200 font-sans antialiased min-h-screen">

    <!-- App Container -->
    <div id="app" class="flex min-h-screen">

        <?php if (isset($currentUser) && $currentUser): ?>
            <!-- Sidebar Navigation -->
            <?php require_once ROOT_PATH . '/views/templates/sidebar.php'; ?>
        <?php endif; ?>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col <?= (isset($currentUser) && $currentUser) ? 'lg:ml-72' : '' ?>">
            
            <?php if (isset($currentUser) && $currentUser): ?>
            <!-- Top Navigation Bar -->
            <header class="sticky top-0 z-30 bg-surface-900/80 backdrop-blur-xl border-b border-white/[0.06]">
                <div class="flex items-center justify-between h-16 px-4 lg:px-8">
                    <!-- Mobile Menu Toggle -->
                    <button id="btn-mobile-menu" class="lg:hidden p-2 text-gray-400 hover:text-white hover:bg-white/[0.06] rounded-xl transition-all">
                        <i data-lucide="menu" class="w-5 h-5"></i>
                    </button>

                    <!-- Breadcrumb / Page Title -->
                    <div class="hidden lg:flex items-center gap-2 text-sm">
                        <a href="<?= url('dashboard') ?>" class="text-gray-500 hover:text-gray-300 transition-colors">
                            <i data-lucide="home" class="w-4 h-4"></i>
                        </a>
                        <?php if (!empty($activePage) && $activePage !== 'dashboard'): ?>
                            <i data-lucide="chevron-right" class="w-4 h-4 text-gray-600"></i>
                            <span class="text-gray-300 font-medium capitalize"><?= e(str_replace('-', ' ', $activePage)) ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Right Side: Search, Notifications, Profile -->
                    <div class="flex items-center gap-2">
                        <!-- Search Toggle -->
                        <button id="btn-search" class="p-2.5 text-gray-400 hover:text-white hover:bg-white/[0.06] rounded-xl transition-all" title="Cari (Ctrl+K)">
                            <i data-lucide="search" class="w-[18px] h-[18px]"></i>
                        </button>

                        <!-- Notifications -->
                        <div class="relative" id="notification-dropdown">
                            <button id="btn-notifications" class="p-2.5 text-gray-400 hover:text-white hover:bg-white/[0.06] rounded-xl transition-all relative">
                                <i data-lucide="bell" class="w-[18px] h-[18px]"></i>
                                <span id="notification-badge" class="absolute -top-0.5 -right-0.5 w-4.5 h-4.5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center ring-2 ring-surface-900">3</span>
                            </button>
                        </div>

                        <!-- Divider -->
                        <div class="w-px h-8 bg-white/[0.06] mx-1"></div>

                        <!-- User Profile Dropdown -->
                        <div class="relative" id="profile-dropdown">
                            <button id="btn-profile" class="flex items-center gap-3 p-1.5 pr-3 hover:bg-white/[0.06] rounded-xl transition-all">
                                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-primary-500 to-violet-500 flex items-center justify-center text-white text-xs font-bold shadow-lg shadow-primary-500/25">
                                    <?= get_initials($currentUser['nama_lengkap']) ?>
                                </div>
                                <div class="hidden md:block text-left">
                                    <p class="text-sm font-semibold text-gray-200 leading-tight"><?= e(str_limit($currentUser['nama_lengkap'], 20)) ?></p>
                                    <p class="text-[11px] text-gray-500 leading-tight"><?= e(role_label($currentUser['role'])) ?></p>
                                </div>
                                <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-gray-500 hidden md:block"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div id="profile-menu" class="hidden absolute right-0 mt-2 w-64 bg-surface-800 border border-white/[0.08] rounded-2xl shadow-2xl shadow-black/40 py-2 animate-scale-in z-50">
                                <div class="px-4 py-3 border-b border-white/[0.06]">
                                    <p class="text-sm font-semibold text-white"><?= e($currentUser['nama_lengkap']) ?></p>
                                    <p class="text-xs text-gray-500 mt-0.5"><?= e($currentUser['email']) ?></p>
                                </div>
                                <div class="py-1">
                                    <a href="<?= url('profile') ?>" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-300 hover:text-white hover:bg-white/[0.04] transition-colors">
                                        <i data-lucide="user" class="w-4 h-4 text-gray-500"></i>
                                        Profil Saya
                                    </a>
                                    <a href="<?= url('settings') ?>" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-300 hover:text-white hover:bg-white/[0.04] transition-colors">
                                        <i data-lucide="settings" class="w-4 h-4 text-gray-500"></i>
                                        Pengaturan
                                    </a>
                                </div>
                                <div class="border-t border-white/[0.06] pt-1">
                                    <a href="<?= url('auth/logout') ?>" class="flex items-center gap-3 px-4 py-2.5 text-sm text-red-400 hover:text-red-300 hover:bg-red-500/[0.06] transition-colors">
                                        <i data-lucide="log-out" class="w-4 h-4"></i>
                                        Keluar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <?php endif; ?>

            <!-- Page Content -->
            <main class="flex-1 p-4 lg:p-8">
                <!-- Flash Messages -->
                <?= flash_message() ?>
