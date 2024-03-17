@extends('admin.layout.main')

@section('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link href="{{ asset('assets/plugins/datatables/datatables.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h5>Data Karyawan</h5>
                    <div class="row">
                        <div class="col">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                                + Tambah Karyawan
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-hover " id="tableData1">
                            <thead class="table-dark">
                                <tr class="text-center align-middle">
                                    <th class="text-dark">No.</th>
                                    <th class="text-dark">NIK</th>
                                    <th class="text-dark">Nama</th>
                                    <th class="text-dark">Jabatan</th>
                                    <th class="text-dark">Unit Kerja</th>
                                    <th class="text-dark">Posisi</th>
                                    <th class="text-dark">ID Posisi</th>
                                    <th class="text-dark">ID Karyawan</th>
                                    <th class="text-dark">aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($karyawan as $karyawan)
                                    <tr class="text-center align-middle">
                                        <th>{{ $loop->iteration }}</th>
                                        <td>{{ $karyawan->NIK }}</td>
                                        <td>{{ $karyawan->nama }}</td>
                                        <td>{{ $karyawan->jabatan }}</td>
                                        <td>{{ $karyawan->posisi->unitKerja->nama_unit_kerja }}</td>
                                        <td>{{ $karyawan->posisi->jabatan }}</td>
                                        <td>{{ $karyawan->id_posisi }}</td>
                                        <td>{{ $karyawan->id }}</td>
                                        <td class=" ">

                                            <div class="row">
                                                <div class="col">
                                                    <button class="btn btn-sm px-2 py-0 m-0 btn-warning"><span
                                                            class="material-icons">
                                                            edit_note
                                                        </span></button>

                                                    <button class="btn btn-sm px-2 py-0 m-0 btn-danger"><span
                                                            class="material-icons">
                                                            delete
                                                        </span></button>
                                                </div>
                                            </div>

                                        </td>
                                    </tr>
                                @empty
                                    <td colspan="6" class="text-center">Data Not Found</td>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form action="{{ route('admin.tambahKaryawan') }}" method="post">
            @csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Form Penambahan Karyawan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col">
                                <label for="NIK" class="form-label">NIK</label>
                                <input type="text" class="form-control" id="NIK" name="nik" required
                                    value="{{ old('nik') }}" />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="Nama" class="form-label">Nama</label>
                                <input type="text" class="form-control" name="nama_karyawan" required
                                    value="{{ old('nama_karyawan') }}" />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="Jabatan" class="form-label">Jabatan</label>
                                {{-- <select class="form-select" aria-label="jabatan" name="jabatan">
                                    <option selected value="{{ old('jabatan') }}"> </option>
                                    <option value="1">One</option>
                                    <option value="2">Two</option>
                                    <option value="3">Three</option>
                                    <option selected value="">Pilih Jabatan</option>
                                    @foreach ($karyawan as $karyawans)
                                        <option value="{{ $karyawan->id }}">
                                            {{ $karyawan->jabatan }}</option>
                                    @endforeach
                                </select> --}}
                                <input type="text" class="form-control " name="jabatan" required
                                    value="{{ old('jabatan') }}" />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="daterange" class="form-label">Tanggal Mulai Bekerja</label>
                                <input type="text" class="form-control flatpickr1" name="tmt_bekerja" required
                                    value="{{ old('tmt_bekerja') }}" />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="daterange" class="form-label">Tanggal Diangkat Menjadi Staf</label>
                                <input type="text" class="form-control flatpickr1" name="tgl_diangkat_staf" required
                                    value="{{ old('tgl_diangkat_staf') }}" />
                            </div>
                        </div>
                        <div class="row mb-3">

                            <div class="col">
                                <label for="id posisi" class="form-label">ID Posisi</label>
                                <select class="form-select" aria-label="id posisi" name="id_posisi" required>
                                    <option selected value="{{ old('id_posisi') }}">Pilih Jabatan</option>
                                    <option value="1">Kerani Administrasi</option>
                                    <option value="2">Kepala Bagian</option>
                                    <option value="3">Asisten / Staf Bagian SDM & Sistem Manajemen</option>
                                    <option value="4">Kerani Administrasi</option>
                                    <option value="5">user</option>
                                    <option value="6">admin</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batalkan</button>
                        <button type="submit" class="btn btn-primary">Ajukan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('script')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="{{ asset('assets/plugins/datatables/datatables.min.js') }}"></script>

    <script>
        $('#tableData1').DataTable();

        // $(function() {
        //     $('input[name="daterange"]').daterangepicker({
        //         opens: 'left'
        //     }, function(start, end, label) {
        //         console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end
        //             .format('YYYY-MM-DD'));
        //     });
        // });

        $(document).ready(function() {

            $('#datatable1').DataTable({
                responsive: true,
                rowReorder: {
                    selector: 'td:nth-child(2)'
                }
            });




            // flatpickr('.flatpickr1', {
            //     mode: 'range',
            //     onChange: function(selectedDates, dateStr, instance) {
            //         if (selectedDates.length >= 2) {
            //             var startDate = selectedDates[0];
            //             var endDate = selectedDates[selectedDates.length - 1];

            //             // Hitung selisih dalam milidetik
            //             var difference = endDate.getTime() - startDate.getTime();

            //             // Konversi selisih ke jumlah hari
            //             var daysDifference = Math.ceil(difference / (1000 * 60 * 60 * 24)) + 1;

            //             document.getElementById("jumlah-hari").textContent = "Jumlah Hari: " +
            //                 daysDifference;
            //             document.getElementById("jumlahHari").value = daysDifference;
            //         }
            //     }
            // })
        })
    </script>
    @livewireScripts();
@endsection
