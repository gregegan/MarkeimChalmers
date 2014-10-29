<?php
/*
	Plugin Name: Advanced post slider
	Plugin URI: www.wpcue.com
	Description: Advanced post slider is a slideshow plugin powered with three built-in templates, lots of effect, easy customizable options and many more to explore.
	Version: 1.2.1
	Author: digontoahsan
	Author URI: www.wpcue.com
	License: GPL2
*/

	function advps_modify_menu(){
		add_menu_page( 'Advanced post slider', 'Adv. Slider', 'manage_options', 'advps-slideshow', 'advps_options' );
	}
	
	add_action('admin_menu','advps_modify_menu');

	function advps_options(){
		include('advps-admin.php');
	}
	
	define('advps_url',WP_PLUGIN_URL."/advanced-post-slider/");
	
	global $advpsQuery;
	global $advpsQuery2;
	global $advpsEffects;
	global $templateOneDefaultOptions;
	global $templatetwoDefaultOptions;
	global $templatethreeDefaultOptions;
	
	$advpsQuery = array(
			"advps_post_types" => "post",
			"advps_maxpost" => "10",
			"advps_exclude" => "", 
			"advps_order_by" => "date",
			"advps_order" => "DESC"
	);
	$advpsQuery2 = array(
			"advps_post_types" => "post",
			"advps_maxpost" => "10",
			"advps_postperslide" => "1",
			"advps_exclude" => "", 
			"advps_order_by" => "date",
			"advps_order" => "DESC"
	);
	$advpsEffects = array(
			"advps_effects" => "uncover",
			"advps_timeout" => "1500",
			"advps_easing" => "linear",
			"advps_speed" => "2000",
			"advps_ps_hover" => "yes"
	);
		
	$templateOneDefaultOptions = array(
			"advps_thumbnail" => "advps_thumbnail_one",
			"advps_default_image" => "",
			"advps_sld_width" => "820",
			"advps_sld_height" => "270",
			 "advps_contpad1" => "10",
			"advps_contpad2" => "10",
			"advps_contpad3" => "10",
			"advps_contpad4" => "10",
            "advps_bgcolor" => "#FFFFFF",
			"advps_border_size" => "1", 
			"advps_border_type" => "solid",
			"advps_border_color" => "#444444", 
			"advps_remove_border" => "yes",
			"advps_bxshad1" => "0", 
			"advps_bxshad2" => "1", 
			"advps_bxshad3" => "4", 
			"advps_bxshadcolor" => "#000000", 
			"advps_remove_shd" => "no",
			"advps_overlay_width" => "30", 
			"advps_overlay_height" => "100", 
			"advps_overlay_color" => "#000000", 
			"advps_overlay_opacity" => "0.6", 
			"advps_text_opacity" => "1", 
			"advps_ttitle_tag" => "h2",
			"advps_titleFcolor" => "#FFFFFF",
			"advps_titleHcolor" => "#E6E6E6", 
			"advps_titleFsize" => "22", 
			"advps_excptFcolor" => "#FFFFFF", 
			"advps_excptFsize" => "12", 
			"advps_excerptlen" => "30", 
			"advps_excpt_visibility" => "always show",
			"advps_excpt_position" => "left",
			"advps_ed_link" => "enable",
			"advps_link_type" => "permalink",
			"advps_link_target" => "_self",
			"advps_exclude_pager" => "no",
			"advps_pager_type" => "bullet",
			"advps_pager_right" => "8",
			"advps_pager_bottom" => "6",
			"advps_exclude_nxtprev" => "no",
			"advps_exclude_playpause" => "no"
	);
	$templatetwoDefaultOptions = array(
			"advps_thumbnail" => "advps_thumbnail_two",
			"advps_sld_width" => "644",
			"advps_sld_height" => "270",
			"advps_contpad1" => "10",
			"advps_contpad2" => "10",
			"advps_contpad3" => "10",
			"advps_contpad4" => "10",
			"advps_bgcolor" => "#FFFFFF",
			"advps_border_size" => "1", 
			"advps_border_type" => "solid",
			"advps_border_color" => "#444444", 
			"advps_remove_border" => "yes",
			"advps_bxshad1" => "0", 
			"advps_bxshad2" => "1", 
			"advps_bxshad3" => "4", 
			"advps_bxshadcolor" => "#000000", 
			"advps_remove_shd" => "no",
			"advps_img_Orientation" => "horizontal",
			"advps_imagemar" => "10",
			"advps_ed_link" => "enable",
			"advps_link_type" => "permalink",
			"advps_link_target" => "_self",
			"advps_exclude_pager" => "no",
			"advps_pager_type" => "bullet",
			"advps_pager_right" => "8",
			"advps_pager_bottom" => "6",
			"advps_exclude_nxtprev" => "no",
			"advps_exclude_playpause" => "no"
	);
	$templatethreeDefaultOptions = array(
			"advps_content_set" => array(0=>"thumb",1=>"title",2=>"excerpt"),
			"advps_thumbnail" => "medium",
			"advps_sld_width" => "624",
			"advps_sld_height" => "225",
			"advps_contpad1" => "10",
			"advps_contpad2" => "10",
			"advps_contpad3" => "10",
			"advps_contpad4" => "10",
			"advps_bgcolor" => "#FFFFFF",
			"advps_border_size" => "1",
			"advps_border_type" => "solid",
			"advps_border_color" => "#444444",
			"advps_remove_border" => "yes",
			"advps_bxshad1" => "0",
			"advps_bxshad2" => "1",
			"advps_bxshad3" => "4",
			"advps_bxshadcolor" => "#000000",
			"advps_remove_shd" => "no",
			"advps_cont_width" => "280",
			"advps_ttitle_tag" => "h2",
			"advps_titleFcolor" => "#000000", 
			"advps_titleHcolor" => "#E6E6E6", 
			"advps_titleFsize" => "22", 
			"advps_excptFcolor" => "#333333", 
			"advps_excptFsize" => "12", 
			"advps_excerptlen" => "30",
			"advps_ed_link" => "enable",
			"advps_link_type" => "permalink",
			"advps_link_target" => "_self",
			"advps_exclude_pager" => "no",
			"advps_pager_type" => "bullet",
			"advps_pager_right" => "8",
			"advps_pager_bottom" => "6",
			"advps_exclude_nxtprev" => "no",
			"advps_exclude_playpause" => "no"
	);
	/* ---------------------------------------------------------------------------------*/
	function advps_enqueue() {		
		
		$pluginsURL = WP_PLUGIN_URL."/advanced-post-slider/";
			wp_register_style('advpsStyleSheet', advps_url.'advps-style.css');
			wp_enqueue_style( 'advpsStyleSheet');

			wp_enqueue_script('jquery');
    		wp_enqueue_script( 'advps_jcycle',advps_url.'js/jquery.cycle.all.js',array( 'jquery' ) );
			wp_enqueue_script( 'advps_easing',advps_url.'js/jquery.easing.1.3.js',array( 'jquery' ) );
			wp_enqueue_script( 'advps_front_script',advps_url.'js/advps.frnt.script.js' );
	
	}
	add_action( 'wp_enqueue_scripts', 'advps_enqueue' );
	
	function load_custom_wp_admin_style() {
		global $wp_version;		
		if(isset($_GET['page'])){
		$pgslug = $_GET['page'];
		$menuslug = array('advps-slideshow');
		
			if(!in_array($pgslug,$menuslug))
        		return;
       		 
			if($wp_version >= '3.5'){
				wp_enqueue_script( 'wp-color-picker' );
				wp_enqueue_style( 'wp-color-picker' );
			}
			else
			{
				wp_enqueue_style( 'farbtastic' );
  				wp_enqueue_script( 'farbtastic' );
			}
			wp_enqueue_script( 'advps-js-script', advps_url . 'js/advps.script.js', array( 'jquery' ) );
			wp_localize_script( 'advps-js-script', 'advpsajx', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ),'advpsAjaxReqChck' => wp_create_nonce( 'advpsauthrequst' )));
			
		}
	
	}
	add_action( 'admin_enqueue_scripts', 'load_custom_wp_admin_style' );
	
	/* ---------------------------------------------------------------------------------------*/
	
	register_activation_hook(WP_PLUGIN_DIR.'/advanced-post-slider/advanced-post-slider.php','set_advps_options');
	register_deactivation_hook(WP_PLUGIN_DIR.'/advanced-post-slider/advanced-post-slider.php','unset_advps_options');
	
	function set_advps_options(){
		global $wpdb;
		global $advpsQuery;
		global $advpsQuery2;
		global $advpsEffects;
		global $templateOneDefaultOptions;
		global $templatetwoDefaultOptions;
		global $templatethreeDefaultOptions;
		
		$ins_q = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."advps_optionset (
  				`id` int(5) NOT NULL AUTO_INCREMENT,
  				`template` varchar(10) CHARACTER SET utf8 NOT NULL,
  				`opt_data` text CHARACTER SET utf8 NOT NULL,
  				PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";
		$wpdb->query($ins_q);
		
		$ins_q2 = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."advps_thumbnail (
				`id` int(2) NOT NULL AUTO_INCREMENT,
				`thumb_name` varchar(500) NOT NULL,
				`width` int(4) NOT NULL,
				`height` int(4) NOT NULL,
				`crop` varchar(5) NOT NULL,
				PRIMARY KEY (`id`)
			  ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";
		$wpdb->query($ins_q2);
		
		$arrmrg1 = array_merge($advpsQuery,$advpsEffects,$templateOneDefaultOptions);
		$arrmrg2 = array_merge($advpsQuery2,$advpsEffects,$templatetwoDefaultOptions);
		$arrmrg3 = array_merge($advpsQuery,$advpsEffects,$templatethreeDefaultOptions);
		
		$optdt1 = serialize($arrmrg1);
		$optdt2 = serialize($arrmrg2);
		$optdt3 = serialize($arrmrg3);
		
		$q1 = "insert into ".$wpdb->prefix."advps_optionset (template,opt_data) values('one','".$optdt1."')";
		$q2 = "insert into ".$wpdb->prefix."advps_optionset (template,opt_data) values('two','".$optdt2."')";
		$q3 = "insert into ".$wpdb->prefix."advps_optionset (template,opt_data) values('three','".$optdt3."')";
		
		if(!$wpdb->get_results("select id from ".$wpdb->prefix."advps_optionset where template = 'one'")){
			$wpdb->query($q1);
		}
		if(!$wpdb->get_results("select id from ".$wpdb->prefix."advps_optionset where template = 'two'")){
			$wpdb->query($q2);
		}
		if(!$wpdb->get_results("select id from ".$wpdb->prefix."advps_optionset where template = 'three'")){
			$wpdb->query($q3);
		}
		
		if(!$wpdb->get_results("select id from ".$wpdb->prefix."advps_thumbnail where thumb_name = 'advps_thumbnail_one'")){
			$wpdb->query( "insert into ".$wpdb->prefix."advps_thumbnail (thumb_name,width,height,crop) values('advps_thumbnail_one',800,250,'yes')");
		}
		if(!$wpdb->get_results("select id from ".$wpdb->prefix."advps_thumbnail where thumb_name = 'advps_thumbnail_two'")){
			$wpdb->query( "insert into ".$wpdb->prefix."advps_thumbnail (thumb_name,width,height,crop) values('advps_thumbnail_two',624,250,'no')");
		}
	}
	
	function unset_advps_options(){
	}
	/* ---------------------------------------------------------------------------------------*/
	function advps_image_sizes(){
	  if ( function_exists( 'add_image_size' ) ) { 
		  global $wpdb;
		  $rth = $wpdb->get_results( "select * from ".$wpdb->prefix."advps_thumbnail");		
		  foreach($rth as $th){
			  add_image_size( $th->thumb_name,$th->width,$th->height, true); 
		  }
	  }
	}
	add_action('wp_loaded', 'advps_image_sizes');
	
	function advps_excerpt_length_one( $length ) {
		
		return get_option('advps_excerptlen_one');
	}
	//add_filter( 'excerpt_length', 'advps_excerpt_length_one', 999 );
	
	function advps_excerpt_length( $length ) {
		return get_option('advps_excerptlen');
	}	
	add_action( "wp_ajax_chkCaetegory", "chkCaetegory" );

	function chkCaetegory(){
		$nonce = $_POST['checkReq'];
		$posttype = $_POST['post_type'];
		if(!wp_verify_nonce( $nonce, 'advpsauthrequst' )){
			echo "Unauthorized request.";
			exit;
		}
		$catHtml = '';
		if($posttype == 'post'){
			$catHtml = '<th scope="row">Category</th><td><select name="advps_category[]" multiple="multiple">';    
			$catList = get_categories();
			foreach($catList as $scat){	 
    			$catHtml .= '<option value="'.$scat->term_id.'">'.$scat->name.'</option>';
    		}
  		$catHtml .= '</select><span style="padding-left:10px; font-size:10px; font-style:italic; vertical-align:top;">[ * You can select multiple category ]</span></td>';			
		}
		else
		{
			$posttypeobj = get_post_type_object($posttype);
			if(in_array('category',$posttypeobj->taxonomies)){
				$catHtml = '<th scope="row">Category</th><td><select name="advps_category[]" multiple="multiple">';    
				$catList = get_categories();
				foreach($catList as $scat){	 
					$catHtml .= '<option value="'.$scat->term_id.'">'.$scat->name.'</option>';
				}
			$catHtml .= '</select><span style="padding-left:10px; font-size:10px; font-style:italic; vertical-align:top;">[ * You can select multiple category ]</span></td>';
			}
		}
		echo $catHtml;
		exit;
	}
	/* ---------------------------------------------------------------------------------------*/
	
	function advps_slideshow($atts) {
		global $post;
		global $wpdb;
		global $_wp_additional_image_sizes;
		$current = $post->ID;
		
		if(is_array($atts) && array_key_exists('optset',$atts)){	
			$q1 = "select template,opt_data from ".$wpdb->prefix."advps_optionset where id = ".$atts['optset'];
			$res1 = $wpdb->get_results($q1);
			if($res1){
				$optset = unserialize($res1[0]->opt_data);
			}
			else return;			
		}
		else return;
		
		if($optset['advps_exclude']){
			$exclude = explode(',',$optset['advps_exclude']);
		}
		else
		{
			$exclude = '';
		}
		
		$query_arg = array(
			'post_type' 	 => ($optset['advps_post_types']) ? $optset['advps_post_types'] : 'post',
			'post__not_in' => $exclude,
			//'cat'			 => (is_array($atts) && array_key_exists('cat',$atts)) ? $atts['cat'] : 1,
			'posts_per_page' =>	($optset['advps_maxpost']) ? $optset['advps_maxpost'] : 10,
			'orderby'		 => ($optset['advps_order_by']) ? $optset['advps_order_by'] : 'date',
			'order'			 => ($optset['advps_order']) ? $optset['advps_order'] : 'DESC'
		);
		
		if($optset['advps_post_types'] && $optset['advps_post_types'] != "page"){
			if($optset['advps_post_types'] == "post"){
				if(isset($optset['advps_category'])){
					$query_arg['cat'] = implode(',',$optset['advps_category']);
				}
			}
			else
			{
				$post_type_obj = get_post_type_object( $optset['advps_post_types'] );
				if(in_array('category',$post_type_obj->taxonomies)){
					if(isset($optset['advps_category'])){
						$query_arg['cat'] = implode(',',$optset['advps_category']);
					}
				}
			}
		}
		
		$template = $res1[0]->template; // modify it to overwrite through shortcode
		if($template == "two"){
			$postperslide = $optset['advps_postperslide'];
		}
		
		if(($template == "one" || $template == "two") && !post_type_supports( $optset['advps_post_types'], 'thumbnail' )){
			return;				
		}
		
		if(is_array($atts) && array_key_exists('id',$atts)){
			$sldshowID = str_replace(' ','',$atts['id']);			
		}
		else
		{
			$sldshowID = substr(time(),8,2).rand(100,9999);
		}
		
		if($template != 'two'){
			if($optset['advps_excerptlen']){
				update_option('advps_excerptlen',$optset['advps_excerptlen']);
			}
			else
			{
				update_option('advps_excerptlen',30);
			}
			add_filter( 'excerpt_length', 'advps_excerpt_length', 999 );
		}
		
		ob_start();
	?>
<script type="text/javascript">
		jQuery(document).ready(function($){
		//alert("got");
		
			$("#advpsslideshow_<?php echo $sldshowID;?>").cycle({
				fx					: '<?php echo $optset['advps_effects'];?>',
				timeout				:  <?php echo $optset['advps_timeout'];?>,
				speed				:  <?php echo $optset['advps_speed'];?>,
				easing				: '<?php echo $optset['advps_easing'];?>',
				pager				: '<?php if($optset['advps_exclude_pager']=='no'){echo'#slide_nav_'.$sldshowID;}?>',
				prev				: '<?php if($optset['advps_exclude_nxtprev']=='no'){echo'#prev_'.$sldshowID;}?>',
        		next				: '<?php if($optset['advps_exclude_nxtprev']=='no'){echo'#next_'.$sldshowID;}?>',
				pause				: <?php if($optset['advps_ps_hover']=='yes'){echo 1;}else{echo 0;}?>,
				pagerAnchorBuilder  : pagerFactory
			});
			function pagerFactory(idx, slide) {
				<?php if($optset['advps_pager_type'] == 'number'): ?> 
				return '<li><a href="#">'+(idx+1)+'</a></li>';
				<?php elseif($optset['advps_pager_type'] == 'bullet'): ?> 
				return '<li><a href="#"></a></li>';
				<?php endif;?>
			}
			<?php if($template == "one" || $template == "three"):?>
			$("#advpsslideshow_<?php echo $sldshowID;?> a").hover(function(){
				//alert('got')
				$(this).css('color','<?php echo $optset['advps_titleHcolor']?>');
			},function(){
				$(this).css('color','<?php echo $optset['advps_titleFcolor']?>');
			});
			<?php endif;?>
			$(".advps-slide-container").hover(function(){
				if(!$(this).find(".advps-left-arrow, .advps-right-arrow, .advps-play-pause, .advps-left-arrow-two, .advps-right-arrow-two, .advps-play-pause-two").is(":animated")){
					<?php if($optset['advps_exclude_nxtprev']=='no'):?>
					$(this).find(".advps-left-arrow, .advps-right-arrow, .advps-left-arrow-two, .advps-right-arrow-two").fadeIn(200,'linear',function(){});<?php endif;?>
					<?php if($optset['advps_exclude_playpause']=='no'):?>
					if(!$(this).find(".advps-play-pause,.advps-play-pause-two").is(":animated")){
						$(this).find(".advps-play-pause,.advps-play-pause-two").fadeIn(200,'linear',function(){});
					}
					<?php endif;if($template == 'one'  && $optset['advps_excpt_visibility']=='show on hover'):?>
					if(!$(this).find(".advps-excerpt-one, .advps-excerpt-two").is(":animated")){
						
						$(this).find(".advps-excerpt-one, .advps-excerpt-two").fadeIn(200,'linear',function(){});
					}
					<?php endif;?>
				}
				},function(){
					<?php if($optset['advps_exclude_nxtprev']=='no'):?>
					$(".advps-left-arrow, .advps-right-arrow,.advps-left-arrow-two, .advps-right-arrow-two").fadeOut(200,'linear',function(){});<?php endif;?>
					<?php if($optset['advps_exclude_playpause']=='no'):?>
						$(this).find(".advps-play-pause,.advps-play-pause-two").fadeOut(200,'linear',function(){});
					<?php endif;if($template == 'one' && $optset['advps_excpt_visibility']=='show on hover'):?>
					$(this).find(".advps-excerpt-one, .advps-excerpt-two").fadeOut(200,'linear',function(){});
					<?php endif;?>
				});
		});
	</script>
<div class="advps-slide-container" style="width:<?php echo $optset['advps_sld_width'];?>px;height:<?php echo $optset['advps_sld_height'];?>px; <?php if($optset['advps_remove_border'] == 'no'){?>border:<?php echo $optset['advps_border_size'].'px '.$optset['advps_border_type'].' '.$optset['advps_border_color'];?>;<?php }if($optset['advps_remove_shd'] == 'no'){?>box-shadow: <?php echo $optset['advps_bxshad1'].'px '.$optset['advps_bxshad2'].'px '.$optset['advps_bxshad3'].'px '.$optset['advps_bxshadcolor'];?>;<?php }?>background-color:<?php echo $optset['advps_bgcolor'];?>;padding:<?php echo $optset['advps_contpad1'].'px '.$optset['advps_contpad2'].'px '.$optset['advps_contpad3'].'px '.$optset['advps_contpad4'].'px';?>;">
 
  <div id="<?php echo "advpsslideshow_".$sldshowID;?>" style="width:<?php echo $optset['advps_sld_width'];?>px;height:<?php echo $optset['advps_sld_height'];?>px; overflow:hidden; position:relative;">
    <?php $count = 1;$the_query = new WP_Query($query_arg); while ($the_query->have_posts()) : $the_query->the_post();if($template == 'one'):
	
	if(has_post_thumbnail()){
		$thumbID = get_post_meta($post->ID,'_thumbnail_id',true);
		$th_metadata = get_post_meta($thumbID,'_wp_attachment_metadata',true);
		
		if($optset['advps_thumbnail'] == 'full' || !array_key_exists($optset['advps_thumbnail'],$th_metadata['sizes']) ){
			$th_width = $th_metadata['width'];
			$th_height = $th_metadata['height'];
		}
		else
		{
			$th_width = $th_metadata['sizes'][$optset['advps_thumbnail']]['width'];
			$th_height = $th_metadata['sizes'][$optset['advps_thumbnail']]['height'];
		}
	}
	else
	{
		$th_width = $optset['advps_sld_width'] - ($optset['advps_contpad2']+$optset['advps_contpad4']);
		$th_height = $optset['advps_sld_height'] - ($optset['advps_contpad1']+$optset['advps_contpad3']);
	}
	?>
    <div class="advps-slide" style="width:<?php if(isset($th_width)){echo $th_width;}?>px; height:<?php if(isset($th_height)){echo $th_height;}?>px;">
	<?php if( $optset['advps_ed_link']=='enable'){?><a target="<?php echo $optset['advps_link_target'];?>" href="<?php if($optset['advps_link_type'] == 'permalink'){the_permalink();}else{echo get_post_meta($post->ID,'advps_custom_link',true);}?>"><?php }?>
      <?php 
	  		if(has_post_thumbnail()){the_post_thumbnail($optset['advps_thumbnail']);}else{
	  ?>
      <img src="<?php echo $optset['advps_default_image'];?>" style="width:<?php echo $th_width;?>px;height:<?php echo $th_height;?>px;" class="wp-post-image" />
      <?php }if( $optset['advps_ed_link']=='enable'){?></a><?php }?>
       
      <div class="advps-excerpt-<?php echo $template?>" style="width:<?php echo $optset['advps_overlay_width'];?>%;height:<?php echo  $optset['advps_overlay_height'];?>%;<?php if($optset['advps_excpt_visibility'] == 'show on hover'){?>display:none;<?php }if($optset['advps_excpt_position'] == 'left'){?>top:0; left:0;<?php }elseif($optset['advps_excpt_position'] == 'right'){?>top:0; right:0;<?php }elseif($optset['advps_excpt_position'] == 'bottom'){?>bottom:0; left:0;<?php }?>">
      	<div class="advps-overlay-<?php echo $template?>" style="background-color:<?php echo $optset['advps_overlay_color'];?>; -moz-opacity:<?php echo $optset['advps_overlay_opacity'];?>;filter:alpha(opacity=<?php echo $optset['advps_overlay_opacity']*100;?>);opacity:<?php echo $optset['advps_overlay_opacity'];?>;"></div>
        <div class="advps-excerpt-block-<?php echo $template?>" style="color:<?php echo $optset['advps_excptFcolor'];?>;font-size:<?php echo $optset['advps_excptFsize'];?>px;-moz-opacity:<?php echo $optset['advps_text_opacity'];?>;filter:alpha(opacity=<?php echo $optset['advps_text_opacity']*100;?>);opacity:<?php echo $optset['advps_text_opacity'];?>;">        
        <<?php echo $optset['advps_ttitle_tag'];?> class="advs-title" style="font-size:<?php echo $optset['advps_titleFsize'];?>px !important;margin:5px 0px 10px 0px !important;color:<?php echo $optset['advps_titleFcolor'];?>"><?php if( $optset['advps_ed_link']=='enable'){?><a target="<?php echo $optset['advps_link_target'];?>" href="<?php if($optset['advps_link_type'] == 'permalink'){the_permalink();}else{echo get_post_meta($post->ID,'advps_custom_link',true);}?>" style="color:<?php echo $optset['advps_titleFcolor'];?>"><?php }?><?php the_title();?><?php if( $optset['advps_ed_link']=='enable'){?></a><?php }?></<?php echo $optset['advps_ttitle_tag'];?>>
          
            <?php the_excerpt();?>
        </div>
      </div>
    </div>
    <?php elseif($template == 'two'):?>
    <?php if($count % $postperslide == 1 || $postperslide == 1){?><ul class="advps-slide" style="overflow:hidden;"><?php }?><li style="width:<?php if(get_option($optset['advps_thumbnail'].'_size_w')){echo get_option($optset['advps_thumbnail'].'_size_w');}else{echo $_wp_additional_image_sizes[$optset['advps_thumbnail']]['width'];}?>px;height:<?php if(get_option($optset['advps_thumbnail'].'_size_h')){echo get_option($optset['advps_thumbnail'].'_size_h');}else{echo $_wp_additional_image_sizes[$optset['advps_thumbnail']]['height'];}?>px;<?php if($optset['advps_img_Orientation'] == 'horizontal'){{echo 'float:left'.';';}echo 'margin-right:'.$optset['advps_imagemar'].'px;';}else{echo 'margin-bottom:'.$optset['advps_imagemar'].'px;';}?>list-style:none;position:relative;"><?php if( $optset['advps_ed_link']=='enable'){?><a target="<?php echo $optset['advps_link_target'];?>" href="<?php if($optset['advps_link_type'] == 'permalink'){the_permalink();}else{echo get_post_meta($post->ID,'advps_custom_link',true);}?>"><?php }?>
      <?php 
	  		/*echo $count .'and'. $postperslide*/;if(has_post_thumbnail()){the_post_thumbnail($optset['advps_thumbnail']);}
	  ?>
       <?php if( $optset['advps_ed_link']=='enable'){?></a><?php }?></li>
       <?php if($count%$postperslide == 0 || $count == $the_query->post_count){?></ul><?php }?>
    <?php elseif($template == 'three'):?>
    <div class="advps-slide">
        <div class="advps-slide-field-three" style="position:relative; float:left;width:<?php echo $optset['advps_sld_width'];?>px; height:<?php echo $optset['advps_sld_height'];?>px; overflow:hidden;">
         <?php if(in_array('thumb',$optset['advps_content_set'])):if( $optset['advps_ed_link']=='enable'){?><a target="<?php echo $optset['advps_link_target'];?>" href="<?php if($optset['advps_link_type'] == 'permalink'){the_permalink();}else{echo get_post_meta($post->ID,'advps_custom_link',true);};?>"><?php }?>
          	<?php if(has_post_thumbnail()){the_post_thumbnail($optset['advps_thumbnail']);}?>
         <?php if( $optset['advps_ed_link']=='enable'){?></a><?php }endif;?>
          <div class="advps-excerpt-<?php echo $template?>" style="position:relative;float:left;width:<?php echo $optset['advps_cont_width'];?>px;z-index:0; color:<?php echo $optset['advps_excptFcolor'];?>; font-size:<?php echo $optset['advps_excptFsize'];?>px;">
            <?php if(in_array('title',$optset['advps_content_set'])){?><<?php echo $optset['advps_ttitle_tag'];?> class="advs-title" style="font-size:<?php echo $optset['advps_titleFsize'];?>px;margin:0px 0px 10px 0px;"> <?php if( $optset['advps_ed_link']=='enable'){?><a target="<?php echo $optset['advps_link_target'];?>" href="<?php if($optset['advps_link_type'] == 'permalink'){the_permalink();}else{echo get_post_meta($post->ID,'advps_custom_link',true);}?>" style="color:<?php echo $optset['advps_titleFcolor'];?>;"><?php }?>
              <?php the_title();?>
              <?php if( $optset['advps_ed_link']=='enable'){?></a><?php }?></<?php echo $optset['advps_ttitle_tag'];?>><?php }?>
              <?php if(in_array('content',$optset['advps_content_set'])){
            			the_content();
					}
					elseif(in_array('excerpt',$optset['advps_content_set'])){
						the_excerpt();
					}
				?>
          </div>
          </div>
	</div>

	<?php endif;$count++;endwhile; wp_reset_query(); ?>
  </div>
  <div class="advps-<?php echo $optset['advps_pager_type']?>" style="right:<?php echo $optset['advps_pager_right'];?>px;bottom:<?php echo $optset['advps_pager_bottom'];?>px;">
    <ul id="slide_nav_<?php echo $sldshowID;?>">
    </ul>
  </div>
  <?php if($optset['advps_exclude_nxtprev']=='no'){?>
  <div id="prev_<?php echo $sldshowID;?>" class="<?php if($template == 'two'){if($optset['advps_img_Orientation'] == 'vertical'){echo "advps-down-arrow";}else{echo 'advps-left-arrow-two';}}else{echo 'advps-left-arrow';}?>">Down<?php /*?><a href="#"><img src="<?php echo advps_url;?>images/<?php if($template == 'two'){echo 'l1';}else{echo 'advps-left-arrow';}?>.png" alt="Left Arrow" /></a><?php */?></div>
   <?php }?>
  <?php if($optset['advps_exclude_playpause'] == 'no' && $optset['advps_timeout'] != 0):?>
  <div class="<?php if($template == 'two'){echo 'advps-play-pause-two';}else{echo 'advps-play-pause';}?> advps-flip" temp="<?php echo $template;?>" sts="played" sel="advpsslideshow_<?php echo $sldshowID;?>" iuri="<?php echo advps_url;?>"><a href="javascript:void(0)"><img src="<?php echo advps_url;?>images/<?php if($template == 'two'){echo 'pause-two';}else{echo 'pause';}?>.png" alt="pause" /></a></div>
  <?php endif;?>
  <?php if($optset['advps_exclude_nxtprev']=='no'){?>
  <div id="next_<?php echo $sldshowID;?>" class="<?php if($template == 'two'){if($optset['advps_img_Orientation'] == 'vertical'){echo "advps-up-arrow";}else{echo 'advps-right-arrow-two';}}else{echo 'advps-right-arrow';}?>">Up<?php /*?><img src="<?php echo advps_url;?>images/<?php if($template == 'two'){echo 'r1';}else{echo 'advps-right-arrow';}?>.png" alt="Right Arrow" /><?php */?></div>
  <?php }?>
</div>
<?php   
		$advps_res = ob_get_contents();
		ob_end_clean();
		return $advps_res;
	}
	
	add_shortcode('advps-slideshow', 'advps_slideshow');