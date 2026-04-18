<?php
/**
 * Custom Functions
 * @package Grocery Shopping Store
 * @since 1.0.0
 */

if( !function_exists('grocery_shopping_store_site_logo') ):

    /**
     * Logo & Description
     */
    /**
     * Displays the site logo, either text or image.
     *
     * @param array $grocery_shopping_store_args Arguments for displaying the site logo either as an image or text.
     * @param boolean $grocery_shopping_store_echo Echo or return the HTML.
     *
     * @return string $grocery_shopping_store_html Compiled HTML based on our arguments.
     */
    function grocery_shopping_store_site_logo( $grocery_shopping_store_args = array(), $grocery_shopping_store_echo = true ){
        $grocery_shopping_store_logo = get_custom_logo();
        $grocery_shopping_store_site_title = get_bloginfo('name');
        $grocery_shopping_store_contents = '';
        $grocery_shopping_store_classname = '';
        $grocery_shopping_store_defaults = array(
            'logo' => '%1$s<span class="screen-reader-text">%2$s</span>',
            'logo_class' => 'site-logo site-branding',
            'title' => '<a href="%1$s" class="custom-logo-name">%2$s</a>',
            'title_class' => 'site-title',
            'home_wrap' => '<h1 class="%1$s">%2$s</h1>',
            'single_wrap' => '<div class="%1$s">%2$s</div>',
            'condition' => (is_front_page() || is_home()) && !is_page(),
        );
        $grocery_shopping_store_args = wp_parse_args($grocery_shopping_store_args, $grocery_shopping_store_defaults);
        /**
         * Filters the arguments for `grocery_shopping_store_site_logo()`.
         *
         * @param array $grocery_shopping_store_args Parsed arguments.
         * @param array $grocery_shopping_store_defaults Function's default arguments.
         */
        $grocery_shopping_store_args = apply_filters('grocery_shopping_store_site_logo_args', $grocery_shopping_store_args, $grocery_shopping_store_defaults);
        
        $grocery_shopping_store_show_logo  = get_theme_mod('grocery_shopping_store_display_logo', false);
        $grocery_shopping_store_show_title = get_theme_mod('grocery_shopping_store_display_title', true);

        if ( has_custom_logo() && $grocery_shopping_store_show_logo ) {
            $grocery_shopping_store_contents .= sprintf($grocery_shopping_store_args['logo'], $grocery_shopping_store_logo, esc_html($grocery_shopping_store_site_title));
            $grocery_shopping_store_classname = $grocery_shopping_store_args['logo_class'];
        }

        if ( $grocery_shopping_store_show_title ) {
            $grocery_shopping_store_contents .= sprintf($grocery_shopping_store_args['title'], esc_url(get_home_url(null, '/')), esc_html($grocery_shopping_store_site_title));
            // If logo isn't shown, fallback to title class for wrapper.
            if ( !$grocery_shopping_store_show_logo ) {
                $grocery_shopping_store_classname = $grocery_shopping_store_args['title_class'];
            }
        }

        // If nothing is shown (logo or title both disabled), exit early
        if ( empty($grocery_shopping_store_contents) ) {
            return;
        }

        $grocery_shopping_store_wrap = $grocery_shopping_store_args['condition'] ? 'home_wrap' : 'single_wrap';
        // $grocery_shopping_store_wrap = 'home_wrap';
        $grocery_shopping_store_html = sprintf($grocery_shopping_store_args[$grocery_shopping_store_wrap], $grocery_shopping_store_classname, $grocery_shopping_store_contents);
        /**
         * Filters the arguments for `grocery_shopping_store_site_logo()`.
         *
         * @param string $grocery_shopping_store_html Compiled html based on our arguments.
         * @param array $grocery_shopping_store_args Parsed arguments.
         * @param string $grocery_shopping_store_classname Class name based on current view, home or single.
         * @param string $grocery_shopping_store_contents HTML for site title or logo.
         */
        $grocery_shopping_store_html = apply_filters('grocery_shopping_store_site_logo', $grocery_shopping_store_html, $grocery_shopping_store_args, $grocery_shopping_store_classname, $grocery_shopping_store_contents);
        if (!$grocery_shopping_store_echo) {
            return $grocery_shopping_store_html;
        }
        echo $grocery_shopping_store_html; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

    }

endif;

if( !function_exists('grocery_shopping_store_site_description') ):

    /**
     * Displays the site description.
     *
     * @param boolean $grocery_shopping_store_echo Echo or return the html.
     *
     * @return string $grocery_shopping_store_html The HTML to display.
     */
    function grocery_shopping_store_site_description($grocery_shopping_store_echo = true){

        if ( get_theme_mod('grocery_shopping_store_display_header_text', false) == true ) :
        $grocery_shopping_store_description = get_bloginfo('description');
        if (!$grocery_shopping_store_description) {
            return;
        }
        $grocery_shopping_store_wrapper = '<div class="site-description"><span>%s</span></div><!-- .site-description -->';
        $grocery_shopping_store_html = sprintf($grocery_shopping_store_wrapper, esc_html($grocery_shopping_store_description));
        /**
         * Filters the html for the site description.
         *
         * @param string $grocery_shopping_store_html The HTML to display.
         * @param string $grocery_shopping_store_description Site description via `bloginfo()`.
         * @param string $grocery_shopping_store_wrapper The format used in case you want to reuse it in a `sprintf()`.
         * @since 1.0.0
         *
         */
        $grocery_shopping_store_html = apply_filters('grocery_shopping_store_site_description', $grocery_shopping_store_html, $grocery_shopping_store_description, $grocery_shopping_store_wrapper);
        if (!$grocery_shopping_store_echo) {
            return $grocery_shopping_store_html;
        }
        echo $grocery_shopping_store_html; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        endif;
    }

endif;

if( !function_exists('grocery_shopping_store_posted_on') ):

    /**
     * Prints HTML with meta information for the current post-date/time.
     */
    function grocery_shopping_store_posted_on( $grocery_shopping_store_icon = true, $grocery_shopping_store_animation_class = '' ){

        $grocery_shopping_store_default = grocery_shopping_store_get_default_theme_options();
        $grocery_shopping_store_post_date = absint( get_theme_mod( 'grocery_shopping_store_post_date',$grocery_shopping_store_default['grocery_shopping_store_post_date'] ) );

        if( $grocery_shopping_store_post_date ){

            $grocery_shopping_store_time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
            if (get_the_time('U') !== get_the_modified_time('U')) {
                $grocery_shopping_store_time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
            }

            $grocery_shopping_store_time_string = sprintf($grocery_shopping_store_time_string,
                esc_attr(get_the_date(DATE_W3C)),
                esc_html(get_the_date()),
                esc_attr(get_the_modified_date(DATE_W3C)),
                esc_html(get_the_modified_date())
            );

            $grocery_shopping_store_year = get_the_date('Y');
            $grocery_shopping_store_month = get_the_date('m');
            $grocery_shopping_store_day = get_the_date('d');
            $grocery_shopping_store_link = get_day_link($grocery_shopping_store_year, $grocery_shopping_store_month, $grocery_shopping_store_day);

            $grocery_shopping_store_posted_on = '<a href="' . esc_url($grocery_shopping_store_link) . '" rel="bookmark">' . $grocery_shopping_store_time_string . '</a>';

            echo '<div class="entry-meta-item entry-meta-date">';
            echo '<div class="entry-meta-wrapper '.esc_attr( $grocery_shopping_store_animation_class ).'">';

            if( $grocery_shopping_store_icon ){

                echo '<span class="entry-meta-icon calendar-icon"> ';
                grocery_shopping_store_the_theme_svg('calendar');
                echo '</span>';

            }

            echo '<span class="posted-on">' . $grocery_shopping_store_posted_on . '</span>'; // WPCS: XSS OK.
            echo '</div>';
            echo '</div>';

        }

    }

endif;

if( !function_exists('grocery_shopping_store_posted_by') ) :

    /**
     * Prints HTML with meta information for the current author.
     */
    function grocery_shopping_store_posted_by( $grocery_shopping_store_icon = true, $grocery_shopping_store_animation_class = '' ){   

        $grocery_shopping_store_default = grocery_shopping_store_get_default_theme_options();
        $grocery_shopping_store_post_author = absint( get_theme_mod( 'grocery_shopping_store_post_author',$grocery_shopping_store_default['grocery_shopping_store_post_author'] ) );

        if( $grocery_shopping_store_post_author ){

            echo '<div class="entry-meta-item entry-meta-author">';
            echo '<div class="entry-meta-wrapper '.esc_attr( $grocery_shopping_store_animation_class ).'">';

            if( $grocery_shopping_store_icon ){
            
                echo '<span class="entry-meta-icon author-icon"> ';
                grocery_shopping_store_the_theme_svg('user');
                echo '</span>';
                
            }

            $grocery_shopping_store_byline = '<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta('ID') ) ) . '">' . esc_html(get_the_author()) . '</a></span>';
            echo '<span class="byline"> ' . $grocery_shopping_store_byline . '</span>'; // WPCS: XSS OK.
            echo '</div>';
            echo '</div>';

        }

    }

endif;


if( !function_exists('grocery_shopping_store_posted_by_avatar') ) :

    /**
     * Prints HTML with meta information for the current author.
     */
    function grocery_shopping_store_posted_by_avatar( $date = false ){

        $grocery_shopping_store_default = grocery_shopping_store_get_default_theme_options();
        $grocery_shopping_store_post_author = absint( get_theme_mod( 'grocery_shopping_store_post_author',$grocery_shopping_store_default['grocery_shopping_store_post_author'] ) );

        if( $grocery_shopping_store_post_author ){



            echo '<div class="entry-meta-left">';
            echo '<div class="entry-meta-item entry-meta-avatar">';
            echo wp_kses_post( get_avatar( get_the_author_meta( 'ID' ) ) );
            echo '</div>';
            echo '</div>';

            echo '<div class="entry-meta-right">';

            $grocery_shopping_store_byline = '<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta('ID') ) ) . '">' . esc_html(get_the_author()) . '</a></span>';

            echo '<div class="entry-meta-item entry-meta-byline"> ' . $grocery_shopping_store_byline . '</div>';

            if( $date ){
                grocery_shopping_store_posted_on($grocery_shopping_store_icon = false);
            }
            echo '</div>';

        }

    }

endif;

if( !function_exists('grocery_shopping_store_entry_footer') ):

    /**
     * Prints HTML with meta information for the categories, tags and comments.
     */
    function grocery_shopping_store_entry_footer( $grocery_shopping_store_cats = true, $grocery_shopping_store_tags = true, $grocery_shopping_store_edits = true){   

        $grocery_shopping_store_default = grocery_shopping_store_get_default_theme_options();
        $grocery_shopping_store_post_category = absint( get_theme_mod( 'grocery_shopping_store_post_category',$grocery_shopping_store_default['grocery_shopping_store_post_category'] ) );
        $grocery_shopping_store_post_tags = absint( get_theme_mod( 'grocery_shopping_store_post_tags',$grocery_shopping_store_default['grocery_shopping_store_post_tags'] ) );

        // Hide category and tag text for pages.
        if ('post' === get_post_type()) {

            if( $grocery_shopping_store_cats && $grocery_shopping_store_post_category ){

                /* translators: used between list items, there is a space after the comma */
                $grocery_shopping_store_categories = get_the_category();
                if ($grocery_shopping_store_categories) {
                    echo '<div class="entry-meta-item entry-meta-categories">';
                    echo '<div class="entry-meta-wrapper">';
                
                    /* translators: 1: list of categories. */
                    echo '<span class="cat-links">';
                    foreach( $grocery_shopping_store_categories as $grocery_shopping_store_category ){

                        $grocery_shopping_store_cat_name = $grocery_shopping_store_category->name;
                        $grocery_shopping_store_cat_slug = $grocery_shopping_store_category->slug;
                        $grocery_shopping_store_cat_url = get_category_link( $grocery_shopping_store_category->term_id );
                        ?>

                        <a class="twp_cat_<?php echo esc_attr( $grocery_shopping_store_cat_slug ); ?>" href="<?php echo esc_url( $grocery_shopping_store_cat_url ); ?>" rel="category tag"><?php echo esc_html( $grocery_shopping_store_cat_name ); ?></a>

                    <?php }
                    echo '</span>'; // WPCS: XSS OK.
                    echo '</div>';
                    echo '</div>';
                }

            }

            if( $grocery_shopping_store_tags && $grocery_shopping_store_post_tags ){
                /* translators: used between list items, there is a space after the comma */
                $grocery_shopping_store_tags_list = get_the_tag_list('', esc_html_x(', ', 'list item separator', 'grocery-shopping-store'));
                if( $grocery_shopping_store_tags_list ){

                    echo '<div class="entry-meta-item entry-meta-tags">';
                    echo '<div class="entry-meta-wrapper">';

                    /* translators: 1: list of tags. */
                    echo '<span class="tags-links">';
                    echo wp_kses_post($grocery_shopping_store_tags_list) . '</span>'; // WPCS: XSS OK.
                    echo '</div>';
                    echo '</div>';

                }

            }

            if( $grocery_shopping_store_edits ){

                edit_post_link(
                    sprintf(
                        wp_kses(
                        /* translators: %s: Name of current post. Only visible to screen readers */
                            __('Edit <span class="screen-reader-text">%s</span>', 'grocery-shopping-store'),
                            array(
                                'span' => array(
                                    'class' => array(),
                                ),
                            )
                        ),
                        get_the_title()
                    ),
                    '<span class="edit-link">',
                    '</span>'
                );
            }

        }
    }

endif;

if ( ! function_exists( 'grocery_shopping_store_post_thumbnail' ) ) :

    /**
     * Displays an optional post thumbnail.
     *
     * Shows background style image with height class on archive/search/front,
     * and a normal inline image on single post/page views.
     */
    function grocery_shopping_store_post_thumbnail( $grocery_shopping_store_image_size = 'medium' ) {

        if ( post_password_required() || is_attachment() ) {
            return;
        }

        // Fallback image path
        $grocery_shopping_store_default_image = get_template_directory_uri() . '/inc/homepage-setup/assets/homepage-setup-images/Tomato.png';

        // Image size → height class map
        $grocery_shopping_store_size_class_map = array(
            'full'      => 'data-bg-large',
            'large'     => 'data-bg-big',
            'medium'    => 'data-bg-medium',
            'small'     => 'data-bg-small',
            'xsmall'    => 'data-bg-xsmall',
            'thumbnail' => 'data-bg-thumbnail',
        );

        $grocery_shopping_store_class = isset( $grocery_shopping_store_size_class_map[ $grocery_shopping_store_image_size ] )
            ? $grocery_shopping_store_size_class_map[ $grocery_shopping_store_image_size ]
            : 'data-bg-medium';

        if ( is_singular() ) {
            the_post_thumbnail();
        } else {
            // 🔵 On archives → use background image style
            $grocery_shopping_store_image = has_post_thumbnail()
                ? wp_get_attachment_image_src( get_post_thumbnail_id(), $grocery_shopping_store_image_size )
                : array( $grocery_shopping_store_default_image );

            $grocery_shopping_store_bg_image = isset( $grocery_shopping_store_image[0] ) ? $grocery_shopping_store_image[0] : $grocery_shopping_store_default_image;
            ?>
            <div class="post-thumbnail data-bg <?php echo esc_attr( $grocery_shopping_store_class ); ?>"
                 data-background="<?php echo esc_url( $grocery_shopping_store_bg_image ); ?>">
                <a href="<?php the_permalink(); ?>" class="theme-image-responsive" tabindex="0"></a>
            </div>
            <?php
        }
    }

endif;

if( !function_exists('grocery_shopping_store_is_comment_by_post_author') ):

    /**
     * Comments
     */
    /**
     * Check if the specified comment is written by the author of the post commented on.
     *
     * @param object $grocery_shopping_store_comment Comment data.
     *
     * @return bool
     */
    function grocery_shopping_store_is_comment_by_post_author($grocery_shopping_store_comment = null){

        if (is_object($grocery_shopping_store_comment) && $grocery_shopping_store_comment->user_id > 0) {
            $grocery_shopping_store_user = get_userdata($grocery_shopping_store_comment->user_id);
            $post = get_post($grocery_shopping_store_comment->comment_post_ID);
            if (!empty($grocery_shopping_store_user) && !empty($post)) {
                return $grocery_shopping_store_comment->user_id === $post->post_author;
            }
        }
        return false;
    }

endif;

if( !function_exists('grocery_shopping_store_breadcrumb') ) :

    /**
     * Grocery Shopping Store Breadcrumb
     */
    function grocery_shopping_store_breadcrumb($grocery_shopping_store_comment = null){

        echo '<div class="entry-breadcrumb">';
        grocery_shopping_store_breadcrumb_trail();
        echo '</div>';

    }

endif;