<?php 

class WooCommData{
  public function __construct() {  
     add_action( 'wp_enqueue_scripts',array( $this,'woocommerce_data_scripts' ) );

     add_action( 'wp_enqueue_scripts',array( $this,'get_current_user_id' ) );

     add_action('woocommerce_registration_redirect',array( $this,'custom_login_page_redirect'), 2);
  }

 //****************** ENQUEUE SCRIPT FILE  *******************//
  public function woocommerce_data_scripts() {

    wp_enqueue_script( 'jquery' );
    wp_register_script( 'woo_com_load', get_template_directory_uri() . '/js/woo_com_data.js', array( 'jquery' ) );

    wp_localize_script( 'woo_com_load', 'woocom_loadmore_params', array( 'ajaxurl' =>admin_url( 'admin-ajax.php' ) ) );
    wp_enqueue_script( 'woo_com_load' );
    }

  public function custom_login_page_redirect() {
	   $user = wp_get_current_user();
	    return home_url('/').'post-list/?'.$user->ID;
	}

 public function get_current_user_id() {
    if ( ! function_exists( 'wp_get_current_user' ) ) {
       
        return 0;
    }
    $user = wp_get_current_user();
  echo $user->ID;
    return ( isset( $user->ID ) ? (int) $user->ID : 0 );
}


}
$objWooCommData = new WooCommData();
