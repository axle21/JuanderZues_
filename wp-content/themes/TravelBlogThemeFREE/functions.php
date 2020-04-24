<?php
if (function_exists('add_theme_support')) {
	add_theme_support( 'menus' );
	register_nav_menu( 'header-menu','Header Menu');
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	
	add_image_size( 'recent-thumb-max', 250, 250, true );
	add_image_size('hpb_image', 386, 324, true);
}
function multimedia_add_editor_styles() {
    add_editor_style( 'css/editor-style.css' );
}
add_action( 'admin_init', 'multimedia_add_editor_styles' );
function dess_get_excerpt($num_chars) {
    $temp_str = substr(strip_shortcodes(strip_tags(get_the_content())),0,$num_chars);
    $temp_parts = explode(" ",$temp_str);
    $temp_parts[(count($temp_parts) - 1)] = '';
    if(strlen(strip_tags(get_the_content())) > 125) {
      return implode(" ",$temp_parts) . '...';
    } else {
      return implode(" ",$temp_parts);
    }
}
add_action('wp_enqueue_scripts', 'dess_theme_imports'); 
function dess_theme_imports(){
	wp_enqueue_style( 'google-font', 'https://fonts.googleapis.com/css?family=Lato:400,400i,700,700i|Libre+Baskerville:400,400i,700|Montserrat:400,700|Muli:300,300i,400,400i|Open+Sans+Condensed:300,300i,700|Open+Sans:400,400i,700,700i|Oswald:300,400,700|Oxygen:300,400,700|Raleway:400,400i,700,700i|Roboto+Condensed:400,400i,700,700i|Roboto+Slab|Roboto:400,400i,700,700i|Source+Sans+Pro:400,400i,700,700i|Ubuntu:400,400i,700,700i' );
	
	wp_enqueue_style( 'swiper-style', get_template_directory_uri().'/css/swiper.css' );
	wp_enqueue_style( 'slicknav-style', get_template_directory_uri().'/css/slicknav.css' );
	wp_enqueue_style( 'dess-style', get_stylesheet_uri() );
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'swiper-js', get_template_directory_uri() . '/js/swiper.jquery.js' );
	wp_enqueue_script( 'slicknav-js', get_template_directory_uri() . '/js/jquery.slicknav.js' );
	wp_enqueue_script( 'dess-script', get_template_directory_uri() . '/js/scripts.js' );
	
}
add_action('admin_enqueue_scripts', 'dess_admin_imports');
function dess_admin_imports(){
	wp_enqueue_style( 'concept-style', get_template_directory_uri().'/css/admin-style.css' );
}
function dess_widgets_init() {
	
	register_sidebar( array(
		'name' => __('Sidebar','concept'),
		'id' => 'sidebar-1',
		'before_widget' => '<div id="%1$s" class="widget_box %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget_title">',
		'after_title' => '</h3>',
	) );
	register_sidebar( array(
		'name' => __('Footer (Instagram)','concept'),
		'id' => 'footer-instagram',
		'before_widget' => '<div id="%1$s" class="footer_instagram footer_insta_box %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="footer_col_title">',
		'after_title' => '</h3>',
	) );
	
	
}
add_action( 'widgets_init', 'dess_widgets_init' );
function dess_post_meta_box() {
	add_meta_box(
			'dess_post_settings',
			__('Post Settings','concept'),
			'dess_post_meta_box_callback',
			'post'
		);
}
add_action( 'add_meta_boxes', 'dess_post_meta_box' );
function dess_post_meta_box_callback( $post ) {
	wp_nonce_field( 'dess_post_save_meta_box_data', 'dess_post_meta_box_nonce' );
	$show_in_slider = get_post_meta( $post->ID, 'show_in_slider', true );
	$show_under_slider = get_post_meta( $post->ID, 'show_under_slider', true );
	$type = get_post_meta( $post->ID, 'page_featured_type', true );
	
	echo '<p><label for="show_in_slider">'.__('Show in Slider','concept').': </label>';
	echo '<input type="checkbox" id="show_in_slider" name="show_in_slider" value="Yes" '.($show_in_slider ==  'Yes' ? 'checked' : '' ).' /></p>';
	
	/*
	echo '<p><label for="video_type">'.__('Featured Type','concept').': </label><br/>';
	echo '<select id="video_type" name="dess_post[page_featured_type]"><option value="">Image</option><option value="youtube" '.($type == 'youtube' ? 'selected="selected"' : '').'>Youtube</option><option value="vimeo" '.($type == 'vimeo' ? 'selected="selected"' : '').'>Vimeo</option></select></p>';
	echo '<p><label for="video_id">'.__('Video ID','concept').': </label><br/>';
	echo '<input type="text" id="video_id" name="dess_post[page_video_id]" value="'.get_post_meta( $post->ID, 'page_video_id', true ).'" /></p>';
	*/
}
function dess_post_save_meta_box_data( $post_id ) {
	if ( ! isset( $_POST['dess_post_meta_box_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( $_POST['dess_post_meta_box_nonce'], 'dess_post_save_meta_box_data' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}
	} else {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}
	$show_in_slider = sanitize_text_field( $_POST['show_in_slider'] );
	$show_under_slider = sanitize_text_field( $_POST['show_under_slider'] );
	update_post_meta( $post_id, 'show_in_slider', $show_in_slider );
	update_post_meta( $post_id, 'show_under_slider', $show_under_slider );
	
	$arr = array();
	if (isset($_POST['dess_post'])){
		$arr = $_POST['dess_post'];
	}
	foreach ($arr as $key => $value) {
		$val = sanitize_text_field($value);
		update_post_meta( $post_id, $key, $val );
	}
}
add_action( 'save_post', 'dess_post_save_meta_box_data' );
add_action( 'customize_register', 'dess_load_customize_controls', 0 );
function dess_load_customize_controls() {
    /**
 * Multiple checkbox customize control class.
 *
 * @since  1.0.0
 * @access public
 */
class DESS_Customize_Control_Checkbox_Multiple extends WP_Customize_Control {
    /**
     * The type of customize control being rendered.
     *
     * @since  1.0.0
     * @access public
     * @var    string
     */
    public $type = 'checkbox-multiple';
    /**
     * Enqueue scripts/styles.
     *
     * @since  1.0.0
     * @access public
     * @return void
     */
    public function enqueue() {
        wp_enqueue_script( 'jt-customize-controls', trailingslashit( get_template_directory_uri() ) . 'js/customize-controls.js', array( 'jquery' ) );
    }
    /**
     * Displays the control content.
     *
     * @since  1.0.0
     * @access public
     * @return void
     */
    public function render_content() {
        if ( empty( $this->choices ) )
            return; ?>
        <?php if ( !empty( $this->label ) ) : ?>
            <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
        <?php endif; ?>
        <?php if ( !empty( $this->description ) ) : ?>
            <span class="description customize-control-description"><?php echo $this->description; ?></span>
        <?php endif; ?>
        <?php $multi_values = !is_array( $this->value() ) ? explode( ',', $this->value() ) : $this->value(); ?>
        <ul>`
            <?php foreach ( $this->choices as $value => $label ) : ?>
                <li>
                    <label>
                        <input type="checkbox" value="<?php echo esc_attr( $value ); ?>" <?php checked( in_array( $value, $multi_values ) ); ?> /> 
                        <?php echo esc_html( $label ); ?>
                    </label>
                </li>
            <?php endforeach; ?>
        </ul>
        <input type="hidden" <?php $this->link(); ?> value="<?php echo esc_attr( implode( ',', $multi_values ) ); ?>" />
    <?php }
	}
}
function dess_customize_register($wp_customize){	
	$wp_customize->add_section(
	'sm_section', 
	array( 
		'title' =>  __('Social Media','concept'), 
		'capability' => 'edit_theme_options', 
		'description' =>  __('Allows you to set your social media URLs','concept')
	)
	);
	$socials = array('twitter','facebook','google-plus','instagram','pinterest','linkedin','vimeo','youtube');
	for($i=0;$i<count($socials);$i++) {
		$name = str_replace('-',' ',ucfirst($socials[$i]));
		$wp_customize->add_setting('dess_'.$socials[$i], array(
	    'capability' => 'edit_theme_options',
	    'type'       => 'theme_mod',
	    'sanitize_callback' => 'dess_sanitize_url',
		));
		$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'dess_'.$socials[$i], array(
		    'settings' => 'dess_'.$socials[$i],
		    'label'    => $name.' URL',
		    'section'  => 'sm_section',
		    'type'     => 'text',
		)));
	}
	$wp_customize->add_section( 'background_color', array(
		'title' => __( 'Background Color', 'concept' ),
		'capability' => 'edit_theme_options', 
		'description' =>  __('Allows you to update your theme\'s bacgkround color.','concept')
	) );
	$wp_customize->add_setting( 'bg_color', array(
		'default' => '#fff'
	));
	$wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'bcolor', array(
		'label' => 'Select Color',
		'section' => 'background_color',
		'settings' => 'bg_color') 
	));	
 
    $wp_customize->add_section(
	'header_section',
	array( 
		'title' => __('Logo','concept'), 
		'capability' => 'edit_theme_options', 
		'description' =>  __('Allows you to update your theme\'s logo.','concept')
	)
	);
	$wp_customize->add_setting('dess_logo', array(
	    'default'           => get_template_directory_uri().'/images/logo.png',
	    'type'           => 'theme_mod',
	    'sanitize_callback' => 'dess_sanitize_url',
	));
	$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'logo', array(
	    'label'    => __('Logo Image','concept'),
	    'section'  => 'header_section',
	    'settings' => 'dess_logo',
	)));
	

}
add_action('customize_register', 'dess_customize_register');
function dess_setting($name, $default = false) {
	return get_theme_mod( $name, $default );
}
function dess_sanitize_html($value){
	return wp_filter_post_kses($value);
}
function dess_sanitize_text($value){
	return sanitize_text_field($value);
}
function dess_sanitize_url($value) {
	return esc_url_raw($value);
}
/**
 * Extend Recent Posts Widget 
 *
 * Adds different formatting to the default WordPress Recent Posts Widget
 */
Class My_Recent_Posts_Widget extends WP_Widget_Recent_Posts {
        function widget($args, $instance) {
                if ( ! isset( $args['widget_id'] ) ) {
                $args['widget_id'] = $this->id;
            }
            $title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Recent Posts' );
            /** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
            $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
            $number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
            if ( ! $number )
                $number = 5;
            $show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;
            /**
             * Filter the arguments for the Recent Posts widget.
             *
             * @since 3.4.0
             *
             * @see WP_Query::get_posts()
             *
             * @param array $args An array of arguments used to retrieve the recent posts.
             */
            $r = new WP_Query( apply_filters( 'widget_posts_args', array(
                'posts_per_page'      => $number,
                'no_found_rows'       => true,
                'post_status'         => 'publish',
                'ignore_sticky_posts' => true
            ) ) );
            $y = 0;
            if ($r->have_posts()) :
            ?>
            <?php echo $args['before_widget']; ?>
            <?php if ( $title ) {
                echo $args['before_title'] . $title . $args['after_title'];
            } ?>
            <ul>
            <?php while ( $r->have_posts() ) : $r->the_post(); ?>
                <li>
                    <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('recent-thumb-max'); ?></a>
                    <a href="<?php the_permalink(); ?>"><?php get_the_title() ? the_title() : the_ID(); ?></a>
                <?php if ( $show_date ) : ?>
                    <span class="post-date"><?php echo get_the_date(); ?></span>
                <?php endif; ?>
                </li>
                <?php
                if($y == 1 || $y == 3 || $y == 5 || $y == 7 || $y == 9) {
 //               	echo '<li class="clear"></li>';
                }
                ?>
            <?php $y++; endwhile; ?>
            </ul>
            <div class="clear"></div>
            <?php echo $args['after_widget']; ?>
            <?php
            // Reset the global $the_post as this query will have stomped on it
            wp_reset_postdata();
            endif;
        }
}
function my_recent_widget_registration() {
  unregister_widget('WP_Widget_Recent_Posts');
  register_widget('My_Recent_Posts_Widget');
}
add_action('widgets_init', 'my_recent_widget_registration');
function customizer_styles() {
	$bg_color = get_theme_mod( 'bg_color' ); 
	
	if ( $bg_color != '#ff0000' ) :
	?>
		<style type="text/css">
			body {
				background-color: <?php echo $bg_color; ?>;
			}
		</style>
	<?php
	endif;			
}
add_action( 'wp_head', 'customizer_styles' );
?>