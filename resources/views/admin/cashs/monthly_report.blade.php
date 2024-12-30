@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Rekap Bulanan</h4>
                    <p>Pilih bulan untuk melihat rekapitulasi kas.</p>
                </div>
                <div class="card-body">
                    <form id="monthlyReportForm" action="{{ route('cashs.monthlyReport') }}" method="GET">
                        <div class="d-flex align-items-center gap-3">
                            <input type="month" name="month" id="month" class="form-control w-auto"
                                value="{{ $month }}">
                            <button type="submit" class="btn btn-primary">Tampilkan</button>
                        </div>
                    </form>
                </div>
            </div>

            @if ($cashs->isNotEmpty())
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title">Rekap Kas untuk Bulan
                            {{ \Carbon\Carbon::parse($month)->translatedFormat('F Y') }}</h5>
                        <p>Total Jumlah: Rp. {{ number_format($totalAmount, 0, ',', '.') }}</p>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Deskripsi</th>
                                        <th>Kategori</th>
                                        <th>Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cashs as $cash)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ \Carbon\Carbon::parse($cash->date)->translatedFormat('d F Y') }}</td>
                                            <td>{{ Str::limit($cash->description, 100, '...') }}</td>
                                            <td>{{ $cash->category->name }}</td>
                                            <td>Rp. {{ number_format($cash->amount, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-info mt-4">Tidak ada data kas untuk bulan ini.</div>
            @endif
        </div>
    </div>
@endsection
