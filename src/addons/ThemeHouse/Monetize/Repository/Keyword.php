<?php

namespace ThemeHouse\Monetize\Repository;

use Xf\Entity\User;
use XF\Mvc\Entity\Finder;
use XF\Mvc\Entity\Repository;

/**
 * Class Keyword
 * @package ThemeHouse\Monetize\Repository
 */
class Keyword extends Repository
{
    /**
     * @var array
     */
    protected $replacedCache = [];

    /**
     * @return Finder
     */
    public function findKeywordsForList()
    {
        return $this->finder('ThemeHouse\Monetize:Keyword')
            ->order(['keyword_id']);
    }

    /**
     * @return array
     */
    public function rebuildKeywordCache()
    {
        $cache = $this->getKeywordCache();
        \XF::registry()->set('thMonetize_keywords', $cache);
        return $cache;
    }

    /**
     * @return array
     */
    public function getKeywordCache()
    {
        $output = [];

        $keywords = $this->finder('ThemeHouse\Monetize:Keyword')->order(['keyword_id']);

        foreach ($keywords->fetch() as $keyword) {
            /** @var \ThemeHouse\Monetize\Entity\Keyword $keyword */
            if ($keyword->active) {
                $output[$keyword->keyword_id] = [
                    'keyword_id' => $keyword->keyword_id,
                    'keyword' => $keyword->keyword,
                    'keyword_options' => $keyword->keyword_options,
                    'replace_type' => $keyword->replace_type,
                    'replacement' => $keyword->replacement,
                    'limit' => $keyword->limit,
                    'user_criteria' => $keyword->user_criteria,
                ];
            }
        }

        return $output;
    }

    /**
     * @param string $string
     * @param User $user
     * @return string $url
     */
    public function parseString($string, User $user)
    {
        $cache = $this->app()->container('thMonetize.keywords');

        if ($cache) {
            foreach ($cache as $keywordId => $keyword) {
                $keyword['keyword_id'] = $keywordId;

                if (!$this->criteriaMatches($user, $keyword)) {
                    continue;
                }

                $this->updateLimitForKeyword($keyword);

                if (!isset($keyword['limit']) || $keyword['limit'] >= 1) {
                    $string = $this->prepareReplacementForKeyword($string, $keyword);
                }
            }

            $string = $this->makeReplacementsForKeywords($string, $cache);
        }

        return $string;
    }

    /**
     * @param User $user
     * @param array $keyword
     * @return bool
     */
    protected function criteriaMatches(User $user, array $keyword)
    {
        $userCriteria = $this->app()->criteria('XF:User', $keyword['user_criteria']);
        if (!$userCriteria->isMatched($user)) {
            return false;
        }

        return true;
    }

    /**
     * @param array $keyword
     */
    protected function updateLimitForKeyword(&$keyword)
    {
        if (empty($keyword['limit'])) {
            if (!\XF::options()->thmonetize_keywordsLimitPerWord && !\XF::options()->thmonetize_keywordsLimitPerPage) {
                unset($keyword['limit']);
                return;
            }
            if (\XF::options()->thmonetize_keywordsLimitPerWord) {
                $keyword['limit'] = \XF::options()->thmonetize_keywordsLimitPerWord;
            } else {
                $keyword['limit'] = \XF::options()->thmonetize_keywordsLimitPerPage;
            }
        }

        $replaced = 0;
        if (!empty($this->replacedCache[$keyword['keyword_id']])) {
            $replaced = $this->replacedCache[$keyword['keyword_id']];
        }

        $keyword['limit'] = max(0, $keyword['limit'] - $replaced);

        if (\XF::options()->thmonetize_keywordsLimitPerPage) {
            $replacedOnPage = array_sum($this->replacedCache);
            $newPageLimit = \XF::options()->thmonetize_keywordsLimitPerPage - $replacedOnPage;
            if ($newPageLimit < $keyword['limit']) {
                $keyword['limit'] = $newPageLimit;
            }
        }
    }

    /**
     * @var array
     */
    protected $accentTable;

    /**
     * @return array
     */
    protected function getAccentTable()
    {
        if (!$this->accentTable) {
            global $UTF8_LOWER_ACCENTS;
            $accentTable = [];

            foreach ($UTF8_LOWER_ACCENTS as $accented => $deaccented) {
                if (!isset($accentTable[$deaccented])) {
                    $accentTable[$deaccented] = [];
                }

                $accentTable[$deaccented][] = $accented;
            }

            foreach ($accentTable as $deaccented => &$accented) {
                $accented = '(' . $deaccented . '|' . implode('|', $accented) . ')';
            }

            $this->accentTable = $accentTable;
        }

        return $this->accentTable;
    }

    /**
     * @param $string
     * @return string
     */
    protected function prepForAccentInsensitiveRegex($string)
    {
        $string = strtolower($string);

        $accentTable = $this->getAccentTable();

        $characters = str_split($string);

        $matchedGroups = [];

        foreach ($characters as &$character) {
            if (!isset($accentTable[$character])) {
                continue;
            }

            if (isset($matchedGroups[$character])) {
                $replacement = '(?' . $matchedGroups[$character] . ')';
            } else {
                $replacement = $accentTable[$character];
                $matchedGroups[$character] = count($matchedGroups) + 1;
            }

            $character = $replacement;
        }

        $string = implode($characters);

        return $string;
    }

    /**
     * @param string $string
     * @param array $keyword
     * @return string $string
     */
    public function prepareReplacementForKeyword($string, array $keyword)
    {
        $patternString = $keyword['keyword'];
        $patternString = utf8_deaccent($patternString);
        $patternString = preg_quote(htmlentities($patternString, ENT_QUOTES), '/');

        if (!empty($keyword['keyword_options']['romanized']) && $keyword['keyword_options']['romanized']) {
            $patternString = $this->prepForAccentInsensitiveRegex($patternString);
        }

        if (empty($keyword['keyword_options']['in_word']) || !$keyword['keyword_options']['in_word']) {
            $patternString = '(?<!\w)' . $patternString . '(?!\w)';
        }

        $pattern = '/' . $patternString . '/um';

        if (!empty($keyword['keyword_options']['case_insensitive']) && $keyword['keyword_options']['case_insensitive']) {
            $pattern .= 'i';
        }

        $splits = preg_split('/\[replacement=[0-9]+\].*?\[\/replacement\]/', $string, -1, PREG_SPLIT_OFFSET_CAPTURE);

        $newString = '';
        $endOfLast = 0;

        foreach ($splits as $splitString) {
            $startOfCurrent = $splitString[1];
            if ($startOfCurrent !== $endOfLast) {
                $newString .= substr($string, $endOfLast, $startOfCurrent - $endOfLast);
            }
            $endOfLast = $startOfCurrent + strlen($splitString[0]);

            $newString .= preg_replace(
                $pattern,
                '[replacement=' . $keyword['keyword_id'] . ']$0[/replacement]',
                $splitString[0],
                isset($keyword['limit']) ? $keyword['limit'] : -1,
                $count
            );

            if (isset($this->replacedCache[$keyword['keyword_id']])) {
                $this->replacedCache[$keyword['keyword_id']] += $count;
            } else {
                $this->replacedCache[$keyword['keyword_id']] = $count ?: 0;
            }
            if (isset($keyword['limit'])) {
                $keyword['limit'] -= $count;
            }
        }

        return $newString;
    }

    /**
     * @param $string
     * @param array $keywords
     * @return string
     */
    public function makeReplacementsForKeywords($string, array $keywords)
    {
        preg_match_all('/\[replacement=([0-9]+)\](.*?)\[\/replacement\]/', $string, $matches, PREG_OFFSET_CAPTURE);

        $newString = '';
        $endOfLast = 0;

        foreach ($matches[0] as $key => $match) {
            $startOfCurrent = $match[1];
            if ($startOfCurrent !== $endOfLast) {
                $newString .= substr($string, $endOfLast, $startOfCurrent - $endOfLast);
            }
            $endOfLast = $startOfCurrent + strlen($match[0]);

            $keywordId = $matches[1][$key][0];
            if (isset($keywords[$keywordId])) {
                $newString .= $this->makeReplacementForKeyword($matches[2][$key][0], $keywords[$keywordId]);
            }
        }

        $newString .= substr($string, $endOfLast);

        return $newString;
    }

    /**
     * @param $string
     * @param array $keyword
     * @return mixed|string
     */
    public function makeReplacementForKeyword($string, array $keyword)
    {
        switch ($keyword['replace_type']) {
            case 'url':
                return $this->makeUrlReplacement($string, $keyword);
            case 'html':
                return $this->makeHtmlReplacement($string, $keyword);
        }
        return $string;
    }

    /**
     * @param $string
     * @param array $keyword
     * @return string
     */
    public function makeUrlReplacement($string, array $keyword)
    {
        return '<a href="' . $keyword['replacement'] . '" class="keywordReplace externalLink">' . $string . '</a>';
    }

    /**
     * @param $string
     * @param array $keyword
     * @return mixed
     */
    public function makeHtmlReplacement($string, array $keyword)
    {
        return $keyword['replacement'];
    }
}
