@extends('admin.layout.main')

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h5>Sisa Cuti Tahunan</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-hover ">
                            <thead class="table-dark">
                                <tr class="text-center align-middle">
                                    <th class="text-dark">No.</th>
                                    <th class="text-dark">NIK</th>
                                    <th class="text-dark">Nama</th>
                                    <th class="text-dark">Sisa Cuti Tahunan</th>
                                    <th class="text-dark">Sisa Cuti Panjang</th>
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
@endsection