String.prototype.toUcFirst = function() {
        return this.charAt(0).toUpperCase() + this.slice(1);
};
String.prototype.trim = function() {
        var rtrim = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g;
        return this.replace(rtrim, "").replace(/^\s+|\s+$/g,"");
};
function ut8f2assci(str) {
	str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
	str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
	str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
	str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
	str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
	str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
	str = str.replace(/đ/g, "d");
	str = str.replace(/À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ/g, "A");
	str = str.replace(/È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ/g, "E");
	str = str.replace(/Ì|Í|Ị|Ỉ|Ĩ/g, "I");
	str = str.replace(/Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ/g, "O");
	str = str.replace(/Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ/g, "U");
	str = str.replace(/Ỳ|Ý|Ỵ|Ỷ|Ỹ/g, "Y");
	str = str.replace(/Đ/g, "D");
	str = str.replace(/!|@|%|\^|\*|\(|\)|\+|\=|\<|\>|\?|\/|,|\.|\:|\;|\'|\"|\&|\#|\[|\]|~|\$|_|`|-|{|}|\||\\/g," ");
	str = str.replace(/ + /g," ");
	return str.trim();
}
function make_alias(str, c) {
	c = c || '-';
	str = ut8f2assci(str).toLowerCase();
	str = str.replace(/ {2,}/g,' ').trim();
	str = str.replace(/ /g, c);
	return str;
}
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
	$('input[type="file"]').change( function() {
		var name = $(this).attr('name');
		if (name == 'photo') {
			var data = {
				action : 'avatar'
			};
			var allowFiles = ['image/png','image/jpg','image/gif','image/jpeg'];
		} else {
			var data = {
				action : 'file'
			};
			var allowFiles = ["text/html","text/plain","image/bmp","image/gif","image/jpeg","image/png","image/vnd.microsoft.icon","video/mpeg","video/quicktime","video/x-msvideo","video/x-ms-wmv","audio/mpeg","audio/x-pn-realaudio","audio/x-pn-realaudio-plugin","audio/x-realaudio","audio/x-wav","text/css","application/zip","application/pdf","application/msword","application/octet-stream","application/vnd.ms-excel","application/vnd.ms-powerpoint","application/vnd.wap.wbxml","application/vnd.wap.wmlc","application/vnd.wap.wmlscriptc","application/x-dvi","application/x-futuresplash","application/x-gtar","application/x-gzip","application/x-javascript","application/x-shockwave-flash","application/x-tar","application/xhtml+xml","audio/basic","audio/midi","audio/x-mpegurl","image/tiff","text/rtf","text/vnd.wap.wml","text/vnd.wap.wmlscript","text/xml"];
		}
		var file = this.files[0];
		
	});
	$(document).on('click','a.remove',function(e) {
		
		return false;
	});
	$(document).on('click', 'input[name="chkAll"]', function() {
		$('input[type="checkbox"]').not(':disabled').prop('checked', this.checked);
	});
})(jQuery);
