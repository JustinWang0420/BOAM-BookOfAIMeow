<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package uicore-theme
 */

?>

	</div><!-- #content -->

	<?php if (class_exists('\UiCore\Core')){
		do_action( "uicore_content_end", $post );
		echo "<!-- 1.5 uicore_content_end -->";
	} else {?>

	<footer id="colophon" class="site-footer">
		<div class="uicore-container">
			<div>
			<?php echo esc_html('© '.date('Y')); ?>
			
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>.
			<?php echo esc_html__(' All rights reserved.', 'outgrid'); ?>
			</div>
		</div>
	</footer>

	<?php } ?>

</div><!-- #page -->

<?php
if (class_exists('\UiCore\Core')){
		do_action( "uicore_body_end", $post );
		echo "<!-- 1.6 uicore_body_end -->";
}
?>
</div>
<?php
 if (class_exists('\UiCore\Core')){
	do_action( "uicore_after_body_content", $post );
	echo "<!-- 1.7 uicore_after_body_content -->";
}

wp_footer();
?>
</body>
</html>
