<?php

namespace ThemeHouse\Monetize\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property int|null keyword_id
 * @property string title
 * @property string keyword
 * @property array keyword_options
 * @property string replace_type
 * @property string replacement
 * @property int limit
 * @property bool active
 * @property array user_criteria
 *
 * GETTERS
 * @property bool replace_case_insensitive
 * @property bool replace_romanized
 * @property bool replace_in_word
 */
class Keyword extends Entity
{
    /**
     * @return bool
     */
    public function getReplaceCaseInsensitive()
    {
        return empty($this->keyword_options['case_insensitive']) ? false : $this->keyword_options['case_insensitive'];
    }

    /**
     * @return bool
     */
    public function getReplaceRomanized()
    {
        return empty($this->keyword_options['case_insensitive']) ? false : $this->keyword_options['romanized'];
    }

    /**
     * @return bool
     */
    public function getReplaceInWord()
    {
        return empty($this->keyword_options['case_insensitive']) ? false : $this->keyword_options['in_word'];
    }

    /**
     * @param Structure $structure
     * @return Structure
     */
    public static function getStructure(Structure $structure)
    {
        $structure->table = 'xf_th_monetize_keyword';
        $structure->shortName = 'ThemeHouse\Monetize:Keyword';
        $structure->primaryKey = 'keyword_id';
        $structure->columns = [
            'keyword_id' => ['type' => self::UINT, 'autoIncrement' => true, 'nullable' => true],
            'title' => [
                'type' => self::STR,
                'maxLength' => 150,
                'required' => 'please_enter_valid_title'
            ],
            'keyword' => ['type' => self::STR, 'maxLength' => 150, 'required' => true],
            'keyword_options' => [
                'type' => self::JSON,
                'default' => [
                    'case_insensitive' => 1,
                    'romanized' => 1,
                    'in_word' => 1
                ]
            ],
            'replace_type' => [
                'type' => self::STR,
                'default' => 'url',
                'allowedValues' => ['url', 'html']
            ],
            'replacement' => ['type' => self::STR, 'default' => ''],
            'limit' => ['type' => self::UINT, 'default' => 0],
            'active' => ['type' => self::BOOL, 'default' => true],
            'user_criteria' => ['type' => self::JSON_ARRAY, 'default' => []],
        ];

        $structure->getters = [
            'replace_case_insensitive' => true,
            'replace_romanized' => true,
            'replace_in_word' => true
        ];
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
     * @param $replacement
     * @return bool
     */
    protected function verifyReplacement(&$replacement)
    {
        if ($this->replace_type === 'url') {
            $urlValidator = \XF::app()->validator('Url');
            $replacement = $urlValidator->coerceValue($replacement);
            if (!$urlValidator->isValid($replacement, $error)) {
                $this->error(\XF::phrase('thmonetize_invalid_replacement'), 'replacement');
                return false;
            }
        }
        return true;
    }

    /**
     *
     */
    protected function _postSave()
    {
        $this->rebuildKeywordCache();
    }

    /**
     *
     */
    protected function rebuildKeywordCache()
    {
        $repo = $this->getKeywordRepo();

        $repo->rebuildKeywordCache();
    }

    /**
     * @return \ThemeHouse\Monetize\Repository\Keyword
     */
    protected function getKeywordRepo()
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->repository('ThemeHouse\Monetize:Keyword');
    }

    /**
     *
     */
    protected function _postDelete()
    {
        $this->rebuildKeywordCache();
    }
}
