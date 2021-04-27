<?php
/*
Plugin Name: VD Gallery
Plugin URI: https://velocitydeveloper.com/
description: Plugin Galeri dari Velocity Developer
Version: 1.0.2
Author: Velocity Developer
Author URI: https://velocitydeveloper.com/kontak-kami/
License: GPL2
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'VD_GALLERY_VERSION', '1.0.2' );

/**
 * Register post type vdgallery
 */
add_action('init', 'vdgallery_post_type');
function vdgallery_post_type() {
    register_post_type('vdgallery', array(
        'labels' => array(
            'name' => 'VD Gallery',
            'singular_name' => 'vdgallery',
            'add_new' => 'Tambah Galeri Baru',
            'add_new_item' => 'Tambah Galeri Baru',
            'edit_item' => 'Edit Galeri',
            'view_item' => 'Lihat Galeri',
            'search_items' => 'Cari Galeri',
            'not_found' => 'Tidak ditemukan',
            'not_found_in_trash' => 'Tidak ada galeri di kotak sampah'
        ),
        'menu_icon' => 'dashicons-images-alt2',
        'public' => true,
        'exclude_from_search' => true,
        'show_in_admin_bar'   => false,
        'show_in_nav_menus'   => false,
        'publicly_queryable'  => false,
        'query_var'           => false,
        'supports' => array(
            'title',
        ),
   ));
}

/**
 * Call file media Upload.
 * shortcode
 */
add_action('admin_enqueue_scripts', function() {
    global $post;

    wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');
    wp_enqueue_style('thickbox');

    wp_enqueue_media(array(
        'post' => $post->ID,
    ));
});


/**
 * Register meta boxes.
 * vdgallery-meta
 */
require plugin_dir_path( __FILE__ ) . 'admin/meta-box-vdgallery.php';

if ( ! function_exists( 'admin_vdgallery_enqueue' ) ) {
    /**
     * Register script to dashboard area.
     * vdgallery-script
     */
    function admin_vdgallery_enqueue($hook) {
        wp_enqueue_script('admin-vdgallery-script', plugin_dir_url(__FILE__) . 'admin/js/script.js');
        wp_enqueue_style( 'admin-vdgallery-style', plugin_dir_url(__FILE__) . 'admin/css/admin.css');
    }
}// endif function_exists( 'admin_vdgallery_enqueue' ).
add_action('admin_enqueue_scripts', 'admin_vdgallery_enqueue');

if ( ! function_exists( 'vdgallery_scripts_enqueue' ) ) {
	/**
	 * Load plugin sources.
	 */
	function vdgallery_scripts_enqueue() {
        wp_enqueue_style( 'flickity-styles', 'https://unpkg.com/flickity@2/dist/flickity.min.css', array(), VD_GALLERY_VERSION, false );
		wp_enqueue_style( 'magnific-popup-styles', 'https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.0.0/magnific-popup.min.css', array(), VD_GALLERY_VERSION, false );
		wp_enqueue_style( 'vdgallery-styles', plugin_dir_url(__FILE__) . 'public/css/style.css', array(), VD_GALLERY_VERSION, false );
        wp_enqueue_script( 'jquery');
		wp_enqueue_script( 'flickity-script', 'https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js', array(), VD_GALLERY_VERSION, true );
		wp_enqueue_script( 'magnific-popup-script', 'https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.0.0/jquery.magnific-popup.min.js', array(), VD_GALLERY_VERSION, true );
        wp_enqueue_script( 'vdgallery-script', plugin_dir_url(__FILE__) . 'public/js/script.js', array(), VD_GALLERY_VERSION, true );
	}
} // endif function_exists( 'vdgallery_scripts_enqueue' ).
add_action( 'wp_enqueue_scripts', 'vdgallery_scripts_enqueue' );
 
/**
 * Custom columns vdgallery.
 */
add_filter( 'manage_vdgallery_posts_columns', 'set_custom_edit_vdgallery_columns' );
function set_custom_edit_vdgallery_columns($columns) {
    $columns['sgaleri'] = __( 'Shortcode Galeri', 'vdgallery' );
    $columns['sslideshow'] = __( 'Shortcode Slideshow', 'vdgallery' );
    return $columns;
}
add_action( 'manage_vdgallery_posts_custom_column' , 'custom_vdgallery_column', 10, 2 );
function custom_vdgallery_column( $column, $post_id ) {
    switch ( $column ) {
        case 'sgaleri' :
            echo '[vdgallery id="'.$post_id.'"]';
            break;
        case 'sslideshow' :
            echo '[vdgalleryslide id="'.$post_id.'"]';
            break;
    }
}

/**
 * Register file public.
 * shortcode
 */
require plugin_dir_path( __FILE__ ) . 'public/vdgallery-public.php';