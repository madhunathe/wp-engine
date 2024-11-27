<?php
/**
 * Template part for header social info.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package doccure
 */
$social_infos = doccure_get_option('social_infos');
if (!$social_infos) {
    return;
}
?>
<div class="doccure_header-top-contacts">
    <ul class="doccure_header-top-nav social-info">
        <?php
        foreach ($social_infos as $social_info) {
            if (!empty(doccure_get_option($social_info . '_link'))) {
                ?>
                <li>
                    <a class="social-icon" href="<?php echo esc_url(doccure_get_option($social_info . '_link')); ?>"
                       rel="nofollow"><i class="fab fa-<?php echo esc_attr($social_info); ?>"></i></a>
                </li>
                <?php
            }
        }
        ?>
    </ul>
</div>
