<?php

global $wpscom_settings, $wp_customize;

global $wpscom_active_framework;
$wpscom_active_framework = array(
	'shortname' => 'bootstrap',
	'name'      => 'Bootstrap',
	'classname' => 'Compound_Framework_Bootstrap',
	'compiler'  => 'less_php'
);

// Include the framework class
include_once( dirname( __FILE__ ) . '/class-Compound_Framework_Bootstrap.php' );

if ( 'bootstrap' == COMPOUND_FRAMEWORK ) {
	define( 'COMPOUND_FRAMEWORK_PATH', dirname( __FILE__ ) );
}

if ( isset( $wp_customize ) ) {
	include_once( COMPOUND_FRAMEWORK_PATH . '/includes/customizer.php' ); // Customizer mods
}
