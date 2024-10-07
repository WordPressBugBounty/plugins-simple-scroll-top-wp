<?php
/**
 * Plugin Name:       Simple Scroll To Top WP
 * Plugin URI:        https://wordpress.org/plugins/simple-scroll-to-top-wp/
 * Description:       Simple Scroll to top plugin will help you to enable Back to Top button to your WordPress website.
 * Version:           2.0.1
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Ali Hossain
 * Author URI:        https://alihossain.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       sstt
 */

 /*
 * Plugin Option Page Function
 */
  function sstt_add_theme_page(){
    add_menu_page( 'Scroll To Top Option for Admin', 'Scroll To Top', 'manage_options', 'sstt-plugin-option', 'sstt_create_page', 'dashicons-arrow-up-alt', 101 );
  }
  add_action( 'admin_menu', 'sstt_add_theme_page' );

  /*
  * Plugin Option Page Style
  */
function sstt_add_theme_css(){
  wp_enqueue_style( 'sstt-admin-style', plugins_url( 'css/sstt-admin-style.css', __FILE__ ), false, "1.0.0");

}
add_action('admin_enqueue_scripts', 'sstt_add_theme_css');


  /**
   * Plugin Callback
   */
  function sstt_create_page(){
    ?>
      <div class="sstt_main_area">
        <div class="sstt_body_area sstt_common">
          <h3 id="title"><?php print esc_attr( '🎨 Simple to Scroll Customizer' ); ?></h3>
          <form action="options.php" method="post">
            <?php wp_nonce_field('update-options'); ?>

            <!-- Primary Color -->
            <label for="sstt-primary-color" name="sstt-primary-color"><?php print esc_attr( 'Primary Color' ); ?></label>
            <small>Add your Primary Color</small>
            <input type="color" name="sstt-primary-color" value="<?php print get_option('sstt-primary-color') ?>">

            <!-- Button Position -->
            <label for="sstt-image-position"><?php echo esc_attr(__('Button Position')); ?></label>
            <small>Where do you want to show your button position?</small>
            <select name="sstt-image-position" id="sstt-image-position">
              <option value="true" <?php if( get_option('sstt-image-position') == 'true'){ echo 'selected="selected"'; } ?>>Left</option>
              <option value="false" <?php if( get_option('sstt-image-position') == 'false'){ echo 'selected="selected"'; } ?>>Right</option>
            </select>
            
            <!-- Round Corner -->
            <label for="sstt-round-corner"><?php echo esc_attr(__('Round Corner')); ?></label>
            <small>Do you need a rounded corner button?</small>
            <label class="radios">
              <input type="radio" name="sstt-round-corner" id="sstt-round-corner-yes" value="true" <?php if(get_option('sstt-round-corner') == 'true') {echo 'checked="checked"';} ?>><span>No</span>
            </label>
            <label class="radios">
              <input type="radio" name="sstt-round-corner" id="sstt-round-corner-no" value="false" <?php if(get_option('sstt-round-corner') == 'false') {echo 'checked="checked"';} ?>><span>Yes</span>
            </label>



            <!-- Round Corner -->


            <input type="hidden" name="action" value="update">
            <input type="hidden" name="page_options" value="sstt-primary-color, sstt-image-position, sstt-round-corner">
            <input type="submit" name="submit" value="<?php _e('Save Changes', 'clpwp') ?>">
          </form>
        </div>
        <div class="sstt_sidebar_area sstt_common">
          <h3 id="title"><?php print esc_attr( '👩‍💻 About Author' ); ?></h3>
          <p><img src="<?php print plugin_dir_url(__FILE__) . '/img/author.png' ?>" alt=""></p>
          <p>I'm <strong><a href="https://alihossain.com/" target="_blank">Ali Hossain</a></strong> a Front End Web developer who is passionate about making error-free websites with 100% client satisfaction. I have a passion for learning and sharing my knowledge with others as publicly as possible. I love to solve real-world problems.</p>
          <p><a href="https://www.buymeacoffee.com/aliHossain" target="_blank"><img src="<?php print plugin_dir_url(__FILE__) . '/img/buyme.png' ?>" alt=""></a></p>
          <h5 id="title"><?php print esc_attr( 'Watch Help Video' ); ?></h5>
          <p><a href="https://youtu.be/8CqTi5iyQJU" target="_blank" class="btn">Watch On YouTube</a></p>
        </div>
      </div>
    <?php
  }





  // Including CSS
  function sstt_enqueue_style(){
    wp_enqueue_style('sstt-style', plugins_url('css/sstt-style.css', __FILE__));
  }
  add_action( "wp_enqueue_scripts", "sstt_enqueue_style" );

  // Including JavaScript
  function sstt_enqueue_scripts(){
    wp_enqueue_script('jquery');
    wp_enqueue_script('sstt-plugin-script', plugins_url('js/sstt-plugin.js', __FILE__), array(), '1.0.0', 'true');
  }
  add_action( "wp_enqueue_scripts", "sstt_enqueue_scripts" );

  // jQuery Plugin Setting Activation
  function sstt_scroll_script(){
    ?>
      <script>
        jQuery(document).ready(function () {
          jQuery.scrollUp();
        });
      </script>
    <?php
}
add_action( "wp_footer", "sstt_scroll_script" );



// Theme CSS Customization
function sstt_theme_color_cus(){
  ?>
  <style>
    #scrollUp {
    background-color: <?php print get_option('sstt-primary-color'); ?> !important;
    <?php if(get_option('sstt-image-position') == 'true') {echo "left: 5px; right: auto"; } else {echo "right: 5px";}?>;

    <?php if(get_option('sstt-round-corner') == 'true') {echo "border-radius: 0 !important";} else {echo "border-radius: 50px !important";}?>;
  }
  </style>
  <?php 
}
add_action('wp_head', 'sstt_theme_color_cus');





  /*
  * Plugin Redirect Feature
  */
  register_activation_hook( __FILE__, 'sstt_plugin_activation' );
  function sstt_plugin_activation(){
    add_option('sstt_plugin_do_activation_redirect', true);
  }

  add_action( 'admin_init', 'sstt_plugin_redirect');
  function sstt_plugin_redirect(){
    if(get_option('sstt_plugin_do_activation_redirect', false)){
      delete_option('sstt_plugin_do_activation_redirect');
      if(!isset($_GET['active-multi'])){
        wp_safe_redirect(admin_url( 'admin.php?page=sstt-plugin-option' ));
        exit;
      }
    }
  }
?>