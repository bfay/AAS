<?php
/**
 * Implements meta boxes at post page
 */

class PostOptions {
    
    /**
     * 
     * Enter description here ...
     * @var array
     */
    protected $options = array();
    
    //protected $meta_db_key = 'mls_post_meta_data';
    
    protected $metabox_ids = array('mls_custom_post_meta', 'mls_gallery_post_options');
    
    /**
     * 
     * Enter description here ...
     * @var unknown_type
     */
    protected $errors = array();
    
    
    public function __construct($options = array()){
        $this->options = $options;
    }
    
    
    public function init(){
        if(empty($this->options)){
            $this->_setOptionsDefaults();
        }
        $this->doHooks();
    }
    
    protected function doHooks(){
        add_action('add_meta_boxes', array(&$this, 'setupMetaBoxes'));
        add_action('save_post', array(&$this, 'doSaveData'));
        return $this;
    }
    
    
    public function setupMetaBoxes(){
        add_meta_box($this->metabox_ids[0], 
        			 __('Post Event Options', ThemeSetup::HOOK . '_theme' ),
        			 array(&$this, 'renderEventOptions'),  //fja koja daje renderuje opcije
        			 'post', //post type id
        			 'normal', 
        			 'high');
        return $this;
    }
    
    public function renderEventOptions(){
    
        global $post_id;
        
        // Use nonce for verification
	    wp_nonce_field( plugin_basename(__FILE__), ThemeSetup::HOOK . '_postmeta_noncename' );
    	echo '<div class="custom-admin-theme-css">'; 
        	//Lista slika
        	echo '<div class="inside">';
                foreach ($this->options['mls_custom_post_meta'] as $key => $setting){ // By Fields
                    
                    if($setting['type'] != 'start_section'){
        			    $data = get_post_meta($post_id, $setting['id'], true); 
        		    }
            	    $setting['value'] = (isset($data)) ? $data : '';
            	    echo ThemeAdmin()->parseFieldForMetaBox($setting, ThemeSetup::HOOK);
            	}
        	echo '</div>';
    	echo '</div>';
    }
    
    protected function getOptions($meta_box_id = ''){
        if($meta_box_id!=''){
            
            if(isset($this->options[$meta_box_id]) && is_array($this->options[$meta_box_id])){
                return $this->options[$meta_box_id];
            }else{
                return array();
            }           
        }else{
             throw new Exception('MetaBox not specified', 1003);          
        }
    }
    
    
    /* This real mistery!!?! Why wordpress call this method event if I do not press SAVE!?! grrrrr */
    public function doSaveData($post_id){ 
        
        $post = get_post($post_id);
        
        if($post->post_type == 'mlsgallery') return $post_id;
        
        if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){ 
            return $post_id;
        }
        
        if(!isset($_POST[ThemeSetup::HOOK . '_postmeta_noncename'])) return $post_id;
        
        //  kids_meta_noncename
        $nonce = check_ajax_referer(plugin_basename(__FILE__), ThemeSetup::HOOK . '_postmeta_noncename', false);
        
        // make sure data came from our meta box
       // if (!wp_verify_nonce($_POST[ThemeSetup::HOOK . '_postmeta_noncename'],__FILE__)) return $post_id;
 
        
        if ($nonce === false){
            return $post_id;
            die();
        } 
    
    	if ( !current_user_can( 'edit_page', $post_id ) ){
    		return $post_id;
    	} 
        if ( !current_user_can( 'edit_post', $post_id ) ){
    		return $post_id;
        }
        $data = array();
      
      
        //Save Events
        //Collect Input and add/update post meta
	    foreach($this->options['mls_custom_post_meta'] as $setting){
    		if(!is_array($setting) || $setting['type'] == 'start_section'){
    			continue;
    		}
    		$value = $setting['default']===true? 'true' : $setting['default']===false ? 'false' : $setting['default'];
    		$retrieve = isset($_POST[ThemeSetup::HOOK.$setting['id']]) ? $_POST[ThemeSetup::HOOK.$setting['id']] : false;
    		if($retrieve){
    			$value = $retrieve;
    		}
    		update_post_meta($post_id, $setting['id'], $value);
    		$value = '';
	    }
	}
    
    private function _setOptionsDefaults(){
        
        $this->options['mls_custom_post_meta'] = array(
                             array(
                                      'id'=>'',
                            		'name' => __('Upcoming Events Settings', ThemeSetup::HOOK . '_theme' ),
                            		'desc' => __('This settings are related to an Upcoming Events only.', ThemeSetup::HOOK . '_theme' ),
                            		'type' => 'start_section',
                             ),                        	
                             array(
                            		'id' => '_post_month',
                            		'name' => __('Add Month for Upcoming Event', ThemeSetup::HOOK . '_theme' ),
                            		'desc' => __('Enter the name of a month in form of a three letters. (ex. APR)', ThemeSetup::HOOK . '_theme' ),
                            		'type' => 'text',
                            		'default' => ''
                            	),
                             array(
                            		'id' => '_post_day',
                            		'name' => __('Add Day for Upcoming Event', ThemeSetup::HOOK . '_theme' ),
                            		'desc' => __('Enter two digits. (ex. 12 if an event is scheduled on 12th)', ThemeSetup::HOOK . '_theme' ),
                            		'type' => 'text',
                            		'default' => ''
                            	),
                            array(
                            		'id' => '_post_hours',
                            		'name' => __('Time', ThemeSetup::HOOK . '_theme' ),
                            		'desc' => __('Enter something like "Bookstore, 10:00-12:00"', ThemeSetup::HOOK . '_theme' ),
                            		'type' => 'text',
                            		'default' => ''
                            	));
    
    }
}
$postO = new PostOptions();
$postO->init(); 