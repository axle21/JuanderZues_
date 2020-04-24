<?php get_header(); ?>
<div id="content">
	<div class="container">
		<div class="archive_header">
			<h3 class="archive_header_title">
				<?php
					if ( is_category() ) {
						printf( __( '%s', 'someblog' ), '<span>' . single_cat_title( '', false ) . '</span>' );
					} elseif ( is_tag() ) {
						printf( __( '%s', 'someblog' ), '<span>' . single_tag_title( '', false ) . '</span>' );
					} elseif ( is_author() ) {
						/* Queue the first post, that way we know
						 * what author we're dealing with (if that is the case).
						*/
						the_post();
						printf( __( 'Author Archives: %s', 'someblog' ), '<span class="vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( "ID" ) ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>' );
						/* Since we called the_post() above, we need to
						 * rewind the loop back to the beginning that way
						 * we can run the loop properly, in full.
						 */
						rewind_posts();
					} elseif ( is_day() ) {
						printf( __( 'Daily Archives: %s', 'someblog' ), '<span>' . get_the_date() . '</span>' );
					} elseif ( is_month() ) {
						printf( __( 'Monthly Archives: %s', 'someblog' ), '<span>' . get_the_date( 'F Y' ) . '</span>' );
					} elseif ( is_year() ) {
						printf( __( 'Yearly Archives: %s', 'someblog' ), '<span>' . get_the_date( 'Y' ) . '</span>' );
					} else {
						printf( __( '%s', 'someblog' ), '<span>' . $term->name . '</span>' );
					}
				?>
			</h3>
			<?php
			/*
				if ( is_category() ) {
					// show an optional category description
					$category_description = category_description();
					if ( ! empty( $category_description ) )
						echo '<h3 class="archive_desc">' . $category_description . '</h3>';
						//echo apply_filters( 'category_archive_meta', '<div class="taxonomy-description">' . $category_description . '</div>' );
				} elseif ( is_tag() ) {
					// show an optional tag description
					$tag_description = tag_description();
					if ( ! empty( $tag_description ) )
						echo '<h3 class="archive_desc">' . $tag_description . '</h3>';
						//echo apply_filters( 'tag_archive_meta', '<div class="taxonomy-description">' . $tag_description . '</div>' );
				}
			*/
			?>
		</div> <!-- //archive_header -->	
		<div class="content_left content_left_full">
			<?php $x = 0; ?>
			<?php while ( have_posts() ) : the_post(); 
				$last_col = '';
				if($x == 1) {
					$last_col = 'home_post_box_last';
					$x = -1;
				}
			?>	
				<div class="home_post_box <?php echo $last_col; ?>">
					<div class="hpb_left">
						<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('hpb_image'); ?></a>
					</div>
					<div class="hpb_right">
						<div class="hpb_cats"><?php the_category(' / '); ?></div>
						<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						<p><?php echo dess_get_excerpt(135); ?></p>
						
					</div>
					<div class="clear"></div>
				</div>
				<?php if($x == -1) { echo '<div class="clear"></div>'; } ?>
				<?php $x++; ?>
				
			<?php endwhile; ?>
			<div class="page_nav_links">
				<div class="older_link"><?php next_posts_link('OLDER POSTS >>'); ?></div>
				<div class="newer_link"><?php previous_posts_link('<< NEWER POSTS'); ?></div>
				<div class="clear"></div>
			</div>			
		</div>
		<?php //get_sidebar(); ?>
		<div class="clear"></div>
	</div>
</div>
<?php get_footer(); ?>