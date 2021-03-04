<?php

class Andy_RegisterEmail_Listener
{
    public static function Register($class, array &$extend)
    {
        if ($class == 'XenForo_ControllerPublic_Register')
        {
            $extend[] = 'Andy_RegisterEmail_ControllerPublic_Register';
        }
    }
}