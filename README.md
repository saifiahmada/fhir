# Praktikum Pertukaran Informasi Kesehatan Menggunakan HL7 FHIR

**PHP Native | MySQL/MariaDB | HL7 FHIR | XAMPP**

## 1. Deskripsi Project

Project ini merupakan aplikasi simulasi pertukaran informasi kesehatan (*Health Information Exchange*) menggunakan standar **HL7 FHIR (Fast Healthcare Interoperability Resources)**.

Aplikasi dikembangkan menggunakan PHP Native dan MySQL/MariaDB untuk membantu mahasiswa memahami konsep dan implementasi teknis interoperabilitas data kesehatan antara dua fasilitas pelayanan kesehatan, yaitu:

1. **Aplikasi Puskesmas/Klinik** — aplikasi pengirim data pasien dan rujukan.
2. **Aplikasi Rumah Sakit** — aplikasi penerima dan penyimpan data kesehatan yang dikirimkan oleh Puskesmas/Klinik.

Kedua aplikasi dapat saling berkomunikasi melalui HTTP API dengan format pertukaran data JSON berstandar FHIR.

Project ini ditujukan sebagai media pembelajaran praktikum bagi mahasiswa Rekam Medis dan Informasi Kesehatan (RMIK), Manajemen Informasi Kesehatan (MIK), dan bidang kesehatan lainnya.

## 2. Tujuan Pembelajaran

Setelah menyelesaikan praktikum ini, mahasiswa diharapkan mampu:

- Memahami konsep interoperabilitas sistem informasi kesehatan.
- Mengenali struktur data dan resource pada HL7 FHIR.
- Memahami penggunaan JSON sebagai format pertukaran data.
- Memahami komunikasi antarsistem menggunakan HTTP API.
- Membuat aplikasi pengirim dan penerima data menggunakan PHP.
- Mengirimkan data pasien dan rujukan dari Puskesmas/Klinik ke Rumah Sakit.
- Memahami penggunaan FHIR Bundle untuk mengelompokkan beberapa resource.
- Memahami proses penerimaan, penyimpanan, dan pembaruan data FHIR pada sistem penerima.

## 3. Teknologi yang Digunakan

| Teknologi | Keterangan |
|---|---|
| PHP | Bahasa pemrograman aplikasi |
| MySQL/MariaDB | Database |
| HTML5 | Struktur halaman aplikasi |
| CSS | Tampilan antarmuka |
| JavaScript | Interaksi halaman |
| Bootstrap | Framework antarmuka |
| jQuery | Manipulasi DOM dan AJAX |
| HL7 FHIR | Standar pertukaran informasi kesehatan |
| JSON | Format representasi data |
| HTTP API | Komunikasi antaraplikasi |
| XAMPP | Web server dan database lokal |

## 4. Struktur Project

Setelah project diunduh atau di-clone, struktur folder utamanya adalah:

```text
fhir/
│
├── database/
│   ├── puskesmas.sql
│   └── rumah_sakit.sql
│
├── puskesmas/
│   ├── config/
│   │   └── app.php
│   ├── api/
│   ├── ...
│   └── send.php
│
├── rumah-sakit/
│   ├── config/
│   │   └── app.php
│   ├── api/
│   │   └── fhir.php
│   ├── ...
│   └── index.php
│
└── README.md
```

*Catatan: Folder dan file di atas merupakan gambaran struktur utama project.*

## 5. Persyaratan Sistem

Sebelum menjalankan aplikasi, pastikan komputer sudah memiliki:

- Sistem operasi Windows atau Linux.
- XAMPP yang sudah terinstal.
- PHP dengan ekstensi PDO MySQL dan cURL aktif.
- MySQL/MariaDB.
- Browser seperti Google Chrome atau Mozilla Firefox.

Untuk pengguna Linux Mint dengan XAMPP, direktori web server secara default berada di:

```text
/opt/lampp/htdocs/
```

## 6. Instalasi Project

### Langkah 1. Download Project

Clone repository menggunakan Git:

```bash
git clone https://github.com/USERNAME/fhir.git
```

Ganti `USERNAME` dengan username GitHub pemilik repository.

Atau unduh project melalui tombol **Code → Download ZIP** pada halaman GitHub.

Ekstrak project dan pastikan nama folder utamanya adalah:

```text
fhir
```

### Langkah 2. Letakkan Project di htdocs

Untuk Linux Mint dengan XAMPP:

```text
/opt/lampp/htdocs/fhir/
```

Untuk Windows dengan XAMPP:

```text
C:\xampp\htdocs\fhir\
```

### Langkah 3. Jalankan XAMPP

Pada Linux Mint:

```bash
sudo /opt/lampp/lampp start
```

Pastikan Apache dan MySQL berjalan.

Pada Windows, jalankan XAMPP Control Panel, kemudian aktifkan Apache dan MySQL.

### Langkah 4. Buat Database

Buka phpMyAdmin:

```text
http://localhost/phpmyadmin
```

Import dua file SQL yang tersedia di folder `database/`:

1. `puskesmas.sql`
2. `rumah_sakit.sql`

Kedua file tersebut akan membuat database:

| Database | Fungsi |
|---|---|
| db_puskesmas | Menyimpan data pasien dan rujukan Puskesmas |
| db_rumah_sakit | Menyimpan resource FHIR yang diterima Rumah Sakit |

Pastikan kedua database berhasil dibuat.

### Langkah 5. Periksa Konfigurasi Database

Periksa konfigurasi database pada masing-masing aplikasi:

```text
puskesmas/config/
rumah-sakit/config/
```

Sesuaikan host, nama database, username, dan password MySQL dengan konfigurasi XAMPP yang digunakan.

Contoh konfigurasi lokal:

```php
host     = 127.0.0.1
database = db_puskesmas / db_rumah_sakit
username = root
password = 
```

Sesuaikan dengan format konfigurasi yang tersedia di masing-masing file aplikasi.

### Langkah 6. Periksa Konfigurasi API FHIR

Buka file:

```text
puskesmas/config/app.php
```

Pastikan alamat endpoint penerima sesuai dengan lokasi folder project.

Contoh untuk project yang berada di:

```text
/opt/lampp/htdocs/fhir/
```

Konfigurasi URL penerima:

```php
define(
    'FHIR_RECEIVER_URL',
    'http://localhost/fhir/rumah-sakit/api/fhir.php'
);
```

Periksa juga API Key:

```php
define('FHIR_API_KEY', 'praktikum-fhir-key-2026');
```

Selanjutnya, buka konfigurasi aplikasi Rumah Sakit dan pastikan API Key yang digunakan sama dengan API Key pada aplikasi Puskesmas.

**Penting:** Jika nama folder project atau alamat server berubah, sesuaikan URL endpoint penerima agar proses komunikasi tetap berjalan.

## 7. Menjalankan Aplikasi

Setelah instalasi dan konfigurasi selesai, buka browser.

### Aplikasi Puskesmas

```text
http://localhost/fhir/puskesmas
```

Aplikasi ini berfungsi sebagai sistem pengirim data pasien dan rujukan.

### Aplikasi Rumah Sakit

```text
http://localhost/fhir/rumah-sakit
```

Aplikasi ini berfungsi sebagai sistem penerima data FHIR.

## 8. Alur Pertukaran Informasi Kesehatan

Secara umum, proses pertukaran informasi kesehatan pada project ini berlangsung sebagai berikut:

```text
+---------------------------+
|     APLIKASI PUSKESMAS    |
|                           |
|  1. Input Data Pasien     |
|  2. Input Data Rujukan    |
|  3. Membentuk FHIR Bundle |
+-------------+-------------+
              |
              |
              | HTTP POST
              | JSON FHIR Bundle
              | API Key
              |
              v
+---------------------------+
|    API FHIR RUMAH SAKIT   |
|                           |
|  4. Menerima Request      |
|  5. Validasi API Key      |
|  6. Memproses Resource    |
|  7. Menyimpan Data        |
+-------------+-------------+
              |
              v
+---------------------------+
|     DATABASE RUMAH SAKIT  |
|                           |
|  Patient                  |
|  Encounter                |
|  Observation              |
|  ServiceRequest            |
+-------------+-------------+
              |
              v
+---------------------------+
|     RESPONS API FHIR      |
|                           |
|  HTTP 200                 |
|  Transaction Response     |
+---------------------------+
```

## 9. Resource FHIR yang Digunakan

Project ini menggunakan empat resource utama:

| Resource | Fungsi |
|---|---|
| Patient | Menyimpan identitas pasien |
| Encounter | Menyimpan informasi kunjungan atau pelayanan pasien |
| Observation | Menyimpan hasil pemeriksaan, seperti tekanan darah |
| ServiceRequest | Menyimpan permintaan pelayanan atau rujukan |

Keempat resource tersebut dikirimkan dalam satu FHIR Bundle dengan tipe `transaction`.

Contoh struktur sederhana:

```json
{
  "resourceType": "Bundle",
  "type": "transaction",
  "entry": [
    {
      "resource": {
        "resourceType": "Patient"
      }
    },
    {
      "resource": {
        "resourceType": "Encounter"
      }
    },
    {
      "resource": {
        "resourceType": "Observation"
      }
    },
    {
      "resource": {
        "resourceType": "ServiceRequest"
      }
    }
  ]
}
```

Contoh di atas hanya ilustrasi struktur. Aplikasi menghasilkan resource dengan data dan atribut yang lebih lengkap.

## 10. Praktikum Pengiriman Data

Ikuti langkah berikut untuk mencoba pertukaran data:

1. Buka aplikasi Puskesmas.
2. Pilih atau masukkan data pasien.
3. Buat data rujukan pasien.
4. Pastikan data rujukan sudah tersimpan.
5. Klik tombol **Kirim ke Rumah Sakit**.
6. Amati FHIR Bundle dalam format JSON yang ditampilkan aplikasi.
7. Perhatikan respons dari API Rumah Sakit.
8. Jika berhasil, aplikasi menampilkan HTTP Status 200.
9. Buka aplikasi Rumah Sakit.
10. Periksa dashboard dan data resource FHIR yang telah diterima.

### Indikator Keberhasilan

Pengiriman dianggap berhasil apabila:

- Aplikasi Puskesmas berhasil mengirim HTTP POST.
- API Rumah Sakit menerima FHIR Bundle.
- API Key berhasil diverifikasi.
- Resource FHIR berhasil diproses dan disimpan.
- Rumah Sakit memberikan respons HTTP 200.

## 11. Mengenal HTTP Status

Dalam praktikum, mahasiswa dapat menemukan beberapa respons HTTP berikut:

| HTTP Status | Keterangan |
|---|---|
| 200 | Request berhasil diproses |
| 400 | Request tidak valid |
| 401 | API Key tidak sesuai atau tidak tersedia |
| 404 | Endpoint API tidak ditemukan |
| 405 | Metode HTTP tidak diizinkan |
| 500 | Terjadi kesalahan pada server |

Status HTTP 405 ketika endpoint dibuka langsung melalui browser dapat terjadi karena browser menggunakan metode GET, sedangkan endpoint penerima dirancang untuk menerima POST.

## 12. Ide Pengembangan Praktikum

Untuk memperdalam pemahaman interoperabilitas kesehatan, mahasiswa dapat mengembangkan project ini dengan beberapa skenario:

1. Menambahkan resource `Condition` untuk informasi diagnosis pasien.
2. Menambahkan resource `MedicationRequest` untuk permintaan obat.
3. Menambahkan resource `Practitioner` untuk identitas tenaga kesehatan.
4. Menambahkan resource `Organization` untuk identitas fasilitas kesehatan.
5. Mengembangkan pertukaran data dari Rumah Sakit kembali ke Puskesmas.
6. Mempelajari validasi resource menggunakan FHIR Validator.
7. Mempelajari penggunaan terminologi kesehatan seperti LOINC dan SNOMED CT.

## 13. Catatan Penting

Project ini merupakan **simulasi pembelajaran interoperabilitas kesehatan menggunakan HL7 FHIR**.

Beberapa hal yang perlu diperhatikan:

- Project ini bukan aplikasi rekam medis elektronik siap produksi.
- Implementasi ini belum merupakan integrasi resmi dengan SATUSEHAT.
- API Key yang digunakan hanya untuk simulasi autentikasi sederhana.
- Data pasien yang digunakan untuk praktikum sebaiknya berupa data dummy.
- Resource FHIR yang digunakan merupakan implementasi pembelajaran dan belum mencakup seluruh ketentuan profil FHIR Indonesia.
- Penggunaan di lingkungan pelayanan kesehatan nyata memerlukan penguatan keamanan, autentikasi, otorisasi, validasi profil, audit, dan perlindungan data pasien.

## 14. Lisensi dan Penggunaan

Project ini dikembangkan sebagai media pembelajaran praktikum pertukaran informasi kesehatan.

Mahasiswa, dosen, dan pengembang diperbolehkan mempelajari, menjalankan, serta mengembangkan project ini untuk kepentingan pendidikan dengan tetap mencantumkan sumber repository.

---

**Selamat belajar dan bereksperimen dengan HL7 FHIR!**

*Belajar interoperabilitas kesehatan dimulai dari memahami bagaimana dua sistem yang berbeda dapat saling bertukar informasi secara terstruktur.*
