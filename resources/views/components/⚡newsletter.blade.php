<?php

use App\Models\Article;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cookie;
use Livewire\Component;

new class extends Component {

    /** Set once the user subscribed or clicked "Maybe later", so the popup isn't shown again */
    public const DISMISSED_COOKIE = 'newsletter_popup_dismissed';

    #[\Livewire\Attributes\Validate('required|email:filter')]
    public string $email;

    public bool $success = false;

    public function submit(): void
    {
        $user = auth()->check() ? auth()->user() : null;

        if ($user) {
            $this->email = $user->email;
        }

        $this->validate();

        try {
            $client = new \Brevo\Brevo(apiKey: config('app.brevo_api_key'), options: ['timeout' => 30]);
            $client->contacts->createContact(new \Brevo\Contacts\Requests\CreateContactRequest([
                'email' => $this->email,
                'attributes' => [
                    'FIRSTNAME' => $user?->name ?? null,
                ],
                'listIds' => [(int) config('app.brevo_mailing_list_id')],
                'updateEnabled' => true,
            ]));

            if ($user) {
                auth()->user()->update([
                    'extras' => [
                        ...auth()->user()->extras ?? [],
                        'added_to_promotion_emails' => true,
                    ],
                ]);
            }
        } catch (\Exception $e) {
            Log::error($e);

            return;
        }

        $this->success = true;

        Cookie::queue(Cookie::forever(self::DISMISSED_COOKIE, '1'));
    }

    /** "Maybe later": show the popup again after 10 days */
    public function dismiss(): void
    {
        Cookie::queue(self::DISMISSED_COOKIE, '1', 60 * 24 * 10);
    }
};
?>

<div class="px-4 pb-4 p-md-4 pe-md-5">
    @if(!$success)
        <h5>Discover the places, people and stories that make the city worth a closer look.</h5>
        <p class="text-dark">Subscribe to the POV newsletter.</p>
    @else
        <p>You are now subscribed!</p>
    @endif

    @if(!auth()->check() && !$success)
        <input
            type="email"
            wire:model="email"
            class="form-control text-dark pov-input border-dark @error('email') is-invalid @enderror"
            placeholder="Your email address..."
        >
        @error('email')
            <small class="invalid-feedback">{{ $message }}</small>
        @enderror
    @endif

    <div class="d-flex justify-content-between mt-3">
        @if($success)
            <button type="button" class="btn btn-dark" data-bs-dismiss="modal">
                Close
            </button>
        @else
            <button type="submit" class="btn btn-dark" wire:loading.attr="disabled" wire:click="submit">
                <span wire:loading.remove>Subscribe!</span>
                <span wire:loading>
                    <div class="spinner-border spinner-border-sm" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <span>Subscribing...</span>
                </span>
            </button>
            <button type="button" class="btn btn-link pe-0" data-bs-dismiss="modal" wire:click="dismiss">Maybe later</button>
        @endif
    </div>
</div>

@push('after_scripts')
    @if(!request()->hasCookie($this::DISMISSED_COOKIE) && (!auth()->check() || !auth()->user()->added_to_promotion_emails))
        <script>
            document.addEventListener('livewire:initialized', () => {
                const modalEl = document.getElementById('newsletterModal');

                if (!modalEl) {
                    return;
                }

                // getInstance() returns null until the modal has been initialised (Bootstrap does that
                // lazily on the first trigger click), so create the instance if it doesn't exist yet
                // setTimeout(() => bootstrap.Modal.getOrCreateInstance(modalEl).show(), 5000);
                bootstrap.Modal.getOrCreateInstance(modalEl).show()
            });
        </script>
    @endif
@endpush
