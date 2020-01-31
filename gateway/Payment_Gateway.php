<?php
/*
 * Plugin Name: CodingKart Payment Gateway
 * Plugin URI: http://gagan.codingkloud.com/wp-admin/plugins.php
 * Description: Custom WooCommerce Payment Gateway.
 * Author: Gagan
 * Author URI: http://gagan.codingkloud.com
 * Version: 1.0.1
 */
 /*
 * This action hook registers our PHP class as a WooCommerce payment gateway
 */
add_filter( 'woocommerce_payment_gateways', 'stripe_add_gateway_class' );
function stripe_add_gateway_class( $gateways ) {
	$gateways[] = 'WC_Misha_Gateway'; // your class name is here
	return $gateways;
}
 	require_once('stripe/init.php');
/*
 * The class itself, please note that it is inside plugins_loaded action hook
 */
add_action( 'plugins_loaded', 'init_gateway_class' );
function init_gateway_class() {
 
	class WC_Misha_Gateway extends WC_Payment_Gateway {
 
 		/**
 		 * Class constructor, more about it in Step 3
 		 */
 	public function __construct() {
 
	 	$this->id = 'payment'; // payment gateway plugin ID
		$this->icon = ''; // URL of the icon that will be displayed on checkout page near your gateway name
		$this->has_fields = true; // in case you need a custom credit card form
		$this->method_title = 'Codingkart Gateway';
		$this->method_description = 'Stripe Api gateway'; // will be displayed on the options page
	 
		// gateways can support subscriptions, refunds, saved payment methods,
		// but in this tutorial we begin with simple payments
		$this->supports = array(
			 'products',
			  'refunds',
			  'subscriptions',
			  'subscription_cancellation',
			  'subscription_suspension',
			  'subscription_reactivation',
			  'subscription_amount_changes',
			  'subscription_date_changes',
			  'subscription_payment_method_change',
			  'subscription_payment_method_change_customer',
			  'subscription_payment_method_change_admin',
			  'multiple_subscriptions',
			  'pre-orders',
			  'default_credit_card_form',
			  'add_payment_method'
		);
		// Method with all the options fields
		$this->init_form_fields();
		// Load the settings.
		$this->init_settings();
		// $this->wc_stripe_refund_request($order);
		$this->title = $this->get_option( 'title' );
		$this->description = $this->get_option( 'description' );
		$this->enabled = $this->get_option( 'enabled' );
		$this->testmode = 'yes' === $this->get_option( 'testmode' );
		$this->private_key = $this->testmode ? $this->get_option( 'test_private_key' ) : $this->get_option( 'private_key' );
		$this->publishable_key = $this->testmode ? $this->get_option( 'test_publishable_key' ) : $this->get_option( 'publishable_key' );
	 
		// This action hook saves the settings
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
	 
		// We need custom JavaScript to obtain a token
		add_action( 'wp_enqueue_scripts', array( $this, 'payment_scripts' ) );
	
		// add_action( 'wc_stripe_refund_request', array( $this, 'wc_stripe_refund_request_callback') ,1,2 );
       
		// You can also register a webhook here
 		}
		
	    function process_response($charge,$order_id)
		 {

			$order = wc_get_order( $order_id );
			$status = $order->get_status();
	         
			if($status=='pending')
			{
				$order->update_status('completed');
				return 'true';
			}
			

		}
 	public function init_form_fields(){
		$this->form_fields = array(
		'enabled' => array(
			'title'       => 'Enable/Disable',
			'label'       => 'Enable Codingkart Gateway',
			'type'        => 'checkbox',
			'description' => '',
			'default'     => 'no'
		),
		'title' => array(
			'title'       => 'Title',
			'type'        => 'text',
			'description' => 'This controls the title which the user sees during checkout.',
			'default'     => 'Credit Card',
			'desc_tip'    => true,
		),
		'description' => array(
			'title'       => 'Description',
			'type'        => 'textarea',
			'description' => 'This controls the description which the user sees during checkout.',
			'default'     => 'Pay with your credit card via our super-cool payment gateway.',
		),
		'testmode' => array(
			'title'       => 'Test mode',
			'label'       => 'Enable Test Mode',
			'type'        => 'checkbox',
			'description' => 'Place the payment gateway in test mode using test API keys.',
			'default'     => 'yes',
			'desc_tip'    => true,
		),
		'test_publishable_key' => array(
			'title'       => 'Test Publishable Key',
			'type'        => 'text'
		),
		'test_private_key' => array(
			'title'       => 'Test Private Key',
			'type'        => 'password',
		),
		'publishable_key' => array(
			'title'       => 'Live Publishable Key',
			'type'        => 'text'
		),
		'private_key' => array(
			'title'       => 'Live Private Key',
			'type'        => 'password'
		),
		 'saved_cards' => array(
			                   'title'       => __( 'Saved Cards', 'payment' ),
			                   'label'       => __( 'Enable Payment via Saved Cards', 'payment' ),
			                   'type'        => 'checkbox',
			                   'description' => __( 'If enabled, users will be able to pay with a saved card during checkout. Card details are saved on Stripe servers, not on your store.', 'payment' ),
			                   'default'     => 'no',
			                   'desc_tip'    => true,
		 ),);
    }
 
		/**
		 * You will need it if you want your custom credit card form, Step 4 is about it
		 */
	public function payment_fields() {
		// ok, let's display some description before the payment form
		if ( $this->description ) {
			// you can instructions for test mode, I mean test card numbers etc.
			if ( $this->testmode ) {
				$this->description .= ' TEST MODE ENABLED. In test mode, you can use the card numbers listed in <a href="#" target="_blank" rel="noopener noreferrer">documentation</a>.';
				$this->description  = trim( $this->description );
			}
			// display the description with <p> tags etc.
			echo wpautop( wp_kses_post( $this->description ) );
		}
           	
	    $this->tokens= WC_Payment_Tokens::get_customer_tokens( get_current_user_id(), $this->id );
		$cc_form           = new WC_Payment_Gateway_CC();
		$cc_form->id       = $this->id;
		$cc_form->supports = $this->supports;
		$cc_form->form();
	    $this->saved_payment_methods();
        $this->save_payment_method_checkbox();
		echo '<fieldset id="wc-' . esc_attr( $this->id ) . '-cc-form" class="wc-credit-card-form wc-payment-form" style="background:transparent;">';
	 
		// Add this action hook if you want your custom payment gateway to support it
		do_action( 'woocommerce_credit_card_form_start', $this->id );

		do_action( 'woocommerce_credit_card_form_end', $this->id );
	 
		echo '<div class="clear"></div></fieldset>';
	}
 
		/*
		 * Custom CSS and JS, in most cases required only when you decided to go with a custom credit card form
		 */

	public function payment_scripts() {
		// we need JavaScript to process a token only on cart/checkout pages, right?
		if ( ! is_cart() && ! is_checkout() && ! isset( $_GET['pay_for_order'] ) ) {
			return;
		}
	 
		// if our payment gateway is disabled, we do not have to enqueue JS too
		if ( 'no' === $this->enabled ) {
			return;
		}
	 
		// no reason to enqueue JavaScript if API keys are not set
		if ( empty( $this->private_key ) || empty( $this->publishable_key ) ) {
			return;
		}
	 
		// do not work with card detailes without SSL unless your website is in a test mode
		if ( ! $this->testmode && ! is_ssl() ) {
			return;
		}
		// let's suppose it is our payment processor JavaScript that allows to obtain a token
		wp_enqueue_script( 'misha_js', 'https://www.mishapayments.com/api/token.js' );
	 
		// in most payment processors you have to use PUBLIC KEY to obtain a token
		wp_localize_script( 'woocommerce_misha', 'misha_params', array(
			'publishableKey' => $this->publishable_key
		));
	 
		wp_enqueue_script( 'woocommerce_misha' );
	}
		/*
 		 * Fields validation, more in Step 5
		 */
	public function validate_fields() {
	     if( empty( $_POST[ 'billing_first_name' ]) ) {
			wc_add_notice(  'First name is required!', 'error' );
			return false;
		}
    	return true;
	}
		/*
		 * We're processing the payments here, everything about it is in Step 5
		 */
    public function process_refund($order_id, $amount = NULL, $reason = '') {     
        $chargeRecord = get_post_meta($order_id,'_transaction_id',true);
        $total_amount= (int)$amount; 
	   // if($reason ==''){
	   // $reason="requested_by_customer";
	   // }
     	\Stripe\Stripe::setApiKey('sk_test_ZTvO3ZGb3kbnzRp2noAqVIzB00hukgyy3m');
		      $refund = \Stripe\Refund::create([
                                         'charge' => $chargeRecord,
                                         'amount' => $total_amount,
                                         // 'reason' =>$reason,
                     ]);
		  return true; 
	}
	function save_card($charge) {
	 	// print_r($charge);die;
	    $card_number = str_replace( ' ', '', $_POST['payment-card-number'] );
	    $exp_date_array = explode( "/", $_POST['payment-card-expiry'] );
		$exp_month = trim( $exp_date_array[0] );
		$exp_year = trim( $exp_date_array[1] );
		$exp_date = $exp_month . substr( $exp_year, -2 );
		$token = new WC_Payment_Token_CC();
		$token->set_token($charge['source']['id']);
		$token->set_gateway_id( 'payment' );
		$token->set_card_type( strtolower( $this->get_card_type( $card_number ) ) );
		$token->set_last4( substr( $card_number, -4 ) );
		$token->set_expiry_month( substr( $exp_date, 0, 2 ) );
		$token->set_expiry_year( '20' . substr( $exp_date, -2 ) );
		$token->set_user_id( get_current_user_id() );	
		$token->save();
	}
	public function get_card_type( $number ) {
		if ( preg_match( '/^4\d{12}(\d{3})?(\d{3})?$/', $number ) ) {
			return 'Visa';
		} elseif ( preg_match( '/^3[47]\d{13}$/', $number ) ) {
			return 'American Express';
		} elseif ( preg_match( '/^(5[1-5]\d{4}|677189|222[1-9]\d{2}|22[3-9]\d{3}|2[3-6]\d{4}|27[01]\d{3}|2720\d{2})\d{10}$/', $number ) ) {
			return 'MasterCard';
		} elseif ( preg_match( '/^(6011|65\d{2}|64[4-9]\d)\d{12}|(62\d{14})$/', $number ) ) {
			return 'Discover';
		} elseif  (preg_match( '/^35(28|29|[3-8]\d)\d{12}$/', $number ) ) {
			return 'JCB';
		} elseif ( preg_match( '/^3(0[0-5]|[68]\d)\d{11}$/', $number ) ) {
			return 'Diners Club';
		}
	}
	public function process_payment( $order_id ) {
	    global $errors;
		global $woocommerce;
	    $order            = wc_get_order( $order_id );
	    $num              = $order->get_total();
	    $cart_total_amount= (int)$num; 
	    $card_no          = $_POST['payment-card-number'];
	    $first_name       =$_POST['billing_first_name'];
	    $last_name        =$_POST['billing_last_name'];
	    $cust_name        = $first_name." ".$last_name;
	    $phone            =$_POST['billing_phone'];
	    $email            =$_POST['billing_email'];
	    $card_expiry      =$_POST['payment-card-expiry'];
	    $card_cvv         =$_POST['payment-card-cvc'];
	    $publishable_key  = $this->publishable_key;
        $charge;

       if( isset( $_POST['wc-payment-payment-token'] ) &&  'new' !== $_POST['wc-payment-payment-token'] && $card_no == ""){
	         $token_id     = wc_clean( $_POST['wc-payment-payment-token'] );
	         $card         = WC_Payment_Tokens::get( $token_id );
	         $users_id     = $card->get_user_id();
	         $customer_id  = get_user_meta($users_id,'_payment_customer_id');
	         $customer_id  =array_shift($customer_id);
	         $source_id    = $card->get_token();
	         // take payment from user by already saved cards by custom_payment_by_saved_card() and get return charge 
		     $charge1      = custom_payment_by_saved_card($order_id,$cust_name,$email,$phone,$cart_total_amount,$publishable_key,$source_id,$customer_id);
	         $charge        =$charge1;
        }
        else if(($card_no!=="") && ($card_cvv!=="") && ($card_expiry!=="")){
			$number = preg_replace('/[^\d]/', '', $card_no);
			if((preg_match('/^4[0-9]{12}(?:[0-9]{3})?$/', $number)==1)||( preg_match('/^5[1-5][0-9]{14}$/', $number) ==1)||(preg_match('/^(?:2131|1800|35\d{3})\d{11}$/', $number)==1)||(preg_match('/^6(?:011|5[0-9][0-9])[0-9]{12}$/', $number) ==1)||(preg_match('/^3(?:0[0-5]|[68][0-9])[0-9]{11}$/', $number)==1)||(preg_match('/^3[47][0-9]{13}$/', $number)==1))
			{
			   $charge1 = custom_payment_process_by_cvv($order_id,$cust_name,$email,$phone,$card_no,$card_cvv,$card_expiry,$cart_total_amount,$publishable_key);
				$charge=$charge1;
			   $this->save_card($charge);
		    }else{
				wc_add_notice('Only Accepting Card Types (Visa, Mastercard, American Express, Discover, Diners Club, JCB)',  $notice_type = 'error' );
				 return;
		    }
	    }else{
		   wc_add_notice('Please fill out appropreate card details.',  $notice_type = 'error' );
	    return false;
	    }

        $chargeRecord = get_post_meta($order_id,'_transaction_id',true);
	      if('true'==$this->process_response($charge,$order_id))
            { 
              $card_key     = $charge['customer'];
              $loged_in_user=get_current_user_id();
              print_r($charge['source']['id']);
              update_post_meta($order_id,'_transaction_id',$charge['id']);  
		      update_user_meta($loged_in_user,'_payment_customer_id',$charge['customer']);
		      update_post_meta($order_id,'_payment_customer_id',$charge['customer']);
		      update_post_meta($order_id,'_payment_source_id',$charge['source']['id']);
              $order->update_status('completed');
			  $order->payment_complete();
			  $order->reduce_order_stock();
 
			// some notes to customer (replace true with false to make it private)
			$order->add_order_note( 'Hey, order is paid! Thank you! and Charge ID is '.$charge_id, true );
 
			// Empty cart
			$woocommerce->cart->empty_cart();
 
			// Redirect to the thank you page
			return array(
				'result'   => 'success',
				'redirect' => $this->get_return_url( $order )
			);
 
		} else {
			wc_add_notice(  'Please try again.', 'error' );
			return;
		}

	}// process_payment end

 	}
}

    function custom_payment_process_by_cvv($order_id,$cust_name,$email,$phone,$card_no,$card_cvv,$card_expiry,$cart_total_amount,$publishable_key){
    try{
        $loged_in_user =get_current_user_id();
        $customer_id   = get_user_meta($loged_in_user,'_payment_customer_id');
        $customer_id   =array_shift($customer_id);
        $randkey = base64_encode(openssl_random_pseudo_bytes(32));
		// $card_no="4000056655665556";
		// $card_expiry="";
		// $exp_month  =12;
		// $exp_year   =2020;
		// $card_cvv   = 314;
		// $currency   = "inr";
		// $charge;
	    list($exp_month, $exp_year) = explode(' / ', $card_expiry);
		$card_no       = $card_no;
		$card_cvv      = $card_cvv;
		$currency      = "inr";
		$charge;
		\Stripe\Stripe::setMaxNetworkRetries(2);


        \Stripe\Stripe::setApiKey($publishable_key); 
   
     
        $stripeToken   = \Stripe\Token::create(
					 	["card" => ["number"    =>$card_no,
					 	            "exp_month" => $exp_month, 
					 	            "exp_year"  => $exp_year, 
					 	            "cvc"       => $card_cvv, 
					 	            "currency"  => $currency
					 	           ]
					 	]);

        if($loged_in_user==0){    
            $charge = \Stripe\Charge::create(array(
					    "amount"      => $cart_total_amount, // amount in cents
					    "currency"    => "inr",
					    'source'      => $stripeToken,
					    "description" => 'Order id='.$order_id."name =".$cust_name."contact no =".$phone
					     ),["idempotency_key" => $randkey]);
        }else if($loged_in_user!==0 && $customer_id ==""){
	     // $token_id  = $stripeToken->id;
	        $customer = \Stripe\Customer::create(array(
	             	   'name'         => $cust_name,
	            	   'email'        => $email, 
		     		   'source'       => $stripeToken,
		        	   'description'  => 'Contact='.$phone.' '.'Order id='.$order_id
	        	));
	
		    $charge   = \Stripe\Charge::create(array(
					    "amount"      => $cart_total_amount, // amount in cents
					    "currency"    => "inr",
					    "customer"    => $customer->id,
				        "description" => 'Order id='.$order_id."name =".$cust_name."contact no =".$phone
				         ),
		                 ["idempotency_key" => $randkey]);
	    }else{
		    $addcarddetails = \Stripe\Customer::createSource(
			$customer_id, ['source'   => $stripeToken]);        
            $charge  = \Stripe\Charge::create(array(
					    "amount"      => $cart_total_amount, // amount in cents
					    "currency"    => $currency,
					    "customer"    => $customer_id,
					    "description" => 'Order id='.$order_id."name =".$cust_name."contact no =".$phone),
                        ["idempotency_key" => $randkey]);
	    }	
	  update_user_meta($loged_in_user,'_payment_customer_id',$charge['customer']);
       return $charge;	
		  
    } catch (StripeError\Base $e){
        echo 'error handling'.$this->sendBackJsonError($e->getHttpStatus());
}
	 
}

    function custom_payment_by_saved_card($order_id,$cust_name,$email,$phone,$cart_total_amount,$publishable_key,$source_id,$customer_id){
        try{
           $randkey = base64_encode(openssl_random_pseudo_bytes(32));

           \Stripe\Stripe::setApiKey($publishable_key);
           \Stripe\Stripe::setMaxNetworkRetries(2);
   	        $charge1 = \Stripe\Charge::create(array(
				    "amount"      => $cart_total_amount, // amount in cents
				    "currency"    => "inr",
				    "customer"    => $customer_id,
				    "description" => 'Order id='.$order_id,
	                "source"      => $source_id),
   	                ["idempotency_key" => $randkey]);
	        // print_r($charge);die;
	     return $charge1;	
	    }catch (StripeError\Base $e) {
        echo 'error handling'.$this->sendBackJsonError($e->getHttpStatus());
        }
    }