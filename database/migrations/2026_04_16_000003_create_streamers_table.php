<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('streamers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('slug')->unique();
            $table->text('bio')->nullable();
            $table->string('banner')->nullable();
            $table->string('stream_key')->unique();
            $table->boolean('is_live')->default(false);
            $table->timestamp('last_stream_started_at')->nullable();
            $table->timestamps();

            $table->index(['is_live', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('streamers');
    }
};
