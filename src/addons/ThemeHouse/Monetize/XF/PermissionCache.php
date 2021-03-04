<?php

namespace ThemeHouse\Monetize\XF;

/**
 * Class PermissionCache
 * @package ThemeHouse\Monetize\XF
 */
class PermissionCache extends XFCP_PermissionCache
{
    /**
     * @param $permissionCombinationId
     * @return mixed|\XF\PermissionSet
     * @throws \Exception
     */
    public function getPermissionSet($permissionCombinationId)
    {
        $class = \XF::extendClass('XF\PermissionSet');
        return new $class($this, $permissionCombinationId);
    }
}
