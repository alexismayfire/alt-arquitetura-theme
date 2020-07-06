<?php 


// Load CSS
function load_css() {
    wp_register_style( 'montserrat', 'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap', array(), false, 'all' );
    wp_enqueue_style( 'montserrat' );

    wp_register_style( 'main', get_template_directory_uri() . '/css/main.css', array(), THEME_VERSION, 'all' );
    wp_enqueue_style( 'main' );
}

function load_editor_css() {    
    wp_register_style( 'main', get_template_directory_uri() . '/css/main.css', array(), THEME_VERSION, 'all' );
    wp_enqueue_style( 'main' );

    wp_register_style( 'main_editor', get_template_directory_uri() . '/css/editor.css', array(), THEME_VERSION, 'all' );
    wp_enqueue_style( 'main_editor' );
}

add_action( 'wp_enqueue_scripts', 'load_css' );
add_action( 'enqueue_block_editor_assets', 'load_editor_css' );

// Load Javascript
function load_js() {
    wp_register_script( 'main', get_template_directory_uri() . '/js/main.js', array(), THEME_VERSION, true );
    wp_enqueue_script( 'main' );
    wp_localize_script( 'main', 'ajax', array('url' => admin_url('admin-ajax.php')));
    wp_localize_script( 'main', 'restApi', array('url' => rest_url() ) );

    wp_register_script( 'projects', get_template_directory_uri() . '/js/projects.js', array(), THEME_VERSION, true );
    wp_register_script( 'blog', get_template_directory_uri() . '/js/blog.js', array(), THEME_VERSION, true );
}

// Comments scripts, only in blog
function load_comments_js() {
    if ( is_singular() ) {
        wp_enqueue_script('comment-reply');
    }
}

add_action( 'wp_enqueue_scripts', 'load_js' );
add_action( 'wp_enqueue_scripts', 'load_comments_js' );



?>