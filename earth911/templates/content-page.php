<?php

global $wpscom_framework;

while ( have_posts() ) : the_post();
	wpscom_title_section();
	do_action( 'wpscom_entry_meta' );
	do_action( 'wpscom_page_pre_content' );

	echo '<div class="entry-content">';
	do_action( 'wpscom_page_pre_content' );
		the_content();
		echo $wpscom_framework->clearfix();
	echo '</div>';
	
	wpscom_meta( 'cats' );
	wpscom_meta( 'tags' );
	do_action( 'wpscom_page_after_content' );

	wp_link_pages( array( 'before' => '<nav class="pagination">', 'after' => '</nav>' ) );
endwhile;