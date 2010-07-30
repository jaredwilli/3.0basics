<?php get_header(); ?>
</head>
<body class="<?php base_body_class(); ?>">

<?php include(TEMPLATEPATH . '/branding.php'); ?>

<div id="content" class="inner clearfix">

<div id="content-main">

<?php if (have_posts()) : ?>
<?php rewind_posts(); ?>

  <?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
  <?php /* If this is a category archive */ if (is_category()) { ?>
  <h1 class="breadcrumb"><?php bloginfo('name'); ?> &gt; <?php single_cat_title(); ?></h1>
  <?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
  <h1 class="breadcrumb">Posts Tagged &#8216;<?php single_tag_title(); ?>&#8217;</h1>

  <?php /* If this is a taxonomy archive */ } elseif (is_tax()) { ?>
  <h1 class="breadcrumb">Archive for <?php echo get_query_var('taxonomy'); ?></h1>

  <?php /* If this is a daily archive */ } elseif (is_day()) { ?>
  <h1 class="breadcrumb">Archive for <?php the_time('F jS, Y'); ?></h1>
  <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
  <h1 class="breadcrumb">Archive for <?php the_time('F, Y'); ?></h1>
  <?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
  <h1 class="breadcrumb">Archive for <?php the_time('Y'); ?></h1>
  <?php /* If this is an author archive */ } elseif (is_author()) { ?>
  <h1 class="breadcrumb">Author Archive</h1>
  <?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
  <h1 class="breadcrumb">Blog Archives</h1>
  <?php } ?>
  
	<?php while (have_posts()) : the_post(); ?>

  <div <?php post_class('clearfix'); ?> id="post-<?php the_ID(); ?>">
  
    <h2 class="post-title"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
    
    <ul class="postinfo clearfix">
      <li class="authordata"><?php the_time('M j, Y'); ?><!-- at <?php the_time() ?>--> by <strong><?php the_author() ?></strong></li>
      <li class="commentdata"><?php comments_popup_link('No Comments', '1 Comment', '% Comments'); ?></li>
    </ul>
    
    <div class="entry clearfix">
      <?php the_content(__('Read more'));?>
    </div><!--// end #entry -->
    
  </div><!--// end #post-<?php the_ID(); ?> -->

<?php endwhile; ?>

  <?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); } else { ?>
  <div class="navigation clearfix">
    <div class="alignleft"><?php next_posts_link('&laquo; Previous Entries') ?></div>
    <div class="alignright"><?php previous_posts_link('Next Entries &raquo;') ?></div>
  </div>
  <?php } ?>
      
<?php else : ?>

  <h2 class="title">Oops</h2>
  <p>Looks like something is missing...</p>

<?php endif; ?>

</div><!-- end #content-main -->

<div id="content-sub">
  <?php get_sidebar(); ?>
</div> <!-- end #content-sub -->

</div><!-- end #content -->

<?php get_footer(); ?>