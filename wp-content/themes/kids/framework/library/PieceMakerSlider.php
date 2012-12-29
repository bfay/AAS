<?php
class PieceMakerSlider extends ThemeSlider{ 

    public function __construct($settings = array(), $slides= array()){
        $this->type= 'piece_slider';
        parent::__construct(null, array(), null, $settings, $slides);
        $this->enqueueScripts();
    }
    
    public function enqueueScripts(){
       //  wp_enqueue_script($this->js_handle . '_swf_object', KIDS_URI . '/framework/library/piecemaker/scripts/swfobject/swfobject.js'); 
    }
    
    public function _renderSlider(){
        $output = '';
         $output .= ' 
         <div id="piecemaker">
			<p>You need to <a href="http://www.adobe.com/products/flashplayer/" target="_blank">upgrade your Flash Player</a> to version 10 or newer.</p>
		 </div><!-- end flashcontent -->
         <script type="text/javascript">
              var flashvars = {};
              flashvars.cssSource = "'. get_bloginfo('template_url')  .'/framework/library/piecemaker/piecemaker-css.php?x='.rand(1000, 12000) .'";
              flashvars.xmlSource = "'.  get_bloginfo('template_url')  .'/framework/library/piecemaker/piecemaker-xml.php?x='.rand(1000, 12000).'";
              var params = {};
              params.play = "true";
              params.menu = "false";
              params.scale = "showall";
              params.wmode = "transparent";
              params.allowfullscreen = "false";
              params.allowscriptaccess = "always";
              params.allownetworking = "all";
        	  
              swfobject.embedSWF("'. get_bloginfo('template_url').'/framework/library/piecemaker/piecemaker.swf", "piecemaker", "1250", "360", "10", null, flashvars, params, null);
        </script><div id="slider-bg"></div>';
        return $output;
    }
}//Piece slider end