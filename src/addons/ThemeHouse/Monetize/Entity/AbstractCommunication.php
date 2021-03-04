<?php

namespace ThemeHouse\Monetize\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * Class AbstractCommunication
 * @package ThemeHouse\Monetize\Entity
 *
 * @property mixed send_rules
 * @property bool active
 */
abstract class AbstractCommunication extends Entity
{
    /**
     * @param Structure $structure
     * @return Structure
     */
    public static function getStructure(Structure $structure)
    {
        return $structure;
    }

    /**
     * @param $criteria
     * @return bool
     */
    protected function verifyUserCriteria(&$criteria)
    {
        $userCriteria = $this->app()->criteria('XF:User', $criteria);
        $criteria = $userCriteria->getCriteria();
        return true;
    }

    /**
     * @param array $rules
     * @return bool
     */
    protected function verifySendRules(array &$rules)
    {
        $filterTypes = ['dom', 'dow', 'hours', 'minutes'];

        foreach ($filterTypes as $type) {
            if (!isset($rules[$type])) {
                continue;
            }

            $typeRules = $rules[$type];
            if (!is_array($typeRules)) {
                $typeRules = [];
            }

            $typeRules = array_map('intval', $typeRules);
            $typeRules = array_unique($typeRules);
            sort($typeRules, SORT_NUMERIC);

            $rules[$type] = $typeRules;
        }

        return true;
    }

    /**
     *
     */
    protected function _preSave()
    {
        if ($this->active) {
            if (!is_array($this->send_rules)) {
                $this->send_rules = [];
            }

            $this->set('next_send', $this->calculateNextSend());
        } else {
            $this->set('next_send', 0x7FFFFFFF); // waay in future
        }
    }

    /**
     * @return int
     */
    public function calculateNextSend()
    {
        /** @var \ThemeHouse\Monetize\Service\CalculateNextSend $service */
        $service = $this->app()->service('ThemeHouse\Monetize:CalculateNextSend');
        return $service->calculateNextSendTime($this->send_rules);
    }

    /**
     *
     */
    protected function _setupDefaults()
    {
        $this->send_rules = [
            'day_type' => 'dom',
            'dom' => ['-1']
        ];
    }
}
