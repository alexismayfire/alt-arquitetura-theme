<?php

add_action( 'rest_api_init', 'add_categories_to_JSON' );

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

function rest_get_categories( $object, $field_name, $request ) {
    $cats = wp_get_post_categories( $object['id'], array( 'fields' => 'all' ) );
    $base = get_option( 'category_base' );
    $cats_obj = [];

    foreach( $cats as $cat ) {
        array_push( $cats_obj, array( 
            'name' => $cat->name,
            'permalink' => $base ? '/'.$base.$slug : '/'.$slug,
        ));
    }

    return $cats_obj;
}

function full_category_slug( $base, $slug ) {
    $s = '/' . $slug;
    if ($base) {
        return '/' . $base . $s;
    }

    return $s;
}

?>