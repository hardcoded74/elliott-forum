<?php

namespace ThemeHouse\Monetize\Entity;

use XF\Entity\Phrase;
use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null upgrade_page_id
 * @property bool active
 * @property int display_order
 * @property array user_criteria
 * @property array page_criteria
 * @property bool page_criteria_overlay_only
 * @property bool show_all
 * @property bool overlay
 * @property bool overlay_dismissible
 * @property bool accounts_page
 * @property bool error_message
 * @property array upgrade_page_links
 * @property bool accounts_page_link
 *
 * GETTERS
 * @property \XF\Phrase|string title
 *
 * RELATIONS
 * @property \XF\Entity\Phrase MasterTitle
 * @property \ThemeHouse\Monetize\Entity\UpgradePageRelation Relations
 */
class UpgradePage extends Entity
{
    /**
     * @param Structure $structure
     * @return Structure
     */
    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_th_monetize_upgrade_page';
        $structure->shortName = 'ThemeHouse\Monetize:UpgradePage';
        $structure->primaryKey = 'upgrade_page_id';
        $structure->columns = [
            'upgrade_page_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
            'active' => ['type' => self::BOOL, 'default' => true],
            'display_order' => ['type' => self::UINT, 'default' => 1],
            'user_criteria' => ['type' => self::JSON_ARRAY, 'default' => []],
            'page_criteria' => ['type' => self::JSON_ARRAY, 'default' => []],
            'page_criteria_overlay_only' => ['type' => self::BOOL, 'default' => true],
            'show_all' => ['type' => self::BOOL, 'default' => true],
            'overlay' => ['type' => self::BOOL, 'default' => false],
            'overlay_dismissible' => ['type' => self::BOOL, 'default' => false],
            'accounts_page' => ['type' => self::BOOL, 'default' => true],
            'error_message' => ['type' => self::BOOL, 'default' => true],
            'upgrade_page_links' => ['type' => self::LIST_COMMA, 'default' => []],
            'accounts_page_link' => ['type' => self::BOOL, 'default' => true],
        ];
        $structure->getters = [
            'title' => true,
            'UpgradePageLinks' => true,
        ];
        $structure->relations = [
            'MasterTitle' => [
                'entity' => 'XF:Phrase',
                'type' => self::TO_ONE,
                'conditions' => [
                    ['language_id', '=', 0],
                    ['title', '=', 'upgrade_page.', '$upgrade_page_id']
                ]
            ],
            'Relations' => [
                'entity' => 'ThemeHouse\Monetize:UpgradePageRelation',
                'type' => self::TO_MANY,
                'conditions' => 'upgrade_page_id',
                'key' => 'user_upgrade_id',
                'order' => 'display_order',
            ]
        ];

        return $structure;
    }

    /**
     * @param null $error
     * @return bool
     */
    public function canView(&$error = null)
    {
        if (!\XF::visitor()->user_id) {
            return false;
        }

        return $this->active;
    }

    /**
     * @return \XF\Phrase|string
     */
    public function getTitle()
    {
        $upgradePagePhrase = \XF::phrase('upgrade_page.' . $this->upgrade_page_id);
        return $upgradePagePhrase->render('html', ['nameOnInvalid' => false]) ?: \XF::phrase('account_upgrades');
    }

    /**
     * @return \XF\Entity\Phrase|Entity
     */
    public function getMasterPhrase()
    {
        $phrase = $this->MasterTitle;
        if (!$phrase) {
            /** @var Phrase $phrase */
            $phrase = $this->_em->create('XF:Phrase');
            $phrase->title = $this->_getDeferredValue(function () {
                return 'upgrade_page.' . $this->upgrade_page_id;
            });
            $phrase->language_id = 0;
            $phrase->addon_id = '';
        }

        return $phrase;
    }

    /**
     * @param array $relationMap
     */
    public function updateRelations(array $relationMap)
    {
        if (!$this->exists()) {
            throw new \LogicException("Upgrade page must be saved first");
        }

        $upgradePageId = $this->upgrade_page_id;
        $insert = [];
        if ($relationMap && !$this->show_all) {
            foreach ($relationMap as $userUpgradeId => $relation) {
                $insert[] = [
                    'upgrade_page_id' => $upgradePageId,
                    'user_upgrade_id' => $userUpgradeId,
                    'display_order' => $relation['display_order'],
                    'featured' => $relation['featured'],
                ];
            }
        }

        $db = $this->db();
        $db->delete('xf_th_monetize_upgrade_page_relation', 'upgrade_page_id = ?', $upgradePageId);
        if ($insert) {
            $db->insertBulk('xf_th_monetize_upgrade_page_relation', $insert);
        }

        unset($this->_relations['Relations']);
    }

    /**
     * @return array
     */
    public function getUpgradePageLinks()
    {
        if ($this->upgrade_page_links) {
            $upgradePages = $this->app()->container('thMonetize.upgradePages');

            $upgradePageLinks = [];
            foreach ($upgradePages as $upgradePage) {
                /** @var UpgradePage $upgradePage */
                $upgradePage = $this->em()->instantiateEntity('ThemeHouse\Monetize:UpgradePage', $upgradePage);

                if (in_array($upgradePage->upgrade_page_id, $this->upgrade_page_links)) {
                    $upgradePageLinks[$upgradePage->upgrade_page_id] = $upgradePage;
                }
            }

            return $upgradePageLinks;
        }

        return [];
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
     * @param $criteria
     * @return bool
     */
    protected function verifyPageCriteria(&$criteria)
    {
        $pageCriteria = $this->app()->criteria('XF:Page', $criteria);
        $criteria = $pageCriteria->getCriteria();
        return true;
    }

    /**
     *
     */
    protected function _postSave()
    {
        $this->rebuildUpgradePageCache();
    }

    /**
     *
     */
    protected function rebuildUpgradePageCache()
    {
        \XF::runOnce('thMonetize_upgradePageCacheRebuild', function () {
            $this->getUpgradePageRepo()->rebuildUpgradePageCache();
        });
    }

    /**
     * @return \ThemeHouse\Monetize\Repository\UpgradePage
     */
    protected function getUpgradePageRepo()
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->repository('ThemeHouse\Monetize:UpgradePage');
    }

    /**
     * @throws \XF\PrintableException
     */
    protected function _postDelete()
    {
        $this->rebuildUpgradePageCache();

        if ($this->MasterTitle) {
            $this->MasterTitle->delete();
        }

        $db = $this->db();
        $db->delete('xf_th_monetize_upgrade_page_relation', 'upgrade_page_id = ?', $this->upgrade_page_id);
    }
}
