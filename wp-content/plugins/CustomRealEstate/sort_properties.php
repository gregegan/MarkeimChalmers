<?php

add_action( 'admin_menu', 'my_plugin_admin_menu' );

function my_plugin_admin_menu() {
    $suffix = add_submenu_page( 
	    'edit.php?post_type=properties', 
	    'Sort Properties',
	    'Sort Properties', 
	    'manage_options', 
	    'my_plugin-options', 
	    'my_plugin_manage_menu' 
	);
	add_action( "admin_print_scripts-$suffix", 'my_plugin_admin_scripts');
}

function my_plugin_admin_scripts() {
    wp_enqueue_script('jquery');
    wp_enqueue_script("jquery-ui-sortable");
    wp_enqueue_script("jquery-ui-draggable");
    wp_enqueue_script("jquery-ui-droppable"); 
    wp_enqueue_style('plugin_name-admin-ui-css','http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/themes/smoothness/jquery-ui.css',false,PLUGIN_VERSION,false);
}

function my_plugin_manage_menu() {
	$sortedList = $_POST['sortedList'];
	$updateList = $_POST['updateList'];
	
	if($updateList != null && $updateList == 'true') {
		echo '<div id="sortedList">' . $sortedList . '</div>';
		$pieces = explode(",", $sortedList);
		foreach($pieces as $key => $property_id) {
			delete_post_meta($property_id, 'property_sort');
			update_post_meta($property_id, 'property_sort', $key);
		}
	} else {
		$query = new WP_Query( array ( 
			'post_type' => 'properties', 
			'orderby' => 'meta_value_num', 
			'meta_key' => 'property_sort', 
			'posts_per_page' => '-1',
			'post_status'=>'publish',
			'order' => 'ASC'
		));
		add_action( 'admin_footer', 'my_action_javascript' );
?>
<style>
	#sortProperties { margin: 10px 0 0 30px; padding: 0; width: 60%; }
	#sortProperties li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.4em; height: 18px; cursor:pointer; }
	#sortProperties li span { position: absolute; margin-left: -1.3em; }
	#sortProperties li.highlights { background:yellow; }
	#loading { display:none;border: 1px solid gray; padding: 10px; margin-top: 10px; color: red; width: 130px; }
</style>
<div id="loading">Successfully Updated</div>
<ol id='sortProperties'>
<?php
while ( $query->have_posts() ) {
	$query->next_post();
	$property_sort = get_post_meta($query->post->ID, 'property_sort');
	echo '<li id="' . $query->post->ID . '" class="ui-state-default">' . get_the_title( $query->post->ID ) . '</li>';
}
?>
</ol>
<?php
	}
}

function my_action_javascript() { ?>
<script type="text/javascript">
jQuery(document).ready( function($) {
    $('ol#sortProperties').sortable({
    	itemSelector: 'li',
        update: function(event, ui) {
            $sortedList = $(this).sortable('toArray').toString();
            $.ajax({
	            type: "POST",
	            data: {"sortedList":$sortedList, "updateList":true},
	            cache: false,
	            dataType: "json",
	            success: function () {
	            	console.log("success");
	            	jQuery("#loading").show();
	            	setTimeout(function(){ jQuery("#loading").fadeOut(); }, 10000);
	            }
	        });
        }
    });
});
</script>
<?php 
}

?>