<?php

define( 'THEME_VERSION', '1.0.0');

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

add_action( 'wp_enqueue_scripts', 'load_js' );

// Theme options
add_theme_support( 'menus' );
add_theme_support( 'post-thumbnails' );
add_theme_support( 'widgets' );
add_theme_support(
    'html5',
    array(
        'search-form',
        'comment-form',
        'comment-list',
        // 'gallery',
        // 'caption',
        'script',
        'style',
    )
);

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
add_filter( 'image_size_names_choose', 'my_custom_sizes' );

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
            'with_front' => true,
        ),
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-building',
        'show_ui' => true,
        'show_in_rest' => true,
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
function filter_project_content( $content ) {
    // Check if we're inside the main loop in a single Project.
    if ( is_singular( 'project' ) && in_the_loop() && is_main_query() ) {
        return strip_tags( $content, 'p' );
    }
 
    return $content;
}
// add_filter( 'the_content', 'filter_project_content', 1 );

function project_disable_gutenberg($is_enabled, $post_type) {
	
	if ($post_type === 'project') return false;
	
	return $is_enabled;
	
}
// add_filter('use_block_editor_for_post_type', 'project_disable_gutenberg', 10, 2);

// Plugins
add_filter( 'wpcf7_autop_or_not', '__return_false' );

add_action( 'wp_head', function() { ?>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-170483133-1"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-170483133-1');
    </script>
<?php } );

// REST API
function rest_get_image_src( $object, $field_name, $request ) {
    $id = $object['featured_media'];

    if ( $object['type'] === 'project' ) {
        $feat_img_array = wp_get_attachment_image_src( $id, 'full' );
        $orient = $feat_img_array[1] > $feat_img_array[2] ? 'landscape' : 'portrait';
        $feat_img = wp_get_attachment_image_src( $id, 'project-'.$orient );
    } else {
        $feat_img = wp_get_attachment_image_src( $id, 'blog-large' );
    }

    return $feat_img;
}

function add_thumbnail_to_JSON() {
    register_rest_field( 
        ['project', 'post'], // post type
        'featured_image_src', // field name
        array(
            'get_callback'    => 'rest_get_image_src',
            'update_callback' => null,
            'schema'          => null,
        )
    );
}
add_action( 'rest_api_init', 'add_thumbnail_to_JSON' );

function full_category_slug( $base, $slug ) {
    $s = '/' . $slug;
    if ($base) {
        return '/' . $base . $s;
    }

    return $s;
}

function rest_get_categories( $object, $field_name, $request ) {
    $cats = wp_get_post_categories( $object['id'], array( 'fields' => 'all' ) );
    $base = get_option( 'category_base' );
    $cats_obj = [];

    foreach( $cats as $cat ) {
        array_push( $cats_obj, array( 
            'name' => $cat->name,
            'permalink' => full_category_slug( $base, $cat->slug ),
        ));
    }

    return $cats_obj;
}

function add_categories_to_JSON() {
    register_rest_field(
        'post',
        'categories_details',
        array(
            'get_callback'    => 'rest_get_categories',
            'update_callback' => null,
            'schema'          => null,
        )
    );
}
add_action( 'rest_api_init', 'add_categories_to_JSON' );


function add_author_name_to_JSON() {
    register_rest_field(
        'post',
        'author_name',
        array(
            'get_callback'    => function( $object ) {
                return get_author_name( $object['author'] );
            },
            'update_callback' => null,
            'schema'          => null,
        )
    );
}
add_action( 'rest_api_init', 'add_author_name_to_JSON' );

function add_formatted_date_to_JSON() {
    register_rest_field(
        'post',
        'formatted_date',
        array(
            'get_callback'    => function( $object ) {
                return get_the_date( get_option( 'date_format ' ), $object['id'] );
            },
            'update_callback' => null,
            'schema'          => null,
        )
    );
}
add_action( 'rest_api_init', 'add_formatted_date_to_JSON' );

// Load template tags
require get_template_directory() . '/template-tags.php';

// Comments
require get_template_directory() . '/class-alt-walker-comment.php';

function html_comment_field( $name, $label, $type ) {
    $special_classes = array(
        'checkbox' => 'is-two-thirds-widescreen is-flex is-vcentered py-0',
        'textarea' => ''
    );
    
    $css_classes = array_key_exists( $type, $special_classes ) ? $special_classes[$type] : 'is-half-widescreen';

    $output  = "<div class=\"field column is-full $css_classes\">";
    if ( $type === "checkbox" ) {
        $output .= "<input id=\"$name\" name=\"$name\" type=\"checkbox\">";
        $tooltip_text = "Seu comentário passa por uma revisão manual. ";
        $tooltip_text .= "Enquanto verificamos ele, podemos salvar um pequeno cookie no seu dispositivo para que o comentário seja visível para você durante esse período";
        $tooltip = "<span class=\"is-relative tooltip\"><i class=\"fas fa-info-circle\"></i><span class=\"tooltip-content is-size-7 px-2 py-2\">$tooltip_text</span></span>";
        $output .= "<label class=\"is-size-6 is-inline ml-2\" for=\"$name\">$label $tooltip</label>";
        $output .= "</div>";
    } else {
        $output .= "<label class=\"label\" for=\"$name\">$label</label>";
        $output .= "<span class=\"wpcf7-form-control-wrap\">";
        if ( $type === 'textarea' ) {
            $output .= "<textarea class=\"textarea\" id=\"$name\" name=\"$name\" type=\"$type\"></textarea>";
        } else {
            $output .= "<input class=\"input\" id=\"$name\" name=\"$name\" type=\"$type\">";
        }    
        $output .= "</span></div>";
    }

    return $output;
}

?>