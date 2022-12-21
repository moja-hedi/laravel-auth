<?php


namespace MojaHedi\Auth\Ldap\Authentication;


use MojaHedi\Auth\jwt\JwtService;
use MojaHedi\Auth\Ldap\Constants\SelfServiceConstants;
use MojaHedi\Auth\Ldap\Service\DataService;
use MojaHedi\Auth\Logger\MojaHediLogger;

/**
 * Class UsersAuthenticationHandler
 * @package App\MojaHedi\AD\Authentication
 * This class is for authenticating users to active directory
 */
class UsersAuthenticationHandler
{
    /**
     * @param $username
     * @param $password
     * @return array|null
     * authenticate user in ldap
     */
    public function authenticate($username, $password)
    {
        try {
            $bind_res = MojaHediLdap::Bind($username, $password);
            if ($bind_res['bind'] != false) {
                MojaHediLogger::info(SelfServiceConstants::LDAP_USERNAME_ATTRIBUTE . "=" . $username . " bind successfully");
                $ldap = $bind_res['ldap'];
                $data_service = new DataService();
                $info = $data_service->loadUsersAttributes($username, $ldap);
                MojaHediLdap::closeConnection($ldap);
                $info['auth'] = true;
                return $info;
            }

            MojaHediLogger::info(SelfServiceConstants::LDAP_USERNAME_ATTRIBUTE . "=" . $username . " can not bind");
            $info['auth'] = false;
            $info['error_no'] = $bind_res['error_no'];

            return $info;
        } catch (\Exception $e) {
            MojaHediLogger::info("Exception on authenticating: " . $e->getMessage());
            MojaHediLogger::debug("Exception on authenticating: " . $e->getTraceAsString());
            return null;
        }

    }




}
