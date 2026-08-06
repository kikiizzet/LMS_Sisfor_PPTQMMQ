# 📚 LMS & Sistem Informasi PPTQMMQ Digital

Aplikasi ini adalah **Learning Management System (LMS) & Sistem Informasi Akademik** untuk Pondok Pesantren Tahfidz Al-Qur'an (PPTQ) dan Madrasah Mualimin/Mualimat Al-Quran (MMQ). Aplikasi ini dikembangkan menggunakan framework **Laravel 12**, **Tailwind CSS**, dan **Vite** serta terintegrasi dengan **Gemini AI** untuk fitur chatbot interaktif.

---

## 🛠️ Persyaratan Sistem (Prerequisites)

Sebelum menjalankan aplikasi, pastikan komputer Anda sudah terinstal:
*   **PHP >= 8.2** (disertai ekstensi PHP yang diperlukan oleh Laravel seperti `pdo_mysql`, `openssl`, `mbstring`, dll)
*   **Composer** (Pengelola dependensi PHP)
*   **Node.js & NPM** (Untuk mengompilasi aset CSS & JS)
*   **MySQL atau MariaDB** (Sebagai database server, bisa menggunakan XAMPP/Laragon)
*   **Web Browser** modern (Chrome, Edge, Firefox, dll)

---

## 🚀 Panduan Instalasi & Konfigurasi

Ikuti langkah-langkah di bawah ini untuk menjalankan aplikasi di komputer Anda:

### 1. Ekstrak File Project
Ekstrak file `.rar` atau `.zip` project ini ke direktori web server Anda (misalnya `htdocs` jika menggunakan XAMPP, atau folder pilihan Anda jika menggunakan Laragon/PHP CLI).

### 2. Salin dan Konfigurasi Environment File (`.env`)
Salin file `.env.example` dan ubah namanya menjadi `.env`.
*   **Windows (Command Prompt / PowerShell):**
    ```bash
    copy .env.example .env
    ```
*   **Linux / macOS / Git Bash:**
    ```bash
    cp .env.example .env
    ```

Buka file `.env` yang baru dibuat menggunakan text editor (VS Code, Notepad, dll) dan sesuaikan konfigurasi database Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_mmq
DB_USERNAME=root
DB_PASSWORD=
```
*(Sesuaikan `DB_USERNAME` dan `DB_PASSWORD` sesuai dengan kredensial MySQL lokal Anda).*

### 3. Konfigurasi API Key Gemini AI (Opsional)
Jika ingin mengaktifkan fitur chatbot AI, silakan isi API Key Gemini Anda pada file `.env` di baris berikut:
```env
GEMINI_API_KEYS=isi_api_key_gemini_anda_di_sini
```
*(Anda dapat memasukkan beberapa API Key dipisahkan dengan koma `,` jika ingin menggunakan mekanisme load balancing / fallback).*

### 4. Instal Dependensi Backend (PHP Composer)
Jalankan perintah berikut di terminal/command prompt pada direktori root project untuk menginstal library PHP:
```bash
composer install
```

### 5. Generate Application Key
Jalankan perintah berikut untuk membuat key pengaman aplikasi Laravel Anda:
```bash
php artisan key:generate
```

### 6. Setup Database & Import Database SQL
1. Aktifkan MySQL di control panel server Anda (XAMPP/Laragon).
2. Buka **phpMyAdmin** (`http://localhost/phpmyadmin`) atau tool database management lainnya.
3. Buat database baru dengan nama **`db_mmq`**.
4. Pilih database tersebut, masuk ke tab **Import**, lalu pilih file database **`db_mmq.sql`** (yang dilampirkan bersama file RAR ini) dan klik **Import** / **Go**.
5. *Catatan:* Jika ingin melakukan migrasi dari awal tanpa import dump sql, atau membuat akun admin baru di database bersih, Anda bisa menjalankan:
   ```bash
   php artisan migrate --seed
   ```

### 7. Buat Symbolic Link untuk Storage
Agar file/gambar yang diunggah (seperti poster donasi, foto santri, dll) dapat diakses dengan benar di web browser, jalankan perintah ini:
```bash
php artisan storage:link
```

### 8. Instal Dependensi Frontend & Compile Aset
Instal pustaka JavaScript (seperti Tailwind CSS dan Alpine.js) serta jalankan compiler front-end dengan perintah berikut:
```bash
# Instal dependensi JavaScript
npm install

# Compile aset untuk mode development (tetap biarkan terminal ini terbuka)
npm run dev

# ATAU compile aset untuk mode produksi (sekali jalan)
npm run build
```

---

## 🔑 Kredensial Login Admin Bawaan (Default Credentials)

Setelah aplikasi berhasil dikonfigurasi dan dijalankan, Anda dapat masuk ke Dashboard Admin menggunakan akun default berikut:

*   **URL Login:** `http://localhost:8000/login`
*   **Email:** `admin@pesantren.com`
*   **Password:** `admin123`

> ⚠️ **PENTING:** Demi keamanan sistem, disarankan untuk segera mengganti password default administrator ini setelah pertama kali berhasil login di panel admin.

---

## 💻 Cara Menjalankan Aplikasi

Untuk mengakses aplikasi secara lokal:
1. Jalankan server lokal Laravel dengan perintah:
   ```bash
   php artisan serve
   ```
2. Buka browser Anda dan akses url: [http://localhost:8000](http://localhost:8000)

---

## 📝 Catatan Khusus untuk Pemilik Project (Sebelum Dikirim ke RAR)

Sebelum Anda mengompres folder project ini menjadi file `.rar` atau `.zip`, silakan ikuti panduan berikut agar file lebih bersih dan berukuran jauh lebih ringan:
1. **Ekspor Database Anda:** Ekspor database dari phpMyAdmin lokal Anda ke format `.sql`, lalu simpan file tersebut di folder root project dengan nama **`db_mmq.sql`**.
2. **Hapus Folder `vendor` dan `node_modules`:** 
   * Folder `vendor` (dependensi Composer) dan `node_modules` (dependensi NPM/Vite) memiliki ukuran file yang sangat besar (bisa mencapai ratusan MB).
   * Hapus kedua folder tersebut terlebih dahulu sebelum membungkusnya ke RAR. Penerima project akan dengan mudah menginstalnya kembali menggunakan perintah `composer install` dan `npm install` sesuai panduan di atas.
3. **Pastikan File `.env` tidak ikut terkirim (Opsional):** Demi keamanan API Key Gemini Anda, sebaiknya hapus file `.env` rahasia Anda atau pastikan penerima project menggunakan `.env.example` sebagai acuan mereka.
