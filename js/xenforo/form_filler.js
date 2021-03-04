/*
 * XenForo form_filler.min.js
 * Copyright 2010-2016 XenForo Ltd.
 * Released under the XenForo License Agreement: http://xenforo.com/license-agreement
 */
(function(f){XenForo._FormFiller={};XenForo.FormFillerControl=function(d){var b=d.closest("form"),e=b.data("FormFiller");e||(e=new XenForo.FormFiller(b),b.data("FormFiller",e));e.addControl(d)};XenForo.FormFiller=function(d){function b(a,c){if(XenForo.hasResponseError(c))return!1;f.each(c.formValues,function(a,c){var b=d.find(a);b.length&&(b.is(":checkbox, :radio")?b.prop("checked",c).triggerHandler("click"):b.is("select, input, textarea")&&b.val(c))});a.focus()}function e(a){var c=f(a.target).data("choice")||
f(a.target).val();if(c==="")return!0;g[c]?b(this,g[c]):(i=this,h=!0,j=XenForo.ajax(d.data("form-filler-url"),{choice:c},function(a){g[c]=a;b(i,a)}),j.always(function(){h=!1}))}var g={},i=null,j=null,h=!1;d.on("submit",function(a){h&&(a.preventDefault(),a.stopImmediatePropagation())});this.addControl=function(a){a.click(e)}};XenForo.register(".FormFiller","XenForo.FormFillerControl")})(jQuery,this,document);
