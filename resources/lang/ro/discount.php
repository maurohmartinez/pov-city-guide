<?php

return [
    // Entity names
    'discount' => 'reducere',
    'discounts' => 'reduceri',

    // Field labels
    'code' => 'Cod Reducere',
    'value' => 'Valoare Reducere',
    'type' => 'Tip Reducere',
    'max_uses' => 'Utilizări Maxime',
    'max_tickets' => 'Bilete Maxime pe Comandă',
    'used' => 'Utilizări',
    'start_date' => 'Data de început',
    'end_date' => 'Data de sfârșit',
    'eligible_productions' => 'Producții Eligibile',
    'eligible_events' => 'Evenimente Eligibile',
    'eligible_cities' => 'Orașe Eligibile',
    'eligible_upcoming_events_title' => 'Evenimente Viitoare la Care Se Aplică Această Reducere',
    'no_eligible_upcoming_events' => 'Niciun eveniment viitor nu corespunde regulilor de eligibilitate ale acestei reduceri.',
    'eligible_events_preview_loading' => 'Se încarcă evenimentele eligibile...',
    'events_count_suffix' => 'evenimente',
    'event_hash' => 'Hash',
    'event_title' => 'Eveniment',
    'event_city' => 'Oraș',
    'event_date' => 'Dată',

    // Field hints
    'code_hint' => 'Exemple: SAVE20, STUDENT15',
    'code_placeholder' => 'Cod unic de reducere',
    'value_hint' => 'Exemple: 20 (pentru 20% reducere procentuală), 120 (pentru 120 :currency reducere fixă)',
    'max_uses_hint' => 'Exemple: 5 (pentru doar 5 utilizări). Lasă gol pentru utilizări nelimitate.',
    'max_tickets_hint' => 'Numărul maxim de bilete care pot fi cumpărate într-o singură comandă folosind această reducere. Lasă gol pentru nelimitat.',
    'start_date_hint' => 'Reducerea va fi activă începând cu această dată. Lasă gol pentru fără restricții de dată de început.',
    'end_date_hint' => 'Reducerea va fi valabilă până la această dată. Lasă gol pentru fără restricții de dată de sfârșit.',
    'eligible_productions_hint' => 'Selectează la ce producții se aplică această reducere. Lasă gol pentru a se aplica la toate producțiile.',
    'select_productions_placeholder' => 'Selectează producții...',
    'eligible_events_hint' => 'Selectează la ce evenimente se aplică această reducere. Lasă gol pentru a se aplica la toate evenimentele.',
    'select_events_placeholder' => 'Selectează evenimente...',
    'eligible_cities_hint' => 'Selectează în ce orașe se aplică această reducere (reducerea se va aplica la toate evenimentele din aceste orașe). Lasă gol pentru a se aplica la toate orașele.',
    'select_cities_placeholder' => 'Selectează orașe...',

    // Type options
    'type_fixed' => 'Sumă Fixă (:currency)',
    'type_percentage' => 'Procentaj (%)',

    // Validation messages
    'validation' => [
        'code_regex' => 'Codul de reducere trebuie să conțină doar litere mari și cifre',
        'code_regex_bulk' => 'Codul de reducere trebuie să conțină doar litere mari, cifre și asteriscuri (*)',
        'code_unique' => 'Acest cod de reducere există deja',
        'type_invalid' => 'Tipul reducerii trebuie să fie fix sau procentual',
        'value_min' => 'Valoarea reducerii trebuie să fie cel puțin 1',
        'max_uses_min' => 'Utilizările maxime trebuie să fie cel puțin 1',
        'max_tickets_min' => 'Biletele maxime pe comandă trebuie să fie cel puțin 1',
        'percentage_max' => 'Reducerea procentuală nu poate depăși 100%',
        'percentage_min' => 'Reducerea procentuală trebuie să fie cel puțin 1%',
        'end_date_after_start' => 'Data de sfârșit trebuie să fie după data de început',
        'pattern_required_for_bulk' => 'Șablonul trebuie să conțină asteriscuri (*) când creezi mai multe reduceri',
        'pattern_too_many_wildcards' => 'Șablonul nu poate avea mai mult de 20 de asteriscuri (*)',
        'pattern_all_wildcards' => 'Șablonul nu poate fi doar asteriscuri - include și caractere fixe',
        'bulk_count_required' => 'Numărul de reduceri este obligatoriu',
        'bulk_count_integer' => 'Numărul de reduceri trebuie să fie un număr întreg',
        'bulk_count_min' => 'Trebuie să creezi cel puțin 1 reducere',
        'bulk_count_max' => 'Nu poți crea mai mult de 1000 de reduceri odată',
    ],

    // Validation attributes
    'attributes' => [
        'code' => 'cod reducere',
        'type' => 'tip reducere',
        'value' => 'valoare reducere',
        'max_uses' => 'utilizări maxime',
        'max_tickets' => 'bilete maxime pe comandă',
        'used' => 'utilizări',
        'start_date' => 'data de început',
        'end_date' => 'data de sfârșit',
        'bulk_count' => 'numărul de reduceri',
    ],

    // Bulk operation
    'create_bulk' => 'Creare Reduceri în Masă',
    'bulk_count' => 'Numărul de Reduceri',
    'bulk_count_hint' => 'Câte reduceri să creez (1-1000)',
    'code_pattern_hint' => 'Exemple: SAVE***, ****LIDL, BLACKFRIDAY***. Folosește * ca marcator pentru caractere aleatorii.',
    'bulk_success' => 'Am creat cu succes :count reduceri!',
    'bulk_success_single' => 'Am creat cu succes 1 reducere!',
    'bulk_duplicates_warning' => 'Am sărit peste :skipped reduceri din cauza codurilor duplicate. Încearcă să folosești mai multe asteriscuri în șablon.',
    'pattern_too_many_wildcards' => 'Șablonul nu poate avea mai mult de 20 de asteriscuri (*)',

    // Existing messages
    'messages' => [
        'invalid_discount_code' => 'Codul de reducere este invalid.',
        'discount_max_tickets_exceeded' => 'Ai depășit numărul maxim de bilete permise pentru această reducere.',
        'discount_not_valid_for_order' => 'Reducerea nu este valabilă pentru comanda ta.',
        'empty_order' => 'Nu poți aplica reduceri la o comandă goală. Te rugăm să selectezi mai întâi biletele.',
    ]
];
