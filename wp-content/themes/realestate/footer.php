<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package realestate
 */
?>

</section>

<div class="clear"></div>
<footer class="last">

<div class="last_left">



<a href="#"><img src="<?php echo get_template_directory_uri(); ?>/images/Home-2_03.png" class="hs"/></a>




<a href="#"><img src="<?php echo get_template_directory_uri(); ?>/images/logo2_03.png" class="hs"/></a>





<a href="#"><img src="<?php echo get_template_directory_uri(); ?>/images/clux_03.png" class="hs"/></a>



</div>

<div class="footer_menu">
<?php wp_nav_menu( array( 'theme_location' => 'footer' ) ); ?>


<p>Markeim-Chalmers, Inc.</br>
Cherry Hill Plaza - Suite 500, 1415 Route 70 East, Cherry Hill, NJ 08034 </br>
Website design: First Dynamic</p>

</div>


</footer>

</div>

	

<?php wp_footer(); ?>

</body>
</html>
