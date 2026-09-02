<?php
/**
 * SIM Akreditasi — Dashboard Prodi & Task Force
 */
require_once ROOT_PATH . '/views/templates/header.php';
?>

<div class="max-w-6xl mx-auto space-y-8 animate-fade-in">
    
    <!-- Header -->
    <div class="mb-4">
        <h1 class="text-2xl font-bold text-surface-900 tracking-tight">Dashboard Alur</h1>
        <p class="text-surface-500 text-sm mt-1">Pantau progres tahapan akreditasi program studi Anda.</p>
    </div>

    <!-- Stage Tracker Visual -->
    <div class="glass-card p-6 md:p-8">
        <h2 class="text-xl font-bold text-surface-900 mb-8 text-center">Status Tahapan Alur Akreditasi</h2>
        <div class="relative max-w-4xl mx-auto">
            <div class="absolute top-6 left-0 w-full h-1 bg-surface-200 rounded z-0"></div>
            <div class="absolute top-6 left-0 w-[33%] h-1 bg-primary-600 rounded z-0"></div>

            <div class="relative z-10 flex justify-between">
                <!-- Step 1 -->
                <a href="<?= url('borang') ?>" class="flex flex-col items-center group cursor-pointer hover:opacity-80 transition-opacity">
                    <div class="w-12 h-12 rounded-full bg-primary-600 text-white flex items-center justify-center font-bold border-4 border-white shadow-md">
                        <i data-lucide="folder-up" class="w-5 h-5"></i>
                    </div>
                    <span class="text-sm font-bold text-primary-700 mt-3 text-center">1. Tahap Persiapan</span>
                    <span class="text-xs text-surface-500 mt-1">Unggah Borang</span>
                </a>

                <!-- Step 2 -->
                <a href="<?= url('jadwal') ?>" class="flex flex-col items-center group cursor-pointer hover:opacity-80 transition-opacity">
                    <div class="w-12 h-12 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center font-bold border-4 border-white shadow-sm">
                        <i data-lucide="calendar" class="w-5 h-5"></i>
                    </div>
                    <span class="text-sm font-semibold text-surface-600 mt-3 text-center">2. Penjadwalan</span>
                    <span class="text-xs text-surface-400 mt-1">Sesi Pendampingan</span>
                </a>

                <!-- Step 3 -->
                <div class="flex flex-col items-center opacity-50">
                    <div class="w-12 h-12 rounded-full bg-surface-100 text-surface-400 flex items-center justify-center font-bold border-4 border-white shadow-sm">
                        <i data-lucide="send" class="w-5 h-5"></i>
                    </div>
                    <span class="text-sm font-medium text-surface-500 mt-3 text-center">3. Submit Lembaga</span>
                    <span class="text-xs text-surface-400 mt-1">Terkunci</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Current Task Cards -->
        <div class="lg:col-span-2 space-y-6">
            <h3 class="text-lg font-bold text-surface-900">Tugas Anda Saat Ini</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Card 1 -->
                <div class="glass-card flex flex-col overflow-hidden relative border border-primary-100">
                    <div class="absolute top-0 left-0 w-1 h-full bg-primary-500"></div>
                    <div class="p-5 border-b border-surface-100 flex-1 pl-6">
                        <span class="px-2.5 py-1 text-xs font-semibold bg-primary-50 text-primary-700 rounded-md">Persiapan</span>
                        <h4 class="font-bold text-surface-900 mt-2 mb-1">Unggah Dokumen Borang</h4>
                        <p class="text-xs text-surface-500 line-clamp-2 mb-4">Lengkapi Laporan Evaluasi Diri (LED) dan Laporan Kinerja Program Studi (LKPS).</p>
                        <div class="flex justify-between text-xs text-surface-500 mb-1">
                            <span>Progres Dokumen</span>
                            <span class="font-bold text-surface-700">65%</span>
                        </div>
                        <div class="w-full bg-surface-100 rounded-full h-1.5">
                            <div class="bg-primary-500 h-1.5 rounded-full" style="width: 65%"></div>
                        </div>
                    </div>
                    <div class="bg-surface-50 p-3 pl-6">
                        <a href="<?= url('borang') ?>" class="w-full py-2 bg-white border border-surface-200 rounded-lg text-sm font-medium text-primary-600 hover:bg-primary-50 transition-colors flex justify-center items-center gap-2">
                            Mulai Unggah <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="glass-card flex flex-col overflow-hidden relative border border-amber-200">
                    <div class="absolute top-0 left-0 w-1 h-full bg-amber-400"></div>
                    <div class="p-5 border-b border-surface-100 flex-1 pl-6">
                        <span class="px-2.5 py-1 text-xs font-semibold bg-amber-50 text-amber-700 rounded-md">Bimbingan</span>
                        <h4 class="font-bold text-surface-900 mt-2 mb-1">Jadwal Pendampingan</h4>
                        <p class="text-xs text-surface-500 line-clamp-2 mb-4">Melihat detail mentor, jadwal, dan pendampingan lapangan.</p>
                        <div class="flex justify-between text-xs text-surface-500 mb-1">
                            <span>Kesiapan Jadwal</span>
                            <span class="font-bold text-surface-700">50%</span>
                        </div>
                        <div class="w-full bg-surface-100 rounded-full h-1.5">
                            <div class="bg-amber-400 h-1.5 rounded-full" style="width: 50%"></div>
                        </div>
                    </div>
                    <div class="bg-surface-50 p-3 pl-6">
                        <a href="<?= url('jadwal') ?>" class="w-full py-2 bg-white border border-surface-200 rounded-lg text-sm font-medium text-surface-600 hover:bg-surface-100 transition-colors flex justify-center items-center gap-2">
                            Buka Penjadwalan <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Team Task Force Panel -->
        <div class="glass-card p-5 flex flex-col h-fit">
            <div class="flex items-center justify-between pb-4 border-b border-surface-100 mb-4">
                <div class="flex items-center gap-2">
                    <i data-lucide="shield" class="w-5 h-5 text-primary-600"></i>
                    <h3 class="font-bold text-surface-900 text-base">Tim Task Force</h3>
                </div>
            </div>
            <div class="space-y-3 max-h-[280px] overflow-y-auto pr-1">
                <!-- Data Mock/Static untuk Tim Task Force -->
                <div class="flex items-center justify-between p-3 bg-surface-50 rounded-lg border border-surface-100 hover:border-surface-200 transition-colors group">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-surface-700 truncate"><?= e($currentUser['nama_lengkap'] ?? 'Nama Anda') ?></p>
                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold border mt-1 bg-primary-50 text-primary-700 border-primary-100">Ketua</span>
                    </div>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-surface-50 rounded-lg border border-surface-100 hover:border-surface-200 transition-colors group">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-surface-700 truncate">Rina Wijayanti, M.Kom.</p>
                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold border mt-1 bg-surface-100 text-surface-700 border-surface-200">Anggota</span>
                    </div>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-surface-50 rounded-lg border border-surface-100 hover:border-surface-200 transition-colors group">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-surface-700 truncate">Dr. Ir. Hermawan, M.T.</p>
                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold border mt-1 bg-red-50 text-red-700 border-red-100">Penanggung Jawab</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/views/templates/footer.php'; ?>
