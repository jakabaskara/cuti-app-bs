@extends('asisten.layout.main')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css">
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
                    <div class="row mt-3">
                        <div class="col">
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
        var fpTahunan, fpPanjang;
        var selectedTahunan = [],
            selectedPanjang = [];
        var isKandir = {{ $isKandir ? 'true' : 'false' }};
        var username = "{{ $username }}";

        function countWorkdays(startDate, endDate, holidays) {
            var count = 0;
            var dates = [];
            var cur = new Date(startDate);
            var userStartsWith5 = username.startsWith('5');
            var dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

            while (cur <= endDate) {
                var dayOfWeek = cur.getDay();
                var fmt = cur.toLocaleDateString('en-CA');
                var holiday = holidays[fmt];
                var isLiburBiasa = holiday && holiday.jenis_libur === 'libur_biasa';
                var isWeekend;

                if (userStartsWith5) {
                    isWeekend = (dayOfWeek === 0 || dayOfWeek === 6);
                } else {
                    isWeekend = (dayOfWeek === 0);
                }

                if (!isWeekend && !isLiburBiasa) {
                    count++;
                    dates.push(fmt + ' (' + dayNames[dayOfWeek] + ')');
                }
                cur.setDate(cur.getDate() + 1);
            }
            return {
                count: count,
                dates: dates
            };
        }

        function countCalendarDays(startDate, endDate, holidays) {
            var userStartsWith5 = username.startsWith('5');
            var dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            var extendedStart = new Date(startDate);
            var extendedEnd = new Date(endDate);

            while (true) {
                var prev = new Date(extendedStart);
                prev.setDate(prev.getDate() - 1);
                var dayOfWeek = prev.getDay();
                var fmt = prev.toLocaleDateString('en-CA');
                var isWeekend = userStartsWith5 ? (dayOfWeek === 0 || dayOfWeek === 6) : (dayOfWeek === 0);
                var holiday = holidays[fmt];

                if (isWeekend) {
                    extendedStart = prev;
                } else if (holiday) {
                    extendedStart = prev;
                } else {
                    break;
                }
            }

            while (true) {
                var next = new Date(extendedEnd);
                next.setDate(next.getDate() + 1);
                var dayOfWeek = next.getDay();
                var fmt = next.toLocaleDateString('en-CA');
                var isWeekend = userStartsWith5 ? (dayOfWeek === 0 || dayOfWeek === 6) : (dayOfWeek === 0);
                var holiday = holidays[fmt];

                if (isWeekend) {
                    extendedEnd = next;
                } else if (holiday) {
                    extendedEnd = next;
                } else {
                    break;
                }
            }

            var count = 0;
            var dates = [];
            var cur = new Date(extendedStart);
            while (cur <= extendedEnd) {
                var dayOfWeek = cur.getDay();
                var fmt = cur.toLocaleDateString('en-CA');
                dates.push(fmt + ' (' + dayNames[dayOfWeek] + ')');
                count++;
                cur.setDate(cur.getDate() + 1);
            }
            return {
                count: count,
                dates: dates
            };
        }

        function datesOverlap(s1, e1, s2, e2) {
            if (!s1 || !e1 || !s2 || !e2) return false;
            return s1 <= e2 && s2 <= e1;
        }

        function validateAndUpdate(holidays) {
            var sisaTahunan = parseInt($('#sisa_cuti_tahunan').val()) || 0;
            var sisaPanjang = parseInt($('#sisa_cuti_panjang').val()) || 0;

            var hariTahunan = 0,
                hariPanjang = 0;
            var errorTahunan = '',
                errorPanjang = '';
            var detailTahunan = [],
                detailPanjang = [];

            if (selectedTahunan.length >= 2) {
                var resultTahunan = countWorkdays(selectedTahunan[0], selectedTahunan[1], holidays);
                hariTahunan = resultTahunan.count;
                detailTahunan = resultTahunan.dates;
                if (hariTahunan > sisaTahunan) {
                    errorTahunan = 'Melebihi sisa cuti tahunan (' + sisaTahunan + ' hari)!';
                }
            }

            if (selectedPanjang.length >= 2) {
                var resultPanjang = countCalendarDays(selectedPanjang[0], selectedPanjang[1], holidays);
                hariPanjang = resultPanjang.count;
                detailPanjang = resultPanjang.dates;
                if (hariPanjang > sisaPanjang) {
                    errorPanjang = 'Melebihi sisa cuti panjang (' + sisaPanjang + ' hari)!';
                }
            }

            if (selectedTahunan.length >= 2 && selectedPanjang.length >= 2) {
                var hasOverlap = datesOverlap(selectedTahunan[0], selectedTahunan[1], selectedPanjang[0], selectedPanjang[
                    1]);
                if (hasOverlap) {
                    errorTahunan = 'Tanggal bentrok dengan Cuti Panjang!';
                    errorPanjang = 'Tanggal bentrok dengan Cuti Tahunan!';
                }
            }

            var infoTahunan = document.getElementById('info-hari-tahunan');
            var infoPanjang = document.getElementById('info-hari-panjang');
            if (selectedTahunan.length >= 2) {
                var detailText = detailTahunan.length <= 3 ? detailTahunan.join(', ') : detailTahunan.slice(0, 2).join(
                    ', ') + ' ... ' + detailTahunan[detailTahunan.length - 1];
                infoTahunan.innerHTML = errorTahunan ? '⚠ ' + errorTahunan : '✓ ' + hariTahunan + ' hari kerja<br><small>' +
                    detailText + '</small>';
                infoTahunan.className = errorTahunan ? 'text-danger' : 'text-success';
            } else {
                infoTahunan.innerHTML = '';
            }
            if (selectedPanjang.length >= 2) {
                var detailText = detailPanjang.length <= 3 ? detailPanjang.join(', ') : detailPanjang.slice(0, 2).join(
                    ', ') + ' ... ' + detailPanjang[detailPanjang.length - 1];
                infoPanjang.innerHTML = errorPanjang ? '⚠ ' + errorPanjang : '✓ ' + hariPanjang + ' hari<br><small>' +
                    detailText + '</small>';
                infoPanjang.className = errorPanjang ? 'text-danger' : 'text-success';
            } else {
                infoPanjang.innerHTML = '';
            }

            var tahunanVal = selectedTahunan.length >= 2 ?
                selectedTahunan[0].toLocaleDateString('en-CA') + ' to ' + selectedTahunan[1].toLocaleDateString('en-CA') :
                '';
            var panjangVal = selectedPanjang.length >= 2 ?
                selectedPanjang[0].toLocaleDateString('en-CA') + ' to ' + selectedPanjang[1].toLocaleDateString('en-CA') :
                '';

            document.getElementById('tanggal_cuti_tahunan_val').value = tahunanVal;
            document.getElementById('tanggal_cuti_panjang_val').value = panjangVal;
            document.getElementById('jumlah_cuti_tahunan').value = hariTahunan;
            document.getElementById('jumlah_cuti_panjang').value = hariPanjang;
            document.getElementById('jumlahHariCuti').value = hariTahunan + hariPanjang;

            Livewire.dispatch('setJumlahHariTahunan', {
                jumlahHari: hariTahunan
            });
            Livewire.dispatch('setJumlahHariPanjang', {
                jumlahHari: hariPanjang
            });

            var hasAny = selectedTahunan.length >= 2 || selectedPanjang.length >= 2;
            var hasError = errorTahunan || errorPanjang;
            if (hasAny && !hasError) {
                $('#ajukan').show().prop('disabled', false);
            } else {
                $('#ajukan').prop('disabled', true);
                if (hasError) $('#ajukan').show();
            }
        }

        fetch("{{ route('api.libur-kalender') }}")
            .then(response => response.json())
            .then(data => {
                var holidays = data;
                fpTahunan = flatpickr('.flatpickr-tahunan', {
                    mode: 'range',
                    onChange: function(dates) {
                        selectedTahunan = dates.length >= 2 ? [dates[0], dates[dates.length - 1]] : [];
                        validateAndUpdate(holidays);
                    },
                });
                fpPanjang = flatpickr('.flatpickr-panjang', {
                    mode: 'range',
                    onChange: function(dates) {
                        selectedPanjang = dates.length >= 2 ? [dates[0], dates[dates.length - 1]] : [];
                        validateAndUpdate(holidays);
                    },
                });
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

            // $('#dataTable2').DataTable();

            $('#select2').select2({
                dropdownParent: $('#exampleModal .modal-content')
            });

        })

        $(document).ready(function() {
            $('#tableData1').DataTable({
                responsive: true,
                rowReorder: {
                    selector: 'td:nth-child(2)'
                }
            });
            $('#dataTable2').DataTable({ //datanya kebanyakan dia kebawah (plugin datatable)
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


        document.addEventListener('livewire:init', () => {
            Livewire.on('setNama', (event) => {
                selectedTahunan = [];
                selectedPanjang = [];
                try {
                    fpTahunan.clear();
                } catch (e) {}
                try {
                    fpPanjang.clear();
                } catch (e) {}

                var infoT = document.getElementById('info-hari-tahunan');
                var infoP = document.getElementById('info-hari-panjang');
                if (infoT) {
                    infoT.textContent = '';
                    infoT.className = '';
                }
                if (infoP) {
                    infoP.textContent = '';
                    infoP.className = '';
                }

                var t = document.getElementById('tanggal_cuti_tahunan_val');
                var p = document.getElementById('tanggal_cuti_panjang_val');
                if (t) t.value = '';
                if (p) p.value = '';
                document.getElementById('jumlah_cuti_tahunan').value = 0;
                document.getElementById('jumlah_cuti_panjang').value = 0;
                document.getElementById('jumlahHariCuti').value = 0;
                $('#ajukan').prop('disabled', true);

                // Tunggu Livewire selesai render ulang sisa cuti, lalu update enable/disable pickers
                setTimeout(function() {
                    var sisaTahunan = parseInt($('#sisa_cuti_tahunan').val()) || 0;
                    var sisaPanjang = parseInt($('#sisa_cuti_panjang').val()) || 0;

                    var inputTahunan = document.getElementById('tanggal_cuti_tahunan_input');
                    var inputPanjang = document.getElementById('tanggal_cuti_panjang_input');

                    if (inputTahunan) {
                        inputTahunan.disabled = sisaTahunan <= 0;
                        inputTahunan.placeholder = sisaTahunan > 0 ?
                            'Pilih Rentang Tanggal Cuti Tahunan' :
                            'Tidak ada sisa cuti tahunan';
                    }
                    if (inputPanjang) {
                        inputPanjang.disabled = sisaPanjang <= 0;
                        inputPanjang.placeholder = sisaPanjang > 0 ?
                            'Pilih Rentang Tanggal Cuti Panjang' :
                            'Tidak ada sisa cuti panjang';
                    }
                }, 300);
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


        function round_success_noti(message) {
            Lobibox.notify('success', {
                pauseDelayOnHover: true,
                size: 'mini',
                rounded: true,
                icon: 'bx bx-check-circle',
                delayIndicator: false,
                continueDelayOnInactiveTab: false,
                position: 'top right',
                sound: false,
                msg: message
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

        //script modal alasan tolak
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
