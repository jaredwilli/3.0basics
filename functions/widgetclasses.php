<?php
/**
 * Widget classes
 */


/**
 * Popular Posts widget
 */
class bb_widget_popularPosts extends WP_Widget {
	function bb_widget_popularPosts() {
		parent::WP_Widget(false, 'Popular Posts');
	}
	function widget($args, $instance) {
		$args['title'] = $instance['title'];
		bb_popularPosts($args);
	}
	function update($new_instance, $old_instance) {
		return $new_instance;
	}
	function form($instance) {	
		$title = esc_attr($instance['title']); ?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
<?php
	}
}


/**
 * Related posts widget
 */
class bb_widget_relatedPosts extends WP_Widget {
	function bb_widget_relatedPosts() {
		parent::WP_Widget(false, 'Related Posts (by tag)');
	}
	function widget($args, $instance) {
		$args['title'] = $instance['title'];
		bb_relatedPosts ($args);
	}
	function update($new_instance, $old_instance) {
		return $new_instance;
	}
	function form($instance) {
		$title = esc_attr($instance['title']); ?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', BB_BASE ); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
<?php
	}	
}

/**
 * 
 */
class bb_widget_twitter extends WP_Widget {
	function bb_widget_twitter() {
		parent::WP_Widget(false, 'Twitter updates');
	}
	function widget($args, $instance) {
		$args['username'] = $instance['username'];
		if ($args['username'] != '') {
			$args['title'] = $instance['title'] . ' [ <a href="http://twitter.com/' . $instance['username'] . '" alt="Tweets for ' . $instance['username'] . '">&rsaquo;</a> ]';
			$args['count'] = $instance['count'];			
			bb_twitter ($args);
		}
	}
	function update($new_instance, $old_instance) {
		$new_instance['count'] = (int) attribute_escape($new_instance['count']);
		if ($new_instance['count'] < 0 || $new_instance['count'] == '') {
			$new_instance['count'] = 1;
		}
		if ($new_instance['count'] > 10) {
			$new_instance['count'] = 10;
		}
		return $new_instance;
	}
	function form($instance) {	
		$instance = wp_parse_args((array) $instance, array('username' => '', 'count' => 5, 'title' => ''));		
		$username = esc_attr($instance['username']);
		$count = esc_attr($instance['count']);
		$title = esc_attr($instance['title']);
?>
	<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', BB_BASE); ?><input class="widefat" id="<?php echo $this->get_field_name('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
	
	<p><label for="<?php echo $this->get_field_id('username'); ?>"><?php _e('Twitter username:', BB_BASE); ?><input name="<?php echo $this->get_field_name('username'); ?>" id="<?php echo $this->get_field_id('username'); ?>" type="text" value="<?php echo $username; ?>" class="widefat" /></label></p>
	
	<p><label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Number of items to display:', BB_BASE); ?><input name="<?php echo $this->get_field_name('count'); ?>" id="<?php echo $this->get_field_id('count'); ?>" type="text" value="<?php echo $count; ?>" class="widefat" style="width:35px; text-align:center;" /></label></p>
<?php
	}	
}

register_widget('bb_widget_popularPosts');
register_widget('bb_widget_relatedPosts');
register_widget('bb_widget_twitter');

?>