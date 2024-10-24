/*!
 * fullPage 2.9.5 - Extensions 0.1.1
 * https://github.com/alvarotrigo/fullPage.js
 * @license http://alvarotrigo.com/fullPage/extensions/#license
 *
 * Copyright (C) 2015 alvarotrigo.com - A project by Alvaro Trigo
 */
!function(e, n) {
    "use strict";
    "function" == typeof define && define.amd ? define(["jquery"], function(t) {
        return n(t, e, e.document, e.Math)
    }) : "object" == typeof exports && exports ? module.exports = n(require("jquery"), e, e.document, e.Math) : n(jQuery, e, e.document, e.Math)
}("undefined" != typeof window ? window : this, function(e, n, t, o, i) {
    "use strict";
    var r = "fullpage-wrapper"
      , a = "." + r
      , l = "fp-responsive"
      , s = "fp-notransition"
      , c = "fp-destroyed"
      , d = "fp-enabled"
      , f = "fp-viewing"
      , u = "active"
      , p = "." + u
      , v = "fp-completely"
      , h = "." + v
      , g = ".section"
      , m = "fp-section"
      , S = "." + m
      , w = S + p
      , y = S + ":first"
      , b = S + ":last"
      , x = "fp-tableCell"
      , C = "." + x
      , T = "fp-auto-height"
      , A = "fp-normal-scroll"
      , M = "fp-nav"
      , k = "#" + M
      , O = "fp-tooltip"
      , R = "." + O
      , z = "fp-show-active"
      , I = ".slide"
      , L = "fp-slide"
      , E = "." + L
      , H = E + p
      , B = "fp-slides"
      , D = "." + B
      , P = "fp-slidesContainer"
      , Y = "." + P
      , F = "fp-table"
      , W = "fp-slidesNav"
      , X = "." + W
      , V = X + " a"
      , Z = "fp-controlArrow"
      , N = "." + Z
      , j = "fp-prev"
      , q = "." + j
      , G = Z + " " + j
      , U = N + q
      , Q = "fp-next"
      , J = "." + Q
      , _ = Z + " " + Q
      , K = N + J
      , $ = e(n)
      , ee = e(t);
    e.fn.fullpage = function(Z) {
        function q(n, t) {
            n || at(0),
            gt("autoScrolling", n, t);
            var o = e(w);
            Z.autoScrolling && !Z.scrollBar ? (wt.css({
                overflow: "hidden",
                height: "100%"
            }),
            Q(Gt.recordHistory, "internal"),
            Rt.css({
                "-ms-touch-action": "none",
                "touch-action": "none"
            }),
            o.length && at(o.position().top)) : (wt.css({
                overflow: "visible",
                height: "initial"
            }),
            Q(!1, "internal"),
            Rt.css({
                "-ms-touch-action": "",
                "touch-action": ""
            }),
            ft(Rt),
            o.length && wt.scrollTop(o.position().top)),
            Rt.trigger("setAutoScrolling", [n])
        }
        function Q(e, n) {
            gt("recordHistory", e, n)
        }
        function J(e, n) {
            "internal" !== n && Z.fadingEffect && bt.fadingEffect && bt.fadingEffect.update(e),
            gt("scrollingSpeed", e, n)
        }
        function ne(e, n) {
            gt("fitToSection", e, n)
        }
        function te(e) {
            Z.lockAnchors = e
        }
        function oe(e) {
            e ? (Kn(),
            $n()) : (_n(),
            et())
        }
        function ie(n, t) {
            "undefined" != typeof t ? (t = t.replace(/ /g, "").split(","),
            e.each(t, function(e, t) {
                st(n, t, "m")
            })) : (st(n, "all", "m"),
            n ? (oe(!0),
            ut("dragAndMove") && "mouseonly" !== Z.dragAndMove || nt()) : (oe(!1),
            tt()))
        }
        function re(n, t) {
            "undefined" != typeof t ? (t = t.replace(/ /g, "").split(","),
            e.each(t, function(e, t) {
                st(n, t, "k")
            })) : (st(n, "all", "k"),
            Z.keyboardScrolling = n)
        }
        function ae() {
            var n = e(w).prev(S);
            n.length || !Z.loopTop && !Z.continuousVertical || (n = e(S).last()),
            n.length && qe(n, null, !0)
        }
        function le() {
            var n = e(w).next(S);
            n.length || !Z.loopBottom && !Z.continuousVertical || (n = e(S).first()),
            n.length && qe(n, null, !1)
        }
        function se(e, n) {
            J(0, "internal"),
            ce(e, n),
            J(Gt.scrollingSpeed, "internal")
        }
        function ce(e, n) {
            var t = Xn(e);
            "undefined" != typeof n ? Zn(e, n) : t.length > 0 && qe(t)
        }
        function de(e) {
            Ze("right", e)
        }
        function fe(e) {
            Ze("left", e)
        }
        function ue(n) {
            if (!Rt.hasClass(c)) {
                It = !0,
                zt = $.height(),
                e(S).each(function() {
                    var n = e(this).find(D)
                      , t = e(this).find(E);
                    Z.verticalCentered && e(this).find(C).css("height", Fn(e(this)) + "px"),
                    e(this).css("height", be(e(this)) + "px"),
                    t.length > 1 && Tn(n, n.find(H))
                }),
                Z.scrollOverflow && Dt.createScrollBarForAll();
                var t = e(w)
                  , o = t.index(S);
                o && !ut("fadingEffect") && se(o + 1),
                It = !1,
                e.isFunction(Z.afterResize) && n && Z.afterResize.call(Rt),
                e.isFunction(Z.afterReBuild) && !n && Z.afterReBuild.call(Rt)
            }
        }
        function pe(n) {
            var t = yt.hasClass(l);
            n ? t || (q(!1, "internal"),
            ne(!1, "internal"),
            e(k).hide(),
            yt.addClass(l),
            e.isFunction(Z.afterResponsive) && Z.afterResponsive.call(Rt, n),
            Z.responsiveSlides && bt.responsiveSlides && bt.responsiveSlides.toSections(),
            Rt.trigger("afterResponsive", [n])) : t && (q(Gt.autoScrolling, "internal"),
            ne(Gt.autoScrolling, "internal"),
            e(k).show(),
            yt.removeClass(l),
            e.isFunction(Z.afterResponsive) && Z.afterResponsive.call(Rt, n),
            Z.responsiveSlides && bt.responsiveSlides && bt.responsiveSlides.toSlides(),
            Rt.trigger("afterResponsive", [n]))
        }
        function ve() {
            return {
                options: Z,
                internals: {
                    canScroll: Et,
                    isScrollAllowed: Bt,
                    getDestinationPosition: je,
                    isTouch: Ot,
                    c: cn,
                    getXmovement: Pn,
                    removeAnimation: Ln,
                    getTransforms: lt,
                    lazyLoad: en,
                    addAnimation: In,
                    performHorizontalMove: kn,
                    landscapeScroll: Tn,
                    silentLandscapeScroll: rt,
                    keepSlidesPosition: Ne,
                    silentScroll: at,
                    styleSlides: ye,
                    scrollHandler: Le,
                    getEventsPage: it,
                    getMSPointer: ot,
                    isReallyTouch: Fe,
                    usingExtension: ut,
                    toggleControlArrows: An
                }
            }
        }
        function he() {
            Z.css3 && (Z.css3 = Jn()),
            Z.scrollBar = Z.scrollBar || Z.hybrid,
            Se(),
            we(),
            ie(!0),
            q(Z.autoScrolling, "internal"),
            zn(),
            Qn(),
            "complete" === t.readyState && dn(),
            $.on("load", dn)
        }
        function ge() {
            $.on("scroll", Le).on("hashchange", fn).blur(wn).resize(Rn),
            ee.keydown(pn).keyup(hn).on("click touchstart", k + " a", yn).on("click touchstart", V, bn).on("click", R, vn),
            e(S).on("click touchstart", N, Sn),
            Z.normalScrollElements && (ee.on("mouseenter touchstart", Z.normalScrollElements, function() {
                ie(!1)
            }),
            ee.on("mouseleave touchend", Z.normalScrollElements, function() {
                ie(!0)
            }))
        }
        function me(e) {
            var t = "fp_" + e + "Extension";
            Ut[e] = Z[e + "Key"],
            bt[e] = "undefined" != typeof n[t] ? new n[t] : null,
            bt[e] && bt[e].c(e)
        }
        function Se() {
            var n = Rt.find(Z.sectionSelector);
            Z.anchors.length || (Z.anchors = n.filter("[data-anchor]").map(function() {
                return e(this).data("anchor").toString()
            }).get()),
            Z.navigationTooltips.length || (Z.navigationTooltips = n.filter("[data-tooltip]").map(function() {
                return e(this).data("tooltip").toString()
            }).get())
        }
        function we() {
            Rt.css({
                height: "100%",
                position: "relative"
            }),
            Rt.addClass(r),
            e("html").addClass(d),
            zt = $.height(),
            Rt.removeClass(c),
            Te(),
            pt("parallax", "init"),
            e(S).each(function(n) {
                var t = e(this)
                  , o = t.find(E)
                  , i = o.length;
                xe(t, n),
                Ce(t, n),
                i > 0 ? ye(t, o, i) : Z.verticalCentered && Yn(t)
            }),
            Z.fixedElements && Z.css3 && e(Z.fixedElements).appendTo(yt),
            Z.navigation && Me(),
            ke(),
            Z.fadingEffect && bt.fadingEffect && bt.fadingEffect.apply(),
            Z.scrollOverflow ? Dt = Z.scrollOverflowHandler.init(Z) : ze()
        }
        function ye(n, t, o) {
            var i = 100 * o
              , r = 100 / o;
            t.wrapAll('<div class="' + P + '" />'),
            t.parent().wrap('<div class="' + B + '" />'),
            n.find(Y).css("width", i + "%"),
            o > 1 && (Z.controlArrows && Ae(n),
            Z.slidesNavigation && jn(n, o)),
            t.each(function(n) {
                e(this).css("width", r + "%"),
                Z.verticalCentered && Yn(e(this))
            });
            var a = n.find(H);
            a.length && (0 !== e(w).index(S) || 0 === e(w).index(S) && 0 !== a.index()) ? rt(a, "internal") : t.eq(0).addClass(u)
        }
        function be(e) {
            return Z.offsetSections && bt.offsetSections ? o.round(bt.offsetSections.getWindowHeight(e)) : zt
        }
        function xe(n, t) {
            t || 0 !== e(w).length || n.addClass(u),
            At = e(w),
            n.css("height", be(n) + "px"),
            Z.paddingTop && n.css("padding-top", Z.paddingTop),
            Z.paddingBottom && n.css("padding-bottom", Z.paddingBottom),
            "undefined" != typeof Z.sectionsColor[t] && n.css("background-color", Z.sectionsColor[t]),
            "undefined" != typeof Z.anchors[t] && n.attr("data-anchor", Z.anchors[t])
        }
        function Ce(n, t) {
            "undefined" != typeof Z.anchors[t] && n.hasClass(u) && Bn(Z.anchors[t], t),
            Z.menu && Z.css3 && e(Z.menu).closest(a).length && e(Z.menu).appendTo(yt)
        }
        function Te() {
            Rt.find(Z.sectionSelector).addClass(m),
            Rt.find(Z.slideSelector).addClass(L)
        }
        function Ae(e) {
            e.find(D).after('<div class="' + G + '"></div><div class="' + _ + '"></div>'),
            "#fff" != Z.controlArrowColor && (e.find(K).css("border-color", "transparent transparent transparent " + Z.controlArrowColor),
            e.find(U).css("border-color", "transparent " + Z.controlArrowColor + " transparent transparent")),
            Z.loopHorizontal || e.find(U).hide()
        }
        function Me() {
            yt.append('<div id="' + M + '"><ul></ul></div>');
            var n = e(k);
            n.addClass(function() {
                return Z.showActiveTooltip ? z + " " + Z.navigationPosition : Z.navigationPosition
            });
            for (var t = 0; t < e(S).length; t++) {
                var o = "";
                Z.anchors.length && (o = Z.anchors[t]);
                var i = '<li><a href="#' + o + '"><span></span></a>'
                  , r = Z.navigationTooltips[t];
                "undefined" != typeof r && "" !== r && (i += '<div class="' + O + " " + Z.navigationPosition + '">' + r + "</div>"),
                i += "</li>",
                n.find("ul").append(i)
            }
            e(k).css("margin-top", "-" + e(k).height() / 2 + "px"),
            e(k).find("li").eq(e(w).index(S)).find("a").addClass(u)
        }
        function ke() {
            Rt.find('iframe[src*="youtube.com/embed/"]').each(function() {
                Oe(e(this), "enablejsapi=1")
            })
        }
        function Oe(e, n) {
            var t = e.attr("src");
            e.attr("src", t + Re(t) + n)
        }
        function Re(e) {
            return /\?/.test(e) ? "&" : "?"
        }
        function ze() {
            var n = e(w);
            n.addClass(v),
            en(n),
            nn(n),
            Z.scrollOverflow && Z.scrollOverflowHandler.afterLoad(),
            Ie() && e.isFunction(Z.afterLoad) && Z.afterLoad.call(n, n.data("anchor"), n.index(S) + 1),
            e.isFunction(Z.afterRender) && Z.afterRender.call(Rt)
        }
        function Ie() {
            var e = Xn(un().section);
            return !e.length || e.length && e.index() === At.index()
        }
        function Le() {
            to || (requestAnimationFrame(Ee),
            to = !0)
        }
        function Ee() {
            Rt.trigger("onScroll");
            var n;
            if ((!Z.autoScrolling || Z.scrollBar || ut("dragAndMove")) && !ht()) {
                var i = ut("dragAndMove") ? o.abs(bt.dragAndMove.getCurrentScroll()) : $.scrollTop()
                  , r = (Be(i),
                0)
                  , a = i + $.height() / 2
                  , l = ut("dragAndMove") ? bt.dragAndMove.getDocumentHeight() : yt.height() - $.height()
                  , s = l === i
                  , c = t.querySelectorAll(S);
                if (s)
                    r = c.length - 1;
                else if (i)
                    for (var d = 0; d < c.length; ++d) {
                        var f = c[d];
                        f.offsetTop <= a && (r = d)
                    }
                else
                    r = 0;
                if (n = e(c).eq(r),
                !n.hasClass(u)) {
                    Qt = !0;
                    var p, v, h = e(w), g = h.index(S) + 1, m = Dn(n), y = n.data("anchor"), b = n.index(S) + 1, x = n.find(H);
                    x.length && (v = x.data("anchor"),
                    p = x.index()),
                    Et && (n.addClass(u).siblings().removeClass(u),
                    pt("parallax", "afterLoad"),
                    e.isFunction(Z.onLeave) && Z.onLeave.call(h, g, b, m),
                    e.isFunction(Z.afterLoad) && Z.afterLoad.call(n, y, b),
                    Z.resetSliders && bt.resetSliders && bt.resetSliders.apply({
                        localIsResizing: It,
                        leavingSection: g
                    }),
                    on(h),
                    en(n),
                    nn(n),
                    Bn(y, b - 1),
                    Z.anchors.length && (xt = y),
                    qn(p, v, y, b)),
                    clearTimeout(Wt),
                    Wt = setTimeout(function() {
                        Qt = !1
                    }, 100)
                }
                Z.fitToSection && (clearTimeout(Xt),
                Xt = setTimeout(function() {
                    Z.fitToSection && e(w).outerHeight() <= zt && He()
                }, Z.fitToSectionDelay))
            }
            to = !1
        }
        function He() {
            Et && (It = !0,
            qe(e(w)),
            It = !1)
        }
        function Be(e) {
            var n = e > Jt ? "down" : "up";
            return Jt = e,
            oo = e,
            n
        }
        function De(n) {
            if (Bt.m[n]) {
                var t = "down" === n ? le : ae;
                if (bt.scrollHorizontally && (t = bt.scrollHorizontally.getScrollSection(n, t)),
                Z.scrollOverflow) {
                    var o = Z.scrollOverflowHandler.scrollable(e(w))
                      , i = "down" === n ? "bottom" : "top";
                    if (o.length > 0) {
                        if (!Z.scrollOverflowHandler.isScrolled(i, o))
                            return !0;
                        t()
                    } else
                        t()
                } else
                    t()
            }
        }
        function Pe(e) {
            var n = e.originalEvent;
            Z.autoScrolling && Fe(n) && e.preventDefault()
        }
        function Ye(n) {
            var t = n.originalEvent
              , i = e(t.target).closest(S);
            if (Fe(t)) {
                Z.autoScrolling && n.preventDefault();
                var r = it(t);
                $t = r.y,
                eo = r.x,
                i.find(D).length && o.abs(Kt - eo) > o.abs(_t - $t) ? !Mt && o.abs(Kt - eo) > $.outerWidth() / 100 * Z.touchSensitivity && (Kt > eo ? Bt.m.right && de(i) : Bt.m.left && fe(i)) : Z.autoScrolling && Et && o.abs(_t - $t) > $.height() / 100 * Z.touchSensitivity && (_t > $t ? De("down") : $t > _t && De("up"))
            }
        }
        function Fe(e) {
            return "undefined" == typeof e.pointerType || "mouse" != e.pointerType
        }
        function We(e) {
            var n = e.originalEvent;
            if (Z.fitToSection && wt.stop(),
            Fe(n)) {
                var t = it(n);
                _t = t.y,
                Kt = t.x
            }
        }
        function Xe(e, n) {
            for (var t = 0, i = e.slice(o.max(e.length - n, 1)), r = 0; r < i.length; r++)
                t += i[r];
            return o.ceil(t / n)
        }
        function Ve(t) {
            var i = (new Date).getTime()
              , r = e(h).hasClass(A);
            if (Z.autoScrolling && !Tt && !r) {
                t = t || n.event;
                var a = t.wheelDelta || -t.deltaY || -t.detail
                  , l = o.max(-1, o.min(1, a))
                  , s = "undefined" != typeof t.wheelDeltaX || "undefined" != typeof t.deltaX
                  , c = o.abs(t.wheelDeltaX) < o.abs(t.wheelDelta) || o.abs(t.deltaX) < o.abs(t.deltaY) || !s;
                Ht.length > 149 && Ht.shift(),
                Ht.push(o.abs(a)),
                Z.scrollBar && (t.preventDefault ? t.preventDefault() : t.returnValue = !1);
                var d = i - no;
                if (no = i,
                d > 200 && (Ht = []),
                Et && !vt()) {
                    var f = Xe(Ht, 10)
                      , u = Xe(Ht, 70)
                      , p = f >= u;
                    p && c && De(l < 0 ? "down" : "up")
                }
                return !1
            }
            Z.fitToSection && wt.stop()
        }
        function Ze(n, t) {
            var o = "undefined" == typeof t ? e(w) : t
              , i = o.find(D);
            if (!(!i.length || vt() || Mt || i.find(E).length < 2)) {
                var r = i.find(H)
                  , a = null;
                if (a = "left" === n ? r.prev(E) : r.next(E),
                !a.length) {
                    if (!Z.loopHorizontal)
                        return;
                    a = "left" === n ? r.siblings(":last") : r.siblings(":first")
                }
                Mt = !0,
                Tn(i, a, n)
            }
        }
        function Ne() {
            e(H).each(function() {
                rt(e(this), "internal")
            })
        }
        function je(e) {
            var n = e.position()
              , t = n.top
              , o = ut("dragAndMove") && bt.dragAndMove.isGrabbing ? bt.dragAndMove.isScrollingDown() : n.top > oo
              , i = t - zt + e.outerHeight()
              , r = Z.bigSectionsDestination;
            return e.outerHeight() > zt ? (o || r) && "bottom" !== r || (t = i) : (o || It && e.is(":last-child")) && (t = i),
            Z.offsetSections && bt.offsetSections && (t = bt.offsetSections.getSectionPosition(o, t, e)),
            oo = t,
            t
        }
        function qe(n, t, o) {
            if ("undefined" != typeof n && n.length) {
                var i, r, a = je(n), l = {
                    element: n,
                    callback: t,
                    isMovementUp: o,
                    dtop: a,
                    yMovement: Dn(n),
                    anchorLink: n.data("anchor"),
                    sectionIndex: n.index(S),
                    activeSlide: n.find(H),
                    activeSection: e(w),
                    leavingSection: e(w).index(S) + 1,
                    localIsResizing: It
                };
                l.activeSection.is(n) && !It || Z.scrollBar && $.scrollTop() === l.dtop && !n.hasClass(T) || (l.activeSlide.length && (i = l.activeSlide.data("anchor"),
                r = l.activeSlide.index()),
                e.isFunction(Z.onLeave) && !l.localIsResizing && Z.onLeave.call(l.activeSection, l.leavingSection, l.sectionIndex + 1, l.yMovement) === !1 || (pt("parallax", "apply", l),
                Z.autoScrolling && Z.continuousVertical && "undefined" != typeof l.isMovementUp && (!l.isMovementUp && "up" == l.yMovement || l.isMovementUp && "down" == l.yMovement) && (l = Je(l)),
                ut("scrollOverflowReset") && bt.scrollOverflowReset.setPrevious(l.activeSection),
                l.localIsResizing || on(l.activeSection),
                Z.scrollOverflow && Z.scrollOverflowHandler.beforeLeave(),
                n.addClass(u).siblings().removeClass(u),
                en(n),
                Z.scrollOverflow && Z.scrollOverflowHandler.onLeave(),
                Et = !1,
                qn(r, i, l.anchorLink, l.sectionIndex),
                Ue(l),
                xt = l.anchorLink,
                Bn(l.anchorLink, Ge(l))))
            }
        }
        function Ge(n) {
            return n.wrapAroundElements && n.wrapAroundElements.length ? n.isMovementUp ? e(S).length - 1 : 0 : n.sectionIndex
        }
        function Ue(n) {
            if (Z.css3 && Z.autoScrolling && !Z.scrollBar) {
                var t = "translate3d(0px, -" + o.round(n.dtop) + "px, 0px)";
                Wn(t, !0),
                Z.scrollingSpeed ? (clearTimeout(Yt),
                Yt = setTimeout(function() {
                    Ke(n)
                }, Z.scrollingSpeed)) : Ke(n)
            } else {
                var i = Qe(n);
                e(i.element).animate(i.options, Z.scrollingSpeed, Z.easing).promise().done(function() {
                    Z.scrollBar ? setTimeout(function() {
                        Ke(n)
                    }, 30) : Ke(n)
                })
            }
        }
        function Qe(e) {
            var n = {};
            return Z.autoScrolling && !Z.scrollBar ? (n.options = {
                top: -e.dtop
            },
            n.element = a) : (n.options = {
                scrollTop: e.dtop
            },
            n.element = "html, body"),
            n
        }
        function Je(n) {
            return n.isMovementUp ? n.activeSection.before(n.activeSection.nextAll(S)) : n.activeSection.after(n.activeSection.prevAll(S).get().reverse()),
            at(e(w).position().top),
            Ne(),
            n.wrapAroundElements = n.activeSection,
            n.dtop = n.element.position().top,
            n.yMovement = Dn(n.element),
            n.leavingSection = n.activeSection.index(S) + 1,
            n.sectionIndex = n.element.index(S),
            e(a).trigger("onContinuousVertical", [n]),
            n
        }
        function _e(n) {
            n.wrapAroundElements && n.wrapAroundElements.length && (n.isMovementUp ? e(y).before(n.wrapAroundElements) : e(b).after(n.wrapAroundElements),
            at(e(w).position().top),
            Ne(),
            n.sectionIndex = n.element.index(S),
            n.leavingSection = n.activeSection.index(S) + 1)
        }
        function Ke(n) {
            _e(n),
            e.isFunction(Z.afterLoad) && !n.localIsResizing && Z.afterLoad.call(n.element, n.anchorLink, n.sectionIndex + 1),
            Z.scrollOverflow && Z.scrollOverflowHandler.afterLoad(),
            pt("parallax", "afterLoad"),
            ut("scrollOverflowReset") && bt.scrollOverflowReset.reset(),
            Z.resetSliders && bt.resetSliders && bt.resetSliders.apply(n),
            n.localIsResizing || nn(n.element),
            n.element.addClass(v).siblings().removeClass(v),
            Et = !0,
            e.isFunction(n.callback) && n.callback.call(this)
        }
        function $e(e, n) {
            e.attr(n, e.data(n)).removeAttr("data-" + n)
        }
        function en(n) {
            if (Z.lazyLoading) {
                var t, o = rn(n);
                o.find("img[data-src], img[data-srcset], source[data-src], video[data-src], audio[data-src], iframe[data-src]").each(function() {
                    if (t = e(this),
                    e.each(["src", "srcset"], function(e, n) {
                        var o = t.attr("data-" + n);
                        "undefined" != typeof o && o && $e(t, n)
                    }),
                    t.is("source")) {
                        var n = t.closest("video").length ? "video" : "audio";
                        t.closest(n).get(0).load()
                    }
                })
            }
        }
        function nn(n) {
            var t = rn(n);
            t.find("video, audio").each(function() {
                var n = e(this).get(0);
                n.hasAttribute("data-autoplay") && "function" == typeof n.play && n.play()
            }),
            t.find('iframe[src*="youtube.com/embed/"]').each(function() {
                var n = e(this).get(0);
                n.hasAttribute("data-autoplay") && tn(n),
                n.onload = function() {
                    n.hasAttribute("data-autoplay") && tn(n)
                }
            })
        }
        function tn(e) {
            e.contentWindow.postMessage('{"event":"command","func":"playVideo","args":""}', "*")
        }
        function on(n) {
            var t = rn(n);
            t.find("video, audio").each(function() {
                var n = e(this).get(0);
                n.hasAttribute("data-keepplaying") || "function" != typeof n.pause || n.pause()
            }),
            t.find('iframe[src*="youtube.com/embed/"]').each(function() {
                var n = e(this).get(0);
                /youtube\.com\/embed\//.test(e(this).attr("src")) && !n.hasAttribute("data-keepplaying") && e(this).get(0).contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', "*")
            })
        }
        function rn(n) {
            var t = n.find(H);
            return t.length && (n = e(t)),
            n
        }
        function an(e) {
            function n(e) {
                var n, o, i, r, l, s, c, d = "", f = 0;
                for (e = e.replace(/[^A-Za-z0-9+\/=]/g, ""); f < e.length; )
                    r = a.indexOf(e.charAt(f++)),
                    l = a.indexOf(e.charAt(f++)),
                    s = a.indexOf(e.charAt(f++)),
                    c = a.indexOf(e.charAt(f++)),
                    n = r << 2 | l >> 4,
                    o = (15 & l) << 4 | s >> 2,
                    i = (3 & s) << 6 | c,
                    d += String.fromCharCode(n),
                    64 != s && (d += String.fromCharCode(o)),
                    64 != c && (d += String.fromCharCode(i));
                return d = t(d)
            }
            function t(e) {
                for (var n, t = "", o = 0, i = 0, r = 0; o < e.length; )
                    i = e.charCodeAt(o),
                    i < 128 ? (t += String.fromCharCode(i),
                    o++) : i > 191 && i < 224 ? (r = e.charCodeAt(o + 1),
                    t += String.fromCharCode((31 & i) << 6 | 63 & r),
                    o += 2) : (r = e.charCodeAt(o + 1),
                    n = e.charCodeAt(o + 2),
                    t += String.fromCharCode((15 & i) << 12 | (63 & r) << 6 | 63 & n),
                    o += 3);
                return t
            }
            function o(e) {
                return e
            }
            function i(e) {
                return e.slice(3).slice(0, -3)
            }
            function r(e) {
                var t = e.split("_");
                if (t.length > 1) {
                    var o = t[1]
                      , r = e.replace(i(t[1]), "").split("_")[0]
                      , a = r;
                    return a + "_" + n(o.slice(3).slice(0, -3))
                }
                return i(e)
            }
            var a = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
            return o(r(n(e)))
        }
        function ln() {
            if (t.domain.length) {
                for (var e = t.domain.replace(/^(www\.)/, "").split("."); e.length > 2; ) {
                    e.shift()
                }
                var n = e.join(".");
                return n.replace(/(^\.*)|(\.*$)/g, "")
            }
            return ""
        }
        function sn(e) {
            var n = ln()
              , t = ["MTM0bG9jYWxob3N0MjM0", "MTM0MC4xMjM0", "MTM0anNoZWxsLm5ldDIzNA==", "UDdDQU5ZNlNN"]
              , o = an(t[0])
              , i = an(t[1])
              , r = an(t[2])
              , a = an(t[3])
              , l = [o, i, r].indexOf(n) < 0 && 0 !== n.length
              , s = "undefined" != typeof Ut[e] && Ut[e].length;
            if (!s && l)
                return !1;
            var c = s ? an(Ut[e]) : "";
            c = c.split("_");
            var d = c.length > 1 && c[1].indexOf(e, c[1].length - e.length) > -1
              , f = c[0].indexOf(n, c[0].length - n.length) < 0;
            return !(f && l && a != c[0]) && d || !l
        }
        function cn(n) {
            function t() {
                qt || (o.random() < .5 ? yt.prepend(l) : yt.append(l),
                qt = !0,
                l.bind("destroyed", function() {
                    clearTimeout(r),
                    r = setTimeout(i, 900)
                })),
                e(l).attr("style", an("MTIzei1pbmRleDo5OTk5OTk5O3Bvc2l0aW9uOmZpeGVkO3RvcDoyMHB4O2JvdHRvbTphdXRvO2xlZnQ6MjBweDtyaWdodDphdXRvO2JhY2tncm91bmQ6cmVkO3BhZGRpbmc6N3B4IDE1cHg7Zm9udC1zaXplOjE0cHg7Zm9udC1mYW1pbHk6YXJpYWw7Y29sb3I6I2ZmZjtkaXNwbGF5OmlubGluZS1ibG9jazt0cmFuc2Zvcm06dHJhbnNsYXRlM2QoMCwwLDApO29wYWNpdHk6MTtoZWlnaHQ6YXV0bzt3aWR0aDphdXRvO3pvb206MTttYXJnaW46YXV0bztib3JkZXI6bm9uZTt2aXNpYmlsaXR5OnZpc2libGU7Y2xpcC1wYXRoOm5vbmU7MTIz").replace(/;/g, an("MTIzICFpbXBvcnRhbnQ7MzQ1")))
            }
            function i() {
                qt = !1
            }
            if (ut(n) && bt[n]) {
                var r, a = an("MTIzPGRpdj48YSBocmVmPSJodHRwOi8vYWx2YXJvdHJpZ28uY29tL2Z1bGxQYWdlL2V4dGVuc2lvbnMvIiBzdHlsZT0iY29sb3I6ICNmZmYgIWltcG9ydGFudDsgdGV4dC1kZWNvcmF0aW9uOm5vbmUgIWltcG9ydGFudDsiPlVubGljZW5zZWQgZnVsbFBhZ2UuanMgRXh0ZW5zaW9uPC9hPjwvZGl2PjEyMw=="), l = e("<div/>").html(a).contents();
                sn(n) || (t(),
                setInterval(t, 2e3))
            }
        }
        function dn() {
            var e = un()
              , n = e.section
              , t = e.slide;
            n && (Z.animateAnchor ? Zn(n, t) : se(n, t))
        }
        function fn() {
            if (!Qt && !Z.lockAnchors) {
                var e = un()
                  , n = e.section
                  , t = e.slide
                  , o = "undefined" == typeof xt
                  , i = "undefined" == typeof xt && "undefined" == typeof t && !Mt;
                n.length && (n && n !== xt && !o || i || !Mt && Ct != t) && Zn(n, t)
            }
        }
        function un() {
            var e = n.location.hash
              , t = e.replace("#", "").split("/")
              , o = e.indexOf("#/") > -1;
            return {
                section: o ? "/" + t[1] : decodeURIComponent(t[0]),
                slide: o ? decodeURIComponent(t[2]) : decodeURIComponent(t[1])
            }
        }
        function pn(n) {
            clearTimeout(Vt);
            var t = e(":focus");
            if (!t.is("textarea") && !t.is("input") && !t.is("select") && "true" !== t.attr("contentEditable") && "" !== t.attr("contentEditable") && Z.keyboardScrolling && Z.autoScrolling) {
                var o = n.which
                  , i = [40, 38, 32, 33, 34];
                e.inArray(o, i) > -1 && n.preventDefault(),
                Tt = n.ctrlKey,
                Vt = setTimeout(function() {
                    xn(n)
                }, 150)
            }
        }
        function vn() {
            e(this).prev().trigger("click")
        }
        function hn(e) {
            Lt && (Tt = e.ctrlKey)
        }
        function gn(e) {
            2 == e.which && (io = e.pageY,
            Rt.on("mousemove", Cn))
        }
        function mn(e) {
            2 == e.which && Rt.off("mousemove")
        }
        function Sn() {
            var n = e(this).closest(S);
            e(this).hasClass(j) ? Bt.m.left && fe(n) : Bt.m.right && de(n)
        }
        function wn() {
            Lt = !1,
            Tt = !1
        }
        function yn(n) {
            n.preventDefault();
            var t = e(this).parent().index();
            qe(e(S).eq(t))
        }
        function bn(n) {
            n.preventDefault();
            var t = e(this).closest(S).find(D)
              , o = t.find(E).eq(e(this).closest("li").index());
            Tn(t, o)
        }
        function xn(n) {
            var t = n.shiftKey;
            if (Et || !([37, 39].indexOf(n.which) < 0))
                switch (n.which) {
                case 38:
                case 33:
                    Bt.k.up && ae();
                    break;
                case 32:
                    if (t && Bt.k.up) {
                        ae();
                        break
                    }
                case 40:
                case 34:
                    Bt.k.down && le();
                    break;
                case 36:
                    Bt.k.up && ce(1);
                    break;
                case 35:
                    Bt.k.down && ce(e(S).length);
                    break;
                case 37:
                    Bt.k.left && fe();
                    break;
                case 39:
                    Bt.k.right && de();
                    break;
                default:
                    return
                }
        }
        function Cn(e) {
            Et && (e.pageY < io && Bt.m.up ? ae() : e.pageY > io && Bt.m.down && le()),
            io = e.pageY
        }
        function Tn(n, t, o) {
            var i = n.closest(S)
              , r = {
                slides: n,
                destiny: t,
                direction: o,
                destinyPos: t.position(),
                slideIndex: t.index(),
                section: i,
                sectionIndex: i.index(S),
                anchorLink: i.data("anchor"),
                slidesNav: i.find(X),
                slideAnchor: Un(t),
                prevSlide: i.find(H),
                prevSlideIndex: i.find(H).index(),
                localIsResizing: It
            };
            return r.xMovement = Pn(r.prevSlideIndex, r.slideIndex),
            r.localIsResizing || (Et = !1),
            pt("parallax", "applyHorizontal", r),
            Z.onSlideLeave && !r.localIsResizing && "none" !== r.xMovement && e.isFunction(Z.onSlideLeave) && Z.onSlideLeave.call(r.prevSlide, r.anchorLink, r.sectionIndex + 1, r.prevSlideIndex, r.direction, r.slideIndex) === !1 ? void (Mt = !1) : (t.addClass(u).siblings().removeClass(u),
            r.localIsResizing || (on(r.prevSlide),
            en(t)),
            An(r),
            i.hasClass(u) && !r.localIsResizing && qn(r.slideIndex, r.slideAnchor, r.anchorLink, r.sectionIndex),
            bt.continuousHorizontal && bt.continuousHorizontal.apply(r),
            void (ht() ? Mn(r) : kn(n, r, !0)))
        }
        function An(e) {
            !Z.loopHorizontal && Z.controlArrows && (e.section.find(U).toggle(0 !== e.slideIndex),
            e.section.find(K).toggle(!e.destiny.is(":last-child")))
        }
        function Mn(n) {
            bt.continuousHorizontal && bt.continuousHorizontal.afterSlideLoads(n),
            On(n.slidesNav, n.slideIndex),
            n.localIsResizing || (pt("parallax", "afterSlideLoads"),
            e.isFunction(Z.afterSlideLoad) && Z.afterSlideLoad.call(n.destiny, n.anchorLink, n.sectionIndex + 1, n.slideAnchor, n.slideIndex),
            Et = !0,
            nn(n.destiny)),
            Mt = !1,
            bt.interlockedSlides && bt.interlockedSlides.apply(n)
        }
        function kn(e, n, t) {
            var i = n.destinyPos;
            if (Z.css3) {
                var r = "translate3d(-" + o.round(i.left) + "px, 0px, 0px)";
                In(e.find(Y)).css(lt(r)),
                Ft = setTimeout(function() {
                    t && Mn(n)
                }, Z.scrollingSpeed, Z.easing)
            } else
                e.animate({
                    scrollLeft: o.round(i.left)
                }, Z.scrollingSpeed, Z.easing, function() {
                    t && Mn(n)
                })
        }
        function On(e, n) {
            e.find(p).removeClass(u),
            e.find("li").eq(n).find("a").addClass(u)
        }
        function Rn() {
            if (Rt.trigger("onResize"),
            zn(),
            kt) {
                var n = e(t.activeElement);
                if (!n.is("textarea") && !n.is("input") && !n.is("select")) {
                    var i = $.height();
                    o.abs(i - ro) > 20 * o.max(ro, i) / 100 && (ue(!0),
                    ro = i)
                }
            } else
                clearTimeout(Pt),
                Pt = setTimeout(function() {
                    ue(!0)
                }, 350)
        }
        function zn() {
            var e = Z.responsive || Z.responsiveWidth
              , n = Z.responsiveHeight
              , t = e && $.outerWidth() < e
              , o = n && $.height() < n;
            e && n ? pe(t || o) : e ? pe(t) : n && pe(o)
        }
        function In(e) {
            var n = "all " + Z.scrollingSpeed + "ms " + Z.easingcss3;
            return e.removeClass(s),
            e.css({
                "-webkit-transition": n,
                transition: n
            })
        }
        function Ln(e) {
            return e.addClass(s)
        }
        function En(n, t) {
            Z.navigation && (e(k).find(p).removeClass(u),
            n ? e(k).find('a[href="#' + n + '"]').addClass(u) : e(k).find("li").eq(t).find("a").addClass(u))
        }
        function Hn(n) {
            Z.menu && (e(Z.menu).find(p).removeClass(u),
            e(Z.menu).find('[data-menuanchor="' + n + '"]').addClass(u))
        }
        function Bn(e, n) {
            Hn(e),
            En(e, n)
        }
        function Dn(n) {
            var t = e(w).index(S)
              , o = n.index(S);
            return t == o ? "none" : t > o ? "up" : "down"
        }
        function Pn(e, n) {
            return e == n ? "none" : e > n ? "left" : "right"
        }
        function Yn(e) {
            e.hasClass(F) || e.addClass(F).wrapInner('<div class="' + x + '" style="height:' + Fn(e) + 'px;" />')
        }
        function Fn(e) {
            var n = be(e);
            if (Z.paddingTop || Z.paddingBottom) {
                var t = e;
                t.hasClass(m) || (t = e.closest(S));
                var o = parseInt(t.css("padding-top")) + parseInt(t.css("padding-bottom"));
                n -= o
            }
            return n
        }
        function Wn(e, n) {
            n ? In(Rt) : Ln(Rt),
            clearTimeout(Zt),
            Rt.css(lt(e)),
            Zt = setTimeout(function() {
                Rt.removeClass(s)
            }, 10)
        }
        function Xn(n) {
            if (!n)
                return [];
            var t = Rt.find(S + '[data-anchor="' + n + '"]');
            return t.length || (t = e(S).eq(n - 1)),
            t
        }
        function Vn(e, n) {
            var t = n.find(D)
              , o = t.find(E + '[data-anchor="' + e + '"]');
            return o.length || (o = t.find(E).eq(e)),
            o
        }
        function Zn(e, n) {
            var t = Xn(e);
            t.length && ("undefined" == typeof n && (n = 0),
            e === xt || t.hasClass(u) ? Nn(t, n) : qe(t, function() {
                Nn(t, n)
            }))
        }
        function Nn(e, n) {
            if ("undefined" != typeof n) {
                var t = e.find(D)
                  , o = Vn(n, e);
                o.length && Tn(t, o)
            }
        }
        function jn(e, n) {
            e.append('<div class="' + W + '"><ul></ul></div>');
            var t = e.find(X);
            t.addClass(Z.slidesNavPosition);
            for (var o = 0; o < n; o++)
                t.find("ul").append('<li><a href="#"><span></span></a></li>');
            t.css("margin-left", "-" + t.width() / 2 + "px"),
            t.find("li").first().find("a").addClass(u)
        }
        function qn(e, n, t, o) {
            var i = "";
            Z.anchors.length && !Z.lockAnchors && (e ? ("undefined" != typeof t && (i = t),
            "undefined" == typeof n && (n = e),
            Ct = n,
            Gn(i + "/" + n)) : "undefined" != typeof e ? (Ct = n,
            Gn(t)) : Gn(t)),
            Qn()
        }
        function Gn(e) {
            if (Z.recordHistory)
                location.hash = e;
            else if (kt || Ot)
                n.history.replaceState(i, i, "#" + e);
            else {
                var t = n.location.href.split("#")[0];
                n.location.replace(t + "#" + e)
            }
        }
        function Un(e) {
            var n = e.data("anchor")
              , t = e.index();
            return "undefined" == typeof n && (n = t),
            n
        }
        function Qn() {
            var n = e(w)
              , t = n.find(H)
              , o = Un(n)
              , i = Un(t)
              , r = String(o);
            t.length && (r = r + "-" + i),
            r = r.replace("/", "-").replace("#", "");
            var a = new RegExp("\\b\\s?" + f + "-[^\\s]+\\b","g");
            yt[0].className = yt[0].className.replace(a, ""),
            yt.addClass(f + "-" + r)
        }
        function Jn() {
            var e, o = t.createElement("p"), r = {
                webkitTransform: "-webkit-transform",
                OTransform: "-o-transform",
                msTransform: "-ms-transform",
                MozTransform: "-moz-transform",
                transform: "transform"
            };
            t.body.insertBefore(o, null);
            for (var a in r)
                o.style[a] !== i && (o.style[a] = "translate3d(1px,1px,1px)",
                e = n.getComputedStyle(o).getPropertyValue(r[a]));
            return t.body.removeChild(o),
            e !== i && e.length > 0 && "none" !== e
        }
        function _n() {
            t.addEventListener ? (t.removeEventListener("mousewheel", Ve, !1),
            t.removeEventListener("wheel", Ve, !1),
            t.removeEventListener("MozMousePixelScroll", Ve, !1)) : t.detachEvent("onmousewheel", Ve)
        }
        function Kn() {
            var e, o = "";
            n.addEventListener ? e = "addEventListener" : (e = "attachEvent",
            o = "on");
            var r = "onwheel"in t.createElement("div") ? "wheel" : t.onmousewheel !== i ? "mousewheel" : "DOMMouseScroll";
            "DOMMouseScroll" == r ? t[e](o + "MozMousePixelScroll", Ve, !1) : t[e](o + r, Ve, !1)
        }
        function $n() {
            Rt.on("mousedown", gn).on("mouseup", mn)
        }
        function et() {
            Rt.off("mousedown", gn).off("mouseup", mn)
        }
        function nt() {
            (kt || Ot) && (Z.autoScrolling && yt.off(jt.touchmove).on(jt.touchmove, Pe),
            e(a).off(jt.touchstart).on(jt.touchstart, We).off(jt.touchmove).on(jt.touchmove, Ye))
        }
        function tt() {
            (kt || Ot) && e(a).off(jt.touchstart).off(jt.touchmove)
        }
        function ot() {
            var e;
            return e = n.PointerEvent ? {
                down: "pointerdown",
                move: "pointermove"
            } : {
                down: "MSPointerDown",
                move: "MSPointerMove"
            }
        }
        function it(e) {
            var n = [];
            return n.y = "undefined" != typeof e.pageY && (e.pageY || e.pageX) ? e.pageY : e.touches[0].pageY,
            n.x = "undefined" != typeof e.pageX && (e.pageY || e.pageX) ? e.pageX : e.touches[0].pageX,
            Ot && Fe(e) && Z.scrollBar && "undefined" != typeof e.touches && (n.y = e.touches[0].pageY,
            n.x = e.touches[0].pageX),
            n
        }
        function rt(e, n) {
            J(0, "internal"),
            "undefined" != typeof n && (It = !0),
            Tn(e.closest(D), e),
            "undefined" != typeof n && (It = !1),
            J(Gt.scrollingSpeed, "internal")
        }
        function at(e) {
            var n = o.round(e);
            if (Z.css3 && Z.autoScrolling && !Z.scrollBar) {
                var t = "translate3d(0px, -" + n + "px, 0px)";
                Wn(t, !1)
            } else
                Z.autoScrolling && !Z.scrollBar ? Rt.css("top", -n) : wt.scrollTop(n)
        }
        function lt(e) {
            return {
                "-webkit-transform": e,
                "-moz-transform": e,
                "-ms-transform": e,
                transform: e
            }
        }
        function st(n, t, o) {
            "all" !== t ? Bt[o][t] = n : e.each(Object.keys(Bt[o]), function(e, t) {
                Bt[o][t] = n
            })
        }
        function ct(n) {
            Rt.trigger("destroy", [n]),
            q(!1, "internal"),
            ie(!1),
            re(!1),
            Rt.addClass(c),
            clearTimeout(Ft),
            clearTimeout(Yt),
            clearTimeout(Pt),
            clearTimeout(Wt),
            clearTimeout(Xt),
            $.off("scroll", Le).off("hashchange", fn).off("resize", Rn),
            ee.off("click touchstart", k + " a").off("mouseenter", k + " li").off("mouseleave", k + " li").off("click touchstart", V).off("mouseover", Z.normalScrollElements).off("mouseout", Z.normalScrollElements),
            e(S).off("click touchstart", N),
            ut("dragAndMove") && bt.dragAndMove.destroy(),
            clearTimeout(Ft),
            clearTimeout(Yt),
            n && dt()
        }
        function dt() {
            at(0),
            Rt.find("img[data-src], source[data-src], audio[data-src], iframe[data-src]").each(function() {
                $e(e(this), "src")
            }),
            Rt.find("img[data-srcset]").each(function() {
                $e(e(this), "srcset")
            }),
            e(k + ", " + X + ", " + N).remove(),
            e(S).css({
                height: "",
                "background-color": "",
                padding: ""
            }),
            e(E).css({
                width: ""
            }),
            Rt.css({
                height: "",
                position: "",
                "-ms-touch-action": "",
                "touch-action": ""
            }),
            wt.css({
                overflow: "",
                height: ""
            }),
            e("html").removeClass(d),
            yt.removeClass(l),
            e.each(yt.get(0).className.split(/\s+/), function(e, n) {
                0 === n.indexOf(f) && yt.removeClass(n)
            }),
            e(S + ", " + E).each(function() {
                Z.scrollOverflowHandler && Z.scrollOverflowHandler.remove(e(this)),
                e(this).removeClass(F + " " + u)
            }),
            ft(Rt),
            Rt.find(C + ", " + Y + ", " + D).each(function() {
                e(this).replaceWith(this.childNodes)
            }),
            wt.scrollTop(0);
            var n = [m, L, P];
            e.each(n, function(n, t) {
                e("." + t).removeClass(t)
            })
        }
        function ft(e) {
            return e.css({
                "-webkit-transition": "none",
                transition: "none"
            })
        }
        function ut(e) {
            return null !== Z[e] && "object" == typeof Z[e] ? Z[e].enabled && bt[e] : Z[e] && bt[e]
        }
        function pt(e, n, t) {
            if (ut(e))
                return bt[e][n](t)
        }
        function vt() {
            return ut("dragAndMove") && bt.dragAndMove.isAnimating
        }
        function ht() {
            return ut("dragAndMove") && bt.dragAndMove.isGrabbing
        }
        function gt(e, n, t) {
            Z[e] = n,
            "internal" !== t && (Gt[e] = n)
        }
        function mt() {
            return e("html").hasClass(d) ? void St("error", "Fullpage.js can only be initialized once and you are doing it multiple times!") : (Z.continuousVertical && (Z.loopTop || Z.loopBottom) && (Z.continuousVertical = !1,
            St("warn", "Option `loopTop/loopBottom` is mutually exclusive with `continuousVertical`; `continuousVertical` disabled")),
            Z.scrollBar && Z.scrollOverflow && St("warn", "Option `scrollBar` is mutually exclusive with `scrollOverflow`. Sections with scrollOverflow might not work well in Firefox"),
            !Z.continuousVertical || !Z.scrollBar && Z.autoScrolling || (Z.continuousVertical = !1,
            St("warn", "Scroll bars (`scrollBar:true` or `autoScrolling:false`) are mutually exclusive with `continuousVertical`; `continuousVertical` disabled")),
            Z.scrollOverflow && !Z.scrollOverflowHandler && (Z.scrollOverflow = !1,
            St("error", "The option `scrollOverflow:true` requires the file `scrolloverflow.min.js`. Please include it before fullPage.js.")),
            void e.each(Z.anchors, function(n, t) {
                var o = ee.find("[name]").filter(function() {
                    return e(this).attr("name") && e(this).attr("name").toLowerCase() == t.toLowerCase()
                })
                  , i = ee.find("[id]").filter(function() {
                    return e(this).attr("id") && e(this).attr("id").toLowerCase() == t.toLowerCase()
                });
                (i.length || o.length) && (St("error", "data-anchor tags can not have the same value as any `id` element on the site (or `name` element for IE)."),
                i.length && St("error", '"' + t + '" is is being used by another element `id` property'),
                o.length && St("error", '"' + t + '" is is being used by another element `name` property'))
            }))
        }
        function St(e, n) {
            console && console[e] && console[e]("fullPage: " + n)
        }
        if (e("html").hasClass(d))
            return void mt();
        var wt = e("html, body")
          , yt = e("body")
          , bt = e.fn.fullpage;
        Z = e.extend(!0, {
            menu: !1,
            anchors: [],
            lockAnchors: !1,
            navigation: !1,
            navigationPosition: "right",
            navigationTooltips: [],
            showActiveTooltip: !1,
            slidesNavigation: !1,
            slidesNavPosition: "bottom",
            scrollBar: !1,
            hybrid: !1,
            css3: !0,
            scrollingSpeed: 700,
            autoScrolling: !0,
            fitToSection: !0,
            fitToSectionDelay: 1e3,
            easing: "easeInOutCubic",
            easingcss3: "ease",
            loopBottom: !1,
            loopTop: !1,
            loopHorizontal: !0,
            continuousVertical: !1,
            continuousHorizontal: !1,
            scrollHorizontally: !1,
            interlockedSlides: !1,
            dragAndMove: !1,
            offsetSections: !1,
            resetSliders: !1,
            fadingEffect: !1,
            normalScrollElements: null,
            scrollOverflow: !1,
            scrollOverflowReset: !1,
            scrollOverflowHandler: e.fn.fp_scrolloverflow ? e.fn.fp_scrolloverflow.iscrollHandler : null,
            scrollOverflowOptions: null,
            touchSensitivity: 5,
            normalScrollElementTouchThreshold: 5,
            bigSectionsDestination: null,
            keyboardScrolling: !0,
            animateAnchor: !0,
            recordHistory: !0,
            controlArrows: !0,
            controlArrowColor: "#fff",
            verticalCentered: !0,
            sectionsColor: [],
            paddingTop: 0,
            paddingBottom: 0,
            fixedElements: null,
            responsive: 0,
            responsiveWidth: 0,
            responsiveHeight: 0,
            responsiveSlides: !1,
            parallax: !1,
            parallaxOptions: {
                type: "reveal",
                percentage: 62,
                property: "translate"
            },
            sectionSelector: g,
            slideSelector: I,
            afterLoad: null,
            onLeave: null,
            afterRender: null,
            afterResize: null,
            afterReBuild: null,
            afterSlideLoad: null,
            onSlideLeave: null,
            afterResponsive: null,
            lazyLoading: !0
        }, Z);
        var xt, Ct, Tt, At, Mt = !1, kt = navigator.userAgent.match(/(iPhone|iPod|iPad|Android|playbook|silk|BlackBerry|BB10|Windows Phone|Tizen|Bada|webOS|IEMobile|Opera Mini)/), Ot = "ontouchstart"in n || navigator.msMaxTouchPoints > 0 || navigator.maxTouchPoints, Rt = e(this), zt = $.height(), It = !1, Lt = !0, Et = !0, Ht = [], Bt = {};
        Bt.m = {
            up: !0,
            down: !0,
            left: !0,
            right: !0
        },
        Bt.k = e.extend(!0, {}, Bt.m);
        var Dt, Pt, Yt, Ft, Wt, Xt, Vt, Zt, Nt = ot(), jt = {
            touchmove: "ontouchmove"in n ? "touchmove" : Nt.move,
            touchstart: "ontouchstart"in n ? "touchstart" : Nt.down
        }, qt = !1, Gt = e.extend(!0, {}, Z), Ut = {};
        mt(),
        e.extend(e.easing, {
            easeInOutCubic: function(e, n, t, o, i) {
                return (n /= i / 2) < 1 ? o / 2 * n * n * n + t : o / 2 * ((n -= 2) * n * n + 2) + t
            }
        }),
        e.event.special.destroyed = {
            remove: function(e) {
                e.handler && e.handler()
            }
        },
        e(this).length && (bt.version = "2.9.5",
        bt.setAutoScrolling = q,
        bt.setRecordHistory = Q,
        bt.setScrollingSpeed = J,
        bt.setFitToSection = ne,
        bt.setLockAnchors = te,
        bt.setMouseWheelScrolling = oe,
        bt.setAllowScrolling = ie,
        bt.setKeyboardScrolling = re,
        bt.moveSectionUp = ae,
        bt.moveSectionDown = le,
        bt.silentMoveTo = se,
        bt.moveTo = ce,
        bt.moveSlideRight = de,
        bt.moveSlideLeft = fe,
        bt.fitToSection = He,
        bt.reBuild = ue,
        bt.setResponsive = pe,
        bt.getFullpageData = ve,
        bt.destroy = ct,
        bt.landscapeScroll = Tn,
        bt.shared = {
            afterRenderActions: ze
        },
        me("continuousHorizontal"),
        me("scrollHorizontally"),
        me("resetSliders"),
        me("interlockedSlides"),
        me("responsiveSlides"),
        me("fadingEffect"),
        me("dragAndMove"),
        me("offsetSections"),
        me("scrollOverflowReset"),
        me("parallax"),
        ut("dragAndMove") && bt.dragAndMove.init(),
        he(),
        ge(),
        ut("dragAndMove") && bt.dragAndMove.turnOffTouch());
        var Qt = !1
          , Jt = 0
          , _t = 0
          , Kt = 0
          , $t = 0
          , eo = 0;
        !function() {
            var e = n.requestAnimationFrame || n.mozRequestAnimationFrame || n.webkitRequestAnimationFrame || n.msRequestAnimationFrame;
            n.requestAnimationFrame = e
        }();
        var no = (new Date).getTime()
          , to = !1
          , oo = 0
          , io = 0
          , ro = zt
    }
});
