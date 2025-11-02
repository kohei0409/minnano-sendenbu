<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAreasTable extends Migration
{
    public function up(): void
    {
        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('areas')->onDelete('cascade');
            $table->string('name');
            $table->string('slug');
            $table->tinyInteger('level');
            $table->timestamps();
            
            $table->index(['parent_id', 'level']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('areas');
    }
}
