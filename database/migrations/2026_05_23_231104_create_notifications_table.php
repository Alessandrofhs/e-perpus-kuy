<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // penerima notif
            $table->string('title');
            $table->string('message');
            $table->string('type');       // 'loan_approved', 'loan_rejected', 'loan_created', 'returned', 'fine'
            $table->foreignId('loan_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamp('read_at')->nullable(); // null = belum dibaca
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
