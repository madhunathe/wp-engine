<?php
/**
 *
 * The template part for displaying saved hospitals
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link     https://dreamstechnologies.com/
 * @since 1.0
 */
global $current_user,$doccure_options;
$user_identity 	 = $current_user->ID;
$linked_profile  = doccure_get_linked_profile_id($user_identity);
$post_id 		 = $linked_profile;

$add_settings		= !empty( $doccure_options['add_settings'] ) ? $doccure_options['add_settings'] : '';
$show_add			= !empty( $doccure_options['show_add'] ) ? $doccure_options['show_add'] : '';
$add_code			= !empty( $doccure_options['add_code'] ) && !empty( $doccure_options['add_settings'] ) ? $doccure_options['add_code'] : '';
$show_add_before	= '';

if (!empty($_GET['identity'])) {
    $url_identity = $_GET['identity'];
}

$show_posts 	= get_option('posts_per_page') ? get_option('posts_per_page') : 10;
$pg_page 		= get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
$pg_paged 		= get_query_var('paged') ? get_query_var('paged') : 1; //rewrite the global var
$paged 			= max($pg_page, $pg_paged);
$order 			= 'DESC';
$sorting 		= 'ID';

$save_hosptials_ids	= get_post_meta( $post_id, '_saved_hospitals', true);
$post_array_ids		= !empty($save_hosptials_ids) ? $save_hosptials_ids : array(0);

$args = array(
	'posts_per_page' 	=> $show_posts,
    'post_type' 		=> 'hospitals',
    'orderby' 			=> $sorting,
    'order' 			=> $order,
    'paged' 			=> $paged,
	'post__in' 			=> $post_array_ids,
    'suppress_filters' 	=> false
);

$query = new WP_Query($args);
$count_post = $query->found_posts;
?>
<div class="dc-hospsettinghold tab-pane active fade show" id="dc-security">
	<div class="dc-hospsettings dc-tabsinfo">
		<div class="dc-tabscontenttitle dc-titlewithicon">
			<h3><?php esc_html_e('Saved hosptials','doccure');?></h3>
			<?php if( $query->have_posts() ) { ?>
				<a href="javascript:;" data-post-id="<?php echo intval($post_id);?>" data-itme-type="_saved_hospitals" class="dc-clicksave dc-clickremoveall">
					<i class="fa fa-close"></i>
					<?php esc_html_e('Remove all saved hosptials','doccure');?>
				</a>
			<?php } ?>
		</div>
		<div class="dc-docsettingscontent dc-sidepadding">
			<div class="dc-searchresult-grid dc-searchresult-list dc-searchvlistvtwo">
				<?php
					if ($query->have_posts()) {
						while ($query->have_posts()) { 
							$query->the_post();
							global $post;
							get_template_part('directory/front-end/templates/hospitals/hospitals-listing-saved');	
						} 
						
						wp_reset_postdata(); 
					} else {
						do_action('doccure_empty_records_html','dc-empty-saved-hospitals',esc_html__( 'No saved hospitals or location yet.', 'doccure' ));
					} 

					if ( !empty($total_posts) && $total_posts > $show_posts ) {
						doccure_prepare_pagination($total_posts, $show_posts ); 
					}
				?>
			</div>
		</div>
	</div>
</div>