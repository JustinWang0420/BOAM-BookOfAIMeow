<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package uicore-theme
 */

get_header();
?>
<div id="primary" class="content-area">

	<?php
	if (class_exists('\UiCore\Core')){
		
		new \UiCore\Pages;

	}else { ?>
	<main class="uicore-container">
		<?php
		if (!class_exists('\UiCore\Core')){
			get_sidebar('left');
		}
		?>
		<div class="uicore-row">

		<?php
		if ( have_posts() ) { 

			/* Start the Loop */
			while ( have_posts() ) {
				the_post();

				get_template_part( 'template-parts/content', get_post_type() );

			}

			wp_link_pages();


			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		}else{

			get_template_part( 'template-parts/content', 'none' );

		}

		?>
		</div>
		<?php
		if (!class_exists('\UiCore\Core')){
			get_sidebar('right');
		}
		?>
	</main>	
	<?php	
	}
	?>

</div><!-- #primary -->

<?php

get_footer();
