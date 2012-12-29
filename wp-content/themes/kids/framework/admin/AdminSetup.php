<?php
/**
 *  Set up all staff required by theme administration panel.
 */


if(!defined('RUN_FOREST_RUN')) exit('No Direct Access');
/**
*   Setup all required scripts
* 	 Hooks
*	 ...etc
*
* 	 For kids Theme administration
*
 */
class AdminSetup {
	
    /* An Instance of Settings Object */
	private $settings = null;
	
	private static $_instance = null;
	
	public $admin_panel_hook_name = '';

	
	private function __construct(){}
	public function __clone(){}

	/**
	 * Returns instance of this class. Singleton
	 */
	public static function getInstance()
	{
		if (null === self::$_instance)
		{
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function init(){
	    $this->setupAdminPlugins();
		$this->addWpMenu();
		$this->setupFunctions();
		$this->setupAdminClasses();
		$this->setupShortCodeBtns();
		$this->setupAjaxCallback();
		//$this->setupMetaBoxes();
		//$this->setupCustomSidebars();
		$this->setupAdminWpCustomizations();
		return $this;
	}

	
	/**
	 * 
	 */
	protected function setupFunctions(){
		//require_once (KIDS_ADMIN_FUNCTIONS_DIR . '/common.php');
	}
	
	/**
	 * Proveriti kakav SCOPE ima objekat ako je kreiran u funkciji a van funkcije vec postoji takav
	 */
	protected function setupAdminClasses(){
		function ThemeData(){
			return ThemeSetup::getInstance()->getThemeDataObj();
		}
	    function ThemeAdmin(){
			return ThemeAdmin::getInstance()->init();
		}
	}
	  
	/**
	 * Insert Menu item in Main administration menu
	 */
	protected function addWpMenu(){
		add_action('admin_menu', array( $this, 'optionsAdminMenu'));
	}
	
	/**
	 * Add theme options to the main admin menu
	 * @return void
	 */
	public function optionsAdminMenu(){
	    
	    
		$this->admin_panel_hook_name = add_object_page( __('Kids Theme','kids_theme'), 
														__('Kids Theme','kids_theme'), 
														'edit_theme_options', 
														'kids_theme', 
														array( ThemeAdmin(), 'displayAdminSettings'));
	    $this->onAdminPageAdded();
	}
	
	protected function onAdminPageAdded(){
	    require_once (KIDS_ADMIN_FUNCTIONS_DIR . '/head.php');
	    $this->setupMetaBoxes();
	}
	
	/**
	 * Hook a Ajax Theme Handler
	 */
	protected function setupAjaxCallback(){
		add_action('wp_ajax_ajaxCallBack', array( $this, 'ajaxCallBack' ));
		add_action('wp_ajax_ajaxShortcode', array( ThemeShortCodes::getInstance(), 'ajaxShortcode' ));
		return $this;
	}
    
	/**
	 * Ajax handler Call
	 */
	public function ajaxCallBack(){
		$this->enforce_restricted_access();
		ThemeAdmin()->adminAjax();
	}
	
	public function setupAdminPlugins(){
	    require_once (KIDS_FRAMEWORK_DIR . '/plugins/regenerate-thumbnails/regenerate-thumbnails.php');
		return $this;
	}
	
	/**
	 * Security Check. Use when  we have an Ajax Call.
	 */
	public function enforce_restricted_access(){
		//if (!current_user_can('manage_options'))  {
	    if (!current_user_can('edit_pages') || !current_user_can('edit_posts'))  {
			wp_die( __('You do not have permission to edit the theme settings', 'kids_theme' ) );
		}
	}
	
	protected function setupMetaBoxes(){
	    global $typenow;
	    if($typenow != 'mlsgallery'){
		    require_once KIDS_ADMIN_DIR . '/lib/PostOptions.php';
	    }
	    
	  //  var_dump($typenow);
	    
	     require_once KIDS_ADMIN_DIR . '/lib/MetaBoxAbstract.php';
	    return $this;
	}
	
	/**
	 * Integrates Buttons in TinyMCE Editor
	 */
	protected function setupShortCodeBtns(){
		 require_once (KIDS_ADMIN_DIR . '/lib/ShortcodeButton.php'); 
		 return $this;
	}
	
	/**
	 * WTF is THIS?!?
	 */
	protected function setupCustomSidebars(){
	     return $this;
		//ThemeSetup::getInstance()->getThemeDataObj()
		/*$sidebars = ThemeData()->setting('custom_sidebars','false');
		if(is_array($sidebars)){
			if(array_key_exists('items', $sidebars)){
				$items = $sidebars['items'];
				if(is_array($items)){
					if ( function_exists('register_sidebar') ){
						foreach($items as $key => $value) {
							$sidebarName = $value;
							register_sidebar(array( 
								'name' => $sidebarName,
								'description' => __('Custom Side Bar',  'options_admin' ),
								'before_widget' => '<div id="%1$s" class="widget %2$s">',  
								'after_widget' => '</div><div class="clear"></div>',  
								'before_title' => '<h4>',  
								'after_title' => '</h4>',  
							));
						}
					}
				}
			}
		}*/
	}
	
	
	/* Update with initial data. This action should be triggered only once. After theme instalation / first update */
	public function importDemoData(){
	     $url = bloginfo("template_url");
	     $data = '';
	    //Update Theme main data
       // update_option('kids_options', $data);
        
        //Update sidebars
      //  update_option('sidebars_widgets', 'a:9:{s:19:"wp_inactive_widgets";a:0:{}s:15:"general_sidebar";a:0:{}s:12:"home_sidebar";a:0:{}s:12:"blog_sidebar";a:0:{}s:15:"footer_sidebar1";a:0:{}s:15:"footer_sidebar2";a:0:{}s:15:"footer_sidebar3";a:0:{}s:15:"footer_sidebar4";a:0:{}s:13:"array_version";i:3;}');
	     
	} 
	
	public function mlsGalleryRowAction($actions, $post){
	    
	    if ($post->post_type == 'mlsgallery'){
	      unset($actions['view']); 
	      unset($actions['inline hide-if-no-js']); 
	    }
	    return $actions;
	}

	/**
	 * WP admin panel customs
	 */
	protected function setupAdminWpCustomizations(){
	  //  add_action('import_end', array($this, 'importDemoData'));
	    add_filter('post_row_actions',array(&$this, 'mlsGalleryRowAction'), 10, 2);
	    return $this;
	}
}