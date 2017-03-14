<?php
function my_theme_enqueue_styles() {

    $parent_style = 'parent-style'; // This is 'twentyfifteen-style' for the Twenty Fifteen theme.

    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
    wp_enqueue_style( 'quest_css', get_stylesheet_directory_uri() . '/css/quest.css' );
    wp_enqueue_style( 'font_awesome_css', get_stylesheet_directory_uri() . '/css/font-awesome.min.css' );

	wp_enqueue_script( 'quest_js', get_stylesheet_directory_uri() . '/js/quest.js', array( 'jquery' ), '1.0', true );
}
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );

function wpdocs_nine_posts_on_homepage( $query ) {
    if ( $query->is_home() && $query->is_main_query() ) {
        $query->set( 'posts_per_page', 9 );
    }
}
add_action( 'pre_get_posts', 'wpdocs_nine_posts_on_homepage' );

function wpdocs_post_image_html( $html, $post_id, $post_image_id ) {
    $html = '<a href="' . get_permalink( $post_id ) . '" alt="' . esc_attr( get_the_title( $post_id ) ) . '">' . $html . '</a>';
    return $html;
}
add_filter( 'post_thumbnail_html', 'wpdocs_post_image_html', 10, 3 );

function get_custom_posts( $params ) {
extract( shortcode_atts( array (
'number' => '9',
'excerpt' => 100,
'readmore' => 'no',
'cpt' => 'post',
'readmoretext' => 'Read more'
), $params ) );

//$latest_posts = get_posts( 'category=0&numberposts=' . $number .      '&suppress_filters=false');     OLD
$latest_posts = query_posts( 'post_type='.$cpt.'&posts_per_page=' . $number );
wp_reset_query();

$result = '<div class="latest-posts">';
$count = count($latest_posts);
foreach ($latest_posts as $key => $latest_post) {
  $author = get_the_author_meta('nickname', $latest_post->post_author );
  $post_link = get_permalink( $latest_post->ID );
  $date = mysql2date(get_option('date_format'), $latest_post->post_date);
  $category = get_the_category_list( ', ', $parents = '', $latest_post->ID );

  $result .= '<div class="col-md-4 sc-page"><div class="item clearfix">';
 // POST THUMBNAIL
  if (get_the_post_thumbnail( $latest_post->ID, 'full' )) {
      $result .= '<div class="image">';
      $result .= '<a href="' . $post_link . '" class="greyscale">';
      $result .= get_the_post_thumbnail( $latest_post->ID, 'full' );
      $result .= '</a>';
      $result .= '</div>';
  }

  // POST BODY
  $result .= '<div class="text">';
  $result .= '<div class="title"><h3><a href="' . $post_link. '">' . $latest_post->post_title . '</a></h3></div>';
  if ( $latest_post->post_excerpt ) {
      $result .= '<p>' . $latest_post->post_excerpt . '</p>';
  }
  else {
      $limit = $excerpt;
      $my_text = substr($latest_post->post_content, 0, $limit);
      $pos = strrpos($my_text, " ");
      $my_post_text = substr($my_text, 0, ($pos ? $pos : -1)) . "...";
      $read = "";
  if($readmore == 'yes'){
    $read = '&nbsp;<a href="' . $post_link. '">'.$readmoretext.'</a>';
  }
      $result .= '<p>' . strip_tags($my_post_text) . $read . '</p>';
      //$result .= '<p>' . substr_replace( $latest_post['0']->post_content, '...', 350 ) . '</p>';
  }

  $result .= '</div><!-- /.text -->';

  $result .= '</div></div>';
}
$result .= '</div>';

return $result;
}
add_shortcode( "get_posts", "get_custom_posts" );

?>