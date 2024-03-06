@extends('admin.layout.main')

@section('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link href="{{ asset('assets/plugins/datatables/datatables.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .table-container {
            max-height: 500px;
            /* Atur ketinggian maksimum sesuai kebutuhan */
            overflow-y: auto;
            /* Biarkan tabel di-scroll secara vertikal ketika melebihi ketinggian maksimum */
        }
    </style>
    @livewireStyles()
@endsection

@section('content')
    <h3 class="mb-4">Halo, Admin 👋</h3>

    <div class="row">
        <div class="col-xl-4">
            <div class="card widget widget-stats">
                <div class="card-body">
                    <div class="widget-stats-container d-flex">
                        <div class="widget-stats-icon widget-stats-icon-info">
                            <i class="material-icons-outlined">person</i>
                        </div>
                        <div class="widget-stats-content flex-fill">
                            <span class="widget-stats-title text-dark">Karyawan</span>
                            <span class="widget-stats-amount">100</span>
                            <span class="widget-stats-info">Form Cuti Disetujui</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card widget widget-stats">
                <div class="card-body">
                    <div class="widget-stats-container d-flex">
                        <div class="widget-stats-icon widget-stats-icon-warning">
                            <i class="material-icons-outlined">info</i>
                        </div>
                        <div class="widget-stats-content flex-fill">
                            <span class="widget-stats-title text-dark">Pending</span>
                            <span class="widget-stats-amount"></span>
                            <span class="widget-stats-info">Form Cuti Menunggu Respon</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card widget widget-stats">
                <div class="card-body">
                    <div class="widget-stats-container d-flex">
                        <div class="widget-stats-icon widget-stats-icon-danger">
                            <i class="material-icons-outlined">highlight_off</i>
                        </div>
                        <div class="widget-stats-content flex-fill">
                            <span class="widget-stats-title text-dark">Dibatalkan</span>
                            <span class="widget-stats-amount"></span>
                            <span class="widget-stats-info">Form Cuti Ditolak</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="row">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="text-center">Daftar Sisa Cuti Karyawan</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="tableData1">
                                <thead class="table-dark">
                                    <tr class="text-center align-middle">
                                        <th>No.</th>
                                        <th>NIK SAP</th>
                                        <th>Nama</th>
                                        <th>Sisa<br>Cuti<br>Tahunan</th>
                                        <th>Sisa<br>Cuti<br>Panjang</th>
                                        <th>Jumlah</th>
                                        {{-- <th>Periode Cuti</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                    @endphp
                                    @foreach ($sisaCutis as $sisaCuti)
                                        <tr class="text-center align-middle">
                                            <td>{{ $i }}</td>
                                            <td>{{ $sisaCuti->NIK }}</td>
                                            <td class="text-start">{{ $sisaCuti->nama }}</td>
                                            <td>{{ $sisaCuti->sisa_cuti_tahunan }}</td>
                                            <td>{{ $sisaCuti->sisa_cuti_panjang }}</td>
                                            <td>{{ $sisaCuti->sisa_cuti_tahunan + $sisaCuti->sisa_cuti_panjang }}</td>
                                        </tr>
                                        @php
                                            $i++;
                                        @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card" style="min-height: 700px">
                    <div class="card-header">
                        <h5 class="text-center">Karyawan Cuti</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-container">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">No.</th>
                                        <th>Nama</th>
                                        <th>Alasan Cuti</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                    @endphp
                                    @foreach ($karyawanCuti as $cuti)
                                        <tr>
                                            <td class="text-center">{{ $i }}</td>
                                            <td>{{ $cuti->karyawan->nama }}</td>
                                            <td>{{ $cuti->alasan }}</td>
                                        </tr>
                                        @php
                                            $i++;
                                        @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="row mt-1">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                                + Ajukan Cuti
                            </button>
                        </div>
                        <div class="col">
                            <a href="{{ route('admin.download.pdf') }}" class="btn btn-primary">PDF</a>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm table-hover">
                                    <thead class="table-dark">
                                        <tr class="text-center">
                                            <th>No.</th>
                                            <th>NIK</th>
                                            <th>Nama</th>
                                            <th>Jenis Cuti</th>
                                            <th>Periode Tanggal</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Modal -->
    {{-- <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Form Pengajuan Cuti</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col">
                            <label for="nama" class="form-label">Nama Karyawan</label>
                            <select class="form-select" aria-label="Nama Karyawan">
                                <option selected value=""> </option>
                                @foreach ($karyawans as $karyawan)
                                    <option value="{{ $karyawan->id }}">{{ $karyawan->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="nama" class="form-label">Jenis Cuti</label>
                            <select class="form-select" aria-label="Nama Karyawan">
                                <option selected value=""> </option>
                                <option value="1">Cuti Tahunan</option>
                                <option value="2">Cuti Panjang</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="daterange" class="form-label">Tanggal Cuti</label>
                            <input type="text" class="form-control flatpickr1" name="daterange" value="" />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="alasan" class="form-label">Alasan Cuti</label>
                            <input type="text" class="form-control" name="alasan" value="" />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="alamat" class="form-label">Alamat</label>
                            <input type="text" class="form-control" name="alamat" value="" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batalkan</button>
                    <form action="{{ route('admin.tambahCuti') }}" method="post">
                        @csrf
                        <button type="submit" class="btn btn-primary">Ajukan</button>
                    </form>
                </div>
            </div>
        </div>
    </div> --}}
@endsection

@section('script')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script src="{{ asset('assets/plugins/datatables/datatables.min.js') }}"></script>

    <script>
        // $(function() {
        //     $('input[name="daterange"]').daterangepicker({
        //         opens: 'left'
        //     }, function(start, end, label) {
        //         console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end
        //             .format('YYYY-MM-DD'));
        //     });

        //     $('#datatable2').DataTable();
        // });

        flatpickr('.flatpickr1', {
            mode: 'range',
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length >= 2) {
                    var startDate = selectedDates[0];
                    var endDate = selectedDates[selectedDates.length - 1];

                    // Hitung selisih dalam milidetik
                    var difference = endDate.getTime() - startDate.getTime();

                    // Konversi selisih ke jumlah hari
                    var daysDifference = Math.ceil(difference / (1000 * 60 * 60 * 24));

                    document.getElementById("jumlah-hari").textContent = "Jumlah Hari: " + daysDifference;
                }
            }
        });
    </script>
@endsection
