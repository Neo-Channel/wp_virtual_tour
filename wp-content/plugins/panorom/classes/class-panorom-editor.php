<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Panorom_Editor {




  public static function handle_page() {
    $is_activated = Panorom_Api::is_activated();
    ?>
    <div class="pnrm-editor">

    <!-- <h1>Editor</h1> -->

    <input type="hidden" id="input-is-activated" value="<?php echo esc_attr($is_activated ? 'true' : 'false' ); ?>">

    <div class="topbar">
      <div>
        <label for="select-tour">Select Tour: </label>
        <select id="select-tour">
        <?php
          $tours = Panorom_Tour::get_tours();
          $item_number = 1;
          foreach($tours as $tour) {
            if( $is_activated || $item_number <= Panorom_Tour::get_max_allowed_tours() ) {
              echo '<option value="' . esc_attr($tour->ID) . '">' . esc_html($tour->post_title) . '</option>';
            }
            $item_number++;
          }
        ?>
          <!-- <option value="18">default</option>
          <option value="36">something2</option>
          <option value="37">something3 long text</option> -->
        </select>
      </div>
      <div class="shortcode">
        <label for="input-shortcode">Shortcode: </label>
        <input type="text" value='[panorom id=""]' dir="ltr" id="input-shortcode" disabled>
        <span class="box-copy">
          <div class="div-copied">Copied!</div>
          <span class="dashicons dashicons-admin-page icon" id="copy-shortcode" title="copy"></span>
        </span>
      </div>
      <div></div>
      <!-- <div class="box-size">
        <span class="dashicons dashicons-desktop icon" id="size-desktop" title="full screen"></span>
        <span class="dashicons dashicons-laptop icon selected" id="size-laptop" title="medium screen"></span>
        <span class="dashicons dashicons-smartphone icon" id="size-mobile" title="mobile screen"></span>
      </div> -->
    </div>


    <div class="banner">

      <!-- add image box -->
      <div class="box-add-image">
        <div class="table-wrapper">
          <button class="btn-add-image">Add Image</button>
          <br>
          <span class="btn-insert-url">Or Insert URL</span>
        </div>
      </div>

      <input type="hidden" id="first-scene-text-translation" value="first scene">
      <input type="hidden" id="img-change-text-translation" value="change">
      <input type="hidden" id="span-edit-text-translation" value="edit">

      <!-- main image container -->
      <div class="img-container-box">
        <div class="img-container">

          <!--           
            <div class="each-image" data-img-id="1">
              <div class="img-content" style="background-image: url('filename-300x150.jpeg');"></div>
              <table class="img-ui">
                <tr>
                  <td class="td-id"><span class="img-id">1</span></td>
                  <td class="td-middle"><span class="first-scene-text show">first scene</span></td>
                  <td class="td-delete"><span class="dashicons dashicons-no-alt icon" title="remove image"></span></td>
                </tr>
              </table>
              <p class="img-filename">sample.jpeg</p>
              <div class="div-img-change">
                <button class="btn-img-change">change</button>
                <span class="span-edit-change">edit</span>
              </div>
            </div> 
          -->


        </div>
      </div>

      <!-- save button -->
      <div id="div-pnrm-nonce">
        <?php wp_nonce_field( PNRM_NONCE_ACTION , PNRM_NONCE_NAME ); ?>
      </div>
      <div class="box-save">
        <div class="table-wrapper">
          <button class="btn-save">Save</button>
        </div>
      </div>

    </div>

    <div class="box-main">

      <div class="flex-main-interface">
        <div class="box-main-interface" dir="ltr">
          <div class="inside-ui" data-insert="on" style="display: none;">
            <a href="https://panorom.com" target="_blank" class="custom-logo"><img src="<?php echo esc_url(PNRM_DIR_URL . 'public/img/plugin_logo.png'); ?>" alt="logo"></a>
            <!-- <a href='https://panorom.com' target='_blank' class="custom-logo">
              <svg width="32" height="32" version="1.1" viewBox="0 0 67.733 67.733" xmlns="http://www.w3.org/2000/svg">
                <g transform="translate(7.9532 -25.492)">
                <g transform="matrix(.60606 0 0 1.0401 -37.598 -25.166)" fill="#d45500" stroke-width=".44433">
                  <path d="m134.08 82.792s23.67 6.4899 8.6517 13.402c-2.4402 1.1208-13.29 4.6598-29.674 5.3538v3.4698c9.6231-0.543 24.768-2.1469 33.348-6.8694 18.255-10.05-12.324-15.357-12.324-15.357z"/>
                  <path d="m106.07 102.4-10.949-6.8714c-1.4257-0.50439-2.5958-0.18575-2.5958 0.70649v5.064c-14.079-1.0474-23.348-4.0824-25.575-5.1085-15.022-6.9122 8.6517-13.402 8.6517-13.402s-30.581 5.3069-12.326 15.357c7.4438 4.0974 19.819 5.8457 29.251 6.5921v5.0596c0 0.89504 1.1702 1.2105 2.5958 0.70787l10.949-6.2715c1.4053-0.80541 1.397-1.0118 0-1.8332z"/>
                </g>
                <g fill="#fff" aria-label="p">
                  <path d="m20.14 68.254q-0.84835 0-1.3668-0.51844-0.4713-0.4713-0.4713-1.3197v-22.246q0.04713-3.6762 1.7438-6.5983 1.7438-2.9692 4.6659-4.6659 2.9692-1.6967 6.6454-1.6967 3.7704 0 6.7397 1.7438 2.9692 1.6967 4.6659 4.6659 1.7438 2.9692 1.7438 6.7397 0 3.7233-1.6967 6.6925-1.6496 2.9692-4.5245 4.713-2.875 1.6967-6.504 1.6967-3.1577 0-5.7499-1.3197-2.545-1.3668-4.1004-3.5819v13.856q0 0.84835-0.51844 1.3197-0.4713 0.51844-1.2725 0.51844zm11.217-14.092q2.7336 0 4.9016-1.2725 2.168-1.2725 3.3934-3.4877 1.2725-2.2623 1.2725-5.043 0-2.8278-1.2725-5.043-1.2254-2.2151-3.3934-3.4877-2.168-1.3197-4.9016-1.3197-2.6864 0-4.8544 1.3197-2.168 1.2725-3.3934 3.4877-1.2254 2.2151-1.2254 5.043 0 2.7807 1.2254 5.043 1.2254 2.2151 3.3934 3.4877 2.168 1.2725 4.8544 1.2725z" fill="#fff" stroke-width=".26458"/>
                </g>
                </g>
              </svg>
            </a> -->
            <div class="pnrm-handle-move"></div>
          </div>
          <div class="main-interface" id="pnrm-main-interface" style="height: 500px;"></div>

          <div class="info-overlay">
            <div class="box-content">
              <p class="info-title"><a href="#">Some Title</a></p>
              <img src="#" alt="info image" class="info-image">
            </div>
            <span class="close-icon" title="close"><img src="<?php echo esc_url(PNRM_DIR_URL . 'public/img/x.svg'); ?>" alt="close icon"></span>
          </div>

        </div>
      </div>

      <div class="flex-default-options">

        <div class="default-options">

          <!-- ----------  new options  ------------ -->

          <!-- main title -->
          <div class="main-title">
            <span class="fa-solid fa-gear icon-settings"></span>
            <span>Settings</span>
          </div>

          <!-- option height -->
          <div class="box-option open-on-start">

            <div class="box-title">
              <span class="fa-solid fa-ruler-combined"></span>
              <span>Height</span>
              <span class="fa-solid fa-caret-down icon-caret"></span>
            </div>

            <div class="box-content">
              <div class="each-content">
                
                <div class="title-row">
                  <span class="label-title">Responsive:</span>
                  <div class="item-info">
                    <span class="dashicons dashicons-info-outline info-icon"></span>
                    <div class="triangle"></div>
                    <p class="info-text">Adjust height for different screen sizes to achieve similar field of view.</p>
                  </div>
                </div>

                <div class="row row-height">
                  <label for="input-height-small">Mobile</label>
                  <input type="number" id="input-height-small" class="push-to-right">
                  <span class="dashicons dashicons-smartphone icon" id="size-mobile" title="small screen"></span>
                </div>
  
                <div class="row row-height">
                  <label for="input-height-medium">Laptop</label>
                  <input type="number" id="input-height-medium" class="push-to-right">
                  <span class="dashicons dashicons-laptop icon selected" id="size-laptop" title="medium screen"></span>
                </div>
  
                <div class="row row-height">
                  <label for="input-height-large">Desktop</label>
                  <input type="number" id="input-height-large" class="push-to-right">
                  <span class="dashicons dashicons-desktop icon" id="size-desktop" title="large screen"></span>
                </div>

              </div>
              <hr>
              <div class="each-content">
                <div class="title-row">
                  <span>Fullscreen:</span>
                  <div class="item-info">
                    <span class="dashicons dashicons-info-outline info-icon"></span>
                    <div class="triangle"></div>
                    <p class="info-text">
                      <span>1. Result is only shown on output page, not in editor.</span><br>
                      <span>2. Use one tour shortcode on an empty page</span>
                    </p>
                  </div>
                </div>
                <div class="row">
                  <span class="label-title">Enable</span>
                  <label class="toggle-switch">
                    <input type="checkbox" id="checkbox-fullscreen">
                    <span class="slider round"></span>
                  </label>
                </div>
              </div>
            </div>
          </div>

          <!-- option thumbnail bar -->
          <div class="box-option option-thumbnail-bar">

            <div class="box-title">
              <span class="fa-solid fa-pager"></span>
              <span>Thumbnail Bar</span>
              <span class="fa-solid fa-caret-down icon-caret"></span>
            </div>

            <div class="box-content">
              <div class="each-content">
                <div class="row">
                  <span class="label-title">Enable</span>
                  <label class="toggle-switch">
                    <input type="checkbox" id="checkbox-thumbnail-bar">
                    <span class="slider round"></span>
                  </label>
                </div>
              </div>
            </div>

          </div>

          <!-- option font -->
          <div class="box-option option-font">

            <div class="box-title">
              <span class="fa-solid fa-font"></span>
              <span>Font</span>
              <span class="fa-solid fa-caret-down icon-caret"></span>
            </div>

            <div class="box-content">
              <!-- font size -->
              <div class="each-content">
                <div class="title-row">
                  <span>Font Size:</span>
                  <div class="item-info">
                    <span class="dashicons dashicons-info-outline info-icon"></span>
                    <div class="triangle"></div>
                    <p class="info-text">Auto: inherits from page, Fixed: font size in pixels.</p>
                  </div>
                </div>
                <div class="row">
                  <input type="radio" name="fontsize-type" id="radio-fontsize-auto" checked>
                  <label for="radio-fontsize-auto">Auto</label>
                </div>
                <div class="row">
                  <input type="radio" name="fontsize-type" id="radio-fontsize-fixed">
                  <label for="radio-fontsize-fixed">Fixed <small>(px)</small></label>
                  <input type="number" id="input-fontsize" min="1" value="14" class="push-to-right">
                </div>
              </div>
              <hr>
              <!-- font family -->
              <div class="each-content">
                <div class="title-row">
                  <span>Font Type:</span>
                  <div class="item-info">
                    <span class="dashicons dashicons-info-outline info-icon"></span>
                    <div class="triangle"></div>
                    <p class="info-text">Default: sans-serif, Same as Page: inherits from page.</p>
                  </div>
                </div>
                <div class="row">
                  <input type="radio" name="fontfamily" id="radio-fontfamily-default" checked>
                  <label for="radio-fontfamily-default" class="label-title">Default</label>
                </div>
                <div class="row">
                  <input type="radio" name="fontfamily" id="radio-fontfamily-inherit">
                   <label for="radio-fontfamily-inherit" class="label-title">Same as Page</label>
                </div>
              </div>

            </div>

          </div>

          <!-- option zoom -->
          <div class="box-option">

            <div class="box-title">
              <span class="fa-solid fa-magnifying-glass"></span>
              <span>Zoom</span>
              <span class="fa-solid fa-caret-down icon-caret"></span>
            </div>

            <div class="box-content">
              <div class="each-content">
                <div class="row">
                  <span class="label-title">Zoom-level</span>
                  <input type="range" id="input-zoomlevel" min="70" max="110" step="5" value="100">
                </div>
              </div>
              <hr>
              <div class="each-content">
                <div class="row">
                  <span class="label-title">Mouse-zoom</span>
                  <label class="toggle-switch">
                    <input type="checkbox" id="checkbox-mousezoom">
                    <span class="slider round"></span>
                  </label>
                </div>
              </div>
            </div>

          </div>

          <!-- option auto-rotate -->
          <div class="box-option option-rotate">

            <div class="box-title">
              <span class="fa-solid fa-rotate-left"></span>
              <span>Auto Rotate</span>
              <span class="fa-solid fa-caret-down icon-caret"></span>
            </div>

            <div class="box-content">
              <div class="each-content">
                <div class="title-row">
                  <span>Auto Rotate:</span>
                  <div class="item-info">
                    <span class="dashicons dashicons-info-outline info-icon"></span>
                    <div class="triangle"></div>
                    <p class="info-text">Speed: positive values counter-clockwise, negative values clockwise.</p>
                  </div>
                </div>
                <div class="row">
                  <label for="input-autorotate-speed" class="label-title">Speed <small>(deg/s)</small></label>
                  <input type="number" id="input-autorotate-speed" value="0" class="push-to-right">
                </div>
                <div class="row">
                  <label for="input-autorotate-stop-after" class="label-title">Stop after <small>(s)</small></label>
                  <input type="number" id="input-autorotate-stop-after" min="0" value="0" class="push-to-right">
                </div>
                <div class="row">
                  <span>Only First Scene</span>
                  <label class="toggle-switch" style="margin: 0 auto;">
                    <input type="checkbox" id="checkbox-autorotate-only-first-scene">
                    <span class="slider round"></span>
                  </label>
                </div>
              </div>
            </div>

          </div>

          <!-- option smooth-transition -->
          <div class="box-option option-transition">

            <div class="box-title">
              <span class="fa-solid fa-angles-right"></span>
              <span>Transition</span>
              <span class="fa-solid fa-caret-down icon-caret"></span>
            </div>

            <div class="box-content">
              <div class="each-content">
                <div class="title-row">
                  <span>Smooth Transition:</span>
                  <div class="item-info">
                    <span class="dashicons dashicons-info-outline info-icon"></span>
                    <div class="triangle"></div>
                    <p class="info-text">Values higher than zero, enables smooth transition between scense.</p>
                  </div>
                </div>
                <div class="row">
                  <label for="input-smoothtransition-duration" class="label-title">Duration <small>(s)</small></label>
                  <input type="number" id="input-smoothtransition-duration" value="0" class="push-to-right">
                </div>
              </div>
            </div>

          </div>

          <!-- option autoload -->
          <div class="box-option option-load">

            <div class="box-title">
              <span class="fa-solid fa-play"></span>
              <span>Auto Load</span>
              <span class="fa-solid fa-caret-down icon-caret"></span>
            </div>

            <div class="box-content">
              <div class="each-content">
                <div class="row">
                  <span class="label-title">Enable</span>
                  <label class="toggle-switch">
                    <input type="checkbox" id="checkbox-autoload">
                    <span class="slider round"></span>
                  </label>
                </div>
              </div>
            </div>

          </div>


          <!-- option compass -->
          <div class="box-option">

            <div class="box-title">
              <span class="fa-solid fa-compass"></span>
              <span>Compass</span>
              <span class="fa-solid fa-caret-down icon-caret"></span>
            </div>

            <div class="box-content">
              <div class="each-content">
                <div class="row">
                  <span class="label-title">Enable</span>
                  <label class="toggle-switch">
                    <input type="checkbox" id="checkbox-compass">
                    <span class="slider round"></span>
                  </label>
                </div>
              </div>
            </div>

          </div>


          <!-- option icons -->
          <div class="box-option">

            <div class="box-title">
              <span class="fa-solid fa-icons"></span>
              <span>Icons</span>
              <span class="fa-solid fa-caret-down icon-caret"></span>
            </div>

            <div class="box-content">
              <div class="each-content">
                <div class="row">
                  <div class="box-buttons">
                    <button class="btn-customize-icon">Icon Customizer</button>
                  </div>
                </div>
              </div>
            </div>

          </div>


          <!-- option logo -->
          <div class="box-option">

            <div class="box-title">
              <span class="fa-solid fa-peace"></span>
              <span>Logo</span>
              <span class="fa-solid fa-caret-down icon-caret"></span>
            </div>

            <div class="box-content">
              <div class="each-content">
                <div class="row">
                  <div class="box-buttons">
                    <button class="btn-add-logo">Add Logo</button>
                  </div>
                </div>
              </div>
            </div>

          </div>



          <!-- option preview image -->
          <div class="box-option box-preview">

            <div class="box-title">
              <span class="fa-solid fa-tree"></span>
              <span>Placeholder</span>
              <span class="fa-solid fa-caret-down icon-caret"></span>
            </div>

            <div class="box-content">
              <!-- <div class="each-content"> -->
                <!-- <div class="row"> -->
                  <div class="box-cover">
                    <div class="preview-small-img"></div>
                    <button class="btn-img-change">change</button>
                  </div>
                <!-- </div> -->
              <!-- </div> -->
            </div>

          </div>

          <!-- option audio -->
          <div class="box-option">

            <div class="box-title">
              <span class="fa-solid fa-volume-high"></span>
              <span>Audio</span>
              <span class="fa-solid fa-caret-down icon-caret"></span>
            </div>

            <div class="box-content">
              <div class="each-content">
                <div class="row">
                  <span class="label-title">Enable</span>
                  <label class="toggle-switch">
                    <input type="checkbox" id="checkbox-audio">
                    <span class="slider round"></span>
                  </label>
                </div>
              </div>
              <div id="box-audio-options">
                <div class="each-content">
                  <div class="row">
                    <span class="label-title"><button id="btn-audio-select-file">Select File</button></span>
                  </div>
                  <div class="row">
                    <!-- <span class="audio-file-name">file_name_file_name_file_name.mp3</span> -->
                    <span class="audio-file-name">No File Selected.</span>
                  </div>
                </div>
                <div class="each-content">
                  <div class="title-row">
                    <span>Play Condition:</span>
                    <div class="item-info">
                      <span class="dashicons dashicons-info-outline info-icon"></span>
                      <div class="triangle"></div>
                      <p class="info-text">Result is only shown on output page, not in editor.</p>
                    </div>
                  </div>
                  <div class="row">
                    <input type="radio" name="audiostart" id="radio-audiostart-interaction" checked>
                    <label for="radio-audiostart-interaction">After Any User Interaction</label>
                  </div>
                  <div class="row">
                    <input type="radio" name="audiostart" id="radio-audiostart-click">
                    <label for="radio-audiostart-click">After Icon Click</label>
                  </div>
                </div>
              </div>
            </div>

          </div>


        </div>
      </div>
    </div>


    <!-- Modals -->

    <div class="modal-background"></div>

    <!-- modal insert url -->

    <div class="modal-insert-url">
      <div class="title-bar">Insert URL</div>
      <div class="content">

        <form action="">
          <div class="row">
            <div class="box-url">
              <label for="input-insert-url">URL <small>(current domain)</small></label>
              <input type="text" id="input-insert-url">
            </div>
            <div class="box-buttons">
              <button type="submit" class="btn-ok">Ok</button>
              <button type="button" class="btn-cancel">Cancel</button>
            </div>
            <div>
              <div class="item-info">
                <span class="dashicons dashicons-info-outline info-icon"></span>
                <div class="triangle"></div>
                <p class="info-text">Image needs to be on the same domain.</p>
              </div>
            </div>
          </div>
        </form>

      </div>
    </div>

    <!-- modal max reached -->

    <div class="modal-max-reached modal-max-hotspot">
      <div class="dialog-box">
        <div class="title">Max Hotspots Reached</div>
        <div class="content">
          <p>To add more hotspots, please use PRO version.</p>
        </div>
        <div class="buttons">
          <a href="https://panorom.com/pro?source=plugin" target="_blank" class="btn-ok">Go PRO</a>
          <button type="button" class="btn-cancel">Cancel</button>
        </div>
      </div>
    </div>

    <div class="modal-max-reached modal-max-infospot">
      <div class="dialog-box">
        <div class="title">Max Infospots Reached</div>
        <div class="content">
          <p>To add more infospots, please use PRO version.</p>
        </div>
        <div class="buttons">
          <a href="https://panorom.com/pro?source=plugin" target="_blank" class="btn-ok">Go PRO</a>
          <button type="button" class="btn-cancel">Cancel</button>
        </div>
      </div>
    </div>

    <!-- modal edit url -->

    <div class="modal-edit-url" data-img-id="0">
      <div class="title-bar">Edit URL</div>
      <div class="content">

        <form action="">
          <div class="row">
            <div class="box-url">
              <label for="input-edit-url">URL <small>(current domain)</small></label>
              <input type="text" id="input-edit-url">
            </div>
            <div class="box-buttons">
              <button type="submit" class="btn-ok">Ok</button>
              <button type="button" class="btn-cancel">Cancel</button>
            </div>
            <div>
              <div class="item-info">
                <span class="dashicons dashicons-info-outline info-icon"></span>
                <div class="triangle"></div>
                <p class="info-text">Image needs to be on the same domain.</p>
              </div>
            </div>
          </div>
        </form>

      </div>
    </div>

    <!-- modal preview image -->

    <div class="modal-preview-image">
      <div class="title-bar">
        <span class="loading-title">Placeholder Image</span>
        <!-- <span class="dashicons dashicons-no-alt close-icon" title="close"></span> -->
        <!-- <a href="https://panorom.com/pro" target="_blank" class="pro-feature-text"  <?php echo $is_activated ? 'style="display: none;"' : ''; ?>>PRO</a> -->
      </div>
      <div class="content">

        <div class="row">
          <div><button class="btn-select-image">Select Image</button></div>
          <div><input type="text" value="" id="input-preview-image"></div>
          <div>
            <div class="item-info">
              <span class="dashicons dashicons-info-outline info-icon"></span>
              <div class="triangle"></div>
              <p class="info-text">Use small-size image.</p>
            </div>
          </div>
        </div>

        <!-- <div class="row">
          <div><label for="input-preview-title">Scene Title</label></div>
          <div><input type="text" value="" id="input-preview-title"></div>
          <div>
            <div class="item-info">
              <span class="dashicons dashicons-info-outline info-icon"></span>
              <div class="triangle"></div>
              <p class="info-text">Scene title to be displayed on this scene.</p>
            </div>
          </div>
        </div> -->
        
        <div class="box-preview-result">
          <div class="preview-img"></div>
          <div class="panorama-info">
            <div class="title-box"></div>
          </div>
        </div>

        <div class="box-buttons">
          <!-- <button class="btn-ok" <?php echo $is_activated ? '' : 'disabled'; ?>>Apply</button> -->
          <button class="btn-ok">Apply</button>
          <button class="btn-cancel">Cancel</button>
        </div>


      </div>

      <div class="bottom-spacer-for-modal"></div>

    </div>

    <!-- modal custom logo -->

    <div class="modal-custom-logo">
      <div class="title-bar">
        <span class="loading-title">Custom Logo</span>
        <!-- <span class="dashicons dashicons-no-alt close-icon" title="close"></span> -->
        <!-- <a href="https://panorom.com/pro" target="_blank" class="pro-feature-text"  <?php echo $is_activated ? 'style="display: none;"' : ''; ?>>PRO</a> -->
      </div>
      <div class="content">

        <!-- <div class="row choose-switch">
          <div>Hide Logo</div>
          <div>
            <label class="toggle-switch">
              <input type="checkbox" id="input-toggle-no-logo">
              <span class="slider round"></span>
            </label>
          </div>
          <div class="item-info">
            <span class="dashicons dashicons-info-outline info-icon"></span>
            <div class="triangle"></div>
            <p class="info-text">Checking this box hides logo.</p>
          </div>
        </div> -->

        <div class="row">
          <div><button class="btn-select-image">Select Logo</button></div>
          <div><input type="text" value="" id="input-custom-logo"></div>
          <div>
            <div class="item-info">
              <span class="dashicons dashicons-info-outline info-icon"></span>
              <div class="triangle"></div>
              <p class="info-text">Select your custom logo or insert the URL.</p>
            </div>
          </div>
        </div>

        <div class="row">
          <div><span for="input-logo-width">Logo Width</span></div>
          <div>
            <div class="flex-radio">
              <div>
                <input type="radio" name="radio-width-type" id="radio-logo-width-auto" checked>
                <label for="radio-logo-width-auto" class="label-title">Auto</label>
              </div>
              <div>
                <input type="radio" name="radio-width-type" id="radio-logo-width-fixed">
                <label for="radio-logo-width-fixed" class="label-title">Fixed <small>(px)</small></label>
              </div>
              <input type="number" id="input-logo-width" value="30" disabled>
            </div>
          </div>
          <div>
            <div class="item-info">
              <span class="dashicons dashicons-info-outline info-icon"></span>
              <div class="triangle"></div>
              <p class="info-text">Width of logo in pixels.</p>
            </div>
          </div>
        </div>

        <!-- <input type="hidden" id="input-default-logo-url" value="<?php echo esc_url(PNRM_DIR_URL . 'public/img/plugin_logo.png'); ?>"> -->
        <!-- <input type="hidden" id="input-logo-default-link" value="<?php echo esc_url('https://panorom.com'); ?>"> -->

        <div class="row">
          <div><label for="input-logo-link">Logo Link</label></div>
          <div><input type="text" value="" id="input-logo-link"></div>
          <div>
            <div class="item-info">
              <span class="dashicons dashicons-info-outline info-icon"></span>
              <div class="triangle"></div>
              <p class="info-text">Clicking on logo, takes user to this link.</p>
            </div>
          </div>
        </div>
        

        <div class="box-preview-result">
          <div class="preview-img"></div>
          <a href="" class="custom-logo">
            <img src="<?php echo esc_url(PNRM_DIR_URL . 'public/img/plugin_logo.png'); ?>" alt="logo" style="width: initial;">
          </a>
        </div>

        <div class="box-buttons">
          <!-- <button class="btn-ok" <?php echo $is_activated ? '' : 'disabled'; ?>>Apply</button> -->
          <button class="btn-ok">Apply</button>
          <button class="btn-cancel">Cancel</button>
        </div>


      </div>

      <div class="bottom-spacer-for-modal"></div>

    </div>

    <!-- modal custom icon -->

    <div class="modal-custom-icon">
      <div class="title-bar">
        <span class="loading-title">Customize Icon Appearance</span>
        <!-- <span class="dashicons dashicons-no-alt close-icon" title="close"></span> -->
        <!-- <a href="https://panorom.com/pro" target="_blank" class="pro-feature-text"  <?php echo $is_activated ? 'style="display: none;"' : ''; ?>>PRO</a> -->
      </div>
      <div class="content">

        <div class="row">
          <div><label for="input-icon-color">Icon Color</label></div>
          <div><input type="color" value="#222222" id="input-icon-color"></div>
          <div>
            <div class="item-info">
              <span class="dashicons dashicons-info-outline info-icon"></span>
              <div class="triangle"></div>
              <p class="info-text">Select icon color.</p>
            </div>
          </div>
        </div>

        <div class="row">
          <div><label for="input-icon-size">Icon Size</label></div>
          <div><input type="number" value="15" min="3" max="100" id="input-icon-size"></div>
          <div>
            <div class="item-info">
              <span class="dashicons dashicons-info-outline info-icon"></span>
              <div class="triangle"></div>
              <p class="info-text">Select icon size in pixels.</p>
            </div>
          </div>
        </div>

        <hr>

        <div class="row">
          <div><label for="input-icon-background-color">Background Color</label></div>
          <div><input type="color" value="#ffffff" id="input-icon-background-color"></div>
          <div>
            <div class="item-info">
              <span class="dashicons dashicons-info-outline info-icon"></span>
              <div class="triangle"></div>
              <p class="info-text">Select icon background color.</p>
            </div>
          </div>
        </div>

        <div class="row">
          <div><label for="input-icon-background-opacity">Background Opacity</label></div>
          <div><input type="range" value="0.8" min="0" max="1" step="0.1" id="input-icon-background-opacity"></div>
          <div>
            <div class="item-info">
              <span class="dashicons dashicons-info-outline info-icon"></span>
              <div class="triangle"></div>
              <p class="info-text">Select icon background opacity.</p>
            </div>
          </div>
        </div>

        <div class="row">
          <div><label for="input-icon-background-size">Background Size</label></div>
          <div><input type="number" value="35" min="3" max="100" id="input-icon-background-size"></div>
          <div>
            <div class="item-info">
              <span class="dashicons dashicons-info-outline info-icon"></span>
              <div class="triangle"></div>
              <p class="info-text">Select icon background size in pixels.</p>
            </div>
          </div>
        </div>

        <hr>

        <div class="row">
          <div>Same for Title Box</div>
          <div>
            <label class="toggle-switch">
              <input type="checkbox" id="input-toggle-same-for-tooltip">
              <span class="slider round"></span>
            </label>
          </div>
          <div class="item-info">
            <span class="dashicons dashicons-info-outline info-icon"></span>
            <div class="triangle"></div>
            <p class="info-text">Use same colors for pop-up tooltip billboard.</p>
          </div>
        </div>
        
        <div class="box-preview-result">
          <div class="preview-img"></div>
          <div class="pnrm-pnlm-hotspot-base pnrm-pnlm-pointer pnrm-pnlm-tooltip" style="visibility: visible; top: 45%; left: 27%;">
            <!-- <span class=" pnrm-pnlm-pointer" style="width: 100px; margin-left: -40px; margin-top: -45px;">Restaurant</span> -->
            <i class="fa-solid fa-utensils" style="border-radius: 50%; text-align: center; vertical-align: middle; display: table-cell; color: rgb(34, 34, 34); background-color: rgba(255, 255, 255, 0.8); width: 35px; height: 35px; font-size: 15px;"></i>
          </div>
          <div class="pnrm-pnlm-hotspot-base pnrm-pnlm-tooltip pnrm-pnlm-pointer" style="visibility: visible; top: 45%; left: 67%;">
            <!-- <span class=" pnrm-pnlm-pointer" style="width: 100px; margin-left: -47px; margin-top: -43px;">Coffee Shop</span> -->
            <i class="fa-solid fa-coffee" style="border-radius: 50%; text-align: center; vertical-align: middle; display: table-cell; color: rgb(34, 34, 34); background-color: rgba(255, 255, 255, 0.8); width: 35px; height: 35px; font-size: 15px;"></i>
          </div>
        </div>

        <div class="box-buttons">
          <!-- <button class="btn-ok" <?php echo $is_activated ? '' : 'disabled'; ?>>Apply</button> -->
          <button class="btn-ok">Apply</button>
          <button class="btn-cancel">Cancel</button>
        </div>


      </div>

      <div class="bottom-spacer-for-modal"></div>

    </div>

    <!-- modal scene title -->

    <div class="modal-scene-title">
      <div class="title-bar">
        <span class="loading-title">Scene Title</span>
        <!-- <a href="https://panorom.com/pro" target="_blank" class="pro-feature-text"  <?php echo $is_activated ? 'style="display: none;"' : ''; ?>>PRO</a> -->
      </div>
      <div class="content">

        <div class="row">
          <div><label for="input-scene-title">Scene Title</label></div>
          <div><input type="text" value="" id="input-scene-title"></div>
          <div>
            <div class="item-info">
              <span class="dashicons dashicons-info-outline info-icon"></span>
              <div class="triangle"></div>
              <p class="info-text">Scene title to be displayed on this scene.</p>
            </div>
          </div>
        </div>
        
        <div class="box-buttons">
          <!-- <button class="btn-ok" <?php echo $is_activated ? '' : 'disabled'; ?>>Apply</button> -->
          <button class="btn-ok">Apply</button>
          <button class="btn-cancel">Cancel</button>
        </div>


      </div>
    </div>

    <!-- modal multi height -->

    <!-- <div class="modal-multi-height">
      <div class="title-bar">
        <span class="loading-title">Responsive Height</span>
        <a href="https://panorom.com/pro" target="_blank" class="pro-feature-text"  <?php echo $is_activated ? 'style="display: none;"' : ''; ?>>PRO</a>
      </div>
      <div class="content">

        <div class="row choose-switch">
          <span>
            <label class="toggle-switch">
              <input type="checkbox" id="input-toggle-multi-height" <?php echo $is_activated ? '' : 'disabled'; ?>>
              <span class="slider round"></span>
            </label>
          </span>
          <span>Use below responsive height values (px).</span>
          <div class="item-info">
            <span class="dashicons dashicons-info-outline info-icon"></span>
            <div class="triangle"></div>
            <p class="info-text">screen-sizes are referred to the width of panorom div.</p>
          </div>
        </div>

        <div class="row each-height">
          <label for="input-small-screen-height">Small Screen Height</label>
          <input type="number" id="input-small-screen-height" <?php echo $is_activated ? '' : 'disabled'; ?>>
          <span class="dashicons dashicons-smartphone icon"></span>
        </div>

        <div class="row each-height">
          <label for="input-medium-screen-height">Medium Screen Height</label>
          <input type="number" id="input-medium-screen-height" <?php echo $is_activated ? '' : 'disabled'; ?>>
          <span class="dashicons dashicons-laptop icon"></span>
        </div>

        <div class="row each-height">
          <label for="input-large-screen-height">Large Screen Height</label>
          <input type="number" id="input-large-screen-height" <?php echo $is_activated ? '' : 'disabled'; ?>>
          <span class="dashicons dashicons-desktop icon"></span>
        </div>

        <div class="box-buttons">
          <button class="btn-ok" <?php echo $is_activated ? '' : 'disabled'; ?>>Apply</button>
          <button class="btn-cancel">Cancel</button>
        </div>

      </div>
    </div> -->


    <!-- modal ajax message -->
    <div class="modal-ajax-message">
      <div class="title-bar">
        <span class="loading-title">LOADING</span>
        <span class="saving-title">SAVING</span>
        <span class="success-title">SAVED</span>
        <span class="error-title">ERROR</span>
        <span class="dashicons dashicons-no-alt close-icon" title="close"></span>
      </div>
      <div class="content">
        <span class="loading-msg"><img src="<?php echo esc_url(PNRM_DIR_URL . 'public/img/loading.gif'); ?>" alt="loading" style="width: 32px;"></span>
        <span class="saving-msg"><img src="<?php echo esc_url(PNRM_DIR_URL . 'public/img/loading.gif'); ?>" alt="loading" style="width: 32px;"></span>
        <span class="success-msg">Changes saved successfully.</span>
        <span class="error-msg">Server error occured!</span>
      </div>
    </div>

    <div class="modal-change-tour">
      <div class="title-bar">
        <span>NOT SAVED</span>
        <span class="dashicons dashicons-no-alt close-icon" title="close"></span>
      </div>
      <p class="message">You have unsaved changes. Continue without saving?</p>
      <div class="buttons-box">
        <button class="btn btn-ok">Continue</button>
        <button class="btn btn-cancel">Cancel</button>
      </div>
    </div>

    <div class="modal-remove-image" data-img-id="0">
      <p class="message">Want to remove image?</p>
      <div class="buttons-box">
        <button class="btn btn-ok">Remove</button>
        <button class="btn btn-cancel">Cancel</button>
      </div>
    </div>


    <!-- modal top right click -->

    <div class="modal-top-right-click" data-img-id="0">
      <ul>
        <li data-action="setSceneDefault">
          <span class="text-content">Set Default Direction</span>
          <div class="item-info">
            <span class="dashicons dashicons-info-outline info-icon"></span>
            <div class="triangle"></div>
            <p class="info-text">sets the current scene's direction as default for this scene.</p>
          </div>
        </li>
        <li data-action="addHotspot">
          <span class="text-content">Add Hotspot</span>
          <div class="item-info">
            <span class="dashicons dashicons-info-outline info-icon"></span>
            <div class="triangle"></div>
            <p class="info-text">Hotspots are links through which the user can travel between scenes.</p>
          </div>
        </li>
        <li data-action="addInfospot">
          <span class="text-content">Add Infospot</span>
          <div class="item-info">
            <span class="dashicons dashicons-info-outline info-icon"></span>
            <div class="triangle"></div>
            <p class="info-text">Infospots are spots with extra information about a particular point on the scene.</p>
          </div>
        </li>
        <li data-action="setSceneTitle">
          <span class="text-content">Add Title</span>
          <div class="item-info">
            <span class="dashicons dashicons-info-outline info-icon"></span>
            <div class="triangle"></div>
            <p class="info-text">sets the scene title for the current scene.</p>
          </div>
        </li>

      </ul>
    </div>

    <!-- modal banner right click -->

    <div class="modal-banner-right-click" data-click-pitch="0" data-click-yaw="0">
      <ul>
        <li data-action="setAsFirstScene">
          <span class="text-content">Set as First Scene</span>
          <div class="item-info">
            <span class="dashicons dashicons-info-outline info-icon"></span>
            <div class="triangle"></div>
            <p class="info-text">sets this scene as the first scene of the tour.</p>
          </div>
        </li>
        <li data-action="changeImage">
          <span class="text-content">Change Image</span>
          <div class="item-info">
            <span class="dashicons dashicons-info-outline info-icon"></span>
            <div class="triangle"></div>
            <p class="info-text">Change the image for this scene.</p>
          </div>
        </li>
        <li data-action="editUrl">
          <span class="text-content">Edit URL</span>
          <div class="item-info">
            <span class="dashicons dashicons-info-outline info-icon"></span>
            <div class="triangle"></div>
            <p class="info-text">Edit image URL for this scene.</p>
          </div>
        </li>
      </ul>
    </div>

    <!-- modal hotspot right click -->

    <div class="modal-hotspot-right-click">
      <table>
        <tr>
          <td><label for="select-hotspot-to-scene">To Scene ID</label></td>
          <td>
            <select name="to-scene" id="select-hotspot-to-scene">
              <option value="1">1</option>
            </select>
          </td>
          <td>
            <div class="item-info">
              <span class="dashicons dashicons-info-outline info-icon"></span>
              <div class="triangle"></div>
              <p class="info-text">select which scene this hotspot leads to.</p>
            </div>
          </td>
        </tr>
        <tr class="top-bottom-border">
          <td><span>Icon</span></td>
          <td>
            <span class="inner-flex">
              <span class="box-icon">
                <div class="icon-default pnrm-pnlm-hotspot-base pnrm-pnlm-hotspot pnrm-pnlm-sprite pnrm-pnlm-scene"></div>
                <i class="icon-custom fa-solid"></i>
              </span>
              <div class="icon-buttons">
                <button class="btn-reset-icon">Reset</button>
                <button class="btn-change-icon">Change</button>
              </div>
            </span>        
          </td>
          <td>
          </td>
        </tr>

        <tr id="row-target-mode" class="no-bottom-border">
          <td><span class="no-user-select">Target Scene's<br>X & Y</span></td>
          <td>
            <span class="inner-flex" style="flex-direction: column; align-items: flex-start; gap: 3px;">
              <!-- <input type="radio" id="input-hotspot-target-manual" name="radio-hotspot-target" value="manual" checked <?php echo $is_activated ? '' : 'disabled'; ?>>
              <label for="input-hotspot-target-manual">Manual</label>
              <input type="radio" id="input-hotspot-target-auto" name="radio-hotspot-target" value="auto" <?php echo $is_activated ? '' : 'disabled'; ?>>
              <label for="input-hotspot-target-auto">Auto</label>
              <a href="https://panorom.com/pro" target="_blank" class="pro-feature-text"  <?php echo $is_activated ? 'style="display: none;"' : ''; ?>>PRO</a> -->
              <label>
                <input type="radio" id="input-hotspot-target-default" name="radio-hotspot-target">
                <span>Scene's Default</span>
              </label>
              <!-- <label for="input-hotspot-target-default"></label> -->
              <label>
                <input type="radio" id="input-hotspot-target-auto" name="radio-hotspot-target" value="auto">
                <span>Compass data</span>
              </label>
              <!-- <label for="input-hotspot-target-auto">Compass data</label> -->
              <label>
                <input type="radio" id="input-hotspot-target-manual" name="radio-hotspot-target">
                <span>Manual Config</span>
              </label>
              <!-- <label for="input-hotspot-target-manual">Manual Config</label> -->
            </span>
          </td>
          <td>
            <div class="item-info">
              <span class="dashicons dashicons-info-outline info-icon"></span>
              <div class="triangle"></div>
              <p class="info-text">adjust the target scene's direction/angle when arriving from this hotspot.</p>
            </div>
          </td>
        </tr>
        <!-- <tr id="row-target-adjust" class="show no-bottom-border" style="<?php echo $is_activated ? '' : 'display: none;'; ?>">
          <td></td>
          <td>
            <label for="input-hotspot-target-x"> X: </label>
            <input type="text" value="" id="input-hotspot-target-x" disabled>
            <label for="input-hotspot-target-y"> Y: </label>
            <input type="text" value="" id="input-hotspot-target-y" disabled>
            <button class="button-hotspot-target-clear" <?php echo $is_activated ? '' : 'disabled'; ?>>Clear</button>
            <button class="button-hotspot-target-set" <?php echo $is_activated ? '' : 'disabled'; ?>>Set</button>
          </td>
          <td></td>
        </tr> -->
        <tr id="row-target-adjust" class="show">
          <td></td>
          <td style="padding-top: 0;">
            <label for="input-hotspot-target-x" style="display: none;"> X: </label>
            <input type="text" value="" id="input-hotspot-target-x" style="display: none;">
            <label for="input-hotspot-target-y" style="display: none;"> Y: </label>
            <input type="text" value="" id="input-hotspot-target-y" style="display: none;">
            <button class="button-hotspot-target-clear" style="display: none;">Clear</button>
            <button class="button-hotspot-target-set">Configure</button>
          </td>
          <td></td>
        </tr>

        <tr class="no-bottom-border">
          <td><label for="input-hotspot-text">Title Text</label></td>
          <td><input type="text" value="" id="input-hotspot-text"></td>
          <td>
            <div class="item-info">
              <span class="dashicons dashicons-info-outline info-icon"></span>
              <div class="triangle"></div>
              <p class="info-text">type the text to be displayed, when user hovers over this spot.</p>
            </div>
          </td>
        </tr>
        <tr class="no-bottom-border">
          <td><span>Title Width</span></td>
          <td>
            <span class="inner-flex">
              <input type="radio" id="input-hotspot-width-auto" name="radio-hotspot-width" value="auto" checked>
              <label for="input-hotspot-width-auto">Auto</label>
              <br>
              <input type="radio" id="input-hotspot-width-fixed" name="radio-hotspot-width" value="fixed">
              <label for="input-hotspot-width-fixed">Fixed</label>
              <input type="number" id="input-hotspot-width" value="120" disabled>
            </span>
          </td>
          <td>
            <div class="item-info">
              <span class="dashicons dashicons-info-outline info-icon"></span>
              <div class="triangle"></div>
              <p class="info-text">Auto: width is automatically calculated. Fixed: can be manually adjusted.</p>
            </div>
          </td>
        </tr>

        <tr>
          <td><span>Title Appear</span></td>
          <td>
            <span class="inner-flex">
              <input type="radio" id="input-hotspot-show-hover" name="radio-hotspot-show" value="hover" checked>
              <label for="input-hotspot-show-hover">Hover</label>
              <input type="radio" id="input-hotspot-show-always" name="radio-hotspot-show" value="always">
              <label for="input-hotspot-show-always">Always</label>
            </span>
          </td>
          <td>
            <div class="item-info">
              <span class="dashicons dashicons-info-outline info-icon"></span>
              <div class="triangle"></div>
              <p class="info-text">Hover: shows title text on mouse hover. Always: shows title text always.</p>
            </div>
          </td>
        </tr>
        
        <tr class="top-bottom-border">
          <td><span class="no-user-select">Delete</span></td>
          <td>
            <label class="toggle-switch">
              <input type="checkbox" id="input-hotspot-remove">
              <span class="slider round"></span>
            </label>
          </td>
          <td>
            <div class="item-info">
              <span class="dashicons dashicons-info-outline info-icon"></span>
              <div class="triangle"></div>
              <p class="info-text">To remove this spot, check the box and click Ok.</p>
            </div>
          </td>
        </tr>


      </table>
      <div class="buttons-box">
        <button class="btn-ok">Ok</button>
        <button class="btn-cancel">Cancel</button>
      </div>

      <div class="bottom-spacer-for-modal"></div>

    </div>

    <!-- modal infospot right click -->

    <div class="modal-infospot-right-click">

      <div class="row">
        <div><span>Icon</span></div>
        <div class="inner-flex">
          <div class="box-icon">
            <div class="icon-default pnrm-pnlm-hotspot-base pnrm-pnlm-hotspot pnrm-pnlm-sprite pnrm-pnlm-info"></div>
            <i class="icon-custom fa-solid"></i>
          </div>
          <div class="icon-buttons">
            <button class="btn-reset-icon">Reset</button>
            <button class="btn-change-icon">Change</button>
          </div>
        </div>
        <div class="empty"></div>
      </div>

      <hr>
      <div class="row">
        <div><label for="input-infospot-text">Title</label></div>
        <div><input type="text" value="" id="input-infospot-text"></div>
        <div>
          <div class="item-info">
            <span class="dashicons dashicons-info-outline info-icon"></span>
            <div class="triangle"></div>
            <p class="info-text">type the text to be displayed, when user hovers over this spot.</p>
          </div>
        </div>
      </div>

      <hr>
      <div class="row">
        <div><span class="no-user-select">Width</span></div>
        <div>
          <span class="inner-flex">
            <input type="radio" id="input-infospot-width-auto" name="radio-infospot-width" value="auto" checked>
            <label for="input-infospot-width-auto">Auto</label>
            <br>
            <input type="radio" id="input-infospot-width-fixed" name="radio-infospot-width" value="fixed">
            <label for="input-infospot-width-fixed">Fixed</label>
            <input type="number" id="input-infospot-width" value="200" disabled>
          </span>
        </div>
        <div>
          <div class="item-info">
            <span class="dashicons dashicons-info-outline info-icon"></span>
            <div class="triangle"></div>
            <p class="info-text">Auto: width is automatically calculated. Fixed: can be manually adjusted.</p>
          </div>
        </div>
      </div>

      <hr>
      <div class="row">
        <div><label for="input-infospot-image">Image</label></div>
        <div><button id="btn-infospot-image">Select Image</button></div>
        <div>
          <div class="item-info">
            <span class="dashicons dashicons-info-outline info-icon"></span>
            <div class="triangle"></div>
            <p class="info-text">Select or paste the URL of image which you want to be displayed.</p>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="empty"></div>
        <div><input type="text" value="" id="input-infospot-image"></div>
        <div class="empty"></div>
      </div>

      <hr>
      <!-- row video -->
      <div class="row">
        <div><label for="input-infospot-video">Video</label></div>
        <div class="inner-flex">
          <label class="toggle-switch">
            <input type="checkbox" id="input-infospot-allow-video">
            <span class="slider round"></span>
          </label>
        </div>
        <div>
          <div class="item-info">
            <span class="dashicons dashicons-info-outline info-icon"></span>
            <div class="triangle"></div>
            <p class="info-text">Select or paste the URL of video. If both image and video are set, only the video will be displayed.</p>
          </div>
        </div>
      </div>
      <!-- row video 2 -->
      <div class="row video-row-2">
        <div class="empty"></div>
        <div>
          <button id="btn-infospot-video">Select Video</button>
          <span style="margin-left: 1.5rem;">
            <label class="toggle-switch">
              <input type="checkbox" id="input-infospot-autoplay">
              <span class="slider round"></span>
            </label>
            <span>Autoplay</span>
          </span>
          <span style="margin-left: 1.5rem;">
            <label class="toggle-switch">
              <input type="checkbox" id="input-infospot-loop">
              <span class="slider round"></span>
            </label>
            <span>Loop</span>
          </span>
        </div>
        <div class="empty"></div>
      </div>
      <!-- row video 3 -->
      <div class="row video-row-3">
        <div class="empty"></div>
        <div><input type="text" value="" id="input-infospot-video"></div>
        <div class="empty"></div>
      </div>


      <hr>
      <!-- row link -->
      <div class="row">
        <div><label for="input-infospot-link">Link</label></div>
        <div class="inner-flex">
          <label class="toggle-switch">
            <input type="checkbox" id="input-infospot-allow-link">
            <span class="slider round"></span>
          </label>
        </div>
        <div>
          <div class="item-info">
            <span class="dashicons dashicons-info-outline info-icon"></span>
            <div class="triangle"></div>
            <p class="info-text">Paste the external address to which, this spot will be linked.</p>
          </div>
        </div>
      </div>
      <!-- row link 2 -->
      <div class="row div-link">
        <div class="empty"></div>
        <div><input type="text" value="" id="input-infospot-link"></div>
        <div class="empty"></div>
      </div>

      <hr>
      <div class="row">
        <div><span class="no-user-select">Appear</span></div>
        <div>
          <!-- <?php
          if ( $is_activated ) { ?>
            <span class="inner-flex">
              <input type="radio" id="input-infospot-show-hover" name="radio-infospot-show" value="hover" checked>
              <label for="input-infospot-show-hover">Hover</label>
              <input type="radio" id="input-infospot-show-always" name="radio-infospot-show" value="always">
              <label for="input-infospot-show-always">Always</label>
            </span>
          <?php } else { ?>
            <span class="inner-flex">
              <input type="radio" id="input-infospot-show-hover" name="radio-infospot-show" value="hover" checked disabled>
              <label for="input-infospot-show-hover">Hover</label>
              <input type="radio" id="input-infospot-show-always" name="radio-infospot-show" value="always" disabled>
              <label for="input-infospot-show-always">Always</label>
              <a href="https://panorom.com/pro" target="_blank" class="pro-feature-text">PRO</a>
            </span>
          <?php } ?> -->
          <span class="inner-flex">
              <input type="radio" id="input-infospot-show-hover" name="radio-infospot-show" value="hover" checked>
              <label for="input-infospot-show-hover">Hover</label>
              <input type="radio" id="input-infospot-show-always" name="radio-infospot-show" value="always">
              <label for="input-infospot-show-always">Always</label>
            </span>
        </div>
        <div>
          <div class="item-info">
            <span class="dashicons dashicons-info-outline info-icon"></span>
            <div class="triangle"></div>
            <p class="info-text">Hover: shows info image on mouse hover. Always: shows info image always.</p>
          </div>
        </div>
      </div>


      <hr>
      <div class="row">
        <div><span class="no-user-select">Delete<span></div>
        <div class="inner-flex">
          <label class="toggle-switch">
            <input type="checkbox" id="input-infospot-remove">
            <span class="slider round"></span>
          </label>
        </div>
        <div>
          <div class="item-info">
            <span class="dashicons dashicons-info-outline info-icon"></span>
            <div class="triangle"></div>
            <p class="info-text">To remove this spot, check the box and click Ok.</p>
          </div>
        </div>
      </div>

      <hr>
      <div class="buttons-box">
        <button class="btn-ok">Ok</button>
        <button class="btn-cancel">Cancel</button>
      </div>

      <div class="bottom-spacer-for-modal"></div>

    </div>

          
    
    <!-- Aux Interface -->
    <div class="box-aux-interface">
      <div class="box-x-y">
        <label for="input-aux-x"> X: </label>
        <input type="text" value="-88" id="input-aux-x" disabled>
        <label for="input-aux-y"> Y: </label>
        <input type="text" value="12" id="input-aux-y" disabled>
        <!-- <span class="close-icon" title="set and close"><img src="<?php echo esc_url(PNRM_DIR_URL . 'public/img/x.svg'); ?>" alt="close icon"></span> -->
        <span class="close-icon" title="set and close">SET</span>
      </div>
      <div class="aux-interface" id="pnrm-aux-interface"></div>
    </div>

    <!-- modal icon picker -->
    <div class="modal-icon-picker">
      <div class="title-bar">
        <span class="loading-title">Icon Picker</span>
        <!-- <span class="dashicons dashicons-no-alt close-icon" title="close"></span> -->
        <!-- <a href="https://panorom.com/pro" target="_blank" class="pro-feature-text"  <?php echo $is_activated ? 'style="display: none;"' : ''; ?>>PRO</a> -->
      </div>
      <div class="content">

        <div class="row-flex">
          <div><label for="input-search-icon-name">Search</label></div>
          <div class="item-info">
            <span class="dashicons dashicons-info-outline info-icon"></span>
            <div class="triangle"></div>
            <p class="info-text">Search for relevant name of your desired icon.</p>
          </div>
        </div>

        <input type="text" id="input-search-icon-name" placeholder="e.g. arrow up">
        <input type="hidden" id="input-url-for-icon-terms" value="<?php echo esc_url(PNRM_DIR_URL . 'public/js/icon-terms-solid.json'); ?>">

        <div class="box-search-results">
          <div class="wrap-search-results">
            <!-- <div class="icon-box"><span class="each-icon fa-solid fa-coffee"></span></div>
            <div class="icon-box"><span class="each-icon fa-solid fa-coffee"></span></div> -->

          </div>
        </div>

        <div class="box-animation">
          <span>Animation</span>
          <label class="toggle-switch">
            <input type="checkbox" id="input-toggle-icon-animation">
            <span class="slider round"></span>
          </label>
        </div>

        <div class="box-icon-preview">
          <span>Icon Preview</span>
          <span class="icon-box">
            <span class="pnrm-box-animation"></span>
            <i class="icon-preview fa-solid"></i>
          </span>
        </div>

        <div class="box-buttons">
          <!-- <button class="btn-ok" <?php echo $is_activated ? '' : 'disabled'; ?>>Choose</button> -->
          <button class="btn-ok">Choose</button>
          <button class="btn-cancel">Cancel</button>
        </div>


      </div>
    </div>



    </div>
    <?php
  }
}