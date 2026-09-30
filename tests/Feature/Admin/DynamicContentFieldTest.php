<?php

namespace Tests\Feature\Admin;

use App\Enums\VisibilityEnum;
use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class DynamicContentFieldTest extends TestCase
{
    use RefreshDatabase;

    private const COMPONENT = 'vendor.backpack.fields.dynamic-content';

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('articles');

        $user = User::factory()->create();
        $guard = config('backpack.base.guard') ?? config('auth.defaults.guard');
        $this->actingAs($user, $guard);
    }

    public function test_uploaded_images_are_stored_and_pathed_into_content(): void
    {
        $component = Livewire::test(self::COMPONENT)
            ->call('addRow')
            ->set('content.0.type', 'images')
            ->set('photos.0', [
                UploadedFile::fake()->image('one.jpg'),
                UploadedFile::fake()->image('two.png'),
            ]);

        $value = $component->get('content.0.value');

        $this->assertIsArray($value);
        $this->assertCount(2, $value);

        foreach ($value as $path) {
            // Every upload is compressed to a single JPEG, regardless of source format.
            $this->assertStringEndsWith('.jpg', $path);
            Storage::disk('articles')->assertExists($path);

            $binary = Storage::disk('articles')->get($path);
            $this->assertSame('image/jpeg', (new \finfo(FILEINFO_MIME_TYPE))->buffer($binary));
        }
    }

    public function test_non_image_uploads_are_rejected_and_not_stored(): void
    {
        $component = Livewire::test(self::COMPONENT)
            ->call('addRow')
            ->set('content.0.type', 'images')
            ->set('photos.0', [
                UploadedFile::fake()->create('evil.pdf', 100, 'application/pdf'),
            ])
            ->assertHasErrors('photos.0.*');

        $this->assertEmpty(Storage::disk('articles')->allFiles());
        $this->assertNull($component->get('content.0.value'));
    }

    public function test_remove_photo_deletes_file_and_updates_content(): void
    {
        $component = Livewire::test(self::COMPONENT)
            ->call('addRow')
            ->set('content.0.type', 'images')
            ->set('photos.0', [UploadedFile::fake()->image('one.jpg')]);

        $path = $component->get('content.0.value')[0];
        Storage::disk('articles')->assertExists($path);

        $component->call('removePhoto', 0, 0);

        Storage::disk('articles')->assertMissing($path);
        $this->assertSame([], $component->get('content.0.value'));
    }

    public function test_force_deleting_article_removes_its_content_images(): void
    {
        Queue::fake();

        $article = $this->articleWithContentImage($path);

        // Soft delete must keep the files (the article can still be restored).
        $article->delete();
        Storage::disk('articles')->assertExists($path);

        // Permanent deletion removes them.
        $article->forceDelete();
        Storage::disk('articles')->assertMissing($path);
    }

    public function test_removing_a_row_deletes_its_images_and_reindexes(): void
    {
        $component = Livewire::test(self::COMPONENT)
            ->call('addRow')                       // row 0 (text)
            ->call('addRow')                       // row 1
            ->set('content.1.type', 'images')
            ->set('photos.1', [UploadedFile::fake()->image('a.jpg')]);

        $path = $component->get('content.1.value')[0];
        Storage::disk('articles')->assertExists($path);

        $component->call('removeRow', 1);

        Storage::disk('articles')->assertMissing($path);
        $this->assertCount(1, $component->get('content'));
    }

    public function test_content_is_stored_as_clean_nested_json(): void
    {
        Queue::fake();

        $rows = [
            ['type' => 'text', 'size' => '12', 'value' => '<p>hi</p>'],
            ['type' => 'video', 'size' => '6', 'value' => ['provider' => 'youtube', 'url' => 'x']],
        ];

        $article = new Article([
            'title' => 'Test',
            'slug' => 'test-'.uniqid(),
            'description' => 'Test description',
            'visibility' => VisibilityEnum::PRIVATE,
        ]);
        $article->image = 'sample.jpg';
        // Simulate the form submit: content arrives as a JSON string.
        $article->content = json_encode($rows);
        $article->save();

        $raw = \DB::table('articles')->where('id', $article->id)->value('content');

        // Clean: the locale maps to a real JSON array, not an escaped string.
        $this->assertStringContainsString('"en":[{', str_replace(' ', '', $raw));
        $this->assertStringNotContainsString('\\"type\\"', $raw);
        $this->assertSame($rows, json_decode($raw, true)['en']);
    }

    private function articleWithContentImage(?string &$path): Article
    {
        $path = UploadedFile::fake()->image('kept.jpg')->store('/', 'articles');
        Storage::disk('articles')->assertExists($path);

        $article = new Article([
            'title' => 'Test',
            'slug' => 'test-'.uniqid(),
            'description' => 'Test description',
            'visibility' => VisibilityEnum::PRIVATE,
        ]);
        $article->image = 'sample.jpg';
        $article->setTranslation('content', app()->getLocale(), json_encode([
            ['type' => 'images', 'size' => '12', 'value' => [$path]],
            ['type' => 'text', 'size' => '12', 'value' => '<p>hi</p>'],
        ]));
        $article->save();

        return $article;
    }
}
