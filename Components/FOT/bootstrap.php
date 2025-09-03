<?php 	
namespace Hamt\Components\FOT;

define('FOTPATH', trailingslashit( dirname( __FILE__ )).'') ;	


$fot_rul = str_replace('\\', '/',explode('wp-content',FOTPATH)[1]);	

$fot_rul = home_url().'/wp-content/'.$fot_rul.'assets/';	

define('FOTURL', $fot_rul);	

class Bootstrap{	

	public function __construct(){		add_action( 'admin_menu',[$this,'add_theme_fields'] );	
		add_action('admin_enqueue_scripts',[$this,'fot_scripts']);	
		add_action('wp_ajax_save_options',[$this,'save_options']);	
	}

	public function save_options() {
	    // Verify nonce 
	    check_ajax_referer( 'fot_nonce', '_ajax_nonce' );

	    // Check if fields are set and is an array
	    if ( ! isset( $_POST['fields'] ) || ! is_array( $_POST['fields'] ) ) {
	        wp_send_json_error( [ 'message' => 'Invalid fields data' ] );
	    }

	    // Sanitize and update options
	    foreach ( $_POST['fields'] as $key => $value ) {
	        $sanitized_key = sanitize_key( $key );
	        $sanitized_value = sanitize_text_field( stripslashes( $value ) );
	        update_option( $sanitized_key, $sanitized_value );
	    }

	    // Send success response
	    wp_send_json_success( [ 'message' => 'Options saved successfully' ] );
	}

	public function fot_scripts() {
	    wp_enqueue_style( 'fot-icons', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css' );
	    wp_enqueue_style( 'fot-style', FOTURL . 'css/fot.css', [], null );
	    wp_enqueue_script( 'fot-js', FOTURL . 'js/fot.js', ['jquery'], null );

	    // Add nonce and admin-ajax URL for secure AJAX requests
	    wp_localize_script( 'fot-js', 'fotAjax', [
	        'ajaxurl' => admin_url( 'admin-ajax.php' ),
	        'nonce'   => wp_create_nonce( 'fot_nonce' )
	    ]);
	}

	public function add_theme_fields(){		
	add_menu_page( 'Theme Fields','Theme Options', 'manage_options', 'fot_options',[$this,'field_options'],  'dashicons-editor-code', 110 );	

	}	
	public function field_options(){		
		require FOTPATH.'temp/interface.php';	

	}
}

$fot_instance = new Bootstrap;
global $fot_instance;