-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 02 Sep 2026 pada 05.55
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `akreditasiflow_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `activity_log`
--

CREATE TABLE `activity_log` (
  `id` bigint(20) NOT NULL,
  `user_id` int(11) DEFAULT NULL COMMENT 'User pelaku (NULL jika sistem)',
  `aktivitas` varchar(200) NOT NULL COMMENT 'Deskripsi singkat aktivitas',
  `modul` varchar(100) NOT NULL COMMENT 'Modul terkait (users, pengajuan, borang, dll)',
  `detail` text DEFAULT NULL COMMENT 'Detail JSON aktivitas',
  `ip_address` varchar(45) DEFAULT NULL COMMENT 'Mendukung IPv6',
  `user_agent` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Log aktivitas sistem untuk audit trail';

--
-- Dumping data untuk tabel `activity_log`
--

INSERT INTO `activity_log` (`id`, `user_id`, `aktivitas`, `modul`, `detail`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 1, 'Login ke sistem', 'auth', '{\"method\":\"form\",\"role\":\"admin_universitas\"}', '192.168.1.10', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/128.0', '2026-08-30 01:15:00'),
(2, 11, 'Membuat pengajuan akreditasi baru', 'pengajuan', '{\"nomor\":\"PAK-2026-0001\",\"prodi\":\"Teknik Informatika\",\"jenis\":\"reakreditasi\"}', '192.168.1.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/128.0', '2026-07-01 02:00:00'),
(3, 11, 'Upload dokumen LED', 'borang', '{\"file\":\"LED_TI_2026_v2.pdf\",\"pengajuan\":\"PAK-2026-0001\",\"kategori\":\"led\"}', '192.168.1.50', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/128.0', '2026-07-05 03:30:00'),
(4, 13, 'Upload dokumen LKPS', 'borang', '{\"file\":\"LKPS_TI_2026.xlsx\",\"pengajuan\":\"PAK-2026-0001\",\"kategori\":\"lkps\"}', '192.168.1.55', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/128.0', '2026-07-10 07:00:00'),
(5, 7, 'Menyelesaikan review dokumen', 'review', '{\"pengajuan\":\"PAK-2026-0001\",\"jenis\":\"review_dokumen\",\"skor\":85.50}', '192.168.1.30', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/128.0', '2026-08-20 09:45:00'),
(6, 9, 'Menyelesaikan desk evaluation', 'review', '{\"pengajuan\":\"PAK-2026-0001\",\"jenis\":\"desk_evaluation\",\"skor\":82.75}', '192.168.1.35', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/128.0', '2026-08-25 08:00:00'),
(7, 5, 'Membuat jadwal pendampingan', 'jadwal', '{\"judul\":\"Workshop Penyusunan LED Kriteria 1-4\",\"pengajuan\":\"PAK-2026-0001\"}', '192.168.1.20', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/128.0', '2026-07-10 01:30:00'),
(8, 3, 'Menyetujui pengajuan akreditasi', 'pengajuan', '{\"nomor\":\"PAK-2026-0003\",\"prodi\":\"Manajemen\",\"status\":\"disetujui\"}', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/128.0', '2026-07-20 07:30:00'),
(9, 12, 'Login ke sistem', 'auth', '{\"method\":\"form\",\"role\":\"admin_prodi\"}', '192.168.1.52', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/128.0', '2026-08-29 01:30:00'),
(10, 5, 'Menyetujui laporan pendampingan', 'laporan', '{\"judul\":\"Laporan Workshop Penyusunan LED Kriteria 1-4\",\"status\":\"approved\"}', '192.168.1.20', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/128.0', '2026-07-18 03:00:00'),
(11, 1, 'Login ke sistem', 'auth', '{\"method\":\"form\",\"role\":\"admin_universitas\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 OPR/134.0.0.0', '2026-09-01 06:09:55'),
(12, 1, 'Login ke sistem', 'auth', '{\"method\":\"form_login\",\"role\":\"admin_universitas\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-01 08:03:15'),
(13, 1, 'Login ke sistem', 'auth', '{\"method\":\"form_login\",\"role\":\"admin_universitas\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-01 08:41:28');

-- --------------------------------------------------------

--
-- Struktur dari tabel `borang_files`
--

CREATE TABLE `borang_files` (
  `id` int(11) NOT NULL,
  `pengajuan_akreditasi_id` int(11) NOT NULL,
  `uploaded_by` int(11) NOT NULL COMMENT 'User yang mengupload',
  `nama_dokumen` varchar(200) NOT NULL,
  `kategori_dokumen` enum('led','lkps','dokumen_pendukung','surat_pengantar','sk_penetapan','bukti_kinerja') NOT NULL,
  `nomor_standar` varchar(10) DEFAULT NULL COMMENT 'Nomor standar/kriteria terkait',
  `file_path` varchar(500) NOT NULL COMMENT 'Path file di server',
  `file_name` varchar(255) NOT NULL COMMENT 'Nama file asli saat upload',
  `file_size` bigint(20) NOT NULL DEFAULT 0 COMMENT 'Ukuran file dalam bytes',
  `file_type` varchar(50) NOT NULL COMMENT 'MIME type (application/pdf, dll)',
  `versi_dokumen` int(11) NOT NULL DEFAULT 1,
  `status` enum('uploaded','verified','rejected','revised') NOT NULL DEFAULT 'uploaded',
  `catatan_verifikasi` text DEFAULT NULL COMMENT 'Catatan dari reviewer/verifikator',
  `verified_by` int(11) DEFAULT NULL COMMENT 'User yang memverifikasi',
  `verified_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Dokumen borang akreditasi yang diupload';

--
-- Dumping data untuk tabel `borang_files`
--

INSERT INTO `borang_files` (`id`, `pengajuan_akreditasi_id`, `uploaded_by`, `nama_dokumen`, `kategori_dokumen`, `nomor_standar`, `file_path`, `file_name`, `file_size`, `file_type`, `versi_dokumen`, `status`, `catatan_verifikasi`, `verified_by`, `verified_at`, `created_at`, `updated_at`) VALUES
(1, 1, 11, 'Laporan Evaluasi Diri - Teknik Informatika 2026', 'led', NULL, '/uploads/borang/2026/PAK-2026-0001/LED_TI_2026_v2.pdf', 'LED_TI_2026_v2.pdf', 15728640, 'application/pdf', 2, 'verified', 'Dokumen LED telah lengkap dan sesuai format. Sudah direvisi dari versi 1.', 7, '2026-08-10 10:30:00', '2026-09-01 05:53:38', '2026-09-01 05:53:38'),
(2, 1, 13, 'Laporan Kinerja Program Studi - Teknik Informatika', 'lkps', NULL, '/uploads/borang/2026/PAK-2026-0001/LKPS_TI_2026.xlsx', 'LKPS_TI_2026.xlsx', 5242880, 'application/vnd.openxmlformats-officedocument.spre', 1, 'verified', 'Data LKPS telah terisi lengkap semua tabel.', 7, '2026-08-12 14:15:00', '2026-09-01 05:53:38', '2026-09-01 05:53:38'),
(3, 1, 13, 'SK Penetapan Dosen Tetap', 'sk_penetapan', 'K3', '/uploads/borang/2026/PAK-2026-0001/SK_Dosen_Tetap_TI.pdf', 'SK_Dosen_Tetap_TI.pdf', 2097152, 'application/pdf', 1, 'verified', NULL, 7, '2026-08-15 09:00:00', '2026-09-01 05:53:38', '2026-09-01 05:53:38'),
(4, 1, 11, 'Bukti Kinerja Penelitian Dosen', 'bukti_kinerja', 'K7', '/uploads/borang/2026/PAK-2026-0001/Bukti_Penelitian_TI.pdf', 'Bukti_Penelitian_TI.pdf', 8388608, 'application/pdf', 1, 'uploaded', NULL, NULL, NULL, '2026-09-01 05:53:38', '2026-09-01 05:53:38'),
(5, 2, 11, 'Laporan Evaluasi Diri - Sistem Informasi', 'led', NULL, '/uploads/borang/2026/PAK-2026-0002/LED_SI_2026.pdf', 'LED_SI_2026.pdf', 12582912, 'application/pdf', 1, 'uploaded', NULL, NULL, NULL, '2026-09-01 05:53:38', '2026-09-01 05:53:38'),
(6, 2, 11, 'Surat Pengantar Pengajuan Akreditasi SI', 'surat_pengantar', NULL, '/uploads/borang/2026/PAK-2026-0002/Surat_Pengantar_SI.pdf', 'Surat_Pengantar_SI.pdf', 1048576, 'application/pdf', 1, 'uploaded', NULL, NULL, NULL, '2026-09-01 05:53:38', '2026-09-01 05:53:38'),
(7, 3, 12, 'Laporan Evaluasi Diri - Manajemen', 'led', NULL, '/uploads/borang/2026/PAK-2026-0003/LED_MNJ_2026.pdf', 'LED_MNJ_2026.pdf', 18874368, 'application/pdf', 3, 'verified', 'Dokumen LED final, sudah direvisi 2 kali sesuai masukan reviewer.', 8, '2026-07-15 16:00:00', '2026-09-01 05:53:38', '2026-09-01 05:53:38'),
(8, 3, 12, 'LKPS - Manajemen', 'lkps', NULL, '/uploads/borang/2026/PAK-2026-0003/LKPS_MNJ_2026.xlsx', 'LKPS_MNJ_2026.xlsx', 6291456, 'application/vnd.openxmlformats-officedocument.spre', 2, 'verified', 'Tabel LKPS sudah lengkap dan valid.', 8, '2026-07-18 11:30:00', '2026-09-01 05:53:38', '2026-09-01 05:53:38');

-- --------------------------------------------------------

--
-- Struktur dari tabel `instrumen_akreditasi`
--

CREATE TABLE `instrumen_akreditasi` (
  `id` int(11) NOT NULL,
  `kode_instrumen` varchar(30) NOT NULL,
  `nama_instrumen` varchar(200) NOT NULL,
  `versi` varchar(20) DEFAULT NULL,
  `lembaga_akreditasi` varchar(150) NOT NULL COMMENT 'BAN-PT, LAM-PTKes, dll.',
  `deskripsi` text DEFAULT NULL,
  `jumlah_standar` int(11) NOT NULL DEFAULT 0 COMMENT 'Jumlah standar/kriteria dalam instrumen',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Instrumen dan standar akreditasi';

--
-- Dumping data untuk tabel `instrumen_akreditasi`
--

INSERT INTO `instrumen_akreditasi` (`id`, `kode_instrumen`, `nama_instrumen`, `versi`, `lembaga_akreditasi`, `deskripsi`, `jumlah_standar`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'IAPS-4.0', 'Instrumen Akreditasi Program Studi 4.0', '4.0', 'BAN-PT', 'Instrumen penilaian akreditasi program studi berdasarkan 9 Kriteria yang ditetapkan oleh BAN-PT. Mencakup penilaian Laporan Evaluasi Diri (LED) dan Laporan Kinerja Program Studi (LKPS).', 9, 1, '2026-09-01 05:53:38', '2026-09-01 05:53:38'),
(2, 'IAPT-3.0', 'Instrumen Akreditasi Perguruan Tinggi 3.0', '3.0', 'BAN-PT', 'Instrumen penilaian akreditasi institusi/perguruan tinggi secara keseluruhan oleh BAN-PT berdasarkan standar nasional pendidikan tinggi.', 9, 1, '2026-09-01 05:53:38', '2026-09-01 05:53:38'),
(3, 'LAM-INFOKOM', 'Instrumen Akreditasi LAM Infokom', '1.0', 'LAM Infokom', 'Instrumen akreditasi khusus untuk program studi bidang Informatika dan Komputer yang dikelola oleh Lembaga Akreditasi Mandiri Informatika dan Komputer.', 7, 1, '2026-09-01 05:53:38', '2026-09-01 05:53:38');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jadwal_pendampingan`
--

CREATE TABLE `jadwal_pendampingan` (
  `id` int(11) NOT NULL,
  `pengajuan_akreditasi_id` int(11) NOT NULL,
  `judul_kegiatan` varchar(200) NOT NULL,
  `jenis_kegiatan` enum('workshop','bimtek','pendampingan','simulasi','asesmen') NOT NULL DEFAULT 'pendampingan',
  `tanggal_mulai` datetime NOT NULL,
  `tanggal_selesai` datetime NOT NULL,
  `lokasi` varchar(200) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `penanggung_jawab_id` int(11) NOT NULL COMMENT 'User penanggung jawab kegiatan',
  `status` enum('dijadwalkan','berlangsung','selesai','dibatalkan') NOT NULL DEFAULT 'dijadwalkan',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Jadwal kegiatan pendampingan akreditasi';

--
-- Dumping data untuk tabel `jadwal_pendampingan`
--

INSERT INTO `jadwal_pendampingan` (`id`, `pengajuan_akreditasi_id`, `judul_kegiatan`, `jenis_kegiatan`, `tanggal_mulai`, `tanggal_selesai`, `lokasi`, `deskripsi`, `penanggung_jawab_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Workshop Penyusunan LED Kriteria 1-4', 'workshop', '2026-07-15 08:00:00', '2026-07-15 16:00:00', 'Ruang Rapat Utama Gedung Rektorat Lt. 3', 'Workshop penyusunan Laporan Evaluasi Diri untuk Kriteria 1 sampai 4. Peserta: Tim Task Force TI, Admin Prodi, dan Dosen Pembimbing. Narasumber: Reviewer Internal.', 5, 'selesai', '2026-09-01 05:53:38', '2026-09-01 05:53:38'),
(2, 1, 'Bimtek Pengisian LKPS Tabel 1-8', 'bimtek', '2026-07-22 09:00:00', '2026-07-23 15:00:00', 'Lab Komputer Fakultas Teknik Lt. 2', 'Bimbingan teknis pengisian tabel-tabel LKPS sesuai panduan BAN-PT. Menggunakan template LKPS terbaru.', 5, 'selesai', '2026-09-01 05:53:38', '2026-09-01 05:53:38'),
(3, 1, 'Simulasi Asesmen Lapangan', 'simulasi', '2026-09-15 08:00:00', '2026-09-16 17:00:00', 'Program Studi Teknik Informatika', 'Simulasi asesmen lapangan untuk mempersiapkan prodi menghadapi visitasi BAN-PT. Melibatkan asesor internal sebagai penguji.', 9, 'dijadwalkan', '2026-09-01 05:53:38', '2026-09-01 05:53:38'),
(4, 2, 'Pendampingan Penyusunan Dokumen SI', 'pendampingan', '2026-09-05 09:00:00', '2026-09-05 16:00:00', 'Ruang Prodi Sistem Informasi Gedung Teknik Lt. 1', 'Pendampingan penyusunan dokumen akreditasi Program Studi Sistem Informasi oleh Kabid Akreditasi.', 6, 'dijadwalkan', '2026-09-01 05:53:38', '2026-09-01 05:53:38');

-- --------------------------------------------------------

--
-- Struktur dari tabel `laporan_pendampingan`
--

CREATE TABLE `laporan_pendampingan` (
  `id` int(11) NOT NULL,
  `jadwal_pendampingan_id` int(11) NOT NULL,
  `user_pelapor_id` int(11) NOT NULL COMMENT 'User yang membuat laporan',
  `judul_laporan` varchar(200) NOT NULL,
  `isi_laporan` text NOT NULL,
  `hasil_kegiatan` text DEFAULT NULL COMMENT 'Ringkasan hasil/outcome kegiatan',
  `tindak_lanjut` text DEFAULT NULL COMMENT 'Rencana tindak lanjut',
  `file_lampiran` varchar(500) DEFAULT NULL COMMENT 'Path file lampiran',
  `status` enum('draft','submitted','approved','rejected') NOT NULL DEFAULT 'draft',
  `approved_by` int(11) DEFAULT NULL COMMENT 'User yang menyetujui laporan',
  `approved_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Laporan kegiatan pendampingan akreditasi';

--
-- Dumping data untuk tabel `laporan_pendampingan`
--

INSERT INTO `laporan_pendampingan` (`id`, `jadwal_pendampingan_id`, `user_pelapor_id`, `judul_laporan`, `isi_laporan`, `hasil_kegiatan`, `tindak_lanjut`, `file_lampiran`, `status`, `approved_by`, `approved_at`, `created_at`, `updated_at`) VALUES
(1, 1, 13, 'Laporan Workshop Penyusunan LED Kriteria 1-4', 'Workshop dilaksanakan pada 15 Juli 2026 di Ruang Rapat Utama Gedung Rektorat Lt. 3. Kegiatan diikuti oleh 15 peserta yang terdiri dari Tim Task Force TI, Admin Prodi, dan perwakilan dosen dari masing-masing bidang keahlian.\n\nMateri yang dibahas:\n1. Pengenalan format LED sesuai IAPS 4.0\n2. Teknik penulisan narasi evaluasi diri\n3. Penyusunan analisis SWOT program studi\n4. Review dan pembahasan Kriteria 1-4\n\nKegiatan berlangsung efektif dengan diskusi aktif dari seluruh peserta.', 'Berhasil menyusun draft LED untuk Kriteria 1 (Visi Misi) dan Kriteria 2 (Tata Kelola). Kriteria 3 dan 4 masih dalam tahap penyusunan awal. Seluruh peserta memahami format dan standar penulisan LED.', 'Menyelesaikan draft LED Kriteria 3-4 dalam 2 minggu. Melakukan review internal oleh Reviewer. Jadwalkan workshop lanjutan untuk Kriteria 5-9.', '/uploads/laporan/Laporan_Workshop_LED_K1-4_TI.pdf', 'approved', 5, '2026-07-18 10:00:00', '2026-09-01 05:53:38', '2026-09-01 05:53:38'),
(2, 2, 13, 'Laporan Bimtek Pengisian LKPS Tabel 1-8', 'Bimbingan teknis pengisian LKPS dilaksanakan selama 2 hari (22-23 Juli 2026) di Lab Komputer Fakultas Teknik Lt. 2. Diikuti oleh 10 peserta dari Tim Task Force TI dan operator data prodi.\n\nHari 1: Pengenalan template LKPS dan pengisian Tabel 1-4 (Data Umum, Mahasiswa, Dosen, Keuangan)\nHari 2: Pengisian Tabel 5-8 (Kurikulum, Penelitian, PkM, Luaran)\n\nSetiap peserta praktik langsung mengisi template LKPS menggunakan data real dari prodi.', 'Template LKPS terisi 80% dari total tabel yang dibutuhkan. Tabel 1-6 sudah terisi lengkap. Tabel 7 (Penelitian) dan Tabel 8 (PkM) masih memerlukan data tambahan dari LPPM.', 'Koordinasi dengan LPPM untuk melengkapi data penelitian dan PkM. Target penyelesaian LKPS lengkap: 5 Agustus 2026.', '/uploads/laporan/Laporan_Bimtek_LKPS_TI.pdf', 'approved', 5, '2026-07-25 14:30:00', '2026-09-01 05:53:38', '2026-09-01 05:53:38');

-- --------------------------------------------------------

--
-- Struktur dari tabel `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'Penerima notifikasi',
  `judul` varchar(200) NOT NULL,
  `pesan` text NOT NULL,
  `tipe` enum('info','warning','success','danger') NOT NULL DEFAULT 'info',
  `link` varchar(500) DEFAULT NULL COMMENT 'URL terkait notifikasi',
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Sistem notifikasi pengguna';

--
-- Dumping data untuk tabel `notifikasi`
--

INSERT INTO `notifikasi` (`id`, `user_id`, `judul`, `pesan`, `tipe`, `link`, `is_read`, `created_at`) VALUES
(1, 11, 'Pengajuan Akreditasi Diterima', 'Pengajuan reakreditasi Prodi Teknik Informatika (PAK-2026-0001) telah diterima dan sedang dalam tahap review.', 'success', '/pengajuan/detail/1', 1, '2026-07-05 01:00:00'),
(2, 11, 'Review Dokumen LED Selesai', 'Review dokumen LED Teknik Informatika oleh Dr. Nugroho Pratama telah selesai. Rekomendasi: Layak. Silakan cek catatan review.', 'info', '/review/detail/1', 1, '2026-08-20 10:00:00'),
(3, 11, 'Jadwal Simulasi Asesmen', 'Simulasi asesmen lapangan untuk Prodi Teknik Informatika dijadwalkan pada 15-16 September 2026. Silakan persiapkan seluruh dokumen dan sarana prasarana.', 'warning', '/jadwal/detail/3', 0, '2026-08-28 02:00:00'),
(4, 3, 'Pengajuan Baru: Sistem Informasi', 'Pengajuan reakreditasi baru dari Program Studi Sistem Informasi (PAK-2026-0002) memerlukan persetujuan Anda.', 'info', '/pengajuan/detail/2', 0, '2026-08-01 03:30:00'),
(5, 3, 'Laporan Pendampingan Tersedia', 'Laporan workshop penyusunan LED Kriteria 1-4 dan bimtek LKPS telah disetujui oleh Kabid. Silakan review laporan kegiatan.', 'info', '/laporan/list', 1, '2026-07-25 08:00:00'),
(6, 7, 'Tugas Review Baru', 'Anda ditugaskan untuk mereview dokumen pengajuan akreditasi Prodi Teknik Informatika (PAK-2026-0001). Batas waktu review: 25 Agustus 2026.', 'warning', '/review/list', 1, '2026-08-05 01:00:00'),
(7, 13, 'Tugas Upload Dokumen', 'Silakan upload dokumen bukti kinerja penelitian dan PkM untuk Prodi Teknik Informatika. Batas waktu: 10 September 2026.', 'danger', '/borang/upload/1', 0, '2026-08-25 02:00:00'),
(8, 12, 'Akreditasi Disetujui', 'Pengajuan perpanjangan akreditasi Prodi Manajemen (PAK-2026-0003) telah disetujui oleh Kepala KPMA. Selamat!', 'success', '/pengajuan/detail/3', 1, '2026-07-20 08:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengajuan_akreditasi`
--

CREATE TABLE `pengajuan_akreditasi` (
  `id` int(11) NOT NULL,
  `program_studi_id` int(11) NOT NULL,
  `instrumen_akreditasi_id` int(11) NOT NULL,
  `user_pengaju_id` int(11) NOT NULL COMMENT 'User yang membuat pengajuan',
  `nomor_pengajuan` varchar(50) NOT NULL COMMENT 'Nomor unik pengajuan (auto-generate)',
  `jenis_pengajuan` enum('akreditasi_baru','reakreditasi','perpanjangan') NOT NULL DEFAULT 'reakreditasi',
  `tanggal_pengajuan` date NOT NULL,
  `tanggal_target` date DEFAULT NULL COMMENT 'Target tanggal selesai',
  `status` enum('draft','diajukan','review','revisi','disetujui','ditolak','selesai') NOT NULL DEFAULT 'draft',
  `catatan` text DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL COMMENT 'User yang menyetujui pengajuan',
  `approved_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Pengajuan proses akreditasi program studi';

--
-- Dumping data untuk tabel `pengajuan_akreditasi`
--

INSERT INTO `pengajuan_akreditasi` (`id`, `program_studi_id`, `instrumen_akreditasi_id`, `user_pengaju_id`, `nomor_pengajuan`, `jenis_pengajuan`, `tanggal_pengajuan`, `tanggal_target`, `status`, `catatan`, `approved_by`, `approved_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 11, 'PAK-2026-0001', 'reakreditasi', '2026-07-01', '2027-03-01', 'review', 'Pengajuan reakreditasi Program Studi Teknik Informatika menggunakan IAPS 4.0. Akreditasi sebelumnya Baik Sekali, target mempertahankan atau meningkatkan ke Unggul.', NULL, NULL, '2026-09-01 05:53:38', '2026-09-01 05:53:38'),
(2, 2, 1, 11, 'PAK-2026-0002', 'reakreditasi', '2026-08-01', '2026-12-15', 'diajukan', 'Pengajuan reakreditasi Program Studi Sistem Informasi. Akreditasi sebelumnya Baik, masa berlaku hampir habis.', NULL, NULL, '2026-09-01 05:53:38', '2026-09-01 05:53:38'),
(3, 3, 1, 12, 'PAK-2026-0003', 'perpanjangan', '2026-06-15', '2028-01-01', 'disetujui', 'Perpanjangan akreditasi Program Studi Manajemen. Akreditasi saat ini Unggul.', 3, '2026-07-20 14:30:00', '2026-09-01 05:53:38', '2026-09-01 05:53:38'),
(4, 5, 1, 11, 'PAK-2026-0004', 'reakreditasi', '2026-08-15', '2027-06-01', 'draft', 'Rencana pengajuan reakreditasi Program Studi Ilmu Hukum. Masih tahap persiapan dokumen.', NULL, NULL, '2026-09-01 05:53:38', '2026-09-01 05:53:38');

-- --------------------------------------------------------

--
-- Struktur dari tabel `program_studi`
--

CREATE TABLE `program_studi` (
  `id` int(11) NOT NULL,
  `kode_prodi` varchar(20) NOT NULL,
  `nama_prodi` varchar(150) NOT NULL,
  `jenjang` enum('D3','D4','S1','S2','S3','Profesi') NOT NULL DEFAULT 'S1',
  `fakultas` varchar(150) NOT NULL,
  `akreditasi_terakhir` varchar(20) DEFAULT NULL COMMENT 'Nilai: Unggul/Baik Sekali/Baik/A/B/C/Tidak Terakreditasi',
  `tanggal_kadaluarsa` date DEFAULT NULL COMMENT 'Tanggal kadaluarsa akreditasi terakhir',
  `sk_akreditasi` varchar(100) DEFAULT NULL COMMENT 'Nomor SK akreditasi terakhir',
  `kaprodi` varchar(150) DEFAULT NULL COMMENT 'Nama Ketua Program Studi',
  `no_telepon_prodi` varchar(20) DEFAULT NULL,
  `email_prodi` varchar(150) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Data Program Studi dan status akreditasi';

--
-- Dumping data untuk tabel `program_studi`
--

INSERT INTO `program_studi` (`id`, `kode_prodi`, `nama_prodi`, `jenjang`, `fakultas`, `akreditasi_terakhir`, `tanggal_kadaluarsa`, `sk_akreditasi`, `kaprodi`, `no_telepon_prodi`, `email_prodi`, `is_active`, `created_at`, `updated_at`) VALUES
(1, '55201', 'Teknik Informatika', 'S1', 'Fakultas Teknik', 'Baik Sekali', '2027-06-15', '1234/SK/BAN-PT/Ak-PPJ/S/VI/2022', 'Dr. Ahmad Fauzi, M.Kom.', '021-5551001', 'ti@universitas.ac.id', 1, '2026-09-01 05:53:38', '2026-09-01 05:53:38'),
(2, '61201', 'Sistem Informasi', 'S1', 'Fakultas Teknik', 'Baik', '2026-12-20', '2345/SK/BAN-PT/Ak-PPJ/S/XII/2021', 'Dr. Budi Santoso, M.T.', '021-5551002', 'si@universitas.ac.id', 1, '2026-09-01 05:53:38', '2026-09-01 05:53:38'),
(3, '86206', 'Manajemen', 'S1', 'Fakultas Ekonomi dan Bisnis', 'Unggul', '2028-03-10', '3456/SK/BAN-PT/Ak-PPJ/S/III/2023', 'Prof. Dr. Citra Dewi, M.M.', '021-5551003', 'manajemen@universitas.ac.id', 1, '2026-09-01 05:53:38', '2026-09-01 05:53:38'),
(4, '62201', 'Akuntansi', 'S1', 'Fakultas Ekonomi dan Bisnis', 'A', '2027-09-25', '4567/SK/BAN-PT/Ak-PPJ/S/IX/2022', 'Dr. Diana Putri, M.Ak.', '021-5551004', 'akuntansi@universitas.ac.id', 1, '2026-09-01 05:53:38', '2026-09-01 05:53:38'),
(5, '74201', 'Ilmu Hukum', 'S1', 'Fakultas Hukum', 'B', '2026-11-30', '5678/SK/BAN-PT/Ak-PPJ/S/XI/2021', 'Prof. Dr. Eko Prasetyo, S.H.', '021-5551005', 'hukum@universitas.ac.id', 1, '2026-09-01 05:53:38', '2026-09-01 05:53:38'),
(6, '70201', 'Pendidikan Bahasa Inggris', 'S1', 'Fakultas Keguruan dan Ilmu Pendidikan', 'Baik', '2027-01-15', '6789/SK/BAN-PT/Ak-PPJ/S/I/2022', 'Dr. Fatimah Zahra, M.Pd.', '021-5551006', 'pbi@universitas.ac.id', 1, '2026-09-01 05:53:38', '2026-09-01 05:53:38');

-- --------------------------------------------------------

--
-- Struktur dari tabel `review_borang`
--

CREATE TABLE `review_borang` (
  `id` int(11) NOT NULL,
  `pengajuan_akreditasi_id` int(11) NOT NULL,
  `reviewer_id` int(11) NOT NULL COMMENT 'User reviewer/asesor',
  `borang_file_id` int(11) DEFAULT NULL COMMENT 'Dokumen spesifik yang di-review (opsional)',
  `jenis_review` enum('desk_evaluation','visitasi','review_dokumen','review_substansi') NOT NULL DEFAULT 'review_dokumen',
  `skor_penilaian` decimal(5,2) DEFAULT NULL COMMENT 'Skor penilaian (skala sesuai instrumen)',
  `catatan_review` text DEFAULT NULL COMMENT 'Feedback dan catatan review',
  `rekomendasi` enum('layak','perlu_revisi','tidak_layak') DEFAULT NULL,
  `status_review` enum('pending','in_progress','completed') NOT NULL DEFAULT 'pending',
  `tanggal_review` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Review dan penilaian dokumen borang akreditasi';

--
-- Dumping data untuk tabel `review_borang`
--

INSERT INTO `review_borang` (`id`, `pengajuan_akreditasi_id`, `reviewer_id`, `borang_file_id`, `jenis_review`, `skor_penilaian`, `catatan_review`, `rekomendasi`, `status_review`, `tanggal_review`, `created_at`, `updated_at`) VALUES
(1, 1, 7, 1, 'review_dokumen', 85.50, 'Dokumen LED sudah sangat baik. Narasi evaluasi diri pada Kriteria 1 (Visi, Misi, Tujuan, dan Strategi) sudah komprehensif. Perlu sedikit perbaikan pada analisis SWOT di Kriteria 2.', 'layak', 'completed', '2026-08-20', '2026-09-01 05:53:38', '2026-09-01 05:53:38'),
(2, 1, 9, NULL, 'desk_evaluation', 82.75, 'Hasil desk evaluation menunjukkan dokumen telah memenuhi sebagian besar persyaratan. Skor rata-rata dari 9 kriteria adalah 82.75. Kriteria yang perlu diperkuat: Kriteria 5 (Kurikulum) dan Kriteria 9 (Luaran dan Capaian Tridharma).', 'layak', 'completed', '2026-08-25', '2026-09-01 05:53:38', '2026-09-01 05:53:38'),
(3, 3, 8, 7, 'review_dokumen', 91.20, 'Dokumen LED Manajemen sangat baik dan komprehensif. Seluruh standar tercakup dengan lengkap. Bukti pendukung tersedia dan valid.', 'layak', 'completed', '2026-07-10', '2026-09-01 05:53:38', '2026-09-01 05:53:38'),
(4, 3, 10, NULL, 'review_substansi', 89.00, 'Review substansi menunjukkan kualitas pengelolaan program studi yang sangat baik. Rekomendasi: pertahankan capaian dan tingkatkan kerjasama internasional.', 'layak', 'completed', '2026-07-12', '2026-09-01 05:53:38', '2026-09-01 05:53:38');

-- --------------------------------------------------------

--
-- Struktur dari tabel `riwayat_akreditasi`
--

CREATE TABLE `riwayat_akreditasi` (
  `id` int(11) NOT NULL,
  `program_studi_id` int(11) NOT NULL,
  `pengajuan_akreditasi_id` int(11) DEFAULT NULL COMMENT 'FK ke pengajuan (jika melalui sistem)',
  `lembaga_akreditasi` varchar(150) NOT NULL COMMENT 'BAN-PT, LAM, dll.',
  `nomor_sk` varchar(100) NOT NULL COMMENT 'Nomor SK Keputusan',
  `tanggal_sk` date NOT NULL,
  `masa_berlaku_mulai` date NOT NULL,
  `masa_berlaku_selesai` date NOT NULL,
  `nilai_akreditasi` varchar(20) NOT NULL COMMENT 'Unggul/Baik Sekali/Baik/A/B/C',
  `skor_akhir` decimal(5,2) DEFAULT NULL COMMENT 'Skor numerik akhir',
  `dokumen_sk_path` varchar(500) DEFAULT NULL COMMENT 'Path file SK digital',
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Riwayat hasil akreditasi program studi';

--
-- Dumping data untuk tabel `riwayat_akreditasi`
--

INSERT INTO `riwayat_akreditasi` (`id`, `program_studi_id`, `pengajuan_akreditasi_id`, `lembaga_akreditasi`, `nomor_sk`, `tanggal_sk`, `masa_berlaku_mulai`, `masa_berlaku_selesai`, `nilai_akreditasi`, `skor_akhir`, `dokumen_sk_path`, `catatan`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'BAN-PT', '1234/SK/BAN-PT/Ak-PPJ/S/VI/2022', '2022-06-15', '2022-06-15', '2027-06-15', 'Baik Sekali', 361.00, '/uploads/sk/SK_Akreditasi_TI_2022.pdf', 'Akreditasi terakhir Prodi Teknik Informatika. Skor meningkat signifikan dari periode sebelumnya.', '2026-09-01 05:53:38', '2026-09-01 05:53:38'),
(2, 1, NULL, 'BAN-PT', '0891/SK/BAN-PT/Akred/S/VIII/2017', '2017-08-20', '2017-08-20', '2022-08-20', 'A', 370.00, '/uploads/sk/SK_Akreditasi_TI_2017.pdf', 'Akreditasi periode sebelumnya menggunakan instrumen lama (7 standar).', '2026-09-01 05:53:38', '2026-09-01 05:53:38'),
(3, 2, NULL, 'BAN-PT', '2345/SK/BAN-PT/Ak-PPJ/S/XII/2021', '2021-12-20', '2021-12-20', '2026-12-20', 'Baik', 322.00, '/uploads/sk/SK_Akreditasi_SI_2021.pdf', 'Akreditasi terakhir Prodi Sistem Informasi. Target reakreditasi naik ke Baik Sekali.', '2026-09-01 05:53:38', '2026-09-01 05:53:38'),
(4, 3, 3, 'BAN-PT', '3456/SK/BAN-PT/Ak-PPJ/S/III/2023', '2023-03-10', '2023-03-10', '2028-03-10', 'Unggul', 394.00, '/uploads/sk/SK_Akreditasi_MNJ_2023.pdf', 'Prodi Manajemen berhasil meraih peringkat Unggul. Pencapaian tertinggi di universitas.', '2026-09-01 05:53:38', '2026-09-01 05:53:38'),
(5, 4, NULL, 'BAN-PT', '4567/SK/BAN-PT/Ak-PPJ/S/IX/2022', '2022-09-25', '2022-09-25', '2027-09-25', 'A', 365.00, '/uploads/sk/SK_Akreditasi_AKT_2022.pdf', 'Akreditasi Prodi Akuntansi menggunakan instrumen lama. Akan beralih ke IAPS 4.0 pada reakreditasi berikutnya.', '2026-09-01 05:53:38', '2026-09-01 05:53:38'),
(6, 5, NULL, 'BAN-PT', '5678/SK/BAN-PT/Ak-PPJ/S/XI/2021', '2021-11-30', '2021-11-30', '2026-11-30', 'B', 310.00, '/uploads/sk/SK_Akreditasi_HKM_2021.pdf', 'Akreditasi Prodi Ilmu Hukum. Target naik ke A/Baik Sekali pada reakreditasi mendatang.', '2026-09-01 05:53:38', '2026-09-01 05:53:38');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nip` varchar(30) NOT NULL,
  `nama_lengkap` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL COMMENT 'Hashed with bcrypt',
  `role` enum('admin_universitas','kepala_kpma','kabid_kpma','reviewer_internal','asesor_internal','admin_prodi','team_task_force') NOT NULL,
  `program_studi_id` int(11) DEFAULT NULL COMMENT 'FK ??? program_studi (untuk admin_prodi & team_task_force)',
  `jabatan` varchar(100) DEFAULT NULL COMMENT 'Jabatan fungsional',
  `no_telepon` varchar(20) DEFAULT NULL,
  `foto_profil` varchar(255) DEFAULT NULL COMMENT 'Path file foto profil',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Pengguna sistem dengan 7 role RBAC';

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nip`, `nama_lengkap`, `email`, `password`, `role`, `program_studi_id`, `jabatan`, `no_telepon`, `foto_profil`, `is_active`, `last_login`, `created_at`, `updated_at`) VALUES
(1, '198501012010011001', 'Dr. Ir. Hendra Wijaya, M.T.', 'admin1@universitas.ac.id', '$2y$10$wE45JRgFs9hNU.13WhEY8OqK5Ncc4COYf4BqVpaY75pTUeylZDEma', 'admin_universitas', NULL, 'Kepala Biro TIK', '081200001001', NULL, 1, '2026-09-01 15:41:28', '2026-09-01 05:53:38', '2026-09-01 08:41:28'),
(2, '198702152012012002', 'Ir. Indah Permatasari, M.Kom.', 'admin2@universitas.ac.id', '$2y$10$wE45JRgFs9hNU.13WhEY8OqK5Ncc4COYf4BqVpaY75pTUeylZDEma', 'admin_universitas', NULL, 'Staf Biro TIK', '081200001002', NULL, 1, '2026-08-29 14:30:00', '2026-09-01 05:53:38', '2026-09-01 05:53:38'),
(3, '197803202005011003', 'Prof. Dr. Joko Susanto, M.Pd.', 'kepala.kpma@universitas.ac.id', '$2y$10$wE45JRgFs9hNU.13WhEY8OqK5Ncc4COYf4BqVpaY75pTUeylZDEma', 'kepala_kpma', NULL, 'Kepala KPMA', '081200002001', NULL, 1, '2026-08-28 09:00:00', '2026-09-01 05:53:38', '2026-09-01 05:53:38'),
(4, '198005102008012004', 'Dr. Kartika Sari, M.Si.', 'wakil.kpma@universitas.ac.id', '$2y$10$wE45JRgFs9hNU.13WhEY8OqK5Ncc4COYf4BqVpaY75pTUeylZDEma', 'kepala_kpma', NULL, 'Wakil Kepala KPMA', '081200002002', NULL, 1, '2026-08-27 10:45:00', '2026-09-01 05:53:38', '2026-09-01 05:53:38'),
(5, '198206152010011005', 'Dr. Lukman Hakim, M.T.', 'kabid.akreditasi@universitas.ac.id', '$2y$10$wE45JRgFs9hNU.13WhEY8OqK5Ncc4COYf4BqVpaY75pTUeylZDEma', 'kabid_kpma', NULL, 'Kabid Akreditasi', '081200003001', NULL, 1, '2026-08-30 07:30:00', '2026-09-01 05:53:38', '2026-09-01 05:53:38'),
(6, '198410202012012006', 'Dr. Maya Anggraini, M.Pd.', 'kabid.mutu@universitas.ac.id', '$2y$10$wE45JRgFs9hNU.13WhEY8OqK5Ncc4COYf4BqVpaY75pTUeylZDEma', 'kabid_kpma', NULL, 'Kabid Penjaminan Mutu', '081200003002', NULL, 1, '2026-08-29 16:20:00', '2026-09-01 05:53:38', '2026-09-01 05:53:38'),
(7, '197905252007011007', 'Dr. Nugroho Pratama, M.Kom.', 'reviewer1@universitas.ac.id', '$2y$10$wE45JRgFs9hNU.13WhEY8OqK5Ncc4COYf4BqVpaY75pTUeylZDEma', 'reviewer_internal', NULL, 'Reviewer Dokumen Senior', '081200004001', NULL, 1, '2026-08-28 11:00:00', '2026-09-01 05:53:38', '2026-09-01 05:53:38'),
(8, '198108302009012008', 'Dr. Olivia Rahayu, M.Si.', 'reviewer2@universitas.ac.id', '$2y$10$wE45JRgFs9hNU.13WhEY8OqK5Ncc4COYf4BqVpaY75pTUeylZDEma', 'reviewer_internal', NULL, 'Reviewer Dokumen', '081200004002', NULL, 1, '2026-08-27 13:15:00', '2026-09-01 05:53:38', '2026-09-01 05:53:38'),
(9, '197604122004011009', 'Prof. Dr. Panji Kusuma, M.Eng.', 'asesor1@universitas.ac.id', '$2y$10$wE45JRgFs9hNU.13WhEY8OqK5Ncc4COYf4BqVpaY75pTUeylZDEma', 'asesor_internal', NULL, 'Asesor Internal Senior', '081200005001', NULL, 1, '2026-08-26 08:45:00', '2026-09-01 05:53:38', '2026-09-01 05:53:38'),
(10, '198312182010012010', 'Dr. Queen Maharani, M.T.', 'asesor2@universitas.ac.id', '$2y$10$wE45JRgFs9hNU.13WhEY8OqK5Ncc4COYf4BqVpaY75pTUeylZDEma', 'asesor_internal', NULL, 'Asesor Internal', '081200005002', NULL, 1, '2026-08-25 15:30:00', '2026-09-01 05:53:38', '2026-09-01 05:53:38'),
(11, '199001052015011011', 'Rizky Maulana, S.Kom., M.Cs.', 'admin.ti@universitas.ac.id', '$2y$10$wE45JRgFs9hNU.13WhEY8OqK5Ncc4COYf4BqVpaY75pTUeylZDEma', 'admin_prodi', 1, 'Admin Prodi Teknik Informatika', '081200006001', NULL, 1, '2026-08-30 09:00:00', '2026-09-01 05:53:38', '2026-09-01 05:53:38'),
(12, '199203182016012012', 'Siti Nurhaliza, S.E., M.M.', 'admin.mnj@universitas.ac.id', '$2y$10$wE45JRgFs9hNU.13WhEY8OqK5Ncc4COYf4BqVpaY75pTUeylZDEma', 'admin_prodi', 3, 'Admin Prodi Manajemen', '081200006002', NULL, 1, '2026-08-29 08:30:00', '2026-09-01 05:53:38', '2026-09-01 05:53:38'),
(13, '199405222018011013', 'Teguh Prasetya, S.T., M.Kom.', 'taskforce1@universitas.ac.id', '$2y$10$wE45JRgFs9hNU.13WhEY8OqK5Ncc4COYf4BqVpaY75pTUeylZDEma', 'team_task_force', 1, 'Koordinator Task Force TI', '081200007001', NULL, 1, '2026-08-28 10:00:00', '2026-09-01 05:53:38', '2026-09-01 05:53:38'),
(14, '199607102019012014', 'Ulfa Dwi Cahyani, S.Pd., M.Pd.', 'taskforce2@universitas.ac.id', '$2y$10$wE45JRgFs9hNU.13WhEY8OqK5Ncc4COYf4BqVpaY75pTUeylZDEma', 'team_task_force', 6, 'Anggota Task Force PBI', '081200007002', NULL, 1, '2026-08-27 11:30:00', '2026-09-01 05:53:38', '2026-09-01 05:53:38');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_modul` (`modul`),
  ADD KEY `idx_created` (`created_at`),
  ADD KEY `idx_user_modul` (`user_id`,`modul`);

--
-- Indeks untuk tabel `borang_files`
--
ALTER TABLE `borang_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pengajuan` (`pengajuan_akreditasi_id`),
  ADD KEY `idx_kategori` (`kategori_dokumen`),
  ADD KEY `idx_pengajuan_kategori` (`pengajuan_akreditasi_id`,`kategori_dokumen`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_uploaded_by` (`uploaded_by`),
  ADD KEY `fk_borang_verified_by` (`verified_by`);

--
-- Indeks untuk tabel `instrumen_akreditasi`
--
ALTER TABLE `instrumen_akreditasi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_kode_instrumen` (`kode_instrumen`),
  ADD KEY `idx_lembaga` (`lembaga_akreditasi`);

--
-- Indeks untuk tabel `jadwal_pendampingan`
--
ALTER TABLE `jadwal_pendampingan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pengajuan` (`pengajuan_akreditasi_id`),
  ADD KEY `idx_tanggal_mulai` (`tanggal_mulai`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_penanggung_jawab` (`penanggung_jawab_id`),
  ADD KEY `idx_jenis_kegiatan` (`jenis_kegiatan`),
  ADD KEY `idx_tanggal_status` (`tanggal_mulai`,`status`);

--
-- Indeks untuk tabel `laporan_pendampingan`
--
ALTER TABLE `laporan_pendampingan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_jadwal` (`jadwal_pendampingan_id`),
  ADD KEY `idx_pelapor` (`user_pelapor_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `fk_laporan_approved_by` (`approved_by`);

--
-- Indeks untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_user_read` (`user_id`,`is_read`),
  ADD KEY `idx_created` (`created_at`);

--
-- Indeks untuk tabel `pengajuan_akreditasi`
--
ALTER TABLE `pengajuan_akreditasi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_nomor_pengajuan` (`nomor_pengajuan`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_prodi_status` (`program_studi_id`,`status`),
  ADD KEY `idx_tanggal_pengajuan` (`tanggal_pengajuan`),
  ADD KEY `idx_jenis_pengajuan` (`jenis_pengajuan`),
  ADD KEY `fk_pengajuan_instrumen` (`instrumen_akreditasi_id`),
  ADD KEY `fk_pengajuan_user_pengaju` (`user_pengaju_id`),
  ADD KEY `fk_pengajuan_approved_by` (`approved_by`);

--
-- Indeks untuk tabel `program_studi`
--
ALTER TABLE `program_studi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_kode_prodi` (`kode_prodi`),
  ADD KEY `idx_fakultas` (`fakultas`),
  ADD KEY `idx_jenjang` (`jenjang`),
  ADD KEY `idx_akreditasi_kadaluarsa` (`tanggal_kadaluarsa`);

--
-- Indeks untuk tabel `review_borang`
--
ALTER TABLE `review_borang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pengajuan` (`pengajuan_akreditasi_id`),
  ADD KEY `idx_reviewer` (`reviewer_id`),
  ADD KEY `idx_pengajuan_reviewer` (`pengajuan_akreditasi_id`,`reviewer_id`),
  ADD KEY `idx_status_review` (`status_review`),
  ADD KEY `idx_jenis_review` (`jenis_review`),
  ADD KEY `fk_review_borang_file` (`borang_file_id`);

--
-- Indeks untuk tabel `riwayat_akreditasi`
--
ALTER TABLE `riwayat_akreditasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_program_studi` (`program_studi_id`),
  ADD KEY `idx_masa_berlaku` (`masa_berlaku_selesai`),
  ADD KEY `idx_nilai` (`nilai_akreditasi`),
  ADD KEY `idx_prodi_berlaku` (`program_studi_id`,`masa_berlaku_selesai`),
  ADD KEY `fk_riwayat_pengajuan` (`pengajuan_akreditasi_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_nip` (`nip`),
  ADD UNIQUE KEY `uq_email` (`email`),
  ADD KEY `idx_role` (`role`),
  ADD KEY `idx_program_studi` (`program_studi_id`),
  ADD KEY `idx_is_active` (`is_active`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `borang_files`
--
ALTER TABLE `borang_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `instrumen_akreditasi`
--
ALTER TABLE `instrumen_akreditasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `jadwal_pendampingan`
--
ALTER TABLE `jadwal_pendampingan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `laporan_pendampingan`
--
ALTER TABLE `laporan_pendampingan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `pengajuan_akreditasi`
--
ALTER TABLE `pengajuan_akreditasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `program_studi`
--
ALTER TABLE `program_studi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `review_borang`
--
ALTER TABLE `review_borang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `riwayat_akreditasi`
--
ALTER TABLE `riwayat_akreditasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `activity_log`
--
ALTER TABLE `activity_log`
  ADD CONSTRAINT `fk_activity_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `borang_files`
--
ALTER TABLE `borang_files`
  ADD CONSTRAINT `fk_borang_pengajuan` FOREIGN KEY (`pengajuan_akreditasi_id`) REFERENCES `pengajuan_akreditasi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_borang_uploaded_by` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_borang_verified_by` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `jadwal_pendampingan`
--
ALTER TABLE `jadwal_pendampingan`
  ADD CONSTRAINT `fk_jadwal_penanggung_jawab` FOREIGN KEY (`penanggung_jawab_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_jadwal_pengajuan` FOREIGN KEY (`pengajuan_akreditasi_id`) REFERENCES `pengajuan_akreditasi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `laporan_pendampingan`
--
ALTER TABLE `laporan_pendampingan`
  ADD CONSTRAINT `fk_laporan_approved_by` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_laporan_jadwal` FOREIGN KEY (`jadwal_pendampingan_id`) REFERENCES `jadwal_pendampingan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_laporan_pelapor` FOREIGN KEY (`user_pelapor_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD CONSTRAINT `fk_notifikasi_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pengajuan_akreditasi`
--
ALTER TABLE `pengajuan_akreditasi`
  ADD CONSTRAINT `fk_pengajuan_approved_by` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pengajuan_instrumen` FOREIGN KEY (`instrumen_akreditasi_id`) REFERENCES `instrumen_akreditasi` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pengajuan_program_studi` FOREIGN KEY (`program_studi_id`) REFERENCES `program_studi` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pengajuan_user_pengaju` FOREIGN KEY (`user_pengaju_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `review_borang`
--
ALTER TABLE `review_borang`
  ADD CONSTRAINT `fk_review_borang_file` FOREIGN KEY (`borang_file_id`) REFERENCES `borang_files` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_review_pengajuan` FOREIGN KEY (`pengajuan_akreditasi_id`) REFERENCES `pengajuan_akreditasi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_review_reviewer` FOREIGN KEY (`reviewer_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `riwayat_akreditasi`
--
ALTER TABLE `riwayat_akreditasi`
  ADD CONSTRAINT `fk_riwayat_pengajuan` FOREIGN KEY (`pengajuan_akreditasi_id`) REFERENCES `pengajuan_akreditasi` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_riwayat_program_studi` FOREIGN KEY (`program_studi_id`) REFERENCES `program_studi` (`id`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_program_studi` FOREIGN KEY (`program_studi_id`) REFERENCES `program_studi` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
