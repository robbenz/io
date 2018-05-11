<!-- This is expected!! -->
<?php /* get_header(); ?>

<main role="main">

	<div class="wrapper">

		<?php if (have_posts()) :
			$post = $posts[0]; // hack: set $post so that the_date() works
		?>

		<?php if (is_category()) { ?>
			<h1 class="page-headline">Archive for the &ldquo;<?php single_cat_title(); ?>&rdquo; Category</h1>

		<?php } elseif(is_tag()) { ?>
			<h1 class="page-headline">Posts Tagged &ldquo;<?php single_tag_title(); ?>&rdquo;</h1>

		<?php } elseif (is_day()) { ?>
			<h1 class="page-headline">Archive for <?php the_time('F jS, Y'); ?></h1>

		<?php } elseif (is_month()) { ?>
			<h1 class="page-headline">Archive for <?php the_time('F, Y'); ?></h1>

		<?php } elseif (is_year()) { ?>
			<h1 class="page-headline">Archive for <?php the_time('Y'); ?></h1>

		<?php } elseif (is_author()) { ?>
			<h1 class="page-headline">Author Archive</h1>

		<?php } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
			<h1 class="page-headline">Blog Archives</h1>

		<?php } ?>

		<?php while (have_posts()) : the_post(); ?>

			<article role="article">
				<header>
					<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
					<p>Posted on <?php the_time('F jS, Y'); ?> by <?php the_author(); ?></p>
				</header>

				<?php the_excerpt(); ?>

				<footer>
					<p><?php the_tags('Tags: ', ', ', '<br>'); ?> Posted in <?php the_category(', '); ?> &bull; <?php edit_post_link('Edit', '', ' &bull; '); ?> <?php comments_popup_link('Respond to this post &raquo;', '1 Response &raquo;', '% Responses &raquo;'); ?></p>
				</footer>
			</article>

		<?php endwhile; ?>

			<nav class="pagination">
				<?php
					global $wp_query;

					//pagination logic
					$big = 999999999; // need an unlikely integer

					$boolHideFirst = true;
					$boolHideLast = true;
					if($paged < 5){
						$mid = 4;

					} else {
						$mid = 2;
					}

					$pagination =  paginate_links( array(
							'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
							'format'    => '?paged=%#%',
							'current'   => max( 1, $paged ),
							'total'     => $wp_query->max_num_pages,
							'type'      => 'array',
							'end_size'  => 0,
							'mid_size'  => $mid,
							'prev_text' => __('Prev'),
							'next_text' => __('Next')
					) );
				?>
				<div class="pagination-links"><?php echo displayNumberedPagination($pagination);?></div>
			</nav>

		<?php else : ?>

			<article>
				<h1>Not Found</h1>
				<p>Sorry, but the requested resource was not found on this site.</p>
			</article>

		<?php endif; ?>
	</div>
</main>

<?php get_footer(); */ ?>