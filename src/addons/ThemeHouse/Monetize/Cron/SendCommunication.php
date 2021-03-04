<?php

namespace ThemeHouse\Monetize\Cron;

/**
 * Class SendCommunication
 * @package ThemeHouse\Monetize\Cron
 */
class SendCommunication
{
    /**
     *
     */
    public static function process()
    {
        /** @var \ThemeHouse\Monetize\Service\CalculateNextSend $service */
        $service = \XF::app()->service('ThemeHouse\Monetize:CalculateNextSend');

        $alerts = \XF::finder('ThemeHouse\Monetize:Alert')
            ->where('next_send', '<', \XF::$time)
            ->where('active', true)
            ->order(['next_send'])
            ->fetch();

        foreach ($alerts as $alert) {
            if ($service->updateSendTimeAtomic($alert)) {
                \XF::app()->jobManager()->enqueueUnique(
                    'ThMonetize_SendAlert_' . $alert->alert_id,
                    'ThemeHouse\Monetize:SendAlert',
                    ['alert_id' => $alert->alert_id],
                    false
                );
            }
        }

        $emails = \XF::finder('ThemeHouse\Monetize:Email')
            ->where('next_send', '<', \XF::$time)
            ->where('active', true)
            ->order(['next_send'])
            ->fetch();

        foreach ($emails as $email) {
            if ($service->updateSendTimeAtomic($email)) {
                \XF::app()->jobManager()->enqueueUnique(
                    'ThMonetize_SendEmail_' . $email->email_id,
                    'ThemeHouse\Monetize:SendEmail',
                    ['email_id' => $email->email_id],
                    false
                );
            }
        }

        $messages = \XF::finder('ThemeHouse\Monetize:Message')
            ->where('next_send', '<', \XF::$time)
            ->where('active', true)
            ->order(['next_send'])
            ->fetch();

        foreach ($messages as $message) {
            if ($service->updateSendTimeAtomic($message)) {
                \XF::app()->jobManager()->enqueueUnique(
                    'ThMonetize_SendMessage_' . $message->message_id,
                    'ThemeHouse\Monetize:SendMessage',
                    ['message_id' => $message->message_id],
                    false
                );
            }
        }
    }
}
