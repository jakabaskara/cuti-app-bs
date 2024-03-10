@extends('kabag.layout.main')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/plugins/notifications/css/lobibox.min.css') }}" />
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link href="{{ asset('assets/plugins/datatables/datatables.min.css') }}" rel="stylesheet">
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
    <h3 class="mb-4">Halo, {{ $nama }} 👋</h3>

    @livewire('kabag-status-bar-index')

    {{-- <div class="row">
        <div class="col-xl-4">
            <div class="card widget widget-stats">
                <div class="card-body">
                    <div class="widget-stats-container d-flex">
                        <div class="widget-stats-icon widget-stats-icon-success">
                            <i class="material-icons-outlined">check_circle</i>
                        </div>
                        <div class="widget-stats-content flex-fill">
                            <span class="widget-stats-title text-dark">Disetujui</span>
                            <span class="widget-stats-amount">{{ $disetujui }}</span>
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
                            <span class="widget-stats-amount">{{ $pending }}</span>
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
                            <span class="widget-stats-amount">{{ $ditolak }}</span>
                            <span class="widget-stats-info">Form Cuti Ditolak</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h3>Persetujuan Cuti</h3>
                    <hr>
                </div>
                <div class="card-body">
                    @livewire('kabag-table-persetujuan-cuti')
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

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h5>Daftar Riwayat Cuti</h5>
                    <hr>
                </div>
                <div class="card-body">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="tableData2">
                                <thead class="table-dark">
                                    <tr class="text-center align-middle">
                                        <th>No.</th>
                                        <th>NIK SAP</th>
                                        <th>Nama</th>
                                        <th>Jenis Cuti</th>
                                        <th>Jumlah Hari</th>
                                        <th>Periode Tanggal</th>
                                        <th>Alasan</th>
                                        <th>Alamat</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                    @endphp
                                    @foreach ($permintaanCutis as $permintaanCuti)
                                        <tr class="text-center align-middle">
                                            <td>{{ $i }}</td>
                                            <td>{{ $permintaanCuti->karyawan->NIK }}</td>
                                            <td class="text-start">{{ $permintaanCuti->karyawan->nama }}</td>
                                            <td class="text-center">{{ '' }}</td>
                                            <td class="text-center">{{ $permintaanCuti->jumlah_hari_cuti }}</td>
                                            <td class="text-center">
                                                {{ date('d-M', strtotime($permintaanCuti->tanggal_mulai)) . ' s.d ' . date('d-M', strtotime($permintaanCuti->tanggal_selesai)) }}
                                            </td>
                                            <td class="text-dark">{{ $permintaanCuti->alasan }}</td>
                                            <td class="text-dark">{{ $permintaanCuti->alamat }}</td>
                                            {{-- <td>{{ $permintaanCuti->karyawan->sisacuti->sisa_cuti_tahunan }}</td>
                                            <td>{{ $permintaanCuti->karyawan->sisacuti->sisa_cuti_panjang }}</td>
                                            <td>{{ $permintaanCuti->karyawan->sisacuti->sisa_cuti_tahunan + $permintaanCuti->sisa_cuti_panjang }} --}}
                                            </td>
                                            {{-- <td>{{ $permintaanCuti->karyawan->sisa_cuti_tahunan }}</td> --}}

                                            </td>
                                            @if ($permintaanCuti->is_approved == 1)
                                                <td class="text-dark"> <span
                                                        class="badge badge-success p-2">Disetujui</span>
                                                </td>
                                            @elseif ($permintaanCuti->is_rejected == 1)
                                                <td class="text-dark"> <span class="badge badge-danger p-2">Ditolak</span>
                                                </td>
                                            @else
                                                <td class="text-dark"> <span class="badge badge-warning p-2">Pending</span>
                                                </td>
                                                <td class="">
                                                    <form id="deleteForm{{ $permintaanCuti->id }}"
                                                        action="{{ route('kerani.delete-cuti', $permintaanCuti->id) }}"
                                                        method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </td>
                                            @endif
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
                <div class="card-header">
                    <h5 class="">Daftar Riwayat Cuti</h5>
                </div>
                <div class="card-body">
                    <div>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover table-striped" id="dataTable2">
                                <thead>
                                    <tr class="text-center">
                                        <th class="text-dark">No.</th>
                                        <th class="text-dark">NIK</th>
                                        <th class="text-dark">Nama</th>
                                        <th class="text-dark">Jenis Cuti</th>
                                        <th class="text-dark">Jumlah<br>Hari</th>
                                        <th class="text-dark">Periode Tanggal</th>
                                        <th class="text-dark">Alasan</th>
                                        <th class="text-dark">Alamat</th>
                                        <th class="text-dark">Status</th>
                                        <th class="text-dark">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                    @endphp
                                    @foreach ($riwayats as $riwayat)
                                        <tr class="text-center">
                                            <td class="text-dark">{{ $i }}</td>
                                            <td class="text-dark">{{ $riwayat->karyawan->NIK }}</td>
                                            <td class="text-dark">{{ $riwayat->karyawan->nama }}</td>
                                            <td class="text-dark">{{ $riwayat->jenisCuti->jenis_cuti }}</td>
                                            <td class="text-dark">{{ $riwayat->jumlah_hari_cuti }}</td>
                                            <td class="text-dark">
                                                {{ date('d-M', strtotime($riwayat->tanggal_mulai)) . ' s.d ' . date('d-M', strtotime($riwayat->tanggal_selesai)) }}
                                            </td>
                                            <td class="text-dark">{{ $riwayat->alasan }}</td>
                                            <td class="text-dark">{{ $riwayat->alamat }}</td>
                                            @if ($riwayat->is_approved == 1)
                                                <td class="text-dark"> <span
                                                        class="badge badge-success p-2">Disetujui</span>
                                                </td>
                                                <td class="">
                                                    <a href="{{ route('kerani.download.pdf', $riwayat->id) }}"
                                                        class="btn btn-sm btn-success px-1 py-0">
                                                        <span class="material-icons text-sm p-0 align-middle">
                                                            description
                                                        </span>
                                                    </a>
                                                </td>
                                            @elseif ($riwayat->is_rejected == 1)
                                                <td class="text-dark"> <span class="badge badge-danger p-2">Ditolak</span>
                                                </td>
                                                <td class="">
                                                    <button class="btn btn-sm btn-info px-1 py-0">
                                                        <span class="material-icons text-sm p-0 align-middle">
                                                            info
                                                        </span>
                                                    </button>
                                                </td>
                                            @else
                                                <td class="text-dark"> <span class="badge badge-warning p-2">Pending</span>
                                                </td>
                                                <td class="">
                                                    <form id="deleteForm{{ $riwayat->id }}"
                                                        action="{{ route('kerani.delete-cuti', $riwayat->id) }}"
                                                        method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                    <a href="#"
                                                        onclick="event.preventDefault(); document.getElementById('deleteForm{{ $riwayat->id }}').submit();"
                                                        class="btn btn-sm btn-danger px-1 py-0">
                                                        <span class="material-icons text-sm p-0 align-middle">delete</span>
                                                    </a>
                                                </td>
                                            @endif
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
    </div> --}}
@endsection

@section('script')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="{{ asset('assets/plugins/notifications/js/lobibox.min.js') }}"></script>

    <script src="{{ asset('assets/plugins/datatables/datatables.min.js') }}"></script>

    <script>
        $('#tableData1').DataTable();
        $('#tableData2').DataTable();

        $(function() {
            $('input[name="daterange"]').daterangepicker({
                opens: 'left'
            }, function(start, end, label) {
                console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end
                    .format('YYYY-MM-DD'));
            });

            $('#datatable2').DataTable();
        });

        function round_success_noti() {
            Lobibox.notify('success', {
                pauseDelayOnHover: true,
                size: 'mini',
                rounded: true,
                icon: 'bx bx-check-circle',
                delayIndicator: false,
                continueDelayOnInactiveTab: false,
                position: 'top right',
                msg: 'Cuti Disetujui!'
            });
        }

        $('.noti').on('click', function() {
            round_success_noti();
        })


        function round_danger_notis() {
            Lobibox.notify('danger', {
                pauseDelayOnHover: true,
                size: 'mini',
                rounded: true,
                icon: 'bx bx-check-circle',
                delayIndicator: false,
                continueDelayOnInactiveTab: false,
                position: 'top right',
                msg: 'Cuti Ditolak!'
            });
        }

        $('.noti').on('click', function() {
            round_danger_notis();
        })
    </script>

    @livewireScripts()
@endsection
