<?php

return [
    'user_grant' => array(
        'admin' => '1',
        'user' => '0'
        // etc
    ),

    'user_status' => array(
        'inactive' => '0',
        'active' => '1',
        'block' => '2'
    ),

    'hash_type' => array(
        'register' => '1',
        'change_email' => '2',
        'forgot_password' => '3',
    ),

    /*
     * time during when change mail, register, forgot password
     */
    'time_during' => array(
        'register' => 5,
        'change_email' => 5,
        'forgot_password' => 5,
    ),
    'bid_amount_step' => 500,

    'bid_amount_type' => array(
        'manual' => 0,
        'auto' =>1,
    ),

    'bid_status' => array(
        'bid' => 1,
        'bid_success' => 2,
        'bid_fail' => 3,
        'pending_payment' => 4,
        'waiting_payment_confirm' => 5,
        'payment_confirm_success' => 6,
        'bid_success' => 7
    ),

    'number_item_per_page' => array(
        'success_bids' => 8,
        'current_bids' => 8,
    ),
];
