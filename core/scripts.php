<?php 

// Load CSS
function load_css() {
    wp_register_style( 'montserrat', 'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap', array(), false, 'all' );
    wp_enqueue_style( 'montserrat' );

    wp_register_style( 'main', get_template_directory_uri() . '/dist/css/main.css', array(), THEME_VERSION, 'all' );
    wp_enqueue_style( 'main' );

    if ( !is_singular( array( 'project', 'post' ) ) ) {
        wp_dequeue_style( 'wp-block-library' );
        wp_dequeue_style( 'wp-block-library-theme' );
    }
}

function load_editor_css() {    
    wp_register_style( 'main', get_template_directory_uri() . '/dist/css/main.css', array(), THEME_VERSION, 'all' );
    wp_enqueue_style( 'main' );

    wp_register_style( 'main_editor', get_template_directory_uri() . '/css/editor.css', array(), THEME_VERSION, 'all' );
    wp_enqueue_style( 'main_editor' );
}

add_action( 'wp_enqueue_scripts', 'load_css' );
add_action( 'enqueue_block_editor_assets', 'load_editor_css' );

// Load Javascript
function load_js() {
    wp_register_script( 'main', get_template_directory_uri() . '/dist/js/main.js', array(), THEME_VERSION, true );
    wp_enqueue_script( 'main' );

    if ( is_singular( 'post' ) ) {
        wp_enqueue_script( 'comment-reply', true );
    }

    wp_register_script ('fontawesome', get_template_directory_uri() . '/dist/js/fa.min.js', array(), '5.13.0', true );
    wp_enqueue_script( 'fontawesome', get_template_directory_uri() . '/dist/js/fa.min.js', array(), '5.13.0', true );

    if ( ! is_admin() ) {
        wp_deregister_script( 'jquery' );
        wp_deregister_script( 'jquery-migrate' );
    }
}

add_action( 'wp_enqueue_scripts', 'load_js' );

// NoScript filter to load CSS in demand
function add_noscript_style_filter( $tag, $handle, $href, $media ) {
    if ( 'main' === $handle || 'montserrat' === $handle ) {
        $noscript = '<noscript>';
        $noscript_close = '</noscript>';
        $css = $tag;
        $tag = $noscript;
        $tag .= $css;
        $tag .= $noscript_close;
    }

    return $tag;
}

add_filter( 'style_loader_tag', 'add_noscript_style_filter', 10, 4 );
add_filter( 'jetpack_implode_frontend_css', '__return_false', 99 );
add_filter( 'jetpack_sharing_counts', '__return_false', 99 );

function critical_styles( $content ) {
    $critical_css = ".button{-webkit-touch-callout:none}.title:not(:last-child),.subtitle:not(:last-child){margin-bottom:1.5rem}.button{-moz-appearance:none;-webkit-appearance:none;align-items:center;border:1px solid transparent;border-radius:0px;box-shadow:none;display:inline-flex;font-size:1rem;height:2.5em;justify-content:flex-start;line-height:1.5;padding-bottom:calc(0.5em - 1px);padding-left:calc(0.75em - 1px);padding-right:calc(0.75em - 1px);padding-top:calc(0.5em - 1px);position:relative;vertical-align:top}.has-text-centered{text-align:center!important}@media screen and (min-width:1024px){.is-hidden-desktop{display:none!important}}html,body,ul,li,h1,h2{margin:0;padding:0}h1,h2{font-size:100%;font-weight:normal}ul{list-style:none}input{margin:0}html{box-sizing:border-box}*,*::before,*::after{box-sizing:inherit}img{height:auto;max-width:100%}html{background-color:white;font-size:16px;-moz-osx-font-smoothing:grayscale;-webkit-font-smoothing:antialiased;min-width:300px;overflow-x:hidden;overflow-y:scroll;text-rendering:optimizeLegibility;text-size-adjust:100%}header,section{display:block}body,input{font-family:BlinkMacSystemFont,-apple-system,\"Segoe UI\",\"Roboto\",\"Oxygen\",\"Ubuntu\",\"Cantarell\",\"Fira Sans\",\"Droid Sans\",\"Helvetica Neue\",\"Helvetica\",\"Arial\",sans-serif}body{color:#4a4a4a;font-size:1em;font-weight:400;line-height:1.5}a{color:#3273dc;text-decoration:none}img{height:auto;max-width:100%}span{font-style:inherit;font-weight:inherit}.hero{align-items:stretch;display:flex;flex-direction:column;justify-content:space-between}.hero .navbar{background:none}.hero.is-fullheight .hero-body{align-items:center;display:flex}.hero.is-fullheight .hero-body>.container{flex-grow:1;flex-shrink:1}.hero.is-fullheight{min-height:100vh}.hero-head{flex-grow:0;flex-shrink:0}.hero-body{flex-grow:1;flex-shrink:0;padding:3rem 1.5rem}.navbar{background-color:white;min-height:3.25rem;position:relative;z-index:30}.navbar>.container{align-items:stretch;display:flex;min-height:3.25rem;width:100%}.navbar-brand{align-items:stretch;display:flex;flex-shrink:0;min-height:3.25rem}.navbar-burger{color:#4a4a4a;display:block;height:3.25rem;position:relative;width:3.25rem;margin-left:auto}.navbar-burger span{background-color:currentColor;display:block;height:1px;left:calc(50% - 8px);position:absolute;transform-origin:center;width:16px}.navbar-burger span:nth-child(1){top:calc(50% - 6px)}.navbar-burger span:nth-child(2){top:calc(50% - 1px)}.navbar-burger span:nth-child(3){top:calc(50% + 4px)}.navbar-menu{display:none}@media screen and (max-width:1023px){.navbar>.container{display:block}.navbar-menu{background-color:white;box-shadow:0 8px 16px rgba(10,10,10,0.1);padding:0.5rem 0}}@media screen and (min-width:1024px){.navbar,.navbar-menu{align-items:stretch;display:flex}.navbar{min-height:3.25rem}.navbar-burger{display:none}.navbar-menu{flex-grow:1;flex-shrink:0}.navbar>.container .navbar-brand{margin-left:-0.75rem}.navbar>.container .navbar-menu{margin-right:-0.75rem}}.button{background-color:white;border-color:#dbdbdb;border-width:1px;color:#363636;justify-content:center;padding-bottom:calc(0.5em - 1px);padding-left:1em;padding-right:1em;padding-top:calc(0.5em - 1px);text-align:center;white-space:nowrap}.button.is-dark{background-color:#363636;border-color:transparent;color:#fff}.container{flex-grow:1;margin:0 auto;position:relative;width:auto}@media screen and (min-width:1024px){.container{max-width:960px}}@media screen and (min-width:1216px){.container{max-width:1152px}}@media screen and (min-width:1408px){.container{max-width:1344px}}.title,.subtitle{word-break:break-word}.title{color:#363636;font-size:2rem;font-weight:600;line-height:1.125}.title:not(.is-spaced)+.subtitle{margin-top:-1.25rem}.subtitle{color:#4a4a4a;font-size:1.25rem;font-weight:400;line-height:1.25}#home{background-repeat:no-repeat;background-size:cover;background-attachment:fixed}@media screen and (max-width:768px){#home{background-size:auto 100vh}}.navbar .container{align-items:center;justify-content:center}.navbar-menu{align-items:center;justify-content:center;padding-top:1.2em}.navbar-social-container{justify-content:space-evenly}@media screen and (min-width:1216px){.navbar-social-container{margin-left:2em}}.navbar-brand{align-items:center}.navbar-brand img{max-height:120px}@media screen and (min-width:1024px){.navbar-menu{flex-grow:0}}.navbar-items-container{display:flex;justify-content:space-evenly}.navbar-items-container .menu-item{margin:0 1em}.navbar-items-container .menu-item a{color:#323232;font-family:'Apertura';font-size:14px;font-weight:bold;letter-spacing:4px;text-transform:lowercase}@media screen and (max-width:768px){.navbar-items-container .menu-item a{font-size:1.5rem}}.navbar-items-container .menu-item-icon{margin:0 0.5em}@media screen and (max-width:768px){.carousel-item-navright,.carousel-item-navleft{bottom:20em;position:absolute;z-index:10}.carousel-item-navright{right:2em}.carousel-item-navleft{left:2em}}.wpcf7-response-output{font-size:0.8em;padding:1em!important}body{overflow:hidden;background-color:#fafafa;font-family:Montserrat,sans-serif;font-size:18px;line-height:150%}a{color:rgba(50,50,50,0.6)}input{font-family:'Montserrat'}.home-cta-title{font-family:'Apertura';text-transform:lowercase}.home-cta-tagline{font-family:'Apertura';text-transform:lowercase}.button-cta{height:3em;font-weight:800;text-transform:uppercase}";
    echo "<style>" . $critical_css . "</style>";
}

add_action( 'wp_head', 'critical_styles' );

?>