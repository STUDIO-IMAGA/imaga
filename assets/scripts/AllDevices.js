(function () {
	var qs = function (a) {
			return document.querySelector(a);
		},
		t = document.querySelectorAll(".monitorContentGroup path");
	qs(".monitorGroup");
	var f = qs(".monitorScreen"),
		g = qs(".monitorLogo"),
		h = qs(".monitorStand"),
		u = qs(".monitorStandShadow"),
		c = qs(".monitorBottom"),
		v = qs(".monitorEdge"),
		C = document.querySelectorAll(".laptopContentGroup path"),
		w = qs(".laptopContentGroup"),
		x = qs(".laptopGroup"),
		d = qs(".laptopScreen"),
		k = qs(".laptopEdgeLeft"),
		l = qs(".laptopEdgeRight"),
		m = qs(".laptopTrackpad"),
		n = qs(".laptopBase");
	document.querySelectorAll(".tabletContentGroup path");
	var e = qs(".tabletContentGroup"),
		b = qs(".tabletGroup"),
		p = qs(".tabletButton"),
		q = qs(".tabletCamera"),
		y = qs(".tabletScreen"),
		r = qs(".phoneGroup"),
		z = qs(".phoneButton"),
		A = qs(".phoneCamera"),
		a = qs(".phoneSpeaker");
	TweenMax.set([c], {
		transformOrigin: "50% 100%"
	});
	TweenMax.set([h, n, y], {
		transformOrigin: "50% 0%"
	});
	TweenMax.set([g, f, d, m, b, x, p, q, e, z, A, a, w, r], {
		transformOrigin: "50% 50%"
	});
	TweenMax.set([k, l], {
		transformOrigin: "0% 100%"
	});
	TweenMax.set(b, {
		rotation: -90
	});
	var B = new TimelineMax({
		repeat: -1,
		delay: 1,
		yoyo: !1,
		paused: !1
	});
	B.timeScale(2);
	B.from(c, 1, {
		scaleY: 0,
		ease: Power1.easeOut
	}).from(h, 1, {
		y: -70,
		ease: Power1.easeOut
	}, "-=1").from(u, 0.5, {
		alpha: 0,
		ease: Power1.easeIn
	}, "-=0.5").from(v, 1, {
		y: 330
	}, "-=0.25").from(f, 2, {
		y: 330,
		ease: Power1.easeOut
	}, "-=1").staggerFrom(t, 1, {
		scaleX: 0
	}, 0.1, "-=0.51").from(g, 1, {
		scale: 0,
		ease: Back.easeOut.config(2)
	}).staggerTo(t, 1, {
		alpha: 0,
		delay: 2
	}, 0.1).to(f, 1, {
		y: 330,
		ease: Power1.easeIn
	}, "-=1").to(v, 1, {
		y: 330,
		ease: Power1.easeIn
	}, "-=0.75").to(c, 1, {
		scaleX: 0.69,
		y: -23
	}).to(c, 1, {
		scaleY: 0.45,
		alpha: 1
	}, "-=1").set(c, {
		alpha: 0
	}).to(g, 0.5, {
		scale: 0,
		ease: Back.easeIn
	}, "-=1").to(h, 1, {
		y: -120
	}, "-=1.5").to(u, 0.5, {
		alpha: 0
	}, "-=1.5").from(n, 1, {
		alpha: 0
	}, "-=1").from(m, 1, {
		scaleX: 0
	}, "-=1").from(d, 1, {
		scale: 0,
		ease: Back.easeOut.config(0.5)
	}).from(k, 2, {
		skewX: -40,
		scaleY: 0,
		ease: Power3.easeOut
	}, "-=2").from(l, 2, {
		skewX: 40,
		scaleY: 0,
		ease: Power3.easeOut
	}, "-=2").staggerFrom(C, 1, {
		scaleX: 0
	}, 0.1).to(m, 0.3, {
		alpha: 0,
		delay: 2
	}).to(d, 1, {
		scaleX: 0.67
	}).to(d, 1, {
		scaleY: 0.8
	}, "-=1").to(w, 1, {
		alpha: 0,
		scale: 0.5
	}, "-=1").to(n, 1, {
		y: -20,
		scaleX: 0
	}, "-=1").to(k, 1, {
		x: 40,
		transformOrigin: "50% 50%",
		scaleY: 0.85
	}, "-=1").to(l, 1, {
		x: -40,
		transformOrigin: "50% 50%",
		scaleY: 0.85
	}, "-=1").set(x, {
		alpha: 0
	}).from(b, 1, {
		scale: 1.1,
		alpha: 0
	}, "-=1").to(b, 2, {
		rotation: 0,
		delay: 2,
		ease: Anticipate.easeOut
	}).staggerFrom([p, q], 0.5, {
		scale: 0,
		ease: Back.easeOut
	}, 0.1, "-=1").from(e, 2, {
		rotation: 90,
		scaleX: 1.33,
		scaleY: 0.8,
		ease: Power3.easeInOut
	}, "-=0").to([p, q], 0.5, {
		scale: 0,
		delay: 2
	}).to(b, 1, {
		scaleX: 0.45
	}).to(b, 1, {
		scaleY: 0.7
	}, "-=1").to(e, 1, {
		y: -5
	}, "-=1").to(y, 0.5, {
		scaleY: 0.92,
		y: 4
	}, "-=0.5").set(r, {
		alpha: 1
	}).set([b, e], {
		alpha: 0
	}).staggerFrom([z,
		A, a
	], 1, {
		scale: 0,
		ease: Back.easeOut
	}, 0.1).to(r, 2, {
		y: 80,
		delay: 2,
		ease: Back.easeIn.config(2)
	});
	TweenMax.set("svg", {
		visibility: "visible"
	});
})();
