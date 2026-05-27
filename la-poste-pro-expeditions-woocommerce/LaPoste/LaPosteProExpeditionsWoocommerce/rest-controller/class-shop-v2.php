<?php
/**
 * Contains code for the shop class.
 *
 * @package     LaPoste\LaPosteProExpeditionsWoocommerce\Rest_Controller
 */

namespace LaPoste\LaPosteProExpeditionsWoocommerce\Rest_Controller;

use LaPoste\LaPosteProExpeditionsWoocommerce\Notice\Notice_Controller;
use LaPoste\LaPosteProExpeditionsWoocommerce\Util\Api_Util;
use LaPoste\LaPosteProExpeditionsWoocommerce\Util\Auth_Util;
use LaPoste\LaPosteProExpeditionsWoocommerce\Util\Configuration_Util;
use LaPoste\LaPosteProExpeditionsWoocommerce\Util\Configuration_Report_Util;
use LaPoste\LaPosteProExpeditionsWoocommerce\Util\Logger_Util;

/**
 * Shop class.
 *
 * Opens API endpoint to pair.
 */
class Shop_V2 {

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
					'shop/pair',
					array(
						'methods'             => 'POST',
						'callback'            => array( $this, 'pairing_handler' ),
						'permission_callback' => array( $this, 'authenticate' )
					)
				);
			}
		);
	}

	/**
	 * Call to auth helper class authenticate function.
	 *
	 * @param WP_REST_Request $request request.
	 * @return WP_Error|boolean
	 */
	public function authenticate( $request ) {
		return Auth_Util::authenticate( $request );
	}

	/**
	 * Endpoint callback.
	 *
	 * @param WP_REST_Request $request request.
	 * @void
	 */
	public function pairing_handler( $request ) {
		$body = Auth_Util::decrypt_body( $request->get_body() );

		if ( null === $body ) {
			Logger_Util::warning( 'Incoming plugin pairing request (v2) denied: missing body' );
			Api_Util::send_api_response( 400 );
		}

		$access_key   = null;
		$secret_key   = null;
		if ( is_object( $body ) && property_exists( $body, 'accessKey' ) && property_exists( $body, 'secretKey' ) ) {
			$access_key = $body->accessKey;
			$secret_key = $body->secretKey;
		}

		if ( null !== $access_key && null !== $secret_key ) {
			if ( ! Auth_Util::is_plugin_paired() ) {
				Auth_Util::pair_plugin( $access_key, $secret_key );
				Notice_Controller::remove_notice( Notice_Controller::$setup_wizard );
				Notice_Controller::add_notice( Notice_Controller::$pairing, array( 'result' => 1 ) );
				Api_Util::send_api_response( 200, $this->get_pairing_success_response_body() );
			} else {
				Logger_Util::warning( 'Plugin pairing request (v2) denied: plugin already paired' );
				Api_Util::send_api_response( 403 );
			}
		} else {
			Notice_Controller::add_notice( Notice_Controller::$pairing, array( 'result' => 0 ) );
			Logger_Util::warning( 'Plugin pairing request (v2) denied: invalid request' );
			Api_Util::send_api_response( 400 );
		}
	}

	/**
	 * Get pairing success response body.
	 *
	 * @return array
	 */
	private function get_pairing_success_response_body() {
		$result = array(
			'versions'               => Configuration_Report_Util::get_versions(),
			'shopUuid'               => Configuration_Util::get_shop_uuid(),
			'pluginConfigurationUrl' => admin_url( 'admin.php?page=la-poste-pro-expeditions-woocommerce-settings' )
		);
		return $result;
	}

}
