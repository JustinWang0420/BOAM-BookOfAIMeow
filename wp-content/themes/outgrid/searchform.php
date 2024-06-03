<?php
/**
 * The template for displaying search forms
 *
 * @package uicore-theme
 */
?>
    <form method="get" id="searchform" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
        <label>
            <span class="screen-reader-text"><?php esc_html_e( 'Search for:', 'outgrid' ); ?></span>
            <input type="search" class="search-field" placeholder="<?php esc_attr_e( 'Search &hellip;', 'outgrid' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" x-webkit-speech>
        </label>
        <input type="submit" class="search-submit" value="<?php esc_attr_e( 'Search', 'outgrid' ); ?>" />
    </form>
