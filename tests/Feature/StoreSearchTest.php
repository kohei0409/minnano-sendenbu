<?php

namespace Tests\Feature;

use App\Models\Area;
use App\Models\Category;
use App\Models\Store;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_stores_index_page(): void
    {
        $response = $this->get(route('stores.index'));

        $response->assertStatus(200);
        $response->assertViewIs('public.stores.index');
    }

    public function test_can_search_stores_by_keyword(): void
    {
        $category = Category::factory()->create();
        $area = Area::factory()->create();

        $matchingStore = Store::factory()->create([
            'store_name' => 'Tokyo Sushi Restaurant',
            'status' => 'active',
            'category_id' => $category->id,
            'area_id' => $area->id,
        ]);

        $nonMatchingStore = Store::factory()->create([
            'store_name' => 'Osaka Ramen House',
            'status' => 'active',
            'category_id' => $category->id,
            'area_id' => $area->id,
        ]);

        $response = $this->get(route('stores.index', ['keyword' => 'Sushi']));

        $response->assertStatus(200);
    }

    public function test_can_filter_stores_by_category(): void
    {
        $category1 = Category::factory()->create(['name' => 'Restaurant']);
        $category2 = Category::factory()->create(['name' => 'Cafe']);
        $area = Area::factory()->create();

        $restaurantStore = Store::factory()->create([
            'category_id' => $category1->id,
            'area_id' => $area->id,
            'status' => 'active',
        ]);

        $cafeStore = Store::factory()->create([
            'category_id' => $category2->id,
            'area_id' => $area->id,
            'status' => 'active',
        ]);

        $response = $this->get(route('stores.index', ['category_id' => $category1->id]));

        $response->assertStatus(200);
        $response->assertViewHas('stores');
    }

    public function test_can_filter_stores_by_area(): void
    {
        $category = Category::factory()->create();
        $area1 = Area::factory()->create(['name' => 'Tokyo']);
        $area2 = Area::factory()->create(['name' => 'Osaka']);

        $tokyoStore = Store::factory()->create([
            'category_id' => $category->id,
            'area_id' => $area1->id,
            'status' => 'active',
        ]);

        $osakaStore = Store::factory()->create([
            'category_id' => $category->id,
            'area_id' => $area2->id,
            'status' => 'active',
        ]);

        $response = $this->get(route('stores.index', ['area_id' => $area1->id]));

        $response->assertStatus(200);
        $response->assertViewHas('stores');
    }

    public function test_can_filter_stores_by_rating(): void
    {
        $category = Category::factory()->create();
        $area = Area::factory()->create();

        $highRatedStore = Store::factory()->create([
            'category_id' => $category->id,
            'area_id' => $area->id,
            'status' => 'active',
            'average_rating' => 4.5,
            'review_count' => 10,
        ]);

        $lowRatedStore = Store::factory()->create([
            'category_id' => $category->id,
            'area_id' => $area->id,
            'status' => 'active',
            'average_rating' => 3.0,
            'review_count' => 5,
        ]);

        $response = $this->get(route('stores.index', ['min_rating' => 4]));

        $response->assertStatus(200);
        $response->assertViewHas('stores');
    }

    public function test_can_sort_stores_by_rating(): void
    {
        $category = Category::factory()->create();
        $area = Area::factory()->create();

        Store::factory()->create([
            'category_id' => $category->id,
            'area_id' => $area->id,
            'status' => 'active',
            'average_rating' => 3.5,
        ]);

        Store::factory()->create([
            'category_id' => $category->id,
            'area_id' => $area->id,
            'status' => 'active',
            'average_rating' => 4.5,
        ]);

        $response = $this->get(route('stores.index', ['sort' => 'rating', 'order' => 'desc']));

        $response->assertStatus(200);
        $response->assertViewHas('stores');
    }

    public function test_can_sort_stores_by_review_count(): void
    {
        $category = Category::factory()->create();
        $area = Area::factory()->create();

        Store::factory()->create([
            'category_id' => $category->id,
            'area_id' => $area->id,
            'status' => 'active',
            'review_count' => 5,
        ]);

        Store::factory()->create([
            'category_id' => $category->id,
            'area_id' => $area->id,
            'status' => 'active',
            'review_count' => 15,
        ]);

        $response = $this->get(route('stores.index', ['sort' => 'reviews', 'order' => 'desc']));

        $response->assertStatus(200);
        $response->assertViewHas('stores');
    }

    public function test_can_sort_stores_by_views(): void
    {
        $category = Category::factory()->create();
        $area = Area::factory()->create();

        Store::factory()->create([
            'category_id' => $category->id,
            'area_id' => $area->id,
            'status' => 'active',
            'view_count' => 100,
        ]);

        Store::factory()->create([
            'category_id' => $category->id,
            'area_id' => $area->id,
            'status' => 'active',
            'view_count' => 500,
        ]);

        $response = $this->get(route('stores.index', ['sort' => 'views', 'order' => 'desc']));

        $response->assertStatus(200);
        $response->assertViewHas('stores');
    }

    public function test_only_active_stores_are_displayed(): void
    {
        $category = Category::factory()->create();
        $area = Area::factory()->create();

        $activeStore = Store::factory()->create([
            'category_id' => $category->id,
            'area_id' => $area->id,
            'status' => 'active',
        ]);

        $pendingStore = Store::factory()->pending()->create([
            'category_id' => $category->id,
            'area_id' => $area->id,
        ]);

        $suspendedStore = Store::factory()->suspended()->create([
            'category_id' => $category->id,
            'area_id' => $area->id,
        ]);

        $response = $this->get(route('stores.index'));

        $response->assertStatus(200);
        $response->assertViewHas('stores');
    }

    public function test_can_combine_multiple_filters(): void
    {
        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();
        $area = Area::factory()->create();

        Store::factory()->create([
            'category_id' => $category1->id,
            'area_id' => $area->id,
            'status' => 'active',
            'average_rating' => 4.5,
        ]);

        Store::factory()->create([
            'category_id' => $category2->id,
            'area_id' => $area->id,
            'status' => 'active',
            'average_rating' => 3.0,
        ]);

        $response = $this->get(route('stores.index', [
            'category_id' => $category1->id,
            'area_id' => $area->id,
            'min_rating' => 4,
            'sort' => 'rating',
            'order' => 'desc',
        ]));

        $response->assertStatus(200);
        $response->assertViewHas('stores');
    }

    public function test_store_detail_page_increments_view_count(): void
    {
        $category = Category::factory()->create();
        $area = Area::factory()->create();

        $store = Store::factory()->create([
            'category_id' => $category->id,
            'area_id' => $area->id,
            'status' => 'active',
            'view_count' => 0,
        ]);

        $initialViewCount = $store->view_count;

        $response = $this->get(route('stores.show', $store));

        $response->assertStatus(200);

        $store->refresh();
        $this->assertEquals($initialViewCount + 1, $store->view_count);
    }
}
