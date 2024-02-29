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
                                    <th rowspan="2" class="text-dark">No.</th>
                                    <th rowspan="2" class="text-dark">NIK</th>
                                    <th rowspan="2" class="text-dark">Nama</th>
                                    <th colspan="2" class="text-dark">Sisa Cuti Tahunan
                                    <th colspan="2" class="text-dark">Sisa Cuti Panjang</th>
                                </tr>
                                <tr class="text-center align-middle">
                                    <th class="text-dark">Periode Awal</th>
                                    <th class="text-dark">Periode Akhir</th>
                                    <th class="text-dark">Periode Awal</th>
                                    <th class="text-dark">Periode Akhir</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection