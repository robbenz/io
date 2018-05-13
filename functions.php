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



/**
* widget class
*/

// Register and load the widget
function pre_load_widget() {
    register_widget( 'events_widget' );
    register_widget( 'fundys_widget' );
}
add_action( 'widgets_init', 'pre_load_widget' );


// Create fundy widget
class fundys_widget extends WP_Widget {
    function __construct() {
        parent::__construct(
            'fundys_widget',
            __('Featured Fundy', 'fundys_widget_domain'),
            array(
                'description'   => __( 'This widget will show the fundy you select', 'fundys_widget_domain' ),
                'classname'     => __( 'fundys_wrap', 'fundys_widget_domain' ),
            )
        );
    }

    // front
    public function widget( $args, $instance ) {
      $title = apply_filters( 'widget_title', $instance['title'] );
      $fundy = $instance[ 'fundy' ];

      echo $args['before_widget'];   // before and after widget args -- theme default i guess
      if ( ! empty( $title ) ) {
        echo $args['before_title'] . $title . $args['after_title'];
      }


      $new_fundy = get_post($fundy);
      $fund_pro  = iodd_get_theme_option( 'fund_pro' );
      $fund_copy = iodd_get_theme_option( 'fund_copy' );

      echo '<div class="fund_top"><p><b>'.$fund_pro.'</b><br>'.$fund_copy.'</p></div>';
      echo '<div class="fund_bottom"><h2>'.$new_fundy->post_title.'</h2>'.$new_fundy->post_content.'</div><br>';

      echo $args['after_widget'];
    }

    // back
    public function form( $instance ) {
      if ( isset( $instance[ 'title' ] ) ) {
        $title = $instance[ 'title' ];
      } else {
        $title = __( 'Featured Fundy', 'fundys_widget_domain' );
      }
      $fundy = $instance[ 'fundy' ];
      $args = array(
        'post_type'   => 'fundys'
      );
      $all_fundys = get_posts( $args );

      // Widget admin form
      ?>
      <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
      </p>

      <p>
        <select id="<?php echo $this->get_field_id('fundy'); ?>" name="<?php echo $this->get_field_name('fundy'); ?>" class="widefat" style="width:100%;">
          <?php foreach($all_fundys as $one_fundy) : ?>
            <option <?php selected( $instance['fundy'], $one_fundy->ID ); ?>  value="<?php echo $one_fundy->ID; ?>"><?php echo $one_fundy->post_title; ?></option>
          <?php endforeach; ?>
        </select>
      </p>

      <p>
        <a class="page-title-action" target="_blank" href="<?php echo site_url(); ?>/wp-admin/post-new.php?post_type=fundys">Add New fundy</a>
      </p>

      <?php
    }

    // save it
    public function update( $new_instance, $old_instance ) {
      $instance = array();
      $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
      $instance['fundy'] = $new_instance['fundy'];
      return $instance;
    }

} // Class dismissed


// Create the events widget
class events_widget extends WP_Widget {
    function __construct() {
        parent::__construct(
            'events_widget',
            __('Upcoming Events', 'events_widget_domain'),
            array(
                'description'   => __( 'This widget will show your Upcoming events', 'events_widget_domain' ),
                'classname'     => __( 'events_wrap', 'events_widget_domain' ),
            )
        );
    }

    // front
    public function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', $instance['title'] );
        if ( !$number = (int) $instance['number'] ) {
            $number = 10;
        } elseif ( $number < 1 ) {
            $number = 1;
        } elseif ( $number > 15 ) {
            $number = 15;
        }

        $sup = new WP_Query(
          array(
            'showposts'           => $number,
            'nopaging'            => 0,
            'post_status'         => 'publish',
            'ignore_sticky_posts' => true,
            'post_type'           => 'events',
          )
        );

        if ($sup->have_posts()) :

          echo $args['before_widget'];
          if ( ! empty( $title ) ) {
            echo $args['before_title'] . $title . $args['after_title'];
          }
          // show the stuff
          ?>
          <div class="event_widget_wrap">
            <table id="event_list">
              <?php while ($sup->have_posts()) : $sup->the_post(); ?>
                <tr>
                  <td class="event_date"><?php echo get_field('event_date'); ?></td>
                  <td class="event_desc"><?php the_title(); ?></td>
                </tr>
                <tr class="spacer">
                  <td></td>
                  <td></td>
                </tr>
              <?php endwhile; ?>
            </table>
          </div><!-- .event_widget_wrap -->

          <?php
          echo $args['after_widget'];
          wp_reset_postdata();
        endif;
      }

      // back
      public function form( $instance ) {
        if ( isset( $instance[ 'title' ] ) ) {
          $title = $instance[ 'title' ];
        } else {
          $title = __( 'Upcoming Events', 'events_widget_domain' );
        }
        if ( !isset($instance['number']) || !$number = (int) $instance['number'] ) {
          $number = 3;
        }
        // Widget admin form
        ?>
        <p>
          <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>

        <p>
          <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of Events to show:'); ?></label>
          <input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" />
        </p>

        <p>
          <a class="page-title-action" target="_blank" href="<?php echo site_url(); ?>/wp-admin/post-new.php?post_type=events">Add New Event</a>
        </p>

        <?php
      }

    // save it
    public function update( $new_instance, $old_instance ) {
      $instance = array();
      $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
      $instance['number'] = (int) $new_instance['number'];
      return $instance;
    }

} // Class dismissed






/**
 * Create A Simple Theme Options Panel
 * https://www.wpexplorer.com/wordpress-theme-options/
 */


// Start Class
if ( ! class_exists( 'IODD_Theme_Options' ) ) {

	class IODD_Theme_Options {

		/**
		 * Start things up
		 *
		 * @since 1.0.0
		 */
		public function __construct() {

			// We only need to register the admin panel on the back-end
			if ( is_admin() ) {
				add_action( 'admin_menu', array( 'IODD_Theme_Options', 'add_admin_menu' ) );
				add_action( 'admin_init', array( 'IODD_Theme_Options', 'register_settings' ) );
			}

		}

		/**
		 * Returns all theme options
		 *
		 * @since 1.0.0
		 */
		public static function get_theme_options() {
			return get_option( 'theme_options' );
		}

		/**
		 * Returns single theme option
		 *
		 * @since 1.0.0
		 */
		public static function get_theme_option( $id ) {
			$options = self::get_theme_options();
			if ( isset( $options[$id] ) ) {
				return $options[$id];
			}
		}

		/**
		 * Add sub menu page
		 *
		 * @since 1.0.0
		 */
		public static function add_admin_menu() {
			add_menu_page(
				esc_html__( 'Theme Settings', 'text-domain' ),
				esc_html__( 'Theme Settings', 'text-domain' ),
				'manage_options',
				'theme-settings',
				array( 'IODD_Theme_Options', 'create_admin_page' )
			);
		}

		/**
		 * Register a setting and its sanitization callback.
		 *
		 * We are only registering 1 setting so we can store all options in a single option as
		 * an array. You could, however, register a new setting for each option
		 *
		 * @since 1.0.0
		 */
		public static function register_settings() {
			register_setting( 'theme_options', 'theme_options', array( 'IODD_Theme_Options', 'sanitize' ) );
		}

		/**
		 * Sanitization callback
		 *
		 * @since 1.0.0
		 */
		public static function sanitize( $options ) {

			// If we have options lets sanitize them
			if ( $options ) {

				// Main Intro Header
				if ( ! empty( $options['main_intro_header'] ) ) {
					$options['main_intro_header'] = sanitize_text_field( $options['main_intro_header'] );
				} else {
					unset( $options['main_intro_header'] ); // Remove from options if empty
				}

				// Main Intro Copy
				if ( ! empty( $options['main_intro_copy'] ) ) {
					$options['main_intro_copy'] = sanitize_text_field( $options['main_intro_copy'] );
				} else {
					unset( $options['main_intro_copy'] ); // Remove from options if empty
				}

				// Fundy pro
				if ( ! empty( $options['fund_pro'] ) ) {
					$options['fund_pro'] = sanitize_text_field( $options['fund_pro'] );
				} else {
					unset( $options['fund_pro'] ); // Remove from options if empty
				}

				// Fundy copy
				if ( ! empty( $options['fund_copy'] ) ) {
					$options['fund_copy'] = sanitize_text_field( $options['fund_copy'] );
				} else {
					unset( $options['fund_copy'] ); // Remove from options if empty
				}

			}

			// Return sanitized options
			return $options;

		}

		/**
		 * Settings page output
		 *
		 * @since 1.0.0
		 */
		public static function create_admin_page() { ?>

			<div class="wrap">

				<h1><?php esc_html_e( 'Theme Options', 'text-domain' ); ?></h1>

				<form method="post" action="options.php">

					<?php settings_fields( 'theme_options' ); ?>

					<table class="form-table wpex-custom-admin-login-table">

						<?php // Main Intro Header ?>
						<tr valign="top">
							<th scope="row"><?php esc_html_e( 'Main Intro Header', 'text-domain' ); ?></th>
							<td>
								<?php $value = self::get_theme_option( 'main_intro_header' ); ?>
								<input type="text" name="theme_options[main_intro_header]" value="<?php echo esc_attr( $value ); ?>">
							</td>
						</tr>

						<?php // Main Intro Copy ?>
						<tr valign="top">
							<th scope="row"><?php esc_html_e( 'Main Intro Copy', 'text-domain' ); ?></th>
							<td>
								<?php $value = self::get_theme_option( 'main_intro_copy' ); ?>
								<input type="text" name="theme_options[main_intro_copy]" value="<?php echo esc_attr( $value ); ?>">
							</td>
						</tr>

						<?php // Fundee Pronunciation ?>
						<tr valign="top">
							<th scope="row"><?php esc_html_e( 'Fundy Pronunciation', 'text-domain' ); ?></th>
							<td>
								<?php $value = self::get_theme_option( 'fund_pro' ); ?>
								<input type="text" name="theme_options[fund_pro]" value="<?php echo esc_attr( $value ); ?>">
							</td>
						</tr>

						<?php // Fundee Copy ?>
						<tr valign="top">
							<th scope="row"><?php esc_html_e( 'Fundy Copy', 'text-domain' ); ?></th>
							<td>
								<?php $value = self::get_theme_option( 'fund_copy' ); ?>
								<input type="text" name="theme_options[fund_copy]" value="<?php echo esc_attr( $value ); ?>">
							</td>
						</tr>

					</table>

					<?php submit_button(); ?>

				</form>

			</div><!-- .wrap -->
		<?php }

	}
}
new IODD_Theme_Options();

// Helper function to use in your theme to return a theme option value
function iodd_get_theme_option( $id = '' ) {
	return IODD_Theme_Options::get_theme_option( $id );
}
