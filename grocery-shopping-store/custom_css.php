<?php

$grocery_shopping_store_custom_css = "";

	$grocery_shopping_store_theme_pagination_options_alignment = get_theme_mod('grocery_shopping_store_theme_pagination_options_alignment', 'Center');
	if ($grocery_shopping_store_theme_pagination_options_alignment == 'Center') {
		$grocery_shopping_store_custom_css .= '.navigation.pagination,.navigation.posts-navigation .nav-links{';
		$grocery_shopping_store_custom_css .= 'justify-content: center;margin: 0 auto;';
		$grocery_shopping_store_custom_css .= '}';
	} else if ($grocery_shopping_store_theme_pagination_options_alignment == 'Right') {
		$grocery_shopping_store_custom_css .= '.navigation.pagination,.navigation.posts-navigation .nav-links{';
		$grocery_shopping_store_custom_css .= 'justify-content: right;margin: 0 0 0 auto;';
		$grocery_shopping_store_custom_css .= '}';
	} else if ($grocery_shopping_store_theme_pagination_options_alignment == 'Left') {
		$grocery_shopping_store_custom_css .= '.navigation.pagination,.navigation.posts-navigation .nav-links{';
		$grocery_shopping_store_custom_css .= 'justify-content: left;margin: 0 auto 0 0;';
		$grocery_shopping_store_custom_css .= '}';
	}

	$grocery_shopping_store_theme_breadcrumb_enable = get_theme_mod('grocery_shopping_store_theme_breadcrumb_enable',true);
    if($grocery_shopping_store_theme_breadcrumb_enable != true){
        $grocery_shopping_store_custom_css .='nav.breadcrumb-trail.breadcrumbs,nav.woocommerce-breadcrumb{';
            $grocery_shopping_store_custom_css .='display: none;';
        $grocery_shopping_store_custom_css .='}';
    }

	$grocery_shopping_store_theme_breadcrumb_options_alignment = get_theme_mod('grocery_shopping_store_theme_breadcrumb_options_alignment', 'Left');
	if ($grocery_shopping_store_theme_breadcrumb_options_alignment == 'Center') {
	    $grocery_shopping_store_custom_css .= '.breadcrumbs ul,nav.woocommerce-breadcrumb{';
	    $grocery_shopping_store_custom_css .= 'text-align: center !important;';
	    $grocery_shopping_store_custom_css .= '}';
	} else if ($grocery_shopping_store_theme_breadcrumb_options_alignment == 'Right') {
	    $grocery_shopping_store_custom_css .= '.breadcrumbs ul,nav.woocommerce-breadcrumb{';
	    $grocery_shopping_store_custom_css .= 'text-align: Right !important;';
	    $grocery_shopping_store_custom_css .= '}';
	} else if ($grocery_shopping_store_theme_breadcrumb_options_alignment == 'Left') {
	    $grocery_shopping_store_custom_css .= '.breadcrumbs ul,nav.woocommerce-breadcrumb{';
	    $grocery_shopping_store_custom_css .= 'text-align: Left !important;';
	    $grocery_shopping_store_custom_css .= '}';
	}

	$grocery_shopping_store_single_page_content_alignment = get_theme_mod('grocery_shopping_store_single_page_content_alignment', 'left');
	if ($grocery_shopping_store_single_page_content_alignment == 'left') {
	    $grocery_shopping_store_custom_css .= '#single-page .type-page,section.theme-custom-block.theme-error-sectiontheme-error-section.error-block-middle,section.theme-custom-block.theme-error-section.error-block-heading .theme-area-header{';
	    $grocery_shopping_store_custom_css .= 'text-align: left !important;';
	    $grocery_shopping_store_custom_css .= '}';
	} else if ($grocery_shopping_store_single_page_content_alignment == 'center') {
	    $grocery_shopping_store_custom_css .= '#single-page .type-page,section.theme-custom-block.theme-error-sectiontheme-error-section.error-block-middle,section.theme-custom-block.theme-error-section.error-block-heading .theme-area-header{';
	    $grocery_shopping_store_custom_css .= 'text-align: center !important;';
	    $grocery_shopping_store_custom_css .= '}';
	} else if ($grocery_shopping_store_single_page_content_alignment == 'right') {
	    $grocery_shopping_store_custom_css .= '#single-page .type-page,section.theme-custom-block.theme-error-sectiontheme-error-section.error-block-middle,section.theme-custom-block.theme-error-section.error-block-heading .theme-area-header{';
	    $grocery_shopping_store_custom_css .= 'text-align: right !important;';
	    $grocery_shopping_store_custom_css .= '}';
	}

	$grocery_shopping_store_single_post_content_alignment = get_theme_mod('grocery_shopping_store_single_post_content_alignment', 'left');
	if ($grocery_shopping_store_single_post_content_alignment == 'left') {
	    $grocery_shopping_store_custom_css .= '#single-page .type-post,#single-page .type-post .entry-meta,#single-page .type-post .is-layout-flex{';
	    $grocery_shopping_store_custom_css .= 'text-align: left !important;justify-content: left;';
	    $grocery_shopping_store_custom_css .= '}';
	} else if ($grocery_shopping_store_single_post_content_alignment == 'center') {
	    $grocery_shopping_store_custom_css .= '#single-page .type-post,#single-page .type-post .entry-meta,#single-page .type-post .is-layout-flex{';
	    $grocery_shopping_store_custom_css .= 'text-align: center !important;justify-content: center;';
	    $grocery_shopping_store_custom_css .= '}';
	} else if ($grocery_shopping_store_single_post_content_alignment == 'right') {
	    $grocery_shopping_store_custom_css .= '#single-page .type-post,#single-page .type-post .entry-meta,#single-page .type-post .is-layout-flex{';
	    $grocery_shopping_store_custom_css .= 'text-align: right !important;justify-content: right;';
	    $grocery_shopping_store_custom_css .= '}';
	}

	$grocery_shopping_store_menu_text_transform = get_theme_mod('grocery_shopping_store_menu_text_transform', 'Capitalize');
	if ($grocery_shopping_store_menu_text_transform == 'Capitalize') {
	    $grocery_shopping_store_custom_css .= '.site-navigation .primary-menu > li a{';
	    $grocery_shopping_store_custom_css .= 'text-transform: Capitalize !important;';
	    $grocery_shopping_store_custom_css .= '}';
	} else if ($grocery_shopping_store_menu_text_transform == 'uppercase') {
	    $grocery_shopping_store_custom_css .= '.site-navigation .primary-menu > li a{';
	    $grocery_shopping_store_custom_css .= 'text-transform: uppercase !important;';
	    $grocery_shopping_store_custom_css .= '}';
	} else if ($grocery_shopping_store_menu_text_transform == 'lowercase') {
	    $grocery_shopping_store_custom_css .= '.site-navigation .primary-menu > li a{';
	    $grocery_shopping_store_custom_css .= 'text-transform: lowercase !important;';
	    $grocery_shopping_store_custom_css .= '}';
	}

	$grocery_shopping_store_footer_widget_title_alignment = get_theme_mod('grocery_shopping_store_footer_widget_title_alignment', 'left');
	if ($grocery_shopping_store_footer_widget_title_alignment == 'left') {
	    $grocery_shopping_store_custom_css .= 'h2.widget-title{';
	    $grocery_shopping_store_custom_css .= 'text-align: left !important;';
	    $grocery_shopping_store_custom_css .= '}';
	} else if ($grocery_shopping_store_footer_widget_title_alignment == 'center') {
	    $grocery_shopping_store_custom_css .= 'h2.widget-title{';
	    $grocery_shopping_store_custom_css .= 'text-align: center !important;';
	    $grocery_shopping_store_custom_css .= '}';
	} else if ($grocery_shopping_store_footer_widget_title_alignment == 'right') {
	    $grocery_shopping_store_custom_css .= 'h2.widget-title{';
	    $grocery_shopping_store_custom_css .= 'text-align: right !important;';
	    $grocery_shopping_store_custom_css .= '}';
	}

    $grocery_shopping_store_show_hide_related_product = get_theme_mod('grocery_shopping_store_show_hide_related_product',true);
    if($grocery_shopping_store_show_hide_related_product != true){
        $grocery_shopping_store_custom_css .='.related.products{';
            $grocery_shopping_store_custom_css .='display: none;';
        $grocery_shopping_store_custom_css .='}';
    }

	$grocery_shopping_store_sticky_sidebar = get_theme_mod('grocery_shopping_store_sticky_sidebar',true);
    if($grocery_shopping_store_sticky_sidebar != true){
        $grocery_shopping_store_custom_css .='.widget-area-wrapper{';
            $grocery_shopping_store_custom_css .='position: relative !important;';
        $grocery_shopping_store_custom_css .='}';
    }

	/*-------------------- Global First Color -------------------*/

	$grocery_shopping_store_global_color = get_theme_mod('grocery_shopping_store_global_color', '#FF851C'); // Add a fallback if the color isn't set

	if ($grocery_shopping_store_global_color) {
		$grocery_shopping_store_custom_css .= ':root {';
		$grocery_shopping_store_custom_css .= '--global-color: ' . esc_attr($grocery_shopping_store_global_color) . ';';
		$grocery_shopping_store_custom_css .= '}';
	}	

	/*-------------------- Content Font -------------------*/

	$grocery_shopping_store_content_typography_font = get_theme_mod('grocery_shopping_store_content_typography_font', 'Noto Sans'); // Add a fallback if the color isn't set

	if ($grocery_shopping_store_content_typography_font) {
		$grocery_shopping_store_custom_css .= ':root {';
		$grocery_shopping_store_custom_css .= '--font-main: ' . esc_attr($grocery_shopping_store_content_typography_font) . ';';
		$grocery_shopping_store_custom_css .= '}';
	}

	/*-------------------- Heading Font  -------------------*/

	$grocery_shopping_store_heading_typography_font = get_theme_mod('grocery_shopping_store_heading_typography_font', 'Noto Sans'); // Add a fallback if the color isn't set

	if ($grocery_shopping_store_heading_typography_font) {
		$grocery_shopping_store_custom_css .= ':root {';
		$grocery_shopping_store_custom_css .= '--font-head: ' . esc_attr($grocery_shopping_store_heading_typography_font) . ';';
		$grocery_shopping_store_custom_css .= '}';
	}

	$grocery_shopping_store_columns = get_theme_mod('grocery_shopping_store_posts_per_columns', 3);
	$grocery_shopping_store_columns = absint($grocery_shopping_store_columns);
	if ( $grocery_shopping_store_columns < 1 || $grocery_shopping_store_columns > 6 ) {
		$grocery_shopping_store_columns = 3;
	}
	$grocery_shopping_store_custom_css .= "
		.site-content .article-wraper-archive {
			grid-template-columns: repeat({$grocery_shopping_store_columns}, 1fr);
		}
	";

	$grocery_shopping_store_copyright_alignment = get_theme_mod( 'grocery_shopping_store_copyright_alignment', 'Default' );
	if ( $grocery_shopping_store_copyright_alignment === 'Reverse' ) {
		$grocery_shopping_store_custom_css .= '.site-info .column-row { flex-direction: row-reverse; }';
		$grocery_shopping_store_custom_css .= '.footer-credits { justify-content: flex-end; }';
		$grocery_shopping_store_custom_css .= '.footer-copyright { text-align: right; }';
		$grocery_shopping_store_custom_css .= '.site-info .column.column-3 { text-align: left; }';
	} elseif ( $grocery_shopping_store_copyright_alignment === 'Center' ) {
		$grocery_shopping_store_custom_css .= '.site-info .column-row { flex-direction: column; align-items: center; gap: 15px; }';
		$grocery_shopping_store_custom_css .= '.footer-credits { justify-content: center; }';
		$grocery_shopping_store_custom_css .= '.footer-copyright { text-align: center; }';
		$grocery_shopping_store_custom_css .= '.site-info .column.column-3 { text-align: center; }';
	}

	$grocery_shopping_store_footer_widget_background_color = get_theme_mod('grocery_shopping_store_footer_widget_background_color');
	if ($grocery_shopping_store_footer_widget_background_color) {

		$grocery_shopping_store_custom_css .= "
			.footer-widgetarea {
				background-color: ". esc_attr($grocery_shopping_store_footer_widget_background_color) .";
			}
		";
	}

	$grocery_shopping_store_footer_widget_background_image = get_theme_mod('grocery_shopping_store_footer_widget_background_image');
	if ($grocery_shopping_store_footer_widget_background_image) {
		$grocery_shopping_store_custom_css .= "
			.footer-widgetarea {
				background-image: url(" . esc_url($grocery_shopping_store_footer_widget_background_image) . ");
			}
		";
	}

	$grocery_shopping_store_copyright_font_size = get_theme_mod('grocery_shopping_store_copyright_font_size');
	if ($grocery_shopping_store_copyright_font_size) {

		$grocery_shopping_store_custom_css .= "
			.footer-copyright {
				font-size: ". esc_attr($grocery_shopping_store_copyright_font_size) ."px;
			}
		";
	}

	/*-------------------- Menu Color CSS -------------------*/

	$grocery_shopping_store_header_menus_color = get_theme_mod('grocery_shopping_store_header_menus_color');
	if($grocery_shopping_store_header_menus_color != false){
		$grocery_shopping_store_custom_css .='.site-navigation .primary-menu a{';
			$grocery_shopping_store_custom_css .='color: '.esc_attr($grocery_shopping_store_header_menus_color).'!important;';
		$grocery_shopping_store_custom_css .='}';
	}

	$grocery_shopping_store_header_menus_hover_color = get_theme_mod('grocery_shopping_store_header_menus_hover_color');
	if($grocery_shopping_store_header_menus_hover_color != false){
		$grocery_shopping_store_custom_css .='.site-navigation .primary-menu a:hover{';
			$grocery_shopping_store_custom_css .='color: '.esc_attr($grocery_shopping_store_header_menus_hover_color).'!important;';
		$grocery_shopping_store_custom_css .='}';
	}

	$grocery_shopping_store_header_submenus_color = get_theme_mod('grocery_shopping_store_header_submenus_color');
	if($grocery_shopping_store_header_submenus_color != false){
		$grocery_shopping_store_custom_css .='.site-navigation .primary-menu ul.sub-menu li a,.site-navigation .primary-menu li ul li a{';
			$grocery_shopping_store_custom_css .='color: '.esc_attr($grocery_shopping_store_header_submenus_color).'!important;';
		$grocery_shopping_store_custom_css .='}';
	}

	$grocery_shopping_store_header_submenus_hover_color = get_theme_mod('grocery_shopping_store_header_submenus_hover_color');
	if($grocery_shopping_store_header_submenus_hover_color != false){
		$grocery_shopping_store_custom_css .='.site-navigation .primary-menu > li ul.sub-menu a:hover,.site-navigation .primary-menu li ul li a:hover{';
			$grocery_shopping_store_custom_css .='color: '.esc_attr($grocery_shopping_store_header_submenus_hover_color).'!important;';
		$grocery_shopping_store_custom_css .='}';
	}