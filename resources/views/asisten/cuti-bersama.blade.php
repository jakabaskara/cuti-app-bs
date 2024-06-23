@extends('asisten.layout.main')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/plugins/notifications/css/lobibox.min.css') }}" />

    @livewireStyles()
@endsection

@section('content')
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger" role="alert">
                {{ $error }}
            </div>
        @endforeach
    @endif
    <div class="row">
        <div class="col">

        </div>
    </div>
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('asisten.store-karyawan-tidak-cuti') }}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title text-center" id="hadirModalLabel">Konfirmasi Karyawan yang Tidak Melakukan
                            Cuti Bersama
                        </h5>
                    </div>
                    <div class="modal-body px-5 py-3">
                        <p class="keterangan mb-3"></p>
                        @livewire('selected-karyawan-cuti')
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/plugins/notifications/js/lobibox.min.js') }}"></script>

    <script>
        var tgl = '';
        $(document).ready(function() {
            $('#selectTanggal').on('change', function() {
                var tanggal = $(this).val();
                tgl = $('#selectTanggal option:selected').text();
                Livewire.dispatch('changeDate', {
                    tanggal: tanggal
                });
                Livewire.dispatch('refresh');
            })

            $('#btnSimpan').click(function() {
                $(this).html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...'
                ).attr('disabled', 'disabled');
                // Lakukan submit form atau proses lainnya di sini
            });

            @if (session('message'))
                round_success_noti();
            @endif
        });

        Livewire.on('getHadirKaryawan', (e) => {
            $('#hadirModal').modal('show');
            $('.keterangan').text(tgl)
            Livewire.dispatch('setSelectedKaryawan', {
                data: e.data,
            });
        });

        function round_success_noti() {
            Lobibox.notify('success', {
                pauseDelayOnHover: true,
                size: 'mini',
                rounded: true,
                icon: 'bx bx-check-circle',
                delayIndicator: false,
                continueDelayOnInactiveTab: false,
                sound: false,
                position: 'top right',
                msg: 'Data Berhasil Disimpan!'
            });
        }
    </script>
    @livewireScripts()
@endsection
