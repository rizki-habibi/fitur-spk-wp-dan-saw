@extends('layouts.app')

@section('title', 'Edit Kriteria')
@section('page-title', 'Edit Kriteria: ' . $kriteria->nama)

@section('guide-title', 'Panduan Edit Kriteria')
@section('guide-content')
<div class="guide-step">
    <h6><i class="bi bi-pencil me-1"></i> Edit Kriteria</h6>
    <p>Ubah data kriteria yang sudah ada. Perubahan bobot atau jenis kriteria akan mempengaruhi hasil perhitungan WP.</p>
</div>
<div class="guide-step">
    <h6><i class="bi bi-exclamation-triangle me-1"></i> Perhatian</h6>
    <p>Jika kriteria sudah memiliki penilaian dan Anda mengubah jenis dari Benefit ke Cost (atau sebaliknya), hasil perhitungan akan berubah secara signifikan.</p>
</div>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-pencil me-2"></i> Form Edit Kriteria
            </div>
            <div class="card-body">
                <form action="{{ route('kriteria.update', $kriteria) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="kode" class="form-label fw-bold">Kode Kriteria <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('kode') is-invalid @enderror"
                                   id="kode" name="kode" value="{{ old('kode', $kriteria->kode) }}"
                                   required maxlength="10">
                            @error('kode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="nama" class="form-label fw-bold">Nama Kriteria <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                   id="nama" name="nama" value="{{ old('nama', $kriteria->nama) }}" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="bobot" class="form-label fw-bold">Bobot <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('bobot') is-invalid @enderror"
                                   id="bobot" name="bobot" value="{{ old('bobot', $kriteria->bobot) }}"
                                   required step="0.01" min="0" max="100">
                            @error('bobot')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="jenis" class="form-label fw-bold">Jenis <span class="text-danger">*</span></label>
                            <select class="form-select @error('jenis') is-invalid @enderror"
                                    id="jenis" name="jenis" required>
                                <option value="benefit" {{ old('jenis', $kriteria->jenis) == 'benefit' ? 'selected' : '' }}>
                                    ▲ Benefit (Semakin besar semakin baik)
                                </option>
                                <option value="cost" {{ old('jenis', $kriteria->jenis) == 'cost' ? 'selected' : '' }}>
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
                                  id="keterangan" name="keterangan" rows="3">{{ old('keterangan', $kriteria->keterangan) }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('kriteria.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-check-circle me-1"></i> Update Kriteria
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
