<?php
/**
Plugin Name: Vacancies Lister by Vladan
Plugin URI: http://www.givemejobtoday.com
Description: Adding Job Vacancies To Your Website
Author: Vladan Paunovic
Author URI: http://www.givemejobtoday.com
 */


$dir = plugin_dir_path(__FILE__);

require_once $dir . 'inc/custom-type.php';
require_once $dir . 'inc/custom-taxonomy.php';
require_once $dir . 'inc/meta-boxes.php';
require_once $dir . 'inc/settings.php';
require_once $dir . 'inc/shortcodes.php';

function vp_admin_enqueue_scripts($dir)
{
    global $pagenow, $typenow;

    if ($typenow == 'job') {
        wp_enqueue_style('vp-plugin-css', plugins_url('css/styles.css', __FILE__));
    }

    if ( ($pagenow == 'post.php' || $pagenow == 'post-new.php') && $typenow == 'job' ) {
        wp_enqueue_script( 'vp-plugin-js', plugins_url('js/admin.js', __FILE__), array( 'jquery', 'jquery-ui-datepicker' ), '20150204', true );
        wp_enqueue_style('vp-jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
    }

    if ( $pagenow == 'edit.php' && $typenow == 'job' ) {
        wp_enqueue_script( 'vp-reorder-js', plugins_url('js/reorder.js', __FILE__), array('jquery', 'jquery-ui-sortable'), '20160101', true );
        wp_localize_script( 'vp-reorder-js', 'WP_DIARY_LIST', array(
            'security' => wp_create_nonce('wp-diary-order'),
            'success' => 'Changes saved.',
            'error' => '<strong>Error:</strong> Changes are not saved.'
        ) );
    }
}

add_action('admin_enqueue_scripts', 'vp_admin_enqueue_scripts');

// Include templates
function dwwp_load_templates( $original_template ) {
    flush_rewrite_rules();
    if ( get_query_var( 'post_type' ) !== 'job' ) {
        return $original_template;
    }
    if ( is_archive() || is_search() ) {
        if ( file_exists( get_stylesheet_directory(). '/archive-diary.php' ) ) {
            return get_stylesheet_directory() . '/archive-diary.php';
        } else {
            //return plugin_dir_path( __FILE__ ) . 'templates/archive-diary.php';
            return $original_template;
        }
    } elseif(is_singular('job')) {
        if (  file_exists( get_stylesheet_directory(). '/single-diary.php' ) ) {
            return get_stylesheet_directory() . '/single-diary.php';
        } else {
            wp_enqueue_style('vp-template-style', plugins_url('css/frontend-styles.css', __FILE__));
            return plugin_dir_path( __FILE__ ) . 'templates/single-diary.php';
        }
    }else{
        return get_page_template();
    }
    return $original_template;
}
add_action( 'template_include', 'dwwp_load_templates' );
