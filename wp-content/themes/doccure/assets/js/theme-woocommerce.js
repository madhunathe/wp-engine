(function($) {
  "use strict"

  /*-------------------------------------------------------------------------------
  Checkout Notices
  -------------------------------------------------------------------------------*/
  $(".doccure_notice a").on('click', function(e) {
    e.preventDefault();

    $(this).closest('.doccure_notice').next().slideToggle();
  });

  /*-------------------------------------------------------------------------------
  Add / Subtract Quantity
  -------------------------------------------------------------------------------*/
  $("body").on('click', '.qty span', function() {
    var qty = $(this).closest('.qty').find('input');
    var qtyVal = parseInt(qty.val());

    if ($(this).hasClass('qty-add')) {
      if (qty.val() >= 1) {
        $("[name='update_cart']").removeAttr('disabled');
        qty.val(qtyVal + 1);
      } else {
        $("[name='update_cart']").removeAttr('disabled');
        qty.val(1);
      }
    } else {
      $("[name='update_cart']").removeAttr('disabled');
      return qtyVal > 1 ? qty.val(qtyVal - 1) : 0;
    }

  });

  /*----------------------------------------------------------------------------
  Show/Hide Login-Register Form
   ----------------------------------------------------------------------------*/
  $(".account-register-link").click(function(){
    $(".login-form-section .woocommerce-form-register.register").addClass("show-register-form");
    $(".login-form-section .woocommerce-form.woocommerce-form-login").addClass("hide-login-form");
  });
  $(".account-login-link").click(function(){
    $(".login-form-section .woocommerce-form-register.register").removeClass("show-register-form");
    $(".login-form-section .woocommerce-form.woocommerce-form-login").removeClass("hide-login-form");
  });

  /*-------------------------------------------------------------------------------
  Cart: Notification on added
  -------------------------------------------------------------------------------*/
  $('body').on('added_to_cart', function() {
    var snackBar = $(".doccure-snackbar");
    if (!snackBar.hasClass('show')) {
      snackBar.addClass('show');
      setTimeout(function() {
        snackBar.removeClass('show');
      }, 3000);
    }
  });

  /*-------------------------------------------------------------------------------
  Cart Trigger
  -------------------------------------------------------------------------------*/
  $("body").on('click', '.doccure_cart-trigger .doccure_header-cart-inner', function(e) {
    e.preventDefault();
    $(this).closest('.doccure_header-cart').toggleClass('open');
  });

  /*-------------------------------------------------------------------------------
  Related Products / Posts
  -------------------------------------------------------------------------------*/
  $(".doccure_related-posts-slider").slick({
    slidesToShow: 3,
    slidesToScroll: 1,
    arrows: false,
    dots: false,
    autoplay: true,
    responsive: [
      {
        breakpoint: 991,
        settings: {
          slidesToShow: 2,
        }
      },
      {
        breakpoint: 575,
        settings: {
          slidesToShow: 1,
        }
      }
    ]
  });

  
})(jQuery);

