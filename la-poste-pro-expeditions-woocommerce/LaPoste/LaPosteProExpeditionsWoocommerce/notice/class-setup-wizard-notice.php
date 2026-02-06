<?php
/**
 * Contains code for the setup wizard notice class.
 *
 * @package     LaPoste\LaPosteProExpeditionsWoocommerce\Notice
 */

namespace LaPoste\LaPosteProExpeditionsWoocommerce\Notice;

use LaPoste\LaPosteProExpeditionsWoocommerce\Util\Configuration_Util;

/**
 * Setup wizard notice class.
 *
 * Setup wizard notice used to display setup wizard.
 */
class Setup_Wizard_Notice extends Abstract_Notice {

	/**
	 * Onboarding link.
	 *
	 * @var string $onboarding_url url.
	 */
	public $onboarding_url;

	/**
	 * Construct function.
	 *
	 * @param string $key key for notice.
	 * @void
	 */
	public function __construct( $key ) {
		parent::__construct( $key );
		$this->type            = 'setup-wizard';
		$this->autodestruct    = false;
		$this->onboarding_url = Configuration_Util::get_onboarding_url();
		$this->template        = 'html-setup-wizard-notice';
	}
}
