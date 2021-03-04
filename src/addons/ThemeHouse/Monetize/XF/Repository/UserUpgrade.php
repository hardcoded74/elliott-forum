<?php

namespace ThemeHouse\Monetize\XF\Repository;

use XF\Mvc\Entity\ArrayCollection;
use XF\Util\Color;

/**
 * Class UserUpgrade
 * @package ThemeHouse\Monetize\XF\Repository
 */
class UserUpgrade extends XFCP_UserUpgrade
{
    /**
     * @return array
     */
    public function getFilteredUserUpgradesForList()
    {
        list($available, $purchased) = parent::getFilteredUserUpgradesForList();

        if (!\XF::visitor()->user_id) {
            /** @var \XF\Entity\UserUpgrade $upgrade */
            foreach ($available as $upgradeId => $upgrade) {
                if (!$upgrade->canPurchase()) {
                    unset($available[$upgradeId]);
                }
            }
        }

        if (is_array($purchased)) {
            $purchased = new ArrayCollection($purchased);
        }

        $purchaseAgain = $purchased->filter(
            function (\XF\Entity\UserUpgrade $userUpgrade) {
                return $userUpgrade->thmonetize_allow_multiple;
            }
        );
        foreach ($purchaseAgain as $userUpgradeId => $userUpgrade) {
            $available[$userUpgradeId] = $userUpgrade;
        }

        return [$available, $purchased];
    }

    /**
     * @throws \XF\PrintableException
     */
    public function generateCssForThMonetizeUserUpgrades()
    {
        $upgrades = $this->findUserUpgradesForList();

        $cssCache = [];
        foreach ($upgrades as $upgrade) {
            if ($css = $this->generateCssForThMonetizeUserUpgrade($upgrade)) {
                $cssCache[] = $css;
            }
        }

        $this->buildThMonetizeUserUpgradeLessTemplate($cssCache);
    }

    /**
     * @param \XF\Entity\UserUpgrade $upgrade
     * @return mixed|string
     */
    protected function generateCssForThMonetizeUserUpgrade(\XF\Entity\UserUpgrade $upgrade)
    {
        /** @var \ThemeHouse\Monetize\XF\Entity\UserUpgrade $upgrade */
        $styleProperties = $upgrade->thmonetize_style_properties;

        if (!empty($styleProperties['color'])) {
            $color = Color::colorToRgb($styleProperties['color']);
            $lum = Color::getRelativeLuminance($color);
            $params = [
                'upgrade' => $upgrade,
                'isLight' => $lum < 0.5,
                'color' => $styleProperties['color'],
                'shape' => !empty($styleProperties['shape']) ? $styleProperties['shape'] : '',
            ];

            $templater = \XF::app()->templater();

            $css = $templater->renderMacro('public:thmonetize_user_upgrade_generator_macros', 'generator', $params);

            return $css;
        }
        return null;
    }

    /**
     * @param array $cssCache
     * @return null|\XF\Mvc\Entity\Entity
     * @throws \XF\PrintableException
     */
    protected function buildThMonetizeUserUpgradeLessTemplate(array $cssCache)
    {
        $css = implode("\n", $cssCache);

        /** @var \XF\Entity\Template $template */
        $template = $this->finder('XF:Template')->where('style_id', '=', 0)
            ->where('title', '=', 'thmonetize_user_upgrade_cache.less')
            ->fetchOne();

        if (empty($template)) {
            $template = $this->em->create('XF:Template');
            $template->bulkSet([
                'type' => 'public',
                'title' => 'thmonetize_user_upgrade_cache.less',
                'style_id' => 0,
            ]);
        }

        $template->template = "<xf:comment>Do not directly edit this template, doing so will cause issues when you edit your topics.</xf:comment>\n\n" . $css;
        $template->addon_id = '';
        $template->last_edit_date = \XF::$time;
        $template->save();

        return $template;
    }

    /**
     * @param ArrayCollection $upgrades
     * @return ArrayCollection
     */
    public function thMonetizeAddFeaturesToDescription(ArrayCollection $upgrades)
    {
        foreach ($upgrades as $upgrade) {
            if ($upgrade->thmonetize_features) {
                $features = \XF::app()->templater()->renderTemplate('public:thmonetize_features', [
                    'upgrade' => $upgrade,
                ]);
                $upgrade->description = $upgrade->description . $features;
            }
        }

        return $upgrades;
    }

    /**
     * @param $userId
     */
    public function rebuildThMonetizeActiveUserUpgradeCache($userId)
    {
        $cache = $this->getThMonetizeActiveUserUpgradesCache($userId);

        $this->db()->update(
            'xf_user_profile',
            ['thmonetize_active_upgrades' => json_encode($cache)],
            'user_id = ?',
            $userId
        );
    }

    /**
     * @param $userId
     * @return array
     */
    public function getThMonetizeActiveUserUpgradesCache($userId)
    {
        $userUpgradeRecords = $this->finder('XF:UserUpgradeActive')
            ->where('user_id', '=', $userId);

        $cache = [];

        foreach ($userUpgradeRecords as $userUpgradeRecordId => $userUpgradeRecord) {
            $cache[$userUpgradeRecordId] = [
                'user_upgrade_id' => $userUpgradeRecord->user_upgrade_id,
                'start_date' => $userUpgradeRecord->start_date,
                'end_date' => $userUpgradeRecord->end_date,
                'updated' => $userUpgradeRecord->thmonetize_updated,
            ];
        }

        return $cache;
    }

    /**
     * @param $userId
     */
    public function rebuildThMonetizeExpiredUserUpgradeCache($userId)
    {
        $cache = $this->getThMonetizeExpiredUserUpgradesCache($userId);

        $this->db()->update(
            'xf_user_profile',
            ['thmonetize_expired_upgrades' => json_encode($cache)],
            'user_id = ?',
            $userId
        );
    }

    /**
     * @param $userId
     * @return array
     */
    public function getThMonetizeExpiredUserUpgradesCache($userId)
    {
        $userUpgradeRecords = $this->finder('XF:UserUpgradeExpired')
            ->where('user_id', '=', $userId);

        $cache = [];

        foreach ($userUpgradeRecords as $userUpgradeRecordId => $userUpgradeRecord) {
            $cache[$userUpgradeRecordId] = [
                'user_upgrade_id' => $userUpgradeRecord->user_upgrade_id,
                'start_date' => $userUpgradeRecord->start_date,
                'end_date' => $userUpgradeRecord->end_date,
                'updated' => $userUpgradeRecord->thmonetize_updated,
            ];
        }

        return $cache;
    }

    /**
     * @param $userId
     */
    public function rebuildThMonetizeUserUpgradeCaches($userId)
    {
        $activeCache = $this->getThMonetizeActiveUserUpgradesCache($userId);
        $expiredCache = $this->getThMonetizeExpiredUserUpgradesCache($userId);

        $this->db()->update(
            'xf_user_profile',
            [
                'thmonetize_active_upgrades' => json_encode($activeCache),
                'thmonetize_expired_upgrades' => json_encode($expiredCache),
            ],
            'user_id = ?',
            $userId
        );
    }
}
