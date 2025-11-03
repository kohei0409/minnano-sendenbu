<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\Customer;
use App\Models\Reservation;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
        $stores = Store::all();
        $customers = Customer::all();

        if ($stores->isEmpty() || $customers->isEmpty()) {
            $this->command->error('店舗または顧客が見つかりません。先にStoreUserSeederとCustomerSeederを実行してください。');
            return;
        }

        $reservations = [
            // 麺屋 一番の予約（ラーメン店だけど予約可能な設定）
            [
                'store_index' => 0,
                'customer_index' => 0,
                'reservation_date' => now()->addDays(3),
                'reservation_time' => '18:00:00',
                'number_of_people' => 2,
                'customer_name' => '山田太郎',
                'customer_email' => 'yamada@example.com',
                'customer_phone' => '090-1234-5678',
                'notes' => 'カウンター席希望',
                'status' => 'confirmed',
            ],
            // トラットリア ベッラの予約
            [
                'store_index' => 1,
                'customer_index' => 1,
                'reservation_date' => now()->addDays(5),
                'reservation_time' => '19:00:00',
                'number_of_people' => 4,
                'customer_name' => '佐藤花子',
                'customer_email' => 'sato@example.com',
                'customer_phone' => '090-2345-6789',
                'notes' => '誕生日祝いで利用します。デザートプレートをお願いできますか？',
                'status' => 'confirmed',
            ],
            [
                'store_index' => 1,
                'customer_index' => 3,
                'reservation_date' => now()->addDays(7),
                'reservation_time' => '12:00:00',
                'number_of_people' => 2,
                'customer_name' => '田中美咲',
                'customer_email' => 'tanaka@example.com',
                'customer_phone' => '090-4567-8901',
                'notes' => 'ランチコース希望',
                'status' => 'pending',
            ],
            [
                'store_index' => 1,
                'customer_index' => null, // ゲスト予約
                'reservation_date' => now()->addDays(10),
                'reservation_time' => '20:00:00',
                'number_of_people' => 6,
                'customer_name' => 'ゲスト太郎',
                'customer_email' => 'guest@example.com',
                'customer_phone' => '090-9999-9999',
                'notes' => '個室希望、コース料理でお願いします',
                'status' => 'pending',
            ],
            // カフェ モーニングブルーの予約
            [
                'store_index' => 2,
                'customer_index' => 0,
                'reservation_date' => now()->addDays(2),
                'reservation_time' => '15:00:00',
                'number_of_people' => 3,
                'customer_name' => '山田太郎',
                'customer_email' => 'yamada@example.com',
                'customer_phone' => '090-1234-5678',
                'notes' => '打ち合わせで利用します',
                'status' => 'confirmed',
            ],
            // ヘアサロン グレイスの予約
            [
                'store_index' => 3,
                'customer_index' => 1,
                'reservation_date' => now()->addDays(4),
                'reservation_time' => '14:00:00',
                'number_of_people' => 1,
                'customer_name' => '佐藤花子',
                'customer_email' => 'sato@example.com',
                'customer_phone' => '090-2345-6789',
                'notes' => 'カット＆カラー希望',
                'status' => 'confirmed',
            ],
            [
                'store_index' => 3,
                'customer_index' => 3,
                'reservation_date' => now()->addDays(6),
                'reservation_time' => '11:00:00',
                'number_of_people' => 1,
                'customer_name' => '田中美咲',
                'customer_email' => 'tanaka@example.com',
                'customer_phone' => '090-4567-8901',
                'notes' => 'カットのみ',
                'status' => 'pending',
            ],
            [
                'store_index' => 3,
                'customer_index' => 4,
                'reservation_date' => now()->addDays(8),
                'reservation_time' => '16:00:00',
                'number_of_people' => 1,
                'customer_name' => '高橋健太',
                'customer_email' => 'takahashi@example.com',
                'customer_phone' => '090-5678-9012',
                'notes' => 'トリートメント追加希望',
                'status' => 'pending',
            ],
            // 焼肉 炎の予約
            [
                'store_index' => 4,
                'customer_index' => 0,
                'reservation_date' => now()->addDays(9),
                'reservation_time' => '18:30:00',
                'number_of_people' => 5,
                'customer_name' => '山田太郎',
                'customer_email' => 'yamada@example.com',
                'customer_phone' => '090-1234-5678',
                'notes' => '会社の飲み会で利用します。個室希望',
                'status' => 'confirmed',
            ],
            [
                'store_index' => 4,
                'customer_index' => 2,
                'reservation_date' => now()->addDays(12),
                'reservation_time' => '19:30:00',
                'number_of_people' => 2,
                'customer_name' => '鈴木一郎',
                'customer_email' => 'suzuki@example.com',
                'customer_phone' => '090-3456-7890',
                'notes' => '記念日で利用します',
                'status' => 'pending',
            ],
            // 過去の予約（完了済み）
            [
                'store_index' => 1,
                'customer_index' => 1,
                'reservation_date' => now()->subDays(3),
                'reservation_time' => '19:00:00',
                'number_of_people' => 2,
                'customer_name' => '佐藤花子',
                'customer_email' => 'sato@example.com',
                'customer_phone' => '090-2345-6789',
                'notes' => '',
                'status' => 'completed',
            ],
            [
                'store_index' => 3,
                'customer_index' => 3,
                'reservation_date' => now()->subDays(14),
                'reservation_time' => '13:00:00',
                'number_of_people' => 1,
                'customer_name' => '田中美咲',
                'customer_email' => 'tanaka@example.com',
                'customer_phone' => '090-4567-8901',
                'notes' => '',
                'status' => 'completed',
            ],
            // キャンセルされた予約
            [
                'store_index' => 4,
                'customer_index' => 4,
                'reservation_date' => now()->addDays(1),
                'reservation_time' => '20:00:00',
                'number_of_people' => 3,
                'customer_name' => '高橋健太',
                'customer_email' => 'takahashi@example.com',
                'customer_phone' => '090-5678-9012',
                'notes' => '',
                'status' => 'cancelled',
            ],
        ];

        $totalReservations = 0;
        foreach ($reservations as $reservationData) {
            if (!isset($stores[$reservationData['store_index']])) {
                continue;
            }

            $store = $stores[$reservationData['store_index']];
            $customer = null;

            if ($reservationData['customer_index'] !== null && isset($customers[$reservationData['customer_index']])) {
                $customer = $customers[$reservationData['customer_index']];
            }

            Reservation::create([
                'store_id' => $store->id,
                'customer_id' => $customer?->id,
                'reservation_date' => $reservationData['reservation_date'],
                'reservation_time' => $reservationData['reservation_time'],
                'number_of_people' => $reservationData['number_of_people'],
                'customer_name' => $reservationData['customer_name'],
                'customer_email' => $reservationData['customer_email'],
                'customer_phone' => $reservationData['customer_phone'],
                'notes' => $reservationData['notes'],
                'status' => $reservationData['status'],
            ]);

            $totalReservations++;
        }

        $this->command->info("予約を{$totalReservations}件作成しました。");
        $this->command->info('ステータス別:');
        $this->command->info('- pending: ' . Reservation::where('status', 'pending')->count() . '件');
        $this->command->info('- confirmed: ' . Reservation::where('status', 'confirmed')->count() . '件');
        $this->command->info('- completed: ' . Reservation::where('status', 'completed')->count() . '件');
        $this->command->info('- cancelled: ' . Reservation::where('status', 'cancelled')->count() . '件');
    }
}
