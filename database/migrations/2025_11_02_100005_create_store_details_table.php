<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreDetailsTable extends Migration
{
    public function up(): void
    {
        Schema::create('store_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            $table->text('description')->nullable();
            $table->text('access_info')->nullable();
            $table->text('parking_info')->nullable();
            $table->string('payment_methods')->nullable();
            $table->string('website_url')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->integer('seats')->nullable();
            $table->boolean('private_rooms')->default(false);
            $table->enum('smoking', ['allowed', 'separated', 'prohibited'])->default('prohibited');
            $table->boolean('wifi')->default(false);
            $table->boolean('power_outlet')->default(false);
            $table->boolean('credit_card')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_details');
    }
}
