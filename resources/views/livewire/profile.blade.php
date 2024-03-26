{{-- <div>
    <p wire:model='keterangan'>{{ $keterangan }}</p>
</div> --}}

<div>
    <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="profileModalLabel">Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>NIK:</strong> {{ $nik }}</p>
                    <p><strong>Nama:</strong> {{ $nama }}</p>
                    <p><strong>Jabatan:</strong> {{ $jabatan }}</p>
                    <p><strong>Tanggal Diangkat Staf:</strong> {{ $tgl_diangkat_staf }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
