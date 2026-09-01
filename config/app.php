<?php
/**
 * ============================================================================
 * SIM Akreditasi — Konfigurasi Aplikasi
 * ============================================================================
 * File ini berisi konstanta dan pengaturan dasar aplikasi.
 * Semua konfigurasi global didefinisikan di sini.
 * ============================================================================
 */

// ---------------------------------------------------------------------------
// Informasi Aplikasi
// ---------------------------------------------------------------------------
define('APP_NAME', 'SIM Akreditasi');
define('APP_FULL_NAME', 'Sistem Manajemen Akreditasi Perguruan Tinggi');
define('APP_VERSION', '1.0.0');
define('APP_DESCRIPTION', 'Aplikasi pengelolaan proses akreditasi perguruan tinggi secara terintegrasi');

// ---------------------------------------------------------------------------
// URL & Path
// ---------------------------------------------------------------------------
define('BASE_URL', 'http://localhost/SimAkreditasi');
define('ROOT_PATH', dirname(__DIR__));

// ---------------------------------------------------------------------------
// Database
// ---------------------------------------------------------------------------
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'akreditasiflow_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// ---------------------------------------------------------------------------
// Session
// ---------------------------------------------------------------------------
define('SESSION_NAME', 'sim_akreditasi_session');
define('SESSION_LIFETIME', 7200); // 2 jam dalam detik

// ---------------------------------------------------------------------------
// Upload
// ---------------------------------------------------------------------------
define('UPLOAD_PATH', ROOT_PATH . '/uploads');
define('MAX_FILE_SIZE', 25 * 1024 * 1024); // 25 MB
define('ALLOWED_EXTENSIONS', ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'jpg', 'jpeg', 'png']);

// ---------------------------------------------------------------------------
// Role Definitions (RBAC)
// ---------------------------------------------------------------------------
define('ROLES', [
    'admin_universitas'  => 'Admin Universitas',
    'kepala_kpma'        => 'Kepala KPMA',
    'kabid_kpma'         => 'Kabid KPMA',
    'reviewer_internal'  => 'Reviewer Internal',
    'asesor_internal'    => 'Asesor Internal',
    'admin_prodi'        => 'Admin Prodi',
    'team_task_force'    => 'Team Task Force',
]);

// ---------------------------------------------------------------------------
// Role-Based Menu Access
// ---------------------------------------------------------------------------
define('ROLE_MENUS', [
    'admin_universitas' => [
        'dashboard', 'users', 'program_studi', 'instrumen',
        'pengajuan', 'borang', 'review', 'jadwal', 'riwayat',
        'laporan', 'statistik', 'notifikasi', 'activity_log', 'settings'
    ],
    'kepala_kpma' => [
        'dashboard', 'program_studi', 'pengajuan', 'review',
        'jadwal', 'riwayat', 'laporan', 'statistik', 'notifikasi'
    ],
    'kabid_kpma' => [
        'dashboard', 'program_studi', 'pengajuan', 'borang',
        'review', 'jadwal', 'laporan', 'statistik', 'notifikasi'
    ],
    'reviewer_internal' => [
        'dashboard', 'pengajuan', 'borang', 'review', 'notifikasi'
    ],
    'asesor_internal' => [
        'dashboard', 'pengajuan', 'borang', 'review',
        'jadwal', 'notifikasi'
    ],
    'admin_prodi' => [
        'dashboard', 'program_studi', 'pengajuan', 'borang',
        'jadwal', 'riwayat', 'laporan', 'statistik', 'notifikasi'
    ],
    'team_task_force' => [
        'dashboard', 'pengajuan', 'borang', 'jadwal',
        'laporan', 'notifikasi'
    ],
]);

// ---------------------------------------------------------------------------
// Timezone
// ---------------------------------------------------------------------------
date_default_timezone_set('Asia/Jakarta');

// ---------------------------------------------------------------------------
// Error Reporting (matikan di production)
// ---------------------------------------------------------------------------
define('APP_DEBUG', true);

if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}
