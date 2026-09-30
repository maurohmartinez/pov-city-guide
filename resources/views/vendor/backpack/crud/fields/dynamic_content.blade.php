@include('crud::fields.inc.wrapper_start')
@isset($field['label'])<label>{!! $field['label'] !!}</label>@endisset
@include('crud::fields.inc.translatable_icon')

@if(isset($field['prefix']) || isset($field['suffix'])) <div class="input-group"> @endif
    @if(isset($field['prefix'])) <span class="input-group-text">{!! $field['prefix'] !!}</span> @endif
        <livewire:vendor.backpack.fields.dynamic-content
            :value="old_empty_or_null($field['name']) ??  $field['value'] ?? $field['default'] ?? null"
            :field-name="$field['name']" />
    @if(isset($field['suffix'])) <span class="input-group-text">{!! $field['suffix'] !!}</span> @endif
@if(isset($field['prefix']) || isset($field['suffix'])) </div> @endif

{{-- HINT --}}
@isset($field['hint'])
    <p class="help-block">{!! $field['hint'] !!}</p>
@endisset
@include('crud::fields.inc.wrapper_end')
