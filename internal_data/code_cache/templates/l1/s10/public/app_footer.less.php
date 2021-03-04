<?php
// FROM HASH: 169cf50fb12258276a605eb628e01c56
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '// ######################################### FOOTER #################################

' . '

.p-footer
{
	display: flex;
	flex-direction: column;
	.xf-publicFooter();

	// a {.xf-publicFooterLink();}
}

.p-footer-inner
{
	order: @xf-uix_footerMenuOrder;
	';
	if (($__templater->func('property', array('uix_pageStyle', ), false) != 'covered') AND (!$__templater->func('property', array('uix_forceCoverFooterMenu', ), false))) {
		$__finalCompiled .= '
		.m-pageWidth();
		.m-pageInset();
	';
	}
	$__finalCompiled .= '
	padding-top: @xf-paddingMedium;
	padding-bottom: @xf-paddingMedium;
	.xf-uix_footerMenu();

	.pageContent {
		display: flex;
		flex-wrap: wrap;
		justify-content: space-between;
		align-items: center;
		';
	if (($__templater->func('property', array('uix_pageStyle', ), false) == 'covered') OR $__templater->func('property', array('uix_forceCoverFooterMenu', ), false)) {
		$__finalCompiled .= '
			.m-pageWidth();
			.m-pageInset();
			';
		if ($__templater->func('property', array('uix_pageStyle', ), false) != 'wrapped') {
			$__finalCompiled .= '
				padding:0;
			';
		}
		$__finalCompiled .= '
		';
	}
	$__finalCompiled .= '
	}

	a {
		.xf-publicFooterLink();
	}
}

.p-footer-copyrightRow {
	order: @xf-uix_copyrightOrder;
	';
	if (($__templater->func('property', array('uix_pageStyle', ), false) != 'covered') AND (!$__templater->func('property', array('uix_forceCoverCopyright', ), false))) {
		$__finalCompiled .= '
	.m-pageWidth();
	.m-pageInset();
	';
	}
	$__finalCompiled .= '
	.xf-uix_footerCopyrightRow();

	.pageContent {
		display: flex;
		flex-wrap: wrap;
		align-items: center;
		justify-content: space-between;
		';
	if (($__templater->func('property', array('uix_pageStyle', ), false) == 'covered') OR $__templater->func('property', array('uix_forceCoverCopyright', ), false)) {
		$__finalCompiled .= '
			.m-pageWidth();
			.m-pageInset();
			';
		if ($__templater->func('property', array('uix_pageStyle', ), false) != 'wrapped') {
			$__finalCompiled .= '
				padding:0;
			';
		}
		$__finalCompiled .= '
		';
	}
	$__finalCompiled .= '
	}

	.p-footer-debug-list li a {
		color: inherit;

		&:hover {color: inherit; text-decoration: underline;}
	}
}

/* commented out in beta 1 (Ian)
.p-footer-row
{
	.m-clearFix();

	margin-bottom: -@xf-paddingLarge;
}

.p-footer-row-main
{
	float: left;
	margin-bottom: @xf-paddingLarge;
}

.p-footer-row-opposite
{
	float: right;
	margin-bottom: @xf-paddingLarge;
}
*/

.p-footer-linkList
{
	.m-listPlain();
	// .m-clearFix();
	display: inline-flex;
	flex-wrap: wrap;
    align-items: center;
	> li
	{
		/* commented out in beta 1 (Ian)
		float: left;
		margin-right: .5em;
		margin: @xf-paddingMedium;
		*/

		&:last-child
		{
			margin-right: 0;
		}

		a
		{
			padding: 6px;
			border-radius: @xf-borderRadiusSmall;
			display: inline-block;

			&:hover
			{
				.xf-uix_footerLinkHover();
				text-decoration: none;
				// background-color: fade(@xf-publicFooterLink--color, 10%);
			}
		}
	}

	&.p-footer-choosers {
		margin: -6px;
		a {
			margin: 6px;
			.xf-uix_footerChooser();


			&:hover {.xf-uix_footerChooserHover();}

			// i {display: none;}
		}
	}
}

.p-footer-rssLink
{
	> span
	{
		position: relative;
		top: -1px;

		display: inline-block;
		width: 1.44em;
		height: 1.44em;
		line-height: 1.44em;
		text-align: center;
		font-size: .8em;
		background-color: #FFA500;
		border-radius: 2px;
	}

	.fa-rss
	{
		color: white;
	}
}

/*
.p-footer-copyright
{
	margin-top: @xf-elementSpacer;
	text-align: center;
	font-size: @xf-fontSizeSmallest;
}

.p-footer-debug
{
	margin-top: @xf-paddingLarge;
	text-align: right;
	font-size: @xf-fontSizeSmallest;
}
*/

@media (max-width: @xf-responsiveMedium)
{
	.p-footer-row-main,
	.p-footer-row-opposite
	{
		float: none;
	}

	.p-footer-copyright
	{
		text-align: left;
		padding: 0 4px; // aligns with other links
	}
}';
	return $__finalCompiled;
}
);