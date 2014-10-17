<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package realestate
 */

get_header(); ?>

<div class="middle">

<div class="left">

<div class="property">

<h3>Property Search </h3>
<?php wp_nav_menu( array( 'theme_location' => 'property-search', 'menu_class' => 'overview' ) ); ?>
</div>


<div class="property">

<h3>Contact Us </h3>


<p class="listing">Looking to buy, lease, or list your property.</p>

<a href="<?php echo get_permalink(54); ?>" class="us">Contact us</a>


</div>



<div class="property4">

<h3>Join Mailing List </h3>


<p class="listing"><b> Receive property related news and updates.</b>
</p>

<a target="_blank" href="http://visitor.r20.constantcontact.com/d.jsp?llr=di7vz8cab&p=oi&m=1102592012547&sit=osdt8bgeb&f=6f963684-bd29-408c-b90f-60d1c023fed0" class="us">Contact us</a>


</div>





</div>


<div class="right">

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
