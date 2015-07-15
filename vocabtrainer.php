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
 	if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) &&  $_POST['action'] == "update_post" && isset($_POST['pid'])) {
		echo "Update Posted";
    
        $id =				$_POST['pid'];
        $title =  			$_POST['title'];
		$original =			$_POST['original'];
		$transliteration =	$_POST['transliteration'];
		$translation =		$_POST['translation'];
        $content = 			$_POST['content'];

		echo $id;
	    // Add the content of the form to $post as an array
	    $update_post = array(
	    	'ID'			=> $id,
	        'post_title'    => $title,
	        'post_content'  => $content 
	    );
		
	    //save the new post and return its ID
	    wp_update_post($update_post); 
		
		//update the Custom Fields
	    update_post_meta( $id, 'original', $original );
		update_post_meta( $id, 'transliteration', $transliteration );
		update_post_meta( $id, 'translation', $translation );
	
	
	
	};
 	ob_start(); ?>
 	<h1>Edit Words / New Words</h1>
 	
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
		<table>
			<tr><td><h3>Title</h3>
			  </td>
			  
			  <td><h3>Nepali</h3>
			  </td>
			  <td><h3>Transliteration</h3>
			  </td>
			  <td><h3>English</h3>
			  </td>
			  <td><h3>Content</h3>
			  </td>
			  <td></td>
			 </tr>
		<?php
	  while ($my_query->have_posts()) : $my_query->the_post(); 
	  $id = get_the_ID();
	  $custom = get_post_custom($id);
	  
	  $title = get_the_title();
	  $content = get_the_content();
	  $original = $custom["original"][0];
	  $transliteration = $custom["transliteration"][0];
	  $translation = $custom["translation"][0];?>
	    
	    	<tr><form action="" method="post" name="myForm">
	    		<td>
			  <input type="text" name="title" value="<?php echo $title; ?>"></td>
			  
	    		<td>
			  <input type="text" name="original" value="<?php echo $original; ?>"></td>
			 <td> 
			  <input type="text" name="transliteration" value="<?php echo $transliteration; ?>"></td>
			  <td>
			  <input type="text" name="translation" value="<?php echo $translation; ?>"></td>
			  <td>
			  <textarea  name="content"> <?php echo $content; ?></textarea></td>
			  <td><input type="submit" value="Update" class="button-primary"></td>
			  <input type="hidden" name="action" value="update_post" />
				<input type="hidden" name="pid" value="<?php echo $id; ?>" />
				<?php wp_nonce_field( 'new-post' ); ?></form>
			  </tr>
	    	
	    <?php
	  endwhile;?></table><?php
	}
	wp_reset_query();
	
	
	
	
 };
 
 

 	


 
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
?>