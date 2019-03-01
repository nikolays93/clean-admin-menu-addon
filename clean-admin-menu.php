<?php

namespace NikolayS93\CleanAdminMenu;

/*
 * Plugin Name: Clearfy: Clean admin menu
 * Plugin URI: https://github.com/nikolays93
 * Description: Helps for you to hide unused functionality and helps to get rid of annoying promo items.
 * Version: 0.2
 * Author: NikolayS93
 * Author URI: https://vk.com/nikolays_93
 * Author EMAIL: NikolayS93@ya.ru
 * License: GNU General Public License v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: clean-admin-menu
 * Domain Path: /languages/
 */

if ( !defined( 'ABSPATH' ) ) exit('You shall not pass');

require_once ABSPATH . "wp-admin/includes/plugin.php";

class Plugin
{
    protected static $data;
    protected static $options;

    private function __construct() {}
    private function __clone() {}

    /**
     * Get option name for a options in the Wordpress database
     */
    public static function get_option_name()
    {
        return apply_filters("get_{DOMAIN}_option_name", DOMAIN);
    }

    /**
     * Define required plugin data
     */
    public static function define()
    {
        self::$data = get_plugin_data(__FILE__);

        if( !defined(__NAMESPACE__ . '\DOMAIN') )
            define(__NAMESPACE__ . '\DOMAIN', self::$data['TextDomain']);

        if( !defined(__NAMESPACE__ . '\PLUGIN_DIR') )
            define(__NAMESPACE__ . '\PLUGIN_DIR', __DIR__);

        self::$data['Name'] = __('Clearfy: Clean admin menu', DOMAIN);
        self::$data['Description'] = __('Helps for you to hide unused functionality and helps to get rid of annoying promo items.', DOMAIN);
    }

    /**
     * include required files
     */
    public static function initialize()
    {
        load_plugin_textdomain( DOMAIN, false, basename(PLUGIN_DIR) . '/languages/' );

        require PLUGIN_DIR . '/include/utils.php';

        if( is_admin() ) {
            require_once(PLUGIN_DIR . '/include/options.php');
            require_once(PLUGIN_DIR . '/include/clean.php');
        }

        // $autoload = PLUGIN_DIR . '/vendor/autoload.php';
        // if( file_exists($autoload) ) include $autoload;

        add_action( 'admin_enqueue_scripts', array(__CLASS__, '_admin_assets') );

        add_action( 'wbcr/factory/pages/impressive/before_form_save', function($form, $plugin, $this) {

            if( isset($_POST[self::get_option_name()]) ) {
                update_option(
                    self::get_option_name(),
                    json_decode( str_replace('\"', "\"", $_POST[self::get_option_name()]) )
                );
            }

        }, 10, 3 );
    }

    static function uninstall() { delete_option( self::get_option_name() ); }
    static function activate()
    {
        add_option( self::get_option_name(), array(
            'menu'     => array('edit-comments.php', 'users.php', 'tools.php'),
            'sub_menu' => array(
                array(
                    'parent' => 'edit.php?post_type=shop_order',
                    'obj' => 'edit.php?post_type=shop_order',
                ),
                array(
                    'parent' => 'edit.php?post_type=shop_order',
                    'obj' => 'edit.php?post_type=shop_coupon',
                ),
                array(
                    'parent' => 'edit.php?post_type=shop_order',
                    'obj' => 'admin.php?page=wc-reports',
                ),
                array(
                    'parent' => 'options-general.php',
                    'obj' => 'options-discussion.php',
                ),
            ),
        ) );
    }

    static function _admin_assets()
    {
        wp_enqueue_style(  'ncam_clean_tools', Utils::get_plugin_url() . '/assets/admin.css', array(), self::$data['Version'] );
        wp_enqueue_script( 'ncam_clean_tools', Utils::get_plugin_url() . '/assets/admin.js', array( 'jquery' ), self::$data['Version'], true );

        $options = Utils::get(null, array());
        $options['option_name'] = self::get_option_name();

        wp_localize_script( 'ncam_clean_tools', 'ncam_options', $options );
    }
}

Plugin::define();

register_activation_hook( __FILE__, array( __NAMESPACE__ . '\Plugin', 'activate' ) );
register_uninstall_hook( __FILE__, array( __NAMESPACE__ . '\Plugin', 'uninstall' ) );
// register_deactivation_hook( __FILE__, array( __NAMESPACE__ . '\Plugin', 'deactivate' ) );

add_action( 'plugins_loaded', array( __NAMESPACE__ . '\Plugin', 'initialize' ), 10 );
