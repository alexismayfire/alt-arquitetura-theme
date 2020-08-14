
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
</body>
</html>