<div id="wrapper">

<div id="header">
	<div id="branding" class="inner clearfix">
		<div class="top">										
			<div id="navbox">
				<?php
				// Using wp_nav_menu() to display menu
				wp_nav_menu( array(
					'menu' => 'Top', // Select the menu to show by Name
					'class' => '',
					'container' => false, // Remove the navigation container div
					'theme_location' => 'Top Navigation'
					)
				);
				?>
		
				<div class="loginform">
					<?php wp_login_form(); ?>
					<p class="forgotpass">
						<a href="#">Forgot Password</a> 
					</p>
					<p class="register">
						<a href="#">Register</a>
					</p>
				</div>
			
			</div><!--end navbox-->
			<div class="clearit"></div>
			
			<div class="logobox">
				<div class="left">
					<h1 class="logo"><a href="<?php echo home_url(); ?>" title="<?php bloginfo('title'); ?>"><?php bloginfo('title'); ?></a></h1>
					<h3 class="tagline"><?php bloginfo('description'); ?></h3>
				</div><!--end left-->

				<div class="right">													
					<a href="http://new2wp.com"><img src="http://new2wp.com/wp-content/uploads/2010/06/new2wp-468x60px1.png" /></a>
				</div><!--end right-->

				<div class="clearit"></div>
			</div><!--end logobox-->										
		</div>

	<div id="navigation" class="clearfix">
		<div class="inner clearfix">
			<div id="searchbox">
				<?php get_search_form(); ?>
			</div>

			<?php wp_nav_menu( array('menu' => 'Header', 'container' => '' )); ?>
		</div>
	</div>

	<ul id="social">		
		<li id="facebook"><a href="http://facebook.com/new2wp"><img src="<?php bloginfo('template_directory'); ?>/images/icons/facebook32.png" /></a></li>
		<li id="twitter"><a href="http://twitter.com/new2wp"><img src="<?php bloginfo('template_directory'); ?>/images/icons/twitter32.png" /></a></li>
	</ul>
	<ul id="signup" class="clearfix">
		<li id="email"><a href="http://feedburner.google.com/fb/a/mailverify?uri=cfs-resources&amp;loc=en_US" title="Signup for Email Updates">Email</a></li>
		<li id="rss"><a href="<?php bloginfo('rss2_url'); ?>" title="Subscribe to RSS">RSS</a></li>
	</ul>

</div><!-- end header -->