<?php

namespace ThemeHouse\Monetize\XF;

/**
 * Class PermissionSet
 * @package ThemeHouse\Monetize\XF
 */
class PermissionSet extends XFCP_PermissionSet
{
    /**
     * @var null
     */
    protected $thMonetize_lastFailedContentPermissionCheck = null;
    /**
     * @var null
     */
    protected $thMonetize_lastFailedGlobalPermissionCheck = null;

    /**
     * @param $group
     * @param $permission
     * @return bool
     */
    public function hasGlobalPermission($group, $permission)
    {
        $hasPermission = parent::hasGlobalPermission($group, $permission);

        if (!$hasPermission) {
            $this->thMonetize_lastFailedGlobalPermissionCheck = [$group, $permission];
        }

        return $hasPermission;
    }

    /**
     * @return null
     */
    public function getThMonetizeLastFailedGlobalPermissionCheck()
    {
        return $this->thMonetize_lastFailedGlobalPermissionCheck;
    }

    /**
     * @param $contentType
     * @param $contentId
     * @param $permission
     * @return bool
     */
    public function hasContentPermission($contentType, $contentId, $permission)
    {
        $hasPermission = parent::hasContentPermission($contentType, $contentId, $permission);

        if (!$hasPermission) {
            $this->thMonetize_lastFailedContentPermissionCheck = [$contentType, $contentId, $permission];
        }

        return $hasPermission;
    }

    /**
     * @return null
     */
    public function getThMonetizeLastFailedContentPermissionCheck()
    {
        return $this->thMonetize_lastFailedContentPermissionCheck;
    }
}
