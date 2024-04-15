@extends('asisten.layout.main')

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h3>Cuti Bersama</h3>
                    <hr>
                </div>
                <div class="card-body">
                    <x-select-cuti-bersama></x-select-cuti-bersama>
                </div>
            </div>
        </div>
    </div>
@endsection
