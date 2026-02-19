@extends('layouts.app')

@section('title', 'Data Kriteria')
@section('page-title', 'Data Kriteria')

@section('guide-title', 'Panduan Data Kriteria')
@section('guide-content')
<div class="guide-step">
    <h6><i class="bi bi-info-circle me-1"></i> Apa itu Kriteria?</h6>
    <p>Kriteria adalah parameter/faktor yang digunakan untuk menilai alternatif. Misalnya: Harga, Kualitas, Jarak, dll.</p>
</div>
<div class="guide-step">
    <h6><i class="bi bi-1-circle me-1"></i> Kode Kriteria</h6>
    <p>Kode unik untuk identifikasi kriteria (contoh: C1, C2, C3, dst).</p>
</div>
<div class="guide-step">
    <h6><i class="bi bi-2-circle me-1"></i> Bobot Kriteria</h6>
    <p>Angka yang menunjukkan tingkat kepentingan kriteria. Semakin besar bobot, semakin penting kriteria tersebut. Bobot akan dinormalisasi secara otomatis dalam perhitungan.</p>
</div>
<div class="guide-step">
    <h6><i class="bi bi-3-circle me-1"></i> Jenis Kriteria</h6>
    <p>
        <span class="badge badge-benefit">Benefit</span> → Semakin <strong>tinggi</strong> nilainya, semakin <strong>baik</strong> (contoh: Kualitas, Pendapatan)<br>
        <span class="badge badge-cost">Cost</span> → Semakin <strong>rendah</strong> nilainya, semakin <strong>baik</strong> (contoh: Harga, Jarak)
    </p>
</div>
<div class="guide-step">
    <h6><i class="bi bi-4-circle me-1"></i> Import & Export Excel</h6>
    <p>Anda bisa mengimpor data kriteria dari file Excel. Format kolom yang diperlukan: <code>kode</code>, <code>nama</code>, <code>bobot</code>, <code>jenis</code>, <code>keterangan</code> (opsional).</p>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span><i class="bi bi-list-check me-2"></i> Daftar Kriteria</span>
        <div class="d-flex gap-2 flex-wrap">
            <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="bi bi-upload me-1"></i> Import Excel
            </button>
            <a href="{{ route('export.kriteria') }}" class="btn btn-sm btn-outline-info">
                <i class="bi bi-download me-1"></i> Export Excel
            </a>
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="bi bi-plus-circle me-1"></i> Tambah Kriteria
            </button>
        </div>
    </div>
    <div class="card-body">
        @if($kriteria->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-inbox" style="font-size: 3rem; color: #cbd5e1;"></i>
            <p class="text-muted mt-2">Belum ada data kriteria.</p>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="bi bi-plus-circle me-1"></i> Tambah Kriteria Pertama
            </button>
        </div>
        @else
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode</th>
                        <th>Nama Kriteria</th>
                        <th>Bobot</th>
                        <th>Bobot Normal</th>
                        <th>Jenis</th>
                        <th>Keterangan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kriteria as $index => $k)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><code>{{ $k->kode }}</code></td>
                        <td>{{ $k->nama }}</td>
                        <td><strong>{{ $k->bobot }}</strong></td>
                        <td>
                            <span class="badge bg-primary">
                                {{ $totalBobot > 0 ? number_format($k->bobot / $totalBobot, 4) : '0' }}
                            </span>
                        </td>
                        <td>
                            @if($k->jenis == 'benefit')
                                <span class="badge badge-benefit"><i class="bi bi-arrow-up"></i> Benefit</span>
                            @else
                                <span class="badge badge-cost"><i class="bi bi-arrow-down"></i> Cost</span>
                            @endif
                        </td>
                        <td>{{ $k->keterangan ?? '-' }}</td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-warning" title="Edit"
                                    data-bs-toggle="modal" data-bs-target="#editModal{{ $k->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-outline-danger" title="Hapus"
                                    data-bs-toggle="modal" data-bs-target="#deleteModal{{ $k->id }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="table-light fw-bold">
                        <td colspan="3" class="text-end">Total Bobot:</td>
                        <td>{{ $totalBobot }}</td>
                        <td><span class="badge bg-success">1.0000</span></td>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @endif
    </div>
</div>

{{-- ===== MODAL: Tambah Kriteria ===== --}}
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('kriteria.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i> Tambah Kriteria</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="create_kode" class="form-label fw-bold">Kode <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="create_kode" name="kode"
                                   placeholder="Contoh: C1" required maxlength="10">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="create_nama" class="form-label fw-bold">Nama <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="create_nama" name="nama"
                                   placeholder="Contoh: Harga" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="create_bobot" class="form-label fw-bold">Bobot <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="create_bobot" name="bobot"
                                   placeholder="Contoh: 5" required step="0.01" min="0" max="100">
                            <div class="form-text">Bobot akan dinormalisasi otomatis.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="create_jenis" class="form-label fw-bold">Jenis <span class="text-danger">*</span></label>
                            <select class="form-select" id="create_jenis" name="jenis" required>
                                <option value="">-- Pilih --</option>
                                <option value="benefit">▲ Benefit (Semakin besar semakin baik)</option>
                                <option value="cost">▼ Cost (Semakin kecil semakin baik)</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="create_keterangan" class="form-label fw-bold">Keterangan</label>
                        <textarea class="form-control" id="create_keterangan" name="keterangan" rows="2"
                                  placeholder="Opsional"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===== MODAL: Edit & Hapus per Kriteria ===== --}}
@foreach($kriteria as $k)
{{-- Edit Modal --}}
<div class="modal fade" id="editModal{{ $k->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('kriteria.update', $k) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-warning">
                    <h5 class="modal-title"><i class="bi bi-pencil me-2"></i> Edit Kriteria: {{ $k->nama }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Kode <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="kode" value="{{ $k->kode }}" required maxlength="10">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nama <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama" value="{{ $k->nama }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Bobot <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="bobot" value="{{ $k->bobot }}" required step="0.01" min="0" max="100">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Jenis <span class="text-danger">*</span></label>
                            <select class="form-select" name="jenis" required>
                                <option value="benefit" {{ $k->jenis == 'benefit' ? 'selected' : '' }}>▲ Benefit</option>
                                <option value="cost" {{ $k->jenis == 'cost' ? 'selected' : '' }}>▼ Cost</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Keterangan</label>
                        <textarea class="form-control" name="keterangan" rows="2">{{ $k->keterangan }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning"><i class="bi bi-check-circle me-1"></i> Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Delete Modal --}}
<div class="modal fade" id="deleteModal{{ $k->id }}" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form action="{{ route('kriteria.destroy', $k) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="bi bi-trash me-2"></i> Hapus Kriteria</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <i class="bi bi-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                    <p class="mt-2">Yakin ingin menghapus kriteria <strong>{{ $k->kode }} - {{ $k->nama }}</strong>?</p>
                    <p class="text-muted small">Data penilaian terkait juga akan terhapus.</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger"><i class="bi bi-trash me-1"></i> Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

{{-- ===== MODAL: Import Excel ===== --}}
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('import.kriteria') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="bi bi-upload me-2"></i> Import Kriteria dari Excel</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info small">
                        <i class="bi bi-info-circle me-1"></i>
                        <strong>Format kolom Excel:</strong><br>
                        <code>kode</code> | <code>nama</code> | <code>bobot</code> | <code>jenis</code> | <code>keterangan</code>
                        <br><br>
                        <strong>Contoh:</strong><br>
                        C1 | Harga | 5 | cost | Harga produk<br>
                        C2 | Kualitas | 4 | benefit | Kualitas produk
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih File Excel</label>
                        <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                        <div class="form-text">Format: .xlsx, .xls, .csv (Maks 2MB)</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success"><i class="bi bi-upload me-1"></i> Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
