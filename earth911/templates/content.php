<?php

global $wpscom_framework;

echo '<article '; post_class('col-md-4'); echo '>';
echo '<div class="article-drop-shadow">';
echo '<div class="article-thumbnail">'; echo the_post_thumbnail(); echo '</div>';
	echo '<div class="entry-summary">';
	wpscom_title_section( true, 'h2', true );

		echo get_the_excerpt();
		echo '<br><br>';
		echo the_author_posts_link(),  the_time('  •  F jS, Y');
		echo $wpscom_framework->clearfix();
	echo '</div>';

echo '</div>';
echo '</article>';