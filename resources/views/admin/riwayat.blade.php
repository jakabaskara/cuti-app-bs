@extends('admin.layout.main')

@section('css')
    <link href="{{ asset('assets/plugins/datatables/datatables.min.css') }}" rel="stylesheet">
    @livewireStyles()
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h3>Riwayat Cuti</h3>
                    <hr>
                </div>
                <div class="card-body">
                    @livewire('table-riwayat-cuti')
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/plugins/datatables/datatables.min.js') }}"></script>
    @livewireScripts()


    <script>
        $('#tableData1').DataTable();
    </script>
@endsection
