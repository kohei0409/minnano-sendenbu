<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            [
                'name' => '山田太郎',
                'email' => 'yamada@example.com',
                'password' => Hash::make('password'),
                'nickname' => 'やまちゃん',
                'birthday' => '1985-05-15',
                'gender' => 'male',
                'prefecture' => '東京都',
                'city' => '渋谷区',
                'phone' => '090-1234-5678',
                'email_verified_at' => now(),
                'status' => 'active',
            ],
            [
                'name' => '佐藤花子',
                'email' => 'sato@example.com',
                'password' => Hash::make('password'),
                'nickname' => 'はなちゃん',
                'birthday' => '1990-08-22',
                'gender' => 'female',
                'prefecture' => '東京都',
                'city' => '新宿区',
                'phone' => '090-2345-6789',
                'email_verified_at' => now(),
                'status' => 'active',
            ],
            [
                'name' => '鈴木一郎',
                'email' => 'suzuki@example.com',
                'password' => Hash::make('password'),
                'nickname' => 'すずちゃん',
                'birthday' => '1988-03-10',
                'gender' => 'male',
                'prefecture' => '神奈川県',
                'city' => '横浜市',
                'phone' => '090-3456-7890',
                'email_verified_at' => now(),
                'status' => 'active',
            ],
            [
                'name' => '田中美咲',
                'email' => 'tanaka@example.com',
                'password' => Hash::make('password'),
                'nickname' => 'みーちゃん',
                'birthday' => '1995-12-05',
                'gender' => 'female',
                'prefecture' => '大阪府',
                'city' => '大阪市',
                'phone' => '090-4567-8901',
                'email_verified_at' => now(),
                'status' => 'active',
            ],
            [
                'name' => '高橋健太',
                'email' => 'takahashi@example.com',
                'password' => Hash::make('password'),
                'nickname' => 'けんちゃん',
                'birthday' => '1992-07-18',
                'gender' => 'male',
                'prefecture' => '愛知県',
                'city' => '名古屋市',
                'phone' => '090-5678-9012',
                'email_verified_at' => now(),
                'status' => 'active',
            ],
        ];

        foreach ($customers as $customerData) {
            Customer::create($customerData);
        }

        $this->command->info('テスト顧客アカウントを作成しました（パスワード: password）');
        $this->command->info('- yamada@example.com');
        $this->command->info('- sato@example.com');
        $this->command->info('- suzuki@example.com');
        $this->command->info('- tanaka@example.com');
        $this->command->info('- takahashi@example.com');
    }
}
