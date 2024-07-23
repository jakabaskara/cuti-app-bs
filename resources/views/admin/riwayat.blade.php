@extends('admin.layout.main')

@section('css')
    <link href="{{ asset('assets/plugins/datatables/datatables.min.css') }}" rel="stylesheet">
    @livewireStyles()
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="row">
        <div class="col">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                    @if (session('fileName'))
                        <a href="{{ route('admin.riwayat.download', ['fileName' => session('fileName')]) }}"
                            class="btn btn-info">Download Excel File</a>
                    @endif
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Riwayat Cuti</h3>
                    <a href="{{ route('admin.riwayat.export') }}" class="btn btn-success"> <i class="fas fa-file-excel"></i>
                        Export to Excel</a>
                </div>
                {{-- <hr> --}}
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
