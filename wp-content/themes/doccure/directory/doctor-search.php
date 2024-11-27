<?php
/**
 *
 * Template Name: Search Page
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link     https://dreamstechnologies.com/
 * @since 1.0
 */
get_header();
global $paged,$doccure_options,$wpdb,$post;

$hide_location		= !empty($doccure_options['hide_location']) ? $doccure_options['hide_location'] : 'no';
 $gender_search		= !empty($doccure_options['gender_search']) ? $doccure_options['gender_search'] : '';


$post_meta			= !empty($post->ID) ? doccure_get_post_meta( $post->ID ) : '';
$am_layout			= !empty( $post_meta['am_layout'] ) ? $post_meta['am_layout'] : '';
$am_sidebar			= !empty( $post_meta['am_left_sidebar'] ) ? $post_meta['am_left_sidebar'] : '';
$current_position	= 'full';
$sidebar_enabled	= 'dc-disabled';
if (!empty($am_layout) && $am_layout === 'right_sidebar') {
    $aside_class   		= 'order-last';
    $content_class 		= 'order-first';
	$section_width     	= 'col-xs-12 col-sm-12 col-md-7 col-lg-8 col-xl-9';
	$sidebar_enabled	= 'dc-enabled';
} elseif (!empty($am_layout) && $am_layout === 'left_sidebar') {
    $aside_class   		= 'order-first';
    $content_class 		= 'order-last';
	$section_width     	= 'col-xs-12 col-sm-12 col-md-7 col-lg-8 col-xl-9';
	$sidebar_enabled	= 'dc-enabled';
}else{
	$aside_class   		= 'order-first';
    $content_class 		= 'order-last';
	$section_width     	= 'col-12';
	$am_sidebar			= '';
}

if ( !empty( $am_sidebar ) && !is_active_sidebar( $am_sidebar ) ) {
	$section_width     	= 'col-12 full-width-wrapper';
}

$order_fields 	= array();
$tax_query_args = array();
$order_fields	= doccure_list_order_by();

$pg_page    = get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
$pg_paged   = get_query_var('paged') ? get_query_var('paged') : 1; //rewrite the global var
$paged 		= max($pg_page, $pg_paged);
$show_posts = get_option('posts_per_page') ? get_option('posts_per_page') : 10;

$search_option		= !empty($doccure_options['search_type']) ? $doccure_options['search_type'] : '';
$add_settings		= !empty( $doccure_options['add_settings'] ) ? $doccure_options['add_settings'] : '';
$show_add			= !empty( $doccure_options['show_add'] ) ? $doccure_options['show_add'] : '';
$search_type		= !empty($doccure_options['search_type']) ? $doccure_options['search_type'] : '';
$add_code			= !empty( $doccure_options['add_code'] ) && !empty( $doccure_options['add_settings'] ) ? $doccure_options['add_code'] : '';
$search_settings	= !empty( $doccure_options['search_form'] ) ? $doccure_options['search_form'] : '';
 $search_page		= doccure_get_search_page_uri('doctors');
$show_add_before	= '';

if( !empty( $show_add ) && $show_add === 'top' ){
	$show_add_before	= 1;
} else if( !empty( $show_add ) && $show_add === 'middle' ){
	$show_add_before	= round($show_posts/2);
}else if( !empty( $show_add ) && $show_add === 'bottom' ){
	$show_add_before	= $show_posts;
}

$searchby	= array('doctors','hospitals');
if( empty( $_GET['searchby'] ) || $_GET['searchby'] === 'both' ){
	$searchby	= !empty($search_option) && $search_option !='both' ? $search_option : array('doctors','hospitals');
} else {
	$searchby	= $_GET['searchby'];
}

$keyword 		= !empty( $_GET['keyword']) ? $_GET['keyword'] : '';
$locations 	 	= !empty( $_GET['location']) ? $_GET['location'] : '';
$specialities 	= !empty( $_GET['specialities']) ? array($_GET['specialities']) : array();
$services 	 	= !empty( $_GET['services']) ? array($_GET['services']) : array();
$orderby 		= !empty( $_GET['orderby']) ? $_GET['orderby'] : '';
$gender			= !empty($_GET['gender']) ? $_GET['gender'] : '';
$order 			= !empty( $_GET['order']) ? $_GET['order'] : 'ASC';

$terms_services		= array();
$meta_query_args	= array();
$terms_specialities	= array();

//locations term search
if (is_tax('locations')) {
	$locations = $wp_query->get_queried_object();
	$locations = !empty($locations->slug) ? $locations->slug : '';
}

//specialities term search
if (is_tax('specialities')) {
	$specialities = $wp_query->get_queried_object();
	$specialities = !empty($specialities->slug) ? $specialities->slug : array();
}

//services term search
if (is_tax('services')) {
	$services = $wp_query->get_queried_object();
	$services = !empty($services->slug) ? $services->slug : array();
}


if( !empty($keyword) ){
	$args = array(
		'taxonomy'      => array( 'specialities' ), 
		'orderby'       => 'id', 
		'order'         => 'ASC',
		'hide_empty'    => true,
		'fields'        => 'all',
		'name__like'    => $keyword
	);
	
	$terms_specialities 			= get_terms( $args );
	if( !empty($terms_specialities) ){
		foreach($terms_specialities as $specility_term){
			$specialities[]	= $specility_term->slug;
		}
	}

	$args = array(
		'taxonomy'      => array( 'services' ), 
		'orderby'       => 'id', 
		'order'         => 'ASC',
		'hide_empty'    => true,
		'fields'        => 'all',
		'name__like'    => $keyword
	);
	
	$terms_services 			= get_terms( $args );
	if( !empty($terms_services) ){
		foreach($terms_services as $specility_term){
			$services[]	= $specility_term->slug;
		}
	}

}

//Locations
if ( !empty($locations) ) {    
	$query_relation = array('relation' => 'OR',);
	$location_args 	= array(
		'taxonomy' => 'locations',
		'field'    => 'slug',
		'terms'    => $locations,
	);

	$tax_query_args[] = array_merge($query_relation, $location_args);
}


//Specialities
if ( !empty($specialities) ) {    
	$query_relation = array('relation' => 'OR',);
	$specialities_args 	= array(
		'taxonomy' => 'specialities',
		'field'    => 'slug',
		'terms'    => $specialities,
	);

	$tax_query_args[] = array_merge($query_relation, $specialities_args);

	
}

//services
if ( !empty($services) ) {    
	$query_relation = array('relation' => 'OR',);
	$services_args 	= array(
		'taxonomy' => 'services',
		'field'    => 'slug',
		'terms'    => $services,
	);

	$tax_query_args[] = array_merge($query_relation, $services_args);
	
}



$query_args = array(
    'posts_per_page'      => $show_posts,
    'paged'			      => $paged,
    'post_type' 	      => $searchby,
    'post_status'	 	  => 'publish',
    'ignore_sticky_posts' => 1
);

$query_args['meta_key'] = 'is_featured';

if( !empty( $orderby ) ){
	$query_args['orderby']  	= $orderby;
} else {
	$query_args['orderby']	 = array( 
		'meta_value' 	=> 'DESC', 
		'ID'      		=> 'DESC',
	); 
} 
 

if( !empty( $order ) ){
	$query_args['order'] 		= $order;
	if( $order === 'DESC') {
		$slected_order	= 'selected="selected"';
	} else {
		$slected_order 	= '';
	}
}

//keyword search
if( !empty($keyword) && empty($terms_specialities) && empty($terms_services)){
	$query_args['s']	=  $keyword;
	
}

//default
$meta_query_args[] = array(
	'key' 			=> '_profile_blocked',
	'value' 		=> 'off',
	'compare' 		=> '='
);

//serch only verified
$meta_query_args[] = array(
	'key' 			=> '_is_verified',
	'value' 		=> 'yes',
	'compare' 		=> '='
);

//by gender
if(  !empty($gender) && $gender !== 'all' ){
	$meta_query_args[] = array(
		'key' 			=> 'am_gender',
		'value' 		=> $gender,
		'compare' 		=> '='
	);
}

//Taxonomy Query
if ( !empty( $tax_query_args ) ) {
	$query_relation = array('relation' => 'AND',);
	$query_args['tax_query'] = array_merge($query_relation, $tax_query_args);    
}

//Meta Query
if (!empty($meta_query_args)) {
    $query_relation = array('relation' => 'AND',);
    $meta_query_args = array_merge($query_relation, $meta_query_args);
    $query_args['meta_query'] = $meta_query_args;
}

$doctors_data 	= new WP_Query($query_args);
$total_posts   	= $doctors_data->found_posts;

if( have_posts() && is_page() ) {
	while ( have_posts() ) : the_post();
	the_content();
	wp_link_pages( array(
		'before'      => '<div class="dc-paginationvtwo"><nav class="dc-pagination"><ul>',
		'after'       => '</ul></nav></div>',
		) );
	endwhile;
	wp_reset_postdata();
}

?>
<div class="content">
	<div class="container">
		<div class="row">
			<div class="col-md-4 col-lg-4 col-xl-3">
				<div class="search_block">
			<div class="search-header">
				<h4 class="search-title mb-0">Search Filter</h4>
			</div>
			<?php 	if( !empty($search_settings) ){?>
			<div class="sidebar_search dc-innerbanner-holder dc-haslayout dc-open dc-opensearchs">
				<form action="<?php echo $search_page; ?>" method="get" id="search_form">
							<div class="dc-innerbanner">
									<div class="dc-formtheme dc-form-advancedsearch">
										<fieldset>
											<div class="form-group">
												<?php do_action('doccure_get_search_text_field');?>
											</div>
											<?php if( !empty($hide_location) && $hide_location === 'no'){?>
												<div class="form-group">
												<h4>Location</h4>
												<div class="dc-select">
														<?php do_action('doccure_get_search_locations');?>
													</div>
												</div>
											<?php }?>
											<?php if( !empty($gender_search) ){?>
													<div class="form-group">
														
													<h4>Gender</h4>
													<div class="dc-select">
															<?php do_action('doccure_get_search_gender');?>
														</div>
													</div>
												<?php } ?>
												<div class="form-group">
												<h4>Specialist</h4>
												<div class="dc-select">
														<?php do_action('doccure_get_search_speciality');?>
													</div>
												</div>
												

											<div class="dc-btnarea">
												<input type="submit" class="dc-btn" value="<?php esc_attr_e('Search','doccure');?>">
											</div>
										</fieldset>
									</div>
									<a href="javascript:;" class="dc-docsearch"><span class="dc-advanceicon"><i></i> <i></i> <i></i></span><span><?php echo wp_kses(__('Advanced <br> Search','doccure'),array(
										'br' => array()
									));?></span></a>
								</div>
							
				</form>
			</div>
			<?php
			}?>
						
		</div>				
		</div>

			<div class="col-md-8 col-lg-8 col-xl-9">
			<div class="dc-haslayout dc-parent-section">
	<div class="container">
		<div class="row">
			<div id="dc-twocolumns" class="dc-twocolumns dc-haslayout d-flex <?php echo esc_attr($sidebar_enabled); ?>">
			 
				<div class="<?php echo esc_attr($section_width); ?> <?php echo sanitize_html_class($content_class); ?>">
					<div class="dc-searchresult-holder">
						<div class="dc-searchresult-head">
							<div class="dc-title"><h4><strong><?php echo intval( $total_posts );?></strong>&nbsp;<?php esc_html_e('matches found','doccure');?> </h4></div>
							<?php if( !empty( $order_fields ) && !empty($search_settings) ){?>
								<div class="dc-rightarea">
									<div class="dc-select">
										<select name="orderby" class="orderby">
											<option value=""><?php esc_html_e('Sort By','doccure');?></option>
											<?php 
											foreach( $order_fields as $key => $order_field ){
												if( !empty( $orderby ) && $orderby === $key ){
													$slected_orderby	= 'selected="selected"';
												} else {
													$slected_orderby	= '';
												}
												?>
												<option <?php echo do_shortcode( $slected_orderby );?> value="<?php echo esc_attr( $key );?>"><?php echo esc_html( $order_field );?></option>
											<?php } ?>
										</select>
									</div>
									<div class="dc-select">
										<select name="order" class="order">
											<option value="ASC"><?php esc_html_e('Ascending','doccure');?></option>
											<option value="DESC" <?php echo do_shortcode( $slected_order );?>><?php esc_html_e('Descending','doccure');?></option>
										</select>
									</div>
								</div>
							<?php } ?>
						</div>
						<div class="dc-searchresult-grid dc-searchresult-list dc-searchvlistvtwo">
							<?php
								if ($doctors_data->have_posts()) {
									$counter	= 0;
									while ($doctors_data->have_posts()) { 
										$doctors_data->the_post();
										global $post;
										$counter ++;
										$post_type	= get_post_type($post->ID);

 										if( !empty( $add_settings ) && $counter == $show_add_before && $show_add == 'top' ) { ?>
											<figure class="dc-searchresultad">
												<?php echo do_shortcode($add_code);?>
											</figure>
										<?php }
										
										if( $post_type === 'doctors') {
											get_template_part('directory/front-end/templates/doctors/doctors-listing');	
										} else if( $post_type === 'hospitals') {
											get_template_part('directory/front-end/templates/hospitals/hospitals-listing');
										}
										
										if( !empty( $add_settings ) && $counter == $show_add_before && ( $show_add == 'bottom' || $show_add == 'middle' ) ) { ?>
											<figure class="dc-searchresultad">
												<?php echo do_shortcode(nl2br($add_code));?>
											</figure>
										<?php
										}
									} 
									
									wp_reset_postdata(); 
								} else {
									do_action('doccure_empty_records_html','dc-empty-hospital-location',esc_html__( 'No result found.', 'doccure' ));
								}
							
								if ( !empty($total_posts) && $total_posts > $show_posts ) {
									doccure_prepare_pagination($total_posts, $show_posts ); 
	                        	}
							?>
						</div>
					</div>
				</div>
				<?php if ( !empty( $am_sidebar ) && is_active_sidebar( $am_sidebar ) ) {?>
					<div class="col-12 col-md-5 col-lg-4 col-xl-3 <?php echo sanitize_html_class($aside_class); ?>">
						<aside id="dc-sidebar" class="dc-sidebar dc-sidebar-grid float-left mt-md-0 mt-lg-0 mt-xl-0">
							<?php dynamic_sidebar( $am_sidebar );?>
						</aside>
					</div>
				<?php }?>
			</div>
		</div>
	</div>
</div>

			</div>
		</div>
	
	</div>
	
</div>


<?php get_footer();