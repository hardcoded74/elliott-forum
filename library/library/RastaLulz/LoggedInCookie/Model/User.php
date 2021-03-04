<?php

class RastaLulz_LoggedInCookie_Model_User extends XFCP_RastaLulz_LoggedInCookie_Model_User
{
    public function setUserRememberCookie($userId, $auth = null)
    {
        $response = parent::setUserRememberCookie($userId, $auth);

        XenForo_Helper_Cookie::setCookie('logged_in', 1, 30 * 86400, true);

        return $response;
    }
}