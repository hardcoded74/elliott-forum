<?php

namespace ThemeHouse\Monetize\Admin\Controller;

use ThemeHouse\Monetize\XF\Entity\User;
use XF\Admin\Controller\AbstractController;
use XF\Mvc\ParameterBag;

/**
 * Class Alert
 * @package ThemeHouse\Monetize\Admin\Controller
 */
class Alert extends AbstractController
{
    /**
     * @param ParameterBag $params
     * @return \XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
     * @throws \XF\Mvc\Reply\Exception
     */
    public function actionIndex(ParameterBag $params)
    {
        if ($params['alert_id']) {
            $alert = $this->assertAlertExists($params['alert_id']);
            return $this->redirect($this->buildLink('monetize-alerts/edit', $alert));
        }

        $alertRepo = $this->getAlertRepo();
        $alerts = $alertRepo->findAlertsForList()->fetch();

        $viewParams = [
            'alerts' => $alerts,
            'totalAlerts' => $alerts->count()
        ];
        return $this->view('ThemeHouse\Monetize:Alerts\Listing', 'thmonetize_alert_list', $viewParams);
    }

    /**
     * @param string $id
     * @param array|string|null $with
     * @param null|string $phraseKey
     *
     * @return \ThemeHouse\Monetize\Entity\Alert
     * @throws \XF\Mvc\Reply\Exception
     */
    protected function assertAlertExists($id, $with = null, $phraseKey = null)
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->assertRecordExists('ThemeHouse\Monetize:Alert', $id, $with, $phraseKey);
    }

    /**
     * @return \ThemeHouse\Monetize\Repository\Alert
     */
    protected function getAlertRepo()
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->repository('ThemeHouse\Monetize:Alert');
    }

    /**
     * @param ParameterBag $params
     * @return \XF\Mvc\Reply\View
     * @throws \XF\Mvc\Reply\Exception
     */
    public function actionEdit(ParameterBag $params)
    {
        $alert = $this->assertAlertExists($params['alert_id']);
        return $this->alertAddEdit($alert);
    }

    /**
     * @param \ThemeHouse\Monetize\Entity\Alert $alert
     * @return \XF\Mvc\Reply\View
     */
    protected function alertAddEdit(\ThemeHouse\Monetize\Entity\Alert $alert)
    {
        $userUpgradeCriteria = $this->app->criteria('ThemeHouse\Monetize:UserUpgrade', $alert->user_upgrade_criteria);
        $userCriteria = $this->app->criteria('XF:User', $alert->user_criteria);

        $viewParams = [
            'alert' => $alert,
            'userUpgradeCriteria' => $userUpgradeCriteria,
            'userCriteria' => $userCriteria,
        ];
        return $this->view('ThemeHouse\Monetize:Alert\Edit', 'thmonetize_alert_edit', $viewParams);
    }

    /**
     * @return \XF\Mvc\Reply\View
     */
    public function actionAdd()
    {
        /** @var \ThemeHouse\Monetize\Entity\Alert $alert */
        $alert = $this->em()->create('ThemeHouse\Monetize:Alert');
        return $this->alertAddEdit($alert);
    }

    /**
     * @param ParameterBag $params
     * @return \XF\Mvc\Reply\Redirect
     * @throws \XF\Mvc\Reply\Exception
     * @throws \XF\PrintableException
     */
    public function actionSave(ParameterBag $params)
    {
        $this->assertPostOnly();

        if ($params['alert_id']) {
            $alert = $this->assertAlertExists($params['alert_id']);
        } else {
            $alert = $this->em()->create('ThemeHouse\Monetize:Alert');
        }

        $this->alertSaveProcess($alert)->run();

        return $this->redirect($this->buildLink('monetize-alerts') . $this->buildLinkHash($alert->alert_id));
    }

    /**
     * @param \ThemeHouse\Monetize\Entity\Alert $alert
     * @return \XF\Mvc\FormAction
     */
    protected function alertSaveProcess(\ThemeHouse\Monetize\Entity\Alert $alert)
    {
        $form = $this->formAction();

        $input = $this->filter([
            'send_rules' => 'array',
            'title' => 'str',
            'active' => 'bool',
            'link_url' => 'str',
            'link_title' => 'str',
            'body' => 'str',
            'user_upgrade_criteria' => 'array',
            'user_criteria' => 'array',
            'limit_alerts' => 'int',
            'limit_days' => 'int',
        ]);

        $username = $this->filter('username', 'str');
        /** @var User $user */
        $user = $this->em()->findOne('XF:User', ['username' => $username]);

        $input['user_id'] = $user ? $user->user_id : 0;

        $form->basicEntitySave($alert, $input);

        return $form;
    }

    /**
     * @param ParameterBag $params
     * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
     * @throws \XF\PrintableException
     * @throws \XF\Mvc\Reply\Exception
     */
    public function actionDelete(ParameterBag $params)
    {
        $alert = $this->assertAlertExists($params['alert_id']);
        if (!$alert->preDelete()) {
            return $this->error($alert->getErrors());
        }

        if ($this->isPost()) {
            $alert->delete();
            return $this->redirect($this->buildLink('monetize-alerts'));
        } else {
            $viewParams = [
                'alert' => $alert,
            ];
            return $this->view('ThemeHouse\Monetize:Alert\Delete', 'thmonetize_alert_delete', $viewParams);
        }
    }

    /**
     * @param ParameterBag $params
     * @return \XF\Mvc\Reply\Message|\XF\Mvc\Reply\View
     * @throws \XF\PrintableException
     * @throws \XF\Mvc\Reply\Exception
     */
    public function actionSend(ParameterBag $params)
    {
        $alert = $this->assertAlertExists($params['alert_id']);

        if ($this->isPost()) {
            if ($alert->active) {
                $alert->save();
            }
            \XF::app()->jobManager()->enqueueUnique(
                'ThMonetize_SendAlert_' . $alert->alert_id,
                'ThemeHouse\Monetize:SendAlert',
                ['alert_id' => $alert->alert_id],
                false
            );
            return $this->message(\XF::phrase('thmonetize_alert_sent_successfully'));
        } else {
            $viewParams = [
                'alert' => $alert
            ];
            return $this->view('ThemeHouse\Monetize:Alert\Send', 'thmonetize_alert_send', $viewParams);
        }
    }

    /**
     * @return \XF\Mvc\Reply\Message
     */
    public function actionToggle()
    {
        /** @var \XF\ControllerPlugin\Toggle $plugin */
        $plugin = $this->plugin('XF:Toggle');
        return $plugin->actionToggle('ThemeHouse\Monetize:Alert');
    }

    /**
     * @param $action
     * @param ParameterBag $params
     * @throws \XF\Mvc\Reply\Exception
     */
    protected function preDispatchController($action, ParameterBag $params)
    {
        $this->assertAdminPermission('thMonetize_communication');
    }
}
