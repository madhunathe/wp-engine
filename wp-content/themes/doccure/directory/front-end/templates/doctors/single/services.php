<?php
/**
 *
 * The template used for displaying doctors services
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link      https://themeforest.net/user/dreamstechnologies/portfolio
 * @version 1.0
 * @since 1.0
 */
 
 global $post,$doccure_options;
$post_id 			= $post->ID;
$am_specialities	= doccure_get_post_meta( $post_id,'am_specialities');
if( !empty( $am_specialities ) ) {
    //  show services according to doctor package 
    $hide_service_accord_package	= !empty( $doccure_options['hide_services_by_package'] ) ? $doccure_options['hide_services_by_package'] : 'yes';
    $author_id                      = get_post_field ('post_author', $post_id);
    $no_service_in_package          = doccure_get_subscription_metadata('dc_services',$author_id);
    ?>
<div class="dc-services-holder dc-aboutinfo offered_services_single">
 
	<div class="dc-infotitle">
		<h3><?php esc_html_e('Offered Services','doccure');?></h3>
	</div>
	<div class="accordion" id="dc-accordion">
    <?php
    foreach ($am_specialities as $key => $specialities) {
        $specialities_title = doccure_get_term_name($key, 'specialities');
        $logo = get_term_meta($key, 'logo', true);
        $logo = !empty($logo['url']) ? $logo['url'] : '';
        $services = !empty($specialities) ? $specialities : '';
        $service_count = !empty($services) ? count($services) : 0;
        $accordion_id = 'accordion-' . $key; // Unique ID for each accordion
    ?>
        <div class="accordion-item">
            <?php if (!empty($specialities_title)) { ?>
                <h2 class="accordion-header" id="heading-<?php echo esc_attr($key); ?>">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo esc_attr($accordion_id); ?>" aria-expanded="false" aria-controls="<?php echo esc_attr($accordion_id); ?>">
                     
                        <span><?php echo esc_html($specialities_title); ?></span>
                        <?php if (!empty($service_count)) {
                            if ($hide_service_accord_package === 'yes' && ($service_count > $no_service_in_package)) { ?>
                                <em class="ms-2"><?php echo intval($no_service_in_package); ?>&nbsp;<?php esc_html_e('Service(s)', 'doccure'); ?></em>
                            <?php } else { ?>
                                <em class="ms-2"><?php echo intval($service_count); ?>&nbsp;<?php esc_html_e('Service', 'doccure'); ?></em>
                            <?php } 
                        } ?>
                    </button>
                </h2>
            <?php } ?>

            <div id="<?php echo esc_attr($accordion_id); ?>" class="accordion-collapse collapse" aria-labelledby="heading-<?php echo esc_attr($key); ?>" data-bs-parent="#dc-accordion">
                <div class="accordion-body">
                    <?php if (!empty($services)) { ?>
                        <ul class="list-unstyled mb-0">
                            <?php
                            $count = 0;
                            foreach ($services as $service_key => $service) {
                                if ($hide_service_accord_package === 'yes') {
                                    $count++;
                                    if ($count > $no_service_in_package) {
                                        break;
                                    }
                                }
                                $service_title = doccure_get_term_name($service_key, 'services');
                                $service_price = !empty($service['price']) ? $service['price'] : '';
                                $description = !empty($service['description']) ? $service['description'] : '';
                            ?>
                                <li class="mb-0">
                                    <?php if (!empty($service_title)) { ?>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span><?php echo esc_html($service_title); ?></span>
                                            <?php if (!empty($service_price)) { ?>
                                                <em><?php doccure_price_format($service_price); ?></em>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                    <?php if (!empty($description)) { ?>
                                        <p class="mt-2 mb-0"><?php echo nl2br($description); ?></p>
                                    <?php } ?>
                                </li>
                            <?php } ?>
                        </ul>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

</div>
<?php } ?>
