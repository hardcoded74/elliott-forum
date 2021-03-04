<?php
// FROM HASH: c96ab1678e0d23847a7f0a63f71476d0
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.th_node--hasCustomIcon {
    .node-icon > i:before  {
        content: "";
        display: none;
    }

    .node-icon {
        span {
            display: block;
            line-height: 1.125;
            font-size: 32px;

            color: @xf-nodeIconReadColor;
            text-shadow: 1px 1px 0.5px fade(xf-intensify(@xf-nodeIconReadColor, 50%), 50%);

            .node--unread& {
                opacity: 1;
                color: @xf-nodeIconUnreadColor;
                text-shadow: 1px 1px 0.5px fade(xf-intensify(@xf-nodeIconUnreadColor, 50%), 50%);
            }
        }
    }
}';
	return $__finalCompiled;
}
);