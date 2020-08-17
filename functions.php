<?php

define( THEME_VERSION, '1.1.1');

require_once get_template_directory() . '/core/post-types.php';
require_once get_template_directory() . '/core/rest-api.php';
require_once get_template_directory() . '/core/scripts.php';
require_once get_template_directory() . '/core/options.php';

// Theme options
add_theme_support( 'menus' );
add_theme_support( 'post-thumbnails' );
add_theme_support( 'widgets' );
add_theme_support( 'title-tag' );
add_theme_support(
    'html5',
    array(
        'search-form',
        'comment-form',
        'comment-list',
        'script',
        'style',
    )
);

// Menus
register_nav_menus( 
    array(
        'main' => 'Menu Principal',
        'social-networks' => 'Redes Sociais',
    )
);

// Media
add_image_size( 'team', 160, 235, true );
add_image_size( 'blog-large', 1000, 400, true );
add_image_size( 'blog-small', 300, 200, true );
add_image_size( 'project-gallery', 1500, 600, true );
add_image_size( 'project-landscape', 700, 500, false );
add_image_size( 'project-portrait', 500, 700, false );
add_image_size( 'project-large', 500, 385, true );
add_image_size( 'project-small', 300, 150, true );

function filter_site_upload_size_limit( $size ) {
    return 5 * 1024 * 1024;
}
add_filter( 'upload_size_limit', 'filter_site_upload_size_limit', 20 );

// Sidebars
function custom_sidebars() {
    register_sidebar( array(
        'name' => 'Blog Sidebar',
        'id' => 'blog-sidebar',
        'before_title' => '<h4>',
        'after_title' => '</h4>'
    ) );
}
add_action( 'widgets_init', 'custom_sidebars' );

// Blocks
add_filter( 'init', 'remove_editor' );

function remove_editor() {
    if ( isset( $_GET['post'] ) ) {
        $id = $_GET['post'];
        $template = get_post_meta( $id, '_wp_page_template', true );
        // If is front page, $template will be empty 
        switch( $template ) {
            case 'page-projects.php':
            case '':
                remove_post_type_support( 'page', 'editor' );
                break;
            default:
                break;
        }
    }
}

add_filter( 'render_block', 'render_pinterest_button_project_image', 10, 2 );

function render_pinterest_button_project_image( $block_content, $block ) {
	if ( 'core/image' !== $block['blockName'] || ! is_singular( 'project' ) ) {
		return $block_content;
    }

    $html = $block['innerHTML'];

    $start = strpos( $html, 'src=' ) + 5;
    $end = strpos( $html, '"', $start ) - $start;
    $media = substr( $html, $start, $end );

    $start = strpos( $html, '<figcaption>');
    $end = strpos( $html, '</figcaption>');
    $caption = strip_tags( substr( $html, $start, $end ) );
    $site_name = get_bloginfo( 'name' );
    $title = get_the_title();
    $description = $site_name . ' | Projeto de Arquitetura | ' . $title; 

    $pin_link  = 'https://pt.pinterest.com/pin/create/button/';
    $pin_link .= '?url=' . get_the_permalink();
    $pin_link .= '&media=' . $media;
    $pin_link .= '&description=' . $description;
    
    $output  = '<div class="image-block-pinterest is-relative">';
    $output .= '<a class="button-pinterest is-size-6" target="_blank" href="' . $pin_link . '">';
    $output .= '<i class="fab fa-md fa-pinterest-p"></i>';
    $output .= '<span class="ml-2 has-text-weight-semibold">Salvar</span>';
    $output .= '</a>';
	$output .= $block_content;
    $output .= '</div>';
    
	return $output;
}

// Posts action
function query_all_posts( $query ) {
    if ( ! is_admin() && $query->is_main_query() ) {
        if ( is_archive() || is_category() || is_home() || is_search() ) {
            $query->set( 'posts_per_page', -1 );
        }
    }
}
add_action( 'pre_get_posts', 'query_all_posts' );

// Plugins
add_filter( 'wpcf7_autop_or_not', '__return_false' );
add_filter( 'wpcf7_load_js', '__return_false' );
add_filter( 'wpcf7_load_css', '__return_false' );

// Load template tags
require_once get_template_directory() . '/template-tags.php';

// Comments Custom Walker
require_once get_template_directory() . '/class-alt-walker-comment.php';

?>