<?php

namespace ThemeHouse\Monetize\Repository;

use Xf\Entity\User;
use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;

/**
 * Class AffiliateLink
 * @package ThemeHouse\Monetize\Repository
 */
class AffiliateLink extends Repository
{
    /**
     * @return Finder
     */
    public function findAffiliateLinksForList()
    {
        return $this->finder('ThemeHouse\Monetize:AffiliateLink')->order(['affiliate_link_id']);
    }

    /**
     * @return array
     */
    public function rebuildAffiliateLinkCache()
    {
        $cache = $this->getAffiliateLinkCache();
        \XF::registry()->set('thMonetize_affiliateLinks', $cache);
        return $cache;
    }

    /**
     * @return array
     */
    public function getAffiliateLinkCache()
    {
        $output = [];

        $affiliateLinks = $this->finder('ThemeHouse\Monetize:AffiliateLink')->order(['affiliate_link_id']);

        foreach ($affiliateLinks->fetch() as $affiliateLink) {
            if ($affiliateLink->active) {
                $output[$affiliateLink->affiliate_link_id] = [
                    'reference_link_prefix' => $affiliateLink->reference_link_prefix,
                    'reference_link_suffix' => $affiliateLink->reference_link_suffix,
                    'reference_link_parser' => $affiliateLink->reference_link_parser,
                    'url_cloaking' => $affiliateLink->url_cloaking,
                    'url_encoding' => $affiliateLink->url_encoding,
                    'link_criteria' => $affiliateLink->link_criteria,
                    'user_criteria' => $affiliateLink->user_criteria,
                ];
            }
        }

        return $output;
    }

    /**
     * @param string $url
     * @param User $user
     * @return string $url
     */
    public function parseUrl($url, User $user)
    {
        $cache = $this->app()->container('thMonetize.affiliateLinks');

        if ($cache) {
            foreach ($cache as $affiliateLink) {
                if (!$this->criteriaMatches($url, $user, $affiliateLink)) {
                    continue;
                }

                return $this->getLinkForUrl($url, $affiliateLink);
            }
        }

        return $url;
    }

    /**
     * @param string $url
     * @param User $user
     * @param array $affiliateLink
     * @return bool
     */
    protected function criteriaMatches($url, User $user, array $affiliateLink)
    {
        $parsed = parse_url($url);

        if (!is_array($parsed) || !isset($parsed['host'])) {
            return false;
        }

        $domain = explode('.', $parsed['host']);
        foreach ($domain as &$domainValue) {
            $domainValue = strtolower($domainValue);
        }
        $criteria = $affiliateLink['link_criteria'];

        foreach ($criteria as $key => $value) {
            if (!$value) {
                continue;
            }

            $value = strtolower($value);

            switch ($key) {
                case 'domain_match':
                    if (!in_array($value, $domain)) {
                        return false;
                    }
                    break;
                case 'domain_no_match':
                    if (in_array($value, $domain)) {
                        return false;
                    }
                    break;

                case 'domain_extension_match':
                    if ($value != end($domain)) {
                        return false;
                    }
                    break;
                case 'domain_extension_no_match':
                    if ($value == end($domain)) {
                        return false;
                    }
                    break;

                case 'ref_link_match':
                    if (strpos($url, $value) === false) {
                        return false;
                    }
                    break;
                case 'ref_link_no_match':
                    if (strpos($url, $value) !== false) {
                        return false;
                    }
                    break;
            }
        }

        $userCriteria = $this->app()->criteria('XF:User', $affiliateLink['user_criteria']);
        if (!$userCriteria->isMatched($user)) {
            return false;
        }

        return true;
    }

    /**
     * @param string $url
     * @param array $affiliateLink
     * @param boolean $skipCloaking
     * @return string $url
     */
    public function getLinkForUrl($url, array $affiliateLink, $skipCloaking = false)
    {
        $parsed = parse_url($url);
        $parsed = array_merge(array(
            'scheme' => 'http',
            'host' => '',
            'path' => '',
            'query' => '',
            'fragment' => '',
        ), $parsed);

        if (!is_array($parsed)) {
            return $url;
        }

        if ($affiliateLink['url_cloaking'] && !$skipCloaking) {
            $url = urlencode($url);

            $router = \XF::app()->router('public');
            $url = $router->buildLink('public:affiliate-links', '', [
                'url' => $url,
                '_xfToken' => $this->app()['csrf.token'],
                'link' => $affiliateLink['affiliate_link_id']
            ]);

            return $url;
        }

        if (is_array($affiliateLink['reference_link_parser'])) {
            foreach ($affiliateLink['reference_link_parser'] as $key => $parser) {
                if ($parser['type'] != 'parse') {
                    continue;
                }
                $parserType = $parser['param_one'];
                $parserValue = $parser['param_two'];

                unset($affiliateLink['reference_link_parser'][$key]);

                if (isset($parsed[$parserType])) {
                    $parsed[$parserType] = $parserValue;
                }
            }
        }
        $parsed['scheme'] .= '://';

        $parsed['path'] = $parsed['path'] . '?';
        $url = implode('', $parsed);

        if ($affiliateLink['url_encoding']) {
            $url = urlencode($url);
        }

        if ($affiliateLink['reference_link_prefix'] && strpos($url,
                $affiliateLink['reference_link_prefix']) === false) {
            $url = $affiliateLink['reference_link_prefix'] . $url;
        }

        if ($affiliateLink['reference_link_suffix'] && strpos($url,
                $affiliateLink['reference_link_suffix']) === false) {
            $url = $url . $affiliateLink['reference_link_suffix'];
        }

        foreach ($affiliateLink['reference_link_parser'] as $key => $parser) {
            if ($parser['type'] != 'replace') {
                continue;
            }
            $find = $parser['param_one'];
            $replace = $parser['param_two'];

            if (strpos($url, $find) === false) {
                $url = str_replace($find, $replace, $url);
            }
        }

        return $url;
    }
}
