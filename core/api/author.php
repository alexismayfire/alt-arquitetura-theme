<?php

add_action( 'rest_api_init', 'add_author_name_to_JSON' );

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

?>
