@extends('asisten.layout.main')

@section('css')
    @livewireStyles()
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h3>Cuti Bersama</h3>
                    <hr>
                </div>
                <div class="card-body">
                    <div class="mb-5">
                        <x-select-cuti-bersama></x-select-cuti-bersama>
                    </div>
                    <div class="px-3">
                        @livewire('daftar-cuti-bersama-table')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#selectTanggal').on('change', function() {
                console.log($(this).val())
                var tanggal = $(this).val();
                Livewire.dispatch('changeDate', {
                    tanggal: tanggal
                });
            })
        })
    </script>
    @livewireScripts()
@endsection
