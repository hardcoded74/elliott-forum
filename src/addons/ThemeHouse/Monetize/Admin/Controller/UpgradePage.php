<?php

namespace ThemeHouse\Monetize\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\FormAction;
use XF\Mvc\ParameterBag;

/**
 * Class UpgradePage
 * @package ThemeHouse\Monetize\Admin\Controller
 */
class UpgradePage extends AbstractController
{
    /**
     * @param ParameterBag $params
     * @return \XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
     * @throws \XF\Mvc\Reply\Exception
     */
    public function actionIndex(ParameterBag $params)
    {
        if ($params['upgrade_page_id']) {
            $upgradePage = $this->assertUpgradePageExists($params['upgrade_page_id']);
            return $this->redirect($this->buildLink('upgrade-pages/edit', $upgradePage));
        }

        $upgradePageRepo = $this->getUpgradePageRepo();
        $upgradePages = $upgradePageRepo->findUpgradePagesForList()->fetch();

        $optionIds = [
            'thmonetize_requireUserUpgradeOnRegistration',
            'thmonetize_allowGuestsToViewUserUpgrades',
            'thmonetize_suggestUpgradeOnNoPermissionError',
        ];
        $options = $this->em()->findByIds('XF:Option', $optionIds)->sortByList($optionIds);

        $viewParams = [
            'upgradePages' => $upgradePages,
            'totalUpgradePages' => $upgradePages->count(),
            'options' => $options,
        ];
        return $this->view('ThemeHouse\Monetize:UpgradePage\Listing', 'thmonetize_upgrade_page_list', $viewParams);
    }

    /**
     * @param string $id
     * @param array|string|null $with
     * @param null|string $phraseKey
     *
     * @return \ThemeHouse\Monetize\Entity\UpgradePage
     * @throws \XF\Mvc\Reply\Exception
     */
    protected function assertUpgradePageExists($id, $with = null, $phraseKey = null)
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->assertRecordExists('ThemeHouse\Monetize:UpgradePage', $id, $with, $phraseKey);
    }

    /**
     * @return \ThemeHouse\Monetize\Repository\UpgradePage
     */
    protected function getUpgradePageRepo()
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->repository('ThemeHouse\Monetize:UpgradePage');
    }

    /**
     * @param ParameterBag $params
     * @return \XF\Mvc\Reply\View
     * @throws \XF\Mvc\Reply\Exception
     */
    public function actionEdit(ParameterBag $params)
    {
        $upgradePage = $this->assertUpgradePageExists($params['upgrade_page_id']);
        return $this->upgradePageAddEdit($upgradePage);
    }

    /**
     * @param \ThemeHouse\Monetize\Entity\UpgradePage $upgradePage
     * @return \XF\Mvc\Reply\View
     */
    protected function upgradePageAddEdit(\ThemeHouse\Monetize\Entity\UpgradePage $upgradePage)
    {
        $userCriteria = $this->app->criteria('XF:User', $upgradePage->user_criteria);
        $pageCriteria = $this->app->criteria('XF:Page', $upgradePage->page_criteria);

        $relations = [];
        if ($upgradePage->exists() && $upgradePage->Relations) {
            foreach ($upgradePage->Relations as $relation) {
                $relations[$relation->user_upgrade_id] = [
                    'display_order' => $relation->display_order,
                    'featured' => $relation->featured,
                ];
            }
        }

        $upgradeRepo = $this->getUserUpgradeRepo();
        $upgradePageRepo = $this->getUpgradePageRepo();

        $upgradePageId = $upgradePage->upgrade_page_id;
        $upgradePageList = $upgradePageRepo->findUpgradePagesForList()->fetch();
        $upgradePages = $upgradePageList->filter(
            function (\ThemeHouse\Monetize\Entity\UpgradePage $upgradePage) use ($upgradePageId) {
                return ($upgradePage->upgrade_page_id !== $upgradePageId);
            }
        );

        $viewParams = [
            'upgradePage' => $upgradePage,
            'userCriteria' => $userCriteria,
            'pageCriteria' => $pageCriteria,
            'relations' => $relations,
            'upgradePages' => $upgradePages,
            'upgrades' => $upgradeRepo->findUserUpgradesForList()->fetch(),
        ];
        return $this->view('ThemeHouse\Monetize:UpgradePage\Edit', 'thmonetize_upgrade_page_edit', $viewParams);
    }

    /**
     * @return \XF\Repository\UserUpgrade
     */
    protected function getUserUpgradeRepo()
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->repository('XF:UserUpgrade');
    }

    /**
     * @return \XF\Mvc\Reply\View
     */
    public function actionAdd()
    {
        /** @var \ThemeHouse\Monetize\Entity\UpgradePage $upgradePage */
        $upgradePage = $this->em()->create('ThemeHouse\Monetize:UpgradePage');
        return $this->upgradePageAddEdit($upgradePage);
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

        if ($params['upgrade_page_id']) {
            $upgradePage = $this->assertUpgradePageExists($params['upgrade_page_id']);
        } else {
            $upgradePage = $this->em()->create('ThemeHouse\Monetize:UpgradePage');
        }

        $this->upgradePageSaveProcess($upgradePage)->run();

        return $this->redirect($this->buildLink('upgrade-pages') . $this->buildLinkHash($upgradePage->upgrade_page_id));
    }

    /**
     * @param \ThemeHouse\Monetize\Entity\UpgradePage $upgradePage
     * @return FormAction
     */
    protected function upgradePageSaveProcess(\ThemeHouse\Monetize\Entity\UpgradePage $upgradePage)
    {
        $form = $this->formAction();

        $input = $this->filter([
            'display_order' => 'int',
            'active' => 'bool',
            'user_criteria' => 'array',
            'page_criteria' => 'array',
            'page_criteria_overlay_only' => 'bool',
            'overlay' => 'bool',
            'show_all' => 'bool',
            'overlay_dismissible' => 'bool',
            'accounts_page' => 'bool',
            'error_message' => 'bool',
            'upgrade_page_links' => 'array',
            'accounts_page_link' => 'bool',
        ]);

        $form->basicEntitySave($upgradePage, $input);

        $upgrades = $this->getUserUpgradeRepo()->findUserUpgradesForList()->fetch();
        $relationMap = [];

        foreach ($this->filter('relations', 'array') as $userUpgradeId => $relation) {
            if (is_array($relation)
                && !empty($relation['selected'])
                && isset($relation['display_order'])
                && isset($upgrades[$userUpgradeId])
            ) {
                $relationMap[$userUpgradeId] = [
                    'display_order' => $this->app->inputFilterer()->filter($relation['display_order'], 'uint'),
                    'featured' => !empty($relation['featured']),
                ];
            }
        }

        $form->validate(function (FormAction $form) use ($upgradePage, $upgrades, $relationMap) {
            if (!$upgradePage->show_all && !count($relationMap)) {
                $form->logError(
                    \XF::phrase('thmonetize_upgrade_pages_must_have_at_least_one_user_upgrade'),
                    'relations'
                );
            }
        });
        $form->apply(function () use ($upgradePage, $relationMap) {
            $upgradePage->updateRelations($relationMap);
        });

        $extraInput = $this->filter([
            'title' => 'str'
        ]);
        $form->apply(function () use ($upgradePage, $extraInput) {
            $title = $upgradePage->getMasterPhrase();
            $title->phrase_text = $extraInput['title'];
            $title->save();
        });

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
        $upgradePage = $this->assertUpgradePageExists($params['upgrade_page_id']);
        if (!$upgradePage->preDelete()) {
            return $this->error($upgradePage->getErrors());
        }

        if ($this->isPost()) {
            $upgradePage->delete();
            return $this->redirect($this->buildLink('upgrade-pages'));
        } else {
            $viewParams = [
                'upgradePage' => $upgradePage
            ];
            return $this->view('ThemeHouse\Monetize:UpgradePage\Delete', 'thmonetize_upgrade_page_delete', $viewParams);
        }
    }

    /**
     * @return \XF\Mvc\Reply\Message
     */
    public function actionToggle()
    {
        /** @var \XF\ControllerPlugin\Toggle $plugin */
        $plugin = $this->plugin('XF:Toggle');
        return $plugin->actionToggle('ThemeHouse\Monetize:UpgradePage');
    }

    /**
     * @param $action
     * @param ParameterBag $params
     * @throws \XF\Mvc\Reply\Exception
     */
    protected function preDispatchController($action, ParameterBag $params)
    {
        $this->assertAdminPermission('thMonetize_upgradePages');
    }
}
