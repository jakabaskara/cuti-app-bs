@extends('admin.layout.main')

@section('css')
    <link href="{{ asset('assets/plugins/datatables/datatables.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h3>Data Pairing</h3>
                    <hr>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-hover table-striped" id="tableData1">
                            <thead class="table-dark">
                                <tr class="text-center align-middle">
                                    <th rowspan="2" class="text-dark">No.</th>
                                    <th rowspan="2" class="text-dark">Unit Kerja</th>
                                    <th colspan="3" class="text-dark">Atasan</th>
                                    <th colspan="3" class="text-dark">Bawahan</th>
                                </tr>
                                <tr class="text-center">
                                    <th class="text-dark">Nama</th>
                                    <th class="text-dark">Jabatan</th>
                                    <th class="text-dark">Posisi</th>
                                    <th class="text-dark">Nama</th>
                                    <th class="text-dark">Jabatan</th>
                                    <th class="text-dark">Posisi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pairings as $pairing)
                                    @foreach ($pairing->bawahan->karyawan as $data)
                                        {{-- {{ dd($data) }} --}}
                                        <tr class="align-middle">
                                            <td class="text-dark text-center">
                                                {{ $loop->parent->index * $loop->count + $loop->iteration }}.</td>
                                            <td class="text-dark">{{ $pairing->atasan->unitKerja->nama_unit_kerja ?? '' }}
                                            </td>
                                            <td class="text-dark">{{ $pairing->atasan->karyawan->first()->nama ?? '' }}</td>
                                            <td class="text-dark">{{ $pairing->atasan->karyawan->first()->jabatan ?? '' }}
                                            </td>
                                            <td class="text-dark">{{ $pairing->atasan->jabatan }}</td>
                                            <td class="text-dark">{{ $data->nama ?? '' }}
                                            </td>
                                            <td class="text-dark">{{ $data->jabatan ?? '' }}
                                            </td>
                                            <td class="text-dark">{{ $pairing->bawahan->jabatan }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/plugins/datatables/datatables.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#tableData1').DataTable();
        })
    </script>
@endsection
