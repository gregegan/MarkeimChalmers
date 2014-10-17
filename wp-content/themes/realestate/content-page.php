<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package realestate
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<div class="feature">
	<h3><?php the_title(); ?></h3>
	</div>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'realestate' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->
	<?php edit_post_link( __( 'Edit', 'realestate' ), '<footer class="entry-footer"><span class="edit-link">', '</span></footer>' ); ?>
</article><!-- #post-## -->
