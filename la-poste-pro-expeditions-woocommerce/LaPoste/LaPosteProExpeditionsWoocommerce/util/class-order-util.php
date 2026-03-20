<?php
/**
 * Contains code for order util class.
 *
 * @package     LaPoste\LaPosteProExpeditionsWoocommerce\Util
 */

namespace LaPoste\LaPosteProExpeditionsWoocommerce\Util;

/**
 * Order util class.
 *
 * Helper to manage consistency between woocommerce versions order getters and setters.
 */
class Order_Util {

	/**
	 * Get id of WC order.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return string $id order id
	 */
	public static function get_id( $order ) {
		if ( method_exists( $order, 'get_id' ) ) {
			return $order->get_id();
		}
		return $order->id;
	}

	/**
	 * Get id of WC order.
	 *
	 * @param \WC_Order_Item $item woocommerce order item.
	 * @return string $id order item id
	 */
	public static function get_order_item_id( $item ) {
		if ( method_exists( $item, 'get_id' ) ) {
			return $item->get_id();
		}
		return $item->id;
	}

	/**
	 * Get order number (display) of WC order.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return string order number
	 */
	public static function get_order_number( $order ) {
		if ( method_exists( $order, 'get_order_number' ) ) {
			return $order->get_order_number();
		}
		return $order->order_number;
	}

	/**
	 * Does an order have a shipping address.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return boolean
	 */
	public static function has_shipping_address( $order ) {

		return method_exists( $order, 'has_shipping_address' ) && $order->has_shipping_address();
	}

	/**
	 * Does an order have a billing address.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return boolean
	 */
	public static function has_billing_address( $order ) {

		return method_exists( $order, 'has_billing_address' ) && $order->has_billing_address();
	}

	/**
	 * Get shipping first name of WC order.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return string $firstname order shipping first name
	 */
	public static function get_shipping_first_name( $order ) {
		if ( method_exists( $order, 'get_shipping_first_name' ) ) {
			return $order->get_shipping_first_name();
		}
		return $order->shipping_first_name;
	}

	/**
	 * Get billing first name of WC order.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return string $firstname order billing first name
	 */
	public static function get_billing_first_name( $order ) {
		if ( method_exists( $order, 'get_billing_first_name' ) ) {
			return $order->get_billing_first_name();
		}
		return $order->billing_first_name;
	}

	/**
	 * Get shipping last name of WC order.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return string $lastname order shipping last name
	 */
	public static function get_shipping_last_name( $order ) {
		if ( method_exists( $order, 'get_shipping_last_name' ) ) {
			return $order->get_shipping_last_name();
		}
		return $order->shipping_last_name;
	}

	/**
	 * Get billing last name of WC order.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return string $lastname order billing last name
	 */
	public static function get_billing_last_name( $order ) {
		if ( method_exists( $order, 'get_billing_last_name' ) ) {
			return $order->get_billing_last_name();
		}
		return $order->billing_last_name;
	}

	/**
	 * Get shipping company of WC order.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return string $company order shipping company
	 */
	public static function get_shipping_company( $order ) {
		if ( method_exists( $order, 'get_shipping_company' ) ) {
			return $order->get_shipping_company();
		}
		return $order->shipping_company;
	}

	/**
	 * Get billing company of WC order.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return string $company order billing company
	 */
	public static function get_billing_company( $order ) {
		if ( method_exists( $order, 'get_billing_company' ) ) {
			return $order->get_billing_company();
		}
		return $order->billing_company;
	}

	/**
	 * Get shipping address 2 of WC order.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return string $address2 order shipping address 2
	 */
	public static function get_shipping_address_2( $order ) {
		if ( method_exists( $order, 'get_shipping_address_2' ) ) {
			return $order->get_shipping_address_2();
		}
		return $order->shipping_address_2;
	}

	/**
	 * Get billing address 2 of WC order.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return string $address2 order billing address 2
	 */
	public static function get_billing_address_2( $order ) {
		if ( method_exists( $order, 'get_billing_address_2' ) ) {
			return $order->get_billing_address_2();
		}
		return $order->billing_address_2;
	}

	/**
	 * Get shipping address 1 of WC order.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return string $address1 order shipping address 1
	 */
	public static function get_shipping_address_1( $order ) {
		if ( method_exists( $order, 'get_shipping_address_1' ) ) {
			return $order->get_shipping_address_1();
		}
		return $order->shipping_address_1;
	}

	/**
	 * Get billing address 1 of WC order.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return string $address1 order billing address 1
	 */
	public static function get_billing_address_1( $order ) {
		if ( method_exists( $order, 'get_billing_address_1' ) ) {
			return $order->get_billing_address_1();
		}
		return $order->billing_address_1;
	}

	/**
	 * Get shipping city of WC order.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return string $city order shipping city
	 */
	public static function get_shipping_city( $order ) {
		if ( method_exists( $order, 'get_shipping_city' ) ) {
			return $order->get_shipping_city();
		}
		return $order->shipping_city;
	}

	/**
	 * Get billing city of WC order.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return string $city order billing city
	 */
	public static function get_billing_city( $order ) {
		if ( method_exists( $order, 'get_billing_city' ) ) {
			return $order->get_billing_city();
		}
		return $order->billing_city;
	}

	/**
	 * Get shipping state of WC order.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return string $state order shipping state
	 */
	public static function get_shipping_state( $order ) {
		if ( method_exists( $order, 'get_shipping_state' ) ) {
			return $order->get_shipping_state();
		}
		return $order->shipping_state;
	}

	/**
	 * Get billing state of WC order.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return string $state order billing state
	 */
	public static function get_billing_state( $order ) {
		if ( method_exists( $order, 'get_billing_state' ) ) {
			return $order->get_billing_state();
		}
		return $order->billing_state;
	}

	/**
	 * Get shipping postcode of WC order.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return string $postcode order shipping postcode
	 */
	public static function get_shipping_postcode( $order ) {
		if ( method_exists( $order, 'get_shipping_postcode' ) ) {
			return $order->get_shipping_postcode();
		}
		return $order->shipping_postcode;
	}

	/**
	 * Get billing postcode of WC order.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return string $postcode order billing postcode
	 */
	public static function get_billing_postcode( $order ) {
		if ( method_exists( $order, 'get_billing_postcode' ) ) {
			return $order->get_billing_postcode();
		}
		return $order->billing_postcode;
	}

	/**
	 * Get shipping country of WC order.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return string $country order shipping country
	 */
	public static function get_shipping_country( $order ) {
		if ( method_exists( $order, 'get_shipping_country' ) ) {
			return $order->get_shipping_country();
		}
		return $order->shipping_country;
	}

	/**
	 * Get billing country of WC order.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return string $country order billing country
	 */
	public static function get_billing_country( $order ) {
		if ( method_exists( $order, 'get_billing_country' ) ) {
			return $order->get_billing_country();
		}
		return $order->billing_country;
	}

	/**
	 * Get billing email of WC order.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return string $country order billing email
	 */
	public static function get_billing_email( $order ) {
		if ( method_exists( $order, 'get_billing_email' ) ) {
			return $order->get_billing_email();
		}
		return $order->billing_email;
	}

	/**
	 * Get shipping phone of WC order.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return string $country order shipping phone
	 */
	public static function get_shipping_phone( $order ) {
		if ( method_exists( $order, 'get_shipping_phone' ) ) {
			return $order->get_shipping_phone();
		}
		if ( property_exists( $order, 'shipping_phone' ) ) {
			return $order->shipping_phone;
		}
		return null;
	}

	/**
	 * Get billing phone of WC order.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return string $country order billing phone
	 */
	public static function get_billing_phone( $order ) {
		if ( method_exists( $order, 'get_billing_phone' ) ) {
			return $order->get_billing_phone();
		}
		return $order->billing_phone;
	}

	/**
	 * Get status of WC order.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return string $status order status
	 */
	public static function get_status( $order ) {
		if ( method_exists( $order, 'get_status' ) ) {
			return $order->get_status();
		}
		return $order->status;
	}

	/**
	 * Save WC order.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @void
	 */
	public static function save( $order ) {
		if ( method_exists( $order, 'save' ) ) {
			$order->save();
		}
		if ( method_exists( $order, 'save_meta_data' ) ) {
			$order->save_meta_data();
		}
	}

	/**
	 * Add meta data to WC order.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @param string    $key key of meta data.
	 * @param string    $data data to be added.
	 * @void
	 */
	public static function add_meta_data( $order, $key, $data ) {
		if ( method_exists( $order, 'add_meta_data' ) ) {
			$order->add_meta_data( $key, $data );
		} else {
			update_post_meta( $order->id, $key, $data );
		}
	}

	/**
	 * Get meta data to WC order.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @param string    $key key of meta data.
	 * @void
	 */
	public static function get_meta( $order, $key ) {
		if ( method_exists( $order, 'get_meta' ) ) {
			return $order->get_meta( $key );
		}
		return get_post_meta( $order->id, $key, true );
	}

	/**
	 * Get an order parcelpoint meta data
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return mixed    $parcelpoint in standard format
	 */
	public static function get_parcelpoint( $order ) {

		$parcelpoint = self::get_meta( $order, 'laposteproexp_parcel_point' );

		if ( ! $parcelpoint ) {
			$parcelpoint = null;
			$code        = self::get_meta( $order, 'laposteproexp_parcel_point_code' );
			$network     = self::get_meta( $order, 'laposteproexp_parcel_point_network' );

			if ( $code && $network ) {
				$parcelpoint = Parcelpoint_Util::create_parcelpoint(
					$network,
					$code,
					null,
					null,
					null,
					null,
					null,
					null,
					null
				);
			}
		}

		return $parcelpoint;
	}

	/**
	 * Get WC order shipping total.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return float
	 */
	public static function get_shipping_total( $order ) {
		if ( method_exists( $order, 'get_shipping_total' ) ) {
			return (float) $order->get_shipping_total();
		}
		return (float) $order->get_total_shipping();
	}

	/**
	 * Get an order item total with taxes (excluding fees).
	 *
	 * @param \WC_Order      $order order.
	 * @param \WC_Order_Item $item order product.
	 * @return float
	 */
	public static function get_quantity_refunded_for_item( $order, $item ) {
		return $order->get_qty_refunded_for_item( self::get_order_item_id( $item ) );
	}

	/**
	 * Get an order item subtotal.
	 *
	 * @param \WC_Order_Item $item order product.
	 * @return float|null
	 */
	public static function get_order_item_subtotal( $item ) {
		if ( method_exists( $item, 'get_subtotal' ) ) {
			return (float) $item->get_subtotal();
		}
		return null;
	}

	/**
	 * Get an order item total.
	 *
	 * @param \WC_Order_Item $item order product.
	 * @return float|null
	 */
	public static function get_order_item_total( $item ) {
		if ( method_exists( $item, 'get_total' ) ) {
			return (float) $item->get_total();
		}
		return null;
	}

	/**
	 * Get an order item total refunded.
	 *
	 * @param \WC_Order      $order order.
	 * @param \WC_Order_Item $item order product.
	 * @return float
	 */
	public static function get_order_item_total_refunded( $order, $item ) {
		return (float) $order->get_total_refunded_for_item( self::get_order_item_id( $item ) );
	}

	/**
	 * Get an order item unit price.
	 *
	 * @param \WC_Order              $order order.
	 * @param \WC_Order_Item_Product $item order product.
	 * @return float
	 */
	public static function get_order_item_unit_price( $order, $item ) {
		return $order->get_item_subtotal( $item, false, true );
	}

	/**
	 * Get WC order total discount.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return float
	 */
	public static function get_total_discount( $order ) {
		return $order->get_total_discount();
	}

	/**
	 * Get WC order discount tax.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return float
	 */
	public static function get_discount_tax( $order ) {
		// il y a peut être moyen d'avoir un float directement ?
		return floatval( $order->get_discount_tax() );
	}

	/**
	 * Get WC order total fees.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return float|null
	 */
	public static function get_total_fees( $order ) {
		if ( method_exists( $order, 'get_total_fees' ) ) {
			return $order->get_total_fees();
		}
		return null;
	}

	/**
	 * Get WC order shipping tax.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return float
	 */
	public static function get_shipping_tax( $order ) {
		// il y a peut être moyen d'avoir un float directement ?
		return floatval( $order->get_shipping_tax() );
	}

	/**
	 * Get WC order total refunded.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return float|null
	 */
	public static function get_total_refunded( $order ) {
		if ( method_exists( $order, 'get_total_refunded' ) ) {
			return floatval( $order->get_total_refunded() );
		}
		return null;
	}

	/**
	 * Get WC order total shipping refunded.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return float|null
	 */
	public static function get_total_shipping_refunded( $order ) {
		if ( method_exists( $order, 'get_total_shipping_refunded' ) ) {
			return $order->get_total_shipping_refunded();
		}
		return null;
	}

	/**
	 * Get WC order total shipping tax refunded.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return float|null
	 */
	public static function get_total_shipping_tax_refunded( $order ) {
		if ( method_exists( $order, 'get_total_shipping_tax_refunded' ) ) {
			return $order->get_total_shipping_tax_refunded();
		}
		return null;
	}

	/**
	 * Get WC order total tax refunded.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return float|null
	 */
	public static function get_total_tax_refunded( $order ) {
		if ( method_exists( $order, 'get_total_tax_refunded' ) ) {
			return $order->get_total_tax_refunded();
		}
		return null;
	}

	/**
	 * Get WC order customer note.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return string
	 */
	public static function get_customer_note( $order ) {
		return (string) $order->get_customer_note();
	}

	/**
	 * Get WC order shipping method label.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return string
	 */
	public static function get_shipping_method_label( $order ) {
		return (string) $order->get_shipping_method();
	}

	/**
	 * Get WC order shipping method id.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return string
	 */
	public static function get_shipping_method_id( $order ) {
		$ids = array();
		foreach ( $order->get_shipping_methods() as $shipping_method ) {
			if ( 'shipping' === $shipping_method->get_type() ) {
				$ids[] = $shipping_method->get_method_id() . ':' . $shipping_method->get_instance_id();
			}
		}
		return implode( ', ', $ids );
	}

	/**
	 * Get WC order creation date.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return \DateTime
	 */
	public static function get_date_created( $order ) {
		if ( method_exists( $order, 'get_date_created' ) ) {
			return $order->get_date_created();
		}
		return new \DateTime( $order->order_date );
	}

	/**
	 * Get WC order creation date.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return \DateTime|null
	 */
	public static function get_date_modified( $order ) {
		if ( method_exists( $order, 'get_date_modified' ) ) {
			return $order->get_date_modified();
		}
		return null;
	}

	/**
	 * Get WC order total excluding taxes.
	 * Include items and shipping prices tax excluded.
	 *
	 * @deprecated
	 * @param \WC_Order $order woocommerce order.
	 * @return float
	 */
	public static function get_total_excluding_taxes( $order ) {
		return (float) self::get_subtotal( $order ) + self::get_shipping_total( $order );
	}

	/**
	 * Get WC order subtotal.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return float
	 */
	public static function get_subtotal( $order ) {
		return (float) $order->get_subtotal();
	}

	/**
	 * Get WC order cart tax.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return float
	 */
	public static function get_cart_tax( $order ) {
		return (float) $order->get_cart_tax();
	}

	/**
	 * Is an order paid.
	 *
	 * @param \WC_Order $order order.
	 *
	 * @return boolean
	 */
	public static function is_order_paid( $order ) {
		return $order->is_paid();
	}

	/**
	 * Get WC order tax total.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return float
	 */
	public static function get_total_tax( $order ) {
		return (float) $order->get_total_tax();
	}

	/**
	 * Get WC order total.
	 *
	 * @param \WC_Order $order woocommerce order.
	 * @return float
	 */
	public static function get_total( $order ) {
		return (float) $order->get_total();
	}

	/**
	 * Get an order item product price excluding taxes
	 *
	 * @deprecated
	 * @param \WC_Order              $order order.
	 * @param \WC_Order_Item_Product $item order product.
	 * @return float|false
	 */
	public static function get_order_item_price_excluding_taxes( $order, $item ) {

		if ( method_exists( $order, 'get_item_subtotal' ) ) {
			return (float) $order->get_item_subtotal( $item, false, false );
		}

		return false;
	}

	/**
	 * Get order in admin context.
	 *
	 * @return \WC_Order $order woocommerce order
	 */
	public static function admin_get_order() {
		global $the_order, $post;
		if ( ! is_object( $the_order ) ) {
			if ( function_exists( 'wc_get_order' ) ) {
				$order = wc_get_order( $post->ID );
				// fix for WC < 2.5.
			} elseif ( WC()->order_factory !== false ) {
				$order = WC()->order_factory->get_order( $post->ID );
			} else {
				global $theorder;

				if ( ! is_object( $theorder ) ) {
					$theorder = new \WC_Order( $post->ID );
				}

				$order = $theorder;
			}
		} else {
			$order = $the_order;
		}
		return $order;
	}

	/**
	 * Get order statuses valid for import.
	 *
	 * @return array string list of statuses
	 */
	public static function get_import_status_list() {
		$statuses            = array();
		$unauthorized_status = array( 'wc-pending', 'wc-completed', 'wc-cancelled', 'wc-refunded', 'wc-failed', 'wc-checkout-draft' );
		foreach ( wc_get_order_statuses() as $order_status => $translation ) {
			if ( ! in_array( $order_status, $unauthorized_status, true ) ) {
				$statuses[ str_replace( 'wc-', '', $order_status ) ] = $translation;
			}
		}
		return $statuses;
	}

	/**
	 * Update an order state as shipped
	 *
	 * @param number $order_id order id.
	 */
	public static function set_order_as_shipped( $order_id ) {
		$order_statuses = wc_get_order_statuses();
		$shipped_status = Configuration_Util::get_order_shipped();
		$order          = wc_get_order( $order_id );

		if ( false !== $order ) {
			$note = esc_html__( 'Your order has been shipped.', 'la-poste-pro-expeditions-woocommerce' );
			$order->add_order_note( $note, false );
			$order->save();

			/**
			 * Triggered when an order is shipped using this plugin
			 *
			 * @since 1.0.0
			 */
			do_action( /* phpcs:ignore WordPress.NamingConventions.ValidHookName */ 'la_poste_pro_expeditions_woocommerce_order_shipped', $order_id );

			if ( null !== $shipped_status && isset( $order_statuses[ $shipped_status ] ) ) {
				$order->update_status( $shipped_status );
			} elseif ( null !== $shipped_status ) {
				update_option( 'LAPOSTEPROEXP_ORDER_SHIPPED', null );
			}
		}
	}

	/**
	 * Update an order state as delivered
	 *
	 * @param number $order_id order id.
	 */
	public static function set_order_as_delivered( $order_id ) {
		$order_statuses   = wc_get_order_statuses();
		$delivered_status = Configuration_Util::get_order_delivered();
		$order            = wc_get_order( $order_id );

		if ( false !== $order ) {
			$note = esc_html__( 'Your order has been delivered.', 'la-poste-pro-expeditions-woocommerce' );
			$order->add_order_note( $note, false );
			$order->save();

			/**
			 * Triggered when an order is delivered using this plugin
			 *
			 * @since 1.0.0
			 */
			do_action( /* phpcs:ignore WordPress.NamingConventions.ValidHookName */ 'la-poste-pro-expeditions-woocommerce_order_delivered', $order_id );

			if ( null !== $delivered_status && isset( $order_statuses[ $delivered_status ] ) ) {
				$order->update_status( $delivered_status );
			} elseif ( null !== $delivered_status ) {
				update_option( 'LAPOSTEPROEXP_ORDER_DELIVERED', null );
			}
		}
	}
}
