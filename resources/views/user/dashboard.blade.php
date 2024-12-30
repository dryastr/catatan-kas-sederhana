@extends('layouts.main')

@section('title', 'Dashboard Owner')

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-muted">Kas Masuk</h6>
                    <h4 class="font-weight-bold text-success">Rp {{ number_format($totalCashIn, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-muted">Kas Keluar</h6>
                    <h4 class="font-weight-bold text-danger">Rp {{ number_format($totalCashOut, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-muted">Kas Akhir</h6>
                    <h4 class="font-weight-bold text-primary">Rp {{ number_format($totalBalance, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
    </div>
@endsection
