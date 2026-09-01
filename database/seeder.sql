-- ============================================================================
-- SIM AKREDITASI — Data Seeder untuk Pengujian
-- Database: akreditasiflow_db
-- Version: 1.0.0
-- Created: 2026-09-01
-- ============================================================================
-- CATATAN:
--   Semua password menggunakan hash bcrypt dari: password123
--   Hash: $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi
-- ============================================================================

USE `akreditasiflow_db`;

-- Nonaktifkan FK checks sementara untuk mempermudah insert
SET FOREIGN_KEY_CHECKS = 0;

-- Bersihkan data lama (urutan terbalik dari dependency)
TRUNCATE TABLE `activity_log`;
TRUNCATE TABLE `notifikasi`;
TRUNCATE TABLE `laporan_pendampingan`;
TRUNCATE TABLE `riwayat_akreditasi`;
TRUNCATE TABLE `jadwal_pendampingan`;
TRUNCATE TABLE `review_borang`;
TRUNCATE TABLE `borang_files`;
TRUNCATE TABLE `pengajuan_akreditasi`;
TRUNCATE TABLE `instrumen_akreditasi`;
TRUNCATE TABLE `users`;
TRUNCATE TABLE `program_studi`;

SET FOREIGN_KEY_CHECKS = 1;

-- ---------------------------------------------------------------------------
-- 1. SEEDER: program_studi (6 Program Studi)
-- ---------------------------------------------------------------------------
INSERT INTO `program_studi` 
    (`id`, `kode_prodi`, `nama_prodi`, `jenjang`, `fakultas`, `akreditasi_terakhir`, `tanggal_kadaluarsa`, `sk_akreditasi`, `kaprodi`, `no_telepon_prodi`, `email_prodi`, `is_active`)
VALUES
    (1, '55201', 'Teknik Informatika',          'S1', 'Fakultas Teknik',              'Baik Sekali',  '2027-06-15', '1234/SK/BAN-PT/Ak-PPJ/S/VI/2022',    'Dr. Ahmad Fauzi, M.Kom.',       '021-5551001', 'ti@universitas.ac.id',        1),
    (2, '61201', 'Sistem Informasi',            'S1', 'Fakultas Teknik',              'Baik',         '2026-12-20', '2345/SK/BAN-PT/Ak-PPJ/S/XII/2021',   'Dr. Budi Santoso, M.T.',        '021-5551002', 'si@universitas.ac.id',        1),
    (3, '86206', 'Manajemen',                   'S1', 'Fakultas Ekonomi dan Bisnis',  'Unggul',       '2028-03-10', '3456/SK/BAN-PT/Ak-PPJ/S/III/2023',   'Prof. Dr. Citra Dewi, M.M.',    '021-5551003', 'manajemen@universitas.ac.id', 1),
    (4, '62201', 'Akuntansi',                   'S1', 'Fakultas Ekonomi dan Bisnis',  'A',            '2027-09-25', '4567/SK/BAN-PT/Ak-PPJ/S/IX/2022',    'Dr. Diana Putri, M.Ak.',        '021-5551004', 'akuntansi@universitas.ac.id', 1),
    (5, '74201', 'Ilmu Hukum',                  'S1', 'Fakultas Hukum',               'B',            '2026-11-30', '5678/SK/BAN-PT/Ak-PPJ/S/XI/2021',    'Prof. Dr. Eko Prasetyo, S.H.',  '021-5551005', 'hukum@universitas.ac.id',     1),
    (6, '70201', 'Pendidikan Bahasa Inggris',   'S1', 'Fakultas Keguruan dan Ilmu Pendidikan', 'Baik', '2027-01-15', '6789/SK/BAN-PT/Ak-PPJ/S/I/2022', 'Dr. Fatimah Zahra, M.Pd.',      '021-5551006', 'pbi@universitas.ac.id',       1);

-- ---------------------------------------------------------------------------
-- 2. SEEDER: users (14 Pengguna — 2 per Role)
-- ---------------------------------------------------------------------------
-- Password untuk semua akun: password123
-- Hash bcrypt: $2y$10$wE45JRgFs9hNU.13WhEY8OqK5Ncc4COYf4BqVpaY75pTUeylZDEma

INSERT INTO `users`
    (`id`, `nip`, `nama_lengkap`, `email`, `password`, `role`, `program_studi_id`, `jabatan`, `no_telepon`, `is_active`, `last_login`)
VALUES
    -- Admin Universitas (2 akun)
    (1,  '198501012010011001', 'Dr. Ir. Hendra Wijaya, M.T.',        'admin1@universitas.ac.id',       '$2y$10$wE45JRgFs9hNU.13WhEY8OqK5Ncc4COYf4BqVpaY75pTUeylZDEma', 'admin_universitas',  NULL, 'Kepala Biro TIK',              '081200001001', 1, '2026-08-30 08:15:00'),
    (2,  '198702152012012002', 'Ir. Indah Permatasari, M.Kom.',      'admin2@universitas.ac.id',       '$2y$10$wE45JRgFs9hNU.13WhEY8OqK5Ncc4COYf4BqVpaY75pTUeylZDEma', 'admin_universitas',  NULL, 'Staf Biro TIK',                '081200001002', 1, '2026-08-29 14:30:00'),

    -- Kepala KPMA (2 akun)
    (3,  '197803202005011003', 'Prof. Dr. Joko Susanto, M.Pd.',      'kepala.kpma@universitas.ac.id',  '$2y$10$wE45JRgFs9hNU.13WhEY8OqK5Ncc4COYf4BqVpaY75pTUeylZDEma', 'kepala_kpma',        NULL, 'Kepala KPMA',                  '081200002001', 1, '2026-08-28 09:00:00'),
    (4,  '198005102008012004', 'Dr. Kartika Sari, M.Si.',            'wakil.kpma@universitas.ac.id',   '$2y$10$wE45JRgFs9hNU.13WhEY8OqK5Ncc4COYf4BqVpaY75pTUeylZDEma', 'kepala_kpma',        NULL, 'Wakil Kepala KPMA',            '081200002002', 1, '2026-08-27 10:45:00'),

    -- Kabid KPMA (2 akun)
    (5,  '198206152010011005', 'Dr. Lukman Hakim, M.T.',             'kabid.akreditasi@universitas.ac.id', '$2y$10$wE45JRgFs9hNU.13WhEY8OqK5Ncc4COYf4BqVpaY75pTUeylZDEma', 'kabid_kpma',     NULL, 'Kabid Akreditasi',             '081200003001', 1, '2026-08-30 07:30:00'),
    (6,  '198410202012012006', 'Dr. Maya Anggraini, M.Pd.',          'kabid.mutu@universitas.ac.id',       '$2y$10$wE45JRgFs9hNU.13WhEY8OqK5Ncc4COYf4BqVpaY75pTUeylZDEma', 'kabid_kpma',     NULL, 'Kabid Penjaminan Mutu',        '081200003002', 1, '2026-08-29 16:20:00'),

    -- Reviewer Internal (2 akun)
    (7,  '197905252007011007', 'Dr. Nugroho Pratama, M.Kom.',        'reviewer1@universitas.ac.id',    '$2y$10$wE45JRgFs9hNU.13WhEY8OqK5Ncc4COYf4BqVpaY75pTUeylZDEma', 'reviewer_internal',  NULL, 'Reviewer Dokumen Senior',      '081200004001', 1, '2026-08-28 11:00:00'),
    (8,  '198108302009012008', 'Dr. Olivia Rahayu, M.Si.',           'reviewer2@universitas.ac.id',    '$2y$10$wE45JRgFs9hNU.13WhEY8OqK5Ncc4COYf4BqVpaY75pTUeylZDEma', 'reviewer_internal',  NULL, 'Reviewer Dokumen',             '081200004002', 1, '2026-08-27 13:15:00'),

    -- Asesor Internal (2 akun)
    (9,  '197604122004011009', 'Prof. Dr. Panji Kusuma, M.Eng.',     'asesor1@universitas.ac.id',      '$2y$10$wE45JRgFs9hNU.13WhEY8OqK5Ncc4COYf4BqVpaY75pTUeylZDEma', 'asesor_internal',    NULL, 'Asesor Internal Senior',       '081200005001', 1, '2026-08-26 08:45:00'),
    (10, '198312182010012010', 'Dr. Queen Maharani, M.T.',           'asesor2@universitas.ac.id',      '$2y$10$wE45JRgFs9hNU.13WhEY8OqK5Ncc4COYf4BqVpaY75pTUeylZDEma', 'asesor_internal',    NULL, 'Asesor Internal',              '081200005002', 1, '2026-08-25 15:30:00'),

    -- Admin Prodi (2 akun — terkait prodi)
    (11, '199001052015011011', 'Rizky Maulana, S.Kom., M.Cs.',       'admin.ti@universitas.ac.id',     '$2y$10$wE45JRgFs9hNU.13WhEY8OqK5Ncc4COYf4BqVpaY75pTUeylZDEma', 'admin_prodi',        1,    'Admin Prodi Teknik Informatika', '081200006001', 1, '2026-08-30 09:00:00'),
    (12, '199203182016012012', 'Siti Nurhaliza, S.E., M.M.',         'admin.mnj@universitas.ac.id',    '$2y$10$wE45JRgFs9hNU.13WhEY8OqK5Ncc4COYf4BqVpaY75pTUeylZDEma', 'admin_prodi',        3,    'Admin Prodi Manajemen',        '081200006002', 1, '2026-08-29 08:30:00'),

    -- Team Task Force (2 akun — terkait prodi)
    (13, '199405222018011013', 'Teguh Prasetya, S.T., M.Kom.',       'taskforce1@universitas.ac.id',   '$2y$10$wE45JRgFs9hNU.13WhEY8OqK5Ncc4COYf4BqVpaY75pTUeylZDEma', 'team_task_force',    1,    'Koordinator Task Force TI',    '081200007001', 1, '2026-08-28 10:00:00'),
    (14, '199607102019012014', 'Ulfa Dwi Cahyani, S.Pd., M.Pd.',    'taskforce2@universitas.ac.id',   '$2y$10$wE45JRgFs9hNU.13WhEY8OqK5Ncc4COYf4BqVpaY75pTUeylZDEma', 'team_task_force',    6,    'Anggota Task Force PBI',       '081200007002', 1, '2026-08-27 11:30:00');

-- ---------------------------------------------------------------------------
-- 3. SEEDER: instrumen_akreditasi (3 Instrumen)
-- ---------------------------------------------------------------------------
INSERT INTO `instrumen_akreditasi`
    (`id`, `kode_instrumen`, `nama_instrumen`, `versi`, `lembaga_akreditasi`, `deskripsi`, `jumlah_standar`, `is_active`)
VALUES
    (1, 'IAPS-4.0',    'Instrumen Akreditasi Program Studi 4.0',                    '4.0',  'BAN-PT',
        'Instrumen penilaian akreditasi program studi berdasarkan 9 Kriteria yang ditetapkan oleh BAN-PT. Mencakup penilaian Laporan Evaluasi Diri (LED) dan Laporan Kinerja Program Studi (LKPS).',
        9, 1),

    (2, 'IAPT-3.0',    'Instrumen Akreditasi Perguruan Tinggi 3.0',                  '3.0',  'BAN-PT',
        'Instrumen penilaian akreditasi institusi/perguruan tinggi secara keseluruhan oleh BAN-PT berdasarkan standar nasional pendidikan tinggi.',
        9, 1),

    (3, 'LAM-INFOKOM', 'Instrumen Akreditasi LAM Infokom',                           '1.0',  'LAM Infokom',
        'Instrumen akreditasi khusus untuk program studi bidang Informatika dan Komputer yang dikelola oleh Lembaga Akreditasi Mandiri Informatika dan Komputer.',
        7, 1);

-- ---------------------------------------------------------------------------
-- 4. SEEDER: pengajuan_akreditasi (4 Pengajuan)
-- ---------------------------------------------------------------------------
INSERT INTO `pengajuan_akreditasi`
    (`id`, `program_studi_id`, `instrumen_akreditasi_id`, `user_pengaju_id`, `nomor_pengajuan`, `jenis_pengajuan`, `tanggal_pengajuan`, `tanggal_target`, `status`, `catatan`, `approved_by`, `approved_at`)
VALUES
    (1, 1, 1, 11, 'PAK-2026-0001', 'reakreditasi',    '2026-07-01', '2027-03-01', 'review',
        'Pengajuan reakreditasi Program Studi Teknik Informatika menggunakan IAPS 4.0. Akreditasi sebelumnya Baik Sekali, target mempertahankan atau meningkatkan ke Unggul.',
        NULL, NULL),

    (2, 2, 1, 11, 'PAK-2026-0002', 'reakreditasi',    '2026-08-01', '2026-12-15', 'diajukan',
        'Pengajuan reakreditasi Program Studi Sistem Informasi. Akreditasi sebelumnya Baik, masa berlaku hampir habis.',
        NULL, NULL),

    (3, 3, 1, 12, 'PAK-2026-0003', 'perpanjangan',    '2026-06-15', '2028-01-01', 'disetujui',
        'Perpanjangan akreditasi Program Studi Manajemen. Akreditasi saat ini Unggul.',
        3, '2026-07-20 14:30:00'),

    (4, 5, 1, 11, 'PAK-2026-0004', 'reakreditasi',    '2026-08-15', '2027-06-01', 'draft',
        'Rencana pengajuan reakreditasi Program Studi Ilmu Hukum. Masih tahap persiapan dokumen.',
        NULL, NULL);

-- ---------------------------------------------------------------------------
-- 5. SEEDER: borang_files (8 Dokumen)
-- ---------------------------------------------------------------------------
INSERT INTO `borang_files`
    (`id`, `pengajuan_akreditasi_id`, `uploaded_by`, `nama_dokumen`, `kategori_dokumen`, `nomor_standar`, `file_path`, `file_name`, `file_size`, `file_type`, `versi_dokumen`, `status`, `catatan_verifikasi`, `verified_by`, `verified_at`)
VALUES
    -- Dokumen Pengajuan #1 (Teknik Informatika - Review)
    (1, 1, 11, 'Laporan Evaluasi Diri - Teknik Informatika 2026',
        'led', NULL,
        '/uploads/borang/2026/PAK-2026-0001/LED_TI_2026_v2.pdf',
        'LED_TI_2026_v2.pdf', 15728640, 'application/pdf', 2,
        'verified', 'Dokumen LED telah lengkap dan sesuai format. Sudah direvisi dari versi 1.',
        7, '2026-08-10 10:30:00'),

    (2, 1, 13, 'Laporan Kinerja Program Studi - Teknik Informatika',
        'lkps', NULL,
        '/uploads/borang/2026/PAK-2026-0001/LKPS_TI_2026.xlsx',
        'LKPS_TI_2026.xlsx', 5242880, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 1,
        'verified', 'Data LKPS telah terisi lengkap semua tabel.',
        7, '2026-08-12 14:15:00'),

    (3, 1, 13, 'SK Penetapan Dosen Tetap',
        'sk_penetapan', 'K3',
        '/uploads/borang/2026/PAK-2026-0001/SK_Dosen_Tetap_TI.pdf',
        'SK_Dosen_Tetap_TI.pdf', 2097152, 'application/pdf', 1,
        'verified', NULL, 7, '2026-08-15 09:00:00'),

    (4, 1, 11, 'Bukti Kinerja Penelitian Dosen',
        'bukti_kinerja', 'K7',
        '/uploads/borang/2026/PAK-2026-0001/Bukti_Penelitian_TI.pdf',
        'Bukti_Penelitian_TI.pdf', 8388608, 'application/pdf', 1,
        'uploaded', NULL, NULL, NULL),

    -- Dokumen Pengajuan #2 (Sistem Informasi - Diajukan)
    (5, 2, 11, 'Laporan Evaluasi Diri - Sistem Informasi',
        'led', NULL,
        '/uploads/borang/2026/PAK-2026-0002/LED_SI_2026.pdf',
        'LED_SI_2026.pdf', 12582912, 'application/pdf', 1,
        'uploaded', NULL, NULL, NULL),

    (6, 2, 11, 'Surat Pengantar Pengajuan Akreditasi SI',
        'surat_pengantar', NULL,
        '/uploads/borang/2026/PAK-2026-0002/Surat_Pengantar_SI.pdf',
        'Surat_Pengantar_SI.pdf', 1048576, 'application/pdf', 1,
        'uploaded', NULL, NULL, NULL),

    -- Dokumen Pengajuan #3 (Manajemen - Disetujui)
    (7, 3, 12, 'Laporan Evaluasi Diri - Manajemen',
        'led', NULL,
        '/uploads/borang/2026/PAK-2026-0003/LED_MNJ_2026.pdf',
        'LED_MNJ_2026.pdf', 18874368, 'application/pdf', 3,
        'verified', 'Dokumen LED final, sudah direvisi 2 kali sesuai masukan reviewer.',
        8, '2026-07-15 16:00:00'),

    (8, 3, 12, 'LKPS - Manajemen',
        'lkps', NULL,
        '/uploads/borang/2026/PAK-2026-0003/LKPS_MNJ_2026.xlsx',
        'LKPS_MNJ_2026.xlsx', 6291456, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 2,
        'verified', 'Tabel LKPS sudah lengkap dan valid.',
        8, '2026-07-18 11:30:00');

-- ---------------------------------------------------------------------------
-- 6. SEEDER: review_borang (4 Review)
-- ---------------------------------------------------------------------------
INSERT INTO `review_borang`
    (`id`, `pengajuan_akreditasi_id`, `reviewer_id`, `borang_file_id`, `jenis_review`, `skor_penilaian`, `catatan_review`, `rekomendasi`, `status_review`, `tanggal_review`)
VALUES
    (1, 1, 7, 1, 'review_dokumen', 85.50,
        'Dokumen LED sudah sangat baik. Narasi evaluasi diri pada Kriteria 1 (Visi, Misi, Tujuan, dan Strategi) sudah komprehensif. Perlu sedikit perbaikan pada analisis SWOT di Kriteria 2.',
        'layak', 'completed', '2026-08-20'),

    (2, 1, 9, NULL, 'desk_evaluation', 82.75,
        'Hasil desk evaluation menunjukkan dokumen telah memenuhi sebagian besar persyaratan. Skor rata-rata dari 9 kriteria adalah 82.75. Kriteria yang perlu diperkuat: Kriteria 5 (Kurikulum) dan Kriteria 9 (Luaran dan Capaian Tridharma).',
        'layak', 'completed', '2026-08-25'),

    (3, 3, 8, 7, 'review_dokumen', 91.20,
        'Dokumen LED Manajemen sangat baik dan komprehensif. Seluruh standar tercakup dengan lengkap. Bukti pendukung tersedia dan valid.',
        'layak', 'completed', '2026-07-10'),

    (4, 3, 10, NULL, 'review_substansi', 89.00,
        'Review substansi menunjukkan kualitas pengelolaan program studi yang sangat baik. Rekomendasi: pertahankan capaian dan tingkatkan kerjasama internasional.',
        'layak', 'completed', '2026-07-12');

-- ---------------------------------------------------------------------------
-- 7. SEEDER: jadwal_pendampingan (4 Jadwal)
-- ---------------------------------------------------------------------------
INSERT INTO `jadwal_pendampingan`
    (`id`, `pengajuan_akreditasi_id`, `judul_kegiatan`, `jenis_kegiatan`, `tanggal_mulai`, `tanggal_selesai`, `lokasi`, `deskripsi`, `penanggung_jawab_id`, `status`)
VALUES
    (1, 1, 'Workshop Penyusunan LED Kriteria 1-4',
        'workshop',
        '2026-07-15 08:00:00', '2026-07-15 16:00:00',
        'Ruang Rapat Utama Gedung Rektorat Lt. 3',
        'Workshop penyusunan Laporan Evaluasi Diri untuk Kriteria 1 sampai 4. Peserta: Tim Task Force TI, Admin Prodi, dan Dosen Pembimbing. Narasumber: Reviewer Internal.',
        5, 'selesai'),

    (2, 1, 'Bimtek Pengisian LKPS Tabel 1-8',
        'bimtek',
        '2026-07-22 09:00:00', '2026-07-23 15:00:00',
        'Lab Komputer Fakultas Teknik Lt. 2',
        'Bimbingan teknis pengisian tabel-tabel LKPS sesuai panduan BAN-PT. Menggunakan template LKPS terbaru.',
        5, 'selesai'),

    (3, 1, 'Simulasi Asesmen Lapangan',
        'simulasi',
        '2026-09-15 08:00:00', '2026-09-16 17:00:00',
        'Program Studi Teknik Informatika',
        'Simulasi asesmen lapangan untuk mempersiapkan prodi menghadapi visitasi BAN-PT. Melibatkan asesor internal sebagai penguji.',
        9, 'dijadwalkan'),

    (4, 2, 'Pendampingan Penyusunan Dokumen SI',
        'pendampingan',
        '2026-09-05 09:00:00', '2026-09-05 16:00:00',
        'Ruang Prodi Sistem Informasi Gedung Teknik Lt. 1',
        'Pendampingan penyusunan dokumen akreditasi Program Studi Sistem Informasi oleh Kabid Akreditasi.',
        6, 'dijadwalkan');

-- ---------------------------------------------------------------------------
-- 8. SEEDER: riwayat_akreditasi (6 Riwayat)
-- ---------------------------------------------------------------------------
INSERT INTO `riwayat_akreditasi`
    (`id`, `program_studi_id`, `pengajuan_akreditasi_id`, `lembaga_akreditasi`, `nomor_sk`, `tanggal_sk`, `masa_berlaku_mulai`, `masa_berlaku_selesai`, `nilai_akreditasi`, `skor_akhir`, `dokumen_sk_path`, `catatan`)
VALUES
    (1, 1, NULL, 'BAN-PT', '1234/SK/BAN-PT/Ak-PPJ/S/VI/2022',   '2022-06-15', '2022-06-15', '2027-06-15', 'Baik Sekali', 361.00,
        '/uploads/sk/SK_Akreditasi_TI_2022.pdf',
        'Akreditasi terakhir Prodi Teknik Informatika. Skor meningkat signifikan dari periode sebelumnya.'),

    (2, 1, NULL, 'BAN-PT', '0891/SK/BAN-PT/Akred/S/VIII/2017',   '2017-08-20', '2017-08-20', '2022-08-20', 'A', 370.00,
        '/uploads/sk/SK_Akreditasi_TI_2017.pdf',
        'Akreditasi periode sebelumnya menggunakan instrumen lama (7 standar).'),

    (3, 2, NULL, 'BAN-PT', '2345/SK/BAN-PT/Ak-PPJ/S/XII/2021',   '2021-12-20', '2021-12-20', '2026-12-20', 'Baik', 322.00,
        '/uploads/sk/SK_Akreditasi_SI_2021.pdf',
        'Akreditasi terakhir Prodi Sistem Informasi. Target reakreditasi naik ke Baik Sekali.'),

    (4, 3, 3, 'BAN-PT', '3456/SK/BAN-PT/Ak-PPJ/S/III/2023',     '2023-03-10', '2023-03-10', '2028-03-10', 'Unggul', 394.00,
        '/uploads/sk/SK_Akreditasi_MNJ_2023.pdf',
        'Prodi Manajemen berhasil meraih peringkat Unggul. Pencapaian tertinggi di universitas.'),

    (5, 4, NULL, 'BAN-PT', '4567/SK/BAN-PT/Ak-PPJ/S/IX/2022',    '2022-09-25', '2022-09-25', '2027-09-25', 'A', 365.00,
        '/uploads/sk/SK_Akreditasi_AKT_2022.pdf',
        'Akreditasi Prodi Akuntansi menggunakan instrumen lama. Akan beralih ke IAPS 4.0 pada reakreditasi berikutnya.'),

    (6, 5, NULL, 'BAN-PT', '5678/SK/BAN-PT/Ak-PPJ/S/XI/2021',    '2021-11-30', '2021-11-30', '2026-11-30', 'B', 310.00,
        '/uploads/sk/SK_Akreditasi_HKM_2021.pdf',
        'Akreditasi Prodi Ilmu Hukum. Target naik ke A/Baik Sekali pada reakreditasi mendatang.');

-- ---------------------------------------------------------------------------
-- 9. SEEDER: laporan_pendampingan (2 Laporan)
-- ---------------------------------------------------------------------------
INSERT INTO `laporan_pendampingan`
    (`id`, `jadwal_pendampingan_id`, `user_pelapor_id`, `judul_laporan`, `isi_laporan`, `hasil_kegiatan`, `tindak_lanjut`, `file_lampiran`, `status`, `approved_by`, `approved_at`)
VALUES
    (1, 1, 13,
        'Laporan Workshop Penyusunan LED Kriteria 1-4',
        'Workshop dilaksanakan pada 15 Juli 2026 di Ruang Rapat Utama Gedung Rektorat Lt. 3. Kegiatan diikuti oleh 15 peserta yang terdiri dari Tim Task Force TI, Admin Prodi, dan perwakilan dosen dari masing-masing bidang keahlian.\n\nMateri yang dibahas:\n1. Pengenalan format LED sesuai IAPS 4.0\n2. Teknik penulisan narasi evaluasi diri\n3. Penyusunan analisis SWOT program studi\n4. Review dan pembahasan Kriteria 1-4\n\nKegiatan berlangsung efektif dengan diskusi aktif dari seluruh peserta.',
        'Berhasil menyusun draft LED untuk Kriteria 1 (Visi Misi) dan Kriteria 2 (Tata Kelola). Kriteria 3 dan 4 masih dalam tahap penyusunan awal. Seluruh peserta memahami format dan standar penulisan LED.',
        'Menyelesaikan draft LED Kriteria 3-4 dalam 2 minggu. Melakukan review internal oleh Reviewer. Jadwalkan workshop lanjutan untuk Kriteria 5-9.',
        '/uploads/laporan/Laporan_Workshop_LED_K1-4_TI.pdf',
        'approved', 5, '2026-07-18 10:00:00'),

    (2, 2, 13,
        'Laporan Bimtek Pengisian LKPS Tabel 1-8',
        'Bimbingan teknis pengisian LKPS dilaksanakan selama 2 hari (22-23 Juli 2026) di Lab Komputer Fakultas Teknik Lt. 2. Diikuti oleh 10 peserta dari Tim Task Force TI dan operator data prodi.\n\nHari 1: Pengenalan template LKPS dan pengisian Tabel 1-4 (Data Umum, Mahasiswa, Dosen, Keuangan)\nHari 2: Pengisian Tabel 5-8 (Kurikulum, Penelitian, PkM, Luaran)\n\nSetiap peserta praktik langsung mengisi template LKPS menggunakan data real dari prodi.',
        'Template LKPS terisi 80% dari total tabel yang dibutuhkan. Tabel 1-6 sudah terisi lengkap. Tabel 7 (Penelitian) dan Tabel 8 (PkM) masih memerlukan data tambahan dari LPPM.',
        'Koordinasi dengan LPPM untuk melengkapi data penelitian dan PkM. Target penyelesaian LKPS lengkap: 5 Agustus 2026.',
        '/uploads/laporan/Laporan_Bimtek_LKPS_TI.pdf',
        'approved', 5, '2026-07-25 14:30:00');

-- ---------------------------------------------------------------------------
-- 10. SEEDER: notifikasi (Contoh Notifikasi)
-- ---------------------------------------------------------------------------
INSERT INTO `notifikasi`
    (`user_id`, `judul`, `pesan`, `tipe`, `link`, `is_read`, `created_at`)
VALUES
    -- Notifikasi untuk Admin Prodi TI
    (11, 'Pengajuan Akreditasi Diterima',
        'Pengajuan reakreditasi Prodi Teknik Informatika (PAK-2026-0001) telah diterima dan sedang dalam tahap review.',
        'success', '/pengajuan/detail/1', 1, '2026-07-05 08:00:00'),

    (11, 'Review Dokumen LED Selesai',
        'Review dokumen LED Teknik Informatika oleh Dr. Nugroho Pratama telah selesai. Rekomendasi: Layak. Silakan cek catatan review.',
        'info', '/review/detail/1', 1, '2026-08-20 17:00:00'),

    (11, 'Jadwal Simulasi Asesmen',
        'Simulasi asesmen lapangan untuk Prodi Teknik Informatika dijadwalkan pada 15-16 September 2026. Silakan persiapkan seluruh dokumen dan sarana prasarana.',
        'warning', '/jadwal/detail/3', 0, '2026-08-28 09:00:00'),

    -- Notifikasi untuk Kepala KPMA
    (3, 'Pengajuan Baru: Sistem Informasi',
        'Pengajuan reakreditasi baru dari Program Studi Sistem Informasi (PAK-2026-0002) memerlukan persetujuan Anda.',
        'info', '/pengajuan/detail/2', 0, '2026-08-01 10:30:00'),

    (3, 'Laporan Pendampingan Tersedia',
        'Laporan workshop penyusunan LED Kriteria 1-4 dan bimtek LKPS telah disetujui oleh Kabid. Silakan review laporan kegiatan.',
        'info', '/laporan/list', 1, '2026-07-25 15:00:00'),

    -- Notifikasi untuk Reviewer
    (7, 'Tugas Review Baru',
        'Anda ditugaskan untuk mereview dokumen pengajuan akreditasi Prodi Teknik Informatika (PAK-2026-0001). Batas waktu review: 25 Agustus 2026.',
        'warning', '/review/list', 1, '2026-08-05 08:00:00'),

    -- Notifikasi untuk Task Force
    (13, 'Tugas Upload Dokumen',
        'Silakan upload dokumen bukti kinerja penelitian dan PkM untuk Prodi Teknik Informatika. Batas waktu: 10 September 2026.',
        'danger', '/borang/upload/1', 0, '2026-08-25 09:00:00'),

    -- Notifikasi untuk Admin Prodi Manajemen
    (12, 'Akreditasi Disetujui',
        'Pengajuan perpanjangan akreditasi Prodi Manajemen (PAK-2026-0003) telah disetujui oleh Kepala KPMA. Selamat!',
        'success', '/pengajuan/detail/3', 1, '2026-07-20 15:00:00');

-- ---------------------------------------------------------------------------
-- 11. SEEDER: activity_log (Contoh Log Aktivitas)
-- ---------------------------------------------------------------------------
INSERT INTO `activity_log`
    (`user_id`, `aktivitas`, `modul`, `detail`, `ip_address`, `user_agent`, `created_at`)
VALUES
    (1,  'Login ke sistem',                       'auth',      '{"method":"form","role":"admin_universitas"}',        '192.168.1.10',   'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/128.0', '2026-08-30 08:15:00'),
    (11, 'Membuat pengajuan akreditasi baru',      'pengajuan', '{"nomor":"PAK-2026-0001","prodi":"Teknik Informatika","jenis":"reakreditasi"}', '192.168.1.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/128.0', '2026-07-01 09:00:00'),
    (11, 'Upload dokumen LED',                     'borang',    '{"file":"LED_TI_2026_v2.pdf","pengajuan":"PAK-2026-0001","kategori":"led"}',    '192.168.1.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/128.0', '2026-07-05 10:30:00'),
    (13, 'Upload dokumen LKPS',                    'borang',    '{"file":"LKPS_TI_2026.xlsx","pengajuan":"PAK-2026-0001","kategori":"lkps"}',    '192.168.1.55', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/128.0', '2026-07-10 14:00:00'),
    (7,  'Menyelesaikan review dokumen',            'review',    '{"pengajuan":"PAK-2026-0001","jenis":"review_dokumen","skor":85.50}',          '192.168.1.30', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/128.0', '2026-08-20 16:45:00'),
    (9,  'Menyelesaikan desk evaluation',           'review',    '{"pengajuan":"PAK-2026-0001","jenis":"desk_evaluation","skor":82.75}',         '192.168.1.35', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/128.0', '2026-08-25 15:00:00'),
    (5,  'Membuat jadwal pendampingan',             'jadwal',    '{"judul":"Workshop Penyusunan LED Kriteria 1-4","pengajuan":"PAK-2026-0001"}', '192.168.1.20', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/128.0', '2026-07-10 08:30:00'),
    (3,  'Menyetujui pengajuan akreditasi',         'pengajuan', '{"nomor":"PAK-2026-0003","prodi":"Manajemen","status":"disetujui"}',           '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/128.0', '2026-07-20 14:30:00'),
    (12, 'Login ke sistem',                        'auth',      '{"method":"form","role":"admin_prodi"}',              '192.168.1.52', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/128.0', '2026-08-29 08:30:00'),
    (5,  'Menyetujui laporan pendampingan',         'laporan',   '{"judul":"Laporan Workshop Penyusunan LED Kriteria 1-4","status":"approved"}', '192.168.1.20', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/128.0', '2026-07-18 10:00:00');

-- ============================================================================
-- RINGKASAN DATA SEEDER
-- ============================================================================
-- program_studi        : 6 records
-- users                : 14 records (2 per role × 7 roles)
-- instrumen_akreditasi : 3 records
-- pengajuan_akreditasi : 4 records (draft, diajukan, review, disetujui)
-- borang_files         : 8 records
-- review_borang        : 4 records
-- jadwal_pendampingan  : 4 records (2 selesai, 2 dijadwalkan)
-- riwayat_akreditasi   : 6 records
-- laporan_pendampingan : 2 records
-- notifikasi           : 8 records
-- activity_log         : 10 records
-- ============================================================================
-- TOTAL: 65 records
-- ============================================================================
-- AKUN LOGIN UNTUK TESTING:
-- ============================================================================
-- | Email                              | Password    | Role               |
-- |------------------------------------|-------------|--------------------|
-- | admin1@universitas.ac.id           | password123 | admin_universitas  |
-- | admin2@universitas.ac.id           | password123 | admin_universitas  |
-- | kepala.kpma@universitas.ac.id      | password123 | kepala_kpma        |
-- | wakil.kpma@universitas.ac.id       | password123 | kepala_kpma        |
-- | kabid.akreditasi@universitas.ac.id | password123 | kabid_kpma         |
-- | kabid.mutu@universitas.ac.id       | password123 | kabid_kpma         |
-- | reviewer1@universitas.ac.id        | password123 | reviewer_internal  |
-- | reviewer2@universitas.ac.id        | password123 | reviewer_internal  |
-- | asesor1@universitas.ac.id          | password123 | asesor_internal    |
-- | asesor2@universitas.ac.id          | password123 | asesor_internal    |
-- | admin.ti@universitas.ac.id         | password123 | admin_prodi        |
-- | admin.mnj@universitas.ac.id        | password123 | admin_prodi        |
-- | taskforce1@universitas.ac.id       | password123 | team_task_force    |
-- | taskforce2@universitas.ac.id       | password123 | team_task_force    |
-- ============================================================================
