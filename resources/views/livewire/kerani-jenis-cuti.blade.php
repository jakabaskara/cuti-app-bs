 <div>
     {{-- Bagian badge sisa cuti: di-render ulang Livewire saat karyawan berganti --}}
     <div class="col mb-2">
         <span class="badge bg-{{ ($sisaCutiTahunan ?? 0) > 0 ? 'primary' : 'secondary' }} me-2">
             Sisa Cuti Tahunan: {{ $sisaCutiTahunan ?? 0 }} hari
         </span>
         <span class="badge bg-{{ ($sisaCutiPanjang ?? 0) > 0 ? 'success' : 'secondary' }}">
             Sisa Cuti Panjang: {{ $sisaCutiPanjang ?? 0 }} hari
         </span>
     </div>

     {{-- wire:ignore: Livewire TIDAK akan me-render-ulang bagian ini --}}
     <div wire:ignore>
         <div class="col mb-3">
             <label class="form-label fw-bold">Tanggal Cuti Tahunan</label>
             <input type="text"
                    class="form-control flatpickr-tahunan"
                    id="tanggal_cuti_tahunan_input"
                    placeholder="Pilih Rentang Tanggal Cuti Tahunan">
             <input type="hidden" name="tanggal_cuti_tahunan" id="tanggal_cuti_tahunan_val">
             <small id="info-hari-tahunan"></small>
         </div>
         <div class="col mb-3">
             <label class="form-label fw-bold">Tanggal Cuti Panjang</label>
             <input type="text"
                    class="form-control flatpickr-panjang"
                    id="tanggal_cuti_panjang_input"
                    placeholder="Pilih Rentang Tanggal Cuti Panjang">
             <input type="hidden" name="tanggal_cuti_panjang" id="tanggal_cuti_panjang_val">
             <small id="info-hari-panjang"></small>
         </div>
         <div>
             <input type="hidden" name="jumlah_cuti_tahunan" id="jumlah_cuti_tahunan" value="0">
             <input type="hidden" name="jumlah_cuti_panjang" id="jumlah_cuti_panjang" value="0">
             <input type="hidden" name="jumlahHariCuti" id="jumlahHariCuti" value="0">
         </div>
     </div>
 </div>
