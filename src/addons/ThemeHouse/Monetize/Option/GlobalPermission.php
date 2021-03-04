<?php

namespace ThemeHouse\Monetize\Option;

use XF\Entity\Option;
use XF\Mvc\Entity\AbstractCollection;
use XF\Option\AbstractOption;

/**
 * Class GlobalPermission
 * @package ThemeHouse\Monetize\Option
 */
class GlobalPermission extends AbstractOption
{
    /**
     * @param Option $option
     * @param array $htmlParams
     * @return string
     */
    public static function renderSelect(Option $option, array $htmlParams)
    {
        $data = self::getSelectData($option, $htmlParams);

        return self::getTemplater()->formSelectRow(
            $data['controlOptions'],
            $data['choices'],
            $data['rowOptions']
        );
    }

    /**
     * @param Option $option
     * @param array $htmlParams
     * @return array
     */
    protected static function getSelectData(Option $option, array $htmlParams)
    {
        /** @var \XF\Repository\Permission $permissionRepo */
        $permissionRepo = \XF::repository('XF:Permission');

        $permissionData = $permissionRepo->getGlobalPermissionListData();

        $choices = [
            0 => [
                '_type' => 'option',
                'value' => '',
                'label' => \XF::phrase('(none)'),
                'selected' => empty($option->option_value),
            ],
        ];

        /** @var AbstractCollection $interfaceGroups */
        $interfaceGroups = $permissionData['interfaceGroups'];
        $interfaceGroupsIds = $interfaceGroups->keys();
        $permissionsGrouped = array_replace(array_flip($interfaceGroupsIds), $permissionData['permissionsGrouped']);

        foreach ($permissionsGrouped as $interfaceGroupId => $permissions) {
            if (empty($interfaceGroups[$interfaceGroupId]) || empty($permissions) || !is_array($permissions)) {
                continue;
            }

            $options = [];
            foreach ($permissions as $permissionId => $permission) {
                $options[] = [
                    '_type' => 'option',
                    'value' => $permissionId,
                    'label' => \XF::escapeString($permission->title),
                ];
            }
            $interfaceGroup = $interfaceGroups[$interfaceGroupId];
            $choices[] = [
                '_type' => 'optgroup',
                'value' => $interfaceGroupId,
                'label' => \XF::escapeString($interfaceGroup->title),
                'options' => $options,
            ];
        }

        return [
            'choices' => $choices,
            'controlOptions' => self::getControlOptions($option, $htmlParams),
            'rowOptions' => self::getRowOptions($option, $htmlParams)
        ];
    }

    /**
     * @param Option $option
     * @param array $htmlParams
     * @return string
     */
    public static function renderSelectMultiple(Option $option, array $htmlParams)
    {
        $data = self::getSelectData($option, $htmlParams);
        $data['controlOptions']['multiple'] = true;
        $data['controlOptions']['size'] = 12;

        return self::getTemplater()->formSelectRow(
            $data['controlOptions'],
            $data['choices'],
            $data['rowOptions']
        );
    }

    /**
     * @param $value
     * @param Option $option
     * @return bool
     */
    public static function verifyOption(&$value, Option $option)
    {
        $value = array_filter($value);

        return true;
    }
}
