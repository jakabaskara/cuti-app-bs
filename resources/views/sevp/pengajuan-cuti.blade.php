@extends('sevp.layout.main')

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
                        </div>
                        <div class="col">
                            <a href="{{ route('admin.download.pdf') }}" class="btn btn-primary">PDF</a>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm table-hover">
                                    <thead class="table-dark">
                                        <tr class="text-center">
                                            <th>No.</th>
                                            <th>NIK</th>
                                            <th>Nama</th>
                                            <th>Jenis Cuti</th>
                                            <th>Periode Tanggal</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                            <select class="form-select" aria-label="Nama Karyawan">
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
                            <select class="form-select" aria-label="Nama Karyawan">
                                <option selected value=""> </option>
                                <option value="1">Cuti Tahunan</option>
                                <option value="2">Cuti Panjang</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="daterange" class="form-label">Tanggal Cuti</label>
                            <input type="text" class="form-control flatpickr1" name="daterange" value="" />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <p class="text-dark" id="jumlah-hari"> Jumlah Hari Cuti: 0</p>
                            <input type="hidden" id="jumlahHari" name="jumlahCuti">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="alasan" class="form-label">Alasan Cuti</label>
                            <input type="text" class="form-control" name="alasan" value="" />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="alamat" class="form-label">Alamat</label>
                            <input type="text" class="form-control" name="alamat" value="" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batalkan</button>
                    <form action="{{ route('admin.tambahCuti') }}" method="post">
                        @csrf
                        <button type="submit" class="btn btn-primary">Ajukan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
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

                    document.getElementById("jumlah-hari").textContent = "Jumlah Hari: " + daysDifference;
                    document.getElementById("jumlahHari").value = daysDifference;
                }
            }
        })
    </script>
@endsection
