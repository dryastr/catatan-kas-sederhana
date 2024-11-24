@extends('layouts.main')

@section('title', 'Cash Entries')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="">
                            <h4 class="card-title">Daftar Kas Masuk | Sisa Rp.
                                {{ number_format($totalBalance, 0, ',', '.') }}</h4>
                            <small class="text-muted">Saldo Total Kas Masuk: Rp.
                                {{ number_format($totalBalanceCashIn, 0, ',', '.') }}</small>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#addCashModal">
                                Tambah Kas Masuk Baru
                            </button>
                            <a href="{{ route('cashs.export') }}" class="btn btn-success ms-2">
                                Export to Excel
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-xl pt-5">
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
                                    @foreach ($cashs as $cash)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ \Carbon\Carbon::parse($cash->date)->translatedFormat('d F Y') }}</td>
                                            <td>{{ Str::limit($cash->description, 100, '...') }}</td>
                                            <td>{{ $cash->category->name }}</td>
                                            <td>{{ Str::limit($cash->notes, 100, '...') }}</td>
                                            <td>Rp. {{ number_format($cash->amount, 0, ',', '.') }}</td>
                                            <td class="text-nowrap">
                                                <div class="dropdown dropup">
                                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                                        id="dropdownMenuButton-{{ $cash->id }}"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu"
                                                        aria-labelledby="dropdownMenuButton-{{ $cash->id }}">
                                                        <li>
                                                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                                data-bs-target="#viewCashModal"
                                                                onclick="viewCash({{ json_encode($cash) }})">Lihat</a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                                data-bs-target="#editCashModal"
                                                                onclick="editCash({{ json_encode($cash) }})">Ubah</a>
                                                        </li>
                                                        <li>
                                                            <form action="{{ route('cashs.destroy', $cash->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Yakin ingin menghapus cash entry ini?')">
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

    <!-- Modal Tambah Cash Entry -->
    <div class="modal fade" id="addCashModal" tabindex="-1" aria-labelledby="addCashModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCashModal label">Tambah Cash Entry Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('cashs.store') }}" method="POST">
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

    <!-- Modal Lihat Cash Entry -->
    <div class="modal fade" id="viewCashModal" tabindex="-1" aria-labelledby="viewCashModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewCashModalLabel">Lihat Cash Entry</h5>
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

    <!-- Modal Edit Cash Entry -->
    <div class="modal fade" id="editCashModal" tabindex="-1" aria-labelledby="editCashModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCashModalLabel">Ubah Cash Entry</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editCashForm" method="POST">
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
        function viewCash(cash) {
            document.getElementById('view_date').value = cash.date;
            document.getElementById('view_description').value = cash.description;
            document.getElementById('view_category').value = cash.category.name;
            document.getElementById('view_notes').value = cash.notes;
            document.getElementById('view_amount').value = cash.amount;
            $('#viewCashModal').modal('show');
        }

        function editCash(cash) {
            document.getElementById('editCashForm').action = `/cashs/${cash.id}`;
            document.getElementById('edit_date').value = cash.date;
            document.getElementById('edit_description').value = cash.description;
            document.getElementById('edit_category_id').value = cash.category_id;
            document.getElementById('edit_notes').value = cash.notes;
            document.getElementById('edit_amount').value = cash.amount;
            $('#editCashModal').modal('show');
        }
    </script>
@endpush
