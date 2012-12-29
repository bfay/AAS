<?php
if(!defined("RUN_FOREST_RUN")) die("Forest Gump");
 
abstract class MetaBoxAbstract {
    
    const LANG = 'some_textdomain';
    
    /**
     * Metabox id
     * @var string. Required.
     */
    protected $id = ''; 
    
    /**
     * Metabox title
     * @var string. Required.
     */
    public $title = '';
    
    /**
     *  Function that prints out the HTML for the edit screen section. Pass function name as a string. Within a class, you can instead pass an array to call one of the class's methods. See the second example under Example below.
     * @var string. Required.
     */
    public $callback = 'show';
    
    /**
     * Arguments to pass into your callback function. The callback will receive the $post object and whatever parameters are passed through this variable.
     * @var array. Optional.
     */
    public $callback_args = array();
        
    /**
     * Current Post Type ('post', 'page', 'link', or 'custom_post_type' where custom_post_type is the custom post type slug)
     */
    public $post_type = '';
    
    /**
     * The part of the page where the edit screen section should be shown ('normal', 'advanced', or 'side'). 
     * @var string. Required.
     */
    public $context = 'normal';
    
    /**
     * The priority within the context where the boxes should show ('high', 'core', 'default' or 'low')
     * @var string. Optional.
     */
    public $priority = 'default';
    
    /**
     * Fields on the form
     * @var array
     */
    protected $fields = array();
    
    
    /**
     * All Errors
     * @var array
     */
    protected $errors = array();
    
    
    /* Implement in subclass */
    abstract public function formFieldsConf();

    
    /**
	 * The type of Write screen on which to show the edit screen section 
	 * ('post', 'page', 'link', or 'custom_post_type' where custom_post_type 
	 * is the custom post type slug)
	 * Default: None
	 * 
	 * @var array
	 */
	var $_object_types = array();
	
    public function __construct($id, $title, $callback, $callback_args, $context, $priority, $object_types = null){
        $this->id            = $id;
        $this->title         = $title;
        $this->callback      = ($callback !='') ? $callback : $this->callback;
        $this->callback_args = is_array($callback_args) && count($callback_args) > 0 ? $callback_args : $this;
        $this->priority      = $priority !='' ? $priority : $this->priority;
        $this->context       = $context !='' ? $context : $this->context;
        $this->_object_types = !empty($object_types) ? $object_types : array('post', 'page');
        
		// if the user has not already set the type of this metabox,
		// then we need to do that now
        if(!isset($this->metaboxType) || !$this->metaboxType ) $this->setMetaboxType();

        add_action( 'add_meta_boxes', array(&$this, 'addMetaBox' ));
        add_action( 'save_post'     , array(&$this, 'saveMetaBox'));
	//	add_filter( 'wp_redirect', array(&$this, '_redirectIntervention'), 40, 1 );
	
        /* Populate fields with default values */
         if(empty($this->fields)) $this->formFieldsConf();
         add_filter('metabox-requests-'.$this->id, array(&$this, '_filterRequest'));
    }
    
    
    /*
     * The Idea behind this method is to be implemented in subclass. If it is not implemented there is no
     * additional filtering :)
     */
    public function _filterRequest($param){
        return $param;
    }
    
    /**
     * $id, $title, $callback, $post_type, $context, $priority, $callback_args
     * Enter description here ...
     */
    
    public function addMetaBox(){
        
          //post or page
          $this->post_type     = $this->getCurrentPostType();
        
         // this metabox is to be displayed for a certain object type only
		 if ( !in_array($this->post_type, $this->_object_types) )
			return;
         
		 add_meta_box( 
                        $this->id
                        ,__( $this->title, self::LANG )
                        ,array( &$this, $this->callback )
                        ,$this->post_type 
                        ,$this->context
                        ,$this->priority
                        ,$this->callback_args
                    );
    }
    
    
    /**
     * Save Metabox
     * Enter description here ...
     * @param unknown_type $post_id
     */
    public function saveMetaBox($post_id){
       
        //initializing
		$post = get_post($post_id);
		
		$this->post_type     = $this->getCurrentPostType();

		// verify if this is an auto save routine. 
		// If it is our form has not been submitted, so we dont want to do anything
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return;

		// verify this came from the our screen and with proper authorization,
		// because save_post can be triggered at other times
		if ( !isset($_REQUEST[ get_class().$this->id ]) )
			return;

		if ( !wp_verify_nonce( $_REQUEST[ get_class().$this->id ], plugin_basename( __FILE__ ) ) )
			return;

		// this metabox is to be displayed for a certain object type only
		if ( !in_array($post->post_type, $this->_object_types) )
			return;

		// Check permissions
		if ( 'page' == $post->post_type ) 
		{
			if ( !current_user_can( 'edit_page', $post->ID ) )
				return;
		}
		else
		{
			if ( !current_user_can( 'edit_post', $post->ID ) )
				return;
		}
		
		/* Save Meta Data Logic */
		do_action('metabox-save-'.$this->id, $this->_getRequestedPostMetas(), $post->ID, $this );
		return true;
    }
    
    
    /**
     * Display Metabox
     */
    public function show(){
  		wp_nonce_field( plugin_basename( __FILE__ ), get_class().$this->id ); 
  		do_action('metabox-show-'.$this->id, $this->fields);
    }
    
    /**
     * Save data to DB
     * @param array $source
     * @param int $post_id
     */
    public function _saveAsPostMeta($source, $post_id){
        foreach ($source as $key=>$s){
            update_post_meta($post_id, $key, $s);
        }
        return true;
    }
    
    
    /**
     * Validate / sanitarize and return data before save
     */
    protected function _getRequestedPostMetas(){
        $ignores = array('post_title', 'post_name', 'post_content', 'post_excerpt', 'post',
		'post_status', 'post_type', 'post_author', 'ping_status', 'post_parent', 'message',
		'post_category', 'comment_status', 'menu_order', 'to_ping', 'pinged', 'post_password', 
		'guid', 'post_content_filtered', 'import_id', 'post_date', 'post_date_gmt', 'tags_input',
		'action');

		$fields = array();
		foreach ((array)$this->fields as $field) {
			if (!isset($field['id'])) continue;
			$fields[] = $field['id'];
		}

		$requests = $_REQUEST; 
		foreach ((array)$requests as $k => $request)
		{
			if (($fields && !in_array($k, $fields))	|| (in_array($k, $ignores) || strpos($k, 'nounce')!==false))
			{
				unset($requests[$k]);
			}
		}
		return apply_filters('metabox-requests-'.$this->id, $requests);
    }
    
   /**
     * At the moment supports only pages and posts
     * @param string $type
     */
    protected function setMetaboxType($type='default'){
        
        $this->metaboxType = $type;
        
        switch($this->metaboxType){
            case 'default':
                add_action('metabox-show-'.$this->id, array(&$this, '_renderMetaBoxContent'), 20, 1 );
				add_action('metabox-save-'.$this->id, array(&$this, '_saveAsPostMeta'), 10, 2);
                break;
        }
    }
    
	/**
	 * Method is designed to return the currently visible post type
	 */
	function getCurrentPostType()
	{ global $typenow;
	    return $typenow;
	}
	    
	/**
     * Render inner content of metabox
     */
    public function _renderMetaBoxContent(){
        global $post;
        
        $out = '';

        /* Get Meta for this item */
         $post_meta = get_post_custom($post->ID);
         
         foreach ($this->fields as $k => $f) {
             
             if(!isset($f['id']) || !isset($f['type'])) { 
                  continue;   
             } else{
                 if(array_key_exists($f['id'], $post_meta)){
                     if(is_array($post_meta[$f['id']])){
                         $post_meta_data = unserialize($post_meta[$f['id']][0]); 
                     }else
                     {
                         $post_meta_data = $post_meta[$f['id']]; 
                     }
                     $this->fields[$k]['value'] = $post_meta_data;
                 }
             }    
             $out .= $this->_renderField($this->fields[$k]);
         }
         echo '<div class="custom-admin-theme-css">'.$out.'</div>';
    }
    
    /**
     * Render Form Fields in Metabox
     */
    protected function _renderField($field){
        
        $output = '';
        $value = isset($field['value']) ? $field['value'] : '';
        
        switch ($field['type']){
            
            case 'subsection_start':
                $output .= '<p class="inside"><h4>'.$field['name'].'</h4>'.$field['desc'].'</p>';
                break;
            case 'text':
                $output .= '<p class="inside"><input type="text" name="'.$field['id'].'" value="'.$value.'" /></p>';
                break;
            case 'select':
                $options = '';
                foreach ($field['values'] as $k => $v) {
                    if($v == $value)
                        $options .= '<option value="'.$k.'" selected="selected">'.$v.'</option>';
                    else 
                        $options .= '<option value="'.$k.'">'.$v.'</option>';
                }
                $output .= '<select name="'.$field['id'].'">'.$options.'</select>';
                break;
            case 'multiselect':
                
                $value = isset($value) && $value !='' ? $value : 'no_sidebars';
                if(!is_array($value)){
                    $value = array($value);
                }
                
                $options = '';
                foreach ($field['values'] as $k => $v) {
                    if(in_array($k, $value))
                        $options .= '<option value="'.$k.'" selected="selected">'.$v.'</option>';
                    else 
                        $options .= '<option value="'.$k.'">'.$v.'</option>';
                }
                $output .= '<div class="inside"><h4>'.$field['name'].' - <small>'.$field['desc'].'</small></h4><select style="width:50%" name="'.$field['id'].'[]" multiple="multiple">'.$options.'</select><br /></div>';
                break;
            case 'radio':
                break;    
            case 'checkbox':
                break;
            case 'textarea':
                break;    
            case 'hidden':
                break;
            case 'image':
                break;        
        }

        return  $output;    
    }
    
    
    public function getErrors(){
        return $this->errors;
    }
    
    public function hasErrors(){
        return !empty($this->errors);
    }
}//End of class



class MlsSidebarMetaBox extends MetaBoxAbstract{
    
    public function __construct($id, $title, $callback='', $callback_args, $context, $priority){
        parent::__construct($id, $title, $callback, $callback_args, $context, $priority, null);
    }
    
    public function _filterRequest($param){
        $tmp = $param;
        foreach ($param as $k=>$p){
            if($p == 'no_sidebars'){
                unset($tmp[$k]);
            }
        }
        return $tmp;
    }
    
    public function formFieldsConf(){
        
        $sidebars = Data()->getMain('mi_custom_sidebars.csidebar_list');
        $tmp = array();
        
        if(!is_array($sidebars)) $sidebars = array($sidebars);
        
        $tmp['no_sidebars'] = '-- '.__('No Sidebar', 'kids_theme').' --'; 
        foreach ($sidebars as $value) {
            $tmp[str_replace(' ', '_', strtolower($value))] = $value;
        }
        
        $this->fields = array(
                	 array(
                		'id' => 'kids_sidebar_ids',
                		'name' => __('Select Sidebars', ThemeSetup::HOOK . '_theme' ),
                		'desc' => __('Selected sidebars will be shown at this page/post', ThemeSetup::HOOK . '_theme' ),
                		'type' => 'multiselect',
                	    'values' => $tmp,
                	    'value' => null,
                		'default' => ''
                	)
        );//Ebd of fields
        
        return $this->fields;
    }
} // End MlsSidebarMetaBox

$sidebarMetabox = new MlsSidebarMetaBox('mls_sidebar', 'Sidebars', '', array(), "", "");
