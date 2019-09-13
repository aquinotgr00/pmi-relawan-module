<?php

return [
    'prefix' => 'volunteer',
    'religion'=>[
        'Islam',
        'Kristen',
        'Katolik',
        'Hindu',
        'Buddha',
        'Konghucu',
    ],
    'bloodType'=>['A','B','AB','O'],
    'qualification'=>[
        'category'=>[
            1=>'Penghargaan',
            2=>'Penugasan',
            3=>'Pelatihan'
        ]
    ],
    'payment_gateway' => [
        'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
        'client_key' => env('MIDTRANS_CLIENT_KEY','SB-Mid-client-5cSArh5V34nHg_JD'),
        'server_key' => env('MIDTRANS_SERVER_KEY','SB-Mid-server-baMLstdpTA09vZusn5hDr69e')
    ]
];