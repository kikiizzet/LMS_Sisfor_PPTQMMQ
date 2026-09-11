# ERD PPTQMMQ Digital

File utama yang dapat diedit di diagrams.net/draw.io: `erd-pptqmmq.drawio`.

## Mermaid

```mermaid
erDiagram
    GURUS o|--o| KELAS : "wali_kelas_id"
    GURUS ||--o{ TEACHINGS : "guru_id"
    KELAS ||--o{ TEACHINGS : "kelas_id"
    KELAS ||--o{ SANTRIS : "kelas_id"
    SANTRIS ||--o{ PRESENSIS : "santri_id"
    GURUS ||--o{ EKSTRAKURIKULERS : "pembina_id"
    SANTRIS ||--o{ ORANG_TUAS : "santri_id"
    MUNAQOSYAH_PERIODES ||--o{ MUNAQOSYAH_PESERTAS : "periode_id"
    SANTRIS ||--o{ MUNAQOSYAH_PESERTAS : "santri_id"
    GURUS ||--o{ MUNAQOSYAH_PESERTAS : "penguji_id"
    SANTRIS ||--o{ HAFALAN_PROGRESSES : "santri_id"
    ORANG_TUAS ||--o{ WA_NOTIFICATIONS : "orang_tua_id"
    SANTRIS ||--o{ WA_NOTIFICATIONS : "santri_id"

    GURUS {
        bigint id PK
        string nik UK
        string nuptk UK
        string nama
        enum jenis_kelamin
        boolean is_active
    }
    KELAS {
        bigint id PK
        string nama_kelas
        bigint wali_kelas_id FK
        string tingkat
        string jurusan
        string jenis
        string kurikulum
        boolean is_active
    }
    TEACHINGS {
        bigint id PK
        bigint guru_id FK
        bigint kelas_id FK
        string mata_pelajaran
        string induk
        string kelompok
        string jurusan
        integer jtm
        boolean is_active
    }
    SANTRIS {
        bigint id PK
        string nama_lengkap
        string no_induk UK
        string nisn
        bigint kelas_id FK
        enum jenis_kelamin
        enum status
    }
    PRESENSIS {
        bigint id PK
        bigint santri_id FK
        date tanggal
        enum status
        string keterangan
    }
    EKSTRAKURIKULERS {
        bigint id PK
        string nama_ekstrakurikuler
        bigint pembina_id FK
        boolean is_active
    }
    ORANG_TUAS {
        bigint id PK
        bigint santri_id FK
        string nama
        string hubungan
        string no_whatsapp
        boolean is_active
    }
    MUNAQOSYAH_PERIODES {
        bigint id PK
        string semester
        string tahun_pelajaran
        date tanggal_ujian
        enum status
    }
    MUNAQOSYAH_PESERTAS {
        bigint id PK
        bigint periode_id FK
        bigint santri_id FK
        bigint penguji_id FK
        enum status
        decimal nilai
        text catatan
    }
    HAFALAN_PROGRESSES {
        bigint id PK
        bigint santri_id FK
        integer bulan
        integer tahun
        string juz_mulai
        string juz_selesai
        integer jumlah_halaman
        enum kualitas
        text catatan
    }
    WA_NOTIFICATIONS {
        bigint id PK
        bigint orang_tua_id FK
        bigint santri_id FK
        string tipe
        string referensi_type
        bigint referensi_id
        text pesan
        enum status
        timestamp sent_at
    }
```

## Keterangan Untuk Bab 3

ERD sistem PPTQMMQ Digital menggambarkan struktur penyimpanan data dan hubungan antarentitas. Entitas `gurus` menyimpan data guru dan menjadi induk bagi entitas `kelas`, `teachings`, serta `ekstrakurikulers`. Entitas `kelas` menyimpan data kelas dan berhubungan dengan `santris` serta `teachings`. Entitas `santris` menjadi induk data presensi melalui `presensis`.

Hubungan yang ditampilkan sebagai garis utama pada diagram berasal dari foreign key pada migration Laravel. Kardinalitas `1:N` berarti satu data pada entitas induk dapat memiliki banyak data pada entitas anak, sedangkan data anak hanya mengacu pada satu data induk. Foreign key yang nullable, seperti `kelas.wali_kelas_id`, `santris.kelas_id`, dan `ekstrakurikulers.pembina_id`, memungkinkan data anak belum memiliki induk.

Tabel `mapels` belum memiliki foreign key langsung ke `teachings`; keterkaitan mata pelajaran masih disimpan melalui nilai teks `mata_pelajaran`. Tabel `raports`, `raport_kmis`, dan `raport_tokens` juga menyimpan identitas santri/kelas dalam bentuk teks, bukan foreign key. Oleh karena itu, tabel-tabel tersebut dicantumkan sebagai entitas pendukung tanpa garis relasi eksplisit agar ERD sesuai dengan struktur database aktual.

Tabel `users`, `sessions`, `password_reset_tokens`, `cache`, dan `jobs` merupakan tabel pendukung framework Laravel. Tabel konten publik `prestasis`, `penghargaans`, `testimonis`, `donasis`, serta `questions` berdiri sendiri karena tidak memiliki foreign key ke entitas lain.

### Fitur Munaqosyah, Hafalan, dan WhatsApp

Entitas `munaqosyah_periodes` menyimpan jadwal ujian munaqosyah berdasarkan semester dan tahun pelajaran. Setiap periode memiliki banyak data `munaqosyah_pesertas`. Entitas peserta menghubungkan periode dengan `santris`, serta dapat menyimpan penguji dari entitas `gurus`, status kelulusan, nilai, dan catatan hasil ujian.

Entitas `hafalan_progresses` menyimpan perkembangan hafalan setiap santri secara berkala, minimal satu catatan untuk setiap santri pada setiap bulan dan tahun. Kolom seperti juz awal, juz akhir, jumlah halaman, kualitas hafalan, dan catatan dapat digunakan untuk membentuk laporan progres bulanan.

Entitas `orang_tuas` menyimpan nomor WhatsApp wali/orang tua yang terhubung dengan santri. Entitas `wa_notifications` menyimpan pesan yang dikirim, jenis referensi (misalnya `hafalan_bulanan` atau `hasil_munaqosyah`), status pengiriman, waktu pengiriman, dan identitas orang tua penerima. Dengan demikian, sistem tidak hanya mengirim pesan, tetapi juga dapat menampilkan riwayat berhasil, gagal, atau menunggu.

Untuk implementasi aktual, pengiriman WhatsApp memerlukan layanan gateway/API WhatsApp. ERD ini memodelkan data penerima dan riwayat pengiriman; kredensial API sebaiknya disimpan di konfigurasi environment, bukan di tabel.

## Legenda

- `PK`: primary key.
- `FK`: foreign key.
- `UK`: unique key.
- Garis relasi utama: foreign key benar-benar didefinisikan pada migration.
- Keterkaitan teks/JSON: hubungan logis aplikasi, tetapi belum menjadi relasi database.
- `MUNAQOSYAH_PESERTAS`: tabel transaksi peserta dan hasil ujian per periode.
- `HAFALAN_PROGRESSES`: tabel transaksi progres hafalan bulanan per santri.
- `WA_NOTIFICATIONS`: tabel log pengiriman pesan ke orang tua/wali.
