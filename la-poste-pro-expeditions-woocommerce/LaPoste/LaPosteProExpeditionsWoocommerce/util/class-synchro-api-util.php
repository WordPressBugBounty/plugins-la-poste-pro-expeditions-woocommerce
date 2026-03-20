<?php
/**
 * Contains code for shipping method util class.
 *
 * @package     LaPoste\LaPosteProExpeditionsWoocommerce\Util
 */

namespace LaPoste\LaPosteProExpeditionsWoocommerce\Util;

use LaPoste\LaPosteProExpeditionsWoocommerce\Util\Order_Util;
use LaPoste\LaPosteProExpeditionsWoocommerce\Util\Configuration_Report_Util;

/**
 * Shipping method util class.
 *
 * Helper to manage shipping methods.
 */
class Synchro_Api_Util {

	/**
	 * Default page size.
	 *
	 * @var number
	 */
	private static $default_page_size = 50;

	/**
	 * Get an order synchronization response.
	 *
	 * Expected parameters :
	 * - updatedBefore
	 * - updatedAfter
	 * - createdBefore
	 * - createdAfter
	 * - lastCursor
	 * - pageSize
	 *
	 * @param \StdClass $params list of synchronization request parameters.
	 *
	 * @return array
	 */
	public static function get_order_synchronization_response( $params ) {

		$created_after  = property_exists( $params, 'createdAfter' ) ? self::format_synchro_date( $params->createdAfter ) : null;
		$updated_after  = property_exists( $params, 'updatedAfter' ) ? self::format_synchro_date( $params->updatedAfter ) : null;
		$created_before = property_exists( $params, 'createdBefore' ) ? self::format_synchro_date( $params->createdBefore ) : null;
		$updated_before = property_exists( $params, 'updatedBefore' ) ? self::format_synchro_date( $params->updatedBefore ) : null;
		$last_cursor    = property_exists( $params, 'lastCursor' ) ? $params->lastCursor : null;
		$page_size      = property_exists( $params, 'pageSize' ) ? $params->pageSize : self::$default_page_size;
		$page           = 0;

		if ( null !== $last_cursor ) {
			$parsed_cursor = self::parse_cursor( $last_cursor );

			if ( count( $parsed_cursor ) === 6 ) {
				$created_after  = empty( $parsed_cursor[0] ) ? null : $parsed_cursor[0];
				$updated_after  = empty( $parsed_cursor[1] ) ? null : $parsed_cursor[1];
				$created_before = empty( $parsed_cursor[2] ) ? null : $parsed_cursor[2];
				$updated_before = empty( $parsed_cursor[3] ) ? null : $parsed_cursor[3];
				$page_size      = intval( $parsed_cursor[4], 10 );
				$page           = intval( $parsed_cursor[5], 10 );
			}
		}

		$orders_params = array(
			'type'     => 'shop_order',
			'paginate' => true,
			'paged'    => $page + 1,
			'limit'    => $page_size,
			'orderby'  => 'modified',
			'order'    => 'ASC',
			'status'   => self::get_synchronizable_status()
		);

		$created_filter = self::get_date_range_filter( $created_after, $created_before );
		if ( null !== $created_filter ) {
			$orders_params['date_created'] = $created_filter;
		}
		$modified_filter = self::get_date_range_filter( $updated_after, $updated_before );
		if ( null !== $modified_filter ) {
			$orders_params['date_modified'] = $modified_filter;
		}

		$paged_orders = wc_get_orders( $orders_params );

		$content = array();
		foreach ( $paged_orders->orders as $order ) {
			$content[] = self::format_order( $order );
		}

		return self::build_paged_response(
			$content,
			self::get_order_synchronization_page_info( $paged_orders->total, $created_after, $updated_after, $created_before, $updated_before, $page, $page_size )
		);
	}

	/**
	 * Return plugin's state response
	 *
	 * @return array
	 */
	public static function get_plugin_state_response() {
		return Configuration_Report_Util::get_light_configuration_report();
	}

	/**
	 * Return deleted orders response
	 *
	 * Expected parameters :
	 * - deletedAfter
	 * - lastCursor
	 * - pageSize
	 *
	 * @param \StdClass $params list of deleted orders request parameters.
	 *
	 * @return array
	 */
	public static function get_deleted_orders_response( $params ) {
		$deleted_orders = Configuration_Util::get_deleted_orders();
		$deleted_after  = property_exists( $params, 'deletedAfter' ) ? self::format_synchro_date( $params->deletedAfter ) : null;
		$last_cursor    = property_exists( $params, 'lastCursor' ) ? $params->lastCursor : null;
		$page_size      = property_exists( $params, 'pageSize' ) ? $params->pageSize : self::$default_page_size;
		$page           = 0;
		$result         = array();

		$deleted_orders = Misc_Util::remove_old_deleted_orders( $deleted_orders );
		Configuration_Util::set_deleted_orders( $deleted_orders );

		if ( null !== $last_cursor ) {
			$parsed_cursor = self::parse_cursor( $last_cursor );

			if ( count( $parsed_cursor ) === 3 ) {
				$deleted_after = empty( $parsed_cursor[0] ) ? null : $parsed_cursor[0];
				$page_size     = intval( $parsed_cursor[1], 10 );
				$page          = intval( $parsed_cursor[2], 10 );
			}
		}

		usort(
			$deleted_orders,
			function ( $a, $b ) {
				return Misc_Util::compare( strtotime( $a['date'] ), strtotime( $b['date'] ) );
			}
		);

		$page_deleted_orders = array_slice( $deleted_orders, $page * $page_size, $page_size );

		foreach ( $page_deleted_orders as $deleted_order ) {
			$deleted_date = self::format_synchro_date( $deleted_order['date'] );

			if ( null === $deleted_after || strtotime( $deleted_after ) < strtotime( $deleted_date ) ) {
				$result[] = array(
					'technicalReference' => $deleted_order['id'],
					'deletedAt'          => $deleted_date
				);
			}
		}

		return self::build_paged_response(
			$result,
			self::get_deleted_orders_page_info( count( $deleted_orders ), $deleted_after, $page, $page_size )
		);
	}

	/**
	 * Return the list of synchroniable status.
	 *
	 * @return array
	 */
	private static function get_synchronizable_status() {
		global $wp_post_statuses;
		$statuses            = array();
		$unauthorized_status = array( 'wc-checkout-draft' );

		foreach ( wc_get_order_statuses() as $order_status => $translation ) {
			if ( ! in_array( $order_status, $unauthorized_status, true ) ) {
				$statuses[] = $order_status;
			}
		}

		$trash_status = 'trash';
		if ( isset( $wp_post_statuses ) && isset( $wp_post_statuses[ $trash_status ] ) && ! in_array( $trash_status, $statuses, true ) ) {
			$statuses[] = $trash_status;
		}

		return $statuses;
	}

	/**
	 * Return a date range condition from 2 dates.
	 *
	 * @param string|null $min min date.
	 * @param string|null $max max date.
	 * @return string|null
	 */
	private static function get_date_range_filter( $min, $max ) {
		$result = null;

		if ( null !== $max || null !== $min ) {
			if ( null === $max ) {
				$result = '>=' . self::get_timestamp( $min );
			} elseif ( null === $min ) {
				$result = '<=' . self::get_timestamp( $max );
			} else {
				$result = self::get_timestamp( $min ) . '...' . self::get_timestamp( $max );
			}
		}

		return $result;
	}

	/**
	 * Return a date timestamp.
	 *
	 * @param string $date date.
	 * @return int
	 */
	private static function get_timestamp( $date ) {
		return ( new \DateTime( $date ) )->getTimestamp();
	}

	/**
	 * Get page info for an orders synchronization response.
	 *
	 * @param number      $count orders count.
	 * @param string|null $created_after min creation date.
	 * @param string|null $updated_after min update date.
	 * @param string|null $created_before max creation date.
	 * @param string|null $updated_before max update date.
	 * @param number      $page current page.
	 * @param number      $page_size page size.
	 *
	 * @return array
	 */
	private static function get_order_synchronization_page_info( $count, $created_after, $updated_after, $created_before, $updated_before, $page, $page_size ) {
		if ( null === $page ) {
			$page = 0;
		}

		$next_page      = $page + 1;
		$has_next_page  = $count > ( $next_page * $page_size );
		$created_after  = $created_after ? $created_after : '';
		$updated_after  = $updated_after ? $updated_after : '';
		$created_before = $created_before ? $created_before : '';
		$updated_before = $updated_before ? $updated_before : '';

		return self::build_page_info(
			$has_next_page,
			self::get_cursor(
				array( $created_after, $updated_after, $created_before, $updated_before, $page_size, 0 )
			),
			$has_next_page
				? self::get_cursor(
					array(
						$created_after,
						$updated_after,
						$created_before,
						$updated_before,
						$page_size,
						$next_page
					)
				)
				: null
		);
	}

	/**
	 * Get page info for a deleted orders response.
	 *
	 * @param number      $count orders count.
	 * @param string|null $deleted_after min deletion date.
	 * @param number      $page current page.
	 * @param number      $page_size page size.
	 *
	 * @return array
	 */
	private static function get_deleted_orders_page_info( $count, $deleted_after, $page, $page_size ) {
		if ( null === $page ) {
			$page = 0;
		}

		$next_page     = $page + 1;
		$has_next_page = $count > ( $next_page * $page_size );

		$deleted_after = $deleted_after ? $deleted_after : '';

		return self::build_page_info(
			$has_next_page,
			self::get_cursor( array( $deleted_after, $page_size, 0 ) ),
			$has_next_page
				? self::get_cursor( array( $deleted_after, $page_size, $next_page ) )
				: null
		);
	}

	/**
	 * Get request cursor.
	 *
	 * @param string[] $values cursor values.
	 *
	 * @return string
	 */
	private static function get_cursor( $values ) {
		// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
		return base64_encode( implode( '.', $values ) );
	}

	/**
	 * Parse synchronization request cursor.
	 *
	 * @param string $cursor synchronization cursor.
	 *
	 * @return string[]
	 */
	private static function parse_cursor( $cursor ) {
		// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode
		return explode( '.', base64_decode( $cursor ) );
	}

	/**
	 * Build a paged response
	 *
	 * @param array $content response content.
	 * @param array $page_info response page info.
	 *
	 * @return array
	 */
	private static function build_paged_response( $content, $page_info ) {
		return array(
			'content'  => $content,
			'pageInfo' => $page_info
		);
	}

	/**
	 * Build a paged response
	 *
	 * @param bool   $has_next_page has next page.
	 * @param string $start_cursor paged response start cursor.
	 * @param string $end_cursor paged response end cursor.
	 *
	 * @return array
	 */
	private static function build_page_info( $has_next_page, $start_cursor, $end_cursor ) {
		return array(
			'hasNextPage' => $has_next_page,
			'startCursor' => $start_cursor,
			'endCursor'   => $end_cursor
		);
	}

	/**
	 * Get an order shipping method.
	 *
	 * @param \WC_Order $order order.
	 *
	 * @return array|null
	 */
	private static function get_shipping_method( $order ) {
		$result = null;

		$shipping_method_id    = Order_Util::get_shipping_method_id( $order );
		$shipping_method_label = Order_Util::get_shipping_method_label( $order );
		if ( '' !== $shipping_method_id ) {
			$result = self::format_code_translations( $shipping_method_id, $shipping_method_label );
		}

		return $result;
	}

	/**
	 * Get an order status
	 *
	 * @param \WC_Order $order order.
	 *
	 * @return array
	 */
	private static function get_status( $order ) {
		global $wp_post_statuses;
		$all_status = wc_get_order_statuses();
		$status     = Order_Util::get_status( $order );

		$label = '';

		if ( isset( $all_status[ 'wc-' . $status ] ) ) {
			$label = $all_status[ 'wc-' . $status ];
		} elseif ( isset( $wp_post_statuses ) && isset( $wp_post_statuses[ $status ] ) ) {
			$label = $wp_post_statuses[ $status ]->label;
		}

		return self::format_code_translations( $status, $label );
	}

	/**
	 * Format an order's shipping address into synchronization request format.
	 *
	 * @param \WC_Order $order order.
	 *
	 * @return array|null
	 */
	private static function get_order_shipping_address( $order ) {
		$result = null;

		if ( Order_Util::has_shipping_address( $order ) ) {
			$company = Misc_Util::not_empty_or_null( Order_Util::get_shipping_company( $order ) );
			$result  = array(
				'type'                  => null === $company ? 'RESIDENTIAL' : 'BUSINESS',
				'contact'               => array(
					'firstName' => Misc_Util::not_empty_or_null( Order_Util::get_shipping_first_name( $order ) ),
					'lastName'  => Misc_Util::not_empty_or_null( Order_Util::get_shipping_last_name( $order ) ),
					'company'   => $company,
					'phone'     => Misc_Util::not_empty_or_null( Order_Util::get_shipping_phone( $order ) )
				),
				'location'              => array(
					'addressLine1' => Misc_Util::not_empty_or_null( Order_Util::get_shipping_address_1( $order ) ),
					'addressLine2' => Misc_Util::not_empty_or_null( Order_Util::get_shipping_address_2( $order ) ),
					'city'         => Misc_Util::not_empty_or_null( Order_Util::get_shipping_city( $order ) ),
					'postalCode'   => Misc_Util::not_empty_or_null( Order_Util::get_shipping_postcode( $order ) ),
					'country'      => Misc_Util::not_empty_or_null( Order_Util::get_shipping_country( $order ) )
				),
				'additionalInformation' => Misc_Util::not_empty_or_null( Order_Util::get_customer_note( $order ) )
			);
		}

		return $result;
	}

	/**
	 * Format an order's billing address into synchronization request format.
	 *
	 * @param \WC_Order $order order.
	 *
	 * @return array|null
	 */
	private static function get_order_billing_address( $order ) {
		$result = null;

		if ( Order_Util::has_billing_address( $order ) ) {
			$company = Misc_Util::not_empty_or_null( Order_Util::get_billing_company( $order ) );
			$result  = array(
				'type'     => null === $company ? 'RESIDENTIAL' : 'BUSINESS',
				'contact'  => array(
					'firstName' => Misc_Util::not_empty_or_null( Order_Util::get_billing_first_name( $order ) ),
					'lastName'  => Misc_Util::not_empty_or_null( Order_Util::get_billing_last_name( $order ) ),
					'company'   => $company,
					'phone'     => Misc_Util::not_empty_or_null( Order_Util::get_billing_phone( $order ) ),
					'email'     => Misc_Util::not_empty_or_null( Order_Util::get_billing_email( $order ) )
				),
				'location' => array(
					'addressLine1' => Misc_Util::not_empty_or_null( Order_Util::get_billing_address_1( $order ) ),
					'addressLine2' => Misc_Util::not_empty_or_null( Order_Util::get_billing_address_2( $order ) ),
					'city'         => Misc_Util::not_empty_or_null( Order_Util::get_billing_city( $order ) ),
					'postalCode'   => Misc_Util::not_empty_or_null( Order_Util::get_billing_postcode( $order ) ),
					'country'      => Misc_Util::not_empty_or_null( Order_Util::get_billing_country( $order ) )
				)
			);
		}

		return $result;
	}

	/**
	 * Get an order parcelpoint choice.
	 *
	 * @param \WC_Order $order order.
	 *
	 * @return array
	 */
	private static function get_parcelpoint_choice( $order ) {
		$parcelpoint = Order_Util::get_parcelpoint( $order );
		return null === $parcelpoint ? null : array(
			'network' => $parcelpoint->network,
			'code'    => $parcelpoint->code
		);
	}

	/**
	 * Get an order items.
	 *
	 * @param \WC_Order $order order.
	 *
	 * @return array
	 */
	private static function get_items( $order ) {
		$items = array();
		foreach ( $order->get_items( 'line_item' ) as $item ) {
			if ( is_object( $item ) ) {
				$items[] = self::format_item( $order, $item );
			}
		}

		return $items;
	}

	/**
	 * Get an order amounts.
	 *
	 * @param \WC_Order $order order.
	 *
	 * @return array
	 */
	private static function get_amounts( $order ) {
		return array(
			// Total discount amount for the order. Montant du coupon HT, doit être interprété négativement.
			'discountTotal'            => Order_Util::get_total_discount( $order ),
			// Total discount tax amount for the order. Total TVA sur le coupon.
			'discountTax'              => Order_Util::get_discount_tax( $order ),
			// Total fees amount for the order. Montant du frais HT.
			'feesTotal'                => Order_Util::get_total_fees( $order ),
			// Total shipping amount for the order. Total shipping HT.
			'shippingTotal'            => Order_Util::get_shipping_total( $order ),
			// Total shipping tax amount for the order. Total TVA sur le shipping.
			'shippingTax'              => Order_Util::get_shipping_tax( $order ),
			// Order subtotal. Order subtotal is the price of all items excluding taxes, fees, shipping cost, and coupon discounts. Montant HT des produits.
			'subtotal'                 => Order_Util::get_subtotal( $order ),
			// Sum of line item taxes only. Total TVA sur les line items.
			'cartTax'                  => Order_Util::get_cart_tax( $order ),
			// Grand total. Montant total TTC. Méthode get_total de WC_Order.
			'total'                    => Order_Util::get_total( $order ),
			// Sum of all taxes. Montant total TVA. Méthode get_total_tax de WC_Order.
			'totalTax'                 => Order_Util::get_total_tax( $order ),
			// Total refunded for the order. Montant remboursé TTC.
			'refundedTotal'            => Order_Util::get_total_refunded( $order ),
			// Total shipping refunded for the order. Montant du shipping remboursé HT.
			'totalShippingRefunded'    => Order_Util::get_total_shipping_refunded( $order ),
			// Total shipping tax refunded for the order. Montant de la TAV du shipping remboursée.
			'totalShippingTaxRefunded' => Order_Util::get_total_shipping_tax_refunded( $order ),
			// Tax refunded for the order. Montant de TVA remboursé. Méthode get_total_tax_refunded de WC_Order.
			'totalTaxRefunded'         => Order_Util::get_total_tax_refunded( $order )
		);
	}

	/**
	 * Format an order item into synchronization request format.
	 *
	 * @param \WC_Order      $order order.
	 * @param \WC_Order_Item $item woocommerce order item.
	 *
	 * @return array
	 */
	private static function format_item( $order, $item ) {
		$variation_id = $item['variation_id'];
		$product_id   = ( '0' !== $variation_id && 0 !== $variation_id ) ? $variation_id : $item['product_id'];
		$weight       = Product_Util::get_product_weight( $product_id );

		return array(
			'technicalReference' => $product_id,
			'sku'                => Product_Util::get_sku( $product_id ),
			'gtin'               => Product_Util::get_global_unique_id( $product_id ),
			'quantity'           => (int) $item['qty'],
			'refundedQuantity'   => Order_Util::get_quantity_refunded_for_item( $order, $item ),
			'unitWeight'         => array(
				'unit'  => Product_Util::$weight_unit,
				'value' => false !== $weight ? (float) $weight : null
			),
			'name'               => self::format_translations( esc_html( Product_Util::get_product_description( $item ) ) ),
			'virtual'            => Product_Util::is_product_virtual( $product_id ),
			'amounts'            => array(
				// Line subtotal (before discounts). Prix total (item x quantité) HT d'un produit, hors discount.
				'subtotal'      => Order_Util::get_order_item_subtotal( $item ),
				// Line total (after discounts). Prix total (item x quantité) HT d'un produit, discount inclus.
				'total'         => Order_Util::get_order_item_total( $item ),
				// Refunded amount for a line item. Prix remboursé HT pour toutes les quantités d'un produit.
				'totalRefunded' => Order_Util::get_order_item_total_refunded( $order, $item ),
				// Product price. Prix unitaire HT hors discount, hors remboursement d'un line item.
				'unitPrice'     => Order_Util::get_order_item_unit_price( $order, $item )
			)
		);
	}

	/**
	 * Format an order into synchronization request format.
	 *
	 * @param \WC_Order $order woocommerce order object.
	 *
	 * @return array
	 */
	private static function format_order( $order ) {
		return array(
			'technicalReference' => Order_Util::get_id( $order ),
			'reference'          => self::get_order_reference( $order ),
			'status'             => self::get_status( $order ),
			'isPaid'             => Order_Util::is_order_paid( $order ),
			'shippingMethod'     => self::get_shipping_method( $order ),
			'parcelPointChoice'  => self::get_parcelpoint_choice( $order ),
			'shippingAddress'    => self::get_order_shipping_address( $order ),
			'billingAddress'     => self::get_order_billing_address( $order ),
			'items'              => self::get_items( $order ),
			'currency'           => $order->get_currency(),
			'amounts'            => self::get_amounts( $order ),
			'createdAt'          => self::format_synchro_date( Order_Util::get_date_created( $order ) ),
			'updatedAt'          => self::format_synchro_date( Order_Util::get_date_modified( $order ) )
		);
	}

	/**
	 * Format code and translated text into synchronization request format.
	 *
	 * @param string $code code for translated text.
	 * @param string $translation translated text in current locale.
	 *
	 * @return array
	 */
	private static function format_code_translations( $code, $translation ) {
		return array(
			'code'         => $code,
			'translations' => self::format_translations( $translation )
		);
	}

	/**
	 * Format translated text into synchronization request format.
	 *
	 * @param string $translation translated text in current locale.
	 *
	 * @return array
	 */
	private static function format_translations( $translation ) {
		return array(
			str_replace( '_', '-', get_locale() ) => $translation
		);
	}

	/**
	 * Format date into synchronization request format.
	 *
	 * @param string|\WC_DateTime|null $date date in string format.
	 *
	 * @return string|null
	 */
	private static function format_synchro_date( $date ) {
		$result = null;

		if ( null !== $date ) {
			$result = Misc_Util::date_iso8601_format( $date );
		}

		return $result;
	}

	/**
	 * Get an order reference to display
	 *
	 * @param \WC_Order $order woocommerce order object.
	 *
	 * @return string
	 */
	private static function get_order_reference( $order ) {
		return '#' . Order_Util::get_order_number( $order );
	}
}
