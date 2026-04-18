<?php
$grocery_shopping_store_layout = grocery_shopping_store_get_final_sidebar_layout();
$grocery_shopping_store_sidebar_class = 'column-order-1';

if ( $grocery_shopping_store_layout === 'left-sidebar' ) {
    $grocery_shopping_store_sidebar_class = 'column-order-1';
} elseif ( $grocery_shopping_store_layout === 'right-sidebar' ) {
    $grocery_shopping_store_sidebar_class = 'column-order-2';
}

if ( $grocery_shopping_store_layout !== 'no-sidebar' ) : ?>
    <aside id="secondary" class="widget-area <?php echo esc_attr( $grocery_shopping_store_sidebar_class ); ?>">
        <div class="widget-area-wrapper">
            <?php if ( is_active_sidebar('sidebar-1') ) : ?>
                <?php dynamic_sidebar( 'sidebar-1' ); ?>
            <?php else : ?>
                <!-- Default widgets -->
                <div class="widget widget_block widget_search">
                    <h3 class="widget-title"><?php esc_html_e('Search', 'grocery-shopping-store'); ?></h3>
                    <?php get_search_form(); ?>
                </div>

                <div class="widget widget_pages">
                    <h3 class="widget-title"><?php esc_html_e('Pages', 'grocery-shopping-store'); ?></h3>
                    <ul>
                        <?php
                        wp_list_pages(array(
                            'title_li' => '',
                        ));
                        ?>
                    </ul>
                </div>

                <div class="widget widget_archive">
                    <h3 class="widget-title"><?php esc_html_e('Archives', 'grocery-shopping-store'); ?></h3>
                    <ul>
                        <?php wp_get_archives(['type' => 'monthly', 'show_post_count' => true]); ?>
                    </ul>
                </div>

                <div class="widget widget_categories">
                    <h3 class="widget-title"><?php esc_html_e('Categories', 'grocery-shopping-store'); ?></h3>
                    <ul class="wp-block-categories-list wp-block-categories">
                        <?php wp_list_categories(['orderby' => 'name', 'title_li' => '', 'show_count' => true]); ?>
                    </ul>
                </div>

                <div class="widget widget_tag_cloud">
                    <h3 class="widget-title"><?php esc_html_e('Tags', 'grocery-shopping-store'); ?></h3>
                    <?php
                    $grocery_shopping_store_tags = get_tags();
                    if ( $grocery_shopping_store_tags ) {
                        echo '<div class="tagcloud">';
                        foreach ( $grocery_shopping_store_tags as $grocery_shopping_store_tag ) {
                            $grocery_shopping_store_link = get_tag_link($grocery_shopping_store_tag->term_id);
                            echo '<a href="' . esc_url($grocery_shopping_store_link) . '" class="tag-cloud-link">' . esc_html($grocery_shopping_store_tag->name) . '</a> ';
                        }
                        echo '</div>';
                    } else {
                        echo '<p>' . esc_html__('No tags found.', 'grocery-shopping-store') . '</p>';
                    }
                    ?>
                </div>

            <?php endif; ?>
        </div>
    </aside>
<?php endif; ?>
