<?php
class KidsCustomHtmlWidget extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget-what-we-do', 'description' => __('Kids - Arbitrary text or HTML','kids_theme'));
		$control_ops = array('width' => 300, 'height' => 350);
		parent::__construct('kids_text', __('Kids - HTML / Text','kids_theme'), $widget_ops, $control_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title    = apply_filters( 'widget_title', !isset($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
		$subtitle = apply_filters( 'widget_title', !isset($instance['subtitle']) ? '' : $instance['subtitle'], $instance, $this->id_base);
		$image    = esc_attr($instance['image']); 
		$url    = !isset($instance['url']) ? '#' : $instance['url']; 
		
		$text = apply_filters( 'widget_text', $instance['text'], $instance );
		echo $before_widget;
		if ( !empty( $title ) ) { echo $before_title . $title . $after_title; } 
		if ( !empty( $subtitle ) ) { echo '<h4>' . $subtitle . '</h4>'; } ?>
        <?php if(isset($image)): ?>
        <div class="mask">
            <a href="<?php echo esc_url($url); ?>" title="">
                <span class="middle-frame-mask"></span>
                <img width="293" title="<?php echo esc_attr($title); ?>" src="<?php echo esc_attr($image); ?>">
            </a>    
        </div>
        <?php endif; ?>
			<p><?php echo $instance['filter'] ? wpautop($text) : $text; ?></p>
            <?php if($url != '#') :?>
            <a href="<?php echo esc_url($url); ?>" class="read-more"><?php _e('Read More', 'kids_theme'); ?><span class="circle-arrow"></span></a>
		    <?php endif; ?>
        <?php
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['subtitle'] = strip_tags($new_instance['subtitle']);
		$instance['image'] = strip_tags($new_instance['image']);
		$instance['url'] = strip_tags($new_instance['url']);
		
		if ( current_user_can('unfiltered_html') )
			$instance['text'] =  $new_instance['text'];
		else
			$instance['text'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text']) ) ); // wp_filter_post_kses() expects slashed
		$instance['filter'] = isset($new_instance['filter']);
		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '','subtitle' => '','image' => '', 'text' => '', 'url'=>'' ) );
		$title = strip_tags($instance['title']);
		$subtitle = strip_tags($instance['subtitle']);
		$image = strip_tags($instance['image']);
		$url   = strip_tags($instance['url']);
		$text = esc_textarea($instance['text']);
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

        <p><label for="<?php echo $this->get_field_id('subtitle'); ?>"><?php _e('Sub Title:', 'kids_theme'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('subtitle'); ?>" name="<?php echo $this->get_field_name('subtitle'); ?>" type="text" value="<?php echo esc_attr($subtitle); ?>" /></p>
        
        <p><label for="<?php echo $this->get_field_id('image'); ?>"><?php _e('Image:'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('image'); ?>" type="text" value="<?php echo esc_attr($image); ?>" /></p>
        
        <p><label for="<?php echo $this->get_field_id('url'); ?>"><?php _e('Url:'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" type="text" value="<?php echo esc_attr($url); ?>" /></p>
        
		<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>

		<p><input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox" <?php checked(isset($instance['filter']) ? $instance['filter'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('filter'); ?>"><?php _e('Automatically add paragraphs'); ?></label></p>
<?php
	}
}

register_widget('KidsCustomHtmlWidget');