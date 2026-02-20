# SPK Beasiswa Prestasi — Metode Weighted Product (WP) & Simple Additive Weighting (SAW)

Sistem Pendukung Keputusan (SPK) berbasis web untuk **Penerimaan Beasiswa Prestasi** menggunakan dua metode Multi-Criteria Decision Making (MCDM): **Weighted Product (WP)** dan **Simple Additive Weighting (SAW)**. Dibangun dengan **Laravel 12**, **Bootstrap 5**, dan **PhpSpreadsheet / PhpWord**.

## Kelompok 3

| No | Nama |
|----|------|
| 1 | Aurelia Vionana Michelle Kurniawan |
| 2 | Rizki Habibi |
| 3 | Firda Eka Agustin |
| 4 | Moh Diky Adhiansyah |

---

## Fitur Aplikasi

### 1. Manajemen Proyek (Multi-Proyek)
- Membuat, mengedit, dan menghapus proyek SPK secara independen
- Setiap proyek memiliki data kriteria, alternatif, dan penilaian masing-masing
- Pilihan ikon dan warna kustom per proyek
- Tombol **Aktifkan** untuk beralih antar proyek
- Tampilan kartu proyek dengan indikator proyek aktif
- Tombol **Kembali ke Proyek** di top bar setiap halaman

### 2. Dashboard
- Kartu statistik: Total Kriteria, Total Alternatif, Total Penilaian
- Tabel ranking WP dengan progress bar dan ikon trofi
- Tabel ranking SAW dengan progress bar dan ikon trofi
- Penjelasan singkat rumus metode WP & SAW
- Tombol aksi cepat ke halaman Kriteria, Alternatif, Penilaian, dan Perbandingan

### 3. Data Kriteria (CRUD)
- Tambah, edit, dan hapus kriteria melalui **modal dialog**
- Field: Kode, Nama, Bobot, Jenis (Benefit/Cost), Keterangan
- Kalkulasi otomatis bobot ternormalisasi
- **Import Excel** (xlsx, xls, csv) melalui modal upload
- **Export Excel** (xlsx) data kriteria
- Hapus cascade (menghapus penilaian terkait)

### 4. Data Alternatif (CRUD)
- Tambah, edit, dan hapus alternatif melalui **modal dialog**
- Field: Kode, Nama, Keterangan
- **Import Excel** (xlsx, xls, csv) melalui modal upload
- **Export Excel** (xlsx) data alternatif
- Hapus cascade (menghapus penilaian terkait)

### 5. Penilaian (Matriks Input)
- Input matriks penilaian (baris = alternatif, kolom = kriteria)
- Skala penilaian 1–5 (Sangat Kurang s.d. Sangat Baik)
- Simpan massal semua nilai sekaligus
- Hapus nilai per alternatif
- Logika `updateOrCreate` — data lama diperbarui, data baru ditambahkan

### 6. Perhitungan Weighted Product (WP)
- Langkah-langkah perhitungan ditampilkan secara detail:
  1. Matriks keputusan (nilai asli)
  2. Normalisasi bobot (Wj = Wj / ΣWj), tampilkan tanda pangkat benefit/cost
  3. Vektor S — kartu detail per alternatif dengan rumus X^W tiap kriteria
  4. Vektor V — preferensi relatif (Si / ΣS)
  5. Ranking akhir dengan trofi, persentase, dan **kesimpulan otomatis**
- Export Excel dan Cetak

### 7. Perhitungan Simple Additive Weighting (SAW)
- Langkah-langkah perhitungan ditampilkan secara detail:
  1. Matriks keputusan
  2. Normalisasi bobot + Max/Min per kriteria
  3. Matriks ternormalisasi (Rij) dengan rumus per sel
  4. Nilai preferensi (Vi = Σ Wj × Rij) dengan kartu detail per alternatif
  5. Ranking akhir dengan kesimpulan
- Export Excel dan Cetak

### 8. Perbandingan WP & SAW
- Tabel ranking berdampingan (WP kiri, SAW kanan)
- Tabel gabungan: Rank WP, Nilai WP, Rank SAW, Nilai SAW, Selisih Rank, Status Konsistensi
- Analisis konsistensi otomatis (berapa persen rank sama)
- Kartu kesimpulan: Terbaik WP, Terbaik SAW, Persentase Konsistensi
- Penjelasan perbandingan metode (perkalian vs penjumlahan)

### 9. Laporan Lengkap
- Seluruh alur perhitungan dari kedua metode dalam satu halaman
- Bagian: Header Proyek → Skala Penilaian → Data Kriteria → Data Alternatif → Matriks Penilaian → WP (Normalisasi Bobot, Vektor S, Vektor V & Ranking) → SAW (Normalisasi Matriks, Preferensi & Ranking) → Kesimpulan
- **Export Word** (.docx) — dokumen lengkap dengan format profesional (Times New Roman)
- **Export Excel** (.xlsx) — multi-sheet dengan rumus otomatis
- **Cetak** langsung dari browser

### 10. Export & Import

| Fitur | Format | Keterangan |
|-------|--------|------------|
| Export Kriteria | Excel (.xlsx) | Data kriteria satu sheet |
| Export Alternatif | Excel (.xlsx) | Data alternatif satu sheet |
| Export Hasil WP | Excel (.xlsx) | Hasil perhitungan WP |
| Export All-in-One | Excel (.xlsx) | Multi-sheet: WP & SAW dengan rumus |
| Export Word | Word (.docx) | Laporan lengkap dengan tabel & format profesional |
| Import Kriteria | Excel (.xlsx, .xls, .csv) | Upload maks 2MB, kolom: kode, nama, bobot, jenis, keterangan |
| Import Alternatif | Excel (.xlsx, .xls, .csv) | Upload maks 2MB, kolom: kode, nama, keterangan |

### 11. UI/UX & Pengaturan Tampilan
- **Tema:** Light / Dark / Auto (ikut sistem)
- **7 Warna Aksen:** Indigo, Blue, Emerald, Rose, Amber, Violet, Cyan
- **Ukuran Font:** Small / Medium / Large
- **Kepadatan Layout:** Compact / Comfortable
- **Fitur Dark Mode Eksklusif:** Glassmorphism, Neon Glow, Partikel Background
- **Fitur Umum:** Focus Mode (sembunyikan sidebar), Toggle Animasi, Sticky Top Bar
- **Pintasan Keyboard:** Ctrl+, (pengaturan), Ctrl+F1 (focus mode)
- **Responsif:** Sidebar toggle untuk mobile
- **Panduan Kontekstual:** Modal bantuan per halaman
- **Jam Live** di top bar
- Tombol **Kembali ke Proyek** di setiap halaman (kecuali halaman Proyek)

---

## Metode yang Digunakan

### Weighted Product (WP)

Metode WP menggunakan **perkalian** untuk menggabungkan nilai setiap kriteria yang sudah dipangkatkan dengan bobotnya.

**Langkah-langkah:**
1. **Normalisasi Bobot:** Wj = Wj / ΣWj
2. **Tentukan Jenis Kriteria:** Benefit (pangkat positif) / Cost (pangkat negatif)
3. **Hitung Vektor S:** Si = Π (Xij ^ Wj)
4. **Hitung Vektor V:** Vi = Si / ΣSi — nilai terbesar = alternatif terbaik

### Simple Additive Weighting (SAW)

Metode SAW menggunakan **penjumlahan terbobot** dari rating kinerja pada setiap alternatif.

**Langkah-langkah:**
1. **Normalisasi Bobot:** Wj = Wj / ΣWj
2. **Normalisasi Matriks:** Benefit: Rij = Xij / Max(Xij) | Cost: Rij = Min(Xij) / Xij
3. **Hitung Preferensi:** Vi = Σ (Wj × Rij) — nilai terbesar = alternatif terbaik

---

## Tech Stack

- **Backend:** Laravel 12 (PHP 8.2+)
- **Frontend:** Bootstrap 5.3, Bootstrap Icons, Google Fonts (Inter, JetBrains Mono)
- **Database:** MySQL / MariaDB
- **Export Excel:** Maatwebsite Excel (PhpSpreadsheet)
- **Export Word:** PhpOffice PhpWord
- **Build Tool:** Vite

## Instalasi

```bash
# Clone repository
git clone <repo-url>
cd spk-wp

# Install dependensi
composer install
npm install

# Konfigurasi
cp .env.example .env
php artisan key:generate

# Setup database
php artisan migrate
php artisan db:seed

# Jalankan
php artisan serve
npm run dev
```

## Lisensi

Proyek ini dibuat untuk keperluan akademis oleh **Kelompok 3**.
