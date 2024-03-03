@extends('asisten.layout.main')

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="{{ asset('assets/plugins/datatables/datatables.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/plugins/notifications/css/lobibox.min.css') }}" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    {{-- @livewireStyles; --}}
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
                        <div class="col">
                            <a href="{{ route('admin.download.pdf') }}" class="btn btn-primary">PDF</a>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            {{-- @livewire('asisten-tabel-pengajuan-cuti') --}}
                            <div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm table-hover" id="datatable1">
                                        <thead class="table-dark">
                                            <tr class="text-center align-middle">
                                                <th>No.</th>
                                                <th>NIK</th>
                                                <th>Nama</th>
                                                <th>Jenis Cuti</th>
                                                <th>Jumlah<br>Hari</th>
                                                <th>Periode Tanggal</th>
                                                <th>Alasan</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $i = 1;
                                            @endphp
                                            @foreach ($riwayats as $riwayat)
                                                <tr class="text-center">
                                                    <td>{{ $i }}</td>
                                                    <td>{{ $riwayat->karyawan->NIK }}</td>
                                                    <td>{{ $riwayat->karyawan->nama }}</td>
                                                    <td>{{ $riwayat->jenisCuti->jenis_cuti }}</td>
                                                    <td>{{ $riwayat->jumlah_hari_cuti }}</td>
                                                    <td>{{ date('d-M', strtotime($riwayat->tanggal_mulai)) . ' s.d ' . date('d-M', strtotime($riwayat->tanggal_selesai)) }}
                                                    </td>
                                                    <td>{{ $riwayat->alasan }}</td>
                                                    @if ($riwayat->is_approved == 1)
                                                        <td> <span class="badge badge-success p-2">Disetujui</span> </td>
                                                    @elseif ($riwayat->is_rejected == 1)
                                                        <td> <span class="badge badge-danger p-2">Ditolak</span> </td>
                                                    @else
                                                        <td> <span class="badge badge-warning p-2">Pending</span> </td>
                                                    @endif
                                                    @if ($riwayat->is_rejected == 0 && $riwayat->is_approved == 0 && $riwayat->is_checked == 1)
                                                        <td>
                                                            <div class="row">
                                                                <div class="col">
                                                                    <button data-riwayat-id="{{ $riwayat->id }}"
                                                                        onclick="confirmation({{ $riwayat->id }})"
                                                                        class="button-confirm btn btn-sm px-2 py-0 m-0 btn-danger"><span
                                                                            class="material-icons">
                                                                            delete
                                                                        </span></button>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    @else
                                                        <td></td>
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
    </div>

    @livewire('asisten-modal-add-cuti')
@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="{{ asset('assets/plugins/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/notifications/js/lobibox.min.js') }}"></script>


    <script>
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
                    axios.delete('delete-cuti/' + id)
                        .then(response => {
                            Swal.fire({
                                title: response.data.title,
                                text: response.data.message,
                                icon: response.data.icon
                            });
                        })
                        .catch(error => {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Terjadi kesalahan saat memproses permintaan.',
                                icon: 'error'
                            });
                        });
                }
            });
        }


        $(document).ready(function() {

            $('#datatable1').DataTable({
                responsive: true,
                rowReorder: {
                    selector: 'td:nth-child(2)'
                }
            });


            flatpickr('.flatpickr1', {
                mode: 'range',
                onChange: function(selectedDates, dateStr, instance) {
                    if (selectedDates.length >= 2) {
                        var startDate = selectedDates[0];
                        var endDate = selectedDates[selectedDates.length - 1];

                        // Hitung selisih dalam milidetik
                        var difference = endDate.getTime() - startDate.getTime();

                        // Konversi selisih ke jumlah hari
                        var daysDifference = Math.ceil(difference / (1000 * 60 * 60 * 24)) + 1;

                        document.getElementById("jumlah-hari").textContent = "Jumlah Hari: " +
                            daysDifference;
                        document.getElementById("jumlahHari").value = daysDifference;
                    }
                }
            })
        })
    </script>
    @livewireScripts();
@endsection
