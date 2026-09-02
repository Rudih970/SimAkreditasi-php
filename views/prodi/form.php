<?php
/**
 * Program Studi Form — Create/Edit View
 */

$currentUser = $_SESSION['user'] ?? null;
$prodi = $prodi ?? null;
$action = $action ?? 'create';
$isEdit = ($action === 'edit' && $prodi !== null);
?>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Program Studi') ?> — <?= e(APP_NAME) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>
<body class="bg-slate-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <?php include ROOT_PATH . '/views/templates/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Navbar -->
            <?php include ROOT_PATH . '/views/templates/navbar.php'; ?>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-4 md:p-6">
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-slate-900"><?= e($pageTitle ?? 'Program Studi') ?></h1>
                            <p class="text-slate-500 text-sm mt-1"><?= $isEdit ? 'Edit data program studi yang sudah ada' : 'Tambahkan program studi baru ke sistem' ?></p>
                        </div>
                        <a href="<?= url('program-studi') ?>" class="text-slate-600 hover:text-slate-900 font-semibold py-2.5 px-4 rounded-lg hover:bg-slate-100 transition-all flex items-center gap-2">
                            <i data-lucide="arrow-left" class="w-5 h-5"></i>
                            Kembali
                        </a>
                    </div>
                </div>

                <!-- Flash Messages -->
                <?= flash_message() ?>

                <!-- Form Container -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Main Form -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-6">
                            <form method="POST" action="<?= $isEdit ? url('program-studi/' . $prodi['id'] . '/update') : url('program-studi/store') ?>" class="space-y-6">
                                <input type="hidden" name="_token" value="<?= e($csrfToken ?? '') ?>">

                                <!-- Kode & Nama Prodi -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="kode_prodi" class="block text-sm font-semibold text-slate-700 mb-1.5">Kode Program Studi <span class="text-red-500">*</span></label>
                                        <input type="text" id="kode_prodi" name="kode_prodi" required
                                               placeholder="Contoh: IF"
                                               value="<?= e($prodi['kode_prodi'] ?? '') ?>"
                                               <?= $isEdit ? 'readonly' : '' ?>
                                               class="w-full px-3 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-transparent outline-none transition-all text-sm bg-slate-50 focus:bg-white <?= $isEdit ? 'bg-slate-100 cursor-not-allowed' : '' ?>">
                                        <p class="text-xs text-slate-500 mt-1">Tidak dapat diubah setelah dibuat</p>
                                    </div>
                                    <div>
                                        <label for="nama_prodi" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Program Studi <span class="text-red-500">*</span></label>
                                        <input type="text" id="nama_prodi" name="nama_prodi" required
                                               placeholder="Contoh: Teknik Informatika"
                                               value="<?= e($prodi['nama_prodi'] ?? '') ?>"
                                               class="w-full px-3 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-transparent outline-none transition-all text-sm bg-slate-50 focus:bg-white">
                                    </div>
                                </div>

                                <!-- Jenjang -->
                                <div>
                                    <label for="jenjang" class="block text-sm font-semibold text-slate-700 mb-1.5">Jenjang Pendidikan <span class="text-red-500">*</span></label>
                                    <select id="jenjang" name="jenjang" required
                                            class="w-full px-3 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-transparent outline-none transition-all text-sm bg-slate-50 focus:bg-white cursor-pointer">
                                        <option value="">-- Pilih Jenjang --</option>
                                        <option value="S3" <?= ($prodi['jenjang'] ?? '' === 'S3' ? 'selected' : '') ?>>S3 / Doktor</option>
                                        <option value="S2" <?= ($prodi['jenjang'] ?? '' === 'S2' ? 'selected' : '') ?>>S2 / Magister</option>
                                        <option value="S1" <?= ($prodi['jenjang'] ?? '' === 'S1' ? 'selected' : '') ?>>S1 / Sarjana</option>
                                        <option value="D4" <?= ($prodi['jenjang'] ?? '' === 'D4' ? 'selected' : '') ?>>D4 / Sarjana Terapan</option>
                                        <option value="Profesi" <?= ($prodi['jenjang'] ?? '' === 'Profesi' ? 'selected' : '') ?>>Profesi</option>
                                        <option value="D3" <?= ($prodi['jenjang'] ?? '' === 'D3' ? 'selected' : '') ?>>D3 / Diploma 3</option>
                                    </select>
                                </div>

                                <!-- Fakultas -->
                                <div>
                                    <label for="fakultas" class="block text-sm font-semibold text-slate-700 mb-1.5">Fakultas <span class="text-red-500">*</span></label>
                                    <input type="text" id="fakultas" name="fakultas" required
                                           placeholder="Contoh: Fakultas Teknik"
                                           value="<?= e($prodi['fakultas'] ?? '') ?>"
                                           class="w-full px-3 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-transparent outline-none transition-all text-sm bg-slate-50 focus:bg-white">
                                </div>

                                <!-- Contact Info -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="kaprodi" class="block text-sm font-semibold text-slate-700 mb-1.5">Ketua Program Studi</label>
                                        <input type="text" id="kaprodi" name="kaprodi"
                                               placeholder="Nama ketua program studi"
                                               value="<?= e($prodi['kaprodi'] ?? '') ?>"
                                               class="w-full px-3 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-transparent outline-none transition-all text-sm bg-slate-50 focus:bg-white">
                                    </div>
                                    <div>
                                        <label for="no_telepon_prodi" class="block text-sm font-semibold text-slate-700 mb-1.5">Nomor Telepon</label>
                                        <input type="tel" id="no_telepon_prodi" name="no_telepon_prodi"
                                               placeholder="Contoh: (0274) 123-4567"
                                               value="<?= e($prodi['no_telepon_prodi'] ?? '') ?>"
                                               class="w-full px-3 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-transparent outline-none transition-all text-sm bg-slate-50 focus:bg-white">
                                    </div>
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email_prodi" class="block text-sm font-semibold text-slate-700 mb-1.5">Email Program Studi</label>
                                    <input type="email" id="email_prodi" name="email_prodi"
                                           placeholder="contoh@universitas.ac.id"
                                           value="<?= e($prodi['email_prodi'] ?? '') ?>"
                                           class="w-full px-3 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-transparent outline-none transition-all text-sm bg-slate-50 focus:bg-white">
                                </div>

                                <!-- Akreditasi Terakhir -->
                                <div>
                                    <label for="akreditasi_terakhir" class="block text-sm font-semibold text-slate-700 mb-1.5">Akreditasi Terakhir</label>
                                    <select id="akreditasi_terakhir" name="akreditasi_terakhir"
                                            class="w-full px-3 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-transparent outline-none transition-all text-sm bg-slate-50 focus:bg-white cursor-pointer">
                                        <option value="">-- Belum ada akreditasi --</option>
                                        <option value="Unggul" <?= ($prodi['akreditasi_terakhir'] ?? '' === 'Unggul' ? 'selected' : '') ?>>Unggul (A)</option>
                                        <option value="Baik" <?= ($prodi['akreditasi_terakhir'] ?? '' === 'Baik' ? 'selected' : '') ?>>Baik (B)</option>
                                        <option value="Cukup" <?= ($prodi['akreditasi_terakhir'] ?? '' === 'Cukup' ? 'selected' : '') ?>>Cukup (C)</option>
                                        <option value="Kurang" <?= ($prodi['akreditasi_terakhir'] ?? '' === 'Kurang' ? 'selected' : '') ?>>Kurang (D)</option>
                                    </select>
                                </div>

                                <!-- Form Actions -->
                                <div class="flex gap-3 pt-6 border-t border-slate-200">
                                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-6 rounded-lg transition-all flex items-center gap-2 shadow-md hover:shadow-lg">
                                        <i data-lucide="save" class="w-5 h-5"></i>
                                        <?= $isEdit ? 'Simpan Perubahan' : 'Tambah Program' ?>
                                    </button>
                                    <a href="<?= url('program-studi') ?>" class="bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold py-2.5 px-6 rounded-lg transition-all">
                                        Batal
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Sidebar Info -->
                    <div class="space-y-4">
                        <!-- Info Card -->
                        <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                            <div class="flex gap-3">
                                <div class="w-10 h-10 rounded-lg bg-indigo-200 text-indigo-600 flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="info" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-indigo-900 text-sm">Panduan Pengisian</h3>
                                    <ul class="mt-2 space-y-1 text-xs text-indigo-700">
                                        <li>• Kode program harus unik dan tidak dapat diubah</li>
                                        <li>• Jenjang harus sesuai dengan tingkat pendidikan</li>
                                        <li>• Semua field yang bertanda * harus diisi</li>
                                        <li>• Email gunakan format institusional</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Status Info -->
                        <?php if ($isEdit): ?>
                        <div class="bg-white rounded-lg border border-slate-200 p-4 space-y-2">
                            <p class="text-xs font-bold text-slate-600 uppercase tracking-wider">Informasi Sistem</p>
                            <div class="text-xs text-slate-600 space-y-1">
                                <div class="flex justify-between">
                                    <span>Dibuat:</span>
                                    <span class="font-semibold text-slate-900"><?= format_tanggal($prodi['created_at'] ?? null) ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Diperbarui:</span>
                                    <span class="font-semibold text-slate-900"><?= format_tanggal($prodi['updated_at'] ?? null) ?></span>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Help Card -->
                        <div class="bg-slate-100 rounded-lg p-4">
                            <h4 class="font-semibold text-slate-900 text-sm mb-2">Butuh Bantuan?</h4>
                            <p class="text-xs text-slate-600 mb-3">Hubungi bagian Master Data KPMA jika ada pertanyaan.</p>
                            <a href="mailto:mutu@universitas.ac.id" class="text-indigo-600 hover:text-indigo-700 text-xs font-semibold">
                                → Kirim Email
                            </a>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
