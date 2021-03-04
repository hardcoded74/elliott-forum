/*
 * XenForo sidebar.min.js
 * Copyright 2010-2016 XenForo Ltd.
 * Released under the XenForo License Agreement: http://xenforo.com/license-agreement
 */
(function(c,d,h){XenForo.FixedSidebar=function(a){var e=c(d),b=h.documentElement,f=parseInt(a.data("cutoff"),10),g=function(){f&&b.clientWidth<f?a.css("position",""):b.scrollWidth>b.clientWidth||a.offset().top+a.height()>e.scrollTop()+e.height()?a.css("position",""):a.css("position","fixed")};c(d).resize(g);g()};XenForo.register(".FixedSidebar","XenForo.FixedSidebar")})(jQuery,this,document);
