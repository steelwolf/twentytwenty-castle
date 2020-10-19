<?php
/**
 * Template Name: Homepage Template
 * Template Post Type: page
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Castle
 * @since Twenty Twenty Castle 1.0.0
 */

 get_header();
 ?>

 <main id="site-content" role="main">

 	<?php

 	if ( have_posts() ) {

 		while ( have_posts() ) {
 			the_post();

 			get_template_part( 'template-parts/content', get_post_type() );
 		}
 	}

 	?>
	<?php get_template_part( 'template-parts/pagination' ); ?>

 </main><!-- #site-content -->

 <?php get_template_part( 'template-parts/footer-menus-widgets' ); ?>

 <?php get_footer(); ?>
