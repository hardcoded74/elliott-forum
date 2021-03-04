<?php

namespace ThemeHouse\Monetize\Pub\Controller;

use XF\Pub\Controller\AbstractController;

/**
 * Class AffiliateLink
 * @package ThemeHouse\Monetize\Pub\Controller
 */
class AffiliateLink extends AbstractController
{
    /**
     * @return \XF\Mvc\Reply\Redirect
     * @throws \XF\Mvc\Reply\Exception
     */
    public function actionIndex()
    {
        $this->assertValidCsrfToken();

        $url = $this->filter('url', 'str');
        $affiliateLinkId = $this->filter('link', 'int');

        $url = urldecode($url);

        $affiliateLink = $this->em()->find('ThemeHouse\Monetize:AffiliateLink', $affiliateLinkId);
        if ($affiliateLink) {
            /** @var \ThemeHouse\Monetize\Repository\AffiliateLink $repo */
            $repo = $this->repository('ThemeHouse\Monetize:AffiliateLink');

            $url = $repo->getLinkForUrl($url, $affiliateLink->toArray(), true);
        }

        return $this->redirect($url);
    }
}
