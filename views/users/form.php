<?php

/**
 * User Management Form — Create/Edit View
 */

$currentUser = $_SESSION['user'] ?? null;
$user = $user ?? null;
$action = $action ?? 'create';
$isEdit = ($action === 'edit' && $user !== null);
?>
<?php require_once ROOT_PATH . '/views/templates/header.php'; ?>
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900"><?= e($pageTitle ?? 'Pengguna') ?></h1>
            <p class="text-slate-500 text-sm mt-1"><?= $isEdit ? 'Edit data pengguna yang sudah ada' : 'Daftarkan pengguna baru ke sistem' ?></p>
        </div>
        <a href="<?= url('users') ?>" class="text-slate-600 hover:text-slate-900 font-semibold py-2.5 px-4 rounded-lg hover:bg-slate-100 transition-all flex items-center gap-2">
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
            <form method="POST" action="<?= $isEdit ? url('users/' . $user['id'] . '/update') : url('users/store') ?>" class="space-y-6">
                <input type="hidden" name="_token" value="<?= e($csrfToken ?? '') ?>">

                <!-- NIK & Nama -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nik" class="block text-sm font-semibold text-slate-700 mb-1.5">NIK / NIDN / NUPTK <span class="text-red-500">*</span></label>
                        <input type="text" id="nik" name="nik" required
                            placeholder="Contoh: 198512101234567"
                            value="<?= e($user['nik'] ?? '') ?>"
                            <?= $isEdit ? 'readonly' : '' ?>
                            class="w-full px-3 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-transparent outline-none transition-all text-sm bg-slate-50 focus:bg-white <?= $isEdit ? 'bg-slate-100 cursor-not-allowed' : '' ?>">
                        <p class="text-xs text-slate-500 mt-1">Tidak dapat diubah setelah dibuat</p>
                    </div>
                    <div>
                        <label for="nama_lengkap" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" id="nama_lengkap" name="nama_lengkap" required
                            placeholder="Contoh: Budi Santoso, S.Kom., M.T."
                            value="<?= e($user['nama_lengkap'] ?? '') ?>"
                            class="w-full px-3 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-transparent outline-none transition-all text-sm bg-slate-50 focus:bg-white">
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                    <input type="email" id="email" name="email" required
                        placeholder="budi@universitas.ac.id"
                        value="<?= e($user['email'] ?? '') ?>"
                        <?= $isEdit ? 'readonly' : '' ?>
                        class="w-full px-3 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-transparent outline-none transition-all text-sm bg-slate-50 focus:bg-white <?= $isEdit ? 'bg-slate-100 cursor-not-allowed' : '' ?>">
                    <p class="text-xs text-slate-500 mt-1">Format email institusional</p>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Password
                        <span class="text-red-500">*</span>
                        <?php if ($isEdit): ?>
                            <span class="text-xs text-slate-500 ml-2">(Kosongkan jika tidak ingin mengubah)</span>
                        <?php endif; ?>
                    </label>
                    <div class="relative">
                        <input type="password" id="password" name="password"
                            placeholder="Minimal 8 karakter"
                            <?= !$isEdit ? 'required' : '' ?>
                            class="w-full px-3 py-2.5 pr-11 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-transparent outline-none transition-all text-sm bg-slate-50 focus:bg-white">
                        <button type="button" id="togglePassword"
                            onclick="(function(){
                                                    var inp = document.getElementById('password');
                                                    var eyeOn  = document.getElementById('eye-show');
                                                    var eyeOff = document.getElementById('eye-hide');
                                                    if (inp.type === 'password') {
                                                        inp.type = 'text';
                                                        eyeOn.classList.add('hidden');
                                                        eyeOff.classList.remove('hidden');
                                                    } else {
                                                        inp.type = 'password';
                                                        eyeOn.classList.remove('hidden');
                                                        eyeOff.classList.add('hidden');
                                                    }
                                                })()"
                            title="Tampilkan / sembunyikan password"
                            class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-400 hover:text-indigo-600 transition-colors focus:outline-none">
                            <i data-lucide="eye" id="eye-show" class="w-5 h-5"></i>
                            <i data-lucide="eye-off" id="eye-hide" class="w-5 h-5 hidden"></i>
                        </button>
                    </div>
                    <p class="text-xs text-slate-500 mt-1">Gabung huruf kapital, huruf kecil, angka, dan simbol</p>
                </div>

                <!-- Role -->
                <div>
                    <label for="role" class="block text-sm font-semibold text-slate-700 mb-1.5">Role / Peran <span class="text-red-500">*</span></label>
                    <select id="role" name="role" required
                        class="w-full px-3 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-transparent outline-none transition-all text-sm bg-slate-50 focus:bg-white cursor-pointer">
                        <option value="">-- Pilih Role --</option>
                        <?php foreach (ROLES as $roleKey => $roleLabel): ?>
                            <option value="<?= e($roleKey) ?>" <?= ($user['role'] ?? '' === $roleKey ? 'selected' : '') ?>>
                                <?= e($roleLabel) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Program Studi (if applicable) -->
                <div>
                    <label for="program_studi_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Program Studi (Opsional)</label>
                    <select id="program_studi_id" name="program_studi_id"
                        class="w-full px-3 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-transparent outline-none transition-all text-sm bg-slate-50 focus:bg-white cursor-pointer">
                        <option value="">-- Tidak ada program studi --</option>
                        <?php foreach ($prodis as $prodi): ?>
                            <option value="<?= $prodi['id'] ?>"
                                <?= ($user['program_studi_id'] ?? '' == $prodi['id'] ? 'selected' : '') ?>>
                                <?= e($prodi['nama_prodi']) ?> (<?= e($prodi['kode_prodi']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <p class="text-xs text-slate-500 mt-1">Untuk Admin Prodi & Task Force</p>
                </div>

                <!-- Jabatan -->
                <div>
                    <label for="jabatan" class="block text-sm font-semibold text-slate-700 mb-1.5">Jabatan (Opsional)</label>
                    <input type="text" id="jabatan" name="jabatan"
                        placeholder="Contoh: Koordinator Akreditasi"
                        value="<?= e($user['jabatan'] ?? '') ?>"
                        class="w-full px-3 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-transparent outline-none transition-all text-sm bg-slate-50 focus:bg-white">
                </div>

                <!-- Form Actions -->
                <div class="flex gap-3 pt-6 border-t border-slate-200">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-6 rounded-lg transition-all flex items-center gap-2 shadow-md hover:shadow-lg">
                        <i data-lucide="save" class="w-5 h-5"></i>
                        <?= $isEdit ? 'Simpan Perubahan' : 'Daftarkan Pengguna' ?>
                    </button>
                    <a href="<?= url('users') ?>" class="bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold py-2.5 px-6 rounded-lg transition-all">
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
                        <li>• NIK dan Email tidak dapat diubah setelah dibuat</li>
                        <li>• Password minimal 8 karakter</li>
                        <li>• Role menentukan menu akses di aplikasi</li>
                        <li>• Program Studi hanya untuk Admin Prodi</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Role Info -->
        <div class="bg-white rounded-lg border border-slate-200 p-4">
            <h4 class="font-semibold text-slate-900 text-sm mb-3">Penjelasan Role</h4>
            <div class="space-y-2 text-xs text-slate-600">
                <div class="pb-2 border-b border-slate-200">
                    <p class="font-bold text-slate-700">Admin Universitas</p>
                    <p>Akses penuh ke semua modul sistem</p>
                </div>
                <div class="pb-2 border-b border-slate-200">
                    <p class="font-bold text-slate-700">Kepala KPMA / Kabid KPMA</p>
                    <p>Manajemen data dan monitoring akreditasi</p>
                </div>
                <div class="pb-2 border-b border-slate-200">
                    <p class="font-bold text-slate-700">Admin Prodi / Task Force</p>
                    <p>Input pengajuan dan dokumen program studi</p>
                </div>
                <div>
                    <p class="font-bold text-slate-700">Reviewer / Asesor</p>
                    <p>Review borang dan laporan banding</p>
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
                        <span class="font-semibold text-slate-900"><?= format_tanggal($user['created_at'] ?? null) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span>Diperbarui:</span>
                        <span class="font-semibold text-slate-900"><?= format_tanggal($user['updated_at'] ?? null) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span>Login Terakhir:</span>
                        <span class="font-semibold text-slate-900">
                            <?= $user['last_login'] ? format_tanggal($user['last_login'], 'datetime') : '-' ?>
                        </span>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php require_once ROOT_PATH . '/views/templates/footer.php'; ?>