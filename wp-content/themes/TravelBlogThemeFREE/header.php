<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://fonts.googleapis.com/css?family=Muli" rel="stylesheet">

	<?php wp_head(); ?>
	
</head>
<body <?php body_class(); ?>>
<header id="head">
	<div class="container">
		<div class="logo_cont">
			<?php echo (dess_setting('dess_logo') != '' ? '<a href="'.home_url().'"><img src="'.dess_setting('dess_logo').'" alt="logo" /></a>': '<a href="'.home_url().'"><img src="'.esc_url( get_stylesheet_directory_uri() ).'/img/logo.jpg" alt="logo" /></a>'); ?>	
			
		</div>
	</div>
	<div class="header_menu">
		<div class="container">
			<div class="header_menu_left">
				<?php wp_nav_menu(array('theme_location' => 'header-menu', 'menu_id' => 'header_menu_id')); ?>
				<div class="clear"></div>
			</div>
			<div class="header_social_right">
				<ul>
					<?php
						$socials = array('twitter','facebook','google-plus','instagram','pinterest','vimeo','youtube','linkedin');
						for($i=0;$i<count($socials);$i++){
							$url = '';
							$s = $socials[$i];
							$url = dess_setting('dess_'.$s);
							echo ($url != '' ? '<li><a target="_blank" href="'.$url.'"><img src="'.esc_url( get_stylesheet_directory_uri() ).'/images/'.$s.'-icon.png" alt="'.$s.'" /></a></li>':'');
						}
					?>
				</ul>					
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
</header>
<?php //if ( (is_front_page() && is_home()) || (is_home() && is_paged()) ) : ?>
<?php if ( is_front_page() && is_home() ) : ?>
	<div class="slider_cont">
		<!-- Slider main container -->
		<div class="swiper-container">
			<!-- Additional required wrapper -->
			<div class="swiper-wrapper">
				<!-- Slides -->
				<?php
				global $slider_posts;
				$slider_posts = array();
				$slider = array(
					'post_type' => 'post',
					'meta_key' => 'show_in_slider',
					'meta_value' => 'yes',
					'posts_per_page' => 6
					);
				$the_query = new WP_Query( $slider );
				if ( $the_query->have_posts() ) : 	
					while ( $the_query->have_posts() ) : $the_query->the_post(); 
					$img_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
				?>				
				<div class="swiper-slide" style="background-image: url('<?php echo $img_url; ?>');">
					<div class="slider_overlay"></div>
					<div class="swiper_slide_text">
						<h3><?php the_title(); ?></h3>
						<p><?php echo dess_get_excerpt(120); ?></p>
						<p class="slide_read_more"><a href="<?php the_permalink(); ?>">READ MORE</a></p>
					</div>
				</div>
				<?php
					array_push($slider_posts, get_the_ID());
					endwhile;
				endif;
				wp_reset_postdata();
wp_reset_query();
				?>
			</div>
			<!-- If we need pagination -->
			<div class="swiper-pagination"></div>
			<!-- If we need navigation buttons -->
			<div class="swiper-button-prev swiper-button-white"></div>
			<div class="swiper-button-next swiper-button-white"></div>
			<!-- If we need scrollbar -->
			<div class="swiper-scrollbar"></div>
		</div>
	</div>
	<div class="posts_under_slider">
		<?php
		$under_slider = array(
			'post_type' => 'post',
			'meta_key' => 'show_under_slider',
			'meta_value' => 'yes',
			'post__not_in' => $slider_posts,
			'posts_per_page' => 1
			);
		$counter = 0;
		$the_query = new WP_Query( $under_slider );
		if ( $the_query->have_posts() ) : 	
			while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
			<div class="under_slide_box">
				<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('under-slide-image'); ?></a>
				<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
				<p><?php echo dess_get_excerpt(60); ?></p>
			</div>
		<?php
			if($counter == 2) { echo '<div class="tablet_clear"></div>'; }
			$counter++;		
			array_push($slider_posts, get_the_ID());
			endwhile;
		endif;
		wp_reset_postdata();
wp_reset_query();
		?>
		<div class="clear"></div>
	</div>
<?php endif; ?>