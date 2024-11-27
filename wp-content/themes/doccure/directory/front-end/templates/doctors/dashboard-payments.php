<?php
/**
 *
 * The template part for payments
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link     https://dreamstechnologies.com/
 * @since 1.0
 */
global $current_user,$wpdb;
$user_identity 	 	= $current_user->ID;
$linked_profile  	= doccure_get_linked_profile_id($user_identity);
$post_id 		 	= $linked_profile;

$table_name 		= $wpdb->prefix . "dc_payouts_history";
$earning_sql		= "SELECT * FROM $table_name where ( user_id =".$user_identity." And status= 'completed')";
$total_query 		= "SELECT COUNT(1) FROM (${earning_sql}) AS combined_table";
$total 				= $wpdb->get_var( $total_query );
$items_per_page 	= get_option('posts_per_page');
$page 				= isset( $_GET['epage'] ) ? abs( (int) $_GET['epage'] ) : 1;
$offset 			= ( $page * $items_per_page ) - $items_per_page;
$payments 			= $wpdb->get_results( $earning_sql . " ORDER BY id DESC LIMIT ${offset}, ${items_per_page}" );
$total_pages		= ceil($total / $items_per_page);
$date_formate		= get_option('date_format');
$payrols_list		= doccure_get_payouts_lists();
?>
<div class="dc-userexperience dc-followcompomy">
	<div class="dc-tabscontenttitle dc-addnew">
		<h3><?php esc_html_e('Your Payments','doccure');?></h3>
	</div>
	<div class="dc-dashboardboxcontent dc-categoriescontentholder dc-categoriesholder dc-emptydata-holder">
		<?php if( !empty($payments) ) {?>
			<table class="dc-tablecategories">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Date', 'doccure' ); ?></th>
						<th><?php esc_html_e( 'Amount', 'doccure' ); ?></th>
						<th><?php esc_html_e( 'Payment Method', 'doccure' ); ?></th>
						<th><?php esc_html_e( 'Status', 'doccure' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach( $payments as $payment ) {
						$payment_mode	= !empty( $payment->payment_method ) ? $payment->payment_method : 'paypal';
						$payrol_title 	= !empty( $payrols_list[$payment_mode]['title'] ) ? $payrols_list[$payment_mode]['title'] : '';
						$paymentdetails	= '';
						$bank_detail	= !empty( $payment->payment_details ) ? maybe_unserialize($payment->payment_details) : '';
						?>
						<tr>
							<td><?php echo esc_html($payment->processed_date);?></td>
							<td><?php doccure_price_format($payment->amount);?></td>
							<td><?php echo esc_html($payrol_title);?></td>
							<td><?php echo esc_html($payment->status);?></td>
						</tr>
					<?php }?>
				</tbody>
			</table>
			<?php if( !empty( $total_pages ) && $total_pages > 0 ) { ?>
				<nav class="dc-pagination woo-pagination">
					<?php 
						echo paginate_links( array(
							'base' 		=> add_query_arg( 'epage', '%#%' ),
							'format' 	=> '',
							'prev_text' => '<i class="fa fa-chevron-left"></i>',
							'next_text' => '<i class="fa fa-chevron-right"></i>',
							'total' 	=> $total_pages,
							'current' 	=> $page
						));
					?>
				</nav>
				<?php } ?>
			<?php 
			} else {
				do_action('doccure_empty_records_html','dc-empty-payouts',esc_html__( 'No payments found yet.', 'doccure' ));
			} ?>
	</div>									
</div>