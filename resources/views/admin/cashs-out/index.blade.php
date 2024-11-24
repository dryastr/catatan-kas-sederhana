@extends('layouts.main')

@section('title', 'Cash Out Entries')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="">
                            <h4 class="card-title">Daftar Kas Keluar | Sisa Rp.
                                {{ number_format($totalBalance, 0, ',', '.') }}</h4>
                            <small class="text-muted">Saldo Total Kas Keluar: Rp. {{ number_format($totalBalanceCashOut, 0, ',', '.') }}</small>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#addCashOutModal">
                                Tambah Kas Keluar Baru
                            </button>
                            <a href="{{ route('cashsout.export') }}" class="btn btn-success ms-2">
                                Export to Excel
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-xl">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Deskripsi</th>
                                        <th>Kategori</th>
                                        <th>Catatan</th>
                                        <th>Jumlah</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cashOuts as $cashOut)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ \Carbon\Carbon::parse($cashOut->date)->translatedFormat('d F Y') }}</td>
                                            <td>{{ Str::limit($cashOut->description, 100, '...') }}</td>
                                            <td>{{ $cashOut->category->name }}</td>
                                            <td>{{ Str::limit($cashOut->notes, 100, '...') }}</td>
                                            <td>Rp. {{ number_format($cashOut->amount, 0, ',', '.') }}</td>
                                            <td class="text-nowrap">
                                                <div class="dropdown dropup">
                                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                                        id="dropdownMenuButton-{{ $cashOut->id }}"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu"
                                                        aria-labelledby="dropdownMenuButton-{{ $cashOut->id }}">
                                                        <li>
                                                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                                data-bs-target="#viewCashOutModal"
                                                                onclick="viewCashOut({{ json_encode($cashOut) }})">Lihat</a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                                data-bs-target="#editCashOutModal"
                                                                onclick="editCashOut({{ json_encode($cashOut) }})">Ubah</a>
                                                        </li>
                                                        <li>
                                                            <form action="{{ route('cashs-out.destroy', $cashOut->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Yakin ingin menghapus cash out entry ini?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item">Hapus</button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Cash Out -->
    <div class="modal fade" id="addCashOutModal" tabindex="-1" aria-labelledby="addCashOutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCashOutModalLabel">Tambah Cash Out Entry Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('cashs-out.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="date" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" id="date" name="date" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <input type="text" class="form-control" id="description" name="description" required>
                        </div>
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Kategori</label>
                            <select class="form-control" id="category_id" name="category_id" required>
                                @if (isset($categories) && count($categories) > 0)
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                @else
                                    <option value="" disabled selected>- No Categories Available -</option>
                                @endif
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Catatan</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="amount" class="form-label">Jumlah</label>
                            <input type="number" class="form-control" id="amount" name="amount" step="0.01"
                                required>
                            <small class="text-muted">Saldo Total: {{ $totalBalance }}</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Lihat Cash Out -->
    <div class="modal fade" id="viewCashOutModal" tabindex="-1" aria-labelledby="viewCashOutModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewCashOutModalLabel">Lihat Cash Out Entry</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="view_date" class="form-label">Tanggal</label>
                        <input type="text" class="form-control" id="view_date" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="view_description" class="form-label">Deskripsi</label>
                        <input type="text" class="form-control" id="view_description" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="view_category_id" class="form-label">Kategori</label>
                        <input type="text" class="form-control" id="view_category" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="view_notes" class="form-label">Catatan</label>
                        <textarea class="form-control" id="view_notes" rows="3" readonly></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="view_amount" class="form-label">Jumlah</label>
                        <input type="text" class="form-control" id="view_amount" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Cash Out -->
    <div class="modal fade" id="editCashOutModal" tabindex="-1" aria-labelledby="editCashOutModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCashOutModalLabel">Ubah Cash Out Entry</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editCashOutForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_date" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" id="edit_date" name="date" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Deskripsi</label>
                            <input type="text" class="form-control" id="edit_description" name="description"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_category_id" class="form-label">Kategori</label>
                            <select class="form-control" id="edit_category_id" name="category_id" required>
                                @if (isset($categories) && count($categories) > 0)
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                @else
                                    <option value="" disabled selected>- No Categories Available -</option>
                                @endif
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_notes" class="form-label">Catatan</label>
                            <textarea class="form-control" id="edit_notes" name="notes" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit_amount" class="form-label">Jumlah</label>
                            <input type="number" class="form-control" id="edit_amount" name="amount" step="0.01"
                                required>
                            <small class="text-muted">Saldo Total: {{ $totalBalance }}</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function viewCashOut(cashOut) {
            document.getElementById('view_date').value = cashOut.date;
            document.getElementById('view_description').value = cashOut.description;
            document.getElementById('view_category').value = cashOut.category.name;
            document.getElementById('view_notes').value = cashOut.notes;
            document.getElementById('view_amount').value = cashOut.amount;
            $('#viewCashOutModal').modal('show');
        }

        function editCashOut(cashOut) {
            document.getElementById('editCashOutForm').action = `/cashs-out/${cashOut.id}`;
            document.getElementById('edit_date').value = cashOut.date;
            document.getElementById('edit_description').value = cashOut.description;
            document.getElementById('edit_category_id').value = cashOut.category_id;
            document.getElementById('edit_notes').value = cashOut.notes;
            document.getElementById('edit_amount').value = cashOut.amount;
            $('#editCashOutModal').modal('show');
        }
    </script>
@endpush
