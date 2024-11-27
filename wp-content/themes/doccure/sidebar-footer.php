<?php

/**
 * The footer sidebar containing the widget area for footer.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package doccure
 */


global $post;


$footer_columns_classes = array('col-xl-12 col-lg-12 col-md-12 col-sm-12');


/**
 * Filters footer widget columns.
 *
 * @param string $widget_column Name of widget column option.
 *
 * @visible true
 */

$footer_layout = doccure_get_option('footer_layout', '');
if ($footer_layout) {

    if ('6-6' === $footer_layout) {

        $footer_columns_classes = array('col-xl-6 col-lg-6 col-md-6 col-sm-12', 'col-xl-6 col-lg-6 col-md-6 col-sm-12');

    } elseif ('8-4' === $footer_layout) {

        $footer_columns_classes = array('col-xl-8 col-lg-8 col-md-6 col-sm-12', 'col-xl-4 col-lg-4 col-md-6 col-sm-12');

    } elseif ('4-8' === $footer_layout) {

        $footer_columns_classes = array('col-xl-4 col-lg-4 col-md-6 col-sm-12', 'col-xl-8 col-lg-8 col-md-6 col-sm-12');

    } elseif ('4-4-4' === $footer_layout) {

        $footer_columns_classes = array('col-xl-4 col-lg-4 col-md-6 col-sm-12', 'col-xl-4 col-lg-4 col-md-6 col-sm-12', 'col-xl-4 col-lg-4 col-md-6 col-sm-12');

    } elseif ('3-3-3-3' === $footer_layout) {

        $footer_columns_classes = array('col-xl-3 col-lg-3 col-md-6 col-sm-12', 'col-xl-3 col-lg-3 col-md-6 col-sm-12', 'col-xl-3 col-lg-3 col-md-6 col-sm-12', 'col-xl-3 col-lg-3 col-md-6 col-sm-12');

    } elseif ('6-3-3' === $footer_layout) {

        $footer_columns_classes = array('col-xl-6 col-lg-6 col-md-4 col-sm-12', 'col-xl-3 col-lg-3 col-md-4 col-sm-12', 'col-xl-3 col-lg-3 col-md-4 col-sm-12');

    } elseif ('3-3-6' === $footer_layout) {

        $footer_columns_classes = array('col-xl-3 col-lg-3 col-md-4 col-sm-12', 'col-xl-3 col-lg-3 col-md-4 col-sm-12', 'col-xl-6 col-lg-6 col-md-4 col-sm-12');

    } elseif ('8-2-2' === $footer_layout) {

        $footer_columns_classes = array('col-xl-8 col-lg-6 col-md-4 col-sm-12', 'col-xl-2 col-lg-3 col-md-4 col-sm-12', 'col-xl-2 col-lg-3 col-md-4 col-sm-12');

    } elseif ('2-2-8' === $footer_layout) {

        $footer_columns_classes = array('col-xl-2 col-lg-3 col-md-4 col-sm-12', 'col-xl-2 col-lg-3 col-md-4 col-sm-12', 'col-xl-8 col-lg-6 col-md-4 col-sm-12');

    } elseif ('6-2-2-2' === $footer_layout) {

        $footer_columns_classes = array('col-xl-6 col-lg-6 col-md-12 col-sm-12', 'col-xl-2 col-lg-2 col-md-4 col-sm-12', 'col-xl-2 col-lg-2 col-md-4 col-sm-12', 'col-xl-2 col-lg-2 col-md-4 col-sm-12');

    } elseif ('2-2-2-6' === $footer_layout) {

        $footer_columns_classes = array('col-xl-2 col-lg-2 col-md-4 col-sm-12', 'col-xl-2 col-lg-2 col-md-4 col-sm-12', 'col-xl-2 col-lg-2 col-md-4 col-sm-12', 'col-xl-6 col-lg-6 col-md-12 col-sm-12');

    } elseif ('2-2-4-4' === $footer_layout) {

        $footer_columns_classes = array('col-xl-2 col-lg-2 col-md-6 col-sm-12', 'col-xl-2 col-lg-2 col-md-6 col-sm-12', 'col-xl-4 col-lg-4 col-md-6 col-sm-12', 'col-xl-4 col-lg-4 col-md-6 col-sm-12');

    }

} else {
    $footer_columns_classes = array('col-xl-3 col-lg-3 col-md-6 col-sm-12', 'col-xl-3 col-lg-3 col-md-6 col-sm-12', 'col-xl-3 col-lg-3 col-md-6 col-sm-12', 'col-xl-3 col-lg-3 col-md-6 col-sm-12');
}


$col_count = 0;

$sidebar_active = false;

foreach ($footer_columns_classes as $columns_class) {

    $col_count++;

    if (is_active_sidebar('footer-column-' . $col_count)) {

        $sidebar_active = true;

    }

}


if (!$sidebar_active) {

    return;

}

?>

    <div class="doccure-footer-widgets-wrapper">

        <div class="container">

            <div class="doccure-footer row">

                <?php

                $col_count = 0;

                foreach ($footer_columns_classes as $columns_class) {

                    $col_count++;

                    ?>

                    <div class="<?php echo esc_attr($columns_class); ?>">

                        <?php dynamic_sidebar('footer-column-' . $col_count); ?>

                    </div>

                    <?php

                }
                ?>

            </div>

        </div>

    </div>

<?php

