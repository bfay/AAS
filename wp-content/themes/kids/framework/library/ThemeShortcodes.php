<?php
/**
 * 
 * Theme Shortcodes
 * @author milos
 *
 */
class ThemeShortCodes {
    
   public static $_instance = null; 
    
   private function __construct(){}
   private function __clone(){}
   
    public static function getInstance()
	{
		if (null === self::$_instance)
		{
			self::$_instance = new ThemeShortCodes();
		}
		return self::$_instance;
	}
    
   
   public function init(){
       
      //Remove default WP gallery shortcode 
      remove_shortcode('[gallery]'); 
      
      add_shortcode('raw' , array(&$this, 'themeRaw'));
      add_shortcode('mls_mark' , array(&$this, 'themeMarkText'));
      add_shortcode('mls_blockquote' , array(&$this, 'themeBlockQuote'));
      add_shortcode('mls_quoteleft'  , array(&$this, 'themeQuoteLeft'));
      add_shortcode('mls_quoteright' , array(&$this, 'themeQuoteRight'));
      
      add_shortcode('mls_iframe', array(&$this, 'themeIframe'));
      add_shortcode('mls_yt', array(&$this, 'themeYoutube'));
      add_shortcode('mls_vimeo', array(&$this, 'themeVimeo'));
      add_shortcode('mls_msg'   , array(&$this, 'ThemeMessageShortcode'));
       
      add_shortcode('mls_h1' , array(&$this, 'themeH1'));
      add_shortcode('mls_h2' , array(&$this, 'themeH2'));
      add_shortcode('mls_h3' , array(&$this, 'themeH3'));
      add_shortcode('mls_h4' , array(&$this, 'themeH4'));
      add_shortcode('mls_h5' , array(&$this, 'themeH5'));
      
      /* Todo */
      //add_shortcode('mls_center', array(&$this, 'themeAlignCenter'));
      //add_shortcode('mls_tabele', array(&$this, 'themeTable'));
      
      add_shortcode('mls_bullet_list', array(&$this, 'themeList1'));
      add_shortcode('mls_star_list'  , array(&$this, 'themeList2'));
      add_shortcode('mls_arrow_list' , array(&$this, 'themeList3'));
      add_shortcode('mls_ok_list'    , array(&$this, 'themeList4')); 
      add_shortcode('mls_o_list'     , array(&$this, 'themeOrdered1')); 
       
      add_shortcode('mls_gallery'    , array(&$this, 'themeGallery'));

      /* Layout shortcodes */
      add_shortcode('mls_onethird'      , array(&$this, 'themeOneThird'));
      add_shortcode('mls_onehalf'       , array(&$this, 'themeOneHalf'));
      add_shortcode('mls_twothird'       , array(&$this, 'themeTwoThird'));
      
      add_shortcode('mls_devider', array($this, 'themeDevider'));
      
      add_shortcode('mls_sitemap', array($this, 'themeSiteMap'));
      
      add_filter('the_content', array(&$this, 'themeFormaterShortcode'), 99);
      
      
		
      return $this;
   }
   
   protected function shortcodeHead(){
       ?>
       <html>
            <head>
                <script type="text/javascript" src="<?php echo get_bloginfo('wpurl') . '/wp-includes/js/jquery/jquery.js' ?>"></script>
                <script type="text/javascript" src="<?php echo KIDS_ADMIN_URI . '/js/shortcodefunctions.js' ?>"></script>
                <script type="text/javascript" src="<?php echo KIDS_ADMIN_URI . '/bootstrap/js/bootstrap.js' ?>"></script>
                <link rel="stylesheet" type="text/css" href="<?php echo KIDS_ADMIN_URI . '/css/admin-style.css' ?>" />
                <link rel="stylesheet" type="text/css" href="<?php echo KIDS_ADMIN_URI . '/bootstrap/css/bootstrap.css' ?>" />
                <link rel="stylesheet" type="text/css" href="<?php echo KIDS_ADMIN_URI . '/bootstrap/css/bootstrap-responsive.css' ?>" />
            </head>
            <body>
       <?php 
   }
   
   protected function shortcodeFooter(){
      echo '</body></html>'; 
   }
   
   /**
    * Generate UI options for Shortcodes
    */
   public function ajaxShortcode(){
       
       AdminSetup::getInstance()->enforce_restricted_access();
       
       $shortcode = sanitize_text_field(trim($_GET['sc']));
       $selectedContent = isset($_GET['selectedContent'])? trim($_GET['selectedContent']) : '';
       
       $this->shortcodeHead();
     
       /* On button click Call JS shortcode logic to format and insert staff in to the psot */
       $response = '';
       $response .= '<div id="theme-panel-wrapper" style="background-image:none;">';
       $response .= '<form method="post" class="form-horizontal">';
       $response .= '<div class="control-group theme-section well-no-shadow">';
       $response .= '<label class="control-label">%s<br></label>';
       $response .= '</div>';
       $response .= '<script type="text/javascript">
       					
       					jQuery("body").on("click", "a.mls_send_to_editor",{"selectedContent":"'.$selectedContent.'"}, '.$shortcode.');
                    	var win = window.dialogArguments || opener || parent || top;
       				 </script>';
       
       $o = ShortcodeButton::getInstance(); 
       $fields = $o->getConf($shortcode);
       $response = sprintf($response, 'Shortcode Options');
       $ta = ThemeAdmin::getInstance()->init();
       
       foreach ($fields as $f) {
           $response .= $ta->parseField($f);
       }
       
       $response .= ' <div class="control-group dinamic-field"><a class="btn mls_send_to_editor" href="#">Insert</a></div>';
       $response .= '</form></div>';
       echo $response;
       $this->shortcodeFooter();  
       exit;    
   }
   
   
   /* Simple Shortcodes */
   
   public function themeH1($atts, $content){
       return '<h1>' . do_shortcode($content) .'</h1>';
   }
   
   public function themeH2($atts, $content){
       return '<h2>' . do_shortcode($content) .'</h2>';
   }
   
   public function themeH3($atts, $content){
       return '<h3>' . do_shortcode($content) .'</h3>';
   }
   
   public function themeH4($atts, $content){
       return '<h4>' . do_shortcode($content) .'</h4>';
   }
   
   public function themeH5($atts, $content){
       return '<h5>' . do_shortcode($content) .'</h5>';
   }
   
   public function themeAlignCenter($atts, $content){
       return $content;
   }
   
   public function themeDevider($atts, $content){
       return '<div class="example"></div>';
   }
   
   /* Layout Shortcodes */
   
   public function themeOneHalf($atts, $content){
        extract(shortcode_atts(array(
            'first'       => 'no',
            ), $atts));
            
            if($first == 'no'){
                return '<div class="cs-6">'.do_shortcode($content).'</div>';
            }
            else{
                return '<div class="cs-6 first">'.do_shortcode($content).'</div>';
            }
   }
   
    public function themeOneThird($atts, $content){
        extract(shortcode_atts(array(
            'first'       => 'no',
            ), $atts));
            
            if($first == 'no'){
                return '<div class="cs-4">'.do_shortcode($content).'</div>';
            }
            else{
                return '<div class="cs-4 first">'.do_shortcode($content).'</div>';
            }
   }
   
    public function themeTwoThird($atts, $content){
        extract(shortcode_atts(array(
            'first'       => 'no',
            ), $atts));
            
            if($first == 'no'){
                return '<div class="cs-8">'.do_shortcode($content).'</div>';
            }
            else{
                return '<div class="cs-8 first">'.do_shortcode($content).'</div>';
            }
   }
   
   
   public function themeSiteMap($atts){
           extract(shortcode_atts(array(
            'ignore_cats'    => '',
            'ignore_post'	 => '',
            'ignore_pages'	 => '',
            'ignore_tags'	 => '',
            'hide_pages'	 => false,
            'hide_cats'	     => false,
            'hide_posts'	 => false,
            'hide_tags'	     => false,
            ), $atts));
            
            ob_start();
            
            /* Pages */
            
            if(!$hide_pages){
            
                  $args = array(
                        	'show_date'    => 'modified',
                        	'date_format'  => get_option('date_format'),
                        	'child_of'     => 0,
                        	'exclude'      => '',
                        	'title_li'     => '',
                        	'echo'         => 0,
                        	'sort_column'  => 'menu_order, post_title',
                            'post_status'  => 'publish');
                  
                  $pages = wp_list_pages($args);
                  $pages = '<ul class="stars">' . $pages . '</ul>';
            }
            
            /* Categories */
            
            if(!$hide_cats){
            
            }
            
            /* Posts */
            
            if(!$hide_posts){}
            
            /* Tags */
            
           if(!$hide_tags){}
            
            
            echo $pages;            
            
            
            $output = ob_get_contents();;
            ob_end_clean();
            
            return $output;
   }
/*   
   public function theme($atts, $content){
       return;
   }
 */  
   
   /* Lists */
   
    public function themeList1( $atts, $content = null ) {
         return '<ul class="bullets">' . do_shortcode($content) . '</ul>';
    }
    
    public function themeList2( $atts, $content = null ) {
         return '<ul class="stars">' . do_shortcode($content) . '</ul>';
    }
    
    public function themeList3( $atts, $content = null ) {
         return '<ul class="arrows">' . do_shortcode($content) . '</ul>';
    }

    public function themeList4( $atts, $content = null ) {
         return '<ul class="checklist">' . do_shortcode($content) . '</ul>';
    }
    
    public function themeOrdered1( $atts, $content = null ) {
         return '<ol class="list">' . do_shortcode($content) . '</ol>';
    }
   
   /* Tables */
    
   public function themeTable($atts, $content){
       return $content;
   }
   
   public function themeBlockQuote($atts, $content){
       return '<blockquote>' . $content . '</blockquote>';
   }
   
   public function themeQuoteLeft($atts, $content){
       $content = '<span class="pullquote left">' . do_shortcode($content) . '</span>';
       return $content;
   }
   
   public function themeQuoteRight($atts, $content){
       $content = '<span class="pullquote right">' . do_shortcode($content) . '</span>';
       return $content;
   }
   
   
   
   /* 
    * @todo Add color classes in styles file */
   public function themeMarkText($atts,$content){
       return '<span class="mark">' . $content . '</span>';
   } 
   
   
   public function themeIframe($atts) {
          extract(shortcode_atts(array(
            'url'       => 'http://',
            'scrolling' => 'auto',
            'width'     => '100%',
            'height'    => '500',
            'frameborder'  => '0',
            'marginheight' => '0'
            ), $atts));
  
      return '<iframe src="'.$url.'" scrolling="'.$scrolling.'" width="'.$width.'" height="'.$height.'" frameborder="'.$frameborder.'" marginheight="'.$marginheight.'"></iframe>';
    }
    
    
    public function ThemeMessageShortcode($atts, $content){
         extract( shortcode_atts( array(
          'type'    => 'info',
       ), $atts ) );
       
       switch ($type){
           case 'success' :
               $content = '<p class="message success">' . do_shortcode($content) . '</p>';
                   break;
           case 'info' :
               $content = '<p class="message info">' . do_shortcode($content) . '</p>';
                   break;
           case 'warning' :
               $content = '<p class="message warning">' . do_shortcode($content) . '</p>';
                   break;
           case 'error' :
               $content = '<p class="message error">' . do_shortcode($content) . '</p>';
                   break;        
       }
       return $content;
    }

    public function themeRaw($atts, $content){
        return $content;    
    }
   
    /* In case we envelope content into a [raw] tag, no filters applied*/
   public function themeFormaterShortcode($content){
        $new_content = '';
    	$pattern_full = '/(\[raw\].*?\[\/raw\])/is';
    	$pattern_contents = '/\[raw\](.*?)\[\/raw\]/is';
    	$pieces = preg_split($pattern_full, $content, -1, PREG_SPLIT_DELIM_CAPTURE);
    	
    	foreach ($pieces as $piece) {
    		if (preg_match($pattern_contents, $piece, $matches)) {
    			$new_content .= $matches[1];
    		} else {
    			$new_content .= wptexturize(wpautop($piece));
    		}
    	}
	    return $new_content;
   }
   
   /* Insert Youtube Video Clip */
   public function themeYoutube($atts){
   
       extract( shortcode_atts( array(
          'yt_width'   	    => 480,
          'yt_height'       => 320,
          'yt_url'          => ''  
       ), $atts ) );
       
       preg_match('/\/([^\/]+)$/i', $yt_url, $m);
       return '<iframe width="'.$yt_width.'" height="'.$yt_height.'" src="http://www.youtube.com/embed/'.$m[1].'" frameborder="0" allowfullscreen></iframe>';
   }
   
    /* Insert Vimeo Video Clip */
    public function themeVimeo($atts){
         extract( shortcode_atts( array(
          'vm_width'   	    => 480,
          'vm_height'       => 320,
          'loop'            => '0',  
          'autoplay'        => '0',  
          'frameborder'     => '0',   
          'allowFullScreen' => 1  
       ), $atts ) );
       
       if($allowFullScreen == 1){
           $allowFullScreen = 'webkitAllowFullScreen mozallowfullscreen allowFullScreen';
       }else{
           $allowFullScreen = '';
       }
       return '<iframe src="http://player.vimeo.com/video/'. $id .'?autoplay='.$autoplay.'&amp;loop='.$loop.'" width="'.$width.'" height="'.$height.'" frameborder="'.$frameborder.'" '.$allowFullScreen.' ></iframe>';
   }
   
   
   /* Advanced Shortcodes */
   
   /**
    * Render Gallery
    * @param array $atts
    * 
    * [mls_gallery id=1 title='on' pic_title='off']
    */
   public function themeGallery($atts)
   {
       global $post;
       $content = '';
       extract( shortcode_atts( array(
          'id'   	     => null,
          'cols'       => 4,
          'g_title'    => 'on',   //Whole gallery title 
          'g_subtitle' => 'on',   //Gallery subtitle
          'title'      => 'on',   //Picture title
          'subtitle'   => 'on',   // Picture Subtitle
          'desc'       => 'off'   //Picture description 
       ), $atts ) );
       
       /**
        * In case we are dealing with 3 or more cols title, subtitle and desc are for whole page.
        * In case of 1 or two cols, every particular pic has it
        */
       if($id){
            $gal_post = get_posts( array(
                                    'p'				  => $id,
                                    'post_type'       => 'mlsgallery',
                                    'post_status'     => 'publish' ));
           
           if(!empty($gal_post)) {  
               
               $gal_post = $gal_post[0];   
               $pic_desc = '';
                   
               if($g_title == 'on'){ 
                   $content .= '<h2 class="portfolio-title">'.esc_attr($gal_post->post_title).'</h2>';
               }
               
               $cols = (int) $cols;
    
               if($g_subtitle == 'on'){
                   $content .= '<h4 class="portfolio-title">'.the_subtitle($id).'</h4>';
               }
               
              if($cols > 4 || $cols < 2){ $cols = 4; }
              $images = get_post_meta($id, 'mls_gallery_image_ids', true); 
              
              if(!empty($images)){
                  $content .= '[raw]<ul class="portfolio-menu">';     
    
                   if(!is_page()){ // This is post
                       $cols = 3; // Force 3 column layout for pages with sidebar
                   }
                   
                  foreach ($images as $i => $image) {
                      
                      if($cols == 2){
                          $thumb_src  = wp_get_attachment_image_src( $image['id'], 'blog-middle-thumb');
                          $mask_class = 'gallery-2col-mask';
                          $li_class   = 'c-6 two-column';
                          $headers    = '<h3 class="title"><a>'.(($title == 'on') ? $image['title']:'').'</a></h3>';
                          $headers    .= ' <h4>'.(($subtitle == 'on') ? $image['subtitle']:'').'</h4>';
                          
                          if($desc == 'on'){
                            $pic_desc = (isset($image['text']) && $image['text'] != '') ? '<div class="excerpt"><p>'.$image['text'].'</p></div>' : '';
                          }else $pic_desc = '';
                          $small_title = '';
                      }elseif($cols == 3){
                          $thumb_src  = wp_get_attachment_image_src( $image['id'], 'small-gallery-thumb');
                          $mask_class = 'gallery-3col-mask';
                          $li_class   = 'c-3 three-column';
                          $headers    = $desc = ''; 
                          $small_title = ($title == 'on') ? '<span class="image-caption">'.$image['title'].'</span>' : '';
                      }else{
                          $thumb_src  = wp_get_attachment_image_src( $image['id'], 'theme-gallery-thumb');
                          $mask_class = 'gallery-4col-mask';
                          $li_class   = 'c-3 four-column';
                          $headers    = $desc = ''; 
                          $small_title = ($title == 'on') ? '<span class="image-caption">'.$image['title'].'</span>' : '';
                          
                      }
                      
                      $width  = $thumb_src[1];
                      $height = $thumb_src[2];
                      
                      if(!is_page()){ // This is post
                          $width = '135';
                          $height = '110';
                      }
    
                      $thumb_src_preview = wp_get_attachment_image_src( $image['id'], 'theme-gallery-photo');
                      
        			  $content .=  '<li class="'.$li_class.( (($i%$cols) == 0) ? ' clearfix':'' ).'">
        			  				   '.$headers.'
            			  				<p class="image">
                			  				<a href="'.$thumb_src_preview[0].'" rel="example_group" title="'.$image['title'].'">
                			  					<span class="'.$mask_class.'"></span>
                    			  				<img title="'.$image['title'].'" height="'.$height.'" width="'.$width.'" alt="'.$image['title'].'" src="'.$thumb_src[0].'" rel="'.$thumb_src_preview[0].'" />
                			  				</a>
                			  				'.$small_title.'
            			  				</p>
            			  				'.$pic_desc.'
        			  				</li>';
                  }
        	     $content .= '</ul><div class="clearfix"></div>[/raw]';
              }
            }//Gallery exists
       }//id
       return $content;
   }
} // End of class

ThemeShortCodes::getInstance()->init();