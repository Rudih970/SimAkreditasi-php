<?php
/**
 * ============================================================================
 * SIM Akreditasi — Helper Functions
 * ============================================================================
 * Fungsi-fungsi utilitas global yang digunakan di seluruh aplikasi.
 * ============================================================================
 */

// ---------------------------------------------------------------------------
// URL & Asset Helpers
// ---------------------------------------------------------------------------

/**
 * Generate URL lengkap
 * @param string $path Path relatif
 * @return string URL lengkap
 */
function url(string $path = ''): string
{
    return BASE_URL . '/' . ltrim($path, '/');
}

/**
 * Generate URL untuk asset (CSS, JS, gambar)
 * @param string $path Path asset relatif dari folder assets/
 * @return string URL asset
 */
function asset(string $path): string
{
    return BASE_URL . '/assets/' . ltrim($path, '/');
}

/**
 * Generate URL untuk upload
 * @param string $path Path file upload
 * @return string URL upload
 */
function upload_url(string $path): string
{
    return BASE_URL . '/uploads/' . ltrim($path, '/');
}

// ---------------------------------------------------------------------------
// Format Helpers
// ---------------------------------------------------------------------------

/**
 * Format tanggal ke bahasa Indonesia
 * @param string|null $date Tanggal (Y-m-d atau datetime)
 * @param string $format Format output ('long', 'short', 'datetime')
 * @return string Tanggal terformat
 */
function format_tanggal(?string $date, string $format = 'long'): string
{
    if (empty($date)) return '-';
    
    $bulan = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];

    $timestamp = strtotime($date);
    $d = (int) date('d', $timestamp);
    $m = (int) date('m', $timestamp);
    $y = date('Y', $timestamp);

    return match($format) {
        'long'        => "{$d} {$bulan[$m]} {$y}",
        'short'       => "{$d} " . substr($bulan[$m], 0, 3) . " {$y}",
        'datetime'    => "{$d} {$bulan[$m]} {$y}, " . date('H:i', $timestamp) . ' WIB',
        'month_short' => substr($bulan[$m], 0, 3),
        'month'       => $bulan[$m],
        default       => date($format, $timestamp),
    };
}

/**
 * Format ukuran file ke human readable
 * @param int $bytes Ukuran dalam bytes
 * @return string Ukuran terformat
 */
function format_file_size(int $bytes): string
{
    $units = ['B', 'KB', 'MB', 'GB'];
    $i = 0;
    while ($bytes >= 1024 && $i < count($units) - 1) {
        $bytes /= 1024;
        $i++;
    }
    return round($bytes, 2) . ' ' . $units[$i];
}

/**
 * Format angka ke format Indonesia
 * @param float|int $number Angka
 * @param int $decimals Jumlah desimal
 * @return string
 */
function format_number($number, int $decimals = 0): string
{
    return number_format($number, $decimals, ',', '.');
}

// ---------------------------------------------------------------------------
// Security Helpers
// ---------------------------------------------------------------------------

/**
 * Escape output HTML
 * @param string|null $string
 * @return string
 */
function e(?string $string): string
{
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Generate CSRF token hidden input
 * @return string HTML hidden input
 */
function csrf_field(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return '<input type="hidden" name="_token" value="' . $_SESSION['csrf_token'] . '">';
}

/**
 * Generate CSRF token value
 * @return string Token
 */
function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// ---------------------------------------------------------------------------
// String Helpers
// ---------------------------------------------------------------------------

/**
 * Potong teks dengan ellipsis
 * @param string $text Teks
 * @param int $length Panjang maksimal
 * @return string
 */
function str_limit(string $text, int $length = 100): string
{
    if (mb_strlen($text) <= $length) return $text;
    return mb_substr($text, 0, $length) . '...';
}

/**
 * Generate nama label role yang user-friendly
 * @param string $role Role key
 * @return string Nama role
 */
function role_label(string $role): string
{
    return ROLES[$role] ?? ucwords(str_replace('_', ' ', $role));
}

/**
 * Generate badge HTML untuk status
 * @param string $status Status value
 * @return string HTML badge
 */
function status_badge(string $status): string
{
    $config = match($status) {
        'draft'       => ['bg-gray-500/15 text-gray-400 ring-gray-500/30', 'Draft'],
        'diajukan'    => ['bg-blue-500/15 text-blue-400 ring-blue-500/30', 'Diajukan'],
        'review'      => ['bg-amber-500/15 text-amber-400 ring-amber-500/30', 'Review'],
        'revisi'      => ['bg-orange-500/15 text-orange-400 ring-orange-500/30', 'Revisi'],
        'disetujui'   => ['bg-emerald-500/15 text-emerald-400 ring-emerald-500/30', 'Disetujui'],
        'ditolak'     => ['bg-red-500/15 text-red-400 ring-red-500/30', 'Ditolak'],
        'selesai'     => ['bg-indigo-500/15 text-indigo-400 ring-indigo-500/30', 'Selesai'],
        // Status borang
        'uploaded'    => ['bg-blue-500/15 text-blue-400 ring-blue-500/30', 'Uploaded'],
        'verified'    => ['bg-emerald-500/15 text-emerald-400 ring-emerald-500/30', 'Verified'],
        'rejected'    => ['bg-red-500/15 text-red-400 ring-red-500/30', 'Rejected'],
        'revised'     => ['bg-amber-500/15 text-amber-400 ring-amber-500/30', 'Revised'],
        // Status review
        'pending'     => ['bg-gray-500/15 text-gray-400 ring-gray-500/30', 'Pending'],
        'in_progress' => ['bg-blue-500/15 text-blue-400 ring-blue-500/30', 'In Progress'],
        'completed'   => ['bg-emerald-500/15 text-emerald-400 ring-emerald-500/30', 'Completed'],
        // Status jadwal
        'dijadwalkan' => ['bg-blue-500/15 text-blue-400 ring-blue-500/30', 'Dijadwalkan'],
        'berlangsung' => ['bg-amber-500/15 text-amber-400 ring-amber-500/30', 'Berlangsung'],
        'dibatalkan'  => ['bg-red-500/15 text-red-400 ring-red-500/30', 'Dibatalkan'],
        // Status laporan
        'submitted'   => ['bg-blue-500/15 text-blue-400 ring-blue-500/30', 'Submitted'],
        'approved'    => ['bg-emerald-500/15 text-emerald-400 ring-emerald-500/30', 'Approved'],
        // Default
        default       => ['bg-gray-500/15 text-gray-400 ring-gray-500/30', ucfirst($status)],
    };

    return sprintf(
        '<span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold ring-1 ring-inset %s">%s</span>',
        $config[0],
        $config[1]
    );
}

/**
 * Generate badge HTML untuk role
 * @param string $role Role key
 * @return string HTML badge
 */
function role_badge(string $role): string
{
    $config = match($role) {
        'admin_universitas' => ['bg-purple-500/15 text-purple-400 ring-purple-500/30', 'Admin Universitas'],
        'kepala_kpma'       => ['bg-rose-500/15 text-rose-400 ring-rose-500/30', 'Kepala KPMA'],
        'kabid_kpma'        => ['bg-pink-500/15 text-pink-400 ring-pink-500/30', 'Kabid KPMA'],
        'reviewer_internal' => ['bg-cyan-500/15 text-cyan-400 ring-cyan-500/30', 'Reviewer Internal'],
        'asesor_internal'   => ['bg-teal-500/15 text-teal-400 ring-teal-500/30', 'Asesor Internal'],
        'admin_prodi'       => ['bg-sky-500/15 text-sky-400 ring-sky-500/30', 'Admin Prodi'],
        'team_task_force'   => ['bg-amber-500/15 text-amber-400 ring-amber-500/30', 'Task Force'],
        default             => ['bg-gray-500/15 text-gray-400 ring-gray-500/30', ucfirst($role)],
    };

    return sprintf(
        '<span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold ring-1 ring-inset %s">%s</span>',
        $config[0],
        $config[1]
    );
}

// ---------------------------------------------------------------------------
// Flash Message Helper
// ---------------------------------------------------------------------------

/**
 * Render flash message sebagai HTML alert
 * @return string HTML alert atau empty string
 */
function flash_message(): string
{
    $flash = $_SESSION['flash'] ?? null;
    if (!$flash) return '';
    
    unset($_SESSION['flash']);

    $icons = [
        'success' => 'check-circle-2',
        'danger'  => 'alert-circle',
        'warning' => 'alert-triangle',
        'info'    => 'info',
    ];

    $colors = [
        'success' => 'bg-emerald-500/10 border-emerald-500/30 text-emerald-400',
        'danger'  => 'bg-red-500/10 border-red-500/30 text-red-400',
        'warning' => 'bg-amber-500/10 border-amber-500/30 text-amber-400',
        'info'    => 'bg-blue-500/10 border-blue-500/30 text-blue-400',
    ];

    $type = $flash['type'];
    $icon = $icons[$type] ?? 'info';
    $color = $colors[$type] ?? $colors['info'];

    return sprintf(
        '<div class="flash-alert %s border rounded-xl p-4 mb-6 flex items-center gap-3 animate-slide-down" role="alert">
            <i data-lucide="%s" class="w-5 h-5 flex-shrink-0"></i>
            <p class="text-sm font-medium">%s</p>
            <button onclick="this.parentElement.remove()" class="ml-auto hover:opacity-70 transition-opacity">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>',
        $color,
        $icon,
        e($flash['message'])
    );
}

// ---------------------------------------------------------------------------
// Auth Helpers
// ---------------------------------------------------------------------------

/**
 * Cek apakah user saat ini memiliki role tertentu
 * @param string|array $roles Role atau array of roles
 * @return bool
 */
function is_role(string|array $roles): bool
{
    $userRole = $_SESSION['user']['role'] ?? '';
    if (is_array($roles)) {
        return in_array($userRole, $roles);
    }
    return $userRole === $roles;
}

/**
 * Middleware: Cek akses halaman berdasarkan role.
 * Jika belum login, redirect ke halaman login.
 * Jika tidak punya akses, redirect ke dashboard/403.
 * 
 * @param array $allowed_roles Array role yang diizinkan mengakses halaman
 * @return void
 */
function check_access(array $allowed_roles): void
{
    // 1. Pastikan user sudah login
    if (empty($_SESSION['user_id']) || empty($_SESSION['user'])) {
        $_SESSION['flash'] = [
            'type'    => 'warning',
            'message' => 'Silakan login terlebih dahulu untuk mengakses halaman ini.'
        ];
        header('Location: ' . BASE_URL . '/auth/login');
        exit;
    }

    // 2. Pastikan session belum expired atau session fixation attempt (fingerprint mismatch)
    // Akan diurus di layer App / Auth, tapi ini guard tambahan.
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > SESSION_LIFETIME)) {
        session_unset();
        session_destroy();
        session_start();
        $_SESSION['flash'] = [
            'type'    => 'warning',
            'message' => 'Sesi Anda telah berakhir. Silakan login kembali.'
        ];
        header('Location: ' . BASE_URL . '/auth/login');
        exit;
    }

    // Update last activity
    $_SESSION['last_activity'] = time();

    // 3. Cek Role
    $userRole = $_SESSION['user']['role'];
    if (!in_array($userRole, $allowed_roles)) {
        $_SESSION['flash'] = [
            'type'    => 'danger',
            'message' => 'Anda tidak memiliki akses ke halaman tersebut.'
        ];
        header('Location: ' . BASE_URL . '/dashboard');
        exit;
    }
}

/**
 * Ambil data user yang sedang login
 * @param string|null $key Kunci spesifik (null = semua data)
 * @return mixed
 */
function auth(?string $key = null): mixed
{
    if ($key) {
        return $_SESSION['user'][$key] ?? null;
    }
    return $_SESSION['user'] ?? null;
}

/**
 * Ambil inisial dari nama lengkap (untuk avatar)
 * @param string $name Nama lengkap
 * @return string Inisial (2 huruf)
 */
function get_initials(string $name): string
{
    $words = explode(' ', trim($name));
    $initials = '';
    
    // Ambil huruf pertama dari kata pertama
    if (isset($words[0])) {
        // Skip gelar akademik
        $skipTitles = ['dr.', 'dr', 'prof.', 'prof', 'ir.', 'ir', 'drs.', 'drs'];
        $startIdx = 0;
        foreach ($words as $i => $word) {
            if (in_array(strtolower(rtrim($word, '.')), $skipTitles)) {
                $startIdx = $i + 1;
            } else {
                break;
            }
        }
        
        if (isset($words[$startIdx])) {
            $initials .= strtoupper(mb_substr($words[$startIdx], 0, 1));
        }
        // Huruf pertama dari kata terakhir (sebelum gelar belakang)
        $lastWord = end($words);
        if (str_contains($lastWord, ',') || str_contains($lastWord, '.')) {
            $lastWord = prev($words) ?: $lastWord;
        }
        if ($lastWord !== ($words[$startIdx] ?? '')) {
            $initials .= strtoupper(mb_substr($lastWord, 0, 1));
        }
    }
    
    return $initials ?: '??';
}
