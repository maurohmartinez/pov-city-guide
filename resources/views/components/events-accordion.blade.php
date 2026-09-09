@props(['eventGroups', 'layout' => 'list'])

<!-- Accordion with alternative button icon -->
<div class="accordion" id="accordionEvents" data-pov-component="events-accordion">

    @foreach($eventGroups as $groupName => $events)
        <!-- Item (expanded) -->
        <div class="accordion-item">
            <h3 class="accordion-header" id="heading-{{ Str::slug($groupName) }}">
                <button type="button" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#{{ Str::slug($groupName) }}" aria-expanded="true" aria-controls="{{ Str::slug($groupName) }}">
                    <span class="hover-effect-underline stretched-link me-2 fs-4">{{ $groupName }}</span>
                    @if(count($eventGroups) > 1)
                        <span class="badge text-body-emphasis border fs-sm">{{ count($events) }} {{ trans_choice('common.events_plural', $events->count()) }}</span>
                    @endif
                </button>
            </h3>
            <div class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" id="{{ Str::slug($groupName) }}" aria-labelledby="heading-{{ Str::slug($groupName) }}" data-bs-parent="#accordionEvents">
                <div class="accordion-body fs-base">
                    @if($layout === 'grid')
                        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4">
                            @foreach($events as $event)
                                <x-component::event-card :event="$event" />
                            @endforeach
                        </div>
                    @else
                        @foreach($events as $event)
                            <x-component::event-horizontal-card :event="$event" />
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>
