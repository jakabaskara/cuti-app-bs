@extends('manajer.layout.main')

@section('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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
                        <table class="table table-sm table-bordered table-hover ">
                            <thead class="table-dark">
                                <tr class="text-center align-middle">
                                    <th class="text-dark">No.</th>
                                    <th class="text-dark">NIK</th>
                                    <th class="text-dark">Nama</th>
                                    <th class="text-dark">Status Karyawan</th>
                                    <th class="text-dark">Tanggal Mulai Bekerja</th>
                                    <th class="text-dark">Tanggal Diangkat Staff</th>
                                    <th class="text-dark">ID Posisi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- <tr class="text-center">
                                    <td>1.</td>
                                    <td>13002775</td>
                                    <td>Prabowo Widodo</td>
                                    <td>10</td>
                                    <td>21</td>
                                </tr> --}}
                                @forelse ($karyawan as $karyawan)
                                    <tr>
                                        <th>{{ $loop->iteration }}</th>
                                        <td>{{ $karyawan->NIK }}</td>
                                        <td>{{ $karyawan->nama }}</td>
                                        <td>{{ $karyawan->jabatan }}</td>
                                        <td>{{ $karyawan->TMT_bekerja }}</td>
                                        <td>{{ $karyawan->tgl_diangkat_staf }}</td>
                                        <td>{{ $karyawan->id_posisi }}</td>

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
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Form Penambahan Karyawan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="post">
                        <div class="row mb-3">
                            <div class="col">
                                <label for="NIK" class="form-label">NIK</label>
                                <input type="text" class="form-control" id="NIK" name="NIK"
                                    value="{{ old('nik') }}" />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="Nama" class="form-label">Nama</label>
                                <input type="text" class="form-control" name="Nama"
                                    value="{{ old('nama_karyawan') }}" />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="nama" class="form-label">Status Karyawan</label>
                                <select class="form-select" aria-label="Nama Karyawan">
                                    <option selected value=""> </option>
                                    <option value="1">One</option>
                                    <option value="2">Two</option>
                                    <option value="3">Three</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="date" class="form-label">Tanggal Mulai Bekerja</label>
                                <input type="date" class="form-control" name="date" value="" />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="date" class="form-label">Tanggal Diangkat Menjadi Staff</label>
                                <input type="date" class="form-control" name="date" value="" />
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="id posisi" class="form-label">ID Posisi</label>
                                <input type="text" class="form-control" name="id posisi" value="" />
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batalkan</button>
                    <button type="button" class="btn btn-primary">Ajukan</button>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="row mt-1">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <button type="submit" class="btn btn-primary">+ Ajukan Cuti</button>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm table-hover">
                                    <thead class="table-dark">
                                        <tr class="text-center">
                                            <th>No.</th>
                                            <th>NIK</th>
                                            <th>Nama</th>
                                            <th>Jenis Cuti</th>
                                            <th>Periode Tanggal</th>
                                            <th>Sisa Cuti</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
@endsection
@section('script')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        $(function() {
            $('input[name="daterange"]').daterangepicker({
                opens: 'left'
            }, function(start, end, label) {
                console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end
                    .format('YYYY-MM-DD'));
            });
        });
    </script>
@endsection
