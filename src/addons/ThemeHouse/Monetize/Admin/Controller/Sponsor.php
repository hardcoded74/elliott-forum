<?php

namespace ThemeHouse\Monetize\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Mvc\ParameterBag;

/**
 * Class Sponsor
 * @package ThemeHouse\Monetize\Admin\Controller
 */
class Sponsor extends AbstractController
{
    /**
     * @param ParameterBag $params
     * @return \XF\Mvc\Reply\Redirect|\XF\Mvc\Reply\View
     * @throws \XF\Mvc\Reply\Exception
     */
    public function actionIndex(ParameterBag $params)
    {
        if ($params['th_sponsor_id']) {
            $sponsor = $this->assertSponsorExists($params['th_sponsor_id']);
            return $this->redirect($this->buildLink('sponsors/edit', $sponsor));
        }

        $sponsorRepo = $this->getSponsorRepo();
        $sponsorList = $sponsorRepo->findSponsorsForList()->fetch();
        $sponsors = $sponsorList;

        $options = $this->em()->findByIds('XF:Option', [
            'thmonetize_enableSponsorsDirectory',
        ]);

        $viewParams = [
            'sponsors' => $sponsors,
            'totalSponsors' => $sponsors->count(),
            'options' => $options,
        ];
        return $this->view('ThemeHouse\Monetize:Sponsor\Listing', 'thmonetize_sponsor_list', $viewParams);
    }

    /**
     * @param string $id
     * @param array|string|null $with
     * @param null|string $phraseKey
     *
     * @return \ThemeHouse\Monetize\Entity\Sponsor
     * @throws \XF\Mvc\Reply\Exception
     */
    protected function assertSponsorExists($id, $with = null, $phraseKey = null)
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->assertRecordExists('ThemeHouse\Monetize:Sponsor', $id, $with, $phraseKey);
    }

    /**
     * @return \ThemeHouse\Monetize\Repository\Sponsor
     */
    protected function getSponsorRepo()
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->repository('ThemeHouse\Monetize:Sponsor');
    }

    /**
     * @param ParameterBag $params
     * @return \XF\Mvc\Reply\View
     * @throws \XF\Mvc\Reply\Exception
     */
    public function actionEdit(ParameterBag $params)
    {
        $sponsor = $this->assertSponsorExists($params['th_sponsor_id']);
        return $this->sponsorAddEdit($sponsor);
    }

    /**
     * @param \ThemeHouse\Monetize\Entity\Sponsor $sponsor
     * @return \XF\Mvc\Reply\View
     */
    protected function sponsorAddEdit(\ThemeHouse\Monetize\Entity\Sponsor $sponsor)
    {
        $viewParams = [
            'sponsor' => $sponsor,
        ];
        return $this->view('ThemeHouse\Monetize:Sponsor\Edit', 'thmonetize_sponsor_edit', $viewParams);
    }

    /**
     * @return \XF\Mvc\Reply\View
     */
    public function actionAdd()
    {
        /** @var \ThemeHouse\Monetize\Entity\Sponsor $sponsor */
        $sponsor = $this->em()->create('ThemeHouse\Monetize:Sponsor');
        return $this->sponsorAddEdit($sponsor);
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

        if ($params['th_sponsor_id']) {
            $sponsor = $this->assertSponsorExists($params['th_sponsor_id']);
        } else {
            $sponsor = $this->em()->create('ThemeHouse\Monetize:Sponsor');
        }

        $this->sponsorSaveProcess($sponsor)->run();

        return $this->redirect($this->buildLink('sponsors') . $this->buildLinkHash($sponsor->th_sponsor_id));
    }

    /**
     * @param \ThemeHouse\Monetize\Entity\Sponsor $sponsor
     * @return \XF\Mvc\FormAction
     */
    protected function sponsorSaveProcess(\ThemeHouse\Monetize\Entity\Sponsor $sponsor)
    {
        $form = $this->formAction();

        $input = $this->filter([
            'title' => 'str',
            'active' => 'bool',
            'url' => 'str',
            'image' => 'str',
            'width' => 'int',
            'height' => 'int',
            'directory' => 'bool',
        ]);

        $form->basicEntitySave($sponsor, $input);

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
        $sponsor = $this->assertSponsorExists($params['th_sponsor_id']);
        if (!$sponsor->preDelete()) {
            return $this->error($sponsor->getErrors());
        }

        if ($this->isPost()) {
            $sponsor->delete();
            return $this->redirect($this->buildLink('sponsors'));
        } else {
            $viewParams = [
                'sponsor' => $sponsor
            ];
            return $this->view('ThemeHouse\Monetize:Sponsor\Delete', 'thmonetize_sponsor_delete', $viewParams);
        }
    }

    /**
     * @return \XF\Mvc\Reply\Message
     */
    public function actionToggle()
    {
        /** @var \XF\ControllerPlugin\Toggle $plugin */
        $plugin = $this->plugin('XF:Toggle');
        return $plugin->actionToggle('ThemeHouse\Monetize:Sponsor');
    }

    /**
     * @param $action
     * @param ParameterBag $params
     * @throws \XF\Mvc\Reply\Exception
     */
    protected function preDispatchController($action, ParameterBag $params)
    {
        $this->assertAdminPermission('thMonetize_sponsors');
    }
}
