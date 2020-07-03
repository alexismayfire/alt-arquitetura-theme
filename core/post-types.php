<?php 

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
        'exclude_from_search' => true,
    );
    register_post_type( 'project', $args );
}

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

function register_custom_post_types() {
    team_post_type();
    services_post_type();
    projects_post_type();
    projects_taxonomy();
}

add_action( 'init', 'register_custom_post_types' );

?>