/** @param {jQuery} $ jQuery Object */
!function($, window, document, _undefined)
{
	XenForo.acpNavInit = function()
	{
		var $sidebar = $('#sideNav'),
			$heightTarget = $('#body'),
			$tabsContainer = $('#tabsNav .acpTabs'),
			sidebarActive = $sidebar.hasClass('active'),
			sidebarTransitioning = false,
			transitionSide = XenForo.isRTL() ? 'right' : 'left';

		var animateSide = function(value)
		{
			var o = {};
			o[transitionSide] = value;
			return o;
		};

		var toggleSidebar = function(newValue)
		{
			if (sidebarTransitioning)
			{
				return;
			}

			if (newValue == sidebarActive)
			{
				return;
			}

			sidebarTransitioning = true;

			if (newValue)
			{
				$sidebar.addClass('active');
				recalcSidebarHeight();

				$sidebar.css(transitionSide, -$sidebar.width()).animate(animateSide(0), function()
				{
					$sidebar.css(transitionSide, '');
					sidebarActive = true;
					sidebarTransitioning = false;
				});
			}
			else
			{
				$sidebar.animate(animateSide(-$sidebar.width()), function()
				{
					$sidebar.css(transitionSide, '')
						.removeClass('active');
					sidebarActive = false;
					sidebarTransitioning = false;
				});
			}
		};

		var recalcSidebarHeight = function()
		{
			if (!$heightTarget.length)
			{
				return;
			}

			var sidebarHeight = $sidebar.css('height', '').height(),
				testHeight = Math.max($heightTarget.height(), $(window).height() - $heightTarget.offset().top);

			if (testHeight && sidebarHeight < testHeight)
			{
				$sidebar.css('height', testHeight);
			}
		};

		$(document).on('click', '.AcpSidebarToggler', function(e)
		{
			e.preventDefault();
			toggleSidebar(sidebarActive ? false : true);
		});
		$(document).on('click', '.AcpSidebarCloser', function(e)
		{
			e.preventDefault();
			toggleSidebar(false);
		});

		$(window).resize(function()
		{
			if (sidebarActive)
			{
				recalcSidebarHeight();
			}
		});

		var checkTabsOverflow = function()
		{
			if (!$tabsContainer.length)
			{
				return;
			}

			var tabsContainer = $tabsContainer[0];

			$tabsContainer.removeClass('withNoLinks');

			if (tabsContainer.scrollHeight >= $tabsContainer.height() * 1.1)
			{
				$sidebar.addClass('withSections');
				$tabsContainer.addClass('withNoLinks');
			}
			else
			{
				$sidebar.removeClass('withSections');
			}
		};

		checkTabsOverflow();

		$(window).resize(function()
		{
			checkTabsOverflow();
		});
	};

	// due to the fixed header, need to adjust any anchor scrolls. We'll do this on load to cover the common case.
	var isScrolled = false;
	$(window).on('load', function()
	{
		if (isScrolled || !window.location.hash)
		{
			return;
		}

		var $header = $('#header');
		if ($header.css('position') != 'fixed')
		{
			return;
		}

		var delay = ($.browser.webkit || ($.browser.mozilla && !navigator.userAgent.match(/Trident\//))) ? 0 : 50;

		setTimeout(function() {
			var hash = window.location.hash.replace(/[^a-zA-Z0-9_-]/g, ''),
				$match = hash ? $('#' + hash) : $();

			if ($match.length)
			{
				var $heightLimited = $match.parents().filter(function(index, el)
				{
					var $el = $(el),
						maxHeight = $el.css('max-height');

					if ($el.is('body, html'))
					{
						return false;
					}

					if (maxHeight && maxHeight != 'none')
					{
						var overflow = $el.css('overflow'),
							overflowY = $el.css('overflow-y');

						if (overflow == 'auto' || overflow == 'scroll'
							|| overflowY == 'auto' || overflowY == 'scroll')
						{
							return true;
						}
					}

					return false;
				});

				if (!$heightLimited.length)
				{
					var scrollTo = $match.offset().top - $header.outerHeight();
					$('html, body').animate({scrollTop: scrollTo}, 0);
				}
				else
				{
					$match.get(0).scrollIntoView(true);
				}
			}
		}, delay);
	});

	$(function()
	{
		XenForo.acpNavInit();

		if (window.location.hash)
		{
			// do this after the document is ready as triggering it too early
			// causes the initial hash to trigger a scroll
			$(window).one('scroll', function(e) {
				isScrolled = true;
			});
		}
	});
}
(jQuery, this, document);