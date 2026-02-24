@extends('admin.layout.main')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/plugins/notifications/css/lobibox.min.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables/datatables.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h5>Data Sisa Cuti</h5>
                    <div class="row">
                        <div class="col">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#tambahCutiModal">
                                + Tambah Sisa Cuti
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
                                    <th rowspan="2" class="text-dark">No.</th>
                                    <th rowspan="2" class="text-dark">NIK</th>
                                    <th rowspan="2" class="text-dark">Nama</th>
                                    <th rowspan="2" class="text-dark">Unit Kerja</th>
                                    <th colspan="3" class="text-dark">Sisa Cuti</th>
                                    <th rowspan="2" class="text-dark">Aksi</th>
                                </tr>
                                <tr class="text-center align-middle">
                                    <th class="text-dark">Cuti Tahunan</th>
                                    <th class="text-dark">Cuti Panjang</th>
                                    <th class="text-dark">Jumlah Dapat Dipakai</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                <tr>
                                    <td colspan="8" class="text-center">Loading...</td>
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

    <!-- Modal Tambah Sisa Cuti -->
    <div class="modal fade" id="tambahCutiModal" aria-labelledby="tambahCutiModalLabel">
        <form method="post" action="{{ route('tambahCuti') }}">
            @csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tambahCutiModalLabel">Form Penambahan Cuti Karyawan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="id_karyawan" class="form-label">ID Karyawan</label>
                            <select class="form-select" id="select2" style="display: none; width: 100%" name="id_karyawan"
                                required>
                                <option value="" disabled selected>Loading...</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="id_jenis_cuti[]" class="form-label">Jenis Cuti 1</label>
                            <select class="form-select" id="id_jenis_cuti1" name="id_jenis_cuti[]" required>
                                <option value="" disabled selected>Pilih Jenis Cuti</option>
                                @foreach ($jenisCutis as $jenisCuti)
                                    <option value="{{ $jenisCuti->id }}">
                                        {{ $jenisCuti->id }} - {{ $jenisCuti->jenis_cuti }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="periode_mulai[]" class="form-label">Periode Mulai</label>
                            <input type="date" class="form-control flatpickr1" id="periode_mulai1"
                                name="periode_mulai[]" required>
                        </div>
                        <div class="mb-3">
                            <label for="periode_akhir[]" class="form-label">Periode Akhir</label>
                            <input type="date" class="form-control flatpickr1" id="periode_akhir1"
                                name="periode_akhir[]" required>
                        </div>
                        <div class="mb-3">
                            <label for="jumlah[]" class="form-label">Jumlah</label>
                            <input type="number" class="form-control" id="jumlah1" name="jumlah[]" required>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <label for="id_jenis_cuti[]" class="form-label">Jenis Cuti 2</label>
                            <select class="form-select" id="id_jenis_cuti2" name="id_jenis_cuti[]">
                                <option value="" disabled selected>Pilih Jenis Cuti</option>
                                @foreach ($jenisCutis as $jenisCuti)
                                    <option value="{{ $jenisCuti->id }}">
                                        {{ $jenisCuti->id }} - {{ $jenisCuti->jenis_cuti }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="periode_mulai[]" class="form-label">Periode Mulai</label>
                            <input type="date" class="form-control flatpickr1" id="periode_mulai2"
                                name="periode_mulai[]">
                        </div>
                        <div class="mb-3">
                            <label for="periode_akhir[]" class="form-label">Periode Akhir</label>
                            <input type="date" class="form-control flatpickr1" id="periode_akhir2"
                                name="periode_akhir[]">
                        </div>
                        <div class="mb-3">
                            <label for="jumlah[]" class="form-label">Jumlah</label>
                            <input type="number" class="form-control" id="jumlah2" name="jumlah[]">
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




    <!-- Modal Edit Sisa Cuti -->
    <div class="modal fade" id="editCutiModal" aria-labelledby="editCutiModalLabel">
        <form id="editCutiForm" method="post" action="{{ route('updateCuti') }}">
            @csrf
            @method('PUT')

            <input type="hidden" name="id" id="editCutiId">


            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCutiModalLabel">Form Edit Cuti Karyawan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editid_karyawan" class="form-label">ID Karyawan</label>
                            <select class="form-select" id="editid_karyawan" style="display: none; width: 100%"
                                name="id_karyawan" required>
                                <option value="" disabled selected>Loading...</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editid_jenis_cuti[]" class="form-label">Jenis Cuti 1</label>
                            <select class="form-select" id="editid_jenis_cuti1" name="id_jenis_cuti[]" required>
                                <option value="" disabled selected>Pilih Jenis Cuti</option>
                                @foreach ($jenisCutis as $jenisCuti)
                                    <option value="{{ $jenisCuti->id }}">
                                        {{ $jenisCuti->id }} - {{ $jenisCuti->jenis_cuti }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editperiode_mulai[]" class="form-label">Periode Mulai</label>
                            <input type="date" class="form-control flatpickr1" id="editperiode_mulai1"
                                name="periode_mulai[]" required>
                        </div>
                        <div class="mb-3">
                            <label for="editperiode_akhir[]" class="form-label">Periode Akhir</label>
                            <input type="date" class="form-control flatpickr1" id="editperiode_akhir1"
                                name="periode_akhir[]" required>
                        </div>
                        <div class="mb-3">
                            <label for="editjumlah[]" class="form-label">Jumlah</label>
                            <input type="number" class="form-control" id="editjumlah1" name="jumlah[]" required>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <label for="editid_jenis_cuti[]" class="form-label">Jenis Cuti 2</label>
                            <select class="form-select" id="editid_jenis_cuti2" name="id_jenis_cuti[]">
                                <option value="" disabled selected>Pilih Jenis Cuti</option>
                                @foreach ($jenisCutis as $jenisCuti)
                                    <option value="{{ $jenisCuti->id }}">
                                        {{ $jenisCuti->id }} - {{ $jenisCuti->jenis_cuti }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editperiode_mulai[]" class="form-label">Periode Mulai</label>
                            <input type="date" class="form-control flatpickr1" id="editperiode_mulai2"
                                name="periode_mulai[]">
                        </div>
                        <div class="mb-3">
                            <label for="editperiode_akhir[]" class="form-label">Periode Akhir</label>
                            <input type="date" class="form-control flatpickr1" id="editperiode_akhir2"
                                name="periode_akhir[]">
                        </div>
                        <div class="mb-3">
                            <label for="editjumlah[]" class="form-label">Jumlah</label>
                            <input type="number" class="form-control" id="editjumlah2" name="jumlah[]">
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







    <!-- Modal Delete-->
    <div class="modal fade" id="hapusCutiModal" tabindex="-1" aria-labelledby="hapusCutiModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="hapusCutiModalLabel">Hapus Semua Sisa Cuti</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus semua sisa cuti untuk karyawan <b><span
                                id="employeeName"></span></b>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batalkan</button>
                    <form id="hapusCutiForm" action="" method="post" style="display: inline;">
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
    <script src="{{ asset('assets/plugins/notifications/js/lobibox.min.js') }}"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
                dropdownParent: $('#tambahCutiModal .modal-content')
            });
            $('#editid_karyawan').select2({
                dropdownParent: $('#editCutiModal .modal-content')
            });
        });

        function loadKaryawanForSelect() {
            fetch('{{ route('admin.sisacuti.karyawan-select') }}')
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


        // function editCuti(id_karyawan) {
        //     $.ajax({
        //         method: 'GET',
        //         url: `/admin/sisacuti/${id_karyawan}/edit`,
        //         success: function(data) {
        //             // Clear existing data in the modal
        //             $('#editCutiForm').find('input[type="text"], input[type="date"], select').val('');
        //             // Set id_karyawan value
        //             $('#editid_karyawan').val(data[0].id_karyawan).trigger('change');
        //             // Iterate through the data and fill the modal
        //             data.forEach((cuti, index) => {
        //                 $(`#editid_jenis_cuti${index + 1}`).val(cuti.id_jenis_cuti);
        //                 $(`#editperiode_mulai${index + 1}`).val(cuti.periode_mulai);
        //                 $(`#editperiode_akhir${index + 1}`).val(cuti.periode_akhir);
        //                 $(`#editjumlah${index + 1}`).val(cuti.jumlah);
        //             });

        //             // Show modal
        //             $('#editCutiModal').modal('show');
        //         }
        //     });
        // }

        function editCuti(id_karyawan) {
            $.ajax({
                method: 'GET',
                url: `/admin/sisacuti/${id_karyawan}/edit`,
                success: function(data) {
                    // Clear existing data in the modal
                    $('#editCutiForm').find('input[type="text"], input[type="date"], select').val('');
                    // Set id_karyawan value
                    $('#editid_karyawan').val(data[0].id_karyawan).trigger('change');
                    // Iterate through the data and fill the modal
                    data.forEach((cuti, index) => {
                        $(`#editid_jenis_cuti${index + 1}`).val(cuti.id_jenis_cuti);
                        $(`#editperiode_mulai${index + 1}`).val(cuti.periode_mulai);
                        $(`#editperiode_akhir${index + 1}`).val(cuti.periode_akhir);
                        if (cuti.jumlah !== undefined && cuti.jumlah !== null) {
                            $(`#editjumlah${index + 1}`).val(cuti.jumlah);
                        } else {
                            $(`#editjumlah${index + 1}`).val(''); // kosongkan data jika tidak ada
                        }
                    });

                    // Kosongkan data jumlah jika data tidak ada
                    for (let i = data.length + 1; i <= 2; i++) {
                        $(`#editjumlah${i}`).val('');
                    }

                    // Show modal
                    $('#editCutiModal').modal('show');
                }
            });
        }

        function confirmDelete(id_karyawan, name) {
            $('#employeeName').text(name); // Set the employee name in the modal
            $('#hapusCutiForm').attr('action', `/admin/delete-sisacuti/${id_karyawan}`); // Set the form action URL
            $('#hapusCutiModal').modal('show');
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





        document.addEventListener('DOMContentLoaded', function() {
            function updateEndDate() {
                const jenisCuti1 = document.getElementById('id_jenis_cuti1');
                const periodeMulai1 = document.getElementById('periode_mulai1');
                const periodeAkhir1 = document.getElementById('periode_akhir1');

                const jenisCuti2 = document.getElementById('id_jenis_cuti2');
                const periodeMulai2 = document.getElementById('periode_mulai2');
                const periodeAkhir2 = document.getElementById('periode_akhir2');

                // Fungsi untuk mengatur tanggal akhir berdasarkan jenis cuti
                function setEndDate(jenisCuti, periodeMulai, periodeAkhir) {
                    if (jenisCuti && periodeMulai && periodeAkhir) {
                        const startDate = new Date(periodeMulai.value);
                        let endDate = new Date(startDate);

                        if (jenisCuti.value === '2') { // Misalkan ID 1 adalah Cuti Tahunan
                            endDate.setFullYear(startDate.getFullYear() + 1);
                        } else if (jenisCuti.value === '1') { // Misalkan ID 2 adalah Cuti Panjang
                            endDate.setFullYear(startDate.getFullYear() + 6);
                        }

                        // Set tanggal akhir ke format YYYY-MM-DD
                        const year = endDate.getFullYear();
                        const month = ('0' + (endDate.getMonth() + 1)).slice(-2);
                        const day = ('0' + endDate.getDate()).slice(-2);
                        periodeAkhir.value = `${year}-${month}-${day}`;
                    }
                }

                // Event listeners untuk mengupdate periode akhir saat memilih jenis cuti atau mengubah periode mulai
                jenisCuti1.addEventListener('change', () => setEndDate(jenisCuti1, periodeMulai1,
                    periodeAkhir1));
                periodeMulai1.addEventListener('change', () => setEndDate(jenisCuti1, periodeMulai1,
                    periodeAkhir1));

                jenisCuti2.addEventListener('change', () => setEndDate(jenisCuti2, periodeMulai2,
                    periodeAkhir2));
                periodeMulai2.addEventListener('change', () => setEndDate(jenisCuti2, periodeMulai2,
                    periodeAkhir2));
            }

            updateEndDate();
        });

        document.addEventListener('DOMContentLoaded', function() {
            function updateEndDate() {
                const editJenisCuti1 = document.getElementById('editid_jenis_cuti1');
                const editPeriodeMulai1 = document.getElementById('editperiode_mulai1');
                const editPeriodeAkhir1 = document.getElementById('editperiode_akhir1');

                const editJenisCuti2 = document.getElementById('editid_jenis_cuti2');
                const editPeriodeMulai2 = document.getElementById('editperiode_mulai2');
                const editPeriodeAkhir2 = document.getElementById('editperiode_akhir2');

                // Fungsi untuk mengatur tanggal akhir berdasarkan jenis cuti
                function setEndDate(jenisCuti, periodeMulai, periodeAkhir) {
                    if (jenisCuti && periodeMulai && periodeAkhir) {
                        const startDate = new Date(periodeMulai.value);
                        let endDate = new Date(startDate);

                        if (jenisCuti.value === '2') { // Misalkan ID 1 adalah Cuti Tahunan
                            endDate.setFullYear(startDate.getFullYear() + 1);
                        } else if (jenisCuti.value === '1') { // Misalkan ID 2 adalah Cuti Panjang
                            endDate.setFullYear(startDate.getFullYear() + 6);
                        }

                        // Set tanggal akhir ke format YYYY-MM-DD
                        const year = endDate.getFullYear();
                        const month = ('0' + (endDate.getMonth() + 1)).slice(-2);
                        const day = ('0' + endDate.getDate()).slice(-2);
                        periodeAkhir.value = `${year}-${month}-${day}`;
                    }
                }

                // Event listeners untuk mengupdate periode akhir saat memilih jenis cuti atau mengubah periode mulai
                editJenisCuti1.addEventListener('change', () => setEndDate(editJenisCuti1, editPeriodeMulai1,
                    editPeriodeAkhir1));
                editPeriodeMulai1.addEventListener('change', () => setEndDate(editJenisCuti1, editPeriodeMulai1,
                    editPeriodeAkhir1));

                editJenisCuti2.addEventListener('change', () => setEndDate(editJenisCuti2, editPeriodeMulai2,
                    editPeriodeAkhir2));
                editPeriodeMulai2.addEventListener('change', () => setEndDate(editJenisCuti2, editPeriodeMulai2,
                    editPeriodeAkhir2));
            }

            updateEndDate();
        });



        $(document).ready(function() {
            // Hide options in dropdown2 based on selection in dropdown1
            $('#id_jenis_cuti1').change(function() {
                var selectedValue = $(this).val();
                $('#id_jenis_cuti2 option').each(function() {
                    if ($(this).val() == selectedValue) {
                        $(this).hide();
                    } else {
                        $(this).show();
                    }
                });
            });

            // Hide options in dropdown1 based on selection in dropdown2
            $('#id_jenis_cuti2').change(function() {
                var selectedValue = $(this).val();
                $('#id_jenis_cuti1 option').each(function() {
                    if ($(this).val() == selectedValue) {
                        $(this).hide();
                    } else {
                        $(this).show();
                    }
                });
            });

            // Similar code for the edit modal
            $('#editid_jenis_cuti1').change(function() {
                var selectedValue = $(this).val();
                $('#editid_jenis_cuti2 option').each(function() {
                    if ($(this).val() == selectedValue) {
                        $(this).hide();
                    } else {
                        $(this).show();
                    }
                });
            });

            $('#editid_jenis_cuti2').change(function() {
                var selectedValue = $(this).val();
                $('#editid_jenis_cuti1 option').each(function() {
                    if ($(this).val() == selectedValue) {
                        $(this).hide();
                    } else {
                        $(this).show();
                    }
                });
            });
        });

        let currentPage = 1;
        let perPage = 25;
        let searchQuery = '';
        let searchTimeout;
        let totalPages = 1;

        function loadSisaCutiData() {
            const tableBody = document.getElementById('tableBody');
            tableBody.innerHTML = '<tr><td colspan="8" class="text-center">Loading...</td></tr>';

            fetch(
                    `{{ route('admin.sisacuti.data') }}?page=${currentPage}&per_page=${perPage}&search=${encodeURIComponent(searchQuery)}`
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
                        '<tr><td colspan="8" class="text-center text-danger">Error loading data</td></tr>';
                });
        }

        function renderTable(data) {
            const tableBody = document.getElementById('tableBody');
            if (data.data.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="8" class="text-center">No data found</td></tr>';
                return;
            }

            let html = '';
            data.data.forEach((item, index) => {
                const no = ((data.current_page - 1) * data.per_page) + index + 1;
                html += `
                    <tr class="text-center align-middle">
                        <td>${no}</td>
                        <td>${item.nik || '-'}</td>
                        <td>${item.nama || '-'}</td>
                        <td>${item.unit_kerja || '-'}</td>
                        <td>${item.total_cuti_tahunan || 0}</td>
                        <td>${item.total_cuti_panjang || 0}</td>
                        <td>${item.total_cuti || 0}</td>
                        <td>
                            <button class="btn btn-sm px-2 py-0 m-0 btn-warning"
                                onclick="editCuti(${item.id_karyawan})" data-bs-toggle="modal"
                                data-bs-target="#editCutiModal">
                                <span class="material-icons">edit_note</span>
                            </button>
                            <button class="btn btn-sm px-2 py-0 m-0 btn-danger"
                                onclick="confirmDelete(${item.id_karyawan}, '${item.nama}')">
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
            loadSisaCutiData();
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
            loadSisaCutiData();
        });

        document.getElementById('searchInput').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                searchQuery = this.value;
                currentPage = 1;
                loadSisaCutiData();
            }, 500);
        });

        document.addEventListener('DOMContentLoaded', function() {
            loadSisaCutiData();
        });
    </script>
@endsection
