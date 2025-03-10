<?php
// FROM HASH: 4a55d77fd612e30f1e1cf154c7af7d49
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__finalCompiled .= '/* XF-RTL:disable */

.codeEditor
{
	// standard editor with fixed-width font and 55% screen height, used when the code editor is the primary
	// editable element on screen
	&.CodeMirror
	{
		height: 55vh;
		direction: ltr;

		.xf-input();
		font-family: @xf-fontFamilyCode;
		padding: 0;
		-ltr-rtl-border-color: @xf-borderColorHeavy @xf-borderColorLight @xf-borderColorLight @xf-borderColorHeavy;

		//color: @xf-inputTextColor;
		//background: @xf-inputBgColor;
		//border: @xf-borderSize solid;
		//border-radius: @xf-borderRadiusMedium;

		.m-inputZoomFix();

		&.CodeMirror-focused
		{
			.xf-inputFocus();
		}

		&.CodeMirror-simplescroll
		{
			.CodeMirror-sizer
			{
				// Bit hacky but solves issue with the simplescroll bars overlapping the content
				padding-right: 30px !important;
			}
		}
	}

	// short editor, taking only 30% of the vertical height
	&.codeEditor--short
	{
		height: 30vh;
	}

	// show an editor that shrinks to a very small height for very little content
	&.codeEditor--autoSize
	{
		height: auto;

		.CodeMirror-lines
		{
			min-height: (@xf-fontSizeNormal * @xf-lineHeightDefault * 2) + 8px; // 2 lines, 4px padding from .CodeMirror-lines
		}
	}

	// like --autoSize, but shrinks to a single line when empty
	&.codeEditor--oneLine
	{
		min-height: auto;
	}

	// use proportional font - use this when syntax highlighting is useful, but not imperative, like HTML-enabled descriptions
	&.codeEditor--proportional
	{
		font-family: @xf-fontFamilyUi;
	}
}

[disabled] + .codeEditor,
[disabled] + .codeEditor.CodeMirror-focused
{
	.xf-inputDisabled();
}

[readonly] + .codeEditor,
[readonly] + .codeEditor.CodeMirror-focused
{
	background: mix(xf-default(@xf-input--background-color, @xf-contentBg), xf-default(@xf-inputDisabled--background-color, @xf-paletteNeutral1));
}

/* BASICS */

.CodeMirror {
	height: 300px;
}

/* PADDING */

.CodeMirror-lines {
	padding: 4px 0; /* Vertical padding around content */
}
.CodeMirror pre {
	padding: 0 4px; /* Horizontal padding of content */
}

.CodeMirror-scrollbar-filler, .CodeMirror-gutter-filler {
	background-color: white; /* The little square between H and V scrollbars */
}

/* GUTTER */

.CodeMirror-gutters {
	border-right: 1px solid #ddd;
	background-color: #f7f7f7;
	white-space: nowrap;
}
.CodeMirror-linenumbers {}
.CodeMirror-linenumber {
	padding: 0 3px 0 5px;
	min-width: 20px;
	text-align: right;
	color: #999;
	white-space: nowrap;
}

.CodeMirror-guttermarker { color: black; }
.CodeMirror-guttermarker-subtle { color: #999; }

/* CURSOR */

.CodeMirror-cursor {
	border-left: 1px solid black;
	border-right: none;
	width: 0;
}
/* Shown when moving in bi-directional text */
.CodeMirror div.CodeMirror-secondarycursor {
	border-left: 1px solid silver;
}
.cm-fat-cursor .CodeMirror-cursor {
	width: auto;
	border: 0 !important;
	background: #7e7;
}
.cm-fat-cursor div.CodeMirror-cursors {
	z-index: 1;
}

.cm-animate-fat-cursor {
	width: auto;
	border: 0;
	-webkit-animation: blink 1.06s steps(1) infinite;
	-moz-animation: blink 1.06s steps(1) infinite;
	animation: blink 1.06s steps(1) infinite;
	background-color: #7e7;
}
@-moz-keyframes blink {
	0% {}
	50% { background-color: transparent; }
	100% {}
}
@-webkit-keyframes blink {
	0% {}
	50% { background-color: transparent; }
	100% {}
}
@keyframes blink {
	0% {}
	50% { background-color: transparent; }
	100% {}
}

/* Can style cursor different in overwrite (non-insert) mode */
.CodeMirror-overwrite .CodeMirror-cursor {}

.cm-tab { display: inline-block; text-decoration: inherit; }

.CodeMirror-rulers {
	position: absolute;
	left: 0; right: 0; top: -50px; bottom: -20px;
	overflow: hidden;
}
.CodeMirror-ruler {
	border-left: 1px solid #ccc;
	top: 0; bottom: 0;
	position: absolute;
}

/* DEFAULT THEME */

.cm-s-default .cm-header {color: blue;}
.cm-s-default .cm-quote {color: #090;}
.cm-negative {color: #d44;}
.cm-positive {color: #292;}
.cm-header, .cm-strong {font-weight: bold;}
.cm-em {font-style: italic;}
.cm-link {text-decoration: underline;}
.cm-strikethrough {text-decoration: line-through;}

.cm-s-default .cm-keyword {color: #708;}
.cm-s-default .cm-atom {color: #219;}
.cm-s-default .cm-number {color: #164;}
.cm-s-default .cm-def {color: #00f;}
.cm-s-default .cm-variable,
.cm-s-default .cm-punctuation,
.cm-s-default .cm-property,
.cm-s-default .cm-operator {}
.cm-s-default .cm-variable-2 {color: #05a;}
.cm-s-default .cm-variable-3 {color: #085;}
.cm-s-default .cm-comment {color: #a50;}
.cm-s-default .cm-string {color: #a11;}
.cm-s-default .cm-string-2 {color: #f50;}
.cm-s-default .cm-meta {color: #555;}
.cm-s-default .cm-qualifier {color: #555;}
.cm-s-default .cm-builtin {color: #30a;}
.cm-s-default .cm-bracket {color: #997;}
.cm-s-default .cm-tag {color: #170;}
.cm-s-default .cm-attribute {color: #00c;}
.cm-s-default .cm-hr {color: #999;}
.cm-s-default .cm-link {color: #00c;}

.cm-s-default .cm-error {color: #f00;}
.cm-invalidchar {color: #f00;}

.CodeMirror-composing { border-bottom: 2px solid; }

/* Default styles for common addons */

div.CodeMirror span.CodeMirror-matchingbracket {color: #0f0;}
div.CodeMirror span.CodeMirror-nonmatchingbracket {color: #f22;}
.CodeMirror-matchingtag { background: rgba(255, 150, 0, .3); }
.CodeMirror-activeline-background {background: #e8f2ff;}

// Extra stuff for DARK styles, taking values from CodeMirror\'s DARCULA theme
.cm-s-default
{
	& when (@xf-styleType = dark)
	{
		&.CodeMirror
		{
			.CodeMirror-cursor { border-left: 1px solid #dddddd; }
			.CodeMirror-activeline-background { background: #3A3A3A; }
			.CodeMirror-selected { background: #085a9c; }
			.CodeMirror-gutters { background: rgb(72, 72, 72); border-right: 1px solid grey; color: #606366 }
			.CodeMirror-matchingbracket { background-color: #3b514d; color: yellow !important; }
		}

		span.cm-keyword { font-weight: bold; color: #CC7832; }
		span.cm-atom { font-weight: bold; color: #CC7832; }
		span.cm-number { color: #6897BB; }
		span.cm-def { color: #FFC66D; }
		span.cm-variable { color: #A9B7C6; }
		span.cm-property { color: #A9B7C6; }
		span.cm-operator { color: #A9B7C6; }
		span.cm-variable-2 { color: #A9B7C6; }
		span.cm-variable-3,
		span.cm-comment { color: #808080; }
		span.cm-string { color: #6A8759; }
		span.cm-string-2 { color: #6A8759; }
		span.cm-meta { color: #BBB529; }
		span.cm-qualifier { color: #6A8759; }
		span.cm-builtin { color: #A9B7C6; }
		span.cm-bracket { color: #A9B7C6; }
		span.cm-tag { color: #CC7832; }
		span.cm-attribute { color: #6A8759; }
		span.cm-link { color: #287BDE; }
		span.cm-error { color: #BC3F3C; }
		span.cm-invalidchar { color: #BC3F3C; }
		span.cm-type { color: #A9B7C6; }
	}
}

/* STOP */

/* The rest of this file contains styles related to the mechanics of
   the editor. You probably shouldn\'t touch them. */

.CodeMirror {
	position: relative;
	overflow: hidden;
	background: white;
}

.CodeMirror-scroll {
	overflow: scroll !important; /* Things will break if this is overridden */
	/* 30px is the magic margin used to hide the element\'s real scrollbars */
	/* See overflow: hidden in .CodeMirror */
	margin-bottom: -30px; margin-right: -30px;
	padding-bottom: 30px;
	height: 100%;
	outline: none; /* Prevent dragging from highlighting the element */
	position: relative;
	// background-color: #fff;
}
.CodeMirror-sizer {
	position: relative;
	border-right: 30px solid transparent;
}

/* The fake, visible scrollbars. Used to force redraw during scrolling
   before actual scrolling happens, thus preventing shaking and
   flickering artifacts. */
.CodeMirror-vscrollbar, .CodeMirror-hscrollbar, .CodeMirror-scrollbar-filler, .CodeMirror-gutter-filler {
	position: absolute;
	z-index: 6;
	display: none;
}
.CodeMirror-vscrollbar {
	right: 0; top: 0;
	overflow-x: hidden;
	overflow-y: scroll;
}
.CodeMirror-hscrollbar {
	bottom: 0; left: 0;
	overflow-y: hidden;
	overflow-x: scroll;
}
.CodeMirror-scrollbar-filler {
	right: 0; bottom: 0;
}
.CodeMirror-gutter-filler {
	left: 0; bottom: 0;
}

.CodeMirror-gutters {
	position: absolute; left: 0; top: 0;
	min-height: 100%;
	z-index: 3;
}
.CodeMirror-gutter {
	white-space: normal;
	height: 100%;
	display: inline-block;
	vertical-align: top;
	margin-bottom: -30px;
}
.CodeMirror-gutter-wrapper {
	position: absolute;
	z-index: 4;
	background: none !important;
	border: none !important;
}
.CodeMirror-gutter-background {
	position: absolute;
	top: 0; bottom: 0;
	z-index: 4;
}
.CodeMirror-gutter-elt {
	position: absolute;
	cursor: default;
	z-index: 4;
}
.CodeMirror-gutter-wrapper {
	-webkit-user-select: none;
	-moz-user-select: none;
	user-select: none;
}

.CodeMirror-lines {
	cursor: text;
	min-height: 1px; /* prevents collapsing before first draw */
}
.CodeMirror pre {
	/* Reset some styles that the rest of the page might have set */
	-moz-border-radius: 0; -webkit-border-radius: 0; border-radius: 0;
	border-width: 0;
	background: transparent;
	font-family: inherit;
	font-size: inherit;
	margin: 0;
	white-space: pre;
	word-wrap: normal;
	line-height: inherit;
	color: inherit;
	z-index: 2;
	position: relative;
	overflow: visible;
	-webkit-tap-highlight-color: transparent;
	-webkit-font-variant-ligatures: none;
	font-variant-ligatures: none;
}
.CodeMirror-wrap pre {
	word-wrap: break-word;
	white-space: pre-wrap;
	word-break: normal;
}

.CodeMirror-linebackground {
	position: absolute;
	left: 0; right: 0; top: 0; bottom: 0;
	z-index: 0;
}

.CodeMirror-linewidget {
	position: relative;
	z-index: 2;
	overflow: auto;
}

.CodeMirror-widget {}

.CodeMirror-code {
	outline: none;
}

/* Force content-box sizing for the elements where we expect it */
.CodeMirror-scroll,
.CodeMirror-sizer,
.CodeMirror-gutter,
.CodeMirror-gutters,
.CodeMirror-linenumber {
	-moz-box-sizing: content-box;
	box-sizing: content-box;
}

.CodeMirror-measure {
	position: absolute;
	width: 100%;
	height: 0;
	overflow: hidden;
	visibility: hidden;
}

.CodeMirror-cursor {
	position: absolute;
	pointer-events: none;
}
.CodeMirror-measure pre { position: static; }

div.CodeMirror-cursors {
	visibility: hidden;
	position: relative;
	z-index: 3;
}
div.CodeMirror-dragcursors {
	visibility: visible;
}

.CodeMirror-focused div.CodeMirror-cursors {
	visibility: visible;
}

.CodeMirror-selected { background: #d9d9d9; }
.CodeMirror-focused .CodeMirror-selected { background: #d7d4f0; }
.CodeMirror-crosshair { cursor: crosshair; }
.CodeMirror-line::selection, .CodeMirror-line > span::selection, .CodeMirror-line > span > span::selection { background: #d7d4f0; .xf-uix_textSelection();}
.CodeMirror-line::-moz-selection, .CodeMirror-line > span::-moz-selection, .CodeMirror-line > span > span::-moz-selection { background: #d7d4f0; .xf-uix_textSelection();}

.cm-searching {
	background: #ffa;
	background: rgba(255, 255, 0, .4);
}

/* Used to force a border model for a node */
.cm-force-border { padding-right: .1px; }

@media print {
	/* Hide the cursor when printing */
	.CodeMirror div.CodeMirror-cursors {
		visibility: hidden;
	}
}

/* See issue #2901 */
.cm-tab-wrap-hack:after { content: \'\'; }

/* Help users use markselection to safely style text background */
span.CodeMirror-selectedtext { background: none; }

/* Simple scrollbars */

.CodeMirror-simplescroll-horizontal div, .CodeMirror-simplescroll-vertical div {
	position: absolute;
	background: #ccc;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	border: 1px solid #bbb;
	border-radius: 2px;
}

.CodeMirror-simplescroll-horizontal, .CodeMirror-simplescroll-vertical {
	position: absolute;
	z-index: 6;
	background: #eee;
}

.CodeMirror-simplescroll-horizontal {
	bottom: 0; left: 0;
	height: 8px;
}
.CodeMirror-simplescroll-horizontal div {
	bottom: 0;
	height: 100%;
}

.CodeMirror-simplescroll-vertical {
	right: 0; top: 0;
	width: 8px;
}
.CodeMirror-simplescroll-vertical div {
	right: 0;
	width: 100%;
}


.CodeMirror-overlayscroll .CodeMirror-scrollbar-filler, .CodeMirror-overlayscroll .CodeMirror-gutter-filler {
	display: none;
}

.CodeMirror-overlayscroll-horizontal div, .CodeMirror-overlayscroll-vertical div {
	position: absolute;
	background: #bcd;
	border-radius: 3px;
}

.CodeMirror-overlayscroll-horizontal, .CodeMirror-overlayscroll-vertical {
	position: absolute;
	z-index: 6;
}

.CodeMirror-overlayscroll-horizontal {
	bottom: 0; left: 0;
	height: 6px;
}
.CodeMirror-overlayscroll-horizontal div {
	bottom: 0;
	height: 100%;
}

.CodeMirror-overlayscroll-vertical {
	right: 0; top: 0;
	width: 6px;
}
.CodeMirror-overlayscroll-vertical div {
	right: 0;
	width: 100%;
}

/* Dialog add-on */

.CodeMirror-dialog {
	position: absolute;
	left: 0; right: 0;
	background: inherit;
	z-index: 15;
	padding: .1em .8em;
	overflow: hidden;
	color: inherit;
}

.CodeMirror-dialog-top {
	border-bottom: 1px solid #eee;
	top: 0;
}

.CodeMirror-dialog-bottom {
	border-top: 1px solid #eee;
	bottom: 0;
}

.CodeMirror-dialog input {
	border: none;
	outline: none;
	background: transparent;
	width: 20em;
	color: inherit;
}

.CodeMirror-dialog button {
	font-size: 70%;
}

.CodeMirror-fullscreen {
	position: fixed;
	top: 0; left: 0; right: 0; bottom: 0;
	height: auto;
	z-index: @zIndex-9;
}

/* XF-RTL:enable */';
	return $__finalCompiled;
}
);