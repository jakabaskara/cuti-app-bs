@extends('admin.layout.main')

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/notifications/css/lobibox.min.css') }}" />
    <link href="{{ asset('assets/plugins/datatables/datatables.min.css') }}" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css">
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h5>Data User</h5>
                    <div class="row">
                        <div class="col">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                                + Tambah User
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
                                    <th class="text-dark">Username</th>
                                    <th class="text-dark">ID Karyawan</th>
                                    <th class="text-dark">Nama Karyawan</th>
                                    <th class="text-dark">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                <tr>
                                    <td colspan="5" class="text-center">Loading...</td>
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




    <!-- Modal add User -->
    <div class="modal fade" id="exampleModal" aria-labelledby="exampleModalLabel">
        <form method="post" action="{{ route('tambahUser') }}">
            @csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Form Penambahan User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" name="username" required />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="password" id="password" required />
                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="togglePasswordVisibility()">
                                        <i id="password-icon" class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="id_karyawan" class="form-label">ID Karyawan</label>
                                <select class="form-select" id="select2" style="display: none; width: 100%"
                                    aria-label="ID Karyawan" name="id_karyawan" required>
                                    <option value="" disabled selected>Loading...</option>
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
    <div class="modal fade" id="editUserModal" aria-labelledby="editUserModalLabel">
        <form id="editUserForm" method="post" action="{{ route('updateUser') }}">
            @csrf
            @method('PUT')

            <input type="hidden" name="id" id="editUserId">

            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserModalLabel">Form Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Form fields for editing User -->
                        <div class="row mb-3">
                            <div class="col">
                                <label for="editusername" class="form-label">Username</label>
                                <input type="text" class="form-control" id="editusername" name="username" readonly />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="editid_karyawan" class="form-label">ID Karyawan</label>
                                <select class="form-select" id="editid_karyawan" style="display: none; width: 100%"
                                    aria-label="ID Karyawan" name="id_karyawan" required>
                                    <option value="" disabled selected>Loading...</option>
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
                    <p>Apakah Anda yakin ingin menghapus data user <b><span id="employeeName"></span></b>?</p>
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
            $('.flatpickr1').flatpickr({
                dateFormat: "Y-m-d",
            });

            loadKaryawanForSelect();

            $('#select2').select2({
                dropdownParent: $('#exampleModal .modal-content')
            });
            $('#editid_karyawan').select2({
                dropdownParent: $('#editUserModal .modal-content')
            });
        });

        function loadKaryawanForSelect() {
            fetch('{{ route('admin.user.karyawan-select') }}')
                .then(response => response.json())
                .then(data => {
                    let options = '<option value="" disabled selected>Pilih Karyawan</option>';
                    data.forEach(karyawan => {
                        options += `<option value="${karyawan.id}">${karyawan.nik} - ${karyawan.nama}</option>`;
                    });
                    $('#select2').html(options);
                    $('#editid_karyawan').html(options);
                })
                .catch(error => {
                    console.error('Error loading karyawan:', error);
                });
        }

        function togglePasswordVisibility() {
            const passwordField = document.getElementById('password');
            const passwordIcon = document.getElementById('password-icon');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                passwordIcon.classList.remove('bi-eye');
                passwordIcon.classList.add('bi-eye-slash');
            } else {
                passwordField.type = 'password';
                passwordIcon.classList.remove('bi-eye-slash');
                passwordIcon.classList.add('bi-eye');
            }
        }

        function editUser(id) {
            $.ajax({
                method: 'GET',
                url: `/admin/user/${id}/edit`, // Make sure this URL matches your route
                success: function(data) {
                    // Fill modal form with data
                    $('#editUserId').val(data.id);
                    $('#editusername').val(data.username);
                    $('#editid_karyawan').val(data.id_karyawan).trigger('change');
                    // Show modal
                    $('#editUserModal').modal('show');
                }
            });
        }

        function confirmDelete(id, name) {
            $('#employeeName').text(name); // Set the employee name in the modal
            $('#deleteEmployeeForm').attr('action', `/admin/delete-user/${id}`); // Set the form action URL
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

        function loadUserData() {
            const tableBody = document.getElementById('tableBody');
            tableBody.innerHTML = '<tr><td colspan="5" class="text-center">Loading...</td></tr>';

            fetch(
                    `{{ route('admin.user.data') }}?page=${currentPage}&per_page=${perPage}&search=${encodeURIComponent(searchQuery)}`
                    )
                .then(response => response.json())
                .then(data => {
                    renderTable(data);
                    renderPagination(data);
                    renderInfo(data);
                })
                .catch(error => {
                    console.error('Error:', error);
                    tableBody.innerHTML =
                        '<tr><td colspan="5" class="text-center text-danger">Error loading data</td></tr>';
                });
        }

        function renderTable(data) {
            const tableBody = document.getElementById('tableBody');
            if (data.data.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="5" class="text-center">No data found</td></tr>';
                return;
            }

            let html = '';
            data.data.forEach((item, index) => {
                const no = ((data.current_page - 1) * data.per_page) + index + 1;
                const namaKaryawan = item.karyawan ? item.karyawan.nama : '-';
                html += `
                    <tr class="text-center align-middle">
                        <th>${no}</th>
                        <td>${item.username}</td>
                        <td>${item.id_karyawan || '-'}</td>
                        <td>${namaKaryawan}</td>
                        <td>
                            <button class="btn btn-sm px-2 py-0 m-0 btn-warning"
                                onclick="editUser(${item.id})" data-bs-toggle="modal"
                                data-bs-target="#editUserModal">
                                <span class="material-icons">edit_note</span>
                            </button>
                            <button class="btn btn-sm px-2 py-0 m-0 btn-danger"
                                onclick="confirmDelete(${item.id}, '${namaKaryawan}')">
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
            loadUserData();
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
            loadUserData();
        });

        document.getElementById('searchInput').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                searchQuery = this.value;
                currentPage = 1;
                loadUserData();
            }, 500);
        });

        document.addEventListener('DOMContentLoaded', function() {
            loadUserData();
        });
    </script>
    @livewireScripts();
@endsection
