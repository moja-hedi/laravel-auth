<?php

namespace MojaHedi\Auth\Http\Auth;


use Carbon\Carbon;
use MojaHedi\Auth\Ldap\Authentication\UsersAuthenticationHandler;
use MojaHedi\Auth\Ldap\Constants\SelfServiceConstants;
use MojaHedi\Auth\Ldap\Service\DataService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;

class AuthProvider implements UserProvider
{

    private $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function retrieveById($identifier)
    {
        $user_model = new $this->model;
        return $user_model->find($identifier);
    }

    public function retrieveByToken($identifier, $token)
    {
        // We will not implement this as we are not dealing with password remember feature
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        // We will not implement this as we are not dealing with password remember feature
    }

    public function retrieveByCredentials(array $credentials)
    {
        $user = null;
        $data_service = new DataService();
        $user_info_array = $data_service->loadUserAttributesByLoginName($credentials['username']);
        if (sizeof($user_info_array) > 0) {
            $user_info_array = $data_service->convertLdapArrayToLaravelModel($user_info_array);
            $user = new $this->model;
            $user = $user->where('username', $credentials['username'])->first();
            if (config('ldap.sync_user')) {
                if ($user) {
                    $user->update($user_info_array);
                } else {
                    $user = new $this->model;
                    $user = $user->create($user_info_array);
                }
            }
        }
        return $user;
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        $authenticationHandler = new UsersAuthenticationHandler();
        $auth = $authenticationHandler->authenticate($user->username, $credentials['password']);
        if ($auth['auth']) {
            return true;
        }
        return false;
    }

    public function validateCredentialsWithSms(Authenticatable $user, array $credentials)
    {
        $mfa=$user->mfaFirst();
        if ($mfa and $mfa->code == $credentials['code']) {
            return true;
        }
        return false;
    }
}
