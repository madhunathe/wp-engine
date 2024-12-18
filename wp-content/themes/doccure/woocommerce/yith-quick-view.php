<?php
/**
 * Quick view bone.
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

?>

<div id="yith-quick-view-modal">
	<div class="yith-quick-view-overlay"></div>
	<div class="yith-wcqv-wrapper">
		<div class="yith-wcqv-main">
			<div class="yith-wcqv-head">
				<a href="#" id="yith-quick-view-close" class="close-btn close-dark">
          <span></span>
          <span></span>
        </a>
			</div>
			<div id="yith-quick-view-content" class="woocommerce single-product"></div>
		</div>
	</div>
</div>
<?php
