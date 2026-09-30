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

    public function test_moving_rows_reorders_content_and_ignores_out_of_bounds(): void
    {
        $component = Livewire::test(self::COMPONENT)
            ->call('addRow')
            ->call('addRow')
            ->set('content.0.value', 'first')
            ->set('content.1.value', 'second');

        // Move the second row up -> it becomes first.
        $component->call('moveRow', 1, -1);
        $this->assertSame(['second', 'first'], array_column($component->get('content'), 'value'));

        // Move it back down.
        $component->call('moveRow', 0, 1);
        $this->assertSame(['first', 'second'], array_column($component->get('content'), 'value'));

        // Out-of-bounds moves are no-ops.
        $component->call('moveRow', 0, -1);
        $component->call('moveRow', 1, 1);
        $this->assertSame(['first', 'second'], array_column($component->get('content'), 'value'));
    }

    public function test_changing_type_clears_value_and_deletes_old_images(): void
    {
        $component = Livewire::test(self::COMPONENT)
            ->call('addRow')
            ->set('content.0.type', 'images')
            ->set('photos.0', [UploadedFile::fake()->image('a.jpg')]);

        $path = $component->get('content.0.value')[0];
        Storage::disk('articles')->assertExists($path);

        // Switching away from images clears the value and removes the uploaded file.
        $key = $component->get('content.0.key');
        $component->call('changeType', $key, 'text');

        $this->assertSame('text', $component->get('content.0.type'));
        $this->assertNull($component->get('content.0.value'));
        Storage::disk('articles')->assertMissing($path);
    }

    public function test_changing_size_targets_the_right_row_by_key_after_reorder(): void
    {
        $component = Livewire::test(self::COMPONENT)
            ->call('addRow')
            ->call('addRow');

        $key1 = $component->get('content.1.key');

        // Reorder so indices shift, then change the size of the row identified by key.
        $component->call('moveRow', 1, -1);   // that row is now at index 0
        $component->call('changeSize', $key1, '6');

        $row = collect($component->get('content'))->firstWhere('key', $key1);
        $this->assertSame('6', $row['size']);

        // An unknown size is ignored.
        $component->call('changeSize', $key1, '7');
        $this->assertSame('6', collect($component->get('content'))->firstWhere('key', $key1)['size']);
    }

    public function test_size_dropdown_reflects_the_stored_value(): void
    {
        $component = Livewire::test(self::COMPONENT)->call('addRow');
        $key = $component->get('content.0.key');

        $component->call('changeSize', $key, '6');

        // The stored size (6 => "2/4") must be the selected option, not the default 4/4.
        $component->assertSeeHtml('<option value="6" selected>2/4</option>');
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
