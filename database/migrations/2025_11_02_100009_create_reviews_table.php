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
