<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FavoriteSeeder extends Seeder
{
    public function run(): void
    {
        $stores = Store::all();
        $customers = Customer::all();

        if ($stores->isEmpty() || $customers->isEmpty()) {
            $this->command->error('店舗または顧客が見つかりません。先にStoreUserSeederとCustomerSeederを実行してください。');
            return;
        }

        $favorites = [
            // 山田太郎のお気に入り
            [
                'customer_index' => 0,
                'store_indices' => [0, 1, 4], // ラーメン、イタリアン、焼肉
            ],
            // 佐藤花子のお気に入り
            [
                'customer_index' => 1,
                'store_indices' => [1, 2, 3], // イタリアン、カフェ、美容院
            ],
            // 鈴木一郎のお気に入り
            [
                'customer_index' => 2,
                'store_indices' => [0, 2], // ラーメン、カフェ
            ],
            // 田中美咲のお気に入り
            [
                'customer_index' => 3,
                'store_indices' => [1, 3], // イタリアン、美容院
            ],
            // 高橋健太のお気に入り
            [
                'customer_index' => 4,
                'store_indices' => [0, 4], // ラーメン、焼肉
            ],
        ];

        $totalFavorites = 0;
        foreach ($favorites as $favoriteData) {
            if (!isset($customers[$favoriteData['customer_index']])) {
                continue;
            }

            $customer = $customers[$favoriteData['customer_index']];

            foreach ($favoriteData['store_indices'] as $storeIndex) {
                if (!isset($stores[$storeIndex])) {
                    continue;
                }

                $store = $stores[$storeIndex];

                // お気に入りを追加（中間テーブルに挿入）
                DB::table('favorites')->insert([
                    'customer_id' => $customer->id,
                    'store_id' => $store->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $totalFavorites++;
            }
        }

        $this->command->info("お気に入りを{$totalFavorites}件作成しました。");

        // 各顧客のお気に入り件数を表示
        foreach ($customers as $index => $customer) {
            $count = $customer->favorites()->count();
            $this->command->info("{$customer->name}さん: {$count}件のお気に入り");
        }
    }
}
