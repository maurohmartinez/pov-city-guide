@props(['amount', 'omitZeroDecimals' => false])

<span class="price-display" data-pov-component="price" data-price="{{ $amount }}" data-currency="{{ config('custom.currency_iso') }}">{{ money_to_string($amount, omitZeroDecimals: $omitZeroDecimals) }} <span class="currency-iso">{{ config('custom.currency_iso') }}</span></span>
