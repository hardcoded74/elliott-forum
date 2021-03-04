<?php
// FROM HASH: 1a4932a3a1673ba8c3ac18020563a80c
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.xfmgThumbnail
{
	display: flex;
	position: relative;
	width: 100px;

	vertical-align: top;
	white-space: nowrap;
	word-wrap: normal;
	text-align: center;

	&&.is-selected // increase specificity to ensure overriding of noThumb border
	{
		border: @xf-borderSizeMinorFeature solid @xf-borderColorFeature;
	}

	&.xfmgThumbnail--fluid
	{
		width: inherit;
	}

	&.xfmgThumbnail--smallest
	{
		width: 50px;
	}

	&.xfmgThumbnail--small
	{
		width: 60px;
	}

	&.xfmgThumbnail--noThumb
	{
		.xf-contentAltBase();

		.xfmgThumbnail-icon
		{
			&::before
			{
				.m-faContent(@fa-var-ellipsis-h);
			}

			.xfmgThumbnail--upload&
			{
				&::before
				{
					.m-faContent(@fa-var-upload) !important; // has to override the default no thumbnail icon
					' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'upload',
	), $__vars) . '
				}
			}

			.xfmgThumbnail--audio&
			{
				&::before
				{
					.m-faContent(@fa-var-music);
					' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'audio',
	), $__vars) . '
				}
			}

			.xfmgThumbnail--image&
			{
				&::before
				{
					.m-faContent(@fa-var-image);
					' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'media',
	), $__vars) . '
				}
			}

			.xfmgThumbnail--video&
			{
				&::before
				{
					.m-faContent(@fa-var-video);
					' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'video',
	), $__vars) . '
				}
			}

			.xfmgThumbnail--embed&
			{
				.xfmgThumbnail--upload&
				{
					&::before
					{
						.m-faBase(\'Pro\');
					}
				}

				&::before
				{
					.m-faBase(\'Brands\');
					.m-faContent(@fa-var-youtube);
					' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'youtube',
	), $__vars) . '
				}
			}

			.xfmgThumbnail--album&
			{
				&::before
				{
					.m-faContent(@fa-var-folder-open);
					' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'folder',
	), $__vars) . '
				}
			}
		}
	}

	&.xfmgThumbnail--notVisible
	{
		.xfmgThumbnail-image
		{
			opacity: 0.3;
			z-index: @zIndex-2;
		}

		&.xfmgThumbnail--notVisible--deleted
		{
			.xfmgThumbnail-icon
			{
				&::before
				{
					.m-faBase(\'Pro\');
					.m-faContent(@fa-var-trash-alt);
					' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'delete',
	), $__vars) . '
				}
			}
		}

		&.xfmgThumbnail--notVisible--moderated
		{
			.xfmgThumbnail-icon
			{
				&::before
				{
					.m-faBase(\'Pro\');
					.m-faContent(@fa-var-shield);
					' . $__templater->callMacro('uix_icons.less', 'content', array(
		'icon' => 'moderate',
	), $__vars) . '
				}
			}
		}
	}
}

.xfmgThumbnail-image
{
	width: 100%;
	height: 100%;
	vertical-align: top;
}

.xfmgThumbnail-icon
{
	position: absolute;
	left: 50%;
	top: 50%;
	transform: translate(-50%, -50%);
	color: @xf-textColorMuted;
	.m-textOutline(@xf-textColorMuted, xf-intensify(@xf-textColorMuted, 20%));
	
	.m-faBase();
	font-size: 60px;
	
	.xfmgThumbnail--upload && // extra specificity required in this case
	{
		.m-faBase();
		font-size: 60px;
	}

	.xfmgThumbnail--embed:not(.xfmgThumbnail--notVisible, .xfmgThumbnail--upload) &
	{
		.m-faBase(\'Brands\');
		font-size: 60px;
	}

	.xfmgThumbnail--smallest &,
	.xfmgThumbnail--iconSmallest &
	{
		font-size: 25px;
	}

	.xfmgThumbnail--small &,
	.xfmgThumbnail--iconSmall &
	{
		font-size: 30px;
	}

	@media (max-width: @xf-responsiveMedium)
	{
		font-size: 50px;
	}

	@media (max-width: @xf-responsiveNarrow)
	{
		font-size: 30px;
	}
}';
	return $__finalCompiled;
}
);