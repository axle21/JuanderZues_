<?php
/**
 * The template for displaying header layout 1.
 *
 * @package Sinatra
 * @author  Sinatra Team <hello@sinatrawp.com>
 * @since   1.0.0
 */

?>

<div class="si-container si-header-container">

	<?php
	get_template_part( 'template-parts/header/logo' );
	get_template_part( 'template-parts/header/navigation' );

	do_action( 'sinatra_header_widget_location', array( 'left', 'right' ) );
	?>

	<span class="si-header-element si-mobile-nav">
		<?php sinatra_hamburger( sinatra_option( 'main_nav_mobile_label' ), 'sinatra-primary-nav' ); ?>
	</span>

</div><!-- END .si-container -->