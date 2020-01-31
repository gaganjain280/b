<?php
/**
 * The template for displaying all single posts and attachments
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
get_header(); ?>
<!-- <script src="https://apis.google.com/js/api.js?onload=handleClientLoad"></script> -->
<script src="https://apis.google.com/js/api.js"></script>
<div class="container" id="default_date_desable">
<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<div class="row">
		<?php
		// Start the loop.
		while ( have_posts() ) :
		?>
		 <div class="col-md-7">    
		 	<label for="price">Price:</label> <?php echo get_field('price');?>
		    <label for="status">Status:</label>  <?php the_field('status'); ?>
		 	<br><label for="address">Address:</label> <?php echo get_field('address');?>
            <label for="facility">Facilities:</label> <?php the_field('amenities'); ?>
	
  	<?php the_post();
     

          ?>
            <?php
		 // echo get_the_post_thumbnail('thumbnail' );	

			get_template_part( 'template-parts/content', 'single' );   
          ?> <span class="responce_timer" id="responce_timer"></span> 
          <div id="clockdiv"> 
             <input type="hidden" name ="property_title" id="property_title" value="<?php echo get_the_title(); ?>" readonly/>  

            <label for="">title:</label> 

          <div> 
            <span class="minutes" id="minute"></span> 
            <div class="smalltext">Minutes</div> 
          </div> 
          <div> 
            <span class="seconds" id="second"></span> 
            <div class="smalltext">Seconds</div> 
          </div> 
        </div> 
          <input id="temp_timer" style="border: 0px none;" readonly/>     
             <input type="hidden"  id="submit_timer_value" value="0" readonly/>  
          </div>
        </div><div class="row">
        <form name="guest_form" id="Bookform">
          <div class="col-sm-6" id="tanent_data">
           <!-- <tbody id="tanent_include"> -->
		       <label for="fname">Tenant Name</label>
		        <input type="text" id="tanentfirstname" name="tanentfirstname" placeholder="First Name" />
	       </div>
		      <div class="col-sm-6">
		   	  <label for="lname">Last Name</label>
		   	    <input type="text" id="tanentlastname" name="tanentlastname" placeholder=" Last name" />
         </div>
         <h4>
           <input type="checkbox" value="" id="guestcheckbox" name="guestcheckbox"/> Guest Option: (Note: per guest $20 extra.)
           <button class="btn btn-info add_guest" id="add_guest" name="add_guest" desabled/>
            Add Guest
          </button><br><br>
          <span style="color:black;">Extra payble Amount ($).</span>
          <input type="text" name="booking_amount" id="booking_amount" value="0" class="form-control"style="width:20%" readonly/>
         </h4> <br>  
            <input type="hidden" id="property_id" value="<?php echo get_the_ID(); ?>">
             <table class="table table-bordered" id="guest_data" cellpadding="11">
              <tbody id="guest_include">
               <tr>
                 <td style="font-size:15px;"><span>1</span></td>
                 <input type="hidden" value="0" id="guest_id">
                 <td><input type="text" class="form-control" placeholder="Enter First name" id="guest_first_name" name="guest_first_name[]" onchange="guestfnameValidate(this.value);"/></td>
                 <td><input type="text" class="form-control" placeholder="Enter Last  name" id="guest_last_name" name="guest_last_name[]" onchange="guestlnameValidate(this.value);"/></td>
                 <td><i class="glyphicon glyphicon-remove"  style="color:red;" id="delete_guest"  ><span class="glyphicon glyphicon-remove"></span> Remove </i></td>
              </tr>
             </tbody>
            </table>
            <span id="errmsg" style="color:red;"></span>
              <h5>Select Date:</h5>
               <input type="text" id="daterange" value="" />
              <br><br>
            <span style="color:black;">Total Amount (100 $)+ (Guest Charge $) .</span>
          <input type="text" name="total_booking_amount" id="total_booking_amount" value="100" class="form-control"style="width:20%" readonly/> 
          <button type="button" class="btn btn-danger btn-lg btn-block" style="font-weight:bold;" id="check_out" desabled/> ADD TO CART</button>
         <!--  <button type="button" class="btn btn-info" style="font-weight:bold;" id="register" desabled /> Booking</button> -->
            <center><img id="loader" src="http://thinkfuture.com/wp-content/uploads/2013/10/loading_spinner.gif" style="width:12%;height:12%;display:none;" />
            </center>
          <input type="hidden" name="tmp_event_id" id="tmp_event_id" value="" class="form-control"style="width:20%" readonly/> 
          <input type="hidden" name="tmp_booking" id="tmp_booking" value="" class="form-control"style="width:20%" readonly/> 
          <input type="hidden" name="checkout_status" id="checkout_status" value="" class="form-control"style="width:20%" readonly/> 
           <input type="hidden"  id="already_bookings_counts" value="0" readonly/> 
    </form>  
          </div><?php
			if ( is_singular( 'attachment' ) ) {
				the_post_navigation(
					array(
						'prev_text' => _x( '<span class="meta-nav">Published in</span><span class="post-title">%title</span>', 'Parent post link', 'twentysixteen' ),
					)
				);
			} elseif ( is_singular( 'post' ) ) {
				// Previous/next post navigation.
				the_post_navigation(
					array(
						'next_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Next', 'twentysixteen' ) . '</span> ' .
							'<span class="screen-reader-text">' . __( 'Next post:', 'twentysixteen' ) . '</span> ' .
							'<span class="post-title">%title</span>',
						'prev_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Previous', 'twentysixteen' ) . '</span> ' .
							'<span class="screen-reader-text">' . __( 'Previous post:', 'twentysixteen' ) . '</span> ' .
							'<span class="post-title">%title</span>',
					)
				);
			}

			// End of the loop.
		endwhile;
		// print_r($woocommerce);

    ?>

	</main><!-- .site-main -->

	<?php get_sidebar( 'content-bottom' ); ?>

</div></div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
<style type="text/css">
	.disabled {
    color:#cecece;
}
#picker {
    width:12em;
    margin:1em;
}
p {
  text-align: center;
  font-size: 30px;
  margin-top: 0px;
}
</style>
<style> 
body{ 
    text-align: center; 
    background: #ffff; 
  font-family: sans-serif; 
  font-weight: 100; 
} 
h1{ 
  color: #396; 
  font-weight: 100; 
  font-size: 40px; 
  margin: 40px 0px 20px; 
} 
 #clockdiv{ 
    font-family: sans-serif; 
    color: #fff; 
    display: inline-block; 
    font-weight: 100; 
    text-align: center; 
    font-size: 30px; 
} 
#clockdiv > div{ 
    padding: 10px; 
    border-radius: 3px; 
    background: #00BF96; 
    display: inline-block; 
} 
#clockdiv div > span{ 
    padding: 15px; 
    border-radius: 3px; 
    background: #00816A; 
    display: inline-block; 
} 
smalltext{ 
    padding-top: 5px; 
    font-size: 16px; 
} 
</style> 
<script type="text/javascript">
	function guestfnameValidate(inputtxt)
   {
      var value1 = /^[A-Z,a-z]+$/;
      if(inputtxt.match(value1)) {return true;}
      else{
         alert('Guest first Name must be character');
            return false;
      }
   }function guestlnameValidate(inputtxt)
   {
      var value1 = /^[A-Z,a-z]+$/;
      if(inputtxt.match(value1)) {return true;}
      else{
         alert('Guest last Name must be character');
            return false;
      }
   }
</script>