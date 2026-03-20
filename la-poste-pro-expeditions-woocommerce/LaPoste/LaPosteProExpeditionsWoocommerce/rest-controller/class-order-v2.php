<?php
/**
 * Contains code for the order class.
 *
 * @package     LaPoste\LaPosteProExpeditionsWoocommerce\Rest_Controller
 */

namespace LaPoste\LaPosteProExpeditionsWoocommerce\Rest_Controller;

use LaPoste\LaPosteProExpeditionsWoocommerce\Util\Api_Util;
use LaPoste\LaPosteProExpeditionsWoocommerce\Util\Auth_Util;
use LaPoste\LaPosteProExpeditionsWoocommerce\Util\Order_Item_Shipping_Util;
use LaPoste\LaPosteProExpeditionsWoocommerce\Util\Product_Util;
use LaPoste\LaPosteProExpeditionsWoocommerce\Util\Order_Util;
use LaPoste\LaPosteProExpeditionsWoocommerce\Util\Misc_Util;
use LaPoste\LaPosteProExpeditionsWoocommerce\Util\Logger_Util;
use LaPoste\LaPosteProExpeditionsWoocommerce\Util\Configuration_Util;
use LaPoste\LaPosteProExpeditionsWoocommerce\Util\Synchro_Api_Util;

/**
 * OrderV2 class.
 *
 * Opens API endpoint to sync orders.
 */
class Order_V2 {

	/**
	 * Run class.
	 *
	 * @void
	 */
	public function run() {
		add_action(
			'rest_api_init',
			function () {
				register_rest_route(
					'la-poste-pro-expeditions-woocommerce/v2',
					'/order',
					array(
						'methods'             => 'POST',
						'callback'            => array( $this, 'retrieve_orders_handler' ),
						'permission_callback' => array( $this, 'authenticate' )
					)
				);
			}
		);
		add_action(
			'rest_api_init',
			function () {
				register_rest_route(
					'la-poste-pro-expeditions-woocommerce/v2',
					'/state',
					array(
						'methods'             => 'POST',
						'callback'            => array( $this, 'state_handler' ),
						'permission_callback' => array( $this, 'authenticate' )
					)
				);
			}
		);
		add_action(
			'rest_api_init',
			function () {
				register_rest_route(
					'la-poste-pro-expeditions-woocommerce/v2',
					'/deleted-order',
					array(
						'methods'             => 'POST',
						'callback'            => array( $this, 'deleted_orders_handler' ),
						'permission_callback' => array( $this, 'authenticate' )
					)
				);
			}
		);
	}

	/**
	 * Call to auth helper class authenticate function.
	 *
	 * @param \WP_REST_Request $request request.
	 * @return \WP_Error|boolean
	 */
	public function authenticate( $request ) {
		return Auth_Util::authenticate_access_key( $request );
	}

	/**
	 * Retrieve orders callback.
	 *
	 * @param \WP_REST_Request $request request.
	 * @void
	 */
	public function retrieve_orders_handler( $request ) {
		$body = Auth_Util::decrypt_body( $request->get_body() );
		Logger_Util::info(
			'Incoming order synchronization request (v2) params: '
			. "\n" . wp_json_encode( $body, true )
		);
		$response = Synchro_Api_Util::get_order_synchronization_response( $body );
		Logger_Util::info(
			'Incoming order synchronization request (v2) response: '
			. "\n" . wp_json_encode( $response )
		);
		Api_Util::send_api_response( 200, $response );
	}

	/**
	 * Plugin state handler.
	 *
	 * @void
	 */
	public function state_handler() {
		$response = Synchro_Api_Util::get_plugin_state_response();
		Logger_Util::info(
			'Incoming state request (v2) response: '
			. "\n" . wp_json_encode( $response )
		);
		Api_Util::send_api_response( 200, $response );
	}

	/**
	 * Deleted orders handler.
	 *
	 * @param \WP_REST_Request $request request.
	 *
	 * @void
	 */
	public function deleted_orders_handler( $request ) {
		$body = Auth_Util::decrypt_body( $request->get_body() );
		Logger_Util::info(
			'Incoming deleted orders request (v2) params: '
			. "\n" . wp_json_encode( $body, true )
		);
		$response = Synchro_Api_Util::get_deleted_orders_response( $body );
		Logger_Util::info(
			'Incoming deleted orders request (v2) response: '
			. "\n" . wp_json_encode( $response )
		);
		Api_Util::send_api_response( 200, $response );
	}
}
