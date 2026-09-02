<?php
/**
 * SIM Akreditasi — 404 Not Found
 */
require_once ROOT_PATH . '/views/templates/header.php';
?>

<div class="flex items-center justify-center min-h-[60vh]">
    <div class="text-center max-w-md">
        <!-- 404 Illustration -->
        <div class="relative mb-8">
            <div class="text-[120px] font-black text-surface-200 leading-none select-none">404</div>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-20 h-20 bg-primary-50 rounded-2xl flex items-center justify-center border border-primary-200">
                    <i data-lucide="search-x" class="w-10 h-10 text-primary-600"></i>
                </div>
            </div>
        </div>

        <h1 class="text-2xl font-bold text-surface-900 mb-2">Halaman Tidak Ditemukan</h1>
        <p class="text-surface-500 text-sm mb-8 leading-relaxed">
            Maaf, halaman yang Anda cari tidak ada atau telah dipindahkan. 
            Silakan periksa URL atau kembali ke dashboard.
        </p>

        <div class="flex items-center justify-center gap-3">
            <a href="<?= url('dashboard') ?>" class="btn btn-primary">
                <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                Ke Dashboard
            </a>
            <button onclick="history.back()" class="btn btn-outline">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Kembali
            </button>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/views/templates/footer.php'; ?>
