<?php get_header(); ?>

<div class="middle">
	<div class="left">
		<div class="property">
			<h3>Property Search </h3>
			<form method="post" action="<?php echo get_post_type_archive_link( 'properties' ); ?>">
			<div class="buy">
				<input type="text" value="false" name="property_sale_type_sale" class="buybutton"/>
				<input type="text" value="false" name="property_sale_type_lease" class="leasebutton"/>
			</div>
			
			<script>
				(function($) { 
					$('.buybutton,.leasebutton').on("click", function(e) {
						e.preventDefault();
						$this = $(this);
						if($this.hasClass("active")) {
							$this.removeClass("active").val('false');	
						} else {
							$this.addClass('active').val('true');
						}
					});
				})(jQuery)
			</script>

			<div class="proper">
				<h5> Property Types </h5>
				<p><input type="checkbox" class="chick" name="property_type_investment" value="true"  /><span> Investment </span></p>
				<p><input type="checkbox" class="chick" name="property_type_office" value="true" /><span> Office </span></p>
				<p><input type="checkbox" class="chick" name="property_type_rental" value="true" /><span> Retail </span></p>
				<p><input type="checkbox" class="chick" name="property_type_industrial" value="true" /><span> Industrial </span></p>
				<p><input type="checkbox" class="chick" name="property_type_land" value="true" /><span> Land </span></p>
				<p><input type="checkbox" class="chick" name="property_type_mixed" value="true" /><span> Mixed </span></p>
			</div>
			
			<div class="proper">
				<h5>Space Requirement (Sq Ft)</h5>
				<p><input type="checkbox" class="chick" name="property_space_requirement[]" value="1000" /><span> 1,000 -5,000  </span></p>
				<p><input type="checkbox" class="chick" name="property_space_requirement[]" value="5000" /><span>5,000 -10,000  </span></p>
				<p><input type="checkbox" class="chick" name="property_space_requirement[]" value="10000" /><span>10,000 - 25,000 </span></p>
				<p><input type="checkbox" class="chick" name="property_space_requirement[]" value="25000"/><span> 25,000 up  </span></p>
				<p> <input type="submit" value="View Results" class="result" /></p> </form>
				</form>
				<div class="pdf">
					<!--<a href="#"> Download all properties </a>-->
				</div>
			</div>
	
			<div class="property">
			
				<h3>Contact Us </h3>
				<p class="listing">Looking to buy, lease, or list your property.</p>
				<a href="<?php echo get_permalink(54); ?>" class="us">Contact us</a>
			
			</div>
			
			<div class="property4">
				<h3 style="font-size:14px;">Join Mailing List</h3>
				<p class="listing"><b> Receive property related news and updates.</b></p>
				<a target="_blank" href="http://visitor.r20.constantcontact.com/d.jsp?llr=di7vz8cab&p=oi&m=1102592012547&sit=osdt8bgeb&f=6f963684-bd29-408c-b90f-60d1c023fed0" class="us">Join us</a>
			</div>
		
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
	.bio { font-size:14px; font-family:arial; width:160px;height:300px;float:left;margin-right:14px; margin-bottom:20px; }
	.bio span{margin-top:5px; float:left;clear:both; }
</style>
<?php 
//if(have_posts()) : while(have_posts()) : the_post();
//$custom = get_post_custom(get_the_ID());
$query_args = array(
    'post_type' => 'brokers',
    'posts_per_page' => 25);
$the_query = new WP_Query( $query_args );

if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post();
?>

<div class="bio"><a href="<?php the_permalink()?>">
		<?php the_post_thumbnail(); ?>
		<h3 style="font-weight:bold;color:#000;text-decoration:none;"><?php the_title(); ?></h3></a>
		<span style="margin-top:0;display:none;"><?php echo $custom['broker_title'][0];?></span>
		<span><b>Phone:</b> <?php echo $custom['broker_phone'][0];?></span>
		<span><b>Email:</b> <a href="mailto:<?php echo $custom['broker_email'][0];?>"><?php echo $custom['broker_email'][0];?></a></span>
		
</div>

<?php endwhile; endif; ?>
</div>


</div>










</div>


</div>

<?php get_footer(); ?>