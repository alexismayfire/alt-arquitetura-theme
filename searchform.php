<form action="/" method="get">
    <label for="search">Pesquise em nossos posts</label>
    <input type="text" name="s" id="search" value="<?php the_search_query(); ?>" required /> 
    <button type="submit">Buscar</button>
</form>