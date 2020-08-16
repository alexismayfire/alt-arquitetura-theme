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
        $output .= "<span class=\"wpcf7-form-control-wrap is-relative\">";
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
    $alternative_featured = get_field( 'projeto_destaque' );
    if ( $alternative_featured ) {
        $image = $alternative_featured['sizes']['project-small'];
    } else {
        $image = get_the_post_thumbnail_url( $post_id, 'project-small' );
    }
    return array(
        'permalink' => get_the_permalink( $post_id ),
        'img' => $image,
        'title' => get_the_title( $post_id ),
    );
}

function get_post_author_tag() { 
    $output = '';
    $url = get_the_author_url();
    $author = get_the_author();

    if ( $url ) {
        $output .= '<a href="'.$url.'" target="_blank" class="has-text-primary has-text-weight-semibold">';
        $output .= '<span class="mr-1">'.$author.'</span>';
        $output .= '<i class="fab fa-md fa-linkedin"></i>';
        $output .= '</a>';
    } else {
        $output = '<span class="has-text-primary has-text-weight-semibold">'.$author.'</span>';
    }

    return $output;
}

?>