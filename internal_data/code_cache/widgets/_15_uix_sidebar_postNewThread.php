<?php

return function($__templater, array $__vars, array $__options = [])
{
	$__widget = \XF::app()->widget()->widget('uix_sidebar_postNewThread', $__options)->render();

	return $__widget;
};