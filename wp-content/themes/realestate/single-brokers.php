<?php get_header(); 

$custom = get_post_custom(get_the_ID());
?>

<div class="middle">
  
  <div class="left">
    
    <div class="property">
      
      <h3>
        Property Search 
      </h3>
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
          <?php the_title(); ?>
        </h3>
        
        <span>
          <?php echo $custom['broker_title'][0]; ?>
        </span>
        
      </div>
      
      
      <div class="level">
        
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <?php the_content(); ?>
		<?php endwhile; endif; ?>

<div class="even">
  <?php the_post_thumbnail(); ?>
  
  <div class="address">
    
    <h4>
      <?php the_title(); ?>
    </h4>
    <span>
      <?php echo $custom['broker_title'][0]; ?>    
    </span>
    
    <p>
      <?php echo $custom['broker_address'][0]; ?>
    </br>
  Suite 
  104 
</br>
<?php echo $custom['broker_city'][0]; ?>, <?php echo $custom['broker_state'][0]; ?> <?php echo $custom['broker_zip'][0]; ?> 
</p>

<p>
  <b>
    Phone:
  </b>
  <?php echo $custom['broker_phone'][0]; ?>
  <b>
    Email:
  </b>
  <a href="mailto:<?php echo $custom['broker_email'][0]; ?>"><?php echo $custom['broker_email'][0]; ?></a>
</p>



</div>

<?php if($custom['broker_linkedin'][0] != "") { ?>
<a href="<?php echo $custom['broker_linkedin'][0]; ?>">
  
  <img src="<?php echo get_template_directory_uri(); ?>/images/linkedin_03.png" class="link" />
</a>
<?php } ?>
<?php if($custom['broker_vcard'][0] != "") { ?>
<a href="<?php echo $custom['broker_vcard'][0]; ?>">
  
  <img src="<?php echo get_template_directory_uri(); ?>/images/vcard_03.png" class="link" />
</a>
<?php } ?>






</div>


</div>


</div>










</div>


</div>

<?php get_footer(); ?>