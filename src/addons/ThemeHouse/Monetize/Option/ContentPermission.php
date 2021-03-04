<?php

namespace ThemeHouse\Monetize\Option;

use XF\Entity\Option;
use XF\Option\AbstractOption;

/**
 * Class ContentPermission
 * @package ThemeHouse\Monetize\Option
 */
class ContentPermission extends AbstractOption
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
        $permissionHandlers = \XF::app()->getContentTypeField('permission_handler_class');

        $choices = [
            0 => [
                '_type' => 'option',
                'value' => '',
                'label' => \XF::phrase('(none)'),
                'selected' => empty($option->option_value),
            ],
        ];

        foreach ($permissionHandlers as $contentType => $handler) {
            $choices[] = [
                '_type' => 'option',
                'value' => $contentType,
                'label' => \XF::phrase(\XF::app()->getContentTypePhraseName($contentType)),
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
        $data['controlOptions']['size'] = 6;

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
