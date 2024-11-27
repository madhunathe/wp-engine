<?php
/**
 *
 * The template used for displaying hodpital post style
 *
 * @package   doccure
 * @version 1.0
 * @since 1.0
 */

get_header();
$section_width     	= 'col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-9';

if(  is_active_sidebar( 'doctor-sidebar-right' ) ){ 
	$section_width     	= 'col-12 col-lg-12 col-xl-9';
} else {
	$section_width     	= 'col-12 col-lg-12 col-xl-9';
}

while ( have_posts() ) {
the_post();
global $post;
?>
<div class="dc-haslayout dc-parent-section">
	<div class="container">
		<div class="row">
			<div id="dc-twocolumns" class="dc-twocolumns dc-haslayout">
				<div class="<?php echo esc_attr($section_width);?> float-left">
					<?php get_template_part('directory/front-end/templates/hospitals/single/basic'); ?>
					<div class="dc-docsingle-holder dc-hospsingle-holder">
						<ul class="dc-navdocsingletab nav navbar-nav">
							<li class="nav-item">
								<a data-toggle="tab" href="#locations"><?php esc_html_e('Onboard Doctors','doccure');?></a>
							</li>
							<li class="nav-item">
								<a class="active" id="userdetails-tab" data-toggle="tab" href="#userdetails"><?php esc_html_e('Hospital Details','doccure');?></a>
							</li>
						</ul>
						<div class="tab-content dc-haslayout">
							<?php get_template_part('directory/front-end/templates/hospitals/single/onboarddoctors'); ?>
							<?php get_template_part('directory/front-end/templates/hospitals/single/userdetails'); ?>
						</div>
					</div>
				</div>
				
					<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-3 float-left">
						<aside id="dc-sidebar" class="dc-sidebar dc-sidebar-grid float-left mt-xl-0">
							<?php
								get_template_part('directory/front-end/templates/location-sidebar');
								if(  is_active_sidebar( 'doctor-sidebar-right' ) ){ 
									dynamic_sidebar( 'doctor-sidebar-right' );
								}
							?>
						</aside>
					</div>
				
			</div>
		</div>
	</div>
</div>
<?php }
get_footer(); 
