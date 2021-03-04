<?php

namespace ThemeHouse\Monetize\XF\Admin\Controller;

use XF\Entity\AbstractNode;
use XF\Entity\Node;
use XF\Mvc\FormAction;
use XF\Mvc\Reply\View;

/**
 * Class Forum
 * @package ThemeHouse\Monetize\XF\Admin\Controller
 */
class Forum extends XFCP_Forum
{
    /**
     * @param Node $node
     * @return View
     */
    protected function nodeAddEdit(Node $node)
    {
        $reply = parent::nodeAddEdit($node);

        if ($reply instanceof View) {
            /** @var \ThemeHouse\Monetize\Repository\Sponsor $sponsorRepo */
            $sponsorRepo = $this->repository('ThemeHouse\Monetize:Sponsor');
            $availableSponsors = $sponsorRepo->findSponsorsForList()->fetch()->pluckNamed('title', 'th_sponsor_id');

            $reply->setParams([
                'availableSponsors' => $availableSponsors,
            ]);
        }

        return $reply;
    }

    /**
     * @param FormAction $form
     * @param Node $node
     * @param AbstractNode $data
     */
    protected function saveTypeData(FormAction $form, Node $node, AbstractNode $data)
    {
        $input = $this->filter([
            'node' => [
                'th_sponsor_id' => 'uint'
            ]
        ]);

        $form->basicEntitySave($node, $input['node']);

        parent::saveTypeData($form, $node, $data);
    }
}
