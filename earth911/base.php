<?php wpscom_get_template_part( 'templates/head' ); ?>
<body <?php body_class(); ?>>
<a href="#content" class="sr-only"><?php _e( 'Skip to main content', 'wpscom' ); ?></a>
<?php global $wpscom_framework; ?>

	<!--[if lt IE 8]>
		<?php echo $wpscom_framework->alert( 'warning', __(' You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.', 'wpscom' ) ); ?>
	<![endif]-->

	<?php do_action( 'get_header' ); ?>

	<?php do_action( 'wpscom_pre_top_bar' ); ?>

	<?php wpscom_get_template_part( apply_filters( 'wpscom_top_bar_template', 'templates/top-bar' ) ); ?>

	<?php do_action( 'wpscom_pre_wrap' ); ?>

	<?php echo $wpscom_framework->open_container( 'div', 'wrap-main-section', 'wrap main-section' ); ?>

		<?php do_action( 'wpscom_pre_content' ); ?>

		<div id="content" class="content">
			<?php echo $wpscom_framework->open_row( 'div', null, 'bg' ); ?>

				<?php do_action( 'wpscom_pre_main' ); ?>

					<main class="main col-md-12" <?php if (is_home()){ echo 'id="home-blog"';} ?> role="main">
						<?php include wpscom_template_path(); ?>
					</main><!-- /.main -->

				<?php do_action( 'wpscom_after_main' ); ?>

				
				<?php echo $wpscom_framework->clearfix(); ?>
			<?php echo $wpscom_framework->close_row( 'div' ); ?>
		</div><!-- /.content -->
		<?php do_action( 'wpscom_after_content' ); ?>
	<?php echo $wpscom_framework->close_container( 'div' ); ?><!-- /.wrap -->
	<?php

	do_action( 'wpscom_pre_footer' );

	if ( ! has_action( 'wpscom_footer_override' ) ) {
		wpscom_get_template_part( 'templates/footer' );
	} else {
		do_action( 'wpscom_footer_override' );
	}

	do_action( 'wpscom_after_footer' );

	wp_footer();

	?>
</body>
</html>
