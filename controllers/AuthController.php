<?php
/**
 * ============================================================================
 * SIM Akreditasi — Auth Controller
 * ============================================================================
 * Menangani autentikasi: login, logout, dan session management.
 * ============================================================================
 */

class AuthController extends Controller
{
    /**
     * Halaman Login
     */
    public function login(): void
    {
        // Jika sudah login, redirect ke dashboard
        if ($this->isLoggedIn()) {
            $this->redirect('dashboard');
        }

        $error = null;

        if ($this->isMethod('POST')) {
            // Validasi CSRF Token
            if (!$this->validateCsrf()) {
                $error = 'Token keamanan tidak valid atau telah kadaluarsa. Silakan muat ulang halaman.';
            } else {
                // Rate Limiting sederhana (proteksi Brute-Force)
                if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= 5) {
                    if (time() - $_SESSION['last_failed_login'] < 300) {
                        $error = 'Terlalu banyak percobaan gagal. Silakan coba lagi dalam 5 menit.';
                    } else {
                        // Reset setelah 5 menit
                        $_SESSION['login_attempts'] = 0;
                    }
                }

                if (!$error) {
                    $email = $this->input('email');
                    $password = $_POST['password'] ?? '';

                    if (empty($email) || empty($password)) {
                        $error = 'Email dan password wajib diisi.';
                    } else {
                        // Cari user berdasarkan email
                        $user = $this->db->fetch(
                            "SELECT * FROM users WHERE email = :email AND is_active = 1 LIMIT 1",
                            ['email' => $email]
                        );

                        // Verifikasi password hash
                        if ($user && password_verify($password, $user['password'])) {
                            // Login berhasil — set session
                            $_SESSION['user_id'] = $user['id'];
                            $_SESSION['user'] = [
                                'id'              => $user['id'],
                                'nik'             => $user['nik'],
                                'nama_lengkap'    => $user['nama_lengkap'],
                                'email'           => $user['email'],
                                'role'            => $user['role'],
                                'program_studi_id'=> $user['program_studi_id'],
                                'jabatan'         => $user['jabatan'],
                                'foto_profil'     => $user['foto_profil'],
                            ];

                            // Session Fingerprinting untuk mencegah Session Hijacking
                            $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? '';
                            $_SESSION['user_ip']    = $_SERVER['REMOTE_ADDR'] ?? '';
                            $_SESSION['last_activity'] = time();

                            // Reset login attempts
                            unset($_SESSION['login_attempts']);
                            unset($_SESSION['last_failed_login']);

                            // Update last_login timestamp di DB
                            $this->db->execute(
                                "UPDATE users SET last_login = NOW() WHERE id = :id",
                                ['id' => $user['id']]
                            );

                            // Log aktivitas
                            $this->db->execute(
                                "INSERT INTO activity_log (user_id, aktivitas, modul, detail, ip_address, user_agent)
                                 VALUES (:uid, 'Login ke sistem', 'auth', :detail, :ip, :ua)",
                                [
                                    'uid'    => $user['id'],
                                    'detail' => json_encode(['method' => 'form_login', 'role' => $user['role']]),
                                    'ip'     => $_SESSION['user_ip'],
                                    'ua'     => $_SESSION['user_agent'],
                                ]
                            );

                            // Regenerate session ID untuk mencegah Session Fixation
                            session_regenerate_id(true);

                            $this->setFlash('success', 'Selamat datang, ' . $user['nama_lengkap'] . '!');
                            $this->redirect('dashboard');
                        } else {
                            $error = 'Email atau password salah.';
                            
                            // Catat percobaan gagal
                            $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
                            $_SESSION['last_failed_login'] = time();
                        }
                    }
                }
            }
        }

        $this->view('auth.login', [
            'pageTitle' => 'Login',
            'error'     => $error,
            'csrfToken' => $this->generateCsrf(),
        ]);
    }

    /**
     * Logout
     */
    public function logout(): void
    {
        // Log aktivitas sebelum destroy session
        if ($this->isLoggedIn()) {
            $this->db->execute(
                "INSERT INTO activity_log (user_id, aktivitas, modul, detail, ip_address, user_agent)
                 VALUES (:uid, 'Logout dari sistem', 'auth', :detail, :ip, :ua)",
                [
                    'uid'    => $this->user['id'],
                    'detail' => json_encode(['role' => $this->user['role']]),
                    'ip'     => $_SERVER['REMOTE_ADDR'] ?? '',
                    'ua'     => $_SERVER['HTTP_USER_AGENT'] ?? '',
                ]
            );
        }

        // Hapus data session
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }
        
        // Hancurkan session file
        session_destroy();

        // Redirect ke login dengan param sukses
        session_start(); // Mulai session baru untuk flash message
        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => 'Anda berhasil keluar dari sistem.'
        ];
        header('Location: ' . BASE_URL . '/auth/login');
        exit;
    }
}
