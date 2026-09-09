<?php

return [
    'accepted'             => 'Câmpul :attribute trebuie să fie acceptat.',
    'accepted_if'          => 'Câmpul :attribute trebuie să fie acceptat când :other este :value.',
    'active_url'           => 'Câmpul :attribute nu este un URL valid.',
    'after'                => 'Câmpul :attribute trebuie să fie o dată după :date.',
    'after_or_equal'       => 'Câmpul :attribute trebuie să fie o dată egală sau după :date.',
    'alpha'                => 'Câmpul :attribute poate conține doar litere.',
    'alpha_dash'           => 'Câmpul :attribute poate conține doar litere, numere, liniuțe și subliniuțe.',
    'alpha_num'            => 'Câmpul :attribute poate conține doar litere și numere.',
    'array'                => 'Câmpul :attribute trebuie să fie un array.',
    'before'               => 'Câmpul :attribute trebuie să fie o dată înainte de :date.',
    'before_or_equal'      => 'Câmpul :attribute trebuie să fie o dată egală sau înainte de :date.',
    'between'              => [
        'numeric' => 'Câmpul :attribute trebuie să fie între :min și :max.',
        'file'    => 'Câmpul :attribute trebuie să fie între :min și :max kilobyți.',
        'string'  => 'Câmpul :attribute trebuie să fie între :min și :max caractere.',
        'array'   => 'Câmpul :attribute trebuie să aibă între :min și :max elemente.',
    ],
    'boolean'              => 'Câmpul :attribute trebuie să fie adevărat sau fals.',
    'confirmed'            => 'Confirmarea câmpului :attribute nu se potrivește.',
    'current_password'     => 'Parola este incorectă.',
    'date'                 => 'Câmpul :attribute nu este o dată validă.',
    'date_equals'          => 'Câmpul :attribute trebuie să fie o dată egală cu :date.',
    'date_format'          => 'Câmpul :attribute nu respectă formatul :format.',
    'different'            => 'Câmpul :attribute și :other trebuie să fie diferite.',
    'digits'               => 'Câmpul :attribute trebuie să aibă :digits cifre.',
    'digits_between'       => 'Câmpul :attribute trebuie să aibă între :min și :max cifre.',
    'dimensions'           => 'Câmpul :attribute are dimensiuni de imagine nevalide.',
    'distinct'             => 'Câmpul :attribute are o valoare duplicată.',
    'email'                => 'Câmpul :attribute trebuie să fie o adresă de e-mail validă.',
    'ends_with'            => 'Câmpul :attribute trebuie să se termine cu una dintre următoarele valori: :values.',
    'exists'               => 'Câmpul :attribute selectat nu este valid.',
    'file'                 => 'Câmpul :attribute trebuie să fie un fișier.',
    'filled'               => 'Câmpul :attribute trebuie completat.',
    'gt'                   => [
        'numeric' => 'Câmpul :attribute trebuie să fie mai mare decât :value.',
        'file'    => 'Câmpul :attribute trebuie să fie mai mare decât :value kilobyți.',
        'string'  => 'Câmpul :attribute trebuie să fie mai mare de :value caractere.',
        'array'   => 'Câmpul :attribute trebuie să aibă mai mult de :value elemente.',
    ],
    'gte'                  => [
        'numeric' => 'Câmpul :attribute trebuie să fie mai mare sau egal cu :value.',
        'file'    => 'Câmpul :attribute trebuie să fie mai mare sau egal cu :value kilobyți.',
        'string'  => 'Câmpul :attribute trebuie să fie mai mare sau egal cu :value caractere.',
        'array'   => 'Câmpul :attribute trebuie să aibă :value elemente sau mai multe.',
    ],
    'image'                => 'Câmpul :attribute trebuie să fie o imagine.',
    'in'                   => 'Câmpul :attribute selectat nu este valid.',
    'in_array'             => 'Câmpul :attribute nu există în :other.',
    'integer'              => 'Câmpul :attribute trebuie să fie un număr întreg.',
    'ip'                   => 'Câmpul :attribute trebuie să fie o adresă IP validă.',
    'ipv4'                 => 'Câmpul :attribute trebuie să fie o adresă IPv4 validă.',
    'ipv6'                 => 'Câmpul :attribute trebuie să fie o adresă IPv6 validă.',
    'json'                 => 'Câmpul :attribute trebuie să fie un șir JSON valid.',
    'lt'                   => [
        'numeric' => 'Câmpul :attribute trebuie să fie mai mic decât :value.',
        'file'    => 'Câmpul :attribute trebuie să fie mai mic decât :value kilobyți.',
        'string'  => 'Câmpul :attribute trebuie să aibă mai puțin de :value caractere.',
        'array'   => 'Câmpul :attribute trebuie să aibă mai puțin de :value elemente.',
    ],
    'lte'                  => [
        'numeric' => 'Câmpul :attribute trebuie să fie mai mic sau egal cu :value.',
        'file'    => 'Câmpul :attribute trebuie să fie mai mic sau egal cu :value kilobyți.',
        'string'  => 'Câmpul :attribute trebuie să aibă cel mult :value caractere.',
        'array'   => 'Câmpul :attribute nu trebuie să aibă mai mult de :value elemente.',
    ],
    'max'                  => [
        'numeric' => 'Câmpul :attribute nu poate fi mai mare de :max.',
        'file'    => 'Câmpul :attribute nu poate avea mai mult de :max kilobyți.',
        'string'  => 'Câmpul :attribute nu poate avea mai mult de :max caractere.',
        'array'   => 'Câmpul :attribute nu poate avea mai mult de :max elemente.',
    ],
    'mimes'                => 'Câmpul :attribute trebuie să fie un fișier de tipul: :values.',
    'mimetypes'            => 'Câmpul :attribute trebuie să fie un fișier de tipul: :values.',
    'min'                  => [
        'numeric' => 'Câmpul :attribute trebuie să fie cel puțin :min.',
        'file'    => 'Câmpul :attribute trebuie să aibă cel puțin :min kilobyți.',
        'string'  => 'Câmpul :attribute trebuie să aibă cel puțin :min caractere.',
        'array'   => 'Câmpul :attribute trebuie să aibă cel puțin :min elemente.',
    ],
    'multiple_of'          => 'Câmpul :attribute trebuie să fie un multiplu de :value.',
    'not_in'               => 'Câmpul :attribute selectat nu este valid.',
    'not_regex'            => 'Formatul câmpului :attribute nu este valid.',
    'numeric'              => 'Câmpul :attribute trebuie să fie un număr.',
    'password'             => 'Parola este incorectă.',
    'present'              => 'Câmpul :attribute trebuie să fie prezent.',
    'regex'                => 'Formatul câmpului :attribute nu este valid.',
    'required'             => 'Câmpul :attribute este obligatoriu.',
    'required_if'          => 'Câmpul :attribute este obligatoriu când :other este :value.',
    'required_unless'      => 'Câmpul :attribute este obligatoriu, cu excepția cazului în care :other este în :values.',
    'required_with'        => 'Câmpul :attribute este obligatoriu când există :values.',
    'required_with_all'    => 'Câmpul :attribute este obligatoriu când există :values.',
    'required_without'     => 'Câmpul :attribute este obligatoriu când :values nu există.',
    'required_without_all' => 'Câmpul :attribute este obligatoriu când niciuna dintre valorile :values nu există.',
    'prohibited'           => 'Câmpul :attribute este interzis.',
    'prohibited_if'        => 'Câmpul :attribute este interzis când :other este :value.',
    'prohibited_unless'    => 'Câmpul :attribute este interzis, cu excepția cazului în care :other este în :values.',
    'prohibits'            => 'Câmpul :attribute interzice prezența câmpului :other.',
    'same'                 => 'Câmpul :attribute și :other trebuie să fie identice.',
    'size'                 => [
        'numeric' => 'Câmpul :attribute trebuie să fie :size.',
        'file'    => 'Câmpul :attribute trebuie să aibă :size kilobyți.',
        'string'  => 'Câmpul :attribute trebuie să aibă :size caractere.',
        'array'   => 'Câmpul :attribute trebuie să conțină :size elemente.',
    ],
    'starts_with'          => 'Câmpul :attribute trebuie să înceapă cu una dintre următoarele valori: :values.',
    'string'               => 'Câmpul :attribute trebuie să fie un șir.',
    'timezone'             => 'Câmpul :attribute trebuie să fie un fus orar valid.',
    'unique'               => 'Câmpul :attribute a fost deja folosit.',
    'uploaded'             => 'Încărcarea câmpului :attribute a eșuat.',
    'url'                  => 'Câmpul :attribute trebuie să fie un URL valid.',
    'uuid'                 => 'Câmpul :attribute trebuie să fie un UUID valid.',

    /*
    |--------------------------------------------------------------------------
    | Linii de limbă personalizate
    |--------------------------------------------------------------------------
    |
    | Aici puteți specifica mesaje de validare personalizate pentru atribute
    | folosind convenția "attribute.rule" pentru a denumi liniile. Acest lucru
    | permite specificarea unui mesaj personalizat clar pentru o anumită regulă
    | de validare a unui anumit atribut.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'mesaj-personalizat',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Atribute personalizate
    |--------------------------------------------------------------------------
    |
    | Următoarele linii de limbă sunt folosite pentru a înlocui locurile
    | rezervate pentru atribute cu ceva mai prietenos pentru cititor, cum ar
    | fi "Adresă de e-mail" în loc de "email". Acest lucru ne ajută să facem
    | mesajele mai expresive.
    |
    */

    'attributes' => [],
];
