<?php
/**
 * ============================================================================
 * Model: Program Studi
 * ============================================================================
 * Model untuk mengelola data Program Studi dan statistiknya.
 * ============================================================================
 */

class ProgramStudi extends Model
{
    protected string $table = 'program_studi';
    protected array $fillable = [
        'kode_prodi',
        'nama_prodi',
        'jenjang',
        'fakultas',
        'akreditasi_terakhir',
        'tanggal_kadaluarsa',
        'sk_akreditasi',
        'kaprodi',
        'no_telepon_prodi',
        'email_prodi',
        'is_active'
    ];

    /**
     * Get program studi dengan status akreditasi dan pengajuan terbaru
     * @param int $limit Batas data
     * @param int $offset Offset data
     * @return array
     */
    public function getWithStatus(int $limit = 50, int $offset = 0): array
    {
        $query = "
            SELECT 
                ps.id,
                ps.kode_prodi,
                ps.nama_prodi,
                ps.jenjang,
                ps.fakultas,
                ps.akreditasi_terakhir,
                ps.tanggal_kadaluarsa,
                ps.kaprodi,
                COUNT(DISTINCT pa.id) as total_pengajuan,
                SUM(CASE WHEN pa.status NOT IN ('selesai','ditolak') THEN 1 ELSE 0 END) as pengajuan_aktif,
                MAX(pa.tanggal_pengajuan) as pengajuan_terakhir,
                MAX(pa.status) as status_pengajuan_terakhir,
                MAX(ja.status) as status_jadwal_terakhir
            FROM program_studi ps
            LEFT JOIN pengajuan_akreditasi pa ON ps.id = pa.program_studi_id
            LEFT JOIN jadwal_pendampingan ja ON pa.id = ja.pengajuan_akreditasi_id
            WHERE ps.is_active = 1
            GROUP BY ps.id
            ORDER BY ps.nama_prodi ASC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get jumlah program studi per jenjang
     * @return array Associative array dengan jenjang sebagai key
     */
    public function countByJenjang(): array
    {
        $query = "
            SELECT 
                jenjang,
                COUNT(*) as total,
                SUM(CASE WHEN tanggal_kadaluarsa IS NOT NULL AND tanggal_kadaluarsa < CURDATE() THEN 1 ELSE 0 END) as expired,
                SUM(CASE WHEN tanggal_kadaluarsa IS NOT NULL AND tanggal_kadaluarsa >= CURDATE() THEN 1 ELSE 0 END) as active
            FROM program_studi
            WHERE is_active = 1
            GROUP BY jenjang
            ORDER BY FIELD(jenjang, 'S3', 'S2', 'S1', 'D4', 'Profesi', 'D3')
        ";

        $result = $this->db->fetchAll($query);
        $output = [];

        foreach ($result as $row) {
            $output[$row['jenjang']] = [
                'total' => (int)$row['total'],
                'active' => (int)$row['active'],
                'expired' => (int)$row['expired']
            ];
        }

        return $output;
    }

    /**
     * Get statistik akreditasi per jenjang
     * @return array
     */
    public function getAccreditationStats(): array
    {
        $query = "
            SELECT 
                jenjang,
                akreditasi_terakhir,
                COUNT(*) as total
            FROM program_studi
            WHERE is_active = 1
            GROUP BY jenjang, akreditasi_terakhir
            ORDER BY jenjang, akreditasi_terakhir
        ";

        return $this->db->fetchAll($query);
    }

    /**
     * Get program studi dengan pengajuan dalam progress
     * @return array
     */
    public function getWithPendingApplications(): array
    {
        $query = "
            SELECT 
                ps.*,
                pa.id as pengajuan_id,
                pa.nomor_pengajuan,
                pa.jenis_pengajuan,
                pa.status,
                pa.tanggal_pengajuan,
                pa.tanggal_target,
                COUNT(ja.id) as total_jadwal,
                SUM(CASE WHEN ja.status = 'selesai' THEN 1 ELSE 0 END) as jadwal_selesai,
                MAX(ja.tanggal_mulai) as jadwal_terakhir
            FROM program_studi ps
            INNER JOIN pengajuan_akreditasi pa ON ps.id = pa.program_studi_id
            LEFT JOIN jadwal_pendampingan ja ON pa.id = ja.pengajuan_akreditasi_id
            WHERE ps.is_active = 1 
            AND pa.status NOT IN ('ditolak', 'selesai')
            GROUP BY pa.id
            ORDER BY pa.tanggal_pengajuan DESC
        ";

        return $this->db->fetchAll($query);
    }

    /**
     * Get monitoring data untuk dashboard
     * @return array
     */
    public function getMonitoringData(): array
    {
        // Get all active programs
        $query = "
            SELECT 
                ps.id,
                ps.kode_prodi,
                ps.nama_prodi,
                ps.jenjang,
                ps.fakultas,
                ps.akreditasi_terakhir,
                ps.tanggal_kadaluarsa,
                ps.kaprodi
            FROM program_studi ps
            WHERE ps.is_active = 1
            ORDER BY ps.nama_prodi ASC
        ";

        $programs = $this->db->fetchAll($query);
        $result = [];

        foreach ($programs as $prog) {
            // Get latest non-completed pengajuan
            $pengajuan = $this->db->fetch(
                "SELECT id, nomor_pengajuan, status, jenis_pengajuan, tanggal_pengajuan, tanggal_target
                 FROM pengajuan_akreditasi
                 WHERE program_studi_id = :pid
                 AND status NOT IN ('ditolak', 'selesai')
                 ORDER BY tanggal_pengajuan DESC
                 LIMIT 1",
                ['pid' => $prog['id']]
            );
            
            if ($pengajuan && $pengajuan['id']) {
                // Get jadwal count
                $jadwal = $this->db->fetch(
                    "SELECT COUNT(*) as total, SUM(CASE WHEN status = 'selesai' THEN 1 ELSE 0 END) as completed
                     FROM jadwal_pendampingan
                     WHERE pengajuan_akreditasi_id = :pid",
                    ['pid' => $pengajuan['id']]
                );
                
                $prog['pengajuan_id'] = $pengajuan['id'];
                $prog['nomor_pengajuan'] = $pengajuan['nomor_pengajuan'];
                $prog['status_pengajuan'] = $pengajuan['status'];
                $prog['jenis_pengajuan'] = $pengajuan['jenis_pengajuan'];
                $prog['tanggal_pengajuan'] = $pengajuan['tanggal_pengajuan'];
                $prog['tanggal_target'] = $pengajuan['tanggal_target'];
                $prog['total_stages'] = (int)($jadwal['total'] ?? 0);
                $prog['completed_stages'] = (int)($jadwal['completed'] ?? 0);
            } else {
                $prog['pengajuan_id'] = null;
                $prog['nomor_pengajuan'] = null;
                $prog['status_pengajuan'] = null;
                $prog['jenis_pengajuan'] = null;
                $prog['tanggal_pengajuan'] = null;
                $prog['tanggal_target'] = null;
                $prog['total_stages'] = 0;
                $prog['completed_stages'] = 0;
            }
            
            $result[] = $prog;
        }

        return $result;
    }

    /**
     * Get total count program studi aktif
     * @return int
     */
    public function countActive(): int
    {
        return (int)$this->db->count("SELECT COUNT(*) FROM " . $this->table . " WHERE is_active = 1");
    }

    /**
     * Get jumlah program dengan akreditasi kadaluarsa
     * @return int
     */
    public function countExpired(): int
    {
        return (int)$this->db->count(
            "SELECT COUNT(*) FROM " . $this->table . " 
             WHERE is_active = 1 AND tanggal_kadaluarsa IS NOT NULL 
             AND tanggal_kadaluarsa < CURDATE()"
        );
    }

    /**
     * Get program studi by jenjang
     * @param string $jenjang Jenjang (D3, D4, S1, S2, S3)
     * @return array
     */
    public function getByJenjang(string $jenjang): array
    {
        return $this->db->fetchAll(
            "SELECT * FROM " . $this->table . " 
             WHERE is_active = 1 AND jenjang = :jenjang
             ORDER BY nama_prodi ASC",
            ['jenjang' => $jenjang]
        );
    }
}
