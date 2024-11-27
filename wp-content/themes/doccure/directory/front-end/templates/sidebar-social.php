<?php
/**
 *
 * The template used for displaying freelancer Skills
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link      https://themeforest.net/user/dreamstechnologies/portfolio
 * @version 1.0
 * @since 1.0
 */

global $post,$doccure_options;
$post_id 	= $post->ID;
$social_links	= !empty( $doccure_options['social_links'] ) ? $doccure_options['social_links'] : '';
$am_socials		= doccure_get_post_meta( $post_id, 'am_socials');
if(!empty($social_links) && $social_links === 'yes' && $args['social_available'] === 'yes') {?>
	<div class="dc-widge dcconnect-socialprofiles">
		<div class="doc-widgetheading"><h2><?php esc_html_e('Connect with me virtually', 'doccure'); ?></h2></div>
		<div class="dc-locationbox dc-socialabox">
			<ul class="dc-socialiconssimple">
				<?php foreach($args['social_settings'] as $key => $val ) {
					$icon		= !empty( $val['icon'] ) ? $val['icon'] : '';
					$color		= !empty( $val['color'] ) ? $val['color'] : '#484848';
					$social_url	= '';
					$social_url	= !empty($am_socials[$key]) ? $am_socials[$key] : '';

					if( $key === 'whatsapp' ){
						if ( !empty( $social_url ) ){
							$social_url	= 'https://api.whatsapp.com/send?phone='.$social_url;
						}
					} else if( $key === 'skype' ){
						if ( !empty( $social_url ) ){
							$social_url	= 'skype:'.$social_url.'?call';
						}
					}else{
						$social_url	= esc_url($social_url);;
					}

					if(!empty($social_url)) {?>
						<li><a href="<?php echo esc_attr($social_url); ?>" target="_blank">
							<i class="dc-icon <?php echo esc_attr( $icon );?>" style="color:<?php echo esc_attr( $color );?> !important"></i>
						</a></li>
					<?php } ?>
				<?php } ?>
			</ul>
		</div>
	</div>
<?php } ?>