"use strict";
jQuery(document).ready(function($) {
	
	//Change page settings 
	jQuery('#am_title_bar').on('change', function(e){
		var _this	= jQuery(this);
		var _item_val	= _this.val();
		_this.parents('.am_option_wraper').nextAll().hide();
		_this.parents('.am_option_wraper').nextAll('.'+_item_val+'-wrapper').show();
	});
	
	//init date picker
	init_datepicker('dc-datetimepicker-wrapper input');
	init_datepicker('dc-datetimepicker');
	
	setTimeout(function(){
		jQuery( "#am_title_bar" ).trigger( "change" );
	}, 1000);
	
	//Save settings
	jQuery(document).on('click', '.save-data-settings', function(event) {
		event.preventDefault();
		var serialize_data = jQuery('.save-settings-form').serialize();
		var dataString 	   = 'security='+scripts_vars.ajax_nonce+'&'+serialize_data + '&action=doccure_save_doccure_options';
		
		var _this = jQuery(this);
		jQuery('.dc-featurescontent').append('<div class="inportusers">'+scripts_vars.spinner+'</div>');
		jQuery.ajax({
			type: "POST",
			url: ajaxurl,
			dataType:"json",
			data: dataString,
			success: function(response) {
				jQuery('.dc-featurescontent').find('.inportusers').remove();
				jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000});
				window.location.reload();
			}
		});

	});

	//Update mailchimp list
	jQuery(document).on('click', '.am-latest-mailchimp-list', function(event) {
		event.preventDefault();
		var dataString = 'security='+scripts_vars.ajax_nonce+'&action=doccure_mailchimp_array';
		
		var _this = jQuery(this);
		jQuery('.dc-featurescontent').append('<div class="inportusers">'+scripts_vars.spinner+'</div>');
		jQuery.ajax({
			type: "POST",
			url: ajaxurl,
			dataType:"json",
			data: dataString,
			success: function(response) {
				jQuery('.dc-featurescontent').find('.inportusers').remove();
				jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000});
				window.location.reload();
			}
		});

    });
	
	//Update mailchimp list
	jQuery(document).on('click', '.am-get-list', function(event) {
		event.preventDefault();
		var dataString = 'security='+scripts_vars.ajax_nonce+'&action=doccure_cron_interval_array';
		
		var _this = jQuery(this);
		jQuery('.dc-featurescontent').append('<div class="inportusers">'+scripts_vars.spinner+'</div>');
		jQuery.ajax({
			type: "POST",
			url: ajaxurl,
			dataType:"json",
			data: dataString,
			success: function(response) {
				jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000});
				window.location.reload();
			}
		});

    });
	
	//veryfy profiles
	jQuery(document).on('click', '.do_verify_user', function() {
		var _this 		= jQuery(this);
		var _type		= _this.data('type'); 
		
		if( _type === 'reject' ){
			var localize_title = scripts_vars.reject_account;
			var localize_vars_message = scripts_vars.reject_account_message;
		}else{
			var localize_title = scripts_vars.approve_account;
			var localize_vars_message = scripts_vars.approve_account_message;
		}

		jQuery.confirm({
			title: localize_title,
			content: localize_vars_message,
			boxWidth: '500px',
    		useBootstrap: false,
			typeAnimated: true,
			closeIcon: function(){
				return true; 
			},
			closeIcon: 'aRandomButton',
			buttons: {
				yes: {
					text: scripts_vars.yes,
					action: function () {
						var _id			= _this.data('id'); 
						var _user_id	= _this.data('user_id'); 
						var _type		= _this.data('type'); 
						var dataString = 'security='+scripts_vars.ajax_nonce+'&type='+_type+'&id='+_id+'&user_id='+_user_id+'&action=doccure_approve_profile';
						var jc	= this; 
						jc.showLoading();
						jQuery.ajax({
							type: "POST",
							url: ajaxurl,
							dataType:"json",
							data: dataString,
							success: function(response) {
								jQuery('.inportusers').remove();
								if( response.type === 'success' ){
									jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000});
									window.location.reload();
								} else{
									jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
								}
							}
						});
				
						return false;
					}
				},
				no: {
					close: {
						text: scripts_vars.close
					}
				}
			}
		});

    });
	
	//import dummy users
	jQuery(document).on('click', '.doc-import-users', function() {
		 jQuery.confirm({
			title: scripts_vars.import,
			message: scripts_vars.import_message,
			boxWidth: '500px',
    		useBootstrap: false,
			typeAnimated: true,
			closeIcon: function(){
				return true; 
			},
			closeIcon: 'aRandomButton',
			'buttons': {
				'Yes': {
					'class': 'blue',
					'action': function () {
						var dataString = 'security='+scripts_vars.ajax_nonce+'&action=doccure_import_users';
						var $this = jQuery(this);
						jQuery('#import-users').append('<div class="inportusers">'+scripts_vars.spinner+'</div>');
						jQuery.ajax({
							type: "POST",
							url: ajaxurl,
							dataType:"json",
							data: dataString,
							success: function(response) {
								jQuery('#import-users').find('.inportusers').remove();
								jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000});
							}
						});
				
						return false;
					}
				},
				'No': {
					'class': 'gray',
					'action': function () {
						return true;
					}	// Nothing to do in this case. You can as well omit the action property.
				}
			}
		});
    });
	
	
	jQuery('.am_option_tabs ul a').on('click', function(e){
		e.preventDefault();

		if(jQuery(this).hasClass('open')) {
		  // do nothing because the link is already open
		} else {
		  var oldcontent = jQuery('.am_option_tabs ul a.open').attr('href');
		  var newcontent = jQuery(this).attr('href');

		  jQuery(oldcontent).fadeOut('fast', function(){
			jQuery(newcontent).fadeIn().removeClass('hidden');
			jQuery(oldcontent).addClass('hidden');
		  });
		  jQuery('.am_option_tabs ul a').removeClass('open');
		  jQuery(this).addClass('open');
		}
	});
	
	jQuery(document).on('click',".system_media_upload_button", function() {
		var _this 			= jQuery(this);
        var inputfield 		= _this.parent().prev('.input-sec').find('input').attr('id');
		var screenshot 		= _this.parent().parent().find('.screenshot');
		var selector		= _this.parents('.section-upload');
		var custom_uploader = wp.media({
			title: 'Select File',
			button: {
				text: 'Add File'
			},
			multiple: false
		})
			.on('select', function() {
				var attachment  = custom_uploader.state().get('selection').first().toJSON();
				var itemurl			= attachment.url;
				var itemid			= attachment.id;
				var btnContent 		= '<a href="'+itemurl+'"><img class="system-upload-image" src="'+itemurl+'" alt="" /></a>';
				selector.find('.remove-item').css( 'display', 'inline-block' );
				$('#' + inputfield).val(itemurl);
				$('#' + inputfield).next('input').val(itemid);
			 	screenshot.fadeIn().html(btnContent);
			}).open();

	});	
	
	jQuery(document).on('click',".upload_button_wgt", function() {
		var _this 			= jQuery(this);
        var inputfield 		= _this.parent().find('input').attr('id');
		var custom_uploader = wp.media({
			title: 'Select File',
			button: {
				text: 'Add File'
			},
			multiple: false
		})
		.on('select', function() {
			var attachment  = custom_uploader.state().get('selection').first().toJSON();
			var itemurl			= attachment.url;
			$('#' + inputfield).val(itemurl);
		}).open();

	});	
	
	
	jQuery('.remove-item').on('click', function() {
		var _this 		= jQuery(this);
		var selector	= _this.parents('.section-upload')
		selector.find('.remove-item').hide().addClass('hide');//hide "Remove" button
		selector.find('.upload').val('');
		selector.find('.screenshot').slideUp();
	});

	//Single Repeater
	jQuery(document).on('click','.add-repeater-phone_numbers', function() {
		var load_repeater = wp.template('load-phone_numbers'); 
		var counter = Math.floor((Math.random() * 999999) + 999);
		var data = {counter: counter, name: 'phone_numbers'};        
		load_repeater = load_repeater(data);             
		jQuery('.repeater-wrap-phone_numbers').append(load_repeater);
	});

	jQuery(document).on('click','.add-repeater-memberships_name', function() {
		var load_repeater = wp.template('load-memberships_name'); 
		var counter = Math.floor((Math.random() * 999999) + 999);
		var data = {counter: counter, name: 'memberships_name'};        
		load_repeater = load_repeater(data);             
		jQuery('.repeater-wrap-memberships_name').append(load_repeater);
	});
	
	jQuery(document).on('click','.add-repeater-videos', function() {
		var _this	= jQuery(this);
		var load_repeater = wp.template('load-repeater'); 
		var counter = Math.floor((Math.random() * 999999) + 999);
		var data = {counter: counter, name: 'videos', placeholder: _this.data('placeholder')};        
		load_repeater = load_repeater(data);     
		jQuery('.repeater-wrap-videos').append(load_repeater);
	});

	//Single Repeater
	jQuery(document).on('click','.add-repeater-booking_contact', function() {
		var load_repeater = wp.template('load-booking_contact'); 
		var counter = Math.floor((Math.random() * 999999) + 999);
		var data = {counter: counter, name: 'booking_contact'};        
		load_repeater = load_repeater(data);             
		jQuery('.repeater-wrap-booking_contact').append(load_repeater);
	});
	
	//Single Repeater
	jQuery(document).on('click','.add-repeater-downloads', function() {
		var load_repeater = wp.template('load-downloads'); 
		var counter = Math.floor((Math.random() * 999999) + 999);
		var data = {counter: counter, name: 'downloads'};        
		load_repeater = load_repeater(data);             
		jQuery('.repeater-wrap-downloads').append(load_repeater);
	});
	
	//Specialities and Services Repeater
	jQuery(document).on('click','.add-repeater-services', function() {
		var _this	= jQuery(this);
		var _id		= _this.data('id');
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
			_this.parents('.services-wrap').prepend(load_repeater);
		} else{
			alert('select speciality');
		}
		
		
	});
	
	jQuery(document).on('click','.add-repeater-specialities', function() {
		var load_repeater = wp.template('load-specialities'); 
		var counter = Math.floor((Math.random() * 999999) + 999);
		var data = {counter: counter};        
		load_repeater = load_repeater(data);             
		jQuery('.repeater-wrap-specialities').append(load_repeater);
	});
	
	jQuery(document).on('change','.item-specialities-dp',function () {
        var _this = jQuery(this);
		var _sp_id = this.value;
        if (DT_Editor.elements[_sp_id]) {
			var _options = DT_Editor.elements[_sp_id];
		} else {
			var _options = [];
		}
		
		var load_repeater = wp.template('load-services-options'); 
		var data = {options: _options};        
		load_repeater = load_repeater(data);
		
		var _fields	= _this.parents('.specialities_parents').find('.services-item .sp_services').empty().append(load_repeater);
		
	});
	
	//Repeater experiences
	jQuery(document).on('click','.add-repeater-experiences', function() {
		var load_repeater = wp.template('load-exprience'); 
		var counter = Math.floor((Math.random() * 999999) + 999);
		var data = {counter: counter};        
		load_repeater = load_repeater(data);             
		jQuery('.repeater-wrap-experiences').append(load_repeater);
		init_datepicker('dc-datetimepicker');
	});
	
	//Repeater education
	jQuery(document).on('click','.add-repeater-education', function() {
		var load_repeater = wp.template('load-education'); 
		var counter = Math.floor((Math.random() * 999999) + 999);
		var data = {counter: counter};        
		load_repeater = load_repeater(data);             
		jQuery('.repeater-wrap-education').append(load_repeater);
		init_datepicker('dc-datetimepicker');
	});
	
	//
	jQuery(document).on('click','.add-repeater-award', function() {
		var load_repeater = wp.template('load-award'); 
		var counter = Math.floor((Math.random() * 999999) + 999);
		var data = {counter: counter};        
		load_repeater = load_repeater(data);             
		jQuery('.repeater-wrap-award').append(load_repeater);
	});
	
	//Single Repeater
	jQuery(document).on('click','.remove-repeater', function() {  
		var _this 		= jQuery(this);
		jQuery.confirm({
			'title': scripts_vars.repeater_title,
			'message': scripts_vars.repeater_message,
			boxWidth: '500px',
    		useBootstrap: false,
			typeAnimated: true,
			closeIcon: function(){
				return true; 
			},
			closeIcon: 'aRandomButton',
			'buttons': {
				'Yes': {
					'class': 'blue',
					'action': function () {
						jQuery(_this).parent('.repeater-wrap-inner').remove();
						return false;
					}
				},
				'No': {
					'class': 'gray',
					'action': function () {
						return true;
					}	// Nothing to do in this case. You can as well omit the action property.
				}
			}
		});
	});
	
	/*---------------------------------------------------------------------
	 * Z Multi Uploader
	 *---------------------------------------------------------------------*/
	jQuery('.gallery-list').sortable();
	var gallery_container	= jQuery('.gallery-container');
	var gallery_frame;
	var gallery_ids 	 	= jQuery('#gallery_ids');
	var reset_gallery   	= jQuery('#reset_gallery');
	var gallery_images  	= jQuery('ul.gallery-list');
	jQuery('.multi_open').on('click', function(event) {
		
		var $el = jQuery(this);
		event.preventDefault();
		if ( gallery_frame ) {
			gallery_frame.open();
			return;
		}
		
		// Create the media frame.
		gallery_frame = wp.media.frames.gallery = wp.media({
			title	: $el.data('choose'),
			library  : { type : 'image'},
			button   : {
				text : $el.data('update'),
			},
			states   : [
				new wp.media.controller.Library({
					title		: $el.data('choose'),
					 filterable: 'image',
					multiple	 : true,
				})
			]
		});

		// When an image is selected, run a callback.
		gallery_frame.on( 'select', function() {
			var selection = gallery_frame.state().get('selection');
			var input_id	= gallery_container.parent().data('id');
			selection.map( function( attachment ) {
				attachment = attachment.toJSON();
				if ( attachment.id ) {
					gallery_container.show();
					reset_gallery.show();
					gallery_images.append('\
						<li class="image" data-attachment_id="' + attachment.id + '">\
						<input type="hidden" name="am_'+input_id+'[' + attachment.id + '][attachment_id]" value="' + attachment.id + '"/>\
						<input type="hidden" name="am_'+input_id+'[' + attachment.id + '][url]" value="' + attachment.url + '"/>\
						<img src="' + attachment.url + '" />\
						<a href="javascript:;" class="del-node" title="' + $el.data('delete') + '"><i class="fa fa-times"></i></a>\
					</li>');
					}
				});

			});
			// Finally, open the modal.
			gallery_frame.open();

		});
		
		/*---------------------------------------------------------------------
		 * Z Get Gallery IDS
		 *---------------------------------------------------------------------*/
		jQuery(function() {
		 jQuery('#post').submit( function() {
			var multi = jQuery('.gallery-list .image');
			var container = jQuery('#z_gallery_images');
			var gallery_array = [];
			jQuery.each(multi, function (index, item) {
				 gallery_array.push( jQuery(item).data('attachment_id') );
			});
			container.val(gallery_array);
			return true;
		 });
		 
		 /*---------------------------------------------------------------------
		  * Z Delete gallery Node
		  *---------------------------------------------------------------------*/
		 jQuery( '.gallery-list' ).delegate( "a", "click", function() { 
			jQuery(this).parent().remove();
		 });
		 
		 /*---------------------------------------------------------------------
		  * Z Gallery
		  *---------------------------------------------------------------------*/
		 jQuery( '.system-buttons' ).delegate( "#reset_gallery", "click", function() { 
			jQuery('.gallery-list').html('');
			jQuery(this).hide();
		 });
	});
});

/*
	Sticky v2.1.2 by Andy Matthews
	http://twitter.com/commadelimited

	forked from Sticky by Daniel Raftery
	http://twitter.com/ThrivingKings
*/
(function ($) {

	jQuery.sticky = jQuery.fn.sticky = function (note, options, callback) {

		// allow options to be ignored, and callback to be second argument
		if (typeof options === 'function') callback = options;

		// generate unique ID based on the hash of the note.
		var hashCode = function(str){
				
				var hash = 0,
					i = 0,
					c = '',
					len = str.length;
				if (len === 0) return hash;
				for (i = 0; i < len; i++) {
					c = str.charCodeAt(i);
					hash = ((hash<<5)-hash) + c;
					hash &= hash;
				}
				return 's'+Math.abs(hash);
			},
			o = {
				position: 'top-right', // top-left, top-right, bottom-left, or bottom-right
				speed: 'fast', // animations: fast, slow, or integer
				allowdupes: true, // true or false
				autoclose: 5000,  // delay in milliseconds. Set to 0 to remain open.
				classList: '' // arbitrary list of classes. Suggestions: success, warning, important, or info. Defaults to ''.
			},
			uniqID = hashCode(note), // a relatively unique ID
			display = true,
			duplicate = false,
			tmpl = '<div class="sticky border-POS CLASSLIST" id="ID"><span class="sticky-close"></span><p class="sticky-note">NOTE</p></div>',
			positions = ['top-right', 'top-center', 'top-left', 'bottom-right', 'bottom-center', 'bottom-left'];

		// merge default and incoming options
		if (options) jQuery.extend(o, options);

		// Handling duplicate notes and IDs
		jQuery('.sticky').each(function () {
			if (jQuery(this).attr('id') === hashCode(note)) {
				duplicate = true;
				if (!o.allowdupes) display = false;
			}
			if (jQuery(this).attr('id') === uniqID) uniqID = hashCode(note);
		});

		// Make sure the sticky queue exists
		if (!jQuery('.sticky-queue').length) {
			jQuery('body').append('<div class="sticky-queue ' + o.position + '">');
		} else {
			// if it exists already, but the position param is different,
			// then allow it to be overridden
			jQuery('.sticky-queue').removeClass( positions.join(' ') ).addClass(o.position);
		}

		// Can it be displayed?
		if (display) {
			// Building and inserting sticky note
			jQuery('.sticky-queue').prepend(
				tmpl
					.replace('POS', o.position)
					.replace('ID', uniqID)
					.replace('NOTE', note)
					.replace('CLASSLIST', o.classList)
			).find('#' + uniqID)
			.slideDown(o.speed, function(){
				display = true;
				// Callback function?
				if (callback && typeof callback === 'function') {
					callback({
						'id': uniqID,
						'duplicate': duplicate,
						'displayed': display
					});
				}
			});

		}

		// Listeners
		jQuery('.sticky').ready(function () {
			// If 'autoclose' is enabled, set a timer to close the sticky
			if (o.autoclose) {
				jQuery('#' + uniqID).delay(o.autoclose).fadeOut(o.speed, function(){
					jQuery(this).remove();
				});
			}
		});

		// Closing a sticky
		jQuery('.sticky-close').on('click', function () {
			jQuery('#' + jQuery(this).parent().attr('id')).dequeue().fadeOut(o.speed, function(){
				jQuery(this).remove();
			});
		});

	};
})(jQuery);

//init datepicker
function init_datepicker(_class){
    jQuery('.'+_class).datetimepicker({      
        datepicker: true,
        timepicker: false,  
        dayOfWeekStart:1,
		format: 'Y-m-d',
    });
}