<?php
/**
 * @File Type	General Options for pages, posts and custom post type
 * @package	 	WordPress
 * @link 		https://themeforest.net/user/dreamstechnologies
 */

// die if accessed directly
if (!defined('ABSPATH')) {
    die('no kiddies please!');
}

global $post;
$booking_id	= get_the_ID();
?>
<div id="am_ho_details_tab" >
	<div class="am_bookiing_details dc-featurescontent">
		
	</div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('.am_bookiing_details').append('<div class="inportusers">'+scripts_vars.spinner+'</div>');
		setTimeout(function() {
			jQuery.ajax({
				type: "POST",
				url: ajaxurl,
				dataType:"json",
				data: {
					action	: 'doccure_get_booking_byID',
					id		: <?php echo intval($booking_id);?>,
					security	: scripts_vars.ajax_nonce,
					type		: 'administrator',
				},
				success: function(response) {
					if (response.type === 'success') {
						jQuery('.am_bookiing_details').html(response.booking_data);
					} else {
						jQuery('.am_bookiing_details').html('');
					}
				}
			});
		}, 1000);
	});
</script>
