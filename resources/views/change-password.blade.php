@extends($sidebar . '.layout.main')

@section('content')
    <x-change-password>
        @slot('js')
            <script>
                console.log('Halo')
            </script>
        @endslot
    </x-change-password>
@endsection
