<?php 
//get_header();
class DesignPattern{
  public function __construct() {   
    // initie enque
     add_action( 'wp_enqueue_scripts',array( $this,'factory_scripts' ) );
     // add_action( 'wp_ajax_load_posts_by_ajax',array( $this,'load_posts_by_ajax_callback' ) ); 
    
    // //property filter initiate ajax 
    //  add_action( 'wp_ajax_search_property_by_ajax',array( $this,'filter_property' ) ); 
    //  add_action( 'wp_ajax_nopriv_search_property_by_ajax',array( $this,'filter_property' ) );


     // add_action( 'wp_ajax_factory_call_ajax',array( $this,'factorycall' ) ); 
     // add_action('wp_ajax_nopriv_factory_call_ajax',array($this,'factorycall' )); 

  }


 //****************** ENQUEUE SCRIPT FILE  *******************//
  public function factory_scripts() {

    wp_enqueue_script( 'jquery' );

    wp_register_script( 'my_loadmore', get_template_directory_uri() . '/js/designPattern.js', array( 'jquery' ) );

    wp_localize_script( 'my_loadmore', 'misha_loadmore_params', array( 'ajaxurl' =>admin_url( 'admin-ajax.php' ) ) );
    wp_enqueue_script( 'my_loadmore' );
   }


// **************Seaarch property********************//



  // public function filter_property(){
  //   $location_address;
  //   $property_status;
  //   $metaquery=array();
  //   $argument=array();
  //   $location_address=$_POST['location_address'];
  //   $property_status=$_POST['property_status'];

  //     if(($location_address=="0")&&($property_status=="0")){

  //       $argument  = array(          
  //                       'post_type'      => 'property'
  //                       );
      
  //     }elseif(($location_address!=="0")&&($property_status!=="0")){

  //       $metaquery = array(
  //                            'relation'  => 'AND',
  //                         array(
  //                              'key'     => 'address',
  //                              'value'   =>  $location_address,    // GET ADDRESS OF PROPERTY
  //                              'type'    => 'CHAR',
  //                              'compare' => '='
                               
  //                         ),
  //                         array(
  //                              'key'     => 'status',
  //                              'value'   =>  $property_status,    // GET ADDRESS OF PROPERTY
  //                              'type'    => 'CHAR',
  //                              'compare' => '='
  //                         )
  //                      ); 
  //       $argument  = array(          
  //                       'post_type'      => 'property',
  //                       'meta_query'     => $metaquery 
  //                       );
  //     }
  //      else{
  //       $metaquery = array(
  //                            'relation'  => 'OR',
  //                         array(
  //                              'key'     => 'address',
  //                              'value'   =>  $location_address,    // GET ADDRESS OF PROPERTY
  //                              'type'    => 'CHAR',
  //                              'compare' => '='  
  //                         ),
  //                         array(
  //                              'key'     => 'status',
  //                              'value'   =>  $property_status,    // GET ADDRESS OF PROPERTY
  //                              'type'    => 'CHAR',
  //                              'compare' => '='
                               
  //                         )
  //                      ); 
  //         $argument  = array(          
  //                       'post_type'      => 'property',
  //                       'meta_query'     => $metaquery 
  //                       );
  //      }   

  //     $itemquery1 = new WP_Query($argument); 
  //       if ( $itemquery1->have_posts() ) : 
  //         while ( $itemquery1->have_posts() ) : $itemquery1->the_post(); 
  //         get_template_part( 'template-parts/content');
  //        endwhile;
  //      endif;
  // }

}
$objDesignPattern = new DesignPattern();


