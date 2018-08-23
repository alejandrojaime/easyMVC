//Añadir eventos móviles
//https://github.com/alejandrojaime/jsMobileEvents/blob/master/version2.js
(function(){
	var el = window;
	el.start = { x: 0, y: 0 };
	el.end = { x: 0, y: 0 };
	window.addEventListener('touchstart', function(e){
		var ev = e || window.event || event;
		el.start.x = ev.screenX || ev.pageX || ev.changedTouches[0].pageX;
		el.start.y = ev.screenY || ev.pageY || ev.changedTouches[0].pageY;
	}, {passive: true});
	window.addEventListener('touchend', function(e){
		var ev = e || window.event || event;
		el.end.x = ev.screenX || ev.pageX || ev.changedTouches[0].pageX;
		el.end.y = ev.screenY || ev.pageY || ev.changedTouches[0].pageY;
		var minimun = 50;
		if (el.end.x < (el.start.x - minimun)) {
			var event = new Event('swipeleft', {'type':'swipeleft', 'bubbles':true, 'cancelable':true});
			ev.target.dispatchEvent(event);
		}else if (el.end.x > (el.start.x + minimun)) {
			var event = new Event('swiperight', {'type':'swiperight', 'bubbles':true, 'cancelable':true});
			ev.target.dispatchEvent(event);
		}else if (el.end.y < el.start.y) {
			var event = new Event('swipedown', {'type':'swipedown', 'bubbles':true, 'cancelable':true});
			ev.target.dispatchEvent(event);
		}else if (el.end.y > el.start.y) {
			var event = new Event('swipetop', {'type':'swipetop', 'bubbles':true, 'cancelable':true});
			ev.target.dispatchEvent(event);
		}else if (el.end.y == el.start.y && el.end.x == el.start.x) {
			var event = new Event('tap', {'type':'tap', 'bubbles':true, 'cancelable':true});
			ev.target.dispatchEvent(event);
		}
	}, {passive: true});
})();