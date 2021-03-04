<?php

namespace ThemeHouse\Monetize\Admin\Controller;

use XF\Admin\Controller\AbstractController;
use XF\Entity\OptionGroup;

/**
 * Class Monetize
 * @package ThemeHouse\Monetize\Admin\Controller
 */
class Monetize extends AbstractController
{
    /**
     * @return \XF\Mvc\Reply\View
     */
    public function actionIndex()
    {
        return $this->view('ThemeHouse\Monetize:Monetize', 'thmonetize_monetize');
    }

    /**
     * @return \XF\Mvc\Reply\Error|\XF\Mvc\Reply\View
     * @throws \XF\Mvc\Reply\Exception
     */
    public function actionOptions()
    {
        $this->setSectionContext('thMonetize_options');
        /** @var OptionGroup $group */
        $group = $this->assertOptionGroupExists('thmonetize');

        if ($group->AddOn && !$group->AddOn->active) {
            return $this->error(\XF::phrase('option_group_belongs_to_disabled_addon', [
                'addon' => $group->AddOn->title,
                'link' => $this->buildLink('add-ons')
            ]));
        }

        $optionRepo = $this->getOptionRepo();

        $viewParams = [
            'group' => $group,
            'groups' => $optionRepo->findOptionGroupList()->fetch(),
            'canAdd' => $optionRepo->canAddOption()
        ];
        return $this->view('XF:Option\Listing', 'option_list', $viewParams);
    }

    /**
     * @param string $groupId
     * @param array|string|null $with
     * @param null|string $phraseKey
     *
     * @return OptionGroup
     * @throws \XF\Mvc\Reply\Exception
     */
    protected function assertOptionGroupExists($groupId, $with = null, $phraseKey = null)
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->assertRecordExists('XF:OptionGroup', $groupId, $with, $phraseKey);
    }

    /**
     * @return \XF\Repository\Option
     */
    protected function getOptionRepo()
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->repository('XF:Option');
    }
}
