<?php
global $current_user, $wp_roles, $userdata, $post, $paged, $woocommerce;
global $wpdb;

$identity       = !empty($_GET['identity']) ? $_GET['identity'] : "";
$ref            = !empty($_GET['ref']) ? $_GET['ref'] : "";

$show_posts     = get_option('posts_per_page');
$date_formate   = get_option('date_format');

$pg_page        = get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
$pg_paged       = get_query_var('paged') ? get_query_var('paged') : 1; //rewrite the global var

//paged works on single pages, page - works on homepage
$paged          = max($pg_page, $pg_paged);
$current_page   = $paged;
$price_symbol   = doccure_get_current_currency();
?>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 float-right">
    <div class="dc-dashboardbox dc-dashboardinvocies">
        <div class="dc-dashboardboxtitle dc-titlewithsearch">
            <h2><?php esc_html_e('Invoices', 'doccure'); ?></h2>
        </div>
        <div class="dc-dashboardboxcontent dc-categoriescontentholder dc-categoriesholder">
            <table class="dc-tablecategories">
                <?php
                if (class_exists('WooCommerce')) {
                    $customer_orders = wc_get_orders(apply_filters('woocommerce_my_account_my_orders_query', 
                        array( 
                            'customer'  => $current_user->ID, 
                            'page'      => $current_page, 
                            'paginate'  => true,
                            'limit'     => $show_posts,
                        ) 
                    ));
                    ?>
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Order ID', 'doccure'); ?></th>
                            <th><?php esc_html_e('Created date', 'doccure'); ?></th>
                            <th><?php esc_html_e('Amount', 'doccure'); ?></th>
                            <th><?php esc_html_e('Action', 'doccure'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    if (!empty($customer_orders->orders)) {
                        $count_post = count($customer_orders->orders); 
                        foreach ($customer_orders->orders as $customer_order) {
                            $order = wc_get_order($customer_order);
                            $data_created = $order->get_date_created();
                            $actions = wc_get_account_orders_actions($order);
                            ?>
                            <tr>
                                <td><?php echo intval($order->get_id()); ?></td>
                                <td><?php echo date($date_formate, strtotime($data_created)); ?></td>
                                <td><?php echo esc_html($price_symbol['symbol'] . $order->get_total()); ?></td>
                                <td>
                                    <div class="dc-actionbtn">
                                        <?php
                                        if (!empty($actions)) {
                                            foreach ($actions as $key => $action) {
                                                echo '<a target="_blank" href="' . esc_url( $action['url'] ) . '" class="dc-addinfo dc-skillsaddinfo' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
												?> 
												<?php
                                            }
                                        }
                                        ?>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                    </tbody>
                </table>
				

                <?php } else {
                    do_action('doccure_empty_records_html', '', esc_html('WooCommerce should be installed for payments. Please contact the administrator.', 'doccure'), true);
                } 

                if (empty($customer_orders->orders)) {
                    do_action('doccure_empty_records_html', 'dc-empty-invoices', esc_html__('No order has been made yet.', 'doccure'), true);
                }

                if (1 < $customer_orders->max_num_pages) { ?>
                    <nav class="dc-pagination woo-pagination">
                        <?php 
                        echo paginate_links(array(
                            'base'      => '%_%',
                            'format'    => '?paged=%#%',
                            'current'   => max(1, get_query_var('paged')),
                            'total'     => $customer_orders->max_num_pages,
                            'prev_text' => '<i class="fa fa-chevron-left"></i>',
                            'next_text' => '<i class="fa fa-chevron-right"></i>',
                        ));
                        ?>
                    </nav>
                <?php } ?>
        </div>
    </div>
</div>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-3 float-left">
    <?php get_template_part('directory/front-end/templates/dashboard', 'sidebar-ads'); ?>    
</div>


<?php
$script = "
     jQuery('.dc-tablecategories').basictable({
        breakpoint: 767
    });
";
wp_add_inline_script('doccure_basictable_js', $script, 'after');
?>
