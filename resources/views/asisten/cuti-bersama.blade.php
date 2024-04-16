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

    <div class="modal fade" id="hadirModal" tabindex="-1" aria-labelledby="hadirModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="hadirModalLabel">Konfirmasi Karyawan yang Tidak Melakukan Cuti</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-5 py-5">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary">Simpan</button>
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
        });

        document.addEventListener('livewire:init', () => {
            Livewire.on('setHadir', (e) => {
                $('#hadirModal').modal('show');
            });
        });
    </script>
    @livewireScripts()
@endsection
