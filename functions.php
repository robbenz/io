<?php
/**
 * Theme auto loading
 */
foreach (glob(__DIR__ . '/includes/functions/*.php') as $auto_load) {
    require_once $auto_load;
}

// Justified layout fix
add_filter('wp_nav_menu_items', 'filter_menu_items');

function filter_menu_items($menu_items){
	return str_replace('</li><li', "</li> <li", $menu_items);
}

// setup mail settings
add_action ( 'phpmailer_init', function ($phpmailer) {
	if ($_ENV ['MAIL_DRIVER'] == "smtp") {
		$phpmailer->From = $_ENV ['MAIL_FROM'];
		$phpmailer->Timeout = 20;
		if ($_ENV ['MAIL_ENCRYPTION'] != 'null') {
			$phpmailer->SMTPSecure = true;

			$phpmailer->SMTPAutoTLS = false;
			$phpmailer->SMTPOptions ['ssl'] ['verify_peer'] = false;
			$phpmailer->SMTPOptions ['ssl'] ['verify_peer_name'] = false;
			$phpmailer->SMTPOptions ['ssl'] ['allow_self_signed'] = true;
			$phpmailer->SMTPOptions ['tls'] ['verify_peer'] = false;
			$phpmailer->SMTPOptions ['tls'] ['verify_peer_name'] = false;
			$phpmailer->SMTPOptions ['tls'] ['allow_self_signed'] = true;
		} else {
			$phpmailer->SMTPSecure = false;
		}
		$phpmailer->isSMTP ();
		$phpmailer->Host = $_ENV ['MAIL_HOST'];
		$phpmailer->Port = $_ENV ['MAIL_PORT'];
		if ($_ENV ['MAIL_AUTH'] != 'false') {
			$phpmailer->SMTPAuth = true;
			$phpmailer->Username = $_ENV ['MAIL_USERNAME'];
			$phpmailer->Password = $_ENV ['MAIL_PASSWORD'];
		} else {
			$phpmailer->SMTPAuth = false;
		}
	}
}, 3, 1 );


/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function iodd_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'iodd' ),
		'id'            => 'standard-sidebar',
		'description'   => esc_html__( 'Add widgets here.', 'iodd' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'iodd_widgets_init' );
