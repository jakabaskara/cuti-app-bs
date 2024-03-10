 <div>
     {{-- <div class="col">
         <label for="nama" class="form-label">Jenis Cuti</label>
         <select class="form-select " aria-label="Nama Karyawan" name="jenis_cuti" required id="jenisCuti"
             wire:change='setCuti'>
             <option selected value=""> </option>


         </select>
     </div> --}}
     <div class="col mb-3">
         <label for="cutiPanjang" class="form-label">Jumlah Cuti Panjang Diambil</label>
         <input type="text" class="form-control" name="jumlah_cuti_panjang" id="cutiPanjang" readonly required
             value="{{ $cutiPanjangDiambil }}">
     </div>
     <div class="col mb-3">
         <label for="cutiTahunan" class="form-label">Jumlah Cuti Tahunan Diambil</label>
         <input type="text" class="form-control" name="jumlah_cuti_tahunan" id="cutiTahunan" readonly
             value="{{ $cutiTahunanDiambil }}">
     </div>
 </div>
