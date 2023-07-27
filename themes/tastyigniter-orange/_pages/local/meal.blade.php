---
title: 'main::lang.local.menus.title'
permalink: '/:location?local/meal'
description: ''
layout: mealplanlayout

'[localMealPlanMenu]':
    menusPerPage: 200
    showMenuImages: 0
    menuImageWidth: 95
    menuImageHeight: 80

---
@partial('nav/local_tabs', ['activeTab' => 'menus'])

<div class="panel">
    @component('localMealPlanMenu')
</div>
