<?php

namespace sixlabs\wp;

class Tax
{
    public function register($postTypeSlug, $taxSlug, $singular, $plural, $hierarchical = true, $args = [])
    {
        register_taxonomy($taxSlug, $postTypeSlug, array_merge([
            'labels' => [
                'name'              => $plural,
                'singular_name'     => $singular,
                'search_items'      =>  sprintf('Search %s', $plural),
                'all_items'         =>  sprintf('All %s', $plural),
                'parent_item'       =>  sprintf('Parent %s', $singular),
                'parent_item_colon' =>  sprintf('Parent %s:', $singular),
                'edit_item'         =>  sprintf('Edit %s', $singular),
                'update_item'       =>  sprintf('Update %s', $singular),
                'add_new_item'      =>  sprintf('Add New %s', $singular),
                'new_item_name'     =>  sprintf('New %s Name', $singular),
                'menu_name'         =>  $singular,
            ],
            'hierarchical'      => $hierarchical,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => ['slug' => $taxSlug],
        ], $args));

        return $this;
    }
}
