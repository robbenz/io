<?php get_header(); ?>

	<main role="main">

		<div class="wrapper">

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

				<article role="article">
					<header>
						<h1 class="page-headline"><?php the_title(); ?></h1>
					</header>

					<div class="content-editor">
						<?php the_content(); ?>
					</div>

					<footer>
						<?php the_tags( '<p>Tags: ', ', ', '</p>'); ?>
					</footer>
				</article>

			<?php endwhile; else: ?>

				<?php echo View::render('404-content'); ?>

			<?php endif; ?>
		</div>
	</main>

<?php get_footer(); ?>