<?php get_header(); ?>
<div id="content">
	<div class="container">
		<div class="content_left">
			<?php while ( have_posts() ) : the_post(); ?>
			<article class="post_box" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<h1><?php the_title(); ?></h1>
				<?php the_content(); ?>
					<?php
					wp_link_pages(array(
						'before' => '<div class="link_pages">'.__('Pages', 'concept'),
						'after' => '</div>',
						'link_before' => '<span>',
						'link_after' => '</span>'
					)); 
				?>
				<?php //the_tags( '<div class="post_tags">Tags: ', ', ', '</div>' ); ?> 
			</article>
			<div class="clear"></div>
			<?php
			endwhile;
			?>		
		</div>
		<?php get_sidebar(); ?>
		<div class="clear"></div>
	</div>
</div>
<?php get_footer(); ?>