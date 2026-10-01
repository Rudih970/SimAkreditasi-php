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
        <!-- ===== LEFT PANEL: Branding & Decorative Icons ===== -->
        <div class="auth-left-panel">
            <!-- Floating decorative SVG icons -->
            <div class="auth-floating-icons">
                <!-- Book open -->
                <div class="auth-floating-icon">
                    <svg width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor"><path d="M21 4.5c-1.09-.53-2.16-.85-3.28-.97C16.57 3.39 15.4 3.5 14.5 4c-.92.5-1.72 1.33-2.5 2.5-.78-1.17-1.58-2-2.5-2.5-.9-.5-2.07-.61-3.22-.47C5.16 3.65 4.09 3.97 3 4.5v15c1.09-.53 2.16-.85 3.28-.97 1.15-.14 2.32-.03 3.22.47.92.5 1.72 1.33 2.5 2.5.78-1.17 1.58-2 2.5-2.5.9-.5 2.07-.61 3.22-.47 1.12.12 2.19.44 3.28.97V4.5z"/></svg>
                </div>
                <!-- Graduation cap -->
                <div class="auth-floating-icon">
                    <svg width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3L1 9l4 2.18v6L12 21l7-3.82v-6l2-1.09V17h2V9L12 3zm6.82 6L12 12.72 5.18 9 12 5.28 18.82 9zM17 15.99l-5 2.73-5-2.73v-3.72L12 15l5-2.73v3.72z"/></svg>
                </div>
                <!-- Users / People -->
                <div class="auth-floating-icon">
                    <svg width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                </div>
                <!-- Award / Certificate -->
                <div class="auth-floating-icon">
                    <svg width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 2.38 1.19 4.47 3 5.74V17c0 .55.45 1 1 1h6c.55 0 1-.45 1-1v-2.26c1.81-1.27 3-3.36 3-5.74 0-3.87-3.13-7-7-7zm2 15h-4v-1h4v1zm0-2h-4v-1h4v1zm-1.5-3.59V14h-1v-2.59L9.67 9.59l.71-.71L12 10.5l1.62-1.62.71.71-2.83 2.82z"/></svg>
                </div>
                <!-- Clipboard / Checklist -->
                <div class="auth-floating-icon">
                    <svg width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor"><path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm-2 14l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/></svg>
                </div>
                <!-- Building / Institution -->
                <div class="auth-floating-icon">
                    <svg width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor"><path d="M12 7V3H2v18h20V7H12zM6 19H4v-2h2v2zm0-4H4v-2h2v2zm0-4H4V9h2v2zm0-4H4V5h2v2zm4 12H8v-2h2v2zm0-4H8v-2h2v2zm0-4H8V9h2v2zm0-4H8V5h2v2zm10 12h-8v-2h2v-2h-2v-2h2v-2h-2V9h8v10zm-2-8h-2v2h2v-2zm0 4h-2v2h2v-2z"/></svg>
                </div>
                <!-- Star -->
                <div class="auth-floating-icon">
                    <svg width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                </div>
                <!-- File Text / Document -->
                <div class="auth-floating-icon">
                    <svg width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
                </div>
            </div>

            <!-- Branding content -->
            <div class="auth-left-branding">
                <div class="auth-logo-wrapper">
                    <img src="<?= asset('images/logo-uika.png') ?>" alt="Logo UIKA">
                </div>
                <h2><?= e(APP_NAME) ?></h2>
                <p><?= e(APP_FULL_NAME) ?></p>
            </div>
        </div>

        <!-- ===== RIGHT PANEL: Login Form ===== -->
        <div class="auth-right-panel">
            <div class="auth-card-wrapper">
                <div class="auth-card">
                    <span class="auth-welcome-label">Selamat Datang</span>
                    <h1 class="auth-title">Masuk ke <?= e(APP_NAME) ?></h1>
                    <p class="auth-subtitle">Gunakan email dan password Anda untuk mengakses sistem.</p>

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
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                            <span>Masuk</span>
                            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- Footer -->
                <p class="auth-footer">
                    &copy; <?= date('Y') ?> <?= e(APP_NAME) ?> &mdash; v<?= e(APP_VERSION) ?>
                </p>
            </div>
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
