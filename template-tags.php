<?php

function is_comment_by_post_author( $comment = null ) {

    if ( is_object( $comment ) && $comment->user_id > 0 ) {

        $user = get_userdata( $comment->user_id );
        $post = get_post( $comment->comment_post_ID );

        if ( ! empty( $user ) && ! empty( $post ) ) {

            return $comment->user_id === $post->post_author;

        }
    }
 
    return false;

}

function get_comment_datetime_fmt( $comment, $callback ) {
    $date = get_comment_date( '', $comment );
    $time = get_comment_time( 'H:i' );

    return $callback( sprintf( ( '%1$s às %2$s' ), $date, $time ) );
}

?>