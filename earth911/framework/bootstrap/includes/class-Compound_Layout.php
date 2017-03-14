<?php


if ( ! class_exists( 'Compound_Layout' ) ) {

	/**
	* The "Layout Module"
	*/
	class Compound_Layout {

		function __construct() {
			global $wpscom_settings;

			add_filter( 'wpscom_section_class_wrapper',   array( $this, 'apply_layout_classes_wrapper'   )     );
			add_filter( 'wpscom_section_class_main',      array( $this, 'apply_layout_classes_main'      )     );
			add_filter( 'wpscom_section_class_primary',   array( $this, 'apply_layout_classes_primary'   )     );
			add_filter( 'wpscom_section_class_secondary', array( $this, 'apply_layout_classes_secondary' )     );
			add_filter( 'wpscom_container_class',         array( $this, 'container_class'                )     );
			add_filter( 'body_class',                       array( $this, 'layout_body_class'              )     );
			add_filter( 'wpscom_navbar_container_class',  array( $this, 'navbar_container_class'         )     );
			add_action( 'template_redirect',                array( $this, 'content_width'                  )     );

			if ( isset( $wpscom_settings['body_margin_top'] ) && ( $wpscom_settings['body_margin_top'] > 0 || $wpscom_settings['body_margin_bottom'] > 0 ) ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'body_margin' ), 101 );
			}

			add_action( 'get_header',            array( $this, 'boxed_container_div_open' ), 1 );
			add_action( 'wpscom_pre_footer',   array( $this, 'boxed_container_div_open' ), 1 );
			add_action( 'wpscom_do_navbar',    array( $this, 'boxed_container_div_close' ), 99 );
			add_action( 'wpscom_after_footer', array( $this, 'boxed_container_div_close' ), 899 );
			add_action( 'wp',                    array( $this, 'control_primary_sidebar_display' ) );
			add_action( 'wp',                    array( $this, 'control_secondary_sidebar_display' ) );

			 // Modify the appearance of widgets based on user selection.
			if ( isset( $wpscom_settings['widgets_mode'] ) ) {
				$widgets_mode = $wpscom_settings['widgets_mode'];
				if ( $widgets_mode == 0 || $widgets_mode == 1 ) {
					add_filter( 'wpscom_widgets_class',        array( $this, 'alter_widgets_class'        ) );
					add_filter( 'wpscom_widgets_before_title', array( $this, 'alter_widgets_before_title' ) );
					add_filter( 'wpscom_widgets_after_title',  array( $this, 'alter_widgets_after_title'  ) );
				}
			}

			add_action( 'wp_head', array( $this, 'static_meta'      ) );
		}

		/*
		 * Get the layout value, but only set it once!
		 */
		static public function get_layout() {
			global $wpscom_layout;
			global $wpscom_settings;

			if ( ! isset( $wpscom_layout ) ) {
				do_action( 'wpscom_layout_modifier' );

				$wpscom_layout = intval( $wpscom_settings['layout'] );

				// Looking for a per-page template ?
				if ( is_page() && is_page_template() ) {
					if ( is_page_template( 'template-0.php' ) ) {
						$wpscom_layout = 0;
					} elseif ( is_page_template( 'template-1.php' ) ) {
						$wpscom_layout = 1;
					} elseif ( is_page_template( 'template-2.php' ) ) {
						$wpscom_layout = 2;
					} elseif ( is_page_template( 'template-3.php' ) ) {
						$wpscom_layout = 3;
					} elseif ( is_page_template( 'template-4.php' ) ) {
						$wpscom_layout = 4;
					} elseif ( is_page_template( 'template-5.php' ) ) {
						$wpscom_layout = 5;
					}
				}

				if ( $wpscom_settings['cpt_layout_toggle'] == 1 ) {
					if ( ! is_page_template() ) {
						$post_types = get_post_types( array( 'public' => true ), 'names' );
						foreach ( $post_types as $post_type ) {
							$wpscom_layout = ( is_singular( $post_type ) ) ? intval( $wpscom_settings[$post_type . '_layout'] ) : $wpscom_layout;
						}
					}
				}

				if ( ! is_active_sidebar( 'sidebar-secondary' ) && is_active_sidebar( 'sidebar-primary' ) && $wpscom_layout == 5 ) {
					$wpscom_layout = 3;
				}
			}
			return $wpscom_layout;
		}

		/*
		 *Override the layout value globally
		 */
		function set_layout( $val ) {
			global $wpscom_layout, $redux;
			$wpscom_layout = intval( $val );
		}

		/*
		 * Calculates the classes of the main area, main sidebar and secondary sidebar
		 */
		public static function section_class_ext( $target, $echo = false ) {
			global $redux, $wpscom_framework;
			global $wpscom_settings;

			$layout = self::get_layout();
			$first  = intval( $wpscom_settings['layout_primary_width'] );
			$second = intval( $wpscom_settings['layout_secondary_width'] );

			// disable responsiveness if layout is set to non-responsive
			$width = ( $wpscom_settings['site_style'] == 'static' ) ? 'mobile' : 'tablet';

			// Set some defaults so that we can change them depending on the selected template
			$main       = 12;
			$primary    = NULL;
			$secondary  = NULL;
			$wrapper    = 12;

			if ( wpscom_display_primary_sidebar() && wpscom_display_secondary_sidebar() ) {

				if ( $layout == 5 ) {
					$main       = 12 - floor( ( 12 * $first ) / ( 12 - $second ) );
					$primary    = floor( ( 12 * $first ) / ( 12 - $second ) );
					$secondary  = $second;
					$wrapper    = 12 - $second;
				} elseif ( $layout >= 3 ) {
					$main       = 12 - $first - $second;
					$primary    = $first;
					$secondary  = $second;
				} elseif ( $layout >= 1 ) {
					$main       = 12 - $first;
					$primary    = $first;
					$secondary  = $second;
				}

			} elseif ( wpscom_display_primary_sidebar() && ! wpscom_display_secondary_sidebar() ) {

				if ( $layout >= 1 ) {
					$main       = 12 - $first;
					$primary    = $first;
				}

			} elseif ( ! wpscom_display_primary_sidebar() && wpscom_display_secondary_sidebar() ) {

				if ( $layout >= 3 ) {
					$main       = 12 - $second;
					$secondary  = $second;
				}
			}

			if ( $target == 'primary' ) {
				$class = $wpscom_framework->column_classes( array( $width => $primary ), 'strimg' );
			} elseif ( $target == 'secondary' ) {
				$class = $wpscom_framework->column_classes( array( $width => $secondary ), 'strimg' );
			} elseif ( $target == 'wrapper' ) {
				$class = $wpscom_framework->column_classes( array( $width => $wrapper ), 'strimg' );
			} else {
				$class = $wpscom_framework->column_classes( array( $width => $main ), 'strimg' );
			}

			if ( $echo ) {
				echo $class;
			} else {
				return $class;
			}
		}

		/**
		 * Helper function for layout classes
		 */
		function apply_layout_classes_wrapper() {
			return self::section_class_ext( 'wrapper' );
		}

		/**
		 * Helper function for layout classes
		 */
		function apply_layout_classes_main() {
			return self::section_class_ext( 'main' );
		}

		/**
		 * Helper function for layout classes
		 */
		function apply_layout_classes_primary() {
			return self::section_class_ext( 'primary' );
		}

		/**
		 * Helper function for layout classes
		 */
		function apply_layout_classes_secondary() {
			return self::section_class_ext( 'secondary' );
		}

		/**
		 * Add and remove body_class() classes to accomodate layouts
		 */
		function layout_body_class( $classes ) {
			global $wpscom_settings;

			$layout     = self::get_layout();
			$site_style = $wpscom_settings['site_style'];
			$margin     = $wpscom_settings['navbar_margin_top'];

			if ( $layout == 2 || $layout == 3 || $layout == 5 ) {
				$classes[] = 'main-float-right';
			}

			if ( $site_style == 'boxed' ) {
				$classes[] = 'boxed-style';
			} elseif ( $site_style == 'fluid' ) {
				$classes[] = 'fluid';
			}

			return $classes;
		}

		/*
		 * Return the container class
		 */
		public static function container_class() {
			global $wpscom_settings;
			$class    = $wpscom_settings['site_style'] != 'fluid' ? 'container' : 'fluid';

			// override if navbar module exists and 'navbar-toggle' is set to left.
			if ( class_exists( 'Compound_Menus' ) ) {
				if ( $wpscom_settings['navbar_toggle'] == 'left' ) {
					$class = 'fluid';
				}
			}

			return $class;
		}

		/*
		 * Return the container class
		 */
		function navbar_container_class() {
			global $wpscom_settings;

			$site_style = $wpscom_settings['site_style'];
			$toggle     = $wpscom_settings['navbar_toggle'];

			if ( $toggle == 'full' ) {
				$class = 'fluid';
			} else {
				$class = ( $site_style != 'fluid' ) ? 'container' : 'fluid';
			}

			// override if navbar module exists and 'navbar-toggle' is set to left.
			if ( class_exists( 'CompoundMenus' ) ) {
				if ( $wpscom_settings['navbar_toggle'] == 'left' ) {
					$class = 'fluid';
				}
			}

			return $class;
		}

		/*
		 * Calculate the width of the content area in pixels.
		 */
		public static function content_width_px( $echo = false ) {
			global $redux;
			global $wpscom_settings;

			$layout = self::get_layout();

			$container  = filter_var( $wpscom_settings['screen_large_desktop'], FILTER_SANITIZE_NUMBER_INT );
			$gutter     = filter_var( $wpscom_settings['layout_gutter'], FILTER_SANITIZE_NUMBER_INT );

			$main_span  = filter_var( self::section_class_ext( 'main', false ), FILTER_SANITIZE_NUMBER_INT );
			$main_span  = str_replace( '-' , '', $main_span );

			// If the layout is #5, override the default function and calculate the span width of the main area again.
			if ( is_active_sidebar( 'sidebar-secondary' ) && is_active_sidebar( 'sidebar-primary' ) && $layout == 5 ) {
				$main_span = 12 - intval( $wpscom_settings['layout_primary_width'] ) - intval( $wpscom_settings['layout_secondary_width'] );
			}

			if ( is_front_page() && $wpscom_settings['layout_sidebar_on_front'] != 1 ) {
				$main_span = 12;
			}

			$width = $container * ( $main_span / 12 ) - $gutter;

			// Width should be an integer since we're talking pixels, round up!.
			$width = round( $width );

			if ( $echo ) {
				echo $width;
			} else {
				return $width;
			}
		}

		/*
		 * Set the content width
		 */
		public static function content_width() {
			global $content_width;
			$content_width = self::content_width_px();
		}

		/*
		 * Body Margins
		 */
		function body_margin() {
			global $wpscom_settings;

			$body_margin_top    = $wpscom_settings['body_margin_top'];
			$body_margin_bottom = $wpscom_settings['body_margin_bottom'];

			$style = 'body { margin-top:'. $body_margin_top .'px; margin-bottom:'. $body_margin_bottom .'px; }';

			wp_add_inline_style( 'wpscom_css', $style );
		}

		/**
		 * Add a wrapper div when in "boxed" mode to disallow full-width elements
		 */
		function boxed_container_div_open() {
			global $wpscom_settings;

			if ( $wpscom_settings['site_style'] == 'boxed' ) echo '<div class="container boxed-container">';
		}

		/**
		 * Close the wrapper div that the 'boxed_container_div_open' opens when in "boxed" mode.
		 */
		function boxed_container_div_close() {
			global $wpscom_settings;

			if ( $wpscom_settings['site_style'] == 'boxed' ) echo '</div>';
		}

		/**
		 * Modify the rules for showing up or hiding the primary sidebar
		 */
		function control_primary_sidebar_display() {
			global $wpscom_settings;

			$layout_sidebar_on_front = $wpscom_settings['layout_sidebar_on_front'];

			if ( self::get_layout() == 0 ) {
				add_filter( 'wpscom_display_primary_sidebar', '__return_false' );
			}

			if ( is_front_page() && $layout_sidebar_on_front == 1 && self::get_layout() != 0 ) {
				add_filter( 'wpscom_display_primary_sidebar', '__return_true' );
			}

			if ( ( ! is_front_page() || ( is_front_page() && $layout_sidebar_on_front == 1 ) ) && self::get_layout() != 0 ) {
				add_filter( 'wpscom_display_primary_sidebar', '__return_true' );
			}
		}

		/**
		 * Modify the rules for showing up or hiding the secondary sidebar
		 */
		function control_secondary_sidebar_display() {
			global $wpscom_settings;

			$layout_sidebar_on_front = $wpscom_settings['layout_sidebar_on_front'];

			if ( self::get_layout() < 3 ) {
				add_filter( 'wpscom_display_secondary_sidebar', '__return_false' );
			}

			if ( ( ! is_front_page() && wpscom_display_secondary_sidebar() ) || ( is_front_page() && $layout_sidebar_on_front == 1 && self::get_layout() >= 3 ) ) {
				add_filter( 'wpscom_display_secondary_sidebar', '__return_true' );
			}
		}

		/**
		 * Get the widget class
		 */
		function alter_widgets_class() {
			global $wpscom_settings;
			return $wpscom_settings['widgets_mode'] == 0 ? 'panel panel-default' : 'well';
		}

		/**
		 * Widgets 'before_title' modifying based on widgets mode.
		 */
		function alter_widgets_before_title() {
			global $wpscom_settings;
			return $wpscom_settings['widgets_mode'] == 0 ? '<div class="panel-heading">' : '<h3 class="widget-title">';
		}

		/**
		 * Widgets 'after_title' modifying based on widgets mode.
		 */
		function alter_widgets_after_title() {
			global $wpscom_settings;
			return $wpscom_settings['widgets_mode'] == 0 ? '</div><div class="panel-body">' : '</h3>';
		}

		/**
		 * Add some metadata when users have selected a static mode for their layout (not responsive).
		 */
		function static_meta() {
			global $wpscom_settings;

			if ( $wpscom_settings['site_style'] != 'static' ) : ?>
				<meta name="viewport" content="width=device-width, initial-scale=1">
				<meta name="mobile-web-app-capable" content="yes">
				<meta name="apple-mobile-web-app-capable" content="yes">
				<meta name="apple-mobile-web-app-status-bar-style" content="black">
				<?php
			endif;
		}

		function include_wrapper() {
			global $wpscom_layout;

			if ( $wpscom_layout == 5 ) {
				return true;
			} else {
				return false;
			}
		}
	}
}
