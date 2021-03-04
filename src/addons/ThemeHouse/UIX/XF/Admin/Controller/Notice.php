<?php

namespace ThemeHouse\UIX\XF\Admin\Controller;

/**
 * Class Notice
 * @package ThemeHouse\UIX\XF\Admin\Controller
 */
class Notice extends XFCP_Notice
{
    /**
     * @param \XF\Entity\Notice $notice
     * @return \XF\Mvc\FormAction
     */
    protected function noticeSaveProcess(\XF\Entity\Notice $notice)
    {
        $form = parent::noticeSaveProcess($notice);

        $form->basicEntitySave($notice, [
            'thuix_icon' => $this->filter('thuix_icon', 'str')
        ]);

        return $form;
    }
}
