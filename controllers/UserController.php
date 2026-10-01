<?php
/**
 * ============================================================================
 * UserController — Manajemen Akun & Pengguna
 * ============================================================================
 * Controller untuk CRUD User dengan role assignment (hanya Admin KPMA)
 */

class UserController extends Controller
{
    /**
     * List semua pengguna dengan filter role
     */
    public function index(): void
    {
        check_access(['admin_universitas', 'kepala_kpma', 'kabid_kpma']);

        $page = $_GET['page'] ?? 1;
        $search = $_GET['search'] ?? '';
        $role = $_GET['role'] ?? '';
        $status = $_GET['status'] ?? '';
        $perPage = 15;

        // Build query
        $query = "SELECT id, nik, nama_lengkap, email, role, program_studi_id, is_active, created_at FROM users WHERE 1=1";
        $params = [];

        if ($status === 'active') {
            $query .= " AND is_active = 1";
        } elseif ($status === 'inactive') {
            $query .= " AND is_active = 0";
        }

        if (!empty($role)) {
            $query .= " AND role = :role";
            $params['role'] = $role;
        }

        if (!empty($search)) {
            $query .= " AND (nama_lengkap LIKE :search OR email LIKE :search OR nik LIKE :search)";
            $params['search'] = "%$search%";
        }

        $query .= " ORDER BY nama_lengkap ASC";

        // Get total
        $countQuery = "SELECT COUNT(*) as total FROM ($query) as t";
        $countResult = $this->db->fetch($countQuery, $params);
        $total = $countResult['total'] ?? 0;

        // Pagination
        $totalPages = ceil($total / $perPage);
        $offset = ($page - 1) * $perPage;
        $query .= " LIMIT $perPage OFFSET $offset";

        $users = $this->db->fetchAll($query, $params);

        // Get stats
        $roleStats = [];
        foreach (ROLES as $roleKey => $roleLabel) {
            $count = $this->db->count(
                "SELECT COUNT(*) FROM users WHERE role = :role AND is_active = 1",
                ['role' => $roleKey]
            );
            $roleStats[$roleKey] = ['label' => $roleLabel, 'count' => $count];
        }

        $this->view('users.index', [
            'pageTitle' => 'Manajemen Akun Pengguna',
            'activePage' => 'users',
            'users' => $users,
            'page' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
            'search' => $search,
            'role' => $role,
            'status' => $status,
            'roleStats' => $roleStats,
        ]);
    }

    /**
     * Show form tambah pengguna baru
     */
    public function create(): void
    {
        check_access(['admin_universitas', 'kepala_kpma']);

        // Get available program studi for assignment
        $prodis = $this->db->fetchAll("SELECT id, nama_prodi, kode_prodi FROM program_studi WHERE is_active = 1 ORDER BY nama_prodi");

        $this->view('users.form', [
            'pageTitle'  => 'Tambah Pengguna Baru',
            'activePage' => 'users',
            'user'       => null,
            'prodis'     => $prodis,
            'action'     => 'create',
            'csrfToken'  => csrf_token(),
        ]);
    }

    /**
     * Store pengguna baru
     */
    public function store(): void
    {
        check_access(['admin_universitas', 'kepala_kpma']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('users/create');
        }

        // Validate CSRF
        if (!validate_csrf($_POST['_token'] ?? '')) {
            set_flash('error', 'Token tidak valid');
            $this->redirect('users/create');
        }

        // Validate input
        $errors = [];
        if (empty($_POST['nik'])) $errors[] = 'NIK harus diisi';
        if (empty($_POST['nama_lengkap'])) $errors[] = 'Nama lengkap harus diisi';
        if (empty($_POST['email'])) $errors[] = 'Email harus diisi';
        if (empty($_POST['password'])) $errors[] = 'Password harus diisi';
        if (empty($_POST['role'])) $errors[] = 'Role harus diisi';

        // Validate email format
        if (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Format email tidak valid';
        }

        // Check duplicate NIK & email
        $existing = $this->db->fetch(
            "SELECT id FROM users WHERE nik = :nik OR email = :email",
            ['nik' => $_POST['nik'] ?? '', 'email' => $_POST['email'] ?? '']
        );
        if ($existing) {
            $errors[] = 'NIK atau Email sudah terdaftar';
        }

        // Validate password strength
        if (!empty($_POST['password']) && strlen($_POST['password']) < 8) {
            $errors[] = 'Password minimal 8 karakter';
        }

        if (!empty($errors)) {
            set_flash('error', implode('; ', $errors));
            $this->redirect('users/create');
        }

        // Insert
        try {
            $this->db->execute(
                "INSERT INTO users (nik, nama_lengkap, email, password, role, program_studi_id, jabatan, is_active, created_at)
                 VALUES (:nik, :nama, :email, :password, :role, :prodi_id, :jabatan, 1, NOW())",
                [
                    'nik'     => $_POST['nik'],
                    'nama' => $_POST['nama_lengkap'],
                    'email' => $_POST['email'],
                    'password' => password_hash($_POST['password'], PASSWORD_BCRYPT),
                    'role' => $_POST['role'],
                    'prodi_id' => $_POST['program_studi_id'] ?? null,
                    'jabatan' => $_POST['jabatan'] ?? null,
                ]
            );

            set_flash('success', 'Pengguna ' . $_POST['nama_lengkap'] . ' berhasil ditambahkan');
            $this->redirect('users');
        } catch (Exception $e) {
            set_flash('error', 'Gagal menambahkan pengguna: ' . $e->getMessage());
            $this->redirect('users/create');
        }
    }

    /**
     * Show form edit pengguna
     */
    public function edit($id): void
    {
        check_access(['admin_universitas', 'kepala_kpma']);

        $user = $this->db->fetch(
            "SELECT * FROM users WHERE id = :id",
            ['id' => (int)$id]
        );

        if (!$user) {
            set_flash('error', 'Pengguna tidak ditemukan');
            $this->redirect('users');
        }

        // Get available program studi
        $prodis = $this->db->fetchAll("SELECT id, nama_prodi, kode_prodi FROM program_studi WHERE is_active = 1 ORDER BY nama_prodi");

        $this->view('users.form', [
            'pageTitle'  => 'Edit Pengguna',
            'activePage' => 'users',
            'user'       => $user,
            'prodis'     => $prodis,
            'action'     => 'edit',
            'csrfToken'  => csrf_token(),
        ]);
    }

    /**
     * Update pengguna
     */
    public function update($id): void
    {
        check_access(['admin_universitas', 'kepala_kpma']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('users/' . $id . '/edit');
        }

        // Validate CSRF
        if (!validate_csrf($_POST['_token'] ?? '')) {
            set_flash('error', 'Token tidak valid');
            $this->redirect('users/' . $id . '/edit');
        }

        // Get existing user
        $user = $this->db->fetch(
            "SELECT * FROM users WHERE id = :id",
            ['id' => (int)$id]
        );

        if (!$user) {
            set_flash('error', 'Pengguna tidak ditemukan');
            $this->redirect('users');
        }

        // Validate input
        $errors = [];
        if (empty($_POST['nama_lengkap'])) $errors[] = 'Nama lengkap harus diisi';
        if (empty($_POST['role'])) $errors[] = 'Role harus diisi';

        if (!empty($errors)) {
            set_flash('error', implode('; ', $errors));
            $this->redirect('users/' . $id . '/edit');
        }

        // Update
        try {
            $updateData = [
                'nama' => $_POST['nama_lengkap'],
                'role' => $_POST['role'],
                'prodi_id' => $_POST['program_studi_id'] ?? null,
                'jabatan' => $_POST['jabatan'] ?? null,
                'id' => (int)$id,
            ];

            // If password is provided, hash it
            $updateQuery = "UPDATE users SET nama_lengkap = :nama, role = :role, program_studi_id = :prodi_id, jabatan = :jabatan, updated_at = NOW()";
            if (!empty($_POST['password'])) {
                if (strlen($_POST['password']) < 8) {
                    set_flash('error', 'Password minimal 8 karakter');
                    $this->redirect('users/' . $id . '/edit');
                }
                $updateQuery = "UPDATE users SET nama_lengkap = :nama, role = :role, program_studi_id = :prodi_id, jabatan = :jabatan, password = :password, updated_at = NOW()";
                $updateData['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
            }

            $updateQuery .= " WHERE id = :id";

            $this->db->execute($updateQuery, $updateData);

            set_flash('success', 'Pengguna berhasil diperbarui');
            $this->redirect('users');
        } catch (Exception $e) {
            set_flash('error', 'Gagal memperbarui pengguna: ' . $e->getMessage());
            $this->redirect('users/' . $id . '/edit');
        }
    }

    /**
     * Deactivate user
     */
    public function deactivate($id): void
    {
        check_access(['admin_universitas', 'kepala_kpma']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('users');
        }

        // Validate CSRF
        if (!validate_csrf($_POST['_token'] ?? '')) {
            set_flash('error', 'Token tidak valid');
            $this->redirect('users');
        }

        try {
            $this->db->execute(
                "UPDATE users SET is_active = 0, updated_at = NOW() WHERE id = :id",
                ['id' => (int)$id]
            );

            set_flash('success', 'Pengguna berhasil dinonaktifkan');
        } catch (Exception $e) {
            set_flash('error', 'Gagal menonaktifkan pengguna: ' . $e->getMessage());
        }

        $this->redirect('users');
    }

    /**
     * Activate user
     */
    public function activate($id): void
    {
        check_access(['admin_universitas', 'kepala_kpma']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('users');
        }

        try {
            $this->db->execute(
                "UPDATE users SET is_active = 1, updated_at = NOW() WHERE id = :id",
                ['id' => (int)$id]
            );

            set_flash('success', 'Pengguna berhasil diaktifkan');
        } catch (Exception $e) {
            set_flash('error', 'Gagal mengaktifkan pengguna: ' . $e->getMessage());
        }

        $this->redirect('users');
    }

    /**
     * Reset password untuk user lain
     */
    public function resetPassword($id): void
    {
        check_access(['admin_universitas', 'kepala_kpma']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('users');
        }

        if (!validate_csrf($_POST['_token'] ?? '')) {
            set_flash('error', 'Token tidak valid');
            $this->redirect('users');
        }

        try {
            $newPassword = 'Password123!'; // Default temporary password
            $this->db->execute(
                "UPDATE users SET password = :password, updated_at = NOW() WHERE id = :id",
                ['password' => password_hash($newPassword, PASSWORD_BCRYPT), 'id' => (int)$id]
            );

            set_flash('success', 'Password berhasil direset ke: ' . $newPassword);
        } catch (Exception $e) {
            set_flash('error', 'Gagal reset password: ' . $e->getMessage());
        }

        $this->redirect('users');
    }
}
