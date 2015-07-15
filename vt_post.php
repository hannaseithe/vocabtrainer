<?php if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) &&  $_POST['action'] == "update_post" && isset($_POST['pid'])) {

    // Do some minor form validation to make sure there is content
    
        $id =				$_POST['pid'];
        $title =  			$_POST['title'];
		$original =			$_POST['original'];
		$transliteration =	$_POST['transliteration'];
		$translation =		$_POST['translation'];
        $content = 			$_POST['content'];
		
   
    

    // Add the content of the form to $post as an array
    $update_post = array(
    	'ID'			=> $id,
        'post_title'    => $title,
        'post_content'  => $description 
    );
	
	 
    //save the new post and return its ID
    wp_update_post($id, $update_post); 
    update_post_meta( $id, 'original', $original );
	update_post_meta( $id, 'transliteration', $transliteration );
	update_post_meta( $id, 'translation', $translation );
	
	
	
}

	
    ?>
