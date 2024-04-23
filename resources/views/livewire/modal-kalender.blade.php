<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="ketModalLabel">Rincian Cuti</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body px-5 py-2">
        <div class="">
            <table class="table table-bordered table-sm">
                <thead class="table-dark">
                    <tr class="text-center">
                        <th>Nama</th>
                        <th>NIK</th>
                        <th>Unit Kerja</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($dataCutis)
                        @foreach ($dataCutis as $dataCuti)
                            <tr>
                                <td>{{ $dataCuti->karyawan->nama }}</td>
                                <td>{{ $dataCuti->karyawan->NIK }}</td>
                                <td>{{ $dataCuti->karyawan->posisi->unitKerja->nama_unit_kerja }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
