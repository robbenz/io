<?php

View::make(__DIR__ . '/../../partials');


//builds breadcrumbs
function clio_breadcrumbs(){
  global $post;

  if(is_post_type_archive('leadership')){
    $post = get_page_by_title('Leadership');
  }

  $html = '<div class="breadcrumbs">';
    $html .= '<a href="/">Home</a>';
    $html .= '<span class="spacer">|</span>';
    $html .= $post->post_title; 
  $html .= '</div>';
  return $html;
}

// Allow .vcf files to upload to the media library
add_filter('upload_mimes', function ($existing_mimes){
  $existing_mimes['vcf'] = 'text/x-vcard'; 
  return $existing_mimes;
});
 

/**
 * Script & Style Registration
 */
function io_theme_scripts()
{
  /* -- Scripts -- */
  wp_register_script('io-main', get_template_directory_uri().'/public/js/main.js', array( 'jquery' ), '', true );
  wp_register_script('vue', 'https://cdnjs.cloudflare.com/ajax/libs/vue/2.3.3/vue.min.js', [], '', true );

  /* -- Initial Localization -- */
  wp_localize_script( 'io-main', 'io_obj', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}
add_action( 'init', 'io_theme_scripts' );

/**
 * Custom Image Sizes
 */
 
 /**
 * Get contents of an SVG file for inline ouput
 * Example: renderSVG(get_template_directory_uri().'/images/site-logo.svg');
 */
function renderSVG($path) {
    if ($_ENV['DEBUG']) {
        $context = stream_context_create(array (
            'http' => array (
                'header' => 'Authorization: Basic ' . base64_encode("{$_ENV['HTUSER']}:{$_ENV['HTPASS']}")
            )
        ));

        return file_get_contents($path, false, $context);
    } else {
        return file_get_contents($path);
    }
}

/**
 * Removes content editor from template
 */
add_action('admin_init', function () {
    $currentPost = get_current_admin_post_object();
    $templateName = [];

    if(!empty($templateName)){
      foreach ($templateName as $name) {
          if ($currentPost->template === "template-{$name}.php") {
              remove_post_type_support($currentPost->post_type, 'editor');
              break;
          }
      }
    }

});

/**
 * Wrap videos embedded via oEmbed to make them responsive
 */
function p2_wrap_oembed( $html, $url, $attr, $post_id ) {
    return '<div class="video-embed">'.$html.'</div>';
}
add_filter( 'embed_oembed_html', 'p2_wrap_oembed', 99, 4 );

