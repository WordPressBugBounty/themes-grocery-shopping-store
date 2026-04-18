<?php
/**
 * The template for displaying 404 pages (not found)
 * @package Grocery Shopping Store
 */
get_header();

$grocery_shopping_store_default = grocery_shopping_store_get_default_theme_options();

?>
    <div class="singular-main-block">
        <section class="theme-custom-block theme-error-section error-block-heading">
            <div class="wrapper">
                <div class="theme-area-header">
                    <div class="theme-area-headlines">
                        <?php 
                            $grocery_shopping_store_404_main_title = get_theme_mod( 'grocery_shopping_store_404_main_title', $grocery_shopping_store_default['grocery_shopping_store_404_main_title'] ); 
                        ?>
                        <h2 class="theme-area-title"><?php echo esc_html( $grocery_shopping_store_404_main_title ); ?></h2>
                        <div class="theme-animated-line"></div>
                    </div>
                </div>
            </div>
        </section>
        <section class="theme-custom-block theme-error-sectiontheme-error-section error-block-middle">
            <div class="wrapper">
                <div class="column-row">
                    <div class="column column-12">
                        <?php 
                            $grocery_shopping_store_404_subtitle_one = get_theme_mod( 'grocery_shopping_store_404_subtitle_one', $grocery_shopping_store_default['grocery_shopping_store_404_subtitle_one'] ); 
                        ?>
                        <h2><?php echo esc_html( $grocery_shopping_store_404_subtitle_one ); ?></h2>
                        
                        <?php 
                            $grocery_shopping_store_404_para_one = get_theme_mod( 'grocery_shopping_store_404_para_one', $grocery_shopping_store_default['grocery_shopping_store_404_para_one'] ); 
                        ?>
                        <p><?php echo esc_html( $grocery_shopping_store_404_para_one ); ?> 
                            <a href="<?php echo esc_url( home_url() ); ?>"><?php esc_html_e('Homepage','grocery-shopping-store'); ?></a>
                        </p>
                    </div>
                </div>
            </div>
            <div class="wrapper">
                <div class="column-row">
                    <div class="column column-12">
                        <?php 
                            $grocery_shopping_store_404_subtitle_two = get_theme_mod( 'grocery_shopping_store_404_subtitle_two', $grocery_shopping_store_default['grocery_shopping_store_404_subtitle_two'] ); 
                        ?>
                        <h2><?php echo esc_html( $grocery_shopping_store_404_subtitle_two ); ?></h2>
                        
                        <?php 
                            $grocery_shopping_store_404_para_two = get_theme_mod( 'grocery_shopping_store_404_para_two', $grocery_shopping_store_default['grocery_shopping_store_404_para_two'] ); 
                        ?>
                        <p><?php echo esc_html( $grocery_shopping_store_404_para_two ); ?></p>
                    </div>
                </div>
            </div>
        </section>
    </div>

<?php
get_footer();