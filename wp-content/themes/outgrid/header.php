<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package uicore-theme
 */

global $post;
defined( 'ABSPATH' ) || exit;
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>
		<?php 
		
		wp_body_open();
		
		if (class_exists('\UiCore\Core')){ 
			do_action( "uicore_before_body_content");
			echo "<!-- 1.1 uicore_before_body_content -->"; 
		}
		?>
	<div class="uicore-body-content">
		<?php 
		if (class_exists('\UiCore\Core')){ 
			do_action( "uicore_before_page_content");
			echo "<!-- 1.2 uicore_before_page_content -->"; 
		}
		?>
		<div id="uicore-page">
		<?php 
		if (class_exists('\UiCore\Core')){ 
			do_action( "uicore_page", $post ); 
			echo "<!-- 1.3 uicore_page -->"; 
			} else {?>
			<header id="masthead" class="site-header">
				<div class="uicore-container">
					<div class="uicore-row">
						<div class="site-branding">
								<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
						</div>

						<nav id="site-navigation" class="main-navigation">
							<div class="menu-toggle"><span></span></div>
							<?php
							wp_nav_menu( array(
								'theme_location' => 'primary',
								'menu_id'        => 'primary-menu',
								'menu_class'     => 'nav-menu'
							) );
							?>
						</nav>
					</div>
				</div>
			</header>
			<div class="ui-page-title">
				<div class="uicore-container">
					<?php
					uicore_page_title();

					if ( 'post' === get_post_type() ) {
						echo '<div class="entry-meta-data">';
							if ( is_singular() ) {
							global $post; 
							$author_id=$post->post_author;
							uicore_posted_by($author_id);
							$categories_list = get_the_category_list( esc_html__( ', ', 'outgrid' ) );
							if ( $categories_list ) {
								echo ' • ';
								echo '<span class="cat-links">' . $categories_list . '</span>';
							}
							uicore_posted_on();
							}
						echo'</div><!-- .entry-meta -->';
					} 
					?>
				</div>
			</div>
			<?php } ?>
			<div id="content" class="uicore-content">

			<?php 
			if (class_exists('\UiCore\Core')){ 
				do_action( "uicore_before_content", $post ); 
				echo "<!-- 1.4 uicore_before_content -->";
			} 
