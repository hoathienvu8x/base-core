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
})(jQuery);
