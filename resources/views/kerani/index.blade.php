@extends('kerani.layout.main')

@section('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link href="{{ asset('assets/plugins/datatables/datatables.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/notifications/css/lobibox.min.css') }}" />

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
    <div class="row">
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
    </div>

    <div class="col">
        <div class="row">
            <div class="col-xl-7">
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
            <div class="col-xl-5">
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

    <div class="row mt-1">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h5 class="">Surat Cuti Karyawan</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                                Buat Surat
                            </button>
                        </div>
                    </div>
                    <div>
                        {{-- <div wire:loading class="f-14 text-dark"> <span class="spinner-grow text-danger align-middle"></span> Loading...</div> --}}
                        <div class="table-responsive">
                            <table class="table table-sm table-hover table-striped" id="dataTable2">
                                <thead>
                                    <tr class="text-center">
                                        <th class="text-dark">No.</th>
                                        <th class="text-dark">NIK</th>
                                        <th class="text-dark">Nama</th>
                                        {{-- <th class="text-dark">Jenis Cuti</th> --}}
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
    </div>

    <!-- Modal -->
    <div class="modal fade " id="exampleModal" aria-labelledby="exampleModalLabel">
        <form method="post" action="{{ route('kerani.submit-cuti') }}" id="formSubmit">
            @csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Form Pengajuan Cuti</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <label for="nama" class="form-label">Nama Karyawan</label>
                            <select class="form-select" id="select2" style="display: none; width: 100%"
                                aria-label="Nama Karyawan" name="karyawan" required">
                                <option selected value=""> </option>
                                @foreach ($dataPairing as $pairing)
                                    <option value="{{ $pairing->id }}">
                                        {{ $pairing->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @livewire('kerani-daftar-sisa-cuti')
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="daterange" class="form-label">Tanggal Cuti</label>
                                <input type="text" class="form-control flatpickr1" name="tanggal_cuti" required
                                    id="tanggal_cuti" />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <p class="text-dark" id="jumlah-hari"> Jumlah Hari Cuti: 0</p>
                                <input type="hidden" id="jumlahHari" name="jumlah_cuti" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            @livewire('kerani-jenis-cuti')
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="alasan" class="form-label">Alasan Cuti</label>
                                <input type="text" class="form-control" name="alasan" required />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="alamat" class="form-label">Alamat</label>
                                <input type="text" class="form-control" name="alamat" required />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">

                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batalkan</button>
                        <button type="submit" id="ajukan" class="btn btn-primary">Ajukan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h5>Riwayat Cuti Karyawan</h5>
                    <hr>
                </div>
                <div class="card-body">
                    <a href="{{ route('send.noti') }}" class="btn btn-warning">Send Notify</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="{{ asset('assets/plugins/datatables/datatables.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/notifications/js/lobibox.min.js') }}"></script>



    <script>
        var fp = flatpickr('.flatpickr1', {
            mode: 'range',
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length >= 2) {
                    var startDate = selectedDates[0];
                    var endDate = selectedDates[selectedDates.length - 1];

                    // Hitung selisih dalam milidetik
                    var difference = endDate.getTime() - startDate.getTime();
                    var tipeCuti = $('#jenisCuti option:selected').text();
                    // var kdtipeCuti = $('#jenisCuti option:selected').val();
                    // Konversi selisih ke jumlah hari
                    var daysDifference = Math.ceil(difference / (1000 * 60 * 60 * 24)) + 1;

                    var sisaCutiPanjang = parseInt($('#sisa_cuti_panjang').val());
                    var sisaCutiTahunan = parseInt($('#sisa_cuti_tahunan').val());
                    var totalCuti = sisaCutiPanjang + sisaCutiTahunan;

                    var content = document.getElementById("jumlah-hari");
                    content.classList.add('text-dark');

                    content.textContent = "Jumlah " +
                        tipeCuti + ": " +
                        daysDifference + " hari";
                    console.log(totalCuti)
                    console.log(daysDifference)
                    if (daysDifference > totalCuti) {
                        content.classList.remove('text-dark');
                        content.classList.add('text-danger');
                        $('#ajukan').prop('disabled', true).hide();

                    } else {
                        content.classList.remove('text-danger');
                        content.classList.add('text-dark');
                        $('#ajukan').show().prop('disabled', false);
                    }

                    document.getElementById("jumlahHari").value = daysDifference;

                    Livewire.dispatch('setJumlahHariCuti', {
                        daysDifference,
                        totalCuti
                    });
                }
            }
        })

        $(document).ready(function() {
            $('#ajukan').click(function() {

                $(this).html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...'
                );
                $(this).prop('disabled', true);
                $('#formSubmit').submit();
            });
            // $.fn.modal.Constructor.prototype.enforceFocus = function() {};
            $('#tableData1').DataTable();

            $('#dataTable2').DataTable({
                responsive: true,
                rowReorder: {
                    selector: 'td:nth-child(2)'
                }
            });

            $('#select2').select2({
                dropdownParent: $('#exampleModal .modal-content')
            });

        })

        document.addEventListener('livewire:init', () => {
            Livewire.on('setNama', (event) => {
                $('#jumlah-hari').text('');
                $('#jumlahHari').val('');
                try {
                    fp.clear()
                } catch (e) {
                    console.log(e)
                }
            });

            Livewire.on('errorCuti', (e) => {
                round_error_noti('Jumlah Cuti Tidak Mencukupi');
            });
        });


        $('#select2').select2({
            dropdownParent: $('#exampleModal .modal-content')
        }).on('change', function() {
            var selectedValue = $(this).val();
            Livewire.dispatch('setname', {
                id: selectedValue
            });
        });

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                round_error_noti('{!! $error !!}');
            @endforeach
        @endif


        function round_success_noti() {
            Lobibox.notify('success', {
                pauseDelayOnHover: true,
                size: 'mini',
                rounded: true,
                icon: 'bx bx-check-circle',
                delayIndicator: false,
                continueDelayOnInactiveTab: false,
                position: 'top right',
                msg: 'Pengajuan Berhasil Dibuat!'
            });
        }

        @if (session('message'))
            round_success_noti()
        @endif

        function round_error_noti(msg) {
            Lobibox.notify('error', {
                pauseDelayOnHover: true,
                size: 'mini',
                rounded: true,
                icon: 'bi bi-exclamation-triangle',
                delayIndicator: false,
                continueDelayOnInactiveTab: false,
                position: 'top right',
                sound: false,
                msg: msg + '!',
            });
        }
    </script>
    @livewireScripts()
@endsection
