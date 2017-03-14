<?php

if ( ! class_exists( 'Compound_Blog' ) ) {

	/**
	* The "Blog" module
	*/
	class Compound_Blog {

		function __construct() {

			global $wpscom_settings;

			if ( ! class_exists( 'BuddyPress' ) || ( class_exists( 'BuddyPress' ) && ! wpscom_is_bp() ) ) {
				add_action( 'wpscom_entry_meta', array( $this, 'meta_custom_render' ) );
			}
			add_filter( 'excerpt_more', array( $this, 'excerpt_more' ) );
			add_action( 'wp', array( $this, 'remove_featured_image_per_post_type' ) );


			// Chamnge the excerpt length
			if ( isset( $wpscom_settings['post_excerpt_length'] ) ) {
				add_filter( 'excerpt_length', array( $this, 'excerpt_length' ) );
			}

			// Show full content instead of excerpt
			if ( isset( $wpscom_settings['blog_post_mode'] ) && 'full' == $wpscom_settings['blog_post_mode'] ) {
				add_filter( 'wpscom_do_the_excerpt', 'get_the_content' );
				add_filter( 'wpscom_do_the_excerpt', 'do_shortcode', 50 );
				add_action( 'wpscom_entry_footer', array( $this, 'archives_full_footer' ) );
			}

			// Hide post meta data in footer of single posts
			if ( isset( $wpscom_settings['single_meta'] ) && $wpscom_settings['single_meta'] == 0 ) {
				add_filter( 'wpscom_the_tags', '__return_null' );
				add_filter( 'wpscom_the_cats', '__return_null' );
			}
		}

		/**
		 * Footer for full-content posts.
		 * Used on archives when 'blog_post_mode' == full
		 */
		function archives_full_footer() { ?>
			<footer style="margin-top: 2em;">
				<i class="el-icon-tag"></i> <?php _e( 'Categories: ', 'wpscom' ); ?>
				<span class="label label-tag">
					<?php echo get_the_category_list( '</span> ' . '<span class="label label-tag">' ); ?>
				</span>

				<?php echo get_the_tag_list( '<i class="el-icon-tags"></i> ' . __( 'Tags: ', 'wpscom' ) . '<span class="label label-tag">', '</span> ' . '<span class="label label-tag">', '</span>' ); ?>

				<?php wp_link_pages( array(
					'before' => '<nav class="page-nav"><p>' . __( 'Pages:', 'wpscom' ),
					'after'  => '</p></nav>'
				) ); ?>
			</footer>
			<?php
		}

		/**
		 * Output of meta information for current post: categories, tags, permalink, author, and date.
		 */
		function meta_custom_render() {
			global $wpscom_framework, $wpscom_settings, $post;

			// get config and data
			$metas = $wpscom_settings['wpscom_entry_meta_config'];
			$date_format = $wpscom_settings['date_meta_format'];

			$categories_list = get_the_category_list( __( ', ', 'wpscom' ) );
			$tag_list        = get_the_tag_list( '', __( ', ', 'wpscom' ) );

			$i = 0;
			if ( is_array( $metas ) ) {
				foreach ( $metas as $meta => $value ) {
					if ( $meta == 'sticky' ) {
						if ( ! empty( $value ) && is_sticky() ) {
							$i++;
						}
					} elseif ( $meta == 'date' ) {
						if ( ! empty( $value ) ) {
							$i++;
						}
					} elseif ( $meta == 'category' ) {
						if ( ! empty( $value ) && has_category() ) {
							$i++;
						}
					} elseif ( $meta == 'tags' ) {
						if ( ! empty( $value ) && has_tag() ) {
							$i++;
						}
					} elseif ( $meta == 'author' ) {
						if ( ! empty( $value ) ) {
							$i++;
						}
					} elseif ( $meta == 'comment-count' && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
						if ( ! empty( $value ) ) {
							$i++;
						}
					}  elseif ( $meta == 'post-format' ) {
						if ( ! empty( $value ) ) {
							$i++;
						}
					}
				}
			}

			$col = ( $i >= 2 ) ? round( ( 12 / ( $i ) ), 0) : 12;

			$content = '';
			if ( is_array( $metas ) ) {
				foreach ( $metas as $meta => $value ) {
					// output sticky element
					if ( $meta == 'sticky' && ! empty( $value ) && is_sticky() ) {
						$content .= $wpscom_framework->open_col( 'span', array( 'medium' => $col ), null, 'featured-post' ) . '<i class="el-icon-flag icon"></i> ' . __( 'Sticky', 'wpscom' ) . $wpscom_framework->close_col( 'span' );
					}

					// output post format element
					if ( $meta == 'post-format' && ! empty( $value ) ) {
						if ( get_post_format( $post->ID ) === 'gallery' ) {
						  $content .= $wpscom_framework->open_col( 'span', array( 'medium' => $col ), null, 'post-format' ) . '<i class="el-icon-picture"></i> <a href="' . esc_url( get_post_format_link( 'gallery' ) ) . '">' . __('Gallery','wpscom') . '</a>' . $wpscom_framework->close_col( 'span' );
						}
						if ( get_post_format( $post->ID ) === 'aside' ) {
						  $content .= $wpscom_framework->open_col( 'span', array( 'medium' => $col ), null, 'post-format' ) . '<i class="el-icon-chevron-right"></i> <a href="' . esc_url( get_post_format_link( 'aside' ) ) . '">' . __('Aside','wpscom') . '</a>' . $wpscom_framework->close_col( 'span' );
						}
						if ( get_post_format( $post->ID ) === 'link' ) {
						  $content .= $wpscom_framework->open_col( 'span', array( 'medium' => $col ), null, 'post-format' ) . '<i class="el-icon-link"></i> <a href="' . esc_url( get_post_format_link( 'link' ) ) . '">' . __('Link','wpscom') . '</a>' . $wpscom_framework->close_col( 'span' );
						}
						if ( get_post_format( $post->ID ) === 'image' ) {
						  $content .= $wpscom_framework->open_col( 'span', array( 'medium' => $col ), null, 'post-format' ) . '<i class="el-icon-picture"></i> <a href="' . esc_url( get_post_format_link( 'image' ) ) . '">' . __('Image','wpscom') . '</a>' . $wpscom_framework->close_col( 'span' );
						}
						if ( get_post_format( $post->ID ) === 'quote' ) {
						  $content .= $wpscom_framework->open_col( 'span', array( 'medium' => $col ), null, 'post-format' ) . '<i class="el-icon-quotes-alt"></i> <a href="' . esc_url( get_post_format_link( 'quote' ) ) . '">' . __('Quote','wpscom') . '</a>' . $wpscom_framework->close_col( 'span' );
						}
						if ( get_post_format( $post->ID ) === 'status' ) {
						  $content .= $wpscom_framework->open_col( 'span', array( 'medium' => $col ), null, 'post-format' ) . '<i class="el-icon-comment"></i> <a href="' . esc_url( get_post_format_link( 'status' ) ) . '">' . __('Status','wpscom') . '</a>' . $wpscom_framework->close_col( 'span' );
						}
						if ( get_post_format( $post->ID ) === 'video' ) {
						  $content .= $wpscom_framework->open_col( 'span', array( 'medium' => $col ), null, 'post-format' ) . '<i class="el-icon-video"></i> <a href="' . esc_url( get_post_format_link( 'video' ) ) . '">' . __('Video','wpscom') . '</a>' . $wpscom_framework->close_col( 'span' );
						}
						if ( get_post_format( $post->ID ) === 'audio' ) {
						  $content .= $wpscom_framework->open_col( 'span', array( 'medium' => $col ), null, 'post-format' ) . '<i class="el-icon-volume-up"></i> <a href="' . esc_url( get_post_format_link( 'audio' ) ) . '">' . __('Audio','wpscom') . '</a>' . $wpscom_framework->close_col( 'span' );
						}
						if ( get_post_format( $post->ID ) === 'chat' ) {
						  $content .= $wpscom_framework->open_col( 'span', array( 'medium' => $col ), null, 'post-format' ) . '<i class="el-icon-comment-alt"></i> <a href="' . esc_url( get_post_format_link( 'chat' ) ) . '">' . __('Chat','wpscom') . '</a>' . $wpscom_framework->close_col( 'span' );
						}
					}

					// output date element
					if ( $meta == 'date' && ! empty( $value ) ) {
						if ( ! has_post_format( 'link' ) ) {
							$format_prefix = ( has_post_format( 'chat' ) || has_post_format( 'status' ) ) ? _x( '%1$s on %2$s', '1: post format name. 2: date', 'wpscom' ): '%2$s';

							if ( $date_format == 0 ) {
								$text = esc_html( sprintf( $format_prefix, get_post_format_string( get_post_format() ), get_the_date() ) );
								$icon = "el-icon-calendar icon";
							}
							elseif ( $date_format == 1 ) {
								$text = sprintf( human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago');
								$icon = "el-icon-time icon";
							}

							$content .= sprintf( $wpscom_framework->open_col( 'span', array( 'medium' => $col ), null, 'date' ) . '<i class="fa fa-calendar" aria-hidden="true"></i> <a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a>' . $wpscom_framework->close_col( 'span' ),
								esc_url( get_permalink() ),
								esc_attr( sprintf( __( 'Permalink to %s', 'wpscom' ), the_title_attribute( 'echo=0' ) ) ),
								esc_attr( get_the_date( 'c' ) ),
								$text
							);
						}
					}

					// output category element
					if ( $meta == 'category' && ! empty( $value ) ) {
						if ( $categories_list ) {
							$content .= $wpscom_framework->open_col( 'span', array( 'medium' => $col ), null, 'categories-links' ) . '<i class="el-icon-folder-open icon"></i> ' . $categories_list . $wpscom_framework->close_col( 'span' );
						}
					}

					// output tag element
					if ( $meta == 'tags' && ! empty( $value ) ) {
						if ( $tag_list ) {
							$content .= $wpscom_framework->open_col( 'span', array( 'medium' => $col ), null, 'tags-links' ) . '<i class="el-icon-tags icon"></i> ' . $tag_list . $wpscom_framework->close_col( 'span' );
						}
					}

					// output author element
					if ( $meta == 'author' && ! empty( $value ) ) {
						$content .= sprintf( $wpscom_framework->open_col( 'span', array( 'medium' => $col ), null, 'author vcard' ) . '<i class="fa fa-user-circle" aria-hidden="true"></i> <a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a>' . $wpscom_framework->close_col( 'span' ),
							esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
							esc_attr( sprintf( __( 'View all posts by %s', 'wpscom' ), get_the_author() ) ),
							get_the_author()
						);
					}

					// output comment count element
					if ( $meta == 'comment-count' && ! empty( $value ) ) {
						if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
							$content .= $wpscom_framework->open_col( 'span', array( 'medium' => $col ), null, 'comments-link' ) . '<i class="el-icon-comment icon"></i> <a href="' . get_comments_link( $post->ID ) . '">' . get_comments_number( $post->ID ) . ' ' . __( 'Comments', 'wpscom' ) . '</a>' . $wpscom_framework->close_col( 'span' );
						}
					}

					// Output author meta but do not display it if user has selected not to show it.
					if ( $meta == 'author' && empty( $value ) ) {
						$content .= sprintf( $wpscom_framework->open_col( 'span', array( 'medium' => $col ), null, 'author vcard' ) . '<a class="url fn n" href="%1$s" title="%2$s" rel="author" style="display:none;">%3$s</a>' . $wpscom_framework->close_col( 'span' ),
							esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
							esc_attr( sprintf( __( 'View all posts by %s', 'wpscom' ), get_the_author() ) ),
							get_the_author()
						);
					}
				}
			}

			if ( ! empty( $content ) ) {
				echo $wpscom_framework->open_row( 'div', null, 'row-meta' ) . $content . $wpscom_framework->close_row( 'div' );
			}
		}

		/**
		 * The "more" text
		 */
		function excerpt_more( $more ) {
			global $wpscom_settings;

			$continue_text = $wpscom_settings['post_excerpt_link_text'];
			return ' &hellip; <a href="' . get_permalink() . '">' . $continue_text . '</a>';
		}

		/**
		 * Excerpt length
		 */
		function excerpt_length($length) {
			global $wpscom_settings;

			$excerpt_length = $wpscom_settings['post_excerpt_length'];
			return $excerpt_length;
		}

	}
}
