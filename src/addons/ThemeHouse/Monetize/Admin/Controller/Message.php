<?php

namespace ThemeHouse\Monetize\Admin\Controller;

use ThemeHouse\Monetize\XF\Entity\User;
use XF\Admin\Controller\AbstractController;
use XF\Mvc\ParameterBag;

/**
 * Class Message
 * @package ThemeHouse\Monetize\Admin\Controller
 */
class Message extends AbstractController
{
    /**
     * @param ParameterBag $params
     * @return \XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
     * @throws \XF\Mvc\Reply\Exception
     */
    public function actionIndex(ParameterBag $params)
    {
        if ($params['message_id']) {
            $message = $this->assertMessageExists($params['message_id']);
            return $this->redirect($this->buildLink('monetize-messages/edit', $message));
        }

        $messageRepo = $this->getMessageRepo();
        $messages = $messageRepo->findMessagesForList()->fetch();

        $viewParams = [
            'messages' => $messages,
            'totalMessages' => $messages->count()
        ];
        return $this->view('ThemeHouse\Monetize:Messages\Listing', 'thmonetize_message_list', $viewParams);
    }

    /**
     * @param string $id
     * @param array|string|null $with
     * @param null|string $phraseKey
     *
     * @return \ThemeHouse\Monetize\Entity\Message
     * @throws \XF\Mvc\Reply\Exception
     */
    protected function assertMessageExists($id, $with = null, $phraseKey = null)
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->assertRecordExists('ThemeHouse\Monetize:Message', $id, $with, $phraseKey);
    }

    /**
     * @return \ThemeHouse\Monetize\Repository\Message
     */
    protected function getMessageRepo()
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->repository('ThemeHouse\Monetize:Message');
    }

    /**
     * @param ParameterBag $params
     * @return \XF\Mvc\Reply\View
     * @throws \XF\Mvc\Reply\Exception
     */
    public function actionEdit(ParameterBag $params)
    {
        $message = $this->assertMessageExists($params['message_id']);
        return $this->messageAddEdit($message);
    }

    /**
     * @param \ThemeHouse\Monetize\Entity\Message $message
     * @return \XF\Mvc\Reply\View
     */
    protected function messageAddEdit(\ThemeHouse\Monetize\Entity\Message $message)
    {
        $userUpgradeCriteria = $this->app->criteria('ThemeHouse\Monetize:UserUpgrade', $message->user_upgrade_criteria);
        $userCriteria = $this->app->criteria('XF:User', $message->user_criteria);

        $viewParams = [
            'message' => $message,
            'userUpgradeCriteria' => $userUpgradeCriteria,
            'userCriteria' => $userCriteria,
        ];
        return $this->view('ThemeHouse\Monetize:Message\Edit', 'thmonetize_message_edit', $viewParams);
    }

    /**
     * @return \XF\Mvc\Reply\View
     */
    public function actionAdd()
    {
        /** @var \ThemeHouse\Monetize\Entity\Message $message */
        $message = $this->em()->create('ThemeHouse\Monetize:Message');
        return $this->messageAddEdit($message);
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

        if ($params['message_id']) {
            $message = $this->assertMessageExists($params['message_id']);
        } else {
            $message = $this->em()->create('ThemeHouse\Monetize:Message');
        }

        $this->messageSaveProcess($message)->run();

        return $this->redirect($this->buildLink('monetize-messages') . $this->buildLinkHash($message->message_id));
    }

    /**
     * @param \ThemeHouse\Monetize\Entity\Message $message
     * @return \XF\Mvc\FormAction
     */
    protected function messageSaveProcess(\ThemeHouse\Monetize\Entity\Message $message)
    {
        $form = $this->formAction();

        $input = $this->filter([
            'send_rules' => 'array',
            'title' => 'str',
            'body' => 'str',
            'active' => 'bool',
            'open_invite' => 'bool',
            'conversation_locked' => 'bool',
            'delete_type' => 'str',
            'user_upgrade_criteria' => 'array',
            'user_criteria' => 'array',
            'limit_messages' => 'int',
            'limit_days' => 'int',
        ]);

        $username = $this->filter('username', 'str');
        /** @var User $user */
        $user = $this->em()->findOne('XF:User', ['username' => $username]);

        $input['user_id'] = $user ? $user->user_id : 0;

        $form->basicEntitySave($message, $input);

        return $form;
    }

    /**
     * @param ParameterBag $params
     * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
     * @throws \XF\Mvc\Reply\Exception
     * @throws \XF\PrintableException
     */
    public function actionDelete(ParameterBag $params)
    {
        $message = $this->assertMessageExists($params['message_id']);
        if (!$message->preDelete()) {
            return $this->error($message->getErrors());
        }

        if ($this->isPost()) {
            $message->delete();
            return $this->redirect($this->buildLink('monetize-messages'));
        } else {
            $viewParams = [
                'message' => $message,
            ];
            return $this->view('ThemeHouse\Monetize:Message\Delete', 'thmonetize_message_delete', $viewParams);
        }
    }

    /**
     * @param ParameterBag $params
     * @return \XF\Mvc\Reply\Message|\XF\Mvc\Reply\View
     * @throws \XF\Mvc\Reply\Exception
     * @throws \XF\PrintableException
     */
    public function actionSend(ParameterBag $params)
    {
        $message = $this->assertMessageExists($params['message_id']);

        if ($this->isPost()) {
            if ($message->active) {
                $message->save();
            }
            \XF::app()->jobManager()->enqueueUnique(
                'ThMonetize_SendMessage_' . $message->message_id,
                'ThemeHouse\Monetize:SendMessage',
                ['message_id' => $message->message_id],
                false
            );
            return $this->message(\XF::phrase('thmonetize_message_sent_successfully'));
        } else {
            $viewParams = [
                'message' => $message
            ];
            return $this->view('ThemeHouse\Monetize:Message\Send', 'thmonetize_message_send', $viewParams);
        }
    }

    /**
     * @return \XF\Mvc\Reply\Message
     */
    public function actionToggle()
    {
        /** @var \XF\ControllerPlugin\Toggle $plugin */
        $plugin = $this->plugin('XF:Toggle');
        return $plugin->actionToggle('ThemeHouse\Monetize:Message');
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
