"use strict";
var calendar_locale  = scripts_vars.calendar_locale;
jQuery(document).on('ready', function() {
	var loader_html 	= '<div class="dc-preloader-section"><div class="dc-preloader-holder"><div class="dc-loader"></div></div></div>';
    var package_update		= scripts_vars.package_update;
    var listing_type		= scripts_vars.listing_type;
	var calendar_locale  	= scripts_vars.calendar_locale;
	var startweekday  		= scripts_vars.startweekday;
	var calendar_format  	= scripts_vars.calendar_format;
	
	if( calendar_locale  && calendar_locale != null){
		jQuery.datetimepicker.setLocale(calendar_locale);
		moment.locale(calendar_locale);
    }
    
	//sortable for phone, membership and videos
	if(jQuery('.dc-sortable-list').length > 0){
		jQuery( ".dc-sortable-list" ).sortable();
	}
	
	//submit form
    jQuery('.dc-header-serach-form').on('click', function(){
        jQuery('.dc-form-advancedsearch').submit();
    });
	
	//numeric field
	jQuery(".custom_spaces").numeric({ decimal : false,  negative : false });

    /*  ADD CLASS*/
	jQuery(document).on('click','.dc-removeform', function($){
		var _this = jQuery(this);
		_this.parents('.dc-headerform-holder').removeClass('show-sform');
	});
	jQuery(document).on('click','.dc-headerform-holder .dc-searchbtn', function($){
		var _this = jQuery(this);
		_this.parents('.dc-headerform-holder').addClass('show-sform');
    });
    
	//Resend verification
	jQuery(document).on('click', '.re-sendverification .alert-warning a', function (event) {
        event.preventDefault();		
        var _this 	= jQuery(this);
        jQuery('body').append(loader_html);
		
        jQuery.ajax({
            type: 'POST',
            url : scripts_vars.ajaxurl,
            data: 'security='+scripts_vars.ajax_nonce+'&action=doccure_resend_verification',
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
	

    //Book Reschedule
    jQuery(document).on('click', '.dc-booking-doctor-reschedule', function (event) {
        event.preventDefault();		
        var _this 	= jQuery(this);
		var _id     = parseInt(_this.data('id'));
		var total_price     = jQuery('#dc-total-price').attr('data-price');
        jQuery('body').append(loader_html);
		
        jQuery.ajax({
            type: 'POST',
            url: scripts_vars.ajaxurl,
            data: 'security='+scripts_vars.ajax_nonce+'&id=' + _id +'&total_price='+total_price +'&'+jQuery('.dc-booking-doctor').serialize() + '&action=doccure_booking_doctor_reschedule',
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

	//Book doctor
    jQuery(document).on('click', '.dc-booking-doctor-btn', function (event) {
        event.preventDefault();		
        var _this 	= jQuery(this);
		var _id     = parseInt(_this.data('id'));
		var total_price     = jQuery('#dc-total-price').attr('data-price');
        jQuery('body').append(loader_html);
		
        jQuery.ajax({
            type: 'POST',
            url: scripts_vars.ajaxurl,
            data: 'security='+scripts_vars.ajax_nonce+'&id=' + _id +'&total_price='+total_price +'&'+jQuery('.dc-booking-doctor').serialize() + '&action=doccure_booking_doctor',
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

	jQuery(".datepicker").datetimepicker({
		format: scripts_vars.calendar_format,
		dayOfWeekStart:scripts_vars.startweekday,
		format: 'H:i',
        datepicker: false
	});
	
	// add class to last tab
	jQuery('.dc-tabscontent').children('.tab-pane').children('.dc-tabsinfo:last').addClass('dc-tabsinfo-mt');
	jQuery(document).on('click', '.dc-share-link', function (e) {
		jQuery(this).closest('div').next().find('.dc-share-articals').css('display','block');
	});
    
    
	//Payout settings
    jQuery(document).on('click', '.dc-payrols-settings', function (e) {
        e.preventDefault();
        var _this 		= jQuery(this);
		var _id 		= _this.data('id');
		jQuery('body').append(loader_html);

		var _serialized   	= jQuery('.dc-payout-settings').serialize();
		var dataString 	  	= 'security='+scripts_vars.ajax_nonce+'&'+_serialized+'&action=doccure_payrols_settings';
		
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: dataString,
			dataType: "json",
			success: function (response) {
				jQuery('body').find('.dc-preloader-section').remove();
				if (response.type === 'success') {
					jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000}); 
					window.location = response.url;
				} else {
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
				}
			}
		});
	});
    
    //Payout settings
    jQuery(document).on('click', '.dc-send_message-btn', function () {
      
        var _this 		= jQuery(this);
		var _id 		= _this.data('id');
		
        var _msg        = jQuery('#dc-booking-msg').val();
        if( _msg === null || _msg == '' ){
            jQuery.sticky(scripts_vars.empty_message, {classList: 'important', speed: 200, autoclose: 5000});
            return false;
        }
		
		var dataString 	  	= 'security='+scripts_vars.ajax_nonce+'&msg='+_msg+'&id='+_id+'&action=doccure_send_message';
		jQuery('body').append(loader_html);
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: dataString,
			dataType: "json",
			success: function (response) {
				jQuery('body').find('.dc-preloader-section').remove();
				if (response.type === 'success') {
					jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000}); 
					window.location = response.url;
				} else {
					jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
				}
			}
		});
    });
    
	//Responsive table
	jQuery('.dc-tablecategories').basictable({
		breakpoint: 767,
	});
	 
	//init tipso
	function tipso_init(){
		if(jQuery('.dc-tipso').length > 0){
			jQuery('.dc-tipso').tipso({
				tooltipHover	  : true,
				useTitle		  : false,
				background        : scripts_vars.tip_content_bg,
				titleBackground   : scripts_vars.tip_title_bg,
				color             : scripts_vars.tip_content_color,
				titleColor        : scripts_vars.tip_title_color,
			});
		}
	}
	
	//Service details
	jQuery(document).on('click', '#dc-booking-service', function (event) {
        'use strict';
        event.preventDefault();
        var _this 	= jQuery(this);        
        var _id     = parseInt(_this.data('id'));
		var _type    = _this.data('type');
        jQuery('body').append(loader_html);
		jQuery.ajax({
				type: "POST",
				url: scripts_vars.ajaxurl,
				data: {
					action	: 'doccure_get_booking_byID',
					id		: _id,
					dashboard	: 'yes',
					type		: _type,
					security	: scripts_vars.ajax_nonce
				},
				dataType: "json",
				success: function (response) {
					jQuery('body').find('.dc-preloader-section').remove();
					if (response.type === 'success') {
						jQuery('#dc-booking_service_details').html(response.booking_data);
                        jQuery('#dc-booking_service_details').show();
                        window.scrollTo({ top: 0, behavior: 'smooth' });

						tipso_init();
					} else {
						//jQuery('#dc-booking_service_details').html('');
						jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
					}	
				}
			});
    });
	
	//Change service status
	jQuery(document).on('click', '.dc-update-status', function (event) {
        'use strict';
        event.preventDefault();
        var _this 	= jQuery(this);        
        var _id     = parseInt(_this.data('id'));
		var _status = _this.data('status');
		jQuery.confirm({
            'title': scripts_vars.update_booking,
            'message': scripts_vars.update_booking_status_message,
            'buttons': {
                'Yes': {
                    'class': 'blue',
                    'action': function () {
                        jQuery('body').append(loader_html);

                        jQuery.ajax({
							type: "POST",
							url: scripts_vars.ajaxurl,
							data: {
								action	: 'doccure_update_booking_status',
								id		: _id,
								status	: _status,
								security	: scripts_vars.ajax_nonce
							},
							dataType: "json",
							success: function (response) {
								jQuery('body').find('.dc-preloader-section').remove();
								if (response.type === 'success') {
									window.location.reload();
								} else {
									jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
								}
							}
						});
                    }
                },
                'No': {
                    'class': 'gray',
                    'action': function () {
                        return false;
                    }	
                }
            }
        });
		
    });
	
	//Delete slots
	jQuery(document).on('click', '.dc-spaces i, .dc-dayscontent li a i',function(){
        var _this    			= jQuery(this);         
        var _id      			= parseInt(_this.data('id'));
		var _day      			= _this.data('day');
		var _key      			= _this.data('key');
        jQuery.confirm({
            'title': scripts_vars.slots_remove,
            'message': scripts_vars.slots_remove_message,
            'buttons': {
                'Yes': {
                    'class': 'blue',
                    'action': function () {
                        jQuery('body').append(loader_html);

                        jQuery.ajax({
                            type: "POST",
                            url: scripts_vars.ajaxurl,
                            data: {
								action	: 'doccure_remove_slot',
								id		: _id,
								day		: _day,
								key		: _key,
								security	: scripts_vars.ajax_nonce
							},
                            dataType: "json",
                            success: function (response) {
								jQuery('body').find('.dc-preloader-section').remove();
                                if (response.type === 'success') {
                                    jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000 });
                                    jQuery(_this).closest('.dc-spaces-wrap li,.dc-dayscontent li').remove();
                                } else {
                                    jQuery.sticky(response.message, {classList: 'important',position:'center-center', speed: 200, autoclose: 5000});
                                }
                            }
                        });
                    }
                },
                'No': {
                    'class': 'gray',
                    'action': function () {
                        return false;
                    }	
                }
            }
        });
	});
	
	//Remove location
	jQuery('.dc-remove-location').on('click', function(){
        var _this    			= jQuery(this);         
        var _id      			= parseInt(_this.data('id'));
        jQuery.confirm({
            'title': scripts_vars.location_remove,
            'message': scripts_vars.location_remove_message,
            'buttons': {
                'Yes': {
                    'class': 'blue',
                    'action': function () {
                        jQuery('body').append(loader_html);

                        jQuery.ajax({
                            type: "POST",
                            url: scripts_vars.ajaxurl,
                            data: {
								action	: 'doccure_remove_location',
								id		: _id,
								security	: scripts_vars.ajax_nonce
							},
                            dataType: "json",
                            success: function (response) {
								jQuery('body').find('.dc-preloader-section').remove();
                                if (response.type === 'success') {
                                    jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000 });
                                    window.location.replace(response.url);
                                } else {
                                    jQuery.sticky(response.message, {classList: 'important',speed: 200, autoclose: 5000});
                                }
                            }
                        });
                    }
                },
                'No': {
                    'class': 'gray',
                    'action': function () {
                        return false;
                    }	
                }
            }
        });
	});
	
	//change post status
	jQuery('.dc-chage-status').on('click', function(){
        var _this    			= jQuery(this);         
        var _id      			= parseInt(_this.data('id'));
		var _status      		= _this.data('status');
        jQuery.confirm({
            'title': scripts_vars.change_status,
            'message': scripts_vars.change_status_message,
            'buttons': {
                'Yes': {
                    'class': 'blue',
                    'action': function () {
                        jQuery('body').append(loader_html);

                        jQuery.ajax({
                            type: "POST",
                            url: scripts_vars.ajaxurl,
                            data: {
								action	: 'doccure_change_post_status',
								id		: _id,
								status	: _status,
								security	: scripts_vars.ajax_nonce
							},
                            dataType: "json",
                            success: function (response) {
								jQuery('body').find('.dc-preloader-section').remove();
                                if (response.type === 'success') {
                                    jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000 });
                                    window.location.reload();
                                } else {
                                    jQuery.sticky(response.message, {classList: 'important',speed: 200, autoclose: 5000});
                                }
                            }
                        });
                    }
                },
                'No': {
                    'class': 'gray',
                    'action': function () {
                        return false;
                    }	
                }
            }
        });
	});
    
    //Update Appointment settings for single location
    jQuery(document).on('click', '.dc-update-ap-location', function (e) {
        e.preventDefault();        
        var _this    	= jQuery(this);     
		var _id   		= parseInt(_this.data('id'));
		
        jQuery('body').append(loader_html);
        var _serialized   = jQuery('.dc-update-providingservices').serialize();
        var dataString 	  = 'security='+scripts_vars.ajax_nonce+'&post_id=' + _id +'&'+ _serialized+'&action=doccure_update_ap_location';   
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: dataString,
            dataType: "json",
            success: function (response) {
                jQuery('body').find('.dc-preloader-section').remove();
                if (response.type === 'success') {
                    jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000});
					//window.location.reload();
                } else {
                    jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
                }
            }
        });
    });

	//Update Appointment services
    jQuery(document).on('click', '.dc-update-ap-services', function (e) {
        e.preventDefault();        
        var _this    	= jQuery(this);     
		var _id   		= parseInt(_this.data('id'));
		
        jQuery('body').append(loader_html);
        var _serialized   = jQuery('.dc-update-providingservices').serialize();
        var dataString 	  = 'security='+scripts_vars.ajax_nonce+'&post_id=' + _id +'&'+ _serialized+'&action=doccure_update_ap_services';   
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: dataString,
            dataType: "json",
            success: function (response) {
                jQuery('body').find('.dc-preloader-section').remove();
                if (response.type === 'success') {
                    jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000});
					//window.location.reload();
                } else {
                    jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
                }
            }
        });
    });
	
	//Remove appointment 
	jQuery('.dc-remove-appointment-all').on('click', function(){
		 
        var _this    			= jQuery(this);  
        var _id      			= parseInt(_this.data('id'));
		var _day      			= _this.data('day');
		var _spaces_size		= jQuery('.dc-spaces-ul-'+_day+' li').length;
		if( _spaces_size > 0 ) {
			jQuery.confirm({
				'title': scripts_vars.slots_remove,
				'message': scripts_vars.slots_remove_message,
				'buttons': {
					'Yes': {
						'class': 'blue',
						'action': function () {
							jQuery('body').append(loader_html);

							jQuery.ajax({
								type: "POST",
								url: scripts_vars.ajaxurl,
								data: {
									action	: 'doccure_remove_allslots',
									id		: _id,
									day		: _day,
									security	: scripts_vars.ajax_nonce
								},
								dataType: "json",
								success: function (response) {
									jQuery('body').find('.dc-preloader-section').remove();
									if (response.type === 'success') {
										jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000 });
										jQuery(_this).parents().next('.dc-spaces-holder').children('ul').empty();
									} else {
										jQuery.sticky(response.message, {classList: 'important',position:'center-center', speed: 200, autoclose: 5000});
									}
								}
							});
						}
					},
					'No': {
						'class': 'gray',
						'action': function () {
							return false;
						}	
					}
				}
			});
		} else {
			jQuery.sticky(scripts_vars.empty_spaces_message, {classList: 'important', speed: 200, autoclose: 5000});
		}
	});
	
	//Disbale/Enable Timings
    jQuery(document).on('change', 'select[name=start_time]', function (e) {

        var endTimeSelect = jQuery(this).parents('.dc-formtheme').find('select[name=end_time]');
        var startTimeVal = jQuery(this).val();
        endTimeSelect.find('option').removeAttr('disabled');
        endTimeSelect.find('option').each(function () {
            var current = jQuery(this).val();
            if (current <= startTimeVal) {
                jQuery(this).attr('disabled', true);
            }
        });
		
    });
	
	// start Membership section
	jQuery(document).on('click', '.dc-addinfo', function() {
		var _this = jQuery(this);
		_this.addClass('dc-update-info');
		_this.parents('li').find('.dc-deleteinfo').addClass('dc-delete-info');
	    _this.parents('li').addClass('dc-skillsaddinfo');
	});
	
	jQuery(document).on('click', '.dc-deleteinfo', function() {
		var _this 		= jQuery(this);
		var check_class	= _this.hasClass('dc-deleteinfo dc-delete-info')
		if(check_class == false) {
			_this.parents('li').remove();
		}
		
	});
	
	jQuery(document).on('click', '.dc-delete-info', function() {
		var _this = jQuery(this);
		var _val = _this.parents('li').find('.skill-dynamic-field input').val();
		_this.parents('li').find('.skill-dynamic-html .skill-val').html(_val);
		_this.parents('li').removeClass('dc-skillsaddinfo');
		_this.parents('li').find('.dc-update-info').removeClass('dc-update-info');
		_this.removeClass('dc-delete-info');
	});
	
	jQuery(document).on('click', '.dc-update-info', function() {
		var _this = jQuery(this);
		var _val = _this.parents('li').find('.skill-dynamic-field input').val();
		_this.parents('li').find('.skill-dynamic-html .skill-val').html(_val);
		_this.parents('li').removeClass('dc-skillsaddinfo');
		_this.removeClass('dc-update-info');
		_this.parents('li').find('.dc-delete-info').removeClass('dc-delete-info');
		
	});
	
	jQuery(document).on('click', '.dc-payout-settings input[type="radio"]', function (e) {
        //e.preventDefault();
        var _this 		= jQuery(this);
		_this.parents('.dc-payout-settings').find('.fields-wrapper').hide();
        _this.parents('.dc-checkboxholder').next('.fields-wrapper').show();
        
	});
	
	// Auto complete hospitals
	jQuery( '.suggestquestion' ).on('input', function(){	
		var _this = jQuery(this);	
		var dataString = _this.val();	
		if( dataString == '' ){
			return false;
		}

		var ajaxurl = scripts_vars.ajaxurl;		
		var sp_action = 'doccure_get_hospitals';
		jQuery('#search_hospitals').parents('.dc-inputwithicon').find('.sp-spin').remove();
		jQuery('#search_hospitals').parents('.dc-inputwithicon').append(scripts_vars.spinner);
		jQuery('input[name=search_string]').autocomplete({
			
			source: function(req, response){
				jQuery('#search_hospitals').parents('.dc-inputwithicon').find('.sp-spin').remove();
				jQuery.getJSON(ajaxurl+'?callback=?&action='+sp_action, req, response);			            	            		         
				jQuery('.ui-autocomplete.ui-front').css('display', 'block');   
				jQuery('.dc-inputwithicon .fa-check').css('display','none');
			},
			select: function(event, ui) {		   
				jQuery('#search_hospitals').parents('.dc-inputwithicon').find('.sp-spin').remove();
				jQuery('#hospitals_team_id').val(ui.item.id);
				jQuery('.dc-inputwithicon .fa-check').css('display','block');
				
			},		        
			response: function(event, ui) {
				jQuery('#search_hospitals').parents('.dc-inputwithicon').find('.sp-spin').remove();
				if (ui.content.length === 0) {
					jQuery('.dc-inputwithicon .fa-check').css('display','none');
				} 
			},		        
			minLength: 0,
		});	

		//Set matched string to bold 	
		jQuery.ui.autocomplete.prototype._renderItem = function (ul, item) {	   	
			var t = String(item.value).replace(
					new RegExp(this.term, 'gi'),
					'<b>$&</b>');
			return jQuery('<li></li>')
				.data('item.autocomplete', item)
				.append('' + t + '')
				.appendTo(ul);
		}
	});
	
	//@renew package
    jQuery(document).on('click', '.renew-package', function (e) {
        e.preventDefault();
        var _this = jQuery(this);
        var _id = _this.data('key');
        var dataString = 'security='+scripts_vars.ajax_nonce+'&id=' + _id + '&action=doccure_update_cart';

        jQuery.confirm({
            'title': scripts_vars.order,
            'message': scripts_vars.order_message,
            'buttons': {
                'Yes': {
                    'class': 'blue',
                    'action': function () {
                        jQuery('body').append(loader_html);

                        jQuery.ajax({
                            type: "POST",
                            url: scripts_vars.ajaxurl,
                            data: dataString,
                            dataType: "json",
                            success: function (response) {
								jQuery('body').find('.dc-preloader-section').remove();
                                if (response.type === 'success') {
                                    jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000 });
                                    window.location.replace(response.checkout_url);
                                } else {
                                    jQuery.sticky(response.message, {classList: 'important',position:'center-center', speed: 200, autoclose: 5000});
                                }
                            }
                        });
                    }
                },
                'No': {
                    'class': 'gray',
                    'action': function () {
                        return false;
                    }	
                }
            }
        });
    });
	//Add phone numbers
	jQuery(document).on('click', '.dc-add_phone_number', function() {
		var _this 		= jQuery(this);
		var _input_val	= jQuery('#input_phone_numbers').val();
		
		if( _input_val != ''){
			var load_repeater = wp.template('load-phone_numbers'); 
			var counter = Math.floor((Math.random() * 999999) + 999);
			var data = {id: counter, name: _input_val};        
			load_repeater = load_repeater(data);             
			jQuery('.dc-phone_numbers').append(load_repeater);
			jQuery('#input_phone_numbers').val('');
		}
    });

	//Add membership
	jQuery(document).on('click', '.dc-add_membership', function() {
		var _this 		= jQuery(this);
		var _input_val	= jQuery('#input_membership').val();
		
		var membershipcount 	= jQuery( ".dc-memberships li" ).size();
		var _package_member		= scripts_vars.dc_memberships;
		var user_type			= scripts_vars.user_type;
		
		if( membershipcount>= _package_member && listing_type == 'paid' && user_type === 'doctors'){
			jQuery.sticky(package_update, {classList: 'important',position:'top-right', speed: 200, autoclose: 5000});
                return false;
		}
		
		if( _input_val != ''){
			var load_repeater = wp.template('load-memberships'); 
			var counter = Math.floor((Math.random() * 999999) + 999);
			var data = {id: counter, name: _input_val};        
			load_repeater = load_repeater(data);             
			jQuery('.dc-memberships').append(load_repeater);
			jQuery('#input_membership').val('');
		}
    });
	
    jQuery( "#dc-booking-email" ).keyup(function() {
        var _this 		    = jQuery(this);
        var email_address   = _this.val();
        var _data           = 'security='+scripts_vars.ajax_nonce+'&email='+email_address;
        if(doccure_validate_email(email_address)){
            jQuery('body').append(loader_html);
            jQuery.ajax({
                type: 'POST',
                url:  scripts_vars.ajaxurl,
                data: _data + '&action=doccure_user_by_email',
                dataType: "json",
                success: function (response) {
                    jQuery('body').find('.dc-preloader-section').remove();
                    if (response.type === 'success') {			
                        if(response.success_type === 'other'){
                            jQuery('input[name=create_user]').attr("disabled", false);
                            jQuery('input[name=user_id]').val("");
                            jQuery('input[name=first_name]').val("");
                            jQuery('input[name=last_name]').val("");
                            jQuery('input[name=phone]').val("");
                            _this.val("");
                            jQuery('.dc-creat-user').css('display','block');
                            jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
                        } else if(response.success_type === 'registered'){
                            jQuery('input[name=first_name]').val(response.first_name);
                            jQuery('input[name=last_name]').val(response.last_name);
                            jQuery('input[name=phone]').val(response.mobile_number);
                            jQuery('input[name=user_id]').val(response.user_id);
                            jQuery('.dc-creat-user').css('display','none');
                            jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000});
                        } else {
                            jQuery('input[name=create_user]').attr("disabled", false);
                            jQuery('input[name=user_id]').val("");
                            jQuery('input[name=first_name]').val("");
                            jQuery('input[name=last_name]').val("");
							jQuery('input[name=phone]').val("");
                            jQuery('.dc-creat-user').css('display','block');
                        }
                        
                    } else { 
                        jQuery('input[name=create_user]').attr("disabled", false);
                        jQuery('input[name=user_id]').val("");
                        jQuery('input[name=first_name]').val("");
                        jQuery('input[name=last_name]').val("");
                        jQuery('.dc-creat-user').css('display','block');
                        jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});  
                    }
                }
            });
        }
    });
    // invitation model to users
    jQuery(document).on('click', '.dc-add-booking-reschedule', function() {
        var _this 	= jQuery(this);
        var _access     = _this.data('access');
        var _postid     = parseInt(_this.data('postid'));
         // Set the _postid inside the div before showing the modal
         jQuery('#appointment input[name="order_post_id"]').val(_postid);
          var _val = jQuery(".dc-booking-hospitals").val();
          doccure_select_loaction(_val);
         jQuery('#appointment').modal('show');
    });


    jQuery(document).on('click', '.dc-add-booking-reschedule-patient', function() {
        var _this 	= jQuery(this);
        var _access     = _this.data('access');
        var _postid     = parseInt(_this.data('postid'));
        var _hosid     = parseInt(_this.data('hosid'));
         // Set the _postid inside the div before showing the modal
         jQuery('#appointment input[name="order_post_id"]').val(_postid);
         jQuery('#appointment input[name="booking_hospitals"]').val(_hosid);
           doccure_select_loaction(_hosid);
         jQuery('#appointment').modal('show');
    });
    

    jQuery(document).on('click', '.dc-add-booking', function() {
        var _this 	= jQuery(this);
        var _access     = _this.data('access');
       
        if( _access != '' && _access ===1 ){
            var _val = jQuery(".dc-booking-hospitals option:first").val();
            if(typeof _val === 'undefined'){

            } else {
                doccure_select_loaction(_val);
            }
        }
        jQuery('#appointment').modal('show');
    });


    // invitation model to users
    jQuery(document).on('click', '.dc-invitation-users', function() {
        jQuery('#dc-invitationmodel').modal('show');
    });
    // invitation form submit
    jQuery(document).on('click', '.dc-invitation-btn', function() {
        var _this 		= jQuery(this);
        var _data        = 'security='+scripts_vars.ajax_nonce+'&'+jQuery('.dc-invitation-form').serialize();
        jQuery('body').append(loader_html);
        jQuery.ajax({
            type: 'POST',
            url:  scripts_vars.ajaxurl,
            data: _data + '&action=doccure_users_invitations',
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
    
    //Add membership
	jQuery(document).on('click', '.dc-add_booking_contact', function() {
		var _this 		= jQuery(this);
		var _input_val	= jQuery('#input_booking_contact').val();
		if( _input_val != ''){
			var load_repeater = wp.template('load-booking_contact'); 
			var counter = Math.floor((Math.random() * 999999) + 999);
			var data = {id: counter, name: _input_val};        
			load_repeater = load_repeater(data);             
			jQuery('.dc-booking_contacts').append(load_repeater);
			jQuery('#input_booking_contact').val('');
		}
	});
	
	//Spaces display
	jQuery(document).on('click', '.dc-spaces', function (e) {
		var select_value = jQuery(this).val();
		if( select_value === 'others') {
			jQuery('.dc-others').css('display','block');
		} else {
			jQuery('.dc-others').css('display','none');
		}
	});
	
	// Add appointment template
	jQuery(document).on('click', '.dc-add-appointment', function() {
		var _this    			= jQuery(this);         
		var _day      			= _this.data('day');
		var load_repeater 		= wp.template('load-appointment'); 
		var counter 			= Math.floor((Math.random() * 999999) + 999);;
		var data 				= {day: _day};        
		load_repeater 			= load_repeater(data);
		jQuery('.dc-'+_day).css('display','block');
		jQuery('.dc-'+_day).html(load_repeater);
		jQuery(".custom_spaces").numeric({ decimal : false,  negative : false });
	});
	
	// Add education template
	jQuery(document).on('click', '.dc-add_education', function() {
		var load_repeater 		= wp.template('load-education'); 
		var counter 			= Math.floor((Math.random() * 999999) + 999);;
		var data 				= {counter: counter};        
		load_repeater 			= load_repeater(data);
		jQuery('.dc-educations').append(load_repeater);
		init_datepicker('dc-date-pick');
	});
	
	// Add experience template
	jQuery(document).on('click', '.dc-add_experience', function() {
		var load_repeater 		= wp.template('load-experience'); 
		var counter 			= Math.floor((Math.random() * 999999) + 999);;
		var data 				= {counter: counter};        
		load_repeater 			= load_repeater(data);
		jQuery('.dc-experiences').append(load_repeater);
		init_datepicker('dc-date-pick');
	});
	
	// Add award template
	jQuery(document).on('click', '.dc-add_award', function() {
		var awardshipcount 	= jQuery( ".dc-award li" ).size();
		var _package_member	= scripts_vars.dc_awards;
		if( awardshipcount >= _package_member && listing_type == 'paid' ){
			jQuery.sticky(package_update, {classList: 'important',position:'top-right', speed: 200, autoclose: 5000});
                return false;
		}
		
		var load_repeater 		= wp.template('load-award'); 
		var counter 			= Math.floor((Math.random() * 999999) + 999);;
		var data 				= {counter: counter};        
		load_repeater 			= load_repeater(data);
		jQuery('.dc-award').append(load_repeater);
		init_datepicker_year('dc-year-pick');
	});
	
	///Add Services
	jQuery(document).on('click','.dc-add_service', function() {
		var load_repeater = wp.template('load-specialities'); 
		var counter = Math.floor((Math.random() * 999999) + 999);;
		var data = {counter: counter};        
		load_repeater = load_repeater(data);             
		jQuery('.dc-specilities-items').append(load_repeater);
	});
	
	//Specialities and Services Repeater
	jQuery(document).on('click','.add-repeater-services', function() {
		var _this		= jQuery(this);
		var _id			= _this.data('id');
		var _current	= _this.parents('.specialities_parents').find('.item-specialities-dp option:selected').val();
		
		if(_current !== null && _current !== '' && _current !== '0') {
			var load_repeater = wp.template('load-services'); 
			var counter = Math.floor((Math.random() * 999999) + 999);

			if (DT_Editor.elements[_current]) {
				var _options = DT_Editor.elements[_current];
			} else {
				var _options = [];
			}
			
			var data = {counter: counter,id:_id,options:_options};        
			load_repeater = load_repeater(data);             
			_this.parents('.services-wrap').append(load_repeater);
		} else{
			 jQuery.sticky(scripts_vars.speciality_required, {classList: 'important', speed: 200, autoclose: 5000});
		}		
	});
	
	//speciality
	jQuery(document).on('change','.item-specialities-dp',function () {
        var _this 	= jQuery(this);
		var _sp_id 	= this.value;
		
        if( DT_Editor.elements[_sp_id] ) {
			var _options = DT_Editor.elements[_sp_id];
		} else {
			var _options = [];
		}
		
		var load_repeater = wp.template('load-services-options'); 
		var data = {options: _options};        
		load_repeater = load_repeater(data);
		var _fields	= _this.parents('.specialities_parents').find('.services-item .sp_services').empty().append(load_repeater);
		
	});
	
	//Update services
    jQuery(document).on('click', '.dc-update-services', function (e) {
        e.preventDefault();        
        var _this    = jQuery(this);                    
        jQuery('body').append(loader_html);
        var _serialized   = jQuery('.dc-user-profile-specialities').serialize();
        var dataString 	  = 'security='+scripts_vars.ajax_nonce+'&'+_serialized+'&action=doccure_update_specialities';   
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: dataString,
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
	
	// Remove multiple saved items
	jQuery(document).on('click', '.dc-clickremoveall', function (e) {
        e.preventDefault();        
        var _this   = jQuery(this);                    
        var _id   	= _this.data('post-id');
		var _type  	= _this.data('itme-type');
		jQuery.confirm({
			'title': scripts_vars.remove_itme,
			'message': scripts_vars.remove_itme_message,
			'buttons': {
				'Yes': {
					'class': 'blue',
					'action': function () {
						jQuery('body').append(loader_html);
						jQuery.ajax({
							type: "POST",
							url: scripts_vars.ajaxurl,
							data: {
									action		: 'doccure_remove_save_multipuleitems',
									post_id		: _id,
									item_type	: _type,
									security	: scripts_vars.ajax_nonce
								},
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
					}
				},
				'No': {
					'class': 'gray',
					'action': function () {
						return false;
					}   // Nothing to do in this case. You can as well omit the action property.
				}
			}
		});
        
    });
	
	// Remove single saved
	jQuery(document).on('click', '.dc-removesingle_saved', function (e) {
        e.preventDefault();        
        var _this   	= jQuery(this);                    
        var _id   		= _this.data('id');
		var _type  		= _this.data('itme-type');
		var _item_id	= _this.data('item-id');
		jQuery.confirm({
			'title': scripts_vars.remove_itme,
			'message': scripts_vars.remove_itme_message,
			'buttons': {
				'Yes': {
					'class': 'blue',
					'action': function () {
						jQuery('body').append(loader_html);
						jQuery.ajax({
							type: "POST",
							url: scripts_vars.ajaxurl,
							data: {
									action		: 'doccure_remove_save_item',
									post_id		: _id,
									item_type	: _type,
									item_id		: _item_id,
									security	: scripts_vars.ajax_nonce
								},
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
					}
				},
				'No': {
					'class': 'gray',
					'action': function () {
						return false;
					}   // Nothing to do in this case. You can as well omit the action property.
				}
			}
		});
        
    });
	
	//add hospital team
    jQuery(document).on('click', '.dc-add_hospital_team', function (e) {
        e.preventDefault();       
        var _this    = jQuery(this);  
		
        jQuery('body').append(loader_html);
        var _serialized   = jQuery('.dc-hospital-team').serialize();
        var dataString 	  = 'security='+scripts_vars.ajax_nonce+'&'+_serialized+'&action=doccure_add_hospital_team';   
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: dataString,
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
	
	//add appointment team
    jQuery(document).on('click', '.dc-update-appointment', function (e) {
        e.preventDefault();       
        var _this    = jQuery(this);  
		var _id   	= _this.data('id');
		var _day   	= _this.data('day');
		
        jQuery('body').append(loader_html);
        var _serialized   = _this.parents('.dc-form-appointment').serialize();
        var dataString 	  = 'security='+scripts_vars.ajax_nonce+'&post_id=' + _id + '&week_day=' + _day +'&'+ _serialized+'&action=doccure_update_appointment';   
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: dataString,
            dataType: "json",
            success: function (response) {
                jQuery('body').find('.dc-preloader-section').remove();
                if (response.type === 'success') {
                    jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000});
					e.preventDefault();
					jQuery('.dc-spaces-ul-'+_day).html(response.slots);
                } else {
                    jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
                }
            }
        });
    });
	
	//add article
    jQuery(document).on('click', '.dc-add-post', function (e) {
        e.preventDefault();       
        var _this    = jQuery(this);  
		
		if( typeof tinyMCE === 'object' ) {
		  tinyMCE.triggerSave();
		}
		
        jQuery('body').append(loader_html);
        var _serialized   = jQuery('.dc-post-artical').serialize();
        var dataString 	  = 'security='+scripts_vars.ajax_nonce+'&'+_serialized+'&action=doccure_add_article';   
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: dataString,
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
	
	//Delete articles
	jQuery(document).on('click', '.dc-article-delete', function (e) {
        e.preventDefault();        
        var _this   = jQuery(this);                    
        var _id   	= _this.data('id');
		jQuery.confirm({
			'title': scripts_vars.delete_article,
			'message': scripts_vars.delete_article_message,
			'buttons': {
				'Yes': {
					'class': 'blue',
					'action': function () {
						jQuery('body').append(loader_html);
						jQuery.ajax({
							type: "POST",
							url: scripts_vars.ajaxurl,
							data: {
									action	: 'doccure_remove_article',
									id		: _id,
									security	: scripts_vars.ajax_nonce
								},
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
					}
				},
				'No': {
					'class': 'gray',
					'action': function () {
						return false;
					}   // Nothing to do in this case. You can as well omit the action property.
				}
			}
		});
        
    });
	
	//Update account settings
    jQuery(document).on('click', '.dc-update-account', function (e) {
        e.preventDefault();        
        var _this    = jQuery(this);                    
        jQuery('body').append(loader_html);
        var _serialized   = jQuery('.dc-user-account').serialize();
        var dataString 	  = 'security='+scripts_vars.ajax_nonce+'&'+_serialized+'&action=doccure_update_account_settings';   
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: dataString,
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
	
	//update password
    jQuery(document).on('click', '.dc-reset-password', function (e) {
        e.preventDefault();        
        var _this    = jQuery(this);                    
        var _serialized   = jQuery('.dc-user-account').serialize();
        var dataString 	  = 'security='+scripts_vars.ajax_nonce+'&'+_serialized+'&action=doccure_change_user_password';
		jQuery('body').append(loader_html);
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: dataString,
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
	
	//update email
    jQuery(document).on('click', '.dc-change-email', function (e) {
        e.preventDefault();        
        var _this    = jQuery(this);                    
        var _serialized   = jQuery('.dc-user-account').serialize();
        var dataString 	  = 'security='+scripts_vars.ajax_nonce+'&'+_serialized+'&action=doccure_change_user_email';
		jQuery('body').append(loader_html);
		jQuery.ajax({
			type: "POST",
			url: scripts_vars.ajaxurl,
			data: dataString,
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
	
	//delete profile
    jQuery(document).on('click', '.dc-delete-user-account', function (e) {
        e.preventDefault();        
        var _this    = jQuery(this);                    
        var _serialized   = jQuery('.dc-user-account').serialize();
        var dataString 	  = 'security='+scripts_vars.ajax_nonce+'&'+_serialized+'&action=doccure_delete_account';
		jQuery.confirm({
			'title': scripts_vars.delete_account,
			'message': scripts_vars.delete_account_message,
			'buttons': {
				'Yes': {
					'class': 'blue',
					'action': function () {
						jQuery('body').append(loader_html);
						jQuery.ajax({
							type: "POST",
							url: scripts_vars.ajaxurl,
							data: dataString,
							dataType: "json",
							success: function (response) {
								jQuery('body').find('.dc-preloader-section').remove();
								if (response.type === 'success') {
									jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000}); 
									window.location = response.redirect;
								} else {
									jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
								}
							}
						});
					}
				},
				'No': {
					'class': 'gray',
					'action': function () {
						return false;
					}   // Nothing to do in this case. You can as well omit the action property.
				}
			}
		});
        
    });
	
	 //Update Doctors Profile
    jQuery(document).on('click', '.dc-update-profile-doctors', function (e) {
        e.preventDefault();        
        var _this    = jQuery(this);                    
        jQuery('body').append(loader_html);
        var _serialized   = jQuery('.dc-user-profile').serialize();
        var dataString 	  = 'security='+scripts_vars.ajax_nonce+'&'+_serialized+'&action=doccure_update_doctor_profile';  
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: dataString,
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

     //Update patient Profile
     jQuery(document).on('click', '.dc-update-profile-patient', function (e) {
        e.preventDefault();        
        var _this    = jQuery(this);                    
        jQuery('body').append(loader_html);
        var _serialized   = jQuery('.dc-user-profile').serialize();
        var dataString 	  = 'security='+scripts_vars.ajax_nonce+'&'+_serialized+'&action=doccure_update_patient_profile';  
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: dataString,
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

     //Update Doctors Profile
     jQuery(document).on('click', '.dc-update-profile-location', function (e) {
        e.preventDefault();        
        var _this    = jQuery(this);                    
        jQuery('body').append(loader_html);
        var _serialized   = jQuery('.dc-user-profile').serialize();
        var dataString 	  = 'security='+scripts_vars.ajax_nonce+'&'+_serialized+'&action=doccure_update_doctor_profile_location';   
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: dataString,
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

     //Update Doctors Profile
     jQuery(document).on('click', '.dc-update-bookings-doctors', function (e) {
        e.preventDefault();        
        var _this    = jQuery(this);                    
        jQuery('body').append(loader_html);
        var _serialized   = jQuery('.dc-user-profile').serialize();
        var dataString 	  = 'security='+scripts_vars.ajax_nonce+'&'+_serialized+'&action=doccure_update_doctor_booking_options';   
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: dataString,
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
	
	//Update Doctors Profile education
    jQuery(document).on('click', '.dc-update-doctors-education', function (e) {
        e.preventDefault();        
        var _this    = jQuery(this);                    
        jQuery('body').append(loader_html);
        var _serialized   = jQuery('.dc-user-profile-educations').serialize();
        var dataString 	  = 'security='+scripts_vars.ajax_nonce+'&'+_serialized+'&action=doccure_update_doctor_education';   
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: dataString,
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
	
	//Update Doctors Profile education
    jQuery(document).on('click', '.dc-update-doctors-awards', function (e) {
        e.preventDefault();        
        var _this    = jQuery(this);                    
        jQuery('body').append(loader_html);
        var _serialized   = jQuery('.dc-user-profile-awards').serialize();
        var dataString 	  = 'security='+scripts_vars.ajax_nonce+'&'+_serialized+'&action=doccure_update_doctor_award';   
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: dataString,
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
	
	//Update Doctors Profile registrations
    jQuery(document).on('click', '.dc-update-doctors-registrations', function (e) {
        e.preventDefault();        
        var _this    = jQuery(this);                    
        jQuery('body').append(loader_html);
        var _serialized   = jQuery('.dc-user-profile-registrations').serialize();
        var dataString 	  = 'security='+scripts_vars.ajax_nonce+'&'+_serialized+'&action=doccure_update_doctor_registrations';   
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: dataString,
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
	
	//Update Doctors Profile gallery
    jQuery(document).on('click', '.dc-update-gallery', function (e) {
        e.preventDefault();        
        var _this    = jQuery(this);                    
        jQuery('body').append(loader_html);
        var _serialized   = jQuery('.dc-user-profile-gallery').serialize();
        var dataString 	  = 'security='+scripts_vars.ajax_nonce+'&'+_serialized+'&action=doccure_update_gallery';   
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: dataString,
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
	
	//Update Doctors social profiles
    jQuery(document).on('click', '.dc-update-social-link', function (e) {
        e.preventDefault();        
        var _this    = jQuery(this);                    
        jQuery('body').append(loader_html);
        var _serialized   = jQuery('.dc-social-profile').serialize();
        var dataString 	  = 'security='+scripts_vars.ajax_nonce+'&'+_serialized+'&action=doccure_social_profiles';   
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: dataString,
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
	
	//Update hospitals Profile
    jQuery(document).on('click', '.dc-update-profile-hospitals', function (e) {
        e.preventDefault();        
        var _this    = jQuery(this);                    
        jQuery('body').append(loader_html);
        var _serialized   = jQuery('.dc-user-profile').serialize();
        var dataString 	  = 'security='+scripts_vars.ajax_nonce+'&'+_serialized+'&action=doccure_update_hospitals_profile';   
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: dataString,
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
	
	jQuery(document).on('click', '.dc-closediv', function(){
        var _this = jQuery(this);
        _this.parents('li').remove();
    });
	
	jQuery(document).on('click', '.remove-repeater', function(){
        var _this = jQuery(this);
        _this.parents('.repeater-wrap-inner').remove();
    });
	
	jQuery(document).on('click', '.dc-remove-image', function(){
        var _this = jQuery(this);
        _this.parents('ul').remove();
	});
	jQuery(document).on('click', '.dc-remove-gallery-image', function(){
        var _this = jQuery(this);
        _this.parents('li').remove();
    });
	
	jQuery(document).on('click', '.dc-remove-attachment', function(){
        var _this = jQuery(this);
		jQuery('.registration-option').css('display','block');
        _this.parents('ul').remove();
    });

    // Add js for prescription
    //Add vital signs
	jQuery(document).on('click', '.dc-add-vitals', function() {
        var vital_signs 	= jQuery('#vital_signs').val();
        var vital_signs_val	= jQuery('#dc-vital-signs-val').val();
		if( vital_signs != ''){
			var load_repeater   = wp.template('load-dc-visals'); 
			var data            = { id: vital_signs, value: vital_signs_val };        
			load_repeater       = load_repeater(data);             
            jQuery('.vital-sign-list').append(load_repeater);

            jQuery(".dc-visal-"+vital_signs + ' select option[value="' + vital_signs + '"]').prop('selected', true);
            jQuery(".dc-visal-"+vital_signs +" select").attr('name','vital_signs['+vital_signs+'][name]');
            
            jQuery('#vital_signs').val('');
            jQuery('#dc-vital-signs-val').val('');
		}
    });

    jQuery(document).on('click', '.dc-remove-visual', function() {
        var _this 		= jQuery(this);
        _this.parents('.dc-visal-sign').remove();
    });

    //Add vital igns
	jQuery(document).on('click', '.dc-add-medician', function() {
        var medicine_name 	= jQuery('#medicine_name').val();
        var medicine_types	= jQuery('#medicine_types').val();

        var medicine_duration	= jQuery('#medicine_duration').val();
        var medicine_usage	    = jQuery('#medicine_usage').val();
        var medicine_details	= jQuery('#medicine_details').val();

		if( medicine_name != ''){
            var load_repeater   = wp.template('load-dc-medician'); 
            var counter         = Math.floor((Math.random() * 999999) + 999);

			var data            = { id: counter, name: medicine_name, detail:medicine_details };        
			load_repeater       = load_repeater(data);             
            jQuery('#dc-medican-html').append(load_repeater);
            jQuery('#medicine_types-'+ counter +' option[value="' + medicine_types + '"]').prop('selected', true);
            jQuery('#medicine_duration-'+ counter +' option[value="' + medicine_duration + '"]').prop('selected', true);
            jQuery('#medicine_usage-'+ counter +' option[value="' + medicine_usage + '"]').prop('selected', true);

            jQuery('#medicine_name').val('');
            jQuery('#medicine_types').val('');
            jQuery('#medicine_duration').val('');
            jQuery('#medicine_usage').val('');
            jQuery('#medicine_details').val('');
		}
    });

    jQuery(document).on('click', '.dc-remove-visual', function() {
        var _this 		= jQuery(this);
        _this.parents('.dc-visal-sign').remove();
    });
   	
    // update date
    jQuery(document).on('click', '.dc-update-prescription', function() {
        var _this 		= jQuery(this);
        var _serialized     = jQuery('.dc-prescription-form').serialize();
        var booking_id      = _this.data('booking_id');
        var dataString 	    = 'security='+scripts_vars.ajax_nonce+'&booking_id=' + booking_id +'&'+ _serialized+'&action=doccure_update_prescription'; 
        jQuery('body').append(loader_html);  
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: dataString,
            dataType: "json",
            success: function (response) {
                jQuery('body').find('.dc-preloader-section').remove();
                if (response.type === 'success') {
                    jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000});
					window.location = response.url;
                } else {
                    jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
                }
            }
        });
    });
    
    // end  js for prescription
    
	
	 //init datepicker
    init_datepicker('dc-date-pick');
	init_datepicker_year('dc-year-pick');
	
	/* SEARCH CHOSEN */
	jQuery(".chosen-select").select2({
	  templateResult: loadSelect2Images
	});
	
	// call sidebar 
	//fixedNav();
	
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

//Image uploader
function init_uploader_downloads() {
    var uploadSize     		= scripts_vars.data_size_in_kb;
    //Job add upload attachment
	var JobUploaderArguments = {
		browse_button: 'download-btn', // this can be an id of a DOM element or the DOM element itself
		file_data_name: 'file_name',
		container: 'dc-download-container',
		drop_element: 'download-drag',
		multipart_params: {
			"type": "file_name",
		},
		multi_selection: true,
		url: scripts_vars.ajaxurl + "?action=doccure_temp_file_uploader&security=" + scripts_vars.ajax_nonce,
		filters: {
			mime_types: [
				{title: scripts_vars.download_attachments, extensions: "pdf,doc,docx,xls,xlsx,ppt,pptx,csv,jpg,jpeg,gif,png"}
			],
			max_file_size: uploadSize,
			max_file_count: 1,
			prevent_duplicates: false
		}
	};

	var JobUploader = new plupload.Uploader(JobUploaderArguments);
	JobUploader.init();

	//bind
	JobUploader.bind('FilesAdded', function (up, files) {
		up.refresh();
		JobUploader.start();
	});

	//bind
	JobUploader.bind('UploadProgress', function (up, file) {
		var _html = '<span class="uploadprogressbar" style="width:'+file.percent+'%"></span>';
        jQuery('.dc-formprojectinfo .uploadprogressbar').replaceWith(_html);
	});

	//Error
	JobUploader.bind('Error', function (up, err) {
		jQuery.sticky(err.message, {classList: 'important', speed: 200, autoclose: 5000});
	});

	//display data
	JobUploader.bind('FileUploaded', function (up, file, ajax_response) {
		
		var response = jQuery.parseJSON(ajax_response.response);
		if ( response.type === 'success' ) {
			var _Thumb = "";
			var load_thumb = wp.template('load-download-attachments');
			var counter = Math.floor((Math.random() * 999999) + 999);;
			var _size 	= bytesToSize(file.size);
			var data 	= {counter : counter,id: file.id,size:_size,name:file.name,url:response.thumbnail};       
			load_thumb  = load_thumb(data);
			_Thumb 		+= load_thumb;

			jQuery('.dc-formprojectinfo .dc-attachfile').append(_Thumb);
			jQuery('.dc-formprojectinfo .dc-attachfile').addClass('dc-infouploading');
		} else {
			jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
			jQuery("#thumb-" + file.id).remove();
		}
	});
}

//Image uploader
function init_image_uploader_gallery(current_uploader, current_type,role) {
    var uploadSize          = scripts_vars.data_size_in_kb;
    var award_image_size    = scripts_vars.award_image_size;

    var uploaderArguments = {
        browse_button: 'image-btn-' + current_uploader, // this can be an id of a DOM element or the DOM element itself
        file_data_name: 'file_name',
        container: 'dc-image-container-' + current_uploader,
        drop_element: 'image-drag-' + current_uploader,
        multipart_params: {
            "type": "file_name",
        },
        multi_selection: true,
        url: scripts_vars.ajaxurl + "?action=doccure_temp_file_uploader&security=" + scripts_vars.ajax_nonce,
        filters: {
            mime_types: [
                {title: '', extensions: "jpg,jpeg,gif,png"}
            ],
            max_file_size: uploadSize,
            max_file_count: 1,
            prevent_duplicates: false
        }
    };

    var ImageUploader = new plupload.Uploader(uploaderArguments);
    ImageUploader.init();

    //bind
    ImageUploader.bind('FilesAdded', function (up, files) {
        var imageThumb = "";

        plupload.each(files, function (file) {
			var load_thumb = wp.template('load-gallery-image');
			var _size = bytesToSize(file.size);
            var data = {id: file.id,size:_size,name:file.name,percentage:file.percent};       
            load_thumb = load_thumb(data);
            imageThumb += load_thumb;
        });  


        jQuery('#dc-img-' + current_uploader + ' .dc-galler-images').append(imageThumb);
        up.refresh();
        ImageUploader.start();
    });

    //bind
    ImageUploader.bind('UploadProgress', function (up, file) {
        var _html = '<span class="uploadprogressbar" style="width:'+file.percent+'%"></span>';
        jQuery('#dc-img-' + current_uploader + ' .uploadprogressbar').replaceWith(_html);
    });

    //Error
    ImageUploader.bind('Error', function (up, err) {
        jQuery.sticky(err.message, {classList: 'important', speed: 200, autoclose: 5000});
    });

    //display data
    ImageUploader.bind('FileUploaded', function (up, file, ajax_response) {
        var response = jQuery.parseJSON(ajax_response.response);
        if ( response.type === 'success' ) {          
			if( current_type == 'gallery' ){                
                var load_thumb = wp.template('load-append-gallery-image');
            }

            var counter = current_uploader;        
            var data = {count: counter, name: response.name, url:response.thumbnail, size:response.size};    
			
			var load_thumb = load_thumb(data);
            jQuery("#thumb-" + file.id).html(load_thumb);                    
            jQuery('#image-drag-'+ current_uploader).removeClass('dc-infouploading');
            jQuery('#dc-img-' + current_uploader + ' .img-thumb').find('img').attr('src', response.thumbnail);
        } else {
            jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
			jQuery("#thumb-" + file.id).remove();
        }
    });    
	
}

//Image uploader
function init_image_uploader_v2(current_uploader, current_type,role) {
    var uploadSize          = scripts_vars.data_size_in_kb;
    var awardImage          = scripts_vars.award_image;

    var uploaderArguments = {
        browse_button: 'image-btn-' + current_uploader, // this can be an id of a DOM element or the DOM element itself
        file_data_name: 'file_name',
        container: 'dc-image-container-' + current_uploader,
        drop_element: 'image-drag-' + current_uploader,
        multipart_params: {
            "type": "file_name",
        },
        multi_selection: false,
        url: scripts_vars.ajaxurl + "?action=doccure_temp_file_uploader&security=" + scripts_vars.ajax_nonce,
        filters: {
            mime_types: [
                {title: awardImage, extensions: "jpg,jpeg,gif,png"}
            ],
            max_file_size: uploadSize,
            max_file_count: 1,
            prevent_duplicates: false
        }
    };

    var ImageUploader = new plupload.Uploader(uploaderArguments);
    ImageUploader.init();

    //bind
    ImageUploader.bind('FilesAdded', function (up, files) {
        var imageThumb = "";

		var load_thumb = wp.template('load-default-image');
		
        plupload.each(files, function (file) {
			var _size = bytesToSize(file.size);
            var data = {id: file.id,size:_size,name:file.name,percentage:file.percent};       
            load_thumb = load_thumb(data);
            imageThumb += load_thumb;
        });  

        jQuery('#dc-img-' + current_uploader + ' .uploaded-placeholder').html(imageThumb);
        up.refresh();
        ImageUploader.start();
    });

    //bind
    ImageUploader.bind('UploadProgress', function (up, file) {
        var _html = '<span class="uploadprogressbar" style="width:'+file.percent+'%"></span>';
        jQuery('#dc-img-' + current_uploader + ' .uploadprogressbar').replaceWith(_html);
    });

    //Error
    ImageUploader.bind('Error', function (up, err) {
        jQuery.sticky(err.message, {classList: 'important', speed: 200, autoclose: 5000});
    });

    //display data
    ImageUploader.bind('FileUploaded', function (up, file, ajax_response) {
        var response = jQuery.parseJSON(ajax_response.response);
        if ( response.type === 'success' ) {     
			
            if( current_type == 'banner' ){                
                var load_thumb = wp.template('load-banner-image');
            } else {
                var load_thumb = wp.template('load-profile-image');
            }

            var counter = current_uploader;        
            var data = {count: counter, name: response.name, url:response.thumbnail, size:response.size};       
            var load_thumb = load_thumb(data);
            jQuery("#thumb-" + file.id).html(load_thumb);                    
            jQuery('#image-drag-'+ current_uploader).removeClass('dc-infouploading');
            jQuery('#dc-img-' + current_uploader + ' .img-thumb').find('img').attr('src', response.thumbnail);
        } else {
            jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
			jQuery("#thumb-" + file.id).remove();
        }
    });    
	
}

//Image uploader
function init_uploader_registrations(current_uploader, current_type,role) {
    var uploadSize          = scripts_vars.data_size_in_kb;
    var awardImage          = scripts_vars.award_image;

    var uploaderArguments = {
        browse_button: 'image-btn-' + current_uploader, // this can be an id of a DOM element or the DOM element itself
        file_data_name: 'file_name',
        container: 'dc-image-container-' + current_uploader,
        drop_element: 'image-drag-' + current_uploader,
        multipart_params: {
            "type": "file_name",
        },
        multi_selection: false,
        url: scripts_vars.ajaxurl + "?action=doccure_temp_file_uploader&security=" + scripts_vars.ajax_nonce,
        filters: {
            mime_types: [
                {title: awardImage, extensions: "jpg,jpeg,gif,png,pdf,doc,docx,ppt,txt,xls,xlsx,pptx,ods,html"}
            ],
            max_file_size: uploadSize,
            max_file_count: 1,
            prevent_duplicates: false
        }
    };

    var ImageUploader = new plupload.Uploader(uploaderArguments);
    ImageUploader.init();

    //bind
    ImageUploader.bind('FilesAdded', function (up, files) {
        var imageThumb = "";
		var load_thumb = wp.template('load-default-image');
		var url		   = jQuery('#dc-img-' + current_uploader).data('default');
        plupload.each(files, function (file) {
			var _size 	= bytesToSize(file.size);
            var data 	= {id: file.id,size:_size,name:file.name,url:url,percentage:file.percent};       
            load_thumb 	= load_thumb(data);
            imageThumb += load_thumb;
        });  

        jQuery('#dc-img-' + current_uploader + ' .uploaded-placeholder').html(imageThumb);
        up.refresh();
        ImageUploader.start();
    });

    //bind
    ImageUploader.bind('UploadProgress', function (up, file) {
        var _html = '<span class="uploadprogressbar" style="width:'+file.percent+'%"></span>';
        jQuery('#dc-img-' + current_uploader + ' .uploadprogressbar').replaceWith(_html);
    });

    //Error
    ImageUploader.bind('Error', function (up, err) {
        jQuery.sticky(err.message, {classList: 'important', speed: 200, autoclose: 5000});
    });

    //display data
    ImageUploader.bind('FileUploaded', function (up, file, ajax_response) {
        var response = jQuery.parseJSON(ajax_response.response);
        if ( response.type === 'success' ) {          
            if( current_type == 'banner' ){                
                var load_thumb = wp.template('load-banner-image');
            } else {
                var load_thumb = wp.template('load-profile-image');
            }

            var counter 	= current_uploader;
			
			var url		   = jQuery('#dc-img-' + current_uploader).data('default');
            var data 		= {count: counter, name: response.name, url:response.thumbnail,preview:url, size:response.size};       
            var load_thumb 	= load_thumb(data);
            jQuery("#thumb-" + file.id).html(load_thumb);                    
            jQuery('#image-drag-'+ current_uploader).removeClass('dc-infouploading');
            jQuery('#dc-img-' + current_uploader + ' .img-thumb').find('img').attr('src', url);
        } else {
            jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
			jQuery("#thumb-" + file.id).remove();
        }
    });    

	
}

// function fixedNav(){			
// 	jQuery(window).scroll(function () {			
// 		var pscroll = jQuery(window).scrollTop();						
// 		if(pscroll > 76){
// 		 jQuery('.dc-sidebarwrapper').addClass('dc-fixednav');
// 		}else{
// 		 jQuery('.dc-sidebarwrapper').removeClass('dc-fixednav');
// 		}
// 	});
// }
	
//Date picker
function init_datepicker(_class){
    jQuery('.'+_class).datetimepicker({      
        datepicker: true,
        timepicker: false,  
        dayOfWeekStart:1,
        maxDate: 0,
        format: scripts_vars.calendar_format,
		dayOfWeekStart:scripts_vars.startweekday,
    });
}

//year picker
function init_datepicker_year(_class){
    jQuery('.'+_class).datetimepicker({      
        datepicker: true,
        timepicker: false,  
      	format: 'Y',
        dayOfWeekStart:1,
        maxDate: 0,
		dayOfWeekStart:scripts_vars.startweekday,
    });
}

//Child accordions
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

function doccure_print() {
    var medical_history     = document.getElementById('medical_history');
    var new_window          = window.open('','Print-Window');
    new_window.document.open();
    new_window.document.write('<html><body onload="window.print()">'+medical_history.innerHTML+'</body></html>');
    new_window.document.close();
    setTimeout(function(){new_window.close();},10);
}

