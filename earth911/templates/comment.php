<?php

global $wpscom_framework;

echo get_avatar( $comment, $size = '64' );

echo '<div class="media-body">';
	echo '<h4 class="media-heading">' . get_comment_author_link() . '</h4>';
	echo '<time datetime="' . get_comment_date( 'c' ) . '"><a href="' . htmlspecialchars( get_comment_link( $comment->comment_ID ) ) . '">';
		printf( __( '%1$s', 'wpscom' ), get_comment_date(),  get_comment_time() );
	echo '</a></time>';

	edit_comment_link( __( '(Edit)', 'wpscom' ), '', '' );

	if ( $comment->comment_approved == '0' ) {
		echo $wpscom_framework->alert( 'info', __( 'Your comment is awaiting moderation.', 'wpscom' ) );
	}

	comment_text();
	comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) );
