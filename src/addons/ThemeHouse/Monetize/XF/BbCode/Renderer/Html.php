<?php

namespace ThemeHouse\Monetize\XF\BbCode\Renderer;

/**
 * Class Html
 * @package ThemeHouse\Monetize\XF\BbCode\Renderer
 */
class Html extends XFCP_Html
{
    /**
     * @param array $children
     * @param $option
     * @param array $tag
     * @param array $options
     * @return string
     */
    public function renderTagUrl(array $children, $option, array $tag, array $options)
    {
        $options['thmonetize_stop_keyword_replacement'] = true;
        return parent::renderTagUrl($children, $option, $tag, $options);
    }

    /**
     * @param array $children
     * @param $option
     * @param array $tag
     * @param array $options
     * @return string
     */
    public function renderTagCode(array $children, $option, array $tag, array $options)
    {
        $options['thmonetize_stop_keyword_replacement'] = true;
        return parent::renderTagCode($children, $option, $tag, $options);
    }

    /**
     * @param array $children
     * @param $option
     * @param array $tag
     * @param array $options
     * @return string
     */
    public function renderTagUser(array $children, $option, array $tag, array $options)
    {
        $options['thmonetize_stop_keyword_replacement'] = true;
        return parent::renderTagUser($children, $option, $tag, $options);
    }

    /**
     * @param array $children
     * @param $option
     * @param array $tag
     * @param array $options
     * @return mixed|string
     */
    public function renderTagMedia(array $children, $option, array $tag, array $options)
    {
        $options['thmonetize_stop_keyword_replacement'] = true;
        return parent::renderTagMedia($children, $option, $tag, $options);
    }

    /**
     * @param array $children
     * @param $option
     * @param array $tag
     * @param array $options
     * @return string
     */
    public function renderTagImage(array $children, $option, array $tag, array $options)
    {
        $options['thmonetize_stop_keyword_replacement'] = true;
        return parent::renderTagImage($children, $option, $tag, $options);
    }

    /**
     * @param array $children
     * @param $option
     * @param array $tag
     * @param array $options
     * @return string
     */
    public function renderTagAttach(array $children, $option, array $tag, array $options)
    {
        $options['thmonetize_stop_keyword_replacement'] = true;
        return parent::renderTagAttach($children, $option, $tag, $options);
    }

    /**
     * @param array $children
     * @param $option
     * @param array $tag
     * @param array $options
     * @return null|string|string[]
     */
    public function renderTagEmail(array $children, $option, array $tag, array $options)
    {
        $options['thmonetize_stop_keyword_replacement'] = true;
        return parent::renderTagEmail($children, $option, $tag, $options);
    }

    /**
     * @param $string
     * @param array $options
     * @return null|string|string[]
     */
    public function filterString($string, array $options)
    {
        $string = parent::filterString($string, $options);

        $user = \XF::visitor();

        if (!isset($options['thmonetize_stop_keyword_replacement']) || !$options['thmonetize_stop_keyword_replacement']) {
            /** @var \ThemeHouse\Monetize\Repository\Keyword $keywordRepo */
            $keywordRepo = \XF::app()->em()->getRepository('ThemeHouse\Monetize:Keyword');

            $string = $keywordRepo->parseString($string, $user);
        }

        return $string;
    }

    /**
     * @param $text
     * @param $url
     * @param array $options
     * @return mixed|string
     */
    protected function getRenderedLink($text, $url, array $options)
    {
        $originalUrl = $url;
        $noSkim = false;

        $user = \XF::visitor();

        /** @var \ThemeHouse\Monetize\Repository\AffiliateLink $affiliateLinkRepo */
        $affiliateLinkRepo = \XF::app()->em()->getRepository('ThemeHouse\Monetize:AffiliateLink');

        $url = $affiliateLinkRepo->parseUrl($url, $user);

        if ($url !== $originalUrl) {
            $noSkim = true;
        }

        $html = parent::getRenderedLink($text, $url, $options);

        if ($noSkim) {
            $html = str_ireplace(' rel="nofollow', 'rel="', $html);
            $html = str_ireplace(' rel="', ' rel="noskim ', $html, $count);
            if (!$count) {
                $html = str_ireplace(' href="', ' rel="noskim" href="', $html);
            }
        }

        return $html;
    }
}
