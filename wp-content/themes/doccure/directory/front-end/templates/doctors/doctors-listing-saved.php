<?php 
/**
 *
 * The template part for displaying doctors in listing
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link     https://dreamstechnologies.com/
 * @since 1.0
 */
global $post,$current_user;
 	$user_identity   = $current_user->ID;
	$link_id		 = doccure_get_linked_profile_id( $user_identity );
	$custom = get_post_custom( get_the_ID() );
          $url = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()), 'thumbnail' );
          $feedback			= get_post_meta(get_the_ID(),'review_data',true);
          $feedback			= !empty( $feedback ) ? $feedback : array();
          $total_rating		= !empty( $feedback['dc_total_rating'] ) ? $feedback['dc_total_rating'] : 0 ;
          $total_percentage	= !empty( $feedback['dc_total_percentage'] ) ? $feedback['dc_total_percentage'] : 0 ;
          $name			= doccure_full_name(get_the_ID() );
           $name			= !empty( $name ) ? $name : '';
	$starting_price	= doccure_get_post_meta( get_the_ID(), 'am_starting_price');

?>

<div class="dc-docpostholder col-lg-4 dochold">
<div class="row">

          <div class="profile-widget">
                
			<?php do_action('doccure_get_doctor_thumnail',$post->ID);?>

            <div class="pro-contents">
            <div class="content_block"><h3 class="content_block"><?php echo esc_html($name);?></h3></div>
				
				
            <div class="ratings">
              <div class="empty-stars"></div>
              <div class="full-stars" style="width: <?php echo intval( $total_percentage );?>%"></div>
            </div>

          <div class="location"><i class="feather-map-pin"></i><?php $first_category = wp_get_post_terms( get_the_ID(), 'locations' )[0]->name;
              echo esc_html($first_category);
            ?>
         </div>

          <?php if( !empty( $starting_price ) ) { ?>
					<div class="price-section"><i class="far fa-money-bill-alt"></i><?php esc_html_e('','doccure');?>&nbsp;<?php doccure_price_format($starting_price);?></div>
				<?php } ?>

          <div class="row row-sm saved_view_details">
											<div class="col-6">
                      <div class="btn view-btn"><a href="<?php echo get_permalink(get_the_ID())?>">View Profile</a></div>
											</div>
											<div class="col-6">
                      <div class="btn book-btn"><a href="<?php echo get_permalink(get_the_ID())?>">Book Now</a></div>
											</div>
										</div>
          </div>
          </div>
   
	</div>
</div>
