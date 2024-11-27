<?php 
if (!defined('ABSPATH')) {
    exit;
}

if (isset($_GET['print-order']) && wp_verify_nonce($_REQUEST['_wpnonce'], 'woocommerce_print_order')) {
    $order_id = absint($_GET['print-order']);
    $order = wc_get_order($order_id);

    if ($order) {
        // Display order details here
        ?>
        <!DOCTYPE html>
        <html <?php language_attributes(); ?>>
        <head>
            <meta charset="<?php bloginfo('charset'); ?>">
            <title><?php esc_html_e('Print Order', 'woocommerce'); ?></title>
            <style>
                body { font-family: Arial, sans-serif; }
                .order-details { width: 100%; }
                .order-details th, .order-details td { padding: 10px; border: 1px solid #ddd; }
            </style>
        </head>
        <body>
            <h1><?php esc_html_e('Order Details', 'woocommerce'); ?></h1>
            <table class="order-details">
                <tr>
                    <th><?php esc_html_e('Product', 'woocommerce'); ?></th>
                    <th><?php esc_html_e('Quantity', 'woocommerce'); ?></th>
                    <th><?php esc_html_e('Total', 'woocommerce'); ?></th>
                </tr>
                <?php
                foreach ($order->get_items() as $item_id => $item) {
                    $product = $item->get_product();
                    ?>
                    <tr>
                        <td><?php echo esc_html($product->get_name()); ?></td>
                        <td><?php echo esc_html($item->get_quantity()); ?></td>
                        <td><?php echo esc_html($order->get_formatted_line_subtotal($item)); ?></td>
                    </tr>
                    <?php
                }
                ?>
            </table>
            
        </body>
        </html>
        <?php
        exit;
    }
}
?>

 