"use strict";

/**!
 * sportsapp v1.0.0
 * PSD to HTML/CSS
 * (c) 2019 Ömer Alpı
 */
(function () {
    function r(e, n, t) {
        function o(i, f) {
            if (!n[i]) {
                if (!e[i]) {
                    var c = "function" == typeof require && require;
                    if (!f && c) return c(i, !0);
                    if (u) return u(i, !0);
                    var a = new Error("Cannot find module '" + i + "'");
                    throw a.code = "MODULE_NOT_FOUND", a;
                }

                var p = n[i] = {
                    exports: {}
                };
                e[i][0].call(p.exports, function (r) {
                    var n = e[i][1][r];
                    return o(n || r);
                }, p, p.exports, r, e, n, t);
            }

            return n[i].exports;
        }

        for (var u = "function" == typeof require && require, i = 0; i < t.length; i++) {
            o(t[i]);
        }

        return o;
    }

    return r;
})()({
    1: [function (require, module, exports) {
        var body = $('body');
        var page = $('.page');
        var title = $('.logo');
        var titleText = title.html();
        var topMenuLeftOpen = $('.top-menu-left-open');
        var topMenuRightOpen = $('.top-menu-right-open');
        var topMenuBack = $('.top-menu-back');
        var topMenuClose = $('.top-menu-close');

        if ($('.top-menu-back').length > 0) {
            var isTopMenuBack = 1;
        } else {
            var isTopMenuBack = 2;
        }

        $(document).ready(function () {
            function onTopMenuOpen() {
                body.stop().animate({
                    scrollTop: 0
                }, 500, 'swing', function () {
                });
                body.css('overflow', 'hidden');

                if (!$('.backdrop').length) {
                    page.append('<div class="backdrop"></div>');
                }

                topMenuRightOpen.removeClass('active');
                topMenuClose.addClass('active');
            }

            function onTopMenuClose() {
                body.css('overflow', 'auto');
                $('.backdrop').remove();
                topMenuRightOpen.addClass('active');
                topMenuClose.removeClass('active');
            }

            function onTopMenuRightClose() {
                body.removeClass('sidebar-right-open');

                if (isTopMenuBack == 1) {
                    topMenuBack.addClass('active');
                    topMenuLeftOpen.removeClass('active');
                }
            }

            function onTopMenuRightOpen() {
                body.addClass('sidebar-right-open');

                if (isTopMenuBack == 1) {
                    topMenuBack.removeClass('active');
                    topMenuLeftOpen.addClass('active');
                }
            }

            function onTopMenuLeftOpen() {
                body.addClass('sidebar-left-open');
                title.html($('.sidebar-left').attr('data-title'));
            }

            function onTopMenuLeftClose() {
                body.removeClass('sidebar-left-open');
                title.html(titleText);
            }

            $(document).on('click', '.top-menu-left-open', function () {
                onTopMenuOpen();
                onTopMenuLeftOpen();
            });
            $(document).on('click', '.top-menu-right-open', function () {
                onTopMenuOpen();
                onTopMenuRightOpen();
            });
            $(document).on('click', '.sidebar-close, .backdrop, .top-menu-close', function () {
                onTopMenuClose();
                onTopMenuLeftClose();
                onTopMenuRightClose();
            });
            $('td,th').on('focus', function () {
                $(this).closest('tr').focus();
            });
            $('.scrollbar-inner').scrollbar();

            $(document).on('click', '#shareBtn', function () {
                var buttons = $('.page-share-buttons');
                var icon = $(this).find('i');
                buttons.slideToggle('fast');

                if ($(this).hasClass('btn-active')) {
                    $(this).removeClass('btn-active');
                    icon.removeClass('fa-plus').addClass('fa-share');
                } else {
                    $(this).addClass('btn-active');
                    icon.removeClass('fa-share').addClass('fa-plus');
                }
            });

            var bandcampLinks = document.getElementsByClassName('bandcamp-link');

            for (var i = 0; i < bandcampLinks.length; i++) {
                bandcampLinks[i].addEventListener('click', function (e) {
                    e.stopPropagation();
                });
            }

            var songElements = document.getElementsByClassName('song');

            for (var i = 0; i < songElements.length; i++) {
                songElements[i].addEventListener('mouseover', function () {
                    this.style.backgroundColor = '#3fb03d';
                    this.querySelectorAll('.song-meta-data .song-title')[0].style.color = '#FFFFFF';
                    this.querySelectorAll('.song-meta-data .song-artist')[0].style.color = '#FFFFFF';

                    if (!this.classList.contains('amplitude-active-song-container')) {
                        this.querySelectorAll('.play-button-container')[0].style.display = 'block';
                    }

                    this.querySelectorAll('img.bandcamp-grey')[0].style.display = 'none';
                    this.querySelectorAll('img.bandcamp-white')[0].style.display = 'block';
                    this.querySelectorAll('.song-duration')[0].style.color = '#FFFFFF';
                });
                songElements[i].addEventListener('mouseout', function () {
                    this.style.backgroundColor = '#FFFFFF';
                    this.querySelectorAll('.song-meta-data .song-title')[0].style.color = '#272726';
                    this.querySelectorAll('.song-meta-data .song-artist')[0].style.color = '#607D8B';
                    this.querySelectorAll('.play-button-container')[0].style.display = 'none';
                    this.querySelectorAll('img.bandcamp-grey')[0].style.display = 'block';
                    this.querySelectorAll('img.bandcamp-white')[0].style.display = 'none';
                    this.querySelectorAll('.song-duration')[0].style.color = '#607D8B';
                });
                songElements[i].addEventListener('click', function () {
                    this.querySelectorAll('.play-button-container')[0].style.display = 'none';
                });
            }

            document.getElementById('large-visualization').style.height = document.getElementById('album-art').offsetWidth + 'px';
        });
    }, {}]
}, {}, [1]);

//# sourceMappingURL=index.js.map

/**
 * Cancels the subscription of the current user
 */
function cancelSubscription() {
    // Are you sure want to cancel the subscription?
    if (!confirm('သင်စာရင်းသွင်းခြင်းကိုပယ်ဖျက်လိုပါသလား?')) {
        return;
    }

    $.post('/cancel-subscription', function (data) {
        if (typeof data.message !== 'undefined') {
            alert(data.message);
        }

        if (data.result) {
            window.location.href = data.redirectionUrl;
        }
    });
}
