@extends('layouts.app')

@section('title', 'Kelola Proyek')
@section('page-title', 'Kelola Proyek SPK')

@section('guide-title', 'Panduan Proyek')
@section('guide-content')
<div class="guide-step">
    <h6><i class="bi bi-info-circle me-1"></i> Apa itu Proyek?</h6>
    <p>Proyek adalah wadah untuk mengelompokkan data SPK berdasarkan kasus yang berbeda. Misalnya: <strong>Beasiswa Prestasi</strong>, <strong>Bantuan Sosial Staff</strong>, dll.</p>
</div>
<div class="guide-step">
    <h6><i class="bi bi-1-circle me-1"></i> Membuat Proyek Baru</h6>
    <p>Klik tombol <strong>"Tambah Proyek"</strong> lalu isi nama dan deskripsi proyek. Setiap proyek memiliki data kriteria, alternatif, dan penilaian yang terpisah.</p>
</div>
<div class="guide-step">
    <h6><i class="bi bi-2-circle me-1"></i> Memilih Proyek Aktif</h6>
    <p>Klik tombol <strong>"Aktifkan"</strong> pada proyek yang ingin digunakan. Semua data (kriteria, alternatif, penilaian, perhitungan) akan otomatis menampilkan data dari proyek yang dipilih.</p>
</div>
<div class="guide-step">
    <h6><i class="bi bi-exclamation-triangle me-1"></i> Perhatian</h6>
    <p>Menghapus proyek akan menghapus <strong>semua data</strong> terkait proyek tersebut (kriteria, alternatif, penilaian). Pastikan data sudah di-export terlebih dahulu.</p>
</div>
@endsection

@section('content')
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-folder-plus me-2"></i> Tambah Proyek Baru</span>
    </div>
    <div class="card-body">
        <form action="{{ route('proyek.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Nama Proyek <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control" placeholder="contoh: Beasiswa Prestasi" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Deskripsi</label>
                    <input type="text" name="deskripsi" class="form-control" placeholder="Deskripsi singkat proyek">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Icon</label>
                    <select name="icon" class="form-select">
                        <option value="bi-mortarboard">🎓 Beasiswa</option>
                        <option value="bi-people">👥 Staff/Karyawan</option>
                        <option value="bi-heart">❤️ Bansos</option>
                        <option value="bi-trophy">🏆 Prestasi</option>
                        <option value="bi-building">🏢 Perusahaan</option>
                        <option value="bi-geo-alt">📍 Lokasi</option>
                        <option value="bi-folder" selected>📁 Umum</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Warna</label>
                    <input type="color" name="warna" class="form-control form-control-color w-100" value="#4f46e5">
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Buat Proyek
                </button>
            </div>
        </form>
    </div>
</div>

@if($proyeks->isEmpty())
<div class="card">
    <div class="card-body text-center py-5">
        <i class="bi bi-folder-x" style="font-size: 3rem; color: #94a3b8;"></i>
        <p class="text-muted mt-2 mb-0">Belum ada proyek. Buat proyek pertama di atas!</p>
    </div>
</div>
@else
<div class="row">
    @foreach($proyeks as $p)
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100 {{ $activeProyekId == $p->id ? 'border-primary border-2' : '' }}" style="position: relative; overflow: hidden;">
            {{-- Active indicator bar --}}
            @if($activeProyekId == $p->id)
            <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: {{ $p->warna }};"></div>
            @endif

            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div style="width: 48px; height: 48px; border-radius: 12px; background: {{ $p->warna }}15; color: {{ $p->warna }}; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                        <i class="bi {{ $p->icon }}"></i>
                    </div>
                    <div class="ms-3 flex-grow-1">
                        <h5 class="mb-0 fw-bold">{{ $p->nama }}</h5>
                        @if($activeProyekId == $p->id)
                        <span class="badge bg-success" style="font-size: 0.7rem;"><i class="bi bi-check-circle me-1"></i>Aktif</span>
                        @endif
                    </div>
                </div>

                @if($p->deskripsi)
                <p class="text-muted small mb-3">{{ $p->deskripsi }}</p>
                @endif

                <div class="d-flex gap-3 mb-3">
                    <div class="text-center">
                        <div class="fw-bold" style="font-size: 1.25rem; color: {{ $p->warna }};">{{ $p->kriteria_count }}</div>
                        <small class="text-muted">Kriteria</small>
                    </div>
                    <div class="text-center">
                        <div class="fw-bold" style="font-size: 1.25rem; color: {{ $p->warna }};">{{ $p->alternatif_count }}</div>
                        <small class="text-muted">Alternatif</small>
                    </div>
                </div>

                <div class="d-flex gap-2 flex-wrap">
                    @if($activeProyekId != $p->id)
                    <form action="{{ route('proyek.activate', $p) }}" method="POST">
                        @csrf
                        <button class="btn btn-sm btn-primary">
                            <i class="bi bi-check2-circle me-1"></i> Aktifkan
                        </button>
                    </form>
                    @else
                    <a href="{{ route('dashboard') }}" class="btn btn-sm btn-success">
                        <i class="bi bi-speedometer2 me-1"></i> Dashboard
                    </a>
                    @endif

                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editProyek{{ $p->id }}">
                        <i class="bi bi-pencil"></i>
                    </button>

                    <form action="{{ route('proyek.destroy', $p) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('Hapus proyek {{ $p->nama }} beserta SEMUA datanya? Aksi ini tidak bisa dibatalkan!')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>

            <div class="card-footer small text-muted">
                Dibuat: {{ $p->created_at->format('d M Y H:i') }}
            </div>
        </div>

        {{-- Edit Modal --}}
        <div class="modal fade" id="editProyek{{ $p->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('proyek.update', $p) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title"><i class="bi bi-pencil me-2"></i> Edit Proyek</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nama Proyek</label>
                                <input type="text" name="nama" class="form-control" value="{{ $p->nama }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="2">{{ $p->deskripsi }}</textarea>
                            </div>
                            <div class="row">
                                <div class="col-8">
                                    <label class="form-label fw-semibold">Icon</label>
                                    <select name="icon" class="form-select">
                                        <option value="bi-mortarboard" {{ $p->icon == 'bi-mortarboard' ? 'selected' : '' }}>🎓 Beasiswa</option>
                                        <option value="bi-people" {{ $p->icon == 'bi-people' ? 'selected' : '' }}>👥 Staff/Karyawan</option>
                                        <option value="bi-heart" {{ $p->icon == 'bi-heart' ? 'selected' : '' }}>❤️ Bansos</option>
                                        <option value="bi-trophy" {{ $p->icon == 'bi-trophy' ? 'selected' : '' }}>🏆 Prestasi</option>
                                        <option value="bi-building" {{ $p->icon == 'bi-building' ? 'selected' : '' }}>🏢 Perusahaan</option>
                                        <option value="bi-geo-alt" {{ $p->icon == 'bi-geo-alt' ? 'selected' : '' }}>📍 Lokasi</option>
                                        <option value="bi-folder" {{ $p->icon == 'bi-folder' ? 'selected' : '' }}>📁 Umum</option>
                                    </select>
                                </div>
                                <div class="col-4">
                                    <label class="form-label fw-semibold">Warna</label>
                                    <input type="color" name="warna" class="form-control form-control-color w-100" value="{{ $p->warna }}">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection
