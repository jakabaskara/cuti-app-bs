<?php

namespace App\View\Components;

use App\Models\MasterLiburKalender;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SelectCutiBersama extends Component
{
    /**
     * Create a new component instance.
     */
    public $dates;

    public function __construct()
    {
        $cutiBersama = MasterLiburKalender::where('jenis_libur', 'cuti_bersama')
            ->orderBy('tanggal', 'asc')
            ->get();

        $formattedDates = [];
        foreach ($cutiBersama as $item) {
            $date = $item->tanggal;
            $description = $item->description;

            $formattedDate = Carbon::createFromFormat('Y-m-d', $date)->translatedFormat('l, d F Y');

            $formattedDates[$date] = [
                'value' => $date,
                'formatted_date' => $formattedDate,
                'description' => $description,
                'holiday' => true,
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
