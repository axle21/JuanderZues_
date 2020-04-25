<?php get_header(); ?>
<div id="content">

	<div class="container">

	<div class="map_section">
		<?php echo do_shortcode( '[display-map id="42"]' ); ?>
	</div>

		<div class="content_left content_left_full">
			<?php
wp_reset_postdata();
wp_reset_query();
global $wp_query;
			global $slider_posts;
			$slider = array(
				'post_type' => 'post',
				
				'posts_per_page' => 12,
				'post__not_in' => $slider_posts,
				'paged' => ( get_query_var('paged') ? get_query_var('paged') : 1),
				);
			$x = 0;
			$the_query = new WP_Query( $slider );
			if ( $the_query->have_posts() ) : 	
				while ( $the_query->have_posts() ) : $the_query->the_post(); 
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
				<?php //posts_nav_link('&#8734;','NEWER POSTS','OLDER POSTS'); ?>
				<div class="page_nav_links">
					<?php //echo $the_query->max_num_pages; ?>
					<div class="older_link"><?php next_posts_link('OLDER POSTS >>', $the_query->max_num_pages); ?></div>
				<div class="newer_link"><?php previous_posts_link('<< NEWER POSTS', $the_query->max_num_pages); ?></div>
					<div class="clear"></div>
				</div>
			<?php
			endif;
			wp_reset_postdata();
			?>	
			
		</div>
		
		<?php //get_sidebar(); ?>
		<div class="clear"></div>

			
				
	</div>
	
</div>

<?php get_footer(); ?>