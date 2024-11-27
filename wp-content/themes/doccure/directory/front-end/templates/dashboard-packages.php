<?php
/**
 *
 * The template part for displaying Packages
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link     https://dreamstechnologies.com/
 * @since 1.0
 */
 
 global $current_user,$doccure_options;
$user_identity 	 	= $current_user->ID;
$linked_profile  	= doccure_get_linked_profile_id($user_identity);
$icon				= 'lnr lnr-hourglass';
$expiry_string			= doccure_get_subscription_metadata( 'subscription_package_string',intval( $user_identity ) );
$package_expiry_img		= !empty( $doccure_options['package_expiry']['url'] ) ? $doccure_options['package_expiry']['url'] : '';
$formatted_date			= ''; 

if( $expiry_string != false ){
	$formatted_date = date("Y, n, d, H, i, s", strtotime("-1 month",intval($expiry_string))); 
}


global $current_user;
$user_identity 	 	= $current_user->ID;
$linked_profile  	= doccure_get_linked_profile_id($user_identity);
$user_role			= doccure_get_user_type( $user_identity );
$currency_symbol	= doccure_get_current_currency();
$pakeges_features 	= doccure_get_pakages_features();
$meta_query_args	= array();

$args 				= array(
						'post_type' 			=> 'product',
						'posts_per_page' 		=> -1,
						'post_status' 			=> 'publish',
						'ignore_sticky_posts' 	=> 1
					);
$meta_query_args[] = array(
						'key' 		=> 'package_type',
						'value' 	=> $user_role,
						'compare' 	=> '=',
					);

$query_relation 	= array('relation' => 'AND',);
$meta_query_args 	= array_merge($query_relation, $meta_query_args);
$args['meta_query'] = $meta_query_args;
$loop = new WP_Query( $args );
?>

<div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-4">
	<div class="dc-insightsitem dc-dashboardbox">
	<figure class="dc-userlistingimg">
			<?php if( !empty($package_expiry_img) ) {?>
				<img src="<?php echo esc_url($package_expiry_img);?>" alt="<?php esc_attr_e('Pakckage expiry', 'doccure'); ?>">
			<?php } else {?>
					<span class="<?php echo esc_attr($icon);?>"></span>
			<?php }?>
		</figure>
		<ul class="dc-countersoon" data-date="<?php echo esc_attr($formatted_date);?>">
			<li>
				<div class="dc-countdowncontent">
					<p><?php esc_html_e('d', 'doccure'); ?></p> <span class="days" data-days></span>
				</div>
			</li>
			<li>
				<div class="dc-countdowncontent">
					<p><?php esc_html_e('h', 'doccure'); ?></p> <span class="hours" data-hours></span>
				</div>
			</li>
			<li>
				<div class="dc-countdowncontent">
					<p><?php esc_html_e('m', 'doccure'); ?></p> <span class="minutes" data-minutes></span>
				</div>
			</li>
			<li>
				<div class="dc-countdowncontent">
					<p><?php esc_html_e('s', 'doccure'); ?></p> <span class="seconds" data-seconds></span>
				</div>
			</li>
		</ul>
		
		<div class="dc-insightdetails">
			<div class="dc-title">
				<h3><?php esc_html_e('Your Package Ends In', 'doccure'); ?></h3>
			</div>													
		</div>	
	</div>
</div>	
<?php
	$script = "
            (function($) {
                var launch = new Date(".esc_js($formatted_date).");
                var days 	= jQuery('.days');
                var hours 	= jQuery('.hours');
                var minutes = jQuery('.minutes');
                var seconds = jQuery('.seconds');
                setDate();
                function setDate(){
                    var now = new Date();
                    if( launch < now ){
                        days.html('0');
                        hours.html('0');
                        minutes.html('0');
                        seconds.html('0');
                    }
                    else{
                        var s = -now.getTimezoneOffset()*60 + (launch.getTime() - now.getTime())/1000;
                        var d = Math.floor(s/86400);
                        days.html(d);
                        s -= d*86400;
                        var h = Math.floor(s/3600);
                        hours.html(h);
                        s -= h*3600;
                        var m = Math.floor(s/60);
                        minutes.html(m);
                        s = Math.floor(s-m*60);
                        seconds.html(s);
                        setTimeout(setDate, 1000);
                    }
                }
            })(jQuery);
        ";
    wp_add_inline_script('doccure-callback', $script, 'after');
	
	?>
	
	
<div class="col-12 col-sm-12 col-md-12 col-lg-12 float-left">
	<div class="dc-dashboardbox">
		<div class="dc-dashboardboxtitle">
			<h2><?php esc_html_e('Packages','doccure');?></h2>
		</div>
			<?php 
				if( class_exists('woocommerce') ){
					if ( $loop->have_posts() ) {?>
					<div class="dc-dashboardboxcontent dc-packages">
						<div class="dc-package dc-packagedetails">
							<div class="dc-packagehead"></div>
							<div class="dc-packagecontent">
								<ul class="dc-packageinfo">
									<li class="dc-packageprices"><span><?php esc_html_e('Price','doccure');?></span></li>
									<?php foreach ( $pakeges_features as $key => $values ) { 
										if( $values['user_type'] === $user_role || $values['user_type'] === 'common' ) {?>
											<li><span><?php echo esc_html($values['title']);?></span></li>
									<?php }}?>
								</ul>
							</div>
						</div>
						<?php 
							
							while ( $loop->have_posts() ) : $loop->the_post();
								global $product; 
								$post_id 		= intval($product->get_id());
								$duration_type	= get_post_meta($post_id,'dc_duration',true);
								$duration_title = doccure_get_duration_types($duration_type,'title'); ?>
								<div class="dc-package dc-baiscpackage">
									<div class="dc-packagehead">
										<h3><?php echo esc_html(get_the_title()); ?></h3>
										<div class="packages-desc"><?php the_content();?></div>
									</div>
									<div class="dc-packagecontent">
										<ul class="dc-packageinfo">
											<li class="dc-packageprice">
												<span>
													<sup><?php echo esc_html($currency_symbol['symbol']);?></sup><?php echo esc_html($product->get_price()); ?><sub>\ <?php echo  esc_html($duration_title);?></sub>
												</span>
											</li>
											<?php 
												if ( !empty ( $pakeges_features )) {
													foreach( $pakeges_features as $key => $vals ) {
														if( $vals['user_type'] === $user_role || $vals['user_type'] === 'common' ) {
															do_action('doccure_print_pakages_features',$key,$vals,$post_id);
														}
													}
												}
											?>
										</ul>
										<a class="dc-btn renew-package" data-key="<?php echo intval($post_id);?>" href="javascript:;"><span><?php esc_html_e('Buy Now','doccure');?></span></a>
									</div>
								</div>
							<?php
						endwhile;
						wp_reset_postdata();
						?>
					</div>	
					<?php
					} else {
						do_action('doccure_empty_records_html','',esc_html__( 'No package has been made yet.', 'doccure' ),true);
					}
				} else {
					do_action('doccure_empty_records_html','',esc_html__( 'Please install woocommerce.', 'doccure' ),true);
			}
		?>	
	</div>
</div>