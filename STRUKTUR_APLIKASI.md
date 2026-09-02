# Dokumentasi Struktur SimAkreditasi

## 🎯 Gambaran Umum

Aplikasi SimAkreditasi adalah sistem manajemen akreditasi perguruan tinggi yang berbasis role. Struktur mengikuti pola MVC dengan routing berbasis URL.

### Arsitektur Aplikasi
```
URL Format: /controller-name/method/param1/param2
Example: /program-studi/edit/5
         /pengajuan/show/123
         /dashboard/index
```

### Konversi URL Routing
- Dash menjadi camelCase: `program-studi` → `ProdiController`
- Dash menjadi camelCase: `users` → `UserController`
- Dash menjadi camelCase: `activity-log` → `ActivityLogController`

## 🔐 Role-Based Access Control (RBAC)

### 7 Role Utama
```php
define('ROLES', [
    'admin_universitas' => 'Admin Universitas',  // Superadmin
    'kepala_kpma'       => 'Kepala KPMA',        // Head of Quality Office
    'kabid_kpma'        => 'Kabid KPMA',         // Division Head
    'reviewer_internal' => 'Reviewer Internal',  // Quality Reviewer
    'asesor_internal'   => 'Asesor Internal',    // Internal Assessor
    'admin_prodi'       => 'Admin Prodi',        // Program Director
    'team_task_force'   => 'Team Task Force',    // Program Team
]);
```

### Kontrol Akses Per Role

**Admin Roles (Admin Universitas, Kepala KPMA, Kabid KPMA)**
```
✅ Master Data:  Program Studi, Manajemen Akun, Instrumen Akreditasi
✅ Akreditasi:   Pengajuan, Jadwal, Review, Upload, Riwayat
✅ Laporan:      Laporan Pendampingan, Statistik
✅ Sistem:       Activity Log, Notifikasi, Settings
```

**Reviewer Roles (Reviewer Internal, Asesor Internal)**
```
✅ Pekerjaan Mutu: Review Borang IAPS, Laporan Pendampingan
```

**Prodi Roles (Admin Prodi, Team Task Force)**
```
✅ Tahapan Pengajuan: Tahap Persiapan, Penjadwalan
✅ Arsip Penilaian: Riwayat Akreditasi
```

## 🏗️ Struktur Direktori

```
controllers/
├── AuthController.php           ✅ Login/Logout
├── DashboardController.php      ✅ Dashboard (role-based)
├── ProdiController.php          ✅ Program Studi CRUD
├── UserController.php           ⬜ User Management
├── InstrumenController.php      ⬜ Master Instrumen
├── PengajuanController.php      ⬜ Accreditation Requests
├── JadwalController.php         ⬜ Schedule Management
├── BorangController.php         ⬜ File Upload
├── ReviewController.php         ⬜ Borang Review
├── RiwayatController.php        ⬜ Timeline
├── LaporanController.php        ⬜ Reports
├── NotifikasiController.php     ⬜ Notifications
├── ActivityLogController.php    ⬜ Activity Logging
├── ProfileController.php        ⬜ User Profile
└── SettingsController.php       ⬜ Settings

models/
├── ProgramStudi.php             ✅ Program queries
├── User.php                     ⬜ User queries
├── Pengajuan.php                ⬜ Request queries
├── Jadwal.php                   ⬜ Schedule queries
├── Borang.php                   ⬜ File queries
├── Review.php                   ⬜ Review queries
└── ...

views/
├── auth/
│   └── login.php                ✅ Login page
├── dashboard/
│   ├── index.php                ✅ Main dashboard
│   ├── detail.php               ⬜ Stage 1: Preparation
│   └── asesor.php               ⬜ Stage 2: Scheduling
├── prodi/
│   ├── index.php                ✅ Program list
│   └── form.php                 ✅ Create/Edit form
├── users/
│   ├── index.php                ⬜ User list
│   └── form.php                 ⬜ User form
├── pengajuan/
│   ├── index.php                ⬜ Request list
│   └── form.php                 ⬜ Request form
├── jadwal/
│   ├── index.php                ⬜ Schedule list
│   └── form.php                 ⬜ Schedule form
├── borang/
│   ├── index.php                ⬜ File list
│   └── upload.php               ⬜ Upload form
├── review/
│   └── index.php                ⬜ Review interface
├── riwayat/
│   └── index.php                ⬜ Timeline view
├── laporan/
│   └── index.php                ⬜ Report view
└── templates/
    ├── header.php               ✅ HTML head
    ├── navbar.php               ✅ Top navigation
    └── sidebar.php              ✅ Side navigation

core/
├── App.php                      ✅ Router & loader
├── Controller.php               ✅ Base controller
├── Model.php                    ✅ Base model
├── Helpers.php                  ✅ Utilities
└── Middleware.php               ⬜ Auth checks

config/
├── app.php                      ✅ App config
└── database.php                 ✅ DB config
```

## 🔑 Helper Functions

### Auth Helpers
```php
check_access(['admin_universitas', 'admin_prodi']);  // Validate role
is_role('admin_prodi');                             // Check current role
auth('nama_lengkap');                               // Get user data
get_initials('Budi Santoso');                       // Returns 'BS'
```

### URL Helpers
```php
url('program-studi');                   // Returns /program-studi
url('program-studi/5/edit');            // Returns /program-studi/5/edit
asset('css/style.css');                 // Returns /assets/css/style.css
upload_url('borang/file.pdf');          // Returns /uploads/borang/file.pdf
```

### Format Helpers
```php
format_tanggal('2024-01-15');           // '15 Januari 2024'
format_tanggal($date, 'short');         // '15 Jan 2024'
format_tanggal($date, 'datetime');      // '15 Januari 2024, 14:30 WIB'
format_file_size(2048);                 // '2 KB'
format_number(1000000);                 // '1.000.000'
status_badge('diajukan');               // HTML badge
role_badge('admin_prodi');              // HTML role badge
```

### Security Helpers
```php
csrf_token();                           // Get CSRF token
csrf_field();                           // HTML hidden input
e($userInput);                          // Escape HTML
set_flash('success', 'Berhasil');       // Set flash message
validate_csrf($_POST['_token']);        // Validate token
```

## 📝 Template Controller

Setiap controller harus extend `Controller` dan implement middleware:

```php
<?php

class NameController extends Controller
{
    public function index(): void
    {
        // 1. CHECK ACCESS
        check_access(['admin_universitas', 'kepala_kpma']);
        
        // 2. GET DATA
        $data = $this->db->fetchAll("SELECT * FROM table");
        
        // 3. RENDER VIEW
        $this->view('view.name', [
            'pageTitle' => 'Page Title',
            'activePage' => 'menu-key',
            'data' => $data,
        ]);
    }

    public function create(): void
    {
        check_access(['admin_universitas']);
        $this->view('view.form', ['action' => 'create']);
    }

    public function store(): void
    {
        check_access(['admin_universitas']);
        
        if (!validate_csrf($_POST['_token'] ?? '')) {
            set_flash('error', 'Token invalid');
            $this->redirect('page/create');
        }
        
        // Process form...
        set_flash('success', 'Data saved');
        $this->redirect('page');
    }

    public function edit($id): void
    {
        check_access(['admin_universitas']);
        $data = $this->db->fetch("SELECT * FROM table WHERE id = ?", [$id]);
        $this->view('view.form', ['data' => $data, 'action' => 'edit']);
    }

    public function update($id): void
    {
        check_access(['admin_universitas']);
        // Process update...
        $this->redirect('page');
    }

    public function delete($id): void
    {
        check_access(['admin_universitas']);
        // Process delete...
        $this->redirect('page');
    }
}
```

## 📋 Template View (List)

```php
<?php $currentUser = $_SESSION['user'] ?? null; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> — <?= e(APP_NAME) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>
<body class="bg-slate-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <?php include ROOT_PATH . '/views/templates/sidebar.php'; ?>
        
        <!-- Main -->
        <div class="flex-1 flex flex-col">
            <!-- Navbar -->
            <?php include ROOT_PATH . '/views/templates/navbar.php'; ?>
            
            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <div class="mb-6">
                    <h1 class="text-2xl font-bold"><?= e($pageTitle) ?></h1>
                </div>
                
                <!-- Flash Messages -->
                <?= flash_message() ?>
                
                <!-- Your content here -->
            </main>
        </div>
    </div>
    <script>lucide.createIcons();</script>
</body>
</html>
```

## 🎨 Styling Guidelines

### Tailwind Utility Classes
- **Primary Color**: `bg-indigo-600 text-indigo-700`
- **Success**: `bg-emerald-50 text-emerald-700`
- **Warning**: `bg-amber-50 text-amber-700`
- **Error**: `bg-red-50 text-red-700`
- **Info**: `bg-blue-50 text-blue-700`

### Button Styles
```html
<!-- Primary CTA -->
<button class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-4 rounded-lg transition-all shadow-md hover:shadow-lg">
    Button Text
</button>

<!-- Secondary -->
<button class="bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold py-2.5 px-4 rounded-lg transition-all">
    Button Text
</button>

<!-- Danger -->
<button class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 px-4 rounded-lg transition-all">
    Delete
</button>
```

### Jenjang Colors
- **S3** (Purple): `bg-purple-50 text-purple-700 border-purple-200`
- **S2** (Blue): `bg-blue-50 text-blue-700 border-blue-200`
- **S1** (Emerald): `bg-emerald-50 text-emerald-700 border-emerald-200`
- **D4** (Amber): `bg-amber-50 text-amber-700 border-amber-200`
- **Profesi** (Rose): `bg-rose-50 text-rose-700 border-rose-200`
- **D3** (Orange): `bg-orange-50 text-orange-700 border-orange-200`

## 🚀 Cara Membuat Modul Baru

### 1. Create Controller
Copy template dari ProdiController, sesuaikan nama class dan queries.

### 2. Create Model
Create file di `models/` untuk queries khusus modul.

### 3. Create Views
- `views/module-name/index.php` - List view
- `views/module-name/form.php` - Create/Edit form
- `views/module-name/show.php` - Detail view (optional)

### 4. Add Routing
Routing sudah automatic via App.php controllerMap. Pastikan controller nama sesuai format: `NameController`.

### 5. Add Sidebar Menu
Edit `views/templates/sidebar.php` untuk add menu item:

```php
<a href="<?= url('module-name') ?>" class="flex items-center gap-3 px-4 py-2 text-indigo-200 hover:bg-indigo-800/50 rounded-lg transition-colors">
    <i data-lucide="icon-name" class="w-5 h-5"></i>
    <span class="font-medium">Module Name</span>
</a>
```

## 📊 Database Schema

### Core Tables
- `users` - User accounts & roles
- `program_studi` - Program data
- `pengajuan_akreditasi` - Accreditation requests
- `borang_files` - File uploads
- `review_borang` - Reviews
- `jadwal_pendampingan` - Schedules
- `instrumen_akreditasi` - Instruments
- `activity_log` - Audit trail
- `notifikasi` - Notifications
- `riwayat_akreditasi` - Timeline

See `database/akreditasiflow_db.sql` for full schema.

## 🔗 Next Steps

1. ✅ ProdiController + views (DONE)
2. ⬜ UserController + views
3. ⬜ PengajuanController + views
4. ⬜ Create remaining controllers using same pattern
5. ⬜ Add proper role-based data filtering
6. ⬜ Implement notification system
7. ⬜ Add reports & exports

---
Generated: 2024
