<?php
/**
 * 
 * 
 */

class KidsContactInfoWidget extends WP_Widget {
    
    /**
     * Constructor
     */
    public function __construct() {
		
        $widget_ops  = array('classname' => 'widget-address', 
        					 'description' => __( 'Contact Info', 'kids_theme'));
        
		parent::__construct(
	 		'contact_info', 
	 		'Kids Contact Info Widget',
		    $widget_ops
		);
	}

	/**
	 * Outputs the options form on admin
	 * @see WP_Widget::form()
	 * @param $instance current settings
	 */
 	public function form( $instance ) {
 	  
 	    $fields_of_intereset  = array('address', 'tel', 'fax', 'email');
 	    $themeObj = ThemeSetup::getInstance();
 	    $data = array();
 	    
 	    if(isset($themeObj)){ //Data Obj Present
 	        $data = $themeObj->getThemeDataObj()->getSetting('main_settings.data.items.mi_contact');
 	        $datax = array();
 	        
 	        foreach ($data as $v){ // By Fields
 	            if(in_array($v['id'], $fields_of_intereset)){
     	              $datax[$v['id']]    = array('value'=>$v['value'], 'title'=> $v['name']); 
     	              if(!empty($instance))
                          $instance[$v['id']] = array('value'=>$instance[$v['id']], 'title'=> $v['name']);    
 	            }     
 	        }
 	        
 	         if(isset($instance['title'])) 
                  $instance['title'] = array('value'=>$instance['title'], 'title'=>  __('Address Box Title', ThemeSetup::HOOK .'_theme'));   
 	        
 	    }
 	    
		$default = 	array( 'title'    => array('title' => __('Address Box Title', ThemeSetup::HOOK .'_theme'), 'value'=>__('Address', ThemeSetup::HOOK .'_theme')),
		                   'address' => $datax['address'],
                    	   'fax'     => $datax['fax'],
                    	   'tel'     => $datax['tel'],
                    	   'email'   => $datax['email']);
		
		$instance = wp_parse_args((array) $instance, $default);
	
		 ?>
		
        <?php foreach ($instance as $k=>$v) : ?>
        <p>
                <label for="<?php echo $this->get_field_id($k); ?>"><?php echo $instance[$k]['title'] ?></label><br />
                <input class="widefat" name="<?php echo $this->get_field_name($k); ?>" id="<?php echo $this->get_field_id($k); ?>" value="<?php echo esc_attr($instance[$k]['value']); ?>" />
        </p>   
	    <?php endforeach; ?>
        
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

	    extract( $args );
		
		$title   = empty($instance['title'])   ? '' : apply_filters('widget_title', $instance['title']);
		$address = empty($instance['address']) ? '' : apply_filters('widget_title', $instance['address']);
		$fax     = empty($instance['fax'])     ? '' : apply_filters('widget_title', $instance['fax']);
		$tel   = empty($instance['tel'])   ? '' : apply_filters('widget_title', $instance['tel']);
		$email   = empty($instance['email'])   ? '' : apply_filters('widget_title', $instance['email']);
		
		echo $before_widget;
		?>
            <?php if($title): echo $before_title . $title . $after_title; endif; ?>
            <ul>
            <?php if($address !='') :?>
                <li>
                    <p class="meta"><?php _e('Main office', ThemeSetup::HOOK . "_theme"); ?>: </p>
                    <p><?php echo esc_attr($address);?></p>
                </li> 
            <?php endif; ?>  
            <?php if($tel !='') :?>     
                <li>
                    <p class="meta"><?php _e('Phone', ThemeSetup::HOOK . "_theme"); ?>: </p>
                    <p><?php echo esc_attr($tel);?></p>
                </li>  
             <?php endif; ?>      
             <?php if($fax !='') :?>       
                <li>
                    <p class="meta"><?php _e('Fax', ThemeSetup::HOOK . "_theme"); ?>: </p>
                    <p><?php echo esc_attr($fax);?></p>
                </li>  
             <?php endif; ?>      
            <?php if($email !='') :?>      
                <li>    
                    <p class="meta"><?php _e('E-mail', ThemeSetup::HOOK . "_theme"); ?>: </p>
                    <p><a href="mailto:<?php echo esc_attr($email);?>"><?php echo esc_attr($email);?></a></p>
                </li>
            <?php endif; ?>       
            </ul>
		<?php
		echo $after_widget;
	}
} //Class End

register_widget('KidsContactInfoWidget');
