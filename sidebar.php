<aside class="main-sidebar" role="complementary">
	<ul>
  	<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : ?>
			<?php dynamic_sidebar('standard-sidebar'); ?>
  	<?php endif; ?>
	</ul>
</aside>
