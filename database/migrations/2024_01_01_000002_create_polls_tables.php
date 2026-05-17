<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('polls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('slug')->unique();
            $table->string('primary_key_label')->default('ID / NIM / NIK');
            $table->string('primary_key_placeholder')->default('Masukkan ID Anda');
            $table->boolean('is_active')->default(true);
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->timestamps();
        });

        Schema::create('poll_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poll_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('poll_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poll_id')->constrained()->onDelete('cascade');
            $table->foreignId('poll_option_id')->constrained()->onDelete('cascade');
            $table->string('voter_key'); // primary key / NIM / NIK etc
            $table->string('voter_name')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();

            $table->unique(['poll_id', 'voter_key']); // prevent double vote
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poll_votes');
        Schema::dropIfExists('poll_options');
        Schema::dropIfExists('polls');
    }
};
