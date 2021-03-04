<?php

namespace ThemeHouse\Monetize\XF\Admin\Controller;

use XF\Mvc\ParameterBag;
use XF\Repository\Payment;

/**
 * Class UserUpgrade
 * @package ThemeHouse\Monetize\XF\Admin\Controller
 */
class UserUpgrade extends XFCP_UserUpgrade
{
    /**
     * @param \XF\Entity\UserUpgrade $upgrade
     * @return \XF\Mvc\Reply\View
     */
    public function upgradeAddEdit(\XF\Entity\UserUpgrade $upgrade)
    {
        $reply = parent::upgradeAddEdit($upgrade);

        $upgradePageRelations = [];
        /** @var \ThemeHouse\Monetize\XF\Entity\UserUpgrade $upgrade */
        if ($upgrade->exists() && $upgrade->ThMonetizeUpgradePageRelations) {
            foreach ($upgrade->ThMonetizeUpgradePageRelations as $relation) {
                $upgradePageRelations[$relation->upgrade_page_id] = [
                    'display_order' => $relation->display_order,
                    'featured' => $relation->featured,
                ];
            }
        }

        $upgradePageRepo = $this->getUpgradePageRepo();

        $reply->setParam('upgradePageRelations', $upgradePageRelations);
        $reply->setParam('upgradePages', $upgradePageRepo->findUpgradePagesForList()->notShowAll()->fetch());

        return $reply;
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
     * @param \XF\Entity\UserUpgrade $upgrade
     * @return \XF\Mvc\FormAction
     */
    protected function upgradeSaveProcess(\XF\Entity\UserUpgrade $upgrade)
    {
        $form = parent::upgradeSaveProcess($upgrade);

        $input = $this->filter([
            'thmonetize_style_properties' => [
                'color' => 'str',
                'shape' => 'str',
                'icon' => 'str',
            ],
            'thmonetize_features' => 'array',
            'thmonetize_custom_amount' => 'boolean',
            'thmonetize_allow_multiple' => 'boolean',
        ]);

        $input['thmonetize_style_properties'] = array_filter($input['thmonetize_style_properties']);
        $input['thmonetize_features'] = array_filter($input['thmonetize_features']);

        $form->basicEntitySave($upgrade, $input);

        $upgradePages = $this->getUpgradePageRepo()->findUpgradePagesForList()->notShowAll()->fetch();
        $relationMap = [];

        foreach ($this->filter('upgrade_page_relations', 'array') as $upgradePageId => $relation) {
            if (is_array($relation)
                && !empty($relation['selected'])
                && isset($relation['display_order'])
                && isset($upgradePages[$upgradePageId])
                && !$upgradePages[$upgradePageId]->show_all
            ) {
                $relationMap[$upgradePageId] = [
                    'display_order' => $this->app->inputFilterer()->filter($relation['display_order'], 'uint'),
                    'featured' => !empty($relation['featured']),
                ];
            }
        }

        $form->apply(function () use ($upgrade, $relationMap) {
            /** @var \ThemeHouse\Monetize\XF\Entity\UserUpgrade $upgrade */
            $upgrade->thMonetizeUpdateUpgradePageRelations($relationMap);
        });

        return $form;
    }

    /**
     * @param ParameterBag $params
     * @return \XF\Mvc\Reply\View
     */
    public function actionShare(ParameterBag $params)
    {
        $upgrade = $this->assertUpgradeExists($params['user_upgrade_id']);

        /** @var Payment $paymentRepo */
        $paymentRepo = $this->repository('XF:Payment');
        $paymentProfiles = $paymentRepo->findPaymentProfilesForList()->fetch();

        $viewParams = [
            'upgrade' => $upgrade,
            'profiles' => $paymentProfiles,
        ];
        return $this->view('ThemeHouse\Monetize:UserUpgrade\Share', 'thmonetize_user_upgrade_share', $viewParams);
    }
}
