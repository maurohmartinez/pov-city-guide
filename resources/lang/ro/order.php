<?php

return [
    'order_sent_page_title' => 'Comandă trimisă',

    'processing_fee' => 'Taxă de procesare',
    'your_order' => 'Comanda ta',
    'your_info' => 'Informațiile tale',
    'retry_payment' => 'Reîncearcă plata',
    'tnc_checkbox_label' => 'Sunt de acord cu <a class="text-muted text-decoration-underline" href=":link" target="_blank">Termenii și condițiile</a>.',
    'marketing_emails_checkbox_label' => 'Sunt de acord să primesc e-mailuri de marketing despre evenimente similare.',
    'order_date' => 'Dată comandă',
    'order_number' => 'Număr comandă',
    'payment_method' => 'Metodă de plată',
    'payment_status' => 'Status plată',
    'order_status' => 'Status comandă',
    'notes' => 'Observații',
    'email_notes' => 'Observații în email',
    'email_reminder_notes' => 'Observații în email-ul de reminder',
    'financials' => 'Financiar',
    'timeline' => 'Timeline',
    'discount' => 'Discount',
    'discount_code' => 'Cod de discount',
    'enter_discount_code' => 'Introdu codul de discount',
    'order_summary' => 'Rezumatul comenzii',
    'no_tickets_selected' => 'Nu au fost selectate bilete.',

    'send_email' => 'Trimite Bilete pe Email',
    'this_will_resend_order_email' => 'Se va retrimite emailul de confirmare a comenzii, cu biletele atașate ca PDF.',
    'send_email_success' => 'Emailul a fost pus pe listă, va fi trimis cât mai curând posibil!',
    'order_not_complete_cannot_send_email' => 'Comanda nu este completă! Nu se poate trimite emailul.',
    'order_does_no_have_tickets_generated_cannot_send_email' => 'Comanda nu are toate biletele generate! Nu se poate trimite emailul.',

    'regenerate_pdf_tickets' => 'Regenerează biletele',
    'regenerate_pdf_tickets_confirmation' => 'Sigur doriți să regenerați toate biletele PDF pentru această comandă? Aceasta va suprascrie orice PDF-uri existente.',
    'regenerate_pdf_tickets_success' => 'Biletele PDF au fost regenerate cu succes.',
    'regenerate_pdf_tickets_error' => 'Eroare la regenerarea biletelor PDF:',
    'order_not_complete_cannot_regenerate_tickets' => 'Comanda nu este completă! Nu se pot regenera biletele PDF.',
    'error_occurred' => 'A apărut o eroare',

    'this_will_recover_order' => 'Aceasta va recupera comanda și toate biletele acesteia.',
    'not_enough_seats_to_restore_order' => 'Nu există suficiente locuri disponibile pentru a recupera comanda.',
    'order_successfully_restored' => 'Comanda recuperata cu succes!',

    'refund' => 'Returnează',
    'refund_only_card' => 'Rambursările automate sunt disponibile doar pentru plățile cu CARD',
    'notify_customer_of_refund' => 'Notifică clientul despre rambursare prin email',
    'delete_order' => 'Șterge comanda',
    'put_tickets_back_on_sale' => 'Pune biletele înapoi la vânzare',
    'back_to_orders' => 'Înapoi la comenzi',

    'status' => [
        'completed' => 'Completă',
        'reserved' => 'În desfășurare',
        'awaiting_payment' => 'În așteptare plată',
        'awaiting_ticket_generation' => 'În așteptare generare bilete',
        'refunded' => 'Rambursată',
        'expired' => 'Expirată'
    ],

    'pay_status' => [
        'accepted' => 'Acceptată',
        'in_progress' => 'În curs',
        'rejected' => 'Respinsă',
        'refunded' => 'Rambursată'
    ],

    'messages' => [
        'reservation_expires_in' => 'Comanda va expira în :expiry_time',

        'payment_redirect' => 'Veți fi redirecționat către pagina de plată în câteva momente...',

        'order_success_title' => 'Mulțumim pentru comandă!',
        'order_success_subtitle' => 'Confirmarea comenzii a fost trimisă la :email.',

        'order_pending_title' => 'Plata este în curs de procesare!',
        'order_pending_subtitle' => 'Vă vom anunța imediat ce plata este confirmată.',

        'order_failed_title' => 'Plata a eșuat!',
        'order_failed_subtitle' => 'Din păcate, plata dvs. a eșuat. Vă rugăm să încercați din nou.',

        'order_unknown_status_title' => 'Status necunoscut',
        'order_unknown_status_subtitle' => 'Vă rugăm să contactați suportul pentru asistență suplimentară.',

        'awaiting_ticket_generation' => 'Biletele sunt în curs de generare. Vă rugăm așteptați.',

        'confirm_order_deletion' => 'Trebuie să confirmați ștergerea comenzii.',
        'order_not_deleted' => 'Comanda nu a fost ștearsă.',
        'order_deleted' => 'Comanda a fost ștearsă.',
        'last_chance_to_purchase' => 'Ultima șansă! Asigură-ți biletul înainte să dispară!',

        'sent_to' => 'Trimis către :email',
    ],

    'validation' => [
        'all_tickets_required' => 'Toate categoriile de bilete sunt obligatorii.',
        'repeated_ticket' => 'Asigurați-vă că nu alegeți același bilet de două ori.',
        'cat_doesnt_exist' => 'Cel puțin o categorie de bilete nu există pentru evenimentul selectat.',
        'not_enough_seats' => 'Categoria de bilete ":category_name" are doar :available_seats locuri disponibile, iar dumneavoastră încercați să vindeți :seats_count.',
        'max_tickets_per_order' => 'Nu puteți adăuga mai mult de :max bilete per comandă.',
        'email_required' => 'E-mailul este obligatoriu',
        'email_invalid' => 'E-mailul este invalid',
        'checkout_type_not_supported' => 'Comenzile pot fi adăugate doar pentru evenimente cu checkout simplu, pe intervale orare sau cu hartă de locuri.',
        'min_tickets' => 'Adăugați cel puțin un bilet.',
        'price_invalid' => 'Prețul biletelor trebuie să fie zero sau o sumă pozitivă.',
        'seat_doesnt_exist' => 'Locul ":seat" nu există pe harta de locuri.',
        'seat_not_in_category' => 'Locul ":seat" nu aparține categoriei ":category_name".',
        'seat_occupied' => 'Locul ":seat" a fost deja vândut sau rezervat.',
        'seatmap_missing' => 'Acest eveniment nu are hartă de locuri, așa că locurile nu pot fi vândute.',
    ],

    'timeline_actions' => [
        'order_created' => 'Comanda a fost creată',
        'payment_initialized' => 'Plata a fost inițializată',
        'ipn_received' => '[:gateway] Notificare primită | Stare: :status',
        'ipn_received_with_reason' => '[:gateway] Notificare primită | Stare: :status | Motiv: :reason',
        'order_deleted' => 'Comanda a fost ștearsă',
    ],
];
