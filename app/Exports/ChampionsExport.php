<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ChampionsExport implements FromView
{
    protected $champions;

    public function __construct($champions)
    {
        $this->champions = $champions;
    }

    public function view(): View
    {
        return view('exports.champions', [
            'champions' => $this->champions,
        ]);
    }
}
