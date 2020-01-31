<?php
/** 
 * Template Name: Property Infinity
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
get_header(); 

    $args3     = array(

		                'posts_per_page' => 3,
		                'post_type'      => 'property',
				    );

    $the_query = new WP_Query( $args3 ); 

?>

<div class="row">
<div class="col-md-4"> 
   <form action="" id="property_form_id"> 	
   	 <!-----   Display All address List  --------->
	   	  <div>
			 <h4> Location </h4>
			   <select class="js-example-basic-single form-control" name="location_address" id="location_address">
			      <option value = "0" id="location_address">Any
			      </option>

		          <?php $query = new WP_Query( array( 'post_type' => 'property','post_per_page'=> -1 ) );
		           if($query->have_posts()):
				   while( $query->have_posts() ) : 
		                	 $query->the_post(); ?>
		                 <option > 
		                 	<?php $add = get_field('address',get_the_id()); 
		                 	echo $add; ?>
		                 </option>
			   	   <?php 
		               endwhile;
                       endif;
 
			       ?>
		      </select>
		</div>
     <!-----   Display All Property Type List  --------->
		<div><h4> Property Status </h4>
		    <select class="js-example-basic-single form-control" name="property_status" id="property_status">
		      <option value="0" id="property_status">
		      	 Any
			 </option>
		   	<?php $categories = get_terms('status');
			$field = get_field_object('field_5ddfe1b700b2a');
			$choices = $field['choices'];
	         foreach ( (array) $choices as $category ) 
	         	{ ?>
	             <option >
	            	<?php echo $category; ?>
	             </option>
	      <?php } ?>
	    </select>
	  </div> 
		<div>
			<br>
			<input type="submit" class="btn btn-lg btn-success" id="sending_data" value ="Search">
		</div>

   </form>
   </div>
  
<div class="col-md-8"> 
	<div id="property_search"> </div>

	        <?php echo '<div id ="all_property">';

			 if ($the_query->have_posts() ) : ?>
			<?php
			// Start the loop.
			while ($the_query->have_posts() ) :
			 ?>
			   <div class="col-md-3">
			   </div><hr><?php

				$the_query->the_post();
				get_template_part( 'template-parts/content');

			endwhile;
		
			endif;
			   echo '</div></div>'; ?>
	   
	    <input type="hidden" id="pageno" value="1">
 </div>
<?php echo '<div id="property_post" class="property_post"></div>'; 
get_footer(); ?>

