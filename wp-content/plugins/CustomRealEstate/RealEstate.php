<?php
/*
Plugin Name: Custom Real Estate built exclusively for Markeim Development
Plugin URI: http://hybridhosting.net
Description: Custom Real Estate Plugin
Version: 1.0
Author: First Dynamic / Greg Egan gregegan8@gmail.com
Author URI: http://hybridhosting.net.com/
License: GPLv2
*/

define('MY_PLUGIN_PATH', plugin_dir_path(__FILE__)); 
require_once MY_PLUGIN_PATH . 'brokers.php';
require_once MY_PLUGIN_PATH . 'properties.php';
require_once MY_PLUGIN_PATH . 'units.php';
require_once MY_PLUGIN_PATH . 'widgets.php';
require_once MY_PLUGIN_PATH . 'sort_properties.php';

/* CUSTOM POST TYPES */
add_action('init', 'create_real_estate_property');
add_action('init', 'create_real_estate_broker');

add_action( 'admin_init', 'brokers_properties_admin' );
add_action( 'admin_init', 'brokers_admin' );
add_action( 'admin_init', 'properties_admin' );
add_action( 'admin_init', 'slider_admin' );
add_action( 'admin_init', 'units_admin' );




function create_real_estate_broker() {
	register_post_type( 'brokers',
		array(
            'labels' => array(
                'name' => 'Brokers',
                'singular_name' => 'Broker',
                'add_new' => 'Add New',
                'add_new_item' => 'Add New Broker',
                'edit' => 'Edit',
                'edit_item' => 'Edit Broker',
                'new_item' => 'New Broker',
                'view' => 'View',
                'view_item' => 'View Broker',
                'search_items' => 'Search Broker',
                'not_found' => 'No Brokers found',
                'not_found_in_trash' => 'No Brokers found in Trash',
                'parent' => 'Parent Broker'
            ),
 
            'public' => true,
            'menu_position' => 15,
            'supports' => array( 'title', 'editor', 'comments', 'thumbnail', 'custom-fields' ),
            'taxonomies' => array( '' ),
            'has_archive' => true
        )
	);
}

function create_real_estate_property() {
	
	//add_rewrite_tag('%country%', '([^&/]+)'));
	
    register_post_type( 'properties',
        array(
            'labels' => array(
                'name' => 'Properties',
                'singular_name' => 'Property',
                'add_new' => 'Add New',
                'add_new_item' => 'Add New Property',
                'edit' => 'Edit',
                'edit_item' => 'Edit Property',
                'new_item' => 'New Property',
                'view' => 'View',
                'view_item' => 'View Property',
                'search_items' => 'Search Properties',
                'not_found' => 'No Property found',
                'not_found_in_trash' => 'No Properties found in Trash',
                'parent' => 'Parent Property'
            ),
 
            'public' => true,
            'rewrite' => array('slug'=>'listings'),
            'menu_position' => 15,
            'supports' => array( 'title', 'editor', 'comments', 'thumbnail', 'custom-fields' ),
            'taxonomies' => array( 'category' ),
            'has_archive' => true
        )
    );
}










/*
 * PROPERTIES FILE UPLOADER
 */

 
 
function slider_admin() {
    add_meta_box( 'slider_meta_box',
        'Slider Images',
        'slider_meta_box',
        'properties', 'normal', 'high'
    );
}

function slider_meta_box() {
    global $post;
	wp_nonce_field( plugin_basename( __FILE__ ), 'slider_metabox_nonce' );
	
    $file_upload1 = esc_html( get_post_meta( $post->ID, 'file_upload1', true ) );
    $file_upload2 = esc_html( get_post_meta( $post->ID, 'file_upload2', true ) );
    $gallery_image1 = esc_html( get_post_meta( $post->ID, 'gallery_image1', true ) );
    $gallery_image2 = esc_html( get_post_meta( $post->ID, 'gallery_image2', true ) );
    $gallery_image3 = esc_html( get_post_meta( $post->ID, 'gallery_image3', true ) );
    $gallery_image4 = esc_html( get_post_meta( $post->ID, 'gallery_image4', true ) );
    $gallery_image5 = esc_html( get_post_meta( $post->ID, 'gallery_image5', true ) );
?>
<input type="hidden" name="slider_metabox_nonce" value="<?php wp_create_nonce(basename(__FILE__)) ?>" />
<table class="form-table">
<tbody>
    <tr>
    	<th><label for="file_upload1">Fact Sheet</label></th>
    	<td>
		    <input id="file_upload1" class="upload_image_field" type="text" size="70" name="file_upload1" value="<?php echo $file_upload1; ?>" /> 
		    <input class="button upload_image_button" type="button" value="Upload PDF" />
		    <br />Enter a URL or upload an PDF
    	</td>
    </tr>
    <tr>
    	<th><label for="file_upload2">Additional Attachment</label></th>
    	<td>
		    <input id="file_upload2" class="upload_image_field" type="text" size="70" name="file_upload2" value="<?php echo $file_upload2; ?>" /> 
		    <input class="button upload_image_button" type="button" value="Upload PDF" />
		    <br />Enter a URL or upload an PDF
    	</td>
    </tr>
    <tr>
    	<th><label for="gallery_image1">Image 1</label></th>
    	<td>
		    <input id="gallery_image1" class="upload_image_field" type="text" size="70" name="gallery_image1" value="<?php echo $gallery_image1; ?>" /> 
		    <input class="button upload_image_button" type="button" value="Upload Image" />
		    <br />Enter a URL or upload an image
    	</td>
    </tr>
	<tr>
    	<th><label for="gallery_image2">Image 2</label></th>
    	<td>
		    <input id="gallery_image2" class="upload_image_field" type="text" size="70" name="gallery_image2" value="<?php echo $gallery_image2; ?>" /> 
		    <input class="button upload_image_button" type="button" value="Upload Image" />
		    <br />Enter a URL or upload an image
    	</td>
    </tr>
    <tr>
    	<th><label for="gallery_image3">Image 3</label></th>
    	<td>
		    <input id="gallery_image3" class="upload_image_field" type="text" size="70" name="gallery_image3" value="<?php echo $gallery_image3; ?>" /> 
		    <input class="button upload_image_button" type="button" value="Upload Image" />
		    <br />Enter a URL or upload an image
    	</td>
    </tr>
    <tr>
    	<th><label for="gallery_image4">Image 4</label></th>
    	<td>
		    <input id="gallery_image4" class="upload_image_field" type="text" size="70" name="gallery_image4" value="<?php echo $gallery_image4; ?>" /> 
		    <input class="button upload_image_button" type="button" value="Upload Image" />
		    <br />Enter a URL or upload an image
    	</td>
    </tr>
    <tr>
    	<th><label for="gallery_image5">Image 5</label></th>
    	<td>
		    <input id="gallery_image5" class="upload_image_field" type="text" size="70" name="gallery_image5" value="<?php echo $gallery_image5; ?>" /> 
		    <input class="button upload_image_button" type="button" value="Upload Image" />
		    <br />Enter a URL or upload an image
    	</td>
    </tr>
</tbody>
</table>
<?php
}


add_action('save_post', 'slider_metabox_save');
function slider_metabox_save($post_id) {
  // verify nonce
 // if ( !wp_verify_nonce( $_POST['slider_metabox_nonce'], plugin_basename( __FILE__ ) ) )
   //     return;

  // check autosave
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;
  // check permissions
  if (!current_user_can('edit_post', $post_id))
    return;
	
	if ( isset( $_POST['file_upload1'] ) && $_POST['file_upload1'] != '' ) {
	    update_post_meta( $post_id, 'file_upload1', $_POST['file_upload1'] );
	} else {
		update_post_meta( $post_id, 'file_upload1', '' );
	}
	
	if ( isset( $_POST['file_upload2'] ) && $_POST['file_upload2'] != '' ) {
	    update_post_meta( $post_id, 'file_upload2', $_POST['file_upload2'] );
	} else {
		update_post_meta( $post_id, 'file_upload2', '' );
	}
	
	if ( isset( $_POST['gallery_image1'] ) && $_POST['gallery_image1'] != '' ) {
	    update_post_meta( $post_id, 'gallery_image1', $_POST['gallery_image1'] );
	} else {
		update_post_meta( $post_id, 'gallery_image1', '' );
	}
	
	if ( isset( $_POST['gallery_image2'] ) && $_POST['gallery_image2'] != '' ) {
	    update_post_meta( $post_id, 'gallery_image2', $_POST['gallery_image2'] );
	} else {
		update_post_meta( $post_id, 'gallery_image2', '' );
	}
	
	if ( isset( $_POST['gallery_image3'] ) && $_POST['gallery_image3'] != '' ) {
	    update_post_meta( $post_id, 'gallery_image3', $_POST['gallery_image3'] );
	} else {
		update_post_meta( $post_id, 'gallery_image3', '' );
	}
	
	if ( isset( $_POST['gallery_image4'] ) && $_POST['gallery_image4'] != '' ) {
	    update_post_meta( $post_id, 'gallery_image4', $_POST['gallery_image4'] );
	} else {
		update_post_meta( $post_id, 'gallery_image4', '' );
	}
	
	if ( isset( $_POST['gallery_image5'] ) && $_POST['gallery_image5'] != '' ) {
	    update_post_meta( $post_id, 'gallery_image5', $_POST['gallery_image5'] );
	} else {
		update_post_meta( $post_id, 'gallery_image5', '' );
	}
}

?>