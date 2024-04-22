<form action="" method="post">
    @csrf


    <div class="card">
        <div class="auth-credentials card-body">
            <div class="settings-security-two-factor">
                <h5>Ubah Password</h5>
            </div>
            <div class="row m-t-xxl">
                <div class="col-md-6">
                    <label for="settingsCurrentPassword" class="form-label">Password Sekarang</label>
                    <input type="password" class="form-control" aria-describedby="settingsCurrentPassword"
                        placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;" required />
                </div>
            </div>
            <div class="row m-t-xxl">
                <div class="col-md-6">
                    <label for="settingsNewPassword" class="form-label">Password Baru</label>
                    <input type="password" class="form-control" aria-describedby="settingsNewPassword"
                        placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;" required />
                    <div id="settingsCurrentPassword" class="form-text text-warning">
                        Password Harus Berisikan Angka dan Huruf
                    </div>
                </div>
            </div>
            <div class="row m-t-xxl">
                <div class="col-md-6">
                    <label for="settingsConfirmPassword" class="form-label">Konfirmasi Password</label>
                    <input type="password" class="form-control" aria-describedby="settingsConfirmPassword"
                        placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;" required />
                </div>
            </div>

            <div class="row m-t-lg">
                <div class="col auth-submit">
                    <button type="submit" class="btn btn-primary m-t-sm">Change Password</button>
                </div>
            </div>
        </div>
    </div>

</form>

@if (isset($js))
    {{ $js }}
@endisset
