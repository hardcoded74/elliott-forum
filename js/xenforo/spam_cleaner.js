/*
 * XenForo spam_cleaner.min.js
 * Copyright 2010-2016 XenForo Ltd.
 * Released under the XenForo License Agreement: http://xenforo.com/license-agreement
 */
(function(d){XenForo.SpamCleaner=function(b){var c=b.closest(".xenOverlay");c.length&&b.submit(function(e){e.preventDefault();XenForo.ajax(b.attr("action"),b.serializeArray(),function(a){if(XenForo.hasResponseError(a))return!1;XenForo.hasTemplateHtml(a)?new XenForo.ExtLoader(a,function(){b.slideUp(XenForo.speed.fast,function(){b.remove();$template=d(a.templateHtml).prepend('<h2 class="heading">'+a.title+"</h2>");$template.xfInsert("appendTo",c,"slideDown",XenForo.speed.fast);c.data("overlay").getTrigger().bind("onClose",
function(){d(this).data("XenForo.OverlayTrigger")&&d(this).data("XenForo.OverlayTrigger").deCache()})})}):(c.data("overlay").close(),a._redirectMessage&&XenForo.alert(a._redirectMessage,"",2E3))})})};XenForo.register(".SpamCleaner","XenForo.SpamCleaner")})(jQuery,this,document);
