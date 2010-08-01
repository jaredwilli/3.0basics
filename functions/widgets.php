<?php

/**
 * calculate the posts with the most comments from the last 6 months
 *
 * @param array args previously specified arguments
 * @param int postCount the number of posts to display
 */
function bb_popularPosts ($args = array(), $displayComments = TRUE, $interval = '') {
	global $wpdb;	
	$postCount = 5;
	$request = 'SELECT * FROM ' . $wpdb->posts . ' WHERE ';
	if ($interval != '') {
		$request .= 'post_date>DATE_SUB(NOW(), ' . $interval . ') ';
	}
	$request .= 'post_status="publish" AND comment_count > 0 ORDER BY comment_count DESC LIMIT 0, ' . $postCount;
	$posts = $wpdb->get_results($request);
	if (count($posts) >= 1) {	
		$defaults = array (
			'title' => __('Popular Posts', BB_BASE),
		);
		$args = bb_defaultArgs($args, $defaults);
		foreach ($posts as $post) {
			wp_cache_add($post->ID, $post, 'posts');
			$popularPosts[] = array(
				'title' => stripslashes($post->post_title),
				'url' => get_permalink($post->ID),
				'comment_count' => $post->comment_count,
			);
		}		
		echo $args['before_widget'] . $args['before_title'] . $args['title'] . $args['after_title']; ?>
	<div class="widgetInternalWrapper">
		<ol class="popularPosts postsList">
		<?php
		foreach ($popularPosts as $post) {
			if ($listClass == 'odd') {
				$listClass = 'even';
			} else {
				$listClass = 'odd';
			} ?>
			<li class="<?php echo $listClass; ?>">
				<a href="<?php echo $post['url'];?>"><?php echo $post['title']; ?></a>
	<?php if ($displayComments) { ?>
				<span class="commentsCount">(<?php echo $post['comment_count'] . ' ' . __('comments', BB_BASE ); ?>)</span>
	<?php } ?>
			</li>
	<?php } ?>
		</ol>
	</div>
		<?php echo $args['after_widget']; 
	}
}

/**
 *
 * Related Posts widget
 */
function bb_relatedPosts ($args = array()) {
	if (!is_single()) {
		return FALSE;
	}
	global $post;
	$tags = wp_get_post_tags($post->ID);
	if ($tags) {
		$first_tag = $tags[0]->term_id;
		$query = array(
			'tag__in' => array ($first_tag),
			'post__not_in' => array ($post->ID),
			'showposts' => 5,
			'caller_get_posts' => 1,
		);
		$bbWp = new WP_Query($query);		
		if ($bbWp->have_posts()) {
			$defaults = array (
				'title' => __('Related Posts', BB_BASE),
			);
			$args = bb_defaultArgs($args, $defaults);			
			echo $args['before_widget'] . $args['before_title'] . $args['title'] . $args['after_title'];
		echo '<ul class="relatedPosts postsList">';
			while ($bbWp->have_posts()) {
				$bbWp->the_post();
		?>
			<li><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e('Permanent Link to', BB_BASE); ?> <?php the_title_attribute(); ?>"><?php the_title(); ?></a></li>
		<?php
			}
		echo '</ul>';
		echo $args['after_widget'];
		}	
	}
}


/**
 *
 * post your latest Tweets on your sidebar
 *
 * @param array args previously specified arguments
 * @param string searchQuery
 */
function bb_twitter($args = array(), $searchQuery = '') {	
	$defaults = array (
		'title' => __('Twitter Updates', BB_BASE),
		'count' => 5,
		'username' => 'binarymoon',
	);
	$args = bb_defaultArgs($args, $defaults);
	if ($searchQuery == '') {
		$searchQuery = 'q=' . urlencode('from:' . $args['username']);
	}	
	$cachename = 'twitter_' . md5($searchQuery);
	$requestPath = 'http://search.twitter.com/search.json?' . $searchQuery . '&rpp=' . $args['count'];	
	//echo $requestPath;
	$content = bb_getContent($requestPath, 60, 'twitter');
	if ($content) {
		$content = json_decode($content);
		if (count($content->results) > 0) {
			echo $args['before_widget'] . $args['before_title'] . $args['title'] . $args['after_title'];	
	echo '<div class="twitterWidget">
		<div class="widgetInternalWrapper">
			<ul class="twitter_update_list">';			
			$listClass = 'even';
			foreach ($content->results as $tweet) {
				if ($listClass == 'odd') {
					$listClass = 'even';
				} else {
					$listClass = 'odd';
				}				
				$tweet->text = make_clickable($tweet->text);
				$tweet->text = preg_replace('/(@([_a-z0-9\-]+))/i','<a href="http://twitter.com/$2">$1</a>', $tweet->text);
				$tweet->text = preg_replace('/(#([_a-z0-9\-]+))/i','<a href="http://twitter.com/search?q=%23$2">$1</a>', $tweet->text);
				$tweet->created_at = bb_timeSince(strtotime($tweet->created_at)); ?>
				<li class="<?php echo $listClass; ?>">
					<div class="tweetContent">
						<a href="http://www.twitter.com/<?php echo $tweet->from_user; ?>/status/<?php echo $tweet->id; ?>/" class="profile_image_url">
							<img src="<?php echo $tweet->profile_image_url; ?>" width="48" height="48" alt="<?php echo $tweet->from_user; ?>" />
						</a>
						<p class="tweetText"><?php echo $tweet->text; ?></p>
						<p class="tweetDate"><a href="http://www.twitter.com/<?php echo $tweet->from_user; ?>/status/<?php echo $tweet->id; ?>/"><?php echo $tweet->created_at; ?></a></p>
					</div>
				</li>
		<?php }

		echo'</ul>
		</div>
	</div>';
			echo $args['after_widget'];
		}	
	}
}
?>