<!-- MAIN HOMEPAGE
================================================== -->

<?php
$main_intro_header = iodd_get_theme_option( 'main_intro_header' );
$main_intro_copy   = iodd_get_theme_option( 'main_intro_copy' );

?>

<section id="main_homepage">

  <h1><?php echo $main_intro_header; ?></h1>
  <p><?php echo $main_intro_copy; ?></p>

  <?php $count_slides = wp_count_posts( $post_type = 'slides' ); ?>

  		<div id="myCarousel" class="carousel slide" data-ride="carousel">

  			<!-- Indicators -->
  	    <ol class="carousel-indicators">
  				<?php
  				for ($x=0; $x<$count_slides->publish; $x++) {
  					$act = '';
  					if ($x == 0) $act = ' active';
  					?>
  					<li data-target="#myCarousel" data-slide-to="<?php echo $x; ?>" class="indicator<?php echo $act; ?>"></li>
  				<?php } ?>
  	    </ol>

  	    <!-- Wrapper for slides -->
  	    <div class="carousel-inner" role="listbox">

  				<?php
  				$loop = new WP_Query(
  					array(
  						'post_type' => 'slides',
  						'orderby'   => 'post_id',
  						'order'     => 'ASC'
  					)
  				);
  				$y=0;
  				while( $loop->have_posts() ) : $loop->the_post();
  				// $slide_title   = get_field('slide_title');
  				$slide_image   = get_field('slide_image');
  				$slide_caption = get_field('slide_caption');
  				$active = '';
  				if ($y == 0) $active = ' active';
  				?>

  				<div class="item<?php echo $active; ?>">
  					<div class="carousel-title">
  						<h3><?php the_title(); ?></h3>
  					</div>
  					<img src="<?php echo $slide_image['url']; ?>" alt="<?php echo $slide_image['alt']; ?>" >
  					<div class="carousel-caption">
  						<p><?php echo $slide_caption ; ?></p>
  					</div>
  				</div>

  			 <?php $y++; endwhile; ?>

  	    </div>

  	    <!-- Left and right controls -->
  	    <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
  	      <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
  	      <span class="sr-only">Previous</span>
  	    </a>
  	    <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
  	      <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
  	      <span class="sr-only">Next</span>
  	    </a>
  	  </div>

  		<?php wp_reset_postdata(); ?>


<?php

      $menu = new WP_Query(
        array(
          'post_type' => 'menu_grid',
          'orderby'   => 'post_id',
          'order'     => 'ASC'
        )
      );
      // $y=0;
      // for ($x=0; $x<$count_slides->publish; $x++) {
      // $count_slides = wp_count_posts( $post_type = 'slides' );
      //
?>


      <div class="row" id="img_box_wrap">
          <?php while( $menu->have_posts() ) : $menu->the_post(); ?>
          <div class="img_box text-center col-xs-4">
              <img src="<?php the_post_thumbnail_url(); ?>" alt="" class="img-responsive">
              <div class="img_box_text">
                  <p><?php the_title(); ?></p>
              </div>
          </div>

          <?php endwhile; ?>
      </div>





</section>
