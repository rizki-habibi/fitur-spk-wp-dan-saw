@extends('layouts.app')

@section('title', 'Edit Alternatif')
@section('page-title', 'Edit Alternatif: ' . $alternatif->nama)

@section('guide-title', 'Panduan Edit Alternatif')
@section('guide-content')
<div class="guide-step">
    <h6><i class="bi bi-pencil me-1"></i> Edit Alternatif</h6>
    <p>Ubah data alternatif yang sudah ada. Perubahan nama tidak mempengaruhi penilaian yang sudah diinput.</p>
</div>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-pencil me-2"></i> Form Edit Alternatif
            </div>
            <div class="card-body">
                <form action="{{ route('alternatif.update', $alternatif) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="kode" class="form-label fw-bold">Kode Alternatif <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('kode') is-invalid @enderror"
                                   id="kode" name="kode" value="{{ old('kode', $alternatif->kode) }}"
                                   required maxlength="10">
                            @error('kode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="nama" class="form-label fw-bold">Nama Alternatif <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                   id="nama" name="nama" value="{{ old('nama', $alternatif->nama) }}" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="keterangan" class="form-label fw-bold">Keterangan</label>
                        <textarea class="form-control @error('keterangan') is-invalid @enderror"
                                  id="keterangan" name="keterangan" rows="3">{{ old('keterangan', $alternatif->keterangan) }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('alternatif.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-check-circle me-1"></i> Update Alternatif
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
