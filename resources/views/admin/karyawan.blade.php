@extends('admin.layout.main')

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link href="{{ asset('assets/plugins/datatables/datatables.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h5>Data Karyawan</h5>
                    <div class="row">
                        <div class="col">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                                + Tambah Karyawan
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-hover " id="tableData1">
                            <thead class="table-dark">
                                <tr class="text-center align-middle">
                                    <th class="text-dark">No.</th>
                                    <th class="text-dark">NIK</th>
                                    <th class="text-dark">Nama</th>
                                    <th class="text-dark">Jabatan</th>
                                    <th class="text-dark">Unit Kerja</th>
                                    <th class="text-dark">Posisi</th>
                                    <th class="text-dark">ID Posisi</th>
                                    <th class="text-dark">ID Karyawan</th>
                                    <th class="text-dark">aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($karyawan as $karyawan)
                                    <tr class="text-center align-middle">
                                        <th>{{ $loop->iteration }}</th>
                                        <td>{{ $karyawan->NIK }}</td>
                                        <td>{{ $karyawan->nama }}</td>
                                        <td>{{ $karyawan->jabatan }}</td>
                                        <td>{{ $karyawan->posisi->unitKerja->nama_unit_kerja }}</td>
                                        <td>{{ $karyawan->posisi->jabatan }}</td>
                                        <td>{{ $karyawan->id_posisi }}</td>
                                        <td>{{ $karyawan->id }}</td>
                                        <td class=" ">

                                            <div class="row">
                                                <div class="col">
                                                    <button class="btn btn-sm px-2 py-0 m-0 btn-warning"
                                                        onclick="editEmployee({{ $karyawan->id }})" data-bs-toggle="modal"
                                                        data-bs-target="#editEmployeeModal"><span
                                                            class="material-icons">edit_note</span></button>
                                                    <button class="btn btn-sm px-2 py-0 m-0 btn-danger"
                                                        onclick="confirmDelete({{ $karyawan->id }}, '{{ $karyawan->nama }}')">
                                                        <span class="material-icons">delete</span>
                                                    </button>

                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <td colspan="6" class="text-center">Data Not Found</td>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal add employee -->
    <div class="modal fade" id="exampleModal" aria-labelledby="exampleModalLabel">
        <form method="post" action="{{ route('tambahKaryawan') }}">
            @csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Form Penambahan Karyawan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col">
                                <label for="nik" class="form-label">NIK</label>
                                <input type="text" class="form-control" name="nik" required />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" class="form-control" name="nama" required />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="jabatan" class="form-label">Jabatan</label>
                                <input type="text" class="form-control" name="jabatan" required />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="daterange" class="form-label">Tanggal Mulai Bekerja</label>
                                <input type="text" class="form-control flatpickr1" name="tmt_bekerja" required />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="daterange" class="form-label">Tanggal Diangkat Menjadi Staf</label>
                                <input type="text" class="form-control flatpickr1" name="tgl_diangkat_staf" required />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="id_posisi" class="form-label">ID Posisi</label>
                                <select class="form-select" aria-label="ID Posisi" name="id_posisi" required>
                                    <option selected value="">Pilih Jabatan</option>
                                    @foreach ($positions as $posisi)
                                        <option value="{{ $posisi->id }}"
                                            {{ old('id_posisi') == $posisi->id ? 'selected' : '' }}>
                                            {{ $posisi->id }} - {{ $posisi->jabatan }}
                                            ({{ $posisi->unitKerja->nama_unit_kerja }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batalkan</button>
                        <button type="submit" class="btn btn-primary">Tambahkan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>



    <!-- Edit Employee Modal -->
    <div class="modal fade" id="editEmployeeModal" aria-labelledby="editEmployeeModalLabel">
        <form id="editEmployeeForm" method="post" action="{{ route('updateKaryawan') }}">
            @csrf
            @method('PUT')

            <input type="hidden" name="id" id="editEmployeeId">

            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editEmployeeModalLabel">Form Edit Karyawan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Form fields for editing employee -->
                        <div class="row mb-3">
                            <div class="col">
                                <label for="editnik" class="form-label">NIK</label>
                                <input type="text" class="form-control" id="editnik" name="nik" required />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="editnama" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="editnama" name="nama_karyawan"
                                    required />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="editjabatan" class="form-label">Jabatan</label>
                                <input type="text" class="form-control" id="editjabatan" name="jabatan" required />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="edittmt_bekerja" class="form-label">Tanggal Mulai Bekerja</label>
                                <input type="text" class="form-control flatpickr1" id="edittmt_bekerja"
                                    name="tmt_bekerja" required />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="edittgl_diangkat_staf" class="form-label">Tanggal Diangkat Menjadi
                                    Staf</label>
                                <input type="text" class="form-control flatpickr1" id="edittgl_diangkat_staf"
                                    name="tgl_diangkat_staf" required />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="editid_posisi" class="form-label">ID Posisi</label>
                                <select class="form-select" id="editid_posisi" name="id_posisi" required>
                                    <option selected value="">Pilih Jabatan</option>
                                    @foreach ($positions as $posisi)
                                        <option value="{{ $posisi->id }}">
                                            {{ $posisi->id }} - {{ $posisi->jabatan }}
                                            ({{ $posisi->unitKerja->nama_unit_kerja }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batalkan</button>
                        <button type="submit" class="btn btn-primary">Edit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>



    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">Konfirmasi Penghapusan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus data <b><span id="employeeName"></span></b>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batalkan</button>
                    <form id="deleteEmployeeForm{{ $karyawan->id }}"
                        action="{{ route('admin.delete-karyawan', $karyawan->id) }}" method="post"
                        style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="{{ asset('assets/plugins/notifications/js/lobibox.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="{{ asset('assets/plugins/datatables/datatables.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#tableData1').DataTable();

            $('.flatpickr1').flatpickr({
                dateFormat: "Y-m-d",
            });


        });

        function editEmployee(id) {
            $.ajax({
                method: 'GET',
                url: `/admin/karyawan/${id}/edit`, // Make sure this URL matches your route
                success: function(data) {
                    // Fill modal form with data
                    $('#editEmployeeId').val(data.id);
                    $('#editnik').val(data.NIK);
                    $('#editnama').val(data.nama);
                    $('#editjabatan').val(data.jabatan);
                    $('#edittmt_bekerja').val(data.TMT_bekerja);
                    $('#edittgl_diangkat_staf').val(data.tgl_diangkat_staf);
                    $('#editid_posisi').val(data.id_posisi);

                    // Initialize flatpickr with existing values
                    $('.flatpickr1').flatpickr({
                        dateFormat: "Y-m-d",
                    });

                    // Show modal
                    $('#editEmployeeModal').modal('show');
                }
            });
        }



        function confirmDelete(id, name) {
            $('#employeeName').text(name); // Set the employee name in the modal
            $('#deleteEmployeeForm').attr('action', `/admin/karyawan/${id}/delete`); // Set the form action URL
            $('#deleteConfirmationModal').modal('show'); // Show the confirmation modal
        }





        function round_success_noti(message) {
            Lobibox.notify('success', {
                pauseDelayOnHover: true,
                size: 'mini',
                rounded: true,
                icon: 'bx bx-check-circle',
                delayIndicator: false,
                continueDelayOnInactiveTab: false,
                position: 'top right',
                sound: false,
                msg: message
            });
        }

        function round_error_noti(message) {
            Lobibox.notify('error', {
                pauseDelayOnHover: true,
                size: 'mini',
                rounded: true,
                icon: 'bi bi-exclamation-triangle',
                delayIndicator: false,
                continueDelayOnInactiveTab: false,
                position: 'top right',
                sound: false,
                msg: message
            });
        }


        @if (session('message'))
            document.addEventListener('DOMContentLoaded', function() {
                round_success_noti("{{ session('message') }}");
            });
        @endif

        @if (session('error_message'))
            document.addEventListener('DOMContentLoaded', function() {
                round_error_noti("{{ session('error_message') }}");
            });
        @endif
    </script>
    @livewireScripts();
@endsection
