<?php

// Load CSS
function load_css() {
    wp_register_style( 'materialize', get_template_directory_uri() . '/css/materialize.min.css', array(), false, 'all' );
    wp_enqueue_style( 'materialize' );

    wp_register_style( 'materialize_icons', 'https://fonts.googleapis.com/icon?family=Material+Icons', array(), false, 'all');
    wp_enqueue_style( 'materialize_icons' );

    wp_register_style( 'montserrat', 'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap', array(), false, 'all' );
    wp_enqueue_style( 'montserrat' );

    wp_register_style( 'base', get_template_directory_uri() . '/css/base.css', array(), false, 'all' );
    wp_enqueue_style( 'base' );

    wp_register_style( 'main', get_template_directory_uri() . '/css/main.css', array(), false, 'all' );
    wp_enqueue_style( 'main' );
}

function load_editor_css() {
    /*
    wp_register_style( 'materialize', get_template_directory_uri() . '/css/materialize.min.css', array(), false, 'all' );
    wp_enqueue_style( 'materialize' );
    */
    wp_register_style( 'materialize_icons', 'https://fonts.googleapis.com/icon?family=Material+Icons', array(), false, 'all');
    wp_enqueue_style( 'materialize_icons' );
    
    wp_register_style( 'main', get_template_directory_uri() . '/css/main.css', array(), false, 'all' );
    wp_enqueue_style( 'main' );

    wp_register_style( 'main_editor', get_template_directory_uri() . '/css/editor.css', array(), false, 'all' );
    wp_enqueue_style( 'main_editor' );
}

add_action( 'wp_enqueue_scripts', 'load_css' );
add_action( 'enqueue_block_editor_assets', 'load_editor_css' );

// Load Javascript
function load_js() {
    wp_register_script( 'materialize', get_template_directory_uri() . '/js/materialize.min.js', array(), false, true );
    wp_enqueue_script( 'materialize' );

    wp_register_script( 'main', get_template_directory_uri() . '/js/main.js', array(), false, true );
    wp_enqueue_script( 'main' );
    wp_localize_script('main', 'ajax', array('url' => admin_url('admin-ajax.php')));
}

add_action( 'wp_enqueue_scripts', 'load_js' );

// Theme options
add_theme_support( 'menus' );
add_theme_support( 'post-thumbnails' );
add_theme_support( 'widgets' );

// Menus
register_nav_menus( 
    array(
        'top-menu' => 'Top Menu Location',
        'mobile-menu' => 'Mobile Menu Location',
    )
);

// Media
add_image_size( 'blog-large', 800, 400, true );
add_image_size( 'blog-small', 300, 200, true );
add_image_size( 'project-gallery', 1500, 600, true );
add_image_size( 'project-large', 430, 330, true );
add_image_size( 'project-small', 300, 150, true );

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
    );
    register_taxonomy( 'segments', array( 'project' ), $args );
}
add_action( 'init', 'projects_taxonomy' );

// Shortcodes
function projects_query_args() {
    return array(
        'post_type' => 'project',
        'post_status' => 'publish',
        'posts_per_page' => 3,
        'orderby' => 'post_date',
        'order' => 'DESC',
        'paged' => 1,
    );
}

function projects_carousel_shortcode( $atts, $content = null ) {
	/*
	extract(shortcode_atts(array(
		'cat'     => '',
		'num'     => '3',
		'order'   => 'DESC',
        'orderby' => 'post_date',
	), $atts));    
    */
    $args = projects_query_args();
    $loop = new WP_Query( $args );
    set_query_var( 'loop', $loop ); 
    
    ob_start();
    echo '<div>';
    echo '<div class="project-carousel">';
    get_template_part( 'includes/projects', 'carousel' );
    echo '</div>';
    get_template_part( 'includes/projects', 'carouselnav' );
    echo '</div>';
    wp_reset_postdata();

    return ob_get_clean();
}
add_shortcode('projects_carousel', 'projects_carousel_shortcode');

function projects_filters_shortcode($atts, $content = null) {
    $args = array(
        'taxonomy' => 'segments',
        'orderby' => 'name',
        'order'   => 'ASC'
    );

    $cats = get_categories($args);
    set_query_var( 'cats', $cats );
    ob_start();
    
    get_template_part( 'includes/projects', 'filters' );

    return ob_get_clean();
}
add_shortcode('projects_filters', 'projects_filters_shortcode');

add_action('wp_ajax_myfilter', 'misha_filter_function'); // wp_ajax_{ACTION HERE} 
add_action('wp_ajax_nopriv_myfilter', 'misha_filter_function');
 
function misha_filter_function() {

    $args = projects_query_args();

	if( isset( $_POST['cat'] ) )
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'segments',
				'field' => 'id',
				'terms' => $_POST['cat']
			)
        );

    if( isset( $_POST['page'] ) )
        $args['paged'] = $_POST['page'];
    
    $loop = new WP_Query( $args );
    set_query_var( 'loop', $loop ); 
    set_query_var( 'cat', $_POST['cat']);

    if ( isset( $_POST['replace'] ) ) {
        echo '<div class="project-carousel fade">';
        get_template_part( 'includes/projects', 'carousel' );
        echo '</div>';
        get_template_part( 'includes/projects', 'carouselnav' );
    } else {
        get_template_part( 'includes/projects', 'carousel' );
    }
    die();
}

// filter for Frontend output.
add_filter( 'lazyblock/project-gallery/frontend_callback', 'project_gallery_block_output', 10, 2 );

// filter for Editor output.
add_filter( 'lazyblock/project-gallery/editor_callback', 'project_gallery_block_output', 10, 2 );

if ( ! function_exists( 'project_gallery_block_output' ) ) {
    /**
     * Test Render Callback
     *
     * @param string $output - block output.
     * @param array  $attributes - block attributes.
     */
    function project_gallery_block_output( $output, $attributes ) {
        ob_start();
        // foreach($attributes['images'] as $photo) {
        $photo = array_pop( $attributes['images'] ); ?>
        <div class="gallery">
            <div class="gallery-control gallery-control-left">
                <i class="material-icons md-48">keyboard_arrow_left</i>
            </div>
            <div class="gallery-control gallery-control-right">
                <i class="material-icons md-48">keyboard_arrow_right</i>
            </div>
            <div class="gallery-wrapper">
                <img src="<?php echo $photo["image"]["url"]; ?>" alt="<?php echo $photo["alt-text"]; ?>" />
                <div class="caption-wrapper bg-primary">
                    <h3 class="card-title"><?php the_title(); ?></h3>
                    <p><?php echo $photo["caption"]; ?></p>
                </div>
            </div>
        </div>
        <?php

        return ob_get_clean();
    }
}

?>