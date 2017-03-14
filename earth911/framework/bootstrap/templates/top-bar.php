<?php
global $wpscom_settings;

$navbar_toggle = $wpscom_settings['navbar_toggle'];

if ( $navbar_toggle != 'none' ) {
	if ( ! has_action( 'wpscom_header_top_navbar_override' ) ) { ?>
	<section id="to-top"></section>
		<div class="social-header">
			<div class="container">
				<a href="https://www.facebook.com/Earth911" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a>
				<a href="https://twitter.com/earth911" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a>
				<a href="https://plus.google.com/+Earth911" target="_blank"><i class="fa fa-google-plus" aria-hidden="true"></i></a>
				<a href="https://www.youtube.com/user/Earth911TV" target="_blank"><i class="fa fa-youtube-play" aria-hidden="true"></i></a>
				<a href="https://www.pinterest.com/earth911/" target="_blank"><i class="fa fa-pinterest" aria-hidden="true"></i></a>
			</div>
		</div>
		<header id="banner-header" class="banner <?php echo apply_filters( 'wpscom_navbar_class', 'navbar navbar-default' ); ?>" role="banner">
			<div class="<?php echo apply_filters( 'wpscom_navbar_container_class', 'container' ); ?>">
				<div class="navbar-header">
					<?php echo apply_filters( 'wpscom_nav_toggler', '
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".nav-main, .nav-extras">
						<span class="sr-only">' . __( 'Toggle navigation', 'wpscom' ) . '</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>' ); ?>
					<?php echo apply_filters( 'wpscom_navbar_brand', '<a class="navbar-brand text" href="' . home_url('/') . '">' . get_bloginfo( 'name' ) . '</a>' ); ?>
				</div>
				<?php if ( has_action( 'wpscom_pre_main_nav' ) ) : ?>
					<div class="nav-extras">
						<?php do_action( 'wpscom_pre_main_nav' ); ?>
					</div>
				<?php endif; ?>
				<nav class="nav-main navbar-collapse collapse" role="navigation">
					<?php
					do_action( 'wpscom_inside_nav_begin' );
					if ( has_nav_menu( 'primary_navigation' ) )
						wp_nav_menu( array( 'theme_location' => 'primary_navigation', 'menu_class' => apply_filters( 'wpscom_nav_class', 'navbar-nav nav' ) ) );

					do_action( 'wpscom_inside_nav_end' );
					?>
				</nav>
				<?php do_action( 'wpscom_post_main_nav' ); ?>
			</div>
		</header>

		<header id="banner-header" class="banner navbar navbar-default navbar-fixed-top" role="banner">
			<div class="<?php echo apply_filters( 'wpscom_navbar_container_class', 'container' ); ?>">
				<div class="navbar-header">
					<?php echo apply_filters( 'wpscom_nav_toggler', '
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".nav-main, .nav-extras">
						<span class="sr-only">' . __( 'Toggle navigation', 'wpscom' ) . '</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>' ); ?>
					<?php echo apply_filters( 'wpscom_navbar_brand', '<a class="navbar-brand text" href="' . home_url('/') . '">' . get_bloginfo( 'name' ) . '</a>' ); ?>
				</div>
				<?php if ( has_action( 'wpscom_pre_main_nav' ) ) : ?>
					<div class="nav-extras">
						<?php do_action( 'wpscom_pre_main_nav' ); ?>
					</div>
				<?php endif; ?>
				<nav class="nav-main navbar-collapse collapse" role="navigation">
					<?php
					do_action( 'wpscom_inside_nav_begin' );
					if ( has_nav_menu( 'primary_navigation' ) )
						wp_nav_menu( array( 'theme_location' => 'primary_navigation', 'menu_class' => apply_filters( 'wpscom_nav_class', 'navbar-nav nav' ) ) );

					do_action( 'wpscom_inside_nav_end' );
					?>
				</nav>
				<?php do_action( 'wpscom_post_main_nav' ); ?>
			</div>
		</header>
		<?php do_action( 'wpscom_do_navbar' ); ?>

<?php
	} else {
		do_action( 'wpscom_header_top_navbar_override' );
	}
} else {
	return '';
}