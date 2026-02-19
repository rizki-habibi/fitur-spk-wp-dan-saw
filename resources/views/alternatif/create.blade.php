@extends('layouts.app')

@section('title', 'Tambah Alternatif')
@section('page-title', 'Tambah Alternatif')

@section('guide-title', 'Panduan Tambah Alternatif')
@section('guide-content')
<div class="guide-step">
    <h6><i class="bi bi-1-circle me-1"></i> Kode Alternatif</h6>
    <p>Masukkan kode unik untuk alternatif ini (maks. 10 karakter). Contoh: A1, A2, dst.</p>
</div>
<div class="guide-step">
    <h6><i class="bi bi-2-circle me-1"></i> Nama Alternatif</h6>
    <p>Masukkan nama deskriptif untuk alternatif yang akan dinilai.</p>
</div>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-plus-circle me-2"></i> Form Tambah Alternatif
            </div>
            <div class="card-body">
                <form action="{{ route('alternatif.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="kode" class="form-label fw-bold">Kode Alternatif <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('kode') is-invalid @enderror"
                                   id="kode" name="kode" value="{{ old('kode') }}"
                                   placeholder="Contoh: A1" required maxlength="10">
                            @error('kode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="nama" class="form-label fw-bold">Nama Alternatif <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                   id="nama" name="nama" value="{{ old('nama') }}"
                                   placeholder="Contoh: Karyawan A" required>
                            @error('nama')
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
                        <a href="{{ route('alternatif.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Simpan Alternatif
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
