<?php
/*
Plugin Name: Video Background
Plugin URI: https://pushlabs.co/documentation/video-background
Description: WordPress plugin to easily assign a video background to any element. Awesome.
Author: Push Labs
Version: 2.6.3
Author URI: https://pushlabs.co
Text Domain: video-background
Domain Path: /languages
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
  exit;
}

// Define some constants
define( 'VIDBG_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'VIDBG_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'VIDBG_PLUGIN_BASE', plugin_basename(__FILE__) );
define( 'VIDBG_PLUGIN_VERSION', '2.6.3' );

/**
 * Install the plugin
 * deactivate vidbgpro if installed
 *
 * @since 2.5.2
 *
 * @uses is_plugin_active()
 * @uses deactivate_plugins()
 * @uses delete_option()
 */
function vidbg_install_plugin() {
  if( is_plugin_active( 'video-background-pro/vidbgpro.php') ) {
    deactivate_plugins( 'video-background-pro/vidbgpro.php' );
  }
  delete_option( 'vidbgpro-notice-dismissed' );
  delete_option( 'vidbg_disable_pro_fields' );
}
register_activation_hook( __FILE__, 'vidbg_install_plugin' );

/**
 * Create function to see if WordPress version is 4.2 or higher
 *
 * @since 2.5.1
 */
function vidbg_is_wp_version( $version = '4.2' ) {
  global $wp_version;

  if ( version_compare( $wp_version, $version, '>=' ) ) {
    return true;
  } else {
    return false;
  }
}

/**
 * Include the metabox framework
 */
if ( file_exists( dirname( __FILE__ ) . '/inc/vendor/cmb2/init.php' ) ) {
  require_once dirname( __FILE__ ) . '/inc/vendor/cmb2/init.php';
}
if ( file_exists( dirname( __FILE__ ) . '/inc/classes/cmb2_field_slider.php' ) ) {
  require_once dirname( __FILE__ ) . '/inc/classes/cmb2_field_slider.php';
}
if ( file_exists( dirname( __FILE__ ) . '/admin_premium_notice.php' ) ) {
  require_once dirname( __FILE__ ) . '/admin_premium_notice.php';
}

/**
 * Load plugin textdomain.
 *
 * @since 2.5.0
 *
 * @uses load_plugin_textdomain()
 * @uses plugin_basename()
 */
function vidbg_load_textdomain() {
  load_plugin_textdomain( 'video-background', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'vidbg_load_textdomain' );

/**
 * Enqueue backend style and script
 * Note: renamed. Previous, vidbg_metabox_scripts()
 *
 * @since 2.1.4
 *
 * @uses wp_enqueue_style()
 * @uses plugins_url()
 * @uses wp_enqueue_script()
 * @uses plugin_dir_url()
 */
function vidbg_enqueue_admin_scripts() {
  wp_enqueue_style('vidbg-metabox-style', plugins_url('/css/vidbg-style.css', __FILE__));
  wp_enqueue_script( 'vidbg-admin-backend', plugin_dir_url( __FILE__ ) . '/js/vidbg-backend.js' );

  wp_localize_script( 'vidbg-admin-backend', 'vidbg_localized_text', array(
    'show_advanced' => __( 'Show Advanced Options', 'video-background' ),
    'hide_advanced' => __( 'Hide Advanced Options', 'video-background' ),
  ) );
}
add_action('admin_enqueue_scripts', 'vidbg_enqueue_admin_scripts');

/**
 * Enqueue vidbg jquery script
 * Note: renamed. Previous, vidbg_jquery
 *
 * @since 2.0.0
 *
 * @uses wp_enqueue_script()
 * @uses plugins_url()
 */
function vidbg_enqueue_scripts() {
  wp_register_script( 'vidbg-video-background', plugins_url('/js/vidbg.min.js', __FILE__), array('jquery'), VIDBG_PLUGIN_VERSION, true);
  wp_register_style( 'vidbg-frontend-style', plugins_url( '/css/pushlabs-vidbg.css', __FILE__), array(), VIDBG_PLUGIN_VERSION );

  // Enqueue the style
  wp_enqueue_style( 'vidbg-frontend-style' );
}
add_action( 'wp_enqueue_scripts', 'vidbg_enqueue_scripts' );

/**
 * Add custom color palette
 *
 * @since 2.5.0
 */
function vidbg_default_color_palette( $l10n ) {
  $l10n['defaults']['color_picker'] = array(
    'palettes' => array( '#000000', '#3498db', '#e74c3c', '#374e64', '#2ecc71', '#f1c40f' ),
  );
  return $l10n;
}
add_filter( 'cmb2_localized_data', 'vidbg_default_color_palette' );

/**
 * Construct the vidbg shortcode from an array
 *
 * @since 2.6
 *
 * @uses do_shortcode()
 *
 * @param $atts_array array A 2d array of shortcode attributes
 * @return string The [vidbg] constructed from the array input
 */
function vidbg_construct_shortcode( $atts_array ) {
  // If no array is provided, quit
  if ( empty( $atts_array ) ) {
    return;
  }

  // Our shortcode name
  $shortcode_name = 'vidbg';

  // Construct the shortcode
  $the_shortcode = '[' . $shortcode_name;
  foreach ( $atts_array as $key => $value ) {
    $the_shortcode .= ' ' . $key . '="' . $value .'"';
  }
  $the_shortcode .= ']';

  // Create the output
  $output = $the_shortcode;

  return $output;
}

/**
 * Helper function to output disabled Video Background Pro fields
 *
 * @since 2.5.4
 *
 * @uses get_option()
 */
function vidbg_disabled_pro_field( $field_name = 'Blank Pro', $field_id = 'pro_blank', $field_type = 'input', $field_description = '' ) {

  $output = '';
  $options = get_option( 'vidbg_disable_pro_fields' );

  if ( ! $options ) {
    if ( $field_type === 'input' ) {
      $field_class = 'cmb-row cmb-type-text cmb2-id-pro-disabled-field-' . $field_id . ' table-layout';
    } elseif ( $field_type === 'radio' ) {
      $field_class = 'cmb-row cmb-type-radio-inline cmb2-id-pro-disabled-field-' . $field_id . ' cmb-inline';
    }

    $output .= '<div class="' . $field_class . '">';
    $output .= '<div class="cmb-th"><label for="pro_disabled_' . $field_id . '">' . $field_name . '</label></div>';
    $output .= '<div class="cmb-td">';

    if ( $field_type === 'input' ) {
      $output .= '<input type="text" class="regular-text" name="pro_disabled_' . $field_id . '" id="' . $field_id . '" disabled>';
    }

    if ( $field_type === 'radio' ) {
      $output .= '<ul class="cmb2-radio-list cmb2-list">';
      $output .= '<li><input type="radio" value="off" class="cmb2-option" name="pro_disabled_' . $field_id . '" id="pro_disabled_' . $field_id . '1" checked="checked" disabled> <label for="pro_disabled_' . $field_id . '1">Off</label></li>';
      $output .= '<li><input type="radio" class="cmb2-option" name="pro_disabled_' . $field_id . '" id="pro_disabled_' . $field_id . '2" value="on" disabled> <label for="pro_disabled_' . $field_id . '2">On</label></li>';
      $output .= '</ul>';
    }

    if ( $field_id === 'overlay_texture' ) {
      $output .= '<input class="cmb2-upload-button button" type="button" value="Upload Overlay Texture" disabled="">';
    }

    $output .= '<p class="cmb2-metabox-description">' . $field_description . '</p>';
    $output .= '</div>';
    $output .= '</div>';
  }

  return $output;
}

/**
 * Register metabox and scripts
 *
 * @since 2.5.0
 *
 * @uses new_cmb2_box()
 * @uses __()
 * @uses add_field()
 * @uses vidbg_disabled_pro_field()
 */
function vidbg_register_metabox() {
  $prefix = 'vidbg_metabox_field_';
  $post_types = array( 'post', 'page' );

  /**
   * Allow the post types to be filtered out
   */
  $post_types = apply_filters( 'vidbg_post_types', $post_types );

  $vidbg_metabox = new_cmb2_box( array(
    'id'           => 'vidbg-metabox',
    'title'        => __( 'Video Background', 'video-background' ),
    'object_types' => $post_types,
    'context'      => 'normal',
    'priority'     => 'high',
  ) );

  $vidbg_metabox->add_field( array(
    'name' => __( 'Container', 'video-background' ),
    'desc' => __( 'Please specify the container you would like your video background to be in.<br>ex: <code>.header</code> or <code>body</code>', 'video-background' ),
    'id'   => $prefix . 'container',
    'type' => 'text',
    'after_row' => vidbg_disabled_pro_field(
      __( 'YouTube Link', 'video-background' ),
      'youtube_link',
      'input',
      __( 'To create YouTube video backgrounds, <a href="http://pushlabs.co/video-background-pro" rel="nofollow" target="_blank">please download the pro version!</a>', 'video-background' )
    ) . vidbg_disabled_pro_field(
      __( 'YouTube Start Second', 'video-background' ),
      'youtube_start',
      'input',
      __( 'To use the YouTube Start Second feature, <a href="http://pushlabs.co/video-background-pro" rel="nofollow" target="_blank">please download the pro version!</a>', 'video-background' )
    ) . vidbg_disabled_pro_field(
      __( 'YouTube End Second', 'video-background' ),
      'youtube_end',
      'input',
      __( 'To use the YouTube End Second feature, <a href="http://pushlabs.co/video-background-pro" rel="nofollow" target="_blank">please download the pro version!</a>', 'video-background' )
    ),
  ) );

  $vidbg_metabox->add_field( array(
    'name'    => __( 'Link to .mp4', 'video-background' ),
    'desc'    => __( 'Please specify the link to the .mp4 file. You can either enter a URL or upload a file.<br>For browser compatability, please enter an .mp4 and .webm file for video backgrounds.', 'video-background' ),
    'id'      => $prefix . 'mp4',
    'type'    => 'file',
    'options' => array(
      'add_upload_file_text' => __( 'Upload .mp4 file', 'video-background' ),
    ),
  ) );

  $vidbg_metabox->add_field( array(
    'name'    => __( 'Link to .webm', 'video-background' ),
    'desc'    => __( 'Please specify the link to the .webm file. You can either enter a URL or upload a file.<br>For browser compatability, please enter an .mp4 and .webm file for video backgrounds.', 'video-background' ),
    'id'      => $prefix . 'webm',
    'type'    => 'file',
    'options' => array(
      'add_upload_file_text' => __( 'Upload .webm file', 'video-background' ),
    ),
  ) );

  $vidbg_metabox->add_field( array(
    'name'    => __( 'Link to fallback image', 'video-background' ),
    'desc'    => __( 'Please specify a link to the fallback image in case the browser does not support video backgrounds. You can either enter a URL or upload a file.', 'video-background' ),
    'id'      => $prefix . 'poster',
    'type'    => 'file',
    'options' => array(
      'add_upload_file_text' => __( 'Upload fallback image', 'video-background' ),
    ),
  ) );

  $vidbg_metabox->add_field( array(
    'name'       => __( 'Overlay', 'video-background' ),
    'desc'       => __( 'Add an overlay over the video. This is useful if your text isn\'t readable with a video background.', 'video-background' ),
    'id'         => $prefix . 'overlay',
    'type'       => 'radio_inline',
    'default'    => 'off',
    'options' => array(
      'off' => __( 'Off', 'video-background' ),
      'on'  => __( 'On', 'video-background' ),
    ),
    'before_row' => '<div id="vidbg_advanced_options">',
  ) );

  $vidbg_metabox->add_field( array(
    'name'    => __( 'Overlay Color', 'video-background' ),
    'desc'    => __( 'If overlay is enabled, a color will be used for the overlay. You can specify the color here.', 'video-background' ),
    'id'      => $prefix . 'overlay_color',
    'type'    => 'colorpicker',
    'default' => '#000',
  ) );

  $vidbg_metabox->add_field( array(
    'name'    => __( 'Overlay Opacity', 'video-background' ),
    'desc'    => __( 'Specify the opacity of the overlay with the left being mostly transparent and the right being hardly transparent.', 'video-background' ),
    'id'      => $prefix . 'overlay_alpha',
    'type'    => 'vidbg_slider',
    'min'     => '10',
    'max'     => '99',
    'default' => '30',
    'after_row' => vidbg_disabled_pro_field(
      __( 'Overlay Texture', 'video-background' ),
      'overlay_texture',
      'input',
      __( 'To add overlay textures to your video background, <a href="http://pushlabs.co/video-background-pro" rel="nofollow" target="_blank">please download the pro version!</a>', 'video-background' )
    ),
  ) );

  $vidbg_metabox->add_field( array(
    'name'    => __( 'Turn off loop?', 'video-background' ),
    'desc'    => __( 'Turn off the loop for Video Background. Once the video is complete, it will display the last frame of the video.', 'video-background' ),
    'id'      => $prefix . 'no_loop',
    'type'    => 'radio_inline',
    'default' => 'off',
    'options' => array(
      'off' => __( 'Off', 'video-background' ),
      'on'  => __( 'On', 'video-background' ),
    ),
    'after_row' => vidbg_disabled_pro_field(
      __( 'End video on fallback image?', 'video-background' ),
      'end_fallback',
      'radio',
      __( 'To enable the end video on fallback image feature, <a href="http://pushlabs.co/video-background-pro" rel="nofollow" target="_blank">please download the pro version!</a>', 'video-background' )
    ) . vidbg_disabled_pro_field(
      __( 'Enable CSS loader?', 'video-background' ),
      'enable_loader',
      'radio',
      __( 'To enable the CSS loader feature, <a href="http://pushlabs.co/video-background-pro" rel="nofollow" target="_blank">please download the pro version!</a>', 'video-background' )
    ),
  ) );

  $vidbg_metabox->add_field( array(
    'name'      => __( 'Play the audio?', 'video-background' ),
    'desc'      => __( 'Enabling this will play the audio of the video.', 'video-background' ),
    'id'        => $prefix . 'unmute',
    'type'      => 'radio_inline',
    'default'   => 'off',
    'options'   => array(
      'off' => __( 'Off', 'video-background' ),
      'on'  => __( 'On', 'video-background' ),
    ),
    'after_row' => vidbg_disabled_pro_field(
      __( 'Enable Play/Pause button', 'video-background' ),
      'play_button',
      'radio',
      __( 'To enable a play/pause button on the frontend, <a href="http://pushlabs.co/video-background-pro" rel="nofollow" target="_blank">please download the pro version!</a>', 'video-background' )
    ) . vidbg_disabled_pro_field(
      __( 'Enable Mute/Unmute button', 'video-background' ),
      'volume_button',
      'radio',
      __( 'To enable a mute/unmute button on the frontend, <a href="http://pushlabs.co/video-background-pro" rel="nofollow" target="_blank">please download the pro version!</a>', 'video-background' )
    ) . '</div>',
  ) );

  $vidbg_metabox->add_field( array(
    'before_field' => __( '<a href="#vidbg_advanced_options" class="button vidbg-button advanced-options-button">Show Advanced options</a>', 'video-background' ),
    'type'         => 'title',
    'id'           => $prefix . 'advanced_button',
  ) );

}
add_action( 'cmb2_admin_init', 'vidbg_register_metabox' );

/**
 * Add inline javascript to footer for video background
 *
 * @since 2.0.0
 *
 * @uses is_page()
 * @uses is_single()
 * @uses is_home()
 * @uses get_option()
 * @uses get_the_ID()
 * @uses get_post_meta()
 */
function vidbg_initialize_footer() {
  if( is_page() || is_single() || is_home() && get_option( 'show_on_front') == 'page' ) {

    if( is_page() || is_single() ) {
      $the_id = get_the_ID();
    } elseif( is_home() && get_option( 'show_on_front' ) == 'page' ) {
      $the_id = get_option( 'page_for_posts' );
    }

    $meta_prefix = 'vidbg_metabox_field_';
    $container_meta = get_post_meta( $the_id, $meta_prefix . 'container', true );
    $mp4_meta = get_post_meta( $the_id, $meta_prefix . 'mp4', true );
    $webm_meta = get_post_meta( $the_id, $meta_prefix . 'webm', true );
    $poster_meta = get_post_meta( $the_id, $meta_prefix . 'poster', true );
    $overlay_meta = get_post_meta( $the_id, $meta_prefix . 'overlay', true );
    $overlay_color_meta = get_post_meta( $the_id, $meta_prefix . 'overlay_color', true );
    $overlay_alpha_meta = get_post_meta( $the_id, $meta_prefix . 'overlay_alpha', true );
    $loop_meta = get_post_meta( $the_id, $meta_prefix . 'no_loop', true );
    $mute_meta = get_post_meta( $the_id, $meta_prefix . 'unmute', true );

    // If there is no container element, return.
    if( empty( $container_meta ) ) {
      return;
    }

    // Create our shortcode attributes array
    $shortcode_atts = array(
      'container' => $container_meta,
      'mp4' => ( $mp4_meta == '' ) ? '#' : $mp4_meta,
      'webm' => ( $webm_meta == '' ) ? '#' : $webm_meta,
      'poster' => ( $poster_meta == '' ) ? '#' : $poster_meta,
      'overlay' => ( $overlay_meta == 'on' ) ? 'true' : 'false',
      'overlay_color' => !empty( $overlay_color_meta ) ? $overlay_color_meta : '#000',
      'overlay_alpha' => !empty( $overlay_alpha_meta ) ? '0.' . $overlay_alpha_meta : '0.3',
      'loop' => ( $loop_meta == 'on' ) ? 'false' : 'true',
      'muted' => ( $mute_meta == 'on' ) ? 'false' : 'true',
      'source' => 'Metabox',
    );

    // Construct the shortcode, then echo it.
    $the_shortcode = vidbg_construct_shortcode( $shortcode_atts );
    $output = do_shortcode( $the_shortcode );

    echo $output;
  }
}
add_action( 'wp_footer', 'vidbg_initialize_footer' );

/**
 * Shortcode for v1.0.x versions
 *
 * @since 1.0.0
 *
 * @uses shortcode_atts()
 * @uses do_shortcode()
 */
function candide_video_background( $atts , $content = null ) {

    // Attributes
    extract(
      shortcode_atts(
        array(
          'container' => 'body',
          'mp4' => '#',
          'webm' => '#',
          'poster' => '#',
          'muted' => 'true',
          'loop' => 'true',
          'overlay' => 'false',
          'overlay_color' => '#000',
          'overlay_alpha' => '0.3',
          'source' => 'Shortcode',
        ), $atts , 'vidbg'
      )
    );

    // Enqueue the vidbg script conditionally
    wp_enqueue_script( 'vidbg-video-background' );

    $output = "<script>
      jQuery(function($){
        // Source: " . $source . "
        $( '" . $container . "' ).vidbg( {
          mp4: '" . $mp4 . "',
          webm: '" . $webm . "',
          poster: '" . $poster . "',
          mute: " . $muted . ",
          repeat: " . $loop . ",
          overlay: " . $overlay . ",
          overlayColor: '" . $overlay_color . "',
          overlayAlpha: '" . $overlay_alpha . "',
        });
      });
    </script>";

    return $output;
}
add_shortcode( 'vidbg', 'candide_video_background' );

/**
 * Add getting started page
 *
 * @since 2.1.1
 *
 * @uses add_options_page()
 */
function vidbg_add_gettingstarted() {
  add_options_page(
    'Video Background',
    'Video Background',
    'manage_options',
    'html5-vidbg',
    'vidbg_gettingstarted_page'
  );
}
add_action( 'admin_menu', 'vidbg_add_gettingstarted' );

/**
 * Creates Video Background plugin settings
 *
 * @since 2.5.4
 *
 * @uses register_setting()
 * @uses add_settings_section()
 * @uses add_settings_field()
 */
function vidbg_register_settings() {
  register_setting( 'vidbg_settings', 'vidbg_disable_pro_fields' );

  add_settings_section(
    'vidbg_vidbg_settings_section',
    __( 'Hide Muted Pro Fields', 'video-background' ),
    'vidbg_disable_pro_fields_section_callback',
    'vidbg_settings'
  );

  add_settings_field(
    'vidbg_checkbox_disable_pro_field',
    __( 'Hide Muted Pro Fields', 'video-background' ),
    'vidbg_checkbox_disable_pro_field_render',
    'vidbg_settings',
    'vidbg_vidbg_settings_section'
  );
}
add_action( 'admin_init', 'vidbg_register_settings' );

/**
 * Creats the checkbox callback for Video Background options
 *
 * @since 2.5.4
 *
 * @uses get_option()
 * @uses checked()
 */
function vidbg_checkbox_disable_pro_field_render() {
  $options = get_option( 'vidbg_disable_pro_fields' );

  $output = '';
  $check = '';
  if ( $options ) {
    $check = checked( $options['vidbg_checkbox_disable_pro_field'], 1, false );
  }

  $output .= '<input type="checkbox" name="vidbg_disable_pro_fields[vidbg_checkbox_disable_pro_field]" ' . $check . ' value="1">';

  echo $output;
}

/**
 * Video Background plugin settings section callback
 *
 * @since 2.5.4
 *
 * @uses _e()
 */
function vidbg_disable_pro_fields_section_callback() {
  _e( 'Okay, Okay, some of you don\'t want/need Video Background Pro. I get that. That\'s why you can hide the muted pro fields below :)', 'video-background' );
}

/**
 * Getting started page content
 *
 * @since 2.1.1
 *
 * @uses _e()
 * @uses settings_fields()
 * @uses do_settings_sections()
 * @uses submit_button()
 */
function vidbg_gettingstarted_page() {
  echo '<div class="wrap">';
    _e( '<h2>Video Background</h2>', 'video-background' );
    _e( '<p>Video background makes it easy to add responsive, great looking video backgrounds to any element on your website.</p>', 'video-background' );
    _e( '<h3>Getting Started</h3>', 'video-background' );
    _e( '<p>To implement Video Background on your website, please follow the instructions below.', 'video-background' );
    echo '<ol>';
      _e( '<li>Edit the page or post you would like the video background to appear on.</li>', 'video-background' );
      _e( '<li>Below the content editor, you should see a metabox titled <b>Video Background</b>. Enter the values for the required fields and publish/update the page.</li>', 'video-background' );
      _e( '<li>Enjoy.</li>', 'video-background' );
    echo '</ol>';
    _e( '<p>Alternatively, you can use the shortcode by placing the following code at the bottom of the content editor of the page or post you would like the video background to appear on. Here is how it works:</p>', 'video-background' );
    echo '<p><code>[vidbg container=&quot;body&quot; mp4=&quot;#&quot; webm=&quot;#&quot; poster=&quot;#&quot; loop=&quot;true&quot; overlay=&quot;false&quot; overlay_color=&quot;#000&quot; overlay_alpha=&quot;0.3&quot; muted=&quot;false&quot;]</code></p>';
    _e( '<a href="https://pushlabs.co/docs/video-background/" class="button" target="_blank">Further Documentation</a>', 'video-background' );
    _e( '<h3>Questions?</h3>', 'video-background' );
    _e( '<p>If you have any feedback/questions regarding the plugin you can reach me <a href="https://wordpress.org/support/plugin/video-background" target="_blank">here.</a>', 'video-background' );
    _e( '<h3>Supporting the Plugin</h3>', 'video-background' );
    _e( '<p>If you like Video Background and want to show your support, consider purchasing the <a href="http://pushlabs.co/video-background-pro" rel="nofollow" target="_blank">pro version</a>. It comes with plenty of helpful features that make your life easier like:</p>', 'video-background' );
    echo '<ul>';
      _e( '<li>Mobile video background playback on supported browsers</li>', 'video-background' );
      _e( '<li>YouTube Integration</li>', 'video-background' );
      _e( '<li>Visual Composer Integration</li>', 'video-background' );
      _e( '<li>Page Builder by SiteOrigin Integration</li>', 'video-background' );
      _e( '<li>Frontend Play/Pause Button Option</li>', 'video-background' );
      _e( '<li>Frontend Volume Button Option</li>', 'video-background' );
      _e( '<li>Overlay Image Textures</li>', 'video-background' );
      _e( '<li>Extensive Documentation</li>', 'video-background' );
      _e( '<li>Video Tutorials</li>', 'video-background' );
      _e( '<li>And Much More!</li>', 'video-background' );
    echo '</ul>';
    _e( '<a href="http://pushlabs.co/video-background-pro" class="button button-primary" rel="nofollow" target="_blank">Learn More About Video Background Pro</a>', 'video-background' );
    echo '<form action="options.php" method="post">';
      settings_fields( 'vidbg_settings' );
      do_settings_sections( 'vidbg_settings' );
      submit_button();
    echo '</form>';
  echo '</div>';
}

/**
 * Add getting started link on plugin page
 *
 * @since 2.1.1
 *
 * @uses __()
 */
function vidbg_gettingstarted_link($links) {
  $gettingstarted_link = __( '<a href="options-general.php?page=html5-vidbg">Getting Started</a>', 'video-background' );
  array_unshift($links, $gettingstarted_link);
  return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'vidbg_gettingstarted_link' );
