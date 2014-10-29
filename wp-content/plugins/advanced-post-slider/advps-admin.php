<?php
	if ( ! defined( 'ABSPATH' ) || ! current_user_can( 'manage_options' ) ) exit;
	
	function advpsSanit($str){
		return sanitize_text_field($str);
	}
	
	global $wpdb;
	global $wp_version;
	$stsMgs = '';
	$flg = 0;
	
	if(isset($_GET['tab'])){
		$currTab = $_GET['tab'];
	}
	else
	{
		$currTab = 'one';
	}
	if(isset($_POST['advps_submit'])){
	
		if($_POST['advps_submit'] == 'Save changes'){
			
			if ( !isset($_POST['advps_wpnonce']) || !wp_verify_nonce($_POST['advps_wpnonce'],'advps-checkauthnonce') )
			{
			   print 'Sorry, your nonce did not verify.';
			   exit;
			}

			$all_field = $_POST;
			$optID = sanitize_text_field($_POST['opt_id']);
			unset($all_field['opt_id']);
			unset($all_field['advps_submit']);
			unset($all_field['advps_wpnonce']);
			unset($all_field['_wp_http_referer']);
			
			$update_data = array();
			foreach($all_field as $fkey => $fval){
				if(is_array($fval)){
					$update_data[$fkey] = array_map('advpsSanit',$fval);
				}
				else
				{
					$update_data[$fkey] = sanitize_text_field($fval);
				}
			}
			
			$update_data = serialize($update_data);
			//$q_upd = "update ".$wpdb->prefix."advps_optionset set opt_data = '".$update_data ."' where id =".$optID;			
			$q_upd = $wpdb->prepare("update ".$wpdb->prefix."advps_optionset set opt_data = '%s' where id = %d",$update_data,$optID);
			
			if($wpdb->query($q_upd)){
				$stsMgs =  "Updated successfully.";
			}
		}
		elseif($_POST['advps_submit'] == 'Add'){
			
			if ( !isset($_POST['advps_wpnonce']) || !wp_verify_nonce($_POST['advps_wpnonce'],'advps-checkauthnonce') )
			{
			   print 'Sorry, your nonce did not verify.';
			   exit;
			}
			
			$all_field = $_POST;
			$tem_list = array('one','two','three');
			$template = sanitize_text_field($_POST['template']);
			if( ! in_array( $template, $tem_list )){
				exit;
			}
			unset($all_field['template']);
			unset($all_field['advps_submit']);
			unset($all_field['advps_wpnonce']);
			unset($all_field['_wp_http_referer']);
			
			$postdata = array();
			foreach($all_field as $fkey => $fval){
				if(is_array($fval)){
					$postdata[$fkey] = array_map('advpsSanit',$fval);
				}
				else
				{
					$postdata[$fkey] = sanitize_text_field($fval);
				}
			}
			$postdata = serialize($postdata);
			
			$q_add = $wpdb->prepare("insert into ".$wpdb->prefix."advps_optionset (template,opt_data) values(%s,%s)",$template,$postdata);
			if($wpdb->query($q_add)){
				$stsMgs =  "Added successfully.";
			}
		}
		
	}
	
	if(isset($_POST['advps_add_thumb'])){
		if($_POST['advps_add_thumb'] == 'Add'){
			
			if ( !isset($_POST['advps_wpnonce']) || !wp_verify_nonce($_POST['advps_wpnonce'],'advps-checkauthnonce') )
			{
			   print 'Sorry, your nonce did not verify.';
			   exit;
			}
			
			$thumb_name = sanitize_text_field($_POST['advps_thumb_name']);
			$width = sanitize_text_field($_POST['advps_thumb_width']);
			$height = sanitize_text_field($_POST['advps_thumb_height']);
			$crop = sanitize_text_field($_POST['advps_crop']);
	
			$q = $wpdb->prepare("insert into ".$wpdb->prefix."advps_thumbnail (thumb_name,width,height,crop) values(%s,%d,%d,%s)",$thumb_name,$width,$height,$crop);
			 if($wpdb->query($q)){
				$stsMgs =  "Added successfully.";
			}				
		}
	}
	if(isset($_POST['update_thumb'])){
		
		if ( !isset($_POST['advps_wpnonce']) || !wp_verify_nonce($_POST['advps_wpnonce'],'advps-checkauthnonce') )
		{
		   print 'Sorry, your nonce did not verify.';
		   exit;
		}
		
		$thumb_id = sanitize_text_field($_POST['thumb_id']);
		$thumb_name = sanitize_text_field($_POST['advps_thumb_name']);
		$width = sanitize_text_field($_POST['advps_thumb_width']);
		$height = sanitize_text_field($_POST['advps_thumb_height']);
		$crop = sanitize_text_field($_POST['advps_crop']);
			
		$q = $wpdb->prepare("update ".$wpdb->prefix."advps_thumbnail set thumb_name = '%s',width = %d, height = %d, crop = '%s' where id = %d",$thumb_name,$width,$height,$crop,$thumb_id);
		if($wpdb->query($q)){
			$stsMgs =  "Updated successfully.";
		}
	}
	
	$q1 = "select id,opt_data from ".$wpdb->prefix."advps_optionset where template = 'one'";
	$q2 = "select id,opt_data from ".$wpdb->prefix."advps_optionset where template = 'two'";
	$q3 = "select id,opt_data from ".$wpdb->prefix."advps_optionset where template = 'three'";
	$res1 = $wpdb->get_results($q1);
	$res2 = $wpdb->get_results($q2);
	$res3 = $wpdb->get_results($q3);
	
	$q_thumb = "select * from ".$wpdb->prefix."advps_thumbnail";
	$res_thumb = $wpdb->get_results($q_thumb);
	$catList = get_categories();	
	$customPostTypes = get_post_types(array('public' => true, '_builtin' => false)); 
?>
<style>
fieldset {
	border:1px solid #D9D9D9;
	border-radius:2px;
	margin-bottom:10px;
	padding:0px 5px 10px 5px;
}
.inside {
	position:relative;
}
.wp-admin select[multiple], #wpcontent select[multiple] {
	height: auto;
}
</style>
<div class="wrap">
  <?php if($stsMgs != ''){?>
  <div id="message" class="updated below-h2">
    <p><?php echo $stsMgs;?></p>
  </div>
  <?php }?>
  <h2 class="nav-tab-wrapper"> <a href="?page=advps-slideshow&tab=one" class="nav-tab <?php if($currTab == 'one'){echo 'nav-tab-active';}?>">Template One</a> <a href="?page=advps-slideshow&tab=two" class="nav-tab <?php if($currTab == 'two'){echo 'nav-tab-active';}?>">Template Two</a> <a href="?page=advps-slideshow&tab=three" class="nav-tab <?php if($currTab == 'three'){echo 'nav-tab-active';}?>">Template Three</a><a href="?page=advps-slideshow&tab=thumb" class="nav-tab <?php if($currTab == 'thumb'){echo 'nav-tab-active';}?>">Thumbnails</a>
    <?php /*?><a href="?page=advps-slideshow&tab=styling" class="nav-tab <?php if($currTab == 'styling'){echo 'nav-tab-active';}?>">Styling</a> <?php */?>
  </h2>
  <?php if($currTab == 'one'){foreach( $res1 as $dset){ $dataSet = unserialize($dset->opt_data);?>
  <div class="metabox-holder" style="margin-top:20px;">
    <div class="postbox-container" style="width:65%">
      <div class="postbox <?php if(!isset($_POST['opt_id']) || $_POST['opt_id'] != $dset->id){echo 'closed';}?>">
        <div class="handlediv" title=""> <br>
        </div>
        <h3 style="cursor:pointer; text-align:center" class="advps-expand"> <span>Option Set</span> </h3>
        <div class="inside">
          <div style="position:absolute; top:1.2%; right:4%; font-weight:bold; border:1px solid #393;padding:2px 15px;">ID = <?php echo $dset->id;?></div>
          <form method="post">
            <fieldset>
              <legend style="margin-left:10px;"><strong>Query</strong></legend>
              <table class="form-table">
                <tr>
                  <th scope="row">Post Type</th>
                  <td><select name="advps_post_types" onchange="advpsCheckCat(this.value,<?php echo $dset->id;?>)">
                      <option value="post" <?php if($dataSet['advps_post_types'] == 'post'){echo 'selected="selected"';}?>>post</option>
                      <option value="page" <?php if($dataSet['advps_post_types'] == 'page'){echo 'selected="selected"';}?>>page</option>
                      <?php
                              foreach ($customPostTypes  as $post_type ) {
                          ?>
                      <option value="<?php echo $post_type;?>" <?php if($dataSet['advps_post_types'] == $post_type){echo 'selected="selected"';}?>><?php echo $post_type;?></option>
                      <?php		
                              }
                          ?>
                    </select></td>
                </tr>
                <tr id="advps-cat-field<?php echo $dset->id;?>">
                  <?php  if($dataSet['advps_post_types'] != "page"){?>
                  <th scope="row">Category</th>
                  <td><!--<input type="text" name="advps_category" value="<?php //echo get_option('advps_category');?>" style="width:100px;" />-->
                    
                    <select name="advps_category[]" multiple="multiple">
                      <?php 
					  	$catList = get_categories();
						foreach($catList as $scat){
					  ?>
                      <option value="<?php echo $scat->term_id;?>" <?php if(isset($dataSet['advps_category']) && in_array($scat->term_id,$dataSet['advps_category'])){echo 'selected="selected"';}?>><?php echo $scat->name;?></option>
                      <?php }?>
                    </select>
                    <span style="padding-left:10px; font-size:10px; font-style:italic; vertical-align:top;">[ * You can select multiple category ]</span></td>
                  <?php }?>
                </tr>
                <tr>
                  <th scope="row">Max. Number of post</th>
                  <td><input type="text" name="advps_maxpost" value="<?php echo $dataSet['advps_maxpost'];?>" style="width:60px;" onkeypress="return onlyNum(event);" /></td>
                </tr>
                <tr>
                  <th scope="row">Exclude</th>
                  <td><input type="text" name="advps_exclude" value="<?php echo $dataSet['advps_exclude'];?>" style="width:100px;" />
                    <span style="padding-left:10px; font-size:10px; font-style:italic;">[ Ex. 1,5,10 Comma separated post IDs that need to exclude from slideshow ]</span></td>
                </tr>
                <tr>
                  <th scope="row">Order by</th>
                  <td><select name="advps_order_by">
                      <option value="date" <?php if($dataSet['advps_order_by'] == 'date'){echo 'selected="selected"';}?>>Date</option>
                      <option value="ID" <?php if($dataSet['advps_order_by'] == 'ID'){echo 'selected="selected"';}?>>ID</option>
                      <option value="author" <?php if($dataSet['advps_order_by'] == 'author'){echo 'selected="selected"';}?>>Author</option>
                      <option value="title" <?php if($dataSet['advps_order_by'] == 'title'){echo 'selected="selected"';}?>>Title</option>
                      <option value="name" <?php if($dataSet['advps_order_by'] == 'name'){echo 'selected="selected"';}?>>Name</option>
                      <option value="rand" <?php if($dataSet['advps_order_by'] == 'rand'){echo 'selected="selected"';}?>>Random</option>
                      <option value="menu_order" <?php if($dataSet['advps_order_by'] == 'menu_order'){echo 'selected="selected"';}?>>Menu order</option>
                      <option value="comment_count" <?php if($dataSet['advps_order_by'] == 'comment_count'){echo 'selected="selected"';}?>>Comment count</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Order</th>
                  <td><select name="advps_order">
                      <option value="ASC" <?php if($dataSet['advps_order'] == 'ASC'){echo 'selected="selected"';}?>>Ascending</option>
                      <option value="DESC" <?php if($dataSet['advps_order'] == 'DESC'){echo 'selected="selected"';}?>>Descending</option>
                    </select></td>
                </tr>
              </table>
            </fieldset>
            <fieldset>
              <legend style="margin-left:10px;"><strong>Effects</strong></legend>
              <table class="form-table">
                <tr>
                  <th scope="row">Effect</th>
                  <td><select name="advps_effects">
                      <option value="blindX" <?php if($dataSet['advps_effects'] == 'blindX'){echo 'selected="selected"';}?>>blindX</option>
                      <option value="blindY" <?php if($dataSet['advps_effects'] == 'blindY'){echo 'selected="selected"';}?>>blindY</option>
                      <option value="blindZ" <?php if($dataSet['advps_effects'] == 'blindZ'){echo 'selected="selected"';}?>>blindZ</option>
                      <option value="cover" <?php if($dataSet['advps_effects'] == 'cover'){echo 'selected="selected"';}?>>cover</option>
                      <option value="curtainX" <?php if($dataSet['advps_effects'] == 'curtainX'){echo 'selected="selected"';}?>>curtainX</option>
                      <option value="curtainY" <?php if($dataSet['advps_effects'] == 'curtainY'){echo 'selected="selected"';}?>>curtainY</option>
                      <option value="fade" <?php if($dataSet['advps_effects'] == 'fade'){echo 'selected="selected"';}?>>fade</option>
                      <option value="fadeZoom" <?php if($dataSet['advps_effects'] == 'fadeZoom'){echo 'selected="selected"';}?>>fadeZoom</option>
                      <option value="growX" <?php if($dataSet['advps_effects'] == 'growX'){echo 'selected="selected"';}?>>growX</option>
                      <option value="growY" <?php if($dataSet['advps_effects'] == 'growY'){echo 'selected="selected"';}?>>growY</option>
                      <option value="none" <?php if($dataSet['advps_effects'] == 'none'){echo 'selected="selected"';}?>>none</option>
                      <option value="scrollUp" <?php if($dataSet['advps_effects'] == 'scrollUp'){echo 'selected="selected"';}?>>scrollUp</option>
                      <option value="scrollDown" <?php if($dataSet['advps_effects'] == 'scrollDown'){echo 'selected="selected"';}?>>scrollDown</option>
                      <option value="scrollLeft" <?php if($dataSet['advps_effects'] == 'scrollLeft'){echo 'selected="selected"';}?>>scrollLeft</option>
                      <option value="scrollRight" <?php if($dataSet['advps_effects'] == 'scrollRight'){echo 'selected="selected"';}?>>scrollRight</option>
                      <option value="scrollHorz" <?php if($dataSet['advps_effects'] == 'scrollHorz'){echo 'selected="selected"';}?>>scrollHorz</option>
                      <option value="scrollVert" <?php if($dataSet['advps_effects'] == 'scrollVert'){echo 'selected="selected"';}?>>scrollVert</option>
                      <option value="shuffle" <?php if($dataSet['advps_effects'] == 'shuffle'){echo 'selected="selected"';}?>>shuffle</option>
                      <option value="slideX" <?php if($dataSet['advps_effects'] == 'slideX'){echo 'selected="selected"';}?>>slideX</option>
                      <option value="slideY" <?php if($dataSet['advps_effects'] == 'slideY'){echo 'selected="selected"';}?>>slideY</option>
                      <option value="toss" <?php if($dataSet['advps_effects'] == 'toss'){echo 'selected="selected"';}?>>toss</option>
                      <option value="turnUp" <?php if($dataSet['advps_effects'] == 'turnUp'){echo 'selected="selected"';}?>>turnUp</option>
                      <option value="turnDown" <?php if($dataSet['advps_effects'] == 'turnDown'){echo 'selected="selected"';}?>>turnDown</option>
                      <option value="turnLeft" <?php if($dataSet['advps_effects'] == 'turnLeft'){echo 'selected="selected"';}?>>turnLeft</option>
                      <option value="turnRight" <?php if($dataSet['advps_effects'] == 'turnRight'){echo 'selected="selected"';}?>>turnRight</option>
                      <option value="uncover" <?php if($dataSet['advps_effects'] == 'uncover'){echo 'selected="selected"';}?>>uncover</option>
                      <option value="wipe" <?php if($dataSet['advps_effects'] == 'wipe'){echo 'selected="selected"';}?>>wipe</option>
                      <option value="zoom" <?php if($dataSet['advps_effects'] == 'zoom'){echo 'selected="selected"';}?>>zoom</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Timeout</th>
                  <td><input type="text" name="advps_timeout" value="<?php echo $dataSet['advps_timeout'];?>" style="width:60px;" onkeypress="return onlyNum(event);" />
                    <span style="padding-left:10px; font-size:10px; font-style:italic;"> [ 0 to disable auto advance.]</span></td>
                </tr>
                <tr>
                  <th scope="row">Easing</th>
                  <td><select name="advps_easing">
                      <option value="linear" <?php if($dataSet['advps_easing'] == 'linear'){echo 'selected="selected"';}?>>linear</option>
                      <option value="swing" <?php if($dataSet['advps_easing'] == 'swing'){echo 'selected="selected"';}?>>swing</option>
                      <option value="jswing" <?php if($dataSet['advps_easing'] == 'jswing'){echo 'selected="selected"';}?>>jswing</option>
                      <option value="easeInQuad" <?php if($dataSet['advps_easing'] == 'easeInQuad'){echo 'selected="selected"';}?>>easeInQuad</option>
                      <option value="easeOutQuad" <?php if($dataSet['advps_easing'] == 'easeOutQuad'){echo 'selected="selected"';}?>>easeOutQuad</option>
                      <option value="easeInOutQuad" <?php if($dataSet['advps_easing'] == 'easeInOutQuad'){echo 'selected="selected"';}?>>easeInOutQuad</option>
                      <option value="easeInCubic" <?php if($dataSet['advps_easing'] == 'easeInCubic'){echo 'selected="selected"';}?>>easeInCubic</option>
                      <option value="easeOutCubic" <?php if($dataSet['advps_easing'] == 'easeOutCubic'){echo 'selected="selected"';}?>>easeOutCubic</option>
                      <option value="easeInOutCubic" <?php if($dataSet['advps_easing'] == 'easeInOutCubic'){echo 'selected="selected"';}?>>easeInOutCubic</option>
                      <option value="easeInQuart" <?php if($dataSet['advps_easing'] == 'easeInQuart'){echo 'selected="selected"';}?>>easeInQuart</option>
                      <option value="easeOutQuart" <?php if($dataSet['advps_easing'] == 'easeOutQuart'){echo 'selected="selected"';}?>>easeOutQuart</option>
                      <option value="easeInOutQuart" <?php if($dataSet['advps_easing'] == 'easeInOutQuart'){echo 'selected="selected"';}?>>easeInOutQuart</option>
                      <option value="easeInQuint" <?php if($dataSet['advps_easing'] == 'easeInQuint'){echo 'selected="selected"';}?>>easeInQuint</option>
                      <option value="easeInQuint" <?php if($dataSet['advps_easing'] == 'easeOutQuint'){echo 'selected="selected"';}?>>easeOutQuint</option>
                      <option value="easeInQuint" <?php if($dataSet['advps_easing'] == 'easeInOutQuint'){echo 'selected="selected"';}?>>easeInOutQuint</option>
                      <option value="easeInSine" <?php if($dataSet['advps_easing'] == 'easeInSine'){echo 'selected="selected"';}?>>easeInSine</option>
                      <option value="easeOutSine" <?php if($dataSet['advps_easing'] == 'easeOutSine'){echo 'selected="selected"';}?>>easeOutSine</option>
                      <option value="easeInOutSine" <?php if($dataSet['advps_easing'] == 'easeInOutSine'){echo 'selected="selected"';}?>>easeInOutSine</option>
                      <option value="easeInExpo" <?php if($dataSet['advps_easing'] == 'easeInExpo'){echo 'selected="selected"';}?>>easeInExpo</option>
                      <option value="easeOutExpo" <?php if($dataSet['advps_easing'] == 'easeOutExpo'){echo 'selected="selected"';}?>>easeOutExpo</option>
                      <option value="easeInOutExpo" <?php if($dataSet['advps_easing'] == 'easeInOutExpo'){echo 'selected="selected"';}?>>easeInOutExpo</option>
                      <option value="easeInCirc" <?php if($dataSet['advps_easing'] == 'easeInCirc'){echo 'selected="selected"';}?>>easeInCirc</option>
                      <option value="easeOutCirc" <?php if($dataSet['advps_easing'] == 'easeOutCirc'){echo 'selected="selected"';}?>>easeOutCirc</option>
                      <option value="easeInOutCirc" <?php if($dataSet['advps_easing'] == 'easeInOutCirc'){echo 'selected="selected"';}?>>easeInOutCirc</option>
                      <option value="easeInBounce" <?php if($dataSet['advps_easing'] == 'easeInBounce'){echo 'selected="selected"';}?>>easeInBounce</option>
                      <option value="easeOutBounce" <?php if($dataSet['advps_easing'] == 'easeOutBounce'){echo 'selected="selected"';}?>>easeOutBounce</option>
                      <option value="easeInOutBounce" <?php if($dataSet['advps_easing'] == 'easeInOutBounce'){echo 'selected="selected"';}?>>easeInOutBounce</option>
                      <option value="easeInElastic" <?php if($dataSet['advps_easing'] == 'easeInElastic'){echo 'selected="selected"';}?>>easeInElastic</option>
                      <option value="easeOutElastic" <?php if($dataSet['advps_easing'] == 'easeOutElastic'){echo 'selected="selected"';}?>>easeOutElastic</option>
                      <option value="easeInOutElastic" <?php if($dataSet['advps_easing'] == 'easeInOutElastic'){echo 'selected="selected"';}?>>easeInOutElastic</option>
                      <option value="easeInBack" <?php if($dataSet['advps_easing'] == 'easeInBack'){echo 'selected="selected"';}?>>easeInBack</option>
                      <option value="easeOutBack" <?php if($dataSet['advps_easing'] == 'easeOutBack'){echo 'selected="selected"';}?>>easeOutBack</option>
                      <option value="easeInOutBack" <?php if($dataSet['advps_easing'] == 'easeInOutBack'){echo 'selected="selected"';}?>>easeInOutBack</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Speed</th>
                  <td><input type="text" name="advps_speed" value="<?php echo $dataSet['advps_speed'];?>" style="width:60px;" onkeypress="return onlyNum(event);" /></td>
                </tr>
                <tr>
                  <th scope="row">Enable pause on hover</th>
                  <td><select name="advps_ps_hover">
                      <option value="yes" <?php if($dataSet['advps_ps_hover'] == 'yes'){echo 'selected="selected"';}?>>Yes</option>
                      <option value="no" <?php if($dataSet['advps_ps_hover'] == 'no'){echo 'selected="selected"';}?>>No</option>
                    </select></td>
                </tr>
              </table>
            </fieldset>
            <fieldset>
              <legend style="margin-left:10px;"><strong>Container & Thumbnail</strong></legend>
              <table class="form-table">
                <tr>
                  <th scope="row">Select Thumbnail</th>
                  <td><select name="advps_thumbnail">
                      <option value="thumbnail" <?php if($dataSet['advps_thumbnail'] == 'thumbnail'){echo 'selected="selected"';}?>>thumbnail</option>
                      <option value="medium" <?php if($dataSet['advps_thumbnail'] == 'medium'){echo 'selected="selected"';}?>>medium</option>
                      <option value="large" <?php if($dataSet['advps_thumbnail'] == 'large'){echo 'selected="selected"';}?>>large</option>
                      <option value="full" <?php if($dataSet['advps_thumbnail'] == 'full'){echo 'selected="selected"';}?>>full</option>
                      <?php 
                        global $_wp_additional_image_sizes;
                        unset($_wp_additional_image_sizes['post-thumbnail']);
                        foreach($_wp_additional_image_sizes as $tkey => $tval){
                        ?>
                      <option value="<?php echo $tkey;?>" <?php if($dataSet['advps_thumbnail'] == $tkey){echo 'selected="selected"';}?>><?php echo $tkey;?></option>
                      <?php
                        }
                        ?>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Default image url</th>
                  <td><input type="text" name="advps_default_image" value="<?php echo $dataSet['advps_default_image'];?>" style="width:250px;" />
                    <span style="padding-left:10px; font-size:10px; font-style:italic;"> [ N.B. If any post doesn't have featured image then default image will be shown.]</span></td>
                </tr>
                <tr>
                  <th scope="row">Slide Container</th>
                  <td>Width&nbsp;
                    <input type="text" name="advps_sld_width" value="<?php echo $dataSet['advps_sld_width'];?>" style="width:80px;" onkeypress="return onlyNum(event);" />
                    px&nbsp;&nbsp;&nbsp;Height&nbsp;
                    <input type="text" name="advps_sld_height" value="<?php echo $dataSet['advps_sld_height'];?>" style="width:80px;" onkeypress="return onlyNum(event);" />
                    px</td>
                </tr>
                <tr>
                  <th scope="row">Padding</th>
                  <td><input type="text" name="advps_contpad1" value="<?php echo $dataSet['advps_contpad1'];?>" style="width:40px;" onkeypress="return onlyNum(event);" />
                    px &nbsp;&nbsp;
                    <input type="text" name="advps_contpad2" value="<?php echo $dataSet['advps_contpad2'];?>" style="width:40px;" onkeypress="return onlyNum(event);" />
                    px&nbsp;&nbsp;
                    <input type="text" name="advps_contpad3" value="<?php echo $dataSet['advps_contpad3'];?>" style="width:40px;" onkeypress="return onlyNum(event);" />
                    px&nbsp;&nbsp;
                    <input type="text" name="advps_contpad4" value="<?php echo $dataSet['advps_contpad4'];?>" style="width:40px;" onkeypress="return onlyNum(event);" />
                    px&nbsp;&nbsp; </td>
                </tr>
                <tr>
                  <th scope="row">Background Color</th>
                  <td><input id="advpscolor<?php echo ++$flg?>" class="advps-color-picker" type="text" name="advps_bgcolor" value="<?php echo $dataSet['advps_bgcolor'];?>" style="width:100px;" />
                    <div class="advpsfarb" style="padding-left:22%"></div></td>
                </tr>
                <tr>
                  <th scope="row">Border</th>
                  <td><span style="vertical-align:top">
                    <input type="text" name="advps_border_size" value="<?php echo $dataSet['advps_border_size'];?>" style="width:40px;" onkeypress="return onlyNum(event);" />
                    px &nbsp;&nbsp;
                    <select name="advps_border_type">
                      <option value="dashed" <?php if($dataSet['advps_border_type'] == 'dashed'){echo 'selected="selected"';}?>>dashed</option>
                      <option value="dotted" <?php if($dataSet['advps_border_type'] == 'dotted'){echo 'selected="selected"';}?>>dotted</option>
                      <option value="double" <?php if($dataSet['advps_border_type'] == 'double'){echo 'selected="selected"';}?>>double</option>
                      <option value="solid" <?php if($dataSet['advps_border_type'] == 'solid'){echo 'selected="selected"';}?>>solid</option>
                      <option value="inset" <?php if($dataSet['advps_border_type'] == 'inset'){echo 'selected="selected"';}?>>inset</option>
                      <option value="outset" <?php if($dataSet['advps_border_type'] == 'outset'){echo 'selected="selected"';}?>>outset</option>
                    </select>
                    &nbsp;&nbsp;</span>
                    <input class="advps-color-picker" type="text" name="advps_border_color" id="advpscolor<?php echo ++$flg?>" value="<?php echo $dataSet['advps_border_color'];?>" style="width:100px;" />
                    <div class="advpsfarb" style="padding-left:22%"></div></td>
                </tr>
                <tr>
                  <th scope="row">Remove Border</th>
                  <td><select name="advps_remove_border">
                      <option value="yes" <?php if($dataSet['advps_remove_border'] == 'yes'){echo 'selected="selected"';}?>>Yes</option>
                      <option value="no" <?php if($dataSet['advps_remove_border'] == 'no'){echo 'selected="selected"';}?>>No</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Box Shadow</th>
                  <td><span style="vertical-align:top">
                    <input type="text" name="advps_bxshad1" value="<?php echo $dataSet['advps_bxshad1'];?>" style="width:40px;" onkeypress="return onlyNum(event);" />
                    px &nbsp;&nbsp;
                    <input type="text" name="advps_bxshad2" value="<?php echo $dataSet['advps_bxshad2'];?>" style="width:40px;" onkeypress="return onlyNum(event);" />
                    px&nbsp;&nbsp;
                    <input type="text" name="advps_bxshad3" value="<?php echo $dataSet['advps_bxshad3'];?>" style="width:40px;" onkeypress="return onlyNum(event);" />
                    px&nbsp;&nbsp;</span>
                    <input class="advps-color-picker" type="text" name="advps_bxshadcolor" value="<?php echo $dataSet['advps_bxshadcolor'];?>" style="width:100px;" id="advpscolor<?php echo ++$flg?>" />
                    <div class="advpsfarb" style="padding-left:22%"></div></td>
                </tr>
                <tr>
                  <th scope="row">Remove Shadow</th>
                  <td><select name="advps_remove_shd">
                      <option value="yes" <?php if($dataSet['advps_remove_shd'] == 'yes'){echo 'selected="selected"';}?>>Yes</option>
                      <option value="no" <?php if($dataSet['advps_remove_shd'] == 'no'){echo 'selected="selected"';}?>>No</option>
                    </select></td>
                </tr>
              </table>
            </fieldset>
            <fieldset>
              <legend style="margin-left:10px;"><strong>Title & Excerpt</strong></legend>
              <table class="form-table">
                <tr>
                  <th scope="row">Overlay size</th>
                  <td>Width&nbsp;
                    <input type="text" name="advps_overlay_width" value="<?php echo $dataSet['advps_overlay_width'];?>" style="width:80px;" onkeypress="return onlyNum(event);" />
                    %&nbsp;&nbsp;&nbsp;Height&nbsp;
                    <input type="text" name="advps_overlay_height" value="<?php echo $dataSet['advps_overlay_height'];?>" style="width:80px;" onkeypress="return onlyNum(event);" />
                    %</td>
                </tr>
                <tr>
                  <th scope="row">Overlay color</th>
                  <td><input type="text" name="advps_overlay_color" value="<?php echo $dataSet['advps_overlay_color'];?>" style="width:100px;" class="advps-color-picker" id="advpscolor<?php echo ++$flg?>" />
                    <div class="advpsfarb" style="padding-left:22%"></div></td>
                </tr>
                <tr>
                  <th scope="row">Overlay opacity</th>
                  <td><input type="text" name="advps_overlay_opacity" value="<?php echo $dataSet['advps_overlay_opacity'];?>" style="width:50px;" />
                    &nbsp;<span style="padding-left:10px; font-size:10px; font-style:italic;">[ 0 - 1 ]</span></td>
                </tr>
                <tr>
                  <th scope="row">Text opacity</th>
                  <td><input type="text" name="advps_text_opacity" value="<?php echo $dataSet['advps_text_opacity'];?>" style="width:50px;" />
                    &nbsp;<span style="padding-left:10px; font-size:10px; font-style:italic;">[ 0 - 1 ]</span></td>
                </tr>
                <tr>
                  <th scope="row">Title tag</th>
                  <td><select name="advps_ttitle_tag">
                      <option value="h1" <?php if($dataSet['advps_ttitle_tag'] == 'h1'){echo 'selected="selected"';}?>>h1</option>
                      <option value="h2" <?php if($dataSet['advps_ttitle_tag'] == 'h2'){echo 'selected="selected"';}?>>h2</option>
                      <option value="h3" <?php if($dataSet['advps_ttitle_tag'] == 'h3'){echo 'selected="selected"';}?>>h3</option>
                      <option value="div" <?php if($dataSet['advps_ttitle_tag'] == 'div'){echo 'selected="selected"';}?>>div</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Title font Color</th>
                  <td><input type="text" name="advps_titleFcolor" value="<?php echo $dataSet['advps_titleFcolor'];?>" style="width:100px;" class="advps-color-picker" id="advpscolor<?php echo ++$flg?>" />
                    <div class="advpsfarb" style="padding-left:22%"></div></td>
                </tr>
                <tr>
                  <th scope="row">Title hover Color</th>
                  <td><input type="text" name="advps_titleHcolor" value="<?php echo $dataSet['advps_titleHcolor'];?>" style="width:100px;" class="advps-color-picker" id="advpscolor<?php echo ++$flg?>" />
                    <div class="advpsfarb" style="padding-left:22%"></div></td>
                </tr>
                <tr>
                  <th scope="row">Title font size</th>
                  <td><input type="text" name="advps_titleFsize" value="<?php echo $dataSet['advps_titleFsize'];?>" style="width:60px;" />
                    px</td>
                </tr>
                <tr>
                  <th scope="row">Excerpt font color</th>
                  <td><input class="advps-color-picker" type="text" name="advps_excptFcolor" value="<?php echo $dataSet['advps_excptFcolor'];?>" style="width:100px;" id="advpscolor<?php echo ++$flg?>" />
                    <div class="advpsfarb" style="padding-left:22%"></div></td>
                </tr>
                <tr>
                  <th scope="row">Excerpt font size</th>
                  <td><input type="text" name="advps_excptFsize" value="<?php echo $dataSet['advps_excptFsize'];?>" style="width:60px;" />
                    px</td>
                </tr>
                <tr>
                  <th scope="row">Excerpt length</th>
                  <td><input type="text" name="advps_excerptlen" value="<?php echo $dataSet['advps_excerptlen'];?>" style="width:60px;" onkeypress="return onlyNum(event);" />
                    &nbsp;words</td>
                </tr>
                <tr>
                  <th scope="row">Excerpt visibility</th>
                  <td><select name="advps_excpt_visibility">
                      <option value="show on hover" <?php if($dataSet['advps_excpt_visibility'] == 'show on hover'){echo 'selected="selected"';}?>>Show on hover</option>
                      <option value="always show" <?php if($dataSet['advps_excpt_visibility'] == 'always show'){echo 'selected="selected"';}?>>Always show</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Excerpt Position</th>
                  <td><select name="advps_excpt_position">
                      <option value="left" <?php if($dataSet['advps_excpt_position'] == 'left'){echo 'selected="selected"';}?>>Left</option>
                      <option value="right" <?php if($dataSet['advps_excpt_position'] == 'right'){echo 'selected="selected"';}?>>Right</option>
                      <option value="bottom" <?php if($dataSet['advps_excpt_position'] == 'bottom'){echo 'selected="selected"';}?>>Bottom</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Enable/Disable link</th>
                  <td><select name="advps_ed_link">
                      <option value="enable" <?php if($dataSet['advps_ed_link'] == 'enable'){echo 'selected="selected"';}?>>Enable</option>
                      <option value="disable" <?php if($dataSet['advps_ed_link'] == 'disable'){echo 'selected="selected"';}?>>Disable</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">link type</th>
                  <td><select name="advps_link_type">
                      <option value="permalink" <?php if($dataSet['advps_link_type'] == 'permalink'){echo 'selected="selected"';}?>>Permalink</option>
                      <option value="custom" <?php if($dataSet['advps_link_type'] == 'custom'){echo 'selected="selected"';}?>>Custom</option>
                    </select>
                    <span style="padding-left:10px; font-size:10px; font-style:italic;">[ N.B. For custom link create a custom field with name 'advps_custom_link' ]</span></td>
                </tr>
                <tr>
                  <th scope="row">link target</th>
                  <td><select name="advps_link_target">
                      <option value="_self" <?php if($dataSet['advps_link_target'] == '_self'){echo 'selected="selected"';}?>>_self</option>
                      <option value="_blank" <?php if($dataSet['advps_link_target'] == '_blank'){echo 'selected="selected"';}?>>_blank</option>
                      <option value="_new" <?php if($dataSet['advps_link_target'] == '_new'){echo 'selected="selected"';}?>>_new</option>
                      <option value="_top" <?php if($dataSet['advps_link_target'] == '_top'){echo 'selected="selected"';}?>>_top</option>
                      <option value="_parent" <?php if($dataSet['advps_link_target'] == '_parent'){echo 'selected="selected"';}?>>_parent</option>
                    </select></td>
                </tr>
              </table>
            </fieldset>
            <fieldset>
              <legend style="margin-left:10px;"><strong>Navigation</strong></legend>
              <table class="form-table">
                <tr>
                  <th scope="row">Exclude pager</th>
                  <td><select name="advps_exclude_pager">
                      <option value="yes" <?php if($dataSet['advps_exclude_pager'] == 'yes'){echo 'selected="selected"';}?>>Yes</option>
                      <option value="no" <?php if($dataSet['advps_exclude_pager'] == 'no'){echo 'selected="selected"';}?>>No</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Pager type</th>
                  <td><span style="padding-right:5px;">Number</span>
                    <input type="radio" name="advps_pager_type" value="number" <?php if($dataSet['advps_pager_type'] == 'number'){echo 'checked="checked"';}?>>
                    <span style="padding:0px 5px 0px 10px;">Bullet</span>
                    <input type="radio" name="advps_pager_type" value="bullet" <?php if($dataSet['advps_pager_type'] == 'bullet'){echo 'checked="checked"';}?>></td>
                </tr>
                <tr>
                  <th scope="row">Pager position</th>
                  <td>Right&nbsp;
                    <input type="text" name="advps_pager_right" value="<?php echo $dataSet['advps_pager_right'];?>" style="width:50px;" onkeypress="return onlyNum(event);" />
                    px&nbsp;&nbsp;&nbsp;Bottom&nbsp;
                    <input type="text" name="advps_pager_bottom" value="<?php echo $dataSet['advps_pager_bottom'];?>" style="width:50px;" onkeypress="return onlyNum(event);" />
                    px</td>
                </tr>
                <tr>
                  <th scope="row">Exclude Next/Previous</th>
                  <td><select name="advps_exclude_nxtprev">
                      <option value="yes" <?php if($dataSet['advps_exclude_nxtprev'] == 'yes'){echo 'selected="selected"';}?>>Yes</option>
                      <option value="no" <?php if($dataSet['advps_exclude_nxtprev'] == 'no'){echo 'selected="selected"';}?>>No</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Exclude Play/Pause</th>
                  <td><select name="advps_exclude_playpause">
                      <option value="yes" <?php if($dataSet['advps_exclude_playpause'] == 'yes'){echo 'selected="selected"';}?>>Yes</option>
                      <option value="no" <?php if($dataSet['advps_exclude_playpause'] == 'no'){echo 'selected="selected"';}?>>No</option>
                    </select></td>
                </tr>
              </table>
            </fieldset>
            <input type="hidden" value="<?php echo $dset->id;?>" name="opt_id" />
            <?php wp_nonce_field('advps-checkauthnonce','advps_wpnonce'); ?>
            <p>
              <input type="submit" name="advps_submit" value="Save changes" class="button-primary" />
            </p>
          </form>
        </div>
      </div>
    </div>
  </div>
  <?php }?>
  <?php if(isset($_POST['advps_add'])){?>
  <div class="metabox-holder" style="margin-top:20px;">
    <div class="postbox-container" style="width:65%">
      <div class="postbox">
        <div class="handlediv" title=""> <br>
        </div>
        <h3 style="cursor:pointer; text-align:center" class="advps-expand"> <span>Add new option set</span> </h3>
        <div class="inside">
          <form method="post">
            <fieldset style="border:1px solid #D9D9D9; border-radius:2px; margin-bottom:10px;">
              <legend style="margin-left:10px;"><strong>Query</strong></legend>
              <table class="form-table">
                <tr>
                  <th scope="row">Post Type</th>
                  <td><select name="advps_post_types" onchange="advpsCheckCat(this.value,'add')">
                      <option value="post" selected="selected">post</option>
                      <option value="page">page</option>
                      <?php
                              foreach ($customPostTypes  as $post_type ) {
                          ?>
                      <option value="<?php echo $post_type;?>"><?php echo $post_type;?></option>
                      <?php		
                              }
                          ?>
                    </select></td>
                </tr>
                <tr id="advps-cat-fieldadd">
                  <th scope="row">Category</th>
                  <td><select name="advps_category[]" multiple="multiple">
                      <?php 
					  	$catList = get_categories();
						foreach($catList as $scat){
					  ?>
                      <option value="<?php echo $scat->term_id;?>"><?php echo $scat->name;?></option>
                      <?php }?>
                    </select>
                    <span style="padding-left:10px; font-size:10px; font-style:italic; vertical-align:top;">[ * You can select multiple category ]</span></td>
                </tr>
                <tr>
                  <th scope="row">Max. Number of post</th>
                  <td><input type="text" name="advps_maxpost" value="10" style="width:60px;" onkeypress="return onlyNum(event);" /></td>
                </tr>
                <tr>
                  <th scope="row">Exclude</th>
                  <td><input type="text" name="advps_exclude" value="" style="width:100px;" />
                    <span style="padding-left:10px; font-size:10px; font-style:italic;">[ Ex. 1,5,10 Comma separated post IDs that need to exclude from slideshow ]</span></td>
                </tr>
                <tr>
                  <th scope="row">Order by</th>
                  <td><select name="advps_order_by">
                      <option value="date" selected="selected">Date</option>
                      <option value="ID">ID</option>
                      <option value="author">Author</option>
                      <option value="title">Title</option>
                      <option value="name">Name</option>
                      <option value="rand">Random</option>
                      <option value="menu_order">Menu order</option>
                      <option value="comment_count">Comment count</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Order</th>
                  <td><select name="advps_order">
                      <option value="ASC">Ascending</option>
                      <option value="DESC" selected="selected">Descending</option>
                    </select></td>
                </tr>
              </table>
            </fieldset>
            <fieldset style="border:1px solid #D9D9D9; border-radius:2px;margin-bottom:10px;">
              <legend style="margin-left:10px;"><strong>Effects</strong></legend>
              <table class="form-table">
                <tr>
                  <th scope="row">Effect</th>
                  <td><select name="advps_effects">
                      <option value="blindX">blindX</option>
                      <option value="blindY">blindY</option>
                      <option value="blindZ">blindZ</option>
                      <option value="cover">cover</option>
                      <option value="curtainX">curtainX</option>
                      <option value="curtainY">curtainY</option>
                      <option value="fade">fade</option>
                      <option value="fadeZoom">fadeZoom</option>
                      <option value="growX">growX</option>
                      <option value="growY">growY</option>
                      <option value="none">none</option>
                      <option value="scrollUp">scrollUp</option>
                      <option value="scrollDown">scrollDown</option>
                      <option value="scrollLeft">scrollLeft</option>
                      <option value="scrollRight">scrollRight</option>
                      <option value="scrollHorz">scrollHorz</option>
                      <option value="scrollVert">scrollVert</option>
                      <option value="shuffle">shuffle</option>
                      <option value="slideX">slideX</option>
                      <option value="slideY">slideY</option>
                      <option value="toss">toss</option>
                      <option value="turnUp">turnUp</option>
                      <option value="turnDown">turnDown</option>
                      <option value="turnLeft">turnLeft</option>
                      <option value="turnRight">turnRight</option>
                      <option value="uncover" selected="selected">uncover</option>
                      <option value="wipe">wipe</option>
                      <option value="zoom">zoom</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Timeout</th>
                  <td><input type="text" name="advps_timeout" value="2000" style="width:60px;" onkeypress="return onlyNum(event);" />
                    <span style="padding-left:10px; font-size:10px; font-style:italic;"> [ 0 to disable auto advance.]</span></td>
                </tr>
                <tr>
                  <th scope="row">Easing</th>
                  <td><select name="advps_easing">
                      <option value="linear" selected="selected">linear</option>
                      <option value="swing">swing</option>
                      <option value="jswing">jswing</option>
                      <option value="easeInQuad">easeInQuad</option>
                      <option value="easeOutQuad">easeOutQuad</option>
                      <option value="easeInOutQuad">easeInOutQuad</option>
                      <option value="easeInCubic">easeInCubic</option>
                      <option value="easeOutCubic">easeOutCubic</option>
                      <option value="easeInOutCubic">easeInOutCubic</option>
                      <option value="easeInQuart">easeInQuart</option>
                      <option value="easeOutQuart">easeOutQuart</option>
                      <option value="easeInOutQuart">easeInOutQuart</option>
                      <option value="easeInQuint">easeInQuint</option>
                      <option value="easeInQuint">easeOutQuint</option>
                      <option value="easeInQuint">easeInOutQuint</option>
                      <option value="easeInSine">easeInSine</option>
                      <option value="easeOutSine">easeOutSine</option>
                      <option value="easeInOutSine">easeInOutSine</option>
                      <option value="easeInExpo">easeInExpo</option>
                      <option value="easeOutExpo">easeOutExpo</option>
                      <option value="easeInOutExpo">easeInOutExpo</option>
                      <option value="easeInCirc">easeInCirc</option>
                      <option value="easeOutCirc">easeOutCirc</option>
                      <option value="easeInOutCirc">easeInOutCirc</option>
                      <option value="easeInBounce">easeInBounce</option>
                      <option value="easeOutBounce">easeOutBounce</option>
                      <option value="easeInOutBounce">easeInOutBounce</option>
                      <option value="easeInElastic">easeInElastic</option>
                      <option value="easeOutElastic">easeOutElastic</option>
                      <option value="easeInOutElastic">easeInOutElastic</option>
                      <option value="easeInBack">easeInBack</option>
                      <option value="easeOutBack">easeOutBack</option>
                      <option value="easeInOutBack">easeInOutBack</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Speed</th>
                  <td><input type="text" name="advps_speed" value="1500" style="width:60px;" onkeypress="return onlyNum(event);" /></td>
                </tr>
                <tr>
                  <th scope="row">Enable pause on hover</th>
                  <td><select name="advps_ps_hover">
                      <option value="yes" selected="selected">Yes</option>
                      <option value="no">No</option>
                    </select></td>
                </tr>
              </table>
            </fieldset>
            <fieldset style="border:1px solid #D9D9D9; border-radius:2px;margin-bottom:10px;">
              <legend style="margin-left:10px;"><strong>Container & Thumbnail</strong></legend>
              <table class="form-table">
                <tr>
                  <th scope="row">Select Thumbnail</th>
                  <td><select name="advps_thumbnail">
                      <option value="thumbnail">thumbnail</option>
                      <option value="medium">medium</option>
                      <option value="large">large</option>
                      <option value="full">full</option>
                      <?php 
                        global $_wp_additional_image_sizes;
                        unset($_wp_additional_image_sizes['post-thumbnail']);
                        foreach($_wp_additional_image_sizes as $tkey => $tval){
                        ?>
                      <option value="<?php echo $tkey;?>"><?php echo $tkey;?></option>
                      <?php
                        }
                        ?>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Default image url</th>
                  <td><input type="text" name="advps_default_image" value="" style="width:250px;" />
                    <span style="padding-left:10px; font-size:10px; font-style:italic;"> [ N.B. If any post doesn't have featured image then default image will be shown.]</span></td>
                </tr>
                <tr>
                  <th scope="row">Slide Container</th>
                  <td>Width&nbsp;
                    <input type="text" name="advps_sld_width" value="820" style="width:80px;" onkeypress="return onlyNum(event);" />
                    px&nbsp;&nbsp;&nbsp;Height&nbsp;
                    <input type="text" name="advps_sld_height" value="270" style="width:80px;" onkeypress="return onlyNum(event);" />
                    px</td>
                </tr>
                <tr>
                  <th scope="row">Padding</th>
                  <td><input type="text" name="advps_contpad1" value="10" style="width:40px;" onkeypress="return onlyNum(event);" />
                    px &nbsp;&nbsp;
                    <input type="text" name="advps_contpad2" value="10" style="width:40px;" onkeypress="return onlyNum(event);" />
                    px&nbsp;&nbsp;
                    <input type="text" name="advps_contpad3" value="10" style="width:40px;" onkeypress="return onlyNum(event);" />
                    px&nbsp;&nbsp;
                    <input type="text" name="advps_contpad4" value="10" style="width:40px;" onkeypress="return onlyNum(event);" />
                    px&nbsp;&nbsp; </td>
                </tr>
                <tr>
                  <th scope="row">Background Color</th>
                  <td><input id="advpscolor<?php echo ++$flg?>" class="advps-color-picker" type="text" name="advps_bgcolor" value="#FFFFFF" style="width:100px;" />
                    <div class="advpsfarb" style="padding-left:22%"></div></td>
                </tr>
                <tr>
                  <th scope="row">Border</th>
                  <td><span style="vertical-align:top">
                    <input type="text" name="advps_border_size" value="<?php echo $dataSet['advps_border_size'];?>" style="width:40px;" onkeypress="return onlyNum(event);" />
                    px &nbsp;&nbsp;
                    <select name="advps_border_type">
                      <option value="dashed">dashed</option>
                      <option value="dotted">dotted</option>
                      <option value="double">double</option>
                      <option value="solid">solid</option>
                      <option value="inset">inset</option>
                      <option value="outset">outset</option>
                    </select>
                    &nbsp;&nbsp;</span>
                    <input id="advpscolor<?php echo ++$flg?>" class="advps-color-picker" type="text" name="advps_border_color" value="#444444" style="width:100px;" />
                    <div class="advpsfarb" style="padding-left:22%"></div></td>
                </tr>
                <tr>
                  <th scope="row">Remove Border</th>
                  <td><select name="advps_remove_border">
                      <option value="yes" selected="selected">Yes</option>
                      <option value="no">No</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Box Shadow</th>
                  <td><span style="vertical-align:top">
                    <input type="text" name="advps_bxshad1" value="0" style="width:40px;" onkeypress="return onlyNum(event);" />
                    px &nbsp;&nbsp;
                    <input type="text" name="advps_bxshad2" value="1" style="width:40px;" onkeypress="return onlyNum(event);" />
                    px&nbsp;&nbsp;
                    <input type="text" name="advps_bxshad3" value="4" style="width:40px;" onkeypress="return onlyNum(event);" />
                    px&nbsp;&nbsp;</span>
                    <input id="advpscolor<?php echo ++$flg?>" class="advps-color-picker" type="text" name="advps_bxshadcolor" value="#000000" style="width:100px;" />
                    <div class="advpsfarb" style="padding-left:22%"></div></td>
                </tr>
                <tr>
                  <th scope="row">Remove Shadow</th>
                  <td><select name="advps_remove_shd">
                      <option value="yes">Yes</option>
                      <option value="no" selected="selected">No</option>
                    </select></td>
                </tr>
              </table>
            </fieldset>
            <fieldset style="border:1px solid #D9D9D9; border-radius:2px;margin-bottom:10px;">
              <legend style="margin-left:10px;"><strong>Title & Excerpt</strong></legend>
              <table class="form-table">
                <tr>
                  <th scope="row">Overlay size</th>
                  <td>Width&nbsp;
                    <input type="text" name="advps_overlay_width" value="30" style="width:80px;" onkeypress="return onlyNum(event);" />
                    %&nbsp;&nbsp;&nbsp;Height&nbsp;
                    <input type="text" name="advps_overlay_height" value="100" style="width:80px;" onkeypress="return onlyNum(event);" />
                    %</td>
                </tr>
                <tr>
                  <th scope="row">Overlay color</th>
                  <td><input id="advpscolor<?php echo ++$flg?>" type="text" name="advps_overlay_color" value="#000000" style="width:100px;" class="advps-color-picker" />
                    <div class="advpsfarb" style="padding-left:22%"></div></td>
                </tr>
                <tr>
                  <th scope="row">Overlay opacity</th>
                  <td><input type="text" name="advps_overlay_opacity" value="0.6" style="width:50px;" />
                    &nbsp;<span style="padding-left:10px; font-size:10px; font-style:italic;">[ 0 - 1 ]</span></td>
                </tr>
                <tr>
                  <th scope="row">Text opacity</th>
                  <td><input type="text" name="advps_text_opacity" value="1" style="width:50px;" />
                    &nbsp;<span style="padding-left:10px; font-size:10px; font-style:italic;">[ 0 - 1 ]</span></td>
                </tr>
                <tr>
                  <th scope="row">Title tag</th>
                  <td><select name="advps_ttitle_tag">
                      <option value="h1">h1</option>
                      <option value="h2" selected="selected">h2</option>
                      <option value="h3">h3</option>
                      <option value="div">div</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Title font Color</th>
                  <td><input id="advpscolor<?php echo ++$flg?>" type="text" name="advps_titleFcolor" value="#FFFFFF" style="width:100px;" class="advps-color-picker" />
                    <div class="advpsfarb" style="padding-left:22%"></div></td>
                </tr>
                <tr>
                  <th scope="row">Title hover Color</th>
                  <td><input id="advpscolor<?php echo ++$flg?>" type="text" name="advps_titleHcolor" value="#E6E6E6" style="width:100px;" class="advps-color-picker" />
                    <div class="advpsfarb" style="padding-left:22%"></div></td>
                </tr>
                <tr>
                  <th scope="row">Title font size</th>
                  <td><input type="text" name="advps_titleFsize" value="22" style="width:60px;" />
                    px</td>
                </tr>
                <tr>
                  <th scope="row">Excerpt font color</th>
                  <td><input id="advpscolor<?php echo ++$flg?>" class="advps-color-picker" type="text" name="advps_excptFcolor" value="#FFFFFF" style="width:100px;" />
                    <div class="advpsfarb" style="padding-left:22%"></div></td>
                </tr>
                <tr>
                  <th scope="row">Excerpt font size</th>
                  <td><input type="text" name="advps_excptFsize" value="12" style="width:60px;" />
                    px</td>
                </tr>
                <tr>
                  <th scope="row">Excerpt length</th>
                  <td><input type="text" name="advps_excerptlen" value="30" style="width:60px;" onkeypress="return onlyNum(event);" />
                    &nbsp;words</td>
                </tr>
                <tr>
                  <th scope="row">Excerpt visibility</th>
                  <td><select name="advps_excpt_visibility">
                      <option value="show on hover">Show on hover</option>
                      <option value="always show" selected="selected">Always show</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Excerpt Position</th>
                  <td><select name="advps_excpt_position">
                      <option value="left" selected="selected">Left</option>
                      <option value="right">Right</option>
                      <option value="bottom">Bottom</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Enable/Disable link</th>
                  <td><select name="advps_ed_link">
                      <option value="enable">Enable</option>
                      <option value="disable" selected="selected">Disable</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">link type</th>
                  <td><select name="advps_link_type">
                      <option value="permalink" selected="selected">Permalink</option>
                      <option value="custom">Custom</option>
                    </select>
                    <span style="padding-left:10px; font-size:10px; font-style:italic;">[ N.B. For custom link create a custom field with name 'advps_custom_link' ]</span></td>
                </tr>
                <tr>
                  <th scope="row">link target</th>
                  <td><select name="advps_link_target">
                      <option value="_self" selected="selected">_self</option>
                      <option value="_blank">_blank</option>
                      <option value="_new">_new</option>
                      <option value="_top">_top</option>
                      <option value="_parent">_parent</option>
                    </select></td>
                </tr>
              </table>
            </fieldset>
            <fieldset style="border:1px solid #D9D9D9; border-radius:2px;margin-bottom:10px;">
              <legend style="margin-left:10px;"><strong>Navigation</strong></legend>
              <table class="form-table">
                <tr>
                  <th scope="row">Exclude pager</th>
                  <td><select name="advps_exclude_pager">
                      <option value="yes">Yes</option>
                      <option value="no" selected="selected">No</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Pager type</th>
                  <td><span style="padding-right:5px;">Number</span>
                    <input type="radio" name="advps_pager_type" value="number">
                    <span style="padding:0px 5px 0px 10px;">Bullet</span>
                    <input type="radio" name="advps_pager_type" value="bullet" checked="checked"></td>
                </tr>
                <tr>
                  <th scope="row">Pager position</th>
                  <td>Right&nbsp;
                    <input type="text" name="advps_pager_right" value="8" style="width:50px;" onkeypress="return onlyNum(event);" />
                    px&nbsp;&nbsp;&nbsp;Bottom&nbsp;
                    <input type="text" name="advps_pager_bottom" value="6" style="width:50px;" onkeypress="return onlyNum(event);" />
                    px</td>
                </tr>
                <tr>
                  <th scope="row">Exclude Next/Previous</th>
                  <td><select name="advps_exclude_nxtprev">
                      <option value="yes">Yes</option>
                      <option value="no" selected="selected">No</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Exclude Play/Pause</th>
                  <td><select name="advps_exclude_playpause">
                      <option value="yes">Yes</option>
                      <option value="no" selected="selected">No</option>
                    </select></td>
                </tr>
              </table>
            </fieldset>
            <input type="hidden" name="template" value="one" />
            <?php wp_nonce_field('advps-checkauthnonce','advps_wpnonce'); ?>
            <p>
              <input type="submit" name="advps_submit" value="Add" class="button-primary" style="width:90px; font-weight:bold" />
              <span style="margin-left:10px;">
              <button class="button-secondary" style="width:90px; height:25px;">Cancel</button>
              </span></p>
          </form>
        </div>
      </div>
    </div>
  </div>
  <?php }else{?>
  <div style="position:relative; float:left; width:100%">
    <form method="post">
      <input type="submit" class="button-secondary" value="Add New" name="advps_add" />
    </form>
  </div>
  <?php }}elseif($currTab == 'two'){foreach( $res2 as $dset){ $dataSet = unserialize($dset->opt_data);?>
  <div class="metabox-holder" style="margin-top:20px;">
    <div class="postbox-container" style="width:65%">
      <div class="postbox <?php if(!isset($_POST['opt_id']) || $_POST['opt_id'] != $dset->id){echo 'closed';}?>">
        <div class="handlediv" title=""> <br>
        </div>
        <h3 style="cursor:pointer; text-align:center" class="advps-expand"> <span>Option Set</span> </h3>
        <div class="inside">
          <div style="position:absolute; top:1.2%; right:4%; font-weight:bold; border:1px solid #393;padding:2px 15px;">ID = <?php echo $dset->id;?></div>
          <form method="post">
            <fieldset>
              <legend style="margin-left:10px;"><strong>Query</strong></legend>
              <table class="form-table">
                <tr>
                  <th scope="row">Post Type</th>
                  <td><select name="advps_post_types" onchange="advpsCheckCat(this.value,<?php echo $dset->id;?>)">
                      <option value="post" <?php if($dataSet['advps_post_types'] == 'post'){echo 'selected="selected"';}?>>post</option>
                      <option value="page" <?php if($dataSet['advps_post_types'] == 'page'){echo 'selected="selected"';}?>>page</option>
                      <?php
                              foreach ($customPostTypes  as $post_type ) {
                          ?>
                      <option value="<?php echo $post_type;?>" <?php if($dataSet['advps_post_types'] == $post_type){echo 'selected="selected"';}?>><?php echo $post_type;?></option>
                      <?php		
                              }
                          ?>
                    </select></td>
                </tr>
                <tr id="advps-cat-field<?php echo $dset->id;?>">
                  <?php  if($dataSet['advps_post_types'] != "page"){?>
                  <th scope="row">Category</th>
                  <td><select name="advps_category[]" multiple="multiple">
                      <?php 
					  	$catList = get_categories();
						foreach($catList as $scat){
					  ?>
                      <option value="<?php echo $scat->term_id;?>" <?php if(isset($dataSet['advps_category']) && in_array($scat->term_id,$dataSet['advps_category'])){echo 'selected="selected"';}?>><?php echo $scat->name;?></option>
                      <?php }?>
                    </select>
                    <span style="padding-left:10px; font-size:10px; font-style:italic; vertical-align:top;">[ * You can select multiple category ]</span></td>
                  <?php }?>
                </tr>
                <tr>
                  <th scope="row">Max. Number of post</th>
                  <td><input type="text" name="advps_maxpost" value="<?php echo $dataSet['advps_maxpost'];?>" style="width:60px;" onkeypress="return onlyNum(event);" />
                    <span style="padding-left:10px; font-size:10px; font-style:italic;">[ * Maximum Slides]</span></td>
                </tr>
                <tr>
                  <th scope="row">Post per Slide</th>
                  <td><input type="text" name="advps_postperslide" value="<?php echo $dataSet['advps_postperslide'];?>" style="width:60px;" onkeypress="return onlyNum(event);" /></td>
                </tr>
                <tr>
                  <th scope="row">Exclude</th>
                  <td><input type="text" name="advps_exclude" value="<?php echo $dataSet['advps_exclude'];?>" style="width:100px;" />
                    <span style="padding-left:10px; font-size:10px; font-style:italic;">[ Ex. 1,5,10 Comma separated post IDs that need to exclude from slideshow ]</span></td>
                </tr>
                <tr>
                  <th scope="row">Order by</th>
                  <td><select name="advps_order_by">
                      <option value="date" <?php if($dataSet['advps_order_by'] == 'date'){echo 'selected="selected"';}?>>Date</option>
                      <option value="ID" <?php if($dataSet['advps_order_by'] == 'ID'){echo 'selected="selected"';}?>>ID</option>
                      <option value="author" <?php if($dataSet['advps_order_by'] == 'author'){echo 'selected="selected"';}?>>Author</option>
                      <option value="title" <?php if($dataSet['advps_order_by'] == 'title'){echo 'selected="selected"';}?>>Title</option>
                      <option value="name" <?php if($dataSet['advps_order_by'] == 'name'){echo 'selected="selected"';}?>>Name</option>
                      <option value="rand" <?php if($dataSet['advps_order_by'] == 'rand'){echo 'selected="selected"';}?>>Random</option>
                      <option value="menu_order" <?php if($dataSet['advps_order_by'] == 'menu_order'){echo 'selected="selected"';}?>>Menu order</option>
                      <option value="comment_count" <?php if($dataSet['advps_order_by'] == 'comment_count'){echo 'selected="selected"';}?>>Comment count</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Order</th>
                  <td><select name="advps_order">
                      <option value="ASC" <?php if($dataSet['advps_order'] == 'ASC'){echo 'selected="selected"';}?>>Ascending</option>
                      <option value="DESC" <?php if($dataSet['advps_order'] == 'DESC'){echo 'selected="selected"';}?>>Descending</option>
                    </select></td>
                </tr>
              </table>
            </fieldset>
            <fieldset>
              <legend style="margin-left:10px;"><strong>Effects</strong></legend>
              <table class="form-table">
                <tr>
                  <th scope="row">Effect</th>
                  <td><select name="advps_effects">
                      <option value="blindX" <?php if($dataSet['advps_effects'] == 'blindX'){echo 'selected="selected"';}?>>blindX</option>
                      <option value="blindY" <?php if($dataSet['advps_effects'] == 'blindY'){echo 'selected="selected"';}?>>blindY</option>
                      <option value="blindZ" <?php if($dataSet['advps_effects'] == 'blindZ'){echo 'selected="selected"';}?>>blindZ</option>
                      <option value="cover" <?php if($dataSet['advps_effects'] == 'cover'){echo 'selected="selected"';}?>>cover</option>
                      <option value="curtainX" <?php if($dataSet['advps_effects'] == 'curtainX'){echo 'selected="selected"';}?>>curtainX</option>
                      <option value="curtainY" <?php if($dataSet['advps_effects'] == 'curtainY'){echo 'selected="selected"';}?>>curtainY</option>
                      <option value="fade" <?php if($dataSet['advps_effects'] == 'fade'){echo 'selected="selected"';}?>>fade</option>
                      <option value="fadeZoom" <?php if($dataSet['advps_effects'] == 'fadeZoom'){echo 'selected="selected"';}?>>fadeZoom</option>
                      <option value="growX" <?php if($dataSet['advps_effects'] == 'growX'){echo 'selected="selected"';}?>>growX</option>
                      <option value="growY" <?php if($dataSet['advps_effects'] == 'growY'){echo 'selected="selected"';}?>>growY</option>
                      <option value="none" <?php if($dataSet['advps_effects'] == 'none'){echo 'selected="selected"';}?>>none</option>
                      <option value="scrollUp" <?php if($dataSet['advps_effects'] == 'scrollUp'){echo 'selected="selected"';}?>>scrollUp</option>
                      <option value="scrollDown" <?php if($dataSet['advps_effects'] == 'scrollDown'){echo 'selected="selected"';}?>>scrollDown</option>
                      <option value="scrollLeft" <?php if($dataSet['advps_effects'] == 'scrollLeft'){echo 'selected="selected"';}?>>scrollLeft</option>
                      <option value="scrollRight" <?php if($dataSet['advps_effects'] == 'scrollRight'){echo 'selected="selected"';}?>>scrollRight</option>
                      <option value="scrollHorz" <?php if($dataSet['advps_effects'] == 'scrollHorz'){echo 'selected="selected"';}?>>scrollHorz</option>
                      <option value="scrollVert" <?php if($dataSet['advps_effects'] == 'scrollVert'){echo 'selected="selected"';}?>>scrollVert</option>
                      <option value="shuffle" <?php if($dataSet['advps_effects'] == 'shuffle'){echo 'selected="selected"';}?>>shuffle</option>
                      <option value="slideX" <?php if($dataSet['advps_effects'] == 'slideX'){echo 'selected="selected"';}?>>slideX</option>
                      <option value="slideY" <?php if($dataSet['advps_effects'] == 'slideY'){echo 'selected="selected"';}?>>slideY</option>
                      <option value="toss" <?php if($dataSet['advps_effects'] == 'toss'){echo 'selected="selected"';}?>>toss</option>
                      <option value="turnUp" <?php if($dataSet['advps_effects'] == 'turnUp'){echo 'selected="selected"';}?>>turnUp</option>
                      <option value="turnDown" <?php if($dataSet['advps_effects'] == 'turnDown'){echo 'selected="selected"';}?>>turnDown</option>
                      <option value="turnLeft" <?php if($dataSet['advps_effects'] == 'turnLeft'){echo 'selected="selected"';}?>>turnLeft</option>
                      <option value="turnRight" <?php if($dataSet['advps_effects'] == 'turnRight'){echo 'selected="selected"';}?>>turnRight</option>
                      <option value="uncover" <?php if($dataSet['advps_effects'] == 'uncover'){echo 'selected="selected"';}?>>uncover</option>
                      <option value="wipe" <?php if($dataSet['advps_effects'] == 'wipe'){echo 'selected="selected"';}?>>wipe</option>
                      <option value="zoom" <?php if($dataSet['advps_effects'] == 'zoom'){echo 'selected="selected"';}?>>zoom</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Timeout</th>
                  <td><input type="text" name="advps_timeout" value="<?php echo $dataSet['advps_timeout'];?>" style="width:60px;" onkeypress="return onlyNum(event);" />
                    <span style="padding-left:10px; font-size:10px; font-style:italic;"> [ 0 to disable auto advance.]</span></td>
                </tr>
                <tr>
                  <th scope="row">Easing</th>
                  <td><select name="advps_easing">
                      <option value="linear" <?php if($dataSet['advps_easing'] == 'linear'){echo 'selected="selected"';}?>>linear</option>
                      <option value="swing" <?php if($dataSet['advps_easing'] == 'swing'){echo 'selected="selected"';}?>>swing</option>
                      <option value="jswing" <?php if($dataSet['advps_easing'] == 'jswing'){echo 'selected="selected"';}?>>jswing</option>
                      <option value="easeInQuad" <?php if($dataSet['advps_easing'] == 'easeInQuad'){echo 'selected="selected"';}?>>easeInQuad</option>
                      <option value="easeOutQuad" <?php if($dataSet['advps_easing'] == 'easeOutQuad'){echo 'selected="selected"';}?>>easeOutQuad</option>
                      <option value="easeInOutQuad" <?php if($dataSet['advps_easing'] == 'easeInOutQuad'){echo 'selected="selected"';}?>>easeInOutQuad</option>
                      <option value="easeInCubic" <?php if($dataSet['advps_easing'] == 'easeInCubic'){echo 'selected="selected"';}?>>easeInCubic</option>
                      <option value="easeOutCubic" <?php if($dataSet['advps_easing'] == 'easeOutCubic'){echo 'selected="selected"';}?>>easeOutCubic</option>
                      <option value="easeInOutCubic" <?php if($dataSet['advps_easing'] == 'easeInOutCubic'){echo 'selected="selected"';}?>>easeInOutCubic</option>
                      <option value="easeInQuart" <?php if($dataSet['advps_easing'] == 'easeInQuart'){echo 'selected="selected"';}?>>easeInQuart</option>
                      <option value="easeOutQuart" <?php if($dataSet['advps_easing'] == 'easeOutQuart'){echo 'selected="selected"';}?>>easeOutQuart</option>
                      <option value="easeInOutQuart" <?php if($dataSet['advps_easing'] == 'easeInOutQuart'){echo 'selected="selected"';}?>>easeInOutQuart</option>
                      <option value="easeInQuint" <?php if($dataSet['advps_easing'] == 'easeInQuint'){echo 'selected="selected"';}?>>easeInQuint</option>
                      <option value="easeInQuint" <?php if($dataSet['advps_easing'] == 'easeOutQuint'){echo 'selected="selected"';}?>>easeOutQuint</option>
                      <option value="easeInQuint" <?php if($dataSet['advps_easing'] == 'easeInOutQuint'){echo 'selected="selected"';}?>>easeInOutQuint</option>
                      <option value="easeInSine" <?php if($dataSet['advps_easing'] == 'easeInSine'){echo 'selected="selected"';}?>>easeInSine</option>
                      <option value="easeOutSine" <?php if($dataSet['advps_easing'] == 'easeOutSine'){echo 'selected="selected"';}?>>easeOutSine</option>
                      <option value="easeInOutSine" <?php if($dataSet['advps_easing'] == 'easeInOutSine'){echo 'selected="selected"';}?>>easeInOutSine</option>
                      <option value="easeInExpo" <?php if($dataSet['advps_easing'] == 'easeInExpo'){echo 'selected="selected"';}?>>easeInExpo</option>
                      <option value="easeOutExpo" <?php if($dataSet['advps_easing'] == 'easeOutExpo'){echo 'selected="selected"';}?>>easeOutExpo</option>
                      <option value="easeInOutExpo" <?php if($dataSet['advps_easing'] == 'easeInOutExpo'){echo 'selected="selected"';}?>>easeInOutExpo</option>
                      <option value="easeInCirc" <?php if($dataSet['advps_easing'] == 'easeInCirc'){echo 'selected="selected"';}?>>easeInCirc</option>
                      <option value="easeOutCirc" <?php if($dataSet['advps_easing'] == 'easeOutCirc'){echo 'selected="selected"';}?>>easeOutCirc</option>
                      <option value="easeInOutCirc" <?php if($dataSet['advps_easing'] == 'easeInOutCirc'){echo 'selected="selected"';}?>>easeInOutCirc</option>
                      <option value="easeInBounce" <?php if($dataSet['advps_easing'] == 'easeInBounce'){echo 'selected="selected"';}?>>easeInBounce</option>
                      <option value="easeOutBounce" <?php if($dataSet['advps_easing'] == 'easeOutBounce'){echo 'selected="selected"';}?>>easeOutBounce</option>
                      <option value="easeInOutBounce" <?php if($dataSet['advps_easing'] == 'easeInOutBounce'){echo 'selected="selected"';}?>>easeInOutBounce</option>
                      <option value="easeInElastic" <?php if($dataSet['advps_easing'] == 'easeInElastic'){echo 'selected="selected"';}?>>easeInElastic</option>
                      <option value="easeOutElastic" <?php if($dataSet['advps_easing'] == 'easeOutElastic'){echo 'selected="selected"';}?>>easeOutElastic</option>
                      <option value="easeInOutElastic" <?php if($dataSet['advps_easing'] == 'easeInOutElastic'){echo 'selected="selected"';}?>>easeInOutElastic</option>
                      <option value="easeInBack" <?php if($dataSet['advps_easing'] == 'easeInBack'){echo 'selected="selected"';}?>>easeInBack</option>
                      <option value="easeOutBack" <?php if($dataSet['advps_easing'] == 'easeOutBack'){echo 'selected="selected"';}?>>easeOutBack</option>
                      <option value="easeInOutBack" <?php if($dataSet['advps_easing'] == 'easeInOutBack'){echo 'selected="selected"';}?>>easeInOutBack</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Speed</th>
                  <td><input type="text" name="advps_speed" value="<?php echo $dataSet['advps_speed'];?>" style="width:60px;" onkeypress="return onlyNum(event);" /></td>
                </tr>
                <tr>
                  <th scope="row">Enable pause on hover</th>
                  <td><select name="advps_ps_hover">
                      <option value="yes" <?php if($dataSet['advps_ps_hover'] == 'yes'){echo 'selected="selected"';}?>>Yes</option>
                      <option value="no" <?php if($dataSet['advps_ps_hover'] == 'no'){echo 'selected="selected"';}?>>No</option>
                    </select></td>
                </tr>
              </table>
            </fieldset>
            <fieldset>
              <legend style="margin-left:10px;"><strong>Container & Thumbnail</strong></legend>
              <table class="form-table">
                <tr>
                  <th scope="row">Select Thumbnail</th>
                  <td><select name="advps_thumbnail">
                      <option value="thumbnail" <?php if($dataSet['advps_thumbnail'] == 'thumbnail'){echo 'selected="selected"';}?>>thumbnail</option>
                      <option value="medium" <?php if($dataSet['advps_thumbnail'] == 'medium'){echo 'selected="selected"';}?>>medium</option>
                      <option value="large" <?php if($dataSet['advps_thumbnail'] == 'large'){echo 'selected="selected"';}?>>large</option>
                      <option value="full" <?php if($dataSet['advps_thumbnail'] == 'full'){echo 'selected="selected"';}?>>full</option>
                      <?php 
                        global $_wp_additional_image_sizes;
                        unset($_wp_additional_image_sizes['post-thumbnail']);
                        foreach($_wp_additional_image_sizes as $tkey => $tval){
                        ?>
                      <option value="<?php echo $tkey;?>" <?php if($dataSet['advps_thumbnail'] == $tkey){echo 'selected="selected"';}?>><?php echo $tkey;?></option>
                      <?php
                        }
                        ?>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Slide Container</th>
                  <td>Width&nbsp;
                    <input type="text" name="advps_sld_width" value="<?php echo $dataSet['advps_sld_width'];?>" style="width:80px;" onkeypress="return onlyNum(event);" />
                    px&nbsp;&nbsp;&nbsp;Height&nbsp;
                    <input type="text" name="advps_sld_height" value="<?php echo $dataSet['advps_sld_height'];?>" style="width:80px;" onkeypress="return onlyNum(event);" />
                    px</td>
                </tr>
                <tr>
                  <th scope="row">Padding</th>
                  <td><input type="text" name="advps_contpad1" value="<?php echo $dataSet['advps_contpad1'];?>" style="width:40px;" onkeypress="return onlyNum(event);" />
                    px &nbsp;&nbsp;
                    <input type="text" name="advps_contpad2" value="<?php echo $dataSet['advps_contpad2'];?>" style="width:40px;" onkeypress="return onlyNum(event);" />
                    px&nbsp;&nbsp;
                    <input type="text" name="advps_contpad3" value="<?php echo $dataSet['advps_contpad3'];?>" style="width:40px;" onkeypress="return onlyNum(event);" />
                    px&nbsp;&nbsp;
                    <input type="text" name="advps_contpad4" value="<?php echo $dataSet['advps_contpad4'];?>" style="width:40px;" onkeypress="return onlyNum(event);" />
                    px&nbsp;&nbsp; </td>
                </tr>
                <tr>
                  <th scope="row">Background Color</th>
                  <td><input id="advpscolor<?php echo ++$flg?>" class="advps-color-picker" type="text" name="advps_bgcolor" value="<?php echo $dataSet['advps_bgcolor'];?>" style="width:100px;" />
                    <div class="advpsfarb" style="padding-left:22%"></div></td>
                </tr>
                <tr>
                  <th scope="row">Border</th>
                  <td><span style="vertical-align:top">
                    <input type="text" name="advps_border_size" value="<?php echo $dataSet['advps_border_size'];?>" style="width:40px;" onkeypress="return onlyNum(event);" />
                    px &nbsp;&nbsp;
                    <select name="advps_border_type">
                      <option value="dashed" <?php if($dataSet['advps_border_type'] == 'dashed'){echo 'selected="selected"';}?>>dashed</option>
                      <option value="dotted" <?php if($dataSet['advps_border_type'] == 'dotted'){echo 'selected="selected"';}?>>dotted</option>
                      <option value="double" <?php if($dataSet['advps_border_type'] == 'double'){echo 'selected="selected"';}?>>double</option>
                      <option value="solid" <?php if($dataSet['advps_border_type'] == 'solid'){echo 'selected="selected"';}?>>solid</option>
                      <option value="inset" <?php if($dataSet['advps_border_type'] == 'inset'){echo 'selected="selected"';}?>>inset</option>
                      <option value="outset" <?php if($dataSet['advps_border_type'] == 'outset'){echo 'selected="selected"';}?>>outset</option>
                    </select>
                    &nbsp;&nbsp;</span>
                    <input id="advpscolor<?php echo ++$flg?>" class="advps-color-picker" type="text" name="advps_border_color" value="<?php echo $dataSet['advps_border_color'];?>" style="width:100px;" />
                    <div class="advpsfarb" style="padding-left:22%"></div></td>
                </tr>
                <tr>
                  <th scope="row">Remove Border</th>
                  <td><select name="advps_remove_border">
                      <option value="yes" <?php if($dataSet['advps_remove_border'] == 'yes'){echo 'selected="selected"';}?>>Yes</option>
                      <option value="no" <?php if($dataSet['advps_remove_border'] == 'no'){echo 'selected="selected"';}?>>No</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Box Shadow</th>
                  <td><span style="vertical-align:top">
                    <input type="text" name="advps_bxshad1" value="<?php echo $dataSet['advps_bxshad1'];?>" style="width:40px;" onkeypress="return onlyNum(event);" />
                    px &nbsp;&nbsp;
                    <input type="text" name="advps_bxshad2" value="<?php echo $dataSet['advps_bxshad2'];?>" style="width:40px;" onkeypress="return onlyNum(event);" />
                    px&nbsp;&nbsp;
                    <input type="text" name="advps_bxshad3" value="<?php echo $dataSet['advps_bxshad3'];?>" style="width:40px;" onkeypress="return onlyNum(event);" />
                    px&nbsp;&nbsp;</span>
                    <input id="advpscolor<?php echo ++$flg?>" class="advps-color-picker" type="text" name="advps_bxshadcolor" value="<?php echo $dataSet['advps_bxshadcolor'];?>" style="width:100px;" />
                    <div class="advpsfarb" style="padding-left:22%"></div></td>
                </tr>
                <tr>
                  <th scope="row">Remove Shadow</th>
                  <td><select name="advps_remove_shd">
                      <option value="yes" <?php if($dataSet['advps_remove_shd'] == 'yes'){echo 'selected="selected"';}?>>Yes</option>
                      <option value="no" <?php if($dataSet['advps_remove_shd'] == 'no'){echo 'selected="selected"';}?>>No</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Image Orientation</th>
                  <td><select name="advps_img_Orientation">
                      <option value="horizontal" <?php if($dataSet['advps_img_Orientation'] == 'horizontal'){echo 'selected="selected"';}?>>Horizontal</option>
                      <option value="vertical" <?php if($dataSet['advps_img_Orientation'] == 'vertical'){echo 'selected="selected"';}?>>Vertical</option>
                    </select>
                    <span style="padding-left:10px; font-size:10px; font-style:italic;">[ * Skip this if you want to show single image per slide.]</span></td>
                </tr>
                <tr>
                  <th scope="row">Margin between Images</th>
                  <td><input type="text" name="advps_imagemar" value="<?php echo $dataSet['advps_imagemar'];?>" style="width:40px;" onkeypress="return onlyNum(event);" />
                    px &nbsp;&nbsp;<span style="padding-left:10px; font-size:10px; font-style:italic;">[ * Skip this if you want to show single image per slide.]</span></td>
                </tr>
                <tr>
                  <th scope="row">Enable/Disable link</th>
                  <td><select name="advps_ed_link">
                      <option value="enable" <?php if($dataSet['advps_ed_link'] == 'enable'){echo 'selected="selected"';}?>>Enable</option>
                      <option value="disable" <?php if($dataSet['advps_ed_link'] == 'disable'){echo 'selected="selected"';}?>>Disable</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">link type</th>
                  <td><select name="advps_link_type">
                      <option value="permalink" <?php if($dataSet['advps_link_type'] == 'permalink'){echo 'selected="selected"';}?>>Permalink</option>
                      <option value="custom" <?php if($dataSet['advps_link_type'] == 'custom'){echo 'selected="selected"';}?>>Custom</option>
                    </select>
                    <span style="padding-left:10px; font-size:10px; font-style:italic;">[ N.B. For custom link create a custom field with name 'advps_custom_link' ]</span></td>
                </tr>
                <tr>
                  <th scope="row">link target</th>
                  <td><select name="advps_link_target">
                      <option value="_self" <?php if($dataSet['advps_link_target'] == '_self'){echo 'selected="selected"';}?>>_self</option>
                      <option value="_blank" <?php if($dataSet['advps_link_target'] == '_blank'){echo 'selected="selected"';}?>>_blank</option>
                      <option value="_new" <?php if($dataSet['advps_link_target'] == '_new'){echo 'selected="selected"';}?>>_new</option>
                      <option value="_top" <?php if($dataSet['advps_link_target'] == '_top'){echo 'selected="selected"';}?>>_top</option>
                      <option value="_parent" <?php if($dataSet['advps_link_target'] == '_parent'){echo 'selected="selected"';}?>>_parent</option>
                    </select></td>
                </tr>
              </table>
            </fieldset>
            <fieldset>
              <legend style="margin-left:10px;"><strong>Navigation</strong></legend>
              <table class="form-table">
                <tr>
                  <th scope="row">Exclude pager</th>
                  <td><select name="advps_exclude_pager">
                      <option value="yes" <?php if($dataSet['advps_exclude_pager'] == 'yes'){echo 'selected="selected"';}?>>Yes</option>
                      <option value="no" <?php if($dataSet['advps_exclude_pager'] == 'no'){echo 'selected="selected"';}?>>No</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Pager type</th>
                  <td><span style="padding-right:5px;">Number</span>
                    <input type="radio" name="advps_pager_type" value="number" <?php if($dataSet['advps_pager_type'] == 'number'){echo 'checked="checked"';}?>>
                    <span style="padding:0px 5px 0px 10px;">Bullet</span>
                    <input type="radio" name="advps_pager_type" value="bullet" <?php if($dataSet['advps_pager_type'] == 'bullet'){echo 'checked="checked"';}?>></td>
                </tr>
                <tr>
                  <th scope="row">Pager position</th>
                  <td>Right&nbsp;
                    <input type="text" name="advps_pager_right" value="<?php echo $dataSet['advps_pager_right'];?>" style="width:50px;" onkeypress="return onlyNum(event);" />
                    px&nbsp;&nbsp;&nbsp;Bottom&nbsp;
                    <input type="text" name="advps_pager_bottom" value="<?php echo $dataSet['advps_pager_bottom'];?>" style="width:50px;" onkeypress="return onlyNum(event);" />
                    px</td>
                </tr>
                <tr>
                  <th scope="row">Exclude Next/Previous</th>
                  <td><select name="advps_exclude_nxtprev">
                      <option value="yes" <?php if($dataSet['advps_exclude_nxtprev'] == 'yes'){echo 'selected="selected"';}?>>Yes</option>
                      <option value="no" <?php if($dataSet['advps_exclude_nxtprev'] == 'no'){echo 'selected="selected"';}?>>No</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Exclude Play/Pause</th>
                  <td><select name="advps_exclude_playpause">
                      <option value="yes" <?php if($dataSet['advps_exclude_playpause'] == 'yes'){echo 'selected="selected"';}?>>Yes</option>
                      <option value="no" <?php if($dataSet['advps_exclude_playpause'] == 'no'){echo 'selected="selected"';}?>>No</option>
                    </select></td>
                </tr>
              </table>
            </fieldset>
            <input type="hidden" value="<?php echo $dset->id;?>" name="opt_id" />
            <?php wp_nonce_field('advps-checkauthnonce','advps_wpnonce'); ?>
            <p>
              <input type="submit" name="advps_submit" value="Save changes" class="button-primary" />
            </p>
          </form>
        </div>
      </div>
    </div>
  </div>
  <?php }?>
  <?php if(isset($_POST['advps_add'])){?>
  <div class="metabox-holder" style="margin-top:20px;">
    <div class="postbox-container" style="width:65%">
      <div class="postbox">
        <div class="handlediv" title=""> <br>
        </div>
        <h3 style="cursor:pointer; text-align:center" class="advps-expand"> <span>Add new option set</span> </h3>
        <div class="inside">
          <form method="post">
            <fieldset style="border:1px solid #D9D9D9; border-radius:2px; margin-bottom:10px;">
              <legend style="margin-left:10px;"><strong>Query</strong></legend>
              <table class="form-table">
                <tr>
                  <th scope="row">Post Type</th>
                  <td><select name="advps_post_types" onchange="advpsCheckCat(this.value,'add')">
                      <option value="post" selected="selected">post</option>
                      <option value="page">page</option>
                      <?php
                              foreach ($customPostTypes  as $post_type ) {
                          ?>
                      <option value="<?php echo $post_type;?>"><?php echo $post_type;?></option>
                      <?php		
                              }
                          ?>
                    </select></td>
                </tr>
                <tr id="advps-cat-fieldadd">
                  <th scope="row">Category</th>
                  <td><select name="advps_category[]" multiple="multiple">
                      <?php 
					  	$catList = get_categories();
						foreach($catList as $scat){
					  ?>
                      <option value="<?php echo $scat->term_id;?>"><?php echo $scat->name;?></option>
                      <?php }?>
                    </select>
                    <span style="padding-left:10px; font-size:10px; font-style:italic; vertical-align:top;">[ * You can select multiple category ]</span></td>
                </tr>
                <tr>
                  <th scope="row">Max. Number of post</th>
                  <td><input type="text" name="advps_maxpost" value="10" style="width:60px;" onkeypress="return onlyNum(event);" /></td>
                </tr>
                <tr>
                  <th scope="row">Post per Slide</th>
                  <td><input type="text" name="advps_postperslide" value="1" style="width:60px;" onkeypress="return onlyNum(event);" /></td>
                </tr>
                <tr>
                  <th scope="row">Exclude</th>
                  <td><input type="text" name="advps_exclude" value="" style="width:100px;" />
                    <span style="padding-left:10px; font-size:10px; font-style:italic;">[ Ex. 1,5,10 Comma separated post IDs that need to exclude from slideshow ]</span></td>
                </tr>
                <tr>
                  <th scope="row">Order by</th>
                  <td><select name="advps_order_by">
                      <option value="date" selected="selected">Date</option>
                      <option value="ID">ID</option>
                      <option value="author">Author</option>
                      <option value="title">Title</option>
                      <option value="name">Name</option>
                      <option value="rand">Random</option>
                      <option value="menu_order">Menu order</option>
                      <option value="comment_count">Comment count</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Order</th>
                  <td><select name="advps_order">
                      <option value="ASC">Ascending</option>
                      <option value="DESC" selected="selected">Descending</option>
                    </select></td>
                </tr>
              </table>
            </fieldset>
            <fieldset style="border:1px solid #D9D9D9; border-radius:2px;margin-bottom:10px;">
              <legend style="margin-left:10px;"><strong>Effects</strong></legend>
              <table class="form-table">
                <tr>
                  <th scope="row">Effect</th>
                  <td><select name="advps_effects">
                      <option value="blindX">blindX</option>
                      <option value="blindY">blindY</option>
                      <option value="blindZ">blindZ</option>
                      <option value="cover">cover</option>
                      <option value="curtainX">curtainX</option>
                      <option value="curtainY">curtainY</option>
                      <option value="fade">fade</option>
                      <option value="fadeZoom">fadeZoom</option>
                      <option value="growX">growX</option>
                      <option value="growY">growY</option>
                      <option value="none">none</option>
                      <option value="scrollUp">scrollUp</option>
                      <option value="scrollDown">scrollDown</option>
                      <option value="scrollLeft">scrollLeft</option>
                      <option value="scrollRight">scrollRight</option>
                      <option value="scrollHorz">scrollHorz</option>
                      <option value="scrollVert">scrollVert</option>
                      <option value="shuffle">shuffle</option>
                      <option value="slideX">slideX</option>
                      <option value="slideY">slideY</option>
                      <option value="toss">toss</option>
                      <option value="turnUp">turnUp</option>
                      <option value="turnDown">turnDown</option>
                      <option value="turnLeft">turnLeft</option>
                      <option value="turnRight">turnRight</option>
                      <option value="uncover" selected="selected">uncover</option>
                      <option value="wipe">wipe</option>
                      <option value="zoom">zoom</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Timeout</th>
                  <td><input type="text" name="advps_timeout" value="2000" style="width:60px;" onkeypress="return onlyNum(event);" />
                    <span style="padding-left:10px; font-size:10px; font-style:italic;"> [ 0 to disable auto advance.]</span></td>
                </tr>
                <tr>
                  <th scope="row">Easing</th>
                  <td><select name="advps_easing">
                      <option value="linear" selected="selected">linear</option>
                      <option value="swing">swing</option>
                      <option value="jswing">jswing</option>
                      <option value="easeInQuad">easeInQuad</option>
                      <option value="easeOutQuad">easeOutQuad</option>
                      <option value="easeInOutQuad">easeInOutQuad</option>
                      <option value="easeInCubic">easeInCubic</option>
                      <option value="easeOutCubic">easeOutCubic</option>
                      <option value="easeInOutCubic">easeInOutCubic</option>
                      <option value="easeInQuart">easeInQuart</option>
                      <option value="easeOutQuart">easeOutQuart</option>
                      <option value="easeInOutQuart">easeInOutQuart</option>
                      <option value="easeInQuint">easeInQuint</option>
                      <option value="easeInQuint">easeOutQuint</option>
                      <option value="easeInQuint">easeInOutQuint</option>
                      <option value="easeInSine">easeInSine</option>
                      <option value="easeOutSine">easeOutSine</option>
                      <option value="easeInOutSine">easeInOutSine</option>
                      <option value="easeInExpo">easeInExpo</option>
                      <option value="easeOutExpo">easeOutExpo</option>
                      <option value="easeInOutExpo">easeInOutExpo</option>
                      <option value="easeInCirc">easeInCirc</option>
                      <option value="easeOutCirc">easeOutCirc</option>
                      <option value="easeInOutCirc">easeInOutCirc</option>
                      <option value="easeInBounce">easeInBounce</option>
                      <option value="easeOutBounce">easeOutBounce</option>
                      <option value="easeInOutBounce">easeInOutBounce</option>
                      <option value="easeInElastic">easeInElastic</option>
                      <option value="easeOutElastic">easeOutElastic</option>
                      <option value="easeInOutElastic">easeInOutElastic</option>
                      <option value="easeInBack">easeInBack</option>
                      <option value="easeOutBack">easeOutBack</option>
                      <option value="easeInOutBack">easeInOutBack</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Speed</th>
                  <td><input type="text" name="advps_speed" value="1500" style="width:60px;" onkeypress="return onlyNum(event);" /></td>
                </tr>
                <tr>
                  <th scope="row">Enable pause on hover</th>
                  <td><select name="advps_ps_hover">
                      <option value="yes" selected="selected">Yes</option>
                      <option value="no">No</option>
                    </select></td>
                </tr>
              </table>
            </fieldset>
            <fieldset style="border:1px solid #D9D9D9; border-radius:2px;margin-bottom:10px;">
              <legend style="margin-left:10px;"><strong>Container & Thumbnail</strong></legend>
              <table class="form-table">
                <tr>
                  <th scope="row">Select Thumbnail</th>
                  <td><select name="advps_thumbnail">
                      <option value="thumbnail">thumbnail</option>
                      <option value="medium">medium</option>
                      <option value="large">large</option>
                      <option value="full">full</option>
                      <?php 
                        global $_wp_additional_image_sizes;
                        unset($_wp_additional_image_sizes['post-thumbnail']);
                        foreach($_wp_additional_image_sizes as $tkey => $tval){
                        ?>
                      <option value="<?php echo $tkey;?>" <?php if('advps-thumb-one' == $tkey){echo 'selected="selected"';}?>><?php echo $tkey;?></option>
                      <?php
                        }
                        ?>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Slide Container</th>
                  <td>Width&nbsp;
                    <input type="text" name="advps_sld_width" value="624" style="width:80px;" onkeypress="return onlyNum(event);" />
                    px&nbsp;&nbsp;&nbsp;Height&nbsp;
                    <input type="text" name="advps_sld_height" value="225" style="width:80px;" onkeypress="return onlyNum(event);" />
                    px</td>
                </tr>
                <tr>
                  <th scope="row">Padding</th>
                  <td><input type="text" name="advps_contpad1" value="10" style="width:40px;" onkeypress="return onlyNum(event);" />
                    px &nbsp;&nbsp;
                    <input type="text" name="advps_contpad2" value="10" style="width:40px;" onkeypress="return onlyNum(event);" />
                    px&nbsp;&nbsp;
                    <input type="text" name="advps_contpad3" value="10" style="width:40px;" onkeypress="return onlyNum(event);" />
                    px&nbsp;&nbsp;
                    <input type="text" name="advps_contpad4" value="10" style="width:40px;" onkeypress="return onlyNum(event);" />
                    px&nbsp;&nbsp; </td>
                </tr>
                <tr>
                  <th scope="row">Background Color</th>
                  <td><input id="advpscolor<?php echo ++$flg?>" class="advps-color-picker" type="text" name="advps_bgcolor" value="#FFFFFF" style="width:100px;" />
                    <div class="advpsfarb" style="padding-left:22%"></div></td>
                </tr>
                <tr>
                  <th scope="row">Border</th>
                  <td><span style="vertical-align:top">
                    <input type="text" name="advps_border_size" value="<?php echo $dataSet['advps_border_size'];?>" style="width:40px;" onkeypress="return onlyNum(event);" />
                    px &nbsp;&nbsp;
                    <select name="advps_border_type">
                      <option value="dashed">dashed</option>
                      <option value="dotted">dotted</option>
                      <option value="double">double</option>
                      <option value="solid">solid</option>
                      <option value="inset">inset</option>
                      <option value="outset">outset</option>
                    </select>
                    &nbsp;&nbsp;</span>
                    <input id="advpscolor<?php echo ++$flg?>" class="advps-color-picker" type="text" name="advps_border_color" value="#444444" style="width:100px;" />
                    <div class="advpsfarb" style="padding-left:22%"></div></td>
                </tr>
                <tr>
                  <th scope="row">Remove Border</th>
                  <td><select name="advps_remove_border">
                      <option value="yes" selected="selected">Yes</option>
                      <option value="no">No</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Box Shadow</th>
                  <td><span style="vertical-align:top">
                    <input type="text" name="advps_bxshad1" value="0" style="width:40px;" onkeypress="return onlyNum(event);" />
                    px &nbsp;&nbsp;
                    <input type="text" name="advps_bxshad2" value="1" style="width:40px;" onkeypress="return onlyNum(event);" />
                    px&nbsp;&nbsp;
                    <input type="text" name="advps_bxshad3" value="4" style="width:40px;" onkeypress="return onlyNum(event);" />
                    px&nbsp;&nbsp;</span>
                    <input id="advpscolor<?php echo ++$flg?>" class="advps-color-picker" type="text" name="advps_bxshadcolor" value="#000000" style="width:100px;" />
                    <div class="advpsfarb" style="padding-left:22%"></div></td>
                </tr>
                <tr>
                  <th scope="row">Remove Shadow</th>
                  <td><select name="advps_remove_shd">
                      <option value="yes">Yes</option>
                      <option value="no" selected="selected">No</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Image Orientation</th>
                  <td><select name="advps_img_Orientation">
                      <option value="horizontal" selected="selected">Horizontal</option>
                      <option value="vertical">Vertical</option>
                    </select>
                    <span style="padding-left:10px; font-size:10px; font-style:italic;">[ * Skip this if you want to show single image per slide.]</span></td>
                </tr>
                <tr>
                  <th scope="row">Margin between Images</th>
                  <td><input type="text" name="advps_imagemar" value="10" style="width:40px;" onkeypress="return onlyNum(event);" />
                    px &nbsp;&nbsp;<span style="padding-left:10px; font-size:10px; font-style:italic;">[ * Skip this if you want to show single image per slide.]</span></td>
                </tr>
                <tr>
                  <th scope="row">Enable/Disable link</th>
                  <td><select name="advps_ed_link">
                      <option value="enable">Enable</option>
                      <option value="disable" selected="selected">Disable</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">link type</th>
                  <td><select name="advps_link_type">
                      <option value="permalink" selected="selected">Permalink</option>
                      <option value="custom">Custom</option>
                    </select>
                    <span style="padding-left:10px; font-size:10px; font-style:italic;">[ N.B. For custom link create a custom field with name 'advps_custom_link' ]</span></td>
                </tr>
                <tr>
                  <th scope="row">link target</th>
                  <td><select name="advps_link_target">
                      <option value="_self" selected="selected">_self</option>
                      <option value="_blank">_blank</option>
                      <option value="_new">_new</option>
                      <option value="_top">_top</option>
                      <option value="_parent">_parent</option>
                    </select></td>
                </tr>
              </table>
            </fieldset>
            <fieldset style="border:1px solid #D9D9D9; border-radius:2px;margin-bottom:10px;">
              <legend style="margin-left:10px;"><strong>Navigation</strong></legend>
              <table class="form-table">
                <tr>
                  <th scope="row">Exclude pager</th>
                  <td><select name="advps_exclude_pager">
                      <option value="yes">Yes</option>
                      <option value="no" selected="selected">No</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Pager type</th>
                  <td><span style="padding-right:5px;">Number</span>
                    <input type="radio" name="advps_pager_type" value="number">
                    <span style="padding:0px 5px 0px 10px;">Bullet</span>
                    <input type="radio" name="advps_pager_type" value="bullet" checked="checked"></td>
                </tr>
                <tr>
                  <th scope="row">Pager position</th>
                  <td>Right&nbsp;
                    <input type="text" name="advps_pager_right" value="8" style="width:50px;" onkeypress="return onlyNum(event);" />
                    px&nbsp;&nbsp;&nbsp;Bottom&nbsp;
                    <input type="text" name="advps_pager_bottom" value="6" style="width:50px;" onkeypress="return onlyNum(event);" />
                    px</td>
                </tr>
                <tr>
                  <th scope="row">Exclude Next/Previous</th>
                  <td><select name="advps_exclude_nxtprev">
                      <option value="yes">Yes</option>
                      <option value="no" selected="selected">No</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Exclude Play/Pause</th>
                  <td><select name="advps_exclude_playpause">
                      <option value="yes">Yes</option>
                      <option value="no" selected="selected">No</option>
                    </select></td>
                </tr>
              </table>
            </fieldset>
            <input type="hidden" name="template" value="two" />
            <?php wp_nonce_field('advps-checkauthnonce','advps_wpnonce'); ?>
            <p>
              <input type="submit" name="advps_submit" value="Add" class="button-primary" style="width:90px; font-weight:bold" />
              <span style="margin-left:10px;">
              <button class="button-secondary" style="width:90px; height:25px;">Cancel</button>
              </span></p>
          </form>
        </div>
      </div>
    </div>
  </div>
  <?php }else{?>
  <div style="position:relative; float:left; width:100%">
    <form method="post">
      <input type="submit" class="button-secondary" value="Add New" name="advps_add" />
    </form>
  </div>
  <?php }}elseif($currTab == 'three'){foreach( $res3 as $dset){ $dataSet = unserialize($dset->opt_data)?>
  <div class="metabox-holder" style="margin-top:20px;">
    <div class="postbox-container" style="width:65%">
      <div class="postbox <?php if(!isset($_POST['opt_id']) || $_POST['opt_id'] != $dset->id){echo 'closed';}?>">
        <div class="handlediv" title=""> <br>
        </div>
        <h3 style="cursor:pointer; text-align:center" class="advps-expand"> <span>Option Set</span> </h3>
        <div class="inside">
          <div style="position:absolute; top:1.2%; right:4%; font-weight:bold; border:1px solid #393;padding:2px 15px;">ID = <?php echo $dset->id;?></div>
          <form method="post">
            <fieldset>
              <legend style="margin-left:10px;"><strong>Query</strong></legend>
              <table class="form-table">
                <tr>
                  <th scope="row">Post Type</th>
                  <td><select name="advps_post_types" onchange="advpsCheckCat(this.value,<?php echo $dset->id;?>)">
                      <option value="post" <?php if($dataSet['advps_post_types'] == 'post'){echo 'selected="selected"';}?>>post</option>
                      <option value="page" <?php if($dataSet['advps_post_types'] == 'page'){echo 'selected="selected"';}?>>page</option>
                      <?php
                              foreach ($customPostTypes  as $post_type ) {
                          ?>
                      <option value="<?php echo $post_type;?>" <?php if($dataSet['advps_post_types'] == $post_type){echo 'selected="selected"';}?>><?php echo $post_type;?></option>
                      <?php		
                              }
                          ?>
                    </select></td>
                </tr>
                <tr id="advps-cat-field<?php echo $dset->id;?>">
                  <?php  if($dataSet['advps_post_types'] != "page"){?>
                  <th scope="row">Category</th>
                  <td><select name="advps_category[]" multiple="multiple">
                      <?php 
					  	$catList = get_categories();
						foreach($catList as $scat){
					  ?>
                      <option value="<?php echo $scat->term_id;?>" <?php if(isset($dataSet['advps_category']) && in_array($scat->term_id,$dataSet['advps_category'])){echo 'selected="selected"';}?>><?php echo $scat->name;?></option>
                      <?php }?>
                    </select>
                    <span style="padding-left:10px; font-size:10px; font-style:italic; vertical-align:top">[ * You can select multiple category ]</span></td>
                  <?php }?>
                </tr>
                <tr>
                  <th scope="row">Max. Number of post</th>
                  <td><input type="text" name="advps_maxpost" value="<?php echo $dataSet['advps_maxpost'];?>" style="width:60px;" onkeypress="return onlyNum(event);" /></td>
                </tr>
                <tr>
                  <th scope="row">Exclude</th>
                  <td><input type="text" name="advps_exclude" value="<?php echo $dataSet['advps_exclude'];?>" style="width:100px;" />
                    <span style="padding-left:10px; font-size:10px; font-style:italic;">[ Ex. 1,5,10 Comma separated post IDs that need to exclude from slideshow ]</span></td>
                </tr>
                <tr>
                  <th scope="row">Order by</th>
                  <td><select name="advps_order_by">
                      <option value="date" <?php if($dataSet['advps_order_by'] == 'date'){echo 'selected="selected"';}?>>Date</option>
                      <option value="ID" <?php if($dataSet['advps_order_by'] == 'ID'){echo 'selected="selected"';}?>>ID</option>
                      <option value="author" <?php if($dataSet['advps_order_by'] == 'author'){echo 'selected="selected"';}?>>Author</option>
                      <option value="title" <?php if($dataSet['advps_order_by'] == 'title'){echo 'selected="selected"';}?>>Title</option>
                      <option value="name" <?php if($dataSet['advps_order_by'] == 'name'){echo 'selected="selected"';}?>>Name</option>
                      <option value="rand" <?php if($dataSet['advps_order_by'] == 'rand'){echo 'selected="selected"';}?>>Random</option>
                      <option value="menu_order" <?php if($dataSet['advps_order_by'] == 'menu_order'){echo 'selected="selected"';}?>>Menu order</option>
                      <option value="comment_count" <?php if($dataSet['advps_order_by'] == 'comment_count'){echo 'selected="selected"';}?>>Comment count</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Order</th>
                  <td><select name="advps_order">
                      <option value="ASC" <?php if($dataSet['advps_order'] == 'ASC'){echo 'selected="selected"';}?>>Ascending</option>
                      <option value="DESC" <?php if($dataSet['advps_order'] == 'DESC'){echo 'selected="selected"';}?>>Descending</option>
                    </select></td>
                </tr>
              </table>
            </fieldset>
            <fieldset>
              <legend style="margin-left:10px;"><strong>Effects</strong></legend>
              <table class="form-table">
                <tr>
                  <th scope="row">Effect</th>
                  <td><select name="advps_effects">
                      <option value="blindX" <?php if($dataSet['advps_effects'] == 'blindX'){echo 'selected="selected"';}?>>blindX</option>
                      <option value="blindY" <?php if($dataSet['advps_effects'] == 'blindY'){echo 'selected="selected"';}?>>blindY</option>
                      <option value="blindZ" <?php if($dataSet['advps_effects'] == 'blindZ'){echo 'selected="selected"';}?>>blindZ</option>
                      <option value="cover" <?php if($dataSet['advps_effects'] == 'cover'){echo 'selected="selected"';}?>>cover</option>
                      <option value="curtainX" <?php if($dataSet['advps_effects'] == 'curtainX'){echo 'selected="selected"';}?>>curtainX</option>
                      <option value="curtainY" <?php if($dataSet['advps_effects'] == 'curtainY'){echo 'selected="selected"';}?>>curtainY</option>
                      <option value="fade" <?php if($dataSet['advps_effects'] == 'fade'){echo 'selected="selected"';}?>>fade</option>
                      <option value="fadeZoom" <?php if($dataSet['advps_effects'] == 'fadeZoom'){echo 'selected="selected"';}?>>fadeZoom</option>
                      <option value="growX" <?php if($dataSet['advps_effects'] == 'growX'){echo 'selected="selected"';}?>>growX</option>
                      <option value="growY" <?php if($dataSet['advps_effects'] == 'growY'){echo 'selected="selected"';}?>>growY</option>
                      <option value="none" <?php if($dataSet['advps_effects'] == 'none'){echo 'selected="selected"';}?>>none</option>
                      <option value="scrollUp" <?php if($dataSet['advps_effects'] == 'scrollUp'){echo 'selected="selected"';}?>>scrollUp</option>
                      <option value="scrollDown" <?php if($dataSet['advps_effects'] == 'scrollDown'){echo 'selected="selected"';}?>>scrollDown</option>
                      <option value="scrollLeft" <?php if($dataSet['advps_effects'] == 'scrollLeft'){echo 'selected="selected"';}?>>scrollLeft</option>
                      <option value="scrollRight" <?php if($dataSet['advps_effects'] == 'scrollRight'){echo 'selected="selected"';}?>>scrollRight</option>
                      <option value="scrollHorz" <?php if($dataSet['advps_effects'] == 'scrollHorz'){echo 'selected="selected"';}?>>scrollHorz</option>
                      <option value="scrollVert" <?php if($dataSet['advps_effects'] == 'scrollVert'){echo 'selected="selected"';}?>>scrollVert</option>
                      <option value="shuffle" <?php if($dataSet['advps_effects'] == 'shuffle'){echo 'selected="selected"';}?>>shuffle</option>
                      <option value="slideX" <?php if($dataSet['advps_effects'] == 'slideX'){echo 'selected="selected"';}?>>slideX</option>
                      <option value="slideY" <?php if($dataSet['advps_effects'] == 'slideY'){echo 'selected="selected"';}?>>slideY</option>
                      <option value="toss" <?php if($dataSet['advps_effects'] == 'toss'){echo 'selected="selected"';}?>>toss</option>
                      <option value="turnUp" <?php if($dataSet['advps_effects'] == 'turnUp'){echo 'selected="selected"';}?>>turnUp</option>
                      <option value="turnDown" <?php if($dataSet['advps_effects'] == 'turnDown'){echo 'selected="selected"';}?>>turnDown</option>
                      <option value="turnLeft" <?php if($dataSet['advps_effects'] == 'turnLeft'){echo 'selected="selected"';}?>>turnLeft</option>
                      <option value="turnRight" <?php if($dataSet['advps_effects'] == 'turnRight'){echo 'selected="selected"';}?>>turnRight</option>
                      <option value="uncover" <?php if($dataSet['advps_effects'] == 'uncover'){echo 'selected="selected"';}?>>uncover</option>
                      <option value="wipe" <?php if($dataSet['advps_effects'] == 'wipe'){echo 'selected="selected"';}?>>wipe</option>
                      <option value="zoom" <?php if($dataSet['advps_effects'] == 'zoom'){echo 'selected="selected"';}?>>zoom</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Timeout</th>
                  <td><input type="text" name="advps_timeout" value="<?php echo $dataSet['advps_timeout'];?>" style="width:60px;" onkeypress="return onlyNum(event);" />
                    <span style="padding-left:10px; font-size:10px; font-style:italic;"> [ 0 to disable auto advance.]</span></td>
                </tr>
                <tr>
                  <th scope="row">Easing</th>
                  <td><select name="advps_easing">
                      <option value="linear" <?php if($dataSet['advps_easing'] == 'linear'){echo 'selected="selected"';}?>>linear</option>
                      <option value="swing" <?php if($dataSet['advps_easing'] == 'swing'){echo 'selected="selected"';}?>>swing</option>
                      <option value="jswing" <?php if($dataSet['advps_easing'] == 'jswing'){echo 'selected="selected"';}?>>jswing</option>
                      <option value="easeInQuad" <?php if($dataSet['advps_easing'] == 'easeInQuad'){echo 'selected="selected"';}?>>easeInQuad</option>
                      <option value="easeOutQuad" <?php if($dataSet['advps_easing'] == 'easeOutQuad'){echo 'selected="selected"';}?>>easeOutQuad</option>
                      <option value="easeInOutQuad" <?php if($dataSet['advps_easing'] == 'easeInOutQuad'){echo 'selected="selected"';}?>>easeInOutQuad</option>
                      <option value="easeInCubic" <?php if($dataSet['advps_easing'] == 'easeInCubic'){echo 'selected="selected"';}?>>easeInCubic</option>
                      <option value="easeOutCubic" <?php if($dataSet['advps_easing'] == 'easeOutCubic'){echo 'selected="selected"';}?>>easeOutCubic</option>
                      <option value="easeInOutCubic" <?php if($dataSet['advps_easing'] == 'easeInOutCubic'){echo 'selected="selected"';}?>>easeInOutCubic</option>
                      <option value="easeInQuart" <?php if($dataSet['advps_easing'] == 'easeInQuart'){echo 'selected="selected"';}?>>easeInQuart</option>
                      <option value="easeOutQuart" <?php if($dataSet['advps_easing'] == 'easeOutQuart'){echo 'selected="selected"';}?>>easeOutQuart</option>
                      <option value="easeInOutQuart" <?php if($dataSet['advps_easing'] == 'easeInOutQuart'){echo 'selected="selected"';}?>>easeInOutQuart</option>
                      <option value="easeInQuint" <?php if($dataSet['advps_easing'] == 'easeInQuint'){echo 'selected="selected"';}?>>easeInQuint</option>
                      <option value="easeInQuint" <?php if($dataSet['advps_easing'] == 'easeOutQuint'){echo 'selected="selected"';}?>>easeOutQuint</option>
                      <option value="easeInQuint" <?php if($dataSet['advps_easing'] == 'easeInOutQuint'){echo 'selected="selected"';}?>>easeInOutQuint</option>
                      <option value="easeInSine" <?php if($dataSet['advps_easing'] == 'easeInSine'){echo 'selected="selected"';}?>>easeInSine</option>
                      <option value="easeOutSine" <?php if($dataSet['advps_easing'] == 'easeOutSine'){echo 'selected="selected"';}?>>easeOutSine</option>
                      <option value="easeInOutSine" <?php if($dataSet['advps_easing'] == 'easeInOutSine'){echo 'selected="selected"';}?>>easeInOutSine</option>
                      <option value="easeInExpo" <?php if($dataSet['advps_easing'] == 'easeInExpo'){echo 'selected="selected"';}?>>easeInExpo</option>
                      <option value="easeOutExpo" <?php if($dataSet['advps_easing'] == 'easeOutExpo'){echo 'selected="selected"';}?>>easeOutExpo</option>
                      <option value="easeInOutExpo" <?php if($dataSet['advps_easing'] == 'easeInOutExpo'){echo 'selected="selected"';}?>>easeInOutExpo</option>
                      <option value="easeInCirc" <?php if($dataSet['advps_easing'] == 'easeInCirc'){echo 'selected="selected"';}?>>easeInCirc</option>
                      <option value="easeOutCirc" <?php if($dataSet['advps_easing'] == 'easeOutCirc'){echo 'selected="selected"';}?>>easeOutCirc</option>
                      <option value="easeInOutCirc" <?php if($dataSet['advps_easing'] == 'easeInOutCirc'){echo 'selected="selected"';}?>>easeInOutCirc</option>
                      <option value="easeInBounce" <?php if($dataSet['advps_easing'] == 'easeInBounce'){echo 'selected="selected"';}?>>easeInBounce</option>
                      <option value="easeOutBounce" <?php if($dataSet['advps_easing'] == 'easeOutBounce'){echo 'selected="selected"';}?>>easeOutBounce</option>
                      <option value="easeInOutBounce" <?php if($dataSet['advps_easing'] == 'easeInOutBounce'){echo 'selected="selected"';}?>>easeInOutBounce</option>
                      <option value="easeInElastic" <?php if($dataSet['advps_easing'] == 'easeInElastic'){echo 'selected="selected"';}?>>easeInElastic</option>
                      <option value="easeOutElastic" <?php if($dataSet['advps_easing'] == 'easeOutElastic'){echo 'selected="selected"';}?>>easeOutElastic</option>
                      <option value="easeInOutElastic" <?php if($dataSet['advps_easing'] == 'easeInOutElastic'){echo 'selected="selected"';}?>>easeInOutElastic</option>
                      <option value="easeInBack" <?php if($dataSet['advps_easing'] == 'easeInBack'){echo 'selected="selected"';}?>>easeInBack</option>
                      <option value="easeOutBack" <?php if($dataSet['advps_easing'] == 'easeOutBack'){echo 'selected="selected"';}?>>easeOutBack</option>
                      <option value="easeInOutBack" <?php if($dataSet['advps_easing'] == 'easeInOutBack'){echo 'selected="selected"';}?>>easeInOutBack</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Speed</th>
                  <td><input type="text" name="advps_speed" value="<?php echo $dataSet['advps_speed'];?>" style="width:60px;" onkeypress="return onlyNum(event);" /></td>
                </tr>
                <tr>
                  <th scope="row">Enable pause on hover</th>
                  <td><select name="advps_ps_hover">
                      <option value="yes" <?php if($dataSet['advps_ps_hover'] == 'yes'){echo 'selected="selected"';}?>>Yes</option>
                      <option value="no" <?php if($dataSet['advps_ps_hover'] == 'no'){echo 'selected="selected"';}?>>No</option>
                    </select></td>
                </tr>
              </table>
            </fieldset>
            <fieldset>
              <legend style="margin-left:10px;"><strong>Content</strong></legend>
              <table class="form-table">
                <tr>
                  <th scope="row">Show on slide</th>
                  <td><select name="advps_content_set[]" multiple="multiple">
                      <option value="thumb" <?php if(in_array('thumb',$dataSet['advps_content_set'])){echo 'selected="selected"';}?>>Thumbnail</option>
                      <option value="title" <?php if(in_array('title',$dataSet['advps_content_set'])){echo 'selected="selected"';}?>>Title</option>
                      <option value="excerpt" <?php if(in_array('excerpt',$dataSet['advps_content_set'])){echo 'selected="selected"';}?>>Excerpt</option>
                      <option value="content" <?php if(in_array('content',$dataSet['advps_content_set'])){echo 'selected="selected"';}?>>Content</option>
                    </select>
                    <span style="padding-left:10px; font-size:10px; font-style:italic; vertical-align:top">[ * You can select multiple ]</span></td>
                </tr>
                <tr>
                  <th scope="row">Select Thumbnail</th>
                  <td><select name="advps_thumbnail">
                      <option value="thumbnail" <?php if($dataSet['advps_thumbnail'] == 'thumbnail'){echo 'selected="selected"';}?>>thumbnail</option>
                      <option value="medium" <?php if($dataSet['advps_thumbnail'] == 'medium'){echo 'selected="selected"';}?>>medium</option>
                      <option value="large" <?php if($dataSet['advps_thumbnail'] == 'large'){echo 'selected="selected"';}?>>large</option>
                      <option value="full" <?php if($dataSet['advps_thumbnail'] == 'full'){echo 'selected="selected"';}?>>full</option>
                      <?php 
                        global $_wp_additional_image_sizes;
                        unset($_wp_additional_image_sizes['post-thumbnail']);
                        foreach($_wp_additional_image_sizes as $tkey => $tval){
                        ?>
                      <option value="<?php echo $tkey;?>" <?php if($dataSet['advps_thumbnail'] == $tkey){echo 'selected="selected"';}?>><?php echo $tkey;?></option>
                      <?php
                        }
                        ?>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Slide Container</th>
                  <td>Width&nbsp;
                    <input type="text" name="advps_sld_width" value="<?php echo $dataSet['advps_sld_width'];?>" style="width:80px;" onkeypress="return onlyNum(event);" />
                    px&nbsp;&nbsp;&nbsp;Height&nbsp;
                    <input type="text" name="advps_sld_height" value="<?php echo $dataSet['advps_sld_height'];?>" style="width:80px;" onkeypress="return onlyNum(event);" />
                    px</td>
                </tr>
                <tr>
                  <th scope="row">Padding</th>
                  <td><input type="text" name="advps_contpad1" value="<?php echo $dataSet['advps_contpad1'];?>" style="width:40px;" onkeypress="return onlyNum(event);" />
                    px &nbsp;&nbsp;
                    <input type="text" name="advps_contpad2" value="<?php echo $dataSet['advps_contpad2'];?>" style="width:40px;" onkeypress="return onlyNum(event);" />
                    px&nbsp;&nbsp;
                    <input type="text" name="advps_contpad3" value="<?php echo $dataSet['advps_contpad3'];?>" style="width:40px;" onkeypress="return onlyNum(event);" />
                    px&nbsp;&nbsp;
                    <input type="text" name="advps_contpad4" value="<?php echo $dataSet['advps_contpad4'];?>" style="width:40px;" onkeypress="return onlyNum(event);" />
                    px&nbsp;&nbsp; </td>
                </tr>
                <tr>
                  <th scope="row">Background Color</th>
                  <td><input id="advpscolor<?php echo ++$flg?>" class="advps-color-picker" type="text" name="advps_bgcolor" value="<?php echo $dataSet['advps_bgcolor'];?>" style="width:100px;" />
                    <div class="advpsfarb" style="padding-left:22%"></div></td>
                </tr>
                <tr>
                  <th scope="row">Border</th>
                  <td><span style="vertical-align:top">
                    <input type="text" name="advps_border_size" value="<?php echo $dataSet['advps_border_size'];?>" style="width:40px;" onkeypress="return onlyNum(event);" />
                    px &nbsp;&nbsp;
                    <select name="advps_border_type">
                      <option value="dashed" <?php if($dataSet['advps_border_type'] == 'dashed'){echo 'selected="selected"';}?>>dashed</option>
                      <option value="dotted" <?php if($dataSet['advps_border_type'] == 'dotted'){echo 'selected="selected"';}?>>dotted</option>
                      <option value="double" <?php if($dataSet['advps_border_type'] == 'double'){echo 'selected="selected"';}?>>double</option>
                      <option value="solid" <?php if($dataSet['advps_border_type'] == 'solid'){echo 'selected="selected"';}?>>solid</option>
                      <option value="inset" <?php if($dataSet['advps_border_type'] == 'inset'){echo 'selected="selected"';}?>>inset</option>
                      <option value="outset" <?php if($dataSet['advps_border_type'] == 'outset'){echo 'selected="selected"';}?>>outset</option>
                    </select>
                    &nbsp;&nbsp;</span>
                    <input id="advpscolor<?php echo ++$flg?>" class="advps-color-picker" type="text" name="advps_border_color" value="<?php echo $dataSet['advps_border_color'];?>" style="width:100px;" />
                    <div class="advpsfarb" style="padding-left:22%"></div></td>
                </tr>
                <tr>
                  <th scope="row">Remove Border</th>
                  <td><select name="advps_remove_border">
                      <option value="yes" <?php if($dataSet['advps_remove_border'] == 'yes'){echo 'selected="selected"';}?>>Yes</option>
                      <option value="no" <?php if($dataSet['advps_remove_border'] == 'no'){echo 'selected="selected"';}?>>No</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Box Shadow</th>
                  <td><span style="vertical-align:top">
                    <input type="text" name="advps_bxshad1" value="<?php echo $dataSet['advps_bxshad1'];?>" style="width:40px;" onkeypress="return onlyNum(event);" />
                    px &nbsp;&nbsp;
                    <input type="text" name="advps_bxshad2" value="<?php echo $dataSet['advps_bxshad2'];?>" style="width:40px;" onkeypress="return onlyNum(event);" />
                    px&nbsp;&nbsp;
                    <input type="text" name="advps_bxshad3" value="<?php echo $dataSet['advps_bxshad3'];?>" style="width:40px;" onkeypress="return onlyNum(event);" />
                    px&nbsp;&nbsp;</span>
                    <input id="advpscolor<?php echo ++$flg?>" class="advps-color-picker" type="text" name="advps_bxshadcolor" value="<?php echo $dataSet['advps_bxshadcolor'];?>" style="width:100px;" />
                    <div class="advpsfarb" style="padding-left:22%"></div></td>
                </tr>
                <tr>
                  <th scope="row">Remove Shadow</th>
                  <td><select name="advps_remove_shd">
                      <option value="yes" <?php if($dataSet['advps_remove_shd'] == 'yes'){echo 'selected="selected"';}?>>Yes</option>
                      <option value="no" <?php if($dataSet['advps_remove_shd'] == 'no'){echo 'selected="selected"';}?>>No</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Content width</th>
                  <td><input type="text" name="advps_cont_width" value="<?php echo $dataSet['advps_cont_width'];?>" style="width:60px;" onkeypress="return onlyNum(event);" />
                    px</td>
                </tr>
                <tr>
                  <th scope="row">Title tag</th>
                  <td><select name="advps_ttitle_tag">
                      <option value="h1" <?php if($dataSet['advps_ttitle_tag'] == 'h1'){echo 'selected="selected"';}?>>h1</option>
                      <option value="h2" <?php if($dataSet['advps_ttitle_tag'] == 'h2'){echo 'selected="selected"';}?>>h2</option>
                      <option value="h3" <?php if($dataSet['advps_ttitle_tag'] == 'h3'){echo 'selected="selected"';}?>>h3</option>
                      <option value="div" <?php if($dataSet['advps_ttitle_tag'] == 'div'){echo 'selected="selected"';}?>>div</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Title font Color</th>
                  <td><input id="advpscolor<?php echo ++$flg?>" type="text" name="advps_titleFcolor" value="<?php echo $dataSet['advps_titleFcolor'];?>" style="width:100px;" class="advps-color-picker" />
                    <div class="advpsfarb" style="padding-left:22%"></div></td>
                </tr>
                <tr>
                  <th scope="row">Title hover Color</th>
                  <td><input id="advpscolor<?php echo ++$flg?>" type="text" name="advps_titleHcolor" value="<?php echo $dataSet['advps_titleHcolor'];?>" style="width:100px;" class="advps-color-picker" />
                    <div class="advpsfarb" style="padding-left:22%"></div></td>
                </tr>
                <tr>
                  <th scope="row">Title font size</th>
                  <td><input type="text" name="advps_titleFsize" value="<?php echo $dataSet['advps_titleFsize'];?>" style="width:60px;" />
                    px</td>
                </tr>
                <tr>
                  <th scope="row">Excerpt/Content font color</th>
                  <td><input id="advpscolor<?php echo ++$flg?>" class="advps-color-picker" type="text" name="advps_excptFcolor" value="<?php echo $dataSet['advps_excptFcolor'];?>" style="width:100px;" />
                    <div class="advpsfarb" style="padding-left:22%"></div></td>
                </tr>
                <tr>
                  <th scope="row">Excerpt/Content font size</th>
                  <td><input type="text" name="advps_excptFsize" value="<?php echo $dataSet['advps_excptFsize'];?>" style="width:60px;" />
                    px</td>
                </tr>
                <tr>
                  <th scope="row">Excerpt length</th>
                  <td><input type="text" name="advps_excerptlen" value="<?php echo $dataSet['advps_excerptlen'];?>" style="width:60px;" onkeypress="return onlyNum(event);" />
                    &nbsp;words</td>
                </tr>
                <tr>
                  <th scope="row">Enable/Disable link</th>
                  <td><select name="advps_ed_link">
                      <option value="enable" <?php if($dataSet['advps_ed_link'] == 'enable'){echo 'selected="selected"';}?>>Enable</option>
                      <option value="disable" <?php if($dataSet['advps_ed_link'] == 'disable'){echo 'selected="selected"';}?>>Disable</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">link type</th>
                  <td><select name="advps_link_type">
                      <option value="permalink" <?php if($dataSet['advps_link_type'] == 'permalink'){echo 'selected="selected"';}?>>Permalink</option>
                      <option value="custom" <?php if($dataSet['advps_link_type'] == 'custom'){echo 'selected="selected"';}?>>Custom</option>
                    </select>
                    <span style="padding-left:10px; font-size:10px; font-style:italic;">[ N.B. For custom link create a custom field with name 'advps_custom_link' ]</span></td>
                </tr>
                <tr>
                  <th scope="row">link target</th>
                  <td><select name="advps_link_target">
                      <option value="_self" <?php if($dataSet['advps_link_target'] == '_self'){echo 'selected="selected"';}?>>_self</option>
                      <option value="_blank" <?php if($dataSet['advps_link_target'] == '_blank'){echo 'selected="selected"';}?>>_blank</option>
                      <option value="_new" <?php if($dataSet['advps_link_target'] == '_new'){echo 'selected="selected"';}?>>_new</option>
                      <option value="_top" <?php if($dataSet['advps_link_target'] == '_top'){echo 'selected="selected"';}?>>_top</option>
                      <option value="_parent" <?php if($dataSet['advps_link_target'] == '_parent'){echo 'selected="selected"';}?>>_parent</option>
                    </select></td>
                </tr>
              </table>
            </fieldset>
            <fieldset>
              <legend style="margin-left:10px;"><strong>Navigation</strong></legend>
              <table class="form-table">
                <tr>
                  <th scope="row">Exclude pager</th>
                  <td><select name="advps_exclude_pager">
                      <option value="yes" <?php if($dataSet['advps_exclude_pager'] == 'yes'){echo 'selected="selected"';}?>>Yes</option>
                      <option value="no" <?php if($dataSet['advps_exclude_pager'] == 'no'){echo 'selected="selected"';}?>>No</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Pager type</th>
                  <td><span style="padding-right:5px;">Number</span>
                    <input type="radio" name="advps_pager_type" value="number" <?php if($dataSet['advps_pager_type'] == 'number'){echo 'checked="checked"';}?>>
                    <span style="padding:0px 5px 0px 10px;">Bullet</span>
                    <input type="radio" name="advps_pager_type" value="bullet" <?php if($dataSet['advps_pager_type'] == 'bullet'){echo 'checked="checked"';}?>></td>
                </tr>
                <tr>
                  <th scope="row">Pager position</th>
                  <td>Right&nbsp;
                    <input type="text" name="advps_pager_right" value="<?php echo $dataSet['advps_pager_right'];?>" style="width:50px;" onkeypress="return onlyNum(event);" />
                    px&nbsp;&nbsp;&nbsp;Bottom&nbsp;
                    <input type="text" name="advps_pager_bottom" value="<?php echo $dataSet['advps_pager_bottom'];?>" style="width:50px;" onkeypress="return onlyNum(event);" />
                    px</td>
                </tr>
                <tr>
                  <th scope="row">Exclude Next/Previous</th>
                  <td><select name="advps_exclude_nxtprev">
                      <option value="yes" <?php if($dataSet['advps_exclude_nxtprev'] == 'yes'){echo 'selected="selected"';}?>>Yes</option>
                      <option value="no" <?php if($dataSet['advps_exclude_nxtprev'] == 'no'){echo 'selected="selected"';}?>>No</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Exclude Play/Pause</th>
                  <td><select name="advps_exclude_playpause">
                      <option value="yes" <?php if($dataSet['advps_exclude_playpause'] == 'yes'){echo 'selected="selected"';}?>>Yes</option>
                      <option value="no" <?php if($dataSet['advps_exclude_playpause'] == 'no'){echo 'selected="selected"';}?>>No</option>
                    </select></td>
                </tr>
              </table>
            </fieldset>
            <input type="hidden" value="<?php echo $dset->id;?>" name="opt_id" />
            <?php wp_nonce_field('advps-checkauthnonce','advps_wpnonce'); ?>
            <p>
              <input type="submit" name="advps_submit" value="Save changes" class="button-primary" />
            </p>
          </form>
        </div>
      </div>
    </div>
  </div>
  <?php }?>
  <?php if(isset($_POST['advps_add'])){?>
  <div class="metabox-holder" style="margin-top:20px;">
    <div class="postbox-container" style="width:65%">
      <div class="postbox">
        <div class="handlediv" title=""> <br>
        </div>
        <h3 style="cursor:pointer; text-align:center" class="advps-expand"> <span>Add new option set</span> </h3>
        <div class="inside">
          <form method="post">
            <fieldset style="border:1px solid #D9D9D9; border-radius:2px; margin-bottom:10px;">
              <legend style="margin-left:10px;"><strong>Query</strong></legend>
              <table class="form-table">
                <tr>
                  <th scope="row">Post Type</th>
                  <td><select name="advps_post_types" onchange="advpsCheckCat(this.value,'add')">
                      <option value="post" selected="selected">post</option>
                      <option value="page">page</option>
                      <?php
                              foreach ($customPostTypes  as $post_type ) {
                          ?>
                      <option value="<?php echo $post_type;?>"><?php echo $post_type;?></option>
                      <?php		
                              }
                          ?>
                    </select></td>
                </tr>
                <tr id="advps-cat-fieldadd">
                  <th scope="row">Category</th>
                  <td><select name="advps_category[]" multiple="multiple">
                      <?php 
					  	$catList = get_categories();
						foreach($catList as $scat){
					  ?>
                      <option value="<?php echo $scat->term_id;?>"><?php echo $scat->name;?></option>
                      <?php }?>
                    </select>
                    <span style="padding-left:10px; font-size:10px; font-style:italic;">[ * You can select multiple category ]</span></td>
                </tr>
                <tr>
                  <th scope="row">Max. Number of post</th>
                  <td><input type="text" name="advps_maxpost" value="10" style="width:60px;" onkeypress="return onlyNum(event);" /></td>
                </tr>
                <tr>
                  <th scope="row">Exclude</th>
                  <td><input type="text" name="advps_exclude" value="" style="width:100px;" />
                    <span style="padding-left:10px; font-size:10px; font-style:italic;">[ Ex. 1,5,10 Comma separated post IDs that need to exclude from slideshow ]</span></td>
                </tr>
                <tr>
                  <th scope="row">Order by</th>
                  <td><select name="advps_order_by">
                      <option value="date" selected="selected">Date</option>
                      <option value="ID">ID</option>
                      <option value="author">Author</option>
                      <option value="title">Title</option>
                      <option value="name">Name</option>
                      <option value="rand">Random</option>
                      <option value="menu_order">Menu order</option>
                      <option value="comment_count">Comment count</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Order</th>
                  <td><select name="advps_order">
                      <option value="ASC">Ascending</option>
                      <option value="DESC" selected="selected">Descending</option>
                    </select></td>
                </tr>
              </table>
            </fieldset>
            <fieldset style="border:1px solid #D9D9D9; border-radius:2px;margin-bottom:10px;">
              <legend style="margin-left:10px;"><strong>Effects</strong></legend>
              <table class="form-table">
                <tr>
                  <th scope="row">Effect</th>
                  <td><select name="advps_effects">
                      <option value="blindX">blindX</option>
                      <option value="blindY">blindY</option>
                      <option value="blindZ">blindZ</option>
                      <option value="cover">cover</option>
                      <option value="curtainX">curtainX</option>
                      <option value="curtainY">curtainY</option>
                      <option value="fade">fade</option>
                      <option value="fadeZoom">fadeZoom</option>
                      <option value="growX">growX</option>
                      <option value="growY">growY</option>
                      <option value="none">none</option>
                      <option value="scrollUp">scrollUp</option>
                      <option value="scrollDown">scrollDown</option>
                      <option value="scrollLeft">scrollLeft</option>
                      <option value="scrollRight">scrollRight</option>
                      <option value="scrollHorz">scrollHorz</option>
                      <option value="scrollVert">scrollVert</option>
                      <option value="shuffle">shuffle</option>
                      <option value="slideX">slideX</option>
                      <option value="slideY">slideY</option>
                      <option value="toss">toss</option>
                      <option value="turnUp">turnUp</option>
                      <option value="turnDown">turnDown</option>
                      <option value="turnLeft">turnLeft</option>
                      <option value="turnRight">turnRight</option>
                      <option value="uncover" selected="selected">uncover</option>
                      <option value="wipe">wipe</option>
                      <option value="zoom">zoom</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Timeout</th>
                  <td><input type="text" name="advps_timeout" value="2000" style="width:60px;" onkeypress="return onlyNum(event);" />
                    <span style="padding-left:10px; font-size:10px; font-style:italic;"> [ 0 to disable auto advance.]</span></td>
                </tr>
                <tr>
                  <th scope="row">Easing</th>
                  <td><select name="advps_easing">
                      <option value="linear" selected="selected">linear</option>
                      <option value="swing">swing</option>
                      <option value="jswing">jswing</option>
                      <option value="easeInQuad">easeInQuad</option>
                      <option value="easeOutQuad">easeOutQuad</option>
                      <option value="easeInOutQuad">easeInOutQuad</option>
                      <option value="easeInCubic">easeInCubic</option>
                      <option value="easeOutCubic">easeOutCubic</option>
                      <option value="easeInOutCubic">easeInOutCubic</option>
                      <option value="easeInQuart">easeInQuart</option>
                      <option value="easeOutQuart">easeOutQuart</option>
                      <option value="easeInOutQuart">easeInOutQuart</option>
                      <option value="easeInQuint">easeInQuint</option>
                      <option value="easeInQuint">easeOutQuint</option>
                      <option value="easeInQuint">easeInOutQuint</option>
                      <option value="easeInSine">easeInSine</option>
                      <option value="easeOutSine">easeOutSine</option>
                      <option value="easeInOutSine">easeInOutSine</option>
                      <option value="easeInExpo">easeInExpo</option>
                      <option value="easeOutExpo">easeOutExpo</option>
                      <option value="easeInOutExpo">easeInOutExpo</option>
                      <option value="easeInCirc">easeInCirc</option>
                      <option value="easeOutCirc">easeOutCirc</option>
                      <option value="easeInOutCirc">easeInOutCirc</option>
                      <option value="easeInBounce">easeInBounce</option>
                      <option value="easeOutBounce">easeOutBounce</option>
                      <option value="easeInOutBounce">easeInOutBounce</option>
                      <option value="easeInElastic">easeInElastic</option>
                      <option value="easeOutElastic">easeOutElastic</option>
                      <option value="easeInOutElastic">easeInOutElastic</option>
                      <option value="easeInBack">easeInBack</option>
                      <option value="easeOutBack">easeOutBack</option>
                      <option value="easeInOutBack">easeInOutBack</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Speed</th>
                  <td><input type="text" name="advps_speed" value="1500" style="width:60px;" onkeypress="return onlyNum(event);" /></td>
                </tr>
                <tr>
                  <th scope="row">Enable pause on hover</th>
                  <td><select name="advps_ps_hover">
                      <option value="yes" selected="selected">Yes</option>
                      <option value="no">No</option>
                    </select></td>
                </tr>
              </table>
            </fieldset>
            <fieldset style="border:1px solid #D9D9D9; border-radius:2px;margin-bottom:10px;">
              <legend style="margin-left:10px;"><strong>Content</strong></legend>
              <table class="form-table">
                <tr>
                  <th scope="row">Show on slide</th>
                  <td><select name="advps_content_set[]" multiple="multiple">
                      <option value="thumb" selected="selected">Thumbnail</option>
                      <option value="title" selected="selected">Title</option>
                      <option value="excerpt" selected="selected">Excerpt</option>
                      <option value="content">Content</option>
                    </select>
                    <span style="padding-left:10px; font-size:10px; font-style:italic; vertical-align:top">[ * You can select multiple ]</span></td>
                </tr>
                <tr>
                  <th scope="row">Select Thumbnail</th>
                  <td><select name="advps_thumbnail">
                      <option value="thumbnail">thumbnail</option>
                      <option value="medium"selected="selected">medium</option>
                      <option value="large">large</option>
                      <option value="full">full</option>
                      <?php 
                        global $_wp_additional_image_sizes;
                        unset($_wp_additional_image_sizes['post-thumbnail']);
                        foreach($_wp_additional_image_sizes as $tkey => $tval){
                        ?>
                      <option value="<?php echo $tkey;?>"><?php echo $tkey;?></option>
                      <?php
                        }
                        ?>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Slide Container</th>
                  <td>Width&nbsp;
                    <input type="text" name="advps_sld_width" value="624" style="width:80px;" onkeypress="return onlyNum(event);" />
                    px&nbsp;&nbsp;&nbsp;Height&nbsp;
                    <input type="text" name="advps_sld_height" value="225" style="width:80px;" onkeypress="return onlyNum(event);" />
                    px</td>
                </tr>
                <tr>
                  <th scope="row">Padding</th>
                  <td><input type="text" name="advps_contpad1" value="10" style="width:40px;" onkeypress="return onlyNum(event);" />
                    px &nbsp;&nbsp;
                    <input type="text" name="advps_contpad2" value="10" style="width:40px;" onkeypress="return onlyNum(event);" />
                    px&nbsp;&nbsp;
                    <input type="text" name="advps_contpad3" value="10" style="width:40px;" onkeypress="return onlyNum(event);" />
                    px&nbsp;&nbsp;
                    <input type="text" name="advps_contpad4" value="10" style="width:40px;" onkeypress="return onlyNum(event);" />
                    px&nbsp;&nbsp; </td>
                </tr>
                <tr>
                  <th scope="row">Background Color</th>
                  <td><input id="advpscolor<?php echo ++$flg?>" class="advps-color-picker" type="text" name="advps_bgcolor" value="#FFFFFF" style="width:100px;" />
                    <div class="advpsfarb" style="padding-left:22%"></div></td>
                </tr>
                <tr>
                  <th scope="row">Border</th>
                  <td><span style="vertical-align:top">
                    <input type="text" name="advps_border_size" value="1" style="width:40px;" onkeypress="return onlyNum(event);" />
                    px &nbsp;&nbsp;
                    <select name="advps_border_type">
                      <option value="dashed">dashed</option>
                      <option value="dotted">dotted</option>
                      <option value="double">double</option>
                      <option value="solid"selected="selected">solid</option>
                      <option value="inset">inset</option>
                      <option value="outset">outset</option>
                    </select>
                    &nbsp;&nbsp;</span>
                    <input id="advpscolor<?php echo ++$flg?>" class="advps-color-picker" type="text" name="advps_border_color" value="#444444" style="width:100px;" />
                    <div class="advpsfarb" style="padding-left:22%"></div></td>
                </tr>
                <tr>
                  <th scope="row">Remove Border</th>
                  <td><select name="advps_remove_border">
                      <option value="yes" selected="selected">Yes</option>
                      <option value="no">No</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Box Shadow</th>
                  <td><span style="vertical-align:top">
                    <input type="text" name="advps_bxshad1" value="0" style="width:40px;" onkeypress="return onlyNum(event);" />
                    px &nbsp;&nbsp;
                    <input type="text" name="advps_bxshad2" value="1" style="width:40px;" onkeypress="return onlyNum(event);" />
                    px&nbsp;&nbsp;
                    <input type="text" name="advps_bxshad3" value="4" style="width:40px;" onkeypress="return onlyNum(event);" />
                    px&nbsp;&nbsp;</span>
                    <input id="advpscolor<?php echo ++$flg?>" class="advps-color-picker" type="text" name="advps_bxshadcolor" value="#000000" style="width:100px;" />
                    <div class="advpsfarb" style="padding-left:22%"></div></td>
                </tr>
                <tr>
                  <th scope="row">Remove Shadow</th>
                  <td><select name="advps_remove_shd">
                      <option value="yes">Yes</option>
                      <option value="no" selected="selected">No</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Content width</th>
                  <td><input type="text" name="advps_cont_width" value="280" style="width:60px;" onkeypress="return onlyNum(event);" />
                    px</td>
                </tr>
                <tr>
                  <th scope="row">Title tag</th>
                  <td><select name="advps_ttitle_tag">
                      <option value="h1">h1</option>
                      <option value="h2" selected="selected">h2</option>
                      <option value="h3">h3</option>
                      <option value="div">div</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Title font Color</th>
                  <td><input id="advpscolor<?php echo ++$flg?>" type="text" name="advps_titleFcolor" value="#000000" style="width:100px;" class="advps-color-picker" />
                    <div class="advpsfarb" style="padding-left:22%"></div></td>
                </tr>
                <tr>
                  <th scope="row">Title hover Color</th>
                  <td><input id="advpscolor<?php echo ++$flg?>" type="text" name="advps_titleHcolor" value="#E6E6E6" style="width:100px;" class="advps-color-picker" />
                    <div class="advpsfarb" style="padding-left:22%"></div></td>
                </tr>
                <tr>
                  <th scope="row">Title font size</th>
                  <td><input type="text" name="advps_titleFsize" value="22" style="width:60px;" />
                    px</td>
                </tr>
                <tr>
                  <th scope="row">Excerpt/Content font color</th>
                  <td><input id="advpscolor<?php echo ++$flg?>" class="advps-color-picker" type="text" name="advps_excptFcolor" value="#333333" style="width:100px;" />
                    <div class="advpsfarb" style="padding-left:22%"></div></td>
                </tr>
                <tr>
                  <th scope="row">Excerpt/Content font size</th>
                  <td><input type="text" name="advps_excptFsize" value="12" style="width:60px;" />
                    px</td>
                </tr>
                <tr>
                  <th scope="row">Excerpt length</th>
                  <td><input type="text" name="advps_excerptlen" value="30" style="width:60px;" onkeypress="return onlyNum(event);" />
                    &nbsp;words</td>
                </tr>
                <tr>
                  <th scope="row">Enable/Disable link</th>
                  <td><select name="advps_ed_link">
                      <option value="enable" selected="selected">Enable</option>
                      <option value="disable">Disable</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">link type</th>
                  <td><select name="advps_link_type">
                      <option value="permalink" selected="selected">Permalink</option>
                      <option value="custom">Custom</option>
                    </select>
                    <span style="padding-left:10px; font-size:10px; font-style:italic;">[ N.B. For custom link create a custom field with name 'advps_custom_link' ]</span></td>
                </tr>
                <tr>
                  <th scope="row">link target</th>
                  <td><select name="advps_link_target">
                      <option value="_self" selected="selected">_self</option>
                      <option value="_blank">_blank</option>
                      <option value="_new">_new</option>
                      <option value="_top">_top</option>
                      <option value="_parent">_parent</option>
                    </select></td>
                </tr>
              </table>
            </fieldset>
            <fieldset style="border:1px solid #D9D9D9; border-radius:2px;margin-bottom:10px;">
              <legend style="margin-left:10px;"><strong>Navigation</strong></legend>
              <table class="form-table">
                <tr>
                  <th scope="row">Exclude pager</th>
                  <td><select name="advps_exclude_pager">
                      <option value="yes">Yes</option>
                      <option value="no" selected="selected">No</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Pager type</th>
                  <td><span style="padding-right:5px;">Number</span>
                    <input type="radio" name="advps_pager_type" value="number">
                    <span style="padding:0px 5px 0px 10px;">Bullet</span>
                    <input type="radio" name="advps_pager_type" value="bullet" checked="checked"></td>
                </tr>
                <tr>
                  <th scope="row">Pager position</th>
                  <td>Right&nbsp;
                    <input type="text" name="advps_pager_right" value="8" style="width:50px;" onkeypress="return onlyNum(event);" />
                    px&nbsp;&nbsp;&nbsp;Bottom&nbsp;
                    <input type="text" name="advps_pager_bottom" value="6" style="width:50px;" onkeypress="return onlyNum(event);" />
                    px</td>
                </tr>
                <tr>
                  <th scope="row">Exclude Next/Previous</th>
                  <td><select name="advps_exclude_nxtprev">
                      <option value="yes">Yes</option>
                      <option value="no" selected="selected">No</option>
                    </select></td>
                </tr>
                <tr>
                  <th scope="row">Exclude Play/Pause</th>
                  <td><select name="advps_exclude_playpause">
                      <option value="yes">Yes</option>
                      <option value="no" selected="selected">No</option>
                    </select></td>
                </tr>
              </table>
            </fieldset>
            <input type="hidden" name="template" value="three" />
            <?php wp_nonce_field('advps-checkauthnonce','advps_wpnonce'); ?>
            <p>
              <input type="submit" name="advps_submit" value="Add" class="button-primary" style="width:90px; font-weight:bold" />
              <span style="margin-left:10px;">
              <button class="button-secondary" style="width:90px; height:25px;">Cancel</button>
              </span></p>
          </form>
        </div>
      </div>
    </div>
  </div>
  <?php }else{?>
  <div style="position:relative; float:left; width:100%">
    <form method="post">
      <input type="submit" class="button-secondary" value="Add New" name="advps_add" />
    </form>
  </div>
  <?php }}elseif($currTab == 'thumb'){?>
  <div class="metabox-holder" style="margin-top:20px;">
    <div class="postbox-container" style="width:65%">
      <div class="postbox" style="margin-bottom:15px;">
        <h3><strong>Thumbnail settings</strong></h3>
        <table class="form-table">
          <?php foreach( $res_thumb as $thmb){?>
          <tr>
            <form method="post">
              <th scope="row">Name&nbsp;
                <input type="text" name="advps_thumb_name" value="<?php echo $thmb->thumb_name;?>" style="width:140px" /></th>
              <td>Width&nbsp;
                <input type="text" name="advps_thumb_width" value="<?php echo $thmb->width;?>" style="width:80px;" onkeypress="return onlyNum(event);" />
                px&nbsp;&nbsp;&nbsp;Height&nbsp;
                <input type="text" name="advps_thumb_height" value="<?php echo $thmb->height;?>" style="width:80px;" onkeypress="return onlyNum(event);" />
                px <span style="margin-left:20px;">Crop&nbsp;
                <select name="advps_crop">
                  <option value="yes" <?php if($thmb->crop == 'yes'){echo 'selected="selected"';}?>>true</option>
                  <option value="no" <?php if($thmb->crop == 'no'){echo 'selected="selected"';}?>>false</option>
                </select>
                </span> <span style="margin-left:20px;">
                <input type="submit" value="Save" class="button-secondary" name="update_thumb" />
                </span></td>
              <input type="hidden" value="<?php echo $thmb->id;?>" name="thumb_id" />
              <?php wp_nonce_field('advps-checkauthnonce','advps_wpnonce'); ?>
            </form>
          </tr>
          <?php }?>
        </table>
      </div>
      <?php if(!isset($_POST['advps_add_thumb']) || $_POST['advps_add_thumb'] != 'Add New'){?>
      <div style="position:relative; float:left; width:100%;">
        <form method="post">
          <input type="submit" class="button-secondary" value="Add New" name="advps_add_thumb" style="width:80px;" />
        </form>
      </div>
      <?php }else{?>
      <div class="postbox">
        <h3><strong>Add thumbnail</strong></h3>
        <form method="post">
          <table class="form-table">
            <tr>
              <th scope="row">Name&nbsp;
                <input type="text" name="advps_thumb_name" value="" style="width:140px" /></th>
              <td>Width&nbsp;
                <input type="text" name="advps_thumb_width" value="" style="width:80px;" onkeypress="return onlyNum(event);" />
                px&nbsp;&nbsp;&nbsp;Height&nbsp;
                <input type="text" name="advps_thumb_height" value="" style="width:80px;" onkeypress="return onlyNum(event);" />
                px <span style="margin-left:20px;">Crop&nbsp;
                <select name="advps_crop">
                  <option value="yes">true</option>
                  <option value="no" selected="selected">false</option>
                </select>
                </span> <span style="margin-left:20px;">
                <?php wp_nonce_field('advps-checkauthnonce','advps_wpnonce'); ?>
                <input type="submit" value="Add" class="button-secondary" name="advps_add_thumb" />
                </span></td>
            </tr>
          </table>
        </form>
      </div>
      <?php }?>
    </div>
  </div>
  <?php }?>
</div>
<meta name="wpversion" content="<?php echo $wp_version;?>" />
