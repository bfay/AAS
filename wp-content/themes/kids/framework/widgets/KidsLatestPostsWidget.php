<?php
/**
 *  Select category for upcoming events
 */

class KidsLatestPostsWidget extends WP_Widget {
    
    /**
     * Constructor
     */
    public function __construct() {
		
        $widget_ops  = array('classname'   => 'widget-latest-posts', 
        					 'description' => __( 'Kids - Latest Posts (for footer section)', 'kids_theme'));
        
		parent::__construct(
	 		'kids_latest_posts', 
	 		'Kids - Latest Posts Widget',
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
 	    				   'title'   => __('Latest Posts', ThemeSetup::HOOK .'_theme'),
		                   'num' => 8
                    	  );
                    	  
        $instance = wp_parse_args((array) $instance, $default);    
		 ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title', ThemeSetup::HOOK.'_theme'); ?></label><br />
            <input class="widefat" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" value="<?php echo esc_attr($instance['title']); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('num'); ?>"><?php _e('Number of posts (Max. 8 posts)', ThemeSetup::HOOK.'_theme'); ?></label><br />
            <input name="<?php echo $this->get_field_name('num'); ?>" id="<?php echo $this->get_field_id('num'); ?>" value="<?php echo esc_attr($instance['num']); ?>" />
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
	    //Get two leatest posts from upcoming Events Category 
	    	    
	     $querystr = "SELECT $wpdb->posts.post_title, $wpdb->posts.post_date, $wpdb->posts.ID
                      FROM $wpdb->posts
                      WHERE $wpdb->posts.post_status = 'publish' 
                            AND $wpdb->posts.post_type = 'post'
                      ORDER BY $wpdb->posts.post_date DESC
                      LIMIT " . $instance['num'];
	     
         $posts = $wpdb->get_results($querystr, OBJECT);
	    
		$title    = empty($instance['title'])   ? '' : apply_filters('widget_title', $instance['title']);
		echo $before_widget;
		?>
            <?php if($title): echo $before_title . $title . $after_title; endif; ?>
            <ul>
            <?php foreach ($posts as $post): ?>
                 <li><h5><a title="<?php echo strtoupper($post->post_title); ?>" href="<?php echo get_permalink($post->ID); ?>"><?php echo strtoupper($post->post_title); ?></a></h5></li>
            <?php endforeach; ?>     
            </ul>
		<?php
		echo $after_widget;
	}
} //Class End

register_widget('KidsLatestPostsWidget');
