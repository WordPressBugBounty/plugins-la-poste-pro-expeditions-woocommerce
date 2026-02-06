<?php
/**
 * Contains code for cart util class.
 *
 * @package     LaPoste\LaPosteProExpeditionsWoocommerce\Util
 */

namespace LaPoste\LaPosteProExpeditionsWoocommerce\Util;

/**
 * Cart util class.
 *
 * Helper to manage cart.
 */
class Cart_Util {

	/**
	 * Get a cart package's weight.
	 *
	 * @param mixed $package current package (or full cart)
	 * @return float
	 */
	public static function get_weight( $package = null ) {
		$package = $package === null ? WC()->cart->get_cart() : $package;
		$weight  = 0;
		foreach ( $package as $item ) {
			if ( $item['data']->needs_shipping() ) {
				$variation_id   = $item['variation_id'];
				$product_id     = ( '0' !== $variation_id && 0 !== $variation_id ) ? $variation_id : $item['product_id'];
				$product_weight = Product_Util::get_product_weight( $product_id );
				if ( false === $product_weight ) {
					$product_weight = 0;
				}
				$weight += $product_weight * $item['quantity'];
			}
		}
		return $weight;
	}
}
