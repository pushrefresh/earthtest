<?php

global $wpscom_framework;

while ( have_posts() ) : the_post();

	echo '<div class="single-header">';
	echo '<div class="single-featured-img">';
	echo the_post_thumbnail();
	echo '</div>';
	echo '<div class="single-title-meta col-md-12 col-md-offset-1">';
	echo '<div class="single-title">';
	wpscom_title_section();
	echo '</div>';
	echo '<div class="single-meta">';
	do_action( 'wpscom_entry_meta' );
	echo '</div>';
	echo '</div>';
	echo '</div>';

	echo '<article class="' . implode( ' ', get_post_class( 'col-md-7 col-md-offset-1' ) ) . '">';
		do_action( 'wpscom_single_top' );

		echo '<div class="entry-content">';
			do_action( 'wpscom_single_pre_content' );
			the_content();
			echo $wpscom_framework->clearfix();
			do_action( 'wpscom_single_after_content' );
		echo '</div>';

		// The comments section loaded when appropriate
		echo '<div class="comments-header">'; echo '<h1>'; echo 'Comments'; echo '</h1>'; echo '</div>';
		echo '<div class="fb-comments" data-href="http://localhost:8888/" data-numposts="5">'; echo '</div>';

		do_action( 'wpscom_in_article_bottom' );
	echo '</article>';

endwhile;

?>

<div class="col-sm-3 single-sidebar">
<?php if ( wpscom_display_primary_sidebar() && is_active_sidebar('sidebar-primary')) : ?>
					<aside id="sidebar-primary" class="sidebar <?php wpscom_section_class( 'primary', true ); ?>" role="complementary">
						<?php if ( ! has_action( 'wpscom_sidebar_override' ) ) {
							include wpscom_sidebar_path();
						} else {
							do_action( 'wpscom_sidebar_override' );
						} ?>
					</aside><!-- /.sidebar -->
				<?php endif; ?>
				</div>