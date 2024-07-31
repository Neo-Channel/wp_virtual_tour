<?php

/**
 *
 * Plugin Name:       Panorom
 * Plugin URI:        https://wordpress.org/plugins/panorom/
 * Description:       Panorom - 360° panorama and virtual tour builder with interactive and easy-to-use interface.
 * Version:           5.8.0
 * Author:            Panorom
 * Author URI:        https://panorom.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       panorom
 * Domain Path:       /languages
*/

if ( !defined( 'ABSPATH' ) ) exit;


define('PNRM_VERSION', '5.8.0');
define("PNRM_DIR_URL", plugin_dir_url(__FILE__));
define("PNRM_DIR_PATH", plugin_dir_path(__FILE__));
define("PNRM_FLASH_CLASS_ERROR", 'notice notice-error is-dismissible');
define("PNRM_FLASH_CLASS_WARNING", 'notice notice-warning is-dismissible');
define("PNRM_FLASH_CLASS_SUCCESS", 'notice notice-success is-dismissible');
define("PNRM_NONCE_NAME", 'pnrm_nonce');
define("PNRM_NONCE_ACTION", 'pnrm_action');


require_once(PNRM_DIR_PATH . 'classes/class-panorom.php');
require_once(PNRM_DIR_PATH . 'classes/class-panorom-info.php');
require_once(PNRM_DIR_PATH . 'classes/class-panorom-editor.php');
require_once(PNRM_DIR_PATH . 'classes/class-panorom-tour.php');
require_once(PNRM_DIR_PATH . 'classes/class-panorom-api.php');


register_activation_hook(__FILE__, array('Panorom', 'activate') );
register_deactivation_hook(__FILE__, array('Panorom', 'deactivate') );
register_uninstall_hook(__FILE__, array('Panorom', 'uninstall') );


add_shortcode( 'panorom', array('Panorom', 'shortcode') );

add_action('admin_enqueue_scripts', array('Panorom', 'admin_scripts'));
add_action( 'admin_menu', array('Panorom', 'add_admin_menu') );
add_action('wp_ajax_get_tour', array('Panorom_Tour', 'get_tour_ajax') );
add_action('wp_ajax_nopriv_get_tour', array('Panorom_Tour', 'get_tour_ajax') );
add_action('wp_ajax_update_tour', array('Panorom_Tour', 'update_tour_ajax') );



add_action( 'init', array('Panorom_Tour', 'register_tour_post_type') );






