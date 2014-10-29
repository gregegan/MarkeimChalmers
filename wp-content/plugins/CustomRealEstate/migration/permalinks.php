<?php echo $_SERVER['DOCUMENT_ROOT']; 
define( 'WP_USE_THEMES', false );  
require('/home/gregegan/public_html/markeim/wp-blog-header.php'); 

if($_GET['type'] == "properties") {
	$args=array(
	  'post_type' => 'properties',
	  'post_status' => 'publish',
	  'posts_per_page' => -1,
	  'caller_get_posts'=> 1
	);
	$my_query = null;
	$my_query = new WP_Query($args);
	if( $my_query->have_posts() ) {
	  while ($my_query->have_posts()) : $my_query->the_post(); ?>
	    <p><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a>
	    <br>
	    http://hybridhosting.net/markeim/?p=<?php the_id(); ?>
	    <br>
	    http://markeim-chalmers.com/?p=<?php the_id(); ?>
	    </p>
	    <?php
	  endwhile;
	}
	wp_reset_query(); 
} else if($_GET['type'] == "news") {
	$args=array(
	  'post_status' => 'publish',
	  'posts_per_page' => -1,
	  'caller_get_posts'=> 1
	);
	$my_query = null;
	$my_query = new WP_Query($args);
	if( $my_query->have_posts() ) {
	  while ($my_query->have_posts()) : $my_query->the_post(); ?>
			 <p><a href="<?php the_permalink() ?>"><?php the_title(); ?></a>
			  <br>
			    http://hybridhosting.net/markeim/?p=<?php the_id(); ?>
			    <br>
			    http://markeim-chalmers.com/?p=<?php the_id(); ?>
			    </p>

 <?php
	  endwhile;
	}
	wp_reset_query(); 
}
?>
