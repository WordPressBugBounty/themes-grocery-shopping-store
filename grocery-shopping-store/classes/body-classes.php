<?php
/**
 * Body Classes.
 * @package Grocery Shopping Store
 */

if (!function_exists('grocery_shopping_store_body_classes')) :

    function grocery_shopping_store_body_classes($grocery_shopping_store_classes)
    {
        $grocery_shopping_store_defaults = grocery_shopping_store_get_default_theme_options();
        $grocery_shopping_store_layout = grocery_shopping_store_get_final_sidebar_layout();

        // Adds a class of hfeed to non-singular pages.
        if (!is_singular()) {
            $grocery_shopping_store_classes[] = 'hfeed';
        }

        // Sidebar layout logic
        $grocery_shopping_store_classes[] = $grocery_shopping_store_layout;

        // Copyright alignment
        $copyright_alignment = get_theme_mod('grocery_shopping_store_copyright_alignment', 'Default');
        $grocery_shopping_store_classes[] = 'copyright-' . strtolower($copyright_alignment);

        return $grocery_shopping_store_classes;
    }

endif;

add_filter('body_class', 'grocery_shopping_store_body_classes');