
<div class="menu-list">
    <?php echo controller()->renderPartial('@items', ['menuItems' => $menuList]); ?>
    
    <div class="pagination-bar text-right">
        <div class="links"><?php echo $menuList->links(); ?></div>
    </div>
</div>

