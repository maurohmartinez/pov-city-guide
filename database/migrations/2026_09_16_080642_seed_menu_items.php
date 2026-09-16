<?php

use App\Models\MenuItem;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        MenuItem::create([
            'name' => 'News',
            'type' => 'internal_link',
            'link' => 'news',
            'lft' => 2,
            'rgt' => 3,
            'depth' => 1,
        ]);

        MenuItem::create([
            'name' => 'Things to do',
            'type' => 'internal_link',
            'link' => 'things-to-do',
            'lft' => 4,
            'rgt' => 5,
            'depth' => 1,
        ]);

        MenuItem::create([
            'name' => 'Festivals',
            'type' => 'internal_link',
            'link' => 'festivals',
            'lft' => 5,
            'rgt' => 7,
            'depth' => 1,
        ]);

        MenuItem::create([
            'name' => 'Food & Drinks',
            'type' => 'internal_link',
            'link' => 'food-and-drinks',
            'lft' => 8,
            'rgt' => 9,
            'depth' => 1,
        ]);

        MenuItem::create([
            'name' => 'Cinema',
            'type' => 'internal_link',
            'link' => 'cinema',
            'lft' => 10,
            'rgt' => 11,
            'depth' => 1,
        ]);

        MenuItem::create([
            'name' => 'Theatre',
            'type' => 'internal_link',
            'link' => 'theatre',
            'lft' => 12,
            'rgt' => 13,
            'depth' => 1,
        ]);
    }

    public function down(): void
    {
        //
    }
};
