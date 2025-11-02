#!/bin/bash

# 6. store_images
cat > 2025_11_02_100006_create_store_images_table.php << 'EOF'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreImagesTable extends Migration
{
    public function up(): void
    {
        Schema::create('store_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            $table->string('image_path');
            $table->enum('image_type', ['main', 'exterior', 'interior', 'menu', 'other'])->default('other');
            $table->integer('display_order')->default(0);
            $table->string('caption')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('store_images'); }
}
EOF

# 7. business_hours
cat > 2025_11_02_100007_create_business_hours_table.php << 'EOF'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessHoursTable extends Migration
{
    public function up(): void
    {
        Schema::create('business_hours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('day_of_week');
            $table->boolean('is_closed')->default(false);
            $table->time('open_time')->nullable();
            $table->time('close_time')->nullable();
            $table->time('break_start_time')->nullable();
            $table->time('break_end_time')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('business_hours'); }
}
EOF

# 8. menus
cat > 2025_11_02_100008_create_menus_table.php << 'EOF'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            $table->string('category')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('image_path')->nullable();
            $table->boolean('is_available')->default(true);
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('menus'); }
}
EOF

# 9. reviews
cat > 2025_11_02_100009_create_reviews_table.php << 'EOF'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('rating');
            $table->string('title')->nullable();
            $table->text('content');
            $table->date('visit_date')->nullable();
            $table->integer('helpful_count')->default(0);
            $table->enum('status', ['pending', 'published', 'rejected'])->default('pending');
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
            $table->index(['store_id', 'status']);
            $table->index(['customer_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('reviews'); }
}
EOF

# 10. review_images
cat > 2025_11_02_100010_create_review_images_table.php << 'EOF'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewImagesTable extends Migration
{
    public function up(): void
    {
        Schema::create('review_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_id')->constrained()->onDelete('cascade');
            $table->string('image_path');
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('review_images'); }
}
EOF

# 11. review_replies
cat > 2025_11_02_100011_create_review_replies_table.php << 'EOF'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewRepliesTable extends Migration
{
    public function up(): void
    {
        Schema::create('review_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('content');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('review_replies'); }
}
EOF

# 12. favorites
cat > 2025_11_02_100012_create_favorites_table.php << 'EOF'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFavoritesTable extends Migration
{
    public function up(): void
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->unique(['customer_id', 'store_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('favorites'); }
}
EOF

# 13. coupons
cat > 2025_11_02_100013_create_coupons_table.php << 'EOF'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('discount_type', ['percentage', 'amount', 'free_item'])->default('percentage');
            $table->decimal('discount_value', 10, 2);
            $table->string('code')->unique();
            $table->integer('usage_limit')->nullable();
            $table->integer('used_count')->default(0);
            $table->date('start_date');
            $table->date('end_date');
            $table->text('conditions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('coupons'); }
}
EOF

# 14. coupon_usage
cat > 2025_11_02_100014_create_coupon_usage_table.php << 'EOF'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponUsageTable extends Migration
{
    public function up(): void
    {
        Schema::create('coupon_usage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            $table->timestamp('used_at');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('coupon_usage'); }
}
EOF

# 15. reservations
cat > 2025_11_02_100015_create_reservations_table.php << 'EOF'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('set null');
            $table->date('reservation_date');
            $table->time('reservation_time');
            $table->integer('number_of_people');
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email');
            $table->text('message')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
            $table->index(['store_id', 'reservation_date', 'status']);
        });
    }
    public function down(): void { Schema::dropIfExists('reservations'); }
}
EOF

# 16. tags
cat > 2025_11_02_100016_create_tags_table.php << 'EOF'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagsTable extends Migration
{
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('tags'); }
}
EOF

# 17. store_tags
cat > 2025_11_02_100017_create_store_tags_table.php << 'EOF'
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreTagsTable extends Migration
{
    public function up(): void
    {
        Schema::create('store_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            $table->foreignId('tag_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->unique(['store_id', 'tag_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('store_tags'); }
}
EOF

echo "All migration files created successfully!"
