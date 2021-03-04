<?php

namespace ThemeHouse\Monetize\XF\Data;

use XF\Language;

/**
 * Class Currency
 * @package ThemeHouse\Monetize\XF\Data
 */
class Currency extends XFCP_Currency
{
    /**
     * @var bool
     */
    protected $thMonetizeFree = false;

    /**
     * @param $free
     */
    public function setThMonetizeFree($free)
    {
        $this->thMonetizeFree = $free;
    }

    /**
     * @param $value
     * @param $currencyCode
     * @param Language|null $language
     * @param null $format
     * @return string|\XF\Phrase
     */
    public function languageFormat($value, $currencyCode, Language $language = null, $format = null)
    {
        if ($value == 0 && $this->thMonetizeFree) {
            return \XF::phrase('thmonetize_free');
        }

        return parent::languageFormat($value, $currencyCode, $language, $format);
    }
}
