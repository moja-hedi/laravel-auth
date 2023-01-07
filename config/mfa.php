<?php

return [


    'models' => [
        /*
       * When using the "HasMfa" trait from this package, we need to know which
       * Eloquent model should be used.
       */
        'mfa' => MojaHedi\Auth\Models\Mfa::class,
    ],


    /*
    * When using the "HasMfa" trait from this package, we need to know which
    * parameter is intended for the message.
    */
    'message' => 'message',

    'table_names' => [

        /*
       * When using the "HasMfa" trait from this package, we need to know which
       * table should be used to retrieve your mfa.
       */
        'mfa' => 'mfa',
    ],


    'FROM_RAND_NUMBER' => 10000,
    'TO_RAND_NUMBER' => 99999,

    /*
     * Specify the length of time (in minutes) that the message will be valid for.
     */
    'ttl' => 5,

    /*
    * Specify the SMS_API_URL.
    */

    'sms_api_url' => env('SMS_API_URL'),


];
