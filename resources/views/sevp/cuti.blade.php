@extends('sevp.layout.main')

@section('css')
    <link href="{{ asset('assets/plugins/fullcalendar/lib/main.min.css') }}" rel="stylesheet">
    @livewireStyles()
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <div class="card calendar-container">
                <div class="card-header">
                    <h3>Berita Karyawan Cuti</h3>
                    <hr>
                </div>
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ketModal" tabindex="-1" aria-labelledby="ketModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            @livewire('modal-kalender')
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/plugins/fullcalendar/lib/main.min.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/pages/calendar.js') }}"></script> --}}
    @livewireScripts()
    <script>
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialDate: "{{ date('Y-m-d') }}",
            editable: true,
            selectable: true,
            businessHours: true,
            dayMaxEvents: true,
            events: {!! $dataKalender !!}
        });
        calendar.render();



        calendar.on('dateClick', function(info) {
            console.log('Tanggal yang diklik: ' + info.dateStr);
        });

        calendar.on('eventClick', function(info) {
            console.log('Event yang diklik: ' + info.event.title);
            console.log('Tanggal mulai: ' + info.event.start);

            var date = new Date(info.event.start);

            var year = date.getFullYear();
            var month = String(date.getMonth() + 1).padStart(2,
                '0'); // Tambahkan leading zero jika perlu
            var day = String(date.getDate()).padStart(2, '0'); // Tambahkan leading zero jika perlu

            var formattedDate = year + '-' + month + '-' + day;
            console.log(formattedDate); // Output: '2024-04-22'
            $('#ketModal').modal('show');

            Livewire.dispatch('wait-tanggal', {
                tanggal: formattedDate,
            })
        });
        // Kode event handler Anda
    </script>
@endsection
