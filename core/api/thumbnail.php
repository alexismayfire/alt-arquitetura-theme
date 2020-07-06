<?php

add_action( 'rest_api_init', 'add_thumbnail_to_JSON' );

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

?>