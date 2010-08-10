<div id="wrapper">
	<div id="header">
		<div id="branding" class="inner clearfix">
			<div class="top">										
				<div id="topnav">
					<?php
					// Using wp_nav_menu() to display menu
					wp_nav_menu( array(
						'menu' => 'Top', // Select the menu to show by Name
						'class' => 'nav-top',
						'container' => false, // Remove the navigation container div
						'theme_location' => 'Top Navigation'
						)
					);
					?>
					
					<?php // Uncomment this to not show login form when logged in
					// if( !is_user_logged_in() ) { ?>
					<div class="loginform">
						<?php wp_login_form(); ?>
						<p class="forgotpass">
							<a href="#">Forgot Password</a> 
						</p>
						<p class="register">
							<a href="#">Register</a>
						</p>
					</div>
					<?php // } // end if(!is_user_logged_in()). Uncomment to close IF. ?>
				
				</div><!--end topnav-->
				<div class="clearit"></div>
				
				<div class="logobox">
					<div class="left">
						<h1 class="logo"><a class="logo" href="<?php echo home_url('/'); ?>" title="<?php bloginfo('title'); ?>"><?php bloginfo('title'); ?></a></h1>
						<h3 class="tagline"><?php bloginfo('description'); ?></h3>
					</div><!--end left-->
	
					<div class="right">													
						<a href="http://new2wp.com" rel="bookmark"><img src="http://new2wp.com/wp-content/uploads/2010/06/new2wp-468x60px1.png" /></a>
					</div><!--end right-->
	
					<div class="clearit"></div>
				</div><!--end logobox-->										
			</div>
	
			<div id="navigation" class="clearfix">
				<div class="inner clearfix">
					<div id="searchbox"><?php get_search_form(); ?></div>
		
					<?php wp_nav_menu( array('menu' => 'Header', 'container' => '' )); ?>
				</div>
			</div>
		
			<ul id="social">		
				<li id="facebook"><a href="http://facebook.com/new2wp"><img src="<?php bloginfo('template_directory'); ?>/images/icons/facebook32.png" /></a></li>
				<li id="twitter"><a href="http://twitter.com/new2wp"><img src="<?php bloginfo('template_directory'); ?>/images/icons/twitter32.png" /></a></li>
				<li id="rss"><a href="http://feeds.feedburner.com/New2WP"><img src="<?php bloginfo('template_directory'); ?>/images/icons/rss32.png" /></a></li>
				<li id="email"><a href="http://feedburner.google.com/fb/a/mailverify?uri=New2WP"><img src="<?php bloginfo('template_directory'); ?>/images/icons/email32.png" /></a></li>
			</ul>

		</div>
	</div><!-- end header -->	