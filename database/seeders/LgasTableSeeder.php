<?php

namespace Database\Seeders;

use App\Models\Lga;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class LgasTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('lgas')->delete();

        $state_id = [
            38,39,40
        ];

        $lgas = [
            "Aba North", "Aba South", "Arochukwu"
        ];

        for ($i = 0; $i < count($lgas); $i++) {
            Lga::create(['state_id' => $state_id[$i], 'name' => $lgas[$i]]);
        }
    }
}
