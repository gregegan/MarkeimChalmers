<?php
 /*
 Template Name: News
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

<a target="_BLANK" href="http://visitor.r20.constantcontact.com/d.jsp?llr=di7vz8cab&p=oi&m=1102592012547&sit=osdt8bgeb&f=6f963684-bd29-408c-b90f-60d1c023fed0" class="us">Contact us</a>


</div>





</div>


<div class="right">

<div class="slide">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="news">
			<?php 
				$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
				$args = array(
					  'posts_per_page' => 5,
					  'paged' => $paged
					);
					
					query_posts($args); 
			?>
			<?php while ( have_posts() ) : the_post(); ?>
				<div class="article">

					<?php the_post_thumbnail('thumbnail'); ?>

					<div class="position">
						
					<h4><?php the_title(); ?></h4>
					<span><?php the_date(); ?></span>
						<div style="color: #808080;padding-top: 3px;">
			<?php
			if(sizeof(get_the_category()) > 0) {
				?>
				Tags: 
				<?php
				foreach((get_the_category()) as $category) { 
				    echo $category->cat_name . ' '; 
				} 
			}
			?>
		</div>

					<p><?php the_excerpt(); ?></p>
					<a href="<?php echo the_permalink(); ?>">Read more</a>

					</div>
				</div>
					
				<?php //get_template_part( 'content', 'page' ); ?>


			<?php endwhile; // end of the loop. ?>
			</div>
			<?php realestate_paging_nav(); ?>
		</main><!-- #main -->
	</div><!-- #primary -->
	</div>
	</div>

<?php // get_sidebar(); ?>
<?php get_footer(); ?>
