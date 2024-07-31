<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define("PNRM_POST_TYPE", 'pnrm_tour');
define("PNRM_META_CONFIG_NAME", 'pnrm_config');
// define("PNRM_META_CONFIG_DEFAULT_VALUE", '{"default":{},"scenes":{}}');
define("PNRM_META_CONFIG_DEFAULT_VALUE", '{"default":{"pnrmHeight":500,"pnrmHfov":100,"autoLoad":true,"compass":false,"mouseZoom":false},"scenes":{}}');




class Panorom_Tour {


  public static function register_tour_post_type() {
    register_post_type(PNRM_POST_TYPE);
  }

  public static function add_default_tour() {
    self::add_tour('First Tour');
  }

  public static function remove_all_db() {
    $posts = self::get_tours();
    foreach($posts as $post) {
      wp_delete_post($post->ID, true);
    }
  }

  private static function is_max_tour_reached() {
    $all_tours = self::get_tours();
    if(count($all_tours) >= 2 + 1) {
      return true;
    }
    return false;
  }

  public static function get_max_allowed_tours() {
    return 2 + 1;
  }

  // return error and data
  public static function get_tour_ajax() {

    $responseObj = new stdClass();
    $responseObj->data = null;
    $responseObj->error = null;

    // global $wpdb;
    $post_id = isset($_POST['post_id']) ? sanitize_key($_POST['post_id']) : '';

    if(empty($post_id)) {
      $responseObj->error = 'tour id not provided';
      // echo json_encode($responseObj);
      // wp_die();
      wp_send_json($responseObj);
    }

    $found_post = self::get_tour_by_id($post_id);

    if($found_post === null) {
      $responseObj->error = 'Tour not found';
      // echo json_encode($responseObj);
      // wp_die();
      wp_send_json($responseObj);
    }

    $responseObj->data = $found_post->meta_config;
    // echo json_encode($responseObj);
    // wp_die();
    wp_send_json($responseObj);
  }

  // returns error if any
  public static function update_tour_ajax() {

    $responseObj = new stdClass();
    $responseObj->error = null;

    // global $wpdb;
    $post_id = isset($_POST['post_id']) ? sanitize_key($_POST['post_id']) : '';
    $config = isset($_POST['config']) ? sanitize_text_field($_POST['config']) : '';

    if ( !check_admin_referer( PNRM_NONCE_ACTION, PNRM_NONCE_NAME ) ) {
      // $responseObj->error = 'nonce error';
      // wp_send_json($responseObj);
      // automatically sends forbidden status code
      wp_die();
    }

    if(empty($post_id) || empty($config)) {
      $responseObj->error = 'tour id or config data not provided';
      wp_send_json($responseObj);
    }

    $found_post = self::get_tour_by_id($post_id);

    if($found_post === null) {
      $responseObj->error = 'Tour not found';
      wp_send_json($responseObj);
    }
    
    update_post_meta($post_id, PNRM_META_CONFIG_NAME, $config);
    $responseObj->error = null;
    wp_send_json($responseObj);


  }

  public static function get_tours() {
    $args = array(
      'numberposts' => -1,
      'post_type' => PNRM_POST_TYPE,
      'orderby' => 'ID',
		  'order' => 'ASC',
    );
    $posts = get_posts($args);

    $posts_extract = array();
    foreach($posts as $post) {
      $extract = self::extract_from_post($post);
      array_push($posts_extract, $extract);
    }

    return $posts_extract;
  }


  // returns null or an array containing post data
  public static function get_tour_by_id($id) {
    $post = get_post($id);
    if(empty($post) || $post->post_type !== PNRM_POST_TYPE) {
      return null;
    }
    $extract = self::extract_from_post($post);
    return $extract;
  }

  // extracts and adds meta to post
  private static function extract_from_post($post) {
    $extract = new stdClass();
    $extract->ID = $post->ID;
    $extract->post_title = $post->post_title;
    $extract->post_name = $post->post_name;
    $extract->post_type = $post->post_type;
    $extract->post_date = $post->post_date;
    $extract->meta_config = get_post_meta($post->ID, PNRM_META_CONFIG_NAME, true);
    return $extract;
  }

  private static function tour_title_exists($title) {
    $posts = self::get_tours();
    foreach($posts as $post) {
      if($post->post_title === $title) {
        return true;
      }
    }
    return false;
  }


  // returns error string or null if no error
  private static function add_tour($tour_title) {

    if(empty($tour_title)) {
      $error = 'Tour name cannot be empty!';
      return $error;
    }

    // check if $tour_title does not exist
    if(self::tour_title_exists($tour_title)) {
      $error = 'Tour name already exists!';
      return $error;
    }

    // start inserting into database
    $args = array(
      'post_type' => PNRM_POST_TYPE,
      'post_title' => $tour_title,
      'post_status' => 'publish',
    );
    $insert_result = wp_insert_post($args, true);

    // if there was an error in the post insertion, 
    if(is_wp_error($insert_result)){
      $error = $insert_result->get_error_message();
      return $error;
    }

    // if no error $insert_result is the post_id
    $post_id = $insert_result;

    // insert default config meta
    update_post_meta($post_id, PNRM_META_CONFIG_NAME, PNRM_META_CONFIG_DEFAULT_VALUE);

    // added tour successfully, return null
    $error = null;
    return $error;
  }

  // returns error message
  private static function edit_tour($id, $new_title) {

    if(empty($new_title)) {
      $error = 'Tour name cannot be empty!';
      return $error;
    }

    // check if title already exists
    if(self::tour_title_exists($new_title)) {
      $error = 'Tour name already exists!';
      return $error;
    }
    
    $post = self::get_tour_by_id($id);
    if(empty($post)) {
      $error = 'Tour not found!';
      return $error;
    }
    
    // start updating database
    $args = array(
      'ID' => $id,
      'post_title' => $new_title,
    );
    $update_result = wp_update_post($args, true);

    // if there was error in the post update
    if(is_wp_error($update_result)){
      $error = $update_result->get_error_message();
      return $error;
    }

    // tour title updated successfully and $error is null
    $error = null;
    return $error;

  }

  // returns error message
  private static function copy_tour($tour_id_origin, $tour_title_copy) {

    if(empty($tour_title_copy)) {
      $error = 'Tour name cannot be empty!';
      return $error;
    }

    // check if title already exists
    if(self::tour_title_exists($tour_title_copy)) {
      $error = 'Tour name already exists!';
      return $error;
    }

    // start inserting a new tour into database
    $args = array(
      'post_type' => PNRM_POST_TYPE,
      'post_title' => $tour_title_copy,
      'post_status' => 'publish',
    );
    $insert_result = wp_insert_post($args, true);

    // if there was an error in the post insertion, 
    if(is_wp_error($insert_result)){
      $error = $insert_result->get_error_message();
      return $error;
    }

    // if no error $insert_result is the post_id_copy
    $post_id_copy = $insert_result;

    // get origin post
    $post_origin = self::get_tour_by_id($tour_id_origin);
    if(empty($post_origin)) {
      $error = 'Tour not found!';
      return $error;
    }

    // copy config meta to new tour
    update_post_meta($post_id_copy, PNRM_META_CONFIG_NAME, $post_origin->meta_config);

    // tour copied successfully, return null
    $error = null;
    return $error;

  }

  // returns error, if any
  private static function delete_tour($id) {
    
    $post = self::get_tour_by_id($id);

    if(empty($post)) {
      $error = 'Tour not found!';
      return $error;
    }

    wp_delete_post($post->ID, true);

    return null;
  }

  private static function get_tour_id_from_tour_title($title) {
    $posts = self::get_tours();
    foreach($posts as $post) {
      if($post->post_title === $title) {
        return $post->ID;
      }
    }
    return -1;
  }

  // returns error, if any
  private static function import_tour($tour_title, $config) {

    // check if user has pro version
    if( !Panorom_Api::is_activated() ) {
      $error = 'PRO plan is not active';
      return $error;
    }

    // return 'error in importing tour';
    if( empty($tour_title) || empty($config) ) {
      $error = 'tour_title or config data not provided';
      return $error;
    }

    // check if tour_title exists and fetch its id
    $tour_id = self::get_tour_id_from_tour_title($tour_title);

    // if no id, first create a new post tour_type with that name and then get its id
    if ($tour_id == -1) {

      // start inserting into database
      $args = array(
        'post_type' => PNRM_POST_TYPE,
        'post_title' => $tour_title,
        'post_status' => 'publish',
      );
      $insert_result = wp_insert_post($args, true);

      // if there was an error in the post insertion, 
      if(is_wp_error($insert_result)){
        $error = $insert_result->get_error_message();
        return $error;
      }

      // if no error $insert_result is the post_id
      $tour_id = $insert_result;
    }

    // with id then update config of that id
    update_post_meta($tour_id, PNRM_META_CONFIG_NAME, $config);

    return null;
  }









  public static function handle_page() {
    $flash_msg = '';
    $flash_class = '';

    // handle post
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

      $action = isset($_POST['action']) ? sanitize_key( $_POST['action'] ) : '';
      $post_id = isset($_POST['post_id']) ? sanitize_key( $_POST['post_id'] ) : '';
      $tour_name = isset($_POST['tour_name']) ? sanitize_text_field( $_POST['tour_name'] ) : '';
      $config = isset($_POST['config']) ? sanitize_text_field($_POST['config']) : '';
      // $nonce = isset($_POST[PNRM_NONCE_NAME]) ? wp_kses( $_POST[PNRM_NONCE_NAME], array() ) : '';

      // print_r($action);
      // print_r($post_id);
      // print_r($tour_name);

      // check if nonce is incorrect (user is not using admin frontend)
      if ( !check_admin_referer( PNRM_NONCE_ACTION, PNRM_NONCE_NAME ) ) {
        return;
      }

      switch($action) {
        case 'add':
          $error = self::add_tour($tour_name);
          if($error !== null) {
            // update the flash error message
            $flash_msg = $error;
            $flash_class = PNRM_FLASH_CLASS_ERROR;
          }
          else {
            // success, say that tour name added successfully
            $flash_msg = 'Tour is added successfully.';
            $flash_class = PNRM_FLASH_CLASS_SUCCESS;
          }
          break;
        case 'edit':
          $error = self::edit_tour($post_id, $tour_name);
          if($error !== null) {
            // update flash error message
            $flash_msg = $error;
            $flash_class = PNRM_FLASH_CLASS_ERROR;
          }
          else {
            // succees, update flash success message
            $flash_msg = 'Tour is updated successfully.';
            $flash_class = PNRM_FLASH_CLASS_SUCCESS;
          }
          break;
        case 'copy':
          $error = self::copy_tour($post_id, $tour_name);
          if($error !== null) {
            // update flash error message
            $flash_msg = $error;
            $flash_class = PNRM_FLASH_CLASS_ERROR;
          }
          else {
            // succees, update flash success message
            $flash_msg = 'Tour is copied successfully.';
            $flash_class = PNRM_FLASH_CLASS_SUCCESS;
          }
          break;
        case 'delete':
          $error = self::delete_tour($post_id);
          if($error !== null) {
            // update flash error message
            $flash_msg = $error;
            $flash_class = PNRM_FLASH_CLASS_ERROR;
          }
          else {
            // success, update flash success message
            $flash_msg = 'Tour is deleted successfully.';
            $flash_class = PNRM_FLASH_CLASS_SUCCESS;
          }
          break;
        case 'import':
          $error = self::import_tour($tour_name, $config);
          if($error !== null) {
            // update flash error message
            $flash_msg = $error;
            $flash_class = PNRM_FLASH_CLASS_ERROR;
          }
          else {
            // success, update flash success message
            $flash_msg = 'Tour imported successfully.';
            $flash_class = PNRM_FLASH_CLASS_SUCCESS;
          }
          break;

      }

    }

    // get most updated posts
    $posts = self::get_tours();
    $is_activated = Panorom_Api::is_activated();
    ?>
    <div class="pnrm-tour">

      <div id="div-pnrm-nonce">
        <?php wp_nonce_field( PNRM_NONCE_ACTION , PNRM_NONCE_NAME ); ?>
      </div>

      <h1>Panorom Tours</h1>

      <input type="hidden" id="input-is-activated" value="<?php echo esc_attr($is_activated ? 'true' : 'false' ); ?>">

      <div class="buttons-import-export">
        <button class="btn-export-top">Export</button>
        <button class="btn-import-top">Import</button>
      </div>

      <div class="<?php echo esc_attr($flash_class); ?>">
        <p><?php echo esc_html($flash_msg); ?></p>
      </div>
      
      <button class="btn-add" <?php echo ($is_activated || !self::is_max_tour_reached()) ? '' : 'disabled'; ?>>Add New Tour</button>
      <?php echo ($is_activated || !self::is_max_tour_reached()) ? '' : '<div>Reached maximum number of tours. <a href="https://panorom.com/pro?source=plugin" target="_blank" class="btn-upgrade">Upgrade to PRO Version</a></div>'; ?>

      <h2>All Tours</h2>

      <table>
        <thead>
          <tr>
            <td>#</td>
            <td class="post-id">post id</td>
            <td>Name</td>
            <td>Shortcode</td>
            <td>Created At</td>
            <td>Actions</td>
          </tr>
        </thead>
        <tbody>
          <!-- <tr>
            <td>1</td>
            <td class="post-id">35</td>
            <td><a href="" class="tour-link"><span class="tour-name">default</span></a></td>
            <td><span class="shortcode">[panorom id="35"]</span></td>
            <td>2023/02/23 at 1:07 pm</td>
            <td>
              <button class="btn-copy" title="copy"><span class="dashicons dashicons-admin-page icon"></span></button>
              <button class="btn-edit" title="edit"><span class="dashicons dashicons-edit-page icon"></span></button>
              <button class="btn-delete" title="delete"><span class="dashicons dashicons-trash icon"></span></button>
            </td>
          </tr> -->
        <?php 
          $item_number = 0;
          foreach($posts as $post) {
            $item_number++;
            if($is_activated || $item_number <= self::get_max_allowed_tours()) {
              $current_url = sanitize_url("//$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
              $target_url = str_replace("page=panorom-tours", "page=panorom-editor&tour_id=" . esc_html($post->ID), $current_url);

              $output = '<tr>';
              $output .= '<td>' . esc_html($item_number) . '</td>';
              $output .= '<td class="post-id">' . esc_html($post->ID) . '</td>';
              $output .= '<td><a href="' . esc_url($target_url) . '" class="tour-link"><span class="tour-name">' . esc_html($post->post_title) . '</span></a></td>';
              $output .= '<td><span class="shortcode" dir="ltr">[panorom id="' . esc_attr($post->ID) . '"]</span></td>';
              $output .= '<td>' . esc_html( date('d-M-Y G:i', strtotime($post->post_date)) ) . '</td>';
              $output .= '<td>';
              $output .= '<button class="btn-copy" title="copy"><span class="dashicons dashicons-admin-page icon"></span></button>';
              $output .= '<button class="btn-edit" title="edit"><span class="dashicons dashicons-edit-page icon"></span></button>';
              $output .= '<button class="btn-delete" title="delete"><span class="dashicons dashicons-trash icon"></span></button>';
              $output .= '</td>';
              $output .= '</tr>';
              echo $output;
            }
          }
        ?>
        </tbody>
      </table>

      <!-- Modals -->

      <!-- Modal Add Tour -->

      <div class="modal modal-add">
        <div class="dialog-box">
          <form action="">
            <div class="title">Add New Tour</div>
            <div class="content">
              <label for="input-add-tour-name">Tour Name:</label>
              <input type="text" id="input-add-tour-name" required>
            </div>
            <div class="buttons">
              <button type="submit" class="btn-ok">Add Tour</button>
              <button type="button" class="btn-cancel">Cancel</button>
            </div>
          </form>
        </div>
      </div>

      <!-- Modal Edit Tour -->

      <div class="modal modal-edit">
        <div class="dialog-box">
          <form action="">
            <div class="title">Edit Tour</div>
            <div class="content">
              <input type="hidden" id="input-edit-post-id">
              <label for="input-edit-tour-name">Tour Name:</label>
              <input type="text" id="input-edit-tour-name" required>
            </div>
            <div class="buttons">
              <button type="submit" class="btn-ok">Apply</button>
              <button type="button" class="btn-cancel">Cancel</button>
            </div>
          </form>
        </div>
      </div>

      <!-- Modal Copy Tour -->

      <div class="modal modal-copy">
        <div class="dialog-box">
          <form action="">
            <div class="title">Copy Tour</div>
            <div class="content">
              <input type="hidden" id="input-copy-post-id">
              <label for="input-copy-tour-name">Tour Name:</label>
              <input type="text" id="input-copy-tour-name" required>
            </div>
            <div class="buttons">
              <button type="submit" class="btn-ok">Apply</button>
              <button type="button" class="btn-cancel">Cancel</button>
            </div>
          </form>
        </div>
      </div>

      <!-- Modal Delete Tour -->

      <div class="modal modal-delete">
        <div class="dialog-box">
          <form action="">
            <div class="title">Delete Tour</div>
            <div class="content">
              <input type="hidden" id="input-delete-post-id">
              <p>Are you sure you want to completely delete this tour?</p>
              <p><span>Tour Name: </span><span id="span-delete-tour-name">Sample Tour</span></p>
            </div>
            <div class="buttons">
              <button type="submit" class="btn-ok">Delete</button>
              <button type="button" class="btn-cancel">Cancel</button>
            </div>
          </form>
        </div>
      </div>
      


      <input type="hidden" id="site-url" value="<?php echo esc_url(site_url()); ?>">


      <!-- modal export tour -->

      <div class="modal modal-export">

        <div class="dialog-box">
          
          <div class="title">
            <span>Export Tour</span>
            <a href="https://panorom.com/pro" target="_blank" class="pro-feature-text"  <?php echo $is_activated ? 'style="display: none;"' : ''; ?>>PRO</a>
          </div>
          <div class="content">

            <!-- add open info button -->
            <div class="row box-info-message">
              <div class="box-info-button">
                <span>Info </span>
                <span class="icon-down"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg></span>
              </div>
              <ul>
                <li>Select a tour to export.</li>
                <li>Select and download tour resources.</li>
                <li>Save the downloaded files in a folder, e.g. panorom.</li>
                <li>Make sure downloaded files have the same name as the resources.</li>
              </ul>
            </div>
            
            <div class="row select-tour">
              <select id="select-export-tour">
                <option value="">Select a tour</option>
                <?php
                  $item_number = 0;
                  // foreach($posts as $post) {
                  //   $item_number++;
                  //   if($is_activated || $item_number <= self::get_max_allowed_tours()) {
                  //     $output = "<option value='" . $post->meta_config . "' >";
                  //     $output .= esc_html($post->post_title);
                  //     $output .= "</option>";
                  //     echo $output;
                  //   }
                  // }
                  foreach($posts as $post) {
                    $item_number++;
                    if($is_activated || $item_number <= self::get_max_allowed_tours()) {
                      $output = "<option value='" . esc_attr($post->ID) . "' >";
                      $output .= esc_html($post->post_title);
                      $output .= "</option>";
                      echo $output;
                    }
                  }
                ?>
              </select>
              <span class="loading-msg"><img src="<?php echo esc_url(PNRM_DIR_URL . 'public/img/loading.gif'); ?>" alt="loading"></span>
              <span class="error-msg">Error in getting tour data.</span>
            </div>

            <div class="row">
              <div class="inner-flex">
                <label>Resources</label>
                <div class="item-info">
                  <span class="dashicons dashicons-info-outline info-icon"></span>
                  <div class="triangle"></div>
                  <p class="info-text">Resources need to be uploaded separately to target domain.</p>
                </div>
              </div>

              <div class="div-resources-title">
                <div class="each-row">
                  <span></span>
                  <span>Resource URL</span>
                </div>
              </div>
              <div class="div-resources">
                <!-- <div class="each-row" data-selected="no">
                  <span><input type="checkbox"></span>
                  <span></span>
                </div>
                <div class="each-row" data-selected="no">
                  <span><input type="checkbox"></span>
                  <span></span>
                </div> -->
              </div>
              <div class="div-select-all">
                <div class="each-row">
                  <span><input type="checkbox"></span>
                  <span>Select All</span>
                </div>
              </div>

            </div>

            <div class="row command">
              <span>Download Selected Files</span>
              <button class="btn-ok" id="btn-download-resources">Download</button>
            </div>

            <div class="row command">
              <label for="input-export-tour-name">Exported Tour Name</label>
              <input type="text" id="input-export-tour-name">
            </div>

          </div>

          <div class="buttons">
            <button type="submit" class="btn-ok" id="btn-submit-export" <?php echo $is_activated ? '' : 'disabled'; ?>>Export Tour</button>
            <button type="button" class="btn-cancel">Cancel</button>
          </div>
          
        </div>
      </div>


      <!-- modal import tour -->

      <?php
        // get post names
        $item_number = 0;
        $tour_name_array = array();
        foreach($posts as $post) {
          $item_number++;
          if($is_activated || $item_number <= self::get_max_allowed_tours()) {
            array_push($tour_name_array, $post->post_title);
          }
        }
        echo "<input type='hidden' id='input-import-tour-names' value='" . esc_attr(json_encode($tour_name_array)) . "' >";
      ?>
      
      <div class="modal modal-import">
        
        <div class="dialog-box">
          
          <div class="title">
            <span>Import Tour</span>
            <a href="https://panorom.com/pro" target="_blank" class="pro-feature-text"  <?php echo $is_activated ? 'style="display: none;"' : ''; ?>>PRO</a>
          </div>
          <div class="content">

            <!-- add open info button -->
            <div class="row box-info-message">
              <div class="box-info-button">
                <span>Info </span>
                <span class="icon-down"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg></span>
              </div>
              <ul>
                <li>Make a folder (e.g. panorom) in your WordPress-> wp-contents -> uploads.</li>
                <li>Upload your resources to that folder using ftp or direct web upload.</li>
                <li>Note: If you don't have access to your domain directory, upload resources using WordPress -> Media menu</li>
                <li>Choose your panorom tour file.</li>
                <li>Apply domain and path changes.</li>
                <li>Import the tour</li>
              </ul>
            </div>
            
            <div class="row box-file-input">
              <label>Panorom Tour File</label>
              <input type="file" id="input-import-file" accept=".txt">
            </div>

            <div class="row">
              <div class="inner-flex">
                <label>Resources</label>
                <div class="item-info">
                  <span class="dashicons dashicons-info-outline info-icon"></span>
                  <div class="triangle"></div>
                  <p class="info-text">Change the domain and path for uploaded resources. 360-images need to be uploaded to target domain.</p>
                </div>
              </div>

              <div class="div-resources-title">
                <div class="each-row">
                  <span></span>
                  <span>Domain</span>
                  <span>Path</span>
                  <span>File Name</span>
                </div>
              </div>
              <div class="div-resources">
                <!-- 
                  <div class="each-row" data-resource-url="" data-selected="false">
                    <span><input type="checkbox"></span>
                    <span>domain</span>
                    <span>path</span>
                    <span>filename</span>
                  </div>
                -->
              </div>
              <div class="div-select-all">
                <div class="each-row">
                  <span><input type="checkbox"></span>
                  <span>Select All</span>
                  <span></span>
                  <span></span>
                </div>
              </div>

            </div>

            <div class="row flex-change">
              <label for="input-change-selected-domain">Change Selected Domain To</label>
              <input type="text" id="input-change-selected-domain" value="">
              <button class="btn-ok" id="btn-apply-domain-change">Apply</button>
            </div>

            <div class="row flex-change">
              <label for="input-change-selected-path">Change Selected Path To</label>
              <input type="text" id="input-change-selected-path" value="">
              <button class="btn-ok" id="btn-apply-path-change">Apply</button>
            </div>

            <div class="row flex-change">
              <label for="input-change-tour-name">Change Tour Name To</label>
              <input type="text" id="input-change-tour-name" value="">
              <button class="btn-ok" style="visibility: hidden;">Apply</button>
            </div>

            <div class="row flex-overwrite-tour">
              <span>Tour name already exists. To overwrite the existing tour name, toggle the confirm switch.</span>
              <label class="toggle-switch">
                <input type="checkbox" id="checkbox-overwrite-tour">
                <span class="slider round"></span>
              </label>
              <span>Confirm Overwrite</span>
            </div>

          </div>

          <div class="buttons">
            <button type="submit" class="btn-ok" id="btn-submit-import" <?php echo $is_activated ? '' : 'disabled'; ?>>Import Tour</button>
            <button type="button" class="btn-cancel">Cancel</button>
          </div>
          
        </div>
      </div>

      
    </div>
    <?php
  }
}