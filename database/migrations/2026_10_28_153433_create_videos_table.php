<?php

use App\Enums\VisibilityEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->json('data');
            $table->string('slug');
            $table->string('image')->nullable()->default(null);
            $table->integer('parent_id')->nullable()->default(null);
            $table->integer('lft')->default(0);
            $table->integer('rgt')->default(0);
            $table->integer('depth')->default(0);
            $table->json('extras')->nullable()->default(null);
            $table->enum('visibility', VisibilityEnum::options())->default(VisibilityEnum::PRIVATE->value);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
