 <?php

  use Illuminate\Database\Migrations\Migration;
  use Illuminate\Database\Schema\Blueprint;
  use Illuminate\Support\Facades\Schema;

  class CreateStoreApplicationsTable extends Migration
  {
      public function up(): void
      {
          Schema::create('store_applications', function (Blueprint $table) {
              $table->id();
              $table->string('store_name');
              $table->string('industry');
              $table->string('contact_name');
              $table->string('email');
              $table->string('phone');
              $table->text('message')->nullable();
              $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
              $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
              $table->timestamp('reviewed_at')->nullable();
              $table->text('rejection_reason')->nullable();
              $table->timestamps();
          });
      }

      public function down(): void
      {
          Schema::dropIfExists('store_applications');
      }
  }
