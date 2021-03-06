<?php


if ( !class_exists( 'Compound_Branding' ) ) {

	/**
	* The Branding module
	*/
	class Compound_Branding {

		function __construct() {
			add_action( 'wp_head', array( $this, 'icons' ) );
		}

		function icons() {
			global $wpscom_settings;

			$favicon_item    = $wpscom_settings['favicon'];
			$apple_icon_item = $wpscom_settings['apple_icon'];

			// Add the favicon
			if ( ! empty( $favicon_item['url'] ) && $favicon_item['url'] != '' ) {
				$favicon = Compound_Image::_resize( $favicon_item['url'], 32, 32, true, false );

				echo '<link rel="shortcut icon" href="'.$favicon['url'].'" type="image/x-icon" />';
			}

			// Add the apple icons
			if ( ! empty( $apple_icon_item['url'] ) ) {
				$iphone_icon        = Compound_Image::_resize( $apple_icon_item['url'], 57, 57, true, false );
				$iphone_icon_retina = Compound_Image::_resize( $apple_icon_item['url'], 57, 57, true, true );
				$ipad_icon          = Compound_Image::_resize( $apple_icon_item['url'], 72, 72, true, false );
				$ipad_icon_retina   = Compound_Image::_resize( $apple_icon_item['url'], 72, 72, true, true );
				?>

				<!-- For iPhone --><link rel="apple-touch-icon-precomposed" href="<?php echo $iphone_icon['url'] ?>">
				<!-- For iPhone 4 Retina display --><link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo $iphone_icon_retina['url'] ?>">
				<!-- For iPad --><link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $ipad_icon['url'] ?>">
				<!-- For iPad Retina display --><link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo $ipad_icon_retina['url'] ?>">
				<?php
			}
		}

		/*
		 * The site logo.
		 * If no custom logo is uploaded, use the sitename
		 */
		public static function logo() {
			global $wpscom_settings;
			$logo  = $wpscom_settings['logo'];

			if ( ! empty( $logo['url'] ) ) {
				//added to aid with SSL
				$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https:" : "http:";
				$logo['url'] = $protocol. str_replace(array('http:', 'https:'), '', $logo['url']);
				
				$branding = '<img id="site-logo" src="' . $logo['url'] . '" alt="' . get_bloginfo( 'name' ) . '">';
			} else {
				$branding = '<span class="sitename">' . get_bloginfo( 'name' ) . '</span>';
			}

			return $branding;
		}
	}
}
