<?php
/*
Plugin Name: Vocab Trainer
Plugin URI:  http://
Description: A plugin that allows you to practice your Vocabulary
Version:     1.5
Author:      Hanna Seithe
Author URI:  http://
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: 
 * 
 * {Plugin Name} is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
{Plugin Name} is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with {Plugin Name}. If not, see {License URI}.
 */
 
 
 
 //admin page
 
 function vt_page() {
 	?>
 	
 	<h1>Edit Words / New Words</h1>
 	<?php
 	
 	//UPDATE POST
 	if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) &&  $_POST['action'] == "update_post" && isset($_POST['pid'])) {
		?>
		<div class="updated notice notice-success is-dismissible below-h2" id="message">
			<p>Update posted 
				<button class="notice-dismiss" type="button">
					<span class="screen-reader-text">Diese Meldung verwerfen.</span>
				</button>
			</div>
		<?php	
    
        $pid =				$_POST['pid'];
		$original =			$_POST['original'];
		$transliteration =	$_POST['transliteration'];
		$translation =		$_POST['translation'];
        $content = 			$_POST['content'];
		$proficiency = 		$_POST['proficiency'];
		

		echo $id;
	    // Add the content of the form to $post as an array
	    $update_post = array(
	    	'ID'			=> $pid,
	        'post_title'    => $original . " (" . $transliteration . ") - " . $translation,
	        'post_content'  => $content 
	    );
		
	    //save the new post and return its ID
	    wp_update_post($update_post); 
		
		//update the Custom Fields
	    update_post_meta( $pid, 'original', $original );
		update_post_meta( $pid, 'transliteration', $transliteration );
		update_post_meta( $pid, 'translation', $translation );
		update_post_meta( $pid, 'proficiency', $proficiency );
	
	
	
	}
	elseif ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) &&  $_POST['action'] == "new_post" ) {
		?>
		<div class="updated notice notice-success is-dismissible below-h2" id="message">
			<p>New word posted 
				<button class="notice-dismiss" type="button">
					<span class="screen-reader-text">Diese Meldung verwerfen.</span>
				</button>
			</div>
		<?php	
    
        
		$original =			$_POST['original'];
		$transliteration =	$_POST['transliteration'];
		$translation =		$_POST['translation'];
        $content = 			$_POST['content'];
		$proficiency = 		$_POST['proficiency'];

		
	    // Add the content of the form to $post as an array
	    $new_post = array(

	        'post_title'    => $original . " (" . $transliteration . ") - " . $translation,
	        'post_content'  => $content,
	        'post_status'   => 'publish',           
        	'post_type' 	=> 'vt_words'
	    );
		
		
		
	    //save the new post and return its ID
	    $pid = wp_insert_post($new_post); 
		
		//update the Custom Fields
	    update_post_meta( $pid, 'original', $original );
		update_post_meta( $pid, 'transliteration', $transliteration );
		update_post_meta( $pid, 'translation', $translation );
		update_post_meta( $pid, 'proficiency', $proficiency );
	
	
	}
	elseif ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) &&  $_POST['action'] == "delete_post" && isset($_POST['pid'])) {
		?>
		<div class="updated notice notice-success is-dismissible below-h2" id="message">
			<p>Word deleted 
				<button class="notice-dismiss" type="button">
					<span class="screen-reader-text">Diese Meldung verwerfen.</span>
				</button>
			</div>
		<?php	
    
        $pid =  			$_POST['pid'];
		
	    //delete post
	    wp_delete_post($pid); 
		
		//delete the Custom Fields --> delete if there is Trash Option
	    delete_post_meta( $pid, 'original');
		delete_post_meta( $pid, 'transliteration');
		delete_post_meta( $pid, 'translation');
		delete_post_meta( $pid, 'proficiency');
	
	
	
	}
	
	;
	
 	ob_start(); ?>
 	
 	
 	<?php
 	
 	//get ALL vt_words
 	echo ob_get_clean();
	$type = 'vt_words';
	$args=array(
	  'post_type' => $type,
	  'post_status' => 'publish',
	  'posts_per_page' => -1,
	  'caller_get_posts'=> 1 );
	
	$my_query = null;
	$my_query = new WP_Query($args);
	if( $my_query->have_posts() ) {?>
		<table class="widefat tablenav">
			<thead><th>Title
			  </th>
			  
			  <th>Nepali
			  </th>
			  <th>Transliteration
			  </th>
			  <th>English
			  </th>
			  <th>Content
			  </th>
			  <th>Proficiency
			  </th>
			  <th></th>
			  <th></th>
			 </thead>
			 <tr><form action="" method="post" name="myForm">
	    		<td>
			  	<h3>NEW WORD</h3>
			  
	    		<td>
			  <input type="text" name="original" value=""></td>
			 <td> 
			  <input type="text" name="transliteration" value=""></td>
			  <td>
			  <input type="text" name="translation" value=""></td>
			  <td>
			  <textarea name="content" rows="10" cols="50"></textarea></td>
			  <td><select name="proficiency" value="">
				  <option value="beginner">Beginner</option>
				  <option value="intermediate">Intermediate</option>
				  <option value="advanced">Advanced</option>
				  
				</select></td>
			  <td><input type="submit" value="New Word" class="button-primary"></td>
			  <input type="hidden" name="action" value="new_post" />
				<?php wp_nonce_field( 'new-post' ); ?></form>
			  </tr>
		<?php
	  while ($my_query->have_posts()) : $my_query->the_post(); 
	  $id = get_the_ID();
	  $custom = get_post_custom($id);
	  
	  $title = get_the_title();
	  $content = get_the_content();
	  $original = $custom["original"][0];
	  $transliteration = $custom["transliteration"][0];
	  $translation = $custom["translation"][0];
	  $proficiency = $custom["proficiency"][0];
	  ?>
	    
	    	<tr><form action="" method="post" name="myForm">
	    		<td>
			  <strong><?php echo $title; ?></strong></td>
			  
	    		<td>
			  <input type="text" name="original" value="<?php echo $original; ?>"></td>
			 <td> 
			  <input type="text" name="transliteration" value="<?php echo $transliteration; ?>"></td>
			  <td>
			  <input type="text" name="translation" value="<?php echo $translation; ?>"></td>
			  <td>
			  <div class="accordion">
				  <h3>Edit Content</h3>
				  <div>
				    <textarea name="content" rows="10" cols="50"><?php echo $content; ?></textarea>
				  </div>
			  </div></td>
			  <td><select name="proficiency" >
				  <option value="beginner" <?php if ($proficiency == "beginner") { echo "selected='selected'";}; ?>>Beginner</option>
				  <option value="intermediate" <?php if ($proficiency == "intermediate") { echo "selected='selected'";}; ?>>Intermediate</option>
				  <option value="advanced" <?php if ($proficiency == "advanced") { echo "selected='selected'";}; ?>>Advanced</option>
				  
				</select></td>
			  
			  <td><input type="submit" value="Update" class="button-primary"></td>
			  
			  <input type="hidden" name="action" value="update_post" />
				<input type="hidden" name="pid" value="<?php echo $id; ?>" />
				<?php wp_nonce_field( 'update-post' ); ?></form>
				<form action="" method="post" name="myForm">
	    		
			  <td><input type="submit" value="Delete" class="button-primary"></td>
			  <input type="hidden" name="action" value="delete_post" />
				<input type="hidden" name="pid" value="<?php echo $id; ?>" />
				<?php wp_nonce_field( 'delete-post' ); ?></form>
			  </tr>
	    	
	    <?php
	  endwhile;?>
	  			
	  </table><?php
	}
	wp_reset_query();
	
	
	
	
 };
 
 

//enqueue scripts / styles

function vt_frontend_scripts() {
		 // register AngularJS
		  wp_register_script('angular-core', 'https://ajax.googleapis.com/ajax/libs/angularjs/1.2.14/angular.js', array(), null, false);
		  
		  	wp_register_script(
				'angular-route', 'https://ajax.googleapis.com/ajax/libs/angularjs/1.2.16/angular-route.min.js', array('angular-core'), null, false
			);
		  
		
		  // register our app.js, which has a dependency on angular-core
		  wp_register_script('angular-app', plugins_url( '/js/app.js', __FILE__ ), array('angular-core','angular-route'), null, false);
		
		  // enqueue all scripts
		  wp_enqueue_script('angular-core');
		  wp_enqueue_script('angular-route');
		  wp_enqueue_script('angular-app');
		
		  // we need to create a JavaScript variable to store our API endpoint...   
		  wp_localize_script( 'angular-core', 'AppAPI', array( 'url' => get_bloginfo('wpurl').'/wp-json/') ); // this is the API address of the JSON API plugin
		  // ... and useful information such as the theme directory and website url
		  wp_localize_script( 'angular-core', 'BlogInfo', array( 'url' => get_bloginfo('template_directory').'/', 'site' => get_permalink()) );
		  
		  wp_localize_script(
				'angular-app',
				'myLocalized',
				array(
					'partials' => plugins_url( '/partials/', __FILE__ )
					)
			);
	
}
add_action( 'wp_enqueue_scripts', 'vt_frontend_scripts' );





function vt_admin_script_init () {
	wp_register_script ('vt_custom', plugins_url( '/js/vt_custom.js', __FILE__ ));
	
	
	wp_enqueue_style('vt_admin_custom', plugins_url( '/admin-styles.css', __FILE__ ));
	 wp_enqueue_style( 'vt_admin_custom' );
}
add_action ('admin_init', 'vt_admin_script_init');




 
 function vt_admin_scripts() {
 	wp_enqueue_script('jquery-ui-accordion');
	wp_enqueue_script('vt_custom','jquery-ui-accordion') ;
 }

 add_action('admin_enqueue_scripts', 'vt_admin_scripts');
 
 
 
// modify header tab

function vt_header() {
	?>
	<base href="<?php echo get_permalink(); ?>" />
	<?php
}

add_action('wp_head','vt_header');

 
 
//admin tab
function vt_menu() {
	add_options_page('My Options', 'Vocab Trainer', 'manage_options', 'my-plugin.php', 'vt_page');
};
 add_action('admin_menu', 'vt_menu');
 
 //custom post-type
 add_action( 'init', 'vt_create_post_type' );
 
 
function vt_create_post_type() {
    register_post_type( 'vt_words',
        array(
            'labels' => array(
                'name' => __( 'VTWords' ),
                'singular_name' => __( 'VTWord' )
            ),
        'public' => true,
        'has_archive' => true,
        'show_ui'		=> false
        )
    );
}

//Custom Fields

add_action("admin_init", "vt_admin_init");
 
function vt_admin_init(){
  add_meta_box("credits_meta", "Word Fields", "credits_meta", "vt_words", "advanced", "core");

}
 
 
function credits_meta() {
  global $post;
  $custom = get_post_custom($post->ID);
  $original = $custom["original"][0];
  $transliteration = $custom["transliteration"][0];
  $translation = $custom["translation"][0];
  ?>
  <p><label>Word</label><br />
  <input type="text" name="original" value="<?php echo $original; ?>"></p>
  <p><label>Transliteration</label><br />
  <input type="text" name="transliteration" value="<?php echo $transliteration; ?>"></p>
  <p><label>Translation</label><br />
  <input type="text" name="translation" value="<?php echo $translation; ?>"></p>
  <?php
}
 
 
add_action('save_post', 'save_details');

function save_details(){
  global $post;
  update_post_meta($post->ID, "original", $_POST["original"]);
  update_post_meta($post->ID, "transliteration", $_POST["transliteration"]);
  update_post_meta($post->ID, "translation", $_POST["translation"]);
 
 }


add_filter( 'json_prepare_post', function ($data, $post, $context) {
	$data['custom_fields'] = array(
		'original' => get_post_meta( $post['ID'], 'original', true ),
		'transliteration' => get_post_meta( $post['ID'], 'transliteration', true ),
		'translation' => get_post_meta( $post['ID'], 'translation', true ),
		'proficiency' => get_post_meta( $post['ID'], 'proficiency', true ),
	);
	return $data;
}, 10, 3 );


function addsomevars($filters){
   $metaparts = array("meta_key", "meta_value", "meta_compare", "meta_query");
   $filters = array_merge($filters, $metaparts);
   return $filters;
}
add_filter('json_query_vars', 'addsomevars');

//Shortcode

//[vocab-trainer]
function vt_short( $atts ){
	?>
	<div ng-app="vt-app">
		<div ng-view></div>
	</div>
	
	<?php
}
add_shortcode( 'vocab-trainer', 'vt_short' );

?>