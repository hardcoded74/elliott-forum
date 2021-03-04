<?php

namespace ThemeHouse\Monetize\Service;

use ThemeHouse\Monetize\Entity\AbstractCommunication;
use XF\Mvc\Entity\Structure;
use XF\Service\AbstractService;

/**
 * Class CalculateNextSend
 * @package ThemeHouse\Monetize\Service
 */
class CalculateNextSend extends AbstractService
{
    /**
     * Atomically update the next send time for a communication. This allows you
     * to determine whether a communication still needs to be sent.
     *
     * @param AbstractCommunication $entity Communication info
     *
     * @return boolean True if updated (thus safe to run), false otherwise
     */
    public function updateSendTimeAtomic(AbstractCommunication $entity)
    {
        $sendRules = $entity['send_rules'];
        $nextSend = $this->calculateNextSendTime($sendRules);

        $structure = $entity::getStructure(new Structure());

        $updateResult = $this->db()->update(
            $structure->table,
            ['next_send' => $nextSend],
            $structure->primaryKey . ' = ? AND next_send = ?',
            [$entity[$structure->primaryKey], $entity['next_send']]
        );

        return (bool)$updateResult;
    }

    /**
     * Calculate the next run time for an entry using the given rules. Rules expected in keys:
     * dow, dom (all arrays) and day_type (string: dow or dom)
     * Array rules are in format: -1 means "any", any other value means on those specific
     * occurances. DoW runs 0 (Sunday) to 6 (Saturday).
     *
     * @param array $sendRules Send rules. See above for format.
     * @param integer|null $currentTime Current timestamp; null to use current time from application
     *
     * @return integer Next run timestamp
     */
    public function calculateNextSendTime(array $sendRules, $currentTime = null)
    {
        $currentTime = ($currentTime === null ? \XF::$time : $currentTime);

        $nextSend = new \DateTime('@' . $currentTime);

        $currentHour = $nextSend->format('G');
        $currentMinute = $nextSend->format('i');

        $nextSend->modify('+1 minute');

        if (empty($sendRules['minutes'])) {
            $sendRules['minutes'] = [$currentMinute];
        }
        $this->modifyRunTimeMinutes($sendRules['minutes'], $nextSend);

        if (empty($sendRules['hours'])) {
            $sendRules['hours'] = [$currentHour];
        }
        $this->modifyRunTimeHours($sendRules['hours'], $nextSend);

        if (!empty($sendRules['day_type'])) {
            if ($sendRules['day_type'] == 'dow') {
                if (empty($sendRules['dow'])) {
                    $sendRules['dow'] = [-1];
                }
                $this->modifyRunTimeDayOfWeek($sendRules['dow'], $nextSend);
            } else {
                if (empty($sendRules['dom'])) {
                    $sendRules['dom'] = [-1];
                }
                $this->modifyRunTimeDayOfMonth($sendRules['dom'], $nextSend);
            }
        }

        return intval($nextSend->format('U'));
    }

    /**
     * Modifies the next send time based on the minute rules.
     *
     * @param array $minuteRules Rules about what minutes are valid (-1, or any number of values 0-59)
     * @param \DateTime $nextSend Date calculation object. This will be modified.
     */
    protected function modifyRunTimeMinutes(array $minuteRules, \DateTime &$nextSend)
    {
        $currentMinute = $nextSend->format('i');
        $this->modifyRunTimeUnits($minuteRules, $nextSend, $currentMinute, 'minute', 'hour');
    }

    /**
     * Modifies the next send time based on the hour rules.
     *
     * @param array $hourRules Rules about what hours are valid (-1, or any number of values 0-23)
     * @param \DateTime $nextSend Date calculation object. This will be modified.
     */
    protected function modifyRunTimeHours(array $hourRules, \DateTime &$nextSend)
    {
        $currentHour = $nextSend->format('G');
        $this->modifyRunTimeUnits($hourRules, $nextSend, $currentHour, 'hour', 'day');
    }

    /**
     * Modifies the next send time based on the day of month rules. Note that if
     * the required DoM doesn't exist (eg, Feb 30), it will be rolled over as if
     * it did (eg, to Mar 2).
     *
     * @param array $hourRules Rules about what days are valid (-1, or any number of values 0-31)
     * @param \DateTime $nextSend Date calculation object. This will be modified.
     */
    protected function modifyRunTimeDayOfMonth(array $dayRules, \DateTime &$nextSend)
    {
        $currentDay = $nextSend->format('j');
        $this->modifyRunTimeUnits($dayRules, $nextSend, $currentDay, 'day', 'month');
    }

    /**
     * Modifies the next send time based on the day of week rules.
     *
     * @param array $dayRules
     * @param \DateTime $nextSend Date calculation object. This will be modified.
     */
    protected function modifyRunTimeDayOfWeek(array $dayRules, \DateTime &$nextSend)
    {
        $currentDay = $nextSend->format('w'); // 0 = sunday, 6 = saturday
        $this->modifyRunTimeUnits($dayRules, $nextSend, $currentDay, 'day', 'week');
    }

    /**
     * General purpose send time calculator for a set of rules.
     *
     * @param array $unitRules List of rules for unit. Array of ints, values -1 to unit-defined max.
     * @param \DateTime $nextSend Date calculation object. This will be modified.
     * @param integer $currentUnitValue The current value for the specified unit type
     * @param string $unitName Name of the current unit (eg, minute, hour, day, etc)
     * @param string $rolloverUnitName Name of the unit to use when rolling over; one unit bigger (eg, minutes to hours)
     */
    protected function modifyRunTimeUnits(
        array $unitRules,
        \DateTime &$nextSend,
        $currentUnitValue,
        $unitName,
        $rolloverUnitName
    ) {
        if (sizeof($unitRules) && reset($unitRules) == -1) {
            // correct already
            return;
        }

        $currentUnitValue = intval($currentUnitValue);
        $rollover = null;

        sort($unitRules, SORT_NUMERIC);
        foreach ($unitRules as $unitValue) {
            if ($unitValue == -1 || $unitValue == $currentUnitValue) {
                // already in correct position
                $rollover = null;
                break;
            } elseif ($unitValue > $currentUnitValue) {
                // found unit later in date, adjust to time
                $nextSend->modify('+ ' . ($unitValue - $currentUnitValue) . " $unitName");
                $rollover = null;
                break;
            } elseif ($rollover === null) {
                // found unit earlier in the date; use smallest value
                $rollover = $unitValue;
            }
        }

        if ($rollover !== null) {
            $nextSend->modify(($rollover - $currentUnitValue) . " $unitName");
            $nextSend->modify("+ 1 $rolloverUnitName");
        }
    }
}
