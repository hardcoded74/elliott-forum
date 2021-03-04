<?php

class RastaLulz_LoggedInCookie_Listener_VisitorSetup
{
    private static $initialSetup = true;

    public static function visitorSetup(&$visitor)
    {
        $isUserLoggedIn = $visitor['user_id'] != 0;

        if ($isUserLoggedIn)
        {
            if (!XenForo_Helper_Cookie::getCookie('logged_in'))
            {
                XenForo_Helper_Cookie::setCookie('logged_in', 1, 30 * 86400, true);

                if (self::$initialSetup)
                {
                    XenForo_Visitor::setup(0);
                }
            }
        }
        else
        {
            $scriptName = basename($_SERVER['SCRIPT_NAME'], '.php');

            if ($scriptName == 'index')
            {
                if (XenForo_Helper_Cookie::getCookie('logged_in'))
                {
                    XenForo_Helper_Cookie::deleteCookie('logged_in', true);
                }
            }
        }

        self::$initialSetup = false;
    }
}