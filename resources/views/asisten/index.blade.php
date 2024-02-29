@extends('asisten.layout.main')

@section('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link href="{{ asset('assets/plugins/datatables/datatables.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <h3 class="mb-4">Halo, PIC Bagian SDM & Sistem Manajemen 👋</h3>
    <div class="row">
        <div class="col-xl-4">
            <div class="card widget widget-stats">
                <div class="card-body">
                    <div class="widget-stats-container d-flex">
                        <div class="widget-stats-icon widget-stats-icon-primary">
                            <i class="material-icons-outlined">person</i>
                        </div>
                        <div class="widget-stats-content flex-fill">
                            <span class="widget-stats-title text-dark">Karyawan</span>
                            <span class="widget-stats-amount">108</span>
                            <span class="widget-stats-info">Jumlah Karyawan</span>
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
                            <span class="widget-stats-title text-dark"> Pending</span>
                            <span class="widget-stats-amount">3</span>
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
                        <div class="widget-stats-icon widget-stats-icon-success">
                            <i class="material-icons-outlined">check_circle</i>
                        </div>
                        <div class="widget-stats-content flex-fill">
                            <span class="widget-stats-title text-dark">Form Cuti</span>
                            <span class="widget-stats-amount">14</span>
                            <span class="widget-stats-info">Form Cuti Dibuat</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h3>Persetujuan Cuti</h3>
                    <hr>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover table-striped">
                            <thead>
                                <tr class="text-center">
                                    <th class="text-dark"><strong>NIK</strong></th>
                                    <th class="text-dark"><strong>Nama</strong></th>
                                    <th class="text-dark"><strong>Periode Cuti</strong></th>
                                    <th class="text-dark"><strong>Jumlah Cuti</strong></th>
                                    <th class="text-dark"><strong>Jumlah Cuti</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="text-center">
                                    <td class="text-dark"></td>
                                    <td class="text-dark"></td>
                                    <td class="text-dark"></td>
                                    <td class="text-dark"></td>

                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="">Daftar Sisa Cuti Karyawan</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="display table-hover" id="datatable2">
                            <thead class="table-dark">
                                <tr class="text-center align-middle">
                                    <th class="text-dark">No.</th>
                                    <th class="text-dark">NIK SAP</th>
                                    <th class="text-dark">Nama</th>
                                    <th class="text-dark">Sisa Cuti Tahunan</th>
                                    <th class="text-dark">Sisa Cuti Panjang</th>
                                    <th class="text-dark">Periode Cuti</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                {{-- @foreach ($karyawans as $karyawan)
                                    <tr class="text-center">
                                        <td>{{ $i }}</td>
                                        <td>{{ $karyawan->NIK }}</td>
                                        <td>{{ $karyawan->nama }}</td>
                                        <td>0</td>
                                        <td>{{ $karyawan->sisaCutiPanjang->isEmpty() ? 0 : $karyawan->sisaCutiPanjang->first()->sisa_cuti }}
                                        </td>
                                        <td></td>
                                    </tr>
                                    @php
                                        $i++;
                                    @endphp
                                @endforeach --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h5 class="text-center">Karyawan Cuti Hari Ini</h5>
                </div>
                <div class="card-body" style="min-height: 300px">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">No.</th>
                                    <th>Nama</th>
                                    <th>Alasan Cuti</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">1.</td>
                                    <td>Jeno</td>
                                    <td>Urusan Keluarga</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-1">
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
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                {{-- @foreach ($karyawans as $karyawan)
                                    <option value="{{ $karyawan->id }}">{{ $karyawan->nama }}</option>
                                @endforeach --}}
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
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="{{ asset('assets/plugins/datatables/datatables.min.js') }}"></script>

    <script>
        $(function() {
            $('input[name="daterange"]').daterangepicker({
                opens: 'left'
            }, function(start, end, label) {
                console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end
                    .format('YYYY-MM-DD'));
            });

            $('#datatable2').DataTable();
        });
    </script>
@endsection
