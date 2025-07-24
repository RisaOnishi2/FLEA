<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item_condition;

class ConditionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $conditions = [
            '良好', 
            '目立った傷や汚れなし', 
            'やや傷や汚れあり', 
            '状態が悪い'
        ];

        foreach ($conditions as $condition) {
            item_condition::create(['condition' => $condition]);
        }
    }
}
