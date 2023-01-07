<?php

namespace MojaHedi\Auth\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use MojaHedi\Auth\Http\Api\MfaFactory;

trait HasMfa
{

    public function mfa(): HasMany
    {
        return $this->HasMany(
            config('mfa.models.mfa'),
            'id_user'
        );
    }

    public function mfaFirst()
    {
        $this->mfa()->where('expired_at', '<', Carbon::now())->delete();
        return $this->mfa()->where('expired_at', '>', Carbon::now())->first();
    }

    public function mfaCreate(string $message_type = 'sms', array $params = [])
    {
        if (isset($params[config('mfa.message')])) {

            DB::beginTransaction();

            $mfa = $this->mfaFirst();
            if (!$mfa) {
                $rand_code = rand(config("mfa.FROM_RAND_NUMBER"), config("mfa.TO_RAND_NUMBER"));

                $mfa = $this->mfa()->create([
                    'id_user' => $this->id,
                    'code' => $rand_code,
                    'expired_at' => Carbon::now()->addMinutes(config("mfa.ttl")),
                ]);

            }

            DB::commit();

            $params[config('mfa.message')] = $this->message_replace($mfa->code, $params[config('mfa.message')]);

            MfaFactory::message($message_type, $params);

            return true;
        }

        return false;
    }

    public function message_replace($code, $message)
    {
        return str_replace(config('mfa.message_separator'), $code, $message);
    }


}
