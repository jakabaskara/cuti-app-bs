@extends('admin.layout.main')

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h5>Data Karyawan</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-hover ">
                            <thead class="table-dark">
                                <tr class="text-center">
                                    <th class="text-dark">No.</th>
                                    <th class="text-dark">NIK</th>
                                    <th class="text-dark">Nama</th>
                                    <th class="text-dark">BRM</th>
                                    <th class="text-dark">Tanggal Mulai Bekerja</th>
                                    <th class="text-dark">Tanggal Diangkat Staff</th>
                                    <th class="text-dark">Unit Kerja</th>
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
                            </tbody>
                        </table>
                    </div>
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
