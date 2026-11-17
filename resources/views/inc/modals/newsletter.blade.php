<div class="modal fade" id="newsletterModal" tabindex="-1" aria-labelledby="newsletterModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content newsletter-bg border-0 overflow-hidden">
            <div class="modal-body p-0">
                <div class="row">
                    <div class="col-md-6 p-4 p-md-5 pe-md-4">
                        <img src="{{ asset('images/newsletter.jpg') }}" alt="Newsletter">
                    </div>
                    <div class="col-md-6 ps-0 d-flex flex-column justify-content-center">
                        <livewire:newsletter />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
