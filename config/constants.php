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
        'current_bid' => 1,
        'bid_success' => 2,
        'bid_fail' => 3,
        'pending_payment' => 4,
        'waiting_payment_confirm' => 5,
        'payment_confirm_success' => 6,
        'waiting_shipping' => 7,
        'cancel' => 8,
    ),

    'number_item_per_page' => array(
        'success_bids' => 8,
        'current_bids' => 8,
        'fail-bid' => 8,
    ),

    'japan_prefectures' => array(
        '-1' => '都道府県',
        '北海道'=>'北海道',
        '青森県'=>'青森県',
        '岩手県'=>'岩手県',
        '宮城県'=>'宮城県',
        '秋田県'=>'秋田県',
        '山形県'=>'山形県',
        '福島県'=>'福島県',
        '茨城県'=>'茨城県',
        '栃木県'=>'栃木県',
        '群馬県'=>'群馬県',
        '埼玉県'=>'埼玉県',
        '千葉県'=>'千葉県',
        '東京都'=>'東京都',
        '神奈川県'=>'神奈川県',
        '新潟県'=>'新潟県',
        '富山県'=>'富山県',
        '石川県'=>'石川県',
        '福井県'=>'福井県',
        '山梨県'=>'山梨県',
        '長野県'=>'長野県',
        '岐阜県'=>'岐阜県',
        '静岡県'=>'静岡県',
        '愛知県'=>'愛知県',
        '三重県'=>'三重県',
        '滋賀県'=>'滋賀県',
        '京都府'=>'京都府',
        '大阪府'=>'大阪府',
        '兵庫県'=>'兵庫県',
        '奈良県'=>'奈良県',
        '和歌山県'=>'和歌山県',
        '鳥取県'=>'鳥取県',
        '島根県'=>'島根県',
        '岡山県'=>'岡山県',
        '広島県'=>'広島県',
        '山口県'=>'山口県',
        '徳島県'=>'徳島県',
        '香川県'=>'香川県',
        '愛媛県'=>'愛媛県',
        '高知県'=>'高知県',
        '福岡県'=>'福岡県',
        '佐賀県'=>'佐賀県',
        '長崎県'=>'長崎県',
        '熊本県'=>'熊本県',
        '大分県'=>'大分県',
        '宮崎県'=>'宮崎県',
        '鹿児島県'=>'鹿児島県',
        '沖縄県'=>'沖縄県'
    ),
];
