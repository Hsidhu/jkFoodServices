
<?php echo controller()->renderPartial('nav/local_tabs', ['activeTab' => 'menus']); ?>

<div class="panel">
    <?php echo controller()->renderComponent('localMealPlanMenu'); ?>
</div>
