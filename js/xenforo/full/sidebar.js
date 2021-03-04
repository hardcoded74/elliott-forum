/** @param {jQuery} $ jQuery Object */
!function($, window, document, _undefined)
{
	/**
	 * Allow a sidebar to remain fixed when scrolling, if the window size allows it
	 *
	 * @param jQuery .FixedSidebar
	 */
	XenForo.FixedSidebar = function($sidebar)
	{
		var $window = $(window),
			docEl = document.documentElement,
			cutOff = parseInt($sidebar.data('cutoff'), 10),
			handleResize = function()
			{
				if (cutOff && docEl.clientWidth < cutOff)
				{
					$sidebar.css('position', '');
					return;
				}

				if (docEl.scrollWidth > docEl.clientWidth || $sidebar.offset().top + $sidebar.height() > $window.scrollTop() + $window.height())
				{
					$sidebar.css('position', '');
				}
				else
				{
					$sidebar.css('position', 'fixed');
				}
			};

		$(window).resize(handleResize);

		handleResize();
	};

	// *********************************************************************

	// Register fixed sidebar handler
	XenForo.register('.FixedSidebar', 'XenForo.FixedSidebar');
}
(jQuery, this, document);