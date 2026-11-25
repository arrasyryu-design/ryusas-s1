# SIPEKA — Sistem Informasi Pelaporan Kerusakan Fasilitas Sekolah

Source code Laravel 12/13 (PHP + MySQL + Blade + Bootstrap 5) mengikuti PRD v1.1 dan desain Google Stitch.
Hasil ini berisi **file aplikasi saja** (tanpa `vendor`, `node_modules`, `.git`, `.env`). Salin ke instalasi Laravel Anda.

## Cara memasang (tanpa terminal)

1. **Cadangkan** dulu proyek Laravel Anda (salin foldernya).
2. **Buat database baru** di phpMyAdmin, mis. `sipeka` (collation `utf8mb4_unicode_ci`), lalu **Impor** file
   `database/sipeka_database.sql`. File ini membuat semua tabel + 6 kategori awal + 1 akun admin.
   > Gunakan database baru yang kosong. Jangan menjalankan `migrate` pada database yang sama.
3. **Salin file**: tarik seluruh isi folder ini ke folder proyek Laravel Anda dan pilih *Replace/Timpa* bila ditanya.
   File yang **menimpa file bawaan Laravel** (pastikan Anda belum mengubahnya sendiri; jika sudah, gabungkan manual):

   | File | Perubahan |
   |---|---|
   | `bootstrap/app.php` | alias middleware `admin`, redirect tamu ke `/login` |
   | `config/auth.php` | tambah guard `member` (akun siswa terpisah dari admin) |
   | `routes/web.php` | seluruh route SIPEKA |
   | `app/Models/User.php` | tambah `is_active`, relasi |
   | `app/Providers/AppServiceProvider.php` | pagination Bootstrap 5 + locale tanggal |
   | `app/Http/Controllers/Controller.php` | sama dengan bawaan Laravel |
   | `database/seeders/DatabaseSeeder.php` | memanggil seeder SIPEKA |

   Semua file lain **baru** (model, controller, service, view, `public/css`, `public/js`, `public/images`, `config/sipeka.php`, migration).
4. **Atur `.env`** milik proyek Anda (jangan ganti `APP_KEY` yang sudah ada). Samakan dengan `.env.example`
   pada bagian: `DB_DATABASE=sipeka`, `DB_USERNAME`, `DB_PASSWORD`, `APP_LOCALE=id`, `APP_TIMEZONE=Asia/Jakarta`,
   `SESSION_DRIVER=file`, `CACHE_STORE=file`, `QUEUE_CONNECTION=sync`, dan variabel `SCHOOL_*`.
5. **Hapus cache lama** (pengganti perintah `optimize:clear`): hapus isi `bootstrap/cache/` kecuali `.gitignore`
   (khususnya `config.php`, `routes-*.php`) dan file di `storage/framework/views/`.
6. Buka situs. Halaman publik di `/`, login admin di `/login`.

**Akun admin awal:** `admin@sman1nusantara.sch.id` / `Admin12345` — **segera ganti** (ubah di tabel `users`; password harus berupa hash bcrypt).

## Halaman

| Publik (tanpa login) | Anggota (opsional) | Admin |
|---|---|---|
| `/` beranda + panduan | `/anggota/daftar` | `/login` |
| `/lapor` form + upload foto | `/anggota/login` | `/admin/dashboard` |
| `/tracking?kode=...` | | `/admin/laporan`, `/admin/laporan/{id}` |
| `/laporan`, `/laporan/{kode}` | | `/admin/kategori`, `/admin/aktivitas` |
| | | `/admin/laporan-pdf` |

## Catatan desain & keputusan

- **Tampilan** mengikuti design system Stitch (navy `#082052`, krem `#F8F0E5`, Inter + JetBrains Mono) lewat `public/css/sipeka.css` di atas Bootstrap 5. Tailwind tidak dipakai.
- **Bootstrap, ikon, dan font dimuat dari CDN** sehingga tidak perlu `npm`/Vite build (Anda tidak perlu terminal). Komputer perlu terhubung internet saat demo.
- **Kode laporan** berformat `SKL-YYYYMMDD-XXXXXX` (acak, sulit ditebak) sesuai PRD.
- **Foto** disimpan di `storage/app/private/reports/...` (tidak publik, tidak perlu `storage:link`) dan disajikan lewat URL bertanda tangan yang kedaluwarsa dalam 3 jam, hanya pada halaman yang berhak.
- **Anggota** memakai tabel `members` + guard `member`; tidak bisa membuka `/admin/*`. Tidak ada dashboard/riwayat anggota. Melapor dan melacak tetap tanpa akun.
- **Laporan PDF** berupa halaman cetak; tekan *Cetak / Simpan PDF* lalu pilih "Simpan sebagai PDF" (tanpa library tambahan).
- **Tidak termasuk**: Manajemen Admin & Pengaturan Profil (opsional di PRD), reset password (sesuai desain: hubungi TU), role Petugas.
- Kategori awal mengikuti PRD (Elektronik, Furnitur, Bangunan, Sanitasi, Infrastruktur, Lainnya).

## Jika ada masalah

- **Unggah foto gagal / "foto gagal diunggah"**: naikkan di `php.ini` `upload_max_filesize = 6M` dan `post_max_size = 20M` (3 foto × 5 MB), lalu restart Apache/Laragon/XAMPP.
- **Halaman 404 di semua rute selain `/`**: pastikan `.htaccess` di folder `public` ada dan `mod_rewrite` aktif.
- **Perubahan tidak terbaca**: ulangi langkah 5 (hapus cache).
- **Error koneksi database**: periksa `DB_*` di `.env`.
