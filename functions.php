<?php
add_action( 'wp_enqueue_scripts', 'twentytwenty_castle_enqueue_styles' );
function twentytwenty_castle_enqueue_styles() {
  wp_enqueue_style( 'twentytwenty-style', get_template_directory_uri() . '/style.css',
    wp_get_theme()->parent()->get('Version')
  );
  wp_enqueue_style( 'twentytwenty-child-style', get_stylesheet_uri(),
    array( 'twentytwenty-style' ),
    wp_get_theme()->get('Version') // this only works if you have Version in the style header
  );
}

// Recent Posts widget
require get_stylesheet_directory() . '/template-parts/related-posts.php';

//Estimated reading time based on Medium: https://blog.medium.com/read-time-and-you-bc2048ab620c.
function reading_time() {
  $content = get_post_field( 'post_content', $post->ID );
  $count_images = substr_count( strtolower( $content ), '<img ');
  $content = wp_strip_all_tags( $content );
  $word_count = count( preg_split( '/\s+/', $content ) );
  $extra_time_for_images = reading_time_images( $count_images );
  // Take word count, add the "words" from images, and convert to minutes based on reading speed of 275 wpm.
  $reading_time = ceil( ( $word_count + $extra_time_for_images ) / 275 );

  // Set to < 1 minute if 0, otherwise deal with singular vs plural.
  if ( 1 > $reading_time) {
    $timer = __( "< 1 minute");
  } elseif ($reading_time == 1) {
    $timer = " minute";
  } else {
    $timer = " minutes";
  }

  // Add the text ending to the time.
  $final_reading_time = $reading_time . $timer;

  return $final_reading_time;
}

function reading_time_images( $total_images ) {
  $extra_time = 0;
  // Add 12 seconds for first image, 11 for the second, etc. and add 3 seconds for >= 10 images.
  for ( $i = 1; $i <= $total_images; $i++ ) {
    if ( $i >= 10 ) {
      $extra_time += 3;
    } else {
      $extra_time += 12 - ( $i - 1 );
    }
  }
  // convert seconds to words based on Medium's reading speed of 275 wpm.
  $extra_time = $extra_time * (int) 275 / 60;
  return $extra_time;
}

// Post meta: Output the modified_date post meta item
function twentytwenty_castle_post_meta_output_reading_time( $post_id, $location ) {
	global $has_meta;
	$has_meta = true;
	?>
	<li class="post-date meta-wrapper">
		<span class="meta-icon">
			<span class="screen-reader-text"><?php esc_html_e( 'Estimated reading time', 'chaplin-child' ); ?></span>
		</span>
		<span class="meta-text">
			<?php printf( __( 'Read time: %s', 'twentytwenty-castle' ), reading_time() ); ?>
		</span>
	</li>
	<?php
}
