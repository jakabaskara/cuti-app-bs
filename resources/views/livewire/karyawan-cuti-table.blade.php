 <div class="table-container">
     <table class="table table-hover">
         <thead>
             <tr>
                 <th class="text-center">No.</th>
                 <th>Nama</th>
                 <th>Mulai</th>
                 <th>Selesai</th>
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
