<?php 
	$menu = wp_nav_menu([
		'theme_location'  => 'main',
		'container'       => false,
		'container_class' => false,
		'menu_id'					=> 'footer-menu',
		'menu_class'      => 'footer-menu',
		'echo' => false
  ]);

  $content = get_field('footer_content', 'options');
?>
		</div>
  	<footer role="contentinfo">
  		<div class="main-footer">
	  		<div class="wrapper">
	  			<div class="left">
	  				<div class="footer-logo">
	  					
	  				</div>
	  			</div>
	  			<div class="right">
	  				<div class="left"><?php echo $menu; ?></div>
	  				<div class="right"><?php echo $content; ?></div>
	  			</div>
	  		</div>
  		</div>
  		<div class="secondary-footer">
  			<div class="wrapper">
	  			<p>Copyright &copy; <?php echo date('Y'); ?> by XXX. All Rights Reserved.</p>
  			</div>
  		</div>
  	</footer>
  	<?php wp_footer(); ?>
	</body>
</html>