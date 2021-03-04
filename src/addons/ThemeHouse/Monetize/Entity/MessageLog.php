<?php

namespace ThemeHouse\Monetize\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null message_log_id
 * @property int log_date
 * @property int user_id
 * @property int message_id
 *
 * RELATIONS
 * @property \XF\Entity\User User
 * @property \ThemeHouse\Monetize\Entity\Message Message
 */
class MessageLog extends Entity
{
    /**
     * @param Structure $structure
     * @return Structure
     */
    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_th_monetize_message_log';
        $structure->shortName = 'ThemeHouse/Monetize:MessageLog';
        $structure->primaryKey = 'message_log_id';
        $structure->columns = [
            'message_log_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
            'log_date' => ['type' => self::UINT, 'default' => \XF::$time],
            'user_id' => ['type' => self::UINT, 'required' => true],
            'message_id' => ['type' => self::UINT, 'required' => true],
        ];
        $structure->relations = [
            'User' => [
                'entity' => 'XF:User',
                'type' => self::TO_ONE,
                'conditions' => 'user_id',
                'primary' => true
            ],
            'Message' => [
                'entity' => 'ThemeHouse\Monetize:Message',
                'type' => self::TO_ONE,
                'conditions' => 'message_id',
                'primary' => true
            ],
        ];

        return $structure;
    }
}
