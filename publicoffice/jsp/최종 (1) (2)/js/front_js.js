// gnbMenu
function initNavigation(seq) {
	if (!document.getElementById("gnbMenu")) return true;
	nav = document.getElementById("gnbMenu");
	nav.menu = new Array();
	nav.current = null;
	nav.menuseq = 0;
	navLen = nav.childNodes.length;
	
	allA = nav.getElementsByTagName("li")
	for(k = 0; k < allA.length; k++) {
		allA.item(k).onmouseover = allA.item(k).onfocus = function () {
			nav.isOver = true;
		}
		allA.item(k).onmouseout = allA.item(k).onblur = function () {
			nav.isOver = false;
			setTimeout(function () {
				if (nav.isOver == false) {
					if (nav.menu[seq])
						nav.menu[seq].onmouseover();
					else if(nav.current) {
						menuImg = nav.current.childNodes.item(0);
						menuImg.src = menuImg.src.replace("_on.gif", "_off.gif");
						if (nav.current.submenu)
							nav.current.submenu.style.display = "none";
						nav.current = null;
					}
				}
			}, 1500);
		}
	}
	for (i = 0; i < navLen; i++) {
		navItem = nav.childNodes.item(i);
		if (navItem.tagName != "LI")
			continue;

		navAnchor = navItem.getElementsByTagName("a").item(0);
		navAnchor.submenu = navItem.getElementsByTagName("ul").item(0);

		
		navAnchor.onmouseover = navAnchor.onfocus = function () {
			if (nav.current) {
				menuImg = nav.current.childNodes.item(0);
				menuImg.src = menuImg.src.replace("_on.gif", "_off.gif");
				if (nav.current.submenu)
					nav.current.submenu.style.display = "none";
				nav.current = null;
			}
			if (nav.current != this) {
				menuImg = this.childNodes.item(0);
				menuImg.src = menuImg.src.replace("_off.gif", "_on.gif");
				if (this.submenu) {
					this.submenu.style.display = "block";
					suballA = this.submenu.getElementsByTagName("li");					
					for (i=0; i<suballA.length; i++) {
						if(suballA[i].getElementsByTagName("img").length == 0) continue;

						if (suballA[i].getElementsByTagName("img")[0].src.indexOf("_on.gif") != -1 ) {
							continue;
						}
						suballA[i].onmouseover = function() {
							subImage = this.getElementsByTagName("img")[0];
							if (subImage.src.indexOf("_on.png") != -1) return false;
							subImage.src = subImage.src.replace("_off.gif","_on.gif");
						}
						suballA[i].onfocus = function() {
							subImage = this.getElementsByTagName("img")[0];
							if (subImage.src.indexOf("_on.gif") != -1) return false;
							subImage.src = subImage.src.replace("_off.gif","_on.gif");
						}
						suballA[i].onmouseout = function() {
							subImage = this.getElementsByTagName("img")[0];
							subImage.src = subImage.src.replace("_on.gif", "_off.gif");
						}
						suballA[i].onblur = function() {
							subImage = this.getElementsByTagName("img")[0];
							subImage.src = subImage.src.replace("_on.gif", "_off.gif");
						}
					}
				}
				nav.current = this;
			}
			nav.isOver = true;
		}
		nav.menuseq++;
		nav.menu[nav.menuseq] = navAnchor;
	}
	if (nav.menu[seq])
		nav.menu[seq].onmouseover();
}

function setPNG24(obj) {
	obj.width=obj.height=1;
	obj.className=obj.className.replace(/\bPNG24\b/i,'');
	obj.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+ obj.src +"',sizingMethod='image');"
	obj.src='';
	return '';
	}


/* img over */
function imgMenuOver(containderID) {
	var objwrap = document.getElementById(containderID);
	var imgMenu = objwrap.getElementsByTagName("a");

	for (i=0; i<imgMenu.length; i++) {
		if(imgMenu[i].getElementsByTagName("img").length == 0) continue;

		if (imgMenu[i].getElementsByTagName("img")[0].src.indexOf("_on.gif") != -1 ) {
			continue;
		}
		imgMenu[i].onmouseover = function() {
			subImage = this.getElementsByTagName("img")[0];
			if (subImage.src.indexOf("_on.gif") != -1) return false;
			subImage.src = subImage.src.replace("_off.gif","_on.gif");
		}
		imgMenu[i].onfocus = function() {
			subImage = this.getElementsByTagName("img")[0];
			if (subImage.src.indexOf("_on.gif") != -1) return false;
			subImage.src = subImage.src.replace("_off.gif","_on.gif");
		}
		imgMenu[i].onmouseout = function() {
			subImage = this.getElementsByTagName("img")[0];
			subImage.src = subImage.src.replace("_on.gif", "_off.gif");
		}
		imgMenu[i].onblur = function() {
			subImage = this.getElementsByTagName("img")[0];
			subImage.src = subImage.src.replace("_on.gif", "_off.gif");
		}
	}
}


function embedFlash(id, url, width, height, altText, flashVars, wmode) {
	if (!flashVars) flashVars = '';
	if (!wmode) wmode = 'window';
	if (!altText) altText = '';

	var str = '' +
	'<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,115,0" width="' + width + '" height="' + height + '" id="' + id + '">' +
		'<param name="movie" value="' + url + '" />' +
		'<param name="wmode" value="' + wmode + '" />' +
		'<param name="FlashVars" value="' + flashVars + '" />' +
		'<!--[if !IE]>-->' +
		'<object type="application/x-shockwave-flash" data="' + url + '" width="' + width + '" height="' + height + '" name="' + id + '">' +
			'<param name="wmode" value="' + wmode + '" />' +
			'<param name="FlashVars" value="' + flashVars + '" />' +
		'<!--<![endif]-->' +
			'<div class="alt-content alt-' + id + '">' + altText + '</div>' +
		'<!--[if !IE]>-->' +
		'</object>' +
		'<!--<![endif]-->' +
	'</object>';
	document.write(str);
}


// Tab Content
function initTabMenu(tabContainerID) {
	var tabContainer = document.getElementById(tabContainerID);
	var tabAnchor = tabContainer.getElementsByTagName("a");
	var i = 0;

	for(i=0; i<tabAnchor.length; i++) {
		if (tabAnchor.item(i).className == "tabclick")
			thismenu = tabAnchor.item(i);
		else
			continue;

		thismenu.container = tabContainer;
		thismenu.targetEl = document.getElementById(tabAnchor.item(i).href.split("#")[1]);
		thismenu.targetEl.style.display = "none";
		thismenu.imgEl = thismenu.getElementsByTagName("img").item(0);
		thismenu.onclick = function tabMenuClick() {
			currentmenu = this.container.current;
			if (currentmenu == this)
				return false;

			if (currentmenu) {
				currentmenu.targetEl.style.display = "none";
				if (currentmenu.imgEl) {
					currentmenu.imgEl.src = currentmenu.imgEl.src.replace("_on.gif", "_off.gif");
				} else {
					currentmenu.className = currentmenu.className.replace(" over", "");
				}
			}
			this.targetEl.style.display = "";
			if (this.imgEl) {
				this.imgEl.src = this.imgEl.src.replace("_off.gif", "_on.gif");
			} else {
				this.className += " over";
			}
			this.container.current = this;

			return false;
		};

		if (!thismenu.container.first)
			thismenu.container.first = thismenu;
	}
	if (tabContainer.first)
		tabContainer.first.onclick();
}