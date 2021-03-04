<?php

namespace ThemeHouse\Monetize\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null email_log_id
 * @property int log_date
 * @property int user_id
 * @property int email_id
 *
 * RELATIONS
 * @property \XF\Entity\User User
 * @property \ThemeHouse\Monetize\Entity\Email Email
 */
class EmailLog extends Entity
{
    /**
     * @param Structure $structure
     * @return Structure
     */
    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_th_monetize_email_log';
        $structure->shortName = 'ThemeHouse/Monetize:EmailLog';
        $structure->primaryKey = 'email_log_id';
        $structure->columns = [
            'email_log_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
            'log_date' => ['type' => self::UINT, 'default' => \XF::$time],
            'user_id' => ['type' => self::UINT, 'required' => true],
            'email_id' => ['type' => self::UINT, 'required' => true],
        ];
        $structure->relations = [
            'User' => [
                'entity' => 'XF:User',
                'type' => self::TO_ONE,
                'conditions' => 'user_id',
                'primary' => true
            ],
            'Email' => [
                'entity' => 'ThemeHouse\Monetize:Email',
                'type' => self::TO_ONE,
                'conditions' => 'email_id',
                'primary' => true
            ],
        ];

        return $structure;
    }
}
