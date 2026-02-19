@extends('layouts.app')

@section('title', 'Tambah Kriteria')
@section('page-title', 'Tambah Kriteria')

@section('guide-title', 'Panduan Tambah Kriteria')
@section('guide-content')
<div class="guide-step">
    <h6><i class="bi bi-1-circle me-1"></i> Kode Kriteria</h6>
    <p>Masukkan kode unik untuk kriteria ini (maksimal 10 karakter). Contoh: C1, C2, K1, K2, dst.</p>
</div>
<div class="guide-step">
    <h6><i class="bi bi-2-circle me-1"></i> Nama Kriteria</h6>
    <p>Masukkan nama deskriptif untuk kriteria. Contoh: Harga, Kualitas, Jarak, Pelayanan.</p>
</div>
<div class="guide-step">
    <h6><i class="bi bi-3-circle me-1"></i> Bobot</h6>
    <p>Masukkan bobot kepentingan (angka desimal). Bobot akan dinormalisasi secara otomatis: W<sub>j</sub> = W<sub>j</sub> / &Sigma;W<sub>j</sub></p>
</div>
<div class="guide-step">
    <h6><i class="bi bi-4-circle me-1"></i> Jenis Kriteria</h6>
    <p><strong>Benefit:</strong> Semakin besar nilainya, semakin baik (contoh: Kualitas)<br>
    <strong>Cost:</strong> Semakin kecil nilainya, semakin baik (contoh: Harga)</p>
</div>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-plus-circle me-2"></i> Form Tambah Kriteria
            </div>
            <div class="card-body">
                <form action="{{ route('kriteria.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="kode" class="form-label fw-bold">Kode Kriteria <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('kode') is-invalid @enderror"
                                   id="kode" name="kode" value="{{ old('kode') }}"
                                   placeholder="Contoh: C1" required maxlength="10">
                            @error('kode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="nama" class="form-label fw-bold">Nama Kriteria <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                   id="nama" name="nama" value="{{ old('nama') }}"
                                   placeholder="Contoh: Harga" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="bobot" class="form-label fw-bold">Bobot <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('bobot') is-invalid @enderror"
                                   id="bobot" name="bobot" value="{{ old('bobot') }}"
                                   placeholder="Contoh: 5" required step="0.01" min="0" max="100">
                            @error('bobot')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Bobot akan dinormalisasi secara otomatis.</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="jenis" class="form-label fw-bold">Jenis <span class="text-danger">*</span></label>
                            <select class="form-select @error('jenis') is-invalid @enderror"
                                    id="jenis" name="jenis" required>
                                <option value="">-- Pilih Jenis --</option>
                                <option value="benefit" {{ old('jenis') == 'benefit' ? 'selected' : '' }}>
                                    ▲ Benefit (Semakin besar semakin baik)
                                </option>
                                <option value="cost" {{ old('jenis') == 'cost' ? 'selected' : '' }}>
                                    ▼ Cost (Semakin kecil semakin baik)
                                </option>
                            </select>
                            @error('jenis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="keterangan" class="form-label fw-bold">Keterangan</label>
                        <textarea class="form-control @error('keterangan') is-invalid @enderror"
                                  id="keterangan" name="keterangan" rows="3"
                                  placeholder="Keterangan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('kriteria.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Simpan Kriteria
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
