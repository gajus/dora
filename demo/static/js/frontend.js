$(function () {
	var index = [],
		highlightedPage,
		highlight;

	$('#examples').children().each(function () {
		index.push( [$(this).position().top, $(this).position().top + $(this).outerHeight(true)] );
	});

	$(document).on('scroll', function () {
		var i = index.length,
			offset = $(document).scrollTop() + 200;

		while (i--) {
			if (offset >= index[i][0] && offset <= index[i][1]) {
				highlight(i + 1);

				break;
			}
		}
		// 
	});

	highlight = function (i) {
		if (highlightedPage && highlightedPage == i) {
			return;
		}

		highlightedPage = i;

		$('#sidebar .nav li').removeClass('active').eq(i - 1).addClass('active');
	};

	$('#sidebar .nav').on('click', 'li', function () {
		var i = $(this).index(),
			page = $('#examples').children().eq(i);

		//console.log(page.position().top);

		$('body').animate({scrollTop: page.position().top}, 500);
	});
});

$(function () {
	return;
	/*\
	 |*|
	 |*|  :: cookies.js ::
	 |*|
	 |*|  A complete cookies reader/writer framework with full unicode support.
	 |*|
	 |*|  https://developer.mozilla.org/en-US/docs/DOM/document.cookie
	 |*|
	 |*|  Syntaxes:
	 |*|
	 |*|  * docCookies.setItem(name, value[, end[, path[, domain[, secure]]]])
	 |*|  * docCookies.getItem(name)
	 |*|  * docCookies.removeItem(name[, path])
	 |*|  * docCookies.hasItem(name)
	 |*|  * docCookies.keys()
	 |*|
	 \*/
	 
	var docCookies = {
	  getItem: function (sKey) {
	    if (!sKey || !this.hasItem(sKey)) { return null; }
	    return unescape(document.cookie.replace(new RegExp("(?:^|.*;\\s*)" + escape(sKey).replace(/[\-\.\+\*]/g, "\\$&") + "\\s*\\=\\s*((?:[^;](?!;))*[^;]?).*"), "$1"));
	  },
	  setItem: function (sKey, sValue, vEnd, sPath, sDomain, bSecure) {
	    if (!sKey || /^(?:expires|max\-age|path|domain|secure)$/i.test(sKey)) { return; }
	    var sExpires = "";
	    if (vEnd) {
	      switch (vEnd.constructor) {
	        case Number:
	          sExpires = vEnd === Infinity ? "; expires=Tue, 19 Jan 2038 03:14:07 GMT" : "; max-age=" + vEnd;
	          break;
	        case String:
	          sExpires = "; expires=" + vEnd;
	          break;
	        case Date:
	          sExpires = "; expires=" + vEnd.toGMTString();
	          break;
	      }
	    }
	    document.cookie = escape(sKey) + "=" + escape(sValue) + sExpires + (sDomain ? "; domain=" + sDomain : "") + (sPath ? "; path=" + sPath : "") + (bSecure ? "; secure" : "");
	  },
	  removeItem: function (sKey, sPath) {
	    if (!sKey || !this.hasItem(sKey)) { return; }
	    document.cookie = escape(sKey) + "=; expires=Thu, 01 Jan 1970 00:00:00 GMT" + (sPath ? "; path=" + sPath : "");
	  },
	  hasItem: function (sKey) {
	    return (new RegExp("(?:^|;\\s*)" + escape(sKey).replace(/[\-\.\+\*]/g, "\\$&") + "\\s*\\=")).test(document.cookie);
	  },
	  keys: /* optional method: you can safely remove it! */ function () {
	    var aKeys = document.cookie.replace(/((?:^|\s*;)[^\=]+)(?=;|$)|^\s*|\s*(?:\=[^;]*)?(?:\1|$)/g, "").split(/\s*(?:\=[^;]*)?;\s*/);
	    for (var nIdx = 0; nIdx < aKeys.length; nIdx++) { aKeys[nIdx] = unescape(aKeys[nIdx]); }
	    return aKeys;
	  }
	};
	
	$('#tab-navigation li').on('click', function () {
		var index = $(this).index(),
			tabs = $('#examples .example');
			
		docCookies.setItem('thorax-tab', index);
		
		$(this).addClass('active').siblings().removeClass('active');
		
		$('#examples .example').each(function () {
			$(this).find('.tabs .tab').eq( index ).show().siblings().hide();
		});
	});
	
	if (docCookies.getItem('thorax-tab') !== null) {
		$('#tab-navigation li').eq(parseInt(docCookies.getItem('thorax-tab'), 10)).trigger('click');
	}
});