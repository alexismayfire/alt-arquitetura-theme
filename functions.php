<?php

// Load CSS
function load_css() {
    wp_register_style( 'montserrat', 'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap', array(), false, 'all' );
    wp_enqueue_style( 'montserrat' );

    wp_register_style( 'main', get_template_directory_uri() . '/css/main.css', array(), false, 'all' );
    wp_enqueue_style( 'main' );
}

function load_editor_css() {    
    wp_register_style( 'main', get_template_directory_uri() . '/css/main.css', array(), false, 'all' );
    wp_enqueue_style( 'main' );

    wp_register_style( 'main_editor', get_template_directory_uri() . '/css/editor.css', array(), false, 'all' );
    wp_enqueue_style( 'main_editor' );
}

add_action( 'wp_enqueue_scripts', 'load_css' );
add_action( 'enqueue_block_editor_assets', 'load_editor_css' );

// Load Javascript
function load_js() {
    wp_register_script( 'main', get_template_directory_uri() . '/js/main.js', array(), false, true );
    wp_enqueue_script( 'main' );
    wp_localize_script( 'main', 'ajax', array('url' => admin_url('admin-ajax.php')));
    wp_localize_script( 'main', 'restApi', array('url' => rest_url() ) );

    wp_register_script( 'projects', get_template_directory_uri() . '/js/projects.js', array(), false, true );
}

add_action( 'wp_enqueue_scripts', 'load_js' );

// Theme options
add_theme_support( 'menus' );
add_theme_support( 'post-thumbnails' );
add_theme_support( 'widgets' );

// Menus
register_nav_menus( 
    array(
        'top-menu' => 'Menu Desktop',
        'mobile-menu' => 'Menu Mobile',
    )
);

// Media
add_image_size( 'team', 160, 235, true );
add_image_size( 'blog-large', 800, 400, true );
add_image_size( 'blog-small', 300, 200, true );
add_image_size( 'project-gallery', 1500, 600, true );
add_image_size( 'project-landscape', 700, 500, false );
add_image_size( 'project-portrait', 500, 700, false );
add_image_size( 'project-large', 430, 330, true );
add_image_size( 'project-small', 300, 150, true );

function my_custom_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'project-large' => __( 'Projeto Home' ),
    ) );
}
// add_filter( 'image_size_names_choose', 'my_custom_sizes' );

function filter_site_upload_size_limit( $size ) {
    return 5 * 1024 * 1024;
}
add_filter( 'upload_size_limit', 'filter_site_upload_size_limit', 20 );

@ini_set( 'upload_max_size' , '5M' );

// Sidebars
function custom_sidebars() {
    register_sidebar( array(
        'name' => 'Blog Sidebar',
        'id' => 'blog-sidebar',
        'before_title' => '<h4 class="card-title">',
        'after_title' => '</h4>'
    ) );
}
add_action( 'widgets_init', 'custom_sidebars' );

// Custom Post Types
function team_post_type() {
    $args = array(
        'labels' => array(
            'name' => 'Equipe',
            'singular_name' => 'Colaborador',
        ),
        'public' => false,
        'supports' => array('title', 'editor', 'thumbnail'),
        'menu_icon' => 'dashicons-networking',
        'show_ui' => true,
    );
    register_post_type( 'team', $args );
}
add_action( 'init', 'team_post_type' );

function services_post_type() {
    $args = array(
        'labels' => array(
            'name' => 'Serviços',
            'singular_name' => 'Serviço',
        ),
        'public' => false,
        'supports' => array('title', 'editor', 'custom-fields'),
        'menu_icon' => 'dashicons-screenoptions',
        'show_ui' => true,
    );
    register_post_type( 'services', $args );
}
add_action( 'init', 'services_post_type' );

function projects_post_type() {
    $args = array(
        'labels' => array(
            'name' => 'Projetos',
            'singular_name' => 'Projeto',
            'add_new' => 'Novo projeto',
            'add_new_item' => 'Adicionar Novo Projeto',
        ),
        'public' => true,
        'rewrite' => array(
            'slug' => 'projetos',
            'with_front' => false,
        ),
        'has_archive' => 'projetos',
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-building',
        'show_ui' => true,
        'show_in_rest' => true,
        'publicly_queryable' => true,
    );
    register_post_type( 'project', $args );
}
add_action( 'init', 'projects_post_type' );

function projects_taxonomy() {
    $args = array(
        'labels' => array(
            'name' => 'Segmentos',
            'singular_name' => 'Segmento',
        ),
        'hierarchical' => true,
        'public' => true,
        'show_in_rest' => true,
    );
    register_taxonomy( 'segments', array( 'project' ), $args );
}
add_action( 'init', 'projects_taxonomy' );

// Filters
add_filter( 'the_content', 'filter_project_content', 1 );
 
function filter_project_content( $content ) {
    // Check if we're inside the main loop in a single Project.
    if ( is_singular( 'project' ) && in_the_loop() && is_main_query() ) {
        return strip_tags( $content, 'p' );
    }
 
    return $content;
}

// Plugins
add_filter('wpcf7_autop_or_not', '__return_false');

// REST API
function get_image_src( $object, $field_name, $request ) {
    /*
    $feat_img_array = wp_get_attachment_image_src(
        $object['featured_media'], // Image attachment ID
        'project-gallery',  // Size.  Ex. "thumbnail", "large", "full", etc..
        true // Whether the image should be treated as an icon.
    );
    */
    $id = $object['featured_media'];
    $feat_img_array = wp_get_attachment_image_src( $id, 'full' );
    $orient = $feat_img_array[1] > $feat_img_array[2] ? 'landscape' : 'portrait';
    $feat_img = wp_get_attachment_image_src( $id, 'project-'.$orient );

    return $feat_img[0];
}

function add_thumbnail_to_JSON() {
    //Add featured image
    register_rest_field( 
        'project', // Where to add the field (Here, blog posts. Could be an array)
        'featured_image_src', // Name of new field (You can call this anything)
        array(
            'get_callback'    => 'get_image_src',
            'update_callback' => null,
            'schema'          => null,
        )
    );
}

add_action( 'rest_api_init', 'add_thumbnail_to_JSON' );

?>