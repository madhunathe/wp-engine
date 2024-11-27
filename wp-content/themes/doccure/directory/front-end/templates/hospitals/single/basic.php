<?php
/**
 *
 * The template used for hospital basics
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link      https://themeforest.net/user/dreamstechnologies/portfolio
 * @version 1.0
 * @since 1.0
 */

global $post,$current_user;

$post_id 	= $post->ID; 
$user_id	= doccure_get_linked_profile_id( $post_id,'users' );
$verified	= get_post_meta($post_id, '_is_verified', true);
$verified	= !empty( $verified ) ? $verified	: '';
$shoert_des		= doccure_get_post_meta( $post_id, 'am_short_description');
$tagline		= doccure_get_post_meta( $post_id, 'am_sub_heading');
$mrv			= doccure_get_post_meta( $post_id, 'am_registration_number');

$name			= doccure_full_name( $post_id );
$name			= !empty( $name ) ? $name : ''; 

$feedback			= get_post_meta($post_id,'review_data',true);
$feedback			= !empty( $feedback ) ? $feedback : array();
$total_rating		= !empty( $feedback['dc_total_rating'] ) ? $feedback['dc_total_rating'] : 0 ;
$total_percentage	= !empty( $feedback['dc_total_percentage'] ) ? $feedback['dc_total_percentage'] : 0 ;

$doctor_avatar = apply_filters(
		'doccure_doctor_avatar_fallback', doccure_get_doctor_avatar( array( 'width' => 255, 'height' => 250 ), $post_id ), array( 'width' => 255, 'height' => 250 )
	);

$doctor_avatar_2x = apply_filters(
		'doccure_doctor_avatar_fallback', doccure_get_doctor_avatar( array( 'width' => 545, 'height' => 428 ), $post_id ), array( 'width' => 545, 'height' => 428 )
	);
?>
<div class="dc-docsingle-header">
	<?php if( !empty( $doctor_avatar ) ){?>
		<figure class="dc-docsingleimg">
			<img class="dc-ava-detail" src="<?php echo esc_url( $doctor_avatar );?>" alt="<?php echo esc_attr( get_the_title() );?>">
			<img class="dc-ava-detail-2x" src="<?php echo esc_url( $doctor_avatar_2x );?>" alt="<?php echo esc_attr( get_the_title() );?>">
		</figure>
	<?php } ?>
	<div class="dc-docsingle-content dc-hossingle-content">
		<div class="dc-title">
		<?php do_action('doccure_specilities_list',$post_id,1);?>
			<h2>
				<a href="<?php esc_url( the_permalink() );?>"><?php echo esc_html( $name );?></a>
				<?php do_action('doccure_get_drverification_check',$post_id,esc_html__('Medical Registration Verified','doccure'));?>
				<?php do_action('doccure_get_verification_check',$post_id,'');?>
			</h2>
			<ul class="dc-docinfo">
				<?php if( !empty( $tagline ) ) {?>
					<li><em><?php echo esc_html( $tagline );?></em></li>
				<?php }?>
			</ul>
		</div>
		<?php if( !empty( $shoert_des ) ) {?>
			<div class="dc-description">
				<p><?php echo esc_html( $shoert_des );?></p>
			</div>
		<?php }?>
		<div class="dc-btnarea">
			<?php do_action('doccure_get_favorit_check',$post_id,'large');?>
		</div>
	</div>
</div>