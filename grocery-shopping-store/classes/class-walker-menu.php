<?php
/**
 * Custom page walker for this theme.
 *
 * @package Grocery Shopping Store
 */

if (!class_exists('grocery_shopping_store_Walker_Page')) {
    /**
     * CUSTOM PAGE WALKER
     * A custom walker for pages.
     */
    class grocery_shopping_store_Walker_Page extends Walker_Page
    {

        /**
         * Outputs the beginning of the current element in the tree.
         *
         * @param string $grocery_shopping_store_output Used to append additional content. Passed by reference.
         * @param WP_Post $page Page data object.
         * @param int $grocery_shopping_store_depth Optional. Depth of page. Used for padding. Default 0.
         * @param array $grocery_shopping_store_args Optional. Array of arguments. Default empty array.
         * @param int $current_page Optional. Page ID. Default 0.
         * @since 2.1.0
         *
         * @see Walker::start_el()
         */

        public function start_lvl( &$grocery_shopping_store_output, $grocery_shopping_store_depth = 0, $grocery_shopping_store_args = array() ) {
            $grocery_shopping_store_indent  = str_repeat( "\t", $grocery_shopping_store_depth );
            $grocery_shopping_store_output .= "$grocery_shopping_store_indent<ul class='sub-menu'>\n";
        }

        public function start_el(&$grocery_shopping_store_output, $page, $grocery_shopping_store_depth = 0, $grocery_shopping_store_args = array(), $current_page = 0)
        {

            if (isset($grocery_shopping_store_args['item_spacing']) && 'preserve' === $grocery_shopping_store_args['item_spacing']) {
                $t = "\t";
                $n = "\n";
            } else {
                $t = '';
                $n = '';
            }
            if ($grocery_shopping_store_depth) {
                $grocery_shopping_store_indent = str_repeat($t, $grocery_shopping_store_depth);
            } else {
                $grocery_shopping_store_indent = '';
            }

            $grocery_shopping_store_css_class = array('page_item', 'page-item-' . $page->ID);

            if (isset($grocery_shopping_store_args['pages_with_children'][$page->ID])) {
                $grocery_shopping_store_css_class[] = 'page_item_has_children';
            }

            if (!empty($current_page)) {
                $_current_page = get_post($current_page);
                if ($_current_page && in_array($page->ID, $_current_page->ancestors, true)) {
                    $grocery_shopping_store_css_class[] = 'current_page_ancestor';
                }
                if ($page->ID === $current_page) {
                    $grocery_shopping_store_css_class[] = 'current_page_item';
                } elseif ($_current_page && $page->ID === $_current_page->post_parent) {
                    $grocery_shopping_store_css_class[] = 'current_page_parent';
                }
            } elseif (get_option('page_for_posts') === $page->ID) {
                $grocery_shopping_store_css_class[] = 'current_page_parent';
            }

            /** This filter is documented in wp-includes/class-walker-page.php */
            $grocery_shopping_store_css_classes = implode(' ', apply_filters('page_css_class', $grocery_shopping_store_css_class, $page, $grocery_shopping_store_depth, $grocery_shopping_store_args, $current_page));
            $grocery_shopping_store_css_classes = $grocery_shopping_store_css_classes ? ' class="' . esc_attr($grocery_shopping_store_css_classes) . '"' : '';

            if ('' === $page->post_title) {
                /* translators: %d: ID of a post. */
                $page->post_title = sprintf(__('#%d (no title)', 'grocery-shopping-store'), $page->ID);
            }

            $grocery_shopping_store_args['link_before'] = empty($grocery_shopping_store_args['link_before']) ? '' : $grocery_shopping_store_args['link_before'];
            $grocery_shopping_store_args['link_after'] = empty($grocery_shopping_store_args['link_after']) ? '' : $grocery_shopping_store_args['link_after'];

            $grocery_shopping_store_atts = array();
            $grocery_shopping_store_atts['href'] = get_permalink($page->ID);
            $grocery_shopping_store_atts['aria-current'] = ($page->ID === $current_page) ? 'page' : '';

            /** This filter is documented in wp-includes/class-walker-page.php */
            $grocery_shopping_store_atts = apply_filters('page_menu_link_attributes', $grocery_shopping_store_atts, $page, $grocery_shopping_store_depth, $grocery_shopping_store_args, $current_page);

            $grocery_shopping_store_attributes = '';
            foreach ($grocery_shopping_store_atts as $attr => $grocery_shopping_store_value) {
                if (!empty($grocery_shopping_store_value)) {
                    $grocery_shopping_store_value = ('href' === $attr) ? esc_url($grocery_shopping_store_value) : esc_attr($grocery_shopping_store_value);
                    $grocery_shopping_store_attributes .= ' ' . $attr . '="' . $grocery_shopping_store_value . '"';
                }
            }

            $grocery_shopping_store_args['list_item_before'] = '';
            $grocery_shopping_store_args['list_item_after'] = '';
            $grocery_shopping_store_args['icon_rennder'] = '';
            // Wrap the link in a div and append a sub menu toggle.
            if (isset($grocery_shopping_store_args['show_toggles']) && true === $grocery_shopping_store_args['show_toggles']) {
                // Wrap the menu item link contents in a div, used for positioning.
                $grocery_shopping_store_args['list_item_after'] = '';
            }


            // Add icons to menu items with children.
            if (isset($grocery_shopping_store_args['show_sub_menu_icons']) && true === $grocery_shopping_store_args['show_sub_menu_icons']) {
                if (isset($grocery_shopping_store_args['pages_with_children'][$page->ID])) {
                    $grocery_shopping_store_args['icon_rennder'] = '';
                }
            }

            // Add icons to menu items with children.
            if (isset($grocery_shopping_store_args['show_toggles']) && true === $grocery_shopping_store_args['show_toggles']) {
                if (isset($grocery_shopping_store_args['pages_with_children'][$page->ID])) {

                    $toggle_target_string = '.page_item.page-item-' . $page->ID . ' > .sub-menu';

                    $grocery_shopping_store_args['list_item_after'] = '<button type="button" class="theme-aria-button submenu-toggle" data-toggle-target="' . $toggle_target_string . '" data-toggle-type="slidetoggle" data-toggle-duration="250"><span class="btn__content" tabindex="-1"><span class="screen-reader-text">' . __( 'Show sub menu', 'grocery-shopping-store' ) . '</span>' . grocery_shopping_store_get_theme_svg( 'chevron-down' ) . '</span></button>';
                }
            }

            if (isset($grocery_shopping_store_args['show_toggles']) && true === $grocery_shopping_store_args['show_toggles']) {

                $grocery_shopping_store_output .= $grocery_shopping_store_indent . sprintf(
                        '<li%s>%s%s<a%s>%s%s%s</a>%s%s',
                        $grocery_shopping_store_css_classes,
                        '<div class="submenu-wrapper">',
                        $grocery_shopping_store_args['list_item_before'],
                        $grocery_shopping_store_attributes,
                        $grocery_shopping_store_args['link_before'],
                        /** This filter is documented in wp-includes/post-template.php */
                        apply_filters('the_title', $page->post_title, $page->ID),
                        $grocery_shopping_store_args['link_after'],
                        $grocery_shopping_store_args['list_item_after'],
                        '</div>'
                    );

            }else{

                $grocery_shopping_store_output .= $grocery_shopping_store_indent . sprintf(
                        '<li%s>%s<a%s>%s%s%s%s</a>%s',
                        $grocery_shopping_store_css_classes,
                        $grocery_shopping_store_args['list_item_before'],
                        $grocery_shopping_store_attributes,
                        $grocery_shopping_store_args['link_before'],
                        /** This filter is documented in wp-includes/post-template.php */
                        apply_filters('the_title', $page->post_title, $page->ID),
                        $grocery_shopping_store_args['icon_rennder'],
                        $grocery_shopping_store_args['link_after'],
                        $grocery_shopping_store_args['list_item_after']
                    );

            }

            if (!empty($grocery_shopping_store_args['show_date'])) {
                if ('modified' === $grocery_shopping_store_args['show_date']) {
                    $grocery_shopping_store_time = $page->post_modified;
                } else {
                    $grocery_shopping_store_time = $page->post_date;
                }

                $grocery_shopping_store_date_format = empty($grocery_shopping_store_args['date_format']) ? '' : $grocery_shopping_store_args['date_format'];
                $grocery_shopping_store_output .= ' ' . mysql2date($grocery_shopping_store_date_format, $grocery_shopping_store_time);
            }
        }
    }
}