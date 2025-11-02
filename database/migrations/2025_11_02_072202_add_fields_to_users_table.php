 <?php

  use Illuminate\Database\Migrations\Migration;
  use Illuminate\Database\Schema\Blueprint;
  use Illuminate\Support\Facades\Schema;

  class AddFieldsToUsersTable extends Migration
  {
      public function up(): void
      {
          Schema::table('users', function (Blueprint $table) {
              $table->string('username')->unique()->after('id');
              $table->enum('role', ['admin', 'store_owner',
  'store_staff'])->default('store_staff')->after('email');
              $table->enum('user_level', ['free', 'premium1', 'premium2'])->default('free')->after('role');
              $table->enum('status', ['pending', 'active', 'suspended'])->default('active')->after('user_level');
              $table->foreignId('store_id')->nullable()->constrained()->onDelete('cascade')->after('status');
              $table->timestamp('approved_at')->nullable()->after('email_verified_at');
              $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set
  null')->after('approved_at');
          });
      }

      public function down(): void
      {
          Schema::table('users', function (Blueprint $table) {
              $table->dropForeign(['approved_by']);
              $table->dropForeign(['store_id']);
              $table->dropColumn([
                  'username',
                  'role',
                  'user_level',
                  'status',
                  'store_id',
                  'approved_at',
                  'approved_by',
              ]);
          });
      }
  }
