<?php

namespace ThemeHouse\Monetize\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\ParameterBag;

/**
 * Class AffiliateLink
 * @package ThemeHouse\Monetize\Admin\Controller
 */
class AffiliateLink extends AbstractController
{
    /**
     * @param ParameterBag $params
     * @return \XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
     * @throws \XF\Mvc\Reply\Exception
     */
    public function actionIndex(ParameterBag $params)
    {
        if ($params['affiliate_link_id']) {
            $affiliateLink = $this->assertAffiliateLinkExists($params['affiliate_link_id']);
            return $this->redirect($this->buildLink('affiliate-links/edit', $affiliateLink));
        }

        $affiliateLinkRepo = $this->getAffiliateLinkRepo();
        $affiliateLinkList = $affiliateLinkRepo->findAffiliateLinksForList()->fetch();
        $affiliateLinks = $affiliateLinkList;

        $viewParams = [
            'affiliateLinks' => $affiliateLinks,
            'totalAffiliateLinks' => $affiliateLinks->count()
        ];
        return $this->view('ThemeHouse\Monetize:AffiliateLink\Listing', 'thmonetize_affiliate_link_list', $viewParams);
    }

    /**
     * @param string $id
     * @param array|string|null $with
     * @param null|string $phraseKey
     *
     * @return \ThemeHouse\Monetize\Entity\AffiliateLink
     * @throws \XF\Mvc\Reply\Exception
     */
    protected function assertAffiliateLinkExists($id, $with = null, $phraseKey = null)
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->assertRecordExists('ThemeHouse\Monetize:AffiliateLink', $id, $with, $phraseKey);
    }

    /**
     * @return \ThemeHouse\Monetize\Repository\AffiliateLink
     */
    protected function getAffiliateLinkRepo()
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->repository('ThemeHouse\Monetize:AffiliateLink');
    }

    /**
     * @return \XF\Mvc\Reply\Redirect
     */
    public function actionRebuildCache()
    {
        $this->getAffiliateLinkRepo()->rebuildAffiliateLinkCache();

        return $this->redirect($this->buildLink('affiliate-links'));
    }

    /**
     * @param ParameterBag $params
     * @return \XF\Mvc\Reply\View
     * @throws \XF\Mvc\Reply\Exception
     */
    public function actionEdit(ParameterBag $params)
    {
        $affiliateLink = $this->assertAffiliateLinkExists($params['affiliate_link_id']);
        return $this->affiliateLinkAddEdit($affiliateLink);
    }

    /**
     * @param \ThemeHouse\Monetize\Entity\AffiliateLink $affiliateLink
     * @return \XF\Mvc\Reply\View
     */
    protected function affiliateLinkAddEdit(\ThemeHouse\Monetize\Entity\AffiliateLink $affiliateLink)
    {
        $userCriteria = $this->app->criteria('XF:User', $affiliateLink->user_criteria);

        $viewParams = [
            'affiliateLink' => $affiliateLink,
            'userCriteria' => $userCriteria,
        ];
        return $this->view('ThemeHouse\Monetize:AffiliateLink\Edit', 'thmonetize_affiliate_link_edit', $viewParams);
    }

    /**
     * @return \XF\Mvc\Reply\View
     */
    public function actionAdd()
    {
        /** @var \ThemeHouse\Monetize\Entity\AffiliateLink $affiliateLink */
        $affiliateLink = $this->em()->create('ThemeHouse\Monetize:AffiliateLink');
        return $this->affiliateLinkAddEdit($affiliateLink);
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

        if ($params['affiliate_link_id']) {
            $affiliateLink = $this->assertAffiliateLinkExists($params['affiliate_link_id']);
        } else {
            $affiliateLink = $this->em()->create('ThemeHouse\Monetize:AffiliateLink');
        }

        $this->affiliateLinkSaveProcess($affiliateLink)->run();

        return $this->redirect($this->buildLink('affiliate-links') . $this->buildLinkHash($affiliateLink->affiliate_link_id));
    }

    /**
     * @param \ThemeHouse\Monetize\Entity\AffiliateLink $affiliateLink
     * @return \XF\Mvc\FormAction
     */
    protected function affiliateLinkSaveProcess(\ThemeHouse\Monetize\Entity\AffiliateLink $affiliateLink)
    {
        $form = $this->formAction();

        $input = $this->filter([
            'title' => 'str',
            'reference_link_prefix' => 'str',
            'reference_link_suffix' => 'str',
            'active' => 'bool',
            'url_cloaking' => 'bool',
            'url_encoding' => 'bool',
            'link_criteria' => 'array',
            'user_criteria' => 'array'
        ]);

        $parserTypes = $this->filter('reference_link_parser_type', 'array');
        $paramOnes = $this->filter('reference_link_parser_param_one', 'array');
        $paramTwos = $this->filter('reference_link_parser_param_two', 'array');

        $referenceLinkParser = [];
        foreach ($parserTypes as $key => $value) {
            $paramOne = $paramOnes[$key];
            $paramTwo = $paramTwos[$key];

            if (!$value || !$paramOne || !$paramTwo) {
                continue;
            }

            $referenceLinkParser[] = array(
                'type' => $value,
                'param_one' => $paramOne,
                'param_two' => $paramTwo,
            );
        }

        $input['reference_link_parser'] = $referenceLinkParser;

        $form->basicEntitySave($affiliateLink, $input);

        $this->getAffiliateLinkRepo()->rebuildAffiliateLinkCache();

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
        $affiliateLink = $this->assertAffiliateLinkExists($params['affiliate_link_id']);
        if (!$affiliateLink->preDelete()) {
            return $this->error($affiliateLink->getErrors());
        }

        if ($this->isPost()) {
            $affiliateLink->delete();
            return $this->redirect($this->buildLink('affiliate-links'));
        } else {
            $viewParams = [
                'affiliateLink' => $affiliateLink
            ];
            return $this->view('ThemeHouse\Monetize:AffiliateLink\Delete', 'thmonetize_affiliate_link_delete',
                $viewParams);
        }
    }

    /**
     * @return \XF\Mvc\Reply\Message
     */
    public function actionToggle()
    {
        /** @var \XF\ControllerPlugin\Toggle $plugin */
        $plugin = $this->plugin('XF:Toggle');
        return $plugin->actionToggle('ThemeHouse\Monetize:AffiliateLink');
    }

    /**
     * @param $action
     * @param ParameterBag $params
     * @throws \XF\Mvc\Reply\Exception
     */
    protected function preDispatchController($action, ParameterBag $params)
    {
        $this->assertAdminPermission('thMonetize_affiliateLinks');
    }
}
