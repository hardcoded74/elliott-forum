<?php

namespace ThemeHouse\Monetize\Entity;


use XF\Entity\User;
use XF\Language;
use XF\Mvc\Entity\ArrayCollection;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int message_id
 * @property array send_rules
 * @property string title
 * @property int user_id
 * @property string body
 * @property bool active
 * @property bool open_invite
 * @property bool conversation_locked
 * @property string delete_type
 * @property array user_criteria
 * @property array user_upgrade_criteria
 * @property int next_send
 * @property integer limit_messages
 * @property integer limit_days
 *
 * RELATIONS
 * @property \ThemeHouse\Monetize\XF\Entity\User User
 */
class Message extends AbstractCommunication
{
    /**
     * @param Structure $structure
     * @return Structure
     */
    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_th_monetize_message';
        $structure->shortName = 'ThemeHouse\Monetize:Message';
        $structure->primaryKey = 'message_id';
        $structure->columns = [
            'message_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
            'send_rules' => ['type' => self::JSON_ARRAY, 'required' => true],
            'title' => [
                'type' => self::STR,
                'maxLength' => 150,
                'required' => 'please_enter_valid_title'
            ],
            'user_id' => [
                'type' => self::UINT,
                'required' => 'thmonetize_please_enter_valid_user',
            ],
            'body' => ['type' => self::STR, 'default' => ''],
            'active' => ['type' => self::BOOL, 'default' => true],
            'open_invite' => ['type' => self::BOOL, 'default' => true],
            'conversation_locked' => ['type' => self::BOOL, 'default' => true],
            'delete_type' => [
                'type' => self::STR,
                'default' => '',
                'allowedValues' => ['', 'deleted', 'deleted_ignored']
            ],
            'user_upgrade_criteria' => ['type' => self::JSON_ARRAY, 'default' => []],
            'user_criteria' => ['type' => self::JSON_ARRAY, 'default' => []],
            'next_send' => ['type' => self::UINT, 'default' => 0],
            'limit_messages' => ['type' => self::UINT, 'default' => 0],
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
    public function sendMessageForUser(User $user, ArrayCollection $userUpgrades)
    {
        if (!$this->active) {
            return;
        }

        if (!$this->canUserReceiveMessage($user, $userUpgrades)) {
            return;
        }

        $language = \XF::app()->language($user->language_id);
        $title = $this->replacePhrases($this->title, $language);
        $body = $this->replacePhrases($this->body, $language);

        $tokens = $this->prepareTokens($user);
        $title = strtr($title, $tokens);
        $body = strtr($body, $tokens);

        /** @var \XF\Service\Conversation\Creator $creator */
        $creator = \XF::app()->service('XF:Conversation\Creator', $this->User);
        $creator->setIsAutomated();
        $creator->setOptions([
            'open_invite' => $this->open_invite,
            'conversation_open' => !$this->conversation_locked,
        ]);
        $creator->setRecipientsTrusted($user);
        $creator->setContent($title, $body);
        if (!$creator->validate()) {
            return;
        }

        $conversation = $creator->save();

        if ($this->delete_type) {
            /** @var \XF\Entity\ConversationRecipient $recipient */
            $recipient = $conversation->Recipients[$this->User->user_id];
            $recipient->recipient_state = $this->delete_type;
            $recipient->save(false);
        }

        /** @var MessageLog $log */
        $log = \XF::em()->create('ThemeHouse\Monetize:MessageLog');
        $log->message_id = $this->message_id;
        $log->user_id = $user->user_id;
        $log->save();
    }

    /**
     * @param User $user
     * @param ArrayCollection $userUpgrades
     * @return bool
     */
    public function canUserReceiveMessage(User $user, ArrayCollection $userUpgrades)
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

        $totalPerUser = \XF::options()->thmonetize_maxTotalMessagesPerUser;
        $perUser = \XF::options()->thmonetize_maxMessagesPerUser;

        $maxMessages = max($totalPerUser['messages'], $perUser['messages'], $this->limit_messages);
        $maxDays = max($totalPerUser['days'], $perUser['days'], $this->limit_days);

        $cutOff = $maxDays ? \XF::$time - 86400 * $maxDays : 0;

        if (!$cutOff || !$maxMessages) {
            return true;
        }

        $messageLogFinder = $this->finder('ThemeHouse\Monetize:MessageLog');
        $totalMessages = $messageLogFinder->where('user_id', $user->user_id)
            ->where('log_date', '>=', $cutOff)
            ->order($messageLogFinder->expression('FIELD(message_id, ' . $this->message_id . ')'), 'desc')
            ->limit($maxMessages)
            ->fetch();

        if ($this->isLimitReached($totalMessages, $totalPerUser['messages'], $totalPerUser['days'])) {
            return false;
        }

        $messageId = $this->message_id;
        $messages = $totalMessages->filter(
            function (\ThemeHouse\Monetize\Entity\MessageLog $log) use ($messageId) {
                return $log->message_id === $messageId;
            }
        );

        if ($this->isLimitReached($messages, $perUser['messages'], $perUser['days'])) {
            return false;
        }

        if ($this->isLimitReached($messages, $this->limit_messages, $this->limit_days)) {
            return false;
        }

        return true;
    }

    /**
     * @param ArrayCollection $messages
     * @param $limitMessages
     * @param $limitDays
     * @return bool
     */
    protected function isLimitReached(ArrayCollection $messages, $limitMessages, $limitDays)
    {
        if ($limitMessages && $limitDays) {
            $cutOff = \XF::$time - 86400 * $limitDays;
            $totalMessages = $messages->filter(
                function (MessageLog $log) use ($cutOff) {
                    return ($log->log_date >= $cutOff);
                }
            );
            if ($totalMessages->count() >= $limitMessages) {
                return true;
            }
        }

        return false;
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
     * @param User $user
     * @return array
     */
    protected function prepareTokens(User $user)
    {
        return [
            '{name}' => $user->username,
            '{email}' => $user->email,
            '{id}' => $user->user_id
        ];
    }

    /**
     * @return \ThemeHouse\Monetize\Repository\Message
     */
    protected function getMessageRepo()
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->repository('ThemeHouse\Monetize:Message');
    }
}
