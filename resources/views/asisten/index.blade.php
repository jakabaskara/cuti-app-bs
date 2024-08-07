@extends('asisten.layout.main')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/plugins/notifications/css/lobibox.min.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link href="{{ asset('assets/plugins/datatables/datatables.min.css') }}" rel="stylesheet">
    @livewireStyles()
@endsection

@section('content')
    <h3 class="mb-4">Halo, {{ $nama }} 👋</h3>
    {{-- <div class="row">
        <div class="col-xl-3">
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
        <div class="col-xl-3">
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
        <div class="col-xl-3">
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
        <div class="col-xl-3">
            <div class="card widget widget-stats">
                <div class="card-body">
                    <div class="widget-stats-container d-flex">
                        <div class="widget-stats-icon widget-stats-icon-primary">
                            <i class="material-icons-outlined">info</i>
                        </div>
                        <div class="widget-stats-content flex-fill">
                            <span class="widget-stats-title text-dark">Menunggu Dicek</span>
                            <span class="widget-stats-amount">{{ $menunggudiketahui }}</span>
                            <span class="widget-stats-info">Form Cuti Belum di Cek</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    @livewire('asisten-status-bar-index')

    @if ($is_kebun == 1)
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
    @else
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <h3>Mengetahui Cuti</h3>
                        <hr>
                    </div>
                    <div class="card-body">
                        @livewire('table-mengetahui-cuti')
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="">Daftar Sisa Cuti Karyawan</h5>
                </div>
                <div class="card-body">
                    @livewire('kabag-daftar-sisa-cuti')
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h5 class="text-center">Karyawan Cuti Hari Ini</h5>
                </div>
                <div class="card-body" style="min-height: 300px">
                    @livewire('karyawan-cuti-table')
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h5>Riwayat Persetujuan Cuti</h5>
                    <hr>
                </div>
                <div class="card-body">
                    @livewire('kabag-daftar-riwayat-cuti')

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h5>Daftar Pengajuan Cuti</h5>
                    <hr>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-mt-3">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover table-striped" id="dataTable2">
                                    <thead class="table-dark">
                                        <tr class="text-center align-middle">
                                            <th class="text-dark">No.</th>
                                            <th class="text-dark">NIK</th>
                                            <th class="text-dark">Nama</th>
                                            <th class="text-dark">Jumlah<br>Hari</th>
                                            <th class="text-dark">Periode<br>Tanggal</th>
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
                                            <tr class="text-center align-middle">
                                                <td class="text-dark">{{ $i }}</td>
                                                <td class="text-dark">{{ $riwayat->karyawan->NIK }}</td>
                                                <td class="text-dark">{{ $riwayat->karyawan->nama }}</td>
                                                {{-- <td class="text-dark">{{ $riwayat->sisa_cuti_panjang }}</td> --}}
                                                <td class="text-dark">
                                                    {{ $riwayat->jumlah_cuti_panjang + $riwayat->jumlah_cuti_tahunan }}
                                                </td>
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
                                                        <a href="{{ route('asisten.download.pdf', $riwayat->id) }}"
                                                            class="btn btn-sm btn-success px-1 py-0">
                                                            <span class="material-icons text-sm p-0 align-middle">
                                                                description
                                                            </span>
                                                        </a>
                                                    </td>
                                                @elseif ($riwayat->is_rejected == 1)
                                                    <td class="text-dark"> <span
                                                            class="badge badge-danger p-2">Ditolak</span>
                                                    </td>
                                                    <td class="">
                                                        <button id="" data-id='{{ $riwayat->id }}'
                                                            class="btn btn-sm btn-info px-1 py-0 tolak">
                                                            <span class="material-icons text-sm p-0 align-middle">
                                                                info
                                                            </span>
                                                        </button>
                                                    </td>
                                                @elseif ($riwayat->is_checked == 0)
                                                    <td class="text-dark"> <span class="badge badge-dark p-2">Belum
                                                            Diperiksa</span>
                                                    </td>
                                                    <td class="">
                                                        <form id="deleteForm{{ $riwayat->id }}"
                                                            action="{{ route('asisten.delete-cuti', $riwayat->id) }}"
                                                            method="POST" style="display: none;">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                        <a href="#"
                                                            onclick="event.preventDefault(); document.getElementById('deleteForm{{ $riwayat->id }}').submit();"
                                                            class="btn btn-sm btn-danger px-1 py-0">
                                                            <span
                                                                class="material-icons text-sm p-0 align-middle">delete</span>
                                                        </a>
                                                    </td>
                                                @else
                                                    <td class="text-dark"> <span
                                                            class="badge badge-warning p-2">Pending</span>
                                                    </td>
                                                    <td class="">
                                                        <form id="deleteForm{{ $riwayat->id }}"
                                                            action="{{ route('asisten.delete-cuti', $riwayat->id) }}"
                                                            method="POST" style="display: none;">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                        <a href="#"
                                                            onclick="event.preventDefault(); document.getElementById('deleteForm{{ $riwayat->id }}').submit();"
                                                            class="btn btn-sm btn-danger px-1 py-0">
                                                            <span
                                                                class="material-icons text-sm p-0 align-middle">delete</span>
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
        </div>
    </div>

    <!-- Modal alasan tolak -->
    <div class="modal fade" id="tolakmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Alasan Tolak Cuti</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @livewire('informasi-tolak')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    {{-- modal untuk menolak cuti --}}
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

        // document.addEventListener('livewire:init', () => {

        //     Livewire.on('tolak', (event) => {
        //         round_danger_noti('Cuti Ditolak!');
        //     });

        //     Livewire.on('cutiKurang', (event) => {
        //         round_danger_noti('Jumlah Sisa Cuti Kurang!');
        //     });
        // });
        // Livewire.on('terima', (event) => {
        //     round_success_noti();
        // });

        // Livewire.on('refresh-datatable', (event) => {
        //     console.log('ssss');
        //     var dataTable = $('#tableData1').DataTable();
        //     dataTable.destroy();
        //     console.log(dataTable.destroy());
        //     console.log('Instance DataTable sebelumnya dihancurkan');
        //     $('#tableData1').DataTable(); // Membuat instance DataTable bar
        // })

        document.addEventListener('DOMContentLoaded', () => {
            // Attach event listeners only once
            if (!window.livewireListenersAttached) {
                Livewire.on('tolak', (event) => {
                    round_danger_noti('Cuti Ditolak!');
                });

                Livewire.on('cutiKurang', (event) => {
                    round_danger_noti('Jumlah Sisa Cuti Kurang!');
                });

                Livewire.on('terima', (event) => {
                    round_success_noti();
                });

                Livewire.on('refresh-datatable', (event) => {
                    console.log('ssss');
                    var dataTable = $('#tableData1').DataTable();
                    dataTable.destroy();
                    console.log(dataTable.destroy());
                    console.log('Instance DataTable sebelumnya dihancurkan');
                    $('#tableData1').DataTable(); // Membuat instance DataTable baru
                });

                window.livewireListenersAttached = true;
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
                sound: false,
                position: 'top right',
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
                position: 'top right',
                sound: false,
                msg: alasan
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

        function round_success1_noti() {
            Lobibox.notify('info', {
                pauseDelayOnHover: true,
                size: 'mini',
                rounded: true,
                icon: 'bx bx-check-circle',
                delayIndicator: false,
                continueDelayOnInactiveTab: false,
                position: 'top right',
                msg: 'Cuti Diketahui!',
                sound: false,

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

        document.addEventListener('livewire:init', () => {
            if (!window.livewireNotified) {
                window.livewireNotified = true;
                Livewire.on('ketahui', (event) => {
                    round_success1_noti();
                });
            }
        });


        $(document).ready(function() {
            $('#tableData1').DataTable({
                responsive: true,
                rowReorder: {
                    selector: 'td:nth-child(2)'
                }
            });
            $('#dataTable2').DataTable({
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
            // $('#btnTolak').click(function() {
            //     id = $('#idCuti').val();
            //     pesan = $('#textTolak').val();
            //     Livewire.dispatch('tolak_cuti', {
            //         id: id,
            //         pesan: pesan,
            //     })
            //     $('#rejectModal').modal('hide');

            // });
            $('#btnTolak').click(function() {
                let id = $('#idCuti').val();
                let pesan = $('#textTolak').val();
                if (pesan === '') {
                    alert('Alasan tidak boleh kosong!');
                    return;
                }
                Livewire.dispatch('tolak_cuti', {
                    id: id,
                    pesan: pesan,
                })
                $('#rejectModal').modal('hide');
            });
        });



        //SCRIPT Modal Alasan Tolak
        // $('.tolak').on('click', function() {
        //     var id = $(this).data('id');
        //     $('#tolakmodal').modal('show');
        //     Livewire.dispatch('setKeterangan', {
        //         id: id,
        //     });
        // })

        let offset = 0;
        const limit = 10;
        const actionContainer = document.getElementById('action-container');
        const loader = document.getElementById('loader');

        function fetchActions() {
            loader.style.display = 'block';
            fetch(`get_actions.php?limit=${limit}&offset=${offset}`)
                .then(response => response.json())
                .then(data => {
                    loader.style.display = 'none';
                    data.forEach(action => {
                        const actionDiv = document.createElement('div');
                        actionDiv.className = 'action';
                        actionDiv.innerHTML = `
                            <span class="badge badge-danger p-2">Ditolak</span>
                            <button data-id='${action.id}' class="btn btn-sm btn-info px-1 py-0 tolak">
                                <span class="material-icons text-sm p-0 align-middle">info</span>
                            </button>
                        `;
                        actionContainer.appendChild(actionDiv);
                    });
                    offset += limit;
                });
        }

        function onScroll() {
            if (window.innerHeight + window.scrollY >= document.body.offsetHeight) {
                fetchActions();
            }
        }

        window.addEventListener('scroll', onScroll);

        document.addEventListener('click', function(event) {
            if (event.target.closest('.tolak')) {
                var id = event.target.closest('.tolak').dataset.id;
                $('#tolakmodal').modal('show');
                Livewire.dispatch('setKeterangan', {
                    id: id
                });
            }
        });

        // Fetch the initial set of actions
        fetchActions();
    </script>

    @livewireScripts()
@endsection
