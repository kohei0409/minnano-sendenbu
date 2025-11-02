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
