@extends('asisten.layout.main')

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    @livewireStyles();
@endsection

@section('content')
    <div class="row mt-1">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                                + Ajukan Cuti
                            </button>
                            {{-- <button @click="showModal = true">Open Modal</button> --}}
                        </div>
                        <div class="col">
                            <a href="{{ route('admin.download.pdf') }}" class="btn btn-primary">PDF</a>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            @livewire('asisten-tabel-pengajuan-cuti')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @livewire('asisten-modal-add-cuti')
@endsection
@section('script')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        $(document).ready(function() {

            flatpickr('.flatpickr1', {
                mode: 'range',
                onChange: function(selectedDates, dateStr, instance) {
                    if (selectedDates.length >= 2) {
                        var startDate = selectedDates[0];
                        var endDate = selectedDates[selectedDates.length - 1];

                        // Hitung selisih dalam milidetik
                        var difference = endDate.getTime() - startDate.getTime();

                        // Konversi selisih ke jumlah hari
                        var daysDifference = Math.ceil(difference / (1000 * 60 * 60 * 24)) + 1;

                        document.getElementById("jumlah-hari").textContent = "Jumlah Hari: " +
                            daysDifference;
                        document.getElementById("jumlahHari").value = daysDifference;
                    }
                }
            })
        })
    </script>
    @livewireScripts;
@endsection
