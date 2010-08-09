<?php // Do not delete these lines
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
if ( post_password_required() ) { ?>
	<p class="nocomments">This post is password protected. Enter the password to view comments.</p>
<?php return; } ?>
<?php if ( have_comments() ) : ?>

	<h2 id="comments" class="title"><?php comments_number('No Responses', 'One Response', '% Responses' );?> to &#8220;<?php the_title(); ?>&#8221;</h2>

	<ul class="navigation">
		<li class="alignleft"><?php previous_comments_link() ?></li>
		<li class="alignright"><?php next_comments_link() ?></li>
	</ul>

	<ol class="commentlist">
<?php wp_list_comments('callback=base_comment'); ?>
	</ol>

	<ul class="navigation">
		<li class="alignleft"><?php previous_comments_link() ?></li>
		<li class="alignright"><?php next_comments_link() ?></li>
	</ul>
	
<?php else : // this is displayed if there are no comments so far ?>

	<?php if ('open' == $post->comment_status) : ?>
	<!-- <p>No comments yet. <a href="#respond">Post one &raquo;</a>.</p> -->
	
	<?php else : // comments are closed ?>
	<p class="nocomments">Comments are closed.</p>
	
	<?php endif; ?>
<?php endif; ?>

<?php comment_form(); ?>