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
        $dates = array_keys($cutiBersama);
        // Ubah format tanggal menggunakan Carbon
        $formattedDates = [];
        foreach ($dates as $date) {
            $formattedDate = Carbon::createFromFormat('Y-m-d', $date)->translatedFormat('l, d F Y');
            $formattedDates[$date] = $formattedDate;
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
