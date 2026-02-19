@extends('layouts.app')

@section('title', 'Data Alternatif')
@section('page-title', 'Data Alternatif')

@section('guide-title', 'Panduan Data Alternatif')
@section('guide-content')
<div class="guide-step">
    <h6><i class="bi bi-info-circle me-1"></i> Apa itu Alternatif?</h6>
    <p>Alternatif adalah pilihan/kandidat yang akan dinilai dan diranking dalam sistem SPK. Contoh: Nama Karyawan, Nama Produk, Nama Lokasi, dll.</p>
</div>
<div class="guide-step">
    <h6><i class="bi bi-1-circle me-1"></i> Kode Alternatif</h6>
    <p>Kode unik untuk identifikasi alternatif (contoh: A1, A2, A3, dst).</p>
</div>
<div class="guide-step">
    <h6><i class="bi bi-2-circle me-1"></i> Nama Alternatif</h6>
    <p>Nama deskriptif dari alternatif yang akan dinilai.</p>
</div>
<div class="guide-step">
    <h6><i class="bi bi-3-circle me-1"></i> Import & Export Excel</h6>
    <p>Format kolom Excel: <code>kode</code> | <code>nama</code> | <code>keterangan</code> (opsional).</p>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span><i class="bi bi-people me-2"></i> Daftar Alternatif</span>
        <div class="d-flex gap-2 flex-wrap">
            <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="bi bi-upload me-1"></i> Import Excel
            </button>
            <a href="{{ route('export.alternatif') }}" class="btn btn-sm btn-outline-info">
                <i class="bi bi-download me-1"></i> Export Excel
            </a>
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="bi bi-plus-circle me-1"></i> Tambah Alternatif
            </button>
        </div>
    </div>
    <div class="card-body">
        @if($alternatif->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-inbox" style="font-size: 3rem; color: #cbd5e1;"></i>
            <p class="text-muted mt-2">Belum ada data alternatif.</p>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="bi bi-plus-circle me-1"></i> Tambah Alternatif Pertama
            </button>
        </div>
        @else
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode</th>
                        <th>Nama Alternatif</th>
                        <th>Keterangan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($alternatif as $index => $a)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><code>{{ $a->kode }}</code></td>
                        <td>{{ $a->nama }}</td>
                        <td>{{ $a->keterangan ?? '-' }}</td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-warning" title="Edit"
                                    data-bs-toggle="modal" data-bs-target="#editModal{{ $a->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-outline-danger" title="Hapus"
                                    data-bs-toggle="modal" data-bs-target="#deleteModal{{ $a->id }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

{{-- ===== MODAL: Tambah Alternatif ===== --}}
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('alternatif.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i> Tambah Alternatif</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Kode <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="kode" placeholder="Contoh: A1" required maxlength="10">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nama <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama" placeholder="Contoh: Karyawan A" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Keterangan</label>
                        <textarea class="form-control" name="keterangan" rows="2" placeholder="Opsional"></textarea>
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

{{-- ===== MODAL: Edit & Hapus per Alternatif ===== --}}
@foreach($alternatif as $a)
{{-- Edit Modal --}}
<div class="modal fade" id="editModal{{ $a->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('alternatif.update', $a) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-warning">
                    <h5 class="modal-title"><i class="bi bi-pencil me-2"></i> Edit Alternatif: {{ $a->nama }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Kode <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="kode" value="{{ $a->kode }}" required maxlength="10">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nama <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama" value="{{ $a->nama }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Keterangan</label>
                        <textarea class="form-control" name="keterangan" rows="2">{{ $a->keterangan }}</textarea>
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
<div class="modal fade" id="deleteModal{{ $a->id }}" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form action="{{ route('alternatif.destroy', $a) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="bi bi-trash me-2"></i> Hapus Alternatif</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <i class="bi bi-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                    <p class="mt-2">Yakin ingin menghapus alternatif <strong>{{ $a->kode }} - {{ $a->nama }}</strong>?</p>
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
            <form action="{{ route('import.alternatif') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="bi bi-upload me-2"></i> Import Alternatif dari Excel</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info small">
                        <i class="bi bi-info-circle me-1"></i>
                        <strong>Format kolom Excel:</strong><br>
                        <code>kode</code> | <code>nama</code> | <code>keterangan</code>
                        <br><br>
                        <strong>Contoh:</strong><br>
                        A1 | Karyawan A | Staff IT<br>
                        A2 | Karyawan B | Staff HRD
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
