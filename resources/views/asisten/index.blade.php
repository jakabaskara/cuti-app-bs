@extends('asisten.layout.main')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/plugins/notifications/css/lobibox.min.css') }}" />

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link href="{{ asset('assets/plugins/datatables/datatables.min.css') }}" rel="stylesheet">
    @livewireStyles()
@endsection

@section('content')
    <h3 class="mb-4">Halo, {{ $nama }} 👋</h3>

    @livewire('kabag-status-bar-index')

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
        <div class="col-xl-8">
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
                                            {{-- <th class="text-dark">Jenis Cuti</th> --}}
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
                                            <tr class="text-center">
                                                <td class="text-dark">{{ $i }}</td>
                                                <td class="text-dark">{{ $riwayat->karyawan->NIK }}</td>
                                                <td class="text-dark">{{ $riwayat->karyawan->nama }}</td>
                                                {{-- <td class="text-dark">{{ $riwayat->sisa_cuti_panjang }}</td> --}}
                                                <td class="text-dark">
                                                    {{ $riwayat->jumlah_cuti_panjang + $riwayat->jumlah_cuti_tahunan }}</td>
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
                                                        <button class="btn btn-sm btn-info px-1 py-0">
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
                                                            action="{{ route('kerani.delete-cuti', $riwayat->id) }}"
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
                                                            action="{{ route('kerani.delete-cuti', $riwayat->id) }}"
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
@endsection

@section('script')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="{{ asset('assets/plugins/notifications/js/lobibox.min.js') }}"></script>

    <script src="{{ asset('assets/plugins/datatables/datatables.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#tableData1').DataTable();
            $('#tableData2').DataTable();

        });
        document.addEventListener('livewire:init', () => {
            Livewire.on('terima', (event) => {
                round_success_noti();
            });

            Livewire.on('tolak', (event) => {
                round_danger_noti('Cuti Ditolak!');
            });

            Livewire.on('cutiKurang', (event) => {
                round_danger_noti('Jumlah Sisa Cuti Kurang!');
            });
        });


        $(function() {
            $('input[name="daterange"]').daterangepicker({
                opens: 'left'
            }, function(start, end, label) {
                console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end
                    .format('YYYY-MM-DD'));
            });


            $('#dataTable2').DataTable();
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
    </script>

    @livewireScripts()
@endsection
