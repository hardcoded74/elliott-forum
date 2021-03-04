<?php

namespace ThemeHouse\Monetize\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null alert_log_id
 * @property int log_date
 * @property int user_id
 * @property int alert_id
 *
 * RELATIONS
 * @property \XF\Entity\User User
 * @property \ThemeHouse\Monetize\Entity\Alert Alert
 */
class AlertLog extends Entity
{
    /**
     * @param Structure $structure
     * @return Structure
     */
    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_th_monetize_alert_log';
        $structure->shortName = 'ThemeHouse/Monetize:AlertLog';
        $structure->primaryKey = 'alert_log_id';
        $structure->columns = [
            'alert_log_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
            'log_date' => ['type' => self::UINT, 'default' => \XF::$time],
            'user_id' => ['type' => self::UINT, 'required' => true],
            'alert_id' => ['type' => self::UINT, 'required' => true],
        ];
        $structure->relations = [
            'User' => [
                'entity' => 'XF:User',
                'type' => self::TO_ONE,
                'conditions' => 'user_id',
                'primary' => true
            ],
            'Alert' => [
                'entity' => 'ThemeHouse\Monetize:Alert',
                'type' => self::TO_ONE,
                'conditions' => 'alert_id',
                'primary' => true
            ],
        ];

        return $structure;
    }
}
