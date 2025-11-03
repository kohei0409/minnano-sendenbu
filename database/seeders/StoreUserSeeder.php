<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Store;
use App\Models\Category;
use App\Models\Area;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StoreUserSeeder extends Seeder
{
    public function run(): void
    {
        // カテゴリーとエリアを取得
        $categoryRamen = Category::where('slug', 'ramen')->first();
        $categoryItalian = Category::where('slug', 'italian')->first();
        $categoryCafe = Category::where('slug', 'cafe')->first();
        $categoryHairSalon = Category::where('slug', 'hair-salon')->first();
        $categoryYakiniku = Category::where('slug', 'yakiniku')->first();

        $areaShibuya = Area::where('name', '渋谷区')->first();
        $areaShinjuku = Area::where('name', '新宿区')->first();
        $areaMinato = Area::where('name', '港区')->first();
        $areaYokohama = Area::where('name', '横浜市')->first();

        // 店舗オーナー1: ラーメン店
        $owner1 = User::create([
            'username' => 'ramen_owner',
            'name' => '小林店主',
            'email' => 'ramen@example.com',
            'password' => Hash::make('password'),
            'role' => 'store_owner',
            'user_level' => 'premium1',
            'status' => 'active',
            'email_verified_at' => now(),
            'approved_at' => now(),
        ]);

        $store1 = Store::create([
            'user_id' => $owner1->id,
            'store_name' => '麺屋 一番',
            'industry' => $categoryRamen ? $categoryRamen->name : 'ラーメン',
            'category_id' => $categoryRamen?->id,
            'area_id' => $areaShibuya?->parent_id,
            'phone' => '03-1111-1111',
            'email' => 'ramen@example.com',
            'contact_name' => '小林店主',
            'address' => '東京都渋谷区宇田川町1-1-1',
            'prefecture' => '東京都',
            'city' => '渋谷区',
            'street_address' => '宇田川町1-1-1',
            'postal_code' => '150-0042',
            'average_rating' => 4.5,
            'review_count' => 25,
            'status' => 'active',
            'is_featured' => true,
            'featured_until' => now()->addMonths(3),
        ]);

        // ユーザーにstore_idを設定
        $owner1->update(['store_id' => $store1->id]);

        $store1->storeDetail()->create([
            'description' => '創業30年の老舗ラーメン店。自慢の鶏白湯スープは毎朝仕込み、コク深い味わいが特徴です。特製の細麺との相性も抜群。深夜営業もしているので、仕事帰りにもぜひお立ち寄りください。',
            'access_info' => '渋谷駅ハチ公口から徒歩3分',
            'parking_info' => '提携駐車場あり（2時間無料）',
            'payment_methods' => '現金、クレジットカード、電子マネー、QRコード決済',
            'seats' => 20,
            'wifi' => true,
            'credit_card' => true,
        ]);

        // 営業時間
        for ($i = 0; $i <= 6; $i++) {
            $store1->businessHours()->create([
                'day_of_week' => $i,
                'is_closed' => false,
                'open_time' => '11:00:00',
                'close_time' => '02:00:00',
            ]);
        }

        // メニュー
        $store1->menus()->createMany([
            [
                'name' => '特製鶏白湯ラーメン',
                'description' => '自慢の鶏白湯スープに特製細麺',
                'price' => 950,
                'category' => 'ラーメン',
                'is_available' => true,
            ],
            [
                'name' => '醤油ラーメン',
                'description' => '昔ながらの醤油味',
                'price' => 800,
                'category' => 'ラーメン',
                'is_available' => true,
            ],
            [
                'name' => 'チャーシュー麺',
                'description' => '特製チャーシュー5枚',
                'price' => 1200,
                'category' => 'ラーメン',
                'is_available' => true,
            ],
            [
                'name' => '餃子（6個）',
                'description' => '手作り餃子',
                'price' => 400,
                'category' => 'サイドメニュー',
                'is_available' => true,
            ],
        ]);

        // クーポン
        $store1->coupons()->create([
            'title' => 'ランチタイム限定！100円OFF',
            'description' => '平日11:00-15:00限定',
            'discount_type' => 'amount',
            'discount_value' => 100,
            'code' => 'LUNCH100',
            'start_date' => now(),
            'end_date' => now()->addMonths(3),
            'is_active' => true,
        ]);

        // 店舗オーナー2: イタリアンレストラン
        $owner2 = User::create([
            'username' => 'italian_owner',
            'name' => '佐々木店主',
            'email' => 'italian@example.com',
            'password' => Hash::make('password'),
            'role' => 'store_owner',
            'user_level' => 'premium1',
            'status' => 'active',
            'email_verified_at' => now(),
            'approved_at' => now(),
        ]);

        $store2 = Store::create([
            'user_id' => $owner2->id,
            'store_name' => 'トラットリア ベッラ',
            'industry' => $categoryItalian ? $categoryItalian->name : 'イタリアン',
            'category_id' => $categoryItalian?->id,
            'area_id' => $areaMinato?->parent_id,
            'phone' => '03-2222-2222',
            'email' => 'italian@example.com',
            'contact_name' => '佐々木店主',
            'address' => '東京都港区六本木3-3-3',
            'prefecture' => '東京都',
            'city' => '港区',
            'street_address' => '六本木3-3-3',
            'postal_code' => '106-0032',
            'average_rating' => 4.7,
            'review_count' => 42,
            'status' => 'active',
            'is_featured' => true,
            'featured_until' => now()->addMonths(3),
        ]);

        // ユーザーにstore_idを設定
        $owner2->update(['store_id' => $store2->id]);

        $store2->storeDetail()->create([
            'description' => '本場イタリアで修行したシェフが作る本格イタリアン。新鮮な魚介と厳選したイタリア産食材を使用した料理の数々をお楽しみください。ワインのラインナップも充実しています。',
            'access_info' => '六本木駅3番出口から徒歩2分',
            'parking_info' => '近隣にコインパーキング多数あり',
            'payment_methods' => '現金、クレジットカード、電子マネー',
            'seats' => 50,
            'wifi' => true,
            'credit_card' => true,
        ]);

        for ($i = 0; $i <= 6; $i++) {
            $store2->businessHours()->create([
                'day_of_week' => $i,
                'is_closed' => $i === 2, // 火曜定休
                'open_time' => $i !== 2 ? '11:30:00' : null,
                'close_time' => $i !== 2 ? '23:00:00' : null,
            ]);
        }

        $store2->menus()->createMany([
            [
                'name' => 'マルゲリータ',
                'description' => 'トマト、モッツァレラ、バジル',
                'price' => 1500,
                'category' => 'ピッツァ',
                'is_available' => true,
            ],
            [
                'name' => 'カルボナーラ',
                'description' => '卵とパンチェッタの濃厚パスタ',
                'price' => 1800,
                'category' => 'パスタ',
                'is_available' => true,
            ],
            [
                'name' => 'ペスカトーレ',
                'description' => '魚介たっぷりのトマトパスタ',
                'price' => 2200,
                'category' => 'パスタ',
                'is_available' => true,
            ],
            [
                'name' => 'ランチコース',
                'description' => '前菜・パスタ・ドリンク',
                'price' => 1200,
                'category' => 'ランチ',
                'is_available' => true,
            ],
            [
                'name' => 'ディナーコース',
                'description' => '前菜・パスタ・メイン・デザート・ドリンク',
                'price' => 4500,
                'category' => 'ディナー',
                'is_available' => true,
            ],
        ]);

        $store2->coupons()->create([
            'title' => 'ディナーコース10%OFF',
            'description' => 'ディナーコース限定',
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'code' => 'DINNER10',
            'start_date' => now(),
            'end_date' => now()->addMonths(2),
            'is_active' => true,
        ]);

        // 店舗オーナー3: カフェ
        $owner3 = User::create([
            'username' => 'cafe_owner',
            'name' => '田中オーナー',
            'email' => 'cafe@example.com',
            'password' => Hash::make('password'),
            'role' => 'store_owner',
            'user_level' => 'free',
            'status' => 'active',
            'email_verified_at' => now(),
            'approved_at' => now(),
        ]);

        $store3 = Store::create([
            'user_id' => $owner3->id,
            'store_name' => 'カフェ モーニングブルー',
            'industry' => $categoryCafe ? $categoryCafe->name : 'カフェ',
            'category_id' => $categoryCafe?->id,
            'area_id' => $areaShinjuku?->parent_id,
            'phone' => '03-3333-3333',
            'email' => 'cafe@example.com',
            'contact_name' => '田中オーナー',
            'address' => '東京都新宿区新宿5-5-5',
            'prefecture' => '東京都',
            'city' => '新宿区',
            'street_address' => '新宿5-5-5',
            'postal_code' => '160-0022',
            'average_rating' => 4.3,
            'review_count' => 18,
            'status' => 'active',
        ]);

        // ユーザーにstore_idを設定
        $owner3->update(['store_id' => $store3->id]);

        $store3->storeDetail()->create([
            'description' => '静かな路地裏にある隠れ家カフェ。自家焙煎のコーヒーと手作りスイーツが自慢です。Wi-Fi完備で電源も利用可能なので、リモートワークにも最適です。',
            'access_info' => '新宿三丁目駅C8出口から徒歩5分',
            'parking_info' => 'なし',
            'payment_methods' => '現金、クレジットカード、電子マネー',
            'seats' => 25,
            'wifi' => true,
            'credit_card' => true,
        ]);

        for ($i = 0; $i <= 6; $i++) {
            $store3->businessHours()->create([
                'day_of_week' => $i,
                'is_closed' => $i === 3, // 水曜定休
                'open_time' => $i !== 3 ? '08:00:00' : null,
                'close_time' => $i !== 3 ? '20:00:00' : null,
            ]);
        }

        $store3->menus()->createMany([
            [
                'name' => 'ブレンドコーヒー',
                'description' => '当店自慢の自家焙煎ブレンド',
                'price' => 500,
                'category' => 'ドリンク',
                'is_available' => true,
            ],
            [
                'name' => 'カフェラテ',
                'description' => 'ラテアート付き',
                'price' => 600,
                'category' => 'ドリンク',
                'is_available' => true,
            ],
            [
                'name' => 'チーズケーキ',
                'description' => '濃厚ベイクドチーズケーキ',
                'price' => 550,
                'category' => 'スイーツ',
                'is_available' => true,
            ],
            [
                'name' => 'モーニングセット',
                'description' => 'トースト・サラダ・ドリンク',
                'price' => 800,
                'category' => 'モーニング',
                'is_available' => true,
            ],
        ]);

        // 店舗オーナー4: 美容院
        $owner4 = User::create([
            'username' => 'salon_owner',
            'name' => '山本オーナー',
            'email' => 'salon@example.com',
            'password' => Hash::make('password'),
            'role' => 'store_owner',
            'user_level' => 'premium2',
            'status' => 'active',
            'email_verified_at' => now(),
            'approved_at' => now(),
        ]);

        $store4 = Store::create([
            'user_id' => $owner4->id,
            'store_name' => 'ヘアサロン グレイス',
            'industry' => $categoryHairSalon ? $categoryHairSalon->name : '美容院',
            'category_id' => $categoryHairSalon?->id,
            'area_id' => $areaYokohama?->parent_id,
            'phone' => '045-4444-4444',
            'email' => 'salon@example.com',
            'contact_name' => '山本オーナー',
            'address' => '神奈川県横浜市西区みなとみらい7-7-7',
            'prefecture' => '神奈川県',
            'city' => '横浜市',
            'street_address' => '西区みなとみらい7-7-7',
            'postal_code' => '220-0012',
            'average_rating' => 4.8,
            'review_count' => 35,
            'status' => 'active',
            'is_featured' => true,
            'featured_until' => now()->addMonths(3),
        ]);

        // ユーザーにstore_idを設定
        $owner4->update(['store_id' => $store4->id]);

        $store4->storeDetail()->create([
            'description' => '経験豊富なスタイリストがあなたに最適なヘアスタイルをご提案。最新のトレンドを取り入れながら、一人ひとりのライフスタイルに合わせたスタイルを提供します。',
            'access_info' => 'みなとみらい駅から徒歩3分',
            'parking_info' => '提携駐車場あり（3時間無料）',
            'payment_methods' => '現金、クレジットカード、電子マネー',
            'seats' => 8,
            'wifi' => true,
            'credit_card' => true,
        ]);

        for ($i = 0; $i <= 6; $i++) {
            $store4->businessHours()->create([
                'day_of_week' => $i,
                'is_closed' => $i === 1, // 月曜定休
                'open_time' => $i !== 1 ? '10:00:00' : null,
                'close_time' => $i !== 1 ? '20:00:00' : null,
            ]);
        }

        $store4->menus()->createMany([
            [
                'name' => 'カット',
                'description' => 'シャンプー・ブロー込み',
                'price' => 5500,
                'category' => '施術メニュー',
                'is_available' => true,
            ],
            [
                'name' => 'カラー',
                'description' => 'シャンプー・ブロー込み',
                'price' => 8800,
                'category' => '施術メニュー',
                'is_available' => true,
            ],
            [
                'name' => 'カット＆カラー',
                'description' => 'シャンプー・ブロー込み',
                'price' => 13200,
                'category' => '施術メニュー',
                'is_available' => true,
            ],
            [
                'name' => 'トリートメント',
                'description' => 'プレミアムトリートメント',
                'price' => 3300,
                'category' => 'オプション',
                'is_available' => true,
            ],
        ]);

        $store4->coupons()->create([
            'title' => '新規ご来店20%OFF',
            'description' => '全メニュー20%割引',
            'discount_type' => 'percentage',
            'discount_value' => 20,
            'code' => 'FIRST20',
            'start_date' => now(),
            'end_date' => now()->addMonths(6),
            'is_active' => true,
        ]);

        // 店舗オーナー5: 焼肉店
        $owner5 = User::create([
            'username' => 'yakiniku_owner',
            'name' => '伊藤店主',
            'email' => 'yakiniku@example.com',
            'password' => Hash::make('password'),
            'role' => 'store_owner',
            'user_level' => 'premium1',
            'status' => 'active',
            'email_verified_at' => now(),
            'approved_at' => now(),
        ]);

        $store5 = Store::create([
            'user_id' => $owner5->id,
            'store_name' => '焼肉 炎',
            'industry' => $categoryYakiniku ? $categoryYakiniku->name : '焼肉',
            'category_id' => $categoryYakiniku?->id,
            'area_id' => $areaShibuya?->parent_id,
            'phone' => '03-5555-5555',
            'email' => 'yakiniku@example.com',
            'contact_name' => '伊藤店主',
            'address' => '東京都渋谷区道玄坂8-8-8',
            'prefecture' => '東京都',
            'city' => '渋谷区',
            'street_address' => '道玄坂8-8-8',
            'postal_code' => '150-0043',
            'average_rating' => 4.6,
            'review_count' => 28,
            'status' => 'active',
        ]);

        // ユーザーにstore_idを設定
        $owner5->update(['store_id' => $store5->id]);

        $store5->storeDetail()->create([
            'description' => 'A5ランクの黒毛和牛を中心に、厳選したお肉をリーズナブルな価格で提供。個室も完備しているので、接待や記念日にもおすすめです。',
            'access_info' => '渋谷駅から徒歩7分',
            'parking_info' => '提携駐車場あり（2時間無料）',
            'payment_methods' => '現金、クレジットカード、電子マネー',
            'seats' => 60,
            'wifi' => true,
            'credit_card' => true,
        ]);

        for ($i = 0; $i <= 6; $i++) {
            $store5->businessHours()->create([
                'day_of_week' => $i,
                'is_closed' => false,
                'open_time' => '17:00:00',
                'close_time' => '24:00:00',
            ]);
        }

        $store5->menus()->createMany([
            [
                'name' => '特選カルビ',
                'description' => 'A5ランク黒毛和牛',
                'price' => 2800,
                'category' => '焼肉',
                'is_available' => true,
            ],
            [
                'name' => '特選ロース',
                'description' => 'A5ランク黒毛和牛',
                'price' => 3200,
                'category' => '焼肉',
                'is_available' => true,
            ],
            [
                'name' => '上タン',
                'description' => '厚切り牛タン',
                'price' => 2500,
                'category' => '焼肉',
                'is_available' => true,
            ],
            [
                'name' => '食べ放題コース',
                'description' => '90分食べ放題・飲み放題',
                'price' => 5500,
                'category' => 'コース',
                'is_available' => true,
            ],
        ]);

        $this->command->info('店舗オーナーと店舗を作成しました：');
        $this->command->info("1. {$store1->store_name} (ID: {$store1->id}) - オーナー: {$owner1->email}");
        $this->command->info("2. {$store2->store_name} (ID: {$store2->id}) - オーナー: {$owner2->email}");
        $this->command->info("3. {$store3->store_name} (ID: {$store3->id}) - オーナー: {$owner3->email}");
        $this->command->info("4. {$store4->store_name} (ID: {$store4->id}) - オーナー: {$owner4->email}");
        $this->command->info("5. {$store5->store_name} (ID: {$store5->id}) - オーナー: {$owner5->email}");
        $this->command->info('パスワード: password');
    }
}
