<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package uicore-theme
 */

if ( ! is_active_sidebar( 'left-sidebar' ) ) {
	return;
}
?>

<aside class="left-widget-area uicore-col-lg-3">
	<?php dynamic_sidebar( 'left-sidebar' ); ?>
</aside>
