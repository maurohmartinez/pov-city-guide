<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('article_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id');
            $table->foreignId('tag_id');

            $table->foreign('article_id')
                ->on('articles')
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
        Schema::dropIfExists('article_tag');
    }
};
