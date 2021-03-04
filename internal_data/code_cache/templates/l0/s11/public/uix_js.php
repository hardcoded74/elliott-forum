<?php
// FROM HASH: 243a76a628201bf7d2baa54cfdf0aa28
return array(
'code' => function($__templater, array $__vars, $__extensions = null)
{
	$__finalCompiled = '';
	$__templater->includeJs(array(
		'src' => 'themehouse/global/20180112.js',
		'min' => 'true',
	));
	$__finalCompiled .= '
';
	if ($__templater->func('property', array('uix_borderRadiusJs', ), false)) {
		$__finalCompiled .= '
';
		$__templater->includeJs(array(
			'src' => 'themehouse/' . $__templater->func('property', array('uix_jsPath', ), false) . '/indexRadius.js',
			'min' => 'true',
		));
		$__finalCompiled .= '
';
	} else {
		$__finalCompiled .= '
';
		$__templater->includeJs(array(
			'src' => 'themehouse/' . $__templater->func('property', array('uix_jsPath', ), false) . '/index.js',
			'min' => 'true',
		));
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '
' . $__templater->func('uix_js', array(('themehouse/' . $__templater->func('property', array('uix_jsPath', ), false)) . '/defer.js', true, 'defer', ), true) . '
';
	if ($__templater->func('property', array('uix_navigationType', ), false) == 'sidebarNav') {
		$__finalCompiled .= '
	' . $__templater->func('uix_js', array(('themehouse/' . $__templater->func('property', array('uix_jsPath', ), false)) . '/deferSidebarNav.js', true, 'defer', ), true) . '
';
	}
	$__finalCompiled .= '
';
	if ($__templater->func('property', array('uix_fab', ), false) != 'never') {
		$__finalCompiled .= '
	' . $__templater->func('uix_js', array(('themehouse/' . $__templater->func('property', array('uix_jsPath', ), false)) . '/deferFab.js', true, 'defer', ), true) . '
';
	}
	$__finalCompiled .= '
';
	if ($__templater->func('property', array('uix_categoryCollapse', ), false)) {
		$__finalCompiled .= '
	' . $__templater->func('uix_js', array(('themehouse/' . $__templater->func('property', array('uix_jsPath', ), false)) . '/deferNodesCollapse.js', true, 'defer', ), true) . '
';
	}
	$__finalCompiled .= '
';
	if ($__templater->func('property', array('uix_pageWidthToggle', ), false) != 'disabled') {
		$__finalCompiled .= '
	' . $__templater->func('uix_js', array(('themehouse/' . $__templater->func('property', array('uix_jsPath', ), false)) . '/deferWidthToggle.js', true, 'defer', ), true) . '
';
	}
	$__finalCompiled .= '

';
	$__templater->inlineJs('
	// detect android device. Added to fix the dark pixel bug https://github.com/Audentio/xf2theme-issues/issues/1055
	
	$(document).ready(function() {
		var ua = navigator.userAgent.toLowerCase();
		var isAndroid = ua.indexOf("android") > -1; //&& ua.indexOf("mobile");
	
		if(isAndroid) {
			$(\'html\').addClass(\'device--isAndroid\');
		}	
	})
');
	$__finalCompiled .= '

';
	if ($__templater->func('property', array('uix_clickableThreads', ), false)) {
		$__finalCompiled .= '
';
		$__templater->inlineJs('
	$(document).ready(function() {
		$(\'.structItem--thread\').bind(\'click\', function(e) {
			var target = $(e.target);
			var skip = [\'a\', \'i\', \'input\', \'label\'];
			if (target.length && skip.indexOf(target[0].tagName.toLowerCase()) === -1) {
				var href = $(this).find(\'.structItem-title\').attr(\'uix-data-href\');
				if (e.metaKey || e.cmdKey) {
					e.preventDefault();
					window.open(href, \'_blank\');
				} else {
					window.location = href;
				}
			}
		});
	});
');
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if ($__templater->func('property', array('uix_sidebarMobileCanvas', ), false)) {
		$__finalCompiled .= '
	';
		$__templater->inlineJs('
		$(document).ready(function() {
			var sidebar = $(\'.p-body-sidebar\');
			var backdrop = $(\'.p-body-sidebar [data-ocm-class="offCanvasMenu-backdrop"]\');

			$(\'.uix_sidebarCanvasTrigger\').click(function(e) {
				e.preventDefault();
				sidebar.addClass(\'offCanvasMenu offCanvasMenu--blocks is-active is-transitioning\');
				$(\'body\').addClass(\'sideNav--open\');

				window.setTimeout(function() {
					sidebar.removeClass(\'is-transitioning\');
				}, 250);

				$(\'.uix_sidebarInner\').addClass(\'offCanvasMenu-content\');
				backdrop.addClass(\'offCanvasMenu-backdrop\');
				$(\'body\').addClass(\'is-modalOpen\');
			});

			backdrop.click(function() {
				sidebar.addClass(\'is-transitioning\');
				sidebar.removeClass(\'is-active\');

				window.setTimeout(function() {
					sidebar.removeClass(\'offCanvasMenu offCanvasMenu--blocks is-transitioning\');
					$(\'.uix_sidebarInner\').removeClass(\'offCanvasMenu-content\');
					backdrop.removeClass(\'offCanvasMenu-backdrop\');
					$(\'body\').removeClass(\'is-modalOpen\');
				}, 250);
			})
		});
	');
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	$__templater->inlineJs('
	/****** OFF CANVAS ***/
	$(document).ready(function() {
		var panels = {
			navigation: {
				position: 1
			},
			account: {
				position: 2
			},
			inbox: {
				position: 3
			},
			alerts: {
				position: 4
			}
		};


		var tabsContainer = $(\'.sidePanel__tabs\');

		var activeTab = \'navigation\';

		var activeTabPosition = panels[activeTab].position;

		var generateDirections = function() {
			$(\'.sidePanel__tabPanel\').each(function() {
				var tabPosition = $(this).attr(\'data-content\');
				var activeTabPosition = panels[activeTab].position;

				if (tabPosition != activeTab) {
					if (panels[tabPosition].position < activeTabPosition) {
						$(this).addClass(\'is-left\');
					}

					if (panels[tabPosition].position > activeTabPosition) {
						$(this).addClass(\'is-right\');
					}
				}
			});
		};

		generateDirections();

		$(\'.sidePanel__tab\').click(function() {
			$(tabsContainer).find(\'.sidePanel__tab\').removeClass(\'sidePanel__tab--active\');
			$(this).addClass(\'sidePanel__tab--active\');

			activeTab = $(this).attr(\'data-attr\');

			$(\'.sidePanel__tabPanel\').removeClass(\'is-active\');

			$(\'.sidePanel__tabPanel[data-content="\' + activeTab + \'"]\').addClass(\'is-active\');
			$(\'.sidePanel__tabPanel\').removeClass(\'is-left\').removeClass(\'is-right\');
			generateDirections();
		});
	});

	/******** extra info post toggle ***********/

	$(document).ready(function() {
		XF.thThreadsUserExtraTrigger = XF.Click.newHandler({
			eventNameSpace: \'XFthThreadsUserExtraTrigger\',

			init: function(e) {},

			click: function(e)
			{
				var parent =  this.$target.parents(\'.message-user\');
				var triggerContainer = this.$target.parent(\'.thThreads__userExtra--toggle\');
				var container = triggerContainer.siblings(\'.thThreads__message-userExtras\');
				var child = container.find(\'.message-userExtras\');
				var eleHeight = child.height();
				if (parent.hasClass(\'userExtra--expand\')) {
					container.css({ height: eleHeight });
					parent.toggleClass(\'userExtra--expand\');
					window.setTimeout(function() {
						container.css({ height: \'0\' });
						window.setTimeout(function() {
							container.css({ height: \'\' });
						}, 200);
					}, 17);

				} else {
					container.css({ height: eleHeight });
					window.setTimeout(function() {
						parent.toggleClass(\'userExtra--expand\');
						container.css({ height: \'\' });
					}, 200);
				}
			}
		});

		XF.Click.register(\'ththreads-userextra-trigger\', \'XF.thThreadsUserExtraTrigger\');
	});

	/******** Backstretch images ***********/

	$(document).ready(function() {
		if ( ' . $__templater->func('property', array('uix_backstretch', ), false) . ' ) {

			$("' . $__templater->func('property', array('uix_backstretchSelector', ), false) . '").addClass(\'uix__hasBackstretch\');

			 $("' . $__templater->func('property', array('uix_backstretchSelector', ), false) . '").backstretch([
				 ' . $__templater->filter($__vars['__globals']['uix_backstretchImages'], array(array('raw', array()),), false) . '
		  ], {
				duration: ' . $__templater->func('property', array('uix_backstretchDuration', ), false) . ',
				fade: ' . $__templater->func('property', array('uix_backstretchFade', ), false) . '
			});

			$("' . $__templater->func('property', array('uix_backstretchSelector', ), false) . '").css("zIndex","");
		}
	});

	// sidenav canvas blur fix

	$(document).ready(function(){
		$(\'.p-body-sideNavTrigger .button\').click(function(){
			$(\'body\').addClass(\'sideNav--open\');
		});
	})

	$(document).ready(function(){
		$("[data-ocm-class=\'offCanvasMenu-backdrop\']").click(function(){
			$(\'body\').removeClass(\'sideNav--open\');
		});
	})

	$(document).on(\'editor:start\', function (m, ed) {
		if (typeof (m) !== \'undefined\' && typeof (m.target) !== \'undefined\') {
			var ele = $(m.target);
			if (ele.hasClass(\'js-editor\')) {
				var wrapper = ele.closest(\'.message-editorWrapper\');
				if (wrapper.length) {
					window.setTimeout(function() {
						var innerEle = wrapper.find(\'.fr-element\');
						if (innerEle.length) {
							innerEle.focus(function (e) {
								$(\'html\').addClass(\'uix_editor--focused\')
							});
							innerEle.blur(function (e) {
								$(\'html\').removeClass(\'uix_editor--focused\')
							});
						}
					}, 0);
				}
			}
		}
	});

	// off canvas menu closer keyboard shortcut
	$(document).ready(function() {
		$(document.body).onPassive(\'keyup\', function(e) {
			switch (e.key) {
				case \'Escape\':
					$(\'.offCanvasMenu.is-active .offCanvasMenu-backdrop\').click();
					return;
			}
		});
	});
');
	$__finalCompiled .= '

';
	if ($__templater->func('property', array('uix_parallax', ), false)) {
		$__finalCompiled .= '
	';
		$__templater->inlineJs('
		var parallaxSelector = "' . $__templater->func('property', array('uix_parallaxSelector', ), false) . '"
		var parallaxImage = "' . $__templater->func('base_url', array($__templater->func('property', array('uix_parallaxImage', ), false), ), false) . '"
		var parallaxPosition = "' . $__templater->func('property', array('uix_parallaxPosition', ), false) . '"
		$(parallaxSelector).parallax({imageSrc: parallaxImage, positionY: parallaxPosition});
	');
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	$__templater->inlineJs('
	$(document).ready(function() {
		var uixMegaHovered = false;
		$(\'.uix-navEl--hasMegaMenu\').hoverIntent({
			over: function() {
				if (uixMegaHovered) {
					menu = $(this).attr(\'data-nav-id\');

					$(\'.p-nav\').addClass(\'uix_showMegaMenu\');

					$(\'.uix_megaMenu__content\').removeClass(\'uix_megaMenu__content--active\');

					$(\'.uix_megaMenu__content--\' + menu).addClass(\'uix_megaMenu__content--active\');
				}
			},
			timeout: 200,
		});

		$(\'.p-nav\').mouseenter(function() {
			uixMegaHovered = true;
		});

		$(\'.p-nav\').mouseleave(function() {
			$(this).removeClass(\'uix_showMegaMenu\');
			uixMegaHovered = false;
		});
	});
');
	$__finalCompiled .= '
	
';
	if ($__templater->func('property', array('uix_abridgedSignatures', ), false)) {
		$__finalCompiled .= '
';
		$__compilerTemp1 = '';
		if ($__templater->func('property', array('uix_signatureHoverEnabled', ), false)) {
			$__compilerTemp1 .= '
				$(\'.message-signature--expandable\').hoverIntent({
					over: function() {
						expand($(this, false));
					},
					out: null,
					timeout: 300,
					interval: 300,
				});
			';
		}
		$__templater->inlineJs('
		/******** signature collapse toggle ***********/
	$(window).on(\'load\', function() {
		window.setTimeout(function() {
			var maxHeight = ' . $__templater->func('property', array('uix_signatureMaxHeight', ), false) . ';

			/*** check if expandable ***/
			var eles = [];
	
			$(\'.message-signature\').each(function() {
				var height = $(this).height();
				if (height > maxHeight) {
					eles.push($(this));
				}
			});
	
			for (var i = 0; i < eles.length; i++) {
				eles[i].addClass(\'message-signature--expandable\');
			};

			/**** expand function ***/
			var expand = function(container, canClose) {
				var inner = container.find(\'.bbWrapper\');
				var eleHeight = inner.height();
				var isExpanded = container.hasClass(\'message-signature--expanded\');

				if (isExpanded) {
					if (canClose) {
						container.css({ height: eleHeight });
						container.removeClass(\'message-signature--expanded\');
						window.setTimeout(function() {
							container.css({ height: maxHeight });
							window.setTimeout(function() {
								container.css({ height: \'\' });
							}, 200);
						}, 17);					
					}

				} else {
					container.css({ height: eleHeight });
					window.setTimeout(function() {
						container.addClass(\'message-signature--expanded\');
						container.css({ height: \'\' });
					}, 200);
				}
			}

			/*** handle hover ***/
			' . $__compilerTemp1 . '

			/*** handle click ***/
			$(\'.uix_signatureExpand\').click(function() {
				var container =  $(this).parent(\'.message-signature\');
				expand(container, true);
			});
		}, 0);
	});
');
		$__finalCompiled .= '
';
	}
	$__finalCompiled .= '

';
	if ($__templater->func('property', array('uix_lazyLoadSupport', ), false)) {
		$__finalCompiled .= '
';
		$__templater->inlineJs('
	document.addEventListener("DOMContentLoaded", function() {
  var lazyloadImages;    

  if ("IntersectionObserver" in window) {
    lazyloadImages = document.querySelectorAll(".lazy");
    var imageObserver = new IntersectionObserver(function(entries, observer) {
      entries.forEach(function(entry) {
        if (entry.isIntersecting) {
          var image = entry.target;
          image.src = image.dataset.src;
          image.classList.remove("lazy");
          imageObserver.unobserve(image);
        }
      });
    });

    lazyloadImages.forEach(function(image) {
      imageObserver.observe(image);
    });
  } else {  
    var lazyloadThrottleTimeout;
    lazyloadImages = document.querySelectorAll(".lazy");
    
    function lazyload () {
      if(lazyloadThrottleTimeout) {
        clearTimeout(lazyloadThrottleTimeout);
      }    

      lazyloadThrottleTimeout = setTimeout(function() {
        var scrollTop = window.pageYOffset;
        lazyloadImages.forEach(function(img) {
            if(img.offsetTop < (window.innerHeight + scrollTop)) {
              img.src = img.dataset.src;
              img.classList.remove(\'lazy\');
            }
        });
        if(lazyloadImages.length == 0) { 
          document.removeEventListener("scroll", lazyload);
          window.removeEventListener("resize", lazyload);
          window.removeEventListener("orientationChange", lazyload);
        }
      }, 20);
    }

    document.addEventListener("scroll", lazyload);
    window.addEventListener("resize", lazyload);
    window.addEventListener("orientationChange", lazyload);
  }
})
');
		$__finalCompiled .= '
';
	}
	return $__finalCompiled;
}
);