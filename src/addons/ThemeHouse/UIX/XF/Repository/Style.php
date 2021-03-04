<?php

namespace ThemeHouse\UIX\XF\Repository;

/**
 * Class Style
 * @package ThemeHouse\UIX\XF\Repository
 */
class Style extends XFCP_Style
{
    /**
     * @param array $products
     * @return array
     */
    public function prepareTHStyles(array $products)
    {
        $productIdMap = [];
        foreach ($products as $key => &$product) {
            $productIdMap[$product['id']] = $key;

            $product['installed'] = false;
        }
        unset($product);

        $productIds = array_keys($productIdMap);

        $styles = $this->findStylesByTHProductIds($productIds);

        foreach ($styles as $style) {
            if (isset($productIdMap[$style->th_product_id_uix])) {
                $key = $productIdMap[$style->th_product_id_uix];
                $product = $products[$key];

                $product['isOutdated'] = $this->isTHStyleOutdated($style, $product);
                $product['installed'] = $style;

                $products[$key] = $product;
            }
        }

        return $products;
    }

    /**
     * @param array $productIds
     * @return \XF\Mvc\Entity\ArrayCollection
     */
    public function findStylesByTHProductIds(array $productIds)
    {
        return $this->finder('XF:Style')->where('th_product_id_uix', $productIds)->fetch();
    }

    /**
     * @param \XF\Entity\Style $style
     * @param array $product
     * @return bool
     */
    protected function isTHStyleOutdated(\XF\Entity\Style $style, array $product)
    {
        /** @var \ThemeHouse\UIX\XF\Entity\Style $style */
        if (version_compare($style->th_product_version_uix, $product['latest_version']) === -1) {
            return true;
        }

        return false;
    }

    /**
     * @param array $versions
     * @return array
     */
    public function prepareTHVersions(array $versions)
    {
        preg_match('/^\d.\d.\d/', \XF::$version, $match);
        $xfVersionMinorRelease = $match[0];

        $minorReleaseVersions = [];
        foreach ($versions as $version) {
            preg_match('/^\d.\d.\d/', $version['version'], $match);
            if ($match[0] === $xfVersionMinorRelease) {
                $minorReleaseVersions[] = $version;
            }
        }

        return $minorReleaseVersions;
    }
}
