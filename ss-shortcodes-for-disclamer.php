<?php
/**
Plugin Name: SS ShortCodes for Disclamer
Description: Short Codes for Disclamer paste.
Version: 1.0.0
Author: misha1.nd 
Author URI: 
*/


defined('DS') or define('DS', DIRECTORY_SEPARATOR);
define('SS_SHORTCODE_DISCLAMER_ROOT', __DIR__ . DS);

if ( ! class_exists( 'SS_shortcode_disclamer' ) ) :

class SS_shortcode_disclamer {

	/**
	* Construct the plugin.
	*/
	public function __construct( ) {

		// Define user set variables.
		//$this->api_key				= get_option( 'ss_sms_verification_api_key' );
		//$this->sms_retries			= get_option( 'ss_sms_verification_sms_retries' );
		//$this->sms_instruction		= get_option( 'ss_sms_verification_sms_instruction' );
		//$this->sms_template			= get_option( 'ss_sms_verification_sms_template' );

		
		add_action( 'admin_menu', array( $this, 'admin_page' ) );
		//add_action( 'wp_ajax_wp_sms_action_send', array( $this, 'wp_sms_action_send' ) );
		//add_action( 'wp_ajax_wp_sms_action_verify', array( $this, 'wp_sms_action_verify' ) );
		//add_action( 'admin_enqueue_scripts', array( $this, 'plugin_enqueues' ) );


		add_shortcode( 'ss-disclamer', array( $this, 'disclamer_codes' ) );

		// Checks if Contact Form is installed.
		if ( class_exists( 'WPCF7' ) ) {
			wpcf7_add_form_tag( 'ss_disclamer', array( $this, 'disclamer_codes' ) );
		}

		


	}

	
	/**
	 * Add admin page.
	 */
	function admin_page() {
		
		add_menu_page( 'ShortCodes', 'ShortCodes', 'manage_options', 'ss-shortcodes-for-disclamer', array( &$this, 'admin_options' ), 'dashicons-smiley', 31 );
	}

	/**
	 * Content of admin page.
	 */
	function admin_options() {
		if ( !is_admin() ) {
			$this->write_debug_log( 'Not logged in as administrator. Settings page will not be shown.' );
			return;
		}

		


		echo '
		<div class="wrap">
			<h2>SS Shortcodes for disclamer</h2>
			

			<p>&nbsp;</p>


			<div>
				<div style="border-bottom:1px solid #ccc;">
					<h3>Shortcodes</h3>
				</div>

				<p>
					<h4>Shortcode for WordPress</h4>
					<pre> [ss-disclamer m="name"] - output is Full name (First name , Last Name ) </pre>
					<pre> [ss-disclamer m="email"] - output is an email  </pre>
					<pre> [ss-disclamer m="phone"] - output is phone number from Billing_phone (meta_key)  </pre>
					<pre> [ss-disclamer m="birth"] - output is birth date from birth_date (meta_key)  </pre>
					<pre> [ss-disclamer m="id"] - output is personal id number from Billing_id (meta_key)  </pre>
					<pre> [ss-disclamer m="address"] - output is an address from billing_address (meta_key)  </pre>
					<pre> [ss-disclamer m="date" format="" ] - output is is current time By default it will show a date like this: 7th May 2017	, format=’d/m/Y’] will show the date like this: 07/05/2017
 format=’F d, Y’] will show the date like this: May 07, 2017 </pre>
					<p>The shortcode is required to be embedded in any post, article or page.<p>
				</p>

				<p>&nbsp;</p>

				<p>
					<h4>Shortcode for Contact Form 7</h4>
					<pre> [ss_disclamer m="name"] - output is Full name (First name , Last Name ) </pre>
					<pre> [ss_disclamer m="email"] - output is an email  </pre>
					<pre> [ss_disclamer m="phone"] - output is phone number from Billing_phone (meta_key)  </pre>
					<pre> [ss_disclamer m="birth"] - output is birth date from birth_date (meta_key)  </pre>
					<pre> [ss_disclamer m="id"] - output is personal id number from Billing_id (meta_key)  </pre>
					<pre> [ss_disclamer m="address"] - output is an address from billing_address (meta_key)  </pre>
					<pre> [ss_disclamer m="date" format="" ] - output is is current time By default it will show a date like this: 7th May 2017	, format=’d/m/Y’] will show the date like this: 07/05/2017
 format=’F d, Y’] will show the date like this: May 07, 2017 </pre>
					<p>The shortcode is required to be embedded in Contact Form.<p>
				</p>

			</div>
		</div>';
	}

	/**
	 * Initial default settings.
	 */
	function set_defaults() {
		//if( !get_option( 'ss_sms_verification_api_key' ) ) update_option( 'ss_sms_verification_api_key', '' );
		//update_option( 'ss_sms_verification_sms_retries', '3' );
		//update_option( 'ss_sms_verification_sms_instruction', '' );
		//update_option( 'ss_sms_verification_sms_template', 'Hi, your OTP is {otp}.' );
		//update_option( 'ss_sms_verification_debug_log_enabled', 0 );


		flush_rewrite_rules();
	}

	
	/**
	 * Shortcode for SMS Verification.
	 */
	public function disclamer_codes($atts,$content,$tag) {
		
		global $current_user;
        get_currentuserinfo();
		
		$values = shortcode_atts(array(
		'm' => 'other','format'=>''
		),$atts);  
		
		if($values['m'] == 'email'){
		$output = $current_user->user_email;
		}
		else if($values['m'] == 'name'){
		$output = $current_user->user_firstname . ' '. $current_user->user_lastname;
		}
		else if($values['m'] == 'phone'){
		$output = $current_user->billing_phone;
		}
		else if($values['m'] == 'birth'){
		$output = $current_user->birth_date;
		}
		else if($values['m'] == 'phone'){
		$output = $current_user->billing_phone;
		}
		else if($values['m'] == 'id'){
		    if($current_user->billing_id){
		$output = $current_user->billing_id;
		    }else{
		$output = $current_user->Billing_id;
		    }
		}
		else if($values['m'] == 'address'){
		
		$output = $current_user->billing_address;
		}else if($values['m'] == 'date'){
		
		if ( !empty( $atts['format'] ) ) {
				$dateFormat = $atts['format'];
			} else {
				$dateFormat = 'jS F Y';
			}

			/* Changed [return date( $dateFormat );]
			 * to the following line in order to retrieve
			 * WP time as opposed to server time.
			 *
			 * @author UN_Rick
			 */
			if ( $dateFormat == 'z' ) {
				$output =date_i18n( $dateFormat ) + 1;
			} else {
				$output =date_i18n( $dateFormat );
			}
		
				
			
		}else{
		$output = 'I am not sure what you are'; 
		}
		
		// echo 'Username: ' . $current_user->user_login . "";
     // echo 'User email: ' . $current_user->user_email . "";
     // echo 'User first name: ' . $current_user->user_firstname . "";
      //echo 'User last name: ' . $current_user->user_lastname . "";
      //echo 'User display name: ' . $current_user->display_name . "";
      //echo 'User ID: ' . $current_user->ID . "";
		return $output;
	}

	
	
	
}

$ss_shortcode_disclamer = new SS_shortcode_disclamer( __FILE__ );
register_activation_hook( __FILE__, array( $ss_shortcode_disclamer, 'set_defaults' ) );

endif;
