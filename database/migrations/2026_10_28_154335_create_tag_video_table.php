<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tag_video', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tag_id');
            $table->foreignId('video_id');

            $table->foreign('video_id')
                ->on('videos')
                ->references('id')
                ->cascadeOnDelete();

            $table->foreign('tag_id')
                ->on('tags')
                ->references('id')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tag_video');
    }
};
