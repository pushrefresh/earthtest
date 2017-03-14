<?php

if ( ! class_exists( 'Compound_Framework' ) ) {

	/**
	* The "Advanced" module
	*/
	class Compound_Framework {

		/**
		 * Class constructor
		 */
		function __construct() {
			global $wpscom_settings;

			require_once dirname( __FILE__ ) . '/core/class-Compound_Framework_Core.php';

			do_action( 'wpscom_include_frameworks' );

			if ( ! defined( 'COMPOUND_FRAMEWORK' ) ) {
				$active_framework = 'bootstrap';
			}

			// If the active framework is Bootstrap, include it.
			if ( ( defined( 'COMPOUND_FRAMEWORK' ) && 'bootstrap' == COMPOUND_FRAMEWORK ) || ! defined( 'COMPOUND_FRAMEWORK' ) ) {
				require_once 'bootstrap/framework.php';
			}

			global $wpscom_active_framework;

			$compiler = false;
			// Return the classname of the active framework.
			$active   = $wpscom_active_framework['classname'];

			$compiler = $wpscom_active_framework['compiler'];

			global $wpscom_framework;
			$wpscom_framework = new $active;

			// Get the compiler that will be used and initialize it.
			if ( $compiler ) {

				if ( $compiler == 'less_php' ) {

					require_once 'compilers/less-php/class-Compound_Less_php.php';
					$compiler_init = new Compound_Less_PHP();

				} elseif ( $compiler == 'sass_php' ) {

					require_once 'compilers/sass-php/class-Compound_Sass_php.php';
					$compiler_init = new Compound_Sass_PHP();

				}
			}

		}
	}
}

$framework = new Compound_Framework();
