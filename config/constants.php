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
        'begining' => 1,
        'success' => 2,
    ),

];
