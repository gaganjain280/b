<?php
/**
 * The template part for displaying content
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
			<span class="sticky-post"><?php _e( 'Featured', 'twentysixteen' ); ?></span>
		<?php endif; ?>
     <center><h1>
	<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
	</h1></center>
	</header><!-- .entry-header -->
<div class="entry-content col-md-12" >
	<?php twentysixteen_excerpt(); ?>
	<div class="row">
		<div class="entry-content col-md-4" >
  	<?php  twentysixteen_post_thumbnail(); ?>			
        </div> 
        <?php 
			wp_link_pages(
				array(
					'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentysixteen' ) . '</span>',
					'after'       => '</div>',
					'link_before' => '<span>',
					'link_after'  => '</span>',
					'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'twentysixteen' ) . ' </span>%',
					'separator'   => '<span class="screen-reader-text">, </span>',
				)
			);
			?>
				<div class="col-md-7">           
			<label for="address">Address:</label>
               <?php echo get_field('address');?>
		        <br><label for="price">Price:</label>
               <?php echo get_field('price');?>
		        <br><label for="price">Status:</label>  <?php the_field('status'); ?>
                <br><label for="price">Amenities:</label> <?php the_field('amenities'); ?>
	    </div> 
	</div><!-- .entry-content -->
	</div>
	<footer class="entry-footer">
		
		<?php
			edit_post_link(
				sprintf(
					/* translators: %s: Post title. */
					__( 'Edit<span class="screen-reader-text"> "%s"</span>', 'twentysixteen' ),
					get_the_title()
				),
				'<span class="edit-link">',
				'</span>'
			);
			?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
