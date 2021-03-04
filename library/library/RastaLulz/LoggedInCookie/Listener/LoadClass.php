<?php

class RastaLulz_LoggedInCookie_Listener_LoadClass
{
    public static function loadModelUser($class, &$extend)
    {
        $extend[] = 'RastaLulz_LoggedInCookie_Model_User';
    }
}