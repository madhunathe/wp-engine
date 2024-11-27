<?php
/**
 * Template part for header call to action information.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package doccure
 */
$cta_info_title_1 = doccure_get_option('cta_info_title_1');
$cta_info_subtitle_1 = doccure_get_option('cta_info_subtitle_1');
$cta_info_link_1 = doccure_get_option('cta_info_link_1');
$cta_info_icon_1 = doccure_get_option('cta_info_icon_1');
$cta_info_title_2 = doccure_get_option('cta_info_title_2');
$cta_info_subtitle_2 = doccure_get_option('cta_info_subtitle_2');
$cta_info_link_2 = doccure_get_option('cta_info_link_2');
$cta_info_icon_2 = doccure_get_option('cta_info_icon_2');
$cta_info_title_3 = doccure_get_option('cta_info_title_3');
$cta_info_subtitle_3 = doccure_get_option('cta_info_subtitle_3');
$cta_info_link_3 = doccure_get_option('cta_info_link_3');
$cta_info_icon_3 = doccure_get_option('cta_info_icon_3');
if (!empty($cta_info_title_1) || !empty($cta_info_subtitle_1) || !empty($cta_info_icon_1) || !empty($cta_info_title_2) || !empty($cta_info_subtitle_2) || !empty($cta_info_icon_2) || !empty($cta_info_title_3) || !empty($cta_info_subtitle_3) || !empty($cta_info_icon_3)) {
    ?>
    <div class="doccure_header-top-links important-links">
        <ul class="doccure_header-top-nav cta-info">
            <?php if (doccure_get_option('display-cta-btn-1') == '1') {
                if (!empty($cta_info_title_1) || !empty($cta_info_subtitle_1) || !empty($cta_info_icon_1)) {
                    ?>
                    <li>
                        <a href="<?php echo esc_url($cta_info_link_1); ?>">
                            <?php if (!empty($cta_info_icon_1)) { ?>
                                <i class="<?php echo esc_attr($cta_info_icon_1); ?>"></i>
                            <?php } ?>
                            <div>
                                <?php if (isset($cta_info_subtitle_1) && !empty($cta_info_subtitle_1)) { ?>
                                    <p><?php echo esc_html($cta_info_subtitle_1); ?></p>
                                <?php }
                                if (isset($cta_info_title_1) && !empty($cta_info_title_1)) { ?>
                                    <p><b><?php echo esc_html($cta_info_title_1); ?></b></p>
                                <?php } ?>
                            </div>
                        </a>
                    </li>
                <?php }
            }
            if (doccure_get_option('display-cta-btn-2') == '1') {
                if (!empty($cta_info_title_2) || !empty($cta_info_subtitle_2) || !empty($cta_info_icon_2)) {
                    ?>
                    <li>
                        <a href="<?php echo esc_url($cta_info_link_2); ?>">
                            <?php if (!empty($cta_info_icon_2)) { ?>
                                <i class="<?php echo esc_attr($cta_info_icon_2); ?>"></i>
                            <?php } ?>
                            <div>
                                <?php if (isset($cta_info_subtitle_2) && !empty($cta_info_subtitle_2)) { ?>
                                    <p><?php echo esc_html($cta_info_subtitle_2); ?></p>
                                <?php }
                                if (isset($cta_info_title_2) && !empty($cta_info_title_2)) { ?>
                                    <p><b><?php echo esc_html($cta_info_title_2); ?></b></p>
                                <?php } ?>
                            </div>
                        </a>
                    </li>
                <?php }
            }
            if (doccure_get_option('display-cta-btn-3') == '1') {
                if (!empty($cta_info_title_3) || !empty($cta_info_subtitle_3) || !empty($cta_info_icon_3)) {
                    ?>
                    <li>
                        <a href="<?php echo esc_url($cta_info_link_3); ?>">
                            <?php if (!empty($cta_info_icon_3)) { ?>
                                <i class="<?php echo esc_attr($cta_info_icon_3); ?>"></i>
                            <?php } ?>
                            <div>
                                <?php if (isset($cta_info_subtitle_3) && !empty($cta_info_subtitle_3)) { ?>
                                    <p><?php echo esc_html($cta_info_subtitle_3); ?></p>
                                <?php }
                                if (isset($cta_info_title_3) && !empty($cta_info_title_3)) { ?>
                                    <p><b><?php echo esc_html($cta_info_title_3); ?></b></p>
                                <?php } ?>
                            </div>
                        </a>
                    </li>
                <?php }
            } ?>
        </ul>
    </div>
<?php }
