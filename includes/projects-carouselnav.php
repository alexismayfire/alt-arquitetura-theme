<div class="indicators">
<?php 
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    for($i = 0; $i < $loop->max_num_pages; $i++): 
        if( $paged === $i + 1 ): ?>
            <span class="indicator-item active" data-page="<?php echo $i + 1; ?>">
                <svg height="36" width="36">
                <circle cx="18" cy="18" r="6" />
                </svg>
            </span>
        <?php else: ?>
            <span class="indicator-item" data-page="<?php echo $i + 1; ?>">
                <svg height="36" width="36">
                    <circle cx="18" cy="18" r="6" />
                </svg>
            </span>
        <?php endif; ?>
<?php endfor; ?>
</div>