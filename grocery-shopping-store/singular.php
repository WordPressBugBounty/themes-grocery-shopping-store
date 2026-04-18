<?php
/**
 * The template for displaying single posts and pages.
 * @package Grocery Shopping Store
 * @since 1.0.0
 */

get_header();

$grocery_shopping_store_default = grocery_shopping_store_get_default_theme_options();
$grocery_shopping_store_global_layout = get_theme_mod('grocery_shopping_store_global_sidebar_layout', $grocery_shopping_store_default['grocery_shopping_store_global_sidebar_layout']);
$grocery_shopping_store_page_layout = get_theme_mod('grocery_shopping_store_page_sidebar_layout', $grocery_shopping_store_global_layout);
$grocery_shopping_store_post_layout = get_theme_mod('grocery_shopping_store_post_sidebar_layout', $grocery_shopping_store_global_layout);
$grocery_shopping_store_post_meta = get_post_meta(get_the_ID(), 'grocery_shopping_store_post_sidebar_option', true);

$grocery_shopping_store_final_layout = $grocery_shopping_store_global_layout;
if (!empty($grocery_shopping_store_post_meta) && $grocery_shopping_store_post_meta !== 'default') {
    $grocery_shopping_store_final_layout = $grocery_shopping_store_post_meta;
} elseif (is_page() || (function_exists('is_shop') && is_shop())) {
    $grocery_shopping_store_final_layout = $grocery_shopping_store_page_layout;
} elseif (is_single()) {
    $grocery_shopping_store_final_layout = $grocery_shopping_store_post_layout;
}

// Set content column order based on sidebar layout
$grocery_shopping_store_sidebar_column_class = 'column-order-1';
if ($grocery_shopping_store_final_layout === 'left-sidebar') {
    $grocery_shopping_store_sidebar_column_class = 'column-order-2';
}

?>

<div id="single-page" class="singular-main-block">
    <div class="wrapper">
        <div class="column-row <?php echo esc_attr($grocery_shopping_store_final_layout === 'no-sidebar' ? 'no-sidebar-layout' : ''); ?>">

            <?php if ($grocery_shopping_store_final_layout === 'left-sidebar') : ?>
                <?php get_sidebar(); ?>
            <?php endif; ?>

            <div id="primary" class="content-area <?php echo esc_attr($grocery_shopping_store_final_layout === 'no-sidebar' ? 'full-width-content' : $grocery_shopping_store_sidebar_column_class); ?>">
                <main id="site-content" role="main">

                    <?php
                    grocery_shopping_store_breadcrumb(); // Display breadcrumb

                    if (have_posts()) : ?>

                        <div class="article-wraper">
                            <?php while (have_posts()) : the_post(); ?>

                                <?php get_template_part('template-parts/content', 'single'); ?>

                                <?php if ((is_single() || is_page()) && (comments_open() || get_comments_number()) && !post_password_required()) : ?>
                                    <div class="comments-wrapper">
                                        <?php comments_template(); ?>
                                    </div>
                                <?php endif; ?>

                            <?php endwhile; ?>
                        </div>

                    <?php else : ?>

                        <?php get_template_part('template-parts/content', 'none'); ?>

                    <?php endif;

                    do_action('grocery_shopping_store_navigation_action');
                    ?>

                </main>
            </div>

            <?php if ($grocery_shopping_store_final_layout === 'right-sidebar') : ?>
                <?php get_sidebar(); ?>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php get_footer(); ?>