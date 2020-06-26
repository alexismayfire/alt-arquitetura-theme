<?php

add_action( 'rest_api_init', 'add_formatted_date_to_JSON' );

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

?>