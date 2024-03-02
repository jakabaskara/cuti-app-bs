@extends('asisten.layout.main')

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    @livewireStyles();
@endsection

@section('content')
    <div class="row mt-1">
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
                                    <table class="table table-bordered table-sm table-hover">
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
                                                    <td></td>
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
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        $(document).ready(function() {

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
    @livewireScripts;
@endsection
