<?php

return [

    'created' => [
        'subject'                 => '[Acțiune necesară] Decont nou pentru :name – :hash',
        'title'                   => 'Decont nou pentru :name',
        'greeting'                => 'Bună ziua,',
        'intro'                   => 'A fost generat un nou decont care necesită semnătura dumneavoastră.',
        'label_payout'            => 'Număr decont',
        'label_total_sales'       => 'Total vânzări',
        'label_commission'        => 'Comision platformă',
        'label_net_amount'        => 'Sumă de plată',
        'attachments_intro'       => 'Atașat găsiți două documente:',
        'attachment_report'       => '**Raport de decont** – lista completă a comenzilor incluse',
        'attachment_confirmation' => '**Aviz de decont** – document rezumativ care necesită semnătura dumneavoastră',
        'cta_text'                => 'Vă rugăm să verificați documentele atașate și să semnați decontul accesând linkul de mai jos.',
        'button'                  => 'Semnează decontul',
        'sign_off'                => 'Mulțumim,',
    ],

    'completed' => [
        'subject'                 => 'Decont finalizat pentru :name – :hash',
        'title'                   => 'Decont finalizat pentru :name',
        'greeting'                => 'Bună ziua,',
        'intro'                   => 'Plata aferentă decontului :hash a fost finalizată cu succes.',
        'bank_timing'             => 'În funcție de bancă, suma va ajunge în contul dumneavoastră în 1–5 zile lucrătoare.',
        'label_net_amount'        => 'Sumă netă:',
        'label_processed_at'      => 'Data procesării:',
        'attachments_intro'       => 'Atașat găsiți:',
        'attachment_report'       => 'Raportul de decont semnat',
        'attachment_confirmation' => 'Avizul de decont semnat',
        'attachment_finance'      => 'Documentele de transfer bancar și factura',
        'invoice_note'            => 'Factura atașată **NU trebuie plătită**. Comisionul platformei a fost deja reținut din suma decontată.',
        'sign_off'                => 'Mulțumim,',
    ],

    'signed_digest' => [
        'subject'        => '[Deconturi] :count deconturi gata de transfer',
        'title'          => ':count deconturi gata de transfer',
        'intro'          => 'Următoarele deconturi au fost semnate și așteaptă procesarea transferului bancar.',
        'col_organiser'  => 'Organizator',
        'col_payout'     => 'Decont',
        'col_amount'     => 'Sumă',
        'col_signed_at'  => 'Semnat la',
        'button'         => 'Vezi deconturile semnate',
        'sign_off'       => 'Mulțumim,',
    ],

    'processed_digest' => [
        'subject'       => '[Deconturi] :count transferuri în așteptarea aprobării',
        'title'         => ':count transferuri în așteptarea aprobării',
        'intro'         => 'Următoarele transferuri bancare au fost înregistrate de echipa financiară și așteaptă aprobarea dumneavoastră în platforma bancară.',
        'note'          => 'După ce aprobați fiecare transfer în bancă, marcați decontul ca finalizat în panoul de administrare.',
        'col_organiser' => 'Organizator',
        'col_payout'    => 'Decont',
        'col_amount'    => 'Sumă',
        'button'        => 'Vezi deconturile în așteptare',
        'sign_off'      => 'Mulțumim,',
    ],

    'advance_payout' => [
        'subject'            => '[Cerere avans decont] :name',
        'title'              => 'Cerere de avans pentru decont primită',
        'intro'              => 'Bună ziua :name, cererea dumneavoastră de avans a fost primită și echipa financiară vă va contacta în scurt timp.',
        'label_requested_by' => 'Solicitat de:',
        'label_organiser'    => 'Organizator:',
        'label_message'      => 'Mesaj:',
        'button'             => 'Vezi deconturile',
        'sign_off'           => 'Mulțumim,',
    ],

];
