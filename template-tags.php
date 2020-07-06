<?php

function html_comment_field( $name, $label, $type ) {
    $special_classes = array(
        'checkbox' => 'is-two-thirds-widescreen',
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

function get_related_meta( $post_id ) {
    return array(
        'permalink' => get_the_permalink( $post_id ),
        'img' => get_the_post_thumbnail_url( $post_id, 'project-small' ),
        'title' => get_the_title( $post_id ),
    );
}

?>