<?php
/**
 * SIM Akreditasi — Dashboard View
 */
require_once ROOT_PATH . '/views/templates/header.php';
?>

<!-- Dashboard Header -->
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-surface-900 tracking-tight">Dashboard</h1>
            <p class="text-surface-500 text-sm mt-1">Selamat datang kembali, <span class="text-surface-700"><?= e($currentUser['nama_lengkap'] ?? '') ?></span></p>
        </div>
        <div class="flex items-center gap-2 text-sm text-surface-500">
            <i data-lucide="calendar" class="w-4 h-4"></i>
            <span><?= format_tanggal(date('Y-m-d'), 'long') ?></span>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <!-- Total Program Studi -->
    <div class="stat-card indigo">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-indigo-500/10 rounded-xl flex items-center justify-center">
                <i data-lucide="graduation-cap" class="w-5 h-5 text-indigo-500"></i>
            </div>
            <span class="text-xs text-surface-500 font-medium">Prodi</span>
        </div>
        <p class="text-2xl font-bold text-surface-900"><?= format_number($stats['total_prodi']) ?></p>
        <p class="text-xs text-surface-400 mt-1">Program Studi Aktif</p>
    </div>

    <!-- Pengajuan Aktif -->
    <div class="stat-card emerald">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-emerald-500/10 rounded-xl flex items-center justify-center">
                <i data-lucide="file-check" class="w-5 h-5 text-emerald-500"></i>
            </div>
            <span class="text-xs text-surface-500 font-medium">Aktif</span>
        </div>
        <p class="text-2xl font-bold text-surface-900"><?= format_number($stats['pengajuan_aktif']) ?></p>
        <p class="text-xs text-surface-400 mt-1">Pengajuan Berjalan</p>
    </div>

    <!-- Review Pending -->
    <div class="stat-card amber">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-amber-500/10 rounded-xl flex items-center justify-center">
                <i data-lucide="scan-search" class="w-5 h-5 text-amber-500"></i>
            </div>
            <span class="text-xs text-surface-500 font-medium">Review</span>
        </div>
        <p class="text-2xl font-bold text-surface-900"><?= format_number($stats['review_pending']) ?></p>
        <p class="text-xs text-surface-400 mt-1">Review Menunggu</p>
    </div>

    <!-- Jadwal Aktif -->
    <div class="stat-card rose">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-rose-500/10 rounded-xl flex items-center justify-center">
                <i data-lucide="calendar-days" class="w-5 h-5 text-rose-500"></i>
            </div>
            <span class="text-xs text-surface-500 font-medium">Jadwal</span>
        </div>
        <p class="text-2xl font-bold text-surface-900"><?= format_number($stats['jadwal_aktif']) ?></p>
        <p class="text-xs text-surface-400 mt-1">Kegiatan Mendatang</p>
    </div>
</div>

<!-- Jenjang Program Studi -->
<div class="mb-8">
    <h2 class="text-lg font-semibold text-surface-900 mb-4">Jenjang Program Studi</h2>
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        <?php 
        $jenjangConfig = [
            'S3'       => ['label' => 'S3 / DOKTOR', 'icon' => 'award', 'color' => 'purple'],
            'S2'       => ['label' => 'S2 / MAGISTER', 'icon' => 'book-open', 'color' => 'blue'],
            'S1'       => ['label' => 'S1 / SARJANA', 'icon' => 'graduation-cap', 'color' => 'emerald'],
            'D4'       => ['label' => 'D4 / SARJ. TERAPAN', 'icon' => 'briefcase', 'color' => 'amber'],
            'Profesi'  => ['label' => 'PROFESI', 'icon' => 'stethoscope', 'color' => 'rose'],
            'D3'       => ['label' => 'D3 / DIPLOMA 3', 'icon' => 'layers', 'color' => 'orange'],
        ];
        foreach ($jenjangConfig as $jenjang => $config):
            $data = $jenjangBreakdown[$jenjang] ?? ['total' => 0, 'active' => 0, 'expired' => 0];
            $colorClass = match($config['color']) {
                'purple' => 'border-purple-200 bg-purple-50 hover:bg-purple-100',
                'blue' => 'border-blue-200 bg-blue-50 hover:bg-blue-100',
                'emerald' => 'border-emerald-200 bg-emerald-50 hover:bg-emerald-100',
                'amber' => 'border-amber-200 bg-amber-50 hover:bg-amber-100',
                'rose' => 'border-rose-200 bg-rose-50 hover:bg-rose-100',
                'orange' => 'border-orange-200 bg-orange-50 hover:bg-orange-100',
            };
            $textColor = match($config['color']) {
                'purple' => 'text-purple-600',
                'blue' => 'text-blue-600',
                'emerald' => 'text-emerald-600',
                'amber' => 'text-amber-600',
                'rose' => 'text-rose-600',
                'orange' => 'text-orange-600',
            };
        ?>
        <div class="glass-card p-4 border <?= $colorClass ?> transition-colors cursor-pointer">
            <div class="flex flex-col items-center text-center gap-2">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-white/50">
                    <i data-lucide="<?= $config['icon'] ?>" class="w-5 h-5 <?= $textColor ?>"></i>
                </div>
                <p class="text-2xl font-bold text-surface-900"><?= $data['total'] ?></p>
                <p class="text-[11px] text-surface-600 font-medium uppercase tracking-wide"><?= $config['label'] ?></p>
                <?php if ($data['expired'] > 0): ?>
                    <div class="w-full mt-1 pt-2 border-t border-current/10">
                        <span class="text-[10px] text-rose-600 font-semibold"><?= $data['expired'] ?> Kadaluarsa</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Pemantauan Alur Administrasi Aktif -->
<div class="mb-8">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-surface-900">Pemantauan Alur Administrasi Aktif</h2>
        <a href="<?= url('program-studi') ?>" class="text-sm text-primary-600 hover:text-primary-700 font-medium transition-colors flex items-center gap-1">
            Kelola Semua Prodi <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </a>
    </div>

    <div class="glass-card overflow-hidden">
        <?php if (empty($monitoringData)): ?>
            <div class="px-6 py-12 text-center">
                <div class="w-12 h-12 bg-surface-50 rounded-xl flex items-center justify-center mx-auto mb-3 border border-surface-200">
                    <i data-lucide="inbox" class="w-5 h-5 text-surface-400"></i>
                </div>
                <p class="text-surface-500 text-sm">Tidak ada data program studi</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-surface-50 border-b border-surface-200">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold text-surface-700">Program Studi</th>
                            <th class="px-6 py-3 text-left font-semibold text-surface-700">Fakultas</th>
                            <th class="px-6 py-3 text-left font-semibold text-surface-700">Status Akreditasi</th>
                            <th class="px-6 py-3 text-center font-semibold text-surface-700">Progress</th>
                            <th class="px-6 py-3 text-center font-semibold text-surface-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-100">
                        <?php foreach ($monitoringData as $data): 
                            $progressPercent = 0;
                            if ($data['total_stages'] > 0) {
                                $progressPercent = round(($data['completed_stages'] / $data['total_stages']) * 100);
                            }
                            $statusClass = match($data['status_pengajuan'] ?? null) {
                                'draft' => 'bg-slate-100 text-slate-700',
                                'diajukan' => 'bg-blue-100 text-blue-700',
                                'review' => 'bg-amber-100 text-amber-700',
                                'revisi' => 'bg-orange-100 text-orange-700',
                                'disetujui' => 'bg-emerald-100 text-emerald-700',
                                'ditolak' => 'bg-rose-100 text-rose-700',
                                'selesai' => 'bg-purple-100 text-purple-700',
                                default => 'bg-surface-100 text-surface-700',
                            };
                        ?>
                        <tr class="hover:bg-surface-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full" style="background-color: <?= match($data['jenjang']) {
                                        'S3' => '#a855f7',
                                        'S2' => '#3b82f6',
                                        'S1' => '#10b981',
                                        'D4' => '#f59e0b',
                                        'Profesi' => '#f43f5e',
                                        'D3' => '#f97316',
                                        default => '#6b7280'
                                    } ?>"></div>
                                    <div>
                                        <p class="font-semibold text-surface-900"><?= e($data['nama_prodi']) ?></p>
                                        <p class="text-xs text-surface-500"><?= e($data['jenjang']) ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-surface-700"><?= e($data['fakultas']) ?></td>
                            <td class="px-6 py-4">
                                <div>
                                    <?php if (!empty($data['status_pengajuan'])): ?>
                                        <span class="inline-block px-2.5 py-1 rounded-full text-xs font-medium <?= $statusClass ?>">
                                            <?= ucfirst(str_replace('_', ' ', $data['status_pengajuan'])) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-block px-2.5 py-1 rounded-full text-xs font-medium bg-surface-100 text-surface-700">
                                            No Status
                                        </span>
                                    <?php endif; ?>
                                    <?php if ($data['akreditasi_terakhir']): ?>
                                        <p class="text-xs text-surface-500 mt-1">
                                            Akreditasi: <span class="font-medium"><?= e($data['akreditasi_terakhir']) ?></span>
                                            <?php if ($data['tanggal_kadaluarsa']): ?>
                                                <br>Kadaluarsa: <?= format_tanggal($data['tanggal_kadaluarsa'], 'short') ?>
                                            <?php endif; ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col items-center gap-2">
                                    <div class="w-20 bg-surface-200 rounded-full h-1.5">
                                        <div class="bg-gradient-to-r from-primary-500 to-primary-600 h-full rounded-full transition-all" 
                                             style="width: <?= $progressPercent ?>%"></div>
                                    </div>
                                    <span class="text-xs font-medium text-surface-600">
                                        <?= $progressPercent ?>% (<?= $data['completed_stages'] ?>/<?= $data['total_stages'] ?>)
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="<?= url('pengajuan/' . ($data['pengajuan_id'] ?? '')) ?>" 
                                   class="text-primary-600 hover:text-primary-700 transition-colors"
                                   title="Lihat Detail">
                                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Left Column: Pengajuan Terbaru (2/3 width) -->
    <div class="lg:col-span-2 space-y-6">

        <!-- Pengajuan Terbaru -->
        <div class="glass-card overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-surface-200">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-primary-50 rounded-lg flex items-center justify-center">
                        <i data-lucide="file-check" class="w-4 h-4 text-primary-600"></i>
                    </div>
                    <h2 class="text-sm font-semibold text-surface-900">Pengajuan Terbaru</h2>
                </div>
                <?php if (is_role(['admin_universitas', 'kepala_kpma', 'kabid_kpma', 'admin_prodi'])): ?>
                    <a href="<?= url('pengajuan') ?>" class="text-xs text-primary-600 hover:text-primary-700 font-medium transition-colors">
                        Lihat Semua →
                    </a>
                <?php endif; ?>
            </div>

            <?php if (empty($pengajuanTerbaru)): ?>
                <div class="px-6 py-12 text-center">
                    <div class="w-12 h-12 bg-surface-50 rounded-xl flex items-center justify-center mx-auto mb-3 border border-surface-200">
                        <i data-lucide="inbox" class="w-5 h-5 text-surface-400"></i>
                    </div>
                    <p class="text-surface-500 text-sm">Belum ada pengajuan akreditasi</p>
                </div>
            <?php else: ?>
                <div class="divide-y divide-surface-100">
                    <?php foreach ($pengajuanTerbaru as $paj): ?>
                        <div class="px-6 py-4 hover:bg-surface-50 transition-colors">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <p class="text-sm font-semibold text-surface-800 truncate"><?= e($paj['nama_prodi']) ?></p>
                                        <span class="text-[10px] text-surface-600 bg-surface-100 px-1.5 py-0.5 rounded font-medium"><?= e($paj['jenjang']) ?></span>
                                    </div>
                                    <p class="text-xs text-surface-500">
                                        <?= e($paj['nomor_pengajuan']) ?> &bull;
                                        <?= e(ucfirst(str_replace('_', ' ', $paj['jenis_pengajuan']))) ?> &bull;
                                        <?= format_tanggal($paj['tanggal_pengajuan'], 'short') ?>
                                    </p>
                                </div>
                                <div class="flex-shrink-0">
                                    <?= status_badge($paj['status']) ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Jadwal Mendatang -->
        <div class="glass-card overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-surface-200">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-amber-50 rounded-lg flex items-center justify-center">
                        <i data-lucide="calendar-days" class="w-4 h-4 text-amber-600"></i>
                    </div>
                    <h2 class="text-sm font-semibold text-surface-900">Jadwal Mendatang</h2>
                </div>
                <a href="<?= url('jadwal') ?>" class="text-xs text-primary-600 hover:text-primary-700 font-medium transition-colors">
                    Lihat Semua →
                </a>
            </div>

            <?php if (empty($jadwalMendatang)): ?>
                <div class="px-6 py-12 text-center">
                    <div class="w-12 h-12 bg-surface-50 rounded-xl flex items-center justify-center mx-auto mb-3 border border-surface-200">
                        <i data-lucide="calendar-x" class="w-5 h-5 text-surface-400"></i>
                    </div>
                    <p class="text-surface-500 text-sm">Tidak ada jadwal mendatang</p>
                </div>
            <?php else: ?>
                <div class="divide-y divide-surface-100">
                    <?php foreach ($jadwalMendatang as $jdw): ?>
                        <div class="px-6 py-4 hover:bg-surface-50 transition-colors">
                            <div class="flex items-start gap-4">
                                <!-- Date Box -->
                                <div class="flex-shrink-0 w-12 h-14 bg-primary-50 rounded-xl flex flex-col items-center justify-center border border-primary-100">
                                    <span class="text-lg font-bold text-primary-600 leading-none"><?= date('d', strtotime($jdw['tanggal_mulai'])) ?></span>
                                    <span class="text-[10px] text-primary-600/70 font-medium uppercase mt-0.5"><?= format_tanggal($jdw['tanggal_mulai'], 'month_short') ?></span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-surface-800 mb-1 truncate"><?= e($jdw['judul_kegiatan']) ?></p>
                                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-surface-500">
                                        <span class="flex items-center gap-1">
                                            <i data-lucide="graduation-cap" class="w-3 h-3"></i>
                                            <?= e($jdw['nama_prodi']) ?>
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <i data-lucide="clock" class="w-3 h-3"></i>
                                            <?= date('H:i', strtotime($jdw['tanggal_mulai'])) ?> WIB
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <?= status_badge($jdw['status']) ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Right Column: Activity & Quick Stats (1/3 width) -->
    <div class="space-y-6">

        <!-- Quick Stats -->
        <div class="glass-card p-6">
            <h3 class="text-sm font-semibold text-surface-900 mb-4 flex items-center gap-2">
                <i data-lucide="bar-chart-3" class="w-4 h-4 text-surface-500"></i>
                Ringkasan Sistem
            </h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs text-surface-500">Total Pengguna</span>
                    <span class="text-sm font-semibold text-surface-800"><?= format_number($stats['total_users']) ?></span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-surface-500">Total Pengajuan</span>
                    <span class="text-sm font-semibold text-surface-800"><?= format_number($stats['total_pengajuan']) ?></span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-surface-500">Dokumen Borang</span>
                    <span class="text-sm font-semibold text-surface-800"><?= format_number($stats['total_borang']) ?></span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-surface-500">Total Review</span>
                    <span class="text-sm font-semibold text-surface-800"><?= format_number($stats['total_review']) ?></span>
                </div>
            </div>
        </div>

        <!-- Aktivitas Terbaru -->
        <div class="glass-card overflow-hidden">
            <div class="px-6 py-4 border-b border-surface-200">
                <h3 class="text-sm font-semibold text-surface-900 flex items-center gap-2">
                    <i data-lucide="activity" class="w-4 h-4 text-surface-500"></i>
                    Aktivitas Terbaru
                </h3>
            </div>

            <?php if (empty($aktivitasTerbaru)): ?>
                <div class="px-6 py-8 text-center">
                    <p class="text-surface-500 text-sm">Belum ada aktivitas</p>
                </div>
            <?php else: ?>
                <div class="divide-y divide-surface-100">
                    <?php foreach ($aktivitasTerbaru as $log): ?>
                        <div class="px-6 py-3 hover:bg-surface-50 transition-colors">
                            <div class="flex items-start gap-3">
                                <div class="w-7 h-7 rounded-lg bg-surface-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i data-lucide="<?= match($log['modul']) {
                                        'auth'     => 'log-in',
                                        'pengajuan'=> 'file-check',
                                        'borang'   => 'upload',
                                        'review'   => 'scan-search',
                                        'jadwal'   => 'calendar',
                                        'laporan'  => 'file-bar-chart',
                                        default    => 'activity',
                                    } ?>" class="w-3.5 h-3.5 text-surface-500"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs text-surface-700">
                                        <span class="font-medium text-surface-900"><?= e($log['nama_lengkap'] ?? 'System') ?></span>
                                        <span class="text-surface-500"><?= e(str_limit($log['aktivitas'], 40)) ?></span>
                                    </p>
                                    <p class="text-[10px] text-surface-400 mt-0.5"><?= format_tanggal($log['created_at'], 'datetime') ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- User Role Info -->
        <div class="glass-card p-6">
            <h3 class="text-sm font-semibold text-surface-900 mb-3 flex items-center gap-2">
                <i data-lucide="shield" class="w-4 h-4 text-surface-500"></i>
                Akses Anda
            </h3>
            <div class="mb-3">
                <?= role_badge($currentUser['role'] ?? '') ?>
            </div>
            <p class="text-xs text-surface-500 leading-relaxed">
                <?php
                $roleDesc = match($currentUser['role'] ?? '') {
                    'admin_universitas'  => 'Anda memiliki akses penuh ke seluruh fitur sistem termasuk manajemen pengguna dan pengaturan.',
                    'kepala_kpma'        => 'Anda dapat menyetujui pengajuan, melihat seluruh laporan, dan memonitor proses akreditasi.',
                    'kabid_kpma'         => 'Anda dapat mengelola jadwal pendampingan, mereview dokumen, dan menyetujui laporan kegiatan.',
                    'reviewer_internal'  => 'Anda bertugas mereview dokumen borang dan memberikan rekomendasi penilaian.',
                    'asesor_internal'    => 'Anda bertugas melakukan desk evaluation dan penilaian substansi dokumen akreditasi.',
                    'admin_prodi'        => 'Anda dapat mengelola pengajuan dan dokumen borang untuk program studi Anda.',
                    'team_task_force'    => 'Anda bertugas menyiapkan dan mengupload dokumen borang serta mengikuti kegiatan pendampingan.',
                    default              => 'Akses Anda terbatas sesuai peran yang diberikan.',
                };
                echo e($roleDesc);
                ?>
            </p>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/views/templates/footer.php'; ?>
