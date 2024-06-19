<?php

namespace App\Livewire;

use App\Models\PermintaanCuti;
use App\Models\RiwayatCuti;
use App\Models\User;
use App\Notifications\SendNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;

class TableMengetahuiCuti extends Component
{
    public $permintaanCuti;

    public function render()
    {
        $karyawan = Auth::user()->karyawan;

        $permintaanCuti = PermintaanCuti::select('permintaan_cuti.*')
            ->join('karyawan', 'permintaan_cuti.id_karyawan', '=', 'karyawan.id')
            ->join('pairing', 'karyawan.id_posisi', '=', 'pairing.id_bawahan')
            ->where('pairing.id_atasan', $karyawan->id_posisi)
            ->where('permintaan_cuti.is_approved', '=', 0)
            ->where('permintaan_cuti.is_checked', '=', 0)
            ->get();

        $this->permintaanCuti = $permintaanCuti;
        return view('livewire.table-mengetahui-cuti');
    }

    public function setujui($id)
    {
        $karyawan = Auth::user()->karyawan;
        $idUser = Auth::user()->id;
        $user = User::find($idUser);

        $dataCuti = PermintaanCuti::find($id);
        DB::transaction(function () use ($dataCuti, $karyawan, $user) {

            if ($dataCuti) {
                $dataCuti->is_checked = 1;
                $dataCuti->save();
            }

            $riwayat = RiwayatCuti::where('id_permintaan_cuti', $dataCuti->id)->first();
            $riwayat->nama_checker = $karyawan->nama;
            $riwayat->jabatan_checker = $karyawan->jabatan;
            $riwayat->save();

            // $message = "Terdapat Permintaan Cuti Baru\n";
            // $message .= "Nama: " . $riwayat->permintaanCuti->karyawan->nama . "\n";
            // $message .= "Tanggal Mulai: " . date('d M Y', strtotime($riwayat->permintaanCuti->tanggal_mulai)) . "\n";
            // $message .= "Tanggal Selesai: " . date('d M Y', strtotime($riwayat->permintaanCuti->tanggal_selesai)) . "\n";
            // $message .= "Jumlah: " . $riwayat->permintaanCuti->jumlah_cuti_panjang + $riwayat->permintaanCuti->jumlah_cuti_tahunan . " HK\n";
            // $message .= "Alasan: " . $riwayat->permintaanCuti->alasan;

            // Notification::send($user, new SendNotification($message));

            // $keyboard = Keyboard::make()->inline();

            // $buttonSetujui = Keyboard::inlineButton(['text' => 'Klik untuk konfirmasi',  'web_app' => ['url' => 'https://cuti.reg5palmco.com']]);

            // $keyboard->row([$buttonSetujui]);

            // $pesan = 'Apakah Cuti Disetujui?';

            // $keyboard = json_encode($keyboard);

            // $response = Telegram::sendMessage([
            //     'chat_id' => '1176854977',
            //     'text' => $pesan,
            //     'reply_markup' => $keyboard,
            // ]);
        });
        $this->dispatch('refresh');
        $this->dispatch('ketahui');
    }
}
