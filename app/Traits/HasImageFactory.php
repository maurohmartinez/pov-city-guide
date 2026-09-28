<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Factories\Factory;

trait HasImageFactory
{
    public function prepareImageForTesting(): Factory
    {
        return $this->state(function () {
            return [
                'image' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAIAAAD/gAIDAAABDklEQVR4nOzSsQkCYRgEUZFrxg4EQzPRxMyyrNAOrMEG/mSiz4P3KliG3T7Xx+H/3O7v6QkLx+kBeyJWIFYgViBWIFYgViBWIFYgViBWIFYgViBWIFYgViBWIFYgViBWIFYgViBWIFYgViBWIFYgViBWIFYgViBWIFYgViBWIFYgViBWIFYgViBWIFYgViBWIFYgViBWIFYgVrBdXqfpDQvf83N6woJnBWIFYgViBWIFYgViBWIFYgViBWIFYgViBWIFYgViBWIFYgViBWIFYgViBWIFYgViBWIFYgViBWIFYgViBWIFYgViBWIFYgViBWIFYgViBWIFYgViBWIFYgViBWIFYgViBb8AAAD//5CXBZiKrggXAAAAAElFTkSuQmCC',
            ];
        });
    }
}
