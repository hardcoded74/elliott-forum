<?php

namespace ThemeHouse\Monetize\Pub\Controller;

use XF\Pub\Controller\AbstractController;

/**
 * Class Sponsors
 * @package ThemeHouse\Monetize\Pub\Controller
 */
class Sponsors extends AbstractController
{
    /**
     * @return \XF\Mvc\Reply\View
     */
    public function actionIndex()
    {
        if (!$this->options()->thmonetize_enableSponsorsDirectory) {
            return $this->notFound();
        }

        $sponsors = $this->getSponsorRepo()
            ->findSponsorsForList()
            ->activeOnly()
            ->inDirectory()
            ->fetch();

        if (!$sponsors->count()) {
            return $this->notFound();
        }

        $viewParams = [
            'sponsors' => $sponsors,
        ];

        return $this->view('ThemeHouse\Monetize:Sponsors', 'thmonetize_sponsors', $viewParams);
    }

    /**
     * @return \ThemeHouse\Monetize\Repository\Sponsor
     */
    protected function getSponsorRepo()
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->repository('ThemeHouse\Monetize:Sponsor');
    }
}
