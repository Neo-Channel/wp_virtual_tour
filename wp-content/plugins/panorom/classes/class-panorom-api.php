<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define("PNRM_OPTION_API_KEY", 'pnrm_api_key');
define("PNRM_OPTION_API_ACTIVATED", 'pnrm_api_activated');
define("PNRM_OPTION_API_DOMAIN", 'pnrm_api_domain');
// define("PNRM_REMOTE_API_URL", 'http://127.0.0.1:3000/api/plugin');
define("PNRM_REMOTE_API_URL", 'https://panorom.com/api/plugin');


class Panorom_Api {

  public static function init_db() {
    add_option(PNRM_OPTION_API_KEY);
    add_option(PNRM_OPTION_API_ACTIVATED);
    add_option(PNRM_OPTION_API_DOMAIN);
  }

  public static function remove_all_db() {
    delete_option(PNRM_OPTION_API_KEY);
    delete_option(PNRM_OPTION_API_ACTIVATED);
    delete_option(PNRM_OPTION_API_DOMAIN);
  }


  public static function is_activated_test() {
    
    // run this function as add_action('init', array...)
    // show $api_activated for no option in database
    // make an update_option and see if it gets added to database
    // true/false should use != or !==
    // extrct domain from site_url()
    // this function does not send http_request only check and sets database
    

    $api_activated = get_option(PNRM_OPTION_API_ACTIVATED);
    $api_domain = get_option(PNRM_OPTION_API_DOMAIN);


    // check if is_activated is true or not

    // instead of the below code we can simply return $api_activated
    $result = false;
    // if ($api_activated !== true) doesn't work comparing to ture/false
    if ($api_activated) {
      $result = 'true';
    }
    else {
      $result = 'false';
    }

    

    // extracting domain name from site_url

    // $url = 'https://www.domain.com';
    $url = site_url();
    $parse = parse_url($url);
    $domain = str_replace('www.', '', $parse['host']);
    


  }

  public static function get_current_domain() {
    $site_url = site_url();
    $parsed_url = parse_url($site_url);
    $site_domain = str_replace('www.', '', $parsed_url['host']);
    return $site_domain;
  }


  public static function is_activated() {

    // if(class_exists('Panorom_Pro_Editor')) {
    //   $get_pro_value = Panorom_Pro_Editor::get_pro_value();
    //   return $get_pro_value;
    // }


    $api_activated = get_option(PNRM_OPTION_API_ACTIVATED);
    $api_domain = get_option(PNRM_OPTION_API_DOMAIN);

    // if not activated or api_activated does not exist in db -> free version
    if (!$api_activated) {
      return false;
    }

    // if activated and site_url domain === api_domain then all ok
    $site_domain = self::get_current_domain();
    if ($api_domain === $site_domain) {
      return true;
    }

    // else remove activated status
    update_option(PNRM_OPTION_API_ACTIVATED, false);
    return false;
  }



  // sends activate http request
  // returns http response for activate request
  private static function send_http_req_activate($api_key, $site_domain) {

    $url = PNRM_REMOTE_API_URL . '/activate-key';
    $args = array(
      'method' => 'POST',
      'headers' => array(
        'Content-Type' => 'application/json',
      ),
      'body' => json_encode(array(
        'key_name' => $api_key,
        'domain' => $site_domain,
      )),
    );
    $response = wp_remote_request( $url, $args );
    return $response;
  
  }

  // checks response of activate request
  // updates api_activated, api_domain
  // returns error text or null
  private static function handle_res_activate($response, $domain) {

    // error in remote http server
    if ( is_wp_error( $response ) ) {
      $error = 'Panorom server error, please try again later.';
      return $error;
    }

    $resObj = json_decode($response['body']);

    // check if response was proper json
    if(json_last_error() !== JSON_ERROR_NONE) {
      $error = 'Server response was not proper json.';
      return $error;
    }

    // check if response contained expected params
    if( !isset($resObj->success) || !isset($resObj->status) ) {
      $error = 'Response does not contain expected parameters';
      return $error;
    }

    // could not be activated (either key_name does not exist or already assigned to another domain)
    if(!$resObj->success) {
      // update_option(PNRM_OPTION_API_ACTIVATED, false);
      if ($resObj->status === 400) {
        $error = 'Provided API key or domain name is wrong.';
        return $error;
      }
      if ($resObj->status === 404) {
        $error = 'API key was not found.';
        return $error;
      }
      if ($resObj->status === 409) {
        $error = 'The requested API key is already in use by another domain.';
        return $error;
      }
      $error = 'API Key could not be activated.';
      return $error;
    }

    // response was success true
    // update api_activated and api_domain in db
    update_option(PNRM_OPTION_API_ACTIVATED, true);
    update_option(PNRM_OPTION_API_DOMAIN, $domain);

    return null;
  }


  // sends release http request
  // returns http response
  private static function send_http_req_release($api_key, $site_domain) {

    $url = PNRM_REMOTE_API_URL . '/release-key';
    $args = array(
      'method' => 'POST',
      'headers' => array(
        'Content-Type' => 'application/json',
      ),
      'body' => json_encode(array(
        'key_name' => $api_key,
        'domain' => $site_domain,
      )),
    );
    $response = wp_remote_request( $url, $args );
    return $response;
  
  }

  // checks the returned response for activate request
  // updates api_activated, api_domain
  // returns error text or null
  private static function handle_res_release($response, $domain) {

    // error in remote http server
    if ( is_wp_error( $response ) ) {
      $error = 'Panorom server error, please try again later.';
      return $error;
    }

    $resObj = json_decode($response['body']);

    // check if response was proper json
    if(json_last_error() !== JSON_ERROR_NONE) {
      $error = 'Server response was not proper json.';
      return $error;
    }

    // check if response contained expected params
    if(!isset($resObj->status) || !isset($resObj->success)) {
      $error = 'Response does not contain expected parameters';
      return $error;
    }

    // could not be released (either key_name does not exist or the provided domain name does not match the registered domain on server)
    if(!$resObj->success) {
      if ($resObj->status === 400) {
        $error = 'Provided API key or domain name is wrong.';
        return $error;
      }
      if ($resObj->status === 404) {
        $error = 'API key was not found.';
        return $error;
      }
      if ($resObj->status === 409) {
        $error = 'The requested domain name does not match the activated domain name.';
        return $error;
      }
      $error = 'API Key could not be released.';
      return $error;
    }

    // response success was true
    // update api_activated and api_domain in db
    update_option(PNRM_OPTION_API_ACTIVATED, false);
    update_option(PNRM_OPTION_API_DOMAIN, '');

    return null;
  }




  public static function handle_page() {
    $flash_msg = '';
    $flash_class = '';


    // handle flash messages for GET request
    if ( !self::is_activated() ) {
      $flash_msg = 'You are currently using free version of Panorom plugin.';
      $flash_class = PNRM_FLASH_CLASS_WARNING;
    }
    else {
      $flash_msg = 'Congratulations! your PRO plan is activated.';
      $flash_class = PNRM_FLASH_CLASS_SUCCESS;
    }


    // handle POST request: updates api_key
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      
      $post_api_key = isset($_POST['api_key']) ? sanitize_text_field( $_POST['api_key'] ) : '';
      $post_site_domain = isset($_POST['site_domain']) ? sanitize_text_field( $_POST['site_domain'] ) : '';
      $post_checkbox_release = isset($_POST['checkbox_release']) ? sanitize_text_field( $_POST['checkbox_release'] ) : 'off';
      // $nonce = isset($_POST[PNRM_NONCE_NAME]) ? wp_kses( $_POST[PNRM_NONCE_NAME], array() ) : '';

      // check if nonce is incorrect (user is not using the frontend)
      if ( ! check_admin_referer( PNRM_NONCE_ACTION, PNRM_NONCE_NAME ) ) {
        return;
      }


      if ($post_checkbox_release === 'off') {
        // activating API key
        // echo '<h2 style="color: red; position: absolute; z-index: 100000; top: 180px; left: 50%;">' . 'activating API key' . '</h2>';
        
        $response = self::send_http_req_activate($post_api_key, $post_site_domain);

        $error = self::handle_res_activate($response, $post_site_domain);

        if($error !== null) {
          // update error flash mgs
          $flash_msg = $error;
          $flash_class = PNRM_FLASH_CLASS_ERROR;
        }
        else {
          // update success flash mgs
          $flash_msg = 'Congratulations! your API Key is activated.';
          $flash_class = PNRM_FLASH_CLASS_SUCCESS;
          // save api key in db
          update_option(PNRM_OPTION_API_KEY, $post_api_key);
        }
        
      }
      else {
        // releasing API key
        // echo '<h2 style="color: red; position: absolute; z-index: 100000; top: 180px; left: 50%;">' . 'releasing API key' . '</h2>';

        $response = self::send_http_req_release($post_api_key, $post_site_domain);

        $error = self::handle_res_release($response, $post_site_domain);

        if($error !== null) {
          // update error flash mgs
          $flash_msg = $error;
          $flash_class = PNRM_FLASH_CLASS_ERROR;
        }
        else {
          // update success flash mgs
          $flash_msg = 'Your API Key is released from this domain.';
          if ($post_site_domain === 'localhost' || $post_site_domain === '127.0.0.1') {
            $flash_msg = 'Your API Key is released from local development.';
          }
          $flash_class = PNRM_FLASH_CLASS_SUCCESS;
          // update db
          update_option(PNRM_OPTION_API_KEY, '');
        }

      }


    }

    // get most recent api key
    $api_key = get_option(PNRM_OPTION_API_KEY);
    $site_domain = self::get_current_domain();


    ?>
    <div class="pnrm-api">

      <h1>API Keys</h1>
      <div class="top-flex">

        <form method="POST" action="" class="box-api-key">
          <?php wp_nonce_field( PNRM_NONCE_ACTION , PNRM_NONCE_NAME ); ?>
          <table class="table-api-key">
            <caption>
              <div class="<?php echo esc_attr($flash_class); ?>">
                <p><?php echo esc_html($flash_msg); ?></p>
                <?php echo (self::is_activated() ? '' : '<a href="https://panorom.com/pro?source=plugin" target="_blank" class="btn-upgrade">Upgrade to PRO Version</a>'); ?>
              </div>
            </caption>
            <tr>
              <td>
                <label for="input-api-key">API Key</label>
              </td>
              <td class="td-input">
                <input type="text" id="input-api-key" name="api_key" value="<?php echo ($_SERVER['REQUEST_METHOD'] === 'POST' ? esc_attr($post_api_key) : esc_attr($api_key)); ?>" required>
              </td>
            </tr>
            <tr>
              <td>
                <label for="input-site-domain">Domain</label>
              </td>
              <td class="td-input">
                <input type="text" id="input-site-domain" name="site_domain" value="<?php echo esc_attr($site_domain); ?>" readonly>
              </td>
            </tr>
            <tr> 
              <td></td>
              <td class="td-submit">
                <input type="hidden" id="text-translation-activate" value="Activate" disabled>
                <input type="hidden" id="text-translation-release" value="Release" disabled>
                <?php submit_button('Activate', 'primary', 'submit', false); ?>
              </td>
            </tr>
  
            <tr style="border-top: 1px solid #aaa;">
              <td class="td-show-release">Release Key</td>
              <td></td>
            </tr>
            <tr class="tr-release-row">
              <td>
                <input type="checkbox" name="checkbox_release" id="input-checkbox-release">
              </td>
              <td>To release the API key from this domain, check the box and click on Save button. (You don't need to release the key from localhost)</td>
            </tr>
            <!-- <tr style="visibility: hidden;">
              <td class="td-show-release">Release Key</td>
              <td></td>
            </tr>
            <tr class="tr-release-row" style="visibility: hidden;">
              <td>
                <input type="checkbox" name="checkbox_release" id="input-checkbox-release">
              </td>
              <td>To release the API key from this domain, check the box and click on Save button. (You don't need to release the key from localhost)</td>
            </tr> -->
  
  
  
          </table>
        </form>


        <!-- <form class="box-review-request">
          <h2>Tour Review</h2>
          <p>We can review your panorom tour, and provide you with the feedback on how to improve it.</p>
          <p><strong>Note: your panorom tour needs to be on the current live domain.</strong></p>
          <table class="table-review-request">
            <caption>
              <div class="<?php echo esc_attr(PNRM_FLASH_CLASS_SUCCESS); ?>">
                <p><?php echo esc_html('Your review request has been sent successfully.'); ?></p>
              </div>
            </caption>
            <tr>
              <td>
                <label for="input-user-email">Your Email</label>
              </td>
              <td class="td-input">
                <input type="email" id="input-user-email" required>
              </td>
            </tr>
            <tr>
              <td>
                <label for="input-review-domain">Domain</label>
              </td>
              <td class="td-input">
                <input type="text" id="input-review-domain" value="<?php echo esc_attr($site_domain); ?>" readonly>
              </td>
            </tr>
            <tr> 
              <td></td>
              <td class="td-submit">
                <?php submit_button('Please review my tour', 'primary', 'send-review-request', false); ?>
              </td>
            </tr>
          </table>
        </form> -->

      </div>
      

    </div>
    <?php
  }
}