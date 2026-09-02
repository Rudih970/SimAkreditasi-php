<?php
/**
 * SIM Akreditasi — Halaman Login
 * View tanpa sidebar (auth layout)
 */
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — <?= e(APP_NAME) ?></title>
    <meta name="description" content="Login ke Sistem Manajemen Akreditasi Perguruan Tinggi">
    <link rel="icon" type="image/png" href="<?= asset('images/logo-uika.png') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] },
                    colors: {
                        primary: { 400: '#2dd4bf', 500: '#14b8a6', 600: '#0d9488', 700: '#0f766e' },
                        surface: { 50: '#f8fafc', 100: '#f1f5f9', 200: '#e2e8f0', 300: '#cbd5e1', 400: '#94a3b8', 500: '#64748b', 600: '#475569', 700: '#334155', 800: '#1e293b', 900: '#0f172a' }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>
<body class="font-sans antialiased text-surface-800">

    <div class="auth-page">
        <!-- Decorative Background Elements handled by style.css -->
        
        <!-- Login Card -->
        <div class="relative w-full max-w-md mx-4">
            <!-- Logo -->
            <div class="text-center mb-8">
                <div class="w-24 h-24 flex items-center justify-center mx-auto mb-4">
                    <img src="<?= asset('images/logo-uika.png') ?>" alt="Logo UIKA" class="w-full h-full object-contain drop-shadow-md">
                </div>
                <h1 class="text-2xl font-bold text-surface-900 tracking-tight"><?= e(APP_NAME) ?></h1>
                <p class="text-surface-500 text-sm mt-1"><?= e(APP_FULL_NAME) ?></p>
            </div>

            <!-- Form Card -->
            <div class="bg-white/80 backdrop-blur-xl border border-surface-200 rounded-2xl p-8 shadow-xl shadow-surface-200/50">
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-surface-900">Masuk ke Akun</h2>
                    <p class="text-surface-500 text-sm mt-1">Gunakan email dan password Anda untuk login</p>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 flex items-center gap-3">
                        <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" x2="12" y1="8" y2="12"/>
                            <line x1="12" x2="12.01" y1="16" y2="16"/>
                        </svg>
                        <p class="text-sm text-red-600 font-medium"><?= e($error) ?></p>
                    </div>
                <?php endif; ?>

                <?= flash_message() ?>

                <form method="POST" action="<?= url('auth/login') ?>" autocomplete="off">
                    <input type="hidden" name="_token" value="<?= e($csrfToken ?? '') ?>">
                    
                    <!-- Email -->
                    <div class="mb-5">
                        <label for="email" class="form-label">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                                <svg class="w-4 h-4 text-surface-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                                </svg>
                            </div>
                            <input type="email" id="email" name="email" required
                                   class="form-input pl-10"
                                   placeholder="email@universitas.ac.id"
                                   value="<?= e($_POST['email'] ?? '') ?>"
                                   autofocus>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="mb-6">
                        <label for="password" class="form-label">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                                <svg class="w-4 h-4 text-surface-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                </svg>
                            </div>
                            <input type="password" id="password" name="password" required
                                   class="form-input pl-10 pr-10"
                                   placeholder="Masukkan password">
                            <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-surface-400 hover:text-surface-600 transition-colors">
                                <svg id="eye-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between mb-6">
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded border-surface-300 bg-white text-primary-500 focus:ring-primary-500/30 focus:ring-offset-0">
                            <span class="text-sm text-surface-500 group-hover:text-surface-700 transition-colors">Ingat saya</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                            class="w-full btn btn-primary btn-lg justify-center group">
                        <span>Masuk</span>
                        <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                        </svg>
                    </button>
                </form>
            </div>

            <!-- Footer -->
            <p class="text-center text-surface-400 text-xs mt-6">
                &copy; <?= date('Y') ?> <?= e(APP_NAME) ?> &mdash; v<?= e(APP_VERSION) ?>
            </p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eye-icon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = '<path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49"/><path d="M14.084 14.158a3 3 0 0 1-4.242-4.242"/><path d="M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151 1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143"/><path d="m2 2 20 20"/>';
            } else {
                input.type = 'password';
                icon.innerHTML = '<path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/>';
            }
        }
    </script>
</body>
</html>
