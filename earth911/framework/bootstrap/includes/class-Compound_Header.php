<?php


if ( ! class_exists( 'Compound_Header' ) ) {

	/**
	* The Header module
	*/
	class Compound_Header {

		function __construct() {
			add_action( 'widgets_init',       array( $this, 'header_widgets_init' ), 30 );
			add_action( 'wpscom_pre_wrap', array( $this, 'branding' ), 3 );
			add_action( 'wp_enqueue_scripts', array( $this, 'css' ), 101 );

		}

		/**
		 * Register sidebars and widgets
		 */
		function header_widgets_init() {
			register_sidebar( array(
				'name'          => __( 'Header Area', 'wpscom' ),
				'id'            => 'header-area',
				'before_widget' => '<div>',
				'after_widget'  => '</div>',
				'before_title'  => '<h1>',
				'after_title'   => '</h1>',
			) );
		}

		/*
		 * The Header template
		 */
		function branding() {
			global $wpscom_settings;

			if ( $wpscom_settings['header_toggle'] == 1 ) {
				echo '<div class="before-main-wrapper">';

				if ( $wpscom_settings['site_style'] == 'boxed' ) {
					echo '<div class="container header-boxed">';
				}

				echo '<div class="header-wrapper">';

				if ( $wpscom_settings['site_style'] == 'wide' ) {
					echo '<div class="container">';
				}

				if ( $wpscom_settings['header_branding'] == 1 ) {
					echo '<a class="brand-logo" href="' . home_url() . '/"><h1>' . Compound_Branding::logo() . '</h1></a>';
				}

				if ( $wpscom_settings['header_branding'] == 1 ) {
					$pullclass = ' class="pull-right"';
				} else {
					$pullclass = null;
				}

				echo '<div' . $pullclass . '>';
				dynamic_sidebar( 'header-area' );
				echo '</div>';

				if ( $wpscom_settings['site_style'] == 'wide' ) {
					echo '</div>';
				}

				echo '</div>';

				if ( $wpscom_settings['site_style'] == 'boxed' ) {
					echo '</div>';
				}

				echo '</div>';
			}
		}

		/*
		 * Any necessary extra CSS is generated here
		 */
		function css() {
			global $wpscom_settings;

			if ( is_array( $wpscom_settings['header_bg'] ) ) {
				$bg = Compound_Color::sanitize_hex( $wpscom_settings['header_bg']['background-color'] );
			} else {
				$bg = Compound_Color::sanitize_hex( $wpscom_settings['header_bg'] );
			}
			$cl = Compound_Color::sanitize_hex( $wpscom_settings['header_color'] );

			$header_margin_top    = $wpscom_settings['header_margin_top'];
			$header_margin_bottom = $wpscom_settings['header_margin_bottom'];

			$opacity  = ( intval( $wpscom_settings['header_bg_opacity'] ) ) / 100;

			$rgb      = Compound_Color::get_rgb( $bg, true );

			if ( $wpscom_settings['site_style'] == 'boxed' ) {
				$element = 'body .before-main-wrapper .header-boxed';
			} else {
				$element = 'body .before-main-wrapper .header-wrapper';
			}

			if ( $wpscom_settings['header_toggle'] == 1 ) {
				$style  = $element . ' a, ' . $element . ' h1, ' . $element . ' h2, ' . $element . ' h3, ' . $element . ' h4, ' . $element . ' h5, ' . $element . ' h6  { color: ' . $cl . '; }';
				$style .= $element . '{ color: ' . $cl . ';';

				if ( $opacity < 1 && ! $wpscom_settings['header_bg']['background-image'] ) {
					$style .= 'background: rgb(' . $rgb . '); background: rgba(' . $rgb . ', ' . $opacity . ');';
				}

				if ( $header_margin_top > 0 ) {
					$style .= 'margin-top:' . $header_margin_top . 'px;';
				}

				if ( $header_margin_bottom > 0 ) {
					$style .= 'margin-bottom:' . $header_margin_bottom . 'px;';
				}

				$style .= '}';

				wp_add_inline_style( 'wpscom_css', $style );
			}
		}
	}
}
