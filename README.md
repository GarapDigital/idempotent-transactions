# Idempotency Key dalam Transaksi Laravel

Idempotency Key adalah mekanisme penting dalam sistem transaksi untuk memastikan bahwa permintaan yang sama tidak diproses lebih dari satu kali. Ini sangat berguna dalam sistem pembayaran, pemesanan, atau operasi lainnya yang harus dieksekusi hanya sekali.

## 🚀 Fitur yang Disediakan

- Mencegah transaksi duplikat dengan menggunakan Idempotency Key.
- Middleware Idempotency untuk memeriksa apakah permintaan sudah pernah diproses.
- Riwayat transaksi per pengguna, bisa diurutkan berdasarkan bulan dan tahun.
- API login & logout dengan Artisan Command untuk manajemen otentikasi melalui terminal.

## 🔍 Apa Itu Idempotency Key?

Idempotency Key adalah nilai unik yang dikirim bersama permintaan untuk memastikan bahwa permintaan yang sama tidak diproses lebih dari sekali.

## 📌 Bagaimana Cara Kerjanya?

- Klien mengirim permintaan ke API dengan menyertakan header Idempotency-Key.
- Server memeriksa apakah kunci tersebut sudah pernah digunakan.
- Jika belum ada, server memproses transaksi dan menyimpan Idempotency Key ke dalam database.
- Jika sudah ada, server mengembalikan hasil transaksi sebelumnya tanpa memproses ulang.

## ✅ Kelebihan Idempotency Key

- Menghindari transaksi ganda, terutama dalam sistem pembayaran.
- Meningkatkan keandalan API, karena bisa menangani permintaan berulang dengan aman.
- Memudahkan debugging, karena setiap transaksi memiliki ID unik.

## ⚠️ Kekurangan Idempotency Key

- Memerlukan penyimpanan tambahan untuk mencatat setiap Idempotency Key.
- Harus diimplementasikan dengan benar, jika tidak, bisa menyebabkan inkonsistensi data.
- Tidak otomatis berlaku untuk semua endpoint, harus diterapkan secara eksplisit pada transaksi penting.

## 📜 Instalasi & Penggunaan

### 1️⃣ Instalasi

Pastikan Anda telah menginstal Laravel dan Sanctum untuk otentikasi API.

```
git clone https://github.com/GarapDigital/idempotent-transactions.git
composer install
php artisan migrate
```

### 2️⃣ Menjalankan Server

```
php artisan serve
```

### 3️⃣ Membuat Transaksi dengan Idempotency Key

Gunakan API berikut untuk membuat transaksi:

```
POST /api/transactions HTTP/1.1
Host: your-api.com
Authorization: Bearer {TOKEN}
Idempotency-Key: unique-key-1234
Content-Type: application/json

{
    "amount": 100.00
}
```

Jika permintaan ini dikirim ulang dengan Idempotency-Key yang sama, API akan menolak duplikasi.

### 4️⃣ Mendapatkan Riwayat Transaksi

```
GET /api/transactions/history?month=3&year=2025 HTTP/1.1
Host: your-api.com
Authorization: Bearer {TOKEN}
```

## 🔑 Login & Logout dengan Artisan Command

### 📌 Login User

```
php artisan auth:login user@example.com password123
```

Jika berhasil, akan menghasilkan token untuk otentikasi.

### 📌 Logout User

```
php artisan auth:logout user@example.com
```

Semua token akan dihapus dan sesi user akan berakhir.

### 📌 Kesimpulan

Dengan implementasi Idempotency Key, transaksi lebih terjamin keamanannya, tidak duplikat, dan lebih andal. Sistem ini sangat cocok untuk aplikasi yang menangani transaksi sensitif seperti pembayaran atau pemesanan.

Jika ada pertanyaan atau masukan, silakan diskusi lebih lanjut! 🚀
