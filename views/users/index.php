<?php
/**
 * User Management List View
 */

$currentUser = $_SESSION['user'] ?? null;
?>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Manajemen Akun') ?> — <?= e(APP_NAME) ?></title>
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
                <!-- Header -->
                <div class="mb-6">
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <div>
                            <h1 class="text-2xl font-bold text-slate-900"><?= e($pageTitle ?? 'Manajemen Akun') ?></h1>
                            <p class="text-slate-500 text-sm mt-1">Kelola pengguna dan role akses sistem</p>
                        </div>
                        <a href="<?= url('users/create') ?>" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-4 rounded-lg flex items-center gap-2 transition-all shadow-md hover:shadow-lg">
                            <i data-lucide="plus" class="w-5 h-5"></i>
                            Tambah Pengguna
                        </a>
                    </div>

                    <!-- Search & Filter -->
                    <div class="mt-4 flex flex-col md:flex-row gap-3">
                        <form method="GET" class="flex gap-2 flex-1" id="search-form">
                            <input type="text" name="search" placeholder="Cari nama, email, atau NIP..." 
                                   value="<?= e($search ?? '') ?>" 
                                   class="flex-1 px-4 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-600 focus:border-transparent">
                            <button type="submit" class="bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold px-4 py-2 rounded-lg transition-all">
                                <i data-lucide="search" class="w-5 h-5"></i>
                            </button>
                        </form>
                        <select name="role" form="search-form" onchange="document.getElementById('search-form').submit()" class="px-4 py-2 border border-slate-300 rounded-lg text-sm bg-white cursor-pointer">
                            <option value="">Semua Role</option>
                            <?php foreach (ROLES as $roleKey => $roleLabel): ?>
                                <option value="<?= e($roleKey) ?>" <?= ($role === $roleKey ? 'selected' : '') ?>>
                                    <?= e($roleLabel) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <select name="status" form="search-form" onchange="document.getElementById('search-form').submit()" class="px-4 py-2 border border-slate-300 rounded-lg text-sm bg-white cursor-pointer">
                            <option value="">Semua Status</option>
                            <option value="active" <?= ($status === 'active' ? 'selected' : '') ?>>Aktif</option>
                            <option value="inactive" <?= ($status === 'inactive' ? 'selected' : '') ?>>Nonaktif</option>
                        </select>
                    </div>

                    <!-- Flash Messages -->
                    <?= flash_message() ?>
                </div>

                <!-- Role Stats -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <?php foreach ($roleStats as $roleKey => $stat): ?>
                        <div class="bg-white rounded-lg border border-slate-200 p-4 shadow-sm">
                            <p class="text-xs font-bold text-slate-600 uppercase tracking-wider"><?= e($stat['label']) ?></p>
                            <p class="text-2xl font-bold text-indigo-600 mt-2"><?= $stat['count'] ?></p>
                            <p class="text-xs text-slate-500 mt-1">Pengguna Aktif</p>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Table -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                    <?php if (!empty($users)): ?>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-slate-50 border-b border-slate-200">
                                    <tr>
                                        <th class="px-6 py-3 text-left font-bold text-slate-700">Pengguna</th>
                                        <th class="px-6 py-3 text-left font-bold text-slate-700">Email</th>
                                        <th class="px-6 py-3 text-left font-bold text-slate-700">Role</th>
                                        <th class="px-6 py-3 text-center font-bold text-slate-700">Status</th>
                                        <th class="px-6 py-3 text-center font-bold text-slate-700">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200">
                                    <?php foreach ($users as $user): ?>
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-6 py-3">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm">
                                                    <?= get_initials($user['nama_lengkap']) ?>
                                                </div>
                                                <div>
                                                    <div class="font-semibold text-slate-900"><?= e($user['nama_lengkap']) ?></div>
                                                    <div class="text-xs text-slate-500"><?= e($user['nip']) ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-3 text-slate-600"><?= e($user['email']) ?></td>
                                        <td class="px-6 py-3">
                                            <?= role_badge($user['role']) ?>
                                        </td>
                                        <td class="px-6 py-3 text-center">
                                            <?php if ($user['is_active']): ?>
                                                <span class="inline-block px-2.5 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded">Aktif</span>
                                            <?php else: ?>
                                                <span class="inline-block px-2.5 py-1 bg-slate-100 text-slate-700 text-xs font-bold rounded">Nonaktif</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-3 text-center">
                                            <div class="flex justify-center gap-2">
                                                <a href="<?= url('users/' . $user['id'] . '/edit') ?>" 
                                                   class="p-2 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded transition-colors"
                                                   title="Edit">
                                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                                </a>
                                                <?php if ($user['is_active']): ?>
                                                    <form method="POST" action="<?= url('users/' . $user['id'] . '/deactivate') ?>" class="inline">
                                                        <input type="hidden" name="_token" value="<?= e($csrfToken ?? '') ?>">
                                                        <button type="submit" class="p-2 bg-red-50 hover:bg-red-100 text-red-600 rounded transition-colors" title="Nonaktifkan" onclick="return confirm('Nonaktifkan pengguna ini?')">
                                                            <i data-lucide="power-off" class="w-4 h-4"></i>
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <form method="POST" action="<?= url('users/' . $user['id'] . '/activate') ?>" class="inline">
                                                        <input type="hidden" name="_token" value="<?= e($csrfToken ?? '') ?>">
                                                        <button type="submit" class="p-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-600 rounded transition-colors" title="Aktifkan">
                                                            <i data-lucide="check-circle" class="w-4 h-4"></i>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
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
                                Menampilkan <?= (($page - 1) * 15) + 1 ?> – <?= min($page * 15, $total) ?> dari <?= $total ?> pengguna
                            </span>
                            <div class="flex gap-1">
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <a href="<?= url('users?page=' . $i) ?>" 
                                       class="px-3 py-2 rounded-lg transition-all <?= ($page === $i ? 'bg-indigo-600 text-white font-bold' : 'bg-slate-100 text-slate-700 hover:bg-slate-200') ?>">
                                        <?= $i ?>
                                    </a>
                                <?php endfor; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="px-6 py-12 text-center">
                            <i data-lucide="users" class="w-12 h-12 text-slate-300 mx-auto mb-3"></i>
                            <p class="text-slate-500 font-medium">Tidak ada pengguna yang ditemukan</p>
                        </div>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
