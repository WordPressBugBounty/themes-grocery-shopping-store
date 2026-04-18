<?php

function grocery_shopping_store_enqueue_fonts() {
    $grocery_shopping_store_default_font_content = 'NotoSans';
    $grocery_shopping_store_default_font_heading = 'NotoSans';

    $grocery_shopping_store_font_content = esc_attr(get_theme_mod('grocery_shopping_store_content_typography_font', $grocery_shopping_store_default_font_content));
    $grocery_shopping_store_font_heading = esc_attr(get_theme_mod('grocery_shopping_store_heading_typography_font', $grocery_shopping_store_default_font_heading));

    $grocery_shopping_store_css = '';

    // Always enqueue main font
    $grocery_shopping_store_css .= '
    :root {
        --font-main: ' . $grocery_shopping_store_font_content . ', ' . (in_array($grocery_shopping_store_font_content, ['bitter', 'NotoSans']) ? 'serif' : 'sans-serif') . '!important;
    }';
    wp_enqueue_style('grocery-shopping-store-style-font-general', get_template_directory_uri() . '/fonts/' . $grocery_shopping_store_font_content . '/font.css');

    // Always enqueue header font
    $grocery_shopping_store_css .= '
    :root {
        --font-head: ' . $grocery_shopping_store_font_heading . ', ' . (in_array($grocery_shopping_store_font_heading, ['bitter', 'NotoSans']) ? 'serif' : 'sans-serif') . '!important;
    }';
    wp_enqueue_style('grocery-shopping-store-style-font-h', get_template_directory_uri() . '/fonts/' . $grocery_shopping_store_font_heading . '/font.css');

    // Add inline style
    wp_add_inline_style('grocery-shopping-store-style-font-general', $grocery_shopping_store_css);
}
add_action('wp_enqueue_scripts', 'grocery_shopping_store_enqueue_fonts', 50);