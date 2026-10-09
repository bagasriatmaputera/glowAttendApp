<div align="center">

# 🌟 GlowAttend

**Modern Attendance & Employee Management System**

Aplikasi sistem presensi berbasis GPS geolokasi dan manajemen karyawan modern dengan antarmuka responsif (Desktop & Mobile Portal), workflow persetujuan berjenjang, dan manajemen cuti terintegrasi.

[![Live Demo](https://img.shields.io/badge/Live_Demo-glowattend--demo.bagasr.my.id-6366f1?style=for-the-badge&logo=googlechrome&logoColor=white)](https://glowattend-demo.bagasr.my.id)
[![Laravel](https://img.shields.io/badge/Laravel_11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![Livewire](https://img.shields.io/badge/Livewire_3-4E56A6?style=for-the-badge&logo=livewire&logoColor=white)](https://livewire.laravel.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![Docker](https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white)](https://www.docker.com)

</div>

---

## 📌 Fitur Utama

- 📍 **Presensi Berbasis GPS Geolokasi**  
  Pencatatan kehadiran (*Clock In* & *Clock Out*) secara akurat dengan validasi koordinat GPS dan radius jangkauan lokasi kantor.
- 👥 **Multi-Role Access Control (Spatie)**  
  - **Owner (Management)**: Monitoring statistik dashboard, approval persetujuan data/cuti, dan manajemen sistem penuh.
  - **Admin (HR / Operasional)**: Manajemen data karyawan, monitoring rekap absensi, pengolahan cuti, lokasi kantor, dan pengumuman.
  - **Karyawan (Mobile Portal View)**: Tampilan mobile khusus untuk presensi mandiri, form pengajuan cuti, riwayat absensi, dan notifikasi.
- 📅 **Manajemen & Alur Persetujuan Cuti**  
  Pengajuan izin dan cuti secara online dengan tracking status (*Pending*, *Approved*, *Rejected*).
- 🏢 **Multi-Lokasi Kantor**  
  Konfigurasi titik koordinat latitude/longitude dan radius absensi per cabang kantor.
- 📢 **Broadcast Pengumuman & Notifikasi**  
  Penyampaian informasi internal perusahaan secara real-time ke akun karyawan.
- 🚀 **Demo Quick-Login**  
  Fitur 1-click select account pada halaman login untuk memudahkan pengujian semua role.

---

## 🛠️ Tech Stack

| Komponen | Teknologi |
| :--- | :--- |
| **Backend** | PHP 8.3 / 8.4, Laravel 11 / 13 |
| **Reactivity & Logic** | Livewire 3, Livewire Volt, Alpine.js |
| **Styling & UI** | Tailwind CSS, WireUI, Blade Components |
| **Database & Auth** | MySQL / MariaDB, Spatie Laravel-Permission |
| **Reporting & Export** | Maatwebsite Excel, Laravel DomPDF |
| **Deployment** | Docker (Multi-stage Build), Nginx Alpine, Docker Compose |

---

## 🔑 Akun Demo (Default Credentials)

| Role | Email / Login | Password | Akses & Hak Istimewa |
| :--- | :--- | :--- | :--- |
| **Owner / Management** | `owner@glow.com` | `password` | Dashboard statistik, persetujuan *Pending Changes*, kontrol penuh |
| **Admin** | `management@glow.com` | `password` | Kelola karyawan, absensi, cuti, lokasi kantor, dan pengumuman |
| **Karyawan (Employee)** | `employee@glow.com` | `password` | Portal presensi mobile (Clock In/Out), form izin/cuti, riwayat |

---

## 🚀 Panduan Instalasi & Menjalankan

### Opsi 1: Menjalankan dengan Docker (Rekomendasi)

1. **Clone repository & masuk ke direktori**:
   ```bash
   git clone https://github.com/bagasriatmaputera/glowAttendApp.git
   cd glowAttendApp
   ```

2. **Siapkan environment file**:
   ```bash
   cp .env.example .env
   ```

3. **Pastikan Docker network `shared-db` tersedia** (atau sesuaikan dengan konfigurasi database Anda):
   ```bash
   docker network create shared-db || true
   ```

4. **Build dan jalankan container**:
   ```bash
   docker compose up -d --build
   ```

5. **Jalankan migrasi database dan seeder**:
   ```bash
   docker compose exec app php artisan key:generate
   docker compose exec app php artisan migrate --seed
   ```
   Aplikasi siap diakses pada: `http://localhost:3002`

---

### Opsi 2: Menjalankan secara Lokal

1. **Install dependensi PHP & Node.js**:
   ```bash
   composer install
   npm install
   ```

2. **Konfigurasi Environment & Key**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Migrasi Database & Seeder**:
   ```bash
   php artisan migrate --seed
   ```

4. **Jalankan Aplikasi**:
   ```bash
   # Terminal 1: Backend Server
   php artisan serve

   # Terminal 2: Vite Dev Server
   npm run dev
   ```

---

## 📂 Struktur Direktori Utama

```
glowAttendApp/
├── app/
│   ├── Livewire/              # Komponen Livewire & Volt (Halaman & Modul)
│   ├── Models/                # Eloquent Models (User, Employee, Attendance, dll.)
│   └── Providers/             # Service Providers
├── database/
│   ├── migrations/            # Skema Database
│   └── seeders/               # Database & Role Seeders
├── docker/                    # Konfigurasi Nginx & Entrypoint script
├── resources/
│   ├── views/                 # Blade Layouts, Pages, & Components
│   └── css/ & js/             # Asset Tailwind & JavaScript
├── routes/
│   └── web.php & auth.php     # Routing Aplikasi & Autentikasi
├── Dockerfile                 # Multi-stage Dockerfile
└── docker-compose.yml         # Konfigurasi Container & Network
```

---

## 📄 Lisensi

Proyek ini dikembangkan di bawah lisensi [MIT License](LICENSE).
