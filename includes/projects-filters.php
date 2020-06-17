<div>
    <a href="#" class="menu-link primary active" data-filter-category="">Todos</a>
    <svg>
        <line x1="0" y1="0" x2="30" y2="0" style="stroke:rgb(50,50,50);stroke-width:2"></line>
    </svg>
</div>
<?php
    foreach($cats as $cat): 
?>
    <div>
        <a href="#" class="menu-link primary" data-filter-category="<?php echo $cat->term_id; ?>">
            <?php echo $cat->name ?>
        </a>
        <svg>
            <line x1="0" y1="0" x2="30" y2="0" style="stroke:rgb(50,50,50);stroke-width:2"></line>
        </svg>
    </div>
<?php endforeach; ?>