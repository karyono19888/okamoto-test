# Sistem Manajemen Shipping & Container
**Dokumentasi Logika & Arsitektur Teknis**

Dokumen ini merangkum secara komprehensif arsitektur sistem, logika penanganan data (Excel), aturan bisnis, dan fitur keamanan yang diimplementasikan pada aplikasi ini.

---

## 📋 1. Gambaran Umum Sistem
Aplikasi ini dirancang untuk menyederhanakan pengelolaan logistik pengiriman barang (shipping) dengan hierarki data bertingkat 4 level. Sistem mampu menerima dokumen Excel masukan berukuran masif, memecahnya menjadi struktur relasional dinamis, melacak progres penyelesaian kontainer secara mandiri, dan melahirkan riwayat audit yang akuntabel.

---

## 🏗️ 2. Arsitektur Basis Data (ERD Ringkas)
Sistem menggunakan struktur data berjenjang (Parent-Child Cascade) untuk menormalkan jutaan baris data menjadi efisien:

1.  **`shipping_codes`**: Entitas utama / ID Grup Pengiriman.
    *   *Unik berdasarkan kode dokumen.*
2.  **`containers`**: Kontainer spesifik di bawah 1 shipment code.
    *   *Memiliki field `status` (enum: shipping, complete).*
3.  **`level_cases`**: Kardus / Case di dalam 1 kontainer.
    *   *Menyimpan `model`, `o_f`, `lot_no`, dan `case_no`.*
4.  **`level_parts`**: Part satuan terkecil di dalam Case.
    *   *Menyimpan `parts_no`, `ruibe`, `parts_name`, `qty`, bobot berat, dan `fta_code`.*

---

## ⚙️ 3. Logika Impor Excel (Intelligent Parser)
Terletak di: `app/Imports/ShippingDataImport.php`

Karena format visual Excel sering menggabungkan sel (merged cell) atau mengosongkan kolom di baris berikutnya, parser dirancang dengan logika "Persistent Tracking Context":

1.  **Pre-Scan Uniqueness**: Sebelum memasukkan data, parser memindai semua `Shipping Code` unik di file Excel. Jika ada salah satu yang sudah eksis di database, proses dibatalkan seketika (Rollback) untuk mencegah data ganda.
2.  **Context Mapping**:
    *   Jika baris baru memiliki nomor kontainer, parser memperbarui referensi `currentContainer`. Jika kosong, baris itu akan otomatis diasosiasikan dengan referensi kontainer terakhir yang tidak kosong.
    *   Pola yang sama berlaku untuk pendeteksian `Case` baru (jika kolom case identifiers terisi).
3.  **Transactional Atomic**: Seluruh proses dibungkus dalam `DB::transaction`. Gagal 1 baris, seluruh dokumen dibatalkan (Consistency).

---

## 🔒 4. Aturan Bisnis & State Machine (Workflow)
### Status Kontainer:
*   **Shipping** (Default Awal): Seluruh part dapat diedit dan dihapus. Kontainer bisa dihapus.
*   **Complete**: Kondisi final yang tidak bisa diubah kembali (Immutable).

### Logika Immutability (Penguncian Otomatis):
Sistem secara ketat menegakkan kebijakan penguncian (Lockdown) baik di UI maupun Controller:
1.  **Tombol Otomatis Menghilang**: Jika status `complete`, tombol Edit & Delete di daftar kontainer maupun part individu langsung disembunyikan.
2.  **Server-Side Blocking**: Controller ([ShippingController.php](file:///var/www/karyono/okamoto-test/app/Http/Controllers/ShippingController.php)) mengecek status kontainer induk sebelum memproses permintaan Hapus/Edit. Jika `complete`, server melempar Exception/Abort.
3.  **Shipment Cascade Lock**: Di dashboard utama, jika salah satu kontainer di dalam sebuah shipment berstatus `complete`, maka seluruh grup Shipment tersebut dikunci dari aksi Penghapusan Master.

---

## 🚀 5. Arsitektur UI: Drill-Down Pagination
Menghadapi skalabilitas jutaan baris data, sistem **tidak memuat seluruh data anak dalam satu loop eager-load**. Sebaliknya, digunakan konsep perambah berjenjang (Drill-Down):

*   **Level 1 (Main)**: Menampilkan list Shipment.
*   **Level 2 (Shipment Show)**: Melakukan query terpisah ke tabel kontainer menggunakan Pagination (25 data/page).
*   **Level 3 (Container Show)**: Masuk ke kontainer terpilih, query tabel cases dengan Pagination (50 data/page).
*   **Level 4 (Case Show)**: Menampilkan tabel list parts satuan dengan Pagination (100 data/page).

*Manfaat*: Kecepatan respon tetap stabil di bawah 100ms tanpa membebani RAM server (Mencegah PHP Memory Exhausted Error).

---

## 📜 6. Sistem Jejak Audit (Auditing Logs)
Terletak di: `app/Traits/LogsActivity.php` & `app/Models/ActivityLog.php`

Setiap aksi pengguna tercatat rapi secara real-time tanpa kecuali:
*   **Aksi Tercatat**: Login, Akses Halaman View, Akses Halaman Edit, Melakukan Upload (Berhasil/Gagal), Menghapus, dan Mengubah Status ke Complete.
*   **Data Metadata Lengkap**: ID & Nama User, Label Aksi, Deskripsi Dinamis, Timestamp Detik, Alamat IP, dan User Agent (Browser).
*   *UI Monitoring*: Disediakan menu **Audit Logs** di navbar khusus administrator.

---

## 📊 7. Logika Generator Ekspor Excel
Terletak di: `app/Exports/ShippingDataExport.php`

Logika ekspor secara efisien membalikkan relasi hierarkis berjenjang kembali menjadi flat 2D tabel rata:
1.  Menggunakan query Laravel `join` dari table Part naik ke level Case, Kontainer, hingga Master Shipping.
2.  Melakukan pemetaan (Mapping) field sesuai urutan visual asli (13 kolom) secara statis dan rigid.
3.  Memaksimal formatting angka desimal (6 digit belakang koma) menggunakan PHP `number_format` agar angka tetap konsisten di Excel.

---
*Dokumen ini dibuat pada: 11 Mei 2026*
*Framework: Laravel 13 + PHP + Breeze + Maatwebsite Excel*
