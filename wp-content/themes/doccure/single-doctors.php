<?php
/**
 *
 * The template used for doctors post style
 *
 * @package   doccure
 * @version 1.0
 * @since 1.0
 */
global $doccure_options,$current_user,$post;
$redirect_unverified			= !empty( $doccure_options['redirect_unverified'] ) ? $doccure_options['redirect_unverified'] : '';

if(!empty($redirect_unverified) && $redirect_unverified === 'yes'){
	//if user is unverified and blocked then redirect to home page
	$profile_option	= get_post_meta( $post->ID, '_profile_blocked', true );
	$is_verified	= get_post_meta( $post->ID, '_is_verified', true );
	if( ( !empty($is_verified) && $is_verified === 'no' ) || ( !empty($profile_option) && $profile_option === 'on' ) ){
		wp_redirect(home_url('/'));
		die();
	}
}

get_header();

$booking_option			= doccure_theme_option();
$doctor_detail_forum	= !empty( $doccure_options['doctor_detail_forum'] ) ? $doccure_options['doctor_detail_forum'] : '';
$enable_options			= !empty($doccure_options['doctors_contactinfo']) ? $doccure_options['doctors_contactinfo'] : '';

if(  is_active_sidebar( 'doctor-sidebar-right' ) ){ 
	$section_width     	= 'col-12';
} else {
	$section_width     	= 'col-12';
}

$doctor_user_id	= '';

while ( have_posts() ) {
	the_post();
	global $post;
	$width			= 271;
	$height			= 194;
	$thumbnail      = doccure_prepare_thumbnail($post->ID, $width, $height);
	$doctor_user_id	= doccure_get_linked_profile_id($post->ID,'post');
	$profile_option	= get_post_meta( $post->ID, '_profile_blocked', true );
	$is_verified	= get_post_meta( $post->ID, '_is_verified', true );
	$profile_option	= !empty($profile_option) ? $profile_option : '';
	$images  	= doccure_get_post_meta( $post->ID,'am_gallery');
	$post_meta		= doccure_get_post_meta( $post->ID );
	$am_socials		= !empty($post_meta['am_socials']) ? $post_meta['am_socials'] : '';
	$social_settings	= array();
	if(function_exists('doccure_get_social_media_icons_list')){
		$social_settings	= doccure_get_social_media_icons_list('no');
	}

	$social_available = 'no';
	if(!empty($social_settings) && is_array($social_settings) ) {
		foreach($social_settings as $key => $val ) {
			if(!empty($am_socials[$key])){
				$social_available = 'yes';
				break;
			}
		}
	}
	
	
	$social_sidebar	= array();
	
	$social_sidebar['social_available']	= $social_available;
	$social_sidebar['social_settings']	= $social_settings;

	$latitude		    = get_post_meta( $post->ID , '_latitude',true );
	$longitude		    = get_post_meta( $post->ID , '_longitude',true );
	?>
	<div class="dc-haslayout dc-parent-section single-doctor">
		<div class="container">
 
  			
				
				<div id="dc-twocolumns" class="dc-twocolumns dc-haslayout">
					<?php if( !empty($profile_option) && $profile_option ==='on' ){
						do_action('doccure_empty_records_html','dc-empty-hospital-location',esc_html__( 'The profile is a temporary block from user.', 'doccure' ));
					} else { 

							get_template_part('directory/front-end/templates/doctors/single/basic'); ?>

							<div class="card">
							<div class="dc-docsingle-holder" id="single_doctor_tabs">
								
								<ul class="dc-navdocsingletab nav navbar-nav">
									<li class="nav-item dc-available-location">
										<a data-bs-toggle="collapse" href="#locations_new"><?php esc_html_e('Locations','doccure');?></a>
									</li>
									<li class="nav-item dc-doctor-detail">
										<a data-bs-toggle="collapse" href="#userdetails_tab_new" aria-expanded="true"><?php esc_html_e('Overview','doccure');?></a>
									</li>
									<li class="nav-item dc-doctor-detail">
										<a data-bs-toggle="collapse" href="#gallery_tab_new" ><?php esc_html_e('Gallery','doccure');?></a>
									</li>
									<?php if( !empty($doctor_detail_forum) && $doctor_detail_forum === 'no' ){?>
										<li class="nav-item dc-forum-section">
											<a data-bs-toggle="collapse" href="#comments_tabnew"><?php esc_html_e('Forum Discussion','doccure');?></a>
										</li>
									<?php }?>
									<li class="nav-item dc-doc-feedback">
										<a data-bs-toggle="collapse" href="#feedback_tabnew"><?php esc_html_e('Reviews','doccure');?></a>
									</li>
								</ul>
								<div class="tab-content dc-contentdoctab dc-haslayout">
								<div class="collapse " id="locations_new"  data-bs-parent="#single_doctor_tabs">
									<?php get_template_part('directory/front-end/templates/doctors/single/locations'); ?>
								</div>
								<div class="collapse show" id="userdetails_tab_new" data-bs-parent="#single_doctor_tabs">
									<?php get_template_part('directory/front-end/templates/doctors/single/userdetails'); ?>
								</div>

  								
<div class="collapse" id="gallery_tab_new" data-bs-parent="#single_doctor_tabs">
	
	<div class="gallery-img">
			<div class="dc-projects">
				<?php 
					foreach( $images as $key => $gallery_image ){ 
						$gallery_thumnail_image_url 	= !empty( $gallery_image['attachment_id'] ) ? wp_get_attachment_image_src( $gallery_image['attachment_id'], array(255,200), true ) : '';
						$gallery_image_url 				= !empty( $gallery_image['url'] ) ? $gallery_image['url'] : '';
						
				?>
				<div class="dc-project dc-crprojects">
					<?php if( !empty($gallery_thumnail_image_url[0]) ){?>
						<a  data-rel="prettyPhoto[gallery]" href="<?php echo esc_url($gallery_image_url);?>"  rel="prettyPhoto[gallery]">
							<figure><img src="<?php echo esc_url( $gallery_thumnail_image_url[0] );?>" alt="<?php echo esc_attr($title);?>"></figure>
						</a>
					<?php }?>
				</div>
				<?php } ?>
			</div>
		</div>

		</div>
	<script type="application/javascript">
		jQuery(document).ready(function () {
			jQuery("a[data-rel]").each(function () {
				jQuery(this).attr("rel", jQuery(this).data("rel"));
			});
			jQuery("a[data-rel^='prettyPhoto']").prettyPhoto({
				animation_speed: 'normal',
				theme: 'light_square',
				slideshow: 3000,
				default_width: 800,
				default_height: 500,
				allowfullscreen: true,
				autoplay_slideshow: false,	
				social_tools: false,
				iframe_markup: "<iframe src='{path}' width='{width}' height='{height}' frameborder='no' allowfullscreen='true'></iframe>", 
				deeplinking: false
			});
		});
	</script>


									<?php if( !empty($doctor_detail_forum) && $doctor_detail_forum === 'no' ){ ?>
										<div class="collapse " id="comments_tabnew"  data-bs-parent="#single_doctor_tabs">
										<?php get_template_part('directory/front-end/templates/doctors/single/consultation'); ?>
										</div> 
									<?php }?>
									<div class="collapse " id="feedback_tabnew"  data-bs-parent="#single_doctor_tabs">
									<?php  get_template_part('directory/front-end/templates/doctors/single/feedback'); ?>
									</div>

									<?php get_template_part('directory/front-end/templates/doctors/single/articles'); ?>

									<div class="dc-shareprofile">
										<?php doccure_prepare_social_sharing( false,esc_html__('Share Profile','doccure'),true,'dc-simplesocialicons dc-socialiconsborder',$thumbnail ); ?>
									</div>
								</div>
							
						</div>
							</div>
						<?php if(  is_active_sidebar( 'doctor-sidebar-right' ) 
								 || ( !empty($social_available) && $social_available === 'yes' )
								 || (!empty($latitude) && !empty($longitude) && !empty($enable_options))
							){ ?>
							<div class="col-12 col-md-6 col-lg-6 col-xl-3 float-left">
								<aside id="dc-sidebar" class="dc-sidebar dc-sidebar-grid float-left mt-xl-0">
									<?php
										get_template_part('directory/front-end/templates/doctors-location-sidebar');
										get_template_part('directory/front-end/templates/sidebar-social','',$social_sidebar);
										if(  is_active_sidebar( 'doctor-sidebar-right' ) ){ 
											dynamic_sidebar( 'doctor-sidebar-right' );
										}
									?>
								</aside>
							</div>
						<?php } ?>
					<?php } ?>
				</div>
				 
		</div>
	</div>
<?php
if( ( empty($profile_option) || $profile_option ==='off' ) && !empty($doctor_user_id) && $doctor_user_id !== $current_user->ID  && ( apply_filters('doccure_is_feature_allowed', 'dc_chat', $doctor_user_id) === true )
) {
	get_template_part('directory/front-end/templates/messages'); 
}
	
if ( is_user_logged_in() ) {
	get_template_part('directory/front-end/templates/doctors/single/addfeedback');
}

get_template_part('directory/front-end/templates/doctors/single/bookings'); 
	
}

get_footer(); 