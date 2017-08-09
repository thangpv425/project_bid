<?php

return [
    'user_grant' => array(
        'admin' => '1',
        'user' => '0'
        // etc
    ),

    'user_status' => array(
        'active' => '1',
        'inactive' => '0',
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
    )

];