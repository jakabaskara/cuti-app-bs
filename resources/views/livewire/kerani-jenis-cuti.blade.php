 <div class="col">
     <label for="nama" class="form-label">Jenis Cuti</label>
     <select class="form-select " aria-label="Nama Karyawan" name="jenis_cuti" required id="jenisCuti" wire:change='setCuti'>
         <option selected value=""> </option>
         @if ($sisaCutiTahunan != 0 && $sisaCutiPanjang != 0)
             <option value="1"> Cuti Panjang </option>
             <option value="2"> Cuti Tahunan </option>
         @elseif ($sisaCutiTahunan != 0)
             <option value="2"> Cuti Tahunan </option>
         @elseif ($sisaCutiPanjang != 0)
             <option value="1"> Cuti Panjang </option>
         @endif
     </select>
 </div>
