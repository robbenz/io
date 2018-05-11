<?php

namespace sixlabs\wp;

class Cpt
{
    public $postType;
    public $slug;

    public function register($slug, $singular, $plural, array $args = [])
    {
        // Set Defaults
        $args = array_merge([
            'labels' => [
                'name' => $plural,
                'singular_name' => $singular,
                'menu_name' => $plural,
                'name_admin_bar' => $singular,
                'add_new' => 'Add New',
                'add_new_item' => 'Add New ' . $singular,
                'new_item' => 'New ' . $singular,
                'edit_item' => 'Edit ' . $singular,
                'view_item' => 'View ' . $singular,
                'all_items' => 'All ' . $plural,
                'search_items' => 'Search ' . $plural,
                'parent_item_colon' => 'Parent ' . $plural,
                'not_found' => 'No ' . $plural . ' Found',
                'not_found_in_trash' => ' No ' . $plural . ' Found in Trash',
            ],
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => ['slug' => $slug],
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => null,
            'supports' => [
                'title',
                'editor',
                'author',
                'thumbnail',
                'excerpt',
                'comments',
                'page-attributes',
            ],
        ], $args);

        $this->postType = register_post_type($slug, $args);
        $this->slug = $slug;

        return $this;
    }

    public function setIcon($iconCode = 'f11a')
    {
        // Add Font Awesome
        add_action('admin_enqueue_scripts', function () {
            wp_enqueue_style(
                'font-awesome',
                '//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css',
                null,
                '4.4.0',
                'screen'
            );
        });

        // Output styles for icon
        add_action('admin_head', function () use ($iconCode) {
            echo '<style>';
            echo '#menu-posts-' . $this->slug . ' div.wp-menu-image:before {font-family:"FontAwesome"; content:"\\' . $iconCode . '"}';
            echo '</style>';
        }, 666);

        return $this;
    }
}
