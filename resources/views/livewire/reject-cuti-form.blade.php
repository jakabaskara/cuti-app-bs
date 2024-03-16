<div>

    {{-- @foreach ($permintaanCuti as $cutiPending)
        <tr class="text-center">
            <td class="text-dark noti">
                <textarea wire:model="alasan_ditolak" class="form-control" placeholder="Masukkan alasan penolakan"></textarea>
                @error('alasan_ditolak')
                    <span class="error">{{ $message }}</span>
                @enderror

                <button class="btn btn-danger">Tolak Cuti</button>
                <button class="btn btn-danger" wire:click='tolak({{ $cutiPending->id }})'>Tolak</button>
            </td>
        </tr>
    @endforeach --}}

    <textarea wire:model="alasan_ditolak" class="form-control" placeholder="Masukkan alasan penolakan"></textarea>
    @error('alasan_ditolak')
        <span class="error">{{ $message }}</span>
    @enderror

    <button class="btn btn-danger">Tolak Cuti</button>
 </div>
