@extends('sevp.layout.main')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/plugins/notifications/css/lobibox.min.css') }}" />
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link href="{{ asset('assets/plugins/datatables/datatables.min.css') }}" rel="stylesheet">
    @livewireStyles()
@endsection

@section('content')
    <h3 class="mb-4">Halo, {{ $nama }} 👋</h3>

    {{-- @livewire('sevp-status-bar-index') --}}
    @livewire('kabag-status-bar-index')

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
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="text-center">Daftar Sisa Cuti Karyawan</h5>
                    </div>
                    <div class="card-body">
                        @livewire('kabag-daftar-sisa-cuti')
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
                    <h5>Riwayat Permintaan Cuti</h5>
                    <hr>
                </div>
                <div class="card-body">
                    @livewire('kabag-daftar-riwayat-cuti')
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

    {{-- <script>
        Livewire.on('closeRejectModal', () => {
            $('#rejectModal').modal('hide');
        });
    </script> --}}
    <script>
        $(document).ready(function() {
            $('#btnTolak').click(function() {
                id = $('#idCuti').val();
                pesan = $('#textTolak').val();
                Livewire.dispatch('tolak_cuti', {
                    id: id,
                    pesan: pesan,
                })
                $('#rejectModal').modal('hide');

            });
        });

        function showRejectModal(id) {
            $('#rejectModal').modal('show');
            Livewire.dispatch('getCuti', {
                id: id,
            });
        }
    </script>

    <script>
        $('#tableData1').DataTable({
            responsive: true,
            rowReorder: {
                selector: 'td:nth-child(2)'
            }
        });
        $('#tableData2').DataTable({
            responsive: true,
            rowReorder: {
                selector: 'td:nth-child(2)'
            }
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
                sound: false,
                msg: 'Cuti Disetujui!'
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
                sound: false,
                position: 'top right',
                msg: alasan,
            });
        }

        document.addEventListener('livewire:init', () => {
            Livewire.on('terima', (event) => {
                round_success_noti();
            });

            Livewire.on('tolak', (event) => {
                round_danger_noti('Cuti Ditolak!');
            });

            Livewire.on('cutiKurang', (event) => {
                round_danger_noti('Sisa Cuti Kurang!');
            });
        });

        $(document).ready(function() {

        });
    </script>

    @livewireScripts()
@endsection
