"use strict";
var loader_html 	= '<div class="dc-preloader-section"><div class="dc-preloader-holder"><div class="dc-loader"></div></div></div>';
jQuery(document).on('ready', function() {
	
	var is_rtl  			= scripts_vars.is_rtl;
	var is_loggedin  		= scripts_vars.is_loggedin;	
	
	jQuery(document).on('click','.dc-tab-des-enb .dc-subpaneltitle',function() {
		var _this = jQuery(this);
		_this.parents('ul').find('li .dc-subpaneltitle').removeClass('wrapper-activate');
		_this.addClass('wrapper-activate')
		_this.parents('ul').find('li .dc-subpanelcontent').hide();
		_this.parents('.dc-tab-des-enb').find('.dc-subpanelcontent').show();
	});
	
	jQuery(document).on('change', '.dc-startoption input[type=radio][name=user_type]', function(event) {
		event.preventDefault();
		var _this = jQuery(this);
		var placeholder	= _this.parent('.dc-radio').data('display');
		jQuery('.wt-display-type input').attr("placeholder", placeholder);
	});
	
	//add display name from first and last name
	jQuery(".dc-registerformhold input[name=first_name], .dc-registerformhold input[name=last_name]").keyup(function(){
		event.preventDefault();
		var _this = jQuery(this);
		var first_name	= jQuery('input[name=first_name]').val();
		var last_name	= jQuery('input[name=last_name]').val();
		jQuery('.wt-display-type input[name=display_name]').val(first_name+' '+last_name);
	});
	
	//Google Connect
	jQuery(document).on('click', '.dc-loginfor-offer', function (event) {
		event.preventDefault();
		var _this = jQuery(this);
		jQuery('html, body').animate({scrollTop:0}, 'slow');
	});
	
	//finish steps
	jQuery(document).on('click', '.finish-appointment', function (event) {
		window.location.reload();
	});

	/* MOBILE MENU	*/
	function collapseMenu(){
		jQuery('.dc-navigation ul li.menu-item-has-children,.dc-navigation ul li.page_item_has_children, .dc-navdashboard ul li.menu-item-has-children, .dc-navigation ul li.menu-item-has-mega-menu').prepend('<span class="dc-dropdowarrow"><i class="fa fa-chevron-right"></i></span>');
		jQuery('.dc-navigation ul li.menu-item-has-children span, .dc-navigation ul li.page_item_has_children span, .dc-navigation ul li.menu-item-has-mega-menu span').on('click', function() {
			jQuery(this).parent('li').toggleClass('dc-open');
			jQuery(this).next().next().slideToggle(300);
		});
		jQuery('.dc-navdashboard ul li.menu-item-has-children > a').on('click', function(){
			jQuery(this).parents('li.menu-item-has-children').toggleClass('dc-open');
			
			if(jQuery(this).parents('li.menu-item-has-children').hasClass('.dc-open')){
				//do nothing
			}else{
				jQuery(this).parents('li.menu-item-has-children').find('.sub-menu').hide();
			}
			
		});
	}
	
	collapseMenu();
	
	// Show - Hide Tipso on Click
	// jQuery('.show-hide-tipso').on('click', function(e){
	// 	if(jQuery(this).hasClass('clicked')){
	// 		jQuery(this).removeClass('clicked');
	// 		jQuery(this).parent('.dc-tipso').tipso('hide');
	// 	  } else {
	// 		jQuery(this).addClass('clicked');
	// 		jQuery(this).parent('.dc-tipso').tipso('show');
	// 	  }
		
	// });

	// Show - Hide Tipso on Click
	// jQuery('.show-hide-tipso').on('click', function(e){
	// 	if(jQuery(this).hasClass('clicked')){
	// 		jQuery(this).removeClass('clicked');
	// 		jQuery(this).parent('.dc-tipso').tipso('hide');
	// 	  } else {
	// 		jQuery(this).addClass('clicked');
	// 		jQuery(this).parent('.dc-tipso').tipso('show');
	// 	  }
		
	// });

	//Toolip init
	// function tipso_init(){
	// 	if(jQuery('.dc-tipso').length > 0){
	// 		jQuery('.dc-tipso').tipso({
	// 			tooltipHover	  : true,
	// 			useTitle		  : false,
	// 			background        : scripts_vars.tip_content_bg,
	// 			titleBackground   : scripts_vars.tip_title_bg,
	// 			color             : scripts_vars.tip_content_color,
	// 			titleColor        : scripts_vars.tip_title_color,
	// 		});
	// 	}
	// }
	
	// tipso_init();
	
	//Booking modal
	jQuery('.dc-booking-model').on('click', function(){
		var _this 	= jQuery(this);
		var doctor_id	= _this.data('doctor_id');

		if (scripts_vars.is_loggedin === 'false') {
			jQuery('.dc-preloader-section').remove();
            jQuery.sticky(scripts_vars.booking_message, {classList: 'important', speed: 200, autoclose: 7000});
            return false;
        }
		
		if ( scripts_vars.user_type !== 'regular_users' ) {
			jQuery('.dc-preloader-section').remove();
            jQuery.sticky(scripts_vars.allow_booking, {classList: 'important', speed: 200, autoclose: 7000});
            return false;
        } else {
			var _access     = _this.data('access');
			if( _access != '' && _access === 1 ){
				var _val = jQuery(".dc-booking-hospitals option:first").val();
				
				if(typeof _val === 'undefined'){
					//do nothing
				} else {
					doccure_select_loaction(_val,doctor_id);
				}
				
			}
			
			jQuery('#appointment').modal('show');
		}
	});
	
	

	//on call bookings details
	jQuery('.dc-booking-contacts').on('click', function(){
		
		var _this 	= jQuery(this);
		var _id		= _this.data('id');
		jQuery('body').append(loader_html);
		jQuery.ajax({
			type: 'POST',
			url: scripts_vars.ajaxurl,
			data: {
				action	: 'doccure_bookings_details',
				id		: _id,
				security		: scripts_vars.ajax_nonce
			},
			dataType: "json",
			success: function (response) {
				jQuery('.dc-preloader-section').remove();
				if (response.type === 'success') {
					jQuery('.dc-append-doc-info').html(response.html);
					jQuery('#dc-bookingcontactsmodal').modal('show');
				} else {                	                
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});               
				}
			}
		});
	});
	
	// post likes
	jQuery('.dcget-likes').on('click', function(){
		event.preventDefault();
		var _this 	= jQuery(this);
		var _id		= _this.data('key');
		
		jQuery('body').append(loader_html);
		jQuery.ajax({
			type: 'POST',
			url: scripts_vars.ajaxurl,
			data: {
				action	: 'doccure_post_likes',
				id		: _id,
				security		: scripts_vars.ajax_nonce
			},
			dataType: "json",
			success: function (response) {
				jQuery('.dc-preloader-section').remove();
				if (response.type === 'success') {
					_this.removeClass('dcget-likes');
					_this.find('a').html(response.html);
				} else {                	                
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});               
				}
			}
		});
	});
	
	jQuery('.dc-recommend-click').on('click', function(e){
		var _this	= jQuery(this);
		jQuery('.dc-recommend-click').removeClass('dc-active-recommend');
		_this.addClass('dc-active-recommend');
	});

	//Add feedback
	jQuery('.dc-add-feedback').on('click', function(event){
		event.preventDefault();
		var _this 	= jQuery(this);
		var _id		= _this.data('id');
		if (scripts_vars.is_loggedin === 'false') {
			jQuery('.dc-preloader-section').remove();
            jQuery.sticky(scripts_vars.feedback_message, {classList: 'important', speed: 200, autoclose: 7000});
            return false;
        }
		
		if (scripts_vars.user_type !== 'regular_users') {
			jQuery('.dc-preloader-section').remove();
            jQuery.sticky(scripts_vars.allow_feedback, {classList: 'important', speed: 200, autoclose: 7000});
            return false;
        } else {
			jQuery('body').append(loader_html);
			jQuery.ajax({
				type: 'POST',
				url: scripts_vars.ajaxurl,
				data: {
					action	: 'doccure_check_feedback',
					id		: _id,
					security		: scripts_vars.ajax_nonce
				},
				dataType: "json",
				success: function (response) {
					jQuery('.dc-preloader-section').remove();
					if (response.type === 'success') {                	
						jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000});
						jQuery('#feedbackmodal').modal('show');
					} else {                	                
						jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});               
					}
				}
			});
		}
	});
	
	/*get app link*/
	jQuery(document).on('click', '.dc-get-app', function (event) {
		event.preventDefault();
		var _this 		= jQuery(this);
		var _app_eamil	= _this.prev('input').val();
		if( _app_eamil === '' ){
			jQuery.sticky(scripts_vars.email_required, {classList: 'important', speed: 200, autoclose: 5000});
			return false;
		} else {
			jQuery.ajax({
				type: 'POST',
				url:  scripts_vars.ajaxurl,
				data: {
						action		: 'doccure_get_app_link',
						app_eamil	: _app_eamil,
						security	: scripts_vars.ajax_nonce
					},
				dataType: "json",
				success: function (response) {
					jQuery('body').find('.dc-preloader-section').remove(); 
					if (response.type === 'success') {
						jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000});
					} else { 
						jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});  

					}
				}
			});
		}
	});
	
	//feedback step 3 form submit 
	jQuery(document).on('click', '.dc-formfeedback-btn', function (event) {
        jQuery('body').append(loader_html);
        jQuery.ajax({
            type: 'POST',
            url:  scripts_vars.ajaxurl,
            data: 'security='+scripts_vars.ajax_nonce+'&'+jQuery('.dc-formfeedback').serialize() + '&action=doccure_add_feedback',
            dataType: "json",
            success: function (response) {
            	jQuery('body').find('.dc-preloader-section').remove(); 
                if (response.type === 'success') {					
                    jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000});
					window.location.reload();
                } else { 
                	jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});  
                }
            }
        });
    });
	
	//THEME VERTICAL SCROLLBAR 
	// if(jQuery('.dc-verticalscrollbar').length > 0){
	// 	var _dc_verticalscrollbar = jQuery('.dc-verticalscrollbar');
	// 	_dc_verticalscrollbar.mCustomScrollbar({
	// 		axis:"y",
	// 		//mouseWheelPixels: 5000,
	// 		scrollInertia: 100
	// 	});
	// }
	
	//THEME HORIZANTAL SCROLLBAR
	if(jQuery('.dc-horizontalthemescrollbar').length > 0){
		var _dc_horizontalthemescrollbar = jQuery('.dc-horizontalthemescrollbar');
		_dc_horizontalthemescrollbar.mCustomScrollbar({
			axis:"x",
			advanced:{autoExpandHorizontalScroll:true},
		});
	}
	
	//Question form submit
	jQuery(document).on('change', '.dc-booking-hospitals', function (event) {
		event.preventDefault();
		var _this 	= jQuery(this);
		var _id		= _this.val();
		var doctor_id		= _this.data('doctor_id');
		doccure_select_loaction(_id,doctor_id);
        
    });

	jQuery(document).on('load', '.dc-booking-hospitals_reschedule', function (event) {
		event.preventDefault();
		var _this 	= jQuery(this);
		var _id		= _this.val();
		var doctor_id		= _this.data('doctor_id');
		doccure_select_loaction(_id,doctor_id);
        
    });
	
	if(jQuery('#dc-calendar').length > 0){
		//render model js
		dcAppointmentModal();
	}
	
	//Booking step 1 form submit
	jQuery(document).on('click', '.dc-booking-step1-btn', function (event) {
        'use strict';
        event.preventDefault();
		
		var dcModalBody = jQuery("#dcModalBody");
		var modalBody1 	= jQuery("#dcModalBody1");
		var dcModalBody = jQuery("#dcModalBody");
		var dcBody2 	= jQuery("#dcbody2");
		var modalBody2 	= jQuery("#dcModalBody2");
		var btn 		= jQuery("#dcbtn");
		var btn2 		= jQuery("#dcbtn2");		
        var _this 	= jQuery(this);
		var _id     = parseInt(_this.data('id'));
		
        jQuery('body').append(loader_html);
		
        jQuery.ajax({
            type: 'POST',
            url: scripts_vars.ajaxurl,
            data: 'security='+scripts_vars.ajax_nonce+'&id=' + _id +'&'+jQuery('.dc-booking-step1').serialize() + '&action=doccure_booking_step1',
            dataType: "json",
            success: function (response) {
            	jQuery('body').find('.dc-preloader-section').remove();              
                if (response.type === 'success') {       
					
					if (response.booking_verification === 'skipe') {
						jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000});
						var btn3 = jQuery("#dcbtn3");
						var btn4 = jQuery("#dcbtn4");
						var dcModalBody1 = jQuery("#dcModalBody");
						var modalBody4 = jQuery("#dcModalBody4");

						jQuery(btn4).css('display', 'block');
						jQuery(modalBody4).css('display', 'block');
						jQuery(btn3).css('display', 'none');
						jQuery(dcModalBody1).css('display', 'none');
						
						if(response.checkout_option === 'yes') {
							jQuery('.dc-modal-footer').css('display', 'none');
							window.location = response.checkout_url;
						} else if(response.checkout_option === 'no') {
							jQuery('body').append(loader_html);
							jQuery.ajax({
								type: "POST",
								url: scripts_vars.ajaxurl,
								data: {
									action	: 'doccure_get_booking_byID',
									id		: response.booking_id,
									security	: scripts_vars.ajax_nonce,
									type		: 'patient',
								},
								dataType: "json",
								success: function (response) {
									jQuery('body').find('.dc-preloader-section').remove();
									if (response.type === 'success') {
										jQuery('.dc-offline-checkout').html(response.booking_data);
										jQuery('.dc-modal-footer').hide();
									} else {
										jQuery('.dc-offline-checkout').html('');
										jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
									}
								}
							});
						}
						
					} else {
					
						jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000});
						jQuery(modalBody2).css('display', 'block');
						jQuery(btn2).css('display', 'block');
						jQuery(modalBody1).css('overflow-y', 'hidden')
						jQuery(modalBody1).css('display', 'none');
						jQuery(btn).css('display', 'none');
						jQuery(dcModalBody).css('display', 'none');
					}
                } else {                	                
                	jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});               
                }
            }
        });
    });
	
	//Booking step 2 form submit
	jQuery(document).on('click', '.dc-booking-step2-btn', function (event) {
        'use strict';
        event.preventDefault();
		
		var dcBody2 = jQuery("#dcbody2");
		var modalBody2 = jQuery("#dcModalBody2");
		var modalBody3 = jQuery("#dcModalBody3");
		var btn3 = jQuery("#dcbtn3");
		var btn2 = jQuery("#dcbtn2");		
		
		var _this 	= jQuery(this);
		var _id     = parseInt(_this.data('id'));
        jQuery('body').append(loader_html);
		
        jQuery.ajax({
            type: 'POST',
            url: scripts_vars.ajaxurl,
            data: 'security='+scripts_vars.ajax_nonce+'&id=' + _id +'&'+jQuery('.dc-booking-step2').serialize() + '&action=doccure_booking_step2',
            dataType: "json",
            success: function (response) {
            	jQuery('body').find('.dc-preloader-section').remove();              
                if (response.type === 'success') {                	
                    jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000});
					jQuery(modalBody2).css('display', 'none');
					jQuery(btn3).css('display', 'block');
					jQuery(btn2).css('display', 'none');
					jQuery(dcBody2).css('display', 'block');
					jQuery(modalBody3).css('display', 'block');
					jQuery('.email_address').text(response.email);
					
                } else {                	                
                	jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});               
                }
            }
        });
    });
	
	//Booking step 3 form submit
	jQuery(document).on('click', '.dc-booking-step3-btn', function (event) {
        'use strict';
        event.preventDefault();
		var modalBody3 = jQuery("#dcModalBody3");
		var modalBody4 = jQuery("#dcModalBody4");
		var btn3 = jQuery("#dcbtn3");
		var btn4 = jQuery("#dcbtn4");		
		var _this 	= jQuery(this);
		var _id     = parseInt(_this.data('id'));
        jQuery('body').append(loader_html);
		
        jQuery.ajax({
            type: 'POST',
            url:  scripts_vars.ajaxurl,
            data: 'security='+scripts_vars.ajax_nonce+'&'+jQuery('.dc-booking-step3').serialize() + '&action=doccure_booking_step3',
            dataType: "json",
            success: function (response) {
            	jQuery('body').find('.dc-preloader-section').remove();
                if (response.type === 'success') {
                    jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000});
					
					jQuery(btn4).css('display', 'block');
					jQuery(modalBody4).css('display', 'block');
					jQuery(btn3).css('display', 'none');
					jQuery(modalBody3).css('display', 'none');
					if(response.checkout_option === 'yes') {
						window.location = response.checkout_url;
					} else if(response.checkout_option === 'no') {
						jQuery('body').append(loader_html);
						jQuery.ajax({
							type: "POST",
							url: scripts_vars.ajaxurl,
							data: {
								action	: 'doccure_get_booking_byID',
								id		: response.booking_id,
								security	: scripts_vars.ajax_nonce,
								type		: 'patient',
							},
							dataType: "json",
							success: function (response) {
								jQuery('body').find('.dc-preloader-section').remove();
								if (response.type === 'success') {
									jQuery('.dc-offline-checkout').html(response.booking_data);
									jQuery('.dc-modal-footer').hide();
								} else {
									jQuery('.dc-offline-checkout').html('');
									jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
								}
							}
						});
					}
					
                } else { 
                	jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});  
                }
            }
        });
    });

	
	//Newsletter form submit 
	jQuery(document).on('click', '.subscribe_me', function (event) {
        'use strict';
        event.preventDefault();
        var _this = jQuery(this);
        jQuery('body').append(loader_html);
		
        jQuery.ajax({
            type: 'POST',
            url: scripts_vars.ajaxurl,
            data: 'security='+scripts_vars.ajax_nonce+'&'+_this.parents('form').serialize() + '&action=doccure_subscribe_mailchimp',
            dataType: "json",
            success: function (response) {
            	jQuery('body').find('.dc-preloader-section').remove();              
                if (response.type === 'success') {                	
                    jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000});
                } else {                	                
                	jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});               
                }
            }
        });
    });
	
	//Question form submit
	jQuery(document).on('click', '.submit-question', function (event) {
        'use strict';
        event.preventDefault();
        var _this = jQuery(this);
        jQuery('body').append(loader_html);
        jQuery.ajax({
            type: 'POST',
            url: scripts_vars.ajaxurl,
            data: 'security='+scripts_vars.ajax_nonce+'&'+_this.parents('form').serialize() + '&action=doccure_question_submit',
            dataType: "json",
            success: function (response) {
            	jQuery('body').find('.dc-preloader-section').remove();              
                if (response.type === 'success') {                	
                    jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000});
					window.location.reload();
                } else {                	                
                	jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});               
                }
            }
        });
    });

	// Show all services
	jQuery(document).on('click', '.dc-viewall-services', function (e) { 
        e.preventDefault();
		var _this 	= jQuery(this);
		_this.hide();
		_this.nextAll().css( "display", "block" );
	});
	
	//Add to saved doctors
    jQuery(document).on('click', '.dc-add-wishlist', function (e) { 
        e.preventDefault();
		jQuery('body').append(loader_html);
        
		if (scripts_vars.is_loggedin == 'false') {
			jQuery('.dc-preloader-section').remove();
            jQuery.sticky(scripts_vars.wishlist_message, {classList: 'important', speed: 200, autoclose: 7000});
            return false;
        }
		
        var _this 		= jQuery(this);
        var id 			= _this.data('id') ;    
        var dataString 	= 'security='+scripts_vars.ajax_nonce+'&id=' + id + '&action=doccure_follow_doctors';
		
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: dataString,
            dataType: "json",
            success: function (response) {
               jQuery('.dc-preloader-section').remove();
               if (response.type === 'success') {
                    _this.removeClass('dc-add-wishlist');
                    _this.addClass('dc-liked');
                    jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000 });                   
                } else {
                    jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
                }
            }
        });           
    });
	
	
	/*OPEN CLOSE */
	jQuery('.dc-loginbtn, .dc-loginheader a').on('click', function(event){
		event.preventDefault();
		jQuery('.dc-loginarea .dc-loginformhold').slideToggle();
	});
		
	//DASHBOARD MENU
	if(jQuery('#dc-btnmenutoggle').length > 0){
		jQuery("#dc-btnmenutoggle").on('click', function(event) {
			event.preventDefault();
			jQuery('#dc-wrapper').toggleClass('dc-closemenu');
			jQuery('body').toggleClass('dc-noscroll');
			jQuery('.dc-navdashboard ul.sub-menu').hide();
		});
	}
	
	//ADD AND REMOVE CLASS
	if(jQuery('.dc-docsearch').length > 0){
		var _dc_docsearch = jQuery('.dc-docsearch');
		_dc_docsearch.on('click',function () {
			jQuery(this).parents('.dc-innerbanner-holder').addClass('dc-open');
			jQuery(this).parent().parents('.dc-innerbanner-holder').addClass('dc-opensearchs');
		});
		var _dc_home = jQuery('.dc-home');
		_dc_home.on('click',function () {
			jQuery('.dc-home').parents('.dc-innerbanner-holder').removeClass('dc-opensearchs');
		});
	}
	
	if(jQuery('.dc-docsearch.dc-serach-toggle').length > 0){
		jQuery('.dc-docsearch.dc-serach-toggle').on('click',function () {
			event.preventDefault();
			var _this 		= jQuery(this);
			_this.parents('.dc-advancedsearch').find('.advancefilters-wrap').toggle();
		});
	}

	//OPEN CLOSE
	jQuery('input[name=myself]').on('click', function(event){
		var _this 		= jQuery(this);
		var selected	= _this.val();
		if( selected === 'myself') {
			jQuery('.form-group-relation').hide();
		} else {
			jQuery('.form-group-relation').show();
		}
	});
	
	//ADD Class
	jQuery('#someelse').on('click', function() {
		var _this = jQuery(this);
		_this.parents('.dc-tabbtns').toggleClass('dc-tabbtnsactive');
	});
	
	//SEARCH CHOSEN
	jQuery(".chosen-select").select2({
	  templateResult: loadSelect2Images
	});
		
	function loadSelect2Images (option) {
		var flag = jQuery(option.element).attr('data-flag'); 
		if(!flag){
			return option.text;
		} else {                    
			var opt = jQuery(
			   '<span><img src="' + flag + '" width="18px" /> ' + option.text + '</span>'
			);

			return opt;
		}
	}

	//SEARCH CHOSEN
	jQuery(document).on('click', '.dc-docsearch', function(e){
		e.preventDefault();
		var _this = jQuery(this);
		_this.parents('.dc-opensearchs').find('.dc-advancedsearch-holder').slideToggle(400);
	});
	
	jQuery(document).on('change','.search_specialities, .search_type',function () {
        var _this = jQuery(this);
		var _sp_id = this.value;

		if(_sp_id){
			if (DT_Editor.elements[_sp_id]) {
				var _options = DT_Editor.elements[_sp_id];
			} else {
				var _options = [];
			}

			var load_repeater = wp.template('load-services-options'); 
			var data = {options: _options};        
			load_repeater = load_repeater(data);

			var _fields	= jQuery('.search_services').empty().append(load_repeater);
			jQuery('.search_services').trigger("chosen:updated");
		}
	});
	jQuery('.search_specialities').trigger('change');
	
	jQuery(document).on('change','.search_type',function () {
		 var _this = jQuery(this);
		if(_this.val() === 'doctors' ){
			jQuery('#gender_search').show();
		}else{
			jQuery('#gender_search').hide();
		}
	});

	setTimeout(function(){
		jQuery('.search_type').trigger("change");
		jQuery('.search_specialities').trigger("change");
	}, 500);
	
	//Order form submit
	jQuery(document).on('change','.order',function () {
		var _this 	= jQuery(this);
		var _val	= _this.val();
		jQuery('.search_order').val(_val);
		jQuery('#search_form').submit();
	});
	
	//Orderby form submit
	jQuery(document).on('change','.dc-select .orderby',function () {
		var _this 	= jQuery(this);
		var _val	= _this.val();
		jQuery('.search_orderby').val(_val);
		jQuery('#search_form').submit();
	});
	
	//Health form order by
	jQuery(document).on('change','.orderby_healthforum',function () {
		var _this 	= jQuery(this);
		var _val	= _this.val();
		jQuery('#search_orderby_healthforum').val(_val);
		jQuery('#search_form_healthforum').submit();
	});
	
	//Reset Button
	jQuery(document).on('click','.dc-resetbtn',function () {
		jQuery('#search_form')[0].reset();
	});

	//Resend Verification Code for booking  
	jQuery(document).on('click', '.dc-resend-booking-code', function (e) { 
        e.preventDefault();
        var _this = jQuery(this);       
        jQuery('body').append(loader_html);        
        var dataString = 'security='+scripts_vars.ajax_nonce+'&action=doccure_booking_resend_code';
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: dataString,
            dataType: "json",
            success: function (response) {
                jQuery('.dc-preloader-section').remove();
               if (response.type === 'success') {
                    jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000 });                   
                } else {
                    jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
                }
            }
        });           
    });

	//Clone Link
	jQuery('#dc-clone').click(function(){
		var value	= jQuery('#dc-profile-url').val();
		var tempInput = document.createElement("input");
		tempInput.style = "position: absolute; left: -1000px; top: -1000px";
		tempInput.value = value;
		document.body.appendChild(tempInput);
		tempInput.select();
		document.execCommand("copy");
		document.body.removeChild(tempInput);
		jQuery.sticky(scripts_vars.copy_profile_msg, {classList: 'success', speed: 200, autoclose: 5000});
	});

	if(is_loggedin == 'false'){
		//Registration Step One    
		jQuery(document).on('click', '.rg-step-start', function (e) { 
			e.preventDefault();
			var _this = jQuery(this);
			jQuery('body').append(loader_html);        
			var dataString = jQuery('.dc-formregister').serialize()+'&security='+scripts_vars.ajax_nonce+'&action=doccure_process_registration';
			jQuery.ajax({
				type: "POST",
				url: scripts_vars.ajaxurl,
				data: dataString,
				dataType: "json",
				success: function (response) {
				   jQuery('.dc-preloader-section').remove();
				   if (response.type === 'success') {
						jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000 });
						window.location.replace(response.retrun_url);
					} else {
						jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
					}
				}
			});           
		});

		//Reset password Ajax    
		jQuery(document).on('click', '.dc-change-password', function (event) {
			event.preventDefault();
			var _this = jQuery(this);       
			jQuery('body').append(loader_html);
	
			jQuery.ajax({
				type: "POST",
				url: scripts_vars.ajaxurl,
				data: 'security='+scripts_vars.ajax_nonce+'&'+jQuery('.dc-reset_password_form').serialize() + '&action=doccure_ajax_reset_password',
				dataType: "json",
				success: function (response) {
					jQuery('body').find('.dc-preloader-section').remove();
					if (response.type == 'success') {
						jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000 });
						jQuery('.dc-reset_password_form').get(0).reset();
						window.location.replace(response.redirect_url);                   
					} else {                  
						jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
					}
				}
			});
		});
			
		//Login Ajax    
		jQuery(document).on('click', '.do-login-button', function (event) {
			event.preventDefault();
			var _this = jQuery(this);
			jQuery('body').append(loader_html);
			var _serialize = _this.parents('form.do-login-form').serialize();
			jQuery.ajax({
				type: "POST",
				url: scripts_vars.ajaxurl,
				data: 'security='+scripts_vars.ajax_nonce+'&'+_serialize + '&action=doccure_ajax_login',
				dataType: "json",
				success: function (response) {
					jQuery('body').find('.dc-preloader-section').remove();
					if (response.type === 'success') {
						jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 500000, position: 'top-right'});
						window.location.replace(response.redirect);                    
					} else {                   
						jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
					}
				}
			});
		});
		
		//Lost passowrd Box    
		jQuery('.dc-forgot-password').on('click', function (e) {     
			jQuery('.do-login-form').addClass('dc-hide-form');
			jQuery('.dc-loginheader span').html(scripts_vars.forgot_password);
			jQuery('.do-forgot-password-form').removeClass('dc-hide-form');
		});
		jQuery('.dc-show-login').on('click', function (e) {       
			jQuery('.do-login-form').removeClass('dc-hide-form');
			jQuery('.dc-loginheader span').text(scripts_vars.login);
			jQuery('.do-forgot-password-form').addClass('dc-hide-form');
		});
		
		//Lost password Ajax
		jQuery(document).on('click', '.do-get-password', function (event) {
			event.preventDefault();
			var _this = jQuery(this);
			var _email = jQuery('.get_password').val();       
			jQuery('body').append(loader_html);
	
			if (!(doccure_isValidEmailAddress(_email))) {
				jQuery('body').find('.dc-preloader-section').remove();
				jQuery.sticky(scripts_vars.valid_email, {classList: 'important', speed: 200, autoclose: 5000});
				return false;
			}
	
			jQuery.ajax({
				type: "POST",
				url: scripts_vars.ajaxurl,
				data: 'security='+scripts_vars.ajax_nonce+'&'+jQuery('.do-forgot-password-form').serialize() + '&action=doccure_ajax_lp',
				dataType: "json",
				success: function (response) {
					jQuery('body').find('.dc-preloader-section').remove();
					if (response.type == 'success') {
						jQuery('.do-forgot-password-form').get(0).reset();
						jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000 });                                       
					} else {                   
						jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
					}
				}
			});
		});
		
		//Email Validation    
		function doccure_isValidEmailAddress(emailAddress) {
			var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
			return pattern.test(emailAddress);
		} 
	}
	
});

function doccuregetParameterByName(name, url = window.location.href) {
	name = name.replace(/[\[\]]/g, '\\$&');
	var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
		results = regex.exec(url);
	if (!results) return null;
	if (!results[2]) return '';
	return decodeURIComponent(results[2].replace(/\+/g, ' '));
}


//Preloader
jQuery(window).load(function () {
	var loading_duration = scripts_vars.loading_duration;
    jQuery(".preloader-outer").delay(loading_duration).fadeOut();
    jQuery(".pins").delay(loading_duration).fadeOut("slow");
});


//Sticky Note
!function(t){t.sticky=t.fn.sticky=function(e,i,s){"function"==typeof i&&(s=i);var o=function(t){var e=0,i=0,s="",o=t.length;if(0===o)return e;for(i=0;o>i;i++)s=t.charCodeAt(i),e=(e<<5)-e+s,e&=e;return"s"+Math.abs(e)},n={position:"top-right",speed:"fast",allowdupes:!0,autoclose:5e3,classList:""},a=o(e),c=!0,l=!1,r='<div id="ID" class="dc-alert alert-dismissible border-POS CLASSLIST" role="alert"><button type="button" class="dc-close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"></span></button><div class="dc-description"><p><i class="fa fa-bullhorn"></i>NOTE</p></div></div>',d=["top-right","top-center","top-left","bottom-right","bottom-center","bottom-left"];i&&t.extend(n,i),t(".sticky").each(function(){t(this).attr("id")===o(e)&&(l=!0,n.allowdupes||(c=!1)),t(this).attr("id")===a&&(a=o(e))}),t(".sticky-queue").length?t(".sticky-queue").removeClass(d.join(" ")).addClass(n.position):t("body").append('<div class="sticky-queue '+n.position+'">'),c&&t(".sticky-queue").prepend(r.replace("POS",n.position).replace("ID",a).replace("NOTE",e).replace("CLASSLIST",n.classList)).find("#"+a).slideDown(n.speed,function(){c=!0,s&&"function"==typeof s&&s({id:a,duplicate:l,displayed:c})}),t(".sticky").ready(function(){n.autoclose&&t("#"+a).delay(n.autoclose).fadeOut(n.speed,function(){t(this).remove()})}),t(".sticky-close").on("click",function(){t("#"+t(this).parent().attr("id")).dequeue().fadeOut(n.speed,function(){t(this).remove()})})}}(jQuery);


// Confirm Box
!function(n){n.confirm=function(i){if(n("#confirmOverlay").length)return!1;var o="";n.each(i.buttons,function(n,i){n="Yes"==n?scripts_vars.yes:"No"==n?scripts_vars.no:n,o+='<a href="#" class="button '+i.class+'">'+n+"<span></span></a>",i.action||(i.action=function(){})});var t=['<div id="confirmOverlay">','<div id="confirmBox">',"<h1>",i.title,"</h1>","<p>",i.message,"</p>",'<div id="confirmButtons">',o,"</div></div></div>"].join("");n(t).hide().appendTo("body").fadeIn();var c=n("#confirmBox .button"),r=0;n.each(i.buttons,function(i,o){c.eq(r++).on("click",function(){return o.action(),n.confirm.hide(),!1})})},n.confirm.hide=function(){n("#confirmOverlay").fadeOut(function(){n(this).remove()})}}(jQuery);

//Check Numeric Value only
!function(e){e.fn.numeric=function(t,i){"boolean"==typeof t&&(t={decimal:t}),void 0===(t=t||{}).negative&&(t.negative=!0);var n,a,r=!1===t.decimal?"":t.decimal||".",c=!0===t.negative;return i="function"==typeof i?i:function(){},"number"==typeof t.scale?0==t.scale?(r=!1,n=-1):n=t.scale:n=-1,a="number"==typeof t.precision?t.precision:0,this.data("numeric.decimal",r).data("numeric.negative",c).data("numeric.callback",i).data("numeric.scale",n).data("numeric.precision",a).keypress(e.fn.numeric.keypress).keyup(e.fn.numeric.keyup).blur(e.fn.numeric.blur)},e.fn.numeric.keypress=function(t){var i=e.data(this,"numeric.decimal"),n=e.data(this,"numeric.negative"),a=t.charCode?t.charCode:t.keyCode?t.keyCode:0;if(13==a&&"input"==this.nodeName.toLowerCase())return!0;if(13==a)return!1;var r=!1;if(t.ctrlKey&&97==a||t.ctrlKey&&65==a)return!0;if(t.ctrlKey&&120==a||t.ctrlKey&&88==a)return!0;if(t.ctrlKey&&99==a||t.ctrlKey&&67==a)return!0;if(t.ctrlKey&&122==a||t.ctrlKey&&90==a)return!0;if(t.ctrlKey&&118==a||t.ctrlKey&&86==a||t.shiftKey&&45==a)return!0;if(a<48||a>57){var c=e(this).val();if(0!==c.indexOf("-")&&n&&45==a&&(0===c.length||0===parseInt(e.fn.getSelectionStart(this),10)))return!0;i&&a==i.charCodeAt(0)&&-1!=c.indexOf(i)&&(r=!1),8!=a&&9!=a&&13!=a&&35!=a&&36!=a&&37!=a&&39!=a&&46!=a?r=!1:void 0!==t.charCode&&(t.keyCode==t.which&&0!==t.which?(r=!0,46==t.which&&(r=!1)):0!==t.keyCode&&0===t.charCode&&0===t.which&&(r=!0)),i&&a==i.charCodeAt(0)&&(r=-1==c.indexOf(i))}else if(e.data(this,"numeric.scale")>=0){var s=this.value.indexOf(i);s>=0?(decimalsQuantity=this.value.length-s-1,e.fn.getSelectionStart(this)>s?r=decimalsQuantity<e.data(this,"numeric.scale"):(integersQuantity=this.value.length-1-decimalsQuantity,r=integersQuantity<e.data(this,"numeric.precision")-e.data(this,"numeric.scale"))):r=!(e.data(this,"numeric.precision")>0)||this.value.replace(e.data(this,"numeric.decimal"),"").length<e.data(this,"numeric.precision")-e.data(this,"numeric.scale")}else r=!(e.data(this,"numeric.precision")>0)||this.value.replace(e.data(this,"numeric.decimal"),"").length<e.data(this,"numeric.precision");return r},e.fn.numeric.keyup=function(t){var i=e(this).val();if(i&&i.length>0){var n=e.fn.getSelectionStart(this),a=e.data(this,"numeric.decimal"),r=e.data(this,"numeric.negative");if(""!==a&&null!==a){var c=i.indexOf(a);0===c&&(this.value="0"+i),1==c&&"-"==i.charAt(0)&&(this.value="-0"+i.substring(1)),i=this.value}for(var s=[0,1,2,3,4,5,6,7,8,9,"-",a],u=i.length,l=u-1;l>=0;l--){var h=i.charAt(l);0!==l&&"-"==h?i=i.substring(0,l)+i.substring(l+1):0!==l||r||"-"!=h||(i=i.substring(1));for(var d=!1,o=0;o<s.length;o++)if(h==s[o]){d=!0;break}d&&" "!=h||(i=i.substring(0,l)+i.substring(l+1))}var m=i.indexOf(a);if(m>0){for(var f=u-1;f>m;f--){i.charAt(f)==a&&(i=i.substring(0,f)+i.substring(f+1))}e.data(this,"numeric.scale")>=0&&(i=i.substring(0,m+e.data(this,"numeric.scale")+1)),e.data(this,"numeric.precision")>0&&(i=i.substring(0,e.data(this,"numeric.precision")+1))}else e.data(this,"numeric.precision")>0&&(i=i.substring(0,e.data(this,"numeric.precision")-e.data(this,"numeric.scale")));this.value=i,e.fn.setSelection(this,n)}},e.fn.numeric.blur=function(){var t=e.data(this,"numeric.decimal"),i=e.data(this,"numeric.callback"),n=this.value;""!==n&&(new RegExp("^\\d+$|^\\d*"+t+"\\d+$").exec(n)||i.apply(this))},e.fn.removeNumeric=function(){return this.data("numeric.decimal",null).data("numeric.negative",null).data("numeric.callback",null).unbind("keypress",e.fn.numeric.keypress).unbind("blur",e.fn.numeric.blur)},e.fn.getSelectionStart=function(e){if(e.createTextRange){var t=document.selection.createRange().duplicate();return t.moveEnd("character",e.value.length),""===t.text?e.value.length:e.value.lastIndexOf(t.text)}return e.selectionStart},e.fn.setSelection=function(e,t){if("number"==typeof t&&(t=[t,t]),t&&t.constructor==Array&&2==t.length)if(e.createTextRange){var i=e.createTextRange();i.collapse(!0),i.moveStart("character",t[0]),i.moveEnd("character",t[1]),i.select()}else e.setSelectionRange&&(e.focus(),e.setSelectionRange(t[0],t[1]))}}(jQuery);

//get distance
function _get_distance(lat1, lon1, lat2, lon2, unit) {
    var radlat1 = Math.PI * lat1 / 180
    var radlat2 = Math.PI * lat2 / 180
    var theta = lon1 - lon2
    var radtheta = Math.PI * theta / 180
    var dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
    dist = Math.acos(dist)
    dist = dist * 180 / Math.PI
    dist = dist * 60 * 1.1515
    if (unit == "K") {
        dist = dist * 1.609344
    }
    if (unit == "N") {
        dist = dist * 0.8684
    }
    return dist
}

// get rounded value
function _get_round(value, decimals) {
    return Number(Math.round(value + 'e' + decimals) + 'e-' + decimals);
}

// string replace URL
function _string_replace_url(url) {
    var _url = url.replace("#038;", "&");
	_url = _url.replace("&", "&");
	return _url;
}

//Map styles
function doccure_get_map_styles(style) {

    var styles = '';
    if (style == 'view_1') {
        var styles = [{"featureType": "administrative.country", "elementType": "geometry", "stylers": [{"visibility": "simplified"}, {"hue": "#ff0000"}]}];
    } else if (style == 'view_2') {
        var styles = [{"featureType": "water", "elementType": "all", "stylers": [{"hue": "#7fc8ed"}, {"saturation": 55}, {"lightness": -6}, {"visibility": "on"}]}, {"featureType": "water", "elementType": "labels", "stylers": [{"hue": "#7fc8ed"}, {"saturation": 55}, {"lightness": -6}, {"visibility": "off"}]}, {"featureType": "poi.park", "elementType": "geometry", "stylers": [{"hue": "#83cead"}, {"saturation": 1}, {"lightness": -15}, {"visibility": "on"}]}, {"featureType": "landscape", "elementType": "geometry", "stylers": [{"hue": "#f3f4f4"}, {"saturation": -84}, {"lightness": 59}, {"visibility": "on"}]}, {"featureType": "landscape", "elementType": "labels", "stylers": [{"hue": "#ffffff"}, {"saturation": -100}, {"lightness": 100}, {"visibility": "off"}]}, {"featureType": "road", "elementType": "geometry", "stylers": [{"hue": "#ffffff"}, {"saturation": -100}, {"lightness": 100}, {"visibility": "on"}]}, {"featureType": "road", "elementType": "labels", "stylers": [{"hue": "#bbbbbb"}, {"saturation": -100}, {"lightness": 26}, {"visibility": "on"}]}, {"featureType": "road.arterial", "elementType": "geometry", "stylers": [{"hue": "#ffcc00"}, {"saturation": 100}, {"lightness": -35}, {"visibility": "simplified"}]}, {"featureType": "road.highway", "elementType": "geometry", "stylers": [{"hue": "#ffcc00"}, {"saturation": 100}, {"lightness": -22}, {"visibility": "on"}]}, {"featureType": "poi.school", "elementType": "all", "stylers": [{"hue": "#d7e4e4"}, {"saturation": -60}, {"lightness": 23}, {"visibility": "on"}]}];
    } else if (style == 'view_3') {
        var styles = [{"featureType": "water", "stylers": [{"saturation": 43}, {"lightness": -11}, {"hue": "#0088ff"}]}, {"featureType": "road", "elementType": "geometry.fill", "stylers": [{"hue": "#ff0000"}, {"saturation": -100}, {"lightness": 99}]}, {"featureType": "road", "elementType": "geometry.stroke", "stylers": [{"color": "#808080"}, {"lightness": 54}]}, {"featureType": "landscape.man_made", "elementType": "geometry.fill", "stylers": [{"color": "#ece2d9"}]}, {"featureType": "poi.park", "elementType": "geometry.fill", "stylers": [{"color": "#ccdca1"}]}, {"featureType": "road", "elementType": "labels.text.fill", "stylers": [{"color": "#767676"}]}, {"featureType": "road", "elementType": "labels.text.stroke", "stylers": [{"color": "#ffffff"}]}, {"featureType": "poi", "stylers": [{"visibility": "off"}]}, {"featureType": "landscape.natural", "elementType": "geometry.fill", "stylers": [{"visibility": "on"}, {"color": "#b8cb93"}]}, {"featureType": "poi.park", "stylers": [{"visibility": "on"}]}, {"featureType": "poi.sports_complex", "stylers": [{"visibility": "on"}]}, {"featureType": "poi.medical", "stylers": [{"visibility": "on"}]}, {"featureType": "poi.business", "stylers": [{"visibility": "simplified"}]}];
    } else if (style == 'view_4') {
        var styles = [{"elementType": "geometry", "stylers": [{"hue": "#ff4400"}, {"saturation": -68}, {"lightness": -4}, {"gamma": 0.72}]}, {"featureType": "road", "elementType": "labels.icon"}, {"featureType": "landscape.man_made", "elementType": "geometry", "stylers": [{"hue": "#0077ff"}, {"gamma": 3.1}]}, {"featureType": "water", "stylers": [{"hue": "#00ccff"}, {"gamma": 0.44}, {"saturation": -33}]}, {"featureType": "poi.park", "stylers": [{"hue": "#44ff00"}, {"saturation": -23}]}, {"featureType": "water", "elementType": "labels.text.fill", "stylers": [{"hue": "#007fff"}, {"gamma": 0.77}, {"saturation": 65}, {"lightness": 99}]}, {"featureType": "water", "elementType": "labels.text.stroke", "stylers": [{"gamma": 0.11}, {"weight": 5.6}, {"saturation": 99}, {"hue": "#0091ff"}, {"lightness": -86}]}, {"featureType": "transit.line", "elementType": "geometry", "stylers": [{"lightness": -48}, {"hue": "#ff5e00"}, {"gamma": 1.2}, {"saturation": -23}]}, {"featureType": "transit", "elementType": "labels.text.stroke", "stylers": [{"saturation": -64}, {"hue": "#ff9100"}, {"lightness": 16}, {"gamma": 0.47}, {"weight": 2.7}]}];
    } else if (style == 'view_5') {
        var styles = [{"featureType": "water", "elementType": "geometry", "stylers": [{"color": "#e9e9e9"}, {"lightness": 17}]}, {"featureType": "landscape", "elementType": "geometry", "stylers": [{"color": "#f5f5f5"}, {"lightness": 20}]}, {"featureType": "road.highway", "elementType": "geometry.fill", "stylers": [{"color": "#ffffff"}, {"lightness": 17}]}, {"featureType": "road.highway", "elementType": "geometry.stroke", "stylers": [{"color": "#ffffff"}, {"lightness": 29}, {"weight": 0.2}]}, {"featureType": "road.arterial", "elementType": "geometry", "stylers": [{"color": "#ffffff"}, {"lightness": 18}]}, {"featureType": "road.local", "elementType": "geometry", "stylers": [{"color": "#ffffff"}, {"lightness": 16}]}, {"featureType": "poi", "elementType": "geometry", "stylers": [{"color": "#f5f5f5"}, {"lightness": 21}]}, {"featureType": "poi.park", "elementType": "geometry", "stylers": [{"color": "#dedede"}, {"lightness": 21}]}, {"elementType": "labels.text.stroke", "stylers": [{"visibility": "on"}, {"color": "#ffffff"}, {"lightness": 16}]}, {"elementType": "labels.text.fill", "stylers": [{"saturation": 36}, {"color": "#333333"}, {"lightness": 40}]}, {"elementType": "labels.icon", "stylers": [{"visibility": "off"}]}, {"featureType": "transit", "elementType": "geometry", "stylers": [{"color": "#f2f2f2"}, {"lightness": 19}]}, {"featureType": "administrative", "elementType": "geometry.fill", "stylers": [{"color": "#fefefe"}, {"lightness": 20}]}, {"featureType": "administrative", "elementType": "geometry.stroke", "stylers": [{"color": "#fefefe"}, {"lightness": 17}, {"weight": 1.2}]}];
    } else if (style == 'view_6') {
        var styles = [{"featureType": "landscape", "stylers": [{"hue": "#FFBB00"}, {"saturation": 43.400000000000006}, {"lightness": 37.599999999999994}, {"gamma": 1}]}, {"featureType": "road.highway", "stylers": [{"hue": "#FFC200"}, {"saturation": -61.8}, {"lightness": 45.599999999999994}, {"gamma": 1}]}, {"featureType": "road.arterial", "stylers": [{"hue": "#FF0300"}, {"saturation": -100}, {"lightness": 51.19999999999999}, {"gamma": 1}]}, {"featureType": "road.local", "stylers": [{"hue": "#FF0300"}, {"saturation": -100}, {"lightness": 52}, {"gamma": 1}]}, {"featureType": "water", "stylers": [{"hue": "#0078FF"}, {"saturation": -13.200000000000003}, {"lightness": 2.4000000000000057}, {"gamma": 1}]}, {"featureType": "poi", "stylers": [{"hue": "#00FF6A"}, {"saturation": -1.0989010989011234}, {"lightness": 11.200000000000017}, {"gamma": 1}]}];
    } else {
        var styles = [{"featureType": "administrative.country", "elementType": "geometry", "stylers": [{"visibility": "simplified"}, {"hue": "#ff0000"}]}];
    }
    return styles;
}

//convert bytes to KB< MB,GB,TB
function bytesToSize(bytes) {
   var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
   if (bytes == 0) return '0 Byte';
   var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
   return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
};

//validate amount
function validateAmount(_this) {
    if (isNaN(jQuery.trim(jQuery(_this).val()))) {
        jQuery(_this).val("");
    } else {
        var amt = jQuery(_this).val();
        if (amt != '') {
            if (amt.length > 16) {
                amt = amt.substr(0, 16);
                jQuery(_this).val(amt);
            }
            //amount = amt;
            return true;
        } else {
            //amount = gloAmount;
            return true;
        }
    }
}

//get random ID
function get_random_number() {
  function s4() {
    return Math.floor((1 + Math.random()) * 0x10000)
      .toString(16)
      .substring(1);
  }
  return s4();
}

//Cookie
!function(e){"function"==typeof define&&define.amd?define(["jquery"],e):"object"==typeof exports?module.exports=e(require("jquery")):e(jQuery)}(function(e){var n=/\+/g;function o(e){return t.raw?e:encodeURIComponent(e)}function i(e){return o(t.json?JSON.stringify(e):String(e))}function r(o,i){var r=t.raw?o:function(e){0===e.indexOf('"')&&(e=e.slice(1,-1).replace(/\\"/g,'"').replace(/\\\\/g,"\\"));try{return e=decodeURIComponent(e.replace(n," ")),t.json?JSON.parse(e):e}catch(e){}}(o);return e.isFunction(i)?i(r):r}var t=e.cookie=function(n,c,u){if(arguments.length>1&&!e.isFunction(c)){if("number"==typeof(u=e.extend({},t.defaults,u)).expires){var s=u.expires,a=u.expires=new Date;a.setMilliseconds(a.getMilliseconds()+864e5*s)}return document.cookie=[o(n),"=",i(c),u.expires?"; expires="+u.expires.toUTCString():"",u.path?"; path="+u.path:"",u.domain?"; domain="+u.domain:"",u.secure?"; secure":""].join("")}for(var d,f=n?void 0:{},p=document.cookie?document.cookie.split("; "):[],l=0,m=p.length;l<m;l++){var x=p[l].split("="),g=(d=x.shift(),t.raw?d:decodeURIComponent(d)),v=x.join("=");if(n===g){f=r(v,c);break}n||void 0===(v=r(v))||(f[g]=v)}return f};t.defaults={},e.removeCookie=function(n,o){return e.cookie(n,"",e.extend({},o,{expires:-1})),!e.cookie(n)}});

/* THEME ACCORDION */
function themeAccordion() {
	jQuery('.dc-panelcontent').hide();
	jQuery('.dc-accordion .dc-paneltitle:first').addClass('active').next().slideDown('slow');
	jQuery('.dc-accordion .dc-paneltitle').on('click',function() {
		if(jQuery(this).next().is(':hidden')) {
			jQuery('.dc-accordion .dc-paneltitle').removeClass('active').next().slideUp('slow');
			jQuery(this).toggleClass('active').next().slideDown('slow');
		}
	});
}

function childAccordion() {
	jQuery('.dc-subpanelcontent').hide();
	jQuery('.dc-childaccordion .dc-subpaneltitle:first').addClass('active').next().slideDown('slow');
	jQuery('.dc-childaccordion .dc-subpaneltitle').on('click',function() {
		if(jQuery(this).next().is(':hidden')) {
			jQuery('.dc-childaccordion .dc-subpaneltitle').removeClass('active').next().slideUp('slow');
			jQuery(this).toggleClass('active').next().slideDown('slow');
		}
	});
}

//DC Modal
function dcAppointmentModal(){
  var dcmodal = jQuery("#appointment");
  jQuery(dcmodal).on('shown.bs.modal', function () {
	var calendarEl = document.getElementById('dc-calendar');
	

	var calendar = new FullCalendar.Calendar(calendarEl, {
		locale: 'en',
		initialView: 'dayGridMonth',
		height: 'auto',
		headerToolbar:{
			left:   'title',
			center: '',
			right:  'prev,next'
		},
		validRange: function(nowDate) {
			return {
			  start: nowDate,
			}
		},
		dateClick: function(date, jsEvent, view) {
			var _date			= date.dateStr;
			var _hospital_id	= jQuery('.dc-booking-hospitals').val();
			jQuery('#dc-calendar .fc-state-highlight').removeClass('fc-state-highlight fc-today-clicked');
			jQuery('#dc-calendar td[data-date='+_date+']').addClass('fc-state-highlight fc-today-clicked');

			if( _hospital_id == '' ){
				jQuery.sticky(scripts_vars.location_required, {classList: 'important',position:'top-right', speed: 200, autoclose: 5000});
				return false;
			}

			jQuery('body').append(loader_html);
			var dataString 	  = 'security='+scripts_vars.ajax_nonce+'&_date='+_date+'&_hospital_id='+_hospital_id+'&action=doccure_get_slots';   
			jQuery.ajax({
				type: 'POST',
				url: scripts_vars.ajaxurl,
				data: dataString,
				dataType: 'json',
				success: function (response) {
					jQuery('body').find('.dc-preloader-section').remove();
					if (response.type === 'success') {
						jQuery('.dc-update-timeslots').html(response.time_slots);
						jQuery('#appointment_date').val(_date);
					} else {
						jQuery('.dc-update-timeslots').html('');
						jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
					}
				}
			});
		},
	});
	calendar.render();
  });
}

// Email validtion
function doccure_validate_email(email) {
	var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	return regex.test(email);
}

//Get URL params
var doccureGetUrlParameter = function getUrlParameter(sParam, is_array) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;
    var array_data = [];
    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');
		alert(sParameterName);
        if (is_array === 'yes') {
            if (sParameterName[0] === sParam) {
                sParameterName[1] === undefined ? true : array_data.push(sParameterName[1]);
            }
        } else {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }

    return array_data;
};

function doccure_select_loaction(_id,doctor_id){
	
	jQuery('body').append(loader_html);
	jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: {
				action	: 'doccure_get_booking_data',
				id		: _id,
				doctor_id		: doctor_id,
				security		: scripts_vars.ajax_nonce
			},
			dataType: "json",
			success: function (response) {
				jQuery('body').find('.dc-preloader-section').remove();
				if (response.type === 'success') {
					jQuery('#booking_service_select').html(response.booking_services);
					jQuery('.dc-update-timeslots').html(response.time_slots);
					jQuery('#booking_fee').html(response.consultant_fee);
					jQuery('.dc-checkbox-service').on('change', function() {
						var _this 			= jQuery(this);
						var _id				= _this.val();
						var _text			= _this.data('title');
						var _price_formate	= _this.data('price-formate');
						var _price			= _this.data('price');
						var total_price		= jQuery('#dc-total-price').data('price');
						var consultant_fee	= jQuery('.dc-consultant-fee').data('price');
						
						var prinice_array = [];
						jQuery('.dc-checkbox-service:checkbox:checked').each(function () {
							prinice_array.push(jQuery(this).attr('data-price'));
						  });
						  
						var _service_price	= '';
						var service			= '';
						if(_this.is(":checked") ) {	
							service			= 'check';
						} else {
							service			= 'uncheck';
						}
						jQuery('body').append(loader_html);
						jQuery.ajax({
							type: "POST",
							url: scripts_vars.ajaxurl,
							data: {
									allprices		: prinice_array,
									price			: _price,
									consultant_fee	: consultant_fee,
									action 			: 'doccure_calcute_price',
									security		: scripts_vars.ajax_nonce
							},
							dataType: "json",
							success: function (response) {
								jQuery('.dc-preloader-section').remove();
								_service_price	= '<li id="dc-service-'+_id+'"><span>'+_text+'<em>'+response.price_format+' <span class="  toltip-content dc-service-price" data-price="'+_price+'"></span></em></span></li>';
								if(service == 'check' ) {							
									jQuery('#consultant_fee').after(_service_price);
								} else {
									jQuery('#dc-service-'+_id).remove();
								}
								
								jQuery('#dc-total-price').attr('data-price',response.total_price);
								jQuery('body').find('.dc-preloader-section').remove();
								if (response.type === 'success') {
									jQuery('#dc-total-price').html(response.total_price_format);
									                    
								} else {                   
									//jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
								}
							}
						});
						
				   });
					
					themeAccordion();
					
				} else {
					jQuery('#booking_service_select').html('');
					jQuery('.dc-update-timeslots').html('');
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
				}
			}
		});
}
//SVG Render
jQuery("img.amsvglogo").each(function(){var t=jQuery(this),r=t.attr("id"),a=t.attr("class"),e=t.attr("src");jQuery.get(e,function(e){var i=jQuery(e).find("svg");void 0!==r&&(i=i.attr("id",r)),void 0!==a&&(i=i.attr("class",a+" replaced-svg")),i=i.removeAttr("xmlns:a"),t.replaceWith(i)},"xml")});
