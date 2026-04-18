<?php
/**
 * The default template for displaying content
 * @package Grocery Shopping Store
 * @since 1.0.0
 */

$grocery_shopping_store_default = grocery_shopping_store_get_default_theme_options();
$grocery_shopping_store_image_size = get_theme_mod('grocery_shopping_store_archive_image_size', 'medium');
global $grocery_shopping_store_archive_first_class; 
$grocery_shopping_store_archive_classes = [
    'theme-article-post',
    'theme-article-animate',
    $grocery_shopping_store_archive_first_class
];?>

<article id="post-<?php the_ID(); ?>" <?php post_class($grocery_shopping_store_archive_classes); ?>>
    <div class="theme-article-image">
        <?php if ( get_theme_mod('grocery_shopping_store_display_archive_post_image', true) == true ) : ?>
            <div class="entry-thumbnail">
                <?php
                if ( is_search() || is_archive() || is_front_page() ) {

                    $grocery_shopping_store_image_size = get_theme_mod('grocery_shopping_store_archive_image_size', 'medium');

                    $grocery_shopping_store_image_size_class_map = array(
                        'full'      => 'data-bg-large',
                        'large'     => 'data-bg-big',
                        'medium'    => 'data-bg-medium',
                        'small'     => 'data-bg-small',
                        'xsmall'    => 'data-bg-xsmall',
                        'thumbnail' => 'data-bg-thumbnail',
                    );

                    $grocery_shopping_store_image_size_class = isset( $grocery_shopping_store_image_size_class_map[ $grocery_shopping_store_image_size ] )
                        ? $grocery_shopping_store_image_size_class_map[ $grocery_shopping_store_image_size ]
                        : 'data-bg-medium';

                    if ( has_post_thumbnail() ) {
                        $grocery_shopping_store_featured_image = wp_get_attachment_image_src( get_post_thumbnail_id(), $grocery_shopping_store_image_size );
                        $grocery_shopping_store_featured_image = isset( $grocery_shopping_store_featured_image[0] ) ? $grocery_shopping_store_featured_image[0] : '';
                    } else {
                        $grocery_shopping_store_featured_image = get_template_directory_uri() . '/inc/homepage-setup/assets/homepage-setup-images/Tomato.png';
                    }
                    ?>
                    <div class="post-thumbnail data-bg <?php echo esc_attr( $grocery_shopping_store_image_size_class ); ?>"
                        data-background="<?php echo esc_url( $grocery_shopping_store_featured_image ); ?>">
                        <a href="<?php the_permalink(); ?>" class="theme-image-responsive" tabindex="0"></a>
                    </div>
                    <?php
                } else {
                    grocery_shopping_store_post_thumbnail( $grocery_shopping_store_image_size );
                }

                if ( get_theme_mod( 'grocery_shopping_store_display_archive_post_format_icon', true ) ) :
                    grocery_shopping_store_post_format_icon();
                endif;
                ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="theme-article-details">
        <?php if ( get_theme_mod('grocery_shopping_store_display_archive_post_category', true) == true ) : ?>  
            <div class="entry-meta-top">
                <div class="entry-meta">
                    <?php grocery_shopping_store_entry_footer($cats = true, $tags = false, $edits = false); ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if ( get_theme_mod('grocery_shopping_store_display_archive_post_title', true) == true ) : ?>
            <header class="entry-header">
                <h2 class="entry-title entry-title-medium">
                    <a href="<?php the_permalink(); ?>" rel="bookmark">
                        <span><?php the_title(); ?></span>
                    </a>
                </h2>
            </header>
        <?php endif; ?>
        <?php if ( get_theme_mod('grocery_shopping_store_display_archive_post_content', true) == true ) : ?>
            <div class="entry-content">

                <?php
                if (has_excerpt()) {

                    the_excerpt();

                } else {

                    echo '<p>';
                    echo esc_html(wp_trim_words(get_the_content(), get_theme_mod('grocery_shopping_store_excerpt_limit', 20), '...'));
                    echo '</p>';
                }

                wp_link_pages(array(
                    'before' => '<div class="page-links">' . esc_html__('Pages:', 'grocery-shopping-store'),
                    'after' => '</div>',
                )); ?>
            </div>
        <?php endif; ?>
        <?php if ( get_theme_mod('grocery_shopping_store_display_archive_post_button', true) == true ) : ?>
            <a href="<?php the_permalink(); ?>" rel="bookmark" class="theme-btn-link">
            <span> <?php esc_html_e('Read More', 'grocery-shopping-store'); ?> </span>
            <span class="topbar-info-icon"><?php grocery_shopping_store_the_theme_svg('arrow-right-1'); ?></span>
            </a>
        <?php endif; ?>
    </div>
</article>