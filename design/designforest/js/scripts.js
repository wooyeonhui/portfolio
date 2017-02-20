
(function($) {
    "use strict";
    /*==============================
        Is mobile
    ==============================*/
    var isMobile = {
        Android: function() {
            return navigator.userAgent.match(/Android/i);
        },
        BlackBerry: function() {
            return navigator.userAgent.match(/BlackBerry/i);
        },
        iOS: function() {
            return navigator.userAgent.match(/iPhone|iPad|iPod/i);
        },
        Opera: function() {
            return navigator.userAgent.match(/Opera Mini/i);
        },
        Windows: function() {
            return navigator.userAgent.match(/IEMobile/i);
        },
        any: function() {
            return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
        }
    }

    /*==============================
        Image cover
    ==============================*/
    $.fn.imageCover = function() {
        $(this).each(function() {
            var self = $(this),
                image = self.find('img'),
                heightWrap = self.outerHeight(),
                widthImage = image.outerWidth(),
                heightImage = image.outerHeight();
            if (heightImage < heightWrap) {
                image.css({
                    'height': '100%',
                    'width': 'auto'
                });
            }
        });
    }

    function inputPlaceholder() {
        var $ph = $('input[type="search"], input[type="text"], input[type="email"], textarea');
        $ph.each(function() {
            var value = $(this).val();
            $(this).focus(function() {
                if ($(this).val() === value) {
                    $(this).val('');
                }
            });
            $(this).blur(function() {
                if ($(this).val() === '') {
                    $(this).val(value);
                }
            });
        });
    }

    function backgroundImage()
    {
        var section = $('.background-image');
        section.each(function()
        {
            var $this = $(this);
            if ($this.attr('data-background-image')) {
                var bg = $this.attr('data-background-image');
                $this.css('background-image', 'url(' + bg + ')');
            }
        });
    }

    function owlCarouselSlider() {
        var navslider = ['<i class="fa fa-angle-left"></i>', '<i class="fa  fa-angle-right"></i>'];
        if ($('.featured-slider').length > 0)
        {
            var setAutoplay = 10000, $featuredSlider = $('.featured-slider');
            $featuredSlider.owlCarousel({
                autoPlay: setAutoplay,
                slideSpeed: 800,
                navigation: true,
                pagination: true,
                singleItem: true,
                autoHeight: true,
                addClassActive: true,
                navigationText: navslider,
                afterMove : function(el) {
                    el.find('.timer-slider').css({
                        '-webkit-animation': 'none',
                        '-moz-animation': 'none',
                        'animation': 'none'
                    });
                    setTimeout(function() {
                        el.find('.timer-slider').css({
                            '-webkit-animation': 'timer ' + setAutoplay + 'ms linear infinite',
                            '-moz-animation': 'timer ' + setAutoplay + 'ms linear infinite',
                            'animation': 'timer ' + setAutoplay + 'ms linear infinite'
                        });
                    }, 100);
                }
            });

            if ( typeof _disableTimeSlider == 'undefined' )
            {
                $featuredSlider.prepend('<div class="timer-slider"></div>');

                $('.timer-slider').css({
                    '-webkit-animation': 'timer ' + setAutoplay + 'ms linear infinite',
                    '-moz-animation': 'timer ' + setAutoplay + 'ms linear infinite',
                    'animation': 'timer ' + setAutoplay + 'ms linear infinite',
                });
            }

            $(window).on('resize', function() {
                setTimeout(function() {
                    var windowWidth = $('.featured-slider').width(),
                        itemFeaturedWidth = $('.featured-slider').find('.item-content').width(),
                        setOwlNavWidth = (windowWidth - itemFeaturedWidth) / 2;
                    $('.featured-slider').find('.owl-next, .owl-prev').css('width', setOwlNavWidth);
                }, 100);
            }).trigger('resize');
        }

        if ($('.images-slider').length > 0)
        {
            $('.images-slider').owlCarousel({
                autoPlay: false,
                slideSpeed: 300,
                navigation: true,
                pagination: true,
                singleItem: true,
                autoHeight: true,
                navigationText: navslider,
                afterInit: function(el) {
                    el.magnificPopup({
                        delegate: 'a',
                        type: 'image',
                        closeOnContentClick: false,
                        closeBtnInside: false,
                        mainClass: 'pp-gallery mfp-with-zoom mfp-img-mobile',
                        image: {
                            verticalFit: true,
                        },
                        gallery: {
                            enabled: true
                        },
                        zoom: {
                            enabled: true,
                            duration: 300, // don't foget to change the duration also in CSS
                            opener: function(element) {
                                return element.find('img');
                            }
                        },
                    });
                }
            });
        }

        if ($('.widget-slider').length > 0)
        {
            $('.widget-slider').owlCarousel({
                autoPlay: false,
                slideSpeed: 300,
                navigation: true,
                pagination: false,
                singleItem: true,
                autoHeight: true,
                navigationText: navslider, 
            });
        }
    }


    function tiledGallery()
    {
        if ($('.tiled-gallery').length)
        {
            var tiledItemSpacing = 4;
            $('.tiled-gallery').wrap('<div class="tiled-gallery-row"></div>');
            $('.tiled-gallery').parent().css('margin', -tiledItemSpacing);
            $('.tiled-gallery').justifiedGallery({
                rowHeight: 230,
                lastRow : 'justify',
                margins: tiledItemSpacing,
                waitThumbnailsLoad: false
            });
        }
    }

    function popup() {
        $('.tiled-gallery').magnificPopup({
            delegate: 'a',
            type: 'image',
            closeOnContentClick: false,
            closeBtnInside: false,
            mainClass: 'pp-gallery mfp-with-zoom mfp-img-mobile',
            image: {
                verticalFit: true,
            },
            gallery: {
                enabled: true
            },
            zoom: {
                enabled: true,
                duration: 300, // don't foget to change the duration also in CSS
                opener: function(element) {
                    return element.find('img');
                }
            },
        });
    }

    function socialAndsearch() {
        $('.toggle-social').on('click', function() {
            $('.page-social').fadeIn(500);
        });

        $('.page-social-close').on('click', function() {
            $('.page-social').fadeOut(500);
        });

        $('.toggle-search').on('click', function() {
            $('.page-search').fadeIn(500);
        });

        $('.page-search-close').on('click', function() {
            $('.page-search').fadeOut(500);
        });
    }

    function piCheckSearchSocial()
    {
        var i = 0;
        if ( $(".page-social").length == 0 )
        {
            $(".toggle-social.item").remove();
            i =  i+1;
        }
        if ( $(".page-search").length == 0 )
        {
            $(".toggle-search.item").remove();
            i = i + 1;
        }

        if ( i == 2 )
        {
            $('.header-right').remove();
        }
    }

    function piAddFullScreen()
    {
        var selectors = [
            'iframe[src*="player.vimeo.com"]',
            'iframe[src*="youtube.com"]',
            'iframe[src*="youtube-nocookie.com"]',
            'iframe[src*="kickstarter.com"][src*="video.html"]',
            'object',
            'embed'
        ];

        $("article.post").each(function(){
            var $allVideos = $(this).find(selectors.join(','));

            $allVideos.each(function()
            {

                if ( !$(this).hasClass('embed-responsive-item') )
                {
                    $(this).addClass("embed-responsive-item");
                    if ( !$(this).parent().hasClass("embed-responsive") )
                    {
                        $(this).wrap('<div class="embed-responsive embed-responsive-16by9"></div>');
                    }
                }
            })
        })
    }

    function piRemoveEmpty()
    {
        if ( $("#comments .comments-inner-wrap").children().length == 0 )
        {
            $("#comments").remove();
        }

        var _i = 1;
        $(".footer-content").children().each(function(){
            if ( $(this).children().length == 0 )
            {
                $(this).remove();
                _i++;
            }

            if ( _i == 4 )
            {
                $('.footer-content').remove();
            }
        })
    }

    function subToggle()
    {
        if ($('.pi-menulist').find('.submenu-toggle').length === 0) {
            $('.menu-item-has-children')
                .children('a')
                .after('<span class="submenu-toggle"><i class="fa fa-angle-right"></i></span>');
            $('.pi-menulist').on('click', '.submenu-toggle', function(evt) {
                evt.preventDefault();
                $(this)
                    .siblings('.sub-menu')
                    .addClass('sub-menu-active');
            });
        }
    }

    function submenuBack() {
        $('.pi-menulist .sub-menu').each(function() {
            var $this = $(this);
            if ($this.find('.back-mb').length === 0) {
                $this
                    .prepend('<li class="back-mb"><a href="#">Back</a></li>');
            }
            $('.pi-menulist').on('click', '.back-mb a', function(evt) {
                evt.preventDefault();
                $(this)
                    .parent()
                    .parent()
                    .removeClass('sub-menu-active');
            });
        });
    }

    function responsiveMenu()
    {
        $('.toggle-menu').on('click', function() {
            $(this).toggleClass('toggle-active');
            $('.pi-navigation').toggleClass('pi-navigation-active');
        });

        $(window).resize(function() {
            var windowWidth = window.innerWidth;
            if (windowWidth <= 991) {
                subToggle();
                submenuBack();
            } else {
                $('.submenu-toggle, .back-mb').remove();
            }
        }).trigger("resize");
    }

    $.fn.numberLine = function(opts)
    {
        $(this).each( function () {
            var $this = $(this),
                defaults = {
                    numberLine: 0
                },
                data = $this.data(),
                dataTemp = $.extend(defaults, opts),
                options = $.extend(dataTemp, data);

            if (!options.numberLine)
                return false;

            $this.bind('customResize', function(event) {
                event.stopPropagation();
                reInit();
            }).trigger('customResize');
            $(window).resize( function () {
                $this.trigger('customResize');
            })
            function reInit() {
                var fontSize = parseInt($this.css('font-size')),
                    lineHeight = parseInt($this.css('line-height')),
                    overflow = fontSize * (lineHeight / fontSize) * options.numberLine;

                $this.css({
                    'display': 'block',
                    'max-height': overflow,
                    'overflow': 'hidden'
                });
            }
        })
    }

    function piLazyLoad()
    {
        $("img.lazy").lazyload({
            effect : "fadeIn"
        });
    }

    function movePageTitle() 
    {
        if( $('.page-template .no-sidebar .category-page-title').length ) 
        {
            if( $('.page-template .no-sidebar .pi-content').length ) 
            {
                var $page_title = $('.page-template .no-sidebar .category-page-title').clone();
                $('.page-template .no-sidebar .category-page-title').remove();
                $('.page-template .no-sidebar .pi-content').prepend($page_title)
            }
        }
     }

    $(document).ready(function()
    {
        $('.pi-grid').find('.pi-content').wrapInner('<div class="pi-row"></div>');
        $('.pi-grid').find('.post').wrap('<div class="pi-grid-item"></div>');

        if ($('.pi-sidebar').length) 
        {
            if (!$('.main-content').hasClass('pi-grid-first-large')) {
                $('.pi-grid').find('.pi-grid-item:nth-child(2n+1)').css('clear', 'both');
            } else {
                $('.pi-grid').find('.pi-grid-item:nth-child(2n)').css('clear', 'both');
            }
        }

        owlCarouselSlider();
        piCheckSearchSocial();
        backgroundImage();
        movePageTitle();
        
        responsiveMenu();

        $('[data-number-line]').numberLine();

        $('.pi-grid .post-entry p').numberLine(
        {
            numberLine: 4
        });
        $('.pi-list .post-entry p').numberLine(
        {
            numberLine: 3
        });

        if (isMobile.iOS()) {
            $('[data-background-image]')
                .addClass('fix-background-ios');
        }


        piAddFullScreen();
        piRemoveEmpty();

        if ( isMobile.any() )
        {
            $(".tiled-gallery").addClass("tiled-gallery-mobile");
        }

        tiledGallery();
        socialAndsearch();
        popup();
    });

    $(window).load(function() {
        inputPlaceholder();
        $('.preloader').fadeOut(1200);
        $('.image-cover, .images-slider .item, .pi-grid .pi-content .images, .pi-list .pi-content .images, .post-link ~ .images').imageCover();
    });
        
    var $catPage = $(".category-page-title"), _cacheTitle = "";
        if ( $catPage.length == 1 )
        {
            _cacheTitle = $catPage.clone();
            $catPage.remove();
        }

    $(window).resize(function() {
        if ($('.pi-sidebar').length == 0) 
        {
            if (window.innerWidth >= 1230) {
                if ( !$('.main-content').hasClass('pi-grid-first-large') ) 
                {
                    $('.pi-grid').find('.pi-grid-item:nth-child(3n+1)').css('clear', 'both');
                    $('.pi-grid').find('.pi-grid-item:nth-child(2n+1)').css('clear', 'none');
                } else {
                    $('.pi-grid').find('.pi-grid-item:nth-child(3n+2)').css('clear', 'both');
                    $('.pi-grid').find('.pi-grid-item:nth-child(2n)').css('clear', 'none');
                }
            } else {
                if (!$('.main-content').hasClass('pi-grid-first-large')) {
                    $('.pi-grid').find('.pi-grid-item:nth-child(2n+1)').css('clear', 'both');
                    $('.pi-grid').find('.pi-grid-item:nth-child(3n+1)').css('clear', 'none');
                } else {
                    $('.pi-grid').find('.pi-grid-item:nth-child(2n)').css('clear', 'both');
                    $('.pi-grid').find('.pi-grid-item:nth-child(3n+2)').css('clear', 'none');
                }
            }

            if ( $(".category-page-title").length == 0 )
            {
                $(".main-content .pi-row").prepend(_cacheTitle); 
            }

        }
    }).trigger('resize');

    piLazyLoad();




})(jQuery);