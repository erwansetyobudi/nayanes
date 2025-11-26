# Nayanes: The SLiMS Search Proxy

## Deskripsi
Nayanes adalah sistem proxy pencarian untuk SLiMS (Senayan Library Management System) yang memungkinkan pencarian terintegrasi across multiple library nodes/catalogs.

## Persyaratan Sistem
- PHP 5.6 atau lebih tinggi
- Ekstensi SimpleXML
- Ekstensi cURL (disarankan)
- Akses internet untuk koneksi ke node pencarian

## Instalasi

### 1. Upload Files
Upload semua file Nayanes ke direktori web server Anda.

### 2. Konfigurasi HTTPS
Karena sistem ini dirancang untuk berjalan di lingkungan HTTPS, pastikan:

**A. Konfigurasi Server**
- Pastikan SSL certificate sudah terinstall dengan benar
- Redirect semua traffic HTTP ke HTTPS

**B. Modifikasi Konfigurasi**
File `sysconfig.inc.php` sudah dimodifikasi untuk mendukung HTTPS:

```php
// HTTPS Configuration
$sysconf['https']['enable'] = true;
$sysconf['base_url'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
$sysconf['domain'] = $_SERVER['HTTP_HOST'];
```

**C. Node Pencarian**
Semua URL node telah diupdate ke HTTPS:
```php
$sysconf['node'][1] = array('url' => 'https://perpustakaan.kemdikbud.go.id/libsenayan', 'desc' => 'Perpustakaan Kementerian Pendidikan dan Kebudayaan');
// ... dan seterusnya
```

### 3. Permissions
Pastikan direktori memiliki permission yang tepat:
```bash
chmod 755 lib/
chmod 644 *.php
```

## Konfigurasi

### Node Pencarian
Edit file `sysconfig.inc.php` untuk menambah/mengubah node pencarian:

```php
$sysconf['node'][X] = array(
    'url' => 'https://example.com/libsenayan', 
    'desc' => 'Deskripsi Perpustakaan'
);
```

### Timeout Request
```php
$sysconf['request_timeout'] = 5000; // dalam milidetik
```

## Troubleshooting

### Masalah Umum Setelah Migrasi ke HTTPS

**1. Pencarian Tidak Menghasilkan Hasil**
- Pastikan semua node mendukung HTTPS
- Cek error log server untuk detail error
- Verifikasi SSL certificate valid

**2. Mixed Content Errors**
- Pastikan semua resource (CSS, JS, images) diload via HTTPS
- Update semua URL di template menjadi HTTPS

**3. SSL Certificate Issues**
Jika node tertentu memiliki certificate issues, sistem akan:
- Mencoba versi HTTPS terlebih dahulu
- Fallback ke HTTP jika HTTPS gagal
- Ignore SSL verification untuk self-signed certificates

**4. Enable Debugging**
Tambahkan kode berikut untuk debugging:

```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

### Testing Koneksi Node

**Test Manual dengan cURL:**
```bash
curl -I https://perpustakaan.kemdikbud.go.id/libsenayan
```

**Test dari Browser:**
Buka URL node langsung di browser untuk verifikasi accessibility.

## File-file Penting

- `index.php` - File utama
- `sysconfig.inc.php` - Konfigurasi sistem
- `lib/modsxmlsenayan.inc.php` - Parser MODS XML
- `lib/contents/` - Berisi halaman konten
- `templates/default/` - Template tampilan

## Daftar Node yang Didukung

1. Perpustakaan Kementerian Pendidikan dan Kebudayaan
2. Pusat Perpustakaan Islam Indonesia  
3. Perpustakaan BAPETEN
4. Perpustakaan KPK
5. Union Catalog Yogyakarta (Jogjalib.net)
6. Union Catalog Priyangan Timur (Primurlib.net)
7. Union Catalog Makassar (Makassarlib.net)
8. Union Catalog Jawa Tengah

## Keamanan

- Input validation pada semua parameter
- URL filtering untuk mencegah SSRF attacks
- HTTPS enforcement
- Secure XML parsing

## Support

Jika mengalami masalah:
1. Cek error logs web server
2. Verifikasi koneksi ke masing-masing node
3. Pastikan konfigurasi HTTPS sudah benar
4. Test dengan node yang berbeda

## Lisensi
Program ini menggunakan GNU General Public License v3. Lihat file LICENSE untuk detail lengkap.

## Changelog

### Versi 5 (HTTPS Update)
- Migrasi penuh ke HTTPS
- Penambahan fallback mechanism
- Improved error handling
- SSL verification options

---

**Catatan:** Setelah migrasi ke HTTPS, pastikan untuk mengupdate semua bookmark dan link yang mengarah ke sistem Nayanes.
