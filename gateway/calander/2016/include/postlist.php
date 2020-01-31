<?php 
//get_header();
class Postlist{
  public function __construct() {   
    // initie enque
     add_action( 'wp_enqueue_scripts',array( $this,'misha_my_load_more_scripts' ) );
     add_action( 'wp_ajax_load_posts_by_ajax',array( $this,'load_posts_by_ajax_callback' ) ); 
     add_action( 'wp_ajax_nopriv_load_posts_by_ajax',array( $this,'load_posts_by_ajax_callback' ));
    //property filter initiate ajax 
     add_action( 'wp_ajax_search_property_by_ajax',array( $this,'filter_property' ) ); 
     add_action( 'wp_ajax_nopriv_search_property_by_ajax',array( $this,'filter_property' ) );
     add_action( 'wp_ajax_register_guest_detail_ajax',array( $this,'register_guest_detail_call_back' ) ); 
     add_action( 'wp_ajax_nopriv_register_guest_detail_ajax',array( $this,'register_guest_detail_call_back' ) );

     add_action( 'wp_ajax_booked_property_date_ajax',array( $this,'fetch_protety_booked_dates' ) ); 
     add_action( 'wp_ajax_nopriv_booked_property_date_ajax',array( $this,'fetch_protety_booked_dates' ) );

     add_action( 'wp_ajax_add_booking_ajax',array( $this,'add_booking_ajax_callback' ) ); 

     add_action( 'wp_ajax_nopriv_add_booking_ajax',array( $this,'add_booking_ajax_callback' ) );
      // get datetime for counter
      add_action( 'wp_ajax_database_user_time_ajax',array( $this,'db_utime_callback' ) ); 
      add_action( 'wp_ajax_nopriv_database_user_time_ajax',array( $this,'db_utime_callback' ) ); 
      // delete user booking as per timer
      add_action( 'wp_ajax_delete_tmp_booking_ajax',array( $this,'delete_user_booking_callback' ) ); 
      add_action( 'wp_ajax_nopriv_delete_tmp_booking_ajax',array( $this,'delete_user_booking_callback' ) );

      // temperory booking for property
      add_action( 'wp_ajax_temporary_booking_ajax',array( $this,'temporary_booking_callback' ) ); 
      add_action('wp_ajax_nopriv_temporary_booking_ajax',array($this,'temporary_booking_callback' )); 

      // check_old_tmp_bookings_ajax for geting old date entries
      add_action( 'wp_ajax_check_old_tmp_bookings_ajax',array( $this,'check_old_tmp_bookings_callback' ) ); 
      add_action('wp_ajax_nopriv_check_old_tmp_bookings_ajax',array($this,'check_old_tmp_bookings_callback' )); 

// check_old_tmp_bookings_ajax for geting old booking date entries for checkout purpose
      add_action( 'wp_ajax_check_old_tmp_bookings_checkout_ajax',array( $this,'check_old_tmp_bookings_checkout' ) ); 
      add_action('wp_ajax_nopriv_check_old_tmp_bookings_checkout_ajax',array($this,'check_old_tmp_bookings_checkout' )); 

  // check logged in user
      add_action( 'wp_ajax_check_loged_in_user_ajax',array( $this,'is_user_logged_in' ) ); 
      add_action('wp_ajax_nopriv_check_loged_in_user_ajax',array($this,'is_user_logged_in' )); 
 // Add to cart details
      //  add_action( 'wp_ajax_add_to_cart_ajax',array( $this,'add_to_cart_ajax' ) ); 
      // add_action('wp_ajax_nopriv_add_to_cart_ajax',array($this,'add_to_cart_ajax' )); 
    
     add_action('wp_ajax_wdm_add_user_custom_data_options', array( $this,'wdm_add_user_custom_data_options_callback'));
      add_action('wp_ajax_nopriv_wdm_add_user_custom_data_options',  array( $this,'wdm_add_user_custom_data_options_callback'));

      add_filter('woocommerce_add_cart_item_data', array( $this,'wdm_add_item_data'),1,2);
   
      add_filter('woocommerce_get_cart_item_from_session', array( $this,'wdm_get_cart_items_from_session'), 1, 3 );


    add_filter('woocommerce_checkout_cart_item_quantity',array( $this,'wdm_add_user_custom_option_from_session_into_cart'),1,3);  

     add_filter('woocommerce_cart_item_price',array( $this,'wdm_add_user_custom_option_from_session_into_cart'),1,3);

    // cart item name change
     add_filter('woocommerce_cart_item_name',array( $this,'cart_item_name_change'),1,3);

     add_filter('woocommerce_cart_item_thumbnail',array($this,'cart_item_thumbnails_change'),1,3); 
   
    // add_action('woocommerce_thankyou', array($this,'bbloomer_redirectcustom'));
     add_filter( 'woocommerce_thankyou_order_received_text', array($this,'misha_thank_you_title'), 20, 2 );

     // add_action( 'woocommerce_checkout_create_order_line_item', array($this,'wdm_add_custom_order_line_item_meta'),20,4 );

     // add_action( 'wp_ajax_factory_call_ajax',array( $this,'factorycall' ) ); 
     // add_action('wp_ajax_nopriv_factory_call_ajax',array($this,'factorycall' )); 

  }


 //****************** ENQUEUE SCRIPT FILE  *******************//
  public function misha_my_load_more_scripts() {

    wp_enqueue_script( 'jquery' );

    wp_register_script( 'my_loadmore', get_template_directory_uri() . '/js/property_load.js', array( 'jquery' ) );

    wp_localize_script( 'my_loadmore', 'misha_loadmore_params', array( 'ajaxurl' =>admin_url( 'admin-ajax.php' ) ) );
    wp_enqueue_script( 'my_loadmore' );
   }
  //*********** GET ALL LIST OF PROPERTY ***************//
   public function load_posts_by_ajax_callback()
   {
      $posts_per_page = 3;
      $pageno         =$_POST['pageno'];
      $offset         = ( $pageno - 1 ) * $posts_per_page;
      $args           = array(
                                'offset'         => $offset,             
                                'posts_per_page' => $posts_per_page ,
                                'post_type'      => 'property'
                          );
      $data_query = new WP_Query( $args ); 
    if ( $data_query->have_posts() ) : 
       while ( $data_query->have_posts() ) : $data_query->the_post(); 
       // the_ID(); ?><hr><?php
        get_template_part( 'template-parts/content');
       endwhile;
    endif;
    
  }
// **************Seaarch property********************//
  public function filter_property(){
    $location_address;
    $property_status;
    $metaquery=array();
    $argument=array();
    $location_address=$_POST['location_address'];
    $property_status=$_POST['property_status'];

      if(($location_address=="0")&&($property_status=="0")){

        $argument  = array(          
                        'post_type'      => 'property'
                        );
      
      }elseif(($location_address!=="0")&&($property_status!=="0")){

        $metaquery = array(
                             'relation'  => 'AND',
                          array(
                               'key'     => 'address',
                               'value'   =>  $location_address,    // GET ADDRESS OF PROPERTY
                               'type'    => 'CHAR',
                               'compare' => '='
                               
                          ),
                          array(
                               'key'     => 'status',
                               'value'   =>  $property_status,    // GET ADDRESS OF PROPERTY
                               'type'    => 'CHAR',
                               'compare' => '='
                          )
                       ); 
        $argument  = array(          
                        'post_type'      => 'property',
                        'meta_query'     => $metaquery 
                        );
      }
       else{
        $metaquery = array(
                             'relation'  => 'OR',
                          array(
                               'key'     => 'address',
                               'value'   =>  $location_address,    // GET ADDRESS OF PROPERTY
                               'type'    => 'CHAR',
                               'compare' => '='  
                          ),
                          array(
                               'key'     => 'status',
                               'value'   =>  $property_status,    // GET ADDRESS OF PROPERTY
                               'type'    => 'CHAR',
                               'compare' => '='
                               
                          )
                       ); 
          $argument  = array(          
                        'post_type'      => 'property',
                        'meta_query'     => $metaquery 
                        );
       }   

      $itemquery1 = new WP_Query($argument); 
        if ( $itemquery1->have_posts() ) : 
          while ( $itemquery1->have_posts() ) : $itemquery1->the_post(); 
          get_template_part( 'template-parts/content');
         endwhile;
       endif;
  }
   //*********** Register guest on property ***************//
   public function register_guest_detail_call_back()
   {   
     global $wpdb;
          $tanentfirstname = $_POST['tanentfirstname'];
          $tanentlastname = $_POST['tanentlastname'];
          $book_date       = $_POST['book_date'];
          list($startDate, $endDate) = explode(' - ', $_POST['book_date']);
           $property_id     = $_POST['property_id'];
           $deadline        = $_POST['deadline'];
           $booking_time = date('Y-m-d h:i:s a', time()); 
           $property_booking= $wpdb->insert("wp_property_booking",array(
                            "guest_first_name"  => maybe_serialize( $_POST['first_name']),
                            "guest_last_name"   => maybe_serialize( $_POST['last_name']),
                            "property_id"       => $property_id,
                            "tanent_first_name" => $tanentfirstname,
                            "tanent_last_name"  => $tanentlastname,
                            "booking_start_date"=> $startDate,
                            "booking_end_date"  => $endDate,
                            "booking_time"      => $booking_time,
                            "dead_line"         => $deadline
                           ));

         if($property_booking) {
          echo 'booking successfully';
             } else {
          echo 'booking failed';
          }  
  } 
     //*********** fetch protety booked dates ***************//
  public function fetch_protety_booked_dates()
   {   
     global $wpdb;
     $property_id = $_POST['property_id'];
     $date_list = $wpdb->get_results("SELECT booking_start_date, booking_end_date FROM wp_property_booking WHERE (property_id = '".$property_id ."')");
     
        $array = json_decode(json_encode($date_list), True);
        $blank=array();

        foreach($array as $key=>$row){
        $blank[]=$row;
        // $counter++;
        }
    
       echo json_encode($array);
      exit;
    }

///////////////////////////////////////////////////////////////

     //*********** fetch protety booked dates ***************//
  public function add_booking_ajax_callback()
   {   
         global $wpdb;
         $book_date       = $_POST['book_date'];
         list($startDate, $endDate) = explode(' - ', $_POST['book_date']);
         $property_id     = $_POST['property_id'];

      $date_list = $wpdb->get_results("SELECT booking_start_date, booking_end_date FROM wp_property_booking WHERE ((booking_start_date= '".$startDate."' or booking_end_date ='".$endDate."') and property_id = '".$property_id ."')");

        $array = json_decode(json_encode($date_list), True);
        $blank=array();
        // static $counter=0;
        foreach($array as $key=>$row){
        $blank[]=$row;
        // $counter++;
        }
  
       echo json_encode($array);
      exit;
      
    }

      public function db_utime_callback()
      {   
         global $wpdb;
         $property_id     = $_POST['property_id'];
     list($startDate, $endDate) = explode(' - ', $_POST['date_range']);

     $date_list = $wpdb->get_results("SELECT booking_time,dead_line FROM property_booking WHERE (property_id = '".$property_id ."' and booking_start_date= '".$startDate."' and booking_end_date ='".$endDate."')");
        $array = json_decode(json_encode($date_list), True);
        $booking_time=array();
        // static $counter=0;
        foreach($array as $key=>$row){
        $booking_time[]=$row;
        // $counter++;
        }
  
       echo json_encode($array);
      exit; 
    }
//////////////////////////////////////////////////////////////////
   public function delete_user_booking_callback()
      {  
         global $wpdb;
         $property_id     = $_POST['property_id'];
         $dead_line     = $_POST['dead_line'];
         $date_range     = $_POST['date_range'];
         $deletequery= $wpdb->query('DELETE  FROM '.$wpdb->prefix.'temporary_booking WHERE property_id = "'.$property_id.'" and dead_line = "'.$dead_line.'" and date_range = "'.$date_range.'"');
        if($deletequery){
          echo 'booking free successfully';
        }else{
          echo 'booking is not free';
        }
              exit; 
      }

//*********** Register temporary guest booking on property ***************//
   public function temporary_booking_callback()
   {   
     global $wpdb;
            $deadline     = $_POST['deadline'];
            $date_range   = $_POST['date_range'];
            $property_id  = $_POST['property_id'];
            $tmp_event_id = $_POST['tmp_event_id'];
            list($startDate, $endDate) = explode(' - ', $_POST['date_range']);
            echo $startDate;
            echo $endDate;
            $booking_time = date('Y-m-d h:i:s a', time()); 
           $result=array();

           $tmp_property_booking= $wpdb->insert("wp_temporary_booking",array(
                            "booking_time"  => $booking_time,
                            "dead_line"     => $deadline,
                            "property_id"   => $property_id,
                            "date_range"    => $date_range,
                            "co_status"     => '1',
                            "tmp_event_id"  =>$tmp_event_id,
                            "booking_start_date" =>$startDate,
                            "booking_end_date"   =>$endDate
                           ));
         if($tmp_property_booking) {
         $lastid = $wpdb->insert_id;
             $result=array($lastid);
             } else {
         $result=array(0);
          } 

 echo json_encode($result);
  } 

    //*********** fetch check old temporary bookings for timer purpose***************//
  public function check_old_tmp_bookings_callback()
   {   
         global $wpdb;
         $date_range       = $_POST['date_range'];
         $property_id     = $_POST['property_id'];
      
       $bookinglist = $wpdb->get_results("SELECT tmp_booking_id,dead_line FROM wp_temporary_booking WHERE (date_range= '".$date_range."' and property_id = '".$property_id ."')");

        $array = json_decode(json_encode($bookinglist), True);
      
       echo json_encode($array);
      exit;
      
    }
    //*********** fetch check old temporary bookings for booking check***************//
public function check_old_tmp_bookings_checkout()
   {   
         global $wpdb;
         $book_date      = $_POST['book_date'];
         $property_id     = $_POST['property_id'];
        list($startDate, $endDate) = explode(' - ', $_POST['book_date']);
        $bookinglist = $wpdb->get_results("SELECT booking_start_date,booking_end_date FROM wp_temporary_booking WHERE (booking_start_date= '".$startDate."' or booking_end_date= '".$endDate."' and property_id = '".$property_id ."')");

        $array = json_decode(json_encode($bookinglist), True);
      
       echo json_encode($array);
      exit;
      
    }


    //*********** property details for add to cart***************//
public function is_user_logged_in()
   {   
          $user = wp_get_current_user();
          $user->exists();
         // $array = json_decode(json_encode($bookinglist), True);
        echo $user->ID;
         // echo json_encode($array);
      exit;
      
    }
    // public function add_to_cart_ajax()
    //    {   global $woocommerce;
    //           $product_id =148;
    //           $property_id =$_POST['product_id']; 
    //           $date_range =$_POST['date_range']; 
    //           $min_price  =$_POST['min_price']; 
    //           $max_price  =$_POST['max_price'];
    //           $property_title     =$_POST['property_title'];
    //           $item_data[] = array(
    //               'property_id'  => $property_id,
    //               'noofmembers'   => 1,
    //               'date_range' => $date_range
    //           );
    //      $wookeyresult = $woocommerce->cart->add_to_cart( $product_id,1,'','',$item_data);
    //         if($wookeyresult!=""){
    //         $custom_data = WC()->cart->cart_contents[$wookeyresult];
    //         $custom_data['product_id'] = $_POST['product_id'];
    //         $custom_data['woocommerce_item_name'] = $_POST['property_title'];      
    //         // $product->set_name( $property_title );

    //         // print_r($custom_data);
    //      }

             
    //       exit;
          
    //     }

 public function wdm_add_user_custom_data_options_callback()
{ global $woocommerce;
              $product_id =148;
              $property_id   =$_POST['product_id']; 
              $date_range    =$_POST['date_range']; 
              $total_booking_price =$_POST['total_booking_price'];
              $property_title=$_POST['property_title'];
              $tanentfirstname=$_POST['tanentfirstname'];
              $tanentlastname=$_POST['tanentlastname'];
              $gmember_count=$_POST['gmember_count'];
              $item_data[]   = array(
                  'property_id'  => $property_id,
                  'tanentfirstname'=>$tanentfirstname,
                  'tanentlastname'=> $tanentlastname,
                  'gmember_count'   => $gmember_count,
                  'date_range'    => $date_range,
                  'total_booking_price'=>$total_booking_price,
                  'property_title'=>$property_title
              );

    
      session_start();
      $_SESSION['wdm_user_custom_data'] =  $item_data;
      $woocommerce->cart->add_to_cart(148,1,'','',$item_data);
  
       // print_r($_SESSION['wdm_user_custom_data']);
      die();
         
          exit;

}
 public function wdm_add_item_data($cart_item_data,$product_id)
    {
        /*Here, We are adding item in WooCommerce session with, wdm_user_custom_data_value name*/
        global $woocommerce;
        session_start();    
        if (isset($_SESSION['wdm_user_custom_data'])) {
            $option = $_SESSION['wdm_user_custom_data'];       
            $new_value = array('wdm_user_custom_data_value' => $option);
        }
        if(empty($option))
            return $cart_item_data;
        else
        {    
            if(empty($cart_item_data))
                return $new_value;
            else
                return array_merge($cart_item_data,$new_value);
        }
        unset($_SESSION['wdm_user_custom_data']); 
        //Unset our custom session variable, as it is no longer needed.
    }

 public function wdm_get_cart_items_from_session($item,$values,$key)
    {
        if (array_key_exists( 'wdm_user_custom_data_value', $values ) )
        {
       
        $item['wdm_user_custom_data_value'] = $values['wdm_user_custom_data_value'];
  
      $item['data']->set_price($values['wdm_user_custom_data_value'][0]['total_booking_price']);
      $item['data']->set_name($values['wdm_user_custom_data_value'][0]['property_title']);

        }       
        return $item;
    }

    public function cart_item_name_change($item,$values,$key){
    $item_name = $values[0]['property_title']; 
     return $item_name;
    }

  
    
     public function cart_item_thumbnails_change($item,$values,$key){

      $property_id = $values[0]['property_id']; 
      $item_name =get_the_post_thumbnail_url($property_id, 'post-thumbnail' );
      $imagsrc="<img src='$item_name' width='500' height='500'>"; 
      
     return $imagsrc; 

    }

 public function wdm_add_user_custom_option_from_session_into_cart($product_name, $values, $cart_item_key )
    {
        /*code to add custom data on Cart & checkout Page*/    
        if(count($values['wdm_user_custom_data_value']) > 0)
        {
            $return_string = $product_name . "</a><dl class='variation'>";
            $return_string .= "<table class='wdm_options_table' id='" . $values['product_id'] . "'>";
             $return_string .= "<tr><td>Tanent name = " . $values['wdm_user_custom_data_value'][0]['tanentfirstname'] ." ". $values['wdm_user_custom_data_value'][0]['tanentlastname'] ."</td></tr>";

             $return_string .= "<tr><td>Property name = " . $values['wdm_user_custom_data_value'][0]['property_title'] . "</td></tr>";

             $return_string .= "<tr><td>Boooking Dates = " . $values['wdm_user_custom_data_value'][0]['date_range'] . "</td></tr>";

             $return_string .= "<tr><td>No of Guest = " . $values['wdm_user_custom_data_value'][0]['gmember_count'] . "</td></tr>";
  
             $return_string .= "<tr><td>Amount=" . $values['wdm_user_custom_data_value'][0]['total_booking_price'] . "</td></tr>";

             $return_string .= "</table></dl>"; 
            return $return_string;
        }
        else
        {
            return $product_name;
        }
    }


// public function bbloomer_redirectcustom(){

//     if( is_checkout() && ! is_wc_endpoint_url( 'order-received' ) ){

//         //Set the messages for notice and button
//         $message = __( 'Do you want to go back to shopping cart?', 'woocommerce' );
//         $button_text = __( 'Go to shopping cart', 'woocommerce' );

//         $cart_link = WC()->cart->get_cart_url();

//         wc_add_notice( '<a href="' . $cart_link . '" class="button wc-forward">' . $button_text . '</a>' . $message, 'notice' );
//     }
// }


function misha_thank_you_title( $thank_you_title, $order ){
 
  return 'Dear ' . $order->get_billing_first_name() . ', Thank you so much for your order!';
 
}

// function wdm_add_custom_order_line_item_meta($item, $cart_item_key, $values, $order)
// {

//     if(array_key_exists('tenant_name', $values))
//     {
//         $item->add_meta_data('tenant_name',$values['tanentfirstname']);
//     }
// }


}
$objpostlist = new Postlist();


