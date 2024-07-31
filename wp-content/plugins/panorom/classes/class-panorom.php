<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

  // echo '<h2 style="color: red; position: absolute; z-index: 100000; top: 100px; left: 50%;">' . 'file running' . '</h2>';


class Panorom {

  public static function activate() {
    // is running

    Panorom_Tour::add_default_tour();
    Panorom_Api::init_db();
  
  }
    
  public static function deactivate() {
    // is running

    // Panorom_Tour::remove_all_db(); // will be moved to uninstall hook
    // Panorom_Api::remove_all_db(); // will be moved to uninstall hook

  }

  public static function uninstall() {
    // is running
    Panorom_Tour::remove_all_db();
    Panorom_Api::remove_all_db();
  }

  public static function shortcode( $atts ) {
    // load styles
    wp_enqueue_style('pnrm-style-viewer', PNRM_DIR_URL . 'public/css/viewer.css', array(), PNRM_VERSION);
    wp_enqueue_style('pnrm-style-pannellum-min', PNRM_DIR_URL . 'public/css/pannellum-mod.min.css', array(), PNRM_VERSION);
    wp_enqueue_style('pnrm-style-swiper', PNRM_DIR_URL . 'public/css/swiper.min.css', array(), PNRM_VERSION);
    wp_enqueue_style('pnrm-style-fontawesome', PNRM_DIR_URL . 'public/fontawesome/css/fontawesome.min.css', array(), PNRM_VERSION);
    wp_enqueue_style('pnrm-style-solid', PNRM_DIR_URL . 'public/fontawesome/css/solid.min.css', array(), PNRM_VERSION);

    // load javascript
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script('pnrm-script-libpannellum', PNRM_DIR_URL . 'public/js/libpannellum.min.js', array(), PNRM_VERSION, true);
    wp_enqueue_script('pnrm-script-pannellum-mod', PNRM_DIR_URL . 'public/js/pannellum-mod.min.js', array(), PNRM_VERSION, true);
    wp_enqueue_script('pnrm-script-viewer', PNRM_DIR_URL . 'public/js/viewer.min.js', array(), PNRM_VERSION, true);
    wp_enqueue_script('pnrm-script-thumbnail-bar', PNRM_DIR_URL . 'public/js/thumbnail-bar.min.js', array(), PNRM_VERSION, true);
    wp_enqueue_script('pnrm-script-swiper', PNRM_DIR_URL . 'public/js/swiper.min.js', array(), PNRM_VERSION, true);
    wp_localize_script( 'pnrm-script-viewer', 'pnrm_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'is_activated' => esc_attr(Panorom_Api::is_activated()) ) );

    
    if ( !is_array( $atts ) || empty($atts['id'])) {
      return null;
    }
    
    $id = $atts['id'];
    $post = Panorom_Tour::get_tour_by_id($id);
    
    if(empty($post)) {
      return null;
    }
    
    
    // show all tours even if is_activated false


    $configObj = json_decode($post->meta_config);
    $height = isset($configObj->default->pnrmHeight) ? (int)$configObj->default->pnrmHeight : 500;
    
    $start_from = !empty($atts['start']) ? sanitize_key($atts['start']) : null;
    // if ($start_from) {
    //   $configObj->default->firstScene = $start_from;
    // }
    $classFullscreen = !empty($configObj->default->pnrmFullscreen) ? 'pnrm-fullscreen' : '';
    
    // here the $post with extracted data is available and we can show it
    $output = "";
    $output .= "<div class='pnrm-viewer'>";
    $output .= "<div class='box-main-interface " . $classFullscreen . "' dir='ltr' style='text-align: left;'>";
    $output .= "<div class='inside-ui' data-insert='on' style='display: none;'>";
    $output .= "<a href='' class='custom-logo'></a>";
    //   $output .= "<a href='https://panorom.com' target='_blank'>" . '<svg width="32" height="32" version="1.1" viewBox="0 0 67.733 67.733" xmlns="http://www.w3.org/2000/svg" class="custom-logo">
    //   <g transform="translate(7.9532 -25.492)">
    //    <g transform="matrix(.60606 0 0 1.0401 -37.598 -25.166)" fill="#d45500" stroke-width=".44433">
    //     <path d="m134.08 82.792s23.67 6.4899 8.6517 13.402c-2.4402 1.1208-13.29 4.6598-29.674 5.3538v3.4698c9.6231-0.543 24.768-2.1469 33.348-6.8694 18.255-10.05-12.324-15.357-12.324-15.357z"/>
    //     <path d="m106.07 102.4-10.949-6.8714c-1.4257-0.50439-2.5958-0.18575-2.5958 0.70649v5.064c-14.079-1.0474-23.348-4.0824-25.575-5.1085-15.022-6.9122 8.6517-13.402 8.6517-13.402s-30.581 5.3069-12.326 15.357c7.4438 4.0974 19.819 5.8457 29.251 6.5921v5.0596c0 0.89504 1.1702 1.2105 2.5958 0.70787l10.949-6.2715c1.4053-0.80541 1.397-1.0118 0-1.8332z"/>
    //    </g>
    //    <g fill="#fff" aria-label="p">
    //     <path d="m20.14 68.254q-0.84835 0-1.3668-0.51844-0.4713-0.4713-0.4713-1.3197v-22.246q0.04713-3.6762 1.7438-6.5983 1.7438-2.9692 4.6659-4.6659 2.9692-1.6967 6.6454-1.6967 3.7704 0 6.7397 1.7438 2.9692 1.6967 4.6659 4.6659 1.7438 2.9692 1.7438 6.7397 0 3.7233-1.6967 6.6925-1.6496 2.9692-4.5245 4.713-2.875 1.6967-6.504 1.6967-3.1577 0-5.7499-1.3197-2.545-1.3668-4.1004-3.5819v13.856q0 0.84835-0.51844 1.3197-0.4713 0.51844-1.2725 0.51844zm11.217-14.092q2.7336 0 4.9016-1.2725 2.168-1.2725 3.3934-3.4877 1.2725-2.2623 1.2725-5.043 0-2.8278-1.2725-5.043-1.2254-2.2151-3.3934-3.4877-2.168-1.3197-4.9016-1.3197-2.6864 0-4.8544 1.3197-2.168 1.2725-3.3934 3.4877-1.2254 2.2151-1.2254 5.043 0 2.7807 1.2254 5.043 1.2254 2.2151 3.3934 3.4877 2.168 1.2725 4.8544 1.2725z" fill="#fff" stroke-width=".26458"/>
    //    </g>
    //   </g>
    //  </svg>' . "</a>";
    $output .= "<div class='pnrm-handle-move'></div>";
    $output .= "<span class='pnrm-audio-icon icon-audio-off'><i class='fa-solid fa-volume-xmark'></i></span>";
    $output .= "<span class='pnrm-audio-icon icon-audio-on'><i class='fa-solid fa-volume-high'></i></span>";
    $output .= "<span class='pnrm-icon-go-back' title='Go Back'><i class='fa-solid fa-arrow-left'></i></span>";
    $output .= "</div>";
    $fontSizeText = isset($configObj->default->pnrmFontSize) ? "font-size: " . esc_attr($configObj->default->pnrmFontSize) . "px;" : "";
    $fontFamilyText = isset($configObj->default->pnrmFontFamily) ? "font-family: inherit;" : "";
    // $output .= "<div class='pnrm-div' data-config='" . esc_attr(json_encode($configObj)) . "' style='height: " . esc_attr($height) . "px; " . $fontSizeText . $fontFamilyText . "'></div>";
    $output .= "<div class='pnrm-div' data-tour-id='" . esc_attr($id) . "' data-start-scene-id='" . esc_attr($start_from) . "' style='height: " . esc_attr($height) . "px; " . $fontSizeText . $fontFamilyText . "'></div>";
    // start info overlay
    $output .= "<div class='info-overlay'> ";
    $output .= "<div class='box-content'>";
    $output .= "<p class='info-title'></p>";
    $output .= "<img src='#' alt='info image' class='info-image'>";
    $output .= "</div>";
    $output .= "<span class='close-icon' title='close'><img src=" . esc_url(PNRM_DIR_URL . 'public/img/x.svg') . " alt='close icon'></span>";
    $output .= "</div>";
    // end info overlay
    $output .= "</div>"; // end of box-main-interface
    $output .= "</div>"; // end of pnrm-viewer

    return $output;
  }

  public static function admin_scripts() {
    // load styles
    wp_enqueue_style('pnrm-style-info', PNRM_DIR_URL . 'public/css/info.css', array(), PNRM_VERSION);
    wp_enqueue_style('pnrm-style-tour', PNRM_DIR_URL . 'public/css/tour.css', array(), PNRM_VERSION);
    wp_enqueue_style('pnrm-style-api', PNRM_DIR_URL . 'public/css/api.css', array(), PNRM_VERSION);
    wp_enqueue_style('pnrm-style-editor', PNRM_DIR_URL . 'public/css/editor.css', array(), PNRM_VERSION);
    wp_enqueue_style('pnrm-style-pannellum-min', PNRM_DIR_URL . 'public/css/pannellum-mod.min.css', array(), PNRM_VERSION);
    wp_enqueue_style('pnrm-style-swiper', PNRM_DIR_URL . 'public/css/swiper.min.css', array(), PNRM_VERSION);
    wp_enqueue_style('pnrm-style-fontawesome', PNRM_DIR_URL . 'public/fontawesome/css/fontawesome.min.css', array(), PNRM_VERSION);
    wp_enqueue_style('pnrm-style-solid', PNRM_DIR_URL . 'public/fontawesome/css/solid.min.css', array(), PNRM_VERSION);

    // load scripts
    wp_enqueue_script( 'jquery' );
    wp_enqueue_media();
    wp_enqueue_script('pnrm-script-info', PNRM_DIR_URL . 'public/js/info.js', array(), PNRM_VERSION, true);
    wp_enqueue_script('pnrm-script-api', PNRM_DIR_URL . 'public/js/api.js', array(), PNRM_VERSION, true);
    wp_enqueue_script('pnrm-script-tour', PNRM_DIR_URL . 'public/js/tour.min.js', array(), PNRM_VERSION, true);
    wp_enqueue_script('pnrm-script-editor', PNRM_DIR_URL . 'public/js/editor.min.js', array(), PNRM_VERSION, true);
    wp_enqueue_script('pnrm-script-libpannellum', PNRM_DIR_URL . 'public/js/libpannellum.min.js', array(), PNRM_VERSION, true);
    wp_enqueue_script('pnrm-script-pannellum-mod', PNRM_DIR_URL . 'public/js/pannellum-mod.min.js', array(), PNRM_VERSION, true);
    wp_enqueue_script('pnrm-script-thumbnail-bar', PNRM_DIR_URL . 'public/js/thumbnail-bar.min.js', array(), PNRM_VERSION, true);
    wp_enqueue_script('pnrm-script-swiper', PNRM_DIR_URL . 'public/js/swiper.min.js', array(), PNRM_VERSION, true);


  }
  

  public static function add_admin_menu() {
    add_menu_page( 'Panorom', 'Panorom', 'manage_options', 'panorom', array('Panorom_Info', 'handle_page'), PNRM_DIR_URL . 'public/img/plugin_icon.png', null );
    add_submenu_page( 'panorom', 'Panorom', '<span class="dashicons dashicons-info-outline" style="font-size: 17px; margin-left: 5px;"></span> Info', 'manage_options', 'panorom', array('Panorom_Info', 'handle_page'), null );
    add_submenu_page( 'panorom', 'Panorom Editor', '<span class="dashicons dashicons-move" style="font-size: 17px; margin-left: 5px;"></span> Editor', 'manage_options', 'panorom-editor', array('Panorom_Editor', 'handle_page'), null );
    add_submenu_page( 'panorom', 'Panorom Tours', '<span class="dashicons dashicons-open-folder" style="font-size: 17px; margin-left: 5px;"></span> Tours', 'manage_options', 'panorom-tours', array( 'Panorom_Tour', 'handle_page' ), null );
    add_submenu_page( 'panorom', 'Panorom API', '<span class="dashicons dashicons-admin-network" style="font-size: 17px; margin-left: 5px;"></span> API', 'manage_options', 'panorom-api', array( 'Panorom_Api', 'handle_page' ), null );

  }


}