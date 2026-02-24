@extends('admin.layout.main')

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/notifications/css/lobibox.min.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css">
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
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label>Show
                                <select id="perPageSelect" class="form-select form-select-sm d-inline-block w-auto">
                                    <option value="10">10</option>
                                    <option value="25" selected>25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="200">200</option>
                                </select> entries
                            </label>
                        </div>
                        <div class="col-md-4 text-center">
                            <label class="d-inline-block">Go to page:
                                <input type="number" id="gotoPageInput" class="form-control form-control-sm d-inline-block"
                                    style="width: 80px;" min="1" placeholder="Page">
                                <button class="btn btn-sm btn-primary" onclick="gotoPage()">Go</button>
                            </label>
                        </div>
                        <div class="col-md-4">
                            <input type="text" id="searchInput" class="form-control form-control-sm"
                                placeholder="Search...">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-hover" id="tableData1">
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
                                    <th class="text-dark">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                <tr>
                                    <td colspan="9" class="text-center">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div id="tableInfo"></div>
                        </div>
                        <div class="col-md-6">
                            <nav>
                                <ul class="pagination pagination-sm justify-content-end" id="pagination"></ul>
                            </nav>
                        </div>
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
                                <input type="number" class="form-control" name="nik" required />
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
                                <input type="text" class="form-control flatpickr1" name="tgl_diangkat_staf"
                                    required />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="id_posisi" class="form-label">ID Posisi</label>
                                <select class="form-select" id="select2" style="display: none; width: 100%"
                                    aria-label="ID Posisi" name="id_posisi" required>
                                    <option value="" disabled selected>Pilih Jabatan</option>
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
                                <input type="number" class="form-control" id="editnik" name="nik" required />
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
                                <select class="form-select" id="editid_posisi" style="display: none; width: 100%"
                                    name="id_posisi" required>
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
                    <p>Apakah Anda Yakin Ingin Menghapus Data <b><span id="employeeName"></span></b>? <br> <br> Apakah Anda
                        Yakin Karyawan Tersebut Sudah MBT atau Keluar?</p>
                    <b> <span style="color: red;">NIK Yang Telah Digunakan Tidak Dapat Dipakai Kembali!!!</span></b>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batalkan</button>
                    <form id="deleteEmployeeForm" action="" method="post" style="display: inline;">
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('assets/plugins/notifications/js/lobibox.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/datatables.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#tableData1').DataTable();

            $('.flatpickr1').flatpickr({
                dateFormat: "Y-m-d",
            });
            $('#select2').select2({
                dropdownParent: $('#exampleModal .modal-content')
            });
            $('#editid_posisi').select2({
                dropdownParent: $('#editEmployeeModal .modal-content')
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
                    // $('#editid_posisi').val(data.id_posisi);
                    $('#editid_posisi').val(data.id_posisi).trigger('change');
                    // Show modal
                    $('#editEmployeeModal').modal('show');
                }
            });
        }

        function confirmDelete(id, name) {
            $('#employeeName').text(name); // Set the employee name in the modal
            $('#deleteEmployeeForm').attr('action', `/admin/delete-karyawan/${id}`); // Set the form action URL
            $('#deleteConfirmationModal').modal('show'); // Show the confirmation modal
        }


        @if ($errors->any())
            @foreach ($errors->all() as $error)
                round_warning_noti("{{ $error }}");
            @endforeach
        @endif


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

        function round_warning_noti(message) {
            Lobibox.notify('warning', {
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

        @if (session('warning_message'))
            document.addEventListener('DOMContentLoaded', function() {
                round_warning_noti("{{ session('warning_message') }}");
            });
        @endif

        let currentPage = 1;
        let perPage = 25;
        let searchQuery = '';
        let searchTimeout;
        let totalPages = 1;

        function loadKaryawanData() {
            const tableBody = document.getElementById('tableBody');
            tableBody.innerHTML = '<tr><td colspan="9" class="text-center">Loading...</td></tr>';

            fetch(`{{ route('admin.karyawan.data') }}?page=${currentPage}&per_page=${perPage}&search=${searchQuery}`)
                .then(response => response.json())
                .then(data => {
                    renderTable(data);
                    renderPagination(data);
                    renderInfo(data);
                })
                .catch(error => {
                    console.error('Error:', error);
                    tableBody.innerHTML =
                        '<tr><td colspan="9" class="text-center text-danger">Error loading data</td></tr>';
                });
        }

        function renderTable(data) {
            const tableBody = document.getElementById('tableBody');
            if (data.data.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="9" class="text-center">No data found</td></tr>';
                return;
            }

            let html = '';
            data.data.forEach((item, index) => {
                const no = ((data.current_page - 1) * data.per_page) + index + 1;
                html += `
                    <tr class="text-center align-middle">
                        <th>${no}</th>
                        <td>${item.nik}</td>
                        <td>${item.nama}</td>
                        <td>${item.jabatan}</td>
                        <td>${item.posisi?.unit_kerja?.nama_unit_kerja || '-'}</td>
                        <td>${item.posisi?.jabatan || '-'}</td>
                        <td>${item.id_posisi}</td>
                        <td>${item.id}</td>
                        <td>
                            <button class="btn btn-sm px-2 py-0 m-0 btn-warning"
                                onclick="editEmployee(${item.id})" data-bs-toggle="modal"
                                data-bs-target="#editEmployeeModal">
                                <span class="material-icons">edit_note</span>
                            </button>
                            <button class="btn btn-sm px-2 py-0 m-0 btn-danger"
                                onclick="confirmDelete(${item.id}, '${item.nama}')">
                                <span class="material-icons">delete</span>
                            </button>
                        </td>
                    </tr>
                `;
            });
            tableBody.innerHTML = html;
        }

        function renderPagination(data) {
            totalPages = data.last_page;
            const pagination = document.getElementById('pagination');
            let html = '';
            const maxPages = 10;
            const current = data.current_page;
            const last = data.last_page;

            if (current > 1) {
                html += `<li class="page-item">
                    <a class="page-link" href="#" onclick="changePage(1); return false;">First</a>
                </li>`;
                html += `<li class="page-item">
                    <a class="page-link" href="#" onclick="changePage(${current - 1}); return false;">Prev</a>
                </li>`;
            }

            if (last <= maxPages) {
                for (let i = 1; i <= last; i++) {
                    html += `<li class="page-item ${i === current ? 'active' : ''}">
                        <a class="page-link" href="#" onclick="changePage(${i}); return false;">${i}</a>
                    </li>`;
                }
            } else {
                let startPage, endPage;

                if (current <= 5) {
                    startPage = 1;
                    endPage = maxPages;
                } else if (current >= last - 4) {
                    startPage = last - maxPages + 1;
                    endPage = last;
                } else {
                    startPage = current - 4;
                    endPage = current + 5;
                }

                if (startPage > 1) {
                    html += `<li class="page-item">
                        <a class="page-link" href="#" onclick="changePage(1); return false;">1</a>
                    </li>`;
                    if (startPage > 2) {
                        html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                    }
                }

                for (let i = startPage; i <= endPage; i++) {
                    html += `<li class="page-item ${i === current ? 'active' : ''}">
                        <a class="page-link" href="#" onclick="changePage(${i}); return false;">${i}</a>
                    </li>`;
                }

                if (endPage < last) {
                    if (endPage < last - 1) {
                        html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                    }
                    html += `<li class="page-item">
                        <a class="page-link" href="#" onclick="changePage(${last}); return false;">${last}</a>
                    </li>`;
                }
            }

            if (current < last) {
                html += `<li class="page-item">
                    <a class="page-link" href="#" onclick="changePage(${current + 1}); return false;">Next</a>
                </li>`;
                html += `<li class="page-item">
                    <a class="page-link" href="#" onclick="changePage(${last}); return false;">Last</a>
                </li>`;
            }

            pagination.innerHTML = html;
        }

        function renderInfo(data) {
            const info = document.getElementById('tableInfo');
            const start = ((data.current_page - 1) * data.per_page) + 1;
            const end = Math.min(data.current_page * data.per_page, data.total);
            info.textContent = `Showing ${start} to ${end} of ${data.total} entries`;
        }

        function changePage(page) {
            currentPage = page;
            loadKaryawanData();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        function gotoPage() {
            const input = document.getElementById('gotoPageInput');
            const page = parseInt(input.value);

            if (page && page >= 1 && page <= totalPages) {
                changePage(page);
                input.value = '';
            } else {
                alert(`Please enter a valid page number between 1 and ${totalPages}`);
            }
        }

        document.getElementById('gotoPageInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                gotoPage();
            }
        });

        document.getElementById('perPageSelect').addEventListener('change', function() {
            perPage = parseInt(this.value);
            currentPage = 1;
            loadKaryawanData();
        });

        document.getElementById('searchInput').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                searchQuery = this.value;
                currentPage = 1;
                loadKaryawanData();
            }, 500);
        });

        document.addEventListener('DOMContentLoaded', function() {
            loadKaryawanData();
        });
    </script>
    @livewireScripts();
@endsection
