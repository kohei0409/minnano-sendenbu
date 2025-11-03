<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // 基本設定
            PermissionSeeder::class,
            AdminUserSeeder::class,

            // マスターデータ
            CategorySeeder::class,
            AreaSeeder::class,

            // ユーザーデータ
            CustomerSeeder::class,
            StoreUserSeeder::class,  // 店舗オーナー・スタッフと店舗を作成

            // 関連データ（店舗と顧客が必要）
            ReviewSeeder::class,
            ReservationSeeder::class,
            FavoriteSeeder::class,
        ]);

        $this->command->info('');
        $this->command->info('=================================================');
        $this->command->info('シーダーの実行が完了しました！');
        $this->command->info('=================================================');
        $this->command->info('');
        $this->command->info('管理者アカウント:');
        $this->command->info('  Email: admin@sendenbu.com');
        $this->command->info('  Password: password');
        $this->command->info('');
        $this->command->info('テスト顧客アカウント:');
        $this->command->info('  yamada@example.com / password');
        $this->command->info('  sato@example.com / password');
        $this->command->info('  suzuki@example.com / password');
        $this->command->info('  tanaka@example.com / password');
        $this->command->info('  takahashi@example.com / password');
        $this->command->info('');
        $this->command->info('店舗オーナーアカウント:');
        $this->command->info('  ramen@example.com / password (麺屋 一番)');
        $this->command->info('  italian@example.com / password (トラットリア ベッラ)');
        $this->command->info('  cafe@example.com / password (カフェ モーニングブルー)');
        $this->command->info('  salon@example.com / password (ヘアサロン グレイス)');
        $this->command->info('  yakiniku@example.com / password (焼肉 炎)');
        $this->command->info('');
        $this->command->info('作成されたデータ:');
        $this->command->info('  - 店舗: ' . \App\Models\Store::count() . '件');
        $this->command->info('  - 顧客: ' . \App\Models\Customer::count() . '件');
        $this->command->info('  - レビュー: ' . \App\Models\Review::count() . '件');
        $this->command->info('  - 予約: ' . \App\Models\Reservation::count() . '件');
        $this->command->info('  - お気に入り: ' . \DB::table('favorites')->count() . '件');
        $this->command->info('  - メニュー: ' . \App\Models\Menu::count() . '件');
        $this->command->info('  - クーポン: ' . \App\Models\Coupon::count() . '件');
        $this->command->info('=================================================');
        $this->command->info('');
    }
}
