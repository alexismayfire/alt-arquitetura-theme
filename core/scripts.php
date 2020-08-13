<?php 

// Load CSS
function load_css() {
    wp_register_style( 'montserrat', 'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap', array(), false, 'all' );
    wp_enqueue_style( 'montserrat' );

    wp_register_style( 'main', get_template_directory_uri() . '/dist/css/main.css', array(), THEME_VERSION, 'all' );
    wp_enqueue_style( 'main' );
}

function load_editor_css() {    
    wp_register_style( 'main', get_template_directory_uri() . '/dist/css/main.css', array(), THEME_VERSION, 'all' );
    wp_enqueue_style( 'main' );

    wp_register_style( 'main_editor', get_template_directory_uri() . '/css/editor.css', array(), THEME_VERSION, 'all' );
    wp_enqueue_style( 'main_editor' );
}

add_action( 'wp_enqueue_scripts', 'load_css' );
add_action( 'enqueue_block_editor_assets', 'load_editor_css' );

// Load Javascript
function load_js() {
    wp_register_script( 'main', get_template_directory_uri() . '/dist/js/main.js', array(), THEME_VERSION, true );
    wp_enqueue_script( 'main' );

    if ( is_singular() ) {
        wp_enqueue_script( 'comment-reply', true );
    }

    wp_register_script ('fontawesome', get_template_directory_uri() . '/dist/js/fa.min.js', array(), '5.13.0', true );
    wp_enqueue_script( 'fontawesome', get_template_directory_uri() . '/dist/js/fa.min.js', array(), '5.13.0', true );
}

add_action( 'wp_enqueue_scripts', 'load_js' );

?>