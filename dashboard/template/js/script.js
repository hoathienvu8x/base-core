(function($) {
	'use strict';
	$.browser = $.browser || (function() {
		var
		    ua = navigator.userAgent.toLowerCase(),
		    rwebkit = /(webkit)[ \/]([\w.]+)/,
		    ropera = /(opera)(?:.*version)?[ \/]([\w.]+)/,
		    rmsie = /(msie) ([\w.]+)/,
		    rmozilla = /(mozilla)(?:.*? rv:([\w.]+))?/;
		var match = rwebkit.exec(ua) || ropera.exec(ua) || rmsie.exec(ua) || ua.indexOf("compatible") < 0 && rmozilla.exec(ua) || [];
		var browser = {};
		if (match[1]) {
		        browser[match[1]] = true;
		        browser['version'] = match[2] || "0";
    		}
		if (match[1] && match[1] == 'webkit') {
			browser['safari'] = true;
    		}
		return browser;
	})();
	$.isMobile = $.isMobile || (function() {
		if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
			return true;
		}
		return false;
	})();
	$('#nav-wrap').on('touchstart', function(e) {
		$('body').removeClass('fixed');
		$('#aside').removeClass('show');
		$('#nav-wrap').removeClass('nav-wrap-show');
	});
	$('#nav-wrap').on('touchmove', function(e) {
		e.preventDefault();		
	});
	$(document).on('click', '#open-nav', function(e) {
		$('#nav-wrap').addClass('nav-wrap-show');
		$('body').addClass('fixed');
		$('#aside').addClass('show');
	});
})(jQuery);
