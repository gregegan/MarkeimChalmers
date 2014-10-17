<?php get_header(); ?>

<div class="middle">
  
  <div class="left">
    

	<div class="property">

	<h3>Property Search </h3>
	<?php wp_nav_menu( array( 'theme_location' => 'property-search', 'menu_class' => 'overview' ) ); ?>
	</div>
    
    <div class="property">
      
      <h3>
        Contact Us 
      </h3>
      
      
      <p class="listing">
        Looking to buy, lease, or list your property.
      </p>
      
      <a href="<?php echo get_permalink(54); ?>" class="us">
        Contact us
      </a>
      
      
    </div>
    
    
    
    <div class="property4">
      
      <h3>
        Join Mailing List 
      </h3>
      
      
      <p class="listing">
        <b>
          Receive property related news and updates.
        </b>
      </p>
      
      <a href="<?php echo get_permalink(54); ?>" class="us">
        Contact us
      </a>
      
      
    </div>
    
    
    
    
    
  </div>
  
  
  <div class="right">
    
    <div class="slide">
      
      <div class="feature">
        
        <h3>
          Staff Profiles
        </h3>
        
      </div>
      
      
<div class="level">
<style>
	.bio { font-size:14px; font-family:arial; width:160px;height:370px;float:left;margin-right:14px; margin-bottom:20px; }
	.bio span{margin-top:15px; float:left;clear:both; }
</style>
<?php 
if(have_posts()) : while(have_posts()) : the_post();
$custom = get_post_custom(get_the_ID());
?>

<div class="bio"><a href="<?php the_permalink()?>">
		<?php the_post_thumbnail(); ?>
		<h3 style="font-weight:bold;color:#000;text-decoration:none;"><?php the_title(); ?></h3></a>
		<span style="margin-top:0;"><?php echo $custom['broker_title'][0];?></span>
		<span><b>Phone:</b> <?php echo $custom['broker_phone'][0];?></span>
		<span><b>Email:</b> <a href="mailto:<?php echo $custom['broker_email'][0];?>"><?php echo $custom['broker_email'][0];?></a></span>
		
</div>

<?php endwhile; endif; ?>
</div>


</div>










</div>


</div>

<?php get_footer(); ?>