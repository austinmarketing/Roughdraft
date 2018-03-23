			<footer class="footer" role="contentinfo" itemscope itemtype="http://schema.org/WPFooter">

				<div id="inner-footer" class="grid-container">

					<nav role="navigation">
						<?php wp_nav_menu(array(
    					'container' => 'div',                           	// enter '' to remove nav container
    					'container_class' => 'footer-links cf',         	// class of container (should you choose to use it)
    					'menu' => __( 'Footer Links', 'roughdraft' ),  		// nav name
    					'menu_class' => 'nav footer-nav cf',            	// adding custom nav class
    					'theme_location' => 'footer-links',             	// where it's located in the theme
    					'before' => '',                                 	// before the menu
    					'after' => '',                                  	// after the menu
    					'link_before' => '',                            	// before each link
    					'link_after' => '',                             	// after each link
    					'depth' => 0,                                   	// limit the depth of the nav
    					'fallback_cb' => 'roughdraft_footer_links_fallback' // fallback function
						)); ?>
					</nav>

					<p class="source-org copyright">&copy; <?php echo date('Y'); ?> <?php bloginfo( 'name' ); ?>.</p>

				</div>

			</footer>

		</div>

		<?php // all js scripts are loaded in library/roughdraft.php ?>
		<?php wp_footer(); ?>

	</body>

</html> <!-- end html -->
