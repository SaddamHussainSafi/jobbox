/*!
 * jquery.counterup.js 2.1.0
 *
 * Copyright 2013, Benjamin Intal http://gambit.ph @bfintal
 * Released under the GPL v2 License
 *
 * Amended by Jeremy Paris, Ciro Mattia Gonano and others
 *
 * Date: Feb 24, 2017
 */
!function(t){"use strict";t.fn.counterUp=function(e){var a,n=t.extend({time:400,delay:10,offset:100,beginAt:0,formatter:!1,context:"window",callback:function(){}},e);return this.each((function(){var e=t(this),o={time:t(this).data("counterup-time")||n.time,delay:t(this).data("counterup-delay")||n.delay,offset:t(this).data("counterup-offset")||n.offset,beginAt:t(this).data("counterup-beginat")||n.beginAt,context:t(this).data("counterup-context")||n.context};e.waypoint((function(t){!function(){var t=[],u=o.time/o.delay,r=e.attr("data-num")?e.attr("data-num"):e.text(),i=/[0-9]+,[0-9]+/.test(r),c=((r=r.replace(/,/g,"")).split(".")[1]||[]).length;o.beginAt>r&&(o.beginAt=r);var f=/^(\d{2,3}(?!\d):?){2,3}$/.test(r);if(f){var d=r.split(":"),s=1;for(a=0;d.length>0;)a+=s*parseInt(d.pop(),10),s*=60}o.divider=f?a*u:r*u;for(var l=u;l>=o.beginAt/o.divider;l--){var p=parseFloat(r/u*l).toFixed(c);if(f){p=parseInt(a/u*l);var h=Math.floor(p/3600),m=Math.floor((p-3600*h)/60),g=Math.floor(p%60);p=(h<10?"0"+h:h)+":"+(m<10?"0"+m:m)+":"+(g<10?"0"+g:g)}if(i)for(;/(\d+)(\d{3})/.test(p.toString());)p=p.toString().replace(/(\d+)(\d{3})/,"$1,$2");n.formatter&&(p=n.formatter.call(this,p)),t.unshift(p)}e.data("counterup-nums",t),e.text(o.beginAt),e.data("counterup-func",(function(){e.data("counterup-nums")?(e.html(e.data("counterup-nums").shift()),e.data("counterup-nums").length?setTimeout(e.data("counterup-func"),o.delay):(e.data("counterup-nums",null),e.data("counterup-func",null),n.callback.call(this))):n.callback.call(this)})),setTimeout(e.data("counterup-func"),o.delay)}(),this.destroy()}),{offset:o.offset+"%",context:o.context})}))}}(jQuery);
