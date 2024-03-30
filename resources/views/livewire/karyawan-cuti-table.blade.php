 <div class="table-container">
     <table class="table table-hover">
         <thead>
             <tr class="text-dark">
                 <th class="text-center text-dark">No.</th>
                 <th class="text-dark">Nama</th>
                 <th class="text-dark">Tanggal Mulai</th>
                 <th class="text-dark">Tanggal Selesai</th>
                 <th class="text-dark">Alasan</th>
                 <th class="text-dark">Alamat</th>
                 <th class="text-dark">Jumlah <br> Hari Cuti</th>
             </tr>
         </thead>
         <tbody>
             @php
                 $i = 1;
             @endphp
             @foreach ($karyawanCuti as $cuti)
                 <tr>
                     <td class="text-center">{{ $i }}</td>
                     <td>{{ $cuti->karyawan->nama }}</td>
                     <td>{{ date('d M Y', strtotime($cuti->tanggal_mulai)) }}</td>
                     <td>{{ date('d M Y', strtotime($cuti->tanggal_selesai)) }}</td>
                 </tr>
                 @php
                     $i++;
                 @endphp
             @endforeach
         </tbody>
     </table>
 </div>
