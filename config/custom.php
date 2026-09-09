<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Supported Locales
    |--------------------------------------------------------------------------
    |
    | This value is the list of supported locales in the application.
    | This list is used to show translation options to the admin.
    |
    */
    'supported_locales' => ['en', 'ro'],

    /*
    |--------------------------------------------------------------------------
    | Payment Gateway
    |--------------------------------------------------------------------------
    |
    | This value is used to show payment options to the admin.
    | At the moment we support one payment gateway.
    |
    */
    'payment_gateway' => env('PAYMENT_GATEWAY'),

    /*
    |--------------------------------------------------------------------------
    | Currency Configuration
    |--------------------------------------------------------------------------
    |
    | These values determine the currency used throughout the application
    | for displaying prices, processing payments, and formatting monetary
    | values. The currency_iso should be a valid ISO 4217 currency code,
    | and currency_locale should be a valid locale for number formatting.
    |
    */
    'country_code' => env('COUNTRY_CODE', 'RO'),
    'currency_iso' => env('CURRENCY_ISO', 'RON'),
    'currency_locale' => env('CURRENCY_LOCALE', 'ro-RO'),

    /*
    |--------------------------------------------------------------------------
    | Development login
    |--------------------------------------------------------------------------
    |
    | Opt-in for the password-less "login as any user" route used while
    | developing (/backoffice/tools/login). Only honoured when APP_ENV=local.
    |
    */

    'dev_login_enabled' => (bool) env('DEV_LOGIN_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | Feature Flags
    |--------------------------------------------------------------------------
    |
    | These flags can be used to enable or disable features in the application.
    | New feature flags should use the FEAT_ prefix in the .env file.
    |
    */

    /*
     * Which color modes the storefront offers. One of:
     *   light_and_dark => visitors pick, via the theme switcher
     *   light / dark    => locked to that mode, switcher hidden
     */
    'color_mode' => env('COLOR_MODE', 'light_and_dark'),
];
