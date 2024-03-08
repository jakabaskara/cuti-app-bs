@extends('sevp.layout.main')

@section('css')
    <link href="{{ asset('assets/plugins/fullcalendar/lib/main.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="app-content">
        <div class="content-wrapper">
            <h5>Berita Karyawan Cuti</h5>
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="card calendar-container">
                            <div class="card-body">
                                <div id="calendar"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/plugins/fullcalendar/lib/main.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/calendar.js') }}"></script>
@endsection
