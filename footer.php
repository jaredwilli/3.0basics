	<div id="footer">

		<div id="site-info" class="inner clearfix">
	
			<?php wp_nav_menu( array( 'menu' => 'Footer', 'class' => 'footer-menu', 'container' => false, 'theme_location' => 'Footer Navigation' )); ?>
			
		  <p id="copyright">
				&copy; <?php echo date("Y"); ?> <a href="<?php home_url('/'); ?>"><?php bloginfo('name'); ?></a> by <a href="http://new2wp.com">New2WP.com</a>
			</p>
		</div>

	</div><!--// end #footer -->
</div><!--// end #wrapper -->

<?php wp_footer(); ?>
<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/global.js"></script>
<!--<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/cufon-yui.js"></script>-->

</body>
</html>