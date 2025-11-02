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
