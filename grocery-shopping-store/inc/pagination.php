<?php
/**
 *
 * Pagination Functions
 *
 * @package Grocery Shopping Store
 */

/**
 * Pagination for archive.
 */
function grocery_shopping_store_render_posts_pagination() {
    // Get the setting to check if pagination is enabled
    $grocery_shopping_store_is_pagination_enabled = get_theme_mod( 'grocery_shopping_store_enable_pagination', true );

    // Check if pagination is enabled
    if ( $grocery_shopping_store_is_pagination_enabled ) {
        // Get the selected pagination type from the Customizer
        $grocery_shopping_store_pagination_type = get_theme_mod( 'grocery_shopping_store_theme_pagination_type', 'numeric' );

        // Check if the pagination type is "newer_older" (Previous/Next) or "numeric"
        if ( 'newer_older' === $grocery_shopping_store_pagination_type ) :
            // Display "Newer/Older" pagination (Previous/Next navigation)
            the_posts_navigation(
                array(
                    'prev_text' => __( '&laquo; Newer', 'grocery-shopping-store' ),  // Change the label for "previous"
                    'next_text' => __( 'Older &raquo;', 'grocery-shopping-store' ),  // Change the label for "next"
                    'screen_reader_text' => __( 'Posts navigation', 'grocery-shopping-store' ),
                )
            );
        else :
            // Display numeric pagination (Page numbers)
            the_posts_pagination(
                array(
                    'prev_text' => __( '&laquo; Previous', 'grocery-shopping-store' ),
                    'next_text' => __( 'Next &raquo;', 'grocery-shopping-store' ),
                    'type'      => 'list', // Display as <ul> <li> tags
                    'screen_reader_text' => __( 'Posts navigation', 'grocery-shopping-store' ),
                )
            );
        endif;
    }
}
add_action( 'grocery_shopping_store_posts_pagination', 'grocery_shopping_store_render_posts_pagination', 10 );