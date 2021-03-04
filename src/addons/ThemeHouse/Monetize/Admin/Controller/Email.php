<?php

namespace ThemeHouse\Monetize\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\ParameterBag;

/**
 * Class Email
 * @package ThemeHouse\Monetize\Admin\Controller
 */
class Email extends AbstractController
{
    /**
     * @param ParameterBag $params
     * @return \XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
     * @throws \XF\Mvc\Reply\Exception
     */
    public function actionIndex(ParameterBag $params)
    {
        if ($params['email_id']) {
            $email = $this->assertEmailExists($params['email_id']);
            return $this->redirect($this->buildLink('monetize-emails/edit', $email));
        }

        $emailRepo = $this->getEmailRepo();
        $emails = $emailRepo->findEmailsForList()->fetch();

        $viewParams = [
            'emails' => $emails,
            'totalEmails' => $emails->count()
        ];
        return $this->view('ThemeHouse\Monetize:Emails\Listing', 'thmonetize_email_list', $viewParams);
    }

    /**
     * @param string $id
     * @param array|string|null $with
     * @param null|string $phraseKey
     *
     * @return \ThemeHouse\Monetize\Entity\Email
     * @throws \XF\Mvc\Reply\Exception
     */
    protected function assertEmailExists($id, $with = null, $phraseKey = null)
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->assertRecordExists('ThemeHouse\Monetize:Email', $id, $with, $phraseKey);
    }

    /**
     * @return \ThemeHouse\Monetize\Repository\Email
     */
    protected function getEmailRepo()
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->repository('ThemeHouse\Monetize:Email');
    }

    /**
     * @param ParameterBag $params
     * @return \XF\Mvc\Reply\View
     * @throws \XF\Mvc\Reply\Exception
     */
    public function actionEdit(ParameterBag $params)
    {
        $email = $this->assertEmailExists($params['email_id']);
        return $this->emailAddEdit($email);
    }

    /**
     * @param \ThemeHouse\Monetize\Entity\Email $email
     * @return \XF\Mvc\Reply\View
     */
    protected function emailAddEdit(\ThemeHouse\Monetize\Entity\Email $email)
    {
        $userUpgradeCriteria = $this->app->criteria('ThemeHouse\Monetize:UserUpgrade', $email->user_upgrade_criteria);
        $userCriteria = $this->app->criteria('XF:User', $email->user_criteria);

        $viewParams = [
            'email' => $email,
            'userUpgradeCriteria' => $userUpgradeCriteria,
            'userCriteria' => $userCriteria,
        ];
        return $this->view('ThemeHouse\Monetize:Email\Edit', 'thmonetize_email_edit', $viewParams);
    }

    /**
     * @return \XF\Mvc\Reply\View
     */
    public function actionAdd()
    {
        /** @var \ThemeHouse\Monetize\Entity\Email $email */
        $email = $this->em()->create('ThemeHouse\Monetize:Email');
        return $this->emailAddEdit($email);
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

        if ($params['email_id']) {
            $email = $this->assertEmailExists($params['email_id']);
        } else {
            $email = $this->em()->create('ThemeHouse\Monetize:Email');
        }

        $this->emailSaveProcess($email)->run();

        return $this->redirect($this->buildLink('monetize-emails') . $this->buildLinkHash($email->email_id));
    }

    /**
     * @param \ThemeHouse\Monetize\Entity\Email $email
     * @return \XF\Mvc\FormAction
     */
    protected function emailSaveProcess(\ThemeHouse\Monetize\Entity\Email $email)
    {
        $form = $this->formAction();

        $input = $this->filter([
            'send_rules' => 'array',
            'from_name' => 'str',
            'from_email' => 'str',
            'title' => 'str',
            'format' => 'str',
            'body' => 'str',
            'active' => 'bool',
            'wrapped' => 'bool',
            'unsub' => 'bool',
            'receive_admin_email_only' => 'bool',
            'user_upgrade_criteria' => 'array',
            'user_criteria' => 'array',
            'limit_emails' => 'int',
            'limit_days' => 'int',
        ]);

        $form->basicEntitySave($email, $input);

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
        $email = $this->assertEmailExists($params['email_id']);
        if (!$email->preDelete()) {
            return $this->error($email->getErrors());
        }

        if ($this->isPost()) {
            $email->delete();
            return $this->redirect($this->buildLink('monetize-emails'));
        } else {
            $viewParams = [
                'email' => $email,
            ];
            return $this->view('ThemeHouse\Monetize:Email\Delete', 'thmonetize_email_delete', $viewParams);
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
        $email = $this->assertEmailExists($params['email_id']);

        if ($this->isPost()) {
            if ($email->active) {
                $email->save();
            }
            \XF::app()->jobManager()->enqueueUnique(
                'ThMonetize_SendEmail_' . $email->email_id,
                'ThemeHouse\Monetize:SendEmail',
                ['email_id' => $email->email_id],
                false
            );
            return $this->message(\XF::phrase('thmonetize_email_sent_successfully'));
        } else {
            $viewParams = [
                'email' => $email
            ];
            return $this->view('ThemeHouse\Monetize:Email\Send', 'thmonetize_email_send', $viewParams);
        }
    }

    /**
     * @return \XF\Mvc\Reply\Message
     */
    public function actionToggle()
    {
        /** @var \XF\ControllerPlugin\Toggle $plugin */
        $plugin = $this->plugin('XF:Toggle');
        return $plugin->actionToggle('ThemeHouse\Monetize:Email');
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
