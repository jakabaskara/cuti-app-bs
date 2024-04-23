@extends('asisten.layout.main')

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="{{ asset('assets/plugins/notifications/css/lobibox.min.css') }}" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="{{ asset('assets/plugins/datatables/datatables.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @livewireStyles()
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                                + Ajukan Cuti
                            </button>
                            {{-- <button @click="showModal = true">Open Modal</button> --}}
                        </div>
                        {{-- <div class="col">
                            <a href="{{ route('admin.download.pdf') }}" class="btn btn-primary">PDF</a>
                        </div> --}}
                    </div>
                    <div class="row mt-3">
                        <div class="col">
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

    <!-- Modal -->
    <div class="modal fade " id="exampleModal" aria-labelledby="exampleModalLabel">
        <form method="post" action="{{ route('asisten.submit-cuti') }}" id="formSubmit">
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
                                <input type="hidden" id="jumlahHari" name="jumlahHariCuti" required>
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

    {{-- @livewire('asisten-modal-add-cuti') --}}
@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="{{ asset('assets/plugins/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/notifications/js/lobibox.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>


    <script>
        var fp;

        function confirmation(id) {
            Swal.fire({
                title: "Apakah anda yakin untuk membatalkan?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, batalkan cuti!"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Panggil rute delete-cuti menggunakan metode DELETE
                    axios.get('delete-cuti/' + id)
                        .then(response => {
                            // Swal.fire({
                            //     title: 'Data Berhasil Dihapus',
                            //     text: response.data.message,
                            //     icon: response.data.icon
                            // });
                            location.reload();
                        });
                }
            });
        }
        fetch("{{ asset('assets/libur.json') }}")
            .then(response => response.json())
            .then(data => {
                var holidays = data;
                fp = flatpickr('.flatpickr1', {
                    mode: 'range',
                    onChange: function(selectedDates, dateStr, instance) {
                        if (selectedDates.length >= 2) {
                            var startDate = selectedDates[0];
                            var endDate = selectedDates[selectedDates.length - 1];

                            var difference = endDate.getTime() - startDate.getTime();

                            var daysDifference = 0;

                            // Loop through each day in the range
                            var currentDate = new Date(startDate);

                            while (currentDate <= endDate) {
                                // Check if the current day is not Sunday (0)
                                if (currentDate.getDay() !== 0) {
                                    var formattedDate = currentDate.toLocaleDateString('en-CA');
                                    if (!holidays[formattedDate] || !holidays[formattedDate].holiday) {
                                        daysDifference++;
                                    }
                                }
                                // Move to the next day
                                currentDate.setDate(currentDate.getDate() + 1);
                            }

                            var sisaCutiPanjang = parseInt($('#sisa_cuti_panjang').val());
                            var sisaCutiTahunan = parseInt($('#sisa_cuti_tahunan').val());
                            var totalCuti = sisaCutiPanjang + sisaCutiTahunan;

                            var content = document.getElementById("jumlah-hari");
                            content.classList.add('text-dark');

                            content.textContent = "Jumlah :" +
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
                    },
                })
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
            // $('#tableData1').DataTable();

            $('#dataTable2').DataTable();

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
                msg: 'Pengajuan Cuti Berhasil Dibuat!'
            });
        }

        @if (session('message'))
            round_success_noti()
        @endif

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
