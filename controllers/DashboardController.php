<?php
/**
 * ============================================================================
 * SIM Akreditasi — Dashboard Controller
 * ============================================================================
 */

class DashboardController extends Controller
{
    public function index(): void
    {
        // Terapkan middleware check_access untuk semua role yang diizinkan
        check_access(array_keys(ROLES));

        // Statistik ringkasan untuk dashboard
        $stats = [
            'total_prodi'     => $this->db->count("SELECT COUNT(*) FROM program_studi WHERE is_active = 1"),
            'total_pengajuan' => $this->db->count("SELECT COUNT(*) FROM pengajuan_akreditasi"),
            'pengajuan_aktif' => $this->db->count("SELECT COUNT(*) FROM pengajuan_akreditasi WHERE status NOT IN ('selesai','ditolak')"),
            'total_review'    => $this->db->count("SELECT COUNT(*) FROM review_borang"),
            'review_pending'  => $this->db->count("SELECT COUNT(*) FROM review_borang WHERE status_review = 'pending'"),
            'total_borang'    => $this->db->count("SELECT COUNT(*) FROM borang_files"),
            'jadwal_aktif'    => $this->db->count("SELECT COUNT(*) FROM jadwal_pendampingan WHERE status IN ('dijadwalkan','berlangsung')"),
            'total_users'     => $this->db->count("SELECT COUNT(*) FROM users WHERE is_active = 1"),
        ];

        // Pengajuan terbaru
        $pengajuanTerbaru = $this->db->fetchAll(
            "SELECT pa.*, ps.nama_prodi, ps.jenjang, u.nama_lengkap AS nama_pengaju
             FROM pengajuan_akreditasi pa
             JOIN program_studi ps ON pa.program_studi_id = ps.id
             JOIN users u ON pa.user_pengaju_id = u.id
             ORDER BY pa.created_at DESC
             LIMIT 5"
        );

        // Jadwal mendatang
        $jadwalMendatang = $this->db->fetchAll(
            "SELECT jp.*, pa.nomor_pengajuan, ps.nama_prodi, u.nama_lengkap AS penanggung_jawab
             FROM jadwal_pendampingan jp
             JOIN pengajuan_akreditasi pa ON jp.pengajuan_akreditasi_id = pa.id
             JOIN program_studi ps ON pa.program_studi_id = ps.id
             JOIN users u ON jp.penanggung_jawab_id = u.id
             WHERE jp.status IN ('dijadwalkan','berlangsung')
             ORDER BY jp.tanggal_mulai ASC
             LIMIT 5"
        );

        // Aktivitas terbaru
        $aktivitasTerbaru = $this->db->fetchAll(
            "SELECT al.*, u.nama_lengkap, u.role
             FROM activity_log al
             LEFT JOIN users u ON al.user_id = u.id
             ORDER BY al.created_at DESC
             LIMIT 8"
        );

        // Notifikasi belum dibaca untuk user ini
        $notifCount = 0;
        if ($this->user) {
            $notifCount = $this->db->count(
                "SELECT COUNT(*) FROM notifikasi WHERE user_id = :uid AND is_read = 0",
                ['uid' => $this->user['id']]
            );
        }

        $this->view('dashboard.index', [
            'pageTitle'        => 'Dashboard',
            'activePage'       => 'dashboard',
            'stats'            => $stats,
            'pengajuanTerbaru' => $pengajuanTerbaru,
            'jadwalMendatang'  => $jadwalMendatang,
            'aktivitasTerbaru' => $aktivitasTerbaru,
            'notifCount'       => $notifCount,
        ]);
    }
}
