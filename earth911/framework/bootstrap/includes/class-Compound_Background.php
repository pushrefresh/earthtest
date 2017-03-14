<?php

if ( ! class_exists( 'Compound_Background' ) ) {

	/**
	* The "Background" module
	*/
	class Compound_Background {

		function __construct() {
			add_action( 'wp_enqueue_scripts', array( $this, 'css' ), 101 );
			add_action( 'plugins_loaded',     array( $this, 'upgrade_options' ) );
		}

		function css() {
			global $wpscom_settings;

			$content_opacity = $wpscom_settings['body_bg_opacity'];
			$bg_color        = $wpscom_settings['body_bg'];

			if ( isset( $bg_color['background-color'] ) ) {
				$bg_color = $bg_color['background-color'];
			} else {
				$bg_color = '#ffffff';
			}

			// Style defaults to null.
			$style = null;

			// The Content background color
			if ( $content_opacity < 100 ) {

				$content_bg = 'background:' . Compound_Color::get_rgba( $bg_color, $content_opacity ) . ';';
				$style = '.wrap.main-section div.content .bg {' . $content_bg . '}';

			}

			wp_add_inline_style( 'wpscom_css', $style );
		}
	}
}
