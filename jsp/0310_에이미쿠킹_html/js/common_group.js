/* HTML5 for LowerVersion
document.createElement('header');
document.createElement('footer');
document.createElement('section');
document.createElement('aside');
document.createElement('nav');
document.createElement('article');*/
// rollover
function imgRollover(groupID){
	var d=document;
	var Nodes = d.getElementById(groupID).getElementsByTagName("img");
	for ( i=0 ; i < Nodes.length ; i++ ) {
		var Node = Nodes.item(i);
		var Check = Node.src.substring(Node.src.length-10,Node.src.length-7);
		if (Check!="_on") {
				
				Node.onmouseover = function () {
					this.src = this.src.replace("_off", "_on");
				}
				Node.onmouseout = function () {
					this.src = this.src.replace("_on", "_off");
				}
		}
	}
}


//visual
function visual(num) {
	var d=document;
	for ( i=1 ; d.getElementById("visual_btn_0"+i) ; i++ ) {
		var dimg=d.getElementById("visual_img_0"+i),dsub=d.getElementById("visual_btn_0"+i);
		if ( i == num ) {
			dsub.src='../images/main/icon_on.gif';
			if ( dimg!=null ) dimg.style.left="0";
		} else {
			dsub.src='../images/main/icon_off.gif';
			if ( dimg!=null ) dimg.style.left="-3000px";
		}
	}
}

//*****  Äü¸Þ´º ºÎºÐ *****
var isDOM = (document.getElementById ? true : false);
  var isIE4 = ((document.all && !isDOM) ? true : false);
  var isNS4 = (document.layers ? true : false);
  var isNS = navigator.appName == "Netscape";

  var scrollerHeight = 88;
  var puaseBetweenImages = 3000;
  var imageIdx = 0;
  var slide_top;

  function moveRightEdge() {
    var yMenuFrom, yMenuTo, yOffset, timeoutNextCheck;
    var quickMNBox = document.getElementById('slide_top');
    if(isDOM) {
      yMenuFrom = parseInt(quickMNBox.style.top, 10);
      yMenuTo = (isNS ? window.pageYOffset : document.documentElement.scrollTop) + -20;
    }

    timeoutNextCheck = 500;
    if(yMenuFrom != yMenuTo) {
      yOffset = Math.ceil(Math.abs(yMenuTo - yMenuFrom) / 20);
      if(yMenuTo < yMenuFrom) yOffset = -yOffset;
      if(isNS4) quickMNBox.top += yOffset + "px";
      else if(isDOM) quickMNBox.style.top = parseInt (slide_top.style.top, 10) + yOffset + "px";
      timeoutNextCheck = 10;
    }
    setTimeout('moveRightEdge()', timeoutNextCheck);
  }

  function ScrollTop(){
    self.scroll(0,0);
  }

  window.onload = function() {
    if (isDOM) {
      slide_top = document.getElementById('slide_top');
      if(slide_top){

      slide_top.style.top = (isNS ? window.pageYOffset : document.documentElement.scrollTop) + 30 + "px";
      slide_top.style.visibility = "visible";
      moveRightEdge();

      }
    }
  }