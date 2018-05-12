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
      echo '<div class="fund_top"><p>Fundy (fun-dee)<br>Referring to fundamental values and behaviors that help define how we act and roll with our customers.</p></div>';
      echo '<div class="fund_bottom"><h2>' . $new_fundy->post_title . '</h2>'.$new_fundy->post_content . '</div><br>';

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
