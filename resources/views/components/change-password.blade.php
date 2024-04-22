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
                        name="current_password" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;"
                        required />
                </div>
                <div class="row m-t-xxl">
                    <div class="col-md-6">
                        <label for="settingsNewPassword" class="form-label">Password Baru</label>
                        <input type="password" class="form-control" aria-describedby="settingsNewPassword"
                            name="password" id="password1"
                            placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;" required />
                        <div class="row mt-3">
                            <div class="col-sm-6">
                                <span id="8char" class="material-icons align-middle"
                                    style="color:#FF0004;">close</span>Min. 8 Karakter<br>
                                <span id="ucase" class="material-icons align-middle"
                                    style="color:#FF0004;">close</span>Min. 1 Huruf Kapital
                            </div>
                            <div class="col-sm-6">
                                <span id="lcase" class="material-icons align-middle"
                                    style="color:#FF0004;">close</span>Min. 1 Huruf Kecil<br>
                                <span id="num" class="material-icons align-middle"
                                    style="color:#FF0004;">close</span>Memiliki Angka
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row m-t-xxl">
                    <div class="col-md-6">
                        <label for="settingsConfirmPassword" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" aria-describedby="settingsConfirmPassword"
                            name="password_confirmation" id="password2"
                            placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;" required />
                        <div class="row mt-3">
                            <div class="col-sm-12">
                                <span id="pwmatch" class="material-icons align-middle"
                                    style="color:#FF0004;">close</span>Password Sama
                            </div>
                        </div>
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

<script script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $("input[type=password]").keyup(function() {
            var ucase = new RegExp("[A-Z]+");
            var lcase = new RegExp("[a-z]+");
            var num = new RegExp("[0-9]+");

            if ($("#password1").val().length >= 8) {
                $("#8char").text('done');
                $("#8char").css("color", "#00A41E");
            } else {
                $("#8char").text('close')
                $("#8char").css("color", "#FF0004");
            }

            if (ucase.test($("#password1").val())) {
                $("#ucase").text('done');
                $("#ucase").css("color", "#00A41E");
            } else {
                $("#ucase").text('close')
                $("#ucase").css("color", "#FF0004");
            }

            if (lcase.test($("#password1").val())) {
                $("#lcase").text('done');
                $("#lcase").css("color", "#00A41E");
            } else {
                $("#lcase").text('close')
                $("#lcase").css("color", "#FF0004");
            }

            if (num.test($("#password1").val())) {
                $("#num").text('done');
                $("#num").css("color", "#00A41E");
            } else {
                $("#num").text('close')
                $("#num").css("color", "#FF0004");
            }

            if ($("#password1").val() == $("#password2").val()) {
                $("#pwmatch").text('done');
                $("#pwmatch").css("color", "#00A41E");
            } else {
                $("#pwmatch").text('close')
                $("#pwmatch").css("color", "#FF0004");
            }
        });

    })
</script>

@if (isset($js))
    {{ $js }}
@endisset
