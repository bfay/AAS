<?php
class KidsCategoriesWidget extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'widget_categories', 'description' => __( "Kids - A list or dropdown of categories" ) );
		parent::__construct('kids_categories', __('Kids Categories', 'kids_theme'), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters('widget_title', empty( $instance['title'] ) ? __( 'Categories','kids_theme' ) : $instance['title'], $instance, $this->id_base);
		$subtitle = $instance['subtitle'];
		
		$c = $instance['count'] ? '1' : '0';
		$h = $instance['hierarchical'] ? '1' : '0';

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
?>
        <h4><?php echo esc_attr($subtitle); ?></h4>
		<ul class="bullets">
<?php
        $cat_args = array('orderby' => 'name', 'show_count' => $c, 'hierarchical' => $h);
		$cat_args['title_li'] = '';
		wp_list_categories(apply_filters('widget_categories_args', $cat_args));
?>
		</ul>
<?php
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['subtitle'] = strip_tags($new_instance['subtitle']);
		$instance['count'] = !empty($new_instance['count']) ? 1 : 0;
		$instance['hierarchical'] = !empty($new_instance['hierarchical']) ? 1 : 0;
		$instance['dropdown'] = !empty($new_instance['dropdown']) ? 1 : 0;

		return $instance;
	}

	function form( $instance ) {
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'subtitle'=>'') );
		$title = esc_attr( $instance['title'] );
		$subtitle = esc_attr( $instance['subtitle']);
		$count = isset($instance['count']) ? (bool) $instance['count'] :false;
		$hierarchical = isset( $instance['hierarchical'] ) ? (bool) $instance['hierarchical'] : false;
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

        <p><label for="<?php echo $this->get_field_id('subtitle'); ?>"><?php _e( 'Sub Title:', 'kids_theme' ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('subtitle'); ?>" name="<?php echo $this->get_field_name('subtitle'); ?>" type="text" value="<?php echo $subtitle; ?>" /></p>
        

		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>"<?php checked( $count ); ?> />
		<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e( 'Show post counts' ); ?></label><br />

		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('hierarchical'); ?>" name="<?php echo $this->get_field_name('hierarchical'); ?>"<?php checked( $hierarchical ); ?> />
		<label for="<?php echo $this->get_field_id('hierarchical'); ?>"><?php _e( 'Show hierarchy' ); ?></label></p>
<?php
	}
}

register_widget('KidsCategoriesWidget');