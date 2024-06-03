<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package uicore-theme
 */

if ( ! is_active_sidebar( 'right-sidebar' ) ) {
	return;
}
?>

<aside class="right-widget-area uicore-col-lg-3">
	<?php dynamic_sidebar( 'right-sidebar' ); ?>
</aside>
