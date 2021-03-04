<?php

namespace ThemeHouse\UIX\Widget;

use XF\Repository\ConnectedAccount;
use XF\Widget\AbstractWidget;

/**
 * Class LoginForm
 * @package ThemeHouse\UIX\Widget
 */
class LoginForm extends AbstractWidget
{
    /**
     * @return \XF\Widget\WidgetRenderer
     */
    public function render()
    {
        /** @var ConnectedAccount $connectedAccountRepo */
        $connectedAccountRepo = $this->repository('XF:ConnectedAccount');
        $viewParams = ['providers' => $connectedAccountRepo->getUsableProviders(true)];
        return $this->renderer('th_widget_login_uix', $viewParams);
    }
}
