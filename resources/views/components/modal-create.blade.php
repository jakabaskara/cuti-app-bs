<div class="modal show " id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel">
    <form>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Form Pengajuan Cuti</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col">
                            <label for="nama" class="form-label">Nama Karyawan</label>
                            <select class="form-select" aria-label="Nama Karyawan" name="karyawan" required>
                                <option selected value=""> </option>
                                @foreach ($dataPairing as $pairing)
                                    <option value="{{ $pairing->bawahan->karyawan->first()->id }}">
                                        {{ $pairing->bawahan->karyawan->first()->nama }}
                                    </option>
                                @endforeach
                                {{-- @foreach ($karyawans as $karyawan)
                                    <option value="{{ $karyawan->id }}">{{ $karyawan->nama }}</option>
                                @endforeach --}}
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="nama" class="form-label">Jenis Cuti</label>
                            <select class="form-select" aria-label="Nama Karyawan" name="jenis_cuti" required>
                                <option selected value=""> </option>
                                <option value="1">Cuti Tahunan</option>
                                <option value="2">Cuti Panjang</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="daterange" class="form-label">Tanggal Cuti</label>
                            <input type="text" class="form-control flatpickr1" name="tanggal_cuti" required />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <p class="text-dark" id="jumlah-hari"> Jumlah Hari Cuti: 0</p>
                            <input type="hidden" id="jumlahHari" name="jumlah_cuti" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="alasan" class="form-label">Alasan Cuti</label>
                            <input type="text" class="form-control" name="alasan" required />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="alamat" class="form-label">Alamat</label>
                            <input type="text" class="form-control" name="alamat" required />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div wire:loading class="f-14 text-dark"> <span
                            class="spinner-grow text-danger align-middle"></span>
                        Loading...</div>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batalkan</button>
                    <button type="submit" class="btn btn-primary">Ajukan</button>
                </div>
            </div>
        </div>
    </form>
</div>
