<?php

namespace ThemeHouse\Monetize\XF\Entity;

use XF\Mvc\Entity\Structure;

/**
 * Class Post
 * @package ThemeHouse\Monetize\XF\Entity
 */
class Post extends XFCP_Post
{
    /**
     * @param Structure $structure
     * @return Structure
     */
    public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);

        $structure->getters['thmonetize_post_snippet_length'] = true;
        $structure->getters['thmonetize_max_posts'] = true;

        return $structure;
    }

    /**
     * @param null $error
     * @return bool|mixed
     */
    public function getThMonetizePostSnippetLength(&$error = null)
    {
        if (!$this->Thread) {
            return false;
        }

        $visitor = \XF::visitor();
        $nodeId = $this->Thread->node_id;

        if ($visitor->user_id && $this->user_id == $visitor->user_id) {
            return false;
        }

        return $visitor->hasNodePermission($nodeId, 'thMonetize_postSnippet');
    }

    /**
     * @param null $error
     * @return bool
     */
    public function getThMonetizeMaxPosts(&$error = null)
    {
        if (!$this->Thread) {
            return false;
        }

        $visitor = \XF::visitor();
        $nodeId = $this->Thread->node_id;

        if ($visitor->user_id && $this->user_id == $visitor->user_id) {
            return false;
        }

        return $visitor->hasNodePermission($nodeId, 'thMonetize_maxPosts');
    }
}
