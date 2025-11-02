<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\Category;
use App\Models\Area;
use Illuminate\Database\Seeder;

class TestStoreSeeder extends Seeder
{
    public function run(): void
    {
        // カテゴリーとエリアを取得
        $category = Category::whereNotNull('parent_id')->first();
        $area = Area::where('level', 2)->first();

        if (!$category || !$area) {
            $this->command->error('カテゴリーまたはエリアが見つかりません。先にCategorySeederとAreaSeederを実行してください。');
            return;
        }

        // テスト店舗を作成
        $store = Store::create([
            'store_name' => 'サンプル飲食店',
            'industry' => $category->name, // 既存のindustryフィールド
            'category_id' => $category->id,
            'area_id' => $area->id,
            'phone' => '03-1234-5678',
            'email' => 'sample@example.com',
            'contact_name' => 'サンプル太郎',
            'address' => '東京都渋谷区道玄坂1-1-1', // 既存のaddressフィールド
            'prefecture' => '東京都',
            'city' => '渋谷区',
            'street_address' => '道玄坂1-1-1',
            'postal_code' => '150-0043',
            'average_rating' => 4.5,
            'review_count' => 10,
            'status' => 'active',
        ]);

        // 店舗詳細を作成
        $store->storeDetail()->create([
            'description' => 'テスト用の店舗です。美味しい料理を提供しています。地元の新鮮な食材を使用した創作料理が自慢です。',
            'access_info' => '渋谷駅から徒歩5分',
            'parking_info' => '近隣にコインパーキングあり',
            'payment_methods' => '現金、クレジットカード、電子マネー',
            'seats' => 40,
            'wifi' => true,
            'credit_card' => true,
        ]);

        // 営業時間を作成（月〜日）
        $days = ['日', '月', '火', '水', '木', '金', '土'];
        for ($i = 0; $i <= 6; $i++) {
            $store->businessHours()->create([
                'day_of_week' => $i,
                'is_closed' => false,
                'open_time' => '11:00:00',
                'close_time' => '22:00:00',
            ]);
        }

        // メニューを作成
        $store->menus()->create([
            'name' => '本日のランチセット',
            'description' => '日替わりメイン + サラダ + スープ + ドリンク',
            'price' => 1200,
            'category' => 'ランチ',
            'is_available' => true,
        ]);

        $store->menus()->create([
            'name' => 'プレミアムディナーコース',
            'description' => '前菜・スープ・魚料理・肉料理・デザート・コーヒー',
            'price' => 5000,
            'category' => 'ディナー',
            'is_available' => true,
        ]);

        $store->menus()->create([
            'name' => 'こだわりのハンバーグ',
            'description' => '自家製デミグラスソース',
            'price' => 1500,
            'category' => 'メイン',
            'is_available' => true,
        ]);

        // クーポンを作成
        $store->coupons()->create([
            'title' => '新規会員登録で10%OFF',
            'description' => '全メニュー10%割引',
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'code' => 'WELCOME10',
            'start_date' => now(),
            'end_date' => now()->addMonths(3),
            'is_active' => true,
        ]);

        $this->command->info("テスト店舗を作成しました！ID: {$store->id}");
        $this->command->info("店舗名: {$store->store_name}");
        $this->command->info("URL: https://www.minnanosendenbu.jp/system/stores/{$store->id}");
    }
}
