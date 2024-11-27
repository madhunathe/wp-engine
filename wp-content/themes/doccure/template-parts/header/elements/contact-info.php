<?php

/**
 * Template part for header contact information.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package doccure
 */

$contact_email = doccure_get_option('contact_email');
$contact_phone = doccure_get_option('contact_phone');
$contact_address = doccure_get_option('contact_address');

$display_top_email_address = doccure_get_option('display_top_email_address');
$display_top_phone_number = doccure_get_option('display_top_phone_number');
$display_top_work_address = doccure_get_option('display_top_work_address');

$contact_info_style = doccure_get_option('header_contact_info_style');
if ($contact_info_style == 'style-1') {
    if ($display_top_email_address || $display_top_phone_number || $display_top_work_address) {
        ?>

        <div class="doccure_header-top-contacts <?php echo esc_attr($contact_info_style); ?>">
            <ul class="doccure_header-top-nav">
                <?php if (isset($contact_phone) && !empty($contact_phone) && $display_top_phone_number) { ?>
                    <li><a title="Call Us"
                           href="<?php echo esc_attr('tel:' . str_replace(' ', '', $contact_phone)); ?>"><i class="fa-solid fa-phone"></i></a></li>
                <?php }
                if (isset($contact_email) && !empty($contact_email) && $display_top_email_address) { ?>
                    <li><a title="Email Us" href="<?php echo esc_attr('mailto:' . $contact_email); ?>"><i
                                    class="far fa-envelope"></i></a></li>
                <?php }
                if (isset($contact_address) && !empty($contact_address) && $display_top_work_address) { ?>
                    <li class="top_location_info"><i
                                class="feather-map-pin"></i> <?php echo esc_html($contact_address); ?> </li>
                <?php } ?>
            </ul>
        </div>
    <?php }
} elseif ($contact_info_style == 'style-2') { ?>
    <div class="doccure_header-top-contacts <?php echo esc_attr($contact_info_style); ?>">
        <ul class="doccure_header-top-nav">
            <?php if (isset($contact_phone) && !empty($contact_phone) && $display_top_phone_number) { ?>
                <li><a title="Call Us" href="<?php echo esc_attr('tel:' . str_replace(' ', '', $contact_phone)); ?>"><i class="fa-solid fa-phone"></i> <?php echo esc_html($contact_phone); ?></a></li>
            <?php }
            if (isset($contact_email) && !empty($contact_email) && $display_top_email_address) { ?>
                <li><a title="Email Us" href="<?php echo esc_attr('mailto:' . $contact_email); ?>"><i
                                class="far fa-envelope"></i> <?php echo esc_html($contact_email); ?></a></li>
            <?php }
            if (isset($contact_address) && !empty($contact_address) && $display_top_work_address) { ?>
                <li class="top_location_info"><i
                            class="feather-map-pin"></i> <?php echo esc_html($contact_address); ?> </li>
            <?php } ?>
        </ul>
    </div>
<?php }
