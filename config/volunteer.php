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
    'push_notification' => [
        'app_id' => 'b7d76b60-8459-4fcc-8b38-fc1147614d9b',
        'rest_api_key' => 'YTM2ZGI1MzMtZDAzNS00NDQzLWFmNzctY2NjYTdhYjU2MDcz'
    ],
    'payment_gateway' => [
        'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
        'client_key' => env('MIDTRANS_CLIENT_KEY','SB-Mid-client-5cSArh5V34nHg_JD'),
        'server_key' => env('MIDTRANS_SERVER_KEY','SB-Mid-server-baMLstdpTA09vZusn5hDr69e')
    ]
];