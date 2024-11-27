<?php
/**
 * Quick view content.
 *
 * @author  YITH
 * @package YITH WooCommerce Quick View
 * @version 1.0.0
 */

/**
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

while ( have_posts() ) :
	the_post();
	?>
	<div id="product-<?php the_ID(); ?>" <?php post_class( 'product' ); ?>>

    <div class="row">
      <div class="col-lg-6 col-md-5">

        <?php do_action( 'yith_wcqv_product_image' ); ?>

      </div>

      <div class="col-lg-6 col-md-7">

        <div class="doccure_product-single-content summary entry-summary">
      		<?php do_action( 'yith_wcqv_product_summary' ); ?>
      	</div>

      </div>

    </div>

	</div>

	<?php
endwhile; // end of the loop.
