@extends('layout.master')

@section('konten')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5><i class="bi bi-bar-chart-steps"></i> Ringkasan Beban Mengajar Dosen</h5>
                </div>
                <div class="card-body">
                    @php
                        $labels = $summary->pluck('dosen.nama_dosen')->toArray();
                        $sksData = $summary->pluck('totalSks')->toArray();
                    @endphp
                    <canvas id="bebanChart" style="max-height:400px;"></canvas>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Dosen</th>
                                    <th>Total SKS</th>
                                    <th>Total Jam</th>
                                    <th>Mata Kuliah</th>
                                    <th>Kelas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($summary as $s)
                                    <tr>
                                        <td>{{ $s->dosen->nama_dosen }}</td>
                                        <td>{{ $s->totalSks }}</td>
                                        <td>{{ $s->totalJam }}</td>
                                        <td>{{ $s->mataKuliahList }}</td>
                                        <td>{{ $s->kelasList }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('bebanChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'Total SKS',
                    data: @json($sksData),
                    backgroundColor: '#4e73df',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'SKS'
                        }
                    }
                }
            }
        });
    </script>
@endsection
