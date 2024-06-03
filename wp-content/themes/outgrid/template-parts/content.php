<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package uicore-theme
 */

?>
<article id="post-<?php the_ID(); ?>" <?php
if(is_single() || is_search()){
	post_class("uicore-col-md-12 uicore-col-lg-12");
}else{
	if (is_active_sidebar( 'left-sidebar' ) && is_active_sidebar( 'right-sidebar' )){
		post_class("uicore-col-md-6 uicore-col-lg-6");
	}else{
		post_class("uicore-col-md-6 uicore-col-lg-4");
	}
}
?>>
	<div class="uicore-grid-item">
		<header class="entry-header">
			<?php
			if (! is_singular() ) {
				/* translators: used between list items, there is a space after the comma */
				$categories_list = get_the_category_list( esc_html__( ', ', 'outgrid' ) );
				if ( $categories_list ) {
					echo '<span class="cat-links">' . $categories_list . '</span>';
				}
				the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
			}
			?>
		</header><!-- .entry-header -->


			<?php
			if( !is_singular()) {
				if( strlen( trim($ex_data = get_the_excerpt()) ) ){
					echo '<div class="entry-content">';
					echo esc_html($ex_data);
					echo '</div><!-- .entry-content -->';
				}
			}else{
				echo '<div class="entry-content">';
				the_content( sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
						__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'outgrid' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				) );

				wp_link_pages( array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'outgrid' ),
					'after'  => '</div>',
				) );
				echo '</div><!-- .entry-content -->';
			}
			?>
		

		<footer class="entry-footer">
			<?php uicore_entry_footer(); ?>
		</footer><!-- .entry-footer -->
	</div>
</article><!-- #post-<?php the_ID(); ?> -->
