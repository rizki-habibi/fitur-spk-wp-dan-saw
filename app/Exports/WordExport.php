<?php

namespace App\Exports;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\SimpleType\TblWidth;
use PhpOffice\PhpWord\Style\Font;

class WordExport
{
    protected $hasilWP;
    protected $hasilSAW;
    protected $proyekNama;
    protected $proyekDeskripsi;

    // Styles
    protected $titleFont   = ['bold' => true, 'size' => 16, 'name' => 'Times New Roman'];
    protected $subtitleFont = ['bold' => true, 'size' => 13, 'name' => 'Times New Roman'];
    protected $h3Font       = ['bold' => true, 'size' => 12, 'name' => 'Times New Roman'];
    protected $normalFont   = ['size' => 12, 'name' => 'Times New Roman'];
    protected $smallFont    = ['size' => 11, 'name' => 'Times New Roman'];
    protected $boldFont     = ['bold' => true, 'size' => 12, 'name' => 'Times New Roman'];
    protected $italicFont   = ['italic' => true, 'size' => 12, 'name' => 'Times New Roman'];
    protected $thFont       = ['bold' => true, 'size' => 11, 'name' => 'Times New Roman', 'color' => 'FFFFFF'];
    protected $tdFont       = ['size' => 11, 'name' => 'Times New Roman'];
    protected $rankFont     = ['bold' => true, 'size' => 11, 'name' => 'Times New Roman', 'color' => '1e3a5f'];

    protected $parCenter    = ['alignment' => Jc::CENTER, 'spaceAfter' => 0];
    protected $parLeft      = ['alignment' => Jc::START, 'spaceAfter' => 60, 'spaceBefore' => 0];
    protected $parJustify   = ['alignment' => Jc::BOTH, 'spaceAfter' => 120, 'spaceBefore' => 0, 'lineHeight' => 1.5];

    // Color palette
    protected $wpColor  = '2563eb'; // blue
    protected $wpBg     = 'dbeafe';
    protected $sawColor = '16a34a'; // green
    protected $sawBg    = 'dcfce7';
    protected $thBg     = '1e3a5f';
    protected $thBgSaw  = '14532d';
    protected $altBg    = 'f8fafc';

    public function __construct(array $hasilWP, array $hasilSAW, string $proyekNama, string $proyekDeskripsi = '')
    {
        $this->hasilWP = $hasilWP;
        $this->hasilSAW = $hasilSAW;
        $this->proyekNama = $proyekNama;
        $this->proyekDeskripsi = $proyekDeskripsi;
    }

    public function generate(): string
    {
        $phpWord = new PhpWord();
        $phpWord->getSettings()->setUpdateFields(true);

        // Default font
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(12);

        $section = $phpWord->addSection([
            'marginTop'    => 1134, // ~2cm
            'marginBottom' => 1134,
            'marginLeft'   => 1701, // ~3cm
            'marginRight'  => 1134,
        ]);

        $this->writeHeader($section);
        $this->writePendahuluan($section);
        $this->writeDataKriteria($section);
        $this->writeDataAlternatif($section);
        $this->writeMatriksPenilaian($section);

        // WP Section
        $this->writeWPTeori($section);
        $this->writeWPLangkah1($section);
        $this->writeWPLangkah2($section);
        $this->writeWPLangkah3($section);
        $this->writeWPRanking($section);

        // SAW Section
        $this->writeSAWTeori($section);
        $this->writeSAWLangkah1($section);
        $this->writeSAWLangkah2($section);
        $this->writeSAWLangkah3($section);
        $this->writeSAWRanking($section);

        // Perbandingan & Kesimpulan
        $this->writePerbandingan($section);
        $this->writeKesimpulan($section);

        // Save to temp
        $tmpFile = tempnam(sys_get_temp_dir(), 'spk_') . '.docx';
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($tmpFile);

        return $tmpFile;
    }

    // ======================================================================
    // HEADER
    // ======================================================================
    protected function writeHeader($section)
    {
        $section->addText('LAPORAN SISTEM PENDUKUNG KEPUTUSAN', $this->titleFont, $this->parCenter);
        $section->addText($this->proyekNama, ['bold' => true, 'size' => 14, 'name' => 'Times New Roman'], $this->parCenter);
        $section->addText('Metode: Weighted Product (WP) & Simple Additive Weighting (SAW)', $this->smallFont, $this->parCenter);
        $section->addText('Tanggal: ' . now()->format('d F Y'), $this->smallFont, $this->parCenter);
        $section->addTextBreak(1);

        // Separator line
        $section->addText('', [], ['borderBottomSize' => 12, 'borderBottomColor' => '1e3a5f', 'spaceAfter' => 200]);
    }

    // ======================================================================
    // PENDAHULUAN
    // ======================================================================
    protected function writePendahuluan($section)
    {
        $section->addText('1. PENDAHULUAN', $this->subtitleFont, $this->parLeft);

        $desc = $this->proyekDeskripsi ?: $this->proyekNama;
        $section->addText(
            "Laporan ini menyajikan hasil analisis Sistem Pendukung Keputusan (SPK) untuk \"{$desc}\" menggunakan dua metode, yaitu Weighted Product (WP) dan Simple Additive Weighting (SAW). Kedua metode ini digunakan untuk memberikan perankingan alternatif berdasarkan kriteria yang telah ditentukan.",
            $this->normalFont, $this->parJustify
        );

        $section->addText(
            'Penggunaan dua metode sekaligus bertujuan untuk memberikan hasil yang lebih objektif dan dapat saling memvalidasi. Hasil akhir dari kedua metode akan dibandingkan untuk melihat konsistensi perankingan.',
            $this->normalFont, $this->parJustify
        );
        $section->addTextBreak(1);
    }

    // ======================================================================
    // DATA KRITERIA
    // ======================================================================
    protected function writeDataKriteria($section)
    {
        $section->addText('2. DATA KRITERIA', $this->subtitleFont, $this->parLeft);
        $section->addText('Berikut adalah kriteria yang digunakan dalam proses pengambilan keputusan:', $this->normalFont, $this->parJustify);

        $kriteria = $this->hasilWP['kriteria'];
        $tw = 9000; // total width in twips (~16cm)

        $table = $section->addTable([
            'borderSize' => 6,
            'borderColor' => 'cccccc',
            'cellMargin' => 60,
            'alignment' => Jc::CENTER,
        ]);

        // Header
        $table->addRow();
        $this->addThCell($table, 'No', 600);
        $this->addThCell($table, 'Kode', 1000);
        $this->addThCell($table, 'Nama Kriteria', 3400);
        $this->addThCell($table, 'Bobot', 1000);
        $this->addThCell($table, 'Jenis', 1200);
        $this->addThCell($table, 'Bobot Normal', 1400);

        $totalBobot = $this->hasilWP['total_bobot'];
        foreach ($kriteria as $i => $k) {
            $bg = ($i % 2 === 0) ? 'FFFFFF' : $this->altBg;
            $table->addRow();
            $this->addTdCell($table, $i + 1, 600, 'center', $bg);
            $this->addTdCell($table, $k->kode, 1000, 'center', $bg);
            $this->addTdCell($table, $k->nama, 3400, 'left', $bg);
            $this->addTdCell($table, $k->bobot, 1000, 'center', $bg);
            $this->addTdCell($table, ucfirst($k->jenis), 1200, 'center', $bg);
            $wn = $totalBobot > 0 ? round($k->bobot / $totalBobot, 4) : 0;
            $this->addTdCell($table, number_format($wn, 4), 1400, 'center', $bg);
        }

        // Total row
        $table->addRow();
        $cell = $table->addCell(5000, ['gridSpan' => 3, 'bgColor' => 'f1f5f9']);
        $cell->addText('Total', $this->boldFont, ['alignment' => Jc::END, 'spaceAfter' => 0]);
        $this->addTdCell($table, $totalBobot, 1000, 'center', 'f1f5f9', true);
        $this->addTdCell($table, '', 1200, 'center', 'f1f5f9');
        $this->addTdCell($table, '1.0000', 1400, 'center', 'f1f5f9', true);

        $section->addTextBreak(1);
        $section->addText('Keterangan Jenis: Benefit = semakin tinggi semakin baik, Cost = semakin rendah semakin baik.', $this->italicFont, $this->parJustify);
        $section->addTextBreak(1);
    }

    // ======================================================================
    // DATA ALTERNATIF
    // ======================================================================
    protected function writeDataAlternatif($section)
    {
        $section->addText('3. DATA ALTERNATIF', $this->subtitleFont, $this->parLeft);
        $section->addText('Berikut adalah daftar alternatif yang akan dievaluasi:', $this->normalFont, $this->parJustify);

        $alternatif = $this->hasilWP['alternatif'];
        $table = $section->addTable([
            'borderSize' => 6,
            'borderColor' => 'cccccc',
            'cellMargin' => 60,
            'alignment' => Jc::CENTER,
        ]);

        $table->addRow();
        $this->addThCell($table, 'No', 800);
        $this->addThCell($table, 'Kode', 1600);
        $this->addThCell($table, 'Nama Alternatif', 5600);

        foreach ($alternatif as $i => $a) {
            $bg = ($i % 2 === 0) ? 'FFFFFF' : $this->altBg;
            $table->addRow();
            $this->addTdCell($table, $i + 1, 800, 'center', $bg);
            $this->addTdCell($table, $a->kode, 1600, 'center', $bg);
            $this->addTdCell($table, $a->nama, 5600, 'left', $bg);
        }

        $section->addTextBreak(1);
    }

    // ======================================================================
    // MATRIKS PENILAIAN
    // ======================================================================
    protected function writeMatriksPenilaian($section)
    {
        $section->addText('4. MATRIKS PENILAIAN', $this->subtitleFont, $this->parLeft);
        $section->addText('Matriks penilaian menunjukkan nilai setiap alternatif terhadap masing-masing kriteria berdasarkan data yang telah dikumpulkan:', $this->normalFont, $this->parJustify);

        $kriteria = $this->hasilWP['kriteria'];
        $alternatif = $this->hasilWP['alternatif'];
        $matriks = $this->hasilWP['matriks'];

        $kCount = $kriteria->count();
        $kodeW = 1000;
        $critW = max(800, intval((9000 - $kodeW) / max($kCount, 1)));

        $table = $section->addTable([
            'borderSize' => 6,
            'borderColor' => 'cccccc',
            'cellMargin' => 60,
            'alignment' => Jc::CENTER,
        ]);

        // Header
        $table->addRow();
        $this->addThCell($table, 'Alternatif', $kodeW);
        foreach ($kriteria as $k) {
            $this->addThCell($table, $k->kode, $critW);
        }

        foreach ($alternatif as $i => $a) {
            $bg = ($i % 2 === 0) ? 'FFFFFF' : $this->altBg;
            $table->addRow();
            $this->addTdCell($table, $a->kode . ' - ' . $a->nama, $kodeW, 'left', $bg, true);
            foreach ($kriteria as $k) {
                $val = $matriks[$a->id][$k->id] ?? 0;
                $this->addTdCell($table, $val, $critW, 'center', $bg);
            }
        }

        $section->addTextBreak(1);
    }

    // ======================================================================
    // WP TEORI
    // ======================================================================
    protected function writeWPTeori($section)
    {
        $section->addText('', [], ['borderBottomSize' => 8, 'borderBottomColor' => $this->wpColor, 'spaceAfter' => 100]);
        $section->addText('5. METODE WEIGHTED PRODUCT (WP)', ['bold' => true, 'size' => 14, 'name' => 'Times New Roman', 'color' => $this->wpColor], $this->parLeft);

        $section->addText('5.1 Pengertian', $this->h3Font, $this->parLeft);
        $section->addText(
            'Metode Weighted Product (WP) adalah salah satu metode penyelesaian masalah Multi Attribute Decision Making (MADM). Metode ini menggunakan perkalian untuk menghubungkan rating atribut, dimana rating setiap atribut harus dipangkatkan dengan bobot atribut yang bersangkutan.',
            $this->normalFont, $this->parJustify
        );

        $section->addText('5.2 Langkah-langkah Metode WP:', $this->h3Font, $this->parLeft);

        $steps = [
            'Menentukan kriteria dan bobot yang akan digunakan.',
            'Normalisasi bobot: Wj = Wj / ΣWj sehingga total bobot = 1.',
            'Menghitung Vektor S: Si = Π(Xij^Wj), dimana pangkat positif (+Wj) untuk kriteria benefit dan pangkat negatif (-Wj) untuk kriteria cost.',
            'Menghitung Vektor V (preferensi relatif): Vi = Si / ΣSi.',
            'Melakukan perankingan berdasarkan nilai Vektor V terbesar.',
        ];
        foreach ($steps as $i => $step) {
            $section->addListItem('Langkah ' . ($i + 1) . ': ' . $step, 0, $this->normalFont, ['listType' => \PhpOffice\PhpWord\Style\ListItem::TYPE_NUMBER], $this->parJustify);
        }

        $section->addTextBreak(1);
        $section->addText('5.3 Kelebihan Metode WP:', $this->h3Font, $this->parLeft);
        $pros = [
            'Proses perhitungan lebih sederhana karena menggunakan perkalian.',
            'Dapat menangani kriteria benefit dan cost secara langsung melalui tanda pangkat.',
            'Tidak memerlukan proses normalisasi matriks keputusan.',
        ];
        foreach ($pros as $p) {
            $section->addListItem($p, 0, $this->normalFont, ['listType' => \PhpOffice\PhpWord\Style\ListItem::TYPE_BULLET_FILLED]);
        }

        $section->addText('5.4 Kekurangan Metode WP:', $this->h3Font, $this->parLeft);
        $cons = [
            'Sensitif terhadap nilai nol pada data penilaian.',
            'Hasil perhitungan bisa menjadi sangat kecil atau sangat besar karena operasi perpangkatan.',
            'Kurang cocok untuk data dengan rentang nilai yang sangat besar.',
        ];
        foreach ($cons as $c) {
            $section->addListItem($c, 0, $this->normalFont, ['listType' => \PhpOffice\PhpWord\Style\ListItem::TYPE_BULLET_FILLED]);
        }

        $section->addTextBreak(1);
    }

    // ======================================================================
    // WP LANGKAH 1 - Normalisasi Bobot
    // ======================================================================
    protected function writeWPLangkah1($section)
    {
        $section->addText('5.5 Perhitungan WP', ['bold' => true, 'size' => 13, 'name' => 'Times New Roman', 'color' => $this->wpColor], $this->parLeft);
        $section->addText('Langkah 1: Normalisasi Bobot', $this->h3Font, $this->parLeft);
        $section->addText('Rumus: Wj = Wj / ΣWj', $this->boldFont, ['alignment' => Jc::CENTER, 'spaceAfter' => 120]);

        $kriteria = $this->hasilWP['kriteria'];
        $totalBobot = $this->hasilWP['total_bobot'];
        $bobotNormal = $this->hasilWP['bobot_normal'];

        $table = $section->addTable([
            'borderSize' => 6,
            'borderColor' => 'cccccc',
            'cellMargin' => 60,
            'alignment' => Jc::CENTER,
        ]);

        $table->addRow();
        $this->addThCell($table, 'Kriteria', 2000, $this->thBg);
        $this->addThCell($table, 'Bobot (Wj)', 1500, $this->thBg);
        $this->addThCell($table, 'Perhitungan', 2500, $this->thBg);
        $this->addThCell($table, 'Wj Normal', 1500, $this->thBg);

        foreach ($kriteria as $i => $k) {
            $bg = ($i % 2 === 0) ? 'FFFFFF' : $this->altBg;
            $wn = $bobotNormal[$k->id] ?? 0;
            $table->addRow();
            $this->addTdCell($table, $k->kode . ' - ' . $k->nama, 2000, 'left', $bg);
            $this->addTdCell($table, $k->bobot, 1500, 'center', $bg);
            $this->addTdCell($table, $k->bobot . ' / ' . $totalBobot, 2500, 'center', $bg);
            $this->addTdCell($table, number_format($wn, 4), 1500, 'center', $bg, true);
        }

        $section->addTextBreak(1);
    }

    // ======================================================================
    // WP LANGKAH 2 - Vektor S
    // ======================================================================
    protected function writeWPLangkah2($section)
    {
        $section->addText('Langkah 2: Hitung Vektor S', $this->h3Font, $this->parLeft);
        $section->addText('Rumus: Si = Π(Xij ^ Wj), pangkat (+) untuk Benefit, (-) untuk Cost', $this->boldFont, ['alignment' => Jc::CENTER, 'spaceAfter' => 120]);

        $kriteria = $this->hasilWP['kriteria'];
        $alternatif = $this->hasilWP['alternatif'];
        $detail = $this->hasilWP['detail_perhitungan'];
        $vektorS = $this->hasilWP['vektor_s'];

        // Show calculation detail for each alternatif
        foreach ($alternatif as $a) {
            $det = $detail[$a->id] ?? [];
            $parts = [];
            foreach ($det as $d) {
                $parts[] = "({$d['nilai']}^{$d['pangkat']})";
            }
            $formula = implode(' × ', $parts);
            $s = $vektorS[$a->id] ?? 0;

            $section->addText(
                "S({$a->kode}) = {$formula} = " . number_format($s, 6),
                $this->smallFont, $this->parLeft
            );
        }

        // Vektor S Table
        $section->addTextBreak(0);
        $section->addText('Hasil Vektor S:', $this->boldFont, $this->parLeft);

        $table = $section->addTable([
            'borderSize' => 6,
            'borderColor' => 'cccccc',
            'cellMargin' => 60,
            'alignment' => Jc::CENTER,
        ]);

        $table->addRow();
        $this->addThCell($table, 'Alternatif', 4000, $this->thBg);
        $this->addThCell($table, 'Vektor S', 3000, $this->thBg);

        foreach ($alternatif as $i => $a) {
            $bg = ($i % 2 === 0) ? 'FFFFFF' : $this->altBg;
            $table->addRow();
            $this->addTdCell($table, $a->kode . ' - ' . $a->nama, 4000, 'left', $bg);
            $this->addTdCell($table, number_format($vektorS[$a->id] ?? 0, 6), 3000, 'center', $bg, true);
        }

        // Total S
        $totalS = $this->hasilWP['total_s'];
        $table->addRow();
        $this->addTdCell($table, 'Total ΣS', 4000, 'right', 'f1f5f9', true);
        $this->addTdCell($table, number_format($totalS, 6), 3000, 'center', 'f1f5f9', true);

        $section->addTextBreak(1);
    }

    // ======================================================================
    // WP LANGKAH 3 - Vektor V
    // ======================================================================
    protected function writeWPLangkah3($section)
    {
        $section->addText('Langkah 3: Hitung Vektor V (Preferensi Relatif)', $this->h3Font, $this->parLeft);
        $section->addText('Rumus: Vi = Si / ΣSi', $this->boldFont, ['alignment' => Jc::CENTER, 'spaceAfter' => 120]);

        $alternatif = $this->hasilWP['alternatif'];
        $vektorS = $this->hasilWP['vektor_s'];
        $vektorV = $this->hasilWP['vektor_v'];
        $totalS = $this->hasilWP['total_s'];

        foreach ($alternatif as $a) {
            $s = $vektorS[$a->id] ?? 0;
            $v = $vektorV[$a->id] ?? 0;
            $section->addText(
                "V({$a->kode}) = " . number_format($s, 6) . " / " . number_format($totalS, 6) . " = " . number_format($v, 6),
                $this->smallFont, $this->parLeft
            );
        }

        $section->addTextBreak(1);
    }

    // ======================================================================
    // WP RANKING
    // ======================================================================
    protected function writeWPRanking($section)
    {
        $section->addText('5.6 Hasil Ranking Metode WP', ['bold' => true, 'size' => 13, 'name' => 'Times New Roman', 'color' => $this->wpColor], $this->parLeft);

        $ranking = $this->hasilWP['ranking'];

        $table = $section->addTable([
            'borderSize' => 6,
            'borderColor' => 'cccccc',
            'cellMargin' => 60,
            'alignment' => Jc::CENTER,
        ]);

        $table->addRow();
        $this->addThCell($table, 'Rank', 800, $this->thBg);
        $this->addThCell($table, 'Kode', 1000, $this->thBg);
        $this->addThCell($table, 'Nama Alternatif', 3200, $this->thBg);
        $this->addThCell($table, 'Vektor S', 1500, $this->thBg);
        $this->addThCell($table, 'Vektor V', 1500, $this->thBg);
        $this->addThCell($table, '%', 1000, $this->thBg);

        foreach ($ranking as $i => $r) {
            $bg = ($i === 0) ? $this->wpBg : (($i % 2 === 0) ? 'FFFFFF' : $this->altBg);
            $font = ($i === 0) ? $this->rankFont : $this->tdFont;
            $table->addRow();
            $this->addTdCellFont($table, '#' . $r['rank'], 800, 'center', $bg, $font);
            $this->addTdCellFont($table, $r['kode'], 1000, 'center', $bg, $font);
            $this->addTdCellFont($table, $r['nama'], 3200, 'left', $bg, $font);
            $this->addTdCellFont($table, number_format($r['vektor_s'], 6), 1500, 'center', $bg, $font);
            $this->addTdCellFont($table, number_format($r['vektor_v'], 6), 1500, 'center', $bg, $font);
            $this->addTdCellFont($table, number_format($r['persentase'], 2) . '%', 1000, 'center', $bg, $font);
        }

        if (!empty($ranking)) {
            $best = $ranking[0];
            $section->addTextBreak(0);
            $section->addText(
                "Berdasarkan metode Weighted Product, alternatif terbaik adalah {$best['kode']} - {$best['nama']} dengan nilai Vektor V = " . number_format($best['vektor_v'], 6) . " ({$best['persentase']}%).",
                $this->boldFont, $this->parJustify
            );
        }

        $section->addTextBreak(1);
    }

    // ======================================================================
    // SAW TEORI
    // ======================================================================
    protected function writeSAWTeori($section)
    {
        $section->addText('', [], ['borderBottomSize' => 8, 'borderBottomColor' => $this->sawColor, 'spaceAfter' => 100]);
        $section->addText('6. METODE SIMPLE ADDITIVE WEIGHTING (SAW)', ['bold' => true, 'size' => 14, 'name' => 'Times New Roman', 'color' => $this->sawColor], $this->parLeft);

        $section->addText('6.1 Pengertian', $this->h3Font, $this->parLeft);
        $section->addText(
            'Metode Simple Additive Weighting (SAW) sering dikenal dengan metode penjumlahan terbobot. Konsep dasar metode SAW adalah mencari penjumlahan terbobot dari rating kinerja pada setiap alternatif pada semua atribut. Metode SAW membutuhkan proses normalisasi matriks keputusan (X) ke suatu skala yang dapat diperbandingkan dengan semua rating alternatif yang ada.',
            $this->normalFont, $this->parJustify
        );

        $section->addText('6.2 Langkah-langkah Metode SAW:', $this->h3Font, $this->parLeft);

        $steps = [
            'Menentukan kriteria dan bobot yang akan digunakan.',
            'Normalisasi bobot: Wj = Wj / ΣWj sehingga total bobot = 1.',
            'Normalisasi matriks keputusan: Benefit → Rij = Xij / Max(Xij), Cost → Rij = Min(Xij) / Xij.',
            'Menghitung nilai preferensi: Vi = Σ(Wj × Rij).',
            'Melakukan perankingan berdasarkan nilai preferensi (Vi) terbesar.',
        ];
        foreach ($steps as $i => $step) {
            $section->addListItem('Langkah ' . ($i + 1) . ': ' . $step, 0, $this->normalFont, ['listType' => \PhpOffice\PhpWord\Style\ListItem::TYPE_NUMBER], $this->parJustify);
        }

        $section->addTextBreak(1);
        $section->addText('6.3 Kelebihan Metode SAW:', $this->h3Font, $this->parLeft);
        $pros = [
            'Perhitungan relatif sederhana dan mudah dipahami.',
            'Dapat menangani kriteria benefit dan cost melalui rumus normalisasi.',
            'Hasil normalisasi mempermudah perbandingan antar alternatif.',
            'Cocok untuk masalah dengan banyak kriteria.',
        ];
        foreach ($pros as $p) {
            $section->addListItem($p, 0, $this->normalFont, ['listType' => \PhpOffice\PhpWord\Style\ListItem::TYPE_BULLET_FILLED]);
        }

        $section->addText('6.4 Kekurangan Metode SAW:', $this->h3Font, $this->parLeft);
        $cons = [
            'Memerlukan langkah normalisasi tambahan dibanding metode WP.',
            'Sensitif terhadap perubahan bobot kriteria.',
            'Tidak memperhitungkan interaksi antar kriteria.',
        ];
        foreach ($cons as $c) {
            $section->addListItem($c, 0, $this->normalFont, ['listType' => \PhpOffice\PhpWord\Style\ListItem::TYPE_BULLET_FILLED]);
        }

        $section->addTextBreak(1);
    }

    // ======================================================================
    // SAW LANGKAH 1 - Normalisasi Bobot (same as WP, brief)
    // ======================================================================
    protected function writeSAWLangkah1($section)
    {
        $section->addText('6.5 Perhitungan SAW', ['bold' => true, 'size' => 13, 'name' => 'Times New Roman', 'color' => $this->sawColor], $this->parLeft);
        $section->addText('Langkah 1: Normalisasi Bobot', $this->h3Font, $this->parLeft);
        $section->addText('Normalisasi bobot SAW sama dengan WP: Wj = Wj / ΣWj (lihat Tabel pada Langkah 1 WP).', $this->normalFont, $this->parJustify);
        $section->addTextBreak(1);
    }

    // ======================================================================
    // SAW LANGKAH 2 - Normalisasi Matriks
    // ======================================================================
    protected function writeSAWLangkah2($section)
    {
        $section->addText('Langkah 2: Normalisasi Matriks Keputusan', $this->h3Font, $this->parLeft);
        $section->addText('Benefit: Rij = Xij / Max(Xij)  |  Cost: Rij = Min(Xij) / Xij', $this->boldFont, ['alignment' => Jc::CENTER, 'spaceAfter' => 120]);

        $kriteria = $this->hasilSAW['kriteria'];
        $alternatif = $this->hasilSAW['alternatif'];
        $detailNorm = $this->hasilSAW['detail_normalisasi'];
        $maxK = $this->hasilSAW['max_kriteria'];
        $minK = $this->hasilSAW['min_kriteria'];

        // Show Max/Min
        $section->addText('Nilai Maximum dan Minimum per Kriteria:', $this->boldFont, $this->parLeft);

        $table = $section->addTable([
            'borderSize' => 6,
            'borderColor' => 'cccccc',
            'cellMargin' => 60,
            'alignment' => Jc::CENTER,
        ]);

        $kCount = $kriteria->count();
        $w = max(900, intval(7500 / max($kCount, 1)));

        $table->addRow();
        $this->addThCell($table, '', 1500, $this->thBgSaw);
        foreach ($kriteria as $k) {
            $this->addThCell($table, $k->kode, $w, $this->thBgSaw);
        }

        $table->addRow();
        $this->addTdCell($table, 'Max', 1500, 'center', $this->sawBg, true);
        foreach ($kriteria as $k) {
            $this->addTdCell($table, $maxK[$k->id] ?? 0, $w, 'center', $this->sawBg);
        }

        $table->addRow();
        $this->addTdCell($table, 'Min', 1500, 'center', 'FFFFFF', true);
        foreach ($kriteria as $k) {
            $this->addTdCell($table, $minK[$k->id] ?? 0, $w, 'center', 'FFFFFF');
        }

        $table->addRow();
        $this->addTdCell($table, 'Jenis', 1500, 'center', $this->sawBg, true);
        foreach ($kriteria as $k) {
            $this->addTdCell($table, ucfirst($k->jenis), $w, 'center', $this->sawBg);
        }

        $section->addTextBreak(1);

        // Detail normalisasi for each alt
        $section->addText('Detail Perhitungan Normalisasi:', $this->boldFont, $this->parLeft);

        foreach ($alternatif as $a) {
            $parts = [];
            foreach ($kriteria as $k) {
                $dn = $detailNorm[$a->id][$k->id] ?? null;
                if ($dn) {
                    $parts[] = "{$k->kode}: {$dn['rumus']} = " . number_format($dn['rij'], 4);
                }
            }
            $section->addText(
                "{$a->kode}: " . implode('  |  ', $parts),
                $this->smallFont, $this->parLeft
            );
        }

        // Normalized matrix table
        $section->addTextBreak(0);
        $section->addText('Matriks Ternormalisasi (Rij):', $this->boldFont, $this->parLeft);

        $table2 = $section->addTable([
            'borderSize' => 6,
            'borderColor' => 'cccccc',
            'cellMargin' => 60,
            'alignment' => Jc::CENTER,
        ]);

        $altW = 2000;
        $cW = max(800, intval(7000 / max($kCount, 1)));

        $table2->addRow();
        $this->addThCell($table2, 'Alternatif', $altW, $this->thBgSaw);
        foreach ($kriteria as $k) {
            $this->addThCell($table2, $k->kode, $cW, $this->thBgSaw);
        }

        $matriksNormal = $this->hasilSAW['matriks_normal'];
        foreach ($alternatif as $i => $a) {
            $bg = ($i % 2 === 0) ? 'FFFFFF' : $this->altBg;
            $table2->addRow();
            $this->addTdCell($table2, $a->kode, $altW, 'left', $bg, true);
            foreach ($kriteria as $k) {
                $rij = $matriksNormal[$a->id][$k->id] ?? 0;
                $this->addTdCell($table2, number_format($rij, 4), $cW, 'center', $bg);
            }
        }

        $section->addTextBreak(1);
    }

    // ======================================================================
    // SAW LANGKAH 3 - Preferensi
    // ======================================================================
    protected function writeSAWLangkah3($section)
    {
        $section->addText('Langkah 3: Hitung Nilai Preferensi (Vi)', $this->h3Font, $this->parLeft);
        $section->addText('Rumus: Vi = Σ(Wj × Rij)', $this->boldFont, ['alignment' => Jc::CENTER, 'spaceAfter' => 120]);

        $alternatif = $this->hasilSAW['alternatif'];
        $detailPref = $this->hasilSAW['detail_preferensi'];
        $preferensi = $this->hasilSAW['preferensi'];

        foreach ($alternatif as $a) {
            $det = $detailPref[$a->id] ?? [];
            $parts = [];
            foreach ($det as $d) {
                $parts[] = "(" . number_format($d['bobot_normal'], 4) . "×" . number_format($d['rij'], 4) . ")";
            }
            $formula = implode(' + ', $parts);
            $vi = $preferensi[$a->id] ?? 0;

            $section->addText(
                "V({$a->kode}) = {$formula} = " . number_format($vi, 6),
                $this->smallFont, $this->parLeft
            );
        }

        $section->addTextBreak(1);
    }

    // ======================================================================
    // SAW RANKING
    // ======================================================================
    protected function writeSAWRanking($section)
    {
        $section->addText('6.6 Hasil Ranking Metode SAW', ['bold' => true, 'size' => 13, 'name' => 'Times New Roman', 'color' => $this->sawColor], $this->parLeft);

        $ranking = $this->hasilSAW['ranking'];

        $table = $section->addTable([
            'borderSize' => 6,
            'borderColor' => 'cccccc',
            'cellMargin' => 60,
            'alignment' => Jc::CENTER,
        ]);

        $table->addRow();
        $this->addThCell($table, 'Rank', 800, $this->thBgSaw);
        $this->addThCell($table, 'Kode', 1000, $this->thBgSaw);
        $this->addThCell($table, 'Nama Alternatif', 3700, $this->thBgSaw);
        $this->addThCell($table, 'Preferensi (Vi)', 1800, $this->thBgSaw);
        $this->addThCell($table, '%', 1000, $this->thBgSaw);

        foreach ($ranking as $i => $r) {
            $bg = ($i === 0) ? $this->sawBg : (($i % 2 === 0) ? 'FFFFFF' : $this->altBg);
            $font = ($i === 0) ? ['bold' => true, 'size' => 11, 'name' => 'Times New Roman', 'color' => '14532d'] : $this->tdFont;
            $table->addRow();
            $this->addTdCellFont($table, '#' . $r['rank'], 800, 'center', $bg, $font);
            $this->addTdCellFont($table, $r['kode'], 1000, 'center', $bg, $font);
            $this->addTdCellFont($table, $r['nama'], 3700, 'left', $bg, $font);
            $this->addTdCellFont($table, number_format($r['preferensi'], 6), 1800, 'center', $bg, $font);
            $this->addTdCellFont($table, number_format($r['persentase'], 2) . '%', 1000, 'center', $bg, $font);
        }

        if (!empty($ranking)) {
            $best = $ranking[0];
            $section->addTextBreak(0);
            $section->addText(
                "Berdasarkan metode SAW, alternatif terbaik adalah {$best['kode']} - {$best['nama']} dengan nilai preferensi = " . number_format($best['preferensi'], 6) . " ({$best['persentase']}%).",
                $this->boldFont, $this->parJustify
            );
        }

        $section->addTextBreak(1);
    }

    // ======================================================================
    // PERBANDINGAN
    // ======================================================================
    protected function writePerbandingan($section)
    {
        $section->addText('', [], ['borderBottomSize' => 8, 'borderBottomColor' => '6366f1', 'spaceAfter' => 100]);
        $section->addText('7. PERBANDINGAN HASIL', ['bold' => true, 'size' => 14, 'name' => 'Times New Roman', 'color' => '4338ca'], $this->parLeft);

        $wpRanking = $this->hasilWP['ranking'];
        $sawRanking = $this->hasilSAW['ranking'];

        $table = $section->addTable([
            'borderSize' => 6,
            'borderColor' => 'cccccc',
            'cellMargin' => 60,
            'alignment' => Jc::CENTER,
        ]);

        $table->addRow();
        $this->addThCell($table, 'Rank', 800, '4338ca');
        $this->addThCell($table, 'Metode WP', 3600, $this->thBg);
        $this->addThCell($table, 'Skor WP', 1200, $this->thBg);
        $this->addThCell($table, 'Metode SAW', 3600, $this->thBgSaw);
        $this->addThCell($table, 'Skor SAW', 1200, $this->thBgSaw);

        $maxRows = max(count($wpRanking), count($sawRanking));
        for ($i = 0; $i < $maxRows; $i++) {
            $bg = ($i % 2 === 0) ? 'FFFFFF' : $this->altBg;
            $table->addRow();
            $this->addTdCell($table, '#' . ($i + 1), 800, 'center', $bg, true);

            if (isset($wpRanking[$i])) {
                $this->addTdCell($table, $wpRanking[$i]['kode'] . ' - ' . $wpRanking[$i]['nama'], 3600, 'left', $bg);
                $this->addTdCell($table, number_format($wpRanking[$i]['vektor_v'], 6), 1200, 'center', $bg);
            } else {
                $this->addTdCell($table, '-', 3600, 'center', $bg);
                $this->addTdCell($table, '-', 1200, 'center', $bg);
            }

            if (isset($sawRanking[$i])) {
                $this->addTdCell($table, $sawRanking[$i]['kode'] . ' - ' . $sawRanking[$i]['nama'], 3600, 'left', $bg);
                $this->addTdCell($table, number_format($sawRanking[$i]['preferensi'], 6), 1200, 'center', $bg);
            } else {
                $this->addTdCell($table, '-', 3600, 'center', $bg);
                $this->addTdCell($table, '-', 1200, 'center', $bg);
            }
        }

        // Konsistensi analysis
        $section->addTextBreak(0);
        $wpBest = $wpRanking[0] ?? null;
        $sawBest = $sawRanking[0] ?? null;

        if ($wpBest && $sawBest) {
            if ($wpBest['alternatif_id'] === $sawBest['alternatif_id']) {
                $section->addText(
                    "Kedua metode menghasilkan alternatif terbaik yang SAMA, yaitu {$wpBest['kode']} - {$wpBest['nama']}. Hal ini menunjukkan konsistensi yang tinggi dalam hasil perankingan.",
                    $this->boldFont, $this->parJustify
                );
            } else {
                $section->addText(
                    "Kedua metode menghasilkan alternatif terbaik yang BERBEDA. WP memilih {$wpBest['kode']} - {$wpBest['nama']}, sedangkan SAW memilih {$sawBest['kode']} - {$sawBest['nama']}. Perbedaan ini disebabkan oleh perbedaan cara perhitungan kedua metode.",
                    $this->boldFont, $this->parJustify
                );
            }
        }

        $section->addTextBreak(1);
    }

    // ======================================================================
    // KESIMPULAN
    // ======================================================================
    protected function writeKesimpulan($section)
    {
        $section->addText('8. KESIMPULAN', $this->subtitleFont, $this->parLeft);

        $wpBest = $this->hasilWP['ranking'][0] ?? null;
        $sawBest = $this->hasilSAW['ranking'][0] ?? null;

        $section->addText(
            "Berdasarkan analisis menggunakan dua metode Sistem Pendukung Keputusan untuk \"{$this->proyekNama}\", diperoleh hasil sebagai berikut:",
            $this->normalFont, $this->parJustify
        );

        if ($wpBest) {
            $section->addListItem(
                "Metode Weighted Product (WP): Alternatif terbaik adalah {$wpBest['kode']} - {$wpBest['nama']} dengan nilai Vektor V = " . number_format($wpBest['vektor_v'], 6) . " ({$wpBest['persentase']}%).",
                0, $this->normalFont, ['listType' => \PhpOffice\PhpWord\Style\ListItem::TYPE_NUMBER], $this->parJustify
            );
        }
        if ($sawBest) {
            $section->addListItem(
                "Metode Simple Additive Weighting (SAW): Alternatif terbaik adalah {$sawBest['kode']} - {$sawBest['nama']} dengan nilai preferensi = " . number_format($sawBest['preferensi'], 6) . " ({$sawBest['persentase']}%).",
                0, $this->normalFont, ['listType' => \PhpOffice\PhpWord\Style\ListItem::TYPE_NUMBER], $this->parJustify
            );
        }

        $section->addTextBreak(0);

        if ($wpBest && $sawBest && $wpBest['alternatif_id'] === $sawBest['alternatif_id']) {
            $section->addText(
                "Dengan demikian, kedua metode secara konsisten merekomendasikan {$wpBest['kode']} - {$wpBest['nama']} sebagai alternatif terbaik untuk \"{$this->proyekNama}\". Konsistensi hasil ini memperkuat validitas rekomendasi yang diberikan.",
                $this->normalFont, $this->parJustify
            );
        } else {
            $section->addText(
                'Meskipun terdapat perbedaan perankingan dari kedua metode, hal ini merupakan hal yang wajar karena masing-masing metode memiliki pendekatan perhitungan yang berbeda. Disarankan untuk mempertimbangkan hasil dari kedua metode dalam pengambilan keputusan akhir.',
                $this->normalFont, $this->parJustify
            );
        }

        // Footer
        $section->addTextBreak(2);
        $section->addText('', [], ['borderBottomSize' => 6, 'borderBottomColor' => 'cccccc', 'spaceAfter' => 100]);
        $section->addText('Dokumen ini dihasilkan secara otomatis oleh Sistem Pendukung Keputusan pada ' . now()->format('d F Y, H:i') . '.', $this->italicFont, ['alignment' => Jc::CENTER, 'spaceAfter' => 0]);
    }

    // ======================================================================
    // HELPER: Table cells
    // ======================================================================
    protected function addThCell($table, $text, $width, $bgColor = null)
    {
        $bgColor = $bgColor ?: $this->thBg;
        $cell = $table->addCell($width, ['bgColor' => $bgColor, 'valign' => 'center']);
        $cell->addText($text, $this->thFont, $this->parCenter);
    }

    protected function addTdCell($table, $text, $width, $align = 'left', $bgColor = 'FFFFFF', $bold = false)
    {
        $cell = $table->addCell($width, ['bgColor' => $bgColor, 'valign' => 'center']);
        $font = $bold ? array_merge($this->tdFont, ['bold' => true]) : $this->tdFont;
        $par = ['spaceAfter' => 0];
        if ($align === 'center') $par['alignment'] = Jc::CENTER;
        elseif ($align === 'right') $par['alignment'] = Jc::END;
        $cell->addText((string) $text, $font, $par);
    }

    protected function addTdCellFont($table, $text, $width, $align, $bgColor, $font)
    {
        $cell = $table->addCell($width, ['bgColor' => $bgColor, 'valign' => 'center']);
        $par = ['spaceAfter' => 0];
        if ($align === 'center') $par['alignment'] = Jc::CENTER;
        elseif ($align === 'right') $par['alignment'] = Jc::END;
        $cell->addText((string) $text, $font, $par);
    }
}
