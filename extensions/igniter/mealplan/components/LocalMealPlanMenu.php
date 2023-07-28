<?php

namespace Igniter\MealPlan\Components;

use Admin\Models\Locations_model;
use Igniter\Local\Facades\Location;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Igniter\MealPlan\Models\MealPlan;
use Igniter\MealPlan\Models\MealPlanAddon;
use Igniter\MealPlan\Models\MealPlanOptions;
use Igniter\MealPlan\Classes\CartManager;
use Igniter\Flame\Cart\Facades\Cart;

// https://tastyigniter.com/docs/extend/building-components

class LocalMealPlanMenu extends \System\Classes\BaseComponent
{
    use \Main\Traits\UsesPage;

    protected $menuListCategories = [];

    public function initialize()
    {
        //$this->cartManager = CartManager::instance();
    }

    public function defineProperties()
    {
        return [
            'menusPerPage' => [
                'label' => 'Menus Per Page',
                'type' => 'number',
                'default' => 20,
                'validationRule' => 'required|integer',
            ],
            'showMenuImages' => [
                'label' => 'Show Menu Item Images',
                'type' => 'switch',
                'default' => false,
                'validationRule' => 'required|boolean',
            ],
            'menuImageWidth' => [
                'label' => 'Menu Thumb Width',
                'type' => 'number',
                'span' => 'left',
                'default' => 95,
                'validationRule' => 'integer',
            ],
            'menuImageHeight' => [
                'label' => 'Menu Thumb Height',
                'type' => 'number',
                'span' => 'right',
                'default' => 80,
                'validationRule' => 'integer',
            ],
            'localNotFoundPage' => [
                'label' => 'lang:igniter.local::default.label_redirect',
                'type' => 'select',
                'options' => [static::class, 'getThemePageOptions'],
                'default' => 'home',
                'validationRule' => 'regex:/^[a-z0-9\-_\/]+$/i',
            ]
        ];
    }

    public function onRun()
    {
        $this->page['showMenuImages'] = $this->property('showMenuImages');
        $this->page['menuImageWidth'] = $this->property('menuImageWidth');
        $this->page['menuImageHeight'] = $this->property('menuImageHeight');
        $this->page['menuList'] = $this->loadList();
        $this->page['cartCount'] = Cart::count();
    }

    protected function loadList()
    {
        $list = MealPlan::with([
            'mealplan_addon', 'media'
        ])->listFrontEnd([
            'page' => $this->param('page'),
            'pageLimit' => $this->property('menusPerPage'),
            'sort' => $this->property('sort', 'created_at asc')
        ]);

        $this->mapIntoObjects($list);

        return $list;
    }

    protected function mapIntoObjects($list)
    {
        $collection = $list->getCollection()->map(function ($menuItem) {
            return $this->createMenuItemObject($menuItem);
        });

        $list->setCollection($collection);

        return $list;
    }

    // Special price can be set here
    public function createMenuItemObject($mealPlanItem)
    {
        $object = new \stdClass();

        $object->hasThumb = $mealPlanItem->hasMedia('thumb');
        $object->hasOptions = $mealPlanItem->hasOptions();

        $object->model = $mealPlanItem;

        return $object;
    }

}
