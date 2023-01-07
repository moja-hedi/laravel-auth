<?php

return [


    'models' => [

        'mfa' => MojaHedi\Auth\Models\Mfa::class,


    ],

    'message' => 'message',
    'message_separator' => '{}',

    'table_names' => [

        'mfa' => 'mfa',


    ],

    'FROM_RAND_NUMBER' => 10000,
    'TO_RAND_NUMBER' => 99999,
    'ttl' => 5,
    'sms_api_url' => env('SMS_API_URL'),


    'sms_params' => [
        'sender',
        'message',
        'method',
        'receptor',
    ]


];
