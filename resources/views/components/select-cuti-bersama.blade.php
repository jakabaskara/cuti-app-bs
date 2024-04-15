<div class="col-xl-6 px-3 mb-3">
    <label for="selectTanggal" class="form-label">Silahkan Pilih Tanggal Cuti Bersama</label>
    <select class="form-select" aria-label="Default select example" id="selectTanggal" name="selectTanggal">
        <option> </option>
        @foreach ($dates as $date)
            <option value="{{ $date }}">{{ $date }}</option>
        @endforeach
    </select>
</div>
