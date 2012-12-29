<?php
/**
 * As name implies this class manage all thing related to a Theme Setup. 
 * 
 *  1. Loading all configs from config files
 *  2. Setting up all required filters and actions
 *  3. Set up Theme support (Thumbs/// etc)
 *  4. Trigger activate method only once(When theme is installing)
 *  5. ...
 */
if(!defined('RUN_FOREST_RUN')) exit('No Direct Access1');

/*
	Setup all aspects of a theme
*/

class ThemeSetup{

    /*Theme version*/
	const THEME_VERSION = '1.4.3';

	/**
	 * The lookup key used to locate the options record in the wp_options table
	 */
	const OPTIONS_KEY = 'kids_options';
	
	/**
	 * The hook to be used in all variable actions and filters
	 */
	const HOOK = 'kids';


    /* Minimal WP Version */
	public $require_wp = '3.1.1';
	
	
	protected $init_flag = false;
	
	
	protected $installed_flag = false;
	

	/* Instance of this class*/
	private static $_instance = null;


	/* Reference to a ThemeOptions instance */
	protected $theme_options = null;
	
	/**
	 * List of all Errors / Messages
	 */
	protected $errors = array();


	/**
	 *  There can be only one :)
	 */
	private function __construct(){}
	private function __clone(){}

	/**
	 * Returns instance of this class. Singleton
	 */
	public static function getInstance()
	{
		if (null === self::$_instance)
		{
			self::$_instance = new ThemeSetup();
		}
		return self::$_instance;
	}

	/**
	 * Theme Initialization.
	 *
	 * Setup all aspects of a theme
	 */
	public function init(){
	   
	    if($this->init_flag == false){
    	    self::$_instance->setupPaths();
    	    
    		try{
    			// Activation
    			if (is_admin() && isset($_GET['activated']) && isset($GLOBALS['pagenow']) && $GLOBALS['pagenow'] == 'themes.php'){
    				self::$_instance->activate();
    			}
    
    			$this->installed_flag = true;
  
    			 self::$_instance->setupSupport()
                				 ->setupFunctions()
                				 ->setupThemeDataObjHandler()
                				 ->setupThemeOptionHandler()
                				 ->setupHead()
                				 ->setupCustomMenus()
                				 ->setupHooks()
                				 ->setupSideBars()
                				 ->setupWidgets()
                				 ->setupShortCodes()
                				 ->setupPlugins()
                				 ->setupAjax()
                				 ->setupLanguages()
                				 ->setupWpCustomizations();
    				 
    			$this->init_flag = true;
    			
    		}catch(FrameworkException $e){
    			echo $e->getMessage();
    		}
	    }
	    	
		return $this;
	}
	
/**
	 * Performs installation action if necessary
	 *
	 * @return void
	 */
	protected function setupPaths(){
		
		//Theme URI
	    define('KIDS_URI'               , get_template_directory_uri());
	    //CSS URI
	    define('KIDS_CSS_URI'           , KIDS_URI . '/css');
	    //JS URI
	    define('KIDS_JS_URI'            , KIDS_URI . '/js');
	    //JS FONTS URI
	    define('KIDS_FONTS_URI'         , KIDS_URI . '/fonts');
	    //INCLUDES URI
	    define('KIDS_INCLUDES_URI'      , KIDS_URI . '/inc');
	    //CACHE URI
	    define('KIDS_CACHE_URI'         , KIDS_URI . '/cache');
	    
	    //IMAGES URI
	    define('KIDS_IMAGES_URI'        , KIDS_URI . '/images');
	    //ADMIN URI
	    define('KIDS_PLUGIN_URI'         , KIDS_URI . '/framework/plugins');
	    
	    define('KIDS_ADMIN_URI'         , KIDS_URI . '/framework/admin');
	    //ADMIN CSS URI
	    define('KIDS_ADMIN_CSS_URI'     , KIDS_ADMIN_URI . '/css');
	    //ADMIN JS URI
	    define('KIDS_ADMIN_JS_URI'      , KIDS_ADMIN_URI . '/js');


	    /* --- PATHS --- */
	    
	    //DIRECTORY PATH
	    define('KIDS_DIR'               , get_template_directory());
	    
	    //DIRECTORY PATH
	   // define('KIDS_WP_DIR'            , '/../../' . get_template_directory());
	    define('KIDS_WP_DIR'            , '/../../' . dirname(__FILE__));

	    //CACHE DIR
	    define('KIDS_CACHE_DIR'         , KIDS_DIR . '/cache');
	    //FRAMEWORK PATH
	    define('KIDS_FRAMEWORK_DIR'     , KIDS_DIR . '/framework');
	    
	    define('KIDS_CONFIG_DIR'        , KIDS_FRAMEWORK_DIR . '/config');
	    //FUNCTIONS PATH
	    define('KIDS_FUNCTIONS_DIR'         , KIDS_FRAMEWORK_DIR . '/functions');
	    //WIDGETS PATH
	    define('KIDS_PATH_WIDGETS_DIR'      , KIDS_FRAMEWORK_DIR . '/widgets');
	    //XML PATH
	    define('KIDS_XML_DIR'               , KIDS_FRAMEWORK_DIR . '/xml');
	    
	    //ADMIN PATH
	    define('KIDS_ADMIN_DIR'             , KIDS_FRAMEWORK_DIR . '/admin');

	    define('KIDS_ADMIN_VIEW_DIR'        , KIDS_ADMIN_DIR . '/view');
	    //ADMIN FUNCTIONS
	    define('KIDS_ADMIN_FUNCTIONS_DIR'   , KIDS_ADMIN_DIR . '/functions');
	    //LIBRARY PATH
	    define('KIDS_LIB_DIR'               , KIDS_FRAMEWORK_DIR . '/library');

	    return $this;
	}
	

	/**
	 * @todo Check does this environtment supports our theme.
	 */
	protected function activate()
	{
		$this->setupFunctions();
		if (!$this->isInstalled())
		{
			$this->install();
		}
		/* Set posts as default for home page */
		 update_option('show_on_front', 'posts');
	}
	
	protected function isInstalled(){ 
	    $installedVersion = $this->getThemeDataObj()->getOption('meta.version');
	    return (isset($installedVersion) && ($installedVersion !== null)) ? true:false;
	}

	/**
	 * Performs installation when theme is activated for the first time.
	 *
	 * @return void
	 */
	protected function install()
	{
		$this->getThemeDataObj()->setOptionsDefaults()->save();
		$this->installed_flag = true;
	}

	/**
	 * Should we implement this method. It should delete all data related to our theme.
	 */
	protected function uninstall(){	}

	protected function setupSupport(){
		
		if ( function_exists( 'add_theme_support' ) ) {
			add_theme_support( 'automatic-feed-links' );
			add_theme_support( 'post-thumbnails' );
		}

		if ( function_exists( 'add_image_size' ) ) { 
			add_image_size( 'blog-thumb', 172, 140, true); // post list with sidebar
			add_image_size( 'blog-middle-thumb', 446, 182, true); // two columns post's list
			add_image_size( 'blog-large', 600, 182, true); // single post size
			add_image_size( 'blog-big-list', 627, 307, true); // 1 col blog list
			
			add_image_size( 'theme-big-slider', 945, 300, true); // Big slider
			add_image_size( 'theme-big-slider-with-scroller', 650, 300, true); // Big slider with scroller
			
			add_image_size( 'theme-small-thumb', 290, 132, true); // front featured section
			
			//gallery thumbs 4 cols 157x194
			add_image_size( 'theme-gallery-thumb', 194, 157, true); //gallery 4 cols thumbs
			add_image_size( 'small-gallery-thumb', 135, 110, true); //gallery 3 cols thumbs
			
			add_image_size( 'theme-gallery-photo', 800, 9999); // Gallery photo
		}  
		return $this;
	}
	
	protected function setupHooks(){
	    add_action('init', array(&$this, 'setupCustomTypes'));
	    return $this;
	}
	
	/**
	 * Register new POST type
	 */
	public function setupCustomTypes(){
    	require_once KIDS_ADMIN_DIR . '/lib/GalleryPostType.php';
	}

	protected function setupHead(){
		require_once (KIDS_FUNCTIONS_DIR . '/head.php');
		return $this;
	}

	protected function setupFunctions(){

		require_once (KIDS_FUNCTIONS_DIR . '/common.php');
		require_once (KIDS_FUNCTIONS_DIR . '/helper.php');
		return $this;
	}
	
	protected function setupCustomMenus(){
		if ( function_exists( 'register_nav_menus' ) ) {
			register_nav_menus(array(
									'header-menu' => __('Header Menu', self::HOOK . '_theme' )
								));
		}
		return $this;
	}	
	
	/**
	 * Front Ajax Handler. All Ajax Requests goes through this script.
	 */
	protected function setupAjax(){
	    add_action( 'wp_ajax_mls_ajax_handler', 'mls_ajax_handler' );  
	    add_action( 'wp_ajax_nopriv_mls_ajax_handler', 'mls_ajax_handler' );  
	    return $this;
	}
			
	protected function setupWpCustomizations(){
		add_filter('excerpt_more', array(&$this, '_new_excerpt_more'));

    	function removeHeadLinks() {
	    	remove_action('wp_head', 'rsd_link');
	    	remove_action('wp_head', 'wlwmanifest_link');
    	}
       	add_action('init', 'removeHeadLinks');
    	remove_action('wp_head', 'wp_generator');

    	//Changes double line-breaks in the text into HTML paragraphs (<p>...</p>). Removed!
    	remove_filter( 'the_content', 'wpautop' );
		remove_filter( 'the_excerpt', 'wpautop' );
		
		/* Lenght of excerpt is 20 words at homepage, in other cases standard */
        function custom_excerpt_length( $length ) {
            //This does not work as expected. Fix it.
            if(is_home()){ 
                return 20;
        	}else{ 
        	    return $length;
        	}    
        }
        add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );
        
        add_filter('the_excerpt', 'strip_shortcodes');
        
         function myremove_shortcode($content) {
            $content = strip_shortcodes( $content );
          return $content;
         }add_filter('widget_text', 'myremove_shortcode');
        
       

		/*
		  This returns given text with transformations of quotes to smart quotes, apostrophes, dashes, ellipses, the trademark symbol, 
		  and the multiplication symbol. 
		  Text enclosed in the tags <pre>, <code>, <kbd>, <style>, <script>, <tt>, and [code] will be skipped.

		  Removed. 
		*/
		remove_filter('the_content','wptexturize');
		return $this;
	}

	protected function setupThemeDataObjHandler(){
		if($this->theme_options == null){
		    $this->theme_options = new ThemeOptions(self::OPTIONS_KEY);
		}
		return $this;
	}

	protected function setupThemeOptionHandler(){
		function Data(){
			return ThemeSetup::getInstance()->getThemeDataObj();
		}
		return $this;
	}

	public function &getThemeDataObj(){
	    
	    if($this->theme_options == null){
	        $this->theme_options = new ThemeOptions(self::OPTIONS_KEY);
	    }
	    
	    return $this->theme_options;
	}

	public function _new_excerpt_more($more) {
		return __('...', self::HOOK . '_theme' );
	}
	
	protected function setupLanguages(){
		/*$locale = get_locale();
		if (is_admin()) {
			load_theme_textdomain( self::HOOK . '_admin', KIDS_ADMIN_DIR . '/languages' );
			$locale_file = KIDS_ADMIN_DIR . "/languages/$locale.php";
		}else{
			load_theme_textdomain( self::HOOK . '_front', KIDS_ADMIN_DIR . '/languages' );
			$locale_file = KIDS_DIR . "/languages/$locale.php";
		}
		if ( is_readable( $locale_file ) ){
			require_once( $locale_file );
		}*/

		return $this;
	}

	protected function setupSideBars(){
		if ( function_exists('register_sidebar') ){
		    
		    register_sidebar(array( 
				'name' => __('General Sidebar', 'kids_theme' ),
				'description' => __('Sidebar shown at all pages except "blog", "search", "contact" pages',  ThemeSetup::HOOK . '_theme' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',  
				'after_widget' => '</div>',  
				'before_title' => '<h3 class="widget-title">',  
				'after_title' => '</h3>', 
			    'id'=>'general_sidebar'
			));
			
			register_sidebar(array( 
				'name' => __('Home Page Sidebar', 'kids_theme' ),
				'description' => __('SideBar shown at home page template.',  ThemeSetup::HOOK . '_theme' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',  
				'after_widget' => '</div>',  
				'before_title' => '<h3 class="widget-title">',  
				'after_title' => '</h3>', 
			    'id'=>'home_sidebar'
			));
			
			register_sidebar(array( 
				'name' => __('Blog Sidebar', 'kids_theme' ),
				'description' => __('Blog Sidebar Widget Area',  ThemeSetup::HOOK . '_theme' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',  
				'after_widget' => '</div>',  
				'before_title' => '<h3 class="widget-title">',  
				'after_title' => '</h3>', 
			    'id'=>'blog_sidebar'
			));
			
			register_sidebar(array( 
				'name' => __('Search Page Sidebar', 'kids_theme' ),
				'description' => __('SideBar shown at search page template.',  ThemeSetup::HOOK . '_theme' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',  
				'after_widget' => '</div>',  
				'before_title' => '<h3 class="widget-title">',  
				'after_title' => '</h3>', 
			    'id'=>'search_sidebar' 
			));
			
			register_sidebar(array( 
				'name' => __('Contact Page Sidebar', 'kids_theme' ),
				'description' => __('SideBar shown at contact page template.',  ThemeSetup::HOOK . '_theme' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',  
				'after_widget' => '</div>',  
				'before_title' => '<h3 class="widget-title">',  
				'after_title' => '</h3>', 
			    'id'=>'contact_sidebar' 
			));
			
			register_sidebar(array( 
				'name' => __('Footer Widget Column 1', 'kids_theme' ),
				'description' => __('Footer Column 1 Widget Area',  ThemeSetup::HOOK . '_theme' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',  
				'after_widget' => '</div>',  
				'before_title' => '<h3 class="widget-title">',  
				'after_title' => '</h3>', 
			    'id'=>'footer_sidebar1' 
			));
			
			register_sidebar(array( 
				'name' => __('Footer Widget Column 2', 'kids_theme' ),
				'description' => __('Footer Column 2 Widget Area', ThemeSetup::HOOK . '_theme' ), 
				'before_widget' => '<div id="%1$s" class="widget %2$s">',  
				'after_widget' => '</div>',  
				'before_title' => '<h3 class="widget-title">',  
				'after_title' => '</h3>',
			    'id'=>'footer_sidebar2'  
			));
			
			register_sidebar(array( 
				'name' => __('Footer Widget Column 3', 'kids_theme' ),
				'description' => __('Footer Column 3 Widget Area', ThemeSetup::HOOK . '_theme' ), 
				'before_widget' => '<div id="%1$s" class="widget %2$s">',  
				'after_widget' => '</div>',  
				'before_title' => '<h3 class="widget-title">',  
				'after_title' => '</h3>', 
			    'id'=>'footer_sidebar3' 
			));
			
			register_sidebar(array( 
				'name' => __('Footer Widget Column 4', 'kids_theme' ),
				'description' => __('Footer Column 4 Widget Area', ThemeSetup::HOOK . '_theme' ), 
				'before_widget' => '<div id="%1$s" class="widget %2$s">',  
				'after_widget' => '</div>',  
				'before_title' => '<h3>',  
				'after_title' => '</h3>',
			    'id'=>'footer_sidebar4'  
			));
			
			//Check if there are User created sidebars?
			$sidebars = Data()->getMain('mi_custom_sidebars.csidebar_list'); 
			if(!is_array($sidebars)) $sidebars = array($sidebars);
			if(count($sidebars) > 0){
			    foreach ($sidebars as $s){
			        $tmp = str_replace(' ', '_', strtolower($s));
			        register_sidebar(array( 
        				'name' => __('User Defined - '. $s, 'kids_theme' ),
        				'description' => __('This sidebar was created in the Theme\'s Administration Panel.', ThemeSetup::HOOK . '_theme' ), 
        				'before_widget' => '<div id="%1$s" class="widget %2$s">',  
        				'after_widget' => '</div>',  
        				'before_title' => '<h3>',  
        				'after_title' => '</h3>',
        			    'id'=> 'kids_' . $tmp
        			));
			    }
			}
		}
		return $this;
	}
	
	protected function setupWidgets(){
	    
	    require_once KIDS_PATH_WIDGETS_DIR . '/KidsRecentPostsWidget.php';
	    require_once KIDS_PATH_WIDGETS_DIR . '/KidsCategoriesWidget.php';
	    require_once KIDS_PATH_WIDGETS_DIR . '/KidsArchivesWidget.php';
	    require_once KIDS_PATH_WIDGETS_DIR . '/KidsPagesWidget.php';
	    require_once KIDS_PATH_WIDGETS_DIR . '/KidsCustomHtmlWidget.php';
	 
	    require_once KIDS_PATH_WIDGETS_DIR . '/KidsContactFormWidget.php';
	    require_once KIDS_PATH_WIDGETS_DIR . '/KidsLatestNewsWidget.php';
	    require_once KIDS_PATH_WIDGETS_DIR . '/KidsLatestPostsWidget.php';
	    require_once KIDS_PATH_WIDGETS_DIR . '/KidsEventsWidget.php';
	    require_once KIDS_PATH_WIDGETS_DIR . '/KidsWorkingHoursWidget.php';
	    require_once KIDS_PATH_WIDGETS_DIR . '/KidsContactInfoWidget.php';
		return $this;
	}
	
	protected function setupShortCodes(){
		require_once (KIDS_LIB_DIR . '/ThemeShortcodes.php');
		return $this;
	}
	
	protected function setupPlugins(){
		require_once (KIDS_FRAMEWORK_DIR . '/plugins/breadcrumbs-plus/breadcrumbs-plus.php');
		require_once (KIDS_FRAMEWORK_DIR . '/plugins/wp-pagenavi/wp-pagenavi.php');
		require_once (KIDS_FRAMEWORK_DIR . '/plugins/easy-post-subtitle/easy-post-subtitle.php');
		return $this;
	}

	protected function getRequiredWpVersion(){
		return $this->require_wp;		
	}

	public function wpVersionCheck(){
		global $wp_version;

		if (version_compare($wp_version, $this->getRequiredWpVersion(), "<")){
			return false;
		}else{
			return true;
		}
	}
	
    public function getInitFlag(){
	    return $this->init_flag;
	}

    /**
     * Return theme version
     */
	public function getVersion(){
		return THEME_VERSION;
	}
	
	public function hasErrors(){
	    return count($this->errors) > 0 ? true : false;
	}
}