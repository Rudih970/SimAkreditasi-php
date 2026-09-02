<?php
/**
 * Top Navbar Component
 * Header dengan profile dan status
 */
?>

<header class="bg-white border-b border-slate-200 h-16 flex items-center justify-between px-6 z-10 shrink-0">
    <div class="flex items-center gap-4">
        <div class="text-sm font-semibold text-slate-700" id="header-title">
            Sistem Penjaminan Mutu
        </div>
    </div>
    <div class="flex items-center gap-4">
        <?php if (in_array($currentUser['role'] ?? '', ['admin_prodi', 'team_task_force'])): ?>
            <span id="header-status-badge" class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-bold rounded-full border border-yellow-200">
                Status Aktivitas: Tahap Persiapan
            </span>
        <?php endif; ?>
        
        <div class="flex items-center gap-3 border-l border-slate-200 pl-4">
            <div class="text-right hidden sm:block">
                <p class="text-xs font-bold text-slate-800"><?= e($currentUser['role'] ?? 'User') ?></p>
                <p class="text-[10px] text-slate-500 font-semibold">
                    <?php 
                    if (in_array($currentUser['role'] ?? '', ['admin_prodi', 'team_task_force'])) {
                        echo "T. Informatika";
                    } else {
                        echo "Kantor Pusat Mutu";
                    }
                    ?>
                </p>
            </div>
            <div class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm border border-indigo-200 select-none">
                <?= get_initials($currentUser['nama_lengkap'] ?? 'User') ?>
            </div>
        </div>
    </div>
</header>
