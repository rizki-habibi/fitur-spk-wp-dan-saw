<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\WithCharts;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\Layout;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class WPFormulaSheet implements WithCharts, WithEvents, WithTitle
{
    private $hasil, $proyekNama;

    private $c = [
        'header'   => '1e3a5f', 'header_fg' => 'ffffff',
        'step_bg'  => '2563eb', 'step_fg'   => 'ffffff',
        'note_bg'  => 'eff6ff', 'note_fg'   => '1e40af',
        'th_bg'    => '3b82f6', 'th_fg'     => 'ffffff',
        'row_odd'  => 'f8fafc', 'row_even'  => 'ffffff',
        'text'     => '1e293b', 'border'    => 'cbd5e1',
        'gold_bg'  => 'fef3c7', 'gold_fg'   => '92400e',
        'green_bg' => 'dcfce7', 'green_fg'  => '166534',
        'blue_lt'  => 'dbeafe', 'blue'      => '1e40af',
        'red'      => 'dc2626', 'green'     => '16a34a',
    ];

    public function __construct($hasil = [], $proyekNama = 'SPK')
    {
        $this->hasil = $hasil;
        $this->proyekNama = $proyekNama;
    }

    public function title(): string { return 'Metode WP'; }

    public function charts(): array { return []; }

    public function registerEvents(): array
    {
        return [AfterSheet::class => fn(AfterSheet $e) => $this->build($e->sheet->getDelegate())];
    }

    // ── helpers ──
    private function col($i) { return Coordinate::stringFromColumnIndex($i); }

    private function fill($s, $r, $bg, $fg = null, $b = false, $sz = 10)
    {
        $st = ['fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bg]],
               'font' => ['size' => $sz, 'name' => 'Calibri', 'bold' => $b]];
        if ($fg) $st['font']['color'] = ['rgb' => $fg];
        $s->getStyle($r)->applyFromArray($st);
    }

    private function bdr($s, $r)
    {
        $s->getStyle($r)->applyFromArray(['borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => $this->c['border']]]]]);
    }

    private function ctr($s, $r)
    {
        $s->getStyle($r)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
    }

    private function step($s, $row, $ec, $t)
    {
        $s->mergeCells("A{$row}:{$ec}{$row}"); $s->setCellValue("A{$row}", $t);
        $this->fill($s, "A{$row}:{$ec}{$row}", $this->c['step_bg'], $this->c['step_fg'], true, 11);
        $this->bdr($s, "A{$row}:{$ec}{$row}"); $s->getRowDimension($row)->setRowHeight(24);
    }

    private function note($s, $row, $ec, $t)
    {
        $s->mergeCells("A{$row}:{$ec}{$row}"); $s->setCellValue("A{$row}", $t);
        $this->fill($s, "A{$row}:{$ec}{$row}", $this->c['note_bg'], $this->c['note_fg'], false, 9);
        $this->bdr($s, "A{$row}:{$ec}{$row}"); $s->getStyle("A{$row}")->getFont()->setItalic(true);
    }

    private function th($s, $r)
    {
        $this->fill($s, $r, $this->c['th_bg'], $this->c['th_fg'], true, 10); $this->bdr($s, $r); $this->ctr($s, $r);
    }

    private function td($s, $r, $odd = true)
    {
        $this->fill($s, $r, $odd ? $this->c['row_odd'] : $this->c['row_even'], $this->c['text'], false, 10); $this->bdr($s, $r);
    }

    private function gold($s, $r) { $this->fill($s, $r, $this->c['gold_bg'], $this->c['gold_fg'], true, 10); $this->bdr($s, $r); }
    private function grn($s, $r) { $this->fill($s, $r, $this->c['green_bg'], $this->c['green_fg'], true, 10); $this->bdr($s, $r); }

    private function spc($s, $row, $ec) { $s->mergeCells("A{$row}:{$ec}{$row}"); $s->getRowDimension($row)->setRowHeight(6); }

    /** Write formula + cached value so Protected View still shows the number */
    private function setF($s, $cell, $formula, $cached, $fmt = null)
    {
        $s->setCellValue($cell, $formula);
        $s->getCell($cell)->setCalculatedValue($cached);
        if ($fmt) $s->getStyle($cell)->getNumberFormat()->setFormatCode($fmt);
    }

    // ══════════════════════════════════════
    private function build($s)
    {
        $kriteria   = $this->hasil['kriteria'] ?? collect();
        $alternatif = $this->hasil['alternatif'] ?? collect();
        $matriks    = $this->hasil['matriks'] ?? [];
        $totalBobot = $this->hasil['total_bobot'] ?? 0;
        $bobotNormal = $this->hasil['bobot_normal'] ?? [];
        $detail     = $this->hasil['detail_perhitungan'] ?? [];
        $vektorS    = $this->hasil['vektor_s'] ?? [];
        $totalS     = $this->hasil['total_s'] ?? 0;
        $vektorV    = $this->hasil['vektor_v'] ?? [];
        $ranking    = $this->hasil['ranking'] ?? [];

        $nK = $kriteria->count(); $nA = $alternatif->count();
        if ($nK == 0 || $nA == 0) { $s->setCellValue('A1', 'Data belum lengkap.'); return; }

        $s->getTabColor()->setRGB('2563eb');
        $maxCI = max(8, $nK + 3); $mc = $this->col($maxCI);
        $lkCI = $nK + 1; $lk = $this->col($lkCI);
        $tCI  = $nK + 2; $tc = $this->col($tCI);
        $row = 1;

        // ── TITLE ──
        $s->mergeCells("A1:{$mc}1"); $s->setCellValue('A1', strtoupper($this->proyekNama));
        $this->fill($s, "A1:{$mc}1", $this->c['header'], $this->c['header_fg'], true, 14); $this->ctr($s, "A1:{$mc}1");
        $s->getRowDimension(1)->setRowHeight(32); $row++;

        $s->mergeCells("A2:{$mc}2"); $s->setCellValue('A2', 'PERHITUNGAN METODE WEIGHTED PRODUCT (WP)');
        $this->fill($s, "A2:{$mc}2", $this->c['header'], $this->c['header_fg'], true, 12); $this->ctr($s, "A2:{$mc}2");
        $s->getRowDimension(2)->setRowHeight(26); $row++;

        $s->mergeCells("A3:{$mc}3"); $s->setCellValue('A3', 'Tanggal: ' . now()->format('d/m/Y H:i'));
        $this->fill($s, "A3:{$mc}3", $this->c['blue_lt'], $this->c['blue'], false, 9); $this->ctr($s, "A3:{$mc}3"); $row++;

        $this->spc($s, $row, $mc); $row++;

        // ═══════════════ LANGKAH 1: KRITERIA ═══════════════
        $this->step($s, $row, $mc, 'LANGKAH 1: DATA KRITERIA'); $row++;
        $this->note($s, $row, $mc, 'Kolom D = Bobot (Wi), Kolom E = Jenis. Data direferensi di Langkah 4.'); $row++;

        foreach (['No', 'Kode', 'Nama Kriteria', 'Bobot', 'Jenis', 'Keterangan'] as $i => $h)
            $s->setCellValue($this->col($i + 1) . $row, $h);
        $this->th($s, "A{$row}:F{$row}"); $row++;

        $kR = []; // ki => row
        foreach ($kriteria as $ki => $k) {
            $kR[$ki] = $row;
            $s->setCellValue("A{$row}", $ki + 1); $s->setCellValue("B{$row}", $k->kode);
            $s->setCellValue("C{$row}", $k->nama); $s->setCellValue("D{$row}", $k->bobot);
            $s->setCellValue("E{$row}", ucfirst($k->jenis)); $s->setCellValue("F{$row}", $k->keterangan ?? '-');
            $this->td($s, "A{$row}:F{$row}", $ki % 2 == 0); $this->ctr($s, "A{$row}:B{$row}"); $this->ctr($s, "D{$row}:E{$row}");
            $clr = $k->jenis === 'cost' ? $this->c['red'] : $this->c['green'];
            $s->getStyle("E{$row}")->getFont()->getColor()->setRGB($clr); $s->getStyle("E{$row}")->getFont()->setBold(true);
            $row++;
        }

        $s->setCellValue("C{$row}", 'TOTAL BOBOT');
        $this->setF($s, "D{$row}", "=SUM(D{$kR[0]}:D{$kR[$nK-1]})", $totalBobot, '0.##');
        $this->gold($s, "A{$row}:F{$row}"); $this->ctr($s, "D{$row}"); $row++;

        $this->spc($s, $row, $mc); $row++;

        // ═══════════════ LANGKAH 2: ALTERNATIF ═══════════════
        $this->step($s, $row, $mc, 'LANGKAH 2: DATA ALTERNATIF'); $row++;
        $this->note($s, $row, $mc, 'Daftar alternatif (kandidat) yang akan dievaluasi.'); $row++;

        foreach (['No', 'Kode', 'Nama Alternatif', 'Keterangan'] as $i => $h)
            $s->setCellValue($this->col($i + 1) . $row, $h);
        $this->th($s, "A{$row}:D{$row}"); $row++;

        foreach ($alternatif as $ai => $a) {
            $s->setCellValue("A{$row}", $ai + 1); $s->setCellValue("B{$row}", $a->kode);
            $s->setCellValue("C{$row}", $a->nama); $s->setCellValue("D{$row}", $a->keterangan ?? '-');
            $this->td($s, "A{$row}:D{$row}", $ai % 2 == 0); $this->ctr($s, "A{$row}:B{$row}");
            $row++;
        }

        $this->spc($s, $row, $mc); $row++;

        // ═══════════════ LANGKAH 3: MATRIKS ═══════════════
        $this->step($s, $row, $mc, 'LANGKAH 3: MATRIKS PENILAIAN (Xij)'); $row++;
        $this->note($s, $row, $mc, 'Nilai penilaian tiap alternatif. Direferensi di Langkah 5 untuk Vektor S.'); $row++;

        $s->setCellValue("A{$row}", 'Alternatif');
        foreach ($kriteria as $ki => $k) {
            $c = $this->col($ki + 2);
            $s->setCellValue("{$c}{$row}", $k->kode . "\n(" . ucfirst($k->jenis) . ")");
            $s->getStyle("{$c}{$row}")->getAlignment()->setWrapText(true);
        }
        $this->th($s, "A{$row}:{$lk}{$row}"); $s->getRowDimension($row)->setRowHeight(30); $row++;

        $mR = []; // ai => row
        foreach ($alternatif as $ai => $a) {
            $mR[$ai] = $row;
            $s->setCellValue("A{$row}", $a->kode . ' - ' . $a->nama);
            foreach ($kriteria as $ki => $k) {
                $c = $this->col($ki + 2);
                $s->setCellValue("{$c}{$row}", $matriks[$a->id][$k->id] ?? 0);
                $this->ctr($s, "{$c}{$row}");
            }
            $this->td($s, "A{$row}:{$lk}{$row}", $ai % 2 == 0);
            $row++;
        }

        $this->spc($s, $row, $mc); $row++;

        // ═══════════════ LANGKAH 4: NORMALISASI + PANGKAT ═══════════════
        $this->step($s, $row, $mc, 'LANGKAH 4: NORMALISASI BOBOT (Wj) + PANGKAT'); $row++;
        $this->note($s, $row, $mc, 'Wj = Wi/ΣWi (ref Langkah 1). Pangkat: Benefit = +Wj, Cost = -Wj (ref kolom E Langkah 1).'); $row++;

        $s->setCellValue("A{$row}", '');
        foreach ($kriteria as $ki => $k) $s->setCellValue($this->col($ki + 2) . $row, $k->kode);
        $s->setCellValue("{$tc}{$row}", 'Total');
        $this->th($s, "A{$row}:{$tc}{$row}"); $row++;

        // Wi row — ref Langkah 1 kolom D
        $wiR = $row;
        $s->setCellValue("A{$row}", 'Bobot Awal (Wi)');
        foreach ($kriteria as $ki => $k) {
            $c = $this->col($ki + 2);
            $this->setF($s, "{$c}{$row}", '=D' . $kR[$ki], $k->bobot, '0.##');
            $this->ctr($s, "{$c}{$row}");
        }
        $this->setF($s, "{$tc}{$row}", '=SUM(B' . $row . ':' . $lk . $row . ')', $totalBobot, '0.##');
        $this->td($s, "A{$row}:{$tc}{$row}", true); $this->gold($s, "{$tc}{$row}"); $this->ctr($s, "{$tc}{$row}"); $row++;

        // Wj row
        $wjR = $row;
        $s->setCellValue("A{$row}", 'Wj (Normalisasi)'); $s->getStyle("A{$row}")->getFont()->setBold(true);
        foreach ($kriteria as $ki => $k) {
            $c = $this->col($ki + 2);
            $wj = $bobotNormal[$k->id] ?? 0;
            $this->setF($s, "{$c}{$row}", '=' . $c . $wiR . '/$' . $tc . '$' . $wiR, $wj, '0.0000');
            $this->ctr($s, "{$c}{$row}");
        }
        $this->setF($s, "{$tc}{$row}", '=SUM(B' . $row . ':' . $lk . $row . ')', array_sum($bobotNormal), '0.0000');
        $this->td($s, "A{$row}:{$tc}{$row}", false); $this->grn($s, "{$tc}{$row}"); $this->ctr($s, "{$tc}{$row}"); $row++;

        // Pangkat row
        $pR = $row;
        $s->setCellValue("A{$row}", 'Pangkat (+/-)'); $s->getStyle("A{$row}")->getFont()->setBold(true);
        foreach ($kriteria as $ki => $k) {
            $c = $this->col($ki + 2);
            $wj = $bobotNormal[$k->id] ?? 0;
            $pangkat = $k->jenis === 'cost' ? -$wj : $wj;
            $jenisRef = '$E$' . $kR[$ki];
            $this->setF($s, "{$c}{$row}", '=IF(' . $jenisRef . '="Cost",-' . $c . $wjR . ',' . $c . $wjR . ')', $pangkat, '+0.0000;-0.0000');
            $this->ctr($s, "{$c}{$row}");
            $clr = $k->jenis === 'cost' ? $this->c['red'] : $this->c['green'];
            $s->getStyle("{$c}{$row}")->getFont()->getColor()->setRGB($clr);
            $s->getStyle("{$c}{$row}")->getFont()->setBold(true);
        }
        $this->fill($s, "A{$row}:{$tc}{$row}", $this->c['blue_lt'], $this->c['text'], true, 10);
        $this->bdr($s, "A{$row}:{$tc}{$row}"); $row++;

        $this->note($s, $row, $mc, 'Benefit → +Wj  |  Cost → -Wj. Klik sel untuk lihat rumus IF referensi ke Langkah 1.'); $row++;
        $this->spc($s, $row, $mc); $row++;

        // ═══════════════ LANGKAH 5: VEKTOR S ═══════════════
        $this->step($s, $row, $mc, 'LANGKAH 5: VEKTOR S (Si)'); $row++;
        $this->note($s, $row, $mc, 'Setiap sel = Xij^Pangkat (ref Langkah 3 & 4). Si = PRODUCT per baris. Klik sel untuk cek.'); $row++;

        $s->setCellValue("A{$row}", 'Alternatif');
        foreach ($kriteria as $ki => $k) $s->setCellValue($this->col($ki + 2) . $row, $k->kode . '^W');
        $s->setCellValue("{$tc}{$row}", 'Si');
        $this->th($s, "A{$row}:{$tc}{$row}"); $row++;

        $siR = []; // ai => row
        foreach ($alternatif as $ai => $a) {
            $siR[$ai] = $row;
            $s->setCellValue("A{$row}", $a->kode . ' - ' . $a->nama);
            $s->getStyle("A{$row}")->getFont()->setBold(true);

            $detArr = $detail[$a->id] ?? [];
            foreach ($kriteria as $ki => $k) {
                $c = $this->col($ki + 2);
                $matCell = $c . $mR[$ai];
                $pangkatRef = $c . '$' . $pR;
                $cached = isset($detArr[$ki]) ? $detArr[$ki]['hasil'] : 0;
                $this->setF($s, "{$c}{$row}", '=' . $matCell . '^' . $pangkatRef, $cached, '0.000000');
                $this->ctr($s, "{$c}{$row}");
            }

            $si = $vektorS[$a->id] ?? 0;
            $this->setF($s, "{$tc}{$row}", '=PRODUCT(B' . $row . ':' . $lk . $row . ')', $si, '0.000000');
            $this->td($s, "A{$row}:{$tc}{$row}", $ai % 2 == 0);
            $this->gold($s, "{$tc}{$row}"); $this->ctr($s, "{$tc}{$row}");
            $row++;
        }

        // Total S
        $tsR = $row;
        $s->setCellValue("A{$row}", 'TOTAL S (ΣSi)');
        $this->setF($s, "{$tc}{$row}", '=SUM(' . $tc . $siR[0] . ':' . $tc . $siR[$nA - 1] . ')', $totalS, '0.000000');
        $this->grn($s, "A{$row}:{$tc}{$row}"); $this->ctr($s, "{$tc}{$row}"); $row++;

        $this->spc($s, $row, $mc); $row++;

        // ═══════════════ LANGKAH 6: VEKTOR V & RANKING ═══════════════
        $this->step($s, $row, $mc, 'LANGKAH 6: VEKTOR V & RANKING AKHIR'); $row++;
        $this->note($s, $row, $mc, 'Vi = Si/ΣSi. Ranking = RANK(Vi). Semua rumus Excel, klik sel untuk cek referensi.'); $row++;

        foreach (['Ranking', 'Kode', 'Nama Alternatif', 'Si', 'Vi', 'Persentase'] as $i => $h)
            $s->setCellValue($this->col($i + 1) . $row, $h);
        $this->th($s, "A{$row}:F{$row}"); $row++;

        $altIdx = [];
        foreach ($alternatif as $ai => $a) $altIdx[$a->id] = $ai;

        $rkStart = $row; $rkEnd = $row + count($ranking) - 1;

        foreach ($ranking as $ri => $r) {
            $ai = $altIdx[$r['alternatif_id']];
            $siRef = $tc . $siR[$ai];

            // Rank = RANK formula
            $this->setF($s, "A{$row}", '=RANK(E' . $row . ',E$' . $rkStart . ':E$' . $rkEnd . ')', $r['rank']);
            $s->setCellValue("B{$row}", $r['kode']);
            $s->setCellValue("C{$row}", $r['nama']);
            $this->setF($s, "D{$row}", '=' . $siRef, $r['vektor_s'], '0.000000');
            $this->setF($s, "E{$row}", '=D' . $row . '/$' . $tc . '$' . $tsR, $r['vektor_v'], '0.000000');
            $this->setF($s, "F{$row}", '=E' . $row . '*100', $r['persentase'], '0.00"%"');

            $this->td($s, "A{$row}:F{$row}", $ri % 2 == 0);
            $this->ctr($s, "A{$row}:B{$row}"); $this->ctr($s, "D{$row}:F{$row}");
            if ($ri == 0) { $this->gold($s, "A{$row}:F{$row}"); $this->ctr($s, "A{$row}:B{$row}"); $this->ctr($s, "D{$row}:F{$row}"); }
            $row++;
        }

        $this->spc($s, $row, $mc); $row++;

        // ═══════════════ KESIMPULAN ═══════════════
        $s->mergeCells("A{$row}:{$mc}{$row}"); $s->setCellValue("A{$row}", 'KESIMPULAN METODE WP');
        $this->fill($s, "A{$row}:{$mc}{$row}", $this->c['header'], $this->c['header_fg'], true, 12);
        $this->bdr($s, "A{$row}:{$mc}{$row}"); $s->getRowDimension($row)->setRowHeight(26); $row++;

        $w = $ranking[0] ?? null;
        if ($w) {
            $s->mergeCells("A{$row}:{$mc}{$row}");
            $s->setCellValue("A{$row}", "Alternatif terbaik: {$w['kode']} - {$w['nama']}  |  Vi = {$w['vektor_v']}  |  {$w['persentase']}%");
            $this->gold($s, "A{$row}:{$mc}{$row}"); $s->getStyle("A{$row}")->getFont()->setSize(11);
            $s->getRowDimension($row)->setRowHeight(24); $row++;
        }

        $s->mergeCells("A{$row}:{$mc}{$row}");
        $s->setCellValue("A{$row}", 'Semua nilai menggunakan rumus Excel. Klik sel untuk lihat formula dan referensi antar tabel.');
        $this->fill($s, "A{$row}:{$mc}{$row}", $this->c['note_bg'], $this->c['text'], false, 9); $this->bdr($s, "A{$row}:{$mc}{$row}");
        $row++;

        // ═══════════════ DIAGRAM ═══════════════
        $this->spc($s, $row, $mc); $row++;

        $s->mergeCells("A{$row}:{$mc}{$row}"); $s->setCellValue("A{$row}", 'DIAGRAM PERANKINGAN METODE WP');
        $this->fill($s, "A{$row}:{$mc}{$row}", $this->c['header'], $this->c['header_fg'], true, 12);
        $this->bdr($s, "A{$row}:{$mc}{$row}"); $s->getRowDimension($row)->setRowHeight(26); $row++;

        $this->note($s, $row, $mc, 'Diagram batang menampilkan perbandingan persentase ranking setiap alternatif.'); $row++;

        $chartStartRow = $row;

        $catDataSrc = "'Metode WP'!\$B\${$rkStart}:\$B\${$rkEnd}";
        $valDataSrc = "'Metode WP'!\$F\${$rkStart}:\$F\${$rkEnd}";

        $catLabels = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $catDataSrc, null, count($ranking))];
        $dsLabel   = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, null, 'Persentase (%)', 1)];
        $values    = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, $valDataSrc, null, count($ranking))];

        $series = new DataSeries(
            DataSeries::TYPE_BARCHART,
            DataSeries::GROUPING_CLUSTERED,
            range(0, 0),
            $dsLabel,
            $catLabels,
            $values
        );
        $series->setPlotDirection(DataSeries::DIRECTION_COL);

        $layout = new Layout();
        $layout->setShowVal(true);

        $plotArea = new PlotArea($layout, [$series]);
        $legend   = new Legend(Legend::POSITION_BOTTOM, null, false);
        $chartTitle = new Title('Perbandingan Ranking Alternatif - Metode WP');
        $yTitle     = new Title('Persentase (%)');

        $chart = new Chart('wp_ranking_chart', $chartTitle, $legend, $plotArea, true, DataSeries::EMPTY_AS_GAP, null, $yTitle);

        $chartEndRow = $chartStartRow + 15;
        $chart->setTopLeftPosition("A{$chartStartRow}");
        $chart->setBottomRightPosition("{$mc}{$chartEndRow}");
        $s->addChart($chart);
        $row = $chartEndRow + 1;

        // ═══════════════ PENJELASAN METODE ═══════════════
        $this->spc($s, $row, $mc); $row++;

        $s->mergeCells("A{$row}:{$mc}{$row}"); $s->setCellValue("A{$row}", 'PENJELASAN METODE WEIGHTED PRODUCT (WP)');
        $this->fill($s, "A{$row}:{$mc}{$row}", $this->c['header'], $this->c['header_fg'], true, 12);
        $this->bdr($s, "A{$row}:{$mc}{$row}"); $s->getRowDimension($row)->setRowHeight(26); $row++;

        // Pengertian
        $s->mergeCells("A{$row}:{$mc}{$row}"); $s->setCellValue("A{$row}", '1. PENGERTIAN');
        $this->fill($s, "A{$row}:{$mc}{$row}", $this->c['step_bg'], $this->c['step_fg'], true, 10);
        $this->bdr($s, "A{$row}:{$mc}{$row}"); $row++;

        $desc = 'Weighted Product (WP) adalah metode pengambilan keputusan multi-kriteria (MCDM) yang menggunakan perkalian untuk menghubungkan rating atribut, dimana setiap rating atribut dipangkatkan dengan bobot yang bersangkutan. Metode ini mengevaluasi beberapa alternatif terhadap sekumpulan kriteria yang telah ditentukan untuk menghasilkan perankingan terbaik.';
        $s->mergeCells("A{$row}:{$mc}{$row}"); $s->setCellValue("A{$row}", $desc);
        $this->fill($s, "A{$row}:{$mc}{$row}", $this->c['note_bg'], $this->c['text'], false, 9);
        $this->bdr($s, "A{$row}:{$mc}{$row}"); $s->getStyle("A{$row}")->getAlignment()->setWrapText(true);
        $s->getRowDimension($row)->setRowHeight(50); $row++;

        // Alur Perhitungan
        $s->mergeCells("A{$row}:{$mc}{$row}"); $s->setCellValue("A{$row}", '2. ALUR PERHITUNGAN');
        $this->fill($s, "A{$row}:{$mc}{$row}", $this->c['step_bg'], $this->c['step_fg'], true, 10);
        $this->bdr($s, "A{$row}:{$mc}{$row}"); $row++;

        $alur = [
            '1. Menentukan kriteria (Ci) beserta bobot (Wi) dan jenis (Benefit/Cost)',
            '2. Normalisasi bobot: Wj = Wi / ΣWi sehingga total bobot = 1',
            '3. Menentukan pangkat: Benefit → +Wj, Cost → -Wj',
            '4. Menghitung Vektor S: Si = Π(Xij^Wj) — perkalian seluruh nilai pangkat per alternatif',
            '5. Menghitung Vektor V: Vi = Si / ΣSi — proporsi terhadap total S',
            '6. Perankingan: alternatif dengan Vi tertinggi adalah yang terbaik',
        ];
        foreach ($alur as $a) {
            $s->mergeCells("A{$row}:{$mc}{$row}"); $s->setCellValue("A{$row}", $a);
            $this->fill($s, "A{$row}:{$mc}{$row}", $this->c['note_bg'], $this->c['text'], false, 9);
            $this->bdr($s, "A{$row}:{$mc}{$row}"); $row++;
        }

        // Kelebihan
        $s->mergeCells("A{$row}:{$mc}{$row}"); $s->setCellValue("A{$row}", '3. KELEBIHAN');
        $this->fill($s, "A{$row}:{$mc}{$row}", $this->c['step_bg'], $this->c['step_fg'], true, 10);
        $this->bdr($s, "A{$row}:{$mc}{$row}"); $row++;

        $kelebihan = [
            '✓ Proses perhitungan relatif sederhana dan mudah dipahami',
            '✓ Dapat menangani multi-kriteria dengan tipe Benefit dan Cost',
            '✓ Menghasilkan ranking yang konsisten dan terukur',
            '✓ Efektif untuk pengambilan keputusan dengan banyak alternatif',
            '✓ Tidak memerlukan proses normalisasi matriks terlebih dahulu',
        ];
        foreach ($kelebihan as $k) {
            $s->mergeCells("A{$row}:{$mc}{$row}"); $s->setCellValue("A{$row}", $k);
            $this->fill($s, "A{$row}:{$mc}{$row}", $this->c['green_bg'], $this->c['green_fg'], false, 9);
            $this->bdr($s, "A{$row}:{$mc}{$row}"); $row++;
        }

        // Kekurangan
        $s->mergeCells("A{$row}:{$mc}{$row}"); $s->setCellValue("A{$row}", '4. KEKURANGAN');
        $this->fill($s, "A{$row}:{$mc}{$row}", $this->c['step_bg'], $this->c['step_fg'], true, 10);
        $this->bdr($s, "A{$row}:{$mc}{$row}"); $row++;

        $kekurangan = [
            '✗ Sensitif terhadap perubahan bobot kriteria',
            '✗ Memerlukan data penilaian yang bersifat kuantitatif (angka)',
            '✗ Kurang efektif jika nilai atribut mendekati nol',
            '✗ Hasil bergantung pada kelengkapan dan kualitas data input',
        ];
        foreach ($kekurangan as $k) {
            $s->mergeCells("A{$row}:{$mc}{$row}"); $s->setCellValue("A{$row}", $k);
            $this->fill($s, "A{$row}:{$mc}{$row}", 'fef2f2', '991b1b', false, 9);
            $this->bdr($s, "A{$row}:{$mc}{$row}"); $row++;
        }

        // Column widths
        $s->getColumnDimension('A')->setWidth(28); $s->getColumnDimension('B')->setWidth(16);
        $s->getColumnDimension('C')->setWidth(24); $s->getColumnDimension('D')->setWidth(16);
        $s->getColumnDimension('E')->setWidth(16); $s->getColumnDimension('F')->setWidth(20);
        for ($i = 7; $i <= $maxCI; $i++) $s->getColumnDimension($this->col($i))->setWidth(16);
        $s->getParent()->getDefaultStyle()->getFont()->setName('Calibri')->setSize(10);
        $s->freezePane('A5');
    }
}
