<!doctype html>
<html class="no-js" <?php language_attributes(); ?>>

	<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- <title><?php wp_title( '|', true, 'right' ); ?></title> -->

    <meta name="description" content="">
    <!-- Removes auto phone number detection on iOS -->
    <meta name="format-detection" content="telephone=no">

		<?php wp_enqueue_style( 'theme-meta', get_stylesheet_uri() ); ?>
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/public/css/style.css">
		<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/resources/assets/css/bootstrap.min.css" >
		<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet">
    <!-- Javascript detection script -->
    <script>(function(H){H.className=H.className.replace(/\bno-js\b/,'js')})(document.documentElement)</script>

    <?php
			wp_enqueue_script('jquery');
			wp_enqueue_script('io-main');
			wp_head();

      $left_menu = wp_nav_menu([
        'theme_location'  => 'main-left',
        'container'       => false,
        'container_class' => false,
        'menu_id'         => 'main-menu-left',
        'menu_class'      => 'main-menu-left',
        'echo' => false,
      ]);

      $right_menu = wp_nav_menu([
        'theme_location'  => 'main-right',
        'container'       => false,
        'container_class' => false,
        'menu_id'         => 'main-menu-right',
        'menu_class'      => 'main-menu-right',
        'echo' => false,
      ]);;

    ?>
  </head>

	<body <?php body_class(); ?>>
    <div class="footer-push">
  		<header class="main-header" role="banner">

        <button type="button" class="mobile-menu-button">
            <span class="mobile-button-icon" aria-hidden="true"></span>
        </button>


        <div class="wrapper header-menu-wrapper">
          <nav class="header-menu header-menu-left"><?php echo $left_menu; ?></nav>
          <div class="site-logo">

          </div>
          <nav class="header-menu header-menu-right"><?php echo $right_menu; ?></nav>
        </div>

        <div class="wrapper header-menu-mobile">
        	<div class="site-logo">

  				</div>

          <nav class="site-menu" role="navigation" aria-label="Main Menu">
          	<?php wp_nav_menu(
          		array(
          			'theme_location'  => 'main',
          			'container'       => false,
          			'container_class' => false,
          			'menu_id'					=> 'main-menu',
          			'menu_class'      => 'main-menu'
          	)); ?>
          </nav>
        </div>
      </header>

			<div style="margin-bottom:40px; width:100%; background-color:#425462; height:30px;"></div>
