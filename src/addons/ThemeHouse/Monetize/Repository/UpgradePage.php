<?php

namespace ThemeHouse\Monetize\Repository;

use ThemeHouse\Monetize\XF\Repository\UserUpgrade;
use XF\Mvc\Entity\ArrayCollection;
use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Manager;
use XF\Mvc\Entity\Repository;

/**
 * Class UpgradePage
 * @package ThemeHouse\Monetize\Repository
 */
class UpgradePage extends Repository
{
    /**
     * @return Finder|\ThemeHouse\Monetize\Finder\UpgradePage
     */
    public function findUpgradePagesForList()
    {
        return $this->finder('ThemeHouse\Monetize:UpgradePage')->order(['display_order', 'upgrade_page_id']);
    }

    /**
     * @param null $type
     * @param null $available
     * @param null $purchased
     * @param array $params
     * @param null $upgradePageIds
     * @return array
     */
    public function suggestUserUpgradePageForUser(
        $type = null,
        $available = null,
        $purchased = null,
        $params = [],
        $upgradePageIds = null
    ) {
        $visitor = \XF::visitor();

        /** @var UserUpgrade $upgradeRepo */
        $upgradeRepo = $this->repository('XF:UserUpgrade');

        $upgradePages = $this->app()->container('thMonetize.upgradePages');

        $newAvailable = null;
        $newPurchased = null;
        $upgradePage = null;

        foreach ($upgradePages as $upgradePage) {
            /** @var \ThemeHouse\Monetize\Entity\UpgradePage $upgradePage */
            $upgradePage = $this->em->instantiateEntity('ThemeHouse\Monetize:UpgradePage', $upgradePage);

            if ($upgradePageIds !== null && !in_array($upgradePage->upgrade_page_id, $upgradePageIds)) {
                continue;
            }

            if ($type && !$upgradePage->$type) {
                continue;
            }

            $userCriteria = $this->app()->criteria('XF:User', $upgradePage->user_criteria);
            if (!$userCriteria->isMatched($visitor)) {
                continue;
            }

            if ($type === 'overlay' || !$upgradePage->page_criteria_overlay_only) {
                $pageCriteria = $this->app()->criteria('XF:Page', $upgradePage->page_criteria, $params);
                $pageCriteria->setMatchOnEmpty(false);
                if (!$pageCriteria->isMatched($visitor)) {
                    continue;
                }
            }

            if ($available === null && $purchased === null) {
                list($available, $purchased) = $upgradeRepo->getFilteredUserUpgradesForList();

                if (!$available) {
                    return [null, null];
                }
            }

            if ($upgradePage->show_all) {
                $newAvailable = $available;
                $newPurchased = $purchased;
            } else {
                /** @var ArrayCollection $relations */
                $relations = $upgradePage->Relations;
                $userUpgradeIds = $relations->pluckNamed('user_upgrade_id');
                /** @var ArrayCollection $available */
                $newAvailable = $available->filter(
                    function (\XF\Entity\UserUpgrade $userUpgrade) use ($userUpgradeIds) {
                        return (in_array($userUpgrade->user_upgrade_id, $userUpgradeIds));
                    }
                );
                /** @var ArrayCollection $purchased */
                $newPurchased = $purchased->filter(
                    function (\XF\Entity\UserUpgrade $userUpgrade) use ($userUpgradeIds) {
                        return (in_array($userUpgrade->user_upgrade_id, $userUpgradeIds));
                    }
                );
            }

            if ($newAvailable || $newPurchased) {
                $upgrades = $available->merge($purchased);

                if (!$upgradePage->show_all) {
                    /** @var ArrayCollection $relations */
                    $relations = $upgradePage->Relations;
                    $upgrades = $upgrades->sortByList($relations->pluckNamed('user_upgrade_id'));
                } else {
                    $entities = $upgrades->toArray();
                    usort($entities, function ($a, $b) {
                        return $a->display_order >= $b->display_order;
                    });
                    $upgrades = new ArrayCollection($entities);
                }

                return [
                    $upgradePage,
                    $upgrades,
                ];
            }
        }

        return [null, null];
    }

    /**
     * @param Manager $em
     */
    public function setEm($em)
    {
        $this->em = $em;
    }

    /**
     * @param mixed $identifier
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * @return array
     */
    public function rebuildUpgradePageCache()
    {
        $cache = [];

        $upgradePages = $this->finder('ThemeHouse\Monetize:UpgradePage')
            ->where('active', 1)
            ->order(['display_order', 'upgrade_page_id'])
            ->keyedBy('upgrade_page_id');

        foreach ($upgradePages->fetch() as $upgradePageId => $upgradePage) {
            /** @var \ThemeHouse\Monetize\Entity\UpgradePage $upgradePage */
            $cache[$upgradePageId] = $upgradePage->toArray(false);
        }

        \XF::registry()->set('thmonetize_upgradePages', $cache);
        return $cache;
    }
}
