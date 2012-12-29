<?php
class ShortcodeButton {
    
    public static $_instance = null;
    
    protected $options = array();
    
    private function __construct($options = array()){}
    
    public function getInstance(){
        if (null === self::$_instance)
		{
			self::$_instance = new ShortcodeButton();
		}
		return self::$_instance;
    }
    
    public function init($options = array()){
        $this->options = $options;
        $this->initConfig();
        add_action('init', array(&$this, 'addButton'));
       
    }
    
    public function addButton(){
         if (current_user_can('edit_posts') &&  current_user_can('edit_pages'))  
         {  
             add_filter('mce_external_plugins', array(&$this, 'addPlugin'));  
            // add_filter('mce_buttons', array(&$this, 'registerButton')); 
            add_filter('mce_buttons_3', array(&$this, 'registerButton')); 
         }  
    }
    
    public function registerButton($buttons){ // 'mls_gallery', 'mls_video', 'mls_mark', 'mls_quoteleft', 'mls_quoteright'
        array_push($buttons,'mls_lists','|','mls_headings','|','mls_mark','mls_devider','|','|','mls_halfx2','mls_thirdx3','mls_onethird_twothird','mls_twothird_onethird','|','|','mls_blockquote','mls_quoteleft','mls_quoteright','|','mls_msg','|','mls_yt','mls_iframe','mls_gallery','|','|');
        return $buttons;
    }
    
    public function addPlugin($plugin_array) {  
       $plugin_array['shortcodes']     = KIDS_ADMIN_JS_URI . '/tinyMCEshortcodes.js';  
       $plugin_array['shortcodeslist'] = KIDS_ADMIN_JS_URI . '/mls_mce_list_plugin.js';  
       return $plugin_array;  
    }  
    
    public function getConf($key){
        return $this->options[$key];
    }
    
    
    public function initConfig(){

        /**
         *  Mark Shortcode Options 
         */
             
        /* Gallery options */
         $galleries = mls_get_galleries();
         $this->options['mls_gallery'] = array(
         
            array('id'          =>'mls_sc_gallery_id', 
            	  'type'        =>'select', 
            	  'name'        =>__('Select a Gallery', 'kids_theme'), 
            	  'desc' =>    __(' ', 'kids_theme'),
                  'values'      => $galleries,
            	  'default'     => null,
            	  'value'       => null),
            
             array('id'         =>'mls_sc_cols_num', 
            	  'type'        =>'text', 
            	  'name'        =>__('Number of columns', 'kids_theme'), 
            	  'desc' =>    __('Value between 2 and 4', 'kids_theme'),
            	  'default'     =>4,
            	  'value'       =>null),
         
            array('id'          =>'mls_sc_gtitle_switch', 
            	  'type'        =>'checkbox', 
            	  'name'        =>__('Show Title', 'kids_theme'), 
            	  'desc' =>    __('Main Gallery Title', 'kids_theme'),
            	  'default'     =>'off',
            	  'value'       =>null),
            
             array('id'          =>'mls_sc_gsubtitle_switch', 
            	  'type'        =>'checkbox', 
            	  'name'        =>__('Show Gallery Subtitle', 'kids_theme'), 
            	  'desc' =>    __('Main Gallery Subtitle.', 'kids_theme'),
            	  'default'     =>'off',
            	  'value'       =>null),
            
            array('id'          =>'mls_sc_title_switch', 
            	  'type'        =>'checkbox', 
            	  'name'        =>__('Show title', 'kids_theme'), 
            	  'desc' =>    __('Display picture title', 'kids_theme'),
            	  'default'     => 'on',
            	  'value'       => null),
            
            array('id'          =>'mls_sc_subtitle_switch', 
            	  'type'        =>'checkbox', 
            	  'name'        =>__('Show subtitle', 'kids_theme'), 
            	  'desc'        =>__('Image Subtitle. This data is visible only with 2 column option', 'kids_theme'),
            	  'default'     => 'on',
            	  'value'       => null),
            
             array('id'          =>'mls_sc_desc_switch', 
            	  'type'        =>'checkbox', 
            	  'name'        =>__('Show picture description', 'kids_theme'), 
            	  'desc'        =>__('Available only with 2 column option', 'kids_theme'),
            	  'default'     => 'on',
            	  'value'       => null)
        );
        
        
        $this->options['mls_yt'] = array(
                array('id'          =>'yt_url', 
                	  'type'        =>'text', 
                	  'name'        => __('Youtube URL', 'kids_theme'), 
                	  'desc'         => __('Full URL to youtube movie clip.', 'kids_theme'),
                	  'default'     => '',
                	  'value'       =>null),
            
                array('id'          =>'yt_width', 
                	  'type'        =>'text', 
                	  'name'        => __('Youtube Movie Width', 'kids_theme'), 
                	  'desc'         => __('Width in pixels or percents.', 'kids_theme'),
                	  'default'     => 480,
                	  'value'       =>null),
                
                array('id'          =>'yt_height', 
                	  'type'        =>'text', 
                	  'name'        => __('Youtube Movie Height', 'kids_theme'), 
                	  'desc'         => __('Height in pixels.', 'kids_theme'),
                	  'default'     => 320,
                	  'value'       =>null)
        );
        
        
        /* iFrame */
        $this->options['mls_iframe'] = array(
        
            array('id'          =>'mls_sc_iframe_url', 
            	  'type'        =>'text', 
            	  'name'        => __('Url', 'kids_theme'), 
            	  'desc'         => __('Enter Full Url (ex. http://www.example.com)', 'kids_theme'),
            	  'default'     => 'http://',
            	  'value'       => null),
            
             array('id'         =>'mls_sc_scrolling', 
            	  'type'        =>'checkbox', 
            	  'name'        =>__('Scrolling', 'kids_theme'), 
            	  'desc'		 => __('Enable scrollbars', 'kids_theme'),
            	  'default'     =>'on',
            	  'value'       =>null),
         
            array('id'          =>'mls_sc_width', 
            	  'type'        =>'text', 
            	  'name'        => __('Iframe Width', 'kids_theme'), 
            	  'desc'         => __('Width in pixels or percents.', 'kids_theme'),
            	  'default'     => '100%',
            	  'value'       =>null),
            
            array('id'          =>'mls_sc_height', 
            	  'type'        =>'text', 
            	  'name'        => __('Iframe Height', 'kids_theme'), 
            	  'desc'         => __('Height in pixels.', 'kids_theme'),
            	  'default'     => 500,
            	  'value'       =>null),
            
            array('id'          =>'mls_sc_frameborder', 
            	  'type'        =>'text', 
            	  'name'        =>__('Border Width', 'kids_theme'), 
            	  'desc'        =>__('To disable border set Thickness to 0.', 'kids_theme'),
            	  'default'     => 0,
            	  'value'       => null),
            
            array('id'          =>'mls_sc_marginheight', 
            	  'type'        =>'text', 
            	  'name'        => __('Margin Height', 'kids_theme'), 
            	  'desc'         => __('Set margin for bottom of an Iframe.(Ex. 33px)', 'kids_theme'),
            	  'default'     => '40px',
            	  'value'       => null),
        );
        
        /* Message */
         $this->options['mls_msg'] = array(
        
            array('id'          =>'mls_sc_msg', 
            	  'type'        =>'select', 
            	  'name'        =>__('Message type', 'kids_theme'), 
            	  'desc'        =>     __(' ', 'kids_theme'),
            	  'default'     => 'info',
                  'values'		=> array('success'=>'Success', 'info'=>'Info', 'warning'=>'Warning', 'error'=>'Error'),
            	  'value'       => null)
            );
    }
} // Class End

$obj = ShortcodeButton::getInstance()->init();