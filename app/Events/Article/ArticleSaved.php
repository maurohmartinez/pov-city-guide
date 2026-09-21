<?php

namespace App\Events\Article;

use App\Models\Article;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ArticleSaved
{
    use Dispatchable, SerializesModels;

    public function __construct(public Article $event)
    {
    }
}
