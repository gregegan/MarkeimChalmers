<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package realestate
 */

get_header(); ?>



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
				<h5> Space Requirement </br>(Sq Ft)" </h5>
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
		</div>

		<div class="property">
			<h2>Appraisals and tax appeals </h2>
			<p class="para">Property Valuation is a key ingredient to Financing, Appealing your Tax Assessment, or selecting a fair Sales Price or Lease Rate. Our Appraisal Department completes approximately 200 commercial and 300 residential appraisals each year. Our data base includes thousands of property sales and lease agreements in the area. Our firm has had the privilege of being hired by every local, regional and national lending institution located in the Southern New Jersey market.</p>
			<p class="drop"> Our team of experts will take an assignment related to: </p>
			<ul class="small"> 
				<li>Bank Financing or Refinancing</li>
				<li>Condemnation</li>
				<li>Tax Appeals</li>
				<li>Highest and Best Use</li>
				<li>Partnership Resolution</li>
			</ul>
		</div>
	</div>


	<div class="right">
		<div class="slide">
			<div class="feature">
				<h3>Featured Listings</h3>
				<a href="<?php echo get_post_type_archive_link( 'properties' ); ?>">View all listings </a>
			</div>
			
			<div class="slider">
				<?php echo do_shortcode( '[advps-slideshow optset="1"]' ); ?>
			</div>
		</div>
		
		<?php 
			$id=156; 
			$post = get_post($id); 
			$content = apply_filters('the_content', $post->post_content); 
			echo $content;
		?>
	
		<!--<div class="slide">	
			<div class="feature">
				<h3>Reliable Answers</h3>
			</div>
		
			<div class="buy">
				<div class="grid">
					<img src="<?php echo get_template_directory_uri(); ?>/images/thum_03.jpg" class="thum" />
					<div class="office">
						<h5>Tenants / Buyers</h5>
						<p>Need commercial space? Can't decide whether to buy or rent? Not sure of how much space you need? Click here for answers.</p>
					</div>
				</div>
			
				<div class="grid2">
					<div class="office">
						<h5>By the numbers</h5>
						<p class="over"><span> Over </span>500 million <span> in 5 Yrs </span></p>
						<p class="over1">130 listings  </p>
						<p class="over2">2.5 million <span> SF represented </span></p>
						<p class="over3">600 Appraisals  <span> per Yr </span> </p>
						<p class="over4"><span> Over</span> 1,000 clients <span> served</span></p>
					</div>
				</div>
			</div>
			
			
			<div class="buy1">
				<div class="grid">
				<img src="<?php echo get_template_directory_uri(); ?>/images/black_thum_03.jpg" class="thum2" />
					<div class="office">
						<h5>Landlords / Sellers</h5>
						<p>Ready to sell or lease your commercial building? Not sure of property values or rental rates? Click here for answers.</p>
					</div>
				</div>
				
				<div class="grid2">
					<div class="office">
						<h5>Commercial Real Estate Services</h5>
						<ul> 
							<li>Sales and Leasing</li>
							<li>Investment Sales</li>
							<li>Appraisals</li>
							<li>Tax Appealse</li>
							<li>Property Management</li>
							<li>Receivership</li>
						</ul>
					</div>
				</div>
			</div>
		</div>-->

		<div class="slide2">
			<div class="feature">
				<h3>Recent Deals</h3>
				<!--<img src="<?php echo get_template_directory_uri(); ?>/images/screoll_03.jpg" class="scroll" />-->
			</div>

			<div class="catagory">
				<?php
				$count = 1;
				$args = array( 'numberposts' => '6', 'category' => '7' );
				$recent_posts = wp_get_recent_posts( $args );
				foreach( $recent_posts as $recent ){		
				?>
					<div class="box1<?php /*if($count < 4) { echo "box1"; } else { echo "box2"; }*/?>" <?php if($count == 4) { echo "style='clear:both;'"; }?>>
						<a href="<?php echo get_permalink($recent["ID"]); ?>"><?php echo get_the_post_thumbnail($recent["ID"], 'thumbnail', array('alt'=>trim(strip_tags( $recent["post_title"] ))));  ?></a>
						<p><?php echo $recent["post_title"]; ?></p>
						<div class="act">
							<?php echo $custom['property_comments'][0]; ?>
						</div>
						<a href="<?php echo get_permalink($recent["ID"]); ?>">Read more</a>
					</div>
				<?php 
					$count ++;
				}
				?>
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>
