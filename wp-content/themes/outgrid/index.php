<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
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
		
		new \Uicore\Posts;

	}else { ?>
	<main class="uicore-container">
		<?php
		if (!class_exists('\UiCore\Core')){
			get_sidebar('left');
		}
		?>
		<div class="uicore-row-offset">
			<div class="uicore-row">

			<?php
			if ( have_posts() ) { 

				/* Start the Loop */
				while ( have_posts() ) {
					the_post();

					get_template_part( 'template-parts/content', get_post_type() );

				}

				uicore_pagination();

			}else{

				get_template_part( 'template-parts/content', 'none' );

			}

			?>
			</div>
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
