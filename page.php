<?php 
$featured_img = get_the_post_thumbnail_url();
get_header(); ?>
<main role="main">
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<article role="article">
			<header>
				<?php 
					if(!empty($featured_img)){
						echo '<div class="standard-hero-image" style="background-image:url('.$featured_img.')"></div>';
					}
				?>
				<div class="wrapper">
					<?php
						echo clio_breadcrumbs();
					?>
				</div>
			</header>

			<div class="wrapper">
				<div class="content-editor page-content">
					<?php the_content(); ?>
				</div>
			</div>
			<?php 
				echo View::render('brands'); 
				echo View::render('leadership'); 
			?>
		</article>

	<?php endwhile; endif; ?>
</main>
<?php get_footer(); ?>