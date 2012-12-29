<?php 
/**
 *  Gallery Custom Post Type 
 *  @author Milos djorjdevic
 *  @desc	 
 * 
 */


class GalleryCustomPostType {
    
    /**
     * Post type id
     * @var string
     */
    protected $post_type = 'mlsgallery';
    
    protected $meta_db_key = 'mls_gallery_image_ids';
    
    /**
     * Options for meta box
     * @var array
     */
    protected $options = array();    
    
    /**
     * Entry point.
     */
    public function init(){
        $this->registerPostType()
             ->doHooks();
        return $this;    
    }

        
    /**
     * Register Gallery Post Type
     */
    protected function registerPostType(){
        
        $labels = array(
			'name' => __('Galleries', 'Gallery General Name', 'kids_theme'),
			'singular_name' => __('Gallery Item', 'Gallery Singular Name', 'kids_theme'),
			'add_new' => __('Add New', 'Add New Gallery Name', 'kids_theme'),
			'add_new_item' => __('Add New Gallery', 'kids_theme'),
			'edit_item' => __('Edit Gallery', 'kids_theme'),
			'new_item' => __('New Gallery', 'kids_theme'),
			'view_item' => __('View Gallery', 'kids_theme'),
			'search_items' => __('Search Gallery', 'kids_theme'),
			'not_found' =>  __('Nothing found', 'kids_theme'),
			'not_found_in_trash' => __('Nothing found in Trash', 'kids_theme'),
			'parent_item_colon' => ''
		);
		
		$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'menu_position' => 5,
			'supports' => array('title','author','thumbnail','excerpt')
		  ); 
        
       register_post_type($this->post_type, $args);  
       flush_rewrite_rules();     
        return $this;
    }
    
    /**
     * Create Meta box for gallery custom type
     */
    public function doGalleryOptions(){

        add_meta_box('gallery_post_type_option', 
        			 __('Gallery Options', ThemeSetup::HOOK . '_theme' ),
        			 array(&$this, 'renderOptions'),  //fja koja daje renderuje opcije
        			 $this->post_type, //post type id
        			 'normal', 
        			 'high');
        return $this;
    }
    
    public function renderOptions(){
        global $post_id;
        
        // Use nonce for verification
	    wp_nonce_field( plugin_basename(__FILE__), ThemeSetup::HOOK . '_gallery_noncename' );
    	
    	echo '<div class="custom-admin-theme-css">'; 
        	echo '<div class="inside">';
        	    $this->getMediaImage();
        	echo '</div>';
    	echo '</div>';
    }
    
    /**
     * Ajax Handler for loading an images in Metabox.
     */
    protected function doHooks(){ 
        add_action('add_meta_boxes',          array(&$this, 'doGalleryOptions')); 
        add_action('wp_ajax_get_media_image', array(&$this, 'getMediaImage'));
        add_action('save_post',               array(&$this, 'doGSaveData'));
        return $this;
    }
    
    public function getMediaImage(){
        global $post_id;
        $image_width  = 110;
        
		$paged = (isset($_POST['page']))? $_POST['page'] : 1; 	
		if($paged == ''){ $paged = 1; }
		
		$paged = (int) $paged;
		
		$statement = array('post_type' => 'attachment',
                			'post_mime_type' =>'image',
                			'post_status' => 'inherit', 
                			'posts_per_page' =>10,
		                    'nopaging ' => false,
                			'paged' => $paged);
		
		//Get all images from WP media center
		$media_query = new WP_Query($statement);
		
        $data = get_post_meta($post_id, $this->meta_db_key, true);
        $selected_imgs = '';
       
        // Do selected images
    	if(is_array($data) && !empty($data)){
    	    foreach ($data as $i_key=>$item) { 
    			$thumb_src = wp_get_attachment_image_src( $item['id'], 'thumbnail');
    			$thumb_src_preview = wp_get_attachment_image_src( $item['id'], 'medium');
    	        $selected_imgs .=  '<li>
    	        						<img width="'.$image_width.'" src="'.$thumb_src[0].'" rel="'.$thumb_src_preview[0].'" />
    	        						<input type="hidden" name="gallery_image_id['.$i_key.'][id]" value="'.$item['id'].'">
    	        						<input type="hidden" name="gallery_image_id['.$i_key.'][title]" value="'.$item['title'].'">
    	        						<input type="hidden" name="gallery_image_id['.$i_key.'][subtitle]" value="'.$item['subtitle'].'">
    	        						<input type="hidden" name="gallery_image_id['.$i_key.'][text]" value="'.$item['text'].'">
    	        						
    	        						<a data-id="'.$item['id'].'" data-toggle="modal" data-target="#tmp_form" href="#tmp_form" class="edit-pic"><img src="'.KIDS_ADMIN_URI.'/images/pencil-add-icon.png"></a>
    									<a href="#" class="delete-pic"><img src="'.KIDS_ADMIN_URI.'/images/pencil-delete-icon.png"></a>
    	        					</li>';
    	    }
    	}
		
		?>
        <?php if(!isset($_POST['page']) || $_POST['page'] == '') : ?>
        <div id="selected-images"><ul><?php echo $selected_imgs; ?></ul></div>
        <div id="media-wrapper">
    		<div class="media-title">
    			<label><?php _e('SELECT PHOTOS','kids_theme'); ?></label>
    		</div>
    	<?php endif; ?>	
    		<?php
    		echo '<!-- Container for selected images --><div id="image-selector"><div class="media-gallery-nav" id="media-gallery-nav">';
    		echo '<!-- pagination --> <ul>';
    		echo '<li class="nav-first" rel="1" ><a href="#">&laquo;</a></li>';
    		
    		for( $i=1 ; $i<=$media_query->max_num_pages; $i++){
    			if($i == $paged){
    				echo '<li class="current" rel="' . $i . '">' . $i . '</li>';
    			}else if( ($i <= $paged+2 && $i >= $paged-2) || $i%10 == 0){
    				echo '<li rel="' . $i . '"><a href="#">' . $i . '</a></li>';		
    			}
    		}
    		echo '<li class="nav-last" rel="' . $media_query->max_num_pages . '"><a href="#">&raquo;</a></li>'; 
    		echo '</ul><!-- pagination end -->';
    		echo '</div><br class="clear">';
    		
    		echo "<div class='loading' style='display:none;height: 90px; width:100%; text-align:center; position:absolute; bottom:0px;'>Loading...</div>";
    		echo '<ul class="thumbs">';
    		foreach( $media_query->posts as $image ){ 
    			$thumb_src = wp_get_attachment_image_src( $image->ID, 'thumbnail');
    			$thumb_src_preview = wp_get_attachment_image_src( $image->ID, 'medium');
    			echo '<li>
    						<img width="70" src="' . $thumb_src[0] .'" attid="' . $image->ID . '" rel="' . $thumb_src_preview[0] . '"/>
    				  </li>';
    		}
    		echo '</ul><br class="clear"></div>';
    		//This is hidden
    		echo '<div id="tmp_form" style="display:none;" class="modal hide fade">
    						<div class="modal-header">
                                <a class="close" data-dismiss="modal">×</a>
                                <h3 style="background:none; border: none;">Photo Data</h3>
                            </div>
                            <div class="modal-body">
                            	<label>Image Title<br /><input style="width:300px" type="text" name="tmp_title" /></label><br />
                                <label>Image Subtitle<br /><input style="width:300px" type="text" name="tmp_subtitle" /></label><br />
                                <label>Image Description<br /><textarea style="width:525px; height: 220px" name="tmp_text" /></textarea></label><br />
                                <input type="button" name="commit" value="Add" />
                            </div>
                            <div class="modal-footer">
                            <p>Aislin Themes</p>
                            </div>
                            
                         </div>';
    		?>
        <?php if(!isset($_POST['page']) || $_POST['page'] == '') : ?>    
        </div><!-- #media-wrapper -->
        <?php endif; ?> 
        <?php 
		
		if(isset($_POST['page'])){ die(''); }
    }
    
    /**
     * Save All data realated to this metabox
     * Enter description here ...
     * @param unknown_type $post_id
     */
    public function doGSaveData($post_id){
        
       if(empty($_POST) || !isset($_POST['gallery_image_id']) || !isset($post_id)) return $post_id;
       
        $post_data = get_post($post_id);
        
        if($post_data->post_type != 'mlsgallery') return ;
        if(!isset($_POST[ThemeSetup::HOOK . '_gallery_noncename'])) return $post_id;
        
        $nonce = check_ajax_referer(plugin_basename(__FILE__), ThemeSetup::HOOK . '_gallery_noncename', false);
        if ($nonce === false){
            die("IM DIEYING");
        }
    	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
    		return $post_id;
    
    	if ( !current_user_can( 'edit_page', $post_id ) ){
    		return $post_id;
    	} 
    	else {
    		if ( !current_user_can( 'edit_post', $post_id ) )
    			return $post_id;
    	}

        $data = array();
       
    	//Collect Input and add/update post meta
    	foreach($_POST['gallery_image_id'] as $id => $img){ 
    		$img['id']       = (int) $img['id'];
    		$img['title']    = strip_tags(trim($img['title']));
    		$img['subtitle'] = strip_tags(trim($img['subtitle']));
    		$img['text']     = esc_textarea(trim($img['text']));
    		
    		if($img['id'] > 0){
        		$data[] = $img;
    		}
    	}
    	
	    $old_data = get_post_meta($post_id, $this->meta_db_key, true);
	   
	    if($old_data =='') $old_data = array();
		//save_meta_data($post_id, $data, $old_data, $this->meta_db_key);
    	if($data == $old_data){
			add_post_meta($post_id, $this->meta_db_key, $data, true);
		}else if(!$data){
			delete_post_meta($post_id, $this->meta_db_key, $old_data);
		}else if($data != $old_data){ 
			update_post_meta($post_id, $this->meta_db_key, $data, $old_data);
		}
    }
} // Class End

$gallery_type = new GalleryCustomPostType();
$gallery_type->init();        