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

    @if ($is_kebun == 1)
        @livewire('manajer-status-bar-index')
    @else
        @livewire('status-bar-index')
    @endif

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

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h5>Daftar Riwayat Cuti</h5>
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

    <script>
        // $(document).ready(function() {
        //     $('#btnTolak').click(function() {
        //         id = $('#idCuti').val();
        //         pesan = $('#textTolak').val();
        //         Livewire.dispatch('tolak_cuti', {
        //             id: id,
        //             pesan: pesan,
        //         })
        //         $('#rejectModal').modal('hide');

        //     });
        // });
        $(document).ready(function() {
            $('#btnTolak').click(function() {
                let id = $('#idCuti').val();
                let pesan = $('#textTolak').val();
                Livewire.dispatch('tolak_cuti', {
                    id: id,
                    pesan: pesan,
                })
                $('#rejectModal').modal('hide');
            });
        });

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

        $(function() {
            $('input[name="daterange"]').daterangepicker({
                opens: 'left'
            }, function(start, end, label) {
                console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end
                    .format('YYYY-MM-DD'));
            });

            $('#datatable2').DataTable({
                responsive: true,
                rowReorder: {
                    selector: 'td:nth-child(2)'
                }
            });
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
            Livewire.on('terima', (event) => {
                round_success_noti();
            });

            Livewire.on('cutiKurang', (event) => {
                round_danger_noti('Sisa Cuti Kurang!');
            });

            Livewire.on('tolak', (event) => {
                round_danger_noti('Cuti Ditolak!');
            });
        });

        // function showRejectModal(id) {
        //     $('#rejectModal').modal('show');
        //     Livewire.dispatch('getCuti', {
        //         id: id,
        //     });
        // }
        function showRejectModal(id) {
            $('#rejectModal').modal('show');
            $('#textTolak').val(''); // Kosongkan input alasan penolakan
            Livewire.dispatch('getCuti', {
                id: id,
            });
        }
    </script>

    @livewireScripts()
@endsection
