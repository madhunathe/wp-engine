"use strict";
jQuery(document).ready(function(e) {
	jQuery('.options_group.pricing').addClass('show_if_packages').show();
	jQuery('.options_group.pricing').addClass('show_if_packages').show();
	jQuery('.shipping_tab').addClass('hide_if_packages');
	jQuery('.linked_product_tab').addClass('hide_if_packages');
	jQuery('.attribute_tab').addClass('hide_if_packages');
	jQuery('.variations_tab').addClass('hide_if_packages');
	jQuery('.advanced_tab').addClass('hide_if_packages');
	jQuery('.color-picker, .am_color_picker').wpColorPicker();
	
    jQuery('#upload_category_image').on('click', function() {
        "use strict";
        var $ = jQuery;
        var custom_uploader = wp.media({
            title: 'Select File',
            button: {
                text: 'Add File'
            },
            multiple: false
        })
                .on('select', function() {
                    var attachment = custom_uploader.state().get('selection').first().toJSON();
                    jQuery('#category_image').val(attachment.url);
                    jQuery('#category-src').attr('src', attachment.url);
                    jQuery('#category-wrap').show();
                }).open();

    });
	
	jQuery('input[type=radio][name=am_availability]').change(function() {
		if (jQuery(this).val() == 'others') {
			jQuery('.am_other_time').show();
		} else {
			jQuery('.am_other_time').hide();
		}
	});
    
	//Upload avatar
    jQuery('#upload-user-avatar').on('click', function() {
        "use strict";
        var $ = jQuery;
        var $this = jQuery(this);
        var custom_uploader = wp.media({
            title: 'Select File',
            button: {
                text: 'Add File'
            },
            multiple: false
        })
                .on('select', function() {
                    var attachment = custom_uploader.state().get('selection').first().toJSON();
                    jQuery('#author_profile_avatar').val(attachment.url);
                    jQuery('#avatar-src').attr('src', attachment.url);
                    jQuery('#avatar-wrap').show();
                    $this.parents('tr').next('tr').find('.backgroud-image').show();
                    $this.parents('tr').next('tr').attr('class', '');
                }).open();

    });
	
	// Delete Avatar
	jQuery(document).on('click', '.delete-auhtor-media', function(e) {
        jQuery(this).parents('.backgroud-image').find('img').attr('src', '');
        jQuery(this).parents('tr').prev('tr').find('.media-image').val('');
        jQuery(this).parents('.backgroud-image').hide();
    });
	
	
	//Testimonials
	jQuery(document).on('click', '.add_more_testimonial', function (event) {
		var widget	= jQuery(this).data('widget');
		var $html	= '';

		$html	= '<div class="data-services"><p><label for="">Client Name</label><input type="text" id="heading" name="widget-doccure_testimonial['+widget+'][client][]" value="" class="widefat" /></p> <p><label for="description">Description</label><textarea id="description"  rows="8" cols="10" name="widget-doccure_testimonial['+widget+'][description][]" class="widefat"></textarea></p><p class="data-del"><a href="javascript:;" class="delete-me"><i class="fa fa-times"></i></a></p></div>';

		jQuery('.testimonial_html').append($html);

	});
	
	
	jQuery(document).on('click','.delete-me', function (e) {
		jQuery(this).parents('.data-services').remove();
    });
	
    //Modal box Window
	jQuery('.order-edit-wrap').on('click',".cus-open-modal", function(event){
		event.preventDefault();
		var _this	= jQuery(this);
		jQuery(_this.data("target")).show();
		jQuery(_this.data("target")).addClass('in');
		jQuery('body').addClass('cus-modal-open');
	});
	
	jQuery('.order-edit-wrap, .withdrawal').on('click',".cus-close-modal", function(event){
		event.preventDefault();
		var _this	= jQuery(this);
		
		jQuery(_this.data("target")).removeClass('in');
		jQuery(_this.data("target")).hide();
		jQuery('body').removeClass('cus-modal-open');
	});
	
    //Woocommerce Package Switcher Code
	if( jQuery( 'body' ).find( '.woocommerce_options_panel' ) ){
		var select_pack	= jQuery('.dc_package_type').val();
		if( select_pack !== null && ( select_pack === 'doctors' || select_pack === 'trail_doctors') ){
			jQuery('.dc_doctors').parents('.form-field').show();
			jQuery('.dc-common-field').parents('.form-field').show();
			
			var duration	= jQuery('.dc-duration').val();
			if( duration !== null && ( duration === 'others' ) ){
				jQuery('.dc-duration-days').parents('.form-field').show();
			} else {
				jQuery('.dc-duration-days').parents('.form-field').hide();
			}
		} else{
			jQuery('.dc-all-field').parents('.form-field').hide();
		}
	}
	
	jQuery(document).on('change','.dc_package_type', function (e) {
		var _this	= jQuery(this);
		var _current= _this.val();
		if( _current !== null && ( _current === 'doctors' || _current === 'trail_doctors' ) ){
			jQuery('.dc_doctors').parents('.form-field').show();
			jQuery('.dc-common-field').parents('.form-field').show();
			jQuery('.dc-duration-days').parents('.form-field').hide();
		} else{
			jQuery('.dc-all-field').parents('.form-field').hide();
		}
	});
	
	jQuery(document).on('change','.dc-duration', function (e) {
		var _this		= jQuery(this);
		var _current	= _this.val();
		if( _current !== null && ( _current === 'others' ) ){
			jQuery('.dc-duration-days').parents('.form-field').show();
		} else{
			jQuery('.dc-duration-days').parents('.form-field').hide();
		}
	});
});


