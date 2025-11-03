<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\Customer;
use App\Models\Review;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $stores = Store::all();
        $customers = Customer::all();

        if ($stores->isEmpty() || $customers->isEmpty()) {
            $this->command->error('店舗または顧客が見つかりません。先にStoreUserSeederとCustomerSeederを実行してください。');
            return;
        }

        $reviewData = [
            // 麺屋 一番のレビュー
            [
                'store_index' => 0,
                'reviews' => [
                    [
                        'customer_index' => 0,
                        'rating' => 5,
                        'title' => '絶品の鶏白湯ラーメン！',
                        'comment' => 'クリーミーで濃厚な鶏白湯スープが最高です。細麺との相性も抜群で、チャーシューも柔らかくて美味しかったです。深夜まで営業しているのも嬉しいポイント。また絶対来ます！',
                        'visit_date' => now()->subDays(5),
                    ],
                    [
                        'customer_index' => 1,
                        'rating' => 4,
                        'title' => '美味しいけど待ち時間が...',
                        'comment' => 'ラーメンは本当に美味しいです。ただ人気店なので、ランチタイムは30分以上待つこともあります。時間に余裕を持って行くことをおすすめします。',
                        'visit_date' => now()->subDays(10),
                    ],
                    [
                        'customer_index' => 2,
                        'rating' => 5,
                        'title' => 'リピート確定！',
                        'comment' => '渋谷でラーメンといえばここ。スープが本当に美味しくて、毎週通っています。餃子もパリパリで最高です。店員さんも親切で居心地が良いです。',
                        'visit_date' => now()->subDays(15),
                    ],
                ],
            ],
            // トラットリア ベッラのレビュー
            [
                'store_index' => 1,
                'reviews' => [
                    [
                        'customer_index' => 1,
                        'rating' => 5,
                        'title' => '本格イタリアンの名店',
                        'comment' => '記念日ディナーで利用しました。パスタもピザも本当に美味しくて、特にペスカトーレの魚介の新鮮さに感動しました。ワインのラインナップも豊富で、店員さんのおすすめが当たりでした。雰囲気も良く、特別な日にぴったりのお店です。',
                        'visit_date' => now()->subDays(3),
                    ],
                    [
                        'customer_index' => 3,
                        'rating' => 4,
                        'title' => 'ランチがお得',
                        'comment' => 'ランチコースを利用しました。1200円でこのクオリティは素晴らしいです。前菜もパスタも美味しく、ドリンクも付いてこの価格。コスパ最高です。ディナーも行ってみたいです。',
                        'visit_date' => now()->subDays(7),
                    ],
                    [
                        'customer_index' => 4,
                        'rating' => 5,
                        'title' => '何度でも行きたい',
                        'comment' => 'シェフの腕が本当に素晴らしいです。どの料理も丁寧に作られていて、素材の良さが際立っています。接客も気持ち良く、また必ず来ます。',
                        'visit_date' => now()->subDays(20),
                    ],
                ],
            ],
            // カフェ モーニングブルーのレビュー
            [
                'store_index' => 2,
                'reviews' => [
                    [
                        'customer_index' => 0,
                        'rating' => 4,
                        'title' => 'リモートワークに最適',
                        'comment' => 'Wi-Fiと電源が完備されているので、リモートワークによく利用しています。コーヒーも美味しく、静かな環境で集中できます。ただ、土日は混雑するので平日がおすすめです。',
                        'visit_date' => now()->subDays(2),
                    ],
                    [
                        'customer_index' => 2,
                        'rating' => 5,
                        'title' => '隠れ家的カフェ',
                        'comment' => '新宿の喧騒から離れた静かな場所にあります。自家焙煎のコーヒーが本格的で美味しいです。チーズケーキも濃厚で最高。落ち着いた雰囲気で読書するのにぴったりです。',
                        'visit_date' => now()->subDays(12),
                    ],
                ],
            ],
            // ヘアサロン グレイスのレビュー
            [
                'store_index' => 3,
                'reviews' => [
                    [
                        'customer_index' => 1,
                        'rating' => 5,
                        'title' => '技術力が高い！',
                        'comment' => 'カットとカラーをお願いしました。要望を丁寧に聞いてくれて、イメージ通り以上の仕上がりに大満足です。スタイリストさんの技術力が本当に高いです。もう他のサロンには行けません。',
                        'visit_date' => now()->subDays(8),
                    ],
                    [
                        'customer_index' => 3,
                        'rating' => 5,
                        'title' => '接客も施術も最高',
                        'comment' => '初めて伺いましたが、カウンセリングがとても丁寧でした。髪質や普段のスタイリングについて細かく聞いてくれて、それに合わせて提案してくれます。仕上がりも想像以上で、次回の予約も入れました。',
                        'visit_date' => now()->subDays(14),
                    ],
                    [
                        'customer_index' => 4,
                        'rating' => 4,
                        'title' => '雰囲気の良いサロン',
                        'comment' => '清潔感のある店内で、スタッフの方も親切です。トリートメントの効果も良く、髪がサラサラになりました。価格は少し高めですが、それだけの価値はあると思います。',
                        'visit_date' => now()->subDays(25),
                    ],
                ],
            ],
            // 焼肉 炎のレビュー
            [
                'store_index' => 4,
                'reviews' => [
                    [
                        'customer_index' => 0,
                        'rating' => 5,
                        'title' => 'お肉の質が最高',
                        'comment' => 'A5ランクの和牛が本当に美味しい！特に特選カルビとタンが絶品でした。店員さんも焼き加減などアドバイスしてくれて、最高の状態で食べられました。また記念日に利用したいです。',
                        'visit_date' => now()->subDays(4),
                    ],
                    [
                        'customer_index' => 2,
                        'rating' => 4,
                        'title' => 'コスパ良し',
                        'comment' => 'このクオリティでこの価格は嬉しい。食べ放題コースもあって、がっつり食べたい時におすすめです。個室も使えるので、会社の飲み会にも良さそうです。',
                        'visit_date' => now()->subDays(11),
                    ],
                    [
                        'customer_index' => 4,
                        'rating' => 5,
                        'title' => '接待にも使える',
                        'comment' => '取引先との接待で利用しました。個室でゆっくり話ができて、お肉も最高品質。先方にも喜んでいただけました。店員さんのサービスも行き届いていて、安心して利用できるお店です。',
                        'visit_date' => now()->subDays(18),
                    ],
                ],
            ],
        ];

        $totalReviews = 0;
        foreach ($reviewData as $storeData) {
            if (!isset($stores[$storeData['store_index']])) {
                continue;
            }

            $store = $stores[$storeData['store_index']];

            foreach ($storeData['reviews'] as $reviewInfo) {
                if (!isset($customers[$reviewInfo['customer_index']])) {
                    continue;
                }

                $customer = $customers[$reviewInfo['customer_index']];

                Review::create([
                    'store_id' => $store->id,
                    'customer_id' => $customer->id,
                    'rating' => $reviewInfo['rating'],
                    'title' => $reviewInfo['title'],
                    'content' => $reviewInfo['comment'],
                    'visit_date' => $reviewInfo['visit_date'],
                    'status' => 'published',
                ]);

                $totalReviews++;
            }

            // 店舗の平均評価と件数を更新
            $avgRating = $store->reviews()->avg('rating');
            $reviewCount = $store->reviews()->count();
            $store->update([
                'average_rating' => round($avgRating, 1),
                'review_count' => $reviewCount,
            ]);
        }

        $this->command->info("レビューを{$totalReviews}件作成しました。");
    }
}
