 <?php

  use Illuminate\Database\Migrations\Migration;
  use Illuminate\Database\Schema\Blueprint;
  use Illuminate\Support\Facades\Schema;

  class CreateStoresTable extends Migration
  {
      public function up(): void
      {
          Schema::create('stores', function (Blueprint $table) {
              $table->id();
              $table->string('store_name');
              $table->string('industry');
              $table->string('contact_name');
              $table->string('email')->unique();
              $table->string('phone');
              $table->text('address')->nullable();
              $table->text('description')->nullable();
              $table->enum('status', ['pending', 'active', 'suspended'])->default('pending');
              $table->timestamps();
              $table->softDeletes();
          });
      }

      public function down(): void
      {
          Schema::dropIfExists('stores');
      }
  }
