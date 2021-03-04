<?php

namespace ThemeHouse\Monetize\XF\Template;

use XF\Entity\UnfurlResult;

/**
 * Class Templater
 * @package ThemeHouse\Monetize\XF\Template
 */
class Templater extends XFCP_Templater
{
    /**
     * @param UnfurlResult $result
     * @param array $options
     * @return mixed|string
     */
    public function renderUnfurl(UnfurlResult $result, array $options = [])
    {
        $originalUrl = $result->url;

        $user = \XF::visitor();

        /** @var \ThemeHouse\Monetize\Repository\AffiliateLink $affiliateLinkRepo */
        $affiliateLinkRepo = \XF::app()->em()->getRepository('ThemeHouse\Monetize:AffiliateLink');

        $url = $affiliateLinkRepo->parseUrl($result->url, $user);

        $html = parent::renderUnfurl($result, $options);

        if ($url !== $originalUrl) {
            $html = str_ireplace('href="' . $result->url . '"', 'href="' . $url . '"', $html);
            $html = str_ireplace(' rel="nofollow', 'rel="', $html);
            $html = str_ireplace(' rel="', ' rel="noskim ', $html, $count);
            if (!$count) {
                $html = str_ireplace(' href="', ' rel="noskim" href="', $html);
            }
        }

        return $html;
    }
}
