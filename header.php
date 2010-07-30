<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
  <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
  <title><?php wp_title(''); ?></title>
  <meta name="author" content="freshtilledsoil.com" />
  <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
  <link href="<?php bloginfo('template_url'); ?>/css/print.css" type="text/css" rel="stylesheet" media="print" />
  <!--[if IE]><link href="<?php bloginfo('template_url'); ?>/css/ie.css" type="text/css" rel="stylesheet" media="all" /><![endif]-->
  <!--<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/cufon-yui.js"></script>-->
  <link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
  <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
  
  <?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
  <?php wp_head(); ?> 
  <script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/global.js"></script>
