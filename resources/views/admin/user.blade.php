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
                            <tbody>
                                @forelse ($users as $user)
                                    <tr class="text-center align-middle">
                                        <th>{{ $loop->iteration }}</th>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ $user->id_karyawan }}</td>
                                        <td>{{ $user->karyawan->nama }}</td>
                                        <td class=" ">
                                            <div class="row">
                                                <div class="col">
                                                    <button class="btn btn-sm px-2 py-0 m-0 btn-warning"
                                                        onclick="editUser({{ $user->id }})" data-bs-toggle="modal"
                                                        data-bs-target="#editUserModal">
                                                        <span class="material-icons">edit_note</span>
                                                    </button>
                                                    <button class="btn btn-sm px-2 py-0 m-0 btn-danger"
                                                        onclick="confirmDelete({{ $user->id }}, '{{ $user->karyawan->nama }}')">
                                                        <span class="material-icons">delete</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Data Not Found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
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
                                    <option value="" disabled selected>Pilih Karyawan</option>
                                    @foreach ($karyawans as $karyawan)
                                        <option value="{{ $karyawan->id }}">{{ $karyawan->NIK }} - {{ $karyawan->nama }}
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
                                    <option value="" disabled selected>Pilih Karyawan</option>
                                    @foreach ($karyawans as $karyawan)
                                        <option value="{{ $karyawan->id }}">{{ $karyawan->NIK }} - {{ $karyawan->nama }}
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
            $('#tableData1').DataTable();

            $('.flatpickr1').flatpickr({
                dateFormat: "Y-m-d",
            });
            $('#select2').select2({
                dropdownParent: $('#exampleModal .modal-content')
            });
            $('#editid_karyawan').select2({
                dropdownParent: $('#editUserModal .modal-content')
            });
        });

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
    </script>
    @livewireScripts();
@endsection
