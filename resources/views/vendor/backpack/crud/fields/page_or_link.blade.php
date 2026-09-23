{{-- PAGE OR LINK field --}}
{{-- Used in Backpack\MenuCRUD --}}

<?php
    $field['allows_null'] = $field['allows_null'] ?? false;

    [$type, $link] = explode(',', $field['name']);

    $field['configurationNames'] = [];
    $field['configurationNames']['type'] = $type ?? 'type';
    $field['configurationNames']['link'] = $link ?? 'link';
    $field['options']['internal_link'] = $field['options']['internal_link'] ?? trans('backpack::crud.internal_link');
    $field['options']['external_link'] = $field['options']['external_link'] ?? trans('backpack::crud.external_link');
?>

@include('crud::fields.inc.wrapper_start')
    <label>{!! $field['label'] !!}</label>
    @include('crud::fields.inc.translatable_icon')

    <div class="row" data-init-function="bpFieldInitLinkElement">
        {{-- hidden placeholders for content --}}
        <input type="hidden" value="{{ $entry->{$field['configurationNames']['link']} ?? '' }}" name="{{ $field['configurationNames']['link'] }}" />

        <div class="col-sm-3">
            {{-- type select --}}
            <select
                data-identifier="link_select"
                name="{!! $field['configurationNames']['type'] !!}"
                @include('crud::fields.inc.attributes')
                >
                @if ($field['allows_null'])
                    <option value="">-</option>
                @endif
                @foreach ($field['options'] as $key => $value)
                    <option value="{{ $key }}"
                        @if (isset($entry) && $key === $entry->{$field['configurationNames']['type']})
                            selected
                        @endif
                    >{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-9">
            {{-- internal link input --}}
            @php
                $shouldShowInternalLink = (isset($entry) && $entry->{$field['configurationNames']['type']} === 'internal_link') ||
                     (isset($entry) && !$entry->{$field['configurationNames']['type']} && !$field['allows_null']) ||
                     (!isset($entry) && !$field['allows_null']);
            @endphp
            <div class="link_value internal_link {{ $shouldShowInternalLink ? '' : 'd-none' }}">
                <input
                    type="text"
                    class="form-control"
                    placeholder="{{ trans('backpack::crud.internal_link_placeholder', ['url', url(config('backpack.base.route_prefix').'/page')]) }}"
                    for="{{ $field['configurationNames']['link'] }}"
                    {{ $shouldShowInternalLink ? 'required' : '' }}
                    @if(isset($entry))
                        @if ($entry->{$field['configurationNames']['type']} !== 'internal_link' && $entry->{$field['configurationNames']['type']} !== 'page_link')
                            disabled="disabled"
                        @endif

                        @if ($entry->{$field['configurationNames']['type']} === 'internal_link' && $entry->{$field['configurationNames']['link']})
                            value="{{ $entry->{$field['configurationNames']['link']} }}"
                        @endif
                    @else
                        disabled="disabled"
                    @endif
                    >
            </div>

            {{-- external link input --}}
            @php
                $shouldShowExternalLink = isset($entry) && $entry->{$field['configurationNames']['type']} === 'external_link';
            @endphp
            <div class="link_value external_link {{ $shouldShowExternalLink ? '' : 'd-none' }}">
                <input
                    type="url"
                    class="form-control"
                    placeholder="{{ trans('backpack::crud.page_link_placeholder') }}"
                    for="{{ $field['configurationNames']['link'] }}"
                    {{ $shouldShowExternalLink ? 'required' : '' }}
                    @if(isset($entry))
                        @if (!in_array($entry->{$field['configurationNames']['type']}, ['external_link']))
                            disabled="disabled"
                        @endif

                        @if ($entry->{$field['configurationNames']['type']} === 'external_link' && $entry->{$field['configurationNames']['link']})
                            value="{{ $entry->{$field['configurationNames']['link']} }}"
                        @endif
                    @else
                        disabled="disabled"
                    @endif
                    >
            </div>
        </div>
    </div>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif

@include('crud::fields.inc.wrapper_end')


{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
@if ($crud->fieldTypeNotLoaded($field))
    @php
        $crud->markFieldTypeAsLoaded($field);
    @endphp

    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('crud_fields_scripts')
    <script>
        function bpFieldInitLinkElement(element) {
            element = element[0]; // jQuery > Vanilla

            const select = element.querySelector('select[data-identifier=link_select]');
            const values = element.querySelectorAll('.link_value');

            // updates hidden fields
            const updateHidden = () => {
                let selectedInput = select.value && element.querySelector(`.${select.value}`).firstElementChild;
                element.querySelectorAll(`input[type="hidden"]`).forEach(hidden => {
                    hidden.value = selectedInput && hidden.getAttribute('name') === selectedInput.getAttribute('for') ? selectedInput.value : '';
                });
            }

            // save input changes to hidden placeholders
            values.forEach(value => value.firstElementChild.addEventListener('input', updateHidden));

            // main select change
            select.addEventListener('change', () => {
                values.forEach(value => {
                    console.log(value);
                    let isSelected = value.classList.contains(select.value);

                    // toggle visibility, disabled and required validation
                    value.classList.toggle('d-none', !isSelected);
                    value.firstElementChild.toggleAttribute('disabled', !isSelected);
                    value.firstElementChild.toggleAttribute('required', isSelected);
                });

                // updates hidden fields
                updateHidden();
            });
        }
    </script>
    @endpush

@endif
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
