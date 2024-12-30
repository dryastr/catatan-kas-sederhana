@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Rekap Bulanan Pengeluaran</h4>
                    <p>Pilih bulan untuk melihat rekapitulasi pengeluaran kas.</p>
                </div>
                <div class="card-body">
                    <form id="monthlyReportForm" action="{{ route('cashOut.monthlyReport') }}" method="GET">
                        <div class="d-flex align-items-center gap-3">
                            <input type="month" name="month" id="month" class="form-control w-auto"
                                value="{{ $month }}">
                            <button type="submit" class="btn btn-primary">Tampilkan</button>
                        </div>
                    </form>
                </div>
            </div>

            @if ($cashOuts->isNotEmpty())
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title">Rekap Pengeluaran untuk Bulan
                            {{ \Carbon\Carbon::parse($month)->translatedFormat('F Y') }}</h5>
                        <p>Total Jumlah Pengeluaran: Rp. {{ number_format($totalAmount, 0, ',', '.') }}</p>
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
                                    @foreach ($cashOuts as $cashOut)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ \Carbon\Carbon::parse($cashOut->date)->translatedFormat('d F Y') }}</td>
                                            <td>{{ Str::limit($cashOut->description, 100, '...') }}</td>
                                            <td>{{ $cashOut->category->name }}</td>
                                            <td>Rp. {{ number_format($cashOut->amount, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-info mt-4">Tidak ada data pengeluaran kas untuk bulan ini.</div>
            @endif
        </div>
    </div>
@endsection
