# Quick Start Guide — Melanjutkan Implementasi

## ✅ Yang Sudah Selesai

### Phase 1 Complete: Foundation & Master Data
```
✅ ProdiController   → /program-studi
   - List, Create, Edit, Delete, Show
   - Search & filter by jenjang
   
✅ UserController   → /users  
   - List, Create, Edit, Deactivate, Activate, Reset Password
   - Role management & assignment
   
✅ Navbar Template  → Menampilkan user info & role
✅ Documentation    → STRUKTUR_APLIKASI.md
```

### View URLs yang Sudah Aktif
- `http://localhost/program-studi` — List program studi
- `http://localhost/program-studi/create` — Tambah program
- `http://localhost/users` — List pengguna
- `http://localhost/users/create` — Tambah pengguna

---

## 📋 Checklist: Langkah Berikutnya

### Phase 2: Instrumen Master Data
```
1. [ ] Create InstrumenController
   - index() — List instrumen akreditasi
   - show() — Detail instrumen
   - edit/update/delete (untuk admin)
   
2. [ ] Create views:
   - views/instrumen/index.php
   - views/instrumen/show.php
   
3. [ ] URL: http://localhost/instrumen
```

### Phase 3: Akreditasi Workflow
```
1. [ ] PengajuanController
   - index() — List pengajuan
   - create() — Buat pengajuan baru
   - show() — Detail pengajuan
   
2. [ ] JadwalController
   - index() — List jadwal pendampingan
   - create() — Buat jadwal baru
   
3. [ ] BorangController
   - index() — List file borang
   - upload() — Upload file
   
4. [ ] ReviewController
   - index() — List review
   - update() — Update review borang
   
5. [ ] RiwayatController
   - index() — Timeline akreditasi
```

---

## 🎯 Template Cepat: Membuat Controller Baru

Gunakan pola dari ProdiController atau UserController:

```php
<?php
require_once ROOT_PATH . '/models/ModelName.php';

class NameController extends Controller
{
    public function index(): void
    {
        // 1. Check access
        check_access(['admin_universitas']);
        
        // 2. Get data
        $data = $this->db->fetchAll("SELECT * FROM table");
        
        // 3. Render
        $this->view('name.index', [
            'pageTitle' => 'Page Title',
            'activePage' => 'menu-key',
            'data' => $data,
        ]);
    }
    
    public function create(): void
    {
        check_access(['admin_universitas']);
        $this->view('name.form', ['action' => 'create']);
    }
    
    public function store(): void
    {
        check_access(['admin_universitas']);
        if (!validate_csrf($_POST['_token'] ?? '')) {
            set_flash('error', 'Invalid token');
            $this->redirect('name/create');
        }
        // Process...
        set_flash('success', 'Created');
        $this->redirect('name');
    }
}
```

---

## 📁 File Structure Reference

### Untuk membuat modul baru `nama-modul`:

```
1. Controller:      controllers/NamaModulController.php
2. Model:           models/NamaModul.php (if needed)
3. Views:
   - views/nama-modul/index.php
   - views/nama-modul/form.php (create/edit)
   - views/nama-modul/show.php (detail, optional)

4. Sidebar:         Update views/templates/sidebar.php
5. Routing:         Already mapped in core/App.php
```

### Konversi URL → Controller
```
/nama-modul          → NamaModulController
/nama-modul/method   → NamaModulController->method()
/nama-modul/5/edit   → NamaModulController->edit(5)
```

---

## 🎨 Styling Cheat Sheet

### Button Styles
```html
<!-- Primary (Indigo) -->
<button class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-4 rounded-lg">
    Button
</button>

<!-- Secondary (Gray) -->
<button class="bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold py-2.5 px-4 rounded-lg">
    Button
</button>

<!-- Danger (Red) -->
<button class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 px-4 rounded-lg">
    Delete
</button>
```

### Jenjang Colors
```
S3      → purple-50 / purple-700
S2      → blue-50 / blue-700
S1      → emerald-50 / emerald-700
D4      → amber-50 / amber-700
Profesi → rose-50 / rose-700
D3      → orange-50 / orange-700
```

### Status Badges
```php
<?= status_badge('diajukan') ?>   // Blue
<?= status_badge('disetujui') ?>  // Green
<?= status_badge('ditolak') ?>    // Red
<?= role_badge('admin_prodi') ?>  // Purple
```

---

## 🚀 How to Test Modul Baru

1. Create controller file
2. Create view file(s)
3. Add sidebar menu link
4. Test di browser:
   ```
   http://localhost/modul-name
   http://localhost/modul-name/create
   ```
5. Verify:
   - ✅ Form loads
   - ✅ Validasi works
   - ✅ CSRF token present
   - ✅ Sidebar active state correct

---

## 💡 Pro Tips

1. **CSRF Token di Form**
   ```html
   <input type="hidden" name="_token" value="<?= e($csrfToken ?? '') ?>">
   ```

2. **Flash Messages**
   ```php
   // Di controller:
   set_flash('success', 'Data saved!');
   set_flash('error', 'Something wrong');
   
   // Di view:
   <?= flash_message() ?>
   ```

3. **Helper Functions**
   ```php
   e($variable)                    // Escape HTML
   url('page')                     // Generate URL
   asset('css/style.css')          // Asset URL
   check_access(['role'])          // Check permission
   is_role('admin_prodi')          // Current role check
   get_initials('John Doe')        // 'JD'
   format_tanggal('2024-01-15')    // '15 Januari 2024'
   ```

4. **View Pattern**
   ```html
   <?php include ROOT_PATH . '/views/templates/sidebar.php'; ?>
   <?php include ROOT_PATH . '/views/templates/navbar.php'; ?>
   <?= flash_message() ?>
   ```

---

## 📞 Database Schema Reminder

Key tables untuk implementasi berikutnya:
```sql
-- Master Data
users
program_studi
instrumen_akreditasi

-- Workflow
pengajuan_akreditasi
jadwal_pendampingan
borang_files
review_borang

-- Archive
riwayat_akreditasi
activity_log
notifikasi
```

See `database/akreditasiflow_db.sql` for full schema with foreign keys.

---

## ❓ Frequently Needed

**Get all programs:**
```php
$programs = $this->db->fetchAll(
    "SELECT * FROM program_studi WHERE is_active = 1"
);
```

**Get all active users:**
```php
$users = $this->db->fetchAll(
    "SELECT * FROM users WHERE is_active = 1 AND role = :role",
    ['role' => 'admin_prodi']
);
```

**Check if user has certain role:**
```php
if (is_role('admin_universitas')) {
    // Show admin-only content
}
```

---

**Last Updated**: 2024
**Status**: Ready for Phase 2
**Next Milestone**: InstrumenController ✏️
