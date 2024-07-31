<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Panorom_Info {




  public static function handle_page() {
    ?>
    <div class="pnrm-info">

      <div class="content">
        <a href="https://panorom.com?source=plugin" target="_blank"><img src="<?php echo esc_url(PNRM_DIR_URL . 'public/img/plugin_logo_full_black.png'); ?>" alt="panorom logo" class="logo"></a>
        <p class="title">Intuitive 360° Virtual Tour Builder and Viewer</p>
        <button class="btn-start">Start Creating</button>
      </div>

      <ul class="extra">
        <li>
          <div>
            <h2>Have questions?</h2>
            <p>You can ask your questions or give suggestions on <a href="https://wordpress.org/support/plugin/panorom/" target="_blank">forum page</a></p>
          </div>
        </li>
        <li>
          <div>
            <h2>Enjoying Panorom?</h2>
            <p>Consider rating us on <a href="https://wordpress.org/plugins/panorom/#reviews" target="_blank">review page</a></p>
          </div>
        </li>
      </ul>
      


    </div>
    <?php
  }
}
