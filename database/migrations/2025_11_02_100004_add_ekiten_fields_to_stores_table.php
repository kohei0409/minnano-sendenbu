<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEkitenFieldsToStoresTable extends Migration
{
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->after('industry')->constrained()->onDelete('set null');
            $table->foreignId('area_id')->nullable()->after('category_id')->constrained()->onDelete('set null');
            $table->string('postal_code')->nullable()->after('phone');
            $table->string('prefecture')->nullable()->after('postal_code');
            $table->string('city')->nullable()->after('prefecture');
            $table->string('street_address')->nullable()->after('city');
            $table->string('building')->nullable()->after('street_address');
            $table->decimal('latitude', 10, 8)->nullable()->after('building');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->decimal('average_rating', 3, 2)->default(0)->after('longitude');
            $table->integer('review_count')->default(0)->after('average_rating');
            $table->integer('view_count')->default(0)->after('review_count');
            $table->integer('favorite_count')->default(0)->after('view_count');
            $table->boolean('is_featured')->default(false)->after('favorite_count');
            $table->timestamp('featured_until')->nullable()->after('is_featured');
            
            $table->index(['category_id', 'area_id', 'status']);
            $table->index(['average_rating']);
        });
    }

    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['category_id']);
            $table->dropForeign(['area_id']);
            $table->dropColumn([
                'user_id', 'category_id', 'area_id', 'postal_code', 'prefecture', 'city',
                'street_address', 'building', 'latitude', 'longitude',
                'average_rating', 'review_count', 'view_count',
                'favorite_count', 'is_featured', 'featured_until'
            ]);
        });
    }
}
