<?php

/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package uicore-theme
 */
defined('ABSPATH') || exit;

global $pagenow;
// redirect to welcome page after theme activation
if ( ! empty( $_GET['activated'] ) && 'true' == $_GET['activated'] && $pagenow == "themes.php" && defined('UICORE_VERSION') ){
    wp_redirect( self_admin_url('admin.php?page=uicore#/') );
}


function uicore_ajax_plugins(){
    if ( ! check_ajax_referer( 'uicore_setup_nonce', 'wpnonce' ) || empty( $_POST['slug'] ) ) {
        wp_send_json_error( array( 'error' => 1, 'message' => esc_html__( 'No Slug Found', 'outgrid' ) ) );
    }
    $json = array();
    // send back some json we use to hit up TGM
    $plugins = uicore_get_plugins();

    foreach ( $plugins as $plugin ) {
        if ( $_POST['slug'] == $plugin['slug'] ) {
            $json = array(
                'url'           => admin_url( 'themes.php?page=tgmpa-install-plugins' ),
                'plugin'        => array( $plugin['slug'] ),
                'tgmpa-page'    => 'tgmpa-install-plugins',
                'plugin_status' => 'all',
                '_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
                'action'        => 'tgmpa-bulk-install',
                'action2'       => - 1,
                'message'       => esc_html__( 'Installing...', 'outgrid' ),
            );
            break;
        }
    }

    if ( $json ) {
        wp_send_json( $json );
    }
    exit;

}
add_action( 'wp_ajax_uicore_plugins', 'uicore_ajax_plugins' );


function uicore_get_plugins(){
    return array(

        // This is an example of how to include a plugin bundled with a theme.
        array(
            'name'               => 'UiCore Framework', // The plugin name.
            'slug'               => 'uicore-framework', // The plugin slug (typically the folder name).
            'source'             => 'uicore-framework.zip', // The plugin source.
            'required'           => true, // If false, the plugin is only 'recommended' instead of required.
            'version'            => UICORE_FRAMEWORK_VERSION, // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
            'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
            'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
            'external_url'       => '', // If set, overrides default API URL and points to an external URL.
            'is_callable'        => '', // If set, this callable will be be checked for availability to determine if a plugin is active.
        ),
		        array(
            'name'               => 'UiCore Elements', // The plugin name.
            'slug'               => 'uicore-elements', // The plugin slug (typically the folder name).
            'required'           => true, // If false, the plugin is only 'recommended' instead of required.// E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
        ),

        // This is an example of how to include a plugin bundled with a theme.
        array(
            'name'               => 'Elementor', // The plugin name.
            'slug'               => 'elementor', // The plugin slug (typically the folder name).
            'required'           => true, // If false, the plugin is only 'recommended' instead of required.
        ),

        array(
            'name'               => 'UiCore Animate', // The plugin name.
            'slug'               => 'uicore-animate', // The plugin slug (typically the folder name).
            'required'           => true, // If false, the plugin is only 'recommended' instead of required.
        ),

        // This is an example of how to include a plugin bundled with a theme.
        array(
            'name'               => 'Element Pack', // The plugin name.
            'slug'               => 'bdthemes-element-pack', // The plugin slug (typically the folder name).
            'required'           => true, // If false, the plugin is only 'recommended' instead of required.
			'version'            => '5.7.4',
            'source'             => 'bdthemes-element-pack.zip' // The plugin source.
        ),

    );
}
require_once get_template_directory() . '/inc/class-tgm-plugin-activation.php';
if (!function_exists('uicore_plugins_activation')) :

    function uicore_plugins_activation()
    {

        $plugins = uicore_get_plugins();

        $config = array(
            'id'           => 'outgrid',            // Unique ID for hashing notices for multiple instances of TGMPA.
            'default_path' => get_template_directory() . "/inc/plugins/",                      // Default absolute path to bundled plugins.
            'menu'         => 'tgmpa-install-plugins', // Menu slug.
            'has_notices'  => true,                   // Show admin notices or not.
            'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
            'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
            'is_automatic' => true,                   // Automatically activate plugins after installation or not.
            'message'      => '',                      // Message to output right before the plugins table.

            'strings'      => array(
                'page_title'                      => esc_attr__( 'Install Recommended Plugins', 'outgrid' ),
                'menu_title'                      => esc_attr__( 'Install Plugins', 'outgrid' )
            )
        );

        tgmpa($plugins, $config);
    }
endif;
add_action('tgmpa_register', 'uicore_plugins_activation');

/**
 * Get plugin activate link
 *
 * @return string               Activate plugin link
 */
function uicore_get_plugin_activation_link( $plugin_base_name, $slug, $plugin_filename ) {
    $activate_nonce = wp_create_nonce( 'activate-plugin_' . $slug .'/'. $plugin_filename );
    return self_admin_url( 'plugins.php?_wpnonce=' . $activate_nonce . '&action=activate&plugin='. str_replace( '/', '%2F', $plugin_base_name ) );
}

/**
 * Get install link
 *
 * @param  string  $plugin_slug The plugin slug
 *
 * @return string               Install plugin link
 */
function uicore_get_plugin_install_link( $plugin_slug ){

    // sanitize the plugin slug
    $plugin_slug = esc_attr( $plugin_slug );

    $install_link  = wp_nonce_url(
        add_query_arg(
            array(
                'action' => 'install-plugin',
                'plugin' => $plugin_slug,
            ),
            network_admin_url( 'update.php' )
        ),
        'install-plugin_' . $plugin_slug
    );

    return $install_link;
}


/**
 * Creates and returns attributes for a dom
 *
 * @param  array        $attrs   List of attributes and their values
 *
 * @return string                HTML attribute string
 */
function uicore_make_html_attributes( $attrs = array() ){

    if( ! is_array( $attrs ) ){
        return '';
    }

    $attributes_string = '';

    foreach ( $attrs as $attr => $value ) {
        $value = is_array( $value ) ? join( ' ', array_unique( $value ) ) : $value;
        $attributes_string .= sprintf( '%s="%s" ', $attr, esc_attr( trim( $value ) ) );
    }

    return $attributes_string;
}

/**
 * Display a notice for installing theme core plugin
 *
 * @return void
 */
function uicore_plugin_notice(){
    if ( get_transient( 'uicore-fw-notice_' . UICORE_THEME_NAME ) ) {
        return;
    }
    if( class_exists( '\Elementor\Plugin' ) && class_exists( '\UiCore\Core' ) && class_exists( '\ElementPack\Admin' ) ){
        return;
    }

       $plugins_base_name = array(
        'uicore-elements/plugin.php',
        'uicore-animate/plugin.php',
        'uicore-framework/plugin.php',
        'elementor/elementor.php',
        'bdthemes-element-pack/bdthemes-element-pack.php'
    );
    $plugins_slug      = array(
        'uicore-elements',
        'uicore-animate',
        'uicore-framework',
        'elementor',
        'bdthemes-element-pack'
    );
    $plugins_filename  = array(
        'plugin.php',
        'plugin.php',
        'plugin.php',
        'elementor.php',
        'bdthemes-element-pack.php'
    );
    $plugins_title     = array(
        __('UiCore Elements', 'outgrid'),
        __('UiCore Animate', 'outgrid'),
        __('UiCore Framework', 'outgrid'),
        __('Elementor','outgrid'),
        __('Element Pack','outgrid')
    );
    // Classess to check if plugins are active or not
    $class_check = array(
        '\UiCoreElements\Base',
        '\UiCoreAnimate\Base',
        '\UiCore\Core',
        '\Elementor\Plugin',
        '\ElementPack\Admin'
    );


    $installed_plugins  = get_plugins();

    // find required plugins which is not installed or active
    $not_installed_or_activated_plugins_id = array();
    foreach ( $plugins_base_name as $key => $plugin_base_name ) {
        if( ! isset( $installed_plugins[ $plugin_base_name ] ) || ! class_exists( $class_check[$key] ) ){
            $not_installed_or_activated_plugins_id[] = $key;
        }
    }

    // get information of required plugins which is not installed or not activated
    foreach ( $not_installed_or_activated_plugins_id as $key => $value ) {

        $not_installed_plugins_number = count( $not_installed_or_activated_plugins_id );
        $progress_text = $not_installed_plugins_number > 1 ? ( $key + 1 ). " / {$not_installed_plugins_number}" : "";
        $progress_text_and_title = $progress_text . ' - ' .$plugins_title[ $value ];

        $links_attrs[$key] = array(
            'data-plugin-slug'      => $plugins_slug[$value],

            'data-activating-label' => sprintf( esc_attr__( 'Activating %s', 'outgrid' ), $progress_text_and_title ),
            'data-installing-label' => sprintf( esc_attr__( 'Installing %s', 'outgrid' ), $progress_text_and_title ),
            'data-activate-label'   => sprintf( esc_attr__( 'Activate %s'  , 'outgrid' ), $progress_text_and_title ),
            'data-install-label'    => sprintf( esc_attr__( 'Install %s'   , 'outgrid' ), $progress_text_and_title ),

            'data-activate-url'     => uicore_get_plugin_activation_link( $plugins_base_name[$value], $plugins_slug[$value], $plugins_filename[$value] ),
            'data-install-url'      => uicore_get_plugin_install_link( $plugins_slug[$value] ),

            'data-redirect-url'     => self_admin_url('admin.php?page=uicore#/'),
            'data-num-of-required-plugins' => $not_installed_plugins_number,
            'data-plugin-order'     => $key + 1,
            'data-wpnonce'          => wp_create_nonce( 'uicore_setup_nonce' )
        );

        if( ! isset( $installed_plugins[ $plugins_base_name[$value] ] ) ){
            $links_attrs[$key]['data-action'] = 'install';
            $links_attrs[$key]['href'] = $links_attrs[ $key ]['data-install-url'];
            $links_attrs[$key]['button_label'] = sprintf( esc_html__( 'Install %s', 'outgrid' ), $progress_text_and_title );
        } elseif( ! class_exists( $class_check[ $value ] ) ) {
            $links_attrs[$key]['data-action'] = 'activate';
            $links_attrs[$key]['href'] = $links_attrs[ $key ]['data-activate-url'];
            $links_attrs[$key]['button_label'] = sprintf( esc_html__( 'Activate %s', 'outgrid' ), $progress_text_and_title );
        }
    }
?>
<script>
/**
 * Ajax install the Theme Core Plugin
 *
 */
(function($, window, document, undefined){
    "use strict";

    $(function(){

        $('.uicore-btn-default').on( 'click', function( event ) {
            var $button = $( event.target );
            event.preventDefault();

            if ( $button.hasClass( 'updating-message' ) || $button.hasClass( 'button-disabled' ) ) {
                return;
            }

            /**
             * Install a plugin
             *
             * @return void
             */
            function installPlugin($data){
                if ( $data['data-plugin-slug'] == 'bdthemes-element-pack' ) {
                    var _ajaxData = {
                        action: "uicore_plugins",
                        wpnonce: $data['data-wpnonce'],
                        slug: 'bdthemes-element-pack',
                        plugins: ['bdthemes-element-pack']
                    };
                    globalAjax(
                        '<?php echo admin_url( 'admin-ajax.php' ); ?>',
                        'POST',
                        _ajaxData,
                        function () { // beforeSend callback
                            buttonStatusInProgress( $data['data-installing-label']  );
                        },
                        function ( response ){ // success callback
                            console.log(response);
                            if ( response.url ) {
                                globalAjax(
                                    response.url,
                                    'POST',
                                    response,
                                    function(){}, // beforeSendCallback
                                    function(){ // success callback
                                        buttonStatusInstalled( 'All plugins installed and activated successfully!' );
                                        activatePlugin($data);
                                    },
                                    function(){} // error callback
                                );
                            }
                        },
                        function () { // error callback
                            buttonStatusDisabled( 'Error!' );
                            return false;
                        }
                    );
                } else if ( $data['data-plugin-slug'] == 'uicore-elements' ) {
                    var _ajaxData = {
                        action: "uicore_plugins",
                        wpnonce: $data['data-wpnonce'],
                        slug: 'uicore-elements',
                        plugins: ['uicore-elements']
                    };
                    globalAjax(
                        '<?php echo admin_url( 'admin-ajax.php' ); ?>',
                        'POST',
                        _ajaxData,
                        function () { // beforeSend callback
                            buttonStatusInProgress( $data['data-installing-label']  );
                        },
                        function ( response ){ // success callback
                            console.log(response);
                            if ( response.url ) {
                                globalAjax(
                                    response.url,
                                    'POST',
                                    response,
                                    function(){}, // beforeSendCallback
                                    function(){ // success callback
                                        buttonStatusInstalled( 'All plugins installed and activated successfully!' );
                                        activatePlugin($data);
                                    },
                                    function(){} // error callback
                                );
                            }
                        },
                        function () { // error callback
                            buttonStatusDisabled( 'Error!' );
                            return false;
                        }
                    );
                } else if ( $data['data-plugin-slug'] == 'uicore-animate' ) {
                    var _ajaxData = {
                        action: "uicore_plugins",
                        wpnonce: $data['data-wpnonce'],
                        slug: 'uicore-animate',
                        plugins: ['uicore-animate']
                    };
                    globalAjax(
                        '<?php echo admin_url( 'admin-ajax.php' ); ?>',
                        'POST',
                        _ajaxData,
                        function () { // beforeSend callback
                            buttonStatusInProgress( $data['data-installing-label']  );
                        },
                        function ( response ){ // success callback
                            console.log(response);
                            if ( response.url ) {
                                globalAjax(
                                    response.url,
                                    'POST',
                                    response,
                                    function(){}, // beforeSendCallback
                                    function(){ // success callback
                                        buttonStatusInstalled( 'All plugins installed and activated successfully!' );
                                        activatePlugin($data);
                                    },
                                    function(){} // error callback
                                );
                            }
                        },
                        function () { // error callback
                            buttonStatusDisabled( 'Error!' );
                            return false;
                        }
                    );
                } else if ( $data['data-plugin-slug'] == 'uicore-framework' ) {
                    var _ajaxData = {
                        action: "uicore_plugins",
                        wpnonce: $data['data-wpnonce'],
                        slug: 'uicore-framework',
                        plugins: ['uicore-framework']
                    };
                    globalAjax(
                        '<?php echo admin_url( 'admin-ajax.php' ); ?>',
                        'POST',
                        _ajaxData,
                        function () { // beforeSend callback
                            buttonStatusInProgress( $data['data-installing-label']  );
                        },
                        function ( response ){ // success callback
                            console.log(response);
                            if ( response.url ) {
                                globalAjax(
                                    response.url,
                                    'POST',
                                    response,
                                    function(){}, // beforeSendCallback
                                    function(){ // success callback
                                        buttonStatusInstalled( 'All plugins installed and activated successfully!' );
                                        activatePlugin($data);
                                    },
                                    function(){} // error callback
                                );
                            }
                        },
                        function () { // error callback
                            buttonStatusDisabled( 'Error!' );
                            return false;
                        }
                    );
                } else {
                    globalAjax(
                        $data['data-install-url'],
                        'GET',
                        {},
                        function(){ // beforeSend callback
                           buttonStatusInProgress( $data['data-installing-label']  );
                        },
                        function(){ // success callback
                            buttonStatusInstalled( 'All plugins installed and activated sucessfully!' );
                            activatePlugin($data);
                        },
                        function() { // error callback
                            buttonStatusDisabled( 'Error!' );
                            return false;
                        }
                    );
                }
            }

            /**
             * global AJAX callback
             */
            function globalAjax( _url, _type, _data, _beforeSendCallback, _successCallback, _errorCallback ) {
                $.ajax({
                    url: _url,
                    type: _type,
                    data: _data,
                    beforeSend: _beforeSendCallback,
                    success: _successCallback,
                    error: _errorCallback
                });
            }

            /**
             * Activate a plugin
             *
             * @return void
             */
            function activatePlugin( $data ){

                globalAjax(
                    $data['data-activate-url'],
                    'GET',
                    {},
                    function () { // beforeSend callback
                        buttonStatusInProgress( $data['data-activating-label'] );
                    },
                    function () { // success callback
                        buttonStatusDisabled(  'All plugins intalled and activated sucessfully!' );
                        run( $data['data-plugin-order'] );
                    },
                    function (xhr) {
                        buttonStatusDisabled( 'Error!' );
                        return false;
                    }
                );

            }

            /**
             * Change button status to in-progress
             *
             * @return void
             */
            function buttonStatusInProgress( message ){
                $button.addClass('updating-message').removeClass('button-disabled installed').text( message );
            }

            /**
             * Change button status to disabled
             *
             * @return void
             */
            function buttonStatusDisabled( message ){
                $button.removeClass('updating-message installed')
                        .addClass('button-disabled')
                        .text( message );
            }

            /**
             * Change button status to installed
             *
             * @return void
             */
            function buttonStatusInstalled( message ){
                $button.removeClass('updating-message')
                        .addClass('installed')
                        .text( message );
            }

            const $plugins_info = $button.data('info');
            function run($key = 0) {
                if (typeof $plugins_info[$key] == 'undefined' || $plugins_info[$key]['data-plugin-order'] > $plugins_info[0]['data-num-of-required-plugins'] ) {
                    location.replace( $plugins_info[$plugins_info.length - 1]['data-redirect-url'] );
                    return;
                }
                let $this = $plugins_info[$key];
                if( $this['data-action'] === 'install' ){
                    installPlugin($this);
                } else if( $this['data-action'] === 'activate' ){
                    activatePlugin($this);
                }
            }
            run();

        });

    });

})(jQuery, window, document);
</script>
<style>
.uicore-msg{
    background-size: 900px;
    background-position: center right;
    background-repeat: no-repeat;
    background-color: #c8ff63;
    color: black;
    padding: 35px 60px;
    margin: 5px 20px 5px 2px;
    position: relative;
    border: 1px solid #ccd0d4;
    box-shadow: 0 1px 1px rgba(0,0,0,.04), inset 10px 10px 0 #fff, inset -10px -10px 0 #fff;

}
.uicore-title-default{
    color:black;
    font-size: 32px;
}
.uicore-description-default{
    color: black;
    font-size: 18px;
    opacity: .7;
    max-width: calc(100% - 550px);
}
.uicore-btn-default{
  line-height: 50px;
  font-weight: 500;
  text-align: center;
  color: white;
  border-radius: 4px;
  background: black;
  cursor: pointer;
  display: inline-block;
  text-decoration: none;
  position: relative;
  transition: all 0.2s ease-in-out;
  font-size: 16px;
  padding:0 40px;
}
.uicore-btn-default:hover{
    background:rgba(255, 255, 255, .85);
    color:#1b0c5d;
}
.uicore-close::before{
    font-size: 16px;
}
.uicore-close {
  text-decoration: none;
  top: 3px;
  right: 3px;
}
.uicore-close:hover::before{
    text-decoration: none;
}
</style>
    <div class="uicore-msg" style="background-image:url(<?php echo get_template_directory_uri(); ?>/assets/img/demo-import-bg.jpg)">
        <h3 class="uicore-title-default"><?php printf( __( 'Thanks for choosing %s', 'outgrid' ), UICORE_THEME_NAME ); ?></h3>
        <p class="uicore-description-default"><?php echo __( 'Please install and activate the required plugins to gain access to all the theme functionalities and features.', 'outgrid' ); ?></p>
        <p class="submit">
            <a class="uicore-btn-default" data-info='<?php echo wp_json_encode( $links_attrs);?>' >Install Required Plugins</a>
            <a href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'uicore-fw-notice', 'install' ), 'uicore-fw-notice', '_notice_nonce' ) ); ?>" class="notice-dismiss uicore-close"><span class="screen-reader-text"><?php _e( 'Skip', 'outgrid' ); ?></span></a>
        </p>
    </div>
<?php
}
add_action( 'admin_notices', 'uicore_plugin_notice' );

function uicore_hide_notice() {

    if ( isset( $_GET['uicore-fw-notice'] ) && isset( $_GET['_notice_nonce'] ) ) {
        if ( ! wp_verify_nonce( $_GET['_notice_nonce'], 'uicore-fw-notice' ) ) {
            wp_die( __( 'Authorization failed. Please refresh the page and try again.', 'outgrid' ) );
        }
        set_transient( 'uicore-fw-notice_' . UICORE_THEME_NAME, 1, MONTH_IN_SECONDS );
    }

}
add_action( 'wp_loaded', 'uicore_hide_notice' );
