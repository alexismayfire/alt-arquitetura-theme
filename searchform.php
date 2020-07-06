<form action="/" method="get" class="search-form">
    <div class="search-form-control form-control">
        <input class="input" type="text" name="s" id="search" value="<?php the_search_query(); ?>" required /> 
        <button class="button is-dark" type="submit"><i class="fas fa-search"></i></button>
    </div>
</form>