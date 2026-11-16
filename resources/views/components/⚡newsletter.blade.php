<?php

use App\Models\Article;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

new class extends Component {

    #[\Livewire\Attributes\Validate('required|email:filter')]
    public string $email;

    public function submit(): void
    {
        $this->validate();

        \App\Jobs\AddContactToMailingList::dispatch($user->id);
    }
};
?>

<div class="p-4 pe-5">
    <h5>Discover the places, people and stories that make the city worth a closer look.</h5>
    <p class="text-dark">Subscribe to the POV newsletter.</p>

    <input type="email" wire:model="email" class="form-control text-dark pov-input border-dark @error('email') is-invalid @enderror" placeholder="Your email address...">
    @error('email')
        <small class="invalid-feedback">{{ $message }}</small>
    @enderror

    <div class="d-flex justify-content-between mt-3">
        <button type="submit" class="btn btn-dark" wire:loading.attr="disabled" wire:click="submit">
            <span wire:loading.remove>Subscribe!</span>
            <span wire:loading>
                <div class="spinner-border spinner-border-sm" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <span>Subscribing...</span>
            </span>
        </button>
        <button type="button" class="btn btn-link pe-0" data-bs-dismiss="modal">Maybe later</button>
    </div>
</div>

@push('after_scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            const modalEl = document.getElementById('newsletterModal');

            if (!modalEl) {
                return;
            }

            // getInstance() returns null until the modal has been initialised (Bootstrap does that
            // lazily on the first trigger click), so create the instance if it doesn't exist yet
            bootstrap.Modal.getOrCreateInstance(modalEl).show();
        });
    </script>
@endpush
