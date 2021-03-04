<?php

namespace ThemeHouse\Monetize\Entity;


use XF\Entity\User;
use XF\Language;
use XF\Mvc\Entity\ArrayCollection;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int email_id
 * @property array send_rules
 * @property string from_name
 * @property string from_email
 * @property string title
 * @property string format
 * @property string body
 * @property bool active
 * @property bool wrapped
 * @property bool unsub
 * @property bool receive_admin_email_only
 * @property array user_criteria
 * @property int next_send
 * @property integer limit_days
 * @property integer limit_emails
 * @property array user_upgrade_criteria
 */
class Email extends AbstractCommunication
{
    /**
     * @param Structure $structure
     * @return Structure
     */
    public static function getStructure(Structure $structure)
    {
        $options = \XF::options();

        $structure->table = 'xf_th_monetize_email';
        $structure->shortName = 'ThemeHouse\Monetize:Email';
        $structure->primaryKey = 'email_id';
        $structure->columns = [
            'email_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
            'send_rules' => ['type' => self::JSON_ARRAY, 'required' => true],
            'from_name' => ['type' => self::STR, 'default' => $options->emailSenderName ?: $options->boardTitle],
            'from_email' => ['type' => self::STR, 'default' => $options->defaultEmailAddress],
            'title' => [
                'type' => self::STR,
                'maxLength' => 150,
                'required' => 'please_enter_valid_title'
            ],
            'format' => [
                'type' => self::STR,
                'default' => '',
                'allowedValues' => ['', 'html']
            ],
            'body' => ['type' => self::STR],
            'active' => ['type' => self::BOOL, 'default' => true],
            'wrapped' => ['type' => self::BOOL, 'default' => true],
            'unsub' => ['type' => self::BOOL, 'default' => true],
            'receive_admin_email_only' => ['type' => self::BOOL, 'default' => true],
            'user_upgrade_criteria' => ['type' => self::JSON_ARRAY, 'default' => []],
            'user_criteria' => ['type' => self::JSON_ARRAY, 'default' => []],
            'next_send' => ['type' => self::UINT, 'default' => 0],
            'limit_emails' => ['type' => self::UINT, 'default' => 0],
            'limit_days' => ['type' => self::UINT, 'default' => 0],
        ];

        return $structure;
    }

    /**
     * @param User $user
     * @param ArrayCollection $userUpgrades
     * @throws \XF\PrintableException
     */
    public function sendEmailForUser(User $user, ArrayCollection $userUpgrades)
    {
        if (!$this->active || !$user->email) {
            return;
        }

        if (!$this->canUserReceiveEmail($user, $userUpgrades)) {
            return;
        }

        $language = \XF::app()->language($user->language_id);

        $body = $this->replacePhrases($this->body, $language);
        $title = $this->replacePhrases($this->title, $language);

        if ($this->format === 'html') {
            if ($this->unsub) {
                $body .= "\n\n<div class=\"minorText\" align=\"center\"><a href=\"{unsub}\">"
                    . $language->renderPhrase('unsubscribe_from_mailing_list')
                    . '</a></div>';
            }

            $tokens = $this->prepareTokens($user, true);
            $html = strtr($body, $tokens);
            $text = \XF::app()->mailer()->generateTextBody($html);
        } else {
            if ($this->unsub) {
                $body .= "\n\n"
                    . $language->renderPhrase('unsubscribe_from_mailing_list:')
                    . ' {unsub}';
            }

            $tokens = $this->prepareTokens($user, false);
            $text = strtr($body, $tokens);
            $html = null;
        }

        $titleTokens = $this->prepareTokens($user, false);
        $title = strtr($title, $titleTokens);

        $mail = $this->getMail($user)->setFrom($this->from_email, $this->from_name);
        $mail->setTemplate('prepared_email', [
            'title' => $title,
            'htmlBody' => $html,
            'textBody' => $text,
            'raw' => $this->wrapped ? false : true
        ]);
        $mail->send();

        /** @var EmailLog $log */
        $log = \XF::em()->create('ThemeHouse\Monetize:EmailLog');
        $log->email_id = $this->email_id;
        $log->user_id = $user->user_id;
        $log->save();
    }

    /**
     * @param User $user
     * @param ArrayCollection $userUpgrades
     * @return bool
     */
    public function canUserReceiveEmail(User $user, ArrayCollection $userUpgrades)
    {
        if ($this->receive_admin_email_only && (!$user->Option || !$user->Option->receive_admin_email)) {
            return false;
        }

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

        $totalPerUser = \XF::options()->thmonetize_maxTotalEmailsPerUser;
        $perUser = \XF::options()->thmonetize_maxEmailsPerUser;

        $maxEmails = max($totalPerUser['emails'], $perUser['emails'], $this->limit_emails);
        $maxDays = max($totalPerUser['days'], $perUser['days'], $this->limit_days);

        $cutOff = $maxDays ? \XF::$time - 86400 * $maxDays : 0;

        if (!$cutOff || !$maxEmails) {
            return true;
        }

        $emailLogFinder = $this->finder('ThemeHouse\Monetize:EmailLog');
        $totalEmails = $emailLogFinder->where('user_id', $user->user_id)
            ->where('log_date', '>=', $cutOff)
            ->order($emailLogFinder->expression('FIELD(email_id, ' . $this->email_id . ')'), 'desc')
            ->limit($maxEmails)
            ->fetch();

        if ($this->isLimitReached($totalEmails, $totalPerUser['emails'], $totalPerUser['days'])) {
            return false;
        }

        $emailId = $this->email_id;
        $emails = $totalEmails->filter(
            function (\ThemeHouse\Monetize\Entity\EmailLog $log) use ($emailId) {
                return $log->email_id === $emailId;
            }
        );

        if ($this->isLimitReached($emails, $perUser['emails'], $perUser['days'])) {
            return false;
        }

        if ($this->isLimitReached($emails, $this->limit_emails, $this->limit_days)) {
            return false;
        }

        return true;
    }

    /**
     * @param ArrayCollection $emails
     * @param $limitEmails
     * @param $limitDays
     * @return bool
     */
    protected function isLimitReached(ArrayCollection $emails, $limitEmails, $limitDays)
    {
        if ($limitEmails && $limitDays) {
            $cutOff = \XF::$time - 86400 * $limitDays;
            $totalEmails = $emails->filter(
                function (EmailLog $log) use ($cutOff) {
                    return ($log->log_date >= $cutOff);
                }
            );
            if ($totalEmails->count() >= $limitEmails) {
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
     * @param $escape
     * @return array
     */
    protected function prepareTokens(User $user, $escape)
    {
        $unsubLink = \XF::app()->router('public')->buildLink('canonical:email-stop/mailing-list', $user);

        $tokens = [
            '{name}' => $user->username,
            '{email}' => $user->email,
            '{id}' => $user->user_id,
            '{unsub}' => $unsubLink
        ];

        if ($escape) {
            array_walk($tokens, function (&$value) {
                if (is_string($value)) {
                    $value = htmlspecialchars($value);
                }
            });
        }

        return $tokens;
    }

    /**
     * @param User $user
     *
     * @return \XF\Mail\Mail
     */
    protected function getMail(User $user)
    {
        $mailer = \XF::app()->mailer();
        $mail = $mailer->newMail();

        $options = \XF::app()->options();
        $unsubEmail = $options->unsubscribeEmailAddress;
        $useVerp = $options->enableVerp;

        $mail->setToUser($user);

        return $mail->setListUnsubscribe($unsubEmail, $useVerp);
    }

    /**
     * @return \ThemeHouse\Monetize\Repository\Email
     */
    protected function getEmailRepo()
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->repository('ThemeHouse\Monetize:Email');
    }
}
