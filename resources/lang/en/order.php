<?php

return [
    'order_sent_page_title' => 'Order Sent',
    'processing_fee' => 'Processing fee',
    'your_order' => 'Your order',
    'your_info' => 'Your info',
    'retry_payment' => 'Retry payment',
    'tnc_checkbox_label' => 'I agree with the <a class="text-muted text-decoration-underline" href=":link" target="_blank">Terms and Conditions</a>.',
    'marketing_emails_checkbox_label' => 'I agree to receive marketing emails about similar events.',
    'order_date' => 'Order date',
    'order_number' => 'Order number',
    'payment_method' => 'Payment method',
    'payment_status' => 'Payment status',
    'order_status' => 'Order status',
    'notes' => 'Notes',
    'email_notes' => 'Email Notes',
    'email_reminder_notes' => 'Email Reminder Notes',
    'financials' => 'Financials',
    'timeline' => 'Timeline',
    'discount' => 'Discount',
    'discount_code' => 'Discount code',
    'enter_discount_code' => 'Enter discount code',
    'order_summary' => 'Order Summary',
    'no_tickets_selected' => 'No Tickets Selected.',

    'send_email' => 'Send Tickets By Email',
    'this_will_resend_order_email' => 'This will re-send the order confirmation email, with the tickets attached as PDFs.',
    'send_email_success' => 'The email was queued, will be sent ASAP!',
    'order_not_complete_cannot_send_email' => 'Order is not complete! Cannot send email.',
    'order_does_no_have_tickets_generated_cannot_send_email' => 'Order does not have all tickets generated! Cannot send email.',

    'regenerate_pdf_tickets' => 'Regenerate Tickets',
    'regenerate_pdf_tickets_confirmation' => 'Are you sure you want to regenerate all PDF tickets for this order? This will overwrite any existing PDFs.',
    'regenerate_pdf_tickets_success' => 'PDF tickets regenerated successfully.',
    'regenerate_pdf_tickets_error' => 'Error regenerating PDF tickets:',
    'order_not_complete_cannot_regenerate_tickets' => 'Order is not complete! Cannot regenerate PDF tickets.',
    'error_occurred' => 'An error occurred',

    'this_will_recover_order' => 'This will restore the order and all its tickets.',
    'not_enough_seats_to_restore_order' => 'There are not enough available seats to restore the order.',
    'order_successfully_restored' => 'Order successfully restored!',

    'refund' => 'Refund',
    'refund_only_card' => 'Automatic refunds are only available for CARD payments',
    'notify_customer_of_refund' => 'Notify customer of refund by email',
    'delete_order' => 'Delete order',
    'put_tickets_back_on_sale' => 'Put tickets back on sale',
    'back_to_orders' => 'Back to orders',

    'status' => [
        'completed' => 'Completed',
        'reserved' => 'Reserved',
        'awaiting_payment' => 'Awaiting Payment',
        'awaiting_ticket_generation' => 'Awaiting Ticket Generation',
        'refunded' => 'Refunded',
        'expired' => 'Expired',
    ],

    'pay_status' => [
        'accepted' => 'Accepted',
        'in_progress' => 'In Progress',
        'rejected' => 'Rejected',
        'refunded' => 'Refunded',
    ],

    'messages' => [
        'reservation_expires_in' => 'Your order will expire in :expiry_time',

        'payment_redirect' => 'You will be redirected to the payment page in a few moments...',

        'order_success_title' => 'Thanks for your order!',
        'order_success_subtitle' => 'The order confirmation has been sent to :email.',

        'order_pending_title' => 'Your payment is being processed!',
        'order_pending_subtitle' => 'We will notify you once the payment is confirmed.',

        'order_failed_title' => 'Payment Failed!',
        'order_failed_subtitle' => 'Unfortunately, your payment was not successful. Please try again.',

        'order_unknown_status_title' => 'Unknown Status',
        'order_unknown_status_subtitle' => 'Please contact support for further assistance.',

        'awaiting_ticket_generation' => 'We\'re generating your tickets... Please wait.',

        'confirm_order_deletion' => 'You need to confirm the deletion of the order.',
        'order_not_deleted' => 'Order not deleted.',
        'order_deleted' => 'Order deleted',
        'last_chance_to_purchase' => 'Almost gone! Complete your purchase before it sells out!',

        'sent_to' => 'Sent to :email',
    ],

    'validation' => [
        'all_tickets_required' => 'All Ticket categories are required.',
        'repeated_ticket' => 'Make sure you do not choose the same ticket twice.',
        'cat_doesnt_exist' => 'At least one ticket category doesn\'t exist for the selected event.',
        'not_enough_seats' => '":category_name" category Ticket has only :available_seats available seats and you are trying to sell :seats_count.',
        'max_tickets_per_order' => 'You cannot add more than :max tickets per order.',
        'email_required' => 'E-mail is required',
        'email_invalid' => 'E-mail is invalid',
        'checkout_type_not_supported' => 'Orders can only be added for events with simple, timeslot or seatmap checkout.',
        'min_tickets' => 'Add at least one ticket.',
        'price_invalid' => 'Ticket prices must be zero or a positive amount.',
        'seat_doesnt_exist' => 'Seat ":seat" does not exist on the seatmap.',
        'seat_not_in_category' => 'Seat ":seat" does not belong to the ":category_name" category.',
        'seat_occupied' => 'Seat ":seat" has already been sold or reserved.',
        'seatmap_missing' => 'This event has no seatmap, so seats cannot be sold.',
    ],

    'timeline_actions' => [
        'order_created' => 'Order created',
        'payment_initialized' => 'Payment initialized',
        'ipn_received' => '[:gateway] Received notification | Status: :status',
        'ipn_received_with_reason' => '[:gateway] Received notification | Status: :status | Reason: :reason',
        'order_deleted' => 'Order deleted',
    ],
];
