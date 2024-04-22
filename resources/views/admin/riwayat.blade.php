@extends('admin.layout.main')

@section('css')
    @livewireStyles()
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h3>Riwayat Cuti</h3>
                </div>
                <div class="card-body">
                    @livewire('table-riwayat-cuti')
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @livewireScripts()
@endsection
