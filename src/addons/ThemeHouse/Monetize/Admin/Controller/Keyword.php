<?php

namespace ThemeHouse\Monetize\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\ParameterBag;

/**
 * Class Keyword
 * @package ThemeHouse\Monetize\Admin\Controller
 */
class Keyword extends AbstractController
{
    /**
     * @param ParameterBag $params
     * @return \XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
     * @throws \XF\Mvc\Reply\Exception
     */
    public function actionIndex(ParameterBag $params)
    {
        if ($params['keyword_id']) {
            $keyword = $this->assertKeywordExists($params['keyword_id']);
            return $this->redirect($this->buildLink('keywords/edit', $keyword));
        }

        $keywordRepo = $this->getKeywordRepo();
        $keywordList = $keywordRepo->findKeywordsForList()->fetch();
        $keywords = $keywordList;

        $options = $this->em()->findByIds('XF:Option', [
            'thmonetize_keywordsLimitPerPage',
            'thmonetize_keywordsLimitPerWord',
        ]);

        $viewParams = [
            'keywords' => $keywords,
            'totalKeywords' => $keywords->count(),
            'options' => $options,
        ];
        return $this->view('ThemeHouse\Monetize:Keyword\Listing', 'thmonetize_keyword_list', $viewParams);
    }

    /**
     * @param string $id
     * @param array|string|null $with
     * @param null|string $phraseKey
     *
     * @return \ThemeHouse\Monetize\Entity\Keyword|\XF\Mvc\Entity\Entity
     * @throws \XF\Mvc\Reply\Exception
     * @throws \XF\Mvc\Reply\Exception
     */
    protected function assertKeywordExists($id, $with = null, $phraseKey = null)
    {
        return $this->assertRecordExists('ThemeHouse\Monetize:Keyword', $id, $with, $phraseKey);
    }

    /**
     * @return \ThemeHouse\Monetize\Repository\Keyword|\XF\Mvc\Entity\Repository
     */
    protected function getKeywordRepo()
    {
        return $this->repository('ThemeHouse\Monetize:Keyword');
    }

    /**
     * @return \XF\Mvc\Reply\Redirect
     */
    public function actionRebuildCache()
    {
        $this->getKeywordRepo()->rebuildKeywordCache();

        return $this->redirect($this->buildLink('keywords'));
    }

    /**
     * @param ParameterBag $params
     * @return \XF\Mvc\Reply\View
     * @throws \XF\Mvc\Reply\Exception
     */
    public function actionEdit(ParameterBag $params)
    {
        $keyword = $this->assertKeywordExists($params['keyword_id']);
        return $this->keywordAddEdit($keyword);
    }

    /**
     * @param \ThemeHouse\Monetize\Entity\Keyword $keyword
     * @return \XF\Mvc\Reply\View
     */
    protected function keywordAddEdit(\ThemeHouse\Monetize\Entity\Keyword $keyword)
    {
        $userCriteria = $this->app->criteria('XF:User', $keyword->user_criteria);

        $viewParams = [
            'keyword' => $keyword,
            'userCriteria' => $userCriteria,
        ];
        return $this->view('ThemeHouse\Monetize:Keyword\Edit', 'thmonetize_keyword_edit', $viewParams);
    }

    /**
     * @return \XF\Mvc\Reply\View
     */
    public function actionAdd()
    {
        /** @var \ThemeHouse\Monetize\Entity\Keyword $keyword */
        $keyword = $this->em()->create('ThemeHouse\Monetize:Keyword');
        return $this->keywordAddEdit($keyword);
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

        if ($params['keyword_id']) {
            $keyword = $this->assertKeywordExists($params['keyword_id']);
        } else {
            $keyword = $this->em()->create('ThemeHouse\Monetize:Keyword');
        }

        $this->keywordSaveProcess($keyword)->run();

        return $this->redirect($this->buildLink('keywords') . $this->buildLinkHash($keyword->keyword_id));
    }

    /**
     * @param \ThemeHouse\Monetize\Entity\Keyword $keyword
     * @return \XF\Mvc\FormAction
     */
    protected function keywordSaveProcess(\ThemeHouse\Monetize\Entity\Keyword $keyword)
    {
        $form = $this->formAction();

        $input = $this->filter([
            'title' => 'str',
            'active' => 'bool',
            'keyword' => 'str',
            'keyword_options' => 'array-bool',
            'replace_type' => 'str',
            'replacement' => 'str',
            'limit' => 'int',
            'user_criteria' => 'array'
        ]);

        $form->basicEntitySave($keyword, $input);

        $this->getKeywordRepo()->rebuildKeywordCache();

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
        $keyword = $this->assertKeywordExists($params['keyword_id']);
        if (!$keyword->preDelete()) {
            return $this->error($keyword->getErrors());
        }

        if ($this->isPost()) {
            $keyword->delete();
            return $this->redirect($this->buildLink('keywords'));
        } else {
            $viewParams = [
                'keyword' => $keyword
            ];
            return $this->view('ThemeHouse\Monetize:Keyword\Delete', 'thmonetize_keyword_delete', $viewParams);
        }
    }

    /**
     * @return \XF\Mvc\Reply\Message
     */
    public function actionToggle()
    {
        /** @var \XF\ControllerPlugin\Toggle $plugin */
        $plugin = $this->plugin('XF:Toggle');
        return $plugin->actionToggle('ThemeHouse\Monetize:Keyword');
    }

    /**
     * @param $action
     * @param ParameterBag $params
     * @throws \XF\Mvc\Reply\Exception
     */
    protected function preDispatchController($action, ParameterBag $params)
    {
        $this->assertAdminPermission('thMonetize_keywords');
    }
}
