<?php

namespace ThemeHouse\Monetize\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null affiliate_link_id
 * @property string title
 * @property string reference_link_prefix
 * @property string reference_link_suffix
 * @property array reference_link_parser
 * @property bool active
 * @property bool url_cloaking
 * @property bool url_encoding
 * @property array link_criteria
 * @property array user_criteria
 */
class AffiliateLink extends Entity
{
    /**
     * @param Structure $structure
     * @return Structure
     */
    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_th_monetize_affiliate_link';
        $structure->shortName = 'ThemeHouse\Monetize:AffiliateLink';
        $structure->primaryKey = 'affiliate_link_id';
        $structure->columns = [
            'affiliate_link_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
            'title' => [
                'type' => self::STR,
                'maxLength' => 150,
                'required' => 'please_enter_valid_title'
            ],
            'reference_link_prefix' => ['type' => self::STR],
            'reference_link_suffix' => ['type' => self::STR],
            'reference_link_parser' => ['type' => self::JSON_ARRAY, 'default' => []],
            'active' => ['type' => self::BOOL, 'default' => true],
            'url_cloaking' => ['type' => self::BOOL, 'default' => false],
            'url_encoding' => ['type' => self::BOOL, 'default' => false],
            'link_criteria' => ['type' => self::JSON_ARRAY, 'default' => []],
            'user_criteria' => ['type' => self::JSON_ARRAY, 'default' => []],
        ];
        $structure->getters = [];
        $structure->relations = [];

        return $structure;
    }

    /**
     * @param $criteria
     * @return bool
     */
    protected function verifyUserCriteria(&$criteria)
    {
        $userCriteria = $this->app()->criteria('XF:User', $criteria);
        $criteria = $userCriteria->getCriteria();
        return true;
    }

    /**
     *
     */
    protected function _postSave()
    {
        $this->rebuildAffiliateLinkCache();
    }

    /**
     *
     */
    protected function rebuildAffiliateLinkCache()
    {
        $repo = $this->getAffiliateLinkRepo();

        $repo->rebuildAffiliateLinkCache();
    }

    /**
     * @return \ThemeHouse\Monetize\Repository\AffiliateLink
     */
    protected function getAffiliateLinkRepo()
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->repository('ThemeHouse\Monetize:AffiliateLink');
    }

    /**
     *
     */
    protected function _postDelete()
    {
        $this->rebuildAffiliateLinkCache();
    }
}
