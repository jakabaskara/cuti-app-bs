@extends('admin.layout.main')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.css" />
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h5>Sisa Cuti Tahunan</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-hover" id="dataTable">
                            {{-- <thead class="table-dark">
                                <tr class="text-center align-middle">
                                    <th class="text-dark">No.</th>
                                    <th class="text-dark">NIK</th>
                                    <th class="text-dark">Nama</th>
                                    <th class="text-dark">Unit Kerja</th>
                                </tr>
                            </thead> --}}
                            <thead class="table-dark">
                                <tr class="text-center align-middle">
                                    <th rowspan="2" class="text-dark">ID</th>
                                    <th rowspan="2" class="text-dark">NIK</th>
                                    <th rowspan="2" class="text-dark">Nama</th>
                                    <th rowspan="2" class="text-dark">Unit Kerja</th>
                                    <th rowspan="2" class="text-dark">Posisi</th>
                                    <th colspan="3" class="text-dark">Sisa Cuti</th>
                                </tr>
                                <tr class="text-center align-middle">
                                    <th class="text-dark">Jenis</th>
                                    <th class="text-dark">Periode</th>
                                    <th class="text-dark">Jumlah</th>
                                </tr>
                            </thead>
                            {{-- <tbody>
                                @foreach ($sisaCutis as $sisaCuti)
                                    <tr class="text-center">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $sisaCuti->karyawan->NIK }}</td>
                                        <td>{{ $sisaCuti->karyawan->nama }}</td>
                                        <td>{{ $sisaCuti->karyawan->posisi->unitKerja->nama_unit_kerja }}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                @endforeach
                            </tbody> --}}
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>

    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('data-sisa-cuti') }}",
                columns: [{
                        data: "ID",
                        name: "ID",
                        orderable: true,
                    },
                    {
                        data: "NIK"
                    },
                    {
                        data: "Nama"
                    },
                    {
                        data: "UnitKerja"
                    },
                    {
                        data: "Posisi"
                    },
                    {
                        data: "JenisCuti"
                    },
                    {
                        data: "PeriodeCuti"
                    },
                    {
                        data: "SisaCuti"
                    }
                ]
            });
        });
    </script>
@endsection
