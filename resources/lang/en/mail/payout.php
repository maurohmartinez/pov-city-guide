<?php

return [

    'created' => [
        'subject'                 => '[Action Required] New payout for :name – :hash',
        'title'                   => 'New payout for :name',
        'greeting'                => 'Hello,',
        'intro'                   => 'A new payout has been generated that requires your signature.',
        'label_payout'            => 'Payout number',
        'label_total_sales'       => 'Total sales',
        'label_commission'        => 'Platform commission',
        'label_net_amount'        => 'Payout amount',
        'attachments_intro'       => 'Attached you will find two documents:',
        'attachment_report'       => '**Order report** – full list of orders included in this payout',
        'attachment_confirmation' => '**Payout confirmation** – summary document that requires your signature',
        'cta_text'                => 'Please review the attached documents and sign the payout using the link below.',
        'button'                  => 'Sign the payout',
        'sign_off'                => 'Thanks,',
    ],

    'completed' => [
        'subject'                 => 'Payout complete for :name – :hash',
        'title'                   => 'Payout complete for :name',
        'greeting'                => 'Hello,',
        'intro'                   => 'The payment for payout :hash has been successfully completed.',
        'bank_timing'             => 'Depending on your bank, the amount will arrive in your account within 1–5 business days.',
        'label_net_amount'        => 'Net amount:',
        'label_processed_at'      => 'Processed at:',
        'attachments_intro'       => 'Attached you will find:',
        'attachment_report'       => 'Signed order report',
        'attachment_confirmation' => 'Signed payout confirmation',
        'attachment_finance'      => 'Bank transfer documents and invoice',
        'invoice_note'            => 'The attached invoice **does NOT need to be paid**. The platform commission has already been deducted from the payout amount.',
        'sign_off'                => 'Thanks,',
    ],

    'signed_digest' => [
        'subject'        => '[Payouts] :count signed payout(s) ready for transfer',
        'title'          => ':count payout(s) ready for transfer',
        'intro'          => 'The following payouts have been signed and are waiting for the bank transfer to be processed.',
        'col_organiser'  => 'Organiser',
        'col_payout'     => 'Payout',
        'col_amount'     => 'Amount',
        'col_signed_at'  => 'Signed at',
        'button'         => 'View Signed Payouts',
        'sign_off'       => 'Thanks,',
    ],

    'processed_digest' => [
        'subject'       => '[Payouts] :count transfer(s) pending your approval',
        'title'         => ':count transfer(s) pending your approval',
        'intro'         => 'The following bank transfers have been submitted by the finance team and are awaiting your approval in the banking platform.',
        'note'          => 'Once approved in the bank, please mark each payout as completed in the admin panel.',
        'col_organiser' => 'Organiser',
        'col_payout'    => 'Payout',
        'col_amount'    => 'Amount',
        'button'        => 'View Payouts Awaiting Approval',
        'sign_off'      => 'Thanks,',
    ],

    'advance_payout' => [
        'subject'            => '[Advance Payout Request] :name',
        'title'              => 'Advance Payout Request Received',
        'intro'              => 'Hi :name, your advance payout request has been received and our finance team will be in touch shortly.',
        'label_requested_by' => 'Requested by:',
        'label_organiser'    => 'Organiser:',
        'label_message'      => 'Message:',
        'button'             => 'View Payouts',
        'sign_off'           => 'Thanks,',
    ],

];
