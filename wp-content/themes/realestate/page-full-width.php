<?php
 /*
 Template Name: Full Width
 */

get_header(); ?>

<div class="middle">

<div class="slide">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>

			<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->
</div>
	</div>

<?php // get_sidebar(); ?>
<?php get_footer(); ?>
