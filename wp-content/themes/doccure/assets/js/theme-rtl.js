(function ($) {
    "use strict"

    	// Stick Sidebar

	if ($(window).width() > 767) {
		if ($('.theiaStickySidebar').length > 0) {
			$('.theiaStickySidebar').theiaStickySidebar({
				// Settings
				additionalMarginTop: 30
			});
		}
	}
    
    /*-------------------------------------------------------------------------------
   Search Trigger
   -------------------------------------------------------------------------------*/
    $(".search-trigger").on('click', function (e) {
        $(".search-form-wrapper").toggleClass('open');
    });
    /*-------------------------------------------------------------------------------
    Preloader
    -------------------------------------------------------------------------------*/
    // Name preloader
    var preloaderLetters = $('#letters'),
        letterItems = preloaderLetters.find('span');

    function doLetterAnim() {

        var delay = 200;
        letterItems.each(function (e, i) {
            var $this = $(this);
            setTimeout(function () {
                $this.addClass('appeared');
                if (e == letterItems.length - 1) {
                    $(".preloader-name").addClass('done');
                }
            }, 50 + e * delay)
        })

    }

    if (preloaderLetters.length) {
        doLetterAnim();
    }

    /*-------------------------------------------------------------------------------
    Jump To
    -------------------------------------------------------------------------------*/
    $('body').on('click', '.doccure-go-to', function (e) {
        e.preventDefault();

        var jumpTo = $(this).data('to');

        $("html, body").animate({
            scrollTop: $(jumpTo).offset().top
        }, 600);
        return false;

    });

    /*-------------------------------------------------------------------------------
    Mobile Navigation and Aside panels
    -------------------------------------------------------------------------------*/
    $(".aside-trigger").on('click', function () {
        $("body").toggleClass('aside-open');
    });

    $(".aside-trigger-right").on('click', function () {
        $("body").toggleClass('aside-right-open');
    });

    $(".doccure_aside .menu-item-has-children > a").on('click', function (e) {
        var submenu = $(this).next(".sub-menu");
        e.preventDefault();

        submenu.slideToggle(200);
    });


    /*-------------------------------------------------------------------------------
    Sticky Header
      -------------------------------------------------------------------------------*/
    var header = $(".can-sticky");
    var headerHeight = header.innerHeight();

    function doSticky() {
        if (window.pageYOffset > headerHeight + 120) {
            header.addClass("sticky");
        } else {
            header.removeClass("sticky");
        }
    }

    doSticky();


    /*-------------------------------------------------------------------------------
    on scroll functions
    -------------------------------------------------------------------------------*/
    $(window).on('scroll', function () {

        // Sticky header
        doSticky();

        // Back to top
        stickBackToTop();

    });


    /*-------------------------------------------------------------------------------
    Back to top
    -------------------------------------------------------------------------------*/
    function stickBackToTop() {
        if (window.pageYOffset > 400) {
            $('.doccure_to-top').addClass('active');
        } else {
            $('.doccure_to-top').removeClass('active');
        }
    }

    stickBackToTop();

    $('body').on('click', '.doccure_to-top', function () {
        $("html, body").animate({
            scrollTop: 0
        }, 600);
        return false;
    });

    /*-------------------------------------------------------------------------------
    Masonry
    -------------------------------------------------------------------------------*/
    $('.masonry').imagesLoaded(function () {
        var isotopeContainer = $('.masonry');
        isotopeContainer.isotope({
            itemSelector: '.masonry-item',
        });
    });


    /*-------------------------------------------------------------------------------
    Countdown
    -------------------------------------------------------------------------------*/
    $(".doccure_countdown-timer").each(function () {
        var $this = $(this);
        $this.countdown($this.data('countdown'), function (event) {
            $(this).text(
                event.strftime('%D days %H:%M:%S')
            );
        });
    });

    /*------------------------------------------------------------------------------------
    Tooltips
    -------------------------------------------------------------------------------*/

    $('.popup-doccure a').magnificPopup({
        disableOn: 700,
        type: 'iframe',
        mainClass: 'mfp-fade',
        removalDelay: 160,
        preloader: false,
        fixedContentPos: false
    });

    //On scroll events
    $(window).on('scroll', function () {

        doSticky();
        stickBackToTop();

    });
    /*-------------------------------------------------------------------------------
    Gallery Format Slider
    -------------------------------------------------------------------------------*/
    $(".doccure_post.format-gallery .doccure_post-thumb.has-slider").slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
        dots: true,
        rtl: true,
        autoplay: false,
        centerMode: true,
        centerPadding: 0,
        responsive: [
            {
                breakpoint: 767,
                settings: {
                    arrows: false
                }
            }
        ]
    });
    /*-------------------------------------------------------------------------------
      Ajax Search
    -------------------------------------------------------------------------------*/

    /* Perform a delay before executing a function (callback) */
    function delay(callback, ms) {
        var timer = 0;
        return function () {
            var context = this, args = arguments;
            clearTimeout(timer);
            timer = setTimeout(function () {
                callback.apply(context, args);
            }, ms || 0);
        };
    }

    $(".doccure-ajax-search-wrap .search-field").on('keyup', delay(function () {

        var val = $(this).val();

        doAjaxProductSearch(val);

    }, 500));

    $(document).on('mouseup', function (e) {
        var container = $(".doccure-product-search-results");

        if (!container.is(e.target) && container.has(e.target).length === 0) {
            container.hide();
        }

    });


    function doAjaxProductSearch(val) {

        $(".doccure-ajax-search-wrap .woocommerce-product-search button").html('<i class="fa fa-spin fa-spinner"></i>');

        $.ajax({
            url: ajax_woocommerce_object.ajaxurl,
            type: "post",
            data: {
                action: 'doccure_ajax_search_content',
                keyword: val,
            },
            success: function (response) {
                $(".doccure-product-search-results").show();
                $(".doccure-ajax-search-wrap .woocommerce-product-search button").html('<i class="flaticon-search"></i>');
                $(".doccure-product-search-results").html(response);
            }
        });
    }

    /*-------------------------------------------------------------------------------
      Infinite Scroll
      -------------------------------------------------------------------------------*/
    if ($('.scroller-status').length) {
        $('.doccure_product-infinite-scroll #main div.products').infiniteScroll({
            path: '.woocommerce-pagination a.next',
            append: '.doccure_product',
            status: '.scroller-status',
            hideNav: '.woocommerce-pagination',
            history: false,
        });
    }


    /*-------------------------------------------------------------------------------
    Cookies
    -------------------------------------------------------------------------------*/
    function setCookie(cname, cvalue, days) {

        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            var expires = "; expires=" + date.toGMTString();
        } else {
            var expires = "";
        }
        document.cookie = cname + "=" + cvalue + expires + "; path=/";
    }

    //Return a particular cookie
    function getCookie(cname) {
        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }

    //Checks if a cookie exists
    function checkCookie(cookieToCheck) {
        var cookie = getCookie(cookieToCheck);
        if (cookie != "") {
            return true;
        }
        return false;
    }

    //Delet an existing cookie
    function deleteCookie(name) {
        document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    }

    /*-------------------------------------------------------------------------------
   Newsletter popup close and set cookie
   -------------------------------------------------------------------------------*/

    /*-------------------------------------------------------------------------------
    Header login/Register popup
   -------------------------------------------------------------------------------*/
    $('.register-new-account').not('.register-new-account.active').on('click', function (e) {
        e.preventDefault();

        $('.login-register-form-toggle').addClass('visible');
        $('.login-form-wrapper').addClass('hidden');
        $('.header-login-register-form .doccure_close').addClass('hidden');
        $('.registration-form-wrapper').addClass('active');
    });

    $('.login-register-form-toggle').on('click', function (e) {
        e.preventDefault();
        $(this).removeClass('visible');
        $('.login-form-wrapper').removeClass('hidden');
        $('.registration-form-wrapper').removeClass('active');
    });

    /*-------------------------------------------------------------------------------
    Password Toggle Js
    -------------------------------------------------------------------------------*/
    $(".password-toggle").on('click', function () {
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $(this).next(),
            inputType = input.attr("type") == "password"
                ? 'text'
                : 'password';

        input.attr("type", inputType);
    });

    /*-------------------------------------------------------------------------------
     Doctor Socials Trigger
     -------------------------------------------------------------------------------*/
    $("a.trigger-doctor-socials").on('click', function (e) {
        e.preventDefault();
        $(this).prev('.doccure_social-icons').toggleClass('visible');
    });


    /*-------------------------------------------------------------------------------
     One page menu scroll
     -------------------------------------------------------------------------------*/
    var headerHeight = $('header.doccure_header').outerHeight();

    if ($('body').hasClass('admin-bar')) {
        headerHeight = headerHeight + 32;
    }

    if ($('body').hasClass('doccure-one-page-menu')) {
        $('.doccure-one-page-section').each(function () {
            $(this).next().addClass('scroll-to-block');
        });
        $('.doccure_header .navbar-nav li').find('a').each(function () {
            $(this).addClass('scroll-to-link');
        });
    }
    $('.scroll-to-link').on('click', function () {
        if ($(this).attr('href').indexOf('http://') > -1 || $(this).attr('href').indexOf('https://') > -1) {
            window.location.href = $(this).attr('href').replace(/^#/, '');
            return;
        }
        var index = $(this).parent().parent().find('.scroll-to-link').index(this);
        $('body, html').animate({'scrollTop': ($('.scroll-to-block').eq(index).offset().top - headerHeight) + 'px'}, 800);
        console.log($('.scroll-to-block').eq(index).offset().top);
        return false;
    });


    /*----------------------------------------------------------
    Popup
    ---------------------------------------------------------*/

    var modalWrapper = $("#doccure_popup-newsletter, #doccure_newsletter-popup");
    var modalCookiesType = modalWrapper.data('enable-type') != '' && modalWrapper.data('enable-type') != undefined ? modalWrapper.data('enable-type') : '';

    function doccure_popup_init() {
        var popupHideSeconds = modalWrapper.data('close-delay') != '' && modalWrapper.data('close-delay') != undefined ? parseInt(modalWrapper.data('close-delay')) : '';
        var popupTriggerType = modalWrapper.data('close-trigger-type') != '' && modalWrapper.data('close-trigger-type') != undefined ? modalWrapper.data('close-trigger-type') : '';

        // on load popup
        $(window).on('load', function () {

            // Newsletter popup
            $("#doccure_popup-newsletter.popup-onload, #doccure_newsletter-popup.popup-onload").each(function () {
                var popupWrapper = $(this),
                    popupDelaySeconds = popupWrapper.data('delay') != '' && popupWrapper.data('delay') != undefined ? parseInt(popupWrapper.data('delay')) : 3;
                setTimeout(function () {
                    $("#doccure_popup-newsletter.popup-onload").modal('show');
                    $(".doccure_popup").addClass('show');
                }, popupDelaySeconds * 1000);

                if (popupTriggerType == 'trigger-delay' && popupHideSeconds != '') {
                    setTimeout(function () {
                        $("#doccure_popup-newsletter.popup-onload").modal('hide');
                        $(".doccure_popup").removeClass('show');
                    }, popupHideSeconds * 1000);
                }

            });
        });

        // on scoll popup
        $(window).on('scroll', function () {
            var scroll = $(this).scrollTop();
            $("#doccure_popup-newsletter.popup-onscroll, #doccure_newsletter-popup.popup-onscroll").each(function () {
                if (scroll >= 350) {
                    $('#doccure_popup-newsletter.popup-onscroll').modal('show');
                    $(".doccure_popup").addClass('show');
                }
                if (popupTriggerType == 'trigger-delay' && popupHideSeconds != '') {
                    setTimeout(function () {
                        $("#doccure_popup-newsletter.popup-onscroll").modal('hide');
                        $(".doccure_popup").removeClass('show');
                    }, popupHideSeconds * 1000);
                }
            });
        });

        // on click popup
        $('body').on('click', '.doccure-popup-trigger', function (e) {
            e.preventDefault();
            $('#doccure_popup-newsletter.popup-onclick, #doccure_newsletter-popup.popup-onclick').modal('show');
            $(".doccure_popup").addClass('show');

            if (popupTriggerType == 'trigger-delay' && popupHideSeconds != '') {
                setTimeout(function () {
                    $("#doccure_popup-newsletter.popup-onclick").modal('hide');
                    $(".doccure_popup").removeClass('show');
                }, popupHideSeconds * 1000);
            }
        });

    }

    if (modalCookiesType == 'popup-show-refresh-reload') {
        doccure_popup_init();
    } else {
        $(".newsletter-popup-trigger, .doccure-popup-close-trigger").on('click', function () {
            setCookie('newsletter_popup_viewed', 'true');
            $(".doccure_popup").removeClass('show');
        });

        $('#doccure_popup-newsletter').on('hidden.bs.modal', function () {
            setCookie('newsletter_popup_viewed', 'true');
        });

        if (!checkCookie('newsletter_popup_viewed')) {
            doccure_popup_init();
        }
    }

    // close trigger
    $('body').on('click', '.doccure-popup-close-trigger, .newsletter-popup-trigger', function (e) {
        e.preventDefault();
        $('#doccure_popup-newsletter').modal('hide');
        $(".doccure_popup").removeClass('show');
    });

    // on load popup
    $(window).on('load', function () {
        // preloader
        $('.doccure_preloader').addClass('hidden');

    });

    function resetForm(){
        $('#commentform').reset();
        validator.resetForm();
    }

    function validateForm(){
        if (validator.form()) {
            $('#commentform').submit();
        }
    }

    // validator = $("#commentform").validate({
    //     rules: {
    //         author: "required",
    //         email: {
    //             required: true,
    //             email: true
    //         },
    //         url: {
    //             url: true
    //         },
    //         comment: "required"

    //     },
    //     messages: {
    //         author: "Please enter your name",
    //         email: {
    //             required: "Please enter an email address",
    //             email: "Please enter a valid email address"
    //         },
    //         url: "Please enter a valid URL e.g. http://www.mysite.com",
    //         comment: "Please include your comment"
    //     }
    // });

	//Header profile 
	
	$(".nav-item.dropdown").click(function(){
			$(this).toggleClass("showdropdown");
			$(".dropdown-menu-end").toggleClass("showdropdown");
	});

    $('.home_doctor_slider').slick({
            dots: true,
            rtl: true,
			autoplay:false,
            slidesToShow: 3,
            autoplay:false,
            infinite: false,
			prevArrow: false,
			nextArrow: false,
            responsive: [{
                breakpoint: 992,
                    settings: {
                        slidesToShow: 3
                      }
            },
            {
                breakpoint: 800,
                    settings: {
                        slidesToShow: 1
                      }
            },
            {
                breakpoint: 776,
                    settings: {
                        slidesToShow: 2
                      }
            },
            {
                breakpoint: 567,
                    settings: {
                        slidesToShow: 1
            }
            }]
          });

          $('.doctor-book-slider').slick({
                dots: false,
                rtl: true,
                arrows:true,
                slidesToShow: 4,
                autoplay:false,
                infinite: false,
                responsive: [{
                    breakpoint: 992,
                        settings: {
                            slidesToShow: 3
                          }
                },
                {
                    breakpoint: 800,
                        settings: {
                            slidesToShow: 1
                          }
                },
                {
                    breakpoint: 776,
                        settings: {
                            slidesToShow: 2
                          }
                },
                {
                    breakpoint: 567,
                        settings: {
                            slidesToShow: 1
                }
                }]
               
                
              });

              $('.best-doctor-slider').slick({
                dots: false,
                rtl: true,
                arrows:true,
                slidesToShow: 4,
                autoplay:false,
                infinite: false,
                responsive: [{
                    breakpoint: 992,
                        settings: {
                            slidesToShow: 3
                          }
                },
                {
                    breakpoint: 800,
                        settings: {
                            slidesToShow: 1
                          }
                },
                {
                    breakpoint: 776,
                        settings: {
                            slidesToShow: 2
                          }
                },
                {
                    breakpoint: 567,
                        settings: {
                            slidesToShow: 1
                }
                }]
                    
              });



              $('.best-doctors-slider').slick({
                dots: true,
                rtl: true,
                arrows:false,
                slidesToShow: 4,
                autoplay:false,
                infinite: false,
                responsive: [{
                    breakpoint: 992,
                        settings: {
                            slidesToShow: 3
                          }
                },
                {
                    breakpoint: 800,
                        settings: {
                            slidesToShow: 1
                          }
                },
                {
                    breakpoint: 776,
                        settings: {
                            slidesToShow: 2
                          }
                },
                {
                    breakpoint: 567,
                        settings: {
                            slidesToShow: 1
                }
                }]
                    
              });

              $('.features-clinic-slider-four').slick({
                dots: false,
                rtl: true,
                arrows:true,
                slidesToShow: 3,
                autoplay:false,
                infinite: false,
                responsive: [{
                    breakpoint: 992,
                        settings: {
                            slidesToShow: 3
                          }
                },
                {
                    breakpoint: 800,
                        settings: {
                            slidesToShow: 1
                          }
                },
                {
                    breakpoint: 776,
                        settings: {
                            slidesToShow: 2
                          }
                },
                {
                    breakpoint: 567,
                        settings: {
                            slidesToShow: 1
                }
                }]
                    
              });

              $('.features-section-slider').slick({
                dots: false,
                rtl: true,
                arrows:true,
                slidesToShow: 4,
                autoplay:false,
                infinite: false,
                responsive: [{
                    breakpoint: 992,
                        settings: {
                            slidesToShow: 3
                          }
                },
                {
                    breakpoint: 800,
                        settings: {
                            slidesToShow: 1
                          }
                },
                {
                    breakpoint: 776,
                        settings: {
                            slidesToShow: 2
                          }
                },
                {
                    breakpoint: 567,
                        settings: {
                            slidesToShow: 1
                }
                }]
                    
              });


             

              
	// Specialities Slider

	if ($('.owl-carousel.specialities-slider-one').length > 0) {
		$('.owl-carousel.specialities-slider-one').owlCarousel({
			loop: true,
            rtl:true,
			margin: 24,
			dots: false,
			nav: true,
			smartSpeed: 2000,
			navContainer: '.slide-nav-1',
			navText: ['<i class="fas fa-chevron-left custom-arrow"></i>', '<i class="fas fa-chevron-right custom-arrow"></i>'],
			responsive: {
				0: {
					items: 1
				},
				500: {
					items: 1
				},
				768: {
					items: 2
				},
				1000: {
					items: 3
				},
				1300: {
					items: 6
				}
			}
		})
	}


     // Doctors Slider

	if ($('.owl-carousel.doctor-slider-one').length > 0) {
		$('.owl-carousel.doctor-slider-one').owlCarousel({
			loop: true,
            rtl:true,
			margin: 24,
			dots: false,
			nav: true,
			smartSpeed: 2000,
			navContainer: '.slide-nav-2',
			navText: ['<i class="fas fa-chevron-left custom-arrow"></i>', '<i class="fas fa-chevron-right custom-arrow"></i>'],
			responsive: {
				0: {
					items: 1
				},
				500: {
					items: 1
				},
				768: {
					items: 2
				},
				1000: {
					items: 3
				},
				1300: {
					items: 4
				}
			}
		})
	}

    
    
	// Testimonial Slider

	if ($('.testimonial-slider').length > 0) {
		$('.testimonial-slider').slick({
			dots: false,
            rtl: true,
			autoplay: false,
			infinite: true,
			speed: 2000,
			variableWidth: false,
			slidesToShow: 1,
			slidesToScroll: 1,
			swipeToSlide: true,
			responsive: [
				{
					breakpoint: 1024,
					settings: {
						slidesToShow: 1,
						slidesToScroll: 1
					}
				},
				{
					breakpoint: 992,
					settings: {
						slidesToShow: 1,
						slidesToScroll: 1
					}
				},
				{
					breakpoint: 768,
					settings: {
						slidesToShow: 1,
						slidesToScroll: 1
					}
				},
				{
					breakpoint: 500,
					settings: {
						slidesToShow: 1,
						slidesToScroll: 1
					}
				}
			]
		});
	}

    // Partners Slider

	if ($('.owl-carousel.partners-slider').length > 0) {
		$('.owl-carousel.partners-slider').owlCarousel({
			loop: true,
			margin: 24,
            rtl:true,
			nav: true,
			autoplay: true,
			smartSpeed: 2000,
			responsive: {
				0: {
					items: 1
				},

				550: {
					items: 2
				},
				700: {
					items: 4
				},
				1000: {
					items: 6
				}
			}
		})
	}

    

})(jQuery);
