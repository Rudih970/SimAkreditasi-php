<?php
/**
 * SIM Akreditasi — 403 Forbidden
 */
require_once ROOT_PATH . '/views/templates/header.php';
?>

<div class="flex items-center justify-center min-h-[60vh]">
    <div class="text-center max-w-md">
        <div class="relative mb-8">
            <div class="text-[120px] font-black text-surface-200 leading-none select-none">403</div>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-20 h-20 bg-red-50 rounded-2xl flex items-center justify-center border border-red-200">
                    <i data-lucide="shield-alert" class="w-10 h-10 text-red-500"></i>
                </div>
            </div>
        </div>

        <h1 class="text-2xl font-bold text-surface-900 mb-2">Akses Ditolak</h1>
        <p class="text-surface-500 text-sm mb-8 leading-relaxed">
            Anda tidak memiliki izin untuk mengakses halaman ini. 
            Hubungi administrator jika Anda merasa ini adalah kesalahan.
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
