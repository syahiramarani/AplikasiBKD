@extends('layout.master') {{-- sesuaikan --}}

@section('konten')
    <style>
        .hero {
            border-radius: 18px;
            padding: 18px;
            color: #f4f3ff;
            background:
                radial-gradient(circle at top left, rgba(124, 58, 237, .35), transparent 42%),
                radial-gradient(circle at bottom right, rgba(6, 182, 212, .18), transparent 38%),
                linear-gradient(135deg, #221d55 0%, #1d184c 100%);
            box-shadow: 0 18px 40px rgba(18, 15, 45, .18);
            border: 1px solid rgba(255, 255, 255, .08);
        }

        .filter-card {
            border-radius: 18px;
            padding: 14px 16px;
            background: #fff;
            border: 1px solid rgba(18, 15, 45, .06);
            box-shadow: 0 12px 28px rgba(18, 15, 45, .06);
        }

        .btn-gradient {
            border: none;
            color: #fff;
            font-weight: 800;
            border-radius: 12px;
            padding: 10px 14px;
            background: linear-gradient(135deg, #5f55db, #4e46b9);
            box-shadow: 0 14px 25px rgba(79, 70, 201, .22);
        }
    </style>

    <div class="container py-3">

        <div class="hero mb-3">
            <h4 style="margin:0;font-weight:900;">Rekap Laporan Distribusi (History)</h4>
            <p style="margin:6px 0 0;color:rgba(233,231,255,.82);font-size:13px;">
                Pilih Tahun Ajaran untuk membuka hasil distribusi yang pernah dibuat.
            </p>
        </div>

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="filter-card">
            <form method="GET" action="{{ route('rekap-distribusi.index') }}" class="row g-2 align-items-end">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Tahun Ajaran</label>
                    <select name="tahun_ajaran_id" class="form-select" required>
                        <option value="" selected disabled>-- Pilih Tahun Ajaran --</option>
                        @foreach ($tahun as $t)
                            <option value="{{ $t->id }}">{{ $t->nama ?? ($t->tahun_ajaran ?? 'TA #' . $t->id) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 d-flex gap-2">
                    <button class="btn-gradient w-100" type="submit">Buka Rekap</button>
                    <a href="{{ route('rekap-distribusi.index') }}" class="btn btn-light w-100"
                        style="border-radius:12px;font-weight:800;">
                        Reset
                    </a>
                </div>
            </form>
        </div>

    </div>
@endsection
