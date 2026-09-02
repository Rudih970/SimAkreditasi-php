<?php
/**
 * Program Studi List & Management View
 */
require_once ROOT_PATH . '/views/templates/header.php';
?>
                <!-- Header dengan Filter -->
                <div class="mb-6 space-y-4">
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <div>
                            <h1 class="text-2xl font-bold text-slate-900"><?= e($pageTitle ?? 'Program Studi') ?></h1>
                            <p class="text-slate-500 text-sm mt-1">Kelola data program studi universitas</p>
                        </div>
                        <a href="<?= url('program-studi/create') ?>" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-4 rounded-lg flex items-center gap-2 transition-all shadow-md hover:shadow-lg">
                            <i data-lucide="plus" class="w-5 h-5"></i>
                            Tambah Program Studi
                        </a>
                    </div>

                    <!-- Search & Filter -->
                    <div class="flex flex-col md:flex-row gap-3">
                        <form method="GET" class="flex gap-2 flex-1" id="search-form">
                            <input type="text" name="search" placeholder="Cari nama atau kode prodi..." 
                                   value="<?= e($search ?? '') ?>" 
                                   class="flex-1 px-4 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-600 focus:border-transparent">
                            <button type="submit" class="bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold px-4 py-2 rounded-lg transition-all">
                                <i data-lucide="search" class="w-5 h-5"></i>
                            </button>
                        </form>
                        <select name="jenjang" form="search-form" onchange="document.getElementById('search-form').submit()" class="px-4 py-2 border border-slate-300 rounded-lg text-sm bg-white cursor-pointer">
                            <option value="">Semua Jenjang</option>
                            <option value="S3" <?= ($jenjang === 'S3' ? 'selected' : '') ?>>S3 / Doktor</option>
                            <option value="S2" <?= ($jenjang === 'S2' ? 'selected' : '') ?>>S2 / Magister</option>
                            <option value="S1" <?= ($jenjang === 'S1' ? 'selected' : '') ?>>S1 / Sarjana</option>
                            <option value="D4" <?= ($jenjang === 'D4' ? 'selected' : '') ?>>D4 / Sarjana Terapan</option>
                            <option value="Profesi" <?= ($jenjang === 'Profesi' ? 'selected' : '') ?>>Profesi</option>
                            <option value="D3" <?= ($jenjang === 'D3' ? 'selected' : '') ?>>D3 / Diploma 3</option>
                        </select>
                    </div>

                    <!-- Flash Messages -->
                    <?= flash_message() ?>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-2 md:grid-cols-6 gap-4 mb-6">
                    <?php 
                    $colors = [
                        'S3' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-700', 'icon' => 'award'],
                        'S2' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'icon' => 'book-open'],
                        'S1' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'icon' => 'graduation-cap'],
                        'D4' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'icon' => 'briefcase'],
                        'Profesi' => ['bg' => 'bg-rose-50', 'text' => 'text-rose-700', 'icon' => 'stethoscope'],
                        'D3' => ['bg' => 'bg-orange-50', 'text' => 'text-orange-700', 'icon' => 'layers'],
                    ];
                    
                    foreach ($colors as $jenjangName => $style):
                        $stats = $jenjangStats[$jenjangName] ?? ['total' => 0, 'active' => 0, 'expired' => 0];
                    ?>
                    <div class="<?= $style['bg'] ?> rounded-lg p-4 border <?= str_replace('bg', 'border', $style['bg']) ?>">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-600"><?= e($jenjangName) ?></p>
                            <i data-lucide="<?= $style['icon'] ?>" class="w-4 h-4 <?= $style['text'] ?>"></i>
                        </div>
                        <p class="text-2xl font-bold <?= $style['text'] ?>"><?= $stats['total'] ?? 0 ?></p>
                        <p class="text-[10px] text-slate-600 mt-1">Total Program</p>
                        <?php if (($stats['expired'] ?? 0) > 0): ?>
                            <span class="inline-block mt-2 px-2 py-1 bg-red-100 text-red-700 text-[10px] font-bold rounded">
                                <?= $stats['expired'] ?> Expired
                            </span>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Table -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                    <?php if (!empty($prodis)): ?>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-slate-50 border-b border-slate-200">
                                    <tr>
                                        <th class="px-6 py-3 text-left font-bold text-slate-700">Program Studi</th>
                                        <th class="px-6 py-3 text-left font-bold text-slate-700">Jenjang</th>
                                        <th class="px-6 py-3 text-left font-bold text-slate-700">Fakultas</th>
                                        <th class="px-6 py-3 text-left font-bold text-slate-700">Akreditasi</th>
                                        <th class="px-6 py-3 text-center font-bold text-slate-700">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200">
                                    <?php foreach ($prodis as $prodi): ?>
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-6 py-3">
                                            <div class="font-semibold text-slate-900"><?= e($prodi['nama_prodi']) ?></div>
                                            <div class="text-xs text-slate-500"><?= e($prodi['kode_prodi']) ?></div>
                                        </td>
                                        <td class="px-6 py-3">
                                            <span class="inline-block px-2.5 py-1 bg-slate-100 text-slate-700 text-xs font-bold rounded">
                                                <?= e($prodi['jenjang']) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-3 text-slate-600"><?= e($prodi['fakultas']) ?></td>
                                        <td class="px-6 py-3">
                                            <?php if ($prodi['akreditasi_terakhir']): ?>
                                                <span class="inline-block px-2.5 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded">
                                                    <?= e($prodi['akreditasi_terakhir']) ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-slate-400 text-xs">Belum ada</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-3 text-center">
                                            <div class="flex justify-center gap-2">
                                                <a href="<?= url('program-studi/' . $prodi['id'] . '/edit') ?>" 
                                                   class="p-2 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded transition-colors"
                                                   title="Edit">
                                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                                </a>
                                                <form method="POST" action="<?= url('program-studi/' . $prodi['id'] . '/delete') ?>" 
                                                      class="inline" 
                                                      onsubmit="return confirm('Yakin ingin menghapus program studi ini?')">
                                                    <input type="hidden" name="_token" value="<?= e($csrfToken ?? '') ?>">
                                                    <button type="submit" class="p-2 bg-red-50 hover:bg-red-100 text-red-600 rounded transition-colors" title="Hapus">
                                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-between text-sm">
                            <span class="text-slate-600">
                                Menampilkan <?= (($page - 1) * 15) + 1 ?> – <?= min($page * 15, $total) ?> dari <?= $total ?> program
                            </span>
                            <div class="flex gap-1">
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <a href="<?= url('program-studi?page=' . $i) ?>" 
                                       class="px-3 py-2 rounded-lg transition-all <?= ($page === $i ? 'bg-indigo-600 text-white font-bold' : 'bg-slate-100 text-slate-700 hover:bg-slate-200') ?>">
                                        <?= $i ?>
                                    </a>
                                <?php endfor; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="px-6 py-12 text-center">
                            <i data-lucide="inbox" class="w-12 h-12 text-slate-300 mx-auto mb-3"></i>
                            <p class="text-slate-500 font-medium">Tidak ada program studi yang ditemukan</p>
                            <a href="<?= url('program-studi/create') ?>" class="mt-3 inline-block text-indigo-600 hover:text-indigo-700 text-sm font-semibold">
                                Tambahkan yang pertama →
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
<?php require_once ROOT_PATH . '/views/templates/footer.php'; ?>
