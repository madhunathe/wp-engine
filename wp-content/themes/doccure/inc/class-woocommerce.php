<?php

/**
 * @Woocommerce Customization
 * return {}
 */
if (!class_exists('doccure_woocommerace')) {

    class doccure_woocommerace {

        function __construct() {
            add_action('woocommerce_process_product_meta', array(&$this, 'doccure_save_package_meta'));
			add_action( 'doccure_woocommerce_add_to_cart_button', array(&$this,'doccure_woocommerce_add_to_cart_button'), 10 );
			add_action( 'woocommerce_checkout_fields', array( &$this, 'doccure_custom_checkout_update_customer' ), 10);
			add_action( 'woocommerce_product_query', array( &$this, 'doccure_pre_get_product_query') );
			add_filter( 'woocommerce_cart_needs_shipping_address', '__return_false');
			add_action( 'wp_ajax_add_to_cart_variable_rc', array(&$this,'doccure_add_to_cart_variable_rc') );
			add_filter('woocommerce_add_to_cart_fragments', array(&$this,'doccure_woocommerce_header_add_to_cart')); // Ajax Add To cart
        }
		
		/**
		 * @Add to cart via ajax
		 * @return {}
		 */
		public function doccure_woocommerce_header_add_to_cart( $fragments ) {
			global $woocommerce;
			
			ob_start();
			?>
            <a href="javascript:;" id="dc-cart" class="cart-contents">
                <i class="fa fa-cart-plus"></i>
                <span class="dc-badge"><?php echo intval($woocommerce->cart->cart_contents_count); ?></span>
            </a>
			<?php 
			$fragments['a.cart-contents'] = ob_get_clean();
			
			return $fragments;
		}
		
		/**
		 * @add to cart
		 * @return {}
		 */
		public function doccure_add_to_cart_variable_rc() {
			global $woocommerce;
			$product_id 	= apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['product_id'] ) );
			$quantity 		= empty( $_POST['quantity'] ) ? 1 : apply_filters( 'woocommerce_stock_amount', $_POST['quantity'] );
			$variation_id   = 0;
			$variation  	= array();
			
			$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );

			if ( $passed_validation && WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation  ) ) {
				do_action( 'woocommerce_ajax_added_to_cart', $product_id );
				if ( get_option( 'woocommerce_cart_redirect_after_add' ) == 'yes' ) {
					wc_add_to_cart_message( $product_id );
				}
				$this->doccure_get_fragments();
				
			} else {
			$data = array(
				'error' => 'true',
				'message' => esc_html__('Some error occur,please try again later.','doccure'),
				'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id )
				);
				echo json_encode( $data );
			}
			die();
		} 
		
		/**
		 * @refresh fregments for multiple products rendering
		 * @return {}
		 */
		public function doccure_get_fragments() {
			global $woocommerce;
			ob_start();
			woocommerce_mini_cart();
			$mini_cart = ob_get_clean();

			$data = array(
				'fragments' => apply_filters( 'woocommerce_add_to_cart_fragments', array(
						'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content"><div class="dc-haslayout dc-shopcontent-wrap">' . $mini_cart . '</div></div>'
					)
				),
				'cart_hash' => apply_filters( 'woocommerce_add_to_cart_hash', WC()->cart->get_cart_for_session() ? md5( json_encode( WC()->cart->get_cart_for_session() ) ) : '', WC()->cart->get_cart_for_session() )
			);
			
			$data['message']	.= esc_html__('Cart updated','doccure');
			if ( sizeof( $woocommerce->cart->cart_contents) > 0 ) :
				$data['cart']	.= '<a class="added_to_cart wc-forward" href="' . esc_url( $woocommerce->cart->get_checkout_url()) . '" title="' . esc_attr__( 'Checkout','doccure' ) . '">' . esc_html__( 'Checkout','doccure' ) . '</a>';
			endif;

			wp_send_json( $data );
		}
		
		/**
		 * @remove packages from shop
		 * @return {}
		 */
		function doccure_pre_get_product_query( $q ) {
			  //get current loop query
			   $taxonomy_query = $q->get('tax_query') ;

			   //appends the grouped products condition
			   $taxonomy_query['relation'] = 'AND';
			   $taxonomy_query[] = array(
					   'taxonomy' => 'product_type',
					   'field' => 'slug',
					   'terms' => 'packages',
				   	   'operator'	=> 'NOT IN'
			   );

			   $q->set( 'tax_query', $taxonomy_query );
		}

		/**
		 * @Checkout First and last name 
		 * @return {}
		 */
		public function doccure_custom_checkout_update_customer( $fields ){
			$user = wp_get_current_user();
			$first_name = $user ? $user->user_firstname : '';
			$last_name = $user ? $user->user_lastname : '';
			$fields['billing']['billing_first_name']['default'] = $first_name;
			$fields['billing']['billing_last_name']['default']  = $last_name;
			return $fields;
		}

		/**
		 * @Add to cart button
		 * @return {}
		 */
		public function doccure_woocommerce_add_to_cart_button(){
			global $product;
			echo apply_filters( 'woocommerce_loop_add_to_cart_link',
				sprintf( '<a href="%s" data-product_id="%s" data-product_sku="%s" data-quantity="%s" class="%s product_type_%s ajax_add_to_cart  dc-btnaddtocart"><i class="lnr lnr-cart"></i><i class="fa fa-spinner fa-spin" aria-hidden="true"></i></a>',
					esc_url( $product->add_to_cart_url() ),
					esc_html( $product->get_id() ),
					esc_html( $product->get_sku() ),
					esc_html( isset( $quantity ) ? $quantity : 1 ),
					$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
					esc_html( $product->get_type() ),
					esc_html( $product->add_to_cart_text() )
				),
			$product );
		}

        /**
         * @Package Meta save
         * return {}
         */
        public function doccure_save_package_meta($post_id) {
			update_post_meta($post_id, 'package_type', sanitize_text_field($_POST['package_type']));
			$pakeges_features = doccure_get_pakages_features();
			if ( !empty ( $pakeges_features )) {
				foreach( $pakeges_features as $key => $vals ) {
					update_post_meta($post_id, $key, sanitize_text_field($_POST[$key]));
				}
			}        
		}

    }

    new doccure_woocommerace();
}