<?php


namespace MojaHedi\Auth\Http\Api;


use MojaHedi\Auth\Constants\MfaConstants;

class MfaFactory
{

    public static function message($type, $params)
    {

        switch ($type) {
            case MfaConstants::SMS:
                $sms = new SmsService($params);
                $sms->send();
                break;

            case MfaConstants::MAIL:

                break;


            default:
                return null;
                break;
        }
    }


}
