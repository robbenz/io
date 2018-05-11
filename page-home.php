<?php
/*
Template Name: Home Page
*/
get_header();  ?>

<div id="primary" class="content-area">
		<main id="main" class="site-main">

      <div class="container">
        <div class="row">

          <div class="col-sm-8">
            <?php get_template_part('content', 'homepage') ; ?>
          </div>

          <div class="col-sm-4">
            <?php get_sidebar(); ?>
          </div>

        </div> <!-- .row -->
      </div><!-- .container -->

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
