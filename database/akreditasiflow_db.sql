-- ============================================================================
-- SIM AKREDITASI — Sistem Manajemen Akreditasi Perguruan Tinggi
-- Database: akreditasiflow_db
-- Version: 1.0.0
-- Created: 2026-09-01
-- Engine: InnoDB | Charset: utf8mb4 | Collation: utf8mb4_unicode_ci
-- ============================================================================
-- Deskripsi:
--   DDL lengkap untuk database SIM Akreditasi meliputi 11 tabel utama
--   dengan dukungan RBAC (Role-Based Access Control) untuk 7 peran pengguna.
-- ============================================================================

-- ---------------------------------------------------------------------------
-- 0. BUAT DATABASE
-- ---------------------------------------------------------------------------
DROP DATABASE IF EXISTS `akreditasiflow_db`;
CREATE DATABASE `akreditasiflow_db`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE `akreditasiflow_db`;

-- ---------------------------------------------------------------------------
-- 1. TABEL: program_studi
--    Menyimpan data Program Studi beserta status akreditasi terakhir.
-- ---------------------------------------------------------------------------
CREATE TABLE `program_studi` (
    `id`                    INT             NOT NULL AUTO_INCREMENT,
    `kode_prodi`            VARCHAR(20)     NOT NULL,
    `nama_prodi`            VARCHAR(150)    NOT NULL,
    `jenjang`               ENUM('D3','D4','S1','S2','S3') NOT NULL DEFAULT 'S1',
    `fakultas`              VARCHAR(150)    NOT NULL,
    `akreditasi_terakhir`   VARCHAR(20)     DEFAULT NULL COMMENT 'Nilai: Unggul/Baik Sekali/Baik/A/B/C/Tidak Terakreditasi',
    `tanggal_kadaluarsa`    DATE            DEFAULT NULL COMMENT 'Tanggal kadaluarsa akreditasi terakhir',
    `sk_akreditasi`         VARCHAR(100)    DEFAULT NULL COMMENT 'Nomor SK akreditasi terakhir',
    `kaprodi`               VARCHAR(150)    DEFAULT NULL COMMENT 'Nama Ketua Program Studi',
    `no_telepon_prodi`      VARCHAR(20)     DEFAULT NULL,
    `email_prodi`           VARCHAR(150)    DEFAULT NULL,
    `is_active`             TINYINT(1)      NOT NULL DEFAULT 1,
    `created_at`            TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`            TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_kode_prodi` (`kode_prodi`),
    INDEX `idx_fakultas` (`fakultas`),
    INDEX `idx_jenjang` (`jenjang`),
    INDEX `idx_akreditasi_kadaluarsa` (`tanggal_kadaluarsa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Data Program Studi dan status akreditasi';

-- ---------------------------------------------------------------------------
-- 2. TABEL: users
--    Pengguna sistem dengan 7 role RBAC.
--    Role: admin_universitas, kepala_kpma, kabid_kpma, reviewer_internal,
--          asesor_internal, admin_prodi, team_task_force
-- ---------------------------------------------------------------------------
CREATE TABLE `users` (
    `id`                INT             NOT NULL AUTO_INCREMENT,
    `nip`               VARCHAR(30)     NOT NULL,
    `nama_lengkap`      VARCHAR(150)    NOT NULL,
    `email`             VARCHAR(150)    NOT NULL,
    `password`          VARCHAR(255)    NOT NULL COMMENT 'Hashed with bcrypt',
    `role`              ENUM(
                            'admin_universitas',
                            'kepala_kpma',
                            'kabid_kpma',
                            'reviewer_internal',
                            'asesor_internal',
                            'admin_prodi',
                            'team_task_force'
                        ) NOT NULL,
    `program_studi_id`  INT             DEFAULT NULL COMMENT 'FK → program_studi (untuk admin_prodi & team_task_force)',
    `jabatan`           VARCHAR(100)    DEFAULT NULL COMMENT 'Jabatan fungsional',
    `no_telepon`        VARCHAR(20)     DEFAULT NULL,
    `foto_profil`       VARCHAR(255)    DEFAULT NULL COMMENT 'Path file foto profil',
    `is_active`         TINYINT(1)      NOT NULL DEFAULT 1,
    `last_login`        DATETIME        DEFAULT NULL,
    `created_at`        TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`        TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_nip` (`nip`),
    UNIQUE KEY `uq_email` (`email`),
    INDEX `idx_role` (`role`),
    INDEX `idx_program_studi` (`program_studi_id`),
    INDEX `idx_is_active` (`is_active`),

    CONSTRAINT `fk_users_program_studi`
        FOREIGN KEY (`program_studi_id`)
        REFERENCES `program_studi` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Pengguna sistem dengan 7 role RBAC';

-- ---------------------------------------------------------------------------
-- 3. TABEL: instrumen_akreditasi
--    Instrumen/standar yang digunakan dalam proses akreditasi.
-- ---------------------------------------------------------------------------
CREATE TABLE `instrumen_akreditasi` (
    `id`                    INT             NOT NULL AUTO_INCREMENT,
    `kode_instrumen`        VARCHAR(30)     NOT NULL,
    `nama_instrumen`        VARCHAR(200)    NOT NULL,
    `versi`                 VARCHAR(20)     DEFAULT NULL,
    `lembaga_akreditasi`    VARCHAR(150)    NOT NULL COMMENT 'BAN-PT, LAM-PTKes, dll.',
    `deskripsi`             TEXT            DEFAULT NULL,
    `jumlah_standar`        INT             NOT NULL DEFAULT 0 COMMENT 'Jumlah standar/kriteria dalam instrumen',
    `is_active`             TINYINT(1)      NOT NULL DEFAULT 1,
    `created_at`            TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`            TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_kode_instrumen` (`kode_instrumen`),
    INDEX `idx_lembaga` (`lembaga_akreditasi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Instrumen dan standar akreditasi';

-- ---------------------------------------------------------------------------
-- 4. TABEL: pengajuan_akreditasi
--    Pengajuan proses akreditasi oleh Program Studi.
-- ---------------------------------------------------------------------------
CREATE TABLE `pengajuan_akreditasi` (
    `id`                        INT             NOT NULL AUTO_INCREMENT,
    `program_studi_id`          INT             NOT NULL,
    `instrumen_akreditasi_id`   INT             NOT NULL,
    `user_pengaju_id`           INT             NOT NULL COMMENT 'User yang membuat pengajuan',
    `nomor_pengajuan`           VARCHAR(50)     NOT NULL COMMENT 'Nomor unik pengajuan (auto-generate)',
    `jenis_pengajuan`           ENUM(
                                    'akreditasi_baru',
                                    'reakreditasi',
                                    'perpanjangan'
                                ) NOT NULL DEFAULT 'reakreditasi',
    `tanggal_pengajuan`         DATE            NOT NULL,
    `tanggal_target`            DATE            DEFAULT NULL COMMENT 'Target tanggal selesai',
    `status`                    ENUM(
                                    'draft',
                                    'diajukan',
                                    'review',
                                    'revisi',
                                    'disetujui',
                                    'ditolak',
                                    'selesai'
                                ) NOT NULL DEFAULT 'draft',
    `catatan`                   TEXT            DEFAULT NULL,
    `approved_by`               INT             DEFAULT NULL COMMENT 'User yang menyetujui pengajuan',
    `approved_at`               DATETIME        DEFAULT NULL,
    `created_at`                TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`                TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_nomor_pengajuan` (`nomor_pengajuan`),
    INDEX `idx_status` (`status`),
    INDEX `idx_prodi_status` (`program_studi_id`, `status`),
    INDEX `idx_tanggal_pengajuan` (`tanggal_pengajuan`),
    INDEX `idx_jenis_pengajuan` (`jenis_pengajuan`),

    CONSTRAINT `fk_pengajuan_program_studi`
        FOREIGN KEY (`program_studi_id`)
        REFERENCES `program_studi` (`id`)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT `fk_pengajuan_instrumen`
        FOREIGN KEY (`instrumen_akreditasi_id`)
        REFERENCES `instrumen_akreditasi` (`id`)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT `fk_pengajuan_user_pengaju`
        FOREIGN KEY (`user_pengaju_id`)
        REFERENCES `users` (`id`)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT `fk_pengajuan_approved_by`
        FOREIGN KEY (`approved_by`)
        REFERENCES `users` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Pengajuan proses akreditasi program studi';

-- ---------------------------------------------------------------------------
-- 5. TABEL: borang_files
--    Dokumen borang yang diupload untuk pengajuan akreditasi.
-- ---------------------------------------------------------------------------
CREATE TABLE `borang_files` (
    `id`                        INT             NOT NULL AUTO_INCREMENT,
    `pengajuan_akreditasi_id`   INT             NOT NULL,
    `uploaded_by`               INT             NOT NULL COMMENT 'User yang mengupload',
    `nama_dokumen`              VARCHAR(200)    NOT NULL,
    `kategori_dokumen`          ENUM(
                                    'led',
                                    'lkps',
                                    'dokumen_pendukung',
                                    'surat_pengantar',
                                    'sk_penetapan',
                                    'bukti_kinerja'
                                ) NOT NULL,
    `nomor_standar`             VARCHAR(10)     DEFAULT NULL COMMENT 'Nomor standar/kriteria terkait',
    `file_path`                 VARCHAR(500)    NOT NULL COMMENT 'Path file di server',
    `file_name`                 VARCHAR(255)    NOT NULL COMMENT 'Nama file asli saat upload',
    `file_size`                 BIGINT          NOT NULL DEFAULT 0 COMMENT 'Ukuran file dalam bytes',
    `file_type`                 VARCHAR(50)     NOT NULL COMMENT 'MIME type (application/pdf, dll)',
    `versi_dokumen`             INT             NOT NULL DEFAULT 1,
    `status`                    ENUM(
                                    'uploaded',
                                    'verified',
                                    'rejected',
                                    'revised'
                                ) NOT NULL DEFAULT 'uploaded',
    `catatan_verifikasi`        TEXT            DEFAULT NULL COMMENT 'Catatan dari reviewer/verifikator',
    `verified_by`               INT             DEFAULT NULL COMMENT 'User yang memverifikasi',
    `verified_at`               DATETIME        DEFAULT NULL,
    `created_at`                TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`                TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    INDEX `idx_pengajuan` (`pengajuan_akreditasi_id`),
    INDEX `idx_kategori` (`kategori_dokumen`),
    INDEX `idx_pengajuan_kategori` (`pengajuan_akreditasi_id`, `kategori_dokumen`),
    INDEX `idx_status` (`status`),
    INDEX `idx_uploaded_by` (`uploaded_by`),

    CONSTRAINT `fk_borang_pengajuan`
        FOREIGN KEY (`pengajuan_akreditasi_id`)
        REFERENCES `pengajuan_akreditasi` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    CONSTRAINT `fk_borang_uploaded_by`
        FOREIGN KEY (`uploaded_by`)
        REFERENCES `users` (`id`)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT `fk_borang_verified_by`
        FOREIGN KEY (`verified_by`)
        REFERENCES `users` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Dokumen borang akreditasi yang diupload';

-- ---------------------------------------------------------------------------
-- 6. TABEL: review_borang
--    Review dan penilaian dokumen borang oleh reviewer/asesor.
-- ---------------------------------------------------------------------------
CREATE TABLE `review_borang` (
    `id`                        INT             NOT NULL AUTO_INCREMENT,
    `pengajuan_akreditasi_id`   INT             NOT NULL,
    `reviewer_id`               INT             NOT NULL COMMENT 'User reviewer/asesor',
    `borang_file_id`            INT             DEFAULT NULL COMMENT 'Dokumen spesifik yang di-review (opsional)',
    `jenis_review`              ENUM(
                                    'desk_evaluation',
                                    'visitasi',
                                    'review_dokumen',
                                    'review_substansi'
                                ) NOT NULL DEFAULT 'review_dokumen',
    `skor_penilaian`            DECIMAL(5,2)    DEFAULT NULL COMMENT 'Skor penilaian (skala sesuai instrumen)',
    `catatan_review`            TEXT            DEFAULT NULL COMMENT 'Feedback dan catatan review',
    `rekomendasi`               ENUM(
                                    'layak',
                                    'perlu_revisi',
                                    'tidak_layak'
                                ) DEFAULT NULL,
    `status_review`             ENUM(
                                    'pending',
                                    'in_progress',
                                    'completed'
                                ) NOT NULL DEFAULT 'pending',
    `tanggal_review`            DATE            DEFAULT NULL,
    `created_at`                TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`                TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    INDEX `idx_pengajuan` (`pengajuan_akreditasi_id`),
    INDEX `idx_reviewer` (`reviewer_id`),
    INDEX `idx_pengajuan_reviewer` (`pengajuan_akreditasi_id`, `reviewer_id`),
    INDEX `idx_status_review` (`status_review`),
    INDEX `idx_jenis_review` (`jenis_review`),

    CONSTRAINT `fk_review_pengajuan`
        FOREIGN KEY (`pengajuan_akreditasi_id`)
        REFERENCES `pengajuan_akreditasi` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    CONSTRAINT `fk_review_reviewer`
        FOREIGN KEY (`reviewer_id`)
        REFERENCES `users` (`id`)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT `fk_review_borang_file`
        FOREIGN KEY (`borang_file_id`)
        REFERENCES `borang_files` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Review dan penilaian dokumen borang akreditasi';

-- ---------------------------------------------------------------------------
-- 7. TABEL: jadwal_pendampingan
--    Jadwal kegiatan pendampingan akreditasi.
-- ---------------------------------------------------------------------------
CREATE TABLE `jadwal_pendampingan` (
    `id`                        INT             NOT NULL AUTO_INCREMENT,
    `pengajuan_akreditasi_id`   INT             NOT NULL,
    `judul_kegiatan`            VARCHAR(200)    NOT NULL,
    `jenis_kegiatan`            ENUM(
                                    'workshop',
                                    'bimtek',
                                    'pendampingan',
                                    'simulasi',
                                    'asesmen'
                                ) NOT NULL DEFAULT 'pendampingan',
    `tanggal_mulai`             DATETIME        NOT NULL,
    `tanggal_selesai`           DATETIME        NOT NULL,
    `lokasi`                    VARCHAR(200)    DEFAULT NULL,
    `deskripsi`                 TEXT            DEFAULT NULL,
    `penanggung_jawab_id`       INT             NOT NULL COMMENT 'User penanggung jawab kegiatan',
    `status`                    ENUM(
                                    'dijadwalkan',
                                    'berlangsung',
                                    'selesai',
                                    'dibatalkan'
                                ) NOT NULL DEFAULT 'dijadwalkan',
    `created_at`                TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`                TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    INDEX `idx_pengajuan` (`pengajuan_akreditasi_id`),
    INDEX `idx_tanggal_mulai` (`tanggal_mulai`),
    INDEX `idx_status` (`status`),
    INDEX `idx_penanggung_jawab` (`penanggung_jawab_id`),
    INDEX `idx_jenis_kegiatan` (`jenis_kegiatan`),
    INDEX `idx_tanggal_status` (`tanggal_mulai`, `status`),

    CONSTRAINT `fk_jadwal_pengajuan`
        FOREIGN KEY (`pengajuan_akreditasi_id`)
        REFERENCES `pengajuan_akreditasi` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    CONSTRAINT `fk_jadwal_penanggung_jawab`
        FOREIGN KEY (`penanggung_jawab_id`)
        REFERENCES `users` (`id`)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Jadwal kegiatan pendampingan akreditasi';

-- ---------------------------------------------------------------------------
-- 8. TABEL: riwayat_akreditasi
--    Riwayat hasil akreditasi setiap Program Studi.
-- ---------------------------------------------------------------------------
CREATE TABLE `riwayat_akreditasi` (
    `id`                        INT             NOT NULL AUTO_INCREMENT,
    `program_studi_id`          INT             NOT NULL,
    `pengajuan_akreditasi_id`   INT             DEFAULT NULL COMMENT 'FK ke pengajuan (jika melalui sistem)',
    `lembaga_akreditasi`        VARCHAR(150)    NOT NULL COMMENT 'BAN-PT, LAM, dll.',
    `nomor_sk`                  VARCHAR(100)    NOT NULL COMMENT 'Nomor SK Keputusan',
    `tanggal_sk`                DATE            NOT NULL,
    `masa_berlaku_mulai`        DATE            NOT NULL,
    `masa_berlaku_selesai`      DATE            NOT NULL,
    `nilai_akreditasi`          VARCHAR(20)     NOT NULL COMMENT 'Unggul/Baik Sekali/Baik/A/B/C',
    `skor_akhir`                DECIMAL(5,2)    DEFAULT NULL COMMENT 'Skor numerik akhir',
    `dokumen_sk_path`           VARCHAR(500)    DEFAULT NULL COMMENT 'Path file SK digital',
    `catatan`                   TEXT            DEFAULT NULL,
    `created_at`                TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`                TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    INDEX `idx_program_studi` (`program_studi_id`),
    INDEX `idx_masa_berlaku` (`masa_berlaku_selesai`),
    INDEX `idx_nilai` (`nilai_akreditasi`),
    INDEX `idx_prodi_berlaku` (`program_studi_id`, `masa_berlaku_selesai`),

    CONSTRAINT `fk_riwayat_program_studi`
        FOREIGN KEY (`program_studi_id`)
        REFERENCES `program_studi` (`id`)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT `fk_riwayat_pengajuan`
        FOREIGN KEY (`pengajuan_akreditasi_id`)
        REFERENCES `pengajuan_akreditasi` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Riwayat hasil akreditasi program studi';

-- ---------------------------------------------------------------------------
-- 9. TABEL: laporan_pendampingan
--    Laporan kegiatan pendampingan yang dibuat setelah kegiatan selesai.
-- ---------------------------------------------------------------------------
CREATE TABLE `laporan_pendampingan` (
    `id`                        INT             NOT NULL AUTO_INCREMENT,
    `jadwal_pendampingan_id`    INT             NOT NULL,
    `user_pelapor_id`           INT             NOT NULL COMMENT 'User yang membuat laporan',
    `judul_laporan`             VARCHAR(200)    NOT NULL,
    `isi_laporan`               TEXT            NOT NULL,
    `hasil_kegiatan`            TEXT            DEFAULT NULL COMMENT 'Ringkasan hasil/outcome kegiatan',
    `tindak_lanjut`             TEXT            DEFAULT NULL COMMENT 'Rencana tindak lanjut',
    `file_lampiran`             VARCHAR(500)    DEFAULT NULL COMMENT 'Path file lampiran',
    `status`                    ENUM(
                                    'draft',
                                    'submitted',
                                    'approved',
                                    'rejected'
                                ) NOT NULL DEFAULT 'draft',
    `approved_by`               INT             DEFAULT NULL COMMENT 'User yang menyetujui laporan',
    `approved_at`               DATETIME        DEFAULT NULL,
    `created_at`                TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`                TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    INDEX `idx_jadwal` (`jadwal_pendampingan_id`),
    INDEX `idx_pelapor` (`user_pelapor_id`),
    INDEX `idx_status` (`status`),

    CONSTRAINT `fk_laporan_jadwal`
        FOREIGN KEY (`jadwal_pendampingan_id`)
        REFERENCES `jadwal_pendampingan` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    CONSTRAINT `fk_laporan_pelapor`
        FOREIGN KEY (`user_pelapor_id`)
        REFERENCES `users` (`id`)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT `fk_laporan_approved_by`
        FOREIGN KEY (`approved_by`)
        REFERENCES `users` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Laporan kegiatan pendampingan akreditasi';

-- ---------------------------------------------------------------------------
-- 10. TABEL: notifikasi
--     Sistem notifikasi untuk semua pengguna.
-- ---------------------------------------------------------------------------
CREATE TABLE `notifikasi` (
    `id`            INT             NOT NULL AUTO_INCREMENT,
    `user_id`       INT             NOT NULL COMMENT 'Penerima notifikasi',
    `judul`         VARCHAR(200)    NOT NULL,
    `pesan`         TEXT            NOT NULL,
    `tipe`          ENUM('info','warning','success','danger') NOT NULL DEFAULT 'info',
    `link`          VARCHAR(500)    DEFAULT NULL COMMENT 'URL terkait notifikasi',
    `is_read`       TINYINT(1)      NOT NULL DEFAULT 0,
    `created_at`    TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    INDEX `idx_user` (`user_id`),
    INDEX `idx_user_read` (`user_id`, `is_read`),
    INDEX `idx_created` (`created_at`),

    CONSTRAINT `fk_notifikasi_user`
        FOREIGN KEY (`user_id`)
        REFERENCES `users` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Sistem notifikasi pengguna';

-- ---------------------------------------------------------------------------
-- 11. TABEL: activity_log
--     Log aktivitas sistem untuk audit trail.
-- ---------------------------------------------------------------------------
CREATE TABLE `activity_log` (
    `id`            BIGINT          NOT NULL AUTO_INCREMENT,
    `user_id`       INT             DEFAULT NULL COMMENT 'User pelaku (NULL jika sistem)',
    `aktivitas`     VARCHAR(200)    NOT NULL COMMENT 'Deskripsi singkat aktivitas',
    `modul`         VARCHAR(100)    NOT NULL COMMENT 'Modul terkait (users, pengajuan, borang, dll)',
    `detail`        TEXT            DEFAULT NULL COMMENT 'Detail JSON aktivitas',
    `ip_address`    VARCHAR(45)     DEFAULT NULL COMMENT 'Mendukung IPv6',
    `user_agent`    VARCHAR(500)    DEFAULT NULL,
    `created_at`    TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    INDEX `idx_user` (`user_id`),
    INDEX `idx_modul` (`modul`),
    INDEX `idx_created` (`created_at`),
    INDEX `idx_user_modul` (`user_id`, `modul`),

    CONSTRAINT `fk_activity_user`
        FOREIGN KEY (`user_id`)
        REFERENCES `users` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Log aktivitas sistem untuk audit trail';

-- ============================================================================
-- AKHIR DDL — 11 Tabel berhasil dibuat
-- ============================================================================
