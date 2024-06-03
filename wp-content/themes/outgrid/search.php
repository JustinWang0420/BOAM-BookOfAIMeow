<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
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

				get_template_part( 'template-parts/content' );

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
