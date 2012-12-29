<?php
/**
 *  Select category for upcoming events
 */
class KidsContactFormWidget extends WP_Widget {
    
    /**
     * Constructor
     */
    public function __construct() {
		
        $widget_ops  = array('classname'   => 'widget-contact', 
        					 'description' => __( 'Kids - Contact Form', 'kids_theme'));
        
		parent::__construct(
	 		'kids_contact_form', 
	 		'Kids - Contact Form Widget',
		    $widget_ops
		);
	}

	/**
	 * Outputs the options form on admin
	 * @see WP_Widget::form()
	 * @param $instance current settings
	 */
 	public function form( $instance ) {
 	     	    
 	    //Get Posts from first category (current one)
 	    $default = 	array( 
 	    				   'title'   => __('Contact Us', ThemeSetup::HOOK .'_theme'),
                    	  );
                    	  
        $instance = wp_parse_args((array) $instance, $default);    
		 ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title', ThemeSetup::HOOK.'_theme'); ?></label><br />
            <input class="widefat" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" value="<?php echo esc_attr($instance['title']); ?>" />
        </p>
        
        <?php 
	}

	
	/**
	 * processes widget options to be saved
	 * @see WP_Widget::update()
	 */
	public function update( $new_instance, $old_instance ) {
	    
	    $instance = array();
	    if(empty($old_instance)){
	        $old_instance = $new_instance;
	    }
	    
	    if($new_instance['num'] > 8) $new_instance['num'] = 8;
	    
	    foreach ($old_instance as $k => $value) {
	        $instance[$k] = trim(strip_tags($new_instance[$k]));
	    }
        return $instance;
	}

	
	/**
	 * Front-end display of widget.
	 * @see WP_Widget::widget()
	 * @param array $args Display arguments including before_title, after_title, before_widget, and after_widget.
	 * @param array $instance The settings for the particular instance of the widget
	 * 
	 * 		address, phone, fax, email
	 */
	public function widget( $args, $instance ) {
	    global $wpdb;
	    extract( $args );
        
	   	$title    = empty($instance['title'])   ? '' : apply_filters('widget_title', $instance['title']);
		echo $before_widget;
		?>
        <?php if($title): echo $before_title . $title . $after_title; endif; ?>
        
        <?php 
            echo $this->renderForm($args, $instance);
		    echo $after_widget;
	}
	
	
	protected function renderForm($args, $instance){
	?>
	                        
        <form enctype="multipart/form-data" method="post" id="contact-form-footer"> 
        <div class="send-form"> 
              <p class="contact-modal-box">Fill in all fields.</p>  
              <p>
              <label>*<?php _e('Your name', ThemeSetup::HOOK . '_theme') ?>:</label>
              <input class="u-3" name="name" id="name1" />
              </p>
              <p>
              <label>*<?php _e('Your E-mail', ThemeSetup::HOOK . '_theme') ?>:</label>
              <input class="u-3" name="email" id="email1" />
              </p>
              <p>
              <label>*<?php _e('Your Message', ThemeSetup::HOOK . '_theme') ?>:</label>
              <textarea class="u-3" name="message" id="message1" cols="80" rows="3"></textarea>
              </p>
              <p>
              <input type="hidden" name="from_widget" value="1" />
              <a class="button-submit contact-button">Contact Us<span class="circle-arrow"></span></a>
              </p>
         </div>
         </form> 
    <?php            
	}
	
	protected function sendMail(){//By ajax.... common.php
	}
} //Class End

register_widget('KidsContactFormWidget');