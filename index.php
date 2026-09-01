<?php
/**
 * ============================================================================
 * SIM Akreditasi — Front Controller (Entry Point)
 * ============================================================================
 * Semua request HTTP diarahkan ke file ini melalui .htaccess.
 * File ini memuat konfigurasi, session, dan menginisialisasi aplikasi.
 * ============================================================================
 */

// ---------------------------------------------------------------------------
// 1. Load Konfigurasi Aplikasi
// ---------------------------------------------------------------------------
require_once __DIR__ . '/config/app.php';

// ---------------------------------------------------------------------------
// 2. Start Session
// ---------------------------------------------------------------------------
session_name(SESSION_NAME);
session_start([
    'cookie_lifetime' => SESSION_LIFETIME,
    'cookie_httponly'  => true,
    'cookie_secure'   => isset($_SERVER['HTTPS']),
    'cookie_samesite' => 'Lax',
    'use_strict_mode' => true,
]);

// ---------------------------------------------------------------------------
// 3. Load Core Files
// ---------------------------------------------------------------------------
require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/core/Helpers.php';
require_once ROOT_PATH . '/core/Model.php';
require_once ROOT_PATH . '/core/Controller.php';
require_once ROOT_PATH . '/core/App.php';

// ---------------------------------------------------------------------------
// 4. Inisialisasi & Jalankan Aplikasi
// ---------------------------------------------------------------------------
$app = new App();
