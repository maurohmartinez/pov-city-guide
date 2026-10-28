<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('category_video', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id');
            $table->foreignId('video_id');

            $table->foreign('video_id')
                ->on('videos')
                ->references('id')
                ->cascadeOnDelete();

            $table->foreign('category_id')
                ->on('categories')
                ->references('id')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_video');
    }
};
