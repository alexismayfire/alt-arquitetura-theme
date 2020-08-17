<?php
$whatsapp_number = altarq_get_theme_option( 'whatsapp_number' );
$whatsapp_message = altarq_get_theme_option( 'whatsapp_message' );

if ( $whatsapp_number ):
  $whatsapp_link = 'https://wa.me/55'.$whatsapp_number.'?text='.str_replace( PHP_EOL, '%0a', $whatsapp_message );
?>

<div class="button-whatsapp <?php if ( is_home() | is_archive() | is_search() | is_page_template( 'page-projects.php' ) ): echo 'filter-spacing'; endif; ?>">
  <a href="<?php echo $whatsapp_link; ?>" target="_blank">
    <div class="fa-3x">
      <span class="fa-layers fa-fw">
        <i class="fas fa-circle"></i>
        <i class="fab fa-whatsapp" data-fa-transform="shrink-3"></i>
      </span>
    </div>
  </a>
</div>

<?php endif; ?>

<?php wp_footer(); ?>

<script type="text/javascript">
  var links = document.getElementsByTagName('link');
  var godefer = links[links.length - 1];
  var fontCSS = document.createElement('link');
  fontCSS.rel = 'stylesheet';
  fontCSS.id = 'montserrat-css';
  fontCSS.href = 'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap';
  godefer.parentNode.appendChild(fontCSS);

  var themeMainCSS = document.createElement('link');
  themeMainCSS.rel = 'stylesheet';
  themeMainCSS.id = 'main-css';
  themeMainCSS.href = '<?php echo get_template_directory_uri() . '/dist/css/main.css?ver=' . THEME_VERSION; ?>';
  themeMainCSS.type = 'text/css';
  godefer.parentNode.appendChild(themeMainCSS);
</script>

<?php if ( ! is_user_logged_in() && altarq_get_theme_option( 'ga_code' ) && ! altarq_get_theme_option( 'ga_debug' ) ): ?>

<script>
  document.addEventListener( 'wpcf7mailsent', function( event ) {
   gtag('event', 'envio', {'event_category': 'Formulário'});
  }, false );
</script>

<?php endif; ?>
</body>
</html>