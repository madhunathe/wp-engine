<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package doccure
 */
/**
 * Get the initials of a string.
 *
 * @since 2.0.0
 */
function doccure_get_initials($str)
{
    if (empty($str)) return;
    preg_match_all('/(?<=\s|^)[a-z]/i', $str, $matches);
    $result = implode('', $matches[0]);
    $result = strtoupper($result);
    return $result;
}

/**
 * Single Post pagination
 *
 * @since 2.0.0
 */
if (!function_exists('doccure_single_post_pagination')) {
    function doccure_single_post_pagination($with_content = true)
    {
        $pagination_style = doccure_get_option('blog_details_pagination_style', 'style-1');
        $previous = get_previous_post();
        $next = get_next_post();
        if ($next || $previous) {
          if($pagination_style == 'style-2') { ?>
            <div class="doccure_single-pagination style-2">
              <?php if ($previous) { ?>
                <div class="doccure_single-pagination-item doccure_single-pagination-prev">
                  <a href="<?php echo esc_url(get_the_permalink($previous)) ?>">
                    <div class="doccure_single-pagination-content">
                      <span><?php esc_html_e('Prev Post', 'doccure'); ?></span>
                      <h4><?php echo get_the_title($previous) ?></h4>
                    </div>
                  </a>
                </div>
              <?php } ?>
                <div class="grid-block">
                  <span></span>
                  <span></span>
                  <span></span>
                  <span></span>
                  <span></span>
                  <span></span>
                  <span></span>
                  <span></span>
                  <span></span>
                </div>
                <div class="doccure_single-pagination-item doccure_single-pagination-next">
                  <a href="<?php echo esc_url(get_the_permalink($next)) ?>">
                    <div class="doccure_single-pagination-content">
                      <span><?php esc_html_e('Next Post', 'doccure'); ?></span>
                      <h4><?php echo get_the_title($next) ?></h4>
                    </div>
                  </a>
                </div>
              </div>
          <?php } elseif($pagination_style == 'style-3') { ?>
            <div class="pagination-style-3-wrapper">
              <div class="doccure_single-pagination style-3">
                <a href="<?php echo esc_url(get_the_permalink($previous)) ?>" class="doccure_single-pagination-item doccure_single-pagination-prev">
                  <i class="fal fa-arrow-left"></i>
                </a>
                <div class="pagination-breadcrumb">
                  <span></span>
                  <span></span>
                  <span></span>
                </div>
                <a href="<?php echo esc_url(get_the_permalink($next)) ?>" class="doccure_single-pagination-item doccure_single-pagination-next">
                  <i class="fal fa-arrow-right"></i>
                </a>
              </div>
            </div>
          <?php } else { ?>
            <div class="doccure_single-pagination style-1">
                <div class="doccure_single-pagination-item doccure_single-pagination-prev">
                    <?php if ($previous) { ?>
                        <a title="<?php echo esc_attr(get_the_title($previous)) ?>"
                           href="<?php echo esc_url(get_the_permalink($previous)) ?>">
                            <?php if (has_post_thumbnail($previous)) { ?>
                                <div class="doccure_single-pagination-thumb">
                                    <?php echo get_the_post_thumbnail($previous, 'thumbnail'); ?>
                                </div>
                            <?php } ?>
                            <div class="doccure_single-pagination-content">
                                <span><?php esc_html_e('Prev Post', 'doccure'); ?></span>
                                <h6><?php echo get_the_title($previous) ?></h6>
                            </div>
                            <i class="far fa-chevron-left"></i>
                        </a>
                    <?php } ?>
                </div>
                <div class="doccure_single-pagination-item doccure_single-pagination-next">
                    <a title="<?php echo esc_attr(get_the_title($next)) ?>"
                       href="<?php echo esc_url(get_the_permalink($next)) ?>">
                        <?php if (has_post_thumbnail($next)) { ?>
                            <div class="doccure_single-pagination-thumb">
                                <?php echo get_the_post_thumbnail($next, 'thumbnail'); ?>
                            </div>
                        <?php } ?>
                        <div class="doccure_single-pagination-content">
                            <span><?php esc_html_e('Next Post', 'doccure'); ?></span>
                            <h6><?php echo get_the_title($next) ?></h6>
                        </div>
                        <i class="far fa-chevron-right"></i>
                    </a>
                </div>
            </div>
          <?php }
        }
    }
}
