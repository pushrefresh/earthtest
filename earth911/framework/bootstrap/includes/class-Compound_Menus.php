<?php


if ( ! class_exists( 'Compound_Menus' ) ) {

	/**
	* The "Menus" module
	*/
	class Compound_Menus {

		function __construct() {
			global $wpscom_settings;

			add_filter( 'wpscom_nav_class',        array( $this, 'nav_class' ) );
			add_action( 'wpscom_inside_nav_begin', array( $this, 'navbar_pre_searchbox' ), 11 );
			add_filter( 'wpscom_navbar_class',     array( $this, 'navbar_class' ) );
			add_action( 'wp_enqueue_scripts',        array( $this, 'navbar_css' ), 101 );
			add_filter( 'wpscom_navbar_brand',     array( $this, 'navbar_brand' ) );
			add_filter( 'body_class',                array( $this, 'navbar_body_class' ) );
			add_action( 'widgets_init',              array( $this, 'sl_widgets_init' ), 40 );
			add_action( 'wpscom_post_main_nav',    array( $this, 'navbar_sidebar' ) );
			add_action( 'wpscom_pre_wrap',         array( $this, 'secondary_navbar' ) );
			add_action( 'widgets_init',              array( $this, 'slidedown_widgets_init' ), 50 );
			add_action( 'wp_enqueue_scripts',        array( $this, 'megadrop_script' ), 200 );
			add_action( 'wpscom_pre_wrap',         array( $this, 'content_wrapper_static_left_open' ) );
			add_action( 'wpscom_after_footer',     array( $this, 'content_wrapper_static_left_close' ), 1 );

			if ( isset( $wpscom_settings['secondary_navbar_margin'] ) && $wpscom_settings['secondary_navbar_margin'] != 0 ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'secondary_navbar_margin' ), 101 );
			}

			if ( isset( $wpscom_settings['navbar_toggle'] ) ) {

				if ( $wpscom_settings['navbar_toggle'] == 'left' ) {
					$hook_navbar_slidedown_toggle = 'wpscom_pre_content';
				} else {
					$hook_navbar_slidedown_toggle = 'wpscom_inside_nav_begin';
				}

			} else {

				$hook_navbar_slidedown_toggle = 'wpscom_inside_nav_begin';

			}

			add_action( $hook_navbar_slidedown_toggle, array( $this, 'navbar_slidedown_toggle' ) );

			if ( isset( $wpscom_settings['navbar_toggle'] ) ) {

				if ( $wpscom_settings['navbar_toggle'] == 'left' ) {
					$hook_navbar_slidedown_content = 'wpscom_pre_content';
				} else {
					$hook_navbar_slidedown_content = 'wpscom_do_navbar';
				}

			} else {

				$hook_navbar_slidedown_content = 'wpscom_do_navbar';

			}

			add_action( $hook_navbar_slidedown_content, array( $this, 'navbar_slidedown_content' ), 99 );
		}

		/**
		 * Modify the nav class.
		 */
		function nav_class() {
			global $wpscom_settings;

			if ( $wpscom_settings['navbar_nav_right'] == '1' ) {
				return 'navbar-nav nav pull-right';
			} else {
				return 'navbar-nav nav';
			}
		}


		/*
		 * The template for the primary navbar searchbox
		 */
		function navbar_pre_searchbox() {
			global $wpscom_settings;

			$show_searchbox = $wpscom_settings['navbar_search'];
			if ( $show_searchbox == '1' ) : ?>
				<form role="search" method="get" id="searchform" class="form-search pull-right navbar-form" action="<?php echo home_url('/'); ?>">
					<label class="hide" for="s"><?php _e('Search for:', 'wpscom'); ?></label>
					<input type="text" value="<?php if (is_search()) { echo get_search_query(); } ?>" name="s" id="s" class="form-control search-query" placeholder="&#xf002">
				</form>
			<?php endif;
		}

		/**
		 * Modify the navbar class.
		 */
		public static function navbar_class( $navbar = 'main') {
			global $wpscom_settings;

			$fixed    = $wpscom_settings['navbar_fixed'];
			$fixedpos = $wpscom_settings['navbar_fixed_position'];
			$style    = $wpscom_settings['navbar_style'];
			$toggle   = $wpscom_settings['navbar_toggle'];
			$left     = ( $toggle == 'left' ) ? true : false;

			$bp = self::sl_breakpoint();

			$defaults = 'navbar navbar-default topnavbar';

			if ( $fixed != 1 ) {
				$class = ' navbar-static-top';
			} else {
				$class = ( $fixedpos == 1 ) ? ' navbar-fixed-bottom' : ' navbar-fixed-top';
			}

			$class = $defaults . $class;

			if ( $left ) {
				$extra_classes = 'navbar navbar-default static-left ' . $bp .  ' col-' . $bp . '-' . $wpscom_settings['layout_secondary_width'];
				$class = $extra_classes;
			}

			if ( $navbar != 'secondary' ) {
				return $class . ' ' . $style;
			} else {
				return 'navbar ' . $style;
			}
		}

		/**
		 * Modify the grid-float-breakpoint using Bootstrap classes.
		 */
		public static function sl_breakpoint() {
			global $wpscom_settings;

			$break    = $wpscom_settings['grid_float_breakpoint'];

			$bp = ( $break == 'min' || $break == 'screen_xs_min' ) ? 'xs' : 'xs';
			$bp = ( $break == 'screen_sm_min' )                    ? 'sm' : $bp;
			$bp = ( $break == 'screen_md_min' )                    ? 'md' : $bp;
			$bp = ( $break == 'screen_lg_min' || $break == 'max' ) ? 'lg' : $bp;

			return $bp;
		}

		/**
		 * Add some CSS for the navbar when needed.
		 */
		function navbar_css() {
			global $wpscom_settings;

			$navbar_bg_opacity = $wpscom_settings['navbar_bg_opacity'];
			$style = '';

			$opacity = ( $navbar_bg_opacity == '' ) ? '0' : ( intval( $navbar_bg_opacity ) ) / 100;

			if ( $opacity != 1 && $opacity != '' ) {
				$bg  = str_replace( '#', '', $wpscom_settings['navbar_bg'] );
				$rgb = Compound_Color::get_rgb( $bg, true );
				$opacityie = str_replace( '0.', '', $opacity );

				$style .= '.navbar, .navbar-default {';

				if ( $opacity != 1 && $opacity != '') {
					$style .= 'background: transparent; background: rgba(' . $rgb . ', ' . $opacity . '); filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#' . $opacityie . $bg . ',endColorstr=#' . $opacityie . $bg . '); ;';
				} else {
					$style .= 'background: #' . $bg . ';';
				}

				$style .= '}';
			}

			if ( $wpscom_settings['navbar_margin'] != 1 ) {
				$style .= '.navbar-static-top { margin-top:'. $wpscom_settings['navbar_margin'] . 'px; margin-bottom:' . $wpscom_settings['navbar_margin'] . 'px; }';
			}

			wp_add_inline_style( 'wpscom_css', $style );
		}

		/**
		 * get the navbar branding options (if the branding module exists)
		 * and then add the appropriate logo or sitename.
		 */
		function navbar_brand() {
			global $wpscom_settings;

			$logo           = $wpscom_settings['logo'];
			$branding_class = ! empty( $logo['url'] ) ? 'logo' : 'text';
			$branding = '';

			if ( $wpscom_settings['navbar_brand'] === 'on' ) {
				$branding  = '<a class="navbar-brand ' . $branding_class . '" href="' . home_url('/') . '">';
				$branding .= $wpscom_settings['navbar_logo'] == 1 ? Compound_Branding::logo() : get_bloginfo( 'name' );
				$branding .= '</a>';
			} elseif ( $wpscom_settings['navbar_brand'] === 'off' ){
				$branding = '';
			} elseif ( $wpscom_settings['navbar_brand'] === 'both' ){
				$branding  = '<a class="navbar-brand ' . $branding_class . '" href="' . home_url('/') . '">';
				$branding .= Compound_Branding::logo();
				$branding .= '</a>';
				$branding .= '<span class="navbar-sitename">' .get_bloginfo( 'name' ) .'</span>';
			}
			return $branding;
		}

		/**
		 * Add and remove body_class() classes
		 */
		function navbar_body_class( $classes ) {
			global $wpscom_settings;

			// Add 'top-navbar' or 'bottom-navabr' class if using Bootstrap's Navbar
			// Used to add styling to account for the WordPress admin bar
			if ( $wpscom_settings['navbar_fixed'] == 1 && $wpscom_settings['navbar_fixed_position'] != 1 && $wpscom_settings['navbar_toggle'] != 'left' ) {
				$classes[] = 'top-navbar';
			} elseif ( $wpscom_settings['navbar_fixed'] == 1 && $wpscom_settings['navbar_fixed_position'] == 1 ) {
				$classes[] = 'bottom-navbar';
			}

			return $classes;
		}

		/**
		 * Register sidebars and widgets
		 */
		function sl_widgets_init() {
			register_sidebar( array(
				'name'          => __( 'In-Navbar Widget Area', 'wpscom' ),
				'id'            => 'navbar',
				'description'   => __( 'This widget area will show up in your NavBars. This is most useful when using a static-left navbar.', 'wpscom' ),
				'before_widget' => '<div id="%1$s" class="in-navbar">',
				'after_widget'  => '</div>',
				'before_title'  => '<h1>',
				'after_title'   => '</h1>',
			) );
		}

		/**
		 * Add the sidebar to the navbar.
		 */
		function navbar_sidebar() {
			dynamic_sidebar( 'navbar' );
		}

		/**
		 * The contents of the secondary navbar
		 */
		function secondary_navbar() {
			global $wpscom_settings, $wpscom_framework;

			if ( has_nav_menu( 'secondary_navigation' ) ) : ?>

				<?php echo $wpscom_framework->open_container( 'div' ); ?>
					<header class="secondary navbar navbar-default <?php echo self::navbar_class( 'secondary' ); ?>" role="banner">
						<button data-target=".nav-secondary" data-toggle="collapse" type="button" class="navbar-toggle">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<?php
						if ( $wpscom_settings['navbar_secondary_social'] != 0 ) {
							SS_Framework_Bootstrap::navbar_social_links();
						} ?>
						<nav class="nav-secondary navbar-collapse collapse" role="navigation">
							<?php wp_nav_menu( array( 'theme_location' => 'secondary_navigation', 'menu_class' => apply_filters( 'wpscom_nav_class', 'navbar-nav nav' ) ) ); ?>
						</nav>
					</header>
				<?php echo $wpscom_framework->close_container( 'div' ); ?>

			<?php endif;
		}

		/**
		 * Add margin to the secondary nvbar if needed
		 */
		function secondary_navbar_margin() {
			global $wpscom_settings;

			$secondary_navbar_margin = $wpscom_settings['secondary_navbar_margin'];
			$style = '.secondary.navbar { margin-top:' . $secondary_navbar_margin . 'px !important; margin-bottom:'. $secondary_navbar_margin .'px !important; }';

			wp_add_inline_style( 'wpscom_css', $style );
		}

		/**
		 * Register widget areas for the navbar dropdowns.
		 */
		function slidedown_widgets_init() {
			// Register widgetized areas
			register_sidebar( array(
				'name'          => __( 'Navbar Slide-Down Top', 'wpscom' ),
				'id'            => 'navbar-slide-down-top',
				'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-inner">',
				'after_widget'  => '</div></section>',
				'before_title'  => '<h3>',
				'after_title'   => '</h3>',
			) );

			register_sidebar( array(
				'name'          => __( 'Navbar Slide-Down 1', 'wpscom' ),
				'id'            => 'navbar-slide-down-1',
				'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-inner">',
				'after_widget'  => '</div></section>',
				'before_title'  => '<h3>',
				'after_title'   => '</h3>',
			) );

			register_sidebar( array(
				'name'          => __( 'Navbar Slide-Down 2', 'wpscom' ),
				'id'            => 'navbar-slide-down-2',
				'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-inner">',
				'after_widget'  => '</div></section>',
				'before_title'  => '<h3>',
				'after_title'   => '</h3>',
			) );

			register_sidebar( array(
				'name'          => __( 'Navbar Slide-Down 3', 'wpscom' ),
				'id'            => 'navbar-slide-down-3',
				'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-inner">',
				'after_widget'  => '</div></section>',
				'before_title'  => '<h3>',
				'after_title'   => '</h3>',
			) );

			register_sidebar( array(
				'name'          => __( 'Navbar Slide-Down 4', 'wpscom' ),
				'id'            => 'navbar-slide-down-4',
				'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-inner">',
				'after_widget'  => '</div></section>',
				'before_title'  => '<h3>',
				'after_title'   => '</h3>',
			) );
		}

		/*
		 * Calculates the class of the widget areas based on a 12-column bootstrap grid.
		 */
		public static function navbar_widget_area_class() {
			$str = 0;
			if ( is_active_sidebar( 'navbar-slide-down-1' ) ) { $str++; }
			if ( is_active_sidebar( 'navbar-slide-down-2' ) ) { $str++; }
			if ( is_active_sidebar( 'navbar-slide-down-3' ) ) { $str++; }
			if ( is_active_sidebar( 'navbar-slide-down-4' ) ) { $str++; }

			$colwidth = ( $str > 0 ) ? 12 / $str : 12;

			return $colwidth;
		}

		/*
		 * Prints the content of the slide-down widget areas.
		 */
		function navbar_slidedown_content() {
			global $wpscom_settings;

			if ( is_active_sidebar( 'navbar-slide-down-1' ) || is_active_sidebar( 'navbar-slide-down-2' ) || is_active_sidebar( 'navbar-slide-down-3' ) || is_active_sidebar( 'navbar-slide-down-4' ) || is_active_sidebar( 'navbar-slide-down-top' ) ) : ?>
				<div class="before-main-wrapper">
					<?php $megadrop_class = ( $wpscom_settings['site_style'] != 'fluid' ) ? 'top-megamenu container' : 'top-megamenu'; ?>
					<div id="megaDrop" class="<?php echo $megadrop_class; ?>">
						<?php $widgetareaclass = 'col-sm-' . self::navbar_widget_area_class(); ?>

						<?php if ( is_active_sidebar( 'navbar-slide-down-top' ) ) : ?>
							<?php dynamic_sidebar( 'navbar-slide-down-top' ); ?>
						<?php endif; ?>

						<div class="row">
							<?php if ( is_active_sidebar( 'navbar-slide-down-1' ) ) : ?>
								<div class="<?php echo $widgetareaclass; ?>">
									<?php dynamic_sidebar( 'navbar-slide-down-1' ); ?>
								</div>
							<?php endif; ?>

							<?php if ( is_active_sidebar( 'navbar-slide-down-2' ) ) : ?>
								<div class="<?php echo $widgetareaclass; ?>">
									<?php dynamic_sidebar( 'navbar-slide-down-2' ); ?>
								</div>
							<?php endif; ?>

							<?php if ( is_active_sidebar( 'navbar-slide-down-3' ) ) : ?>
								<div class="<?php echo $widgetareaclass; ?>">
									<?php dynamic_sidebar( 'navbar-slide-down-3' ); ?>
								</div>
							<?php endif; ?>

							<?php if ( is_active_sidebar( 'navbar-slide-down-4' ) ) : ?>
								<div class="<?php echo $widgetareaclass; ?>">
									<?php dynamic_sidebar( 'navbar-slide-down-4' ); ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			<?php endif;
		}

		/**
		 * When static-left navbar is selected, we need to add a wrapper to the whole content
		 */
		function content_wrapper_static_left_open() {
			global $wpscom_settings, $wpscom_framework;

			$breakpoint = self::sl_breakpoint();

			if ( $breakpoint == 'xs' ) {
				$width = 'mobile';
			} elseif ( $breakpoint == 'sm' ) {
				$width = 'tablet';
			} elseif ( $breakpoint == 'md' ) {
				$width = 'medium';
			} elseif ( $breakpoint == 'lg' ) {
				$width = 'large';
			}

			if ( isset( $wpscom_settings['navbar_toggle'] ) && $wpscom_settings['navbar_toggle'] == 'left' ) {
				echo $wpscom_framework->open_col( 'div', array( $width => 12 - $wpscom_settings['layout_secondary_width'] ), 'content-wrapper-left', 'col-' . $breakpoint . '-offset-' . $wpscom_settings['layout_secondary_width'] );
			}
		}

		/**
		 * When static-left navbar is selected, we need to close the wrapper opened by the content_wrapper_static_left function.
		 */
		function content_wrapper_static_left_close() {
			global $wpscom_settings, $wpscom_framework;

			if ( isset( $wpscom_settings['navbar_toggle'] ) && $wpscom_settings['navbar_toggle'] == 'left' ) {
				echo $wpscom_framework->close_col( 'div' );
			}
		}

		/**
		 * The icon that helps us open/close the dropdown widgets.
		 */
		function navbar_slidedown_toggle() {
			global $wpscom_settings;

			$navbar_color = $wpscom_settings['navbar_bg'];
			$navbar_mode  = $wpscom_settings['navbar_toggle'];
			$trigger = (
				is_active_sidebar( 'navbar-slide-down-top' ) ||
				is_active_sidebar( 'navbar-slide-down-1' ) ||
				is_active_sidebar( 'navbar-slide-down-2' ) ||
				is_active_sidebar( 'navbar-slide-down-3' ) ||
				is_active_sidebar( 'navbar-slide-down-4' )
			) ? true : false;

			if ( $trigger ) {

				$class = ( $navbar_mode == 'left' ) ? ' static-left' : ' nav-toggle';
				$pre   = ( $navbar_mode != 'left' ) ? '<ul class="nav navbar-nav"><li>' : '';
				$post  = ( $navbar_mode != 'left' ) ? '</li></ul>' : '';

				echo $pre . '<a class="toggle-nav' . $class . '" href="#"><i class="el-icon-chevron-down"></i></a>' . $post;

			}
		}

		/**
		 * The script responsible for showing/hiding the dropdown widget areas from the navbar.
		 */
		function megadrop_script() {
			if ( is_active_sidebar( 'navbar-slide-down-top' ) || is_active_sidebar( 'navbar-slide-down-1' ) || is_active_sidebar( 'navbar-slide-down-2' ) || is_active_sidebar( 'navbar-slide-down-3' ) || is_active_sidebar( 'navbar-slide-down-4' ) ) {
				wp_register_script( 'wpscom_megadrop', get_template_directory_uri() . '/assets/js/megadrop.js', false, null, false );
				wp_enqueue_script( 'wpscom_megadrop' );
			}
		}
	}
}
