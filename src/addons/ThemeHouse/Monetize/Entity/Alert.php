<?php

namespace ThemeHouse\Monetize\Entity;


use XF\Entity\User;
use XF\Language;
use XF\Mvc\Entity\ArrayCollection;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int alert_id
 * @property array send_rules
 * @property string title
 * @property int user_id
 * @property string link_url
 * @property string link_title
 * @property string body
 * @property bool active
 * @property array user_criteria
 * @property int next_send
 * @property integer limit_alerts
 * @property integer limit_days
 * @property array user_upgrade_criteria
 *
 * RELATIONS
 * @property \ThemeHouse\Monetize\XF\Entity\User User
 */
class Alert extends AbstractCommunication
{
    /**
     * @param Structure $structure
     * @return Structure
     */
    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_th_monetize_alert';
        $structure->shortName = 'ThemeHouse\Monetize:Alert';
        $structure->primaryKey = 'alert_id';
        $structure->columns = [
            'alert_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
            'send_rules' => ['type' => self::JSON_ARRAY, 'required' => true],
            'title' => [
                'type' => self::STR,
                'maxLength' => 150,
                'required' => 'please_enter_valid_title'
            ],
            'user_id' => ['type' => self::UINT, 'default' => 0],
            'link_url' => ['type' => self::STR, 'default' => ''],
            'link_title' => ['type' => self::STR, 'default' => ''],
            'body' => ['type' => self::STR, 'default' => ''],
            'active' => ['type' => self::BOOL, 'default' => true],
            'user_upgrade_criteria' => ['type' => self::JSON_ARRAY, 'default' => []],
            'user_criteria' => ['type' => self::JSON_ARRAY, 'default' => []],
            'next_send' => ['type' => self::UINT, 'default' => 0],
            'limit_alerts' => ['type' => self::UINT, 'default' => 0],
            'limit_days' => ['type' => self::UINT, 'default' => 0],
        ];
        $structure->relations = [
            'User' => [
                'entity' => 'XF:User',
                'type' => self::TO_ONE,
                'conditions' => 'user_id',
            ],
        ];

        return $structure;
    }

    /**
     * @param User $user
     * @param ArrayCollection $userUpgrades
     * @throws \XF\PrintableException
     */
    public function sendAlertForUser(User $user, ArrayCollection $userUpgrades)
    {
        if (!$this->active) {
            return;
        }

        if (!$this->canUserReceiveAlert($user, $userUpgrades)) {
            return;
        }

        $replacements = [];
        $body = $this->prepareAlert($replacements);

        $language = \XF::app()->language($user->language_id);
        $body = $this->replacePhrases($body, $language);

        $replacements = array_merge($replacements, [
            '{name}' => htmlspecialchars($user->username),
            '{id}' => $user->user_id
        ]);
        $alert = [
            'alert_text' => strtr($body, $replacements),
        ];

        /** @var \XF\Repository\UserAlert $alertRepo */
        $alertRepo = $this->repository('XF:UserAlert');
        $alertRepo->alert(
            $user,
            $this->User ? $this->User->user_id : 0,
            $this->User ? $this->User->username : '',
            'user',
            $user->user_id,
            'thmonetize_alert',
            $alert
        );

        /** @var AlertLog $log */
        $log = \XF::em()->create('ThemeHouse\Monetize:AlertLog');
        $log->alert_id = $this->alert_id;
        $log->user_id = $user->user_id;
        $log->save();
    }

    /**
     * @param User $user
     * @param ArrayCollection $userUpgrades
     * @return bool
     */
    public function canUserReceiveAlert(User $user, ArrayCollection $userUpgrades)
    {
        if (!\XF::app()->criteria('XF:User', $this->user_criteria)->isMatched($user)) {
            return false;
        }

        $userUpgradeCriteria = \XF::app()->criteria(
            'ThemeHouse\Monetize:UserUpgrade',
            $this->user_upgrade_criteria,
            $userUpgrades
        );

        if (!$userUpgradeCriteria->isMatched($user)) {
            return false;
        }

        $totalPerUser = \XF::options()->thmonetize_maxTotalAlertsPerUser;
        $perUser = \XF::options()->thmonetize_maxAlertsPerUser;

        $maxAlerts = max($totalPerUser['alerts'], $perUser['alerts'], $this->limit_alerts);
        $maxDays = max($totalPerUser['days'], $perUser['days'], $this->limit_days);

        $cutOff = $maxDays ? \XF::$time - 86400 * $maxDays : 0;

        if (!$cutOff || !$maxAlerts) {
            return true;
        }

        $alertLogFinder = $this->finder('ThemeHouse\Monetize:AlertLog');
        $totalAlerts = $alertLogFinder->where('user_id', $user->user_id)
            ->where('log_date', '>=', $cutOff)
            ->order($alertLogFinder->expression('FIELD(alert_id, ' . $this->alert_id . ')'), 'desc')
            ->limit($maxAlerts)
            ->fetch();

        if ($this->isLimitReached($totalAlerts, $totalPerUser['alerts'], $totalPerUser['days'])) {
            return false;
        }

        $alertId = $this->alert_id;
        $alerts = $totalAlerts->filter(
            function (\ThemeHouse\Monetize\Entity\AlertLog $log) use ($alertId) {
                return $log->alert_id === $alertId;
            }
        );

        if ($this->isLimitReached($alerts, $perUser['alerts'], $perUser['days'])) {
            return false;
        }

        if ($this->isLimitReached($alerts, $this->limit_alerts, $this->limit_days)) {
            return false;
        }

        return true;
    }

    /**
     * @param ArrayCollection $alerts
     * @param $limitAlerts
     * @param $limitDays
     * @return bool
     */
    protected function isLimitReached(ArrayCollection $alerts, $limitAlerts, $limitDays)
    {
        if ($limitAlerts && $limitDays) {
            $cutOff = \XF::$time - 86400 * $limitDays;
            $totalAlerts = $alerts->filter(
                function (AlertLog $log) use ($cutOff) {
                    return ($log->log_date >= $cutOff);
                }
            );
            if ($totalAlerts->count() >= $limitAlerts) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $replacements
     * @return string
     */
    protected function prepareAlert(&$replacements = [])
    {
        $body = $this->body;

        if ($this->link_url) {
            $link = '<a href="' . $this->link_url . '" class="fauxBlockLink-blockLink">'
                . ($this->link_title ? $this->link_title : $this->link_url)
                . '</a>';
            $replacements['{link}'] = $link;

            if (strpos($body, '{link}') === false) {
                $body .= ' {link}';
            }
        }
        return $body;
    }

    /**
     * @param $string
     * @param Language $language
     * @return string
     */
    protected function replacePhrases($string, Language $language)
    {
        return \XF::app()->stringFormatter()->replacePhrasePlaceholders($string, $language);
    }

    /**
     * @return \ThemeHouse\Monetize\Repository\Alert
     */
    protected function getAlertRepo()
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->repository('ThemeHouse\Monetize:Alert');
    }
}
