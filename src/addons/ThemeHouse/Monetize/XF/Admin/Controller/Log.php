<?php

namespace ThemeHouse\Monetize\XF\Admin\Controller;

use XF\Mvc\ParameterBag;

/**
 * Class Log
 * @package ThemeHouse\Monetize\XF\Admin\Controller
 */
class Log extends XFCP_Log
{
    /**
     * @param ParameterBag $params
     * @return \XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
     * @throws \XF\Mvc\Reply\Exception
     */
    public function actionMonetizeAlert(ParameterBag $params)
    {
        if ($params['alert_log_id']) {
            $entry = $this->assertThMonetizeAlertLogExists(
                $params['alert_log_id'],
                ['Alert', 'User'],
                'requested_log_entry_not_found'
            );

            $viewParams = [
                'entry' => $entry
            ];
            return $this->view('ThemeHouse\Monetize:Log\Alerts\View', 'thmonetize_log_alert_view', $viewParams);
        } else {
            $page = $this->filterPage();
            $perPage = 20;

            /** @var \ThemeHouse\Monetize\Repository\AlertLog $alertLogRepo */
            $alertLogRepo = $this->repository('ThemeHouse\Monetize:AlertLog');

            $logFinder = $alertLogRepo->findLogsForList()
                ->limitByPage($page, $perPage);

            $linkFilters = [];
            if ($userId = $this->filter('user_id', 'uint')) {
                $linkFilters['user_id'] = $userId;
                $logFinder->where('user_id', $userId);
            }

            if ($this->isPost()) {
                // redirect to give a linkable page
                return $this->redirect($this->buildLink('logs/monetize-alerts', null, $linkFilters));
            }

            $viewParams = [
                'entries' => $logFinder->fetch(),
                'logUsers' => $alertLogRepo->getUsersInLog(),

                'userId' => $userId,

                'page' => $page,
                'perPage' => $perPage,
                'total' => $logFinder->total(),
                'linkFilters' => $linkFilters
            ];
            return $this->view('ThemeHouse\Monetize:Log\Alert\Listing', 'thmonetize_log_alert_list', $viewParams);
        }
    }

    /**
     * @param string $id
     * @param array|string|null $with
     * @param null|string $phraseKey
     *
     * @return \ThemeHouse\Monetize\Entity\AlertLog
     * @throws \XF\Mvc\Reply\Exception
     */
    protected function assertThMonetizeAlertLogExists($id, $with = null, $phraseKey = null)
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->assertRecordExists('ThemeHouse\Monetize:AlertLog', $id, $with, $phraseKey);
    }

    /**
     * @param ParameterBag $params
     * @return \XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
     * @throws \XF\Mvc\Reply\Exception
     */
    public function actionMonetizeEmail(ParameterBag $params)
    {
        if ($params['email_log_id']) {
            $entry = $this->assertThMonetizeEmailLogExists(
                $params['email_log_id'],
                ['Email', 'User'],
                'requested_log_entry_not_found'
            );

            $viewParams = [
                'entry' => $entry
            ];
            return $this->view('ThemeHouse\Monetize:Log\Emails\View', 'thmonetize_log_email_view', $viewParams);
        } else {
            $page = $this->filterPage();
            $perPage = 20;

            /** @var \ThemeHouse\Monetize\Repository\EmailLog $emailLogRepo */
            $emailLogRepo = $this->repository('ThemeHouse\Monetize:EmailLog');

            $logFinder = $emailLogRepo->findLogsForList()
                ->limitByPage($page, $perPage);

            $linkFilters = [];
            if ($userId = $this->filter('user_id', 'uint')) {
                $linkFilters['user_id'] = $userId;
                $logFinder->where('user_id', $userId);
            }

            if ($this->isPost()) {
                // redirect to give a linkable page
                return $this->redirect($this->buildLink('logs/monetize-emails', null, $linkFilters));
            }

            $viewParams = [
                'entries' => $logFinder->fetch(),
                'logUsers' => $emailLogRepo->getUsersInLog(),

                'userId' => $userId,

                'page' => $page,
                'perPage' => $perPage,
                'total' => $logFinder->total(),
                'linkFilters' => $linkFilters
            ];
            return $this->view('ThemeHouse\Monetize:Log\Email\Listing', 'thmonetize_log_email_list', $viewParams);
        }
    }

    /**
     * @param string $id
     * @param array|string|null $with
     * @param null|string $phraseKey
     *
     * @return \ThemeHouse\Monetize\Entity\EmailLog
     * @throws \XF\Mvc\Reply\Exception
     */
    protected function assertThMonetizeEmailLogExists($id, $with = null, $phraseKey = null)
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->assertRecordExists('ThemeHouse\Monetize:EmailLog', $id, $with, $phraseKey);
    }

    /**
     * @param ParameterBag $params
     * @return \XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
     * @throws \XF\Mvc\Reply\Exception
     */
    public function actionMonetizeMessage(ParameterBag $params)
    {
        if ($params['message_log_id']) {
            $entry = $this->assertThMonetizeMessageLogExists(
                $params['message_log_id'],
                ['Message', 'User'],
                'requested_log_entry_not_found'
            );

            $viewParams = [
                'entry' => $entry
            ];
            return $this->view('ThemeHouse\Monetize:Log\Messages\View', 'thmonetize_log_message_view', $viewParams);
        } else {
            $page = $this->filterPage();
            $perPage = 20;

            /** @var \ThemeHouse\Monetize\Repository\MessageLog $messageLogRepo */
            $messageLogRepo = $this->repository('ThemeHouse\Monetize:MessageLog');

            $logFinder = $messageLogRepo->findLogsForList()
                ->limitByPage($page, $perPage);

            $linkFilters = [];
            if ($userId = $this->filter('user_id', 'uint')) {
                $linkFilters['user_id'] = $userId;
                $logFinder->where('user_id', $userId);
            }

            if ($this->isPost()) {
                // redirect to give a linkable page
                return $this->redirect($this->buildLink('logs/monetize-messages', null, $linkFilters));
            }

            $viewParams = [
                'entries' => $logFinder->fetch(),
                'logUsers' => $messageLogRepo->getUsersInLog(),

                'userId' => $userId,

                'page' => $page,
                'perPage' => $perPage,
                'total' => $logFinder->total(),
                'linkFilters' => $linkFilters
            ];
            return $this->view('ThemeHouse\Monetize:Log\Message\Listing', 'thmonetize_log_message_list', $viewParams);
        }
    }

    /**
     * @param string $id
     * @param array|string|null $with
     * @param null|string $phraseKey
     *
     * @return \ThemeHouse\Monetize\Entity\MessageLog
     * @throws \XF\Mvc\Reply\Exception
     */
    protected function assertThMonetizeMessageLogExists($id, $with = null, $phraseKey = null)
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->assertRecordExists('ThemeHouse\Monetize:MessageLog', $id, $with, $phraseKey);
    }
}
