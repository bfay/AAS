<?php
/**
 * 
 * 
 */

class KidsWorkingHoursWidget extends WP_Widget {
    
    /**
     * Constructor
     */
    public function __construct() {
		
        $widget_ops  = array('classname' => 'widget-working-hours', 
        					 'description' => __( 'Working Hours Box', 'kids_theme'));
        
		parent::__construct(
	 		'working_hours', 
	 		'Kids Working Hours Widget',
		    $widget_ops
		);
	}

	/**
	 * Outputs the options form on admin
	 * @see WP_Widget::form()
	 * @param $instance current settings
	 */
 	public function form( $instance ) {
 	  
		$default = 	array( 'title'     => __('Working Hours Box Title', ThemeSetup::HOOK .'_theme'),
		                   'hours_msg' => __('7 DAYS A WEEK', ThemeSetup::HOOK .'_theme'),
                    	   'hours'     => '10:00 am - 1:30 pm',
                    	   'tel_msg'   => __('CONTACT PHONE', ThemeSetup::HOOK .'_theme'),
                    	   'tel'       => '+123 456789-123');
		
		$instance = wp_parse_args((array) $instance, $default);
	
		 ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Box Title</label><br />
            <input class="widefat" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" value="<?php echo esc_attr($instance['title']); ?>" />
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('hours_msg'); ?>">Hours Text</label><br />
            <input class="widefat" name="<?php echo $this->get_field_name('hours_msg'); ?>" id="<?php echo $this->get_field_id('hours_msg'); ?>" value="<?php echo esc_attr($instance['hours_msg']); ?>" />
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('hours'); ?>">Working Hours</label><br />
            <input class="widefat" name="<?php echo $this->get_field_name('hours'); ?>" id="<?php echo $this->get_field_id('hours'); ?>" value="<?php echo esc_attr($instance['hours']); ?>" />
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('tel_msg'); ?>">Phone Text</label><br />
            <input class="widefat" name="<?php echo $this->get_field_name('tel_msg'); ?>" id="<?php echo $this->get_field_id('tel_msg'); ?>" value="<?php echo esc_attr($instance['tel_msg']); ?>" />
        </p>
        
         <p>
            <label for="<?php echo $this->get_field_id('tel'); ?>">Phone No.</label><br />
            <input class="widefat" name="<?php echo $this->get_field_name('tel'); ?>" id="<?php echo $this->get_field_id('tel'); ?>" value="<?php echo esc_attr($instance['tel']); ?>" />
        </p>
        
        
        <?php 
	}

	
	/**
	 * processes widget options to be saved
	 * @see WP_Widget::update()
	 */
	public function update( $new_instance, $old_instance ) {

	    if(empty($old_instance)){
	        $old_instance = $new_instance;
	    }
	    $instance = $old_instance;
	    
	    foreach ($instance as $k => $value) {
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
		
		$title     = empty($instance['title'])   ? '' : apply_filters('widget_title', $instance['title']);
		$hours_msg = empty($instance['hours_msg']) ? '' : apply_filters('widget_title', $instance['hours_msg']);
		$hours     = empty($instance['hours'])     ? '' : apply_filters('widget_title', $instance['hours']);
		$tel_msg   = empty($instance['tel_msg'])   ? '' : apply_filters('widget_title', $instance['tel_msg']);
		$tel       = empty($instance['tel'])   ? '' : apply_filters('widget_title', $instance['tel']);
		
		echo $before_widget;
		?>
            <?php if($title): echo $before_title . $title . $after_title; endif; ?>
            <ul>
                <?php if($hours_msg && $hours): ?>
                <li class="clock"><span><?php echo esc_attr(strtoupper($hours_msg)); ?></span><br><strong><?php echo esc_attr(strtoupper($hours)); ?></strong></li>
                <?php endif; ?>
                <li><span class="devider"></span></li>
                <?php if($tel_msg && $tel): ?>
                <li class="phone"><span><?php echo esc_attr(strtoupper($tel_msg)); ?></span><br><strong><?php echo esc_attr(strtoupper($tel)); ?></strong></li>
                <?php endif; ?>
            </ul>
		<?php
		echo $after_widget;
	}
} //Class End

register_widget('KidsWorkingHoursWidget');
