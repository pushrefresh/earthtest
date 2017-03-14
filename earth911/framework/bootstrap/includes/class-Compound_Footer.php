<?php


if( ! class_exists( 'Compound_Footer' ) ) {
	/**
	* Build the Compound Footer module class.
	*/
	class Compound_Footer {

		function __construct() {
			add_action( 'wp_enqueue_scripts',    array( $this, 'css' ), 101 );
			add_action( 'wpscom_footer_html', array( $this, 'html' ) );
			add_action( 'widgets_init',          array( $this, 'widgets_init' ) );
		}

		/**
		 * Register sidebars and widgets
		 */
		function widgets_init() {
			$class        = apply_filters( 'wpscom_widgets_class', '' );
			$before_title = apply_filters( 'wpscom_widgets_before_title', '<h3 class="widget-title">' );
			$after_title  = apply_filters( 'wpscom_widgets_after_title', '</h3>' );

			// Sidebars
			register_sidebar( array(
				'name'          => __( 'Primary Sidebar', 'wpscom' ),
				'id'            => 'sidebar-primary',
				'before_widget' => '<section id="%1$s" class="' . $class . ' widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => $before_title,
				'after_title'   => $after_title,
			));

			register_sidebar( array(
				'name'          => __( 'Secondary Sidebar', 'wpscom' ),
				'id'            => 'sidebar-secondary',
				'before_widget' => '<section id="%1$s" class="' . $class . ' widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => $before_title,
				'after_title'   => $after_title,
			));

			register_sidebar( array(
				'name'          => __( 'Footer Widget Area 1', 'wpscom' ),
				'id'            => 'sidebar-footer-1',
				'before_widget' => '<section id="%1$s" class="' . $class . ' widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => $before_title,
				'after_title'   => $after_title,
			));

			register_sidebar( array(
				'name'          => __( 'Footer Widget Area 2', 'wpscom' ),
				'id'            => 'sidebar-footer-2',
				'before_widget' => '<section id="%1$s" class="' . $class . ' widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => $before_title,
				'after_title'   => $after_title,
			));

			register_sidebar( array(
				'name'          => __( 'Footer Widget Area 3', 'wpscom' ),
				'id'            => 'sidebar-footer-3',
				'before_widget' => '<section id="%1$s" class="' . $class . ' widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => $before_title,
				'after_title'   => $after_title,
			));

			register_sidebar( array(
				'name'          => __( 'Footer Widget Area 4', 'wpscom' ),
				'id'            => 'sidebar-footer-4',
				'before_widget' => '<section id="%1$s" class="' . $class . ' widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => $before_title,
				'after_title'   => $after_title,
			));
		}

		/**
		 * If the options selected require the insertion of some custom CSS to the document head, generate that CSS here
		 */

		function css() {
			global $wpscom_settings;

			$bg         = $wpscom_settings['footer_background'];
			$cl         = $wpscom_settings['footer_color'];
			$cl_brand   = $wpscom_settings['color_brand_primary'];
			$opacity    = ( intval( $wpscom_settings['footer_opacity'] ) ) / 100;
			$rgb        = Compound_Color::get_rgb( $bg, true );
			$border     = $wpscom_settings['footer_border'];
			$top_margin = $wpscom_settings['footer_top_margin'];

			$container_margin = $top_margin * 0.381966011;

			$style = 'footer.content-info {';
				$style .= 'color:' . $cl . ';';

				$style .= ( $opacity != 1 && $opacity != "" ) ? 'background: rgba(' . $rgb . ',' . $opacity . ');' : 'background:' . $bg . ';';
				$style .= ( ! empty($border) && $border['border-top'] > 0 && ! empty($border['border-color']) ) ? 'border-top:' . $border['border-top'] . ' ' . $border['border-style'] . ' ' . $border['border-color'] . ';' : '';
				if ( isset( $wpscom_settings['layout_gutter'] ) ) {
					$style .= 'padding-top:' . $wpscom_settings['layout_gutter'] / 2 . 'px;';
					$style .= 'padding-bottom:' . $wpscom_settings['layout_gutter'] / 2 . 'px;';
				}

				$style .= ( ! empty($top_margin) ) ? 'margin-top:'. $top_margin .'px;' : '';
			$style .= '}';

			$style .= 'footer div.container { margin-top:'. $container_margin .'px; }';
			$style .= '#copyright-bar { line-height: 30px; }';
			$style .= '#footer_social_bar { line-height: 30px; font-size: 16px; text-align: right; }';
			$style .= '#footer_social_bar a { margin-left: 9px; padding: 3px; color:' . $cl . '; }';
			$style .= '#footer_social_bar a:hover, #footer_social_bar a:active { color:' . $cl_brand . ' !important; text-decoration:none; }';

			wp_add_inline_style( 'wpscom_css', $style );
		}

		function html() {
			global $wpscom_framework, $wpscom_social, $wpscom_settings;

			// The blogname for use in the copyright section
			$blog_name  = get_bloginfo( 'name', 'display' );

			// The copyright section contents
			if ( isset( $wpscom_settings['footer_text'] ) ) {
				$ftext = $wpscom_settings['footer_text'];
			} else {
				$ftext = '&copy; [year] [sitename]';
			}

			// Replace [year] and [sitename] with meaninful content
			$ftext = str_replace( '[year]', date( 'Y' ), $ftext );
			$ftext = str_replace( '[sitename]', $blog_name, $ftext );

			// Do we want to display social links?
			if ( isset( $wpscom_settings['footer_social_toggle'] ) && $wpscom_settings['footer_social_toggle'] == 1 ) {
				$social = true;
			} else {
				$social = false;
			}

			// How many columns wide should social links be?
			if ( $social && isset( $wpscom_settings['footer_social_width'] ) ) {
				$social_width = $wpscom_settings['footer_social_width'];
			} else {
				$social_width = false;
			}

			// Social is enabled, we're modifying the width!
			if ( $social_width && $social && intval( $social_width ) > 0 ) {
				$width = 12 - intval( $social_width );
			} else {
				$width = 12;
			}

			if ( isset( $wpscom_settings['footer_social_new_window_toggle'] ) && ! empty( $wpscom_settings['footer_social_new_window_toggle'] ) ) {
				$blank = ' target="_blank"';
			} else {
				$blank = null;
			}

			$networks = $wpscom_social->get_social_links();
		}
	}
}
