<?php

namespace Database\Seeders;

use App\Models\Proyek;
use App\Models\Kriteria;
use App\Models\Alternatif;
use App\Models\Penilaian;
use Illuminate\Database\Seeder;

class SpkSeeder extends Seeder
{
    /**
     * Seed data sampel SPK Weighted Product
     * 2 Proyek: Penerimaan Beasiswa Prestasi & Bantuan Sosial Staff
     */
    public function run(): void
    {
        // ============================================
        // PROYEK 1: PENERIMAAN BEASISWA PRESTASI
        // ============================================
        $proyek1 = Proyek::create([
            'nama' => 'Penerimaan Beasiswa Prestasi',
            'deskripsi' => 'SPK penentuan penerima beasiswa prestasi bagi siswa berprestasi',
            'icon' => 'bi-mortarboard',
            'warna' => '#4f46e5',
        ]);

        $kriteriaBeasiswa = [
            ['kode' => 'C1', 'nama' => 'Nilai Akademik', 'bobot' => 9, 'jenis' => 'benefit', 'keterangan' => 'Nilai rata-rata rapor / IPK (semakin tinggi semakin baik)', 'proyek_id' => $proyek1->id],
            ['kode' => 'C2', 'nama' => 'Penghasilan Orang Tua', 'bobot' => 6, 'jenis' => 'cost', 'keterangan' => 'Tingkat penghasilan orang tua (semakin rendah semakin layak)', 'proyek_id' => $proyek1->id],
            ['kode' => 'C3', 'nama' => 'Prestasi Non-Akademik', 'bobot' => 6, 'jenis' => 'benefit', 'keterangan' => 'Prestasi di bidang olahraga, seni, organisasi, dll', 'proyek_id' => $proyek1->id],
            ['kode' => 'C4', 'nama' => 'Kehadiran', 'bobot' => 4.5, 'jenis' => 'benefit', 'keterangan' => 'Tingkat kehadiran/absensi', 'proyek_id' => $proyek1->id],
            ['kode' => 'C5', 'nama' => 'Perilaku', 'bobot' => 4.5, 'jenis' => 'benefit', 'keterangan' => 'Sikap dan perilaku sehari-hari', 'proyek_id' => $proyek1->id],
        ];

        $k1 = [];
        foreach ($kriteriaBeasiswa as $data) {
            $k1[] = Kriteria::create($data);
        }

        $altBeasiswa = [
            ['kode' => 'A1', 'nama' => 'Andi Pratama', 'keterangan' => 'Siswa Kelas XII IPA 1', 'proyek_id' => $proyek1->id],
            ['kode' => 'A2', 'nama' => 'Budi Setiawan', 'keterangan' => 'Siswa Kelas XII IPA 2', 'proyek_id' => $proyek1->id],
            ['kode' => 'A3', 'nama' => 'Citra Dewi', 'keterangan' => 'Siswa Kelas XII IPS 1', 'proyek_id' => $proyek1->id],
            ['kode' => 'A4', 'nama' => 'Dina Maharani', 'keterangan' => 'Siswa Kelas XII IPA 1', 'proyek_id' => $proyek1->id],
            ['kode' => 'A5', 'nama' => 'Eko Saputra', 'keterangan' => 'Siswa Kelas XII IPS 2', 'proyek_id' => $proyek1->id],
        ];

        $a1 = [];
        foreach ($altBeasiswa as $data) {
            $a1[] = Alternatif::create($data);
        }

        // Penilaian Beasiswa: C1  C2  C3  C4  C5
        $nilaiBeasiswa = [
            [0, 0, 4], [0, 1, 2], [0, 2, 3], [0, 3, 5], [0, 4, 4],
            [1, 0, 3], [1, 1, 4], [1, 2, 2], [1, 3, 4], [1, 4, 3],
            [2, 0, 5], [2, 1, 3], [2, 2, 4], [2, 3, 4], [2, 4, 5],
            [3, 0, 4], [3, 1, 1], [3, 2, 5], [3, 3, 5], [3, 4, 4],
            [4, 0, 3], [4, 1, 3], [4, 2, 3], [4, 3, 3], [4, 4, 3],
        ];

        foreach ($nilaiBeasiswa as $item) {
            Penilaian::create([
                'alternatif_id' => $a1[$item[0]]->id,
                'kriteria_id' => $k1[$item[1]]->id,
                'nilai' => $item[2],
            ]);
        }

        // ============================================
        // PROYEK 2: BANTUAN SOSIAL STAFF
        // ============================================
        $proyek2 = Proyek::create([
            'nama' => 'Bantuan Sosial Staff',
            'deskripsi' => 'SPK penentuan prioritas penerima bantuan sosial untuk staff',
            'icon' => 'bi-people',
            'warna' => '#059669',
        ]);

        $kriteriaBansos = [
            ['kode' => 'C1', 'nama' => 'Masa Kerja', 'bobot' => 8, 'jenis' => 'benefit', 'keterangan' => 'Lama bekerja (semakin lama semakin prioritas)', 'proyek_id' => $proyek2->id],
            ['kode' => 'C2', 'nama' => 'Gaji Pokok', 'bobot' => 7, 'jenis' => 'cost', 'keterangan' => 'Besaran gaji (semakin rendah semakin perlu bantuan)', 'proyek_id' => $proyek2->id],
            ['kode' => 'C3', 'nama' => 'Jumlah Tanggungan', 'bobot' => 7, 'jenis' => 'benefit', 'keterangan' => 'Jumlah anggota keluarga yang ditanggung', 'proyek_id' => $proyek2->id],
            ['kode' => 'C4', 'nama' => 'Kinerja', 'bobot' => 5, 'jenis' => 'benefit', 'keterangan' => 'Nilai kinerja tahunan', 'proyek_id' => $proyek2->id],
            ['kode' => 'C5', 'nama' => 'Status Rumah', 'bobot' => 3, 'jenis' => 'cost', 'keterangan' => 'Status kepemilikan rumah (semakin rendah = kontrak/menumpang)', 'proyek_id' => $proyek2->id],
        ];

        $k2 = [];
        foreach ($kriteriaBansos as $data) {
            $k2[] = Kriteria::create($data);
        }

        $altBansos = [
            ['kode' => 'A1', 'nama' => 'Siti Rahayu', 'keterangan' => 'Staff Administrasi', 'proyek_id' => $proyek2->id],
            ['kode' => 'A2', 'nama' => 'Hadi Santoso', 'keterangan' => 'Staff Kebersihan', 'proyek_id' => $proyek2->id],
            ['kode' => 'A3', 'nama' => 'Rina Wulandari', 'keterangan' => 'Staff Perpustakaan', 'proyek_id' => $proyek2->id],
            ['kode' => 'A4', 'nama' => 'Joko Widodo', 'keterangan' => 'Staff Keamanan', 'proyek_id' => $proyek2->id],
            ['kode' => 'A5', 'nama' => 'Dewi Lestari', 'keterangan' => 'Staff Tata Usaha', 'proyek_id' => $proyek2->id],
        ];

        $a2 = [];
        foreach ($altBansos as $data) {
            $a2[] = Alternatif::create($data);
        }

        // Penilaian Bansos: C1  C2  C3  C4  C5
        $nilaiBansos = [
            [0, 0, 4], [0, 1, 2], [0, 2, 4], [0, 3, 4], [0, 4, 1],
            [1, 0, 3], [1, 1, 1], [1, 2, 5], [1, 3, 3], [1, 4, 2],
            [2, 0, 5], [2, 1, 3], [2, 2, 3], [2, 3, 5], [2, 4, 3],
            [3, 0, 2], [3, 1, 2], [3, 2, 4], [3, 3, 3], [3, 4, 1],
            [4, 0, 4], [4, 1, 4], [4, 2, 2], [4, 3, 4], [4, 4, 4],
        ];

        foreach ($nilaiBansos as $item) {
            Penilaian::create([
                'alternatif_id' => $a2[$item[0]]->id,
                'kriteria_id' => $k2[$item[1]]->id,
                'nilai' => $item[2],
            ]);
        }
    }
}
