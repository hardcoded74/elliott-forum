<?php

namespace ThemeHouse\Monetize\Criteria;

use XF\App;
use XF\Criteria\AbstractCriteria;
use XF\Entity\User;
use XF\Mvc\Entity\ArrayCollection;
use XF\Util\Php;

/**
 * Class UserUpgrade
 * @package ThemeHouse\Monetize\Criteria
 */
class UserUpgrade extends AbstractCriteria
{
    /**
     * @var null|ArrayCollection
     */
    protected $userUpgrades;

    /**
     * UserUpgrade constructor.
     * @param App $app
     * @param array $criteria
     * @param ArrayCollection|null $userUpgrades
     */
    public function __construct(App $app, array $criteria, ArrayCollection $userUpgrades = null)
    {
        parent::__construct($app, $criteria);

        $this->userUpgrades = $userUpgrades;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function isMatched(User $user)
    {
        if (!$this->criteria) {
            return $this->matchOnEmpty;
        }

        /** @noinspection PhpUndefinedFieldInspection */
        $active = $user->Profile->thmonetize_active_upgrades;
        /** @noinspection PhpUndefinedFieldInspection */
        $expired = $user->Profile->thmonetize_expired_upgrades;

        $upgradeRecords = [];
        if ($active) {
            foreach ($active as $upgradeRecordId => $upgradeRecord) {
                $upgradeRecords[$upgradeRecordId] = [
                    'active' => true,
                    'user_upgrade_id' => $upgradeRecord['user_upgrade_id'],
                    'start_date' => $upgradeRecord['start_date'],
                    'end_date' => $upgradeRecord['end_date'],
                    'updated' => !empty($upgradeRecord['updated']) ? $upgradeRecord['updated'] : $upgradeRecord['start_date'],
                ];
            }
        }
        if ($expired) {
            foreach ($expired as $upgradeRecordId => $upgradeRecord) {
                $upgradeRecords[$upgradeRecordId] = [
                    'active' => false,
                    'user_upgrade_id' => $upgradeRecord['user_upgrade_id'],
                    'start_date' => $upgradeRecord['start_date'],
                    'end_date' => $upgradeRecord['end_date'],
                    'updated' => !empty($upgradeRecord['updated']) ? $upgradeRecord['updated'] : $upgradeRecord['start_date'],
                ];
            }
        }

        if (!$upgradeRecords) {
            return false;
        }

        $userUpgradeIds = array_column($upgradeRecords, 'user_upgrade_id');

        foreach ($userUpgradeIds as $userUpgradeId) {
            if (!isset($this->userUpgrades[$userUpgradeId])) {
                continue;
            }
            $userUpgrade = $this->userUpgrades[$userUpgradeId];

            $selectedUpgradeRecords = array_filter(
                $upgradeRecords,
                function ($upgradeRecord) use ($userUpgradeId) {
                    return ($upgradeRecord['user_upgrade_id'] === $userUpgradeId);
                }
            );

            foreach ($this->criteria as $criterion) {
                $rule = $criterion['rule'];
                $data = $criterion['data'];

                $method = '_match' . Php::camelCase($rule);
                if (method_exists($this, $method)) {
                    $result = $this->$method($data, $user, $userUpgrade, $selectedUpgradeRecords);
                    if (!$result) {
                        continue(2);
                    }
                }
            }

            return true;
        }

        return false;
    }

    /**
     * @return array
     */
    public function getExtraTemplateData()
    {
        /** @var \XF\Repository\UserUpgrade $userUpgradeRepo */
        $userUpgradeRepo = $this->app->repository('XF:UserUpgrade');
        $userUpgrades = $userUpgradeRepo->getUpgradeTitlePairs();

        $templateData = [
            'userUpgrades' => $userUpgrades,
        ];

        return $templateData;
    }

    /**
     * @param array $data
     * @param User $user
     * @param \XF\Entity\UserUpgrade $userUpgrade
     * @param array $upgradeRecords
     * @return bool
     */
    protected function _matchIsActive(
        array $data,
        User $user,
        \XF\Entity\UserUpgrade $userUpgrade,
        array $upgradeRecords
    ) {
        foreach ($upgradeRecords as $upgradeRecord) {
            if ($upgradeRecord['active']) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $data
     * @param User $user
     * @param \XF\Entity\UserUpgrade $userUpgrade
     * @param array $upgradeRecords
     * @return bool
     */
    protected function _matchIsNotActive(
        array $data,
        User $user,
        \XF\Entity\UserUpgrade $userUpgrade,
        array $upgradeRecords
    ) {
        foreach ($upgradeRecords as $upgradeRecord) {
            if (!$upgradeRecord['active']) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array $data
     * @param User $user
     * @param \XF\Entity\UserUpgrade $userUpgrade
     * @param array $upgradeRecords
     * @return bool
     */
    protected function _matchIsExpired(
        array $data,
        User $user,
        \XF\Entity\UserUpgrade $userUpgrade,
        array $upgradeRecords
    ) {
        foreach ($upgradeRecords as $upgradeRecord) {
            if (!$upgradeRecord['active']) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $data
     * @param User $user
     * @param \XF\Entity\UserUpgrade $userUpgrade
     * @param array $upgradeRecords
     * @return bool
     */
    protected function _matchIsNotExpired(
        array $data,
        User $user,
        \XF\Entity\UserUpgrade $userUpgrade,
        array $upgradeRecords
    ) {
        foreach ($upgradeRecords as $upgradeRecord) {
            if ($upgradeRecord['active']) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $data
     * @param User $user
     * @param \XF\Entity\UserUpgrade $userUpgrade
     * @param array $upgradeRecords
     * @return bool
     */
    protected function _matchIsRecurring(
        array $data,
        User $user,
        \XF\Entity\UserUpgrade $userUpgrade,
        array $upgradeRecords
    ) {
        return $userUpgrade->recurring;
    }

    /**
     * @param array $data
     * @param User $user
     * @param \XF\Entity\UserUpgrade $userUpgrade
     * @param array $upgradeRecords
     * @return bool
     */
    protected function _matchIsNotRecurring(
        array $data,
        User $user,
        \XF\Entity\UserUpgrade $userUpgrade,
        array $upgradeRecords
    ) {
        return !$userUpgrade->recurring;
    }

    /**
     * @param array $data
     * @param User $user
     * @param \XF\Entity\UserUpgrade $userUpgrade
     * @param array $upgradeRecords
     * @return bool
     */
    protected function _matchStartDateWithin(
        array $data,
        User $user,
        \XF\Entity\UserUpgrade $userUpgrade,
        array $upgradeRecords
    ) {
        $cutOff = \XF::$time - ($data['days'] * 86400);

        foreach ($upgradeRecords as $upgradeRecord) {
            if ($upgradeRecord['start_date'] >= $cutOff) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $data
     * @param User $user
     * @param \XF\Entity\UserUpgrade $userUpgrade
     * @param array $upgradeRecords
     * @return bool
     */
    protected function _matchStartDateNotWithin(
        array $data,
        User $user,
        \XF\Entity\UserUpgrade $userUpgrade,
        array $upgradeRecords
    ) {
        $cutOff = \XF::$time - ($data['days'] * 86400);

        foreach ($upgradeRecords as $upgradeRecord) {
            if ($upgradeRecord['start_date'] >= $cutOff) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array $data
     * @param User $user
     * @param \XF\Entity\UserUpgrade $userUpgrade
     * @param array $upgradeRecords
     * @return bool
     */
    protected function _matchEndDateWithin(
        array $data,
        User $user,
        \XF\Entity\UserUpgrade $userUpgrade,
        array $upgradeRecords
    ) {
        $earlyCutOff = \XF::$time - ($data['days'] * 86400);
        $lateCutOff = \XF::$time + ($data['days'] * 86400);

        foreach ($upgradeRecords as $upgradeRecord) {
            if ($upgradeRecord['end_date'] >= $earlyCutOff && $upgradeRecord['end_date'] <= $lateCutOff) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $data
     * @param User $user
     * @param \XF\Entity\UserUpgrade $userUpgrade
     * @param array $upgradeRecords
     * @return bool
     */
    protected function _matchEndDateNotWithin(
        array $data,
        User $user,
        \XF\Entity\UserUpgrade $userUpgrade,
        array $upgradeRecords
    ) {
        $earlyCutOff = \XF::$time - ($data['days'] * 86400);
        $lateCutOff = \XF::$time + ($data['days'] * 86400);

        foreach ($upgradeRecords as $upgradeRecord) {
            if ($upgradeRecord['end_date'] >= $earlyCutOff && $upgradeRecord['end_date'] <= $lateCutOff) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array $data
     * @param User $user
     * @param \XF\Entity\UserUpgrade $userUpgrade
     * @param array $upgradeRecords
     * @return bool
     */
    protected function _matchUpdatedWithin(array $data, User $user, \XF\Entity\UserUpgrade $userUpgrade, array $upgradeRecords)
    {
        $earlyCutOff = \XF::$time - ($data['days'] * 86400);
        $lateCutOff = \XF::$time + ($data['days'] * 86400);

        foreach ($upgradeRecords as $upgradeRecord) {
            if ($upgradeRecord['updated'] >= $earlyCutOff && $upgradeRecord['updated'] <= $lateCutOff) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $data
     * @param User $user
     * @param \XF\Entity\UserUpgrade $userUpgrade
     * @param array $upgradeRecords
     * @return bool
     */
    protected function _matchUpdatedNotWithin(array $data, User $user, \XF\Entity\UserUpgrade $userUpgrade, array $upgradeRecords)
    {
        $earlyCutOff = \XF::$time - ($data['days'] * 86400);
        $lateCutOff = \XF::$time + ($data['days'] * 86400);

        foreach ($upgradeRecords as $upgradeRecord) {
            if ($upgradeRecord['updated'] >= $earlyCutOff && $upgradeRecord['updated'] <= $lateCutOff) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array $data
     * @param User $user
     * @param \XF\Entity\UserUpgrade $userUpgrade
     * @param array $upgradeRecords
     * @return bool
     */
    protected function _matchUserUpgrades(
        array $data,
        User $user,
        \XF\Entity\UserUpgrade $userUpgrade,
        array $upgradeRecords
    ) {
        return in_array($userUpgrade->user_upgrade_id, $data['user_upgrade_ids']);
    }

    /**
     * @param array $data
     * @param User $user
     * @param \XF\Entity\UserUpgrade $userUpgrade
     * @param array $upgradeRecords
     * @return bool
     */
    protected function _matchNotUserUpgrades(
        array $data,
        User $user,
        \XF\Entity\UserUpgrade $userUpgrade,
        array $upgradeRecords
    ) {
        return !in_array($userUpgrade->user_upgrade_id, $data['user_upgrade_ids']);
    }
}
