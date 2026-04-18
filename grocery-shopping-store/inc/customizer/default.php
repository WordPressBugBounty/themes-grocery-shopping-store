<?php
/**
 * Default Values.
 *
 * @package Grocery Shopping Store
 */

if ( ! function_exists( 'grocery_shopping_store_get_default_theme_options' ) ) :
	function grocery_shopping_store_get_default_theme_options() {

        $grocery_shopping_store_defaults = array();

        // Header
        $grocery_shopping_store_defaults['grocery_shopping_store_header_layout_button']          =  esc_html__( 'Appointment', 'grocery-shopping-store' );
        $grocery_shopping_store_defaults['grocery_shopping_store_header_layout_button_url']      =  esc_url( '#', 'grocery-shopping-store' );
        $grocery_shopping_store_defaults['grocery_shopping_store_header_search']                 = 1;
        $grocery_shopping_store_defaults['grocery_shopping_store_theme_loader']                  = 0;
        $grocery_shopping_store_defaults['grocery_shopping_store_footer_column_layout']          = 3;
        $grocery_shopping_store_defaults['grocery_shopping_store_menu_font_size']                 = 14;
        $grocery_shopping_store_defaults['grocery_shopping_store_copyright_font_size']                 = 16;
        $grocery_shopping_store_defaults['grocery_shopping_store_breadcrumb_font_size']                 = 16;
        $grocery_shopping_store_defaults['grocery_shopping_store_excerpt_limit']                 = 20;
        $grocery_shopping_store_defaults['grocery_shopping_store_menu_text_transform']                 = 'capitalize';  
        $grocery_shopping_store_defaults['grocery_shopping_store_single_page_content_alignment']                 = 'left';
        $grocery_shopping_store_defaults['grocery_shopping_store_theme_pagination_options_alignment']                 = 'Center'; 
        $grocery_shopping_store_defaults['grocery_shopping_store_theme_breadcrumb_options_alignment']                 = 'Left'; 
        $grocery_shopping_store_defaults['grocery_shopping_store_per_columns']                 = 3;  
        $grocery_shopping_store_defaults['grocery_shopping_store_product_per_page']                 = 9;
        $grocery_shopping_store_defaults['grocery_shopping_store_custom_related_products_number'] = 6;
        $grocery_shopping_store_defaults['grocery_shopping_store_custom_related_products_number_per_row'] = 3;
        $grocery_shopping_store_defaults['grocery_shopping_store_sticky']                                         = 0;
        $grocery_shopping_store_defaults['grocery_shopping_store_theme_breadcrumb_enable']                 = 1;
        $grocery_shopping_store_defaults['grocery_shopping_store_single_post_content_alignment']                 = 'left';

        //Slider 

        $grocery_shopping_store_defaults['grocery_shopping_store_slider_section_small_title']    =  esc_html__( 'WELCOME TO GROCERY STORE', 'grocery-shopping-store' );
        $grocery_shopping_store_defaults['grocery_shopping_store_slider_section_sub_title']      =  esc_html__( 'Shop Fresh, Eat Well One Stop Online Grocery Destination', 'grocery-shopping-store' );
        $grocery_shopping_store_defaults['grocery_shopping_store_slider_section_content']        =  esc_html__( 'In publishing and graphic design, ipsum is a placeholder text commonly used to demonstrate the visual form placeholder text commonly of a document.', 'grocery-shopping-store' );
        $grocery_shopping_store_defaults['grocery_shopping_store_slider_section_button_url']     =  esc_url( '#', 'grocery-shopping-store' );
        $grocery_shopping_store_defaults['grocery_shopping_store_slider_section_button']         =  esc_html__( 'Read More', 'grocery-shopping-store' );

	// Options.
        $grocery_shopping_store_defaults['grocery_shopping_store_logo_width_range']                 = 200;
        
        $grocery_shopping_store_defaults['grocery_shopping_store_global_sidebar_layout']	        = 'right-sidebar';
        
        $grocery_shopping_store_defaults['grocery_shopping_store_pagination_layout']                = 'numeric';
	$grocery_shopping_store_defaults['grocery_shopping_store_footer_copyright_text'] 	        = esc_html__( 'All rights reserved.', 'grocery-shopping-store' );
        $grocery_shopping_store_defaults['grocery_shopping_store_twp_navigation_type']              = 'theme-normal-navigation';
        $grocery_shopping_store_defaults['grocery_shopping_store_post_author']                      = 1;
        $grocery_shopping_store_defaults['grocery_shopping_store_post_date']                        = 1;
        $grocery_shopping_store_defaults['grocery_shopping_store_post_category']                	= 1;
        $grocery_shopping_store_defaults['grocery_shopping_store_post_tags']                        = 1;
        $grocery_shopping_store_defaults['grocery_shopping_store_floating_next_previous_nav']       = 1;
        $grocery_shopping_store_defaults['grocery_shopping_store_header_slider']                    = 1;
        $grocery_shopping_store_defaults['grocery_shopping_store_display_footer']            = 1;
        $grocery_shopping_store_defaults['grocery_shopping_store_banner_right_image_1']                    = esc_url(get_template_directory_uri() . '/assets/images/banner.png');
        
        $grocery_shopping_store_defaults['grocery_shopping_store_footer_widget_title_alignment']                 = 'left'; 
        $grocery_shopping_store_defaults['grocery_shopping_store_show_hide_related_product']          = 1;
        $grocery_shopping_store_defaults['grocery_shopping_store_display_archive_post_image']            = 1;
        $grocery_shopping_store_defaults['grocery_shopping_store_background_color']                 = '#fff';
        $grocery_shopping_store_defaults['grocery_shopping_store_global_color']                                   = '#FF204E';
        $grocery_shopping_store_defaults['grocery_shopping_store_display_archive_post_category']          = 1;
        $grocery_shopping_store_defaults['grocery_shopping_store_display_archive_post_title']             = 1;
        $grocery_shopping_store_defaults['grocery_shopping_store_display_archive_post_content']           = 1;
        $grocery_shopping_store_defaults['grocery_shopping_store_display_archive_post_button']            = 1;
        
        $grocery_shopping_store_defaults['grocery_shopping_store_display_single_post_image']            = 1;
        $grocery_shopping_store_defaults['grocery_shopping_store_display_archive_post_format_icon']       = 1;

        $grocery_shopping_store_defaults['grocery_shopping_store_enable_to_the_top']                      = 1;
        $grocery_shopping_store_defaults['grocery_shopping_store_to_the_top_text']                      = esc_html__( 'To The Top', 'grocery-shopping-store' );

        //Product
        
        $grocery_shopping_store_defaults['grocery_shopping_store_product_section']                   = 1;
        $grocery_shopping_store_defaults['grocery_shopping_store_product_section_title']            = esc_html__( 'FEATURED PRODUCTS', 'grocery-shopping-store' );
        $grocery_shopping_store_defaults['grocery_shopping_store_product_section_button']            = esc_html__( 'View All', 'grocery-shopping-store' );
        $grocery_shopping_store_defaults['grocery_shopping_store_product_section_button_url']            = esc_url( '#', 'grocery-shopping-store' );

        // 404 Page Defaults
        $grocery_shopping_store_defaults['grocery_shopping_store_404_main_title'] = esc_html__( 'Oops! That page can’t be found.', 'grocery-shopping-store' );
        $grocery_shopping_store_defaults['grocery_shopping_store_404_subtitle_one'] = esc_html__( 'Maybe it’s out there, somewhere...', 'grocery-shopping-store' );
        $grocery_shopping_store_defaults['grocery_shopping_store_404_para_one'] = esc_html__( 'You can always find insightful stories on our', 'grocery-shopping-store' );
        $grocery_shopping_store_defaults['grocery_shopping_store_404_subtitle_two'] = esc_html__( 'Still feeling lost? You’re not alone.', 'grocery-shopping-store' );
        $grocery_shopping_store_defaults['grocery_shopping_store_404_para_two'] = esc_html__( 'Enjoy these stories about getting lost, losing things, and finding what you never knew you were looking for.', 'grocery-shopping-store' );

	// Pass through filter.
	$grocery_shopping_store_defaults = apply_filters( 'grocery_shopping_store_filter_default_theme_options', $grocery_shopping_store_defaults );

		return $grocery_shopping_store_defaults;
	}
endif;