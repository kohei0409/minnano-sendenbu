<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'グルメ・飲食',
                'slug' => 'gourmet',
                'children' => [
                    ['name' => '和食', 'slug' => 'washoku'],
                    ['name' => 'イタリアン', 'slug' => 'italian'],
                    ['name' => 'フレンチ', 'slug' => 'french'],
                    ['name' => '中華', 'slug' => 'chinese'],
                    ['name' => '焼肉', 'slug' => 'yakiniku'],
                    ['name' => 'ラーメン', 'slug' => 'ramen'],
                    ['name' => 'カフェ', 'slug' => 'cafe'],
                    ['name' => '居酒屋', 'slug' => 'izakaya'],
                    ['name' => 'バー', 'slug' => 'bar'],
                    ['name' => 'ファストフード', 'slug' => 'fastfood'],
                ]
            ],
            [
                'name' => '美容・健康',
                'slug' => 'beauty-health',
                'children' => [
                    ['name' => '美容院', 'slug' => 'hair-salon'],
                    ['name' => 'ネイルサロン', 'slug' => 'nail-salon'],
                    ['name' => 'エステ', 'slug' => 'esthetic'],
                    ['name' => 'マッサージ', 'slug' => 'massage'],
                    ['name' => 'スパ', 'slug' => 'spa'],
                    ['name' => '整体', 'slug' => 'seitai'],
                    ['name' => '鍼灸', 'slug' => 'acupuncture'],
                    ['name' => 'フィットネス', 'slug' => 'fitness'],
                ]
            ],
            [
                'name' => '医療・介護',
                'slug' => 'medical-care',
                'children' => [
                    ['name' => '病院', 'slug' => 'hospital'],
                    ['name' => 'クリニック', 'slug' => 'clinic'],
                    ['name' => '歯科', 'slug' => 'dental'],
                    ['name' => '薬局', 'slug' => 'pharmacy'],
                    ['name' => '介護施設', 'slug' => 'nursing-home'],
                    ['name' => '訪問看護', 'slug' => 'home-nursing'],
                    ['name' => 'リハビリ', 'slug' => 'rehabilitation'],
                ]
            ],
            [
                'name' => 'ショッピング',
                'slug' => 'shopping',
                'children' => [
                    ['name' => 'ファッション', 'slug' => 'fashion'],
                    ['name' => '雑貨', 'slug' => 'goods'],
                    ['name' => '家電', 'slug' => 'electronics'],
                    ['name' => '書店', 'slug' => 'bookstore'],
                    ['name' => 'スーパー', 'slug' => 'supermarket'],
                    ['name' => 'コンビニ', 'slug' => 'convenience'],
                    ['name' => 'ドラッグストア', 'slug' => 'drugstore'],
                ]
            ],
            [
                'name' => 'レジャー・娯楽',
                'slug' => 'leisure',
                'children' => [
                    ['name' => 'カラオケ', 'slug' => 'karaoke'],
                    ['name' => 'ボウリング', 'slug' => 'bowling'],
                    ['name' => 'ゲームセンター', 'slug' => 'game-center'],
                    ['name' => '映画館', 'slug' => 'cinema'],
                    ['name' => 'テーマパーク', 'slug' => 'theme-park'],
                    ['name' => '温泉', 'slug' => 'onsen'],
                    ['name' => 'スポーツ施設', 'slug' => 'sports-facility'],
                ]
            ],
            [
                'name' => '教育・習い事',
                'slug' => 'education',
                'children' => [
                    ['name' => '学習塾', 'slug' => 'cram-school'],
                    ['name' => '英会話', 'slug' => 'english-school'],
                    ['name' => 'ピアノ教室', 'slug' => 'piano-school'],
                    ['name' => 'ダンススクール', 'slug' => 'dance-school'],
                    ['name' => 'プログラミング教室', 'slug' => 'programming-school'],
                    ['name' => '書道教室', 'slug' => 'calligraphy-school'],
                    ['name' => '料理教室', 'slug' => 'cooking-school'],
                ]
            ],
            [
                'name' => '暮らし・サービス',
                'slug' => 'life-service',
                'children' => [
                    ['name' => 'クリーニング', 'slug' => 'cleaning'],
                    ['name' => '不動産', 'slug' => 'real-estate'],
                    ['name' => '引越し', 'slug' => 'moving'],
                    ['name' => '修理・メンテナンス', 'slug' => 'repair'],
                    ['name' => 'ペットサロン', 'slug' => 'pet-salon'],
                    ['name' => '写真スタジオ', 'slug' => 'photo-studio'],
                    ['name' => '葬儀', 'slug' => 'funeral'],
                ]
            ],
            [
                'name' => 'ホテル・宿泊',
                'slug' => 'hotel-accommodation',
                'children' => [
                    ['name' => 'ホテル', 'slug' => 'hotel'],
                    ['name' => '旅館', 'slug' => 'ryokan'],
                    ['name' => 'ペンション', 'slug' => 'pension'],
                    ['name' => 'ゲストハウス', 'slug' => 'guesthouse'],
                    ['name' => 'カプセルホテル', 'slug' => 'capsule-hotel'],
                    ['name' => 'ビジネスホテル', 'slug' => 'business-hotel'],
                ]
            ],
        ];

        $order = 0;
        foreach ($categories as $categoryData) {
            $parent = Category::create([
                'name' => $categoryData['name'],
                'slug' => $categoryData['slug'],
                'display_order' => $order++,
            ]);

            $childOrder = 0;
            foreach ($categoryData['children'] as $childData) {
                Category::create([
                    'parent_id' => $parent->id,
                    'name' => $childData['name'],
                    'slug' => $childData['slug'],
                    'display_order' => $childOrder++,
                ]);
            }
        }
    }
}
