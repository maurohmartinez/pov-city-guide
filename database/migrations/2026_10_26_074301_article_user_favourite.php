<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('article_user_favourite', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id');
            $table->foreignId('user_id');

            $table->foreign('article_id')
                ->on('articles')
                ->references('id')
                ->cascadeOnDelete();

            $table->foreign('user_id')
                ->on('users')
                ->references('id')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('article_user_favourite');
    }
};
