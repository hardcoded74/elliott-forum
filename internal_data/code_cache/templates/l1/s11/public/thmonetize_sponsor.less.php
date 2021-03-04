<?php
// FROM HASH: 94a4db93627731c86b30eab643be0693
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '.node-thMonetizeSponsorBlock
{
	padding: @xf-blockPaddingV @xf-blockPaddingH;
	.xf-blockFooter();
	.m-clearFix();
	&.pairs.pairs--justified {
		display: block;
	}
}

.node-thMonetizeSponsorImage
{
	float: right;
    display: table-cell;
    vertical-align: middle;
    text-align: center;
	padding: 10px 0;
    margin-left: 20px;
}

@media (max-width: @xf-responsiveMedium)
{
	.node-thMonetizeSponsorImage
	{
		display: none;
	}
}

.thmonetize_SponsorsList
{
	display: flex;
	flex-wrap: wrap;
	margin-right: -@xf-paddingMedium;

	.thmonetize_Sponsor {
		flex-basis: 250px;
		flex-grow: 1;
		padding-right: @xf-paddingMedium;
		display: flex;
		flex-direction: column;
		justify-content: center;
	}
}';
	return $__finalCompiled;
}
);