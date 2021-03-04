<?php

namespace ThemeHouse\Core;

class LegacyUpgrader
{
    protected static $addOns = [
        'addm' => [
            'start' => 2,
            'length' => null,
            'prepend' => 1,
        ],
    ];

    public static function run(array $installedAddOns = [])
    {
        foreach (self::$addOns as $addOnId => $data) {
            if (!isset($installedAddOns[$addOnId])) {
                continue;
            }

            /** @var \XF\AddOn\AddOn $installed */
            $installed = $installedAddOns[$addOnId];
            if (!$installed->is_legacy) {
                continue;
            }

            $versionLen = strlen($installed->version_id);

            if ($versionLen > 7) {
                $newId = $installed->version_id;

                if (isset($data['length']) && $data['length']) {
                    $newId = substr($newId, $data['start'], $data['length']);
                } else {
                    $newId = substr($newId, $data['start']);
                }

                if (isset($data['prepend']) && $data['prepend']) {
                    $newId = $data['prepend'] . $newId;
                }

                $newId = (int) $newId;

                $addOn = \XF::em()->find('XF:AddOn', $installed->getAddOnId());
                $addOn->version_id = $newId;
                $addOn->save();
            }
        }
    }
}
