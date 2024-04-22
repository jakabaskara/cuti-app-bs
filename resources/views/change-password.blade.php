@extends($sidebar . '.layout.main')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger alert-style-light" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-style-light" role="alert">
            {{ session('success') }}
        </div>
    @endif
    <x-change-password>
    </x-change-password>
@endsection
