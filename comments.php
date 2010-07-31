<?php
// Do not delete these lines
  if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
    die ('Please do not load this page directly. Thanks!');

  if ( post_password_required() ) { ?>
    <p class="nocomments">This post is password protected. Enter the password to view comments.</p>
  <?php
    return;
  }
?>

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
  <p>Nothing yet.</p>
  
  <?php else : // comments are closed ?>
  <p class="nocomments">Comments are closed.</p>
  
  <?php endif; ?>
<?php endif; ?>


<?php if ('open' == $post->comment_status) : ?>

<h3><?php comment_form_title( 'Leave a Reply', 'Leave a Reply to %s' ); ?></h3>

<div class="cancel-comment-reply">
  <small><?php cancel_comment_reply_link(); ?></small>
</div>

<?php if ( get_option('comment_registration') && !$user_ID ) : ?>

<p>You must be <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>">logged in</a> to post a comment.</p>

<?php else : ?>

<div id="commentsform">
<form action="<?php site_url(); ?>/wp-comments-post.php" method="post" id="commentform">
	<?php comment_id_fields(); ?>

	<?php if ( $user_ID ) : ?>
	<p>Logged in as <a href="<?php site_url(); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="Log out of this account">Log out &raquo;</a></p>
	<?php else : ?>

    <p>
      <label for="author">Name <?php if ($req) echo '<small class="required">*</small>'; ?></label>
      <input type="text" name="author" id="s1" value="<?php echo $comment_author; ?>" size="40" tabindex="1" />
    </p>
    <p>
      <label for="email">Mail <em class="notice">(will not be published)</em> <?php if ($req) echo '<small class="required">*</small>'; ?></label>
      <input type="text" name="email" id="s2" value="<?php echo $comment_author_email; ?>" size="40" tabindex="2" />
    </p>
    <p>
      <label for="url">Website</label>
      <input type="text" name="url" id="s3" value="<?php echo $comment_author_url; ?>" size="40" tabindex="3" />
    </p>
    <?php 
    /****** Math Comment Spam Protection Plugin ******/
    if ( function_exists('math_comment_spam_protection') ) { 
      $mcsp_info = math_comment_spam_protection();
    ?>  
    <p>
      <label for="mcspvalue">Sum of <?php echo $mcsp_info['operand1'] . ' + ' . $mcsp_info['operand2'] . ' ?' ?></label>
      <input type="text" name="mcspvalue" id="mcspvalue" value="" size="22" tabindex="4" />
      <input type="hidden" style="display:none" name="mcspinfo" value="<?php echo $mcsp_info['result']; ?>" />
    </p>
    <?php } // if function_exists... ?>

<?php endif; ?>
    <p id="rules-toggle"><a href="#">show allowed tags</a></p>
    <p class="comment-rules"><strong>XHTML:</strong> You can use these tags: <code><?php echo allowed_tags(); ?></code></p>
    <p><textarea name="comment" id="s4" rows="10" tabindex="4"></textarea></p>
    <p class="comment-disclaimer">By submitting a comment here you grant <strong><?php bloginfo('name'); ?></strong> a perpetual license to reproduce your words and name/web site in attribution. Inappropriate comments will be removed at admin's discretion.</p>
    <p>
      <input name="submit" type="submit" id="sbutt" tabindex="5" value="Submit Comment" />
      <input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
    </p>
    <?php do_action('comment_form', $post->ID); ?>

</form>
</div><!-- end #commentsform -->

<?php endif; // If registration required and not logged in ?>
<?php endif; // if you delete this the sky will fall on your head ?>