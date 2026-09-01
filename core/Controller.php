<?php
/**
 * ============================================================================
 * SIM Akreditasi — Base Controller
 * ============================================================================
 * Kelas dasar untuk semua controller. Menyediakan method helper untuk:
 * - Memuat view dengan data
 * - Redirect
 * - Validasi akses RBAC
 * - Flash messages
 * ============================================================================
 */

class Controller
{
    /**
     * Instance Database
     * @var Database
     */
    protected Database $db;

    /**
     * Data user yang sedang login
     * @var array|null
     */
    protected ?array $user = null;

    /**
     * Constructor — Inisialisasi database & session data
     */
    public function __construct()
    {
        $this->db = Database::getInstance();
        
        // Ambil data user dari session jika sudah login
        if (isset($_SESSION['user_id'])) {
            $this->user = $_SESSION['user'] ?? null;
        }
    }

    /**
     * Memuat view file dengan data
     * 
     * @param string $view Path view (dot notation: 'dashboard.index')
     * @param array $data Data yang dikirim ke view
     * @return void
     */
    protected function view(string $view, array $data = []): void
    {
        // Konversi dot notation ke path
        $viewPath = ROOT_PATH . '/views/' . str_replace('.', '/', $view) . '.php';

        if (file_exists($viewPath)) {
            // Ekstrak data menjadi variabel
            extract($data);

            // Sediakan variabel global untuk view
            $currentUser = $this->user;
            $baseUrl = BASE_URL;
            $appName = APP_NAME;

            require_once $viewPath;
        } else {
            throw new \Exception("View tidak ditemukan: {$view} ({$viewPath})");
        }
    }

    /**
     * Memuat model
     * 
     * @param string $model Nama model (contoh: 'UserModel')
     * @return object Instance model
     */
    protected function model(string $model): object
    {
        $modelFile = ROOT_PATH . '/models/' . $model . '.php';

        if (file_exists($modelFile)) {
            require_once $modelFile;
            return new $model();
        }

        throw new \Exception("Model tidak ditemukan: {$model}");
    }

    /**
     * Redirect ke URL tertentu
     * 
     * @param string $url Path relatif (contoh: 'dashboard' atau 'auth/login')
     * @return void
     */
    protected function redirect(string $url): void
    {
        header('Location: ' . BASE_URL . '/' . ltrim($url, '/'));
        exit;
    }

    /**
     * Cek apakah user sudah login
     * 
     * @return bool
     */
    protected function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    /**
     * Middleware: Wajib login — redirect ke login jika belum
     * 
     * @return void
     */
    protected function requireLogin(): void
    {
        if (!$this->isLoggedIn()) {
            $this->setFlash('warning', 'Silakan login terlebih dahulu.');
            $this->redirect('auth/login');
        }
    }

    /**
     * Middleware: Cek akses role — redirect jika tidak diizinkan
     * 
     * @param array $allowedRoles Array role yang diizinkan
     * @return void
     */
    protected function requireRole(array $allowedRoles): void
    {
        $this->requireLogin();

        if (!in_array($this->user['role'], $allowedRoles)) {
            $this->setFlash('danger', 'Anda tidak memiliki akses ke halaman ini.');
            $this->redirect('dashboard');
        }
    }

    /**
     * Cek apakah user memiliki akses ke menu tertentu
     * 
     * @param string $menu Nama menu
     * @return bool
     */
    protected function hasMenuAccess(string $menu): bool
    {
        if (!$this->user) return false;
        $role = $this->user['role'];
        return in_array($menu, ROLE_MENUS[$role] ?? []);
    }

    /**
     * Set flash message (ditampilkan sekali setelah redirect)
     * 
     * @param string $type Tipe: success, danger, warning, info
     * @param string $message Pesan
     * @return void
     */
    protected function setFlash(string $type, string $message): void
    {
        $_SESSION['flash'] = [
            'type'    => $type,
            'message' => $message,
        ];
    }

    /**
     * Ambil dan hapus flash message
     * 
     * @return array|null
     */
    protected function getFlash(): ?array
    {
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        return $flash;
    }

    /**
     * Kirim response JSON (untuk AJAX/API)
     * 
     * @param array $data Data response
     * @param int $statusCode HTTP status code
     * @return void
     */
    protected function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Validasi method HTTP request
     * 
     * @param string $method GET, POST, PUT, DELETE
     * @return bool
     */
    protected function isMethod(string $method): bool
    {
        return strtoupper($_SERVER['REQUEST_METHOD']) === strtoupper($method);
    }

    /**
     * Ambil input POST yang sudah di-sanitize
     * 
     * @param string $key Nama field
     * @param mixed $default Nilai default
     * @return mixed
     */
    protected function input(string $key, mixed $default = null): mixed
    {
        $value = $_POST[$key] ?? $default;
        if (is_string($value)) {
            return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
        }
        return $value;
    }

    /**
     * Ambil parameter GET yang sudah di-sanitize
     * 
     * @param string $key Nama parameter
     * @param mixed $default Nilai default
     * @return mixed
     */
    protected function query(string $key, mixed $default = null): mixed
    {
        $value = $_GET[$key] ?? $default;
        if (is_string($value)) {
            return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
        }
        return $value;
    }

    /**
     * Validasi CSRF token
     * 
     * @return bool
     */
    protected function validateCsrf(): bool
    {
        $token = $_POST['_token'] ?? '';
        return hash_equals($_SESSION['csrf_token'] ?? '', $token);
    }

    /**
     * Generate CSRF token
     * 
     * @return string
     */
    protected function generateCsrf(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}
