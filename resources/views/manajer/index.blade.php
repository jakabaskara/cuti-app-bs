@extends('manajer.layout.main')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/plugins/notifications/css/lobibox.min.css') }}" />
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link href="{{ asset('assets/plugins/datatables/datatables.min.css') }}" rel="stylesheet">
    @livewireStyles()
@endsection

@section('content')
    <h3 class="mb-4">Halo, {{ $nama }} 👋</h3>

    @livewire('manajer-status-bar-index')

    {{-- <div class="row">
        <div class="col-xl-4">
            <div class="card widget widget-stats">
                <div class="card-body">
                    <div class="widget-stats-container d-flex">
                        <div class="widget-stats-icon widget-stats-icon-primary">
                            <i class="material-icons-outlined">person</i>
                        </div>
                        <div class="widget-stats-content flex-fill">
                            <span class="widget-stats-title">Karyawan</span>
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
                            <span class="widget-stats-title">Pending</span>
                            <span class="widget-stats-amount">2</span>
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
                            <span class="widget-stats-title">Form Cuti</span>
                            <span class="widget-stats-amount">13</span>
                            <span class="widget-stats-info">Form Cuti Dibuat</span>
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
                        @livewire('karyawan-cuti-table')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h5>Daftar Cuti yang Disetujui</h5>
                    <hr>
                </div>
                <div class="card-body">

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Alasan Penolakan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-5 py-2">
                    @livewire('reject-cuti-form')
                    <button class="btn btn-danger mt-3 mb-3" id="btnTolak">Tolak Cuti</button>
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
    <script src="{{ asset('assets/plugins/datatables/datatables.min.js') }}"></script>

    <script>
        $('#tableData1').DataTable();

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
                msg: 'Cuti Disetujui!',
                sound: false,
            });
        }


        function round_danger_noti(alasan) {
            Lobibox.notify('error', {
                pauseDelayOnHover: true,
                size: 'mini',
                rounded: true,
                icon: 'bx bx-check-circle',
                delayIndicator: false,
                continueDelayOnInactiveTab: false,
                position: 'top right',
                msg: alasan,
                sound: false,
            });
        }

        document.addEventListener('livewire:init', () => {
            Livewire.on('refresh', (event) => {
                round_success_noti();
            });

            Livewire.on('cutiKurang', (event) => {
                round_danger_noti('Sisa Cuti Kurang!');
            });

            Livewire.on('tolak', (event) => {
                round_danger_noti('Cuti Ditolak!');
            });
        });

        function showRejectModal(id) {
            $('#rejectModal').modal('show');
            Livewire.dispatch('getCuti', {
                id: id,
            });
        }
    </script>

    @livewireScripts()
@endsection
