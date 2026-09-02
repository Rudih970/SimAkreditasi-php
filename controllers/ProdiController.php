<?php
/**
 * ============================================================================
 * ProdiController — Manajemen Program Studi
 * ============================================================================
 * Controller untuk CRUD Program Studi (hanya Admin Universitas/KPMA)
 */

require_once ROOT_PATH . '/models/ProgramStudi.php';

class ProdiController extends Controller
{
    protected ProgramStudi $prodiModel;

    public function __construct()
    {
        parent::__construct();
        $this->prodiModel = new ProgramStudi();
        
        // Restrict access — hanya admin roles yang bisa akses
        if (!is_role(['admin_universitas', 'kepala_kpma', 'kabid_kpma'])) {
            $this->redirect('auth/unauthorized');
        }
    }

    /**
     * List semua program studi dengan filter & search
     */
    public function index(): void
    {
        check_access(['admin_universitas', 'kepala_kpma', 'kabid_kpma']);

        $page = $_GET['page'] ?? 1;
        $search = $_GET['search'] ?? '';
        $jenjang = $_GET['jenjang'] ?? '';
        $perPage = 15;

        // Build query
        $where = ['is_active' => 1];
        if (!empty($search)) {
            // Search di nama atau kode
            $prodis = $this->db->fetchAll(
                "SELECT * FROM program_studi 
                 WHERE is_active = 1 
                 AND (nama_prodi LIKE :search OR kode_prodi LIKE :search)
                 ORDER BY nama_prodi ASC",
                ['search' => "%$search%"]
            );
        } elseif (!empty($jenjang)) {
            $prodis = $this->prodiModel->getByJenjang($jenjang);
        } else {
            $prodis = $this->db->fetchAll(
                "SELECT * FROM program_studi WHERE is_active = 1 ORDER BY nama_prodi ASC"
            );
        }

        // Pagination
        $total = count($prodis);
        $totalPages = ceil($total / $perPage);
        $offset = ($page - 1) * $perPage;
        $prodis = array_slice($prodis, $offset, $perPage);

        $this->view('prodi.index', [
            'pageTitle' => 'Manajemen Program Studi',
            'activePage' => 'program-studi',
            'prodis' => $prodis,
            'page' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
            'search' => $search,
            'jenjang' => $jenjang,
            'jenjangStats' => $this->prodiModel->countByJenjang(),
        ]);
    }

    /**
     * Show form tambah program studi baru
     */
    public function create(): void
    {
        check_access(['admin_universitas', 'kepala_kpma', 'kabid_kpma']);

        $this->view('prodi.form', [
            'pageTitle' => 'Tambah Program Studi',
            'activePage' => 'program-studi',
            'prodi' => null,
            'action' => 'create',
        ]);
    }

    /**
     * Store program studi baru
     */
    public function store(): void
    {
        check_access(['admin_universitas', 'kepala_kpma', 'kabid_kpma']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('program-studi/create');
        }

        // Validate CSRF
        if (!validate_csrf($_POST['_token'] ?? '')) {
            set_flash('error', 'Token tidak valid');
            $this->redirect('program-studi/create');
        }

        // Validate input
        $errors = [];
        if (empty($_POST['nama_prodi'])) $errors[] = 'Nama program studi harus diisi';
        if (empty($_POST['kode_prodi'])) $errors[] = 'Kode program studi harus diisi';
        if (empty($_POST['jenjang'])) $errors[] = 'Jenjang harus diisi';
        if (empty($_POST['fakultas'])) $errors[] = 'Fakultas harus diisi';

        // Check duplicate kode
        $existing = $this->db->fetch(
            "SELECT id FROM program_studi WHERE kode_prodi = :kode AND is_active = 1",
            ['kode' => $_POST['kode_prodi']]
        );
        if ($existing) {
            $errors[] = 'Kode program studi sudah terdaftar';
        }

        if (!empty($errors)) {
            set_flash('error', implode('; ', $errors));
            $this->redirect('program-studi/create');
        }

        // Insert
        try {
            $this->db->execute(
                "INSERT INTO program_studi (kode_prodi, nama_prodi, jenjang, fakultas, akreditasi_terakhir, kaprodi, no_telepon_prodi, email_prodi, is_active)
                 VALUES (:kode, :nama, :jenjang, :fakultas, :akreditasi, :kaprodi, :telp, :email, 1)",
                [
                    'kode' => $_POST['kode_prodi'],
                    'nama' => $_POST['nama_prodi'],
                    'jenjang' => $_POST['jenjang'],
                    'fakultas' => $_POST['fakultas'],
                    'akreditasi' => $_POST['akreditasi_terakhir'] ?? null,
                    'kaprodi' => $_POST['kaprodi'] ?? null,
                    'telp' => $_POST['no_telepon_prodi'] ?? null,
                    'email' => $_POST['email_prodi'] ?? null,
                ]
            );

            set_flash('success', 'Program studi ' . $_POST['nama_prodi'] . ' berhasil ditambahkan');
            $this->redirect('program-studi');
        } catch (Exception $e) {
            set_flash('error', 'Gagal menambahkan program studi: ' . $e->getMessage());
            $this->redirect('program-studi/create');
        }
    }

    /**
     * Show form edit program studi
     */
    public function edit($id): void
    {
        check_access(['admin_universitas', 'kepala_kpma', 'kabid_kpma']);

        $prodi = $this->db->fetch(
            "SELECT * FROM program_studi WHERE id = :id AND is_active = 1",
            ['id' => (int)$id]
        );

        if (!$prodi) {
            set_flash('error', 'Program studi tidak ditemukan');
            $this->redirect('program-studi');
        }

        $this->view('prodi.form', [
            'pageTitle' => 'Edit Program Studi',
            'activePage' => 'program-studi',
            'prodi' => $prodi,
            'action' => 'edit',
        ]);
    }

    /**
     * Update program studi
     */
    public function update($id): void
    {
        check_access(['admin_universitas', 'kepala_kpma', 'kabid_kpma']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('program-studi/' . $id . '/edit');
        }

        // Validate CSRF
        if (!validate_csrf($_POST['_token'] ?? '')) {
            set_flash('error', 'Token tidak valid');
            $this->redirect('program-studi/' . $id . '/edit');
        }

        // Get existing prodi
        $prodi = $this->db->fetch(
            "SELECT * FROM program_studi WHERE id = :id AND is_active = 1",
            ['id' => (int)$id]
        );

        if (!$prodi) {
            set_flash('error', 'Program studi tidak ditemukan');
            $this->redirect('program-studi');
        }

        // Validate input
        $errors = [];
        if (empty($_POST['nama_prodi'])) $errors[] = 'Nama program studi harus diisi';
        if (empty($_POST['jenjang'])) $errors[] = 'Jenjang harus diisi';
        if (empty($_POST['fakultas'])) $errors[] = 'Fakultas harus diisi';

        if (!empty($errors)) {
            set_flash('error', implode('; ', $errors));
            $this->redirect('program-studi/' . $id . '/edit');
        }

        // Update
        try {
            $this->db->execute(
                "UPDATE program_studi 
                 SET nama_prodi = :nama, jenjang = :jenjang, fakultas = :fakultas, 
                     akreditasi_terakhir = :akreditasi, kaprodi = :kaprodi,
                     no_telepon_prodi = :telp, email_prodi = :email, updated_at = NOW()
                 WHERE id = :id",
                [
                    'nama' => $_POST['nama_prodi'],
                    'jenjang' => $_POST['jenjang'],
                    'fakultas' => $_POST['fakultas'],
                    'akreditasi' => $_POST['akreditasi_terakhir'] ?? null,
                    'kaprodi' => $_POST['kaprodi'] ?? null,
                    'telp' => $_POST['no_telepon_prodi'] ?? null,
                    'email' => $_POST['email_prodi'] ?? null,
                    'id' => (int)$id,
                ]
            );

            set_flash('success', 'Program studi berhasil diperbarui');
            $this->redirect('program-studi');
        } catch (Exception $e) {
            set_flash('error', 'Gagal memperbarui program studi: ' . $e->getMessage());
            $this->redirect('program-studi/' . $id . '/edit');
        }
    }

    /**
     * Hapus (soft delete) program studi
     */
    public function delete($id): void
    {
        check_access(['admin_universitas', 'kepala_kpma', 'kabid_kpma']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('program-studi');
        }

        // Validate CSRF
        if (!validate_csrf($_POST['_token'] ?? '')) {
            set_flash('error', 'Token tidak valid');
            $this->redirect('program-studi');
        }

        try {
            // Soft delete
            $this->db->execute(
                "UPDATE program_studi SET is_active = 0, updated_at = NOW() WHERE id = :id",
                ['id' => (int)$id]
            );

            set_flash('success', 'Program studi berhasil dihapus');
        } catch (Exception $e) {
            set_flash('error', 'Gagal menghapus program studi: ' . $e->getMessage());
        }

        $this->redirect('program-studi');
    }

    /**
     * Detail program studi dengan statistik
     */
    public function show($id): void
    {
        check_access(['admin_universitas', 'kepala_kpma', 'kabid_kpma']);

        $prodi = $this->db->fetch(
            "SELECT * FROM program_studi WHERE id = :id AND is_active = 1",
            ['id' => (int)$id]
        );

        if (!$prodi) {
            set_flash('error', 'Program studi tidak ditemukan');
            $this->redirect('program-studi');
        }

        // Get stats
        $pengajuanStats = $this->db->fetch(
            "SELECT 
                COUNT(*) as total_pengajuan,
                SUM(CASE WHEN status NOT IN ('selesai','ditolak') THEN 1 ELSE 0 END) as aktif,
                SUM(CASE WHEN status = 'selesai' THEN 1 ELSE 0 END) as selesai
             FROM pengajuan_akreditasi WHERE program_studi_id = :pid",
            ['pid' => $prodi['id']]
        );

        $this->view('prodi.show', [
            'pageTitle' => 'Detail Program Studi',
            'activePage' => 'program-studi',
            'prodi' => $prodi,
            'pengajuanStats' => $pengajuanStats,
        ]);
    }
}
