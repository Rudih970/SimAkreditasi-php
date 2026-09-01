# SIM Akreditasi — Sistem Manajemen Akreditasi Perguruan Tinggi

Aplikasi web untuk mengelola proses akreditasi perguruan tinggi secara terintegrasi, meliputi pengajuan akreditasi, manajemen dokumen borang, review & penilaian, pendampingan, dan pelacakan riwayat akreditasi.

## 🛠 Tech Stack

| Komponen | Teknologi |
|----------|-----------|
| Backend | PHP 8.x (Native MVC/Modular) |
| Database | MySQL 8.x |
| Frontend | Tailwind CSS + Lucide Icons |
| Server | Apache (XAMPP) |

## 👥 Role-Based Access Control (RBAC)

| No | Role | Deskripsi |
|----|------|-----------|
| 1 | Admin Universitas | Super admin, kelola seluruh sistem |
| 2 | Kepala KPMA | Kepala Kantor Penjaminan Mutu Akademik |
| 3 | Kabid KPMA | Kepala Bidang di KPMA |
| 4 | Reviewer Internal | Reviewer dokumen borang |
| 5 | Asesor Internal | Asesor penilaian akreditasi |
| 6 | Admin Prodi | Administrator tingkat Program Studi |
| 7 | Team Task Force | Tim pelaksana tugas akreditasi |

## 📦 Setup Database

### Prasyarat
- XAMPP dengan MySQL/MariaDB
- PHP 8.0 atau lebih tinggi

### Langkah Instalasi

1. **Clone atau salin proyek** ke `C:\xampp\htdocs\SimAkreditasi`

2. **Import DDL** (buat database dan tabel):
   ```bash
   mysql -u root < database/akreditasiflow_db.sql
   ```

3. **Import Data Seeder** (data awal untuk testing):
   ```bash
   mysql -u root < database/seeder.sql
   ```

4. **Verifikasi**:
   ```bash
   mysql -u root -e "USE akreditasiflow_db; SHOW TABLES;"
   ```

### Akun Login Testing

| Email | Password | Role |
|-------|----------|------|
| admin1@universitas.ac.id | password123 | Admin Universitas |
| kepala.kpma@universitas.ac.id | password123 | Kepala KPMA |
| kabid.akreditasi@universitas.ac.id | password123 | Kabid KPMA |
| reviewer1@universitas.ac.id | password123 | Reviewer Internal |
| asesor1@universitas.ac.id | password123 | Asesor Internal |
| admin.ti@universitas.ac.id | password123 | Admin Prodi |
| taskforce1@universitas.ac.id | password123 | Team Task Force |

## 📁 Struktur Proyek

```
SimAkreditasi/
├── database/
│   ├── akreditasiflow_db.sql    # DDL (Database Definition Language)
│   └── seeder.sql               # Data awal untuk testing
├── README.md
└── ... (tahap selanjutnya)
```

## 📋 Roadmap Pengembangan

- [x] **Tahap 1**: Perancangan Database MySQL & Skema Relasi
- [ ] **Tahap 2**: Arsitektur MVC & Konfigurasi Dasar
- [ ] **Tahap 3**: Sistem Autentikasi & RBAC
- [ ] **Tahap 4**: Modul Manajemen Pengguna
- [ ] **Tahap 5**: Modul Program Studi & Instrumen
- [ ] **Tahap 6**: Modul Pengajuan Akreditasi
- [ ] **Tahap 7**: Modul Manajemen Borang
- [ ] **Tahap 8**: Modul Review & Penilaian
- [ ] **Tahap 9**: Modul Pendampingan & Laporan
- [ ] **Tahap 10**: Dashboard, Notifikasi & Finalisasi

## 📝 Lisensi

Hak Cipta © 2026 — SIM Akreditasi