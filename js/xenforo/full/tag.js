/** @param {jQuery} $ jQuery Object */
!function($, window, document, _undefined)
{
	XenForo.TagInput = function($element) { this.__construct($element); };
	XenForo.TagInput.prototype =
	{
		__construct: function($input)
		{
			var id = $input.uniqueId().attr('id');

			var extra = $input.data('extra-class');

			$input.tagsInput({
				width: '',
				minInputWidth: '100%',
				maxInputWidth: '100%',
				height: '',
				defaultText: '',
				wrapperExtraClass: 'textCtrl' + (extra ? ' ' + extra : ''),
				removeWithBackspace: true,
				autosize: false,
				unique: true
			});

			var $textInput = $('#' + id + '_tag');

			$textInput.addClass('AcSingle').data('acurl', 'index.php?misc/tag-auto-complete');

			XenForo.create('XenForo.AutoComplete', $textInput);
			$textInput.on('AutoComplete', function(e, v)
			{
				$textInput.val('');
				$input.addTag(v.inserted, {unique: true});
			});

			$textInput.on('paste', function()
			{
				setTimeout(function()
				{
					var tags = $textInput.val().split(',');
					if (tags.length > 1)
					{
						for (var i = 0; i < tags.length; i++)
						{
							tags[i] = $.trim(tags[i]);
							if (tags[i].length)
							{
								$input.addTag(tags[i], {unique: true});
							}
						}

						$textInput.val('');
					}
				}, 0);
			});

			$textInput.closest('form').on('submit AutoValidationBeforeSubmit', function()
			{
				var val = $textInput.val();
				if (val.length)
				{
					$input.addTag(val, {unique: true});
					$textInput.val('');
				}
			});

			$textInput.on('focus', function() {
				$textInput.closest('.textCtrl').addClass('Focus');
			});
			$textInput.on('blur', function() {
				$textInput.closest('.textCtrl').removeClass('Focus');
			});

			if ($input.prop('autofocus'))
			{
				$input.prop('autofocus', false);
				$textInput.prop('autofocus', true);
				$textInput.focus();
			}
		}
	};

	XenForo.TagEditorForm = function($element) { this.__construct($element); };
	XenForo.TagEditorForm.prototype =
	{
		__construct: function($form)
		{
			var redirect = function(e)
			{
				if (e.ajaxData.redirect)
				{
					XenForo.redirect(e.ajaxData.redirect);
				}
			};

			$form.on('AutoValidationComplete', function(e)
			{
				e.preventDefault();

				if (!e.ajaxData.templateHtml || !e.ajaxData.isTagList)
				{
					redirect(e);
					return;
				}

				var $overlay = $form.closest('.xenOverlay');
				if (!$overlay.length || !$overlay.data('overlay'))
				{
					redirect(e);
					return;
				}

				var overlay = $overlay.data('overlay'),
					$trigger = overlay.getTrigger(),
					$tagContainer = $trigger.closest('.TagContainer');
				if (!$tagContainer.length)
				{
					redirect(e);
					return;
				}

				e.preventDefault();

				var $new = $($.parseHTML(e.ajaxData.templateHtml));

				$tagContainer.replaceWith($new);
				$new.parent().xfActivate();

				overlay.close();
			});
		}
	};

	XenForo.register('input.TagInput', 'XenForo.TagInput');
	XenForo.register('form.TagEditorForm', 'XenForo.TagEditorForm');
}
(jQuery, this, document);

/*

 jQuery Tags Input Plugin 1.3.3

 Copyright (c) 2011 XOXCO, Inc

 Modifications for XenForo

 Documentation for this plugin lives here:
 http://xoxco.com/clickable/jquery-tags-input

 Licensed under the MIT license:
 http://www.opensource.org/licenses/mit-license.php

 ben@xoxco.com

 */

(function ($)
{
	var tagsInputClassName = 'taggingInput';

	var delimiter = new Array();
	var tags_callbacks = new Array();
	$.fn.doAutosize = function (o)
	{
		var minWidth = $(this).data('minwidth'),
			maxWidth = $(this).data('maxwidth'),
			val = '',
			input = $(this),
			testSubject = $('#' + $(this).data('tester_id'));

		if (val === (val = input.val()))
		{
			return;
		}

		// Enter new content into testSubject
		var escaped = val.replace(/&/g, '&amp;').replace(/\s/g, ' ').replace(/</g, '&lt;').replace(/>/g, '&gt;');
		testSubject.html(escaped);
		// Calculate new width + whether to change
		var testerWidth = testSubject.width(),
			newWidth = (testerWidth + o.comfortZone) >= minWidth ? testerWidth + o.comfortZone : minWidth,
			currentWidth = input.width(),
			isValidWidthChange = (newWidth < currentWidth && newWidth >= minWidth)
				|| (newWidth > minWidth && newWidth < maxWidth);

		// Animate width
		if (isValidWidthChange)
		{
			input.width(newWidth);
		}


	};
	$.fn.resetAutosize = function (options)
	{
		// alert(JSON.stringify(options));
		var minWidth = $(this).data('minwidth') || options.minInputWidth || $(this).width(),
			maxWidth = $(this).data('maxwidth') || options.maxInputWidth || ($(this).closest('.' + tagsInputClassName).width() - options.inputPadding),
			val = '',
			input = $(this),
			testSubject = $('<tester/>').css({
				position: 'absolute',
				top: -9999,
				left: -9999,
				width: 'auto',
				fontSize: input.css('fontSize'),
				fontFamily: input.css('fontFamily'),
				fontWeight: input.css('fontWeight'),
				letterSpacing: input.css('letterSpacing'),
				whiteSpace: 'nowrap'
			}),
			testerId = $(this).attr('id') + '_autosize_tester';
		if (!$('#' + testerId).length > 0)
		{
			testSubject.attr('id', testerId);
			testSubject.appendTo('body');
		}

		input.data('minwidth', minWidth);
		input.data('maxwidth', maxWidth);
		input.data('tester_id', testerId);
		input.css('width', minWidth);
	};

	$.fn.addTag = function (value, options)
	{
		options = jQuery.extend({focus: false, callback: true}, options);
		this.each(function ()
		{
			var id = $(this).attr('id');

			var tagslist = $(this).val().split(delimiter[id]);
			if (tagslist[0] == '')
			{
				tagslist = new Array();
			}

			value = jQuery.trim(value);

			if (options.unique)
			{
				var skipTag = $(this).tagExist(value);
				if (skipTag == true)
				{
					//Marks fake input as not_valid to let styling it
					$('#' + id + '_tag').addClass('not_valid');
				}
			}
			else
			{
				var skipTag = false;
			}

			if (value != '' && skipTag != true)
			{
				$('<span>').addClass('tag').append(
					$('<span>').text(value).append('&nbsp;&nbsp;'),
					$('<a>', {
						href: '#',
						title: '',
						text: 'x'
					}).click(function ()
					{
						return $('#' + id).removeTag(escape(value));
					})
				).insertBefore('#' + id + '_addTag');

				tagslist.push(value);

				$('#' + id + '_tag').val('');
				if (options.focus)
				{
					$('#' + id + '_tag').focus();
				}
				else
				{
					$('#' + id + '_tag').blur();
				}

				$.fn.tagsInput.updateTagsField(this, tagslist);

				if (options.callback && tags_callbacks[id] && tags_callbacks[id]['onAddTag'])
				{
					var f = tags_callbacks[id]['onAddTag'];
					f.call(this, value);
				}
				if (tags_callbacks[id] && tags_callbacks[id]['onChange'])
				{
					var i = tagslist.length;
					var f = tags_callbacks[id]['onChange'];
					f.call(this, $(this), tagslist[i - 1]);
				}
			}

		});

		return false;
	};

	$.fn.removeTag = function (value)
	{
		value = unescape(value);
		this.each(function ()
		{
			var id = $(this).attr('id');

			var old = $(this).val().split(delimiter[id]);

			$('#' + id + '_tagsinput .tag').remove();
			str = '';
			for (i = 0; i < old.length; i++)
			{
				if (old[i] != value)
				{
					str = str + delimiter[id] + old[i];
				}
			}

			$.fn.tagsInput.importTags(this, str);

			if (tags_callbacks[id] && tags_callbacks[id]['onRemoveTag'])
			{
				var f = tags_callbacks[id]['onRemoveTag'];
				f.call(this, value);
			}
		});

		return false;
	};

	$.fn.tagExist = function (val)
	{
		var id = $(this).attr('id');
		var tagslist = $(this).val().split(delimiter[id]);
		return (jQuery.inArray(val, tagslist) >= 0); //true when tag exists, false when not
	};

	// clear all existing tags and import new ones from a string
	$.fn.importTags = function (str)
	{
		id = $(this).attr('id');
		$('#' + id + '_tagsinput .tag').remove();
		$.fn.tagsInput.importTags(this, str);
	}

	$.fn.tagsInput = function (options)
	{
		var settings = jQuery.extend({
			interactive: true,
			defaultText: 'add a tag',
			minChars: 0,
			width: '300px',
			height: '100px',
			autocomplete: {selectFirst: false },
			wrapperExtraClass: '',
			'hide': true,
			'delimiter': ',',
			'unique': true,
			removeWithBackspace: true,
			autosize: true,
			comfortZone: 20,
			inputPadding: 6 * 2
		}, options);

		this.each(function ()
		{
			if (settings.hide)
			{
				$(this).hide();
			}
			var id = $(this).attr('id');
			if (!id || delimiter[$(this).attr('id')])
			{
				id = $(this).attr('id', 'tags' + new Date().getTime()).attr('id');
			}

			var data = jQuery.extend({
				pid: id,
				real_input: '#' + id,
				holder: '#' + id + '_tagsinput',
				input_wrapper: '#' + id + '_addTag',
				fake_input: '#' + id + '_tag'
			}, settings);

			delimiter[id] = data.delimiter;

			if (settings.onAddTag || settings.onRemoveTag || settings.onChange)
			{
				tags_callbacks[id] = new Array();
				tags_callbacks[id]['onAddTag'] = settings.onAddTag;
				tags_callbacks[id]['onRemoveTag'] = settings.onRemoveTag;
				tags_callbacks[id]['onChange'] = settings.onChange;
			}

			var markup = '<div id="' + id + '_tagsinput" class="' + tagsInputClassName + ' ' + settings.wrapperExtraClass + '"><div id="' + id + '_addTag" class="addTag">';

			if (settings.interactive)
			{
				markup = markup + '<input id="' + id + '_tag" value="" data-default="' + settings.defaultText + '" />';
			}

			markup = markup + '</div><div class="tagsClear"></div></div>';

			$(markup).insertAfter(this);

			$(data.holder).css('width', settings.width);
			$(data.holder).css('min-height', settings.height);
			$(data.holder).css('height', '100%');

			if ($(data.real_input).val() != '')
			{
				$.fn.tagsInput.importTags($(data.real_input), $(data.real_input).val());
			}
			if (settings.interactive)
			{
				$(data.fake_input).val($(data.fake_input).attr('data-default'));
				$(data.fake_input).resetAutosize(settings);

				$(data.holder).bind('click', data, function (event)
				{
					$(event.data.fake_input).focus();
				});

				$(data.fake_input).bind('focus', data, function (event)
				{
					if ($(event.data.fake_input).val() == $(event.data.fake_input).attr('data-default'))
					{
						$(event.data.fake_input).val('');
					}
				});

				if (settings.autocomplete_url != undefined)
				{
					autocomplete_options = {source: settings.autocomplete_url};
					for (attrname in settings.autocomplete)
					{
						autocomplete_options[attrname] = settings.autocomplete[attrname];
					}

					if (jQuery.Autocompleter !== undefined)
					{
						$(data.fake_input).autocomplete(settings.autocomplete_url, settings.autocomplete);
						$(data.fake_input).bind('result', data, function (event, data, formatted)
						{
							if (data)
							{
								$('#' + id).addTag(data[0] + "", {focus: true, unique: (settings.unique)});
							}
						});
					}
					else if (jQuery.ui.autocomplete !== undefined)
					{
						$(data.fake_input).autocomplete(autocomplete_options);
						$(data.fake_input).bind('autocompleteselect', data, function (event, ui)
						{
							$(event.data.real_input).addTag(ui.item.value, {focus: true, unique: (settings.unique)});
							return false;
						});
					}


				}
				else
				{
					// if a user tabs out of the field, create a new tag
					// this is only available if autocomplete is not used.
					/*$(data.fake_input).bind('blur', data, function (event)
					{
						var d = $(this).attr('data-default');
						if ($(event.data.fake_input).val() != '' && $(event.data.fake_input).val() != d)
						{
							if (event.relatedTarget === null)
							{
								return false;
							}

							if ((event.data.minChars <= $(event.data.fake_input).val().length) && (!event.data.maxChars || (event.data.maxChars >= $(event.data.fake_input).val().length)))
								$(event.data.real_input).addTag($(event.data.fake_input).val(), {focus: true, unique: (settings.unique)});
						}
						else
						{
							$(event.data.fake_input).val($(event.data.fake_input).attr('data-default'));
						}
						return false;
					});*/

				}
				// if user types a comma, create a new tag
				$(data.fake_input).bind('keypress', data, function (event)
				{
					var delimPress = (event.which == event.data.delimiter.charCodeAt(0));

					if (delimPress || event.which == 13)
					{
						if (event.which == 13 && $(event.data.fake_input).val().length == 0)
						{
							// let auto submit work with an empty input
							return true;
						}

						event.preventDefault();

						var $fakeInput = $(event.data.fake_input),
							fakeInput = $fakeInput[0],
							val = $fakeInput.val(),
							valLength = val.length,
							split = false,
							splitPos = null;

						try
						{
							if ('selectionStart' in fakeInput)
							{
								split = (fakeInput.selectionStart < valLength);
								splitPos = fakeInput.selectionStart;
							}
							else if (document.selection)
							{
								fakeInput.focus();
								var sel = document.selection.createRange(),
									selLen = sel.text.length;
								sel.moveStart('character', -valLength);
								splitPos = sel.text.length - selLen;
								split = splitPos < valLength;
							}
						}
						catch (e) {}

						if (split && delimPress)
						{
							var newTag = val.substr(0, splitPos),
								suffix = val.substr(splitPos);

							$(event.data.real_input).addTag(newTag, {focus: true, unique: (settings.unique)});
							$fakeInput.val($.trim(suffix));
							if (fakeInput.setSelectionRange)
							{
								fakeInput.setSelectionRange(0, 0);
							}
						}
						else
						{
							if ((event.data.minChars <= valLength) && (!event.data.maxChars || (event.data.maxChars >= valLength)))
							{
								$(event.data.real_input).addTag(val, {focus: true, unique: (settings.unique)});
							}
						}

						$fakeInput.resetAutosize(settings);
						return false;
					}
					else if (event.data.autosize)
					{
						$(event.data.fake_input).doAutosize(settings);

					}
				});

				/*$(data.fake_input).bind('change', data, function(event)
				{
					if ((event.data.minChars <= $(event.data.fake_input).val().length) && (!event.data.maxChars || (event.data.maxChars >= $(event.data.fake_input).val().length)))
						$(event.data.real_input).addTag($(event.data.fake_input).val(), {focus: true, unique: (settings.unique)});
					$(event.data.fake_input).resetAutosize(settings);
					return false;
				});*/

				//Delete last tag on backspace
				data.removeWithBackspace && $(data.fake_input).bind('keydown', function (event)
				{
					if (event.keyCode == 8 && $(this).val() == '')
					{
						event.preventDefault();
						var last_tag = $(this).closest('.' + tagsInputClassName).find('.tag:last').text();
						var id = $(this).attr('id').replace(/_tag$/, '');
						last_tag = last_tag.replace(/[\s]+x$/, '');
						$('#' + id).removeTag(escape(last_tag));
						$(this).trigger('focus');
					}
				});
				$(data.fake_input).blur();

				//Removes the not_valid class when user changes the value of the fake input
				if (data.unique)
				{
					$(data.fake_input).keydown(function (event)
					{
						if (event.keyCode == 8 || String.fromCharCode(event.which).match(/\w+|[áéíóúÁÉÍÓÚñÑ,/]+/))
						{
							$(this).removeClass('notValid');
						}
					});
				}
			} // if settings.interactive
		});

		return this;

	};

	$.fn.tagsInput.updateTagsField = function (obj, tagslist)
	{
		var id = $(obj).attr('id');
		$(obj).val(tagslist.join(delimiter[id]));
	};

	$.fn.tagsInput.importTags = function (obj, val)
	{
		$(obj).val('');
		var id = $(obj).attr('id');
		var tags = val.split(delimiter[id]);
		for (i = 0; i < tags.length; i++)
		{
			$(obj).addTag(tags[i], {focus: false, callback: false});
		}
		if (tags_callbacks[id] && tags_callbacks[id]['onChange'])
		{
			var f = tags_callbacks[id]['onChange'];
			f.call(obj, obj, tags[i]);
		}
	};

})(jQuery);