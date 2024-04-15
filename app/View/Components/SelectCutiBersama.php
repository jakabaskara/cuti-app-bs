<?php

namespace App\View\Components;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\File;
use Illuminate\View\Component;

class SelectCutiBersama extends Component
{
    /**
     * Create a new component instance.
     */
    public $dates;

    public function __construct()
    {
        $file = File::get(public_path('assets/cuti_bersama.json'));
        $cutiBersama = json_decode($file, true);

        $formattedDates = [];
        foreach ($cutiBersama as $date => $data) {
            // Mengakses data dari array asosiatif berdasarkan kunci (key) tanggal
            $description = $data['summary'] ?? ''; // Misalnya, mengambil deskripsi
            $holiday = $data['holiday'] ?? ''; // Misalnya, mengambil status libur

            // Ubah format tanggal menggunakan Carbon
            $formattedDate = Carbon::createFromFormat('Y-m-d', $date)->translatedFormat('l, d F Y');

            // Simpan data yang diinginkan ke dalam array hasil
            $formattedDates[$date] = [
                'value' => $date,
                'formatted_date' => $formattedDate,
                'description' => $description,
                'holiday' => $holiday,
            ];
        }

        $this->dates = $formattedDates;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.select-cuti-bersama');
    }
}
