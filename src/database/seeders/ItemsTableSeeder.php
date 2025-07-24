<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Item::create([
            'user_id' => 1,
            'name' => '腕時計',
            'price' => 15000,
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'image' => 'Armani+Mens+Clock.jpg',
            'brand' => null,
            'item_condition_id' => 1,  // condition_idを追加
            'is_sold' => false,
        ]);

        Item::create([
            'user_id' => 2,
            'name' => 'HDD',
            'price' => 5000,
            'description' => '高速で信頼性の高いハードディスク',
            'image' => 'HDD+Hard+Disk.jpg',
            'brand' => null,
            'item_condition_id' => 2,  // condition_idを追加
            'is_sold' => false,
        ]);

        Item::create([
            'user_id' => 3,
            'name' => '玉ねぎ3束',
            'price' => 300,
            'description' => '新鮮な玉ねぎ3束のセット',
            'image' => 'iLoveIMG+d.jpg',
            'brand' => null,
            'item_condition_id' => 3,  // condition_idを追加
            'is_sold' => false,
        ]);

        Item::create([
            'user_id' => 4,
            'name' => '革靴',
            'price' => 4000,
            'description' => 'クラシックなデザインの革靴',
            'image' => 'Leather+Shoes+Product+Photo.jpg',
            'brand' => null,
            'item_condition_id' => 4,  // condition_idを追加
            'is_sold' => false,
        ]);

        Item::create([
            'user_id' => 5,
            'name' => 'ノートPC',
            'price' => 45000,
            'description' => '高性能なノートパソコン',
            'image' => 'Living+Room+Laptop.jpg',
            'brand' => null,
            'item_condition_id' => 1,  // condition_idを追加
            'is_sold' => false,
        ]);

        Item::create([
            'user_id' => 6,
            'name' => 'マイク',
            'price' => 8000,
            'description' => '高音質のレコーディング用マイク',
            'image' => 'Music+Mic+4632231.jpg',
            'brand' => null,
            'item_condition_id' => 2,  // condition_idを追加
            'is_sold' => false,
        ]);

        Item::create([
            'user_id' => 7,
            'name' => 'ショルダーバッグ',
            'price' => 3500,
            'description' => 'おしゃれなショルダーバッグ',
            'image' => 'Purse+fashion+pocket.jpg',
            'brand' => null,
            'item_condition_id' => 3,  // condition_idを追加
            'is_sold' => false,
        ]);

        Item::create([
            'user_id' => 8,
            'name' => 'タンブラー',
            'price' => 500,
            'description' => '使いやすいタンブラー',
            'image' => 'Tumbler+souvenir.jpg',
            'brand' => null,
            'item_condition_id' => 4,  // condition_idを追加
            'is_sold' => false,
        ]);

        Item::create([
            'user_id' => 9,
            'name' => 'コーヒーミル',
            'price' => 4000,
            'description' => '手動のコーヒーミル',
            'image' => 'Waitress+with+Coffee+Grinder.jpg',
            'brand' => null,
            'item_condition_id' => 1,  // condition_idを追加
            'is_sold' => false,
        ]);

        Item::create([
            'user_id' => 10,
            'name' => 'メイクセット',
            'price' => 2500,
            'description' => '便利なメイクアップセット',
            'image' => '外出メイクアップセット.jpg',
            'brand' => null,
            'item_condition_id' => 2,  // condition_idを追加
            'is_sold' => false,
        ]);
    }
}
