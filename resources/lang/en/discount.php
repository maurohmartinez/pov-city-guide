<?php

return [
    // Entity names
    'discount' => 'discount',
    'discounts' => 'discounts',

    // Field labels
    'code' => 'Discount Code',
    'value' => 'Discount Value',
    'type' => 'Discount Type',
    'max_uses' => 'Maximum Uses',
    'max_tickets' => 'Maximum Tickets per Order',
    'used' => 'Times Used',
    'start_date' => 'Start Date',
    'end_date' => 'End Date',
    'eligible_productions' => 'Eligible Productions',
    'eligible_events' => 'Eligible Events',
    'eligible_cities' => 'Eligible Cities',
    'eligible_upcoming_events_title' => 'Upcoming Events This Discount Applies To',
    'no_eligible_upcoming_events' => 'No upcoming events match this discount\'s eligibility rules.',
    'eligible_events_preview_loading' => 'Loading eligible events...',
    'events_count_suffix' => 'events',
    'event_hash' => 'Hash',
    'event_title' => 'Event',
    'event_city' => 'City',
    'event_date' => 'Date',

    // Field hints
    'code_hint' => 'Examples: SAVE20, STUDENT15',
    'code_placeholder' => 'Unique discount code',
    'value_hint' => 'Examples: 20 (for 20% percentage discount), 120 (for 120 :currency fixed discount)',
    'max_uses_hint' => 'Examples: 5 (for 5 times only). Leave empty for unlimited uses.',
    'max_tickets_hint' => 'Examples: 4 (for maximum 4 tickets per order). Leave empty for no limit.',
    'start_date_hint' => 'Discount will be active starting from this date. Leave empty for no start date restriction.',
    'end_date_hint' => 'Discount will be valid until this date. Leave empty for no end date restriction.',
    'eligible_productions_hint' => 'Select which productions this discount applies to. Leave empty to apply to all productions.',
    'select_productions_placeholder' => 'Select productions...',
    'eligible_events_hint' => 'Select which events this discount applies to. Leave empty to apply to all events.',
    'select_events_placeholder' => 'Select events...',
    'eligible_cities_hint' => 'Select which cities this discount applies to (discount will apply to all events in these cities). Leave empty to apply to all cities.',
    'select_cities_placeholder' => 'Select cities...',

    // Type options
    'type_fixed' => 'Fixed Amount (:currency)',
    'type_percentage' => 'Percentage (%)',

    // Validation messages
    'validation' => [
        'code_regex' => 'Discount code must contain only uppercase letters and numbers',
        'code_regex_bulk' => 'Discount code must contain only uppercase letters, numbers, and asterisks (*)',
        'code_unique' => 'This discount code already exists',
        'type_invalid' => 'Discount type must be either fixed or percentage',
        'value_min' => 'Discount value must be at least 1',
        'max_uses_min' => 'Maximum uses must be at least 1',
        'max_tickets_min' => 'Maximum tickets per order must be at least 1',
        'percentage_max' => 'Percentage discount cannot exceed 100%',
        'percentage_min' => 'Percentage discount must be at least 1%',
        'end_date_after_start' => 'End date must be after start date',
        'pattern_required_for_bulk' => 'Pattern must contain asterisks (*) when creating multiple discounts',
        'pattern_too_many_wildcards' => 'Pattern cannot have more than 20 asterisks (*)',
        'pattern_all_wildcards' => 'Pattern cannot be all asterisks - include some fixed characters',
        'bulk_count_required' => 'Number of discounts is required',
        'bulk_count_integer' => 'Number of discounts must be a whole number',
        'bulk_count_min' => 'Must create at least 1 discount',
        'bulk_count_max' => 'Cannot create more than 1000 discounts at once',
    ],

    // Validation attributes
    'attributes' => [
        'code' => 'discount code',
        'type' => 'discount type',
        'value' => 'discount value',
        'max_uses' => 'maximum uses',
        'max_tickets' => 'maximum tickets per order',
        'used' => 'times used',
        'start_date' => 'start date',
        'end_date' => 'end date',
        'bulk_count' => 'number of discounts',
    ],

    // Bulk operation
    'create_bulk' => 'Add multiple discounts',
    'bulk_count' => 'Number of Discounts',
    'bulk_count_hint' => 'How many discounts to create (1-1000)',
    'code_pattern_hint' => 'Examples: SAVE***, ****LIDL, BLACKFRIDAY***. Use * as placeholder for random characters.',
    'bulk_success' => 'Successfully created :count discount(s)!',
    'bulk_success_single' => 'Successfully created 1 discount!',
    'bulk_duplicates_warning' => 'Skipped :skipped discount(s) due to duplicate codes. Try using more asterisks in your pattern.',
    'pattern_too_many_wildcards' => 'Pattern cannot have more than 20 asterisks (*)',

    // Existing messages
    'messages' => [
        'invalid_discount_code' => 'Discount code is invalid.',
        'discount_max_tickets_exceeded' => 'You have exceeded the maximum number of tickets allowed for this discount.',
        'discount_not_valid_for_order' => 'Discount is not valid for your order.',
        'empty_order' => 'You cannot apply discounts to an empty order. Please select your tickets first.'
    ]
];
