<?php

/*
	Username Change Add-on by Siropu
	XenForo Profile: https://xenforo.com/community/members/siropu.92813/
	Website: http://www.siropu.com/
	Contact: contact@siropu.com
*/

class Siropu_UsernameChange_Listener
{
	public static function template_create($templateName, array &$params, XenForo_Template_Abstract $template)
    {
        if ($templateName == 'PAGE_CONTAINER')
        {
            $template->preloadTemplate('siropu_username_change_tab_link');
			$template->preloadTemplate('siropu_username_change_sidebar_link');
			$template->preloadTemplate('siropu_username_change_user_tabs_heading');
			$template->preloadTemplate('siropu_username_change_user_tabs_content');
        }
    }
	public static function load_class_controller($class, &$extend)
	{
		switch ($class)
		{
			case 'XenForo_ControllerPublic_Register':
				$extend[] = 'Siropu_UsernameChange_ControllerPublic_Register';
				break;
			case 'XenForo_ControllerPublic_Account':
				$extend[] = 'Siropu_UsernameChange_ControllerPublic_Account';
				break;
		}
	}
	public static function template_hook($hookName, &$contents, array $hookParams, XenForo_Template_Abstract $template)
	{
		switch ($hookName)
		{
			case 'navigation_visitor_tab_links1':
			case 'account_wrapper_sidebar_settings':
				if (Siropu_UsernameChange_Helper::userHasPermission('change'))
				{
					$templateParams = $template->getParams();

					if ($hookName == 'navigation_visitor_tab_links1')
					{
						$contents = $template->create('siropu_username_change_tab_link', $templateParams) . $contents;
					}
					else
					{
						$contents = $template->create('siropu_username_change_sidebar_link', $templateParams) . $contents;
					}
				}
				break;
			case 'member_view_tabs_heading':
			case 'member_view_tabs_content':
				if (Siropu_UsernameChange_Helper::userHasPermission('history'))
				{
					if ($usernameHistory = XenForo_Model::create('Siropu_UsernameChange_Model_History')->getUserHistory($hookParams['user']['user_id'], XenForo_Visitor::getInstance()))
					{
						if ($hookName == 'member_view_tabs_heading')
						{
							$contents .= $template->create('siropu_username_change_user_tabs_heading', $hookParams);
						}
						else
						{
							$viewParams = array_merge($hookParams, array(
								'usernameHistory' => $usernameHistory
							));

							$contents .= $template->create('siropu_username_change_user_tabs_content', $viewParams);
						}
					}
				}
				break;
		}
	}
}