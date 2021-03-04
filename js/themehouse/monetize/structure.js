var thmonetize = thmonetize || {};

!function($, window, document, _undefined)
{
	"use strict";

	thmonetize.NoticeOverlay = XF.Element.newHandler({
        options: {
			focusShow: true,
			dismissible: true
        },

		init: function() {
            var $overlay = this.getOverlayHtml();
			if ($overlay) {
                $overlay = new XF.Overlay($overlay, {
                    backdropClose: this.options.dismissible,
                    escapeClose: this.options.dismissible,
                    focusShow: this.options.focusShow
                });
                $overlay.show();
			}
        },

		getOverlayHtml: function() {
            var $overlay = this.$target;

			if ($overlay && $overlay.length && !$overlay.is('.overlay')) {
				$overlay = XF.getOverlayHtml({
                    html: $overlay,
                    dismissible: this.options.dismissible
                });
			}

			return ($overlay && $overlay.length) ? $overlay : null;
        },
    });

    XF.Element.register('thmonetize_notice-overlay', 'thmonetize.NoticeOverlay');
}
(jQuery, window, document);