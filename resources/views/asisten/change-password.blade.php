@extends('asisten.layout.main')

@section('content')
    {{-- <x-change-password> --}}
    <div class="card">
        <div class="card-body">
            <div class="settings-security-two-factor">
                <h5>Ubah Password</h5>
            </div>
            <div class="row m-t-xxl">
                <div class="col-md-6">
                    <label for="settingsCurrentPassword" class="form-label">Password Sekarang</label>
                    <input type="password" class="form-control" aria-describedby="settingsCurrentPassword"
                        placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;" />
                    <div id="settingsCurrentPassword" class="form-text">
                        Jangan berikan password anda kepada siapapun
                    </div>
                </div>
            </div>
            <div class="row m-t-xxl">
                <div class="col-md-6">
                    <label for="settingsNewPassword" class="form-label">Password Baru</label>
                    <input type="password" class="form-control" aria-describedby="settingsNewPassword"
                        placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;" />
                </div>
            </div>
            <div class="row m-t-xxl">
                <div class="col-md-6">
                    <label for="settingsConfirmPassword" class="form-label">Konfirmasi Password</label>
                    <input type="password" class="form-control" aria-describedby="settingsConfirmPassword"
                        placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;" />
                </div>
            </div>

            <div class="row m-t-lg">
                <div class="col">
                    <a href="#" class="btn btn-primary m-t-sm">Change Password</a>
                </div>
            </div>
        </div>
    </div>
    {{-- </x-change-password> --}}
@endsection
