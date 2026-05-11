# Sistem Manajemen Shipping & Container

Aplikasi berbasis web yang dirancang untuk menyederhanakan pengelolaan logistik pengiriman barang (shipping) dengan hierarki data bertingkat (4 level). Sistem ini mampu mengimpor dokumen Excel masukan berukuran masif, memecahnya menjadi struktur relasional dinamis, melacak progres penyelesaian kontainer secara mandiri, dan melahirkan riwayat audit yang akuntabel.

## 🚀 Fitur Utama

- **Hierarki Data 4-Level**: `Shipping Code` ➔ `Containers` ➔ `Cases` ➔ `Parts`.
- **Intelligent Excel Parser**: Penanganan cerdas terhadap *merged cells* dan *atomic transactions* saat impor file.
- **Workflow Immutable State**: Penguncian data otomatis pada status 'Complete' untuk mencegah perubahan tidak sah.
- **Drill-Down Pagination**: Penanganan skalabilitas data jutaan baris tanpa membebani memori server.
- **Sistem Audit Log**: Pencatatan setiap aktivitas pengguna secara real-time.
- **Export Data**: Ekspor data hierarkis kembali ke format tabular datar (Excel) secara efisien.

---

## 📋 Prasyarat Sistem (Requirements)

Sebelum memulai, pastikan perangkat Anda sudah menginstal:
- **PHP >= 8.3**
- **Composer**
- **Node.js & NPM** (Versi LTS direkomendasikan)
- **SQLite** (Default, atau DBMS lain seperti MySQL/PostgreSQL)

---

## 🛠️ Cara Setup Aplikasi

Pilih panduan sesuai dengan sistem operasi yang Anda gunakan.

### 🐧 Untuk Linux / macOS

1. **Clone repositori ini** ke folder lokal Anda:
   ```bash
   git clone <repository-url>
   cd okamoto-test
   ```

2. **Salin berkas environment**:
   ```bash
   cp .env.example .env
   ```

3. **Install dependensi PHP**:
   ```bash
   composer install
   ```

4. **Generate Application Key**:
   ```bash
   php artisan key:generate
   ```

5. **Siapkan Database (SQLite)**:
   Secara default aplikasi ini menggunakan SQLite. Buat file databasenya jika belum ada:
   ```bash
   touch database/database.sqlite
   ```

6. **Jalankan Migrasi Database**:
   ```bash
   php artisan migrate
   ```

7. **Install & Build Aset Frontend**:
   ```bash
   npm install
   npm run build
   ```

---

### 🪟 Untuk Windows

1. **Clone repositori ini** (bisa menggunakan Git Bash / Command Prompt):
   ```cmd
   git clone <repository-url>
   cd okamoto-test
   ```

2. **Salin berkas environment**:
   - Di Command Prompt (CMD):
     ```cmd
     copy .env.example .env
     ```
   - Di PowerShell:
     ```powershell
     Copy-Item .env.example .env
     ```

3. **Install dependensi PHP**:
   ```cmd
   composer install
   ```

4. **Generate Application Key**:
   ```cmd
   php artisan key:generate
   ```

5. **Siapkan Database (SQLite)**:
   - Di Command Prompt (CMD):
     ```cmd
     echo. > database/database.sqlite
     ```
   - Di PowerShell:
     ```powershell
     New-Item -Path "database\database.sqlite" -ItemType File
     ```

6. **Jalankan Migrasi Database**:
   ```cmd
   php artisan migrate
   ```

7. **Install & Build Aset Frontend**:
   ```cmd
   npm install
   npm run build
   ```

---

## ⚡ Cara Menjalankan Aplikasi (Running Application)

Untuk menjalankan aplikasi di lingkungan pengembangan lokal (*development environment*), Anda perlu menjalankan dua terminal secara bersamaan:

### Terminal 1: Menjalankan Server PHP
Jalankan server bawaan Laravel:
```bash
php artisan serve
```
Aplikasi akan dapat diakses secara default melalui peramban di alamat: `http://127.0.0.1:8000`

### Terminal 2: Menjalankan Vite (Frontend Live Reload)
Jika Anda sedang melakukan modifikasi pada tampilan CSS (Tailwind) atau JS, jalankan ini agar perubahan langsung terlihat:
```bash
npm run dev
```

---

## 📝 Informasi Tambahan

- Kerangka Kerja: Laravel 13
- Engine Frontend: Vite & Tailwind CSS
- Paket Utama: Laravel Breeze, Maatwebsite Excel
- Dokumentasi Teknis Lengkap dapat dilihat pada file: `DOCUMENTATION.md`
