	<div id="footer">

		<div id="site-info" class="inner clearfix">
	
			<?php wp_nav_menu( array( 'menu' => 'Footer', 'class' => '', 'container' => false, 'theme_location' => 'Footer Navigation' )); ?>
			
			<p id="copyright">&copy; <?php echo date("Y"); ?> 
			<?php bloginfo('name'); ?>. Site Design by <a href="http://new2wp.com">New2WP</a></p>
		</div>

	</div><!--// end #footer -->
</div><!--// end #wrapper -->

<?php wp_footer(); ?>

</body>
</html>