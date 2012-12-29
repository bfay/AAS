<?php
/**
 *  Select category for upcoming events
 */

class KidsEventsWidget extends WP_Widget {
    
    /**
     * Constructor
     */
    public function __construct() {
		
        $widget_ops  = array('classname' => 'widget-upcoming', 
        					 'description' => __( 'Kids - Upcoming Events (for footer section)', 'kids_theme'));
        
		parent::__construct(
	 		'kids_upcoming_events', 
	 		'Kids - Upcoming Events Widget',
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
 	    				   'title'   => __('Upcoming Events', ThemeSetup::HOOK .'_theme'),
		                   'current_cat' => null
                    	  );
                    	  
        $instance = wp_parse_args((array) $instance, $default);    
		 ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title', ThemeSetup::HOOK.'_theme'); ?></label><br />
            <input class="widefat" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" value="<?php echo esc_attr($instance['title']); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('current_cat'); ?>"><?php _e('Category for Upcoming Events', ThemeSetup::HOOK.'_theme'); ?></label><br />
            <?php 
                            
                wp_dropdown_categories(array('selected'=>$instance['current_cat'], 
                                             'name'=> $this->get_field_name('current_cat'),
                                             'id'=> $this->get_field_id('current_cat'),
                                             'class'=>'widefat',
                                             'show_count'=>true,
                                             'hide_empty'=>false,
                                             'orderby'=>'name'));
            ?>                          
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
	    //Get two leatest posts from upcoming Events Category
	    $args = array(
                        'numberposts'     => 2,
                        'category'        => $instance['current_cat'],
                        'orderby'         => 'post_date',
                        'order'           => 'DESC',
	                    'suppress_filters' => false);
	    
	    $posts = get_posts( $args );
		$title    = empty($instance['title'])   ? '' : apply_filters('widget_title', $instance['title']);
		echo $before_widget;
		?>
            <?php if($title): echo $before_title . $title . $after_title; endif; ?>
            <ul>
            <?php foreach ($posts as $post):  
                $post_meta = get_post_custom($post->ID);
            ?>
                 <li>
                    <h5>
                       <span class="date">
                            <span><?php echo $post_meta['_post_month'][0]?></span>
                            <span class="day"><?php echo $post_meta['_post_day'][0] ?></span>
                        </span>
                        <a title="<?php echo $post->post_title; ?>" href="<?php echo get_permalink($post->ID); ?>"><?php echo $post->post_title; ?></a>
                    </h5>
                     <?php $text = apply_filters('widget_text', $post->post_content); ?>
                    <p><?php echo mls_abstract($text, 10); ?><br>
                       <span><?php echo $post_meta['_post_hours'][0]?></span>
                    </p>
                 </li>
            <?php endforeach; ?>     
            </ul>
		<?php
		echo $after_widget;
	}
} //Class End

register_widget('KidsEventsWidget');
